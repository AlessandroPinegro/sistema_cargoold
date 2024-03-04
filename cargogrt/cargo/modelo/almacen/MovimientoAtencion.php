<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class MovimientoAtencion extends ModeloBase {
    /**
     * 
     * @return MovimientoAtencion
     */
    static function create() {
        return parent::create();
    }
    
    public function buscarDocumentoACopiar($empresaId, $documentoTipoIds, $personaId, $serie, $numero, $fechaEmisionInicio,
                                           $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados,
                                           $formaOrdenar, $columnaOrdenar, $tamanio,$transferenciaTipo,$movimientoTipoId=null) {
        $this->commandPrepare("sp_documento_atencion_buscarParaCopiar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limit", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $tamanio);
        $this->commandAddParameter(":vin_transferencia_tipo", $transferenciaTipo);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function buscarDocumentoACopiarTotal($empresaId, $documentoTipoIds, $personaId, $serie, $numero, 
                                                $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, 
                                                $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar,$transferenciaTipo,
                                                $movimientoTipoId=null) {
        $this->commandPrepare("sp_documento_atencion_buscarParaCopiar_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoIds);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_transferencia_tipo", $transferenciaTipo);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerXIdMovimiento($movimientoId) {
        $this->commandPrepare("sp_movimiento_bien_atencion_obtenerXMovimiento");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadProgramadaXDocumentoId($documentoId,$bienId,$unidadMedidaId,$organizadorId){
        $this->commandPrepare("sp_patencion_obtenerCantidadProgramadaXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        return $this->commandGetData();        
    }
    
    public function obtenerPAtencionLiberado($documentoId, $bienId,$unidadMedidaId){
        $this->commandPrepare("sp_patencion_obtenerLiberado");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        return $this->commandGetData();        
    }
    
    public function guardarPatencionMovimientoBien($patencionId,$movimientoBienId,$cantidadAtendida,$usuarioId){
        $this->commandPrepare("sp_patencion_movimiento_bien_guardar");
        $this->commandAddParameter(":vin_patencion_id", $patencionId);
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_cantidad", $cantidadAtendida);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();                
    }
    
    public function buscarPersonasXDocumentoTipoXValor($documentoTipoIdStringArray, $valor){
        $this->commandPrepare("sp_persona_atencion_buscarXDocumentoTipoXNombre");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_valor", $valor);
        return $this->commandGetData();
    }
    
    public function buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdStringArray, $descripcion){
        $this->commandPrepare("sp_documento_tipo_patencion_buscarXDocumentoTipo");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        return $this->commandGetData();
    }
   
    function buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdStringArray, $busqueda){        
        $this->commandPrepare("sp_documento_patencion_buscarXTipoDocumentoXSerieNumero");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }
}