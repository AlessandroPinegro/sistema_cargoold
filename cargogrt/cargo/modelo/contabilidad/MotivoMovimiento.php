<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class MotivoMovimiento extends ModeloBase {
    //put your code here

    /**
     * 
     * @return MotivoMovimiento
     */
    static function create() {
        return parent::create();
    }

    public function listarMotivosMovimiento($empresaID) {
        $this->commandPrepare("sp_motivos_movimiento_listar");
        $this->commandAddParameter(":vin_motivo_id", $empresaID);
        return $this->commandGetData();
    }

    public function eliminarMotivosCaracteristicaXMotivoId($motivoID) {
        $this->commandPrepare("sp_motivo_movimiento_caracteristica_eliminarXSubdiarioId");
        $this->commandAddParameter(":vin_motivo_id", $motivoID);
        return $this->commandGetData();
    }

    public function guardarMotivoCaracteristica($caracteristicaId, $motivoID, $usuarioCreacion) {
        $this->commandPrepare("sp_motivo_movimiento_caracteristica_guardar");
        $this->commandAddParameter(":vin_motivo_id", $motivoID);
        $this->commandAddParameter(":vin_caracteristica_id", $caracteristicaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function eliminarMotivoDocumentoXMotivoId($motivoId) {
        $this->commandPrepare("sp_motivo_movimiento_documentotipo_eliminarXMotivoId");
        $this->commandAddParameter(":vin_motivo_id", $motivoId);
        return $this->commandGetData();
    }

    public function guardarMotivoDocumento($caracteristicaId, $motivoId, $usuarioCreacion) {
        $this->commandPrepare("sp_motivo_movimiento_documentotipo_guardar");
        $this->commandAddParameter(":vin_motivo_id", $motivoId);
        $this->commandAddParameter(":vin_documentotipo_id", $caracteristicaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerMotivoMovimientoXid($id) {
        $this->commandPrepare("sp_motivo_movimiento_obtenerXid");
        $this->commandAddParameter(":vin_motivo_id", $id);
        return $this->commandGetData();
    }

    public function obtenerMotivoCaracteristicaXMotivoId($id) {
        $this->commandPrepare("sp_motivo_movimiento_caracteristica_obtenerXmotivoId");
        $this->commandAddParameter(":vin_motivo_id", $id);
        return $this->commandGetData();
    }

    public function obtenerMotivoDocumentoXMotivoId($id) {
        $this->commandPrepare("sp_motivo_movimiento_documento_obtenerXmotivoId");
        $this->commandAddParameter(":vin_motivo_id", $id);
        return $this->commandGetData();
    }

    public function guardarMotivoMovimiento($codigo, $descripcion, $nombreCorto, $tipoMotivoId, $tipoCalculoId, $tipoCambioId, $grupoId, $codigoSunatId, $estadoId, $usuarioId, $empresaId, $motivoId) {
        $this->commandPrepare("sp_motivo_movimiento_guardar");

        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_nombre_corto", $nombreCorto);
        $this->commandAddParameter(":vin_tipomotivo_id", $tipoMotivoId);
        $this->commandAddParameter(":vin_tipocalculo_id", $tipoCalculoId);
        $this->commandAddParameter(":vin_tipocambio_id", $tipoCambioId);
        $this->commandAddParameter(":vin_grupo_id", $grupoId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_motivo_id", $motivoId);

        return $this->commandGetData();
    }

    public function cambiarEstado($id) {
        $this->commandPrepare("sp_motivo_movimiento_cambiarEstado");
        $this->commandAddParameter(":vin_motivo_id", $id);

        return $this->commandGetData();
    }

    public function eliminar($id) {
        $this->commandPrepare("sp_motivo_movimiento_eliminar");
        $this->commandAddParameter(":vin_motivo_id", $id);
        return $this->commandGetData();
    }

}
