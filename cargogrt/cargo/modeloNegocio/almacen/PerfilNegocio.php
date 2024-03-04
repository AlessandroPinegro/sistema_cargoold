<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/almacen/Perfil.php';
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
require_once __DIR__ . '/../../modelo/almacen/Caja.php';
require_once __DIR__ . '/../../modelo/almacen/PerfilAgenciaCaja.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/../../modelo/almacen/Persona.php';

class PerfilNegocio extends ModeloNegocioBase {

    // Sección de constantes
    const PERFIL_ADMINISTRADOR_ID = 1;
    const PERFIL_ADMINISTRADOR_TI_ID = 118;

    /**
     * 
     * @return PerfilNegocio
     */
    static function create() {
        return parent::create();
    }

    public function getDataPerfil($id_bandera = null) {
        $data = Perfil::create()->getDataPerfil();
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
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function getDataPerfilBase($id_bandera = null) {
        $data = Perfil::create()->getDataPerfilBase();
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
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function getDataComboPerfil($id_bandera) {
        $data = Perfil::create()->getDataComboPerfil();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function insertPerfil($nombre, $descripcion, $estado, $dashboard, $email, $monetaria, $usuario, $pant_principal,
            $opcionT, $personaClase, $agenciaId, $cajaId) {
        
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $visiblepe = ConstantesNegocio::PARAM_VISIBLE;
        $response = Perfil::create()->insertPerfil($nombre, $descripcion, $estado, $dashboard, $email, $monetaria, $visible, $usuario, $pant_principal);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $this->guardarPerfilPersonaClase($personaClase, $id_p, $usuario);
            $this->guardarAgenciaCaja($id_p, $agenciaId, $cajaId, $usuario);
            foreach ($opcionT as $item) {
                $id_opcion = $item['opcionId'];
                $estado = $item['estado'];
                $visualizar = $item['visualizar'];
                $agregar = $item['agregar'];
                $editar = $item['editar'];
                $anular = $item['anular'];
                $eliminar = $item['eliminar'];

                $res = PerfilNegocio::create()->updateDetOpcPerfil($id_p, $id_opcion, $estado, $usuario
                        , $visualizar
                        , $agregar
                        , $editar
                        , $anular
                        , $eliminar
                );
            }

//            if (!ObjectUtil::isEmpty($totalOpcionesMovimiento)) {
//                for ($kk = 0; $kk < count($totalOpcionesMovimiento); $kk++) {
//                    $estadoopMT = 0;
//                    $id_opcionMT = $totalOpcionesMovimiento[$kk];
//
//                    for ($mm = 0; $mm < count($totalMovimientoSeleccionado); $mm++) {
//                        if ($id_opcionMT == $totalMovimientoSeleccionado[$mm]) {
//                            $estadoopMT = 1;
//                        }
//                    }
//                    Perfil::create()->insertarMovimientoTipoPerfil($id_p, $id_opcionMT, $estadoopMT, $usuario);
//                }
//            }
            return $response;
        }
    }

    public function updatePerfil($id, $descripcion, $comentario, $estado, $dashboard, $email, $monetaria, $pant_principal, $usuarioId,
            $personaClase, $agenciaId, $cajaId) {
        $responsePerfil = Perfil::create()->obtnerPerfilXUsuarioId($usuarioId);
        $perfilId = $responsePerfil[0]['id'];
        $response = Perfil::create()->updatePerfil($id, $descripcion, $comentario, $estado, $dashboard, $email, $monetaria, $pant_principal, $perfilId);

        $this->guardarPerfilPersonaClase($personaClase, $id, $usuarioId);

        $this->guardarAgenciaCaja($id, $agenciaId, $cajaId, $usuarioId);

//        if (!ObjectUtil::isEmpty($totalOpcionesMovimiento)) {
//            for ($kk = 0; $kk < count($totalOpcionesMovimiento); $kk++) {
//                $estadoopMT = 0;
//                $id_opcionMT = $totalOpcionesMovimiento[$kk];
//
//                for ($mm = 0; $mm < count($totalMovimientoSeleccionado); $mm++) {
//                    if ($id_opcionMT == $totalMovimientoSeleccionado[$mm]) {
//                        $estadoopMT = 1;
//                    }
//                }
//                Perfil::create()->updateMovimientoTipoPerfil($id, $id_opcionMT, $estadoopMT);
//            }
//        }
        return $response;
//        }
    }

    public function getPerfil($id, $usuarioId) {
        $response_perfil = Perfil::create()->obtnerPerfilXUsuarioId($usuarioId);
        $id_per_ensesion = $response_perfil[0]['id'];
        $response = Perfil::create()->getPerfil($id);
        $response[0]['id_per_sesion'] = $id_per_ensesion;
        $response[0]['dataAgenciaCaja'] = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id);
        return $response;
    }

    public function deletePerfil($id, $nom, $usuarioId) {

//        $id_per_ensesion = $_SESSION['perfil_id'];
        $response_perfil = Perfil::create()->obtnerPerfilXUsuarioId($usuarioId);
        $id_per_ensesion = $response_perfil[0]['id'];
//        throw new WarningException($id_per_ensesion);
        $response = Perfil::create()->deletePerfil($id, $id_per_ensesion);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function getMenu($id_perfil) {
//        $arrayDataTipoMovimiento = array();
//        $response_tipo_movimiento = Perfil::create()->obterTipoMovimiento();
        $data = array();
        $arrayPadre = Perfil::create()->getMenuPadre();
        $cont = 0;
        foreach ($arrayPadre as $value) {
            $d = $value['id'];
            $nombre = $value['text'];
            $url = $value['url'];
//            if($d>0){
            $arrayHijo = Perfil::create()->getMenuHijo($d);
            $count2 = 0;

            $data[$cont] = array('id_perfil' => $id_perfil, 'id' => $d, 'nombre' => $nombre, 'url' => $url, 'hijo' => $arrayHijo);
            $count2++;
            $cont++;
//            }
        }
        return $data;
    }

    public function insertDetOpcPerfil($id_per, $id_opcion, $id_usu, $estado) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        return Perfil::create()->insertDetOpcPerfil($id_per, $id_opcion, $estado, $visible, $id_usu);
    }

    public function updateDetOpcPerfil($id_per, $id_opcion, $estado, $usuario_creacion
            , $visualizar = null
            , $agregar = null
            , $editar = null
            , $anular = null
            , $eliminar = null) {
        return Perfil::create()->updateDetOpcPerfil($id_per, $id_opcion, $estado, $usuario_creacion
                        , $visualizar
                        , $agregar
                        , $editar
                        , $anular
                        , $eliminar);
    }

    public function obtenerPantallaPrincipal($id) {
        return Perfil::create()->obtenerPantallaPrincipal($id);
    }

    public function obtenerPantallaXToken($token, $id) {

        return Perfil::create()->obtenerPantallaXToken($token, $id);
    }

    public function obtenerImagenPerfil($id_per, $id_usu) {
        return Perfil::create()->obtenerImagenPerfil($id_per, $id_usu);
    }

    public function obterTipoMovimiento() {
        return Perfil::create()->obterTipoMovimiento();
    }

    public function cambiarEstado($id, $usuario_creacion) {
        $dataUsuario = Usuario::create()->getUsuario($usuario_creacion);
        $dataPerfil = $dataUsuario[0]['perfil'];
        $perfiles = explode(";", $dataPerfil);
        foreach ($perfiles as $valor) {
            if ($valor == $id) {
                throw new WarningException('El perfil está en sesion. ');
            }
        }
        $data = Perfil::create()->cambiarEstado($id, $id_per_ensesion);
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

    public function ObtenerEmpresasXUsuarioId($id) {
        return Perfil::create()->ObtenerEmpresasXUsuarioId($id);
    }

    public function obtenerMenuXEmpresa($empresaId, $usuarioId) {
        $data = array();
        $arrayPadre = Perfil::create()->obtenerPadreMenuXEmpresaXusuario($empresaId, $usuarioId);
        $contador = 0;
        foreach ($arrayPadre as $value) {
            $idPadre = $value['id'];
            $nombre = $value['text'];
            $url = $value['url'];
            $icono_padre = $value['icono'];
            $indicador_negocio = $value['indicador_negocio'];

            if ($idPadre != -1) {
                $arrayHijo = Perfil::create()->obtenerHijoMenuXEmpresaXusuario($idPadre, $empresaId, $usuarioId);
                $data[$contador] = array('id' => $idPadre, 'nombre' => $nombre, 'url' => $url, 'icono_padre' => $icono_padre, 'indicador_negocio' => $indicador_negocio, 'hijo' => $arrayHijo);
            } else {

                $arrayHijo = Perfil::create()->obtenerMovimientoTipo($empresaId, $usuarioId);
                for ($i = 0; $i < count($arrayHijo); $i++) {
                    $arrayHijo[$i]['opcion_id'] = -2;
                    $arrayHijo[$i]['movimiento_tipo_id'] = $arrayHijo[$i]['id'];
                    $arrayHijo[$i]['id'] = "-2" . $arrayHijo[$i]['id'];
                    $arrayHijo[$i]['url'] = Configuraciones::RUTA_MOVIMIENTO . "?id=" . $arrayHijo[$i]['id'];
//                    $arrayHijo[$i]['orden'] = $i + 1;
                }

                $data[$contador] = array('id' => $idPadre, 'nombre' => $nombre, 'url' => $url, 'icono_padre' => $icono_padre, 'indicador_negocio' => $indicador_negocio, 'hijo' => $arrayHijo);
            }

            $contador++;
        }
        return $data;
    }

    public function obtenerImagenXUsuario($usuario) {
        return Perfil::create()->obtenerImagenXUsuario($usuario);
    }

    public function obtenerPersonaClase() {
        return Persona::create()->obtenerComboPersonaClase();
    }

    public function obtenerConfiguracionInicialForm($empresaId, $perfilId, $usuarioId) {
        $respuesta = new stdClass();
        $respuesta->dataComboPersonaClase = Persona::create()->obtenerComboPersonaClase();
        $respuesta->dataAgencia = Agencia::create()->obtenerAgenciaActivas($empresaId);
        $respuesta->dataMenu = PerfilNegocio::create()->getMenu($perfilId);

        if ($perfilId != '' || $perfilId != null) {
            $respuesta->dataPerfil = PerfilNegocio::create()->getPerfil($perfilId, $usuarioId);
        }

        return $respuesta;
    }

    public function obtenerCajaXAgenciaId($agenciaId) {
        return Caja::create()->obtenerCajaXAgenciaId($agenciaId);
    }

    function guardarPerfilPersonaClase($personaClase, $perfilId, $usuarioCreacion) {

        if (!ObjectUtil::isEmpty($personaClase) && !ObjectUtil::isEmpty($perfilId)) {
            Perfil::create()->eliminarPerfilPersonaClaseXPerfilId($perfilId);
            if (is_array($personaClase)) {
                foreach ($personaClase as $claseId) {
                    $res = Perfil::create()->guardarPerfilPersonaClaseXPerfilId($claseId, $perfilId, $usuarioCreacion);
                }
            }
        }
    }

    function guardarAgenciaCaja($perfilId, $dataAgencia, $dataCaja, $usuarioCreacion) {

        if (!ObjectUtil::isEmpty($dataCaja) && !ObjectUtil::isEmpty($perfilId) && !ObjectUtil::isEmpty($dataAgencia)) {
            PerfilAgenciaCaja::create()->eliminarPerfilAgenciaCajaXPerfilId($perfilId);
            foreach ($dataCaja as $cajaId) {
                $res = PerfilAgenciaCaja::create()->registrarPerfilAgenciaCaja($dataAgencia, $cajaId, $perfilId, $usuarioCreacion);
            }
        }
    }

    public function obtenerCorreosDeUsuarioXNombrePerfil($descripcion) {
        return Perfil::create()->obtenerCorreosDeUsuarioXNombrePerfil($descripcion);
    }

    public function obtenerPerfilXUsuarioId($usuarioId) {
        return Perfil::create()->obtnerPerfilXUsuarioId($usuarioId);
    }

    public function getComboAgencias() {
        $response = Caja::create()->getComboAgencias();
        return $response;
    }

    public function obtenerOpcionXIdXUsuarioId($opcionId, $usuarioId) {
        return Perfil::create()->obtenerOpcionXIdXUsuarioId($opcionId, $usuarioId);
    }
    
    public function insertPerfilAgenciaCaja($perfilId, $agenciaId, $cajaId, $usuarioCreacion) {
        return Perfil::create()->inserPerfilAgenciaCaja($perfilId, $agenciaId, $cajaId, $usuarioCreacion);
    }

}
