<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class DetalleQr extends ModeloBase {

    /**
     * 
     * @return DetalleQr
     */
    static function create() {
        return parent::create();
    }

    public function insertarXPedidoId($documentId, $usuarioId) {
        $this->commandPrepare("sp_detalle_qr_insertXPedidoId");
        $this->commandAddParameter(":vin_documento_id", $documentId);
        $this->commandAddParameter(":vin_usuario_creacion_id", $usuarioId);
        return $this->commandGetData();
    }
    
    public function insertarXComprobanteId($documentId, $usuarioId) {
        $this->commandPrepare("sp_detalle_qr_insertXComprobanteId");
        $this->commandAddParameter(":vin_documento_id", $documentId);
        $this->commandAddParameter(":vin_usuario_creacion_id", $usuarioId);
        return $this->commandGetData();
    }
    
    

}
