<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseException
 *
 * @author CHL007
 */
class BaseException extends Exception { 
    private $modal = true;
    protected $titulo = "";
    protected $tipo = null;
    /**
     * @param type $code : Viene ha ser la clave del idiomacontenido
     **/
    public function __construct($message, $modal = true){
        $this->message = $message;
        $this->modal = $modal;
    }
    
    /**
     * Obtiene las concatenaciones
     * @return array
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public function getConcatenations(){
        return $this->concatenations;
    }
    public function getTitulo(){
        return $this->titulo;
    }
    public function getTipo(){
        return $this->tipo;
    }
    public function getModal(){
        return $this->modal;
    }
}
