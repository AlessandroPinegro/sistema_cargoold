<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once __DIR__.'/../../modeloNegocio/almacen/BienNegocio.php';

$respuesta = BienNegocio::create()->enviarNotificacionActivosFijosNoInternados();

echo $respuesta;