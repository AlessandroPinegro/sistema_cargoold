<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/AprobacionParcialNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/ProgramacionPagoControlador.php';

class AprobacionParcialControlador extends ProgramacionPagoControlador {

    public function obtenerConfiguracionInicialListado() {
        $data = AprobacionParcialNegocio::create()->obtenerConfiguracionInicialListado();
        return $data;
    }

    public function obtenerDocumentosPPago() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = AprobacionParcialNegocio::create()->obtenerDocumentosPPagoXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = AprobacionParcialNegocio::create()->obtenerCantidadDocumentosPPagoXCriterios($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function obtenerProgramacionPagoDetalle() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = AprobacionParcialNegocio::create()->obtenerProgramacionPagoDetalleXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = AprobacionParcialNegocio::create()->obtenerCantidadProgramacionPagoDetalleXCriterios($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function obtenerConfiguracionesIniciales() {
        $documentoId = $this->getParametro("documentoId");
        $data = AprobacionParcialNegocio::create()->obtenerConfiguracionesIniciales($documentoId);
        return $data;
    }

    public function obtenerDocumentosRelacionados() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
    }
}
