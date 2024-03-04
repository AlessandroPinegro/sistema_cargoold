<?php

require_once __DIR__ . '/../../modelo/contabilidad/VentaTipo.php';
require_once __DIR__ . '/../../modelo/contabilidad/Caracteristica.php';
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

/**
 * Description of VentaTipoNegocio
 *
 * @author Administrador
 */
class VentaTipoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return VentaTipoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarVentaTipo($empresaId) {
        $resultado = VentaTipo::create()->listarVentaTipo($empresaId);
        return $resultado;
    }

    public function obtenerConfiguracionesIniciales($empresaId) {
        $resultado->caracteristicas = Caracteristica::create()->obtenerCaracteristicasXTipo(3);
        $resultado->caracteristicasOtros = Caracteristica::create()->obtenerCaracteristicasXTipo(4);
        $resultado->documentostipos = DocumentoTipo::create()->listarDocumentoTipo($empresaId);
        return $resultado;
    }

    public function obtenerVentaTipoXid($id) {
        $resultado->dataVentaTipo = VentaTipo::create()->obtenerVentaTipoXid($id);
        $resultado->dataVentaTipoCaracteristicas = VentaTipo::create()->obtenerVentaTipoCaracteristicaXTipoVentaIdXTipo($id, 3);
        $resultado->dataVentaTipoCaracteristicasOtros = VentaTipo::create()->obtenerVentaTipoCaracteristicaXTipoVentaIdXTipo($id, 4);
        $resultado->dataVentaTipoDocumentos = VentaTipo::create()->obtenerVentaTipoDocumentoXVentaTipoId($id);
        //$resultado->dataMotivoMovimientoCaracteristicas = MotivoMovimiento::create()->obtenerMotivoCaracteristicaXMotivoId($id);
        //$resultado->dataMotivoMovimientoDocumentos = MotivoMovimiento::create()->obtenerMotivoDocumentoXMotivoId($id);
        return $resultado;
    }

    public function guardarCaracteristicas($caracteristica, $ventaTipoId, $usuarioCreacion) {
        if (!ObjectUtil::isEmpty($ventaTipoId)) {
            VentaTipo::create()->eliminarVentaTipoCaracteristicaXVentaTipoId($ventaTipoId);
            if (is_array($caracteristica)) {
                foreach ($caracteristica as $caracteristicaId) {
                    $res = VentaTipo::create()->guardarVentaTipoCaracteristica($caracteristicaId, $ventaTipoId, $usuarioCreacion);
                }
            }
        }
    }
    
    public function guardarDocumentos($documento, $ventaTipoId, $usuarioCreacion)
    {
        if(!ObjectUtil::isEmpty($ventaTipoId))
        {
            VentaTipo::create()->eliminarVentaTipoDocumentoXVentaTipoId($ventaTipoId);
            if(is_array($documento))
            {
                foreach($documento as $documentoId)
                {
                    $res = VentaTipo::create()->guardarVentaTipoDocumento($documentoId, $ventaTipoId, $usuarioCreacion);
                }
            }
        }
    }
    
    public function guardarVentaTipo($empresaId, $codigo, $descripcion, $codigoExportacion, $notaCredito, $valorVentaInafecto, $estado, $usuarioId, $ventaTipoId, $caracteristicasSeleccionadas, $documentosSeleccionados)
    {
        $resVentaTipo = VentaTipo::create()->guardarVentaTipo($empresaId, $codigo, $descripcion, $codigoExportacion, $notaCredito, $valorVentaInafecto, $estado, $usuarioId, $ventaTipoId);
        
        if ($resVentaTipo[0]['vout_exito'] == 1) {
            $this->guardarCaracteristicas($caracteristicasSeleccionadas, $resVentaTipo[0]['id'], $usuarioId);
            $this->guardarDocumentos($documentosSeleccionados, $resVentaTipo[0]['id'], $usuarioId);
        }

        $respuesta->resultado = $resVentaTipo;
        return $respuesta;
        
    }
    
    public function cambiarEstado($id)
    {
        return VentaTipo::create()->cambiarEstado($id);
    }
    
    public function eliminar($id, $nom)
    {
        $resultado = VentaTipo::create()->eliminar($id);
        $resultado[0]['descripcion'] = $nom;
        
        return $resultado;
    }
}
