<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AsignarRepartoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class AsignarRepartoControlador extends ControladorBase
{


    public function obtenerVehiculoRepartoQR()
    {
        $textoQR = $this->getParametro("textoQR");
        $fecha = $this->getParametro("fecha");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->obtenerVehiculoRepartoQR($textoQR, $fecha, $usuarioCreacion);

        return $resultado;
    }

    public function listarPaqueteReparto()
    {
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->listarPaqueteReparto($documentoId, $usuarioCreacion);
        return $resultado;
    }

    public function registrarPaqueteReparto()
    {
        $textoQR = $this->getParametro("textoQR");
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->registrarPaqueteReparto($textoQR, $documentoId, $usuarioCreacion);
        return $resultado;
    }

    public function registrarCodigoPaqueteReparto()
    {
        $codigo = $this->getParametro("codigo");
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->registrarCodigoPaqueteReparto($codigo, $documentoId, $usuarioCreacion);
        return $resultado;
    }

    public function eliminarPaqueteReparto()
    {
        $id = $this->getParametro("id");
        $usuarioCreacion = $this->getUsuarioId();

        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->eliminarPaqueteTracking($id, $usuarioCreacion);

        return $resultado;
    }

    public function guardarReparto()
    {
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = AsignarRepartoNegocio::create()->guardarReparto($documentoId, $usuarioCreacion);
        return $resultado;
    }
}
