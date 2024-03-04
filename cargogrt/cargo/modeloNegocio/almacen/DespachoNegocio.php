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

class DespachoNegocio extends ModeloNegocioBase
{
    const DOCUMENTO_TIPO_DESPACHO_ID = 279;
    const DOCUMENTO_TIPO_MANIFIESTO_ID = 276;
    const HORA_DEFECTO = '19:00:00';

    /**
     *
     * @return DespachoNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function validarPerfilAgencia($usuarioId)
    {
        $responsePerfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioId);

        if (ObjectUtil::isEmpty($responsePerfil)) {
            throw new WarningException("El usuario ($usuarioId) no tiene asignado ningún perfil perteneciente a una agencia.");
        }

        if (count($responsePerfil) > 1) {
            throw new WarningException("El usuario ($usuarioId) tiene asignado más de una agencia.");
        }

        return $responsePerfil;
    }

    public function obtenerPeriodoIdXFecha($fecha)
    {
        $responsePeriodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha);

        if (ObjectUtil::isEmpty($responsePeriodo)) {
            throw new WarningException("No existe una periodo para la  fech $fecha.");
        }

        return $responsePeriodo[0]['id'];
    }

    public function getDataAgenciaDespacho($usuarioId)
    {
        $responseDataPerfil = self::validarPerfilAgencia($usuarioId);
        $agenciaId = $responseDataPerfil[0]['agencia_id'];
        return Agencia::create()->getDataAgenciaDespacho($agenciaId);
    }

    public function obtenerDespachoQR($textoQR, $usuarioId, $fecha)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        }
        //LEER EL QR DEL VEHICULO 
        $resultadoLeerQr = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioId);
        $this->validateResponse($resultadoLeerQr);

        $resultadoPerfilAgencia = self::validarPerfilAgencia($usuarioId);

        $vehiculoId = $resultadoLeerQr[0]['id'];
        $vehiculoPlaca = $resultadoLeerQr[0]['placa'];

        $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];
        $agenciaDescripcion = $resultadoPerfilAgencia[0]['agencia_descripcion'];

        //DATA DE PRUEBA 
        $choferId = NULL;
        $copilotoId = NULL;
        $agenciaDestinoId = NULL;
        $agenciaDestinoDescripcion = ' ';

        //Obtener los itenerarios para ese vehiculo según la vecha
        $dataItinerario = MovimientoNegocio::create()->obtenerAgenciasIttsa($fecha, $usuarioId, $vehiculoPlaca);

        if (!ObjectUtil::isEmpty($dataItinerario)) {
            usort($dataItinerario, function ($fechaInicial, $fechaFinal) {
                return strtotime(trim($fechaInicial['itinerarioHoraSalida'])) < strtotime(trim($fechaFinal['itinerarioHoraSalida']));
            });

            foreach ($dataItinerario as $item) {
                //Obtenemos el itinerario más cercado a la hora actual.
                if (time() <= strtotime(trim($item['itinerarioHoraSalida']))) {
                    $fecha = str_replace('T', ' ', trim($item['itinerarioHoraSalida']));
                    $agenciaDestinoId = $item['agencia_destino_id'];
                    $agenciaDestinoDescripcion = $item['destinoNombre'];
                    $dataTribulacion = $item['tripulación'];
                    foreach ($dataTribulacion as $valueIndex => $value) {
                        if ($value['pilotoCargo'] == 'PILOTO') {
                            $choferId  = $value['chofer_id'];
                        } elseif ($value['pilotoCargo'] == 'COPILOTO') {
                            $copilotoId  = $value['chofer_id'];
                        }
                    }
                    break;
                }
            }
        }

        $movimientoTipoId = 146;
        $estado = 1;
        $documento_estado = 8;
        $serie = "D001";

        $periodoId = self::obtenerPeriodoIdXFecha($fecha);
        $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(self::DOCUMENTO_TIPO_DESPACHO_ID, $serie);
        $correlativo = $dataCorrelativo[0]['numero'];
        $resultadoObtenerDespacho = Documento::create()->obtenerDocumentoDespacho($vehiculoId, $agenciaId);
        if ($resultadoObtenerDespacho[0]['vout_exito'] != 1) {
            throw new WarningException($resultadoObtenerDespacho[0]['vout_mensaje']);
        }
        if (ObjectUtil::isEmpty($resultadoObtenerDespacho[0]['id'])) {
            $fechaSalida = $fecha;
            if (ObjectUtil::isEmpty($fechaSalida)) {
                $fechaPorDefecto = date("Y-m-d") . " " . self::HORA_DEFECTO;
                $fechaActual = date("Y-m-d H:i:s");
                if (strtotime($fechaPorDefecto) > strtotime($fechaActual)) {
                    $fechaSalida = $fechaPorDefecto;
                } else {
                    $fechaSalida = $fechaActual;
                }
                $fecha  = $fechaSalida;
            }
            //INSERTAMOS EL MOVIMIENTO DESPACHO  
            $resultadoGuardarMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioId);
            $movimientoId = $resultadoGuardarMovimiento[0]['vout_id'];
            //INSERTAMOS EL DESPACHO 
            $resultadoGuardarDespacho = Documento::create()->InsertDocumentoDespacho($vehiculoId, $usuarioId, $agenciaId, $choferId, $agenciaDestinoId, NULL, $serie, $periodoId, $movimientoId, $correlativo, $copilotoId, $fechaSalida);
            $documentoId = $resultadoGuardarDespacho[0]['id'];
            //INSERTAMOS EL ESTADO A PENDIENTE DE CONFIRMACION 
            Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId);
        } else {
            $movimientoId = $resultadoObtenerDespacho[0]['movimiento_id'];
            $documentoId = $resultadoObtenerDespacho[0]['id'];
        }

        $dataInput = new stdClass();
        $dataInput->vehiculoPlaca = $vehiculoPlaca;
        $dataInput->agenciaOrigenId = $agenciaId;
        $dataInput->agenciaOrigenDescripcion = $agenciaDescripcion;
        $dataInput->agenciaDestinoId = $agenciaDestinoId;
        $dataInput->agenciaDestinoDescripcion = $agenciaDestinoDescripcion;
        $dataInput->fecha = $fecha;
        $dataInput->documentoId = $documentoId;
        $dataInput->movimientoId = $movimientoId;
        $dataInput->pilotoId = $choferId;
        $dataInput->copilotoId = $copilotoId;
        return $dataInput;
    }


    public function obtenerDespachoIttsa(
        $agenciaDestinoId,
        $agenciaDestinoDescripcion,
        $fechaSalida,
        $flotaPlaca,
        $usuarioId,
        $choferId,
        $copilotoId
    ) {

        $responseVehiculo = Vehiculo::create()->obtenerVehiculoxPlaca($flotaPlaca);
        $vehiculoId = $responseVehiculo[0]['id'];
        $vehiculoPlaca = $responseVehiculo[0]['flota_placa'];

        $resultadoPerfilAgencia = self::validarPerfilAgencia($usuarioId);
        $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];
        $agenciaDescripcion = $resultadoPerfilAgencia[0]['agencia_descripcion'];

        $movimientoTipoId = 146;
        $estado = 1;
        $documento_estado = 8;
        $serie = "D001";

        $resultadoObtenerDespacho = Documento::create()->obtenerDocumentoDespacho($vehiculoId, $agenciaId);
        if ($resultadoObtenerDespacho[0]['vout_exito'] != 1) {
            throw new WarningException($resultadoObtenerDespacho[0]['vout_mensaje']);
        }
        if (ObjectUtil::isEmpty($resultadoObtenerDespacho[0]['id'])) {
            if (ObjectUtil::isEmpty($fechaSalida)) {
                $fechaPorDefecto = date("Y-m-d") . " " . self::HORA_DEFECTO;
                $fechaActual = date("Y-m-d H:i:s");
                if (strtotime($fechaPorDefecto) > strtotime($fechaActual)) {
                    $fechaSalida = $fechaPorDefecto;
                } else {
                    $fechaSalida = $fechaActual;
                }
            }


            $periodoId = self::obtenerPeriodoIdXFecha($fechaSalida);
            $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(self::DOCUMENTO_TIPO_DESPACHO_ID, $serie);
            $correlativo = $dataCorrelativo[0]['numero'];

            //INSERTAMOS EL MOVIMIENTO DESPACHO  
            $resultadoGuardarMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioId);
            $movimientoId = $resultadoGuardarMovimiento[0]['vout_id'];
            //INSERTAMOS EL DESPACHO 
            $resultadoGuardarDespacho = Documento::create()->InsertDocumentoDespacho($vehiculoId, $usuarioId, $agenciaId, $choferId, $agenciaDestinoId, NULL, $serie, $periodoId, $movimientoId, $correlativo, $copilotoId, $fechaSalida);
            $documentoId = $resultadoGuardarDespacho[0]['id'];
            //INSERTAMOS EL ESTADO A PENDIENTE DE CONFIRMACION 
            Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId);
        } else {
            $movimientoId = $resultadoObtenerDespacho[0]['movimiento_id'];
            $documentoId = $resultadoObtenerDespacho[0]['id'];
        }

        $dataInput = new stdClass();
        $dataInput->vehiculoPlaca = $vehiculoPlaca;
        $dataInput->agenciaOrigenId = $agenciaId;
        $dataInput->agenciaOrigenDescripcion = $agenciaDescripcion;
        $dataInput->agenciaDestinoId = $agenciaDestinoId;
        $dataInput->agenciaDestinoDescripcion = $agenciaDestinoDescripcion;
        $dataInput->fecha =  str_replace("T", " ", $fechaSalida); // $fecha_salida;
        $dataInput->documentoId = $documentoId;
        $dataInput->movimientoId = $movimientoId;
        $dataInput->pilotoId = $choferId;
        $dataInput->copilotoId = $copilotoId;
        return $dataInput;
    }



    public function listarPaqueteDespacho($documentoId, $usuarioId, $agenciaDestinoId)
    {

        $resultadoPerfilAgencia = self::validarPerfilAgencia($usuarioId);
        $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];
        $agenciaDescripcion = $resultadoPerfilAgencia[0]['agencia_descripcion'];
        $agenciaCodigo = $resultadoPerfilAgencia[0]['agencia_codigo'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoPlaca = $resultado[0]['placa'];

        $data = new stdClass();
        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $agenciaCodigo;
        $data->descripcionAgencia = $agenciaDescripcion;
        $data->documentoId = $documentoId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxManifiestoDespachoIdXAgenciaId($documentoId, $agenciaId, $agenciaDestinoId);
        $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxAgenciaManifiesto($agenciaDestinoId, $agenciaId);
        return $data;
    }

    public function registrarCodigoPaqueteDespacho($codigo, $documentoId, $usuarioCreacion, $agencia_id, $bandera)
    {
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo((int)$codigo, $tipo);
        if (ObjectUtil::isEmpty($respuesta_codigo)) {
            throw new WarningException("No se encontro información relacionada al código ingresado.");
        }

        $textoQR = $respuesta_codigo[0]['textoQr'];
        if (ObjectUtil::isEmpty($textoQR)) {
            throw new WarningException("El código no se encuentra registrado.");
        }

        return self::registrarPaqueteDespacho($textoQR, $documentoId, $usuarioCreacion, $agencia_id, $bandera);
    }

    public function registrarPaqueteDespacho($textoQR, $documentoId, $usuarioId, $agenciaId, $bandera)
    {

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        }

        $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioId);
        $this->validateResponse($response);

        $codigo_paquete = $response[0]['codigo_paquete'];
        $codigo_bien = $response[0]['codigo'];
        $id_bien = $response[0]['id_bien'];
        $remitente = $response[0]['remitente'];
        $destino = $response[0]['destino'];
        $destinatario = $response[0]['destinatario'];
        $agenciaDestinoId = $response[0]['codigo_destino'];

        if ($agenciaId != $agenciaDestinoId && $bandera == 0) {
            $dataValidacion = new stdClass();
            $bandera = 0;
            $mensaje = 'El QR leido no pertenece a este destino, ¿esta seguro de asignarlo?';
            $dataValidacion->bandera = $bandera;
            $dataValidacion->mensaje = $mensaje;
            return $dataValidacion;
        }

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $chofer = $resultado[0]['chofer_id'];
        $copilotoId = $resultado[0]['copiloto_id'];
        $vehiculo = $resultado[0]['vehiculo_id'];
        $agenciaOrigen = $resultado[0]['agencia_id'];
        $agenciaDestino = $agenciaId;
        $periodo = $resultado[0]['periodo_id'];
        $fecha_salida = $resultado[0]['fecha_salida'];

        $movimientoTipoId = 143; //146
        $estado = 1;
        $documento_estado = 8;
        $tipo = 4;

        //VERIFICAMOS SI EL PAQUETE ESTA RELACIONADO A OTRO DESPACHO
        $resultadoObtenerPaquete = Movimiento::create()->obtenerVerificarDespachoxPaqueteId($codigo_paquete);
        if ($resultadoObtenerPaquete[0]['vout_exito'] != 1) {
            throw new WarningException($resultadoObtenerPaquete[0]['vout_mensaje']);
        }

        $resultadoObtenerManifiesto = Documento::create()->obtenerDocumentoManifiestoDespacho($vehiculo, $agenciaOrigen, $agenciaDestino, $documentoId);
        if ($resultadoObtenerManifiesto[0]['vout_exito'] != 1) {
            throw new WarningException($resultadoObtenerManifiesto[0]['vout_mensaje']);
        }

        if (ObjectUtil::isEmpty($resultadoObtenerManifiesto[0]['id'])) {

            $resultadoGuardarMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioId);
            $movimientoManifiestoId = $resultadoGuardarMovimiento[0]['vout_id'];

            $serie = "M001";
            $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(self::DOCUMENTO_TIPO_MANIFIESTO_ID, $serie);
            $correlativo = $dataCorrelativo[0]['numero'];

            $fechaSalida = $fecha_salida;
            if (ObjectUtil::isEmpty($fechaSalida)) {
                $fechaPorDefecto = date("Y-m-d") . " " . self::HORA_DEFECTO;
                $fechaActual = date("Y-m-d H:i:s");
                if (strtotime($fechaPorDefecto) > strtotime($fechaActual)) {
                    $fechaSalida = $fechaPorDefecto;
                } else {
                    $fechaSalida = $fechaActual;
                }
            }

            $resultadoGuardarManifiesto = Documento::create()->InsertDocumentoManifiesto($vehiculo, $usuarioId, $agenciaOrigen, $fechaSalida, NULL, $chofer, $agenciaDestino, NULL, $serie, $periodo, $movimientoManifiestoId, $correlativo, $copilotoId);
            $documentoManifiestoId = $resultadoGuardarManifiesto[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($documentoManifiestoId, $documento_estado, $usuarioId);

            Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoManifiestoId, $estado, $usuarioId, $tipo);
        } else {

            $movimientoManifiestoId = $resultadoObtenerManifiesto[0]['movimiento_id'];
            $documentoManifiestoId = $resultadoObtenerManifiesto[0]['id'];
        }

        $respuestaGuardarMovimientoBien = Movimiento::create()->InsertMovimientoBien($movimientoManifiestoId, $id_bien, $usuarioId);
        $movimientoBienId = $respuestaGuardarMovimientoBien[0]['id'];
        Movimiento::create()->UpdateTracking($codigo_paquete);
        Movimiento::create()->InsertTrackingDespacho($codigo_paquete, $movimientoBienId, $usuarioId);

        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->agenciaDestino = $destino;
        $data->remitente = $remitente;
        $data->destinatario = $destinatario;
        $data->codigoPaquete = $codigo_bien;
        $data->paqueteId = $codigo_paquete;
        return $data;
    }

    public function guardarDespacho($documentoId, $usuarioCreacion)
    {
        $dataDespacho = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        if (ObjectUtil::isEmpty($dataDespacho)) {
            throw new WarningException("No se encontró la información del despacho.");
        }

        if ($dataDespacho[0]['estado'] != 1) {
            throw new WarningException("El despacho se encuentra inactivo.");
        }

        if ($dataDespacho[0]['documento_estado_id'] != 8) {
            throw new WarningException("El despacho no se encuentra en el estado pendiente de confirmación.");
        }

        $serieNumeroDespacho = $dataDespacho[0]['serie_numero'];
        $vehiculoId = $dataDespacho[0]['vehiculo_id'];
        $agenciaId = $dataDespacho[0]['agencia_id'];

        $documentoEstadoId = 1;
        $docuemntoEstadoAnuladoId = 2;
        $tipo = 4;
        $respuestaRelacion = Documento::create()->obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, $tipo);
        if (ObjectUtil::isEmpty($respuestaRelacion)) {
            throw new WarningException("Aún no ingresa paquetes al despacho seleccionado.");
        }
        $dataManifiesto = Util::filtrarArrayPorColumna($respuestaRelacion, "documento_tipo_id", self::DOCUMENTO_TIPO_MANIFIESTO_ID);
        if (ObjectUtil::isEmpty($respuestaRelacion)) {
            throw new WarningException("Aún no ingresa paquetes al despacho seleccionado.");
        }

        $contadorActulizacionManifiesto = 0;
        foreach ($dataManifiesto as $item) {
            if ($agenciaId == $item['agencia_id'] && $vehiculoId == $item['vehiculo_id']) {
                $manifiestoId = $item['documento_id'];
                $serieNumeroManifiesto = $item['serie_numero'];

                if ($item['cantidad_paquete'] == 0) {

                    // ANULO AL DOCUMENTO 
                    $respuestaAnular = DocumentoNegocio::create()->anular($manifiestoId);
                    // ANULO LA RELACION 
                    $respuestaRelacion = Documento::create()->eliminarDocumentoRelacionXId($item['relacion_id']);
                    // ANULO AL DOCUMENTO ESTADO 
                    Documento::create()->insertarDocumentoDocumentoEstado($manifiestoId, $docuemntoEstadoAnuladoId, $usuarioCreacion);
                } else {
                    if ($item['documento_estado_id'] != 8) {
                        throw new WarningException("El manifiesto $serieNumeroManifiesto no se encuentra pendiente confirmación");
                    }

                    Documento::create()->insertarDocumentoDocumentoEstado($manifiestoId, $documentoEstadoId, $usuarioCreacion);
                    Documento::create()->actualizarFechaSalidaDocumento($manifiestoId);
                    $contadorActulizacionManifiesto++;
                }
            }
        }

        if ($contadorActulizacionManifiesto == 0) {
            throw new WarningException("Aún no ingresa paquetes al despacho seleccionado.");
        }

        Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documentoEstadoId, $usuarioCreacion);
        Documento::create()->actualizarFechaSalidaDocumento($documentoId);
        MovimientoNegocio::create()->enviarNotificacionDespacho($documentoId);

        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->serie_numero = $serieNumeroDespacho;
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
}
