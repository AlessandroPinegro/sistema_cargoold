<?php
include_once __DIR__.'/BaseException.php';
/**
 * Excepción Error Personalizado usado en el Sistema
 *
 * @author Christopher Heredia Lozada
 */
class ErrorPersException extends BaseException { 
    public function __construct($message, $modal = true){
        parent::__construct($message, $modal);
        $this->tipo = 2;
        $this->titulo = "Error";
    }
}
?>
