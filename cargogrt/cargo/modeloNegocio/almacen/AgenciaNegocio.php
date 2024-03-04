<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
include_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../modelo/almacen/Perfil.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPMailer/class.phpmailer.php';
require_once __DIR__ . '/../../modelo/almacen/Colaborador.php';
require_once __DIR__ . '/../../util/EmailEnvioUtil.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

class AgenciaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return AgenciaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerAgenciaActivas() {
        return Agencia::create()->obtenerAgenciaActivas();
    } 
    public function getDataAgencia() {
        $data = Agencia::create()->getDataAgencia();
        foreach ($data as $i => $item) {
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
        }
        return $data;
    }

    public function getDataAgenciaZona() {
        $data = Agencia::create()->getDataAgenciaZona();

        foreach ($data as $i => $item) {
            if ($item['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    public function enviarCorreo($to, $cc, $bcc, $subject, $body, $attachString = NULL, $attachFilename = NULL) {
        $email = new EmailEnvioUtil();
        $enviar = $email->envio($to, $cc, $bcc, "[SGI] " . $subject, $body, $attachString, $attachFilename);
        if ($enviar['status'] == '0') {
            $this->setMensajeEmergente($enviar["mensaje"], null, Configuraciones::MENSAJE_WARNING);
        }
    }

    public function insertAgencia($codigo, $descripcion, $direccion, $ubigeo, $usuarioCreacion, $estado) {

        $response = Agencia::create()->insertAgencia($codigo, $descripcion, $direccion, $ubigeo, $usuarioCreacion, $estado);
        $agenciaId = $response[0]["id"];

        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException("No se pudo registrar, agencia ya registrada. ");
        } else {
            $perfilDescripcion = "[AGE] ". $codigo;
            $responsePerfil = Perfil::create()->insertPerfil($perfilDescripcion, null, 1, 0, 0, 0, $usuarioCreacion, null);
            $perfilId = $responsePerfil[0]["id"];
            
            if($perfilId != null){
                PerfilNegocio::create()->insertPerfilAgenciaCaja($perfilId, $agenciaId, null, $usuarioCreacion);
            }

            return $response;
        }
    }

    public function insertAgenciaZona($agenciaId, $zona_descripcion, $zona_creacion, $estado,$id_ubigeo) {


        $response = Agencia::create()->insertAgenciaZona($agenciaId, $zona_descripcion, $zona_creacion, $estado,$id_ubigeo);

        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException("No se pudo registrar ,Zona ya registrada");
        } else {
            return $response;
        }
    }

    public function getAgencia($id) {
//      $id_usu_ensesion = $_SESSION['id_usuario'];
        $dataAgencia = Agencia::create()->getAgencia($id);
        if (!ObjectUtil::isEmpty($dataAgencia)) {
            $dataAgencia[0]['dataOrganizadorPosicion'] = OrganizadorNegocio::create()->obtenerOrganizadorActivoXAgenciaIdXOrganizadorTipoId($id, OrganizadorNegocio::ORGANIZADOR_TIPO_POS_ID);
        }
        return $dataAgencia;
    }

    public function getZona($id, $usuarioId) {
        //      $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Agencia::create()->getZona($id);
        return $response;
    }

    public function updateAgencia($id, $age_nombre, $age_descripcion, $age_direccion, $ubigeoId, $estado, $usuarioId, $organizadorDefectoId, $organizadorDefectoRecepcionId) {
        $response = Agencia::create()->updateAgencia($id, $age_nombre, $age_descripcion, $age_direccion, $ubigeoId, $estado, $usuarioId, $organizadorDefectoId, $organizadorDefectoRecepcionId);
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException("No se pudo actualizar ,Agencia ya registrada");
        }
        return $response;
    }

    public function updateZona($id, $agenciaId, $zona_descripcion, $usuarioId, $estado,$id_ubigeo) {
        $response = Agencia::create()->updateZona($id, $agenciaId, $zona_descripcion, $usuarioId, $estado,$id_ubigeo);
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException("No se pudo actualizar ,Agencia ya registrada");
        } else {
            return $response;
        }
    }

    public function deleteAgencia($id, $nom, $des, $usuarioId) {
//        $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Agencia::create()->deleteAgencia($id, $usuarioId);
        $response[0]['nombre'] = $nom;
        $response[0]['descripcion'] = $des;
        return $response;
    }

    public function deleteZona($id, $zona, $usuarioId) {
        //        $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Agencia::create()->deleteZona($id, $usuarioId);
        $response[0]['nombre'] = $zona;
        return $response;
    }

    public function cambiarEstado($id_estado, $usuarioId) {
        $data = Agencia::create()->cambiarEstado($id_estado, $usuarioId);
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

    public function cambiarEstadoZona($id_estado, $usuarioId) {
        $data = Agencia::create()->cambiarEstadoZona($id_estado, $usuarioId);
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

    public function getComboUbigeo() {
        $response = Agencia::create()->getDataComboUbigeo();
        return $response;
    }

    public function getComboPerfil($id_perfil, $id_usuario) {
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

    public function colaboradorPorUsuario($id_usuario) {
        return Usuario::create()->colaboradorPorUsuario($id_usuario);
    }

    public function recuperarContrasena($usu_email) {
        $response = Usuario::create()->recuperarContrasena($usu_email);
        if ($response[0]['email'] == null || $response[0]['email'] == '') {
            return $response;
        } else {
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

            $this->enviarCorreo($to, $cc, null, $subject, $body, null, null);
            return $response;
        }
    }

    public function obtenerContrasenaActual($usuario) {
        $response = Usuario::create()->obtenerContrasenaActual($usuario);
//        if (ObjectUtil::isEmpty($response)) {
//            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
//        }else{
//            $clave = Util::desencripta($response[0]['clave']);
//            $response[0]['clave'] = $clave;
        return $response;
//        }
    }

    public function cambiarContrasena($usuario, $contra_actual, $contra_nueva) {
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

    public function obtenerPantallaPrincipalUsuario() {
        $id_perf_sesion = $_SESSION['perfil_id'];
        return Perfil::create()->obtenerPantallaPrincipal($id_perf_sesion);
    }

    public function obtenerCorreoXUsuario($usuario) {
        return Usuario::create()->obtenerCorreoXUsuario($usuario);
    }

    public function obtenerAgenciaxIdIntegracion($agenciaItegracionId){
        return Agencia::create()->obtenerAgenciaxIdIntegracion($agenciaItegracionId);
    }

}
