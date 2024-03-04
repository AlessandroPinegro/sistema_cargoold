<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/OperacionesNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class OperacionesControlador extends ControladorBase {

    public function obtenerConfiguracionesIniciales() {
        $empresaId = $this->getParametro("empresaId");   
        return OperacionesNegocio::create()->obtenerConfiguracionesIniciales($empresaId);
    }          
    
    public function guardarOperacion() {
        $this->setTransaction();
        //variables
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $tipoCambioId = $this->getParametro("tipoCambioId");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $estadoId = $this->getParametro("estadoId");
        $subdiarioId = $this->getParametro("subdiarioId");
        $sucursalId = $this->getParametro("sucursalId");
        $chkEgresoBanco = $this->getParametro("chkEgresoBanco");        
               
        //ids
        $usuarioId=$this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $operacionId = $this->getParametro("operacionId");
        
        return OperacionesNegocio::create()->guardarOperacion($codigo,$descripcion,$tipoCambioId,$codigoSunatId,$estadoId,
                $subdiarioId,$sucursalId,$chkEgresoBanco,$usuarioId,$empresaId,$operacionId);
    }
    
    public function listarOperaciones(){
        $empresaId = $this->getParametro("empresaId");
        return OperacionesNegocio::create()->listarOperaciones($empresaId);        
    }
    
    public function cambiarEstado() {
        $id = $this->getParametro("id");
        return OperacionesNegocio::create()->cambiarEstado($id);
    }    
    
    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return OperacionesNegocio::create()->eliminar($id, $nom);
    }
    
    public function obtenerOperacionXid(){
        $id = $this->getParametro("id");
        return OperacionesNegocio::create()->obtenerOperacionXid($id);        
    }
    
    public function obtenerOperacionNumeracionXid()
    {
        $id = $this->getParametro("operacionId");
        return OperacionesNegocio::create()->obtenerOperacionNumeracionXid($id);
    }

}