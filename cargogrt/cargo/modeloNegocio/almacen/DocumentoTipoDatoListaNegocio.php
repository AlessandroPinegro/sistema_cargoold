<?php
require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipoDatoLista.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/SunatTablaNegocio.php';

class DocumentoTipoDatoListaNegocio extends ModeloNegocioBase {
    /**
     * 
     * @return DocumentoTipoDatoListaNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function listar() {
        $data = DocumentoTipoDatoLista::create()->listar();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }
    public function obtenerComboDocumentoTipoDato() {
        return DocumentoTipoDatoLista::create()->obtenerComboDocumentoTipoDato();
    }
    public function insertar($documentoTipoDatoId, $descripcion, $valor, $estado, $usuarioCreacion) {
        $response = DocumentoTipoDatoLista::create()->insertar($documentoTipoDatoId, $descripcion, $valor, $estado, $usuarioCreacion);
        return $response;
    }
    public function obtenerPorId($documentoTipoDatoListaId)
     {
        return DocumentoTipoDatoLista::create()->obtenerPorId($documentoTipoDatoListaId);
     }
     public function actualizar($id, $documentoTipoDatoId, $descripcion, $valor, $estado) {
        $response = DocumentoTipoDatoLista::create()->actualizar($id, $documentoTipoDatoId, $descripcion, $valor, $estado);
        return $response;
    }
    public function eliminar($documentoTipoDatoListaId,$descripcion) {
        $response = DocumentoTipoDatoLista::create()->eliminar($documentoTipoDatoListaId);
        $response[0]['descripcion'] = $descripcion;
        return $response;
    }    
    
    public function obtenerXDocumentoTipoDato($documentoTipoDatoId){
        return DocumentoTipoDatoLista::create()->obtenerXDocumentoTipoDato($documentoTipoDatoId);
    }
    public function cambiarEstado($id_estado)
    {
     $data = DocumentoTipoDatoLista::create()->cambiarEstado($id_estado);
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
        
    public function obtenerDocumentoTipoDatoLista($documentoTipoDatoId) {
        return DocumentoTipoDatoLista::create()->obtenerDocumentoTipoDatoLista($documentoTipoDatoId);
    }
    
    public function obtenerXIds($documentoTipoDatoListaIds){
        return DocumentoTipoDatoLista::create()->obtenerXIds($documentoTipoDatoListaIds);
    }
    
    public function obtenerConfiguracionesIniciales($documentoTipoDatoId){
        $respuesta->documentoTipoDato=  DocumentoTipoDatoNegocio::create()->obtenerXId($documentoTipoDatoId);
        
        $dataValor=null;
        if($respuesta->documentoTipoDato[0]['identificador_negocio']==23){//ES GUIA INTERNA TRANSFERENCIA EN UN SOLO PASO
            $dataValor=  TablaNegocio::create()->obtenerXPadreId(69);
        }
        
        $respuesta->dataValor=$dataValor;
        
        return $respuesta;
        
    }
}
