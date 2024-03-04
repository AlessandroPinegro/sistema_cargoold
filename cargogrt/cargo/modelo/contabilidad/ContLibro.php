<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContLibro extends ModeloBase {

    const CLASIFICACION_LIBRO_DIARO = 1;
    const CLASIFICACION_COMPRAS = 2;
    const CLASIFICACION_VENTAS = 3;
    const CLASIFICACION_CAJAYBANCOS = 4;
    
    const CONT_LIBRO_DIARIO_ID = 5;

    /**
     * 
     * @return ContLibro
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXClasificacion($clasificacion = NULL) {
        $this->commandPrepare("sp_cont_libro_XClasificacion");
        $this->commandAddParameter(":vin_clasificacion", $clasificacion);
        return $this->commandGetData();
    }

}
