<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Tipo de documento
 *
 * @author CHL
 */
class DocumentoTipoDato extends ModeloBase {
    /**
     * 
     * @return DocumentoTipoDato
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerDocumentoTipoDato($documentoTipoId) {
        $this->commandPrepare("sp_documento_tipo_dato_obtener");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
     public function obtenerXTipo($empresaId,$tipoId) {
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXTipoId");
        $this->commandAddParameter(":vin_tipo_id", $tipoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
     public function obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,$tipo){
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXDocumentoIdXTipo");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId){
        $this->commandPrepare("sp_documento_tipo_dato_obtenerEditableXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
        
    }
    
    public function obtenerXId($documentoTipoDatoId){
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXId");
        $this->commandAddParameter(":vin_id", $documentoTipoDatoId);
        return $this->commandGetData();        
    }
}
