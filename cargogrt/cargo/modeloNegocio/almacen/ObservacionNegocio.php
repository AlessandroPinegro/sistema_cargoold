<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modelo/almacen/Observacion.php';
class ObservacionNegocio  extends ModeloNegocioBase {
  private $estiloTituloReporte, $estiloTituloColumnas, $estiloInformacion;

public function getPedidosEnviarManciche($numPedidos,  $usuarioId){
    $data = new stdClass();
    for ($j=0; $j < count($numPedidos) ; $j++) { 
        $data -> enviarPedidosManciche = Observacion::create()->obtenerPedidosEnviadosManciche($numPedidos[$j]['documentoId'], $usuarioId);
    }
    return $data;

}
  public function reversarPedido($documento_id){
    $data = new stdClass();
    $data->reversarPedido = Observacion::create()->getReversarPedidoObservado( $documento_id);
    return $data;
}
 public function getMostrarDetalle($documento_id){
    $data = new stdClass();
    $data->detalledepedido = Observacion::create()->ObtenerDetalleDocumentoObservado( $documento_id);
    return $data;
 }
     public  function getPedidosObservados( $fInicio, $fFinal ){
        $data = new stdClass();
        $data->pedidosObservados = Observacion::create()->getPedidosObservados(  $fInicio, $fFinal);
        return $data;
     }
      public function obtenerEstadoObservacion(){
        $data = new stdClass();
        $data->ObtenerEstadoObservacion = Observacion::create()->obtenerPedidosObservados();
        return $data;
      }
       public function cambiarEstadoDocumentoObservacion ($id_documento, $estado_observacion_id, $observacion, $usuarioId ){
        $data = new stdClass();
        $data->ObtenerEstadoObservacion = Observacion::create()->cambiarEstadoDocumentoObservacion($id_documento, $estado_observacion_id, $observacion, $usuarioId );
        return $data;
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
      

    public function    getPedidosObservadosExportarExcel($fInicio, $fFinal){
      $respuesta = $this->getPedidosObservados($fInicio, $fFinal);
      if (ObjectUtil::isEmpty($respuesta)) {
        throw new WarningException("No existe datos para exportar");
    } else {
        $this->crearReporteCObservacion($respuesta, "Reporte de Observaciones");
    }
       }
 public function crearReporteCObservacion($reportes, $titulo){
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Fecha creación	Tipo documento	S|N	Tipo doc. pago	S|N pago	Cliente/Proveedor	COD	Actividad	Cuenta	Total
         */

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Pedido');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F.Pedido');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'F. Observacion');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'persona_nombre');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'agencia_destino');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'total');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'comprobante_serie_numero');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'usuario_creacion');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'estado');
        
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

         for ($j=0; $j < count($reportes->pedidosObservados) ; $j++) { 
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reportes->pedidosObservados[$j]['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reportes->pedidosObservados[$j]['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reportes->pedidosObservados[$j]['fecha_observacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reportes->pedidosObservados[$j]['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reportes->pedidosObservados[$j]['agencia_destino']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reportes->pedidosObservados[$j]['total']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reportes->pedidosObservados[$j]['comprobante_serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reportes->pedidosObservados[$j]['usuario_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reportes->pedidosObservados[$j]['estado']);

          $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);
         $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
         }

        for ($i = 'A'; $i <= 'I'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;

 }     
 
}