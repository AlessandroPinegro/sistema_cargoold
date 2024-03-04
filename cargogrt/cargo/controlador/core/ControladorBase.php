<?php

/**
 * ControladorBase: Todos los controladores deben heredar de esta clase
 * 
 * @version 1.0
 * @author Christopher Heredia <cheredia@imaginatecperu.com>
 */

require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../util/ObjectUtil.php';
require_once __DIR__ . '/../../modelo/enumeraciones/FormatoTipo.php';
require_once __DIR__ . '/../../modelo/enumeraciones/FormatoFecha.php';
require_once __DIR__ . '/../../modeloNegocio/core/FormatoNegocio.php';

class ControladorBase extends Base{
// <editor-fold defaultstate="collapsed" desc="region variables y constantes">
    
    public $usuario_id;
    public $usuario_tipo;
    public $sesion_id;
    public $ultimo_acceso;
    public $tag;
    public $params;
    public $time_zone;
    public $control_id;
    
    public $empresa_id;
    
    private $hasTransaction;
    private $hasGetEtiquetas;
    private $etiquetas;
    
    private $formato;
// </editor-fold>
// <editor-fold desc="Métodos principales">
    public function __construct() {
        $this->params = NULL;
        $this->ultimo_acceso = NULL;
        $this->tag  = NULL;
        $this->time_zone = '';
        $this->hasTransaction = FALSE;
        $this->hasGetEtiquetas = FALSE;
        $this->etiquetas = NULL;
        $this->formato = FormatoNegocio::create();
        parent::__construct();
    }
    
