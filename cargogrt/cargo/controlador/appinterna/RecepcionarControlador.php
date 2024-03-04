<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/RecepcionarNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class RecepcionarControlador extends ControladorBase
{

    public function obtenerDocumentoQR()
    {
        $textoQR = $this->getParametro("textoQR");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->obtenerDocumentoQR($textoQR, $usuarioCreacion);

        return $resultado;
    }

    public function obtenerDocumentoNumeroSerie()
    {
        $serie = $this->getParametro("serie");
        $numero = $this->getParametro("numero");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->obtenerDocumentoNumeroSerie($serie, $numero, $usuarioCreacion);

        return $resultado;
    }

    public function registrarRecepcionPaqueteQR()
    {
        $textoQR = $this->getParametro("textoQR");
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->registrarRecepcionPaqueteQR($textoQR, $usuarioCreacion, $documentoId);

        return $resultado;
    }

    public function registrarRecepcionPaqueteCodigo()
    {
        $codigo = $this->getParametro("codigo");
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->registrarRecepcionPaqueteCodigo($codigo, $usuarioCreacion, $documentoId);
        return $resultado;
    }

    // ver esta funcion
    public function obtenerDocumentoPaquetes()
    {
        $documentoId = $this->getParametro("documentoId");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->obtenerDocumentoPaquetes($documentoId, $usuarioCreacion);

        return $resultado;
    }

    public function eliminarPaquete()
    {
        $id = $this->getParametro("id");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->eliminarPaqueteTracking($id, $usuarioCreacion);

        return $resultado;
    }

    public function guardarRecepcion()
    {
        $documentoId = $this->getParametro("documentoId");
        $bandera = $this->getParametro("bandera");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = RecepcionarNegocio::create()->guardarRecepcion($documentoId, $usuarioCreacion, $bandera);
        return $resultado;
    }
}
