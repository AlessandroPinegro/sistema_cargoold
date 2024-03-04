<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Equivalencia extends ModeloBase {

    static function create() {
        return parent::create();
    }

    public function getDataEquivalencia() {
        $this->commandPrepare("sp_unidad_medida_equivalencia_getAll");
//        $this->commandAddParameter(":vin_id", $id_unidad);
//        $this->commandAddParameter(":vin_factor_uni", $factor_uni);
        return $this->commandGetData();
    }

    public function getDataEquivalenciaIds() {
        $this->commandPrepare("sp_equi_listar_equivalencias_ids");
        return $this->commandGetData();
    }

    public function insertEquivalencia($fac_alternativa, $uni_alternativa, $fac_base, $uni_base, $estado, $usu_creacion) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_insert");
        $this->commandAddParameter(":vin_fac_alternativa", $fac_alternativa);
        $this->commandAddParameter(":vin_uni_alternativa", $uni_alternativa);
        $this->commandAddParameter(":vin_fac_base", $fac_base);
        $this->commandAddParameter(":vin_uni_base", $uni_base);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function getEquivalencia($id) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateEquivalencia($id_equivalencia, $unidad_base, $factor_unidad, $unidad_alternativa, $factor_alternativa) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_update");
        $this->commandAddParameter(":vin_id", $id_equivalencia);
        $this->commandAddParameter(":vin_unidad_base", $unidad_base);
        $this->commandAddParameter(":vin_factor_unidad", $factor_unidad);
        $this->commandAddParameter(":vin_unidad_alternativa", $unidad_alternativa);
        $this->commandAddParameter(":vin_factor_alternativa", $factor_alternativa);
        return $this->commandGetData();
    }

    public function deleteEquivalencia($id) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function validarEquivalencia($unidad_base, $unidad_alternativa) {
        $this->commandPrepare("sp_unidad_medida_equivalencia_validate");
        $this->commandAddParameter(":vin_unidad_base", $unidad_base);
        $this->commandAddParameter(":vin_unidad_alternativa", $unidad_alternativa);
        return $this->commandGetData();
    }

}
