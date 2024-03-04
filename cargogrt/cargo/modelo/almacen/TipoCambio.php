<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class TipoCambio extends ModeloBase {
    /**
     * 
     * @return TipoCambio
     */
    
    static function create() {
        return parent::create();
    }

    public function listarTipoCambio() {
        $this->commandPrepare("sp_tipo_cambio_listar");
        return $this->commandGetData();
    }
    
    public function insertarActualizarTipoCambio($tipoCambioId,$monedaId,$fecha,$equivalenciaCompra ,$equivalenciaVenta,$usuCreacion) {
        $this->commandPrepare("sp_tipo_cambio_insertar_actualizar");
        $this->commandAddParameter(":vin_id", $tipoCambioId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_equivalencia_compra", $equivalenciaCompra);
        $this->commandAddParameter(":vin_equivalencia_venta", $equivalenciaVenta);
        $this->commandAddParameter(":vin_usu_creacion", $usuCreacion);
        return $this->commandGetData();
    }
        
    public function obtenerTipoCambioXid($id){
        $this->commandPrepare("sp_tipo_cambio_obtenerXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }    
    
    public function obtenerTipoCambioXfecha($fecha){
        $this->commandPrepare("sp_tipo_cambio_obtenerXfecha");
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }    
    
    public function cambiarEstado($id_estado) {
        $this->commandPrepare("sp_tipo_cambio_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }
        
    public function eliminar($id) {
        $this->commandPrepare("sp_tipo_cambio_eliminar");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerTipoCambioXFechaUltima($fecha){
        $this->commandPrepare("sp_tipo_cambio_obtenerXFechaUltima");
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }    
}
