<?php

require_once __DIR__ . '/../exceptions/ModeloException.php';

class Connection {

    private $host = '52.4.169.183\SQL2019';
    private $dbname = 'ittsa220916';
    private $username = 'admin';
    private $password = 'Abc123+++';

    /**
     *
     * @var PDO
     */
    private $con;
    private $time_zone = '-05:00';
    //Variable de Comando
    private $sp_name;
    private $sp_fields;
    private $sp_params;
    private $sp_order;
    private $sp_where;
    private $sp_where_agrupador;
    //Variables internas de la clase
    public $last_query = '';
    public $last_error = array();
    public $has_error = FALSE;
    public $last_id = 0;

    //Objecto de instancia


    public function __construct() {
        $this->connect();
    }

    public function __destruct() {
        $this->clearConnection();
        //$this->destruir();
    }

    public function clearConnection() {
        $this->con = NULL;
    }

    private function connect() {
        try {
            //$this->con = new mysqli($this->host, $this->username, $this->password, $this->dbname);
            //if (!ObjectUtil::isEmpty($this->time_zone)) $this->con->exec("SET TIME ZONE '$this->time_zone';");
            //pg_set_client_encoding($this->con, "UTF-8");

            $this->con = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->exec("set names utf8");
            if (!ObjectUtil::isEmpty($this->time_zone))
                $this->con->exec("SET TIME_ZONE ='$this->time_zone';");
            //mysql_client_encoding($this->con, "UTF-8");
        } catch (PDOException $e) {
            throw new ModeloException("No fue posible realizar la conexión con la base de datos");
        }
    }

    private function close() {
        $this->con->close();
    }

    /**
     * 
     * @return PDO
     */
    public function getPDO() {
        return $this->con;
    }

