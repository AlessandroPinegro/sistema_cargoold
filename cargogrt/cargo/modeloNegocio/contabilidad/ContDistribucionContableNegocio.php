<?php


require_once __DIR__ . '/../../modelo/contabilidad/ContDistribucionContable.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContOperacionTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContVoucherNegocio.php';

class ContDistribucionContableNegocio extends ModeloNegocioBase {

    const COLUMNA_TIPODATO_BUSQUEDA = 'tipo';
    const TIPO_DUA_ID_FOB = "FOB";
    const TIPO_DUA_ID_CIF = "CIF";
    const DOCUMENTO_TIPO_ID_DUA = 256;
    const DOCUMENTO_TIPO_ID_INVOICE = 227;
    const DOCUMENTO_TIPO_ID_RECIBO_HONORARIO = 234; 
    const DOCUMENTO_TIPO_ID_DECLARACION_JURADA = 247;
    const DOCUMENTO_TIPO_OTROS = 248;
    const DOCUMENTO_TIPO_ID_COMPROBANTE_DETRACCION_COMPRA = 238;
    const DOCUMENTO_TIPO_ID_RECIBO_GASTO = 259;
    const DOCUMENTO_TIPO_ID_FACTURA_VENTA = 7;
    const DOCUMENTO_TIPO_ID_BOLETA_VENTA = 6;
    const DOCUMENTO_TIPO_ID_NOTA_CREDITO_VENTA = 61;
    const DOCUMENTO_TIPO_ID_NOTA_DEBITO_VENTA = 269;
    const DOCUMENTO_TIPO_ID_NOTA_CREDITO_COMPRA = 267;
    /**
     * 
     * @return ContDistribucionContableNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerContDistribucionContableXDocumentoId($documentoId) {
        return ContDistribucionContable::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
    }

    public function validarDistribucionContable($documentoId, $dataDistribucion, $contOperacionTipoId) {
        /*
          $dataOperacionTipo = ContOperacionTipoNegocio::create()->obtenerContOperacionTipoXId($contOperacionTipoId);

          $centroCostoObligatorio = $dataOperacionTipo[0]['requiere_centro_costo'];

          $camposDinamicos = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);

          $dataDocumento['montoAfecto'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

          $dataDocumento['montoFlete'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_FLETE_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

          $dataDocumento['montoSeguro'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_SEGURO_DOCUMENTO, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

          $dataDocumento['montoIgv'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_IGV, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

          $dataDocumento['montoTotal'] = Util::buscarArrayPorNombreColumnaIdentificador($camposDinamicos, DocumentoTipoNegocio::DATO_IMPORTE_TOTAL, self::COLUMNA_TIPODATO_BUSQUEDA, 'valor') * 1;

          $dataDocumento['montoNoAfecto'] = Util::redondearNumero(($dataDocumento['montoTotal'] - $dataDocumento['montoAfecto'] - $dataDocumento['montoIgv'] - $dataDocumento['montoFlete'] - $dataDocumento['montoSeguro']), 2);

          $montoMaximo = ($dataDocumento['montoAfecto'] > 0 ? $dataDocumento['montoAfecto'] + $dataDocumento['montoNoAfecto'] : $dataDocumento['montoTotal']);

          $montoTotal = 0;
          $porcentajeTotal = 0;
          if (ObjectUtil::isEmpty($dataDistribucion)) {
          throw new WarningException("No se obtuvo la información para registrar la distribución contable.");
          }

          foreach ($dataDistribucion as $indice => $linea) {
          if (ObjectUtil::isEmpty($linea['plan_contable_id'])) {
          throw new WarningException("Se debe ingresar la cuenta contable para la fila " . ($indice++) . ".");
          }

          if ($centroCostoObligatorio == '1' && ObjectUtil::isEmpty($linea['centro_costo_id'])) {
          throw new WarningException("Se debe ingresar el centro de costo para la fila " . ($indice++) . ".");
          }

          if (ObjectUtil::isEmpty($linea['monto']) || $linea['monto'] * 1 <= 0) {
          throw new WarningException("El monto en la fila " . ($indice++) . " debe ser mayor que cero.");
          } elseif ($linea['monto'] * 1 > $montoMaximo * 1) {
          throw new WarningException("El monto en la fila " . ($indice++) . " sobre pasa el monto máximo " . $montoMaximo . ".");
          }
          $montoTotal += $linea['monto'] * 1;

          if (ObjectUtil::isEmpty($linea['porcentaje']) || $linea['porcentaje'] * 1 <= 0) {
          throw new WarningException("El porcentaje en la fila " . ($indice++) . " debe ser mayor que cero.");
          } elseif ($linea['porcentaje'] * 1 > 100) {
          throw new WarningException("El porcentaje en la fila " . ($indice++) . " no debe ser mayor de lo permitido 100%.");
          }
          $porcentajeTotal += $linea['porcentaje'] * 1;
          }

          if (round($montoTotal, 2) != ($montoMaximo * 1)) {
          throw new WarningException('La suma de los montos debe ser ' . $montoMaximo . '.');
          }
          if (round($porcentajeTotal, 2) != 100) {
          throw new WarningException('La suma de porcentajes debe ser  100.00%.');
          } */
    }

    public function guardarContDistribucionContable($documentoId, $contOperacionTipoId, $dataDistribucion, $usuarioId) {
        foreach ($dataDistribucion as $indice => $linea) {
            $respuestaGuardarDistribucion = ContDistribucionContable::create()->guardarContDistribucionContable($linea['linea'], $documentoId, $contOperacionTipoId, $linea['plan_contable_id'], $linea['centro_costo_id'], $linea['monto'], $linea['porcentaje'], $usuarioId);
            if ($respuestaGuardarDistribucion[0]['vout_exito'] != Util::VOUT_EXITO) {
                throw new WarningException('Error al intentar registrar la distribución contable : ' . $respuestaGuardarDistribucion[0]['vout_mensaje'] . " en la fila" . (int($indice) + 1));
            }
        }
        return $respuestaGuardarDistribucion;
    }

    public function anularDistribucionContableXDocumentoId($documentoId) {
        return ContDistribucionContable::create()->anularDistribucionContableXDocumentoId($documentoId);
    }

}
