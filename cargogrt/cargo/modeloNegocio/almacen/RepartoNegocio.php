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

class RepartoNegocio extends ModeloNegocioBase
{


    /**
     *
     * @return RepartoNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerReparto($usuarioCreacion)
    {
        $resultadoPerfilAgencia = DespachoNegocio::create()->validarPerfilAgencia($usuarioCreacion);
        $choferId = $resultadoPerfilAgencia[0]['persona_id'];
        $agenciaId = $resultadoPerfilAgencia[0]['agencia_id'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoxReparto($agenciaId, $choferId);
        $documentoId = $resultado[0]['id'];
        $vehiculoId = $resultado[0]['vehiculo_id'];

        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->vehiculoId = $vehiculoId;
        $data->choferId = $choferId;
        $data->paquetesPorRepartir = Movimiento::create()->obtenerPaquetesxRepartir($documentoId);
        $data->paquetesEntregados = Movimiento::create()->obtenerPaquetesEntregados($documentoId);

        return $data;
    }

    public function registrarReparto($documentoId, $personaId, $direccionId, $usuarioCreacion, $choferId, $vehiculoId, $personaRecepcionNombre, $personaRecepcionDocumento, $documentoAdjunto)
    {
        $movimientoTipoId = 145;
        $documentoFinalizadoEstadoId = 12;
        $estado = 1;
        $tipo = 3;

        $resultadoGuardarMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
        $movimientoId = $resultadoGuardarMovimiento[0]['vout_id'];
        $serie = "CR001";
        $dataCorrelativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(278, $serie);
        $correlativo = $dataCorrelativo[0]['numero'];

        $resultadoGuardarEntrega = Documento::create()->InsertDocumentoConstanciaReparto($vehiculoId, $choferId, $personaId, $direccionId, $personaRecepcionNombre, $personaRecepcionDocumento, $usuarioCreacion, $movimientoId, $correlativo);
        $entregaId = $resultadoGuardarEntrega[0]['id'];

        Documento::create()->insertarDocumentoRelacionado($documentoId, $entregaId, $estado, $usuarioCreacion, $tipo);

        //registrar documentos adjuntos
        MovimientoNegocio::create()->insertDocumentoAdjunto($entregaId, $documentoAdjunto, $usuarioCreacion);

        $obtenerPaquetesRelacionados = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId);
        $contadorPaquetes = 0;
        foreach ($obtenerPaquetesRelacionados as $value) {
            $paqueteId = $value['paquete_id'];
            $bienId = $value['bien_id'];

            $respuestaGuardarBien = Movimiento::create()->InsertMovimientoBien($movimientoId, $bienId, $usuarioCreacion);
            $movimientoBienId = $respuestaGuardarBien[0]['id'];

            Movimiento::create()->UpdateTracking($paqueteId);
            Movimiento::create()->InsertTrackingEntregado($paqueteId, $movimientoBienId, $usuarioCreacion);

            $contadorPaquetes++;

            $obtenerPedido = Movimiento::create()->UpdatePedidoTracking($paqueteId);
            $documentoPedidoId = $obtenerPedido[0]['id'];
            $estadoPedido = $obtenerPedido[0]['documento_estado_id'];
            $totalPedido = $obtenerPedido[0]['pedido_total'];
            $entregadoPedido = $obtenerPedido[0]['entregado_pedido'];

            if ($totalPedido == $entregadoPedido) {
                Movimiento::create()->InsertPedidoEstadoTracking($documentoPedidoId, 12, $usuarioCreacion);
            } else if ($totalPedido > $entregadoPedido && $estadoPedido !=  11) {
                Movimiento::create()->InsertPedidoEstadoTracking($documentoPedidoId, 11, $usuarioCreacion);
            }
        }

        $paquetePendienteEntregar =  Movimiento::create()->obtenerPaquetesxRepartir($documentoId);
        if (ObjectUtil::isEmpty($paquetePendienteEntregar)) {
            Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documentoFinalizadoEstadoId, $usuarioCreacion);
        }

        return $entregaId;
    }

    public function obtenerDetalleReparto($documentoId, $personaId, $direccionId)
    {

        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->detallepaquetesPorRepartir = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId);
        return $data;
    }
}
