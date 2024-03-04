<?php

/**
 * Description of DateUtil
 *
 * @author Christopher
 */
class DateUtil {
    static public function formatearCadenaACadenaBD($cadena){
        if(empty($cadena))
        {
            return $cadena;
        }
        
        $fecha = DateTime::createFromFormat('d/m/Y', $cadena);
        //Error
        if ($fecha == false) {
            throw new WarningException("No se especificó un valor válido para Fecha");
        }
        return $fecha->format('Y-m-d');
    }
    static public function formatearFechaACadenaVw($fecha){
        
        return $fecha->format('d/m/Y');
    }
    
    /**
     * 
     * @param String $fechaBD 24/03/2012 17:45:12
     * @return String 24/03/2012
     * 
     */
    static public function formatearFechaBDAaCadenaVw($fechaBD){
        
        if(ObjectUtil::isEmpty($fechaBD))
        { 
            return $fechaBD;   
        }
        else
        {
            $fecha = date_create($fechaBD);   
            return date_format($fecha, 'd/m/Y');
        }
        
    }
    
    static public function formatearBDACadena($cadena){
        if(empty($cadena))
        {
            return $cadena;
        }
        $fecha = DateTime::createFromFormat('Y-m-d', substr($cadena, 0, 10));
        
        return $fecha->format('d/m/Y');
    }
}