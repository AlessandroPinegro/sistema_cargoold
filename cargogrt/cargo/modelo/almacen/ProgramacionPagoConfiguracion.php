<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ProgramacionPagoConfiguracion extends ModeloBase {

    /**
     * 
     * @return ProgramacionPagoConfiguracion
     */
    static function create() {
        return parent::create();
    }

    public function listarProgramacionPagoConfiguracion() {
        $this->commandPrepare("sp_programacion_pago_configuracion_listar");
        return $this->commandGetData();
    }

    public function guardarProgramacionPagoConfiguracion($programacionPagoConfiguracionId, $descripcion, $proveedorId, $comentario, $usuCreacion) {
        $this->commandPrepare("sp_programacion_pago_configuracion_guardar");
        $this->commandAddParameter(":vin_id", $programacionPagoConfiguracionId);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_persona_id", $proveedorId);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }

    public function eliminarProgramacionPagoConfiguracionBienTipo($programacionPagoConfiguracionId) {
        $this->commandPrepare("sp_programacion_pago_configuracion_bien_tipo_eliminar");
        $this->commandAddParameter(":vin_ppago_configuracion_id", $programacionPagoConfiguracionId);
        return $this->commandGetData();
    }

    public function guardarProgramacionPagoConfiguracionBienTipo($programacionPagoConfiguracionId, $bienTipoId, $usuCreacion) {
        $this->commandPrepare("sp_programacion_pago_configuracion_bien_tipo_guardar");
        $this->commandAddParameter(":vin_ppago_configuracion_id", $programacionPagoConfiguracionId);
        $this->commandAddParameter(":vin_bien_tipo_id", $bienTipoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }

    public function guardarProgramacionPagoConfiguracionDetalle($programacionPagoConfiguracionId, $programacionPagoDetalleId, $indicadorId, $dias, $porcentaje, $usuCreacion) {
        $this->commandPrepare("sp_programacion_pago_configuracion_detalle_guardar");
        $this->commandAddParameter(":vin_ppago_configuracion_id", $programacionPagoConfiguracionId);
        $this->commandAddParameter(":vin_id", $programacionPagoDetalleId);
        $this->commandAddParameter(":vin_tabla_id", $indicadorId);
        $this->commandAddParameter(":vin_dias", $dias);
        $this->commandAddParameter(":vin_porcentaje", $porcentaje);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }
    
    public function eliminarProgramacionPagoConfiguracionDetalle($id){
        $this->commandPrepare("sp_programacion_pago_configuracion_detalle_eliminarXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerProgramacionPagoConfiguracionXId($ppagoConfiguracionId){
        $this->commandPrepare("sp_programacion_pago_configuracion_obtenerXid");
        $this->commandAddParameter(":vin_id", $ppagoConfiguracionId);
        return $this->commandGetData();        
    }
    
    public function obtenerProgramacionPagoConfiguracionDetalleXId($ppagoConfiguracionId){
        $this->commandPrepare("sp_programacion_pago_configuracion_detalle_obtenerXppagoConfId");
        $this->commandAddParameter(":vin_ppago_configuracion_id", $ppagoConfiguracionId);
        return $this->commandGetData();        
    }

    public function actualizarEstadoProgramacionPagoConfiguracion($ppagoId,$estado) {
        $this->commandPrepare("sp_programacion_pago_configuracion_actualizarEstadoXid");
        $this->commandAddParameter(":vin_ppago_configuracion_id", $ppagoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

}
