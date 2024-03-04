<?php

require_once __DIR__ . '/../../modelo/contabilidad/ContVoucherDetalle.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class ContVoucherDetalleNegocio extends ModeloNegocioBase {

    const CLASIFICACION_VOUCHER_COMPRAS = 1;

    /**
     * 
     * @return ContVoucherDetalleNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerVoucherDetalleXDocumentoId($documentoId) {
        return ContVoucherDetalle::create()->obtenerVoucherDetalleXDocumentoId($documentoId);
    }

    public function guardarContVoucherDetalle($voucherId, $documentoId, $operacionDetalleId, $cuentaId, $centroCostoId, $monto, $tipoNegocioVoucher, $usuarioId, $personaContable = NULL, $fechaContable = NULL, $monedaIdContable = NULL, $tipoCambio = NULL) {
        return ContVoucherDetalle::create()->guardarContVoucherDetalle($voucherId, $documentoId, $operacionDetalleId, $cuentaId, $centroCostoId, $monto, $tipoNegocioVoucher, $usuarioId, $personaContable, $fechaContable, $monedaIdContable, $tipoCambio);
    }

    public function obtenerContVoucherDetalleMontoTotalesXVoucherId($voucherId) {
        return ContVoucherDetalle::create()->obtenerContVoucherDetalleMontoTotalesXVoucherID($voucherId);
    }

    public function obtenerContVoucherDetalleXVoucherId($voucherId) {
        return ContVoucherDetalle::create()->obtenerContVoucherDetalleXVoucherId($voucherId);
    }

    public function anularXVoucherId($voucherId) {
        return ContVoucherDetalle::create()->anularXVoucherId($voucherId);
    }

}
