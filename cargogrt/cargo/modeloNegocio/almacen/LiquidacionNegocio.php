<?php

require_once __DIR__ . '/../../modelo/almacen/Movimiento.php';
require_once __DIR__ . '/../../modelo/almacen/Tarifario.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modelo/almacen/BienUnico.php';
require_once __DIR__ . '/../../modelo/almacen/Zona.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/CentroCostoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContDistribucionContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/DetraccionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DetalleQrNegocio.php';
require_once __DIR__ . '/../commons/ConstantesNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/AgenciaNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/UnidadNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
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
require_once __DIR__ . '/ProgramacionAtencionNegocio.php';
require_once __DIR__ . '/../../util/NumeroALetra/EnLetras.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modelo/almacen/Actividad.php';
require_once __DIR__ . '/../../modelo/almacen/Pago.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

$objPHPExcel = null;
$objWorkSheet = null;
$i = 0;
$j = 0;
$h = null;
$documentoTipoIdAnterior = null;

class LiquidacionNegocio extends ModeloNegocioBase {

    var $dtDuaId = 256;
    var $dtCompraNacional = "406";
    var $dataRetencion = array("id" => 1, "descripcion" => "001 | Retención", "monto_minimo" => 700.00, "porcentaje" => 3.0);
    var $cuentaBancoNacion = "";

    /**
     * 
     * @return LiquidacionNegocio
     */
    static function create() {
        return parent::create();
    }

    private $docInafectas;

    public function obtenerConfiguracionInicial($opcionId, $empresaId, $usuarioId, $documentoId = null, $movimientoTipoId = null) {
        // obtenemos el id del movimiento tipo que utiliza la opcion
        if (!ObjectUtil::isEmpty($movimientoTipoId)) {
            $movimientoTipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        } else {
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $movimientoTipoId = $movimientoTipo[0]["id"];
        }

        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }

        $respuesta = new ObjectUtil();
//        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
        }
        //identificador_negocio	  
        $documentoTipoDefectoId = $respuesta->documento_tipo[0]["id"];
        if (!ObjectUtil::isEmpty($movimientoTipo[0]['documento_tipo_defecto_id'])) {
            $documentoTipoDefectoId = $movimientoTipo[0]['documento_tipo_defecto_id'];
        }

        $respuesta->dataDocumento = null;
        $personaId = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            $respuesta->dataDocumentoPenalidad = Penalidad::create()->obtenerPenalidadDocumentoXDocumentoId($documentoId);
            $respuesta->dataDocumentoRelacion = DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoIdXTipo($documentoId, 9);
            $documentoTipoDefectoId = $respuesta->dataDocumento[0]['documento_tipo_id'];
            $personaId = $respuesta->dataDocumento[0]['persona_id'];
        }

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId);
        if (ObjectUtil::isEmpty($dataAperturaCaja)) {
            throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
        }

        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoDefectoId, $usuarioId, $dataAperturaCaja[0]['agencia_id']);

        if (!ObjectUtil::isEmpty($respuesta->documento_tipo_conf) && !ObjectUtil::isEmpty($personaId)) {
            foreach ($respuesta->documento_tipo_conf as $index => $itemDtd) {
                switch ($itemDtd["tipo"]) {
                    case 5:
                        $respuesta->documento_tipo_conf[$index]['data'] = PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda("", $personaId);
                        break;
                }
            }
        }
        //$respuesta->bien = BienNegocio::create()->obtenerActivos($empresaId);
        $respuesta->bien = BienNegocio::create()->obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId);
//        if (!ObjectUtil::isEmpty($organizador_ids)) {
//            $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo2($movimientoTipoId,$organizador_ids,1);
//        }else{
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);
        $respuesta->penalidad = Penalidad::create()->obtenerActivaXEmpreId($empresaId);
//        }

        $respuesta->movimientoTipo = $movimientoTipo;
//        $respuesta->precioTipo = BienPrecioNegocio::create()->obtenerPrecioTipoActivo();

        $respuesta->precioTipo = BienPrecioNegocio::create()->obtenerPrecioTipoXMovimientoTipo($movimientoTipoId);
        $respuesta->dataAgenciaUsuario = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $respuesta->dataAgencia = AgenciaNegocio::create()->getDataAgencia();
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        $respuesta->accionesEnvio = Movimiento::create()->obtenerMovimientoTipoAcciones($movimientoTipoId, 2);
        $respuesta->accionEnvioPredeterminado = Movimiento::create()->obtenerMovimientoTipoAccionEnvioPredeterminado($movimientoTipoId);

        //obtner datos para las columnas del detalle
        $respuesta->movimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);

        $respuesta->periodo = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        } else {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        }


        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        $respuesta->dataDocumentoPago = PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresaId, 2, 3, $usuarioId);
        $respuesta->actividad = Pago::create()->obtenerActividades($tipoComprobantePago = 1, $empresaId);
        $respuesta->cuenta = CuentaNegocio::create()->obtenerCuentaDefectoXEmpresaId($empresaId);

        return $respuesta;
    }

    public function obtenerBienPrecioXBienId($bienId, $unidadMedidaId, $monedaId, $opcionId) {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];

        $data = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXMovimientoTipoId($bienId, $unidadMedidaId, $monedaId, $movimientoTipoId);
        return $data;
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
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "CÃ³digo";
                        break;
                    case 7 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Serie";
                        break;
                    case 8 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "NÃºmero";
                        break;
                    case 9 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de emisiÃ³n";
                        break;
                    case 10 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de vencimiento";
                        break;
                    case 11 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de tentativa";
                        break;
                    case 12 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "DescripciÃ³n";
                        break;
                    case 13 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Comentario";
                        break;
                    case 14 :
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Importe";
                        break;
                    case 17 :
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

    private function obtenerValorCampoDinamicoPorTipo($camposDinamicos, $tipo) {
        foreach ($camposDinamicos as $campo) {
            if ($campo["tipo"] == $tipo) {
                return $campo["valor"];
            }
        }
    }

    public function generarDocumentoElectronico($documentoId, $identificadorNegocio, $soloPDF = 0, $tipoUso = 1) {
        //soloPDF = 1 -> solo generar PDF
        //tipoUso = 1 -> por sistema
        //tipoUso = 2 -> por script

        if (ObjectUtil::isEmpty($soloPDF)) {
            $soloPDF = 0;
        }

        if (ObjectUtil::isEmpty($tipoUso)) {
            $tipoUso = 1;
        }

        $esDocElectronico = 0;
        $respDocElectronico = null;

        switch ($identificadorNegocio * 1) {
            case DocumentoTipoNegocio::IN_FACTURA_VENTA:
                $respDocElectronico = $this->generarFacturaElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_BOLETA_VENTA:
                $respDocElectronico = $this->generarBoletaElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA:
                $respDocElectronico = $this->generarNotaCreditoElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA:
                $respDocElectronico = $this->generarNotaDebitoElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
        }

        $respuesta->esDocElectronico = $esDocElectronico;
        $respuesta->respDocElectronico = $respDocElectronico;

        return $respuesta;
    }

    public function validarResultadoEfactura($resultado) {
        $mensaje = "Resultado EFACT: " . $resultado;

        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("Se generó un error al registrar el documento electrónico.");
        } else if (strpos($mensaje, '[Cod: IMA01]') === false) {
            throw new WarningException($mensaje);
        }

        $this->setMensajeEmergente($mensaje);
    }

    public function validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso) {
        $mensaje = $resultado;
        $urlPDF = '';
        $nombrePDF = '';

        switch (true) {
            //EXCEPCIONES DE LA WS - EFAC
            case strpos($mensaje, '[Cod: IMAEX') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO;
                $mensaje = "Resultado EFACT: " . $resultado;
                //throw new WarningException("Resultado EFACT: " . $resultado);
                break;

            //REGISTRO EN LA WS - PENDIENTE DE ENVIO A SUNAT U OSE
            case strpos($mensaje, '[Cod: IMA00]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_PENDIENTE_ENVIO;
                $mensaje = "Resultado EFACT: " . $resultado;
                break;

            //REGISTRO CORRECTO
            case strpos($mensaje, '[Cod: IMA01]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_CORRECTO;
                $mensaje = "Resultado EFACT: " . $resultado;
                break;

            //ERROR CONTROLADO QUE GENERA EXCEPCION
            case strpos($mensaje, '[Cod: IMA02]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO;
                $mensaje = "Resultado EFACT (ERROR): " . $resultado;
                break;

            //ERROR CONTROLADO QUE GENERA RECHAZO EN SUNAT
            case strpos($mensaje, '[Cod: IMA03]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_RECHAZADO;
                $mensaje = "Resultado EFACT (ERROR): " . $resultado;
                //CAMBIAR ESTADO ANULADO : 
                $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($documentoId, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS);
                $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, 2, 1);
                break;

            //ERROR DESCONOCIDO
            case strpos($mensaje, '[Cod: IMA04]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO;
                $mensaje = "Se generó un error al registrar el documento electrónico. Resultado EFACT: " . $resultado;
                break;

            default:
                throw new WarningException("Resultado EFACT (ERROR): " . $resultado);
        }

//        if ($tipoMensaje == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO && $tipoUso == 1) {
//            throw new WarningException($mensaje);
//        }

        $resEstadoRegistro = DocumentoNegocio::create()->actualizarEfactEstadoRegistro($documentoId, $tipoMensaje, $resultado); // guardar $resultado 
        if (strpos($resultado, '[FN:') > -1) {
            $indInicial = strpos($resultado, '[FN:');
            $indFinal = strpos($resultado, '.pdf]');

            $nombrePDF = substr($resultado, ($indInicial + 5), ($indFinal - $indInicial - 1));

            if (!ObjectUtil::isEmpty($nombrePDF)) {
                $urlPDF = Configuraciones::EFACT_CONTENEDOR_PDF . $nombrePDF;

                $resActNombrePDF = DocumentoNegocio::create()->actualizarEfactPdfNombre($documentoId, $nombrePDF);
            } else {
                $urlPDF = '';
            }
        }

        if ($tipoMensaje != DocumentoTipoNegocio::EFACT_CORRECTO) {
            $mensaje = "Se registró correctamente en el SGI, pero se ha presentado un problema en el envió a OSE<br>Detalle: " . $mensaje;
            $titulo = ", pendiente de emisión a OSE";
        }

        $respEfact->tipoMensaje = $tipoMensaje; //[Cod: IMAEX05] |  Error la generar el documento : Comprobante: F001-000349 presenta el error: Se ha especificado un tipo de proveedor no válido.
        $respEfact->mensaje = $mensaje;
        $respEfact->urlPDF = $urlPDF;
        $respEfact->nombrePDF = $nombrePDF;
        $respEfact->titulo = $titulo; //titulo que en caso de reenvio de comprobante  no será nulo
        return $respEfact;
    }

    public function generarFacturaElectronica($documentoId, $soloPDF, $tipoUso) {
        $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        $tipoPago = ($documento[0]["tipo_pago"] * 1);
        // receptor
        $codigoIdentificacion = $persona[0]["codigo_identificacion"];
        $sunatTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $totalIgv = $documento[0]["igv"] * 1.0;
        $totalGravadas = $documento[0]["subtotal"] * 1.0;
        $totalGratuitas = 0;
        $totalDocumento = $documento[0]["total"] * 1.0;
        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $codigoIdentificacion = '0';
            $sunatTipoDocumento = 0;
            $totalIgv = 0;
            $totalGravadas = 0;
//            $totalGratuitas = $documento[0]["total"] * 1.0;
        }

        //Ventas gratuitas
        if ($documento[0]["movimiento_tipo_codigo"] == "18") {
            $totalGratuitas = $documento[0]["total"] * 1.0;
            //Venta gratuita obsequio 
            if (($this->docInafectas * 1) > 0) {
                $totalGratuitas = $this->docInafectas;
            }
            $totalGravadas = 0;
            $totalIgv = 0;
            $totalDocumento = 0;
        }

        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($totalDocumento, $documento[0]['moneda_id']);

        $comprobanteElectronico->receptorNroDocumento = $codigoIdentificacion;
        $comprobanteElectronico->receptorTipoDocumento = $sunatTipoDocumento;
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
//        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        // factura
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F') {
            throw new WarningException("La serie del documento debe empezar con F");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $totalIgv;
        $comprobanteElectronico->docTotalVenta = $totalDocumento;
        $comprobanteElectronico->docGravadas = $totalGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
//        $comprobanteElectronico->docInafectas = (!ObjectUtil::isEmpty($this->docInafectas)) ? $this->docInafectas : 0.0;

        $comprobanteElectronico->docGratuitas = $totalGratuitas;
        $comprobanteElectronico->docDescuentoGlobal = 0.00;
        $comprobanteElectronico->icbper = 0.0;

        if ((ObjectUtil::isEmpty($totalGratuitas) || $totalGratuitas == 0) && ($documento[0]["igv"] * 1) == 0) {
            $comprobanteElectronico->docGravadas = 0;
            $comprobanteElectronico->docExoneradas = $totalGravadas;
        }

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $porcentajeIgv = Configuraciones::IGV_PORCENTAJE;
            $valorMonetario = $fila['valor_monetario'] * 1;
            $valorMonetarioRef = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
            } else {
                $valorMonetarioRef = $fila['valor_monetario'] * (1 + ($porcentajeIgv / 100));
            }
            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $precio = $valorMonetario;
            $precioReferencial = $valorMonetarioRef;
            $tipoPrecio = "01";
            $impuesto = 0; //Impuesto
            $tipoImpuesto = '10';

            $porcentajeImpuesto = Configuraciones::IGV_PORCENTAJE;
            if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
                $tipoImpuesto = '40';
            } elseif ($totalGratuitas > 0) {
                if ($fila['incluye_igv'] * 1 == 1 && ($documento[0]["igv"] * 1) > 0) {
                    $valorMonetarioRef = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
                } else {
                    $valorMonetarioRef = $fila['valor_monetario'] * 1;
                }
                $precioReferencial = $valorMonetarioRef;
                $precio = 0;
                $tipoImpuesto = '21';
                $tipoPrecio = '02'; //Tipos de precio
            } elseif (($documento[0]["igv"] * 1) == 0) {
                $precioReferencial = $precio;
                $tipoImpuesto = '20';
                $porcentajeImpuesto = 0;
            } else {
                $impuesto = $valorMonetario * $fila['cantidad'] * ($porcentajeIgv / 100); //Impuesto 
            }

            $items[$index][2] = $precio;
            $items[$index][3] = $precioReferencial; //Precio refencial
            $items[$index][4] = $tipoPrecio; //Tipos de precio 
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuesto;
            $items[$index][9] = $tipoImpuesto; //Tipo de impuesto
            $items[$index][10] = ($valorMonetario * $fila['cantidad']);
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
            $items[$index][13] = $fila['bien_codigo_internacional']; //codigo internacional
            $items[$index][14] = 0.0; //ICBPER
        }

        $comprobanteElectronico->items = $items;

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        //guias de remision
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);
        $i = 0;
        foreach ($docRelacion as $index => $guias) {
            switch ($guias['identificador_negocio_relacion'] * 1) {
                case 6: {
                        $guiasRemision[$i][0] = $guias["serie_relacion"] . '-' . $guias["numero_relacion"];
                        $guiasRemision[$i][1] = $guias["fecha_emision_relacion"];
                        $guiasRemision[$i][2] = $guias["sunat_tipo_doc_rel"];
                        $i++;
                    }
            }
        }
        //DATOS ADICIONALES                
        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $incoterm = '';

            foreach ($dataDocumento as $index => $item) {
                switch ($item['documento_tipo_id'] * 1) {
                    case 2925:
                        $incoterm = $item['valor'];
                        break;
                }
            }

            $datoAdicional[0][0] = 'Incoterm';
            $datoAdicional[0][1] = $incoterm;

            $comprobanteElectronico->extras = $datoAdicional;
        }
        //FIN DATOS ADICIONALES

        $comprobanteElectronico->guiasRemision = $guiasRemision;
        // orden de compra
        $docOrdenCompra = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
        foreach ($docOrdenCompra as $index => $ordenCompra) {
            switch ($ordenCompra['identificador_negocio'] * 1) {
                case 2: {
                        $orden = $ordenCompra['serie_numero_original'];
                    }
            }
        }

        $afectoDetraccionRetencion = ($documento[0]["afecto_detraccion_retencion"] * 1);
        $porcentajeDetraccionRetencion = ($documento[0]["porcentaje_afecto"] * 1);
        $montoRetencionDetraccion = ($documento[0]["monto_detraccion_retencion"] * 1);
        $codigoDetraccion = $documento[0]["detraccion_codigo"];
        $tipoCambio = $documento[0]["tipo_cambio"];

        $datoAdicional = array();

        //OBTENER DATOS ADICIONALES        
        $datoAdicional[] = array('Fecha vencimiento', $documento[0]['fecha_vencimiento']);
        $datoAdicional[] = array('Forma pago', $tipoPago == 1 ? "Contado" : "Crédito");

        $formaPago = array();
        if ($tipoPago == 2) {
            if ($afectoDetraccionRetencion == 1) {
                $montoRetencionDetraccionSoles = $montoRetencionDetraccion * 1.0;
                if (ObjectUtil::isEmpty($codigoDetraccion)) {
                    throw new WarningException("Este documento requiere del código detracción aplicada.");
                }
                if (ObjectUtil::isEmpty($montoRetencionDetraccion) || $montoRetencionDetraccion <= 0) {
                    throw new WarningException("Este documento requiere del monto detracción aplicada aplicada.");
                }
                if (ObjectUtil::isEmpty($porcentajeDetraccionRetencion) || $porcentajeDetraccionRetencion <= 0) {
                    throw new WarningException("Este documento requiere del porcentaje detracción aplicada aplicada.");
                }
                if ($comprobanteElectronico->docMoneda != "PEN") {
                    if (ObjectUtil::isEmpty($tipoCambio)) {
                        throw new WarningException("Este documento requiere del tipo de campo para calcular la detracción en SOLES.");
                    }
                    $montoRetencionDetraccionSoles = round($montoRetencionDetraccionSoles * $tipoCambio, 0);
                }
                $formaPago[] = array("Detraccion" . $codigoDetraccion, $montoRetencionDetraccionSoles, "", $porcentajeDetraccionRetencion * 0.01, $this->cuentaBancoNacion);
                $afectoDescripcion = "DETRACCIÓN / OPERACION SUJETA AL SPOT D.L 940 - " . number_format($porcentajeDetraccionRetencion, 2) . "% (" . $comprobanteElectronico->docMoneda . " " . number_format($montoRetencionDetraccion, 2) . ") CTA BN: " . $this->cuentaBancoNacion;
                $datoAdicional[] = array('Afecto a', $afectoDescripcion);

                $comprobanteElectronico->tipoOperacion = "1001"; // Tipo de operación afecto a detracción.
            } elseif ($afectoDetraccionRetencion == 2) {
                if (ObjectUtil::isEmpty($montoRetencionDetraccion) || $montoRetencionDetraccion <= 0) {
                    throw new WarningException("Este documento requiere del monto retención aplicada aplicada.");
                }
                if (ObjectUtil::isEmpty($porcentajeDetraccionRetencion) || $porcentajeDetraccionRetencion <= 0) {
                    throw new WarningException("Este documento requiere del porcentaje retención aplicada aplicada.");
                }

                $formaPago[] = array("Retencion", $montoRetencionDetraccion * 1.0, "", $porcentajeDetraccionRetencion * 0.01);
                $afectoDescripcion = "RETENCIÓN - " . number_format($porcentajeDetraccionRetencion, 2) . "% (" . $comprobanteElectronico->docMoneda . " " . number_format($montoRetencionDetraccion, 2) . ")";
                $datoAdicional[] = array('Afecto a', $afectoDescripcion);
            }

            $montoNetoPago = round($comprobanteElectronico->docTotalVenta - ($montoRetencionDetraccion * 1), 2);
            $formaPago[] = array("Credito", $montoNetoPago);
            $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
            if (ObjectUtil::isEmpty($formaPagoDetalle)) {
                throw new WarningException("Se requiere de la programación de pago de la factura.");
            }

            $arrayFechaVencimiento = array();
            foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                $arrayFechaVencimiento[] = substr($itemFormaPago['fecha_pago'], 0, 10);
                $formaPago[] = array("Cuota" . str_pad(($indexFormaPago + 1), 3, "0", STR_PAD_LEFT), $itemFormaPago['importe'] * 1.0, substr($itemFormaPago['fecha_pago'], 0, 10));
            }
            $fechaMaximaCuota = date("Y-m-d", max(array_map('strtotime', $arrayFechaVencimiento)));
            if (substr($documento[0]['fecha_vencimiento'], 0, 10) != $fechaMaximaCuota) {
                throw new WarningException("La fecha de vencimiento de la factura (" . substr($documento[0]['fecha_vencimiento'], 0, 10) . ") debe ser igual a la última cuota de la programación de pagos ($fechaMaximaCuota)");
            }
        } elseif ($tipoPago == 1) {
            $formaPago[] = array("Contado", 0.0);
        } else {
            throw new WarningException("No se identifica la forma de pago para esta factura.");
        }

        $comprobanteElectronico->ordenCompra = $orden;
        $comprobanteElectronico->formaPago = $formaPago;
        $comprobanteElectronico->extras = $datoAdicional;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];

        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $comprobanteElectronico->tipoOperacion = '0200';
        }


        $client = self::conexionEFAC();
        try {

            if ($soloPDF == 1) {
                $resultado = $client->procesarFacturaPDF((array) $comprobanteElectronico)->procesarFacturaPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarFactura((array) $comprobanteElectronico)->procesarFacturaResult;
            }
//            throw new WarningException($resultado);
            //DESCOMENTAR PARA PROBAR RESPUESTAS
//            $resultado = "Resultado EFACT: [Cod: IMA01] | La Factura numero F001-000052, ha sido aceptada | [FN: 2018101517134720600143361-01-F001-000049.pdf]";
//            $resultado = "Resultado EFACT: La Factura numero F001-000052, hay error desconocido | [FN: 2018111613502920600143361-01-F001-000058.pdf]";
//            $resultado = "Resultado EFACT: [Cod: IMA02] | ERROR SUNAT .. ... -_-";
        } catch (Exception $e) {
            $resultado = $e->getMessage();
//            throw new WarningException($resultado);
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

//        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarBoletaElectronica($documentoId, $soloPDF, $tipoUso) {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }


        $totalIgv = $documento[0]["igv"] * 1.0;
        $totalGravadas = $documento[0]["subtotal"] * 1.0;
        $totalGratuitas = 0;
        $totalDocumento = $documento[0]["total"] * 1.0;
        //Ventas gratuitas
        if ($documento[0]["movimiento_tipo_codigo"] == "18") {
            $totalGratuitas = $documento[0]["total"] * 1.0;
            //Venta gratuita obsequio 
            if (($this->docInafectas * 1) > 0) {
                $totalGratuitas = $this->docInafectas;
            }
            $totalGravadas = 0;
            $totalIgv = 0;
            $totalDocumento = 0;
        }

        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($totalDocumento, $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
//        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        // factura        
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con B");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $totalIgv;
        $comprobanteElectronico->docTotalVenta = $totalDocumento;
        $comprobanteElectronico->docGravadas = $totalGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = $totalGratuitas;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;
        $comprobanteElectronico->icbper = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $porcentajeIgv = Configuraciones::IGV_PORCENTAJE;
            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / 1.18;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $precio = $valorMonetario;

            $precioReferencial = 0;
            $tipoPrecio = "01";
            $impuesto = 0; //Impuesto
            $tipoImpuesto = '10';

            if ($totalGratuitas > 0) {
                if ($fila['incluye_igv'] * 1 == 1 && ($documento[0]["igv"] * 1) > 0) {
                    $valorMonetario = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
                } else {
                    $valorMonetario = $fila['valor_monetario'] * 1;
                }
                $precio = 0;
                $precioReferencial = $valorMonetario;
                $tipoImpuesto = '21';
                $tipoPrecio = '02'; //Tipos de precio
            } else {
                $impuesto = $valorMonetario * $fila['cantidad'] * ($porcentajeIgv / 100); //Impuesto 
            }

            $items[$index][2] = $precio;
            $items[$index][3] = $precioReferencial; //Precio refencial
            $items[$index][4] = $tipoPrecio; //Tipos de precio 
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuesto;
            $items[$index][9] = $tipoImpuesto; //Tipo de impuesto
            $items[$index][10] = $valorMonetario * $fila['cantidad'];
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
            $items[$index][13] = $fila['bien_codigo_internacional']; //codigo internacional
            $items[$index][14] = 0.0; //ICBPER
        }

        $comprobanteElectronico->items = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {
            if ($soloPDF == 1) {
                $resultado = $client->procesarBoletaPDF((array) $comprobanteElectronico)->procesarBoletaPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarBoleta((array) $comprobanteElectronico)->procesarBoletaResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

//        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarNotaCreditoElectronica($documentoId, $soloPDF, $tipoUso) {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }
        $enLetras = new EnLetras();
        $importeTotalLetras = $documento[0]["total"];
        if ($documento[0]["motivo_codigo"] == 13) {
            $importeTotalLetras = 0.0;
        }
        $importeLetras = $enLetras->ValorEnLetras($importeTotalLetras, $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);

//        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
//        // factura
//        $serieNum=  DocumentoNegocio::create()->obtenerSerieNumeroXDocumentoId($documentoId);
//                
//        //VALIDA SERIE
//        if ($serieNum[0]["serie"][0] != 'F' && $serieNum[0]["serie"][0] != 'B') {
//            throw new WarningException("La serie del documento debe empezar con F o B");
//        }
        // factura
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F' && $documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con F o B");
        }

//        $comprobanteElectronico->docSerie = $serieNum[0]["serie"];
//        $comprobanteElectronico->docNumero = $serieNum[0]["numero"];
        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $docTotalIgv = $documento[0]["total"] * 1 - $documento[0]["total"] / 1.18;
        $docTotalVenta = $documento[0]["total"] * 1;
        $docGravadas = $documento[0]["total"] / 1.18;
        if ($documento[0]["motivo_codigo"] == 13) { // Cambio de importes para la NC tipo 13
            $docTotalIgv = 0;
            $docTotalVenta = 0;
            $docGravadas = 0;
        }

        $comprobanteElectronico->docTotalIgv = $docTotalIgv;
        $comprobanteElectronico->docTotalVenta = $docTotalVenta;
        $comprobanteElectronico->docGravadas = $docGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = 0.0;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $impuestoIgv = Configuraciones::IGV_PORCENTAJE / 100;
            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / (1 + $impuestoIgv);
            }

            $totalVentaItem = $valorMonetario * $fila['cantidad'];
            $impuestoItem = $valorMonetario * $fila['cantidad'] * $impuestoIgv;
            if ($documento[0]["motivo_codigo"] == 13) { // Cambio de importes para la NC tipo 13
                $valorMonetario = 0.0;
                $totalVentaItem = 0.0;
                $impuestoItem = 0.0;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $items[$index][2] = $valorMonetario;
            $items[$index][3] = 0; //Precio refencial
            $items[$index][4] = '01'; //Tipos de precio
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuestoItem; //Impuesto
            $items[$index][9] = '10'; //Tipo de imp$impuestoItem
            $items[$index][10] = $totalVentaItem;
            $items[$index][11] = 0.0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
        }

        $comprobanteElectronico->items = $items;

        //VALIDO EL COMENTARIO
        if (ObjectUtil::isEmpty($documento[0]["comentario"])) {
            throw new WarningException("Ingrese comentario (Sustento por el que se emite la NC)");
        }

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);

        $discrepancias = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA) {

                //VALIDA SERIE
                /* if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA && $itemRel["serie_relacion"][0] != 'B') {
                  throw new WarningException("La serie de la boleta relacionada debe empezar con B");
                  } else if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA && $itemRel["serie_relacion"][0] != 'F') {
                  throw new WarningException("La serie de la factura relacionada debe empezar con F");
                  } */

                $itemDiscrepancia[0] = $documento[0]["comentario"];
                $itemDiscrepancia[1] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemDiscrepancia[2] = $documento[0]["motivo_codigo"];

                array_push($discrepancias, $itemDiscrepancia);
            }
        }

        //VALIDO QUE HAYA DISCREPANCIAS
        if (ObjectUtil::isEmpty($discrepancias)) {
            throw new WarningException("Relacione un documento de venta (factura o boleta)");
        }

        $comprobanteElectronico->discrepancias = array($discrepancias[0]);

        $docRelacionados = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA) {
                $itemRelacion[0] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemRelacion[1] = $itemRel['sunat_tipo_doc_rel'];

                array_push($docRelacionados, $itemRelacion);
            }
        }
        $tipoPago = ($documento[0]["tipo_pago"] * 1);
        if ($documento[0]["motivo_codigo"] == 13) {


            $datoAdicional = array();
            //OBTENER DATOS ADICIONALES        
            $datoAdicional[] = array('Fecha vencimiento', $documento[0]['fecha_vencimiento']);
            $datoAdicional[] = array('Forma pago', $tipoPago == 1 ? "Contado" : "Crédito");
            $comprobanteElectronico->extras = $datoAdicional;

            $formaPago = array();

            if ($tipoPago == 2) {
                $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
                if (ObjectUtil::isEmpty($formaPagoDetalle)) {
                    throw new WarningException("Se requiere de la programación de pago de la factura.");
                }
                $montoNetoPago = 0.0;
                foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                    $montoNetoPago += $itemFormaPago['importe'] * 1.0;
                }
                $formaPago[] = array("Credito", $montoNetoPago);

                $arrayFechaVencimiento = array();
                foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                    $arrayFechaVencimiento[] = substr($itemFormaPago['fecha_pago'], 0, 10);
                    $formaPago[] = array("Cuota" . str_pad(($indexFormaPago + 1), 3, "0", STR_PAD_LEFT), $itemFormaPago['importe'] * 1.0, substr($itemFormaPago['fecha_pago'], 0, 10));
                }
            } elseif ($tipoPago == 1) {
                throw new WarningException("Nota de credito tipo 13 debe ser con forma de pago CREDITO.Se requiere de la programación de pago de la factura.");

                $formaPago[] = array("Contado", 0.0, "");
            } else {
                throw new WarningException("No se identifica la forma de pago para este comprobante.");
            }
            $comprobanteElectronico->formaPago = $formaPago;
        }

        $comprobanteElectronico->docRelacionados = array($docRelacionados[0]);

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {

            if ($soloPDF == 1) {
                $resultado = $client->procesarNotaCreditoPDF((array) $comprobanteElectronico)->procesarNotaCreditoPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->icbper = 0.0;
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarNotaCredito((array) $comprobanteElectronico)->procesarNotaCreditoResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

//        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarNotaDebitoElectronica($documentoId, $soloPDF, $tipoUso) {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }
        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($documento[0]["total"], $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
//        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F' && $documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con F o B");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $documento[0]["total"] * 1 - $documento[0]["total"] / 1.18;
        $comprobanteElectronico->docTotalVenta = $documento[0]["total"] * 1;
        $comprobanteElectronico->docGravadas = $documento[0]["total"] / 1.18;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = 0.0;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {

            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / 1.18;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $items[$index][2] = $valorMonetario;
            $items[$index][3] = 0; //Precio refencial
            $items[$index][4] = '01'; //Tipos de precio
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $valorMonetario * $fila['cantidad'] * 0.18; //Impuesto
            $items[$index][9] = '10'; //Tipo de impuesto
            $items[$index][10] = $valorMonetario * $fila['cantidad'];
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
        }

        $comprobanteElectronico->items = $items;

        //VALIDO EL COMENTARIO
        if (ObjectUtil::isEmpty($documento[0]["comentario"])) {
            throw new WarningException("Ingrese comentario (Sustento por el que se emite la ND)");
        }

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);

        $discrepancias = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA) {

                //VALIDA SERIE
                /*
                  if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA && $serieNumRel[0]["serie"][0] != 'B') {
                  throw new WarningException("La serie de la boleta relacionada debe empezar con B");
                  } else if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA && $serieNumRel[0]["serie"][0] != 'F') {
                  throw new WarningException("La serie de la factura relacionada debe empezar con F");
                  } */

                $itemDiscrepancia[0] = $documento[0]["comentario"];
                $itemDiscrepancia[1] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemDiscrepancia[2] = $documento[0]["motivo_codigo"];

                array_push($discrepancias, $itemDiscrepancia);
            }
        }

        //VALIDO QUE HAYA DISCREPANCIAS
        if (ObjectUtil::isEmpty($discrepancias)) {
            throw new WarningException("Relacione un documento de venta (factura o boleta)");
        }

        $comprobanteElectronico->discrepancias = $discrepancias;

        $docRelacionados = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA) {

                $itemRelacion[0] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemRelacion[1] = $itemRel['sunat_tipo_doc_rel'];

                array_push($docRelacionados, $itemRelacion);
            }
        }

        $comprobanteElectronico->docRelacionados = $docRelacionados;

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {
            if ($soloPDF == 1) {
                $resultado = $client->procesarNotaDebitoPDF((array) $comprobanteElectronico)->procesarNotaDebitoPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->icbper = 0.0;
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarNotaDebito((array) $comprobanteElectronico)->procesarNotaDebitoResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

//        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function validarBienesFaltantes($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $accionEnvio) {
        //validacion en caso de bienes faltantes
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $bandera = false;
        $dataOrganizador = array();
        $dataProveedor = array();

        if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
            $j = 0;
            foreach ($detalle as $indice => $item) {
                $stockData = BienNegocio::create()->obtenerStockActual($item["bienId"], $item["organizadorId"], $item["unidadMedidaId"]);
                $stock = $stockData[0]["stock"];
                if ($stock < 0) {
                    $stock = 0;
                }

                $cantidadFaltante = $stock - $item["cantidad"];
                $cantidadFaltante = $cantidadFaltante * -1;
                //validar que no sea servicio
                $bien = BienNegocio::create()->getBien($item["bienId"]);

                if ($cantidadFaltante > 0 && $bien[0]['bien_tipo_id'] != -1) {

                    $dataOrganizador[$j] = array();
                    //$dataProveedor[$j]=array();

                    $dataP = BienNegocio::create()->obtenerBienPersonaXBienId($item["bienId"]);
                    array_push($dataProveedor, $dataP);

                    $bandera = true;
                    $detalleFaltantes[$j] = $item;

                    $detalle[$indice]["cantidad"] = $stock;
                    $detalleFaltantes[$j]["cantidad"] = $cantidadFaltante;
                    $detalleFaltantes[$j]["organizadorId"] = '';

                    //$dataStockBien=BienNegocio::create()->obtenerStockPorBien($item["bienId"], $movimientoTipo[0]["empresa_id"]);
                    $dataStockBien = BienNegocio::create()->obtenerStockPorBien($item["bienId"], null);

                    foreach ($dataStockBien->stockBien as $ind => $itemDataStock) {
                        if ($cantidadFaltante <= $itemDataStock["stock"] && $item["unidadMedidaId"] == $itemDataStock["unidad_medida_id"]) {

                            array_push($dataOrganizador[$j], array('organizadorId' => $itemDataStock["organizador_id"], 'descripcion' => $itemDataStock["organizador_descripcion"]));
                        }
                    }

                    $j++;
                }
            }

            $respuesta->detalleFaltantes = $detalleFaltantes;
            $respuesta->dataOrganizador = $dataOrganizador;
            $respuesta->dataProveedor = $dataProveedor;
            $respuesta->dataDetalle = $detalle;
        }

        if ($bandera) {
            return $respuesta;
        }


        //fin validaacion       

        return $this->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio);
    }

    public function guardarNormalmente($documentoId, $dataMovBienPRM, $usuarioIdPRM) {
        $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

        foreach ($relacionadosDocumentoActual as $item) {

            $docRelacionadoId = $item['documento_relacionado_id'];

            $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
            $dataMovBien = $dataMovBienPRM;
            $bienesIdRelacionados = MovimientoBien::create()->obtenerBienesIdRelacionadosXDocumentoId($docRelacionadoId);
            foreach ($dataMovBien as $dataMovBienActual) {
                foreach ($bienesIdRelacionados as $bienesIdRelacionado) {

                    if ($dataMovBienActual['bien_id'] == $bienesIdRelacionado['bien_id']) {

                        $movimiento_bien_anterior = $bienesIdRelacionado['movimiento_bien_anterior'];
                        $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                        $cantidad = $bienesIdRelacionado['cantidad_solicitada'];
                        MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioIdPRM);
                    }
                }
            }
        }
    }

    public function guardarDocumentoPercepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $percepcion, $periodoId) {
        //OBTENIENDO PERCEPCION
        $percepcionMonto = $percepcion["importeSoles"];
        foreach ($camposDinamicos as $indexCampos => $valorDtd) {
//            if ($valorDtd["tipo"] == 19) {
//                $percepcionMonto = $valorDtd["valor"];
//            }
            if ($valorDtd["tipo"] == 9) {
                $fechaEmisionDtd = $valorDtd["valor"];
            }
        }

        if ($opcionId == Configuraciones::OPCION_ID_DUA && $percepcionMonto * 1 != 0 && !ObjectUtil::isEmpty($percepcionMonto)) {
            $opcionIdPer = null;
            $documentoTipoIdPer = Configuraciones::DOCUMENTO_TIPO_ID_PERCEPCION;

            //cabecera del documento            
            $configuracionesDtd = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoIdPer);

            foreach ($configuracionesDtd as $indexConfig => $itemDtd) {
                if ($itemDtd["tipo"] == 8) {
                    $configuracionesDtd[$indexConfig]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoIdPer);
                }
                if ($itemDtd["tipo"] == 9) {
                    $configuracionesDtd[$indexConfig]["valor"] = $fechaEmisionDtd;
                }
                if ($itemDtd["tipo"] == 7) {
                    $configuracionesDtd[$indexConfig]["valor"] = $itemDtd['cadena_defecto'];
                }
                if ($itemDtd["tipo"] == 14) {
                    $configuracionesDtd[$indexConfig]["valor"] = $percepcionMonto;
                }
            }
            $monedaId = 2;
            $documentoId = PagoNegocio::create()->guardar($opcionIdPer, $usuarioId, $documentoTipoIdPer, $configuracionesDtd, $monedaId, $periodoId);

            $documentoARelacionarRecep = array('documentoId' => $documentoId, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => '');

            $respuesta->documentoIdRecep = $documentoId;
            $respuesta->documentoARelacionarPercepcion = $documentoARelacionarRecep;
        } else {
            $respuesta = null;
        }
        return $respuesta;
    }

    public function validarDocumentoARelacionar($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck) {

        //Validación de relaciones de DUA con GR y con OC   - GR: GUIA DE REMISION BH     
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 21) {
            //ES DUA
            if (ObjectUtil::isEmpty($documentoARelacionar)) {
                throw new WarningException("Debe relacionar una orden de compra para poder guardar");
            } else {
                $copiaGuiaRec = false;
                foreach ($documentoARelacionar as $item) {
                    IF (!ObjectUtil::isEmpty($item['documentoId'])) {
                        //buscando orden de compra para ver sus relaciones.
                        $dataDocCopia = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);
                        $dataDocTipoCopia = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($dataDocCopia[0]['documento_tipo_id']);

                        if ($dataDocTipoCopia[0]['identificador_negocio'] == 10) {//ES ORDEN DE COMPRA
                            //OBTENEMOS LAS RELACIONES DE O.C.
//                            $dataRelaciones=  DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($item['documentoId']);
                            $dataRelaciones = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($item['documentoId']);
                            foreach ($dataRelaciones as $itemRel) {
                                //VALIDAMOS SI ES UNA GUIA DE REMISION  o GUIA DE RECEPCION (SE QUITO LA GR DE ENTRADA)
                                if ($itemRel['identificador_negocio'] == 6 || $itemRel['identificador_negocio'] == 22) {
                                    $copiaGuiaRec = true;
                                }
                            }
                        }
                    }
                }

                if (!$copiaGuiaRec) {
                    throw new WarningException("Debe relacionar una guia de recepción con la orden de compra para poder guardar");
                }
            }
        }

        //SI ES RECEPCION DE TRANSFERENCIA - DT: RECEPCION
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 9) {
            if (ObjectUtil::isEmpty($documentoARelacionar)) {
                throw new WarningException("Debe relacionar la Guía de remisión para poder guardar");
            } else {
                $copiaGuiaRem = false;
                foreach ($documentoARelacionar as $item) {
                    IF (!ObjectUtil::isEmpty($item['documentoId'])) {
                        //buscando guia de remision en las copias.
                        $dataDocCopia = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);

                        if ($dataDocCopia[0]['identificador_negocio'] == 6) {//ES GUIA DE REMISION
                            $copiaGuiaRem = true;
                        }
                    }
                }

                if (!$copiaGuiaRem) {
                    throw new WarningException("Debe relacionar la Guía de remisión para poder guardar");
                }
            }
        }

