<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class Agencia extends ModeloBase {

    /**
     * 
     * @return Agencia
     */
    static function create() {
        return parent::create();
    }

    public function obtenerAgenciaXEmpresaId($empresaId) {
        $this->commandPrepare("sp_agencia_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function getDataAgenciaUbigeo() {
        $this->commandPrepare("sp_agencia_obtenerAgenciaUbigeo");
        return $this->commandGetData();
    }

    public function obtenerAgenciaXPerfilId($perfilId) {
        $this->commandPrepare("sp_agencia_obtenerXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function getAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $usuarioId = 1) {
        $this->commandPrepare("sp_persona_obtenerXCriterios");
        $this->commandAddParameter(":vin_nombres", $nombres);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo_persona", $tipoPersona);
        $this->commandAddParameter(":vin_clase_persona", $clasePersona);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function getCantidadAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $usuarioId) {
        $this->commandPrepare("sp_persona_contador_consulta");
        $this->commandAddParameter(":vin_nombres", $nombres);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo_persona", $tipoPersona);
        $this->commandAddParameter(":vin_clase_persona", $clasePersona);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function getDataAgencia() {
        $this->commandPrepare("sp_agencia_getAll");
        return $this->commandGetData();
    }

    public function obtenerAgenciaActivas() {
        $this->commandPrepare("sp_agencia_obtenerAgenciaActiva");
        return $this->commandGetData();
    }

    public function getDataConductores() {
        $this->commandPrepare("sp_choferes_getAll");
        return $this->commandGetData();
    }

    public function getDataConductoresxDocumentoTipo($origin_id) {
        $this->commandPrepare("sp_choferes_documento_getAll");
        $this->commandAddParameter(":vin_agencia", $origin_id);
        return $this->commandGetData();
    }

    public function getDataAgenciaUser($usuarioId) {
        $this->commandPrepare("sp_agenciaUser_getAll");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function getDataAgenciaDespacho($id_agencia) {
        $this->commandPrepare("sp_agencia_despacho_getAll");
        $this->commandAddParameter(":vin_id", $id_agencia);
        return $this->commandGetData();
    }

    public function getDataAgenciaZona() {
        $this->commandPrepare("sp_agencia_zona_getAll");
        return $this->commandGetData();
    }

    public function getDataZona() {
        $this->commandPrepare("sp_zona_getAll");
        return $this->commandGetData();
    }

    public function getDataComboUbigeo() {
        $this->commandPrepare("sp_ubigeo_obtenerActivos");
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado, $id_usu_ensesion) {
        $this->commandPrepare("sp_agencia_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function cambiarEstadoZona($id_estado, $id_usu_ensesion) {
        $this->commandPrepare("sp_agenciaZona_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function insertAgencia($codigo, $descripcion, $direccion, $ubigeo, $usuarioCreacion, $estado) {
        $this->commandPrepare("sp_agencia_insert");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_ubigeo", $ubigeo);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_estado", $estado);

        return $this->commandGetData();
    }

    public function insertAgencias($integracionId, $codigo, $nombre, $direccion, $usuarioCreacion) {
        $this->commandPrepare("sp_agencia_integracion_insert");
        $this->commandAddParameter(":vin_integracion_id", $integracionId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_usuarioCreacion", $usuarioCreacion);

        return $this->commandGetData();
    }

    public function insertAgenciaZona($agenciaId, $zona_descripcion, $zona_creacion, $estado,$id_ubigeo) {
        $this->commandPrepare("sp_zona_insert");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_zona_descripcion", $zona_descripcion);
        $this->commandAddParameter(":vin_zona_creacion", $zona_creacion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_id_ubigeo", $id_ubigeo);

        return $this->commandGetData();
    }

    public function deleteAgencia($id, $id_usu_ensesion) {
        $this->commandPrepare("sp_agencia_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function deleteZona($id, $id_usu_ensesion) {
        $this->commandPrepare("sp_zona_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function getAgencia($id) {
        $this->commandPrepare("sp_agencia_getById");
        $this->commandAddParameter(":vin_agencia_id", $id);
        return $this->commandGetData();
    }

    public function getZona($id) {
        $this->commandPrepare("sp_zona_getById");
        $this->commandAddParameter(":vin_zona_id", $id);
        return $this->commandGetData();
    }

    public function updateAgencia($id, $age_nombre, $age_descripcion, $age_direccion, $ubigeoId, $estado, $usuarioId, $organizadorDefectoId, $organizadorDefectoRecepcionId) {
        $this->commandPrepare("sp_agencia_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_agencia_nombre", $age_nombre);
        $this->commandAddParameter(":vin_agencia_descripcion", $age_descripcion);
        $this->commandAddParameter(":vin_agencia_direccion", $age_direccion);
        $this->commandAddParameter(":vin_ubigeo_id", $ubigeoId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_sesion_id", $usuarioId);
        $this->commandAddParameter(":vin_organizador_defecto_id", $organizadorDefectoId);
        $this->commandAddParameter(":vin_organizador_defecto_recepcion_id", $organizadorDefectoRecepcionId);

        return $this->commandGetData();
    }

    public function updateZona($id, $agenciaId, $zona_descripcion, $usuarioId, $estado,$id_ubigeo) {
        $this->commandPrepare("sp_zona_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_zona_descripcion", $zona_descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
           $this->commandAddParameter(":vin_id_ubigeo", $id_ubigeo);
        return $this->commandGetData();
    }

    public function obtenerAgenciaxIdIntegracion($agenciaDestinoId) {
        $this->commandPrepare("sp_agencia_obtener_id");
        $this->commandAddParameter(":vin_agencia_integracion_id", $agenciaDestinoId);
        return $this->commandGetData();
    }

}
