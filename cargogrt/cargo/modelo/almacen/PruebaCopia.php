<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class PruebaCopia extends ModeloBase {

    /**
     * 
     * @return Movimiento
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_movimiento_obtenerXCriterios");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionDesde);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionHasta);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_fecha_tentativa_desde", $fechaTentativaDesde);
        $this->commandAddParameter(":vin_fecha_tentativa_hasta", $fechaTentativaHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_movimiento_consulta_contador");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionDesde);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionHasta);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_fecha_tentativa_desde", $fechaTentativaDesde);
        $this->commandAddParameter(":vin_fecha_tentativa_hasta", $fechaTentativaHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        //  $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        // $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function ObtenerTotalDeRegistros() {
        $this->commandPrepare("sp_obtener_CantidadDeRegistrosDeConsultas");
        return $this->commandGetData();
    }

    public function ObtenerMovimientoTipoPorOpcion($opcionId) {
        $this->commandPrepare("sp_movimiento_obtenerXOpcionId");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function guardar($movimientoTipoId, $estado, $usuarioCreacionId) {
        $this->commandPrepare("sp_movimiento_guardar");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        return $this->commandGetData();
    }
    
    public function sp_movimiento_tipo_documento_tipo_XMovimientoTipoXDocumentoTipo($movimientoTipoId,$documentoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_documento_tipo_XMovimientoTipoXDocumentoTipo");
        $this->commandAddParameter(":vin_movimmiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerMovimientoTipoAcciones($movimientoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_accion_obtenerPorMovimientoTipo");
        $this->commandAddParameter(":vin_movimmiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    // FUNCIONES PARA COPIAR DOCUMENTO

    public function buscarDocumentoACopiar($empresaId, $documentoTipoIds, $personaId, $serie, $numero, $fechaEmisionInicio,
                                           $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados,
                                           $formaOrdenar, $columnaOrdenar, $tamanio) {
        $this->commandPrepare("sp_documento_buscarParaCopiar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limit", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $tamanio);
        return $this->commandGetData();
    }

    public function buscarDocumentoACopiarTotal($empresaId, $documentoTipoIds, $personaId, $serie, $numero, 
                                                $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, 
                                                $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar) {
        $this->commandPrepare("sp_documento_buscarParaCopiar_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

}
