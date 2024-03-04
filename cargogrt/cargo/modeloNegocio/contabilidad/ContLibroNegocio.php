<?php

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modelo/contabilidad/ContLibro.php';

class ContLibroNegocio extends ModeloNegocioBase {

    const CLASIFICACION_LIBRO_DIARIO = 1;
    const CLASIFICACION_COMPRAS = 2;
    const CLASIFICACION_VENTAS = 3;
    const CLASIFICACION_CAJAYBANCOS = 4;
    
    const LIBRO_EXTRANJERO_ID = "1";
    const LIBRO_RH_ID = "4";
    const LIBRO_LIBRO_DIARIO_COMPRAS = "6";
    const LIBRO_ARRENDAMIENTO_ID = "21";
    const LIBRO_INVENTARIO_ID = "20";
    /**
     * 
     * @return ContLibroNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerXClasificacion($clasificacion = NULL) {
        return ContLibro::create()->obtenerXClasificacion($clasificacion);
    }

}
