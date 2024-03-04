<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/VentaTipoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

/**
 * Description of VentaTipoControlador
 *
 * @author Administrador
 */
class VentaTipoControlador extends ControladorBase {

    public function listarVentaTipo() {
        $empresaId = $this->getParametro("empresaId");
        return VentaTipoNegocio::create()->listarVentaTipo($empresaId);
    }

    public function obtenerConfiguracionesIniciales() {
        $empresaId = $this->getParametro("empresaId");
        return VentaTipoNegocio::create()->obtenerConfiguracionesIniciales($empresaId);
    }

    public function obtenerVentaTipoXid() {
        $id = $this->getParametro("id");
        return VentaTipoNegocio::create()->obtenerVentaTipoXid($id);
    }

    public function guardarVentaTipo() {
        $this->setTransaction();

        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $codigoExportacion = $this->getParametro("codigoExportacion");
        $notaCredito = $this->getParametro("notaCredito");
        $valorVentaInafecto = $this->getParametro("valorVentaInafecto");
        $estadoId = $this->getParametro("estadoId");
        $caracteristicasSeleccionadas = $this->getParametro("caracteristicasSeleccionadas");
        $documentosSeleccionados = $this->getParametro("documentosSeleccionados");



        //id's
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $ventaTipoId = $this->getParametro("ventaTipoId");

        return VentaTipoNegocio::create()->guardarVentaTipo($empresaId, $codigo, $descripcion, $codigoExportacion, $notaCredito, $valorVentaInafecto, $estadoId, $usuarioId, $ventaTipoId, $caracteristicasSeleccionadas, $documentosSeleccionados);
    }
    
    public function cambiarEstado()
    {
        $id = $this->getParametro("id");
        
        return VentaTipoNegocio::create()->cambiarEstado($id);
    }
    
    public function eliminar()
    {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        
        return VentaTipoNegocio::create()->eliminar($id, $nom);
    }

}
