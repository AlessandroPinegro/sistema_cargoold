<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AlmacenNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class AlmacenControlador extends AlmacenIndexControlador {

    public function getDataGridAlmacenTipo() {
        return AlmacenNegocio::create()->getDataAlmacenTipo();
    }

    public function insertAlmacenTipo() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getParametro("usu_creacion");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("comboT");
        return AlmacenNegocio::create()->insertAlmacenTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion, $empresa, $combo);
    }

    public function getAlmacenTipo() {
        $id_almacen_tipo = $this->getParametro("id_almacen_tipo");
        return AlmacenNegocio::create()->getAlmacenTipo($id_almacen_tipo);
    }

    public function updateAlmacenTipo() {
        $id_alm_tipo = $usu_nombre = $this->getParametro("id_alm_tipo");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        return AlmacenNegocio::create()->updateAlmacenTipo($id_alm_tipo, $descripcion, $codigo, $comentario, $estado, $empresa, $combo);
    }

    public function deleteAlmacenTipo() {
        $id_alm_tipo = $this->getParametro("id_alm_tipo");
        $nom = $this->getParametro("nom");
        return AlmacenNegocio::create()->deleteAlmacenTipo($id_alm_tipo, $nom);
    }

    public function cambiarTipoEstado() {
        $id_estado = $this->getParametro("id_estado");
//        $estado = $this->getParametro("estado");
        return AlmacenNegocio::create()->cambiarTipoEstado($id_estado);
    }
    public function getComboEmpresaTipo() {
        $id_tipo = $this->getParametro("id_tipo");
        if ($id_tipo == null) {
            return EmpresaNegocio::create()->getDataEmpresa($id_tipo);
        } else {
            return EmpresaNegocio::create()->getDataEmpresaAlmacenTipo($id_tipo);
        }
    }

    
    
    //////////////////////////////////////////////////////////////////////////

    public function getDataGridAlmacen() {
        return AlmacenNegocio::create()->getDataAlmacen();
    }

    public function insertAlmacen() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getParametro("usu_creacion");
        $comentario = $this->getParametro("comentario");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("comboT");
        return AlmacenNegocio::create()->insertAlmacen($descripcion, $codigo, $tipo, $estado, $usu_creacion, $comentario,$empresa, $combo);
    }

    public function getAlmacen() {
        $id_almacen = $this->getParametro("id_almacen");
        return AlmacenNegocio::create()->getAlmacen($id_almacen);
    }

    public function updateAlmacen() {
        $id_alm = $usu_nombre = $this->getParametro("id_alm");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $comentario = $this->getParametro("comentario");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        return AlmacenNegocio::create()->updateAlmacen($id_alm, $descripcion, $codigo, $tipo, $estado, $comentario,$empresa,$combo);
    }

    public function deleteAlmacen() {
        $id_alm = $this->getParametro("id_alm");
        $nom = $this->getParametro("nom");
        return AlmacenNegocio::create()->deleteAlmacen($id_alm, $nom);
    }

    public function getComboTipoAlmacen() {
        $id_tipo = $this->getParametro("id_tipo");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        return AlmacenNegocio::create()->getDataComboAlmacenTipo($id_tipo,$empresa,$combo);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return AlmacenNegocio::create()->cambiarEstado($id_estado);
    }

    public function getComboEmpresa() {
        $id_tipo = $this->getParametro("id_tipo");
        if ($id_tipo == null) {
            return EmpresaNegocio::create()->getDataEmpresa($id_tipo);
        } else {
            return EmpresaNegocio::create()->getDataEmpresaAlmacen($id_tipo);
        }
    }

}
