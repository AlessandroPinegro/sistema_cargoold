<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class MisEncomiendasControlador extends ControladorBase {

    public function listarPedidosUsuario() {
        $usuarioId = $this->getUsuarioId();
        return DocumentoNegocio::create()->listarPedidosUsuario($usuarioId);
    }

  
}
