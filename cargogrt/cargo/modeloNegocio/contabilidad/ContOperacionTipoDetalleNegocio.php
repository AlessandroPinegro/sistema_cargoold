<?php

require_once __DIR__ . '/../../modelo/contabilidad/ContOperacionTipoDetalle.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class ContOperacionTipoDetalleNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ContOperacionTipoDetalleNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContOperacionTipoDetalleXContOperacionTipoId($id) {
        return ContOperacionTipoDetalle::create()->obtenerContOperacionTipoDetalleXContOperacionTipoId($id);
    }

}