//        throw new WarningException("ERROR...PASO TODO BIEN");
    }

    public function validarDetalleContenidoEnDocumentoRelacion($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck, $detalle) {
        $bandera = false; //NO HAY ERRORES

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) {
            //ES GUIA INTERNA DE TRASLADO EN UN SOLO PASO
            if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                $movimientoIds = '0';
                $contador = 0;
                foreach ($documentoARelacionar as $item) {
//                    $item['tipo']==1; ES EL PADRE DE LOS DOCUMENTOS RELACIONADO
                    IF (!ObjectUtil::isEmpty($item['documentoId']) && $item['tipo'] == 1) {
                        $movimientoIds = $movimientoIds . ',' . $item['movimientoId'];
                        $contador++;
                    }
                }
                //obtenemos la data del detalle de la copia
                $bandera = false; //NO HAY ERRORES
                $df = $detalle;
//                $dr = MovimientoBien::create()->obtenerXIdMovimiento($item['movimientoId']);
                $dr = MovimientoBien::create()->obtenerXMovimientoIds($movimientoIds);

                foreach ($df as $i => $itemDoc) {
                    $bandera2 = false; //HAY ERROR
                    foreach ($dr as $j => $itemRel) {
                        if ($itemDoc['bienId'] == $itemRel['bien_id'] && $itemDoc['cantidad'] * 1 <= $itemRel['cantidad'] * 1 && $itemDoc['unidadMedidaId'] == $itemRel['unidad_medida_id']) {
                            $bandera2 = true; //CORRECTO
                            break;
                        }
                    }
                    if (!$bandera2) {//SI HAY ERROR
                        $bandera = true; //ERROR
                        break;
                    }
                }
            }
        }

        if ($bandera) {
            $mensaje = "El detalle del formulario debe estar contenido en el detalle del documento relacionado";
            if ($contador > 1) {
                $mensaje = "El detalle del formulario debe estar contenido en el detalle de los documentos relacionados";
            }
            throw new WarningException($mensaje);
        }

//        throw new WarningException("TODO BIEN");
    }

    public function guardarXAccionEnvio($opcionId, $usuarioId, $documento, $detalle) {

        if ($documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_FACTURA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_BOLETA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_CREDITO_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_DEBITO_VENTA) {

            $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_VENTAS;
        }

        $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $contOperacionTipoId, $afectoAImpuesto, $datosExtras);

        if ($documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_FACTURA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_BOLETA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_CREDITO_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_DEBITO_VENTA) {

            $respuestaContVoucher = ContVoucherNegocio::create()->registrarContVoucherRegistroVentas($documentoId, $usuarioId);
            $respuestaActualizarEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, NULL, $usuarioId, 'AP', NULL);
            if ($respuestaActualizarEstado[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaActualizarEstado[0]['vout_mensaje']);
            }
        }

        if (!ObjectUtil::isEmpty($atiende)) {
            if ($atiende == false) {
                //---GUARDAR VALORES AUTOMATICOS
                $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

                foreach ($relacionadosDocumentoActual as $item) {

                    $docRelacionadoId = $item['documento_relacionado_id'];

                    $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
                    $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoIdActual[0]['movimiento_id']);
                    $bienesIdRelacionados = MovimientoBien::create()->obtenerBienesIdRelacionadosXDocumentoId($docRelacionadoId);
                    foreach ($dataMovBien as $dataMovBienActual) {
                        foreach ($bienesIdRelacionados as $bienesIdRelacionado) {

                            if ($dataMovBienActual['bien_id'] == $bienesIdRelacionado['bien_id']) {

                                $movimiento_bien_anterior = $bienesIdRelacionado['movimiento_bien_anterior'];
                                $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                                $cantidad = $bienesIdRelacionado['cantidad_solicitada'];
                                MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioId);
                            }
                        }
                    }
                }
            } else {
                //Guardar asignaciones que hace el usuario

                $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
                $detallesMovimientoBien = $camposDinamicos['atencionesRef'];
                //foreach ($relacionadosDocumentoActual as $item) {
//                        $docRelacionadoId = $item['documento_relacionado_id'];

                $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
                $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoIdActual[0]['movimiento_id']);
                $guardadoNormal = array();
                $guardadoNormal = $dataMovBien;
                foreach ($detallesMovimientoBien as $detalleMB) {
                    foreach ($dataMovBien as $indiceMB => $dataMovBienActual) {
                        if ($dataMovBienActual['bien_id'] == $detalleMB[0]) {
//                                   array_splice($guardadoNormal, $indiceMB,1,null);
                            unset($guardadoNormal[$indiceMB]);
//                                    $guardadoNormal = $dataMovBien;
                        } else {
                            //array_push($guardadoNormal, $dataMovBienActual);
                        }
                    }
                }


                if (!ObjectUtil::isEmpty($guardadoNormal)) {
                    $this->guardarNormalmente($documentoId, $guardadoNormal, $usuarioId);
                }

                foreach ($dataMovBien as $dataMovBienActual) {
                    foreach ($detallesMovimientoBien as $detalleMB) {
                        if ($dataMovBienActual['bien_id'] == $detalleMB[0]) {
                            $detalleArray = $detalleMB[1];
                            $length = count($detalleMB[1]);
                            for ($x = 0; $x < $length; $x++) {
                                $movimiento_bien_anterior = $detalleMB[1][$x]['mov_bien_ant_id'];
                                $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                                $cantidad = $detalleMB[1][$x]['cantidad'];
                                MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioId);
                            }
                        }
                    }
                }
                //}
            }
        }

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
            $respuesta->dataImprimir = $this->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        } else {

            //obtener email de plantilla            
            $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
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
            $respuesta->dataEnvioCorreo = $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }
    }

    public function guardarDocumentoRecepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId) {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $empresaDestinoId = $movimientoTipo[0]["empresa_id"];
        //OBTENIENDO ORGANIZADOR DESTINO        
        foreach ($camposDinamicos as $indexCampos => $valorDtd) {
            if ($valorDtd["tipo"] == 17) {
                $organanizadorDestinoId = $valorDtd["valor"];
            }
        }
        //OBTENIENDO EMPRESA DESTINO SEGUN ORGANIZADOR
        $dataEmpresa = OrganizadorNegocio::create()->obtenerEmpresaXOrganizadorId($organanizadorDestinoId);
        if (!ObjectUtil::isEmpty($dataEmpresa)) {
            $empresaDestinoId = $dataEmpresa[0]['empresa_id'];
        }

        $res = Movimiento::create()->obtenerMovimientoTipoRecepcionXEmpresaIdXCodigo($empresaDestinoId, Configuraciones::MOVIMIENTO_TIPO_CODIGO_RECEPCION);

        $opcionIdR = $res[0]['opcion_id'];
        $documentoTipoIdR = $res[0]['documento_tipo_id'];

        //cabecera del documento
        $camposDinamicosGuia = $camposDinamicos;
        $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoIdR);

        foreach ($configuraciones as $indexConfig => $itemDtd) {
            foreach ($camposDinamicosGuia as $indexCampos => $valorDtd) {
                if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                    $camposDinamicosGuia[$indexCampos]["id"] = $itemDtd["id"];
                    if ($itemDtd["tipo"] == 8) {
                        $camposDinamicosGuia[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoIdR);
                    }
                }
            }
        }

        foreach ($detalle as $indexDet => $item) {
            $detalle[$indexDet]['organizadorId'] = $organanizadorDestinoId;
        }

        $documentoId = $this->guardar($opcionIdR, $usuarioId, $documentoTipoIdR, $camposDinamicosGuia, $detalle, null, 1, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId);
        $documentoARelacionarRecep = array('documentoId' => $documentoId, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => '');

        $respuesta->documentoIdRecep = $documentoId;
        $respuesta->documentoARelacionarRecep = $documentoARelacionarRecep;

        return $respuesta;
    }

    public function imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId) {
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        $indicadorImprimir = $dataDocumentoTipo[0]['indicador_imprimir'] == 1;
        if ($indicadorImprimir == 1) {//genera pdf            
            $hoy = date("Y_m_d_H_i_s");
            $pdf = 'documento_' . $hoy . '_' . $usuarioId . '.pdf';
            $url = __DIR__ . '/../../vistas/com/movimiento/documentos/' . $pdf;
            $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);

            $nombre = MovimientoNegocio::create()->generarDocumentoPDF($documentoId, '', 'F', $url, $data);

            $respuesta->url = $url;
            $respuesta->nombre = $nombre;
            $respuesta->pdf = $pdf;
            return $respuesta;
        } else {
            //VALIDAMOS QUE TENGA PDF LA FACTURA ELECTRONICA
            $resDocElectronico = null;
            if (// $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
            //    $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
                    $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA) {

                $resDocElectronico = $this->imprimirDocumentoElectronico($documentoId);
            }

            if (!ObjectUtil::isEmpty($resDocElectronico) && !ObjectUtil::isEmpty($resDocElectronico->urlPDF)) {
                $respuesta->url = $resDocElectronico->urlPDF;
                $respuesta->nombre = $resDocElectronico->nombrePDF;
                $respuesta->contenedor = $resDocElectronico->contenedor;
                $respuesta->pdfSunat = 1;
                $respuesta->descargar = 0;
                return $respuesta;
            } else {
                //SI ES BOLETA o NOTA DE CREDITO EXPORTAMOS EN PDF
                if ($dataDocumentoTipo[0]['identificador_negocio'] == 5) {
                    $hoy = date("Y_m_d_H_i_s");
                    $pdf = 'documento_' . $hoy . '_' . $usuarioId . '.pdf';
                    $url = __DIR__ . '/../../reporteJasper/documentos/' . $pdf;
                    $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);

                    $nombre = MovimientoNegocio::create()->generarDocumentoImpresionPDF($documentoId, $url, $data);

                    $respuesta->url = $url;
                    $respuesta->nombre = $nombre;
                    $respuesta->pdf = $pdf;
                    $respuesta->iReport = 1;
                } else {
                    return MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
                }
            }
        }
    }

    public function imprimirDocumentoElectronico($documentoId) {
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        if (strtotime($documento[0]['fecha_creacion']) < strtotime('2020-06-11')) {
            $url = Configuraciones::EFACT_CONTENEDOR_PDF_OLD;
        } else {
            $url = Configuraciones::EFACT_CONTENEDOR_PDF;
        }


        if (!ObjectUtil::isEmpty($documento[0]['efact_pdf_nombre'])) {
            $urlPDF = $url . $documento[0]['efact_pdf_nombre'];
        } else {
            $urlPDF = null;
        }

        $respuesta->urlPDF = $urlPDF;
        $respuesta->nombrePDF = $documento[0]['efact_pdf_nombre'];
        $respuesta->contenedor = $url;

        return $respuesta;
    }

    public function existeColumnaCodigo($dataColumna, $codigo) {
        if (!ObjectUtil::isEmpty($dataColumna)) {
            foreach ($dataColumna as $item) {
                if ($item['codigo'] == $codigo) {
                    return true;
                }
            }
        }

        return false;
    }

    public function generarDocumentoDevolucionCargo($opcionId, $usuarioId, $documentoId) {

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        if (ObjectUtil::isEmpty($dataDocumento)) {
            throw new WarningException("No obtuvo la data del documento - pedido. " . $documentoId);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['documento_cargo_id'])) {
            throw new WarningException("Este pedido ya tiene un documento cargo relacionado. " . $dataDocumento[0]['documento_cargo_serie'] . "-" . $dataDocumento[0]['documento_cargo_numero']);
        }

        if ($dataDocumento[0]['documento_estado_id'] != 12) {
            throw new WarningException("Este pedido debe estar en el estado de Entregado. ");
        }

        $fechaEmision = date('d/m/Y');
        $anio = date('Y');
        $mes = date('m');
        $empresaId = $dataDocumento[0]['empresa_id'];

        $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes);

        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("No existe un periodo activo para la fecha " . $fechaEmision);
        }

        $documento = array();
        $documento['documentoTipoId'] = $dataDocumento[0]['documento_tipo_id'];
        $documento['banderaEsCargo'] = 1;
        $documento['documentoOrigenId'] = $documentoId;
        $documento['fechaEmision'] = $fechaEmision;
        $documento['periodoId'] = $dataPeriodo[0]['id'];
        $documento['empresaId'] = $dataDocumento[0]['empresa_id'];
        $documento['personaId'] = $dataDocumento[0]['persona_id'];
        $documento['personaDireccionId'] = $dataDocumento[0]['persona_direccion_id'];
        $documento['clienteId'] = $dataDocumento[0]['persona_destinatario_id'];
        $documento['clienteDireccionId'] = $dataDocumento[0]['persona_direccion_destino_id'];
        $documento['destinatarioDireccionId'] = $dataDocumento[0]['persona_direccion_origen_id'];
        $documento['clienteDireccionDescripcion'] = $dataDocumento[0]['persona_direccion_destino'];
        $documento['destinatarioDireccionDescripcion'] = $dataDocumento[0]['persona_direccion_origen'];
        $documento['agenciaOrigenId'] = $dataDocumento[0]['agencia_destino_id'];
        $documento['agenciaDestinoId'] = $dataDocumento[0]['agencia_id'];
        $documento['tipoPedidoId'] = $dataDocumento[0]['modalidad_id'];
        $documento['destinatarioId'] = $dataDocumento[0]['persona_origen_id'];
