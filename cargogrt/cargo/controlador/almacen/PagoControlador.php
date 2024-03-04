<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/RegistroComprasNegocio.php';

class PagoControlador extends AlmacenIndexControlador {

    // Sección de cobranzas
    public function obtenerDocumentoTipo() {
        $tipoPago = 1;
        $tipoProvision = 3;
        $empresa_id = $this->getParametro("empresa_id");
        return PagoNegocio::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipoPago, $tipoProvision);
//        return MovimientoNegocio::create()->obtenerDocumentoTipo($opcionId);
    }

    public function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento() {
        $empresa_id = $this->getParametro("empresa_id");
        $tipo = 2;
        $tipo2 = 3;
        $usuarioId = $this->getUsuarioId();
        return PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresa_id, $tipo, $tipo2, $usuarioId);
    }

    public function obtenerDocumentoTipoDato() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function obtenerPersonaActivas() {
        $respuesta = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    public function obtenerDocumentosAPagar() {
        // seccion de obtencion de variables
//        $opcionId = $this->getOpcionId();
//        $opcionId = 50;
        $tipoPago = 1;
        $tipoProvisionPago = 3;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentosPagoConDocumento() {
        $tipoPago = 2;
        $tipoProvisionPago = 3;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {

            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='anularPago(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#81BEF7;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            }
        }
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentosPagoListarConDocumento() {
        $tipoPago = 2;
        $tipoProvisionPago = 3;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoListarXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoListarXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $accionAnular = "";
            $accionEliminar = "";
            if ($data[$i]['cantidad_pagos'] <= 1) {
                $accionAnular = "<a href='#' onclick='anularPago(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#DF7401;'></i><b></a>&nbsp;\n";
                $accionEliminar = "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            }

            $accionVis = "";
            if ($data[$i]['cantidad_pagos'] >= 1) {
                $accionVis = "<a href='#' onclick='visualizarDocumentoPago(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
            }

            $accionImp = "<a href='#' onclick='imprimirDocumentoPago(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:#088A08;'></i><b></a>&nbsp;\n";

            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = $accionImp . $accionVis . $accionAnular . $accionEliminar;
            } else {
                $data[$i]['acciones'] = $accionEliminar;
            }
        }
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentoAPagar() {
        $documentoId = $this->getParametro("documentoId");
        $fechaPago = $this->getParametro("fechaPago");

        $data = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId, $fechaPago);
        return $data;
    }

    public function obtenerTipoCambioXfecha() {
        $this->setTransaction();
        $fecha = $this->getParametro("fecha");
        $fecha = explode("/", $fecha);
        $fecha = "$fecha[2]-$fecha[1]-$fecha[0]";
        return TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fecha);
    }

    ///pago con documento
    public function obtenerDocumentoTipoPagoConDocumento() {
//        $tipoPago = 2;
        $tipoPago = 2;
        $tipoPagoProvision = 3;
        $empresa_id = $this->getParametro("empresa_id");
        return PagoNegocio::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipoPago, $tipoPagoProvision);
    }

    public function obtenerDocumentoPagoConDocumento() {
        $documentoId = $this->getParametro("documentoId");
        return DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);
    }

    public function registrarPago() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();

        $montoAPagar = $this->getParametro("montoAPagar");
        $tipoCambio = $this->getParametro("tipoCambio");
        $tipoCambio = strlen($tipoCambio) == 0 ? null : $tipoCambio;
        $cliente = $this->getParametro("cliente");
        $fecha = $this->getParametro("fecha");
//        $retencion = $this->getParametro("retencion");
//        $retencion = strlen("retencion") == 0 ? 1 : $retencion; 
//        if(ObjectUtil::isEmpty($retencion)){
        $retencion = 1;
