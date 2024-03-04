<?php

require_once __DIR__ . '/../../modelo/almacen/PerfilAgenciaCaja.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class PerfilAgenciaCajaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return PerfilAgenciaCajaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerCuentasActivas() {
        return Cuenta::create()->obtenerCuentasActivas();
    }

    public function obtenerPerfilAgenciaCajaXUsuarioId($usuarioId) {
        return PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXUsuarioId($usuarioId);
    }

    public function obtenerPerfilAgenciaXUsuarioId($cuentaId) {
        return PerfilAgenciaCaja::create()->obtenerPerfilAgenciaXUsuarioId($cuentaId);
    }
    
    //Permite MultiplesIds
    public function obtenerCajaXAgenciaId($agenciaId) {
        return PerfilAgenciaCaja::create()->obtenerCajaXAgenciaId($agenciaId);
    }

}
