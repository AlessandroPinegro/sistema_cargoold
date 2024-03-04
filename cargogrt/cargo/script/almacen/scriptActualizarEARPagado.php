<?php
include_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
$earId = $_GET['ear_id'];
$respuesta = PagoNegocio::create()->actualizarEstadoDesembolsadoSolicitudEar($earId, "1") ;
var_dump($respuesta);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

