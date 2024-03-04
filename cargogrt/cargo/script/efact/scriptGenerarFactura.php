<?php

include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';

try {
    $documentoid = $_GET['documento_id'];
        
    $res = MovimientoNegocio::create()->generarFacturaElectronica($documentoid);

    echo $res;
//    var_dump($res);
} catch (Exception $ex) {
    echo $ex->getMessage();
}