<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Persona
 *
 * @author CHL
 */
class PersonaContacto extends ModeloBase {

    /**
     * 
     * @return PersonaContacto
     */
    static function create() {
        return parent::create();
    }

    public function obtenerPersonaContactoXUsuarioId($usuarioId, $personaContactoId = NULL) {
        $this->commandPrepare("sp_persona_contacto_obtenerMovilXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_persona_id", $personaContactoId);
        return $this->commandGetData();
    }
    
        public function obtenerPersonaMisContactoXUsuarioId($usuarioId, $personaContactoId = NULL) {
        $this->commandPrepare("sp_persona_miscontacto_obtenerMovilXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_persona_id", $personaContactoId);
        return $this->commandGetData();
    }
    
    
    


}
