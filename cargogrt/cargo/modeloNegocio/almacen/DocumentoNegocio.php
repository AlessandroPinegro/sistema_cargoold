<?php

require_once __DIR__ . '/../../modelo/almacen/Documento.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoTipoDocumentoTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/DocumentoDatoValorNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/VehiculoNegocio.php';
require_once __DIR__ . '/PeriodoNegocio.php';
require_once __DIR__ . '/../../util/DateUtil.php';
require_once __DIR__ . '/../../util/config/ConfigGlobal.php';

class DocumentoNegocio extends ModeloNegocioBase
{

    /**
     *
     * @return DocumentoNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function guardar($documentoTipoId, $movimientoId, $adjuntoId, $camposDinamicos, $estado, $usuarioCreacionId, $monedaId, $comentarioDoc = null, $descripcionDoc = null, $utilidadTotal = null, $utilidadPorcentajeTotal = null, $tipoPago = null, $periodoId = null, $esEar = null, $contOperacionTipoId = null, $afectoAImpuesto = null, $datosExtras = null, $acCajaId = NULL)
    {

        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $importeFlete = null;
        $importeSeguro = null;
        $importeOtros = null;
        $importeExoneracion = null;
        $importeTotal = null;
        $importeIgv = null;
        $importeIcbp = null;
        $importeSubTotal = null;
        $importeNoAfecto = null;
        $organizadorId = null;
        $direccionId = null;
        $percepcion = null;
        $cuentaId = null;
        $actividadId = null;
        $retencionDetraccionId = null;
        $detraccionTipoId = null;
        $cambioPersonalizado = null;
        $archivoAdjunto = null;
        $archivoAdjuntoMulti = null;
        $comprobanteTipoId = null;
        $compraNacionalBienes = null;
        $numeros = array();
        $cadenas = array();
        $fechas = array();
        $listas = array();
        $validarSerieNumero = false;
        $grupoUnico = array();
        $agenciaOrigen = null;
        $agenciaDestino = null;
        $vehiculo = null;
        $conductor = null;
        $fechaTraslado = null;
        $personaOrigenId = null;
        $personaDestinoId = null;
        $direccionDestinatarioId = null;

        $importeAjustePrecio = null;
        $importeCostoReparto = null;
        $importeDevolucionCargo = null;
        $importeOtrosCargos = null;

        $otrosCargosDescripcion = null;

        // 1. Obtenemos la configuracion actual del tipo de documento
        $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        $banderaSaltoValidacionProveedor = false;
        if ($documentoTipoId == 189 && !ObjectUtil::isEmpty(Util::filtrarArrayPorColumna($camposDinamicos, ["id", "valor"], ["1730", "224"]))) {
            $banderaSaltoValidacionProveedor = true;
        } //189

        if (!ObjectUtil::isEmpty($configuraciones)) {
            if (ObjectUtil::isEmpty($camposDinamicos)) {
                throw new WarningException("No se especificaron los campos mínimos necesarios para guardar el documento");
            }
            foreach ($configuraciones as $itemDtd) {
                foreach ($camposDinamicos as $valorDtd) {
                    if ((int) $itemDtd["id"] === (int) $valorDtd["id"]) {
                        $valor = ObjectUtil::isEmpty($valorDtd["valor"]) ? null : $valorDtd["valor"];
                        $banderaValidar = ((int) $itemDtd["opcional"] === 0 ? ($valorDtd["id"] == 1723 && $banderaSaltoValidacionProveedor ? FALSE : TRUE) : FALSE);

                        if ($banderaValidar && ObjectUtil::isEmpty($valor)) {
                            throw new WarningException("No se especificó un valor válido para " . $itemDtd["descripcion"]);
                        }

                        if (!ObjectUtil::isEmpty($itemDtd["grupo_unico"])) {
                            $descripcionUnicoGrupo = '';
                            if ($itemDtd["unico"] == 1) {
                                $descripcionUnicoGrupo = $itemDtd["descripcion"];
                            }

                            if (ObjectUtil::isEmpty($grupoUnico)) {
                                $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                array_push($grupoUnico, $grupo);
                            } else {

                                $banderaGrupo = false;

                                foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                                    if ($itemGrupo['grupo_unico'] == $itemDtd["grupo_unico"]) {
                                        $banderaGrupo = true;
                                        $grupoUnico[$indGrupo]['valor'] = $itemGrupo['valor'] . "-" . (is_numeric($valor) ? (int) $valor : $valor);

                                        if (!ObjectUtil::isEmpty($descripcionUnicoGrupo)) {
                                            $grupoUnico[$indGrupo]['descripcion'] = $descripcionUnicoGrupo;
                                        }
                                    }
                                }
                                if (!$banderaGrupo) {
                                    $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                    array_push($grupoUnico, $grupo);
                                }
                            }
                        }

                        switch ((int) $valorDtd["tipo"]) {
                            case DocumentoTipoNegocio::DATO_CODIGO:
                                $codigo = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_PERSONA:
                                if ($documentoTipoId == 284) {
                                    if ($valorDtd['codigo'] == 1) {
                                        $personaId = $valor;
                                        $personaOrigenId = $valor;
                                    } else {
                                        $personaDestinoId = $valor;
                                    }
                                } else {
                                    $personaId = $valor;
                                }
                                break;
                            case DocumentoTipoNegocio::DATO_SERIE:
                                $serie = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_NUMERO:
                                $validarSerieNumero = ($itemDtd["unico"] == 1);
                                $numero = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_EMISION:
                                $fechaEmision = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:
                                $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                                $fechaTentativa = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_DESCRIPCION:
                                $descripcion = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_COMENTARIO:
                                $comentario = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO:
                                $importeFlete = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO:
                                $importeSeguro = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_TOTAL:
                                $importeTotal = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_IGV:
                                $importeIgv = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_ICBP:
                                $importeIcbp = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL:
                                $importeSubTotal = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_OTROS:
                                $importeOtros = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_EXONERADO:
                                $importeExoneracion = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ENTERO:
                            case DocumentoTipoNegocio::DATO_FOB:
                            case DocumentoTipoNegocio::DATO_CIF:
                            case DocumentoTipoNegocio::DATO_FLETE_SUNAT:
                                array_push($numeros, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_CADENA:
                                array_push($cadenas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA:
                                array_push($fechas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_LISTA:
                                if ($itemDtd["codigo"] == "13") {
                                    $compraNacionalBienes = $valorDtd['valor'];
                                }
                                $idValor = $valorDtd['valor'];
                                $resDTDL = DocumentoTipoDatoLista::create()->obtenerPorId($idValor);
                                if ($resDTDL[0]['valor'] == 13) {
                                    $notaCreditoTipo13 = 1;
                                }

                                if ($itemDtd["codigo"] == "0") {
                                    $comprobanteTipoId = $resDTDL[0]['valor'];
                                }
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_ORGANIZADOR_DESTINO:
                                $organizadorId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_DIRECCION:
                                if ($documentoTipoId == 284) {
                                    $direccionDestinatarioId = $valor;
                                } else {
                                    $direccionId = $valor;
                                }
                                break;
                            case DocumentoTipoNegocio::DATO_PERCEPCION:
                                //                                array_push($numeros, $valorDtd);
                                $importeNoAfecto = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_CUENTA:
                                $cuentaId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ACTIVIDAD:
                                $actividadId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_RETENCION_DETRACCION:
                                $retencionDetraccionId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_DETRACCION_TIPO:
                                $detraccionTipoId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_OTRA_PERSONA:
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_CAMBIO_PERSONALIZADO:
                                $cambioPersonalizado = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_VENDEDOR:
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_ARCHIVO_ADJUNTO:
                                $archivoAdjunto = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ARCHIVO_ADJUNTO_MULTI:
                                $archivoAdjuntoMulti = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_PROVEEDOR_FINANCIMIENTO:
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_AGENCIA_ORIGEN:
                                $agenciaOrigen = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_AGENCIA_DESTINO:
                                $agenciaDestino = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_VEHICULO:
                                $vehiculo = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_CONDUCTOR:
                                $conductor = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_TRASLADO:
                                $fechaTraslado = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_OTROS_CARGOS:
                                $importeOtrosCargos = $valor;
                                $otrosCargosDescripcion = "OTROS CARGOS";
                                break;
                            case DocumentoTipoNegocio::DATO_DEVOLUCION_CARGO:
                                $importeDevolucionCargo = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_COSTO_REPARTO:
                                $importeCostoReparto = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_AJUSTE_PRECIO:
                                $importeAjustePrecio = $valor;
                                break;
                            default:
                        }
                        break;
                    }
                }
            }
        }

        if (ObjectUtil::isEmpty($agenciaOrigen)) {
            $agenciaOrigen = $datosExtras['agencia_id'];
        }

        //Validación de correlatividad numérica con la fecha de emisión
        if ($dataDocumentoTipo[0]["tipo"] == 1 || $dataDocumentoTipo[0]["identificador_negocio"] == 6) {
            $dataRes = Documento::create()->validarCorrelatividadNumericaConFechaEmision($documentoTipoId, $serie, $numero, $fechaEmision);

            if ($dataRes[0]['validacion'] == 0) {
                throw new WarningException("Validación de correlatividad numérica: cambie la fecha de emisión o el número del documento.");
            }
        }

        if (!ObjectUtil::isEmpty($grupoUnico)) {
            foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                $resSN = strpos(strtoupper($itemGrupo['valor']), 'S/N');
                if ($resSN === false) {
                    $documentoRepetido = Documento::create()->obtenerXDocumentoTipoXGrupoUnico($documentoTipoId, $itemGrupo['grupo_unico'], $itemGrupo['valor']);
                    if (!ObjectUtil::isEmpty($documentoRepetido)) {
                        throw new WarningException($itemGrupo['descripcion'] . " está duplicado");
                    }
                }
            }
        }

        if (ObjectUtil::isEmpty($descripcion)) {
            $descripcion = $descripcionDoc;
        }

        $arrayFecha = explode("-", $fechaEmision); //Dividimos la fecha, para obetener el Año-Mes-Dia.
        $serieAnio = $arrayFecha[0];
        $serieMes = $arrayFecha[1];

        if (strlen($serieMes) == 1) {
            $serieMes = "0" . $serieMes;
        }
        //$dataDocumentoTipo[0]["tipo"] == 19
        if ($dataDocumentoTipo[0]["tipo"] == 5 || $dataDocumentoTipo[0]["tipo"] == 6) {
            //PARA ESTOS DOCUMENTO EL PRIMER CARACTER DE LA SERIE EMPIEZA POR "P".
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorelativoPagosCobransa($dataDocumentoTipo[0]["tipo"], "P" . $serieAnio . $serieMes); //P20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $cadenaSerie = substr($serieCorrealativa[0]["codigo"], 7, 11);
                $ultimoNumero = (int) $cadenaSerie;
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $codigo = "P" . $serieAnio . $serieMes . $ultimoNumero;
        } else if ($dataDocumentoTipo[0]["tipo"] == 2 || $dataDocumentoTipo[0]["tipo"] == 3) {
            //PARA ESTOS DOCUMENTO EL PRIMER CARACTER DE LA SERIE EMPIEZA POR "C".
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorelativoPagosCobransa($dataDocumentoTipo[0]["tipo"], "C" . $serieAnio . $serieMes); //C20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $ultimoNumero = (int) substr($serieCorrealativa[0]["codigo"], 7, 11);
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $codigo = "C" . $serieAnio . $serieMes . $ultimoNumero;
        }

        //$documento = Documento::create()->guardar($documentoTipoId, $movimientoId, $personaId,$direccionId,$organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $estado, $usuarioCreacionId);
        if (ObjectUtil::isEmpty($periodoId)) {
            throw new WarningException('Periodo inválido');
        } else {
            //VALIDAR QUE EL PERIODO ESTE ABIERTO
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
            $dataPeriodo = $dataPeriodo->dataPeriodo;
            if ($dataPeriodo[0]['indicador'] != 2) {
                throw new WarningException('El periodo ' . $dataPeriodo[0]['anio'] . '-' . ($dataPeriodo[0]['mes'] * 1 < 10 ? '0' . $dataPeriodo[0]['mes'] : $dataPeriodo[0]['mes']) . ' no está abierto');
            }
        }
        if ($notaCreditoTipo13 == 1) {
            $importeTotal = 0.0;
            $importeIgv = 0.0;
            $importeSubTotal = 0.0;
        }

        $documento = Documento::create()->guardar(
            $documentoTipoId,
            $movimientoId,
            $personaId,
            $direccionId,
            $organizadorId,
            $adjuntoId,
            $codigo,
            $serie,
            $numero,
            $fechaEmision,
            $fechaVencimiento,
            $fechaTentativa,
            $descripcion,
            $comentarioDoc,
            $importeTotal,
            $importeIgv,
            $importeSubTotal,
            $estado,
            $monedaId,
            $usuarioCreacionId,
            $cuentaId,
            $actividadId,
            $retencionDetraccionId,
            $utilidadTotal,
            $utilidadPorcentajeTotal,
            $cambioPersonalizado,
            $tipoPago,
            $importeNoAfecto,
            $periodoId,
            $esEar,
            $importeFlete,
            $importeSeguro,
            $contOperacionTipoId,
            $importeOtros,
            $importeExoneracion,
            $afectoAImpuesto,
            $importeIcbp,
            $detraccionTipoId,
            $datosExtras['afecto_detraccion_retencion'],
            $datosExtras['porcentaje_afecto'],
            $datosExtras['monto_detraccion_retencion'],
            $importeOtrosCargos,
            $importeDevolucionCargo,
            $importeCostoReparto,
            $otrosCargosDescripcion,
            NULL,
            $direccionDestinatarioId,
            NULL,
            NULL,
            $comprobanteTipoId,
            $agenciaOrigen,
            $agenciaDestino,
            NULL,
            $personaDestinoId,
            NULL,
            NULL,
            NULL,
            NULL,
            $personaOrigenId,
            $acCajaId,
            NULL,
            $vehiculo,
            $conductor,
            NULL,
            $fechaTraslado,
            $importeAjustePrecio,
            null,
            //::DESAROLLO JESUS
            null
        );

        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId)) {
            throw new WarningException("No se pudo guardar el documento");
        }
        $documento[0]['compra_nacional_bienes'] = $compraNacionalBienes;
        // Ahora guardamos los campos dinámicos
        // Campos numéricos
        foreach ($numeros as $item) {
            DocumentoDatoValorNegocio::create()->guardarNumero($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        // Campos cadenas
        foreach ($cadenas as $item) {
            DocumentoDatoValorNegocio::create()->guardarCadena($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        // Campos fechas
        foreach ($fechas as $item) {
            DocumentoDatoValorNegocio::create()->guardarFecha($documentoId, $item["id"], DateUtil::formatearCadenaACadenaBD($item["valor"]), $usuarioCreacionId);
        }

        // Campos listas
        foreach ($listas as $item) {
            DocumentoDatoValorNegocio::create()->guardarLista($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        //DOCUMENTO ADJUNTO
        if (!ObjectUtil::isEmpty($archivoAdjunto['data'])) {

            $decode = Util::base64ToImage($archivoAdjunto['data']);
            $nombreArchivo = $archivoAdjunto['nombre'];
            $pos = strripos($nombreArchivo, '.');
            $ext = substr($nombreArchivo, $pos);

            $hoy = date("YmdHis");
            $nombreGenerado = $documentoId . $hoy . $usuarioCreacionId . $ext;
            $url = __DIR__ . '/../../util/uploads/documentoAdjunto/' . $nombreGenerado;

            file_put_contents($url, $decode);

            $resAdjunto = Documento::create()->insertarDocumentoAdjunto($documentoId, $nombreArchivo, $nombreGenerado, $usuarioCreacionId);
        }

        //DOCUMENTO ADJUNTO MULTIPLE
        if (!ObjectUtil::isEmpty($archivoAdjuntoMulti)) {
            $resAdjunto = $this->guardarArchivosXDocumentoID($documentoId, $archivoAdjuntoMulti, null, $usuarioCreacionId);
        }

        //4. Insertar documento_documento_estado
        if (ObjectUtil::isEmpty($movimientoId)) {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXDocumentoTipo($documentoTipoId);
        } else {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXMovimiento($movimientoId, $documentoTipoId);
        }

        $documento_estado = $movimientoTipoDocumentoTipo['0']['documento_estado_id'];
        //        if (!ObjectUtil::isEmpty($contOperacionTipoId)) {
        //            $documento_estado = 6;
        //        } else 
        if (ObjectUtil::isEmpty($documento_estado)) {
            //throw new WarningException("No se encontro estado en movimiento tipo documento tipo");
            $documento_estado = 1;
        }

        $this->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacionId);

        return $documento;
    }

    public function guardarCajaBancos($documentoTipoId, $movimientoId, $adjuntoId, $camposDinamicos, $estado, $usuarioCreacionId, $monedaId, $comentarioDoc = null, $descripcionDoc = null, $utilidadTotal = null, $utilidadPorcentajeTotal = null, $tipoPago = null, $periodoId = null, $esEar = null, $contOperacionTipoId = null, $afectoAImpuesto = null, $datosExtras = null, $acCajaId = NULL)
    {

        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $importeFlete = null;
        $importeSeguro = null;
        $importeOtros = null;
        $importeExoneracion = null;
        $importeTotal = null;
        $importeIgv = null;
        $importeIcbp = null;
        $importeSubTotal = null;
        $importeNoAfecto = null;
        $organizadorId = null;
        $direccionId = null;
        $percepcion = null;
        $cuentaId = null;
        $actividadId = null;
        $retencionDetraccionId = null;
        $detraccionTipoId = null;
        $cambioPersonalizado = null;
        $archivoAdjunto = null;
        $archivoAdjuntoMulti = null;
        $comprobanteTipoId = null;

        $compraNacionalBienes = null;

        $numeros = array();
        $cadenas = array();
        $fechas = array();
        $listas = array();
        $validarSerieNumero = false;
        $grupoUnico = array();
        // 1. Obtenemos la configuracion actual del tipo de documento
        $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        $banderaSaltoValidacionProveedor = false;
        if ($documentoTipoId == 189 && !ObjectUtil::isEmpty(Util::filtrarArrayPorColumna($camposDinamicos, ["id", "valor"], ["1730", "224"]))) {
            $banderaSaltoValidacionProveedor = true;
        } //189

        if (!ObjectUtil::isEmpty($configuraciones)) {
            if (ObjectUtil::isEmpty($camposDinamicos)) {
                throw new WarningException("No se especificaron los campos minimos necesarios para guardar el documento");
            }
            foreach ($configuraciones as $itemDtd) {
                foreach ($camposDinamicos as $valorDtd) {
                    if ((int) $itemDtd["id"] === (int) $valorDtd["id"]) {
                        $valor = ObjectUtil::isEmpty($valorDtd["valor"]) ? null : $valorDtd["valor"];
                        $banderaValidar = ((int) $itemDtd["opcional"] === 0 ? ($valorDtd["id"] == 1723 && $banderaSaltoValidacionProveedor ? FALSE : TRUE) : FALSE);

                        if ($banderaValidar && ObjectUtil::isEmpty($valor)) {
                            throw new WarningException("No se especificó un valor válido para " . $itemDtd["descripcion"]);
                        }

                        if (!ObjectUtil::isEmpty($itemDtd["grupo_unico"])) {
                            $descripcionUnicoGrupo = '';
                            if ($itemDtd["unico"] == 1) {
                                $descripcionUnicoGrupo = $itemDtd["descripcion"];
                            }

                            if (ObjectUtil::isEmpty($grupoUnico)) {
                                $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                array_push($grupoUnico, $grupo);
                            } else {

                                $banderaGrupo = false;

                                foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                                    if ($itemGrupo['grupo_unico'] == $itemDtd["grupo_unico"]) {
                                        $banderaGrupo = true;
                                        $grupoUnico[$indGrupo]['valor'] = $itemGrupo['valor'] . "-" . (is_numeric($valor) ? (int) $valor : $valor);

                                        if (!ObjectUtil::isEmpty($descripcionUnicoGrupo)) {
                                            $grupoUnico[$indGrupo]['descripcion'] = $descripcionUnicoGrupo;
                                        }
                                    }
                                }
                                if (!$banderaGrupo) {
                                    $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                    array_push($grupoUnico, $grupo);
                                }
                            }
                        }

                        switch ((int) $valorDtd["tipo"]) {
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
                                $validarSerieNumero = ($itemDtd["unico"] == 1);
                                $numero = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_EMISION:
                                $fechaEmision = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:
                                $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                                $fechaTentativa = DateUtil::formatearCadenaACadenaBD($valor);
                                break;
                            case DocumentoTipoNegocio::DATO_DESCRIPCION:
                                $descripcion = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_COMENTARIO:
                                $comentario = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO:
                                $importeFlete = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO:
                                $importeSeguro = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_TOTAL:
                                $importeTotal = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_IGV:
                                $importeIgv = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_ICBP:
                                $importeIcbp = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL:
                                $importeSubTotal = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_OTROS:
                                $importeOtros = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_IMPORTE_EXONERADO:
                                $importeExoneracion = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ENTERO:
                            case DocumentoTipoNegocio::DATO_FOB:
                            case DocumentoTipoNegocio::DATO_CIF:
                            case DocumentoTipoNegocio::DATO_FLETE_SUNAT:
                                array_push($numeros, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_CADENA:
                                array_push($cadenas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_FECHA:
                                array_push($fechas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_LISTA:
                                if ($itemDtd["codigo"] == "13") {
                                    $compraNacionalBienes = $valorDtd['valor'];
                                }
                                $idValor = $valorDtd['valor'];
                                $resDTDL = DocumentoTipoDatoLista::create()->obtenerPorId($idValor);
                                if ($resDTDL[0]['valor'] == 13) {
                                    $notaCreditoTipo13 = 1;
                                }

                                if ($itemDtd["codigo"] == "0") {
                                    $comprobanteTipoId = $resDTDL[0]['valor'];
                                }
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_ORGANIZADOR_DESTINO:
                                $organizadorId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_DIRECCION:
                                $direccionId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_PERCEPCION:
                                //                                array_push($numeros, $valorDtd);
                                $importeNoAfecto = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_CUENTA:
                                $cuentaId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ACTIVIDAD:
                                $actividadId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_RETENCION_DETRACCION:
                                $retencionDetraccionId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_DETRACCION_TIPO:
                                $detraccionTipoId = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_OTRA_PERSONA:
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_CAMBIO_PERSONALIZADO:
                                $cambioPersonalizado = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_VENDEDOR:
                                array_push($listas, $valorDtd);
                                break;
                            case DocumentoTipoNegocio::DATO_ARCHIVO_ADJUNTO:
                                $archivoAdjunto = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_ARCHIVO_ADJUNTO_MULTI:
                                $archivoAdjuntoMulti = $valor;
                                break;
                            case DocumentoTipoNegocio::DATO_PROVEEDOR_FINANCIMIENTO:
                                array_push($listas, $valorDtd);
                                break;
                            default:
                        }
                        break;
                    }
                }
            }
        }
        //        if ($validarSerieNumero) {
        //            $documentosRepetidos = Documento::create()->obtenerXSerieNumero($documentoTipoId, $serie, $numero);
        //            if (!ObjectUtil::isEmpty($documentosRepetidos)) {
        //                $nuevoNumero = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId);
        //                throw new WarningException("El número del documento que desea registrar está duplicado. "." Usar el siguiente: ".$serie."-".$nuevoNumero);
        //            }
        //        }
        //        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        //Validación de correlatividad numérica con la fecha de emisión
        if ($dataDocumentoTipo[0]["tipo"] == 1 || $dataDocumentoTipo[0]["identificador_negocio"] == 6) {
            $dataRes = Documento::create()->validarCorrelatividadNumericaConFechaEmision($documentoTipoId, $serie, $numero, $fechaEmision);

            if ($dataRes[0]['validacion'] == 0) {
                throw new WarningException("Validación de correlatividad numérica: cambie la fecha de emisión o el número del documento.");
            }
        }

        if (!ObjectUtil::isEmpty($grupoUnico)) {
            foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                $resSN = strpos(strtoupper($itemGrupo['valor']), 'S/N');
                if ($resSN === false) {
                    $documentoRepetido = Documento::create()->obtenerXDocumentoTipoXGrupoUnico($documentoTipoId, $itemGrupo['grupo_unico'], $itemGrupo['valor']);
                    if (!ObjectUtil::isEmpty($documentoRepetido)) {
                        throw new WarningException($itemGrupo['descripcion'] . " está duplicado");
                    }
                }
            }
        }

        if (ObjectUtil::isEmpty($descripcion)) {
            $descripcion = $descripcionDoc;
        }

        // $mes_curso = getdate()["mon"];
        //$anio_curso = getdate()["year"];
        $arrayFecha = explode("-", $fechaEmision); //Dividimos la fecha, para obetener el Año-Mes-Dia.
        $serieAnio = $arrayFecha[0];
        $serieMes = $arrayFecha[1];

        if (strlen($serieMes) == 1) {
            $serieMes = "0" . $serieMes;
        }
        //$dataDocumentoTipo[0]["tipo"] == 19
        if ($dataDocumentoTipo[0]["tipo"] == 5 || $dataDocumentoTipo[0]["tipo"] == 6) {
            //PARA ESTOS DOCUMENTO EL PRIMER CARACTER DE LA SERIE EMPIEZA POR "P".
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorelativoPagosCobransa($dataDocumentoTipo[0]["tipo"], "P" . $serieAnio . $serieMes); //P20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $cadenaSerie = substr($serieCorrealativa[0]["codigo"], 7, 11);
                $ultimoNumero = (int) $cadenaSerie;
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $codigo = "P" . $serieAnio . $serieMes . $ultimoNumero;
        } else if ($dataDocumentoTipo[0]["tipo"] == 2 || $dataDocumentoTipo[0]["tipo"] == 3) {
            //PARA ESTOS DOCUMENTO EL PRIMER CARACTER DE LA SERIE EMPIEZA POR "C".
            $serieCorrealativa = Documento::create()->obtenerNumeroSerieCorelativoPagosCobransa($dataDocumentoTipo[0]["tipo"], "C" . $serieAnio . $serieMes); //C20161000001
            if (!ObjectUtil::isEmpty($serieCorrealativa)) {
                $ultimoNumero = (int) substr($serieCorrealativa[0]["codigo"], 7, 11);
                $ultimoNumero = $this->generaCeros($ultimoNumero + (int) 1);
            } else {
                $ultimoNumero = $this->generaCeros(1);
            }
            $codigo = "C" . $serieAnio . $serieMes . $ultimoNumero;
        }

        //$documento = Documento::create()->guardar($documentoTipoId, $movimientoId, $personaId,$direccionId,$organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $estado, $usuarioCreacionId);
        if (ObjectUtil::isEmpty($periodoId)) {
            throw new WarningException('Periodo inválido');
        } else {
            //VALIDAR QUE EL PERIODO ESTE ABIERTO
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
            $dataPeriodo = $dataPeriodo->dataPeriodo;
            if ($dataPeriodo[0]['indicador'] != 2) {
                throw new WarningException('El periodo ' . $dataPeriodo[0]['anio'] . '-' . ($dataPeriodo[0]['mes'] * 1 < 10 ? '0' . $dataPeriodo[0]['mes'] : $dataPeriodo[0]['mes']) . ' no está abierto');
            }
        }
        if ($notaCreditoTipo13 == 1) {
            $importeTotal = 0.0;
            $importeIgv = 0.0;
            $importeSubTotal = 0.0;
        }

        $documento = Documento::create()->guardarCajaBancos(
            $documentoTipoId,
            $movimientoId,
            $personaId,
            $direccionId,
            $organizadorId,
            $adjuntoId,
            $codigo,
            $serie,
            $numero,
            $fechaEmision,
            $fechaVencimiento,
            $fechaTentativa,
            $descripcion,
            $comentarioDoc,
            $importeTotal,
            $importeIgv,
            $importeSubTotal,
            $estado,
            $monedaId,
            $usuarioCreacionId,
            $cuentaId,
            $actividadId,
            $retencionDetraccionId,
            $utilidadTotal,
            $utilidadPorcentajeTotal,
            $cambioPersonalizado,
            $tipoPago,
            $importeNoAfecto,
            $periodoId,
            $esEar,
            $importeFlete,
            $importeSeguro,
            $contOperacionTipoId,
            $importeOtros,
            $importeExoneracion,
            $afectoAImpuesto,
            $importeIcbp,
            $detraccionTipoId,
            $datosExtras['afecto_detraccion_retencion'],
            $datosExtras['porcentaje_afecto'],
            $datosExtras['monto_detraccion_retencion'],
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            $comprobanteTipoId,
            $datosExtras['agencia_id'],
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            $acCajaId
        );

        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId)) {
            throw new WarningException("No se pudo guardar el documento");
        }
        $documento[0]['compra_nacional_bienes'] = $compraNacionalBienes;
        // Ahora guardamos los campos dinámicos
        // Campos numéricos
        foreach ($numeros as $item) {
            DocumentoDatoValorNegocio::create()->guardarNumero($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        // Campos cadenas
        foreach ($cadenas as $item) {
            DocumentoDatoValorNegocio::create()->guardarCadena($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        // Campos fechas
        foreach ($fechas as $item) {
            DocumentoDatoValorNegocio::create()->guardarFecha($documentoId, $item["id"], DateUtil::formatearCadenaACadenaBD($item["valor"]), $usuarioCreacionId);
        }

        // Campos listas
        foreach ($listas as $item) {
            DocumentoDatoValorNegocio::create()->guardarLista($documentoId, $item["id"], $item["valor"], $usuarioCreacionId);
        }

        //DOCUMENTO ADJUNTO
        if (!ObjectUtil::isEmpty($archivoAdjunto['data'])) {

            $decode = Util::base64ToImage($archivoAdjunto['data']);
            $nombreArchivo = $archivoAdjunto['nombre'];
            $pos = strripos($nombreArchivo, '.');
            $ext = substr($nombreArchivo, $pos);

            $hoy = date("YmdHis");
            $nombreGenerado = $documentoId . $hoy . $usuarioCreacionId . $ext;
            $url = __DIR__ . '/../../util/uploads/documentoAdjunto/' . $nombreGenerado;

            file_put_contents($url, $decode);

            $resAdjunto = Documento::create()->insertarDocumentoAdjunto($documentoId, $nombreArchivo, $nombreGenerado, $usuarioCreacionId);
        }

        //DOCUMENTO ADJUNTO MULTIPLE
        if (!ObjectUtil::isEmpty($archivoAdjuntoMulti)) {
            $resAdjunto = $this->guardarArchivosXDocumentoID($documentoId, $archivoAdjuntoMulti, null, $usuarioCreacionId);
        }

        //4. Insertar documento_documento_estado
        if (ObjectUtil::isEmpty($movimientoId)) {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXDocumentoTipo($documentoTipoId);
        } else {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXMovimiento($movimientoId, $documentoTipoId);
        }

        $documento_estado = $movimientoTipoDocumentoTipo['0']['documento_estado_id'];
        if (!ObjectUtil::isEmpty($contOperacionTipoId)) {
            $documento_estado = 6;
        } elseif (ObjectUtil::isEmpty($documento_estado)) {
            //throw new WarningException("No se encontro estado en movimiento tipo documento tipo");
            $documento_estado = 1;
        }

        $this->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacionId);

        return $documento;
    }

    function guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion)
    {
        if ($documentoId != null) {
            //Eliminando archivos
            foreach ($lstDocEliminado as $d) {
                //Dando de baja en documento_adjunto
                $resAdjunto = Documento::create()->insertarActualizarDocumentoAdjunto($d[0]['id'], null, null, null, null, 0);
                if ($resAdjunto[0]['vout_exito'] != 1) {
                    throw new WarningException($resAdjunto[0]['vout_mensaje']);
                }
            }
            //Insertando documento_adjunto
            foreach ($lstDocumento as $d) {
                //Se valida que el ID contenga el prefijo temporal "t" para que se opere, si no lo encuentra ya estaría registrado

                if (strpos($d['id'], 't') !== false) {

                    //DOCUMENTO ADJUNTO
                    if (!ObjectUtil::isEmpty($d['data'])) {

                        $decode = Util::base64ToImage($d['data']);
                        $nombreArchivo = $d['archivo'];
                        $pos = strripos($nombreArchivo, '.');
                        $ext = substr($nombreArchivo, $pos);

                        $hoy = date("YmdHis");
                        $nombreGenerado = $documentoId . $hoy . $usuarioCreacionId . $ext;
                        $url = __DIR__ . '/../../util/uploads/documentoAdjunto/' . $nombreGenerado;

                        file_put_contents($url, $decode);

                        $resAdjunto = Documento::create()->insertarActualizarDocumentoAdjunto(null, $documentoId, $nombreArchivo, $nombreGenerado, $usuCreacion);
                        if ($resAdjunto[0]['vout_exito'] != 1) {
                            throw new WarningException($resAdjunto[0]['vout_mensaje']);
                        }
                    }
                }
            }
        } else {
            throw new WarningException("No existe documento para relacionar con el archivo adjunto");
        }
        return $resAdjunto;
    }

    function obtenerXId($documentoId, $documentoTipoId)
    {
        return Documento::create()->obtenerXId($documentoId, $documentoTipoId);
    }

    function anular($documentoId)
    {
        return Documento::create()->anular($documentoId);
    }

    function eliminar($documentoId)
    {
        return Documento::create()->eliminar($documentoId);
    }

    function obtenerDocumentoAPagar($documentoId, $fechaPago = null)
    {
        if (!ObjectUtil::isEmpty($fechaPago)) {
            $fechaPago = DateUtil::formatearCadenaACadenaBD($fechaPago);
        }

        return Documento::create()->obtenerDocumentoAPagar($documentoId, $fechaPago);
    }

    function obtenerFechaPrimerDocumento()
    {
        return Documento::create()->obtenerFechaPrimerDocumento();
    }

    function obtenerDetalleDocumento($documentoId)
    {
        return Documento::create()->obtenerDetalleDocumento($documentoId);
    }

    function obtenerComentarioDocumento($documentoId)
    {
        return Documento::create()->obtenerComentarioDocumento($documentoId);
    }

    function obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serie = NULL)
    {
        $numero = Documento::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serie);
        return (ObjectUtil::isEmpty($numero)) ? '' : $numero[0]["numero"];
    }

    function obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId)
    {
        $numero = Documento::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId);
        return (ObjectUtil::isEmpty($numero)) ? '' : $numero[0]["numero"];
    }

    function obtenerDetalleDocumentoPago($documentoId)
    {
        return Documento::create()->obtenerDetalleDocumentoPago($documentoId);
    }

    function obtenerDataDocumentoACopiar($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId)
    {
        return Documento::create()->obtenerDataDocumentoACopiar($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
    }

    function guardarDocumentoRelacionado($documentoId, $documentoRelacionadoId, $valorCheck, $estado, $usuarioCreacion, $relacionEar = null, $tipo = null)
    {
        return Documento::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacionadoId, $valorCheck, $estado, $usuarioCreacion, $relacionEar, $tipo);
    }

    function insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId)
    {
        return Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId);
    }

    function ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId, $accion = NULL, $comentario = NULL)
    {
        return Documento::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId, $accion, $comentario);
    }

    function obtenerDocumentosRelacionadosXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentosRelacionadosXDocumentoId($documentoId);
    }

    function obtenerDocumentosRelacionadosXDocumentoIdXTipo($documentoId, $tipo)
    {
        return Documento::create()->obtenerDocumentosRelacionadosXDocumentoIdXTipo($documentoId, $tipo);
    }

    function obtenerDocumentosRelacionados($documentoId)
    {
        return Documento::create()->obtenerDocumentosRelacionados($documentoId);
    }

    function obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId)
    {
        return Documento::create()->obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId);
    }

    function obtenerDireccionEmpresa($documentoId)
    {
        return Documento::create()->obtenerDireccionEmpresa($documentoId);
    }

    function actualizarTipoRetencionDetraccion($documentoId, $tipoRetencionDetraccion)
    {
        return Documento::create()->actualizarTipoRetencionDetraccion($documentoId, $tipoRetencionDetraccion);
    }

    function actualizarComentarioDocumento($documentoId, $comentario)
    {
        return Documento::create()->actualizarComentarioDocumento($documentoId, $comentario);
    }

    function buscarDocumentosXOpcionXSerieNumero($opcionId, $busqueda)
    {
        return Documento::create()->buscarDocumentosXOpcionXSerieNumero($opcionId, $busqueda);
    }

    function anularDocumentoRelacionXDocumentoId($documentoId)
    {
        return Documento::create()->anularDocumentoRelacionXDocumentoId($documentoId);
    }

    function guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario = null, $periodoId = null, $tipoPago = null, $monedaId = null, $usuarioId, $contOperacionTipoId = null, $afectoAImpuesto = null, $edicionForm = 1, $datosExtras = NULL, $acCajaId = NULL)
    {

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        //        $comentario = null;
        $cambioPersonalizado = null;
        $importeExoneracion = null;
        $importeOtros = null;
        $importeTotal = null;
        $importeIgv = null;
        $importeIcbp = null;
        $importeSubTotal = null;
        $importeFlete = null;
        $importeSeguro = null;
        $organizadorId = null;
        $detracionTipoId = null;
        $importeNoAfecto = null;
        $cuentaId = null;
        $actividadId = null;
        $retencionDetraccionId = null;
        $numeros = array();
        $cadenas = array();
        $fechas = array();
        $listas = array();
        $comprobanteTipoId = null;
        $compraNacionalBienes = null;
        if (ObjectUtil::isEmpty($camposDinamicos)) {
            throw new WarningException("No se especificaron los campos minimos necesarios para guardar el documento");
        }

        foreach ($camposDinamicos as $valorDtd) {

            $valor = ObjectUtil::isEmpty($valorDtd["valor"]) ? null : $valorDtd["valor"];
            if ((int) $valorDtd["opcional"] === 0 && ObjectUtil::isEmpty($valor)) {
                throw new WarningException("No se especificó un valor válido para " . $valorDtd["descripcion"]);
            }
            switch ((int) $valorDtd["tipo"]) {
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
                    //                                $validarSerieNumero = ($itemDtd["unico"] == 1);
                    $numero = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_EMISION:
                    $fechaEmision = DateUtil::formatearCadenaACadenaBD($valor);
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:
                    $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor);
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                    $fechaTentativa = DateUtil::formatearCadenaACadenaBD($valor);
                    break;
                case DocumentoTipoNegocio::DATO_DESCRIPCION:
                    $descripcion = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_ICBP:
                    $importeIcbp = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO:
                    $importeFlete = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO:
                    $importeSeguro = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_OTROS:
                    $importeOtros = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_EXONERADO:
                    $importeExoneracion = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_TOTAL:
                    $importeTotal = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_IGV:
                    $importeIgv = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL:
                    $importeSubTotal = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_ENTERO:
                    array_push($numeros, $valorDtd);
                    break;
                case DocumentoTipoNegocio::DATO_CADENA:
                    array_push($cadenas, $valorDtd);
                    break;
                case DocumentoTipoNegocio::DATO_FECHA:
                    array_push($fechas, $valorDtd);
                    break;
                case DocumentoTipoNegocio::DATO_LISTA:
                    if ($valorDtd["codigo"] == "13") {
                        $compraNacionalBienes = $valorDtd['valor'];
                    }
                    $idValor = $valorDtd['valor'];
                    $resDTDL = DocumentoTipoDatoLista::create()->obtenerPorId($idValor);

                    if ($valorDtd["codigo"] == "0") {
                        $comprobanteTipoId = $resDTDL[0]['valor'];
                    }
                    array_push($listas, $valorDtd);
                    break;
                case DocumentoTipoNegocio::DATO_ORGANIZADOR_DESTINO:
                    $organizadorId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_DIRECCION:
                    $direccionId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_PERCEPCION:
                    $importeNoAfecto = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_CUENTA:
                    $cuentaId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_ACTIVIDAD:
                    $actividadId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_RETENCION_DETRACCION:
                    $retencionDetraccionId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_DETRACCION_TIPO:
                    $detracionTipoId = $valor;
                    break;
                case DocumentoTipoNegocio::DATO_OTRA_PERSONA:
                    array_push($listas, $valorDtd);
                    break;

                case DocumentoTipoNegocio::DATO_CAMBIO_PERSONALIZADO:
                    $cambioPersonalizado = $valor;
                    break;
                default:
            }
        }

        $grupoUnico = array();
        $documentoTipoId = $dataDocumento[0]['documento_tipo_id'];
        // 1. Obtenemos la configuracion actual del tipo de documento
        $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        if (!ObjectUtil::isEmpty($configuraciones)) {
            if (ObjectUtil::isEmpty($camposDinamicos)) {
                throw new WarningException("No se especificaron los campos minimos necesarios para guardar el documento");
            }
            foreach ($configuraciones as $itemDtd) {
                foreach ($camposDinamicos as $valorDtd) {
                    if ((int) $itemDtd["id"] === (int) $valorDtd["id"]) {
                        $valor = ObjectUtil::isEmpty($valorDtd["valor"]) ? null : $valorDtd["valor"];
                        if ((int) $itemDtd["opcional"] === 0 && ObjectUtil::isEmpty($valor)) {
                            throw new WarningException("No se especificó un valor válido para " . $itemDtd["descripcion"]);
                        }

                        if (!ObjectUtil::isEmpty($itemDtd["grupo_unico"])) {
                            $descripcionUnicoGrupo = '';
                            if ($itemDtd["unico"] == 1) {
                                $descripcionUnicoGrupo = $itemDtd["descripcion"];
                            }

                            if (ObjectUtil::isEmpty($grupoUnico)) {
                                $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                array_push($grupoUnico, $grupo);
                            } else {

                                $banderaGrupo = false;

                                foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                                    if ($itemGrupo['grupo_unico'] == $itemDtd["grupo_unico"]) {
                                        $banderaGrupo = true;
                                        $grupoUnico[$indGrupo]['valor'] = $itemGrupo['valor'] . "-" . (is_numeric($valor) ? (int) $valor : $valor);

                                        if (!ObjectUtil::isEmpty($descripcionUnicoGrupo)) {
                                            $grupoUnico[$indGrupo]['descripcion'] = $descripcionUnicoGrupo;
                                        }
                                    }
                                }
                                if (!$banderaGrupo) {
                                    $grupo = array('grupo_unico' => $itemDtd["grupo_unico"], 'valor' => (is_numeric($valor) ? (int) $valor : $valor), 'descripcion' => $descripcionUnicoGrupo);
                                    array_push($grupoUnico, $grupo);
                                }
                            }
                        }
                    }
                }
            }
        }

        //Validación de correlatividad numérica con la fecha de emisión
        //INACTIVO DOCUMENTO TEMPORALMENTE
        $resInac = DocumentoNegocio::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, 2);

        //        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        //        if ($dataDocumentoTipo[0]["tipo"] == 1 || $dataDocumentoTipo[0]["identificador_negocio"] == 6) {
        //            $dataRes = Documento::create()->validarCorrelatividadNumericaConFechaEmision($documentoTipoId, $serie, $numero, $fechaEmision);
        //
        //            if ($dataRes[0]['validacion'] == 0) {
        //                throw new WarningException("Validación de correlatividad numérica: cambie la fecha de emisión o el número del documento.");
        //            }
        //        }

        if (!ObjectUtil::isEmpty($grupoUnico)) {
            foreach ($grupoUnico as $indGrupo => $itemGrupo) {
                $resSN = strpos(strtoupper($itemGrupo['valor']), 'S/N');
                if ($resSN === false) {
                    $documentoRepetido = Documento::create()->obtenerXDocumentoTipoXGrupoUnico($documentoTipoId, $itemGrupo['grupo_unico'], $itemGrupo['valor']);
                    if (!ObjectUtil::isEmpty($documentoRepetido)) {
                        throw new WarningException($itemGrupo['descripcion'] . " está duplicado");
                    }
                }
            }
        }
        //ACTIVO DOCUMENTO
        $resAct = DocumentoNegocio::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, 1);

        if (!ObjectUtil::isEmpty($periodoId) && $dataDocumento[0]['periodo_id'] != $periodoId) {
            //VALIDAR QUE EL PERIODO ESTE ABIERTO
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
            $dataPeriodo = $dataPeriodo->dataPeriodo;
            if ($dataPeriodo[0]['indicador'] != 2) {
                throw new WarningException('El periodo ' . $dataPeriodo[0]['anio'] . '-' . ($dataPeriodo[0]['mes'] * 1 < 10 ? '0' . $dataPeriodo[0]['mes'] : $dataPeriodo[0]['mes']) . ' no está abierto');
            }
        }

        //        if ($edicionForm == 2) {
        $documento = Documento::create()->editarDocumentoModal($documentoId, $personaId, $direccionId, $organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $monedaId, $cuentaId, $actividadId, $retencionDetraccionId, $importeNoAfecto, $periodoId, $tipoPago, $importeFlete, $importeSeguro, $contOperacionTipoId, $importeOtros, $importeExoneracion, $detracionTipoId, $afectoAImpuesto, $importeIcbp, $datosExtras['agencia_id'], $acCajaId, $comprobanteTipoId);
        //        } else {
        //            $documento = Documento::create()->editarDocumento($documentoId, $personaId, $direccionId, $organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $monedaId, $cuentaId, $actividadId, $retencionDetraccionId, $importeNoAfecto, $periodoId, $tipoPago, $importeFlete, $importeSeguro, $contOperacionTipoId, $importeOtros, $importeExoneracion, $detracionTipoId, $afectoAImpuesto, $importeIcbp, $cambioPersonalizado);
        //        }
        $documento[0]['compra_nacional_bienes'] = $compraNacionalBienes;
        if (!ObjectUtil::isEmpty($contOperacionTipoId)) {
            $this->ActualizarDocumentoEstadoId($documentoId, 6, $usuarioId);
            $respuestaAnularDistribucion = ContDistribucionContableNegocio::create()->anularDistribucionContableXDocumentoId($documentoId); //    ->::create->anularVoucherXDocumentoId();
        }
        // Ahora guardamos los campos dinámicos
        // Campos numéricos
        foreach ($numeros as $item) {
            $res = DocumentoDatoValorNegocio::create()->editarNumero($documentoId, $item["id"], $item["valor"], $usuarioId);
        }

        // Campos cadenas
        foreach ($cadenas as $item) {
            $res = DocumentoDatoValorNegocio::create()->editarCadena($documentoId, $item["id"], $item["valor"], $usuarioId);
        }

        // Campos fechas
        foreach ($fechas as $item) {
            $res = DocumentoDatoValorNegocio::create()->editarFecha($documentoId, $item["id"], DateUtil::formatearCadenaACadenaBD($item["valor"]), $usuarioId);
        }

        // Campos listas
        foreach ($listas as $item) {
            $res = DocumentoDatoValorNegocio::create()->editarLista($documentoId, $item["id"], $item["valor"], $usuarioId);
        }

        return $documento;
    }

    function buscarDocumentosOperacionXOpcionXSerieNumero($opcionId, $busqueda)
    {
        return Documento::create()->buscarDocumentosOperacionXOpcionXSerieNumero($opcionId, $busqueda);
    }

    function obtenerPersonaXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerPersonaXDocumentoId($documentoId);
    }

    function buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdArray, $busqueda)
    {
        return Documento::create()->buscarDocumentosXTipoDocumentoXSerieNumero(Util::fromArraytoString($documentoTipoIdArray), $busqueda);
    }

    function buscarDocumentosXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Documento::create()->buscarDocumentosXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    function buscarDocumentosXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Documento::create()->buscarDocumentosXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    function buscarDocumentosXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Documento::create()->buscarDocumentosXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    function obtenerRelacionesDocumento($documentoId)
    {
        return Documento::create()->obtenerRelacionesDocumento($documentoId);
    }

    function obtenerDocumentoRelacionadoImpresion($documentoId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
    }

    function obtenerDocumentoXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoXDocumentoId($documentoId);
    }
    function obtenerDocumentoXPaqueteId($paqueteId)
    {
        return Documento::create()->obtenerDocumentoXPaqueteId($paqueteId);
    }

    public function obtenerFechasPosterioresDocumentosSalidas($fechaEmision, $bienId, $organizadorId)
    {
        return Documento::create()->obtenerFechasPosterioresDocumentosSalidas($fechaEmision, $bienId, $organizadorId);
    }

    //operaciones: modal de busqueda
    function buscarDocumentosOperacionXTipoDocumentoXSerieNumero($documentoTipoIdArray, $busqueda)
    {
        return Documento::create()->buscarDocumentosOperacionXTipoDocumentoXSerieNumero(Util::fromArraytoString($documentoTipoIdArray), $busqueda);
    }

    function generaCeros($numero)
    {
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

    function obtenerIdTipoDocumentoXIdDocumento($idDocumento)
    {
        return Documento::create()->obtenerIdTipoDocumentoXIdDocumento($idDocumento);
    }

    function actualizarEstadoQRXDocumentoId($documentoId, $estadoQR)
    {
        return Documento::create()->actualizarEstadoQRXDocumentoId($documentoId, $estadoQR);
    }

    function obtenerDocumentoIdXMovimientoBienId($movimientoBienId)
    {
        return Documento::create()->obtenerDocumentoIdXMovimientoBienId($movimientoBienId);
    }

    function obtenerDocumentoRelacion($documentoOrigenId, $documentoDestinoId, $documentoId)
    {
        $respuesta = new ObjectUtil();
        $documentoACopiar = $this->obtenerDataDocumentoACopiar($documentoDestinoId, $documentoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($documentoACopiar)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->documentoACopiar = $documentoACopiar;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId);

        if ($documentoDestinoId != $documentoOrigenId) {
            $respuesta->documentoCopiaRelaciones = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentoCopiaRelaciones = 1;
        }

        return $respuesta;
    }

    function obtenerDocumentoAdjuntoXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);
    }

    function obtenerAnticiposPendientesXPersonaId($personaId, $monedaId)
    {
        return Documento::create()->obtenerAnticiposPendientesXPersonaId($personaId, $monedaId);
    }

    public function obtenerPlanillaImportacionXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerPlanillaImportacionXDocumentoId($documentoId);
    }

    public function obtenerDuaXTicketEXT($documentoId)
    {
        return Documento::create()->obtenerDuaXTicketEXT($documentoId);
    }

    public function actualizarTipoCambioMontoNoAfectoXDocumentoId($documentoId, $tc, $montoNoAfecto)
    {
        return Documento::create()->actualizarTipoCambioMontoNoAfectoXDocumentoId($documentoId, $tc, $montoNoAfecto);
    }

    public function obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoId, $documentoRelacionId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoId, $documentoRelacionId);
    }

    public function obtenerDataAlmacenVirtualXDocumentoId($documentoTipoId)
    {
        return Documento::create()->obtenerDataAlmacenVirtualXDocumentoId($documentoTipoId);
    }

    public function obtenerDocumentoDuaXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoDuaXDocumentoId($documentoId);
    }

    public function obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);
    }

    public function obtenerDocumentoRelacionadoActivoXDocumentoId2($documentoId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoActivoXDocumentoId2($documentoId);
    }

    public function obtenerDocumentoRelacionadoActivoXDocumentoIdXPaqueteId($documentoId, $paqueteId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoActivoXDocumentoIdXPaqueteId($documentoId, $paqueteId);
    }

    public function obtenerDocumentoRelacionadoActivoXDocumentoId3($documentoId)
    {
        return Documento::create()->obtenerDocumentoRelacionadoActivoXDocumentoId3($documentoId);
    }

    public function obtenerDocumentoFEXId($documentoId)
    {
        return Documento::create()->obtenerDocumentoFEXId($documentoId);
    }

    public function obtenerSerieNumeroXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerSerieNumeroXDocumentoId($documentoId);
    }

    public function actualizarNroSecuencialBajaXDocumentoId($documentoId, $nroSecuencialBaja, $ticket)
    {
        return Documento::create()->actualizarNroSecuencialBajaXDocumentoId($documentoId, $nroSecuencialBaja, $ticket);
    }

    public function obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo)
    {
        return Documento::create()->obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo);
    }

    public function obtenerIdDocumentosResumenDiario()
    {
        return Documento::create()->obtenerIdDocumentosResumenDiario();
    }

    public function actualizarEstadoEfactAnulacionXDocumentoId($documentoId, $estado, $ticket)
    {
        return Documento::create()->actualizarEstadoEfactAnulacionXDocumentoId($documentoId, $estado, $ticket);
    }

    //EDICION
    function obtenerDataDocumentoACopiarEdicion($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId)
    {
        return Documento::create()->obtenerDataDocumentoACopiarEdicion($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
    }

    function actualizarEstadoXDocumentoIdXEstado($documentoId, $estado)
    {
        return Documento::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, $estado);
    }

    //DOCUMENTOS EAR
    function validarImportePago($documentoIdSumaImporte, $documentoId)
    {
        return Documento::create()->validarImportePago($documentoIdSumaImporte, $documentoId);
    }

    //SCRIPTS MEJORAS DE EFACT
    public function actualizarEstadoXId($documentoId, $estado)
    {
        return Documento::create()->actualizarEstadoXId($documentoId, $estado);
    }

    public function actualizarEfactPdfNombre($documentoId, $nombrePDF)
    {
        return Documento::create()->actualizarEfactPdfNombre($documentoId, $nombrePDF);
    }

    public function actualizarEfactEstadoRegistro($documentoId, $estadoRegistro, $resultado)
    {
        return Documento::create()->actualizarEfactEstadoRegistro($documentoId, $estadoRegistro, $resultado);
    }

    public function actualizarEfacturaEstado($documentoId, $estdoEnvio, $mensaje)
    {
        return Documento::create()->actualizarEfacturaEstado($documentoId, $estdoEnvio, $mensaje);
    }

    function obtenerInvoiceCommercialXDUA($documentoId)
    {
        return Documento::create()->obtenerInvoiceCommercialXDUA($documentoId);
    }

    function obtenerDUAXInvoiceComercial($documentoId)
    {
        return Documento::create()->obtenerDUAXInvoiceComercial($documentoId);
    }

    function obtenerDocumentoPagoImportacionXInvoiceComercialXTipoDocumentoSUNAT($documentoId, $documentoTipoSunat)
    {
        return Documento::create()->obtenerDocumentoPagoImportacionXInvoiceComercialXTipoDocumentoSUNAT($documentoId, $documentoTipoSunat);
    }

    function obtenerDocumentoDocumentoEstadoXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoDocumentoEstadoXDocumentoId($documentoId);
    }

    function obtenerMontoAddValoremXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerMontoAddValoremXDocumentoId($documentoId);
    }

    function validarPedidoStockDisponibleEntregar($documentoId)
    {
        return Documento::create()->validarPedidoStockDisponibleEntregar($documentoId);
    }

    function obtenerDespacho2($agencia_destino_id, $fecha_salida, $usuarioCreacion)
    {
        $response_perfil = Perfil::create()->obtnerPerfilXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $id_agencia = $response[0]['agencia_id'];
        return Documento::create()->obtenerDespacho($id_agencia, $agencia_destino_id, $fecha_salida, $usuarioCreacion);
    }

    function obtenerDocumentoxVehiculo($vehiculoId, $id_agencia)
    {
        return Documento::create()->obtenerDocumentoxVehiculo($vehiculoId, $id_agencia);
    }

    function obtenerDocumentoManiestoxVehiculo($vehiculoId, $id_agencia)
    {
        return Documento::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $id_agencia);
    }
    function obtenerDocumentoManiestoxRecepcionId($documentoId)
    {
        return Documento::create()->obtenerDocumentoManiestoxRecepcionId($documentoId);
    }

    function obtenerDocumentoRecepcionPendientexVehiculoxAgencia($vehiculoId, $agenciaId)
    {
        return Documento::create()->obtenerDocumentoRecepcionPendientexVehiculoxAgencia($vehiculoId, $agenciaId);
    }

    function obtenerDocumentoxReparto($codigo_agencia, $choferId)
    {
        return Documento::create()->obtenerDocumentoxReparto($codigo_agencia, $choferId);
    }

    public function generaQRticket($texto, $nrodeComprobante)
    {


        require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
        $texto = VehiculoNegocio::create()->eliminar_acentos($texto);
        $merchantid = ConfigGlobal::PARAM_MERCHANTID_NIUBIZ;
        $ruta = __DIR__ . '/../../vistas/com/movimiento/codigoQR/' . $nrodeComprobante . ".png";

        QRcode::png($texto, $ruta, "Q", 10, 2);

        return $ruta;
    }

 public function generaQRguia($texto, $nrodeComprobante)
    {


        require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
        $texto = VehiculoNegocio::create()->eliminar_acentos($texto);
        $merchantid = ConfigGlobal::PARAM_MERCHANTID_NIUBIZ;
        $ruta = __DIR__ . '/../../vistas/com/movimiento/codigoQR/' . $nrodeComprobante . ".png";
        $ruta2 = 'vistas/com/movimiento/codigoQR/' . $nrodeComprobante . ".png";
        QRcode::png($texto, $ruta, "Q", 10, 2);

        return $ruta2;
    }


    public function obtenerDocumentoPagoXDocumentoId($documentoId)
    {
        return Documento::create()->obtenerDocumentoPagoXDocumentoId($documentoId);
    }

    public function anularDocumentoPago($documentoId)
    {
        return Documento::create()->anularDocumentoPago($documentoId);
    }

    public function obtenerPedidoPendienteAtenderMovilXUsuarioId($usuarioId)
    {
        $data = Documento::create()->obtenerPedidoPendienteAtenderMovilXUsuarioId($usuarioId);

        foreach ($data as $index => $item) {
            $urlDespacho = Configuraciones::url_host() . "tracking.php?" . Util::encripta("tipo=2&documentoId=" . $item['documento_id']);
            $data[$index]['url_tracking'] = $urlDespacho;
        }
        return $data;
    }

    public function listarPedidosUsuario($usuarioId)
    {
        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);
        $idPersona = $persona[0]['id'];
        $data = Documento::create()->listarPedidosPersona($idPersona);

        foreach ($data as $index => $item) {
            $urlDespacho = Configuraciones::url_host() . "tracking.php?" . Util::encripta("tipo=2&documentoId=" . $item['documento_id']);
            $data[$index]['url_tracking'] = $urlDespacho;
            $urlComprobante = '';
            if (!ObjectUtil::isEmpty($item['documento_relacionado'])) {
                $urlComprobante = Configuraciones::url_host() . "vistas/com/movimiento/ticket.php?id=" . $item['documento_relacionado'];
            }

            $data[$index]['url_comprobante'] = $urlComprobante;
        }
        return $data;
    }

    function registrarPaqueteXDocumentoId($documentoId, $usuarioId)
    {
        return Documento::create()->registrarPaqueteXDocumentoId($documentoId, $usuarioId);
    }

    function actualizarFechaSalidaDocumento($id)
    {
        return Documento::create()->actualizarFechaSalidaDocumento($id);
    }
}
