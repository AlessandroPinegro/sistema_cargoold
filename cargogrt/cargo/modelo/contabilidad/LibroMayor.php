<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class LibroMayor extends ModeloBase {

    /**
     * 
     * @return LibroMayor
     */
    static function create() {
        return parent::create();
    }

    public function listarLibroMayorXCriterios($empresaId, $personaId = null, $periodoIdInicio = null, $periodoIdFin = null, $planContableCodigo = null) {
        $this->commandPrepare("sp_cont_voucher_obtenerLibroMayor");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_periodo_id_inicio", $periodoIdInicio);
        $this->commandAddParameter(":vin_periodo_id_fin", $periodoIdFin);
        $this->commandAddParameter(":vin_plan_contable_codigo", $planContableCodigo);
        return $this->commandGetData();
    }

    public function saldoInicialXEmpresaIdXPeriodoId($empresaId, $periodoId, $personaId = null, $planContableCodigo = null) {
        $this->commandPrepare("sp_cont_voucher_obtenerSaldosInicialesLibroMayor");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_plan_contable_codigo", $planContableCodigo);
        return $this->commandGetData();
    }

}
