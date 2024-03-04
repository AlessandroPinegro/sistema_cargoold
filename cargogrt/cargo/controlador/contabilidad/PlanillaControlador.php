<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanillaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';
//require_once __DIR__ . '/../../util/ImportacionExcel.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel/IOFactory.php';

class PlanillaControlador extends ControladorBase {

    public function obtenerConfiguracionInicial() {
        $empresaId = $this->getParametro("id_empresa");
        return PlanillaNegocio::create()->obtenerConfiguracionInicial($empresaId);
    }

    public function registrarActualizarImportarArchivo() {
        $arhivoContenido = $this->getParametro("arhivoContenido");
        $archivoNombre = $this->getParametro("archivoNombre");
        $archivoTipo = $this->getParametro("archivoTipo");
        $periodoId = $this->getParametro("periodoId");
        $id = $this->getParametro("id");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PlanillaNegocio::create()->registrarActualizarImportarArchivo($id, $arhivoContenido, $archivoNombre, $archivoTipo, $periodoId, $usuarioId);
    }

    public function obtenerPlaImportarArchivo() {
        return PlanillaNegocio::create()->obtenerPlaImportarArchivo();
    }
}
