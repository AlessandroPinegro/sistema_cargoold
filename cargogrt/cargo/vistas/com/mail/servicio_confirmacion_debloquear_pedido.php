<?php

include_once __DIR__ . '/../../../controlador/almacen/MailControlador.php';
$control = new MailControlador();
$listadatos = json_decode($_POST['lista']);
$accion = $_POST['accion'];
$respuesta = array();

switch ($accion) {
    case 'obtenerDatos':

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
        list($tipo, $tipoAccion) = explode('=', $parametros[1]);
        list($documento, $documentoId) = explode('=', $parametros[2]);

        $respuesta = array(array('usuario_id' => $usuarioId, 'tipo_accion' => $tipoAccion, 'documento_id' => $documentoId));
        break;

    case 'aprobar':

        $usuarioId = $_POST['usuarioId'];
        $documentoId = $_POST['documentoId'];
        $respuesta = $control->registrarDesbloquePedido($documentoId, $usuarioId);

        break;
}

echo json_encode($respuesta);
