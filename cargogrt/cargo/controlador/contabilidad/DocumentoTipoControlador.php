<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class DocumentoTipoControlador extends ControladorBase {

    public function obtenerConfiguracionesIniciales() {
        $empresaId = $this->getParametro("empresaId");   
        return DocumentoTipoNegocio::create()->obtenerConfiguracionesIniciales($empresaId);
    }          
    
    public function guardarDocumentoTipo() {
        $this->setTransaction();
        //variables
        $descripcion = $this->getParametro("descripcion");
        $comentario = $this->getParametro("comentario");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $estadoId = $this->getParametro("estadoId");
        $tipo = $this->getParametro("tipo");
               
        //ids
        $usuarioId=$this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $documentoTipoId = $this->getParametro("documentoTipoId");
        
        return DocumentoTipoNegocio::create()->guardarDocumentoTipo($descripcion,$comentario,$codigoSunatId,$estadoId,
                $usuarioId,$empresaId,$documentoTipoId,$tipo);
    }
    
    public function listarDocumentoTipo(){
        $empresaId = $this->getParametro("empresaId");
        return DocumentoTipoNegocio::create()->listarDocumentoTipo($empresaId);        
    }
    
    public function cambiarEstado() {
        $id = $this->getParametro("id");
        return DocumentoTipoNegocio::create()->cambiarEstado($id);
    }    
    
    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return DocumentoTipoNegocio::create()->eliminar($id, $nom);
    }
    
    public function obtenerDocumentoTipoXid(){
        $id = $this->getParametro("id");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($id);        
    }
    

}