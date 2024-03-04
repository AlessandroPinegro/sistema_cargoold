<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/ProgramacionPagoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ProgramacionPagoControlador extends ControladorBase {

    public function obtenerConfiguracionInicialListado() {
        $data = ProgramacionPagoNegocio::create()->obtenerConfiguracionInicialListado();
        return $data;
    }

    public function obtenerDocumentosPPago() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ProgramacionPagoNegocio::create()->obtenerDocumentosPPagoXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ProgramacionPagoNegocio::create()->obtenerCantidadDocumentosPPagoXCriterios($criterios, $columns, $order);
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
        $data = ProgramacionPagoNegocio::create()->obtenerProgramacionPagoDetalleXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ProgramacionPagoNegocio::create()->obtenerCantidadProgramacionPagoDetalleXCriterios($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function obtenerConfiguracionesIniciales() {
        $documentoId = $this->getParametro("documentoId");
        $data = ProgramacionPagoNegocio::create()->obtenerConfiguracionesIniciales($documentoId);
        return $data;
    }

    public function guardarProgramacionPago() {
        $this->setTransaction();
        $usuCreacion = $this->getUsuarioId();
        $documentoId = $this->getParametro("documentoId");
        $fechaTentativa = $this->getParametro("fechaTentativa");
        $personaId = $this->getParametro("personaId");
        $aprobacion_parcial = $this->getParametro("aprobacion_parcial");
        $listaProgramacionPagoDetalle = $this->getParametro("listaProgramacionPagoDetalle");
        $listaProgramacionPagoDetalleEliminado = $this->getParametro("listaProgramacionPagoDetalleEliminado");

        $data = ProgramacionPagoNegocio::create()->guardarProgramacionPago(
                $documentoId, $fechaTentativa, $personaId, $listaProgramacionPagoDetalle, $listaProgramacionPagoDetalleEliminado, $usuCreacion,$aprobacion_parcial);
        return $data;
    }

    public function obtenerDocumento() {
        $documentoId = $this->getParametro("documentoId");
        $data=ProgramacionPagoNegocio::create()->obtenerDocumento($documentoId);
        return $data;
    }
    
    public function actualizarEstadoPPagoDetalle(){
        $ppagoDetalleId = $this->getParametro("ppagoDetalleId");
        $nuevoEstado = $this->getParametro("nuevoEstado");
        return ProgramacionPagoNegocio::create()->actualizarEstadoPPagoDetalle($ppagoDetalleId,$nuevoEstado);        
    }

}
