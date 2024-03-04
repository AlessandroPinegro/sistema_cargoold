<?php
require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require_once __DIR__ . '/PhpSpreadsheet/src/PhpSpreadsheet/IOFactory.php';
require_once __DIR__ . '/ExcelReader.php';

class ImportacionExcel2 {
  
  public static function parseExcelToSTDTarifarioXml($path, $usuario, $tipo) {
    $xml = "";
    $fila = 2;
    $tipo = strtolower($tipo);
    $errors = array();
    $tildes = array("á" => "a","é" => "e","í" => "i","ó" => "o","ú" => "u");
    $path = __DIR__ . "/" . $path;
    $excel = new Spreadsheet_Excel_Reader();
    $excel->read($path);
    $cells = $excel->sheets[0]["cells"];
    $headers = array_shift($cells);
    //array_shift($headers);
    return $headers;

    // $documento = IOFactory::load($path);
    // $totalHojas = $documento->getSheetCount();
    // $hojaActual = $documento->getSheet(0);
    
    // $numeroFilas = $hojaActual->getHighestDataRow();
    // $letra = $hojaActual->getHighestColumn();
    //     return $letra;
  }
}