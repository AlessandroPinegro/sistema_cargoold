<?php
include_once __DIR__.'/../../modeloNegocio/almacen/AprobacionParcialNegocio.php';

$fecha = date("d/m/Y");
//$fecha = '01/12/2017';

$dataPPend=  AprobacionParcialNegocio::create()->obtenerPendientePorAprobarXFechaProgramada($fecha);
$res1=  AprobacionParcialNegocio::create()->guardarEmailEnvioPorAprobar($dataPPend,$fecha);

echo $res1;
