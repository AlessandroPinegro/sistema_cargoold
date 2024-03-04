<?php

require_once __DIR__ . '/../core/ModeloBase.php';
/**
 *
 * @author Christopher Heredia
 */
class BienPrecio extends ModeloBase {

    /**
     * 
     * @return BienPrecio
     */
    static function create() {
        return parent::create();
    }

    public function obtener($bienId, $precioTipoId,$unidadMedidaId,$precioIndicador) {
        $this->commandPrepare("sp_bien_precio_obtener");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_precio_tipo_id", $precioTipoId);
        $this->commandAddParameter(":vin_unidad_medida_id_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_precio_indicador", $precioIndicador);
        return $this->commandGetData();
    }
    
    public function obtenerPrecioCompraPromedio($bienId,$unidadMedidaId,$fecha) {        
        $this->commandPrepare("sp_bien_precio_obtenerCompraPromedio");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }
    
    public function obtenerPrecioTipoActivo() {        
        $this->commandPrepare("sp_precio_tipo_obtener_activos");
        return $this->commandGetData();
    }
    
    public function obtenerBienPrecioXBienId($bienId) {
        $this->commandPrepare("sp_bien_precio_obtener_detallado");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();        
    }
    
    public function obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId,$precioTipoId,$monedaId) {
        $this->commandPrepare("sp_bien_precio_obtenerParaMovimientos");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_precio_tipo_id", $precioTipoId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        return $this->commandGetData();        
    }
    
    public function obtenerPrecioTipoXMovimientoTipo($movimientoTipoId) {
        $this->commandPrepare("sp_precio_tipo_obtenerXMovimientoTipo");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();        
    }
    
    public function obtenerBienPrecioXBienIdXMovimientoTipoId($bienId,$unidadMedidaId,$monedaId,$movimientoTipoId) {
        $this->commandPrepare("sp_bien_precio_obtenerXBienIdXMovimientoTipoId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();        
    }
    
    public function obtenerPrecioCompraMovimiento($fechaEmision,$bienId){
        $this->commandPrepare("sp_bien_precio_obtenerPrecioCompraMovimiento");
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();                
    }
    
    public function obtenerPrecioCompraXBienIdXUnidadMedidaId($bienId,$unidadId,$fecha){
        $this->commandPrepare("sp_bien_precio_obtenerPrecioCompraXBienIdXUnidadMedidaId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();                        
    }
}
