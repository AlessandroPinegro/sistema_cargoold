<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class Caja extends ModeloBase {

    /**
     * 
     * @return Caja
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

    public function getDataCajaXusuario($id_usu_ensesion) {
        $this->commandPrepare("sp_perfil_caja_obtenerCajaXUsuarioId");
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
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

    public function insertCaja($caja_nombre,$caja_descripcion,$caja_direccion
                ,$id_agencia, $caja_creacion,$estado,$caja_sufijo, $correlativo_inicio,$banderaVirtual) {
        $this->commandPrepare("sp_caja_insert");
        $this->commandAddParameter(":vin_caja_nombre", $caja_nombre);
        $this->commandAddParameter(":vin_caja_descripcion", $caja_descripcion);
        $this->commandAddParameter(":vin_caja_direccion", $caja_direccion);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_caja_creacion", $caja_creacion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_caja_sufijo", $caja_sufijo);
        $this->commandAddParameter(":vin_correlativo_inicio", $correlativo_inicio);
        $this->commandAddParameter(":vin_bandera_virtual", $banderaVirtual);

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
    
    
       public function updateCaja($id, $caja_nombre, $id_agencia, $caja_descripcion,
                $estado, $usuarioId,$caja_sufijo, $correlativo_inicio,$banderaVirtual,$caja_ip=null) {
        $this->commandPrepare("sp_caja_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_caja_nombre", $caja_nombre);
        $this->commandAddParameter(":vin_caja_descripcion", $caja_descripcion);
        $this->commandAddParameter(":vin_id_agencia", $id_agencia);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_sesion_id", $usuarioId);
        $this->commandAddParameter(":vin_caja_sufijo", $caja_sufijo);
        $this->commandAddParameter(":vin_correlativo_inicio", $correlativo_inicio);
        $this->commandAddParameter(":vin_bandera_virtual", $banderaVirtual);
        $this->commandAddParameter(":vin_caja_ip", $caja_ip);
        return $this->commandGetData();
    }

    public function getCaja_correlativo($id) {
        $this->commandPrepare("sp_caja_serie_correlativoXCajaId");
        $this->commandAddParameter(":vin_caja_id", $id);
        return $this->commandGetData();
    }

    public function insertCajaCorrelativo($cajaId, $documento_tipoId, $serie, $correlativo, $documento_tipo_relacionId, $estado, $caja_creacion) {
        $this->commandPrepare("sp_caja_serie_correlativoInsert");
        $this->commandAddParameter(":vin_cajaId", $cajaId);
        $this->commandAddParameter(":vin_documento_tipoId", $documento_tipoId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_correlativo", $correlativo);
        $this->commandAddParameter(":vin_documento_tipo_relacionId", $documento_tipo_relacionId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_caja_creacion", $caja_creacion);

        return $this->commandGetData();
    }
    public function obtenerDocumentoTipoXId($documento_tipoId) {
        $this->commandPrepare("sp_documento_tipo_obtenerXId");
        $this->commandAddParameter(":vin_id", $documento_tipoId);
        return $this->commandGetData();
    }

    public function updateCajaCorrelativo($correlativoId, $cajaId,$documento_tipoId, $serie, $correlativo, $documento_tipo_relacionId, $estado, $caja_creacion) {
        $this->commandPrepare("sp_caja_serie_correlativoUpdate");
        $this->commandAddParameter(":vin_Id", $correlativoId);
        $this->commandAddParameter(":vin_cajaId", $cajaId);
        $this->commandAddParameter(":vin_documento_tipoId", $documento_tipoId);
        $this->commandAddParameter(":vin_serie", $serie);
        $this->commandAddParameter(":vin_correlativo", $correlativo);
        $this->commandAddParameter(":vin_documento_tipo_relacionId", $documento_tipo_relacionId);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_caja_creacion", $caja_creacion);

        return $this->commandGetData();
    }
    public function eliminarCajaCorrelativo($correlativoId,$estado) {
        $this->commandPrepare("sp_caja_serie_correlativo_eliminarXId");
        $this->commandAddParameter(":vin_id", $correlativoId);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }
}
