<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Cuenta
 *
 * @author CHL
 */
class Cuenta extends ModeloBase {
    /**
     * 
     * @return Cuenta
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerCuentasActivas() {
        $this->commandPrepare("sp_cuenta_obtenerActivas");
        return $this->commandGetData();
    }
    
    public function obtenerCuentaDefectoXEmpresaId($empresaId) {
        $this->commandPrepare("sp_cuenta_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    public function obtenerCuentaXId($cuentaId) {
        $this->commandPrepare("sp_cuenta_obtenerXId");
        $this->commandAddParameter(":vin_cuenta_id", $cuentaId);
        return $this->commandGetData();
    }
    
    public function obtenerSaldoCuentaXId($cuentaId) {
        $this->commandPrepare("sp_cuenta_obtenerSaldoXId");
        $this->commandAddParameter(":vin_cuenta_id", $cuentaId);
        return $this->commandGetData();
    }
    
    public function obtenerCuentaSaldoTodos($empresaId){
        $this->commandPrepare("sp_cuenta_obtenerSaldoTodos");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
        
    }
}
