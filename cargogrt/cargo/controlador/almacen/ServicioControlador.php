<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ServicioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class ServicioControlador extends AlmacenIndexControlador {

    public function getDataGridServicio() {
        return ServicioNegocio::create()->getDataServicio();
    }
    public function insertServicio() {
        $descripcion = $this->getParametro("descripcion");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $codigo = $this->getParametro("codigo");
        $usu_creacion = $this->getUsuarioId();
        $empresa = $this->getParametro("empresa");
        return ServicioNegocio::create()->insertServicio($descripcion, $comentario, $estado, $usu_creacion, $codigo, $empresa);
    }

    public function getServicio() {
        $id_servicio = $this->getParametro("id_servicio");
        return ServicioNegocio::create()->getServicio($id_servicio);
    }

    public function updateServicio() {
        $id_servicio = $usu_nombre = $this->getParametro("id_servicio");
        $descripcion = $this->getParametro("descripcion");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $codigo = $this->getParametro("codigo");
        $empresa = $this->getParametro("empresa");
        return ServicioNegocio::create()->updateServicio($id_servicio, $descripcion, $comentario, $estado, $codigo, $empresa);
    }

    public function deleteServicio() {
        $id_servicio = $this->getParametro("id_servicio");
        $nom = $this->getParametro("nom");
        return ServicioNegocio::create()->deleteServicio($id_servicio, $nom);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return ServicioNegocio::create()->cambiarEstado($id_estado);
    }

    public function getAllEmpresa() {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }

}
