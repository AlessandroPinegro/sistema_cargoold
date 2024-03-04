<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/ActivoFijoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';
//require_once __DIR__ . '/../../util/ImportacionExcel.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel/IOFactory.php';

class ActivoFijoControlador extends ControladorBase {

    public function obtenerConfiguracionInicial() {
        $empresaId = $this->getParametro("id_empresa");
        return ActivoFijoNegocio::create()->obtenerConfiguracionInicial($empresaId);
    }

    public function obtenerActivosFijos() {
        $empresaId = $this->getParametro("id_empresa");
        return ActivoFijoNegocio::create()->obtenerActivosFijos($empresaId);
    }

    public function generarDepreciacion() {
        $periodoId = $this->getParametro("periodoId");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return ActivoFijoNegocio::create()->generarDepreciacionXPeriodoId($periodoId, $usuarioId);
    }

    public function obtenerExcelActivosFijoSunat() {
        $activoFijoId = $this->getParametro("activoFijoId");
        $empresaId = $this->getParametro("id_empresa");
        return ActivoFijoNegocio::create()->obtenerExcelActivosFijoSunat($activoFijoId, $empresaId);
    }

}
