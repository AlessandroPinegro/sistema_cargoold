<?php
require_once __DIR__.'/../util/Seguridad.php';
include_once __DIR__.'/../../../util/Configuraciones.php';     
require_once __DIR__.'/../../../controlador/almacen/PdfControlador.php';
include_once __DIR__.'/../../../util/ObjectUtil.php';

$documentoId = $_POST['documentoIdHidden'];
$correo = $_POST['correoHidden'];

crearPdfDocumento($documentoId,$correo);