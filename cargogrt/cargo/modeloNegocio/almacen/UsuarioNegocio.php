<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/itec/Usuario.php';
include_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../modelo/almacen/Perfil.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPMailer/class.phpmailer.php';
require_once __DIR__ . '/../../modelo/almacen/Colaborador.php';
require_once __DIR__ . '/../../util/EmailEnvioUtil.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

class UsuarioNegocio extends ModeloNegocioBase
{

    /**
     * 
     * @return UsuarioNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function validateLogin($usuarioAd, $contrasena, $channel = NULL,$tokenUser= NULL)
    {
        $usuario = Usuario::create()->validateLogin($usuarioAd, $contrasena);

        if (ObjectUtil::isEmpty($usuario) && $channel != 'web') {
            throw new WarningException("Usuario no válido, vuelva a autenticarse.");
        }

        if (!ObjectUtil::isEmpty($usuario)) {

            $token = Util::encripta($usuario . "_" . date('D M d, Y G:i:s'));
            $ip=$_SERVER['REMOTE_ADDR'];
            Usuario::create()->sesionAbrir($usuario[0]["id"], $token,$ip);
            $usuario[0][Configuraciones::PARAM_SID] = Util::encripta($usuarioAd);
            $usuario[0][Configuraciones::PARAM_USUARIO_TOKEN] = $token;
            $opciones = Usuario::create()->OpcionesUsuario($usuario[0]["id"],$channel);
            $usuario[0]['opciones'] = $opciones;
            if (ObjectUtil::isEmpty($opciones) && $channel != 'web') {
                throw new WarningException("Usuario no tiene opciones asignadas");
            }
             if (!ObjectUtil::isEmpty($tokenUser) ) {
                 Usuario::create()->actualizarTokenUser($usuario[0]["id"], $tokenUser);
            }
           
        }
        return $usuario;
    }

    public function getDataUsuario()
    {
        $data = Usuario::create()->getDataUsuario();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($data[$i]['bandera_dashboard'] == 1) {
                $data[$i]['dashboard_icono'] = 'fa fa-unlock';
                $data[$i]['dashboard_color'] = "#5cb85c";
            } else {
                $data[$i]['dashboard_icono'] = 'fa fa-lock';
                $data[$i]['dashboard_color'] = "#cb2a2a";
            }
            if ($data[$i]['bandera_monetaria'] == 1) {
                $data[$i]['monetaria_icono'] = 'fa fa-unlock';
                $data[$i]['monetaria_color'] = "#5cb85c";
            } else {
                $data[$i]['monetaria_icono'] = 'fa fa-lock';
                $data[$i]['monetaria_color'] = "#cb2a2a";
            }

            if ($data[$i]['bandera_email'] == 1) {
                $data[$i]['email_icono'] = 'fa fa-unlock';
                $data[$i]['email_color'] = "#5cb85c";
            } else {
                $data[$i]['email_icono'] = 'fa fa-lock';
                $data[$i]['email_color'] = "#cb2a2a";
            }
            $data[$i]['contra'] = Util::desencripta($data[$i]['clave']);
        }
        return $data;
    }

    public function enviarCorreo($to, $cc, $bcc, $subject, $body, $attachString = NULL, $attachFilename = NULL)
    {
        $email = new EmailEnvioUtil();
        $enviar = $email->envio($to, $cc, $bcc, $subject, $body, $attachString, $attachFilename);
        if ($enviar['status'] == '0') {
            $this->setMensajeEmergente($enviar["mensaje"], null, Configuraciones::MENSAJE_WARNING);
        }
    }

    public function insertUsuario($usu_nombre, $id_colaborador, $perfil, $usu_creacion, $estado, $empresa, $combo, $jefeId)
    {

        $estadoep = ConstantesNegocio::PARAM_ACTIVO;
        $clave_generada = Util::generateCode();
        $clave = Util::encripta($clave_generada);
        $response_colaborador = Colaborador::create()->getColaborador($id_colaborador);
        $to = $response_colaborador[0]['email'];
        $cc = $response_colaborador[0]['email'];
        $response = Usuario::create()->insertUsuario($usu_nombre, $id_colaborador, $usu_creacion, $estado, $clave, $jefeId);
        $response_perfil = Perfil::create()->getDataPerfil();
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $response_empresa = Empresa::create()->getDataEmpresaTotal();

            ///
            //            for ($i = 0; $i < count($response_empresa); $i++) {
            //                $estadoep = 0;
            //                $id_emp = $response_empresa[$i]['id'];
            //                for ($j = 0; $j < count($empresa); $j++) {
            //                    if ($id_emp == $empresa[$j]) {
            //                        $estadoep = 1;
            //                    }
            //                }
            //                Usuario::create()->insertUsuarioEmpresa($id_p, $id_emp, $estadoep);
            //            }
            ///
            $id_usuario = $response[0]["id"];
            for ($ii = 0; $ii < count($response_perfil); $ii++) {
                $estadoup = false;
                $id_perfil = $response_perfil[$ii]['id'];

                for ($jj = 0; $jj < count($perfil); $jj++) {
                    if ($id_perfil == $perfil[$jj]) {
                        $estadoup = true;
                    }
                }


                for ($i = 0; $i < count($response_empresa); $i++) {
                    $estadot = 0;
                    $estadoep = false;
                    $id_emp = $response_empresa[$i]['id'];

                    for ($j = 0; $j < count($empresa); $j++) {
                        if ($id_emp == $empresa[$j]) {
                            $estadoep = true;
                        }
                    }
                    if ($estadoup == true && $estadoep == true) {
                        $estadot = 1;
                    }
                    Usuario::create()->insertDetUsuarioPerfil($id_usuario, $id_perfil, $id_emp, $usu_creacion, $estadot);
                }
            }
            $url = Configuraciones::url_base() . "index.php";

            //Plantilla envio contraseña
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(9);
            $body = $plantilla[0]["cuerpo"];
            $subject = $plantilla[0]["asunto"];
            $innersubject = "<h3>Confirmación de usuario y password</h3>";
            $innerbody = "Datos de seguridad para el sistema <br>"
                . "Usuario: " . $usu_nombre . "<br>"
                . "Password: " . $clave_generada;
            $urlSistema = "Dirección del sistema web: " . $url . "<br>";

            $body = str_replace("[|asunto|]", $innersubject, $body);
            $body = str_replace("[|cuerpo|]", $urlSistema . $innerbody, $body);

            $this->enviarCorreo($to, $cc, null, $subject, $body, null, null);
            return $response;
        }
    }

    public function getUsuario($id, $usuarioId)
    {
        //      $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Usuario::create()->getUsuario($id);
        $ids = '';
        $response_empresa = Empresa::create()->getAllEmpresaByUsuarioId($id);
        $k = 0;
        $coma = '';
        foreach ($response_empresa as $value) {
            if ($k != 0) {
                $coma = ';';
            }
            $ids = $ids . $coma . $value['id'];
            $k++;
        }
        $response[0]['id_usu_sesion'] = $usuarioId;
        //        $response[0]['empresa'] = $ids;
        return $response;
    }

    public function updateUsuario($id, $usu_nombre, $id_colaborador, $perfil, $estado, $empresa, $combo, $usuarioId, $jefeId)
    {
        $estadop = ConstantesNegocio::PARAM_ACTIVO;
        $response = Usuario::create()->updateUsuario($id, $usu_nombre, $id_colaborador, $perfil, $estado, $usuarioId, $jefeId);
        $response_perfil = Perfil::create()->getDataPerfil();

        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            //            for ($i = 0; $i < count($response_empresa); $i++) {
            //                $estadoep = 0;
            //                $id_emp = $response_empresa[$i]['id'];
            //                for ($j = 0; $j < count($empresa); $j++) {
            //                    if ($id_emp == $empresa[$j]) {
            //                        $estadoep = 1;
            //                    }
            //                }
            //                Usuario::create()->updateUsuarioEmpresa($id, $id_emp, $estadop);
            //                Usuario::create()->insertUsuarioEmpresa($id_p, $id_emp, $estadoep);
            //            }

            $id_usuario = $response[0]["id"];
            for ($ii = 0; $ii < count($response_perfil); $ii++) {
                $estadoup = false;
                $id_perfil = $response_perfil[$ii]['id'];

                for ($jj = 0; $jj < count($perfil); $jj++) {
                    if ($id_perfil == $perfil[$jj]) {
                        $estadoup = true;
                    }
                }


                for ($i = 0; $i < count($response_empresa); $i++) {
                    $estadot = 0;
                    $estadoep = false;
                    $id_emp = $response_empresa[$i]['id'];

                    for ($j = 0; $j < count($empresa); $j++) {
                        if ($id_emp == $empresa[$j]) {
                            $estadoep = true;
                        }
                    }
                    if ($estadoup == true && $estadoep == true) {
                        $estadot = 1;
                    }
                    Usuario::create()->updateDetUsuarioPerfil($id, $id_perfil, $id_emp, $estadot, $usuarioId);
                    //                     Usuario::create()->insertDetUsuarioPerfil($id_usuario, $id_perfil,$id_emp,$usu_creacion, $estadot);
                }
            }
        }

        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {

            for ($i = 0; $i < count($combo); $i++) {
                $estadop = 0;
                $id_emp = $combo[$i];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadop = 1;
                        //                         throw new WarningException($empresa[0]);
                    }
                }
                Usuario::create()->updateUsuarioEmpresa($id, $id_emp, $estadop);
            }
            //            for ($ii = 0; $ii < count($response_perfil); $ii++) {
            //                $estadoup = 0;
            //                $id_perfil = $response_perfil[$ii]['id'];
            //                for ($jj = 0; $jj < count($perfil); $jj++) {
            //                    if ($id_perfil == $perfil[$jj]) {
            //                        $estadoup = 1;
            //                    }
            //                }
            //                Usuario::create()->updateDetUsuarioPerfil($id, $id_perfil, $estadoup);
            //            }
            return $response;
        }
    }

    public function deleteUsuario($id, $nom, $usuarioId)
    {
        //        $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Usuario::create()->deleteUsuario($id, $usuarioId);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarEstado($id_estado, $usuarioId)
    {
        $data = Usuario::create()->cambiarEstado($id_estado, $usuarioId);
        if ($data[0]["vout_exito"] == 0 && $data[0]["vout_exito"] != '') {
            throw new WarningException($data[0]["vout_mensaje"]);
        } else {
            $tamanio = count($data);
            for ($i = 0; $i < $tamanio; $i++) {
                if ($data[$i]['estado_nuevo'] == 1) {
                    $data[$i]['icono'] = "ion-checkmark-circled";
                    $data[$i]['color'] = "#5cb85c";
                } else {
                    $data[$i]['icono'] = "ion-flash-off";
                    $data[$i]['color'] = "#cb2a2a";
                }
            }
            return $data;
        }
    }

    public function getComboColaborador()
    {
        $response = Colaborador::create()->getDataComboColaborador();
        return $response;
    }

    public function getComboPerfil($id_perfil, $id_usuario)
    {
        $response = Perfil::create()->getDataComboPerfil();

        $tamanio = count($response);
        for ($i = 0; $i < $tamanio; $i++) {
            $response[$i]['id_perfil'] = $id_perfil;
        }
        //        $response[0]['id_sesion'] = $id_usu_ensesion;
        $response[0]['id_usuario'] = $id_usuario;
        $response[0]['id_perfil'] = $id_perfil;
        return $response;
    }

    public function colaboradorPorUsuario($id_usuario)
    {
        return Usuario::create()->colaboradorPorUsuario($id_usuario);
    }

    public function recuperarContrasena($usu_email)
    {
        $response = Usuario::create()->recuperarContrasena($usu_email);
        if (ObjectUtil::isEmpty($response)) {
            return $response;
        } else {
            $usuarioId = $response[0]['id'];
            $to = $response[0]['email'];
            $cc = $response[0]['email'];
            $usu_nombre = $response[0]['usuario'];
            $contrasenia = Util::desencripta($response[0]['clave']);
            $url = Configuraciones::url_base() . "index.php";

            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(9);
            $body = $plantilla[0]["cuerpo"];
            $subject = "Recuperación de Contraseña para " . $usu_nombre;
            $innersubject = "<h3>Nueva contraseña para " . $usu_nombre . " </h3>";
            $innerbody = "<b>Datos de seguridad para el sistema: </b><br>"
                . "<b>Usuario:</b> " . $usu_nombre . "<br>"
                . "<b>Contraseña:</b> " . $contrasenia;
            $urlSistema = "<h4>Dirección del sistema web:   " . $url . "</h4>";

            $body = str_replace("[|asunto|]", $innersubject, $body);
            $body = str_replace("[|cuerpo|]", $urlSistema . $innerbody, $body);

            $respuestaInsertar = EmailEnvioNegocio::create()->insertarEmailEnvio($to, $subject, $body, 1, $usuarioId);

            $this->enviarCorreo($to, $cc, null, $subject, $body, null, null);
            return $response;
        }
    }

    public function obtenerContrasenaActual($usuario)
    {
        $response = Usuario::create()->obtenerContrasenaActual($usuario);
        //        if (ObjectUtil::isEmpty($response)) {
        //            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
        //        }else{
        //            $clave = Util::desencripta($response[0]['clave']);
        //            $response[0]['clave'] = $clave;
        return $response;
        //        }
    }

    public function cambiarContrasena($usuario, $contra_actual, $contra_nueva)
    {
        //Obtener contrasenia actual 
        $responseObtenerContra = $this->obtenerContrasenaActual($usuario);
        if (ObjectUtil::isEmpty($responseObtenerContra)) {
            throw new WarningException("Usuario no tiene una cuenta");
        } else {
            $claveObtenida = Util::desencripta($responseObtenerContra[0]['clave']);
            if ($claveObtenida === $contra_actual) {
                $contra_actual = Util::encripta($contra_actual);
                $contra_nueva = Util::encripta($contra_nueva);
                $response = Usuario::create()->cambiarContrasena($usuario, $contra_actual, $contra_nueva);
            } else {
                throw new WarningException("Contraseña actual incorrecta");
            }

            //        $response[0]['pant_principal'] = $response_pantalla[0]['url'];
            return $response;
        }
        //        $id_perf_sesion = $_SESSION['perfil_id'];
        //        $response_pantalla = Perfil::create()->obtenerPantallaPrincipal($id_perf_sesion);
    }

    public function actualizarContrasenia($usuario, $contra_nueva)
    {
        //Obtener contrasenia actual 


        $contra_actual = Util::encripta($contra_nueva);
        $response = Usuario::create()->actualizarContrasenia($usuario,  $contra_actual);

        return $response;
    }

    public function obtenerPantallaPrincipalUsuario()
    {
        $id_perf_sesion = $_SESSION['perfil_id'];
        return Perfil::create()->obtenerPantallaPrincipal($id_perf_sesion);
    }

    public function obtenerCorreoXUsuario($usuario)
    {
        return Usuario::create()->obtenerCorreoXUsuario($usuario);
    }

    public function obtenerUsuarioXCorreo($correo)
    {
        return Usuario::create()->obtenerUsuarioXCorreo($correo);
    }

    public function obtenerUsuarioXCodidgo($usuario, $claveEncriptada)
    {
        return Usuario::create()->obtenerUsuarioXCodidgo($usuario, $claveEncriptada);
    }

    public function cerrarSesion($usuarioId)
    {
        return Usuario::create()->cerrarSesion($usuarioId);
    }

    public function obtenerUsarioXAgenciaIdXOpcionIdXBanderaBloqueo($agenciaId, $opcionId)
    {
        return Usuario::create()->obtenerUsarioXAgenciaIdXOpcionIdXBanderaBloqueo($agenciaId, $opcionId);
    }

    public function obtenerUsuarioXPerfilId($perfilId)
    {
        return Usuario::create()->obtenerUsuarioXPerfilId($perfilId);
    }

    public function obtenerPersonaXUsuarioId($usuarioId)
    {
        return Usuario::create()->obtenerPersonaXUsuarioId($usuarioId);
    }

    public function activarUsuarioXUsuarioId($usuarioId)
    {
        return Usuario::create()->activarUsuarioXUsuarioId($usuarioId);
    }

    public function correoActivacionUsuario($usuarioId)
    {
        $dataPersona = UsuarioNegocio::create()->obtenerPersonaXUsuarioId($usuarioId);
        $usuarioNombre = $dataPersona[0]['nombre_completo'];
        $usuarioCorreo = $dataPersona[0]['email'];
        $usuario = $dataPersona[0]['usuario'];

        $urlActivar = Configuraciones::url_host() . "usuario_activar.php?" . Util::encripta("usuario=$usuarioId");

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(32);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];
        $cuerpo = str_replace("[|usuario_nombre|]", $usuarioNombre, $cuerpo);
        $cuerpo = str_replace("[|usuario_correo|]", $usuarioCorreo, $cuerpo);
        $cuerpo = str_replace("[|url_activar|]", $urlActivar, $cuerpo);

        $asunto = str_replace("[|usuario|]", $usuario, $asunto);

        return EmailEnvioNegocio::create()->insertarEmailEnvio($usuarioCorreo, $asunto, $cuerpo, 1, $usuarioId);
        //        return Usuario::create()->obtenerPersonaXUsuarioId($usuarioId);
    }

    public function correoRestablecerClave($usuarioId, $codigo)
    {
        $dataPersona = UsuarioNegocio::create()->obtenerPersonaXUsuarioId($usuarioId);
        $usuarioNombre = $dataPersona[0]['nombre_completo'];
        $usuarioCorreo = $dataPersona[0]['email'];
        $usuario = $dataPersona[0]['usuario'];

        $urlActivar = Configuraciones::url_host() . "usuario_activar.php?" . Util::encripta("usuario=$usuarioId");

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(33);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];
        $cuerpo = str_replace("[|usuario_nombre|]", $usuarioNombre, $cuerpo);
        $cuerpo = str_replace("[|usuario_correo|]", $usuarioCorreo, $cuerpo);
        $cuerpo = str_replace("[|usuario_codigo|]", $codigo, $cuerpo);
        $cuerpo = str_replace("[|url_activar|]", $urlActivar, $cuerpo);

        $asunto = str_replace("[|usuario|]", $usuario, $asunto);

        return EmailEnvioNegocio::create()->insertarEmailEnvio($usuarioCorreo, $asunto, $cuerpo, 1, $usuarioId);
        //        return Usuario::create()->obtenerPersonaXUsuarioId($usuarioId);
    }
}
