<?php
require_once __DIR__ . '/../../modelo/almacen/Actividad.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class ActividadNegocio extends ModeloNegocioBase {
    /**
     * 
     * @return ActividadNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerActividadesActivas($documentoTipoId){
        return Actividad::create()->obtenerActividadesActivas($documentoTipoId);
    }
    
    public function obtenerActividadTipoActivas(){
        return Actividad::create()->obtenerActividadTipoActivas();
    }
    
    public function obtenerActividadesActivasTodo(){
        return Actividad::create()->obtenerActividadesActivasTodo();
    }
}
