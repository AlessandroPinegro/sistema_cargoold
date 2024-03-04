<?php

require_once __DIR__ . '/../../modelo/almacen/DetalleQr.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class DetalleQrNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return DetalleQrNegocio
     */
    static function create() {
        return parent::create();
    }

    public function insertarXPedidoId($documentId, $usuarioId) {
        return DetalleQr::create()->insertarXPedidoId($documentId, $usuarioId);
    }
    
    public function insertarXComprobanteId($documentId, $usuarioId) {
        return DetalleQr::create()->insertarXComprobanteId($documentId, $usuarioId);
    }

    
    
}
