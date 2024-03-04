<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class RegistroVentas extends ModeloBase {

    /**
     * 
     * @return RegistroVentas
     */
    static function create() {
        return parent::create();
    }  
    

    public function listarRegistroVentasXCriterios($empresaId, $personaId = null, $periodoIdInicio = null, $periodoIdFin = null, $fechaInicio = null, $fechaFin = null) {
        $this->commandPrepare("sp_documento_obtenerRegistroVentasXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_periodo_id_inicio", $periodoIdInicio);
        $this->commandAddParameter(":vin_periodo_id_fin", $periodoIdFin);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

}
