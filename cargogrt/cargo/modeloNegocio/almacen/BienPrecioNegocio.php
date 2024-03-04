<?php
require_once __DIR__ . '/../../modelo/almacen/BienPrecio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/MovimientoNegocio.php';
require_once __DIR__ . '/UnidadNegocio.php';

class BienPrecioNegocio extends ModeloNegocioBase {
    const PRECIO_TIPO_COMPRA = 1;
    const PRECIO_TIPO_VENTA = 2;
    const PRECIO_DEL_ULTIMO_MOVIMIENTO = 1;
    const PRECIO_INICIAL_DEL_BIEN = 2;
    
    /**
     * 
     * @return BienPrecioNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerPrecio($bienId, $precioTipoId,$unidadId,$precioIndicador){
        $precio = BienPrecio::create()->obtener($bienId, $precioTipoId,$unidadId,$precioIndicador);
        return $this->obtenerSoloPrecio($precio);
    }
    public function obtenerPrecioCompra($bienId){
        return  $this->obtenerPrecio($bienId, self::PRECIO_TIPO_COMPRA);
    }
    public function obtenerPrecioVenta($bienId){
        return  $this->obtenerPrecio($bienId, self::PRECIO_TIPO_VENTA);
    }
    private function obtenerSoloPrecio($precio){
        if (ObjectUtil::isEmpty($precio)) return 0;
        return $precio[0]["precio"];
    }
    
    public function obtenerPrecioCompraPromedio($bienId, $unidadId,$fechaEmision,$retornar=1){
        //retornar=1 solo retorna precio de compra.
        //retornar=2 retorna precio de compra y cantidad final.
        
        $fechaBD = $this->formatearFechaBD($fechaEmision);        
//        $precio = BienPrecio::create()->obtenerPrecioCompraPromedio($bienId,$unidadId,$fechaBD);
        
        //OBTENER PRECIO COMPRA METODO FIFO
        //obtengo los movimientos de entrada y salida <= fecha de emision
        $dataMovimiento=  MovimientoNegocio::create()->obtenerMovimientoEntradaSalidaXFechaXBienId($fechaBD,$bienId);
        
        if(!ObjectUtil::isEmpty($dataMovimiento)){
            //convirtiendo los datos en la unidad de control
            foreach ($dataMovimiento as $index => $itemMovimiento){
                $conversion=1;
                if($itemMovimiento['unidad_medida_id']!=$itemMovimiento['unidad_control_id']){
                    $dataEquivalencia= UnidadNegocio::create()->obtenerUnidadMedidaEquivalenciaXIds($itemMovimiento['unidad_medida_id'],$itemMovimiento['unidad_control_id']);
                    $conversion=$dataEquivalencia[0]['equivalencia'];

                    $dataMovimiento[$index]['cantidad']=$dataMovimiento[$index]['cantidad']/$conversion;
                    $dataMovimiento[$index]['valor_monetario']=$dataMovimiento[$index]['valor_monetario']*$conversion;                
                }   
            }

            $indice=0;
            foreach ($dataMovimiento as $index => $itemMovimiento){
                if($itemMovimiento['documento_tipo_tipo']==  DocumentoTipoNegocio::TIPO_PROVISION_VENTA){   
                    $cantidadSal=$itemMovimiento['cantidad'];
                    for( $i=$indice; ($i < count($dataMovimiento)) && ($cantidadSal!=0) ; $i++){                    
                        if ($dataMovimiento[$i]['documento_tipo_tipo'] == DocumentoTipoNegocio::TIPO_PROVISION_COMPRA) {
                            $itemE = $dataMovimiento[$i];
                            $cantidadEnt = $itemE['cantidad'];

                            if ($cantidadSal >= $cantidadEnt) {
                                $dataMovimiento[$i]['cantidad'] = 0;
                                $cantidadSal = $cantidadSal - $cantidadEnt;
                                $indice = $i + 1;
                            } else {
                                $dataMovimiento[$i]['cantidad'] = $cantidadEnt - $cantidadSal;
                                $cantidadSal = 0;
                                $indice = $i;
                            }
                        }
                    }                
                }
            }        

            $cantidadTotal=0;
            $precioTotal=0;
            foreach ($dataMovimiento as $index => $itemM){
                if($itemM['documento_tipo_tipo']==  DocumentoTipoNegocio::TIPO_PROVISION_COMPRA
                        && $itemM['cantidad']!=0){
                    $cantidadTotal=$cantidadTotal+$itemM['cantidad'];
                    $precioTotal=$precioTotal+$itemM['cantidad']*$itemM['valor_monetario'];                
                }            
            }
            
            $precioFinal=0;
            if($cantidadTotal!=0){
                $precioFinal=$precioTotal/$cantidadTotal; //precio final en la unidad control
            }else{
                $dataPrecio= $this->obtenerPrecioCompraXBienIdXUnidadMedidaId($bienId,$unidadId,$fechaEmision);
                if(!ObjectUtil::isEmpty($dataPrecio)){
                    $precioFinal=$dataPrecio[0]['precio'];
                }else{
                    $precioFinal=0;
                }
            }           

            //convirtiendo a unidad solicitada
            if($dataMovimiento[0]['unidad_control_id']!=$unidadId){
                $dataEquivalencia= UnidadNegocio::create()->obtenerUnidadMedidaEquivalenciaXIds($dataMovimiento[0]['unidad_control_id'],$unidadId);
                $conversion=$dataEquivalencia[0]['equivalencia'];

                $precioFinal=$precioFinal*$conversion;
            }
        }else{
            $dataPrecio= $this->obtenerPrecioCompraXBienIdXUnidadMedidaId($bienId,$unidadId,$fechaEmision);
            if(!ObjectUtil::isEmpty($dataPrecio)){
                $precioFinal=$dataPrecio[0]['precio'];
            }else{
                $precioFinal=0;
            }
        }
        
        if ($retornar == 1) {
            return $precioFinal;
        }elseif ($retornar==2) {
            if(ObjectUtil::isEmpty($cantidadTotal)){
                $cantidadTotal=0;
            }            
            $resultado->precioCompra=$precioFinal;
            $resultado->cantidadTotal=$cantidadTotal;
            return $resultado;            
        }
//        return $this->obtenerSoloPrecio($precio);
    }
    
    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }
    
    public function obtenerPrecioTipoActivo(){
        $precioTipo = BienPrecio::create()->obtenerPrecioTipoActivo();
        return $precioTipo;
    }
    
    public function obtenerBienPrecioXBienId($bienId){
        $data = BienPrecio::create()->obtenerBienPrecioXBienId($bienId);
        return $data;
    }
    
    public function obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId,$precioTipoId,$monedaId){
        $data = BienPrecio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId,$precioTipoId,$monedaId);
        return $data;
    }
    
    public function obtenerPrecioTipoXMovimientoTipo($movimientoTipoId){
        $precioTipo = BienPrecio::create()->obtenerPrecioTipoXMovimientoTipo($movimientoTipoId);
        return $precioTipo;
    }
    
    public function obtenerBienPrecioXBienIdXMovimientoTipoId($bienId,$unidadMedidaId,$monedaId,$movimientoTipoId){
        $data = BienPrecio::create()->obtenerBienPrecioXBienIdXMovimientoTipoId($bienId,$unidadMedidaId,$monedaId,$movimientoTipoId);
        return $data;
    }
    
    public function obtenerPrecioCompraMovimiento($fechaEmision,$bienId){
        return BienPrecio::create()->obtenerPrecioCompraMovimiento($fechaEmision,$bienId);
    }
    
    public function obtenerPrecioCompraXBienIdXUnidadMedidaId($bienId,$unidadId,$fechaEmision) {
        return BienPrecio::create()->obtenerPrecioCompraXBienIdXUnidadMedidaId($bienId,$unidadId,$fechaEmision);
    }
}
