<?php

require_once __DIR__ . '/MovimientoNegocio.php';

class DocumentoEarNegocio extends MovimientoNegocio {

    /**
     * 
     * @return DocumentoEarNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];


        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        // 2. Obtenemos la moneda
        $monedaId = $criterios[0]['monedaId'];

        // 3. Obtenemos el estado negocio de pago
        $estadoNegocioPago = $criterios[0]['estadoNegocio'];

        // 4. Obtenemos serie y numero original de documento
        $serieDoc = $criterios[0]['serieDoc'];
        $numeroDoc = $criterios[0]['numeroDoc'];

//        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
//            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
//        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

//                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Documento::create()->obtenerDocumentosEarXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago, $serieDoc, $numeroDoc);
    }

    public function relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId) {
        //VALIDAR QUE NO RELACIONEN UN DOCUMENTO YA RELACIONADO
        $dataRel = DocumentoNegocio::create()->obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoIdOrigen, $documentoIdARelacionar);

        if (!ObjectUtil::isEmpty($dataRel)) {
            throw new WarningException('Documento a relacionar duplicado');
        }

        $respuesta = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoIdOrigen, $documentoIdARelacionar, 1, 1, $usuarioId);

        //VALIDAR QUE LA ORDEN DE COMPRA TENGA IMPORTE DISPONIBLE
        //TODOS LOS DOCUMENTOS GENERAN DEUDA SERAN ASOCIADOS A ORDEN DE COMRPA

        if ($respuesta[0]['documento_tipo_identificador_negocio'] != DocumentoTipoNegocio::IN_INVOICE_COMMERCIAL) {
            $validacion = DocumentoNegocio::create()->validarImportePago($documentoIdOrigen, $documentoIdARelacionar);
            if ($validacion[0]['vout_estado'] == 0) {
                throw new WarningException('El importe de los documentos asociados superan el importe total de la orden de compra');
            }
        }
        return $respuesta;
    }

}
