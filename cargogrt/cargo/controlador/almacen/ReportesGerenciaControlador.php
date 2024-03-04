<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReportesGerenciaNegocio.php';
//require_once __DIR__ . '/../../modeloNegocio/almacen/CajaNegocio.php';

class ReportesGerenciaControlador extends AlmacenIndexControlador {

    // public function ObtenerdataInicial()
    // {
        
    //     return ReportesGerenciaNegocio::create()->ReportesGerenciaTest();
    // }

    // public function BuscarReporte_1()
    // {
    //     $bfechaini = $this->getParametro("bfechaini");
    //     $bfechafin = $this->getParametro("bfechafin");
    //     $data= ReportesGerenciaNegocio::create()->testreporte1($bfechaini,$bfechafin);
    //     $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        
    //     return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    // }

    // public function exportarReporteExcelCargo()
    // {
    //     $bfechaini = $this->getParametro("bfechaini");
    //     $bfechafin = $this->getParametro("bfechafin");
    //     return  ReportesGerenciaNegocio::create()->exportarReporteExcel($bfechaini,$bfechafin);
        
    // }


    // public function ReporteVentasSolesxFecha()//ReporteVentasSolesxFecha
    // {
    //     $bfechaini = $this->getParametro("bfechaini");
    //     $bfechafin = $this->getParametro("bfechafin");
    //      return $bfechaini;
    //     //return  //ReportesGerenciaNegocio::create()->ReporteVentasSolesxFecha($bfechaini,$bfechafin);

    // }

    public function exportarDataExcel(){
        $fechaInicio = $this->getParametro("fechaInicio");
         $fechaFin =$this->getParametro("fechaFin");
         return  ReportesGerenciaNegocio::create()->getreporteLiquidacionDeNotasDeVentaExcel($fechaInicio,$fechaFin);
  }
     public function reporteLiquidacionDeNotasDeVenta(){
        $fechaInicio= $this->getParametro("fechaInicio"); 
        $fechaFin= $this->getParametro("fechaFin");
        $bandera= $this->getParametro("bandera");
        return  ReportesGerenciaNegocio::create()->getreporteLiquidacionDeNotasDeVenta($fechaInicio,$fechaFin, $bandera);
     }

    public function ReporteVentasSolesxFecha()
    {
        $bfechaini = $this->getParametro("bfechaini");
        $bfechafin = $this->getParametro("bfechafin");
        $data= ReportesGerenciaNegocio::create()->ReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin);
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);

        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function exportarReporteventasSolesTotalExcel()
    {
        $bfechainirep = $this->getParametro("bfechainirep");
        $bfechafinrep = $this->getParametro("bfechafinrep");
        return  ReportesGerenciaNegocio::create()->getexportarReporVentasSolesTotalExcel($bfechainirep,$bfechafinrep);
        // $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        // return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function ReporteInventarioTotal()
    {
        $fechaininv= $this->getParametro("bfechaini"); 
        $fechafinnv= $this->getParametro("bfechafin");
        $data= ReportesGerenciaNegocio::create()->getInventarioAll($fechaininv,$fechafinnv);
        //return $data;
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function exportarInventarioTotalExcel()
    {
        $fechaininvrepxls= $this->getParametro("bfechainirepxls"); 
        $fechafinnvrepxls= $this->getParametro("bfechafinrepxls");
        return  ReportesGerenciaNegocio::create()->getexportarInventarioTotalExcel($fechaininvrepxls,$fechafinnvrepxls);
    }

    public function ReporteInventarioDetalladoTotal()
    {
        $fechaininv= $this->getParametro("bfechainiinvd"); 
        $fechafinnv= $this->getParametro("bfechafininvd");
        $data= ReportesGerenciaNegocio::create()->getInventarioDetalladoAll($fechaininv,$fechafinnv);
        //return $data;
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function exportarInventarioDetalladoTotalExcel()
    {
        $fechaininvrepxls= $this->getParametro("bfechainirepxls"); 
        $fechafinnvrepxls= $this->getParametro("bfechafinrepxls");
        return  ReportesGerenciaNegocio::create()->getexportarInventarioDetalladoTotalExcel($fechaininvrepxls,$fechafinnvrepxls);
    }


    public function ReportePagosDetalle()
    {
        $fechaininv= $this->getParametro("fechapagoini"); 
        $fechafinnv= $this->getParametro("fechapagofn");
        $data= ReportesGerenciaNegocio::create()->getReportePagosDetalleAll($fechaininv,$fechafinnv);        
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function ExportarDetallePagosExcel()
    {
        $fechaininvrepxls= $this->getParametro("fechapagoinixls"); 
        $fechafinnvrepxls= $this->getParametro("fechapagofnxls");
        return  ReportesGerenciaNegocio::create()->getexportarReportePagosDetalleExcel($fechaininvrepxls,$fechafinnvrepxls,0);
    }

}