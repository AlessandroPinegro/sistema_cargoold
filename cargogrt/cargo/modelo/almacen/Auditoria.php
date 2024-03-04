<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Auditoria extends ModeloBase {
    
    /**
     * 
     * @return Auditoria
     */

    static function create() {
        return parent::create();
    }
     public function obtenerAuditoriaXCriterios($personaId, $fechaInicio,$fechaFin, $comenatrio,$empresaId,$columnaOrdenar, $formaOrdenar,$elemntosFiltrados,$start)
    {
        $this->commandPrepare("sp_auditoria_obtenerXCriterios");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_comentario", $comenatrio);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    
    public function obtenerCantidadAuditoriaXCriterios($personaId, $fechaInicio,$fechaFin, $comenatrio,$empresaId,$columnaOrdenar, $formaOrdenar,$elemntosFiltrados,$start)
    {
        $this->commandPrepare("sp_auditoria_consulta_contador");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_comentario", $comenatrio);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    public function insertarAuditoria($personId,$fecha,$comentario,$usuarioId)
    {
            $this->commandPrepare("sp_auditoria_insertar");
            $this->commandAddParameter(":vin_persona_id", $personId);
            $this->commandAddParameter(":vin_fecha", $fecha);
            $this->commandAddParameter(":vin_comenatario", $comentario);
            $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
            return $this->commandGetData();
    }
    
    public function actualizarAuditoria($auditoriaId,$personId,$fecha,$comentario,$usuarioId)
    {
            $this->commandPrepare("sp_auditoria_actualizar");
            $this->commandAddParameter(":vin_auditoria_id", $auditoriaId);
            $this->commandAddParameter(":vin_persona_id", $personId);
            $this->commandAddParameter(":vin_fecha", $fecha);
            $this->commandAddParameter(":vin_comentario", $comentario);
            $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
            return $this->commandGetData();
    }
    
    public function insertarAuditoriaBien($auditoriaId,$organizadorId,$bienId,$unidadMedidaId,$stockSistema,$stockReal,$discrepancia,$usuarioId)
    {
            $this->commandPrepare("sp_auditoria_bien_insertar");
            $this->commandAddParameter(":vin_auditoria_id", $auditoriaId);
            $this->commandAddParameter(":vin_organizador_id", $organizadorId);
            $this->commandAddParameter(":vin_bien_id", $bienId);
            $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
            $this->commandAddParameter(":vin_stock_sistema", $stockSistema);
            $this->commandAddParameter(":vin_stock_real", $stockReal);
            $this->commandAddParameter(":vin_discrepancia", $discrepancia);
            $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
            return $this->commandGetData();
    }
    public function listaAuditoria($organizadorId,$bienId,$BienTipoId,$emisionInicio,$emisionFin,$empresaId = -1) {
        
        $this->commandPrepare("sp_auditoria_bien_obtenerXCriterios");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
//        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
//        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
//        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
//        $this->commandAddParameter(":vin_tamanio", $tamanio);
        return $this->commandGetData();
    }
    
    public function obtenerDetalleAuditoria($auditoriaId)
    {
        $this->commandPrepare("sp_auditoria_obtenerDetalle");
        $this->commandAddParameter(":vin_auditoria_id", $auditoriaId);
        return $this->commandGetData();
    }
}
