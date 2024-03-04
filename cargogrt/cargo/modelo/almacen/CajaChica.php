<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class CajaChica extends ModeloBase {
    /**
     * 
     * @return CajaChica
     */
    
    static function create() {
        return parent::create();
    }    
    
    public function listarCajaChica($documentoTipoId) {
        $this->commandPrepare("sp_documento_listarXdocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function eliminar($id) {
        $this->commandPrepare("sp_caja_chica_eliminar");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function insertarCajaChica($documentoTipoId,$monedaId,$fecha,$tipoId ,$importe,$comentario,$responsableId,$usuCreacion){
        $this->commandPrepare("sp_caja_chica_insertar");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_tipo_id", $tipoId);
        $this->commandAddParameter(":vin_importe", $importe);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_persona_id", $responsableId);
        $this->commandAddParameter(":vin_usu_creacion", $usuCreacion);
        return $this->commandGetData();
    }
}