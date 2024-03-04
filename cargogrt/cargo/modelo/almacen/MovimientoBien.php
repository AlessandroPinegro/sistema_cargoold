<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class MovimientoBien extends ModeloBase {

    /**
     * 
     * @return MovimientoBien
     */
    static function create() {
        return parent::create();
    }

    public function guardar($movimientoId, $organizadorId, $bienId, $unidadMedidaId, $cantidad, $valorMonetario,
            $estado, $usuarioCreacionId, $precioTipoId = null, $utilidad = null, $utilidadPorcentaje = null,
            $checkIgv = 1, $adValorem = 0, $comentarioDetalle = null, $bien_activo_fijo_id = null,
            $vin_bien_alto = null, $vin_bien_ancho = null, $vin_bien_longitud = null, $vin_bien_peso = null,
            $tipo = null, $bienPesoVolumetrico = null, $bienFactorVolumetrico = null, $movimientoBienPadreId = null,
            $documentoPadreId = null, $movimientoPadreId = null, $banderaTipoPeso = null, $bienPesoTotal = null, $descripcionpaquete = null
    ) {
        $this->commandPrepare("sp_movimiento_bien_guardar");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_cantidad", $cantidad);
        $this->commandAddParameter(":vin_valor_monetario", $valorMonetario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        $this->commandAddParameter(":vin_precio_tipo_id", $precioTipoId);
        $this->commandAddParameter(":vin_utilidad", $utilidad);
        $this->commandAddParameter(":vin_utilidad_porcentaje", $utilidadPorcentaje);
        $this->commandAddParameter(":vin_incluye_igv", $checkIgv);
        $this->commandAddParameter(":vin_ad_valorem", $adValorem);
        $this->commandAddParameter(":vin_bien_activo_fijo_id", $bien_activo_fijo_id);
        $this->commandAddParameter(":vin_comentario_detalle", $comentarioDetalle);

        $this->commandAddParameter(":vin_bien_alto", $vin_bien_alto);
        $this->commandAddParameter(":vin_bien_ancho", $vin_bien_ancho);
        $this->commandAddParameter(":vin_bien_longitud", $vin_bien_longitud);
        $this->commandAddParameter(":vin_bien_peso", $vin_bien_peso);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_bien_peso_volumetrico", $bienPesoVolumetrico);
        $this->commandAddParameter(":vin_bien_factor_volumetrico", $bienFactorVolumetrico);
        $this->commandAddParameter(":vin_movimiento_bien_origen_id", $movimientoBienPadreId);
        $this->commandAddParameter(":vin_documento_padre_id", $documentoPadreId);
        $this->commandAddParameter(":vin_movimiento_padre_id", $movimientoPadreId);

        $this->commandAddParameter(":vin_bandera_tipo_peso", $banderaTipoPeso);
        $this->commandAddParameter(":vin_bien_peso_total", $bienPesoTotal);
        $this->commandAddParameter(":vin_descripcion_paquete", $descripcionpaquete);
        return $this->commandGetData();
    }

    public function obtenerXIdMovimiento($movimientoId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerXMovimiento");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function obtenerDetalleTransferenciaXIdMovimiento($movimientoId) {// para recibir transferencia
        $this->commandPrepare("sp_movimiento_bien_obtenerDetalleTransferenciaXMovimiento");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function movimientoBienDetalleGuardar($movimientoBienId, $columnaCodigo, $valorCadena, $valorFecha, $usuarioId) {
        $this->commandPrepare("sp_movimiento_bien_detalle_guardar");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_columna_codigo", $columnaCodigo);
        $this->commandAddParameter(":vin_valor_cadena", $valorCadena);
        $this->commandAddParameter(":vin_valor_fecha", $valorFecha);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoBienDetalleXMovimientoBienId($movimientoBienId) {
        $this->commandPrepare("sp_movimiento_bien_detalle_obtenerActivosXMovimientoBienId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        return $this->commandGetData();
    }

    public function guardarDocumentoAtencionSolicitud($origenId, $destinoId, $cantidadAtendida, $usuarioId) {
        $this->commandPrepare("sp_movimiento_bien_guardarAtencion");
        $this->commandAddParameter(":vin_movimiento_origen", $origenId);
        $this->commandAddParameter(":vin_movimiento_destino", $destinoId);
        $this->commandAddParameter(":vin_cantidad", $cantidadAtendida);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);

        return $this->commandGetData();
    }

    public function obtenerBienesIdRelacionadosXDocumentoId($documentoId) {
//        $this->commandPrepare("sp_movimiento_bien_obtenerBienesIdRelacionadosXDocumentoId");
        $this->commandPrepare("sp_movimiento_bien_obtenerBienesIdXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);

        return $this->commandGetData();
    }

    public function obtenerMovimientoIdXDocumentoId($documentoId) {

        $this->commandPrepare("sp_movimiento_bien_obtenerMovimientoIdXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);

        return $this->commandGetData();
    }

    public function obtenerMovimientoBienXId($moviBienId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerXId");
        $this->commandAddParameter(":vin_id", $moviBienId);
        return $this->commandGetData();
    }

    public function obtenerXMovimientoIdXMovimientoIdRelacion($movimientoId, $movimientoIdHijos) {
        $this->commandPrepare("sp_movimiento_bien_obtenerXMovimientoIdXMovimientoIdRelacion");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_movimiento_id_relacion", $movimientoIdHijos);
        return $this->commandGetData();
    }

    public function obtenerGRCantidadesPorOCId($ocId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerGRCantidadesPorOCId");
        $this->commandAddParameter(":vin_oc_id", $ocId);
        return $this->commandGetData();
    }

    public function obtenerXMovimientoIds($movimientoIds) {
        $this->commandPrepare("sp_movimiento_bien_obtenerXMovimientoIds");
        $this->commandAddParameter(":vin_movimiento_ids", $movimientoIds);
        return $this->commandGetData();
    }

    //EDICION DE DOCUMENTO
    public function actualizarEstadoXId($movimientoBienId, $estado) {
        $this->commandPrepare("sp_movimiento_bien_actualizarEstadoXId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function actualizarEstadoXDocumentoId($documentoId, $estado) {
        $this->commandPrepare("sp_movimiento_bien_actualizarEstadoXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function editar($movimientoBienId, $movimientoId, $organizadorId, $bienId, $unidadMedidaId, $cantidad, $valorMonetario, $estado, $usuarioCreacionId, $precioTipoId = null, $utilidad = null, $utilidadPorcentaje = null, $checkIgv = 1, $adValorem = 0
            , $comentarioDetalle = null, $bien_activo_fijo_id = null,
            $vin_bien_alto = null, $vin_bien_ancho = null, $vin_bien_longitud = null, $vin_bien_peso = null,
            $tipo = null, $bienPesoVolumetrico = null, $bienFactorVolumetrico = null, $movimientoBienPadreId = null,
            $documentoPadreId = null, $movimientoPadreId = null, $banderaTipoPeso = null, $bienPesoTotal = null, $descripcionpaquete= null) {
        $this->commandPrepare("sp_movimiento_bien_editar");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_cantidad", $cantidad);
        $this->commandAddParameter(":vin_valor_monetario", $valorMonetario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        $this->commandAddParameter(":vin_precio_tipo_id", $precioTipoId);
        $this->commandAddParameter(":vin_utilidad", $utilidad);
        $this->commandAddParameter(":vin_utilidad_porcentaje", $utilidadPorcentaje);
        $this->commandAddParameter(":vin_incluye_igv", $checkIgv);
        $this->commandAddParameter(":vin_ad_valorem", $adValorem);
        $this->commandAddParameter(":vin_bien_activo_fijo_id", $bien_activo_fijo_id);
        $this->commandAddParameter(":vin_comentario_detalle", $comentarioDetalle);

        $this->commandAddParameter(":vin_bien_alto", $vin_bien_alto);
        $this->commandAddParameter(":vin_bien_ancho", $vin_bien_ancho);
        $this->commandAddParameter(":vin_bien_longitud", $vin_bien_longitud);
        $this->commandAddParameter(":vin_bien_peso", $vin_bien_peso);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_bien_peso_volumetrico", $bienPesoVolumetrico);
        $this->commandAddParameter(":vin_bien_factor_volumetrico", $bienFactorVolumetrico);
        $this->commandAddParameter(":vin_movimiento_bien_origen_id", $movimientoBienPadreId);
        $this->commandAddParameter(":vin_documento_padre_id", $documentoPadreId);
        $this->commandAddParameter(":vin_movimiento_padre_id", $movimientoPadreId);

        $this->commandAddParameter(":vin_bandera_tipo_peso", $banderaTipoPeso);
        $this->commandAddParameter(":vin_bien_peso_total", $bienPesoTotal);
        $this->commandAddParameter(":descripcion_paquete", $descripcionpaquete);
        
        return $this->commandGetData();
    }

    public function obtenerMovimientoBienPedidoXId($movimientoBienId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerXMovimientoBienId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        return $this->commandGetData();
    }

    public function obtenerEntregaRelacionadaXIdMovimiento($movimientoId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerEntregasXMovimientoId");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function obtenerDetalleEntregaRelacionadaXIdMovimiento($movimientoId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerDetalleEntregasXMovimientoId");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function obtenerPedidoDetalleXDocumentoId($documentoId) {
        $this->commandPrepare("sp_movimiento_bien_obtenerPedidoDetalleXDocumentoId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $documentoId);
        return $this->commandGetData();
    }

}
