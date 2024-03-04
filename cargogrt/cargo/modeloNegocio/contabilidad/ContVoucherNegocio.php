<?php

require_once __DIR__ . '/../../modelo/contabilidad/ContVoucher.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContDistribucionContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContVoucherDetalleNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanillaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContLibroNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContParametroContableNegocio.php';

class ContVoucherNegocio extends ModeloNegocioBase {

    //IDENTIFICA POR CUAL VENTANA FUE REGISTRADO EL VOUCHER
    const IDENTIFICADOR_REGISTRO_COMPRAS = 1;
    const IDENTIFICADOR_REGISTRO_VENTAS = 2;
    const IDENTIFICADOR_LIQUIDACION = 3;
    const IDENTIFICADOR_CAJAYBANCOS = 4;
    const IDENTIFICADOR_LIBRO_DIARIO = 5;
    const IDENTIFICADOR_PLANILLA = 6;
    const IDENTIFICADOR_ACTIVO_FIJO = 7;
    const IDENTIFICADOR_COSTO_VENTA = 8;
    const IDENTIFICADOR_CIERRE_PRE = 9;
    const IDENTIFICADOR_CIERRE = 10;
    const IDENTIFICADOR_APERTURA = 11;
    //ID'S DE LAS OPERACIONES TIPO EN LA BD
    const OPERACION_TIPO_ID_COMPRAS_EXTRANJERA = 1;
    const OPERACION_TIPO_ID_SERVICIO_EXTRANJERA = 28;
    const OPERACION_TIPO_ID_COMPRAS_COSTO = 2;
    const OPERACION_TIPO_ID_COMPRAS_GENERALES = 3;
    const OPERACION_TIPO_ID_COMPRAS_RECIBO_HONORARIO = 4;
    const OPERACION_TIPO_ID_COMPRAS_COSTO_FIJO = 21;
    const OPERACION_TIPO_ID_LIQUIDACION = 10;
    const OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA = 12;
    const OPERACION_TIPO_ID_CAJA_BANCO_SALIDA = 11;
    const OPERACION_TIPO_ID_VENTAS = 13;
    const OPERACION_TIPO_ID_PAGO_ADELANTO = 14;
    const OPERACION_TIPO_ID_LIBRO_DIARIO = 15;
    const OPERACION_TIPO_ID_PLANILLA = 16;
    const OPERACION_TIPO_ID_RETENCION = 22;
    const OPERACION_TIPO_ID_COSTO_VENTA = 25;
    const OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_PERDIDA = 7;
    const OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_GANANCIA = 8;
    const OPERACION_TIPO_ID_ACTIVO_FIJO = 21;
    const OPERACION_TIPO_ID_ACTIVO_FIJO_BAJA_RETIRO = 23;
    const OPERACION_TIPO_ID_REVERSA_VENTA_GRATUITA = 24;
    const OPERACION_TIPO_ID_PAGO_FINANCIAMIENTO = 29;
    const OPERACION_TIPO_ID_CIERRE_PRE = 33;
    const OPERACION_TIPO_ID_CIERRE = 34;
    const OPERACION_TIPO_ID_APERTURA = 35;
    const OPERACION_TIPO_ID_REVERSA_MERCADERIA_TRANSITO = 36;
    const COLUMNA_TIPODATO_BUSQUEDA = 'tipo';
    const MONEDA_ID_SOLES = 2;
    const MONEDA_ID_DOLARES = 4;

    public $VARIACION_CERO = FALSE;
    public $arrayDocumentoPagoExcluido = array(26391, 26392, 26462, 26463, 26655, 26680, 26798, 26824);

    /**
     * 
     * @return ContVoucherNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContVoucherXId($id) {
        return ContVoucher::create()->obtenerContVoucherXId($id);
    }

    public function obtenerContVoucherRelacionXDocumentos($documentoId) {
        return ContVoucher::create()->obtenerContVoucherRelacionXCriterios($documentoId, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_COMPRAS, ContLibro::CLASIFICACION_COMPRAS);
    }

    public function obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio) {
        return ContVoucher::create()->obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio);
    }

    public function anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio) {
        return ContVoucher::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio);
    }
    
    public function transpasarDetalleVoucherXVoucherId($voucherId, $voucherNuevoId) {
        return ContVoucher::create()->transpasarDetalleVoucherXVoucherId($voucherId, $voucherNuevoId);
    }

    public function guardarVoucherIdXDocumentoId($voucherId, $documentoId) {
        return ContVoucher::create()->guardarVoucherIdXDocumentoId($voucherId, $documentoId);
    }

    public function anularDetalleXVoucherId($id) {
        $respuestaAnular = ContVoucher::create()->anularDetalleXVoucherId($id);
        if ($respuestaAnular[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException('Error al intentar anular el deltalle del voucher : ' . $respuestaAnular[0]['vout_mensaje']);
        }
        return $respuestaAnular;
    }

    public function anularContVoucherXId($id) {
        $respuestaAnular = ContVoucher::create()->anularContVoucherXId($id);
        if ($respuestaAnular[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException('Error al intentar anular el voucher : ' . $respuestaAnular[0]['vout_mensaje']);
        }
        return $respuestaAnular;
    }

    public function actualizarContVoucherXId($id, $monedaId, $glosa) {
        $respuestaActualizar = ContVoucher::create()->actualizarContVoucherXId($id, $monedaId, $glosa);
        if ($respuestaActualizar[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException('Error al intentar actualizar el voucher : ' . $respuestaActualizar[0]['vout_mensaje']);
        }
        return $respuestaActualizar;
    }

    public function anularContVoucherXCriterios($documentoId, $contOperacionTipoId, $identificadorRegistro) {
        return ContVoucher::create()->anularContVoucherXCriterios($documentoId, $contOperacionTipoId, $identificadorRegistro);
    }

    public function anularXDocumentoIdXContOperacionTipoId($documentoId, $contOperacionTipoId) {
        $respuestaAnulacion = ContVoucher::create()->anularXDocumentoIdXContOperacionTipoId($documentoId, $contOperacionTipoId);
        if (!ObjectUtil::isEmpty($respuestaAnulacion[0]['cont_voucher_id'])) {
            $respuestaAnulacionDetalle = ContVoucherDetalleNegocio::create()->anularXVoucherId($respuestaAnulacion[0]['cont_voucher_id']);
        }
        return $respuestaAnulacion;
    }

    public function obtenerSaldoCuentaXPeridoIdXCodigo($periodoId, $codigoCuenta, $banderaAgrupador, $exigirMoneda) {
        return ContVoucher::create()->obtenerSaldoCuentaXPeridoIdXCodigo($periodoId, $codigoCuenta, $banderaAgrupador, $exigirMoneda);
    }

    public function registrarContVoucherRegistroCompras($documentoId, $usuarioId) {

        if (ObjectUtil::isEmpty($documentoId)) {
            throw new WarningException('Se requiere el identificador del documento para generar su asiento.');
        }

        $documento = DocumentoNegocio::create()->obtenerXId($documentoId, NULL);
        $monedaId = $documento[0]['moneda_id'];
        $periodoId = $documento[0]['periodo_id'];
        $contOperacionTipoId = $documento[0]['cont_operacion_tipo_id'];
        $glosa = $documento[0]['comentario'];

        $camposDinamicos = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);

        if ($documento[0]['documento_tipo_id'] == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_DUA) {

            // REGISTRO DEL VOUCHER PARA DUA
            $dataInvoiceCommercial = DocumentoNegocio::create()->obtenerInvoiceCommercialXDUA($documentoId);
            if (ObjectUtil::isEmpty($dataInvoiceCommercial)) {
                throw new WarningException('Debe relacionar una invoice commercial con la orden de compra para poder guardar.');
            } elseif ($dataInvoiceCommercial[0]['documento_estado_id'] != 3) {
                throw new WarningException('Primero debe aprobar el invoice commercial relacionado a esta DUA.');
            }
            $periodoInvoiceId = $dataInvoiceCommercial[0]['periodo_id'];
            $documentoInvoiceId = $dataInvoiceCommercial[0]['id'];
            $monedaInvoiceId = $dataInvoiceCommercial[0]['moneda_id'];

            $montoFleteInvoice = (ObjectUtil::isEmpty($dataInvoiceCommercial[0]['flete']) ? 0 : $dataInvoiceCommercial[0]['flete'] * 1);
            $montoSeguroInvoice = (ObjectUtil::isEmpty($dataInvoiceCommercial[0]['seguro']) ? 0 : $dataInvoiceCommercial[0]['seguro'] * 1);
            $montoSubTotalInvoice = (ObjectUtil::isEmpty($dataInvoiceCommercial[0]['subtotal']) ? 0 : ($dataInvoiceCommercial[0]['subtotal'] * 1) - $montoFleteInvoice - $montoSeguroInvoice);
            $dataDistribucionContableInvoice = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoInvoiceId);

            $camposDinamicosInvoice = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoInvoiceId);
            $tipoInvoice = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicosInvoice, DocumentoTipoNegocio::DATO_LISTA, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor');
            
            //Se comento esta lína de codigo porque Adelina decidió hacer el asiento manual. 20 a la 28
//            if ($tipoInvoice == ContDistribucionContableNegocio::TIPO_DUA_ID_FOB) {
//                //Reversa por la mercadería del invoice en tránsito.
//                $glosaInvoice = "Reversa de mercadería en transito.";
//                $distribucionContableInvoice[] = array('documento_id' => $documentoInvoiceId, 'montoTotal' => $montoSubTotalInvoice);
//                $respuestaVoucherCompra = self::guardarContVoucher($documentoId, self::OPERACION_TIPO_ID_REVERSA_MERCADERIA_TRANSITO, NULL, $periodoInvoiceId, $monedaInvoiceId, $glosaInvoice, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_COMPRAS, $distribucionContableInvoice, $usuarioId);
//            }

            $montoSubTotalDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoFleteDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoSeguroDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoTotalDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoPercepcionDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_PERCEPCION, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoIgvDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_IGV, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
            $montoAddValoremDUA = 0;
            $dataAddValorenDua = DocumentoNegocio::create()->obtenerMontoAddValoremXDocumentoId($documentoId);
            if (!ObjectUtil::isEmpty($dataAddValorenDua)) {
                $montoAddValoremDUA = $dataAddValorenDua[0]['monto_addvalorem'] * 1;
            }

            /* REGISTRO DEL ASIENTO DE LA DUA COMPREDEN IGV(40) + ADDVALOREM (20) * */

