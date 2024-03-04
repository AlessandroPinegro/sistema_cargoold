<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/almacen/Caja.php';
require_once __DIR__ . '/../../modelo/almacen/Tarifario.php';
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
include_once __DIR__ . '/../../util/Util.php';

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPMailer/class.phpmailer.php';

require_once __DIR__ . '/../../util/EmailEnvioUtil.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

class CajaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return CajaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function getDataCaja() {
        $data = Caja::create()->getDataCaja();
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

    public function insertCaja($caja_nombre, $caja_descripcion, $caja_direccion, $id_agencia, $caja_creacion, $estado,
            $caja_sufijo, $correlativo_inicio, $listaCorrelativoDetalle,$banderaVirtual) {

        $response = Caja::create()->insertCaja($caja_nombre, $caja_descripcion, $caja_direccion, $id_agencia, $caja_creacion,
                $estado, $caja_sufijo, $correlativo_inicio, $banderaVirtual);

        $cajaId = $response[0]['id'];

        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {

            $dataAgencia = AgenciaNegocio::create()->getAgencia($id_agencia);
            $perfilDescripcion = "[AGE] " . $dataAgencia[0]['codigo'] . " - [CAJA] " . $caja_nombre;
            $responsePerfil = Perfil::create()->insertPerfil($perfilDescripcion, null, 1, 0, 0, 0, $caja_creacion, null);
            $perfilId = $responsePerfil[0]["id"];

            if ($perfilId != null) {
                PerfilNegocio::create()->insertPerfilAgenciaCaja($perfilId, $id_agencia, $cajaId, $caja_creacion);
            }

            if (!ObjectUtil::isEmpty($listaCorrelativoDetalle)) {
                foreach ($listaCorrelativoDetalle as $indice => $item) {
                    $documento_tipoId = $item[1];
                    $serie = $item[3];
                    $correlativo = $item[4];
                    $documento_tipo_relacionId = ($item[5] == "" ? null : $item[5]);

                    $res = Caja::create()->insertCajaCorrelativo($cajaId, $documento_tipoId, $serie, $correlativo,
                            $documento_tipo_relacionId, $estado, $caja_creacion);
                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar la Correlativo. " . $res[0]['vout_mensaje']);
                    }
                }
            }
            return $response;
        }
    }

    public function getCaja($id, $usuarioId) {
        //      $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = new stdClass();
        $response->data = Caja::create()->getCaja($id);
        $response->correlativo = Caja::create()->getCaja_correlativo($id);

        return $response;
    }

    public function updateCaja(
            $id,
            $caja_nombre,
            $id_agencia,
            $caja_descripcion,
            $estado,
            $usuarioId,
            $caja_sufijo,
            $correlativo_inicio,
            $listaCorrelativoDetalle,
            $listaCorrelativoEliminado,
            $banderaVirtual,
            $caja_ip=null
    ) {
        $response = Caja::create()->updateCaja(
                $id,
                $caja_nombre,
                $id_agencia,
                $caja_descripcion,
                $estado,
                $usuarioId,
                $caja_sufijo,
                $correlativo_inicio,
                $banderaVirtual,
                $caja_ip
        );
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {
            $cajaId = $response[0]['id'];
            if (!ObjectUtil::isEmpty($listaCorrelativoDetalle)) {
                foreach ($listaCorrelativoDetalle as $indice => $item) {
                    $correlativoId = $item[0];
                    $documento_tipoId = $item[1];
                    $serie = $item[3];
                    $correlativo = $item[4];
                    $documento_tipo_relacionId = (!ObjectUtil::isEmpty($item[5]) ? $item[5] : NULL );

                    if (ObjectUtil::isEmpty($correlativoId)) {
                        $respuestaRegistro = Caja::create()->insertCajaCorrelativo($cajaId,
                                $documento_tipoId,
                                $serie,
                                $correlativo,
                                $documento_tipo_relacionId,
                                $estado,
                                $usuarioId);
                    } else {
                        $respuestaRegistro = Caja::create()->updateCajaCorrelativo(
                                $correlativoId,
                                $id,
                                $documento_tipoId,
                                $serie,
                                $correlativo,
                                $documento_tipo_relacionId,
                                $estado,
                                $usuarioId);
                    }

                    if ($respuestaRegistro[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al intentar actualzar el correlativo $serie " . $respuestaRegistro[0]['vout_mensaje']);
                    }
                }
            }
            if (!ObjectUtil::isEmpty($listaCorrelativoEliminado)) {
                foreach ($listaCorrelativoEliminado as $indice => $item) {
                    $correlativoId = $item[0];
                    $res2 = Caja::create()->eliminarCajaCorrelativo($correlativoId, 2);
                    if ($res2[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al Editar la Correlativo. " . $res2[0]['vout_mensaje']);
                    }
                }
            }
            return $response;
        }
    }

    public function deleteCaja($id, $nom, $usuarioId) {
        //        $id_usu_ensesion = $_SESSION['id_usuario'];

        // $responsecorrelativo = Caja::create()->getCaja_correlativo($id);
        // foreach ($responsecorrelativo as $indice => $item) {
        //     $correlativoId = $item['correlativo_id'];
        //     $res2 = Caja::create()->eliminarCajaCorrelativo($correlativoId, 2);
        // }
        // if ($res2[0]['vout_exito'] == 1) {
            $response = Caja::create()->deleteCaja($id, $usuarioId);
            $response[0]['nombre'] = $nom;
      //  }

        return $response;
    }

    public function cambiarEstado($id_estado, $usuarioId) {
        $data = Caja::create()->cambiarEstado($id_estado, $usuarioId);
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
            $responsecorrelativo = Caja::create()->getCaja_correlativo($id_estado);
            foreach ($responsecorrelativo as $indice => $item) {
                $correlativoId = $item[0];
                $res2 = Caja::create()->eliminarCajaCorrelativo($correlativoId, 0);
            }
            return $data;
        }
    }

    public function getComboAgencias() {
        $response = Caja::create()->getComboAgencias();
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

    public function obtenerPantallaPrincipalUsuario() {
        $id_perf_sesion = $_SESSION['perfil_id'];
        return Perfil::create()->obtenerPantallaPrincipal($id_perf_sesion);
    }

    public function obtenerCorreoXUsuario($usuario) {
        return Usuario::create()->obtenerCorreoXUsuario($usuario);
    }

}
