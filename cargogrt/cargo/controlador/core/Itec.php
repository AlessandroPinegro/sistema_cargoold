<?php

/**
 * ControladorEnrutador: 
 *      Se encarga de centralizar los llamados al controlador, 
 *      manejar los errores, tratarlos deacuerdo a tipificacion y 
 *      formatear el resultado según se requiera
 * @version 1.0
 * @author Christopher Heredia 
 */
require_once __DIR__ . '/../../modelo/exceptions/CriticalException.php';
require_once __DIR__ . '/../../modelo/exceptions/ErrorPersException.php';
require_once __DIR__ . '/../../modelo/exceptions/WarningException.php';
require_once __DIR__ . '/../../modelo/exceptions/InformationException.php';

require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../util/ObjectUtil.php';
require_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../util/DateUtil.php';

require_once __DIR__ . '/ControladorErrores.php';
require_once __DIR__ . '/ControladorParametros.php';
require_once __DIR__ . '/../../modelo/itec/Controlador.php';

require_once __DIR__ . '/../../modelo/itec/Usuario.php';

date_default_timezone_set("America/Lima");
$_execute_controller = Itec::getInstance();

class Itec {

// <editor-fold defaultstate="collapsed" desc="constantes y variables">

    const PARAM_SID = "sid";
    const FORMAT_JSON = "json_ajax";
    const FORMAT_JSON_DATATABLE = "json_datatable";
    const FORMAT_JSON_SELECT2 = "json_select2";
    const FORMAT_OBJECT = "object";
    const CURRENT_CULTURE = 'es_pe';
    const RESPONSE_ERROR = 'error';
    const RESPONSE_ERROR_PHP = 'error_php';
    const RESPONSE = 'response';

    /**
     *  $sid es el session id a utilizar para controlar la seguridad
     *  $params parametros adicionales pasados por el cliente
     *  $component_code nombre de la vista donde contendra la url_base y el nombre_archivo a consumir
     *  $accion nombre de la funcion que se va a consumir
     */
    // Variables privadas 
    static private $instance = null;
    private $url_base = null;
    private $nombre_archivo = null;
    private $requestType = self::FORMAT_JSON;
    private $errors_catch = null;
    private $error = null;
    private $param = null;
    private $sid;
    private $mensaje_emergente;
    private $controlador;

// </editor-fold>
// <editor-fold  desc="funciones principales">

