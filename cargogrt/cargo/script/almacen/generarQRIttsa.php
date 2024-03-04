<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once __DIR__ . '/../../util/ObjectUtil.php';

include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
include_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
//GENERACION DE QR
$archivo='TINA';
$textoQR= 'DICTADORA -- NO ME PREGUNTES NADA -- ME VALE Y ME VALES --- SI NO ME ENTIENDES TU PROBLEMA';

        
$resultado =MovimientoNegocio::create()->generarQR($archivo,$textoQR);