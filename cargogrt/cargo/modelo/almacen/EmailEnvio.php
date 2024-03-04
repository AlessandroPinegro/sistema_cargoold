<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class EmailEnvio extends ModeloBase {

    /**
     * 
     * @return EmailEnvio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerPendientesEnvio() {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_email_envio");
        return $this->commandGetData();
    }

    public function obtenerPendientesEnvioXId($id) {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_email_envioXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function actualizarEstadoEnviado($id) {
        $this->commandPrepare("sp_email_envio_actualizarEstadoEnviado");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function insertarEmailEnvio($destinatario, $asunto, $cuerpo, $estado, $usuarioId, $urlEmail = null, $nombreArchivo = null) {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_email_envio_insertar");
        $this->commandAddParameter(":vin_destinatario", $destinatario);
        $this->commandAddParameter(":vin_asunto", $asunto);
        $this->commandAddParameter(":vin_cuerpo", $cuerpo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_url_email", $urlEmail);
        $this->commandAddParameter(":vin_nombre_archivo", $nombreArchivo);
        return $this->commandGetData();
    }

    public function actualizarIntentos($id) {
        $this->commandPrepare("sp_email_envio_actualizarIntentos");
        $this->commandAddParameter(":vin_email_envio_id", $id);

        return $this->commandGetData();
    }

    public function obtenerIntentosXId($id) {
        $this->commandPrepare("sp_email_envio_obtenerIntentosXId");
        $this->commandAddParameter(":vin_email_envio_id", $id);

        return $this->commandGetData();
    }

    public function logActividad($id, $contenido) {
        $this->commandPrepare("sp_email_envio_logActividad");
        $this->commandAddParameter(":vin_email_envio_id", $id);
        $this->commandAddParameter(":vin_contenido", $contenido);

        return $this->commandGetData();
    }

    public function actualizarEstadoError($id) {
        $this->commandPrepare("sp_email_envio_actualizarEstadoError");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

}
