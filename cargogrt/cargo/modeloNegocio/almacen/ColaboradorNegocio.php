<?php

if(!isset($_SESSION)) 
    { 
        session_start(); 
    }

require_once __DIR__ . '/../../modelo/almacen/Colaborador.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';

class ColaboradorNegocio extends ModeloNegocioBase {

    public function getDataColaborador($id_bandera) {
        $data = Colaborador::create()->getDataColaborador();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function getDataComboColaborador($id_bandera) {
        $data = Colaborador::create()->getDataComboColaborador();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function insertColaborador($dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $usuario, $estado, $file, $empresa) {
//        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $estadoep = ConstantesNegocio::PARAM_ACTIVO;
//        $visiblepe = ConstantesNegocio::PARAM_VISIBLE;
        $decode = Util::base64ToImage($file);
//        throw new WarningException($file);
        if ($file == null || $file == '') {
            $imagen = null;
        } else {
            $imagen = $dni . '.jpg';
            file_put_contents(__DIR__ . '/../../vistas/com/colaborador/imagen/' . $imagen, $decode);
        }
        $response = Colaborador::create()->insertColaborador($dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $usuario, $estado, $imagen);

        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($response_empresa); $i++) {
                $estadoep = 0;
                $id_emp = $response_empresa[$i]['id'];
                foreach ($empresa as $emp) {
                    if ($id_emp == $emp) {
                        $estadoep = 1;
                    }
                }
                Colaborador::create()->insertColaboradorEmpresa($id_p, $id_emp, $estadoep);
            }
            return $response;
        }
    }

    public function getDetalleColaborador($id) {
        return Colaborador::create()->getColaborador($id);
    }

    public function getColaborador($id) {
        $id_col_ensesion = $_SESSION['id_colaborador'];
        $response = Colaborador::create()->getColaborador($id);
        $response[0]['id_col_sesion'] = $id_col_ensesion;
        return $response;
    }

    public function updateColaborador($id, $dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $estado, $file, $empresa) {
        $estadop = ConstantesNegocio::PARAM_ACTIVO;

        $decode = Util::base64ToImage($file);
        if ($file == null || $file == '') {
            $imagen = null;
        } else {


            $imagen = $dni . '.jpg';
            $direccion_imagen = __DIR__ . '/../../vistas/com/colaborador/imagen/' . $imagen;
            if (file_exists($direccion)) {
                unlink($direccion_imagen);
            }
        }
        file_put_contents($direccion_imagen, $decode);
        $response = Colaborador::create()->updateColaborador($id, $dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $estado, $imagen);

        if ($response[0]["vout_exito"] == 0) {
            return $response[0]["vout_mensaje"];
        } else {

            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($response_empresa); $i++) {
                $estadop = 0;
                $id_emp = $response_empresa[$i]['id'];
//                for ($j = 0; $j < count($empresa); $j++) {
                foreach ($empresa as $emp) {
                    if ($id_emp == $emp) {
                        $estadop = 1;
                    }
                }

//                }
                Colaborador::create()->updateColaboradorEmpresa($id, $id_emp, $estadop);
            }
            return $response;
        }
    }

    public function deleteColaborador($id, $nom) {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Colaborador::create()->deleteColaborador($id, $id_usu_ensesion);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Colaborador::create()->cambiarEstado($id_estado, $id_usu_ensesion);
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

}
