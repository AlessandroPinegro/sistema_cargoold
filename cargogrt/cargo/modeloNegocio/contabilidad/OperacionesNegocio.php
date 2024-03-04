<?php

require_once __DIR__ . '/../../modelo/contabilidad/Operaciones.php';
require_once __DIR__ . '/../../modelo/contabilidad/Caracteristica.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/SunatTabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/Sucursal.php';
require_once __DIR__ . '/../../modelo/contabilidad/Periodo.php';
require_once __DIR__ . '/../../modelo/contabilidad/Subdiarios.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class OperacionesNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return OperacionesNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerConfiguracionesIniciales($empresaId){
        $resultado->dataTipoCambio=  Tabla::create()->obtenerXPadreId(1);
        $resultado->dataSubdiario= Subdiarios::create()->listarSubdiarios($empresaId);
        $resultado->dataSunatDetalle= SunatTabla::create()->obtenerDetalleXSunatTablaId(12);
        $resultado->dataSucursal= Sucursal::create()->obtenerXEmpresaId($empresaId);
        return $resultado;
    }
    
    public function guardarOperacion($codigo,$descripcion,$tipoCambioId,$codigoSunatId,$estadoId,
                $subdiarioId,$sucursalId,$chkEgresoBanco,$usuarioId,$empresaId,$operacionId){
        
        $resOperacion=  Operaciones::create()->guardarOperacion($codigo,$descripcion,$tipoCambioId,$codigoSunatId,
                $estadoId,$subdiarioId,$sucursalId,$usuarioId,$empresaId,$operacionId,$chkEgresoBanco);
        
        if($resOperacion[0]['vout_exito'] == 1){
            $this->guardarOperacionNumeracion($sucursalId, $resOperacion[0]['id'], $usuarioId);
        }
                
        $respuesta->resultado=$resOperacion;
        return $respuesta;
    }
        
    public function guardarOperacionNumeracion($sucursalId, $operacionID, $usuarioId){
        $dataPeriodo=  Periodo::create()->obtenerPeridoParaNumeracion();
        
        foreach ($dataPeriodo as $item) {
            $res = Operaciones::create()->guardarOperacionNumeracion($sucursalId, $operacionID,$item['id'], $usuarioId);
        }
    }
    
    public function listarOperaciones($empresaId){
        return Operaciones::create()->listarOperaciones($empresaId);        
    }
    
    public function cambiarEstado($id)  {
        return Operaciones::create()->cambiarEstado($id);
    }    
    
    public function eliminar($id, $nom) {
        $response = Operaciones::create()->eliminar($id);
        $response[0]['descripcion'] = $nom;
        return $response;
    }        
    
    public function obtenerOperacionXid($id){
        $resultado->dataOperacion=Operaciones::create()->obtenerOperacionXid($id);     
        
        return $resultado;
    }
    
    public function obtenerOperacionNumeracionXid($id)
    {
        return Operaciones::Create()->obtenerOperacionNumeracionXid($id);
    }
}