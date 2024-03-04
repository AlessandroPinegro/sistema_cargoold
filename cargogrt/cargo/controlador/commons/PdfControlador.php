<?php

/* 
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 */
require_once __DIR__ . '/../../util/Configuraciones.php';
//require_once __DIR__ . '/../../modeloNegocio/almacen/BoletaPagoNegocio.php';
require_once __DIR__ . '/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/tcpdf/tcpdf.php';

class ColaboradorBoletaPDF extends TCPDF {
    private $posicion = "I";
    
    public function Header() {
    }
    public function Footer() {
    }

    // Load table data from file
    public function LoadData($file) {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;
    }
    
    private function calcX($x){
        return ($this->posicion == "D")? ($x+150): $x;
    }
    
    private function calcY($y){
        return ($y + 17);
    }
    
    public function dibujaFondo(){
        $this->SetFillColor(240);
        $this->Rect(160, 12, 130, 180, 'DF', "L");
    }


    // Colored table
    public function ColoredTable($posicion,$data, $imgdata) {
        $this->posicion = $posicion;
        // 1ra Sección
        // 1.1 Logo
        $this->Image(Configuraciones::url_base().Configuraciones::IMG_PDF_DIR, $this->calcX(16), $this->calcY(-1), 45, 9);
        // 1.2 Cabecera de la boleta
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->SetY($this->calcY(-2));
        $this->SetX($this->calcX(67));
        $this->Cell(14, 3, "RUC", 0, 0, 'L', 0);
        $this->Cell(40, 3, "20481450510", 0, 0, 'L', 0);
        $this->Ln();
        $this->SetY($this->calcY(1));
        $this->SetX($this->calcX(67));
        $this->Cell(60, 3, "Av Los Eucaliptos N° 371 Interior 41 Z.I. Santa Genoveva", 0, 1, 'L', 0);
        $this->Ln();
        $this->SetY($this->calcY(4));
        $this->SetX($this->calcX(67));
        $this->Cell(60, 3, "Lurin / Lima / Lima", 0, 1, 'L', 0);
        $this->Ln();
        $this->SetY($this->calcY(7));
        $this->SetX($this->calcX(67));
        $this->Cell(12, 3, "PERIODO", 0, 0, 'L', 0);
        $this->Cell(40, 3, "DICIEMBRE 2015", 0, 0, 'C', 0);
        $this->Ln();
        
        // 2da Sección, Tabla de Datos Personales
        // 2.1. Cabecera de tabla
        $this->SetY($this->calcY(10));
        $this->SetX($this->calcX(10));
        $this->Cell(25, 3, "CATEGORIA", 1, 0, 'C', 0);
        $this->Cell(37, 3, "APELLIDO PATERNO", 1, 0, 'C', 0);
        $this->Cell(31, 3, "APELLIDO MATERNO", 1, 0, 'C', 0);
        $this->Cell(37, 3, "NOMBRES", 1, 0, 'C', 0);
        $this->Ln();
        
        // 2.2. Datos personales
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(10) + $i*3.4);
            $this->SetX($this->calcX(10));
            $this->Cell(25, 3, $row[0], "LRB", 0, 'C', 0);
            $this->Cell(37, 3, $row[1], "RB", 0, 'C', 0);
            $this->Cell(31, 3, $row[2], "RB", 0, 'C', 0);
            $this->Cell(37, 3, $row[3], "RB", 0, 'C', 0);
            $this->Ln();
        }
        // 2.3. Espacio
        $this->Ln();
        
        // 3ra Sección, Datos de cargo 
        // 3.1. Cabecera de tabla
        $this->SetY($this->calcY(20.2));
        $this->SetX($this->calcX(10));
        $this->Cell(65, 3, "CARGO", 1, 0, 'C', 0);
        $this->Cell(32.5, 3, "AREA", 1, 0, 'C', 0);
        $this->Cell(32.5, 3, "SEDE", 1, 0, 'C', 0);
        $this->Ln();
        
        // 3.2. Datos personales
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(20.2) + $i*3.4);
            $this->SetX($this->calcX(10));
            $this->Cell(65, 3, $row[0], "LRB", 0, 'C', 0);
            $this->Cell(32.5, 3, $row[1], "RB", 0, 'C', 0);
            $this->Cell(32.5, 3, $row[2], "RB", 0, 'C', 0);
            $this->Ln();
        }
        // 3.3. Espacio
        $this->Ln();
        
        // 4ta Clasificacion datos personales
        // 4.1. Cabecera de tabla
        $this->SetY($this->calcY(30.4));
        $this->SetX($this->calcX(10));
        $this->Cell(30, 3, "CLASIFICACION", 1, 0, 'C', 0);
        $this->Cell(14.5, 3, "TIPO DOC", 1, 0, 'C', 0);
        $this->Cell(20.5, 3, "N° DOC. IDENT.", 1, 0, 'C', 0);
        $this->Cell(32.5, 3, "FECHA DE INGRESO", 1, 0, 'C', 0);
        $this->Cell(32.5, 3, "FECHA DE CESE", 1, 0, 'C', 0);
        $this->Ln();
        
        // 4.2. Datos personales
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(30.4) + $i*3.4);
            $this->SetX($this->calcX(10));
            $this->Cell(30, 3, $row[0], "LRB", 0, 'C', 0);
            $this->Cell(14.5, 3, $row[1], "RB", 0, 'C', 0);
            $this->Cell(20.5, 3, $row[2], "RB", 0, 'C', 0);
            $this->Cell(32.5, 3, $row[3], "RB", 0, 'C', 0);
            $this->Cell(32.5, 3, $row[0], "RB", 0, 'C', 0);
            $this->Ln();
        }
        // 4.3. Espacio
        $this->Ln();
        
        // 5ta SEGUROS
        // 5.1. Cabecera de tabla
        $this->SetY($this->calcY(40.6));
        $this->SetX($this->calcX(10));
        $this->Cell(29, 3, "SIST. DE PENSIONES", 1, 0, 'C', 0);
        $this->Cell(26, 3, "CUSPP", 1, 0, 'C', 0);
        $this->Cell(29, 3, "SISTEMA DE SEGURO", 1, 0, 'C', 0);
        $this->Cell(26, 3, "AUTOGENERADO", 1, 0, 'C', 0);
        $this->Cell(20, 3, "SUELDO", 1, 0, 'C', 0);
        $this->Ln();
        
        // 5.2. Datos personales
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(40.6) + $i*3.4);
            $this->SetX($this->calcX(10));
            $this->Cell(29, 3, $row[0], "LRB", 0, 'C', 0);
            $this->Cell(26, 3, $row[1], "RB", 0, 'C', 0);
            $this->Cell(29, 3, $row[2], "RB", 0, 'C', 0);
            $this->Cell(26, 3, $row[0], "RB", 0, 'C', 0);
            $this->Cell(20, 3, number_format(2000.57, 2), "RB", 0, 'R', 0);
            $this->Ln();
        }
        // 5.3. Espacio
        $this->Ln();  
        
        // 6ta DIAS LABORADOS / NO LABORADOS
        // 6.1. Cabecera de tabla
        $this->SetY($this->calcY(50.8));
        $this->SetX($this->calcX(10));
        $this->Cell(29, 3, "DIAS LABORADOS", 1, 0, 'C', 0);
        $this->Cell(26, 3, "DIAS NO LABORADOS", 1, 0, 'C', 0);
        $this->Cell(20, 3, "DIAS SUBS", 1, 0, 'C', 0);
        $this->Cell(26, 3, "DIAS VAC.", 1, 0, 'C', 0);
        $this->Cell(29, 3, "TOTAL HHEE", 1, 0, 'C', 0);
        $this->Ln();
        
        // 6.2. Datos personales
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(50.8) + $i*3.4);
            $this->SetX($this->calcX(10));
            $this->Cell(29, 3, $row[0], "LRB", 0, 'C', 0);
            $this->Cell(26, 3, $row[1], "RB", 0, 'C', 0);
            $this->Cell(20, 3, $row[2], "RB", 0, 'C', 0);
            $this->Cell(26, 3, $row[0], "RB", 0, 'C', 0);
            $this->Cell(29, 3, $row[0], "RB", 0, 'R', 0);
            $this->Ln();
        }
        // 6.3. Espacio
        $this->Ln(); 
        
        // 7ma INGRESO / DESCUENTO
        // 7.1. Cabecera de tabla
        $this->SetFillColor(200, 200, 200);
        $this->SetFont("", "B");
        $this->SetY($this->calcY(61));
        $this->SetX($this->calcX(10));
        $this->Cell(65, 3, "INGRESO", "LTB", 0, 'L', 1);
        $this->Cell(65, 3, "DESCUENTO", "TRB", 0, 'C', 1);
        $this->Ln();
        $this->SetFont("", "");
        // 7.2. Datos 
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(61.4) + $i*3);
            $this->SetX($this->calcX(10));
            $this->Cell(30, 3, $row[2], 0, 0, 'L', 0);
            $this->Cell(10, 3, number_format(30), 0, 0, 'R', 0);
            $this->Cell(20, 3, number_format(7500.65,2), 0, 0, 'R', 0);
            $this->Cell(5, 3, "", 0, 0, 'C', 0);
            $this->Cell(30, 3, $row[1], 0, 0, 'L', 0);
            $this->Cell(10, 3, number_format(0), 0, 0, 'R', 0);
            $this->Cell(20, 3, number_format(3500,2), 0, 0, 'R', 0);
            $this->Ln();
            
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
            $i = $this->writeDetalle($i, $row);
        }
        // 7.3. Espacio
        $this->Ln(); 
        
        // 8va APORTES EMPLEADOR
        // 8.1. Cabecera de tabla
        $this->SetFillColor(200, 200, 200);
        $this->SetFont("", "B");
        $this->SetY($this->calcY(109));
        $this->SetX($this->calcX(10));
        $this->Cell(130, 3, "APORTES EMPLEADOR", "LTRB", 0, 'C', 1);
        $this->Ln();
        $this->SetFont("", "");
        // 8.2. Datos 
        $i = 0;
        foreach($data as $row) {
            $i += 1;
            $this->SetY($this->calcY(109.4) + $i*3);
            $this->SetX($this->calcX(10));
            $this->Cell(20, 3, "", 0, 0, 'L', 0);
            $this->Cell(40, 3, $row[3], 0, 0, 'L', 0);
            $this->Cell(20, 3, number_format(7.65,2), 0, 0, 'R', 0);
            $this->Cell(30, 3, number_format(500.65,2), 0, 0, 'R', 0);
            $this->Ln();
            
            $i = $this->writeDetalle2($i, $row);
            $i = $this->writeDetalle2($i, $row);
            $i = $this->writeDetalle2($i, $row);
            $i = $this->writeDetalle2($i, $row);
            $i = $this->writeDetalle2($i, $row);
            $i = $this->writeDetalle2($i, $row);
        }
        // 8.3. Espacio
        $this->Ln(); 
        
        // 9ma. Prestamos
        // 9.1
        $this->SetFont("");
        $this->SetY($this->calcY(136));
        $this->SetX($this->calcX(10));
        $this->Cell(15, 3, "Prestamo:", "LTB", 0, 'L', 0);
        $this->Cell(25, 3, "", "TB", 0, 'C', 0); // IF 
        $this->Cell(15, 3, "Pagado:", "TB", 0, 'L', 0);
        $this->Cell(30, 3, "", "TB", 0, 'C', 0); // IF
        $this->Cell(25, 3, "Saldo pendiente:", "TB", 0, 'L', 0);
        $this->Cell(20, 3, "", "TRB", 0, 'C', 0); // IF
        $this->Ln();
        // 9.2. Espacio
        $this->Ln();
        
        // 10ma. Prestamos
        // 10.1
        $this->SetY($this->calcY(143));
        $this->SetX($this->calcX(10));
        $this->SetFont("", "B");
        $this->Cell(25, 3, "TOTAL INGRESOS", "LTB", 0, 'L', 0);
        $this->SetFont("", "");
        $this->Cell(15, 3, "S/.".  number_format(117680.9, 2), "TRB", 0, 'R', 0); 
        $this->SetFont("", "B");
        $this->Cell(25, 3, "TOTAL DCTOS", "TB", 0, 'L', 0);
        $this->SetFont("", "");
        $this->Cell(20, 3, "S/.".  number_format(7680.9, 2), "TRB", 0, 'R', 0); 
        $this->SetFont("", "B");
        $this->Cell(25, 3, "TOTAL APORTES", "TB", 0, 'L', 0);
        $this->SetFont("", "");
        $this->Cell(20, 3, "S/.".  number_format(7680.9, 2), "TRB", 0, 'R', 0); // IF
        $this->Ln();
        // 10.2. Espacio
        $this->Ln();
        
        // 11Va. Neto a pagar
        // 11.1
        $this->SetY($this->calcY(150));
        $this->SetX($this->calcX(10));
        $this->SetFont("", "B");
        $this->Cell(25, 3, "NETO A PAGAR", 1, 0, 'L', 0);
        $this->Cell(20, 3, "S/.".  number_format(117680.9, 2), 1, 0, 'R', 0); 
        $this->Ln();
        // 11.2. Espacio
        $this->Ln();
        
        // 12Va. SECCION DE FIRMAS
        // 12.1
        $this->SetY($this->calcY(165));
        $this->SetX($this->calcX(10));
        $this->SetFont("", "");
        $this->Image(Configuraciones::url_base()."util/uploads/"."f_f528764d624db129b32c21fbca0cb8d620150615034818.png", $this->calcX(16), $this->calcY(150), 45, 22.5);
        $this->Cell(55, 3, "Por Netafim Peru SAC", "T", 0, 'C', 0);
        $this->Cell(30, 3, "", 0, 0, 'C', 0);
        $this->Image(Configuraciones::url_base()."util/uploads/"."f_f528764d624db129b32c21fbca0cb8d620150615034818.png", $this->calcX(95), $this->calcY(150), 45, 22.5);
