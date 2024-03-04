<?php

require_once __DIR__ . '/../core/ModeloBase.php';

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class BienUnico extends ModeloBase {

    /**
     * 
     * @return BienUnico
     */
    static function create() {
        return parent::create();
    }

    public function insertarBienUnico($bienId, $codigo, $usuarioId) {
        $this->commandPrepare("sp_bien_unico_insertar");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function insertarMovimientoBienUnico($movimientoBienId, $bienUnicoId, $generaUnico, $usuarioId) {
        $this->commandPrepare("sp_movimiento_bien_unico_insertar");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_bien_unico_id", $bienUnicoId);
        $this->commandAddParameter(":vin_genera_unico", $generaUnico);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function bienUnicoObternerUltimoCodigoCorrelativo($codBienUnico) {
        $this->commandPrepare("sp_bien_unico_obtenerUltimoCodigoCorrelativo");
        $this->commandAddParameter(":vin_codigo", $codBienUnico);
        return $this->commandGetData();
    }

    public function anularBienUnicoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_bien_unico_anularXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDataBienUnicoXCriterios($bienTipoIds, $bienIds, $nroGuia, $fechaGuia, $proveedorIds, $clienteIds, $prodUnico, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,$estadoBienUnico) {
        $this->commandPrepare("sp_bien_unico_obtenerXCriterios");
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_numero_guia", $nroGuia);
        $this->commandAddParameter(":vin_fecha_guia", $fechaGuia);
        $this->commandAddParameter(":vin_proveedor_ids", $proveedorIds);
        $this->commandAddParameter(":vin_cliente_ids", $clienteIds);
        $this->commandAddParameter(":vin_bien_unico_codigo", $prodUnico);
        $this->commandAddParameter(":vin_estado_bien_unico", $estadoBienUnico);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadDataBienUnicoXCriterios($bienTipoIds, $bienIds, $nroGuia, $fechaGuia, $proveedorIds, $clienteIds, $prodUnico, $columnaOrdenar, $formaOrdenar,$estadoBienUnico )
    {                                               
        $this->commandPrepare("sp_bien_unico_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_numero_guia", $nroGuia);
        $this->commandAddParameter(":vin_fecha_guia", $fechaGuia);
        $this->commandAddParameter(":vin_proveedor_ids", $proveedorIds);
        $this->commandAddParameter(":vin_cliente_ids", $clienteIds);
        $this->commandAddParameter(":vin_bien_unico_codigo", $prodUnico);
        $this->commandAddParameter(":vin_estado_bien_unico", $estadoBienUnico);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    public function obtenerDetalleBienUnico($bienUnicoId) {
        $this->commandPrepare("sp_bien_unico_obtenerDetalle");
        $this->commandAddParameter(":vin_bien_unico_id", $bienUnicoId);
        return $this->commandGetData();        
    }
    
    public function obtenerBienUnicoXId($bienUnicoId){
        $this->commandPrepare("sp_bien_unico_obtenerXId");
        $this->commandAddParameter(":vin_bien_unico_id", $bienUnicoId);
        return $this->commandGetData();                
    }
    
    public function obtenerBienUnicoDisponibleXDocumentoId($documentoId){
        $this->commandPrepare("sp_bien_unico_obtenerDisponibleXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();                        
    }
    
    public function obtenerMovimientoBienUnicoXDocumentoId($documentoId){
        $this->commandPrepare("sp_movimiento_bien_unico_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();                                
    }    
    
    public function guardarMovimientoBienUnico($bienUnicoId,$movimientoBienId,$usuarioId){
        $this->commandPrepare("sp_movimiento_bien_unico_guardar");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_bien_unico_id", $bienUnicoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }
    
    public function eliminarMovimientoBienUnico($movimientoBienUnicoId){
        $this->commandPrepare("sp_movimiento_bien_unico_eliminar");
        $this->commandAddParameter(":vin_movimiento_bien_unico_id", $movimientoBienUnicoId);
        return $this->commandGetData();        
    }

}