    /**
     * Seteamos la zona horaria y se asigna a la variable conexión
     * @param type $value
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setTimeZone($value) {
        $this->time_zone = $value;
        $this->con = NULL;
        $this->connect();
    }

    /**
     * Seteamos la zona horaria y se asigna a la variable conexión
     * @param type $value
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setDB($dbname) {
        $this->dbname = $dbname;
    }

    /**
     * 
     * @param type $sql Cadena SQL con el Query a ejecutar
     * @param type $format Formato a devolver default (devuelve FETCHALL) y JSON
     * @return null
     */
    function consulta($sql) {
        # Executing our query
        try {
            $this->has_error = FALSE;
            $this->last_id = 0;
            $this->last_query = $sql;

            $obj = $this->con->prepare($sql);
            $this->has_error = !$obj->execute();
            return $obj->fetchAll(PDO::FETCH_ASSOC);
        } catch (mysqli_sql_exception $pdo_error) {
            $this->has_error = TRUE;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage() . '. Sql executed:' . $sql . ' Trace:' . $pdo_error->getTraceAsString());
            return null;
        }
    }

    /**
     * 
     * @param type $sql Cadena para UPDATES y DELETES
     * @return boolean En caso haya error retorna falso y viceversa
     */
    private function ejecuta($sql) {
        # Executing our query
        try {
            $this->last_query = $sql;
            $obj = $this->con->prepare($sql);
            $this->has_error = !$obj->execute();
            $result = $obj->fetchAll(PDO::FETCH_ASSOC);
            return TRUE;
        } catch (mysqli_sql_exception $pdo_error) {
            $this->has_error = true;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage() . '. Sql executed:' . $sql);
            return FALSE;
        }
    }

    private function ejecutaInsert($sql) {
        # Executing our query
        try {
            $this->last_query = $sql;
            $obj = $this->con->prepare($sql);
            $this->has_error = !$obj->execute();
            $result = $obj->fetch(PDO::FETCH_ASSOC);
            $this->last_id = (array_key_exists("id", $result)) ? $result["id"] : 0;
            //$this->last_id = $this->con->lastInsertId();
            return TRUE;
        } catch (mysqli_sql_exception $pdo_error) {
            $this->last_id = 0;
            $this->has_error = true;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage() . '. Sql executed:' . $sql);
            return FALSE;
        }
    }

    /*
     * 
     * METODOS PERSONALIZADOS PARA AGILIZAR EL TRABAJO CON LA BASE DE DATOS
     * 5 Enero del 2013 - Pacho Zuniga
     * 
     */

    /**
     * Devuelve el numero de filas en una tabla o consulta
     * @param type $table
     * @param type $where
     */
    public function getCount($table, $join = NULL, $where = NULL, $group = NULL, $fields = NULL) {
        $exp = (ObjectUtil::isEmpty($group)) ? "COUNT(*)" : "COUNT(" . self::DEFAULT_ALIAS_ESCALAR . ".*)";
        return (int) $this->getEscalar($table, $exp, $join, $where, $group, null, $fields);
    }

    /**
     * Metodo que devuelve un valor escalar de una tabla dada 
     * @param type $table
     * @param type $exp puede ser un valor como count(*), max(field) o simplemente el campo que quiero traer
     * @param type $where
     * @return null
     */
    public function getEscalar($table, $exp, $join = NULL, $where = NULL, $group = NULL, $order = NULL, $fields = NULL) {
        $value = NULL;
        $this->has_error = FALSE;
        try {
            if (!ObjectUtil::isEmpty($group)) {
                $sql = "SELECT  $exp  FROM (SELECT $fields FROM $table ";
                $sql .= $join;
                $sql .= $where;
                $sql .= $group;
                $sql .= $order;
                $sql .= ") AS " . self::DEFAULT_ALIAS_ESCALAR;
            } else {
                $sql = "SELECT " . $exp . " FROM " . $table;
                $sql .= $join;
                $sql .= $where;
                $sql .= $order;
            }
            $obj = $this->con->prepare($sql);
            $obj->bindColumn(1, $value);
            $obj->execute();
            $obj->fetch();
            return $value;
        } catch (mysqli_sql_exception $pdo_error) {
            $this->has_error = TRUE;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage());
            return NULL;
        }
    }

    private function concatLimit($page_config) {
        try {
            if ($page_config) {
                extract($page_config); //Extraigo $pageNumber y $pageSize del array asociativo
                $offset = ($pageNumber - 1) * $pageSize;
                return " LIMIT $pageSize OFFSET $offset";
            }
            return "";
        } catch (Exception $e) {
            throw new ModeloException("Error en page_config. Posiblemente no tenga el formato correcto" . $e->getMessage());
        }
    }

    /**
     * Metodo generico usado para cualquier tipo de consulta compleja
     * @param type $sql
     * @param type $page_config
     * @return type
     */
    public function getData($sql, $page_config = null) {
        $sql .= $this->concatLimit($page_config);
        return $this->consulta($sql);
    }

    /**
     * 
     * @param type $sp_name Nombre del procedimiento almacenado a ejecutar
     */
    public function commandPrepare($sp_name, $sp_fields = '*') {
        $this->sp_name = $sp_name;
        $this->sp_fields = $sp_fields;
        $this->sp_params = array();
        $this->sp_order = array();
        $this->sp_where = null;
        $this->sp_where_agrupador = "";
    }

    /**
     * 
     * @param type $value Valor del parametro a setear en el comando
     */
    function commandAddParameter($key, $value) {
        $this->sp_params[$key] = $this->formatQuoteValue($value, true);
    }

    /**
     * 
     * @return type
     * @throws ModeloException
     */
    function commandGetData($page_config = null) {
        try {
            $this->has_error = FALSE;
            $this->last_query = $this->sp_name;
            $strParams = "";

            for ($iParams = count($this->sp_params); $iParams > 0; $iParams--) {
                $strParams = $strParams . "?,";
            }
            $strParams = substr($strParams, -1 * strlen($strParams), (strlen($strParams) - 1));
            $sentencia = $this->con->prepare("call $this->sp_name($strParams)");

            if (count($this->sp_params) > 0) {
                $i = 1;
                foreach ($this->sp_params as $key => $value) {
                    $sentencia->bindValue($i, $this->formatQuoteValue($value, true));
                    $i = $i + 1;
                }
            }
            $sentencia->execute();
            $data = null;
            do {
                try {
                    $conjunto_filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                    if (!ObjectUtil::isEmpty($conjunto_filas)) {
                        $data = $conjunto_filas;
                    }
                } catch (Exception $ex) {
                    break;
                }
            } while ($sentencia->nextRowset());
            $sentencia->closeCursor();
            return $data;
        } catch (PDOException $pdo_error) {
            //var_dump($pdo_error);
            $this->has_error = TRUE;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage() . '. Procedure executed:' . $this->sp_name);
            return null;
        }
    }

    /**
     * 
     * @return type
     * @throws ModeloException
     */
    function commandGetEscalar($exp, $use_order_by = false) {
        try {
            $value = NULL;
            $this->has_error = FALSE;
            $this->last_query = $this->sp_name;

            $_params = array();
            if (count($this->sp_params) > 0) {
                for ($i = 0; $i < count($this->sp_params); $i++) {
                    $_params[] = '?';
                }
            }
            //Armamos la cadena de consulta para ejecutar el procedure
            $sql = "SELECT $exp FROM {$this->sp_name}(" .
                    implode(', ', $_params) . ")";
            if ($use_order_by)
                $sql .= $this->concatOrderBy();

            $stmt = $this->con->prepare($sql);
            $stmt->bindColumn(1, $value);
            $stmt->execute($this->sp_params);
            $stmt->fetch();
            //$stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $value;
        } catch (mysqli_sql_exception $pdo_error) {
            $this->has_error = TRUE;
            $this->last_error = $pdo_error->getMessage();
            throw new ModeloException($pdo_error->getMessage() . '. Procedure executed:' . $this->sp_name);
            return null;
        }
    }

    /**
     * 
     */
    private function concatOrderBy() {
        if (!ObjectUtil::isEmpty($this->sp_order)) {
            $bFirst = true;
            $sql = '';
            // Key = Columna
            // value = ASC / DESC
            foreach ($this->sp_order as $key => $value) {
                $sql .= ($bFirst) ? " ORDER BY $key $value " : ", $key $value ";
                $bFirst = false;
            }
            return $sql;
        }
        return '';
    }

    /**
     * Obtiene una tabla de la base de datos
     * @param type $table
     * @param type $fields
     * @param type $page_params Objeto donde se encuentran los parametros de 
     * configuracion como pageNumber y pageSize
     * @return type
     */
    public function getDataTable($table, $fields, $join = null, $where = null, $group = null, $order = null, $page_config = null, $limit = null, $offset = null) {
        try {
            $sql = "SELECT " . $fields;
            $sql .= " FROM " . $table;
            $sql .= $join;
            $sql .= $where;
            $sql .= $group;
            $sql .= $order;
            $sql .= $limit;
            $sql .= $offset;
            //echo $sql;
            return $this->getData($sql, $page_config);
        } catch (Exception $e) {
            $this->has_error = TRUE;
            $this->last_error = $e->getMessage();
            throw new ModeloException($e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un registro
     * @param type $table Nombre de la tabla
     * @param type $data data a actualizar
     * @param type $where Where usado para actualizar
     * @return \stdClass
     */
    public function updateRecord($table, $data, $where) {
        $a = "object" === gettype($data) ? get_object_vars($data) : $data;

        $fu = NULL;
        foreach ($a as $key => $value) {
            $a[$key] = $this->formatQuoteValue($value);
            $fu .= !isset($fu) ? " $key = $a[$key] " : ", $key = $a[$key] ";
        }

        $sql = "UPDATE $table SET $fu $where";

        return $this->ejecuta($sql);
    }

    /**
     * Metodo generico encargado de insertar un registro
     * @param type $table
     * @param type $data Puede ser un objeto o un array con Key Value
     * @param type $idName
     */
    public function insertRecord($table, $data, $idName) {
        $this->last_id = 0;
        $a = "object" === gettype($data) ? get_object_vars($data) : $data;
//        unset($a[$idName]);

        foreach ($a as $key => $value) {
            $a[$key] = $this->formatQuoteValue($value);
        }
        $fields = array_keys($a);
        $values = array_values($a);

        //array_walk($values, "quote_array", "'");
        $sql = "INSERT INTO $table (" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ")";
        $sql .= " RETURNING " . $idName;
        //$isok = 
        return $this->ejecutaInsert($sql);
    }

    /**
     * Funcion que formatea el campo a insertar con su comilla en caso de ser cadena
     * @param type $v
     * @return type
     */
    private function formatQuoteValue($v, $is_sp = false) {
        switch (gettype($v)) {
            case "integer":
            case "double":
            case "boolean":
                return $v;
            case "NULL":
                return ($is_sp) ? null : "NULL";
            case "string":
//                $v = mysql_real_escape_string($v);
                return ($is_sp) ? $v : "'$v'";
            default :
        }
    }

    /**
     * Funcion que formatea la cadena segun su operador de comparacion
     * @param type $v
     * @return type
     */
    public function formatParamByOperador($parametro, $comparacion) {
        // tratamos la negacion
        $negacion = (stripos($comparacion, "NOT") !== false) ? "NOT" : "";

        // en el caso que el parametro sea null
        if (is_null($parametro) && $comparacion == ComparacionSQL::igual)
            return " IS NULL ";
        if (is_null($parametro) && $comparacion == ComparacionSQL::diferente)
            return " IS NOT NULL ";

        switch ($comparacion) {
            case ComparacionSQL::not_in:
            case ComparacionSQL::in:
                if (is_array($parametro))
                    $parametro = implode(',', $parametro);
                $cadena = " $negacion " . ComparacionSQL::in . " ($parametro) ";
                break;
            case ComparacionSQL::ilike:
            case ComparacionSQL::not_ilike:
            case ComparacionSQL::like:
            case ComparacionSQL::not_like:
                $comparacion = (stripos($comparacion, "ILIKE") !== false) ? " ILIKE " : "LIKE";
                $cadena = " $negacion $comparacion " . $this->formatQuoteValue($parametro);
                break;
            case ComparacionSQL::Lilike:
            case ComparacionSQL::not_Lilike:
            case ComparacionSQL::Llike:
            case ComparacionSQL::not_Llike:
                $comparacion = (stripos($comparacion, "LILIKE") !== false) ? " ILIKE " : "LIKE";
                $cadena = " $negacion $comparacion " . $this->formatQuoteValue("%$parametro");
                break;
            case ComparacionSQL::Rilike:
            case ComparacionSQL::not_Rilike:
            case ComparacionSQL::Rlike:
            case ComparacionSQL::not_Rlike:
                $comparacion = (stripos($comparacion, "RILIKE") !== false) ? " ILIKE " : "LIKE";
                $cadena = " $negacion $comparacion " . $this->formatQuoteValue("$parametro%");
                break;
            case ComparacionSQL::LRilike:
            case ComparacionSQL::not_LRilike:
            case ComparacionSQL::LRlike:
            case ComparacionSQL::not_LRlike:
                $comparacion = (stripos($comparacion, "LRILIKE") !== false) ? " ILIKE " : "LIKE";
                $cadena = " $negacion $comparacion " . $this->formatQuoteValue("%$parametro%");
                break;
            default :
                $cadena = " " . $comparacion . " " . $this->formatQuoteValue($parametro);
        }
        return $cadena;
    }

    /**
     * Quote array callback function
     *
     * This is walk array callback function that
     * surrounds array element with quotes.
     *
     * @date 08. September 2003
     * @access public
     * @return void
     * @param string &$val Array element to be quouted
     * @param mixed $key Dummy. Not used in the function.
     * @param string $quot Quoute to use. Double quote by default
     */
    private function quote_array(&$val, $key, $quot = '"') {
        $quot_right = array_key_exists(1, (array) $quot) ? $quot[1] : $quot[0];
        $val = is_null($val) ? "null" : $quot[0] . preg_replace("/'/", "''", $val) . $quot_right;
    }

    function beginTransaction() {
        $this->con->beginTransaction();
    }

    function rollbackTransaction() {
        $this->con->rollBack();
    }

    function commitTransaction() {
        $this->con->commit();
    }

    public function deleteRecord($table, $where) {
        $sql = "DELETE FROM $table $where";

        return $this->ejecuta($sql);
    }

}

?>
