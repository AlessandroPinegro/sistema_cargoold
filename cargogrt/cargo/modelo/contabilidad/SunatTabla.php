<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class SunatTabla extends ModeloBase {
    /**
     * 
     * @return SunatTabla
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerDetalleXSunatTablaId($sunatTablaId) {
        $this->commandPrepare("sp_sunat_tabla_detalle_obtenerXSunatTablaId");
        $this->commandAddParameter(":vin_sunat_tabla_id", $sunatTablaId);
        return $this->commandGetData();
    }
}