<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Usuario extends ModeloBase
{

    /**
     * 
     * @return Usuario
     */
    const DEFAULT_ALIAS = "usuario";

    public function __construct()
    {
        parent::__construct();
        $this->schema_name = Schema::cbp;
        $this->table_name = 'usuario';
        $this->fields = array(
            'id', 'persona_id', 'usuario', 'clave',
            'estado', 'visible', 'fec_creacion', 'usu_creacion'
        );
    }

    static function create()
    {
        //        $this->has_upper = FALSE;
        return parent::create();
    }

    public function validarSesionPorUsuario($usuarioId, $token)
    {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_usuario_validarSesionPorUsuario");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":vin_token", $token);
        return $this->commandGetData();
    }

    public function sesionAbrir($usuarioId, $token,$ip=null)
    {
        $this->commandPrepare("sp_usuario_sesionAbrir");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        $this->commandAddParameter(":token", $token);    
        $this->commandAddParameter(":vin_ip", $ip);   
        return $this->commandGetData();
    }

    public function validateLogin($usuario, $contrasena)
    {
        $this->commandPrepare("sp_usuario_validate");
        $this->commandAddParameter(":vin_usuario", $usuario);
        $this->commandAddParameter(":vin_clave", $contrasena);
        return $this->commandGetData();
    }

    public function OpcionesUsuario($usuario, $channel)
    {
        $this->commandPrepare("sp_usuario_validate_opciones");
        $this->commandAddParameter(":vin_usuario", $usuario);
        $this->commandAddParameter(":vin_channel", $channel);
        return $this->commandGetData();
    }
    
      public function actualizarTokenUser($usuario, $tokenUser)
    {   
        $this->has_upper = FALSE;  
        $this->commandPrepare("sp_usuario_actualizar_token");
        $this->commandAddParameter(":vin_usuario", $usuario);
        $this->commandAddParameter(":vin_token_user", $tokenUser);
        return $this->commandGetData();
    }
    
    public function getUsuarioID($usuario)
    {
        $this->commandPrepare("sp_usuario_getId");
        $this->commandAddParameter(":vin_usuario", $usuario);
        return $this->commandGetData();
    }

    public function insertCookie($usuario_id, $fecha_inicio = null)
    {
        $this->commandPrepare("sp_sesion_insertCookie");
        $this->commandAddParameter(":vin_usuario_id", $usuario_id);
        $this->commandAddParameter(":vin_fecha_inicio", $fecha_inicio);
        return $this->commandGetData();
    }

    public function updateFechaCookie($usuario, $fecha_fin)
    {
        $this->commandPrepare("sp_sesion_updateFechaFinCookie");
        $this->commandAddParameter(":vin_usuario_id", $usuario);
        $this->commandAddParameter(":vin_fecha_fin", $fecha_fin);
        return $this->commandGetData();
    }

    public function getDataUsuario()
    {
        $this->commandPrepare("sp_usuario_getAll");
        return $this->commandGetData();
    }

    public function insertUsuario($usu_nombre, $id_colaborador, $usu_creacion, $estado, $clave, $jefeId)
    {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_usuario_insert");
        $this->commandAddParameter(":vin_usu_nombre", $usu_nombre);
        $this->commandAddParameter(":vin_id_colaborador", $id_colaborador);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_clave", $clave);
        $this->commandAddParameter(":vin_usuario_padre_id", $jefeId);
        return $this->commandGetData();
    }

    public function getUsuario($id)
    {
        $this->commandPrepare("sp_usuario_getById");
        $this->commandAddParameter(":vin_usuario_id", $id);
        return $this->commandGetData();
    }

    public function updateUsuario($id, $usuarioNombre, $idColaborador, $idPerfil, $estado, $usuarioId, $jefeId)
    {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_usuario_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_usuario_nombre", $usuarioNombre);
        $this->commandAddParameter(":vin_id_colaborador", $idColaborador);
        $this->commandAddParameter(":vin_id_perfil", $idPerfil);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_sesion_id", $usuarioId);
        $this->commandAddParameter(":vin_usuario_padre_id", $jefeId);
        return $this->commandGetData();
    }

    public function deleteUsuario($id, $id_usu_ensesion)
    {
        $this->commandPrepare("sp_usuario_delete");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function cambiarEstado($id_estado, $id_usu_ensesion)
    {
        $this->commandPrepare("sp_usuario_updateEstado");
        $this->commandAddParameter(":vin_id", $id_estado);
        $this->commandAddParameter(":vin_id_usu_sesion", $id_usu_ensesion);
        return $this->commandGetData();
    }

    public function insertDetUsuarioPerfil($id_usuario, $id_perfil, $id_empresa, $usu_creacion, $estado)
    {
        $this->commandPrepare("sp_usuario_perfil_insert");
        $this->commandAddParameter(":vin_usuario_id", $id_usuario);
        $this->commandAddParameter(":vin_perfil_id", $id_perfil);
        $this->commandAddParameter(":vin_empresa_id", $id_empresa);
        $this->commandAddParameter(":vin_usu_creacion", $usu_creacion);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function colaboradorPorUsuario($id_usuario)
    {
        $this->commandPrepare("sp_persona_getByUsuario");
        $this->commandAddParameter(":vin_id", $id_usuario);
        return $this->commandGetData();
    }

    public function recuperarContrasena($usu_email)
    {
        $this->commandPrepare("sp_usuario_getPassword");
        $this->commandAddParameter(":vin_usu_email", $usu_email);
        return $this->commandGetData();
    }

    public function obtenerContrasenaActual($usuario)
    {
        $this->commandPrepare("sp_usuario_getById");
        $this->commandAddParameter(":vin_usuario_id", $usuario);
        return $this->commandGetData();
    }

    public function cambiarContrasena($usuario, $contra_actual, $contra_nueva)
    {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_usuario_cambiarContrasenia");
        $this->commandAddParameter(":vin_usuario_id", $usuario);
        $this->commandAddParameter(":vin_clave_actual", $contra_actual);
        $this->commandAddParameter(":vin_clave_nueva", $contra_nueva);
        return $this->commandGetData();
    }

    public function actualizarContrasenia($usuario, $contra_actual)
    {
        $this->has_upper = FALSE;
        $this->commandPrepare("sp_usuario_actualizarContrasenia");
        $this->commandAddParameter(":vin_usuario_id", $usuario);
        $this->commandAddParameter(":vin_clave_actual", $contra_actual);
        return $this->commandGetData();
    }

    public function getEnviarEmail($tipo)
    {
        $this->commandPrepare("sp_email_obtener_tipo_email");
        $this->commandAddParameter(":vin_tipo", $tipo);
        return $this->commandGetData();
    }

    public function insertUsuarioEmpresa($id_p, $id_e, $estadoep)
    {
        $this->commandPrepare("sp_usuario_empresa_insert");
        $this->commandAddParameter(":vin_id_p", $id_p);
        $this->commandAddParameter(":vin_id_e", $id_e);
        $this->commandAddParameter(":vin_estado", $estadoep);
        return $this->commandGetData();
    }

    public function updateUsuarioEmpresa($id, $idEmpresa, $estado)
    {
        $this->commandPrepare("sp_usuario_empresa_update");
        $this->commandAddParameter(":vin_id", $id);
        $this->commandAddParameter(":vin_id_emp", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        return $this->commandGetData();
    }

    public function updateDetUsuarioPerfil($id_usaurio, $idPerfil, $idEmpresa, $estado, $usuarioId)
    {
        $this->commandPrepare("sp_usuario_perfil_update");
        $this->commandAddParameter(":vin_usuario_id", $id_usaurio);
        $this->commandAddParameter(":vin_perfil_id", $idPerfil);
        $this->commandAddParameter(":vin_empresa_id", $idEmpresa);
        $this->commandAddParameter(":vin_estado", $estado);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerCorreoXUsuario($usuario)
    {
        $this->commandPrepare("sp_usuario_obtenerCorreoXUsuario");
        $this->commandAddParameter(":vin_usuario", $usuario);
        return $this->commandGetData();
    }

    public function obtenerUsuarioXCorreo($correo)
    {
        $this->commandPrepare("sp_usuario_obtenerUsuarioXCorreo");
        $this->commandAddParameter(":vin_correo", $correo);
        return $this->commandGetData();
    }

    public function obtenerUsuarioXCodidgo($usuario, $codigo)
    {
        $this->commandPrepare("sp_usuario_obtenerUsuarioXCodigo");
        $this->commandAddParameter(":vin_usuario_id", $usuario);
        $this->commandAddParameter(":vin_codigo", $codigo);
        return $this->commandGetData();
    }
    public function cerrarSesion($usuarioId)
    {
        $this->commandPrepare("sp_usuario_cerrarSesion");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function obtenerUsarioXAgenciaIdXOpcionIdXBanderaBloqueo($agenciaId, $opcionId)
    {
        $this->commandPrepare("sp_usuario_obtenerXOpcionIdXAgenciaIdXBanderaBloqueo");
        $this->commandAddParameter(":vin_agencia_id", $agenciaId);
        $this->commandAddParameter(":vin_opcion_id", $opcionId);
        return $this->commandGetData();
    }

    public function obtenerUsuarioXPerfilId($perfilId)
    {
        $this->commandPrepare("sp_usuario_obtenerXPerfilId");
        $this->commandAddParameter(":vin_perfil_id", $perfilId);
        return $this->commandGetData();
    }

    public function obtenerPersonaXUsuarioId($usuarioId)
    {
        $this->commandPrepare("sp_persona_obtenerXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function activarUsuarioXUsuarioId($usuarioId)
    {
        $this->commandPrepare("sp_persona_activarUsuarioXUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuarioId);
        return $this->commandGetData();
    }

    public function updateDeCodigoRecuperacion($id_usuario, $codigo_recuperacion)
    {
        $this->commandPrepare("sp_usuario_codigo_recuperacion_update");
        $this->commandAddParameter(":vin_usuario_id", $id_usuario);
        $this->commandAddParameter(":vin_codigo_recuperacion", $codigo_recuperacion);

        return $this->commandGetData();
    }
}
