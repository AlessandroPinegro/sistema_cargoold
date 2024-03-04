<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

/**
 * Description of Login
 *
 * @author JSC7
 */
class Moneda extends ModeloBase {
    
    /**
     * 
     * @return Moneda
     */

    static function create() {
        return parent::create();
    }
    public function obtenerComboMoneda() {
        $this->commandPrepare("sp_moneda_combo");
        return $this->commandGetData();
    }
    public function obtenerMonedaBase() {
        $this->commandPrepare("sp_moneda_base");
        return $this->commandGetData();
    }
    
    public function obtenerMonedaDistintaBase() {
        $this->commandPrepare("sp_moneda_ObtenerDistintaDeBase");
        return $this->commandGetData();
    }

}
