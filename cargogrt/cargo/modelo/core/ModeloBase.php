<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * ModeloBase es una clase de la cual deben heredar todas las clases que representan 
 * una tabla en el modelo de entidad relación, y que me facilita para realizar consultas
 * y tratar los inserts y updates que es en lo que se resume
 *
 * @author pacho
 */
require_once __DIR__ . '/Base.php';
require_once __DIR__ . '/../enumeraciones/Join.php';
require_once __DIR__ . '/../enumeraciones/Order.php';
require_once __DIR__ . '/../enumeraciones/ElementoSQL.php';
require_once __DIR__ . '/../enumeraciones/OperadorLogico.php';
require_once __DIR__ . '/../enumeraciones/ComparacionSQL.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";
require_once __DIR__ . '/../enumeraciones/UnidadTiempo.php';
require_once __DIR__ . '/../../util/ObjectUtil.php';

class ModeloBase extends Base {
    
    const DEFAULT_ALIAS_TABLE = "tab";
    const DEFAULT_ALIAS_NOTHING = "tab_nothing";
    const DEFAULT_ALIAS_JOINED = "tab_join";
    const DEFAULT_ALIAS_ENUM = "enum";
    const TABLE_TYPE_ENTIDAD = 'table_type_entidad';
    const TABLE_TYPE_RELACION = 'table_type_related';
    
    var $table_name;    //Nombre de la table
    var $fields;        //array('column1', 'column2', 'column3'); //$this->fields['column1'] = array('pkey' => 'y');
    var $sfields;       //filas de la consulta expresada en string
    var $data;          //Arreglo con la data obtenida de la base de datos
    var $orderby;
    var $last_id;
    var $last_query;
    var $last_otherid;
    var $has_upper = TRUE;
    var $has_upper_return = FALSE;
    private $whereDyn;  // almacena la cadena que contiene la parte where del query
    private $joinDyn;   // almacena la cadena que contiene la parte join del query
    private $groupDyn;   // almacena la cadena que contiene el group del query
    private $orderDyn;   // almacena la cadena que contiene el order del query
    private $beginAgrupador;  // guarda el parentesis
    private $tables;    // contenedor de tablas
    private $queryLimit;
    private $queryOffset;
    
    private $relateds_config;
    private $relateds;
    private $table_type;
    
    static private $_instance = array();  // almacena la instancia de la clase hija
    
    public function __construct() {
        parent::__construct();
        $this->whereDyn = NULL;
        $this->joinDyn = NULL;
        $this->groupDyn = NULL;
        $this->orderDyn = NULL;
        $this->queryLimit = null;
        $this->queryOffset = null;
        $this->propertys = array();
        $this->relateds = array();
        $this->table_type = self::TABLE_TYPE_ENTIDAD;
    }
    
