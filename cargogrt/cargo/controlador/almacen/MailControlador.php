<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PaqueteTrackingNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PedidoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class MailControlador extends ControladorBase {

    function __MailControlador() {
        
    }

    public function obtenerTranckingXPedido($documentoId, $tipo) {
        return PaqueteTrackingNegocio::create()->obtenerTranckingXPedido($documentoId, $tipo);
    }

    public function obtenerTranckingXPaquete($paqueteId, $tipo) {
        return PaqueteTrackingNegocio::create()->obtenerTranckingXPaquete($paqueteId, $tipo);
    }
    
    public function activarUsuarioXUsuarioId($usuarioId) {
        return UsuarioNegocio::create()->activarUsuarioXUsuarioId($usuarioId);
    }

    public function registrarDesbloquePedido($documentoId, $usuarioId) {
        try {
            $this->setTransaction();
            $respuesta = PedidoNegocio::create()->registrarDesbloquePedido($documentoId, $usuarioId);
            $this->setCommitTransaction();
            return $respuesta;
        } catch (Exception $ex) {
            $this->setRollbackTransaction();
            $mensaje = $ex->getMessage();
            return array(array('vout_exito' => 0, 'vout_mensaje' => $mensaje));
        }
    }

}
