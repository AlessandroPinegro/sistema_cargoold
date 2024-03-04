<?php
require_once __DIR__ . '/../../../util/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/../../../util/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../../util/NumeroALetra/EnLetras.php';
DEFINE("CUSTOM_W", 209);
DEFINE("CUSTOM_H", 115);

/**
 * Description of LetraPDF
 *
 * @author Imagina
 */
class LetraPDF {
    public $pdf;
    
    public function __construct() {
        $layout = array(CUSTOM_W,CUSTOM_H);
        $this->pdf = new TCPDF("L", PDF_UNIT, $layout, true, 'UTF-8', false);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(0, 0, 0, true);
        $this->pdf->SetAutoPageBreak(TRUE, 0);
    }
    
    public function ObtenerDocumentoImpresion($letra){
        $pdf = $this->pdf;
        $pdf->SetFont('helvetica', '', 7);
        $pdf->AddPage();
        
        $this->ArmarTenedor($pdf, $letra);
        $this->ArmarAceptante($pdf, $letra);
        $this->ArmarRepresentante($pdf, $letra);
        
        $pdf->Output("test.pdf", "I");
    }
    private function ArmarTenedor($pdf, $letra){
        $h = 4;
        $r1 = 24;
        $designacion = $letra->moneda == "soles" ? "S/." : "$/.";
        $pdf->SetXY(40, $r1); //numero
        $pdf->Cell(20, $h, $letra->numero);
        
        $pdf->SetXY(66, $r1-2); //referencia girador
        $pdf->Cell(20, 8, strtoupper($letra->refgirador));
        
        $pdf->SetXY(92, $r1); //fecha giro
        $pdf->Cell(20, $h, $letra->fecha);
        
        $pdf->SetXY(120, $r1); //lugar
        $pdf->Cell(20, $h, strtoupper($letra->lugar));
        
        $pdf->SetXY(147, $r1); //fecha vencimiento
        $pdf->Cell(20, $h, $letra->fecha_ven);
        
        $pdf->SetXY(175, $r1); //importe
        $pdf->Cell(25, $h, $designacion.number_format($letra->importe, 2));
        
        $pdf->SetXY(133, $r1+7); //empresa nombre
        $pdf->Cell(70, $h, strtoupper($letra->empresa_nombre));
        
        $c = new EnLetras();
        $pdf->SetXY(40, $r1+15); //importe en letras
        $pdf->Cell(155, $h, strtoupper($c->ValorEnLetras($letra->importe, $letra->moneda)));
    }
    
    private function ArmarAceptante($pdf, $letra){
        $h = 4;
        $r1 = 52;
        
        $pdf->SetXY(54, $r1); //aceptante
        $pdf->Cell(60, $h*2, strtoupper($letra->aceptante));
        
        $pdf->SetXY(51, $r1+9); //domicilio
        $pdf->Cell(62, $h, strtoupper($letra->a_domicilio));
        
        $pdf->SetXY(81, $r1+13); //localidad
        $pdf->Cell(30, $h, strtoupper($letra->a_localidad));
        
        $pdf->SetXY(53, $r1+17); //ruc
        $pdf->Cell(14, $h, $letra->a_ruc);
        
        $pdf->SetXY(84, $r1+17); //telefono
        $pdf->Cell(25, $h, $letra->a_telefono);
    }
    
    private function ArmarRepresentante($pdf, $letra){
        $h = 4;
        $pdf->SetXY(125,93); //D.O.I.
        $pdf->Cell(30, $h, $letra->doi);
    }
}

$letra = new stdClass();
$letra->numero = "27 - 2015";
$letra->refgirador = "003-B225 / B767";
$letra->importe = "1955.88";
$letra->moneda = "soles";
$letra->lugar = "TRUJILLO";
$letra->fecha = "14/08/2015";
$letra->fecha_ven = "28/09/2015";
$letra->empresa_nombre = "INDUSTRIAL MILCIADES VARGAS S.R.L.";
$letra->empresa_razon = "INDUSTRIAL MV S.R.L.";
$letra->doi = "17839992";
$letra->aceptante = "Angulo Vera Jose Nicanor";
$letra->a_domicilio = "Jr. Union 1º piso nro 212 Barr. La Intendencia";
$letra->a_ruc = "10190553378";
$letra->a_localidad = "Trujillo";
$letra->a_telefono = "201136";

$pdf = new LetraPDF();
$pdf->ObtenerDocumentoImpresion($letra);




