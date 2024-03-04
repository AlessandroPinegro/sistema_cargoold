<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../modelo/almacen/Desbloqueo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';

 class DesbloqueoNegocio extends ModeloNegocioBase{
    /**
     * 
     * @return DesbloqueoNegocio
     */

    public function getPedidosBloqueados( $agencia) {
        $data = new stdClass();
        $data->desbloqueo = Desbloqueo::create()->getPedidosBloqueados($agencia);
        return $data;
    }
     public function connfirmarDesbloqueo($serie, $correlativo){
        $data = new stdClass();
        $data->confirmar = Desbloqueo::create()->getConfirmarDesbloqueo($serie, $correlativo);
         return $data;

     }
    
 }
