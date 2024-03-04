<?php

require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/Pago.php';
require_once __DIR__ . '/../../modelo/almacen/Documento.php';
require_once __DIR__ . '/../../modelo/almacen/DocumentoDatoValor.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/NumeroALetra/EnLetras.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/MovimientoNegocio.php';
require_once __DIR__ . '/ProgramacionPagoNegocio.php';
require_once __DIR__ . '/PeriodoNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

class PagoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return PagoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentoTipoXTipo($empresa_id, $tipo, $tipoPagoProvision) {
        $contador = 0;
        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipo, $tipoPagoProvision);
        if (!ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXTipo($tipo, $tipoPagoProvision);
        }

        if (!ObjectUtil::isEmpty($respuesta->documento_tipo_dato)) {

            $tamanio = count($respuesta->documento_tipo_dato);
            for ($i = 0; $i < $tamanio; $i++) {
                switch ((int) $respuesta->documento_tipo_dato[$i]['tipo']) {
                    case 5 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Persona";
                        break;
                    case 6 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Código";
                        break;
                    case 7 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Serie";
                        break;
                    case 8 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Número";
                        break;
                    case 9 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de emisión";
                        break;
                    case 10 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de vencimiento";
                        break;
                    case 11 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de tentativa";
                        break;
                    case 12 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Descripción";
                        break;
                    case 13 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Comentario";
                        break;
                }
            }

            foreach ($respuesta->documento_tipo_dato as $documento) {
                $documento['descripcion'] = "hola";
            }


            foreach ($respuesta->documento_tipo_dato as $documento) {
                if ($documento['tipo'] == 4) {
                    $respuesta->documento_tipo_dato_lista[$contador]['id'] = $documento['id'];
                    $respuesta->documento_tipo_dato_lista[$contador]['data'] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($documento['id']);
                    $contador++;
                }
            }
        }
        $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXTipo($tipo, $tipoPagoProvision);
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    //consulta
    public function obtenerDocumentosPagoXCriterios($empresa_id, $tipo, $tipoProvision, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        $documentoTipoArray = $criterios[0]['tipoDocumento'];
        $fechaPago = $criterios[0]['fechaPago'];

        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        $fechaPagoForm = DateUtil::formatearCadenaACadenaBD($fechaPago);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerDocumentosPagoXCriterios($empresa_id, $tipo, $tipoProvision, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $fechaPagoForm);
    }

    public function obtenerCantidadDocumentosPagoXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);

        $fechaPago = $criterios[0]['fechaPago'];
        $fechaPagoForm = DateUtil::formatearCadenaACadenaBD($fechaPago);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerCantidadDocumentosPagoXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $fechaPagoForm);
    }

    public function registrarPago($cliente, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago = 2, $retencion = 1, $empresaId = null, $actividadEfectivo = null, $pagoDetraccion = null, $acCajaId = null) {
        $DocPagoVerificado = $documentoAPagar;
        $estado = ConstantesNegocio::PARAM_ACTIVO;
        $moneda = 2;
        $factor = 1;
        if ($documentoAPagar[0]["dolares"] * 1 == 0 && $monedaPago == 4) {
            $factor = $tipoCambio;
        } else if ($documentoAPagar[0]["dolares"] * 1 == 1 && $monedaPago == 2) {
            $factor = 1 / $tipoCambio;
        }
        if (ObjectUtil::isEmpty($factor) || $factor == false) {
            $resultado->error = 1;
            $resultado->mensaje = 'Ingrese tipo de cambio';
            return $resultado;
        }

        $dataDocumentoTipo = $documentoAPagar[0]["tipo"];
        $fecha = DateUtil::formatearCadenaACadenaBD($fecha);
        $serie = null;
        $arrayFecha = explode("-", $fecha); //Dividimos la fecha, para obetener el Año-Mes-Dia.
        $serieAnio = $arrayFecha[0];
        $serieMes = $arrayFecha[1];
        if (strlen($serieMes) == 1) {
            $serieMes = "0" . $serieMes;
        }
        if ($dataDocumentoTipo == 4 || $dataDocumentoTipo == 6) {
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorrelativoPagos($dataDocumentoTipo, "OP"); //OP20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $cadenaSerie = substr($serieCorrealativa[0]["codigo"], 7, 12);
                $ultimoNumero = (int) $cadenaSerie;
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $serie = "OP" . $serieAnio . $serieMes . $ultimoNumero;
        } else if ($dataDocumentoTipo == 1 || $dataDocumentoTipo == 3) {
            //PARA ESTOS DOCUMENTO EL PRIMER CARACTER DE LA SERIE EMPIEZA POR "C".
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorrelativoPagos($dataDocumentoTipo, "OC"); //OC20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $ultimoNumero = (int) substr($serieCorrealativa[0]["codigo"], 7, 12);
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $serie = "OC" . $serieAnio . $serieMes . $ultimoNumero;
        }
        $response = Pago::create()->insertarPago($cliente, $fecha, $usuarioId, $estado, $serie);
        $pagoId = $response[0]['id'];

        if ($montoAPagar > 0) {

            if (!ObjectUtil::isEmpty($empresaId)) {
                $tipoDocumento = $documentoAPagar[0]["tipo"];
                $actividadId = $actividadEfectivo;
                $empresaData = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
                $cuentaId = $empresaData[0]["cuenta_id"];
            }

            $moneda = $monedaPago;
            for ($ii = 0; $ii < count($documentoAPagar); $ii++) {
                $importeEfectivo = 0;
                if ($documentoAPagar[$ii]['pendiente'] > 0 && $montoAPagar * $factor > 0) {
                    if ($documentoAPagar[$ii]['pendiente'] > $montoAPagar * $factor) {
//                     $importeEfectivo = $documentoAPagar[$ii]['pendiente'] - $montoAPagar;
                        $importeEfectivo = $montoAPagar;
                        $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$ii]['documentoId'], null, $importeEfectivo, $moneda, $estado, $usuarioId, $tipoCambio, $cuentaId, $actividadId, $acCajaId);
                        //REGISTRAR PPAGO DETALLE
                        $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$ii]['documentoId'], $resPago[0]['id'], $montoAPagar * $factor, $usuarioId);

                        $documentoAPagar[$ii]['pendiente'] = $documentoAPagar[$ii]['pendiente'] - $montoAPagar * $factor;
                        $montoAPagar = 0;
                    } else {

                        if ($documentoAPagar[$ii]['pendiente'] < $montoAPagar * $factor) {
                            $importeEfectivo = $montoAPagar;
                            $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$ii]['documentoId'], null, $importeEfectivo, $moneda, $estado, $usuarioId, $tipoCambio, $cuentaId, $actividadId, $acCajaId);
                            //REGISTRAR PPAGO DETALLE
                            $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$ii]['documentoId'], $resPago[0]['id'], $montoAPagar * $factor, $usuarioId);

