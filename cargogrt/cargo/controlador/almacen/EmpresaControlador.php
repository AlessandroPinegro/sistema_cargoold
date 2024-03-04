<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class EmpresaControlador extends AlmacenIndexControlador {

    public function getDataGridEmpresa() {
        return EmpresaNegocio::create()->getDataEmpresa();
    }
    public function obtenerXUsuarioId($usuarioId) {
        return EmpresaNegocio::create()->obtenerXUsuarioId();
    }
}


