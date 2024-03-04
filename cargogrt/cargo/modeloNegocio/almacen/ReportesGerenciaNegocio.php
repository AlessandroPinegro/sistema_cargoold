<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../modelo/almacen/ReportesGerencia.php';
include_once __DIR__ . '/../../util/Util.php';

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPMailer/class.phpmailer.php';

require_once __DIR__ . '/../../util/EmailEnvioUtil.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/GraficoNegocio.php';


class ReportesGerenciaNegocio extends ModeloNegocioBase {

    private $estiloTituloReporte, $estiloTituloColumnas, $estiloInformacion;

     /**
     *
     * @return ReportesGerenciaNegocio
     */
    static function create() {
        return parent::create();
    }

 public function getreporteLiquidacionDeNotasDeVentaExcel($fInicio, $fFinal){
    $respuesta = $this->getreporteLiquidacionDeNotasDeVenta($fInicio, $fFinal);
      if (ObjectUtil::isEmpty($respuesta)) {
        throw new WarningException("No existe datos para exportar");
    } else {
        $this->crearReporteLiquidacionNotasdeVenta($respuesta, "Reporte de Liquidacion de Notas de Venta",$fInicio, $fFinal);
    }
 }

    public function getreporteLiquidacionDeNotasDeVenta($fechaInicio = null ,$fechaFin = null){
        $data = ReportesGerencia::create()->obtenerReporteLiquidacionDeNotasDeVenta($fechaInicio,$fechaFin);
        return $data;
    }


    public function crearReporteLiquidacionNotasdeVenta($reportes, $titulo,$fInicio, $fFinal){
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':O' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo. ' '.$fInicio.'/' .$fFinal);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'COMPROBANTE');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'CLIENTE');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'AG. DE ORIGEN');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'AG. DE DESTINO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'FECHA');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'TOTAL');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'DESCRIPCION');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'LIQUIDACION');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'FE. LIQUIDACION');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'TOTAL LIQUIDACION');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'FACTURA CREDITO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'FE. DE FACTURACION');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'TOTAL DE FACTURACION');

        
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'G R T E');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'G R R T');
        
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

         for ($j=0; $j < count($reportes) ; $j++) { 
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reportes[$j]['serie'] .'-'. $reportes[$j]['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reportes[$j]['cliente']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reportes[$j]['origen']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reportes[$j]['destino']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reportes[$j]['fechanota']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reportes[$j]['total']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reportes[$j]['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reportes[$j]['liquidacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reportes[$j]['fechaliq']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $reportes[$j]['totalliq']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $reportes[$j]['facturacredito']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, $reportes[$j]['fechafc']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, $reportes[$j]['totalfc']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, $reportes[$j]['GRTE']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, $reportes[$j]['GRRT']);

           $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':O' . $i)->applyFromArray($this->estiloInformacion);
           $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
         }

        for ($i = 'A'; $i <= 'O'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;

 } 



    public function ReportesGerenciaTest()
    {
        $data = ReportesGerencia::create()->getDataReportetest01();
        return $data;
    }

    public function testreporte1($bfechaini,$bfechafin)
    {
        $data = ReportesGerencia::create()->getDataBuscartestreporte1($bfechaini,$bfechafin);
        return $data;
    }
    // ReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin);

    public function ReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin)
    {
        $data = ReportesGerencia::create()->getReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin);
        return $data;
    }

    public function exportarReporteExcel($bfechaini,$bfechafin)
    {
        $data = ReportesGerencia::create()->getDataBuscartestreporte1($bfechaini,$bfechafin);
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        }else{
             $this->cargadataExcel($data,"Reporte Test");
        }
    }


