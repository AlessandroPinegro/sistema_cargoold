<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/../../modeloNegocio/commons/SeguridadNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class SeguridadControler extends ControladorBase {
    
    public function confIniDetPerMap() {
        return SeguridadNegocio::create()->confIniDetPerMap();
    }
}