//        $documento['documentoId'] = $dataDocumento[0]['persona_direccion_origen'];
        $documento['monedaId'] = $dataDocumento[0]['moneda_id'];
        $documento['documentoEstadoId'] = 10; // POR ENTREGAR

        $documento['monto_total'] = 0.00;
        $documento['monto_igv'] = 0.00;
        $documento['monto_subtotal'] = 0.00;

        $itemDetalle = array();
        $itemDetalle["bienId"] = 2;
        $itemDetalle["unidadMedidaId"] = -1;
        $itemDetalle["precioTipoId"] = -1;
        $itemDetalle["cantidad"] = 1;
        $itemDetalle["precio"] = $dataDocumento[0]['monto_devolucion_gasto'];

        $detalle = array($itemDetalle);
        return $this->guardar($opcionId, $usuarioId, $documento, $detalle);
    }

    public function guardar($opcionId, $usuarioId, $documento, $detalle, $dataPago = NULL) {
        $respuestaTexto = "La operación se completó de manera satisfactoria, documento generado : ";
        $tipoRelacion = NULL;
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $documentoTipoId = $documento['documentoTipoId'];
        $fechaEmision = DateUtil::formatearCadenaACadenaBD($documento['fechaEmision']) . ' ' . date('H:i:s');
        $fechaVencimiento = NULL;
        if (!ObjectUtil::isEmpty($documento['fechaVencimiento'])) {
            $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($documento['fechaVencimiento']);
        }
        if (!ObjectUtil::isEmpty($documento['documentoId'])) {
            $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documento['documentoId']);
            $movimientoId = $dataDocumento[0]['movimiento_id'];
        } else {
            // 1. Insertamos el movimiento
            $movimiento = Movimiento::create()->guardar($movimientoTipoId, 1, $usuarioId);
            $movimientoId = $this->validateResponse($movimiento);
            if (ObjectUtil::isEmpty($movimientoId) || $movimientoId < 1) {
                throw new WarningException("No se pudo guardar el movimiento");
            }
        }

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($documento["empresaId"], $usuarioId);
        if (ObjectUtil::isEmpty($dataAperturaCaja)) {
            throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
        }
        $acCajaId = $dataAperturaCaja[0]['ac_caja_id'];
        $agenciaIdConsulta = $dataAperturaCaja[0]['agencia_id'];

        if ($documento['agenciaOrigenId'] != $agenciaIdConsulta && $documento['agenciaDestinoId'] != $agenciaIdConsulta) {
            throw new WarningException("La agencia donde se aperturo la caja no coincide con la agencia de origen o destino.");
        }

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documento['documentoTipoId']);
        $identificadorNegocio = $dataDocumentoTipo[0]['identificador_negocio'];
        $documentoTipoDescripcion = $dataDocumentoTipo[0]['descripcion'];

        // 2. Insertamos el documento 
        $dataDocumentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documento['documentoTipoId'], $usuarioId, $agenciaIdConsulta);

        foreach ($dataDocumentoTipoDato as $itemDtd) {
            // asignamos la data necesaria para que cargue los componentes
            switch ($itemDtd["tipo"]) {
                case DocumentoTipoNegocio::DATO_SERIE :
                    $serie = $itemDtd["data"];
                    break;
                case DocumentoTipoNegocio::DATO_NUMERO:
                    $numero = $itemDtd["data"];
                    break;
            }
        }

        $respuestaTexto .= $documentoTipoDescripcion . " | " . $serie . "-" . $numero;

        $dataRes = Documento::create()->validarCorrelatividadNumericaConFechaEmision($documentoTipoId, $serie, $numero, $fechaEmision);
        if ($dataRes[0]['validacion'] == 0) {
            throw new WarningException("Validación de correlatividad numérica: cambie la fecha de emisión o el número del documento.");
        }

        $documentoGuardar = Documento::create()->guardar($documentoTipoId, $movimientoId, $documento['personaId'], $documento['personaDireccionId'], NULL, NULL, NULL, $serie, $numero, $fechaEmision, $fechaVencimiento, $fechaVencimiento, NULL, NULL, $documento['monto_total'], $documento['monto_igv'], $documento['monto_subtotal'], 1, $documento['monedaId'], $usuarioId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $documento['periodoId'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $documento['monto_detraccion'],
                $documento['monto_otros_cargos'],
                $documento['monto_devolucion_cargo'],
                $documento['monto_costo_reparto'],
                $documento['monto_otros_cargos_descripcion'],
                $documento['clienteDireccionId'],
                $documento['destinatarioDireccionId'],
                $documento['clienteDireccionDescripcion'],
                $documento['destinatarioDireccionDescripcion'],
                $documento['comprobanteTipoId'],
                $documento['agenciaOrigenId'],
                $documento['agenciaDestinoId'],
                $documento['tipoPedidoId'],
                $documento['destinatarioId'],
                $documento['documentoId'],
                $documento['contacto'],
                $documento['guia_relacion'],
                NULL,
                $documento['clienteId'],
                $acCajaId,
                $documento['banderaEsCargo']
        );
        $documentoId = $this->validateResponse($documentoGuardar);
        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException($documentoGuardar[0]['vout_mensaje']);
        }

        $comprobanteId = $documento['comprobanteId'];

        if ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO && !ObjectUtil::isEmpty($documento['comprobanteTipoId']) && ObjectUtil::isEmpty($comprobanteId) && $documento['banderaEsCargo'] != 1) {
            //EL PAGO SOLO SE RELACIONA AL COMPROBANTE 
            $documentoRelacion = $documento;
            $documentoRelacion['documentoOrigenId'] = $documentoId;
            $documentoRelacion['documentoTipoId'] = $documento['comprobanteTipoId'];
            $documentoRelacion['comprobanteTipoId'] = NULL;
            $documentoRelacion['documentoId'] = NULL;
            $documentoRelacion['fechaVencimiento'] = $documento['fechaEmision'];

            $respuestaGuardarComprobante = $this->guardar($opcionId, $usuarioId, $documentoRelacion, $detalle);
            $comprobanteId = $respuestaGuardarComprobante->documentoId;
            Documento::create()->actualizarComprobanteId($documentoId, $comprobanteId);
        }

        //ACTUALIZAMOS EL CAMPO EN EL PEDIDO ORIGEN
        if ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO && $documento['banderaEsCargo'] == 1) {
            Documento::create()->actulizarDocumentoCargoId($documento['documentoOrigenId'], $documentoId);
        }

        //4. Insertar documento_documento_estado
        if (ObjectUtil::isEmpty($movimientoId)) {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXDocumentoTipo($documento['documentoTipoId']);
        } else {
            $movimientoTipoDocumentoTipo = MovimientoTipoDocumentoTipo::create()->obtenerXMovimiento($movimientoId, $documento['documentoTipoId']);
        }

        if (!ObjectUtil::isEmpty($documento['documentoEstadoId'])) {
            $documentoEstado = $documento['documentoEstadoId'];
        } else {
            $documentoEstado = $movimientoTipoDocumentoTipo['0']['documento_estado_id'];
            if (ObjectUtil::isEmpty($documentoEstado)) {
                $documentoEstado = 1;
            }
        }

        if (!ObjectUtil::isEmpty($documento['documentoOrigenId'])) {
            if ($identificadorNegocio == DocumentoTipoNegocio::IN_CONSTANCIA_ENTREGA) {
                $tipoRelacion = 6; // PEDIDO - CONSTANCIA DE ENTREGA
            } else if ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO && $documento['banderaEsCargo'] == 1) {
                $tipoRelacion = 7; // PEDIDO - PEDIDO (CARGO DEVOLUCIÓN)
            } else {
                $tipoRelacion = 2; // PEDIDO - COMPROBANTE (BOLETA - FACTURA)
            }

            $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($documento['documentoOrigenId'], $documentoId, 1, 1, $usuarioId, NULL, $tipoRelacion);
            if ($respuestaRelacion[0]['vout_exito'] != 1) {
                throw new WarningException("Error al intentar guardar la relación del documento.");
            }
        }

        if (!ObjectUtil::isEmpty($documento['documentoId'])) {
            MovimientoBien::create()->actualizarEstadoXDocumentoId($documento['documentoId'], 2);
        }

        DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($documentoId, $documentoEstado, $usuarioId);
        // 3. Insertamos el detalle
        foreach ($detalle as $item) {
            // validaciones
            if ($item["bienId"] == NULL) {
                throw new WarningException("No se especificó un valor válido para un producto. ");
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

            //validacion el precio unitario tiene que ser mayor al precio de compra.
            $precioCompra = 0;
            $validarPrecios = false;
            if ($item["precio"] * 1 == 0) {
                $validarPrecios = false;
            }

            if (!ObjectUtil::isEmpty($item["precioCompra"])) {
                $precioCompra = $item["precioCompra"];
            }

            //fin validaciones
            if (ObjectUtil::isEmpty($item["adValorem"])) {
                $item["adValorem"] = 0;
            }

            $itemPrecio = $item["precio"];
            $checkIgv = 1;

            $bienAlto = (!ObjectUtil::isEmpty($item["bien_alto"]) ? $item["bien_alto"] : null);
            $bienAncho = (!ObjectUtil::isEmpty($item["bien_ancho"]) ? $item["bien_ancho"] : null);
            $bienLongitud = (!ObjectUtil::isEmpty($item["bien_longitud"]) ? $item["bien_longitud"] : null);
            $bienPeso = (!ObjectUtil::isEmpty($item["bien_peso"]) ? $item["bien_peso"] : null);
            $bienPesoVolumetrico = (!ObjectUtil::isEmpty($item["bien_peso_volumetrico"]) ? $item["bien_peso_volumetrico"] : null);
            $bienFactorVolumetrico = (!ObjectUtil::isEmpty($item["bien_factor_volumetrico"]) ? $item["bien_factor_volumetrico"] : null);
            $movimientoBienPadreId = (!ObjectUtil::isEmpty($item["movimientoBienPadreId"]) ? $item["movimientoBienPadreId"] : null);

            if (!ObjectUtil::isEmpty($movimientoBienPadreId)) {
                $dataMovimientoBien = MovimientoBien::create()->obtenerMovimientoBienPedidoXId($movimientoBienPadreId);
                //VALIDAMOS EL STOCK DISPONIBLE PARA LA ENTTREGA
                if (($dataMovimientoBien[0]['cantidad_entregada'] * 1) + ($item["cantidad"] * 1) > $dataMovimientoBien[0]["cantidad"] * 1) {
                    throw new WarningException("Para el producto " . $dataMovimientoBien[0]['bienDesc'] . ", la cantidad máxima por entregar es " . $dataMovimientoBien[0]['cantidad']);
                }
            }

            $movimientoBien = MovimientoBien::create()->guardar($movimientoId, $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $itemPrecio, 1, $usuarioId, $item["precioTipoId"], $item["utilidad"], $item["utilidadPorcentaje"], $checkIgv, $item["adValorem"], $item["comentarioDetalle"], $item["bienActivoFijoId"]
                    , $bienAlto, $bienAncho, $bienLongitud, $bienPeso, $item["tipo"],
                    $bienPesoVolumetrico, $bienFactorVolumetrico, $movimientoBienPadreId
            );

            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }
        }

        if (!ObjectUtil::isEmpty($documento['documentoOrigenId']) && $identificadorNegocio == DocumentoTipoNegocio::IN_CONSTANCIA_ENTREGA) {
            //VALIDAMOS SI FUE ATENDIDO EN SU TOTALIDAD
            $validarStockEntrega = DocumentoNegocio::create()->validarPedidoStockDisponibleEntregar($documento['documentoOrigenId']);
            DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($documento['documentoOrigenId'], $validarStockEntrega[0]['vout_estado_documento'], $usuarioId);
        }


        if (!ObjectUtil::isEmpty($dataPago) && !ObjectUtil::isEmpty($comprobanteId)) {

            //documento pago
            $documentoTipoIdPago = $dataPago['documentoTipoIdPago'];
            $camposDinamicosPago = $dataPago['camposDinamicosPago'];
            $monedaId = $documento['monedaId'];
            $periodoId = $documento['periodoId'];

            $documentoPagoId = null;
            if ($documentoTipoIdPago != 0) {
                $documentoPagoId = PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoIdPago, $camposDinamicosPago, $monedaId, $periodoId, $acCajaId);
            }

            $montoAPagar = $dataPago['montoAPagar']; // efectivo a pagar
            $tipoCambio = $dataPago['tipoCambio'];
            $tipoCambio = strlen($tipoCambio) == 0 ? null : $tipoCambio;
            $cliente = $dataPago['cliente']; //
            $fecha = $dataPago['fecha'];
            $retencion = 1;
            $monedaPago = $monedaId;
            $empresaId = $documento['empresaId'];
            $actividadEfectivo = $dataPago['actividadEfectivo'];

            $totalDocumento = $dataPago['totalDocumento'];
            $totalPago = $dataPago['totalPago'];
            $dolares = 0;
            if ($monedaPago == 4) {
                $dolares = 1;
            }

            $documentoAPagar = array();
            $documentoAPagar[] = array(
                "documentoId" => $comprobanteId, // BOLETA O FACTURA RELACIONADA
                "tipoDocumento" => '',
                "numero" => '',
                "serie" => '',
                "pendiente" => (float) $totalDocumento,
                "total" => (float) $totalDocumento,
                "dolares" => $dolares
            );

            if (ObjectUtil::isEmpty($documentoPagoId)) {
                $documentoPagoConDocumento = array();
            } else {
                $documentoPagoConDocumento = array();
                $documentoPagoConDocumento [] = array(
                    "documentoId" => $documentoPagoId,
                    "tipoDocumento" => '',
                    "tipoDocumentoId" => '',
                    "numero" => '',
                    "serie" => '',
                    "pendiente" => (float) $totalPago,
                    "total" => (float) $totalPago,
                    "monto" => (float) $totalPago,
                    "dolares" => $dolares
                );
                $montoAPagar = 0;
            }

            $pago = PagoNegocio::create()->registrarPago($cliente, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, NULL, $acCajaId);
            //fin registrar pago 
        }

        if ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO) {
            $respuestaRegistrarQr = DetalleQrNegocio::create()->insertarXPedidoId($documentoId, $usuarioId);
            if ($respuestaRegistrarQr[0]['vout_exito'] != 1) {
                throw new WarningException("Error al intentar generar los códigos qr para el pedido." . $respuestaRegistrarQr[0]['vout_mensaje']);
            }
        }

        $tipoGenerarPdf = NULL;
        $documentoGenerarId = NULL;
        if (($identificadorNegocio == DocumentoTipoNegocio::IN_CONSTANCIA_ENTREGA && $documento["tipoPedidoId"] == 75) || ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO && $documento['banderaEsCargo'] != 1)) {
            $tipoGenerarPdf = 1; //GENERA COMPROBANTE
            $documentoGenerarId = $comprobanteId;
        } elseif ($identificadorNegocio == DocumentoTipoNegocio::IN_CONSTANCIA_ENTREGA || ($identificadorNegocio == DocumentoTipoNegocio::IN_PEDIDO && $documento['banderaEsCargo'] == 1)) {
            $tipoGenerarPdf = 2; //GENERA ACTA DE ENTREGA
            $documentoGenerarId = $documentoId;
        }

        $this->setMensajeEmergente($respuestaTexto);

        $respuesta = new stdClass();
        $respuesta->documentoId = $documentoId;
        $respuesta->documentoGenerarId = $documentoGenerarId;
        $respuesta->tipoGenerarPdf = $tipoGenerarPdf;
        return $respuesta;
    }

    public function movimientoBienDetalleGuardarCadena($movimientoBienId, $columnaCodigo, $valorCadena, $usuarioId) {
        return MovimientoBien::create()->movimientoBienDetalleGuardar($movimientoBienId, $columnaCodigo, $valorCadena, null, $usuarioId);
    }

    public function movimientoBienDetalleGuardarFecha($movimientoBienId, $columnaCodigo, $valorFecha, $usuarioId) {
        return MovimientoBien::create()->movimientoBienDetalleGuardar($movimientoBienId, $columnaCodigo, null, $valorFecha, $usuarioId);
    }

    public function obtenerDocumentosLiquidacionXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $movimientoTipoData = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $numeroLiquidacion = $criterios['numero_liquidacion'];
        $fechaLiquidacionInicio = $criterios['fecha_liquidacion_inicio'];
        $fechaLiquidacionFin = $criterios['fecha_liquidacion_fin'];
        $personaId = $criterios['persona_id'];
        $numeroComprobante = $criterios['numero_comprobante'];
        $fechaComprobanteInicio = $criterios['fecha_comprobante_inicio'];
        $fechaComprobanteFin = $criterios['fecha_comprobante_fin'];
        $documentoEstadoId = $criterios['documento_estado_id'];
        $usuarioId = $criterios['usuario_id'];

        $agenciaId = $criterios['agencia_id'];
        $cajaId = $criterios['caja_id'];
        $banderaDevolucionCargo = $criterios['bandera_devolucion'];

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        if (!ObjectUtil::isEmpty($documentoEstadoId)) {
            $documentoEstadoId = implode(",", $documentoEstadoId);
        }

        if (!ObjectUtil::isEmpty($fechaLiquidacionInicio)) {
            $fechaLiquidacionInicio = DateUtil::formatearCadenaACadenaBD($fechaLiquidacionInicio);
        }

        if (!ObjectUtil::isEmpty($fechaLiquidacionFin)) {
            $fechaLiquidacionFin = DateUtil::formatearCadenaACadenaBD($fechaLiquidacionFin);
        }

        if (!ObjectUtil::isEmpty($fechaComprobanteInicio)) {
            $fechaComprobanteInicio = DateUtil::formatearCadenaACadenaBD($fechaComprobanteInicio);
        }


        if (!ObjectUtil::isEmpty($fechaComprobanteFin)) {
            $fechaComprobanteFin = DateUtil::formatearCadenaACadenaBD($fechaComprobanteFin);
        }

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Movimiento::create()->obtenerDocumentosLiquidacionXCriterios(
                        $numeroLiquidacion, $fechaLiquidacionInicio, $fechaLiquidacionFin, $personaId, $numeroComprobante, $fechaComprobanteInicio, $fechaComprobanteFin, $documentoEstadoId, $usuarioId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,
                        $movimientoTipoData[0]['id'],
                        $agenciaId,
                        $cajaId,
                        $banderaDevolucionCargo
        );
    }

    public function obtenerDocumentosLiquidacionExcelXCriterios($opcionId, $criterios) {
        $movimientoTipoData = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $numeroLiquidacion = $criterios['numero_liquidacion'];
        $fechaLiquidacionInicio = $criterios['fecha_liquidacion_inicio'];
        $fechaLiquidacionFin = $criterios['fecha_liquidacion_fin'];
        $personaId = $criterios['persona_id'];
        $numeroComprobante = $criterios['numero_comprobante'];
        $fechaComprobanteInicio = $criterios['fecha_comprobante_inicio'];
        $fechaComprobanteFin = $criterios['fecha_comprobante_fin'];
        $documentoEstadoId = $criterios['documento_estado_id'];
        $usuarioId = $criterios['usuario_id'];

        $agenciaId = $criterios['agencia_id'];
        $cajaId = $criterios['caja_id'];
        $banderaDevolucionCargo = $criterios['bandera_devolucion'];

        if (!ObjectUtil::isEmpty($documentoEstadoId)) {
            $documentoEstadoId = implode(",", $documentoEstadoId);
        }

        if (!ObjectUtil::isEmpty($fechaLiquidacionInicio)) {
            $fechaLiquidacionInicio = DateUtil::formatearCadenaACadenaBD($fechaLiquidacionInicio);
        }

        if (!ObjectUtil::isEmpty($fechaLiquidacionFin)) {
            $fechaLiquidacionFin = DateUtil::formatearCadenaACadenaBD($fechaLiquidacionFin);
        }

        if (!ObjectUtil::isEmpty($fechaComprobanteInicio)) {
            $fechaComprobanteInicio = DateUtil::formatearCadenaACadenaBD($fechaComprobanteInicio);
        }


        if (!ObjectUtil::isEmpty($fechaComprobanteFin)) {
            $fechaComprobanteFin = DateUtil::formatearCadenaACadenaBD($fechaComprobanteFin);
        }

        $data = Movimiento::create()->obtenerDocumentosLiquidacionExcelXCriterios(
                $numeroLiquidacion, $fechaLiquidacionInicio, $fechaLiquidacionFin, $personaId, $numeroComprobante, $fechaComprobanteInicio, $fechaComprobanteFin, $documentoEstadoId, $usuarioId,
                $movimientoTipoData[0]['id'],
                $agenciaId,
                $cajaId,
                $banderaDevolucionCargo
        );

        return self::generarReporteLiquidacionExcel($data, "LIQUIDACIÓN DE NOTA DE VENTA");
    }

    private function generarReporteLiquidacionExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':K' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $i += 1;
