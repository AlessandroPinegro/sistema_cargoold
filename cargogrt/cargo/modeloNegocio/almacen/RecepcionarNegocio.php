<?php

require_once __DIR__ . '/../../modelo/almacen/Movimiento.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modelo/almacen/BienUnico.php';
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
require_once __DIR__ . '/../../modelo/almacen/Penalidad.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/CentroCostoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContDistribucionContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/DetraccionNegocio.php';
require_once __DIR__ . '/../commons/ConstantesNegocio.php';
require_once __DIR__ . '/PedidoNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/UnidadNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/DespachoNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/ExcelNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/DocumentoDatoValorNegocio.php';
require_once __DIR__ . '/BienPrecioNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/PagoNegocio.php';
require_once __DIR__ . '/BienUnicoNegocio.php';
require_once __DIR__ . '/MovimientoDuaNegocio.php';
require_once __DIR__ . '/PeriodoNegocio.php';
require_once __DIR__ . '/ACCajaNegocio.php';
require_once __DIR__ . '/VehiculoNegocio.php';
require_once __DIR__ . '/ProgramacionAtencionNegocio.php';
require_once __DIR__ . '/../../util/NumeroALetra/EnLetras.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modelo/almacen/Actividad.php';
require_once __DIR__ . '/../../modelo/almacen/Pago.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';

class RecepcionarNegocio extends ModeloNegocioBase
{
    const DOCUMENTO_TIPO_DESPACHO_ID = 279;
    const DOCUMENTO_TIPO_MANIFIESTO_ID = 276;
    const HORA_DEFECTO = '19:00:00';

