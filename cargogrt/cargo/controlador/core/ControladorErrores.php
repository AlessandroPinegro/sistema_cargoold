<?php

/**
 * Description of ControladorErrores
 *
 * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
 */
//require_once __DIR__ . '/../../modelo/sbssys/IdiomaContenido.php';
require_once __DIR__ . '/../../util/ObjectUtil.php';

class ControladorErrores {

    const CURRENT_CULTURE = 'es_pe';

    public $has_error_php = FALSE;
    public $has_error = FALSE;
    private $error_tipo;
    private $last_error;
    private $titulo;
    private $modal = true;
    private $trace_error;
    private $file_error;

    public function getTitulo() {
        return $this->titulo;
    }

    public function getModal() {
        return $this->modal;
    }

    public function getError() {
        return $this->last_error;
    }

    public function getErrorTipo() {
        return $this->error_tipo;
    } 

    public function getErrorTrace() {
        $dataError = array();
        foreach ($this->trace_error as $index => $item) {
            $dataError[$index] = $item;
            $dataError[$index]['file'] = pathinfo($item["file"])['basename'];
        }
        return $dataError;
    }

    public function getErrorFile() {
        return $this->file_error;
    }

    public function getErrorFileTrace() {

        $error = new stdClass();
        $error->accion = $this->accion;
        $error->tag = $this->tag;
        $error->title = $this->getTitulo();
        $error->message = $this->getError();
        $error->file = $this->getErrorFile();
        $error->trace = ((array) $this->trace_error);
        $nombreArchivoLog = date("YmdHis") . str_replace(' ', '', microtime());
        file_put_contents(__DIR__ . "/../../util/error_file/" . $nombreArchivoLog . ".txt", json_encode((array) $error));
        return $nombreArchivoLog;
    }

// <editor-fold defaultstate="collapsed" desc="Metodos principales">
    public function __construct() {
        //Inicializo las variables de errores
        $this->has_error = FALSE;
        $this->last_error = "";
    }

    /**
     * Metodo encargado de preparar el mensaje de error hacia el Usuario
     * 
     * @param string|integer $value Cadena de error o clave del error
     * @param IdiomaContenidoTipo $type Es el tipo de Error definido en el sistema
     * 
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     * 
     */
    public function responseError($error_object, $type) {
        $this->has_error = TRUE;
        $this->error_tipo = $type;
        $this->titulo = (method_exists($error_object, "getTitulo")) ? $error_object->getTitulo() : "Incidencia";
        $this->last_error = $error_object->getMessage();
        $this->trace_error = $error_object->getTrace();
        $this->file_error = pathinfo($error_object->getFile())['basename'] . " line: " . $error_object->getLine();
        $this->modal = (method_exists($error_object, "getModal")) ? $error_object->getModal() : true;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Métodos de apoyo">
// </editor-fold>
}

?>
