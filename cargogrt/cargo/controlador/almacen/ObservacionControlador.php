<?php
require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ObservacionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PedidoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
class ObservacionControlador extends AlmacenIndexControlador{
public function obtenerConfiguracionesInicialesObservaciones(){
        $empresaId = $this->getParametro("empresaId");
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $data = new stdClass();
        $data -> dataObservacion = ObservacionNegocio::create()->getPedidosObservados(null, null);
        $data ->configuraciones =  MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiestoReparto($opcionId, $empresaId, $usuarioId);
       return $data;
         
} 
public function  obtenerDataObservaciones(){
         return ObservacionNegocio::create()->getPedidosObservados();
       }
public function  obtenerDataPorFiltros(){
        $fInicio = $this->getParametro("fInicio");
        $fFinal =$this->getParametro("fFinal");
         return ObservacionNegocio::create()->getPedidosObservados($fInicio, $fFinal );
       }

 public function exportarDataExcel(){
       $fInicio = $this->getParametro("fInicio");
        $fFinal =$this->getParametro("fFinal");
         return ObservacionNegocio::create()->getPedidosObservadosExportarExcel($fInicio, $fFinal );
 }
 public function reversarPedido(){
        $documento_id = $this->getParametro("documento_id");
         return ObservacionNegocio:: create()->reversarPedido($documento_id);
 }
 public function mostrarDetalleDocumentoObservacion() {
        $documento_id = $this->getParametro("documento_id");
        return ObservacionNegocio:: create()->getMostrarDetalle($documento_id);
 }
public function obtenerDocumentoRelacionVisualizar() {
       $documentoId = $this->getParametro("documentoId");
       $movimientoId = $this->getParametro("movimientoId");
       $data = PedidoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
       $data->emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);
       return $data;   
}
 public function enviarManciche (){
       $usuarioId = $this->getUsuarioId();
       $valoresSeleccionados = $this->getParametro("valoresSeleccionados");
        return ObservacionNegocio:: create()-> getPedidosEnviarManciche($valoresSeleccionados, $usuarioId);
 }
}
 