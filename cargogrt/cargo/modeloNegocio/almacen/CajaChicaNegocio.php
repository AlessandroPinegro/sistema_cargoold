<?php

require_once __DIR__ . '/../../modelo/almacen/CajaChica.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';

class CajaChicaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return CajaChicaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarCajaChica($opcionId) {
        $documentoTipoId = $this->obtenerDocumentoTipoIdXOpcion($opcionId);
        
        return CajaChica::create()->listarCajaChica($documentoTipoId);
    }
    
    
    public function obtenerDocumentoTipoIdXOpcion($opcionId) {
        $documentoTipo = DocumentoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($documentoTipo)) {
            throw new WarningException("No se encontró el documento asociado a esta opción");
        }
        return $documentoTipo[0]["id"];
    }
    
    public function eliminar($id, $nom) {
        $response = CajaChica::create()->eliminar($id);
        $response[0]['fecha'] = $nom;
        return $response;
    }        
    
    public function crearCajaChica($opcionId,$monedaId,$fecha,$tipoId ,$importe,$comentario,$responsableId,$usuCreacion){
        
        $documentoTipoId = $this->obtenerDocumentoTipoIdXOpcion($opcionId);
        
        $fechaBD = $this->formatearFechaBD($fecha);

        $res = CajaChica::create()->insertarCajaChica($documentoTipoId,$monedaId,$fechaBD,$tipoId ,$importe,$comentario,$responsableId,$usuCreacion);        
        
        return $res;
    }    
    
    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }
}