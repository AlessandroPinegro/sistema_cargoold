<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Periodo extends ModeloBase {
    /**
     * 
     * @return Periodo
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerPeridoParaNumeracion() {
        $this->commandPrepare("sp_periodo_obtenerParaNumeracion");
        return $this->commandGetData();
    }    
}