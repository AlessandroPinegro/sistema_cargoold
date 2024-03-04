<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoTipoNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PruebaCopiaNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienNegocio.php';

class PruebaCopiaControlador extends AlmacenIndexControlador {

    public function obtenerConfiguracionesIniciales() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        return PruebaCopiaNegocio::create()->obtenerConfiguracionInicial($opcionId, $empresaId);
    }

    public function obtenerDocumentoTipo() {
        $opcionId = $this->getOpcionId();
        return PruebaCopiaNegocio::create()->obtenerDocumentoTipo($opcionId);
    }

    public function obtenerUnidadMedida() {
        $bienId = $this->getParametro("bienId");
        $opcionId = $this->getOpcionId();
        $unidad = UnidadNegocio::create()->obtenerActivasXBien($bienId);
        $respuesta = new stdClass();
        $respuesta->unidad_medida = $unidad;
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $respuesta->precio = MovimientoTipoNegocio::create()->obtenerPrecio($bienId, $movimientoTipo);
        return $respuesta;
    }

    public function obtenerStockAControlar() {
        $opcionId = $this->getOpcionId();
        $bienId = $this->getParametro("bienId");
        $organizadorId = $this->getParametro("organizadorId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $cantidad = $this->getParametro("cantidad");
        return PruebaCopiaNegocio::create()->obtenerStockAControlar($opcionId, $bienId, $organizadorId, $unidadMedidaId, $cantidad);
    }

    public function enviar() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $this->setTransaction();
        return PruebaCopiaNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck);
    }

    public function obtenerDocumentos() {
        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        // seccion de consumir negocio
        $data = PruebaCopiaNegocio::create()->obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $responseAcciones = PruebaCopiaNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $response_cantidad_total = PruebaCopiaNegocio::create()->obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        // seccion de respuesta
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            for ($j = 0; $j < count($responseAcciones); $j++) {

                if ($responseAcciones[$j]['id'] == 5) {
                    if ($data[$i]['documento_relacionado'] <= 0) {
                        $stringAcciones.='';
                    } else {
                        $stringAcciones .= "<a href='#' onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i><b></a>&nbsp;\n";
                    }
                } else {


                    if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4)) {
                        $stringAcciones.='';
                    } else {

                        if ($responseAcciones[$j]['id'] == 1) {
                            $datoPivot = $data[$i]['documento_tipo_id'];
                        } else {
                            $datoPivot = $data[$i]['movimiento_id'];
                        }

                        $stringAcciones .= "<a href='#' onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i><b></a>&nbsp;\n";
                    }
                }
            }
            $data[$i]['acciones'] = $stringAcciones;
        }


//        for ($i = 0; $i < $tamanio; $i++) {
//                 if ($data[$i]['documento_estado_id']!=2) {
//                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
//                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>&nbsp;\n".
//                        "<a href='#' onclick='visualizarDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['movimiento_id'] .")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
//                      if($data[$i]['documento_estado_id']!=3)
//                      {
//                            $data[$i]['acciones'].= "<a href='#' onclick='aprobar(" . $data[$i]['documento_id'].")' title='Aprobar'><b><i class='ion-checkmark-circled' style='color:#5cb85c;'></i><b></a>&nbsp;\n";   
//                      }
//                        
//            } else {
//                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n".
//                                        "<a href='#' onclick='visualizarDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['movimiento_id'] .")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
//                                        "<a href='#' onclick='AprobarMovimiento(" . $data[$i]['documento_id'] . "," . $data[$i]['movimiento_id'] .")' title='Aprobar'><b><i class='ion-checkmark-circled' style='color:#5cb85c;'></i><b></a>&nbsp;\n";
//            }
//        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentoTipoDato() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
    }

    public function getAllPersonaTipo() {
        return PersonaNegocio::create()->getAllPersonaTipo();
    }

    public function enviarEImprimir() {

        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $this->setTransaction();
        return PruebaCopiaNegocio::create()->enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle);
    }

    public function getAllProveedor() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
//       return PersonaNegocio::create()->obtenerActivas();
    }

    public function getAllLoaderBien() {
        $opcionId = $this->getOpcionId();
        return PruebaCopiaNegocio::create()->obtenerConfiguracionInicial($opcionId);
    }

    public function obtenerStockPorBien() {
        $bienId = $this->getParametro("bienId");
        $empresaId = $this->getParametro("empresaId");
        return BienNegocio::create()->obtenerStockPorBien($bienId, $empresaId);
    }

    public function obtenerPrecioPorBien() {
        $bienId = $this->getParametro("bienId");
        return BienNegocio::create()->obtenerPrecioPorBien($bienId);
    }

    public function imprimir() {
        $documentoId = $this->getParametro("id");
        $documentoTipoId = $this->getParametro("documento_tipo_id");
        return PruebaCopiaNegocio::create()->imprimir($documentoId, $documentoTipoId);
    }

    public function anular() {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();
        return PruebaCopiaNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function aprobar() {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 3;
        $usuarioId = $this->getUsuarioId();
        return PruebaCopiaNegocio::create()->aprobar($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function visualizarDocumento() {
        $documentoId = $this->getParametro("documento_id");
        $movimientoId = $this->getParametro("movimiento_id");
        return PruebaCopiaNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
    }

    //Area de funciones para copiar documento las cuales ire haciendo en 
    //el transcurso de la semana

    public function ConfiguracionesBuscadorCopiaDocumento() {
        $empresaId = $this->getParametro("empresa_id");
        return PruebaCopiaNegocio::create()->ConfiguracionesBuscadorCopiaDocumento($empresaId);
    }

    public function buscarDocumentoACopiar() {

        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $respuesta = PruebaCopiaNegocio::create()->buscarDocumentoACopiar($criterios, $elementosFiltrados, $columns, $order, $start);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

//    public function obtenerDetalleDocumentoACopiar() {
//        
//        $opcionId = $this->getOpcionId();
//        $documentoOrigenId =  $this->getParametro("documento_id_origen");
//        $documentoDestinoId =  $this->getParametro("documento_id_destino");
//        $movimientoId =  $this->getParametro("movimiento_id");
//        $documentoId =  $this->getParametro("documento_id");
//
//        return PruebaCopiaNegocio::create()->obtenerDetalleDocumentoACopiar($documentoOrigenId,$documentoDestinoId,$movimientoId,$documentoId,$opcionId);
//        
//    }
    public function obtenerDetalleDocumentoACopiar() {

        $opcionId = $this->getOpcionId();
        $documentoOrigenId = $this->getParametro("documento_id_origen");
        $documentoDestinoId = $this->getParametro("documento_id_destino");
        $movimientoId = $this->getParametro("movimiento_id");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");

        return PruebaCopiaNegocio::create()->obtenerDetalleDocumentoACopiar($documentoOrigenId, $documentoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
    }

    public function obtenerDocumentosRelacionados() {

        $documentoId = $this->getParametro("documento_id");

        return PruebaCopiaNegocio::create()->obtenerDocumentosRelacionados($documentoId);
    }

    public function obtenerDetalleDocumentoACopiarSoloDetalle() {

        $opcionId = $this->getOpcionId();
        $documentoRelacionados = $this->getParametro("documentos_relacionados");

        return $respuesta->detalleDocumento = PruebaCopiaNegocio::create()->obtenerDetalleDocumentoACopiarSoloDetalle($movimientoId = null, $documentoId = null, $opcionId, $documentoRelacionados);
    }

}
