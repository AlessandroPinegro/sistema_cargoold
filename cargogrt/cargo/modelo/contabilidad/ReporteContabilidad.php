<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ReporteContabilidad extends ModeloBase {

    /**
     * 
     * @return ReporteContabilidad
     */
    static function create() {
        return parent::create();
    }

    public function obtenerReporteSumasMensualXCriterios($empresaId, $periodoId) {
        $this->commandPrepare("sp_cont_voucher_obtenerReporteSumasXPeriodoIdXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function obtenerReporteSumasAnualXCriterios($empresaId, $anio) {
        $this->commandPrepare("sp_cont_voucher_obtenerReporteSumasXAnioXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_anio", $anio);
        return $this->commandGetData();
    }

    public function obtenerReporteSumasMensualGeneralXCriterios($empresaId, $periodoId) {
        $this->commandPrepare("sp_cont_voucher_balanceGeneralXPeriodoIdXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function obtenerReporteSumasAnualGeneralXCriterios($empresaId, $anio) {
        $this->commandPrepare("sp_cont_voucher_balanceGeneralXAnioXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_anio", $anio);
        return $this->commandGetData();
    }

    public function obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad) {
        $this->commandPrepare("sp_cont_voucher_estado_financiero_funcionXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_valor_busquedad", $valorBusquedad);
        return $this->commandGetData();
    }

    public function obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad) {
        $this->commandPrepare("sp_cont_voucher_estado_financiero_naturalezaXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_valor_busquedad", $valorBusquedad);
        return $this->commandGetData();
    }

}
