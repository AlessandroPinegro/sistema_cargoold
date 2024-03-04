<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ServicioEncomiendaNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ServicioEncomiendaControlador extends ControladorBase {

    public function listarPedidosPendientesAtencion() {
        $usuarioId = $this->getUsuarioId();
        return DocumentoNegocio::create()->obtenerPedidoPendienteAtenderMovilXUsuarioId($usuarioId);
    }

    public function obtenerPersonaContactoXUsuarioId() {
        $usuarioId = $this->getUsuarioId();
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaContactoXUsuarioId($usuarioId, $personaId);
    }

    public function obtenerConfiguracionInicial() {
        $usuarioId = $this->getUsuarioId();
        $destinatarioId = $this->getParametro("destinatarioId");
        $tipo = $this->getParametro("tipo");
        return ServicioEncomiendaNegocio::create()->obtenerConfiguracionInicial($usuarioId, $destinatarioId, $tipo);
    }

    public function obtenerBienTarifario() {
        $agenciaOrigenId = $this->getParametro("agenciaOrigenId");
        $agenciaDestinoId = $this->getParametro("agenciaDestinoId");
        $personaId = $this->getParametro("personaId");
        return ServicioEncomiendaNegocio::create()->obtenerBienTarifario($agenciaOrigenId, $agenciaDestinoId, $personaId);
    }

    public function obtenerPrecioPaquete() {
        $agenciaOrigenId = $this->getParametro("agenciaOrigenId");
        $agenciaDestinoId = $this->getParametro("agenciaDestinoId");
        $personaId = $this->getParametro("personaId");

        $itemDetalle = $this->getParametro("itemDetalle");
        return ServicioEncomiendaNegocio::create()->obtenerPrecioPaquete($agenciaOrigenId, $agenciaDestinoId, $personaId, $itemDetalle);
    }

    public function obtenerActualizarPrecioDetalle() {
        $agenciaOrigenId = $this->getParametro("agenciaOrigenId");
        $agenciaDestinoId = $this->getParametro("agenciaDestinoId");
        $personaId = $this->getParametro("personaId");
        $detalle = $this->getParametro("detalle");
        return ServicioEncomiendaNegocio::create()->obtenerActualizarPrecioDetalle($agenciaOrigenId, $agenciaDestinoId, $personaId, $detalle);
    }

    public function obtenerTarifarioDireccion() {
        $agenciaOrigenId = $this->getParametro("agenciaOrigenId");
        $agenciaDestinoId = $this->getParametro("agenciaDestinoId");
        $personaId = $this->getParametro("personaId");
        $direccionOrigenId = $this->getParametro("direccionOrigenId");
        $direccionDestinoId = $this->getParametro("direccionDestinoId");
        $detalle = $this->getParametro("detalle");
        return ServicioEncomiendaNegocio::create()->obtenerTarifarioDireccion($agenciaOrigenId, $agenciaDestinoId, $personaId, $direccionOrigenId, $direccionDestinoId, $detalle);
    }

    public function validarDatosEncomienda() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documento = $this->getParametro("documento");
        $detalle = $this->getParametro("detalle");
        $this->setTransaction();
        return ServicioEncomiendaNegocio::create()->validarDatosEncomienda($opcionId,$usuarioId, $documento, $detalle);
    }

    public function registrarComprobantePago() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documento = $this->getParametro("documento");
        $detalle = $this->getParametro("detalle");
        $this->setTransaction();
        return ServicioEncomiendaNegocio::create()->registrarComprobantePago($opcionId, $usuarioId, $documento, $detalle);
    }
    
    public function consultarToken() {
        return ServicioEncomiendaNegocio::create()->obtenerParametrosNiubiz();
    }

}
