<?php

include_once __DIR__ . '/../../../controlador/almacen/MailControlador.php';
$control = new MailControlador();
$listadatos = json_decode($_POST['lista']);
$flagRecaptcha = json_decode($_POST['flag_recaptcha']);
//$responseRecaptcha = json_decode($_POST['response_recaptcha']);
foreach ($listadatos as $l) {
    foreach ($l as $c) {
        $op = $c->search;
    }
}
$respuesta = array();
$key = '';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
$decrypted = Util::desencripta($op);
$decrypted;  //Devuelve el string desencriptado
$parametros = explode('&', $decrypted);
list($tipoConsulta, $consultaTipoId) = explode('=', $parametros[0]);
list($identificadorPedido, $id) = explode('=', $parametros[1]);

//if ($consultaTipoId == 2 && $flagRecaptcha == 1) {
//    $keyRecaptcha = "6Lfc9RYjAAAAAHK60BXasQjB7m_I1gcKJXVO0vZ5";
//    $validation = Util::validateReCaptCha($keyRecaptcha, $responseRecaptcha);
//    if (!$validation) {
//        $respuesta = new stdClass();
//        $respuesta->tipo = 2;
//        $respuesta->mensaje = "Recaptcha invalido";
//        echo json_encode($respuesta);
//    }
//}

$consultaTipoId = ($consultaTipoId == 2 && $flagRecaptcha == 1 ? 3 : $consultaTipoId);

$dataPedido = $control->obtenerTranckingXPaquete($id, $consultaTipoId);
echo json_encode($dataPedido);
