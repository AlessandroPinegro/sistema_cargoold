<?php

require_once __DIR__ . '/OperacionTipoNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/ExcelNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/MovimientoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/../../modelo/almacen/CostoCif.php';

/**
 * Description of OperacionNegocio
 *
 * @author Imagina
 */
class OperacionNegocio extends ModeloNegocioBase
{

    /**
     * 
     * @return OperacionNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function obtenerIdXOpcion($opcionId)
    {
        $operacionTipo = OperacionTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($operacionTipo)) {
            throw new WarningException("No se encontró la operación asociado a esta opción");
        }
        return $operacionTipo[0]["id"];
    }

    public function obtenerDocumentoTipo($opcionId, $usuarioId)
    {
        // obtenemos el id de la operacion tipo que utiliza la opcion
        $contador = 0;
        $operacionTipoId = $this->obtenerIdXOpcion($opcionId);
        $respuesta = new stdClass();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXOperacionTipo($operacionTipoId);
        if (!ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXOperacionTipo($operacionTipoId);
        }

        if (!ObjectUtil::isEmpty($respuesta->documento_tipo_dato)) {

            $tamanio = count($respuesta->documento_tipo_dato);
            for ($i = 0; $i < $tamanio; $i++) {
                switch ((int) $respuesta->documento_tipo_dato[$i]['tipo']) {
                    case 5:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Persona";
                        break;
                    case 6:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Código";
                        break;
                    case 7:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Serie";
                        break;
                    case 8:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Número";
                        break;
                    case 9:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de emisión";
                        break;
                    case 10:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de vencimiento";
                        break;
                    case 11:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de tentativa";
                        break;
                    case 12:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Descripción";
                        break;
                    case 13:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Comentario";
                        break;
                    case 14:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Importe";
                        break;
                    case 17:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Organizador Destino";
                        break;
                }
            }

            //            foreach ($respuesta->documento_tipo_dato as $documento) {
            //                $documento['descripcion'] = "hola";
            //            }


            foreach ($respuesta->documento_tipo_dato as $documento) {
                if ($documento['tipo'] == 4) {
                    $respuesta->documento_tipo_dato_lista[$contador]['id'] = $documento['id'];
                    $respuesta->documento_tipo_dato_lista[$contador]['data'] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($documento['id']);
                    $contador++;
                }
            }
        }

        $respuesta->dataCaja = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaCajaXUsuarioId($usuarioId);
        $respuesta->dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId(2, $usuarioId, $respuesta->dataAgencia[0]['id']);
        $cajaDefault = NULL;
        if (!ObjectUtil::isEmpty($dataAperturaCaja)) {
            $cajaDefault = $dataAperturaCaja[0]['caja_id'];
        }
        $respuesta->cajaDefault = $cajaDefault;
        $respuesta->persona_activa = array(); //PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    public function obtenerConfiguracionInicial($opcionId, $empresaId, $usuarioId)
    {
         $ip=$_SERVER['REMOTE_ADDR'];
      //obtener caja
         $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioId);
            $id_caja = $resultado_caja[0]['caja_id'];
       
            //obtener ip de la caja
        $ipCajaF= ACCaja::create()->obtenerAccajaIpxusuario($id_caja);
        $ipCaja=$ipCajaF[0]['ip_caja'];
        
          if($ipCaja!=$ip){
            throw new WarningException("Su ip actual no coincide con el de la caja.");
        }
        

// obtenemos el id del movimiento tipo que utiliza la opcion

        $operacionTipo = OperacionTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($operacionTipo)) {
            throw new WarningException("No se encontró la operación asociado a esta opción");
        }
        $operacionTipoId = $operacionTipo[0]["id"];
        $respuesta = new ObjectUtil();

        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXOperacionTipo($operacionTipoId);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("La operación no cuenta con tipos de documentos asociados");
        }
        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($respuesta->documento_tipo[0]["id"], $usuarioId);

        $respuesta->bien = null;
        $respuesta->organizador = null;
        $respuesta->operacionTipo = $operacionTipo;
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        //        $respuesta->cuentaContable = PlanContableNegocio::create()->obtenerTodo();
        //        $respuesta->centroCosto = CentroCostoNegocio::create()->listarCentroCosto($empresaId);

        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;
        $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        return $respuesta;
    }

    public function guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $comentario, $descripcion, $monedaId, $documentoARelacionar, $valorCheck, $periodoId, $acCajaId = NULL)
    {

        // Insertamos el documento
        $movimientoId = null;

        $documento = DocumentoNegocio::create()->guardarCajaBancos($documentoTipoId, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, $descripcion, null, null, null, $periodoId, null, null, null, null, $acCajaId);
        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException("No se pudo guardar el documento");
        }

        //si el documento se a copiado guardamos las relaciones
        foreach ($documentoARelacionar as $documentoRelacion) {
            if (!ObjectUtil::isEmpty($documentoRelacion['documentoId'])) {
                DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId);
            }
        }

        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");
        return $documentoId;
    }

    public function obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $agenciaId = null;
        $cajaId = null;
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

        //obtnemos el id del tipo de operacion
        $responseOperacionTipo = OperacionTipo::create()->obtenerXOpcion($opcionId);
        $operacionTipoId = $responseOperacionTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        if (!ObjectUtil::isEmpty($documentoTipoArray)){
            $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        }
        
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            $valor = $item['valor'];
            if (!ObjectUtil::isEmpty($valor)) {
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
                if ($item['descripcion'] == "Agencia") {
                    $agenciaId = $valor;
                } elseif ($item['descripcion']  == "Caja") {
                    $cajaId = $valor;
                }
            }
        }
        // return "operacionTipoId ".$operacionTipoId. ' documentoTipoIds '.$documentoTipoIds. ' personaId '. $personaId .' codigo '. $codigo.'  serie '.  $serie.' numero '.  $numero.' fechaEmisionDesde '.  $fechaEmisionDesde.'  fechaEmisionHasta '.  $fechaEmisionHasta.' fechaVencimientoDesde '.  $fechaVencimientoDesde.' fechaVencimientoHasta '.  $fechaVencimientoHasta.' fechaTentativaDesde '.  $fechaTentativaDesde.' fechaTentativaHasta '.  $fechaTentativaHasta.' columnaOrdenar '.  $columnaOrdenar.' formaOrdenar '.  $formaOrdenar.' elemntosFiltrados '.  $elemntosFiltrados.' start '.  $start;
        return OperacionTipo::create()->obtenerDocumentosXCriterios($operacionTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $agenciaId, $cajaId);
    }

    public function obtenerDocumentosXCriteriosExportar($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $agenciaId = null;
        $cajaId = null;
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

        //obtnemos el id del tipo de operacion
        $responseOperacionTipo = OperacionTipo::create()->obtenerXOpcion($opcionId);
        $operacionTipoId = $responseOperacionTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        //        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
        //            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
        //        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);
        // $columnaOrdenarIndice = $order[0]['column'];
        // $formaOrdenar = $order[0]['dir'];
        // $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

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

                if ($item['descripcion'] == "Agencia") {
                    $agenciaId = $valor;
                } elseif ($item['descripcion']  == "Caja") {
                    $cajaId = $valor;
                }
            }
        }
        // return "operacionTipoId ".$operacionTipoId. ' documentoTipoIds '.$documentoTipoIds. ' personaId '. $personaId .' codigo '. $codigo.'  serie '.  $serie.' numero '.  $numero.' fechaEmisionDesde '.  $fechaEmisionDesde.'  fechaEmisionHasta '.  $fechaEmisionHasta.' fechaVencimientoDesde '.  $fechaVencimientoDesde.' fechaVencimientoHasta '.  $fechaVencimientoHasta.' fechaTentativaDesde '.  $fechaTentativaDesde.' fechaTentativaHasta '.  $fechaTentativaHasta.' columnaOrdenar '.  $columnaOrdenar.' formaOrdenar '.  $formaOrdenar.' elemntosFiltrados '.  $elemntosFiltrados.' start '.  $start;
        return OperacionTipo::create()->obtenerDocumentosXCriterios($operacionTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columns, $order, $elemntosFiltrados, $start, $agenciaId, $cajaId);
    }

    public function obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $agenciaId = null;
        $cajaId = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de operacion
        $responseOperacionTipo = OperacionTipo::create()->obtenerXOpcion($opcionId);
        $operacionTipoId = $responseOperacionTipo[0]['id'];

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
                if ($item['descripcion'] == "Agencia") {
                    $agenciaId = $valor;
                } elseif ($item['descripcion']  == "Caja") {
                    $cajaId = $valor;
                }
            }
        }
        return OperacionTipo::create()->obtenerCantidadDocumentosXCriterios($operacionTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $agenciaId, $cajaId);
    }

    public function visualizarDocumento($documentoId)
    {
        $respuesta = new ObjectUtil();

        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $respuesta->comentarioDocumento = DocumentoNegocio::create()->obtenerComentarioDocumento($documentoId);

        $res = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res[0]['movimiento_id'])) {
            $respuesta->detalleDocumento = MovimientoBien::create()->obtenerXIdMovimiento($res[0]['movimiento_id']);

            $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
            $respuesta->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
            $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        }

        return $respuesta;
    }

    public function anular($documentoId, $documentoEstadoId, $usuarioId)
    {
        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);
        if ($respuestaAnular[0]['vout_exito'] == 1) {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);

            $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
            if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                throw new WarningException("No se Actualizo Documento estado");
            }
        } else {
            throw new WarningException($respuestaAnular[0]['vout_mensaje']);
        }
    }

    public function exportarReporteOperacionExcel($data, $opcionId)
    {
        // obtenemos el id del movimiento tipo que utiliza la opcion

        $operacionTipo = OperacionTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($operacionTipo)) {
            throw new WarningException("No se encontró la operación asociado a esta opción");
        }
        $operacionTipoId = $operacionTipo[0]["id"];
        $respuesta = new ObjectUtil();

        $documentoTipoData = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXOperacionTipo($operacionTipoId);
        if (ObjectUtil::isEmpty($documentoTipoData)) {
            throw new WarningException("La operación no cuenta con tipos de documentos asociados");
        }

        return ExcelNegocio::create()->generarReporteOperacion($opcionId, $data, $documentoTipoData);
    }

    public function getUserEmailByUserId($id)
    {
        return MovimientoNegocio::create()->getUserEmailByUserId($id);
    }

    //Area de funciones para copiar documento 

    function ConfiguracionesBuscadorCopiaDocumento($opcionId, $empresaId, $documentoTipoId)
    {

        $tipoIds = '(0),(1),(4),(5),(7),(8)';
        
        $operacionTipo = OperacionTipoNegocio::create()->obtenerXOpcion($opcionId);
        $operacionTipoId = $operacionTipo[0]["id"];
        $respuesta = new stdClass();
        $respuesta->documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXOperacionTipoXDocumentoTipo($operacionTipoId, $documentoTipoId, $empresaId, $tipoIds);
        $respuesta->persona = Array(); //PersonaNegocio::create()->obtenerActivas();

        return $respuesta;
    }

    function buscarDocumentoACopiar($criterios, $elementosFiltrados, $columnas, $orden, $tamanio, $opcionId, $documentoTipoId)
    {

        $empresaId = $criterios['empresa_id'];
        $documentoTipoIds = $criterios['documento_tipo_ids'];
        $personaId = $criterios['persona_id'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $fechaEmisionInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_inicio']);
        $fechaEmisionFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_fin']);
        $fechaVencimientoInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_inicio']);
        $fechaVencimientoFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_fin']);

        $operacionTipo = OperacionTipoNegocio::create()->obtenerXOpcion($opcionId);
        $operacionTipoId = $operacionTipo[0]["id"];
        if(!ObjectUtil::isEmpty($documentoTipoIds)){
            $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);
        }
        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new stdClass();

        $respuesta->data = OperacionTipo::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $operacionTipoId, $documentoTipoId);

        $respuesta->contador = OperacionTipo::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $operacionTipoId, $documentoTipoId);

        return $respuesta;
    }

    function obtenerDetalleDocumentoACopiar($documentoOrigenId, $documentoDestinoId, $documentoId, $opcionId, $documentoRelacionados)
    {
        $respuesta = new ObjectUtil();
        $datoDocumento = DocumentoNegocio::create()->obtenerDataDocumentoACopiar($documentoDestinoId, $documentoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($datoDocumento)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->dataDocumento = $datoDocumento;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId);

        if ($documentoDestinoId != $documentoOrigenId) {
            $respuesta->documentoCopiaRelaciones = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentoCopiaRelaciones = 1;
        }

        return $respuesta;
    }
}
