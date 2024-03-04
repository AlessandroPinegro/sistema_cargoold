<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Description of Ear
 *
 * @author Imagina
 */
class Ear extends ModeloBase {

    /**
     * 
     * @return Ear
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerDocumentosEarXDocumentoDuaId($documentoId,$baseEar){
        $this->commandPrepare("sp_ear_obtenerXDocumentoDuaId");
        $this->commandAddParameter("vin_documento_id", $documentoId);
        $this->commandAddParameter("vin_base_ear", $baseEar);
        return $this->commandGetData();        
    }
    
    public function actualizarSolicitudDuaIdXEarId($baseEar,$earId,$duaId){
        $this->commandPrepare("sp_ear_solicitudes_actualizarDuaIdXEarId");
        $this->commandAddParameter("vin_base_ear", $baseEar);
        $this->commandAddParameter("vin_ear_id", $earId);
        $this->commandAddParameter("vin_dua_id", $duaId);
        return $this->commandGetData();        
        
    }
}
