<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ListaDinamicaNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../util/ImportacionExcel.php';

class ListaDinamicaControlador extends AlmacenIndexControlador {
    
    public function getDataGridListaDinamica() {
        $usuarioCreacion = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        return ListaDinamicaNegocio::create()->obtenerDocumentoTipoDato($usuarioCreacion,$empresaId);
    }
    
    public function getDataGridDatoLista() {
        $tipoDatoId = $this->getParametro("tipoDatoId");
        return ListaDinamicaNegocio::create()->listarPorTipoDato($tipoDatoId);
    }

}
