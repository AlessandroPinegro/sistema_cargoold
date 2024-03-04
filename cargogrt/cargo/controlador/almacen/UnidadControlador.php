<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';

class UnidadControlador extends AlmacenIndexControlador {

    public function getDataGridUnidadTipo() {
        return UnidadNegocio::create()->getDataUnidadTipo();
    }

    public function insertUnidadTipo() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getUsuarioId();
        return UnidadNegocio::create()->insertUnidadTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion);
    }

    public function getUnidadTipo() {
        $id_unidad_tipo = $this->getParametro("id_unidad_tipo");
        return UnidadNegocio::create()->getUnidadTipo($id_unidad_tipo);
    }

    public function updateUnidadTipo() {
        $id_uni_tipo = $usu_nombre = $this->getParametro("id_uni_tipo");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        return UnidadNegocio::create()->updateUnidadTipo($id_uni_tipo, $descripcion, $codigo, $comentario, $estado);
    }

    public function deleteUnidadTipo() {
        $id_uni_tipo = $this->getParametro("id_uni_tipo");
        $nom = $this->getParametro("nom");
        return UnidadNegocio::create()->deleteUnidadTipo($id_uni_tipo, $nom);
    }

    public function cambiarTipoEstado() {
        $id_estado = $this->getParametro("id_estado");
//        $estado = $this->getParametro("estado");
        return UnidadNegocio::create()->cambiarTipoEstado($id_estado);
    }

    //////////////////////////////////////////////////////////////////////////

    public function getDataGridUnidad() {

        return UnidadNegocio::create()->getDataUnidad();
    }

    public function insertUnidad() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $simbolo = $this->getParametro("simbolo");
        $estado = $this->getParametro("estado");
        $unidad_base = $this->getParametro("unidad_base");
        $usu_creacion = $this->getUsuarioId();
        $codigoSunatId = $this->getParametro("codigoSunatId");
        
        return UnidadNegocio::create()->insertUnidad($descripcion, $codigo, $tipo, $simbolo, $estado, 
                $usu_creacion, $unidad_base,$codigoSunatId);
    }

    public function getUnidad() {
        $id_unidad = $this->getParametro("id_unidad");
        return UnidadNegocio::create()->getUnidad($id_unidad);
    }

    public function updateUnidad() {
        $id_uni = $usu_nombre = $this->getParametro("id_uni");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $simbolo = $this->getParametro("simbolo");
        $estado = $this->getParametro("estado");
        $unidad_base = $this->getParametro("unidad_base");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        return UnidadNegocio::create()->updateUnidad($id_uni, $descripcion, $codigo, $tipo, $simbolo, $estado, 
                $unidad_base,$codigoSunatId);
    }

    public function deleteUnidad() {
        $id_uni = $this->getParametro("id_uni");
        $nom = $this->getParametro("nom");
        return UnidadNegocio::create()->deleteUnidad($id_uni, $nom);
    }

    public function getAllUnidadTipo() {
        return UnidadNegocio::create()->getDataComboUnidadTipo();
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return UnidadNegocio::create()->cambiarEstado($id_estado);
    }

    public function validarAsignarUnidadBase() {
        $tipo_unidad = $this->getParametro("tipo_unidad");
        $tipo_accion = $this->getParametro("tipo_accion");
        $unidadId = $this->getParametro("unidadId");
        return UnidadNegocio::create()->validarAsignarUnidadBase($tipo_unidad, $tipo_accion,$unidadId);
    }
    
    public function obtenerConfiguracionInicialUnidadTipo(){
        return UnidadNegocio::create()->obtenerConfiguracionInicialUnidadTipo();
    }
    
    public function obtenerConfiguracionInicialUnidad() {
        return UnidadNegocio::create()->obtenerConfiguracionInicialUnidad();
    }

}
