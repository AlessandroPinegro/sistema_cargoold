<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EquivalenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';

class EquivalenciaControlador extends AlmacenIndexControlador {

    public function getDataGridEquivalencia() {
        return EquivalenciaNegocio::create()->getDataEquivalencia();
    }

    public function getComboAlternativa() {
        $id_unidad = $this->getParametro("id_unidad");
        return UnidadNegocio::create()->getDataComboUnidadAlternativa($id_unidad);
    }

    public function getComboUnidad() {
//        $id_unidad = $this->getParametro("id_unidad");
        return UnidadNegocio::create()->getDataComboUnidadBase();
    }

    public function insertEquivalencia() {
        $fac_alternativa = $this->getParametro("fac_alternativa");
        $uni_alternativa = $this->getParametro("uni_alternativa");
        $fac_base = $this->getParametro("fac_base");
        $uni_base = $this->getParametro("uni_base");
        $usu_creacion = $this->getUsuarioId();
        return EquivalenciaNegocio::create()->insertEquivalencia($fac_alternativa, $uni_alternativa, $fac_base, $uni_base, $usu_creacion);
    }

    public function getEquivalencia() {
        $id_equivalencia = $this->getParametro("id_equivalencia");
        return EquivalenciaNegocio::create()->getEquivalencia($id_equivalencia);
    }

    public function updateEquivalencia() {
        $id_equivalencia = $usu_nombre = $this->getParametro("id_equivalencia");
        $unidad_base = $this->getParametro("unidad_base");
        $factor_unidad = $this->getParametro("factor_unidad");
        $unidad_alternativa = $this->getParametro("unidad_alternativa");
        $factor_alternativa = $this->getParametro("factor_alternativa");
        return EquivalenciaNegocio::create()->updateEquivalencia($id_equivalencia, $unidad_base, $factor_unidad, $unidad_alternativa, $factor_alternativa);
    }

    public function deleteEquivalencia() {
        $id_equi = $this->getParametro("id_equivalencia");
        $nom1 = $this->getParametro("nom1");
        $nom2 = $this->getParametro("nom2");
        return EquivalenciaNegocio::create()->deleteEquivalencia($id_equi, $nom1, $nom2);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
//        $estado = $this->getParametro("estado");
        return EquivalenciaNegocio::create()->cambiarEstado($id_estado);
    }
    public function validarEquivalencia() {
        $unidad_base = $this->getParametro("unidad_base");
        $unidad_alternativa = $this->getParametro("unidad_alternativa");
        return EquivalenciaNegocio::create()->validarEquivalencia($unidad_base, $unidad_alternativa);
    }
    

}