//                            $montoAPagar = $montoAPagar - $documentoAPagar[$ii]['pendiente']/$factor;
                            $montoAPagar = $montoAPagar - $importeEfectivo;
                            $documentoAPagar[$ii]['pendiente'] = 0;
                        } else {
                            $importeEfectivo = $documentoAPagar[$ii]['pendiente'] / $factor;

                            $importeEfectivo = $this->devolverImporteRedondeado($factor, $importeEfectivo, $documentoAPagar[$ii]['pendiente']);
                            $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$ii]['documentoId'], null, $importeEfectivo, $moneda, $estado, $usuarioId, $tipoCambio, $cuentaId, $actividadId, $acCajaId);
                            //REGISTRAR PPAGO DETALLE
                            $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$ii]['documentoId'], $resPago[0]['id'], $montoAPagar * $factor, $usuarioId);

                            $documentoAPagar[$ii]['pendiente'] = 0;

                            $montoAPagar = 0;
                        }
                    }
                }
            }
        }

        if (count($documentoPagoConDocumento) > 0) {
            $moneda = $documentoPagoConDocumento[0]["dolares"] * 1 == 0 ? 2 : 4;
            for ($i = 0; $i < count($documentoAPagar); $i++) {
                $importe = 0;
                if ($documentoAPagar[$i]['pendiente'] > 0) {
                    for ($j = 0; $j < count($documentoPagoConDocumento); $j++) {
                        if ($documentoPagoConDocumento[$j]['monto'] > 0) {
                            if ($documentoAPagar[$i]['pendiente'] > $documentoPagoConDocumento[$j]['monto'] * $factor) {
                                //insertar
                                $importe = $documentoPagoConDocumento[$j]['monto'];
                                $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$i]['documentoId'], $documentoPagoConDocumento[$j]['documentoId'], $importe, $moneda, $estado, $usuarioId, $tipoCambio, NULL, NULL, $acCajaId);
                                //REGISTRAR PPAGO DETALLE
                                $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$i]['documentoId'], $resPago[0]['id'], $documentoPagoConDocumento[$j]['monto'] * $factor, $usuarioId);

                                $documentoAPagar[$i]['pendiente'] = $documentoAPagar[$i]['pendiente'] - $documentoPagoConDocumento[$j]['monto'] * $factor;
                                $documentoPagoConDocumento[$j]['monto'] = 0;
                            } else {
                                if ($documentoAPagar[$i]['pendiente'] < $documentoPagoConDocumento[$j]['monto'] * $factor) {

                                    $importe = Util::redondearNumero($documentoAPagar[$i]['pendiente'] / $factor, 2);
//                                    $importe = round($importe, 2);
                                    $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$i]['documentoId'], $documentoPagoConDocumento[$j]['documentoId'], $importe, $moneda, $estado, $usuarioId, $tipoCambio, NULL, NULL, $acCajaId);
//                                  //REGISTRAR PPAGO DETALLE
                                    $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$i]['documentoId'], $resPago[0]['id'], $documentoPagoConDocumento[$j]['monto'] * $factor, $usuarioId);

