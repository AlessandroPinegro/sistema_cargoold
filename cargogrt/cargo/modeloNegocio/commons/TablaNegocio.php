<?php

require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class TablaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return TablaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXPadreId($padreId) {
        return Tabla::create()->obtenerXPadreId($padreId);        
    }
}