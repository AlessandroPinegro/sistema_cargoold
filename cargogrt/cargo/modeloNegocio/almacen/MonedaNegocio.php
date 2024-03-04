<?php
require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/Moneda.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class MonedaNegocio extends ModeloNegocioBase {
    /**
     * 
     * @return MonedaNegocio
     */
    
    static function create() {
        return parent::create();
    }

    public function obtenerComboMoneda() {
       return Moneda::create()->obtenerComboMoneda();
    }
        
    public function obtenerMonedaBase() {
       return Moneda::create()->obtenerMonedaBase();
    }    
    
    public function obtenerMonedaDistintaBase() {
       return Moneda::create()->obtenerMonedaDistintaBase();
    }
}
