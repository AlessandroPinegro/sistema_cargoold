<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/LibroDiarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class LibroDiarioControlador extends ControladorBase {

    public function obtenerConfiguracionInicial() {
        $empresaId = $this->getParametro("id_empresa");
        return LibroDiarioNegocio::create()->obtenerConfiguracionInicial($empresaId);
    }

    public function listarLibroDiarioXCriterios() {
        $criterios = $this->getParametro("criterios");
        return LibroDiarioNegocio::create()->listarLibroDiarioXCriterios($criterios);
    }

    public function anularContVoucher() {
        $voucherId = $this->getParametro("voucher_id");
        return ContVoucherNegocio::create()->anularContVoucherXId($voucherId);
    }

    public function obtenerContVoucher() {
        $voucherId = $this->getParametro("voucher_id");
        $data->dataVoucher = ContVoucherNegocio::create()->obtenerContVoucherXId($voucherId);
        $data->dataVoucherDetalle = ContVoucherDetalle::create()->obtenerContVoucherDetalleXVoucherId($voucherId);
        return $data;
    }

    public function obtenerDocumentoRelacionVisualizar() {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
        $data->configuracionEditable = MovimientoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);
        $data->emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

        $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        $data->dataAccionEnvio = MovimientoTipoNegocio::create()->obtenerMovimientoTipoAccionesVisualizacion($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataDocumentoAdjunto = DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);
        $data->dataDistribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
        $data->dataVoucherContable = ContVoucherDetalleNegocio::create()->obtenerVoucherDetalleXDocumentoId($documentoId, ContLibroNegocio::CLASIFICACION_COMPRAS);

        return $data;
    }

    public function exportarLibroDiario() {
        $criterios = $this->getParametro("criterios");
        $tipo = $this->getParametro("tipo");
        if ($tipo == 'excel') {
            return LibroDiarioNegocio::create()->obtenerLibroDiarioExcel($criterios);
        } elseif ($tipo == 'txt') {
            return LibroDiarioNegocio::create()->obtenerLibroDiarioTxt($criterios);
        }
    }

    public function registrarAsientoContable() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();
        $voucherId = $this->getParametro("voucherId");
        $contLibroId = $this->getParametro("libroId");
        $periodoId = $this->getParametro("periodoId");
        $monedaId = $this->getParametro("monedaId");
        $glosa = $this->getParametro("glosa");
        $distribucionContable = $this->getParametro("distribucionContable");
        return ContVoucherNegocio::create()->registrarContVoucherXLibroDiario($voucherId, $contLibroId, $periodoId, $monedaId, $glosa, $distribucionContable, $usuarioId);
    }

    public function obtenerTipoDeCambio() {
        $fecha = $this->getParametro("fecha");
        $fecha = TipoCambioNegocio::create()->formatearFechaBD($fecha);
        return TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fecha);
    }

    public function actualizarGlosaVoucher() {
        $voucherId = $this->getParametro("voucherId");
        $glosa = $this->getParametro("glosa");
        return ContVoucherNegocio::create()->actualizarContVoucherXId($voucherId, NULL, $glosa);
    }

    public function generarAsientosCierreApertura() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $anio = $this->getParametro("anio");
        $tipo = $this->getParametro("tipo");
        $banderaGenerar = $this->getParametro("banderaGenerar");
        return LibroDiarioNegocio::create()->generarAsientosCierreApertura($empresaId, $anio, $tipo, $usuarioId, $banderaGenerar);
    }

}
