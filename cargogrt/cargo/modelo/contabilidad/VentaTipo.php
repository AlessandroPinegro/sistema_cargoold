<?php

require_once __DIR__ . "/../core/ModeloBase.php";

/**
 * Description of VentaTipo
 *
 * @author Administrador
 */
class VentaTipo extends ModeloBase {

    /**
     * 
     * @return VentaTipo
     */
    static function create() {
        return parent::create();
    }

    public function listarVentaTipo($empresaId) {
        $this->commandPrepare("sp_venta_tipo_listar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerVentaTipoXid($id) {
        $this->commandPrepare("sp_venta_tipo_obtenerXid");
        $this->commandAddParameter(":vin_venta_tipo_id", $id);

        return $this->commandGetData();
    }

    public function guardarVentaTipoCaracteristica($caracteristicaId, $ventaTipoId, $usuarioCreacion) {
        $this->commandPrepare("sp_venta_tipo_caracteristica_guardar");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);
        $this->commandAddParameter(":vin_caracteristica_id", $caracteristicaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);

        return $this->commandGetData();
    }

    public function eliminarVentaTipoCaracteristicaXVentaTipoId($ventaTipoId) {
        $this->commandPrepare("sp_venta_tipo_caracteristica_eliminarXTipoVentaId");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);

        return $this->commandGetData();
    }

    public function guardarVentaTipo($empresaId, $codigo, $descripcion, $codigoExportacion, $notaCredito, $valorVentaInafecto, $estado, $usuarioId, $ventaTipoId) {
        $this->commandPrepare("sp_venta_tipo_guardar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo_exportacion", $codigoExportacion);
        $this->commandAddParameter(":vin_nota_credito", $notaCredito);
        $this->commandAddParameter(":vin_valor_venta_inafecto", $valorVentaInafecto);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);

        return $this->commandGetData();
    }

    public function obtenerVentaTipoCaracteristicaXTipoVentaIdXTipo($ventaTipoId, $tipoCaracteristica) {
        $this->commandPrepare("sp_venta_tipo_caracteristica_obtenerXIdTipoVentaXTipo");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);
        $this->commandAddParameter(":vin_caracteristica_tipo", $tipoCaracteristica);

        return $this->commandGetData();
    }

    public function guardarVentaTipoDocumento($documentoId, $ventaTipoId, $usuarioCreacion) {
        $this->commandPrepare("sp_venta_tipo_documento_guardar");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);

        return $this->commandGetData();
    }

    public function eliminarVentaTipoDocumentoXVentaTipoId($ventaTipoId) {
        $this->commandPrepare("sp_venta_tipo_documento_eliminarXVentaTipoId");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);

        return $this->commandGetData();
    }

    public function obtenerVentaTipoDocumentoXVentaTipoId($ventaTipoId) {
        $this->commandPrepare("sp_venta_tipo_documento_obtenerXVentaTipoId");
        $this->commandAddParameter(":vin_venta_tipo_id", $ventaTipoId);

        return $this->commandGetData();
    }

    public function cambiarEstado($id) {
        $this->commandPrepare("sp_venta_tipo_cambiarEstado");
        $this->commandAddParameter(":vin_venta_tipo_id", $id);

        return $this->commandGetData();
    }
    
    public function eliminar($id)
    {
        $this->commandPrepare("sp_venta_tipo_eliminar");
        $this->commandAddParameter(":vin_venta_tipo_id", $id);
        
        return $this->commandGetData();
    }

}
