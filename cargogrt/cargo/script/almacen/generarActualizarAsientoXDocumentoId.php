<?php

include_once __DIR__ . '/../../controlador/contabilidad/DocumentoRevisionControlador.php';
$controlador = new DocumentoRevisionControlador();
$usuarioId = "1";
// $documentoId = "21741"; $tipo = "V";
//$documentoId = "21757"; $tipo = "V";
//$documentoId = "21827"; $tipo = "V";
//$documentoId = "21883"; $tipo = "V";
//$documentoId = "21937"; $tipo = "V";
// VENTAS GRATUITAS
//$documentoId = "21826"; $tipo = "V";
//$documentoId = "21828"; $tipo = "V";
// COMPRAS EXTRANJERAS
//$documentoId = "21897"; $tipo = "C";
//$documentoId = "22105"; $tipo = "C";
//$documentoId = "21895"; $tipo = "C";
//COMPRAS 
//$documentoId = "21884"; $tipo = "C";
//$documentoId = "21829"; $tipo = "C";
//$documentoId = "21389"; $tipo = "C";
//$documentoId = "21669"; $tipo = "C";
//$documentoId = "21730"; $tipo = "C";
//$documentoId = "21914"; $tipo = "C";
//$documentoId = "21703"; $tipo = "C";
//$documentoId = "21913"; $tipo = "C";
//$documentoId = "21819"; $tipo = "C";
//$documentoId = "21880"; $tipo = "C";
//$documentoId = "21954"; $tipo = "C";
//RECIBO POR HONORARIOS
//$documentoId = "22838"; $tipo = "C";
//$documentoId = "22842"; $tipo = "C";
//$documentoId = "22934"; $tipo = "C";

$documentoId = $_GET['documentoId'];
$tipo = $_GET['tipo'];

if (!ObjectUtil::isEmpty($documentoId) && !ObjectUtil::isEmpty($tipo)) {
    $repuesta = $controlador->ActualizarAsientoDocumento($documentoId, $tipo, $usuarioId);
}
//$fecha = date("d/m/Y");
//$fecha = '01/12/2017';
//$dataPPend=  AprobacionParcialNegocio::create()->obtenerPendientePorAprobarXFechaProgramada($fecha);
//$res1=  AprobacionParcialNegocio::create()->guardarEmailEnvioPorAprobar($dataPPend,$fecha);

echo var_dump($repuesta);
