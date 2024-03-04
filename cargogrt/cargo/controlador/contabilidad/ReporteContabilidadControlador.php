<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/ReporteContabilidadNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ReporteContabilidadControlador extends ControladorBase {

    public function obtenerDataInicialReporteSumasMensual() {
        $empresaId = $this->getParametro("empresaId");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialReporteSumasMensual($empresaId);
    }

    public function obtenerReporteSumasMensualXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $periodoId = $this->getParametro("periodoId");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasMensualXCriterios($empresaId, $periodoId);
    }

    public function obtenerDataInicialReporteSumasAnual() {
        $empresaId = $this->getParametro("empresaId");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialReporteSumasAnual($empresaId);
    }

    public function obtenerReporteSumasAnualXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $anio = $this->getParametro("anio");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasAnualXCriterios($empresaId, $anio);
    }

    public function obtenerReporteSumasExcel() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasExcel($empresaId, $valorBusquedad, $tipo);
    }

    public function obtenerDataInicialReporteSumasGeneralMensual() {
        $empresaId = $this->getParametro("empresaId");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialReporteSumasGeneralMensual($empresaId);
    }

    public function obtenerReporteSumasGeneralMensualXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $periodoId = $this->getParametro("periodoId");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasGeneralMensualXCriterios($empresaId, $periodoId);
    }

    public function obtenerDataInicialReporteSumasGeneralAnual() {
        $empresaId = $this->getParametro("empresaId");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialReporteSumasGeneralAnual($empresaId);
    }

    public function obtenerReporteSumasAnualGeneralXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $anio = $this->getParametro("anio");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasAnualGeneralXCriterios($empresaId, $anio);
    }

    public function obtenerReporteSumasGeneralExcel() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerReporteSumasGeneralExcel($empresaId, $valorBusquedad, $tipo);
    }

    public function obtenerDataInicialEstadoFinancieroFuncion() {
        $empresaId = $this->getParametro("empresaId");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialEstadoFinancieroFuncion($empresaId, $tipo);
    }

    public function obtenerEstadoFinancieroFuncionXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerEstadoFinancieroFuncionXCriterios($empresaId, $tipo, $valorBusquedad);
    }

    public function obtenerEstadoFinancieroFuncionExcel() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerEstadoFinancieroFuncionExcel($empresaId, $tipo, $valorBusquedad);
    }
    
    public function obtenerDataInicialEstadoFinancieroNaturaleza() {
        $empresaId = $this->getParametro("empresaId");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerDataInicialEstadoFinancieroNaturaleza($empresaId, $tipo);
    }

    public function obtenerEstadoFinancieroNaturalezaXCriterios() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerEstadoFinancieroNaturalezaXCriterios($empresaId, $tipo, $valorBusquedad);
    }

    public function obtenerEstadoFinancieroNaturalezaExcel() {
        $empresaId = $this->getParametro("empresaId");
        $valorBusquedad = $this->getParametro("valorBusquedad");
        $tipo = $this->getParametro("tipo");
        return ReporteContabilidadNegocio::create()->obtenerEstadoFinancieroNaturalezaExcel($empresaId, $tipo, $valorBusquedad);
    }

}
