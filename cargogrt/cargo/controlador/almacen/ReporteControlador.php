<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReporteNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienPrecioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ProgramacionPagoNegocio.php';

class ReporteControlador extends AlmacenIndexControlador {

    //funciones sobre la tabla persona clase
    public function obtenerConfiguracionesIniciales() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionInicial($idEmpresa);
    }

    public function obtenerDataBalance() {
        // seccion de obtencion de variables
        $criterios = $this->getParametro("criterios");

        $elementosFiltrados = $this->getParametro("length");
        $orden = $this->getParametro("order");
        $columnas = $this->getParametro("columns");
        $tamanio = $this->getParametro("start");

        $respuesta = ReporteNegocio::create()->reporteBalance($criterios, $elementosFiltrados, $orden, $columnas, $tamanio);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

    //ver
    public function obtenerConfiguracionesInicialesKardex() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesBienesMayorRotacion() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesComprometidosDia() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesRankingServicios() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesRankingServicios($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesEntradaSalidaAlmacen() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesEntradaSalidaAlmacen($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual() {
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual();
    }

    public function obtenerConfiguracionesInicialesDispersionBienes() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesEntradaSalidaAlmacen($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesEntradaSalida() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesEntradaSalidaAlmacen($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesRankingColaboradores() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesRankingColaboradores($idEmpresa);
    }

    public function obtenerConfiguracionesInicialesServiciosAtendidos() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesServiciosAtendidos($idEmpresa);
    }

    public function obtenerDataKardex() {
        // seccion de obtencion de variables

        $criterios = $this->getParametro("criterios");

        return ReporteNegocio::create()->reporteKardex($criterios);

//        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

    public function obtenerDataBienesMayorRotacion() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteBienesMayorRotacion($criterios);
    }

    public function obtenerDataComprometidosDia() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteComprometidosDia($criterios);
    }

    public function obtenerDataRankingServicios() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteRankingServicios($criterios);
    }

    public function obtenerDataEntradaSalidaAlmacen() {
        $criterios = $this->getParametro("criterios");
        //$reporte=ReporteNegocio::create()->reporteEntradaSalidaAlmacen($criterios);
        return ReporteNegocio::create()->reporteEntradaSalidaAlmacen($criterios);
    }

    public function obtenerDataEntradaSalidaAlmacenVirtual() {
        $criterios = $this->getParametro("criterios");
        $data = ReporteNegocio::create()->obtenerDataEntradaSalidaAlmacenVirtualXCriterios( $criterios);
        return $data;
    }
    
    public function obtenerDataEntradaSalidaAlmacenVirtualDetalle() {
        $documentoId = $this->getParametro("documentoId");
        $bienId = $this->getParametro("bienId");
        $data = ReporteNegocio::create()->obtenerDataEntradaSalidaAlmacenVirtualDetalle( $documentoId,$bienId);
        return $data;
    }
    
    public function obtenerDocumentoRelacionVisualizar() {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");
        $data=MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
        $data->configuracionEditable=  MovimientoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);
        $data->emailPersona=  DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);
        
        $dataMovimientoTipo=  MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        $data->dataAccionEnvio= MovimientoTipoNegocio::create()->obtenerMovimientoTipoAccionesVisualizacion($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataMovimientoTipoColumna=  MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);        
        $data->dataDocumentoAdjunto= DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);
        
        return $data;
    }
    
    public function obtenerDataDispersionBienes() {
        $criterios = $this->getParametro("criterios");
        
        $data= ReporteNegocio::create()->reporteDispersionBienes($criterios);
        return $data;
    }

    public function obtenerDataEntradaSalida() {
        $criterios = $this->getParametro("criterios");

        return ReporteNegocio::create()->reporteEntradaSalida($criterios);
    }

    public function obtenerDataRankingColaboradores() {
        $criterios = $this->getParametro("criterios");

        return ReporteNegocio::create()->reporteRankingColaboradores($criterios);
    }

    public function obtenerDataServicios() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteServicios($criterios);
    }

    function obtenerDetalleKardex() {

        $idBien = $this->getParametro("id_bien");
        $idOrganizador = $this->getParametro("id_organizador");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");

        return ReporteNegocio::create()->obtenerDetalleKardex($idBien, $idOrganizador, $fechaInicio, $fechaFin);
    }

    function obtenerDetalleBienesMayorRotacion() {

        $idBien = $this->getParametro("id_bien");
        $idOrganizador = $this->getParametro("id_organizador");
        $idUnidadMedida = $this->getParametro("id_unidadMedida");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");

        return ReporteNegocio::create()->obtenerDetalleBienesMayorRotacion($idBien, $idOrganizador, $idUnidadMedida, $fechaInicio, $fechaFin);
    }

    function obtenerDetalleComprometidosDia() {

        $idBien = $this->getParametro("id_bien");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");
        $empresaId = $this->getParametro("empresaId");

        return ReporteNegocio::create()->obtenerDetalleComprometidosDia($idBien, $fechaInicio, $fechaFin,$empresaId);
    }

    function obtenerDetalleRankingServicios() {

        $idBien = $this->getParametro("id_bien");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");

        return ReporteNegocio::create()->obtenerDetalleRankingServicios($idBien, $fechaInicio, $fechaFin);
    }

    function obtenerDetalleEntradaSalidaAlmacen() {

        $idOrganizador = $this->getParametro("id_organizador");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");
        $empresaId = $this->getParametro("id_empresa");
        $indicador = $this->getParametro("indicador");

        $criterios = array(array(
                'organizador' => array($idOrganizador),
                'fechaEmision' => array('inicio' => $fechaInicio, 'fin' => $fechaFin),
                'empresaId' => $empresaId
        ));
        return ReporteNegocio::create()->reporteDetalleEntradaSalidaAlmacen($criterios, $indicador);

        //return ReporteNegocio::create()->obtenerDetalleBienesMayorRotacion($idBien,$idOrganizador,$idUnidadMedida,$fechaInicio,$fechaFin);
    }

    function obtenerDocumentoServicios() {

        $idBien = $this->getParametro("id_bien");
        //$idOrganizador = $this->getParametro("id_organizador");
        $fechaInicio = $this->getParametro("fecha_inicio");
        $fechaFin = $this->getParametro("fecha_fin");

        return ReporteNegocio::create()->obtenerDocumentoServicios($idBien, $fechaInicio, $fechaFin);
    }

    public function obtenerConfiguracionesInicialesKardexGeneral() {
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa = -1);
    }

    // Reportde de deuda
    public function obtenerConfiguracionesInicialesDeuda() {
        return PersonaNegocio::create()->obtenerActivas();
    }

    public function obtenerDataDeuda() {
        $tipo1 = 1;
        $tipo2 = 3;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteDeudaXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteDeudaXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        // return $this->obtenerRespuestaDataTable($data, 5, 5);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }            
        }
        
        $dataTotal=ReporteNegocio::create()->obtenerCantidadesTotalesCuentasPorCobrar($tipo1, $tipo2, $criterios);
        
        $data[0]['pagado_soles_reporte']=(float)$dataTotal[0]["pagado_soles_reporte"];
        $data[0]['deuda_soles_reporte']=(float)$dataTotal[0]["deuda_soles_reporte"];
        $data[0]['pagado_dolares_reporte']=(float)$dataTotal[0]["pagado_dolares_reporte"];
        $data[0]['deuda_dolares_reporte']=(float)$dataTotal[0]["deuda_dolares_reporte"];
                
        if(ObjectUtil::isEmpty($data[0]['documento_tipo_descripcion'])){
            $data=null;
        }
        
        $res= $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
        return $res;
    }

    public function obtenerConfiguracionesInicialesDeudaGeneral() {
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesDeudaGeneral();
    }

    public function obtenerDataDeudaGeneral() {
        $tipo1 = 1;
        $tipo2 = 3;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteDeudaGeneralXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
//        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadGeneralReporteDeudaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteDeudaGeneralXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }
        
        $dataTotal=ReporteNegocio::create()->obtenerCantidadesTotalesCuentasPorCobrarGeneral($tipo1, $tipo2, $criterios);
        
        $data[0]['pagado_soles_reporte']=(float)$dataTotal[0]["pagado_soles_reporte"];
        $data[0]['deuda_soles_reporte']=(float)$dataTotal[0]["deuda_soles_reporte"];
        $data[0]['pagado_dolares_reporte']=(float)$dataTotal[0]["pagado_dolares_reporte"];
        $data[0]['deuda_dolares_reporte']=(float)$dataTotal[0]["deuda_dolares_reporte"];
        
        if(ObjectUtil::isEmpty($data[0]['documento_tipo_descripcion'])){
            $data=null;
        }        

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    //Fin reporte de cobranza

    function obtenerReporteBalanceExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteBalanceExcel($criterios);
    }

    function obtenerReporteKardexExcel() {
        $criterios = $this->getParametro("criterios");
        $tipo = $this->getParametro("tipo");
        return ReporteNegocio::create()->obtenerReporteKardexExcel($criterios, $tipo);
    }

    function obtenerReporteBienesMayorRotacionExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteBienesMayorRotacionExcel($criterios);
    }

    function obtenerReporteRankingColaboradoresExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteRankingColaboradoresExcel($criterios);
    }

    function obtenerReporteComprometidosDiaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteComprometidosDiaExcel($criterios);
    }

    function obtenerReporteRankingServiciosExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteRankingServiciosExcel($criterios);
    }

    function obtenerReporteEntradaSalidaAlmacenExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteEntradaSalidaAlmacenExcel($criterios);
    }

    function obtenerReporteEntradaSalidaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteEntradaSalidaExcel($criterios);
    }

    function obtenerReporteServiciosAtendidosExcel() {
        $criterios = $this->getParametro("criterios");
        $tipo = $this->getParametro("tipo");
        return ReporteNegocio::create()->obtenerReporteServiciosAtendidosExcel($criterios, $tipo);
    }

    public function obtenerConfiguracionesInicialesReporteXOrganizador() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteXOrganizador($idEmpresa);
    }

    public function obtenerReporteXOrganizador() {

        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->ReporteXOrganizador($criterios);
    }

    public function obtenerTotalBalance() {

        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerTotalBalance($criterios);
    }

    //Reporte cuentas por pagar

    public function obtenerConfiguracionesInicialesPagar() {
        return PersonaNegocio::create()->obtenerActivas();
    }

    public function obtenerDataPagar() {
        $tipo1 = 4;
        $tipo2 = 6;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteDeudaXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteDeudaXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }
        
        $dataTotal=ReporteNegocio::create()->obtenerCantidadesTotalesCuentasPorCobrar($tipo1, $tipo2, $criterios);
        
        $data[0]['pagado_soles_reporte']=(float)$dataTotal[0]["pagado_soles_reporte"];
        $data[0]['deuda_soles_reporte']=(float)$dataTotal[0]["deuda_soles_reporte"];
        $data[0]['pagado_dolares_reporte']=(float)$dataTotal[0]["pagado_dolares_reporte"];
        $data[0]['deuda_dolares_reporte']=(float)$dataTotal[0]["deuda_dolares_reporte"];

        $data[0]['deuda_liberada_soles_reporte']=(float)$dataTotal[0]["deuda_liberada_soles_reporte"];
        $data[0]['deuda_por_liberar_soles_reporte']=(float)$dataTotal[0]["deuda_por_liberar_soles_reporte"];
        $data[0]['deuda_liberada_dolares_reporte']=(float)$dataTotal[0]["deuda_liberada_dolares_reporte"];
        $data[0]['deuda_por_liberar_dolares_reporte']=(float)$dataTotal[0]["deuda_por_liberar_dolares_reporte"];
        
        if(ObjectUtil::isEmpty($data[0]['documento_tipo_descripcion'])){
            $data=null;
        }
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerConfiguracionesInicialesPagarGeneral() {
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesDeudaGeneral();
    }

    public function obtenerDataPagarGeneral() {
        $tipo1 = 4;
        $tipo2 = 6;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteDeudaGeneralXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
//        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadGeneralReporteDeudaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteDeudaGeneralXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }        
        
        $dataTotal=ReporteNegocio::create()->obtenerCantidadesTotalesCuentasPorCobrarGeneral($tipo1, $tipo2, $criterios);
                
        $data[0]['pagado_soles_reporte']=(float)$dataTotal[0]["pagado_soles_reporte"];
        $data[0]['deuda_soles_reporte']=(float)$dataTotal[0]["deuda_soles_reporte"];
        $data[0]['pagado_dolares_reporte']=(float)$dataTotal[0]["pagado_dolares_reporte"];
        $data[0]['deuda_dolares_reporte']=(float)$dataTotal[0]["deuda_dolares_reporte"];
        
        if(ObjectUtil::isEmpty($data[0]['documento_tipo_descripcion'])){
            $data=null;
        }
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    //fin reportde de cuentas por pagar
    //Reporte de balance consolidado

    public function obtenerConfiguracionesInicialesBalanceConsolidado() {
        $idEmpresa = $this->getParametro("id_empresa");
        $idTipos = '(1)(4)';
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesBalanceConsolidado($idEmpresa, $idTipos);
    }

    public function obtenerDataBalanceConsolidado() {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteBalanceConsolidadoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteBalanceConsolidadoXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
//         return $this->obtenerRespuestaDataTable($data, 5, 5);
//        $elemntosFiltrados = 10;
//        $elementosTotales = 10;
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerCantidadesTotalesBalanceConsolidado() {

        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerCantidadesTotalesBalanceConsolidado($criterios);
    }

    //fin de reporte balance consolidado
    //Reporte movimiento persona
    public function obtenerConfiguracionesInicialesMovimientoPersona() {
        $idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesMovimientoPersona($idEmpresa);
    }

    public function obtenerDataMovimientoPersona() {
//        $tipo1 = '1';
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerMovimientoPersonaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadMovimientoPersonaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        // return $this->obtenerRespuestaDataTable($data, 5, 5);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerCantidadesTotalesMovimientoPersona() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerCantidadesTotalesMovimientoPersona($criterios);
    }

    //Fin reporte movimiento persona
    //Reporte balance consolidado general

    public function obtenerConfiguracionesInicialesBalanceConsolidadoGeneral() {
        $idTipos = '(1)(4)';
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesBalanceConsolidadoGeneral($idTipos);
    }

    public function obtenerDataBalanceConsolidadoGeneral() {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteBalanceConsolidadoGeneralXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteBalanceConsolidadoGeneralXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
//         return $this->obtenerRespuestaDataTable($data, 5, 5);
//        $elemntosFiltrados = 10;
//        $elementosTotales = 10;
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerCantidadesTotalesBalanceConsolidadoGeneral() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerCantidadesTotalesBalanceConsolidadoGeneral($criterios);
    }

    //Fin Reporte balance consolidado general
    // Reporte movimiento persona general

    public function obtenerConfiguracionesInicialesMovimientoPersonaGeneral() {
//        $idTipos = '(0)';
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesMovimientoPersonaGeneral();
    }

    public function obtenerCantidadesTotalesMovimientoPersonaGeneral() {

        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerCantidadesTotalesMovimientoPersonaGeneral($criterios);
    }

    public function obtenerDataMovimientoPersonaGeneral() {
//        $tipo1 = '1';
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerMovimientoPersonaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadMovimientoPersonaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        // return $this->obtenerRespuestaDataTable($data, 5, 5);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_documento'] == 1) {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
            } else {
                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
            }
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    // Fin reporte movimiento persona general
    ///Visualizar detalle en cuentas por pagar 
    public function obtenerDetallePago() {
        $documentoId = $this->getParametro("documentoId");
        return PagoNegocio::create()->obtenerDetallePago($documentoId);
    }

    public function obtenerDetalleCobro() {
        $documentoId = $this->getParametro("documentoId");
        return PagoNegocio::create()->obtenerDetallePago($documentoId);
    }
    
    public function obtenerBienesCantMinimaAlcanzada(){
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerBienesCantMinimaAlcanzada($criterios);
    }

    function obtenerReporteBienesCantMinimaAlcanzadaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteBienesCantMinimaAlcanzadaExcel($criterios);
    }
    
    //Reporte ventas por vendedor
    public function obtenerConfiguracionesInicialesVentasPorVendedor(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesVentasPorVendedor();
    }
    
    public function obtenerCantidadesTotalesVentasPorVendedor(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesVentasPorVendedor($criterios);
        return $data;

    }
    
     public function obtenerDataVentasPorVendedor()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteVentasPorVendedorXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteVentasPorVendedorXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteVentasPorVendedorExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteVentasPorVendedorXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteVentasPorVendedorExcel($data);
    }          
   
    //Reporte ventas por tienda
    public function obtenerConfiguracionesInicialesVentasPorTienda(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesVentasPorTienda();
    }
    
    public function obtenerCantidadesTotalesVentasPorTienda(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesVentasPorTienda($criterios);
        return $data;

    }
    
     public function obtenerDataVentasPorTienda()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteVentasPorTiendaXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteVentasPorTiendaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteVentasPorTiendaExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteVentasPorTiendaXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteVentasPorTiendaExcel($data);
    }    
    
    //Reporte ventas comision por vendedor
    public function obtenerConfiguracionesInicialesComisionVendedor(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesComisionVendedor();
    }

    public function obtenerDataComisionVendedor() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteComisionVendedor($criterios);
        return $data;
    }

    function obtenerReporteComisionVendedorExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteComisionVendedorExcel($criterios);
    }
    
    //Reporte ventas por tiempo
    public function obtenerConfiguracionesInicialesPorTiempo(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorTiempo();
    }

    public function obtenerDataPorTiempo() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reportePorTiempo($criterios);
        $total=ReporteNegocio::create()->obtenerCantidadesTotalesVentasPorTiempo($criterios);
        
        $respuesta = new ObjectUtil();
        $respuesta->datos=$data; 
        $respuesta->total=$total; 
        
        return $respuesta;
    }

    function obtenerReportePorTiempoExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReportePorTiempoExcel($criterios);
    }   
    
    //Reporte ventas productos mas vendidos
    public function obtenerConfiguracionesInicialesProductosMasVendidos(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesProductosMasVendidos();
    }

    public function obtenerDataProductosMasVendidos() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteProductosMasVendidos($criterios);
        return $data;
    }

    function obtenerReporteProductosMasVendidosExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteProductosMasVendidosExcel($criterios);
    }
    
    //Reporte ventas y compras por producto
    public function obtenerConfiguracionesInicialesPorProducto(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorProducto();
    }

    public function obtenerDataPorProducto() {
        $criterios = $this->getParametro("criterios");
        $tipo=$this->getParametro("tipo");
        $data=ReporteNegocio::create()->reportePorProducto($criterios,$tipo);
        return $data;
    }

    function obtenerReportePorProductoExcel() {
        $criterios = $this->getParametro("criterios");
        $tipo=$this->getParametro("tipo");
        return ReporteNegocio::create()->obtenerReportePorProductoExcel($criterios,$tipo);
    }
    
    //Reporte ventas stock valorizado
    public function obtenerConfiguracionesInicialesStockValorizado(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesStockValorizado();
    }

    public function obtenerDataStockValorizado() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteStockValorizado($criterios);
        return $data;
    }

    function obtenerReporteStockValorizadoExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteStockValorizadoExcel($criterios);
    }
    
    //Reporte compras
    public function obtenerConfiguracionesInicialesReporteCompras(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteCompras();
    }
    
    public function obtenerCantidadesTotalesReporteCompras(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesReporteCompras($criterios);
        return $data;

    }
    
     public function obtenerDataReporteCompras()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteReporteComprasXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteReporteComprasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);    
        for ($i = 0; $i < $tamanio; $i++) {
            
                $data[$i]['acciones'] = "<a href='#' onclick='obtenerDocumento(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;".
                                         "<a href='#' onclick='obtenerDocumentosRelacionados(" . $data[$i]['documento_id'].",0)' title='Documento Relacionado'><b><i class='ion-android-share' style='color:#E8BA2F;'></i><b></a>&nbsp;"; 
                       
//            } else {
//                $data[$i]['acciones'] = "<a href='#' onclick='visualizarDocumento(" . $data[$i]['documento_id'] . ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp";
//            }
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);

    }
    
    function obtenerReporteReporteComprasExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteReporteComprasXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteReporteComprasExcel($data);
    }          
    function obtenerReporteComprasProducto() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteReporteComprasXCriteriosProducto( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteComprasProducto($data);
    }          
    
    
    //Reporte activos fijos
    public function obtenerConfiguracionesInicialesActivosFijos(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesActivosFijos();
    }

    public function obtenerDataActivosFijos() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteActivosFijos($criterios);
        return $data;
    }

    function obtenerReporteActivosFijosExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteActivosFijosExcel($criterios);
    }
    
    //reporte estadistico de ventas
    
    public function obtenerConfiguracionesInicialesReporteEstadisticoVentas() {
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteEstadisticoVentas();
    }

    public function obtenerDataReporteEstadisticoVentas() {
        $criterios = $this->getParametro("criterios");
        $data= ReporteNegocio::create()->reporteReporteEstadisticoVentas($criterios);
        return $data;
    }
        
    //Reporte ventas por cliente
    public function obtenerConfiguracionesInicialesVentasPorCliente(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesVentasPorCliente();
    }
    
    public function obtenerCantidadesTotalesVentasPorCliente(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesVentasPorCliente($criterios);
        return $data;

    }
    
    public function obtenerDataVentasPorCliente()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteVentasPorClienteXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteVentasPorClienteXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    public function obtenerGraficoVentasPorCliente()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteVentasPorClienteXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteVentasPorClienteXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteVentasPorClienteExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteVentasPorClienteXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteVentasPorClienteExcel($data);
    }

    public function verDetallePorCliente() {
        $documentoId = $this->getParametro("documento_id");
        $movimientoId = $this->getParametro("movimiento_id");
        
        $data= ReporteNegocio::create()->verDetallePorCliente($documentoId, $movimientoId);
        return $data;
    }
    
    //Reporte ventas reporte de utilidades
    public function obtenerConfiguracionesInicialesReporteUtilidades(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteUtilidades();
    }

    public function obtenerDataReporteUtilidades() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteReporteUtilidades($criterios);
        return $data;
    }

    function obtenerReporteReporteUtilidadesExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteReporteUtilidadesExcel($criterios);
    }
    
    //Reporte tributario
    public function obtenerConfiguracionesInicialesReporteTributario(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteTributario();
    }

    public function obtenerDataReporteTributario() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteTributario($criterios);
        
        $total=ReporteNegocio::create()->obtenerCantidadesTotalesReporteTributario($criterios);
        
        $respuesta = new ObjectUtil();
        $respuesta->datos=$data; 
        $respuesta->total=$total; 
        
        return $respuesta;
    }

    function obtenerReporteTributarioExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteTributarioExcel($criterios);
    }     
   
    //Reporte notas credito y debito
    public function obtenerConfiguracionesInicialesNotasCreditoDebito(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesNotasCreditoDebito();
    }
    
    public function obtenerCantidadesTotalesNotasCreditoDebito(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesNotasCreditoDebito($criterios);
        return $data;

    }
    
     public function obtenerDataNotasCreditoDebito()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteNotasCreditoDebitoXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteNotasCreditoDebitoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteNotasCreditoDebitoExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteNotasCreditoDebitoXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteNotasCreditoDebitoExcel($data);
    }    
    
    //Reporte ventas IGV Ventas
    public function obtenerConfiguracionesInicialesVentasIgvVentas(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesVentasIgvVentas();
    }
    
    public function obtenerCantidadesTotalesVentasIgvVentas(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesVentasIgvVentas($criterios);
        return $data;

    }
    
     public function obtenerDataVentasIgvVentas()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteVentasIgvVentasXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteVentasIgvVentasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteVentasIgvVentasExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteVentasIgvVentasXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteVentasIgvVentasExcel($data);
    }
    
    //Reporte Caja Bancos por actividad
    public function obtenerConfiguracionesInicialesPorActividad(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorActividad();
    }

    public function obtenerDataPorActividad() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reportePorActividad($criterios);
        
        // totales
        $tamanio = count($data);
        $totales = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $totales=(float)$data[$i]['total']+$totales;
        }
        
        $resultado->datos=$data;
        $resultado->total=$totales;
        
        return $resultado;
    }

    function obtenerReportePorActividadExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReportePorActividadExcel($criterios);
    }
    
    //Reporte Caja Bancos por cuenta
    public function obtenerConfiguracionesInicialesPorCuenta(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorCuenta();
    }

    public function obtenerDataPorCuenta() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reportePorCuenta($criterios);
        $totales=ReporteNegocio::create()->reportePorCuentaTotales($criterios);
        
        $resultado->datos=$data;
        $resultado->total=$totales;
        
        return $resultado;
    }

    function obtenerReportePorCuentaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReportePorCuentaExcel($criterios);
    }
    
    //Reporte cierre caja
    public function obtenerConfiguracionesInicialesCierreCaja(){
        $usuarioId = $this->getUsuarioId();
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesCierreCaja($usuarioId);
    }

    public function obtenerDataCierreCaja() {
        $criterios = $this->getParametro("criterios");
        $data = ReporteNegocio::create()->reporteCierreCaja($criterios);
        $totales = ReporteNegocio::create()->reporteCierreCajaTotales($criterios);
        $resultado = new stdClass();
        $resultado->datos = $data;
        $resultado->total = $totales;

        return $resultado;
    }

    function obtenerReporteCierreCajaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteCierreCajaExcel($criterios);
    }
    
    //Reporte orden compra
    public function obtenerConfiguracionesInicialesReporteOrdenCompra(){
        //$idEmpresa = $this->getParametro("id_empresa");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteOrdenCompra();
    }
    
    public function obtenerCantidadesTotalesReporteOrdenCompra(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesReporteOrdenCompra($criterios);
        return $data;

    }
    
     public function obtenerDataReporteOrdenCompra()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteReporteOrdenCompraXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteReporteOrdenCompraXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteReporteOrdenCompraExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteReporteOrdenCompraXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteReporteOrdenCompraExcel($data);
    }
    
    //Reporte retencion detraccion
    public function obtenerConfiguracionesInicialesRetencionDetraccion(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesRetencionDetraccion();
    }

    public function obtenerDataRetencionDetraccion() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reporteRetencionDetraccion($criterios);
        return $data;
    }

    function obtenerReporteRetencionDetraccionExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReporteRetencionDetraccionExcel($criterios);
    }
    
    //Reporte Caja Bancos por actividad por fecha
    public function obtenerConfiguracionesInicialesPorActividadPorFecha(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorActividadPorFecha();
    }

    public function obtenerDataPorActividadPorFecha() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reportePorActividadPorFecha($criterios);
        
        // totales
        $tamanio = count($data);
        $totales = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $totales=(float)$data[$i]['total']+$totales;
        }
        
        $resultado->datos=$data;
        $resultado->total=$totales;
        
        return $resultado;
    }

    function obtenerReportePorActividadPorFechaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReportePorActividadPorFechaExcel($criterios);
    }
    
    //Reporte Caja Bancos por cuenta fecha
    public function obtenerConfiguracionesInicialesPorCuentaFecha(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesPorCuentaFecha();
    }

    public function obtenerDataPorCuentaFecha() {
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->reportePorCuentaFecha($criterios);
        $totales=ReporteNegocio::create()->reportePorCuentaFechaTotales($criterios);
        
        $resultado->datos=$data;
        $resultado->total=$totales;
        
        return $resultado;
    }

    function obtenerReportePorCuentaFechaExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerReportePorCuentaFechaExcel($criterios);
    }

    public function obtenerConfiguracionesInicialesKardexReporte() {
        $idEmpresa = $this->getParametro("empresaId");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa);
    }

    public function obtenerDataKardexReporte() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteKardexReporte($criterios);
    }

    function obtenerKardexReporteExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerKardexReporteExcel($criterios);
    }
    
    //kardex valorizado
    public function obtenerConfiguracionesInicialesKardexValorizado() {
        $idEmpresa = $this->getParametro("empresaId");
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesKardex($idEmpresa);
    }

    public function obtenerDataKardexValorizado() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->reporteKardexValorizado($criterios);
    }

    function obtenerKardexValorizadoExcel() {
        $criterios = $this->getParametro("criterios");
        return ReporteNegocio::create()->obtenerKardexValorizadoExcel($criterios);
    }

    function obtenerConfiguracionesInicialesListadoAtencion(){
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $lista = ReporteNegocio::create()->obtenerConfiguracionesInicialesListadoAtencion($usuarioId, $empresaId);
        return $lista;
    }

    function obtenerDataReporteAtenciones()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteReporteAtenciones( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteReporteAtencionesXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function getMapaConstruccionData()
    {
        $documentoId = $this->getParametro("documentoId");
        $respuesta = new stdClass();
        $respuesta->dataAnteriores =  MovimientoNegocio::create()->obtenerReporteDocumentosAsignaciones($documentoId);

        $respuesta->dataPosteriores = MovimientoNegocio::create()->obtenerReporteDocumentosAsignacionesVariante($documentoId);
        $asd = MovimientoNegocio::create()->obtenerTablaAtencionesColumnas($documentoId);
        $respuesta->dataTablaAtencionesColumnas = $asd->columnas;
        $respuesta->dataTablaAtencionesData = $asd->dataColumnas;
        $respuesta->dataTablaLeftSide = $asd->leftSide;

        return $respuesta;
    }

    public function visualizarDetalleDocumentoReporteAsignacion()
    {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");

        $data=MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
        $data->configuracionEditable=  MovimientoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);
        $data->emailPersona=  DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

        $dataMovimientoTipo=  MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        $data->dataAccionEnvio= MovimientoTipoNegocio::create()->obtenerMovimientoTipoAccionesVisualizacion($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataMovimientoTipoColumna=  MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);

        return $data;
    }
        
    //Reporte OPERACIONES
    public function obtenerConfiguracionesInicialesReporteOperaciones(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesReporteOperaciones();
    }
    
    public function obtenerCantidadesTotalesReporteOperaciones(){
        $criterios = $this->getParametro("criterios");
        $data=ReporteNegocio::create()->obtenerCantidadesTotalesReporteOperaciones($criterios);
        return $data;

    }
    
     public function obtenerDataReporteOperaciones()
    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteReporteOperacionesXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteReporteOperacionesXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      

    }
    
    function obtenerReporteReporteOperacionesExcel() {
        
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteReporteOperacionesXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        
        return ReporteNegocio::create()->obtenerReporteReporteOperacionesExcel($data);
    }

    public function verDetallePorOperacion() {
        $documentoId = $this->getParametro("documento_id");
        
        $data= ReporteNegocio::create()->verDetallePorOperacion($documentoId);
        return $data;
    }

    public function obtenerBienTipoHijo() {
        $bienTipoPadreId = $this->getParametro("bienTipoPadreId");
        
        return BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoPadreId);
    } 
    
    //Reporte ventas producto por periodo (SE PUEDE ADAPTAR PARA COMPRAS ENVIANDO EL TIPO 4)
    public function obtenerConfiguracionesInicialesProductoPorPeriodo(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesProductoPorPeriodo();
    }

    public function obtenerDataProductoPorPeriodo() {
        $criterios = $this->getParametro("criterios");
        $tipo=$this->getParametro("tipo");
        $data=ReporteNegocio::create()->reporteProductoPorPeriodo($criterios,$tipo);
        return $data;
    }

    function obtenerReporteProductoPorPeriodoExcel() {
        $criterios = $this->getParametro("criterios");
        $tipo=$this->getParametro("tipo");
        return ReporteNegocio::create()->obtenerReporteProductoPorPeriodoExcel($criterios,$tipo);
    }

    public function reportePorClienteObtenerGraficoClientesDolares(){
        $criterios = $this->getParametro("criterios");
        $sumatoria = $this->getParametro("sumatoria");
        return ReporteNegocio::create()->reportePorClienteObtenerGraficoClientesDolares($criterios, $sumatoria);
    }
    
    public function reportePorClienteObtenerGraficoClientesSoles(){
        $criterios = $this->getParametro("criterios");
        $sumatoria = $this->getParametro("sumatoria");
        return ReporteNegocio::create()->reportePorClienteObtenerGraficoClientesSoles($criterios, $sumatoria);
    }
    
    public function reportePorClienteObtenerGraficoProductosDolares(){
        $criterios = $this->getParametro("criterios");
        $sumatoria = $this->getParametro("sumatoria");
        return ReporteNegocio::create()->reportePorClienteObtenerGraficoProductosDolares($criterios, $sumatoria);
    }
    public function reportePorClienteObtenerGraficoProductosSoles(){
        $criterios = $this->getParametro("criterios");
        $sumatoria = $this->getParametro("sumatoria");
        return ReporteNegocio::create()->reportePorClienteObtenerGraficoProductosSoles($criterios, $sumatoria);
    }
    
    public function obtenerProgramacionPago(){
        $documentoId = $this->getParametro("documentoId");
        return ProgramacionPagoNegocio::create()->obtenerProgramacionPagoXDocumentoId($documentoId);
    }
    
    public function obtenerDataCotizaciones(){
        return ReporteNegocio::create()->obtenerDataCotizaciones();        
    }
    public function obtenerCotizacionesDetalle(){
        $bienId = $this->getParametro("bienId");
        return ReporteNegocio::create()->obtenerCotizacionesDetalle($bienId);        
    }
    
    public function obtenerDataCotizacionesExt(){
        return ReporteNegocio::create()->obtenerDataCotizacionesExt();        
    }
    public function obtenerCotizacionesDetalleExt(){
        $bienId = $this->getParametro("bienId");
        return ReporteNegocio::create()->obtenerCotizacionesDetalleExt($bienId);        
    }
    
    //TRANSFERENCIA TRANSFORMACION NO ATENDIDAS
    
    public function obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida(){
        return ReporteNegocio::create()->obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida();
    }
        
     public function obtenerDataTransferenciaTransformacionNoAtendida()    {
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteTransferenciaTransformacionNoAtendidaXCriterios( $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteTransferenciaTransformacionNoAtendidaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      
    }
    //FIN TRANSFERENCIA TRANSFORMACION NO ATENDIDAS
        
     public function obtenerDataTransferenciaDiferente(){
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteTransferenciaDiferenteXCriterios( $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteTransferenciaDiferenteXCriterios($elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);      
    }
    public function exportarReporteVentas() {
        $proveedor = $this->getParametro("persona");
        return ReporteNegocio::create()->exportarReporteVentasConFormato($proveedor);        
//        return ReporteNegocio::create()->exportarReporteVentas($proveedor);        
    }
    
    //REPORTE CXP BHDT
    public function obtenerDataPagarBHDT() {
        $tipo1 = 4;
        $tipo2 = 6;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = ReporteNegocio::create()->obtenerReporteDeudaBHDTXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = ReporteNegocio::create()->obtenerCantidadReporteDeudaBHDTXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);
//        for ($i = 0; $i < $tamanio; $i++) {
//            if ($data[$i]['estado_documento'] == 1) {
//                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>&nbsp;\n" .
//                        "<a href='#' onclick='anular(" . $data[$i]['documento_id'] . ")' title='anular'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>";
//            } else {
//                $data[$i]['acciones'] = "<a href='#' onclick='imprimirDocumento(" . $data[$i]['documento_id'] . "," . $data[$i]['documento_tipo_id'] . ")' title='imprimir'><b><i class='fa fa-print' style='color:green;'></i><b></a>";
//            }
//        }
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }
    
    public function exportarExcelComprasBHDT(){
        $tipo1 = 4;
        $tipo2 = 6;
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = 10000;
        $order = null;
        $columns = null;
        $start = 0;
        $data = ReporteNegocio::create()->obtenerReporteDeudaBHDTXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start);
        return ReporteNegocio::create()->exportarExcelComprasBHDT2($data);
        
    }
    public function obtenerDocumento() {
        $documentoId = $this->getParametro("documentoId");
        $data=ReporteNegocio::create()->obtenerDocumento($documentoId);
        return $data;
    }
    
    public function obtenerDocumentosRelacionados() {

        $documentoId = $this->getParametro("documentoId");
        $relacionados = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
        return $relacionados;
    }
}
