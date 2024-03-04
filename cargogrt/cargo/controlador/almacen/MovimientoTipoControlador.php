<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../util/ImportacionExcel.php';

class MovimientoTipoControlador extends AlmacenIndexControlador {
    
    public function getDataGridMovimientoTipo() {
        return MovimientoTipoNegocio::create()->getDataMovimientoTipo();
    }

    public function insertMovimientoTipo() {
        $codigo = $this->getParametro("codigo");
        $indicador = $this->getParametro("indicador");
        $descripcion = $this->getParametro("descripcion");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getUsuarioId();
        return MovimientoTipoNegocio::create()->insertMovimientoTipo($codigo, $indicador,$descripcion, $comentario, $estado, $usu_creacion);
    }
    public function getMovimientoTipo() {
        $id = $this->getParametro("id");
        return MovimientoTipoNegocio::create()->getMovimientoTipo($id);
    }
    public function updateMovimientoTipo() {
        $id = $usu_nombre = $this->getParametro("id");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $indicador = $this->getParametro("indicador");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        return MovimientoTipoNegocio::create()->updateMovimientoTipo($id,$indicador,$codigo, $descripcion,$comentario, $estado);
    }
    public function deleteMovimientoTipo() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return MovimientoTipoNegocio::create()->deleteMovimientoTipo($id, $nom);
    }
    public function cambiarMovimientoTipoEstado() {
        $id_estado = $this->getParametro("id_estado");
        return MovimientoTipoNegocio::create()->cambiarMovimientoTipoEstado($id_estado);
    }
}