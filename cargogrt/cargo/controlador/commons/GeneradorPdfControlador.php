<?php

require_once __DIR__ . '/../../vistas/libs/html2pdf/html2pdf.class.php';
require_once __DIR__ . '/generadorPdf/PeticionCurl.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modelo/enumeraciones/EstadoGenerico.php';
require_once __DIR__ . '/../../modelo/sbssys/Sesion.php';
require_once __DIR__ . '/../../modelo/sbssys/SesionDetalle.php';

/**
 * Clase que permite la exportacion de una plantilla html a PDF
 *
 * @author cheredia
 */
class GeneradorPdfControlador extends HTML2PDF{
    const path = '/../../public/';
    private $sid;
    private $empresaId;
    private $timeLimit = 120;
    private $urlParams = '';
    private $arrayParams = array();
    private $userAgent ;
    private $nombre;
    
    /**
     * Description of GeneradorPdfControlador
     */
     public function __construct($empresaId,$nombre,$time_zome,$usuario_id) {
         $this->empresaId = $empresaId;
         $this->nombre = $nombre;
         $this->time_zome = $time_zome;
         $this->usuario_id = $usuario_id;
         if(isset($_SERVER['HTTP_USER_AGENT']))
            $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
         else
            $this->userAgent = '';
     }
     
    static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new ControladorBase();
        }
        return self::$instance;
    }
    
     public function setTimeLimit($seconds)
    {
         set_time_limit($seconds);
    }
    
     public function setParams($params)
    {
        $this->urlParams = '';
//        if(!array_key_exists('class_key', $params))
//            $AS= '';   
        $this->arrayParams = $params;
        foreach ($params as $key => $value) {
            if($this->urlParams === '')
                $this->urlParams = "$key=$value";
            else
                $this->urlParams .= "&$key=$value";
        }
    }
    
    public function outputFile()
    {
        ob_start();
        try
        {   
            $this->sid = md5(date('YmdHis', time()).'importador.pdf');
            $sesion = new Sesion();
            $sesion->setProperty('usuario_id', $this->usuario_id);
            $sesion->setProperty('sid', $this->sid);
            $sesion->setProperty('direccion_ip', $_SERVER['SERVER_ADDR']);
            $sesion->setProperty('navegador', $this->userAgent);
            $sesion->setProperty('nombre_cookie', Configuraciones::COOKIE_NAME_SID);
            $sesion->setProperty('fecha_inicio', date('Y-m-d H:i:s', time()));
            $sesion->setProperty('fecha_ultimoacceso', date('Y-m-d H:i:s', time()));
            $sesion->setProperty('estado', EstadoGenerico::disponible);
            $sesion->setProperty('zona_horaria',$this->time_zome );
            
            $detalle = new SesionDetalle();
            // seteamos los parametros del detalle de la sesion 
            $detalle->setProperty('data', preg_replace("/[\n|\r|\n\r]/i", '', print_r($this->urlParams, TRUE)));
            $detalle->setProperty('fecha_creacion', date('Y-m-d H:i:s', time()));
            // agregamos el detalle a la sesion
            $sesion->addRelated($detalle);

            // ahora RECIEN GUARDAMOS
            $sesion->save();
            
            $content = ob_get_clean();
            set_time_limit($this->timeLimit);
            $cookie = Configuraciones::COOKIE_NAME_SID."=".$this->sid;
            //utilizamos la libreria CURL para obtener el codigo html de la plantilla, ya que se utiliza smarty para setear los labels
            $objCurl = new PeticionCurl(Configuraciones::url_base."vistas/FrontController.php?".$this->urlParams,$this->userAgent,$cookie);
            
            $curl = $objCurl->inicializarCurlGet($cookie);
            $result = $objCurl->ejecutarCurl($curl);
            $objCurl->cerrarCurl($curl);
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 0);
            
            ob_get_clean();
            $ruta = self::path.'empresa-'.$this->empresaId;
            if(!file_exists(dirname(__FILE__).$ruta))
                mkdir(dirname(__FILE__).$ruta, 0777);
            
            $nombreArchivo = $this->nombre.$this->arrayParams['id'].'_'.date('YmdHis').'.pdf';
            $nombreArchivo = str_replace('|','_', $nombreArchivo);
            $rutaCompleta = $ruta.'/'.$nombreArchivo;
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($result);
            $html2pdf->Output(dirname(__FILE__).$rutaCompleta,'F');
            
            $cerrarSesion = new Sesion();
            $cerrarSesion->setProperty('estado', EstadoGenerico::eliminado);
            $cerrarSesion->whereAdd(Array(ElementoSQL::first_alias => 'tab',
                            ElementoSQL::first_col => "sid",
                            ElementoSQL::val => $this->sid))
                            ->updateRecord();
            
            return $nombreArchivo;
        }
        catch(HTML2PDF_exception $e) {
            $cerrarSesion = new Sesion();
            $cerrarSesion->setProperty('estado', EstadoGenerico::eliminado);
            $cerrarSesion->whereAdd(Array(ElementoSQL::first_alias => 'tab',
                            ElementoSQL::first_col => "sid",
                            ElementoSQL::val => $this->sid))
                            ->updateRecord();
             return $e;
        }
    }
}
?>
