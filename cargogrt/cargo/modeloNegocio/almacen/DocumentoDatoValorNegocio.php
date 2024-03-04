<?php

require_once __DIR__ . '/../../modelo/almacen/DocumentoDatoValor.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class DocumentoDatoValorNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return DocumentoDatoValorNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXIdDocumento($documentoId) {
        return DocumentoDatoValor::create()->obtenerXIdDocumento($documentoId);
    }

    public function guardar($documentoId, $documentoTipoDatoId, $valorNumero, $valorFecha, $valorCadena, $valorLista, $usuarioCreacion) {
        return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, $valorNumero, $valorFecha, $valorCadena, $valorLista, $usuarioCreacion);
    }

    public function guardarNumero($documentoId, $documentoTipoDatoId, $valor, $usuarioCreacion) {
        return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, $valor, null, null, null, $usuarioCreacion);
    }

    public function guardarFecha($documentoId, $documentoTipoDatoId, $valor, $usuarioCreacion) {
        return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, null, $valor, null, null, $usuarioCreacion);
    }

    public function guardarCadena($documentoId, $documentoTipoDatoId, $valor, $usuarioCreacion) {
        return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, null, null, $valor, null, $usuarioCreacion);
    }

    public function guardarLista($documentoId, $documentoTipoDatoId, $valor, $usuarioCreacion) {
        return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, null, null, null, $valor, $usuarioCreacion);
    }

    public function editarNumero($documentoId, $documentoTipoDatoId, $valor) {
        return DocumentoDatoValor::create()->editar($documentoId, $documentoTipoDatoId, $valor, null, null, null);
    }

    public function editarFecha($documentoId, $documentoTipoDatoId, $valor, $usuarioId) {
        $comprobarCamposDinamicos = DocumentoDatoValor::create()->comprobarExistenCamposDinamicos($documentoId, $documentoTipoDatoId);
        $existenCamposAdicionales = (int) $comprobarCamposDinamicos[0]["existe"];
        if ($existenCamposAdicionales) {
            return DocumentoDatoValor::create()->editar($documentoId, $documentoTipoDatoId, null, $valor, null, null);
        } else {
            return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, null, $valor, null, null, $usuarioId);
        }
    }

    public function editarCadena($documentoId, $documentoTipoDatoId, $valor) {
        return DocumentoDatoValor::create()->editar($documentoId, $documentoTipoDatoId, null, null, $valor, null);
    }

    public function editarLista($documentoId, $documentoTipoDatoId, $valor, $usuarioId) {
        $comprobarCamposDinamicos = DocumentoDatoValor::create()->comprobarExistenCamposDinamicos($documentoId, $documentoTipoDatoId);
        $existenCamposAdicionales = (int) $comprobarCamposDinamicos[0]["existe"];
        if ($existenCamposAdicionales) {
            return DocumentoDatoValor::create()->editar($documentoId, $documentoTipoDatoId, null, null, null, $valor);
        } else {
            return DocumentoDatoValor::create()->guardar($documentoId, $documentoTipoDatoId, null, null, null, $valor, $usuarioId);
        }
    }

}
