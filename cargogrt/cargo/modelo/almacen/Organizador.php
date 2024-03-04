<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Organizador extends ModeloBase {

   /**
     * 
     * @return Organizador
     */
    static function create() {
        return parent::create();
    }
    public function getDataOrganizadorTipo() {
        $this->commandPrepare("sp_organizador_tipo_getAll");
        return $this->commandGetData();
    }

    public function insertOrganizadorTipo($descripcion, $codigo, $comentario, $estado, $usuarioCreacion) {
        $this->commandPrepare("sp_organizador_tipo_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function getOrganizadorTipo($id) {
        $this->commandPrepare("sp_organizador_tipo_getbyId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateOrganizadorTipo($id, $descripcion, $codigo, $comentario, $estado) {
        $this->commandPrepare("sp_organizador_tipo_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function deleteOrganizadorTipo($id) {
        $this->commandPrepare("sp_organizador_tipo_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function getDataComboOrganizadorTipo() {
        $this->commandPrepare("sp_organizador_tipo_getCombo");
        return $this->commandGetData();
    }

    public function cambiarTipoEstado($id_estado) {
        $this->commandPrepare("sp_organizador_tipo_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    ////////////////////////////
    //organizador
    //////////////////////////
    public function getDataOrganizador() {
        $this->commandPrepare("sp_organizador_getAll");
        return $this->commandGetData();
    }

    public function insertOrganizador($descripcion, $codigo, $padre, $tipo, $estado,$usu_creacion, $comentario,$agencia) {
        $this->commandPrepare("sp_organizador_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_padre", $padre);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usu_creacion); 
        $this->commandAddParameter(":vin_agencia", $agencia); 
        return $this->commandGetData();
    }

    public function getOrganizador($id ) {
        $this->commandPrepare("sp_organizador_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateOrganizador($id, $descripcion, $codigo, $padre,$tipo, $estado, $comentario,$agencia) {
        $this->commandPrepare("sp_organizador_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_padre", $padre);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_agencia", $agencia);
        return $this->commandGetData();
    }

    public function deleteOrganizador($organizadorId) {
        $this->commandPrepare("sp_organizador_delete");
        $this->commandAddParameter(":vin_id", $organizadorId);
        return $this->commandGetData();
    }

    public function getOrganizadorXTipo($tipo) {
        $this->commandPrepare("sp_getOrganizadorXTipo");
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_organizador_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertOrganizadorEmpresa($id_p, $empresa, $estado,$usu_creacion=null) {
        $this->commandPrepare("sp_organizador_empresa_insert");
        $this->commandAddParameter(":vin_id_organizador", $id_p);
        $this->commandAddParameter(":vin_id_empresa", $empresa);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario", $usu_creacion);
        return $this->commandGetData();
    }

    public function updateOrganizadorEmpresa($id, $empresa, $estado) {
        $this->commandPrepare("sp_organizador_empresa_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_empresa", $empresa);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
    
    public function saveOrganizadorEmpresa($id, $empresa, $estado,$usu_creacion=null) {
        $this->commandPrepare("sp_organizador_empresa_save");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_empresa", $empresa);
        $this->commandAddParameter(":vin_estado", $estado);
         $this->commandAddParameter(":vin_usuario", $usu_creacion);
        return $this->commandGetData();
    }
    
    public function obtenerOrganizadorActivo($id = 0)
    {
        $this->commandPrepare("sp_organizador_obtenerActivos");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerOrganizadorActivoXAgenciaIdXOrganizadorTipoId($agenciaId, $tipo) {
        $this->commandPrepare("sp_organizador_obtenerXAgenciaIdXTipo");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_organizador_tipo", $tipo);
        return $this->commandGetData();
    }

    public function organizadorEsPadre($id)
    {
        $this->commandPrepare("sp_organizador_esPadre");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerXMovimientoTipo($movimientoTipoId)
    {
        $this->commandPrepare("sp_organizador_obtenerXMovimientoTipo");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerXMovimientoTipo2($movimientoTipoId,$organizadoresIds,$comodinMostrar)
    {
        $this->commandPrepare("sp_organizador_obtenerXMovimientoTipo2");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_organizador_ids", $organizadoresIds);
        $this->commandAddParameter(":vin_comodinMostrar", $comodinMostrar);
        return $this->commandGetData();
    }
    
    public function obtenerOrganizadorActivoXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_organizador_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }
    
    public function obtenerOrganizadorActivoXDescripcion($organizadorDescripcion)
    {
        $this->commandPrepare("sp_organizador_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $organizadorDescripcion);
        return $this->commandGetData();
    }
    
    public function obtenerEmpresaXOrganizadorId($organizadorId){
        $this->commandPrepare("sp_empresa_obtenerXOrganizadorId");
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        return $this->commandGetData();        
    }
    
    public function obtenerDireccionOrganizador($organizadorId){
        $this->commandPrepare("sp_organizador_obtenerDireccionXId");
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        return $this->commandGetData();                
    }
    
     public function obtenerOrganizadorUbicacion($organizadorId){
        $this->commandPrepare("sp_organizador_posicion");
        $this->commandAddParameter(":vin_id", $organizadorId);
        return $this->commandGetData();                
    }
    
         public function obtenerOrganizadorUbicacionV($organizadorId,$id_agencia){
        $this->commandPrepare("sp_organizador_posicion_validacion");
        $this->commandAddParameter(":vin_id", $organizadorId);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        return $this->commandGetData();                
    }
    
    public function insertQR($id, $codigo, $textoQR, $usu_creacion){
        $this->commandPrepare("sp_organizador_insert_qr");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_texto", $textoQR);
        $this->commandAddParameter(":vin_user", $usu_creacion);
        return $this->commandGetData();
      }
      
    public function TraerAgenciaHijo($organizadorId){
        $this->commandPrepare("sp_traer_agencia_hijo");
        $this->commandAddParameter(":vin_organizadorPadre", $organizadorId);
        return $this->commandGetData();                
    }
    
}
