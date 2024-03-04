<?php

require_once __DIR__ . '/../../modelo/contabilidad/LibroTemp.php';
require_once __DIR__ . '/../../modelo/almacen/CostoCif.php';
require_once __DIR__ . '/../../modelo/contabilidad/LibroDetalleTemp.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ExcelNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContVoucherNegocio.php';

class InvPermValorizadoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return InvPermValorizadoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listar() {
        return LibroTemp::create()->listar();
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

        // Creamos la cabecera del historial, pero lo ponemos en pendiente
        $libroTemp = LibroTemp::create()->guardar($excelNombre, $anio, $mes, $usuarioId);
        $libroTempId = $this->validateResponse($libroTemp);

        $saldoInicialMesAnterior = 0;
        $compraPeriodo = 0;
        $costoComprasNacionales = 0;
        $costoComprasImportacion = 0;
        $consunoPeriodo = 0;

        foreach ($cells as $key => $value) {
            if (trim($value[1]) == 'Periodo:') {
                $dataCostoVenta = $this->registraDetalle($libroTempId, $cells, $key);

                $saldoInicialMesAnterior += Util::redondearNumero($dataCostoVenta["saldo"], 2);
                $costoComprasNacionales += Util::redondearNumero($dataCostoVenta["compras_nacionales"], 2);
                $costoComprasImportacion += Util::redondearNumero($dataCostoVenta["compras_extranjeras"], 2);
                $consunoPeriodo += Util::redondearNumero($dataCostoVenta["consumo_periodo"], 2);
                $compraPeriodo += Util::redondearNumero($dataCostoVenta["compra_periodo"], 2);
            }
        }

