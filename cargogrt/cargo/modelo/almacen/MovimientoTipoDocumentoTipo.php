<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Movimiento
 *
 * @author Christopher Heredia
 */
class MovimientoTipoDocumentoTipo extends ModeloBase {
    /**
     * 
     * @return MovimientoTipoDocumentoTipo
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXMovimiento($movimientoId, $documentoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_documento_tipo_obtenerXMovimiento");
        $this->commandAddParameter(":vin_movimiento_id", $movimientoId);
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
    
    public function obtenerXDocumentoTipo($documentoTipoId)
    {
        $this->commandPrepare("sp_movimiento_tipo_documento_tipo_obtenerXDocumentoTipo");
        $this->commandAddParameter(":vin_documento_tipo_id", $documentoTipoId);
        return $this->commandGetData();
    }
}
