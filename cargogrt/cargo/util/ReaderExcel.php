<?php

require __DIR__ . '/PhpSpreadSheet/PhpOffice/autoload.php';
require_once __DIR__ . '/../modeloNegocio/almacen/TarifarioNegocio.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ImportacionExcel2 {

    // insert tarifario BD
    public static function importExcelTarifario($fileExcel, $usuarioCreacion) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);
        $numeroFilas = $hojaActual->getHighestDataRow();

        for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
            $agenciaOr =  trim($hojaActual->getCellByColumnAndRow('1', $indiceFila)->getValue());
            $agenciaDes = trim($hojaActual->getCellByColumnAndRow('2', $indiceFila)->getValue());
            $moneda = ($hojaActual->getCellByColumnAndRow('3', $indiceFila)->getValue());
            $codPersona = trim($hojaActual->getCellByColumnAndRow('4', $indiceFila)->getValue());
            $persona = trim($hojaActual->getCellByColumnAndRow('5', $indiceFila)->getValue());
            $precioMinimo = ($hojaActual->getCellByColumnAndRow('6', $indiceFila)->getValue());
            $codArticulo = trim($hojaActual->getCellByColumnAndRow('7', $indiceFila)->getValue());
            $articulo = trim($hojaActual->getCellByColumnAndRow('8', $indiceFila)->getValue());
            $precioArticulo = ($hojaActual->getCellByColumnAndRow('9', $indiceFila)->getValue());
            $precioKg = ($hojaActual->getCellByColumnAndRow('10', $indiceFila)->getValue());
            $precioSobre = ($hojaActual->getCellByColumnAndRow('11', $indiceFila)->getValue());
            $precio5Kg = ($hojaActual->getCellByColumnAndRow('12', $indiceFila)->getValue());

            //validacion
            if ($precioSobre != "" && $precioKg == "" && $precio5Kg != "") {
                $precioKg = 0;
                $precio5Kg = 0;
            }
            if ($codArticulo != "" || !empty($codArticulo)) {
                $precioMinimo = 0;
                $precioKg = 0;
                $precioSobre = 0;
                $precio5Kg = 0;
            }
            if ($codPersona != "" || !empty($codPersona)) {
                if ($codArticulo == "") {
                    $codArticulo = null;
                    $precioArticulo = 0;
                }
            } else {
                if ($codArticulo == "") {
                    $codArticulo = null;
                    $precioArticulo = 0;
                    $precioMinimo = 0;
                }
            }

            // $tarifario[] =  array($agenciaOr, $agenciaDes, $codPersona, $persona, $codArticulo, $articulo, $moneda, $precioKg, $precioSobre, $precio5Kg);
              if ($moneda!=2 ) {
           throw new WarningException("Moneda en el formato debe ser 2:soles ");
              }
            $result = TarifarioNegocio::create()->importTarifario($agenciaOr, $agenciaDes, $codPersona, $persona, $codArticulo, $articulo, $moneda, $precioKg, $precioSobre, $precio5Kg, $precioMinimo, $precioArticulo, $usuarioCreacion);

            $resultado[] = array($result[0]["error"], $result[0]["errorAgenciO"], $result[0]["errorAgenciaD"], $result[0]["errorPersona"],
                $result[0]["agenciaO"], $result[0]["agenciaD"], $result[0]["codPersona"], $result[0]["persona"], $result[0]["codArticulo"], $result[0]["articulo"],
                $result[0]["moneda"], $result[0]["precioKg"], $result[0]["precioSobre"], $result[0]["precio5kg"], $result[0]["precio5kg"], $result[0]["precio5kg"], $result[0]["errorArticulo"], $result[0]["errorPrecio5Kg"], $result[0]["errorinsert"]);
        }

        return $resultado;
    }

    public static function importExcelBien($fileExcel, $usuarioCreacion) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);
        $numeroFilas = $hojaActual->getHighestDataRow();

        for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
            $vacio = $hojaActual->getCellByColumnAndRow('1', $indiceFila)->getValue();
            $codigoBien = $hojaActual->getCellByColumnAndRow('2', $indiceFila)->getValue();
            $descripcion = $hojaActual->getCellByColumnAndRow('3', $indiceFila)->getValue();
            $tipoBienCodigo = $hojaActual->getCellByColumnAndRow('4', $indiceFila)->getValue();
            $largo = $hojaActual->getCellByColumnAndRow('5', $indiceFila)->getValue();
            $ancho = $hojaActual->getCellByColumnAndRow('6', $indiceFila)->getValue();
            $alto = $hojaActual->getCellByColumnAndRow('7', $indiceFila)->getValue();
            $pesoComercial = $hojaActual->getCellByColumnAndRow('8', $indiceFila)->getValue();

            $result = BienNegocio::create()->importBien($codigoBien, $descripcion, $tipoBienCodigo, $largo,
                    $ancho, $alto, $pesoComercial, $usuarioCreacion);

            $resultado[] = array($result[0]["error"], $result[0]["codigoBien"], $result[0]["descripcionBien"], $result[0]["bienTipo"],
                $result[0]["bienLargo"], $result[0]["bienAncho"], $result[0]["bienAlto"], $result[0]["bienPesoComercial"],
                $result[0]["erroCodigo"], $result[0]["errorDescripcion"], $result[0]["errorBienTipo"], $result[0]["errorInsert"],
                $result[0]["successInsert"]);
        }

        return $resultado;
    }

    // save excel file with errors 
    public static function writeErroresTarifario($tarifario, $ruta) {

        $path = __DIR__ . "/formatos/$ruta.xlsx";
        $realFile = "formatos/$ruta.xlsx";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $increment = 1;
        $i = 2;
        foreach ($tarifario as $item => $array) {
            $increment = $increment + 1;
            $i += 1;
            $errorAgenciaO = $array["1"];
            $errorAgenciaD = $array["2"];
            $errorPersona = $array["3"];
            $errorArticulo = $array["14"];
            $errorPrecio5k = $array["17"];
            $errorInsert = $array["18"];

            if ($array["0"] == 1) {
                $errores = $errorAgenciaO . ' ' . $errorAgenciaD . ' ' . $errorPersona . ' ' . $errorArticulo . ' ' . $errorPrecio5k . ' ' . $errorInsert;
                $sheet->setCellValue('A1', "ERRORES");
                $sheet->setCellValue('B1', "AGENCIA ORIGEN");
                $sheet->setCellValue('C1', "AGENCIA DESTINO");
                $sheet->setCellValue('D1', "CODINDENTIFICADOR");
                $sheet->setCellValue('E1', "PERSONA");
                $sheet->setCellValue('F1', "CODARTICULO");
                $sheet->setCellValue('G1', "ARTICULO");
                $sheet->setCellValue('H1', "MONEDA");
                $sheet->setCellValue('I1', "PRECIOKG");
                $sheet->setCellValue('J1', "PRECIO SOBRE");
                $sheet->setCellValue('K1', "PRECIO5KG");

                $sheet->setCellValue('A' . ($increment), $errores);
                $sheet->setCellValue('B' . ($increment), $array["4"]);
                $sheet->setCellValue('C' . ($increment), $array["5"]);
                $sheet->setCellValue('D' . ($increment), $array["6"]);
                $sheet->setCellValue('E' . ($increment), $array["7"]);
                $sheet->setCellValue('F' . ($increment), $array["8"]);
                $sheet->setCellValue('G' . ($increment), $array["9"]);
                $sheet->setCellValue('H' . ($increment), $array["10"]);
                $sheet->setCellValue('I' . ($increment), $array["11"]);
                $sheet->setCellValue('J' . ($increment), $array["12"]);
                $sheet->setCellValue('K' . ($increment), $array["13"]);
                $sheet->getStyle("A" . ($increment))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
                $sheet->getStyle('A1:K1')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getColumnDimension('J')->setAutoSize(true);
                $sheet->getColumnDimension('K')->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return $realFile;
    }

    public static function writeErroresBien($tarifario, $ruta) {

        $path = __DIR__ . "/formatos/$ruta.xlsx";
        $realFile = "formatos/$ruta.xlsx";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $increment = 1;
        $i = 2;
        foreach ($tarifario as $item => $array) {
            $increment = $increment + 1;
            $i += 1;
            $textErrorCodigo = $array["8"];
            $textErrorDescripcion = $array["9"];
            $textErrorBienTipo = $array["10"];

            if ($array["0"] == 1) {
                $errores = $textErrorCodigo . ' | ' . $textErrorDescripcion . ' | ' . $textErrorBienTipo;
                $sheet->setCellValue('A1', "ERRORES");
                $sheet->setCellValue('B1', "CODIGO");
                $sheet->setCellValue('C1', "DESCRIPCION");
                $sheet->setCellValue('D1', "TIPOBIEN");
                $sheet->setCellValue('E1', "LARGO");
                $sheet->setCellValue('F1', "ANCHO");
                $sheet->setCellValue('G1', "ALTO");
                $sheet->setCellValue('H1', "PESOCOMERCIAL");

                $sheet->setCellValue('A' . ($increment), $errores);
                $sheet->setCellValue('B' . ($increment), $array["1"]);
                $sheet->setCellValue('C' . ($increment), $array["2"]);
                $sheet->setCellValue('D' . ($increment), $array["3"]);
                $sheet->setCellValue('E' . ($increment), $array["4"]);
                $sheet->setCellValue('F' . ($increment), $array["5"]);
                $sheet->setCellValue('G' . ($increment), $array["6"]);
                $sheet->setCellValue('H' . ($increment), $array["7"]);
                
                $sheet->getStyle("A" . ($increment))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
                $sheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return $realFile;
    }

    // return cabeceras excel tarifario
    public static function getCabeceraExcel($fileExcel) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);

        $cAgenciaOr = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('1', 1))));
        $cAgenciaDes = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('2', 1))));
        $cCodPersona = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('3', 1))));
        $cPersona = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('4', 1))));
        $cCodArticulo = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('5', 1))));
        $cArticulo = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('6', 1))));
        $cMoneda = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('7', 1))));
        $cPrecioKg = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('8', 1))));
        $cPrecioSobre = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('9', 1))));
        $cPrecio5Kg = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('10', 1))));

        $cabecera = array($cAgenciaOr, $cAgenciaDes, $cCodPersona, $cPersona, $cCodArticulo, $cArticulo, $cMoneda, $cPrecioKg, $cPrecioSobre, $cPrecio5Kg);
        return $cabecera;
    }

    public static function getCabeceraExcelBien($fileExcel) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);

        $vacio = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('1', 1))));
        $cCodigoBien = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('2', 1))));
        $cDescripcion = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('3', 1))));
        $cTipoBienCodigo = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('4', 1))));
        $cLargo = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('5', 1))));
        $cAncho = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('6', 1))));
        $cAlto = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('7', 1))));
        $cPesoComercial = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('8', 1))));

        $cabecera = array($cCodigoBien, $cDescripcion, $cTipoBienCodigo, $cLargo, $cAncho, $cAlto, $cPesoComercial);

        return $cabecera;
    }

    // importar tarifario zona
    public static function importExcelTarifarioZona($fileExcel, $usuarioCreacion) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);
        $numeroFilas = $hojaActual->getHighestDataRow();

        for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
            $agencia = $hojaActual->getCellByColumnAndRow('1', $indiceFila)->getValue();
            $zona = $hojaActual->getCellByColumnAndRow('2', $indiceFila)->getValue();
            $moneda = $hojaActual->getCellByColumnAndRow('3', $indiceFila)->getValue();
            $precioSobre = $hojaActual->getCellByColumnAndRow('4', $indiceFila)->getValue();
            $precio50 = $hojaActual->getCellByColumnAndRow('5', $indiceFila)->getValue();
            $precio51 = $hojaActual->getCellByColumnAndRow('6', $indiceFila)->getValue();
            $precio101 = $hojaActual->getCellByColumnAndRow('7', $indiceFila)->getValue();
            $precio251 = $hojaActual->getCellByColumnAndRow('8', $indiceFila)->getValue();
            $precio500 = $hojaActual->getCellByColumnAndRow('9', $indiceFila)->getValue();

            $result = TarifarioNegocio::create()->importTarifarioZona($agencia, $zona, $moneda, $precioSobre, $precio50, $precio51, $precio101, $precio251, $precio500, $usuarioCreacion);

            $resultado[] = array($result[0]["error"], $result[0]["errorAgencia"], $result[0]["errorZona"],
                $result[0]["agencia"], $result[0]["zona"], $result[0]["moneda"], $result[0]["precioSobre"], $result[0]["precio50"], $result[0]["precio51"],
                $result[0]["precio101"], $result[0]["precio251"], $result[0]["precio500"]);
            //  return $result;  
        }

        return $resultado;
    }

    // generar excel con errores
    Public static function writeErroresTarifarioZona($tarifario, $ruta) {

        $path = __DIR__ . "/formatos/$ruta.xlsx";
        $realFile = "formatos/$ruta.xlsx";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $increment = 1;
        $i = 2;
        foreach ($tarifario as $item => $array) {
            $increment = $increment + 1;
            $i += 1;
            $errorAgencia = $array["1"];
            $errorZona = $array["2"];
            if ($array["0"] == 1) {
                $errores = $errorAgencia . ' ' . $errorZona;
                $sheet->setCellValue('A1', "ERRORES");
                $sheet->setCellValue('B1', "AGENCIA");
                $sheet->setCellValue('C1', "ZONA DE REPARTO");
                $sheet->setCellValue('D1', "MONEDA");
                $sheet->setCellValue('E1', "PRECIO SOBRE KG");
                $sheet->setCellValue('F1', "PRECIO 0-50 KG");
                $sheet->setCellValue('G1', "PRECIO 51-100 KG");
                $sheet->setCellValue('H1', "PRECIO 101-250 KG");
                $sheet->setCellValue('I1', "PRECIO 251-500 KG");
                $sheet->setCellValue('J1', "PRECIO +500 KG");

                $sheet->setCellValue('A' . ($increment), $errores);
                $sheet->setCellValue('B' . ($increment), $array["3"]);
                $sheet->setCellValue('C' . ($increment), $array["4"]);
                $sheet->setCellValue('D' . ($increment), $array["5"]);
                $sheet->setCellValue('E' . ($increment), $array["6"]);
                $sheet->setCellValue('F' . ($increment), $array["7"]);
                $sheet->setCellValue('G' . ($increment), $array["8"]);
                $sheet->setCellValue('H' . ($increment), $array["9"]);
                $sheet->setCellValue('I' . ($increment), $array["10"]);
                $sheet->setCellValue('J' . ($increment), $array["11"]);

                $sheet->getStyle("A" . ($increment))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
                $sheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getColumnDimension('J')->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        return $realFile;
    }

    // return cabeceras excel tarifario
    public static function getCabeceraExcelTarifarioZona($fileExcel) {
        $nameFile = __DIR__ . $fileExcel;

        $documento = IOFactory::load($nameFile);
        $hojaActual = $documento->getSheet(0);

        $cAgencia = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('1', 1))));
        $cZona = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('2', 1))));
        $cMoneda = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('3', 1))));
        $cPrecioSobre = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('4', 1))));
        $cPrecio50 = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('5', 1))));
        $cPrecio100 = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('6', 1))));
        $cPrecio250 = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('7', 1))));
        $cPrecio500 = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('8', 1))));
        $cPrecioMax = utf8_encode(str_replace(' ', '', strtolower($hojaActual->getCellByColumnAndRow('9', 1))));

        $cabecera = array($cAgencia, $cZona, $cMoneda, $cPrecioSobre, $cPrecio50, $cPrecio100, $cPrecio250, $cPrecio500, $cPrecioMax);
        return $cabecera;
    }

}
