<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContVoucher extends ModeloBase {

    /**
     * 
     * @return ContVoucher
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContVoucherXId($id) {
        $this->commandPrepare("sp_cont_voucher_obtenerXId");
        $this->commandAddParameter(":vin_cont_voucher_id", $id);
        return $this->commandGetData();
    }
    
    public function transpasarDetalleVoucherXVoucherId($voucherId, $voucherNuevoId) {
        $this->commandPrepare("sp_cont_voucher_actualizarDetalleNuevoXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        $this->commandAddParameter(":vin_cont_voucher_nuevo_id", $voucherNuevoId);
        return $this->commandGetData();
    }

    public function anularXDocumentoIdXContOperacionTipoId($documentoId, $contOperacionTipoId) {
        $this->commandPrepare("sp_cont_voucher_anularXDocumentoIdXOperacionTipoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        return $this->commandGetData();
    }

    // MODIFICAR
    public function anularContVoucherXCriterios($documentoId, $contOperacionTipoId = NULL, $identificadorRegistro = NULL) {
        $this->commandPrepare("sp_cont_voucher_anularXCriterios");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        $this->commandAddParameter(":vin_identificador_registro", $identificadorRegistro);
        return $this->commandGetData();
    }

    public function guardarContVoucher($operacionTipoId, $libroId = NULL, $periodoId, $monedaId, $glosa = NULL, $usuarioId) {
        $this->commandPrepare("sp_cont_voucher_guardar");
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $operacionTipoId);
        $this->commandAddParameter(":vin_cont_libro_id", $libroId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_glosa", $glosa);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarContVoucherDetalle($voucherId, $operacionDetalleId, $planContableCodigo, $centroCostoCodigo = NULL, $monto, $documentoId = NULL, $personaId = NULL, $monedaId = NULL, $fecha = NULL, $tipoCambio = NULL, $usuarioId, $banderaReversa = NULL, $montoSoles = NULL) {
        $this->commandPrepare("sp_cont_voucher_detalle_guardar");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        $this->commandAddParameter(":vin_cont_operacion_tipo_detalle_id", $operacionDetalleId);
        $this->commandAddParameter(":vin_plan_contable_codigo", $planContableCodigo);
        $this->commandAddParameter(":vin_centro_costo_codigo", $centroCostoCodigo);
        $this->commandAddParameter(":vin_monto", $monto);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_persona_contable_id", $personaId);
        $this->commandAddParameter(":vin_moneda_contable_id", $monedaId);
        $this->commandAddParameter(":vin_fecha_contable", $fecha);
        $this->commandAddParameter(":vin_tipo_cambio_contable", $tipoCambio);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_bandera_reversa", $banderaReversa);
        $this->commandAddParameter(":vin_monto_soles", $montoSoles);
        return $this->commandGetData();
    }

    public function obtenerContVoucherDetalleMontoTotalesXVoucherId($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_obtenerMontoTotalesXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function anularContVoucherXId($id) {
        $this->commandPrepare("sp_cont_voucher_anularXContVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $id);
        return $this->commandGetData();
    }

    public function actualizarContVoucherXId($id, $monedaId, $glosa) {
        $this->commandPrepare("sp_cont_voucher_actualizar");
        $this->commandAddParameter(":vin_cont_voucher_id", $id);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_glosa", $glosa);
        return $this->commandGetData();
    }

    public function guardarVoucherRelacion($voucherId, $identificadorId, $identificadorNegocio, $usuarioId) {
        $this->commandPrepare("sp_cont_voucher_relacion_guardar");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        $this->commandAddParameter(":vin_identificador_id", $identificadorId);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerContVoucherRelacionXCriterios($documentoId, $identificadorNegocio, $clasificacionLibro) {
        $this->commandPrepare("sp_cont_voucher_relacion_obtenerXCriterios");
        $this->commandAddParameter(":vin_identificador_id", $documentoId);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        $this->commandAddParameter(":vin_clasificacion_libro", $clasificacionLibro);
        return $this->commandGetData();
    }

    public function anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio) {
        $this->commandPrepare("sp_cont_voucher_relacion_anularXCriterios");
        $this->commandAddParameter(":vin_identificador_id", $identificadorId);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio); 
        return $this->commandGetData();
    }

    public function anularDetalleXVoucherId($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_anularXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function guardarVoucherIdXDocumentoId($voucherId, $documentoId) {
        $this->commandPrepare("sp_documento_actualizarContVoucherId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function obtenerMontoTotalRedondeado($voucherId) {
        $this->commandPrepare("sp_cont_voucher_obtener_monto_total_redondeadoXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function obtenerContVoucherRelacionXIndetificadorIdXIdentificadorNegocio($identificadorId, $identificadorNegocio) {
        $this->commandPrepare("sp_cont_voucher_relacion_obtenerXIdentificadorId");
        $this->commandAddParameter(":vin_identificador_id", $identificadorId);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        return $this->commandGetData();
    }

    public function obtenerSaldoCuentaXPeridoIdXCodigo($periodoId, $codigoCuenta, $banderaAgrupador, $exigirMoneda) {
        $this->commandPrepare("sp_cont_voucher_obtenerSaldoAcumuladoXPeriodoIdXCodigo");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_plan_contable_codigo", $codigoCuenta);
        $this->commandAddParameter(":vin_agrupador", $banderaAgrupador);
        $this->commandAddParameter(":vin_ambas_monedas", $exigirMoneda);
        return $this->commandGetData();
    }

}
