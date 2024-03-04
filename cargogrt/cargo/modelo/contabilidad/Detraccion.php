<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Detraccion extends ModeloBase {

    /**
     * 
     * @return Detraccion
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDetraccionXEmpresaId($empresaId) {
        $this->commandPrepare("sp_detraccion_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

}