/********************reporte excel ventas totales soles - para pase a producción  */


    public function getexportarReporVentasSolesTotalExcel($bfechaini,$bfechafin)
    {
         $data = ReportesGerencia::create()->getReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin);
         
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar...");
        }else{
             $this->cargadataVentasSolesTotalExcel($data,"Ventas Totales Soles");
        }

    }

    private function cargadataVentasSolesTotalExcel($dataresp,$titulo)
    {
        $this->estilosExcel();
        $objPHPExcel= new PHPExcel();
        $contfilas=1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contfilas . ':J' . $contfilas);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $titulo);

        $contfilas += 2;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, 'SerieCorrelativo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, 'Tipo_doc.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, 'Remitente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, 'Destinatario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, 'Modalidad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, 'Est. pedido');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, 'Est. Cpe');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, 'Origen');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, 'Destino');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, 'F.Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, 'Otros Gastos');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, 'Devolución');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, 'Costo reparto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, 'Costo Recojo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, 'Ajuste de Precio');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, 'Sub Total');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, 'IGV');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, 'Total');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, 'Serie N/C');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, 'Número N/C');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, 'P.efectivo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, 'P.deposito');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, 'P.transferencia');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, 'P.POS');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, 'P.Pasarela');

        $contfilas +=1;
        foreach($dataresp as $detdata)
        {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $detdata['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, $detdata['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, $detdata['serie_correlativo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, $detdata['tipo_documento']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, $detdata['Remitente']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, $detdata['Destinatario']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, $detdata['modalidad']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, $detdata['estado_pedido']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, $detdata['estado_comprobante']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, $detdata['agencia_origen']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, $detdata['agencia_destino']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, $detdata['fecha_venta']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, round($detdata['otros_gastos'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, round($detdata['devolucion_gasto'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, round($detdata['costo_reparto'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, round($detdata['costo_recojodomicilio'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, round($detdata['ajuste_precio'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, round($detdata['subtotal'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, round($detdata['igv'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, round($detdata['venta_total'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, $detdata['serie_nc']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, $detdata['numero_nc']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, round($detdata['pago_efectivo'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, round($detdata['pago_ticketdeposito'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, round($detdata['pago_tickettransferencia'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, round($detdata['pago_ticketpos'],2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, round($detdata['pago_pasarelapago'],2));
            $contfilas +=1;
        }

        $objPHPExcel->getActiveSheet()->setTitle("Reportes Ventas Totales Soles");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reportetotalventassoles.xls');

        return 1;
    }


public function getInventarioAll($fechaininv,$fechafinnv)
{
    $data = ReportesGerencia::create()->getReporteInventarioxFecha($fechaininv,$fechafinnv);
        return $data;
}

public function getexportarInventarioTotalExcel($fechaininvrepxls,$fechafinnvrepxls)
{
         $data = ReportesGerencia::create()->getReporteInventarioxFecha($fechaininvrepxls,$fechafinnvrepxls);
         
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar...");
        }else{
             $this->cargadataInventarioTotalExcel($data,"Inventario Total");
        }

}

