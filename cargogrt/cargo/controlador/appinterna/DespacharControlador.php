<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DespachoNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PeriodoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class DespacharControlador extends ControladorBase
{

    public function obtenerAgencia()
    {
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->getDataAgenciaDespacho($usuarioCreacion);
        return $resultado;
    }

    public function obtenerDespachoQR()
    {
        $textoQR = $this->getParametro("textoQR");
        $fecha = $this->getParametro("fecha");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->obtenerDespachoQR($textoQR, $usuarioCreacion, $fecha);

        return $resultado;
    }

    public function obtenerDespachoIttsa()
    {
        $agenciaDestinoId = $this->getParametro("destinoId");
        $agenciaDestinoCodigo = $this->getParametro("destinoNombre");
        $fechaSalida = $this->getParametro("itinerarioHoraSalida");
        // $fechaLlegada = $this->getParametro("itinerarioHoraLlegada");
        $flotaPlaca = $this->getParametro("flotaPlaca");
        $choferId = $this->getParametro("choferId");
        $copilotoId = $this->getParametro("copilotoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->obtenerDespachoIttsa(
            $agenciaDestinoId,
            $agenciaDestinoCodigo,
            $fechaSalida,
            $flotaPlaca,
            $usuarioCreacion,
            $choferId,
            $copilotoId
        );

        return $resultado;
    }

    public function listarPaqueteDespacho()
    {
        $documentoId = $this->getParametro("documentoId");
        $agenciaId = $this->getParametro("agencia_id");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->listarPaqueteDespacho($documentoId, $usuarioId, $agenciaId);
        return $resultado;
    }

    public function registrarPaqueteDespacho()
    {
        $textoQR = $this->getParametro("textoQR");
        $documentoId = $this->getParametro("documentoId");
        $agencia_id = $this->getParametro("agencia_id");
        $bandera = $this->getParametro("bandera");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->registrarPaqueteDespacho($textoQR, $documentoId, $usuarioCreacion, $agencia_id, $bandera);
        return $resultado;
    }

    public function registrarCodigoPaqueteDespacho()
    {
        $codigo = $this->getParametro("codigo");
        $documentoId = $this->getParametro("documentoId");
        $agencia_id = $this->getParametro("agencia_id");
        $bandera = $this->getParametro("bandera");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = DespachoNegocio::create()->registrarCodigoPaqueteDespacho($codigo, $documentoId, $usuarioCreacion, $agencia_id, $bandera);
        return $resultado;
    }

    public function eliminarPaqueteDespacho()
    {
        $id = $this->getParametro("id");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return  DespachoNegocio::create()->eliminarPaqueteTracking($id,$usuarioId);
    }

    public function guardarDespacho()
    {
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        return DespachoNegocio::create()->guardarDespacho($documentoId, $usuarioCreacion);
    }

    public function obtenerAgenciasIttsa()
    {
        $fecha = $this->getParametro("fecha");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->obtenerAgenciasIttsa($fecha, $usuarioCreacion);
        return $resultado;
    }

    public function probandoAgenciasIttsa()
    {
        $fecha = $this->getParametro("fecha");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->probandoAgenciasIttsa($fecha, $usuarioCreacion);
        return $resultado;
    }
}