//        $this->Image("@".$imgdata, $this->calcX(95), $this->calcY(150), 45, 22);
        $this->Cell(45, 3, "Firma Trabajador", "T", 0, 'C', 0);
        $this->Ln();
        // 12.2. Asignamos la firma
        
    }
    private function writeDetalle($i, $row){
        $i += 1;
        $this->SetY($this->calcY(61.4) + $i*3);
        $this->SetX($this->calcX(10));
        $this->Cell(30, 3, $row[2], 0, 0, 'L', 0);
        $this->Cell(10, 3, number_format(30), 0, 0, 'R', 0);
        $this->Cell(20, 3, number_format(7500.65,2), 0, 0, 'R', 0);
        $this->Cell(5, 3, "", 0, 0, 'C', 0);
        $this->Cell(30, 3, $row[1], 0, 0, 'L', 0);
        $this->Cell(10, 3, number_format(0), 0, 0, 'R', 0);
        $this->Cell(20, 3, number_format(3500,2), 0, 0, 'R', 0);
        $this->Ln();
        return $i;
    }
    private function writeDetalle2($i, $row){
        $i += 1;
        $this->SetY($this->calcY(106.4) + $i*3);
        $this->SetX($this->calcX(10));
        $this->Cell(20, 3, "", 0, 0, 'L', 0);
        $this->Cell(40, 3, $row[3], 0, 0, 'L', 0);
        $this->Cell(20, 3, number_format(7.65,2), 0, 0, 'R', 0);
        $this->Cell(30, 3, number_format(500.65,2), 0, 0, 'R', 0);
        $this->Ln();
        return $i;
    }
}

