<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContOperacionTipoDetalle extends ModeloBase {

    /**
     * 
     * @return ContOperacionTipoDetalle
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContOperacionTipoDetalleXContOperacionTipoId($id) {
        $this->commandPrepare("sp_cont_operacion_tipo_detalle_XId");
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $id);
        return $this->commandGetData();
    }

}
