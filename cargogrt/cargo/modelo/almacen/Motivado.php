<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class Motivado extends ModeloBase {

    /**
     * 
     * @return Motivado
     */
    static function create() {
        return parent::create();
    }

    public function insertMotivado($motivo,$descripcion, $ejemplo,$usu_creacion) {
      $this->commandPrepare("sp_motivado_insert");
      $this->commandAddParameter(":vin_motivo", $motivo);
      $this->commandAddParameter(":vin_descripcion", $descripcion);
      $this->commandAddParameter(":vin_ejemplo", $ejemplo);
      $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);

      return $this->commandGetData();
  }

  public function obtenerDataMotivado() {
    $this->commandPrepare("sp_motivado_getAll");
    return $this->commandGetData();
  } 

  public function getMotivado($id) {
    $this->commandPrepare("sp_motivado_getById");
    $this->commandAddParameter(":vin_motivado_id", $id);
    return $this->commandGetData();
  }

  public function updateMotivado($id, $motivo, $descripcion, $ejemplo, $usuarioId) {
    $this->commandPrepare("sp_motivado_update");
    $this->commandAddParameter(":vin_id", $id);
    $this->commandAddParameter(":vin_motivo", $motivo);
    $this->commandAddParameter(":vin_descripcion", $descripcion);
    $this->commandAddParameter(":vin_ejemplo", $ejemplo);
    $this->commandAddParameter(":vin_user", $usuarioId);

    return $this->commandGetData();
  }

  public function deleteMotivado($id, $id_usu_ensesion) {
    $this->commandPrepare("sp_motivado_delete");
    $this->commandAddParameter(":vin_id", $id);
    $this->commandAddParameter(":vin_user", $id_usu_ensesion);
    return $this->commandGetData();
}
}