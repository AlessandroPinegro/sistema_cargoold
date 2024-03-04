<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Ubicacion extends ModeloBase {

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

    public function getDataUbicacionTipo() {
        $this->commandPrepare("sp_ubi_listar_ubicacion_tipo");
        return $this->commandGetData();
    }

    public function insertUbicacionTipo($descripcion, $codigo, $comentario, $estado, $visible, $usu_creacion) {
        $this->commandPrepare("sp_ubi_insertar_ubicacion_tipo");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function getUbicacionTipo($id) {
        $this->commandPrepare("sp_ubi_obtener_ubicacion_tipo_por_id");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateUbicacionTipo($id, $descripcion, $codigo, $comentario, $estado) {
        $this->commandPrepare("sp_ubi_actualizar_ubicacion_tipo");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function deleteUbicacionTipo($id) {
        $this->commandPrepare("sp_ubi_eliminar_ubicacion_tipo");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function getDataComboUbicacionTipo($array_empresa) {
        $this->commandPrepare("sp_ubi_combo_ubicacion_tipo");
        $this->commandAddParameter(":vin_array_empresa", $array_empresa);
        return $this->commandGetData();
    }

    public function cambiarTipoEstado($id_estado) {
        $this->commandPrepare("sp_ubi_tipo_cambiar_estado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertUbicacionTipoEmpresa($id_p, $id_e, $visible, $estadoep) {
        $this->commandPrepare("sp_ubi_insertar_ubiicacion_tipo_empresa");
        $this->commandAddParameter(":vin_id_p", $id_p);
        $this->commandAddParameter(":vin_id_e", $id_e);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }

    public function updateUbicacionTipoEmpresa($id, $id_emp, $estado) {
        $this->commandPrepare("sp_ubi_actualizar_ubicacion_tipo_empresa");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_emp", $id_emp);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    ////////////////////////////
    //ubicacion
    //
    //////////////////////////
    public function getDataUbicacion() {
        $this->commandPrepare("sp_ubi_listar_ubicacion");
        return $this->commandGetData();
    }

    public function insertUbicacion($descripcion, $codigo, $tipo, $estado, $visible, $usu_creacion, $comentario) {
        $this->commandPrepare("sp_ubi_insertar_ubicacion");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    public function getUbicacion($id) {
        $this->commandPrepare("sp_ubi_obtener_ubicacion_por_id");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateUbicacion($id, $descripcion, $codigo, $tipo, $estado, $comentario) {
        $this->commandPrepare("sp_ubi_actualizar_ubicacion");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    public function deleteUbicacion($id) {
        $this->commandPrepare("sp_ubi_eliminar_ubicacion");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_ubi_cambiar_estado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertUbicacionEmpresa($id_p, $id_e, $visible, $estadoep) {
        $this->commandPrepare("sp_ubi_insertar_ubicacion_empresa");
        $this->commandAddParameter(":vin_id_p", $id_p);
        $this->commandAddParameter(":vin_id_e", $id_e);
        $this->commandAddParameter(":vin_visible", $visible);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }

}
