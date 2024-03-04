<?php

require_once __DIR__ . '/../core/ControladorBase.php';
require_once __DIR__ . '/../libs/ExportarExcel/PHPExcel.php';
require_once __DIR__ . '/../libs/ExportarExcel/PHPExcel/Style/Color.php';
require_once __DIR__ . '/../libs/ExportarExcel/PHPExcel/Worksheet/MemoryDrawing.php';
require_once __DIR__ . '/../libs/ExportarExcel/PHPExcel/IOFactory.php';

class GeneradorExcelControlador extends ControladorBase{
    // <editor-fold defaultstate="collapsed" desc="Declaraciones">
    const path = '/../../public/';
    private $sid;
    private $empresaId;
    private $timeLimit = 120;
    private $urlParams = '';
    private $arrayParams = array();
    //private $userAgent ;
    private $nombre;  
    private $rutaCompleta;
    private $nombreArchivo;
    private $filaActual;
    private $defaulEstiloTexto;
    private $defaulEstiloTitulo;
    private $defaulEstiloGrillaCabecera;
    private $defaulEstiloGrillaFilas;
    private $defaulEstiloGrillaFooter;
    // - privadas propias del excel
    private $objPHPExcel;
    private $objSheetCurrent;
    private $objSheetIndex;
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Constructor">
    public function __construct($sid,$empresaId,$nombre,$rutaPlantilla) { 
        $this->sid = $sid;
        $this->empresaId = $empresaId;
        $this->nombre = $nombre;
        if(isset($_SERVER['HTTP_USER_AGENT'])){
           $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        }else{
           $this->userAgent = '';
        }       
        
//        if ($rutaPlantilla==''){            
            $this->objPHPExcel = new PHPExcel();
            $this->objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
            $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
//        }else{            
//            $this->objPHPExcel =$this->getPlantilla($rutaPlantilla);          
//        }
        
        $this->setParametrosServer();            
        
        $this->filaActual=1;        
        $this->defaulEstiloTexto=null;
        $this->defaulEstiloTitulo=array('fuente'=>'Arial','numero'=>'14','negrita'=>true,'subrayado'=>true,'cursiva'=>true,'color'=>'0E2D5F');
        $this->defaulEstiloGrillaCabecera=array('fuente'=>'Verdanna','numero'=>'10','negrita'=>true,'color'=>'FFFFFF','fondo'=>'4F81BD','alineadoHor'=>'center');
        $this->defaulEstiloGrillaFilas=array('fuente'=>'Calibri','numero'=>'10','color'=>'000000');
        $this->defaulEstiloGrillaFooter=array('fuente'=>'Calibri','numero'=>'10','negrita'=>true,'color'=>'FFFFFF','fondo'=>'4F81BD');        
        
        $this->objSheetCurrent = $this->objPHPExcel->getActiveSheet();
        $this->objSheetIndex = 0;
     }    
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Metodos Principales">
     
     /**
      * Metodo que genera el excel configurado y lo escribe en el servidor
      * 
      * @autor cheredia <cheredia@imaginatecperu.com>
      * @return type
      */
    public function getFormatoExcel() {        
//        //utilizar formulas
//        $objPHPExcel->getActiveSheet()->setCellValue('B16', '=SUM(B13:B15)');
        
        // Activamos para que al abrir el excel nos muestre la primer hoja
        $this->objPHPExcel->setActiveSheetIndex(0);

        // Guardamos el archivo, en este caso lo guarda con el mismo nombre del php
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save(dirname(__FILE__).$this->rutaCompleta);
        //var_dump($this->nombreArchivo);
        return $this->nombreArchivo;
    }
    
    // <editor-fold defaultstate="collapsed" desc="Metodos add Objetos (Configuracion)">
    /**
     * Metodo que inserta una imagen en la hoja de excel
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $ruta
     * @param type $posicion
     */
    public function addImagen($ruta,$posicion=null) {
        $objDrawing = new PHPExcel_Worksheet_Drawing();
//        $objDrawing->setName(' ');
//        $objDrawing->setDescription(' ');
        $objDrawing->setPath($ruta);
        $objDrawing->setCoordinates($this->objSheetCurrent->getCellByColumnAndRow($this->getPosXi($posicion), $this->getPosYi($posicion))->getCoordinate());
        $objDrawing->setWorksheet($this->objSheetCurrent);
    }
    
