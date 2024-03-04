<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class Tarifario extends ModeloBase {

    /**
     * 
     * @return Tarifario
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXId($id) {
        $this->commandPrepare("sp_caja_obtenerXId");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }

    public function obtenerCajaXAgenciaId($agenciaId) {
        $this->commandPrepare("sp_caja_obtenerXAgenciaId");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        return $this->commandGetData();
    }

    public function obtenerCajaXPerfilId($perfilId) {
        $this->commandPrepare("sp_caja_obtenerXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function getDataCaja() {
        $this->commandPrepare("sp_caja_getAll");
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado, $id_usu_ensesion) {
        $this->commandPrepare("sp_caja_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

//    public function obtenerPerfilAgenciaXUsuarioId($usuarioId) {
//        $this->commandPrepare("sp_perfil_agencia_obtenerAgenciaXUsuarioId");
//        $this->commandAddParameter(":vin_cuenta_id", $usuarioId);
//        return $this->commandGetData();
//    }

    public function insertTarifario($origen, $destino, $persona, $moneda, $kilogramo, $sobre, $paquete, $bien,
            $precioMinimo, $precioArticulo, $usu_creacion) {
        $this->commandPrepare("sp_tarifario_insert");
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_kilogramo", $kilogramo);
        $this->commandAddParameter(":vin_sobre", $sobre);
        $this->commandAddParameter(":vin_paquete", $paquete);
        $this->commandAddParameter(":vin_bien", $bien);
        $this->commandAddParameter(":vin_precio_minimo", $precioMinimo);
        $this->commandAddParameter(":vin_precio_articulo", $precioArticulo);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);

        return $this->commandGetData();
    }

    public function insertTarifarioZona($origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion) {
        $this->commandPrepare("sp_tarifariozona_insert");
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_txtsobre", $txtsobre);
        $this->commandAddParameter(":vin_txt1k", $txt1k);
        $this->commandAddParameter(":vin_txt2k", $txt2k);
        $this->commandAddParameter(":vin_txt3k", $txt3k);
        $this->commandAddParameter(":vin_txt4k", $txt4k);
        $this->commandAddParameter(":vin_txt5k", $txt5k);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);

        return $this->commandGetData();
    }

    public function getDataTarifario($origen, $destino, $persona, $articulo, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start) {
        $this->commandPrepare("sp_tarifario_getAll");
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_articulo", $articulo);
        $this->commandAddParameter(":vin_columna_ordenar", $columnaOrdenar);
        $this->commandAddParameter(":vin_forma_ordenar", $formaOrdenar);
        $this->commandAddParameter(":vin_limite", $elementosFiltrados);
        $this->commandAddParameter(":vin_tamanio", $start);
        return $this->commandGetData();
        // return $origen;
    }

    
        public function getDataTarifarioExcel($origen, $destino, $persona, $articulo) {
        $this->commandPrepare("sp_tarifario_excel_getAll");
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_articulo", $articulo);
        return $this->commandGetData();
        // return $origen;
    }
    
    public function getDataTarifarioZona($agencia, $reparto) {
        $this->commandPrepare("sp_tarifariozona_getAll");
        $this->commandAddParameter(":vin_agencia", $agencia);
        $this->commandAddParameter(":vin_reparto", $reparto);
        return $this->commandGetData();
    }

    public function deleteTarifario($id, $id_usu_ensesion) {
        $this->commandPrepare("sp_tarifario_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function deleteTarifarioZona($id, $id_usu_ensesion) {
        $this->commandPrepare("sp_tarifarioZona_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function getTarifario($id) {
        $this->commandPrepare("sp_tarifario_getById");
        $this->commandAddParameter(":vin_tarifario_id", $id);
        return $this->commandGetData();
    }

    public function getTarifarioZona($id) {
        $this->commandPrepare("sp_tarifarioZona_getById");
        $this->commandAddParameter(":vin_tarifarioZona_id", $id);
        return $this->commandGetData();
    }

    public function updateTarifario($id, $origen, $destino, $persona, $moneda, $kilogramo, $sobre, $paquete, $bien,
            $precioMinimo, $precioArticulo, $usuarioId) {
        $this->commandPrepare("sp_tarifario_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_kilogramo", $kilogramo);
        $this->commandAddParameter(":vin_sobre", $sobre);
        $this->commandAddParameter(":vin_paquete", $paquete);
        $this->commandAddParameter(":vin_bien", $bien);
        $this->commandAddParameter(":vin_precio_minimo", $precioMinimo);
        $this->commandAddParameter(":vin_precio_articulo", $precioArticulo);

        return $this->commandGetData();
    }

    public function updateTarifarioZona($id, $origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion) {
        $this->commandPrepare("sp_tarifarioZona_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_origen", $origen);
        $this->commandAddParameter(":vin_destino", $destino);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_txtsobre", $txtsobre);
        $this->commandAddParameter(":vin_txt1k", $txt1k);
        $this->commandAddParameter(":vin_txt2k", $txt2k);
        $this->commandAddParameter(":vin_txt3k", $txt3k);
        $this->commandAddParameter(":vin_txt4k", $txt4k);
        $this->commandAddParameter(":vin_txt5k", $txt5k);

        return $this->commandGetData();
    }

    public function getComboAgencias() {
        $this->commandPrepare("sp_agencia_getAll");
        return $this->commandGetData();
    }

    public function deleteCaja($id, $id_usu_ensesion) {
        $this->commandPrepare("sp_caja_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function getCaja($id) {
        $this->commandPrepare("sp_caja_getById");
        $this->commandAddParameter(":vin_caja_id", $id);
        return $this->commandGetData();
    }

    public function updateCaja($id, $caja_nombre, $id_agencia, $caja_descripcion, $estado, $usuarioId, $caja_sufijo) {
        $this->commandPrepare("sp_caja_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_caja_nombre", $caja_nombre);
        $this->commandAddParameter(":vin_caja_descripcion", $caja_descripcion);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_sesion_id", $usuarioId);
        $this->commandAddParameter(":vin_caja_sufijo", $caja_sufijo);
        return $this->commandGetData();
    }

    public function obtenerXEmpresaId($empresaId) {
        $this->commandPrepare("sp_tarifario_obtenerXEmpresaId");
        $this->commandAddParameter(":vin_empresa_id", $empresaId);
        return $this->commandGetData();
    }

    public function obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId = NULL) {
        $this->commandPrepare("sp_tarifario_obtenerXAgenciaIdXPersonaId");
        $this->commandAddParameter(":vin_agencia_origen_id", $agenciaOrigenId);
        $this->commandAddParameter(":vin_agencia_destino_id", $agenciaDestinoId);
        $this->commandAddParameter(":vin_persona_id", $personaId);
        return $this->commandGetData();
    }

    public function obtenerZonaxAgencia($AgenciaID) {
        $this->commandPrepare("sp_zonaxagencia");
        $this->commandAddParameter(":vin_agencia_id", $AgenciaID);
        return $this->commandGetData();
    }

    // import tarifario
    public function importTarifario($agenciaOr, $agenciaDes, $codPersona, $persona, $codArticulo, $articulo, $moneda, $precioKg, $precioSobre, $precio5Kg, $precioMinimo, $precioArticulo, $usuarioCreacion) {
        $this->commandPrepare("sp_tarifario_import");
        $this->commandAddParameter(":vin_agenciaOr", $agenciaOr);
        $this->commandAddParameter(":vin_agenciaDes", $agenciaDes);
        $this->commandAddParameter(":vin_codPersona", $codPersona);
        $this->commandAddParameter(":vin_persona", $persona);
        $this->commandAddParameter(":vin_codArticulo", $codArticulo);
        $this->commandAddParameter(":vin_articulo", $articulo);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_precioKg", $precioKg);
        $this->commandAddParameter(":vin_precioSobre", $precioSobre);
        $this->commandAddParameter(":vin_precio5Kg", $precio5Kg);
        $this->commandAddParameter(":vin_precio_minimo", $precioMinimo);
        $this->commandAddParameter(":vin_precio_articulo", $precioArticulo);
        $this->commandAddParameter(":vin_user", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function importTarifarioZona($agencia, $zona, $moneda, $precioSobre, $precio50, $precio51, $precio101, $precio251, $precio500, $usuarioCreacion) {
        $this->commandPrepare("sp_tarifario_zona_import");
        $this->commandAddParameter(":vin_agencia", $agencia);
        $this->commandAddParameter(":vin_zona", $zona);
        $this->commandAddParameter(":vin_moneda", $moneda);
        $this->commandAddParameter(":vin_precioSobre", $precioSobre);
        $this->commandAddParameter(":vin_precio50", $precio50);
        $this->commandAddParameter(":vin_precio51", $precio51);
        $this->commandAddParameter(":vin_precio101", $precio101);
        $this->commandAddParameter(":vin_precio251", $precio251);
        $this->commandAddParameter(":vin_precio500", $precio500);
        $this->commandAddParameter(":vin_user", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function obtenerTarifarioXPersonaDireccionId($personaDireccionId) {
        $this->commandPrepare("sp_tarifario_zona_obtenerXPersonaDireccionId");
        $this->commandAddParameter(":vin_persona_direccion", $personaDireccionId);
        return $this->commandGetData();
    }

}
