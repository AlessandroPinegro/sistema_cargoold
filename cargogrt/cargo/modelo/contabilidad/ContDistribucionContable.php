<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ContDistribucionContable extends ModeloBase {

    /**
     * 
     * @return ContDistribucionContable
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContDistribucionContableXDocumentoId($documentoId) {
        $this->commandPrepare("sp_cont_distribucion_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function anularDistribucionContableXDocumentoId($documentoId) {
        $this->commandPrepare("sp_cont_distribucion_anularXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function guardarContDistribucionContable($linea, $documentoId, $contOperacionId, $planContableId, $centroCostoId, $monto, $porcentaje, $usuarioId) {
        $this->commandPrepare("sp_cont_distribucion_contable_guardar");
        $this->commandAddParameter(":vin_linea", $linea);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionId);
        $this->commandAddParameter(":vin_plan_contable_id", $planContableId);
        $this->commandAddParameter(":vin_centro_costo_id", $centroCostoId);
        $this->commandAddParameter(":vin_monto", $monto);
        $this->commandAddParameter(":vin_porcentaje", $porcentaje);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

}
