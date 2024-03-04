<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Servicio extends ModeloBase {

    static function create() {
        return parent::create();
    }

    public function getDataServicio() {
        $this->commandPrepare("sp_servicio_getAll");
        return $this->commandGetData();
    }

    public function insertServicio($codigo, $comentario, $descripcion, $estado, $usuarioCreacion ) {
        $this->commandPrepare("sp_servicio_insert");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_descripcion", $descripcion);   
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        
        return $this->commandGetData();
    }

    public function getServicio($id) {
        $this->commandPrepare("sp_servicio_getById");
        $this->commandAddParameter(":vin_servicio_id",$id);
        return $this->commandGetData();
    }

    public function updateServicio($idServicio, $descripcion, $comentario, $estado, $codigo) {
        $this->commandPrepare("sp_servicio_update");
        $this->commandAddParameter(":vin_id", $idServicio);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function deleteServicio($id) {
        $this->commandPrepare("sp_servicio_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($idEstado) {
        $this->commandPrepare("sp_servicio_updateEstado");
        $this->commandAddParameter(":vin_id", $idEstado);
        return $this->commandGetData();
    }

    public function insertServicioEmpresa($idServicio, $idEmpresa, $estado) {
        $this->commandPrepare("sp_servicio_empresa_insert");
        $this->commandAddParameter(":vin_id_servicio", $idServicio);
        $this->commandAddParameter(":vin_id_empresa", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function updateServicioEmpresa($id, $idEmpresa, $estado) {
        $this->commandPrepare("sp_servicio_empresa_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_empresa",$idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

}
