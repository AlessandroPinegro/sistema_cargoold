<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Periodo extends ModeloBase {

    /**
     * 
     * @return Periodo
     */
    static function create() {
        return parent::create();
    }

    public function guardarPeriodo($anio, $mes, $estadoId, $usuarioId, $empresaId, $periodoId) {
        $this->commandPrepare("sp_periodo_guardar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $periodoId);
        return $this->commandGetData();
    }

    public function listarPeriodo($empresaId) {
        $this->commandPrepare("sp_periodo_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function cambiarEstado($id) {
        $this->commandPrepare("sp_periodo_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function eliminar($id) {
        $this->commandPrepare("sp_periodo_eliminar");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPeriodoXid($id) {
        $this->commandPrepare("sp_periodo_obtenerXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarIndicador($id, $indicador) {
        $this->commandPrepare("sp_periodo_cambiarIndicador");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_indicador", $indicador);
        return $this->commandGetData();
    }

    public function cambiarIndicadorContable($id, $indicador) {
        $this->commandPrepare("sp_periodo_cambiarIndicadorContable");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_indicador_contable", $indicador);
        return $this->commandGetData();
    }

    public function guardarPeriodoCierre($periodoId, $bienId, $unidadMedidaId, $stockInv, $stockCV, $precioCompra, $usuarioId) {
        $this->commandPrepare("sp_periodo_cierre_guardar");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_stock_inv", $stockInv);
        $this->commandAddParameter(":vin_stock_cv", $stockCV);
        $this->commandAddParameter(":vin_precio_compra", $precioCompra);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPeriodoAbiertoXEmpresa($empresaId) {
        $this->commandPrepare("sp_periodo_obtenerAbiertoXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerConfiguracionesInicialesGenerarPeriodoPorAnio() {
        $this->commandPrepare("sp_periodo_obtenerAniosSinGenerar");
        return $this->commandGetData();
    }

    public function obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes) {
        $this->commandPrepare("sp_periodo_obtenerXEmpresaXAnioXMes");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_mes", $mes);
        return $this->commandGetData();
    }

    public function obtenerPeriodoCierreEntreFechas($inicio, $fin) {
        $this->commandPrepare("sp_periodo_cierre_bien_obtener_cantidad_costo_final");
        $this->commandAddParameter(":vin_fecha_inicio", $inicio);
        $this->commandAddParameter(":vin_fecha_fin", $fin);
        return $this->commandGetData();
    }

    public function periodoCierreBienGuardar($periodoId, $bienId, $unidadMedidaId, $monedaId, $cantidadFinal, $costoUnitarioFinal, $usuarioId) {
        $this->commandPrepare("sp_periodo_cierre_bien_guardar");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_cantidad", $cantidadFinal);
        $this->commandAddParameter(":vin_costo_unitario", $costoUnitarioFinal);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPeriodosCerradosMayorIgualXPeriodoId($periodoId) {
        $this->commandPrepare("sp_periodo_obtenerCerradosMayorIgualXPeriodoId");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function periodoCierreBienEliminarXPeriodoId($periodoId) {
        $this->commandPrepare("sp_periodo_cierre_bien_eliminarXPeriodoId");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        return $this->commandGetData();
    }

    public function obtenerPeriodoXEmpresa($empresaId) {
        $this->commandPrepare("sp_periodo_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerUltimoPeriodoActivoXEmpresa($empresaId) {
        $this->commandPrepare("sp_periodo_obtenerUltimoPeriodoActivoXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function actualizarBanderaModificacion($id, $banderaContabilidad) {
        $this->commandPrepare("sp_periodo_actualizarBanderaContabilidad");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_bandera_modificacion_contable", $banderaContabilidad);
        return $this->commandGetData();
    }
    
      public function obtenerPeriodoIdXFecha($m,$y) {
        $this->commandPrepare("sp_periodo_obtenerPeriodoId");
        $this->commandAddParameter(":vin_mes", $m);
         $this->commandAddParameter(":vin_anio", $y);
        return $this->commandGetData();
    }
    

}
