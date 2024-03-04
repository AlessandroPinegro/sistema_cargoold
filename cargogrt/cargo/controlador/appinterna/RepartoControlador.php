<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/RepartoNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class RepartoControlador extends ControladorBase
{

    public function obtenerReparto() {

        $usuarioCreacion = $this->getUsuarioId();
        $latitud = $this->getParametro("latitude");
        $longitud = $this->getParametro("longitude");
        $resultado = MovimientoNegocio::create()->obtenerReparto($usuarioCreacion,$latitud,$longitud);

        return $resultado;
    }

    public function registrarReparto() {
        $documentoId = $this->getParametro("documentoId");
        $personaId = $this->getParametro("personaId");
        $direccionId = $this->getParametro("direccionId");
        $choferId = $this->getParametro("choferId");
        $vehiculoId = $this->getParametro("vehiculoId");
        $personaRecepcionNombre = $this->getParametro("personaNombre");
        $personaRecepcionDocumento = $this->getParametro("personaDocumento");
        $documentoAdjunto = $this->getParametro("documentoAdjunto");
        $numeroPedido = $this->getParametro("numeroPedido");
        $usuarioCreacion = $this->getUsuarioId();
        $resultado = MovimientoNegocio::create()->registrarReparto($documentoId, $personaId, $direccionId,
                $usuarioCreacion, $choferId, $vehiculoId, $personaRecepcionNombre, $personaRecepcionDocumento, $documentoAdjunto,$numeroPedido);

        return $resultado;
    }

    public function obtenerDetalleReparto()
    {
        $documentoId = $this->getParametro("documentoId");
        $personaId = $this->getParametro("personaId");
        $direccionId = $this->getParametro("direccionId");
        // $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->obtenerDetalleReparto($documentoId, $personaId, $direccionId);
        return $resultado;
    }

    public function eliminarReparto()
    {
        $id = $this->getParametro("id");
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->eliminarReparto($id);

        return $resultado;
    }

    public function cancelarReparto()
    {
        $documentoId = $this->getParametro("documentoId");
        $personaId = $this->getParametro("personaId");
        $direccionId = $this->getParametro("direccionId");
        $motivo = $this->getParametro("motivo");
        $numeroPedido = $this->getParametro("numeroPedido");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->cancelarReparto($documentoId, $usuarioCreacion, $personaId, $direccionId, $motivo,$numeroPedido);

        return $resultado;
    }

    public function repartoNoEntregado()
    {
        $documentoId = $this->getParametro("documentoId");
        $personaId = $this->getParametro("personaId");
        $direccionId = $this->getParametro("direccionId");
        $motivo = $this->getParametro("motivo");
        $observacion = $this->getParametro("observacion");
        $numeroPedido = $this->getParametro("numeroPedido");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->repartoNoEntregado($documentoId, $usuarioCreacion, $personaId, $direccionId, $motivo, $observacion,$numeroPedido);

        return $resultado;
    }

    public function consultarDocumento()
    {
        $tipoDocumento = $this->getParametro("tipoDocumento");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->consultarDocumento($tipoDocumento, $numeroDocumento, $usuarioCreacion);

        return $resultado;
    }
}