            if (ObjectUtil::isEmpty($montoIgvDUA) || $montoIgvDUA <= 0) {
                throw new WarningException('Error al intentar registar el voucher : se requiere el monto del igv para registrar el asiento de la DUA');
            }
            $montoTotalAsiento = $montoIgvDUA;
            $distribucionContable[] = array('documento_id' => $documentoId, 'montoIgv' => $montoIgvDUA);
            if (($montoAddValoremDUA * 1) > 0) {
                $montoTotalAsiento = Util::redondearNumero($montoTotalAsiento + $montoAddValoremDUA, 6);
                $distribucionContable[] = array('documento_id' => $documentoId, 'montoSubTotal' => $montoAddValoremDUA);
                foreach ($dataDistribucionContableInvoice as $itemDistribucion) {
                    $montoLinea = ((($itemDistribucion['porcentaje'] * 1) / 100) * $montoAddValoremDUA);
                    $distribucionContable[] = array('documento_id' => $documentoId, 'plan_contable_codigo' => $itemDistribucion['plan_contable_codigo'], 'monto' => $montoLinea);
                }
            }

            $distribucionContable[] = array('documento_id' => $documentoId, 'montoTotal' => $montoTotalAsiento);

            $respuestaVoucherCompra = self::guardarContVoucher($documentoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_COMPRAS, $distribucionContable, $usuarioId);

            $respuestaActualizarCampoDocumento = self::guardarVoucherIdXDocumentoId($respuestaVoucherCompra[0]['vout_id'], $documentoId);
            if ($respuestaActualizarCampoDocumento[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registar el voucher : ' . $respuestaActualizarCampoDocumento[0]['vout_mensaje']);
            }
            return $respuestaVoucherCompra;


            /*
              $montoFOB = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FOB, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
              $montosubTotal = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
              $montoFlete = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
              $montoSeguro = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

              $distribucionContable = array();
              $montoTotal = 0;
              $montoAfecto = 0;
              $montoAdValorem = $montosubTotal - $montoFOB - $montoFlete - $montoSeguro;

              if ($montoAdValorem > 0) {
              $montoAfecto = $montoAdValorem * 1;
              $montoTotal += $montoAfecto;
              $distribucionContable[] = array('documento_id' => $documentoId, 'plan_contable_codigo' => PlanContable::PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA, 'monto' => $montoAfecto);
              }

              $tipoDUA = NULL;
              // FOB -> 1;
              // CIF -> 2;

              $valorTipoDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_LISTA, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor');
              $tipoDUA = $valorTipoDUA;
              // SI ES DE TIPO DE FOB -> DEBE IR EN EL ASIENTO SI O SI
              if ($tipoDUA == ContDistribucionContableNegocio::TIPO_DUA_ID_FOB && ($montoFlete > 0 || $montoSeguro > 0)) {
              $distribucionContable[] = array('documento_id' => $documentoId, 'montoFlete' => $montoFlete);
              $distribucionContable[] = array('documento_id' => $documentoId, 'montoSeguro' => $montoSeguro);
              $montoTotal += $montoFlete + $montoSeguro;
              } elseif ($tipoDUA == ContDistribucionContableNegocio::TIPO_DUA_ID_CIF) {
              // SI ES DE TIPO CIF -> SE DEBE HACER UNA COMPARATIVA ENTRE LA INVOICE Y LA DUA, PARA REALIZAR EL ASIENTO POR LA DIFERENCIA
              if ($montoFlete > $montoFleteInvoice) {
              $distribucionContable[] = array('documento_id' => $documentoId, 'montoFlete' => $montoFlete - $montoFleteInvoice);
              $montoTotal += $montoFlete - $montoFleteInvoice;
              }

              if ($montoSeguro > $montoSeguroInvoice) {
              $distribucionContable[] = array('documento_id' => $documentoId, 'montoSeguro' => $montoSeguro - $montoSeguroInvoice);
              $montoTotal += $montoSeguro - $montoSeguroInvoice;
              }
              }
              $montoIgv = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_IGV, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
              if ($montoIgv > 0) {
              $montoTotal += $montoIgv;
              }
              $distribucionContable[] = array('documento_id' => $documentoId, 'montoIgv' => $montoIgv);

              $distribucionContable[] = array('documento_id' => $documentoId, 'montoTotal' => $montoTotal);
             */
            // SE CAMBIO TODO EL PROCESO SOLO PARA REGISTRAR REGITRAR EL IGV
            //YA NO SE USA POR CAMBIO EN EL PROCESO 
            /*
              $fechaEmisionDUA = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FECHA_EMISION, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor');
              $fechaEmisionInvoice = $dataInvoiceCommercial[0]['fecha_emision']; //DateUtil::formatearFechaBDAaCadenaVw(substr($dataInvoiceCommercial[0]['fecha_emision'], 0, 10));

              $tipoCambioInvoice = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fechaEmisionInvoice)[0]['equivalencia_venta'];
              $tipoCambioDUA = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fechaEmisionDUA)[0]['equivalencia_venta'];


              $montoSubTotalInvoiceSolesTCInvoice = $montoSubTotalInvoice * $tipoCambioInvoice;
              $montoSubTotalInvoiceSolesTCDUA = $montoFobDUA * $tipoCambioDUA; //MontoFobDua Incluye el addValorem

              $documentoIdInvoice = $dataInvoiceCommercial[0]['id'];
              $distribucionContable = array();
              $contOperacionTipoId = NULL;

              $diferenciaSubTotal = Util::redondearNumero($montoSubTotalInvoiceSolesTCDUA - $montoSubTotalInvoiceSolesTCInvoice, 6);
              $montoDiferenciaCambio = abs($diferenciaSubTotal);
              if (Util::redondearNumero($montoDiferenciaCambio, 2) > 0) {
              $contOperacionTipoId = (Util::redondearNumero($diferenciaSubTotal, 2) < 0 ? ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_PERDIDA : ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_GANANCIA);

              $distribucionContable[] = array('documento_id' => $documentoId, 'fecha' => $fechaEmisionDUA, 'moneda_id' => self::MONEDA_ID_SOLES, 'plan_contable_codigo' => PlanContable::PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA_COSTO, 'monto' => $montoDiferenciaCambio);

              $dataDistribucionContableInvoice = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoIdInvoice);
              foreach ($dataDistribucionContableInvoice as $item) {
              $montoLinea = ((($item['porcentaje'] * 1) / 100) * $montoDiferenciaCambio);
              $distribucionContable[] = array('documento_id' => $documentoId, 'fecha' => $fechaEmisionDUA, 'moneda_id' => self::MONEDA_ID_SOLES, 'plan_contable_codigo' => $item['plan_contable_codigo'], 'monto' => $montoLinea);
              }
              $distribucionContable[] = array('documento_id' => $documentoId, 'fecha' => $fechaEmisionDUA, 'moneda_id' => self::MONEDA_ID_SOLES, 'montoTotal' => $montoDiferenciaCambio);
              $respuestVoucherDiferenciaTipoCambio = self::guardarContVoucher($documentoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_COMPRAS, $distribucionContable, $usuarioId);
              }

              return $respuestaVoucherCompra; */
        } else {

            $montoAfecto = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

            $montoFlete = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

            $montoSeguro = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

            $montoIgv = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_IGV, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

            $montoIcbp = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_ICBP, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

            $montoTotal = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

//          $dataDocumento['montoNoAfecto'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_EXONERADO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
//          $dataDocumento['montoNoAfecto'] += Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_OTROS, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;
//            $montoNoAfecto = Util::redondearNumero(($montoTotal - $montoAfecto - $montoIgv - $montoFlete - $montoSeguro), 2);

            $montoNoAfecto = Util::redondearNumero(($montoTotal - $montoAfecto - $montoIgv - $montoFlete - $montoSeguro), 6);

            $montoSubTotal = $montoAfecto + $montoNoAfecto;

            $distribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
            if (ObjectUtil::isEmpty($distribucionContable) || count($distribucionContable) == 0) {
                throw new WarningException('Aún no completa la distribución contable del documento.');
            }

            if (!ObjectUtil::isEmpty($montoTotal) && ($montoTotal * 1) >= 0) {
                $distribucionContable[] = array("documento_id" => $documentoId, "montoTotal" => $montoTotal);
            }

            if (!ObjectUtil::isEmpty($montoIgv) && ($montoIgv * 1) > 0) {
                $distribucionContable[] = array("documento_id" => $documentoId, "montoIgv" => $montoIgv);
            }

            // INVOICE COMERCIAL
            if (!ObjectUtil::isEmpty($montoFlete) && ($montoFlete * 1) > 0) {
                $montoSubTotal = round($montoSubTotal - ($montoFlete * 1), 6);
                $distribucionContable[] = array("documento_id" => $documentoId, "montoFlete" => $montoFlete);
            }

            if (!ObjectUtil::isEmpty($montoSeguro) && ($montoSeguro * 1) > 0) {
                $montoSubTotal = round($montoSubTotal - ($montoSeguro * 1), 6);
                $distribucionContable[] = array("documento_id" => $documentoId, "montoSeguro" => $montoSeguro);
            }
            
            if (!ObjectUtil::isEmpty($montoSubTotal) && ($montoSubTotal*1) >= 0) {
                $tipoCuenta = "montoSubTotal";
              //Se comento esta lína de codigo porque Adelina decidió hacer el asiento manual. 20 a la 28
//                if ($documento[0]['documento_tipo_id'] == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_INVOICE) {
//                    $tipoInvoice = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_LISTA, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor');
//                    if ($tipoInvoice == ContDistribucionContableNegocio::TIPO_DUA_ID_FOB) {
//                        $tipoCuenta = "montoSubTotalPendiente";
//                    }
//                }
                $distribucionContable[] = array("documento_id" => $documentoId, $tipoCuenta => ($montoAfecto + $montoNoAfecto));
            }

            if (!ObjectUtil::isEmpty($montoIcbp) && ($montoIcbp) > 0) {
                $distribucionContable[] = array("documento_id" => $documentoId, "montoIcbp" => $montoIcbp);
            }

            $banderaReversa = 0;
            if ($documento[0]['documento_tipo_id'] == "267") {
                $banderaReversa = 1;
            }

            $respuestaVoucherCompra = self::guardarContVoucher($documentoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_COMPRAS, $distribucionContable, $usuarioId, $banderaReversa);

            $respuestaActualizarCampoDocumento = self::guardarVoucherIdXDocumentoId($respuestaVoucherCompra[0]['vout_id'], $documentoId);
            if ($respuestaActualizarCampoDocumento[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registar el voucher : ' . $respuestaActualizarCampoDocumento[0]['vout_mensaje']);
            }

            return $respuestaVoucherCompra;
        }
    }

