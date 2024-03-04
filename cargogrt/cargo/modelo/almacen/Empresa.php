
<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Empresa extends ModeloBase {

    /**
     * 
     * @return Empresa
     */
    
    static function create() {
        return parent::create();
    }

    public function getDataEmpresaTotal() {
        $this->commandPrepare("sp_empresa_getAll");
        return $this->commandGetData();
    }

    public function getAllEmpresaByUsuarioId($usuarioId) {
        $this->commandPrepare("sp_usuario_empresa_getByUsuarioId");
        $this->commandAddParameter(":vin_id", $usuarioId);
        return $this->commandGetData();
    }

    public function getDataEmpresaPorColaborador($id_colaborador, $id_usuario_ensesion) {
        $this->commandPrepare("sp_colaborador_empresa_getByColaborador");
        $this->commandAddParameter(":vin_id", $id_colaborador);
        $this->commandAddParameter(":vin_id_usuario", $id_usuario_ensesion);
        return $this->commandGetData();
    }

    public function getDataEmpresaPerfil($id_perfil) {
        $this->commandPrepare("sp_emp_listar_perfil_empresa");
        $this->commandAddParameter(":vin_id", $id_perfil);
        return $this->commandGetData();
    }

    public function getDataEmpresaPersona($idPerfil) {
        $this->commandPrepare("sp_persona_empresa_getAll");
//        $this->commandPrepare("sp_emp_listar_usuario_empresa_por_id");
        $this->commandAddParameter(":vin_id", $idPerfil);
        return $this->commandGetData();
    }

    public function getDataEmpresaUsuarioPorId($idUsuario) {
        $this->commandPrepare("sp_usuario_empresa_getById");
        $this->commandAddParameter(":vin_id", $idUsuario);
        return $this->commandGetData();
    }

    public function getDataEmpresaUsuario($id_perfil) {
        $this->commandPrepare("sp_emp_listar_usuario_empresa");
        $this->commandAddParameter(":vin_id", $id_perfil);
        return $this->commandGetData();
    }

    public function getDataEmpresaBien($id_perfil) {
        $this->commandPrepare("sp_bien_empresa_getAll");
        $this->commandAddParameter(":vin_id", $id_perfil);
        return $this->commandGetData();
    }

    public function getDataEmpresaOrganizador($id_perfil) {
        $this->commandPrepare("sp_organizador_empresa_getAll");
        $this->commandAddParameter(":vin_id", $id_perfil);
        return $this->commandGetData();
    }

//    public function getDataEmpresaBienTipo($id_tipo, $id_usu_ensesion) {
//        $this->commandPrepare("sp_emp_listar_bien_tipo_empresa");
//        $this->commandAddParameter(":vin_tipo_id", $id_tipo);
//        $this->commandAddParameter(":vin_usuario_id", $id_usu_ensesion);
//        return $this->commandGetData();
//    }

    public function getDataEmpresaUbicacionTipo($id_tipo, $id_usu_ensesion) {
        $this->commandPrepare("sp_emp_listar_ubicacion_tipo_empresa");
        $this->commandAddParameter(":vin_tipo_id", $id_tipo);
        $this->commandAddParameter(":vin_usuario_id", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function getDataEmpresaAlmacenTipo($id_tipo, $id_usu_ensesion) {
        $this->commandPrepare("sp_emp_listar_almacen_tipo_empresa");
        $this->commandAddParameter(":vin_tipo_id", $id_tipo);
        $this->commandAddParameter(":vin_usuario_id", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function getDataEmpresaServicio($id_tipo, $idUsuarioSesion) {
        $this->commandPrepare("sp_servicio_empresa_getAll");
        $this->commandAddParameter(":vin_tipo_id", $id_tipo);
        $this->commandAddParameter(":vin_usuario_id", $idUsuarioSesion);
        return $this->commandGetData();
    }

    public function obtenerXUsuarioId($usuarioId){
        $this->commandPrepare("sp_empresa_obtenerXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerEmpresaXId($empresaId){
        $this->commandPrepare("sp_empresa_obtenerXId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    public function obtenerEmpresaXDocumentoId($documentoId){
        $this->commandPrepare("sp_empresa_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();        
    }
}