//                                      $documentoPagoConDocumento[$j]['monto'] = $documentoPagoConDocumento[$j]['monto'] - $documentoAPagar[$i]['pendiente']/$factor;
                                    $documentoPagoConDocumento[$j]['monto'] = Util::redondearNumero($documentoPagoConDocumento[$j]['monto'] - $importe, 6);
                                    $documentoAPagar[$i]['pendiente'] = 0;

                                    //insertar
                                } else {
//                                    insertar
                                    $importe = Util::redondearNumero($documentoAPagar[$i]['pendiente'] / $factor, 2);
                                    $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$i]['documentoId'], $documentoPagoConDocumento[$j]['documentoId'], $importe, $moneda, $estado, $usuarioId, $tipoCambio, NULL, NULL, $acCajaId);
                                    //REGISTRAR PPAGO DETALLE
                                    $resPP = $this->insertarPpagoDetalleDocumentoPago($fecha, $documentoAPagar[$i]['documentoId'], $resPago[0]['id'], $documentoPagoConDocumento[$j]['monto'] * $factor, $usuarioId);

                                    $documentoPagoConDocumento[$j]['monto'] = 0;
                                    $documentoAPagar[$i]['pendiente'] = 0;
                                }
                            }
                        }
                    }
                    //PARA RELACIONAR EL PAGO DE LAS DETRACCIONES    
                } elseif ($documentoAPagar[$i]['pendiente'] == 0 && $pagoDetraccion === 1) {
                    $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoAPagar[$i]['documentoId'], $documentoPagoConDocumento[$i]['documentoId'], $importe, $moneda, $estado, $usuarioId, $tipoCambio, NULL, NULL, $acCajaId);
                }
            }
        }


