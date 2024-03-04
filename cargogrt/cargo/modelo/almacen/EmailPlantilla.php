<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Description of Login
 *
 * @author JSC7
 */
class EmailPlantilla extends ModeloBase {

    /**
     * 
     * @return EmailPlantilla
     */
    static function create() {
        return parent::create();
    }    
    
    public function obtenerEmailPlantillaXID($id) {  
        $this->commandPrepare("sp_email_plantilla_obtenerXID");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }    
    
    public function obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId) {
        $this->commandPrepare("sp_plantilla_obtenerDestinatarioXAccionXMovimientoTipoId");
        $this->commandAddParameter(":vin_accion", $accionEnvio);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

}
