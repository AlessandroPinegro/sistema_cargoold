<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ConceptoGasto extends ModeloBase {

    /**
     * 
     * @return ConceptoGasto
     */
    static function create() {
        return parent::create();
    }

    public function listarConceptoGasto($empresaId) {
        $this->commandPrepare("sp_concepto_gasto_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerConceptoGasto($id) {
        $this->commandPrepare("sp_concepto_gasto_obtenerXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function guardarConceptoGasto($codigo, $descripcion, $estado, $usuarioId, $conceptoGatoId, $empresaId) {
        $this->commandPrepare("sp_concepto_gasto_guardar");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $conceptoGatoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function cambiarEstadoConceptoGasto($id) {
        $this->commandPrepare("sp_concepto_gasto_cambiarEstadoXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function eliminarConceptoGasto($id) {
        $this->commandPrepare("sp_concepto_gasto_eliminarXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

}
