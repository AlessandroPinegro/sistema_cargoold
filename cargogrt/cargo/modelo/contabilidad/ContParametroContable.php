<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContParametroContable extends ModeloBase {

    const PLAN_CONTABLE_CODIGO_EAR_TERCERO = 'CC_EAR_TERCERO';
    const PLAN_CONTABLE_CODIGO_ANTICIPO_PROVEEDOR = 'CC_ANTICIPO_PROVEEDO';
    const PLAN_CONTABLE_CODIGO_F_B_EMITIDAS = 'CC_F_B_EMITIDAS';
    const PLAN_CONTABLE_CODIGO_RH_EMITIDAS = 'CC_RH_EMITIDAS';
    const PLAN_CONTABLE_CODIGO_IMPUESTO_PERCEPCION = 'CC_IMPUESTO_PERCEPCION';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA_COSTO = 'CC_MERCADERIA_MANUFACTURADA_COSTO';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_TRANSPORTE_COSTO = 'CC_MERCADERIA_TRANSPORTE_COSTO';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_SEGURO_COSTO = 'CC_MERCADERIA_SEGURO_COSTO';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA = 'CC_MERCADERIA_MANUFACTURADA';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_SEGURO = 'CC_MERCADERIA_SEGURO';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_TRANSPORTE = 'CC_MERCADERIA_TRANSPORTE';
    const PLAN_CONTABLE_CODIGO_VENTA_MERCADERIA = 'CC_VENTA_MERCADERIA';

    /**
     * 
     * @return ContParametroContable
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXCodigoXPeriodoId($codigo, $periodoId) {
        $this->commandPrepare("sp_cont_parametro_contable_obtenerXCodigoXPeriodoId");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function obtenerXEjercicioContableXEmpresaId($empresaId) {
        $this->commandPrepare("sp_cont_parametro_contable_obtenerEjercicioActivo");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

}