    public function registrarContVoucherRegistroVentas($documentoId, $usuarioId) {

        $documento = DocumentoNegocio::create()->obtenerXId($documentoId, NULL);

        $glosa = "VENTA DE MERCADERIA " . $documento[0]['serie_numero'];
        $banderaReversa = NULL;
        $banderaActivoFijo = ( $documento[0]['contador_activo_fijo'] * 1 > 0 ? TRUE : FALSE);
        $banderaOtrosBienes = ( $documento[0]['contador_otros_bienes'] * 1 > 0 ? TRUE : FALSE);

        if ($documento[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA) {
            $glosa = $documento[0]['comentario'] . " " . $documento[0]['serie_numero'];
            $banderaReversa = 1;
        }

        $camposDinamicos = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $tipoVentaGratuita = NULL;
        if ($documento[0]['movimiento_tipo_codigo'] == "18") {
            $tipoVentaGratuita = Util::filtrarArrayPorColumna($camposDinamicos, array("tipo", "codigo"), array(DocumentoTipoNegocio::DATO_LISTA, "12"));
            if (!ObjectUtil::isEmpty($tipoVentaGratuita) && ($tipoVentaGratuita[0]['valor_dato_listar'] == "0" || $tipoVentaGratuita[0]['valor_dato_listar'] == "1")) {
                $glosa = strtoupper($tipoVentaGratuita[0]['valor'] . $documento[0]['serie_numero']);
                $tipoVentaGratuita = $tipoVentaGratuita[0]['valor_dato_listar'];
            }
        }

//        $montoAfecto = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

        $montoIgv = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_IGV, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

        $montoTotal = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

        $montoAfecto = Util::redondearNumero($montoTotal - $montoIgv, 2);

        if ($tipoVentaGratuita == "0" && $montoTotal > 0) {
            throw new WarningException("Si la venta es gratuita y el tipo de venta es obsequio, su monto total debe ser cero.");
        }

        //Venta gratuita
        if ($tipoVentaGratuita == "1") {
            if ($banderaOtrosBienes) {
                $distribucionVentaGratuita = array(array("documento_id" => $documentoId, "montoOtrosIngresos" => $montoAfecto));
            } else {
                $distribucionVentaGratuita = array(array("documento_id" => $documentoId, "monto" => $montoTotal));
            }
            $respuestaVoucherVentaGratuita = self::guardarContVoucher($documentoId, ContVoucherNegocio::OPERACION_TIPO_ID_REVERSA_VENTA_GRATUITA, NULL, $documento[0]['periodo_id'], $documento[0]['moneda_id'], $glosa, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS, $distribucionVentaGratuita, $usuarioId);
        }

        $distribucionContable = array();
        if ($banderaActivoFijo) {
            $glosa = "VENTA DE ACTIVO DE FIJO " . $documento[0]['serie_numero'];
            $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]['movimiento_id']);
            $distribucionContable[] = array("documento_id" => $documentoId, "montoTotalActifoFijo" => $montoTotal);
            foreach ($documentoDetalle as $item) {
                if (ObjectUtil::isEmpty($item["plan_contable_venta"])) {
                    throw new WarningException("El producto " . $item["bien_descripcion"] . ", no tiene configurada la cuenta de contable de venta");
                }
                $montoItemAfecto = ($item["sub_total"] * 1);
                if ($item["incluye_igv"] == 1) {
                    $montoItemAfecto = Util::redondearNumero($montoItemAfecto / (1 + (Configuraciones::IGV_PORCENTAJE * 0.01)), 2);
                }
                $distribucionContable[] = array("montoAfectoActivoFijo" => $montoItemAfecto, "documento_id" => $documentoId, "plan_contable_codigo" => $item["plan_contable_venta"]);
            }
        } elseif ($banderaOtrosBienes) {
            $glosa = "VENTA OTROS BIENES " . $documento[0]['serie_numero'];
            $distribucionContable[] = array("documento_id" => $documentoId, "montoOtrosIngresosTotal" => $montoTotal);
            $distribucionContable[] = array("documento_id" => $documentoId, "montoOtrosIngresosAfecto" => $montoAfecto);
        } else {
            $distribucionContable[] = array("documento_id" => $documentoId, "montoTotal" => $montoTotal);
            $distribucionContable[] = array("documento_id" => $documentoId, "montoAfecto" => $montoAfecto);
        }
        $distribucionContable[] = array("documento_id" => $documentoId, "montoIgv" => $montoIgv);

