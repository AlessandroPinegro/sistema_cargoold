<?php

include_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
include_once __DIR__.'/../../util/DateUtil.php';
date_default_timezone_set("America/Lima");//Zona horaria de Peru

$fecha = date("d/m/Y");  
$equivalenciaSunat = TipoCambioNegocio::create()->obtenerEquivalenciaSunatXFecha($fecha);
$monedaData = TipoCambioNegocio::create()->obtenerMonedaDistintaBase();

$monedaId = $monedaData[0]['id'];
$equivalenciaCompra = $equivalenciaSunat['compra'];
$equivalenciaVenta = $equivalenciaSunat['venta'];
$tipoCambioId = null;
$usuCreacion = 1;

$res=TipoCambioNegocio::create()->crearTipoCambio($tipoCambioId,$monedaId,$fecha,$equivalenciaCompra ,$equivalenciaVenta,$usuCreacion);

echo $res[0]['vout_mensaje'];