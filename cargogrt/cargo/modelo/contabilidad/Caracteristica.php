<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Caracteristica extends ModeloBase {
    /**
     * 
     * @return Caracteristica
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerCaracteristicasXTipo($tipo) {
        $this->commandPrepare("sp_caracteristica_obtenerXTipo");
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }
}