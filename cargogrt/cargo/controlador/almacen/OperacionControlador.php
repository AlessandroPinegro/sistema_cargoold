<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/OperacionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailPlantillaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/CuentaNegocio.php';

/**
 * Description of OperacionControlador
 *
 * @author Imagina
 */
class OperacionControlador  extends AlmacenIndexControlador
{

    public function obtenerConfiguracionesIniciales()
    {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        $usuarioId = $this->getUsuarioId();

        $data = OperacionNegocio::create()->obtenerConfiguracionInicial($opcionId, $empresaId, $usuarioId);
        return $data;
    }

    public function obtenerDocumentoTipo()
    {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();

        $data = new stdClass();
        $data = OperacionNegocio::create()->obtenerDocumentoTipo($opcionId,$usuarioId);
        $data->personasMayorOperacion = array(); //PersonaNegocio::create()->obtenerPersonasMayorOperacion($opcionId); 
        return $data;
    }

    public function enviar()
    {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $descripcion = $this->getParametro("descripcion");
        $monedaId = $this->getParametro("monedaId");
        $empresaId = $this->getParametro("empresaId");
        $periodoId = $this->getParametro("periodoId");
        $this->setTransaction();

        $documentoID = OperacionNegocio::create()->guardar(
            $opcionId,
            $usuarioId,
            $documentoTipoId,
            $camposDinamicos,
            $comentario,
            $descripcion,
            $monedaId,
            $documentoARelacionar,
            $valorCheck,
            $periodoId
        );
        return $documentoID;
    }

