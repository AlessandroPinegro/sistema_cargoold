<?php

require_once __DIR__ . '/../../modelo/almacen/Ubicacion.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class UbicacionNegocio extends ModeloNegocioBase {

    public function getDataUbicacionTipo($id_bandera) {
        $data = Ubicacion::create()->getDataUbicacionTipo();
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

    public function insertUbicacionTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion, $empresa, $combo) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $visiblepe = ConstantesNegocio::PARAM_VISIBLE;
        $response = Ubicacion::create()->insertUbicacionTipo($descripcion, $codigo, $comentario, $estado, $visible, $usu_creacion);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($response_empresa); $i++) {
                $estadoep = 0;
                $id_emp = $response_empresa[$i]['id'];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadoep = 1;
                    }
                }
                Ubicacion::create()->insertUbicacionTipoEmpresa($id_p, $id_emp, $visiblepe, $estadoep);
            }
            return $response;
        }
    }

    public function getUbicacionTipo($id) {
        return Ubicacion::create()->getUbicacionTipo($id);
    }

    public function updateUbicacionTipo($id_ubi_tipo, $descripcion, $codigo, $comentario, $estado, $empresa, $combo) {
        $response = Ubicacion::create()->updateUbicacionTipo($id_ubi_tipo, $descripcion, $codigo, $comentario, $estado);
        if ($response[0]["vout_exito"] == 0) {
            return $response[0]["vout_mensaje"];
        } else {
            for ($i = 0; $i < count($combo); $i++) {
                $estadop = 0;
                $id_emp = $combo[$i];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadop = 1;
                    }
                }
                Ubicacion::create()->updateUbicacionTipoEmpresa($id_ubi_tipo, $id_emp, $estadop);
            }
            return $response;
        }
    }

    public function deleteUbicacionTipo($id_ubi_tipo, $nom) {
        $response = Ubicacion::create()->deleteUbicacionTipo($id_ubi_tipo);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function getDataComboUbicacionTipo($id_bandera, $empresa, $combo) {
        $response_empresa = Empresa::create()->getDataEmpresaTotal();
        
        $cant = 0;
        //
        $array_total='';
        $array='';
        for ($i = 0; $i < count($response_empresa); $i++) {
            $id_emp = $response_empresa[$i]['id'];
            for ($j = 0; $j < count($empresa); $j++) {
                if ($id_emp == $empresa[$j]) {
                    $array = '('.$empresa[$j].')';
                    $array_total = $array_total.$array;
                }
            }
        }
//        $array_total="'".$array_total."'";
//        throw new WarningException($array_total);
        $data = Ubicacion::create()->getDataComboUbicacionTipo($array_total);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
            $cant++;
        }
        $data[0]['id_bandera'] = $id_bandera;
        $data[0]['llenar'] = $j;
        return $data;
    }

    public function cambiarTipoEstado($id_estado) {
        $data = Ubicacion::create()->cambiarTipoEstado($id_estado);
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

    //////////////////////////////////////
    //ubies
    /////////////////////////////////////
    public function getDataUbicacion($id_bandera) {
        $data = Ubicacion::create()->getDataUbicacion();
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

    public function insertUbicacion($descripcion, $codigo, $tipo, $estado, $usu_creacion, $comentario, $empresa, $combo) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $visiblepe = ConstantesNegocio::PARAM_VISIBLE;
        $response = Ubicacion::create()->insertUbicacion($descripcion, $codigo, $tipo, $estado, $visible, $usu_creacion, $comentario);
        
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];
            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($response_empresa); $i++) {
                $estadoep = 0;
                $id_emp = $response_empresa[$i]['id'];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadoep = 1;
                    }
                }
                Ubicacion::create()->insertUbicacionEmpresa($id_p, $id_emp, $visiblepe, $estadoep);
            }
            return $response;
        }
    }

    public function getUbicacion($id) {
        return Ubicacion::create()->getUbicacion($id);
    }

    public function updateUbicacion($id_ubi, $descripcion, $codigo, $tipo, $estado, $comentario) {
        $response = Ubicacion::create()->updateUbicacion($id_ubi, $descripcion, $codigo, $tipo, $estado, $comentario);
        return $response;
    }

    public function deleteUbicacion($id_ubi, $nom) {
        $response = Ubicacion::create()->deleteUbicacion($id_ubi);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $data = Ubicacion::create()->cambiarEstado($id_estado);
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
