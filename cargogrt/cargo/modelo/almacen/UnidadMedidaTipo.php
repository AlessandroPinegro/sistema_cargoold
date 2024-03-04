<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of UnidadMedidaTipo
 *
 * @author Christopher Heredia
 */
class UnidadMedidaTipo extends ModeloBase {

    /**
     * 
     * @return UnidadMedidaTipo
     */
    static function create() {
        return parent::create();
    }

    public function obtener() {
        $this->commandPrepare("sp_unidad_medida_tipo_obtener");
        return $this->commandGetData();
    }
    
    public function obtenerXId($id) {
        $this->commandPrepare("sp_unidad_medida_tipo_obtenerXId");
        $this->commandAddParameter("vin_id", $id);
        return $this->commandGetData();
    }
    
    public function verificarTipoUnidadMedidaParaTramo($unidadMedidaId) {
        $this->commandPrepare("sp_unidad_medida_tipo_obtenerParaTramoBienXUnidadMedidaId");
        $this->commandAddParameter("vin_unidad_medida_id", $unidadMedidaId);
        return $this->commandGetData();
    }
    
    public function obtenerSiguienteCodigo(){
              
    }
}