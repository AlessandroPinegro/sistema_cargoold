<?php

require_once __DIR__ . '/../../modelo/contabilidad/LibroDiario.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContLibroNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/LibroMayorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class LibroDiarioNegocio extends ModeloNegocioBase {

    const CONSULTA_PERSONA = 1;
    const CONSULTA_PERSONA_DOCUMENTO = 2;
    const CONSULTA_SOLO_SOLES = 0;
    const CONSULTA_AMBAS_MONEDAS = 1;
    const CONSULTA_AMBAS_MONEDAS_DETALLADA = 2;

    /**
     * 
     * @return LibroDiarioNegocio
     */
    static function create() {
        return parent::create();
    }

    public function generarAsientosCierreApertura($empresaId, $anio, $tipo, $usuarioId, $banderaGenerar = 1) {

        $identificadorNegocio = NULL;
        $mes = NULL;
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        switch ($tipo) {
            case "PC":
                $identificadorNegocio = ContVoucherNegocio::IDENTIFICADOR_CIERRE_PRE;
                $mes = "12";
                break;
            case "CC":
                $identificadorNegocio = ContVoucherNegocio::IDENTIFICADOR_CIERRE;
                $mes = "12";
                break;
            case "AP":
                $identificadorNegocio = ContVoucherNegocio::IDENTIFICADOR_APERTURA;
                $mes = "01";
                break;
        }

        $periodo = Util::filtrarArrayPorColumna($dataPeriodo, ["anio", "mes"], [$anio, $mes]);

        if (ObjectUtil::isEmpty($periodo)) {
            throw new WarningException('No existe el periodo ' . $anio . '-' . $mes);
        }

        if ($periodo[0]['indicador_contabilidad'] != 2) {
            throw new WarningException('El periodo ' . $anio . '-' . $mes . ' no esta abierto para contabilidad.');
        }

        IF ($banderaGenerar == 1) {
            $respuestaAnular = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($anio, $identificadorNegocio);
            if ($respuestaAnular[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar anular : ' . $respuestaAnular[0]['vout_mensaje']);
            }
        }

        switch ($tipo) {
            case "PC":

                $distribucionContable = array();

                $distribucionContable[] = self::generarAsientoPreCierre("79,94,95,97", $periodo, $usuarioId, NULL, $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("60", $periodo, $usuarioId, "8011", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("61,70,74", $periodo, $usuarioId, "8011", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8011", $periodo, $usuarioId, "8111", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8111", $periodo, $usuarioId, "8211", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("63", $periodo, $usuarioId, "8211", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8211", $periodo, $usuarioId, "8311", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("62", $periodo, $usuarioId, "8311", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("64", $periodo, $usuarioId, "8311", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8311", $periodo, $usuarioId, "8411", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("65", $periodo, $usuarioId, "8411", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("68", $periodo, $usuarioId, "8411", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("75", $periodo, $usuarioId, "8411", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8411", $periodo, $usuarioId, "8511", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("67", $periodo, $usuarioId, "8511", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("77", $periodo, $usuarioId, "8511", $banderaGenerar);
                $distribucionContable[] = self::generarAsientoPreCierre("8511", $periodo, $usuarioId, "8911", $banderaGenerar);
                break;
            case "CC":

                IF ($banderaGenerar == 1) {
                    $dataPreCierre = ContVoucherNegocio::create()->obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($anio, ContVoucherNegocio::IDENTIFICADOR_CIERRE_PRE);
                    if (ObjectUtil::isEmpty($dataPreCierre)) {
                        throw new WarningException('Aún no genera los asiento de pre cierre.');
                    }
                }

                $distribucionContable = array();

                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "10", NULL, self::CONSULTA_AMBAS_MONEDAS_DETALLADA));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "12", self::CONSULTA_PERSONA_DOCUMENTO, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "14", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS_DETALLADA));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "16", self::CONSULTA_PERSONA_DOCUMENTO, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "18", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "20", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "28", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "32", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "33", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "34", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "37", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS_DETALLADA));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "39", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "40", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "41", NULL, NULL));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "42", self::CONSULTA_PERSONA_DOCUMENTO, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "44", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "45", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "46", self::CONSULTA_PERSONA, self::CONSULTA_AMBAS_MONEDAS));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "50"));
                $distribucionContable = array_merge($distribucionContable, self::generarDistribucionAsientoCierre($banderaGenerar, $periodo, "59"));

                if ($banderaGenerar == 0) {
                    $distribucionContable = array($distribucionContable);
                }
                $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_CIERRE;
                $glosa = "ASIENTO DE CIERRE " . $periodo[0]['anio'];
                break;
            case "AP":
                $dataCierre = ContVoucherNegocio::create()->obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio(($anio - 1), ContVoucherNegocio::IDENTIFICADOR_CIERRE);
                if (ObjectUtil::isEmpty($dataCierre)) {
                    throw new WarningException('Aún no genera el asiento de cierre para el ' . ($anio - 1));
                }
                $periodoAnioAnterior = Util::filtrarArrayPorColumna($dataPeriodo, ["anio", "mes"], [$anio - 1, 12]);
                $tipoCambioCompraAnioAnterior = $periodoAnioAnterior[0]['tc_fin_compra'];
                $distribucionContable = ContVoucherDetalleNegocio::create()->obtenerContVoucherDetalleXVoucherId($dataCierre[0]['id']);

                $distribucionContable = self::generarDistribucionAsientoApertura($distribucionContable, $periodo, $banderaGenerar, $usuarioId);
                if ($banderaGenerar == 0) {
                    $distribucionContable = array($distribucionContable);
                }
                $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_APERTURA;
                $glosa = "ASIENTO DE APERTURA " . $periodo[0]['anio'];
                break;
        }

        if ($banderaGenerar == 0) {
            return self::generarExcelAsiento($distribucionContable);
        } elseif ($tipo != "PC") {
            $negocio = new ContVoucherNegocio();
            $negocio->VARIACION_CERO = TRUE;
            return $negocio->guardarContVoucher($periodo[0]['anio'], $contOperacionTipoId, NULL, $periodo[0]['id'], ContVoucherNegocio::MONEDA_ID_SOLES, $glosa, $identificadorNegocio, $distribucionContable, $usuarioId);
        } else {
            return $distribucionContable[0];
        }
    }

    private function generarDistribucionAsientoApertura($distribucionContable, $dataPeriodo, $banderaGenerar = NULL, $usuarioId) {
        $arrayDistribucion = array();
        if (!ObjectUtil::isEmpty($distribucionContable)) {
            foreach ($distribucionContable as $index => $item) {
                $tipoCambioPeriodo = ($item['cuenta_naturaleza_id'] == 1 ? $dataPeriodo[0]['tc_inicio_compra'] : $dataPeriodo[0]['tc_inicio_venta']) * 1;

                $distribucionContable[$index]['fecha'] = $dataPeriodo[0]['fecha_inicio'];
                switch (TRUE) {
                    case ($item['debe_soles'] * 1) > 0:
                        $saldoSoles = ($item['debe_soles'] * 1);
                        $saldoDolares = ($item['debe_dolares'] * 1);
                        $distribucionContable[$index]['montoHaber'] = ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_SOLES ? $item['debe_soles'] : $item['debe_dolares']);
                        $distribucionContable[$index]['haber_soles'] = $item['debe_soles'];
                        $distribucionContable[$index]['haber_dolares'] = ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES ? $item['debe_dolares'] : 0);
                        $distribucionContable[$index]['debe_soles'] = 0;
                        $distribucionContable[$index]['debe_dolares'] = 0;
                        $banderaEsDebeHaber = 0;
                        break;
                    case ($item['haber_soles'] * 1) > 0:
                        $saldoSoles = ($item['haber_soles'] * 1);
                        $saldoDolares = ($item['haber_dolares'] * 1);
                        $distribucionContable[$index]['montoDebe'] = ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_SOLES ? $item['haber_soles'] : $item['haber_dolares']);
                        $distribucionContable[$index]['debe_soles'] = $item['haber_soles'];
                        $distribucionContable[$index]['debe_dolares'] = ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES ? $item['haber_dolares'] : 0);
                        $distribucionContable[$index]['haber_soles'] = 0;
                        $distribucionContable[$index]['haber_dolares'] = 0;
                        $banderaEsDebeHaber = 1;
                        break;
                }
                if ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                    $distribucionContable[$index]['movimiento_monto_soles'] = $saldoSoles;
                    if ($banderaGenerar == 1) {
                        $saldoSoles = Util::redondearNumero($saldoSoles, 2);
                        $saldoSolesPeriodo = Util::redondearNumero($saldoDolares * $tipoCambioPeriodo, 2);
                        $diferencia = Util::redondearNumero($saldoSolesPeriodo - $saldoSoles, 2);
                        if (abs($diferencia) > 0) {
                            $itemCambio = $item;
                            $itemCambio["moneda_id"] = ContVoucherNegocio::MONEDA_ID_SOLES;
                            $itemCambio["monto"] = abs($diferencia);
                            $itemCambio['fecha'] = $dataPeriodo[0]['fecha_inicio'];
                            $itemCambio2 = array("montoTotal" => abs($diferencia), "moneda_id" => ContVoucherNegocio::MONEDA_ID_SOLES, "fecha" => $dataPeriodo[0]['fecha_inicio']);
                            $distribucionContableDiferenciaCambio = array($itemCambio, $itemCambio2);
                            switch ($banderaEsDebeHaber) {
                                case 1:
                                    $contOperacionTipoId = ($diferencia > 0 ? ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_GANANCIA : ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_PERDIDA);
                                    break;
                                case 0:
                                    $contOperacionTipoId = ($diferencia > 0 ? ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_PERDIDA : ContVoucherNegocio::OPERACION_TIPO_ID_DIFERENCIA_CAMBIO_GANANCIA);
                                    break;
                            }
                            $respuestaTc = ContVoucherNegocio::create()->guardarContVoucher($dataPeriodo[0]['anio'], $contOperacionTipoId, NULL, $dataPeriodo[0]['id'], ContVoucherNegocio::MONEDA_ID_SOLES, "AJUSTE POR TC " . $dataPeriodo[0]['fecha_inicio'], ContVoucherNegocio::IDENTIFICADOR_APERTURA, $distribucionContableDiferenciaCambio, $usuarioId);
                        }
                    }
                }
            }
        }
        return $distribucionContable;
    }

    private function generarDistribucionAsientoCierre($banderaGenerar, $dataPeriodo, $cuentaCodigo, $agrupador = NULL, $baneraMoneda = NULL) {
        $dataConsultaSaldo = ContVoucherNegocio::create()->obtenerSaldoCuentaXPeridoIdXCodigo($dataPeriodo[0]['id'], $cuentaCodigo, $agrupador, $baneraMoneda);
        $distribucionContable = array();
        if (!ObjectUtil::isEmpty($dataConsultaSaldo)) {
            foreach ($dataConsultaSaldo as $item) {
                $itemDistribuccion = $item;
                switch ($itemDistribuccion['bandera_debe_haber'] * 1) {
                    case 0: // el haber > debe                   
                        $itemDistribuccion['montoDebe'] = ($itemDistribuccion['saldo'] * 1);
                        break;
                    case 1: // el debe > haber
                        $itemDistribuccion['montoHaber'] = ($itemDistribuccion['saldo'] * 1);
                        break;
                }

                $itemDistribuccion['fecha'] = $dataPeriodo[0]['fecha_fin'];
                if ($itemDistribuccion['persona_id'] == "-1") {
                    $itemDistribuccion['persona_id'] = NULL;
                }

                if ($itemDistribuccion['documento_id'] == "-1") {
                    $itemDistribuccion['documento_id'] = NULL;
                }
                if ($banderaGenerar == 1 && $itemDistribuccion['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
//                if ($itemDistribuccion['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                    $tipoCambio = Util::redondearNumero(($itemDistribuccion['bandera_debe_haber'] == 1 ? ($itemDistribuccion['haber_soles'] * 1) / ($itemDistribuccion['haber_dolares'] * 1) : ($itemDistribuccion['debe_soles'] * 1) / ($itemDistribuccion['debe_dolares'] * 1)), 6);
                    $saldoSoles = ($itemDistribuccion['bandera_debe_haber'] == 1 ? ($itemDistribuccion['haber_soles'] * 1) : ($itemDistribuccion['debe_soles'] * 1));
                    $tipoCambioPeriodo = ($itemDistribuccion['cuenta_naturaleza_id'] == 1 ? $dataPeriodo[0]['tc_fin_compra'] : $dataPeriodo[0]['tc_fin_venta']) * 1;
                    if (Util::redondearNumero($tipoCambioPeriodo, 3) != Util::redondearNumero($tipoCambio, 3) && ObjectUtil::isEmpty($itemDistribuccion['documento_id'])) {
                        $itemDistribuccionSoles = $itemDistribuccion;
                        $itemDistribuccionSoles['moneda_id'] = ContVoucherNegocio::MONEDA_ID_SOLES;
                        $saldo = Util::redondearNumero($saldoSoles - Util::redondearNumero((($itemDistribuccion['saldo'] * 1) * $tipoCambioPeriodo), 2), 2);
                        if ($itemDistribuccion['bandera_debe_haber'] == 1) {
                            $itemDistribuccion['haber_soles'] = Util::redondearNumero((($itemDistribuccion['saldo'] * 1) * $tipoCambioPeriodo), 2);
                            $itemDistribuccionSoles['montoHaber'] = $saldo;
                            $itemDistribuccionSoles['haber_soles'] = $saldo;
                            $itemDistribuccionSoles['haber_dolares'] = 0;
                            $itemDistribuccionSoles['tipo_cambio'] = $tipoCambioPeriodo;
                            $tipoCambio = $tipoCambioPeriodo;
                        } else {
                            $itemDistribuccion['debe_soles'] = Util::redondearNumero((($itemDistribuccion['saldo'] * 1) * $tipoCambioPeriodo), 2);
                            $itemDistribuccionSoles['montoDebe'] = $saldo;
                            $itemDistribuccionSoles['debe_soles'] = $saldo;
                            $itemDistribuccionSoles['debe_dolares'] = 0;
                            $itemDistribuccionSoles['tipo_cambio'] = $tipoCambioPeriodo;
                            $tipoCambio = $tipoCambioPeriodo;
                        }
                        $distribucionContable[] = $itemDistribuccionSoles;
                    } else {
                        $itemDistribuccion['movimiento_monto_soles'] = $saldoSoles;
                    }
                    $itemDistribuccion['tipo_cambio'] = $tipoCambio;
                    $distribucionContable[] = $itemDistribuccion;
                } else {
                    $distribucionContable[] = $itemDistribuccion;
                }
            }
        }

        return $distribucionContable;
    }

    private function generarAsientoPreCierre($codigoCuentas, $dataPeriodo, $usuarioId, $cuentaCierre, $banderaGenerar) {
        $dataCuentaTotal = ContVoucherNegocio::create()->obtenerSaldoCuentaXPeridoIdXCodigo($dataPeriodo[0]['id'], $codigoCuentas);

        $distribucionContable = array();
        $montoDebe = 0.00;
        $montoHaber = 0.00;
        foreach ($dataCuentaTotal as $item) {
            $saldoCuenta = ($item['saldo'] * 1);
            $itemDistribuccion = $item;

            switch ($itemDistribuccion['bandera_debe_haber'] * 1) {
                case 0: // el haber > debe                   
                    $itemDistribuccion['montoDebe'] = $saldoCuenta;
                    $montoHaber = Util::redondearNumero($montoHaber + $saldoCuenta, 2);
                    break;
                case 1: // el debe > haber
                    $itemDistribuccion['montoHaber'] = $saldoCuenta;
                    $montoDebe = Util::redondearNumero($montoDebe + $saldoCuenta, 2);
                    break;
            }
            $itemDistribuccion['fecha'] = $dataPeriodo[0]['fecha_fin'];
            $distribucionContable[] = $itemDistribuccion;
        }
        if (!ObjectUtil::isEmpty($cuentaCierre)) {
            $diferencia = Util::redondearNumero($montoDebe - $montoHaber, 2);
            $itemDistribuccion = array("plan_contable_codigo" => $cuentaCierre, "moneda_id" => ContVoucherNegocio::MONEDA_ID_SOLES, "fecha" => $dataPeriodo[0]['fecha_fin']);
            $itemDistribuccion['debe_soles'] = 0;
            $itemDistribuccion['haber_soles'] = 0;
            $itemDistribuccion['debe_dolares'] = 0;
            $itemDistribuccion['haber_dolares'] = 0;
            IF ($diferencia > 0) {
                $itemDistribuccion['montoDebe'] = abs($diferencia);
                $itemDistribuccion['debe_soles'] = abs($diferencia);
            } ELSE {
                $itemDistribuccion['montoHaber'] = abs($diferencia);
                $itemDistribuccion['haber_soles'] = abs($diferencia);
            }
            $distribucionContable[] = $itemDistribuccion;
        }
        if ($banderaGenerar == 1) {
            return ContVoucherNegocio::create()->guardarContVoucher($dataPeriodo[0]['anio'], ContVoucherNegocio::OPERACION_TIPO_ID_CIERRE_PRE, NULL, $dataPeriodo[0]['id'], ContVoucherNegocio::MONEDA_ID_SOLES, "ASIENTO DE PRE CIERRE " . $dataPeriodo[0]['anio'], ContVoucherNegocio::IDENTIFICADOR_CIERRE_PRE, $distribucionContable, $usuarioId);
        } else {
            return $distribucionContable;
        }
    }

    public function obtenerConfiguracionInicial($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataPersonaActiva = PersonaNegocio::create()->obtenerActivas();
        $respuesta->dataLibro = ContLibroNegocio::create()->obtenerXClasificacion();
        $respuesta->dataMoneda = MonedaNegocio::create()->obtenerComboMoneda();
        $respuesta->dataCuentasContables = PlanContableNegocio::create()->obtenerXEmpresaId($empresaId);
        $respuesta->dataCentroCostos = CentroCostoNegocio::create()->listarCentroCosto($empresaId);
        $respuesta->dataDocumento = LibroDiario::create()->obtenerDocumento($empresaId);
        $respuesta->dataLibroDiario = self::listarLibroDiarioXCriterios(array(array("empresa" => $empresaId, "periodoInicio" => $dataPeriodoActual[0]['id'])));
        $respuesta->dataEjercicio = ContParametroContable::create()->obtenerXEjercicioContableXEmpresaId($empresaId);
        $respuesta->ejercicioActual = date('Y');
        return $respuesta;
    }

    public function generarExcelAsiento($distribucionContable) {


        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Pre - visualización de asiento');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Descripción');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Persona');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Haber');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Debe Dólares');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Haber Dólares');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($distribucionContable as $asiento) {
            $iInicio = $i;
            foreach ($asiento as $item) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $item['plan_contable_codigo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $item['plan_contable_descripcion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $item['persona_codigo_identificacion'] . " | " . $item['persona_nombre']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $item['documento_referencia']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $item['debe_soles']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $item['haber_soles']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $item['debe_dolares']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $item['haber_dolares']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':H' . $i)->applyFromArray($this->estiloNumInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $i += 1;
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, "=SUM(E" . $iInicio . ":E" . ($i - 1) . ")");
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, "=SUM(F" . $iInicio . ":F" . ($i - 1) . ")");
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, "=SUM(G" . $iInicio . ":G" . ($i - 1) . ")");
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, "=SUM(H" . $iInicio . ":H" . ($i - 1) . ")");
            $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':H' . $i)->applyFromArray($this->estiloSubTotalesFilas);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
        }

        for ($i = 'A'; $i <= 'H'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pre - visualización de asiento');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $nombreFile = "pre_asiento.xlsx";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/' . $nombreFile);

        return $nombreFile;
    }

    public function listarLibroDiarioXCriterios($criterios) {
        if (!ObjectUtil::isEmpty($criterios[0]['empresa'])) {
            $empresaId = $criterios[0]['empresa'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['persona'])) {
            $personaId = $criterios[0]['persona'];
        } else {
            $personaId = null;
        }

        if (!ObjectUtil::isEmpty($criterios[0]['libro'])) {
            $contLibroId = $criterios[0]['libro'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['periodoInicio'])) {
            $periodoIdInicio = $criterios[0]['periodoInicio'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['periodoFin'])) {
            $periodoIdFin = $criterios[0]['periodoFin'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['fechaEmisionDesde'])) {
            $fechaInicio = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaEmisionDesde']);
        }

        if (!ObjectUtil::isEmpty($criterios[0]['fechaEmisionHasta'])) {
            $fechaFin = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaEmisionHasta']);
        }

        return LibroDiario::create()->listarLibroDiarioXCriterios($empresaId, $personaId, $contLibroId, $periodoIdInicio, $periodoIdFin, $fechaInicio, $fechaFin);
    }

    public function obtenerLibroDiarioExcel($criterios) {

        $reportes = self::listarLibroDiarioXCriterios(array(array("empresa" => $criterios[0]['empresa'], "libro" => $criterios[0]['libro'], "periodoInicio" => $criterios[0]['periodoInicio'])));

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Libro diario');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Descripción');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Glosa');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Persona');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Libro');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Dólares');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Haber');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        $dataLibros = ContLibroNegocio::create()->obtenerXClasificacion();

        $montoTotalHaber = 0;
        $montoTotalDebe = 0;
        foreach ($dataLibros as $libro) {

            $arrayFiltradoLibro = Util::filtrarArrayPorColumna($reportes, 'cont_libro_id', $libro['id']);

            if (!ObjectUtil::isEmpty($arrayFiltradoLibro) && count($arrayFiltradoLibro) > 0) {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':K' . $i);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $libro['codigo'] . " | " . $libro['descripcion']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloSubTitulo);
                $i += 1;

                $arrayVoucherFiltrado = array_unique(array_map(function($itemFill) {
                            return $itemFill['cont_voucher_id'];
                        }, $arrayFiltradoLibro));

                foreach ($arrayVoucherFiltrado as $voucher) {
                    $montoDebe = 0;
                    $montoHaber = 0;
                    $arrayDetalleVoucher = Util::filtrarArrayPorColumna($arrayFiltradoLibro, 'cont_voucher_id', $voucher);
                    foreach ($arrayDetalleVoucher as $detalleVoucher) {
//                        $correlativo = explode($detalleVoucher['libro_codigo'] . '-', $detalleVoucher['cuo'])[1];
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $detalleVoucher['plan_contable_codigo']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $detalleVoucher['plan_contable_descripcion']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $detalleVoucher['glosa']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, explode("" . $detalleVoucher["libro_clasificacion"] . "-" . $detalleVoucher['libro_codigo'] . '-', $detalleVoucher['cuo'])[1]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $detalleVoucher['persona_codigo_identificacion'] . " | " . $detalleVoucher['persona_nombre_completo']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $detalleVoucher['documento_tipo_sunat'] . " | " . $detalleVoucher['documento_referencia']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, DateUtil::formatearFechaBDAaCadenaVw(substr($detalleVoucher['fecha_contabilizacion'], 0, 10)));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $detalleVoucher['libro_codigo']);

                        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $detalleVoucher['monto_dolares']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $detalleVoucher['debe_soles']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $detalleVoucher['haber_soles']);
                        $montoDebe += $detalleVoucher['debe_soles'] * 1;
                        $montoHaber += $detalleVoucher['haber_soles'] * 1;

                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloInformacion);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':K' . $i)->applyFromArray($this->estiloNumInformacion);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $i += 1;
                    }
                    $montoTotalDebe += $montoDebe;
                    $montoTotalHaber += $montoHaber;

                    $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, number_format($montoDebe, 2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, number_format($montoHaber, 2));
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloSubTotalesFilas);
                    $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $i += 1;
                }
            }
        }
        /*
          $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $montoTotalDebe);
          $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $montoTotalHaber);
          $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloSubTotalesFilas);
          $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':J' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
          $i += 1;
         */
        for ($i = 'A'; $i <= 'K'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Libro de diario');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/libroDiario.xlsx');

        return 1;
    }

    public function obtenerLibroDiarioTxt($criterios) {

        $data = self::listarLibroDiarioXCriterios(array(array("empresa" => $criterios[0]['empresa'], "periodoInicio" => $criterios[0]['periodoInicio'])));

        $dataLibros = ContLibroNegocio::create()->obtenerXClasificacion();
        if (!ObjectUtil::isEmpty($data) && !ObjectUtil::isEmpty($dataLibros)) {

            $periodo = $data[0]['periodo'];
            $archivoNombre = "LE20600143361$periodo" . "00050100001111.TXT";
            $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
            file_put_contents($direccion, null);
            $file = fopen($direccion, "w");
            $direccion = "\xEF\xBB\xBF" . $direccion;


            foreach ($dataLibros as $libro) {
                $arrayFiltradoLibro = array();
                foreach ($data as $item) {
                    if ($libro['id'] == $item['cont_libro_id']) {
                        $arrayFiltradoLibro[] = $item['cont_voucher_id'];
                    }
                }

                if (count($arrayFiltradoLibro) > 0) {
                    $arrayFiltradoLibro = array_unique($arrayFiltradoLibro);
                    foreach ($arrayFiltradoLibro as $voucher) {
                        $arrayDetalleVoucher = Util::filtrarArrayPorColumna($data, 'cont_voucher_id', $voucher);
                        foreach ($arrayDetalleVoucher as $detalleVoucher) {
                            $linea = "";
//                            linea 1
                            $linea = $detalleVoucher["periodo"] . "00|";
//                            linea 2
//                            $correlativoCuo = (int) trim(str_replace("" . $detalleVoucher["libro_clasificacion"] . "-" . $detalleVoucher["libro_codigo"] . "-", "", str_replace("-" . $detalleVoucher["periodo"], "", $detalleVoucher["cuo"])));
//                            $cuo = $detalleVoucher["libro_codigo"] . "-" . $correlativoCuo;
                            $cuo = $detalleVoucher["cuo"];
                            $linea .= str_pad($cuo, 40, Util::rellenarEspacios(40)) . "|";
//                            linea 3
                            $codigoLinea = 'M';
                            if ($detalleVoucher['cont_libro_id'] == 18) {
                                $codigoLinea = 'A';
                            } elseif ($detalleVoucher['cont_libro_id'] == 19) {
                                $codigoLinea = 'C';
                            }

                            $numeroLinea = $codigoLinea . str_pad($detalleVoucher["linea"], 4, '0', STR_PAD_LEFT);
                            $linea .= str_pad($numeroLinea, 10, Util::rellenarEspacios(10)) . "|";
//                            linea 4
                            $linea .= str_pad($detalleVoucher["plan_contable_codigo"], 24, Util::rellenarEspacios(24)) . "|";
//                            linea 5
                            $linea .= str_pad("", 24, Util::rellenarEspacios(24)) . "|";
//                            linea 6
                            $linea .= str_pad("", 24, Util::rellenarEspacios(24)) . "|";
//                            linea 7
                            $linea .= "PEN|";
//                            linea 8 y 9
                            $personaTipoCodigo = $detalleVoucher["persona_codigo_identificacion_tipo"];
                            $personaRuc = $detalleVoucher["persona_codigo_identificacion"];
                            if (!ObjectUtil::isEmpty($personaTipoCodigo) && !ObjectUtil::isEmpty($personaRuc) && is_numeric($personaRuc)) {
                                if ($personaRuc == "00000000002" || $personaRuc == "00000000001" || $personaRuc == "00000000003") {
                                    $personaTipoCodigo = "0";
                                }
                                $linea .= $personaTipoCodigo . "|";
                                $linea .= str_pad($personaRuc, 15, Util::rellenarEspacios(15)) . "|"; //                            
                            } else {
                                $linea .= "0|";
                                $linea .= str_pad("0", 15, Util::rellenarEspacios(15)) . "|";
                            }
//                            linea 10 ,11 y 12
                            if (!ObjectUtil::isEmpty($detalleVoucher["documento_tipo_sunat"]) && !ObjectUtil::isEmpty($detalleVoucher["documento_referencia"]) && $detalleVoucher["documento_tipo_sunat"] * 1 !== 10) {
                                $linea .= $detalleVoucher["documento_tipo_sunat"] . "|";
                                if (($detalleVoucher["documento_tipo_sunat"] * 1) == 91) {
                                    $serieNumero = (preg_replace('/[^0-9]+/', '', $detalleVoucher["documento_referencia"])) * 1;
                                    $serie = 0;
                                    $numero = $serieNumero;
                                    $longitud = strlen($serie);
                                } else {
                                    $serieNumero = explode("-", $detalleVoucher["documento_referencia"]);
                                    $serie = trim($serieNumero[0]);
                                    $numero = trim($serieNumero[1]);
                                    if (!preg_match('/[A-Za-z]/i', $numero)) {
                                        $numero = $numero * 1;
                                    }

                                    $longitud = strlen($serie);
                                }

                                if ($longitud != 4 && ($detalleVoucher["documento_tipo_sunat"] * 1) != 5 && ($detalleVoucher["documento_tipo_sunat"] * 1) != 50) {
                                    $totalCeros = 4 - $longitud;
                                    $ceros = "";
                                    for ($i = 0; $i < $totalCeros; $i++) {
                                        $ceros .= 0;
                                    }
                                    $linea .= str_pad($ceros . $serie, 20, Util::rellenarEspacios(20)) . "|";
                                } else {
                                    $linea .= str_pad($serie, 20, Util::rellenarEspacios(20)) . "|";
                                }
                                $linea .= str_pad($numero, 20, Util::rellenarEspacios(20)) . "|";
                            } else {
                                $linea .= "00|";
                                $linea .= str_pad("0", 20, Util::rellenarEspacios(20)) . "|";
                                $linea .= str_pad("0", 20, Util::rellenarEspacios(20)) . "|";
                            }
                            // linea 13
                            $fechaDocumento = "01/01/0001";
                            // linea 14
                            $fechaVencimiento = "01/01/0001";
                            // linea 15
                            if (!ObjectUtil::isEmpty($detalleVoucher["documento_tipo_sunat"]) && !ObjectUtil::isEmpty($detalleVoucher['documento_fecha_emision'])) {
                                $fechaDocumento = DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['documento_fecha_emision']);
                            }
                            if (!ObjectUtil::isEmpty($detalleVoucher["documento_tipo_sunat"]) && !ObjectUtil::isEmpty($detalleVoucher['documento_fecha_vencimiento'])) {
                                $fechaVencimiento = DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['documento_fecha_vencimiento']);
                            }
                            $linea .= DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['fecha_contabilizacion']) . "|";
                            $linea .= $fechaVencimiento . "|";
                            $linea .= $fechaDocumento . "|";
//                            linea 16
                            $glosa = substr(trim(Util::normaliza($detalleVoucher["glosa"])), 0, 200);
                            $linea .= str_pad($glosa, 200, Util::rellenarEspacios(200)) . "|";
//                            linea 17
                            $linea .= str_pad("", 200, Util::rellenarEspacios(200)) . "|";
//                            linea 18
                            $montoDebe = str_replace(",", "", number_format($detalleVoucher["debe_soles"], 2));
                            $montoHaber = str_replace(",", "", number_format($detalleVoucher["haber_soles"], 2));
                            $linea .= str_pad($montoDebe, 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
//                            linea 19
                            $linea .= str_pad($montoHaber, 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
//                            linea 20
                            $estructura = "140100"; //Si no es compras va este codigo.
                            if (($detalleVoucher["libro_clasificacion"] * 1) == ContLibro::CLASIFICACION_COMPRAS) {
                                $estructura = "080100";
                            }
                            $estructura .= "&" . $detalleVoucher["periodo"] . "00&" . $cuo . "&" . $numeroLinea;
                            $linea .= str_pad($estructura, 92, Util::rellenarEspacios(92)) . "|";
//                            linea 21
                            $linea .= "1|";
//                            linea 22
                            $linea .= str_pad("", 200, Util::rellenarEspacios(200));
                            $lineaSalida = mb_convert_encoding($linea, "ISO-8859-1");
                            fwrite($file, $lineaSalida . "\r\n");
                        }
                    }
                }
            }
            fclose($file);
            return $archivoNombre;
        }
    }

    private function estilosExcel() {

        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );



        $this->estiloSubTitulo = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
        );

        $this->estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTextoInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloSubTotalesFilas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
    }

    private function obtenerCelda($cells, $col, $row, $limpiaString = true) {
        if (!array_key_exists($row, $cells))
            return "";
        $tildes = array("á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "." => "_", " " => "");
        $valor = ($limpiaString) ? $this->limpiarString($cells[$row][$col]) : $cells[$row][$col];
        return trim(strtr(trim($valor), $tildes) . "");
//        return trim($this->limpiarString($cells[$row][$col]));  
    }

    private function limpiarString($texto) {
        $textoLimpio = preg_replace('([^A-Za-z0-9])', '', $texto);
        return $textoLimpio;
    }

    private function obtenerImporte($importe) {
        if (strlen("" . importe) < 2) {
            $numero = str_replace(array("-", ",", "*", "?"), "", "" . $importe);
        } elseif (strpos($importe, "(")) {
            $numero = "-" . str_repeat(array("(", ")"), "", "" . $importe);
        } else {
            $numero = str_replace(array(",", "*", "?"), "", "" . $importe);
        }
        $numero = str_replace("_", ".", $numero);
        $numero = (ObjectUtil::isEmpty($numero)) ? (float) 0.00 : (float) $numero;
        return $numero;
    }

    private function formatoFecha($sFecha) {
        $aFecha = explode("/", $sFecha);
        return $aFecha[1] . "/" . $aFecha[0] . "/" . $aFecha[2];
    }

}
