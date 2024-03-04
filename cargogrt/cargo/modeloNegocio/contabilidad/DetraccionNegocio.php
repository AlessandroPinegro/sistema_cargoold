<?php

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modelo/contabilidad/Detraccion.php';

class DetraccionNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return DetraccionNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDetraccionXEmpresaId($empresaId) {
        return Detraccion::create()->obtenerDetraccionXEmpresaId($empresaId);
    }
}
