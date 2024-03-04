<?php

  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
require_once __DIR__ . '/../../modelo/almacen/OperacionTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

/**
 * Description of OperacionTipoNegocio
 *
 * @author Imagina
 */
class OperacionTipoNegocio  extends ModeloNegocioBase {
    /**
     * 
     * @return OperacionTipoNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerXOpcion($opcionId){
        return OperacionTipo::create()->obtenerXOpcion($opcionId);
    }
}
