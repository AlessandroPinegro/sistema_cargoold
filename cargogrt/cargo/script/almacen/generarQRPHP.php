<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once __DIR__ . '/vistas/com/util/Seguridad.php';
include_once __DIR__ . '/../../util/Configuraciones.php';
include_once __DIR__ . '/../../util/ObjectUtil.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/VehiculoNegocio.php';
include_once __DIR__ . '/../../controlador/almacen/PedidoContr.php';
include_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/PedidoNegocio.php';

//GENERACION DE QR
$Id=1;
//$Id=28803;
$data=VehiculoNegocio::create()->generaPDFQR($Id);
$data=PedidoNegocio::create()->imprimirQrPaquetesXPedido($Id,1);
echo $data;