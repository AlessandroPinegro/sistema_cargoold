<?php

require_once __DIR__ . '/../../modelo/contabilidad/CentroCosto.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';

class CentroCostoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return CentroCostoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarCentroCostoPadres($empresaId) {
        return CentroCosto::create()->listarCentroCostoPadres($empresaId);
    }

    public function obtenerHijos($padreId) {
        return CentroCosto::create()->obtenerHijos($padreId);
    }

    public function obtenerCentroCostoXId($id) {
        return CentroCosto::create()->obtenerCentroCostoXId($id);
    }

    public function guardarCentroCosto($codigo, $descripcion, $estado, $usuarioId, $centroCostoId, $padreCentroCostoId, $empresaId) {

        $resCentroCosto = CentroCosto::create()->guardarCentroCosto($codigo, $descripcion, $estado, $usuarioId, $centroCostoId, $padreCentroCostoId, $empresaId);

        if (!ObjectUtil::isEmpty($padreCentroCostoId)) {
            $dataPadre = CentroCosto::create()->obtenerCentroCostoXId($padreCentroCostoId);
        } else {
            $dataPadre = null;
        }

        $respuesta->codigo = $codigo;
        $respuesta->descripcion = $descripcion;
        $respuesta->dataPadre = $dataPadre;
        $respuesta->resultado = $resCentroCosto;
        return $respuesta;
    }

    public function eliminarCentroCosto($id) {
        return CentroCosto::create()->eliminarCentroCosto($id);
    }

    public function listarCentroCosto($empresaId) {
        return CentroCosto::create()->listarCentroCosto($empresaId);
    }

}
