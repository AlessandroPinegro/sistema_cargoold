<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ACCajaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReportesGerenciaNegocio.php';
class ACCajaControlador extends AlmacenIndexControlador
{

    public function obtenerConfiguracionesInicialesApertura()
    {   
        $ip=$_SERVER['REMOTE_ADDR'];
        $empresaId = $this->getParametro("empresaId");
        $cajaId = $this->getParametro("cajaId");
        $idEditar = $this->getParametro("idEditar");
        $resultado = ACCajaNegocio::create()->obtenerConfiguracionesInicialesApertura($empresaId, $cajaId, $idEditar,$ip);
        return $resultado;
    }

    public function obtenerBienXId()
    {
        $empresaId = $this->getParametro("empresaId");
        $bien_id = $this->getParametro("bien_id");
        $comodin = $this->getParametro("comodin");
        $idEditar = $this->getParametro("idEditar");
        $resultado = ACCajaNegocio::create()->obtenerBienXId($bien_id, $empresaId, $comodin, $idEditar);
        return $resultado;
    }

       public function validarCajaIp()
    {
        $this->setTransaction();
        $usuCreacion = $this->getUsuarioId();
        $ip=$_SERVER['REMOTE_ADDR'];
        $cajaId = $this->getParametro("cajaId");
        return ACCajaNegocio::create()->validarCajaIp($usuCreacion,  $ip, $cajaId);
    }
    
    public function guardarAperturaCaja()
    {
        $this->setTransaction();
        $usuCreacion = $this->getUsuarioId();
        $idEditar = $this->getParametro("idEditar");
        $importeApertura = $this->getParametro("importeApertura");
        $aperturaSugerido = $this->getParametro("aperturaSugerido");
        $comentario = $this->getParametro("comentario");
        $empresaId = $this->getParametro("empresaId");
        $dataInicial = $this->getParametro("dataInicial");
        $cajaId = $this->getParametro("cajaId");
        $indicador = 1;
        return ACCajaNegocio::create()->guardarAperturaCaja($idEditar, $usuCreacion, $importeApertura, $comentario, $empresaId, $indicador, $aperturaSugerido, $dataInicial, $cajaId);
    }

    public function obtenerConfiguracionesInicialesCierre()
    {
        $empresaId = $this->getParametro("empresaId");
        $idEditar = $this->getParametro("idEditar");
        $cajaId = $this->getParametro("cajaId");
        $usuCreacion = $this->getUsuarioId();
        $resultado = ACCajaNegocio::create()->obtenerConfiguracionesInicialesCierre($empresaId, $cajaId, $idEditar , $usuCreacion);
        return $resultado;
    }

    public function guardarCierreCaja()
    {
        $this->setTransaction();
        $usuCreacion = $this->getUsuarioId();
        $idEditar = $this->getParametro("idEditar");
        $id = $this->getParametro("id");
        $importeCierre = $this->getParametro("importeCierre");
        $visa = $this->getParametro("importeVisa");
        $deposito = $this->getParametro("importeDeposito");
        $transferencia = $this->getParametro("importeTransferencia");
        $traslado = $this->getParametro("importeTraslado");
        $comentario = $this->getParametro("comentario");
        $empresaId = $this->getParametro("empresaId");
        $lstImportes_modificados = $this->getParametro("lstImportes_modificados");
        $is_pintar_cierre = $this->getParametro("is_pintar_cierre");
        $is_pintar_visa = $this->getParametro("is_pintar_visa");
        $is_pintar_deposito = $this->getParametro("is_pintar_deposito");
        $is_pintar_transferencia = $this->getParametro("is_pintar_transferencia");
        $total_cierre = $this->getParametro("total_cierre");
        $cierre_sugerido = $this->getParametro("cierre_sugerido");
        $visa_sugerido = $this->getParametro("visa_sugerido");
        $deposito_sugerido = $this->getParametro("deposito_sugerido");
        $transferencia_sugerido = $this->getParametro("transferencia_sugerido");
        $dataInicial = $this->getParametro("dataInicial");
        $indicador = 2;
        $cajaId = $this->getParametro("cajaId");
        $egreso=$this->getParametro("egreso");
        $ingreso=$this->getParametro("ingreso");
        $egresoOtros=$this->getParametro("egresoOtros");
        $egresoPos=$this->getParametro("egresoPos");
        $efectivo=$this->getParametro("efectivo");
        return ACCajaNegocio::create()->guardarCierreCaja(
            $idEditar,
            $id,
            $usuCreacion,
            $importeCierre,
            $comentario,
            $empresaId,
            $indicador,
            $visa,
            $traslado,
            $lstImportes_modificados,
            $is_pintar_cierre,
            $is_pintar_visa,
            $total_cierre,
            $cierre_sugerido,
            $visa_sugerido,
            $dataInicial,
            $deposito_sugerido,
            $deposito,
            $is_pintar_deposito,
            $transferencia,
            $is_pintar_transferencia,
            $transferencia_sugerido,
            $cajaId,
            $egreso ,
            $ingreso,
            $egresoOtros,
            $egresoPos,
            $efectivo
        );
    }

