<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ProgramacionPago extends ModeloBase {

    /**
     * 
     * @return ProgramacionPago
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosPPagoXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $serie, $numero, $monedaId,$estadoProgramacion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_documento_ppago_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_bl_desde", $fechaBlInicio);
        $this->commandAddParameter(":vin_fecha_bl_hasta", $fechaBlFin);
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

    public function obtenerCantidadDocumentosPPagoXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $serie, $numero, $monedaId, $estadoProgramacion,$columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_documento_ppago_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_bl_desde", $fechaBlInicio);
        $this->commandAddParameter(":vin_fecha_bl_hasta", $fechaBlFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado_programacion", $estadoProgramacion);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_bl_desde", $fechaBlInicio);
        $this->commandAddParameter(":vin_fecha_bl_hasta", $fechaBlFin);
        $this->commandAddParameter(":vin_fecha_programada_desde", $fechaProgramadaInicio);
        $this->commandAddParameter(":vin_fecha_programada_hasta", $fechaProgramadaFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estadoPPago);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadProgramacionPagoDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_bl_desde", $fechaBlInicio);
        $this->commandAddParameter(":vin_fecha_bl_hasta", $fechaBlFin);
        $this->commandAddParameter(":vin_fecha_programada_desde", $fechaProgramadaInicio);
        $this->commandAddParameter(":vin_fecha_programada_hasta", $fechaProgramadaFin);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado", $estadoPPago);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleInicialXDocumentoId($documentoId) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerInicialXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function guardarProgramacionPago($documentoId, $fechaTentativa, $personaId, $usuCreacion) {
        $this->commandPrepare("sp_programacion_pago_guardar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_calculo", $fechaTentativa);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }
    //ap
    public function guardarProgramacionPagoDetalle($ppagoId, $programacionPagoDetalleId, $indicadorId, $dias, $fechaProgramada, $importe, $estadoId, $usuCreacion,$aprobacion_parcial) {
        $this->commandPrepare("sp_programacion_pago_detalle_guardar");
        $this->commandAddParameter(":vin_ppago_id", $ppagoId);
        $this->commandAddParameter(":vin_id", $programacionPagoDetalleId);
        $this->commandAddParameter(":vin_tabla_id", $indicadorId);
        $this->commandAddParameter(":vin_dias", $dias);
        $this->commandAddParameter(":vin_fecha_programada", $fechaProgramada);
        $this->commandAddParameter(":vin_importe", $importe);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        $this->commandAddParameter(":vin_aprobacion_parcial", $aprobacion_parcial);
        return $this->commandGetData();
    }

    public function eliminarProgramacionPagoDetalle($id) {
        $this->commandPrepare("sp_programacion_pago_detalle_eliminarXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleXDocumentoId($documentoId) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function actualizarEstadoPPagoDetalle($ppagoDetalleId, $nuevoEstado) {
        $this->commandPrepare("sp_programacion_pago_detalle_actualizarEstadoXid");
        $this->commandAddParameter(":vin_ppago_detalle_id", $ppagoDetalleId);
        $this->commandAddParameter(":vin_estado", $nuevoEstado);
        return $this->commandGetData();
    }

    public function obtenerIndicadorXDocumentoTipoId($documentoTipoId) {
        $this->commandPrepare("sp_tabla_indicador_ppago_obtenerXDocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    public function obtenerPendientePorLiberarXFechaProgramada($fecha) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerPorLiberarXFechaProgramada");
        $this->commandAddParameter(":vin_fecha_programada", $fecha);
        return $this->commandGetData();
    }

    public function obtenerLiberadoPendienteDePagoXFechaProgramada($fecha) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerLiberadoPendientePagoXFecha");
        $this->commandAddParameter(":vin_fecha_programada", $fecha);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_programacion_pago_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleLiberadoPendienteDePagoXDocumentoIdXFecha($documentoId, $fecha) {
        $this->commandPrepare("sp_ppago_detalle_obtenerLiberadoPendientePagoXDocumentoIdXFecha");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleSinIndicadorXDocumentoId($documentoId) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerSinIndicadorXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerProgramacionPagoDetalleInicialXAprobacionParcial($documentoId) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerInicialXAprobacionParcial");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }
    
    public function obtenerLiberadoPendientePagoXBCP($fechaProgramada, $proveedor, $monedaId, $importe) {
        $this->commandPrepare("sp_programacion_pago_detalle_obtenerLiberadoPendientePagoXBCP");
        $this->commandAddParameter(":vin_fecha_programada", $fechaProgramada);
        $this->commandAddParameter(":vin_proveedor", $proveedor);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_importe", $importe);
        return $this->commandGetData();
    }

}