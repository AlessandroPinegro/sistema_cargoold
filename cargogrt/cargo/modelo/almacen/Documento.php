<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Documento
 *
 * @author Christopher Heredia
 */
class Documento extends ModeloBase
{

    /**
     * 
     * @return Documento 
     */
    static function create()
    {
        return parent::create();
    }

    public function guardar(
        $documentoTipoId,
        $movimientoId,
        $personaId,
        $direccionId,
        $organizadorId,
        $adjuntoId,
        $codigo,
        $serie,
        $numero,
        $fechaEmision,
        $fechaVencimiento = null,
        $fechaTentativa,
        $descripcion,
        $comentario,
        $importeTotal,
        $importeIgv,
        $importeSubTotal,
        $estado,
        $monedaId,
        $usuarioCreacionId,
        $cuentaId = null,
        $actividadId = null,
        $retencionDetraccionId = null,
        $utilidadTotal = null,
        $utilidadPorcentajeTotal = null,
        $cambioPersonalizado = null,
        $tipoPago = null,
        $importeNoAfecto = null,
        $periodoId = null,
        $esEar = null,
        $importeFlete = null,
        $importeSeguro = null,
        $contOperacionTipoId = null,
        $importeOtros,
        $importeExoneracion = null,
        $afectoAImpuesto = null,
        $importeIcbp = null,
        $detraccionId = null,
        $afectoDetraccionRetencion = null,
        $porcentajeDetraccionRetencion = null,
        $montoDetraidoRetencion = null,
        $vin_monto_otro_gasto = null,
        $vin_monto_devolucion_gasto = null,
        $vin_monto_costo_reparto = null,
        $vin_otro_gasto_descripcion = null,
        $vin_persona_direccion_origen_id = null,
        $vin_persona_direccion_destino_id = null,
        $vin_persona_direccion_origen = null,
        $vin_persona_direccion_destino = null,
        $vin_comprobante_tipo_id = null,
        $vin_agencia_id = null,
        $vin_agencia_destino_id = null,
        $vin_modalidad_id = null,
        $vin_persona_destinatario_id = null,
        $documentoId = null,
        $contacto = null,
        $guiaRelacion = null,
        $comprobanteId = null,
        $clienteId = null,
        $acCajaId = null,
        $banderaCargoDevolucion = null,
        $vehiculoId = null,
        $choferId = null,
        $copilotoId = null,
        $fechaSalida = null,
        $ajustePrecio = null,
        $clave = null,
        $entregadoId = null,
        $bandera_registro_movil = null,
        $costo_recojo_domicilio = null,
        $pasardato = null
    ) {
        $this->commandPrepare("sp_documento_guardar");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_direccion_id", $direccionId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_adjunto_id", $adjuntoId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_fecha_vencimiento", $fechaVencimiento);
        $this->commandAddParameter(":vin_fecha_tentativa", $fechaTentativa);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_importe_total", $importeTotal);
        $this->commandAddParameter(":vin_importe_igv", $importeIgv);
        $this->commandAddParameter(":vin_importe_sub_total", $importeSubTotal);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_moneda", $monedaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        $this->commandAddParameter(":vin_cuenta_id", (int) $cuentaId);
        $this->commandAddParameter(":vin_actividad_id", $actividadId);
        $this->commandAddParameter(":vin_tipo_retencion_detraccion", $retencionDetraccionId);
        $this->commandAddParameter(":vin_utilidad_total", $utilidadTotal);
        $this->commandAddParameter(":vin_utilidad_porcentaje_total", $utilidadPorcentajeTotal);
        $this->commandAddParameter(":vin_cambio_personalizado", $cambioPersonalizado);
        $this->commandAddParameter(":vin_tipo_pago", $tipoPago);
        $this->commandAddParameter(":vin_noafecto", $importeNoAfecto);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_es_ear", $esEar);
        $this->commandAddParameter(":vin_importe_flete", $importeFlete);
        $this->commandAddParameter(":vin_importe_seguro", $importeSeguro);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        $this->commandAddParameter(":vin_importe_otro", $importeOtros);
        $this->commandAddParameter(":vin_importe_exoneracion", $importeExoneracion);
        $this->commandAddParameter(":vin_afecto_impuesto", $afectoAImpuesto);
        $this->commandAddParameter(":vin_icbp", $importeIcbp);
        $this->commandAddParameter(":vin_detraccion_id", $detraccionId);
        $this->commandAddParameter(":vin_afecto_detraccion_retencion", $afectoDetraccionRetencion);
        $this->commandAddParameter(":vin_porcentaje_afecto", $porcentajeDetraccionRetencion);
        $this->commandAddParameter(":vin_monto_detraccion_retencion", $montoDetraidoRetencion);
        $this->commandAddParameter(":vin_monto_otro_gasto", $vin_monto_otro_gasto);
        $this->commandAddParameter(":vin_monto_devolucion_gasto", $vin_monto_devolucion_gasto);
        $this->commandAddParameter(":vin_monto_costo_reparto", $vin_monto_costo_reparto);
        $this->commandAddParameter(":vin_otro_gasto_descripcion", $vin_otro_gasto_descripcion);
        $this->commandAddParameter(":vin_persona_direccion_origen_id", $vin_persona_direccion_origen_id);
        $this->commandAddParameter(":vin_persona_direccion_destino_id", $vin_persona_direccion_destino_id);
        $this->commandAddParameter(":vin_persona_direccion_origen", $vin_persona_direccion_origen);
        $this->commandAddParameter(":vin_persona_direccion_destino", $vin_persona_direccion_destino);
        $this->commandAddParameter(":vin_comprobante_tipo_id", $vin_comprobante_tipo_id);
        $this->commandAddParameter(":vin_agencia_id", $vin_agencia_id);
        $this->commandAddParameter(":vin_agencia_destino_id", $vin_agencia_destino_id);
        $this->commandAddParameter(":vin_modalidad_id", $vin_modalidad_id);
        $this->commandAddParameter(":vin_persona_destinatario_id", $vin_persona_destinatario_id);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_contacto", $contacto);
        $this->commandAddParameter(":vin_guia_relacion", $guiaRelacion);
        $this->commandAddParameter(":vin_comprobante_id", $comprobanteId);
        $this->commandAddParameter(":vin_persona_origen_id", $clienteId);
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId);
        $this->commandAddParameter(":vin_bandera_es_cargo", $banderaCargoDevolucion);

        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        $this->commandAddParameter(":vin_chofer_id", $choferId);
        $this->commandAddParameter(":vin_copiloto_id", $copilotoId);
        $this->commandAddParameter(":vin_fecha_salida", $fechaSalida);
        $this->commandAddParameter(":vin_ajuste_precio", $ajustePrecio);
        $this->commandAddParameter(":vin_clave", $clave, false);
        $this->commandAddParameter(":vin_entregado_id", $entregadoId);
        $this->commandAddParameter(":vin_bandera_registro_movil", $bandera_registro_movil);
        $this->commandAddParameter(":vin_costo_recojo_domicilio", $costo_recojo_domicilio);
        $resultado = $this->commandGetData();

