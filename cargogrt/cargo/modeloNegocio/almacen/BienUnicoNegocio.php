<?php
require_once __DIR__ . '/../../modelo/almacen/BienUnico.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';

class BienUnicoNegocio extends ModeloNegocioBase {

    /**
     *
     * @return BienUnicoNegocio
     */
    static function create() {
        return parent::create();
    }
    
    private function formatearFechaBD($cadena)
    {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }

    public function obtenerConfiguracionesInicialesBienUnico() {
        $respuesta = new ObjectUtil();
        $respuesta->bien = BienNegocio::create()->obtenerActivos(null);
        $respuesta->personaProveedor = PersonaNegocio::create()->obtenerPersonaXOpcionMovimiento(226); //opcion de la guia recepcion
        $respuesta->personaCliente = PersonaNegocio::create()->obtenerPersonaXOpcionMovimiento(199); //opcion de la guia remision
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoPadre();

        return $respuesta;
    }

    public function obtenerDataBienUnicoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin         
        $bienId = $criterios[0]['bien'];
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);

        $proveedorId = $criterios[0]['proveedor'];
        $proveedorIdFormateado = Util::convertirArrayXCadena($proveedorId);

        $clienteId = $criterios[0]['cliente'];
        $clienteIdFormateado = Util::convertirArrayXCadena($clienteId);

        $nroGuia = $criterios[0]['nroGuia'];
        $fechaGuia = $this->formatearFechaBD($criterios[0]['fechaGuia']);
        $prodUnico = $criterios[0]['prodUnico'];

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        
        $estadoBienUnico=$criterios[0]['estadoBienUnico'];

        $data = BienUnico::create()->obtenerDataBienUnicoXCriterios($bienTipoIdFormateado, $bienIdFormateado, $nroGuia, $fechaGuia, $proveedorIdFormateado, $clienteIdFormateado, $prodUnico, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,$estadoBienUnico);

        return $data;
    }

    public function obtenerCantidadDataBienUnicoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
         //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin         
        $bienId = $criterios[0]['bien'];
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);

        $proveedorId = $criterios[0]['proveedor'];
        $proveedorIdFormateado = Util::convertirArrayXCadena($proveedorId);

        $clienteId = $criterios[0]['cliente'];
        $clienteIdFormateado = Util::convertirArrayXCadena($clienteId);

        $nroGuia = $criterios[0]['nroGuia'];
        $fechaGuia = $this->formatearFechaBD($criterios[0]['fechaGuia']);
        $prodUnico = $criterios[0]['prodUnico'];

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        
        $estadoBienUnico=$criterios[0]['estadoBienUnico'];

        $dataCont= BienUnico::create()->obtenerCantidadDataBienUnicoXCriterios($bienTipoIdFormateado, $bienIdFormateado, $nroGuia, $fechaGuia, $proveedorIdFormateado, $clienteIdFormateado, $prodUnico, $columnaOrdenar, $formaOrdenar,$estadoBienUnico);
        
        return $dataCont;
    }
    
    public function obtenerDetalleBienUnico($bienUnicoId){
        return BienUnico::create()->obtenerDetalleBienUnico($bienUnicoId);        
    }
    
    public function obtenerBienUnicoXId($bienUnicoId){
        return BienUnico::create()->obtenerBienUnicoXId($bienUnicoId);
    }
    
    public function obtenerBienUnicoDisponibleXDocumentoId($documentoId){
        return BienUnico::create()->obtenerBienUnicoDisponibleXDocumentoId($documentoId);        
    }
    
    public function obtenerMovimientoBienUnicoXDocumentoId($documentoId){
        return BienUnico::create()->obtenerMovimientoBienUnicoXDocumentoId($documentoId);        
    }
    
    public function obtenerDocumentoDetalleXId($documentoId){
        return DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);        
    }

}
