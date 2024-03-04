<?php

require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/Servicio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class ServicioNegocio extends ModeloNegocioBase {

    public function getDataServicio() {
        $data = Servicio::create()->getDataServicio();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    public function insertServicio($descripcion, $comentario, $estado, $usuarioCreacion,$codigo,$empresa) {
        $response = Servicio::create()->insertServicio($codigo,$comentario,$descripcion, $estado,$usuarioCreacion);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $idServicio = $response[0]['id'];
            $responseEmpresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($responseEmpresa); $i++) {
                $estadoEmpresa = 0;
                $idEmpresa = $responseEmpresa[$i]['id'];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($idEmpresa == $empresa[$j]) {
                        $estadoEmpresa = 1;
                    }
                }
                Servicio::create()->insertServicioEmpresa($idServicio, $idEmpresa, $estadoEmpresa);
            }
            return $response;
        }
    }
    
     public function getServicio($id) {
        return Servicio::create()->getServicio($id);
    } 
    public function updateServicio($id_servicio,$descripcion,$comentario,$estado,$codigo, $empresa)
    {
          $response = Servicio::create()->updateServicio($id_servicio,$descripcion,$comentario,$estado,$codigo);
          if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            
            $responseEmpresa = Empresa::create()->getDataEmpresaTotal();
            for ($i = 0; $i < count($responseEmpresa); $i++) {
                $estadop = 0;
                $id_emp = $responseEmpresa[$i]['id'];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadop = 1;
                    }
                }
                Servicio::create()->updateServicioEmpresa($id_servicio, $id_emp, $estadop);
            }
            return $response;
        }
    }
    public function deleteServicio($id,$nom) {
        $response = Servicio::create()->deleteServicio($id);
        $response[0]['nombre'] = $nom;
        return $response;
    }
    
    public function cambiarEstado($id_estado)
    {
     $data = Servicio::create()->cambiarEstado($id_estado);
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
