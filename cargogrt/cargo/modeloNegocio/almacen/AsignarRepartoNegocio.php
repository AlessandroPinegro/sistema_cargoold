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

class AsignarRepartoNegocio extends ModeloNegocioBase
{


    /**
     *
     * @return AsignarRepartoNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerVehiculoRepartoQR($textoQR, $fecha, $usuarioCreacion)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        }
        $resultado = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
        $this->validateResponse($resultado);
        $vehiculoId = $resultado[0]['id'];
        $vehiculoPlaca = $resultado[0]['placa'];

        $resultadoPerfilAgencia = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];
        $agenciaDescripcion = $resultadoPerfilAgencia[0]['agencia_descripcion'];


        $movimientoTipoId = 143;
        $estado = 1;
        $documento_estado = 8;

        $serie = "M001";
        $resultadoManifiesto = Documento::create()->obtenerDocumentoManifiestoReparto($vehiculoId, $agenciaId);

        if (ObjectUtil::isEmpty($resultadoManifiesto[0]['id'])) {
            $periodoId = DespachoNegocio::create()->obtenerPeriodoIdXFecha($fecha);
            $resultadoGuardarMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
            $movimientoId = $resultadoGuardarMovimiento[0]['vout_id'];

            $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(DespachoNegocio::DOCUMENTO_TIPO_MANIFIESTO_ID, $serie);
            $correlativo = $dataCorrelativo[0]['numero'];
            $resultadoGuardarManifiesto = Documento::create()->InsertDocumentoManifiestoReparto($vehiculoId, $usuarioCreacion, $agenciaId, $fecha, NULL, $serie, $periodoId, $movimientoId, $correlativo);
            $manifiestoId = $resultadoGuardarManifiesto[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($manifiestoId, $documento_estado, $usuarioCreacion);
        } else {

            $movimientoId = $resultadoManifiesto[0]['movimiento_id'];
            $manifiestoId = $resultadoManifiesto[0]['id'];
        }


        $dataInput = new stdClass();
        $dataInput->vehiculoPlaca = $vehiculoPlaca;
        $dataInput->agenciaOrigenId = $agenciaId;
        $dataInput->agenciaOrigenDescripcion = $agenciaDescripcion;
        $dataInput->fecha = $fecha;
        $dataInput->documentoId = $manifiestoId;
        $dataInput->movimientoId = $movimientoId;

        return $dataInput;
    }

    public function listarPaqueteReparto($documentoId, $usuarioCreacion)
    {
        $estado = 1;
        $tipo = 5;
        $resultadoPerfilAgencia = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        // $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];
        $agenciaCodigo = $resultadoPerfilAgencia[0]['agencia_codigo'];
        $agenciaDescripcion = $resultadoPerfilAgencia[0]['agencia_descripcion'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoPlaca = $resultado[0]['placa'];

        $data = new stdClass();
        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $agenciaCodigo;
        $data->descripcionAgencia = $agenciaDescripcion;
        $data->documentoId = $documentoId;
        $data->paquetesAsignadosReparto = Movimiento::create()->obtenerPaquetesxDocumentoManifiestoReparto($documentoId, $estado, $tipo);

        return $data;
    }
    public function registrarCodigoPaqueteReparto($codigo, $documentoId, $usuarioCreacion)
    {
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];
        if ($textoQR == null) {
            throw new WarningException("El código no se encuentra registrado");
        }
        return self::registrarPaqueteReparto($textoQR, $documentoId, $usuarioCreacion);
    }

    public function registrarPaqueteReparto($textoQR, $documentoId, $usuarioCreacion)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        }
        $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);

        $this->validateResponse($response);
        $codigo_paquete = $response[0]['codigo_paquete'];
        $codigo_bien = $response[0]['codigo'];
        $id_bien = $response[0]['id_bien'];
        $remitente = $response[0]['remitente'];
        $destino = $response[0]['destino'];
        $origen = $response[0]['origen'];
        $destinatario = $response[0]['destinatario'];

        $respuesta_movimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
        $id_movimiento = $respuesta_movimiento[0]['movimiento_id'];
        $respuesta_paquete = Movimiento::create()->obtenerDocumentoRxPaquete($documentoId, $codigo_paquete);
        if ($respuesta_paquete[0]['vout_exito'] != 1) {
            throw new WarningException($respuesta_paquete[0]['vout_mensaje']);
        }

        $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
        $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
        Movimiento::create()->UpdateTracking($codigo_paquete);
        Movimiento::create()->InsertTrackingReparto($codigo_paquete, $id_movimiento_bien, $usuarioCreacion);

        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->agenciaOrigen = $origen;
        $data->agenciaDestino = $destino;
        $data->remitente = $remitente;
        $data->destinatario = $destinatario;
        $data->codigoPaquete = $codigo_bien;
        $data->paqueteId = $codigo_paquete;

        return $data;
    }

    public function guardarReparto($documentoId, $usuarioCreacion)
    {
        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("No se encontró la información del despacho");
        }

        if ($resultado[0]['estado'] != 1) {
            throw new WarningException("El manifiesto se encuentra inactivo");
        }

        if ($resultado[0]['documento_estado_id'] != 8) {
            throw new WarningException("El manifiesto no se encuentra pendiente de confirmación");
        }

        $documento_estado = 1;
        Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacion);

        $serie_numero = $resultado[0]['serie_numero'];
        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->serie_numero = $serie_numero;
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
