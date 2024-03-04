<?php
/* 
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 */
require_once __DIR__ . '/PlantillaPDF/NetafimPDF.php';

class NetafimPdfControlador {
    public function getSolicitudPdf($id, $des_tipo_solicitud, $descripcion, $usu_area_descripcion, 
                                    $fec_solicitud, $fec_tentativa, $fec_real, $monto_total, 
                                    $detalle, $lst_cotizacion, $lst_comentario, $cod_tipo_solicitud) {
        $pdf = new NetafimPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->nro_serie = "SDC-".$id; // Define el numero de serie para el codigo de barras
        $pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('SCM Netafim');
	$pdf->SetTitle('Solicitud de Compra');
	$pdf->SetSubject('Solicitud de Compra');
        
        $pdf->AddPage();
	$pdf->SetFont('times', 'B', 18);
	$pdf->Write($h=0, 'SOLICITUD DE COMPRA', $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
	$pdf->SetFont('times', '', 12);
        $pdf->Ln();
	$a_c1 = 30; // ancho primera columna
	$t_me = 12; // tamano de letra mensaje
        $general = '<style>*{font-size:14px;} .left{width:110px; font-weight: bold;}</style>';
        $general .= '<table>';
        $general .= ' <tbody>';
        $general .= '  <tr>';
        $general .= '    <td class="left">Tipo Solicitud:</td>';
        $general .= '    <td>'.$des_tipo_solicitud.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">Area:</td>';
        $general .= '    <td>'.$usu_area_descripcion.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">Descripción:</td>';
        $general .= '    <td>'.$descripcion.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">F. Solicitud:</td>';
        $general .= '    <td>'.$fec_solicitud.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">F. Tentativa:</td>';
        $general .= '    <td>'.$fec_tentativa.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">F. Real:</td>';
        $general .= '    <td>'.$fec_real.'</td>';
        $general .= '  </tr>';
        $general .= '  <tr>';
        $general .= '    <td class="left">Monto total:</td>';
        $general .= '    <td>'.$monto_total.'</td>';
        $general .= '  </tr>';
        $general .= ' </tbody>';
        $general .= '</table>';
        $pdf->writeHTML($general, true, false, true, false, '');
	$pdf->Ln();
        $pdf->Ln();
        
        /*
         * Detalle de la Solicitud
         */
        $pdf->SetFont('times', 'B', 12);
	$pdf->Write($h=0, 'Detalle de la solicitud', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        $pdf->SetFont('times', '', $t_me);
        $pdf->Ln();
        
        $det = '<style>*{font-size:11px; } th{font-size:12px;}</style>';
        $det .= '<table border="1" cellpadding="2">';
        $det .= '  <thead>';
        $det .= '    <tr>';
        $det .= '      <th bgcolor="#D6D6C1">Descripción</th>';
        $det .= '      <th bgcolor="#D6D6C1">GL</th>';
        $det .= '      <th bgcolor="#D6D6C1">GC</th>';
        $det .= '      <th bgcolor="#D6D6C1">Cantidad</th>';
        switch($cod_tipo_solicitud){
            case 3:
            case 4:
                $det .= '      <th bgcolor="#D6D6C1">Codigo A.F.</th>';
                $det .= '      <th bgcolor="#D6D6C1">Descripción A.F.</th>';
                break;
        }
        $det .= '    </tr>';
        $det .= '  </thead>';
        $det .= '  <tbody>';
        foreach($detalle as $d) {
            $det .= '    <tr>';
            $det .= '      <td>'.$d['comentario'].'</td>';
            $det .= '      <td>'.$d['gl_descripcion'].'</td>';
            $det .= '      <td>'.$d['gc_object'].'</td>';
            $det .= '      <td align="right">'.$d['cantidad'].'</td>';
            switch($cod_tipo_solicitud){
                case 3:
                case 4:
                $det .= '      <td>'.$d['af_codigo'].'</td>';
                $det .= '      <td>'.$d['af_descripcion'].'</td>';
                break;
            }
            $det .= '    </tr>';
        }
        $det .= '  </tbody>';
        $det .= '</table>';
        
        $pdf->writeHTML($det, true, false, true, false, '');
        
        /*
         * Cotizaciones 
         */
        $pdf->SetFont('times', 'B', 12);
	$pdf->Write($h=0, 'Cotización', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        $pdf->SetFont('times', '', $t_me);
        $pdf->Ln();
        
        $cot = '<style>*{font-size:11px; } th{font-size:12px;}</style>';
        $cot .= '<table border="1" cellpadding="2">';
        $cot .= '  <thead>';
        $cot .= '    <tr>';
        $cot .= '      <th bgcolor="#D6D6C1">Cotización</th>';
        $cot .= '      <th bgcolor="#D6D6C1">Usuario</th>';
        $cot .= '      <th bgcolor="#D6D6C1">Fecha</th>';
        $cot .= '    </tr>';
        $cot .= '  </thead>';
        $cot .= '  <tbody>';
        foreach($lst_cotizacion as $c) {
            $cot .= '    <tr>';
            $cot .= '      <td>'.$c['nombre'].'</td>';
            $cot .= '      <td>'.$c['usuario'].'</td>';
            $cot .= '      <td>'.$c['fecha'].'</td>';
            $cot .= '    </tr>';
        }
        $cot .= '  </tbody>';
        $cot .= '</table>';
        $pdf->writeHTML($cot, true, false, true, false, '');
        $pdf->Ln();
        
        $pdf->SetFont('times', 'B', 12);
	$pdf->Write($h=0, 'Comentarios', $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        $pdf->SetFont('times', '', $t_me);
        $pdf->Ln();
        
        $com = '<style>*{font-size:11px; }</style>';
        $com .= '<table border="1" cellpadding="2">';
        foreach($lst_comentario as $cm) {
            $com .= '    <tr>';
            $com .= '      <td colspan="2">Comentario: '.$cm['comentario'].'</td>';
            $com .= '    </tr>';
            $com .= '    <tr>';
            $com .= '      <td> Usuario: '.$cm['usuario'].'</td>';
            $com .= '      <td> Fecha: '.$cm['fecha'].'</td>';
            $com .= '    </tr>';
            $com .= '    <tr>';
            $com .= '      <td colspan="2" bgcolor="#D6D6C1"></td>';
            $com .= '    </tr>';
        }
        $com .= '</table>';
        $pdf->writeHTML($com, true, false, true, false, '');
        $pdf->Ln();
        
        return $pdf->Output('sdc-'.$id.'.pdf', 'S');
    }
}
