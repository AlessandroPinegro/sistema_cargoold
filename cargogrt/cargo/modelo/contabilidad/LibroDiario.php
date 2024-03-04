<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class LibroDiario extends ModeloBase {

    /**
     * 
     * @return LibroDiario
     */
    static function create() {
        return parent::create();
    }

    public function listarLibroDiarioXCriterios($empresaId, $personaId = null, $contLibroId = null, $periodoIdInicio = null, $periodoIdFin = null, $fechaInicio = null, $fechaFin = null) {
        $this->commandPrepare("sp_cont_voucher_obtenerLibroDiario");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_cont_libro_id", $contLibroId);
        $this->commandAddParameter(":vin_periodo_id_inicio", $periodoIdInicio);
        $this->commandAddParameter(":vin_periodo_id_fin", $periodoIdFin);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    public function obtenerDocumento($empresaId) {
        $this->commandPrepare("sp_documento_obtenerLibroDiario");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

}
