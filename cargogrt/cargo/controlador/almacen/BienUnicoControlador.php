<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienUnicoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReporteNegocio.php';

class BienUnicoControlador extends AlmacenIndexControlador {

    public function obtenerConfiguracionesInicialesBienUnico() {
        return BienUnicoNegocio::create()->obtenerConfiguracionesInicialesBienUnico();
    }

    public function obtenerBienTipoHijo() {
        $bienTipoPadreId = $this->getParametro("bienTipoPadreId");
        return BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoPadreId);
    }

    public function obtenerDataBienUnico() {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = BienUnicoNegocio::create()->obtenerDataBienUnicoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = BienUnicoNegocio::create()->obtenerCantidadDataBienUnicoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }
    
    public function obtenerDetalleBienUnico() {
        $bienUnicoId = $this->getParametro("bienUnicoId");        
        return BienUnicoNegocio::create()->obtenerDetalleBienUnico($bienUnicoId);        
    }
    
    public function obtenerBienUnicoXId() {
        $bienUnicoId = $this->getParametro("bienUnicoId");        
        return BienUnicoNegocio::create()->obtenerBienUnicoXId($bienUnicoId);        
    }

    public function verDetalleDocumento() {
        $documentoId = $this->getParametro("documento_id");
        $movimientoId = $this->getParametro("movimiento_id");
        
        $data= ReporteNegocio::create()->verDetallePorCliente($documentoId, $movimientoId);
        return $data;
    }
    
    public function obtenerDocumentoDetalleXId() {
        $documentoId = $this->getParametro("documentoId");
        $data= BienUnicoNegocio::create()->obtenerDocumentoDetalleXId($documentoId);
        return $data;        
    }

}
