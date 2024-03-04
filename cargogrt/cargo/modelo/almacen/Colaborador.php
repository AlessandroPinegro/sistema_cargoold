<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Perfil
 *
 * @author JSC7
 */
class Colaborador extends ModeloBase {

    static function create() {
        return parent::create();
    }
    
    
    public function getDataColaborador() {
        $this->commandPrepare("sp_persona_getAll");
        return $this->commandGetData();
    }
    
     public function insertColaborador($dni, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, 
         $celular, $email, $direccion,$direccionReferencia,$usuario,$estado,$file) {
         
        $this->commandPrepare("sp_persona_insert");
        
        /*se agregan el tipo de persona
         */
        $this->commandAddParameter(":vin_persona_tipo", 2);
        
        /*
         * estos datos ya estan llegando bien del modelo de negocio
         */
        
        $this->commandAddParameter(":vin_dni", $dni);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_paterno", $apellidoPaterno);
        $this->commandAddParameter(":vin_materno", $apellidoMaterno);
        $this->commandAddParameter(":vin_telefono", $telefono);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_direccion_referencia", $direccionReferencia);
        $this->commandAddParameter(":vin_usuario", $usuario);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_file", $file);
        return $this->commandGetData();
    }
    
    public function getColaborador($id) {
        $this->commandPrepare("sp_persona_getById");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
     public function updateColaborador($id,$dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion,$ref_direccion,$estado,$file) {
        $this->commandPrepare("sp_persona_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_dni", $dni);
        $this->commandAddParameter(":vin_nombre", $nombre);
        $this->commandAddParameter(":vin_paterno", $paterno);
        $this->commandAddParameter(":vin_materno", $materno);
        $this->commandAddParameter(":vin_telefono", $telefono);
        $this->commandAddParameter(":vin_celular", $celular);
        $this->commandAddParameter(":vin_email", $email);
        $this->commandAddParameter(":vin_direccion", $direccion);
        $this->commandAddParameter(":vin_ref_direccion", $ref_direccion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_file", $file);
        return $this->commandGetData();
    }  
    
     public function deleteColaborador($id,$idUsuarioSesion) {
        $this->commandPrepare("sp_persona_delete");
        $this->commandAddParameter(":vin_id",$id);
        $this->commandAddParameter(":vin_id_usuario_sesion", $idUsuarioSesion);
        return $this->commandGetData();
    }
    
    public function getDataComboColaborador() {
        $this->commandPrepare("sp_persona_getCombo");
        return $this->commandGetData();
    }
    
    public function cambiarEstado($id_estado,$idUsuarioSesion)
    {
        $this->commandPrepare("sp_persona_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_usu_sesion", $idUsuarioSesion);
        return $this->commandGetData();
    }
    
    public function insertColaboradorEmpresa($idPersona, $idEmpresa,$estadoep) {
        $this->commandPrepare("sp_persona_empresa_insert");
        $this->commandAddParameter(":vin_id_persona", $idPersona);
        $this->commandAddParameter(":vin_id_empresa", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }
    
    public function updateColaboradorEmpresa($id,$idEmpresa,$estado)
    {
      $this->commandPrepare("sp_persona_empresa_update ");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_empresa", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();  
    }
    
    
}