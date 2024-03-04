<?php
require_once __DIR__."/../core/ModeloBase.php";
require_once __DIR__."/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Controlador
 *
 * @author CHL007
 */
class Controlador  extends ModeloBase{
    /**
     * Crea la instancia del Controlador.
     *
     * @return Controlador
     */
    static function create()
    {
       return parent::create();
    }
    
    /**
     * Obtiene un controlador por el id
     * 
     * @param string $opcionId El código en el controlador
     * @param string $usuarioId El id del usuario
     * @return array
     */
    public function getById($opcionId, $usuarioId){
        $this->commandPrepare("sp_controlador_getById");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
}