//        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S | N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Comprobante tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'S | N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Comentario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Nota venta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Total');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Usuario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Estado');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            if (!ObjectUtil::isEmpty($reporte['fecha_emision'])) {
                $fechaEmision = DateUtil::formatearFechaACadenaVw($reporte['fecha_emision']);
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $fechaEmision);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ' ' . $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['persona_codigo_identificacion'] . ' | ' . $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['comprobante_tipo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, ' ' . $reporte['comprobante_serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['comentario']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['documento_relacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, round($reporte['total'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $reporte['usuario_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $reporte['documento_estado_negocio_descripcion']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $i += 1;
        }

        for ($i = 'A'; $i <= 'K'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

//        $x = $i;
//        for ($a = 1; $a <= $x; $a++) {
//            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
//        }

        $objPHPExcel->getActiveSheet()->setTitle("Liquidación");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerDocumentosNotaVentaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $fechaInicio = $criterios['fecha_inicio'];
        $fechaFin = $criterios['fecha_fin'];
        $personaId = $criterios['persona_id'];
        $agenciaOrigenId = $criterios['agencia_origen_id'];
        $agenciaDestinoId = $criterios['agencia_destino_id'];
        $documentoId = $criterios['documento_id'];

        if (!ObjectUtil::isEmpty($agenciaOrigenId)) {
            $agenciaOrigenId = implode(",", $agenciaOrigenId);
        }
        if (!ObjectUtil::isEmpty($agenciaDestinoId)) {
            $agenciaDestinoId = implode(",", $agenciaDestinoId);
        }

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        if (!ObjectUtil::isEmpty($fechaInicio)) {
            $fechaInicio = DateUtil::formatearCadenaACadenaBD($fechaInicio);
        }

        if (!ObjectUtil::isEmpty($fechaFin)) {
            $fechaFin = DateUtil::formatearCadenaACadenaBD($fechaFin);
        }

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Movimiento::create()->obtenerDocumentosNotaVentaXCriterios(
                        $serie,
                        $numero,
                        $personaId,
                        $fechaInicio,
                        $fechaFin,
                        $agenciaOrigenId,
                        $agenciaDestinoId,
                        $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $documentoId
        );
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
        return Movimiento::create()->obtenerDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago);
    }

    public function obtenerDocumentosXCriteriosExcel($opcionId, $criterios) {
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

//        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
//            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
//        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);
        //$columnaOrdenarIndice = $order[0]['column'];
        //$formaOrdenar = $order[0]['dir'];
        //$columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

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
        return Movimiento::create()->obtenerDocumentosXCriteriosExcel($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta);
    }

    public function ObtenerTotalDeRegistros() {
        return Movimiento::create()->ObtenerTotalDeRegistros();
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
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];
        // 2. Obtenemos la moneda
        $monedaId = $criterios[0]['monedaId'];
        // 3. Obtenemos el estado negocio de pago
        $estadoNegocioPago = $criterios[0]['estadoNegocio'];

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
        return Movimiento::create()->obtenerCantidadDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago);
    }

    public function obtenerMovimientoTipoAcciones($opcionId) {
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];
        return Movimiento::create()->obtenerMovimientoTipoAcciones($movimientoTipoId);
    }

    // obtener busqueda para pagos 


    public function enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId) {

//        $nombre_fichero = __DIR__ . '/../../vistas/com/movimiento/plantillas/' . $documentoTipoId . ".php";
//
//        if (!file_exists($nombre_fichero)) {
//            throw new WarningException("No existe el archivo del documento para imprimir.");
//        }

        $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId);

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

        //SE NECESITA TAL Y COMO DEVUELVE DE LA BASE PARA iREPORT
        $respuesta->documentoDetalle = $documentoDetalle;

        $total = 0.00;
        foreach ($documentoDetalle as $detalle) {
            $subTotal = $detalle['cantidad'] * $detalle['valor_monetario'];
            array_push($arrayDetalle, $this->getDetalle("", $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['valor_monetario'], $subTotal, $detalle['unidad_medida_descripcion'], $detalle['simbolo'], $detalle['bien_codigo'], $detalle['unidad_medida_id'], $detalle['bien_id'], $detalle['ad_valorem'], $detalle['movimiento_bien_comentario']));
            $total += $subTotal;
        }

        $respuesta->detalle = $arrayDetalle;
        $respuesta->valorIgv = $igv;
        $enLetra = new EnLetras();
//        $respuesta->totalEnTexto = $enLetra->ValorEnLetras($datoDocumento[0]['total']);

        $respuesta->totalEnTexto = $enLetra->ValorEnLetras($datoDocumento[0]['total'], $datoDocumento[0]['moneda_id']);

        //datos empresa
        $empresaId = $datoDocumento[0]["empresa_id"];
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;

        // obtener documentos relacionados
        $respuesta->documentoRelacionado = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);

        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $respuesta->movimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        return $respuesta;
    }

    public function anularDocumentoMensaje($documentoId, $motivoAnulacion, $documentoEstadoId, $usuarioId) {

        if (ObjectUtil::isEmpty($motivoAnulacion)) {
            throw new WarningException("Ingrese motivo de anulación.");
        }

        Documento::create()->actualizarMotivoAnulacionXDocumentoId($documentoId, $motivoAnulacion);

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        $idNegocio = $documento[0]['identificador_negocio'];
        $serie = $documento[0]["serie"];

        return MovimientoNegocio::create()->anularDocumento($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
    }

    public function anularDocumento($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie) {
        //ANULAR LA RECEPCION DE TRANSFERENCIA VIRTUAL O FISICO
        $res = MovimientoNegocio::create()->obtenerDocumentoRelacionadoTipoRecepcion($documentoId);

        if (!ObjectUtil::isEmpty($res)) {
            $res2 = MovimientoNegocio::create()->anular($res[0]['documento_relacionado_id'], $documentoEstadoId, $usuarioId, $idNegocio, $serie);
        }

        return MovimientoNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
    }

    public function anular($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie) {

        $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);

        if ($dataMovimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_ENTRADA) {
            //validacion que al eliminar el documento no resulte negativo.
            $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

            $dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
            foreach ($dataMovimientoBien as $index => $item) {
                if ($item['bien_tipo_id'] != -1) {
                    //obtener las fechas posteriores de los documentos de salida
                    $dataFechas = DocumentoNegocio::create()->obtenerFechasPosterioresDocumentosSalidas(
                            $dataMovimientoTipo[0]['fecha_emision'], $item['bien_id'], $item['organizador_id']);

                    if (!ObjectUtil::isEmpty($dataFechas)) {
                        $dataFechaInicial = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
                        $fechaInicial = $dataFechaInicial[0]['primera_fecha'];

                        foreach ($dataFechas as $itemFecha) {
                            $fechaFinal = $itemFecha['fecha_emision'];
                            //obtener stock
                            $stock = BienNegocio::create()->obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId
                                    ($item['bien_id'], $item['organizador_id'], $item['unidad_medida_id'], $fechaInicial, $fechaFinal);

                            $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                            if ((floatval($stockControlar) - floatval($item['cantidad'])) < 0) {
                                throw new WarningException("No se puede eliminar el documento.<br>"
                                                . " Stock en fecha " . date_format((date_create($fechaFinal)), 'd/m/Y') . ": " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                                . " Producto: " . $item['bien_descripcion'] . "<br>"
                                                . " Cantidad en documento: " . number_format($item['cantidad'], 2, ".", ","));
                            }
                        }
                    }
                }
            }
        }

        //ANULA LOS ASIENTOS Y RELACIONADOS A VENTAS
        IF ($idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA) {
            $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($documentoId, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS);
            if ($respuestaAnularAsiento[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaAnularAsiento[0]['vout_mensaje']);
            }
        }

        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);

        if ($respuestaAnular[0]['vout_exito'] == 1) {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);

            $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId, 'AN', NULL);
            if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                throw new WarningException("No se Actualizo documento estado");
            }

            // actualizamos el estado de efact_estado_anulacion a 0 (pendiente)
            if ($idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serie[0] == 'B')) {
                Documento::create()->actualizarEfactEstadoAnulacionXDocumentoId($documentoId, 0);
            }


            $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($documentoId);
            if ($dataEmpresa[0]['efactura'] == 1 && (
                    $idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                    ( $idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serie[0] == 'F') )) {
                $this->anularFacturaElectronica($documentoId);
            }
        } else {
            throw new WarningException($respuestaAnular[0]['vout_mensaje']);
        }
    }

    public function anularFacturaElectronica($documentoId) {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $idNegocio = $documento[0]['identificador_negocio'];

        if ($idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA) {

            $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

            $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
            if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
                throw new WarningException("No se especificó el ubigeo del emisor");
            }

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
            if (ObjectUtil::isEmpty($persona)) {
                throw new WarningException("No se encontró a la persona del documento");
            }
            $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
            if (ObjectUtil::isEmpty($ubigeo)) {
                throw new WarningException("No se especificó el ubigeo del receptor");
            }
            $enLetras = new EnLetras();
            $importeLetras = $enLetras->ValorEnLetras($documento[0]["total"], $documento[0]['moneda_id']);

            $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
            $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
            $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
            $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
            $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
            $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
            $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
            $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
            $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
            $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

            // factura
            $comprobanteElectronico->docFechaEmision = date("Y-m-d"); //$documento[0]["fecha_emision"];
            $comprobanteElectronico->docFechaReferencia = substr($documento[0]["fecha_emision"], 0, 10);
            $comprobanteElectronico->docSecuencial = $documento[0]['nro_secuencial_baja'];

            // Detalle
            // Det0           
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA) {
                $serieNum = DocumentoNegocio::create()->obtenerSerieNumeroXDocumentoId($documentoId);

                $serieDoc = $serieNum[0]["serie"];
                $numeroDoc = $serieNum[0]["numero"];
            }

            //VALIDA SERIE
            if ($idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA && $serieDoc[0] != 'B') {
                throw new WarningException("La serie de la boleta a eliminar debe empezar con B");
            } else if ($idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA && $serieDoc[0] != 'F') {
                throw new WarningException("La serie de la factura a eliminar debe empezar con F");
            } else if ($serieDoc[0] != 'B' && $serieDoc[0] != 'F') {
                throw new WarningException("La serie del documento a eliminar debe empezar con F o B");
            }

            //VALIDA MOTIVO ANULACION
            if (ObjectUtil::isEmpty($documento[0]['motivo_anulacion'])) {
                throw new WarningException("Motivo de anulación es obligatorio.");
            }

            $items[0][0] = 1;
            $items[0][1] = $documento[0]["sunat_tipo_doc_rel"];
            $items[0][2] = $serieDoc;
            $items[0][3] = $numeroDoc;
            $items[0][4] = $documento[0]['motivo_anulacion'];

            $comprobanteElectronico->bajas = $items;
            $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
            $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
            $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
            $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
            $comprobanteElectronico = (array) $comprobanteElectronico;

            $client = self::conexionEFAC();

            $resultado = $client->procesarComunicacionBaja($comprobanteElectronico)->procesarComunicacionBajaResult;
//            $this->setMensajeEmergente("Resultado EFACT: ".$resultado);
//            VALIDAR EL RESULTADO
            $this->validarResultadoEfactura($resultado);
//            var_dump($comprobanteElectronico);

            if (strpos($resultado, 'ticket') !== false) {
                $nroticket = explode(':', $resultado);
                $ticket = trim($nroticket[2]);
            }

            //SI TODO ESTA BIEN ACTUALIZAMOS EL NUMERO SECUENCIAL DE BAJA Y EL TICKET QUE SE GENERÓ
            DocumentoNegocio::create()->actualizarNroSecuencialBajaXDocumentoId($documentoId, $documento[0]['nro_secuencial_baja'], $ticket);
        }
    }

    public function generarDocumentoElectronicoPorResumenDiario($documentosResumen) {
        //obtenemos id de documentos que se enviaran a resumen                
//        $documentosResumen = DocumentoNegocio::create()->obtenerIdDocumentosResumenDiario();


        if (ObjectUtil::isEmpty($documentosResumen)) {
            throw new WarningException("No se encontraron documentos para generar por resumen diario");
        }

        $i = 0;
        foreach ($documentosResumen as $index => $fila) {

            //arreglo con los id del documento
            $idDocumentos[$index] = $fila['documentoId'];

            $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($fila['documentoId']);

            $montoTotal = $documento[0]["total"] * 1.0;
            $montoIgv = $documento[0]["igv"] * 1.0;
            $montoAfecto = $documento[0]["subtotal"] * 1.0;
            $montoGratuito = 0;
            if ($documento[0]['movimiento_tipo_codigo'] == "18") {
                $montoTotal = 0;
                $montoIgv = 0;
                $montoAfecto = 0;
                if ($documento[0]['noafecto'] * 1.0 > 0) {
                    $montoGratuito = $documento[0]['noafecto'] * 1.0;
                } else {
                    $montoGratuito = $documento[0]["total"] * 1.0;
                }
            }
            $items[$index][0] = $i + 1; //Número de fila
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];
            $items[$index][1] = $serieDoc . '-' . $numeroDoc; //Número de serie del documento – Numero correlativo 
            $items[$index][2] = $documento[0]["sunat_tipo_doc_rel"]; //Tipo de documento
            $items[$index][3] = $documento[0]["sunat_moneda"]; //moneda

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);

            $items[$index][4] = $persona[0]["codigo_identificacion"]; //Número de documento de Identidad del adquirente o usuario
            $items[$index][5] = $persona[0]["sunat_tipo_documento"]; //Tipo de documento de Identidad del adquirente o usuario
            $items[$index][6] = 1; //Estado del ítem (1 es generar)
            $items[$index][7] = $montoTotal; //Importe total de la venta                 
            $items[$index][8] = $montoAfecto; //Total valor de venta - operaciones gravadas
            $items[$index][9] = 0.0; //Total valor de venta - operaciones exoneradas
            $items[$index][10] = 0.0; //Total valor de venta - operaciones inafectas
            $items[$index][11] = $montoGratuito; //Total Valor Venta operaciones Gratuitas
            $items[$index][12] = $montoIgv;  //Total IGV

            $items[$index][13] = null; //nroDocumentoRelacionado 
            $items[$index][14] = null; //tipoDocumentoRelacionado

            $idNegocio = $documento[0]['identificador_negocio'];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serieDoc[0] == 'B') {

                //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
                $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($fila['documentoId']);

                foreach ($docRelacion as $indRel => $itemRel) {
                    if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA) {
                        $items[$index][13] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"]; //nroDocumentoRelacionado
                        $items[$index][14] = $itemRel['sunat_tipo_doc_rel']; //tipoDocumentoRelacionado
                    }
                }
            }
            $i++;
        }
        // Obtenemos Datos de emisor
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentosResumen[0]["documentoId"]);
        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);
        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

        $comprobanteElectronico->docFechaEmision = date('Y-m-d'); //$documento[0]["fecha_emision"];       
        $comprobanteElectronico->docFechaReferencia = $documento[0]["fecha_emision"];
        $comprobanteElectronico->docSecuencial = 2;

        $comprobanteElectronico->resumenes = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
        $comprobanteElectronico = (array) $comprobanteElectronico;
//        var_dump($comprobanteElectronico);              
//        exit();  

        $client = self::conexionEFAC();

        $resultado = $client->procesarResumenDiarioNuevo($comprobanteElectronico)->procesarResumenDiarioNuevoResult;
        //        VALIDAR EL RESULTADO
        $this->validarResultadoEfactura($resultado);
//        var_dump($comprobanteElectronico);          

        if (strpos($resultado, 'ticket') !== false) {
            $nroticket = explode(':', $resultado);
            $ticket = trim($nroticket[2]);
        }

        for ($j = 0; $j < count($idDocumentos); $j++) {
            DocumentoNegocio::create()->actualizarEstadoEfactAnulacionXDocumentoId($idDocumentos[$j], NULL, $ticket);
            DocumentoNegocio::create()->actualizarEfactEstadoRegistro($idDocumentos[$j], 1);
        }
    }

    public function anularDocumentoElectronicoPorResumenDiario() {
        //obtenemos id de documentos que se enviaran a resumen                
        $documentosResumen = DocumentoNegocio::create()->obtenerIdDocumentosResumenDiario();
        $i = 0;

        if (ObjectUtil::isEmpty($documentosResumen)) {
            throw new WarningException("No se encontraron documentos para realizar baja por resumen diario");
        }

        foreach ($documentosResumen as $index => $fila) {

            //arreglo con los id del documento
            $idDocumentos[$index] = $fila['documentoId'];

            $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($fila['documentoId']);

            $montoTotal = $documento[0]["total"] * 1.0;
            $montoIgv = $documento[0]["igv"] * 1.0;
            $montoAfecto = $documento[0]["subtotal"] * 1.0;
            $montoGratuito = 0;
            if ($documento[0]['movimiento_tipo_codigo'] == "18") {
                $montoTotal = 0;
                $montoIgv = 0;
                $montoAfecto = 0;
                if ($documento[0]['noafecto'] * 1.0 > 0) {
                    $montoGratuito = $documento[0]['noafecto'] * 1.0;
                } else {
                    $montoGratuito = $documento[0]["total"] * 1.0;
                }
            }
            $items[$index][0] = $i + 1; //Número de fila
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];
            $items[$index][1] = $serieDoc . '-' . $numeroDoc; //Número de serie del documento – Numero correlativo 
            $items[$index][2] = $documento[0]["sunat_tipo_doc_rel"]; //Tipo de documento
            $items[$index][3] = $documento[0]["sunat_moneda"]; //moneda

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);

            $items[$index][4] = $persona[0]["codigo_identificacion"]; //Número de documento de Identidad del adquirente o usuario
            $items[$index][5] = $persona[0]["sunat_tipo_documento"]; //Tipo de documento de Identidad del adquirente o usuario
            $items[$index][6] = 3; //Estado del ítem (3 es anulado)
            $items[$index][7] = $montoTotal; //Importe total de la venta                 
            $items[$index][8] = $montoAfecto; //Total valor de venta - operaciones gravadas
            $items[$index][9] = 0.0; //Total valor de venta - operaciones exoneradas
            $items[$index][10] = 0.0; //Total valor de venta - operaciones inafectas
            $items[$index][11] = $montoGratuito; //Total Valor Venta operaciones Gratuitas
            $items[$index][12] = $montoIgv;  //Total IGV

            $items[$index][13] = null; //nroDocumentoRelacionado 
            $items[$index][14] = null; //tipoDocumentoRelacionado

            $idNegocio = $documento[0]['identificador_negocio'];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serieDoc[0] == 'B') {

                //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
                $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($fila['documentoId']);

                foreach ($docRelacion as $indRel => $itemRel) {
                    if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA) {
                        $items[$index][13] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"]; //nroDocumentoRelacionado
                        $items[$index][14] = $itemRel['sunat_tipo_doc_rel']; //tipoDocumentoRelacionado
                    }
                }
            }
            $i++;
        }
        // Obtenemos Datos de emisor
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentosResumen[0]["documentoId"]);
        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);
        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

        $comprobanteElectronico->docFechaEmision = date('Y-m-d'); //$documento[0]["fecha_emision"];       
        $comprobanteElectronico->docFechaReferencia = $documento[0]["fecha_emision"];
        $comprobanteElectronico->docSecuencial = 1;

        $comprobanteElectronico->resumenes = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
        $comprobanteElectronico = (array) $comprobanteElectronico;
//        var_dump($comprobanteElectronico);              
//        exit();  

        $client = self::conexionEFAC();

        $resultado = $client->procesarResumenDiarioNuevo($comprobanteElectronico)->procesarResumenDiarioNuevoResult;
//        $this->setMensajeEmergente("Resultado EFACT: ".$resultado);
//        VALIDAR EL RESULTADO
        $this->validarResultadoEfactura($resultado);
