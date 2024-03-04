<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/RegistroVentasNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';
//require_once __DIR__ . '/../../util/ImportacionExcel.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel/IOFactory.php';

class RegistroVentasControlador extends ControladorBase {

    public function obtenerConfiguracionInicial() {
        $empresaId = $this->getParametro("id_empresa");
        return RegistroVentasNegocio::create()->obtenerConfiguracionInicial($empresaId);
    }

    public function listarRegistroVentasXCriterios() {
        $criterios = $this->getParametro("criterios");
        return RegistroVentasNegocio::create()->listarRegistroVentasXCriterios($criterios);
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
        $data->dataVoucherContable = ContVoucherDetalleNegocio::create()->obtenerVoucherDetalleXDocumentoId($documentoId);

        return $data;
    }

    public function exportarRegistroVentas() {
        $criterios = $this->getParametro("criterios");
        $tipo = $this->getParametro("tipo");
        if ($tipo == 'excel') {
            return RegistroVentasNegocio::create()->obtenerRegistroVentasExcel($criterios);
        } elseif($tipo == 'txt') {
            return RegistroVentasNegocio::create()->obtenerRegistroVentasTxt($criterios);
        }
    }

    public function listar() {
        return InvPermValorizadoNegocio::create()->listar();
    }

    public function generarLibro() {
        $this->setTransaction();

        $file = $this->getParametro("file");
        $usuarioId = $this->getUsuarioId();
        $anio = $this->getParametro("anio");
        $mes = $this->getParametro("mes");
        // hora actual
        $fechaActual = new DateTime();
        $formatoFechaActual = $fechaActual->format("Ymdhis");

        $decode = Util::base64ToImage($file);

        $archivoNombre = "$anio$mes_$formatoFechaActual.xls";
        $direccion = __DIR__ . "/../../util/uploads/$archivoNombre";
        file_put_contents($direccion, $decode);

        return RegistroComprasNegocio::create()->genera($direccion, $archivoNombre, $anio, $mes, $usuarioId);
    }

}
