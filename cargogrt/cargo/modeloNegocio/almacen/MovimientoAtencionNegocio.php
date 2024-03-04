<?php

require_once __DIR__ . '/../../modelo/almacen/MovimientoAtencion.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class MovimientoAtencionNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return MovimientoAtencionNegocio
     */
    static function create() {
        return parent::create();
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

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = MovimientoAtencion::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $transferenciaTipo, $movimientoTipoId);

        $respuesta->contador = MovimientoAtencion::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $transferenciaTipo, $movimientoTipoId);

        return $respuesta;
    }

    function obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $respuesta = new ObjectUtil();

        $documentoACopiar = DocumentoNegocio::create()->obtenerDataDocumentoACopiar($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($documentoACopiar)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->documentoACopiar = $documentoACopiar;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
        $respuesta->detalleDocumento = $this->obtenerDocumentoRelacionDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados);

        if ($documentoTipoDestinoId != $documentoTipoOrigenId) {
            $respuesta->documentosRelacionados = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentosRelacionados = 1;
        }

        $respuesta->dataPagoProgramacion = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
        
        //OBTENER DATA DE UNIDAD DE MEDIDA
        $documentoDetalle = $respuesta->detalleDocumento;
        foreach ($documentoDetalle as $index => $item) {
            $bienId = $item['bien_id'];
            $unidadMedidaId = $item['unidad_medida_id'];
            $precioTipoId = $item['precio_tipo_id'];
            $monedaId = $documentoACopiar[0]['moneda_id'];
            $fechaEmision = date("d/m/Y");
            foreach ($documentoACopiar as $itemDato) {
                if ($itemDato['tipo'] == 9) {
                    $fechaEmision = date_format((date_create($itemDato['valor'])), 'd/m/Y');
                }
            }

            $data = MovimientoNegocio::create()->obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision);
            $documentoDetalle[$index]['dataUnidadMedida'] = $data;
        }
        $respuesta->detalleDocumento = $documentoDetalle;
        //FIN OBTENER DATA UNIDAD MEDIDA

        return $respuesta;
    }

    function obtenerDocumentoRelacionDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $banderaMerge = 0;
        $arrayDetalle = array();

        $tamanhoArrayRelacionado = count($documentoRelacionados);
        if (!ObjectUtil::isEmpty($movimientoId) && !ObjectUtil::isEmpty($documentoId)) {
            $documentoRelacionados[$tamanhoArrayRelacionado]['movimientoId'] = $movimientoId;
            $documentoRelacionados[$tamanhoArrayRelacionado]['documentoId'] = $documentoId;
        }

        foreach ($documentoRelacionados as $documentoRelacion) {
            $documentoDetalle = MovimientoAtencion::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);

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
                    //obtener datos de: movimiento_bien_detalle
                    $resMovimientoBienDetalle = MovimientoBien::create()->obtenerMovimientoBienDetalleXMovimientoBienId($detalle['movimiento_bien_id']);

                    array_push($arrayDetalle, $this->getDocumentoACopiarMerge(
                                    $detalle['organizador_descripcion'], $detalle['organizador_id'], $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['bien_id'], $detalle['valor_monetario'], $detalle['unidad_medida_id'], $detalle['unidad_medida_descripcion'], $detalle['precio_tipo_id'], $resMovimientoBienDetalle
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

        return $arrayDetalle;
    }

    private function getDocumentoACopiarMerge($organizadorDescripcion, $organizadorId, $cantidad, $bienDescripcion, $bienId, $valorMonetario, $unidadMedidaId, $unidadMedidaDescripcion, $precioTipoId, $movimientoBienDetalle) {

        $detalle = array(
            "organizador_descripcion" => $organizadorDescripcion,
            "organizador_id" => $organizadorId,
            "cantidad" => $cantidad,
            "bien_descripcion" => $bienDescripcion,
            "bien_id" => $bienId,
            "unidad_medida_id" => $unidadMedidaId,
            "unidad_medida_descripcion" => $unidadMedidaDescripcion,
            "valor_monetario" => $valorMonetario,
            "precio_tipo_id" => $precioTipoId,
            "movimiento_bien_detalle" => $movimientoBienDetalle
        );

        return $detalle;
    }

    public function obtenerCantidadProgramadaXDocumentoId($documentoId, $bienId, $unidadMedidaId, $organizadorId) {
        return MovimientoAtencion::create()->obtenerCantidadProgramadaXDocumentoId($documentoId, $bienId, $unidadMedidaId, $organizadorId);
    }

    public function validarGenerarDocumentoAdicional($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar = null,$periodoId=null, $percepcion = null) {
        //validacion en caso de bienes faltantes       

        $documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);

        if ($documentoTipo[0]["generar_documento_adicional"] == 1 && ObjectUtil::isEmpty($documentoARelacionar)) {
            $bandera = true;

            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoGenerarXMovimientoTipoId($movimientoTipo[0]["id"]);

            $dataProveedor = array();
            $dataOrganizador = array();

            $j = 0;
            foreach ($detalle as $indice => $item) {
                $cantidadFaltante = $item["cantidad"];
                //validar que no sea servicio
                $bien = BienNegocio::create()->getBien($item["bienId"]);

                if ($cantidadFaltante > 0 && $bien[0]['bien_tipo_id'] != -1) {

                    $dataOrganizador[$j] = array();

                    $dataP = BienNegocio::create()->obtenerBienPersonaXBienId($item["bienId"]);
                    array_push($dataProveedor, $dataP);

                    $dataStockBien = BienNegocio::create()->obtenerStockPorBien($item["bienId"], null);

                    foreach ($dataStockBien as $ind => $itemDataStock) {
                        if ($cantidadFaltante <= $itemDataStock["stock"] && $item["unidadMedidaId"] == $itemDataStock["unidad_medida_id"]) {

                            array_push($dataOrganizador[$j], array('organizadorId' => $itemDataStock["organizador_id"], 'descripcion' => $itemDataStock["organizador_descripcion"]));
                        }
                    }

                    $j++;
                }
            }


            $respuesta->generarDocumentoAdicional = 1;
            $respuesta->dataDocumentoTipo = $dataDocumentoTipo;
            $respuesta->dataOrganizador = $dataOrganizador;
            $respuesta->dataProveedor = $dataProveedor;
            $respuesta->dataDetalle = $detalle;
        }

        if ($bandera) {
            return $respuesta;
        }
        //fin validaacion       
        // validar si tipo de pago es contado
        //obtenemos valor del total
        $total = 0;
        foreach ($camposDinamicos as $item) {
            if ($item['tipo'] == 14) {
                $total = $item['valor'] * 1;
            }
        }

        if ($tipoPago == '1' && $total != 0) {
            $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
            $empresaId = $dataDocumentoTipo[0]['empresa_id'];
            $tipoDocumento = $dataDocumentoTipo[0]['tipo'];

            if ($tipoDocumento == 1 || $tipoDocumento == 3 || $tipoDocumento == 4 || $tipoDocumento == 6) {

                if ($tipoDocumento == 1 || $tipoDocumento == 3) {
                    $tipo = 2;
                    $tipo2 = 3;
                    $tipoCobranzaPago = 1;
                }
                if ($tipoDocumento == 4 || $tipoDocumento == 6) {
                    $tipo = 5;
                    $tipo2 = 6;
                    $tipoCobranzaPago = 2;
                }

                $res->dataDocumentoPago = PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresaId, $tipo, $tipo2, $usuarioId);
                $res->actividad = Pago::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
                return $res;
            }
        }
        //fin validacion tipo pago contado.
        // ATENCION DE SOLICITUDES(QUITAR EL FASLSE PARA HABILITAR ATENCIONES)
        $bandAtiende = null;
        $habilitarAtencion = false;
        if ($habilitarAtencion) {
            foreach ($documentoARelacionar as $index => $item) {
                if ($item['tipo'] == 1) {

                    $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($item['movimientoId']);
                    $documentoARelacionar[$index]['detalleBien'] = $dataMovBien;

                    foreach ($detalle as $indexDet => $itemDet) {
                        foreach ($dataMovBien as $itemMovBien) {
                            if ($itemDet['bienId'] == $itemMovBien['bien_id']) {
                                if (ObjectUtil::isEmpty($detalle[$indexDet]['cantidadSol'])) {
                                    $detalle[$indexDet]['cantidadSol'] = $itemMovBien['cantidad'] * 1;
                                } else {
                                    $detalle[$indexDet]['cantidadSol']+=$itemMovBien['cantidad'] * 1;
                                }
                            }
                        }
                    }

                    //}

                    $bandAtiende = false;
                    $bandExterna = $bandAtiende;
                    foreach ($detalle as $itemDeta) {
                        if ($itemDeta['cantidad'] < $itemDeta['cantidadSol']) {
                            $bandAtiende = true;
                        }
                    }

                    if ($bandAtiende) {
                        $res->dataAtencionSolicitud = $documentoARelacionar;
                        return $res;
                    }
                }
            }
        }

        // FIN ATENCION SOLICITUDES

        $respuesta = $this->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $bandAtiende,$periodoId, $percepcion);
        
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if($movimientoTipo[0]["codigo"]==8){ //MT Guía remisión venta 
            foreach ($camposDinamicos as $item) {
                if ($item["tipo"]==4 && $item["valor"]==357) { //357 id motivo de traslado = defectuoso
                    //ENVIAR CORREO ADMIN
                    $this->guardarEmailEnvioMercaderiaDefectuoso($documentoTipo[0]["descripcion"],$camposDinamicos,$detalle);
                    break;
                }
            }
        }
        
        return $respuesta;
    }
    
    public function guardarEmailEnvioMercaderiaDefectuoso($documentoTipo,$dataCabecera,$detalleProductos) {
        $documentoTipo_descripcion=$documentoTipo . " ";
        
        foreach ($dataCabecera as $item) {
            if ($item["tipo"]==7) {
                $documentoTipo_descripcion.=$item["valor"] . "-";
            }elseif ($item["tipo"]==8) {
                $documentoTipo_descripcion.=$item["valor"];
            }elseif ($item["tipo"]==9) {
                $fecha_emision='<b>' . $item["descripcion"]. ":</b> " . $item["valor"];
            }elseif ($item["tipo"]==3) {
                $fecha_inicio_trslado='<b>' . $item["descripcion"]. ":</b> " . $item["valor"];
            }
        }
        
        $descripcionCorreo='<b>Salida de mercadería por '. $documentoTipo_descripcion . '</b>';
        $descripcionCorreo.= '<br>' . $fecha_emision . "<br>" . $fecha_inicio_trslado;
        $tituloCorreo='Salida de mercaderia por motivo defectuoso';
        $asuntoCorreo='Salida de mercaderia por motivo defectuoso';
        
        $buscarOrganizador = 1;
        $buscarUnidadMedida = 1;//PORQUE SOLO TIENE UNA UNIDAD DE MEDIDA
        
        if (!ObjectUtil::isEmpty($dataCabecera)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(19);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';
            
            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Producto</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>U. Medida</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';
            
            if (!ObjectUtil::isEmpty($detalleProductos)) {
                foreach ($detalleProductos as $index => $item) {
                    if($buscarOrganizador==1){
                        $almacen=Organizador::create()->getOrganizador($item['organizadorId']);
                        $buscarOrganizador=2;
                    }
                    if($buscarUnidadMedida==1){
                        $unidadMedidaData= UnidadNegocio::create()->getUnidad($item['unidadMedidaId']);
                        $buscarUnidadMedida=2;
                    }
                    $html = '<tr>';
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['bienDesc'];
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['cantidad'] * 1;
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $unidadMedidaData[0]['descripcion'];
                    $html = $html . '</tr>';

                    $dataDetalle = $dataDetalle . $html;
                }
            }
            
            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = $descripcionCorreo . "<br><b>Almacén de salida:</b> " . $almacen[0]['descripcion'];
            
            //logica correo:             
            if (ObjectUtil::isEmpty($asuntoCorreo)) {
                $asunto = $plantilla[0]["asunto"];
            } else {
                $asunto = $asuntoCorreo;
            }
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", $tituloCorreo, $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return $tituloCorreo . ' ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }

    public function guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $atiende = null,$periodoId=null, $percepcion = null) {
        $puedeGuardar = false;
        $obligatorio = MovimientoNegocio::create()->verificarDocumentoEsObligatorioXOpcionID($opcionId);

        if ($obligatorio[0]['movimiento_tipo_anterior_relacion'] == 1) {
            if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                $puedeGuardar = true;
            } else {
                $puedeGuardar = false;
                throw new WarningException("Se requiere una " . $obligatorio[0]['anterior_descripcion'] . ", copie alguna.");
            }
        } else {
            $puedeGuardar = true;
        }

        if ($puedeGuardar) {

            $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago,$periodoId);

            if (!ObjectUtil::isEmpty($listaPagoProgramacion)) {
                foreach ($listaPagoProgramacion as $ind => $item) {
                    //listaPagoProgramacion.push([ fechaPago, importePago, dias, porcentaje,glosa,pagoProgramacionId]);
                    $fechaPago = DateUtil::formatearCadenaACadenaBD($item[0]);
                    $importePago = $item[1];
                    $dias = $item[2];
                    $porcentaje = $item[3];
                    $glosa = $item[4];

                    $res = Pago::create()->guardarPagoProgramacion($documentoId, $fechaPago, $importePago, $dias, $porcentaje, $glosa, $usuarioId);
                }
            }

            if ($accionEnvio == 'enviar') {
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }

            if ($accionEnvio == 'enviarEImprimir') {
                $respuesta->dataImprimir = MovimientoNegocio::create()->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            } else {

                //obtener email de plantilla            
                $movimientoTipoId = MovimientoNegocio::create()->obtenerIdXOpcion($opcionId);
                $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
                $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

                $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

                //validar si se muestra el modal de confirmacion de emails.
                if ($plantilla[0]["confirmacion"] == 1) {
                    $respuesta->dataPlantilla = $plantilla;
                    $respuesta->dataCorreos = $correosPlantilla;
                    $respuesta->documentoId = $documentoId;
                    return $respuesta;
                }

                if (ObjectUtil::isEmpty($correosPlantilla)) {
                    $this->setMensajeEmergente("Email en blanco, nose pudo enviar correo.", null, Configuraciones::MENSAJE_WARNING);
                    $respuesta->documentoId = $documentoId;
                    return $respuesta;
                }

                $correos = '';
                foreach ($correosPlantilla as $email) {
                    $correos = $correos . $email . ';';
                }

                $plantillaId = $plantilla[0]["email_plantilla_id"];
                $respuesta->dataEnvioCorreo = MovimientoNegocio::create()->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }
        }
    }

    public function guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $tipoPago = null,$periodoId=null) {

        $movimientoTipoId = MovimientoNegocio::create()->obtenerIdXOpcion($opcionId);
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);

        // 1. Insertamos el movimiento
        $movimiento = Movimiento::create()->guardar($movimientoTipoId, 1, $usuarioId);
        $movimientoId = $this->validateResponse($movimiento);
        if (ObjectUtil::isEmpty($movimientoId) || $movimientoId < 1) {
            throw new WarningException("No se pudo guardar el movimiento");
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        if (ObjectUtil::isEmpty($monedaId)) {
            $monedaId = $movimientoTipo[0]["moneda_id"];
        }

        // 2. Insertamos el documento
        $documento = DocumentoNegocio::create()->guardar($documentoTipoId, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, null, $detalle[0]['utilidadTotal'], $detalle[0]['utilidadPorcentajeTotal'], $tipoPago,$periodoId);

        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException("No se pudo guardar el documento");
        }

        // 3. Insertamos el detalle
        foreach ($detalle as $item) {
            // validaciones
            if ($item["bienId"] == NULL) {
                throw new WarningException("No se especificó un valor válido para Bien. ");
            }
            /* if($item["organizadorId"]==NULL){
              throw new WarningException("No se especificó un valor válido para Organizador. ");
              } */
            if ($item["unidadMedidaId"] == NULL) {
                throw new WarningException("No se especificó un valor válido para Unidad Medida. ");
            }
            if ($item["cantidad"] == NULL or $item["cantidad"] <= 0) {
                throw new WarningException("No se especificó un valor válido para Cantidad. ");
            }

            //obtengo la fecha de emision
            $fechaEmision = null;
            foreach ($camposDinamicos as $valorCampo) {
                if ($valorCampo["tipo"] == 9) {
                    $fechaEmision = DateUtil::formatearCadenaACadenaBD($valorCampo["valor"]);
                }
            }

            MovimientoAtencionNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $documentoARelacionar);
                        
            //validacion el precio unitario tiene que ser mayor al precio de compra.
            $precioCompra = 0;
            $validarPrecios = false;
            if ($item["precio"] * 1 == 0) {
                $validarPrecios = false;
            }

            if (!ObjectUtil::isEmpty($item["precioCompra"])) {
                $precioCompra = $item["precioCompra"];
            }
            if ($dataDocumentoTipo[0]["validacion"] == 1 && $validarPrecios) {
                $precioUnitario = $item["precio"];
//                $precioCompra=$item["precioCompra"];

                if ($precioUnitario <= $precioCompra) {
                    throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor al precio de compra."
                    . "<br> Producto: " . $item["bienDesc"]
                    . "<br> Precio compra: " . $precioCompra);
                }
            }

            //validacion: el precio minimo (descuento) no tiene que ser menor al precio unitaio
