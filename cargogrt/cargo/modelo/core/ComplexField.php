<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require 'Enumeraciones.php';
/**
 * Este metodo se encargara de guardar datos como correo y telefonos tipificados
 * a manera de JSON dentro de un campo String
 * 
 */
class ComplexField {
    //put your code here
    var $e;
    var $enums;
    var $decodata; //Mantiene el arreglo decodificado
    
    /**
     * 
     * @param type $tipo Esta asociado al Enum y puede ser por Ej. TipoTelefono, TipoCorreo
     * @param type $jdata Es el dato en el campo Telefono, Correo, etc
     */
    function __construct($keyTipo, $jdata) {
        $this->e = new Enumeraciones(); 
        //Obtengo los enums correspondientes a los tipos de datos que contendra el campo
        $this->enums = $this->e->getEntityByKey('name', $keyTipo);
        if(strlen($jdata)>0){
            $this->decodata = json_decode($jdata, TRUE);
        }
    }
    
    /**
     * Metodo que agrega al arreglo un dato mas
     * @param type $tipo
     * @param type $value
     */
    function add($key, $value){
        $this->decodata[$key] = $value;
    }
    
    function delete($key){
        unset($this->decodata[$key]);
    }
    
    function getEncodeData(){
        return json_encode($this->decodata);
    }
}

?>