//        var_dump($comprobanteElectronico);          

        if (strpos($resultado, 'ticket') !== false) {
            $nroticket = explode(':', $resultado);
            $ticket = trim($nroticket[2]);
        }

        for ($j = 0; $j < count($idDocumentos); $j++) {
            DocumentoNegocio::create()->actualizarEstadoEfactAnulacionXDocumentoId($idDocumentos[$j], 1, $ticket);
            DocumentoNegocio::create()->actualizarEfactEstadoRegistro($idDocumentos[$j], 1);
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
        $respuesta = new ObjectUtil();
//        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $respuesta->direccionEmpresa = DocumentoNegocio::create()->obtenerDireccionEmpresa($documentoId);
        $movimientoId = (!ObjectUtil::isEmpty($movimientoId) ? $movimientoId : $respuesta->dataDocumento[0]['movimiento_id']);
        $respuesta->detalleDocumento = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);
        $respuesta->detalleEntregaRelacionadas = MovimientoBien::create()->obtenerDetalleEntregaRelacionadaXIdMovimiento($movimientoId);
        return $respuesta;
    }

    private function getDetalle($organizador, $cantidad, $descripcion, $precioUnitario, $importe, $unidadMedida, $simbolo, $bien_codigo, $unidadMedidaID, $bienId, $adValorem = 0, $movimientoBienComentario = '') {

        $detalle = new stdClass();
        $detalle->organizador = $organizador;
        $detalle->cantidad = $cantidad;
        $detalle->descripcion = $descripcion;
        $detalle->precioUnitario = $precioUnitario;
        $detalle->importe = $importe;
        $detalle->unidadMedida = $unidadMedida;
        $detalle->simbolo = $simbolo;
        $detalle->bien_codigo = $bien_codigo;
        $detalle->unidadMedidaId = $unidadMedidaID;
        $detalle->bienId = $bienId;
        $detalle->adValorem = $adValorem;
        $detalle->movimientoBienComentario = $movimientoBienComentario;
        return $detalle;
    }

    public function obtenerStockAControlar($opcionId, $bienId, $organizadorId, $unidadMedidaId, $cantidad, $fechaEmision = null, $organizadorDestinoId = null) {
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
                                    ($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal, $organizadorDestinoId);

                            $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                            if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                                throw new WarningException("No cuenta con stock suficiente en el almacén seleccionado.<br>"
                                                . " Stock en fecha " . date_format((date_create($fechaFinal)), 'd/m/Y') . ": " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                                . " Producto: " . $bien[0]['descripcion'] . "<br>"
                                                . " Cantidad: " . $cantidad);
                            }
                        }
                    } else {
                        // stock hasta fecha actual
                        $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
                        // obtenerStockBase($organizadorId, $bienId);
                        $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;
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

    private function guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $guardar) {
        $respuesta = new stdClass();
        Try {
            $this->beginTransaction();
            MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, null, null);
            $this->commitTransaction();

            $respuesta->exito = true;
            $respuesta->mensaje = "Éxito";
        } catch (WarningException $we) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $we->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $we->getMessage();
        } catch (ModeloException $me) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $me->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $me->getMessage();
        } catch (Exception $ex) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $ex->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $ex->getMessage();
        }

        if ($guardar == TRUE) {
            ExcelNegocio::create()->guardarExcelMovimientosErrores($opcionId);
        }

        return $respuesta;
    }

    public function importarExcelMovimiento($opcionId, $usuarioId, $xml, $usuCreacion) {
        $filasImportadas = 0;
        $row = 7;
        $errors = array();
        $dom = new DOMDocument;
        $xml = "<root>" . $xml . "</root>";
        //Documento tipo
        $xml = str_replace("documento tipo", "documentoTipo", $xml);

        $dom->loadXML($xml);
        $movExcel = simplexml_import_dom($dom);
        $detalle = array();
        for ($i = 0; $i < count($movExcel); $i++) {
            $bandera = false;
            $filaExcel = $movExcel->movi[$i];
            //documentoTipo
            $documentoTipoNombre = trim((string) $filaExcel->documentoTipo);

            $documentoTipo = DocumentoTipoNegocio::create()->obtenerIdXDocumentoTipoDescripcionOpcionId($documentoTipoNombre, $opcionId);
            $documentoTipoId = $documentoTipo[0][id];
            $empresaId = $documentoTipo[0][empresa_id];

            //Dinamico            
            $documentoTipoNombreDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);

            $indice = 0;
            foreach ($documentoTipoNombreDato as $index => $dtd) {
                $nombreColumna = str_replace(' ', '', $dtd['descripcion']);
                if (property_exists($filaExcel, $nombreColumna)) {
                    $valorDinamico = trim((string) $filaExcel->{$nombreColumna});

                    switch ($dtd["tipo"]) {
                        case DocumentoTipoNegocio::DATO_PERSONA:
                            // buscar en data
                            $valor = null;
                            foreach ($dtd["data"] as $persona) {
                                if ($persona['nombre'] == $valorDinamico) {
                                    $valor = $persona[id];
                                    break;
                                }
                            }
                            $documentoTipoNombreDato[$index]["valor"] = $valor;
                            break;
                        case DocumentoTipoNegocio::DATO_LISTA:
                            $valor = null;
                            foreach ($dtd["data"] as $lista) {
                                if ($lista['descripcion'] == $valorDinamico) {
                                    $valor = $lista[id];
                                    break;
                                }
                            }
                            $documentoTipoNombreDato[$index]["valor"] = $valor;
                            break;

                        default:
                            //$documentoTipoNombreDato[$index]["valor"] = $filaExcel[$dtd["descripcion"]];
                            $documentoTipoNombreDato[$index]["valor"] = $valorDinamico;
                    }

                    $camposDinamicos[$indice] = array(
                        id => $documentoTipoNombreDato[$index]["id"],
                        tipo => $documentoTipoNombreDato[$index]["tipo"],
                        opcional => $documentoTipoNombreDato[$index]["opcional"],
                        descripcion => $documentoTipoNombreDato[$index]["descripcion"],
                        valor => $documentoTipoNombreDato[$index]["valor"],
                        valorExcel => $valorDinamico
                    );
                    $indice++;
                    //$valorDinamicoAntes=$valorDinamico;
                }
            }

            $camposDinamicosGuardar = $camposDinamicosAntes;
            $documentoTipoIdGuardar = $documentoTipoIdAntes;

            if ($i != 0) {
                if ($camposDinamicosAntes != $camposDinamicos) {
                    $bandera = true;
                }
            }

            $camposDinamicosAntes = $camposDinamicos;
            $documentoTipoIdAntes = $documentoTipoId;

            //detalle
            $organizador = trim((string) $filaExcel->Organizador);
            $cantidad = trim((string) $filaExcel->Cantidad);
            $unidadMedida = trim((string) $filaExcel->UnidadMedida);
            $bien = trim((string) $filaExcel->Bien);
            $precioUnitario = trim((string) $filaExcel->PrecioUnitario);
            $totalDetalle = trim((string) $filaExcel->TotalDetalle);

            //buscamos Id de los detalles
            $organizadorBuscado = OrganizadorNegocio::create()->obtenerOrganizadorActivoXDescripcion($organizador);
            $organizador_id = $organizadorBuscado[0][id];

            $unidadMedidaBuscado = UnidadNegocio::create()->obtenerUnidadMedidaActivoXDescripcion($unidadMedida);
            $unidadMedida_id = $unidadMedidaBuscado[0][id];

            $bienBuscado = BienNegocio::create()->obtenerBienActivoXDescripcion($bien);
            $bien_id = $bienBuscado[0][id];

            //array detalles
            if ($bandera == false) {
                array_push($detalle, array(organizadorId => $organizador_id, bienId => $bien_id,
                    cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                    organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                    subTotal => $totalDetalle));
            }

            if ($i == (count($movExcel) - 1)) {
                if ($bandera == true) {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoIdGuardar, $camposDinamicosGuardar, $detalle, false);
                    //Errores
                    if ($response > 0) {
                        $filasImportadas++;
                    } else {
                        $cause = $response[0]["vout_mensaje"];
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;

                    $detalle = array();

                    array_push($detalle, array(organizadorId => $organizador_id, bienId => $bien_id,
                        cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                        organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                        subTotal => $totalDetalle));

                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, true);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;
                } else {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, true);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;
                }
            } else {
                if ($bandera == true) {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoIdGuardar, $camposDinamicosGuardar, $detalle, false);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;

                    $detalle = array();

                    array_push($detalle, array(organizadorId => $organizador_id, bienId => $bien_id,
                        cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                        organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                        subTotal => $totalDetalle));
                }
            }
        }
        if ($row == $filasImportadas + 7) {
            $this->setMensajeEmergente("Importacion finalizada. Se procesaron $filasImportadas de " . ($row - 7) . " filas.");
        }

        return $errors;
    }

    //Area de funciones para copiar documento 

    function obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId) {

        $tipoIds = '(0),(1),(4)';
        $respuesta = new ObjectUtil();
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];

        //$respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($empresaId, $tipoIds);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $tipoIds);
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

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = Movimiento::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $transferenciaTipo, $movimientoTipoId);

        $respuesta->contador = Movimiento::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $transferenciaTipo, $movimientoTipoId);

        return $respuesta;
    }

    function obtenerDocumentoRelacionCabecera($documentoOrigenId, $documentoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados) {
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

    function obtenerDocumentoRelacionNotaVenta($documentoPorRelacionar, $documentoRelacionados) {

        $respuesta = new stdClass();
        $dataDocumento = array();
        $dataDocumentoDetalle = array();

        foreach ($documentoPorRelacionar as $item) {
            $documento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);
            $detalle = MovimientoBien::create()->obtenerXIdMovimiento($item['movimientoId']);

            $dataDocumento = array_merge($dataDocumento, $documento);
            $dataDocumentoDetalle = array_merge($dataDocumentoDetalle, $detalle);
        }

        $respuesta->dataDocumento = $dataDocumento;
        $respuesta->detalleDocumento = $dataDocumentoDetalle;

        return $respuesta;
    }

    function obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo) {

        $respuesta = DocumentoNegocio::create()->obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo);

        return $respuesta;
    }

    function obtenerDocumentoRelacionDua($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $respuesta = $this->obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);

        // Obtenemos las cantidades de la GR relacionada a la OC que estamos copiando
        $gr = MovimientoBien::create()->obtenerGRCantidadesPorOCId($documentoId);
        if (!ObjectUtil::isEmpty($gr) && !ObjectUtil::isEmpty($respuesta->detalleDocumento)) {
            foreach ($respuesta->detalleDocumento as $iDD => $itemDD) {
                foreach ($gr as $itemGR) {
                    if ($itemDD["bien_id"] == $itemGR["bien_id"] && $itemDD["unidad_medida_id"] == $itemGR["unidad_medida_id"]) {
                        $respuesta->detalleDocumento[$iDD]["cantidad"] = $itemGR["cantidad"];
                        break;
                    }
                }
            }
        }

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
        if (ObjectUtil::isEmpty($organizador)) {
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

    function obtenerDocumentoRelacionDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $banderaMerge = 0;
        $arrayDetalle = array();

        $tamanhoArrayRelacionado = count($documentoRelacionados);
        if (!ObjectUtil::isEmpty($movimientoId) && !ObjectUtil::isEmpty($documentoId)) {
            $documentoRelacionados[$tamanhoArrayRelacionado]['movimientoId'] = $movimientoId;
            $documentoRelacionados[$tamanhoArrayRelacionado]['documentoId'] = $documentoId;
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $transferenciaTipo = $movimientoTipo[0]["transferencia_tipo"];

        foreach ($documentoRelacionados as $documentoRelacion) {
            if ($transferenciaTipo == 2) {
                $documentoDetalle = MovimientoBien::create()->obtenerDetalleTransferenciaXIdMovimiento($documentoRelacion['movimientoId']);
            } else {
                //OBTENEMOS LOS DOCUMENTOS HIJOS DE LA COPIA
                $documentoRelacionHijos = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoRelacion['documentoId']);
                $movimientoIdHijos = '';
                if (!ObjectUtil::isEmpty($documentoRelacionHijos)) {
                    foreach ($documentoRelacionHijos as $itemRel) {
                        if (!ObjectUtil::isEmpty($itemRel['movimiento_id'])) {
                            $movimientoIdHijos = $movimientoIdHijos . $itemRel['movimiento_id'] . ',';
                        }
                    }
                }

                if ($movimientoIdHijos != '') {
                    //OBTIENE CON PRECIOS DE LOS DOCUMENTOS HIJOS
                    $documentoDetalle = MovimientoBien::create()->obtenerXMovimientoIdXMovimientoIdRelacion($documentoRelacion['movimientoId'], $movimientoIdHijos);
                } else {
                    $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);
                }
            }

            //$documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);

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
                                    $detalle['organizador_descripcion'], $detalle['organizador_id'], $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['bien_id'], $detalle['valor_monetario'], $detalle['unidad_medida_id'], $detalle['unidad_medida_descripcion'], $detalle['precio_tipo_id'], $resMovimientoBienDetalle, $detalle['movimiento_bien_comentario']
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
//        return $this->validarStockDocumento($arrayDetalle, $movimientoTipoId);
        return $arrayDetalle;
//        return $respuesta;
    }

    private function getDocumentoACopiarMerge($organizadorDescripcion, $organizadorId, $cantidad, $bienDescripcion, $bienId, $valorMonetario, $unidadMedidaId, $unidadMedidaDescripcion, $precioTipoId, $movimientoBienDetalle, $movimientoBienComentario) {

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
            "movimiento_bien_detalle" => $movimientoBienDetalle,
            "movimiento_bien_comentario" => $movimientoBienComentario
        );

        return $detalle;
    }

    public function obtenerDocumentosRelacionados($documentoId) {

        return DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($documentoId);
    }

    public function enviarCorreoDocumentoConFechaEmisionAnterior($documentoId, $movimientoId, $usuarioId) {
        $plantillaId = 17;
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

        $correos = '';
        foreach ($correosPlantilla as $email) {
            $correos = $correos . $email . ';';
        }

        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);

        //dibujar cuerpo y detalle
        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        $comentarioFinalDocumento = '<tr><td style="text-align: left; padding: 0 55px 10px; font-size: 14px; line-height: 1.5; width: 80%">Documento generado con fecha de emisión anterior a la fecha actual, registrado en la empresa '
                . $data->direccionEmpresa[0]['razon_social']
                . ' ubicada en '
                . $data->direccionEmpresa[0]['direccion']
                . '</td></tr>'
        ;
        //fin dibujo

        $comentarioDocumento = $data->comentarioDocumento[0]['comentario_documemto'];
        //logica correo:                             
        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentarioDocumento, $cuerpo);
        $cuerpo = str_replace("[|comentario_final_documento|]", $comentarioFinalDocumento, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
        return 1;
    }

    public function obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador) {

        $data = BienNegocio::create()->obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador);
        return $data;
    }

    public function verificarTipoUnidadMedidaParaTramo($unidadMedidaId) {
        $data = UnidadMedidaTipo::create()->verificarTipoUnidadMedidaParaTramo($unidadMedidaId);
        return $data;
    }

    public function registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion) {
        $data = Movimiento::create()->registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion);
        return $data;
    }

    public function obtenerTramoBien($bienId) {
        $data = Movimiento::create()->obtenerTramoBienXBienId($bienId);
        return $data;
    }

    public function editarComentarioDocumento($documentoId, $comentario) {
        $res = DocumentoNegocio::create()->actualizarComentarioDocumento($documentoId, $comentario);
        return $res;
    }

    public function obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId) {
        $data = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);

        return $data;
    }

    public function guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario = null, $periodoId = null, $tipoPago = null, $monedaId = null, $usuarioId, $contOperacionTipoId = null, $afectoAImpuesto = null, $tipoEdicion = null) {
        return DocumentoNegocio::create()->guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario, $periodoId, $tipoPago, $monedaId, $usuarioId, $contOperacionTipoId, $afectoAImpuesto, $tipoEdicion);
    }

    public function enviarMovimientoEmailPDF($correo, $documentoId, $comentario, $usuarioId, $plantillaId) {

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];
        $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
        $dataDocumento = $data->dataDocumento;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];
        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId); // antes 7

        $hoy = date("Y_m_d_H_i_s");
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/documento_' . $hoy . '_' . $usuarioId . '.pdf';

        //crear PDF
        $this->generarDocumentoPDF($documentoId, $comentario, 'F', $url, $data);

        //envio de email
        $email = new EmailEnvioUtil();

        $asunto = $titulo;
        $cuerpo = $plantilla[0]["cuerpo"];

        $cuerpo = str_replace("[|titulo|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|descripcion_persona|]", strtolower($descripcionPersona), $cuerpo);
        $cuerpo = str_replace("[|nombre_persona|]", $dataDocumento[0]['nombre'], $cuerpo);
        $cuerpo = str_replace("[|nombre_documento|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|serie_numero|]", $serieDocumento . $dataDocumento[0]['numero'], $cuerpo);
        $nombreArchivo = $dataDocumentoTipo[0]['descripcion'] . ".pdf";

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId, $url, $nombreArchivo);

        if (!ObjectUtil::isEmpty($res[0]['id'])) {
            $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        }

        return $res;
    }

    public function generarDocumentoPDF($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        require_once __DIR__ . '/../../controlador/commons/tcpdf/config/lang/eng.php';
        require_once __DIR__ . '/../../controlador/commons/tcpdf/tcpdf.php';

        //$tipoSalidaPDF: F-> guarda local        
        $dataDocumento = $data->dataDocumento;

        // create new PDF document

        $identificadorNegocio = $dataDocumento[0]['identificador_negocio'];

        switch ((int) $identificadorNegocio) {
            case 1://generar pdf Cotizacion
                return $this->generarDocumentoPDFCotizacion($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
            case 6: //guia remision
                return $this->generarDocumentoPDFGuiaRemision($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
            case 8://generar pdf orden de compra
                return $this->generarDocumentoPDFOrdenCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 10://generar pdf orden de compra extranjera
                return $this->generarDocumentoPDFOrdenCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 11://generar pdf solicitud de compra
                return $this->generarDocumentoPDFSolicitudCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 12://generar pdf solicitud de compra extranjera
                return $this->generarDocumentoPDFSolicitudCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 13://generar pdf Cotizacion compra
                return $this->generarDocumentoPDFCotizacionCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 14://generar pdf Cotizacion compra extranjera
                return $this->generarDocumentoPDFCotizacionCompraEXT($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 23://generar pdf Guia interna para transferencia
                return $this->generarDocumentoPDFGuiaInterna($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            default:
                return $this->generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
        }
    }

    public function generarDocumentoPDFGuiaInterna($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

//        $pdf->SetHeaderData('logo.PNG', PDF_HEADER_LOGO_WIDTH,strtoupper($dataDocumentoTipo[0]['descripcion']),$dataEmpresa[0]['razon_social']."\n".$dataEmpresa[0]['direccion'], array(0,0,0), array(0,0,0));
        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
        //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));

        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);

        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->Cell(0, 0, $descripcionFechaEmision . ": " . $fecha, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['direccion'])) {
            $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] != 23) {
            $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_vencimiento'])) {
            $pdf->Cell(0, 0, "Fecha de vencimiento: " . date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_tentativa'])) {
            $pdf->Cell(0, 0, "Fecha tentativa: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        $origen = '';
        $destino = '';
        foreach ($documentoDatoValor as $indice => $item) {
            if ($item['documento_tipo_id'] == 2887) {
                $origen = $item['valor'];
            }
            if ($item['documento_tipo_id'] == 2888) {
                $destino = $item['valor'];
            }
        }

        if (ObjectUtil::isEmpty($origen)) {
            $origen = $dataDocumento[0]['org_origen_desc'];
        }
        if (ObjectUtil::isEmpty($destino)) {
            $destino = $dataDocumento[0]['org_destino_desc'];
        }

        $pdf->Cell(0, 0, "Origen: " . $origen, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Destino: " . $destino, 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h4>DETALLE DEL DOCUMENTO</h4>", 0, 1, 0, true, 'L', true);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco
        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="8%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="12%"><b>Código</b></th>
                        <th style="text-align:center;" width="42%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="13%"><b>Unid.</b></th>
                        <th style="text-align:center;" width="12%"><b>P. Unit.</b></th>
                        <th style="text-align:center;" width="13%"><b>P. Total</b></th>
                    </tr>
                '
            ;

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                        . '<td style="text-align:left"  width="12%">' . $esp . $item->bien_codigo . $esp . '</td>'
                        . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                        . '<td style="text-align:center"  width="13%">' . $esp . $item->unidadMedida . $esp . '</td>'
                        . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                        . '</tr>'
                ;
            };

            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
                        . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
                        . '</tr>';
            }
        } else {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="13%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="16%"><b>Código</b></th>
                        <th style="text-align:center;" width="50%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="21%"><b>Unid.</b></th>
                    </tr>
                '
            ;

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                        . '<td style="text-align:left"  width="16%">' . $esp . $item->bien_codigo . $esp . '</td>'
                        . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                        . '<td style="text-align:center"  width="21%">' . $esp . $item->unidadMedida . $esp . '</td>'
                        . '</tr>'
                ;
            };
        }

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
//            $espaciado = 15;
            $pdf->AddPage();
        }

//        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115      

        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            $pdf->Ln(5);

            if ($identificadorNegocio == 1) {
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>TERMINOS Y CONDICIONES</h4>", 'TB', 1, 0, true, 'C', true);
            } else {
                ;
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>OTROS DATOS DEL DOCUMENTO</h4>", 'TB', 1, 0, true, 'C', true);
            }

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentoDatoValor as $indice => $item) {
                if ($item['documento_tipo_id'] == 2870) {
                    if ($dataDocumentoTipo[0]['identificador_negocio'] == 23 &&
                            ($item['documento_tipo_id'] == 2887 || $item['documento_tipo_id'] == 2888) && $item['valor'] == 'Virtual') {
                        //NO MOSTRAR LA DIRECCION
                        $txtDescripcion = $item['descripcion'];
                        $valorItem = $item['valor'];
                    } ELSE {
                        $txtDescripcion = $item['descripcion'];
                        $valorItem = $item['valor'];

                        if ($item['tipo'] == 1) {
                            $valorItem = number_format($valorItem, 2, ".", ",");
                        }

                        if ($item['tipo'] == 3) {
                            $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
                        }

                        $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                        $pdf->MultiCell(110, 0, $valorItem, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');

//                        if ($indice < count($documentoDatoValor) - 1 || $identificadorNegocio == 1) {
//                            if (strlen($valorItem) > 55) {
//                                $pdf->Ln(10);
//                            } else {
//                                $pdf->Ln(6);
//                            }
//                        }
//                        if ($indice == count($documentoDatoValor) - 1) {
                        $pdf->Ln(1);
//                        }
                    }
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        $documentosRelacionImprimir = array();
        $dataDocumentoTipo;
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 22) {//GUIA DE RECEPCION            
            foreach ($dataDocumentoRelacion as $index => $itemR) {
                if ($itemR['identificador_negocio'] == 10) {
                    $itemDR = array('serie_num' => $itemR['serie_numero_original'], 'nombre_doc' => $itemR['documento_tipo_descripcion']);
                    array_push($documentosRelacionImprimir, $itemDR);

                    $relacionOC = MovimientoNegocio::create()->obtenerDocumentosRelacionados($itemR['id']);

                    foreach ($relacionOC as $itemROC) {
                        if ($itemROC['identificador_negocio'] == 21 || $itemROC['identificador_negocio'] == 24) {//21: DUA  24: Commercial Invoice
                            $itemDR = array('serie_num' => $itemROC['serie_numero'], 'nombre_doc' => $itemROC['documento_tipo'], 'serie_numero_original' => $itemROC['serie_numero_original']);
                            array_push($documentosRelacionImprimir, $itemDR);
                        }
                    }
                }
            }
        }

        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) {//GUIA INTERNA DE TRANSFERENCIA                         
            $relacionDoc = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

            foreach ($relacionDoc as $itemDoc) {
                $itemDR = array('serie_num' => $itemDoc['serie_numero'], 'nombre_doc' => $itemDoc['documento_tipo'], 'serie_numero_original' => $itemDoc['serie_numero_original']);
                array_push($documentosRelacionImprimir, $itemDR);
            }
        }

        IF (!ObjectUtil::isEmpty($documentosRelacionImprimir)) {
            $pdf->Ln(5);

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>DOCUMENTOS RELACIONADOS</h4>", 'TB', 1, 0, true, 'C', true);

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentosRelacionImprimir as $indice => $item) {
                $txtDescripcion = $item['nombre_doc'];
                $serieNum = $item['serie_num'];
                $serieNumOriginal = $item['serie_numero_original'];

                $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                $pdf->MultiCell(30, 0, $serieNum, 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T'); // 110

                if (!ObjectUtil::isEmpty($serieNumOriginal)) {
                    $pdf->MultiCell(10, 0, ' | ', 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(70, 0, $serieNumOriginal, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                }

                if ($indice < count($documentosRelacionImprimir) - 1) {
                    if (strlen($serieNum) > 55) {
                        $pdf->Ln(10);
                    } else {
                        $pdf->Ln(6);
                    }
                }

                if ($indice == count($documentosRelacionImprimir) - 1) {
                    $pdf->Ln(1);
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

//        $pdf->SetHeaderData('logo.PNG', PDF_HEADER_LOGO_WIDTH,strtoupper($dataDocumentoTipo[0]['descripcion']),$dataEmpresa[0]['razon_social']."\n".$dataEmpresa[0]['direccion'], array(0,0,0), array(0,0,0));
        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
        //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));

        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);

        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->Cell(0, 0, $descripcionFechaEmision . ": " . $fecha, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['direccion'])) {
            $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] != 23) {
            $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_vencimiento'])) {
            $pdf->Cell(0, 0, "Fecha de vencimiento: " . date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_tentativa'])) {
            $pdf->Cell(0, 0, "Fecha tentativa: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] == 23) {
            $pdf->Cell(0, 0, "Origen: " . $dataDocumento[0]['org_origen_desc'], 0, 1, 'L', 0, '', 0);
            $pdf->Cell(0, 0, "Destino: " . $dataDocumento[0]['org_destino_desc'], 0, 1, 'L', 0, '', 0);
        }

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h4>DETALLE DEL DOCUMENTO</h4>", 0, 1, 0, true, 'L', true);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco
        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="8%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="12%"><b>Código</b></th>
                        <th style="text-align:center;" width="42%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="13%"><b>Unid.</b></th>
                        <th style="text-align:center;" width="12%"><b>P. Unit.</b></th>
                        <th style="text-align:center;" width="13%"><b>P. Total</b></th>
                    </tr>
                '
            ;

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                        . '<td style="text-align:left"  width="12%">' . $esp . $item->bien_codigo . $esp . '</td>'
                        . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                        . '<td style="text-align:center"  width="13%">' . $esp . $item->unidadMedida . $esp . '</td>'
                        . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                        . '</tr>'
                ;
            };

            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
                        . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
                        . '</tr>';
            }
        } else {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="13%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="16%"><b>Código</b></th>
                        <th style="text-align:center;" width="50%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="21%"><b>Unid.</b></th>
                    </tr>
                '
            ;

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                        . '<td style="text-align:rigth"  width="13%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                        . '<td style="text-align:left"  width="16%">' . $esp . $item->bien_codigo . $esp . '</td>'
                        . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                        . '<td style="text-align:center"  width="21%">' . $esp . $item->unidadMedida . $esp . '</td>'
                        . '</tr>'
                ;
            };
        }

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
//            $espaciado = 15;
            $pdf->AddPage();
        }

