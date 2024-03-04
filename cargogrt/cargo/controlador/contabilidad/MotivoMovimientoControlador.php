<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/MotivoMovimientoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class MotivoMovimientoControlador extends ControladorBase {
    //put your code here
    
    /**
     * 
     * @return MotivoMovimientoControlador
     */
    
    public function listarMotivosMovimiento(){
        $empresaID = $this->getParametro("empresaID");
        return MotivoMovimientoNegocio::create()->listarMotivosMovimiento($empresaID);        
    }
    
    public function obtenerConfiguracionesIniciales()
    {
        $empresaId = $this->getParametro("empresaId");
        return MotivoMovimientoNegocio::create()->obtenerConfiguracionesIniciales($empresaId);
    }
    
    public function obtenerMotivoMovimientoXid()
    {
        $id = $this->getParametro("id");
        return MotivoMovimientoNegocio::create()->obtenerMotivoMovimientoXid($id);
    }
    
    public function guardarMotivoMovimiento()
    {
        $this->setTransaction();
        
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $nombreCorto = $this->getParametro("nombreCorto");
        $tipoMotivoId = $this->getParametro("tipoMotivoId");
        
        $tipoCalculoId = $this->getParametro("tipoCalculoId");
        $tipoCambioId = $this->getParametro("tipoCambioId");
        $grupoId = $this->getParametro("grupoId");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $estadoId = $this->getParametro("estadoId");
        $caracteristicasSeleccionadas = $this->getParametro("caracteristicasSeleccionadas");  
        $documentosSeleccionados = $this->getParametro("documentosSeleccionados");
        
        
        //id's
        $usuarioId=$this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $motivoId = $this->getParametro("motivoId");
        
        return MotivoMovimientoNegocio::create()->guardarMotivoMovimiento($codigo,$descripcion, $nombreCorto, $tipoMotivoId, $tipoCalculoId, $tipoCambioId, $grupoId, $codigoSunatId, $estadoId, $caracteristicasSeleccionadas, $usuarioId, $empresaId,$motivoId, $documentosSeleccionados);

    }
    
    public function cambiarEstado()
    {
        $id = $this->getParametro("id");
        
        return MotivoMovimientoNegocio::create()->cambiarEstado($id);
    }
    
    public function eliminar()
    {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        
        return MotivoMovimientoNegocio::create()->eliminar($id,$nom);
    }

}
