<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Description of Ear
 *
 * @author Imagina
 */
class Zona extends ModeloBase {

    /**
     * 
     * @return Zona
     */
    static function create() {
        return parent::create();
    }

    public function obtenerZonaActiva() {
        $this->commandPrepare("sp_zona_obtenerActivo");
//        $this->commandAddParameter("vin_documento_id", $documentoId);
//        $this->commandAddParameter("vin_base_ear", $baseEar);
        return $this->commandGetData();
    }

    public function obtenerTarifarioZonaActiva() {
        $this->commandPrepare("sp_tarifario_zona_obtenerActivo");
        return $this->commandGetData();
    }

}