    /**
     *
     * @return RecepcionarNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerDocumentoNumeroSerie($serie, $numero, $usuarioCreacion)
    {

        $dataPerfil = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $agenciaCodigo = $dataPerfil[0]['agencia_codigo'];
        $agenciaDescripcion = $dataPerfil[0]['agencia_descripcion'];
        $agenciaDestinoId = $dataPerfil[0]['agencia_id'];


        $resultado = Documento::create()->obtenerDocumentoxNumeroSerie($serie, $numero, $agenciaDestinoId);
        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("No se obtuvo la informaciónd del manifiesto ingresado");
        }

        if ($resultado[0]['estado'] != 1) {
            throw new WarningException("El manifiesto no esta activo.");
        }

        if ($resultado[0]['documento_estado_id'] != 1) {
            throw new WarningException("El manifiesto no se encuentra en una estado invalido");
        }
        $estado = 1;
        $movimientoTipoId = 144;
        $documento_estado = 8;
        $tipo = 1;

        $vehiculoId = $resultado[0]['vehiculo_id'];
        $vehiculoPlaca = $resultado[0]['vehiculo_placa'];


        $dataPerfil = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $agenciaCodigo = $dataPerfil[0]['agencia_codigo'];
        $agenciaDescripcion = $dataPerfil[0]['agencia_descripcion'];
        $agenciaDestinoId = $dataPerfil[0]['agencia_id'];

        $dataManifiesto = DocumentoNegocio::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $agenciaDestinoId);
        $documentoRecepcionId = $dataManifiesto[0]['documento_relacion_id'];

        $data = new stdClass();
        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $agenciaCodigo;
        $data->descripcionAgencia = $agenciaDescripcion;

        $serie = "RP001";
        if (ObjectUtil::isEmpty($documentoRecepcionId)) {
            $fechaSalida = date("Y-m-d");
            $periodoId = DespachoNegocio::create()->obtenerPeriodoIdXFecha($fechaSalida);
            $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(277, $serie);
            $correlativo = $dataCorrelativo[0]['numero'];

            $resultadoRegistroMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
            $movimientoRecepcionId = $resultadoRegistroMovimiento[0]['vout_id'];

            $resultadoDocumentoGuardarRecepcion = Documento::create()->InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $agenciaDestinoId, $movimientoRecepcionId, NULL, $serie, $correlativo, $periodoId);
            $documentoRecepcionId = $resultadoDocumentoGuardarRecepcion[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($documentoRecepcionId, $documento_estado, $usuarioCreacion);
        }

        $data->documentoId = $documentoRecepcionId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoRecepcionId);
        $pedidosPorLeer = array();
        $data->documentoManifiestoId = NULL;

        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            $agenciaDestino = $resultado['agencia_destino_id'];
            $data->documentoManifiestoId = $documentoId;

            $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, 1);
            $documentoRelacionadoFilter = Util::filtrarArrayPorColumna($documentoRelacionado, ["documento_id", "documento_estado_id"], [$documentoRecepcionId, 8]);
            if (ObjectUtil::isEmpty($documentoRelacionadoFilter)) {
                $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
            }

            if (!ObjectUtil::isEmpty($agenciaDestino)) {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            } else {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            }
        }
        $data->paquetesPorLeer =  $pedidosPorLeer;
        return $data;
    }


    public function obtenerDocumentoQR($textoQR, $usuarioCreacion)
    {

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        }

        $response = MovimientoNegocio::create()->obtenerDataQR($textoQR);
        $this->validateResponse($response);

        $estado = 1;
        $movimientoTipoId = 144;
        $documento_estado = 8;
        $tipo = 1;

        $vehiculoId = $response[0]['id'];
        $vehiculoPlaca = $response[0]['placa'];


        $dataPerfil = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $agenciaCodigo = $dataPerfil[0]['agencia_codigo'];
        $agenciaDescripcion = $dataPerfil[0]['agencia_descripcion'];
        $agenciaDestinoId = $dataPerfil[0]['agencia_id'];


        $dataRecepcion = DocumentoNegocio::create()->obtenerDocumentoRecepcionPendientexVehiculoxAgencia($vehiculoId, $agenciaDestinoId);
        $documentoRecepcionId = $dataRecepcion[0]['id'];


        $dataManifiesto = DocumentoNegocio::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $agenciaDestinoId);

        $data = new stdClass();
        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $agenciaCodigo;
        $data->descripcionAgencia = $agenciaDescripcion;

        $serie = "RP001";
        if (ObjectUtil::isEmpty($documentoRecepcionId)) {
            $fechaSalida = date("Y-m-d");
            $periodoId = DespachoNegocio::create()->obtenerPeriodoIdXFecha($fechaSalida);
            $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(277, $serie);
            $correlativo = $dataCorrelativo[0]['numero'];

            $resultadoRegistroMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
            $movimientoRecepcionId = $resultadoRegistroMovimiento[0]['vout_id'];

            $resultadoDocumentoGuardarRecepcion = Documento::create()->InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $agenciaDestinoId, $movimientoRecepcionId, NULL, $serie, $correlativo, $periodoId);
            $documentoRecepcionId = $resultadoDocumentoGuardarRecepcion[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($documentoRecepcionId, $documento_estado, $usuarioCreacion);
        }

        $data->documentoId = $documentoRecepcionId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoRecepcionId);
        $pedidosPorLeer = array();
        $data->documentoManifiestoId = NULL;

        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            $agenciaDestino = $resultado['agencia_destino_id'];
            $data->documentoManifiestoId = $documentoId;

            $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, 1);
            $documentoRelacionadoFilter = Util::filtrarArrayPorColumna($documentoRelacionado, ["documento_id", "documento_estado_id"], [$documentoRecepcionId, 8]);
            if (ObjectUtil::isEmpty($documentoRelacionadoFilter)) {
                $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
            }

            if (!ObjectUtil::isEmpty($agenciaDestino)) {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            } else {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            }
        }
        $data->paquetesPorLeer =  $pedidosPorLeer;
        return $data;
    }

    public function registrarRecepcionPaqueteCodigo($codigo, $usuarioCreacion, $documentoId)
    {

        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];
        if (ObjectUtil::isEmpty($textoQR)) {
            throw new WarningException("El código no se encuentra registrado");
        }
        return self::registrarRecepcionPaqueteQR($textoQR, $usuarioCreacion, $documentoId);
    }

    public function registrarRecepcionPaqueteQR($textoQR, $usuarioCreacion, $documentoId)
    {

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        }

        $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
        $this->validateResponse($response);
        $codigoPaquete = $response[0]['codigo_paquete'];
        $bienId = $response[0]['id_bien'];

        $dataPerfil = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $organizadorRecepcionId = $dataPerfil[0]['organizador_defecto_recepcion_id'];

        if (ObjectUtil::isEmpty($documentoId) || strtolower($documentoId) == "null") {
            throw new WarningException("No se logro obtener el identificado del manifiesto, por favor debe volver a escanear el vehiculo.");
        }
        
        //Movimiento recepcion
        $dataMovimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
        $movimientoId = $dataMovimiento[0]['movimiento_id'];

        if (ObjectUtil::isEmpty($movimientoId) || strtolower($movimientoId) == "null") {
            throw new WarningException("No se logro obtener el identificado del manifiesto, por favor debe volver a escanear el vehiculo.");
        }

        //Obtener relación manifiesto
        $dataDocumentoRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoIdXPaqueteId($documentoId, $codigoPaquete);
        $manifiestoId = $dataDocumentoRelacion[0]['documento_id'];

        $respuestaValidarPaquete = Movimiento::create()->obtenerDocumentoRecepcionxPaquete($documentoId, $codigoPaquete, $manifiestoId);
        if ($respuestaValidarPaquete[0]['vout_exito'] != 1) {
            throw new WarningException($respuestaValidarPaquete[0]['vout_mensaje']);
        }

        $respuestaGuardarMovimientoBien = Movimiento::create()->InsertMovimientoBien($movimientoId, $bienId, $usuarioCreacion);
        $movimientoBienId = $respuestaGuardarMovimientoBien[0]['id'];

        Movimiento::create()->UpdateTracking($codigoPaquete);
        Movimiento::create()->InsertTrackingRecepcion($codigoPaquete, $movimientoBienId, $usuarioCreacion, $organizadorRecepcionId);

        $data = new stdClass();
        $data->AgenciaOrigen = $response[0]['origen'];
        $data->AgenciaDestino = $response[0]['destino'];
        $data->Remitente = $response[0]['remitente'];
        $data->Destinatario = $response[0]['destinatario'];
        $data->DocumentoId = $documentoId; // Recepción
        $data->DocumentoManifiestoId = $manifiestoId;  // Manifiesto
        return $data;
    }



    public function obtenerDocumentoPaquetes($documentoId, $usuarioCreacion)
    {
        $dataPerfil = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $agenciaCodigo = $dataPerfil[0]['agencia_codigo'];
        $agenciaDescripcion = $dataPerfil[0]['agencia_descripcion'];
        $agenciaDestinoId = $dataPerfil[0]['agencia_id'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoId = $resultado[0]['vehiculo_id'];
        $vehiculoPlaca = $resultado[0]['placa'];

        $estado = 1;
        $tipo = 1;

        $dataManifiesto = DocumentoNegocio::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $agenciaDestinoId);
        $documentoRecepcionId = $dataManifiesto[0]['documento_relacion_id'];
        $manifiestoid = $dataManifiesto[0]['id'];
        $data = new stdClass();
        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $agenciaCodigo;
        $data->descripcionAgencia = $agenciaDescripcion;
        $data ->dataManifiesto = $dataManifiesto;
        $data->documentoId = $documentoRecepcionId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoRecepcionId);
        $pedidosPorLeer = array();
        $data->documentoManifiestoId = NULL;

        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            $agenciaDestino = $resultado['agencia_destino_id'];
            $data->documentoManifiestoId = $documentoId;

            $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, 1);
            $documentoRelacionadoFilter = Util::filtrarArrayPorColumna($documentoRelacionado, ["documento_id", "documento_estado_id"], [$documentoRecepcionId, 8]);
            if (ObjectUtil::isEmpty($documentoRelacionadoFilter)) {
                $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
            }

            if (!ObjectUtil::isEmpty($agenciaDestino)) {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            } else {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, 1);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            }
        }
         //:::DESARROLLO JESUS:::::::
         $pedidosPorLeermanifiesto = array();
         $pedidosPorLeermanifiesto = Movimiento::create()->obtenerEstadoPaquetexManifiesto($manifiestoid);
 
           $cantidadPaquete =  count((array)$pedidosPorLeermanifiesto);
         if( $cantidadPaquete  == 0){
             $debloquear = Movimiento::create()->desbloquearAutomaticamenteCarguero($manifiestoid);
         }
         //:::DESARROLLO JESUS:::::::
         $data-> pedidosPorLeermanifiesto = $pedidosPorLeermanifiesto;
         $data->  manifiestoid = $manifiestoid;
         $data-> cantidadPaquete= $cantidadPaquete;
         $data->paquetesPorLeer =  $pedidosPorLeer;
        return $data;
    }

    public function eliminarPaqueteTracking($id, $usuarioId)
    {
        $respuestaActualizar =  Movimiento::create()->paqueteTrackingRevertirEstadoMovimiento($id, $usuarioId);
        if ($respuestaActualizar[0]['vout_exito'] != 1) {
            throw new WarningException($respuestaActualizar[0]['vout_mensaje']);
        }
        return $respuestaActualizar;
    }


    public function guardarRecepcion($recepcionId, $usuarioCreacion, $bandera)
    {
        $documentoRegistradoId = 1;
        $documentoRecepcionadoId = 9;
        $dataManifiesto = Documento::create()->obtenerDocumentoManiestoxRecepcionId($recepcionId);
        $pedidosPorLeer = array();
        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            $agenciaDestino = $resultado['agencia_destino_id'];
            if (!ObjectUtil::isEmpty($agenciaDestino)) {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            } else {
                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, 1);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            }
        }

        $cantidadPaquete = count($pedidosPorLeer);
        if ($cantidadPaquete >= 1 && $bandera == 0) {
            $modal = new stdClass();
            $bandera = 0;
            $mensaje = 'Faltan recepcionar ' . $cantidadPaquete . ' paquetes, ¿esta seguro de culminar la recepción?';
            $modal->bandera = $bandera;
            $modal->mensaje = $mensaje;
            return $modal;
        }

        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documentoRecepcionadoId, $usuarioCreacion);
        }
        Documento::create()->insertarDocumentoDocumentoEstado($recepcionId, $documentoRegistradoId, $usuarioCreacion);

        $serie_numero = $dataManifiesto[0]['serie_numero'];
        $data = new stdClass();
        $data->documentoId = $recepcionId;
        $data->serie_numero = $serie_numero;

        //lógica de envio de correos
        MovimientoNegocio::create()->enviarCorreoRecepcionPedido($recepcionId, $usuarioCreacion);

        return $data;
    }
}
