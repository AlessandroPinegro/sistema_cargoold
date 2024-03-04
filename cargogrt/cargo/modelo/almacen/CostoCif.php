<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Actividad
 *
 * @author CHL
 */
class CostoCif extends ModeloBase {

    /**
     * 
     * @return CostoCif
     */
    static function create() {
        return parent::create();
    }

    public function generarPorDocumentoId($documentoId, $usuarioId) {
        $this->commandPrepare("sp_costo_cif_generarPorDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId) {
        $this->commandPrepare("sp_costo_cif_actualizarCostoUnitarioXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerCostoUnitarioDuaXDocumentoId($documentoId) {
        $this->commandPrepare("sp_costo_cif_obtenerCostoUnitarioXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerCostoCifDocumentoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_costo_cif_documento_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerPorMovimientoId($movimientoId) {
        $this->commandPrepare("sp_costo_cif_obtenerPorMovimientoId");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function inventarioPermValobtenerDataExcel($fechaInicio, $fechaFin) {
        $this->commandPrepare("sp_inventario_perm_val_obtenerDataExcel");
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    public function eliminarCostoCifXDocumentoId($documentoId) {
        $this->commandPrepare("sp_costo_cif_eliminarPorDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

}
