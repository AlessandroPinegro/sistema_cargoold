<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Tabla extends ModeloBase {
    /**
     * 
     * @return Tabla
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerXPadreId($padreId) {
        $this->commandPrepare("sp_tabla_obtenerXPadreId");
        $this->commandAddParameter(":vin_padre_id", $padreId);
        return $this->commandGetData();
    }
}