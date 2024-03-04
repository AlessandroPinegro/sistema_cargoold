<?php

require_once __DIR__ . '/../../modelo/contabilidad/RegistroVentas.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContLibroNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class RegistroVentasNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return RegistroVentasNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataPersonaActiva = PersonaNegocio::create()->obtenerActivas();
        $respuesta->dataLibro = ContLibroNegocio::create()->obtenerXClasificacion(ContLibroNegocio::CLASIFICACION_VENTAS);
        $respuesta->dataRegistroVentas = self::listarRegistroVentasXCriterios(array(array("empresa" => $empresaId, "periodoInicio" => $dataPeriodoActual[0]['id'])));
        return $respuesta;
    }

    public function listarRegistroVentasXCriterios($criterios) {
        if (!ObjectUtil::isEmpty($criterios[0]['empresa'])) {
            $empresaId = $criterios[0]['empresa'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['persona'])) {
            $personaId = $criterios[0]['persona'];
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

        return RegistroVentas::create()->listarRegistroVentasXCriterios($empresaId, $personaId, $periodoIdInicio, $periodoIdFin, $fechaInicio, $fechaFin);
    }

    public function obtenerRegistroVentasTxt($criterios) {
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXid($criterios[0]['periodoInicio']);
        $data = self::listarRegistroVentasXCriterios($criterios);

        $periodo = $dataPeriodo->dataPeriodo[0]['anio'] . str_pad($dataPeriodo->dataPeriodo[0]['mes'], 2, '0', STR_PAD_LEFT);

        $banderaExisteRegistros = '1';
        if (ObjectUtil::isEmpty($data)) {
            $banderaExisteRegistros = '0';
        }
        $archivoNombre = "LE20600143361$periodo" . "00140100001" . $banderaExisteRegistros . "11.TXT";

        $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, null);
        $file = fopen($direccion, "w");
        $direccion = "\xEF\xBB\xBF" . $direccion;

        if (!ObjectUtil::isEmpty($data)) {
            foreach ($data as $item) {

                $banderaDocumentoAnulado = false;
                if ($item['documento_estado'] == 0) {
                    $banderaDocumentoAnulado = true;
                }


                $serieNumero = explode("-", $item["serie_numero"]);
                $serie = trim($serieNumero[0]);
                $numero = (int) trim($serieNumero[1]);
                $correlativo = explode($item["libro_codigo"] . "-", $item["cuo"])[1];
                $correlativoMovimiento = substr(str_replace("-", "", $correlativo), 1);
                $linea = "";
                // linea = 1                
                $linea = $item["periodo"] . "00|";
                // linea = 2
                $linea .= str_pad($item["cuo"], 40, Util::rellenarEspacios(40)) . "|";
                // linea = 3
                $linea .= str_pad("M" . $correlativoMovimiento, 10, Util::rellenarEspacios(10)) . "|";
                // linea = 4
                $linea .= DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']) . "|";
                // linea = 5
                $linea .= DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']) . "|";
                // $linea .= DateUtil::formatearFechaBDAaCadenaVw((ObjectUtil::isEmpty($item['fecha_vencimiento']) ? '0001-01-01' : $item['fecha_vencimiento'])) . "|";
                // linea = 6
                $linea .= $item["tipo_documento"] . "|";
                // linea = 7
                $linea .= str_pad($serie, 20, Util::rellenarEspacios(20)) . "|";
                // linea = 8
                $linea .= str_pad($numero, 20, Util::rellenarEspacios(20)) . "|";
                // linea = 9
                $linea .= str_pad("", 20, Util::rellenarEspacios(20)) . "|";


                if (!$banderaDocumentoAnulado) {
                    // linea = 10
                    $linea .= $item["persona_tipo_codigo"] . "|";
                    // linea = 11
                    $linea .= str_pad($item["codigo_identificacion_persona"], 15, Util::rellenarEspacios(15)) . "|";
                    // linea = 12
                    $linea .= str_pad($item["nombre_persona"], 100, Util::rellenarEspacios(100 + substr_count(strtoupper($item["nombre_persona"]), 'Ñ'))) . "|";
                    // linea = 13
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 14
                    $linea .= str_pad(str_replace(",", "", (number_format($item['sub_total'], 2))), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 15
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 16
                    $linea .= str_pad(str_replace(",", "", (number_format($item['igv'], 2))), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 17
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 18
                    $montoNoAfecto = $item['noafecto'];
                    if ($item['movimiento_tipo_codigo'] == "18" && $item['total'] == 0) {
                        $montoNoAfecto = 0;
                    }
                    $linea .= str_pad(str_replace(",", "", (number_format($montoNoAfecto, 2))), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 19
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 20
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 21
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 22
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 23
                    // linea = 23
                    $linea .= str_pad(number_format(0, 2), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 24
                    $linea .= str_pad(str_replace(",", "", (number_format($item['total'], 2))), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    // linea = 25,26

                    if ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                        $linea .= "USD|" . number_format($item['tipo_cambio'], 3) . "|";
                    } elseif ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_SOLES) {
                        $linea .= "PEN|" . number_format($item['tipo_cambio'], 3) . "|";
                    }
                    //COMPROBANTE QUE MODIFICAR NOTA DE CRÉDITO / DÉBITO

                    if (!ObjectUtil::isEmpty($item['documento_relacion_fecha_emision'])) {
                        $serieNumeroRelacion = explode("-", $item["documento_relacion_serie_numero"]);

                        // linea = 27 // FECHA DE EMISIÓN
                        $linea .= DateUtil::formatearFechaBDAaCadenaVw($item['documento_relacion_fecha_emision']) . "|";
                        // linea = 28
                        $linea .= $item['documento_relacion_tipo_documento'] . "|"; // TIPO COMPROBANTE
                        // linea = 29
                        $linea .= str_pad(trim($serieNumeroRelacion[0]), 20, Util::rellenarEspacios(20)) . "|"; // SERIE
                        // linea = 30                  
                        $linea .= str_pad((int) $serieNumeroRelacion[1], 20, Util::rellenarEspacios(20)) . "|"; // CORRELATIVO
                    } else {
                        $linea .= "01/01/0001|";
                        $linea .= "00|";
                        $linea .= str_pad("-", 20, Util::rellenarEspacios(20)) . "|";
                        $linea .= str_pad("-", 20, Util::rellenarEspacios(20)) . "|";
                    }
                    // linea = 31
                    $linea .= str_pad("", 12, Util::rellenarEspacios(12)) . "|"; // PROYECTOS
                    // linea = 32
                    $linea .= " |";
                    // linea = 33
//                    $linea .= $item["documento_estado"] . "|";
                    $linea .= "1|"; // Indicador de Comprobantes de pago cancelados con medios de pago cancelados con medios de pago
                    // linea = 34 
                    //if (!ObjectUtil::isEmpty($item['igv']) && abs($item['igv'] * 1) > 0) {
                    $fechaEmisionExplode = explode("-", $item["fecha_emision"]);
                    // 0 => año 1=>mes
                    if (($fechaEmisionExplode[0] . $fechaEmisionExplode[1]) == $item['periodo']) {
                        $linea .= "1|";
                    } else {
                        $linea .= "8|";
                    }
                    //} else {
                    //    $linea .= "0|";
                    // }
                }
                if ($banderaDocumentoAnulado) {
                    // linea = 10
                    $linea .= "0|";
                    // linea = 11
                    $linea .= str_pad("0 ", 15, Util::rellenarEspacios(15)) . "|";
                    // linea = 12
                    $linea .= str_pad("ANULADO", 100, Util::rellenarEspacios(100)) . "|";

                    for ($i = 0; $i <= 12; $i++) {
                        // linea = 24
                        $linea .= str_pad(str_replace(",", "", (number_format(0, 2))), 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                    }
                    // linea = 25,26
                    if ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                        $linea .= "USD|" . number_format($item['tipo_cambio'], 3) . "|";
                    } elseif ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_SOLES) {
                        $linea .= "PEN|" . number_format($item['tipo_cambio'], 3) . "|";
                    }
                    //COMPROBANTE QUE MODIFICAR NOTA DE CRÉDITO / DÉBITO

                    if (!ObjectUtil::isEmpty($item['documento_relacion_fecha_emision'])) {
                        $serieNumeroRelacion = explode("-", $item["documento_relacion_serie_numero"]);

                        // linea = 27 // FECHA DE EMISIÓN
                        $linea .= DateUtil::formatearFechaBDAaCadenaVw($item['documento_relacion_fecha_emision']) . "|";
                        // linea = 28
                        $linea .= $item['documento_relacion_tipo_documento'] . "|"; // TIPO COMPROBANTE
                        // linea = 29
                        $linea .= str_pad(trim($serieNumeroRelacion[0]), 20, Util::rellenarEspacios(20)) . "|"; // SERIE
                        // linea = 30                  
                        $linea .= str_pad($serieNumeroRelacion[1] * 1, 20, Util::rellenarEspacios(20)) . "|"; // CORRELATIVO
                    } else {
                        $linea .= "01/01/0001|";
                        $linea .= "00|";
                        $linea .= str_pad("-", 20, Util::rellenarEspacios(20)) . "|";
                        $linea .= str_pad("-", 20, Util::rellenarEspacios(20)) . "|";
                    }
                    // linea = 31
                    $linea .= str_pad("", 12, Util::rellenarEspacios(12)) . "|"; // PROYECTOS
                    // linea = 32
                    $linea .= " |";
                    // linea = 33
                    $linea .= "1|"; // Indicador de Comprobantes de pago cancelados con medios de pago cancelados con medios de pago
                    // linea = 34 
                    $linea .= "2|";
                }

                // linea = 35 => Libre
                $linea .= str_pad("", 200, Util::rellenarEspacios(200));
                $lineaSalida = mb_convert_encoding($linea, "ISO-8859-1");

                fwrite($file, $lineaSalida . "\r\n");
            }
        }
        fclose($file);
        return $archivoNombre;
    }

    public function obtenerRegistroVentasExcel($criterios) {

        $data = self::listarRegistroVentasXCriterios($criterios);


        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Registro de ventas');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;


        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':C' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'DOCUMENTO');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'CLIENTE');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L' . $i . ':N' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'COMPROBANTE DE PAGO QUE MODIFICA');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'R.U.C.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Nombre');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Monto en Dólares');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Tipo de Cambio');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Exonerado');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Afectos');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'I.G.V.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Precio Venta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'Serie y Número');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        $montoTotalDolares = 0;
        $montoTotalGravada = 0;
        $montoTotalExonerado = 0;
        $montoTotalIgv = 0;

        $montoTotalDolaresLibro = 0;
        $montoTotalExoneradoLibro = 0;
        $montoTotalGravadaLibro = 0;
        $montoTotalIgvLibro = 0;
        $montoTotalVentassLibro = 0;
        foreach ($data as $reporte) {

            $banderaDocumentoAnulado = false;

            if ($reporte['documento_estado'] == 0) {
                $banderaDocumentoAnulado = TRUE;
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'ANULADO');

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, DateUtil::formatearFechaBDAaCadenaVw($reporte['fecha_emision']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['tipo_documento']);

            if (!$banderaDocumentoAnulado) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['codigo_identificacion_persona']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['nombre_persona']);
                $montoTotalDolaresLibro += $reporte['total_dolares'] * 1;
                $montoTotalDolares += $montoTotalDolaresLibro;
                if ($reporte['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['total_dolares']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['tipo_cambio']);
                }

                $montoNoAfecto = $reporte['noafecto'] * 1;
                if ($reporte['movimiento_tipo_codigo'] == "18" && $reporte['total'] == 0) {
                    $montoNoAfecto = 0;
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $montoNoAfecto);
                $montoTotalExoneradoLibro += $montoNoAfecto;
                $montoTotalExonerado += $montoTotalExoneradoLibro;

                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['sub_total']);
                $montoTotalGravadaLibro += $reporte['sub_total'] * 1;
                $montoTotalGravada += $montoTotalGravadaLibro;

                $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $reporte['igv']);
                $montoTotalIgvLibro += $reporte['igv'] * 1;
                $montoTotalIgv += $montoTotalIgvLibro;

                $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $reporte['total']);
                $montoTotalVentassLibro += $reporte['total'] * 1;

                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0.0000');


                $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, DateUtil::formatearFechaBDAaCadenaVw($reporte['documento_relacion_fecha_emision']));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, $reporte['documento_relacion_tipo_documento']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, $reporte['documento_relacion_serie_numero']);
            }

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->estiloInformacion);
            $i += 1;
        }

        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $montoTotalDolaresLibro);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $montoTotalExoneradoLibro);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $montoTotalGravadaLibro);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $montoTotalIgvLibro);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $montoTotalVentassLibro);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->estiloSubTotalesFilas);
        $i += 1;



        for ($i = 'A'; $i <= 'N'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Reporte de ventas');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/registroVentas.xlsx');

        return 1;
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
