<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class Operaciones extends ModeloBase {
    /**
     * 
     * @return Operaciones
     */
    
    static function create() {
        return parent::create();
    }

    public function guardarOperacion($codigo,$descripcion,$tipoCambioId,$codigoSunatId,
                $estadoId,$subdiarioId,$sucursalId,$usuarioId,$empresaId,$operacionId,$chkEgresoBanco) {
        $this->commandPrepare("sp_operacion_guardar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion",$descripcion );
        $this->commandAddParameter(":vin_tipo_cambio", $tipoCambioId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_subdiarios_id", $subdiarioId);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $operacionId);
        $this->commandAddParameter(":vin_egreso_cheque", $chkEgresoBanco);
        return $this->commandGetData();
    }
        
    public function listarOperaciones($empresaId){
        $this->commandPrepare("sp_operacion_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();                  
    }
    
    public function cambiarEstado($id) {
        $this->commandPrepare("sp_operacion_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
        
    public function eliminar($id) {
        $this->commandPrepare("sp_operacion_eliminar");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function obtenerOperacionXid($id){
        $this->commandPrepare("sp_operacion_obtenerXid");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();        
    }    
    
    public function guardarOperacionNumeracion($sucursalId, $operacionId,$periodoId, $usuarioId){
        $this->commandPrepare("sp_operacion_numeracion_guardar");
        $this->commandAddParameter(":vin_sucursal_id", $sucursalId);
        $this->commandAddParameter(":vin_operaciones_id", $operacionId);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();                        
    }
    
    public function obtenerOperacionNumeracionXid($id)
    {
        $this->commandPrepare("sp_operacion_numeracion_obtener");
        $this->commandAddParameter(":vin_operacion_id", $id);
        return $this->commandGetData();
    }
}