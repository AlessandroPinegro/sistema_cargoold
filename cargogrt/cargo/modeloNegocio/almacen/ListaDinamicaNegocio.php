<?php

session_start();
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipoDato.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

//require_once __DIR__ . '/../../modeloNegocio/almacen/barcode.inc.php';

class ListaDinamicaNegocio extends ModeloNegocioBase {

    const PRECIO_COMPRA = 1;
    const PRECIO_VENTA = 2;
    const PARAMETRO_DESCUENTO = 0.36; // En realidad el  descuento es de 64 %
    const PARAMETRO_IGV = 1.18;

    /**
     * 
     * @return ListaDinamicaNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerDocumentoTipoDato($usuarioId,$empresaId) {

        return DocumentoTipoDato::create()->obtenerDocumentoTipo('4');
    }
    
    public function listarPorTipoDato($tipoDatoId) {

        return DocumentoTipoDatoLista::create()->listarPorTipoDato($tipoDatoId);
    }
}
