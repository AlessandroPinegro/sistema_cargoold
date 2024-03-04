<?php

require_once __DIR__ . '/../../modelo/almacen/DocumentoTipo.php';
require_once __DIR__ . '/../../modelo/almacen/DocumentoTipoDato.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/CuentaNegocio.php';
require_once __DIR__ . '/ActividadNegocio.php';
require_once __DIR__ . '/../../modelo/contabilidad/SunatTabla.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';

class DocumentoTipoNegocio extends ModeloNegocioBase {

    // Sección de constantes
    const DATO_ENTERO = 1;
    const DATO_CADENA = 2;
    const DATO_FECHA = 3;
    const DATO_LISTA = 4;
    const DATO_PERSONA = 5;
    const DATO_CODIGO = 6;
    const DATO_SERIE = 7;
    const DATO_NUMERO = 8;
    const DATO_FECHA_EMISION = 9;
    const DATO_FECHA_VENCIMIENTO = 10;
    const DATO_FECHA_TENTATIVA = 11;
    const DATO_DESCRIPCION = 12;
    const DATO_COMENTARIO = 13;
    const DATO_IMPORTE_TOTAL = 14;
    const DATO_IMPORTE_IGV = 15;
    const DATO_IMPORTE_SUB_TOTAL = 16;
    const DATO_ORGANIZADOR_DESTINO = 17;
    const DATO_DIRECCION = 18;
    const DATO_PERCEPCION = 19;
    const DATO_CUENTA = 20;
    const DATO_ACTIVIDAD = 21;
    const DATO_RETENCION_DETRACCION = 22;
    const DATO_OTRA_PERSONA = 23;
    const DATO_CAMBIO_PERSONALIZADO = 24;
    const DATO_VENDEDOR = 26;
    const DATO_ARCHIVO_ADJUNTO = 27;
    const DATO_FOB = 29;
    const DATO_CIF = 28;
    const DATO_FLETE_SUNAT = 30;
    const DATO_ARCHIVO_ADJUNTO_MULTI = 31;
    const DATO_FLETE_DOCUMENTO = 32;
    const DATO_SEGURO_DOCUMENTO = 33;
    const DATO_IMPORTE_OTROS = 34;
    const DATO_IMPORTE_EXONERADO = 35;
    const DATO_DETRACCION_TIPO = 36;
    const DATO_PROVEEDOR_FINANCIMIENTO = 37;
    const DATO_IMPORTE_ICBP = 38;
    const DATO_AGENCIA_ORIGEN = 39;
    const DATO_AGENCIA_DESTINO = 40;
    const DATO_VEHICULO = 41;
    const DATO_CONDUCTOR = 42;
    const DATO_FECHA_TRASLADO = 43;
    const DATO_OTROS_CARGOS = 44;
    const DATO_DEVOLUCION_CARGO = 45;
    const DATO_COSTO_REPARTO = 46;
    const DATO_AJUSTE_PRECIO = 47;
    //DOCUMENTO TIPO:
    const TIPO_PROVISION_VENTA = 1;
    const TIPO_PROVISION_COMPRA = 4;
    //IDENTIFICADOR NEGOCIO
    const IN_PEDIDO = 2;
    const IN_BOLETA_VENTA = 3;
    const IN_FACTURA_VENTA = 4;
    const IN_NOTA_CREDITO_VENTA = 5;
    const IN_GUIA_TRANSPORTISTA = 6;
    const IN_NOTA_DEBITO_VENTA = 25;
    const IN_BOLETA_COMPRA = 17;
    const IN_FACTURA_COMPRA = 18;
    const IN_NOTA_CREDITO_COMPRA = 34;
    const IN_NOTA_DEBITO_COMPRA = 35;
    const IN_ANTICIPO_PROVEEDOR = 19;
    const IN_EAR_DESEMBOLSO = 26;
    const IN_EAR_REEMBOLSO = 27;
    const IN_CERTIFICADOR_RETENCION = 28;
    const IN_CERTIFICADOR_DETRACCION = 29;
    const IN_FINANCIAMIENTO_COMPRA = 31;
    const IN_GARANTIA = 32;
    const IN_DIFERENCIA_MONTO = 33;
    const IN_GUIA_RECEPCION = 22;
    const IN_INVOICE_COMMERCIAL = 24;
    const IN_LETRA_VENTA = 28;
    const IN_TICKET_POS = 37;
    const IN_TICKET_TRANSFERENCIA = 38;
    const IN_TICKET_DEPOSITO = 39;
    const IN_CONSTANCIA_ENTREGA = 36;
    const IN_EFECTIVO = 40;
    const IN_INGRESO = 41;
    const IN_EGRESO = 42;
    const IN_NOTA_VENTA = 43;
    const IN_LIQUIDACION_COBRANZA = 44;
    //RESPUESTA SUNAT DE REGISTRO
    const EFACT_PENDIENTE_ENVIO = 0;
    const EFACT_CORRECTO = 1;
    const EFACT_ERROR_CONTROLADO = 2;
    const EFACT_ERROR_DESCONOCIDO = 3;
    const EFACT_ERROR_RECHAZADO = 4;

