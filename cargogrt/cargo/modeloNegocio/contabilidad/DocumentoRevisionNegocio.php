<?php

require_once __DIR__ . '/../almacen/MovimientoNegocio.php';

class DocumentoRevisionNegocio extends MovimientoNegocio {

    /**
     * 
     * @return DocumentoRevisionNegocio
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
        return Documento::create()->obtenerDocumentosRevisionContabilidadXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago, $serieDoc, $numeroDoc);
    }

    public function aprobarRechazarVistoBueno($documentoId, $accion, $razonRechazo, $usuarioId) {
        if ($accion == 'AP') {
            $respuestaRegistroVoucher = ContVoucherNegocio::create()->registrarContVoucherRegistroCompras($documentoId, $usuarioId);

            $respuestaAprobarRechazarVistoBueno = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, NULL, $usuarioId, $accion, $razonRechazo);
            if ($respuestaAprobarRechazarVistoBueno[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaAprobarRechazarVistoBueno[0]['vout_mensaje']);
            }
            return $respuestaRegistroVoucher;
        } elseif ($accion == 'RE') {
            self::enviarCorreoRechazo($documentoId, $razonRechazo, $usuarioId);

            $respuestaAprobarRechazarVistoBueno = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, NULL, $usuarioId, $accion, $razonRechazo);
            if ($respuestaAprobarRechazarVistoBueno[0]['vout_exito'] != 1) {
                throw new WarningException($respuestaAprobarRechazarVistoBueno[0]['vout_mensaje']);
            }
            return $respuestaAprobarRechazarVistoBueno;
        }
    }

    public function enviarCorreoRechazo($documentoId, $razonRechazo, $usuarioId) {
        $dataDocumento = DocumentoNegocio::create()->obtenerXId($documentoId, NULL);

        $serieDocumento = '';

        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $mensaje = '<br><div style="font-size: 12px;">';

        // OBTENER PERSONA
        $dataDocumentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($dataDocumento[0]['documento_tipo_id']);

        $descripcionPersona = Util::filtrarArrayPorColumna($dataDocumentoTipoDato, 'tipo', 5, 'descripcion');
        if (!ObjectUtil::isEmpty($descripcionPersona)) {
            $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">- ' . $descripcionPersona . ' :</span>
                        </b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
            $mensaje .= $dataDocumento[0]['nombre'] . '</span></p>';
        }
        // CÓDIGO DE IDENTIFICADOR DE LA PERSONA
        if (!ObjectUtil::isEmpty($dataDocumento[0]['persona_documento_tipo'])) {
            $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">- ' . $dataDocumento[0]['persona_documento_tipo'] . ' :</span>
                        </b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
            $mensaje .= $dataDocumento[0]['codigo_identificacion'] . '</span></p>';
        }
        // SERIE Y CORRELATIVO ORIGINAL.
        $dataDatoValor = DocumentoDatoValorNegocio::create()->obtenerXIdDocumento($documentoId);
        $serieNumeroOriginal = "";
        $serieOriginal = Util::filtrarArrayPorColumna($dataDatoValor, array('tipo', 'codigo'), array('2', '6'), 'valor');
        $correlativoOriginal = Util::filtrarArrayPorColumna($dataDatoValor, array('tipo', 'codigo'), array('2', '7'), 'valor');
        if (ObjectUtil::isEmpty($serieOriginal)) {
            $serieOriginal = Util::filtrarArrayPorColumna($dataDatoValor, array('tipo', 'codigo'), array('2', '5'))[0]['valor'];
            $correlativoOriginal = Util::filtrarArrayPorColumna($dataDatoValor, array('tipo', 'codigo'), array('2', '5'))[1]['valor'];
        }
        if (ObjectUtil::isEmpty($serieOriginal) && !ObjectUtil::isEmpty($correlativoOriginal)) {
            $serieNumeroOriginal = $correlativoOriginal;
        } else {
            $serieNumeroOriginal = $serieOriginal . (!ObjectUtil::isEmpty($correlativoOriginal) ? "-" . $correlativoOriginal : "");
        }
        if (!ObjectUtil::isEmpty($serieNumeroOriginal)) {
            $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">- ' . strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . ' :</span>
                        </b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
            $mensaje .= $serieNumeroOriginal . '</span></p>';
        }
        // OBTENER LA FECHA DE EMISIÓN
        $descripcionFecha = Util::filtrarArrayPorColumna($dataDocumentoTipoDato, 'tipo', 9, 'descripcion');
        if (!ObjectUtil::isEmpty($descripcionFecha)) {
            $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">- ' . $descripcionFecha . ' :</span>
                        </b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
            $mensaje .= DateUtil::formatearFechaBDAaCadenaVw($dataDocumento[0]['fecha_emision']) . '</span></p>';
        }

        // OBTENER EL IMPORTE TOTAL
        $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">-  Importe :</span>
                        </b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
        $mensaje .= $dataDocumento[0]['moneda_simbolo'] . number_format($dataDocumento[0]['total'], 2) . '</span></p>';

        $mensaje .= '<p style = "margin-left: 7px; text-align: justify; background: white;" class = "MsoNormal"><b><span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif; color: #333333; mso-fareast-language: ES-PE">-  Motivo :</span>
                        <span style = "font-size: 10pt; font-family: &quot;Arial&quot;,sans-serif;color: #333333; mso-fareast-language: ES-PE">&nbsp;';
        $mensaje .= $razonRechazo . '</b></span></p>';

        $mensaje .= '</div>';

        $titulo = strtoupper("OBSERVACIÓN POR CONTABILIDAD: " . $dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'] . ".";

        $dataUsuarioCreacion = UsuarioNegocio::create()->getUsuario($dataDocumento[0]['usuario_creacion']);
        $usuarioCreacion = $dataUsuarioCreacion[0]['usuario'];
        $usuarioIdJefe = $dataUsuarioCreacion[0]['usuario_padre_id'];
        $correoInteresados = '';

        $correoUsuarioCreacion = UsuarioNegocio::create()->obtenerCorreoXUsuario($usuarioCreacion);
        $correoInteresados .= $correoUsuarioCreacion[0]['email'];

        if (!ObjectUtil::isEmpty($usuarioIdJefe)) {
            $dataUsuarioJefe = UsuarioNegocio::create()->getUsuario($usuarioIdJefe);
            $correoUsuarioJefe = UsuarioNegocio::create()->obtenerCorreoXUsuario($dataUsuarioJefe[0]['usuario']);
            $correoInteresados .= ';' . $correoUsuarioJefe[0]['email'];
        }

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(25);

        //envio de email
        $email = new EmailEnvioUtil();

        $asunto = 'SGI | ' . $titulo;
        $cuerpo = $plantilla[0]["cuerpo"];
        $cuerpo = str_replace("[|asunto|]", $titulo, $cuerpo);
        $cuerpo = str_replace("[|cuerpo|]", $mensaje, $cuerpo);
        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correoInteresados, $asunto, $cuerpo, 1, $usuarioId);

        if (ObjectUtil::isEmpty($res[0]['id'])) {
            throw new WarningException("No se pudo registrar el correo para los interesados.");
        }

        return $res;
    }

    public function registrosRegistroVenta($documentoId, $usuarioId) {
        $respuestaContVoucher = ContVoucherNegocio::create()->registrarContVoucherRegistroVentas($documentoId, $usuarioId);
        $respuestaActualizarEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, NULL, $usuarioId, 'AP', NULL);
        if ($respuestaActualizarEstado[0]['vout_exito'] != 1) {
            throw new WarningException($respuestaActualizarEstado[0]['vout_mensaje']);
        }
        return $respuestaContVoucher;
    }
    
    
    public function ActualizarAsientoVenta($documentoId, $usuarioId) {
        

        $documento = DocumentoNegocio::create()->obtenerXId($documentoId, NULL);

        $asientoId = $documento[0]['cont_voucher_id'];

        $respuestaAnular = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($documentoId, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS, $asientoId);
        if ($respuestaAnular[0]['vout_exito'] != Util::VOUT_EXITO) {
            throw new WarningException('Error al intentar registar el voucher : ' . $respuestaAnular[0]['vout_mensaje']);
        }


        $respuestaContVoucher = ContVoucherNegocio::create()->registrarContVoucherRegistroVentas($documentoId, $usuarioId);
        
//        $respuesaActualizarVoucher = ContVoucherNegocio::create()->transpasarDetalleVoucherXVoucherId($respuestaContVoucher[0]['vout_id'], $asientoId);
//        if ($respuesaActualizarVoucher[0]['vout_exito'] != 1) {
//            throw new WarningException($respuesaActualizarVoucher[0]['vout_mensaje']);
//        }          
//        
//        $respuestaActualizarCampoDocumento = ContVoucherNegocio::create()->guardarVoucherIdXDocumentoId($asientoId, $documentoId);
//        
        return $respuestaContVoucher;
    }
    
    
    

}
