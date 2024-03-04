<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ActivoFijo extends ModeloBase {

    /**
     * 
     * @return ActivoFijo
     */
    static function create() {
        return parent::create();
    }

    public function obtenerActivosFijos($empresaId) {
        $this->commandPrepare("sp_cont_activo_fijo_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerExcelActivosFijoSunat($activoFijoId) {
        $this->commandPrepare("sp_activo_fijo_obtenerFormatoExcelSunat");
        $this->commandAddParameter(":vin_cont_activo_fijo", $activoFijoId);
        return $this->commandGetData();
    }

    public function obtenerAsientoActivoFijoXPeriodoId($periodoId) {
        $this->commandPrepare("sp_activo_fijo_obtenerAsientoContableXPeriodoId");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function obtenerAsientoActivoFijoXBienDepreciacionId($bienDepreciacionId) {
        $this->commandPrepare("sp_activo_fijo_obtenerAsientoContableXBienDepreciacionId");
        $this->commandAddParameter(":vin_bien_depreciacion_id", $bienDepreciacionId);
        return $this->commandGetData();
    }

    public function generarDepreciacionXPeriodoId($periodoId, $usuarioId) {
        $this->commandPrepare("sp_cont_activo_fijo_generar_depreciacion");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function darDeBajaActivoFijo($bienId, $periodoId, $usuarioId) {
        $this->commandPrepare("sp_cont_activo_fijo_generar_dar_bajaXBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function darDeBajaActivoFijoXPeriodoId($periodoId) {
        $this->commandPrepare("sp_cont_activo_fijo_dar_bajaXPeriodoId");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }
    
    public function obtenerActivosFijosDetreciadosXPeriodoId($periodoId) {
        $this->commandPrepare("sp_cont_activo_fijo_obtenerDepreciadosXPeriodoId");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }
    
    

}
