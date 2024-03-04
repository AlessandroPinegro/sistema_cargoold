<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';

class ExcelNegocio extends ModeloNegocioBase {
        
    public function guardarExcelMovimientosErrores($opcionId) {
        global $objPHPExcel,$objWorkSheet,$i, $j,$h,$documentoTipoIdAnterior;
        
        if($objPHPExcel==null){
            $objPHPExcel = new PHPExcel();
        }
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/formato_movimientos.xlsx');
        
        $objPHPExcel=null;$objWorkSheet=null;$i=0;$j=0;$h=null;$documentoTipoIdAnterior=null;
    }
    
    public function generarExcelMovimientosErrores($opcionId,$documentoTipoId, $dataDocumento,$detalle,$mensajeError) {
        
        global $objPHPExcel,$objWorkSheet,$i, $j,$h,$documentoTipoIdAnterior;
        
        $documentoTipoIdAntes=$documentoTipoIdAnterior;
        $documentoTipoIdAnterior=$documentoTipoId;
        
        $data = MovimientoNegocio::create()->obtenerDocumentosXCriteriosExcel($opcionId, $criterios);        
        $titulo = $data[0]['nombre_opcion'].' Errores';              
                
        //obtnemos el id del tipo de movimiento
        $movimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);        
        $movimientoTipoDocumentoTipo = Movimiento::create()->ObtenerMovimientoTipoDocumentoTipoPorMovimientoTipoID($movimientoTipo[0]['id']);       
        if($h == null){
            $this->estilosExcel();
            $objPHPExcel = new PHPExcel();

            $h = 0;        
        }
                
        //while ($h < 5) {
        foreach ($movimientoTipoDocumentoTipo as $documentoTipo) {
            if($documentoTipo["documento_tipo_id"]==$documentoTipoId){
            // Add new sheet
                if($documentoTipoIdAntes!=$documentoTipoId){
                    $objWorkSheet = $objPHPExcel->createSheet($h); //Setting index when creating

                    //Write cells

                    $i = 1;
                    //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':O' . $i);
                    $objWorkSheet->setCellValue('A' . $i, $titulo);
                    //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);
                    $i +=2;
                    $objWorkSheet->setCellValue('A' . $i, 'RUC');
                    $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);  
                    $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_ruc']);
                    $objWorkSheet->getStyleByColumnAndRow(1 , $i)->applyFromArray($this->estiloDetCabecera);  
                    $i +=1;
                    $objWorkSheet->setCellValue('A' . $i, 'Empresa');
                    $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);
                    $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_razon_social']);

                    $i +=2;

                    $j =0;

                    // dinamico


                    foreach ($data as $fila) {
                        if($documentoTipo['documento_tipo_id']==$fila['documento_tipo_id']){                    
                            //$dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);

                            foreach ($dataDocumento as $filaData) {
                                $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['descripcion']);
                                $j +=1;
                            }
                            break;
                        }                             
                    }

                    // fin

