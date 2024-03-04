<?php

require_once __DIR__ . '/../../modelo/contabilidad/Subdiarios.php';
require_once __DIR__ . '/../../modelo/contabilidad/Caracteristica.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/SunatTabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/Sucursal.php';
require_once __DIR__ . '/../../modelo/contabilidad/Periodo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class SubdiariosNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return SubdiariosNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerConfiguracionesIniciales($empresaId){
        $resultado->caracteristicas=Caracteristica::create()->obtenerCaracteristicasXTipo(2);
        $resultado->dataTipoCambio=  Tabla::create()->obtenerXPadreId(1);
        $resultado->dataTipoAsientos=  Tabla::create()->obtenerXPadreId(4);
        $resultado->dataSunatDetalle= SunatTabla::create()->obtenerDetalleXSunatTablaId(12);
        $resultado->dataSucursal= Sucursal::create()->obtenerXEmpresaId($empresaId);
        return $resultado;
    }
    
    public function guardarSubdiario($codigo,$descripcion,$tipoCambioId,$codigoSunatId,$estadoId,
                $tipoAsientoId,$sucursalId,$caracteristicasSeleccionadas,$usuarioId,$empresaId,$subdiarioId){
        
        $resSubdiario=  Subdiarios::create()->guardarSubdiario($codigo,$descripcion,$tipoCambioId,$codigoSunatId,
                $estadoId,$tipoAsientoId,$sucursalId,$usuarioId,$empresaId,$subdiarioId);
        
        if($resSubdiario[0]['vout_exito'] == 1){
            $this->guardarCaracteristicas($caracteristicasSeleccionadas, $resSubdiario[0]['id'], $usuarioId);
            $this->guardarSubdiarioNumeracion($sucursalId, $resSubdiario[0]['id'], $usuarioId);
        }     
                
        $respuesta->resultado=$resSubdiario;
        return $respuesta;
    }
    
    public function guardarCaracteristicas($caracteristica, $subdiarioID, $usuarioCreacion) {
        if (!ObjectUtil::isEmpty($subdiarioID)) {
            Subdiarios::create()->eliminarSubdiariosCaracteristicaXSubdiarioId($subdiarioID);
            if (is_array($caracteristica)) {
                foreach ($caracteristica as $caracteristicaId) {
                    $res= Subdiarios::create()->guardarSubdiariosCaracteristica($caracteristicaId, $subdiarioID, $usuarioCreacion);
                }
            }
        }
    }
    
    public function guardarSubdiarioNumeracion($sucursalId, $subdiarioID, $usuarioId){
        $dataPeriodo=  Periodo::create()->obtenerPeridoParaNumeracion();
        
        foreach ($dataPeriodo as $item) {
            $res = Subdiarios::create()->guardarSubdiarioNumeracion($sucursalId, $subdiarioID,$item['id'], $usuarioId);
        }
    }
    
    public function listarSubdiarios($empresaId){
        return Subdiarios::create()->listarSubdiarios($empresaId);        
    }
    
    public function cambiarEstado($id)  {
        return Subdiarios::create()->cambiarEstado($id);
    }    
    
    public function eliminar($id, $nom) {
        $response = Subdiarios::create()->eliminar($id);
        $response[0]['descripcion'] = $nom;
        return $response;
    }        
    
    public function obtenerSubdiarioXid($id){
        $resultado->dataSubdiario=Subdiarios::create()->obtenerSubdiarioXid($id);        
        $resultado->dataSubdiarioCaracteristica=Subdiarios::create()->obtenerSubdiarioCaracteristicaXSubdiarioId($id);        
        
        return $resultado;
    }
    
    public function obtenerSubdiarioNumeracionXid($id)
    {
        return Subdiarios::create()->obtenerSubdiarioNumeracionXid($id);       
    }
}