<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class PlanContable extends ModeloBase {

    const PLAN_CONTABLE_CODIGO_EAR_TERCERO = '1691';
    const PLAN_CONTABLE_CODIGO_F_B_EMITIDAS = '4212';
    const PLAN_CONTABLE_CODIGO_RH_EMITIDAS = '4241';
    const PLAN_CONTABLE_CODIGO_IMPUESTO_PERCEPCION = '40113';
    const PLAN_DEPOSITO_GARANTIA = '1643';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA_COSTO = '20111';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_TRANSPORTE_COSTO = '20911';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_SEGURO_COSTO = '20912';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_MANUFACTURADA = '60121';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_SEGURO = '609122';
    const PLAN_CONTABLE_CODIGO_MERCADERIA_TRANSPORTE = '609121';
    const PLAN_CONTABLE_CODIGO_VENTA_MERCADERIA = '1212';
    const PLAN_CONTABLE_CODIGO_ACTIVO_FIJO_COBRANZA = '1653';
    
    const PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_GANANCIA = '7595';
    const PLAN_CONTABLE_CODIGO_AJUSTE_REDONDEO_PERDIDA = '6593';
    const PLAN_CONTABLE_FINANCIMIENTO = '4511';

//    const PLAN_CONTABLE_CODIGO_IMPUESTO_PERCEPCION = '40113';
    /**
     * 
     * @return PlanContable
     */
    static function create() {
        return parent::create();
    }

    public function listarPlanContablePadres($empresaId) {
        $this->commandPrepare("sp_plan_contable_padres_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerHijos($padreId) {
        $this->commandPrepare("sp_plan_contable_obtenerHijos");
        $this->commandAddParameter(":vin_plan_contable_padre_id", $padreId);
        return $this->commandGetData();
    }

    public function obtenerHijosSinCriterios() {
        $this->commandPrepare("sp_plan_contable_obtenerHijosSinCriterio");
        return $this->commandGetData();
    }

    public function obtenerHijosXCriterios($anio, $mes, $empresaId) {
        $this->commandPrepare("sp_plan_contable_obtenerHijosXCriterios");
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_empresaId", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerCuentaTipoActivos() {
        $this->commandPrepare("sp_cuenta_tipo_obtener_activos");
        return $this->commandGetData();
    }

    public function obtenerDimensionActivos() {
        $this->commandPrepare("sp_dimension_obtener_activos");
        return $this->commandGetData();
    }

    public function obtenerCuentaExigeActivos() {
        $this->commandPrepare("sp_cuenta_exige_activos");
        return $this->commandGetData();
    }

    public function obtenerCuentaNaturalezaActivos() {
        $this->commandPrepare("sp_cuenta_naturaleza_activos");
        return $this->commandGetData();
    }

    public function obtenerCuentasQueNoSonTitulos() {
        $this->commandPrepare("sp_plan_contable_obtenerCuentasQueNoSonTitulos");
        return $this->commandGetData();
    }

    public function obtenerCuentaXId($id) {
        $this->commandPrepare("sp_plan_contable_obtenerCuentaXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function guardarCuenta($codigo, $descripcion, $codigoEqui, $descripcionEqui, $estado, $cuentaTipo, $moneda, $naturalezaCuenta, $cuentaCargo, $cuentaAbono, $comoAjustar, $tipoCambio, $checkTitulo, $checkAjustar, $usuarioId, $cuentaId, $padreCuentaId, $empresaId) {

        $this->commandPrepare("sp_plan_contable_guardarCuenta");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_cuenta_equivalente_codigo", $codigoEqui);
        $this->commandAddParameter(":vin_cuenta_equivalente_descripcion", $descripcionEqui);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_cuenta_tipo_id", $cuentaTipo);
        $this->commandAddParameter(":vin_moneda_id", $moneda);
        $this->commandAddParameter(":vin_cuenta_naturaleza_id", $naturalezaCuenta);
        $this->commandAddParameter(":vin_cuenta_cargo_id", $cuentaCargo);
        $this->commandAddParameter(":vin_cuenta_abono_id", $cuentaAbono);
        $this->commandAddParameter(":vin_como_ajustar", $comoAjustar);
        $this->commandAddParameter(":vin_tipo_cambio", $tipoCambio);
        $this->commandAddParameter(":vin_titulo", $checkTitulo);
        $this->commandAddParameter(":vin_ajustar", $checkAjustar);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $cuentaId);
        $this->commandAddParameter(":vin_plan_contable_padre_id", $padreCuentaId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function eliminarPlanContableDimensionXPlanContableId($cuentaId) {
        $this->commandPrepare("sp_plan_contable_dimension_eliminarXPlanContableId");
        $this->commandAddParameter(":vin_plan_contable_id", $cuentaId);
        return $this->commandGetData();
    }

    public function guardarPlanContableDimension($dimensionId, $cuentaId, $usuarioCreacion) {
        $this->commandPrepare("sp_plan_contable_dimension_guardar");
        $this->commandAddParameter(":vin_plan_contable_id", $cuentaId);
        $this->commandAddParameter(":vin_dimension_id", $dimensionId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function eliminarPlanContableCuentaExigeXPlanContableId($cuentaId) {
        $this->commandPrepare("sp_plan_contable_cuenta_exige_eliminarXPlanContableId");
        $this->commandAddParameter(":vin_plan_contable_id", $cuentaId);
        return $this->commandGetData();
    }

    public function guardarPlanContableCuentaExige($cuentaExigeId, $cuentaId, $usuarioCreacion) {
        $this->commandPrepare("sp_plan_contable_cuenta_exige_guardar");
        $this->commandAddParameter(":vin_plan_contable_id", $cuentaId);
        $this->commandAddParameter(":vin_cuenta_exige_id", $cuentaExigeId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function eliminarCuenta($id) {
        $this->commandPrepare("sp_plan_contable_eliminarCuentaXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerCuentasAsientosAutomaticos() {
        $this->commandPrepare("sp_plan_contable_obtenerCuentasParaAsientosAutomaticos");
        return $this->commandGetData();
    }

    public function obtenerXCodigoInicial($codigoInicial) {
        $this->commandPrepare("sp_plan_contable_obtenerXCodigoInicial");
        $this->commandAddParameter(":vin_codigo_inicial", $codigoInicial);
        return $this->commandGetData();
    }

    public function obtenerXCodigo($codigo) {
        $this->commandPrepare("sp_plan_contable_obtenerXCodigo");
        $this->commandAddParameter(":vin_codigo", $codigo);
        return $this->commandGetData();
    }

    public function obtenerTodo() {
        $this->commandPrepare("sp_plan_contable_obtener");
        return $this->commandGetData();
    }

    public function obtenerXEmpresaId($empresaId) {
        $this->commandPrepare("sp_plan_contable_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

}