//            if($movimientoTipo[0]["indicador"]==MovimientoTipoNegocio::INDICADOR_SALIDA){
            if ($dataDocumentoTipo[0]["tipo"] == 1 && $validarPrecios) {
                $precioUnitario = $item["precio"];

                //calculo de precio minimo (descuento)
//                $precioCompra=$item["precioCompra"];

                $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($item["bienId"], $item["unidadMedidaId"], $item["precioTipoId"], $monedaId);
                if (!ObjectUtil::isEmpty($dataPrecio)) {
                    if ($checkIgv == 1) {
                        $precioVenta = $dataPrecio[0]["incluye_igv"];
                    } else {
                        $precioVenta = $dataPrecio[0]["precio"];
                    }
                    $cantidad = $item["cantidad"];
                    $utilidadSoles = ($precioVenta - $precioCompra) * $cantidad;
                    $subTotal = $precioVenta * $cantidad;
                    $utilidadPorcentaje = 0;
                    if ($subTotal != 0) {
                        $utilidadPorcentaje = ($utilidadSoles / $subTotal) * 100;
                    }

                    $descuentoPorcentaje = ($dataPrecio[0]["descuento"] / 100) * ($utilidadPorcentaje);
                    $precioMinimo = $precioVenta - ($descuentoPorcentaje / 100) * $precioVenta;
                    $precioMinimo = round($precioMinimo, 2);  // 1.96

                    if ($precioUnitario < $precioMinimo) {
                        throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor o igual al precio mínimo (descuento)"
                        . "<br> Producto: " . $item["bienDesc"]
                        . "<br> Precio mínimo: " . $precioMinimo);
                    }
                }
            }

            //fin validaciones
            $movimientoBien = MovimientoBien::create()->guardar($movimientoId, $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $item["precio"], 1, $usuarioId, $item["precioTipoId"], $item["utilidad"], $item["utilidadPorcentaje"], $checkIgv);
            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }
            
            //GUARDAR EN patencion_movimiento_bien
            $this->guardarPAtencionMovimientoBien($movimientoBienId,$documentoARelacionar[0]['documentoId'], $item["bienId"],$item["unidadMedidaId"],$item["organizadorId"],$item["cantidad"],$usuarioId);

            //guardar el detalle del detalle del movimiento en movimiento_bien_detalle
            if (!ObjectUtil::isEmpty($item["detalle"])) {
                foreach ($item["detalle"] as $valor) {
                    if (!ObjectUtil::isEmpty($valor['valorDet'])) {
                        if ($valor['columnaCodigo'] == 16 || $valor['columnaCodigo'] == 17) {
                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleGuardarCadena($movimientoBienId, $valor['columnaCodigo'], $valor['valorDet'], $usuarioId);
                        }

                        if ($valor['columnaCodigo'] == 18) {
                            $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor['valorDet']);
                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleGuardarFecha($movimientoBienId, $valor['columnaCodigo'], $fechaVencimiento, $usuarioId);
                        }
                    }
                }
            }

            //Logica de correo            
            //$movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
                $bien = BienNegocio::create()->obtenerCantidadMinima($item["bienId"], $item["unidadMedidaId"]);
