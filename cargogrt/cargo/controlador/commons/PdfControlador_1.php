<?php

/* 
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 */
require_once __DIR__ . '/../../modeloNegocio/boletas/BoletaPagoNegocio.php';
require_once __DIR__ . '/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/tcpdf/tcpdf.php';

class PdfControlador extends TCPDF {
    public function Header() {
     /* 
        $image_file = __DIR__ . '/../../vistas/images/logonetafim.png' ;//IMG.'logonetafim.png';
        $this->Image($image_file, 0, 5, 0, 0, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
        $this->SetFont('times', 'B', 10);
        $this->setColor("#E0E0E0");
        $this->Write($h=0, "NETAFIM PERU S.A.C.\nRUC No. 20481450510", $link='', $fill=0, $align='', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
      * */
    }

    public function Footer() {
        /*
        $cur_y = $this->y;
        $this->SetTextColorArray($this->footer_text_color);
        $line_width = (0.85 / $this->k);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));
        $this->SetY(-16);
        $style = array('position' => 'L', 'align' => 'L',        'stretch' => false,   'fitwidth' => true,        'cellfitalign' => '',
                       'border' => false, 'hpadding' => 'auto',  'vpadding' => 'auto', 'fgcolor' => array(0,0,0), 'bgcolor' => false, //array(255,255,255),
                       'text' => true,    'font' => 'helvetica', 'fontsize' => 8,      'stretchtext' => 4
        );
        $this->write1DBarcode($this->nro_serie, 'C39', '', '', '', 12, 0.4, $style, 'T');
        $pagenumtxt = 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
        $this->SetY(-15);
        $this->SetX($this->original_lMargin);
        $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');*/
    }
}

