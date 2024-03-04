<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class Reporte extends ModeloBase {

    /**
     * 
     * @return Reporte
     */
    static function create() {
        return parent::create();
    }

    public function reporteBalance($tipoDocumentoId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $bandera) {

        $this->commandPrepare("sp_reporte_balance");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limit", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $tamanio);
        return $this->commandGetData();
    }

    public function totalReporteBalance($tipoDocumentoId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin
    , $formaOrdenar, $columnaOrdenar, $bandera) {

        $this->commandPrepare("sp_reporte_balance_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function importeTotal($tipoDocumentoId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin) {

        $this->commandPrepare("sp_reporte_balanceImporteTotal");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        return $this->commandGetData();
    }

//    public function reporteKardex($organizadorId,$bienId,$BienTipoId,$emisionInicio,$emisionFin,
//                                                $elementosFiltrados,$formaOrdenar,$columnaOrdenar,$tamanio) {

    public function reporteKardex($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1) {

        $this->commandPrepare("sp_bien_obtenerKardex");//solo unidad base
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

    public function reporteBienesMayorRotacion($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1) {

        $this->commandPrepare("sp_bien_obtenerBienesMayorRotacion");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function reporteComprometidosDia($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1) {

        $this->commandPrepare("sp_reporte_obtenerComprometidosDia");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function reporteRankingServicios($emisionInicio, $emisionFin, $empresaId = -1) {

        $this->commandPrepare("sp_reporte_obtenerRankingServicios");
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function reporteDetalleEntradaSalidaAlmacen($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1, $indicador) {

        $this->commandPrepare("sp_bien_reporteDetalleEntradaSalidaAlmacen");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_indicador", $indicador);
        return $this->commandGetData();
    }

    public function reporteEntradaSalidaAlmacen($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1, $documentoTipoId) {

        $this->commandPrepare("sp_reporte_obtenerEntradaSalidaAlmacen");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDataEntradaSalidaAlmacenVirtualXCriterios($organizadorOrigenIds,$bienIds, $emisionInicio, $emisionFin){
        $this->commandPrepare("sp_reporte_obtenerEntradaSalidaAlmacenVirtual");
        $this->commandAddParameter(":vin_organizador_origen_ids", $organizadorOrigenIds);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerDataEntradaSalidaAlmacenVirtualDetalle( $documentoId,$bienId){
        $this->commandPrepare("sp_reporte_obtenerEntradaSalidaAlmacenVirtual_detalle");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();        
    }

    public function reporteDispersionBienes($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1, $documentoTipoId, $tipoFrecuenciaId) {

        $this->commandPrepare("sp_reporte_obtenerDispersionBienes");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_tipo_frecuencia_id", $tipoFrecuenciaId);
        return $this->commandGetData();
    }

    public function reporteEntradaSalida($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1, $documentoTipoId) {

        $this->commandPrepare("sp_reporte_obtenerEntradaSalida");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        return $this->commandGetData();
    }

    public function reporteRankingColaboradores($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $empresaId = -1, $personaTipoId) {

        $this->commandPrepare("sp_reporte_obtenerRankingColaboradores");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_tipo_ids", $personaTipoId);
        return $this->commandGetData();
    }

    public function reporteServiciosAtendidos($organizadorId, $bienId, $documentoTipoId, $emisionInicio, $emisionFin, $empresaId = -1) {

        $this->commandPrepare("sp_bien_obtenerServiciosAtendidos");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function totalReporteKardex($organizadorId, $bienId, $BienTipoId, $emisionInicio, $emisionFin, $formaOrdenar, $columnaOrdenar) {

        $this->commandPrepare("sp_reporte_kardex_contador");
        $this->commandAddParameter(":vin_organizador_id", $organizadorId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_id", $BienTipoId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    function obtenerDetalleKardex($idBien, $idOrganizador, $fechaInicio, $fechaFin) {
        $this->commandPrepare("sp_reporte_detalleKardex");
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_organizador_id", $idOrganizador);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    function obtenerDocumentoServicios($idBien, $fechaInicio, $fechaFin) {
        $this->commandPrepare("sp_reporte_obtenerDocumentoServicios");
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    function obtenerDetalleBienesMayorRotacion($idBien, $idOrganizador, $idUnidadMedida, $fechaInicio, $fechaFin) {
        $this->commandPrepare("sp_reporte_obtenerDetalleBienesMayorRotacion");
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_organizador_id", $idOrganizador);
        $this->commandAddParameter(":vin_unidad_medida_id", $idUnidadMedida);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    function obtenerDetalleComprometidosDia($idBien, $fechaInicio, $fechaFin,$empresaId) {
        $this->commandPrepare("sp_reporte_obtenerDetalleComprometidosDia");
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    function obtenerDetalleRankingServicios($idBien, $fechaInicio, $fechaFin) {
        $this->commandPrepare("sp_reporte_obtenerDetalleRankingServicios");
        $this->commandAddParameter(":vin_bien_id", $idBien);
        $this->commandAddParameter(":vin_fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":vin_fecha_fin", $fechaFin);
        return $this->commandGetData();
    }

    //reporte de deuda
    public function obtenerReporteDeudaXCriterios($mostrarPagados,$mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_obtenerXCriterios");
        $this->commandAddParameter(":vin_mostrar", $mostrarPagados);
        $this->commandAddParameter(":vin_mostrar_liberado", $mostrarLib);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_Hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadReporteDeudaXCriterios($mostrarPagados, $mostrarLib,$tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_consulta_contador");
        $this->commandAddParameter(":vin_mostrar", $mostrarPagados);
        $this->commandAddParameter(":vin_mostrar_liberado", $mostrarLib);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_Hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    //Reporte deuda general 

    public function obtenerReporteDeudaGeneralXCriterios($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_general_obtenerXCriterios");
        $this->commandAddParameter(":vin_mostrar", $mostrar);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadReporteDeudaGeneralXCriterios($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_general_consulta_contador");
        $this->commandAddParameter(":vin_mostrar", $mostrar);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function reporteBalanceExcel($tipoDocumentoId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin) {

        $this->commandPrepare("sp_reporte_balance_exportarExcel");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        return $this->commandGetData();
    }

    public function ReporteXOrganizador($organizadorId, $bienId, $empresaId) {

        $this->commandPrepare("sp_reporte_XOrganizador");
        $this->commandAddParameter(":vin_organizador_ids", $organizadorId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    //Reporte balance consolidado

    public function obtenerReporteReporteBalanceConsolidadoXCriterios($empresa, $tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_balanceConsolidado_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadReporteBalanceConsolidadoXCriterios($empresa, $tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_documento_balanceConsolidado_consulta_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    //Reporte de movimiento persona
    public function obtenerMovimientoPersonaXCriterios($empresa, $tipoDocumentoId, $personaId, $bienId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_movimiento_persona_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerCantidadesTotalesBalanceConsolidado($empresa, $tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin) {
        $this->commandPrepare("sp_documento_balanceConsolidado_CantidadesTotalesXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $vencimientoInicio);
        $this->commandAddParameter(":vin_fecha_vencimiento_hasta", $vencimientoFin);
        return $this->commandGetData();
    }

    //Reporte movimiento persona
    public function obtenerCantidadMovimientoPersonaXCriterio($empresa, $tipoDocumentoId, $personaId, $bienId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_movimiento_persona_consulta_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function obtenerCantidadesTotalesMovimientoPersona($empresa, $tipoDocumentoId, $personaId, $bienId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar) {
        $this->commandPrepare("sp_movimiento_persona_obtenerCantidadesTotalesXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_bien_id", $bienId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }

    public function obtenerBienesCantMinimaAlcanzada($bienId, $empresaId) {
        $this->commandPrepare("sp_bien_obtenerCantMinAlcanzada");
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    //reporte ventas por vendedor
    
    public function obtenerCantidadesTotalesVentasPorVendedor($empresaId,$tipoDocumentoId,$personaId,$emisionInicio,$emisionFin,$bienTipoIds )
    {
        $this->commandPrepare("sp_reporteVentas_porVendedor_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        return $this->commandGetData();
    }
    
    public function obtenerReporteVentasPorVendedorXCriterios($empresaId,$tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,$bienTipoIds)
    {
        $this->commandPrepare("sp_reporteVentas_porVendedor_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteVentasPorVendedorXCriterios($empresaId,$tipoDocumentoId, $personaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar,$bienTipoIds)
    {
        $this->commandPrepare("sp_reporteVentas_porVendedor_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        return $this->commandGetData();
    }
    
    //reporte ventas por tienda
    
    public function obtenerCantidadesTotalesVentasPorTienda($tipoDocumentoId,$empresaId,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporteVentas_porTienda_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteVentasPorTiendaXCriterios($tipoDocumentoId, $empresaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporteVentas_porTienda_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteVentasPorTiendaXCriterios($tipoDocumentoId, $empresaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporteVentas_porTienda_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
        
    //Reporte ventas comision por vendedor
    public function reporteVentasComisionVendedor($empresaId,$porcentaje, $vendedorId, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporteVentas_comisionVendedor_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_porcentaje", $porcentaje);
        $this->commandAddParameter(":vin_vendedor_ids", $vendedorId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }    
    
    //Reporte ventas por tiempo
    public function reporteVentasPorTiempo($tiempo, $tiendaId, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporteVentas_porTiempo_obtenerXCriterios");
        $this->commandAddParameter(":vin_tiempo", $tiempo);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadesTotalesVentasPorTiempo($tiempo, $tiendaId, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporteVentas_porTiempo_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tiempo", $tiempo);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }
    
    //Reporte ventas productos mas vendidos    
    public function reporteVentasProductosMasVendidos($limite, $empresaId, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporteVentas_productosMasVendidos_obtenerXCriterios");
        $this->commandAddParameter(":vin_limite", $limite);
        $this->commandAddParameter(":vin_empresa_ids", $empresaId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }    
    
    //Reporte ventas por producto
    public function reporteVentasPorProducto($bienIds,$bienTipoIds, $tiendaIds, $emisionInicio, $emisionFin,$tipo) {
        $this->commandPrepare("sp_reporte_porProducto_obtenerXCriterios");
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_documento_tipo_tipo", $tipo);
        return $this->commandGetData();
    }
    
    //Reporte ventas por stock valorizado
    public function reporteVentasStockValorizado($bienIds,$bienTipoIds, $organizadorIds) {
        $this->commandPrepare("sp_reporte_stockValorizado_obtenerXCriterios");
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_organizador_ids", $organizadorIds);
        return $this->commandGetData();
    }
    
    //reporte compras
    
    public function obtenerCantidadesTotalesReporteCompras($tipoDocumentoId,$personaId,$origen,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporte_compras_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteReporteComprasXCriterios($tipoDocumentoId, $personaId,$origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporte_compras_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    public function obtenerReporteReporteComprasXCriteriosProducto($tipoDocumentoId, $personaId,$origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporte_compras_obtenerXCriteriosProducto");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }

    public function obtenerReporteReporteAtenciones($tipoDocumentoId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporte_atencion_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", -1); // sin persona
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteReporteComprasXCriterios($tipoDocumentoId, $personaId, $origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_compras_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }

    public function obtenerCantidadReporteReporteAtencionesXCriterios($tipoDocumentoId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_atencion_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_persona_id", -1);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    //Reporte activos fijos
    public function reporteVentasActivosFijos($bienIds,$motivoIds, $tiendaIds, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporte_activosFijos_obtenerXCriterios");
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_documento_tipo_dato_lista_ids", $motivoIds);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }

    public function reporteReporteEstadisticoVentas($empresaId, $bienId, $bienTipoId, $emisionInicio, $emisionFin, $documentoTipoId, $tipoFrecuenciaId,$monedaId) {

        $this->commandPrepare("sp_reporteVentas_estadisticoVentas_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_ids", $empresaId);
        $this->commandAddParameter(":vin_bien_ids", $bienId);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_tipo_frecuencia_id", $tipoFrecuenciaId);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        return $this->commandGetData();
    }
    
    //reporte ventas por cliente
    
    public function obtenerCantidadesTotalesVentasPorCliente($empresaId,$tipoDocumentoId,$personaId,$emisionInicio,$emisionFin,$bienTipoIds )
    {
        $this->commandPrepare("sp_reporteVentas_porCliente_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        return $this->commandGetData();
    }
    
    public function obtenerReporteVentasPorClienteXCriterios($empresaId ,$tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,$bienTipoId=null)
    {
        $this->commandPrepare("sp_reporteVentas_porCliente_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteVentasPorClienteXCriterios($empresaId ,$tipoDocumentoId, $personaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar,$bienTipoIds)
    {                                               
        $this->commandPrepare("sp_reporteVentas_porCliente_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        return $this->commandGetData();
    }
    
    //Reporte ventas reporte de utilidades
    public function reporteVentasReporteUtilidades($tiempo, $tiendaId, $emisionInicio, $emisionFin) {
        $this->commandPrepare("sp_reporteVentas_reporteUtilidades_obtenerXCriterios");
        $this->commandAddParameter(":vin_tiempo", $tiempo);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }
    
    //Reporte tributario
    public function reporteTributario($tipoTributo, $emisionInicio, $emisionFin,$empresaId) {
        $this->commandPrepare("sp_reporte_tributario_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_tributo", $tipoTributo);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadesTotalesReporteTributario($tipoTributo, $emisionInicio, $emisionFin,$empresaId) {
        $this->commandPrepare("sp_reporte_tributario_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_tributo", $tipoTributo);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    //reporte notas credito y debito
    
    public function obtenerCantidadesTotalesNotasCreditoDebito($tipoDocumentoId,$empresaId,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporte_notasCreditoDebito_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteNotasCreditoDebitoXCriterios($tipoDocumentoId, $empresaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporte_notasCreditoDebito_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteNotasCreditoDebitoXCriterios($tipoDocumentoId, $empresaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_notasCreditoDebito_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    //reporte de deuda totales
    public function obtenerCantidadesTotalesCuentasPorCobrar($mostrarPagados, $mostrarLib,$tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta) {
        $this->commandPrepare("sp_deuda_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_mostrar", $mostrarPagados);
        $this->commandAddParameter(":vin_mostrar_liberado", $mostrarLib);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_Hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_columna_ordenar", null);
        $this->commandAddParameter(":vin_forma_ordenar", null);
        $this->commandAddParameter(":vin_limite", null);
        $this->commandAddParameter(":vin_tamanio", null);
        return $this->commandGetData();
    }

    public function obtenerCantidadesTotalesCuentasPorCobrarGeneral($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_general_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_mostrar", $mostrar);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha", $fecha);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    //reporte ventas IGV ventas
    
    public function obtenerCantidadesTotalesVentasIgvVentas($tipoDocumentoId,$empresaId,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporteVentas_IgvVentas_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteVentasIgvVentasXCriterios($tipoDocumentoId, $empresaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporteVentas_IgvVentas_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteVentasIgvVentasXCriterios($tipoDocumentoId, $empresaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporteVentas_IgvVentas_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    //Reporte Caja Bancos por actividad
    public function reporteCajaBancosPorActividad($actividadId,$actividadTipoId, $tiendaIds, $mes, $anio) {
        $this->commandPrepare("sp_reporteCajaBancos_porActividad_obtenerXCriterios");
        $this->commandAddParameter(":vin_actividad_ids", $actividadId);
        $this->commandAddParameter(":vin_actividad_tipo_ids", $actividadTipoId);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaIds);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_anio", $anio);
        return $this->commandGetData();
    }
        
    //Reporte Caja Bancos por cuenta
    public function reporteVentasPorCuenta($documentoTipoId, $mes, $anio,$cuentaIds,$empresaId) {
        $this->commandPrepare("sp_reporteCajaBancos_porCuenta_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }    
    
    public function reportePorCuentaTotales($documentoTipoId, $mes, $anio,$cuentaIds,$empresaId) {
        $this->commandPrepare("sp_reporteCajaBancos_porCuenta_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_mes", $mes);
        $this->commandAddParameter(":vin_anio", $anio);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }    
        
    //Reporte Caja cierre caja
    public function reporteCierreCaja($actividadId, $actividadTipoId, $fechaEmision, $cuentaIds, $empresaId, $cajaId) {
        $this->commandPrepare("sp_reporteCajaBancos_cierreCaja_obtenerXCriterios");
        $this->commandAddParameter(":vin_actividad_ids", $actividadId);
        $this->commandAddParameter(":vin_actividad_tipo_ids", $actividadTipoId);
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }

    public function reporteCierreCajaTotales($actividadId,$actividadTipoId, $fechaEmision ,$cuentaIds,$empresaId,$cajaId) {
        $this->commandPrepare("sp_reporteCajaBancos_cierreCaja_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_actividad_ids", $actividadId);
        $this->commandAddParameter(":vin_actividad_tipo_ids", $actividadTipoId);
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_caja_id", $cajaId);
        return $this->commandGetData();
    }
    
    //reporte orden compra
    
    public function obtenerCantidadesTotalesReporteOrdenCompra($empresaId,$personaId,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporte_orden_compra_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteReporteOrdenCompraXCriterios( $empresaId,$personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporte_orden_compra_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteReporteOrdenCompraXCriterios($empresaId, $personaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_orden_compra_consulta_contador");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    //Reporte retencion detracion   
    public function reporteVentasRetencionDetraccion($empresaId,$tipoRD, $clienteId, $emisionInicio, $emisionFin){
        $this->commandPrepare("sp_reporte_retencionDetraccion_obtenerXCriterios");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo_rd", $tipoRD);
        $this->commandAddParameter(":vin_cliente_ids", $clienteId);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }    
    
    //Reporte Caja Bancos por actividad por fecha
    public function reporteCajaBancosPorActividadPorFecha($actividadId,$actividadTipoId, $tiendaIds, $fechaEmision) {
        $this->commandPrepare("sp_reporteCajaBancos_porActividadPorFecha_obtenerXCriterios");
        $this->commandAddParameter(":vin_actividad_ids", $actividadId);
        $this->commandAddParameter(":vin_actividad_tipo_ids", $actividadTipoId);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaIds);
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        return $this->commandGetData();
    }
        
    //Reporte Caja Bancos por cuenta fecha
    public function reporteVentasPorCuentaFecha($documentoTipoId, $fechaEmision,$cuentaIds,$empresaId) {
        $this->commandPrepare("sp_reporteCajaBancos_porCuentaFecha_obtenerXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }    
    
    public function reportePorCuentaFechaTotales($documentoTipoId, $fechaEmision,$cuentaIds,$empresaId) {
        $this->commandPrepare("sp_reporteCajaBancos_porCuentaFecha_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_documento_tipo_ids", $documentoTipoId);
        $this->commandAddParameter(":vin_fecha", $fechaEmision);
        $this->commandAddParameter(":vin_cuenta_ids", $cuentaIds);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }
    
    public function reporteKardexReporte($empresaId,$bienIds, $bienTipoIds, $emisionInicio, $emisionFin) {

        $this->commandPrepare("sp_reporte_kardexTotal");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }
    
    public function reporteKardexValorizado($empresaId,$bienIds, $bienTipoIds, $emisionInicio, $emisionFin) {

        $this->commandPrepare("sp_reporte_kardexValorizado");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        return $this->commandGetData();
    }

    public function obtenerConfiguracionesInicialesListadoAtencion()
    {
        $this->commandPrepare("sp_reporte_getListadoAtencion");

        return $this->commandGetData();
    }
    
    //reporte OPERACIONES
    
    public function obtenerCantidadesTotalesReporteOperaciones($empresaId,$tipoDocumentoId,$personaId,$emisionInicio,$emisionFin )
    {
        $this->commandPrepare("sp_reporteOperaciones_obtenerTotalesXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        return $this->commandGetData();
    }
    
    public function obtenerReporteReporteOperacionesXCriterios($empresaId ,$tipoDocumentoId, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {
        $this->commandPrepare("sp_reporteOperaciones_obtenerXCriterios");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteReporteOperacionesXCriterios($empresaId ,$tipoDocumentoId, $personaId,  $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporteOperaciones_consulta_contador");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    
    //Reporte ventas producto por periodo
    public function reporteProductoPorPeriodo($bienIds,$bienTipoIds, $tiendaIds, $emisionInicio, $emisionFin,$tipo,$periodo) {
        $this->commandPrepare("sp_reporte_productoPorPeriodo_obtenerXCriterios");
        $this->commandAddParameter(":vin_bien_ids", $bienIds);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_empresa_ids", $tiendaIds);
        $this->commandAddParameter(":vin_fecha_inicio", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_fin", $emisionFin);
        $this->commandAddParameter(":vin_documento_tipo_tipo", $tipo);
        $this->commandAddParameter(":vin_periodo", $periodo);
        return $this->commandGetData();
    }
    
    public function reportePorClienteObtenerGraficoClientes($empresaId,$tipoDocumentoId,$personaId,$emisionInicio,$emisionFin,$bienTipoIds, $monedaId, $importeMinimo)
    {
        $this->commandPrepare("sp_reporteVentas_porCliente_obtenerGraficoClientes");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_importe_minimo", $importeMinimo);
        return $this->commandGetData();
    }
    
    public function reportePorClienteObtenerGraficoProductos($empresaId,$tipoDocumentoId,$personaId,$emisionInicio,$emisionFin,$bienTipoIds, $monedaId, $importeMinimo)
    {
        $this->commandPrepare("sp_reporteVentas_porCliente_obtenerGraficoGrupoProductos");
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_bien_tipo_ids", $bienTipoIds);
        $this->commandAddParameter(":vin_moneda_id", $monedaId);
        $this->commandAddParameter(":vin_importe_minimo", $importeMinimo);
        return $this->commandGetData();
    }
    
    public function obtenerDataCotizaciones(){
        $this->commandPrepare("sp_reporte_obtenerProductosCotizaciones");
        return $this->commandGetData();        
    }
    
    public function obtenerCotizacionesDetalle($bienId){
        $this->commandPrepare("sp_reporte_obtenerCotizacionesDetalleXBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();        
    }
    
    public function obtenerDataCotizacionesExt(){
        $this->commandPrepare("sp_reporte_obtenerProductosCotizacionesExt");
        return $this->commandGetData();        
    }
    
    public function obtenerCotizacionesDetalleExt($bienId){
        $this->commandPrepare("sp_reporte_obtenerCotizacionesExtDetalleXBienId");
        $this->commandAddParameter(":vin_bien_id", $bienId);
        return $this->commandGetData();        
    }
    
    //TRANSFERENCIA TRANSFORMACION NO ATENDIDAS        
    public function obtenerReporteTransferenciaTransformacionNoAtendidaXCriterios($motivoTrasladoIds, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {               
        $this->commandPrepare("sp_reporte_transferencia_noAtendida_obtenerXCriterios");
        $this->commandAddParameter(":vin_motivo_traslado_ids", $motivoTrasladoIds);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteTransferenciaTransformacionNoAtendidaXCriterios($motivoTrasladoIds, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_transferencia_noAtendida_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_motivo_traslado_ids", $motivoTrasladoIds);
        $this->commandAddParameter(":vin_fecha_emision_desde", $emisionInicio);
        $this->commandAddParameter(":vin_fecha_emision_hasta", $emisionFin);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    //fin TRANSFERENCIA TRANSFORMACION NO ATENDIDAS
    
    // TRANSFERENCIA DE PRODUCTOS DIFERENTES       
    public function obtenerReporteTransferenciaDiferenteXCriterios($columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start)
    {               
        $this->commandPrepare("sp_reporte_transferencia_diferente_obtenerXCriterios");
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteTransferenciaDiferenteXCriterios($columnaOrdenar, $formaOrdenar)
    {
        $this->commandPrepare("sp_reporte_transferencia_diferente_obtenerXCriterios_contador");
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
    //FIN TRANSFERENCIA DE PRODUCTOS DIFERENTES
    
    public function exportarReporteVentas($proveedor) {
        $this->commandPrepare("sp_obtener_data_reporte_ventas_Excel");
        $this->commandAddParameter(":vin_proveedor", $proveedor);
        return $this->commandGetData();
    }
    public function obtenerDataVencidasGraficoReporteVentas() {
        $this->commandPrepare("sp_obtener_data_grafico_vencidas_reporte_ventas_Excel");
        return $this->commandGetData();
    }
    public function obtenerDataVigentesGraficoReporteVentas() {
        $this->commandPrepare("sp_obtener_data_grafico_vigentes_reporte_ventas_Excel");
        return $this->commandGetData();
    }
    
    //REPORTE CXP BHDT
    public function obtenerReporteDeudaBHDTXCriterios($mostrarPagados,$mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_BHDT_obtenerXCriterios");
        $this->commandAddParameter(":vin_mostrar", $mostrarPagados);
        $this->commandAddParameter(":vin_mostrar_liberado", $mostrarLib);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_Hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
    }
    
    public function obtenerCantidadReporteDeudaBHDTXCriterios($mostrarPagados, $mostrarLib,$tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start) {
        $this->commandPrepare("sp_deuda_consulta_BHDT_contador");
        $this->commandAddParameter(":vin_mostrar", $mostrarPagados);
        $this->commandAddParameter(":vin_mostrar_liberado", $mostrarLib);
        $this->commandAddParameter(":vin_tipo1", $tipo1);
        $this->commandAddParameter(":vin_tipo2", $tipo2);
        $this->commandAddParameter(":vin_empresa_id", $empresa);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_fecha_vencimiento_desde", $fechaVencimientoDesde);
        $this->commandAddParameter(":vin_fecha_vencimiento_Hasta", $fechaVencimientoHasta);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        return $this->commandGetData();
    }
}
