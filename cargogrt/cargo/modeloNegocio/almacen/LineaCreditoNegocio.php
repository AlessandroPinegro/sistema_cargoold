<?php

require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/LineaCredito.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class LineaCreditoNegocio extends ModeloNegocioBase {

    public function listar() {
        
        $data = LineaCredito::create()->listar();
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
    public function insertar($personaClaseId, $moneda, $importe, $periodo, $periodoGracia, $usuarioCreacion) {
        $estado = ConstantesNegocio::PARAM_ACTIVO;
        $response = LineaCredito::create()->insertar($personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion);
        return $response;
    }
    public function actualaizar($lineaCreditoId,$personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion) {
//        $estado = ConstantesNegocio::PARAM_ACTIVO;
        $response = LineaCredito::create()->actualizar($lineaCreditoId,$personaClaseId, $moneda, $importe, $periodo, $periodoGracia,$estado,$usuarioCreacion);
        return $response;
    }
    public function eliminar($lineaCreditoId,$personaClase) {
        $response = LineaCredito::create()->eliminar($lineaCreditoId);
        $response[0]['persona_clase'] = $personaClase;
        return $response;
    }
     public function obtenerPorId($lineaCreditoId)
     {
        return LineaCredito::create()->obtenerPorId($lineaCreditoId);
     }
     public function cambiarEstado($id_estado)
    {
     $data = LineaCredito::create()->cambiarEstado($id_estado);
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
