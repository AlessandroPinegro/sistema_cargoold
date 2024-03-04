<?php

require_once __DIR__ . '/MovimientoControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoDuaNegocio.php';

class MovimientoDuaControlador extends MovimientoControlador {

    public function obtenerPlanillaImportacion() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoDuaNegocio::create()->obtenerPlanillaImportacion($documentoId);
    }

    public function obtenerDocumentoRelacionVisualizar() {
        $data = parent::obtenerDocumentoRelacionVisualizar();
        $data = MovimientoDuaNegocio::create()->obtenerContabilizacion($data);

        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");

        $cif = MovimientoDuaNegocio::create()->obtenerCostoCifPorMovimientoId($movimientoId);

        if (!ObjectUtil::isEmpty($cif)) {
            $data->cif = $cif;
        }

        return $data;
    }

    public function obtenerDocumentoRelacionDUA() {
        $data = parent::obtenerDocumentoRelacionVisualizar();
        return $data;
    }

    public function obtenerDocumentoRelacion() {
        $opcionId = $this->getOpcionId();
        $documentoTipoOrigenId = $this->getParametro("documento_id_origen");
        $documentoTipoDestinoId = $this->getParametro("documento_id_destino");
        $movimientoId = $this->getParametro("movimiento_id");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");
        $tempDocumentosRelacionados = array();

        foreach ($documentoRelacionados as $index => $item) {
            if ($item['tipo'] == 1) {
                array_push($tempDocumentosRelacionados, $item);
            }
        }

        $documentoRelacionados = $tempDocumentosRelacionados;

        $data = MovimientoNegocio::create()->obtenerDocumentoRelacionDua($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function obtenerDocumentosEarDua() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoDuaNegocio::create()->obtenerDocumentosEarXDocumentoDuaId($documentoId);
    }

    public function relacionarDuaEar() {
        $this->setTransaction();
        $documentoDuaId = $this->getParametro("documentoDuaId");
        $earSeleccionados = $this->getParametro("earSeleccionados");
        $usuarioId = $this->getUsuarioId();

        return MovimientoDuaNegocio::create()->relacionarDuaEar($documentoDuaId, $earSeleccionados, $usuarioId);
    }

    public function enviar() {
        $resDocumento = parent::enviar();

        $duaId = $resDocumento->documentoId;
        $dataEar = MovimientoDuaNegocio::create()->obtenerDocumentosEarXDocumentoDuaId($duaId);

        $respuesta = new stdClass();
        if (ObjectUtil::isEmpty($dataEar->documentoEar)) {
            $respuesta = $resDocumento;
        } else {
            $respuesta = $dataEar;
            $respuesta->documentoId = $duaId;
        }

        return $respuesta;
    }

    //Area de funciones para copiar documento 
    public function obtenerConfiguracionBuscadorDocumentoRelacion() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresa_id");
        return MovimientoDuaNegocio::create()->obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId);
    }

    public function obtenerDocumentosRelacionados() {

        $documentoId = $this->getParametro("documento_id");
        $relacionados = MovimientoDuaNegocio::create()->obtenerDocumentosRelacionados($documentoId);
        return $relacionados;
    }

    public function buscarDocumentoRelacionPorCriterio() {

        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $opcionId = $this->getOpcionId();

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $empresaId = $this->getParametro("empresa_id");
        $configuracionesDocumentoACopiar = MovimientoDuaNegocio::create()->obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId);
        $documentoTipos = $configuracionesDocumentoACopiar->documento_tipo;

        if (ObjectUtil::isEmpty($criterios["documento_tipo_ids"])) {
            $criterios["documento_tipo_ids"] = array();
            foreach ($documentoTipos as $index => $docTipo) {
                $criterios["documento_tipo_ids"][] = $docTipo['id'];
            }
        }

        $transferenciaTipo = $movimientoTipo[0]["transferencia_tipo"];
        $respuesta = MovimientoDuaNegocio::create()->buscarDocumentoACopiar($criterios, $elementosFiltrados, $columns, $order, $start, $transferenciaTipo);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

    public function relacionarDocumento() {
        $usuarioId = $this->getUsuarioId();
        $documentoIdOrigen = $this->getParametro("documentoIdOrigen");
        $documentoIdARelacionar = $this->getParametro("documentoIdARelacionar");
        $this->setTransaction();
        return MovimientoDuaNegocio::create()->relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId);
    }

}