//        }
        $monedaPago = $this->getParametro("monedaPago");
        $monedaPago = strlen($monedaPago) == 0 ? 2 : $monedaPago;
        $documentoAPagar = $this->getParametro("documentoAPagar");
        $documentoPagoConDocumento = $this->getParametro("documentoPagoConDocumento");
        $empresaId = $this->getParametro("empresaId");
        $actividadEfectivo = $this->getParametro("actividadEfectivo");



        return PagoNegocio::create()->registrarPago($cliente, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo);
    }

    //guardar el nuevo documento
    public function guardarDocumento() {
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $monedaId = $this->getParametro("moendaId");
        $monedaId = strlen($monedaId) == 0 ? 2 : $monedaId;
        $periodoId = $this->getParametro("periodoId");

        return PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId);
    }

    public function getAllProveedor() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function anularDocumentoPago() {
        $this->setTransaction();
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();
//        return MovimientoNegocio::create()->anular($documentoId,$documentoEstadoId,$usuarioId);
        return PagoNegocio::create()->anularPagoXDocumentoPago($documentoId, $documentoEstadoId, $usuarioId);
    }

//    public function eliminarDocumentoPago(){
//        $documentoId = $this->getParametro("id");
//        return DocumentoNegocio::create()->eliminar($documentoId);
//    }
    public function visualizarPago() {
        $documentoId = $this->getParametro("documento_id");
//        $movimientoId = $this->getParametro("movimiento_id");
        return DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoId);
    }

    // Fin Sección de cobranzas
    //----------------------------------------------------------------------------------
    ///Sección para pagos
    public function obtenerDocumentoTipoPago() {
        $tipoPago = 4;
        $tipoProvision = 6;
        $empresa_id = $this->getParametro("empresa_id");
        return PagoNegocio::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipoPago, $tipoProvision);
//        return MovimientoNegocio::create()->obtenerDocumentoTipo($opcionId);
    }

    public function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago() {
        $empresa_id = $this->getParametro("empresa_id");
        $tipo1 = 5;
        $tipoProvisionPago = 6;
        $usuarioId = $this->getUsuarioId();
        return PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresa_id, $tipo1, $tipoProvisionPago, $usuarioId);
    }

    public function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion() {
        $empresa_id = $this->getParametro("empresa_id");
        $tipo1 = 5;
        $tipoProvisionPago = 6;
        $usuarioId = $this->getUsuarioId();
        return PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumentoDetraccion($empresa_id, $tipo1, $tipoProvisionPago, $usuarioId);
    }

    public function obtenerDocumentoTipoDatoPago() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

