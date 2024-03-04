<?php

/*
 * @author Geancarlo Leiva <gleiva@imaginatecperu.com>
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 * @abstract Clase donde se implementará el Componente
 */

require_once __DIR__ . '/../../modelo/commons/Seguridad.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';

class SeguridadNegocio extends ModeloNegocioBase {

    public function insertLogAcceso($usuario_id, $ip, $navegador) {
        return Seguridad::create()->insertLogAcceso($usuario_id, $ip, $navegador);
    }

    public function insertLogOperacion($log_acceso_id, $entidad, $operacion, $val_anterior, $val_actual) {
        return Seguridad::create()->insertLogOperacion($log_acceso_id, $entidad, $operacion, $val_anterior, $val_actual);
    }

    public function updateLogAcceso($id) {
        return Seguridad::create()->updateLogAcceso($id);
    }

     public function updateEstadoVisible($tabla, $campo, $bandera, $id) {
        return Seguridad::create()->updateEstadoVisible($tabla, $campo, $bandera, $id);
    }

    public function confIniDetPerMap() {
        $valida = $this->validaCarga();
        if ($valida == 1) {
//            $response = Seguridad::create()->confGetAllPerfilId();
//            foreach ($response as $perfil) {
//                Seguridad::create()->confInsertDetPerMap($perfil["id"]);
//            }
//            $this->confIniDetPerFluMap();
            $this->confIniDetElePerMap();
            $this->setMensajeEmergente("Carga de configuraciones iniciales completada satisfactoriamente.");
        }else{
            throw new WarningException($valida);
        }
    }

    public function validaCarga() {
        $valida = Seguridad::create()->confValidar();
        if ($valida[0]["det_ele_permap"] == 0) {
//            if ($valida[0]["det_per_map"] == 0) {
                return "1";
//            } else {
//                return "Data de perfil-mapaEstado existente incorrecta o cargada parcialmente.";
//            }
        } else {
            return "Data de elementos-perfilMapaEstado existente incorrecta o cargada parcialmente.";
        }
    }

    public function confIniDetPerFluMap() {
        $response = Seguridad::create()->confGetAllPerfilFlujoId();
        foreach ($response as $perfil) {
            Seguridad::create()->confInsertDetPerFluMap($perfil["id"]);
        }
    }

    public function confIniDetElePerMap() {
        $response = Seguridad::create()->confGetAllElementoId();
        foreach ($response as $elemento) {
            Seguridad::create()->confInsertDetElePerMap($elemento["id"]);
        }
    }

}
