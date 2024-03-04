<?php

include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';

try {

    $res = MovimientoNegocio::create()->generarDocumentosElectronicosPendientes();

    echo $res;
//    var_dump($res);
} catch (Exception $ex) {
    echo $ex->getMessage();
}