    public function guardarRetiroDeposito()
    {
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $comentario = $this->getParametro("comentario");
        $descripcion = $this->getParametro("descripcion");
        $monedaId = $this->getParametro("monedaId");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $periodoId = $this->getParametro("periodoId");

        // guardar retiro deposito        
        $cuentaDestinoId = $this->getParametro("cuentaDestinoId");
        $camposDinamicosRD = $camposDinamicos;
        $documentoTipoGenerar =  DocumentoTipoNegocio::create()->obtenerDocumentoTipoGenerarXDocumentoTipoId($documentoTipoId);
        $documentoTipoGeneraId = $documentoTipoGenerar[0]['documento_tipo_genera_id'];

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId(2, $usuarioId);
            if (ObjectUtil::isEmpty($dataAperturaCaja)) {
                throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
            }
            $acCajaId = $dataAperturaCaja[0]['ac_caja_id'];


        if ($cuentaDestinoId != 0) {
            //logica para obtener la actividad_id de transferencia monetaria (ingreso o salida)
            $dataDT = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoGeneraId);
            if ($dataDT[0]['tipo'] == 7) { //ingreso
                $actividadRD = Configuraciones::ACTIVIDAD_TRANSFERENCIA_INGRESO;
            } else {
                $actividadRD = Configuraciones::ACTIVIDAD_TRANSFERENCIA_EGRESO;
            }

            //fin logica

            $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoGeneraId);
            foreach ($configuraciones as $indexConfig => $itemDtd) {
                foreach ($camposDinamicosRD as $indexCampos => $valorDtd) {
                    if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                        $camposDinamicosRD[$indexCampos]["id"] = $itemDtd["id"];
                        if ($itemDtd["tipo"] == 8) {
                            $camposDinamicosRD[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoGeneraId);
                        }
                        if ($itemDtd["tipo"] == 20) {
                            $camposDinamicosRD[$indexCampos]["valor"] = $cuentaDestinoId;
                        }
                        if ($itemDtd["tipo"] == 21) {
                            $camposDinamicosRD[$indexCampos]["valor"] = $actividadRD;
                        }
                    }
                }
            }

            $documentoRetiroDepositoID = OperacionNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoGeneraId, $camposDinamicosRD, $comentario, $descripcion, $monedaId, NULL, $valorCheck, $periodoId, $acCajaId);
        }
        //fin guardar documento retiro destino
        // return $opcionId .", ". $usuarioId.", ". $documentoTipoId.", ". $camposDinamicos.", ".$comentario.", ".$descripcion.", ".$monedaId.", ". $documentoARelacionar.", ". $valorCheck.", ". $periodoId = 85;
        $documentoID = OperacionNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $comentario, $descripcion, $monedaId, $documentoARelacionar, $valorCheck, $periodoId, $acCajaId);

        if ($cuentaDestinoId != 0) {
            //relaciones de documentos
            $res = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoID, $documentoRetiroDepositoID, 1, 1, $usuarioId);
        }
        return $documentoID;
    }

    public function obtenerDocumentos()
    {
        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        // seccion de consumir negocio
        // return $opcionId." , ". $criterios." , ". $elemntosFiltrados." , ". $columns." , ". $order." , ". $start;
        $data = OperacionNegocio::create()->obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);
        // return $data;
        //$responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $response_cantidad_total = OperacionNegocio::create()->obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        // seccion de respuesta
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);

        //        for ($i = 0; $i < $tamanio; $i++) {
        //            $stringAcciones = '';
        //            for ($j = 0; $j < count($responseAcciones); $j++) {
        //                if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4) ) {
        //                    $stringAcciones.='';
        //                } elseif ((($data[$i]['documento_relacionado'] == 0) && ($responseAcciones[$j]['id'] == 5)) || (($data[$i]['documento_estado_id'] == 2) && ($responseAcciones[$j]['id'] == 5))) {
        //                    $stringAcciones.='';
        //                }else {
        //                    if($responseAcciones[$j]['id'] == 1)
        //                    {
        //                        $datoPivot = $data[$i]['documento_tipo_id'];
        //                    }  else {
        //                        $datoPivot = $data[$i]['movimiento_id'];
        //                    }
        //                    $stringAcciones .= "<a href='#' onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i><b></a>&nbsp;\n";
        //                }
        //            }
        //            $data[$i]['acciones'] = $stringAcciones;
        //        }


        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['documento_estado_id'] != 2) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                    "<a href='#' onclick='anularDocumento(" . $data[$i]['documento_id'] . ")' title='Anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>&nbsp;\n" .
                    "<a href='#' onclick='visualizarDocumento(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
                if ($data[$i]['documento_relacionado'] != 0) {
                    $data[$i]['acciones'] .= "<a href='#' onclick='obtenerDocumentosRelacionados(" . $data[$i]['documento_id'] . ",0)' title='Documento Relacionado'><b><i class='ion-android-share' style='color:#E8BA2F;'></i><b></a>&nbsp;\n";
                }
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='Imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                    "<a href='#' onclick='visualizarDocumento(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
            }

            $data[$i]['icono_documento'] = '<i class="' . $data[$i]['documento_tipo_icono'] . '"></i>';
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function visualizarDocumento()
    {
        $documentoId = $this->getParametro("documento_id");

        return OperacionNegocio::create()->visualizarDocumento($documentoId);
    }

    public function anular()
    {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();
        return OperacionNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function exportarReporteExcel()
    {
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 100000;
        $order = "desc";
        $columns = "fecha_creacion_ord";
        $start = 0;
        // seccion de consumir negocio
        // return $opcionId.",". $criterios.",". $elemntosFiltrados.",". $columns.",". $order.",". $start;
        $data = OperacionNegocio::create()->obtenerDocumentosXCriteriosExportar($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        return OperacionNegocio::create()->exportarReporteOperacionExcel($data, $opcionId);
    }

    public function obtenerDocumentoTipoDato()
    {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        //$data=DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function obtenerPersonaDireccion()
    {

        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaDireccionXPersonaId($personaId);
    }

    // seccion de pagos
    public function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento()
    {
        $empresaId = $this->getParametro("empresa_id");
        $documentoTipoId = $this->getParametro("documentoTipo");
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        $tipoDocumento = $dataDocumentoTipo[0]['tipo'];
        $usuarioId = $this->getUsuarioId();

        if ($tipoDocumento == 1 || $tipoDocumento == 3) {
            $tipo = 2;
            $tipo2 = 3;
            $tipoCobranzaPago = 1;
        }
        if ($tipoDocumento == 4 || $tipoDocumento == 6) {
            $tipo = 5;
            $tipo2 = 6;
            $tipoCobranzaPago = 2;
        }

        $respuesta = PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresaId, $tipo, $tipo2, $usuarioId);
        $respuesta->actividad = Pago::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
        return $respuesta;
    }

    public function obtenerDocumentoTipoDatoPago()
    {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function guardarDocumento()
    {
        $this->setTransaction();

        //documento operacion
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        //        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $descripcion = $this->getParametro("descripcion");
        $monedaId = $this->getParametro("monedaId");
        $periodoId = $this->getParametro("periodoId");

        $documentoId = OperacionNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $comentario, $descripcion, $monedaId, $documentoARelacionar, $valorCheck, $periodoId);

        //documento pago
        $documentoTipoIdPago = $this->getParametro("documentoTipoIdPago");
        $camposDinamicosPago = $this->getParametro("camposDinamicosPago");

        $documentoPagoId = null;
        if ($documentoTipoIdPago != 0) {
            $documentoPagoId = PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoIdPago, $camposDinamicosPago, $monedaId, $periodoId);
        }
        //registrar pago
        $usuarioId = $this->getUsuarioId();

        $montoAPagar = $this->getParametro("montoAPagar"); // efectivo a pagar
        $tipoCambio = $this->getParametro("tipoCambio");
        $tipoCambio = strlen($tipoCambio) == 0 ? null : $tipoCambio;
        $cliente = $this->getParametro("cliente"); //
        $fecha = $this->getParametro("fecha");
        $retencion = 1;
        $monedaPago = $monedaId;
        $empresaId = $this->getParametro("empresaId");
        $actividadEfectivo = $this->getParametro("actividadEfectivo");
        //        $monedaPago = strlen($monedaPago) == 0 ? 2 : $monedaPago;

        $totalDocumento = $this->getParametro("totalDocumento");
        $totalPago = $this->getParametro("totalPago");
        $dolares = 0;
        if ($monedaPago == 4) {
            $dolares = 1;
        }
        $documentoAPagar = array(
            array(
                documentoId => $documentoId,
                tipoDocumento => '',
                numero => '',
                serie => '',
                pendiente => (float)$totalDocumento,
                total => (float)$totalDocumento,
                dolares => $dolares
            )
        );

        if (ObjectUtil::isEmpty($documentoPagoId)) {
            $documentoPagoConDocumento = null;
        } else {
            $documentoPagoConDocumento = array(
                array(
                    documentoId => $documentoPagoId,
                    tipoDocumento => '',
                    tipoDocumentoId => '',
                    numero => '',
                    serie => '',
                    pendiente => (float) $totalPago,
                    total => (float) $totalPago,
                    monto => (float) $totalPago,
                    dolares => $dolares
                )
            );
        }

        $pago = PagoNegocio::create()->registrarPago(
            $cliente,
            $fecha,
            $documentoAPagar,
            $documentoPagoConDocumento,
            $usuarioId,
            $montoAPagar,
            $tipoCambio,
            $monedaPago,
            $retencion,
            $empresaId,
            $actividadEfectivo
        );
        //return $pago;
        //fin registrar pago

        return $documentoPagoId;
    }

    public function obtenerTipoCambioXfecha()
    {
        $this->setTransaction();
        $fecha = $this->getParametro("fecha");
        $fecha = explode("/", $fecha);
        $fecha = "$fecha[2]-$fecha[1]-$fecha[0]";
        return TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fecha);
    }

    public function obtenerDocumentosRelacionados()
    {

        $documentoId = $this->getParametro("documento_id");

        return MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
    }

    public function enviarCorreoDetalleDocumento()
    {
        $usuarioId = $this->getUsuarioId();
        $correo = $this->getParametro("correo");
        $nombreDocumentoTipo = $this->getParametro("nombreDocumentoTipo");
        $dataDocumento = $this->getParametro("dataDocumento");

        //logica correo:             
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(4);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId);

        $this->setMensajeEmergente("Se envio el correo de manera satisfactoria");
        return 1;
        //Fin logica de correo
    }

    public function imprimir()
    {
        $documentoId = $this->getParametro("id");
        $documentoTipoId = $this->getParametro("documento_tipo_id");
        return MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
    }

    public function getAllPersona()
    {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
        //       return PersonaNegocio::create()->obtenerActivas();
    }

    public function obtenerCuentaSaldo()
    {
        $cuentaId = $this->getParametro("cuentaId");
        $data = CuentaNegocio::create()->obtenerCuentaXId($cuentaId);

        if ($data[0]['tipo'] == 1) {
            $dataSaldo = CuentaNegocio::create()->obtenerSaldoCuentaXId($cuentaId);
            return $dataSaldo;
        } else {
            return null;
        }
    }

    public function buscarCriteriosBusqueda()
    {
        $busqueda = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();

        $dataPersona = PersonaNegocio::create()->buscarPersonaOperacionXNombreXDocumento($opcionId, $busqueda);
        $resultado->dataPersona = $dataPersona;

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoOperacionXOpcionXDescripcion($opcionId, $busqueda);
        $resultado->dataDocumentoTipo = $dataDocumentoTipo;

        $dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosOperacionXOpcionXSerieNumero($opcionId, $busqueda);
        $resultado->dataSerieNumero = $dataSerieNumero;

        return $resultado;
    }

    public function obtenerCuentaSaldoTodos()
    {
        $empresaId = $this->getParametro("empresa_id");
        $dataSaldo = CuentaNegocio::create()->obtenerCuentaSaldoTodos($empresaId);
        return $dataSaldo;
    }

    public function getUserEmailByUserId()
    {
        $usuarioId = $this->getUsuarioId();
        return OperacionNegocio::create()->getUserEmailByUserId($usuarioId);
    }

    //Area de funciones para copiar documento

    public function configuracionesBuscadorCopiaDocumento()
    {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return OperacionNegocio::create()->configuracionesBuscadorCopiaDocumento($opcionId, $empresaId, $documentoTipoId);
    }

    public function buscarDocumentoACopiar()
    {

        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresa_id");
        $documentoTipoId = $this->getParametro("documentoTipoId");

        $configuracionesDocumentoACopiar = OperacionNegocio::create()->configuracionesBuscadorCopiaDocumento($opcionId, $empresaId, $documentoTipoId);
        $documentoTipos = $configuracionesDocumentoACopiar->documentoTipo;

        if ($criterios["documento_tipo_ids"] == '') {
            $criterios["documento_tipo_ids"] = Array();
            foreach ($documentoTipos as $index => $docTipo) {
                array_push($criterios["documento_tipo_ids"],$docTipo['id']);
            }
        }

        $respuesta = OperacionNegocio::create()->buscarDocumentoACopiar($criterios, $elementosFiltrados, $columns, $order, $start, $opcionId, $documentoTipoId);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

    public function buscarCriteriosBusquedaDocumentoCopiar()
    {
        $empresaId = $this->getParametro("empresa_id");
        $valor = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();
        $documentoTipoId = $this->getParametro("documentoTipoId");

        $configuracionesDocumentoACopiar = OperacionNegocio::create()->configuracionesBuscadorCopiaDocumento($opcionId, $empresaId, $documentoTipoId);
        $documentoTipos = $configuracionesDocumentoACopiar->documentoTipo;

        $documentoTipoIdArray = [];
        foreach ($documentoTipos as $index => $docTipo) {
            $documentoTipoIdArray[] = $docTipo[id];
        }

        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoOperacion($documentoTipoIdArray, $valor);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoOperacionXDocumentoTipoXDescripcion($documentoTipoIdArray, $valor);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosOperacionXTipoDocumentoXSerieNumero($documentoTipoIdArray, $valor);
        return $response;
    }

    public function obtenerDetalleDocumentoACopiarSinDetalle()
    {
        $opcionId = $this->getOpcionId();
        $documentoOrigenId = $this->getParametro("documento_id_origen");
        $documentoDestinoId = $this->getParametro("documento_id_destino");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");

        $data = OperacionNegocio::create()->obtenerDetalleDocumentoACopiar($documentoOrigenId, $documentoDestinoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function obtenerSelectPersonas()
    {
        $stringBusqueda = $this->getParametro("querySelect");
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda($stringBusqueda, $personaId);
    }
}
