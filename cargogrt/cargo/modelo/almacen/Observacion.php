<?php
require_once __DIR__ . '/../core/ModeloBase.php';
//require_once __DIR__.'../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";
class Observacion extends ModeloBase {
    /**
     * 
     * @return Desbloqueo
     */  
    
      public function obtenerPedidosEnviadosManciche($numPedidos, $usuarioId){
        $this->commandPrepare("sp_actualizar_pedido_mansiche"); 
        $this->commandAddParameter(":documentoId",$numPedidos);
        $this->commandAddParameter(":usuarioMansiche",$usuarioId);
        return $this->commandGetData();
      }
    public function getPedidosObservados( $fInicio= null, $fFinal= null){
        $this->commandPrepare("sp_GetDataObservacion");
        $this->commandAddParameter(":fecha_inicio",$fInicio);
        $this->commandAddParameter(":fecha_fin",$fFinal);
         return $this->commandGetData();
    }
    public function obtenerPedidosObservados(){
        $this->commandPrepare("sp_getAllObservacionEstado");
         return $this->commandGetData();
    }
    public function cambiarEstadoDocumentoObservacion ($id_documento, $estado_observacion_id, $observacion, $usuarioId ){
        $this->commandPrepare("sp_insert_documento_estado_observacion");
        $this->commandAddParameter(":estado",$estado_observacion_id);
        $this->commandAddParameter(":descripcion",$observacion);
        $this->commandAddParameter(":documento_id",$id_documento);
        $this->commandAddParameter(":usuario_creacion",$usuarioId);
         return $this->commandGetData();
    }

     public function getReversarPedidoObservado($documento_id){
        $this->commandPrepare("sp_eliminar_observacion_documento");
        $this->commandAddParameter(":documento_id",$documento_id);
         return $this->commandGetData();
     }

  public function    ObtenerDetalleDocumentoObservado($documento_id){
    $this->commandPrepare("sp_getDetalleObservacionPedido");
    $this->commandAddParameter(":documento_id",$documento_id);
     return $this->commandGetData();
  }
}