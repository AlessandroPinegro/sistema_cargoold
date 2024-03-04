<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class PeriodoControlador extends ControladorBase {

    public function obtenerConfiguracionesIniciales() {
        return PeriodoNegocio::create()->obtenerConfiguracionesIniciales();
    }

    public function guardarPeriodo() {
        $this->setTransaction();
        //variables
        $anio = $this->getParametro("anio");
        $mes = $this->getParametro("mes");
        $estadoId = $this->getParametro("estadoId");

        //ids
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $periodoId = $this->getParametro("periodoId");

        return PeriodoNegocio::create()->guardarPeriodo($anio, $mes, $estadoId, $usuarioId, $empresaId, $periodoId);
    }

    public function listarPeriodo() {
        $empresaId = $this->getParametro("empresaId");
        return PeriodoNegocio::create()->listarPeriodo($empresaId);
    }

    public function cambiarEstado() {
        $id = $this->getParametro("id");
        return PeriodoNegocio::create()->cambiarEstado($id);
    }

    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return PeriodoNegocio::create()->eliminar($id, $nom);
    }

    public function obtenerPeriodoXid() {
        $id = $this->getParametro("id");
        return PeriodoNegocio::create()->obtenerPeriodoXid($id);
    }

    public function cerrarPeriodo() {
        $this->setTransaction();
        $id = $this->getParametro("id");
        $usuarioId = $this->getUsuarioId();
        return PeriodoNegocio::create()->cerrarPeriodo($id, $usuarioId);
    }

    public function cerrarPeriodoContable() {
        $this->setTransaction();
        $id = $this->getParametro("id");
        $usuarioId = $this->getUsuarioId();
        return PeriodoNegocio::create()->cerrarPeriodoContable($id, $usuarioId);
    }

    public function cerrarPeriodoReabierto() {
        $this->setTransaction();
        $id = $this->getParametro("id");
        $usuarioId = $this->getUsuarioId();
        return PeriodoNegocio::create()->cerrarPeriodoReabierto($id, $usuarioId);
    }

    public function abrirPeriodo() {
        $id = $this->getParametro("id");
        PeriodoNegocio::create()->cambiarIndicadorContable($id, 2);
        return PeriodoNegocio::create()->cambiarIndicador($id, 2);
    }

    public function obtenerConfiguracionesInicialesGenerarPeriodoPorAnio() {
        return PeriodoNegocio::create()->obtenerConfiguracionesInicialesGenerarPeriodoPorAnio();
    }

    public function generarPeriodoAnio() {
        $this->setTransaction();
        $anio = $this->getParametro("anio");
        $empresaId = $this->getParametro("empresaId");
        $usuarioId = $this->getUsuarioId();
        return PeriodoNegocio::create()->generarPeriodoAnio($anio, $empresaId, $usuarioId);
    }

    public function actualizarBanderaModificacion() {
        $this->setTransaction();
        $id = $this->getParametro("id");
        $banderaContabilidad = $this->getParametro("bandera_contabilidad");        
        return PeriodoNegocio::create()->actualizarBanderaModificacion($id,$banderaContabilidad);
    }

}
