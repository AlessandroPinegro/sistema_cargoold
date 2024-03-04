
<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class Movimiento extends ModeloBase
{

    /**
     *
     * @return Movimiento
     */
    static function create()
    {
        return parent::create();
    }

    //Cambios Cristopher
    public function actualizarGrtDespacho($grtId, $vehiculoId, $pilotoId, $copilotoID) {
        $this->commandPrepare("sp_actualizarGRTdDespacho");
        $this->commandAddParameter(":grtId", $grtId);
        $this->commandAddParameter(":vehiculoId", $vehiculoId);
        $this->commandAddParameter(":pilotoId", $pilotoId);
        $this->commandAddParameter(":copilotoID", $copilotoID);

        return $this->commandGetData();
    }

    public function obtenerDocumentosPedidosXCriterios(
        $numeroPedido = NULL,
        $fechaPedido = NULL,
        $personaId = NULL,
        $documentoTipoComprobanteId = NULL,
        $numeroComprobante = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $documentoEstadoId = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $usuarioId = NULL,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null,
        $movimientoTipoId = null,
        $personaOrigenId = null,
        $personaDestinoId = null,
        $agenciaId = null,
        $cajaId = null,
        $banderaDevolucionCargo = null,
        $banderaDestino = null,
        $semaforizacion = null,
    ) {
        $this->commandPrepare("sp_documento_pedido_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_pedido", $numeroPedido);
        $this->commandAddParameter(":vin_fecha_pedido", $fechaPedido);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":documento_tipo_comprobante_id", $documentoTipoComprobanteId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);

        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);

        $this->commandAddParameter(":vin_persona_origen_id", $personaOrigenId);
        $this->commandAddParameter(":vin_persona_destino_id", $personaDestinoId);

        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_bandera_cargo", $banderaDevolucionCargo);
        $this->commandAddParameter(":vin_bandera_destino", $banderaDestino);
        $this->commandAddParameter(":semaforizacion", $semaforizacion);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPosicionVentasXCriteriosDetalle(
        $numeroPedido = NULL,
        $seriePedido = NULL,
        $fechaPedido = NULL,
        $personaId = NULL,
        $documentoTipoComprobanteId = NULL,
        $numeroComprobante = NULL,
        $serieComprobante = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $documentoEstadoId = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $usuarioId = NULL,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null,
        $movimientoTipoId = null,
        $personaOrigenId = null,
        $personaDestinoId = null,
        $agenciaId = null,
        $cajaId = null,
        $banderaDevolucionCargo = null,
        $modalidadId = NULL,
        $ubigeoOrigen = NULL,
        $ubigeoDestino = NULL,
        $articulo = NULL,
        $fechaFinP = NULL
    ) {
        $this->commandPrepare("sp_documento_posicion_ventas_detalle_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_pedido", $numeroPedido);
        $this->commandAddParameter(":vin_serie_pedido", $seriePedido);
        $this->commandAddParameter(":vin_fecha_pedido", $fechaPedido);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":documento_tipo_comprobante_id", $documentoTipoComprobanteId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_serie_comprobante", $serieComprobante);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);

        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);

        $this->commandAddParameter(":vin_persona_origen_id", $personaOrigenId);
        $this->commandAddParameter(":vin_persona_destino_id", $personaDestinoId);

        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_bandera_cargo", $banderaDevolucionCargo);
        $this->commandAddParameter(":vin_modalidad_id", $modalidadId);
        $this->commandAddParameter(":vin_ubigeo_origen", $ubigeoOrigen);
        $this->commandAddParameter(":vin_ubigeo_destino", $ubigeoDestino);
        $this->commandAddParameter(":vin_articulo", $articulo);
        $this->commandAddParameter(":vin_fecha_pedido_fin", $fechaFinP);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPosicionVentasXCriterios(
        $numeroPedido = NULL,
        $seriePedido = NULL,
        $fechaPedido = NULL,
        $personaId = NULL,
        $documentoTipoComprobanteId = NULL,
        $numeroComprobante = NULL,
        $serieComprobante = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $documentoEstadoId = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $usuarioId = NULL,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null,
        $movimientoTipoId = null,
        $personaOrigenId = null,
        $personaDestinoId = null,
        $agenciaId = null,
        $cajaId = null,
        $banderaDevolucionCargo = null,
        $modalidadId = NULL,
        $ubigeoOrigen = NULL,
        $ubigeoDestino = NULL,
        $fechaFinP = NULL
    ) {
        $this->commandPrepare("sp_documento_posicion_ventas_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_pedido", $numeroPedido);
        $this->commandAddParameter(":vin_serie_pedido", $seriePedido);
        $this->commandAddParameter(":vin_fecha_pedido", $fechaPedido);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":documento_tipo_comprobante_id", $documentoTipoComprobanteId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_serie_comprobante", $serieComprobante);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);

        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);

        $this->commandAddParameter(":vin_persona_origen_id", $personaOrigenId);
        $this->commandAddParameter(":vin_persona_destino_id", $personaDestinoId);

        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_bandera_cargo", $banderaDevolucionCargo);
        $this->commandAddParameter(":vin_modalidad_id", $modalidadId);
        $this->commandAddParameter(":vin_ubigeo_origen", $ubigeoOrigen);
        $this->commandAddParameter(":vin_ubigeo_destino", $ubigeoDestino);
        $this->commandAddParameter(":vin_fecha_pedido_fin", $fechaFinP);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPosicionVentasXCriteriosExcel(
        $numeroPedido = NULL,
        $seriePedido = NULL,
        $fechaPedido = NULL,
        $personaId = NULL,
        $documentoTipoComprobanteId = NULL,
        $numeroComprobante = NULL,
        $serieComprobante = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $documentoEstadoId = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $usuarioId = NULL,
        $movimientoTipoId = null,
        $personaOrigenId = null,
        $personaDestinoId = null,
        $agenciaId = null,
        $cajaId = null,
        $banderaDevolucionCargo = null,
        $modalidadId = NULL,
        $ubigeoOrigen = NULL,
        $ubigeoDestino = NULL,
        $fechaFinP = NULL
    ) {
        $this->commandPrepare("sp_documento_posicion_ventas_excel_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_pedido", $numeroPedido);
        $this->commandAddParameter(":vin_serie_pedido", $seriePedido);
        $this->commandAddParameter(":vin_fecha_pedido", $fechaPedido);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":documento_tipo_comprobante_id", $documentoTipoComprobanteId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_serie_comprobante", $serieComprobante);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);

        $this->commandAddParameter(":vin_persona_origen_id", $personaOrigenId);
        $this->commandAddParameter(":vin_persona_destino_id", $personaDestinoId);

        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_bandera_cargo", $banderaDevolucionCargo);
        $this->commandAddParameter(":vin_modalidad_id", $modalidadId);
        $this->commandAddParameter(":vin_ubigeo_origen", $ubigeoOrigen);
        $this->commandAddParameter(":vin_ubigeo_destino", $ubigeoDestino);
        $this->commandAddParameter(":vin_fecha_pedido_fin", $fechaFinP);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPosicionVentasXCriteriosExcelDetalle(
        $numeroPedido = NULL,
        $seriePedido = NULL,
        $fechaPedido = NULL,
        $personaId = NULL,
        $documentoTipoComprobanteId = NULL,
        $numeroComprobante = NULL,
        $serieComprobante = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $documentoEstadoId = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $usuarioId = NULL,
        $movimientoTipoId = null,
        $personaOrigenId = null,
        $personaDestinoId = null,
        $agenciaId = null,
        $cajaId = null,
        $banderaDevolucionCargo = null,
        $modalidadId = NULL,
        $ubigeoOrigen = NULL,
        $ubigeoDestino = NULL,
        $articulo = NULL,
        $fechaFinP = NULL
    ) {
        $this->commandPrepare("sp_documento_posicion_ventas_detalle_excel_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_pedido", $numeroPedido);
        $this->commandAddParameter(":vin_serie_pedido", $seriePedido);
        $this->commandAddParameter(":vin_fecha_pedido", $fechaPedido);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":documento_tipo_comprobante_id", $documentoTipoComprobanteId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_serie_comprobante", $serieComprobante);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);

        $this->commandAddParameter(":vin_persona_origen_id", $personaOrigenId);
        $this->commandAddParameter(":vin_persona_destino_id", $personaDestinoId);

        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        $this->commandAddParameter(":vin_bandera_cargo", $banderaDevolucionCargo);
        $this->commandAddParameter(":vin_modalidad_id", $modalidadId);
        $this->commandAddParameter(":vin_ubigeo_origen", $ubigeoOrigen);
        $this->commandAddParameter(":vin_ubigeo_destino", $ubigeoDestino);
        $this->commandAddParameter(":vin_articulo", $articulo);
        $this->commandAddParameter(":vin_fecha_pedido_fin", $fechaFinP);
        return $this->commandGetData();
    }

    public function obtenerDocumentosLiquidacionXCriterios(
        $numeroLiquidacion = NULL,
        $fechaLiquidacionInicio = NULL,
        $fechaLiquidacionFin = NULL,
        $personaId = NULL,
        $numeroComprobante = NULL,
        $fechaComprobanteInicio = NULL,
        $fechaComprobanteFin = NULL,
        $documentoEstadoId = NULL,
        $usuarioId = NULL,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null,
        $movimientoTipoId = null,
        $agenciaId = null,
        $cajaId = null
    ) {
        $this->commandPrepare("sp_documento_liquidacion_obtenerXCriterios");

        $this->commandAddParameter(":vin_nro_liquidacion", $numeroLiquidacion);
        $this->commandAddParameter(":vin_fecha_liquidacion_inicio", $fechaLiquidacionInicio);
        $this->commandAddParameter(":vin_fecha_liquidacion_fin", $fechaLiquidacionFin);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_fecha_comprobante_inicio", $fechaComprobanteInicio);
        $this->commandAddParameter(":vin_fecha_comprobante_fin", $fechaComprobanteFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);

        return $this->commandGetData();
    }

    public function obtenerDocumentosLiquidacionExcelXCriterios(
        $numeroLiquidacion = NULL,
        $fechaLiquidacionInicio = NULL,
        $fechaLiquidacionFin = NULL,
        $personaId = NULL,
        $numeroComprobante = NULL,
        $fechaComprobanteInicio = NULL,
        $fechaComprobanteFin = NULL,
        $documentoEstadoId = NULL,
        $usuarioId = NULL,
        $movimientoTipoId = null,
        $agenciaId = null,
        $cajaId = null
    ) {
        $this->commandPrepare("sp_documento_liquidacion_obtenerXCriteriosExcel");

        $this->commandAddParameter(":vin_nro_liquidacion", $numeroLiquidacion);
        $this->commandAddParameter(":vin_fecha_liquidacion_inicio", $fechaLiquidacionInicio);
        $this->commandAddParameter(":vin_fecha_liquidacion_fin", $fechaLiquidacionFin);
        $this->commandAddParameter(":persona_id", $personaId);
        $this->commandAddParameter(":vin_nro_comprobante", $numeroComprobante);
        $this->commandAddParameter(":vin_fecha_comprobante_inicio", $fechaComprobanteInicio);
        $this->commandAddParameter(":vin_fecha_comprobante_fin", $fechaComprobanteFin);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);

        return $this->commandGetData();
    }

    public function obtenerDocumentosNotaVentaXCriterios(
        $serie = NULL,
        $numero = NULL,
        $personaId = NULL,
        $fechaInicio = NULL,
        $fechaFin = NULL,
        $agenciaOrigenId = NULL,
        $agenciaDestinoId = NULL,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null,
        $documentoId = null
    ) {
        $this->commandPrepare("sp_documento_nota_venta_obtenerXCriterios");

        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentosXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId = null, $estadoNegocioPago = null)
    {
        $this->commandPrepare("sp_movimiento_obtenerXCriterios");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionDesde);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionHasta);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_fecha_tentativa_desde", $fechaTentativaDesde);
        $this->commandAddParameter(":vin_fecha_tentativa_hasta", $fechaTentativaHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado_negocio_pago", $estadoNegocioPago);
        return $this->commandGetData();
    }

    //, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start
    public function obtenerDocumentosXCriteriosExcel($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta)
    {
        $this->commandPrepare("sp_movimiento_obtenerXCriteriosExcel");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionDesde);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionHasta);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_fecha_tentativa_desde", $fechaTentativaDesde);
        $this->commandAddParameter(":vin_fecha_tentativa_hasta", $fechaTentativaHasta);
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId = null, $estadoNegocioPago = null)
    {
        $this->commandPrepare("sp_movimiento_consulta_contador");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision_desde", $fechaEmisionDesde);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $fechaEmisionHasta);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_fecha_tentativa_desde", $fechaTentativaDesde);
        $this->commandAddParameter(":vin_fecha_tentativa_hasta", $fechaTentativaHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_estado_negocio_pago", $estadoNegocioPago);
        //  $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        // $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function ObtenerTotalDeRegistros()
    {
        $this->commandPrepare("sp_obtener_CantidadDeRegistrosDeConsultas");
        return $this->commandGetData();
    }

    public function ObtenerMovimientoTipoPorOpcion($opcionId, $codigo = null)
    {
        $this->commandPrepare("sp_movimiento_obtenerXOpcionId");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        return $this->commandGetData();
    }

    public function ObtenerMovimientoTipoDocumentoTipoPorMovimientoTipoID($movimientoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_documento_tipoXMovimientoTipoID");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function guardar($movimientoTipoId, $estado, $usuarioCreacionId)
    {
        $this->commandPrepare("sp_movimiento_guardar");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        return $this->commandGetData();
    }

    public function sp_movimiento_tipo_documento_tipo_XMovimientoTipoXDocumentoTipo($movimientoTipoId, $documentoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_documento_tipo_XMovimientoTipoXDocumentoTipo");
        $this->commandAddParameter(":vin_movimmiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoTipoAcciones($movimientoTipoId, $tipoAccion = 1)
    {
        $this->commandPrepare("sp_movimiento_tipo_accion_obtenerPorMovimientoTipo");
        $this->commandAddParameter(":vin_movimmiento_tipo_id", $movimientoTipoId);
        $this->commandAddParameter(":vin_tipo_accion", $tipoAccion);
        return $this->commandGetData();
    }

    public function obtenerMovimientoTipoAccioneXOpcionIdXTipo($opcionId, $usuarioId, $tipoAccion = 1)
    {
        $this->commandPrepare("sp_movimiento_tipo_accion_obtenerXOpcionIdXUsuarioId");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_tipo_accion", $tipoAccion);
        return $this->commandGetData();
    }

    // FUNCIONES PARA COPIAR DOCUMENTO

    public function buscarDocumentoACopiar($empresaId, $documentoTipoIds, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $transferenciaTipo, $movimientoTipoId = null)
    {
        $this->commandPrepare("sp_documento_buscarParaCopiar");
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

    public function buscarDocumentoACopiarTotal($empresaId, $documentoTipoIds, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $transferenciaTipo, $movimientoTipoId = null)
    {
        $this->commandPrepare("sp_documento_buscarParaCopiar_contador");
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

    //FIN COPIAR DOCUMENTO

    public function registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion)
    {
        $this->commandPrepare("sp_bien_tramo_guardar");
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_cantidad", $cantidadTramo);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        return $this->commandGetData();
    }

    public function obtenerTramoBienXBienId($bienId)
    {
        $this->commandPrepare("sp_bien_tramo_obtenerXBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function actualizarBienTramoEstado($bienTramoId, $movimientoId)
    {
        $this->commandPrepare("sp_bien_tramo_actualizarEstado");
        $this->commandAddParameter(":vin_bien_tramo_id", $bienTramoId);
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoTipoAccionEnvioPredeterminado($movimientoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_obtenerAccionEnvioPredeterminado");
        $this->commandAddParameter(":vin_movimmiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoTipoColumnaListaXMovimientoTipoId($movimientoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_columna_lista_obtenerXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function obtenerMovimientoEntradaSalidaXFechaXBienId($fechaEmision, $bienId)
    {
        $this->commandPrepare("sp_movimiento_bien_obtenerEntradaSalidaXFechaXBienId");
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function getUserEmailByUserId($id)
    {
        $this->commandPrepare("sp_movimiento_getUserEmailById");
        $this->commandAddParameter(":vin_user_id", $id);
        return $this->commandGetData();
    }

    public function verificarDocumentoObligatorioExiste($actualId)
    {
        $this->commandPrepare("sp_movimiento_verificarDocumentoObligatorioExiste");
        $this->commandAddParameter(":vin_actual_id", $actualId);
        return $this->commandGetData();
    }

    public function verificarDocumentoEsObligatorioXOpcionID($opcionId)
    {
        $this->commandPrepare("sp_movimiento_DocumentoEsObligatorioXOpcionID");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function obtenerEstadoNegocioXMovimientoId($movimientoId)
    {
        $this->commandPrepare("sp_movimiento_obtenerEstadoNegocioXMovimientoId");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);

        return $this->commandGetData();
    }

    public function obtenerMovimientoTipoRecepcionXEmpresaIdXCodigo($empresaId, $codigo)
    {
        $this->commandPrepare("sp_movimiento_tipo_recepcion_obtenerXEmpresaIdXCodigo");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        return $this->commandGetData();
    }

    public function obtenerDocumentoRelacionadoTipoRecepcion($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_tipo_recepcion_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerNroTicketEFACT($documentoId)
    {
        $this->commandPrepare("sp_obtener_nro_ticket_EFACT");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPorVerificarAnulacionSunat()
    {
        $this->commandPrepare("sp_obtener_documentosPorVerificarAnulacionSunat");
        return $this->commandGetData();
    }

    public function guardarRegistroQR($textoQR, $tipo, $referenciaId, $usuarioId)
    {
        $this->commandPrepare("sp_detalle_qr_insertar");
        $this->commandAddParameter(":vin_valor", $textoQR);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_referencia", $referenciaId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerDataQR($tipoTexto, $textoQR)
    {
        $this->commandPrepare("sp_detalle_qr_obtenerData");
        $this->commandAddParameter(":vin_valor", $textoQR);
        $this->commandAddParameter(":vin_tipo_texto", $tipoTexto);
        return $this->commandGetData();
    }

    public function UpdateTrackingCancelarReparto($codigo_paquete, $motivo)
    {
        $this->commandPrepare("sp_paquete_tracking_update_cancelar_reparto");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_motivo", $motivo);
        return $this->commandGetData();
    }

    public function UpdateTrackingrepartoNoEntregado($codigo_paquete, $motivo, $observacion)
    {
        $this->commandPrepare("sp_paquete_tracking_update_reparto_no_entregado");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_motivo", $motivo);
        $this->commandAddParameter(":vin_observacion", $observacion);
        return $this->commandGetData();
    }

    public function UpdateTracking($codigo_paquete)
    {
        $this->commandPrepare("sp_paquete_tracking_update");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        return $this->commandGetData();
    }

    public function InsertTracking($codigo_paquete, $organizadorId, $usuarioCreacion)
    {
        $this->commandPrepare("sp_paquete_tracking_insert");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_organizadorId", $organizadorId);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function InsertTrackingRecepcion($codigo_paquete, $id_movimiento_bien, $usuarioCreacion, $organizador_defecto)
    {
        $this->commandPrepare("sp_paquete_tracking_recepcion_insert");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_id_movimiento_bien", $id_movimiento_bien);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        $this->commandAddParameter(":vin_organizador", $organizador_defecto);
        return $this->commandGetData();
    }

    public function ListarPaquete($id_agencia, $fecha)
    {
        $this->commandPrepare("sp_paquete_tracking_getAll");
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }

    public function obtenerPaquetesxDocumento($documentoId)
    {
        $this->commandPrepare("sp_documento_paquete_getAll");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
         return $this->commandGetData();
    }
     //:::DESARROLLO JESUS
    public function obtenerEstadoPaquetexManifiesto($documentoId){
        $this->commandPrepare("sp_obtener_estado_pedidos_manifiesto_vj");
        $this->commandAddParameter(":id_manifiesto", $documentoId);
         return $this->commandGetData();
    }
    public function desbloquearAutomaticamenteCarguero($documentoId){
        $this->commandPrepare("sp_desbloquearCargueroAutomaticamente_vj");
        $this->commandAddParameter(":id_manifiesto", $documentoId);
         return $this->commandGetData();
    }
     //:::DESARROLLO JESUS

    public function obtenerPaquetesxDocumento2($documentoId, $estado)
    {
        $this->commandPrepare("sp_documento_paquete_getAll_rechazado");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function obtenerPaquetesxDocumentoManifiesto($documentoId, $agencia_id, $estado, $tipo)
    {
        $this->commandPrepare("sp_documento_paquete_manifiesto_getAll");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_agencia", $agencia_id);
        return $this->commandGetData();
    }

    public function obtenerPaquetesxManifiestoDespachoIdXAgenciaId($documentoId, $agenciaId, $agenciaDestinoId)
    {
        $this->commandPrepare("sp_paquete_obtenerXManifiestoDespachoIdXAgenciaId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        return $this->commandGetData();
    }

    public function obtenerPaquetesxDocumentoManifiestoReparto($documentoId, $estado, $tipo)
    {
        $this->commandPrepare("sp_documento_paquete_manifiesto_reparto_getAll");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_tipo", $tipo);

        return $this->commandGetData();
    }

    public function obtenerPaquetesxAgenciaManifiesto($agenciaDestinoId, $id_agencia)
    {
        $this->commandPrepare("sp_documento_paquete_agencia_getAll");
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);
        return $this->commandGetData();
    }

    public function UpdateTackingAnterior($id)
    {
        $this->commandPrepare("sp_paquete_tracking_anterior_update");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function UpdateTackingAnteriorAlmacenar($id)
    {
        $this->commandPrepare("sp_paquete_tracking_almacenar_anterior_update");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function DelateTacking($id)
    {
        $this->commandPrepare("sp_paquete_tracking_delate");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerMovimientoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_movimiento_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        return $this->commandGetData();
    }

    public function InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion)
    {
        $this->commandPrepare("sp_movimiento_movimientoBien_insert");
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_id_bien", $id_bien);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function InsertTrackingDespacho($codigo_paquete, $id_movimiento_bien, $usuarioCreacion)
    {
        $this->commandPrepare("sp_paquete_tracking_despacho_insert");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_id_movimiento_bien", $id_movimiento_bien);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function InsertTrackingReparto($codigo_paquete, $id_movimiento_bien, $usuarioCreacion)
    {
        $this->commandPrepare("sp_paquete_tracking_reparto_insert");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_id_movimiento_bien", $id_movimiento_bien);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function InsertTrackingEntregado($codigo_paquete, $id_movimiento_bien, $usuarioCreacion)
    {
        $this->commandPrepare("sp_paquete_tracking_entregado_insert");
        $this->commandAddParameter(":vin_id", $codigo_paquete);
        $this->commandAddParameter(":vin_id_movimiento_bien", $id_movimiento_bien);
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerPaquetesxRepartir($documentoId)
    {
        $this->commandPrepare("sp_paquete_tracking_reparto_obtener");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId,$serie,$numero)
    {
        $this->commandPrepare("sp_paquete_tracking_reparto_detalle_obtener");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        $this->commandAddParameter(":vin_id_persona", $personaId);
        $this->commandAddParameter(":vin_id_direccion", $direccionId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        return $this->commandGetData();
    }

    public function obtenerPaquetesEntregados($documentoId)
    {
        $this->commandPrepare("sp_paquete_tracking_entregado_obtener");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentoxPaquete($codigo_paquete)
    {
        $this->commandPrepare("sp_paquete_documento_obtener");
        $this->commandAddParameter(":vin_id_paquete", $codigo_paquete);
        return $this->commandGetData();
    }

    public function obtenerVerificarDespachoxPaqueteId($paqueteId)
    {
        $this->commandPrepare("sp_paquete_verificarDespachoXPaqueteId");
        $this->commandAddParameter(":vin_paquete_id", $paqueteId);
        return $this->commandGetData();
    }



    

    public function obtenerDocumentoRxPaquete($documentoId, $codigo_paquete)
    {
        $this->commandPrepare("sp_paquete_documento_Robtener");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        $this->commandAddParameter(":vin_id_paquete", $codigo_paquete);
        return $this->commandGetData();
    }

    public function obtenerDocumentoRecepcionxPaquete($documentoId, $codigo_paquete, $documentoId2)
    {
        $this->commandPrepare("sp_paquete_documento_recepcion_obtener");
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        $this->commandAddParameter(":vin_id_paquete", $codigo_paquete);
        $this->commandAddParameter(":vin_id_documento_manifiesto", $documentoId2);
        return $this->commandGetData();
    }

    public function obtenerAlmacenxPaquete($organizadorId, $codigo_paquete)
    {
        $this->commandPrepare("sp_paquete_almacen_obtener");
        $this->commandAddParameter(":vin_id_organizador", $organizadorId);
        $this->commandAddParameter(":vin_id_paquete", $codigo_paquete);
        return $this->commandGetData();
    }

    public function ObtenerTackingId($id, $tipo)
    {
        $this->commandPrepare("sp_paquete_tracking_obtener");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function paqueteTrackingRevertirEstadoMovimiento($id, $usuarioId)
    {
        $this->commandPrepare("sp_paquete_tracking_revertirEstadoEliminaMovimiento");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    

    // public function getManifiestos() {
    //     $this->commandPrepare("sp_documento_manifiesto_getALL");
    //     return $this->commandGetData();
    // }
    public function getDespachos($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despa)
    {
        $this->commandPrepare("sp_despacho_getAll");
        $this->commandAddParameter(":vin_bus", $bus_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_conductor", $conductor_id);
        $this->commandAddParameter(":vin_copiloto", $copiloto_id);
        $this->commandAddParameter(":vin_nro_manifiesto", $nro_manifiesto);
        $this->commandAddParameter(":vin_origen", $origin_id);
        $this->commandAddParameter(":vin_destino", $destino_id);
        $this->commandAddParameter(":vin_estado", $estado_id);
        $this->commandAddParameter(":vin_nro_despacho", $nro_despa);
        return $this->commandGetData();
    }

    public function obtenerDocumentosDespachoXCriterios(
        $bus_id,
        $fecha_salida,
        $conductor_id,
        $copiloto_id,
        $nro_manifiesto,
        $origin_id,
        $destino_id,
        $estado_id,
        $nro_despa,
        $bandera_devolucion = null,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null
    ) {
        $this->commandPrepare("sp_documento_obtenerDespachoXCriterios");
        $this->commandAddParameter(":vin_bus", $bus_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_conductor", $conductor_id);
        $this->commandAddParameter(":vin_copiloto", $copiloto_id);
        $this->commandAddParameter(":vin_nro_manifiesto", $nro_manifiesto);
        $this->commandAddParameter(":vin_origen", $origin_id);
        $this->commandAddParameter(":vin_destino", $destino_id);
        $this->commandAddParameter(":vin_estado", $estado_id);
        $this->commandAddParameter(":vin_nro_despacho", $nro_despa);

        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_bandera_devolucion", $bandera_devolucion);

        return $this->commandGetData();
    }

    public function obtenerDocumentosManifiestoRepartoXCriterios(
        $bus_id,
        $fecha_salida,
        $conductor_id,
        $nro_manifiesto,
        $origin_id,
        $estado_id,
        $columnaOrdenar = null,
        $formaOrdenar = null,
        $elemntosFiltrados = null,
        $start = null
    ) {
        $this->commandPrepare("sp_documento_obtenerManifiestoRepartoXCriterios");
        $this->commandAddParameter(":vin_bus", $bus_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_conductor", $conductor_id);
        $this->commandAddParameter(":vin_nro_manifiesto", $nro_manifiesto);
        $this->commandAddParameter(":vin_origen", $origin_id);
        $this->commandAddParameter(":vin_estado", $estado_id);

        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function getManifiestosReparto($bus_id, $fecha_salida, $conductor_id, $nro_manifiesto, $origin_id, $estado_id)
    {
        $this->commandPrepare("sp_manifiesto_reparto_getAll");
        $this->commandAddParameter(":vin_bus", $bus_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_conductor", $conductor_id);
        $this->commandAddParameter(":vin_nro_manifiesto", $nro_manifiesto);
        $this->commandAddParameter(":vin_origen", $origin_id);
        $this->commandAddParameter(":vin_estado", $estado_id);
        return $this->commandGetData();
    }

    public function obtenerDespachoDetalle($despachoId)
    {
        $this->commandPrepare("sp_documento_despachoDetalleXDespachoId");
        $this->commandAddParameter(":vin_despacho_id", $despachoId);
        return $this->commandGetData();
    }

    public function getManifiesto($despacho_id, $destino_id = null, $manifiestos = null)
    {
        $this->commandPrepare("sp_manifiesto_getAll");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        $this->commandAddParameter(":vin_destino_id", $destino_id);
        $this->commandAddParameter(":vin_manifiestos", $manifiestos);
        return $this->commandGetData();
    }

    public function getManifiestoReparto($manifiesto_id)
    {
        $this->commandPrepare("sp_manifiesto_reparto_nro_getAll");
        $this->commandAddParameter(":vin_despacho_id", $manifiesto_id);
        return $this->commandGetData();
    }

    public function obtenerDetalleManifiesto($manifiesto_id)
    {
        $this->commandPrepare("sp_manifiesto_obtenerDetalle");
        $this->commandAddParameter(":vin_id", $manifiesto_id);
        return $this->commandGetData();
    }

    public function getDepartamento($ubigeo_id)
    {
        $this->commandPrepare("sp_traer_departamento");
        $this->commandAddParameter(":vin_ubigeo_id", $ubigeo_id);
        return $this->commandGetData();
    }

    public function verificar($manifiesto_id)
    {
        $this->commandPrepare("sp_verificar_guia_remision_transp");
        $this->commandAddParameter(":vin_documento_id", $manifiesto_id);
        return $this->commandGetData();
    }

    public function verificarManifiestoReparto($manifiesto_id)
    {
        $this->commandPrepare("sp_verificar_guia_remision_transp_manifiesto");
        $this->commandAddParameter(":vin_documento_id", $manifiesto_id);
        return $this->commandGetData();
    }

    public function insertGuiaRT($idDespacho, $movimiento_id, $serie, $numero, $moneda_id, $usuario_creacion, $agencia_id, $agencia_destino_id, $vehiculo_id, $persona_origen, $persona_destino_id, $piloto, $copiloto, $periodo, $fecha)
    {
        $this->commandPrepare("sp_inset_guia_remision_transportista");
        $this->commandAddParameter(":vin_documento_id", $idDespacho);
        $this->commandAddParameter(":vin_movimiento_id", $movimiento_id);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_moneda_id", $moneda_id);
        $this->commandAddParameter(":vin_usuario_creacion", $usuario_creacion);
        $this->commandAddParameter(":vin_agencia_id", $agencia_id);
        $this->commandAddParameter(":vin_agencia_destino_id", $agencia_destino_id);
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculo_id);
        $this->commandAddParameter(":vin_persona_origen_id ", $persona_origen);
        $this->commandAddParameter(":vin_persona_destino_id", $persona_destino_id);
        $this->commandAddParameter(":vin_piloto_id", $piloto);
        $this->commandAddParameter(":vin_copiloto_id", $copiloto);
        $this->commandAddParameter(":vin_periodo_id", $periodo);
        $this->commandAddParameter(":vin_fecha_emision", $fecha);
        return $this->commandGetData();
    }

    public function TraerPersonaidAgencia($rucEmpresa)
    {
        $this->commandPrepare("sp_traer_persona_id_agencia");
        $this->commandAddParameter(":vin_ruc_agencia", $rucEmpresa);
        return $this->commandGetData();
    }

    public function CambiarEstadoDespacho($despacho_id)
    {
        $this->commandPrepare("sp_cambiar_estado_manifiesto");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        return $this->commandGetData();
    }

    public function actualizarDocumentoManifiestoChofer($manifiestoId, $pilotoId, $copilotoId, $vehiculoId)
    {
        $this->commandPrepare("sp_documento_actualizarChoferXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $manifiestoId);
        $this->commandAddParameter(":vin_chofer_id", $pilotoId);
        $this->commandAddParameter(":vin_copiloto", $copilotoId);
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        return $this->commandGetData();
    }

    public function verificarManifiesto($manifiesto_id)
    {
        $this->commandPrepare("sp_verificar_manifiesto");
        $this->commandAddParameter(":vin_documento_id", $manifiesto_id);
        return $this->commandGetData();
    }

    public function getGuiasRT($despacho_id)
    {
        $this->commandPrepare("sp_traer_guia_manifiesto");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        return $this->commandGetData();
    }

    public function getComprobantesxDespacho($despacho_id, $destino_id = null, $tipo_comprobante = null)
    {
        $this->commandPrepare("sp_comprobantesXdespacho_getall");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        $this->commandAddParameter(":vin_destino_id", $destino_id);
        $this->commandAddParameter(":vin_tipo_comprobante_id", $tipo_comprobante);
        return $this->commandGetData();
    }

    public function getComprobatesxtipoxid($despacho_id, $destino_id, $tipo_comprobante, $comprobante_id)
    {
        $this->commandPrepare("sp_comprobanteXtipo_getall");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        $this->commandAddParameter(":vin_destino_id", $destino_id);
        $this->commandAddParameter(":vin_tipo_comprobante", $tipo_comprobante);
        $this->commandAddParameter(":vin_comprobante_id", $comprobante_id);
        return $this->commandGetData();
    }

    public function insertMovimientoGuia($usuario_creacion)
    {
        $this->commandPrepare("sp_insertMovimientoGuia");
        $this->commandAddParameter(":vin_usuario_creacion", $usuario_creacion);
        return $this->commandGetData();
    }

    public function insertDetalleMovimientoGuia($movi_bien_id, $usuarioId, $movimientoGuia_id)
    {
        $this->commandPrepare("sp_insert_detalle_movimiento_bien_guia");
        $this->commandAddParameter(":vin_documento_id", $movi_bien_id);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_movimiento_id", $movimientoGuia_id);
        return $this->commandGetData();
    }

    public function getMovimientoBienXcomprobante($despacho_id, $agencia_destino_id = null, $comprobante_id = null)
    {
        $this->commandPrepare("sp_get_movimientos_bien_x_comprobante");
        $this->commandAddParameter(":vin_despacho_id", $despacho_id);
        $this->commandAddParameter(":vin_agencia_destino_id", $agencia_destino_id);
        $this->commandAddParameter(":vin_comprobante_id", $comprobante_id);
        return $this->commandGetData();
    }

    public function getGuiaRT($bus_id, $fecha_salida, $conductor_id, $origin_id, $destino_id, $nro_guia)
    {
        $this->commandPrepare("sp_guia_remision_transportista_getAll");
        $this->commandAddParameter(":vin_bus", $bus_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_conductor", $conductor_id);
        $this->commandAddParameter(":vin_origen", $origin_id);
        $this->commandAddParameter(":vin_destino", $destino_id);
        $this->commandAddParameter(":vin_nro_guia", $nro_guia);
        return $this->commandGetData();
    }

    public function getDocumentoGuiaRT($guia_id)
    {
        $this->commandPrepare("sp_detalle_guia_remision_transportista_getAll");
        $this->commandAddParameter(":vin_guia_id", $guia_id);
        return $this->commandGetData();
    }

    public function anular_DocumentoGuiaRT($guia_id)
    {
        $this->commandPrepare("sp_anular_DocumentoGuiaRT");
        $this->commandAddParameter(":vin_guia_id", $guia_id);
        return $this->commandGetData();
    }

    public function obtenerDataCodigo($codigo, $tipo)
    {
        $this->commandPrepare("sp_detalleqr_obtenerporcodigo");
        $this->commandAddParameter(":vin_codigo", (int) $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function UpdatePedidoTracking($paqueteId)
    {
        $this->commandPrepare("sp_documento_pedido_tracking_update");
        $this->commandAddParameter(":vin_paquete_id ", (int) $paqueteId);
        return $this->commandGetData();
    }

    public function InsertPedidoEstadoTracking($idDocumento, $estado, $usuarioCreacion)
    {
        $this->commandPrepare("sp_documento_pedido_tracking_insert");
        $this->commandAddParameter(":vin_codigo", (int) $idDocumento);
        $this->commandAddParameter(":vin_estado", (int) $estado);
        $this->commandAddParameter(":vin_usuario", (int) $usuarioCreacion);
        return $this->commandGetData();
    }

//add liquidacion reporte
    public function getLiquidacionReporte($repartoserie,$repartonumero)
    {
        $this->commandPrepare("SP_Pre_Liquidacion_Cargo_RepartoDomicilio");
        $this->commandAddParameter(":seriemnfst",  $repartoserie);
        $this->commandAddParameter(":numeromnfst", $repartonumero);
        return $this->commandGetData();
    }
}