function getBoletaPago($id_usuario, $boleta_id) { // los parametros para la boleta de pago seleccionada
    $pdf = new PdfControlador('P', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', true);

    /*$pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Boletas Netafim');
    $pdf->SetTitle('Boleta de Pago');
    $pdf->SetSubject('Boleta de Pago periodo');

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(30, 30, 10, true);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);*/

    // Aqui para la primera tabla
    //$pdf->AddPage();
    /*$pdf->SetFont('times', 'B', 18);
    $pdf->Write($h=0, 'NOTA DE ENTRADA', $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    //$pdf->Ln();
    $a_c1 = 30; // ancho primera columna
    $t_me = 12; // tamano de letra mensaje
*/
    
    /**
     * Cabecera tabla
     */

    
    // $boleta = InternamientoNegocio::create()->getPrintCabeceraBoletaPago($internamiento_id);
    $html .= '
<style>.cellCab{font-size:9px;font-style:Calibri;font-weight:normal;text-align:center;} .cellDat{font-size:9px;font-style:Calibri;font-weight:normal;text-align:center;} .der{text-align:right;} .izq{text-align:left;}</style>
<div style="position:absolute; top:100px; right:100px; width:200px; background-color:#E0E0E0;">
 <table border="0" width="100%">
 <tr>
  <td class="cellCab" rowspan="4"><img src=/../../vistas/images/logonetafim.png/></td>
  <td>
  <tr>
   <td class="cellCab" style"text-align:center;" >RUC: 20481450510</td>
  </tr>
  <tr>
   <td class="cellCab" style"text-align:center;" >Av Los Eucaliptos N° 371 Interior 41 Z.I. Santa Genoveva</td>
  </tr>
  <tr>
   <td class="cellCab" style"text-align:center;" >Lurin / Lima / Lima</td>
  </tr>
  <tr>
   <td class="cellCab" style"text-align:center;" >PERIODO  DICIEMBRE 2013</td>
  </tr>
  </td>
 </tr>
 </table>
 <table border="1" width="100%">
 <tr>
  <td class="cellCab">CATERORIA</td>
  <td class="cellCab" colspan="3">APELLIDO PATERNO</td>
  <td class="cellCab" colspan="3">APELLIDO MATERNO</td>
  <td class="cellCab" colspan="3">NOMBRES</td>
 </tr>
 <tr>
  <td class="cellDat">EMPLEADO</td>
  <td class="cellDat" colspan="3">GUTIERREZ</td>
  <td class="cellDat" colspan="3">CASTRO</td>
  <td class="cellDat" colspan="3">JOSE FERNANDO</td>
 </tr>
 </table>
 <table border="1" width="100%">
  <tr>
   <td class="cellCab" colspan="5">CARGO</td>
   <td class="cellCab" colspan="3">AREA</td>
   <td class="cellCab" colspan="2">SEDE</td>
  </tr>
  <tr>           
   <td class="cellDat" colspan="5">ANALISTA DE COMPESACIONES & BENEFICIOS</td>
   <td class="cellDat" colspan="3">RRHH</td>
   <td class="cellDat" colspan="2">LIMA</td>
  </tr>
 </table>
 <table border="1" width="100%">
  <tr>
   <td class="cellCab" colspan="2"> CALIFICACION  </td>
    <td class="cellCab">TIPO DOC</td>
    <td class="cellCab" colspan="2"> Nº DOC. IDENT. </td>
    <td class="cellCab" colspan="3"> FECHA DE INGRESO </td>
    <td class="cellCab" colspan="2"> FECHA DE CESE </td>
  </tr> 
  <tr>             
   <td class="cellDat" colspan="2">CONF/SUJETO A FISC.</td>
   <td class="cellDat">DNI</td>
   <td class="cellDat" colspan="2">10497891</td>
   <td class="cellDat" colspan="3">28/01/2013</td>
   <td class="cellDat" colspan="2"></td>
  </tr>
 </table>
 <table border="1" width="100%">
  <tr>
   <td class="cellCab" colspan="2"> SIST. DE PENSIONES </td>
   <td class="cellCab" colspan="2"> CUSPP </td>
   <td class="cellCab" colspan="3"> SISTEMA DE SEGURO </td>
   <td class="cellCab" colspan="2"> AUTOGENERADO </td>
   <td class="cellCab"> SUELDO </td>
  </tr>  
  <tr>              
   <td class="cellDat" colspan="2">SPP INTEGRA</td>
   <td class="cellDat" colspan="2">584611JGCIT1</td>
   <td class="cellDat" colspan="3">EPS</td>
   <td class="cellDat" colspan="2">7712041GICTJ002</td>
   <td class="cellDat">S/. 3.000,00</td>
  </tr>
 </table>
 <table border="1" width="100%">
  <tr>  
   <td class="cellCab" colspan="2">DIAS LABORADOS</td>
   <td class="cellCab" colspan="2">DIAS NO LABORADOS</td>
   <td class="cellCab" colspan="2">DIAS SUBS.</td>
   <td class="cellCab" colspan="2">DIAS VAC.</td>
   <td class="cellCab" colspan="2">TOTAL HHEE</td>
  </tr>  
  <tr>             
   <td class="cellDat" colspan="2">31</td>
   <td class="cellDat" colspan="2">0</td>
   <td class="cellDat" colspan="2">0</td>
   <td class="cellDat" colspan="2">0</td>
   <td class="cellDat" colspan="2">0</td>
  </tr>
 </table>
 <table width="100%">
   <tr>
    <td style="background-color:gray;font-weight=bold;" class="cellCab" width="50%" colspan="3">INGRESO</td>
    <td style="background-color:gray;font-weight=bold;" class="cellCab" width="50%" colspan="3">DESCUENTO</td>
   </tr>
   <tr>             
    <td class="cellDat">Sueldo básico mes</td>
    <td class="cellDat">30</td>            
    <td class="cellDat">3000</td>
    <td class="cellDat">Fondo AFP</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>
   <tr>              
    <td class="cellDat">Asignación Familiar</td>
    <td class="cellDat">30</td>             
    <td class="cellDat">3000</td>
    <td class="cellDat">Seguro AFP</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>   
   <tr>            
    <td class="cellDat">Gratif. Julio/Diciembre</td>
    <td class="cellDat">30</td>       
    <td class="cellDat">3000</td>
    <td class="cellDat">Comision Variable</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>   
   <tr>             
    <td class="cellDat">Horas Extras 25%</td>
    <td class="cellDat">30</td>             
    <td class="cellDat">3000</td>
    <td class="cellDat">Dscto.Concesionario</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>  
   <tr>         
    <td class="cellDat">Horas extras 35%</td>
    <td class="cellDat">30</td>            
    <td class="cellDat">3000</td>
    <td class="cellDat">Dcto. Asig. Cumpleaños</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>  
   <tr>            
    <td class="cellDat">Horas extras 100%</td>
    <td class="cellDat">30</td>       
    <td class="cellDat">3000</td>
    <td class="cellDat">Dscto.Aguinaldo</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>
   <tr>        
    <td class="cellDat">Vale por Segregación</td>
    <td class="cellDat">30</td>          
    <td class="cellDat">3000</td>
    <td class="cellDat">Dscto.Segregación</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr> 
   <tr>         
    <td class="cellDat">Bonif. Ext. L. 2935/td>
    <td class="cellDat">30</td>          
    <td class="cellDat">3000</td>
    <td class="cellDat">Descuento EPS</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>  
   <tr>   
    <td class="cellDat">Asignación por cumpleaños</td>
    <td class="cellDat">30</td>
    <td class="cellDat">3000</td>
    <td class="cellDat">Impto.Rentas 5ta.Categor</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>
   <tr>         
    <td class="cellDat">Aguinaldo Diciembre</td>
    <td class="cellDat">30</td>         
    <td class="cellDat">3000</td>
    <td class="cellDat">Adelanto .Antic.Gratificación</td>
    <td class="cellDat">10</td>
    <td class="cellDat">307,5</td>
   </tr>   
   <tr>             
    <td class="cellDat"></td>
    <td class="cellDat"></td>            
    <td class="cellDat"></td>
    <td class="cellDat">Adelanto de Quinc.</td>
    <td class="cellDat"></td>
    <td class="cellDat">307,5</td>
   </tr>       
 </table>
 <table width="100%">    
   <tr>  
    <td style="background-color:gray;font-weight=bold;" class="cellCab" width="100%" colspan="5">APORTES EMPLEADOR</td>
   </tr> 
   <tr>              
    <td width="20%"></td>         
    <td width="20%">Essalud 6.75%</td>
    <td width="20%">0</td>
    <td width="20%">0</td>
    <td width="20%"></td>  
   </tr>    
   <tr>             
    <td width="20%"></td>  
    <td width="20%">Essalud 6.75%</td>
    <td width="20%">0</td>
    <td width="20%">0</td>
    <td width="20%"></td>  
   </tr>     
   <tr>            
    <td width="20%"></td>  
    <td width="20%">Essalud 6.75%</td>
    <td width="20%">0</td>
    <td width="20%">0</td>
    <td width="20%"></td>  
   </tr>     
   <tr>              
    <td width="20%"></td>  
    <td width="20%">Essalud 6.75%</td>
    <td width="20%">0</td>
    <td width="20%">0</td>
    <td width="20%"></td>  
   </tr>      
   <tr>              
    <td width="20%"></td>  
    <td width="20%">Essalud 6.75%</td>
    <td width="20%">0</td>
    <td width="20%">0</td>
    <td width="20%"></td>  
   </tr>  
 </table>
</div>
 ';
 $pdf->Ln();
    
    $pdf->AddPage();
    $pdf->writeHTML($html);//, true, false, true, false, '');
    //$pdf->Ln();	
    return $pdf->Output('doc.pdf', 'I');
    
}
