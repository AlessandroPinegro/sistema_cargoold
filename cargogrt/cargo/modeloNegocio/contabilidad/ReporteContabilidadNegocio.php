<?php

require_once __DIR__ . '/../../modelo/contabilidad/ReporteContabilidad.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class ReporteContabilidadNegocio extends ModeloNegocioBase {

    public $anios = array(array("anio" => 2020), array("anio" => 2021), array("anio" => 2022), array("anio" => 2023));

    /**
     * 
     * @return ReporteContabilidadNegocio
     */
    static function create() {
        return parent::create();
    }

    private function estilosExcel() {

        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'color' => array('rgb' => '0000FF'),
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
                'color' => array('rgb' => '0000FF'),
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
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FF8000')
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

    public function obtenerDataInicialReporteSumasMensual($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $respuesta->dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataReporte = self::obtenerReporteSumasMensualXCriterios($empresaId, $dataPeriodoActual[0]['id']);
        return $respuesta;
    }

    public function obtenerDataInicialReporteSumasAnual($empresaId) {
        $respuesta->dataAnio = $this->anios;
        $respuesta->dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $anioActual = date("Y");
        $respuesta->dataAnioActual = $anioActual;
        $respuesta->dataReporte = self::obtenerReporteSumasAnualXCriterios($empresaId, $anioActual);
        return $respuesta;
    }

    public function obtenerReporteSumasMensualXCriterios($empresaId, $periodoId) {
        return ReporteContabilidad::create()->obtenerReporteSumasMensualXCriterios($empresaId, $periodoId);
    }

    public function obtenerReporteSumasAnualXCriterios($empresaId, $anio) {
        return ReporteContabilidad::create()->obtenerReporteSumasAnualXCriterios($empresaId, $anio);
    }

    public function obtenerReporteSumasExcel($empresaId, $valorBusquedad, $tipo) {
        $dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $tituloExcel = 'Balance de sumas : ';
        $nombreExcel = 'Balance_sumas_';

        if ($tipo == "1") {
            $dataReporte = self::obtenerReporteSumasMensualXCriterios($empresaId, $valorBusquedad);
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($valorBusquedad)->dataPeriodo;
            setlocale(LC_TIME, 'es_ES');
            $nombreMes = strftime('%B', DateTime::createFromFormat('!m', $dataPeriodo[0]['mes'])->getTimestamp());
            $tituloExcel .= ucwords($nombreMes) . " " . $dataPeriodo[0]['anio'];
            $nombreExcel .= $nombreMes . "_" . $dataPeriodo[0]['anio'];
        } elseif ($tipo == "2") {
            $dataReporte = self::obtenerReporteSumasAnualXCriterios($empresaId, $valorBusquedad);
            $tituloExcel .= $valorBusquedad;
            $nombreExcel .= $valorBusquedad;
        }

        $this->estilosExcel();

        $objPHPExcel = new PHPExcel();

        $i = 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . ($i + 1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $tituloExcel);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Debe Inicial');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Haber Inicial');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Mov. Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Mov. Haber');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Saldo Deudor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Saldo Acreedor');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;

        $inicioCalculo = $i;

        if (!ObjectUtil::isEmpty($dataCuentaContableTitulo) && !ObjectUtil::isEmpty($dataReporte)) {

            foreach ($dataCuentaContableTitulo as $item) {

                $codigoCuentaMayor = $item['codigo'];
                $dataFilter = array();
                eval("\$dataFilter = array_filter(\$dataReporte, function (\$element) { return \substr(\$element['plan_contable_codigo'], 0, 2) == '$codigoCuentaMayor'; });");
                if (ObjectUtil::isEmpty($dataFilter)) {
                    continue;
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $codigoCuentaMayor);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $item['descripcion']);
                $i += 1;

                $totalDebeInicial = 0;
                $totalHaberInicial = 0;
                $totalDebeMovimiento = 0;
                $totalHaberMovimiento = 0;
                $totalDebeSaldo = 0;
                $totalHaberSaldo = 0;

                foreach ($dataFilter as $itemCuenta) {
                    $difDebeHaber = Util::redondearNumero(($itemCuenta['debe_inicial'] * 1) + ($itemCuenta['debe'] * 1) - ($itemCuenta['haber_inicial'] * 1) - ($itemCuenta['haber'] * 1), 2);
                    $saldoDebe = ($difDebeHaber > 0 ? $difDebeHaber : 0);
                    $saldoHaber = ($difDebeHaber < 0 ? abs($difDebeHaber) : 0);

                    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $itemCuenta['plan_contable_codigo']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $itemCuenta['plan_contable_descripcion']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $itemCuenta['debe_inicial']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $itemCuenta['haber_inicial']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $itemCuenta['debe']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $itemCuenta['haber']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $saldoDebe);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $saldoHaber);

                    $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $i += 1;

                    $totalDebeInicial = Util::redondearNumero($totalDebeInicial + ($itemCuenta['debe_inicial'] * 1), 2);
                    $totalHaberInicial = Util::redondearNumero($totalHaberInicial + ($itemCuenta['haber_inicial'] * 1), 2);
                    $totalDebeMovimiento = Util::redondearNumero($totalDebeMovimiento + ($itemCuenta['debe'] * 1), 2);
                    $totalHaberMovimiento = Util::redondearNumero($totalHaberMovimiento + ($itemCuenta['haber'] * 1), 2);
                    $totalDebeSaldo = Util::redondearNumero($totalDebeSaldo + $saldoDebe, 2);
                    $totalHaberSaldo = Util::redondearNumero($totalHaberSaldo + $saldoHaber, 2);
                }
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $totalDebeInicial);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $totalHaberInicial);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $totalDebeMovimiento);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalHaberMovimiento);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $totalDebeSaldo);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $totalHaberSaldo);

                $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $i += 2;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A' . $inicioCalculo . ':H' . $i)->applyFromArray($this->estiloInformacion);
        }

        for ($i = 'A'; $i <= 'H'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Balance de sumas');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $url = "util/formatos/$nombreExcel.xlsx";
        $objWriter->save(__DIR__ . '/../../' . $url);

        return $url;
    }

    public function obtenerDataInicialEstadoFinancieroFuncion($empresaId, $tipo) {
        $valorBusquedad = NULL;
        if ($tipo == 1) {
            $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
            $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
            $respuesta->dataPeriodoActual = $dataPeriodoActual;
            $valorBusquedad = $dataPeriodoActual[0]['id'];
        } elseif ($tipo == 2) {
            $respuesta->dataAnio = $this->anios;
            $anioActual = date("Y");
            $respuesta->dataAnioActual = $anioActual;
            $valorBusquedad = $anioActual;
        }
        $respuesta->dataReporte = self::obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad);
        return $respuesta;
    }

    public function obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad) {
        return ReporteContabilidad::create()->obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad);
    }

    public function obtenerEstadoFinancieroFuncionExcel($empresaId, $tipo, $valorBusquedad) {
        $tituloExcel = 'ESTADO DE GANANCIAS Y PERDIDAS POR FUNCION : ';
        $nombreExcel = 'Estado_ganancia_perdida_por_funcion_';
        $dataReporte = self::obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad);
        if ($tipo == "1") {
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($valorBusquedad)->dataPeriodo;
            setlocale(LC_TIME, 'es_ES');
            $nombreMes = strftime('%B', DateTime::createFromFormat('!m', $dataPeriodo[0]['mes'])->getTimestamp());
            $tituloExcel .= ucwords($nombreMes) . " " . $dataPeriodo[0]['anio'];
            $nombreExcel .= $nombreMes . "_" . $dataPeriodo[0]['anio'];
        } elseif ($tipo == "2") {
            $tituloExcel .= $valorBusquedad;
            $nombreExcel .= $valorBusquedad;
        }

        $this->estilosExcel();

        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $tituloExcel);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);
        $i += 2;
        $montoVentas = 1;
        $arrayConfiguracion[] = array("titulo" => "VENTAS NETAS", "cuenta" => array("70", "74"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "UTILIDAD BRUTA", "cuenta" => array("69"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "UTILIDAD OPERATIVA", "filaExtra" => "GASTOS DE OPERACIÓN", "cuenta" => array("95", "94"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "UTILIDAD ANTES DE PARTICIPACIONES E IMPUESTOS", "filaExtra" => "OTROS INGRESOS Y EGRESOS", "cuenta" => array("73", "75", "76", "77", "66", "97"), "montoTotal" => 0, "porcentajeTotal" => 0);

        $this->estilosExcel();

        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $tituloExcel);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);
        $i += 2;
        $iInicio = $i;
        if (!ObjectUtil::isEmpty($dataReporte) && !ObjectUtil::isEmpty($arrayConfiguracion)) {
            foreach ($arrayConfiguracion as $index => $item) {
                if (!ObjectUtil::isEmpty($item['filaExtra'])) {
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $item['filaExtra']);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $i += 1;
                }
                foreach ($item['cuenta'] as $value) {
                    $dataCuenta = Util::filtrarArrayPorColumna($dataReporte, "codigo", $value);
                    if (!ObjectUtil::isEmpty($dataCuenta)) {                        
                        if ($value === "69") {
                            $dataCuentaFiltro = Util::filtrarArrayPorColumna($dataCuenta, "tipo", "1");
                            $dataCuentaFiltro2 = Util::filtrarArrayPorColumna($dataCuenta, "tipo", "2");
                            $montoCalculado = Util::redondearNumero(0 - (!ObjectUtil::isEmpty($dataCuentaFiltro[0]["debe"]) ? $dataCuentaFiltro[0]["debe"] * 1 : 0), 2);
                            if(!ObjectUtil::isEmpty($dataCuentaFiltro2)){
                                $montoCalculado = Util::redondearNumero($montoCalculado + (!ObjectUtil::isEmpty($dataCuentaFiltro2[0]["haber"]) ? $dataCuentaFiltro2[0]["haber"] * 1 : 0), 2);
//                                $montoCalculado = $montoCalculado + Util::redondearNumero((!ObjectUtil::isEmpty($dataCuentaFiltro2[0]["haber"]) ? $dataCuentaFiltro2[0]["haber"] * 1 : 0) - (!ObjectUtil::isEmpty($dataCuentaFiltro2[0]["debe"]) ? $dataCuentaFiltro2[0]["debe"] * 1 : 0), 2);
                            }
                        } else {
                            $montoCalculado = Util::redondearNumero((!ObjectUtil::isEmpty($dataCuenta[0]["haber"]) ? $dataCuenta[0]["haber"] * 1 : 0) - (!ObjectUtil::isEmpty($dataCuenta[0]["debe"]) ? $dataCuenta[0]["debe"] * 1 : 0), 2);
                        }

//                                Util::redondearNumero((!ObjectUtil::isEmpty($dataCuenta[0]["haber"]) && $value !== "69" ? $dataCuenta[0]["haber"] * 1 : 0) - (!ObjectUtil::isEmpty($dataCuenta[0]["debe"]) ? $dataCuenta[0]["debe"] * 1 : 0), 2);
                        if ($value === "70") {
                            $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
                            $montoVentas = $montoCalculado;
                        }

                        $porcentajeCalculado = Util::redondearNumero($montoCalculado / $montoVentas, 6) * 100;

                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, strtoupper($dataCuenta[0]["descripcion"]));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $montoCalculado);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $porcentajeCalculado);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                        $i += 1;
                    }
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $item['titulo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, '=SUM(G' . $iInicio . ':G' . ($i - 1) . ')');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, '=SUM(H' . $iInicio . ':H' . ($i - 1) . ')');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getBorders()->getTop()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_HAIR));
                $iInicio = $i;
                $i += 2;
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z100')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
        for ($i = 'A'; $i <= 'H'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('EEFF');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $url = "util/formatos/$nombreExcel.xlsx";
        $objWriter->save(__DIR__ . '/../../' . $url);

        return $url;
    }

    public function obtenerDataInicialEstadoFinancieroNaturaleza($empresaId, $tipo) {
        $valorBusquedad = NULL;
        if ($tipo == 1) {
            $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
            $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
            $respuesta->dataPeriodoActual = $dataPeriodoActual;
            $valorBusquedad = $dataPeriodoActual[0]['id'];
        } elseif ($tipo == 2) {
            $respuesta->dataAnio = $this->anios;
            $anioActual = date("Y");
            $respuesta->dataAnioActual = $anioActual;
            $valorBusquedad = $anioActual;
        }
        $respuesta->dataReporte = self::obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad);
        return $respuesta;
    }

    public function obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad) {
        return ReporteContabilidad::create()->obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad);
    }

    public function obtenerEstadoFinancieroNaturalezaExcel($empresaId, $tipo, $valorBusquedad) {
        $tituloExcel = 'ESTADO DE GANANCIAS Y PERDIDAS POR NATURALEZA : ';
        $nombreExcel = 'Estado_ganancia_perdida_por_naturaleza_';
        $dataReporte = self::obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad);
        if ($tipo == "1") {
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($valorBusquedad)->dataPeriodo;
            setlocale(LC_TIME, 'es_ES');
            $nombreMes = strftime('%B', DateTime::createFromFormat('!m', $dataPeriodo[0]['mes'])->getTimestamp());
            $tituloExcel .= ucwords($nombreMes) . " " . $dataPeriodo[0]['anio'];
            $nombreExcel .= $nombreMes . "_" . $dataPeriodo[0]['anio'];
        } elseif ($tipo == "2") {
            $tituloExcel .= $valorBusquedad;
            $nombreExcel .= $valorBusquedad;
        }
        $montoVentas = 1;
        $arrayConfiguracion[] = array("titulo" => "VENTAS NETAS", "cuenta" => array("70", "74"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "MARGEN COMERCIAL", "cuenta" => array("60", "61"), "montoTotal" => 0, "porcentajeTotal" => 0);
        //$arrayConfiguracion[] = array("titulo" => "VALOR AGREGADO", "cuenta" => array("602", "603", "604", "63"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "VALOR AGREGADO", "cuenta" => array("63"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "EXCEDENTE BRUTO DE EXPLOTACION", "cuenta" => array("62", "64"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "RESULTADO DE EXPLOTACION", "cuenta" => array("73", "75", "76", "65", "68", "66"), "montoTotal" => 0, "porcentajeTotal" => 0);
        $arrayConfiguracion[] = array("titulo" => "RESULTADO ANTES DE PARTICIPACIONES E IMPUESTOS", "cuenta" => array("77", "67"), "montoTotal" => 0, "porcentajeTotal" => 0);

        $this->estilosExcel();

        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $tituloExcel);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);
        $i += 2;
        $iInicio = $i;
        if (!ObjectUtil::isEmpty($dataReporte) && !ObjectUtil::isEmpty($arrayConfiguracion)) {
            foreach ($arrayConfiguracion as $index => $item) {
                foreach ($item['cuenta'] as $value) {
                    $dataCuenta = Util::filtrarArrayPorColumna($dataReporte, "codigo", $value);
                    if (!ObjectUtil::isEmpty($dataCuenta)) {
                        $montoCalculado = Util::redondearNumero((!ObjectUtil::isEmpty($dataCuenta[0]["haber"]) ? $dataCuenta[0]["haber"] * 1 : 0) - (!ObjectUtil::isEmpty($dataCuenta[0]["debe"]) ? $dataCuenta[0]["debe"] * 1 : 0), 2);
                        if ($value === "70") {
                            $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
                            $montoVentas = $montoCalculado;
                        }

                        $porcentajeCalculado = Util::redondearNumero($montoCalculado / $montoVentas, 6) * 100;

                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, strtoupper($dataCuenta[0]["descripcion"]));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $montoCalculado);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $porcentajeCalculado);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                        $i += 1;
                    }
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $item['titulo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, '=SUM(G' . $iInicio . ':G' . ($i - 1) . ')');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, '=SUM(H' . $iInicio . ':H' . ($i - 1) . ')');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getBorders()->getTop()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_HAIR));
                $iInicio = $i;
                $i += 2;
            }
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:Z100')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
        for ($i = 'A'; $i <= 'H'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('EEFF');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $url = "util/formatos/$nombreExcel.xlsx";
        $objWriter->save(__DIR__ . '/../../' . $url);

        return $url;
    }

    public function obtenerDataInicialReporteSumasGeneralMensual($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $respuesta->dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $respuesta->dataCuentaNaturaleza = PlanContable::create()->obtenerCuentaNaturalezaActivos();
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataReporte = self::obtenerReporteSumasMensualGeneralXCriterios($empresaId, $dataPeriodoActual[0]['id']);
        return $respuesta;
    }

    public function obtenerDataInicialReporteSumasGeneralAnual($empresaId) {
        $respuesta->dataAnio = array(array("anio" => 2020), array("anio" => 2021), array("anio" => 2022), array("anio" => 2023));
        $respuesta->dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $respuesta->dataCuentaNaturaleza = PlanContable::create()->obtenerCuentaNaturalezaActivos();
        $anioActual = date("Y");
        $respuesta->dataAnioActual = $anioActual;
        $respuesta->dataReporte = self::obtenerReporteSumasAnualGeneralXCriterios($empresaId, $anioActual);
        return $respuesta;
    }

    public function obtenerReporteSumasMensualGeneralXCriterios($empresaId, $periodoId) {
        return ReporteContabilidad::create()->obtenerReporteSumasMensualGeneralXCriterios($empresaId, $periodoId);
    }

    public function obtenerReporteSumasAnualGeneralXCriterios($empresaId, $anio) {
        return ReporteContabilidad::create()->obtenerReporteSumasAnualGeneralXCriterios($empresaId, $anio);
    }

    public function obtenerReporteSumasGeneralExcel($empresaId, $valorBusquedad, $tipo) {
        $dataCuentaContableTitulo = PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
        $dataCuentaNaturaleza = PlanContable::create()->obtenerCuentaNaturalezaActivos();
        $tituloExcel = 'Balance de general : ';
        $nombreExcel = 'Balance_general_';

        if ($tipo == "1") {
            $dataReporte = self::obtenerReporteSumasMensualGeneralXCriterios($empresaId, $valorBusquedad);
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($valorBusquedad)->dataPeriodo;
            setlocale(LC_TIME, 'es_ES');
            $nombreMes = strftime('%B', DateTime::createFromFormat('!m', $dataPeriodo[0]['mes'])->getTimestamp());
            $tituloExcel .= ucwords($nombreMes) . " " . $dataPeriodo[0]['anio'];
            $nombreExcel .= $nombreMes . "_" . $dataPeriodo[0]['anio'];
        } elseif ($tipo == "2") {
            $dataReporte = self::obtenerReporteSumasAnualGeneralXCriterios($empresaId, $valorBusquedad);
            $tituloExcel .= $valorBusquedad;
            $nombreExcel .= $valorBusquedad;
        }

        $this->estilosExcel();

        $objPHPExcel = new PHPExcel();

        $i = 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . ($i + 1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $tituloExcel);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Debe Inicial');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Haber Inicial');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Mov. Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Mov. Haber');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Saldo Deudor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Saldo Acreedor');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;

        $inicioCalculo = $i;

        if (!ObjectUtil::isEmpty($dataCuentaContableTitulo) && !ObjectUtil::isEmpty($dataReporte)) {

            $dataCuentaContableTituloFilter = array_filter($dataCuentaContableTitulo, function ($element) {
                return $element['cuenta_naturaleza_id'] !== NULL;
            });

            $arrayTotal = array();
            foreach ($dataCuentaNaturaleza as $item) {
                $indicador = $item['id'] * 1;
                $arrayTotal[$indicador] = array(
                    "descripcion" => strtoupper($item["descripcion"]),
                    "banderaMostrar" => true,
                    "totalDebeInicial" => 0,
                    "totalHaberInicial" => 0,
                    "totalDebeMovimiento" => 0,
                    "totalHaberMovimiento" => 0,
                    "totalDebeSaldo" => 0,
                    "totalHaberSaldo" => 0
                );
            }

            $cuentaNaturalezaTipo = 0;
            $cuentaNaturalezaTipoOld = 0;

            foreach ($dataCuentaContableTituloFilter as $item) {

                $cuentaNaturalezaTipo = $item['cuenta_naturaleza_id'] * 1;
                if ($arrayTotal[$cuentaNaturalezaTipo]["banderaMostrar"]) {
                    if ($cuentaNaturalezaTipo !== 1) {
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, "TOTAL " . $arrayTotal[$cuentaNaturalezaTipoOld]["descripcion"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeInicial"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberInicial"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeMovimiento"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberMovimiento"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeSaldo"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberSaldo"]);

                        $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $i += 2;

                        $difInicial = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeInicial"] - $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberInicial"], 2);
                        $difMovimiento = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeMovimiento"] - $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberMovimiento"], 2);
                        $difSaldo = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipoOld]["totalDebeSaldo"] - $arrayTotal[$cuentaNaturalezaTipoOld]["totalHaberSaldo"], 2);

                        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, "SALDO " . $arrayTotal[$cuentaNaturalezaTipoOld]["descripcion"]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ($difInicial > 0 ? $difInicial : 0));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, ($difInicial < 0 ? abs($difInicial) : 0));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, ($difMovimiento > 0 ? $difMovimiento : 0));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, ($difMovimiento < 0 ? abs($difMovimiento) : 0));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, ($difSaldo > 0 ? $difSaldo : 0));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, ($difSaldo < 0 ? abs($difSaldo) : 0));

                        $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $i += 1;
                    }
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $arrayTotal[$cuentaNaturalezaTipo]["descripcion"]);
                    $i += 1;
                    $arrayTotal[$cuentaNaturalezaTipo]["banderaMostrar"] = false;
                }

                $codigoCuentaMayor = $item['codigo'];
                eval("\$dataFilter = array_filter(\$dataReporte, function (\$element) { return \substr(\$element['plan_contable_codigo'], 0, 2) == '$codigoCuentaMayor'; });");
                if (ObjectUtil::isEmpty($dataFilter)) {
                    continue;
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $codigoCuentaMayor);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $item['descripcion']);
                $i += 1;

                $totalDebeInicial = 0;
                $totalHaberInicial = 0;
                $totalDebeMovimiento = 0;
                $totalHaberMovimiento = 0;
                $totalDebeSaldo = 0;
                $totalHaberSaldo = 0;

                foreach ($dataFilter as $itemCuenta) {
                    $difDebeHaber = Util::redondearNumero(($itemCuenta['debe_inicial'] * 1) + ($itemCuenta['debe'] * 1) - ($itemCuenta['haber_inicial'] * 1) - ($itemCuenta['haber'] * 1), 2);
                    $saldoDebe = ($difDebeHaber > 0 ? $difDebeHaber : 0);
                    $saldoHaber = ($difDebeHaber < 0 ? abs($difDebeHaber) : 0);

                    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $itemCuenta['plan_contable_codigo']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $itemCuenta['plan_contable_descripcion']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $itemCuenta['debe_inicial']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $itemCuenta['haber_inicial']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $itemCuenta['debe']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $itemCuenta['haber']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $saldoDebe);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $saldoHaber);

                    $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $i += 1;

                    $totalDebeInicial = Util::redondearNumero($totalDebeInicial + ($itemCuenta['debe_inicial'] * 1), 2);
                    $totalHaberInicial = Util::redondearNumero($totalHaberInicial + ($itemCuenta['haber_inicial'] * 1), 2);
                    $totalDebeMovimiento = Util::redondearNumero($totalDebeMovimiento + ($itemCuenta['debe'] * 1), 2);
                    $totalHaberMovimiento = Util::redondearNumero($totalHaberMovimiento + ($itemCuenta['haber'] * 1), 2);
                    $totalDebeSaldo = Util::redondearNumero($totalDebeSaldo + $saldoDebe, 2);
                    $totalHaberSaldo = Util::redondearNumero($totalHaberSaldo + $saldoHaber, 2);
                }
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $totalDebeInicial);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $totalHaberInicial);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $totalDebeMovimiento);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalHaberMovimiento);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $totalDebeSaldo);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $totalHaberSaldo);

                $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $i += 2;

                $arrayTotal[$cuentaNaturalezaTipo]["totalDebeInicial"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeInicial"] + $totalDebeInicial, 2);
                $arrayTotal[$cuentaNaturalezaTipo]["totalHaberInicial"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalHaberInicial"] + $totalHaberInicial, 2);
                $arrayTotal[$cuentaNaturalezaTipo]["totalDebeMovimiento"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeMovimiento"] + $totalDebeMovimiento, 2);
                $arrayTotal[$cuentaNaturalezaTipo]["totalHaberMovimiento"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalHaberMovimiento"] + $totalHaberMovimiento, 2);
                $arrayTotal[$cuentaNaturalezaTipo]["totalDebeSaldo"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeSaldo"] + $totalDebeSaldo, 2);
                $arrayTotal[$cuentaNaturalezaTipo]["totalHaberSaldo"] = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalHaberSaldo"] + $totalHaberSaldo, 2);
                $cuentaNaturalezaTipoOld = $cuentaNaturalezaTipo;
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, "TOTAL " . $arrayTotal[$cuentaNaturalezaTipo]["descripcion"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalDebeInicial"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalHaberInicial"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalDebeMovimiento"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalHaberMovimiento"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalDebeSaldo"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $arrayTotal[$cuentaNaturalezaTipo]["totalHaberSaldo"]);

            $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 2;

            $difInicial = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeInicial"] - $arrayTotal[$cuentaNaturalezaTipo]["totalHaberInicial"], 2);
            $difMovimiento = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeMovimiento"] - $arrayTotal[$cuentaNaturalezaTipo]["totalHaberMovimiento"], 2);
            $difSaldo = Util::redondearNumero($arrayTotal[$cuentaNaturalezaTipo]["totalDebeSaldo"] - $arrayTotal[$cuentaNaturalezaTipo]["totalHaberSaldo"], 2);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, "SALDO " . $arrayTotal[$cuentaNaturalezaTipo]["descripcion"]);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ($difInicial > 0 ? $difInicial : 0));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, ($difInicial < 0 ? abs($difInicial) : 0));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, ($difMovimiento > 0 ? $difMovimiento : 0));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, ($difMovimiento < 0 ? abs($difMovimiento) : 0));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, ($difSaldo > 0 ? $difSaldo : 0));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, ($difSaldo < 0 ? abs($difSaldo) : 0));

            $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 2;

            $sumaTotalDebeInicial = 0;
            $sumaTotalHaberInicial = 0;
            $sumaTotalDebeMovimiento = 0;
            $sumaTotalHaberMovimiento = 0;
            $sumaTotalDebeSaldo = 0;
            $sumaTotalHaberSaldo = 0;

            foreach ($dataCuentaNaturaleza as $item) {
                $indicador = $item['id'] * 1;
                $sumaTotalDebeInicial = Util::redondearNumero($arrayTotal[$indicador]["totalDebeInicial"] + $sumaTotalDebeInicial, 2);
                $sumaTotalHaberInicial = Util::redondearNumero($arrayTotal[$indicador]["totalHaberInicial"] + $sumaTotalHaberInicial, 2);
                $sumaTotalDebeMovimiento = Util::redondearNumero($arrayTotal[$indicador]["totalDebeMovimiento"] + $sumaTotalDebeMovimiento, 2);
                $sumaTotalHaberMovimiento = Util::redondearNumero($arrayTotal[$indicador]["totalHaberMovimiento"] + $sumaTotalHaberMovimiento, 2);
                $sumaTotalDebeSaldo = Util::redondearNumero($arrayTotal[$indicador]["totalDebeSaldo"] + $sumaTotalDebeSaldo, 2);
                $sumaTotalHaberSaldo = Util::redondearNumero($arrayTotal[$indicador]["totalHaberSaldo"] + $sumaTotalHaberSaldo, 2);
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, "TOTAL REPORTE");
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $sumaTotalDebeInicial);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $sumaTotalHaberInicial);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $sumaTotalDebeMovimiento);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $sumaTotalHaberMovimiento);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $sumaTotalDebeSaldo);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $sumaTotalHaberSaldo);

            $objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;

            $objPHPExcel->getActiveSheet()->getStyle('A' . $inicioCalculo . ':H' . $i)->applyFromArray($this->estiloInformacion);
        }

        for ($i = 'A'; $i <= 'H'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Balance de sumas');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $url = "util/formatos/$nombreExcel.xlsx";
        $objWriter->save(__DIR__ . '/../../' . $url);

        return $url;
    }

}
