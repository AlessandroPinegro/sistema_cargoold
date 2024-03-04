<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class Penalidad extends ModeloBase {

    /**
     * 
     * @return Penalidad
     */
    static function create() {
        return parent::create();
    }

    public function obtenerActivaXEmpreId($empresaId) {
        $this->commandPrepare("sp_penalidad_obtenerActivaXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function anularPenalidadDocumentoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_documento_penalidad_anularXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerPenalidadDocumentoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_documento_penalidad_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function registrar($empresaId, $descripcion, $usuarioId) {
        $this->commandPrepare("sp_penalidad_registrar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function registrarDocumentoPenalidad($documentoId, $penalidadId, $usuarioId,$monto) {
        $this->commandPrepare("sp_documento_penalidad_registrar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_penalidad_id", $penalidadId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_monto", $monto);
        return $this->commandGetData();
    }

}
