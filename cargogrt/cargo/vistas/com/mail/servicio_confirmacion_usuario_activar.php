<?php

include_once __DIR__ . '/../../../controlador/almacen/MailControlador.php';
$control = new MailControlador();
$listadatos = json_decode($_POST['lista']);
$accion = $_POST['accion'];
$respuesta = array();

switch ($accion) {
    case 'acivarUsuario':

        foreach ($listadatos as $l) {
            foreach ($l as $c) {
                $op = $c->search;
            }
        }

        $key = '';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $decrypted = Util::desencripta($op);
        $decrypted;  //Devuelve el string desencriptado
        $parametros = explode('&', $decrypted);
        list($usuario, $usuarioId) = explode('=', $parametros[0]);

        $respuesta = $control->activarUsuarioXUsuarioId($usuarioId);
        break;
}

echo json_encode($respuesta);
