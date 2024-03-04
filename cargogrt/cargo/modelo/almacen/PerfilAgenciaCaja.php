<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class PerfilAgenciaCaja extends ModeloBase {

    /**
     * 
     * @return PerfilAgenciaCaja
     */
    static function create() {
        return parent::create();
    }

    public function obtenerPerfilAgenciaCajaXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_perfil_agencia_obtenerCajaXUsuarioId");
        $this->commandAddParameter(":vin_empresa_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPerfilAgenciaXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_perfil_agencia_obtenerAgenciaXUsuarioId");
        $this->commandAddParameter(":vin_cuenta_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPerfilAgenciaCajaXPerfilId($perfilId) {
        $this->commandPrepare("sp_perfil_agencia_caja_obtenerXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function eliminarPerfilAgenciaCajaXPerfilId($perfilId) {
        $this->commandPrepare("sp_perfil_agencia_caja_eliminarXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function registrarPerfilAgenciaCaja($dataAgencia, $cajaId, $perfilId, $usuarioId) {
        $this->commandPrepare("sp_perfil_agencia_caja_registrar");
        $this->commandAddParameter(":vin_agencia_id", $dataAgencia);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
    
    public function obtenerCajaXAgenciaId($agenciaId) {
        $this->commandPrepare("sp_agencia_cajaXAgenciaId");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        return $this->commandGetData();
    }

}