                    //detalle documento
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Organizador');$j +=1;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Cantidad');$j +=1;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Unidad Medida');$j +=1;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Bien');$j +=1;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Precio Unitario');$j +=1;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Total Detalle');$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Errores');$j +=1;  

                    $ultimaColumna=$objWorkSheet->getHighestColumn();        
                    $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTituloColumnas);

                    //Titulo
                    $objWorkSheet->getStyle('A1' . ':'.$ultimaColumna. '1')->applyFromArray($this->estiloTituloReporte);
                    $objWorkSheet->mergeCells('A1' . ':'.$ultimaColumna. '1');

                    //$objPHPExcel->stringFromColumnIndex($colIndex);
                            //stringFromColumnIndex($colIndex);                

                    $i +=1;
                    $h+=1;
                }

                $ultimaColumna=$objWorkSheet->getHighestColumn();  
                $data=$detalle;
                foreach ($data as $fila) {


                    //if($movimientoTipoDocumentoTipo[$h]['documento_tipo_id']==$fila['documento_tipo_id']){       

                        //Estilo detalle
                        $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTxtInformacion);

                        $j =0;

                        // dinamico
                        //$dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);

                        foreach ($dataDocumento as $filaData) {
                            $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['valorExcel']);
                            if($filaData['tipo']>=14 and $filaData['tipo']<=16){
                                $objWorkSheet->getStyleByColumnAndRow($j , $i)->applyFromArray($this->estiloNumInformacion);  
                            }
                            $j +=1;
                        }
                        // fin

                        //detalle documento 
                        /*array(organizadorId=>$organizador_id, bienId=>$bien_id, 
                                cantidad=>$cantidad, unidadMedidaId=>$unidadMedida_id, precio=>$precioUnitario,
                                organizadorDesc=>$organizador, bienDesc=>$bien, unidadMedidaDesc=>$unidadMedida,
                                subTotal=>$totalDetalle)); */

                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['organizadorDesc']);$j +=1;  
                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['cantidad']);$j +=1;  
                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['unidadMedidaDesc']);$j +=1;  
                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['bienDesc']);$j +=1;  
                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['precio']);
                        $objWorkSheet->getStyleByColumnAndRow($j , $i)->applyFromArray($this->estiloNumInformacion);$j +=1;  

                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['subTotal']);
                        $objWorkSheet->getStyleByColumnAndRow($j , $i)->applyFromArray($this->estiloNumInformacion);$j +=1;  
                        $objWorkSheet->setCellValueByColumnAndRow( $j , $i , $mensajeError);$j +=1;  


                        $i +=1;

                    //}           
                }


                for ($colum = 'A'; $colum <= $ultimaColumna; $colum++) {
                    $objWorkSheet->getColumnDimension($colum)->setAutoSize(TRUE);
                }
                $x = $colum;
                for ($a = 1; $a <= $x; $a++) {
                    $objWorkSheet->getRowDimension($colum)->setRowHeight(-1);
                }
                $objWorkSheet->setTitle($documentoTipo[documentoTipoDescripcion]);
                $objPHPExcel->setActiveSheetIndex(0);

            }
            // Rename sheet
            //$objWorkSheet->setTitle("$h");
            //$h+=1;
        }    
        
        /*if($guardar==TRUE){
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(__DIR__ . '/../../util/formatos/formato_movimientos.xlsx');
        }*/
        return 1;
    }



    public function generarReporte($opcionId, $criterios) {
        //return ExcelNegocio::create()->generarReporte($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);   
        
        $data = MovimientoNegocio::create()->obtenerDocumentosXCriteriosExcel($opcionId, $criterios);        
        //$response_cantidad_total = MovimientoNegocio::create()->obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        
        //$data = MovimientoNegocio::create()->generarExcelNegocio('2015', '10', 'MOVIMIENTOS', 'NLEON');
        $titulo = 'REPORTE DE MOVIMIENTOS';               

        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar");
        } else {
            $this->crearReporteMovimientos($opcionId,$data, $data[0]['nombre_opcion']);
        }
    }
    
    public function generarFormatoMovimientos($opcionId, $criterios) {                
        $data = MovimientoNegocio::create()->obtenerDocumentosXCriteriosExcel($opcionId, $criterios);        
        $titulo = 'MOVIMIENTOS';              

        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar");
        } else {
            $this->crearFormatoMovimientos($opcionId,$data, $data[0]['nombre_opcion']);
        }
    }
    
    private function crearFormatoMovimientos($opcionId,$data, $titulo) {
                
        //obtnemos el id del tipo de movimiento
        $movimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);        
        $movimientoTipoDocumentoTipo = Movimiento::create()->ObtenerMovimientoTipoDocumentoTipoPorMovimientoTipoID($movimientoTipo[0]['id']);       
        
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();
        
        $h = 0;
                
        //while ($h < 5) {
        foreach ($movimientoTipoDocumentoTipo as $documentoTipo) {
            // Add new sheet
            $objWorkSheet = $objPHPExcel->createSheet($h); //Setting index when creating
            
            //Write cells
            
            $i = 1;
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':O' . $i);
            $objWorkSheet->setCellValue('A' . $i, $titulo);
            //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);
            $i +=2;
            $objWorkSheet->setCellValue('A' . $i, 'RUC');
            $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);  
            $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_ruc']);
            $objWorkSheet->getStyleByColumnAndRow(1 , $i)->applyFromArray($this->estiloDetCabecera);  
            $i +=1;
            $objWorkSheet->setCellValue('A' . $i, 'Empresa');
            $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);
            $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_razon_social']);

            $i +=2;

            $j =0;         

            // dinamico
            
                      
            foreach ($data as $fila) {
                if($movimientoTipoDocumentoTipo[$h]['documento_tipo_id']==$fila['documento_tipo_id']){                    
                    $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);
                    
                    foreach ($dataDocumento as $filaData) {
                        if($filaData['tipo']==DocumentoTipoNegocio::DATO_PERSONA){
                            //$filaData['descripcion']=$filaData['descripcion'].'(apellidos, nombres)';
                            $objWorkSheet->setCellValueByColumnAndRow($j, $i-1, '(apellidos, nombres)');
                        }
                        
                        $posicion_coincidencia = strpos($filaData['descripcion'], 'Fecha');
                                                
                        if($posicion_coincidencia !== FALSE){
                            //$filaData['descripcion']=$filaData['descripcion'].'(dia/mes/año)';
                            $objWorkSheet->setCellValueByColumnAndRow($j, $i-1, '(dia/mes/año)');
                        }
                        
                        //if($filaData['descripcion'])
                        
                        $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['descripcion']);
                        $j +=1;
                    }
                    break;
                }                             
            }
            
            // fin

            //detalle documento
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Organizador');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Cantidad');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Unidad Medida');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Bien');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Precio Unitario');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Total Detalle');$j +=1;  

            $ultimaColumna=$objWorkSheet->getHighestColumn();        
            $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTituloColumnas);

            //Titulo
            $objWorkSheet->getStyle('A1' . ':'.$ultimaColumna. '1')->applyFromArray($this->estiloTituloReporte);
            $objWorkSheet->mergeCells('A1' . ':'.$ultimaColumna. '1');    
            
            for ($i = 'A'; $i <= $ultimaColumna; $i++) {
                $objWorkSheet->getColumnDimension($i)->setAutoSize(TRUE);
            }
            $x = $i;
            for ($a = 1; $a <= $x; $a++) {
                $objWorkSheet->getRowDimension($i)->setRowHeight(-1);
            }
            $objWorkSheet->setTitle($documentoTipo[documentoTipoDescripcion]);
            $objPHPExcel->setActiveSheetIndex(0);
            
            // Rename sheet
            //$objWorkSheet->setTitle("$h");
            $h+=1;
        }    
            
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/formato_movimientos.xlsx');
        return 1;
    }

    private function crearReporteMovimientos($opcionId,$data, $titulo) {
                
        //obtnemos el id del tipo de movimiento
        $movimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);        
        $movimientoTipoDocumentoTipo = Movimiento::create()->ObtenerMovimientoTipoDocumentoTipoPorMovimientoTipoID($movimientoTipo[0]['id']);       
        
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();
        
        //First sheet
        //$sheet = $objPHPExcel->getActiveSheet();
        
        //Start adding next sheets
        $h = 0;
                
        //while ($h < 5) {
        foreach ($movimientoTipoDocumentoTipo as $documentoTipo) {
            // Add new sheet
            $objWorkSheet = $objPHPExcel->createSheet($h); //Setting index when creating
            
            //Write cells
            
            $i = 1;
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':O' . $i);
            $objWorkSheet->setCellValue('A' . $i, $titulo);
            //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);
            $i +=2;
            $objWorkSheet->setCellValue('A' . $i, 'RUC');
            $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);  
            $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_ruc']);
            $objWorkSheet->getStyleByColumnAndRow(1 , $i)->applyFromArray($this->estiloDetCabecera);  
            $i +=1;
            $objWorkSheet->setCellValue('A' . $i, 'Empresa');
            $objWorkSheet->getStyleByColumnAndRow(0 , $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);
            $objWorkSheet->setCellValue('B' . $i, $data[0]['empresa_razon_social']);

            $i +=2;

            $j =0;
            //$objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. CREACION');
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'F. Creación');$j +=1;//strtoupper($str);
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $j , $i ,  'Documento Tipo');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Estado');$j +=1;
            //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $j , $i ,  'MOVIMIENTO TIPO');$j +=1;
            // dinamico 
            foreach ($data as $fila) {
                if($movimientoTipoDocumentoTipo[$h]['documento_tipo_id']==$fila['documento_tipo_id']){                    
                    $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);                    
                    foreach ($dataDocumento as $filaData) {
                        $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['descripcion']);
                        $j +=1;
                        
                    }
                    break;
                }                             
            }
            
            // fin
            //detalle documento
            if(!ObjectUtil::isEmpty($movimientoTipo[0]['id']) && $movimientoTipo[0]['id']==12){
                $objWorkSheet->setCellValueByColumnAndRow( $j , $i , 'Pago Neto');$j +=1; 
                $objWorkSheet->setCellValueByColumnAndRow( $j , $i , 'Pago Retencion 3%');$j +=1; 
            }
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Organizador');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Cantidad');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Unidad Medida');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Bien');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Código Contable');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Moneda');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Precio Unitario');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Total Detalle');
            