//            $respuestaVoucher = ContVoucherNegocio::create()->registrarContVoucherPagos($pagoId, $usuarioId);

        $mensaje = '';
        $indicador = 1; 
        $respuesta = new stdClass();
        $respuesta->mensaje = $mensaje;
        $respuesta->indicador = $indicador;
        return $respuesta;
    }

    public function insertarPpagoDetalleDocumentoPago($fecha, $documentoId, $documentoPagoId, $importe, $usuarioId) {
        $dataPPagoDetalle = ProgramacionPagoNegocio::create()->obtenerProgramacionPagoDetalleLiberadoPendienteDePagoXDocumentoIdXFecha($documentoId, $fecha);

        $montoPagar = $importe;
        foreach ($dataPPagoDetalle as $index => $item) {
            $importePendiente = $item['pendiente'];
            $ppagoDetalleId = $item['ppago_detalle_id'];
            if ($montoPagar >= $importePendiente) {
                $montoPagar = $montoPagar - $importePendiente;
            } else {
                $importePendiente = $montoPagar;
                $montoPagar = 0;
            }
            if ($importePendiente > 0) {
                $res = Pago::create()->insertarPpagoDetalleDocumentoPago($ppagoDetalleId, $documentoPagoId, $importePendiente, $usuarioId);
            }
        }
    }

    public function actualizarEstadoDesembolsadoSolicitudEar($documentoId, $usuarioId) {
        require_once __DIR__ . '/../../' . Configuraciones::CARPETA_SGI_ADMIN . '/func.php';

        $dataEar = obtenerEarSolicitudXSgiDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($dataEar)) {
            $earId = $dataEar['0']['ear_id'];
//            $url = Configuraciones::url_host() . Configuraciones::CARPETA_SGI_ADMIN . "/ear_pendiente_desemb_p.php?id=" . $earId . "&sgi=1";
            $res = actualizarEstadoDesembolsado($earId, $usuarioId);
        }
    }

    public function obtenerConfiguracionInicialNuevoDocumento($empresaId, $tipo1, $tipoProvisionPago, $usuarioId) {
        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoSinDocumentosDeMovimientoXTipo($empresaId, $tipo1, $tipoProvisionPago);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El documento no cuenta con tipos de documentos asociados");
        }
        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato(282, $usuarioId);
        $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }
        return $respuesta;
    }

    public function obtenerConfiguracionInicialNuevoDocumentoDetraccion($empresaId, $tipo1, $tipoProvisionPago, $usuarioId) {
        $respuesta = new ObjectUtil();
        $documento_tipo_sinFiltro = DocumentoTipoNegocio::create()->obtenerDocumentoTipoSinDocumentosDeMovimientoXTipo($empresaId, $tipo1, $tipoProvisionPago);
        $dataFiltrado = $documento_tipo_sinFiltro[2];
        $respuesta->documento_tipo = $dataFiltrado;
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El documento no cuenta con tipos de documentos asociados");
        }
        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($respuesta->documento_tipo['id'], $usuarioId);
        $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        return $respuesta;
    }

    public function guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId = null, $acCajaId = null) {
        // Insertamos el documento
        $movimientoId = null;
        $documento = DocumentoNegocio::create()->guardar($documentoTipoId, $movimientoId, NULL, $camposDinamicos, 1, $usuarioId, $monedaId, NULL, NULL, NULL, NULL, NULL, $periodoId, NULL, NULL, NULL, NULL, $acCajaId);

        $documentoId = $this->validateResponse($documento);
//        $documentoId=0;
//        if($documento[0]['vout_estado']==1){
//            $documentoId=$documento[0]['vout_id'];
//        }

        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException("No se pudo guardar el documento");
        }
        return $documentoId;
    }