//    public function obtenerPersonaActivasPago() {
//        $respuesta = PersonaNegocio::create()->obtenerActivas();
//        return $respuesta;
//    }

    public function obtenerDocumentosAPagarPago() {
        // seccion de obtencion de variables
//        $opcionId = $this->getOpcionId();
//        $opcionId = 50;
        $tipoPago = 4;
        $tipoProvisionPago = 6;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentosPagoConDocumentoPago() {
        $tipoPago = 5;
        $tipoProvisionPago = 6;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {

            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='anularPago(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#81BEF7;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            }
        }
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentosPagoListarConDocumentoPago() {
        $tipoPago = 5;
        $tipoProvisionPago = 6;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagoListarXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagoListarXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $accionAnular = "";
            $accionEliminar = "";
            if ($data[$i]['cantidad_pagos'] <= 1) {
                $accionAnular = "<a href='#' onclick='anularPago(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#DF7401;'></i><b></a>&nbsp;\n";
                $accionEliminar = "<a href='#' onclick='eliminarPago(" . $data[$i]['documento_id'] . ")' title='Eliminar'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";
            }

            $accionVis = "";
            if ($data[$i]['cantidad_pagos'] >= 1) {
                $accionVis = "<a href='#' onclick='visualizarDocumentoPago(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
            }

            $accionImp = "<a href='#' onclick='imprimirDocumentoPago(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:#088A08;'></i><b></a>&nbsp;\n";

            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = $accionImp . $accionVis . $accionAnular . $accionEliminar;
            } else {
                $data[$i]['acciones'] = $accionEliminar;
            }
        }
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentoAPagarPago() {
        $documentoId = $this->getParametro("documentoId");
        return DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);
    }

    ///pago con documento
    public function obtenerDocumentoTipoPagoConDocumentoPago() {
//        $tipoPago = 2;
        $tipoPago = 5;
        $tipoPagoProvision = 6;
        $empresa_id = $this->getParametro("empresa_id");
        return PagoNegocio::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipoPago, $tipoPagoProvision);
    }

    public function obtenerDocumentoPagoConDocumentoPago() {
        $documentoId = $this->getParametro("documentoId");
        return DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);
    }

    public function imprimir() {
        $id = $this->getParametro("id");
        $tipo_id = $this->getParametro("documento_tipo_id");
        return PagoNegocio::create()->imprimir($id, $tipo_id);
    }

    public function registrarPagoPago() {
        $usuarioId = $this->getUsuarioId();

        $montoAPagar = $this->getParametro("montoAPagar");
        $pagarCon = $this->getParametro("pagarCon");
        $vuelto = $this->getParametro("vuelto");
        $cliente = $this->getParametro("cliente");
        $fecha = $this->getParametro("fecha");
        $documentoAPagar = $this->getParametro("documentoAPagar");
        $documentoPagoConDocumento = $this->getParametro("documentoPagoConDocumento");
        return PagoNegocio::create()->registrarPago($cliente, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $pagarCon, $vuelto);
    }

    //guardar el nuevo documento
    public function guardarDocumentoPago() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $this->setTransaction();
        return PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos);
    }

    public function getAllProveedorPago() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
    }

