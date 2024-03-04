<?php

require_once __DIR__ . '/PHPExcel/PHPExcel.php';
require_once __DIR__ . '/PHPExcel/PHPExcel/IOFactory.php';
//require_once __DIR__ . '/ExcelReader2.php';

class ImportacionExcel {
    
    public static function parseExcelMovimientoToXML($path, $usuario, $tipo) {
        $path = __DIR__ . "/" . $path;
        $objPHPExcel = PHPExcel_IOFactory::load($path);
        $tipo = strtolower($tipo);
        $xml = "";
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();// Nota de entrada
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            for ($row = 7; $row <= $highestRow; $row++) {//TOFIX row=7, donde comienza la data
                $content = "";
                for ($col = 0; $col < $highestColumnIndex; ++$col) {                    
                    $name = str_replace(' ', '', ($worksheet->getCellByColumnAndRow($col, 6)->getValue())); //TOFIX col,1 = donde estan las cabeceras
                    $posicion_coincidencia = strpos($name, 'Fecha');
                    if($posicion_coincidencia !== FALSE){
                        $value=PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue(),'DD/MM/YYYY' );
                    }else{
                        //$value = trim($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                        $value = trim($worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue());
                    }
                    
                    if (strlen($name) > 0) {
                        $value = $value == null ? '0.00' : $value;
                        $content .= "<$name>$value</$name>";
                    }
                }
                $content .= "<documento tipo>$worksheetTitle</documento tipo>";
                
                $xml .= "<$tipo>$content</$tipo>";
            }
        }
        return $xml;
    }

    public static function parseExcelToXML($path, $usuario, $tipo) {
        $path = __DIR__ . "/" . $path;
        $objPHPExcel = PHPExcel_IOFactory::load($path);
        $tipo = strtolower($tipo);
        $xml = "";
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            for ($row = 2; $row <= $highestRow; $row++) {
                $content = "";
                for ($col = 1; $col < $highestColumnIndex; ++$col) {
                    $value = trim($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $value = strlen($value)== 0 ? "": $value;
                    $name = trim(strtolower($worksheet->getCellByColumnAndRow($col, 1)->getValue()));
                    if (strlen($name) > 0)
                        $content .= "\t\t<$name>$value</$name>\n";
                }
                $content .= "\t\t<usuario>$usuario</usuario>\n";
                $xml .= "\t<$tipo>$content</$tipo>\n";
            }
        }
        return $xml;
    }

    public static function parseExcelToSTD($path, $usuario, $tipo) {
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
        array_shift($headers);
        foreach($cells as $row){
            $content = "";
            $stocks = "";
            $error = "";
            foreach($headers as $k => $h){
                $value  = trim($row[$k+2]);
                $value  = utf8_encode(strlen($value)== 0 ? "": $value);
                $name   = utf8_encode(trim(strtolower($h)));
                $name   = strtr($name, $tildes);
                $value  = self::easeElement($value, $name);
                $error .= self::validElement($value, $name);
                
                if(strpos($name, "stock") !== false) {
                    $name = str_replace("stock", "", $name);
                    $stocks .= "\t\t\t<stock>\n\t\t\t\t<nombre>$name</nombre>\n\t\t\t\t<valor>$value</valor>\n\t\t\t</stock>\n";
                }
                else{
                    $content .= "\t\t<$name>$value</$name>\n";
                }
            }
            if(strlen($error) > 0){
                $errors[] = array("row" => $fila, "cause" => $error, "content" => $row);
                $fila++;
                continue;
            }
            if(strlen($content) > 0){
                $content .= "\t\t<row>$fila</row>\n";
                $content .= $stocks;
                $xml .= "<$tipo>\n$content</$tipo>\n";
            }
            $fila++;
        }
        $xml = "<root>".$xml."</root>";
        if(!empty($errors)){
            return $errors;
        }
        return array("xml" => $xml, "data"=>$cells);
    }
    
    public static function parsePersonaExcelToXML($path, $tipo="persona") {
        $xml = "";
        $fila = 2;
        $tipo = strtolower($tipo);
        $errors = array();
        $tildes = array("á" => "a","é" => "e","í" => "i","ó" => "o","ú" => "u", "." => "_", " " => "");
        $path = __DIR__ . "/" . $path;
        $excel = new Spreadsheet_Excel_Reader();
        $excel->read($path);
        $cells = $excel->sheets[0]["cells"];
        $headers = array_shift($cells);
        array_shift($headers);
        foreach($cells as $row){
            if(strlen($row[2]) == 0){
                $fila++;
                continue;
            }
            $content = "";
            $error = "";
            foreach($headers as $k => $h){
                $value  = trim($row[$k+2]);
                $value  = utf8_encode(strlen($value)== 0 ? "": $value);
                $name   = utf8_encode(trim(strtolower($h)));
                $name   = strtr($name, $tildes);
                $content .= "\t\t<$name>$value</$name>\n";
            }
            if(strlen($error) > 0){
                $errors[] = array("row" => $fila, "cause" => $error, "content" => $row);
                $fila++;
                continue;
            }
            if(strlen($content) > 0){
                $content .= "\t\t<row>$fila</row>\n";
                $xml .= "<$tipo>\n$content</$tipo>\n";
            }
            $fila++;
        }
        $xml = "<root>".$xml."</root>";
        if(!empty($errors)){
            return $errors;
        }
        return array("xml" => $xml, "data"=>$cells);
    }
    
    public static function easeElement($value, $name){
//        if($name == "unidadcontrol"){
//            $parseUnidad = array("unidad", "und.", "und", "unid", "unid.", "unidades");
//            $parseBolsas = array("bolsa", "bolsas");
//            $parseJuegos = array("jgo.", "jgo", "juego", "juegos");
//            $parseKilogs = array("kg.", "kg", "kilos", "kilogramos");
//            $parseGramos = array("gr.", "gr", "gramos");
//            $parseMetros = array("mts.", "mts.", "mts,", "metros");
//            $parsePiezas = array("pza.", "pza,", "pza", "piezas");
//            $unidadcontrol = strtolower($value);
//            $unidadcontrol = str_replace($parseUnidad, "Unidad(es)", $unidadcontrol);
//            $unidadcontrol = str_replace($parseBolsas, "Bolsa(s)", $unidadcontrol);
//            $unidadcontrol = str_replace($parseJuegos, "Juego(s)", $unidadcontrol);
//            $unidadcontrol = str_replace($parseKilogs, "Kilogramo(s)", $unidadcontrol);
//            $unidadcontrol = str_replace($parseGramos, "Gramo(s)", $unidadcontrol);
//            $unidadcontrol = str_replace($parseMetros, "Metro(s)", $unidadcontrol);
//            $unidadcontrol = str_replace($parsePiezas, "Pieza(s)", $unidadcontrol);
//            $value = $unidadcontrol;
//        }
        return $value;
    }
    public static function validElement($value, $name) {
        $type = strpos($name, "stock") !== false ? "stock" : $name;
        switch ($type) {
            case "codigo":
                return (empty($value) || strlen($value) > 45) ? "Código erroneo \n" : "";
            case "descripcion":
                return (empty($value) || strlen($value) > 150) ? "Descripción erronea \n" : "";
            case "cantidadminina":
                return (empty($value) || !is_numeric($value)) ? "Cantidad minina erronea \n" : "";
            case "stock":
                return ($value*1<0) ? "$name valor menor de 0 \n" : "";
            //case "tipounidad":
                //return (empty($value) || strlen($value) > 500) ? "Tipo de unidad erronea \n" : "";
            case "tipobien":
                return (empty($value) || strlen($value) > 500) ? "Tipo de bien erroneo \n" : "";
        }
        return "";
    }

    public static function getExcelwithErrors($errors, $base, $bd=false) {
        $real = "formatos/$base.xls";
        $path = __DIR__ . "/formatos/$base.xls";
        $objPHPExcel = PHPExcel_IOFactory::load($path);
        //var_dump($errors);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '   #ERRORES#');
        foreach ($errors as $array) {
            $row = $array["row"];
            $cause = $array["cause"];
            if($bd === false){
                foreach($array["content"] as $k => $v){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($k-1,$row,utf8_encode($v));
                }
            }
            else{
                foreach($bd[$row-2] as $k => $v){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($k-1,$row,utf8_encode($v));
                }
            }
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, utf8_encode(str_replace("_esc", "\n", $cause)));
            $objPHPExcel->getActiveSheet()->getStyle("A$row")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $newreal = str_replace("$base", "errores", $real);
        $newpath = str_replace("$base", "errores", $path);
        $objWriter->save($newpath);
        return $newreal;
    }

    public static function getExcelwithErrorsTarifario($tarifario, $base) {
        $real = "formatos/$base.xlsx";
        $path = __DIR__ . "/formatos/$base.xlsx";
        $objPHPExcel = PHPExcel_IOFactory::load($path);
        //var_dump($errors);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '   #ERRORES#');
        foreach ($tarifario as $item => $array) {
            // $row = $array["row"];
            // $cause = $array["cause"];
            $errorAgenciaO = $tarifario[0]["errorAgenciO"];
            $errorAgenciaD = $tarifario[0]["errorAgenciaD"];
            $errorPersona = $tarifario[0]["errorPersona"];
            
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . ($item+1), utf8_encode($errorAgenciaD));
            // $objPHPExcel->getActiveSheet()->getStyle("A$row")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $newreal = str_replace("$base", "errores", $real);
        $newpath = str_replace("$base", "errores", $path);
        $objWriter->save($newpath);
        return $newreal;
    }
    
    public static function obtenerCabeceraExcel($path,$utf8=0) {
        $tildes = array("á" => "a","é" => "e","í" => "i","ó" => "o","ú" => "u");
        $path = __DIR__ . "/" . $path;
        $excel = new Spreadsheet_Excel_Reader();
        $excel->read($path);
        $cells = $excel->sheets[0]["cells"];
        $headers = array_shift($cells);
        array_shift($headers);
        
        foreach ($headers as $k => $h) {        
            if($utf8==1){            
                $name = utf8_encode(trim($h));
            }else{
                $name = trim($h);
            }
            $name = strtr($name, $tildes);
            $headers[$k]=$name;
        }
        return $headers;        
    }

    // import tarifario
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
        foreach($cells as $row){
            $content = "";
            $stocks = "";
            $error = "";
            foreach($headers as $k => $h){
//                $value  = trim($row[$k+2]);
                $value  = trim($row[$k]);
                $value  = utf8_encode(strlen($value)== 0 ? "": $value);
                $name   = utf8_encode(trim(strtolower($h)));
                $name   = strtr($name, $tildes);
                $value  = self::easeElement($value, $name);
//                $error .= self::validElement($value, $name);

                if($value !== '') {
                    $content .= "\t\t<$name>$value</$name>\n";
                }
            }
//            if(strlen($error) > 0){
//               $errors[] = array("row" => $fila, "cause" => $error, "content" => $row);
//                $fila++;
//                continue;
//            }
            if(strlen($content) > 0){
                $content .= "\t\t<row>$fila</row>\n";
                //$content .= $stocks;
                $xml .= "<$tipo>\n$content</$tipo>\n";
            }
            $fila++;
        }
        $xml = "<root>".$xml."</root>";
//        if(!empty($errors)){
//            return $errors;
//        }
        return array("xml" => $xml, "data"=>$cells);
//        $h = "xd";
//        return $h;
    }

}

?>