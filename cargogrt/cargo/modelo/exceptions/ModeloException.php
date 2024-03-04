<?php
include_once __DIR__.'/BaseException.php';
/*
 * @author: Christopher Heredia
 * Lanza un exception personalizado en el caso de que ocurra un error en el modelo base
 */
class ModeloException extends BaseException { 
    public function __construct($message, $modal = true){
        parent::__construct($message, $modal);
        $this->tipo = null;
        $this->titulo = "Error interno";
    }
}
?>