<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class LineaCredito extends ModeloBase {

    static function create() {
        return parent::create();
    }

    public function listar() {
        $this->commandPrepare("sp_linea_credito_listar");
        return $this->commandGetData();
    }
    public function insertar($personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion) {
        $this->commandPrepare("sp_linea_credito_insertar");
        $this->commandAddParameter(":vin_persona_clase_id", $personaClaseId);
        $this->commandAddParameter(":vin_moneda_id", $moneda);
        $this->commandAddParameter(":vin_valor", $importe);   
        $this->commandAddParameter(":vin_dias", $periodo);
        $this->commandAddParameter(":vin_perioso_gracia", $periodoGracia);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        
        return $this->commandGetData();
    }
    public function actualizar($lineaCreditoId,$personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion) {
        $this->commandPrepare("sp_linea_credito_editar");
        $this->commandAddParameter(":vin_linea_credito_id", $lineaCreditoId);
        $this->commandAddParameter(":vin_persona_clase_id", $personaClaseId);
        $this->commandAddParameter(":vin_moneda_id", $moneda);
        $this->commandAddParameter(":vin_valor", $importe);   
        $this->commandAddParameter(":vin_dias", $periodo);
        $this->commandAddParameter(":vin_perioso_gracia", $periodoGracia);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }
    
    public function eliminar($lineaCreditoId) {
        $this->commandPrepare("sp_linea_credito_eliminar");
        $this->commandAddParameter(":vin_linea_credito_id", $lineaCreditoId);
        return $this->commandGetData();
    }

    
    public function obtenerPorId($lineaCreditoId)
    {
        $this->commandPrepare("sp_linea_credito_obtenerXId");
         $this->commandAddParameter(":vin_linea_creadito_id", $lineaCreditoId);
         return $this->commandGetData();
    }
    public function cambiarEstado($idEstado) {
        $this->commandPrepare("sp_linea_credito_cambiarEstado");
        $this->commandAddParameter(":vin_id", $idEstado);
        return $this->commandGetData();
    }
}
