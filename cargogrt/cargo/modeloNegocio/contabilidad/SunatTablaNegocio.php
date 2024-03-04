<?php
require_once __DIR__ . '/../../modelo/contabilidad/SunatTabla.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class SunatTablaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return SunatTablaNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerDetalleXSunatTablaId($sunatTablaId){
        return SunatTabla::create()->obtenerDetalleXSunatTablaId($sunatTablaId);
    }
}