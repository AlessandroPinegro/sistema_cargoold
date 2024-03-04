<?php

require_once __DIR__ . '/../commons/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/../commons/tcpdf/tcpdf.php';
include_once __DIR__.'/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoDatoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailPlantillaNegocio.php';
require_once __DIR__ . '/../../util/EmailEnvioUtil.php';

function crearPdfDocumento($documentoId,$correo) {
        
        //obtenemos la data
        
        $dataDocumentoTipo= DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        
        $documentoTipoId=$dataDocumentoTipo[0]['id'];
        $data=MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
        $dataDocumento=$data->dataDocumento;
        $documentoDatoValor=$data->documentoDatoValor;
        $detalle=$data->detalle;        
        $dataEmpresa=$data->dataEmpresa; 
        
        //DPF
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));
//        $pdf->SetSubject('TCPDF Tutorial');
//        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        
        // set default header data
//        $urlLogo=Configuraciones::url_base()."vistas/com/movimiento/imagen/logo.png";
        $pdf->SetHeaderData('logo.PNG', PDF_HEADER_LOGO_WIDTH,strtoupper($dataDocumentoTipo[0]['descripcion']),$dataEmpresa[0]['razon_social']."\n".$dataEmpresa[0]['direccion'], array(0,64,0), array(0,64,0));
        $pdf->setFooterData(array(0,64,0), array(0,64,0));
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//        // set some language-dependent strings (optional)
//        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//            require_once(dirname(__FILE__).'/lang/eng.php');
//            $pdf->setLanguageArray($l);
//        }

        // --------------GENERAR PDF-------------------------------------------

        // set font
        $pdf->SetFont('times', '', 11);

        // add a page
        $pdf->AddPage();

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        // test Cell stretching
        $serieDocumento='';
        if(!ObjectUtil::isEmpty($dataDocumento[0]['serie'])){
            $serieDocumento=$dataDocumento[0]['serie']." - ";
        }
        
        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');        
        
        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>".$titulo."</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona=$dataPersona[0]['descripcion'];
                
        $fecha=date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: ".$fecha, 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: ".$dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         
        
        if(!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])){
            $pdf->Cell(0, 0, "Descripción: ".$dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);     
        }
        
        if ($ventaCotizacion !== false) {
            $pdf->Ln(3);
            $pdf->Cell(0, 0,'Estimados señores:', 0, 1, 'L', 0, '', 0);        
            $pdf->Ln(1);
            $pdf->Cell(0, 0,'Nos es grato saludarles por medio de la presente y a la vez hacerles llegar la sgte. cotización.', 0, 1, 'L', 0, '', 0);        
        }else{
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', "<h4>DETALLE DEL DOCUMENTO</h4>", 0, 1, 0, true, 'L', true);
        }
        
        //espacio
        $pdf->Ln(5);
        
        //detalle
        $esp='&nbsp;&nbsp;&nbsp;';//espacio en blanco
        $tabla = 
            '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="10%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="40%"><b>Descripción.</b></th>
                    <th style="text-align:center;" width="20%"><b>Unid.</b></th>
                    <th style="text-align:center;" width="15%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="15%"><b>P. Total</b></th>
                </tr>
            '
            ;
        
        
        foreach ($detalle as $item) {
            $tabla=$tabla.'<tr>'
                .'<td style="text-align:rigth"  width="10%">'.$esp.round($item->cantidad,2).$esp.'</td>'
                .'<td style="text-align:left"  width="40%">'.$esp.$item->descripcion.$esp.'</td>'
                .'<td style="text-align:center"  width="20%">'.$esp.$item->unidadMedida.$esp.'</td>'
                .'<td style="text-align:rigth"  width="15%">'.$esp.number_format($item->precioUnitario,2).$esp.'</td>'
                .'<td style="text-align:rigth"  width="15%">'.$esp.number_format($item->importe, 2).$esp.'</td>'
                .'</tr>'
            ;
        };
        
        if(!ObjectUtil::isEmpty($dataDocumento[0]['total'])){
            $tabla=$tabla.'<tr>'
                .'<td style="text-align:rigth;;"  width="70%" colspan="3"  ></td>'
                .'<td style="text-align:center"  width="15%">TOTAL</td>'
                .'<td style="text-align:rigth"  width="15%">'.$esp.number_format($dataDocumento[0]['total'],2).$esp.'</td>'
                .'</tr>';
        }
        
        $tabla=$tabla.'</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');
        
        
        IF(!ObjectUtil::isEmpty($documentoDatoValor)){        
            $pdf->Ln(5);        
    //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            
            $pdf->Ln(5);
                    
            if ($ventaCotizacion !== false) {
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>TERMINOS Y CONDICIONES</h4>", 'TB', 1, 0, true, 'C', true);
            }else{;
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>OTROS DATOS DEL DOCUMENTO</h4>", 'TB', 1, 0, true, 'C', true);
            }

            $pdf->Ln(1);        
            $pdf->SetFillColor(255, 255, 255);            
            foreach ($documentoDatoValor as $indice=>$item) {
                    $pdf->MultiCell(60, 0, $item['descripcion'], 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(110, 0, $item['valor'], 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    if($indice <  count($documentoDatoValor)-1){
                        $pdf->Ln(6);
                    }else{
                        $pdf->Ln(1);
                    }

                ;
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }
        
        if ($ventaCotizacion !== false) {
            $pdf->Ln(5);
            $pdf->Cell(0, 0,'Sin otro particular, agradeciendo su gentil atención, quedamos a la espera de vuestra pronta respuesta.', 0, 1, 'L', 0, '', 0);        
            $pdf->Ln(1);
            $pdf->Cell(0, 0,'Atentamente.', 0, 1, 'L', 0, '', 0);        
            
            //telefonos
            $pdf->Ln(20);
            $borde=array('R' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            $pdf->MultiCell(145, 0, 'TELEFAX', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '044 262811', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');            
            $pdf->Ln();
            $pdf->MultiCell(145, 0, '', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '044 209454', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
            $pdf->Ln();
            $pdf->MultiCell(145, 0, 'RPC', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '977192256', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
            $pdf->Ln();
            $pdf->MultiCell(145, 0, 'RPM', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '*445213', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
            $pdf->Ln();
            $pdf->MultiCell(145, 0, 'NEXTEL', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '836*3196', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
            $pdf->Ln();
            $pdf->MultiCell(145, 0, 'CELULAR', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
            $pdf->MultiCell(30, 0, '965076817', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
            $pdf->Ln();
        }
        
        //agregar pagina
//        $pdf->AddPage();

        // ---------------------------------------------------------

        //Close and output PDF document
        ob_clean();
//        $pdf->Output('documentoJR.pdf', 'I');
//        $url=Configuraciones::url_base()."vistas/com/movimiento/documentos/documentoJR.pdf";
                
        $usuarioId = $_SESSION['id_usuario'];                
        
        $hoy = date("Y_m_d_H_i_s");  
        
//        $url=__DIR__.'/../../vistas/com/movimiento/documentos/documentoJR.pdf';
        $url=__DIR__.'/../../vistas/com/movimiento/documentos/documentoJR_'.$hoy.'_'.$usuarioId.'.pdf';
        
//        $url='C:\wamp\www\sgi\vistas\com\movimiento\documentos\documentoJR.pdf';
        //$pdf->Output($url, 'FI');
        $pdf->Output($url, 'FD');
        //envio de email
        $email = new EmailEnvioUtil();
        
//        $cuerpo=  cuerpoEmailDocumentoPDF($dataDocumento[0]['nombre'], $dataDocumentoTipo[0]['descripcion'],$descripcionPersona,$dataDocumentoTipo[0]['descripcion'],$serieDocumento.$dataDocumento[0]['numero']);
//        $enviar=$email->envio($correo, null, $titulo, $cuerpo, $urlEmail,$dataDocumentoTipo[0]['descripcion'].".pdf" );
        
        //logica correo:             
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(7);

        $asunto = $titulo;
        $cuerpo = $plantilla[0]["cuerpo"];

        $cuerpo = str_replace("[|titulo|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|descripcion_persona|]", strtolower($descripcionPersona), $cuerpo);
        $cuerpo = str_replace("[|nombre_persona|]", $dataDocumento[0]['nombre'], $cuerpo);
        $cuerpo = str_replace("[|nombre_documento|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|serie_numero|]", $serieDocumento.$dataDocumento[0]['numero'], $cuerpo);
        $nombreArchivo=$dataDocumentoTipo[0]['descripcion'].".pdf";
        
        EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId,$url,$nombreArchivo);
        //fin correo
        
            
        //$this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        
    }
    
    function cuerpoEmailDocumentoPDF($nombrePersona,$titulo,$descripcionPersona,$nombreDocumento,$serieDocumento){
        $data='<head>
                    <style>         
                        body { background-color:#eee;background-image:none;background-repeat:repeat;color:#333;font-family:Helvetica,Arial,sans-serif;line-height:1.25 }
                    </style>
                </head>
                <body>
                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="20" cellspacing="0" width="600">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff;background-image:none;background-repeat:repeat">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <table border="0" cellpadding="0" cellspacing="25" width="100%" style="background-color:#ffffff;background-image:none;background-repeat:repeat">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center" valign="middle">
                                                                                    <a href="http://imaginatecperu.com/" target="_blank"><img src="http://imaginatecperu.com/wp-content/uploads/2015/07/itsac.png" alt="Imagina"  width="200px"></a>

                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <table border="0" cellpadding="0" cellspacing="0" height="1" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center" valign="middle" bgcolor="#eeeeee" height="1" width="249"></td>
                                                                                <td align="center" valign="middle" bgcolor="#ff5800" height="1" width="102"></td>
                                                                                <td align="center" valign="middle" bgcolor="#eeeeee" height="1" width="249"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <table border="0" style="padding: 40px 40px 30px 40px;" cellspacing="0" height="0" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center" valign="middle" height="50" style="background-color:#4372C0;color:white;font-size:16px;">'.$titulo.' </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <table border="0" cellpadding="0" cellspacing="0" height="0" width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center" valign="middle">
                                                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                                                        <tr>
                                                                                            <td style="text-align:left;padding:0 55px 20px;font-size:14px;line-height:1.5;width:80%">
                                                                                                <p align="justify" style="align: justify; line-height:1.5;">                                                                                     
                                                                                                    Estimado '.strtolower($descripcionPersona).': '.$nombrePersona.', se adjuntó el documento "'.$nombreDocumento.'" con n&uacute;mero '.$serieDocumento.'.
                                                                                                </p>
                                                                                                <br>        
                                                                                                <br>                                                                                
                                                                                            </td>
                                                                                        <tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <table border="0" cellpadding="30" cellspacing="0" height="0" width="100%" style="border-top-width:1px;border-top-style:solid;border-top-color:#eee">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center" valign="middle">
                                                                                    <div>
                                                                                        <small style="color:#999;font-size:12px;margin-top:4px;margin-bottom:4px;margin-right:4px;margin-left:4px">Por favor, no responda a este correo. Este es un correo automatizado s&oacute;lo utilizado para enviarle avisos por email. Las respuestas a este mensaje se redirigen a un buz&oacute;n de correo sin supervisi&oacute;n.</small>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>';
        
       return $data;
}