//        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115      

        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);

            if ($identificadorNegocio == 1) {
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>TERMINOS Y CONDICIONES</h4>", 'TB', 1, 0, true, 'C', true);
            } else {
                ;
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>OTROS DATOS DEL DOCUMENTO</h4>", 'TB', 1, 0, true, 'C', true);
            }

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentoDatoValor as $indice => $item) {
                if ($dataDocumentoTipo[0]['identificador_negocio'] == 23 &&
                        ($item['documento_tipo_id'] == 2887 || $item['documento_tipo_id'] == 2888) && $item['valor'] == 'Virtual') {
                    //NO MOSTRAR LA DIRECCION
                    $txtDescripcion = $item['descripcion'];
                    $valorItem = $item['valor'];
                } ELSE {
                    $txtDescripcion = $item['descripcion'];
                    $valorItem = $item['valor'];

                    if ($item['tipo'] == 1) {
                        $valorItem = number_format($valorItem, 2, ".", ",");
                    }

                    if ($item['tipo'] == 3) {
                        $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
                    }

                    $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(110, 0, $valorItem, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');

                    if ($indice < count($documentoDatoValor) - 1 || $identificadorNegocio == 1) {
                        if (strlen($valorItem) > 55) {
                            $pdf->Ln(10);
                        } else {
                            $pdf->Ln(6);
                        }
                    }

                    if ($indice == count($documentoDatoValor) - 1) {
                        $pdf->Ln(1);
                    }
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        $documentosRelacionImprimir = array();
        $dataDocumentoTipo;
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 22) {//GUIA DE RECEPCION            
            foreach ($dataDocumentoRelacion as $index => $itemR) {
                if ($itemR['identificador_negocio'] == 10) {
                    $itemDR = array('serie_num' => $itemR['serie_numero_original'], 'nombre_doc' => $itemR['documento_tipo_descripcion']);
                    array_push($documentosRelacionImprimir, $itemDR);

                    $relacionOC = MovimientoNegocio::create()->obtenerDocumentosRelacionados($itemR['id']);

                    foreach ($relacionOC as $itemROC) {
                        if ($itemROC['identificador_negocio'] == 21 || $itemROC['identificador_negocio'] == 24) {//21: DUA  24: Commercial Invoice
                            $itemDR = array('serie_num' => $itemROC['serie_numero'], 'nombre_doc' => $itemROC['documento_tipo'], 'serie_numero_original' => $itemROC['serie_numero_original']);
                            array_push($documentosRelacionImprimir, $itemDR);
                        }
                    }
                }
            }
        }

        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) {//GUIA INTERNA DE TRANSFERENCIA                         
            $relacionDoc = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

            foreach ($relacionDoc as $itemDoc) {
                $itemDR = array('serie_num' => $itemDoc['serie_numero'], 'nombre_doc' => $itemDoc['documento_tipo'], 'serie_numero_original' => $itemDoc['serie_numero_original']);
                array_push($documentosRelacionImprimir, $itemDR);
            }
        }

        IF (!ObjectUtil::isEmpty($documentosRelacionImprimir)) {
            $pdf->Ln(5);

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>DOCUMENTOS RELACIONADOS</h4>", 'TB', 1, 0, true, 'C', true);

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentosRelacionImprimir as $indice => $item) {
                $txtDescripcion = $item['nombre_doc'];
                $serieNum = $item['serie_num'];
                $serieNumOriginal = $item['serie_numero_original'];

                $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                $pdf->MultiCell(30, 0, $serieNum, 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T'); // 110

                if (!ObjectUtil::isEmpty($serieNumOriginal)) {
                    $pdf->MultiCell(10, 0, ' | ', 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(70, 0, $serieNumOriginal, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                }

                if ($indice < count($documentosRelacionImprimir) - 1) {
                    if (strlen($serieNum) > 55) {
                        $pdf->Ln(10);
                    } else {
                        $pdf->Ln(6);
                    }
                }

                if ($indice == count($documentosRelacionImprimir) - 1) {
                    $pdf->Ln(1);
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFGuiaRemision($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data   
        /// RONALD AQUí
        $dataDocumentoFactura = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
        $documentoFactura = $dataDocumentoFactura[0]['serie_numero_original'];

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;

        $documentoDatoValor = $data->documentoDatoValor;
        $motivoDeTraslado = $documentoDatoValor[7]['valor'];
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;
        $numeroOrdenCompra = $dataDocumentoRelacion[1]['serie_numero_original'];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(9, 21, PDF_MARGIN_RIGHT);
//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }
        // TITULO  
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(3);
        $titulo = "AQUI PDF" . strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        // $pdf->Ln(5);
        // $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        //$pdf->Ln(5);
        $absisaY = 11;
        $absisaX = 8;
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');

        //$pdf->Cell(0, 9, 'Text-to-be-aligned-right', 0, false, 'R', 0, '', 0, false, 'T', 'M' );    
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {

            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 28);
            $pdf->Cell(53, 0, $fecha, 0, 0, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($documentoFactura)) {//FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 80);
            $pdf->Cell(0, 0, $documentoFactura, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($numeroOrdenCompra)) {//FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $numeroOrdenCompra, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($numeroOrdenCompra)) {//FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $numeroOrdenCompra, 0, 1, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->setY($absisaY + 50);
            $pdf->setX($absisaX + 27); //PUNTODE PARTIDA 
            $pdf->Cell(0, 0, $documentoDatoValor[8]['valor'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($documentoDatoValor[9]['valor'])) {
            $pdf->setY($absisaY + 51);
            $pdf->setX($absisaX + 121); //punto de llegada
            $pdf->Cell(0, 0, $documentoDatoValor[9]['valor'], 0, 1, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {//Fecha de inicio de traslado:
            $pdf->setY($absisaY + 56);
            $pdf->setX($absisaX + 39);
            $fecha_emision = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
            $pdf->Cell(80, 0, $fecha_emision, 0, 0, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {//Nombre o razón social del DESTINATARIO:
            $pdf->setY($absisaY + 54);
            $pdf->setX($absisaX + 148);
            $pdf->Cell(0, 0, $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {//Costo mínimo:
            $pdf->setY($absisaY + 63);
            $pdf->setX($absisaX + 39);

            $valorItem3 = number_format($documentoDatoValor[10]['valor'], 2, ".", ",");
            $pdf->Cell(80, 0, $valorItem3, 0, 0, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->setY($absisaY + 63);
            $pdf->setX($absisaX + 130);
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ' ' . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }


        //TRANPOSRTE 

        $marcaYPlaca = $documentoDatoValor[3]['valor'] . ' ' . $documentoDatoValor[4]['valor'];
        $consInscripcion = $documentoDatoValor[5]['valor'];
        $licenciaConducior = $documentoDatoValor[6]['valor'];
        $porciones = explode("|", $dataDocumento[0]['dirigido_a']); // NOMBRE Y RUC DE LA EMPRESA  DE TRANSPORTES
        $razonSocialEmpresaTransportes = $porciones[0]; // porciÃ³n1
        $rucEmpresaTransportes = $porciones[1]; // porción2
        //$rucEmpresaTransportes=$dataDocumento[0]['codigo_identificacion'];

        if (!ObjectUtil::isEmpty($marcaYPlaca)) {
            $pdf->setY($absisaY + 72);
            $pdf->setX($absisaX + 39);
            $pdf->Cell(0, 0, $marcaYPlaca, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($razonSocialEmpresaTransportes)) {
            $pdf->setY($absisaY + 72);
            $pdf->setX($absisaX + 130);
            $pdf->Cell(0, 0, $razonSocialEmpresaTransportes, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($consInscripcion)) {
            $pdf->setY($absisaY + 77);
            $pdf->setX($absisaX + 46);
            $pdf->Cell(0, 0, $consInscripcion, 0, 1, 'L', 0, '', 0);
        }if (!ObjectUtil::isEmpty($licenciaConducior)) {
            $pdf->setY($absisaY + 82);
            $pdf->setX($absisaX + 47);
            $pdf->Cell(0, 0, $licenciaConducior, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($rucEmpresaTransportes)) {
            $pdf->setY($absisaY + 81);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $rucEmpresaTransportes, 0, 1, 'L', 0, '', 0);
        }



        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }



        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {

            $espacio = 0;
            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 6);
                $pdf->Cell(0, 0, $item->bien_codigo, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 30);
                $pdf->Cell(0, 0, $item->descripcion, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 136);
                $pdf->Cell(0, 0, round($item->cantidad, 2), 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 152);
                $pdf->Cell(0, 0, $item->unidadMedida, 0, 1, 'L', 0, '', 0);

                $espacio = $espacio + 6;
            };

//            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
//                $tabla = $tabla . '<tr>'
//                        . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
//                        . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
//                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
//                        . '</tr>';
//            }
        } else {

            $espacio = 0;
            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }


                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 8);
                $pdf->Cell(0, 0, $item->bien_codigo, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 30);
                $pdf->Cell(0, 0, $item->descripcion, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 136);
                $pdf->Cell(0, 0, round($item->cantidad, 2), 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 152);
                $pdf->Cell(0, 0, $item->unidadMedida, 0, 1, 'L', 0, '', 0);

                $espacio = $espacio + 6;
            };
        }

        if (!ObjectUtil::isEmpty($documentoFactura)) {//FACTURA
            $pdf->setY($absisaY + 250);
            $pdf->setX($absisaX + 58);
            $pdf->Cell(0, 0, $documentoFactura, 0, 1, 'L', 0, '', 0);
        }

        //MOTIVO DE TRASLADO;
        $motivoDeTraslado;
        $pdf->SetFont('helvetica', '', 8);
        switch ($motivoDeTraslado) {
            case 'Venta':
                $pdf->setY($absisaY + 254);
                $pdf->setX($absisaX + 39);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Consignación':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Compra':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 41);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            //case 'Defectuoso':
            case 'Devolución':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Emisor itinerante':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;

            case 'Entre establecimientos de la misma empresa':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Exportación':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'ImportaciÃ³n':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Otros':
                $pdf->setY($absisaY + 267);
                $pdf->setX($absisaX + 23);
                $pdf->Cell(0, 0, $motivoDeTraslado, 0, 1, 'L', 0, '', 0);
                break;
            case 'Para transformaciÃ³n':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Recojo bienes transformados':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Venta sujeta a confirmar':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 41);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Zona Primaria':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
        }


        //fin tabla detalle
        //$tabla = $tabla . '</table>';
        //$pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
//            $espaciado = 15;
            $pdf->AddPage();
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFSolicitudCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


//        foreach ($documentoDatoValor as $indice => $item) {
//            $txtDescripcion = $item['descripcion'];
//            $valorItem = $item['valor'];
//
//            if ($item['tipo'] == 1) {
//                $valorItem = number_format($valorItem, 2, ".", ",");
//            }
//
//            if ($item['tipo'] == 3) {
//                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
//            }
//
//            switch ((int) $item['id']) {
//                case 5465:
//                    $dtdTiempoEntrega = $valorItem;
//                    break;
//                case 5466:
//                    $dtdCondicionPago = $valorItem;
//                    break;
//            }
//        };
//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
//        $pdf->Cell(0, 0, "Moneda: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(6);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Código</b></th>
                    <th style="text-align:center;" width="50%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="11%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="16%"><b>U.M.</b></th>
                </tr>
            '
        ;

        foreach ($detalle as $index => $item) {
            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="16%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $pdf->Ln(175);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacionCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2620:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2621:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 2624:
                    $dtdLugarEntrega = $valorItem;
                    break;
                case 2622:
                    $dtdNumeroCuenta = $valorItem;
                    break;
            }
        };

//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="42%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="6%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="11%"><b>U.M.</b></th>
                    <th style="text-align:center;" width="11%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="12%"><b>Sub Total</b></th>
                </tr>
            '
        ;

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }
            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 145 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 
//        $pdf->Ln(125);        
//        IF(!ObjectUtil::isEmpty($documentoDatoValor)){        
        $pdf->Ln(5);
        //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

        $pdf->Ln(5);
        $pdf->SetFillColor(255, 255, 255);

        $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="31%">' . $esp . 'Hecho por:</td>                       
                          <td width="25%"></td>  
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="31%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');
//        }           

        $pdf->Cell(0, 0, "Fecha de entrega: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Número de cuenta: " . $dtdNumeroCuenta, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Lugar de entrega: " . $dtdLugarEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Condiciones comerciales: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFOrdenCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $usuarioRequerimiento = null;
        foreach ($dataDocumentoRelacion as $index => $itemR) {
            if ($itemR['identificador_negocio'] == 13) {
                $usuarioRequerimiento = $itemR['usuario'];
            }
        }

        if (ObjectUtil::isEmpty($usuarioRequerimiento)) {
            $usuarioRequerimiento = $dataDocumento[0]['usuario'];
        }

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2520:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2521:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 2618:
                    $dtdLugarEntrega = $valorItem;
                    break;
                case 2625:
                    $dtdNumeroCuenta = $valorItem;
                    break;
            }
        };

//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(180, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->Ln(4);
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->writeHTMLCell(0, 0, '', '', $dataDocumento[0]['comentario'], 0, 1, 0, true, 'L', true);
//            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="42%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="6%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="11%"><b>U.M.</b></th>
                    <th style="text-align:center;" width="11%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="12%"><b>Sub Total</b></th>
                </tr>
            '
        ;

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115       
        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="23%">' . $esp . 'Hecho por:</td>
                          <td width="8%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="25%">' . $esp . 'Aprobado por:</td>  
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="23%">' . $esp . $usuarioRequerimiento . $esp . '</td>
                          <td width="8%"></td>
                          <td style="text-align:center"  width="25%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Fecha de entrega: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Número de cuenta: " . $dtdNumeroCuenta, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Lugar de entrega: " . $dtdLugarEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Condiciones comerciales: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        $pdf->Ln(8);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(0, 0, "Notas Importantes ", 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, " 1.- Esta O/C tiene validez hasta la fecha de entrega aqui indicada por la parte del PROVEEDOR, de no cumplir esta quedara ANULADA.", 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, " 2.- La FACTURA ORIGINAL se entregará de manera conjunta con copia SUNAT, 01 copia de GUIA DE REMISION y 01 copia de ORDEN DE COMPRA.", 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFSolicitudCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Phone: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


//        foreach ($documentoDatoValor as $indice => $item) {
//            $txtDescripcion = $item['descripcion'];
//            $valorItem = $item['valor'];
//
//            if ($item['tipo'] == 1) {
//                $valorItem = number_format($valorItem, 2, ".", ",");
//            }
//
//            if ($item['tipo'] == 3) {
//                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
//            }
//
//            switch ((int) $item['id']) {
//                case 5541:
//                    $dtdTiempoEntrega = $valorItem;
//                    break;
//                case 5542:
//                    $dtdCondicionPago = $valorItem;
//                    break;
//            }
//        };
//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE REQUEST " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
//        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

//        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Code</b></th>
                    <th style="text-align:center;" width="50%"><b>Description</b></th>
                    <th style="text-align:center;" width="11%"><b>QTY </b></th>
                    <th style="text-align:center;" width="16%"><b>Unit</b></th>
                </tr>
            '
        ;

        foreach ($detalle as $index => $item) {
            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
//                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:left"  width="16%">' . $esp . 'Pcs' . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $pdf->Ln(175);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacionCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Phone: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2612:
                    $dtdFormaPago = $valorItem;
                    break;
                case 2627:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2628:
                    $dtdCondicionComercial = $valorItem;
                    break;
            }
        };

//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE REQUIREMENT " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
//        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

//        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Code</b></th>
                    <th style="text-align:center;" width="50%"><b>Description</b></th>
                    <th style="text-align:center;" width="11%"><b>QTY </b></th>
                    <th style="text-align:center;" width="16%"><b>Unit</b></th>
                </tr>
            '
        ;

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
//                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:left"  width="16%">' . $esp . 'Pcs' . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 151 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115 
//        $pdf->Ln(135);        
        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="40%">' . $esp . 'Made by:</td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td style="text-align:center;"  width="40%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Deadtime: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, "Payment form: " . $dtdFormaPago, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Delivery time: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, "Place of delivery: ".$dtdLugarEntrega, 0, 1, 'L', 0, '', 0);   
        $pdf->Cell(0, 0, "Incoterms: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFOrdenCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $usuarioRequerimiento = null;
        foreach ($dataDocumentoRelacion as $index => $itemR) {
            if ($itemR['identificador_negocio'] == 14) {
                $usuarioRequerimiento = $itemR['usuario'];
            }
        }

        if (ObjectUtil::isEmpty($usuarioRequerimiento)) {
            $usuarioRequerimiento = $dataDocumento[0]['usuario'];
        }

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Phone: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2613:
                    $dtdFormaPago = $valorItem;
                    break;
                case 2614:
                    $dtdCodigoPedido = $valorItem;
                    break;
                case 2596:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2597:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 5537:
                    $dtdLugarEntrega = $valorItem;
                    break;
            }
        };

//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE ORDER " . $serieDocumento . $dataDocumento[0]['numero'];
//        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
//        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Order: " . $dtdCodigoPedido, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $espacioNombrePersona = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $lon = strlen($dataDocumento[0]['nombre']);
            if (strlen($dataDocumento[0]['nombre']) >= 55) {
                $pdf->Ln(3);
            }
            $espacioNombrePersona = 3;
        }

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

//        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Code</b></th>
                    <th style="text-align:center;" width="42%"><b>Description</b></th>
                    <th style="text-align:center;" width="6%"><b>QTY </b></th>
                    <th style="text-align:center;" width="11%"><b>Unit</b></th>
                    <th style="text-align:center;" width="11%"><b>Price Unit</b></th>
                    <th style="text-align:center;" width="12%"><b>Total Price</b></th>
                </tr>
            '
        ;

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39 || strlen($item->bien_codigo) > 11) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
//                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:left"  width="11%">' . $esp . 'Pcs' . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 151 - ($cont * 4.1 + $espacioComentario + $espacioNombrePersona);
//        if ($espaciado < 15) {
//            $espaciado = 15;
//        }

        if ($espaciado < 5) {
            $pdf->AddPage();
        } else {
            $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115 
        }
//        $pdf->Ln(135);        
        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td style="text-align:rigth" width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="23%">' . $esp . 'Made by:</td>
                          <td width="8%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="25%">' . $esp . 'Approved by:</td>  
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td style="text-align:rigth" width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="23%">' . $esp . $usuarioRequerimiento . $esp . '</td>
                          <td width="8%"></td>
                          <td style="text-align:center"  width="25%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="8%"></td>
                          <td width="14%"  style="text-align:rigth">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:center" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Deadtime: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, "Payment form: " . $dtdFormaPago, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Delivery time: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, "Place of delivery: ".$dtdLugarEntrega, 0, 1, 'L', 0, '', 0);   
        $pdf->Cell(0, 0, "Incoterms: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacion($documentoId, $comentario, $tipoSalidaPDF, $url, $data) {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('l', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social']
//                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
                "Telfs: 044-728609\n" .
                "E-mail: gcardenas@bhdrillingtools.com;ddominguez@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
                "RUC: " . $dataEmpresa[0]['ruc']
        ;
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 1850:
                    $dtdModoEntrega = $valorItem;
                    break;
//                case 2615:
//                    $dtdVigencia = $valorItem;
//                    break;
                case 2880:
                    $dtdAtencion = $valorItem;
                    break;
                case 2616:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2617:
                    $dtdNuestraRef = $valorItem;
                    break;
            }
        };

//        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $titulo2 = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " <br> " . $serieDocumento . $dataDocumento[0]['numero'];
        $pdf->Ln(-20);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo2 . "</h2>", 0, 1, 0, true, 'R', true);
        $pdf->Ln(17);
//        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
//        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
//        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
//        $pdf->Cell(0, 0, "Moneda: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);        
        $pdf->MultiCell(120, 0, "Nuestra Ref: " . $dtdNuestraRef, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(90, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        $pdf->Cell(0, 0, "Atención: " . $dtdAtencion, 0, 1, 'L', 0, '', 0);

        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Estimados señores:', 0, 1, 'L', 0, '', 0);
        $pdf->Ln(1);
        $pdf->Cell(0, 0, 'De acuerdo a vuestro requerimiento nos es grato presentarles nuestra cotización de los siguientes Productos y/o Servicios:', 0, 1, 'L', 0, '', 0);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="36%"><b>Descripción</b></th>                    
                    <th style="text-align:center;" width="8%"><b>Cantidad</b></th>
                    <th style="text-align:center;" width="8%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="10%"><b>Sub Total</b></th>
                    <th style="text-align:center;" width="20%"><b>Comentario</b></th>
                </tr>
            '
        ;

        foreach ($detalle as $index => $item) {
            $dataStock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($item->bienId, $item->unidadMedidaId);
            $stock = $dataStock[0]['stock'];
            $disponible = $stock;
            if ($stock >= $item->cantidad) {
                $disponible = $item->cantidad;
            }

            $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                    . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="36%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="8%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="10%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="20%">' . $esp . $item->movimientoBienComentario . $esp . '</td>'
                    . '</tr>'
            ;
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        IF (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>CONDICIONES COMERCIALES(*)</h4>", 0, 1, 0, true, 'L', true); //'TB'->borde:0


            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);

            $cuentaNumero = $dataDocumento[0]['cuenta_numero'];
            $cuentaData = CuentaNegocio::create()->obtenerCuentaXId($dataDocumento[0]['cuenta_id']);
            $valorElaboradoPor = explode(" ", $dataDocumento[0]['perfil_usuario']);
            $valorElaboradoPor = $dataDocumento[0]['usuario'] . ' | ' . $valorElaboradoPor[0];

            $dtdVigencia = date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y');

            $diasCredito = Util::diasTranscurridos($dataDocumento[0]['fecha_emision'], $dataDocumento[0]['fecha_vencimiento']);

            $dtdFormaPago = $dataDocumento[0]['tipo_pago_descripcion'];

            $formaPagoCompl = '';
            if ($dataDocumento[0]['tipo_pago'] == 2) {
                $formaPagoCompl = ' a ' . $diasCredito . ' días';
            }

            $dtdFormaPagoDesc = $dtdFormaPago . $formaPagoCompl;

            if ($dataDocumento[0]['moneda_id'] != 2) {
                $tabla = '<table cellspacing="0" cellpadding="1" border="1"  style="text-align:left">
                        <tr>
                          <td>' . $esp . 'Modo de entrega</td>
                          <td>' . $esp . $dtdModoEntrega . $esp . '</td>
                          <td>' . $esp . 'Fecha vencimiento</td>
                          <td>' . $esp . $dtdVigencia . $esp . '</td>
                          <td>' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Forma de pago</td>
                          <td>' . $esp . $dtdFormaPagoDesc . $esp . '</td>
                          <td>' . $esp . 'Marca</td>
                          <td>' . $esp . 'BH' . $esp . '</td>
                          <td>' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td rowspan="2">' . $esp . 'Observaciones</td>
                          <td colspan="3">' . $esp . 'Cuenta: ' . ((!ObjectUtil::isEmpty($cuentaData)) ?
                        $cuentaData[0]['descripcion'] . ': ' . $cuentaData[0]['numero'] . '<br>' .
                        $esp . $esp . $esp . $esp . $esp . $esp . $esp . '&nbsp; CCI: ' . $cuentaData[0]['cci'] : '') .
                        '</td>
                          <td rowspan="2">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td rowspan="2" style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td colspan="3">' . $esp . 'T/C: ' . number_format($dataDocumento[0]['cambio_personalizado'], 3) . $esp . ' (El T/C es referencial)</td>
                        </tr>
                        <tr>
                          <td colspan="4">' . $esp . '(*) Las operaciones comerciales al crédito estarán sujetas a evaluación y revisión del Area de Créditos y Cobranzas<br>
                                          (*) Disponibilidad: Disponibilidad sujeta a ventas previas
                          </td>
                          <td>' . $esp . 'TOTAL S/.</td>
                          <td style="text-align:rigth;">' . $esp . number_format($dataDocumento[0]['cambio_personalizado'] * $dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                    </table>';
            } else {
                $tabla = '<table cellspacing="0" cellpadding="1" border="1"  style="text-align:left">
                        <tr>
                          <td>' . $esp . 'Modo de entrega</td>
                          <td>' . $esp . $dtdModoEntrega . $esp . '</td>
                          <td>' . $esp . 'Fecha vencimiento</td>
                          <td>' . $esp . $dtdVigencia . $esp . '</td>
                          <td>' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Forma de pago</td>
                          <td>' . $esp . $dtdFormaPago . $esp . '</td>
                          <td>' . $esp . 'Marca</td>
                          <td>' . $esp . 'BH' . $esp . '</td>
                          <td>' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Observaciones</td>
                          <td colspan="3">' . $esp . 'Cuenta: ' . $cuentaNumero . $esp . '</td>
                          <td>' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td colspan="6">' . $esp . '(*) Las operaciones comerciales al crédito estarán sujetas a evaluación y revisión del Area de Créditos y Cobranzas<br>
                                          (*) Disponibilidad: Disponibilidad sujeta a ventas previas
                          </td>
                        </tr>
                    </table>';
            }

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

//            $pdf->Ln(5);
//            $pdf->Cell(0, 0,'Sin otro particular, agradeciendo su gentil atención, quedamos a la espera de vuestra pronta respuesta.', 0, 1, 'L', 0, '', 0);        
//            $pdf->Ln(1);
//            $pdf->Cell(0, 0,'Atentamente.', 0, 1, 'L', 0, '', 0);        
//            
//            //telefonos
//            $pdf->Ln(20);
//            $borde=array('R' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
////            $pdf->MultiCell(145, 0, 'TELEFAX', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
////            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
////            $pdf->MultiCell(30, 0, '044 262811', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');            
////            $pdf->Ln();
//            $pdf->MultiCell(145, 0, 'FIJO', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(30, 0, '044 209454', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
//            $pdf->Ln();
//            $pdf->MultiCell(145, 0, 'RPC', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(30, 0, '977192256', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
//            $pdf->Ln();
//            $pdf->MultiCell(145, 0, 'RPM', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(30, 0, '*445213', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
//            $pdf->Ln();
//            $pdf->MultiCell(145, 0, 'NEXTEL', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(30, 0, '836*3196', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
//            $pdf->Ln();
//            $pdf->MultiCell(145, 0, 'CELULAR', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
//            $pdf->MultiCell(30, 0, '965076817', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
//            $pdf->Ln();
        //agregar pagina
//        $pdf->AddPage();
        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function enviarCorreoConPrecio($correo, $documentoId, $comentarioDocumento, $usuarioId, $plantillaId) {

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId); //antes 3

        $documento = Documento::create()->obtenerDocumentoDatos($documentoId);

        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $documento[0]['movimiento_id']);

        //dibujar cuerpo y detalle

        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        $direccionEmpresa = '<tr><td style="text-align: left; padding: 0 55px 10px; font-size: 14px; line-height: 1.5; width: 80%">Documento generado en la empresa '
                . $data->direccionEmpresa[0]['razon_social']
                . ' ubicada en '
                . $data->direccionEmpresa[0]['direccion']
                . '</td></tr>'
        ;

        //fin dibujo
        //envio correo
        $usuarioId;
        $correo;
        $nombreDocumentoTipo;
        $dataDocumento;
        $dataDetalle;
        $comentarioDocumento;
        $direccionEmpresa;

        //logica correo:             
//        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(3);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentarioDocumento, $cuerpo);
        $cuerpo = str_replace("[|direccion_empresa|]", $direccionEmpresa, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId);

        $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        return 1;
    }

    public function enviarMovimientoEmailCorreoMasPDF($correo, $documentoId, $comentario, $usuarioId, $plantillaId) {

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];
        $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
        $dataDocumento = $data->dataDocumento;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];
        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);

        $hoy = date("Y_m_d_H_i_s");
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/documento_' . $hoy . '_' . $usuarioId . '.pdf';

        //crear PDF
        $this->generarDocumentoPDF($documentoId, $comentario, 'F', $url, $data);

        //---------------------GENERACION DE LA PARTE TEXTUAL DEL CORREO-------------------------------------------

        $documento = Documento::create()->obtenerDocumentoDatos($documentoId);
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $documento[0]['movimiento_id']);

        //dibujar cuerpo y detalle

        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];
            $monedaDescripcionHTML = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>Moneda: </b>' . $data->dataDocumento[0]['moneda_descripcion'] . '</td></tr>';

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
            $dataDocumento = $dataDocumento . $monedaDescripcionHTML;
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) {//Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) {//Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        //fin dibujo    
        //logica correo:             
        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentario, $cuerpo);
        $cuerpo = str_replace("[|direccion_empresa|]", $direccionEmpresa, $cuerpo);

        //-----------------------------------------------------------------        
        //envio de email        
        $nombreArchivo = $dataDocumentoTipo[0]['descripcion'] . ".pdf";

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId, $url, $nombreArchivo);

        if (!ObjectUtil::isEmpty($res[0]['id'])) {
            $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        }

        return $res;
    }

    public function obtenerMovimientoTipoColumnaLista($opcionId) {
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        return Movimiento::create()->obtenerMovimientoTipoColumnaListaXMovimientoTipoId($movimientoTipoId);
    }

    public function enviarCorreosMovimiento($usuarioId, $txtCorreo, $correosSeleccionados, $respuestaCorreo, $comentario) {
        $plantilla = $respuestaCorreo['dataPlantilla'];
        $accionEnvio = $plantilla[0]['accion_funcion'];
        $documentoId = $respuestaCorreo['documentoId'];

        $correos = '';
        if (!ObjectUtil::isEmpty($correosSeleccionados)) {
            foreach ($correosSeleccionados as $email) {
                $correos = $correos . $email . ';';
            }
        }
        if (!ObjectUtil::isEmpty($txtCorreo)) {
            $correos = $correos . $txtCorreo;
        }

        $plantillaId = $plantilla[0]["email_plantilla_id"];
        $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
    }

    public function obtenerEmailsXAccion($opcionId, $accionEnvio, $documentoId) {
        //obtener email de plantilla            
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
        $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

        $correos = '';
        if (!ObjectUtil::isEmpty($correosPlantilla)) {
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
        }
        $resultado->correo = $correos;
        $resultado->plantilla = $plantilla;

        return $resultado;
    }

    public function enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId) {
        switch ($accionEnvio) {
            case "enviarPDF":
                return $this->enviarMovimientoEmailPDF($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
            case "enviarCorreo":
                return $this->enviarCorreoConPrecio($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
            case "enviarCorreoPDF":
                return $this->enviarMovimientoEmailCorreoMasPDF($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
        }
    }

    public function obtenerMovimientoEntradaSalidaXFechaXBienId($fechaEmision, $bienId) {
        return Movimiento::create()->obtenerMovimientoEntradaSalidaXFechaXBienId($fechaEmision, $bienId);
    }

    public function getUserEmailByUserId($id) {
        return Movimiento::create()->getUserEmailByUserId($id);
    }

    public function verificarDocumentoObligatorioExiste($actualId) {
        return Movimiento::create()->verificarDocumentoObligatorioExiste($actualId);
    }

    public function verificarDocumentoEsObligatorioXOpcionID($opcionId) {
        return Movimiento::create()->verificarDocumentoEsObligatorioXOpcionID($opcionId);
    }

    public function guardarDocumentoAtencionSolicitud($origenId, $destinoId, $cantidadAtendida, $usuarioId) {
        return MovimientoBien::create()->guardarDocumentoAtencionSolicitud($origenId, $destinoId, $cantidadAtendida, $usuarioId);
    }

    public function obtenerEstadoNegocioXMovimientoId($movimientoId) {
        return Movimiento::create()->obtenerEstadoNegocioXMovimientoId($movimientoId);
    }

    public function generarBienUnicoXDocumentoId($documentoId, $usuarioId) {
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        if ($dataDocumento[0]['estado'] != 1) {
            throw new WarningException("Documento anulado, no se puede generar los productos únicos");
        }

        $dataBienUnico = BienUnicoNegocio::create()->obtenerMovimientoBienUnicoXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($dataBienUnico)) {
            throw new WarningException("Ya se generó los productos únicos, refresque la página.");
        }

        $movimientoId = $dataDocumento[0]['movimiento_id'];
        $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        $fechaEmision = $dataDocumento[0]['fecha_emision'];

        $anio = date_format((date_create($fechaEmision)), 'Y');
        $mes = date_format((date_create($fechaEmision)), 'm');

        foreach ($dataMovBien as $item) {
            //CODIGO PRODUCTO SIN ESPACIOS (20) + AÑO (4) + MES (2) + CORRELATIVO DE (7)
            //sin concatenacion de ceros, sin BH, + periodo, 5 digitos

            $codigoBien = $item['bien_codigo'];
            $codigoBien = str_replace(' ', '', $codigoBien);
            $codigoBien = str_replace('BH', '', $codigoBien);

            if (strlen($codigoBien) > 20) {
                $codigoBien = substr($codigoBien, 0, 20);
            }

            $codBienUnico = $codigoBien . $anio . $mes;

            $dataCorrelativo = BienUnico::create()->bienUnicoObternerUltimoCodigoCorrelativo($codBienUnico);

            $correlativo = 0;
            if (!ObjectUtil::isEmpty($dataCorrelativo)) {
                $correlativo = $dataCorrelativo[0]['correlativo'] * 1;
            }

            for ($i = 0; $i < $item['cantidad']; $i++) {

                $correlativo++;
                $correlativoCadena = str_pad($correlativo, 5, "0", STR_PAD_LEFT);

                $codigoBU = $codigoBien . $anio . $mes . $correlativoCadena;

                //insertar bien unico

                $resBU = BienUnico::create()->insertarBienUnico($item['bien_id'], $codigoBU, $usuarioId);

                if ($resBU[0]['vout_estado'] == 1) {
                    $resMBU = BienUnico::create()->insertarMovimientoBienUnico($item['movimiento_bien_id'], $resBU[0]['vout_id'], 1, $usuarioId);
                } else {
                    throw new WarningException("Error al guardar bien unico");
                }
            }
        }

        $r = DocumentoNegocio::create()->actualizarEstadoQRXDocumentoId($documentoId, 2);

        return $resBU;
    }

    function anularBienUnicoXDocumentoId($documentoId) {
        return BienUnico::create()->anularBienUnicoXDocumentoId($documentoId);
    }

    function obtenerBienUnicoConfiguracionInicial($documentoId) {
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $resultado->dataBienUnicoDisponible = BienUnicoNegocio::create()->obtenerBienUnicoDisponibleXDocumentoId($documentoId);
        $resultado->dataDocumento = $dataDocumento;
        $resultado->dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
        $resultado->dataMovimientoBienUnico = BienUnicoNegocio::create()->obtenerMovimientoBienUnicoXDocumentoId($documentoId);

        return $resultado;
    }

    function guardarBienUnicoDetalle($listaBienUnicoDetalle, $listaBienUnicoDetalleEliminado, $usuarioId, $opcionId, $estadoQR) {

        if (!ObjectUtil::isEmpty($listaBienUnicoDetalleEliminado)) {
            foreach ($listaBienUnicoDetalleEliminado as $itemEliminar) {
                $res = BienUnico::create()->eliminarMovimientoBienUnico($itemEliminar);

                if ($res[0]['vout_exito'] == 0) {
                    throw new WarningException($res[0]['vout_mensaje']);
                }
            }
        }

        $resDocumento = DocumentoNegocio::create()->obtenerDocumentoIdXMovimientoBienId($listaBienUnicoDetalle[0]['movimiento_bien_id']);
        $r = DocumentoNegocio::create()->actualizarEstadoQRXDocumentoId($resDocumento[0]['documento_id'], $estadoQR);

        foreach ($listaBienUnicoDetalle as $item) {
            $bienUnicoId = $item['bien_unico_id'];
            $movimientoBienId = $item['movimiento_bien_id'];

            $res = BienUnico::create()->guardarMovimientoBienUnico($bienUnicoId, $movimientoBienId, $usuarioId);

            if ($res[0]['vout_exito'] == 0) {
                throw new WarningException($res[0]['vout_mensaje']);
            }
        }

        $resultado->respuesta = $res;

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $resultado->indicador = $movimientoTipo[0]["indicador"];

        return $resultado;
    }

    function obtenerDataEstadoNegocioPago() {
        return Tabla::create()->obtenerXPadreId(56);
    }

    private function guardarAnticipos($resDataDocumento, $anticiposAAplicar, $usuarioId, $camposDinamicos, $monedaId) {
        if (ObjectUtil::isEmpty($anticiposAAplicar["data"]))
            return;

        $documentoId = $resDataDocumento->documentoId;
        $proveedorId = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 5);
        $fecha = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 9);
        $totalDocumento = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 14) * 1;
