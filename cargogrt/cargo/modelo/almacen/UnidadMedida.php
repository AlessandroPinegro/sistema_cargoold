<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Description of Login
 *
 * @author Christopher Heredia Lozada
 */
class UnidadMedida extends ModeloBase {

    /**
     * 
     * @return UnidadMedida
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerUnidadControlXUnidadMedidaTipoId($id) {
        $this->commandPrepare("sp_unidad_medida_obtenerUnidadControlXUnidadMedidaTipoId");
        $this->commandAddParameter("vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerUnidadMedidaActivoXDescripcion($unidadMedidaDescripcion) {
        $this->commandPrepare("sp_unidad_medida_obtenerXDescripcion");
        $this->commandAddParameter("vin_descripcion", $unidadMedidaDescripcion);
        return $this->commandGetData();
    }
    
    public function obtenerUnidadMedidaEquivalenciaXIds($unidadIdBase,$unidadIdConvertir) {                
        $this->commandPrepare("sp_unidad_medida_obtener_equivalencia");
        $this->commandAddParameter("vin_unidad_medida_id", $unidadIdBase);
        $this->commandAddParameter("vin_unidad_medida_convertir_id", $unidadIdConvertir);
        return $this->commandGetData();
    }
}