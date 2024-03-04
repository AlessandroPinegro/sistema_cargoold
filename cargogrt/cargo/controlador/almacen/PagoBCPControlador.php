<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/BCPEMailNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class PagoBCPControlador extends ControladorBase {

    public function obtenerConfiguracionInicialListado() {
        $data = BCPEMailNegocio::create()->obtenerConfiguracionInicialListado();
        return $data;
    }

    public function obtenerBCPEmail() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = BCPEMailNegocio::create()->obtenerXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = BCPEMailNegocio::create()->obtenerXCriteriosContador($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function nuevoReintentoDePago(){
        $bcpEmailId = $this->getParametro("bcpEmailId");
        return BCPEMailNegocio::create()->reintentarPagoBCP($bcpEmailId);
    }
    public function actualizarNumeroOperacion(){
        $bcpEMailId = $this->getParametro("bcpEMailId");
        $numeroOperacion = $this->getParametro("numeroOperacion");
        return BCPEMailNegocio::create()->actualizarNumeroOperacion($bcpEMailId, $numeroOperacion);
    }    
}