    /**
     * En el caso que se haga un llamado ajax 
     * se retornará un JSON impreso en la pagina 
     * y en el caso de que sea por PHP (caso Smarty)
     * se le retornará la instancia para que pueda trabajar
     * en la variable $_executeController
     * @return Itec
     * 
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     */
    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new Itec();
        }
        return self::$instance;
    }

    /**
     * Constructor de la la clase ControladorEnrutador
     * @return ControladorEnrutador
     * 
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     */
    public function __construct() {
        $this->error = new ControladorErrores();
        $this->param = new ControladorParametros();
        // Verificamos si la solicitud al Controlador es a partir de Ajax
        $this->requestType = $this->param->getRequestType();
        $this->getResponse();
    }

    /**
     * Funcion que se encarga de obtener la respuesta para la vista segun lo solicitado, 
     * enrutando al controlador y funcion que corresponda
     * 
     * @param array $params
     * @return Object, array
     * 
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     */
    public function getResponse() {
        try {
            $response = NULL;

            $this->verificaSesion();
            $this->getControlador(); // Seteamos las variables de componente
            require_once __DIR__ . "/../$this->url_base$this->nombre_archivo.php";
            // validamos que la funcion exista 
            if (!method_exists($this->nombre_archivo, $this->param->accion))
                throw new ErrorPersException("No existe la función en la clase específica"); // No existe la acción específica en el controlador
            $class_name = $this->nombre_archivo;
            $accion = $this->param->accion;
            $this->controlador = new $class_name();
            $this->controlador->params = $this->param->getParametros();
            $response = $this->controlador->$accion();
            $this->controlador->setCommitTransaction();
            $this->mensaje_emergente = $this->controlador->getMensajeEmergente();
            $this->controlador->clearConnection();

            $this->setResponse($response);
            $this->controlador = null;
            // Si existe error, hacemos rollback por si hay alguna transaccion activa y posteriormente
            // preparamos el mensae de error y guardamos todo el objeto del error catch
        } catch (CriticalException $cerror) {
            $this->setException($cerror);
        } catch (WarningException $werror) {
            $this->setException($werror);
        } catch (ErrorPersException $eerror) {
            $this->setException($eerror);
        } catch (InformationException $ierror) {
            $this->setException($ierror);
        } catch (ModeloException $emodelo) {
            $this->setException($emodelo, -1);
        } catch (\ErrorException $eerrorex) {
            $this->setException($eerrorex, -2);
        } catch (\Exception $egeneral) {
            $this->setException($egeneral, -3);
        }
    }

    public function __destruct() {
        self::$instance = NULL;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="funciones-de-apoyo"> 
    private function setResponse($response) {
        // Se retorna a la vista según el formato que se ha solicitado 
        switch ($this->requestType) {
            case self::FORMAT_JSON:
            case self::FORMAT_JSON_SELECT2:
                $this->formatJSONAjax($response);
                break;
            case self::FORMAT_JSON_DATATABLE:
                $this->formatJSONDataTable($response);
                break;
//            case self::FORMAT_JSON_SELECT2:
//                $this->formatJSONSelect2($response);
//                break;
        }
    }

    private function setException($error_object, $error_type = null) {
        if (ObjectUtil::isEmpty($error_type)) {
            $error_type = $error_object->getTipo();
        }
        if (!ObjectUtil::isEmpty($this->controlador)) {
            $this->controlador->setRollbackTransaction();
        }
        $this->error->responseError($error_object, $error_type);
        $this->errors_catch = $error_object;
        $this->setResponse(NULL);
        $this->controlador = null;
    }

// <editor-fold defaultstate="collapsed" desc="functiones-de-salida">

    /**
     * imprime el objeto obtenido
     */
    private function formatJSONAjax($response) {
        $newresponse = array();
        if (!$this->error->has_error) {
            // Si todo esta OK
            $newresponse['status'] = "ok";
            $newresponse[Configuraciones::PARAM_ACCION_NAME] = $this->param->accion;
            $newresponse[Configuraciones::PARAM_TAG] = $this->param->tag;
            $newresponse['data'] = $response;
        } else {
            // Si se presentó un error
            $newresponse['status'] = "error";
            $newresponse[Configuraciones::PARAM_ACCION_NAME] = $this->param->accion;
            $newresponse[Configuraciones::PARAM_TAG] = $this->param->tag;
            $newresponse['title'] = $this->error->getTitulo();
            $newresponse['message'] = $this->error->getError();
            $newresponse['file'] = $this->error->getErrorFile();
            if (Configuraciones::FLAG_TRACE) {
                $newresponse['trace'] = $this->error->getErrorFileTrace();
            }

            $newresponse['modal'] = $this->error->getModal();
            $newresponse['type'] = $this->error->getErrorTipo();
        }
        if (!ObjectUtil::isEmpty($this->mensaje_emergente)) {
            $newresponse[Configuraciones::RESPONSE_MENSAJE_EMERGENTE] = $this->mensaje_emergente;
        }
        $buff = json_encode($newresponse);
        $contentType = "application/json; charset=utf-8";
        header("Content-Type: {$contentType}");
        header("Content-Size: " . strlen($buff));
        echo $buff;
    }

    /**
     * imprime el objeto obtenido
     */
    private function formatJSONDataTable($response) {
        if ($this->error->has_error) {
            // Si se presentó un error
            $newresponse['status'] = "error";
            $newresponse[Configuraciones::PARAM_ACCION_NAME] = $this->param->accion;
            $newresponse[Configuraciones::PARAM_TAG] = $this->param->tag;
            $newresponse['title'] = $this->error->getTitulo();
            $newresponse['message'] = $this->error->getError();
            $newresponse['modal'] = $this->error->getModal();
            $newresponse['file'] = $this->error->getErrorFile();
            if (Configuraciones::FLAG_TRACE) {
                $newresponse['trace'] = $this->error->getErrorFileTrace();
            }
            $newresponse['type'] = $this->error->getErrorTipo();
            $response = $newresponse;
        }
        if (!ObjectUtil::isEmpty($this->mensaje_emergente)) {
            $newresponse[Configuraciones::RESPONSE_MENSAJE_EMERGENTE] = $this->mensaje_emergente;
        }
        $buff = json_encode($response);
        $contentType = "application/json; charset=utf-8";
        header("Content-Type: {$contentType}");
        header("Content-Size: " . strlen($buff));
        echo $buff;
    }

    private function formatJSONSelect2($response) {
        if ($this->error->has_error) {
            // Si se presentó un error
            $newresponse['status'] = "error";
            $newresponse[Configuraciones::PARAM_ACCION_NAME] = $this->param->accion;
            $newresponse[Configuraciones::PARAM_TAG] = $this->param->tag;
            $newresponse['title'] = $this->error->getTitulo();
            $newresponse['message'] = $this->error->getError();
            $newresponse['modal'] = $this->error->getModal();
            $newresponse['file'] = $this->error->getErrorFile();
            if (Configuraciones::FLAG_TRACE) {
                $newresponse['trace'] = $this->error->getErrorFileTrace();
            }
            $newresponse['type'] = $this->error->getErrorTipo();
            $response = $newresponse;
        }
        if (!ObjectUtil::isEmpty($this->mensaje_emergente)) {
            $newresponse[Configuraciones::RESPONSE_MENSAJE_EMERGENTE] = $this->mensaje_emergente;
        }
        $buff = json_encode($response);
        $contentType = "application/json; charset=utf-8";
        header("Content-Type: {$contentType}");
        header("Content-Size: " . strlen($buff));
        echo $buff;
    }

// </editor-fold>

    /**
     * valida el nombre de la clase
     * @param String $class_name
     * @return boolean
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    private static function validaClassName($class_name) {
        // validamos que el nombre de la clase sea correcta...
        if (isset($class_name)) {
            if (is_string($class_name) && !(is_array($class_name) || is_object($class_name))) {
                if (strlen($class_name) < 101 && str_word_count($class_name) == 1) {
                    if (strpos($class_name, "=") === FALSE) {
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    /**
     * Valido que la sesión de la petición sea correcta 
     * y obtengo el usuario, su cultura y su zona horaria
     */
    private function verificaSesion() {

        if (!$this->param->workflow) {
            // Obtengo la cookie pasada por la vista y valido si coincide con la de mi browser
            $this->sid = $this->param->getCookieParam();
            $cod_ad = Util::desencripta($this->sid);

            $UsuBD = Usuario::create()->getUsuarioID($cod_ad);
            $idUsuBD = $UsuBD[0]["id"];
            if ($idUsuBD <= 0) {
                $this->verificaSessionError();
            } else {
                if ($this->param->accion !== Configuraciones::PARAM_ACCION_LOGIN) {
                   $sesion = Usuario::create()->validarSesionPorUsuario($idUsuBD, $this->param->getToken());
                   if (ObjectUtil::isEmpty($sesion)) {
                       $this->verificaSessionError();
                   }
                }
            }
            $this->param->addParametro("usuario_id", $idUsuBD);
            $this->param->addParametro(Configuraciones::PARAM_COD_AD, $cod_ad);
        } else {
            $cod_ad = Util::desencripta($this->param->usuariox);

            $UsuBD = Usuario::create()->getUsuarioID($cod_ad);
            $idUsuBD = $UsuBD[0]["id"];
            if ($idUsuBD <= 0)
                $this->verificaSessionError();
            $this->param->addParametro("usuario_id", $idUsuBD);
            $this->param->addParametro(Configuraciones::PARAM_COD_AD, $cod_ad);
        }
    }

    private function verificaSessionError() {
//        Util::borrarCookie();
        throw new \CriticalException("Usuario no válido, vuelva a autenticarse.");
    }

    /**
     * Obtiene los parámetros necesarios del componente para la carga del controlador: url_controlador y nombre_controlador
     * Además aprobechamos para obtener su id
     * 
     * @author Christopher Heredia <cheredia@imaginatecperu.com>
     */
    private function getControlador() {
        if ($this->param->accion === "autenticarUsuario") {
            $data = array(array("id" => 1, "clase" => "UsuarioControlador", "url" => "almacen/", "estado" => 1));
        } else {
            //Obtenemos el controlador en base al id de opcion y el usuario logeado
            $data = Controlador::create()->getById($this->param->opcionId, $this->param->getParametro("usuario_id"));
        }
        if (ObjectUtil::isEmpty($data))
            throw new \WarningException("No tenemos inventariado el controlador especificado");

        $this->url_base = $data[0]["url"];
        $this->nombre_archivo = $data[0]["clase"];

        // validamos las variables obtenidas
        if ($this->validaClassName($this->nombre_archivo) == FALSE)
            throw new \WarningException("El nombre del archivo no es válido");

        // validamos que la url_base concatenado con el nombre_archivo exista
        if (!file_exists(__DIR__ . "/../$this->url_base$this->nombre_archivo.php"))
            throw new \ErrorPersException("No existe el archivo"); // No se pudo encontrar la ruta del archivo para la vista especifica
    }

    /**
     * Funcion utilizada para convertir el tiempo de la cokie a segundos
     * @param type $telapsed
     * @return type
     */
    private function convierteSegundos($telapsed) {
        return ($telapsed->y * 365 * 24 * 60 * 60) +
                ($telapsed->m * 30 * 24 * 60 * 60) +
                ($telapsed->d * 24 * 60 * 60) +
                ($telapsed->h * 60 * 60) +
                ($telapsed->i * 60) +
                $telapsed->s;
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="funciones-de-labels">

    /**
     * Función que obtiene las acciones de seguridad por:
     * * Usuario.
     * * Modulo 
     * * Controlador
     * * Espacio de trabajo
     * * Empresa
     * 
     * @return array | null
     */
//    public function getAccionesSeguridad() {
//        // 1. Verificamos que la solicitud tenga los campos necesarios para obtener 
//        //    dichas configuraciones por espacio de trabajo
//        $espacioTrabajoId = $this->controlador->getParametro(Configuraciones::PARAM_ESPACIOTRABAJO_ID);
//        $controlId = $this->control_id;
//        $componenteId = $this->controlador->getParametro(Configuraciones::PARAM_COMPONENTEBASE_ID);
//        $usuarioId = $this->usuario_id;
//        $modoId = $this->controlador->getParametro(Configuraciones::PARAM_MODO_ID);
//        $empresaId = $this->empresa_id;
//        $componenteinstancia_id = $this->controlador->getParametro(Configuraciones::PARAM_COMPONENTEINSTANCIA_ID);
//                
//        //$acciones = SeguridadNegocio::create()->getAcciones($empresaId, $espacioTrabajoId, $componenteId, $controlId, $usuarioId, $modoId, $this->usuario_tipo);
//        $acciones = SeguridadNegocio::create()->getAcciones($empresaId, $espacioTrabajoId, $componenteinstancia_id, $controlId, $usuarioId, $modoId, $this->usuario_tipo);
//        return $acciones;
//    }
// </editor-fold>
}

?>