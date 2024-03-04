<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/ProgramacionPagoConfiguracionNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ProgramacionPagoConfiguracionControlador extends ControladorBase {

    public function listarProgramacionPagoConfiguracion() {
        return ProgramacionPagoConfiguracionNegocio::create()->listarProgramacionPagoConfiguracion();
    }

    public function obtenerConfiguracionesIniciales() {
        $ppagoConfiguracionId = $this->getParametro("ppagoConfiguracionId");
        $data = ProgramacionPagoConfiguracionNegocio::create()->obtenerConfiguracionesIniciales($ppagoConfiguracionId);
        return $data;
    }

    public function guardarProgramacionPagoConfiguracion() {
        $this->setTransaction();     
        $usuCreacion = $this->getUsuarioId();
        $programacionPagoConfiguracionId = $this->getParametro("programacionPagoConfiguracionId");
        $descripcion = $this->getParametro("descripcion");
        $proveedorId = $this->getParametro("proveedorId");
        $grupoProducto = $this->getParametro("grupoProducto");
        $comentario = $this->getParametro("comentario");
        $listaProgramacionPagoDetalle = $this->getParametro("listaProgramacionPagoDetalle");
        $listaProgramacionPagoDetalleEliminado = $this->getParametro("listaProgramacionPagoDetalleEliminado");

        $data = ProgramacionPagoConfiguracionNegocio::create()->guardarProgramacionPagoConfiguracion(
                $programacionPagoConfiguracionId, $descripcion, $proveedorId, $grupoProducto, 
                $listaProgramacionPagoDetalle, $listaProgramacionPagoDetalleEliminado,$comentario,
                $usuCreacion);
        return $data;
    }    
    
    public function eliminar(){
        $ppagoId = $this->getParametro("id");
        $estado=2;
        return ProgramacionPagoConfiguracionNegocio::create()->actualizarEstadoProgramacionPagoConfiguracion($ppagoId,$estado);        
    }

    
    public function actualizarEstado(){
        $ppagoId = $this->getParametro("id");
        $estado = $this->getParametro("nuevoEstado");
        return ProgramacionPagoConfiguracionNegocio::create()->actualizarEstadoProgramacionPagoConfiguracion($ppagoId,$estado);        
    }
}
