<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/EarNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class EarControlador extends ControladorBase {

    public function pruebaDeControlador($tipoCobranzaPago, $empresaId) {        
        $data = PagoNegocio::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
        return $data;
    }
    
    public function registrarDocumentoDesembolso($parametros) {
        try {
            $this->setTransaction();
            $documentoId = EarNegocio::create()->registrarDocumentoDesembolso($parametros);  
            
            $respuesta->status=1;
            $respuesta->documentoId=$documentoId;
            
            $this->setCommitTransaction();            
            return $respuesta;
        } catch (Exception $ex) {
            $respuesta->status=0;
            $respuesta->mensaje=$ex->getMessage();
            return $respuesta;
        }
    }
    
    public function registrarLiquidacionVistoBueno($parametros) {
        try {
            $this->setTransaction();
            $data = EarNegocio::create()->registrarLiquidacionVistoBueno($parametros);  
            
            $respuesta->status=1;
            $respuesta->data=$data;
            
            $this->setCommitTransaction();            
            return $respuesta;
        } catch (Exception $ex) {
            $respuesta->status=0;
            $respuesta->mensaje=$ex->getMessage();
            return $respuesta;
        }
    }

}