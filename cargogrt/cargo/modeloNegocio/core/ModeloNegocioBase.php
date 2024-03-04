<?php

include_once __DIR__.'/../../util/ObjectUtil.php';
include_once __DIR__.'/../../util/DateUtil.php';
include_once __DIR__.'/../../util/Util.php';
include_once __DIR__.'/../../util/Configuraciones.php';
include_once __DIR__.'/../../modelo/exceptions/CriticalException.php';
include_once __DIR__.'/../../modelo/exceptions/ErrorPersException.php';
include_once __DIR__.'/../../modelo/exceptions/InformationException.php';
include_once __DIR__.'/../../modelo/exceptions/WarningException.php';
require_once __DIR__.'/../commons/SeguridadNegocio.php';
/**
 * Description of ModeloNegocioBase
 * Clase que encapsulará metodos genericos para todas las clases del Modelo de Negocio
 * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
 */

class ModeloNegocioBase extends Base{
    const PARAM_IS_INSERT = 'PARAM_IS_INSERT';
    const PARAM_IS_UPDATE = 'PARAM_IS_UPDATE';
    const PARAM_IS_ERRONEO = 'PARAM_IS_ERRONEO';
    
    private $formato;
    static private $_instance = array();  // almacena la instancia de la clase hija
    
    public function __construct() {
        // se ha dejado en el caso se necesite iniciar algunas variables futuras
        //$this->formato = new FormatoNegocio();
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Crea la instancia. 
     * @return get_called_class()
     */
    public static function create()
    {
        $class_child = get_called_class();
        
        if(ObjectUtil::isEmpty(self::$_instance)){
            //self::$_instance["Prod"] = 1;
            self::$_instance[$class_child] = new $class_child();
        }
        elseif(!array_key_exists($class_child, self::$_instance)){
            self::$_instance[$class_child] = new $class_child();
        }
        return self::$_instance[$class_child];
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Función que implementa el metodo save que se encarga de la validación de:
     *  - La estructura del $params
     *  - Redireccionar a un método específico (insert | update)
     *  - Formatear el array $params para que los valores Empty los transforme en NULL
     * @param array $params : Array con la data a insertar o actualizar
     * @param string $nameParamId : Nombre del identificador dentro 
     * del array $params que indica el id a actualizar
     */
    public function save($params, $nameParamId = NULL){
        if(ObjectUtil::isEmpty($nameParamId)) $nameParamId = 'id';
        switch ($this->getTypeSave($params, $nameParamId)){
            case self::PARAM_IS_INSERT:
                $params = $this->getFormatParams($params);
                return $this->insert($params);
                break;
            case self::PARAM_IS_UPDATE:
                $params = $this->getFormatParams($params);
                return $this->update($params[$nameParamId], $params);
                break;
            default:
                // Lanzamos un warning
                throw new WarningException(32); // No se han especificado los parámetros de manera correcta
        }
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Metodo que me indicará si se trata de un update o un insert
     * a través de la existencia de un nombre de id entre sus parametros
     * @param array $params => Parámetros
     * @param string $nameId => Nombre de parámetro que representa el id
     */
    public function getTypeSave($params, $nameId){
        if (!ObjectUtil::isEmpty($params)){
            if(array_key_exists($nameId, $params)){
                return (!ObjectUtil::isEmpty($params[$nameId]))? self::PARAM_IS_UPDATE : self::PARAM_IS_INSERT;
            }
            else
                return self::PARAM_IS_INSERT;
        }
        else
            return self::PARAM_IS_INSERT;
    }
    
    /**
     * Se encarga de traer y setear los formatos (fecha, hora, numero decimales, etc) 
     * según la empresa especificada
     * @param int $empresa_id
     */
    protected function setFormatosByEmpresa($empresa_id = null){
        $this->formato->setFormatosByEmpresa($empresa_id);
    }

    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * 
     * * Setea las variables de $rows, $total y $colsFormat en un arreglo 
     *   y servirá como formato de respuesta al controlador
     *   para que este lo formatee...
     * 
     * @param int $rows => data del grid
     * @param int $total => longitud de la data completa que se va a mostrar en la grilla
     * @param array $colsFormat => Arreglo con las columnas que se desea formatear
     * @param array $colsGroup => Arreglo con las columnas que se quieren agrupar
     * @param int $pageNumber Numero de pagina que se esta consultando
     * @param int $pageSize Tamaño de la pagina
     */
    protected function getPrepareFormatDataGrid($rows, $total, $colsFormat = null, $colsGroup = NULL, $pageNumber = null, $pageSize = null){
        return $this->formato->getPrepareFormatDataGrid($rows, $total, $colsFormat, $colsGroup, $pageNumber, $pageSize);
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * 
     * Setea las variables de $rows, $total y $colsFormat en un arreglo 
     *   y servirá como formato de respuesta al controlador para que este lo formatee...
     * 
     * @param int $rows => data del grid
     * @param int $total => longitud de la data completa que se va a mostrar en la grilla
     * @param array $colsFormat => Arreglo con las columnas que se desea formatear
     * @param array $colsGroup => Arreglo con las columnas que se quieren agrupar
     * @param int $pageNumber Numero de pagina que se esta consultando
     * @param int $pageSize Tamaño de la pagina
     */
    protected function getPrepareFormatRecord($row, $rowFormat = null, $rowGroup = NULL){
        return $this->formato->getPrepareFormatRecord($row, $rowFormat, $rowGroup);
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Funcion encargada de formatear el $params 
     * normalmente luego de haber sido validado para que sea un insert 
     * o un record
     * @param type $params
     */
    protected function getFormatParams($params){
        if (!ObjectUtil::isEmpty($params)){
            $result = array();
            foreach ($params as $key => $valor){
                if (is_string($valor)){
                    $result[$key] = ((strlen($valor) <= 0))? NULL : $valor;
                }
                else{
                    $result[$key] = (ObjectUtil::isEmpty($valor))? NULL : $valor;
                }
            }
            return $result;
        }
        else return NULL;
    }
    
    /**
     * Operaciones de auditoría: 
     *      Insert Log Acceso       => Registra el acceso de un usuario al sistema.
     *      Insert Log Operacion    => Registra las operaciones de un usuario en el sistema.
     *      Update Log Acceso       => Registra el cierre de sesion de un usuario en el sistema.
     * 
     * @author Geancarlo Leiva <gleiva@imaginatecperu.com>
     */
    public function insertLogAcceso($usuario_id, $ip, $navegador) {
        return SeguridadNegocio::create()->insertLogAcceso($usuario_id, $ip, $navegador);
    }

    public function insertLogOperacion($log_acceso_id, $entidad, $operacion, $val_anterior, $val_actual) {
        return SeguridadNegocio::create()->insertLogOperacion($log_acceso_id, $entidad, $operacion, $val_anterior, $val_actual);
    }
    
    public function updateLogAcceso($id) {
        return SeguridadNegocio::create()->updateLogAcceso($id);
    }

    public function updateEstadoVisible($tabla, $campo, $bandera, $id) {
        return SeguridadNegocio::create()->updateEstadoVisible($tabla, $campo, $bandera, $id);
    }
}

?>
