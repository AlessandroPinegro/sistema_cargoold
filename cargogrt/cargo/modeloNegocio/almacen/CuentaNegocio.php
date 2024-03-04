<?php
require_once __DIR__ . '/../../modelo/almacen/Cuenta.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class CuentaNegocio extends ModeloNegocioBase { 
    
    //CONSTANTES DE IDS DE CUENTAS
    const ID_CUENTA_TRANSFERENCIA_CAJA=3;
    const ID_CUENTA_VISA_CAJA=3;
    const ID_CUENTA_DEPOSITO_CAJA=3;
    
    //TIPO DE CUENTA
    const CUENTA_TIPO_CAJA_CHICA=1;
    const CUENTA_TIPO_BANCO=2;
    /**
     * 
     * @return CuentaNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerCuentasActivas(){
        return Cuenta::create()->obtenerCuentasActivas();
    }
    
    public function obtenerCuentaDefectoXEmpresaId($empresaId){
        return Cuenta::create()->obtenerCuentaDefectoXEmpresaId($empresaId);
    }
    
    public function obtenerCuentaXId($cuentaId){
        return Cuenta::create()->obtenerCuentaXId($cuentaId);
    }
    
    public function obtenerSaldoCuentaXId($cuentaId){
        return Cuenta::create()->obtenerSaldoCuentaXId($cuentaId);
    }
    
    public function obtenerCuentaSaldoTodos($empresaId){
        return Cuenta::create()->obtenerCuentaSaldoTodos($empresaId);
    }
}
