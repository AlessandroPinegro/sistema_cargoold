<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Persona
 *
 * @author CHL
 */
class Persona extends ModeloBase {

    /**
     * 
     * @return Persona
     */
    static function create() {
        return parent::create();
    }

    public function getAllPersonaClase() {
        $this->commandPrepare("sp_persona_clase_getAll");
        return $this->commandGetData();
    }

    public function getAllPersonaTarifario() {
        $this->commandPrepare("sp_persona_getAll");
        return $this->commandGetData();
    }

    public function getAllPersonaTipo() {
        $this->commandPrepare("sp_persona_tipo_getAll");
        return $this->commandGetData();
    }

    public function getDataPersona($usuarioId = 1) {
        $this->commandPrepare("sp_persona_excel_getAll");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function insertPersonaClase($descripcion, $estado, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_clase_insert");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function importaPersonaXML($xml, $usuarioCreacion, $empresaId) {
        $this->commandPrepare("sp_persona_insert_xml");
        $this->commandAddParameter(":vin_XML", $xml);
        $this->commandAddParameter(":vin_usu_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_empresa", $empresaId);
        return $this->commandGetData();
    }

    public function updatePersonaClase($id, $descripcion, $estado) {
        $this->commandPrepare("sp_persona_clase_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function cambiarEstadoPersonaClase($id, $estado = 0) {
        $this->commandPrepare("sp_persona_clase_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function verificarPersona($id, $usuarioId) {
        $this->commandPrepare("sp_persona_verificar");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function savePersonaClaseTipo($tipoId, $personaClaseId, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_clase_tipo_save");
        $this->commandAddParameter(":vin_tipo_id", $tipoId);
        $this->commandAddParameter(":vin_persona_clase_id", $personaClaseId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function deletePersonaClaseTipo($personaClaseId) {
        $this->commandPrepare("sp_persona_clase_tipo_delete");
        $this->commandAddParameter(":vin_persona_clase_id", $personaClaseId);
        return $this->commandGetData();
    }

    // para la tabla persona
    public function getAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $usuarioId = 1) {
        $this->commandPrepare("sp_persona_obtenerXCriterios");
        $this->commandAddParameter(":vin_nombres", $nombres);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo_persona", $tipoPersona);
        $this->commandAddParameter(":vin_clase_persona", $clasePersona);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elemntosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function getCantidadAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $usuarioId) {
        $this->commandPrepare("sp_persona_contador_consulta");
        $this->commandAddParameter(":vin_nombres", $nombres);
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo_persona", $tipoPersona);
        $this->commandAddParameter(":vin_clase_persona", $clasePersona);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function insertPersona($tipo, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $direccion, $direccionReferencia, $file, $estado, $usuarioCreacion, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci, $correoAlternativo, $tipoDocumentoId = null,$bandera_recojo_reparto=null,$bandera_credito_persona=null,$detlicencia,$direccionofc) {
        $this->commandPrepare("sp_persona_insert");
        $this->commandAddParameter(":vin_persona_tipo", $tipo);
        $this->commandAddParameter(":vin_codigo_identificacion", $codigoIdentificacion);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_paterno", $apellidoPaterno);
        $this->commandAddParameter(":vin_materno", $apellidoMaterno);
        $this->commandAddParameter(":vin_telefono", $telefono);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_direccion_referencia", $direccionReferencia);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_file", $file);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id2", $codigoSunatId2);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id3", $codigoSunatId3);
        $this->commandAddParameter(":nombre_bcp", $nombreBCP);
        $this->commandAddParameter(":vin_numero_cuenta_bcp", $numero_cuenta_bcp);
        $this->commandAddParameter(":vin_cci", $cci);
        $this->commandAddParameter(":vin_correoAlternativo", $correoAlternativo);
        $this->commandAddParameter(":vin_tipo_documento_id", $tipoDocumentoId);
        $this->commandAddParameter(":vin_bandera_recojo_reparto", $bandera_recojo_reparto );
	$this->commandAddParameter(":vin_bandera_credito_persona", $bandera_credito_persona);
        $this->commandAddParameter(":vin_licencia", $detlicencia); 
        $this->commandAddParameter(":vin_direccionofc", $direccionofc); 

        return $this->commandGetData();
    }

    public function insertPersonaContacto($persona, $id_colaborador, $contactoTipo, $estado, $usuarioId, $email, $celular, $correoAlternativo) {
        $this->commandPrepare("sp_persona_contacto_movil_insertar");
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_persona_contacto", $id_colaborador);
        $this->commandAddParameter(":vin_contacto_tipo", $contactoTipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_correo_alternativo", $correoAlternativo);

        return $this->commandGetData();
    }

    public function insertEmpresaContacto($persona, $id_colaborador, $contactoTipo, $estado, $usuarioId, $email, $celular, $nombreContacto, $correoAlternativo) {
        $this->commandPrepare("sp_empresa_contacto_movil_insertar");
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_persona_contacto", $id_colaborador);
        $this->commandAddParameter(":vin_contacto_tipo", $contactoTipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_nombre_contacto", $nombreContacto);
        $this->commandAddParameter(":vin_correo_alternativo", $correoAlternativo);

        return $this->commandGetData();
    }

    public function updatePersonaContacto($persona, $id_colaborador, $contactoTipo, $estado, $usuarioId, $email, $celular, $correoAlternativo) {
        $this->commandPrepare("sp_persona_contacto_movil_update");
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_persona_contacto", $id_colaborador);
        $this->commandAddParameter(":vin_contacto_tipo", $contactoTipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_correo_alternativo", $correoAlternativo);

        return $this->commandGetData();
    }

    public function updateEmpresaContacto($persona, $id_colaborador, $contactoTipo, $estado, $usuarioId, $email, $celular, $nombreContacto, $correoAlternativo) {
        $this->commandPrepare("sp_empresa_contacto_movil_update");
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_persona_contacto", $id_colaborador);
        $this->commandAddParameter(":vin_contacto_tipo", $contactoTipo);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_nombre_contacto", $nombreContacto);
        $this->commandAddParameter(":vin_correo_alternativo", $correoAlternativo);

        return $this->commandGetData();
    }

    public function insertPersonaConductor($pilotoDocumento, $pilotoLicencia, $pilotoNombre
            , $paterno, $materno, $pilotoCelular) {
        $this->commandPrepare("sp_persona_integracion_insert");
        $this->commandAddParameter(":vin_piloto_documento", $pilotoDocumento);
        $this->commandAddParameter(":vin_piloto_licencia", $pilotoLicencia);
        $this->commandAddParameter(":vin_piloto_nombre", $pilotoNombre);
        $this->commandAddParameter(":vin_paterno", $paterno);
        $this->commandAddParameter(":vin_materno", $materno);
        $this->commandAddParameter(":vin_piloto_celular", $pilotoCelular);

        return $this->commandGetData();
    }

    public function insertPersonaConductorClase($persona_id) {
        $this->commandPrepare("sp_persona_clase_integracion_insert");
        $this->commandAddParameter(":vin_persona", $persona_id);

        return $this->commandGetData();
    }

    public function obtenerPersonaXDocumentoChofer($choferId) {
        $this->commandPrepare("sp_chofer_integracion_obtener");
        $this->commandAddParameter(":vin_chofer", $choferId);

        return $this->commandGetData();
    }

    public function guardarPersonaDireccion($personaId, $prioridad, $direccion, $usuarioCreacion, $personaDireccionId = null, $direccionTipoId = null, $ubigeoId = null, $longitud = null, $latitud = null, $referencia = null, $zonaId = null) {

        $this->commandPrepare("sp_persona_direccion_guardar");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_prioridad", $prioridad);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        $this->commandAddParameter(":vin_persona_direccion_id", $personaDireccionId);
        $this->commandAddParameter(":vin_direccion_tipo_id", $direccionTipoId);
        $this->commandAddParameter(":vin_ubigeo_id", $ubigeoId);
        $this->commandAddParameter(":vin_latitud", $latitud);
        $this->commandAddParameter(":vin_logitud", $longitud);
        $this->commandAddParameter(":vin_referencia", $referencia);
        $this->commandAddParameter(":vin_zona_id", $zonaId);
        return $this->commandGetData();
    }

    public function updatePersona($id, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $direccion, $direccionReferencia, $file, $estado, $usuarioSesion, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci,$bandera_recojo_reparto=null, $bandera_credito_persona=null, $detlicencia , $direccionofc) {

        $this->commandPrepare("sp_persona_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_codigo_identificacion", $codigoIdentificacion);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_paterno", $apellidoPaterno);
        $this->commandAddParameter(":vin_materno", $apellidoMaterno);
        $this->commandAddParameter(":vin_telefono", $telefono);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_ref_direccion", $direccionReferencia);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_file", $file);
        $this->commandAddParameter(":vin_usuario_sesion", $usuarioSesion);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $codigoSunatId);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id2", $codigoSunatId2);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id3", $codigoSunatId3);
        $this->commandAddParameter(":vin_nombre_bcp", $nombreBCP);
        $this->commandAddParameter(":vin_numero_cuenta_bcp", $numero_cuenta_bcp);
        $this->commandAddParameter(":vin_cci", $cci);
        $this->commandAddParameter(":vin_bandera_recojo_reparto", $bandera_recojo_reparto);
        $this->commandAddParameter(":vin_bandera_credito_persona", $bandera_credito_persona); 
        $this->commandAddParameter(":vin_licencia", $detlicencia); 
        $this->commandAddParameter(":vin_direccionofc", $direccionofc);       
        return $this->commandGetData();
    }

    public function cambiarEstadoPersona($id, $usuarioSesion, $estado = 0) {
        $this->commandPrepare("sp_persona_cambiarEstado");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_sesion", $usuarioSesion);
        return $this->commandGetData();
    }

    public function obtenerPersonaXId($id = 0) {
        $this->commandPrepare("sp_persona_obtenerXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPersonaGetById($id) {
        $this->commandPrepare("sp_persona_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerPersonaXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_persona_getByUsuarioId");
        $this->commandAddParameter(":vin_usuarioId", $usuarioId);
        return $this->commandGetData();
    }

    // persona - empresa

    public function deletePersonaEmpresa($personaId) {
        $this->commandPrepare("sp_persona_empresa_delete");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function savePersonaEmpresa($empresaId, $personaId, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_empresa_save");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    // persona clase persna
    public function deletePersonaClasePersona($personaId) {
        $this->commandPrepare("sp_persona_clase_persona_delete");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function savePersonaClasePersona($claseId, $personaId, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_clase_persona_save");
        $this->commandAddParameter(":vin_clase_id", $claseId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerComboPersonaClase() {
        $this->has_upper_return = TRUE;
        $this->commandPrepare("sp_persona_clase_combo");
        return $this->commandGetData();
    }

    // dfunciones tablas pivote
    public function getAllPersonaClaseByTipo($idTipo) {
        $this->commandPrepare("sp_persona_clase_getAllByTipo");
        $this->commandAddParameter(":vin_id_tipo", $idTipo);
        return $this->commandGetData();
    }

    public function obtenerActivas() {
        $this->commandPrepare("sp_persona_obtenerActivas");
        return $this->commandGetData();
    }

    public function obtenerPersonaActivoXStringBusqueda($textoBusqueda, $personaId = null) {
        $this->commandPrepare("sp_persona_obtenerActivoXTextoBusqueda");
        $this->commandAddParameter(":vin_texto_busqueda", $textoBusqueda);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerPersonaActivoXStringBusqueda2($textoBusqueda, $personaId = null) {
        $this->commandPrepare("sp_persona_obtenerActivoXTextoBusqueda2");
        $this->commandAddParameter(":vin_texto_busqueda", $textoBusqueda);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerPersonaClaseActivas() {
        $this->commandPrepare("sp_persona_clase_obtenerActivas");
        return $this->commandGetData();
    }

    public function obtenerComboPersonaXPersonaClaseId($personaClaseId) {
        $this->commandPrepare("sp_persona_obtener_XPersonaClaseId");
        $this->commandAddParameter(":vin_persona_clase_id", $personaClaseId);
        return $this->commandGetData();
    }

    public function obtenerPersonaDireccionXPersonaId($personaId) {
        $this->commandPrepare("sp_persona_direccion_obtenerXPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerPersonaDireccionXPersonaIdApp($personaId, $usuarioId) {
        $this->commandPrepare("sp_persona_direccion_app_obtenerXPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerContactoDireccionXPersonaIdXUsuarioId($personaId, $usuarioId) {
        $this->commandPrepare("sp_persona_direccion_obtenerXPersonaIdXUsuarioId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPersonaDireccionXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_persona_direccion_obtenerXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPersonaPerfilVendedor() {
        $this->commandPrepare("sp_persona_obtenerPerfilVendedor");
        return $this->commandGetData();
    }

    public function buscarPersonaXNombreXDocumento($opcionId, $busqueda) {
        $this->commandPrepare("sp_persona_buscarXNombreXDocumento");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function obtenerPersonasMayorMovimiento($opcionId) {
        $this->commandPrepare("sp_persona_obtenerXMayorMovimiento");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function obtenerPersonasMayorOperacion($opcionId) {
        $this->commandPrepare("sp_persona_obtenerXMayorOperacion");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function buscarPersonaOperacionXNombreXDocumento($opcionId, $busqueda) {
        $this->commandPrepare("sp_personaOperacion_buscarXNombreXDocumento");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function obtenerActivasXDocumentoTipoId($documentoTipoId) {
        $this->commandPrepare("sp_persona_obtenerActivasXDocumentoTipoId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }

    public function buscarPersonasXDocumentoTipoXValor($documentoTipoIdStringArray, $valor) {
        $this->commandPrepare("sp_persona_buscarXDocumentoTipoXNombre");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_valor", $valor);
        return $this->commandGetData();
    }

    public function buscarPersonasXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        $this->commandPrepare("sp_persona_buscarXDocumentoPagar");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarPersonasXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        $this->commandPrepare("sp_persona_buscarXDocumentoPago");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarPersonasXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        $this->commandPrepare("sp_persona_buscarXDocumentoPagado");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        $this->commandAddParameter(":vin_tipo", $tipo);
        $this->commandAddParameter(":vin_tipoProvisionPago", $tipoProvisionPago);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function buscarPersonaListarXNombreXDocumento($busqueda, $usuarioId = 1) {
        $this->commandPrepare("sp_persona_listar_buscarXNombreXDocumento");
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function buscarPersonaClaseXDescripcion($busqueda, $usuarioId = 1) {
        $this->commandPrepare("sp_persona_listar_buscarPersonaClaseXDescripcion");
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPersonasXPersonaTipo($personaTipoId) {
        $this->commandPrepare("sp_persona_obtenerXPersonaTipoId");
        $this->commandAddParameter(":vin_persona_tipo_id", $personaTipoId);
        return $this->commandGetData();
    }

    public function obtenerContactoTipoActivos() {
        $this->commandPrepare("sp_contacto_tipo_obtenerActivos");
        return $this->commandGetData();
    }

    public function obtenerTipoDocumento() {
        $this->commandPrepare("sp_tipodocumento_getAll");
        return $this->commandGetData();
    }

    public function obtenerContactoTipoXDescripcion($contactoTipo) {
        $this->commandPrepare("sp_contacto_tipo_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $contactoTipo);
        return $this->commandGetData();
    }

    public function insertarContactoTipo($descripcion, $usuarioId) {
        $this->commandPrepare("sp_contacto_tipo_insertar");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function guardarPersonaContacto($personaId, $personaContactoId, $contactoId, $contactoTipoId, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_contacto_insertar");
        $this->commandAddParameter(":vin_persona_empresa_id", $personaId);
        $this->commandAddParameter(":vin_contacto_persona_id", $personaContactoId);
        $this->commandAddParameter(":vin_contacto_id", $contactoId);
        $this->commandAddParameter(":vin_contacto_tipo_id", $contactoTipoId);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerPersonaContactoXPersonaId($personaId) {
        $this->commandPrepare("sp_persona_contacto_obtenerXPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function eliminarPersonaContacto($personaContactoId) {
        $this->commandPrepare("sp_persona_contacto_eliminarXId");
        $this->commandAddParameter(":vin_persona_contacto_id", $personaContactoId);
        return $this->commandGetData();
    }

    public function obtenerDireccionTipoActivos() {
        $this->commandPrepare("sp_direccion_tipo_obtenerActivos");
        return $this->commandGetData();
    }

    public function obtenerUbigeoActivos() {
        $this->commandPrepare("sp_ubigeo_obtenerActivos");
        return $this->commandGetData();
    }

    public function obtenerDireccionTipoXDescripcion($direccionTipo) {
        $this->commandPrepare("sp_direccion_tipo_obtenerXDescripcion");
        $this->commandAddParameter(":vin_descripcion", $direccionTipo);
        return $this->commandGetData();
    }

    public function eliminarDireccionesxId($direccionId) {
        $this->commandPrepare("sp_direccion_eliminarxId");
        $this->commandAddParameter(":vin_direccion_id", $direccionId);
        return $this->commandGetData();
    }

    public function insertarDireccionTipo($descripcion, $usuarioId) {
        $this->commandPrepare("sp_direccion_tipo_insertar");
        $this->commandAddParameter(":vin_descripcion", $descripcion);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function eliminarPersonaDireccion($personaDireccionId) {
        $this->commandPrepare("sp_persona_direccion_eliminarXId");
        $this->commandAddParameter(":vin_persona_direccion_id", $personaDireccionId);
        return $this->commandGetData();
    }

    public function obtenerPersonaClaseXUsuarioId($usuarioId) {
        $this->commandPrepare("sp_persona_clase_obtenerXusuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerPersonaClaseXpersonaTipoIdXUsuarioId($personaTipoId, $usuarioId) {
        $this->commandPrepare("sp_persona_clase_obtenerXpersonaTipoIdXusuarioId");
        $this->commandAddParameter(":vin_persona_tipo_id", $personaTipoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId, $movimientoTipoId = NULL) {
        $this->commandPrepare("sp_persona_obtenerActivasXDocumentoTipoIdXUsuarioId");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_movimiento_tipo_id", $movimientoTipoId);
        return $this->commandGetData();
    }

    public function obtenerSunatTablaDetalleRelacionXTipoXSunatTablaDetalleId($tipo, $sunatTablaDetalleId) {
        $this->commandPrepare("sp_sunat_tabla_detalle_relacion_obtenerXTipoXSunatTablaDetalleId");
        $this->commandAddParameter(":vin_tipo_id", $tipo);
        $this->commandAddParameter(":vin_sunat_tabla_detalle_id", $sunatTablaDetalleId);
        return $this->commandGetData();
    }

    public function obtenerComboPersonaProveedores() {
        $this->commandPrepare("sp_persona_obtenerProveedores");
        return $this->commandGetData();
    }

    public function validarSimilitud($id, $nombre, $apellidoPaterno) {
        $this->commandPrepare("sp_persona_VerificarNombreSimilitud");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_apellido_paterno", $apellidoPaterno);

        return $this->commandGetData();
    }

    //busqueda de personas modal de copia en operaciones
    public function buscarPersonasXDocumentoOperacion($documentoTipoIdStringArray, $valor) {
        $this->commandPrepare("sp_persona_buscarXDocumentoOperacionXNombre");
        $this->commandAddParameter(":vin_tipo_documento_set", $documentoTipoIdStringArray);
        $this->commandAddParameter(":vin_valor", $valor);
        return $this->commandGetData();
    }

    public function obtenerPersonaXOpcionMovimiento($opcionId) {
        $this->commandPrepare("sp_persona_obtenerXOpcionMovimiento");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function obtenerPersonasMayorDocumentosPPagoXTipos($tipos) {
        $this->commandPrepare("sp_persona_obtenerDocumentosPPagoXTipos");
        $this->commandAddParameter(":vin_tipos", $tipos);
        return $this->commandGetData();
    }

    public function obtenerPersonaXCodigoIdentificacion($codigoIdentificacion) {
        $this->commandPrepare("sp_persona_obtenerXCodigoIdentificacion");
        $this->commandAddParameter(":vin_codigo_identificacion", $codigoIdentificacion);
        return $this->commandGetData();
    }

    public function buscarPersonaDocumentoEarXNombreXDocumento($opcionId, $busqueda) {
        $this->commandPrepare("sp_persona_buscarDocumentoEarXNombreXDocumento");
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        $this->commandAddParameter(":vin_busqueda", $busqueda);
        return $this->commandGetData();
    }

    public function obtenerUbigeoXId($ubigeoId) {
        $this->commandPrepare("sp_ubigeo_obtenerXId");
        $this->commandAddParameter(":vin_ubigeo_id", $ubigeoId);
        return $this->commandGetData();
    }

    public function obtenerCorreosEFACT() {
        $this->commandPrepare("sp_efact_obtenerCorreos");
        return $this->commandGetData();
    }

    public function obtenerCuentaContableXPersonaId($personaId) {
        $this->commandPrepare("sp_persona_obtenerCuentaContableXPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function guardarPersonaCentroCosto($personaId, $centroCostoId, $porcentaje, $usarioCreacionId) {
        $this->commandPrepare("sp_persona_centro_costo_guardar");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_centro_costo_id", $centroCostoId);
        $this->commandAddParameter(":vin_porcentaje", $porcentaje);
        $this->commandAddParameter(":vin_usuario_creacion", $usarioCreacionId);

        return $this->commandGetData();
    }

    public function eliminarPersonaCentroCostoXPersonaId($personaId) {
        $this->commandPrepare("sp_persona_centro_costo_eliminarxPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerPersonaCentroCostoXPersonaId($personaId) {
        $this->commandPrepare("sp_persona_centro_costo_obtenerxPersonaId");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerPersonaCentroCostoXCodigoIdentificacion($codigoIdentificacion) {
        $this->commandPrepare("sp_persona_centro_costo_obtenerXCodigoIdentificacion");
        $this->commandAddParameter(":vin_codigo_identificacion", $codigoIdentificacion);
        return $this->commandGetData();
    }

    public function obtenerPorUsuarioId($usuarioId) {
        $this->commandPrepare("sp_persona_obtenerPorUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function updatePersonaDatoApp($id, $email, $emailAlternativo, $celular) {
        $this->commandPrepare("sp_persona_update_app");
        $this->commandAddParameter(":vin_id", $id);
//        $this->commandAddParameter(":vin_persona_documento_tipo_id", $tipoDocumentoId);
//        $this->commandAddParameter(":vin_codigo_identificacion", $codigoIdentificacion);
//        $this->commandAddParameter(":vin_nombre", $nombres);
//        $this->commandAddParameter(":vin_paterno", $apellidoPaterno);
//        $this->commandAddParameter(":vin_materno", $apellidoMaterno);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_email_alternativo", $emailAlternativo);
        $this->commandAddParameter(":vin_celular", $celular);
        return $this->commandGetData();
    }

    public function guardarDireccionFavoritoXId($personaDireccionId, $favorito) {
        $this->commandPrepare("sp_persona_direccion_guardarFavoritoXId");
        $this->commandAddParameter(":vin_id", $personaDireccionId);
        $this->commandAddParameter(":vin_favorito", $favorito);
        return $this->commandGetData();
    }

    public function guardarDireccionFavorito($personaId, $direccion, $personaDireccionId, $favorito, $direccionTipoId) {
        $this->commandPrepare("sp_persona_direccion_guardarFavorito");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_persona_direccion_id", $personaDireccionId);
        $this->commandAddParameter(":vin_favorito", $favorito);
        $this->commandAddParameter(":vin_direccion_tipo_id", $direccionTipoId);
        return $this->commandGetData();
    }

    public function obtenerPersonaDocumentoTipo() {
        $this->commandPrepare("sp_persona_direccion_tipo_getAll");
        return $this->commandGetData();
    }

    public function TraerConductores() {
        $this->commandPrepare("sp_choferes_getAll");
        return $this->commandGetData();
    }

    public function TraerConductor($personaId) {
        $this->commandPrepare("sp_chofer_get");
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function getPersonaCodigo($codigo, $tipoDocumento) {
        $this->commandPrepare("sp_persona_obtenerXCodigo");
        $this->commandAddParameter(":vin_codigo", $codigo);
        $this->commandAddParameter(":vin_tipo_documento", $tipoDocumento);
        return $this->commandGetData();
    }

    public function getPersonaContactoCodigoUsuario($id_colaborador, $usuarioId) {
        $this->commandPrepare("sp_persona_contacto_obtenerXCodigo");
        $this->commandAddParameter(":vin_persona", $id_colaborador);
        $this->commandAddParameter(":vin_usuario", $usuarioId);
        return $this->commandGetData();
    }

    public function eliminarPersonaContactoMovil($persona_id, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_contacto_movil_eliminarXId");
        $this->commandAddParameter(":vin_persona_contacto_id", $persona_id);
        $this->commandAddParameter(":vin_persona_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function ActivarPersonaContacto($persona_id, $estado, $usuarioCreacion) {
        $this->commandPrepare("sp_persona_contacto_movil_activarXId");
        $this->commandAddParameter(":vin_persona_contacto_id", $persona_id);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_persona_usuario", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function eliminarPersonaDireccionMovil($usuarioId, $id_colaborador) {
        $this->commandPrepare("sp_persona_direccion_movil_eliminarXId");
        $this->commandAddParameter(":vin_persona", $id_colaborador);
        $this->commandAddParameter(":vin_usuario", $usuarioId);
        return $this->commandGetData();
    }

    public function zonaPersonaContacto($ciudad) {
        $this->commandPrepare("sp_persona_zona_obtenerxid");
        $this->commandAddParameter(":vin_ciudad", $ciudad);
        return $this->commandGetData();
    }

}
