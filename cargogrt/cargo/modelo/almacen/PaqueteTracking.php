<?php

require_once __DIR__ . '/../core/ModeloBase.php';

class PaqueteTracking extends ModeloBase {

    /**
     * 
     * @return PaqueteTracking
     */
    static function create() {
        return parent::create();
    }

    public function actualizarEstadoXMovimientoBienId($movimientoBienId, $cantidad, $usuarioId) {
        $this->commandPrepare("sp_paquete_tracking_updateXMovimientoBienId");
        $this->commandAddParameter(":vin_movimiento_bien_id", $movimientoBienId);
        $this->commandAddParameter(":vin_cantidad", $cantidad);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioId);
        return $this->commandGetData();
    } 

    public function obtenerPaqueteTranckingSeguimientoXDocumentoId($documentoId) {
        $this->commandPrepare("sp_paquete_tracking_obtenerSeguimientoXPedidoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId); 
        return $this->commandGetData();
    } 

    public function obtenerPaqueteTranckingSeguimientoDetalleXDocumentoId($documentoId) {
        $this->commandPrepare("sp_paquete_tracking_obtenerSeguimientoDetalleXPedidoId");
        $this->commandAddParameter(":vin_documento_id", $documentoId);  
        return $this->commandGetData();
    } 

    public function obtenerPaqueteTranckingSeguimientoDetalleXPaqueteId($paqueteId) {
        $this->commandPrepare("sp_paquete_tracking_obtenerSeguimientoDetalleXPaqueteId");
        $this->commandAddParameter(":vin_paquete_id", $paqueteId);  
        return $this->commandGetData();
    }  
}