function getBoletaPago($id_usuario, $boleta_id) { // los parametros para la boleta de pago seleccionada
    // create new PDF document
    $pdf = new ColaboradorBoletaPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Imagina Technologies S.A.C.');
    $pdf->SetTitle('Boletas de pago');
    
    // set font
    $pdf->SetFont('helvetica', '', 7);

    // add a page
    $pdf->AddPage('L', 'A4');
    
//    $pdf->Image(Configuraciones::url_base().Configuraciones::IMG_PDF_DIR, 15, 10, 25, 10, 'PNG', '', '', false, 300, '', false, false, 1, false, false, false);
    // column titles
    $header = array('CATEGORIA', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES');

    // data loading
    //$data = $pdf->LoadData('data/table_data_demo.txt');
    $data = array();
    $data[0][0] = "TRABAJADOR";
    $data[0][1] = "HEREDIA";
    $data[0][2] = "LOZADA";
    $data[0][3] = "CHRISTOPHER SEGUNDO";
//    $imgdata = base64_decode('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');
    // print colored table
    $pdf->ColoredTable("I", $data, $imgdata);
    $pdf->dibujaFondo();
    $pdf->ColoredTable("D", $data, $imgdata);
    // ---------------------------------------------------------

    // close and output PDF document
    return $pdf->Output('example_011.pdf', 'I');
    
}
function getImagen(){
    
}