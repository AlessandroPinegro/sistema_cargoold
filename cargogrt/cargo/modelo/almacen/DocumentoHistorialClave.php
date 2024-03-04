<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Actividad
 *
 * @author CHL
 */
class DocumentoHistorialClave extends ModeloBase {

    /**
     * 
     * @return DocumentoHistorialClave
     */
    static function create() {
        return parent::create();
    }

    public function guardar($documentoId, $tipo, $usuarioId) {
        $this->commandPrepare("sp_documento_historial_clave_guardar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerXDocumentoId($documentoId) {
        $this->commandPrepare("sp_documento_historial_clave_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function anularXDocumentoIdXTipo($documentoId, $tipo = NULL) {
        $this->commandPrepare("sp_documento_historial_clave_anularXDocumentoIdXTipo");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

}
