<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Bien extends ModeloBase
{

    /**
     * 
     * @return Bien
     */
    static function create()
    {
        return parent::create();
    }

    public function getDataBienTipo()
    {
        $this->commandPrepare("sp_bien_tipo_getAll");
        return $this->commandGetData();
    }

    public function getBienListar()
    {
        $this->commandPrepare("sp_bien_articulo_getAll");
        return $this->commandGetData();
    }

    public function getAllBienTipo()
    {
        $this->commandPrepare("sp_bien_tipo_getCombo");
        return $this->commandGetData();
    }

    public function insertBienTipo($codigo, $descripcion, $comentario, $estado, $usuarioCreacion, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2)
    {
        $this->commandPrepare("sp_bien_tipo_insert");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_bien_tipo_padre_id", $bienTipoPadreId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id2", $codigoSunatId2);
        return $this->commandGetData();
    }

    public function importBienXML($xml, $usuarioCreacion, $empresaId)
    {
        $this->commandPrepare("sp_bien_insert_xml");
        $this->commandAddParameter(":vin_XML", $xml);
        $this->commandAddParameter(":vin_usu_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_empresa", $empresaId);
        return $this->commandGetData();
    }

    public function importBien(
        $codigoBien,
        $descripcion,
        $tipoBienCodigo,
        $largo,
        $ancho,
        $alto,
        $pesoComercial,
        $usuarioCreacion
    ) {
        $this->commandPrepare("sp_bien_import");
        $this->commandAddParameter(":vin_codigo_bien", $codigoBien);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_bien_tipo", $tipoBienCodigo);
        $this->commandAddParameter(":vin_largo", $largo);
        $this->commandAddParameter(":vin_ancho", $ancho);
        $this->commandAddParameter(":vin_alto", $alto);
        $this->commandAddParameter(":vin_peso_comercial", $pesoComercial);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function getBienTipo($id)
    {
        $this->commandPrepare("sp_bien_tipo_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateBienTipo($id, $descripcion, $codigo, $comentario, $estado, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2)
    {
        $this->commandPrepare("sp_bien_tipo_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_bien_tipo_padre_id", $bienTipoPadreId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id2", $codigoSunatId2);
        return $this->commandGetData();
    }

    public function deleteBienTipo($id)
    {
        $this->commandPrepare("sp_bien_tipo_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarTipoEstado($idEstado)
    {
        $this->commandPrepare("sp_bien_tipo_updateEstado");
        $this->commandAddParameter(":vin_id", $idEstado);
        return $this->commandGetData();
    }

    ////////////////////////////
    //bien
    //////////////////////////
    public function getDataBien($id_usu_ensesion, $empresaId)
    {
        $this->commandPrepare("sp_bien_getAll");
        $this->commandAddParameter(":vin_id_usuario", $id_usu_ensesion);
        $this->commandAddParameter("vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function insertBien(
        $descripcion,
        $codigo,
        $tipo,
        $estado,
        $usu_creacion,
        $comentario,
        $largo,
        $ancho,
        $alto,
        $peso_volumetrico
    ) {
        $this->commandPrepare("sp_bien_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_largo", $largo);
        $this->commandAddParameter(":vin_ancho", $ancho);
        $this->commandAddParameter(":vin_alto", $alto);
        $this->commandAddParameter(":vin_peso_volumetrico", $peso_volumetrico);
        //        $this->commandAddParameter(":vin_agregado_precio_venta", $agregadoPrecioVenta);
        //        $this->commandAddParameter(":vin_agregado_precio_venta_tipo", $agregadoPrecioVentaTipo);
        //        $this->commandAddParameter(":vin_codigo_fabricante", $codigoFabricante);
        //        $this->commandAddParameter(":vin_marca_id", $marcaId);
        //        $this->commandAddParameter(":vin_codigo_barra", $codigoBarras);
        //        $this->commandAddParameter(":vin_maquinaria_id", $maquinariaId);
        //        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        //        $this->commandAddParameter(":vin_plan_contable_id", $cuentaContableId);
        //        $this->commandAddParameter(":vin_costo_inical", $costoInical);
        //        $this->commandAddParameter(":vin_codigo_contable", $codigoCuenta);
        //        $this->commandAddParameter(":vin_codigo_internacional", $codigoInternacional);
        //        $this->commandAddParameter(":vin_modelo", $modelo);
        //        $this->commandAddParameter(":vin_serie_numero", $serieNumero);
        //        $this->commandAddParameter(":vin_depreciacion_metodo", $depreacionMetodo);
        //        $this->commandAddParameter(":vin_depreciacion_id", $depreciacionPorcentaje);
        //        $this->commandAddParameter(":vin_fecha_adquisicion", $fechaAdquisicion);
        //        $this->commandAddParameter(":vin_fecha_inicio_uso", $fechaInicioUso);
        //        $this->commandAddParameter(":vin_plan_contable_gasto", $cuentaContableGasto);
        //        $this->commandAddParameter(":vin_plan_contable_depreciacion", $cuentaContableDepreciacion);
        //        $this->commandAddParameter(":vin_plan_contable_venta", $cuentaContableVenta);
        return $this->commandGetData();
    }

    public function getBien($id)
    {
        $this->commandPrepare("sp_bien_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateBien(
        $id_bien,
        $descripcion,
        $codigo,
        $tipo,
        $estado,
        $comentario,
        $largo,
        $ancho,
        $alto,
        $peso_volumetrico
    ) {
        $this->commandPrepare("sp_bien_update");
        $this->commandAddParameter(":vin_id", $id_bien);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_comentario", $comentario);

        $this->commandAddParameter(":vin_largo", $largo);
        $this->commandAddParameter(":vin_ancho", $ancho);
        $this->commandAddParameter(":vin_alto", $alto);
        $this->commandAddParameter(":vin_peso_volumetrico", $peso_volumetrico);
        //        $this->commandAddParameter(":vin_agregado_precio_venta", $agregadoPrecioVenta);
        //        $this->commandAddParameter(":vin_agregado_precio_venta_tipo", $agregadoPrecioVentaTipo);
        //        $this->commandAddParameter(":vin_codigo_fabricante", $codigoFabricante);
        //        $this->commandAddParameter(":vin_marca_id", $marcaId);
        //        $this->commandAddParameter(":vin_codigo_barra", $codigoBarras);
        //        $this->commandAddParameter(":vin_maquinaria_id", $maquinariaId);
        //        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        //        $this->commandAddParameter(":vin_plan_contable_id", $cuentaContableId);
        //        $this->commandAddParameter(":vin_costo_inical", $costoInical);
        //        $this->commandAddParameter(":vin_codigo_contable", $codigoCuenta);
        //        $this->commandAddParameter(":vin_codigo_internacional", $codigoInternacional);
        //        $this->commandAddParameter(":vin_modelo", $modelo);
        //        $this->commandAddParameter(":vin_serie_numero", $serieNumero);
        //        $this->commandAddParameter(":vin_depreciacion_metodo", $depreacionMetodo);
        //        $this->commandAddParameter(":vin_depreciacion_id", $depreciacionPorcentaje);
        //        $this->commandAddParameter(":vin_fecha_adquisicion", $fechaAdquisicion);
        //        $this->commandAddParameter(":vin_fecha_inicio_uso", $fechaInicioUso);
        //        $this->commandAddParameter(":vin_plan_contable_gasto", $cuentaContableGasto);
        //        $this->commandAddParameter(":vin_plan_contable_depreciacion", $cuentaContableDepreciacion);
        //        $this->commandAddParameter(":vin_plan_contable_venta", $cuentaContableVenta);
        return $this->commandGetData();
    }

    public function deleteBien($id)
    {
        $this->commandPrepare("sp_bien_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado)
    {
        $this->commandPrepare("sp_bien_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function insertBienEmpresa($idBien, $idEmpresa, $cantidadMinina, $estado, $unidad_control_id)
    {
        $this->commandPrepare("sp_bien_empresa_insert");
        $this->commandAddParameter(":vin_id_bien", $idBien);
        $this->commandAddParameter(":vin_id_empresa", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_unidad_control_id", $unidad_control_id);
        return $this->commandGetData();
    }

    public function insertBienUnidadTipo($idBien, $id_unidad_tipo, $estado, $usu_creacion)
    {
        $this->commandPrepare("sp_bien_unidad_medida_tipo_insert");
        $this->commandAddParameter(":vin_id_bien", $idBien);
        $this->commandAddParameter(":vin_unidad_tipo", $id_unidad_tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario", $usu_creacion);
        return $this->commandGetData();
    }

    public function updateBienEmpresa($id, $idEmpresa, $estado, $unidad_control_id)
    {
        $this->commandPrepare("sp_bien_empresa_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_empresa", $idEmpresa);
        //        $this->commandAddParameter(":vin_cantidad_minima", $cantidadMinima);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_unidad_control_id", $unidad_control_id);
        return $this->commandGetData();
    }

    public function updateBienUnidadTipo($id_bien, $id_unidad_tipo, $estado, $usuarioId)
    {
        $this->commandPrepare("sp_bien_unidad_medida_tipo_update");
        $this->commandAddParameter(":vin_bien_id", $id_bien);
        $this->commandAddParameter(":vin_unidad_tipo_id", $id_unidad_tipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    /*
     * sp para importar excel
     */

    public function saveImporta($codigo, $descripcion, $cantidadMinina, $comentario, $tipo, $empresaId, $usuarioCreacion)
    {
        $this->commandPrepare("sp_bien_saveImporta");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_cantidad_minima", $cantidadMinina);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    //motivo de salida del bien 
    public function getDataBienMotivoSalida()
    {
        $this->commandPrepare("sp_bien_motivo_salida_getAll");
        return $this->commandGetData();
    }

    public function getIdBienTipo($tipo)
    {
        $this->commandPrepare("sp_bien_tipo_getIdByDesripcion");
        $this->commandAddParameter(":vin_descripcion", $tipo);
        return $this->commandGetData();
    }

    public function insertBienMotivoSalida($codigo, $descripcion, $comentario, $estado, $usuarioCreacion)
    {
        $this->commandPrepare("sp_bien_motivo_salida_insert");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function getBienMotivoSalida($id)
    {
        $this->commandPrepare("sp_bien_motivo_salida_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function updateBienMotivoSalida($id, $descripcion, $codigo, $comentario, $estado)
    {
        $this->commandPrepare("sp_bien_motivo_salida_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_comentario", $comentario);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function deleteBienMotivoSalida($id)
    {
        $this->commandPrepare("sp_bien_motivo_salida_delete");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function cambiarBienMotivoSalidaEstado($id_estado)
    {
        $this->commandPrepare("sp_bien_motivo_salida_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        return $this->commandGetData();
    }

    public function obtenerActivos($empresaId = NULL)
    {
        $this->commandPrepare("sp_bien_obtenerActivos");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerActivosXEmpresaId($empresaId)
    {
        $this->commandPrepare("sp_bien_obtenerActivosXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId)
    {
        $this->commandPrepare("sp_bien_obtenerActivosXMovimientoTipoId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function obtenerActivosXAgenciasIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId)
    {
        $this->commandPrepare("sp_bien_obtenerActivoXAgenciasXPersonaId"); 
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerActivosStock()
    {
        $this->commandPrepare("sp_bien_ObtenerActivosStock");
        return $this->commandGetData();
    }

    public function obtenerBienXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerStock($organizadoreIds = null, $bienIds = null, $bienTipoIds = null, $fechaInicio = '', $fechaFin = '', $empresaId = null)
    {
        $this->commandPrepare("sp_bien_obtenerKardexGeneral");
        $this->commandAddParameter(":vin_organizador_ids", $organizadoreIds);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerBienKardexXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_Kardex_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerServicioXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_servicio_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerBienTipoXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_tipo_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerBienTipoKardexXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_tipo_kardex_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerStockPorBien($bienId)
    {
        $this->commandPrepare("sp_bien_obtenerStockTotal");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function obtenerStockResumenPorBien($bienId)
    {
        $this->commandPrepare("sp_bien_obtenerStockResumenTotal");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    //funcion para obtener el valor de las partes del codigo del bien
    public function obtenerBienEquivalencia($codigo, $tipo)
    {
        $this->commandPrepare("sp_bien_equivalencia_obtenerValor");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function guardarBienPrecio($bienId, $precio, $precioTipo, $usuarioCreacion)
    {
        $this->commandPrepare("sp_bien_precio_guardar");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_precio", $precio);
        $this->commandAddParameter(":vin_precio_tipo", $precioTipo);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerPrecioPorBien($bienId)
    {
        $this->commandPrepare("sp_bien_precio_obtenerXbien");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function obtenerBienMovimientoEmpresa($empresaId)
    {
        $this->commandPrepare("sp_bien_obtenerXMovimientoEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerBienXMovimientosActivos()
    {
        $this->commandPrepare("sp_bien_movimientos_obtenerActivos");
        return $this->commandGetData();
    }

    public function obtenerStockXOrganizador($bienId, $organizadorId, $unidadMedida)
    {
        $this->commandPrepare("sp_bien_obtenerStockXOrganizador");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedida);
        return $this->commandGetData();
    }

    public function obtenerBienCantidadMinima($bienId, $unidadMedidaId)
    {
        $this->commandPrepare("sp_bien_obtenerCantidadMinima");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        return $this->commandGetData();
    }

    public function obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId = null)
    {
        $this->commandPrepare("sp_bien_obtenerStockActual");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_organizador_destino_id", $organizadorDestinoId);
        return $this->commandGetData();
    }

    public function obtenerBienActivoXDescripcion($bienDescripcion)
    {
        $this->commandPrepare("sp_bien_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $bienDescripcion);
        return $this->commandGetData();
    }

    public function insertarBienPersona($bienId, $proveedorId, $prioridad, $usu_creacion)
    {
        $this->commandPrepare("sp_bien_persona_insertar");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_persona_id", $proveedorId);
        $this->commandAddParameter(":vin_prioridad", $prioridad);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function obtenerBienPersonaXBienId($id)
    {
        $this->commandPrepare("sp_bien_persona_obtenerXBienId");
        $this->commandAddParameter(":vin_bien_id", $id);
        return $this->commandGetData();
    }

    public function obtenerActivosFijosXEmpresa($idEmpresa)
    {
        $this->commandPrepare("sp_bien_activos_fijos_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        return $this->commandGetData();
    }

    public function obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId)
    {
        $this->commandPrepare("sp_bien_obtenerCantidadMinimaConvertido");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        return $this->commandGetData();
    }

    public function guardarBienPrecioDetalle($bienPrecioId, $bienId, $monedaId, $precioTipoId, $unidadMedidaId, $precio, $descuento, $usu_creacion, $incluyeIGV, $checkIGV)
    {
        $this->commandPrepare("sp_bien_precio_insertar");
        $this->commandAddParameter(":vin_bien_precio_id", $bienPrecioId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_precio_tipo_id", $precioTipoId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_precio", $precio);
        $this->commandAddParameter(":vin_descuento", $descuento);
        $this->commandAddParameter(":vin_incluye_igv", $incluyeIGV);
        $this->commandAddParameter(":vin_check_igv", $checkIGV);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        return $this->commandGetData();
    }

    public function eliminarBienPrecio($bienPrecioId)
    {
        $this->commandPrepare("sp_bien_precio_eliminar");
        $this->commandAddParameter(":vin_bien_precio_id", $bienPrecioId);
        return $this->commandGetData();
    }

    public function obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador)
    {
        $this->commandPrepare("sp_bien_obtenerBienesConStockMenorACantidadMinima");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_operador", $operador);
        return $this->commandGetData();
    }

    public function obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId)
    {
        $this->commandPrepare("sp_bien_obtenerStockTotalXBienIDXUnidadMedidaId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        return $this->commandGetData();
    }

    public function obtenerMarcaXDescripcion($marca)
    {
        $this->commandPrepare("sp_marca_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $marca);
        return $this->commandGetData();
    }

    public function insertarMarca($marca, $usuarioId)
    {
        $this->commandPrepare("sp_marca_insertar");
        $this->commandAddParameter(":vin_descripcion", $marca);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerMarcas()
    {
        $this->commandPrepare("sp_marca_obtener_activas");
        return $this->commandGetData();
    }

    public function obtenerBienTipoPadresDisponibles($bienTipoId)
    {
        $this->commandPrepare("sp_bien_tipo_obtenerPadresDisponibles");
        $this->commandAddParameter(":vin_bien_tipo_id", $bienTipoId);
        return $this->commandGetData();
    }

    public function obtenerMaquinariaXDescripcion($maquinaria)
    {
        $this->commandPrepare("sp_maquinaria_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $maquinaria);
        return $this->commandGetData();
    }

    public function insertarMaquinaria($maquinaria, $usuarioId)
    {
        $this->commandPrepare("sp_maquinaria_insertar");
        $this->commandAddParameter(":vin_descripcion", $maquinaria);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerMaquinarias()
    {
        $this->commandPrepare("sp_maquinaria_obtener_activas");
        return $this->commandGetData();
    }

    public function obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal, $organizadorDestinoId = null)
    {
        $this->commandPrepare("sp_bien_obtenerStockXFechasXBienIdXOrganizadorIdXUnidadMedidaId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_unidad_medida_id", $unidadMedidaId);
        $this->commandAddParameter(":vin_fecha_inicial", $fechaInicial);
        $this->commandAddParameter(":vin_fecha_final", $fechaFinal);
        $this->commandAddParameter(":vin_organizador_destino_id", $organizadorDestinoId);
        return $this->commandGetData();
    }

    public function obtenerBienesMovimiento()
    {
        $this->commandPrepare("sp_bien_obtenerXMovimiento");
        return $this->commandGetData();
    }

    public function obtenerPrecioTipoXIndicador($indicador)
    {
        $this->commandPrepare("sp_precio_tipo_obtenerXIndicador");
        $this->commandAddParameter(":vin_indicador", $indicador);
        return $this->commandGetData();
    }

    public function obtenerActivosFijosNoInternados()
    {
        $this->commandPrepare("sp_activosfijos_obtenerNoInternados");
        return $this->commandGetData();
    }

    public function obtenerDistribucionXBienId($bienId)
    {
        $this->commandPrepare("sp_bien_centro_costo_obtenerxBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function eliminarDistribucionXBienId($bienId)
    {
        $this->commandPrepare("sp_bien_centro_costo_eliminarxBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }

    public function guardarDistribucionXBienId($bienId, $centroCostoId, $porcentaje, $usuarioId)
    {
        $this->commandPrepare("sp_bien_centro_costo_guardar");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_centro_costo_id", $centroCostoId);
        $this->commandAddParameter(":vin_porcentaje", $porcentaje);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerMetodosDepreciacion()
    {
        $this->commandPrepare("sp_sunat_tabla_detalle_obtenerMetodoDepreciacion");
        return $this->commandGetData();
    }

    public function obtenerDepreaciacionPorcentaje()
    {
        $this->commandPrepare("sp_depreciacion_listar");
        return $this->commandGetData();
    }

    public function actualizarEstadoDepreciado($bienId)
    {
        $this->commandPrepare("sp_bien_actualizar_estado");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();
    }
}
