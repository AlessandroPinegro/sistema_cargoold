<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

class DocumentoDatoValor extends ModeloBase {

    static function create() {
        return parent::create();
    }

    public function comprobarExistenCamposDinamicos($documentoId, $documentoTipoDatoId) {
        $this->commandPrepare("sp_comprobar_existen_campos_dinamicos");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        return $this->commandGetData();
    }

    public function obtenerXIdDocumento($documentoId) {
        $this->commandPrepare("sp_documento_dato_valor_obtenerXDocumento");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        return $this->commandGetData();
    }

    public function guardar($documentoId, $documentoTipoDatoId, $valorNumero, $valorFecha, $valorCadena, $valorLista, $usuarioCreacion) {
        $this->commandPrepare("sp_documento_dato_valor_guardar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        $this->commandAddParameter(":vin_valor_numero", $valorNumero);
        $this->commandAddParameter(":vin_valor_fecha", $valorFecha);
        $this->commandAddParameter(":vin_valor_cadena", $valorCadena);
        $this->commandAddParameter(":vin_valor_lista", $valorLista);
        $this->commandAddParameter(":vin_usuario_creacion", $usuarioCreacion);
        return $this->commandGetData();
    }

    public function editar($documentoId, $documentoTipoDatoId, $valorNumero, $valorFecha, $valorCadena, $valorLista) {
        $this->commandPrepare("sp_documento_dato_valor_editar");
        $this->commandAddParameter(":vin_documento_id", $documentoId);
        $this->commandAddParameter(":vin_documento_tipo_dato_id", $documentoTipoDatoId);
        $this->commandAddParameter(":vin_valor_numero", $valorNumero);
        $this->commandAddParameter(":vin_valor_fecha", $valorFecha);
        $this->commandAddParameter(":vin_valor_cadena", $valorCadena);
        $this->commandAddParameter(":vin_valor_lista", $valorLista);
        return $this->commandGetData();
    }

}
