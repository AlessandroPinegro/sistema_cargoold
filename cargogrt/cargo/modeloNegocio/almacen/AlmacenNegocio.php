<?php

require_once __DIR__ . '/../../modelo/almacen/Almacen.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class AlmacenNegocio extends ModeloNegocioBase {
    public function getDataAlmacenTipo($id_bandera) {
        $data = Almacen::create()->getDataAlmacenTipo();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
              if (id_usuario != null) {
                $data[$i]['id_bandera'] = $id_bandera;
              }
        }
        return $data;
    }

    public function insertAlmacenTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion, $empresa, $combo) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $visiblepe =         $visible = ConstantesNegocio::PARAM_VISIBLE;

        $response = Almacen::create()->insertAlmacenTipo($descripcion, $codigo, $comentario, $estado, $visible, $usu_creacion);
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
                Almacen::create()->insertAlmacenTipoEmpresa($id_p, $id_emp, $visiblepe, $estadoep);
            }
            return $response;
        }
    }

    public function getAlmacenTipo($id) {
        return Almacen::create()->getAlmacenTipo($id);
    }

    public function updateAlmacenTipo($id_alm_tipo, $descripcion, $codigo, $comentario, $estado,$empresa,$combo) {
        $response = Almacen::create()->updateAlmacenTipo($id_alm_tipo, $descripcion, $codigo, $comentario, $estado);
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
                Almacen::create()->updateAlmacenTipoEmpresa($id_alm_tipo, $id_emp, $estadop);
            }
            return $response;
        }
    }

    public function deleteAlmacenTipo($id_alm_tipo,$nom) {
        $response = Almacen::create()->deleteAlmacenTipo($id_alm_tipo);
        $response[0]['nombre'] = $nom;
        return $response;
    }
    
    public function getDataComboAlmacenTipo($id_bandera) {
        $data = Almacen::create()->getDataComboAlmacenTipo();
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
              if (id_usuario != null) {
                $data[$i]['id_bandera'] = $id_bandera;
              }
              $j++;
        }
        $data[0]['id_bandera'] = $id_bandera;
        $data[0]['llenar'] = $j;
        return $data;
    }
    
    public function cambiarTipoEstado($id_estado)
    {
     $data = Almacen::create()->cambiarTipoEstado($id_estado);
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
    //almacenes
    /////////////////////////////////////
    public function getDataAlmacen($id_bandera) {
        $data = Almacen::create()->getDataAlmacen();
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

    public function insertAlmacen($descripcion, $codigo, $tipo, $estado, $usu_creacion,$comentario,$empresa, $combo) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $visiblepe = ConstantesNegocio::PARAM_VISIBLE;
        $estadoep = ConstantesNegocio::PARAM_ACTIVO;

        $response = Almacen::create()->insertAlmacen($descripcion, $codigo, $tipo,$estado, $visible, $usu_creacion,$comentario);
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
                Almacen::create()->insertAlmacenEmpresa($id_p, $id_emp, $visiblepe, $estadoep);
            }
            return $response;
        }
    }

    public function getAlmacen($id) {
        return Almacen::create()->getAlmacen($id);
    }

    public function updateAlmacen($id_alm,$descripcion,$codigo,$tipo,$estado,$comentario,$empresa,$combo) {
        $response = Almacen::create()->updateAlmacen($id_alm,$descripcion,$codigo,$tipo,$estado,$comentario);
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
                Almacen::create()->updateAlmacenEmpresa($id_alm, $id_emp, $estadop);
            }
            return $response;
        }
    }

    public function deleteAlmacen($id_alm,$nom) {
        $response = Almacen::create()->deleteAlmacen($id_alm);
        $response[0]['nombre'] = $nom;
        return $response;
    }
    public function cambiarEstado($id_estado)
    {
     $data = Almacen::create()->cambiarEstado($id_estado);
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
