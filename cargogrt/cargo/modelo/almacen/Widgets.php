<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Widgets extends ModeloBase {

    static function create() {
        return parent::create();
    }
     public function obtenerBienesComprometidosXCriterios($estadoId,$empresaId,$columnaOrdenar, $formaOrdenar,$elemntosFiltrados,$start)
    {
        $this->commandPrepare("sp_bien_comprometido_obtenerXCriterios");
        $this->commandAddParameter(":vin_estado_id", $estadoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadBienesComprometidosXCriterio($estadoId,$empresaId,$columnaOrdenar, $formaOrdenar,$elemntosFiltrados,$start)
    {
        $this->commandPrepare("sp_bien_comprometido_consulta_contador");
        $this->commandAddParameter(":vin_estado_id", $estadoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    public function obtenerCantidadBienesComprometidos($estadoId,$empresaId)
    {
        $this->commandPrepare("sp_bien_comprometido_cantidadesTotalesXCriterios");
        $this->commandAddParameter(":vin_estado_id", $estadoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    //Ranking distribución
     
     public function obtenerRankingDistribucionXCriterios($empresa, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_ranking_distribucion_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadRankingDistribucionXCriterios($empresa,$columnaOrdenar, $formaOrdenar,$elemntosFiltrados,$start)
    {
        $this->commandPrepare("sp_ranking_distribucion_consulta_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    public function CantidadTotalRankingDistribucion($empresaId)
    {
        $this->commandPrepare("sp_ranking_distribucion_cantidadTotalXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
}

