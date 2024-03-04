<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Pago extends ModeloBase {

    /**
     * 
     * @return Pago
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosPagoXCriterios($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $fechaPago = null) {
        $this->commandPrepare("sp_documento_obtenerXCriterios");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        $this->commandAddParameter(":vin_fecha_pago", $fechaPago);
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosPagoXCriterios($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $fechaPago = null) {
        $this->commandPrepare("sp_documento_consulta_contador");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        //  $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        // $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_fecha_pago", $fechaPago);
        return $this->commandGetData();
    }

    function insertarPago($cliente, $fecha, $usuarioId, $estado, $codigo) {
        $this->commandPrepare("sp_pago_insertar");
        $this->commandAddParameter(":vin_persona_id", $cliente);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_codigo", $codigo);
        return $this->commandGetData();
    }

    function insertarDocumentoPago($pagoId, $documento_id, $documento_pago, $importe, $moneda, $estado, $usuarioId, $tipoCambio = null, $cuentaId = null, $actividadId = null,$acCajaId = null) {
        $this->commandPrepare("sp_documento_pago_insertar");
        $this->commandAddParameter(":vin_pago_id", $pagoId);
        $this->commandAddParameter(":vin_documento_id", $documento_id);
        $this->commandAddParameter(":vin_documento_pago", $documento_pago);
        $this->commandAddParameter(":vin_importe", $importe);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_tipo_cambio", $tipoCambio);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_cuenta_id", $cuentaId);
        $this->commandAddParameter(":vin_actividad_id", $actividadId);
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId); 
        return $this->commandGetData();
    }

    public function obtenerDocumentosPagoListarXCriterios($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_pago_obtenerXCriterios");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosPagoListarXCriterios($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_pago_consulta_contador");
        //$this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        //  $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        // $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerDetallePago($documentoId) {
        $this->commandPrepare("sp_documento_pago_detalle");
        $this->commandAddParameter("vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function validarSiTieneDocumentoRetencionDetraccion($documentoId) {
        $this->commandPrepare("sp_documento_pago_obtenerDocumentoRetencionDetraccion");
        $this->commandAddParameter("vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerActividades($tipoCobranzaPago, $empresaId) {
        $this->commandPrepare("sp_actividad_porTipoCobranzaPago");
        $this->commandAddParameter("vin_tipo", $tipoCobranzaPago);
        $this->commandAddParameter("vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPagadosXCriterios($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_pagados_obtenerXCriterios");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        return $this->commandGetData();
    }

    public function obtenerCantidadDocumentosPagadosXCriterio($empresa_id, $tipo, $tipoProvisionPago, $documentoTipoId, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_pagados_consulta_contador");
        $this->commandAddParameter("vin_empresa_id", $empresa_id);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
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
        return $this->commandGetData();
    }

    public function guardarPagoProgramacion($documentoId, $fechaPago, $importePago, $dias, $porcentaje, $glosa, $usuarioId) {
        $this->commandPrepare("sp_pago_programacion_guardar");
        $this->commandAddParameter("vin_documento_id", $documentoId);
        $this->commandAddParameter("vin_fecha_pago", $fechaPago);
        $this->commandAddParameter("vin_importe_pago", $importePago);
        $this->commandAddParameter("vin_dias", $dias);
        $this->commandAddParameter("vin_porcentaje", $porcentaje);
        $this->commandAddParameter("vin_glosa", $glosa);
        $this->commandAddParameter("vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPagoProgramacionXDocumentoId($documentoId) {
        $this->commandPrepare("sp_pago_programacion_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function eliminarRelacionDePago($documentoPagoId) {
        $this->commandPrepare("sp_documento_pago_eliminarXId");
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPagoId);
        return $this->commandGetData();
    }

    public function eliminarDocumentoDePago($documentoPago) {
        $this->commandPrepare("sp_documento_eliminarXDocumentoPagoId");
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPago);
        return $this->commandGetData();
    }

    public function anularDocumentoPago($documentoPago) {
        $this->commandPrepare("sp_documento_anularXDocumentoPagoId");
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPago);
        return $this->commandGetData();
    }

    public function insertarPpagoDetalleDocumentoPago($ppagoDetalleId, $documentoPagoId, $importePendiente, $usuarioId) {
        $this->commandPrepare("sp_ppago_detalle_documento_pago_insertar");
        $this->commandAddParameter(":vin_ppago_detalle_id", $ppagoDetalleId);
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPagoId);
        $this->commandAddParameter(":vin_importe", $importePendiente);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerDocumentoPagoXDocumentoPagoId($documentoPagoId) {
        $this->commandPrepare("sp_documento_pago_obtenerXDocumentoPagoId");
        $this->commandAddParameter(":vin_documento_pago_id", $documentoPagoId);
        return $this->commandGetData();
    }

    //EDICION DE DOCUMENTO
    public function actualizarPagoProgramacionImporteXDocumentoId($documentoId) {
        $this->commandPrepare("sp_pago_programacion_actualizar_importeXDocumentoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function obtenerCobranzasParaEmail($empresaId, $diasRestantePorVencer) {
        $this->commandPrepare("sp_obtener_cobranzas_para_email");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_numero_dias_anteriores", $diasRestantePorVencer);
        return $this->commandGetData();
    }

    public function obtenerDocumentosPagoXPagoId($pagoId) {
        $this->commandPrepare("sp_documento_pago_obtenerXPagoId");
        $this->commandAddParameter(":vin_pago_id", $pagoId);
        return $this->commandGetData();
    }

    public function actualizarContVoucherXPagoId($pagoId, $contVoucherId) {
        $this->commandPrepare("sp_documento_pago_actualizarContVoucherId");
        $this->commandAddParameter(":vin_pago_id", $pagoId);
        $this->commandAddParameter(":vin_cont_voucher_id", $contVoucherId);
        return $this->commandGetData();
    }

}