//    public function anularDocumentoPagoPago(){
//        $documentoId = $this->getParametro("id");
//        return MovimientoNegocio::create()->anular($documentoId);
//    }
//    public function eliminarDocumentoPagoPago(){
//        $documentoId = $this->getParametro("id");
//        return DocumentoNegocio::create()->eliminar($documentoId);
//    }
    public function visualizarPagoPago() {
        $documentoId = $this->getParametro("documento_id");
//        $movimientoId = $this->getParametro("movimiento_id");
        return DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoId);
    }

    public function obtenerEquivalenciaSunat() {
        $fecha = $this->getParametro("fecha");

        return TipoCambioNegocio::create()->obtenerEquivalenciaSunatXFecha($fecha);
    }

    public function validarSiTieneDocumentoRetencionDetraccion() {
        $documentoAPagar = $this->getParametro("documentoAPagar");

        $data = PagoNegocio::create()->validarSiTieneDocumentoRetencionDetraccion($documentoAPagar);
        return $data;
    }

    ///Fin sección para pagos 

    public function obtenerActividades() {
        $tipoCobranzaPago = $this->getParametro("tipoCobranzaPago");
        $empresaId = $this->getParametro("empresaId");

        $data = PagoNegocio::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
        return $data;
    }

    //envio correo documento pago
    public function enviarCorreoDocumentoPago() {
        $usuarioId = $this->getUsuarioId();
        $correo = $this->getParametro("correo");
        $documentoId = $this->getParametro("documento_id");
        $tipoCobroPago = $this->getParametro("tipoCobroPago");

        PagoNegocio::create()->enviarCorreoDocumentoPago($usuarioId, $correo, $documentoId, $tipoCobroPago);

        $this->setMensajeEmergente("Se envio el correo de manera satisfactoria");
        return 1;
        //Fin logica de correo
    }

    public function buscarCriteriosBusquedaDocumentoPagar() {
        $tipoPago = 1;
        $tipoProvisionPago = 3;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaPagoConDocumento() {
        $tipoPago = 2;
        $tipoProvisionPago = 3;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaDocumentoPagoListar() {
        $tipoPago = 2;
        $tipoProvisionPago = 3;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaDocumentoPagarPago() {
        $tipoPago = 4;
        $tipoProvisionPago = 6;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaPagoConDocumentoPago() {
        $tipoPago = 5;
        $tipoProvisionPago = 6;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagar($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaDocumentoPagoListarPago() {
        $tipoPago = 5;
        $tipoProvisionPago = 6;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPago($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function obtenerDocumentosCobrados() {
        $tipoPago = 1;
        $tipoProvisionPago = 3;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagadosXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagadosXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['acciones'] = "<a href='#' onclick='visualizarDocumentoPago(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDetallePago() {
        $documentoId = $this->getParametro("documentoId");
        return PagoNegocio::create()->obtenerDetallePago($documentoId);
    }

    public function obtenerDocumentosPagados() {
        $tipoPago = 4;
        $tipoProvisionPago = 6;
        $empresa_id = $this->getParametro("empresa_id");
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = PagoNegocio::create()->obtenerDocumentosPagadosXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = PagoNegocio::create()->obtenerCantidadDocumentosPagadosXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['acciones'] = "<a href='#' onclick='visualizarDocumentoPago(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function buscarCriteriosBusquedaDocumentoPagadosListar() {
        $tipoPago = 1;
        $tipoProvisionPago = 3;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function buscarCriteriosBusquedaDocumentoPagadosListarPago() {
        $tipoPago = 4;
        $tipoProvisionPago = 6;
        $empresaId = $this->getParametro("empresa_id");
        $busqueda = $this->getParametro("busqueda");

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXDocumentoPagado($empresaId, $tipoPago, $tipoProvisionPago, $busqueda);
        return $response;
    }

    public function getUserEmailByUserId() {
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->getUserEmailByUserId($usuarioId);
    }

    public function eliminarRelacionDePago() {
        $this->setTransaction();
        $documentoPagoId = $this->getParametro("documentoPagoId");
        return PagoNegocio::create()->eliminarRelacionDePago($documentoPagoId);
    }

    public function eliminarDocumentoDePago() {
        $this->setTransaction();
        $documentoPago = $this->getParametro("documentoPago");
        return PagoNegocio::create()->eliminarPagoXDocumentoDePago($documentoPago);
    }

    public function obtenerTipoCambioXFechaDocumentoPago() {
        $fecha = $this->getParametro("fecha");
        $fecha = explode("/", $fecha);
        $fecha = "$fecha[2]-$fecha[1]-$fecha[0]";
        return TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fecha);
    }

//    ================== INICIO PAGO DOCUMENTO DETRACCIÓN ==================
    public function obtenerConfiguracionInicial() {
//        $empresa_id = $this->getParametro("empresa_id");
        return PagoNegocio::create()->obtenerCargaInicial();
    }

    public function listarRegistroComprasXCriteriosDetraccion() {
        $criterios = $this->getParametro("criterios");
        return RegistroComprasNegocio::create()->listarRegistroComprasXCriterioDetraccion($criterios);
    }

    public function obtenerDocumentoRelacionVisualizar() {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
        $data->configuracionEditable = MovimientoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);
        $data->emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

        $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        $data->dataAccionEnvio = MovimientoTipoNegocio::create()->obtenerMovimientoTipoAccionesVisualizacion($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataDocumentoAdjunto = DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);
        $data->dataDistribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
//        $data->dataVoucherContable = ContVoucherDetalleNegocio::create()->obtenerVoucherDetalleXDocumentoId($documentoId, ContLibroNegocio::CLASIFICACION_COMPRAS);
        return $data;
    }

    public function registrarPagoDetraccion() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $monedaId = $this->getParametro("monedaId");
        $periodoId = $this->getParametro("periodoId");
        $documentoAPagar = $this->getParametro("documentoAPagarId");
        $empresaId = $this->getParametro("empresaId");
        return PagoNegocio::create()->registrarPagoDetraccion($usuarioId, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId, $documentoAPagar, $empresaId);
    }

//    ================== FIN PAGO DOCUMENTO DETRACCIÓN ==================
}
