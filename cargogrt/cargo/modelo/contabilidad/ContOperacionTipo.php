<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContOperacionTipo extends ModeloBase {

    /**
     * 
     * @return ContOperacionTipo
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContOperacionTipoXMovimientoTipoId($movimientoTipoId) {
        $this->commandPrepare("sp_cont_operacion_tipo_obtenerXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentoTipoContOperacionTipoXMovimientoTipoId($movimientoTipoId) {
        $this->commandPrepare("sp_documento_tipo_cont_operacion_tipo_obtenerXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }   
    
    public function obtenerContOperacionTipoXId($id) {
        $this->commandPrepare("sp_cont_operacion_tipo_XId");
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $id);
        return $this->commandGetData();
    }
}
