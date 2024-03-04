<?php

/**
 * Description of Base
 *
 * @author Christopher Heredia Lozada
 */
require_once __DIR__.'/Connection.php';
require_once __DIR__.'/CommonsPersistence.php';
require_once __DIR__.'/../enumeraciones/Schema.php';
require_once __DIR__.'/../exceptions/ModeloException.php';
require_once __DIR__.'/../../util/Configuraciones.php';
require_once __DIR__.'/../../util/ObjectUtil.php';

class Base {
    public $has_error;
    public $last_error;
    public $key_error;
    public $type_error;
    public $data;
    
    /**
     *
     * @var Connection
     */
    public $c;
    
    /**
     *
     * @var CommonsPersistence 
     */
    function __construct() {
        $this->c = CommonsPersistence::create()->c;
    }
   
    /**
     * Seteo de mensaje personalizado en caso todo haya ocurrido correctamente.
     * 
     * @param int $clave Clave de tipo  MENSAJE EMERGENTE | TOOLTIP (12) de la tabla Idioma contenido 
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function setMensajeEmergente($mensaje = null, $concatenacion = null, $tipo = Configuraciones::MENSAJE_OK){
        CommonsPersistence::create()->setMensajeEmergente($mensaje, $concatenacion, $tipo);
    }
    
    public function validateResponse($response){
        return CommonsPersistence::create()->validateResponse($response);
    }
    /**
     * Obteniendo el mensaje personalizado en caso todo haya ocurrido correctamente.
     * 
     * @param int $clave Clave de tipo  MENSAJE EMERGENTE | TOOLTIP (12) de la tabla Idioma contenido 
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function getMensajeEmergente(){
        return CommonsPersistence::create()->getMensajeEmergente();
    }
    
    
    /**
     * Funcion que permite settear el ultimo error en la clase
     * 
     * @param string $cadena Cadena de error que se va a setear
     * @param IdiomaContenidoTipo $tipo Es el tipo de error que se está seteando
     * 
     * @author Chritopher Heredia <cheredia@imaginatecperu.com>
     */
    public function setError($cadena, $tipo){
        $this->data = NULL;
        $this->has_error = TRUE;
        $this->key_error = 0;       //Significa que no tiene una CLAVE
        $this->type_error = $tipo;
        $this->last_error = $cadena;
    }
    
    /**
     * Aprobechamos que todos heredan de base para añadir las transacciones
     */
    public function beginTransaction(){
        CommonsPersistence::create()->c->beginTransaction();
    }
    public function commitTransaction(){
        CommonsPersistence::create()->c->commitTransaction();
    }
    public function rollbackTransaction(){
        CommonsPersistence::create()->c->rollbackTransaction();
    }
    public function clearConnection(){
        CommonsPersistence::create()->c->clearConnection();
    }
    public function isEmpty($object){
        return ObjectUtil::isEmpty($object);
    }
}
?>