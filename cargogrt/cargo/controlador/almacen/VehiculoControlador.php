<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/VehiculoNegocio.php';

class VehiculoControlador extends AlmacenIndexControlador {

    public function getDataGridVehiculos() {
        return VehiculoNegocio::create()->getDataVehiculo();
    }

    public function obtenerConfiguracionInicialForm() {
        $vehiculoId = $this->getParametro("id");
        $respuesta = new stdClass();
        $respuesta->dataVehiculo = VehiculoNegocio::create()->getVehiculo($vehiculoId);
        $respuesta->dataEmpresa = VehiculoNegocio::create()->getEmpresas(28);
        return $respuesta;
    }

    public function insertVehiculo() {
        $flotaId = $this->getParametro("flotaId");
        $empresa = $this->getParametro("empresa");
        $flota = $this->getParametro("flota");
        $placa = $this->getParametro("placa");
        $marca = $this->getParametro("marca");
        $capacidad = $this->getParametro("capacidad");
        $tipo = $this->getParametro("tipo");
        $usu_creacion = $this->getUsuarioId();
        $tarjetaCirculacion = $this->getParametro("tarjetaCirculacion");
        $codigoConfiguracion = $this->getParametro("codigoConfiguracion");
        $this->setTransaction();
        return VehiculoNegocio::create()->insertVehiculo($flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion);
    }

    public function getVehiculo() {
        $id_vehiculo = $this->getParametro("id_vehiculo");
        return VehiculoNegocio::create()->getVehiculo($id_vehiculo);
    }

    public function getEmpresas() {
        return VehiculoNegocio::create()->getEmpresas(28);
    }

    public function updateVehiculo() {
        $id = $this->getParametro("id");
        $flotaId = $this->getParametro("flotaId");
        $empresa = $this->getParametro("empresa");
        $flota = $this->getParametro("flota");
        $placa = $this->getParametro("placa");
        $marca = $this->getParametro("marca");
        $capacidad = $this->getParametro("capacidad");
        $tipo = $this->getParametro("tipo");
        $tarjetaCirculacion = $this->getParametro("tarjetaCirculacion");
        $codigoConfiguracion = $this->getParametro("codigoConfiguracion");
        $usu_creacion = $this->getUsuarioId();
        return VehiculoNegocio::create()->updateVehiculo($id, $flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion);
    }

    public function deleteVehiculo() {
        $id = $this->getParametro("id");
        $placa = $this->getParametro("placa");
        return VehiculoNegocio::create()->deleteVehiculo($id, $placa);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return VehiculoNegocio::create()->cambiarEstado($id_estado);
    }

    public function generaQR() {
        $id = $this->getParametro("id");
        $placa = $this->getParametro("placa");
        $tipo = $this->getParametro("tipo");
        $capacidad = $this->getParametro("capacidad");
        $empresa = $this->getParametro("empresa");
        $usu_creacion = $this->getUsuarioId();
        return VehiculoNegocio::create()->generaQR($id, $placa, $tipo, $capacidad, $empresa, $usu_creacion);
    }

    public function generaPDFQR() {
        $idVehiculo = $this->getParametro("idVehiculo");
        return VehiculoNegocio::create()->generaPDFQR($idVehiculo);
    }

    public function insertVehiculos() {
        $usuarioCreacion = $this->getUsuarioId();
        $fecha_actual = date("Y-m-d");
        $fecha = date("Y-m-d", strtotime($fecha_actual . "- 1 days"));
        $resultado = VehiculoNegocio::create()->insertVehiculos($fecha, $usuarioCreacion);
        return $resultado;
    }

    public function eliminarPDFM() {
        $url = $this->getParametro("url");
        unlink(__DIR__ . $url);
    }

}
