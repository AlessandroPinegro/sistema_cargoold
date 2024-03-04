<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AuditoriaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ReporteNegocio.php';

class AuditoriaControlador extends AlmacenIndexControlador {
    
      public function obtenerConfiguracionesInicialesAuditoria(){
        $idEmpresa = $this->getParametro("id_empresa");
        return AuditoriaNegocio::create()->obtenerConfiguracionesInicialesAuditoria($idEmpresa);
    }
    public function obtenerDataAuditoriaPorCriterios()
    {      
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        $data = AuditoriaNegocio::create()->obtenerAuditoriaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total = AuditoriaNegocio::create()->obtenerCantidadAuditoriaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start);
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);  
    }
    
    public function obtenerDataAuditoria() {
        $criterios = $this->getParametro("criterios");
        
        $auditoriaId = $this->getParametro("auditoriaId");
        
         // $response = ReporteNegocio::create()->reporteKardex($criterios);
        $usuarioId=$this->getUsuarioId();
        $response = AuditoriaNegocio::create()->reporteKardex($usuarioId,$criterios,$auditoriaId);
//        $response[0]['hoy'] = date('d/m/Y');
        return $response;
    }

        //Auditoria
    public function finalizarAuditoria() {
        $auditoriaId = $this->getParametro("auditoriaId");
        $fecha = $this->getParametro("fecha");
        $comenatrio = $this->getParametro("comentario");
        $auditoriaData = $this->getParametro("auditoriaData");
        $personaId = $this->getParametro("personaId");
        $usuarioId = $this->getUsuarioId();
        return AuditoriaNegocio::create()->finalizarAuditoria($auditoriaId,$usuarioId,$fecha,$comenatrio,$auditoriaData,$personaId);
    }
    public function enviarAuditoria() {
        $auditoriaId = $this->getParametro("auditoriaId");
        $fecha = $this->getParametro("fecha");
        $comenatrio = $this->getParametro("comentario");
        $auditoriaData = $this->getParametro("auditoriaData");
        $personaId = $this->getParametro("personaId");
        $usuarioId = $this->getUsuarioId();
        return AuditoriaNegocio::create()->finalizarAuditoria($auditoriaId,$usuarioId,$fecha,$comenatrio,$auditoriaData,$personaId);
    }
    function obtenerDetalleAuditoria() {
        $auditoriaId = $this->getParametro("auditoria_id");
        return AuditoriaNegocio::create()->obtenerDetalleAuditoria($auditoriaId);
    }
}