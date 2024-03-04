<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContVoucherDetalle extends ModeloBase {

    /**
     * 
     * @return ContVoucherDetalle
     */
    static function create() {
        return parent::create();
    }

    public function guardarContVoucherDetalle($voucherId, $documentoId, $operacionDetalleId, $cuentaId, $centroCostoId, $monto, $tipoNegocioVoucher, $usuarioId, $personaContable = NULL, $fechaContable = NULL, $monedaIdContable = NULL, $tipoCambio = NULL) {
        $this->commandPrepare("sp_cont_voucher_detalle_guardar");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cont_operacion_tipo_detalle_id", $operacionDetalleId);
        $this->commandAddParameter(":vin_plan_contable_id", $cuentaId);
        $this->commandAddParameter(":vin_centro_costo_id", $centroCostoId);
        $this->commandAddParameter(":vin_monto", $monto);
        $this->commandAddParameter(":vin_tipo_negocio", $tipoNegocioVoucher);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_persona_contable_id", $personaContable);
        $this->commandAddParameter(":vin_fecha_contable", $fechaContable);
        $this->commandAddParameter(":vin_moneda_contable_id", $monedaIdContable);
        $this->commandAddParameter(":vin_tipo_cambio_contable", $tipoCambio);
        return $this->commandGetData();
    }

    public function obtenerContVoucherDetalleXVoucherId($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_obtenerXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function obtenerContVoucherDetalleMontoTotalesXVoucherId($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_obtenerMontoTotalesXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }

    public function obtenerVoucherDetalleXDocumentoId($documentoId) {
        $this->commandPrepare("sp_cont_voucher_detalle_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId); 
        return $this->commandGetData();
    }

    public function anularXVoucherId($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_anularXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }
    
     public function obtenerUltimaFechaContabilizacion($voucherId) {
        $this->commandPrepare("sp_cont_voucher_detalle_utimaFechaContaXVoucherId");
        $this->commandAddParameter(":vin_cont_voucher_id", $voucherId);
        return $this->commandGetData();
    }
}
