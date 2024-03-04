<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modelo/almacen/ProgramacionAtencion.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoAtencionNegocio.php';
require_once __DIR__ . '/MovimientoControlador.php';

class MovimientoAtencionControlador extends MovimientoControlador {

    public function buscarDocumentoRelacionPorCriterio() {

        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $opcionId = $this->getOpcionId();

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $empresaId = $this->getParametro("empresa_id");
        $configuracionesDocumentoACopiar = MovimientoNegocio::create()->obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId);
        $documentoTipos = $configuracionesDocumentoACopiar->documento_tipo;

        if ($criterios["documento_tipo_ids"] == '') {
            foreach ($documentoTipos as $index => $docTipo) {
                $criterios["documento_tipo_ids"][$index] = $docTipo[id];
            }
        }

        $transferenciaTipo = $movimientoTipo[0]["transferencia_tipo"];
        $respuesta = MovimientoAtencionNegocio::create()->buscarDocumentoACopiar($criterios, $elementosFiltrados, $columns, $order, $start, $transferenciaTipo);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
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

        $data = MovimientoAtencionNegocio::create()->obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function obtenerDocumentoRelacionDetalle() {
        $opcionId = $this->getOpcionId();
        $documentoRelacionados = $this->getParametro("documentos_relacionados");

        $tempDocumentosRelacionados = array();

        foreach ($documentoRelacionados as $index => $item) {
            if ($item['tipo'] == 1) {
                array_push($tempDocumentosRelacionados, $item);
            }
        }

        $documentoRelacionados = $tempDocumentosRelacionados;

        return $respuesta->detalleDocumento = MovimientoAtencionNegocio::create()->obtenerDocumentoRelacionDetalle($movimientoId = null, $documentoId = null, $opcionId, $documentoRelacionados);
        
        //OBTENER DATA DE UNIDAD DE MEDIDA
        $documentoDetalle=$respuesta->detalleDocumento;
        foreach ($documentoDetalle as $index=>$item){
            $bienId=$item['bien_id'];
            $unidadMedidaId=$item['unidad_medida_id'];
            $precioTipoId=$item['precio_tipo_id'];
            
            $data = MovimientoNegocio::create()->obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision);    
            $documentoDetalle[$index]['dataUnidadMedida']=$data;
        }
        $respuesta->detalleDocumento=$documentoDetalle;
        //FIN OBTENER DATA UNIDAD MEDIDA  
        
        return $respuesta->detalleDocumento;
    }

    public function obtenerStockActual() {
        $bienId = $this->getParametro("bienId");
        $indice = $this->getParametro("indice");
        $organizadorId = $this->getParametro("organizadorId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $documentoRelacion = $this->getParametro("documentoRelacion");
        
        $stock= MovimientoAtencionNegocio::create()->obtenerStockActual($bienId,$indice,$organizadorId,$unidadMedidaId,$documentoRelacion);
        return $stock;
    }

    public function enviar() {
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $monedaId = $this->getParametro("monedaId");
        $accionEnvio = $this->getParametro("accionEnvio");
        //gclv: campo de tipo de pago (contado, credito)
        $tipoPago = $this->getParametro("tipoPago");
        $listaPagoProgramacion = $this->getParametro("listaPagoProgramacion");
        $periodoId = $this->getParametro("periodoId");
        
        return MovimientoAtencionNegocio::create()->validarGenerarDocumentoAdicional($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion,null,$periodoId,null);
    }
    
    public function buscarDocumentoRelacion(){
        $empresaId = $this->getParametro("empresa_id");
        $valor = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();
           
                
        $tipoIds = '(0),(1),(4)';
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];
        $documentoTipoArray = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId,$empresaId, $tipoIds);
        $documentoTipoIdArray = [];
        foreach ($documentoTipoArray as $index => $docTipo) {
            $documentoTipoIdArray[] = $docTipo[id];
        }
        
        $response->dataPersona = MovimientoAtencionNegocio::create()->buscarPersonasXDocumentoTipoXValor($documentoTipoIdArray, $valor);
        $response->dataDocumentoTipo = MovimientoAtencionNegocio::create()->buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdArray, $valor);
        $response->dataSerieNumero = MovimientoAtencionNegocio::create()->buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdArray, $valor);
        return $response;
    }
    
    public function obtenerAtencion(){
        $documentoId = $this->getParametro("documentoId");
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $respuesta->dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
        $respuesta->dataPAtencion = ProgramacionAtencion::create()->obtenerPAtencionXDocumentoId($documentoId); 
        return $respuesta;
    }


    public function obtenerStockParaProductosDeCopia() {
        $organizadorDefectoId = $this->getParametro("organizadorDefectoId");
        $detalle = $this->getParametro("detalle");
        $documentoRelacion = $this->getParametro("documentoRelacion");

        $data= MovimientoAtencionNegocio::create()->obtenerStockParaProductosDeCopia($organizadorDefectoId,$detalle,$documentoRelacion);
        return $data;        
    }   
}
