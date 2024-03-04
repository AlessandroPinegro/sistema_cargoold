<?php

require_once __DIR__ . '/../../modelo/almacen/PruebaCopia.php';

require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/DocumentoDatoValorNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../util/NumeroALetra/EnLetras.php';

class PruebaCopiaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return MovimientoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($opcionId, $empresaId) {
        // obtenemos el id del movimiento tipo que utiliza la opcion

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }
        $movimientoTipoId = $movimientoTipo[0]["id"];

        $respuesta = new ObjectUtil();
//        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
        }
        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($respuesta->documento_tipo[0]["id"]);

        $respuesta->bien = BienNegocio::create()->obtenerActivos($empresaId);
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);
        return $respuesta;
    }

    public function obtenerDocumentoTipo($opcionId) {
        // obtenemos el id del movimiento tipo que utiliza la opcion
        $contador = 0;
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $respuesta = new ObjectUtil();
//        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (!ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXMovimientoTipo($movimientoTipoId);
        }

        if (!ObjectUtil::isEmpty($respuesta->documento_tipo_dato)) {

            $tamanio = count($respuesta->documento_tipo_dato);
            for ($i = 0; $i < $tamanio; $i++) {
                switch ((int) $respuesta->documento_tipo_dato[$i]['tipo']) {
                    case 5 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Persona";
                        break;
                    case 6 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Código";
                        break;
                    case 7 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Serie";
                        break;
                    case 8 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Número";
                        break;
                    case 9 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de emisión";
                        break;
                    case 10 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de vencimiento";
                        break;
                    case 11 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de tentativa";
                        break;
                    case 12 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Descripción";
                        break;
                    case 13 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Comentario";
                        break;
                    case 14 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Importe";
                        break;
                }
            }

            foreach ($respuesta->documento_tipo_dato as $documento) {
                $documento['descripcion'] = "hola";
            }


            foreach ($respuesta->documento_tipo_dato as $documento) {
                if ($documento['tipo'] == 4) {
                    $respuesta->documento_tipo_dato_lista[$contador]['id'] = $documento['id'];
                    $respuesta->documento_tipo_dato_lista[$contador]['data'] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($documento['id']);
                    $contador++;
                }
            }
        }

        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    public function obtenerIdXOpcion($opcionId) {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }
        return $movimientoTipo[0]["id"];
    }

    public function guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck) {
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        // 1. Insertamos el movimiento
        $movimiento = PruebaCopia::create()->guardar($movimientoTipoId, 1, $usuarioId);
        $movimientoId = $this->validateResponse($movimiento);
        if (ObjectUtil::isEmpty($movimientoId) || $movimientoId < 1) {
            throw new WarningException("No se pudo guardar el movimiento");
        }
        // 2. Insertamos el documento
        $documento = DocumentoNegocio::create()->guardar($documentoTipoId, $movimientoId, null, $camposDinamicos, 1, $usuarioId);
        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException("No se pudo guardar el documento");
        }

        // 3. Insertamos el detalle
        foreach ($detalle as $item) {
            $movimientoBien = MovimientoBien::create()->guardar($movimientoId, $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $item["precio"], 1, $usuarioId);
            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }
        }

        //4. Insertar documento_documento_estado
        $movimientoTipoDocumentoTipo = PruebaCopia::create()->sp_movimiento_tipo_documento_tipo_XMovimientoTipoXDocumentoTipo($movimientoTipoId, $documentoTipoId);
        $documento_estado = $movimientoTipoDocumentoTipo['0']['documento_estado_id'];
        if (ObjectUtil::isEmpty($documento_estado)) {
            throw new WarningException("No se encontro estado en movimiento tipo documento tipo");
        }
        DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioId);

        //si el documento se a copiado guardamos las relaciones
        foreach ($documentoARelacionar as $documentoRelacion) {
            if (!empty($documentoRelacion['documentoId'])) {
                DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId);
            }
        }

        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");
        return $documentoId;
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
        $responseMovimientoTipo = PruebaCopia::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];


        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

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
        return PruebaCopia::create()->obtenerDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function ObtenerTotalDeRegistros() {
        return PruebaCopia::create()->ObtenerTotalDeRegistros();
    }

    public function obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start) {
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
        $responseMovimientoTipo = PruebaCopia::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];


        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

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
        return PruebaCopia::create()->obtenerCantidadDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    private function getDetalle($organizador, $cantidad, $descripcion, $precioUnitario, $importe, $unidadMedida) {

        $detalle = new stdClass();
        $detalle->organizador = $organizador;
        $detalle->cantidad = $cantidad;
        $detalle->descripcion = $descripcion;
        $detalle->precioUnitario = $precioUnitario;
        $detalle->importe = $importe;
        $detalle->unidadMedida = $unidadMedida;
        return $detalle;
    }

    public function obtenerMovimientoTipoAcciones($opcionId) {
        $responseMovimientoTipo = PruebaCopia::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];
        return PruebaCopia::create()->obtenerMovimientoTipoAcciones($movimientoTipoId);
    }

    public function enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle) {

        $nombre_fichero = __DIR__ . '/../../vistas/com/movimiento/plantillas/' . $documentoTipoId . ".php";

        if (!file_exists($nombre_fichero)) {
            throw new WarningException("No existe el archivo del documento para imprimir.");
        }

        $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle);

        return $this->imprimir($documentoId, $documentoTipoId);
    }

    public function imprimir($documentoId, $documentoTipoId) {
        $igv = 18;
        $arrayDetalle = array();
        $respuesta = new ObjectUtil();

        $respuesta->documentoTipoId = $documentoTipoId;
        $datoDocumento = DocumentoNegocio::create()->obtenerXId($documentoId, $documentoTipoId);

        if (ObjectUtil::isEmpty($datoDocumento)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->dataDocumento = $datoDocumento;

        $movimientoId = $datoDocumento[0]["movimiento_id"];

        $documentoDatoValor = DocumentoDatoValorNegocio::create()->obtenerXIdDocumento($documentoId);

        $respuesta->documentoDatoValor = $documentoDatoValor;

        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        if (ObjectUtil::isEmpty($documentoDetalle)) {
            throw new WarningException("No se encontró detalles de este documento");
        }

        $total = 0.00;
        foreach ($documentoDetalle as $detalle) {
            $subTotal = $detalle['cantidad'] * $detalle['valor_monetario'];
            array_push($arrayDetalle, $this->getDetalle("", $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['valor_monetario'], $subTotal, $detalle['unidad_medida_descripcion']));
            $total += $subTotal;
        }

        $respuesta->detalle = $arrayDetalle;

//        $respuesta->subTotal = ($total / (($igv * 0.01) + 1));

        $respuesta->valorIgv = $igv;
        $enLetra = new EnLetras();
        $respuesta->totalEnTexto = $enLetra->ValorEnLetras($datoDocumento[0]['total']);

        return $respuesta;
    }

    public function anular($documentoId, $documentoEstadoId, $usuarioId) {
        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);
        $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
        if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
            throw new WarningException("No se Actualizo Documento estado");
        }
        if ($respuestaAnular[0]['vout_exito'] == 1) {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);
        } else {
            throw new WarningException("Error al anular el documento");
        }
    }

    public function aprobar($documentoId, $documentoEstadoId, $usuarioId) {
//        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);
        $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
        if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
            throw new WarningException("No se Actualizo Documento estado");
        } else {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);
        }
    }

    public function visualizarDocumento($documentoId, $movimientoId) {

        $arrayDetalle = array();
        $respuesta = new ObjectUtil();

        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);

        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        if (ObjectUtil::isEmpty($documentoDetalle)) {
            throw new WarningException("No se encontró detalles de este documento");
        }

        $total = 0.00;

        foreach ($documentoDetalle as $detalle) {
            $subTotal = $detalle['cantidad'] * $detalle['valor_monetario'];
            array_push($arrayDetalle, $this->getDetalle($detalle['organizador_descripcion'], $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['valor_monetario'], $subTotal, $detalle['unidad_medida_descripcion']));
            $total += $subTotal;
        }
        $respuesta->detalleDocumento = $arrayDetalle;

        return $respuesta;
    }

    public function obtenerStockAControlar($opcionId, $bienId, $organizadorId, $unidadMedidaId, $cantidad) {
        if (ObjectUtil::isEmpty($organizadorId))
            return -1;
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (!ObjectUtil::isEmpty($movimientoTipo)) {
            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
                $bien = BienNegocio::create()->getBien($bienId);
                if ($bien[0]['bien_tipo_id'] == -1) {
                    return -1;
                } else {
                    // si es salida validamos si tiene stock
                    $stock = BienNegocio::create()->obtenerStockBase($organizadorId, $bienId);
                    $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;
                    if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                        throw new WarningException("No cuenta con stock suficiente en el organizador seleccionado.<br>Disponible: $stockControlar");
                    } else {
                        return $stockControlar;
                    }
                }
            } else {
                return -1;
            }
        } else {
            return 0;
        }
    }

    //Area de funciones para copiar documento las cuales ire haciendo en 
    //el transcurso de la semana

    function ConfiguracionesBuscadorCopiaDocumento($empresaId) {

        $tipoIds = '(0),(1),(4)';
        $respuesta = new ObjectUtil();

        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($empresaId, $tipoIds);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();

        return $respuesta;
    }

    function buscarDocumentoACopiar($criterios, $elementosFiltrados, $columnas, $orden, $tamanio) {

        $empresaId = $criterios['empresa_id'];
        $documentoTipoIds = $criterios['documento_tipo_ids'];
        $personaId = $criterios['persona_id'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $fechaEmisionInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_inicio']);
        $fechaEmisionFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_fin']);
        $fechaVencimientoInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_inicio']);
        $fechaVencimientoFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_fin']);

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = PruebaCopia::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio);

        $respuesta->contador = PruebaCopia::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar);

        return $respuesta;
    }

    function obtenerDetalleDocumentoACopiar($documentoOrigenId, $documentoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $respuesta = new ObjectUtil();

        $datoDocumento = DocumentoNegocio::create()->obtenerDataDocumentoACopiar($documentoDestinoId, $documentoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($datoDocumento)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->dataDocumento = $datoDocumento;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId);
        $respuesta->detalleDocumento = $this->obtenerDetalleDocumentoACopiarSoloDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $respuesta;
    }

    private function validarStockDocumento($documentoDetalle, $movimientoTipoId) {

        $tamanhoDetalle = count($documentoDetalle);
        $organizadoresEmpresa = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);

        for ($i = 0; $i < $tamanhoDetalle; $i++) {

            if ($this->verificarOrganizadorPertenece($documentoDetalle[$i]['organizador_id'], $organizadoresEmpresa)) {
                $stock = BienNegocio::create()->obtenerStockBase($documentoDetalle[$i]['organizador_id'], $documentoDetalle[$i]['bien_id']);
                $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;
                if ((floatval($stockControlar) - floatval($documentoDetalle[$i]['cantidad'])) < 0) {
                    $stockOrganizadores = BienNegocio::create()->obtenerStockOrganizadoresXEmpresa(
                            $documentoDetalle[$i]['bien_id'], $documentoDetalle[$i]['unidad_medida_id'], $movimientoTipoId);

                    $documentoDetalle[$i]['stock_organizadores'] = $stockOrganizadores;
                } else {
                    $documentoDetalle[$i]['stock_organizadores'] = null;
                }
            } else {
                $stockOrganizadores = BienNegocio::create()->obtenerStockOrganizadoresXEmpresa(
                        $documentoDetalle[$i]['bien_id'], $documentoDetalle[$i]['unidad_medida_id'], $movimientoTipoId);

                $documentoDetalle[$i]['stock_organizadores'] = $stockOrganizadores;
            }
        }

        return $documentoDetalle;
    }

    function verificarOrganizadorPertenece($organizador, $organizadores) {
        if (empty($organizador)) {
            return false;
        }
        $bandera = false;
        foreach ($organizadores as $org) {
            if ($org['id'] == $organizador) {
                $bandera = true;
            }
        }

        return $bandera;
    }

    function obtenerDetalleDocumentoACopiarSoloDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $banderaMerge = 0;
        $arrayDetalle = array();

        $tamanhoArrayRelacionado = count($documentoRelacionados);
        if (!empty($movimientoId) && !empty($documentoId)) {
            $documentoRelacionados[$tamanhoArrayRelacionado]['movimientoId'] = $movimientoId;
            $documentoRelacionados[$tamanhoArrayRelacionado]['documentoId'] = $documentoId;
        }
        foreach ($documentoRelacionados as $documentoRelacion) {
            $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);

            $tamanhioArrayDetalle = count($arrayDetalle);

            foreach ($documentoDetalle as $detalle) {
                $i = 0;
                while ($i < $tamanhioArrayDetalle && $banderaMerge == 0) {
                    if ($detalle['bien_id'] == $arrayDetalle[$i]['bien_id'] && $detalle['unidad_medida_id'] == $arrayDetalle[$i]['unidad_medida_id']) {
                        $arrayDetalle[$i]['cantidad'] = $arrayDetalle[$i]['cantidad'] + $detalle['cantidad'];
                        $arrayDetalle[$i]['valor_monetario'] = $detalle['valor_monetario'];
                        $banderaMerge = 1;
                    }

                    $i++;
                }

                if ($banderaMerge == 0) {
                    array_push($arrayDetalle, $this->getDocumentoACopiarMerge(
                                    $detalle['organizador_descripcion'], $detalle['organizador_id'], $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['bien_id'], $detalle['valor_monetario'], $detalle['unidad_medida_id'], $detalle['unidad_medida_descripcion']
                    ));
                }
                $banderaMerge = 0;
            }
            $banderaMerge = 0;
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }
        $movimientoTipoId = $movimientoTipo[0]["id"];

//        $respuesta = new ObjectUtil();
        return $this->validarStockDocumento($arrayDetalle, $movimientoTipoId);
//        return $respuesta;
    }

    private function getDocumentoACopiarMerge($organizadorDescripcion, $organizadorId, $cantidad, $bienDescripcion, $bienId, $valorMonetario, $unidadMedidaId, $unidadMedidaDescripcion) {

        $detalle = array(
            "organizador_descripcion" => $organizadorDescripcion,
            "organizador_id" => $organizadorId,
            "cantidad" => $cantidad,
            "bien_descripcion" => $bienDescripcion,
            "bien_id" => $bienId,
            "unidad_medida_id" => $unidadMedidaId,
            "unidad_medida_descripcion" => $unidadMedidaDescripcion,
            "valor_monetario" => $valorMonetario
        );

        return $detalle;
    }

    public function obtenerDocumentosRelacionados($documentoId) {

        return DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($documentoId);
    }

//    public function detalleDocumentoACopiarSoloDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados) {
//
//        $respuesta.
//        function obtenerDetalleDocumentoACopiarSoloDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados)
//    }
//    
}