    /**
     * Metodo que inserta un texto en una posicion especifica y con un determinado formato
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $texto
     * @param type $posicion
     * @param type $estilo
     * @param type $ActualizarNroFila
     * @param type $aplicaMerge
     */
    public function addTexto($texto,$posicion=null,$estilo=null,$ActualizarNroFila=true,$aplicaMerge=true) {
        $x=$this->getPosXi($posicion);
        $y=$this->getPosYi($posicion);
        // Escribiendo los datos
        $this->objSheetCurrent->setCellValueByColumnAndRow($x,$y,$texto);        
        $this->aplicaEstilo($x,$y,$estilo);
        
        //Verificar si se Actualiza el Nro de Fila
        if ($ActualizarNroFila) $this->actualizaNroFilaActual($posicion);
        //Verificar si se Aplica Merge
        if ($aplicaMerge && !ObjectUtil::isEmpty($posicion))$this->objSheetCurrent->mergeCellsByColumnAndRow($x,$y,$this->getPosXf($posicion),$this->getPosYf($posicion));
    }
    
    /**
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $texto
     * @param type $posicion
     * @param type $estilo
     */
    public function addTitulo($texto,$posicion=null,$estilo=null) {          
        if (ObjectUtil::isEmpty($estilo)){//Si no se especifico Estilo, se establece uno por Defecto
            $estilo=$this->defaulEstiloTitulo;
        }        
        //Se escribe el texto
        $this->addTexto($texto,$posicion,$estilo);  
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @param type $estilo
     */
    public function addLinea($posicion=null,$estilo=null) {
        $this->actualizaNroFilaActual();
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $dtRows
     * @param type $header
     * @param type $footer
     * @param type $posicion
     * @param type $estiloGrilla
     * @param type $estiloHeader
     * @param type $estiloFooter
     * @return type
     */
    public function addGrilla($dtRows,$header=null,$footer=null,$posicion=null,$estiloGrilla=null,$estiloHeader=null,$estiloFooter=null) {
        if (ObjectUtil::isEmpty($dtRows)) return;                
        $x=$this->getPosXi($posicion);$i=$x;  
        $y=$this->getPosYi($posicion); 
        
        //Escribir la Cabecera
        if (ObjectUtil::isEmpty($header)){                  
            foreach ($dtRows[0] as $nameCol => $valueCol) {            
                $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$nameCol);        
                $this->aplicaEstilo($i,$y,$this->defaulEstiloGrillaCabecera);
                $this->objSheetCurrent->getColumnDimensionByColumn($i)->setAutoSize(true);
                $i++;
            }
        }else{
            foreach ($header as $valueCol) {            
                $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$valueCol['titulo']);        
                $this->aplicaEstilo($i,$y,$this->defaulEstiloGrillaCabecera);   
                $this->objSheetCurrent->getColumnDimensionByColumn($i)->setAutoSize(true);
                $i++;
            }
        }        
        
        //Escribir las Filas        
        $xi=$this->getPosXi($posicion);
        $yi=$this->getPosYi($posicion);
        $yf= $yi + count($dtRows);
        
        foreach ($dtRows as $row) {
            $i=$x;$y++;
           
            if (ObjectUtil::isEmpty($header)){ 
                $xf=count($row[0]);
                foreach ($row as $nameCol => $valueCol) {
                    $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$valueCol);        
                    //mod $this->aplicaEstilo($i,$y,$this->defaulEstiloGrillaFilas);   
                    $i++;
                }
            }else{
                $xf=count($header);
                foreach ($header as $valueCol) { 
                    $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$row[$valueCol['campo']]); 
                    if (array_key_exists('alineacion',$valueCol)){//Alineacion
                        $estiloCol=$this->defaulEstiloGrillaFilas;
                        switch ($valueCol['alineacion']) {
                            case 0:$estiloCol['alineadoHor'] ='left';break;
                            case 1:$estiloCol['alineadoHor'] ='center';break;
                            case 2:$estiloCol['alineadoHor'] ='right';break;
                            default:$estiloCol['alineadoHor'] ='left';break;
                        }
                    } 
                    //$this->aplicaEstilo($i,$y,$this->defaulEstiloGrillaFilas);   
                    $i++;
                }
            }                        
        }
        // Damos formato a la data
        $this->aplicaEstiloByRango($xi, $yi, $xf, $yf, $this->defaulEstiloGrillaFilas);
        
        //Escribir el Footer
        foreach ($footer as $row) {
            $i=$x;$y++;
            if (ObjectUtil::isEmpty($header)){ 
                foreach ($row as $nameCol => $valueCol) {
                    $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$valueCol);        
                    $this->aplicaEstilo($i,$y,$this->defaulEstiloGrillaFooter);   
                    $i++;
                }
            }else{
                foreach ($header as $valueCol) { 
                    $this->objSheetCurrent->setCellValueByColumnAndRow($i,$y,$row[$valueCol['campo']]); 
                    $estiloCol=$this->defaulEstiloGrillaFooter;
                    if (array_key_exists('alineacion',$valueCol)){//Alineacion
                        switch ($valueCol['alineacion']) {
                            case 0:$estiloCol['alineadoHor'] ='left';break;
                            case 1:$estiloCol['alineadoHor'] ='center';break;
                            case 2:$estiloCol['alineadoHor'] ='right';break;
                            default:$estiloCol['alineadoHor'] ='left';break;
                        }
                    } 
                    $this->aplicaEstilo($i,$y,$estiloCol);   
                    $i++;
                }
            }                        
        }
        
        $this->filaActual=$y+1;
        $this->filaActual=$y+1;
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $texto
     */
    public function addTituloHoja($texto){        
        $this->objSheetCurrent->setTitle($texto);// Nombramos la hoja
    }
    
    /**
     * @autor cheredia <cheredia@imaginatecperu.com>
     */
    public function addGrafico(){

    }
    
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Manejo de la Posicion">
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @return type
     */
    public function getNroFilaActual() {
        return $this->filaActual;
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $nroFilaActual
     */
    public function setNroFilaActual($nroFilaActual) {
        $this->filaActual=$nroFilaActual;
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $nroFilas
     */
    public function mueveFilaActual($nroFilas) {
        $this->filaActual += $nroFilas;
    }
    
    /**
     * @abstract Retorna la nueva posicion en bloque normalemente 
     * a partir de la siguiente fila del bloque actual
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return type
     */    
    public function getNuevaPos($posicion) {
        if (!ObjectUtil::isEmpty($posicion)){            
            $posicion['Yf']=$this->filaActual+($posicion['Yf']-$posicion['Yi']);
            $posicion['Yi']=$this->filaActual;
        }
        return $posicion;
    }
    
    /**
     * 
     * @abstract Crea una nueva hoja y la setea como actual
     * @author Christopher Heredia Lozada
     * @param String $titulo
     */
    public function addHoja($titulo = null){
        $this->objSheetIndex += 1;
        $this->objSheetCurrent = $this->objPHPExcel->createSheet($this->objSheetIndex);
        if (!ObjectUtil::isEmpty($titulo)){
            $this->addTituloHoja($titulo);
            $this->filaActual = 1;
        }
    }
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     */
    private function actualizaNroFilaActual($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Yf',$posicion)){
                $this->filaActual = $posicion['Yf'] + 1;
            }elseif (array_key_exists('Yi',$posicion)){
                $this->filaActual = $posicion['Yi'] + 1;
            }
        }else{
            //Si NO se indico la posicion        
            $this->filaActual++;
        }
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return int
     */
    private function getPosXi($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Xi',$posicion)) return $posicion['Xi'];            
        }else{               
            return 0;
        }
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return int
     */
    private function getPosXf($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Xf',$posicion)) return $posicion['Xf'];            
        }else{               
            return 0;
        }
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return type
     */
    private function getPosYi($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Yi',$posicion)) return $posicion['Yi'];            
        }else{               
            return $this->filaActual;
        }
    }
    
    /**
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return type
     */
    private function getPosYf($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Yf',$posicion)) return $posicion['Yf'];            
        }else{               
            return $this->filaActual;
        }
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return type
     */
    private function getStrPosIni($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Xi',$posicion) && array_key_exists('Yi',$posicion)){
                return '' . $posicion['Xi'] . $posicion['Yi'];
            }
        }else{
            //Si NO se indico la posicion        
            return 'A'.$this->filaActual;
        }
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $posicion
     * @return type
     */
    private function getStrPosFin($posicion=null) {
        if (!ObjectUtil::isEmpty($posicion)){
            if (array_key_exists('Xf',$posicion) && array_key_exists('Yf',$posicion)){
                return '' . $posicion['Xf'] . $posicion['Yf'];
            }
        }else{
            //Si NO se indico la posicion        
            return 'A'.$this->filaActual;
        }
    }
    // </editor-fold>    
    
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Metodos Apoyo">   
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $x
     * @param type $y
     * @param type $estilo
     */
    private function aplicaEstilo($x,$y,$estilo=null) {
        //Aplicar los estilos
        if (!ObjectUtil::isEmpty($estilo)){
            foreach ($estilo as $key => $value) {
                switch ($key) {
                    case 'fuente':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setName($value);
                        break;
                    case 'negrita':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setBold($value);
                        break;
                    case 'numero':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setSize($value);
                        break;
                    case 'subrayado':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setUnderline($value);
                        break;
                    case 'cursiva':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setItalic($value);
                        break;
                    case 'color':
                        $color=new PHPExcel_Style_Color();
                        $color->setRGB($value);
                        $this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFont()->setColor($color);
                        break;
                    case 'fondo':
//                        $color=new PHPExcel_Style_Color();
//                        $color->setRGB($value);                        
//                        $this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//                        $this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getFill()->setStartColor((new PHPExcel_Style_Color())->setRGB($value));
                        
                        $this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->applyFromArray(
                            array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => $value)))
                        );
                        break;
                    case 'alineadoHor':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getAlignment()->setHorizontal($value);
                        break;
                    case 'alineadoVer':$this->objSheetCurrent->getStyleByColumnAndRow($x,$y)->getAlignment()->setVertical($value);
                        break;
                    default:
                        break;
                }
            }
        }
    }    
    
    /**
     * 
     * @autor Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     * @param t $x
     * @param type $y
     * @param type $estilo
     */
    private function aplicaEstiloByRango($xi,$yi, $xf, $yf,$estilo=null) {
        //Aplicar los estilos
        $xi = PHPExcel_Cell::stringFromColumnIndex($xi);
        $xf = PHPExcel_Cell::stringFromColumnIndex($xf);
        
        if (!ObjectUtil::isEmpty($estilo)){
            foreach ($estilo as $key => $value) {
                //$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->applyFromArray($style);
                switch ($key) {
                    case 'fuente':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setName($value);
                        break;
                    case 'negrita':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setBold($value);
                        break;
                    case 'numero':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setSize($value);
                        break;
                    case 'subrayado':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setUnderline($value);
                        break;
                    case 'cursiva':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setItalic($value);
                        break;
                    case 'color':
                        $color=new PHPExcel_Style_Color();
                        $color->setRGB($value);
                        $this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getFont()->setColor($color);
                        break;
                    case 'fondo':
                        $this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->applyFromArray(
                            array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => $value)))
                        );
                        break;
                    case 'alineadoHor':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getAlignment()->setHorizontal($value);
                        break;
                    case 'alineadoVer':$this->objSheetCurrent->getStyle($xi.yi.':'.$xf.yf)->getAlignment()->setVertical($value);
                        break;
                    default:
                        break;
                }
            }
        }
    }  
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Default">
    
    /**
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $param
     */
    private function getPlantilla($rutaPlantilla) {
        // Read from Excel2007 (.xlsx) template
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        return $objReader->load($rutaPlantilla);
        /** at this point, we could do some manipulations with the template, but we skip this step */
        // Export to Excel2007 (.xlsx)
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));        
    }
    
    /**
     * 
     * @autor cheredia <cheredia@imaginatecperu.com>
     * @param type $params
     */
    public function setParams($params)    {
        $this->urlParams = '';
        $this->arrayParams = $params;
        foreach ($params as $key => $value) {
            if($this->urlParams === '')
                $this->urlParams = "$key=$value";
            else
                $this->urlParams .= "&$key=$value";
        }
    }   
    
    /**
     * @autor cheredia <cheredia@imaginatecperu.com>
     */
    private function setParametrosServer() {
        ob_start();           
        $content = ob_get_clean();
        set_time_limit($this->timeLimit);
        $cookie = Configuraciones::COOKIE_NAME_SID."=".$this->sid;
        ob_get_clean();
        
        $ruta = self::path.'empresa-'.$this->empresaId;
        if(!file_exists(dirname(__FILE__).$ruta)) mkdir(dirname(__FILE__).$ruta, 0777);
        $nombreArchivo = $this->nombre.$this->arrayParams['id'].'_'.date('YmdHis').'.xlsx';
        $nombreArchivo = str_replace('|','_', $nombreArchivo);
        
        $this->rutaCompleta = $ruta.'/'.$nombreArchivo;
        $this->nombreArchivo=$nombreArchivo;
    }
    // </editor-fold>
}

?>
