<?php
include_once __DIR__.'/../../modeloNegocio/almacen/MovimientoNegocio.php';
include_once __DIR__.'/../../util/EmailEnvioUtil.php';

MovimientoNegocio::create()->enviarCorreoDespachoPedido(29318,1);

//$email = new EmailEnvioUtil();
//$email->envio("niltoncleonl@hotmail.com", null, "Prueba Envio", "Cuerpo de correo prueba", null, null);
//$email->envio("klujan@imaginatecperu.com", null, "Prueba Envio", "Cuerpo de correo prueba");