    /**
     * 
     * Crea la instancia. 
     * Este Pseudo-Criteria se usa para armar las consultas 
     * que obtienen la informacion de la 'clase hija'
     * @return get_called_class()
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    static function create()
    {
        $class_child = get_called_class();
        if(ObjectUtil::isEmpty(self::$_instance)){
            self::$_instance[$class_child] = new $class_child();
        }
        elseif(!array_key_exists($class_child, self::$_instance)){
            self::$_instance[$class_child] = new $class_child();
        }
        
        // Limpiamos las variaables. Usamos reflection por ser una function estatica.
        $method = new ReflectionMethod(self::$_instance[$class_child], 'clearSQL');
        $method->invoke(self::$_instance[$class_child]);
        
        return self::$_instance[$class_child];
    }
    
    /**
     * Pasarela de seteo de otra base de datos que no sea la por defecto
     * @param type $dbname
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setBD($dbname){
        $this->c->setDB($dbname);
    }
    
    /**
     * Asigna un valor a una propiedad del objeto hijo
     * @param type $property
     * @param type $value
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setProperty($property, $value){
        $this->validaProperty($property);
        $this->data[$property] = $value;
    }
    
    /**
     * Asigna el tipo de tabla 
     * @param type $property
     * @param type $value
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setTableType($value){
        $this->table_type = $value;
    }
    
    /**
     * Setea las relaciones que tiene la clase que representa una tabla en la base de datos con las demás
     * Ejemplo: Si estamos dentro de sesion este metodo lo usamos para agregar la relación con sesiondetalle
     * @param type $class_name
     * @param type $property_name
     * @throws \ModeloException
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setConfigRelated($class_name, $property_name){
        // validamos que las variables no esten vacias
        if (ObjectUtil::isEmpty($class_name) || ObjectUtil::isEmpty($property_name))
            throw new \ModeloException('El nombre de la clase o la propiedad de relación no se especificaron de manera correcta');
        
        if (!ObjectUtil::isEmpty($this->relateds_config)){
            // validamos que anteriormente no se hayan agregado la relacion con la clase especificada
            if (array_key_exists($class_name, $this->relateds_config))
                throw new \ModeloException('La relación a la clase "$class_name" ya ha sido agregada anteriormente');
        }
        $this->relateds_config[$class_name] = $property_name;
    }
    
    /**
     * Agregamos las clases relacionadas  para posteriormente ser insertadas
     * @param type $related_column
     * @param type $related_table
     * @param type $related_shema
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function addRelated($object, $class_name = NULL){
        // validamos que el parametro pasado sea un objecto
        if (!is_object($object))
            throw new \ModeloException('No se especificó un objeto para ser relacionado en la clase "$this->table_name"');
        
        // validamos que el nombre de la clase se encuentre dentro de las configuradas en el constructor de esta clase
        if (!array_key_exists(get_class($object), $this->relateds_config))
            throw new \ModeloException('El objeto "'.get_class($object).'" no se encuentra dentro de las configuraciones para esta clase');
        
        // validamos que el nombre de la clase coincida el parametro $class_name
        if (($class_name !== NULL) && ($class_name !== get_class($object)))
            throw new \ModeloException('El objeto "'.get_class($object).'" no coincide con lo que se esperaba en la agregación de la relación "$class_name"');
        
        // seteamos el nombre de la clase con el valor que deberia tener
        //$class_name = ($class_name === NULL)? get_class($object) : $class_name;
        
        $this->relateds[] = $object;
    }
    
    public function beginUpdate() {
        $this->data = array();
    }
    
    /**
     * Definición: 
     *  Insert de una fila a la tabla de la clase que heredó de ModeloBase
     * @return type
     * @throws \ModeloException
     */
    public function insertRecord() {
        if(ObjectUtil::isEmpty($this->data)) {
            throw new \ModeloException('No se ha especificado la data a registrar.');
        }
            
        foreach ($this->data as $key => $value) {
            if(!in_array($key, $this->fields)) {
                throw new \ModeloException('El campo [' . $key . '] no existe en la tabla [' .  $this->table_name . ']');
            }
        }
        
        $this->c->insertRecord("$this->table_name", $this->data, $this->fields[0]);
        return $this->syncVarsAfterQuering();
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Realiza las inserciones de la siguiente manera 
     * 1. Inserta el objeto en el que se encuentra
     * 2. Inserta cada una de sus relaciones en el orden en que 
     *      fueron agregadas
     */
    public function save()
    {
        switch ($this->table_type){
            case self::TABLE_TYPE_ENTIDAD:
                // En caso sea una tabla entidad
                // primero guardamos en base de datos al objeto en el que nos encontramos 
                // y posteriormente sus relaciones
                $this->insertRecord();
                $id = $this->last_id;

                if (!ObjectUtil::isEmpty($this->relateds)){
                    // Ahora guardamos todas sus relaciones
                    foreach ($this->relateds as $related) {

                        // Asignamos la propiedad que faltaba en la relación
                        $related->setProperty($this->relateds_config[get_class($related)], $id);
                        $related->save();
                    }
                }
                return $id;
                break;
            case self::TABLE_TYPE_RELACION:
                // En caso sea una tabla que relaciona dos o más tablas
                $related_ids = array();
                if (!ObjectUtil::isEmpty($this->relateds)){
                    // Ahora guardamos todas sus relaciones
                    foreach ($this->relateds as $related) {
                        $related_ids[$related->table_name."_id"] = $related->save();
                    }
                    if (!ObjectUtil::isEmpty($related_ids)){
                        foreach ($related_ids as $key=>$value) {
                            $this->setProperty($key, $value);
                        }
                    }
                }
                $this->insertRecord();
                
                break;
            default:
                throw new \ModeloException('No se especificó de manera correcta el tipo de tabla a la que pertenece: '.$this->table_name);
        }
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Definición:
     * 
     * Método que me permite la actualización de registros de una tabla utilizando los metodos Where
     * 
     * @return type
     * @throws \ModeloException
     */
    public function updateRecord() {
        if(ObjectUtil::isEmpty($this->data)) {
            throw new \ModeloException('No ha especificado la data a actualizar.');
        }
            
        foreach ($this->data as $key => $value) {
            if(!in_array($key, $this->fields)) {
                throw new \ModeloException('El campo [' . $key . '] no existe en la tabla [' . $this->table_name . ']');
            }
        }
        
        $this->c->updateRecord("$this->table_name AS tab", $this->data, $this->whereDyn);
        return $this->syncVarsAfterQuering();
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Metodo que se encarga de eliminar una o varias filas de una tabla
     * @return type
     */
    public function deleteRecord(){
        $this->c->deleteRecord("$this->table_name AS tab", $this->whereDyn);
        return $this->syncVarsAfterQuering();
    }

    public function groupAdd($params) {
        try {
            if (isset($params)) {
                // Obtenemos los parametros comunes
                $first_col = $params[ElementoSQL::first_col];
                // pasa el alias o la table
                if (isset($params[ElementoSQL::first_alias])) {
                    $this->groupDyn = isset($this->groupDyn) ? $this->groupDyn . ", " : " GROUP BY ";
                    $this->groupDyn .= $params[ElementoSQL::first_alias] . "." . $first_col;
                }elseif (isset($params[ElementoSQL::first_table])) {
                    $first_table = isset($params[ElementoSQL::first_table]) ? $params[ElementoSQL::first_table] : null;
                    $this->groupDyn = isset($this->groupDyn) ? $this->groupDyn . ", " : " GROUP BY ";
                    $this->groupDyn .= "$first_table.$first_col";
                }else{
                    $this->groupDyn = isset($this->groupDyn) ? $this->groupDyn . ", " : " GROUP BY ";
                    $this->groupDyn .= self::DEFAULT_ALIAS_TABLE . "." . $first_col;
                }
            }
            return $this;
        } catch (ModeloException $emodelo) {
            throw new ModeloException($emodelo->getMessage());
        }
    }

    public function orderAdd($params) {
        try {
            if (isset($params)) {
// Obtenemos los parametros comunes
                $first_col = $params[ElementoSQL::first_col];
                if (isset($first_col)){
// pasa el alias o la tabla
                    if (isset($params[ElementoSQL::first_alias])) {
                        $this->orderDyn = isset($this->orderDyn) ? $this->orderDyn . ", " : " ORDER BY ";
                        $this->orderDyn .= (self::DEFAULT_ALIAS_NOTHING === $params[ElementoSQL::first_alias])? "$first_col " : $params[ElementoSQL::first_alias] . ".$first_col ";
                    }elseif (isset($params[ElementoSQL::first_table])) {
                        $first_table = isset($params[ElementoSQL::first_table]) ? $params[ElementoSQL::first_table] : null;
                        $this->orderDyn = isset($this->orderDyn) ? $this->orderDyn . ", " : " ORDER BY ";
                        $this->orderDyn .= " $first_table.$first_col ";
                    }else{
                        $this->orderDyn = isset($this->orderDyn) ? $this->orderDyn . ", " : " ORDER BY ";
                        $this->orderDyn .= " tab.$first_col ";
                    }
                
                    $this->orderDyn .= isset($params[ElementoSQL::order])? $params[ElementoSQL::order] :Order::asc;
                }
            }else{
                throw new ModeloException("No se especificaron los parametros minimos para realizar el ORDER BY");
            }
            return $this;
        } catch (ModeloException $emodelo) {
            throw new ModeloException($emodelo->getMessage());
        }
    }

    public function orderClear() {
        $this->orderDyn = null;
        return $this;
    }
    
    public function groupClear() {
        $this->groupDyn = null;
        return $this;
    }
    
    public function whereClear() {
        $this->whereDyn = null;
        $this->clearAgrupador();
        return $this;
    }

    /**
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     * 
     * @param Array $params: Parametros que me permiten construir una linea de la clapsula where
     */
    public function whereAdd($params) {
        try {
            if (isset($params)) {
                // Obtenemos los parametros comunes
                $comparacion = isset($params[ElementoSQL::comparacion]) ? $params[ElementoSQL::comparacion] : ComparacionSQL::igual;
                $opelog = isset($params[ElementoSQL::ope_log]) ? $params[ElementoSQL::ope_log] : OperadorLogico::y;
                $first_col = $params[ElementoSQL::first_col];

                If (isset($params[ElementoSQL::second_alias]) || isset($params[ElementoSQL::second_table])) {
                    $first_alias = isset($params[ElementoSQL::first_alias]) ? $params[ElementoSQL::first_alias] : null;
                    $first_table = isset($params[ElementoSQL::first_table]) ? $params[ElementoSQL::first_table] : null;
                    $second_col = $params[ElementoSQL::second_col];
                    $second_alias = isset($params[ElementoSQL::second_alias]) ? $params[ElementoSQL::second_alias] : null;
                    $second_table = isset($params[ElementoSQL::second_table]) ? $params[ElementoSQL::second_table] : null;
                    
                    // llamamos a la función que agregará el where de dos columnas distintas
                    //  -Se debe usar en el caso de comparar dos columnas
                    return $this->whereAddCols($first_col, $first_alias, $second_col, $second_alias, $first_table, $second_table, $comparacion, $opelog);
                }
                // solo hay dos funciones en las que puedo utilizar el value
                else
                    $value = $params[ElementoSQL::val];

                if (isset($params[ElementoSQL::first_alias]) || isset($params[ElementoSQL::first_table])) {
                    $first_alias = isset($params[ElementoSQL::first_alias]) ? $params[ElementoSQL::first_alias] : null;
                    $first_table = isset($params[ElementoSQL::first_table]) ? $params[ElementoSQL::first_table] : null;

                    // llamamos a la función que agregará el where
                    //  - Se debe usar cuando quiere compararse una columna de una tabla diferente a la del modelo con un valor X
                    return $this->whereAddOtherColVal($first_col, $first_alias, $value, $first_table, $comparacion, $opelog);
                } else {   // llamamos a la función que agregará el where
                    //  - Se debe usar cuando quiere compararse la columna del modelo con un valor X
                    return $this->whereAddColVal($first_col, $value, $comparacion, $opelog);
                }
            }
        } catch (ModeloException $emodelo) {
            throw new ModeloException($emodelo->getMessage());
        }
    }

    public function whereAgrupador($type = 0) {
        //0 = "("
        //1 = ")"
        if ($type == 0) {
            $this->beginAgrupador .= "(";
        } else {
            $this->clearAgrupador();
            $this->whereDyn .= ")";
        }
        return $this;
    }

    //Si se quiere usar un "order by" usar este metodo
    public function whereAddText($text, $pos = 1) {
        switch ($pos) {
            //izquierda
            case 0:
                $this->whereDyn = "$text $this->whereDyn";
                break;
            //derecha
            case 1:
                $this->whereDyn = "$this->whereDyn $text";
                break;
        }
        return $this;
    }

    //Si se quiere comparar dentro del join
    public function joinAddText($text, $pos = 1) {
        switch ($pos) {
            //izquierda
            case 0:
                $this->joinDyn = $text . $this->joinDyn;
                break;
            //derecha
            case 1:
                $this->joinDyn = $this->joinDyn . $text;
                break;
        }
        return $this;
    }

    public function joinClear() {
        $this->joinDyn = null;
        $this->iniciaAliasecond_tables();
        return $this;
    }

    /**
     * @author Christopher Heredia Lozada
     * 
     * Definicion: Encargado de realizar diferentes intersecciones entre dos tablas.
     * 
     * @param type $params: Array con los parámetros necesarios para realizar un join. Se pueden usar los siguientes "key's" dentro del arreglo:
     * - [ElementoSQL::first_col] => Identifica una de las columnas con las que se requiere realizar el Join.
     * - [ElementoSQL::first_alias] => Identifica el alias de la tabla que contiene a la primera columna. Para el caso de que se quiera representar a la tabla que identifica la entidad usar la palabra "tab" que esta definido en la constante DEFAULT_ALIAS_TABLE de la clase ModeloBase.
     * - [ElementoSQL::first_table] => En el caso NO se haya configurado el alias en la tabla o por comodidad del desarrollador prefiere solo usar siempre la tabla y el esquema pero no los alias.
     * - [ElementoSQL::second_col] => Identifica una de las columnas con las que se requiere realizar el Join. 
     * - [ElementoSQL::second_alias] => Identifica el alias de la tabla que contiene a la segunda columna. 
     * - [ElementoSQL::second_table] => En el caso NO se haya configurado el alias en la tabla o por comodidad del desarrollador prefiere solo usar siempre la tabla y el esquema pero no los alias. 
     * - [ElementoSQL::comparacion] => Si no se especifica este campo, por defecto es "="
     * - [ElementoSQL::orientacion] => 0: por defecto y 1: sentido contrario, los seconds al inicio y los first al final. Esto se realiza por cuestiones de performance.
     * - [ElementoSQL::join] => Identifica el tipo de join a realizar. Por defecto es INNER.
     *  
     * @return \ModeloBase
     */
    public function joinAdd($params) {
        try {
            $first_col = $params[ElementoSQL::first_col];
            $first_alias = isset($params[ElementoSQL::first_alias]) ? $params[ElementoSQL::first_alias] : null;
            $first_table = isset($params[ElementoSQL::first_table]) ? $params[ElementoSQL::first_table] : null;

            $second_col = $params[ElementoSQL::second_col];
            $second_alias = isset($params[ElementoSQL::second_alias]) ? $params[ElementoSQL::second_alias] : null;
            $second_table = isset($params[ElementoSQL::second_table]) ? $params[ElementoSQL::second_table] : null;
            $comparacion = isset($params[ElementoSQL::comparacion]) ? $params[ElementoSQL::comparacion] : ComparacionSQL::igual;
            $orientacion = isset($params[ElementoSQL::orientacion]) ? $params[ElementoSQL::orientacion] : 0;
            $type = isset($params[ElementoSQL::join]) ? $params[ElementoSQL::join] : Join::inner;

            // debemos validar que nos especifiquen un alias o (un esquema y una tabla)
            //      en el caso del nuevo criterio no necesariamente necesita el alias ya que 
            //      puede ser generado posteriormente.
            if ($first_table !== null && ($second_alias !== null || $second_table !== null)) {
                // obtenemos el alias para el nuevo join 
                if ($first_alias !== null) {
                    $this->addAliasecond_tables($first_table, $first_alias);
                } else {
                    $first_alias = $this->addAliasecond_tables($first_table);
                }
                // obtenemos el alias de la tabla con la que se va  a realizar el join
                if ($second_alias === NULL) {
                    $second_alias = $this->findAliasecond_tables($second_table);
                }
                // armamos el query
                $this->joinDyn .= " $type JOIN $first_table as $first_alias on ";
                // validamos si da una orientacion normal o se invierte 
                //      esto seria necesario en el caso del left join y el right join
                if ($orientacion == 0) {
                    $this->joinDyn .= " $first_alias.$first_col $comparacion $second_alias.$second_col ";
                } else {
                    $this->joinDyn .= " $second_alias.$second_col $comparacion $first_alias.$first_col ";
                }

                return $this;
            } else {
                //$this->setError(1);
                throw new ModeloException("No se especificaron los parametros correctos en joinAdd");
            }
        } catch (ModeloException $emodelo) {
            throw new ModeloException($emodelo->getMessage());
        }
    }

    /**
     * 
     * @param type $exp
     * @return type
     * 
     * Obtiene un valor escalar
     */
    public function getEscalar($exp) {
        $v = $this->c->getEscalar($this->formatTableName(), $exp, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn);
        $this->syncVarsAfterQuering();
        return $v;
    }

    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * 
     * Definición: En el caso la parte de la consulta "Select" sea muy compleja. Se puede escribir directamente, mediante este metodo.
     * 
     * @param type $sfields: Script con la cabecera de la consulta, es decir con los campos a los que se requiere hacer select.
     * @param type $page_config: Es un array donde se le debe especificar dos parametros pageNumber y pageSize
     * @return type
     */
    public function getSelectPersonalized($sfields, $page_config = NULL) {
        $this->sfields = $sfields;
        $this->data = $this->c->getDataTable($this->formatTableName(), $sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return $this->data;
    }
    
    /**
     * @param type $sp_name Nombre del procedimiento almacenado a ejecutar
     */
    public function commandPrepare($sp_name, $sp_fields = '*') {
        // validacion 
        if (ObjectUtil::isEmpty($sp_name))
            throw new ModeloException ('Debe especificar un nombre para el procedimiento almacenado');
        if (ObjectUtil::isEmpty($sp_fields))
            $sp_fields = '*';
        $this->c->has_upper = $this->has_upper;
        $this->c->has_upper_return = $this->has_upper_return;
        $this->c->commandPrepare("$sp_name", $sp_fields);
    }
    
    /**
     * 
     * @param type $value Valor del parametro a setear en el comando
     */
    public function commandAddParameter($key, $value, $has_upper = true) {
        $this->c->commandAddParameter($key, $value, $has_upper);
    }

    /**
     * 
     * @return type
     * @throws ModeloException
     */
    public function commandGetData($page_config = null) {
        return $this->c->commandGetData($page_config);
    }
    
    /**
     * 
     * @param type $exp
     * @return type
     * 
     * Obtiene un valor escalar del procedimiento almacenado
     */
    public function commandGetEscalar($exp) {
        return $this->c->commandGetEscalar($exp);
    }
    
    /**
     * Definición: Solo en el caso de que la consulta sea muy compleja y no pueda elaborarse mediante los otros métodos que tenemos en la clase ModeloBase, se puede realizar simplemente introduciendo el script en el parámetro "$sql" de este método.
     * 
     * @param type $sql
     * @param type $page_config
     * @return type
     */
    public function getData($sql, $page_config = null) {
        $this->data = $this->c->getData($sql, $page_config);
        $this->syncVarsAfterQuering();
        return $this->data;
    }

    /**
     * Definición: Obtenemos la cadena de la tabla "idiomacontenido" segun el culture del usaurio
     * que ya lo obtiene automaticamente
     * @param type $code
     */
    public function getResString($code) {
        parent::getResString($code);
    }

    /**
     * @author Christopher Heredia
     * Devuleve todas los campos que se especificaron en el objeto que hereda de esta clase
     * 
     * @param type $page_config: Es un array al que se le debe de especificar el pageNumber y el pageSize
     * @return type
     */
    public function getEntity($page_config = null) {
        $this->formatFields();
        $this->data = $this->c->getDataTable($this->formatTableName(), $this->sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return $this->data;
    }

    /**
     * @author Christopher Heredia
     * Obtiene solo los campos que se le especifique en la variable $fields
     * 
     * @param type $fields
     * @param type $page_config
     * @return type
     */
    public function getFieldsEntity($fields, $page_config = null) {
        $this->formatFields($fields);
        $this->data = $this->c->getDataTable($this->formatTableName(), $this->sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return $this->data;
    }
    
    /**
     * @author Christopher Heredia
     * En el caso de tener inner joins asociados a mi consulta me puede ser de utilidad este metodo ya que me permite seleccionar campos de distintas tablas
     * 
     * @param type $fields
     * @param type $page_config
     * @return type
     */
    public function getFieldsPersonalized($fields, $page_config = null) {
        $this->formatFieldsPersonalized($fields);
        $this->data = $this->c->getDataTable($this->formatTableName(), $this->sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return $this->data;
    }

    /**
     * Definición: Nos retorna un COUNT(*) de la consulta dada
     * @return type
     */
    public function getEntityCount() {
        $v = $this->c->getCount($this->formatTableName(), $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->sfields);
        $this->syncVarsAfterQuering();
        return $v;
    }

    /**
     * @author Christopher Heredia
     * Obtiene solo los campos que se le especifique en la variable $fields y los resultados que coincida el campo especificado en $keyName con el valor especificado en $keyValue.
     * 
     * @param string $key
     * @param string $value
     * @param array $fields
     * @param array $page_config
     * @return type
     */
    public function getFieldsEntityByKey($key, $value, $fields, $page_config = NULL) {
        $this->whereAdd(Array(ElementoSQL::first_alias => 'tab',
            ElementoSQL::first_col => $key,
            ElementoSQL::val => $value));
        $this->formatFields($fields);
        $this->data = $this->c->getDataTable($this->formatTableName(), $this->sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return (!ObjectUtil::isEmpty($this->data) && count($this->data) >0)? $this->data[0] : NULL;
    }
    
    /**
     * @author Christopher Heredia
     * Obtiene todos los resultados que coincida el campo especificado en $keyName con el valor especificado en 
     * 
     * $keyValue
     * @param type $keyName
     * @param type $keyValue
     * @param type $page_config
     * @return type
     */
    public function getEntityByKey($keyName, $keyValue, $page_config = NULL) {
        $this->whereAdd(Array(ElementoSQL::first_alias => 'tab',
            ElementoSQL::first_col => $keyName,
            ElementoSQL::val => $keyValue));
        $this->formatFields();
        $this->data = $this->c->getDataTable($this->formatTableName(), $this->sfields, $this->joinDyn, $this->whereDyn, $this->groupDyn, $this->orderDyn, $page_config, $this->queryLimit, $this->queryOffset);
        $this->syncVarsAfterQuering();
        return $this->data;
    }

    
    public function setTimeZone($time_zone){
        $this->c->setTimeZone($time_zone);
    }
    
    /**
     * Limpia las variables sql.
     * 
     * 
     * @return \ModeloBase
     */
    public function clearSQL() 
    {
        $this->whereClear();
        $this->joinClear();
        $this->orderClear();
        $this->groupClear();
        $this->queryLimit = null;
        $this->queryOffset = null;
        $this->data = array();
        
        return $this;
    }
    
    /**
     * Agrega la condicional OR entre sentencias.
     * 
     * 
     * @return type
     */
    public function _or()
    {
        return $this->addOperator(OperadorLogico::o);
    }

    /**
     * Agrega la condicional AND entre sentencias.
     * 
     * 
     * @return type
     */
    public function _and()
    {
        return $this->addOperator(OperadorLogico::y);
    }

    public function limit($limit)
    {
        if(!is_numeric($limit)) throw new Exception('ERROR: El valor del LIMIT debe ser numerico');
        $this->queryLimit = " limit $limit ";
        return $this;
    }
    
    public function offset($offset)
    {
        if(!is_numeric($offset)) throw new Exception('ERROR: El valor del OFFSET debe ser numerico');
        $this->queryOffset = " offset $offset ";
        return $this;
    }
    
    /**
     * Obtiene los campos de la tabla preparados para su seleccion. Se basa en el arreglo fields
     * 
     * @return string cadena con los campos a seleccionar
     * 
     * 
     */
    protected function getFieldsToSelect($alias = self::DEFAULT_ALIAS_TABLE, $prefix = "")
    {
        if (ObjectUtil::isEmpty($alias)) $alias = self::DEFAULT_ALIAS_TABLE;
        $fields = $this->fields;
        
        if ($prefix != null && strlen(trim($prefix)) > 0) 
        {
            $aliases = array();
            foreach ($fields as $field)
            {
                array_push($aliases, "$field as ".$prefix."_".$field);
            }
            
            $fields = $aliases; //Seteamos en fields al final
        }
        $this->sfields = "$alias.".implode(", $alias.", $fields);
        
        return $this->sfields;
    }
    
    /**
     * Obtiene los campo de la enumeración preparados para su selección.
     * 
     * @param string $aliasEnum El alias que recibe la enumeracion en la consulta
     * @param string $prefix El prefijo que obtendrá cada campo
     * @return string La cadena de campos a seleccionas
     * 
     * 
     */
    protected function getEnumFieldsToSelect ($aliasEnum = self::DEFAULT_ALIAS_ENUM, $prefix = self::DEFAULT_ALIAS_ENUM)
    {
        if (ObjectUtil::isEmpty($aliasEnum)) $aliasEnum = self::DEFAULT_ALIAS_ENUM;
        if (ObjectUtil::isEmpty($prefix)) $prefix = self::DEFAULT_ALIAS_ENUM;
        
        return $aliasEnum.".codigo as ".$prefix."_codigo, ".
               $aliasEnum.".valor as ".$prefix."_valor, ".
               $aliasEnum.".atributo as ".$prefix."_atributo, ".
               $aliasEnum.".estado as ".$prefix."_estado ";
    }
    
    /**
     * Agrega un JOIN a cualquier otra tabla del sistema
     * 
     * @param type $columnThis Es la columna de la tabla actual que servirá de join con la otra tabla que se quiere unir
     * @param type $nameTable Es el nombre de la tabla a la que se quiere unir
     * @param type $columnTable Es el nombre de la columna de la tabla a la que se quiere unir
     * @param type $aliasTable Es el alias de la tabla a la que se quiere unir
     * @param type $aliasThis Es el alias de la tabla actual
     * @param type $joinType Es el tipo de union que tendría
     * @return ModeloBase
     * 
     * 
     */
    protected function useTable ($columnThis, $nameTable, $columnTable, $aliasTable = self::DEFAULT_ALIAS_JOINED,  $aliasThis = self::DEFAULT_ALIAS_TABLE, $joinType = Join::inner)
    {
        if (ObjectUtil::isEmpty($aliasTable)) $aliasTable = self::DEFAULT_ALIAS_JOINED;
        if (ObjectUtil::isEmpty($aliasThis)) $aliasThis = self::DEFAULT_ALIAS_TABLE;
        if (ObjectUtil::isEmpty($joinType)) $joinType = Join::inner;
        
        return $this->joinAdd(Array(ElementoSQL::first_col => $columnTable,
                                    ElementoSQL::first_table => $nameTable, 
                                    ElementoSQL::first_alias => $aliasTable,
                                    ElementoSQL::second_alias => $aliasThis,
                                    ElementoSQL::second_col => $columnThis,
                                    ElementoSQL::join => $joinType));
    }
    
    /**
     * Agrega un where a la consulta que se va armando.
     * 
     * 
     * 
     * @param string $column Es el nombre de la columna a filtrar
     * @param type $value Es el valor que se quiere filtrar
     * @param string $alias Es el alias de la tabla donde se hara el filtro
     * @return ModeloBase
     */
    public function filterBy ($column, $value, $alias = null, $comparison = ComparacionSQL::igual)
    {
        if (ObjectUtil::isEmpty($alias)) $alias = 'tab';
        
        return $this->whereAdd(Array(ElementoSQL::first_col => $column,
                                    ElementoSQL::val => $value, 
                                    ElementoSQL::first_alias => $alias,
                                    ElementoSQL::comparacion => $comparison));
    }
    
    /**
     * Agrega un order a la consulta que se va armando.
     * 
     * 
     * 
     * @param string $column Es el nombre de la columna que se desea ordenar
     * @param string $alias Es el alias de la tabla de la columna que se desea ordenar
     * @return ModeloBase
     */
    public function orderBy ($column, $alias = null, $order =  Order::asc)
    {
        if (ObjectUtil::isEmpty($alias)) $alias = 'tab';
        
        return $this->orderAdd(Array(ElementoSQL::first_col => $column,
                                     ElementoSQL::first_alias => $alias,
                                     ElementoSQL::order => $order));
    }
    
    /**
     * Agrega una agrupacion a la consulta que se va armando.
     * 
     * @param type $column
     * @param type $alias
     * @return type
     */
    public function groupBy ($column, $alias = null)
    {
        return $this->groupAdd(Array(ElementoSQL::first_col => $column,
                                    ElementoSQL::first_alias => $alias));
    }
    
    /**
     * Retorna un solo array con el resultado. De lo contrario devuelve null.
     * 
     * 
     * @param type $page_config
     * @param type $sFields
     * @return type
     */
    public function findOne($page_config = null, $sFields = null) 
    {
        if($sFields !== null) {
            $entity = $this->limit(1)->getSelectPersonalized($sFields, $page_config);
        } else {
            $entity = $this->limit(1)->getEntity($page_config);
        }
        
        if(count($entity) > 0) return $entity[0];
        
        return null;
    }
    
    /**
     * Agrega un JOIN a la Tabla ENUMERACION
     * 
     * @param string $columnThis Nombre de la columna en la Tabla con la que se hará el JOIN
     * @param string $aliasThis Alias de la Tabla con la que se hará el JOIN
     * @param string $aliasEnum Alias que recibirá la enumeracion
     * @param Join $typeJoin Tipo de Join
     * @return ModeloBase
     * 
     * 
     */
    protected function useEnumTable($columnThis, $aliasThis = self::DEFAULT_ALIAS_TABLE, $aliasEnum = self::DEFAULT_ALIAS_ENUM, $joinType = Join::inner)
    {
        if (ObjectUtil::isEmpty($aliasEnum)) $aliasEnum = self::DEFAULT_ALIAS_ENUM;
        
        $this->useTable($columnThis, "enumeracion", "id", $aliasEnum, $aliasThis, $joinType);
        $this->useTable('nombre_id', 'idiomacontenido', 'id', $aliasEnum.'nombre', $aliasEnum, Join::left);
        $this->useTable('descripcion_id', 'idiomacontenido', 'id', $aliasEnum.'descripcion', $aliasEnum, Join::left);
        return $this;
    }
    
    /**
     * Agrega la condicional WHERE para el ESTADO en el REGISTRO según los parámetros enviados
     * 
     * @param EstadoGenerico $estado Estado por el cual se desea filtar
     * @param string $alias Alias que recibe la tabla en la consulta
     * @return ModeloBase
     * 
     * 
     */
    protected function whereEstado ($estado = EstadoGenerico::disponible, $alias = self::DEFAULT_ALIAS_TABLE)
    {
        if (ObjectUtil::isEmpty($alias)) $alias = self::DEFAULT_ALIAS_TABLE;
        
        return $this->whereAdd(Array(ElementoSQL::first_col => 'estado',
                                     ElementoSQL::first_alias => $alias,
                                     ElementoSQL::val => $estado));
    }
    
    /**
     * Agregar el WHERE de ESTADO condicionando solo a los registros que estan DISPONIBLES
     * 
     * @param string $alias Alias que recibe la tabla en la consulta
     * @return ModeloBase
     * 
     * 
     */
    protected function disponible ($alias = self::DEFAULT_ALIAS_TABLE)
    {
        return $this->whereEstado(EstadoGenerico::disponible, $alias);
    }
    
    /**
     * Obtiene la entidad asociada a un identificador
     * 
     * @param string|int $id El identificador del elemento que se va a obtener
     * @param EstadoGenerico $estado condiciona para que busque por un estado. Por defecto que esté disponible.
     * @return mixed
     * 
     * 
     */
    public function findByPk ($id, $estado = EstadoGenerico::disponible)
    {
        if (ObjectUtil::isEmpty($id)) return null;
        
        $result = $this->whereEstado($estado)->limit(1)->getEntityByKey('id', $id);
        
        if (ObjectUtil::isEmpty($result) || ObjectUtil::isEmpty($result[0])) return null;
        
        return array_shift($result);
    }
    
// <editor-fold defaultstate="collapsed" desc="Funciones de apoyo">
    private function formatFields($fields = null) {
        if ($fields === null)
            $fields = $this->fields;
        $this->sfields = "tab." . implode(", tab.", $fields);
    }
    
    private function formatFieldsPersonalized($fields = null) {
        try{
            // validamos que los fields tengan un formato correcto
            if (ObjectUtil::isEmpty($fields))
                throw new \ModeloException("No se especificaron las filas para realizar un formateo valido");
            
            $str_fields = " ";
            $b_first = true;
            // obtenemos un muestreo para ver que tipo de formato utilizamos
            current($fields);
            // verificamos si los keys son enteros
            if(is_integer(key($fields))){
                // solo se tendrá en cuenta el value y en el caso no se especifique un alias, 
                // se considerará el  de la entidad actual 'tab'
                foreach ($fields as $key => $value ){
                    if (strpos($value, ".") !== FALSE){
                        $str_fields .= ($b_first)? $value : ", $value";
                    }else{
                        $str_fields .= ($b_first)? "tab.$value" : ", tab.$value";
                    }
                    $b_first = false;
                }
            }else{
                // en este caso se tomara el key como la columna y el value como el alias
                foreach ($fields as $key => $value ){
                    $str_fields .= ($b_first)? "$value.$key" : ", $value.$key";
                    $b_first = false;
                }
            }
            $this->sfields = $str_fields." ";
            
        }catch(\ErrorException $e){
            throw new \ModeloException($e->getMessage());
        }
    }

    private function formatTableName() {
        return "$this->table_name AS tab";
    }
    
    /**
     * Valida si es necesario insertar el operador o no(en caso se haya utilizado antes la funcion addOperator)
     * 
     * 
     * @param string $opelog
     * @return string
     */
    private function validOperatorBeforeAdd($opelog)
    {
        if(isset($this->whereDyn)) {
            $matches = null;
            // - Busca la ultima palabra del where
            preg_match("/(?<=\040)([^\s]+?)$/",trim($this->whereDyn),$matches);
            // - Comprobamos si la palabra es OR o AND, de ser asi es porque 
            // ya se ha agragado el operador mediante la funcion addOperator()
            if(strtolower($matches[0]) == "and" || strtolower($matches[0]) == "or") {
                $opelog = "";
            }
        }
        return $opelog;
    }
    
    /**
     * Agregar un operador a las condicionales where
     * 
     * 
     * @param type $value
     * @return \ModeloBase
     */
    private function addOperator($value)
    {
        if(isset($this->whereDyn) && $this->whereDyn != null && $this->whereDyn != "" && trim($this->whereDyn) != "") {
            $this->whereDyn .= " " . $value . " ";
        }
        //var_dump($this->whereDyn);
        return $this;
    }
    
    /**
     * Valida que el nombre de la propiedad exista en la clase que hereda 
     * a la presente "ModeloBase"
     * @param type $property
     * @throws \ModeloException
     */
    private function validaProperty($property){
        if (ObjectUtil::isEmpty($property))
            throw new \ModeloException('No se especificó el nombre de la propiedad');
        if (!ObjectUtil::isEmpty($this->fields)){
            if (!in_array($property, $this->fields))
                throw new \ModeloException("El nombre de la propiedad \"$property\" no es válida en la clase \"$this->table_name\"");
        }
    }
    
    //Devuelve un arreglo asociativo con todas las propiedades de la clase
    private function setNewAssocFields() {
        if (isset($this->fields)) {
            $this->data = array_flip($this->fields);
            //Inicializo el array en blanco
            foreach ($this->data as $key => $value) {
                $this->data[$key] = NULL;
            }
        }
    }

    /**
     * Sincroniza las propiedades de la conexion con mi clase base
     * ademas retorna T o F por si hay error o no
     */
    private function syncVarsAfterQuering() {
        $this->has_error = $this->c->has_error;
        $this->last_error = $this->c->last_error;
        $this->last_id = $this->c->last_id;
        $this->last_query = $this->c->last_query;
        //Niego el has_error para devolver VERDADERO cuando has_error = FALSO 
        return !$this->has_error;
    }
    
    
    /**
     * @author Christopher Heredia
     * @param type $column_name
     * @param type $value
     * @param type $comparacion
     * @param type $opelog
     * @return \ModeloBase
     */
    private function whereAddColVal($column_name, $value, $comparacion = "=", $opelog = "AND") {
        $opelog = $this->validOperatorBeforeAdd($opelog);
        //Formateo las cadenas de acuerdo al tipo de dato
        $this->whereDyn .= isset($this->whereDyn) ?
                " $opelog $this->beginAgrupador ".self::DEFAULT_ALIAS_TABLE.".$column_name " :
                " WHERE $this->beginAgrupador ".self::DEFAULT_ALIAS_TABLE.".$column_name ";
        $this->whereDyn .= $this->c->formatParamByOperador($value, $comparacion);
        $this->clearAgrupador();

        return $this;
    }

    /**
     * @author Christopher Heredia
     * @param type $column_name
     * @param type $alias
     * @param type $value
     * @param type $table_name
     * @param type $comparacion
     * @param type $opelog
     * @return \ModeloBase
     */
    private function whereAddOtherColVal($column_name, $alias, $value, $table_name = null, $comparacion = "=", $opelog = "AND") {
        // si no tengo el alias lo obtengo
        if (isset($table_name)) {
            $alias = $this->findAliasecond_tables($table_name);
        }
        //Si tengo alias Formateo las cadenas de acuerdo al tipo de dato
        if (isset($alias)) {
            $opelog = $this->validOperatorBeforeAdd($opelog);
            $this->whereDyn .= isset($this->whereDyn) ?
                    " $opelog $this->beginAgrupador $alias.$column_name " :
                    " WHERE $this->beginAgrupador $alias.$column_name ";
            $this->whereDyn .= $this->c->formatParamByOperador($value, $comparacion);

            $this->clearAgrupador();
        }

        return $this;
    }

    /**
     * @author Christopher Heredia
     * @param type $alias_left
     * @param type $column_left
     * @param type $alias_right
     * @param type $column_right
     * @param type $table_left
     * @param type $table_right
     * @param type $comparacion
     * @param type $opelog
     * @return \ModeloBase
     */
    private function whereAddCols($column_left, $alias_left, $column_right, $alias_right, $table_left = null, $table_right = null, $comparacion = "=", $opelog = "AND") {
        // si no tengo el alias lo obtengo
        if (isset($table_left)) {
            $alias_left = $this->findAliasecond_tables($table_left);
        }
        if (isset($table_right)) {
            $alias_right = $this->findAliasecond_tables($table_right);
        }
        //Si tengo alias Formateo las cadenas de acuerdo al tipo de dato
        if (isset($alias_left) && isset($alias_right)) {
            $opelog = $this->validOperatorBeforeAdd($opelog);
            $this->whereDyn .= isset($this->whereDyn) ?
                    " $opelog $this->beginAgrupador $alias_left.$column_left " :
                    " WHERE $this->beginAgrupador $alias_left.$column_left ";
            $this->whereDyn .= " $comparacion $alias_right.$column_right";

            $this->clearAgrupador();
        }
        return $this;
    }
    
    private function clearAgrupador() {
        $this->beginAgrupador = "";
    }
    
    /*
     * @author Christopher Heredia
     * inicia el array de objetos tablas con unicamente la tabla perteneciente
     * a la clase que esta heredando
     */

    private function iniciaAliasecond_tables() {
        $this->tables = null;
        $this->tables = array();
        $this->addAliasecond_tables($this->table_name, "tab", 0);
    }

    /*
     * @author Christopher Heredia
     * Agrega la tabla y su alias respectivo
     */

    private function addAliasecond_tables($table_name, $alias = null, $index = 0) {
        $table = new stdClass();
        $table->name = $table_name;
        if (!isset($alias)) {
            $index = $this->getIndexAliasecond_tables();
            $alias = "tab" . $index;
        }
        $table->alias = $alias;
        $table->index = $index;
        array_push($this->tables, $table);
        return $alias;
    }

    /*
     * @author Christopher Heredia
     * buscar la tabla y obtenemos su alias respectivo
     */
    private function findAliasecond_tables($table_name) {
        foreach ($this->tables as $tab) {
            if ($tab->name == $table_name)
                return $tab->alias;
        }
        return null;
    }

    /*
     * buscar el index mas alto y sumarle 1
     */
    private function getIndexAliasecond_tables() {
        $index = 0;
        foreach ($this->tables as $tab) {
            $index = ($tab->index > $index) ? $tab->index : $index;
        }
        return $index + 1;
    }
// </editor-fold>
}