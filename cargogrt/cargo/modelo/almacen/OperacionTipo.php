<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Description of OperacionTipo
 * 
 * @author Imagina
 */
class OperacionTipo extends ModeloBase
{

    /**
     * 
     * @return OperacionTipo
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerXOpcion($opcionId)
    {
        $this->commandPrepare("sp_operacion_tipo_obtenerXOpcion");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function obtenerDocumentosXCriterios($operacionTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $agenciaId, $cajaId)
    {
        $this->commandPrepare("sp_operacion_obtenerXCriterios");
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
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
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosXCriterios($operacionTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $agenciaId, $cajaId)
    {
        $this->commandPrepare("sp_operacion_consulta_contador");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
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
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        //  $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        // $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    // FUNCIONES PARA COPIAR DOCUMENTO
    public function buscarDocumentoACopiar(
        $empresaId,
        $documentoTipoIds,
        $personaId,
        $serie,
        $numero,
        $fechaEmisionInicio,
        $fechaEmisionFin,
        $fechaVencimientoInicio,
        $fechaVencimientoFin,
        $elementosFiltrados,
        $formaOrdenar,
        $columnaOrdenar,
        $tamanio,
        $operacionTipoId,
        $documentoTipoId
    ) {
        $this->commandPrepare("sp_documento_operacion_buscarParaCopiar");
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
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    public function buscarDocumentoACopiarTotal(
        $empresaId,
        $documentoTipoIds,
        $personaId,
        $serie,
        $numero,
        $fechaEmisionInicio,
        $fechaEmisionFin,
        $fechaVencimientoInicio,
        $fechaVencimientoFin,
        $formaOrdenar,
        $columnaOrdenar,
        $operacionTipoId,
        $documentoTipoId
    ) {
        $this->commandPrepare("sp_documento_operacion_buscarParaCopiar_contador");
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
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    //FIN COPIAR DOCUMENTO
}