    /**
     * Obtiene el valor de cualquier parametro enviado desde la vista
     * 
     * @param type $key
     * @return null
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function getParametro($key)
    {
        if(ObjectUtil::isEmpty($this->params)) return null;
        if(!array_key_exists($key, $this->params)) return null;
        
        $value = $this->params[$key];
        if(is_array($value)) {
            if(ObjectUtil::isEmpty($value)) return null;
        } else {
            if(ObjectUtil::isEmpty($value) || trim($value) == '') return null;
        }
        
        return $value;
    }
    
    /**
     * Obtiene el valor del usuario de la sesión
     * 
     * @return int usuario_id
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function getUsuarioId()
    {
        return $this->getParametro("usuario_id");
    }
    
    /**
     * Obtiene el valor de la opción que estan consultando
     * 
     * @return int usuario_id
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function getOpcionId()
    {
        return $this->getParametro(Configuraciones::PARAM_OPCION_ID);
    }
    
    /**
     * Validamos si el parámetro existe
     * @param type $key
     * @return boolean
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function existeParametro($key)
    {
        if(ObjectUtil::isEmpty($this->params)) return false;
        if(!array_key_exists($key, $this->params)) return false;
        
        return true;
    }
    
    /**
     * 
     * Metodo encargado de marcar e iniciar una transaccion
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setTransaction($value = true) {
        $this->hasTransaction = $value;
        if ($this->hasTransaction === true)
            $this->beginTransaction();
    }
    
    /**
     * Metodo que confirma la transaccion en caso exista una transaccion activa
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setCommitTransaction() {
        if ($this->hasTransaction === true)
            $this->commitTransaction();
        $this->hasTransaction = false;
    }

    /**
     * rollback de la transaccion en caso exista una transaccion activa
     * y marca como que no existe transaccion para que posteriormente no se 
     * intente realizar commit
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setRollbackTransaction() {
        if ($this->hasTransaction === true) {
            $this->rollbackTransaction();
            $this->hasTransaction = FALSE;
        }
    }
    
    public function obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales){
        if(ObjectUtil::isEmpty($data)){
            $data = array();
        }
        $respuesta = new stdClass();
        $respuesta->draw = $this->getParametro("draw");
        $respuesta->data = $data;
        $respuesta->recordsFiltered = $elementosFiltrados;
        $respuesta->recordsTotal = $elementosTotales;
        return $respuesta;
    }
    
// <editor-fold defaultstate="collapsed" desc="pasarela para aplicar formatos">    
    /**
     * Setea los formatos de fecha, hora y cantidad de decimales según la empresa
     * @return type
     */
    protected function setFormatosByEmpresa(){
       $this->formato->setFormatosByEmpresa();
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * 
     * Aplicamos los formatos de:
     * - Fecha
     * - Hora
     * - Numero entero
     * - Numero decimal
     * según se especifiquen en el $colsFormat
     * 
     * @param array $rows Data del grid
     * @param array $colsFormat Formato de columnas
     * @return array
     */
    protected function getFormatRecord($responseToController, $is_array_to_array = false){
        return $this->formato->getFormatRecord($responseToController, $is_array_to_array);
    }
    

    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Metodo que me permite obtener un formato adecuado para manejar la petición bajo demanda que se utiliza en 
     * la vista por parte del componente datagrid de jeasyui:
     * - Se obtiene la ultima data obtenida usando la instancia que se pasa en el parametro $objModeloBase
     * - Se obtiene un count(*) tomando en cuenta solo la clapsula where y join.
     * - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
     * @param type $responseToController
     * @param type $agrupaObj: Este parametro indica si se hara las agrupaciones de Columnas en Objetos
     * @param int $pageNumber
     * @param int $pageSize
     * @param ModeloBase $objModeloBase => Objeto Heredero de la clase ModeloBase
     * @param array $colsFormat => Arreglo con las columnas que se desea formatear
     */
    protected function getFormatDataGrid($responseToController,$agrupaObj=true,$cantidadDecimalesPersonalizado=3){
        $prepare = $this->formato->getPrepareFormatDataGrid($responseToController);
        return $this->formato->getFormatDataGrid($prepare, $agrupaObj, $cantidadDecimalesPersonalizado);
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * Metodo que me permite obtener un formato adecuado para manejar la data 
     * @param type $responseToController
     * @param type $agrupaObj: Este parametro indica si se hara las agrupaciones de Columnas en Objetos
     * @param int $pageNumber
     * @param int $pageSize
     * @param ModeloBase $objModeloBase => Objeto Heredero de la clase ModeloBase
     * @param array $colsFormat => Arreglo con las columnas que se desea formatear
     */
    protected function getFormatData($responseToController,$agrupaObj=true){
        return $this->formato->getFormatData($responseToController, $agrupaObj);
    }
    
    /**
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * 
     * Aplicamos los formatos de:
     * - Fecha
     * - Hora
     * - Numero entero
     * - Numero decimal
     * según se especifiquen en el $colsFormat
     * 
     * @param array $rows Data del grid
     * @param array $colsFormat Formato de columnas
     * @return array
     */
    protected function getFormatColsDataGrid($rows, $colsFormat, $colsGroup = NULL){
        return $this->formato->getFormatColsDataGrid($rows, $colsFormat, $colsGroup);
    }
    
    /**
     * Obtiene el valor formateado en su tipo según su configuración por empresa
     * 
     * @param type $value
     * @param TipoFormato $format
     * @return string
     */
    protected function getValueFormat($value, $format){
        return $this->formato->getValueFormat($value, $format);
    }
    
    protected function getFormatoFecha($value){
        return $this->formato->getFormatoFecha($value);
    }
    
    protected function getFormatoFechaSinHora($value){
        return $this->formato->getFormatoFechaSinHora($value);
    }
    
    protected function getFormatoDecimal($value){
        return $this->formato->getFormatoDecimal($value);
    }
    
    protected function getFormatoDecimalPersonalizado($value,$verSigno=false,$nro_decimales=-1){
        return $this->formato->getFormatoDecimalPersonalizado($value,$verSigno,$nro_decimales);        
    }
    
    protected function getFormatoDecimalSinMiles($value){
        return $this->formato->getFormatoDecimalSinMiles($value);
    }
    
    protected function getFormatoEntero($value){
        return $this->formato->getFormatoEntero($value);
    }
// </editor-fold>    
    
// </editor-fold>  
}

?>
