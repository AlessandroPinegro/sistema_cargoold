<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoDatoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoDatoListaNegocio.php';

class DocumentoTipoDatoListaControlador extends AlmacenIndexControlador {
    public function getDataGridDocumentoTipoDato() {
        $empresaId = $this->getParametro("empresaId");
        return DocumentoTipoDatoNegocio::create()->obtenerTiposListas($empresaId);
    }
    
    public function getDataGridDocumentoTipoDatoLista() {
        $documentoTipoDatoId = $this->getParametro("documentoTipoDatoId");
        return DocumentoTipoDatoListaNegocio::create()->obtenerDocumentoTipoDatoLista($documentoTipoDatoId);
    }
    
    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return DocumentoTipoDatoListaNegocio::create()->cambiarEstado($id_estado);
    }
    
    public function eliminarDocumentoTipoDatoLista() {
        $documentoTipoDatoId = $this->getParametro("id_documento_tipo_dato_lista");
        $nom = $this->getParametro("nom");
        return DocumentoTipoDatoListaNegocio::create()->eliminar($documentoTipoDatoId, $nom);
    }
      
    public function save() {
        $descripcion = $this->getParametro("descripcion");
        $valor = $this->getParametro("valor");
        $estado = $this->getParametro("estado");
        $documentoTipoDatoId = $this->getParametro("documentoTipoDatoId");
        $documentoTipoDatoListaId = $this->getParametro("documentoTipoDatoListaId");
        $usuarioCreacion = $this->getUsuarioId();
        
        $this->setCommitTransaction();
        
        if($documentoTipoDatoListaId==0){            
            $respuesta=DocumentoTipoDatoListaNegocio::create()->insertar($documentoTipoDatoId, $descripcion, $valor, $estado, $usuarioCreacion);
        }
        else{
            $respuesta=DocumentoTipoDatoListaNegocio::create()->actualizar($documentoTipoDatoListaId,$documentoTipoDatoId, $descripcion, $valor, $estado);            
        }
        
        if($respuesta[0]['vout_exito']==1){
            $this->setMensajeEmergente($respuesta[0]['vout_mensaje'],null,Configuraciones::MENSAJE_OK);
        }else{
            $this->setMensajeEmergente($respuesta[0]['vout_mensaje'],null,Configuraciones::MENSAJE_WARNING);
        }
        
    }
    
    public function obtenerConfiguracionesIniciales(){
        $documentoTipoDatoId = $this->getParametro("documentoTipoDatoId");
        return DocumentoTipoDatoListaNegocio::create()->obtenerConfiguracionesIniciales($documentoTipoDatoId);        
    }
    
}
