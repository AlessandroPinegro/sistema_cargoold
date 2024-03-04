<?php

require_once __DIR__ . "/../core/ModeloBase.php";

class ACCaja extends ModeloBase {

    /**
     * 
     * @return ACCaja
     */
    static function create() {
        return parent::create();
    }

    public function obteneACCajaUltimoXEmpresaId($empresaId, $idEditar, $cajaId = NULL) {
        $this->commandPrepare("sp_ac_caja_obtenerCierreUltimoXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_id", $idEditar);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function guardarAperturaCaja($idEditar, $usuCreacion, $importeApertura, $aperturaSugerido,
            $comentario, $empresaId, $is_pintar_apertura, $cajaId) {

        $this->commandPrepare("sp_ac_caja_guardarApertura");
        $this->commandAddParameter(":vin_id_editar", $idEditar);
        $this->commandAddParameter(":vin_usu_creacion", $usuCreacion);
        $this->commandAddParameter(":vin_importe_apertura", $importeApertura);
        $this->commandAddParameter(":vin_apertura_sugerido", $aperturaSugerido);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_is_pintar_apertura", $is_pintar_apertura);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function insertar_actualizar_ACInventario($id, $idCaja, $indicador, $idBien, $cantidad,
            $stockApertura, $comentario, $usuCreacion, $is_pintar) {
        $this->commandPrepare("sp_ac_inventario_insertar_actualizar");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_caja_id", $idCaja);
        $this->commandAddParameter(":vin_indicador", $indicador);
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_cantidad", $cantidad);
        $this->commandAddParameter(":vin_stock_apertura", $stockApertura);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_usuario_creacion", $usuCreacion);
        $this->commandAddParameter(":vin_is_pintar", $is_pintar);
        return $this->commandGetData();
    }

    public function obteneAperturaCajaUltimoXEmpresaId($empresaId, $idEditar, $cajaId) {
        $this->commandPrepare("sp_ac_caja_obtenerAperturaUltimoXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_id", $idEditar);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function obtenerOpcionIdXEmpresaId($empresaId, $cadena) {
        $this->commandPrepare("sp_ac_caja_obtenerOpcionIdXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_cadena", $cadena);
        return $this->commandGetData();
    }

    public function obtenerDocumentoTipoIdXEmpresaId($empresaId, $cadena, $tipo) {
        $this->commandPrepare("sp_ac_caja_obtenerDocumentoTipoIdXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_cadena", $cadena);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function guardarCierreCaja($idEditar, $id, $usuCreacion, $importeCierre, $comentario,
            $empresaId, $visa, $traslado, $is_pintar_cierre, $is_pintar_visa,
            $cierre_sugerido, $deposito, $is_pintar_deposito, $transferencia, $is_pintar_transferencia,$egreso,$ingreso
            ,$visa_sugerido=null,$transferencia_sugerido=null,$deposito_sugerido=null,$egresoOtros,$egresoPos,$efectivo) {
        $this->commandPrepare("sp_ac_caja_guardarCierre");
        $this->commandAddParameter(":vin_id_editar", (int) $idEditar);
        $this->commandAddParameter(":vin_id", (int) $id);
        $this->commandAddParameter(":vin_usu_creacion", (int) $usuCreacion);
        $this->commandAddParameter(":vin_importe_cierre", $importeCierre);
        $this->commandAddParameter(":vin_visa", $visa);
        $this->commandAddParameter(":vin_deposito", $deposito);
        $this->commandAddParameter(":vin_transferencia", $transferencia);
        $this->commandAddParameter(":vin_traslado", $traslado);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_empresa_id", (int) $empresaId);
        $this->commandAddParameter(":vin_is_pintar_cierre", (int) $is_pintar_cierre);
        $this->commandAddParameter(":vin_is_pintar_visa", (int) $is_pintar_visa);
        $this->commandAddParameter(":vin_is_pintar_deposito", (int) $is_pintar_deposito);
        $this->commandAddParameter(":vin_is_pintar_transferencia", (int) $is_pintar_transferencia);
        $this->commandAddParameter(":vin_cierre_sugerido", $cierre_sugerido);
         $this->commandAddParameter(":vin_egreso", $egreso);
          $this->commandAddParameter(":vin_ingreso", $ingreso);
          $this->commandAddParameter(":vin_visa_sugerido", $visa_sugerido);
          $this->commandAddParameter(":vin_transferencia_sugerido", $transferencia_sugerido);
          $this->commandAddParameter(":vin_deposito_sugerido", $deposito_sugerido);
           $this->commandAddParameter(":vin_egreso_otros", $egresoOtros);
            $this->commandAddParameter(":vin_egreso_pos", $egresoPos);
             $this->commandAddParameter(":vin_efectivo", $efectivo);
        return $this->commandGetData();
    }

    public function obtenerACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar,
            $formaOrdenar, $elemntosFiltrados, $start, $periodoId = NULL, $usuarioId = NULL, $agenciaId = NULL,
            $fechaInicio = NULL, $fechaFin = NULL,$fechaAlterna = NULL) {
        $this->commandPrepare("sp_ac_caja_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", (int) $empresaId);
        $this->commandAddParameter(":vin_caja_id", (int) $cajaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_periodo_id", (int) $periodoId);
        $this->commandAddParameter(":vin_usuario_id", (int) $usuarioId);
        $this->commandAddParameter(":vin_agencia_id", (int) $agenciaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_fecha_alterna", $fechaAlterna);
        return $this->commandGetData();
    }

    public function obtenerACCajaXCriteriosExcel($empresaId, $cajaId
            , $periodoId = NULL, $usuarioId = NULL, $agenciaId = NULL,
$fechaInicio = NULL, $fechaFin = NULL,$fechaAlterna=NULL) {
        $this->commandPrepare("sp_ac_caja_obtenerXCriterios_consulta_excel");
        $this->commandAddParameter(":vin_empresa_id", (int) $empresaId);
        $this->commandAddParameter(":vin_caja_id", (int) $cajaId);
        $this->commandAddParameter(":vin_periodo_id", (int) $periodoId);
        $this->commandAddParameter(":vin_usuario_id", (int) $usuarioId);
        $this->commandAddParameter(":vin_agencia_id", (int) $agenciaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_fecha_alterna", $fechaAlterna);
        return $this->commandGetData();
    }

    public function obtenerCantidadACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar, $formaOrdenar,
            $periodoId, $usuarioId, $agenciaId, $fechaInicio, $fechaFin,$fechaAlterna=NULL) {
        $this->commandPrepare("sp_ac_caja_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_empresa_id", (int) $empresaId);
        $this->commandAddParameter(":vin_caja_id", (int) $cajaId);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_periodo_id", (int) $periodoId);
        $this->commandAddParameter(":vin_usuario_id", (int) $usuarioId);
        $this->commandAddParameter(":vin_agencia_id", (int) $agenciaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
         $this->commandAddParameter(":vin_fecha_alterna", $fechaAlterna);
        return $this->commandGetData();
    }

    public function obtenerDataAperturaCierreXId($idEditar, $indicador) {
        $this->commandPrepare("sp_ac_caja_obtenerXId");
        $this->commandAddParameter(":vin_id", (int) $idEditar);
        $this->commandAddParameter(":vin_indicador", $indicador);
        return $this->commandGetData();
    }

    public function obtenerDataNuevaAperturaCierre($empresaId, $comodin) {
        $this->commandPrepare("sp_ac_caja_dataNuevaAperturaCierre");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_apertura_ac_caja", $comodin);
        return $this->commandGetData();
    }

    public function obtenerImporteTotalDocumentoPagoVisaXEmpresaId($empresaId, $fechaInicio,
            $fechaFinal, $identificadorNegocio) {
        $this->commandPrepare("sp_documento_obtener_importe_visa_XEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFinal);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        return $this->commandGetData();
    }

    public function obtenerImporteTotalDocumentoPagoDepositoXEmpresaId($empresaId, $fechaInicio, $fechaFinal) {
        $this->commandPrepare("sp_documento_obtener_importe_deposito_XEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFinal);
        return $this->commandGetData();
    }

    public function obtenerImporteTotalDocumentoTrasladoXEmpresaId($empresaId, $fechaInicio, $fechaFinal) {
        $this->commandPrepare("sp_documento_obtener_importe_traslado_XEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFinal);
        return $this->commandGetData();
    }

    public function obtenerImporteTotalDocumentoTrasladoXacCajaId($acCajaId) {
        $this->commandPrepare("sp_documento_obtener_importe_trasladoXacCajaId");
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId);
        return $this->commandGetData();
    }

    public function obtenerSaldoCajaXEmpresaIdXFecha($empresaId, $fechaInicio) {
        $this->commandPrepare("sp_cuenta_obtenerSaldoCajaXEmpresaIdXFecha");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        return $this->commandGetData();
    }

    public function obtenerSaldoCajaXEmpresaIdXAcCajaId($acCajaId) {
        $this->commandPrepare("sp_cuenta_obtenerSaldoCajaXAccCajaId");
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId);
        return $this->commandGetData();
    }

    public function obtenerIngresoSalidaXEmpresaIdXFecha($empresaId, $fechaInicio, $fechaFinal) {
        $this->commandPrepare("sp_documento_obtener_importe_IngresoSalida_XEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFinal);
        return $this->commandGetData();
    }

    public function obtenerIngresoSalidaXAcCajaId($acCajaId) {
        $this->commandPrepare("sp_documento_obtener_importe_IngresoSalidaXAcCajaId");
        $this->commandAddParameter(":vin_acCajaId", $acCajaId);
        return $this->commandGetData();
    }

    public function obtenerIngresoSalidaBienXFecha($empresaId, $fechaInicio, $fechaFinal) {
        $this->commandPrepare("sp_ac_inventario_buscarIngresosSalidas");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFinal);
        return $this->commandGetData();
    }

    public function obtenerAccCaja($usuarioCreacion) {
        $this->commandPrepare("sp_ac_obtener_caja");
        $this->commandAddParameter(":vin_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId = NULL, $agenciaId = NULL, $cajaId = NULL) {
        $this->commandPrepare("sp_ac_caja_obtenerAperturaUltimoXUsuarioId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function obtenerCajaCorrelativoXAgenciaId($agenciaId) {
        $this->commandPrepare("sp_caja_serie_correlativo_obtenerCorrelativoXAgenciaId");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        return $this->commandGetData();
    }

    public function obteneACCajaUltimoVirtualXAgenciaId($agenciaId, $cajaId = null) {
        $this->commandPrepare("sp_ac_caja_obtenerAperturaUltimoVirtualXAgenciaId");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function obtenerImporteTotalDocumentoPagoIdentificadorNegocioXAcCajaId($acCajaId, $identificadorNegocio) {
        $this->commandPrepare("sp_documento_obtener_importeXAcCajaIdXIdentificadorNegocio");
        $this->commandAddParameter(":vin_ac_caja_id", $acCajaId);
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        return $this->commandGetData();
    }
    
    
     public function obtenerDocumentoIngresoxCajaId($acCajaId,$documento_tipo) {
        $this->commandPrepare("sp_documento_obtener_ingresoxcajaId");
        $this->commandAddParameter(":vin_acCajaId", $acCajaId);
         $this->commandAddParameter(":vin_documento_tipo", $documento_tipo);
        return $this->commandGetData();
    }

    public function obtenerDocumentoIngresoDatoxCajaId($acCajaId,$documento_tipo) {
        $this->commandPrepare("sp_documento_obtener_ingresoDatoxcajaId");
        $this->commandAddParameter(":vin_acCajaId", $acCajaId);
         $this->commandAddParameter(":vin_documento_tipo", $documento_tipo);
        return $this->commandGetData();
    }
    
      public function obtenerAccajaIpxusuario($cajaId) {
        $this->commandPrepare("sp_documento_obtener_cajaxIp");
        $this->commandAddParameter(":vin_cajaId", $cajaId);
        return $this->commandGetData();
    }
    
}
