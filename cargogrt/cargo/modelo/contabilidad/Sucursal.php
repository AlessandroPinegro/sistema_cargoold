<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Sucursal extends ModeloBase {
    /**
     * 
     * @return Sucursal
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerXEmpresaId($empresaId) {
        $this->commandPrepare("sp_sucursal_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
}