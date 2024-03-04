<?php

include_once __DIR__ . '/../../modelo/almacen/Persona.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
//include_once __DIR__.'/../../modeloNegocio/almacen/OperacionNegocio.php';
try {
//    $documentId = $_GET['documento'];
    $arrayDocumento = array();
    $arrayDocumento[] = array("documentoId" => 27099);
    $arrayDocumento[] = array("documentoId" => 27100);
    $arrayDocumento[] = array("documentoId" => 27101);
    $arrayDocumento[] = array("documentoId" => 27102);
    $arrayDocumento[] = array("documentoId" => 27103);
    

//    if (ObjectUtil::isEmpty($documentId)) {
//        throw new WarningException("No se encontraron documentos para generar por resumen diario - 1");
//    }
    //GENERAR BOLETA RESUMEN
    $res = MovimientoNegocio::create()->generarDocumentoElectronicoPorResumenDiario($arrayDocumento); // BOLETA
    var_dump($res);
//    $respDocElectronico = MovimientoNegocio::create()->generarBoletaElectronica($documentId, 1, 0);
} catch (Exception $ex) {
    echo $ex->getMessage();
}