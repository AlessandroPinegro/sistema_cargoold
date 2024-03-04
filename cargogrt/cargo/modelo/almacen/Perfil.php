<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

class Perfil extends ModeloBase {

    /**
     * 
     * @return Perfil
     */
    const DEFAULT_ALIAS = "perfil";

    public function __construct() {
        parent::__construct();
        $this->schema_name = Schema::cbp;
        $this->table_name = 'perfil';
        $this->fields = array('id', 'codigo', 'nombre', 'descripcion', 'estado',
            'visible', 'fec_creacion', 'usu_creacion');
    }

    static function create() {
        return parent::create();
    }

    public function getMenuHijoPerfil($opcion_id_predecesor, $perfil_id) {

        $this->commandPrepare("sp_perfil_getMenuHijo");
        $this->commandAddParameter(":vin_predecesor_id", $opcion_id_predecesor);
        $this->commandAddParameter(":vin_perfil_id", $perfil_id);
        return $this->commandGetData();
    }

    public function getDataPerfil() {
        $this->commandPrepare("sp_perfil_getAll");
        return $this->commandGetData();
    }

    public function getDataPerfilBase() {
        $this->commandPrepare("sp_perfil_obtenerPerfilBase");
        return $this->commandGetData();
    }

    public function getPerfil($id) {
        $this->commandPrepare("sp_perfil_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updatePerfil($id, $nombre, $descripcion, $estado, $dashboard, $email, $monetaria, $pant_principal, $PerfilId) {
        $this->commandPrepare("sp_perfil_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_dashboard", $dashboard);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_monetaria", $monetaria);
        $this->commandAddParameter(":vin_pantalla_principal", $pant_principal);
        $this->commandAddParameter(":vin_perfil_id", $PerfilId);
        return $this->commandGetData();
    }

    public function deletePerfil($id, $id_per_ensesion) {
        $this->commandPrepare("sp_perfil_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_per_sesion", $id_per_ensesion);
        return $this->commandGetData();
    }

    public function getMenuPadre() {
        $this->commandPrepare("sp_perfil_getOpcionMenuPadre");
        return $this->commandGetData();
    }

    public function getMenuHijo($opcion_id_predecesor) {
        $this->commandPrepare("sp_perfil_getOpcionMenuHijo");
        $this->commandAddParameter(":vin_predecesor_id", $opcion_id_predecesor);
        return $this->commandGetData();
    }

    public function insertDetOpcPerfil($id_per, $id_opcion, $estado, $id_usu) {
        $this->commandPrepare("sp_opcion_perfil_insert");
        $this->commandAddParameter(":vin_perifl_id", $id_per);
        $this->commandAddParameter(":vin_opcion_id", $id_opcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_id", $id_usu);
        return $this->commandGetData();
    }

    public function getDetOpcPer($id_perfil) {
        $this->commandPrepare("sp_seg_obtener_opcion_por_perfil");
        $this->commandAddParameter(":vin_perifl_id", $id_perfil);
    }

    public function updateDetOpcPerfil($id_per, $id_opcion, $estado, $usuario_creacion
            , $visualizar = null
            , $agregar = null
            , $editar = null
            , $anular = null
            , $eliminar = null) {
        $this->commandPrepare("sp_opcion_perfil_update");
        $this->commandAddParameter(":vin_perfil_id", $id_per);
        $this->commandAddParameter(":vin_opcion_id", $id_opcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuario_creacion);

        $this->commandAddParameter(":vin_bandera_accion_visualizar", $visualizar);
        $this->commandAddParameter(":vin_bandera_accion_agregar", $agregar);
        $this->commandAddParameter(":vin_bandera_accion_editar", $editar);
        $this->commandAddParameter(":vin_bandera_accion_anular", $anular);
        $this->commandAddParameter(":vin_bandera_accion_eliminar", $eliminar);
        return $this->commandGetData();
    }

    public function obtenerPantallaPrincipal($id) {
        $this->commandPrepare("sp_perfil_getPantallaPrincipal");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPantallaXToken($token, $id) {
        $this->commandPrepare("sp_perfil_obtenerPantallaXToken");
        $this->commandAddParameter(":vin_id", $token);
        $this->commandAddParameter(":vin_id_usuario", $id);
        return $this->commandGetData();
    }

    public function obtenerImagenPerfil($id_per, $id_usu) {
        $this->commandPrepare("sp_usuario_getImagen");
        $this->commandAddParameter(":vin_id_perfil", $id_per);
        $this->commandAddParameter(":vin_id_usuario", $id_usu);
        return $this->commandGetData();
    }

    public function getDataComboPerfil() {
        $this->commandPrepare("sp_perfil_getCombo");
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado, $id_per_ensesion) {
        $this->commandPrepare("sp_perfil_UpdateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_per_sesion", $id_per_ensesion);
//        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function obtnerPerfilXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_perfil_getByUsuario");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtnerPerfilCargoXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_perfil_getByUsuarioCargo");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
    
        public function obtnerPerfilAgenciaXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_perfil_getByUsuarioAgencia");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
    
          public function obtnerPerfilLiquidacionAgencia($usuarioId) {
        $this->commandPrepare("sp_perfil_liquidacionAgencia");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
    

    public function obterTipoMovimiento() {
        $this->commandPrepare("sp_movimiento_tipo_obtener");
        return $this->commandGetData();
    }

    public function insertarMovimientoTipoPerfil($id_p, $id_opcionMT, $estadoopMT, $usuario) {
        $this->commandPrepare("sp_movimiento_tipo_perfil_insertar");
        $this->commandAddParameter(":vin_perfil_id", $id_p);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $id_opcionMT);
        $this->commandAddParameter(":vin_estado", $estadoopMT);
        $this->commandAddParameter(":vin_usuario", $usuario);
        return $this->commandGetData();
    }

    public function updateMovimientoTipoPerfil($id, $id_opcionMT, $estadoopMT) {
        $this->commandPrepare("sp_movimiento_tipo_perfil_actualizar");
        $this->commandAddParameter(":vin_perfil_id", $id);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $id_opcionMT);
        $this->commandAddParameter(":vin_estado", $estadoopMT);
        return $this->commandGetData();
    }

    public function ObtenerEmpresasXUsuarioId($id) {
        $this->commandPrepare("sp_empresa_obtenerXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPadreMenuXEmpresaXusuario($empresaId, $usuarioId) {
        $this->commandPrepare("sp_opcion_obtenerPadreMenuXEmpresaXusuario");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerHijoMenuXEmpresaXusuario($idPadre, $empresaId, $usuarioId) {

        $this->commandPrepare("sp_opcion_obtenerHijoMenuXEmpresaXusuario");
        $this->commandAddParameter(":vin_predecesor_id", $idPadre);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoTipo($empresaId, $usuarioId) {
        $this->commandPrepare("sp_movimiento_tipo_obtenerMovimientoTipo");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerImagenXUsuario($usuario) {
        $this->commandPrepare("sp_usuario_obtenerImagen");
        $this->commandAddParameter(":vin_usuario", $usuario);
        return $this->commandGetData();
    }

    public function eliminarPerfilPersonaClaseXPerfilId($perfilId) {
        $this->commandPrepare("sp_perfil_persona_clase_eliminarXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function guardarPerfilPersonaClaseXPerfilId($claseId, $perfilId, $usuarioCreacion) {
        $this->commandPrepare("sp_perfil_persona_clase_guardar");
        $this->commandAddParameter(":vin_persona_clase_id", $claseId);
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerCorreosDeUsuarioXNombrePerfil($descripcion) {
        $this->commandPrepare("sp_perfil_ObtenerCorreosXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        return $this->commandGetData();
    }

    public function obtenerOpcionXIdXUsuarioId($opcionId, $usuarioId) {
        $this->commandPrepare("sp_opcion_obtenerXOpcionIdXUsuarioId");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function inserPerfilAgenciaCaja($perfilId, $agenciaId, $cajaId, $usuarioCreacion) {
        $this->commandPrepare("sp_perfil_agencia_caja_insert");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioCreacion);
        return $this->commandGetData();
    }
    
    public function insertPerfil($nombre, $descripcion, $estado, $dashboard, $email, $monetaria, $usuario, $pant_principal) {
        $this->commandPrepare("sp_perfil_insert");
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_dashboard", $dashboard);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_monetaria", $monetaria);
        $this->commandAddParameter(":vin_usuario_creacion", $usuario);
        $this->commandAddParameter(":vin_pant_principal", $pant_principal);
        return $this->commandGetData();
    }

}
