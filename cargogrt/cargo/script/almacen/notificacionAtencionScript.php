<?php
include_once __DIR__.'/../../modeloNegocio/almacen/ProgramacionAtencionNegocio.php';

$fecha = date("d/m/Y");
//$fecha = '01/12/2017';
$patencionEstado=1;
$dataProgramado= ProgramacionAtencionNegocio::create()->obtenerPAtencionXEstadoXFechaProgramada($patencionEstado,$fecha);

$plantillaId=13;
$descripcionCorreo='Detalle de atenciones programadas hasta la fecha actual';
$tituloCorreo='ATENCIONES PROGRAMADAS';
$asuntoCorreo='Atenciones programadas';
$res1=  ProgramacionAtencionNegocio::create()->guardarEmailEnvioPAtencion($dataProgramado,$asuntoCorreo,$plantillaId,$descripcionCorreo,$tituloCorreo);

echo $res1;

$patencionEstado=4;
$dataComprometido= ProgramacionAtencionNegocio::create()->obtenerPAtencionXEstadoXFechaProgramada($patencionEstado,$fecha);

$plantillaId=14;
$descripcionCorreo='Detalle de atenciones comprometidas hasta la fecha actual, libere las atenciones para que puedan ser atendidas';
$tituloCorreo='ATENCIONES COMPROMETIDAS';
$asuntoCorreo='Atenciones comprometidas';
$res2=  ProgramacionAtencionNegocio::create()->guardarEmailEnvioPAtencion($dataComprometido,$asuntoCorreo,$plantillaId,$descripcionCorreo,$tituloCorreo);

echo $res2;

$patencionEstado=3;
$dataLiberado= ProgramacionAtencionNegocio::create()->obtenerPAtencionXEstadoXFechaProgramada($patencionEstado,$fecha);

$plantillaId=15;
$descripcionCorreo='Detalle de atenciones liberadas hasta la fecha actual, las atenciones ya pueden ser atendidas';
$tituloCorreo='ATENCIONES LIBERADAS';
$asuntoCorreo='Atenciones liberadas';
$res3=  ProgramacionAtencionNegocio::create()->guardarEmailEnvioPAtencion($dataLiberado,$asuntoCorreo,$plantillaId,$descripcionCorreo,$tituloCorreo);

echo $res3;