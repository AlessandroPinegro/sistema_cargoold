<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class CentroCosto extends ModeloBase {
    const  CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS = '9710';


    /**
     * 
     * @return CentroCosto
     */
    
    static function create() {
        return parent::create();
    }

    public function listarCentroCostoPadres($empresaId) {
        $this->commandPrepare("sp_centro_costo_padres_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    public function obtenerHijos($padreId){
        $this->commandPrepare("sp_centro_costo_obtenerHijos");
        $this->commandAddParameter(":vin_centro_costo_padre_id", $padreId);
        return $this->commandGetData();        
    }    
    
    public function obtenerCentroCostoXId($id){
        $this->commandPrepare("sp_centro_costo_obtenerXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();              
    }
    
    public function guardarCentroCosto($codigo,$descripcion,$estado,$usuarioId,
                $centroCostoId,$padreCentroCostoId,$empresaId){
        
        $this->commandPrepare("sp_centro_costo_guardar");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);        
        $this->commandAddParameter(":vin_id", $centroCostoId);
        $this->commandAddParameter(":vin_centro_costo_padre_id", $padreCentroCostoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();  
        
    }    
    
    public function eliminarCentroCosto($id){
        $this->commandPrepare("sp_centro_costo_eliminarXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();                
    }
    
    public function listarCentroCosto($empresaId) {
        $this->commandPrepare(" sp_centro_costo_listarxEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
}