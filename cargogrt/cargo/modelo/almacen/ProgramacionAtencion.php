<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ProgramacionAtencion extends ModeloBase {

    /**
     * 
     * @return ProgramacionAtencion
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosPAtencionXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId,$estadoProgramacion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_documento_patencion_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado_programacion", $estadoProgramacion);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosPAtencionXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId, $estadoProgramacion,$columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_documento_patencion_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado_programacion", $estadoProgramacion);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function obtenerProgramacionAtencionDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPAtencion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_patencion_detalle_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_programada_desde", $fechaProgramadaInicio);
        $this->commandAddParameter(":vin_fecha_programada_hasta", $fechaProgramadaFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estadoPAtencion);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadProgramacionAtencionDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPAtencion, $columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_patencion_detalle_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_programada_desde", $fechaProgramadaInicio);
        $this->commandAddParameter(":vin_fecha_programada_hasta", $fechaProgramadaFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estadoPAtencion);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function guardarProgramacionAtencionDetalle($patencionId,$moviBienId,$organizadorId, $cantidad, $fechaProgramada, $estadoId, $usuCreacion) {
        $this->commandPrepare("sp_patencion_guardar");
        $this->commandAddParameter(":vin_patencion_id", $patencionId);
        $this->commandAddParameter(":vin_movimiento_bien_id", $moviBienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_cantidad", $cantidad);
        $this->commandAddParameter(":vin_fecha_programada", $fechaProgramada);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }

    public function eliminarProgramacionAtencionDetalle($id) {
        $this->commandPrepare("sp_patencion_eliminarXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPAtencionXDocumentoId($documentoId) {
        $this->commandPrepare("sp_patencion_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function actualizarEstadoPAtencionDetalle($patencionDetalleId, $nuevoEstado) {
        $this->commandPrepare("sp_patencion_actualizarEstadoXid");
        $this->commandAddParameter(":vin_patencion_id", $patencionDetalleId);
        $this->commandAddParameter(":vin_estado", $nuevoEstado);
        return $this->commandGetData();
    }
    
    public function obtenerPAtencionInicialXDocumentoId($documentoId){
        $this->commandPrepare("sp_patencion_obtenerInicialXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();        
    }
    
    public function obtenerPAtencionXEstadoXFechaProgramada($patencionEstado,$fechaProgramada) {
        $this->commandPrepare("sp_patencion_obtenerXEstadoXFechaProgramada");
        $this->commandAddParameter(":vin_patencion_estado", $patencionEstado);
        $this->commandAddParameter(":vin_fecha_programada", $fechaProgramada);
        return $this->commandGetData();                
    }
    
    public function obtenerPAtencionLiberadasXPAtencionId($patencionId) {
        $this->commandPrepare("sp_patencion_obtenerLiberadosXpatencionId");
        $this->commandAddParameter(":vin_patencion_id", $patencionId);
        return $this->commandGetData();                
    }
    
    public function obtenerPAtencionXId($id){
        $this->commandPrepare("sp_patencion_obtenerXId");
        $this->commandAddParameter(":vin_patencion_id", $id);
        return $this->commandGetData();         
    }
    
    public function obtenerDocumentoAtencionEstadoLogico($documentoId){
        $this->commandPrepare("sp_documento_patencion_obtener_estado_logicoXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();                 
    }
    
    public function obtenerCantidadAtendidaXMovimientoBienId($movimientoBienId){
        $this->commandPrepare("sp_movimiento_bien_obtenerCantidadAtendida");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        return $this->commandGetData();                         
    }
}
