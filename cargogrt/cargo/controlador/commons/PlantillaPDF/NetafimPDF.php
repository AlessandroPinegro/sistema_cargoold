<?php
/* 
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 */
require_once __DIR__ . '/../../../../Classes/tcpdf/config/lang/eng.php';
require_once __DIR__ . '/../../../../Classes/tcpdf/tcpdf.php';
define('IMG',__DIR__ . '/../../../vistas/images/');

class NetafimPDF extends TCPDF {
    public $nro_serie; 
    
    function __construct() {
        parent::__construct();
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	$this->SetMargins(30, 30, 10, true);
	$this->SetHeaderMargin(PDF_MARGIN_HEADER);
	$this->SetFooterMargin(PDF_MARGIN_FOOTER);

	$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
    }
    
    public function Header() {
        $this->SetFont('times', 'B', 12);
        $this->Write($h=0, "NETAFIM PERU S.A.C.\nRUC No. 20481450510", $link='', $fill=0, $align='', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
        $image_file = IMG.'logonetafim.png';
        $this->Image($image_file, 0, 5, 0, 0, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);
    }
    public function Footer() {
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
        $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
    }
    
}