private function cargadataInventarioTotalExcel($dataresp,$titulo)
{
    $this->estilosExcel();
    $objPHPExcel= new PHPExcel();
    $contfilas=1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contfilas . ':J' . $contfilas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $titulo);

    $contfilas += 2;
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, 'Serie');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, 'Número');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, 'SerieCorrelativo');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, 'Fecha cpe.');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, 'Doc Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, 'Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, 'Tel remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, 'Dirección Origen');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, 'Origen');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, 'Doc Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, 'Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, 'Dirección Destino');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, 'Tel. Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, 'Destino');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, 'Estado venta');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, 'Total Soles');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, 'P.Serie');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, 'P.Número');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, 'P.Fecha');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, 'P.Estado');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, 'Modalidad');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, 'Peso total');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, 'Manifiesto');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, 'Placa');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, 'Flota');

    $contfilas +=1;
    foreach($dataresp as $detdata)
    {
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $detdata['docserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, $detdata['docnumero']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, $detdata['Docnumeroserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, $detdata['docfecha']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, $detdata['DocRemitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, $detdata['Remitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, $detdata['TelRemitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, $detdata['direccionorigen']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, $detdata['Origen']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, $detdata['DocDestinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, $detdata['Destinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, $detdata['direcciondestino']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, $detdata['TelDestinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, $detdata['Destino']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, $detdata['estadoventa']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, $detdata['doctotal-Soles']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, $detdata['pedidoserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, $detdata['pedidonumero']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, $detdata['pedidofecha']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, $detdata['estadopedido']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, $detdata['modalidad']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, $detdata['pesototal']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, $detdata['manifiesto']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, $detdata['vehiculo_placa']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, $detdata['vehiculo_flota']);
        $contfilas +=1;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Inventario Total");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(__DIR__ . '/../../util/formatos/inventariocargoall.xls');

    return 1;
}		


public function getInventarioDetalladoAll($fechainiinvd,$fechafininvd)
{
        $data = ReportesGerencia::create()->getReporteInventarioDetallexFecha($fechainiinvd,$fechafininvd);
        return $data;
}

public function getexportarInventarioDetalladoTotalExcel($fechaininvrepxls,$fechafinnvrepxls)
{
        $data = ReportesGerencia::create()->getReporteInventarioDetallexFecha($fechaininvrepxls,$fechafinnvrepxls);
            
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar...");
        }else{
            $this->cargadataInventariodetalladoTotalExcel($data,"Inventario Detalle Paquetes");
        }
}

private function cargadataInventariodetalladoTotalExcel($dataresp,$titulo)
{
    $this->estilosExcel();
    $objPHPExcel= new PHPExcel();
    $contfilas=1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contfilas . ':J' . $contfilas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $titulo);

    $contfilas += 2;
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, 'Serie');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, 'Número');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, 'SerieCorrelativo');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, 'Fecha cpe.');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, 'Doc Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, 'Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, 'Tel remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, 'Dirección Origen');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, 'Origen');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, 'Doc Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, 'Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, 'Dirección Destino');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, 'Tel. Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, 'Destino');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, 'Estado venta');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, 'Total Soles');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, 'P.Serie');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, 'P.Número');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, 'P.Fecha');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, 'P.Estado');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, 'Modalidad');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, 'Peso total');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, 'Indice Articulo');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, 'Articulo Descripción');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, 'Tracking');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, 'F. Tracking');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, 'U.Tracking');

    $contfilas +=1;
    foreach($dataresp as $detdata)
    {
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $detdata['docserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, $detdata['docnumero']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, $detdata['Docnumeroserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, $detdata['docfecha']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, $detdata['DocRemitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, $detdata['Remitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, $detdata['TelRemitente']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, $detdata['direccionorigen']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, $detdata['Origen']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, $detdata['DocDestinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, $detdata['Destinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, $detdata['direcciondestino']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, $detdata['TelDestinatario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, $detdata['Destino']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, $detdata['estadoventa']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, $detdata['doctotal-Soles']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, $detdata['pedidoserie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, $detdata['pedidonumero']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, $detdata['pedidofecha']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, $detdata['estadopedido']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, $detdata['modalidad']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, $detdata['pesototal']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, $detdata['indicearticulo']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, $detdata['articulo_descripcion']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, $detdata['tracking_paquete']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, $detdata['fecha_tracking']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, $detdata['usuario_tracking']);
    
        $contfilas +=1;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Inventario Total");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(__DIR__ . '/../../util/formatos/inventariodetalladocargoall.xls');

    return 1;
}		


/*************************************fin de funciones***************************** */

    private function  cargadataExcel($dataresp,$titulo)
    {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Fecha creación	Tipo documento	S|N	Tipo doc. pago	S|N pago	Cliente/Proveedor	COD	Actividad	Cuenta	Total
         */

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fechac rep test');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Fecha emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo documento rep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S | N rep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo doc. pago rep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'S|N pago rep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Cliente/Proveedor  rep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'CODrep test');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Actividad rep test');
//        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Usuario');
//        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Detalle');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Cuentarep test ');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Totalrep test');

        //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($dataresp as $reporte) {
            //            $fechaCreacion = ((array) $reporte['fecha_creacion'])['date']; 
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['remitente_tipo_de_documento']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['remitente_numero_de_documento']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i,  $reporte['remitente_denominacion']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['remitente_denominacion']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i,  $reporte['destinatario_tipo_de_documento']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['destinatario_numero_de_documento']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['destinatario_denominacion']);
                        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['destinatario_direccion']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['usuario_nombre']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['descripcion']);
                        // $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['cuenta_descripcion']);
                        // $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, round($reporte['total_conversion'], 2));
            
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloInformacion);
            
                        // $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            
                        $i += 1;
                    }


        // for ($i = 'A'; $i <= 'J'; $i++) {

        //     $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        // }

        $objPHPExcel->getActiveSheet()->setTitle("reportetest01");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reportetotal01.xls');

        return 1;

    }   



 public function getReportePagosDetalleAll($fechapagoini,$fechapagofn)
 {
         $data = ReportesGerencia::create()->getReportePagosDetallexFecha($fechapagoini,$fechapagofn,0);
         return $data;
 }

 
public function getexportarReportePagosDetalleExcel($fechaininvrepxls,$fechafinnvrepxls)
{
        $data = ReportesGerencia::create()->getReportePagosDetallexFecha($fechaininvrepxls,$fechafinnvrepxls,0);
            
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar...");
        }else{
            $this->cargadataDetallePagosExcel($data,"Reporte de pagos");
        }
}