        if (!ObjectUtil::isEmpty($pasardato) && !ObjectUtil::isEmpty($resultado)) {
            if ($documentoTipoId == 7 || $documentoTipoId == 6 || $documentoTipoId == 191) {
                $vIdDoc = $resultado[0]['vout_id'];
                foreach ($pasardato as $nombre) {
                    $this->insertar($vIdDoc, $nombre['serieinput'],$nombre['correlativoinput'], $nombre['tipoDocinput'], $nombre['numTipoDoc'], $nombre['numDocinput']);
                }
            }
        }

        return $resultado;
    }

    function insertar($idDocumento, $serieInput, $correlativoInput , $tipoDocInput, $numTipoDoc, $numDocInput)
    {
        $this->commandPrepare("sp_insertar_documento_guia");
        $this->commandAddParameter(":serieinput", $serieInput);
        $this->commandAddParameter(":correlativoinput", $correlativoInput);
        $this->commandAddParameter(":tipoDocinput", $tipoDocInput);
        $this->commandAddParameter(":numTipoDoc", $numTipoDoc);
        $this->commandAddParameter(":numDocinput", $numDocInput);
        $this->commandAddParameter(":documentorelacionado_id", $idDocumento);
        return $this->commandGetData();
    }

    public function guardarCajaBancos(
        $documentoTipoId,
        $movimientoId,
        $personaId,
        $direccionId,
        $organizadorId,
        $adjuntoId,
        $codigo,
        $serie,
        $numero,
        $fechaEmision,
        $fechaVencimiento = null,
        $fechaTentativa,
        $descripcion,
        $comentario,
        $importeTotal,
        $importeIgv,
        $importeSubTotal,
        $estado,
        $monedaId,
        $usuarioCreacionId,
        $cuentaId = null,
        $actividadId = null,
        $retencionDetraccionId = null,
        $utilidadTotal = null,
        $utilidadPorcentajeTotal = null,
        $cambioPersonalizado = null,
        $tipoPago = null,
        $importeNoAfecto = null,
        $periodoId = null,
        $esEar = null,
        $importeFlete = null,
        $importeSeguro = null,
        $contOperacionTipoId = null,
        $importeOtros,
        $importeExoneracion = null,
        $afectoAImpuesto = null,
        $importeIcbp = null,
        $detraccionId = null,
        $afectoDetraccionRetencion = null,
        $porcentajeDetraccionRetencion = null,
        $montoDetraidoRetencion = null,
        $vin_monto_otro_gasto = null,
        $vin_monto_devolucion_gasto = null,
        $vin_monto_costo_reparto = null,
        $vin_otro_gasto_descripcion = null,
        $vin_persona_direccion_origen_id = null,
        $vin_persona_direccion_destino_id = null,
        $vin_persona_direccion_origen = null,
        $vin_persona_direccion_destino = null,
        $vin_comprobante_tipo_id = null,
        $vin_agencia_id = null,
        $vin_agencia_destino_id = null,
        $vin_modalidad_id = null,
        $vin_persona_destinatario_id = null,
        $documentoId = null,
        $contacto = null,
        $guiaRelacion = null,
        $comprobanteId = null,
        $clienteId = null,
        $acCajaId = null,
        $banderaCargoDevolucion = null
    ) {
        $vehiculoId = null;
        $choferId = null;
        $copilotoId = null;
        $fechaSalida = null;
        $ajustePrecio = null;
        $clave = null;
        $entregadoId = null;
        $bandera_registro_movil = null;
        $costo_recojo_domicilio = null;
        $this->commandPrepare("sp_documento_guardar");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_direccion_id", $direccionId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_adjunto_id", $adjuntoId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_fecha_vencimiento", $fechaVencimiento);
        $this->commandAddParameter(":vin_fecha_tentativa", $fechaTentativa);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_importe_total", $importeTotal);
        $this->commandAddParameter(":vin_importe_igv", $importeIgv);
        $this->commandAddParameter(":vin_importe_sub_total", $importeSubTotal);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_moneda", $monedaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        $this->commandAddParameter(":vin_cuenta_id", (int) $cuentaId);
        $this->commandAddParameter(":vin_actividad_id", $actividadId);
        $this->commandAddParameter(":vin_tipo_retencion_detraccion", $retencionDetraccionId);
        $this->commandAddParameter(":vin_utilidad_total", $utilidadTotal);
        $this->commandAddParameter(":vin_utilidad_porcentaje_total", $utilidadPorcentajeTotal);
        $this->commandAddParameter(":vin_cambio_personalizado", $cambioPersonalizado);
        $this->commandAddParameter(":vin_tipo_pago", $tipoPago);
        $this->commandAddParameter(":vin_noafecto", $importeNoAfecto);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_es_ear", $esEar);
        $this->commandAddParameter(":vin_importe_flete", $importeFlete);
        $this->commandAddParameter(":vin_importe_seguro", $importeSeguro);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        $this->commandAddParameter(":vin_importe_otro", $importeOtros);
        $this->commandAddParameter(":vin_importe_exoneracion", $importeExoneracion);
        $this->commandAddParameter(":vin_afecto_impuesto", $afectoAImpuesto);
        $this->commandAddParameter(":vin_icbp", $importeIcbp);
        $this->commandAddParameter(":vin_detraccion_id", $detraccionId);
        $this->commandAddParameter(":vin_afecto_detraccion_retencion", $afectoDetraccionRetencion);
        $this->commandAddParameter(":vin_porcentaje_afecto", $porcentajeDetraccionRetencion);
        $this->commandAddParameter(":vin_monto_detraccion_retencion", $montoDetraidoRetencion);
        $this->commandAddParameter(":vin_monto_otro_gasto", $vin_monto_otro_gasto);
        $this->commandAddParameter(":vin_monto_devolucion_gasto", $vin_monto_devolucion_gasto);
        $this->commandAddParameter(":vin_monto_costo_reparto", $vin_monto_costo_reparto);
        $this->commandAddParameter(":vin_otro_gasto_descripcion", $vin_otro_gasto_descripcion);
        $this->commandAddParameter(":vin_persona_direccion_origen_id", $vin_persona_direccion_origen_id);
        $this->commandAddParameter(":vin_persona_direccion_destino_id", $vin_persona_direccion_destino_id);
        $this->commandAddParameter(":vin_persona_direccion_origen", $vin_persona_direccion_origen);
        $this->commandAddParameter(":vin_persona_direccion_destino", $vin_persona_direccion_destino);
        $this->commandAddParameter(":vin_comprobante_tipo_id", $vin_comprobante_tipo_id);
        $this->commandAddParameter(":vin_agencia_id", $vin_agencia_id);
        $this->commandAddParameter(":vin_agencia_destino_id", $vin_agencia_destino_id);
        $this->commandAddParameter(":vin_modalidad_id", $vin_modalidad_id);
        $this->commandAddParameter(":vin_persona_destinatario_id", $vin_persona_destinatario_id);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_contacto", $contacto);
        $this->commandAddParameter(":vin_guia_relacion", $guiaRelacion);
        $this->commandAddParameter(":vin_comprobante_id", $comprobanteId);
        $this->commandAddParameter(":vin_persona_origen_id", $clienteId);
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId);
        $this->commandAddParameter(":vin_bandera_es_cargo", $banderaCargoDevolucion);
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        $this->commandAddParameter(":vin_chofer_id", $choferId);
        $this->commandAddParameter(":vin_copiloto_id", $copilotoId);
        $this->commandAddParameter(":vin_fecha_salida", $fechaSalida);
        $this->commandAddParameter(":vin_ajuste_precio", $ajustePrecio);
        $this->commandAddParameter(":vin_clave", $clave);
        $this->commandAddParameter(":vin_entregado_id", $entregadoId);
        $this->commandAddParameter(":vin_bandera_registro_movil", $bandera_registro_movil);
        $this->commandAddParameter(":vin_costo_recojo_domicilio", $costo_recojo_domicilio);
        return $this->commandGetData();
    }

    function obtenerXId($documentoId, $documentoTipoId)
    {
        $this->commandPrepare("sp_documento_obtenerXid");
        $this->commandAddParameter(":vin_id", $documentoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    function anular($documentoId)
    {
        $this->commandPrepare("sp_documento_anularXId");
        $this->commandAddParameter(":vin_id", $documentoId);
        return $this->commandGetData();
    }

    function actualizarComprobanteId($documentoId, $comprobanteId)
    {
        $this->commandPrepare("sp_documento_actualizarComprobanteIdXId");
        $this->commandAddParameter(":vin_id", $documentoId);
        $this->commandAddParameter(":vin_comprobante_id", $comprobanteId);
        return $this->commandGetData();
    }

    function actualizarTramaPago($documentoId, $comprobanteId)
    {
        $this->commandPrepare("sp_documento_actualizarTramaPago");
        $this->commandAddParameter(":vin_id", $documentoId);
        $this->commandAddParameter(":vin_trama_pago", $comprobanteId);
        return $this->commandGetData();
    }

    function actulizarDocumentoCargoId($documentoId, $documentoCargoId)
    {
        $this->commandPrepare("sp_documento_actualizarDocumentoCargoIdXId");
        $this->commandAddParameter(":vin_id", $documentoId);
        $this->commandAddParameter(":vin_documento_cargo_id", $documentoCargoId);
        return $this->commandGetData();
    }

    function actualizarDocumentoGuiaTransportistaId($documentoId, $documentoCargoId)
    {
        $this->commandPrepare("sp_documento_actualizarDocumentoGuiaTransportistaIdXId");
        $this->commandAddParameter(":vin_id", $documentoId);
        $this->commandAddParameter(":vin_documento_guia_id", $documentoCargoId);
        return $this->commandGetData();
    }

    function eliminar($documentoId)
    {
        $this->commandPrepare("sp_documento_eliminarXId");
        $this->commandAddParameter(":vin_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoAPagar($documentoId, $fechaPago = null)
    {
        $this->commandPrepare("sp_documento_obtenerAPagar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_fecha_pago", $fechaPago);
        return $this->commandGetData();
    }

    function obtenerDocumentoDatos($documentoId)
    {
        $this->commandPrepare("sp_documento_obtener_datosxId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerFechaPrimerDocumento()
    {
        $this->commandPrepare("sp_documento_obtenerFechaPrimerDocumento");
        return $this->commandGetData();
    }

    function obtenerDetalleDocumento($documentoId)
    {
        $this->commandPrepare("sp_movimiento_obtenerDetalle");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerComentarioDocumento($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerComentarioXid");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerNumeroAutoXDocumentoTipo($documentoTipoId = NULL, $serie = NULL)
    {
        $this->commandPrepare("sp_documento_obtenerNumeroAutoXDocumentoTipo");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_serie", $serie);
        return $this->commandGetData();
    }

    function obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId)
    {
        $this->commandPrepare("sp_documento_obtenerNumeroAutoIncrementalXDocumentoTipo");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    function obtenerXSerieNumero($documentoTipoId, $serie, $numero)
    {
        $this->commandPrepare("sp_documento_obtenerXSerieNumero");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        return $this->commandGetData();
    }

    function obtenerDetalleDocumentoPago($documentoId)
    {
        $this->commandPrepare("sp_documento_pago_viasualizarDetalle");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDataDocumentoACopiar($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerDataDocumentoACopiar");
        $this->commandAddParameter(":vin_documento_tipo_origen_id", $documentoTipoOrigenId);
        $this->commandAddParameter(":vin_documento_tipo_destino_id", $documentoTipoDestinoId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function guardarDocumentoRelacionado($documentoId, $documentoRelacionadoId, $valorCheck, $estado, $usuarioCreacion, $relacionEar = null, $tipo = null)
    {
        $this->commandPrepare("sp_documento_relacionado_guardar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_relacionado_id", $documentoRelacionadoId);
        $this->commandAddParameter(":vin_valor_check", $valorCheck);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_relacion_ear", $relacionEar);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    function anularDocumentoRelacionXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_anularXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function insertarDocumentoRelacionado($documentoId, $documentoId2, $estado, $usuarioCreacion, $tipo)
    {
        $this->commandPrepare("sp_documento_relacionado_insertar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_relacionado_id", $documentoId2);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_tipo", $tipo);

        return $this->commandGetData();
    }

    function insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId)
    {
        $this->commandPrepare("sp_documento_documento_estado_insertar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_estado_id", $documento_estado);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    function ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId, $accion = NULL, $comentario = NULL)
    {
        $this->commandPrepare("sp_documento_documento_estadoActualizarDocumentoEstadoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_estado_id", $documentoEstadoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_accion", $accion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    function obtenerDocumentosRelacionadosXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentosRelacionados($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentosRelacionadosXDocumentoIdXTipo($documentoId, $tipo)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerXDocumentoIdXTipo");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    function obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId)
    {
        $this->commandPrepare("sp_documento_tipo_dato_copia_obtenerXdocumento");
        $this->commandAddParameter(":vin_documento_origen_id", $documentoOrigenId);
        $this->commandAddParameter(":vin_documento_destino_id", $documentoDestinoId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDireccionEmpresa($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerDireccionEmpresaXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function actualizarTipoRetencionDetraccion($documentoId, $tipoRetencionDetraccion)
    {
        $this->commandPrepare("sp_documento_actualizarTipoRetencionDetraccion");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo_retencion_detraccion", $tipoRetencionDetraccion);
        return $this->commandGetData();
    }

    function actualizarComentarioDocumento($documentoId, $comentario)
    {
        $this->commandPrepare("sp_documento_actualizarComentario");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_comentario", $comentario);
        return $this->commandGetData();
    }

    function buscarDocumentosXOpcionXSerieNumero($opcionId, $busqueda)
    {
        $this->commandPrepare("sp_documento_buscarXOpcionXSerieNumero");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function editarDocumento($documentoId, $personaId, $direccionId, $organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $monedaId, $cuentaId = null, $actividadId = null, $retencionDetraccionId = null, $importeNoAfecto = null, $periodoId = null, $tipoPago = null, $importeFlete = null, $importeSeguro = null, $contOperacionTipoId = null, $importeOtros = null, $importeExoneracion = null, $detraccionTipoId = null, $afectoAImpuesto = null, $importeIcbp = null, $cambioPersonalizado = null)
    {
        $this->commandPrepare("sp_documento_editar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_direccion_id", $direccionId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_adjunto_id", $adjuntoId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_fecha_vencimiento", $fechaVencimiento);
        $this->commandAddParameter(":vin_fecha_tentativa", $fechaTentativa);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_importe_total", $importeTotal);
        $this->commandAddParameter(":vin_importe_igv", $importeIgv);
        $this->commandAddParameter(":vin_importe_sub_total", $importeSubTotal);
        $this->commandAddParameter(":vin_moneda", $monedaId);
        $this->commandAddParameter(":vin_cuenta_id", $cuentaId);
        $this->commandAddParameter(":vin_actividad_id", $actividadId);
        $this->commandAddParameter(":vin_tipo_retencion_detraccion", $retencionDetraccionId);
        $this->commandAddParameter(":vin_importe_no_afecto", $importeNoAfecto);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_tipo_pago", $tipoPago);
        $this->commandAddParameter(":vin_importe_flete", $importeFlete);
        $this->commandAddParameter(":vin_importe_seguro", $importeSeguro);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        $this->commandAddParameter(":vin_importe_otro", $importeOtros);
        $this->commandAddParameter(":vin_importe_exoneracion", $importeExoneracion);
        $this->commandAddParameter(":vin_detraccion_tipo_id", $detraccionTipoId);
        $this->commandAddParameter(":vin_afecto_impuesto", $afectoAImpuesto);
        $this->commandAddParameter(":vin_monto_icbp", $importeIcbp);
        $this->commandAddParameter(":vin_tipo_cambio_personalizado", $cambioPersonalizado);
        return $this->commandGetData();
    }

    public function editarDocumentoModal($documentoId, $personaId, $direccionId, $organizadorId, $adjuntoId, $codigo, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaTentativa, $descripcion, $comentario, $importeTotal, $importeIgv, $importeSubTotal, $monedaId, $cuentaId = null, $actividadId = null, $retencionDetraccionId = null, $importeNoAfecto = null, $periodoId = null, $tipoPago = null, $importeFlete = null, $importeSeguro = null, $contOperacionTipoId = null, $importeOtros = null, $importeExoneracion = null, $detraccionTipoId = null, $afectoAImpuesto = null, $importeIcbp = null, $agenciaId = NULL, $accCajaId = NULL, $comprobanteTipoId = NULL)
    {
        $this->commandPrepare("sp_documento_editar_modal");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_direccion_id", $direccionId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_adjunto_id", $adjuntoId);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_fecha_vencimiento", $fechaVencimiento);
        $this->commandAddParameter(":vin_fecha_tentativa", $fechaTentativa);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_importe_total", $importeTotal);
        $this->commandAddParameter(":vin_importe_igv", $importeIgv);
        $this->commandAddParameter(":vin_importe_sub_total", $importeSubTotal);
        $this->commandAddParameter(":vin_moneda", $monedaId);
        $this->commandAddParameter(":vin_cuenta_id", $cuentaId);
        $this->commandAddParameter(":vin_actividad_id", $actividadId);
        $this->commandAddParameter(":vin_tipo_retencion_detraccion", $retencionDetraccionId);
        $this->commandAddParameter(":vin_importe_no_afecto", $importeNoAfecto);
        $this->commandAddParameter(":vin_periodo_id", $periodoId);
        $this->commandAddParameter(":vin_tipo_pago", $tipoPago);
        $this->commandAddParameter(":vin_importe_flete", $importeFlete);
        $this->commandAddParameter(":vin_importe_seguro", $importeSeguro);
        $this->commandAddParameter(":vin_cont_operacion_tipo_id", $contOperacionTipoId);
        $this->commandAddParameter(":vin_importe_otro", $importeOtros);
        $this->commandAddParameter(":vin_importe_exoneracion", $importeExoneracion);
        $this->commandAddParameter(":vin_detraccion_tipo_id", $detraccionTipoId);
        $this->commandAddParameter(":vin_afecto_impuesto", $afectoAImpuesto);
        $this->commandAddParameter(":vin_monto_icbp", $importeIcbp);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_ac_caja_id", $accCajaId);
        $this->commandAddParameter(":vin_comprobante_tipo_id", $comprobanteTipoId);
        return $this->commandGetData();
    }

    public function obtenerXDocumentoTipoXGrupoUnico($documentoTipoId, $grupoUnico, $valor)
    {
        $this->commandPrepare("sp_documento_obtenerXDocumentoTipoXGrupoUnico");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_grupo_unico", $grupoUnico);
        $this->commandAddParameter(":vin_valor", $valor);
        return $this->commandGetData();
    }

    function buscarDocumentosOperacionXOpcionXSerieNumero($opcionId, $busqueda)
    {
        $this->commandPrepare("sp_documento_operacion_buscarXOpcionXSerieNumero");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    function obtenerPersonaXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_persona_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdStringArray, $busqueda)
    {
        $this->commandPrepare("sp_documento_buscarXTipoDocumentoXSerieNumero");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarDocumentosXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        $this->commandPrepare("sp_documento_buscarXDocumentoPagar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarDocumentosXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        $this->commandPrepare("sp_documento_buscarXDocumentoPago");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarDocumentosXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        $this->commandPrepare("sp_documento_buscarXDocumentoPagado");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function obtenerRelacionesDocumento($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionadoObtenerTodosXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentoRelacionadoImpresion($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerParaImpresion");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function validarCorrelatividadNumericaConFechaEmision($documentoTipoId, $serie, $numero, $fechaEmision)
    {
        $this->commandPrepare("sp_documento_validarCorrelatividadNumericaFecha");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        return $this->commandGetData();
    }

    public function obtenerDocumentoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerDocumentoXPaqueteId($paqueteId)
    {
        $this->commandPrepare("sp_documento_obtenerXPaqueteId");
        $this->commandAddParameter(":vin_paquete_id", $paqueteId);
        return $this->commandGetData();
    }

    public function obtenerFechasPosterioresDocumentosSalidas($fechaEmision, $bienId, $organizadorId)
    {
        $this->commandPrepare("sp_documento_obtenerFechasPosterioresDocumentosSalidas");
        $this->commandAddParameter(":vin_fecha_emision", $fechaEmision);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        return $this->commandGetData();
    }

    //operaciones: modal de copia
    function buscarDocumentosOperacionXTipoDocumentoXSerieNumero($documentoTipoIdStringArray, $busqueda)
    {
        $this->commandPrepare("sp_documento_operacion_buscarXTipoDocumentoXSerieNumero");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    function obtenerNumeroSerieCorelativoPagosCobransa($documentoTipo_Tipo, $letraIdentificador)
    {
        $this->commandPrepare("sp_documento_obtenerNumeroSerieCorelativoPagosCobransa");
        $this->commandAddParameter(":vin_tipo_documento_tipo", $documentoTipo_Tipo);
        $this->commandAddParameter(":vin_caracterIdentificador", $letraIdentificador);
        return $this->commandGetData();
    }

    function obtenerNumeroSerieCorrelativoPagos($documentoTipo_Tipo, $letraIdentificador)
    {
        $this->commandPrepare("sp_pago_obtenerNumeroSerieCorelativo");
        $this->commandAddParameter(":vin_tipo_documento_tipo", $documentoTipo_Tipo);
        $this->commandAddParameter(":vin_caracterIdentificador", $letraIdentificador);
        return $this->commandGetData();
    }

    function obtenerIdTipoDocumentoXIdDocumento($idDocumento)
    {
        $this->commandPrepare("sp_documento_obtenerTipoDocumentoXidDocumento");
        $this->commandAddParameter(":vin_documento_id", $idDocumento);
        return $this->commandGetData();
    }

    function actualizarEstadoQRXDocumentoId($documentoId, $estadoQR)
    {
        $this->commandPrepare("sp_documento_actualizar_estado_qr");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado_qr", $estadoQR);
        return $this->commandGetData();
    }

    function obtenerDocumentoIdXMovimientoBienId($movimientoBienId)
    {
        $this->commandPrepare("sp_documento_obtenerDocumentoIdXMovimientoBienId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        return $this->commandGetData();
    }

    function insertarDocumentoAdjunto($documentoId, $nombreArchivo, $nombreGenerado, $usuarioCreacionId)
    {
        $this->commandPrepare("sp_documento_adjunto_insertar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_archivo", $nombreArchivo);
        $this->commandAddParameter(":vin_nombre", $nombreGenerado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        return $this->commandGetData();
    }

    function insertarActualizarDocumentoAdjunto($archivoAdjuntoId, $documentoId, $nombreArchivo, $nombreGenerado, $usuarioCreacionId, $estado = null)
    {
        $this->commandPrepare("sp_documento_adjunto_insertarActualizar");
        $this->commandAddParameter(":vin_id", $archivoAdjuntoId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_archivo", $nombreArchivo);
        $this->commandAddParameter(":vin_nombre", $nombreGenerado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    function obtenerDocumentoAdjuntoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_adjunto_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerAnticiposPendientesXPersonaId($personaId, $monedaId)
    {
        $this->commandPrepare("sp_documento_obtenerAnticiposPendientesXPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        return $this->commandGetData();
    }

    function obtenerPlanillaImportacionXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerPlanillaImportacionXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDuaXTicketEXT($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerDuaXTicketEXT");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function actualizarTipoCambioMontoNoAfectoXDocumentoId($documentoId, $tc, $montoNoAfecto)
    {
        $this->commandPrepare("sp_documento_actualizarTipoCambioMontoNoAfectoXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_cambio_personalizado", $tc);
        $this->commandAddParameter(":vin_monto_no_afecto", $montoNoAfecto);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoId, $documentoRelacionId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerXDocumentoIdXDocumentoRelacionId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_relacion_id", $documentoRelacionId);
        return $this->commandGetData();
    }

    function obtenerDataAlmacenVirtualXDocumentoId($documentoTipoId)
    {
        $this->commandPrepare("sp_movimiento_obtenerPendientesPorReposicionXDocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoDuaXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_dua_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerActivosXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoActivoXDocumentoId2($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerActivosXDocumentoId2");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoActivoXDocumentoIdXPaqueteId($documentoId, $paqueteId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerActivosXDocumentoIdXPaqueteId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_paquete_id", $paqueteId);
        return $this->commandGetData();
    }



    function obtenerDocumentoRelacionadoActivoXDocumentoId3($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerActivosXDocumentoId3");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoFEXId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerFExId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerSerieNumeroXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerSerieNumeroXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function actualizarNroSecuencialBajaXDocumentoId($documentoId, $nroSecuencialBaja, $ticket)
    {
        $this->commandPrepare("sp_documento_actualizarNroSecuenciaBajaXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_nro_secuencial_baja", $nroSecuencialBaja);
        $this->commandAddParameter(":vin_nro_efact_ticket", $ticket);
        return $this->commandGetData();
    }

    function actualizarMotivoAnulacionXDocumentoId($documentoId, $motivoAnulacion)
    {
        $this->commandPrepare("sp_documento_actualizarMotivoAnulacionXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_motivo_anulacion", $motivoAnulacion);
        return $this->commandGetData();
    }

    function obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo)
    {
        $this->commandPrepare("sp_documento_obtenerNumeroAutoXNotaCreditoTipo");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_documento_relacionado_tipo", $documentoRelacionadoTipo);
        return $this->commandGetData();
    }

    function actualizarEfactEstadoAnulacionXDocumentoId($documentoId, $estado)
    {
        $this->commandPrepare("sp_documento_actualizarEfactEstadoAnulacionXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    function obtenerIdDocumentosResumenDiario()
    {
        $this->commandPrepare("sp_obtenerIdDocumentosResumenDiario");
        return $this->commandGetData();
    }

    function actualizarEstadoEfactAnulacionXDocumentoId($documentoId, $estado, $ticket)
    {
        $this->commandPrepare("sp_documento_actualizarEstadoEfactAnulacionXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_efact_estado_anulacion", $estado);
        $this->commandAddParameter(":vin_nro_efact_ticket", $ticket);
        return $this->commandGetData();
    }

    //EDICION       
    function obtenerDataDocumentoACopiarEdicion($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerDataDocumentoACopiarEdicion");
        $this->commandAddParameter(":vin_documento_tipo_origen_id", $documentoTipoOrigenId);
        $this->commandAddParameter(":vin_documento_tipo_destino_id", $documentoTipoDestinoId);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function actualizarEstadoXDocumentoIdXEstado($documentoId, $estado)
    {
        $this->commandPrepare("sp_documento_actualizar_estadoXDocumentoIdXEstado");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function obtenerDocumentosEarXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId = null, $estadoNegocioPago = null, $serieDoc, $numeroDoc)
    {
        $this->commandPrepare("sp_documento_ear_obtenerXCriterios");
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
        $this->commandAddParameter(":vin_serie_doc", $serieDoc);
        $this->commandAddParameter(":vin_numero_doc", $numeroDoc);
        return $this->commandGetData();
    }

    public function obtenerDocumentosRevisionContabilidadXCriterios($movimientoTipoId, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId = null, $estadoNegocioPago = null, $serieDoc, $numeroDoc)
    {
        $this->commandPrepare("sp_documento_contabilidad_obtenerXCriterios");
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
        $this->commandAddParameter(":vin_serie_doc", $serieDoc);
        $this->commandAddParameter(":vin_numero_doc", $numeroDoc);
        return $this->commandGetData();
    }

    function validarImportePago($documentoIdSumaImporte, $documentoId)
    {
        $this->commandPrepare("sp_documento_validar_importe_pago");
        $this->commandAddParameter(":vin_documento_id_suma_importe", $documentoIdSumaImporte);
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function actualizarEstadoXId($documentoId, $estado)
    {
        $this->commandPrepare("sp_documento_actualizarEstadoXId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    function actualizarEstadoEfactAnulacionValido($documentoId, $estado)
    {
        $this->commandPrepare("sp_documento_actualizarEstadoEfactAnulacionValido");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    function actualizarEfactPdfNombre($documentoId, $nombrePDF)
    {
        $this->commandPrepare("sp_documento_actualizarEfactPdfNombre");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_pdf_nombre", $nombrePDF);
        return $this->commandGetData();
    }

    function actualizarEfactEstadoRegistro($documentoId, $estadoRegistro, $resultado)
    {
        $this->commandPrepare("sp_documento_actualizarEfactEstadoRegistro");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_efact_estado_registro", $estadoRegistro);
        $this->commandAddParameter(":vin_resultado", $resultado);
        return $this->commandGetData();
    }

    function actualizarEfacturaEstado($documentoId, $estdoEnvio, $mensaje)
    {
        $this->commandPrepare("sp_documento_actualizarEfactEstadoFactura");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_efact_estado_envio", $estdoEnvio);
        $this->commandAddParameter(":vin_efactura_mensaje", $mensaje);

        return $this->commandGetData();
    }

    function obtenerDocumentosPendientesDeGeneracionEfact($contadorMaximoRegistro)
    {
        $this->commandPrepare("sp_documento_obtenerDocumentosPendientesDeGeneracionEfact");
        $this->commandAddParameter(":vin_contador_maximo", $contadorMaximoRegistro);
        return $this->commandGetData();
    }

    function actualizarEfactContadorRegistro($documentoId)
    {
        $this->commandPrepare("sp_documento_actualizarEfactContadorRegistro");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerInvoiceCommercialXDUA($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerInvoiceCommercialXDUA");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDUAXInvoiceComercial($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerDUAXInvoiceCommercial");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoPagoImportacionXInvoiceComercialXTipoDocumentoSUNAT($documentoId, $documentoTipoSunat)
    {
        $this->commandPrepare("sp_documento_obtenerDocumentoPagoXInvoiceCommercialXCodigoSUNAT");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_codigo_sunat", $documentoTipoSunat);
        return $this->commandGetData();
    }

    function obtenerDocumentoDocumentoEstadoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_documento_estado_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerMontoAddValoremXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerAddValoremXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function validarPedidoStockDisponibleEntregar($documentoId)
    {
        $this->commandPrepare("sp_documento_validarPedidoStockDisponibleEntregarXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDespacho($agencia_origen_id, $agencia_destino_id, $fecha_salida, $usuarioCreacion)
    {
        $this->commandPrepare("sp_documento_obtenerDestino");
        $this->commandAddParameter(":vin_agencia_origen_id", $agencia_origen_id);
        $this->commandAddParameter(":vin_agencia_destino_id", $agencia_destino_id);
        $this->commandAddParameter(":vin_fecha_salida", $fecha_salida);
        $this->commandAddParameter(":vin_usu_creacion", $usuarioCreacion);

        return $this->commandGetData();
    }

    function obtenerDocumentoxVehiculo($vehiculoId, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerAllXVehiculoId");
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);
        return $this->commandGetData();
    }

    function obtenerDocumentoManiestoxVehiculo($vehiculoId, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerManifiestosXVehiculoIdXAgenciaDestinoId");
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        $this->commandAddParameter(":vin_agencia_destino_id", $id_agencia);
        return $this->commandGetData();
    }


    function obtenerDocumentoRecepcionPendientexVehiculoxAgencia($vehiculoId, $agenciaId)
    {
        $this->commandPrepare("sp_documento_obtenerRecepcionPendienteXVehiculoIdXAgenciaId");
        $this->commandAddParameter(":vin_vehiculo_id", $vehiculoId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        return $this->commandGetData();
    }

    function obtenerDocumentoManiestoxRecepcionId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerManifiestosXRecepcionId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoxNumeroSerie($serie, $numero, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerAllXNumeroSerie");
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_numero", $numero);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);
        return $this->commandGetData();
    }

    function InsertDocumentoDespacho($vehiculo_id, $usuarioCreacion, $id_agencia, $chofer_id, $id_agenciaD, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo, $copilotoId = NULL, $fecha = null)
    {
        $this->commandPrepare("sp_documento_despacho_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_chofer", $chofer_id);
        $this->commandAddParameter(":vin_id_agenciaD", $id_agenciaD);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_id_periodo", $id_periodo);
        $this->commandAddParameter(":vin_numero", $dato_correlativo);
        $this->commandAddParameter(":vin_id_copiloto", $copilotoId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        return $this->commandGetData();
    }

    function InsertDocumentoDespachoQR($vehiculo_id, $usuarioCreacion, $id_agencia, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo)
    {
        $this->commandPrepare("sp_documento_despacho_qr_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_id_periodo", $id_periodo);
        $this->commandAddParameter(":vin_numero", $dato_correlativo);

        return $this->commandGetData();
    }

    function InsertDocumentoManifiesto($vehiculo_id, $usuarioCreacion, $id_agencia, $fecha_salida, $fechaLlegada, $chofer_id, $id_agenciaD, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo, $copilotoId = NULL)
    {
        $this->commandPrepare("sp_documento_manifiesto_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_chofer", $chofer_id);
        $this->commandAddParameter(":vin_id_agenciaD", $id_agenciaD);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_fecha", $fecha_salida);
        $this->commandAddParameter(":vin_fecha_llegada", $fechaLlegada);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_id_periodo", $id_periodo);
        $this->commandAddParameter(":vin_numero", $dato_correlativo);
        $this->commandAddParameter(":vin_id_copiloto", $copilotoId);

        return $this->commandGetData();
    }

    function InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $id_agencia, $id_movimiento, $id_caja, $sufijo, $dato_correlativo, $id_periodo)
    {
        $this->commandPrepare("sp_documento_recepcion_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculoId);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_correlativo", $dato_correlativo);
        $this->commandAddParameter(":vin_id_periodo", $id_periodo);
        return $this->commandGetData();
    }

    function obtenerDocumentoManifiesto($vehiculo_id, $usuarioCreacion, $id_agencia, $fecha, $chofer_id, $id_agenciaD, $id_caja, $sufijo)
    {
        $this->commandPrepare("sp_documento_manifiesto_obtener");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_chofer", $chofer_id);
        $this->commandAddParameter(":vin_id_agenciaD", $id_agenciaD);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_fecha", $fecha);

        return $this->commandGetData();
    }

    function obtenerDocumentoManifiestoDespacho($vehiculo, $agenciaOrigen, $agenciaDestino, $documentoId)
    {
        $this->commandPrepare("sp_documento_manifiesto_despacho_obtener");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo);
        $this->commandAddParameter(":vin_id_agencia", $agenciaOrigen);
        $this->commandAddParameter(":vin_id_agenciaD", $agenciaDestino);
        $this->commandAddParameter(":vin_id_documento", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoDespacho($vehiculo_id, $id_agencia)
    {
        $this->commandPrepare("sp_documento_despacho_obtener");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);


        return $this->commandGetData();
    }

    function obtenerDocumentoManifiestoReparto($vehiculo_id, $id_agencia)
    {
        $this->commandPrepare("sp_documento_manifiesto_reparto_obtener");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        return $this->commandGetData();
    }

    function InsertDocumentoManifiestoReparto($vehiculo_id, $usuarioCreacion, $id_agencia, $fecha, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo)
    {
        $this->commandPrepare("sp_documento_manifiesto_reparto_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculo_id);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_id_caja", $id_caja);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_sufijo", $sufijo);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_id_periodo", $id_periodo);
        $this->commandAddParameter(":vin_correlativo", $dato_correlativo);
        return $this->commandGetData();
    }

    function InsertDocumentoConstanciaReparto(
        $vehiculoId,
        $choferId,
        $personaId,
        $direccionId,
        $personaRecepcionNombre,
        $personaRecepcionDocumento,
        $usuarioCreacion,
        $id_movimiento,
        $dato_correlativo
    ) {
        $this->commandPrepare("sp_documento_constancia_reparto_insertar");
        $this->commandAddParameter(":vin_id_vehiculo", $vehiculoId);
        $this->commandAddParameter(":vin_id_chofer", $choferId);
        $this->commandAddParameter(":vin_id_persona", $personaId);
        $this->commandAddParameter(":vin_id_direccion", $direccionId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_id_movimiento", $id_movimiento);
        $this->commandAddParameter(":vin_nombre_persona", $personaRecepcionNombre);
        $this->commandAddParameter(":vin_documento_persona", $personaRecepcionDocumento);
        $this->commandAddParameter(":vin_dato_correlativo", $dato_correlativo);
        return $this->commandGetData();
    }

    function obtenerDocumentoxReparto($codigo_agencia, $choferId)
    {
        $this->commandPrepare("sp_documento_obtener_reparto");
        $this->commandAddParameter(":vin_id_agencia", $codigo_agencia);
        $this->commandAddParameter(":vin_id_chofer", $choferId);
        return $this->commandGetData();
    }

    function insertarDocumentoAdjuntoInterna($documentoId, $nombreArchivo, $imagen, $usuarioCreacionId)
    {
        $this->commandPrepare("sp_documento_adjunto_interna_insertar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_archivo", $nombreArchivo);
        $this->commandAddParameter(":vin_imagen", $imagen);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacionId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoxDocumento($documentoId)
    {
        $this->commandPrepare("sp_documento_relacionado_obtenerDocumentoRelacionadoXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, $tipo)
    {
        $this->commandPrepare("sp_documento_relacionXDocumentoIdXTipo");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    function obtenerQrPaquetesXPedido($documentoId)
    {
        $this->commandPrepare("sp_detalle_qr_obtenerPaquetesXPedido");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionado($documentoId, $tipo)
    {
        $this->commandPrepare("sp_obtener_documento_relacionadoxId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    function eliminarDocumentoRelacionXId($id)
    {
        $this->commandPrepare("sp_documento_relacion_eliminarXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    function obtenerDocumentoRelacionadoId($documentoId, $tipo)
    {
        $this->commandPrepare("sp_obtener_documento_Id");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    function obtenerDocumentoPagoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_pago_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function anularDocumentoPago($documentoPagoId)
    {
        $this->commandPrepare("sp_documento_pago_anularPago");
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPagoId);
        return $this->commandGetData();
    }

    function obtenerPedidoPendienteAtenderMovilXUsuarioId($usuarioId)
    {
        $this->commandPrepare("sp_documento_pedido_obtenerPendientesAtenderMovilXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    function listarPedidosPersona($idPersona)
    {
        $this->commandPrepare("sp_pedido_obtenerListaPedidoXUsuarioId");
        $this->commandAddParameter(":vin_persona_id", $idPersona);
        return $this->commandGetData();
    }

    function registrarPaqueteXDocumentoId($documentoId, $usuarioId)
    {
        $this->commandPrepare("sp_paquete_guardarXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    function obtenerPersonaUsuario($documentoId)
    {
        $this->commandPrepare("sp_usuario_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    function obtenerDocumentosLiquidacionAgencia($fechaAlterna, $id_agencia, $bandera = null)
    {
        $this->commandPrepare("sp_documento_obtenerXLiquidacionAgencia");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);
        $this->commandAddParameter(":vin_bandera", $bandera);
        return $this->commandGetData();
    }

 function  obtenerDocumentosDetalleLiquidacionAgencia($fechaAlterna, $id_agencia, $bandera = null){
     $this->commandPrepare("sp_documento_obtenerdetalleXLiquidacionAgencia");
     $this->commandAddParameter(":vin_fecha", $fechaAlterna);
     $this->commandAddParameter(":vin_agencia_id", $id_agencia);
     $this->commandAddParameter(":vin_bandera", $bandera);
    return $this->commandGetData();

 }
    function obtenerModalidadXDocumento($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerXModalidad");
        $this->commandAddParameter(":vin_documento_id", $documentoId);

        return $this->commandGetData();
    }

    function obtenerPagoXDocumentoId($documentoId)
    {
        $this->commandPrepare("sp_documento_obtenerPagoXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);

        return $this->commandGetData();
    }

    function obtenerDocumentosContraEntrega($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerContraEntregaXDocumentoId");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }


    function obtenerDocumentosIngresos($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerIngresosOtros");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }

    function obtenerDocumentosEgresos($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerEgresos");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }

    function obtenerDocumentosEgresosOtros($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerEgresosOtros");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }

    function obtenerSaldoInicial($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerSaldoInicial");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }

    function obtenerCierreCaja($fechaAlterna, $id_agencia)
    {
        $this->commandPrepare("sp_documento_obtenerCierreCaja");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_agencia_id", $id_agencia);

        return $this->commandGetData();
    }


    function obtenerTipoPago($fechaAlterna, $serie, $documento_tipo)
    {
        $this->commandPrepare("sp_documento_obtenerTipoPago");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_documento_tipo", $documento_tipo);

        return $this->commandGetData();
    }

    function obtenerTipoPagoCE($fechaAlterna, $serie, $documento_tipo, $agencia)
    {
        $this->commandPrepare("sp_documento_obtenerTipoPagoCE");
        $this->commandAddParameter(":vin_fecha", $fechaAlterna);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_documento_tipo", $documento_tipo);
        $this->commandAddParameter(":vin_agencia_id", $agencia);

        return $this->commandGetData();
    }



    function obtenerDetalleDocumentoRealacionado($documentoRelacion)
    {
        $this->commandPrepare("sp_documento_ObtenerDetalleDocumentoRelacionado");
        $this->commandAddParameter(":vin_documento_id", $documentoRelacion);

        return $this->commandGetData();
    }

    function actualizarFechaSalidaDocumento($id)
    {
        $this->commandPrepare("sp_documento_ActualizarFechaSalidaXId");
        $this->commandAddParameter(":vin_id", $id);

        return $this->commandGetData();
    }

    function obtenerTipoDocumentoXTipoRelacion($documentoTipoId,$serie)
    {
        $this->commandPrepare("sp_documento_obtenerTipoDocumentoXTipoRelacion");
        $this->commandAddParameter(":vin_documento_tipo", $documentoTipoId);
        $this->commandAddParameter(":vin_serie", $serie);

        return $this->commandGetData();
    }

    
}
