<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoDuaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/DocumentoRevisionNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class EarNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return EarNegocio
     */
    static function create() {
        return parent::create();
    }

    public function registrarDocumentoDesembolso($parametros) {
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa(2);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("No existe periodo abierto.");
        }
        //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION
        $fechaEmision = date_create($parametros->camposDinamicos->fechaEmision);
        $fechaEmision = date_format($fechaEmision, 'd/m/Y');
        $periodoId = $this->obtenerPeriodoIdXFecha($dataPeriodo, $fechaEmision);
        if (ObjectUtil::isEmpty($periodoId)) {
            throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto.");
        }
        //FIN PERIODO

        $opcionId = null;
        $usuarioId = $parametros->usuarioId;
        $documentoTipoId = $parametros->documentoTipoId;
        $monedaId = $parametros->monedaId;

        $usuarioData = UsuarioNegocio::create()->getUsuario($parametros->usuarioPersona);
        $parametros->camposDinamicos->persona = $usuarioData['0']['persona_id'];

        $camposDinamicos = $this->obtenerCamposDinamicosSGI($documentoTipoId, $parametros->camposDinamicos);

        $resDocId = PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId);

        return $resDocId;
    }

    public function obtenerPeriodoIdXFecha($dataPeriodo, $fecha) {
        $periodoId = null;

        if (!ObjectUtil::isEmpty($fecha)) {
            $fechaArray = explode('/', $fecha);

            $d = $fechaArray[0] * 1;
            $m = $fechaArray[1] * 1;
            $y = $fechaArray[2] * 1;

            foreach ($dataPeriodo as $item) {
                if ($item['anio'] * 1 == $y && $item['mes'] * 1 == $m) {
                    $periodoId = $item['id'];
                }
            }
        }

        return $periodoId;
    }

    public function obtenerCamposDinamicosSGI($documentoTipoIdSGI, $valores) {
        $configuracionesDtd = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoIdSGI);

        foreach ($configuracionesDtd as $index => $item) {
            switch ((int) $item["tipo"]) {
                case DocumentoTipoNegocio::DATO_CADENA:
                    $valoresCadena = $valores->tipoCadena;

                    $posSerie = strpos($item["descripcion"], 'Serie');
                    if ($posSerie !== false) {
                        $configuracionesDtd[$index]['valor'] = $valoresCadena->serie;
                    }
                    $posNumero = strpos($item["descripcion"], 'Número');
                    if ($posNumero !== false) {
                        $configuracionesDtd[$index]['valor'] = $valoresCadena->numero;
                    }
                    break;
                case DocumentoTipoNegocio::DATO_LISTA:
                    $valoresLista = $valores->tipoLista;

                    $posMotivo = strpos($item["descripcion"], 'Motivo');
                    if ($posMotivo !== false) {
                        $motivo = '';
                        if ($valoresLista->motivo == 'defecto') {
                            $motivo = $item["lista_defecto"];
                        }
                        $configuracionesDtd[$index]['valor'] = $motivo;
                    }
                    break;
                case DocumentoTipoNegocio::DATO_FECHA:
                    $valoresFecha = $valores->tipoFecha;

                    //                $posFechaDep = strpos($item["descripcion"], 'Deposito');
                    //                if($posFechaDep!==false){
                    $fecha = date_create($valoresFecha->fecha);
                    $fecha = date_format($fecha, 'd/m/Y');
                    $configuracionesDtd[$index]['valor'] = $fecha;
                    //                }
                    break;
                case DocumentoTipoNegocio::DATO_PERSONA:
                    $configuracionesDtd[$index]['valor'] = $valores->persona;
                    break;
                case DocumentoTipoNegocio::DATO_SERIE:
                    $serie = $valores->serie;
                    if ($valores->serie == 'defecto') {
                        $serie = $item["cadena_defecto"];
                    }
                    $configuracionesDtd[$index]['valor'] = $serie;
                    break;
                case DocumentoTipoNegocio::DATO_NUMERO:
                    $numero = $valores->numero;
                    if ($valores->numero == 'auto') {
                        $numero = DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoIdSGI);
                    }
                    $configuracionesDtd[$index]['valor'] = $numero;
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_EMISION:
                    $fecha = date_create($valores->fechaEmision);
                    $fecha = date_format($fecha, 'd/m/Y');
                    $configuracionesDtd[$index]['valor'] = $fecha;
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:
                    $fecha = date_create($valores->fechaVencimiento);
                    $fecha = date_format($fecha, 'd/m/Y');
                    $configuracionesDtd[$index]['valor'] = $fecha;
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                    $fecha = date_create($valores->fechaTentativa);
                    $fecha = date_format($fecha, 'd/m/Y');
                    $configuracionesDtd[$index]['valor'] = $fecha;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_SUB_TOTAL:
                    $configuracionesDtd[$index]['valor'] = round($valores->subtotal, 2);
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_IGV:
                    $configuracionesDtd[$index]['valor'] = round($valores->igv, 2);
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_TOTAL:
                    $configuracionesDtd[$index]['valor'] = round($valores->total, 2);
                    break;
                case DocumentoTipoNegocio::DATO_PERCEPCION:
                    $configuracionesDtd[$index]['valor'] = round($valores->percepcion, 2);
                    break;
                case DocumentoTipoNegocio::DATO_CUENTA:
                    $cuenta = $valores->cuenta;
                    if ($valores->cuenta == 'defecto') {
                        $cuenta = $item["numero_defecto"];
                    }
                    $configuracionesDtd[$index]['valor'] = $cuenta;
                    break;
                case DocumentoTipoNegocio::DATO_ACTIVIDAD:
                    $actividad = $valores->actividad;
                    if ($valores->actividad == 'defecto') {
                        $actividad = $item["numero_defecto"];
                    }
                    $configuracionesDtd[$index]['valor'] = $actividad;
                    break;
                case DocumentoTipoNegocio::DATO_DESCRIPCION:
                    $configuracionesDtd[$index]['valor'] = $valores->descripcion;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_OTROS:
                    $configuracionesDtd[$index]['valor'] = $valores->montoOtros;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_EXONERADO:
                    $configuracionesDtd[$index]['valor'] = $valores->montoExonerado;
                    break;
                case DocumentoTipoNegocio::DATO_DETRACCION_TIPO:
                    $configuracionesDtd[$index]['valor'] = $valores->detraccionTipoId;
                    break;
                case DocumentoTipoNegocio::DATO_IMPORTE_ICBP:
                    $configuracionesDtd[$index]['valor'] = $valores->icbp;
                    break;
            }
        }

        return $configuracionesDtd;
    }

    public function registrarLiquidacionVistoBueno($parametros) {
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa(2);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        $periodoIdEar = $parametros->periodoIdEar;
        $documentoTipoIdReembolsoSGI = $parametros->documentoTipoIdReembolsoSGI;
        $documentoTipoIdDevolucionSGI = $parametros->documentoTipoIdDevolucionSGI;
        $documentoTipoIdDevolucionSisSGI = $parametros->documentoTipoIdDevolucionSisSGI;
        $earNumero = $parametros->earNumero;
        $earLiqDcto = $parametros->earLiqDcto;
        $isDua = $parametros->isDua;
        $duaId = $parametros->duaId;
        $duaSerie = $parametros->duaSerie;
        $duaNumero = $parametros->duaNumero;
        $documentoIdPago = $parametros->documentoIdPago;
        /*         * ************************** PARA EL ASIENTO DE DIFERENCIA DE TIPO DE CAMBIO -> CONVERTIMOS TODO A SOLES ********************** */
        $arrLiqDet = $parametros->arrLiqDet;
        $fechaLiquidacion = $parametros->fechaLiquidacion;
        $dataTipoCambioFechaLiquidacion = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fechaLiquidacion);
        if (ObjectUtil::isEmpty($dataTipoCambioFechaLiquidacion)) {
            throw new WarningException("No existe el tipo de cambio para la siguiente fecha de liquidacion :" . $fechaLiquidacion);
        }
        $tipoCambioFechaLiquidacion = $dataTipoCambioFechaLiquidacion[0]['equivalencia_venta'];

        //VALIDAR QUE EL DOCUMENTO DE PAGO ESTE ACTIVO Y TENGA MONTO DISPONIBLE
        $arrayDetalleDistribucionLiquidacion = array();
        $arrayPeridos = array();
        $dataDocumentoPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPago);
        if (ObjectUtil::isEmpty($dataDocumentoPago)) {
            throw new WarningException("No existe el documento de pago del desembolso.");
        } else {
            if ($dataDocumentoPago[0]['monto'] * 1 != $dataDocumentoPago[0]['total'] * 1) {
                throw new WarningException("El importe disponible del documento de pago del desembolso es diferente al total.<br><br> Documento: " . $dataDocumentoPago[0]['documento_tipo'] . "<br> Serie: " . $dataDocumentoPago[0]['serie'] . "<br> Número: " . $dataDocumentoPago[0]['numero'] . "<br> Total: " . $dataDocumentoPago[0]['total'] . "<br> Disponible: " . $dataDocumentoPago[0]['monto']);
            }
        }

        $dataPago = PagoNegocio::create()->obtenerDetallePago($documentoIdPago);
        $dataDocumentoUsadoPago = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($dataPago[0]['documento_pago']);
        $dataCuenta = CuentaNegocio::create()->obtenerCuentaXId($dataDocumentoUsadoPago[0]['cuenta_id']);


        $arrDistribucionContable = $parametros->distribucionContable;
        $parametrosPlanilla = $parametros->parametrosPlanilla;
        $usuId = $parametros->usuId; //usuario de la liquidacion el colaborador
        $usuarioIdEar = $parametros->usuarioId; //usuario actual que da visto bueno
        $monedaIdEar = $parametros->monedaId;
        $montoTotalLiquidado = 0;
        $montoTotalLiquidadoSoles = 0;

        $datosUsuarioColaborador = UsuarioNegocio::create()->getUsuario($usuId);
        $colaboradorId = $datosUsuarioColaborador['0']['persona_id'];
        $colaboradorNombreCompleto = (!ObjectUtil::isEmpty($datosUsuarioColaborador['0']['nombre']) ? $datosUsuarioColaborador['0']['nombre'] : "");
        $colaboradorNombreCompleto .= (!ObjectUtil::isEmpty($datosUsuarioColaborador['0']['apellido_paterno']) ? " " . $datosUsuarioColaborador['0']['apellido_paterno'] : "");
        $colaboradorNombreCompleto .= (!ObjectUtil::isEmpty($datosUsuarioColaborador['0']['apellido_materno']) ? " " . $datosUsuarioColaborador['0']['apellido_materno'] : "");

        $dataPlanContablePersona = PersonaNegocio::create()->obtenerCuentaContableXPersonaId($colaboradorId);
        // Si no tiene cuenta le asignamos la de terceros.