    public function obtenerDataACCaja()
    {
        $criteriosBusqueda = $this->getParametro("criteriosBusqueda");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ACCajaNegocio::create()->obtenerACCajaXCriterios($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ACCajaNegocio::create()->obtenerCantidadACCajaXCriteriosA($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerWidgets()
    {
        $data = new ObjectUtil();
        $criteriosBusqueda = $this->getParametro("criteriosBusqueda");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        //$data->data = ACCajaNegocio::create()->obtenerACCajaXCriteriosReporte($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ACCajaNegocio::create()->obtenerCantidadACCajaXCriterios($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        //        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        //        $elementosTotales = $response_cantidad_total[0]['total'];
        $data = new stdClass();
        $data->efectivoTotal = round($response_cantidad_total[1]['total'], 2);
        $data->posTotal = round($response_cantidad_total[2]['total'], 2);
        $data->depositoTotal = round($response_cantidad_total[3]['total'], 2);
        $data->transferenciaTotal = round($response_cantidad_total[4]['total'], 2);
         $data->egreso = round($response_cantidad_total[5]['total'], 2);
          $data->apertura = round($response_cantidad_total[6]['total'], 2);
          $data->egresoPos = round($response_cantidad_total[7]['total'], 2);
          $data->egresoOtros = round($response_cantidad_total[8]['total'], 2);
          $data->ingresos = round($response_cantidad_total[9]['total'], 2);
 $data->traslado = round($response_cantidad_total[10]['total'], 2);
        return $data;
    }


    public function obtenerDataACCajaReporte()
    {
        $criteriosBusqueda = $this->getParametro("criteriosBusqueda");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ACCajaNegocio::create()->obtenerACCajaXCriteriosReporte($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ACCajaNegocio::create()->obtenerCantidadACCajaXCriterios($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $efectivoTotal = $response_cantidad_total[1]['total'];
        $posTotal = $response_cantidad_total[2]['total'];
        $depositoTotal = $response_cantidad_total[3]['total'];
        $transferenciaTotal = $response_cantidad_total[4]['total'];

        return $this->obtenerRespuestaDataTable(
            $data,
            $elemntosFiltrados,
            $elementosTotales,
            $efectivoTotal,
            $posTotal,
            $depositoTotal,
            $transferenciaTotal
        );
    }

    public function obtenerAperturaCierreUltimo()
    {
        $empresaId = $this->getParametro("empresaId");
        $cajaId = $this->getParametro("cajaId");
        return ACCajaNegocio::create()->obtenerAperturaCierreUltimo($empresaId, $cajaId);
    }

    public function obtenerUsuarioDatos()
    {
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        return ACCajaNegocio::create()->obtenerUsuarioDatos($usuarioId, $empresaId);
    }


    public function obtenerUsuarioDatosCaja()
    {
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        return ACCajaNegocio::create()->obtenerUsuarioDatosCaja($usuarioId, $empresaId);
    }
    
    public function exportarPosicionCaja()
    {
      $criteriosBusqueda = $this->getParametro("criterios");
        $order = array(array("column"=>"3","dir"=>"desc"));
        $columns = "fecha_apertura";
        $start = 0;
         if (ObjectUtil::isEmpty($criteriosBusqueda['fechaInicio']) && ObjectUtil::isEmpty($criteriosBusqueda['fechaFin'])) {
            throw new WarningException("Debe ingresar un rango de Fechas.");
        }
        $response_cantidad_total = ACCajaNegocio::create()->obtenerCantidadACCajaXCriterios($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $data = ACCajaNegocio::create()->obtenerACCajaXCriteriosReportePdf($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $usuarioId = $this->getUsuarioId();
        $datausu = UsuarioNegocio::create()->getUsuario($usuarioId, $usuarioId);
        
        return PedidoNegocio::create()->exportarPosicionCajaReporte(
            $data,
            $elemntosFiltrados,
            $response_cantidad_total,
            $datausu[0]['usuario']);
    }

    public function exportarPosicionCajaPdf()
    {
        $criteriosBusqueda = $this->getParametro("criteriosBusqueda");
        $order = array(array("column"=>"3","dir"=>"desc"));
        $columns = "fecha_apertura";
        $start = 0;
        if (ObjectUtil::isEmpty($criteriosBusqueda['fechaInicio']) && ObjectUtil::isEmpty($criteriosBusqueda['fechaFin'])) {
            throw new WarningException("Debe ingresar un rango de Fechas.");
        }
        $response_cantidad_total = ACCajaNegocio::create()->obtenerCantidadACCajaXCriterios($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $data = ACCajaNegocio::create()->obtenerACCajaXCriteriosReportePdf($criteriosBusqueda, $elemntosFiltrados, $columns, $order, $start);
        $usuarioId = $this->getUsuarioId();
        $datausu = UsuarioNegocio::create()->getUsuario($usuarioId, $usuarioId);

        return PedidoNegocio::create()->exportarPosicionCajaPdf(
            $data,
            $elemntosFiltrados,
            $response_cantidad_total,
            $datausu[0]['usuario']
        );
    }
    
        public function exportarLiquidacionAgenciaPdf()
    {
        $usuarioId = $this->getUsuarioId();
       
        $fecha2=$this->getParametro("fecha");
        
          if (!ObjectUtil::isEmpty($fecha2)) {
             $fechaAlterna = DateUtil::formatearCadenaACadenaBD($fecha2);
        }
        else {
             $fechaAlterna= date('Y-m-d');
        }
        $id_agencia=$this->getParametro("agencia");
     

        return PedidoNegocio::create()->exportarLiquidacionAgenciaPdf(
            $usuarioId,
            $fechaAlterna,
            $id_agencia
        );
    }

    //:::::DESARROLLO JESUS
    public function  exportarDetalleLiquidacionAgenciaPdf() {
        $usuarioId = $this->getUsuarioId();
       
        $fecha2=$this->getParametro("fecha");
        
          if (!ObjectUtil::isEmpty($fecha2)) {
             $fechaAlterna = DateUtil::formatearCadenaACadenaBD($fecha2);
        }
        else {
             $fechaAlterna= date('Y-m-d');
        }
        $id_agencia=$this->getParametro("agencia");
        return PedidoNegocio::create()->exportarDetalleLiquidacionAgenciaPdf(
            $usuarioId,
            $fechaAlterna,
            $id_agencia
        );
      
    }
    //::::: DESARROLLO JESUS
//add liquidacion reparto 021223
    public function BuscarLiquidacionReparto() {
        $RepartoSerie = $this->getParametro("Repartoserie");
        $RepartoNumero = $this->getParametro("Repartonumero");
        $data= MovimientoNegocio::create()->ObtenerLiquidacionReporte($RepartoSerie,$RepartoNumero);
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
        
    }


public function ExportarLiquidacionRepartopdf()
    {
        $RepartoSerie = $this->getParametro("Repartoseriepdf");
        $RepartoNumero = $this->getParametro("Repartonumeropdf");
        return  PedidoNegocio::create()->ExportarPreLiquidacionRepartopdf($RepartoSerie,$RepartoNumero);//MovimientoNegocio::create()->ExportarLiquidacionRepartopdf($RepartoSerie,$RepartoNumero);
    }

//
    public function ReportePedidosRepartoPCE()
    {
        $fechainiciopedido= $this->getParametro("bfechainipedido"); 
        $fechafinpedido= $this->getParametro("bfechafinpedido");
        $data= ReportesGerenciaNegocio::create()->getPedidosRepartoDomicilioPCE($fechainiciopedido,$fechafinpedido);
        //return $data;
        $elementosTotales = (count($data) > 0  ?  $data[0]['total_filas'] : 0);
        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }
 public function exportarReporteRepartosExcel()
    {
        $fechaininvrepxls= $this->getParametro("bfechainipedido"); 
        $fechafinnvrepxls= $this->getParametro("bfechafinpedido");
        return  ReportesGerenciaNegocio::create()->getPedidosRepartoDomicilioPCEEXcel($fechaininvrepxls,$fechafinnvrepxls);
    }
}
