<?php

require_once __DIR__ . '/../../modelo/contabilidad/MotivoMovimiento.php';
require_once __DIR__ . '/../../modelo/contabilidad/Caracteristica.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/SunatTabla.php';
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

/**
 * Description of MotivoMovimientoNegocio
 *
 * @author Administrador
 */
class MotivoMovimientoNegocio extends ModeloNegocioBase {
    //put your code here

    /**
     * 
     * @return MotivoMovimientoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarMotivosMovimiento($empresaID) {
        return MotivoMovimiento::create()->listarMotivosMovimiento($empresaID);
    }

    public function obtenerConfiguracionesIniciales($empresaId) {
        $resultado->caracteristicas = Caracteristica::create()->obtenerCaracteristicasXTipo(1);
        $resultado->documentostipos = DocumentoTipo::create()->listarDocumentoTipo($empresaId);
        $resultado->dataTipoCambio = Tabla::create()->obtenerXPadreId(1);
        $resultado->dataTipoMotivo = Tabla::create()->obtenerXPadreId(41);
        $resultado->dataGrupo = Tabla::create()->obtenerXPadreId(53);
        $resultado->dataTipoCalculo = Tabla::create()->obtenerXPadreId(46);

        $resultado->dataSunatDetalle = SunatTabla::create()->obtenerDetalleXSunatTablaId(12);
        return $resultado;
    }

    public function guardarMotivoMovimiento($codigo,$descripcion, $nombreCorto, $tipoMotivoId, $tipoCalculoId, $tipoCambioId, $grupoId, $codigoSunatId, $estadoId, $caracteristicasSeleccionadas, $usuarioId, $empresaId, $motivoId, $documentosSeleccionados) {

        $resMotivoMovimiento = MotivoMovimiento::create()->guardarMotivoMovimiento($codigo, $descripcion, $nombreCorto, $tipoMotivoId, $tipoCalculoId, $tipoCambioId, $grupoId, $codigoSunatId, $estadoId, $usuarioId, $empresaId, $motivoId);

        if ($resMotivoMovimiento[0]['vout_exito'] == 1) {
            $this->guardarCaracteristicas($caracteristicasSeleccionadas, $resMotivoMovimiento[0]['id'], $usuarioId);
            $this->guardarDocumentos($documentosSeleccionados, $resMotivoMovimiento[0]['id'], $usuarioId);
        }

        $respuesta->resultado = $resMotivoMovimiento;
        return $respuesta;
    }

    public function guardarCaracteristicas($caracteristica, $motivoID, $usuarioCreacion) {
        if (!ObjectUtil::isEmpty($motivoID)) {
            MotivoMovimiento::create()->eliminarMotivosCaracteristicaXMotivoId($motivoID);
            if (is_array($caracteristica)) {
                foreach ($caracteristica as $caracteristicaId) {
                    $res = MotivoMovimiento::create()->guardarMotivoCaracteristica($caracteristicaId, $motivoID, $usuarioCreacion);
                }
            }
        }
    }
    
    public function guardarDocumentos($caracteristica, $motivoId, $usuarioCreacion)
    {
        if(!ObjectUtil::isEmpty($motivoId))
        {
            MotivoMovimiento::create()->eliminarMotivoDocumentoXMotivoId($motivoId);
            if(is_array($caracteristica))
            {
                foreach ($caracteristica as $caracteristicaId)
                {
                    $res = MotivoMovimiento::create()->guardarMotivoDocumento($caracteristicaId, $motivoId, $usuarioCreacion);
                }
            }
        }
    }

    public function obtenerMotivoMovimientoXid($id) {

        $resultado->dataMotivoMovimiento = MotivoMovimiento::create()->obtenerMotivoMovimientoXid($id);
        $resultado->dataMotivoMovimientoCaracteristicas = MotivoMovimiento::create()->obtenerMotivoCaracteristicaXMotivoId($id);
        $resultado->dataMotivoMovimientoDocumentos = MotivoMovimiento::create()->obtenerMotivoDocumentoXMotivoId($id);
        return $resultado;
    }
    
    public function cambiarEstado($id)
    {
        return MotivoMovimiento::create()->cambiarEstado($id);
    }
    
    public function eliminar($id, $nom)
    {
        $resultado = MotivoMovimiento::create()->eliminar($id);
        $resultado[0]['descripcion'] = $nom;
        
        return $resultado;
    }

}
