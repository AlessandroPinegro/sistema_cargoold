<?php

require_once __DIR__ . '/../../modelo/contabilidad/ConceptoGasto.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';

class ConceptoGastoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ConceptoGastoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarConceptoGasto($empresaId) {
        $empresaId = 2;
        return ConceptoGasto::create()->listarConceptoGasto($empresaId);
    } 
    
    public function guardarConceptoGasto($codigo, $descripcion, $estado, $usuarioId, $conceptoGatoId, $empresaId) {
        return  ConceptoGasto::create()->guardarConceptoGasto($codigo, $descripcion, $estado, $usuarioId, $conceptoGatoId,$empresaId);       
    }
    
    public function obtenerConceptoGasto($id) {
        return ConceptoGasto::create()->obtenerConceptoGasto($id);
    } 
    public function cambiarEstadoConceptoGasto($id) {
        return ConceptoGasto::create()->cambiarEstadoConceptoGasto($id);
    }     
    public function eliminarConceptoGasto($id) {
        return ConceptoGasto::create()->eliminarConceptoGasto($id);
    }   
}
