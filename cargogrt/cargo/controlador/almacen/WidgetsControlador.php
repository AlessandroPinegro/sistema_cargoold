<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/WidgetsNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReporteNegocio.php';

class WidgetsControlador extends AlmacenIndexControlador {
    
      public function obtenerConfiguracionesBienesComprometidos(){
        $hoy = date('d/m/Y');
        return $hoy;
        // return WidgetsNegocio::create()->obtenerConfiguracionesInicialesAuditoria($idEmpresa);
    }
    public function DataBusquedaBienesComprometidos()
    {      
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
//        $data = AuditoriaNegocio::create()->obtenerAuditoriaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $data = WidgetsNegocio::create()->obtenerBienesComprometidosXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = WidgetsNegocio::create()->obtenerCantidadBienesComprometidosXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);  
    }
    public function obtenerCantidadBienesComprometidos(){
        $criterios = $this->getParametro("criterios");
        return WidgetsNegocio::create()->obtenerCantidadBienesComprometidos($criterios);
    }
    
    //Ranking distribución 
    
     public function obtenerDataRankingDistribucion()
    {
//        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $empresa = $this->getParametro("empresa");
        $data = WidgetsNegocio::create()->obtenerRankingDistribucionXCriterios( $empresa, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = WidgetsNegocio::create()->obtenerCantidadRankingDistribucionXCriterio($empresa, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);  
    }
    
     public function obtenerCantidadTotalRankingDistribucion(){
        $empresa = $this->getParametro("empresa");
        return WidgetsNegocio::create()->CantidadTotalRankingDistribucion($empresa);
    }
}