//        $cuentaTerceros = 
        $planContablePersonaCodigo = ($dataPlanContablePersona[0]['plan_contable_codigo'] ? $dataPlanContablePersona[0]['plan_contable_codigo'] : PlanContable::PLAN_CONTABLE_CODIGO_EAR_TERCERO);

        //Validación DUA para relacione el documento a los pagos
        if ($isDua && ObjectUtil::isEmpty($duaId)) {
            throw new WarningException("La DUA $duaSerie - $duaNumero relacionado a este EAR no existe o no fue relacionado al momento de registrar la misma.");
        }

        //documentos para liquidacion detalle
        foreach ($arrLiqDet as $index => $v) {
            //SE OBTIENE EL ID DEL DOCUMENTO TIPO SGI
            $documentoTipoIdSGI = $v[40];
            $banderaPesonaExtranjera = false;
//            $tem =$v[46];
            //------------------- REGISTRAR EN EL SGI -------------------------
            $personaId = null;
            if (!ObjectUtil::isEmpty($v[3])) {
                if ($documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_INVOICE) {
                    $resPersona = PersonaNegocio::create()->obtenerPersonaXCodigoIdentificacion($v[3]);
                    if (ObjectUtil::isEmpty($resPersona)) {
                        throw new WarningException("El proveedor extranjero con el código " . $v[3] . " no fue registrado, verifique si el código es correcto o registrelo directamente en el SGI.");
                    }
                }
                $dataPersona = $this->personaSgiGuardaObtieneXDocumentoXRazonSocial($v[3], $v[4], $usuId);
                $personaId = $dataPersona->personaId;
            }
            //11-> ticket de deposito
            if ($v[2] == 11 && ObjectUtil::isEmpty($personaId)) {
                $personaId = $colaboradorId;
            }
            $valores->persona = $personaId;
            $valores->numero = 'auto';
            if ($v[2] == 19) {
                $valores->numero = $v[8];
            }
            $valores->serie = 'defecto';
            $valores->fechaEmision = $v[6];
            $valores->fechaVencimiento = $v[6];
            $valores->subtotal = abs($v[12]) / 1.18; //$v[22]/1.18;
            $valores->igv = (abs($v[12]) / 1.18) * 0.18; //$v[22]*0.18;
            $valores->icbp = $v[47];
            $valores->montoOtros = $v[45];
            $valores->total = abs($v[22]);
            $valores->detraccionTipoId = $v[44];
            $tipoLista->motivo = 'defecto';
            $valores->tipoLista = $tipoLista;
            $valores->percepcion = 0;
            $tipoCadena->serie = $v[7];
            $tipoCadena->numero = $v[8];
            $valores->tipoCadena = $tipoCadena;
            $valores->actividad = 'defecto';
            $valores->cuenta = 'defecto';

            $codigoCompletoDocumento = $v[3] . " | " . $v[7] . "-" . $v[8];

            $opcionId = null;
            $usuarioId = $usuarioIdEar;
            $monedaId = 2;
            if ($v[10] != 1) {//1-> soles
                $monedaId = 4;
            }
            $movimientoId = null;
            $comentario = ($v[38] == null ? '' : $v[38]) . ' ' . ($v[9] == null ? '' : $v[9]) . ' ' . ($v[36] == null ? '' : $v[36]);
            $descripcion = null;

            //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION
            if (ObjectUtil::isEmpty($periodoIdEar) || $periodoIdEar == -1) {
                $fechaEmision = date_format(date_create($v[6]), 'd/m/Y');
                $periodoId = $this->obtenerPeriodoIdXFecha($dataPeriodo, $fechaEmision);
                if (ObjectUtil::isEmpty($periodoId)) {
                    throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto, relacionado con el siguiente documento " . $codigoCompletoDocumento);
                }
            } else {
                $periodoId = $periodoIdEar;
            }
            //FIN PERIODO
            //VALIDACIÓN DE NOTAS DE CRÉDITO/DEBITO/DETRACCIÓN
            if (($v[2] == 17 || $v[2] == 18 || $v[2] == 19) && ObjectUtil::isEmpty($v[46])) {
                throw new WarningException("El siguiente documento " . $codigoCompletoDocumento . ", debe estar relacionado a una factura o boleta.");
            }
            //FIN DE VALIDACIÓN
            //OBTENER LA CONT_OPERACION_TIPO_RELACIONADA
            $contOperacionTipoId = ($isDua ? ContVoucherNegocio::OPERACION_TIPO_ID_COMPRAS_COSTO : ContVoucherNegocio::OPERACION_TIPO_ID_COMPRAS_GENERALES );
            switch (TRUE) {
                //En caso de que el documento no sea deducible
                case $v[42] == '0':
                //Concepto por pago de DUA:
                case $v[1] == '0628' && $isDua: //IGV
                case $v[1] == '0629' && $isDua; //Percepcion
                case $v[1] == '0630' && $isDua; //AddValorem   
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_DECLARACION_JURADA :
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_RECIBO_GASTO :
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_OTROS :
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_COMPROBANTE_DETRACCION_COMPRA :
                    $contOperacionTipoId = NULL;
                    break;
                case $v[42] == '1' && $v[3] . $v[7] . $v[8] == "1041559518800010000293":
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_COMPRAS_COSTO;
                    break;
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_INVOICE :
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_SERVICIO_EXTRANJERA;
                    //Pasame el monto al subTotal
                    $valores->subtotal = $valores->total;
                    $v[13] = 0;
                    $banderaPesonaExtranjera = true;
                    break;
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_RECIBO_HONORARIO :
                    $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_COMPRAS_RECIBO_HONORARIO;
                    break;
            }
            //FIN 
            //GUARDAMOS EL DOCUMENTO
            try {
                $camposDinamicos = $this->obtenerCamposDinamicosSGI($documentoTipoIdSGI, $valores);
                $resDocId = DocumentoNegocio::create()->guardar($documentoTipoIdSGI, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, $descripcion, null, null, null, $periodoId, 1, $contOperacionTipoId);
                $documentoId = (int) $resDocId[0]['vout_id'];
            } catch (Exception $ex) {
                $mensajeErrorDocumento = "Error al intentar guardar el documento " . $codigoCompletoDocumento . " :  " . $ex->getMessage();
                throw new WarningException($mensajeErrorDocumento);
            }
            //FIN DE GUARDAR EL DOCUMENTO
            //------------ ACTUALIZAR NO AFECTO Y TIPO DE CAMBIO PERSONALIZADO EN SGI ----------------
            $montoNoAfecto = $v[13];
            $tcLiq = $v[14];

            $resAct = $this->actualizarDocumentoValoresTipoCambioMontoNoAfecto($documentoId, $tcLiq, $montoNoAfecto);
            //------------ FIN ACTUALIZAR NO AFECTO Y TIPO DE CAMBIO PERSONALIZADO EN SGI ------------

            switch (TRUE) {
                //ASIENTO POR DEVOLUCION DE DINERO
                case $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_OTROS && $v[1] == '0626':
                    $planContableBancoCodigo = $dataCuenta[0]['plan_contable_codigo'];
                    $contLibroId = $dataCuenta[0]['cont_libro_id'];
                    $distribucionContable = array();
                    $glosa = $comentario;
                    $tipo_cambio = $v[14];

                    $periodoDevolucionId = $this->obtenerPeriodoIdXFecha($dataPeriodo, date_format(date_create($v[6]), 'd/m/Y'));
                    if (ObjectUtil::isEmpty($periodoDevolucionId)) {
                        throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto, $codigoCompletoDocumento.");
                    }

                    // Asiento de caja y banco en caso depositos.
                    $distribucionContable[] = array('persona_id' => $personaId, 'plan_contable_codigo' => $planContableBancoCodigo, 'fecha' => $v[6], 'moneda_id' => $monedaId, 'montoTotal' => abs($v[22]), 'tipo_cambio' => $tipo_cambio);
                    $distribucionContable[] = array('documento_id' => $documentoId, 'persona_id' => $personaId, 'plan_contable_codigo' => $planContablePersonaCodigo, 'fecha' => $v[6], 'moneda_id' => $monedaId, 'montoEntradaDinero' => abs($v[22]), 'tipo_cambio' => $tipo_cambio);
                    $respuestaVoucherDevolucion = ContVoucherNegocio::create()->guardarContVoucher($documentoIdPago, ContVoucherNegocio::OPERACION_TIPO_ID_CAJA_BANCO_ENTRADA, $contLibroId, $periodoDevolucionId, $monedaId, $glosa, ContVoucherNegocio::IDENTIFICADOR_LIQUIDACION, $distribucionContable, $usuarioId);
                    break;
                //Concepto por pago de DUA -> Igv 
                case $v[1] == '0628' && $isDua:
                    $arrayPeridos[] = $periodoId * 1;
                    $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 2, "documento_id" => $duaId, "plan_contable_codigo" => PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS, "monto" => abs($v[22]), "periodo_id" => $periodoId, "fecha" => $v[6], "tipo_cambio" => $v[14], "moneda_id" => $monedaId);
                    break;
                //Concepto por pago de DUA -> Percepción
                case $v[1] == '0629' && $isDua;
                    $arrayPeridos[] = $periodoId * 1;
                    $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 2, "documento_id" => $duaId, "plan_contable_codigo" => PlanContable::PLAN_CONTABLE_CODIGO_IMPUESTO_PERCEPCION, "monto" => abs($v[22]), "periodo_id" => $periodoId, "fecha" => $v[6], "tipo_cambio" => $v[14], "moneda_id" => $monedaId);
                    break;
                //Concepto por pago de DUA -> AddValorem
                case $v[1] == '0630' && $isDua:
                    $arrayPeridos[] = $periodoId * 1;
                    $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 2, "documento_id" => $duaId, "plan_contable_codigo" => PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS, "monto" => abs($v[22]), "periodo_id" => $periodoId, "fecha" => $v[6], "tipo_cambio" => $v[14], "moneda_id" => $monedaId);
                    break;
                //DOCUMENTOS QUE NO SON DEDUCIBLES 
                case $v[42] == '0' || ObjectUtil::isEmpty($v[42]) || $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_DECLARACION_JURADA || $documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_RECIBO_GASTO :
                    if (ObjectUtil::isEmpty($arrDistribucionContable)) {
                        throw new WarningException("El siguiente documento no tiene distribución contable " . $codigoCompletoDocumento);
                    }
                    $arrayPeridos[] = $periodoId * 1;
                    foreach ($arrDistribucionContable as $indice => $valor) {
                        if ($valor[1] == $v[41]) {
                            $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 1, "documento_id" => $documentoId, "plan_contable_codigo" => $valor[6], "centro_costo_codigo" => $valor[7], "porcentaje" => $valor[4], "monto" => $valor[5], "tipo_cambio" => $v[14], "moneda_id" => $monedaId);
                        }
                    }
                    break;
                //DOCUMENTOS DEDUCIBLES Y QUE INGRESAN AL REGISTRO DE COMPRAS
                case $v[42] == '1':
                    if (!ObjectUtil::isEmpty($dataPersona) && $dataPersona->tipo == 1 && trim($dataPersona->personaNombre) != trim($v[4]) && !$banderaPesonaExtranjera) {
                        throw new WarningException("El nombre del proveedor " . $v[4] . ", no esta actualizado en el SGI | " . $codigoCompletoDocumento);
                    }
                    if (ObjectUtil::isEmpty($arrDistribucionContable)) {
                        throw new WarningException("El siguiente documento no tiene distribución contable " . $codigoCompletoDocumento);
                    }

                    $arrayPeridos[] = $periodoId * 1;
                    $dataDistribucion = array();
                    // SOLO LIQUIDAMOS EL MONTO SIN DETRACCIÓN YA QUE EL MONTO SERA PAGO CON COMPROBANTE DE DETRACCION.
                    $montoPagado = abs($v[22]) * 1;

                    //SI TIENE DETRACCIÓN RESTO
                    if ($v[15] == '1' && $v[16] == '1' && !isEmpty($v[17]) && $v[17] * 1 > 0) {
                        $montoPagado = $montoPagado - ($v[17] * 1);
                    }
                    $planContableCodigoLiq = PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS;
                    if ($documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_RECIBO_HONORARIO) {
                        $planContableCodigoLiq = PlanContable::PLAN_CONTABLE_CODIGO_RH_EMITIDAS;
                    }

                    $banderaReversa = NULL;
                    if ($documentoTipoIdSGI == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_CREDITO_COMPRA) {
                        $banderaReversa = 1;
                    }

                    $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 2, "documento_id" => $documentoId, "plan_contable_codigo" => $planContableCodigoLiq, "monto" => $montoPagado, "periodo_id" => $periodoId, "tipo_cambio" => $v[14], "moneda_id" => $monedaId, "bandera_reversa" => $banderaReversa);
                    foreach ($arrDistribucionContable as $indice => $valor) {
                        if ($valor[1] == $v[41]) {
                            $dataDistribucion[] = array("linea" => ($indice + 1), "plan_contable_id" => $valor[2], "centro_costo_id" => $valor[3], "porcentaje" => $valor[4], "monto" => $valor[5]);
                        }
                    }
                    try {
                        $resGuardarDistribucion = ContDistribucionContableNegocio::create()->guardarContDistribucionContable($documentoId, $contOperacionTipoId, $dataDistribucion, $usuarioId);
                        $respuestaAprobarRechazarVistoBueno = DocumentoRevisionNegocio::create()->aprobarRechazarVistoBueno($documentoId, 'AP', NULL, $usuarioId);
                        if ($respuestaAprobarRechazarVistoBueno[0]['vout_exito'] != 1) {
                            throw new WarningException($respuestaAprobarRechazarVistoBueno[0]['vout_mensaje']);
                        }
                    } catch (Exception $ex) {
                        $mensajeErrorDocumento = "Error al intentar guardar el asiento de compras para el siguiente documento " . $codigoCompletoDocumento . ": " . $ex->getMessage();
                        throw new WarningException($mensajeErrorDocumento);
                    }
                    break;
            }
            //------------------- FIN REGISTRO -------------------------------------
            //----------- GUARDAR LAS RELACIONES DE DUA CON DOCUMENTOS LIQUIDADOS -----------
            if ($isDua) {
                $relacionEar = 1;
                //RECIBO DE GASTOS DE OTROS GASTOS y Ticket depósito NO SE TOMA EN CUENTA PARA GENERACION DE COSTOS
                if ((($v[0] == 58 || $v[0] == 57) && $v[2] == 14) || $v[2] == 11) {
                    $relacionEar = 2;
                }
                if ($duaId != null && $duaId != '') {
                    $resDocRel = DocumentoNegocio::create()->guardarDocumentoRelacionado($duaId, $documentoId, 1, 1, $usuarioIdEar, $relacionEar);
                } else if (!ObjectUtil::isEmpty($duaSerie) && !ObjectUtil::isEmpty($duaNumero)) {
                    //SI DUA SERIE Y NUMERO NO ES NULL REGISTRO LAS RELACIONES EAR CON EAR DESEMBOLSO ($documentoIdPago)
                    //PARA POSTERIORMENTE OBTENER ESAS RELACIONES AL RELACIONAR EN SGI LA DUA CON EL EAR
                    $resDocRel = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoIdPago, $documentoId, 1, 1, $usuarioIdEar, $relacionEar);
                }
            }
            //----------- FIN GUARDAR LAS RELACIONES DE DUA CON DOCUMENTOS LIQUIDADOS -----------
            //---------- GUARDAR EL ID DEL DOCUMENTO SGI EN EAR LIQ DETALLE  
            //LOS ID DE DOCUMENTOS GENERADOS SE VAN A GUARDAR PARA DEVOLVER EN EL EAR Y ACTUALIZAR EAR LIQ DETALLE
            $arrLiqDet[$index]['documento_id_sgi'] = $documentoId;
            $arrLiqDet[$index]['persona_id_sgi'] = $personaId;
            $arrLiqDet[$index]['periodo_id_sgi'] = $periodoId;
            if ($v[15] == '1' && $v[16] == '1' && !isEmpty($v[17]) && $v[17] * 1 > 0) {
                $arrLiqDet[$index]['monto_detraccion'] = $v[17] * 1;
            } else {
                $arrLiqDet[$index]['monto_detraccion'] = 0;
            }
        }

        //------------ RELACIONAR Y PAGAR FACTURAS RELACIONADA CON NOTAS DE CRÉDITO/DÉBITO O DETRACCIÓN -----------------------------
        $arrayFilterLiquidacion = array_filter($arrLiqDet, function ($item) {
            return $item[2] == 17 || $item[2] == 18 || $item[2] == 19;
        });

        if (!ObjectUtil::isEmpty($arrayFilterLiquidacion)) {
            foreach ($arrayFilterLiquidacion as $index => $item) {
                $codigoCompletoDocumento = $item[3] . " | " . $item[7] . "-" . $item[8];
                $documentoPagoId = $item['documento_id_sgi'];
                $personaPagoId = $item['persona_id_sgi'];
                $periodoId = $item['periodo_id_sgi'];
                $fechaPago = $item[6];
                $documentoTipoEAR = $item[2];
                $tipoCambio = $item[14];
                $monedaId = ($item[10] != 1 ? 4 : 2);
                $montoPagado = abs($item[22]) * 1;
                $documentoRelacion = explode("|", $item[46]);
                $consulta = ("\$dataDocumentoRelacionado = array_filter(\$arrLiqDet, function (\$documento) { return \$documento[2] == '$documentoRelacion[0]' && \$documento[3] == '$documentoRelacion[1]' && \$documento[7] == '$documentoRelacion[2]' && (\$documento[8] * 1)  == " . ($documentoRelacion[3] * 1) . "; });");
                eval($consulta);

                if (ObjectUtil::isEmpty($dataDocumentoRelacionado) || count($dataDocumentoRelacionado) !== 1) {
                    throw new WarningException("El siguiente documento " . $codigoCompletoDocumento . ", debe estar relacionado a una factura o boleta.");
                }
                foreach ($dataDocumentoRelacionado as $indexRelacion => $itemRelacion) {
                    $documentoRelacionadoId = $itemRelacion['documento_id_sgi'];
                    //----------- GUARDAR LAS RELACIONES DE NOTA DE CREDITO O DEBITO CON DOCUMENTO LIQUIDADOS ------------------------
                    if ($documentoTipoEAR == 17 || $documentoTipoEAR == 18) {
                        $relacionEar = 2;
                        $resDocRel = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoPagoId, $documentoRelacionadoId, 1, 1, $usuarioIdEar, $relacionEar);
                        $resDocRel = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoRelacionadoId, $documentoPagoId, 1, 1, $usuarioIdEar, $relacionEar);
                    }
                    //----------- FIN LAS RELACIONES DE NOTA DE CREDITO O DEBITO CON DOCUMENTO LIQUIDADOS ------------------------
                    // ==================REALIZA EL PAGO DE FACTURAS CON SU RESPECTIVA NOTA DE CREDITO/DETRACCIÓN===========================================        
                    if ($documentoTipoEAR == 18 || $documentoTipoEAR == 19) {
                        $dataDocNota = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoPagoId);
                        //DAR FORMATO AL DOCUMENTO DE PAGO   
                        $arrayDocumentoPagoNota = array();
                        $arrayDocumentoPagoNota['documentoId'] = $dataDocNota[0]['documento_id'];
                        $arrayDocumentoPagoNota['tipoDocumento'] = $dataDocNota[0]['documento_tipo'];
                        $arrayDocumentoPagoNota['tipoDocumentoId'] = $dataDocNota[0]['documento_tipo_id'];
                        $arrayDocumentoPagoNota['numero'] = $dataDocNota[0]['numero'];
                        $arrayDocumentoPagoNota['serie'] = $dataDocNota[0]['serie'];
                        $arrayDocumentoPagoNota['pendiente'] = $dataDocNota[0]['pendiente'];
                        $arrayDocumentoPagoNota['total'] = $dataDocNota[0]['total'];
                        $arrayDocumentoPagoNota['dolares'] = $dataDocNota[0]['dolares'];
                        $arrayDocumentoPagoNota['moneda'] = $dataDocNota[0]['dolares'] * 1 === 0 ? "Soles" : "Dolares";
                        $arrayDocumentoPagoNota['monto'] = $dataDocNota[0]['monto'];

                        $arrayDocumentoPagoNota = array($arrayDocumentoPagoNota);
                        // OBTENEMOS EL DOCUMENTO RELACIONADO A PAGAR CON LA NOTA DE CREDITO
                        $dataDocFac = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoRelacionadoId);
                        $arrayDocumentoFacAPagar = array();
                        $arrayDocumentoFacAPagar['documentoId'] = $dataDocFac[0]['documento_id'];
                        $arrayDocumentoFacAPagar['tipoDocumento'] = $dataDocFac[0]['documento_tipo'];
                        $arrayDocumentoFacAPagar['numero'] = $dataDocFac[0]['numero'];
                        $arrayDocumentoFacAPagar['serie'] = $dataDocFac[0]['serie'];
                        $arrayDocumentoFacAPagar['pendiente'] = $dataDocFac[0]['pendiente'];
                        $arrayDocumentoFacAPagar['dolares'] = $dataDocFac[0]['mdolares'];
                        $arrayDocumentoFacAPagar['total'] = $dataDocFac[0]['total'];
                        $arrayDocumentoFacAPagar['tipo'] = $dataDocFac[0]['tipo'];
                        $arrayDocumentoFacAPagar = array($arrayDocumentoFacAPagar);

                        $fecha = date("d/m/Y");
                        $usuarioId = $usuarioIdEar;
                        $montoAPagar = 0; //para pago efectivo
                        $monedaPago = $monedaId;
                        $retencion = 1; //siempre, en sgi todavia no se usa
                        $empresaId = 2; //para pago efectivo
                        $actividadEfectivo = null; //para pago efectivo                      

                        $resPago = PagoNegocio::create()->registrarPago($personaPagoId, $fecha, $arrayDocumentoFacAPagar, $arrayDocumentoPagoNota, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
                    }
                    //EN EL CASO DE PAGAR CON DETRACCIÓN SE ANULA DETRACCIÓN PENDIENTE
                    if ($documentoTipoEAR == 19) {
                        $arrLiqDet[$indexRelacion]['monto_detraccion'] = 0;
                        $arrayPeridos[] = $periodoId * 1;
                        $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 2, "documento_id" => $documentoRelacionadoId, "plan_contable_codigo" => PlanContable::PLAN_CONTABLE_CODIGO_F_B_EMITIDAS, "monto" => $montoPagado, "periodo_id" => $periodoId, "tipo_cambio" => $tipoCambio, "moneda_id" => $monedaId, "fecha" => $fechaPago);
                    }
                }
                //=====================================FIN REALIZA EL PAGO DE FACTURAS CON SU RESPECTIVA NOTA DE CREDITO=============================================  
            }
        }



        //------------ FIN DE RELACIONAR Y PAGAR FACTURAS RELACIONADA CON NOTAS DE CRÉDITO/DÉBITO O DETRACCIÓN -----------------------------
        //------------ ACTUALIZAR LA TABLA COSTO_CIF CON LOS VALORES -----------------------------
        if ($isDua && $duaId != null && $duaId != '') {
            $resActualizarCostos = MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($duaId, $usuarioIdEar);
        }
        //------------ FIN ACTUALIZAR LA TABLA COSTO_CIF CON LOS VALORES -------------------------
        //REGISTRAR DOCUMENTO DE PLANILLA EN SGI
        $documentoPlanillaId = null;
        if (!ObjectUtil::isEmpty($parametrosPlanilla)) {
            $opcionId = null;
            $usuarioId = $parametrosPlanilla->usuarioId;
            $documentoTipoIdPlan = $parametrosPlanilla->documentoTipoIdSgi;
            $monedaId = $parametrosPlanilla->monedaId;
            $comentario = $parametrosPlanilla->comentario;

            $usuarioData = UsuarioNegocio::create()->getUsuario($parametrosPlanilla->usuarioPersona);
            $parametrosPlanilla->camposDinamicos->persona = $usuarioData['0']['persona_id'];
            $fechaEmisionPlanillaMovilidad = $parametrosPlanilla->camposDinamicos->fechaEmision;

            $dataTipoCambioFechaPlanilla = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fechaEmisionPlanillaMovilidad);
            if (ObjectUtil::isEmpty($dataTipoCambioFechaPlanilla)) {
                throw new WarningException("No existe el tipo de cambio para la siguiente fecha de planilla de movilidad :" . $fechaEmisionPlanillaMovilidad);
            }
            $tipoCambioPlanilla = $dataTipoCambioFechaPlanilla[0]['equivalencia_venta'];

            $camposDinamicosPlan = $this->obtenerCamposDinamicosSGI($documentoTipoIdPlan, $parametrosPlanilla->camposDinamicos);

            //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION
            if (ObjectUtil::isEmpty($periodoIdEar) || $periodoIdEar == -1) {
                $periodoId = $this->obtenerPeriodoIdXFecha($dataPeriodo, date_format(date_create($fechaEmisionPlanillaMovilidad), 'd/m/Y'));
                if (ObjectUtil::isEmpty($periodoId)) {
                    throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . date_format(date_create($fechaEmisionPlanillaMovilidad), 'd/m/Y') . " no esta abierto.");
                }
                $fechaContabilizacionPlanillaMov = $fechaEmisionPlanillaMovilidad;
            } else {
                $periodoId = $periodoIdEar;
                $dataPeriodo = Util::filtrarArrayPorColumna($dataPeriodo, "id", $periodoId);
                $fechaUltimoDiaMes = Util::obtenerUltimoDiaMes($dataPeriodo[0]['anio'], $dataPeriodo[0]['mes']);
                if (strtotime(substr($fechaEmisionPlanillaMovilidad, 0, 10)) > strtotime($fechaUltimoDiaMes)) {
                    $fechaContabilizacionPlanillaMov = $fechaUltimoDiaMes;
                }
            }
            $arrayPeridos[] = $periodoId * 1;
            //FIN PERIODO
            $resDocId = DocumentoNegocio::create()->guardar($documentoTipoIdPlan, null, null, $camposDinamicosPlan, 1, $usuarioId, $monedaId, $comentario, null, null, null, null, $periodoId, 1, null);
            $documentoPlanillaId = (int) $resDocId[0]['vout_id'];

            if (ObjectUtil::isEmpty($parametrosPlanilla->distribucionContable)) {
                throw new WarningException("Aún no ingresa la distribución contable en la planilla de movilidad.");
            }

            foreach ($parametrosPlanilla->distribucionContable as $indice => $item) {
                $arrayDetalleDistribucionLiquidacion[] = array("tipo" => 1, "fecha" => $fechaContabilizacionPlanillaMov, "documento_id" => $documentoPlanillaId, "plan_contable_codigo" => $item[6], "centro_costo_codigo" => $item[7], "porcentaje" => $item[4], "monto" => $item[5], "periodo_id" => $periodoId, "tipo_cambio" => $tipoCambioPlanilla, "moneda_id" => $monedaId);
            }
            //------------------- FIN REGISTRO -------------------------------------
        }

        $banderaSaltarValidacion = false;
        if ($earNumero === '2020-09-001/LPY') {
            $banderaSaltarValidacion = true;
        }

        /*         * ****************************************  Obtenemos los periodos registrados pueden máximo 2     ************************************** */
        $glosa = "Liquidación " . $earNumero . " de " . $colaboradorNombreCompleto . ", fecha de liquidación " . $fechaLiquidacion;

        $arrayPeridos = array_unique($arrayPeridos);

        $ultimoPeriodo = max($arrayPeridos);
        $primerPeriodo = min($arrayPeridos);
        if (ObjectUtil::isEmpty($arrayPeridos)) {
            throw new WarningException("No existen periodos disponibles para registrar el asiento contable.");
        }

        if (count($arrayPeridos) > 2) {
            throw new WarningException("Existen más de 2 periodos relacionados al EAR, por favor verifique la información o comuniquese con el área responsable.");
        }

        $dataUltimoPeriodo = Util::filtrarArrayPorColumna($dataPeriodo, "id", $ultimoPeriodo);
        $fechaUltimoDiaUltimoPeriodo = Util::obtenerUltimoDiaMes($dataUltimoPeriodo[0]['anio'], $dataUltimoPeriodo[0]['mes']);
        if (strtotime($fechaUltimoDiaUltimoPeriodo) > strtotime(date("Y-m-d"))) {
            $fechaUltimoDiaUltimoPeriodo = date("Y-m-d");
        }

        $anioFechaLiquidacion = (date_format(date_create($fechaLiquidacion), 'Y') * 1);
        $mesFechaLiquidacion = (date_format(date_create($fechaLiquidacion), 'm') * 1);

        if ((($dataUltimoPeriodo[0]['anio'] * 1) > $anioFechaLiquidacion)) { // || (($dataUltimoPeriodo[0]['anio'] * 1) == $anioFechaLiquidacion && ($dataUltimoPeriodo[0]['mes'] * 1) < $mesFechaLiquidacion)) {
            throw new WarningException("La fecha de liquidación $fechaLiquidacion, no pertenece al último periodo seleccionado (Puede ser máximo 10 días).");
        } elseif (( ($dataUltimoPeriodo[0]['anio'] * 1) < $anioFechaLiquidacion || ($mesFechaLiquidacion > ($dataUltimoPeriodo[0]['mes'] * 1) && ($dataUltimoPeriodo[0]['anio'] * 1) == $anioFechaLiquidacion)) && Util::diasTranscurridos($fechaUltimoDiaUltimoPeriodo, date_format(date_create($fechaLiquidacion), 'Y-m-d')) > 10 && !$isDua && !$banderaSaltarValidacion) {
            throw new WarningException("La fecha de liquidación $fechaLiquidacion, no pertenece al último periodo seleccionado (Puede ser máximo 10 días).");
        } elseif (($dataUltimoPeriodo[0]['anio'] * 1) < $anioFechaLiquidacion || ($mesFechaLiquidacion > ($dataUltimoPeriodo[0]['mes'] * 1) && ($dataUltimoPeriodo[0]['anio'] * 1) == $anioFechaLiquidacion)) {
            $fechaLiquidacion = $fechaUltimoDiaUltimoPeriodo;
        }


        if (count($arrayPeridos) == 2) {
            $dataPeriodoMinimo = Util::filtrarArrayPorColumna($dataPeriodo, "id", $primerPeriodo);
            $fechaUltimoDiaPeriodoMinimo = Util::obtenerUltimoDiaMes($dataPeriodoMinimo[0]['anio'], $dataPeriodoMinimo[0]['mes']);
            $dataTipoCambioUltimoDiaPeriodo = TipoCambioNegocio::create()->obtenerTipoCambioXfecha($fechaUltimoDiaPeriodoMinimo);
            if (ObjectUtil::isEmpty($dataTipoCambioUltimoDiaPeriodo)) {
                throw new WarningException("No existe el tipo de cambio para la siguiente fecha :" . $fechaUltimoDiaPeriodoMinimo);
            }
            $tipoCambioUltimoDiaPeriodoMinimo = $dataTipoCambioUltimoDiaPeriodo[0]['equivalencia_venta'];
        }

        /*         * *****************************************  Registramos el asiento de liquidación ************************************ */

        foreach ($arrayPeridos as $periodoItem) {
            $montoTotalVoucher = 0;
            $montoTotalVoucherSoles = 0;
            $distribucionContable = array();
            // Tomamos el tipo de cambio de cada linea de liquidación para obtener y pasarlo a la fecha de liquidación para obtener las diferencias de cambio.
            foreach ($arrayDetalleDistribucionLiquidacion as $indice => $item) {
                // Tipo = 1 no están en registro de compras, solo se aplican para el último periodo
                // Tipo = 2 ingresado en registro de compras, pueden ser de periodo diferente.
                if (($item['tipo'] == 2 && $item['periodo_id'] == $periodoItem) || ($ultimoPeriodo == $periodoItem && $item['tipo'] == 1)) {
                    $distribucionContable [] = $item;
                    $factor = 1;
                    if ($item['bandera_reversa'] == "1") {
                        $factor = -1;
                    }
                    $montoDistribucion = $factor * $item['monto'];

                    if ($monedaIdEar == $item['moneda_id']) {
                        $montoTotalVoucherSoles = Util::redondearNumero($montoTotalVoucherSoles + ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES ? $montoDistribucion * ($item['tipo_cambio'] * 1) : $montoDistribucion), 6);
                    } elseif ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_SOLES && $monedaIdEar == ContVoucherNegocio::MONEDA_ID_DOLARES) {
                        $montoTotalVoucherSoles = Util::redondearNumero($montoTotalVoucherSoles + $montoDistribucion, 6);
                        $montoDistribucion = $montoDistribucion / ($item['tipo_cambio'] * 1);
                    } elseif ($item['moneda_id'] == ContVoucherNegocio::MONEDA_ID_DOLARES && $monedaIdEar == ContVoucherNegocio::MONEDA_ID_SOLES) {
                        $montoDistribucion = $montoDistribucion * ($item['tipo_cambio'] * 1);
                        $montoTotalVoucherSoles = Util::redondearNumero($montoTotalVoucherSoles + $montoDistribucion, 6);
                    }
                    $montoTotalVoucher = Util::redondearNumero($montoTotalVoucher + $montoDistribucion, 6);
                }
            }
            $fechaPago = $fechaLiquidacion;
            $tipoCambioPago = $tipoCambioFechaLiquidacion;

            if ($ultimoPeriodo != $periodoItem) {
                $fechaPago = $fechaUltimoDiaPeriodoMinimo;
                $tipoCambioPago = $tipoCambioUltimoDiaPeriodoMinimo;
            }

            switch (TRUE) {
                //Ganancia diferencia de cambio
                case $monedaIdEar == ContVoucherNegocio::MONEDA_ID_SOLES && Util::redondearNumero($montoTotalVoucher, 2) < Util::redondearNumero($montoTotalVoucherSoles, 2):
                case $monedaIdEar == ContVoucherNegocio::MONEDA_ID_DOLARES && Util::redondearNumero($montoTotalVoucher * ($tipoCambioPago * 1), 2) < Util::redondearNumero($montoTotalVoucherSoles, 2):
                    if ($monedaIdEar == ContVoucherNegocio::MONEDA_ID_SOLES) {
                        $montoDiferenciaCambio = ($montoTotalVoucherSoles * 1) - ($montoTotalVoucher * 1);
                    } else {
                        $montoDiferenciaCambio = ($montoTotalVoucherSoles * 1) - ($montoTotalVoucher * ($tipoCambioPago * 1));
                    }
                    $distribucionContable[] = array('persona_id' => $colaboradorId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoDiferenciaGanancia' => $montoDiferenciaCambio);
                    break;
                //Perdida diferencia de cambio
                case $monedaIdEar == ContVoucherNegocio::MONEDA_ID_SOLES && Util::redondearNumero($montoTotalVoucher, 2) > Util::redondearNumero($montoTotalVoucherSoles, 2):
                case $monedaIdEar == ContVoucherNegocio::MONEDA_ID_DOLARES && Util::redondearNumero($montoTotalVoucher * ($tipoCambioPago * 1), 2) > Util::redondearNumero($montoTotalVoucherSoles, 2):
                    if ($monedaIdEar == ContVoucherNegocio::MONEDA_ID_SOLES) {
                        $montoDiferenciaCambio = ($montoTotalVoucher * 1) - ($montoTotalVoucherSoles * 1);
                    } else {
                        $montoDiferenciaCambio = ($montoTotalVoucher * ($tipoCambioPago * 1)) - ($montoTotalVoucherSoles);
                    }
                    $distribucionContable[] = array('centro_costo_codigo' => CentroCosto::CENTRO_COSTO_CODIGO_GASTOS_FINANCIEROS, 'persona_id' => $colaboradorId, 'fecha' => $fechaPago, 'moneda_id' => ContVoucherNegocio::MONEDA_ID_SOLES, 'montoDiferenciaPerdida' => $montoDiferenciaCambio);
                    break;
            }

            $distribucionContable[] = array('persona_id' => $colaboradorId, 'plan_contable_codigo' => $planContablePersonaCodigo, 'fecha' => $fechaPago, 'tipo_cambio' => $tipoCambioPago, 'moneda_id' => $monedaIdEar, 'montoTotal' => $montoTotalVoucher);

            $respuestaVoucherLiquidacion = ContVoucherNegocio::create()->guardarContVoucher($documentoIdPago, ContVoucherNegocio::OPERACION_TIPO_ID_LIQUIDACION, NULL, $periodoItem, $monedaIdEar, $glosa, ContVoucherNegocio::IDENTIFICADOR_LIQUIDACION, $distribucionContable, $usuarioId);
        }


        //-------------------------- REGISTRAR PAGO EN SGI --------------------------------------
        $fecha = date("d/m/Y");
        $usuarioId = $usuarioIdEar;
        $montoAPagar = 0; //para pago efectivo
        $monedaPago = $monedaIdEar;
        $retencion = 1; //siempre, en sgi todavia no se usa
        $empresaId = 2; //para pago efectivo
        $actividadEfectivo = null; //para pago efectivo
        $proveedor = $colaboradorId;

        $documentoPagoConDocumento = array();
        if ($documentoIdPago != null && $documentoIdPago != '') {
            $dataDocPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPago);
            //DAR FORMATO AL DOCUMENTO DE PAGO     
            $arrayDocumentoPago = array();
            $arrayDocumentoPago['documentoId'] = $dataDocPago[0]['documento_id'];
            $arrayDocumentoPago['tipoDocumento'] = $dataDocPago[0]['documento_tipo'];
            $arrayDocumentoPago['tipoDocumentoId'] = $dataDocPago[0]['documento_tipo_id'];
            $arrayDocumentoPago['numero'] = $dataDocPago[0]['numero'];
            $arrayDocumentoPago['serie'] = $dataDocPago[0]['serie'];
            $arrayDocumentoPago['pendiente'] = $dataDocPago[0]['pendiente'];
            $arrayDocumentoPago['total'] = $dataDocPago[0]['total'];
            $arrayDocumentoPago['dolares'] = $dataDocPago[0]['dolares'];
            $arrayDocumentoPago['moneda'] = $dataDocPago[0]['dolares'] * 1 === 0 ? "Soles" : "Dolares";
            $arrayDocumentoPago['monto'] = $dataDocPago[0]['monto'];

            array_push($documentoPagoConDocumento, $arrayDocumentoPago);
        }
        //-- DOCUMENTOS A PAGAR,OBTENIENDO LOS IDS DE LOS DOCUMENTOS SGI
        //-- DEL DETALLE DE LA LIQUIDACION
        $listaEarLiqDetalle = $arrLiqDet;

        foreach ($listaEarLiqDetalle as $item) {
            $documentoId = $item['documento_id_sgi'];
            $tipoCambio = $item[14];

            if (($documentoId != null && $documentoId != '') && $item[2] != 18) {
                $dataDoc = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);

                //DAR FORMATO DOCUMENTOS A PAGAR    
                $arrayDocumentoAPagar = array();
                $arrayDocumentoAPagar['documentoId'] = $dataDoc[0]['documento_id'];
                $arrayDocumentoAPagar['tipoDocumento'] = $dataDoc[0]['documento_tipo'];
                $arrayDocumentoAPagar['numero'] = $dataDoc[0]['numero'];
                $arrayDocumentoAPagar['serie'] = $dataDoc[0]['serie'];
                $arrayDocumentoAPagar['pendiente'] = (($dataDoc[0]['pendiente'] * 1) - ($item['monto_detraccion'] * 1));
                $arrayDocumentoAPagar['dolares'] = $dataDoc[0]['mdolares'];
                $arrayDocumentoAPagar['total'] = $dataDoc[0]['total'];
                $arrayDocumentoAPagar['tipo'] = $dataDoc[0]['tipo'];

                $documentoAPagar = array();
                array_push($documentoAPagar, $arrayDocumentoAPagar);

                //OBTENER MONTO PENDIENTE 
                $dataPendPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPago);
                $documentoPagoConDocumento[0]['monto'] = $dataPendPago[0]['monto'];
                if ($dataPendPago[0]['monto'] * 1 > 0) {
                    //SE VA A PAGAR DE UNO A UNO LOS DOCUMENTOS POR QUE EL SGI EN GRUPO CON DIFERENTES MONEDAS NO PERMITE PAGAR
                    if ($documentoIdPago != null && $documentoIdPago != '') {
                        $resPago = PagoNegocio::create()->registrarPago($proveedor, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
                    }
                }
            }
        }

        //-- DE LA PLANILLA DE MOVILIDAD
        if (!ObjectUtil::isEmpty($documentoPlanillaId) > 0) {
            $documentoId = $documentoPlanillaId;
            $dataDoc = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);

            //DAR FORMATO DOCUMENTOS A PAGAR    
            $arrayDocumentoAPagar = array();
            $arrayDocumentoAPagar['documentoId'] = $dataDoc[0]['documento_id'];
            $arrayDocumentoAPagar['tipoDocumento'] = $dataDoc[0]['documento_tipo'];
            $arrayDocumentoAPagar['numero'] = $dataDoc[0]['numero'];
            $arrayDocumentoAPagar['serie'] = $dataDoc[0]['serie'];
            $arrayDocumentoAPagar['pendiente'] = $dataDoc[0]['pendiente'];
            $arrayDocumentoAPagar['dolares'] = $dataDoc[0]['mdolares'];
            $arrayDocumentoAPagar['total'] = $dataDoc[0]['total'];
            $arrayDocumentoAPagar['tipo'] = $dataDoc[0]['tipo'];

            $documentoAPagar = array();
            array_push($documentoAPagar, $arrayDocumentoAPagar);

            //OBTENER MONTO PENDIENTE 
            $dataPendPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPago);
            $documentoPagoConDocumento[0]['monto'] = $dataPendPago[0]['monto'];
            if ($dataPendPago[0]['monto'] > 0) {
                //SE VA A PAGAR DE UNO A UNO LOS DOCUMENTOS POR QUE EL SGI EN GRUPO CON DIFERENTES MONEDAS NO PERMITE PAGAR
                if ($documentoIdPago != null && $documentoIdPago != '') {
                    $resPago = PagoNegocio::create()->registrarPago($proveedor, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
                }
            }
        }

        //------- SIMULAR ERROR --------------