//            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Importe Retencion');$j +=1;  
            

            $ultimaColumna=$objWorkSheet->getHighestColumn();        
            $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTituloColumnas);

            //Titulo
            $objWorkSheet->getStyle('A1' . ':'.$ultimaColumna. '1')->applyFromArray($this->estiloTituloReporte);
            $objWorkSheet->mergeCells('A1' . ':'.$ultimaColumna. '1');

            //$objPHPExcel->stringFromColumnIndex($colIndex);
                    //stringFromColumnIndex($colIndex);

            $i +=1;
            
            foreach ($data as $fila) {
                
                
                if($movimientoTipoDocumentoTipo[$h]['documento_tipo_id']==$fila['documento_tipo_id']){       
                    
                    //Estilo detalle
                    $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTxtInformacion);

                    $j =0;
                    //$objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $fila['documento_tipo_descripcion']);
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['fecha_creacion']);$j +=1; 
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $j , $i ,  $fila['documento_tipo_descripcion']);$j +=1; 
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['documento_estado_descripcion']);$j +=1; 
                    //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $j , $i ,  $fila['movimiento_tipo_descripcion']);$j +=1;  

                    // dinamico
                    $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);

                    foreach ($dataDocumento as $filaData) {
                        $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['valor']);
                        if($filaData['tipo']>=14 and $filaData['tipo']<=16){
                            $objWorkSheet->getStyleByColumnAndRow($j , $i)->applyFromArray($this->estiloNumInformacion);  
                        }
                        $j +=1;
                    }
                    // fin

                    //detalle documento 
                    if(!ObjectUtil::isEmpty($movimientoTipo[0]['id']) && $movimientoTipo[0]['id']==12){
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['pago_neto']);$j +=1;
                    $objWorkSheet->getStyleByColumnAndRow(($j-1) , $i)->applyFromArray($this->estiloNumInformacion);
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['valor_retencion']);$j +=1;
                    $objWorkSheet->getStyleByColumnAndRow(($j-1) , $i)->applyFromArray($this->estiloNumInformacion);
                    
                    }
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['organizador_descripcion']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['cantidad']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['unidad_medida_descripcion']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['bien_descripcion']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['codigo_contable']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['moneda_simbolo']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['valor_monetario']);$j +=1; 
                    $objWorkSheet->getStyleByColumnAndRow(($j-1) , $i)->applyFromArray($this->estiloNumInformacion);
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['cantidad']*$fila['valor_monetario']);$j +=1;  
                    $objWorkSheet->getStyleByColumnAndRow(($j-1) , $i)->applyFromArray($this->estiloNumInformacion); 


                    $i +=1;
                    
                }           
            }


            for ($i = 'A'; $i <= $ultimaColumna; $i++) {
                $objWorkSheet->getColumnDimension($i)->setAutoSize(TRUE);
            }
            $x = $i;
            for ($a = 1; $a <= $x; $a++) {
                $objWorkSheet->getRowDimension($i)->setRowHeight(-1);
            }
            $objWorkSheet->setTitle($documentoTipo[documentoTipoDescripcion]);
            $objPHPExcel->setActiveSheetIndex(0);
            
            // Rename sheet
            //$objWorkSheet->setTitle("$h");
            $h+=1;
        }    
            
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/Reporte_Movimientos.xlsx');
        return 1;
    }  

    private function estilosExcel() {
        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
        
        $this->estiloTextoIzquierdaNegrita = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 9
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloDataCabecera = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 9
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloDetCabecera = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => false,
                'size' => 9
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 9
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

        $this->estiloTxtInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
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

        $this->estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
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
        
        $this->estiloTextoAzulIzquierdaSubRayado = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => false,
                'italic' => false,
                'strike' => false,
                'size' => 9,
                'color' => array('rgb' => '0000FF'),
                'underline' => 'single'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTxtInformacionIzquierda = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 9
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
        
        $this->estiloInformacionNegritaConCelda = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => true
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
        
        $this->estiloInformacionCentro = array(
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
    }
    
    public function generarReporteOperacion($opcionId, $data,$documentoTipoData) {
        $titulo="Reporte de Operaciones";
                          
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();        
        
        $h = 0;
                
        foreach ($documentoTipoData as $documentoTipo) {
            // Add new sheet
            $objWorkSheet = $objPHPExcel->createSheet($h); //Setting index when creating
            
            //Write cells            
            $i = 1;
            //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':O' . $i);
            $objWorkSheet->setCellValue('A' . $i, $titulo);
            //$objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);
          
            $i +=2;
            $j =0;
            //$objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. CREACION');
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'F. Creación');$j +=1;//strtoupper($str);
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Estado');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Descripción');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Comentario');$j +=1;
            $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  'Moneda');$j +=1;

            // dinamico  
            foreach ($data as $fila) {
                if($documentoTipoData[$h]['id']==$fila['documento_tipo_id']){                    
                    $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);
                    
                    foreach ($dataDocumento as $filaData) {
                        $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['descripcion']);
                        $j +=1;
                    }
                    break;
                }                             
            }            
            // fin           
            
            //Titulos
            $ultimaColumna=$objWorkSheet->getHighestColumn();        
            $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTituloColumnas);            
            $objWorkSheet->getStyle('A1' . ':'.$ultimaColumna. '1')->applyFromArray($this->estiloTituloReporte);
            $objWorkSheet->mergeCells('A1' . ':'.$ultimaColumna. '1');

            //$objPHPExcel->stringFromColumnIndex($colIndex);
                    //stringFromColumnIndex($colIndex);

            $i +=1;
            
            foreach ($data as $fila) {        
                if($documentoTipoData[$h]['id']==$fila['documento_tipo_id']){             
                    //Estilo detalle
                    $objWorkSheet->getStyle('A' . $i . ':'.$ultimaColumna. $i)->applyFromArray($this->estiloTxtInformacion);
                    
                    $j =0;
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['fecha_creacion']);$j +=1; 
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['documento_estado_descripcion']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['descripcion']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['comentario']);$j +=1;  
                    $objWorkSheet->setCellValueByColumnAndRow( $j , $i ,  $fila['moneda_descripcion']);$j +=1;  

                    // dinamico
                    $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($fila['documento_id']);

                    foreach ($dataDocumento as $filaData) {
                        $objWorkSheet->setCellValueByColumnAndRow($j, $i, $filaData['valor']);
                        if($filaData['tipo']>=14 and $filaData['tipo']<=16){
                            $objWorkSheet->getStyleByColumnAndRow($j , $i)->applyFromArray($this->estiloNumInformacion);  
                        }
                        $j +=1;
                    }
                    // fin
                    $i +=1;                    
                }           
            }

            for ($i = 'A'; $i <= $ultimaColumna; $i++) {
                $objWorkSheet->getColumnDimension($i)->setAutoSize(TRUE);
            }
            $x = $i;
            for ($a = 1; $a <= $x; $a++) {
                $objWorkSheet->getRowDimension($i)->setRowHeight(-1);
            }
            $objWorkSheet->setTitle($documentoTipo['descripcion']);
            $objPHPExcel->setActiveSheetIndex(0);
            
            // Rename sheet
            //$objWorkSheet->setTitle("$h");
            $h+=1;
        }    
            
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/Reporte_Operaciones.xlsx');
        return 1;
    }
    
    
    public function generarExcelInvPermValorizado($data,$anio, $mes){
        
        //OBTENER BIENES DIFERENTES
        $bienIdArray=array();
        foreach ($data as $item){
            array_push($bienIdArray, $item['bien_id']);
        }
        
        $bienIdArray = array_unique($bienIdArray);
        
        //PARA OBTENER LOS INDICES CORRELATIVOS EL ARRAY UNIQUE LOS ELIMINO
        $bienIds=array();
        foreach ($bienIdArray as $item){
            array_push($bienIds, $item);
        }
        
        //AGRUPO LOS BIENES
        $dataKardexBien=$bienIds;
        foreach ($bienIds as $index=>$bienId){
            $dataKardexBien[$index]=array();
            foreach ($data as $item){
                if($bienId==$item['bien_id']){
                    array_push($dataKardexBien[$index], $item);
                }
            }
        }
                
        //ARRAY DE MESES
        $mesesNombre = array(
            "01" => "Enero",
            "02" => "Febrero",
            "03" => "Marzo",
            "04" => "Abril",
            "05" => "Mayo",
            "06" => "Junio",
            "07" => "Julio",
            "08" => "Agosto",
            "09" => "Septiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre");
        
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':N' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'REGISTRO DE INVENTARIO PERMANENTE VALORIZADO');
        $sheet=$objPHPExcel->getActiveSheet();
        $sheet->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->estiloTituloReporte);
        
        //DATOS DE CABECERA POR PRODUCTO
