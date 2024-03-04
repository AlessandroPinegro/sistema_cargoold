<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class TipoCambioControlador extends ControladorBase {

    public function listarTipoCambio() {
        //$data= TipoCambioNegocio::create()->listarTipoCambio();
        return TipoCambioNegocio::create()->listarTipoCambio();
    }          
    
    public function obtenerConfiguracionesIniciales() {
        $resultado = new ObjectUtil();
        $fecha = $this->getParametro("fecha");        
        
        $resultado->equivalenciaSunat= TipoCambioNegocio::create()->obtenerEquivalenciaSunatXFecha($fecha);
        $resultado->moneda= TipoCambioNegocio::create()->obtenerMonedaDistintaBase();
        $resultado->monedaBase= TipoCambioNegocio::create()->obtenerMonedaBase();
        
        return $resultado;
    }   
    
    public function obtenerMoneda() {
        //$data=TipoCambioNegocio::create()->obtenerMoneda();
        return TipoCambioNegocio::create()->obtenerMonedaDistintaBase();
    }   
        
    public function obtenerMonedaBase() {
        //$data=TipoCambioNegocio::create()->obtenerMonedaBase();
        return TipoCambioNegocio::create()->obtenerMonedaBase();
    }       
    
    public function crearTipoCambio() {    
        $this->setTransaction();
        $monedaId = $this->getParametro("monedaId");
        $fecha = $this->getParametro("fecha");
        $equivalenciaCompra = $this->getParametro("equivalenciaCompra");
        $equivalenciaVenta = $this->getParametro("equivalenciaVenta");        
        $tipoCambioId = $this->getParametro("tipoCambioId");        
        $usuCreacion = $this->getUsuarioId();
        
        return TipoCambioNegocio::create()->crearTipoCambio($tipoCambioId,$monedaId,$fecha,$equivalenciaCompra ,$equivalenciaVenta,$usuCreacion);
    }
        
    public function obtenerTipoCambioXid() {    
        $this->setTransaction();
        $id = $this->getParametro("id");        
        
        return TipoCambioNegocio::create()->obtenerTipoCambioXid($id);
    }
    public function obtenerTipoCambioXfecha() {    
        $this->setTransaction();
        $fecha = $this->getParametro("fecha");     
        return TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fecha);
    }
    
    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return TipoCambioNegocio::create()->cambiarEstado($id_estado);
    }    
    
    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return TipoCambioNegocio::create()->eliminar($id, $nom);
    }
        
    public function obtenerEquivalenciaSunat() {            
        $fecha = $this->getParametro("fecha");        
        
        return TipoCambioNegocio::create()->obtenerEquivalenciaSunatXFecha($fecha);
    }
          
    
}
