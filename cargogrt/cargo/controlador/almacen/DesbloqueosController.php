<?php
require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DesbloqueoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
class DesbloqueosController extends AlmacenIndexControlador {
   public function obtenerConfiguracionesInicialesDesbloqueo(){
      $empresaId = $this->getParametro("empresaId");
      $opcionId = $this->getOpcionId();
      $usuarioId = $this->getUsuarioId();
      return MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiestoReparto($opcionId, $empresaId, $usuarioId);
     }
     public function  obtenerDataDesbloqueos(){
      $agencia = $this->getParametro("agenciaId");
       return DesbloqueoNegocio::create()->getPedidosBloqueados($agencia);
     }
   public function confirmarDesbloqueo(){
      $serie = $this->getParametro("serie");
      $correlativo =$this->getParametro("correlativo");
       return DesbloqueoNegocio::create()->connfirmarDesbloqueo($serie, $correlativo );

   }

}