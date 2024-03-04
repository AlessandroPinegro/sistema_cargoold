<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'loginIttsa1.png';
        $this->Image($image_file, '', '', 70, 15, '', '', '', false, 300, '', false, false, 1, false, false, false);
        // Set font
        $this->SetFont('times', 'B', 6);
        $this->Cell(0, 15, 'Fecha de creación de documento: '.date("Y-m-d H:i:s").' Usuario: '.$this->author, 0, false, 'L', 0, '', 0, false, 'M', 'M');

        // Title
        $this->Ln(4);
        $this->Cell(0, 25, 'INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(2);
        $this->Cell(0, 55, 'RUC: 20132272418', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(2);
        $this->Cell(0, 55, 'AV. TUPAC AMARU NRO. 1198 URB. SANTA LEONOR', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(2);
        $this->Cell(0, 55, 'LA LIBERTAD - TRUJILLO - TRUJILLO', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', '', 8);
        // Page number
        $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
