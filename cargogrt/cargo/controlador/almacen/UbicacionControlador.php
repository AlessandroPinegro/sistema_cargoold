<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UbicacionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class UbicacionControlador extends AlmacenIndexControlador {

    public function getDataGridUbicacionTipo() {
        return UbicacionNegocio::create()->getDataUbicacionTipo();
    }

    public function insertUbicacionTipo() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getUsuarioId();
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("comboT");
        return UbicacionNegocio::create()->insertUbicacionTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion, $empresa, $combo);
    }

    public function getUbicacionTipo() {
        $id_ubi_tipo = $this->getParametro("id_ubi_tipo");
        return UbicacionNegocio::create()->getUbicacionTipo($id_ubi_tipo);
    }

    public function updateUbicacionTipo() {
        $id_ubi_tipo = $usu_nombre = $this->getParametro("id_ubi_tipo");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        return UbicacionNegocio::create()->updateUbicacionTipo($id_ubi_tipo, $descripcion, $codigo, $comentario, $estado, $empresa, $combo);
    }

    public function deleteUbicacionTipo() {
        $id_ubi_tipo = $this->getParametro("id_ubi_tipo");
        $nom = $this->getParametro("nom");
        return UbicacionNegocio::create()->deleteUbicacionTipo($id_ubi_tipo, $nom);
    }

    public function cambiarTipoEstado() {
        $id_estado = $this->getParametro("id_estado");
//        $estado = $this->getParametro("estado");
        return UbicacionNegocio::create()->cambiarTipoEstado($id_estado);
    }

    public function getComboEmpresaTipo() {
        $id_tipo = $this->getParametro("id_tipo");
        if ($id_tipo == null) {
            return EmpresaNegocio::create()->getDataEmpresa($id_tipo);
        } else {
            return EmpresaNegocio::create()->getDataEmpresaUbicacionTipo($id_tipo);
        }
    }

    //////////////////////////////////////////////////////////////////////////

    public function getDataGridUbicacion() {
        return UbicacionNegocio::create()->getDataUbicacion();
    }

    public function insertUbicacion() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getParametro("usu_creacion");
        $comentario = $this->getParametro("comentario");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("comboT");
        return UbicacionNegocio::create()->insertUbicacion($descripcion, $codigo, $tipo, $estado, $usu_creacion, $comentario,$empresa,$combo);
    }

    public function getUbicacion() {
        $id_ubi = $this->getParametro("id_ubi");
        return UbicacionNegocio::create()->getUbicacion($id_ubi);
    }

    public function updateUbicacion() {
        $id_ubi = $usu_nombre = $this->getParametro("id_ubi");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $comentario = $this->getParametro("comentario");
        return UbicacionNegocio::create()->updateUbicacion($id_ubi, $descripcion, $codigo, $tipo, $estado, $comentario);
    }

    public function deleteUbicacion() {
        $id_ubi = $this->getParametro("id_ubi");
        $nom = $this->getParametro("nom");
        return UbicacionNegocio::create()->deleteUbicacion($id_ubi, $nom);
    }

    public function getComboTipoUbicacion() {
        $id_tipo = $this->getParametro("id_tipo");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        return UbicacionNegocio::create()->getDataComboUbicacionTipo($id_tipo,$empresa,$combo);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return UbicacionNegocio::create()->cambiarEstado($id_estado);
    }
    public function getComboEmpresa() {
        $id_tipo = $this->getParametro("id_tipo");
        if ($id_tipo == null) {
            return EmpresaNegocio::create()->getDataEmpresa($id_tipo);
        } else {
            return EmpresaNegocio::create()->getDataEmpresaUbicacionTipo($id_tipo);
        }
    }

}