//        $actividadEfectivo = $anticiposAAplicar["actividadId"];
        $empresaId = $anticiposAAplicar["empresaId"];

        $retencion = 1;
        $monedaPago = $monedaId;
        $dolares = ($monedaId == 4) ? 1 : 0;

        $documentoAPagar = array(array(documentoId => $documentoId,
                tipoDocumento => '',
                numero => '',
                serie => '',
                pendiente => (float) $totalDocumento,
                total => (float) $totalDocumento,
                dolares => $dolares
            )
        );
        $documentoPagoConDocumento = array();
        $totalPagos = 0;
        foreach ($anticiposAAplicar["data"] as $anticipo) {
            array_push($documentoPagoConDocumento, array(
                documentoId => $anticipo["documentoId"],
                tipoDocumento => '',
                tipoDocumentoId => '',
                numero => '',
                serie => '',
                pendiente => (float) $anticipo["pendiente"] * 1,
                total => (float) $anticipo["pendiente"] * 1,
                monto => (float) $anticipo["pendiente"] * 1,
                dolares => $dolares
                    )
            );
        }

        // Como todo se hace en la misma moneda, setearemos el tc en 1 
        $tipoCambio = 1;
        $pago = PagoNegocio::create()->registrarPago($proveedorId, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, 0, $tipoCambio, $monedaPago, $retencion, $empresaId, null);
    }

    public function relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId) {
        $earRelacion = NULL;
        //VALIDAR QUE NO RELACIONEN UN DOCUMENTO YA RELACIONADO
        $dataRel = DocumentoNegocio::create()->obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoIdOrigen, $documentoIdARelacionar);

        if (!ObjectUtil::isEmpty($dataRel)) {
            throw new WarningException('Documento a relacionar duplicado');
        }
        $banderaComprobanteCompraNacional = NULL;
        $camposDinamicos = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoIdOrigen);
        if (!ObjectUtil::isEmpty($camposDinamicos)) {
            $banderaComprobanteCompraNacional = Util::filtrarArrayPorColumna($camposDinamicos, array('tipo', 'codigo'), array(DocumentoTipoNegocio::DATO_LISTA, '13'), 'valor_dato_listar');
            if ($banderaComprobanteCompraNacional == "1") {
                $earRelacion = 1;
            }
        }

        $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoIdOrigen, $documentoIdARelacionar, 1, 1, $usuarioId, $earRelacion);
        if ($banderaComprobanteCompraNacional == "1") {
            $respuesta = MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoIdOrigen, $usuarioId);
            $resActualizarCostos = MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoIdOrigen, $usuarioId);
            if (ObjectUtil::isEmpty($resActualizarCostos) || $resActualizarCostos[0]['vout_exito'] != 1) {
                throw new WarningException('Error al intentar generar costo de la compra.');
            }
        }
//                        throw new WarningException('Error al intentar relacionar el documento');

        return $respuestaRelacion;
    }

    public function obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision) {
        $unidad = UnidadNegocio::create()->obtenerActivasXBien($bienId);
        $respuesta = new stdClass();
        $respuesta->unidad_medida = $unidad;

        if ($unidadMedidaId == 0) {
            $unidadMedidaId = $unidad[0]["id"];
        }

        $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId, $precioTipoId, $monedaId);
        if (ObjectUtil::isEmpty($dataPrecio)) {
            $precio = 0;
        } else {
            $precio = $dataPrecio[0]["precio"];
        }
        $respuesta->precioCompra = NULL;
        $respuesta->precio = $precio;
        return $respuesta;
    }

    public function obtenerStockActual($bienId, $indice, $organizadorId, $unidadMedidaId, $organizadorDestinoId = null) {
        if (!ObjectUtil::isEmpty($organizadorId)) {
            //LA TRANSFERENCIA INTERNA TIENE ORGANIZADOR
            $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
        } else {
            $stock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
        }

        $cantidadMinima = BienNegocio::create()->obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId);
        $stock[0]['indice'] = $indice;
        $stock[0]['cantidad_minima'] = $cantidadMinima[0]['cantidad_minima'];

        return $stock;
    }

    public function obtenerStockParaProductosDeCopia($organizadorDefectoId, $detalle, $organizadorDestinoId = null) {
        $dataStock = array();
        foreach ($detalle as $item) {
            //TIENE QUE SER SIMILAR AL METODO DEL CONTROLADOR: obtenerStockActual
            $bienId = $item['bienId'];
            $unidadMedidaId = $item['unidadMedidaId'];
            $organizadorId = $item['organizadorId'];
            if (!ObjectUtil::isEmpty($organizadorDefectoId) && $organizadorDefectoId != 0) {
                $organizadorId = $organizadorDefectoId;
            }
            $stock = MovimientoNegocio::create()->obtenerStockActual($bienId, $item['index'], $organizadorId, $unidadMedidaId, $organizadorDestinoId);

            array_push($dataStock, $stock);
        }

        return $dataStock;
    }

    public function obtenerDocumentoRelacionadoTipoRecepcion($documentoId) {
        return Movimiento::create()->obtenerDocumentoRelacionadoTipoRecepcion($documentoId);
    }

    public function guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion) {
        return DocumentoNegocio::create()->guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion);
    }

    public function guardarEmailEnvioPendientesXReposicion($dataPAtencion, $asuntoCorreo, $plantillaId, $descripcionCorreo, $tituloCorreo, $mostrarDocumento = 1) {
        if (!ObjectUtil::isEmpty($dataPAtencion)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
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
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Traslado</th>";
            if ($mostrarDocumento == 1) {
//                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            }
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Origen</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Producto</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Pendiente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>U. Medida</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPAtencion as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['fecha_traslado'];
                if ($mostrarDocumento == 1) {
//                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                }
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['org_origen'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['bien_descripcion'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['cantidad'] * 1;
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($item['cantidad'] - $item['cant_rep']) * 1;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['unidad_medidad'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = $descripcionCorreo;

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

    public function generarDocumentoImpresionPDF($documentoId, $url, $data) {
        //Import the PhpJasperLibrary
        require_once __DIR__ . '/../../reporteJasper/PhpJasperLibrary/tcpdf/tcpdf.php';
        require_once __DIR__ . '/../../reporteJasper/PhpJasperLibrary/PHPJasperXML.inc.php';

        $dataDocumento = $data->dataDocumento;
        $identificadorNegocio = $dataDocumento[0]['identificador_negocio'];

        switch ((int) $identificadorNegocio) {
            case 3://boleta
                return $this->generarDocumentoImpresionPDFBoleta($documentoId, $url, $data);
            case 5://nota de credito
                return $this->generarDocumentoImpresionPDFNotaCredito($documentoId, $url, $data);

//            default:
//                return $this->generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
        }
    }

    public function generarDocumentoImpresionPDFBoleta($documentoId, $url, $data) {
        //CONEXION
//        $server="localhost";
//        $db="bhdt_20170901";
//        $user="root";
//        $pass="local";
        //DATOS
        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
//        $detalle = $data->detalle;
        $documentoDetalle = $data->documentoDetalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $fechaEmision = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_emision']);

        $dia = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd');
//        $mesNombre = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
        $mes = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'm');
        $anio = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'y'); //Y año completo
        //TOTAL EN LETRAS
        $totalLetras = Util::normaliza($data->totalEnTexto);

        //FIN DATOS
        //REPORTE
        $urlJrxml = __DIR__ . '/../../reporteJasper/almacen/boleta.jrxml';
        $xml = simplexml_load_file($urlJrxml);
        $PHPJasperXML = new PHPJasperXML();

        //DETALLE: cantidad,simbolo,bien_descripcion,valor_monetario,sub_total        
        $PHPJasperXML->arrayParameter = array(
//            "vin_movimiento_id"=>$dataDocumento[0]['movimiento_id'],
            "serie_numero" => $serieDocumento . $dataDocumento[0]['numero'],
            "nombre" => $dataDocumento[0]['nombre'],
            "direccion" => $dataDocumento[0]['direccion'],
            "documento" => $dataDocumento[0]['codigo_identificacion'],
            "fecha_dia" => $dia,
//            "fecha_mes" => $mesNombre[$mes*1-1],
            "fecha_mes" => $mes,
            "fecha_anio" => $anio,
            "total_letras" => strtoupper($totalLetras),
            "total" => $dataDocumento[0]['total'],
            "moneda_simbolo" => $dataDocumento[0]['moneda_simbolo'],
            "fecha_pie" => $fechaEmision,
        );

        $PHPJasperXML->xml_dismantle($xml);
//        $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
        $PHPJasperXML->transferirDataSql($documentoDetalle); //SIN CONEXION
//        $PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file

        $PHPJasperXML->outpage('F', $url);
        //FIN REPORTE

        return $titulo;
    }

    public function generarDocumentoImpresionPDFNotaCredito($documentoId, $url, $data) {
        //DATOS
        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
//        $detalle = $data->detalle;
        $documentoDetalle = $data->documentoDetalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $fechaEmision = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_emision']);
        $fechaVencimiento = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_vencimiento']);

        $dia = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd');
        $mesNombre = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
        $mes = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'm');
        $anio = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'Y'); //Y año completo
        //TOTAL EN LETRAS
        $totalLetras = Util::normaliza($data->totalEnTexto);

        //DOCUMENTO TIPO DATO
        $serieNumero_doc_rel = '';
        $tipo_doc_rel = '';
        $fechaEmision_doc_rel = '';
        if (!ObjectUtil::isEmpty($dataDocumentoRelacion)) {
            foreach ($dataDocumentoRelacion as $item) {
                if ($item['identificador_negocio'] == 3) {
                    $serieNumero_doc_rel = $item['serie_numero_original'];
                    $fechaEmision_doc_rel = DateUtil::formatearBDACadena($item['fecha_emision']);
                    $tipo_doc_rel = 'Boleta';
                }
                if ($item['identificador_negocio'] == 4) {
                    $serieNumero_doc_rel = $item['serie_numero_original'];
                    $fechaEmision_doc_rel = DateUtil::formatearBDACadena($item['fecha_emision']);
                    $tipo_doc_rel = 'Factura';
                }
            }
            $serieNumero_doc_rel = explode(" - ", $serieNumero_doc_rel);
        }


        $anulacion = '';
        $bonificacion = '';
        $descuento = '';
        $devoluciones = '';
        $otros = '';
        foreach ($documentoDatoValor as $item) {
            switch ($item["documento_tipo_id"] * 1) {
                case 2885:
                    $serieNota = $item["valor"];
                    break;
                case 2886:
                    $numeroNota = $item["valor"];
                    break;
                case 609:
                    if ($item["valor"] == "Anulación") {
                        $anulacion = 'X';
                    }
                    if ($item["valor"] == "Bonificaciones") {
                        $bonificacion = 'X';
                    }
                    if ($item["valor"] == "Descuentos") {
                        $descuento = 'X';
                    }
                    if ($item["valor"] == "Devoluciones") {
                        $devoluciones = 'X';
                    }
                    if ($item["valor"] == "Otros") {
                        $otros = 'X';
                    }
                    break;
                default:
                    break;
            }
        }

        //REPORTE
        $urlJrxml = __DIR__ . '/../../reporteJasper/almacen/nota_credito.jrxml';
        $xml = simplexml_load_file($urlJrxml);
        $PHPJasperXML = new PHPJasperXML();

        //DETALLE: cantidad,simbolo,bien_descripcion,valor_monetario,sub_total        
        $PHPJasperXML->arrayParameter = array(
//            "vin_movimiento_id"=>$dataDocumento[0]['movimiento_id'],
//            "serie_numero" => $serieDocumento . $dataDocumento[0]['numero'],
            "serie_numero" => $serieNota . '-' . $numeroNota,
            "nombre" => $dataDocumento[0]['nombre'],
            "total" => $dataDocumento[0]['total'],
            "documento" => $dataDocumento[0]['codigo_identificacion'],
            "total_letras" => 'SON: ' . strtoupper($totalLetras),
            "fecha_emision" => $dia . ' de ' . $mesNombre[$mes * 1 - 1] . ' del ' . $anio,
            "sub_total" => $dataDocumento[0]['subtotal'],
            "igv" => $dataDocumento[0]['igv'],
            "igv_porcentaje" => 18,
            "fecha_emision_doc_rel" => $fechaEmision_doc_rel,
            "tipo_doc_rel" => $tipo_doc_rel,
//            "serie_rel" => $serieNumero_doc_rel[0].'-',
            "serie_doc_rel" => $serieNumero_doc_rel[0] . '-' . $serieNumero_doc_rel[1],
            "moneda_simbolo" => $dataDocumento[0]['moneda_simbolo'],
            "mot_anulacion" => $anulacion,
            "mot_bonificacion" => $bonificacion,
            "mot_descuento" => $descuento,
            "mot_devolucion" => $devoluciones,
            "mot_otros" => $otros,
        );

        $PHPJasperXML->xml_dismantle($xml);
//        $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
        $PHPJasperXML->transferirDataSql($documentoDetalle); //SIN CONEXION
//        $PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file

        $PHPJasperXML->outpage('F', $url);
        //FIN REPORTE

        return $titulo;
    }

    //EDICION
    public function validarDocumentoEdicion($documentoId) {
        $respuesta->exito = 1;

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        //ORDEN DE VENTA
        if ($dataDocumento[0]['identificador_negocio'] == 2) {
            $atencionEstado = ProgramacionAtencionNegocio::create()->obtenerDocumentoAtencionEstadoLogico($documentoId);

            if ($atencionEstado[0]['estado_atencion'] == 4) {//ATENCION COMPLETA
                $respuesta->exito = 0;
                $respuesta->mensaje = 'No se puede editar la orden de venta porque fue atendida completamente';
                return $respuesta;
            }
        }

        // Aprobación por contabilidad
        $respuestaDocumentoEstado = DocumentoNegocio::create()->obtenerDocumentoDocumentoEstadoXDocumentoId($documentoId);
        if ($respuestaDocumentoEstado[0]['documento_estado_id'] == 3) {
            $respuesta->exito = 0;
            $respuesta->mensaje = 'No se puede editar el documento por que ya fue aprobado por área de contabilidad.';
            return $respuesta;
        }


        //INVOICE COMMERCIAL
        /*
          if ($dataDocumento[0]['identificador_negocio'] == 24) {
          $respuestaDua = DocumentoNegocio::create()->obtenerDUAXInvoiceComercial($documentoId);
          if (!ObjectUtil::isEmpty($respuestaDua[0]['id'])) {
          $respuesta->exito = 0;
          $respuesta->mensaje = 'No se puede editar el invoice commercial porque ya existe una DUA relacionada.';
          return $respuesta;
          }
          }
         */
        return $respuesta;
    }

    function obtenerDocumentoRelacionEdicion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados) {

        $respuesta = new ObjectUtil();

        $documentoACopiar = DocumentoNegocio::create()->obtenerDataDocumentoACopiarEdicion($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($documentoACopiar)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->documentoACopiar = $documentoACopiar;
//        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
        $respuesta->detalleDocumento = $this->obtenerDocumentoRelacionDetalleEdicion($movimientoId, $documentoId, $opcionId, $documentoRelacionados);

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
        $respuesta->dataDistribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
        //FIN OBTENER DATA UNIDAD MEDIDA

        return $respuesta;
    }

    function obtenerDocumentoRelacionDetalleEdicion($movimientoId, $documentoId, $opcionId, $documentoRelacionados) {
        //solo va a ver un documento a copiar/editar
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        foreach ($documentoDetalle as $index => $detalle) {
            //obtener datos de: movimiento_bien_detalle
            $resMovimientoBienDetalle = MovimientoBien::create()->obtenerMovimientoBienDetalleXMovimientoBienId($detalle['movimiento_bien_id']);

            $documentoDetalle[$index]['movimiento_bien_detalle'] = $resMovimientoBienDetalle;
        }

        return $documentoDetalle;
    }

    public function guardarXAccionEnvioEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar = null, $periodoId = null, $percepcion = null, $detalleDistribucion = NULL, $contOperacionTipoId = NULL, $distribucionObligatoria = NULL, $afectoAImpuesto = NULL) {

//        $resEdicion = $this->guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $detalle,$listaDetalleEliminar, $checkIgv, $accionEnvio, $comentario);
        $resEdicion = $this->guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $detalleDistribucion, $contOperacionTipoId, $distribucionObligatoria, $afectoAImpuesto);

        //ACTUALIZAMOS IMPORTE DE PROGRAMACION DE PAGO
        if ($tipoPago == 2) {
            $resPP = Pago::create()->actualizarPagoProgramacionImporteXDocumentoId($documentoId);
        }

        if ($accionEnvio == 'enviar') {
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }

        if ($accionEnvio == 'enviarEImprimir') {
            $respuesta->dataImprimir = $this->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        } else {

            //obtener email de plantilla            
            $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
            $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
            $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

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
            $respuesta->dataEnvioCorreo = $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }
    }

    public function guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $detalleDistribucion = NULL, $contOperacionTipoId = NULL, $distribucionObligatoria = NULL, $afectoAImpuesto = NULL) {

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);

        $respuestaDoc = MovimientoNegocio::create()->guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario, $periodoId, $tipoPago, $monedaId, $usuarioId, $contOperacionTipoId, $afectoAImpuesto);

        //ELIMINAR MOVIMIENTO BIEN 
        if (!ObjectUtil::isEmpty($listaDetalleEliminar)) {
            foreach ($listaDetalleEliminar as $itemId) {
                $resElimina = MovimientoBien::create()->actualizarEstadoXId($itemId, 2);
            }
        }

        //Insertamos el detalle
        foreach ($detalle as $item) {
            $valido = $this->validarItemDetalleMovimientoEdicion($item, $opcionId, $dataDocumentoTipo, $camposDinamicos, $monedaId);

            //REGISTRAR LA EDICION DEL DETALLE
            if (!ObjectUtil::isEmpty($item['movimientoBienId'])) {
                $movimientoBien = MovimientoBien::create()->editar($item['movimientoBienId'], $dataDocumento[0]['movimiento_id'], $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $item["precio"], 1, $usuarioId, $item["precioTipoId"], $item["utilidad"], $item["utilidadPorcentaje"], $checkIgv, $item["adValorem"]);
            } else {
                $movimientoBien = MovimientoBien::create()->guardar($dataDocumento[0]['movimiento_id'], $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $item["precio"], 1, $usuarioId, $item["precioTipoId"], $item["utilidad"], $item["utilidadPorcentaje"], $checkIgv, $item["adValorem"], $item["comentarioDetalle"], $item["bienActivoFijoId"]);
            }

            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }

            //guardar el detalle del detalle del movimiento en movimiento_bien_detalle
//            if (!ObjectUtil::isEmpty($item["detalle"])) {
//                foreach ($item["detalle"] as $valor) {
//                        if ($valor['columnaCodigo'] == 18) {
//                            $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor['valorDet']);
//                            //EDITA SI YA EXISTE LA FECHA DE VENCIMIENTO SINO REGISTRA
//                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleEditarFecha($movimientoBienId, $valor['columnaCodigo'], $fechaVencimiento, $usuarioId);
//                        }
//                }
//            }
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $operacionTipoMovimiento = ContOperacionTipoNegocio::create()->obtenerContOperacionTipoXMovimientoTipoId($movimientoTipo[0]['id']);
        if (!ObjectUtil::isEmpty($operacionTipoMovimiento)) {
            if (array_search($contOperacionTipoId, array_column($operacionTipoMovimiento, 'id')) === false) {
                throw new WarningException("La operación tipo seleccionada no pertenece al movimiento tipo.");
            }
            if ($distribucionObligatoria == 1) {
                $respuestaValidarDistribucion = ContDistribucionContableNegocio::create()->validarDistribucionContable($documentoId, $detalleDistribucion, $contOperacionTipoId);
            }
            $respuestaGuardarDistribucion = ContDistribucionContableNegocio::create()->guardarContDistribucionContable($documentoId, $contOperacionTipoId, $detalleDistribucion, $usuarioId);
        }
        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");

        $banderaComprobanteCompraNacional = NULL;
        $camposDinamicosGuardados = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        if (!ObjectUtil::isEmpty($camposDinamicosGuardados)) {
            $banderaComprobanteCompraNacional = Util::filtrarArrayPorColumna($camposDinamicosGuardados, array('tipo', 'codigo'), array(DocumentoTipoNegocio::DATO_LISTA, '13'), 'valor_dato_listar');
        }

        if ($documentoTipoId == $this->dtDuaId || $banderaComprobanteCompraNacional == "1") {
            MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoId, $usuarioId);
            MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId);
        } else {
            MovimientoDuaNegocio::create()->eliminarCostoCifXDocumentoId($documentoId);
        }

        return $documentoId;
    }

    private function validarItemDetalleMovimientoEdicion($item, $opcionId, $dataDocumentoTipo = null, $camposDinamicos, $monedaId) {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        // validaciones
        if ($item["bienId"] == NULL) {
            throw new WarningException("No se especificó un valor válido para Bien. ");
        }

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

        if (ObjectUtil::isEmpty($item["movimientoBienId"])) {
            MovimientoNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $item["detalle"]);
        } else {
            //SI ES ENTRADA O SALIDA VALIDA STOCK
            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA || $movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_ENTRADA) {
                //ACTUALIZO TEMPORALMENTE EL MOVIMIENTO BIEN A INACTIVO
                $resEstInac = MovimientoBien::create()->actualizarEstadoXId($item["movimientoBienId"], 0);

                MovimientoNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $item["detalle"], true);

                //ACTUALIZO MOVIMIENTO BIEN A ACTIVO
                $resEstAc = MovimientoBien::create()->actualizarEstadoXId($item["movimientoBienId"], 1);
            }
        }

        //validacion el precio unitario tiene que ser mayor al precio de compra.
        $precioCompra = 0;
        $validarPrecios = true;