    /**
     * 
     * @return DocumentoTipoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
    }

    public function obtenerDocumentoTipoDatoSimple($documentoTipoId) {
        return DocumentoTipoDato::create()->obtenerDocumentoTipoDato($documentoTipoId);
    }

    public function obtenerPersonas($documentoTipoId, $usuarioId) {
        $dtd = $this->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        if (!ObjectUtil::isEmpty($dtd)) {
            foreach ($dtd as $index => $itemDtd) {
                // asignamos la data necesaria para que cargue los componentes
                switch ($itemDtd["tipo"]) {
                    case self::DATO_PERSONA:
                        if (ObjectUtil::isEmpty($itemDtd['cadena_defecto'])) {
                            $dtd[$index]["data"] = PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda2(""); //PersonaNegocio::create()->obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId);
                        } else {
                            $data = PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda2(NULL); //PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId($itemDtd['cadena_defecto']);
                            foreach ($data as $indice => $persona) {
                                $data[$indice]['nombre'] = $persona["persona_nombre"];
                            }
                            $dtd[$index]["data"] = $data;
                        }
                        $persona = $dtd[$index]["data"];
                        break;
                    case self::DATO_OTRA_PERSONA:
                        if (ObjectUtil::isEmpty($itemDtd['numero_defecto'])) {
                            $dtd[$index]["data"] = PersonaNegocio::create()->obtenerActivas();
                        } else {
                            $data = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId($itemDtd['numero_defecto']);
                            foreach ($data as $indice => $persona) {
                                $data[$indice]['nombre'] = $persona["persona_nombre"];
                            }
                            $dtd[$index]["data"] = $data;
                        }
                        $persona2 = $dtd[$index]["data"];
                        break;
                }
            }
        }
        return $dtd;
    }


    

    public function obtenerDocumentoTipoDatoConf($documentoTipoId, $usuarioId, $comprobanteId) {
        $dtd = $this->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        $serieDefecto = NULL;
        $correlativoDefecto = NULL;
        if($documentoTipoId==61 || $documentoTipoId==269 ){
            $dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
            $agenciaId=$dataAgencia[0]['id'];
        }
        if (!ObjectUtil::isEmpty($dtd)) {
            foreach ($dtd as $index => $itemDtd) {
                // asignamos la data necesaria para que cargue los componentes
                switch ($itemDtd["tipo"]) {
                    case self::DATO_PERSONA:
                    case self::DATO_PROVEEDOR_FINANCIMIENTO:
                        $dtd[$index]["data"] = PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda(NULL); //PersonaNegocio::create()->obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId);
                        $persona = $dtd[$index]["data"];
                        break;
                    case self::DATO_LISTA:
                        $dtd[$index]["data"] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($itemDtd["id"]);
                        break;
                    case self::DATO_FECHA_EMISION:
                    case self::DATO_FECHA_VENCIMIENTO:
                    case self::DATO_FECHA_TENTATIVA:
                    case self::DATO_FECHA:
                    case self::DATO_FECHA_TRASLADO:
                        $dtd[$index]["data"] = date("d/m/Y");
                        break;
                    case self::DATO_SERIE:
                        if (!ObjectUtil::isEmpty($agenciaId)) {
                            //NO USAMOS EL USUARIO PARA GENERAR EL CORRELATIVO
                            if (($documentoTipoId == 284  ) && ObjectUtil::isEmpty($usuarioId)) {
                                $dataAperturaCaja = ACCajaNegocio::create()->obtenerCajaCorrelativoXAgenciaId($agenciaId);
                            } else {
                                $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($itemDtd["empresa_id"], $usuarioId, $agenciaId);
                            }
                            if (ObjectUtil::isEmpty($dataAperturaCaja)) {
                                throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
                            }
                            $serieSubFijo = $dataAperturaCaja[0]['sufijo_comprobante'];

                            switch ((int) $itemDtd['identificador_negocio']) {
                                case DocumentoTipoNegocio::IN_BOLETA_VENTA :
                                    $serieDefecto = $dataAperturaCaja[0]['serie_boleta'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_boleta'];
                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para boleta que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para boleta que pertenece a esta caja esta vacio.");
                                    }

                                    break;
                                case DocumentoTipoNegocio::IN_FACTURA_VENTA :
                                    $serieDefecto = $dataAperturaCaja[0]['serie_factura'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_factura'];

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para factura que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para factura que pertenece a esta caja esta vacio.");
                                    }
                                    break;
                                case DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA :
                                    if($comprobanteId=='7'){
                                        $serieDefecto = $dataAperturaCaja[0]['serie_nota_credito_factura'];
                                        $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_credito_factura'];
                                         }
                                        else {
                                            $serieDefecto = $dataAperturaCaja[0]['serie_nota_credito_boleta'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_credito_boleta'];
                                        }

                                    if(ObjectUtil::isEmpty($serieDefecto)){
                                        $serieDefecto='-';
                                        $correlativoDefecto ='-';
                                    }

                                    // if (ObjectUtil::isEmpty($serieDefecto)) {
                                    //     throw new WarningException("La serie para nota de cédito que pertenece a esta caja esta vacio.");
                                    // }

                                    // if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                    //     throw new WarningException("El correlativo para nota de cédito que pertenece a esta caja esta vacio.");
                                    // }
                                    break;

                                case DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA :
                                    if($comprobanteId=='7'){
                                    $serieDefecto = $dataAperturaCaja[0]['serie_nota_debito_factura'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_debito_factura'];
                                     }
                                    else {
                                        $serieDefecto = $dataAperturaCaja[0]['serie_nota_debito_boleta'];
                                        $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_debito_boleta'];
                                    }
                                    
                                    if(ObjectUtil::isEmpty($serieDefecto)){
                                        $serieDefecto='-';
                                        $correlativoDefecto ='-';
                                    }
                                    // if (ObjectUtil::isEmpty($serieDefecto)) {
                                    //     throw new WarningException("La serie para nota de débito que pertenece a esta caja esta vacio.");
                                    // }

                                    // if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                    //     throw new WarningException("El correlativo para nota de débito que pertenece a esta caja esta vacio.");
                                    // }
                                    break;
                                case DocumentoTipoNegocio::IN_GUIA_TRANSPORTISTA:
                                    $serieDefecto = NULL;
                                    $correlativoDefecto = NULL;
                                    $documentoIdentificadorNegocioRelacion=null;
                                    switch ((int) $documentoIdentificadorNegocioRelacion) {
                                        case DocumentoTipoNegocio::IN_FACTURA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_factura'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_factura'];
                                            break;
                                        case DocumentoTipoNegocio::IN_BOLETA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_boleta'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_boleta'];
                                            break;
                                        case DocumentoTipoNegocio::IN_NOTA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_nota_venta'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_nota_venta'];
                                            break;
                                    }

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para la guía de transportista que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para la guía de transportista que pertenece a esta caja esta vacio.");
                                    }

                                    break;
                                default :
                                    $serieDefecto = substr($itemDtd["cadena_defecto"], 0, 1) . $serieSubFijo;
                            }
                            $dtd[$index]["data"] = $serieDefecto;
                            $dtd[$index]["cadena_defecto"] = $serieDefecto;
                        }
                        break;
                    case self::DATO_NUMERO:
                        // Obtengo el ultimo numero de documento que se haya registrado  
                        $dtd[$index]["data"] = $itemDtd["cadena_defecto"];
                        if ($itemDtd["autoincrementable"] == 1) {
                            $correlativo = $itemDtd["cadena_defecto"] . DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serieDefecto);
                            if ($itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_BOLETA_VENTA || 
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_GUIA_TRANSPORTISTA
                            ) {
                                if ((int) $correlativoDefecto > (int) $correlativo) {
                                    $correlativo = $correlativoDefecto;
                                }
                            }
                            $dtd[$index]["data"] = $correlativo;
                        }
//                        $dtd[$index]["data"] = ($itemDtd["autoincrementable"] == 1) ? $itemDtd["cadena_defecto"] . DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serieDefecto) : $itemDtd["cadena_defecto"];
                        break;
                    case self::DATO_ORGANIZADOR_DESTINO:
                        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoTipoId($documentoTipoId);
                        $movimientoTipoId = $movimientoTipo[0]["movimiento_tipo_id"];

//                        if($itemDtd['codigo']==3){
//                            $dtd[$index]["data"] = OrganizadorNegocio::create()->obtenerXMovimientoTipo2($movimientoTipoId,$itemDtd['cadena_defecto']);
//                        }else{
                        $dtd[$index]["data"] = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);
//                        }
                        //$organizador=$dtd[$index]["data"];
                        break;
                    case self::DATO_CUENTA:
                        $dtd[$index]["data"] = CuentaNegocio::create()->obtenerCuentasActivas();
                        break;
                    case self::DATO_ACTIVIDAD:
                        $dtd[$index]["data"] = ActividadNegocio::create()->obtenerActividadesActivas($documentoTipoId);
                        break;
                    case self::DATO_RETENCION_DETRACCION:
                        $dtd[$index]["data"] = array(array('id' => 1, 'descripcion' => 'Retención'), array('id' => 2, 'descripcion' => 'Detracción'));
                        break;
                    case self::DATO_OTRA_PERSONA:
                        if (ObjectUtil::isEmpty($itemDtd['cadena_defecto'])) {
                            $dtd[$index]["data"] = PersonaNegocio::create()->obtenerActivas();
                        } else {
                            $data = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId($itemDtd['cadena_defecto']);
                            foreach ($data as $indice => $persona) {
                                $data[$indice]['nombre'] = $persona["persona_nombre"];
                            }
                            $dtd[$index]["data"] = $data;
                        }
                        $persona2 = $dtd[$index]["data"];
                        break;
                    case self::DATO_VENDEDOR:
                        $vendedores = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-2);
                        $dtd[$index]["data"] = $vendedores;
                        $dtd[$index]["numero_defecto"] = $this->obtenerPersonaIdPorUsuarioId($vendedores, $usuarioId);
                        break;
                }
            }
        }
        return $dtd;
    }


    public function obtenerDocumentoTipoDato($documentoTipoId, $usuarioId, $agenciaId = NULL, $documentoIdentificadorNegocioRelacion = NULL,$documentoTipo=NULL) {
        $dtd = $this->obtenerDocumentoTipoDatoSimple($documentoTipoId);
        $serieDefecto = NULL;
        $correlativoDefecto = NULL;
        if($documentoTipoId==61 || $documentoTipoId==269 ){
            $dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
             $agenciaId=$dataAgencia[0]['id'];
        }
        if (!ObjectUtil::isEmpty($dtd)) {
            foreach ($dtd as $index => $itemDtd) {
                // asignamos la data necesaria para que cargue los componentes
                switch ($itemDtd["tipo"]) {
                    case self::DATO_PERSONA:
                    case self::DATO_PROVEEDOR_FINANCIMIENTO:
                        $dtd[$index]["data"] = PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda(NULL); //PersonaNegocio::create()->obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId);
                        $persona = $dtd[$index]["data"];
                        break;
                    case self::DATO_LISTA:
                        $dtd[$index]["data"] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($itemDtd["id"]);
                        break;
                    case self::DATO_FECHA_EMISION:
                    case self::DATO_FECHA_VENCIMIENTO:
                    case self::DATO_FECHA_TENTATIVA:
                    case self::DATO_FECHA:
                    case self::DATO_FECHA_TRASLADO:
                        $dtd[$index]["data"] = date("d/m/Y");
                        break;
                    case self::DATO_SERIE:
                        if (!ObjectUtil::isEmpty($agenciaId)) {
                            //NO USAMOS EL USUARIO PARA GENERAR EL CORRELATIVO
                            if (($documentoTipoId == 284  ) && ObjectUtil::isEmpty($usuarioId)) {
                                $dataAperturaCaja = ACCajaNegocio::create()->obtenerCajaCorrelativoXAgenciaId($agenciaId);
                            } else {
                                $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($itemDtd["empresa_id"], $usuarioId, $agenciaId);
                            }
                            if (ObjectUtil::isEmpty($dataAperturaCaja)) {
                                throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
                            }
                            $serieSubFijo = $dataAperturaCaja[0]['sufijo_comprobante'];

                            switch ((int) $itemDtd['identificador_negocio']) {
                                case DocumentoTipoNegocio::IN_BOLETA_VENTA :
                                    $serieDefecto = $dataAperturaCaja[0]['serie_boleta'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_boleta'];
                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para boleta que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para boleta que pertenece a esta caja esta vacio.");
                                    }

                                    break;
                                case DocumentoTipoNegocio::IN_FACTURA_VENTA :
                                    $serieDefecto = $dataAperturaCaja[0]['serie_factura'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_factura'];

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para factura que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para factura que pertenece a esta caja esta vacio.");
                                    }
                                    break;
                                case DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA :
                                    if($documentoTipo==6){
                                        $serieDefecto = $dataAperturaCaja[0]['serie_nota_credito_boleta'];
                                        $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_credito_boleta'];
                                    } else {
                                    $serieDefecto = $dataAperturaCaja[0]['serie_nota_credito_factura'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_credito_factura'];
                                    }

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para nota de cédito que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para nota de cédito que pertenece a esta caja esta vacio.");
                                    }
                                    break;

                                case DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA :
                                    $serieDefecto = $dataAperturaCaja[0]['serie_nota_debito_factura'];
                                    $correlativoDefecto = $dataAperturaCaja[0]['correlativo_nota_debito_factura'];

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para nota de débito que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para nota de débito que pertenece a esta caja esta vacio.");
                                    }
                                    break;
                                case DocumentoTipoNegocio::IN_GUIA_TRANSPORTISTA:
                                    $serieDefecto = NULL;
                                    $correlativoDefecto = NULL;
                                    switch ((int) $documentoIdentificadorNegocioRelacion) {
                                        case DocumentoTipoNegocio::IN_FACTURA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_factura'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_factura'];
                                            break;
                                        case DocumentoTipoNegocio::IN_BOLETA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_boleta'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_boleta'];
                                            break;
                                        case DocumentoTipoNegocio::IN_NOTA_VENTA :
                                            $serieDefecto = $dataAperturaCaja[0]['serie_guia_nota_venta'];
                                            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_guia_nota_venta'];
                                            break;
                                    }

                                    if (ObjectUtil::isEmpty($serieDefecto)) {
                                        throw new WarningException("La serie para la guía de transportista que pertenece a esta caja esta vacio.");
                                    }

                                    if (ObjectUtil::isEmpty($correlativoDefecto)) {
                                        throw new WarningException("El correlativo para la guía de transportista que pertenece a esta caja esta vacio.");
                                    }

                                    break;
                                default :
                                    $serieDefecto = substr($itemDtd["cadena_defecto"], 0, 1) . $serieSubFijo;
                            }
                            $dtd[$index]["data"] = $serieDefecto;
                            $dtd[$index]["cadena_defecto"] = $serieDefecto;
                        }
                        break;
                    case self::DATO_NUMERO:
                        // Obtengo el ultimo numero de documento que se haya registrado  
                        $dtd[$index]["data"] = $itemDtd["cadena_defecto"];
                        if ($itemDtd["autoincrementable"] == 1) {
                            $correlativo = $itemDtd["cadena_defecto"] . DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serieDefecto);
                            if ($itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_BOLETA_VENTA || 
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA ||
                                $itemDtd['identificador_negocio'] == DocumentoTipoNegocio::IN_GUIA_TRANSPORTISTA
                            ) {
                                if ((int) $correlativoDefecto > (int) $correlativo) {
                                    $correlativo = $correlativoDefecto;
                                }
                            }
                            $dtd[$index]["data"] = $correlativo;
                        }
//                        $dtd[$index]["data"] = ($itemDtd["autoincrementable"] == 1) ? $itemDtd["cadena_defecto"] . DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId, $serieDefecto) : $itemDtd["cadena_defecto"];
                        break;
                    case self::DATO_ORGANIZADOR_DESTINO:
                        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoTipoId($documentoTipoId);
                        $movimientoTipoId = $movimientoTipo[0]["movimiento_tipo_id"];

//                        if($itemDtd['codigo']==3){
//                            $dtd[$index]["data"] = OrganizadorNegocio::create()->obtenerXMovimientoTipo2($movimientoTipoId,$itemDtd['cadena_defecto']);
//                        }else{
                        $dtd[$index]["data"] = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);
//                        }
                        //$organizador=$dtd[$index]["data"];
                        break;
                    case self::DATO_CUENTA:
                        $dtd[$index]["data"] = CuentaNegocio::create()->obtenerCuentasActivas();
                        break;
                    case self::DATO_ACTIVIDAD:
                        $dtd[$index]["data"] = ActividadNegocio::create()->obtenerActividadesActivas($documentoTipoId);
                        break;
                    case self::DATO_RETENCION_DETRACCION:
                        $dtd[$index]["data"] = array(array('id' => 1, 'descripcion' => 'Retención'), array('id' => 2, 'descripcion' => 'Detracción'));
                        break;
                    case self::DATO_OTRA_PERSONA:
                        if (ObjectUtil::isEmpty($itemDtd['cadena_defecto'])) {
                            $dtd[$index]["data"] = PersonaNegocio::create()->obtenerActivas();
                        } else {
                            $data = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId($itemDtd['cadena_defecto']);
                            foreach ($data as $indice => $persona) {
                                $data[$indice]['nombre'] = $persona["persona_nombre"];
                            }
                            $dtd[$index]["data"] = $data;
                        }
                        $persona2 = $dtd[$index]["data"];
                        break;
                    case self::DATO_VENDEDOR:
                        $vendedores = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-2);
                        $dtd[$index]["data"] = $vendedores;
                        $dtd[$index]["numero_defecto"] = $this->obtenerPersonaIdPorUsuarioId($vendedores, $usuarioId);
                        break;
                }
            }
        }
        return $dtd;
    }

    private function obtenerPersonaIdPorUsuarioId($vendedores, $usuarioId) {
        if (ObjectUtil::isEmpty($vendedores)) {
            return null;
        } else {
            foreach ($vendedores as $vendedor) {
                if ($vendedor["usuario_id"] == $usuarioId) {
                    return $vendedor["id"];
                    break;
                }
            }
        }
    }

    public function obtenerDocumentoTipoDatoXMovimientoTipo($movimientoTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoDatoXMovimientoTipo($movimientoTipoId);
    }

    public function obtenerDocumentoTipoXEmpresa($empresaId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXEmpresa($empresaId);
    }

    ////para pagos 

    public function obtenerDocumentoTipoXTipo($empresa_id, $tipo1, $tipoPagoProvision) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXTipo($empresa_id, $tipo1, $tipoPagoProvision);
    }

    public function obtenerDocumentoTipoSinDocumentosDeMovimientoXTipo($empresa_id, $tipo1, $tipoPagoProvision) {
        return DocumentoTipo::create()->obtenerDocumentoTipoSinDocumentosDeMovimientoXTipo($empresa_id, $tipo1, $tipoPagoProvision);
    }

    public function obtenerDocumentoTipoDatoXTipo($tipo1, $tipoPagoProvision) {
        return DocumentoTipo::create()->obtenerDocumentoTipoDatoXTipo($tipo1, $tipoPagoProvision);
    }

    public function obtenerDocumentoTipoXEmpresaXTipo($empresaId, $idTipos) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXEmpresaXTipo($empresaId, $idTipos);
    }

    public function obtenerDocumentoTipoXTipos($idTipos) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXTipos($idTipos);
    }

    public function obtenerIdXDocumentoTipoDescripcionOpcionId($documentoTipo, $opcionId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoIdXDocumentoTipoDescripcionOpcionId($documentoTipo, $opcionId);
    }

    public function obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $idTipos) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $idTipos);
    }

    public function obtenerXOpcion($opcionId) {
        return DocumentoTipo::create()->obtenerXOpcion($opcionId);
    }

    public function obtenerDocumentoTipoNotasCreditoDebito() {
        return DocumentoTipo::create()->obtenerDocumentoTipoNotasCreditoDebito();
    }

    public function obtenerDocumentoTipoXOperacionTipo($operacionTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXOperacionTipo($operacionTipoId);
    }

    public function obtenerDocumentoTipoDatoXOperacionTipo($operacionTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoDatoXOperacionTipo($operacionTipoId);
    }

    public function obtenerDocumentoTipoXTiposxDescripcion($idTipos, $descripcion) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXTiposxDescripcion($idTipos, $descripcion);
    }

    public function obtenerDocumentoTipoXId($documentoTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXId($documentoTipoId);
    }

    public function obtenerDocumentoTipoGenerarXDocumentoTipoId($documentoTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoGenerarXDocumentoTipoId($documentoTipoId);
    }

    public function obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato);
    }

    public function obtenerDocumentoTipoXDocumentoId($documentoId) {
        $data = DocumentoTipo::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        return $data;
    }

    public function buscarDocumentoTipoXOpcionXDescripcion($opcionId, $busqueda) {
        return DocumentoTipo::create()->buscarDocumentoTipoXOpcionXDescripcion($opcionId, $busqueda);
    }

    public function obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId) {
        $dtd = DocumentoTipoDato::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);

        if (!ObjectUtil::isEmpty($dtd)) {
            foreach ($dtd as $index => $itemDtd) {
                // asignamos la data necesaria para que cargue los componentes
                switch ($itemDtd["tipo"]) {
                    case self::DATO_PERSONA:
                        $dtd[$index]["data"] = PersonaNegocio::create()->obtenerActivas();
                        $persona = $dtd[$index]["data"];
                        break;
                    case self::DATO_LISTA:
                        $dtd[$index]["data"] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($itemDtd["documento_tipo_dato_id"]);
                        break;
                    case self::DATO_FECHA_EMISION:
                    case self::DATO_FECHA_VENCIMIENTO:
                    case self::DATO_FECHA_TENTATIVA:
                    case self::DATO_FECHA:
                        $dtd[$index]["data"] = date("d/m/Y");
                        break;
                    case self::DATO_NUMERO:
                        // Obtengo el ultimo numero de documento que se haya registrado
                        $dtd[$index]["data"] = ($itemDtd["autoincrementable"] == 1) ? $itemDtd["cadena_defecto"] . DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoId) : $itemDtd["cadena_defecto"];
                        break;
                    case self::DATO_ORGANIZADOR_DESTINO:
                        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoTipoId($documentoTipoId);
                        $movimientoTipoId = $movimientoTipo[0]["movimiento_tipo_id"];

                        $dtd[$index]["data"] = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);
                        //$organizador=$dtd[$index]["data"];
                        break;
                    case self::DATO_CUENTA:
                        $dtd[$index]["data"] = CuentaNegocio::create()->obtenerCuentasActivas();
                        break;
                    case self::DATO_ACTIVIDAD:
                        $dtd[$index]["data"] = ActividadNegocio::create()->obtenerActividadesActivas($documentoTipoId);
                        break;
                    case self::DATO_RETENCION_DETRACCION:
                        $dtd[$index]["data"] = array(array('id' => 1, 'descripcion' => 'Retención'), array('id' => 2, 'descripcion' => 'Detracción'));
                        break;
                    case self::DATO_OTRA_PERSONA:
                        if (ObjectUtil::isEmpty($itemDtd['numero_defecto'])) {
                            $dtd[$index]["data"] = PersonaNegocio::create()->obtenerActivas();
                        } else {
                            $data = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId($itemDtd['numero_defecto']);
                            foreach ($data as $indice => $persona) {
                                $data[$indice]['nombre'] = $persona["persona_nombre"];
                            }
                            $dtd[$index]["data"] = $data;
                        }
                        $persona2 = $dtd[$index]["data"];
                        break;
                }
            }
        }

        return $dtd;
    }

    public function buscarDocumentoTipoOperacionXOpcionXDescripcion($opcionId, $busqueda) {
        return DocumentoTipo::create()->buscarDocumentoTipoOperacionXOpcionXDescripcion($opcionId, $busqueda);
    }

    public function obtenerDocumentoTipoGenerarXMovimientoTipoId($movimientoTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoGenerarXMovimientoTipoId($movimientoTipoId);
    }

    public function obtenerOpcionGenerarDocumentoXMovimientoTipoIdXDocumentoTipoId($movimientoTipoId, $documentoTipoId) {
        return DocumentoTipo::create()->obtenerOpcionGenerarDocumentoXMovimientoTipoIdXDocumentoTipoId($movimientoTipoId, $documentoTipoId);
    }

    public function buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdArray, $descripcion) {
        return DocumentoTipo::create()->buscarDocumentoTipoXDocumentoTipoXDescripcion(Util::fromArraytoString($documentoTipoIdArray), $descripcion);
    }

    public function buscarDocumentoTipoXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        return DocumentoTipo::create()->buscarDocumentoTipoXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    public function buscarDocumentoTipoXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        return DocumentoTipo::create()->buscarDocumentoTipoXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    public function buscarDocumentoTipoXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda) {
        return DocumentoTipo::create()->buscarDocumentoTipoXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    //parte contable
    public function listarDocumentoTipo($empresaId) {
        return DocumentoTipo::create()->listarDocumentoTipo($empresaId);
    }

    public function obtenerConfiguracionesIniciales($empresaId) {
        $resultado->dataSunatDetalle = SunatTabla::create()->obtenerDetalleXSunatTablaId(10);
        $resultado->dataTipo = Tabla::create()->obtenerXPadreId(7);
        return $resultado;
    }

    public function guardarDocumentoTipo($descripcion, $comentario, $codigoSunatId, $estadoId, $usuarioId, $empresaId, $documentoTipoId, $tipo) {

        $resDocumentoTipo = DocumentoTipo::create()->guardarDocumentoTipo($descripcion, $comentario, $codigoSunatId, $estadoId, $usuarioId, $empresaId, $documentoTipoId, $tipo);

        $respuesta->resultado = $resDocumentoTipo;
        return $respuesta;
    }

    public function cambiarEstado($id) {
        return DocumentoTipo::create()->cambiarEstado($id);
    }

    //modal de copia en operacion
    public function obtenerDocumentoTipoXEmpresaXTipoXOperacionTipoXDocumentoTipo($operacionTipoId, $documentoTipoId, $empresaId, $tipoIds) {
        return DocumentoTipo::create()->obtenerDocumentoTipoXEmpresaXTipoXOperacionTipoXDocumentoTipo($operacionTipoId, $documentoTipoId, $empresaId, $tipoIds);
    }

    public function buscarDocumentoOperacionXDocumentoTipoXDescripcion($documentoTipoIdArray, $descripcion) {
        return DocumentoTipo::create()->buscarDocumentoOperacionXDocumentoTipoXDescripcion(Util::fromArraytoString($documentoTipoIdArray), $descripcion);
    }

    public function obtenerDocumentoTipoReporteXOperacionTipos($operacionTipoId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoReporteXOperacionTipos($operacionTipoId);
    }

    public function obtenerDocumentoTipoPPagoXTipos($tipos) {
        return DocumentoTipo::create()->obtenerDocumentoTipoPPagoXTipos($tipos);
    }

    public function obtenerDocumentoTipoPPago() {
        return DocumentoTipo::create()->obtenerDocumentoTipoPPago();
    }

    public function obtenerDocumentoTipoAprobacionParcial() {
        return DocumentoTipo::create()->obtenerDocumentoTipoAprobacionParcial();
    }

    public function obtenerDocumentoTipoProgramacionAtencion() {
        return DocumentoTipo::create()->obtenerDocumentoTipoProgramacionAtencion();
    }

    public function obtenerDocumentoTipoNC($identificadorNegocio, $empresaId) {
        return DocumentoTipo::create()->obtenerDocumentoTipoNC($identificadorNegocio, $empresaId);
    }

}
