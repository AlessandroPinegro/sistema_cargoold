<?php

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modelo/contabilidad/ContParametroContable.php';

class ContParametroContableNegocio extends ModeloNegocioBase {    

    /**
     * 
     * @return ContParametroContableNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXCodigoXPeriodoId($codigo, $periodoId) {
        return ContParametroContable::create()->obtenerXCodigoXPeriodoId($codigo, $periodoId);
    }

}
