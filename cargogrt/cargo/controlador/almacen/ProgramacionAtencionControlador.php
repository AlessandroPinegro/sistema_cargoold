<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/ProgramacionAtencionNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ProgramacionAtencionControlador extends ControladorBase {

    public function obtenerConfiguracionInicialListado() {
        $data = ProgramacionAtencionNegocio::create()->obtenerConfiguracionInicialListado();
        return $data;
    }

    public function obtenerDocumentosPAtencion() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ProgramacionAtencionNegocio::create()->obtenerDocumentosPAtencionXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ProgramacionAtencionNegocio::create()->obtenerCantidadDocumentosPAtencionXCriterios($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function obtenerProgramacionAtencionDetalle() {
        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ProgramacionAtencionNegocio::create()->obtenerProgramacionAtencionDetalleXCriterios($criterios, $elementosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ProgramacionAtencionNegocio::create()->obtenerCantidadProgramacionAtencionDetalleXCriterios($criterios, $columns, $order);
        $response_cantidad_total[0]['total'];
        $elementosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elementosFiltrados, $elementosTotales);
    }

    public function obtenerConfiguracionesIniciales() {
        $documentoId = $this->getParametro("documentoId");
        $data = ProgramacionAtencionNegocio::create()->obtenerConfiguracionesIniciales($documentoId);
        return $data;
    }

    public function guardarProgramacionAtencion() {
        $this->setTransaction();
        $usuCreacion = $this->getUsuarioId();
        $listaProgramacionAtencionDetalle = $this->getParametro("listaProgramacionAtencionDetalle");
        $listaProgramacionAtencionDetalleEliminado = $this->getParametro("listaProgramacionAtencionDetalleEliminado");

        $data = ProgramacionAtencionNegocio::create()->guardarProgramacionAtencion(
                $listaProgramacionAtencionDetalle, $listaProgramacionAtencionDetalleEliminado, $usuCreacion);
        return $data;
    }

    public function obtenerDocumento() {
        $documentoId = $this->getParametro("documentoId");
        $data=ProgramacionAtencionNegocio::create()->obtenerDocumento($documentoId);
        return $data;
    }
    
    public function actualizarEstadoPAtencionDetalle(){        
        $this->setTransaction();
        $patencionDetalleId = $this->getParametro("patencionDetalleId");
        $nuevoEstado = $this->getParametro("nuevoEstado");
        return ProgramacionAtencionNegocio::create()->actualizarEstadoPAtencionDetalle($patencionDetalleId,$nuevoEstado);        
    }
    
    public function obtenerDocumentosRelacionados() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
    }

}
