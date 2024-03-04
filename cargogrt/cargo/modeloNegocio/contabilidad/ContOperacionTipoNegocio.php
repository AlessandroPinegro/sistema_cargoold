<?php

require_once __DIR__ . '/../../modelo/contabilidad/ContOperacionTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContOperacionTipoDetalleNegocio.php';

class ContOperacionTipoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ContOperacionTipoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContOperacionTipoXMovimientoTipoId($movimientoTipoId) {
        return ContOperacionTipo::create()->obtenerContOperacionTipoXMovimientoTipoId($movimientoTipoId);
    }
    
     public function obtenerDocumentoTipoContOperacionTipoXMovimientoTipoId($movimientoTipoId) {
        return ContOperacionTipo::create()->obtenerDocumentoTipoContOperacionTipoXMovimientoTipoId($movimientoTipoId);
    }
    public function obtenerContOperacionTipoXId($id) {
        return ContOperacionTipo::create()->obtenerContOperacionTipoXId($id);
    }
}
