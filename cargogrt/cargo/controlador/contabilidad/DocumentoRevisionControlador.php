<?php

require_once __DIR__ . '/../almacen/MovimientoControlador.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/DocumentoRevisionNegocio.php';

class DocumentoRevisionControlador extends MovimientoControlador {

    public function obtenerDocumentoTipo() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $data = MovimientoNegocio::create()->obtenerDocumentoTipo($opcionId);
//        $data->personasMayorMovimientos = PersonaNegocio::create()->obtenerPersonasMayorMovimiento($opcionId);
        $data->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $data->columna = MovimientoNegocio::create()->obtenerMovimientoTipoColumnaLista($opcionId);
        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $data->estadoNegocioPago = MovimientoNegocio::create()->obtenerDataEstadoNegocioPago();
        $data->movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        //PARA MOSTRAR ICONO DE ACCION EDICION EN LEYENDA        
        //SI HAY ACCION DE EDICION BUSCAR PERFIL
        foreach ($data->acciones as $index => $accion) {
            $data->acciones[$index]['mostrarAccion'] = 1;

            $mostrarAccEdicion = 0;
            if ($accion['id'] == 19) {//EDICION
                $dataPerfil = PerfilNegocio::create()->obtenerPerfilXUsuarioId($usuarioId);
                foreach ($dataPerfil as $itemPerfil) {
                    if ($itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_ID || $itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_TI_ID) {
                        $mostrarAccEdicion = 1;
                    }
                }
                $data->acciones[$index]['mostrarAccion'] = $mostrarAccEdicion;
            }
        }

        return $data;
    }

    public function obtenerDocumentos() {
        // seccion de obtencion de variables
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $data = DocumentoRevisionNegocio::create()->obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        $responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);

        $cantidad_filas = $data[0]["cantidad_filas"];
        $elemntosFiltrados = $cantidad_filas;
        $elementosTotales = $cantidad_filas;
        $tamanio = count($data);

        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            for ($j = 0; $j < count($responseAcciones); $j++) {
                if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4 || $responseAcciones[$j]['id'] == 13 || $responseAcciones[$j]['id'] == 14 || $responseAcciones[$j]['id'] == 19)) {//13 y 14 acciones para QR. 19 Editar
                    $stringAcciones .= '';
                } elseif ((($data[$i]['documento_estado_id'] == 2) && ($responseAcciones[$j]['id'] == 5))) {
                    $stringAcciones .= '';
                } else {
                    if ($responseAcciones[$j]['id'] == 19) {
                        $datoPivot = $data[$i]['movimiento_tipo_id'];
                    } elseif ($responseAcciones[$j]['id'] == 1) {
                        $datoPivot = $data[$i]['documento_tipo_id'];
                    } else {
                        $datoPivot = $data[$i]['movimiento_id'];
                    }
                    $stringAcciones .= "<a  onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i></b></a>&nbsp;\n";
                }
            }
            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function relacionarDocumento() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();
        $documentoIdOrigen = $this->getParametro("documentoIdOrigen");
        $documentoIdARelacionar = $this->getParametro("documentoIdARelacionar");

        return DocumentoEarNegocio::create()->relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId);
    }

    public function buscarCriteriosBusqueda() {
        $busqueda = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();

        $dataPersona = PersonaNegocio::create()->buscarPersonaDocumentoEarXNombreXDocumento($opcionId, $busqueda);
        $resultado->dataPersona = $dataPersona;

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXOpcionXDescripcion($opcionId, $busqueda);
        $resultado->dataDocumentoTipo = $dataDocumentoTipo;

        $dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXOpcionXSerieNumero($opcionId, $busqueda);
        $resultado->dataSerieNumero = $dataSerieNumero;

        return $resultado;
    }

    public function aprobarRechazarVistoBueno() {
        $this->setTransaction();
        $documentoId = $this->getParametro("documentoId");
        $accion = $this->getParametro("accion");
//        $comentarioDocumento = $this->getParametro("comentarioDocumento");
//        //guardamos comentario
//        if (!ObjectUtil::isEmpty($comentarioDocumento)) {
//            $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentarioDocumento);
//        }        
        $razonRechazo = $this->getParametro("razonRechazo");
        $usuarioId = $this->getUsuarioId();
        $respuestaVistoBueno = DocumentoRevisionNegocio::create()->aprobarRechazarVistoBueno($documentoId, $accion, $razonRechazo, $usuarioId);
        return $respuestaVistoBueno;
    }

    public function generarAsientoDocumento($documentoId, $tipo, $usuarioId) {
        try {
            $this->setTransaction();
            if ($tipo == "C") {
                $respuestaVistoBueno = DocumentoRevisionNegocio::create()->aprobarRechazarVistoBueno($documentoId, "AP", NULL, $usuarioId);
            }
            if ($tipo == "V") {
                $respuestaVistoBueno = DocumentoRevisionNegocio::create()->registrosRegistroVenta($documentoId, $usuarioId);
            }
            if ($tipo == "P") {
                $respuestaVistoBueno = ContVoucherNegocio::create()->registrarContVoucherPagos($documentoId, $usuarioId);
            }
            if ($tipo == "R") {
                $respuestaVistoBueno = ContVoucherNegocio::create()->agregarRedondeoAsientoContable($documentoId, $usuarioId);
            }

            $this->setCommitTransaction();
            return $respuestaVistoBueno;
        } catch (Exception $ex) {
            $respuesta->status = 0;
            $respuesta->mensaje = $ex->getMessage();
            return $respuesta;
        }
    }
    
    public function ActualizarAsientoDocumento($documentoId, $tipo, $usuarioId) {
        try {
            $this->setTransaction();
            if ($tipo == "C") {
                $respuestaVistoBueno = DocumentoRevisionNegocio::create()->aprobarRechazarVistoBueno($documentoId, "AP", NULL, $usuarioId);
            }
            if ($tipo == "V") {
                $respuestaVistoBueno = DocumentoRevisionNegocio::create()->ActualizarAsientoVenta($documentoId, $usuarioId);
            }
            if ($tipo == "P") {
                $respuestaVistoBueno = ContVoucherNegocio::create()->registrarContVoucherPagos($documentoId, $usuarioId);
            }
            if ($tipo == "R") {
                $respuestaVistoBueno = ContVoucherNegocio::create()->agregarRedondeoAsientoContable($documentoId, $usuarioId);
            }

            $this->setCommitTransaction();
            return $respuestaVistoBueno;
        } catch (Exception $ex) {
            $respuesta->status = 0;
            $respuesta->mensaje = $ex->getMessage();
            return $respuesta;
        }
    }

}
