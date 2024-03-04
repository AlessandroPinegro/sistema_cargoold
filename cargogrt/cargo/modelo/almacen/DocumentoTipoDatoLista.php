<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class DocumentoTipoDatoLista extends ModeloBase {
    /**
     * 
     * @return DocumentoTipoDatoLista
     */
    static function create() {
        return parent::create();
    }

    public function listar() {
        $this->commandPrepare("sp_documento_tipo_dato_lista_listar");
        return $this->commandGetData();
    }
    public function obtenerComboDocumentoTipoDato() {
        $this->commandPrepare("sp_documento_tipo_dato_combo");
        return $this->commandGetData();
    }
    public function insertar($documentoTipoDatoId, $descripcion, $valor, $estado, $usuarioCreacion) {
        $this->commandPrepare("sp_documento_tipo_dato_lista_insertar");
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_valor", $valor);   
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        
        return $this->commandGetData();
    }
    public function obtenerPorId($documentoTipoDatoListaId)
    {
        $this->commandPrepare("sp_documento_tipo_dato_lista_obtenerXId");
         $this->commandAddParameter(":vin_documento_tipo_dato_lista_id", $documentoTipoDatoListaId);
         return $this->commandGetData();
    }
    public function actualizar($id, $documentoTipoDatoId, $descripcion, $valor, $estado) {
        $this->commandPrepare("sp_documento_tipo_dato_lista_editar");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_valor", $valor);   
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
    public function eliminar($documentoTipoDatoListaId) {
        $this->commandPrepare("sp_documento_tipo_dato_lista_eliminar");
        $this->commandAddParameter(":vin_id", $documentoTipoDatoListaId);
        return $this->commandGetData();
    }
    public function obtenerXDocumentoTipoDato($documentoTipoDatoId){
        $this->commandPrepare("sp_documento_tipo_dato_lista_obtenerXDocumentoTipoDato");
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        return $this->commandGetData();
    }
    public function cambiarEstado($idEstado) {
        $this->commandPrepare("sp_documento_tipo_dato_lista_cambiarEstado");
        $this->commandAddParameter(":vin_id", $idEstado);
        return $this->commandGetData();
    }
    
    
    //----------------------------------------------------------------------------------
    public function obtenerDocumentoTipoDatoLista($documentoTipoDatoId) {
        $this->commandPrepare("sp_documento_tipo_dato_lista_obtenerDocumentoTipoDatoLista");
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        return $this->commandGetData();
    }    
    
    public function obtenerXIds($documentoTipoDatoListaIds){
        $this->commandPrepare("sp_documento_tipo_dato_lista_obtenerXIds");
        $this->commandAddParameter(":vin_documento_tipo_dato_lista_ids", $documentoTipoDatoListaIds);
        return $this->commandGetData();        
    }
}
