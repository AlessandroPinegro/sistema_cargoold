<?php
include_once __DIR__.'/../../modeloNegocio/almacen/ProgramacionPagoNegocio.php';
$plantillaId=20;
$data= ProgramacionPagoNegocio::create()->obtenerFacturasXVencer();
 if (!ObjectUtil::isEmpty($data)) {
     $re=ProgramacionPagoNegocio::create()->guardarEmailEnvioFacturasxVencer($data, $plantillaId);
 }

