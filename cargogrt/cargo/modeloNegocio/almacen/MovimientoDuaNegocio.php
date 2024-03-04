<?php

require_once __DIR__ . '/../../modelo/almacen/CostoCif.php';
require_once __DIR__ . '/../../modelo/almacen/Ear.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class MovimientoDuaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return MovimientoDuaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerPlanillaImportacion($documentoId) {
//        $dataDoc=  DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $dataPlanillaImp = DocumentoNegocio::create()->obtenerPlanillaImportacionXDocumentoId($documentoId);

        //CONSTRUIR DATOS PARA PLANILLA DE IMPORTACION
        $planillaImportacion = array();
        foreach ($dataPlanillaImp as $ind => $item) {
            $valor = new stdClass();
            $valor->indice = $ind + 1;
            $valor->persona_nombre_completo = $item['persona_nombre_completo'];
            $valor->fecha_emision = $item['fecha_emision'];
            $valor->comentario_doc = $item['comentario_doc'];

            $tc = $item['tipo_cambio'];
            if ($item['moneda_id'] == 4) {
                $valor->valor_venta_dolar = $item['valor_venta'];
                $valor->igv_dolar = $item['igv'];
                $valor->total_doc_dolar = $item['total_doc'];

                $valor->valor_venta_soles = $item['valor_venta'] * $tc;
                $valor->igv_soles = $item['igv'] * $tc;
                $valor->total_doc_soles = $item['total_doc'] * $tc;
            } else {
                $valor->valor_venta_dolar = $item['valor_venta'] / $tc;
                $valor->igv_dolar = $item['igv'] / $tc;
                $valor->total_doc_dolar = $item['total_doc'] / $tc;

                $valor->valor_venta_soles = $item['valor_venta'];
                $valor->igv_soles = $item['igv'];
                $valor->total_doc_soles = $item['total_doc'];
            }
            $valor->tipo_cambio = $item['tipo_cambio'];
            $valor->serie_numero = $item['serie_numero'];

            array_push($planillaImportacion, $valor);
        }

        $resultado->planillaImportacion = $planillaImportacion;

        //CONSTRUIR PARA OBTENER PRECIO DE COSTO POR UNIDAD
        $dataCostoUnitario = CostoCif::create()->obtenerCostoUnitarioDuaXDocumentoId($documentoId);
        $monedaId = $dataCostoUnitario[0]['moneda_id'];

        if ($monedaId == 4) {
            $dataCostoUnitarioSoles = $dataCostoUnitario;
            foreach ($dataPlanillaImp as $index => $item) {
                $tc = $item['tipo_cambio'];
                $dataCostoDocumento = CostoCif::create()->obtenerCostoCifDocumentoXDocumentoId($item['id']);

                if (!ObjectUtil::isEmpty($dataCostoDocumento)) {
                    //COMBINO COSTOS UNITARIOS CON DOCUMENTOS
                    foreach ($dataCostoUnitario as $indCU => $itemCU) {
                        if (ObjectUtil::isEmpty($planillaSoles[$indCU])) {
                            $planillaSoles[$indCU] = 0;
                        }
                        foreach ($dataCostoDocumento as $indCD => $itemCD) {
                            if ($itemCU['costo_cif_id'] == $itemCD['costo_cif_id']) {
                                $dataCostoUnitario[$indCU]['indice'] = $indCU + 1;
                                $dataCostoUnitario[$indCU]['doc_valor_' . $index] = $itemCD['valor'];
                                $dataCostoUnitarioSoles[$indCU]['indice'] = $indCU + 1;
                                $dataCostoUnitarioSoles[$indCU]['doc_valor_' . $index] = $itemCD['valor'] * $tc;
                                $planillaSoles[$indCU] = $planillaSoles[$indCU] + $itemCD['valor'] * $tc;
                            }
                        }

                        $dataCostoUnitarioSoles[$indCU]['planilla'] = $planillaSoles[$indCU];
                        $dataCostoUnitarioSoles[$indCU]['imponible'] = $itemCU['imponible'] * $itemCU['cambio_personalizado'];
                        $dataCostoUnitarioSoles[$indCU]['costo'] = $planillaSoles[$indCU] + $itemCU['imponible'] * $itemCU['cambio_personalizado'];
                        $dataCostoUnitarioSoles[$indCU]['costo_unitario_imp'] = $dataCostoUnitarioSoles[$indCU]['costo'] / $dataCostoUnitarioSoles[$indCU]['cantidad'];
                        $dataCostoUnitarioSoles[$indCU]['costo_igv'] = $dataCostoUnitarioSoles[$indCU]['costo_unitario_imp'] * 0.18;
                        $dataCostoUnitarioSoles[$indCU]['costo_unitario'] = $dataCostoUnitarioSoles[$indCU]['costo_unitario_imp'] * 1.18;
                    }
                } else {
                    $dataCostoUnitario = null;
                    $dataCostoUnitarioSoles = null;
                }
            }

            $resultado->costoUnitario = $dataCostoUnitario; //DOLARES
            $resultado->costoUnitarioSoles = $dataCostoUnitarioSoles;
        } else {
            //SOLES
            $dataCostoUnitarioDolares = $dataCostoUnitario;
            foreach ($dataPlanillaImp as $index => $item) {
                $tc = $item['tipo_cambio'];
                $dataCostoDocumento = CostoCif::create()->obtenerCostoCifDocumentoXDocumentoId($item['id']);

                if (!ObjectUtil::isEmpty($dataCostoDocumento)) {
                    //COMBINO COSTOS UNITARIOS CON DOCUMENTOS
                    foreach ($dataCostoUnitario as $indCU => $itemCU) {
                        if (ObjectUtil::isEmpty($planillaDolar[$indCU])) {
                            $planillaDolar[$indCU] = 0;
                        }
                        foreach ($dataCostoDocumento as $indCD => $itemCD) {
                            if ($itemCU['costo_cif_id'] == $itemCD['costo_cif_id']) {
                                $dataCostoUnitario[$indCU]['indice'] = $indCU + 1;
                                $dataCostoUnitario[$indCU]['doc_valor_' . $index] = $itemCD['valor'];
                                $dataCostoUnitarioDolares[$indCU]['indice'] = $indCU + 1;
                                $dataCostoUnitarioDolares[$indCU]['doc_valor_' . $index] = $itemCD['valor'] / $tc;
                                $planillaDolar[$indCU] = $planillaDolar[$indCU] + $itemCD['valor'] / $tc;
                            }
                        }

                        $dataCostoUnitarioDolares[$indCU]['planilla'] = $planillaDolar[$indCU];
                        $dataCostoUnitarioDolares[$indCU]['imponible'] = $itemCU['imponible'] * $itemCU['cambio_personalizado'];
                        $dataCostoUnitarioDolares[$indCU]['costo'] = $planillaDolar[$indCU] + $itemCU['imponible'] * $itemCU['cambio_personalizado'];
                        $dataCostoUnitarioDolares[$indCU]['costo_unitario_imp'] = $dataCostoUnitarioDolares[$indCU]['costo'] / $dataCostoUnitarioDolares[$indCU]['cantidad'];
                        $dataCostoUnitarioDolares[$indCU]['costo_igv'] = $dataCostoUnitarioDolares[$indCU]['costo_unitario_imp'] * 0.18;
                        $dataCostoUnitarioDolares[$indCU]['costo_unitario'] = $dataCostoUnitarioDolares[$indCU]['costo_unitario_imp'] * 1.18;
                    }
                } else {
                    $dataCostoUnitario = null;
                    $dataCostoUnitarioDolares = null;
                }
            }

            $resultado->costoUnitario = $dataCostoUnitarioDolares; //DOLARES
            $resultado->costoUnitarioSoles = $dataCostoUnitario;
        }

        return $resultado;
    }

    public function actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId) {
        return CostoCif::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId);
    }

    public function obtenerContabilizacion($data) {
        $fleteId = 1527;
        $fleteAereoId = 1567;
        $seguroId = 1528;
        $thcId = 1765; //nube: 1765 -> THC
        $adValoremId = 1766; //nube: 1766 -> Ad Valorem
        $comisionId = 1972;
        $otroCostoId = 2132;

        $resumen = new stdClass();
        $resumen->fob = 0.0;
        $resumen->cif = 0.0;
        $resumen->fleteSunat = 0.0;
        $resumen->imponible = 0.0;
        $resumen->total = 0.0;
        $resumen->igv = 0.0;
        $resumen->percepcion = 0.0;
        $resumen->tc = 0.0;

        $resumen->flete = 0.0;
        $resumen->seguro = 0.0;
        $resumen->adValorem = 0.0;
        $resumen->thc = 0.0;
        $resumen->comision = 0.0;
        $resumen->otroCosto = 0.0;

        if (!ObjectUtil::isEmpty($data) || !ObjectUtil::isEmpty($data->dataDocumento) || !ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->dataDocumento as $key => $item) {
                switch ($item["tipo"] * 1) {
                    case 14:
                        $resumen->total = $item["valor"] * 1;
                        break;
                    case 15:
                        $resumen->igv = $item["valor"] * 1;
                        break;
                    case 16:
                        $resumen->imponible = $item["valor"] * 1;
                        break;
                    case 19:
                        $resumen->percepcion = $item["valor"] * 1;
                        break;
                    case 24:
                        $resumen->tc = 0;
                        break;
                    case 28:
                        $resumen->cif = $item["valor"] * 1;
                        break;
                    case 29:
                        $resumen->fob = $item["valor"] * 1;
                        break;
                    case 32:
                        $resumen->flete = $item["valor"] * 1;
                        break;
                    case 33:
                        $resumen->seguro = $item["valor"] * 1;
                        break;
//                    case 30:
//                        $resumen->fleteSunat = $item["valor"]*1;
//                        break; 
                }
            }

            foreach ($data->detalleDocumento as $key => $item) {
                switch ($item->bienId * 1) {
                    case $fleteId:
                    case $fleteAereoId:
                        $resumen->flete += $item->importe;
                        unset($data->detalleDocumento[$key]);
                        break;
                    case $seguroId:
                        $resumen->seguro = $item->importe;
                        unset($data->detalleDocumento[$key]);
                        break;
                    case $thcId:
                        $resumen->thc = $item->importe;
                        unset($data->detalleDocumento[$key]);
                        break;
                    case $comisionId:
                        $resumen->comision = $item->importe;
                        unset($data->detalleDocumento[$key]);
                        break;
                    case $otroCostoId:
                        $resumen->otroCosto = $item->importe;
                        unset($data->detalleDocumento[$key]);
                        break;
//                    case $adValoremId:
//                        $resumen->adValorem = $item->importe;
//                        unset($data->detalleDocumento[$key]);
//                        break;
                }
                $resumen->adValorem += $item->adValorem;
            }
            $data->resumen = $resumen;
        }
        return $data;
    }

    public function generarPorDocumentoId($documentoId, $usuarioId) {
        $data = CostoCif::create()->generarPorDocumentoId($documentoId, $usuarioId);
        $this->validateResponse($data);
        return $data;
    }
    
     public function eliminarCostoCifXDocumentoId($documentoId) {
        return CostoCif::create()->eliminarCostoCifXDocumentoId($documentoId);                    
    }


    public function obtenerCostoCifPorMovimientoId($movimientoId) {
        $data = CostoCif::create()->obtenerPorMovimientoId($movimientoId);
        $cif = new stdClass();
        if (!ObjectUtil::isEmpty($data)) {
            if ($data[0]["moneda_id"] * 1 == 4) {
                $cif->dolares = $data;
                $tc = $data[0]["cambio_personalizado"];
                // obtenemos la data en soles
                foreach ($data as $key => $item) {
                    $cif->soles[$key]["id"] = $item["id"];
                    $cif->soles[$key]["bien_descripcion"] = $item["bien_descripcion"];
                    $cif->soles[$key]["fob"] = $item["fob"] * $tc;
                    $cif->soles[$key]["flete"] = $item["flete"] * $tc;
                    $cif->soles[$key]["seguro"] = $item["seguro"] * $tc;
                    $cif->soles[$key]["comision_bancaria"] = $item["comision_bancaria"] * $tc;
                    $cif->soles[$key]["otro_costo"] = $item["otro_costo"] * $tc;
                    $cif->soles[$key]["cif"] = $item["cif"] * $tc;
                    $cif->soles[$key]["ad_valorem"] = $item["ad_valorem"] * $tc;
                    $cif->soles[$key]["imponible"] = $item["imponible"] * $tc;
                    $cif->soles[$key]["igv"] = $item["igv"] * $tc;
                    $cif->soles[$key]["total"] = $item["total"] * $tc;
                }
            } else {
                $cif->soles = $data;
                foreach ($data as $key => $item) {
                    $cif->dolares[$key]["id"] = $item["id"];
                    $cif->dolares[$key]["bien_descripcion"] = $item["bien_descripcion"];
                    $cif->dolares[$key]["fob"] = $item["fob"] / $tc;
                    $cif->dolares[$key]["flete"] = $item["flete"] / $tc;
                    $cif->dolares[$key]["seguro"] = $item["seguro"] / $tc;
                    $cif->dolares[$key]["comision_bancaria"] = $item["comision_bancaria"] / $tc;
                    $cif->dolares[$key]["otro_costo"] = $item["otro_costo"] / $tc;
                    $cif->dolares[$key]["cif"] = $item["cif"] / $tc;
                    $cif->dolares[$key]["ad_valorem"] = $item["ad_valorem"] / $tc;
                    $cif->dolares[$key]["imponible"] = $item["imponible"] / $tc;
                    $cif->dolares[$key]["total"] = $item["total"] / $tc;
                }
            }
        }

        return $cif;
    }

    public function obtenerDocumentosEarXDocumentoDuaId($documentoId) {
        $baseEar = Configuraciones::BASE_SGI_ADMIN;
        $respuesta->documentoEar = Ear::create()->obtenerDocumentosEarXDocumentoDuaId($documentoId, $baseEar);
        $respuesta->documentoDua = DocumentoNegocio::create()->obtenerDocumentoDuaXDocumentoId($documentoId);
        return $respuesta;
    }

    public function relacionarDuaEar($documentoDuaId, $earSeleccionados, $usuarioId) {
        foreach ($earSeleccionados as $index => $itemEar) {
            //RELACIONO EAR CON DUA (sgi_admin) - Devolver el id de doc de pago (DESEMBOLSO)
            $baseEar = Configuraciones::BASE_SGI_ADMIN;
            $resEar = Ear::create()->actualizarSolicitudDuaIdXEarId($baseEar, $itemEar, $documentoDuaId);

            //OBTENGO LOS DOCUMENTOS RELACIONADOS DEL DESEMBOLO (DOCUMENTOS DEL EAR)
            $resDataRelaciones = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($resEar[0]['doc_desembolso_id']);

            //RELACIONO LA DUA CON LOS DOCUMENTOS DEL EAR
            if (!ObjectUtil::isEmpty($resDataRelaciones)) {
                foreach ($resDataRelaciones as $indexRel => $itemRel) {
                    $resDocRel = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoDuaId, $itemRel['documento_relacionado_id'], 1, 1, $usuarioId, $itemRel['relacion_ear']);
                }
            }
        }

        //GENERACION DE COSTO UNITARIO
        IF (!ObjectUtil::isEmpty($resDocRel) && $resDocRel[0]['vout_exito'] == 1) {
            $resActualizarCostos = MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoDuaId, $usuarioId);
        }

        $respuesta = $resActualizarCostos;
        if (ObjectUtil::isEmpty($respuesta)) {
            $respuesta = $resEar;
        }

        return $respuesta;
    }

    //Area de funciones para copiar documento 

    public function obtenerDocumentosRelacionados($documentoId) {

        return DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($documentoId);
    }

    function obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId) {

        $tipoIds = '(4)';
        $respuesta = new ObjectUtil();
//        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
//        $movimientoTipoId = $movimientoTipo[0]["id"];
//        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $tipoIds);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($empresaId, $tipoIds);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();

        return $respuesta;
    }

    function buscarDocumentoACopiar($criterios, $elementosFiltrados, $columnas, $orden, $tamanio, $transferenciaTipo) {

        $empresaId = $criterios['empresa_id'];
        $documentoTipoIds = $criterios['documento_tipo_ids'];
        $personaId = $criterios['persona_id'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $fechaEmisionInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_inicio']);
        $fechaEmisionFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_fin']);
        $fechaVencimientoInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_inicio']);
        $fechaVencimientoFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_fin']);

        $movimientoTipoId = $criterios['movimiento_tipo_id'];

//        $documentoTipoIdFormateado = $documentoTipoIds[1];
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = Movimiento::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $transferenciaTipo, $movimientoTipoId);

        $respuesta->contador = Movimiento::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $transferenciaTipo, $movimientoTipoId);

        return $respuesta;
    }

    public function relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId) {
        //VALIDAR QUE NO RELACIONEN UN DOCUMENTO YA RELACIONADO
        $relacionarEar = 1;
        $valorCheck = 1;
        $estado = 1;
        $dataRel = DocumentoNegocio::create()->obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoIdOrigen, $documentoIdARelacionar);

        if (!ObjectUtil::isEmpty($dataRel)) {
            throw new WarningException('Documento a relacionar duplicado');
        }

        $respuestaGuardarRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoIdOrigen, $documentoIdARelacionar, $valorCheck, $estado, $usuarioId, $relacionarEar);
        if (ObjectUtil::isEmpty($respuestaGuardarRelacion) || $respuestaGuardarRelacion[0]['vout_exito'] != 1) {
            throw new WarningException('Error al intentar relacionar el documento');
        }

        $resActualizarCostos = MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoIdOrigen, $usuarioId);
        if (ObjectUtil::isEmpty($resActualizarCostos) || $resActualizarCostos[0]['vout_exito'] != 1) {
            throw new WarningException('Error al intentar relacionar el documento');
        }

        return $respuestaGuardarRelacion;
    }

}