//        throw new WarningException("No se pudo guardar el documento, PROBANDO");
        //-----------FIN-------------
        //---------- GENERAR DOCUMENTO DE REEMBOLSO EN SGI --------------------
        if ($earLiqDcto < 0) {
            //SE OBTIENE EL ID DEL DOCUMENTO TIPO SGI DE PAGO: EAR Reembolso (PAGOS)
            $documentoTipoIdSGI = $documentoTipoIdReembolsoSGI;

            //------------------- REGISTRAR EN EL SGI EL DOCUMENTO DE PAGO -------------------------    
            $valores->persona = $colaboradorId;
            $valores->numero = 'auto';
            $valores->fechaEmision = date("Y-m-d");
            $valores->total = $earLiqDcto * -1;
            $valores->cuenta = 'defecto';
            $valores->actividad = 'defecto';

            $camposDinamicos = $this->obtenerCamposDinamicosSGI($documentoTipoIdSGI, $valores);

            $usuarioId = $usuarioIdEar;
            $monedaId = $monedaIdEar;
            $movimientoId = null;
            $comentario = null;
            $descripcion = null;

            //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION
            if (ObjectUtil::isEmpty($periodoIdEar) || $periodoIdEar == -1) {
                $fechaEmision = date_create($valores->fechaEmision);
                $fechaEmision = date_format($fechaEmision, 'd/m/Y');
                $periodoId = $this->obtenerPeriodoIdXFecha($dataPeriodo, $fechaEmision);
                if (ObjectUtil::isEmpty($periodoId)) {
                    throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto.");
                }
            } else {
                $periodoId = $periodoIdEar;
            }
            //FIN PERIODO

            $resDocId = DocumentoNegocio::create()->guardar($documentoTipoIdSGI, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, $descripcion, null, null, null, $periodoId);
            $documentoIdPagoReembolso = (int) $resDocId[0]['vout_id'];
            //------------------- FIN REGISTRO -------------------------------------

            $documentoPagoConDocumento = array();
            $dataDocPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPagoReembolso);

            //DAR FORMATO AL DOCUMENTO DE PAGO   
            $arrayDocumentoPago = array();
            $arrayDocumentoPago['documentoId'] = $dataDocPago[0]['documento_id'];
            $arrayDocumentoPago['tipoDocumento'] = $dataDocPago[0]['documento_tipo'];
            $arrayDocumentoPago['tipoDocumentoId'] = $dataDocPago[0]['documento_tipo_id'];
            $arrayDocumentoPago['numero'] = $dataDocPago[0]['numero'];
            $arrayDocumentoPago['serie'] = $dataDocPago[0]['serie'];
            $arrayDocumentoPago['pendiente'] = $dataDocPago[0]['pendiente'];
            $arrayDocumentoPago['total'] = $dataDocPago[0]['total'];
            $arrayDocumentoPago['dolares'] = $dataDocPago[0]['dolares'];
            $arrayDocumentoPago['moneda'] = $dataDocPago[0]['dolares'] * 1 === 0 ? "Soles" : "Dolares";
            $arrayDocumentoPago['monto'] = $dataDocPago[0]['monto'];

            array_push($documentoPagoConDocumento, $arrayDocumentoPago);

            $montoAPagar = 0; //para pago efectivo
            $monedaPago = $monedaIdEar;
            $retencion = 1; //siempre, en sgi todavia no se usa
            $empresaId = 2; //para pago efectivo
            $actividadEfectivo = null; //para pago efectivo
            //LISTA DE EAR LIQ DETALLE
            $listaEarLiqDetalle = $arrLiqDet;
            foreach ($listaEarLiqDetalle as $item) {
                $documentoId = $item['documento_id_sgi'];
                $tipoCambio = $item[14];
                if (!ObjectUtil::isEmpty($documentoId)) {
                    $dataDoc = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);

                    //DAR FORMATO DOCUMENTOS A PAGAR    
                    $arrayDocumentoAPagar = array();
                    $arrayDocumentoAPagar['documentoId'] = $dataDoc[0]['documento_id'];
                    $arrayDocumentoAPagar['tipoDocumento'] = $dataDoc[0]['documento_tipo'];
                    $arrayDocumentoAPagar['numero'] = $dataDoc[0]['numero'];
                    $arrayDocumentoAPagar['serie'] = $dataDoc[0]['serie'];
                    $arrayDocumentoAPagar['pendiente'] = (($dataDoc[0]['pendiente'] * 1) - ($item['monto_detraccion'] * 1));
                    $arrayDocumentoAPagar['dolares'] = $dataDoc[0]['mdolares'];
                    $arrayDocumentoAPagar['total'] = $dataDoc[0]['total'];
                    $arrayDocumentoAPagar['tipo'] = $dataDoc[0]['tipo'];

                    if ($dataDoc[0]['pendiente'] > 0) {
                        $documentoAPagar = array();
                        array_push($documentoAPagar, $arrayDocumentoAPagar);
                        //OBTENER MONTO PENDIENTE 
                        $dataPendPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPagoReembolso);
                        $documentoPagoConDocumento[0]['monto'] = $dataPendPago[0]['monto'];
                        if ($dataPendPago[0]['monto'] * 1 > 0) {
                            //SE VA A PAGAR DE UNO A UNO LOS DOCUMENTOS POR QUE EL SGI EN GRUPO CON DIFERENTES MONEDAS NO PERMITE PAGAR                               
                            $resPago = PagoNegocio::create()->registrarPago($proveedor, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
                        }
                    }
                }
            }

            //-- DE LA PLANILLA DE MOVILIDAD
            $documentoId = $documentoPlanillaId;
            if (!ObjectUtil::isEmpty($documentoId)) {
                $dataDoc = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);

                //DAR FORMATO DOCUMENTOS A PAGAR    
                $arrayDocumentoAPagar = array();
                $arrayDocumentoAPagar['documentoId'] = $dataDoc[0]['documento_id'];
                $arrayDocumentoAPagar['tipoDocumento'] = $dataDoc[0]['documento_tipo'];
                $arrayDocumentoAPagar['numero'] = $dataDoc[0]['numero'];
                $arrayDocumentoAPagar['serie'] = $dataDoc[0]['serie'];
                $arrayDocumentoAPagar['pendiente'] = $dataDoc[0]['pendiente'];
                $arrayDocumentoAPagar['dolares'] = $dataDoc[0]['mdolares'];
                $arrayDocumentoAPagar['total'] = $dataDoc[0]['total'];
                $arrayDocumentoAPagar['tipo'] = $dataDoc[0]['tipo'];

                if ($dataDoc[0]['pendiente'] > 0) {
                    $documentoAPagar = array();
                    array_push($documentoAPagar, $arrayDocumentoAPagar);
                    //OBTENER MONTO PENDIENTE 
                    $dataPendPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPagoReembolso);
                    $documentoPagoConDocumento[0]['monto'] = $dataPendPago[0]['monto'];
                    if ($dataPendPago[0]['monto'] > 0) {
                        //SE VA A PAGAR DE UNO A UNO LOS DOCUMENTOS POR QUE EL SGI EN GRUPO CON DIFERENTES MONEDAS NO PERMITE PAGAR
                        $resPago = PagoNegocio::create()->registrarPago($proveedor, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1
                        );
                    }
                }
            }
            //---------- FIN REEMBOLSO --------------------------------------------
        } else if ($earLiqDcto > 0) {
            //---------- GENERAR DOCUMENTO DE DEVOLUCION EN SGI -------COBRANZA Y PAGO-------------
            //SE OBTIENE EL ID DEL DOCUMENTO TIPO SGI DE COBRANZA: EAR Devolución (Cobranza)
            $documentoTipoIdSGI = $documentoTipoIdDevolucionSGI;
            $documentoTipoIdSisSGI = $documentoTipoIdDevolucionSisSGI; //TIPO 4 DE PAGO PARA COMPENSAR EL DESEMBOLSO Y NO QUEDE PENDIENTE.
            //------------------- REGISTRAR EN EL SGI EL DOCUMENTO DE PAGO -------------------------    
            $valores->persona = $colaboradorId; //$usuarioData se obtuvo en Pago
            $valores->numero = $earNumero;
            $valores->fechaEmision = $fechaPago;
            $valores->total = $earLiqDcto;

            $camposDinamicos = $this->obtenerCamposDinamicosSGI($documentoTipoIdSGI, $valores);
            $camposDinamicosSis = $this->obtenerCamposDinamicosSGI($documentoTipoIdSisSGI, $valores);

            $usuarioId = $usuarioIdEar;
            $monedaId = $monedaIdEar;
            $movimientoId = null;
            $comentario = null;
            $descripcion = null;

            //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION
//            if (ObjectUtil::isEmpty($periodoIdEar) || $periodoIdEar == -1) {
            $fechaEmision = date_create($valores->fechaEmision);
            $fechaEmision = date_format($fechaEmision, 'd/m/Y');
            $periodoId = $this->obtenerPeriodoIdXFecha($dataPeriodo, $fechaEmision);
            if (ObjectUtil::isEmpty($periodoId)) {
                throw new WarningException("Periodo inválido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto.");
            }
//            } else {
//                $periodoId = $periodoIdEar;
//            }
            //FIN PERIODO

            $resDocId = DocumentoNegocio::create()->guardar($documentoTipoIdSGI, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, $descripcion, null, null, null, $periodoId);
            $resDocIdSis = DocumentoNegocio::create()->guardar($documentoTipoIdSisSGI, $movimientoId, null, $camposDinamicosSis, 1, $usuarioId, $monedaId, $comentario, $descripcion, null, null, null, $periodoId);
            $documentoDevolucionId = (int) $resDocId[0]['vout_id'];
            //------------------- FIN REGISTRO DEVOLUCIONES-------------------------------------
            //REGISTRAR PAGO DE DOCUMENTOS SISTEMA DE DEVOLUCION
            if (!ObjectUtil::isEmpty($resDocIdSis[0]['vout_id'])) {
                $documentoId = $resDocIdSis[0]['vout_id'];
                $dataDoc = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoId);

                //DAR FORMATO DOCUMENTOS A PAGAR    
                $arrayDocumentoAPagar = array();
                $arrayDocumentoAPagar['documentoId'] = $dataDoc[0]['documento_id'];
                $arrayDocumentoAPagar['tipoDocumento'] = $dataDoc[0]['documento_tipo'];
                $arrayDocumentoAPagar['numero'] = $dataDoc[0]['numero'];
                $arrayDocumentoAPagar['serie'] = $dataDoc[0]['serie'];
                $arrayDocumentoAPagar['pendiente'] = $dataDoc[0]['pendiente'];
                $arrayDocumentoAPagar['dolares'] = $dataDoc[0]['mdolares'];
                $arrayDocumentoAPagar['total'] = $dataDoc[0]['total'];
                $arrayDocumentoAPagar['tipo'] = $dataDoc[0]['tipo'];

                $documentoAPagar = array();
                array_push($documentoAPagar, $arrayDocumentoAPagar);

                //OBTENER MONTO PENDIENTE 
                $dataPendPago = DocumentoNegocio::create()->obtenerDocumentoAPagar($documentoIdPago);
                $documentoPagoConDocumento[0]['monto'] = $dataPendPago[0]['monto'];
                if ($dataPendPago[0]['monto'] > 0) {
                    //SE VA A PAGAR DE UNO A UNO LOS DOCUMENTOS POR QUE EL SGI EN GRUPO CON DIFERENTES MONEDAS NO PERMITE PAGAR
                    if ($documentoIdPago != null && $documentoIdPago != '') {
                        $resPago = PagoNegocio::create()->registrarPago($proveedor, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
                    }
                }
            }
            //FIN REGISTRO DE PAGO
        }

        $respuesta->arrLiqDet = $arrLiqDet;
        $respuesta->documentoPlanillaId = $documentoPlanillaId;
        $respuesta->documentoDevolucionId = $documentoDevolucionId;
        return $respuesta;
    }

    public function personaSgiGuardaObtieneXDocumentoXRazonSocial($documento, $razonSocial, $usuarioId) {
        $personaId = '';
        $personaNombre = '';
        $tipo = '';
        $resPersona = PersonaNegocio::create()->obtenerPersonaXCodigoIdentificacion($documento);

        if (!ObjectUtil::isEmpty($resPersona)) {
            $personaId = $resPersona[0]['id'];
            $personaNombre = $resPersona[0]['persona_nombre'];
            $tipo = 1;
        }

        if ($personaId == '') {
            //inserto la persona en SGI        
            $personaTipoIdo = 4;
            $codigoIdentificacion = $documento;
            $nombre = $razonSocial;
            $apellidoPaterno = null;
            $apellidoMaterno = null;
            $telefono = null;
            $celular = null;
            $email = null;
            $file = null;
            $estado = 1;
            $usuarioCreacion = $usuarioId;
            $empresa = array(2);
            $clase = array(-1);
            $listaContactoDetalle = null;
            $listaDireccionDetalle = null;
            $codigoSunatId = null;
            $codigoSunatId2 = null;
            $codigoSunatId3 = null;
            $resp = PersonaNegocio::create()->insertPersona($personaTipoIdo, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $file, $estado, $usuarioCreacion, $empresa, $clase, $listaContactoDetalle, $listaDireccionDetalle, $codigoSunatId, $codigoSunatId2, $codigoSunatId3);

            if ($resp[0]["vout_exito"] == 1) {
                $personaId = $resp[0]["id"];
                $personaNombre = $razonSocial;
                $tipo = 0;
            }
        }

        $datos->personaId = $personaId;
        $datos->personaNombre = $personaNombre;
        $datos->tipo = $tipo;
        return $datos;
    }

    public function actualizarDocumentoValoresTipoCambioMontoNoAfecto($documentoId, $tcLiq, $montoNoAfecto) {
        $resDoc = DocumentoNegocio::create()->actualizarTipoCambioMontoNoAfectoXDocumentoId($documentoId, $tcLiq, $montoNoAfecto);
        return $resDoc;
    }

}
