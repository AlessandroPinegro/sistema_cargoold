<?php

require_once __DIR__ . '/../../modelo/contabilidad/PlanContable.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContLibroNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class PlanContableNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return PlanContableNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarPlanContablePadres($empresaId) {
        return PlanContable::create()->listarPlanContablePadres($empresaId);
    }

    public function obtenerHijos($padreId) {
        return PlanContable::create()->obtenerHijos($padreId);
    }

    public function obtenerHijosSinCriterios() {
        return PlanContable::create()->obtenerHijosSinCriterios();
    }

    public function obtenerHijosXCriterios($anio, $mes, $empresaId) {
        return PlanContable::create()->obtenerHijosXCriterios($anio, $mes, $empresaId);
    }

    public function obtenerConfiguracionesIniciales($empresaId) {

        $respuesta->cuentaTipo = PlanContable::create()->obtenerCuentaTipoActivos();
        $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $respuesta->dimension = PlanContable::create()->obtenerDimensionActivos();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $respuesta->cuentaExige = PlanContable::create()->obtenerCuentaExigeActivos();
        $respuesta->cuentaNaturaleza = PlanContable::create()->obtenerCuentaNaturalezaActivos();
        $respuesta->cuentas = PlanContable::create()->obtenerCuentasAsientosAutomaticos();

        return $respuesta;
    }

    public function obtenerCuentaXId($id) {
        return PlanContable::create()->obtenerCuentaXId($id);
    }

    public function guardarCuenta($codigo, $descripcion, $codigoEqui, $descripcionEqui, $estado, $cuentaTipo, $moneda, $naturalezaCuenta, $cuentaCargo, $cuentaAbono, $comoAjustar, $tipoCambio, $dimension, $cuentaExige, $checkTitulo, $checkAjustar, $usuarioId, $cuentaId, $padreCuentaId, $empresaId) {

        $resCuenta = PlanContable::create()->guardarCuenta($codigo, $descripcion, $codigoEqui, $descripcionEqui, $estado, $cuentaTipo, $moneda, $naturalezaCuenta, $cuentaCargo, $cuentaAbono, $comoAjustar, $tipoCambio, $checkTitulo, $checkAjustar, $usuarioId, $cuentaId, $padreCuentaId, $empresaId);

        if ($resCuenta[0]['vout_exito'] == 1) {
            $this->guardarDimensionCuenta($dimension, $resCuenta[0]['id'], $usuarioId);
            $this->guardarPlanContableCuentaExige($cuentaExige, $resCuenta[0]['id'], $usuarioId);
        }

        if (!ObjectUtil::isEmpty($padreCuentaId)) {
            $dataPadre = PlanContable::create()->obtenerCuentaXId($padreCuentaId);
        } else {
            $dataPadre = null;
        }

        $respuesta->codigo = $codigo;
        $respuesta->descripcion = $descripcion;
        $respuesta->dataPadre = $dataPadre;
        $respuesta->resultado = $resCuenta;
        return $respuesta;
    }

    public function guardarDimensionCuenta($dimension, $cuentaId, $usuarioCreacion) {
        if (!ObjectUtil::isEmpty($cuentaId)) {
            PlanContable::create()->eliminarPlanContableDimensionXPlanContableId($cuentaId);
            if (is_array($dimension)) {
                foreach ($dimension as $dimensionId) {
                    $res = PlanContable::create()->guardarPlanContableDimension($dimensionId, $cuentaId, $usuarioCreacion);
                }
            }
        }
    }

    public function guardarPlanContableCuentaExige($cuentaExige, $cuentaId, $usuarioCreacion) {
        if (!ObjectUtil::isEmpty($cuentaId)) {
            PlanContable::create()->eliminarPlanContableCuentaExigeXPlanContableId($cuentaId);
            if (is_array($cuentaExige)) {
                foreach ($cuentaExige as $cuentaExigeId) {
                    $res = PlanContable::create()->guardarPlanContableCuentaExige($cuentaExigeId, $cuentaId, $usuarioCreacion);
                }
            }
        }
    }

    public function eliminarCuenta($id) {
        return PlanContable::create()->eliminarCuenta($id);
    }

    public function obtenerXCodigoInicial($codigoInicial) {
        return PlanContable::create()->obtenerXCodigoInicial($codigoInicial);
    }

    public function obtenerXCodigo($codigo) {
        return PlanContable::create()->obtenerXCodigo($codigo);
    }

    public function obtenerXEmpresaId($empresaId) {
        return PlanContable::create()->obtenerXEmpresaId($empresaId);
    }

    public function obtenerTodo($empresaId) {
        return PlanContable::create()->obtenerTodo();
    }

    public function obtenerPlanContableExcel() {
        $data = self::obtenerHijosSinCriterios();

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':B' . ($i + 1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Plan Contable');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . ($i + 1))->applyFromArray($this->estiloTituloReporte);
        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'CÓDIGO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'DESCRIPCIÓN DE LA CUENTA');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloTituloColumnas);
        $i += 1;

        foreach ($data as $reporte) {
            $padreCodigo = strlen($reporte['codigo']);
            if ($padreCodigo <= 2) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['codigo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['descripcion']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloInformacionPrincipal);
            } else {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['codigo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['descripcion']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloInformacion);
            }

            $i += 1;
        }
        for ($i = 'A'; $i <= 'B'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Plan Contable');
//
//        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/PlanContable.xlsx');
//
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
        $this->estiloInformacionPrincipal = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
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

    public function obtenerPlanContableTxt($empresaId, $periodo) {

        $periodoTem = explode("|", $periodo);
        $anio = trim($periodoTem[0]);
        $mes = trim($periodoTem[1]);
        $periodoDescripcion = $anio . $mes;

        $data = self::obtenerHijosXCriterios($anio, $mes, $empresaId);

        $archivoNombre = "LE20600143361$periodoDescripcion" . "00050300001" . (!ObjectUtil::isEmpty($data) ? "1" : "0") . "11.TXT";
        $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, null);
        $file = fopen($direccion, "w");
        $direccion = "\xEF\xBB\xBF" . $direccion;
        foreach ($data as $item) {

            $padreCodigo = strlen($item['codigo']);
            if ($padreCodigo >= 3) {
                $linea = "";
                //                 linea 1
                $linea .= $periodoDescripcion . "01|";
                //                linea 2 
                $linea .= str_pad($item['codigo'], 24, Util::rellenarEspacios(24)) . "|";
                //                linea 3
                $linea .= str_pad(Util::normaliza($item['descripcion']), 100, Util::rellenarEspacios(100)) . "|";
                //                linea 4
                $linea .= "01|";
                //                linea 5
                $linea .= str_pad("", 60, Util::rellenarEspacios(60)) . "|";
                //                linea 6
                $linea .= str_pad("", 24, Util::rellenarEspacios(24)) . "|";
                //                linea 7
                $linea .= str_pad("", 100, Util::rellenarEspacios(100)) . "|";
                //                linea 8
                $linea .= "1|";
                //                linea 9
                $linea .= str_pad("", 200, Util::rellenarEspacios(200));
                $lineaSalida = mb_convert_encoding($linea, "ISO-8859-1");
                fwrite($file, $lineaSalida . "\r\n");
            }
        }
        fclose($file);
        return $archivoNombre;
    }

}
