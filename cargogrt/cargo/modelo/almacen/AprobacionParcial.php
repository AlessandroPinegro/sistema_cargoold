<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class AprobacionParcial extends ModeloBase {

    /**
     * 
     * @return AprobacionParcial
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosPPagoXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId,$estadoProgramacion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_aprobacion_parcial_documento_obtenerXCriterios");
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

    public function obtenerCantidadDocumentosPPagoXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId, $estadoProgramacion,$columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_aprobacion_parcial_documento_obtenerXCriterios_contador");
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

    public function obtenerProgramacionPagoDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_aprobacion_parcial_detalle_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
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

    public function obtenerCantidadProgramacionPagoDetalleXCriterios($documentoTipoIds, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_aprobacion_parcial_detalle_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
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
    
    public function obtenerPendientePorAprobarXFechaProgramada($fechaProgramada){
        $this->commandPrepare("sp_aprobacion_parcial_detalle_obtenerPorAprobarXFechaProgramada");
        $this->commandAddParameter(":vin_fecha_programada", $fechaProgramada);
        return $this->commandGetData();
    }
}