        $archivoNombre = "LE20600143361$anio$mes" . "00130100001111.TXT";
        $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, null);
        $file = fopen($direccion, "w");
        $direccion = "\xEF\xBB\xBF" . $direccion;
        $lista = LibroDetalleTemp::create()->listar($libroTempId);
        foreach ($lista as $linea) {
            $lineaSalida = mb_convert_encoding($linea["fila"], "ISO-8859-1");
            fwrite($file, $lineaSalida . "\r\n");
//            fwrite($file, $lineaSalida . PHP_EOL);
        }
        //fwrite($file, "Otra más" . PHP_EOL);
        fclose($file);


        $parametrosSaldos = NULL;
        $contVoucherId = NULL;
        if ($anio > 2019) {
            $saldoFinal = Util::redondearNumero($saldoInicialMesAnterior + $compraPeriodo - $consunoPeriodo, 2);
            $costoVenta = Util::redondearNumero($saldoInicialMesAnterior + $costoComprasNacionales + $costoComprasImportacion - $saldoFinal, 2);

            $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes(2, $anio, $mes);
            if ($dataPeriodo[0]['bandera_modificacion_contable'] == 1) {
                $respuestaContVoucher = ContVoucherNegocio::create()->registrarContVoucherCostoVenta($dataPeriodo[0]['id'], $costoVenta, $usuarioId);
            }
            $parametrosSaldos = json_encode(array('saldo_mes_anterior' => $saldoInicialMesAnterior,
                'compras_periodo' => $compraPeriodo,
                'consumos_periodo' => $consunoPeriodo,
                'saldo_final' => $saldoFinal,
                'compras_nacionales' => $costoComprasNacionales,
                'compras_importacion' => $costoComprasImportacion,
                'costo_venta' => $costoVenta));
            $contVoucherId = $respuestaContVoucher[0]['vout_id'];
        }

        LibroTemp::create()->actualizar($libroTempId, $archivoNombre, "Habilitado", $parametrosSaldos, $contVoucherId);

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

    private function registraDetalle($libroTempId, $cells, $row) {
        $existenciaCodigo = $this->obtenerCelda($cells, 3, $row + 4);
        if (ObjectUtil::isEmpty($existenciaCodigo)) {
            throw new WarningException("El código de producto no es correcto [2, " . ($row + 4) . "]");
        }

        $existenciaTipo = $this->obtenerCelda($cells, 3, $row + 5);
        if (ObjectUtil::isEmpty($existenciaTipo) || strlen($existenciaTipo) < 2) {
            throw new WarningException("El tipo de producto no es correcto [2, " . ($row + 5) . "]");
        }
        $existenciaTipo = substr($existenciaTipo, 0, 2);

        $existenciaDescripcion = $this->obtenerCelda($cells, 3, $row + 6, false);
        if (ObjectUtil::isEmpty($existenciaDescripcion)) {
            throw new WarningException("La descripción del producto no es correcta [2, " . ($row + 6) . "]");
        }

        $unidadMedida = $this->obtenerCelda($cells, 4, $row + 7, false);
        if (ObjectUtil::isEmpty($unidadMedida)) {
            $unidadMedida = "NIU";
        }


//        $unidadMedida = $this->obtenerCelda($cells, 2, $row+7);
//        if (ObjectUtil::isEmpty($unidadMedida) || strlen($unidadMedida)< 2){
//            throw new WarningException("La unidad de media no es correcta [2, ".($row+7)."]");
//        }
//        $unidadMedida = substr($unidadMedida, 0, 2);
//        $metodo = '1';

        $saldoInicialMesAnterior = 0;
        $compraPeriodo = 0;
        $costoComprasNacionales = 0;
        $costoComprasImportacion = 0;
        $consunoPeriodo = 0;
        for ($nRow = $row + 12; $this->obtenerCelda($cells, 1, $nRow) != 'TOTALES'; $nRow++) {
            $operacionTipo = $this->obtenerCelda($cells, 5, $nRow);
            $totalSaldo = $this->formato2($this->obtenerCelda($cells, 14, $nRow, false));
            $costoTotalEntrada = $this->formato2($this->obtenerCelda($cells, 8, $nRow, false));
            $costoTotalSalida = $this->formato2($this->obtenerCelda($cells, 11, $nRow, false));
            switch ($operacionTipo * 1) {
                case 16: $saldoInicialMesAnterior += Util::redondearNumero($totalSaldo, 2);
                    break;
                case 2:
                    $costoComprasNacionales += Util::redondearNumero($costoTotalEntrada, 2);
                    $compraPeriodo += Util::redondearNumero($costoTotalEntrada, 2);
                    break;
                case 18:
                    $costoComprasImportacion += Util::redondearNumero($costoTotalEntrada, 2);
                    $compraPeriodo += Util::redondearNumero($costoTotalEntrada, 2);
                    break;
                default :
                    $compraPeriodo += Util::redondearNumero($costoTotalEntrada, 2);
                    $consunoPeriodo += Util::redondearNumero($costoTotalSalida, 2);
            }

            $cuo = $this->obtenerCelda($cells, 15, $nRow, false);
            if (str_replace('-', '', trim($cuo)) != '') {
//echo "x".$this->obtenerCelda($cells, 3, $nRow)."x";
//                LibroDetalleTemp::create()->guardar($libroTempId, $cuo, $this->obtenerCelda($cells, 16, $nRow, false), $existenciaTipo, $existenciaCodigo, '', $this->formatoFecha($this->obtenerCelda($cells, 1, $nRow, false)), $this->obtenerCelda($cells, 2, $nRow), $this->obtenerCelda($cells, 3, $nRow), $this->obtenerCelda($cells, 4, $nRow), $this->obtenerCelda($cells, 5, $nRow), $existenciaDescripcion, $this->formato8($this->obtenerCelda($cells, 6, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 7, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 8, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 9, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 10, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 11, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 12, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 13, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 14, $nRow, false)), $unidadMedida, 1);
                //CUANDO SUBEN EL EXCEL CON FORMATO FECHA SE CAMBIO LA CLASE DEL EXCEL PARA QUE MUESTRE: DIA/MES/AÑO
                $tipoDocumento = $this->obtenerCelda($cells, 2, $nRow);
                $serieDocumento = $this->obtenerCelda($cells, 3, $nRow);
                $serieDocumento = ( $tipoDocumento == "50" ? $serieDocumento * 1 : str_pad($serieDocumento, 4, "0", STR_PAD_LEFT));
                //                                     1            2             3                                          4                5             6             7                                           8                                       9                                   10                                      11                                  12                          13                                                                 14                                                                               15                                                  16                                                                      17                                                          18                                                          19                                                                  20                                                      21                           22        23                 
                LibroDetalleTemp::create()->guardar($libroTempId, $cuo, $this->obtenerCelda($cells, 16, $nRow, false), $existenciaTipo, $existenciaCodigo, '', $this->obtenerCelda($cells, 1, $nRow, false), $this->obtenerCelda($cells, 2, $nRow), $serieDocumento, $this->obtenerCelda($cells, 4, $nRow), $this->obtenerCelda($cells, 5, $nRow), $existenciaDescripcion, $this->formato8($this->obtenerCelda($cells, 6, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 7, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 8, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 9, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 10, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 11, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 12, $nRow, false)), $this->formato8($this->obtenerCelda($cells, 13, $nRow, false)), $this->formato2($this->obtenerCelda($cells, 14, $nRow, false)), $unidadMedida, 1);
            }
        }
        return array("saldo" => $saldoInicialMesAnterior, "compras_nacionales" => $costoComprasNacionales, "compras_extranjeras" => $costoComprasImportacion, "consumo_periodo" => $consunoPeriodo, "compra_periodo" => $compraPeriodo);
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

    private function formato8($importe) {
        $numero = $this->obtenerImporte($importe);
        return $numero;
        //number_format($numero, 8);
    }

    private function formato2($importe) {
        $numero = $this->obtenerImporte($importe);
        return $numero;
        //return number_format($numero, 2);
    }

    private function formatoFecha($sFecha) {
        $aFecha = explode("/", $sFecha);
        return $aFecha[1] . "/" . $aFecha[0] . "/" . $aFecha[2];
    }

    /**
     * 
     * @param type $anio
     * @param type $mes
     */
    public function obtenerDataExcel($anio, $mes, $usuarioId, $banderaGeneraAsiento = NULL) {
        return $this->obtenerDataExcelPorPeriodo($anio, $mes, $usuarioId, $banderaGeneraAsiento);

//        $data = Array();
//        array_push($data, Array("bien_id"=>5,
//                                "cuenta_codigo"=>'2011279', 
//                                "bien_descripcion"=>'36mm Tapered Buttom bit 11 (P)',
//                                "unidad_medida_descripcion"=>"07-Unidades",
//                                "fecha"=>"2016-10-01",
//                                "documento_tipo"=>"10",
//                                "serie"=>"F11",
//                                "numero"=>"14234",
//                                "operacion_tipo"=>"16",
//                                "entrada_cantidad"=>10,
//                                "entrada_costo_unitario"=>8.0,
//                                "entrada_total"=>80.0,
//                                "salida_cantidad"=>10,
//                                "salida_costo_unitario"=>8.0,
//                                "salida_total"=>80.0,
//                                "final_cantidad"=>10,
//                                "final_costo_unitario"=>8.0,
//                                "final_total"=>80.0,
//                                ));
//        
//        return $data;
    }

    public function obtenerDataExcelPorPeriodo($anio, $mes, $usuarioId, $banderaGeneraAsiento = NULL) {
        //VALIDAR QUE EL MES ANTERIOR ESTE CERRADO
        if ($mes * 1 == 1) {
            $mesAnt = 12;
            $anioAnt = $anio * 1 - 1;
        } else {
            $mesAnt = $mes * 1 - 1;
            $anioAnt = $anio * 1;
        }
        $dataPeriodoAnt = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes(2, $anioAnt, $mesAnt);

        if (ObjectUtil::isEmpty($dataPeriodoAnt) || $dataPeriodoAnt[0]['indicador'] != 0) {
            throw new WarningException("El periodo anterior no esta cerrado. Cierre el periodo " . $anioAnt . "-" . ($mesAnt < 10 ? "0" . $mesAnt : $mesAnt) . " para reportar el inv. perm. valorizado.");
        }
        //FIN VALIDACION

        $inicio = "$anio-$mes-01";
        $fin = ($mes * 1 == 12) ? ($anio + 1) . "-01-01" : "$anio-" . ($mes + 1) . "-01";

        // obtenemos la data en bruto, ahora la procesamos 
//        $data = CostoCif::create()->inventarioPermValobtenerDataExcel($inicio, $fin);

        if (ObjectUtil::isEmpty($data))
            return null;

        $costoComprasNacionales = 0;
        $costoComprasImportacion = 0;
        $saldoInicialMesAnterior = 0;
        $compraPeriodo = 0;
        $consunoPeriodo = 0;

        foreach ($data as $key => $item) {
            if (in_array((int) $data[$key]["operacion_tipo"], array(5,21)) || ($data[$key]["operacion_tipo"] * 1 == 99 && $data[$key]["cantidad_entrada"] * 1 != 0)) {
                if ($data[$key - 1]["bien_id"] == $data[$key]["bien_id"]) {
                    $cantidadFinal = $data[$key - 1]["cantidad_final"];
                    $costoFinal = $data[$key - 1]["costo_final"];
                } else {
                    $cantidadFinal = 0;
                    $costoFinal = 0;
                }
                $costoUnitarioFinal = 0;
                for ($resta = 1; $data[$key - $resta]["bien_id"] == $data[$key]["bien_id"]; $resta++) {
                    if ($data[$key - $resta]["costo_unitario_final"] > 0) {
                        $costoUnitarioFinal = $data[$key - $resta]["costo_unitario_final"];
                        break;
                    }
                }

                $data[$key]["costo_unitario_entrada"] = $costoUnitarioFinal;
                $data[$key]["costo_entrada"] = $data[$key]["costo_unitario_entrada"] * $data[$key]["cantidad_entrada"];

                $data[$key]["cantidad_final"] = $cantidadFinal + $item["cantidad_entrada"] - $item["cantidad_salida"];
                $data[$key]["costo_final"] = $costoFinal + $data[$key]["costo_entrada"] - $data[$key]["costo_salida"];
                $data[$key]["costo_unitario_final"] = $data[$key]["costo_final"] / $data[$key]["cantidad_final"];
            } else if ($data[$key]["operacion_tipo"] * 1 != 16) {
                if ($data[$key - 1]["bien_id"] == $data[$key]["bien_id"]) {
                    $costoUnitarioFinal = $data[$key - 1]["costo_unitario_final"];
                    $costoFinal = $data[$key - 1]["costo_final"];
                    $cantidadFinal = $data[$key - 1]["cantidad_final"];
                } else {
                    $costoUnitarioFinal = 0;
                    $costoFinal = 0;
                    $cantidadFinal = 0;
                }
                $data[$key]["costo_unitario_salida"] = $costoUnitarioFinal;
                $data[$key]["costo_salida"] = $data[$key]["costo_unitario_salida"] * $data[$key]["cantidad_salida"];

                $data[$key]["cantidad_final"] = $cantidadFinal + $data[$key]["cantidad_entrada"] - $data[$key]["cantidad_salida"];
                $data[$key]["costo_final"] = $costoFinal + $data[$key]["costo_entrada"] - $data[$key]["costo_salida"];
                $data[$key]["costo_unitario_final"] = $data[$key]["costo_final"] / $data[$key]["cantidad_final"];
            }

            switch ($item["operacion_tipo"] * 1) {
                case 16: $saldoInicialMesAnterior += Util::redondearNumero($data[$key]["costo_final"], 2);
                    $data[$key]["nuevo_cuo"] = "-";
                    $data[$key]["nuevo_correlativo"] = "-";

                    break;
                case 2:
                    $costoComprasNacionales += Util::redondearNumero($data[$key]["costo_entrada"], 2);
                    $compraPeriodo += Util::redondearNumero($data[$key]["costo_entrada"], 2);
                    $data[$key]["nuevo_correlativo"] = "M0003";
                    break;
                case 18:
                    $costoComprasImportacion += Util::redondearNumero($data[$key]["costo_entrada"], 2);
                    $compraPeriodo += Util::redondearNumero($data[$key]["costo_entrada"], 2);
                    $data[$key]["nuevo_correlativo"] = "M0002";
                    break;
                default :
                    $compraPeriodo += Util::redondearNumero($data[$key]["costo_entrada"], 2);
                    $consunoPeriodo += Util::redondearNumero($data[$key]["costo_salida"], 2);
                    $data[$key]["nuevo_cuo"] = "";
                    $data[$key]["nuevo_correlativo"] = "M0002";
            }
        }

        $cuoGenerardo = "";
        if ($banderaGeneraAsiento === 1 && $anio > 2019) {
            $saldoFinal = Util::redondearNumero($saldoInicialMesAnterior + $compraPeriodo - $consunoPeriodo, 2);
            $costoVenta = Util::redondearNumero($saldoInicialMesAnterior + $costoComprasNacionales + $costoComprasImportacion - $saldoFinal, 2);

            $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes(2, $anio, $mes);
            if ($dataPeriodo[0]['bandera_modificacion_contable'] == 1) {
                $respuestaContVoucher = ContVoucherNegocio::create()->registrarContVoucherCostoVenta($dataPeriodo[0]['id'], $costoVenta, $usuarioId);
                $cuoGenerardo = $respuestaContVoucher[0]['cuo'];
            } else {
                $dataAsientoRegistrado = ContVoucherNegocio::create()->obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($dataPeriodo[0]['id'], ContVoucherNegocio::IDENTIFICADOR_COSTO_VENTA);
                if (!ObjectUtil::isEmpty($dataAsientoRegistrado)) {
                    $cuoGenerardo = $dataAsientoRegistrado[0]['cuo'];
                }
            }
        }
        //FORMATEAR A DOS DECIMALE LOS SUBTOTALES   
        //ENERO NO SE FORMATEA PORQUE YA SE DECLARO
        foreach ($data as $index => $item) {
            if (substr($item['fecha'], 0, 7) != '2017-01') {
//                if (substr($item['fecha'], 0, 7) == '2017-02') {
                $data[$index]["costo_entrada"] = round($item["costo_entrada"], 2);
//                }
                $data[$index]["costo_salida"] = round($item["costo_salida"], 2);
                $data[$index]["costo_final"] = round($item["costo_final"], 2);
            }
            if ($item["nuevo_cuo"] === "") {
                $data[$index]["nuevo_cuo"] = $cuoGenerardo;
            }
        }

        return $data;
    }

    public function generarExcel($anio, $mes, $usuarioId) {
        $dataExcel = $this->obtenerDataExcel($anio, $mes, $usuarioId, 1);
//        return $dataExcel;
        $res = ExcelNegocio::create()->generarExcelInvPermValorizado($dataExcel, $anio, $mes);

//        $resp=$this->genera($res->url, $res->nombre, $anio, $mes, $usuarioId);

        return $res;
    }

    public function generResumen($anio) {
        $dataExcel = $this->obtenerDataExcelPorAnio($anio);

        $res = ExcelNegocio::create()->generarExcelInvPermValorizadoResumen($dataExcel, $anio);
        return $res;
    }

    public function obtenerDataExcelPorAnio($anio) {
        $mesActual = date("m") * 1;

        //OBTENEMOS EL ULTIMO PERIODO CERRADO DESDE ENERO HASTA EL MES ACTUAL
        $mesCierre = 0;
        for ($mes = 1; $mes <= $mesActual; $mes++) {
            if ($mes == 1) {
                $mesAnt = 12;
                $anioAnt = $anio * 1 - 1;
            } else {
                $mesAnt = $mes - 1;
                $anioAnt = $anio * 1;
            }
            $dataPeriodoAnt = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes(2, $anioAnt, $mesAnt);

            if ($dataPeriodoAnt[0]['indicador'] == 0) {
                $mesCierre = $mes;
            } else {
                break;
            }
        }
        //FIN OBTENEMOS EL ULTIMO PERIODO CERRADO

        $data = array();
        for ($iMes = 1; $iMes <= $mesCierre; $iMes++) {
            $mesId = ($iMes < 10 ? ('0' . $iMes) : $iMes);
            $dataMes = $this->obtenerDataExcelPorPeriodo($anio, $mesId);
            if (!ObjectUtil::isEmpty($dataMes)) {
                $data = array_merge($data, $dataMes);
            }
        }

        //PROCESAMOS LA DATA PARA HALLAR LOS TOTALES DE CANTIDADES Y COSTOS POR MES Y PRODUCTO
        //OBTENER BIENES DIFERENTES
        $bienIdArray = array();
        foreach ($data as $item) {
            array_push($bienIdArray, $item['bien_id']);
        }

        $bienIdArray = array_unique($bienIdArray);

        //PARA OBTENER LOS INDICES CORRELATIVOS EL ARRAY UNIQUE LOS ELIMINO
        $bienIds = array();
        foreach ($bienIdArray as $item) {
            array_push($bienIds, $item);
        }

        //AGRUPO LOS BIENES
        $dataKardexBien = $bienIds;
        foreach ($bienIds as $index => $bienId) {
            $dataKardexBien[$index] = array();
            foreach ($data as $item) {
                if ($bienId == $item['bien_id']) {
                    array_push($dataKardexBien[$index], $item);
                }
            }
        }

        //AGRUPAMOS LOS BIENES POR MESES
//        $fechaArray = explode("-", $fechaMax);        
        $mesMax = $mesCierre;

        $dataKardex = $dataKardexBien;
        foreach ($dataKardexBien as $index => $itemBien) {
            $dataKardex[$index] = array(); //1° INDICE POR PRODUCTO 
            $dataKardex[$index]['bien'] = $itemBien[0];
            $dataKardex[$index]['meses'] = array();
            $dataKardex[$index]['totales'] = array('cantTotalEntProd' => 0, 'impTotalEntProd' => 0, 'cantTotalSalProd' => 0, 'impTotalSalProd' => 0);

            for ($i = 0; $i < $mesMax; $i++) {
                $dataKardex[$index]['meses'][$i]['mes'] = $i + 1; //2° INDICE POR MES
                $dataKardex[$index]['meses'][$i]['mov'] = array();

                foreach ($itemBien as $itemMov) {
                    $fechaArrayMov = explode("-", $itemMov['fecha']);
                    $mesMov = $fechaArrayMov[1];

                    if ($mesMov * 1 == ($i + 1)) {
                        array_push($dataKardex[$index]['meses'][$i]['mov'], $itemMov); //3° INDICE DE MOVIMIENTO
                    }
                }
            }
        }

        //PARA OBTENER LOS TOTALES CANTIDADES Y PRECIOS
        foreach ($dataKardex as $index => $item) {
            foreach ($item['meses'] as $indexMes => $itemMes) {
                $cantTotalEnt = 0;
                $cantTotalSal = 0;
                $impTotalEnt = 0;
                $impTotalSal = 0;
                foreach ($itemMes['mov'] as $indexMov => $itemMov) {
                    $cantTotalEnt += $itemMov['cantidad_entrada'];
                    $cantTotalSal += $itemMov['cantidad_salida'];
                    $impTotalEnt += $itemMov['costo_entrada'];
                    $impTotalSal += $itemMov['costo_salida'];
                }
                $dataKardex[$index]['meses'][$indexMes]['cantTotalEnt'] = $cantTotalEnt;
                $dataKardex[$index]['meses'][$indexMes]['cantTotalSal'] = $cantTotalSal;
                $dataKardex[$index]['meses'][$indexMes]['impTotalEnt'] = $impTotalEnt;
                $dataKardex[$index]['meses'][$indexMes]['impTotalSal'] = $impTotalSal;

                $dataKardex[$index]['totales']['cantTotalEntProd'] += $cantTotalEnt;
                $dataKardex[$index]['totales']['impTotalEntProd'] += $impTotalEnt;
                $dataKardex[$index]['totales']['cantTotalSalProd'] += $cantTotalSal;
                $dataKardex[$index]['totales']['impTotalSalProd'] += $impTotalSal;
            }
        }

        return $dataKardex;
    }

}
