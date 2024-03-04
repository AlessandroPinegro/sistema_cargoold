<?php
//Import the PhpJasperLibrary
include_once __DIR__.'/PhpJasperLibrary/tcpdf/tcpdf.php';
include_once __DIR__.'/PhpJasperLibrary/PHPJasperXML.inc.php';

//database connection details
//$server="192.168.1.11";
$server="localhost";
$db="bhdt_20170901";
$user="root";
$pass="local";
//$version="0.8b";
//$pgport=5432;
//$pchartfolder="./class/pchart2";
//display errors should be off in the php.ini file
//ini_set('display_errors', 0);
//setting the path to the created jrxml file
//$xml=  simplexml_load_file("reporte1.jrxml");

//for($i=0;$i<100;$i++){

$urlJrxml = __DIR__ . '/../reporteJasper/almacen/factura2.jrxml';
$xml=  simplexml_load_file($urlJrxml);
$PHPJasperXML= new PHPJasperXML();
//$PHPJasperXML->debugsql=true;
$PHPJasperXML->arrayParameter = array(
    "serie" => '001',
    "numero" => '001223',
    "nombre" => "2R GENERAL CONTRACTORS S.A.C",
    "direccion" => "CAL. SINCHI ROCA NRO. 1248 INT. A URB. PALERMO - TRUJILLO",
    "total" => "890.50",
    "vin_movimiento_id" => 2270
);
// 2270
//$PHPJasperXML->arrayParameter=array("persona_id"=>788);
$PHPJasperXML->xml_dismantle($xml);
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
//$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file

$hoy = date("Y_m_d_H_i_s");
$pdf = 'factura_' . $hoy . '.pdf';  
$url = 'C:/wamp/www/sgi/reporteJasper/documentos/' . $pdf;          
//$PHPJasperXML->Output($url, 'F');
$PHPJasperXML->outpage('F',$url);
//C:\wamp\www\sgi\reporteJasper\documentos
//}
?>