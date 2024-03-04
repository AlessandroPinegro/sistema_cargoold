<?php
require_once __DIR__ . '/BaseException.php';
/**
 * Excepción Critica del Sistema. Esta excepción fuerza el cierre de la sesión
 *
 * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
 * @author cheredia <cheredia@imaginatecperu.com>
 */
class CriticalException extends BaseException { 
    public function __construct($message, $modal = true){
        parent::__construct($message, $modal);
        $this->tipo = 0;
        $this->titulo = "Error crítico";
    }
}

?>