//        Cliente G.P. Principal G.P. Secundario F. Emisión	Tipo documento	S|N	Total S/.	Total $
        //BUCLE PARA CADA PRODUCTO
        

        //ANCHOS DE COLUMNAS        
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(7.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(11.71);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(9.86);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(11.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(11);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(11.14);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(9.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(11.29);

        //ARRAY QUE ALMACENA LOS NUMERO DE FILAS DE TITULOS DE COLUMNAS
        $arrayInd = array();
        $i++;
        foreach ($dataKardexBien as $dataItem){              
            
            $i +=2;
            
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Periodo:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $mesesNombre[$mes].' - '.$anio);
            $sheet->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;

            $dataEmpresa=  EmpresaNegocio::create()->obtenerEmpresaXId(2);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'RUC:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataEmpresa[0]['ruc']);
            $sheet->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Razon Social:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataEmpresa[0]['razon_social']);
            $sheet->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;

            $i++;

            $dataBienCab=$dataItem[0];

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Codigo:');
            $sheet->getStyle('A' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataBienCab['cuenta_codigo']);
            $sheet->getStyle('C' . $i)->applyFromArray($this->estiloTxtInformacionIzquierda);$i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Tipo:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, '01 - Mercaderia');
            $sheet->getStyle('A' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Descripcion:');
            $sheet->getStyle('A' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataBienCab['bien_descripcion']);
            $sheet->getStyle('C' . $i)->applyFromArray($this->estiloTextoAzulIzquierdaSubRayado);$i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Unidad de medida:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataBienCab['unidad_medida_descripcion']);
            $sheet->getStyle('A' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Metodo de valuación:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Promedio Ponderado');
            $sheet->getStyle('A' . $i)->applyFromArray($this->estiloTextoIzquierdaNegrita);$i++;

            $i +=1;

            //TITULO DE COLUMNAS DEL DETALLE 
            //PRIMER TITULO        
            $filaInicio=$i;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $i . ':E' . ($i+1));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'TIPO DE OPERACION');        
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $i . ':H' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'ENTRADAS');        
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I' . $i . ':K' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'SALIDAS');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L' . $i . ':N' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'SALDO FINAL');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('O' . $i . ':P' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'C.C.');

            $sheet->getStyle('A' . $i . ':P' . $i)->applyFromArray($this->estiloTituloColumnas);

            //SEGUNDO TITULO
            $i++;
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'FECHA');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'TIPO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'SERIE');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'NUMERO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'CANT.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'COSTO UNITARIO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'TOTAL');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'CANT.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'COSTO UNITARIO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'TOTAL');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'CANT.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'COSTO UNITARIO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'TOTAL');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'SUB LOTE');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'LOTE');

            $sheet->getStyle('A' . $i . ':P' . $i)->applyFromArray($this->estiloTituloColumnas);

            //GUARDO EL NUMERO DE FILA DEL TITULO
            array_push($arrayInd, $i);

            //AJUSTAR TEXTO
            $sheet->getStyle('A'.$filaInicio.':P'.$i)->getAlignment()->setWrapText(true); 

            $i++;
            $cantTotalEnt=0;
            $cantTotalSal=0;
            $totalEnt=0;
            $totalSal=0;
            foreach ($dataItem as $item) {
                /*DOCUMENTO DE TRASLADO, COMPROBANTE DE PAGO, DOCUMENTO INTERNO O SIMILAR |	TIPO DE OPERACION |ENTRADAS |SALIDAS	|SALDO FINAL		
    FECHA	TIPO	SERIE	NUMERO	TIPO DE OPERACION	CANT.	COSTO UNITARIO	TOTAL	CANT.	COSTO UNITARIO	TOTAL	CANT.	COSTO UNITARIO	TOTAL
    */
                $cantTotalEnt+=$item['cantidad_entrada'];
                $cantTotalSal+=$item['cantidad_salida'];
                $totalEnt+=$item['costo_entrada'];
                $totalSal+=$item['costo_salida'];
                
                $sheet->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacionCentro);
                $sheet->getStyle('F' . $i . ':P' . $i)->applyFromArray($this->estiloInformacion);
                
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, DateUtil::formatearBDACadena( $item['fecha']));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $item['documento_tipo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $item['serie']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $item['numero']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $item['operacion_tipo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $item['cantidad_entrada']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $item['costo_unitario_entrada']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $item['costo_entrada']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $item['cantidad_salida']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $item['costo_unitario_salida']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $item['costo_salida']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, $item['cantidad_final']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, $item['costo_unitario_final']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, $item['costo_final']*1);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, $item['nuevo_cuo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, $item['nuevo_correlativo']);
                //CANTIDAD ENTERO
                $sheet->getStyle('F' . $i)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                $sheet->getStyle('L' . $i)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                //_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)
                //PRECIOS DECIMALES
                $sheet->getStyle('G' . $i.':'.'H'.$i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $sheet->getStyle('J' . $i.':'.'K'.$i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $sheet->getStyle('M' . $i.':'.'N'.$i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');//#,##0.00
                //_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-
                $i +=1;
            }
            //PARA MOSTRAR TOTALES
                $sheet->getStyle('A' . $i . ':N' . $i)->applyFromArray($this->estiloInformacionNegritaConCelda);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'TOTALES');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $cantTotalEnt);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $totalEnt);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $cantTotalSal);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $totalSal);
                //CANTIDAD ENTERO
                $sheet->getStyle('F' . $i)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
                //PRECIOS
                $sheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode('_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)');
                $i++;
        }
        
        for ($i = 'A'; $i <= 'N'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(false);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }        
        
        //ALTO FILAS
        foreach ($arrayInd as $filaTitulo){
            $sheet->getRowDimension($filaTitulo-1)->setRowHeight(23);
            $sheet->getRowDimension($filaTitulo)->setRowHeight(23);
        }

        $sheet->setTitle('kardex_'.$anio.'_'.$mes);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        $fechaActual = new DateTime();
        $formatoFechaActual = $fechaActual->format("Ymdhis");        
        
        $archivoNombre = "$anio$mes_$formatoFechaActual.xls";
        $direccion = __DIR__."/../../util/uploads/$archivoNombre";
        
        $objWriter->save($direccion);
        
        $resultado->url=$direccion;
        $resultado->nombre=$archivoNombre;

        return $resultado;
    }
    
    public function generarExcelInvPermValorizadoResumen($data,$anio){
                        
        //ARRAY DE MESES
        $mesesNombre = array(
            "01" => "Enero",
            "02" => "Febrero",
            "03" => "Marzo",
            "04" => "Abril",
            "05" => "Mayo",
            "06" => "Junio",
            "07" => "Julio",
            "08" => "Agosto",
            "09" => "Septiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre");
        
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();
        $sheet=$objPHPExcel->getActiveSheet();
        
        $i = 7;
        
        $i++;
        
            
        $meses=count($data['0']['meses']);
        
        //ANCHOS DE COLUMNAS        
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(11.7);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(5.7);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(8.9);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(26.6);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(12.14);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(34.29);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(5);
        
        //----------------------------- ENTRADAS ----------------------------------------------
        //TITULO DE COLUMNAS DEL DETALLE 
        //PRIMER TITULO        
        //ITEM	CUENTA	NAME OF COMMODITY	SPECIFICATION	PRODUCTOS	U.M.

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':B' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'ITEM');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $i . ':C' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'CUENTA');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $i . ':D' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'NAME OF COMMODITY');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $i . ':E' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'SPECIFICATION');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $i . ':F' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'PRODUCTOS');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $i . ':G' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'U.M.');               

        //TITULO DINAMICO
        $j=7;
        for($iMes=0;$iMes<$meses;$iMes++){                                
            $mesId=(($iMes+1) < 10 ? ('0' . ($iMes+1)) : ($iMes+1));            
            
            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  strtoupper($mesesNombre[$mesId]));$j +=2; 
        }

            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'TOTALES');$j +=2; 
            
            //COLUMNA EN BLANCO
            $indiceColumnaBlanca=$j;
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  '');$j +=1; 
                        
            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'SALDOS INICIALES');$j +=2; 
            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'TOTALES');$j +=2; 

        $ultimaColumna = $objPHPExcel->setActiveSheetIndex()->getHighestColumn();
        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_HAIR, '92cddc'));
        $sheet->getStyleByColumnAndRow($indiceColumnaBlanca , $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_NONE, 'FFFFFF'));
        
        //SEGUNDO TITULO
        $i++;
        $j=7;
        //DINAMICO
        for($iMes=0;$iMes<($meses+1);$iMes++){      
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(9);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'CANT');$j +=1; 
            
            $anchoTotales=0;
            if($iMes==$meses){
                $anchoTotales=4;
            }
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(12.14+$anchoTotales);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'IMPORTE');$j +=1; 
        }
        
            //PARA COLUMNA EN BLANCA
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(3);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  '');$j +=1; 
            
            //PARA SALDOS INICIALES
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(9);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'CANT');$j +=1; 
            
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(16.14);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'IMPORTE');$j +=1; 
            
            //PARA TOTALES
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(9);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'CANT');$j +=1; 
            
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(16.14);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'IMPORTE');$j +=1; 
        

        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_HAIR, '92cddc'));
        $sheet->getStyleByColumnAndRow($indiceColumnaBlanca , $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_NONE, 'FFFFFF'));

        //AJUSTAR TEXTO
        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->getAlignment()->setWrapText(true);

        $i++;
        //PARA OBTENER TOTALES POR COLUMNAS
        $totalImpMeses=array();
        for($iMes=0;$iMes<$meses;$iMes++){   
            array_push($totalImpMeses, array('totalImp'=>0));
        }
        
        $totalImpTotal=0;//PARA OBTENER EL TOTAL DEL TOTAL DE MESES
        $totalImpSaldoInicial=0;//PARA OBTENER EL TOTAL DE SALDOS INICIALES
        
        foreach ($data as $index=> $item) {
            //ITEM	CUENTA	NAME OF COMMODITY	SPECIFICATION	PRODUCTOS

            $dataBien=$item['bien'];
            //VALIDAMOS SI ES TIPO 16 - SALDO INICIAL
            if($dataBien['operacion_tipo']*1!=16){
                $dataBien['cantidad_final']=0;
                $dataBien['costo_unitario_final']=0;
                $dataBien['costo_final']=0;
            }
            
            $dataMeses=$item['meses'];
            $totalesBien=$item['totales'];
//
//            $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, ($index+1));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataBien['cuenta_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $dataBien['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Unid.');

            $j=7;
            $cantTotalEntProd=$totalesBien['cantTotalEntProd'];
            $impTotalEntProd=$totalesBien['impTotalEntProd'];
            foreach ($dataMeses as $indexMes => $itemMes){                      
                $totalImpMeses[$indexMes]['totalImp']+=$itemMes['impTotalEnt'];
                
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['cantTotalEnt']);$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['impTotalEnt']);$j +=1; 
            }
                
                //TOTALES POR FILA - PRODUCTOS
                $totalImpTotal+=$impTotalEntProd;
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $cantTotalEntProd);$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $impTotalEntProd);$j +=1;            

                $j +=1; //POR LA COLUMNA EN BLANCA
                
                //SALDOS INICIALES
                $totalImpSaldoInicial+=$dataBien['costo_final'];
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $dataBien['cantidad_final']);$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $dataBien['costo_final']);$j +=1;            
                
                //TOTALES DESPUES DE SALDOS INICIALES
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  ($cantTotalEntProd+$dataBien['cantidad_final']));$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  ($impTotalEntProd+$dataBien['costo_final']));$j +=1; 
                
            $i +=1;
        }
        
        //-------------- PARA MOSTRAR LOS TOTALES POR COLUMNA  - MESES --------------------------
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'TOTALES');
        
        $j=7;
        foreach ($totalImpMeses as $indexMes => $itemMes){      
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['totalImp']);$j +=1; 
        }

        //TOTALES POR FILA - PRODUCTOS
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $totalImpTotal);$j +=1;            

        $j +=1; //POR LA COLUMNA EN BLANCA

        //SALDOS INICIALES
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $totalImpSaldoInicial);$j +=1;            

        //TOTALES DESPUES DE SALDOS INICIALES
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  ($totalImpTotal+$totalImpSaldoInicial));$j +=1; 
        
        $i+=1;
        //------------------- ESTILOS PARA EL DETALLE -----------------------------
        $sheet->getStyle('B10:' . $ultimaColumna . ($i-2))->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','9'));        
        $sheet->getStyle('F'.($i-1).':' . $ultimaColumna . ($i-1))->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','11','hair',true));
        //---------------- ESTILOS PARA NUMEROS --------------------------------
        
        $j=7;
        //POR MESES
        foreach ($dataMeses as $itemMes){      
            $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1;                                
            $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        }
        
        //TOTALES POR FILA - PRODUCTOS
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1;
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1;            

        //LA COLUMNA EN BLANCA        
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','9',PHPExcel_Style_Border::BORDER_NONE));$j +=1; 

        //SALDOS INICIALES
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1;            

        //TOTALES DESPUES DE SALDOS INICIALES
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        
        $i+=4;
        $inicioDetalleSalida=$i+2;
        //----------------------------- SALIDAS ----------------------------------------------
        //TITULO DE COLUMNAS DEL DETALLE 
        //PRIMER TITULO        
        //ITEM	CUENTA	NAME OF COMMODITY	SPECIFICATION	PRODUCTOS	U.M.
                
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . ($i-2), 'CONSUMO');
        $sheet->getStyle('F' . ($i-2))->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '13', PHPExcel_Style_Border::BORDER_NONE, 'FFFFFF',PHPExcel_Style_Fill::FILL_NONE));
        
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':B' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'ITEM');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $i . ':C' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'CUENTA');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $i . ':D' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'NAME OF COMMODITY');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $i . ':E' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'SPECIFICATION');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $i . ':F' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'PRODUCTOS');   
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $i . ':G' . ($i+1));
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'U.M.');               

        //TITULO DINAMICO
        $j=7;
        for($iMes=0;$iMes<$meses;$iMes++){                                
            $mesId=(($iMes+1) < 10 ? ('0' . ($iMes+1)) : ($iMes+1));            
            
            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  strtoupper($mesesNombre[$mesId]));$j +=2; 
        }

            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'TOTALES');$j +=2; 
            
            //COLUMNA EN BLANCO
