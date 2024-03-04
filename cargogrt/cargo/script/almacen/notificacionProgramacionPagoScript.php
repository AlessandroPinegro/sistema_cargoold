<?php
include_once __DIR__.'/../../modeloNegocio/almacen/ProgramacionPagoNegocio.php';

$fecha = date("d/m/Y");

$dataPPend=  ProgramacionPagoNegocio::create()->obtenerPendientePorLiberarXFechaProgramada($fecha);
$res1=  ProgramacionPagoNegocio::create()->guardarEmailEnvioProgramacionPagoPendientePorLiberar($dataPPend,$fecha);

echo $res1;

$dataPLib=  ProgramacionPagoNegocio::create()->obtenerLiberadoPendienteDePagoXFechaProgramada($fecha);
$res2=  ProgramacionPagoNegocio::create()->guardarEmailEnvioProgramacionPagoLiberadoPendienteDePago($dataPLib,$fecha);

echo $res2;