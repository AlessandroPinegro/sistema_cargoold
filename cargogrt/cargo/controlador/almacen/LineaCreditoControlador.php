<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/LineaCreditoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';

class LineaCreditoControlador extends AlmacenIndexControlador {

    public function listarLineaCredito() {

        return LineaCreditoNegocio::create()->listar();
    }

    public function insertarLineaCredito() {
        $personaClaseId = $this->getParametro("personaClaseId");
        $moneda = $this->getParametro("moneda");
        $importe = $this->getParametro("importe");
        $periodo = $this->getParametro("periodo");
        $periodoGracia = $this->getParametro("periodoGracia");
        $usuarioCreacion = $this->getUsuarioId();
        return LineaCreditoNegocio::create()->insertar($personaClaseId, $moneda, $importe, $periodo, $periodoGracia, $usuarioCreacion);
    }

    public function actualizarLineaCredito() {
        $lineaCreditoId = $this->getParametro("lineaCreditoId");
        $personaClaseId = $this->getParametro("personaClaseId");
        $moneda = $this->getParametro("moneda");
        $importe = $this->getParametro("importe");
        $periodo = $this->getParametro("periodo");
        $periodoGracia = $this->getParametro("periodoGracia");
        $estado = $this->getParametro("estado");
        $usuarioCreacion = $this->getUsuarioId();
        return LineaCreditoNegocio::create()->actualaizar($lineaCreditoId, $personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion);
    }

    public function obtenerComboPersonaClase() {
        return PersonaNegocio::create()->obtenerComboPersonaClase();
    }

    public function obtenerComboMoneda() {
        return Moneda::create()->obtenerComboMoneda();
    }

    public function obtenerLineaCredito() {
        $lineaCreditoId = $this->getParametro("lineaCreditoId");
        return LineaCreditoNegocio::create()->obtenerPorId($lineaCreditoId);
    }

    public function eliminarLineaCredito() {
        $lineaCreditoId = $this->getParametro("lineaCreditoId");
        $personaClase = $this->getParametro("personaClase");
        return LineaCreditoNegocio::create()->eliminar($lineaCreditoId, $personaClase);
    }
    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return LineaCreditoNegocio::create()->cambiarEstado($id_estado);
    }


}
