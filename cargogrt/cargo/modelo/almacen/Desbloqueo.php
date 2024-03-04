<?php

require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";
class Desbloqueo extends ModeloBase {
    /**
     * 
     * @return Desbloqueo
     */
   

    public function getPedidosBloqueados($agenciaId){
        $this->commandPrepare("sp_lista_Pedidos_Bloqueados_Por_Agencia");
        $this->commandAddParameter(":agenciaId",$agenciaId);
         return $this->commandGetData();

    }
     public function getConfirmarDesbloqueo($serie, $correlativo){
        $this->commandPrepare("sp_liberar_claveXComprobante");
        $this->commandAddParameter(":vin_serie",$serie);
        $this->commandAddParameter(":vin_numero",$correlativo);
        return $this->commandGetData();

     }


}