        $respuestaVoucherVenta = self::guardarContVoucher($documentoId, $documento[0]['cont_operacion_tipo_id'], NULL, $documento[0]['periodo_id'], $documento[0]['moneda_id'], $glosa, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS, $distribucionContable, $usuarioId, $banderaReversa);
        $respuestaActualizarCampoDocumento = self::guardarVoucherIdXDocumentoId($respuestaVoucherVenta[0]['vout_id'], $documentoId);
        if ($respuestaActualizarCampoDocumento[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException('Error al intentar registar el voucher : ' . $respuestaActualizarCampoDocumento[0]['vout_mensaje']);
        }
        return $respuestaVoucherVenta;
    }

    public function registrarContVoucherXLibroDiario($voucherId, $contLibroId, $periodoId, $monedaId, $glosa, $distribucionContable, $usuarioId) {
        return self::guardarContVoucher(NULL, ContVoucherNegocio::OPERACION_TIPO_ID_LIBRO_DIARIO, $contLibroId, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_LIBRO_DIARIO, $distribucionContable, $usuarioId, NULL, $voucherId);
    }

    public function registrarContVoucherPlanilla($importacionArchivoId, $archivoTipo, $periodoId, $usuarioId) {
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("Imposible obtener la información del periodo.");
        }

        if (date('Y') == $dataPeriodo->dataPeriodo[0]['anio'] && (date('m') * 1) == ($dataPeriodo->dataPeriodo[0]['mes'] * 1)) {
            $fecha = date('Y-m-d');
        } else {
            $fecha = Util::obtenerUltimoDiaMes($dataPeriodo->dataPeriodo[0]['anio'], $dataPeriodo->dataPeriodo[0]['mes']);
        }

        $dataCuentasContable = PlanillaNegocio::create()->obtenerCuentasContableXPlaImportacionArhivoId($importacionArchivoId, $archivoTipo);
        $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_PLANILLA;
        $monedaId = ContVoucherNegocio::MONEDA_ID_SOLES;
        switch ($archivoTipo * 1) {
            case 1:
                $glosa = "Planilla " . $fecha;
                break;
            case 2:
                $glosa = "CTS " . $fecha;
                break;
            case 3:
                $glosa = "Gratificaciones " . $fecha;
                break;
        }