//                $stockA = BienNegocio::create()->obtenerStockActual($item["bienId"], $item["organizadorId"], $bien[0]["unidad_control_id"]);
                $stockA = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($item["bienId"], $bien[0]["unidad_control_id"]);
                
                if ($bien[0]["cantidad_minima"] > $stockA[0]["stock"]) {
                    $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(1);

                    $bienDesc = $bien[0]["bien_desc"];
                    $umDesc = $bien[0]["um_desc"];
                    $asunto = $plantilla[0]["asunto"];
                    $cuerpo = $plantilla[0]["cuerpo"];

                    $bienDesc = $bien[0]["bien_desc"];
                    $umDesc = $bien[0]["um_desc"];
                    $asunto = $plantilla[0]["asunto"];
                    $cuerpo = $plantilla[0]["cuerpo"];

                    $destinatario = $plantilla[0]["destinatario"];
                    $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($destinatario, null, null);

                    $correos = '';
                    foreach ($correosPlantilla as $email) {
                        $correos = $correos . $email . ';';
                    }
                    $asunto = str_replace("[|bien_desc|]", $bienDesc, $asunto);
                    $cuerpo = str_replace("[|bien_desc|]", $bienDesc, $cuerpo);
                    $cuerpo = str_replace("[|bien_stock|]", number_format($stockA[0]["stock"], 2, ".", ","), $cuerpo);
                    $cuerpo = str_replace("[|um_desc|]", $umDesc, $cuerpo);
                    $cuerpo = str_replace("[|cantidad_minima|]", number_format($bien[0]["cantidad_minima"], 2, ".", ","), $cuerpo);

                    EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
                }
            }
            //Fin logica de correo
        }

        //si el documento se a copiado guardamos las relaciones
        foreach ($documentoARelacionar as $documentoRelacion) {
            if (!ObjectUtil::isEmpty($documentoRelacion['documentoId'])) {
                DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId);
            }
        }

        // logica de envio de correo de documento
        foreach ($camposDinamicos as $indexCampos => $valor) {
            if ($valor["tipo"] == 9) {
                $fechaEmision = DateUtil::formatearCadenaACadenaBD($valor["valor"]);
                $hoy = date("Y-m-d");

                if ($fechaEmision < $hoy) {
                    MovimientoNegocio::create()->enviarCorreoDocumentoConFechaEmisionAnterior($documentoId, $movimientoId, $usuarioId);
                }
            }
        }
        // fin envio de correo de documento
        //logica para tramos        
        foreach ($detalle as $item) {
            // validaciones
            if (!ObjectUtil::isEmpty($item["bienTramoId"])) {
                Movimiento::create()->actualizarBienTramoEstado($item["bienTramoId"], $movimientoId);
            }
        }
        // fin logica para tramos

        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");

        return $documentoId;
    }

    public function obtenerStockAControlar($opcionId, $bienId, $organizadorId, $unidadMedidaId, $cantidad, $fechaEmision = null, $documentoARelacionar) {
        if (ObjectUtil::isEmpty($organizadorId))
            return -1;
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (!ObjectUtil::isEmpty($movimientoTipo)) {
            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
                $bien = BienNegocio::create()->getBien($bienId);
                if ($bien[0]['bien_tipo_id'] == -1) {
                    return -1;
                } else {
                    $dataFechas = null;
                    if (!ObjectUtil::isEmpty($fechaEmision)) {
                        $dataFechas = DocumentoNegocio::create()->obtenerFechasPosterioresDocumentosSalidas(
                                $fechaEmision, $bienId, $organizadorId);
                    }

                    if (!ObjectUtil::isEmpty($dataFechas)) {
                        $arrayFecha = array("fecha_emision" => $fechaEmision);
                        array_push($dataFechas, $arrayFecha);
                        array_multisort($dataFechas);

                        //validamos stock por fecha posterior o igual a fecha emision
                        $dataFechaInicial = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
                        $fechaInicial = $dataFechaInicial[0]['primera_fecha'];

                        foreach ($dataFechas as $itemFecha) {
                            $fechaFinal = $itemFecha['fecha_emision'];
                            //obtener stock
                            $stock = BienNegocio::create()->obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId
                                    ($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal);

                            $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                            if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                                $cantidadProg = MovimientoAtencionNegocio::create()->obtenerCantidadProgramadaXDocumentoId(
                                        $documentoARelacionar[0]['documentoId'], $bienId, $unidadMedidaId, $organizadorId);

                                $stockControlar = $stockControlar * 1 + $cantidadProg[0]['cantidad_prog'] * 1;
                            }

                            if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                                throw new WarningException("No cuenta con stock suficiente en el almacén seleccionado.<br>"
                                . " Stock en fecha " . date_format((date_create($fechaFinal)), 'd/m/Y') . ": " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                . " Producto: " . $bien[0]['descripcion'] . "<br>"
                                . " Cantidad: " . $cantidad);
                            }
                        }
                    } else {
                        // stock hasta fecha actual
                        $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId);
                        // obtenerStockBase($organizadorId, $bienId);
                        $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                        if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                            $cantidadProg = MovimientoAtencionNegocio::create()->obtenerCantidadProgramadaXDocumentoId(
                                    $documentoARelacionar[0]['documentoId'], $bienId, $unidadMedidaId, $organizadorId);

                            $stockControlar = $stockControlar * 1 + $cantidadProg[0]['cantidad_prog'] * 1;
                        }

                        if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                            throw new WarningException("No cuenta con stock suficiente en el almacén seleccionado.<br>"
                            . " Stock: " . number_format($stockControlar, 2, ".", ",") . "<br>"
                            . " Producto: " . $bien[0]['descripcion'] . "<br>"
                            . " Cantidad: " . $cantidad);
                        } else {
                            return $stockControlar;
                        }
                    }
                }
            } else {
                return -1;
            }
        } else {
            return 0;
        }
    }
    
    public function guardarPAtencionMovimientoBien($movimientoBienId,$documentoId, $bienId,$unidadMedidaId,$organizadorId,$cantidad,$usuarioId){
        $dataPAtencion= MovimientoAtencion::create()->obtenerPAtencionLiberado($documentoId, $bienId,$unidadMedidaId);
        
        $cantidadTemp=$cantidad;
        foreach ($dataPAtencion as $item){
            $patencionId=$item['patencion_id'];
            if($cantidadTemp>0 && ($item['organizador_prog']==$organizadorId || ObjectUtil::isEmpty($item['organizador_prog']))){
                if($item['cantidad_prog']>$cantidadTemp){
                    $cantidadAtendida=$cantidadTemp;                    
                    $cantidadTemp=0;
                }else{
                    $cantidadAtendida=$item['cantidad_prog'];   
                    $cantidadTemp=$cantidadTemp-$item['cantidad_prog'];
                }
                
                $res=  MovimientoAtencion::create()->guardarPatencionMovimientoBien($patencionId,$movimientoBienId,$cantidadAtendida,$usuarioId);
            }
            
        }
    }
    
    public function buscarPersonasXDocumentoTipoXValor($documentoTipoArray, $valor){
        return MovimientoAtencion::create()->buscarPersonasXDocumentoTipoXValor(Util::fromArraytoString($documentoTipoArray), $valor);
    }
    public function buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdArray, $descripcion) {
        return MovimientoAtencion::create()->buscarDocumentoTipoXDocumentoTipoXDescripcion(Util::fromArraytoString($documentoTipoIdArray), $descripcion);
    }
    function buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdArray,$busqueda){
        return MovimientoAtencion::create()->buscarDocumentosXTipoDocumentoXSerieNumero(Util::fromArraytoString($documentoTipoIdArray),$busqueda);
    }
    
    public function obtenerStockActual($bienId,$indice,$organizadorId,$unidadMedidaId,$documentoRelacion){
        if (!ObjectUtil::isEmpty($organizadorId)) {
            $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId);
        } else {
            $stock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
        }

        if (!ObjectUtil::isEmpty($documentoRelacion)) {
            $cantidadProg = MovimientoAtencionNegocio::create()->obtenerCantidadProgramadaXDocumentoId(
                    $documentoRelacion[0]['documentoId'], $bienId, $unidadMedidaId, $organizadorId);

            $stock[0]['stock'] = $stock[0]['stock'] * 1 + $cantidadProg[0]['cantidad_prog'] * 1;
        }


        $cantidadMinima = BienNegocio::create()->obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId);
        $stock[0]['indice'] = $indice;
        $stock[0]['cantidad_minima'] = $cantidadMinima[0]['cantidad_minima'];
        
        return $stock;        
    }
    
    public function obtenerStockParaProductosDeCopia($organizadorDefectoId, $detalle,$documentoRelacion) {
        $dataStock = array();
        foreach ($detalle as $item) {
            //TIENE QUE SER SIMILAR AL METODO DEL CONTROLADOR: obtenerStockActual
            $bienId = $item['bienId'];
            $unidadMedidaId = $item['unidadMedidaId'];
            $organizadorId = $item['organizadorId'];
            if (!ObjectUtil::isEmpty($organizadorDefectoId) && $organizadorDefectoId != 0) {
                $organizadorId = $organizadorDefectoId;
            }
            
            $stock= MovimientoAtencionNegocio::create()->obtenerStockActual($bienId,$item['index'],$organizadorId,$unidadMedidaId,$documentoRelacion);            
            array_push($dataStock, $stock);
        }

        return $dataStock;
    }

}
