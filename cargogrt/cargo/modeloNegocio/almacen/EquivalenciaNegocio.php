<?php

require_once __DIR__ . '/../../modelo/almacen/Unidad.php';
require_once __DIR__ . '/../../modelo/almacen/Equivalencia.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class EquivalenciaNegocio extends ModeloNegocioBase {

    public function getDataEquivalencia() {
//        $data = Equivalencia::create()->getDataEquivalenciaIds();
//        $data_equivalencia = Equivalencia::create()->getDataEquivalencia($id_bandera,$factor_uni);
        $data = Equivalencia::create()->getDataEquivalencia();
        $tamanio = count($data);
        $cont = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }

            $data[$i]['unidad_medida_id'];
            $data[$i]['factor'];
            $data[$i]['unidad_descripcion'];
        }
        return $data;
    }

    public function insertEquivalencia($fac_alternativa, $uni_alternativa, $fac_base, $uni_base, $usu_creacion) {
        $visible = ConstantesNegocio::PARAM_VISIBLE;
        $estado = ConstantesNegocio::PARAM_ACTIVO;
        $fac_alt = $fac_alternativa;
        $uni_alt = $uni_alternativa;
        $response = Equivalencia::create()->insertEquivalencia($fac_alt, $uni_alt, $fac_base, $uni_base, $estado, $usu_creacion);
        return $response;
    }

    public function getEquivalencia($id_equivalencia) {
        return Equivalencia::create()->getEquivalencia($id_equivalencia);
    }

    public function updateEquivalencia($id_equivalencia, $unidad_base, $factor_unidad, $unidad_alternativa, $factor_alternativa) {
        $response = Equivalencia::create()->updateEquivalencia($id_equivalencia, $unidad_base, $factor_unidad, $unidad_alternativa, $factor_alternativa);
        return $response;
    }

    public function deleteEquivalencia($id, $nom1, $nom2) {
        $response = Equivalencia::create()->deleteEquivalencia($id);
        $response[0]['nombre1'] = $nom1;
        $response[0]['nombre2'] = $nom2;
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $data = Equivalencia::create()->cambiarEstado($id_estado);
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

    public function validarEquivalencia($unidad_base, $unidad_alternativa) {
        return Equivalencia::create()->validarEquivalencia($unidad_base, $unidad_alternativa);
    }

}
