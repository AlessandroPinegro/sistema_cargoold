<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";
 
/**
 * Tipo de documento
 *
 * @author CHL
 */
class DocumentoTipo extends ModeloBase {
    /**
     * 
     * @return DocumentoTipo
     */
    static function create() {
        return parent::create();
    }
    
//    public function getDocumentoTipo($documentoTipoId) {
//        $this->commandPrepare("sp_documento_tipo_obtener");
//        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
//        return $this->commandGetData();
//    }
    
    
    
    public function obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId) {
        $this->commandPrepare("sp_documento_tipo_obtenerXMovimientoTipo");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoDatoXMovimientoTipo($movimientoTipoId) {
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXMovimientoTipo");
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }
    
     public function obtenerDocumentoTipoXEmpresa($empresaId) {
        $this->commandPrepare("sp_documento_tipo_obtenerXEmpresa");
        $this->commandAddParameter(":vin_empresa_id",$empresaId);
        return $this->commandGetData();
    }
    
    ////para pagos 
     public function obtenerDocumentoTipoXTipo($empresa_id,$tipo1,$tipoPagoProvision) {
        $this->commandPrepare("sp_documento_tipo_obtenerXTipo");
        $this->commandAddParameter(":vin_empresa_id",$empresa_id);
        $this->commandAddParameter(":vin_tipo",$tipo1);
        $this->commandAddParameter(":vin_tipo_provision",$tipoPagoProvision);
        return $this->commandGetData();
    }
    
     public function obtenerDocumentoTipoSinDocumentosDeMovimientoXTipo($empresa_id,$tipo1,$tipoPagoProvision) {
        $this->commandPrepare("sp_documento_tipo_obtenerSinDocumentosDeMovimientoXTipo");
        $this->commandAddParameter(":vin_empresa_id",$empresa_id);
        $this->commandAddParameter(":vin_tipo",$tipo1);
        $this->commandAddParameter(":vin_tipo_provision",$tipoPagoProvision);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoDatoXTipo($tipo1,$tipoProvision) {
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXTipo");
        $this->commandAddParameter(":vin_tipo",$tipo1);
        $this->commandAddParameter(":vin_tipo_provision",$tipoProvision);
        return $this->commandGetData();
    }
    public function obtenerDocumentoTipoXEmpresaXTipo($empresaId,$idTipos) {
        $this->commandPrepare("sp_documento_tipo_obtenerXEmpresaXTipo");
        $this->commandAddParameter(":vin_empresa_id",$empresaId);
        $this->commandAddParameter(":vin_tipos_id",$idTipos);
        return $this->commandGetData();
    }
    public function obtenerDocumentoTipoXTipos($idTipos) {
        $this->commandPrepare("sp_documento_tipo_obtenerXTipos");
        $this->commandAddParameter(":vin_tipos_id",$idTipos);
        return $this->commandGetData();
    }
    public function obtenerDocumentoTipoIdXDocumentoTipoDescripcionOpcionId($documentoTipo,$opcionId) {
        $this->commandPrepare("sp_documento_tipo_obtenerIdXDocumentoTipoDescripcionOpcionId");
        $this->commandAddParameter(":vin_documento_tipo_descripcion",$documentoTipo);
        $this->commandAddParameter(":vin_opcion_id",$opcionId);
        return $this->commandGetData();
    }
    public function obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId,$empresaId,$idTipos) {
        $this->commandPrepare("sp_documento_tipo_obtenerXEmpresaXTipoXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id",$movimientoTipoId);
        $this->commandAddParameter(":vin_empresa_id",$empresaId);
        $this->commandAddParameter(":vin_tipos_id",$idTipos);
        
        $documentoTipo=$this->commandGetData();
        
        return $this->commandGetData();
    }
    
    public function obtenerXOpcion($opcionId) {  
        $this->commandPrepare("sp_documento_tipo_obtenerXOpcion");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoNotasCreditoDebito() {
        $this->commandPrepare("sp_documento_tipo_obtenerNotasCreditoDebito");
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoXOperacionTipo($operacionTipoId) {
        $this->commandPrepare("sp_documento_tipo_obtenerXOperacionTipo");
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoDatoXOperacionTipo($operacionTipoId) {
        $this->commandPrepare("sp_documento_tipo_dato_obtenerXOperacionTipo");
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoXTiposxDescripcion($idTipos,$descripcion) {
        $this->commandPrepare("sp_documento_tipo_obtenerXTiposxDescripcion");
        $this->commandAddParameter(":vin_tipos_id",$idTipos);
        $this->commandAddParameter(":vin_descripcion",$descripcion);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoXId($documentoTipoId){
        $this->commandPrepare("sp_documento_tipo_obtenerXId");
        $this->commandAddParameter(":vin_id",$documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoGenerarXDocumentoTipoId($documentoTipoId){
        $this->commandPrepare("sp_documento_tipo_generar_obtenerXDocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id",$documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato){
        $this->commandPrepare("sp_documento_tipo_obtenerXDocumentoTipoDatoXTipo");
        $this->commandAddParameter(":vin_tipo",$tipoDato);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoXDocumentoId($documentoId){        
        $this->commandPrepare("sp_documento_tipo_obtenerXDocumentoId");
        $this->commandAddParameter(":vin_documento_id",$documentoId);
        return $this->commandGetData();
    }
    
    public function buscarDocumentoTipoXOpcionXDescripcion($opcionId,$busqueda) {        
        $this->commandPrepare("sp_documento_tipo_buscarXOpcionXDescripcion");
        $this->commandAddParameter(":vin_opcion_id",$opcionId);
        $this->commandAddParameter(":vin_busqueda",$busqueda);
        return $this->commandGetData();
    }
    
    public function buscarDocumentoTipoOperacionXOpcionXDescripcion($opcionId,$busqueda) {        
        $this->commandPrepare("sp_documento_tipo_operacion_buscarXOpcionXDescripcion");
        $this->commandAddParameter(":vin_opcion_id",$opcionId);
        $this->commandAddParameter(":vin_busqueda",$busqueda);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoGenerarXMovimientoTipoId($movimientoTipoId){
        $this->commandPrepare("sp_documento_tipo_obtenerGenerarXMovimientoTipoId");
        $this->commandAddParameter(":vin_movimiento_tipo_id",$movimientoTipoId);
        return $this->commandGetData();        
    }
    
    public function obtenerOpcionGenerarDocumentoXMovimientoTipoIdXDocumentoTipoId($movimientoTipoId,$documentoTipoId){        
        $this->commandPrepare("sp_opcion_obtenerIdGenerarDocumento");
        $this->commandAddParameter(":vin_movimiento_tipo_id",$movimientoTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id",$documentoTipoId);
        return $this->commandGetData();        
    }
    
    public function buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdStringArray, $descripcion){
        $this->commandPrepare("sp_documento_tipo_buscarXDocumentoTipo");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        return $this->commandGetData();
    }
    
    
    public function buscarDocumentoTipoXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda){
        $this->commandPrepare("sp_documento_tipo_buscarXDocumentoPagar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }
    
    public function buscarDocumentoTipoXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda){
        $this->commandPrepare("sp_documento_tipo_buscarXDocumentoPago");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }
    
    public function buscarDocumentoTipoXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda){
        $this->commandPrepare("sp_documento_tipo_buscarXDocumentoPagado");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }
    
    //parte contable
    public function listarDocumentoTipo($empresaId){
        $this->commandPrepare("sp_documento_tipo_listarXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();        
    }
    
    public function guardarDocumentoTipo($descripcion,$comentario,$codigoSunatId,$estadoId,
                $usuarioId,$empresaId,$documentoTipoId,$tipo){
        $this->commandPrepare("sp_documento_tipo_guardar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_descripcion",$descripcion );
        $this->commandAddParameter(":vin_comentario_defecto", $comentario);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_estado", $estadoId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_id", $documentoTipoId);
        return $this->commandGetData();        
    }
    
    public function cambiarEstado($id) {
        $this->commandPrepare("sp_documento_tipo_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    //operaciones, modal de copia
    public function obtenerDocumentoTipoXEmpresaXTipoXOperacionTipoXDocumentoTipo($operacionTipoId,$documentoTipoId,$empresaId, $tipoIds){
        $this->commandPrepare("sp_documento_tipo_obtenerXEmpresaXTipoXOperacionTipo");
        $this->commandAddParameter(":vin_operacion_tipo_id",$operacionTipoId);
        $this->commandAddParameter(":vin_documento_tipo_id",$documentoTipoId);
        $this->commandAddParameter(":vin_empresa_id",$empresaId);
        $this->commandAddParameter(":vin_tipos_id",$tipoIds);
        return $this->commandGetData();        
    }
    
    public function buscarDocumentoOperacionXDocumentoTipoXDescripcion($documentoTipoIdStringArray, $descripcion){
        $this->commandPrepare("sp_documento_tipo_operacion_buscarXDocumentoTipo");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoReporteXOperacionTipos($operacionTipoId) {
        $this->commandPrepare("sp_documento_tipo_reporteObtenerXOperacionTipos");
        $this->commandAddParameter(":vin_operacion_tipo_id", $operacionTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerDocumentoTipoPPagoXTipos($tipos){
        $this->commandPrepare("sp_documento_tipo_obtenerPPagoXTipos");
        $this->commandAddParameter(":vin_tipos", $tipos);
        return $this->commandGetData();        
    }
    
    public function obtenerDocumentoTipoPPago(){
        $this->commandPrepare("sp_documento_tipo_obtenerPPago");
        return $this->commandGetData();        
    }
    
    public function obtenerDocumentoTipoAprobacionParcial(){
        $this->commandPrepare("sp_documento_tipo_obtenerAprobacionParcial");
        return $this->commandGetData();        
    }
    
    public function obtenerDocumentoTipoProgramacionAtencion(){
        $this->commandPrepare("sp_documento_tipo_obtenerProgramacionAtencion");
        return $this->commandGetData();         
    }
    public function obtenerDocumentoTipoNC($identificadorNegocio, $empresaId){
        $this->commandPrepare("sp_documento_tipo_obtenerXIdentificador");
        $this->commandAddParameter(":vin_identificador_negocio", $identificadorNegocio);
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();         
    }
}
