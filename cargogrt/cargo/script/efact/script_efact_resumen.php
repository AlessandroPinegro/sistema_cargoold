<?php

include_once __DIR__ . '/../../modelo/almacen/Persona.php';
include_once __DIR__.'/../../modeloNegocio/almacen/MovimientoNegocio.php';
//include_once __DIR__.'/../../modeloNegocio/almacen/OperacionNegocio.php';
try {
//FACTURAS
//$res=  MovimientoNegocio::create()->generarDocumentoElectronico(6250,4); //mal
//    $res=  MovimientoNegocio::create()->generarDocumentoElectronico(22,4); // bien

//BOLETAS
//$res=  MovimientoNegocio::create()->generarDocumentoElectronico(6732,3);

//NOTA DE CREDITO
//$res=  MovimientoNegocio::create()->generarDocumentoElectronico(13637,5);//Bien
//$res=  MovimientoNegocio::create()->generarDocumentoElectronico(6736,5);//mal

//NOTA DE DEBITO
//$res=  MovimientoNegocio::create()->generarDocumentoElectronico(6739,25);

//ANULAR FACTURA
    $res=  MovimientoNegocio::create()->anularDocumentoElectronicoPorResumenDiario(); // BOLETA

//FACTURA CON NEGOCIO DE OPERACION
//$res= OperacionNegocio::create()->generaFacturaElectronica(6651);
} catch (Exception $ex) {
    echo $ex->getMessage();
}