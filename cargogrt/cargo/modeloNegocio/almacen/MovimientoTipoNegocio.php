<?php

  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
require_once __DIR__ . '/../../modelo/almacen/MovimientoTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/BienPrecioNegocio.php';

class MovimientoTipoNegocio extends ModeloNegocioBase {
    const INDICADOR_ENTRADA = 1;
    const INDICADOR_SALIDA = 2;
    const INDICADOR_TRANSFERENCIA = 3;
    const INDICADOR_COMODIN = 4;
    const TRANSFERENCIA_TIPO_SALIDA=1;
    
    /**
     * 
     * @return MovimientoTipoNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function getDataMovimientoTipo($id_bandera) {
        $data = MovimientoTipo::create()->getDataMovimientoTipo();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
            if( $data[$i]['indicador']=='1')
            {
                $data[$i]['indicador_descripcion']='Ingreso';
            }else
            {
                if( $data[$i]['indicador']=='2')
                {
                    $data[$i]['indicador_descripcion']='Salida';
                }else
                {
                    $data[$i]['indicador_descripcion']='Transferencia';
                }
            }
            
        }
        return $data;
    }

    public function insertMovimientoTipo($codigo,$indicador, $descripcion, $comentario, $estado, $usuarioCreacion) {

        $response = MovimientoTipo::create()->insertMovimientoTipo($codigo,$indicador, $descripcion, $comentario, $estado, $usuarioCreacion);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            return $response;
        }
    }

    public function getMovimientoTipo($id) {
        return MovimientoTipo::create()->getMovimientoTipo($id);
    }

    public function updateMovimientoTipo($id,$indicador,$codigo, $descripcion,$comentario, $estado) {
        $response = MovimientoTipo::create()->updateMovimientoTipo($id,$indicador,$codigo, $descripcion,$comentario, $estado);
        if ($response[0]["vout_exito"] == 0) {
            return $response[0]["vout_mensaje"];
        } else {
            return $response;
        }
    }

    public function deleteMovimientoTipo($id, $nom) {
        $response = MovimientoTipo::create()->deleteMovimientoTipo($id);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarMovimientoTipoEstado($id_estado) {
        $data = MovimientoTipo::create()->cambiarMovimientoTipoEstado($id_estado);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_nuevo'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }
    
    public function obtenerXOpcion($opcionId){
        return MovimientoTipo::create()->obtenerXOpcion($opcionId);
    }
    
    public function obtenerPrecio($bienId, $movimientoTipo,$unidadId){
        if (ObjectUtil::isEmpty($movimientoTipo)) return 0;
        if (ObjectUtil::isEmpty($movimientoTipo[0]["precio_tipo_id"])){
            if ($movimientoTipo[0]["indicador"] == self::INDICADOR_ENTRADA || $movimientoTipo[0]["indicador"] == self::INDICADOR_COMODIN){
                return BienPrecioNegocio::create()->obtenerPrecioCompra($bienId);
            }elseIf ($movimientoTipo[0]["indicador"] == self::INDICADOR_SALIDA) {
                return BienPrecioNegocio::create()->obtenerPrecioVenta($bienId);
            }
        }else{
            return BienPrecioNegocio::create()->obtenerPrecio($bienId, $movimientoTipo[0]["precio_tipo_id"],$unidadId, $movimientoTipo[0]["precio_indicador"]);
        }
        return 0;
    }           
    
    public function obtenerXDocumentoTipoId($documentoTipoId){
        return MovimientoTipo::create()->obtenerXDocumentoTipoId($documentoTipoId);
    }
    
    public function obtenerMovimientoTipoColumna($movimientoTipoId) {
        return MovimientoTipo::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
    }
    
    public function obtenerXDocumentoId($documentoId){
        return MovimientoTipo::create()->obtenerXDocumentoId($documentoId);
        
    }
    
    public function obtenerMovimientoTipoAccionesVisualizacion($movimientoTipoId){
        return MovimientoTipo::create()->obtenerMovimientoTipoAccionesVisualizacion($movimientoTipoId);        
    }
    
    
    public function obtenerDocumentoEstadoXId($movimientoTipoId){
        return MovimientoTipo::create()->obtenerDocumentoEstadoXId($movimientoTipoId);        
    }
    
}
