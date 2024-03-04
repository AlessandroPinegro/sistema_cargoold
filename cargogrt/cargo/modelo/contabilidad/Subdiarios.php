<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Subdiarios extends ModeloBase {
    /**
     * 
     * @return Subdiarios
     */
    
    static function create() {
        return parent::create();
    }

    public function guardarSubdiario($codigo,$descripcion,$tipoCambioId,$codigoSunatId,
                $estadoId,$tipoAsientoId,$sucursalId,$usuarioId,$empresaId,$subdiarioId) {
        $this->commandPrepare("sp_subdiario_guardar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion",$descripcion );
        $this->commandAddParameter(":vin_tipo_cambio", $tipoCambioId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_tipo_asiento_id", $tipoAsientoId);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $subdiarioId);
        return $this->commandGetData();
    }
    
    public function eliminarSubdiariosCaracteristicaXSubdiarioId($subdiarioID) {
        $this->commandPrepare("sp_subdiario_caracteristica_eliminarXSubdiarioId");
        $this->commandAddParameter(":vin_subdiario_id", $subdiarioID);
        return $this->commandGetData();                
    }
    
    public function guardarSubdiariosCaracteristica($caracteristicaId, $subdiarioID, $usuarioCreacion){
        $this->commandPrepare("sp_subdiario_caracteristica_guardar");
        $this->commandAddParameter(":vin_subdiarios_id", $subdiarioID);
        $this->commandAddParameter(":vin_caracteristica_id", $caracteristicaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();                
    }
    
    public function listarSubdiarios($empresaId){
        $this->commandPrepare("sp_subdiario_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();                  
    }
    
    public function cambiarEstado($id) {
        $this->commandPrepare("sp_subdiario_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
        
    public function eliminar($id) {
        $this->commandPrepare("sp_subdiario_eliminar");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerSubdiarioXid($id){
        $this->commandPrepare("sp_subdiario_obtenerXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();        
    }
    
    public function obtenerSubdiarioCaracteristicaXSubdiarioId($subdiarioId){
        $this->commandPrepare("sp_subdiario_caracteristica_obtenerXSubdiarioId");
        $this->commandAddParameter(":vin_subdiario_id", $subdiarioId);
        return $this->commandGetData();                
    }
    
    public function guardarSubdiarioNumeracion($sucursalId, $subdiarioID,$periodoId, $usuarioId){
        $this->commandPrepare("sp_subdiario_numeracion_guardar");
        $this->commandAddParameter(":vin_sucursal_id", $sucursalId);
        $this->commandAddParameter(":vin_subdiarios_id", $subdiarioID);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();                        
    }
    
    public function obtenerSubdiarioNumeracionXid($id)
    {
        $this->commandPrepare("sp_subdiario_numeracion_obtener");
        $this->commandAddParameter(":vin_subdiario_id", $id);
        return $this->commandGetData();
    }
}