<?php

require_once __DIR__ . '/../../modelo/contabilidad/ActivoFijo.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ExcelNegocio.php';

class ActivoFijoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ActivoFijoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
//        $respuesta->dataMoneda = MonedaNegocio::create()->obtenerComboMoneda();
        return $respuesta;
    }

    public function obtenerActivosFijos($empresaId) {
        return ActivoFijo::create()->obtenerActivosFijos($empresaId);
    }

    public function darDeBajaActivoFijo($bienId, $periodoId, $usuarioId, $cuentaRetiro, $fechaContable) {
        $respuestaDeBajaAF = ActivoFijo::create()->darDeBajaActivoFijo($bienId, $periodoId, $usuarioId);
        if ($respuestaDeBajaAF[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException("Error la intentar generar la depreaciacion : " . $respuestaDeBajaAF[0]['vout_mensaje']);
        }

        $bienDepreciacionId = $respuestaDeBajaAF[0]['bien_depreciacion_id'];
        $costoInicial = $respuestaDeBajaAF[0]['costo_inicial'] * 1;
        $montoDepreciadoAcumulado = $respuestaDeBajaAF[0]['monto_depreciado_acumulado'] * 1;
        $bienCodigo = $respuestaDeBajaAF[0]['bien_codigo'];
        $bienDescripcion = $respuestaDeBajaAF[0]['bien_descripcion'];

        $dataAsiento = ActivoFijo::create()->obtenerAsientoActivoFijoXBienDepreciacionId($bienDepreciacionId);
        if (ObjectUtil::isEmpty($dataAsiento)) {
            throw new WarningException("Error la intentar generar la depreaciacion : imposible obtener la información necesaria para generar el asiento contable.");
        }

        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("Imposible obtener la información del periodo.");
        }

        if (ObjectUtil::isEmpty($fechaContable) && date('Y') == $dataPeriodo[0]['anio'] && (date('m') * 1) == ($dataPeriodo[0]['anio'] * 1)) {
            $fecha = date('Y-m-d');
        } elseif (ObjectUtil::isEmpty($fechaContable)) {
            $fecha = Util::obtenerUltimoDiaMes($dataPeriodo[0]['anio'], $dataPeriodo[0]['mes']);
        } else {
            $fecha = DateUtil::formatearCadenaACadenaBD($fechaContable);
        }

        $glosa = 'Dar de baja activo fijo  ' . $bienCodigo . ' ' . $bienDescripcion;
        $monedaId = ContVoucherNegocio::MONEDA_ID_SOLES;

        $distribucionContable = array();
        $distribucionContable[] = array('plan_contable_codigo' => $dataAsiento[0]['plan_contable_depreciacion'], 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoDepreciacion' => $montoDepreciadoAcumulado);
        $distribucionContable[] = array('plan_contable_codigo' => $dataAsiento[0]['plan_contable_codigo'], 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoCostoInicial' => $costoInicial);
        foreach ($dataAsiento as $item) {
            $distribucionContable[] = array('plan_contable_codigo' => $cuentaRetiro, 'centro_costo_codigo' => $item['centro_costo_codigo'], 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoRetiro' => $item['monto_depreciado']);
        }

        $respuestaAsientoDarBaja = ContVoucherNegocio::create()->guardarContVoucher($bienDepreciacionId, ContVoucherNegocio::OPERACION_TIPO_ID_ACTIVO_FIJO_BAJA_RETIRO, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_ACTIVO_FIJO, $distribucionContable, $usuarioId);
        return $respuestaDeBajaAF;
    }

    public function generarDepreciacionXPeriodoId($periodoId, $usuarioId) {

        $respuestaAnularVoucher = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($periodoId, ContVoucherNegocio::IDENTIFICADOR_ACTIVO_FIJO);
        if ($respuestaAnularVoucher[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException("Error al intentar anular voucher : " . $respuestaAnularVoucher[0]['vout_mensaje']);
        }

        $respuestaDepreacion = ActivoFijo::create()->generarDepreciacionXPeriodoId($periodoId, $usuarioId);
        if ($respuestaDepreacion[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException("Error la intentar generar la depreaciacion : " . $respuestaDepreacion[0]['vout_mensaje']);
        }

        $periodoCodigo = $respuestaDepreacion[0]['periodo_codigo'];


        $monedaId = ContVoucherNegocio::MONEDA_ID_SOLES;

        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($periodoId);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("Imposible obtener la información del periodo.");
        }

        if (date('Y') == $dataPeriodo->dataPeriodo[0]['anio'] && (date('m') * 1) == ($dataPeriodo->dataPeriodo[0]['mes'] * 1)) {
            $fecha = date('Y-m-d');
        } else {
            $fecha = Util::obtenerUltimoDiaMes($dataPeriodo->dataPeriodo[0]['anio'], $dataPeriodo->dataPeriodo[0]['mes']);
        }

        // DAR DE BAJA A LOS ACTIVOS FIJOS 
        $dataActivoPorDarBaja = ActivoFijo::create()->obtenerActivosFijosDetreciadosXPeriodoId($periodoId); //FALTA PROCEDIMIENTO
        foreach ($dataActivoPorDarBaja as $item) {
            $distribucionContable = array();
            $costoInicial = ($item['costo_inicial'] * 1);
            $depreciacionAcumulada = ($item['depreciacion_acumulada'] * 1);
            $glosa = "Asiento por la baja del activo fijo " . $item['bien_codigo'] . " " . $item['bien_descripcion'];
            $saldoPorDepreciar = Util::redondearNumero($costoInicial - $depreciacionAcumulada, 6);
            if (Util::redondearNumero($saldoPorDepreciar, 2) < 1) {

                $distribucionContable[] = array('plan_contable_codigo' => $item['plan_contable_depreciacion'], 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoDepreciacion' => $depreciacionAcumulada);
                $distribucionContable[] = array('plan_contable_codigo' => $item['plan_contable_codigo'], 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoCostoInicial' => $costoInicial);

                if ($saldoPorDepreciar < 0) {
                    $distribucionContable[] = array('fecha' => $fecha, 'moneda_id' => $monedaId, 'montoExtraDepre' => abs($saldoPorDepreciar));
                } elseif ($saldoPorDepreciar > 0) {
                    $distribucionContable[] = array('centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'fecha' => $fecha, 'moneda_id' => $monedaId, 'montoExtraCosto' => $saldoPorDepreciar);
                }
                $respuestaAsientoDarBaja = ContVoucherNegocio::create()->guardarContVoucher($periodoId, ContVoucherNegocio::OPERACION_TIPO_ID_ACTIVO_FIJO_BAJA_RETIRO, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_ACTIVO_FIJO, $distribucionContable, $usuarioId);

                $repuestaActualizarEstado = BienNegocio::create()->actualizarEstadoDepreciado($item['bien_id']);
            }
        }

        // GENERAR ASIENTO DE DEPRECIACION
        $distribucionContable = ActivoFijo::create()->obtenerAsientoActivoFijoXPeriodoId($periodoId);
        if (ObjectUtil::isEmpty($distribucionContable)) {
            throw new WarningException("Error la intentar generar la depreaciacion : imposible obtener la información necesaria para generar el asiento contable.");
        }

        $glosa = 'Depreciacion de activo fijo periodo ' . $periodoCodigo . ', el día ' . $fecha;

        foreach ($distribucionContable as $index => $item) {
            $distribucionContable[$index]['fecha'] = $fecha;
            $distribucionContable[$index]['moneda_id'] = $monedaId;
            if ($item['debe_haber'] == 0) {
                $distribucionContable[$index]['montoHaber'] = $item['monto_depreciado'];
            } else {
                $distribucionContable[$index]['montoDebe'] = $item['monto_depreciado'];
            }
        }

        $respuestaAsientoDepreciacion = ContVoucherNegocio::create()->guardarContVoucher($periodoId, ContVoucherNegocio::OPERACION_TIPO_ID_ACTIVO_FIJO, NULL, $periodoId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_ACTIVO_FIJO, $distribucionContable, $usuarioId);

        return $respuestaDepreacion;
    }

    public function obtenerExcelActivosFijoSunat($activoFijoId, $empresaId) {
        $data = ActivoFijo::create()->obtenerExcelActivosFijoSunat($activoFijoId);
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar.");
        }
        $anio = $data[0]['anio'];
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'FORMATO 7.1: "REGISTRO DE ACTIVOS FIJOS - DETALLE DE LOS ACTIVOS FIJOS"');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTituloActivoFijoLeft);
        $i += 2;


        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'PERÍODO: ' . $data[0]['anio']);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTituloActivoFijoLeft);
        $i += 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'RUC: ' . $dataEmpresa[0]['ruc']);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTituloActivoFijoLeft);
        $i += 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'APELLIDOS Y NOMBRES, DENOMINACIÓN O RAZÓN SOCIAL: ' . $dataEmpresa[0]['razon_social']);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTituloActivoFijoLeft);
        $i += 2;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':A' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'CÓDIGO RELACIONADO CON EL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':B' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'CUENTA CONTABLE DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $i . ':F' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'DETALLE DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . ($i + 1) . ':C' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . ($i + 1), 'DESCRIPCION');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . ($i + 1) . ':D' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . ($i + 1), 'MARCA DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . ($i + 1) . ':E' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . ($i + 1), 'MODELO DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . ($i + 1) . ':F' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . ($i + 1), 'NÚMERO DE SERIE Y/O PLACA DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $i . ':G' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'SALDO INICIAL');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H' . $i . ':H' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'ADQUISICION ADICIONALES');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I' . $i . ':I' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'MEJORAS');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J' . $i . ':J' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'RETIROS Y/O BAJAS');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K' . $i . ':K' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'OTROS AJUSTES');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L' . $i . ':L' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'VALOR HISTÓRICO DEL ACTIVO FIJO AL 31.12');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $i . ':M' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'AJUSTE POR INFLACIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N' . $i . ':N' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'VALOR  AJUSTADO DEL ACTIVO FIJO AL 31.12');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('O' . $i . ':O' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'FECHA DE ADQUISICIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('P' . $i . ':P' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'FECHA DE INICIO DEL USO DEL ACTIVO FIJO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . $i . ':R' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, 'DEPRECIACIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . ($i + 1) . ':Q' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . ($i + 1), 'MÉTODO APLICADO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('R' . ($i + 1) . ':R' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . ($i + 1), 'N° DE DOCUMENTO DE AUTORIZACIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('S' . $i . ':S' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, 'PORCENTAJE DE DEPRECIACIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('T' . $i . ':T' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, 'DEPRECIACIÓN ACUMULADA AL AL CIERRE DEL EJERCICIO ANTERIOR');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U' . $i . ':U' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $i, 'DEPRECIACIÓN DEL EJERCICIO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('V' . $i . ':V' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $i, 'DEPRECIACIÓN DEL EJERCICIO RELACIONADA CON LOS RETIROS Y/O BAJAS');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W' . $i . ':W' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $i, 'DEPRECIACIÓN RELACIONADA CON OTROS AJUSTES');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('X' . $i . ':X' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $i, 'DEPRECIACIÓN ACUMULADA HISTÓRICA');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Y' . $i . ':Y' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $i, 'AJUSTE POR INFLACIÓN DE LA DEPRECIACIÓN');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z' . $i . ':Z' . ($i + 3));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $i, 'DEPRECIACIÓN ACUMULADA AJUSTADA POR INFLACIÓN');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloActivoFijoCenter);
        $i += 1;
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloActivoFijoCenter);
        $i += 1;
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloActivoFijoCenter);
        $i += 1;
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloActivoFijoCenter);
        $i += 1;

        $inicioSumatoria = $i;
        foreach ($data as $item) {
            //COSTO AL INICIO DEL AÑO - DEPRECIACION DE AÑOS ANTERIORES
            $costoInicialDelAnio = 0;
            if (($item['compra_activo_fijo'] * 1) == 0) {
                $costoInicialDelAnio = ($item['costo_inicial'] * 1) + ($item['compra_adicionales_anteriores'] * 1) + ($item['mejoras_anteriores'] * 1);
//                $costoInicialDelAnio = round($costoInicialDelAnio - ($item['depreciacion_acumulada'] * 1), 6);
            }

            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('A' . $i, $item['codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('B' . $i, $item['plan_contable_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('C' . $i, $item['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('D' . $i, $item['marca']);
            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('E' . $i, $item['modelo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValueExplicit('F' . $i, $item['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $costoInicialDelAnio);

            if ((($item['compra_activo_fijo'] * 1) + ($item['compras_adicionales_actuales'] * 1)) > 0) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, (($item['compra_activo_fijo'] * 1) + ($item['compras_adicionales_actuales'] * 1))); // ADQUISICIONES ADICIONALES
            }
            if ($item['mejoras_actuales'] * 1 > 0) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $item['mejoras_actuales']); // MEJORAS
            }
            if ($item['estado'] == 3) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $costoInicialDelAnio);
            }

            $valorHistorioActivo = $costoInicialDelAnio + ($item['compra_activo_fijo'] * 1) + ($item['compras_adicionales_acumulada'] * 1) + ($item['mejoras_acumulada'] * 1);
            if ($item['estado'] == 3) {
                $valorHistorioActivo = 0;
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, $valorHistorioActivo);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, '');


            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, DateUtil::formatearFechaBDAaCadenaVw($item['fecha_adquisicion']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, DateUtil::formatearFechaBDAaCadenaVw($item['fecha_inicio_uso']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, $item['depreciacion_metodo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, ''); // 'N° DE DOCUMENTO DE AUTORIZACIÓN' ?
            $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, $item['depreciacion_porcentaje']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, $item['depreciacion_acumulada']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $i, $item['depreciacion_actual']);
            $depreciacionAcumulada = Util::redondearNumero(($item['depreciacion_actual'] * 1) + ($item['depreciacion_acumulada'] * 1), 6);
            if ($item['estado'] == 3) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $i, $depreciacionAcumulada);
                $depreciacionAcumulada = 0;
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $i, $depreciacionAcumulada);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $i, '');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloCeldaActivoFijoLeft);
            $objPHPExcel->getActiveSheet()->getStyle('O' . $i . ':R' . $i)->applyFromArray($this->estiloCeldaActivoFijoLeft);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':N' . $i)->applyFromArray($this->estiloCeldaActivoFijoRight);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':N' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->getActiveSheet()->getStyle('S' . $i . ':Z' . $i)->applyFromArray($this->estiloCeldaActivoFijoRight);
            $objPHPExcel->getActiveSheet()->getStyle('S' . $i . ':Z' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
        }
        $finSumatoria = $i - 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'TOTAL');
        $j = 'F';
        for ($x = 1; $x <= 20; $j++, $x++) {
            if ($j != 'O' && $j != 'P' && $j != 'Q' && $j != 'R') {
                $objPHPExcel->setActiveSheetIndex()->setCellValue($j . $i, '=SUM(' . $j . $inicioSumatoria . ':' . $j . $finSumatoria . ')');
            }
        }

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloCeldaActivoFijoRight);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':Z' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $i += 1;
        for ($i = 'A'; $i <= 'Z'; $i++) {
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth('40');
//            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('F 7.1 Det bs AF');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $excelNombre = 'ActivosFijo' . $anio . '.xlsx';
        $objWriter->save(__DIR__ . '/../../util/formatos/documentoActivoFijo/' . $excelNombre);

        return $excelNombre;
    }

    private function estilosExcel() {

        $this->estiloTituloActivoFijoLeft = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 11
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloActivoFijoCenter = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 11
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
                'wrap' => TRUE
            )
        );

        $this->estiloCeldaActivoFijoLeft = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => FALSE,
                'size' => 11
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
                'wrap' => TRUE
            )
        );

        $this->estiloCeldaActivoFijoRight = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => FALSE,
                'size' => 11
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
                'wrap' => TRUE
            )
        );

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

        $this->estiloInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
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
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
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
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
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
    }

}