//    Listar Pagos
    public function obtenerDocumentosPagoListarXCriterios($empresa_id, $tipo, $tipoProvision, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerDocumentosPagoListarXCriterios($empresa_id, $tipo, $tipoProvision, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadDocumentosPagoListarXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
//        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
//        $movimientoTipoId = $responseMovimientoTipo[0]['id'];
        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

//        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
//            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
//        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerCantidadDocumentosPagoListarXCriterios($empresa_id, $tipoPago, $tipoProvisionPago, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    //Detalle de pago 
    public function obtenerDetallePago($documentoId) {
        return Pago::create()->obtenerDetallePago($documentoId);
    }

    public function imprimir($id, $tipo_id) {
        if ($tipo_id == 45 || $tipo_id == 18) {
            $documento = Documento::create()->obtenerDocumentoDatos($id);
            if (ObjectUtil::isEmpty($documento)) {
                throw new WarningException("Documento vacio");
            }
            $dinamicos = DocumentoDatoValor::create()->obtenerXIdDocumento($id);
            if (ObjectUtil::isEmpty($dinamicos)) {
                throw new WarningException("Faltan especificar campos obligatorios en el documento");
            }
            $formateador = new EnLetras();
            $moneda = $documento[0]["moneda_id"];
            $total = $documento[0]["total"];
            $designacion = $moneda == 2 ? "S/." : "$/.";
            $documento[0]["dinamicos"] = $dinamicos;
            $documento[0]["enLetras"] = $formateador->ValorEnLetras($total, $moneda);
            $documento[0]["documentoTipoId"] = $tipo_id;
            $documento[0]["importe_formateado"] = $designacion . number_format($total, 2);

            $documento[0]["documentoTipoId"] = $tipo_id;
            return $documento[0];
        } else {
            return MovimientoNegocio::create()->imprimir($id, $tipo_id);
        }
//        throw new WarningException("No se ha encontrado una plantilla de impresion para este documento");
    }

    public function validarSiTieneDocumentoRetencionDetraccion($documentoAPagar) {

        $bandera = 0;
        foreach ($documentoAPagar as $k => $documento) {
            $documentoId = $documento["documentoId"];
            $res = Pago::create()->validarSiTieneDocumentoRetencionDetraccion($documentoId);

            if (count($res) == 0) {
                $bandera = 1; // no tiene documento de pago retencion o detraccion
            }
        }

        return $bandera;
    }

    public function obtenerActividades($tipoCobranzaPago, $empresaId) {
        return Pago::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
    }

    public function enviarCorreoDocumentoPago($usuarioId, $correo, $documentoId, $tipoCobroPago) {
        $dataDocumentoPago = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $dataEmpresa = DocumentoNegocio::create()->obtenerDireccionEmpresa($documentoId);
        $dataPagados = DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoId);

        //dibujar correo

        if (!ObjectUtil::isEmpty($dataDocumentoPago)) {

            $nombreDocumentoTipo = $dataDocumentoPago[0]['nombre_documento'];

            foreach ($dataDocumentoPago as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {

                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
//                        $valor = date_format($valor, 'd/m/y');
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = $item['moneda_simbolo'] . ' ' . number_format($valor, 2);
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
        }

        // detalle de documento

        $dataDetalle = '';
        if (!ObjectUtil::isEmpty($dataPagados)) {
            foreach ($dataPagados as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['descripcion'] . '</td>';
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . date_format((date_create($item['fecha_emision'])), 'd/m/Y') . '</td>';
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . date_format((date_create($item['fecha_vencimiento'])), 'd/m/Y') . '</td>';
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['serie'] . '</td>';
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['numero'] . '</td>';
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . date_format((date_create($item['pago_fecha'])), 'd/m/Y') . '</td>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . ' ' . number_format($item['total'], 2) . '</td>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_pago_simbolo'] . ' ' . number_format($item['importe'], 2) . '</td>';
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }


        $direccionEmpresa = '<tr><td style="text-align: left; padding: 0 55px 10px; font-size: 14px; line-height: 1.5; width: 80%">Documento generado en la empresa '
                . $dataEmpresa[0]['razon_social']
                . ' ubicada en '
                . $dataEmpresa[0]['direccion']
                . '</td></tr>'
        ;

        //variables para correo
        $nombreDocumentoTipo;
        $dataDocumento;
        $dataDetalle;
        $direccionEmpresa;
        if ($tipoCobroPago == 1) {
            $tipoDescripcion = 'DOCUMENTOS COBRADOS';
        } else {
            $tipoDescripcion = 'DOCUMENTOS PAGADOS';
        }

        //logica correo:             
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(6);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];
//        $correo = $plantilla[0]["destinatario"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|direccion_empresa|]", $direccionEmpresa, $cuerpo);
        $cuerpo = str_replace("[|tipo_cobro_pago|]", $tipoDescripcion, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId);

        return 1;
    }

    public function obtenerDocumentosPagadosXCriterios($empresa_id, $tipo, $tipoProvision, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerDocumentosPagadosXCriterios($empresa_id, $tipo, $tipoProvision, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadDocumentosPagadosXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
//        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
//        $movimientoTipoId = $responseMovimientoTipo[0]['id'];
        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

//        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
//            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
//        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Pago::create()->obtenerCantidadDocumentosPagadosXCriterio($empresa_id, $tipoPago, $tipoProvisionPago, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerPagoProgramacionXDocumentoId($documentoId) {
        return Pago::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
    }

    public function devolverImporteRedondeado($factor, $importe, $montoPendiente) {
        if ($factor != 1) {
            $numTemp = $montoPendiente - round($importe, 7) * $factor;
            $redondeo = round($numTemp, 2);
            if ($redondeo == 0 && $numTemp != 0) {
                $importe = $importe + 0.1;
//                $importe = round($importe, 1);
//                $importe=round( $importe, 2, PHP_ROUND_HALF_DOWN);
                $importe_10 = $importe * 10;
                $importe_10_floored = floor($importe_10);
                $value_floored = $importe_10_floored / 10;
                $importe = $value_floored;
            }
        }
        return $importe;
    }

    function generaCeros($numero) {
        //obtengop el largo del numero
        $largo_numero = strlen($numero);
        //especifico el largo maximo de la cadena
        $largo_maximo = 5;
        //tomo la cantidad de ceros a agregar
        $agregar = $largo_maximo - $largo_numero;
        //agrego los ceros
        for ($i = 0; $i < $agregar; $i++) {
            $numero = "0" . $numero;
        }
        //retorno el valor con ceros
        return $numero;
    }

    function eliminarRelacionDePago($documentoPagoId) {
        //OBTENEMOS EL ID DEL DOCUMENTO PAGADO
        $data = Pago::create()->obtenerDocumentoPagoXDocumentoPagoId($documentoPagoId);
        $pagoId = $data[0]['pago_id'];

        // ANULAMOS TODO LOS DOCUMENTO PAGO; 
        $dataDocumentoPago = PagoNegocio::create()->obtenerDocumentosPagoXPagoId($pagoId);
        if (ObjectUtil::isEmpty($dataDocumentoPago)) {
            throw new WarningException("No se encontraron documentos por eliminar.");
        }

        foreach ($dataDocumentoPago as $item) {
            $this->actualizarEstadoSolicitudAprobadaEar($item['documento_id']);
            $repuestaEliminarRelacion = Pago::create()->eliminarRelacionDePago($item['documento_pago_id']);
            if ($repuestaEliminarRelacion[0]['vout_exito'] != 1) {
                throw new WarningException($repuestaEliminarRelacion[0]['vout_mensaje']);
            }
        }

        $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($pagoId, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS);
        if ($respuestaAnularAsiento[0]['vout_exito'] != 1) {
            throw new WarningException($respuestaAnularAsiento[0]['vout_mensaje']);
        }

        return $repuestaEliminarRelacion;
    }

    function eliminarDocumentoDePago($documentoPago) {
        //OBTENEMOS LOS DOCUMENTOS QUE FUERON PAGADOS 
        $dataDocPagados = DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoPago);

        //SI FUERON PAGADOS MAS DE UN DOCUMENTO CON EL MISMO DOCUMENTO DE PAGO VALIDAR
        if (count($dataDocPagados) > 1) {
            throw new WarningException("Documento de pago ha sido usado en otro pago. No se puede eliminar");
        } else {
            $this->actualizarEstadoSolicitudAprobadaEar($dataDocPagados[0]['documento_id']);
            return Pago::create()->eliminarDocumentoDePago($documentoPago);
        }
    }

    function anularDocumentoPago($documentoId, $documentoEstadoId, $usuarioId) {
        //OBTENEMOS LOS DOCUMENTOS QUE FUERON PAGADOS 
        $dataDocPagados = DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoId);

        //SI FUERON PAGADOS MAS DE UN DOCUMENTO CON EL MISMO DOCUMENTO DE PAGO VALIDAR
        if (count($dataDocPagados) > 1) {
            throw new WarningException("Documento de pago ha sido usado en otro pago. No se puede anular");
        } else {
            $this->actualizarEstadoSolicitudAprobadaEar($dataDocPagados[0]['documento_id']);

            $respuestaAnular = Pago::create()->anularDocumentoPago($documentoId);

            if ($respuestaAnular[0]['vout_exito'] == 1) {
                //            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);
                $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
                if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                    throw new WarningException("No se Actualizo Documo estado");
                }
            } else {
                throw new WarningException("Error al anular el documento");
            }
        }
    }

    function actualizarEstadoSolicitudAprobadaEar($documentoId) {
        require_once __DIR__ . '/../../' . Configuraciones::CARPETA_SGI_ADMIN . '/func.php';

        $dataEar = obtenerEarSolicitudXSgiDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($dataEar)) {
            $earId = $dataEar['0']['ear_id'];
            $resEAR = actualizarAlEstadoAprobadoEAR($earId);

            if ($resEAR->exito == 0) {
                throw new WarningException("El estado actual del EAR es: " . $resEAR->estadoDescripcion . ", por tal motivo no se puede anular el documento de pago.");
            }
        }
    }

    public function enviarNotificacionCobranzas($dias_por_vencer, $plantillaId, $empresaId = 2) {
        $cobranzas = Pago::create()->obtenerCobranzasParaEmail($empresaId, $dias_por_vencer);
        if (!ObjectUtil::isEmpty($cobranzas)) {
            $cantidad = count($cobranzas);
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
            $cuerpo = $plantilla[0]["cuerpo"];
            $cuerpo = str_replace("[|detalle_programacion|]", EmailPlantillaNegocio::create()->construirTablaCobranzas($cobranzas), $cuerpo);
            $cuerpo = str_replace("[|varios|]", $cantidad === 1 ? '' : 'S', $cuerpo);
            $asunto = str_replace("[|varios|]", $cantidad === 1 ? '' : 'S', $plantilla[0]["asunto"]);
            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($plantilla[0]["destinatario"], count($cobranzas) . " " . $asunto, $cuerpo, 1, 1);
            EmailEnvioNegocio::create()->enviarPendientesEnvio();
        }
    }

    public function obtenerDocumentosPagoXPagoId($pagoId) {
        return Pago::create()->obtenerDocumentosPagoXPagoId($pagoId);
    }

    function anularPagoXDocumentoPago($documentoId, $documentoEstadoId, $usuarioId) {
        //OBTENEMOS LOS DOCUMENTOS QUE FUERON PAGADOS 
        $dataDocPagados = DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoId);
        $pagoId = $dataDocPagados[0]['pago_id'];

        //SI FUERON PAGADOS MAS DE UN DOCUMENTO CON EL MISMO DOCUMENTO DE PAGO VALIDAR
        if (count($dataDocPagados) > 1) {
            throw new WarningException("Documento de pago ha sido usado en otro pago. No se puede anular");
        } elseif (ObjectUtil::isEmpty($pagoId)) {

            $this->actualizarEstadoSolicitudAprobadaEar($dataDocPagados[0]['documento_id']);

            $respuestaAnular = Pago::create()->anularDocumentoPago($documentoId);

            if ($respuestaAnular[0]['vout_exito'] == 1) {
                //            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);
                $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
                if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                    throw new WarningException("No se Actualizo Documo estado");
                }
            } else {
                throw new WarningException("Error al anular el documento");
            }
        } else {

            // ANULAMOS TODO LOS DOCUMENTO PAGO; 
            $dataDocumentoPago = PagoNegocio::create()->obtenerDocumentosPagoXPagoId($pagoId);
            if (ObjectUtil::isEmpty($dataDocumentoPago)) {
                throw new WarningException("No se encontraron documentos por anular");
            }

            foreach ($dataDocumentoPago as $item) {
                $this->actualizarEstadoSolicitudAprobadaEar($item['documento_id']);
                $respuestaAnular = Pago::create()->anularDocumentoPago($item['documento_pago']);
                if ($respuestaAnular[0]['vout_exito'] != 1) {
                    throw new WarningException("Error al anular el documento.");
                }
            }

            $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($pagoId, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS);
            if ($respuestaAnularAsiento[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaAnularAsiento[0]['vout_mensaje']);
            }

            $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
            if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                throw new WarningException("No se Actualizo Documo estado");
            }
        }
    }

    function eliminarPagoXDocumentoDePago($documentoPago) {
        //OBTENEMOS LOS DOCUMENTOS QUE FUERON PAGADOS 
        $dataDocPagados = DocumentoNegocio::create()->obtenerDetalleDocumentoPago($documentoPago);

        //SI FUERON PAGADOS MAS DE UN DOCUMENTO CON EL MISMO DOCUMENTO DE PAGO VALIDAR
        if (count($dataDocPagados) > 1) {
            throw new WarningException("Documento de pago ha sido usado en otro pago. No se puede eliminar");
        } elseif (ObjectUtil::isEmpty($dataDocPagados)) {
            return Pago::create()->eliminarDocumentoDePago($documentoPago);
        } else {

            $pagoId = $dataDocPagados[0]['pago_id'];

            // ANULAMOS TODO LOS DOCUMENTO PAGO; 
            $dataDocumentoPago = PagoNegocio::create()->obtenerDocumentosPagoXPagoId($pagoId);
            if (ObjectUtil::isEmpty($dataDocumentoPago)) {
                throw new WarningException("No se encontraron documentos por eliminar.");
            }

            foreach ($dataDocumentoPago as $item) {
                $this->actualizarEstadoSolicitudAprobadaEar($item['documento_id']);

                if ($item['documento_pago'] == $documentoPago) {
                    $repuestaEliminarPago = Pago::create()->eliminarDocumentoDePago($documentoPago);
                    if ($repuestaEliminarPago[0]['vout_exito'] != 1) {
                        throw new WarningException("Error al anular el documento");
                    }
                } else {
                    $respuestaAnular = Pago::create()->anularDocumentoPago($item['documento_pago']);
                    if ($respuestaAnular[0]['vout_exito'] != 1) {
                        throw new WarningException("Error al anular el documento");
                    }
                }
            }

            $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($pagoId, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS);
            if ($respuestaAnularAsiento[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaAnularAsiento[0]['vout_mensaje']);
            }

            return $repuestaEliminarPago;
        }
    }

