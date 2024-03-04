<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/SubdiariosNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class SubdiariosControlador extends ControladorBase {

    public function obtenerConfiguracionesIniciales() {
        $empresaId = $this->getParametro("empresaId");   
        return SubdiariosNegocio::create()->obtenerConfiguracionesIniciales($empresaId);
    }          
    
    public function guardarSubdiario() {
        $this->setTransaction();
        //variables
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $tipoCambioId = $this->getParametro("tipoCambioId");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $estadoId = $this->getParametro("estadoId");
        $tipoAsientoId = $this->getParametro("tipoAsientoId");
        $sucursalId = $this->getParametro("sucursalId");
        $caracteristicasSeleccionadas = $this->getParametro("caracteristicasSeleccionadas");        
               
        //ids
        $usuarioId=$this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $subdiarioId = $this->getParametro("subdiarioId");
        
        return SubdiariosNegocio::create()->guardarSubdiario($codigo,$descripcion,$tipoCambioId,$codigoSunatId,$estadoId,
                $tipoAsientoId,$sucursalId,$caracteristicasSeleccionadas,$usuarioId,$empresaId,$subdiarioId);
    }
    
    public function listarSubdiarios(){
        $empresaId = $this->getParametro("empresaId");
        return SubdiariosNegocio::create()->listarSubdiarios($empresaId);        
    }
    
    public function cambiarEstado() {
        $id = $this->getParametro("id");
        return SubdiariosNegocio::create()->cambiarEstado($id);
    }    
    
    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return SubdiariosNegocio::create()->eliminar($id, $nom);
    }
    
    public function obtenerSubdiarioXid(){
        $id = $this->getParametro("id");
        return SubdiariosNegocio::create()->obtenerSubdiarioXid($id);        
    }
    
    public function obtenerSubdiarioNumeracionXid()
    {
        $id = $this->getParametro("subdiarioId");
        return SubdiariosNegocio::create()->obtenerSubdiarioNumeracionXid($id);
    }
    

}