//        if ($item["precio"] * 1 == 0) {
//            $validarPrecios = false;
//        }

        if (!ObjectUtil::isEmpty($item["precioCompra"])) {
            $precioCompra = $item["precioCompra"];
        }
        if (!ObjectUtil::isEmpty($dataDocumentoTipo) && $dataDocumentoTipo[0]["validacion"] == 1 && $validarPrecios) {
            $precioUnitario = $item["precio"];
            if ($precioUnitario <= $precioCompra) {
                throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor al precio de compra.<br>"
                                . "Producto: " . $item['bienDesc'] . '<br>'
                                . "Precio compra: " . number_format($precioCompra, 2, ".", ",") . '<br>'
                                . "Precio unitario: " . number_format($precioUnitario, 2, ".", ",") . '<br>'
                );
            }
        }
        if ($dataDocumentoTipo[0]["tipo"] == 1 && $validarPrecios) {
            $precioUnitario = $item["precio"];

            $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($item["bienId"], $item["unidadMedidaId"], $item["precioTipoId"], $monedaId);
            if (!ObjectUtil::isEmpty($dataPrecio)) {
                if ($checkIgv == 1) {
                    $precioVenta = $dataPrecio[0]["incluye_igv"];
                } else {
                    $precioVenta = $dataPrecio[0]["precio"];
                }
                $precioMinimo = $precioVenta * 1 - $dataPrecio[0]["descuento"] * 1;
                $precioMinimo = round($precioMinimo, 2);

                if ($precioUnitario < $precioMinimo) {
                    throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor o igual al precio mínimo (descuento)"
                                    . "<br> Producto: " . $item["bienDesc"]
                                    . "<br> Precio mínimo: " . $precioMinimo);
                }
            }
        }
    }

    public function validarMovimientoBienEdicionEliminar($documentoId, $item) {
        $respuesta->exito = 1;

        if (!ObjectUtil::isEmpty($item['movimientoBienId'])) {
            $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

            //ORDEN DE VENTA
            if ($dataDocumento[0]['identificador_negocio'] == 2) {
                $cantidadAtendida = ProgramacionAtencionNegocio::create()->obtenerCantidadAtendidaXMovimientoBienId($item['movimientoBienId']);

                if (!ObjectUtil::isEmpty($cantidadAtendida[0]['cantidad_atendida']) && $cantidadAtendida[0]['cantidad_atendida'] > 0) {
                    //HAY ATENCION DEL MOVIMIENTO BIEN
                    $respuesta->exito = 0;
                    $respuesta->mensaje = 'La cantidad atendida del producto es ' . ($cantidadAtendida[0]['cantidad_atendida'] * 1) . ' por ello no se puede eliminar';
                }
            }
        }


        return $respuesta;
    }

    public function obtenerDireccionOrganizador($organizadorId) {
        return Organizador::create()->obtenerDireccionOrganizador($organizadorId);
    }

    function consultarTicket($documentoId) {

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ticket = Movimiento::create()->obtenerNroTicketEFACT($documentoId);
        if (!ObjectUtil::isEmpty($ticket)) {
            $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
            $comprobanteElectronico->docNroTicket = $ticket[0]['efact_ticket'];
            $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
            $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
            $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
            $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

            $comprobanteElectronico = (array) $comprobanteElectronico;

            $client = self::conexionEFAC();

            try {
                $resultado = $client->procesarConsultaTicket($comprobanteElectronico)->procesarConsultaTicketResult;
                return $resultado;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return 'Este documento aun no ha sido procesado por el script por favor espere 30 minutos y vuelva a consultar.';
        }
    }

    function verificarAnulacionSunat() {
        $usuarioId = 1;
        $documentos = Movimiento::create()->obtenerDocumentosPorVerificarAnulacionSunat();

        $documentosCorreo = array();
        foreach ($documentos as $index => $item) {
            $resConsulta = $this->consultarTicket($item['documento_id']);
//            $resConsulta = 'ERROR PROBANDO';
            if (strpos($resConsulta, '[Cod: IMA01]') === false) {
                //AGREGAR AL ARRAY PARA ENVIAR CORREO
                $item['error_sunat'] = $resConsulta;
                array_push($documentosCorreo, $item);

                //REVERTIR ESTADO
                $documentoEstadoId = 1;
                $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($item['documento_id'], $documentoEstadoId, $usuarioId);

                $estado = 1;
                $resDoc = DocumentoNegocio::create()->actualizarEstadoXId($item['documento_id'], $estado);
            } else {
                //CORRECTO: ACTUALIZAR ESTADO ANULACION SUNAT
                $resEst = Documento::create()->actualizarEstadoEfactAnulacionValido($item['documento_id'], 1);
            }
        }

        //ENVIAR CORREO
        $resEmailTodos = '';
        if (!ObjectUtil::isEmpty($documentosCorreo)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($documentosCorreo);

            //A EFACT
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(23);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $descripcion = 'Se restablecieron los siguientes documentos, porque la verificación de la anulación fue incorrecta. Anule los documentos por el sistema.';

            $descripcion = 'El proceso de baja de algún documento no se pudo concretar, según política de SUNAT.<br>
                            Por tal motivo le recomendamos emitir nota de crédito.<br>
                            Asi mismo se restablecieron a un estado activo, los siguientes documentos:';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosRevertidosDeAnulacionSunat($itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        return $resEmailTodos;
    }

    function obtenerDocumentosGrupoXUsuario($documentosCorreo) {
        $documentosGrupo = array();

        $documentoItem = array();
        foreach ($documentosCorreo as $key => $item) {
            if (!$this->verificarUsuarioExisteEnArray($documentosGrupo, $item['usuario'])) {
                $documentoItem = array('usuario' => $item['usuario'], 'usuario_email' => $item['usuario_email'], 'documentos' => array());

                array_push($documentosGrupo, $documentoItem);
            }
        }

        foreach ($documentosGrupo as $indexGrupo => $itemGrupo) {
            foreach ($documentosCorreo as $index => $item) {
                if ($itemGrupo['usuario'] == $item['usuario']) {
                    array_push($documentosGrupo[$indexGrupo]['documentos'], $item);
                }
            }
        }

        return $documentosGrupo;
    }

    function verificarUsuarioExisteEnArray($documentosGrupo, $usuario) {
        $bandera = false;

        foreach ($documentosGrupo as $key => $item) {
            if ($item['usuario'] == $usuario) {
                $bandera = true;
            }
        }

        return $bandera;
    }

    function enviarCorreoDocumentosRevertidosDeAnulacionSunat($documentos, $plantilla, $correos, $descripcion) {

        if (!ObjectUtil::isEmpty($documentos)) {
//            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(21);
//            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);
//
//            $correos = '';
//            foreach ($correosPlantilla as $email) {
//                $correos = $correos . $email . ';';
//            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Motivo anulación</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Anulación</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Error SUNAT</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($documentos as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['total'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['motivo_anulacion'];
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['anulacion_fecha']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['error_sunat'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
//            $descripcion = 'Se restablecieron los siguientes documentos, porque la verificación de la anulación fue incorrecta. Anule los documentos por el sistema.';
            //logica correo:             
            $asunto = '[EFACT] ' . $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'DOCUMENTOS RESTABLECIDOS PENDIENTES DE ANULACION', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|documento_detalle|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Documentos restablecidos. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'];
        } else {
            return '';
        }
    }

    public function generarDocumentosElectronicosPendientes() {
        $contadorMaximoRegistro = Configuraciones::EFACT_WS_CONTADOR_MAXIMO;

        $usuarioId = 1;
        $docPendientes = Documento::create()->obtenerDocumentosPendientesDeGeneracionEfact($contadorMaximoRegistro);

        $docCorrectos = array();
        $docErrorControlado = array();
        $docErrorDesconocido = array();

        foreach ($docPendientes as $index => $item) {
            $resValido = $this->consultarDocumentoSUNAT($item['documento_id']);

            //DES COMENTAR PARA PRUEBAS EN BETA:
//            $resValido->tipoRespuesta = 0;
//            $resValido->mensaje = 'Documento no existe';

            if ($resValido->tipoRespuesta == 1) {
                //GENERO SOLO PDF
//                $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($item['documento_id'], $item['identificador_negocio'], 1, 2);                
                $resEstadoRegistro = DocumentoNegocio::create()->actualizarEfactEstadoRegistro($item['documento_id'], DocumentoTipoNegocio::EFACT_CORRECTO);
            } else {
                //GENERAR DOCUMENTO ELECTRONICO
                $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($item['documento_id'], $item['identificador_negocio'], 0, 2);

                $respDocElectronico = $resEfact->respDocElectronico;

                if ($respDocElectronico->tipoMensaje == 1) {
                    $item['url_PDF'] = $respDocElectronico->urlPDF;
                    $item['nombre_PDF'] = $respDocElectronico->nombrePDF;

                    array_push($docCorrectos, $item);
                }
                if ($respDocElectronico->tipoMensaje == 2) {
                    $item['efact_mensaje_respuesta'] = $respDocElectronico->mensaje;

                    array_push($docErrorControlado, $item);

                    $documentoEstadoId = 5; //ESTADO ELIMINADO
                    $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($item['documento_id'], $documentoEstadoId, $usuarioId);

                    $estado = 2; //DOCUMENTO ELIMINADO
                    $resDoc = DocumentoNegocio::create()->actualizarEstadoXId($item['documento_id'], $estado);
                }
                if ($respDocElectronico->tipoMensaje == 3) {
                    if (($item['efact_ws_contador'] * 1 + 1) == $contadorMaximoRegistro) {
                        $item['efact_mensaje_respuesta'] = trim($respDocElectronico->mensaje);
                        array_push($docErrorDesconocido, $item);
                    }

                    $resActContador = Documento::create()->actualizarEfactContadorRegistro($item['documento_id']);
                }
            }
        }

        //ENVIAR CORREO
        $resEmailTodos = '';
        if (!ObjectUtil::isEmpty($docPendientes)) {
            //A EFACT
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(24);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
        }

        //ENVIAR CORRECTOS
        if (!ObjectUtil::isEmpty($docCorrectos)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docCorrectos);
            $descripcion = 'Los siguientes documentos, fueron registrados en SUNAT correctamente';
            $tituloEmail = 'DOCUMENTOS GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos generados correctamente - SUNAT';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_CORRECTO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        //ENVIAR CON ERROR CONTROLADO
        if (!ObjectUtil::isEmpty($docErrorControlado)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docErrorControlado);
            $descripcion = 'Los siguientes documentos, no se pudieron registrar en SUNAT, devolvieron un error controlado, se eliminaron del sistema, tiene que volver a registrar.';
            $tituloEmail = 'DOCUMENTOS NO GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos no generados correctamente - SUNAT - Eliminados del sistema';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        //ENVIAR CON ERROR DESCONOCIDO
        if (!ObjectUtil::isEmpty($docErrorDesconocido)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docErrorDesconocido);
            $descripcion = 'Los siguientes documentos, no se pudieron registrar en SUNAT, devolvieron un error, revise los documentos. Se intentó registrar ' . $contadorMaximoRegistro . ' veces';
            $tituloEmail = 'DOCUMENTOS NO GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos no generados correctamente - SUNAT';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }


        return $resEmailTodos;
    }

    function enviarCorreoDocumentosGeneradosSunat($tipoRespuesta, $documentos, $plantilla, $correos, $descripcion, $tituloEmail, $asuntoEmail) {

        if (!ObjectUtil::isEmpty($documentos)) {
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Creación</th>";

            if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_CORRECTO) {
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PDF</th>";
            }
            if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO ||
                    $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO) {
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Respuesta error</th>";
            }

            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($documentos as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['total'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_creacion']);

                if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_CORRECTO) {
                    $urlPdfSgi = Configuraciones::url_base() . 'pdf2.php?url_pdf=' . $item['url_PDF'] . '&nombre_pdf=' . $item['nombre_PDF'];
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . '<a href="' . $urlPdfSgi . '" target="_blank">Descargar</a>';
                }
                if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO ||
                        $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO) {
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['efact_mensaje_respuesta'];
                }

                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';

            $asunto = $asuntoEmail;
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", $tituloEmail, $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|documento_detalle|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Respuesta tipo: ' . $tipoRespuesta . '. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'];
        } else {
            return '';
        }
    }

    public function consultarDocumentoSUNAT($documentoId) {

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
//        $comprobanteElectronico->emisorNroDocumento = '20531807520';
        $comprobanteElectronico->docTipoDocumento = $documento[0]['sunat_tipo_doc_rel'];
        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
//        $comprobanteElectronico->usuarioSunatSOL = 'MARESTA2';
//        $comprobanteElectronico->claveSunatSOL = 'Maresta2018';

        $comprobanteElectronico = (array) $comprobanteElectronico;

        $client = self::conexionEFAC();

        try {
            $resultado = $client->procesarConsultaDocumento($comprobanteElectronico)->procesarConsultaDocumentoResult;
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        if (strpos($resultado, '[Cod: IMA01]') === false) {
            $tipoRespuesta = 0;
        } else {
            $tipoRespuesta = 1;
        }

        $respuesta->tipoRespuesta = $tipoRespuesta;
        $respuesta->mensaje = $resultado;

        return $respuesta;
    }

    public function conexionEFAC() {
        try {
            $client = new SoapClient(Configuraciones::EFACT_URL);
        } catch (Exception $e) {
//            $resultado = $e->getMessage();
            throw new WarningException("Imposible conectarse con el servicio facturador.");
        }
        return $client;
    }

    public function autogenerarNCTipo13XFacturaId($documentoId, $usuarioId) {

        $docConfiguracionNC = DocumentoTipoNegocio::create()->obtenerDocumentoTipoNC(5, 2);
        $opcionNCId = $docConfiguracionNC[0]['opcion_id'];
        $documentoTipoNCId = $docConfiguracionNC[0]['documento_tipo_id'];
        $movimientoTipoNCId = $docConfiguracionNC[0]['movimiento_tipo_id'];
        $documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoNCId, $usuarioId);
        $documentoARelacionarNC[0]['documentoId'] = $documentoId;

        if (!ObjectUtil::isEmpty($documento_tipo_conf)) {
            for ($i = 0; $i < count($documento_tipo_conf); $i++) {
//                SERIE
                if ($documento_tipo_conf[$i]['tipo'] == 7) {
                    $idenSerie = $documento_tipo_conf[$i]['id'];
                    $valorSerie = $documento_tipo_conf[$i]['cadena_defecto'];
                }
//                NUMERO
                if ($documento_tipo_conf[$i]['tipo'] == 8) {
                    $idenNumero = $documento_tipo_conf[$i]['id'];
                    $valorNumero = $documento_tipo_conf[$i]['data'];
                }
                //                CLIENTE
                if ($documento_tipo_conf[$i]['tipo'] == 5) {
                    $idenPersona = $documento_tipo_conf[$i]['id'];
                    $descPersona = $documento_tipo_conf[$i]['descripcion'];
                }
//                FECHA EMISION
                if ($documento_tipo_conf[$i]['tipo'] == 9) {
                    $idenFecha = $documento_tipo_conf[$i]['id'];
                    $valorFecha = $documento_tipo_conf[$i]['data'];
                }
//                FECHA VENCIMIENTO
                if ($documento_tipo_conf[$i]['tipo'] == 10) {
                    $idenFechaVenc = $documento_tipo_conf[$i]['id'];
                    $valorFechaVenc = $documento_tipo_conf[$i]['data'];
                }
//                RETENCION
                if ($documento_tipo_conf[$i]['tipo'] == 4 && $documento_tipo_conf[$i]['codigo'] == 10) {
                    $idenRentencion = $documento_tipo_conf[$i]['id'];
                    $descRetencion = $documento_tipo_conf[$i]['descripcion'];
                }
//                MOTIVO EMISION
                if ($documento_tipo_conf[$i]['tipo'] == 4 && isEmpty($documento_tipo_conf[$i]['codigo'])) {
                    $idenMotivo = $documento_tipo_conf[$i]['id'];
                    $descMotivo = $documento_tipo_conf[$i]['descripcion'];
                }
////                PROYECTO
//                if ($documento_tipo_conf[$i]['tipo'] == 2) {
//                    $idenProyecto = $documento_tipo_conf[$i]['id'];
//                    $descProyecto = $documento_tipo_conf[$i]['descripcion'];
//                }
////                DETRACCION
//                if ($documento_tipo_conf[$i]['tipo'] == 36) {
//                    $idenDetraccion = $documento_tipo_conf[$i]['id'];
//                    $descDetraccion = $documento_tipo_conf[$i]['descripcion'];
//                }
//                Importe
                if ($documento_tipo_conf[$i]['tipo'] == 14) {
                    $idenImporte = $documento_tipo_conf[$i]['id'];
                    $descImporte = $documento_tipo_conf[$i]['descripcion'];
                }
//                Sub total
                if ($documento_tipo_conf[$i]['tipo'] == 16) {
                    $idenSubTotal = $documento_tipo_conf[$i]['id'];
                    $descSubTotal = $documento_tipo_conf[$i]['descripcion'];
                }
//                IGV
                if ($documento_tipo_conf[$i]['tipo'] == 15) {
                    $idenIGV = $documento_tipo_conf[$i]['id'];
                    $descIGV = $documento_tipo_conf[$i]['descripcion'];
                }
////                Forma de pago 
//                if ($documento_tipo_conf[$i]['tipo'] == 12) {
//                    $idenFormaPago = $documento_tipo_conf[$i]['id'];
//                    $descFormaPago = $documento_tipo_conf[$i]['descripcion'];
//                }
            }
        }
//        obtenemos los datos de la factura para generar la NC
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        $valorPersona = $documento[0]["persona_id"];
        $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $monedaId = $documento[0]["moneda_id"];
        $tipoPago = $documento[0]["tipo_pago"];
        $empresaId = $documento[0]["empresa_id"];
        foreach ($dataDocumento as $index => $item) {
            switch ($item['documento_tipo_id'] * 1) {
                case 2906:
                    if ($item['valor'] == 378) { //Si aplica retencion
                        $retencionFact = 409;
                    } else {
                        $retencionFact = 408;
                    }
                    break;
//                case 2935:
//                    $proyectoFact = $item['valor'];
//                    break;
//                case 2931:
//                    $detraccionFact = $item['valor'];
//                    break;
//                case 2929:
//                    $prodDuplicadoFact = $item['valor'];
//                    break;
            }
        }

        //INICIO DE CABECERA
        $k = 0;
        $camposDinamicosNC[$k]['id'] = $idenSerie;
        $camposDinamicosNC[$k]['tipo'] = 7;
        $camposDinamicosNC[$k]['descripcion'] = "Serie";
        $camposDinamicosNC[$k]['valor'] = $valorSerie;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenNumero;
        $camposDinamicosNC[$k]['tipo'] = 8;
        $camposDinamicosNC[$k]['descripcion'] = "Número";
        $camposDinamicosNC[$k]['valor'] = $valorNumero;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenFecha;
        $camposDinamicosNC[$k]['tipo'] = 9;
        $camposDinamicosNC[$k]['descripcion'] = "Fecha";
        $camposDinamicosNC[$k]['valor'] = date("d/m/Y");
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenFechaVenc;
        $camposDinamicosNC[$k]['tipo'] = 10;
        $camposDinamicosNC[$k]['descripcion'] = "Fecha de vencimiento";
        $camposDinamicosNC[$k]['valor'] = date("d/m/Y", strtotime(date("Ymd")));
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenPersona;
        $camposDinamicosNC[$k]['tipo'] = 5;
        $camposDinamicosNC[$k]['descripcion'] = $descPersona;
        $camposDinamicosNC[$k]['valor'] = $valorPersona;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenMotivo;
        $camposDinamicosNC[$k]['tipo'] = 4;
        $camposDinamicosNC[$k]['descripcion'] = $descMotivo;
        $camposDinamicosNC[$k]['valor'] = 410; //tipo 13
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenRentencion;
        $camposDinamicosNC[$k]['tipo'] = 4;
        $camposDinamicosNC[$k]['descripcion'] = $descRetencion;
        $camposDinamicosNC[$k]['valor'] = $retencionFact;
//        $k++;
//        $camposDinamicosNC[$k]['id'] = $idenDetraccion;
//        $camposDinamicosNC[$k]['tipo'] = 36;
//        $camposDinamicosNC[$k]['descripcion'] = $descDetraccion;
//        $camposDinamicosNC[$k]['valor'] = isEmpty($detraccionFact)? '': $detraccionFact;
//        $k++;
//        $camposDinamicosNC[$k]['id'] = $idenProyecto;
//        $camposDinamicosNC[$k]['tipo'] = 2;
//        $camposDinamicosNC[$k]['descripcion'] = $descProyecto;
//        $camposDinamicosNC[$k]['valor'] = isEmpty($proyectoFact)? '':$proyectoFact;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenImporte;
        $camposDinamicosNC[$k]['tipo'] = 14;
        $camposDinamicosNC[$k]['descripcion'] = $descImporte;
        $camposDinamicosNC[$k]['valor'] = 0;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenSubTotal;
        $camposDinamicosNC[$k]['tipo'] = 16;
        $camposDinamicosNC[$k]['descripcion'] = $descSubTotal;
        $camposDinamicosNC[$k]['valor'] = 0;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenIGV;
        $camposDinamicosNC[$k]['tipo'] = 15;
        $camposDinamicosNC[$k]['descripcion'] = $descIGV;
        $camposDinamicosNC[$k]['valor'] = 0;
//        $k++;
//        $camposDinamicosNC[$k]['id'] = $idenFormaPago;
//        $camposDinamicosNC[$k]['tipo'] = 12;
//        $camposDinamicosNC[$k]['descripcion'] = $descFormaPago;
//        $k++;
//        $camposDinamicosNC[$k]['id'] = 2930;
//        $camposDinamicosNC[$k]['tipo'] = 32;
//        $camposDinamicosNC[$k]['descripcion'] = "Producto duplicado";
//        $camposDinamicosNC[$k]['valor'] = $prodDuplicadoFact;

        $comentario = 'Nota de credito generada para regularizar fechas de pago';

        $anio = date("Y");
        $mes = date("m");
        $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes);
        $periodoId = $dataPeriodo[0]['id'];
        $valorCheck = 1;
        $accionEnvio = "enviar";
        //FIN DE CABECERA
        //Datos Extras
        $datosExtras['afecto_detraccion_retencion'] = $documento[0]["afecto_detraccion_retencion"];
        $datosExtras['porcentaje_afecto'] = $documento[0]["porcentaje_afecto"];
        $datosExtras['monto_detraccion_retencion'] = $documento[0]["monto_detraccion_retencion"];

        //Programacion de pago
        $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
        $j = 0;
        $listaPagoProgramacion[0][0] = date("d/m/Y", strtotime(date("Ymd") . "+ 1 days"));
        $j++;
        $listaPagoProgramacion[0][1] = $formaPagoDetalle[0]['importe'];
        $j++;
        $listaPagoProgramacion[0][2] = $formaPagoDetalle[0]['dias'];
        $j++;
        $listaPagoProgramacion[0][3] = $formaPagoDetalle[0]['porcentaje'];
        $j++;
        $listaPagoProgramacion[0][4] = '';

        // DETALLE
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {

            $items[$index]['organizadorDesc'] = '';
            $items[$index]['unidadMedidaId'] = $fila['unidad_medida_id'];
            $items[$index]['index'] = $index;
            $items[$index]['stockBien'] = 0;
            $items[$index]['bienDesc'] = $fila['bien_descripcion'];
            $items[$index]['cantidad'] = $fila['cantidad'];
            $items[$index]['subTotal'] = $fila['cantidad'];
            $items[$index]['precio'] = $fila['valor_monetario'];
            $items[$index]['precioTipoId'] = $fila['precio_tipo_id'];
            $items[$index]['bienId'] = $fila['bien_id'];
            $items[$index]['unidadMedidaDesc'] = $fila['unidad_medida_descripcion'];
        }
        $detalle = $items;
        //guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $atiende = null, $periodoId = null, $percepcion = null, $datosExtras = null) 
        $docNCId = $this->guardarXAccionEnvio($opcionNCId, $usuarioId, $documentoTipoNCId, $camposDinamicosNC, $detalle, $documentoARelacionarNC, $valorCheck, $comentario, 0, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, null, $periodoId, null, $datosExtras);
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($docNCId->documentoId);
        if ($dataEmpresa[0]['efactura'] == 1) {
            $resEfact = $this->generarDocumentoElectronico($docNCId->documentoId, 5);
            $respuesta->resEfact = $resEfact;
            $mensaje = $resEfact->respDocElectronico->mensaje;
            return $mensaje;
        }
    }

    private function estilosExcel() {

        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
        );

        $this->estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTextoInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
    }

}