//    ============ Inicio Pago Documento Detraccion ================
    public function obtenerCargaInicial() {
        $respuesta = new ObjectUtil();
//        $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXTipo($tipo, $tipoPagoProvision);
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    public function registrarPagoDetraccion($usuarioId, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId, $documentoAPagarId, $empresaId) {
        // Insertamos el documento
        $movimientoId = null;
        $respuestaGuardarDocumento = DocumentoNegocio::create()->guardar($documentoTipoId, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, null, null, null, null, null, $periodoId);
        if (ObjectUtil::isEmpty($respuestaGuardarDocumento) || $respuestaGuardarDocumento[0]['vout_estado'] != 1) {
            throw new WarningException("No se pudo guardar el documento");
        }

        $documentoPagoId = $respuestaGuardarDocumento[0]['vout_id'];
        $monedaPago = $monedaId;

        $dataDocumentoPagoConDocumento = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoPagoId);
        $fechaPago = $dataDocumentoPagoConDocumento[0]['fecha_emision'];
        $cliente = $dataDocumentoPagoConDocumento[0]['persona_id'];

        $documentoPagoConDocumento = array(
            array("documentoId" => $dataDocumentoPagoConDocumento[0]['documento_id'],
                "tipoDocumento" => $dataDocumentoPagoConDocumento[0]['documento_tipo'],
                "documento_tipo_id" => $dataDocumentoPagoConDocumento[0]['documento_tipo_id'],
                "numero" => $dataDocumentoPagoConDocumento[0]['numero'],
                "serie" => $dataDocumentoPagoConDocumento[0]['serie'],
                "pendiente" => round($dataDocumentoPagoConDocumento[0]['pendiente'] * 1, 2),
                "total" => round($dataDocumentoPagoConDocumento[0]['pendiente'] * 1, 2),
                "dolares" => $dataDocumentoPagoConDocumento[0]['dolares'],
                "moneda" => (($dataDocumentoPagoConDocumento[0]['dolares'] * 1 === 0) ? "Soles" : "Doalres"),
                "monto" => round($dataDocumentoPagoConDocumento[0]['monto'] * 1, 2))
        );

        $dataDocumentoAPagar = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoAPagarId);
        $documentoAPagar = array(
            array("documentoId" => $dataDocumentoAPagar[0]['documento_id'],
                "tipoDocumento" => $dataDocumentoAPagar[0]['documento_tipo'],
                "documento_tipo_id" => $dataDocumentoAPagar[0]['documento_tipo_id'],
                "numero" => $dataDocumentoAPagar[0]['numero'],
                "serie" => $dataDocumentoAPagar[0]['serie'],
                "pendiente" => round($dataDocumentoAPagar[0]['pendiente'] * 1, 2),
                "total" => round($dataDocumentoAPagar[0]['pendiente'] * 1, 2),
                "dolares" => $dataDocumentoAPagar[0]['dolares'],
                "moneda" => (($dataDocumentoAPagar[0]['dolares'] * 1 === 0) ? "Soles" : "Doalres"),
                "monto" => round($dataDocumentoAPagar[0]['monto'] * 1, 2))
        );

        $dataTipoCambio = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($dataDocumentoAPagar[0]['fecha_emision']);
        if (!ObjectUtil::isEmpty($dataTipoCambio)) {
            $tipoCambio = ($dataTipoCambio[0]['equivalencia_venta'] * 1);
        } else {
            throw new WarningException("No existe el tipo de cambio para " . substr($fechaPago, 0, 10));
        }

        $montoAPagar = 0;
        $retencion = null;
        $repuestaPago = self::registrarPago($cliente, DateUtil::formatearBDACadena($fechaPago), $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, null, null, 1);

        if (ObjectUtil::isEmpty($repuestaPago) || $repuestaPago->error == 1 || $repuestaPago->indicador !== 1) {
            $mensaje = (!ObjectUtil::isEmpty($repuestaPago->mensaje) ? $repuestaPago->mensaje : "Error al registrar el pago");
            throw new WarningException($mensaje);
        }
        return $repuestaPago;
    }

    //    ============ Fin Pago Documento Detraccion ================
    public function compararFecha($fecha1, $fecha2) {
        $bandera = 1; //es mayor
        $fecha1 = new DateTime($fecha1);
        $fecha2 = new DateTime($fecha2);
        if ($fecha1 < $fecha2) {
            $bandera = 2; //es menor
        }
        if ($fecha1 == $fecha2) {
            $bandera = 3; //es igual
        }
        return $bandera;
    }

}
