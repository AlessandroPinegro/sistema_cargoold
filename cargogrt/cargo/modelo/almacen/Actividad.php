<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Actividad
 *
 * @author CHL
 */
class Actividad extends ModeloBase {
    /**
     * 
     * @return Actividad
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerActividadesActivas($documentoTipoId) {
        $this->commandPrepare("sp_actividad_obtenerActivas");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerActividadTipoActivas() {
        $this->commandPrepare("sp_actividad_tipo_obtenerActivas");
        return $this->commandGetData();
    }
    
    public function obtenerActividadesActivasTodo() {
        $this->commandPrepare("sp_actividad_obtenerActivasTodo");
        return $this->commandGetData();
    }
    
    public function obtenerActividadIdXDescripcion($descripcion) {
        $this->commandPrepare("sp_actividad_obtenerXdescripcion");
        $this->commandAddParameter(":vin_actividad", $descripcion);
        return $this->commandGetData();
    }
}
