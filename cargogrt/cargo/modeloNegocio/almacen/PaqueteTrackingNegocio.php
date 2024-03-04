<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/almacen/PaqueteTracking.php';
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
require_once __DIR__ . '/../../modelo/almacen/Caja.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/../../modelo/almacen/Persona.php';

class PaqueteTrackingNegocio extends ModeloNegocioBase
{

    /**
     * 
     * @return PaqueteTrackingNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerTranckingXPedido($documentoId, $tipo)
    {
        $dataPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $dataDetalle = self::obtenerPaqueteTranckingSeguimientoDetalleXDocumentoId($documentoId);
        $dataRespuesta = array();
        $dataRespuesta[] = array('tipo' => '3', 'descripcion' => 'REGISTRADO', 'fecha' => '', 'icono' => 'registrado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '2', 'descripcion' => 'DESPACHADO', 'fecha' => '', 'icono' => 'despachado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '1', 'descripcion' => 'EN TRÁNSITO', 'fecha' => '', 'icono' => 'en_transito.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '4', 'descripcion' => 'RECEPCIONADO', 'fecha' => '', 'icono' => 'recepcionado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        if ($dataPedido[0]['modalidad_id'] == 76 || $dataPedido[0]['modalidad_id'] == 82) {
            $dataRespuesta[] = array('tipo' => '5', 'descripcion' => 'REPARTO A DOMICILIO', 'fecha' => '', 'icono' => 'en_reparto.png', 'cantidad' => '', 'data' => array());
            $dataRespuesta[] = array('tipo' => '7', 'descripcion' => 'RETORNO A AGENCIA', 'fecha' => '', 'icono' => 'deregreso.png', 'cantidad' => '', 'data' => array());
        }
        $dataRespuesta[] = array('tipo' => '6', 'descripcion' => 'ENTREGADO', 'fecha' => '', 'icono' => 'entregado.png', 'data' => array());

        $banderaAnteriorContieneData = TRUE;
        $indexDelete = NULL;
        foreach ($dataRespuesta as $index => $item) {
            $itemDetalle = ObjectUtil::filtrarArrayPorColumna($dataDetalle, 'tipo', $item['tipo']);
            if (!ObjectUtil::isEmpty($itemDetalle) && $banderaAnteriorContieneData) {
                foreach ($itemDetalle as $indice => $itemData) {
                    $itemDetalle[$indice]['flujo'] = $item['descripcion'];
                }
                $dataRespuesta[$index]['data'] = $itemDetalle;
                $cantidadMaxima = $itemDetalle[0]['cantidad_total'];

                $dataArrayFecha = array_unique(array_column($itemDetalle, 'fecha_creacion'));
                $dataArrayPaquete = array_unique(array_column($itemDetalle, 'paquete_id'));
                usort($dataArrayFecha, function ($a, $b) {
                    $dateTimestamp1 = strtotime($a);
                    $dateTimestamp2 = strtotime($b);

                    return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
                });

                $fechaMaxima = $dataArrayFecha[count($dataArrayFecha) - 1];
                $dataRespuesta[$index]['fecha'] = $fechaMaxima;
                $dataRespuesta[$index]['cantidad'] = (count($dataArrayPaquete) . '/' . $cantidadMaxima);
                $dataRespuesta[$index]['cantidad_paquete'] = count($dataArrayPaquete);
                $dataRespuesta[$index]['cantidad_total'] = $cantidadMaxima;
                if ($item['tipo'] != 7) {
                    $banderaAnteriorContieneData = TRUE;
                }
                if ($item['tipo'] == 7 && strtotime($dataRespuesta[$index - 1]['fecha']) > strtotime($fechaMaxima)) {
                    $indexDelete = $index;
                }
            } else {
                if ($item['tipo'] == 7) {
                    $indexDelete = $index;
                } else {
                    $banderaAnteriorContieneData = FALSE;
                }
            }

            if ($tipo == 2 || $tipo == 3) {
                $dataRespuesta[$index]['data'] = array();
            }
        }

        if (!ObjectUtil::isEmpty($indexDelete)) {
            array_splice($dataRespuesta, $indexDelete, 1);
        }

        $respuesta = new stdClass();
        $respuesta->dataPedido = $dataPedido;
        $respuesta->dataDetalle = $dataRespuesta;
        $respuesta->tipo = $tipo;
        return $respuesta;
    }

    public function obtenerTranckingXPaquete($paqueteId, $tipo)
    {
        $dataPedido = DocumentoNegocio::create()->obtenerDocumentoXPaqueteId($paqueteId);
        $dataDetalle = self::obtenerPaqueteTranckingSeguimientoDetalleXPaqueteId($paqueteId);
        $dataRespuesta = array();
        $dataRespuesta[] = array('tipo' => '3', 'descripcion' => 'REGISTRADO', 'fecha' => '', 'icono' => 'registrado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '2', 'descripcion' => 'DESPACHADO', 'fecha' => '', 'icono' => 'despachado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '1', 'descripcion' => 'EN TRÁNSITO', 'fecha' => '', 'icono' => 'en_transito.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        $dataRespuesta[] = array('tipo' => '4', 'descripcion' => 'RECEPCIONADO', 'fecha' => '', 'icono' => 'recepcionado.png', 'cantidad_paquete' => 0, 'cantidad_total' => 0, 'cantidad' => '', 'data' => array());
        if ($dataPedido[0]['modalidad_id'] == 76 || $dataPedido[0]['modalidad_id'] == 82) {
            $dataRespuesta[] = array('tipo' => '5', 'descripcion' => 'REPARTO A DOMICILIO', 'fecha' => '', 'icono' => 'en_reparto.png', 'cantidad' => '', 'data' => array());
            $dataRespuesta[] = array('tipo' => '7', 'descripcion' => 'RETORNO A AGENCIA', 'fecha' => '', 'icono' => 'deregreso.png', 'cantidad' => '', 'data' => array());
        }
        $dataRespuesta[] = array('tipo' => '6', 'descripcion' => 'ENTREGADO', 'fecha' => '', 'icono' => 'entregado.png', 'data' => array());

        $banderaAnteriorContieneData = TRUE;
        $indexDelete = NULL;
        foreach ($dataRespuesta as $index => $item) {
            $itemDetalle = ObjectUtil::filtrarArrayPorColumna($dataDetalle, 'tipo', $item['tipo']);
            if (!ObjectUtil::isEmpty($itemDetalle) && $banderaAnteriorContieneData) {
                foreach ($itemDetalle as $indice => $itemData) {
                    $itemDetalle[$indice]['flujo'] = $item['descripcion'];
                }
                $dataRespuesta[$index]['data'] = $itemDetalle;
                $cantidadMaxima = $itemDetalle[0]['cantidad_total'];

                $dataArrayFecha = array_unique(array_column($itemDetalle, 'fecha_creacion'));
                $dataArrayPaquete = array_unique(array_column($itemDetalle, 'paquete_id'));
                usort($dataArrayFecha, function ($a, $b) {
                    $dateTimestamp1 = strtotime($a);
                    $dateTimestamp2 = strtotime($b);

                    return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
                });

                $fechaMaxima = $dataArrayFecha[count($dataArrayFecha) - 1];
                $dataRespuesta[$index]['fecha'] = $fechaMaxima;
                $dataRespuesta[$index]['cantidad'] = (count($dataArrayPaquete) . '/' . $cantidadMaxima);
                $dataRespuesta[$index]['cantidad_paquete'] = count($dataArrayPaquete);
                $dataRespuesta[$index]['cantidad_total'] = $cantidadMaxima;
                if ($item['tipo'] != 7) {
                    $banderaAnteriorContieneData = TRUE;
                }
                if ($item['tipo'] == 7 && strtotime($dataRespuesta[$index - 1]['fecha']) > strtotime($fechaMaxima)) {
                    $indexDelete = $index;
                }
            } else {
                if ($item['tipo'] == 7) {
                    $indexDelete = $index;
                } else {
                    $banderaAnteriorContieneData = FALSE;
                }
            }

            if ($tipo == 2 || $tipo == 3) {
                $dataRespuesta[$index]['data'] = array();
            }
        }

        if (!ObjectUtil::isEmpty($indexDelete)) {
            array_splice($dataRespuesta, $indexDelete, 1);
        }

        $respuesta = new stdClass();
        $respuesta->dataPedido = $dataPedido;
        $respuesta->dataDetalle = $dataRespuesta;
        $respuesta->tipo = $tipo;
        return $respuesta;
    }

    public function obtenerPaqueteTranckingSeguimientoXDocumentoId($documentoId)
    {
        return PaqueteTracking::create()->obtenerPaqueteTranckingSeguimientoXDocumentoId($documentoId);
    }

    public function obtenerPaqueteTranckingSeguimientoDetalleXDocumentoId($documentoId)
    {
        return PaqueteTracking::create()->obtenerPaqueteTranckingSeguimientoDetalleXDocumentoId($documentoId);
    }

    public function obtenerPaqueteTranckingSeguimientoDetalleXPaqueteId($paqueteId)
    {
        return PaqueteTracking::create()->obtenerPaqueteTranckingSeguimientoDetalleXPaqueteId($paqueteId);
    }
}