        $distribucionContable = array();
        foreach ($dataCuentasContable as $item) {
            if (in_array($item['plan_contable_codigo'], array("1412", "1411", "1419")) && $archivoTipo == 1) {
                switch ($item['plan_contable_codigo']) {
                    case "1412":
                        $parametroId = 36;
                        break;
                    case "1411":
                        $parametroId = 55;
                        break;
                    case "1419":
                        $parametroId = 56;
                        break;
                }

                $detalle = PlanillaNegocio::create()->obtenerValoresXParametroIdXPlaImportacionArhivoId($importacionArchivoId, $parametroId);
                foreach ($detalle as $itemDetalle) {
                    switch ($item['indicador_debe_haber'] * 1) {
                        case 1: //Debe                    
                            $distribucionContable[] = array('persona_id' => $itemDetalle['persona_id'], 'plan_contable_codigo' => $item['plan_contable_codigo'], 'centro_costo_codigo' => $item['centro_costo_codigo'], 'moneda_id' => $monedaId, 'fecha' => $fecha, 'montoDebe' => $itemDetalle['monto']);
                            break;
                        case 2: //Haber
                            $distribucionContable[] = array('persona_id' => $itemDetalle['persona_id'], 'plan_contable_codigo' => $item['plan_contable_codigo'], 'moneda_id' => $monedaId, 'fecha' => $fecha, 'montoHaber' => $itemDetalle['monto']);
                            break;
                    }
                }
            } else {
                switch ($item['indicador_debe_haber'] * 1) {
                    case 1: //Debe                    
                        $distribucionContable[] = array('plan_contable_codigo' => $item['plan_contable_codigo'], 'centro_costo_codigo' => $item['centro_costo_codigo'], 'moneda_id' => $monedaId, 'fecha' => $fecha, 'montoDebe' => $item['monto']);
                        break;
                    case 2: //Haber
                        $distribucionContable[] = array('plan_contable_codigo' => $item['plan_contable_codigo'], 'moneda_id' => $monedaId, 'fecha' => $fecha, 'montoHaber' => $item['monto']);
                        break;
                }
            }
        }
        return self::guardarContVoucher($importacionArchivoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_PLANILLA, $distribucionContable, $usuarioId);
    }

    public function registrarContVoucherPagos($pagoId, $usuarioId) {

        $dataDocumentoPago = PagoNegocio::create()->obtenerDocumentosPagoXPagoId($pagoId);

        $arrayCuentasBancoId = array();
        foreach ($dataDocumentoPago as $indexDocumentoPago => $documentoItem) {
            if (!ObjectUtil::isEmpty($documentoItem['cuenta_pago_id'])) {
                $arrayCuentasBancoId[] = $documentoItem['cuenta_pago_id'];
            } elseif (in_array((int) $documentoItem['identificador_negocio_pago'], array(DocumentoTipoNegocio::IN_NOTA_CREDITO_COMPRA, DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA))) {
                $dataDocumentoPago[$indexDocumentoPago]['cuenta_pago_id'] = 0;
                $arrayCuentasBancoId[] = 0;
            }
        }

        $arrayCuentasBancoId = array_unique($arrayCuentasBancoId);

        $contadorRegistros = 0;
        foreach ($arrayCuentasBancoId as $cuentaId) {
            
            // Información de la cuenta de banco
            $dataCuenta = CuentaNegocio::create()->obtenerCuentaXId($cuentaId);
            $planContableCodigoPago = $dataCuenta[0]['plan_contable_codigo'];
            $contLibroId = $dataCuenta[0]['cont_libro_id'];

            // Obtenemos separamos por los documenos pagados
            $arrayDocumentoPagoId = array();
            $arrayFiltradoXLibroId = Util::filtrarArrayPorColumna($dataDocumentoPago, 'cuenta_pago_id', $cuentaId);
            foreach ($arrayFiltradoXLibroId as $item) {
                if (!in_array(($item['identificador_negocio_pago'] * 1), array(DocumentoTipoNegocio::IN_ANTICIPO_PROVEEDOR, DocumentoTipoNegocio::IN_CERTIFICADOR_RETENCION, DocumentoTipoNegocio::IN_FINANCIAMIENTO_COMPRA, DocumentoTipoNegocio::IN_DIFERENCIA_MONTO))) {
                    $arrayDocumentoPagoId[] = $item['documento_pago'];
                }
            }

            $arrayDocumentoPagoId = array_unique($arrayDocumentoPagoId);
            foreach ($arrayDocumentoPagoId as $documentoPagoId) {
                
                if (in_array($documentoPagoId * 1, $this->arrayDocumentoPagoExcluido)) {
                    continue;
                }
                //Hacemos un asiento por cada transacción realizada, por lo tanto lo hacemos por transferencia -> deposito o salida.
                $montoTotalPagado = 0;
                $montoDiferenciaTipoCambio = 0;
                $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_SALIDA;
                $glosa = 'Pago de documentos';
                $distribucionContable = array();
                $arrayFiltradoXDocumentoPago = Util::filtrarArrayPorColumna($dataDocumentoPago, 'documento_pago', $documentoPagoId);

                foreach ($arrayFiltradoXDocumentoPago as $documentoItem) {
                    $personaId = $documentoItem['persona_id'];
                    $monedaId = $documentoItem['moneda_documento_pago'];
                    $periodoId = $documentoItem['periodo_id_pago'];
                    $fechaEmision = $documentoItem['fecha_emision'];
                    $fechaPago = $documentoItem['fecha_emision_pago'];
                    $tipoCambioPago = $documentoItem['tipo_cambio_documento_pago'] * 1;
                    $tipoCambioDocumento = $documentoItem['tipo_cambio_documento'] * 1;

                    $importe = $documentoItem['importe_pagado'] * 1;

                    $documentoId = $documentoItem['documento_id'];
                    $importeDocumento = $documentoItem['importe_pago_documento'] * 1;
                    $monedaIdDocumento = $documentoItem['moneda_id'];
                    $banderaPagoDetraccion = false;

                    $banderaActivoFijo = ($documentoItem['contador_activo_fijo'] * 1 > 0 ? TRUE : FALSE);

                    if ($documentoItem['identificador_negocio_pago'] == DocumentoTipoNegocio::IN_CERTIFICADOR_DETRACCION) {
                        if ($monedaId != self::MONEDA_ID_SOLES) {
                            throw new WarningException('El comprobante de detracción debe ser en soles.');
                        }

                        $montoDetraccionSoles = Util::redondearNumero(($documentoItem['monto_detraccion'] * 1) * ($monedaIdDocumento == self::MONEDA_ID_DOLARES ? $tipoCambioDocumento * 1 : 1), 2);
                        if (ObjectUtil::isEmpty($montoDetraccionSoles) && $montoDetraccionSoles == 0) {
                            throw new WarningException('Este documento no tiene detracción.');
                        }

                        $importe = $documentoItem['total_documento_pago'] * 1;
                        if ($monedaIdDocumento == self::MONEDA_ID_DOLARES) {
                            $montoDiferenciaRedondeo = Util::redondearNumero($montoDetraccionSoles - $importe, 2);
                            $banderaPagoDetraccion = true;
                            if (abs($montoDiferenciaRedondeo) >= 1) {
                                throw new WarningException('El monto detracción en soles para a la fecha ' . $fechaEmision . ' debe ser ' . Util::redondearNumero($montoDetraccionSoles, 0));
                            } elseif (Util::redondearNumero($montoDiferenciaRedondeo, 2) > 0) {
                                $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoId, $documentoItem['documento_pago'], abs($montoDiferenciaRedondeo), ContVoucherNegocio::MONEDA_ID_SOLES, 1, $usuarioId, $tipoCambioDocumento, $cuentaId, NULL);
                                $distribucionContable[] = array('persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoGananciaRedondeo' => Util::redondearNumero($montoDiferenciaRedondeo, 2));
                            } elseif (Util::redondearNumero($montoDiferenciaRedondeo, 2) < 0) {
                                $distribucionContable[] = array('centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoPerdidaRedondeo' => abs(Util::redondearNumero($montoDiferenciaRedondeo, 2)));
                                $resPago = Pago::create()->insertarDocumentoPago($pagoId, $documentoId, $documentoItem['documento_pago'], abs($montoDiferenciaRedondeo), ContVoucherNegocio::MONEDA_ID_SOLES, 1, $usuarioId, $tipoCambioDocumento, $cuentaId, NULL);
                            }
                        }
//                    }elseif(){
                    }

                    if (!$banderaPagoDetraccion) {
                        switch (true) {
                            case $monedaIdDocumento == self::MONEDA_ID_DOLARES && $monedaId == self::MONEDA_ID_DOLARES:
                                $montoDiferenciaTipoCambio += (($importe * $tipoCambioDocumento ) - ($importe * $tipoCambioPago));
                                break;
                            case $monedaIdDocumento == self::MONEDA_ID_DOLARES && $monedaId == self::MONEDA_ID_SOLES:
                                $montoDiferenciaTipoCambio += ($importeDocumento * $tipoCambioDocumento ) - ($importe);
                                break;
                            // EN EL RESTO DE CASOS NO HAY DIFERENCIA DE CAMBIO
                        }
                    }

                    $documentoArray = array("documento_id" => $documentoId, 'fecha' => $fechaEmision, 'moneda_id' => $monedaIdDocumento, 'tipo_cambio' => $tipoCambioDocumento);

                    switch ($documentoItem['identificador_negocio'] * 1) {
                        //Pago de factura de venta
                        case DocumentoTipoNegocio::IN_BOLETA_VENTA:
                        case DocumentoTipoNegocio::IN_FACTURA_VENTA:
                        case DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA:
                            $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA;
                            $glosa = 'Cobranza de documentos';
                            IF ($banderaActivoFijo) {
                                $documentoArray['montoCobranzaActivoFijo'] = $importeDocumento;
                            } ELSE {
                                $documentoArray['montoCobranza'] = $importeDocumento;
                            }
                            break;
                        //Anticipo a proveedores
                        case DocumentoTipoNegocio::IN_ANTICIPO_PROVEEDOR:
                            $documentoArray['montoAnticipo'] = $importeDocumento;
                            break;
                        //Financiamiento de compras
                        case DocumentoTipoNegocio::IN_FINANCIAMIENTO_COMPRA:
                            $documentoArray['montoFinanciamiento'] = $importeDocumento;
                            break;
                        //PARA EL EAR Deseembolso o Reembolso
                        case DocumentoTipoNegocio::IN_EAR_DESEMBOLSO :
                        case DocumentoTipoNegocio::IN_EAR_REEMBOLSO :
                            $dataPlanContablePersona = PersonaNegocio::create()->obtenerCuentaContableXPersonaId($personaId);
                            //Si la persona no tiene cuenta contable, se le asigna como tercero.
                            $planContableCodigo = ($dataPlanContablePersona[0]['plan_contable_codigo'] ? $dataPlanContablePersona[0]['plan_contable_codigo'] : PlanContable::PLAN_CONTABLE_CODIGO_EAR_TERCERO);
                            $documentoArray['plan_contable_codigo'] = $planContableCodigo;
                            $documentoArray['montoEAR'] = $importeDocumento;
                            break;
                        case DocumentoTipoNegocio::IN_GARANTIA:
                            $documentoArray['plan_contable_codigo'] = PlanContable::PLAN_DEPOSITO_GARANTIA;
                            $documentoArray['montoEAR'] = $importeDocumento;
                            break;
                        //Facturas,boletas,RH,Recibos por pagar
                        default:
                            //SI ES RH, SE USA OTRA CUENTA                        
                            if ($documentoItem['documento_tipo_codigo'] == "02") {
                                $documentoArray['plan_contable_codigo'] = PlanContable::PLAN_CONTABLE_CODIGO_RH_EMITIDAS;
                            } else {
                                $documentoArray['plan_contable_codigo'] = PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS;
                            }

                            //Cuando se paga la detracción de un documento en dólares, por la conversión al tipo de cambio se hace un ajuste en redondeo.
                            if ($banderaPagoDetraccion && $monedaIdDocumento == self::MONEDA_ID_DOLARES) {
                                $documentoArray['montoCuentasPorPagar'] = $documentoItem['monto_detraccion'] * 1;
                            } else {
                                $documentoArray['montoCuentasPorPagar'] = $importeDocumento;
                            }
                            break;
                    }

                    $documentoPagoIdContabilizable = NULL;
                    if ($cuentaId == 0) {
                        $glosa = "Por aplicación de la nota de crédito " . $documentoItem['serie_numero_documento_pago'] . " al documento " . $documentoItem['serie_numero_documento'];
                        $planContableCodigoPago = $documentoArray['plan_contable_codigo'];
                        if ($contOperacionTipoId == ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA) {
                            if ($banderaActivoFijo) {
                                $planContableCodigoPago = PlanContable::PLAN_CONTABLE_CODIGO_ACTIVO_FIJO_COBRANZA;
                            } else {
                                $planContableCodigoPago = PlanContable::PLAN_CONTABLE_CODIGO_VENTA_MERCADERIA;
                            }
                        }
                        $documentoPagoIdContabilizable = $documentoPagoId;
                        $contLibroId = ContLibro::CONT_LIBRO_DIARIO_ID;
                    }
                    $distribucionContable[] = $documentoArray;
                    //Salida o entrada de dinero a las cuentas.
                    $distribucionContable[] = array('plan_contable_codigo' => $planContableCodigoPago, 'documento_id' => $documentoPagoIdContabilizable, 'persona_id' => $personaId, 'fecha' => $fechaPago, 'tipo_cambio' => $tipoCambioPago, 'moneda_id' => $monedaId, 'montoTotal' => $importe);
                    $contadorRegistros++;
                }

                //En el caso de pagos de dólares, se debe hacer asiento por diferencia de cambio.
                if (Util::redondearNumero($montoDiferenciaTipoCambio, 2) != 0) {
                    switch (true) {
                        case $contOperacionTipoId == ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_SALIDA && Util::redondearNumero($montoDiferenciaTipoCambio, 2) > 0:
                            $distribucionContable[] = array('persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoGananciaDiferenciaTC' => $montoDiferenciaTipoCambio);
                            break;
                        case $contOperacionTipoId == ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_SALIDA && Util::redondearNumero($montoDiferenciaTipoCambio, 2) < 0:
                            $distribucionContable[] = array('centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoPerdidaDiferenciaTC' => $montoDiferenciaTipoCambio * -1);
                            break;
                        case $contOperacionTipoId == ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA && Util::redondearNumero($montoDiferenciaTipoCambio, 2) > 0:
                            $distribucionContable[] = array('centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoPerdidaDiferenciaTC' => $montoDiferenciaTipoCambio);
                            break;
                        case $contOperacionTipoId == ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA && Util::redondearNumero($montoDiferenciaTipoCambio, 2) < 0:
                            $distribucionContable[] = array('persona_id' => $personaId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoGananciaDiferenciaTC' => $montoDiferenciaTipoCambio * -1);
                            break;
                    }
                }
                $respuestaRegistrarVoucherPago = self::guardarContVoucher($pagoId, $contOperacionTipoId, $contLibroId, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS, $distribucionContable, $usuarioId);
            }
        }

        //En caso de que se pague documentos con comprobante de retención.
        $arrayFiltradoXRetencionId = Util::filtrarArrayPorColumna($dataDocumentoPago, 'identificador_negocio_pago', DocumentoTipoNegocio::IN_CERTIFICADOR_RETENCION);
        if (!ObjectUtil::isEmpty($arrayFiltradoXRetencionId) && count($arrayFiltradoXRetencionId) > 0) {
            // Obtenemos separamos por los documetos pagados
            $arrayDocumentoPagoId = array();
            foreach ($arrayFiltradoXRetencionId as $item) {
                $arrayDocumentoPagoId[] = $item['documento_pago'];
            }

            $arrayDocumentoPagoId = array_unique($arrayDocumentoPagoId);
            foreach ($arrayDocumentoPagoId as $documentoPagoId) {
                //Hacemos un asiento por cada transacción realizada, por lo tanto lo hacemos por pago
                $arrayFiltradoXDocumentoPago = Util::filtrarArrayPorColumna($dataDocumentoPago, 'documento_pago', $documentoPagoId);
                foreach ($arrayFiltradoXDocumentoPago as $documentoItem) {
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_RETENCION;
                    $glosa = 'Pago de documentos con retención';

                    $personaId = $documentoItem['persona_id'];

                    $monedaId = $documentoItem['moneda_documento_pago'];
                    $periodoId = $documentoItem['periodo_id_pago'];

                    $fechaPago = $documentoItem['fecha_emision_pago'];
                    $tipoCambioPago = ($documentoItem['tipo_cambio_documento_pago'] * 1);


                    $importe = ($documentoItem['importe_pagado'] * 1);

                    $documentoId = $documentoItem['documento_id'];
                    $fechaEmision = $documentoItem['fecha_emision'];
                    $monedaIdDocumento = $documentoItem['moneda_id'];
                    $tipoCambioDocumento = ($documentoItem['tipo_cambio_documento'] * 1);
                    $importeDocumento = $documentoItem['importe_pago_documento'] * 1;


                    $distribucionContable = array();
                    $montoDiferenciaTipoCambio = 0;

                    // SOLO PERMITE REGISTRAR COMPROBANTES DE RETENCIÓN POSITIVOS.
                    if ($monedaId != self::MONEDA_ID_SOLES) {
                        throw new WarningException('El comprobante de retención debe ser en soles.');
                    }

                    $montoRetencion = Util::redondearNumero($documentoItem['monto_retencion'] * 1, 6);
                    $montoRetencionSoles = ($montoRetencion * $tipoCambioDocumento);

                    if ($monedaIdDocumento == self::MONEDA_ID_DOLARES) {
                        $montoDiferenciaTipoCambio = Util::redondearNumero($montoRetencionSoles - $importe, 2);
                    }

                    if (!ObjectUtil::isEmpty($montoRetencion) && $montoRetencion > 0) {
                        if (Util::redondearNumero($montoRetencion, 2) != Util::redondearNumero($importeDocumento, 2)) {
                            throw new WarningException('El monto retenido en soles para la fecha de pago debe ser ' . Util::redondearNumero($montoRetencion * ($monedaIdDocumento == self::MONEDA_ID_DOLARES ? $tipoCambioPago * 1 : 1), 2));
                        }
                    } else {
                        throw new WarningException('Este documento no tiene retención.');
                    }

                    //Detalle del documento pagado
                    $distribucionContable[] = array("documento_id" => $documentoId, "fecha" => $fechaEmision, 'moneda_id' => $monedaIdDocumento, "tipo_cambio" => $tipoCambioDocumento, "montoPorCobrar" => $montoRetencion);

                    //Detalle de la retención 
                    $distribucionContable[] = array('documento_id' => $documentoPagoId, 'fecha' => $fechaPago, 'moneda_id' => $monedaId, 'tipoCambio' => $tipoCambioPago, 'montoTotal' => $importe);

                    //Si existe diferencia de tipo de cambio para el anticipo.
                    if (Util::redondearNumero(abs($montoDiferenciaTipoCambio), 2) > 0) {
                        //Diferencia perdida 
                        if (Util::redondearNumero($montoDiferenciaTipoCambio, 2) > 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'fecha' => $fechaPago, 'montoPerdidaDiferenciaTC' => $montoDiferenciaTipoCambio);
                            //Diferencia ganancia    
                        } elseif (Util::redondearNumero($montoDiferenciaTipoCambio, 2) < 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'fecha' => $fechaPago, 'montoGananciaDiferenciaTC' => abs($montoDiferenciaTipoCambio));
                        }
                    }

                    $respuestaRegistrarVoucherPago = self::guardarContVoucher($pagoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS, $distribucionContable, $usuarioId);
                    $contadorRegistros++;
                }
            }
        }

        //En caso de que se pague documentos con anticipos.
        $arrayFiltradoXAnticiposId = Util::filtrarArrayPorColumna($dataDocumentoPago, 'identificador_negocio_pago', DocumentoTipoNegocio::IN_ANTICIPO_PROVEEDOR);
        if (!ObjectUtil::isEmpty($arrayFiltradoXAnticiposId) && count($arrayFiltradoXAnticiposId) > 0) {

            // Obtenemos separamos por los documetos pagados
            $arrayDocumentoPagoId = Util::obtenerArrayUnicoXNombreCampo($arrayFiltradoXAnticiposId, "documento_pago");

            foreach ($arrayDocumentoPagoId as $documentoPagoId) {
                //Hacemos un asiento por cada transacción realizada, por lo tanto lo hacemos por pago
                $arrayFiltradoXDocumentoPago = Util::filtrarArrayPorColumna($dataDocumentoPago, 'documento_pago', $documentoPagoId);
                foreach ($arrayFiltradoXDocumentoPago as $documentoItem) {
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_PAGO_ADELANTO;
                    $glosa = 'Pago de documentos con anticipos';
                    $personaId = $documentoItem['persona_id'];
                    $monedaId = $documentoItem['moneda_documento_pago'];
                    $periodoId = $documentoItem['periodo_id_documento'];
                    $fechaPago = $documentoItem['fecha_emision_pago'];

                    // Usamos la fecha de cuando se aplica el adelanto.
                    $fechaEmision = $documentoItem['fecha_emision'];
                    $tipoCambio = $documentoItem['tipo_cambio_documento'];
                    $tipoCambioPago = $documentoItem['tipo_cambio_documento_pago'];
                    $importe = $documentoItem['importe_pagado'] * 1;

                    $montoDiferenciaTipoCambio = 0;
                    if ($monedaId == self::MONEDA_ID_DOLARES) {
                        $montoDiferenciaTipoCambio = Util::redondearNumero(Util::redondearNumero($importe * $documentoItem['tipo_cambio_documento_pago'] * 1, 2) - Util::redondearNumero($importe * $documentoItem['tipo_cambio_documento'] * 1, 2), 6);
                    }
                    $distribucionContable = array();
                    //Detalle del documento pagado
                    $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS; //por defecto la 4212
                    if ($documentoItem['documento_tipo_codigo'] == "02") {
                        $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_RH_EMITIDAS;
                    }

                    $distribucionContable[] = array("documento_id" => $documentoItem['documento_id'], "plan_contable_codigo" => $planContableCodigo, "fecha" => $fechaEmision, 'moneda_id' => $monedaId, "tipo_cambio" => $tipoCambio, "montoCuentasPorPagar" => $importe);
                    //Detalle del adelanto 
                    $distribucionContable[] = array('documento_id' => $documentoPagoId, 'fecha' => $fechaPago, 'moneda_id' => $monedaId, 'tipo_cambio' => $tipoCambioPago, 'montoTotalAnticipo' => $importe);
                    //Si existe diferencia de tipo de cambio para el anticipo.
                    if (Util::redondearNumero(abs($montoDiferenciaTipoCambio), 2) > 0) {
                        //Diferencia perdida 
                        if (Util::redondearNumero($montoDiferenciaTipoCambio, 2) > 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'fecha' => $fechaPago, 'montoPerdidaDiferenciaTC' => $montoDiferenciaTipoCambio);
                            //Diferencia ganancia    
                        } elseif (Util::redondearNumero($montoDiferenciaTipoCambio, 2) < 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'fecha' => $fechaPago, 'montoGananciaDiferenciaTC' => abs($montoDiferenciaTipoCambio));
                        }
                    }
                    $respuestaRegistrarVoucherPago = self::guardarContVoucher($pagoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS, $distribucionContable, $usuarioId);
                    $contadorRegistros++;
                }
            }
        }


        //En caso de que se pague documentos con financiamiento.
        $arrayFiltradoXFinanciamientoId = Util::filtrarArrayPorColumna($dataDocumentoPago, 'identificador_negocio_pago', DocumentoTipoNegocio::IN_FINANCIAMIENTO_COMPRA);
        if (!ObjectUtil::isEmpty($arrayFiltradoXFinanciamientoId) && count($arrayFiltradoXFinanciamientoId) > 0) {
            // Obtenemos separamos por los documetos pagados
            $arrayDocumentoPagoId = Util::obtenerArrayUnicoXNombreCampo($arrayFiltradoXFinanciamientoId, "documento_pago");
            foreach ($arrayDocumentoPagoId as $documentoPagoId) {
                //Hacemos un asiento por cada transacción realizada, por lo tanto lo hacemos por pago
                $arrayFiltradoXDocumentoPago = Util::filtrarArrayPorColumna($dataDocumentoPago, 'documento_pago', $documentoPagoId);
                foreach ($arrayFiltradoXDocumentoPago as $documentoItem) {
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_PAGO_FINANCIAMIENTO;
                    $glosa = 'Pago de documentos con financiamiento';
                    $personaId = $documentoItem['persona_id'];
                    $monedaId = $documentoItem['moneda_documento_pago'];
                    $periodoId = $documentoItem['periodo_id_pago'];

                    // Usamos la fecha de cuando se aplica el financiamiento.
                    $fechaEmision = $documentoItem['fecha_emision'];
                    $tipoCambio = $documentoItem['tipo_cambio_documento'];
                    $tipoCambioPago = $documentoItem['tipo_cambio_documento_pago'];
                    $importe = $documentoItem['importe_pagado'] * 1;
                    $fechaPago = $documentoItem['fecha_emision_pago'];
                    $montoDiferenciaTipoCambio = 0;
                    if ($monedaId == self::MONEDA_ID_DOLARES) {
                        $montoDiferenciaTipoCambio = Util::redondearNumero(Util::redondearNumero($importe * $documentoItem['tipo_cambio_documento_pago'] * 1, 2) - Util::redondearNumero($importe * $documentoItem['tipo_cambio_documento'] * 1, 2), 6);
                    }
                    $distribucionContable = array();
                    //Detalle del documento pagado
                    $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS; //por defecto la 4212
                    if ($documentoItem['documento_tipo_codigo'] == "02") {
                        $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_RH_EMITIDAS;
                    }

                    $distribucionContable[] = array("documento_id" => $documentoItem['documento_id'], "plan_contable_codigo" => $planContableCodigo, "fecha" => $fechaEmision, 'moneda_id' => $monedaId, "tipo_cambio" => $tipoCambio, "montoCuentasPorPagar" => $importe);
                    $distribucionContable[] = array('documento_id' => $documentoPagoId, 'fecha' => $fechaPago, 'moneda_id' => $monedaId, 'tipo_cambio' => $tipoCambioPago, 'montoTotalFinanciamiento' => $importe);
                    //Si existe diferencia de tipo de cambio para el financimiento.
                    if (Util::redondearNumero(abs($montoDiferenciaTipoCambio), 2) > 0) {
                        //Diferencia perdida 
                        if (Util::redondearNumero($montoDiferenciaTipoCambio, 2) > 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'fecha' => $fechaPago, 'montoPerdidaDiferenciaTC' => $montoDiferenciaTipoCambio);
                            //Diferencia ganancia    
                        } elseif (Util::redondearNumero($montoDiferenciaTipoCambio, 2) < 0) {
                            $distribucionContable[] = array('documento_id' => $documentoPagoId, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'fecha' => $fechaPago, 'montoGananciaDiferenciaTC' => abs($montoDiferenciaTipoCambio));
                        }
                    }
                    $respuestaRegistrarVoucherPago = self::guardarContVoucher($pagoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS, $distribucionContable, $usuarioId);
                    $contadorRegistros++;
                }
            }
        }


        //En caso de que diferencia de importes
        $arrayFiltradoXDiferenciaMontoId = Util::filtrarArrayPorColumna($dataDocumentoPago, 'identificador_negocio_pago', DocumentoTipoNegocio::IN_DIFERENCIA_MONTO);
        if (!ObjectUtil::isEmpty($arrayFiltradoXDiferenciaMontoId) && count($arrayFiltradoXDiferenciaMontoId) > 0) {
            // Obtenemos separamos por los documetos pagados
            $arrayDocumentoPagoId = Util::obtenerArrayUnicoXNombreCampo($arrayFiltradoXDiferenciaMontoId, "documento_pago");
            foreach ($arrayDocumentoPagoId as $documentoPagoId) {
                //Hacemos un asiento por cada transacción realizada, por lo tanto lo hacemos por pago
                $arrayFiltradoXDocumentoPago = Util::filtrarArrayPorColumna($dataDocumentoPago, 'documento_pago', $documentoPagoId);
                foreach ($arrayFiltradoXDocumentoPago as $documentoItem) {
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_LIBRO_DIARIO;
                    $glosa = 'Ajuste por redondeo.';
                    $personaId = $documentoItem['persona_id'];
                    $monedaId = $documentoItem['moneda_documento_pago'];
                    $periodoId = $documentoItem['periodo_id_pago'];

                    // Usamos la fecha de cuando se aplica el financiamiento.
                    $fechaEmision = $documentoItem['fecha_emision'];
                    $tipoCambio = $documentoItem['tipo_cambio_documento'];
                    $tipoCambioPago = $documentoItem['tipo_cambio_documento_pago'];
                    $importe = $documentoItem['importe_pagado'] * 1;
                    $fechaPago = $documentoItem['fecha_emision_pago'];

                    // SOLO PERMITE REGISTRAR COMPROBANTES DE RETENCIÓN POSITIVOS.
                    if ($monedaId != self::MONEDA_ID_SOLES) {
                        throw new WarningException('El documento de diferencia de importe solo puede ser en soles.');
                    }

                    $distribucionContable = array();
                    //Detalle del documento pagado
                    $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS; //por defecto la 4212
                    if ($documentoItem['documento_tipo_codigo'] == "02") {
                        $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_RH_EMITIDAS;
                    }

                    $planContableCodigoRedondeo = PlanContable::PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_GANANCIA;
                    $centroCostoCodigo = NULL;
                    if ($importe < 0) {
                        $planContableCodigoRedondeo = PlanContable::PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_PERDIDA;
                        $centroCostoCodigo = CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS;
                    }


                    $distribucionContable[] = array("documento_id" => $documentoItem['documento_id'], "plan_contable_codigo" => $planContableCodigo, "fecha" => $fechaEmision, 'moneda_id' => $monedaId, "tipo_cambio" => $tipoCambio, "montoDebe" => abs($importe));
                    $distribucionContable[] = array('documento_id' => $documentoPagoId, "plan_contable_codigo" => $planContableCodigoRedondeo, "centro_costo_codigo" => $centroCostoCodigo, 'fecha' => $fechaPago, 'moneda_id' => $monedaId, 'tipo_cambio' => $tipoCambioPago, 'montoHaber' => abs($importe));

                    $respuestaRegistrarVoucherPago = self::guardarContVoucher($pagoId, $contOperacionTipoId, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_CAJAYBANCOS, $distribucionContable, $usuarioId);
                    $contadorRegistros++;
                }
            }
        }


        return $respuestaRegistrarVoucherPago;
    }

    public function registrarContVoucherCostoVenta($periodoId, $costoVenta, $usuarioId) {
        $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_COSTO_VENTA;
        $identificadorNegocio = ContVoucherNegocio::IDENTIFICADOR_COSTO_VENTA;

        $dataAsientoRegistrado = ContVoucherNegocio::create()->obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($periodoId, $identificadorNegocio);
        if (!ObjectUtil::isEmpty($dataAsientoRegistrado)) {
            $contVoucherId = $dataAsientoRegistrado[0]['id'];
        }

        $dataPeriodo = Periodo::create()->obtenerPeriodoXid($periodoId);
        if (date('Y') == $dataPeriodo[0]['anio'] && (date('m') * 1) == ($dataPeriodo[0]['mes'] * 1)) {
            $fecha = date('Y-m-d');
        } else {
            $fecha = Util::obtenerUltimoDiaMes($dataPeriodo[0]['anio'], $dataPeriodo[0]['mes']);
        }

        $monedaId = ContVoucherNegocio::MONEDA_ID_SOLES;
        $distribucionContable = array();
        $distribucionContable[] = array('moneda_id' => $monedaId, 'fecha' => $fecha, 'monto' => $costoVenta);
        $glosa = "Costos de venta " . $dataPeriodo[0]['anio'] . $dataPeriodo[0]['mes'];

        return ContVoucherNegocio::create()->guardarContVoucher($periodoId, $contOperacionTipoId, ContLibroNegocio::LIBRO_INVENTARIO_ID, $periodoId, $monedaId, $glosa, $identificadorNegocio, $distribucionContable, $usuarioId, NULL, $contVoucherId);
    }

    public function guardarContVoucher($identificadorId = NULL, $contOperacionTipoId, $contLibroId = NULL, $periodoId, $monedaId, $glosa, $identificadorNegocio, $distribucionContable, $usuarioId, $banderaReversa = NULL, $voucherId = NULL) {

        if (ObjectUtil::isEmpty($glosa)) {
            $glosa = "";
        }

        // $identificadorId => Se refiere al documento, pago, cobranzas o ear al cual se relacionan los voucher's que son guardados en un log => cont_voucher_relacion.
        // REGISTRAMOS EL VOUCHER     
        if (!ObjectUtil::isEmpty($voucherId)) {
            $dataVoucher = self::obtenerContVoucherXId($voucherId);
            if (ObjectUtil::isEmpty($dataVoucher)) {
                throw new WarningException('Error al intentar actualizar el voucher : error al intentar obtener la información del asiento.');
            }
            if ($dataVoucher[0]['periodo_id'] != $periodoId || $dataVoucher[0]['cont_libro_id'] != $contLibroId) {
                throw new WarningException('Error al intentar actualizar el voucher : el voucher debe ser anulado porque se debe generar un cuo nuevo para los cambios solicitados.');
            } else {
                $respuestaRegistrarVoucher = self::actualizarContVoucherXId($voucherId, $monedaId, $glosa);
                $respuestaAnularDetalle = self::anularDetalleXVoucherId($voucherId);
            }
        } else {
            $respuestaRegistrarVoucher = ContVoucher::create()->guardarContVoucher($contOperacionTipoId, $contLibroId, $periodoId, $monedaId, $glosa, $usuarioId);
            if ($respuestaRegistrarVoucher[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registar el voucher : ' . $respuestaRegistrarVoucher[0]['vout_mensaje']);
            }
            $voucherId = $respuestaRegistrarVoucher[0]['vout_id'];

            $respuestaVoucherRelacion = ContVoucher::create()->guardarVoucherRelacion($voucherId, $identificadorId, $identificadorNegocio, $usuarioId);
            if ($respuestaVoucherRelacion[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registar el voucher relación : ' . $respuestaVoucherRelacion[0]['vout_mensaje']);
            }
        }

        $dataOperacionTipoDetalle = ContOperacionTipoDetalleNegocio::create()->obtenerContOperacionTipoDetalleXContOperacionTipoId($contOperacionTipoId);

        if (ObjectUtil::isEmpty($dataOperacionTipoDetalle)) {
            throw new WarningException('Error al intentar registar el voucher : No se encontró la estructura para registrar el voucher.');
        }

        foreach ($dataOperacionTipoDetalle as $operacionTipoDetalle) {
            foreach ($distribucionContable as $index => $item) {
                $montoCuenta = false;
                eval("\$montoCuenta = " . str_replace("[", "\$this->validarMonto(\$item['", str_replace("]", "'])", $operacionTipoDetalle['formula'])) . ";");
                if ($montoCuenta !== false) {
                    $planContableCodigo = (!ObjectUtil::isEmpty($item['plan_contable_codigo']) ? $item['plan_contable_codigo'] : $operacionTipoDetalle['plan_contable_codigo']);
                    $centroCostoCodigo = (!ObjectUtil::isEmpty($item['centro_costo_codigo']) ? $item['centro_costo_codigo'] : $operacionTipoDetalle['centro_costo_codigo']);
                    $banderaReversaLinea = $banderaReversa;
                    if ($banderaReversaLinea != "1" && $item['bandera_reversa'] == "1") {
                        $banderaReversaLinea = $item['bandera_reversa'];
                    }
                    $respuestaRegistrarVoucherDetalle = ContVoucher::create()->guardarContVoucherDetalle($voucherId, $operacionTipoDetalle['id'], $planContableCodigo, $centroCostoCodigo, Util::redondearNumero($montoCuenta, 6), $item['documento_id'], $item['persona_id'], $item['moneda_id'], $item['fecha'], $item['tipo_cambio'], $usuarioId, $banderaReversaLinea, $item['movimiento_monto_soles']);
                    if ($respuestaRegistrarVoucherDetalle[0]['vout_exito'] != Util::VOUT_EXITO) {
                        throw new WarningException('Error al intentar registrar el voucher detalle : ' . $respuestaRegistrarVoucherDetalle[0]['vout_mensaje']);
                    }
                }
            }
        }
        $dataVoucherSuma = ContVoucher::create()->obtenerContVoucherDetalleMontoTotalesXVoucherId($voucherId);
        if (ObjectUtil::isEmpty($dataVoucherSuma[0]['suma_debe_dolares']) || ObjectUtil::isEmpty($dataVoucherSuma[0]['suma_haber_dolares']) || ObjectUtil::isEmpty($dataVoucherSuma[0]['suma_debe_soles']) || ObjectUtil::isEmpty($dataVoucherSuma[0]['suma_haber_soles'])) {
            throw new WarningException('Error al intentar registar el voucher detalle : La suma total debe ser mayor que cero.');
        }

        $variacion = ($this->VARIACION_CERO ? 0 : 0.02);
        if (abs(Util::redondearNumero($dataVoucherSuma[0]['suma_debe_soles'], 2) - Util::redondearNumero($dataVoucherSuma[0]['suma_haber_soles'], 2)) > $variacion) {
            throw new WarningException('Error al intentar registar el voucher : El monto del haber y deber tienen que ser iguales.');
        } else {
            $respuestaRedondeo = self::agregarRedondeoAsientoContable($voucherId, $usuarioId);
        }


        return $respuestaRegistrarVoucher;
    }

    function agregarRedondeoAsientoContable($voucherId, $usuarioId) {
        //Verificamos si el asiento requiere ajuste por redondeo.
        $dataVoucherMontoRedondeado = ContVoucher::create()->obtenerMontoTotalRedondeado($voucherId);
        $diferencia = Util::redondearNumero(($dataVoucherMontoRedondeado[0]['monto_debe'] * 1) - ($dataVoucherMontoRedondeado[0]['monto_haber'] * 1), 2);
        if (abs($diferencia) > 0.02) {
            throw new WarningException('Error al intentar registar el voucher detalle: Existe una diferencia que excede al máximo permitido(0.02), al intentar redondear los monto en el detalle del asiento.');
        } elseif (abs($diferencia) > 0) {
            $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_GANANCIA;
            $centroCostoCodigo = NULL;
            $contOperacionDetalleId = 87;
            $fechaContabilizacion = substr(ContVoucherDetalle::create()->obtenerUltimaFechaContabilizacion($voucherId)[0]['fecha_contabilizacion'], 0, 10);
            if ($diferencia < 0) {
                $contOperacionDetalleId = 88;
                $planContableCodigo = PlanContable::PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_PERDIDA;
                $centroCostoCodigo = CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS;
            }
            $respuestaRegistrarVoucherDetalle = ContVoucher::create()->guardarContVoucherDetalle($voucherId, $contOperacionDetalleId, $planContableCodigo, $centroCostoCodigo, abs($diferencia), NULL, NULL, self::MONEDA_ID_SOLES, $fechaContabilizacion, NULL, $usuarioId, NULL);
            if ($respuestaRegistrarVoucherDetalle[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registrar el voucher detalle : ' . $respuestaRegistrarVoucherDetalle[0]['vout_mensaje']);
            }
        }
        return $respuestaRegistrarVoucherDetalle;
    }

    public function validarMonto($valor) {
        return (!ObjectUtil::isEmpty($valor) ? $valor * 1 : false);
    }

}
