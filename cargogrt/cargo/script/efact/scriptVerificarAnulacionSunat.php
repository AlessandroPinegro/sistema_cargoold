<?php

include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';

try {

    $res = MovimientoNegocio::create()->verificarAnulacionSunat();

    echo $res;
//    var_dump($res);
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}