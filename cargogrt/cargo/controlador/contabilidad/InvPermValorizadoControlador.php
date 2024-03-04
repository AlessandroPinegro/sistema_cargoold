<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/InvPermValorizadoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';
//require_once __DIR__ . '/../../util/ImportacionExcel.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel/IOFactory.php';


class InvPermValorizadoControlador extends ControladorBase {

    public function listar() {
        return InvPermValorizadoNegocio::create()->listar();
    }              
    
    public function generarLibro(){          
        $this->setTransaction();        
        
        $file = $this->getParametro("file");
        $usuarioId = $this->getUsuarioId();
        $anio = $this->getParametro("anio");
        $mes = $this->getParametro("mes");
        // hora actual
        $fechaActual = new DateTime();
        $formatoFechaActual = $fechaActual->format("Ymdhis");
        
        $decode = Util::base64ToImage($file);
        
        $archivoNombre = "$anio$mes_$formatoFechaActual.xls";
        $direccion = __DIR__."/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, $decode);
        
        return InvPermValorizadoNegocio::create()->genera($direccion, $archivoNombre, $anio, $mes, $usuarioId);
    }
    
    public function generarExcel(){      
        set_time_limit(1200);
        $this->setTransaction();  
        $anio = $this->getParametro("anio");
        $mes = $this->getParametro("mes");
        $usuarioId = $this->getUsuarioId();
        
        return InvPermValorizadoNegocio::create()->generarExcel($anio,$mes,$usuarioId);        
    }
    
    public function generarResumen(){
        $anio = $this->getParametro("anio");
        return InvPermValorizadoNegocio::create()->generResumen($anio);
    }

}