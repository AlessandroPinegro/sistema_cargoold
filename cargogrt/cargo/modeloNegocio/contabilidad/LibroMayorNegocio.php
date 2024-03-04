<?php

require_once __DIR__ . '/../../modelo/contabilidad/LibroMayor.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContLibroNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/LibroDiarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class LibroMayorNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return LibroMayorNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicialLibroMayorAuxiliar($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataPersonaActiva = PersonaNegocio::create()->obtenerActivas();
        $respuesta->dataCuentasContables = PlanContableNegocio::create()->obtenerTodo($empresaId);
        $respuesta->dataLibroMayor = self::listarLibroMayorXCriterios(array(array("empresa" => $empresaId, "periodoInicio" => $dataPeriodoActual[0]['id'])));
        return $respuesta;
    }

    public function obtenerConfiguracionInicialLibroMayorGeneral($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = $dataPeriodoActual;
        $respuesta->dataCuentasContables = PlanContableNegocio::create()->obtenerTodo($empresaId);
        $dataSaldoInicial = LibroMayor::create()->saldoInicialXEmpresaIdXPeriodoId($empresaId, $dataPeriodoActual[0]['id']);
        $respuesta->dataLibroMayor = self::listarLibroMayorXCriterios(array(array("empresa" => $empresaId, "periodoInicio" => $dataPeriodoActual[0]['id'])));
        return $respuesta;
    }

    public function listarLibroMayorXCriterios($criterios) {
        if (!ObjectUtil::isEmpty($criterios[0]['empresa'])) {
            $empresaId = $criterios[0]['empresa'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['persona'])) {
            $personaId = $criterios[0]['persona'];
        } else {
            $personaId = null;
        }

        if (!ObjectUtil::isEmpty($criterios[0]['periodoInicio'])) {
            $periodoIdInicio = $criterios[0]['periodoInicio'];
        }

        if (!ObjectUtil::isEmpty($criterios[0]['periodoFin'])) {
            $periodoIdFin = $criterios[0]['periodoFin'];
        }
        if (!ObjectUtil::isEmpty($criterios[0]['planContableCodigo'])) {
            $planContableCodigo = $criterios[0]['planContableCodigo'];
        }

        $dataSaldoInicial = LibroMayor::create()->saldoInicialXEmpresaIdXPeriodoId($empresaId, $periodoIdInicio, $personaId, $planContableCodigo);
        $data = LibroMayor::create()->listarLibroMayorXCriterios($empresaId, $personaId, $periodoIdInicio, $periodoIdFin, $planContableCodigo);
        if (!ObjectUtil::isEmpty($dataSaldoInicial) && !ObjectUtil::isEmpty($data)) {
            $data = array_merge($dataSaldoInicial, $data);
        } elseif (!ObjectUtil::isEmpty($dataSaldoInicial)) {
            $data = $dataSaldoInicial;
        }
        return $data;
    }

    public function obtenerLibroMayorAuxiliarExcel($criterios) {
        $data = self::listarLibroMayorXCriterios($criterios);
        $dataCuentasContables = PlanContableNegocio::create()->obtenerXEmpresaId($criterios[0]['empresa']);
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException('No existen registros por exportar.');
        }


        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Libro mayor auxiliar');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Registro');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Libro');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Dólares');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Haber');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Concepto');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;


        foreach ($dataCuentasContables as $cuentaContable) {
            $arrayFiltrado = array();
            $arrayFiltrado = Util::filtrarArrayPorColumna($data, 'plan_contable_id', $cuentaContable['id']);
            $totalDolaresCuenta = 0;
            $totalDebeCuenta = 0;
            $totalHaberCuenta = 0;

            if (!ObjectUtil::isEmpty($arrayFiltrado) && count($arrayFiltrado) > 0) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $cuentaContable['codigo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $cuentaContable['descripcion']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);
                $i += 1;

                $arrayPersona = Util::obtenerArrayUnicoXNombreCampo($arrayFiltrado, 'persona_id');
                foreach ($arrayPersona as $itemPersona) {
                    $informacionPersona = Util::filtrarArrayPorColumna($arrayFiltrado, 'persona_id', $itemPersona);
                    $codigoIdentificacion = (!ObjectUtil::isEmpty($informacionPersona[count($informacionPersona) - 1]['persona_codigo_identificacion']) ? $informacionPersona[count($informacionPersona) - 1]['persona_codigo_identificacion'] : "");
                    $nombreProveedor = (!ObjectUtil::isEmpty($informacionPersona[count($informacionPersona) - 1]['persona_nombre_completo']) ? $informacionPersona[count($informacionPersona) - 1]['persona_nombre_completo'] : "OTROS");

                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $codigoIdentificacion, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $nombreProveedor, PHPExcel_Cell_DataType::TYPE_STRING);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);
                    $i += 1;

                    $arrayDetalle = Util::filtrarArrayPorColumna($arrayFiltrado, 'persona_id', $itemPersona);
                    $totalDolaresPersona = 0;
                    $totalDebePersona = 0;
                    $totalHaberPersona = 0;

                    foreach ($arrayDetalle as $itemDetalle) {
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, explode("" . $itemDetalle["libro_clasificacion"] . "-" . $itemDetalle['libro_codigo'] . '-', $itemDetalle['cuo'])[1]);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $itemDetalle['documento_cuo']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $itemDetalle['documento_referencia']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, DateUtil::formatearFechaBDAaCadenaVw(substr($itemDetalle['fecha_contabilizacion'], 0, 10)));
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $itemDetalle['libro_codigo']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $itemDetalle['monto_dolares']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $itemDetalle['debe_soles']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $itemDetalle['haber_soles']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $itemDetalle['glosa']);

                        if (($itemDetalle['moneda_id'] * 1) == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                            $totalDolaresPersona += Util::redondearNumero(($itemDetalle['debe_dolares'] * 1) + ($itemDetalle['haber_dolares'] * -1), 6);
                        }

                        $totalDebePersona += $itemDetalle['debe_soles'] * 1;
                        $totalHaberPersona += $itemDetalle['haber_soles'] * 1;

                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->applyFromArray($this->estiloInformacion);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->applyFromArray($this->estiloNumInformacion);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $i += 1;
                    }

                    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Saldo :');
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, Util::redondearNumero(($totalDebePersona - $totalHaberPersona), 6));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalDolaresPersona);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $totalDebePersona);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $totalHaberPersona);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloSubTotalesFilas);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $objPHPExcel->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $i += 1;

                    $totalDolaresCuenta = Util::redondearNumero($totalDolaresCuenta + $totalDolaresPersona, 6);
                    $totalDebeCuenta += $totalDebePersona;
                    $totalHaberCuenta += $totalHaberPersona;
                }

                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Saldo Cuenta :');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, Util::redondearNumero($totalDebeCuenta - $totalHaberCuenta, 6));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalDolaresCuenta);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $totalDebeCuenta);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $totalHaberCuenta);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloSubTotalesFilas);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i . ':H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $i += 1;

                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);
                $i += 1;
            }
        }

        for ($i = 'A'; $i <= 'I'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Libro de mayor auxiliar');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/libroMayorAuxiliar.xlsx');

        return 1;
    }

    public function obtenerLibroMayorGeneralExcel($criterios) {
        $data = self::listarLibroMayorXCriterios($criterios);
        $dataCuentasContables = PlanContableNegocio::create()->obtenerTodo($criterios[0]['empresa']);
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();
        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Libro mayor general');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);
        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Área');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Relación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Debe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Haber');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Glosa');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;

        $totalDebe = 0;
        $totalHaber = 0;
        foreach ($dataCuentasContables as $cuentaContable) {

            $arrayFiltrado = Util::filtrarArrayPorColumna($data, 'plan_contable_id', $cuentaContable['id']);
            $totalDebeCuenta = 0;
            $totalHaberCuenta = 0;
            if (!ObjectUtil::isEmpty($arrayFiltrado) && count($arrayFiltrado) > 0) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $cuentaContable['codigo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $cuentaContable['descripcion']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloColumnas);
                $i += 1;

                foreach ($arrayFiltrado as $itemDetalle) {


                    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, (!ObjectUtil::isEmpty($itemDetalle['libro_codigo']) ? explode($itemDetalle['libro_codigo'] . '-', $itemDetalle['cuo'])[1] : ""));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $itemDetalle['libro_codigo']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, DateUtil::formatearFechaBDAaCadenaVw(substr($itemDetalle['fecha_contabilizacion'], 0, 10)));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, (!ObjectUtil::isEmpty($itemDetalle['persona_codigo_identificacion']) ? $itemDetalle['persona_codigo_identificacion'] . " | " . $itemDetalle['persona_nombre_completo'] : ""));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $itemDetalle['debe_soles']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $itemDetalle['haber_soles']);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $itemDetalle['glosa']);

                    $totalDebeCuenta += $itemDetalle['debe_soles'] * 1;
                    $totalHaberCuenta += $itemDetalle['haber_soles'] * 1;

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);
                    $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray($this->estiloInformacion);
                    $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':F' . $i)->applyFromArray($this->estiloNumInformacion);
                    $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':F' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $i += 1;
                }
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Saldo ' . $cuentaContable['codigo'] . ":");
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, Util::redondearNumero($totalDebeCuenta - $totalHaberCuenta, 6));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $totalDebeCuenta);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalHaberCuenta);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloSubTotalesFilas);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':F' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $i += 1;

                $totalDebe += $totalDebeCuenta;
                $totalHaber += $totalHaberCuenta;
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloInformacion);
        $i += 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'TOTALES :');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, Util::redondearNumero($totalDebe - $totalHaber, 6));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $totalDebe);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $totalHaber);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloSubTotalesFilas);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':F' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $i += 1;

        for ($i = 'A'; $i <= 'I'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Libro de mayor general');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/libroMayorGeneral.xlsx');

        return array(array("vout_exito" => "1"));
    }

    public function obtenerLibroMayorGeneralTxt($criterios) {

        $data = LibroMayor::create()->listarLibroMayorXCriterios($criterios[0]['empresa'], NULL, $criterios[0]['periodoInicio'], NULL, NULL);

        $dataLibros = ContLibroNegocio::create()->obtenerXClasificacion();
        if (!ObjectUtil::isEmpty($data) && !ObjectUtil::isEmpty($dataLibros)) {

            $periodo = $data[0]['periodo'];
            $archivoNombre = "LE20600143361$periodo" . "00060100001111.TXT";
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
                            // linea 1
                            $linea = $detalleVoucher["periodo"] . "00|";
//                            $correlativoCuo = (int) trim(str_replace("".$detalleVoucher["libro_clasificacion"] . "-" . $detalleVoucher["libro_codigo"] . "-", "", str_replace("-" . $detalleVoucher["periodo"], "", $detalleVoucher["cuo"])));
//                            $cuo = $detalleVoucher["libro_codigo"] . "-" . $correlativoCuo;
                            $cuo = $detalleVoucher["cuo"];

                            // linea 2
                            $linea .= str_pad($cuo, 40, Util::rellenarEspacios(40)) . "|";

                            $codigoLinea = 'M';
                            if ($detalleVoucher['cont_libro_id'] == 18) {
                                $codigoLinea = 'A';
                            } elseif ($detalleVoucher['cont_libro_id'] == 19) {
                                $codigoLinea = 'C';
                            }
                            $numeroLinea = $codigoLinea . str_pad($detalleVoucher["linea"], 4, '0', STR_PAD_LEFT);
                            // linea 3
                            $linea .= str_pad($numeroLinea, 10, Util::rellenarEspacios(10)) . "|";
                            // linea 4
                            $linea .= str_pad($detalleVoucher["plan_contable_codigo"], 20, Util::rellenarEspacios(20)) . "|";
                            // linea 5
                            $linea .= str_pad("", 24, Util::rellenarEspacios(24)) . "|";
                            // linea 6
                            $linea .= str_pad("", 24, Util::rellenarEspacios(24)) . "|";
                            // linea 7
                            $linea .= "PEN|";
                            // linea 8 y 9 
                            $personaCodigoIdentificacion = $detalleVoucher["persona_codigo_identificacion"];
                            $personaTipoCodigo = $detalleVoucher["persona_codigo_identificacion_tipo"];
                            if (!ObjectUtil::isEmpty($personaTipoCodigo) && !ObjectUtil::isEmpty($personaCodigoIdentificacion) && is_numeric($personaCodigoIdentificacion) && $detalleVoucher["documento_tipo_sunat"] * 1 !== 10) {
                                if ($personaCodigoIdentificacion == "00000000002" || $personaCodigoIdentificacion == "00000000001" || $personaCodigoIdentificacion == "00000000003") {
                                    $personaTipoCodigo = "0";
                                }
                                $linea .= $personaTipoCodigo . "|";
                                $linea .= str_pad($personaCodigoIdentificacion, 15, Util::rellenarEspacios(15)) . "|";
                            } else {
                                $linea .= "0|";
                                $linea .= str_pad("0", 15, Util::rellenarEspacios(15)) . "|";
                            }
                            // linea 10,11 y 12
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

                            $fechaDocumento = "01/01/0001";
                            $fechaVencimiento = "01/01/0001";
                            if (!ObjectUtil::isEmpty($detalleVoucher["documento_tipo_sunat"]) && !ObjectUtil::isEmpty($detalleVoucher['documento_fecha_emision'])) {
                                $fechaDocumento = DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['documento_fecha_emision']);
                            }

                            if (!ObjectUtil::isEmpty($detalleVoucher["documento_tipo_sunat"]) && !ObjectUtil::isEmpty($detalleVoucher['documento_fecha_vencimiento'])) {
                                $fechaVencimiento = DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['documento_fecha_vencimiento']);
                            }
                            // linea 13,14 y 15    
                            $linea .= DateUtil::formatearFechaBDAaCadenaVw($detalleVoucher['fecha_contabilizacion']) . "|";
                            $linea .= $fechaVencimiento . "|";
                            $linea .= $fechaDocumento . "|";

                            // linea 16
                            $glosa = substr(trim(Util::normaliza($detalleVoucher["glosa"])), 0, 200);
                            $linea .= str_pad($glosa, 200, Util::rellenarEspacios(200)) . "|";

                            // linea 17
                            $linea .= str_pad("", 200, Util::rellenarEspacios(200)) . "|";
                            $montoDebe = str_replace(",", "", number_format($detalleVoucher["debe_soles"], 2));
                            $montoHaber = str_replace(",", "", number_format($detalleVoucher["haber_soles"], 2));
                            // linea 18 y 19
                            $linea .= str_pad($montoDebe, 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";
                            $linea .= str_pad($montoHaber, 12, Util::rellenarEspacios(12), STR_PAD_LEFT) . "|";

                            $estructura = "140100"; //Si no es compras va este codigo.
                            if (($detalleVoucher["libro_clasificacion"] * 1) == ContLibro::CLASIFICACION_COMPRAS) {
                                $estructura = "080100";
                            }
                            $estructura .= "&" . $detalleVoucher["periodo"] . "00&" . $cuo . "&" . $numeroLinea;

                            // linea 20
                            $linea .= str_pad($estructura, 92, Util::rellenarEspacios(92)) . "|";
                            // linea 21
                            $linea .= "1|";
                            // linea 22
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

    /**
     * 
     * @param PHPExcel_IOFactory $excel
     * @param type $excelNombre
     * @param type $anio
     * @param type $mes
     * @param type $usuarioId
     * @throws WarningException
     */
    public function genera($path, $excelNombre, $anio, $mes, $usuarioId) {
        $excel = new Spreadsheet_Excel_Reader();
        $excel->setUTFEncoder('iconv');
        $excel->setOutputEncoding('UTF-8');
        $excel->read($path);
        $cells = $excel->sheets[0]["cells"];
//var_dump($cells);
//return;
        if (ObjectUtil::isEmpty($cells)) {
            throw new WarningException("No se ha especificado un excel correcto");
        }

        LibroTemp::create()->eliminar8();

        foreach ($cells as $key => $value) {
            if ($key > 1) {
                LibroTemp::create()->guardar8($anio . $mes . "00", $this->obtenerCelda($cells, 1, $key), "", $this->obtenerCelda($cells, 2, $key, false), $this->obtenerCelda($cells, 3, $key), $this->obtenerCelda($cells, 4, $key), $this->obtenerCelda($cells, 5, $key), $this->obtenerImporte($this->obtenerCelda($cells, 6, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 7, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 8, $key, false)), $this->obtenerCelda($cells, 9, $key), $this->obtenerCelda($cells, 10, $key), $this->obtenerCelda($cells, 11, $key), $this->obtenerCelda($cells, 12, $key), $this->obtenerImporte($this->obtenerCelda($cells, 13, $key, false)), $this->obtenerCelda($cells, 14, $key), $this->obtenerImporte($this->obtenerCelda($cells, 15, $key, false)), $this->obtenerCelda($cells, 16, $key), $this->obtenerCelda($cells, 17, $key, false), $this->obtenerCelda($cells, 18, $key, false), $this->obtenerCelda($cells, 19, $key, false), $this->obtenerCelda($cells, 20, $key, false), $this->obtenerCelda($cells, 21, $key, false), $this->obtenerCelda($cells, 22, $key), $this->obtenerCelda($cells, 23, $key), $this->obtenerImporte($this->obtenerCelda($cells, 24, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 25, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 26, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 27, $key, false)), $this->obtenerImporte($this->obtenerCelda($cells, 28, $key, false)), $this->obtenerCelda($cells, 29, $key), $this->obtenerCelda($cells, 30, $key), $this->obtenerCelda($cells, 31, $key), $this->obtenerCelda($cells, 32, $key), $this->obtenerCelda($cells, 33, $key), $this->obtenerCelda($cells, 34, $key), $this->obtenerCelda($cells, 35, $key)
                );
            }
        }

        $archivoNombre = "LE20600143361$anio$mes" . "00080200001111.TXT";
        $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, null);
        $file = fopen($direccion, "w");
        $direccion = "\xEF\xBB\xBF" . $direccion;
        $lista = LibroTemp::create()->listar8();
        foreach ($lista as $linea) {
            fwrite($file, $linea["fila"] . PHP_EOL);
        }
        //fwrite($file, "Otra más" . PHP_EOL);
        fclose($file);

        return $archivoNombre;
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

}
