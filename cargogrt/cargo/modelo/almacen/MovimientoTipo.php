<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class MovimientoTipo extends ModeloBase {
    /**
     * 
     * @return MovimientoTipo
     */
    static function create() {
        return parent::create();
    }
    
    public function getDataMovimientoTipo() {
        $this->commandPrepare("sp_movimiento_tipo_getAll");
        return $this->commandGetData();
    }
   
    public function insertMovimientoTipo($codigo, $indicador,$descripcion, $comentario, $estado, $usuarioCreacion) {
        $this->commandPrepare("sp_movimiento_tipo_insert");
        $this->commandAddParameter(":vin_codigo", $codigo);
         $this->commandAddParameter(":vin_indicador", $indicador);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }
    public function getMovimientoTipo($id) {
        $this->commandPrepare("sp_movimiento_tipo_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    public function updateMovimientoTipo($id,$indicador,$codigo, $descripcion,$comentario, $estado) {
        $this->commandPrepare("sp_movimiento_tipo_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_indicador", $indicador);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
    public function deleteMovimientoTipo($id) {
        $this->commandPrepare("sp_movimiento_tipo_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    public function cambiarMovimientoTipoEstado($id_estado) {  
        $this->commandPrepare("sp_movimiento_tipo_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }
    public function obtenerXOpcion($opcionId) {  
        $this->commandPrepare("sp_movimiento_tipo_obtenerXOpcion");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }
    
    public function obtenerXDocumentoTipoId($documentoTipoId){  
        $this->commandPrepare("sp_movimiento_tipo_obtenerXDocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerMovimientoTipoColumna($movimientoTipoId) {
        $this->commandPrepare("sp_movimiento_tipo_columna_obtenerXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();        
    }
    
    public function obtenerXDocumentoId($documentoId){
        $this->commandPrepare("sp_movimiento_tipo_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();                
    }
    
    public function obtenerMovimientoTipoAccionesVisualizacion($movimientoTipoId){
        $this->commandPrepare("sp_movimiento_tipo_accion_visualizacion_obtenerXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();                        
    }
    
    public function obtenerDocumentoEstadoXId($movimientoTipoId){
        $this->commandPrepare("sp_movimiento_tipo_documento_estadoXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();                        
    }
}