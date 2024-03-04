<?php
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipoDato.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class DocumentoTipoDatoNegocio extends ModeloNegocioBase {
    /**
     * 
     * @return DocumentoTipoDatoNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerTiposListas($empresaId){
        return DocumentoTipoDato::create()->obtenerXTipo($empresaId,4);
    }
    
    public function obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,$tipo){
        return DocumentoTipoDato::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,$tipo);
    }
    
    public function obtenerXId($documentoTipoDatoId){
        return DocumentoTipoDato::create()->obtenerXId($documentoTipoDatoId);        
    }
}
