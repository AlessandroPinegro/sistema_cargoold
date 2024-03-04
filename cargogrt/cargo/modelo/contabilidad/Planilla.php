<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Planilla extends ModeloBase {

    /**
     * 
     * @return Planilla
     */
    static function create() {
        return parent::create();
    }

    public function guardarPlaImportarArchivo($periodoId, $archivoNombre, $archivoUrl, $archivoTipo, $usuarioId) {
        $this->commandPrepare("sp_pla_importar_archivo_guardar");
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_nombre", $archivoNombre);
        $this->commandAddParameter(":vin_url", $archivoUrl);
        $this->commandAddParameter(":vin_tipo", $archivoTipo);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarPlaBoleta($importacionArchivoId, $personaId, $sistemaPensionesId, $diasTrabajados, $horasTrabajadas, $horasTotalTrabajadas, $horasExtras, $vacaciones, $licencia, $faltas, $remuneracionComputabilizable, $remuneracionBruta, $remuneracionNeta, $usuarioId) {
        $this->commandPrepare("sp_pla_boleta_guardar");
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $importacionArchivoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_sistema_pension_id", $sistemaPensionesId);
        $this->commandAddParameter(":vin_dias_efectivo_trabajados", $diasTrabajados);
        $this->commandAddParameter(":vin_horas_efectivas", $horasTrabajadas);
        $this->commandAddParameter(":vin_horas_total_efectivas", $horasTotalTrabajadas);
        $this->commandAddParameter(":vin_horas_extras", $horasExtras);
        $this->commandAddParameter(":vin_vacaciones", $vacaciones);
        $this->commandAddParameter(":vin_licencia", $licencia);
        $this->commandAddParameter(":vin_faltas", $faltas);
        $this->commandAddParameter(":vin_remuneracion_computabilizable", $remuneracionComputabilizable);
        $this->commandAddParameter(":vin_remuneracion_bruta", $remuneracionBruta);
        $this->commandAddParameter(":vin_remuneracion_neta", $remuneracionNeta);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarPlaCtsGratificacion($importacionArchivoId, $personaId, $meses, $dias, $usuarioId) {
        $this->commandPrepare("sp_pla_cts_gratificacion_guardar");
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $importacionArchivoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_meses", $meses);
        $this->commandAddParameter(":vin_dias", $dias);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarPlaBoletaParametro($boletaId, $parametroId, $monto, $usuarioId) {
        $this->commandPrepare("sp_pla_boleta_parametro_guardar");
        $this->commandAddParameter(":vin_pla_boleta_id", $boletaId);
        $this->commandAddParameter(":vin_pla_parametro_id", $parametroId);
        $this->commandAddParameter(":vin_monto", $monto);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarPlaCtsGratificacionParametro($ctsGratificacionId, $parametroId, $monto, $usuarioId) {
        $this->commandPrepare("sp_pla_cts_gratificacion_parametro_guardar");
        $this->commandAddParameter(":vin_pla_cts_gratificacion_id", $ctsGratificacionId);
        $this->commandAddParameter(":vin_pla_parametro_id", $parametroId);
        $this->commandAddParameter(":vin_monto", $monto);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPlaPametroXClasificacion($clasificacion) {
        $this->commandPrepare("sp_pla_parametro_obtenerXClasificacion");
        $this->commandAddParameter(":vin_clasificacion", $clasificacion);
        return $this->commandGetData();
    }

    public function obtenerCuentasContableXPlaImportacionArhivoId($importacionArchivoId, $tipoArchivo) {
        switch ($tipoArchivo * 1) {
            case 1:
                $this->commandPrepare("sp_pla_boleta_parametro_obtenerAsientoPlanilla");
                break;
            case 2:
            case 3:
                $this->commandPrepare("sp_pla_cts_gratificacion_parametro_obtenerAsiento");
                break;
        }
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $importacionArchivoId);
        return $this->commandGetData();
    }

    public function obtenerPlaImportarArchivo() {
        $this->commandPrepare("sp_pla_importar_archivo_obtener");
        return $this->commandGetData();
    }

    public function obtenerPlaImportarArchivoXId($id) {
        $this->commandPrepare("sp_pla_importar_archivo_obtenerXId");
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $id);
        return $this->commandGetData();
    }

    public function actualizarXId($id, $nombre, $url) {
        $this->commandPrepare("sp_pla_importar_archivo_actualizarXId");
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_url", $url);
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $id);
        return $this->commandGetData();
    }

    public function obtenerValoresXParametroIdXPlaImportacionArhivoId($importacionArchivoId, $parametroId) {
        $this->commandPrepare("sp_pla_parametro_obtenerXIdXPlaImportacionArchivoId");
        $this->commandAddParameter(":vin_id", $parametroId);
        $this->commandAddParameter(":vin_pla_importacion_archivo_id", $importacionArchivoId);
        return $this->commandGetData();
    }

}