//            $indiceColumnaBlanca=$j;
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  '');$j +=1; 
                        
            $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($j , $i , ($j+1) , $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'SALDOS FINALES');$j +=2; 

        $ultimaColumna = PHPExcel_Cell::stringFromColumnIndex($j-1);
        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_HAIR, 'd8e4bc'));
        $sheet->getStyleByColumnAndRow($indiceColumnaBlanca , $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_NONE, 'FFFFFF'));
        
        //SEGUNDO TITULO
        $i++;
        $j=7;
        //DINAMICO
        for($iMes=0;$iMes<($meses+1);$iMes++){      
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(9);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'CANT');$j +=1; 
                        
            $anchoTotales=0;
            if($iMes==$meses){
                $anchoTotales=4;
            }
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(12.14+$anchoTotales);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'IMPORTE');$j +=1; 
        }
        
            //PARA COLUMNA EN BLANCA
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(3);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  '');$j +=1; 
            
            //PARA SALDOS FINALES
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(9);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'CANT');$j +=1; 
            
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($j)->setWidth(16.14);//ANCHO DE COLUMNA
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  'IMPORTE');$j +=1; 
            
        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_HAIR, 'd8e4bc'));
        $sheet->getStyleByColumnAndRow($indiceColumnaBlanca , $i)->applyFromArray($this->estiloTituloColumnasConParametros('Calibri', '9', PHPExcel_Style_Border::BORDER_NONE, 'FFFFFF'));

        //AJUSTAR TEXTO
        $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->getAlignment()->setWrapText(true);

        $i++;
        //PARA OBTENER TOTALES POR COLUMNAS
        $totalImpMeses=array();
        for($iMes=0;$iMes<$meses;$iMes++){   
            array_push($totalImpMeses, array('totalImp'=>0));
        }
        
        $totalImpTotal=0;//PARA OBTENER EL TOTAL DEL TOTAL DE MESES
        $totalSaldoFinalImp=0;
        foreach ($data as $index=> $item) {
            //ITEM	CUENTA	NAME OF COMMODITY	SPECIFICATION	PRODUCTOS

            $dataBien=$item['bien'];            
            //VALIDAMOS SI ES TIPO 16 - SALDO INICIAL
            if($dataBien['operacion_tipo']*1!=16){
                $dataBien['cantidad_final']=0;
                $dataBien['costo_unitario_final']=0;
                $dataBien['costo_final']=0;
            }
            
            $dataMeses=$item['meses'];
            $totalesBien=$item['totales'];
//
//            $sheet->getStyle('B' . $i . ':' . $ultimaColumna . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, ($index+1));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $dataBien['cuenta_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, '');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $dataBien['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Unid.');

            $j=7;
            $cantTotalSalProd=$totalesBien['cantTotalSalProd'];
            $impTotalSalProd=$totalesBien['impTotalSalProd'];
            foreach ($dataMeses as $indexMes => $itemMes){                      
                $totalImpMeses[$indexMes]['totalImp']+=$itemMes['impTotalSal'];
                
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['cantTotalSal']);$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['impTotalSal']);$j +=1; 
            }
                
                //TOTALES POR FILA - PRODUCTOS
                $totalImpTotal+=$impTotalSalProd;
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $cantTotalSalProd);$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $impTotalSalProd);$j +=1;            

                $j +=1; //POR LA COLUMNA EN BLANCA
                
                //SALDOS FINALES
                $totalSaldoFinalImp+=($dataBien['costo_final']+$totalesBien['impTotalEntProd']-$totalesBien['impTotalSalProd']);
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  ($dataBien['cantidad_final']+$totalesBien['cantTotalEntProd']-$totalesBien['cantTotalSalProd']));$j +=1; 
                $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  ($dataBien['costo_final']+$totalesBien['impTotalEntProd']-$totalesBien['impTotalSalProd']));$j +=1;            
                
            $i +=1;
        }
        
        //-------------- PARA MOSTRAR LOS TOTALES POR COLUMNA  - MESES --------------------------
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'TOTALES');
        
        $j=7;
        foreach ($totalImpMeses as $indexMes => $itemMes){      
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
            $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $itemMes['totalImp']);$j +=1; 
        }

        //TOTALES POR FILA - PRODUCTOS
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $totalImpTotal);$j +=1;            

        $j +=1; //POR LA COLUMNA EN BLANCA

        //SALDOS FINALES
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  0);$j +=1; 
        $objPHPExcel->setActiveSheetIndex()->setCellValueByColumnAndRow($j , $i ,  $totalSaldoFinalImp);$j +=1;            
        
        $i+=1;
        //------------------- ESTILOS PARA EL DETALLE -----------------------------
        $sheet->getStyle('B'.$inicioDetalleSalida.':' . $ultimaColumna . ($i-2))->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','9'));        
        $sheet->getStyle('F'.($i-1).':' . $ultimaColumna . ($i-1))->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','11','hair',true));
        //---------------- ESTILOS PARA NUMEROS --------------------------------
        
        $j=7;
        //POR MESES
        foreach ($dataMeses as $itemMes){      
            $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1;                                
            $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        }
        
        //TOTALES POR FILA - PRODUCTOS
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1;
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1;            

        //LA COLUMNA EN BLANCA        
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->applyFromArray($this->estilosDetalleTablaConParametros('Calibri','9',PHPExcel_Style_Border::BORDER_NONE));$j +=1; 

        //SALDOS FINALES
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0_ ;_ * -#,##0_ ;_ * "-"??_ ;_ @_ ');$j +=1; 
        $sheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($j).'10:' . PHPExcel_Cell::stringFromColumnIndex($j) . $i)->getNumberFormat()->setFormatCode('_ * #,##0.00_ ;_ * -#,##0.00_ ;_ * "-"??_ ;_ @_ ');$j +=1;            
       
        
        for ($i = 'B'; $i <= $ultimaColumna; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(false);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }        
        
        //ALTO FILAS
