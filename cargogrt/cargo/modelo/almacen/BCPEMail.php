<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Persona
 *
 * @author CHL
 */
class BCPEMail extends ModeloBase {
    /**
     * 
     * @return BCPEMail
     */
    static function create() {
        return parent::create();
    }

    public function getAll() {
        $this->commandPrepare("sp_bcp_email_getAll");
        return $this->commandGetData();
    }

    public function guardar($ipCliente, $correoId, $remitente, $asunto, $cuerpo, $fecha) {
        $this->commandPrepare("sp_bcp_email_guardar");
        $this->commandAddParameter(":vin_ip_cliente", $ipCliente);
        $this->commandAddParameter(":vin_correo_id", $correoId);
        $this->commandAddParameter(":vin_remitente", $remitente);
        $this->commandAddParameter(":vin_asunto", $asunto);
        $this->commandAddParameter(":vin_cuerpo", $cuerpo);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }
    
    public function actualizarExtraccion($bcpEmailId, $proveedor, $fecha, $fechaBD, $moneda, $monedaId, $importe, $numeroOperacion, $log){
        $this->commandPrepare("sp_bcp_email_actualizarExtraccion");
        $this->commandAddParameter(":vin_id", $bcpEmailId);
        $this->commandAddParameter(":vin_extraccion_proveedor", $proveedor);
        $this->commandAddParameter(":vin_extraccion_fecha", $fecha);
        $this->commandAddParameter(":vin_extraccion_fechabd", $fechaBD);
        $this->commandAddParameter(":vin_extraccion_moneda", $moneda);
        $this->commandAddParameter(":vin_extraccion_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_extraccion_importe", $importe);
        $this->commandAddParameter(":vin_extraccion_numero_operacion", $numeroOperacion);
        $this->commandAddParameter(":vin_log", $log);
        return $this->commandGetData();
    }
    
    public function actualizarEstado($bcpEmailId, $transferenciaId, $documentoId){
        $this->commandPrepare("sp_bcp_email_actualizarEstado");
        $this->commandAddParameter(":vin_id", $bcpEmailId);
        $this->commandAddParameter(":vin_transferencia_id", $transferenciaId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }
    
    public function obtenerXId($bcpEmailId){
        $this->commandPrepare("sp_bcp_email_obtenerXId");
        $this->commandAddParameter(":vin_id", $bcpEmailId);
        return $this->commandGetData();
    }
    
    public function obtener(){
        $this->commandPrepare("sp_bcp_email_obtener");
        return $this->commandGetData();
    }
    
    public function obtenerXCriterios($documentoTipoIds, $personaId, $transferencia, $serie, $numero, $monedaId, $estado, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_bcp_email_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $transferencia);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    public function obtenerXCriteriosContador($documentoTipoIds, $personaId, $transferencia, $serie, $numero, $monedaId, $estado, $columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_bcp_email_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $transferencia);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    public function actualizarNumeroOperacion($bcpEMailId, $numeroOperacion){
        $this->commandPrepare("sp_bcp_email_actualizarNumeroOperacion");
        $this->commandAddParameter(":vin_id", $bcpEMailId);
        $this->commandAddParameter(":vin_extraccion_numero_operacion", $numeroOperacion);
        return $this->commandGetData();
    }
    
    public function actualizarLog($bcpEMailId, $log){
        $this->commandPrepare("sp_bcp_email_actualizarLog");
        $this->commandAddParameter(":vin_id", $bcpEMailId);
        $this->commandAddParameter(":vin_log", $log);
        return $this->commandGetData();
    }
}
