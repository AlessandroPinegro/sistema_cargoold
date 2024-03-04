<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Almacen extends ModeloBase {

    const DEFAULT_ALIAS = "usuario";

    public function __construct() {
        parent::__construct();
        $this->schema_name = Schema::cbp;
        $this->table_name = 'usuario';
        $this->fields = array('id', 'persona_id', 'usuario', 'clave',
            'estado', 'visible', 'fec_creacion', 'usu_creacion');
    }

    static function create() {
        return parent::create();
    }

    public function getDataAlmacenTipo() {
        $this->commandPrepare("sp_alm_listar_almacen_tipo");
        return $this->commandGetData();
    }

    public function insertAlmacenTipo($descripcion, $codigo, $comentario, $estado, $visible, $usu_creacion) {
        $this->commandPrepare("sp_alm_insertar_almacen_tipo");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function getAlmacenTipo($id) {
        $this->commandPrepare("sp_alm_obtener_almacen_tipo_por_id");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateAlmacenTipo($id, $descripcion, $codigo, $comentario, $estado) {
        $this->commandPrepare("sp_alm_actualizar_almacen_tipo");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function deleteAlmacenTipo($id) {
        $this->commandPrepare("sp_alm_eliminar_almacen_tipo");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function getDataComboAlmacenTipo() {
        $this->commandPrepare("sp_alm_combo_almacen_tipo");
        return $this->commandGetData();
    }

    public function cambiarTipoEstado($id_estado) {
        $this->commandPrepare("sp_alm_tipo_cambiar_estado");
        $this->commandAddParameter(":vin_id", $id_estado);
//        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
    
    public function insertAlmacenTipoEmpresa($id_p, $id_e, $visible, $estadoep) {
        $this->commandPrepare("sp_alm_insertar_almacen_tipo_empresa");
        $this->commandAddParameter(":vin_id_p", $id_p);
        $this->commandAddParameter(":vin_id_e", $id_e);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }
    public function updateAlmacenTipoEmpresa($id, $id_emp, $estado) {
        $this->commandPrepare("sp_alm_actualizar_almacen_tipo_empresa");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_emp", $id_emp);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
    
    ////////////////////////////
    //almacen
    //////////////////////////
    public function getDataAlmacen() {
        $this->commandPrepare("sp_alm_listar_almacen");
        return $this->commandGetData();
    }

    public function insertAlmacen($descripcion, $codigo, $tipo, $estado, $visible, $usu_creacion, $comentario) {
        $this->commandPrepare("sp_alm_insertar_almacen");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    public function getAlmacen($id) {
        $this->commandPrepare("sp_alm_obtener_almacen_por_id");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateAlmacen($id, $descripcion, $codigo, $tipo, $estado, $comentario) {
        $this->commandPrepare("sp_alm_actualizar_almacen");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    public function deleteAlmacen($id) {
        $this->commandPrepare("sp_alm_eliminar_almacen");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_alm_cambiar_estado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertAlmacenEmpresa($id_p, $id_e, $visible, $estadoep) {
        $this->commandPrepare("sp_alm_insertar_almacen_empresa");
        $this->commandAddParameter(":vin_id_p", $id_p);
        $this->commandAddParameter(":vin_id_e", $id_e);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }

    public function updateAlmacenEmpresa($id, $id_emp, $estado) {
        $this->commandPrepare("sp_alm_actualizar_almacen_empresa");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_emp", $id_emp);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

}