//        foreach ($arrayInd as $filaTitulo){
//            $sheet->getRowDimension($filaTitulo-1)->setRowHeight(23);
//            $sheet->getRowDimension($filaTitulo)->setRowHeight(23);
//        }

        $sheet->setTitle('kardex_resumen_'.$anio);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        $fechaActual = new DateTime();
        $formatoFechaActual = $fechaActual->format("Ymdhis");        
        
        $archivoNombre = 'kardex_resumen_'.$anio.".xls";
        $direccion = __DIR__."/../../util/uploads/$archivoNombre";
        
        $objWriter->save($direccion);
        
        $resultado->url=$direccion;
        $resultado->nombre=$archivoNombre;

        return $resultado;
        
    }
    
    public function estilosDetalleTablaConParametros($fuenteNombre='Arial',$fuenteTamanio='10',$bordeEstilo='hair',$fuenteNegrita=false) {
        return
        array(
            'font' => array(
                'name' => $fuenteNombre,
                'bold' => $fuenteNegrita,
                'italic' => false,
                'strike' => false,
                'size' => $fuenteTamanio
            ),
            'borders' => array(
                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'style' => $bordeEstilo,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            )
        );
    }
    
    public function estiloTituloColumnasConParametros($fuenteNombre='Arial',$fuenteTamanio='10',$bordeEstilo='thin',$colorCelda='FFFFFF',$rellenoEstilo='solid') {
        return
        array(
            'font' => array(
                'name' => $fuenteNombre,
                'bold' => true,
                'size' => $fuenteTamanio
            ),
            'borders' => array(
                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_HAIR, 
                    'style' => $bordeEstilo,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            ),
            'fill' => array(
//                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'type' => $rellenoEstilo,
                'color' => array('rgb' => $colorCelda)
            )
        );        
    }
}