private function cargadataDetallePagosExcel($dataresp,$titulo)
{
    $this->estilosExcel();
    $objPHPExcel= new PHPExcel();
    $contfilas=1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contfilas . ':J' . $contfilas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $titulo);

    $contfilas += 2;
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, 'Serie');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, 'Numero');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, 'SerieCorrelativo');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, 'Tipo cpe.');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, 'Fecha cpe');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, 'Total cpe');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, 'Estado');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, 'Cod Pago');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, 'Importe Pago');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, 'Forma');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, 'Fecha pago');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, 'Estado pago');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, 'Usuario Creacion');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, 'Caja');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, 'Agencia');

    $contfilas +=1;
    foreach($dataresp as $detdata)
    {
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $detdata['serie']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, $detdata['numero']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, $detdata['doc']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, $detdata['tipo_cpe']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, $detdata['fecha_emision']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, $detdata['total']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, $detdata['estado']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, $detdata['codpago']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, $detdata['importe_pago']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, $detdata['forma_pago']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, $detdata['fecha_pago']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, $detdata['estadoPago']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, $detdata['usuario']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, $detdata['caja']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, $detdata['agencia']);
        
        $contfilas +=1;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Pagos Totales");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(__DIR__ . '/../../util/formatos/pagosdetallados.xls');

    return 1;
}		


    private function estilosExcel() {

        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
        );

        $this->estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTextoInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
    }

public function getPedidosRepartoDomicilioPCE($fechainipedidordpce,$fechafinpedidordpce)
{
    $data = ReportesGerencia::create()->getReportePedidoRDRDPCE($fechainipedidordpce,$fechafinpedidordpce);
        return $data;
}

//exportacion a excel
public function getPedidosRepartoDomicilioPCEEXcel($fechaininvrepxls,$fechafinnvrepxls)
{
         $data = ReportesGerencia::create()->getReportePedidoRDRDPCE($fechaininvrepxls,$fechafinnvrepxls);
         
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar...");
        }else{
             $this->cargadataReporteRepartoExcel($data,"Reporte de Repartos");
        }
}

private function cargadataReporteRepartoExcel($dataresp,$titulo)
{
    $this->estilosExcel();
    $objPHPExcel= new PHPExcel();
    $contfilas=1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contfilas . ':J' . $contfilas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $titulo);

    $contfilas += 2;
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, 'Pedido');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, 'CODCPE');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, 'CPE');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, 'CodMov');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, 'Tipo CPE');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, 'Fecha');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, 'Estado');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, 'Documento_estado_id');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, 'origen');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, 'destino');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, 'D. Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, 'Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, 'Direcci�n Remitente');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, 'Tel�fono R.');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, 'D. Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, 'Destinatario');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, 'Direcci�m');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, 'Tel�fono D.');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, 'T.Paquetes');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, 'Modalidad');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, 'manifiesto');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, 'flota_placa');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, 'flota_numero');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, 'TipoFlota');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, 'costo recojo domicilio');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, 'costo reparto');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, 'otrosgastos');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AB' . $contfilas, 'devolucion gasto');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AC' . $contfilas, 'juste precio');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AD' . $contfilas, 'subtotal');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AE' . $contfilas, 'igvcpe');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('AF' . $contfilas, 'totalcpe');

    $contfilas +=1;
    foreach($dataresp as $detdata)
    {
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $contfilas, $detdata["numero_pedido"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $contfilas, $detdata["cpeid"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $contfilas, $detdata["numero_cpe"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $contfilas, $detdata["movimiento_id"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $contfilas, $detdata["TipoComprobante"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $contfilas, $detdata["fecha_creacion"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $contfilas, $detdata["estado_pedido"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $contfilas, $detdata["documento_estado_id"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $contfilas, $detdata["origen"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $contfilas, $detdata["destino"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $contfilas, $detdata["doc_remitente"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $contfilas, $detdata["nombre_remitente"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $contfilas, $detdata["persona_direccion_origen"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $contfilas, $detdata["telefono_remitente"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $contfilas, $detdata["doc_destinatario"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $contfilas, $detdata["nombre_destinatario"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $contfilas, $detdata["persona_direccion_destino"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $contfilas, $detdata["telefono_destinatario"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $contfilas, $detdata["total_paquetes"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $contfilas, $detdata["Modalidad"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('U' . $contfilas, $detdata["manifiesto"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('V' . $contfilas, $detdata["flota_placa"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('W' . $contfilas, $detdata["flota_numero"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('X' . $contfilas, $detdata["TipoFlota"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Y' . $contfilas, $detdata["costo_recojo_domicilio"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Z' . $contfilas, $detdata["monto_costo_reparto"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AA' . $contfilas, $detdata["otrosgastos"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AB' . $contfilas, $detdata["monto_devolucion_gasto"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AC' . $contfilas, $detdata["ajuste_precio"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AD' . $contfilas, $detdata["subtotal"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AE' . $contfilas, $detdata["igvcpe"]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('AF' . $contfilas, $detdata["totalcpe"]);
        $contfilas +=1;
    }

    $objPHPExcel->getActiveSheet()->setTitle("Reporte de Repartos Total");
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(__DIR__ . '/../../util/formatos/ReporteRepartos.xls');

    return 1;
}		



}