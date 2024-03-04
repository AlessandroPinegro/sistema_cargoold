<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Unidad extends ModeloBase {

    /**
     * 
     * @return Unidad
     */
    static function create() {
        return parent::create();
    }

    //tipo de unidad
    public function getDataUnidadTipo() {
        $this->commandPrepare("sp_unidad_medida_tipo_getAll");
        return $this->commandGetData();
    }

    public function insertUnidadTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion) {
        $this->commandPrepare("sp_unidad_medida_tipo_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function getUnidadTipo($id) {
        $this->commandPrepare("sp_unidad_medida_tipo_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateUnidadTipo($id, $descripcion, $codigo, $comentario, $estado, $unidad_base, $id_unidad) {
        $this->commandPrepare("sp_unidad_medida_tipo_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_unidad_base", $unidad_base);
        $this->commandAddParameter(":vin_id_unidad", $id_unidad);
        return $this->commandGetData();
    }

    public function deleteUnidadTipo($id) {
        $this->commandPrepare("sp_unidad_medida_tipo_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function getDataComboUnidadTipo() {
        $this->commandPrepare("sp_unidad_medida_tipo_getCombo");
        return $this->commandGetData();
    }

    public function cambiarTipoEstado($id_estado) {
        $this->commandPrepare("sp_unidad_medida_tipo_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function getIdTipoUnidad($tipo) {
        $this->commandPrepare("sp_unidad_tipo_getIdByDesripcion");
        $this->commandAddParameter(":vin_descripcion", $tipo);
        return $this->commandGetData();
    }

    ////////////////////////////
    //unidad
    //////////////////////////
    public function getDataUnidad() {
        $this->commandPrepare("sp_unidad_medida_getAll");
        return $this->commandGetData();
    }

    public function getDataComboUnidadAlternativa($id_bandera, $unidad_medida_tipo) {
        $this->commandPrepare("sp_unidad_alternativa_getCombo");
        $this->commandAddParameter(":vin_id", $id_bandera);
        $this->commandAddParameter(":vin_unidad_medida_tipo", $unidad_medida_tipo);
        return $this->commandGetData();
    }

    public function getDataComboUnidadBase() {
        $this->commandPrepare("sp_unidad_medida_getCombo");
        return $this->commandGetData();
    }

    public function insertUnidad($descripcion, $codigo, $tipo, $simbolo, $estado, $usu_creacion,$codigoSunatId) {
        $this->commandPrepare("sp_unidad_medida_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_simbolo", $simbolo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        return $this->commandGetData();
    }

    public function getUnidad($id) {
        $this->commandPrepare("sp_unidad_medida_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateUnidad($id, $descripcion, $codigo, $tipo, $simbolo, $estado,$unidad_base,$codigoSunatId) {
        $this->commandPrepare("sp_unidad_medida_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_simbolo", $simbolo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_unidad_base", $unidad_base);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        return $this->commandGetData();
    }

    public function deleteUnidad($id) {
        $this->commandPrepare("sp_unidad_medida_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_unida_medida_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function validarAsignarUnidadBase($tipo_unidad, $unidadId) {
        $this->commandPrepare("sp_unidad_medida_tipo_validarUnidadBase");
        $this->commandAddParameter(":vin_tipo_unidad", $tipo_unidad);
        $this->commandAddParameter(":vin_unidad_id", $unidadId);
        return $this->commandGetData();
    }

    public function obtenerActivasXBien($bienId) {
        $this->commandPrepare("sp_unidad_obtenerActivasXBien");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

}
