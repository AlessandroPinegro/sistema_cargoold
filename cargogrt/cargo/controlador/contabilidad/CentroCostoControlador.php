<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/CentroCostoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class CentroCostoControlador extends ControladorBase {

    public function listarCentroCostoPadres() {
        $empresaId = $this->getParametro("empresaId");   
        return CentroCostoNegocio::create()->listarCentroCostoPadres($empresaId);
    }          
    
    public function obtenerHijos() {        
        $padreId = $this->getParametro("padreId");                        
        return CentroCostoNegocio::create()->obtenerHijos($padreId);
    }       
    
    public function obtenerCentroCostoEdicion() {
        $id = $this->getParametro("id");                        
        return CentroCostoNegocio::create()->obtenerCentroCostoXId($id);        
    }
    
    public function guardarCentroCosto() {
        $this->setTransaction();
        //variables
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $estado = $this->getParametro("estado");
        
        //ids
        $usuarioId=$this->getUsuarioId();
        $centroCostoId = $this->getParametro("centroCostoId");
        $padreCentroCostoId = $this->getParametro("padreCentroCostoId");
        $empresaId = $this->getParametro("empresaId");
        
        return CentroCostoNegocio::create()->guardarCentroCosto($codigo,$descripcion,$estado,$usuarioId,
                $centroCostoId,$padreCentroCostoId,$empresaId);
        
    }
    
    public function eliminarCentroCosto() {
        $id = $this->getParametro("id");                        
        return CentroCostoNegocio::create()->eliminarCentroCosto($id);        
    }

}