<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/CajaChicaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class CajaChicaControlador extends ControladorBase {

    public function listarCajaChica() {
        
        $opcionId = $this->getOpcionId();
        
        //$data=CajaChicaNegocio::create()->listarCajaChica($opcionId);
        return CajaChicaNegocio::create()->listarCajaChica($opcionId);
    }  
    
    public function eliminar() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return CajaChicaNegocio::create()->eliminar($id, $nom);
    }
    
    public function obtenerMoneda() {
        
        $monedaBase= TipoCambioNegocio::create()->obtenerMonedaBase();
        $res=MonedaNegocio::create()->obtenerComboMoneda();
        
        $resultado['baseId']=$monedaBase[0]['id'];
        $resultado['data']=$res;
        return $resultado;
    }       
    
    public function obtenerColaboradores() {
        $data=PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-2); 
        
        return $data;
    }
    
    public function crearCajaChica() {    
        $this->setTransaction();
        /*
    ax.addParamTmp("tipoId", tipoId);
    ax.addParamTmp("importe", importe);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("responsableId", responsableId);*/
        
        $monedaId = $this->getParametro("monedaId");
        $fecha = $this->getParametro("fecha");
        $tipoId = $this->getParametro("tipoId");
        $importe = $this->getParametro("importe");        
        $comentario = $this->getParametro("comentario");          
        $responsableId = $this->getParametro("responsableId");     
        
        $opcionId = $this->getOpcionId();
        $usuCreacion = $this->getUsuarioId();
        
        return CajaChicaNegocio::create()->crearCajaChica($opcionId,$monedaId,$fecha,$tipoId ,$importe,$comentario,$responsableId,$usuCreacion);
    }
}