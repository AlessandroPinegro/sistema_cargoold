<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/ConceptoGastoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ConceptoGastoControlador extends ControladorBase {

    public function listarConceptoGasto() {
        $empresaId = $this->getParametro("empresaId");
        return ConceptoGastoNegocio::create()->listarConceptoGasto($empresaId);
    }
    
    public function guardarConceptoGasto() {
        $this->setTransaction();
        //variables
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $estado = $this->getParametro("estado");

        //ids
        $usuarioId = $this->getUsuarioId();
        $conceptoGatoId = $this->getParametro("conceptoGastoId");
        $empresaId = 2;// $this->getParametro("empresaId");

        return ConceptoGastoNegocio::create()->guardarConceptoGasto($codigo, $descripcion, $estado, $usuarioId, $conceptoGatoId, $empresaId);
    }
    public function obtenerConceptoGasto() {
        $id = $this->getParametro("id");
        return ConceptoGastoNegocio::create()->obtenerConceptoGasto($id);
    }
    
    public function cambiarEstadoConceptoGasto() {
        $id = $this->getParametro("id");
        return ConceptoGastoNegocio::create()->cambiarEstadoConceptoGasto($id);
    }

    public function eliminarConceptoGasto() {
        $id = $this->getParametro("id");
        return ConceptoGastoNegocio::create()->eliminarConceptoGasto($id);
    }

}
