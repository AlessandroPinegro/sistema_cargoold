<?php

/* 
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 */
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modeloNegocio/boletas/BoletaPagoNegocio.php';
require_once __DIR__ . '/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/tcpdf/tcpdf.php';

class PdfControlador extends TCPDF {
    public function Header() {
     
    }

    public function Footer() {
        
    }
}

function getBoletaPago($id_usuario, $boleta_id) { // los parametros para la boleta de pago seleccionada
    $pdf = new PdfControlador('P', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', true);

    /*$pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Boletas Netafim');
    $pdf->SetTitle('Boleta de Pago');
    $pdf->SetSubject('Boleta de Pago periodo');*/
    // $boleta = InternamientoNegocio::create()->getPrintCabeceraBoletaPago($internamiento_id);
    $html = file_get_contents( __DIR__.'/../boletas/pdf/PlantillaBoletaPago.php');
    $html = str_replace("{{tabla}}", file_get_contents( __DIR__.'/../boletas/pdf/PlantillaBoletaPagoTabla.php'), $html);
    $html = str_replace("{{imgPdfDir}}", Configuraciones::url_base().Configuraciones::IMG_PDF_DIR, $html);
    $pdf->Ln();
 
    $pdf->AddPage('L', 'A4');
    $pdf->SetFont('helvetica', '', 7);
    $pdf->writeHTML($html);//, true, false, true, false, '');
    //$pdf->Ln();	
    return $pdf->Output('doc.pdf', 'I');
    
}