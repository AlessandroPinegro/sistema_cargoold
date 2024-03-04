<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class AlmacenarControlador extends ControladorBase
{

   public function consultarQrOrganizador()
   {
      $textoQR = $this->getParametro("textoQR");

      $usuarioCreacion = $this->getUsuarioId();
      $this->setTransaction();
      $resultado = MovimientoNegocio::create()->consultarQrOrganizador($textoQR, $usuarioCreacion);

      return $resultado;
   }


   public function RegistrarPaqueteQR()
   {
      $textoQR = $this->getParametro("textoQR");
      $organizadorId = $this->getParametro("organizadorId");
      $usuarioCreacion = $this->getUsuarioId();
      $this->setTransaction();
      $resultado = MovimientoNegocio::create()->registrarPaqueteQr($textoQR, $usuarioCreacion, $organizadorId);

      return $resultado;
   }

   public function RegistrarPaqueteCodigo()
   {
      $codigo = $this->getParametro("codigo");
      $organizadorId = $this->getParametro("organizadorId");
      $usuarioCreacion = $this->getUsuarioId();
      $this->setTransaction();
      $resultado = MovimientoNegocio::create()->RegistrarPaqueteCodigo($codigo, $usuarioCreacion, $organizadorId);

      return $resultado;
   }

   public function listarPaquete()
   {
      $fecha = $this->getParametro("fecha");
      $usuarioCreacion = $this->getUsuarioId();
      $this->setTransaction();
      $resultado = MovimientoNegocio::create()->listarPaquete($fecha, $usuarioCreacion);

      return $resultado;
   }

   public function eliminarPaquete()
   {
      $id = $this->getParametro("id");
      $this->setTransaction();
      $resultado = MovimientoNegocio::create()->eliminarPaquete($id);
      return $resultado;
   }
}
