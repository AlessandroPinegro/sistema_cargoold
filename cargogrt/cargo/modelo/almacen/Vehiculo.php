<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Vehiculo extends ModeloBase {

    /**
     * 
     * @return Vehiculo
     */
    static function create() {
        return parent::create();
    }

    public function getDataVehiculo() {
        $this->commandPrepare("sp_vehiculo_getAll");
        return $this->commandGetData();
    }

    public function getDataVehiculoxTipoDocumento($tipo) {
        $this->commandPrepare("sp_vehiculo_documento_tipo_getAll");
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function insertVehiculo($flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion) {
        $this->commandPrepare("sp_vehiculo_insert");
        $this->commandAddParameter(":vin_flota_id", $flotaId);
        $this->commandAddParameter(":vin_empresa", $empresa);
        $this->commandAddParameter(":vin_flota", $flota);
        $this->commandAddParameter(":vin_placa", $placa);
        $this->commandAddParameter(":vin_marca", $marca);
        $this->commandAddParameter(":vin_capacidad", $capacidad);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_user", $usu_creacion);
        $this->commandAddParameter(":vin_tarjeta_circulacion", $tarjetaCirculacion);
        $this->commandAddParameter(":vin_codigo_configuracion", $codigoConfiguracion);
        return $this->commandGetData();
    }

    public function getVehiculo($id) {
        $this->commandPrepare("sp_vehiculo_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateVehiculo($id, $flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion) {
        $this->commandPrepare("sp_vehiculo_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_flota_id", $flotaId);
        $this->commandAddParameter(":vin_empresa", $empresa);
        $this->commandAddParameter(":vin_flota", $flota);
        $this->commandAddParameter(":vin_placa", $placa);
        $this->commandAddParameter(":vin_marca", $marca);
        $this->commandAddParameter(":vin_capacidad", $capacidad);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_user", $usu_creacion);
        $this->commandAddParameter(":vin_tarjeta_circulacion", $tarjetaCirculacion);
        $this->commandAddParameter(":vin_codigo_configuracion", $codigoConfiguracion);
        return $this->commandGetData();
    }

    public function deleteVehiculo($id) {
        $this->commandPrepare("sp_vehiculo_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_vehiculo_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertQR($id, $textoQR, $usu_creacion) {
        $this->commandPrepare("sp_vehiculo_insert_qr");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_texto", $textoQR);
        $this->commandAddParameter(":vin_user", $usu_creacion);
        return $this->commandGetData();
    }

    public function insertVehiculos($flotaId, $idEmpresa, $flotaNro, $flotaPlaca, $flotaMarca, $flotaCargaMX, $tipo, $usuarioCreacion) {
        $this->commandPrepare("sp_vehiculo_insert_api");
        $this->commandAddParameter(":vin_flotaId", $flotaId);
        $this->commandAddParameter(":vin_idEmpresa", $idEmpresa);
        $this->commandAddParameter(":vin_flotaNro", $flotaNro);
        $this->commandAddParameter(":vin_flotaPlaca", $flotaPlaca);
        $this->commandAddParameter(":vin_flotaMarca", $flotaMarca);
        $this->commandAddParameter(":vin_flotaCargaMX", $flotaCargaMX);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_user", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerVehiculoxPlaca($flotaPlaca) {
        $this->commandPrepare("sp_vehiculo_obtenerxPlaca");
        $this->commandAddParameter(":vin_placa", $flotaPlaca);
        return $this->commandGetData();
    }

    public function obtenerProveedor($ClaseId) {
        $this->commandPrepare("sp_persona_obtener_XPersonaClaseId");
        $this->commandAddParameter(":vin_persona_clase_id", $ClaseId);
        return $this->commandGetData();
    }

}
