<?php
include_once __DIR__.'/../../modeloNegocio/almacen/MovimientoNegocio.php';

//$fecha = date("d/m/Y");
//$fecha = '01/12/2017';
$data= DocumentoNegocio::create()->obtenerDataAlmacenVirtualXDocumentoId(264);

$plantillaId=18;
$descripcionCorreo='Lista de ingresos de almacén virtual que están pendientes de reposición';
$tituloCorreo='Pendientes por reposición en almacén virtual';
$asuntoCorreo='Pendientes por reposición en almacén virtual';
$res1=  MovimientoNegocio::create()->guardarEmailEnvioPendientesXReposicion($data,$asuntoCorreo,$plantillaId,$descripcionCorreo,$tituloCorreo);

echo $res1;

