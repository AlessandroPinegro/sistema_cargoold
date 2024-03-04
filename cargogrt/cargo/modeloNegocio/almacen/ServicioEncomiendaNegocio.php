<?php

require_once __DIR__ . '/PedidoNegocio.php';
require_once __DIR__ . '/TarifarioNegocio.php';
require_once __DIR__ . '/ServicioEncomiendaNegocio.php';

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/config/ConfigGlobal.php';

class ServicioEncomiendaNegocio extends ModeloNegocioBase {

    CONST EMPRESA_ID = 2;
    CONST MONEDA_ID = 2;
    CONST TIPO_AGENCIA = 1;
    CONST TIPO_RECOJO_DOMICILIO = 2;
    CONST TIPO_ARTICULO = 1;
    CONST TIPO_PAQUETE = 0;
    CONST TIPO_PESO_TOTAL = 1;
    CONST TIPO_PESO_UNITARIO = 0;
    CONST TIPO_COMPROBANTE_FACTURA = "FACTURA";
    CONST TIPO_COMPROBANTE_BOLETA = "BOLETA";

    /**
     * 
     * @return ServicioEncomiendaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($usuarioId, $destinatarioId, $tipo) {

        $dataRemitente = UsuarioNegocio::create()->obtenerPersonaXUsuarioId($usuarioId);
        $dataDestinatario = PersonaNegocio::create()->obtenerPersonaContactoXUsuarioId($usuarioId, $destinatarioId);

        $personaId = $dataRemitente[0]['persona_id'];

        $dataRemitenteDireccion = PersonaNegocio::create()->obtenerPersonaDireccionXUsuarioId($usuarioId);
        $dataDestinatarioDireccion = PersonaNegocio::create()->obtenerContactoDireccionXPersonaIdXUsuarioId($destinatarioId, $usuarioId);

        $dataAgencia = AgenciaNegocio::create()->obtenerAgenciaActivas();
        $agenciaOrigenId = null;
        $agenciaDestinoId = null;

        $dataAgenciaDireccion = array();

        foreach ($dataAgencia as $item) {
            $dataAgenciaDireccion[] = array('direccion_id' => 'A' . $item['agencia_id'], 'persona_id' => $personaId, 'agencia_id' => $item['agencia_id'], 'agencia_descripcion' => $item['descripcion'], 'direccion_completa' => 'AGENCIA ' . $item['descripcion']);
        }
        $direccionOrigenId = NULL;
        $direccionDestinoId = NULL;
        if ($tipo == ServicioEncomiendaNegocio::TIPO_AGENCIA) {
            $dataRemitenteDireccion = $dataAgenciaDireccion;
            $direccionOrigenId = $dataRemitenteDireccion[0]['direccion_id'];
            $agenciaOrigenId = $dataRemitenteDireccion[0]['agencia_id'];
        } elseif ($tipo == ServicioEncomiendaNegocio::TIPO_RECOJO_DOMICILIO && !ObjectUtil::isEmpty($dataRemitenteDireccion)) {
            $direccionOrigenId = $dataRemitenteDireccion[0]['direccion_id'];
            $agenciaOrigenId = $dataRemitenteDireccion[0]['agencia_id'];
        }

        if (!ObjectUtil::isEmpty($dataDestinatarioDireccion)) {
            $dataDestinatarioDireccion = array_merge($dataAgenciaDireccion, $dataDestinatarioDireccion);
            $direccionDestinoId = $dataDestinatarioDireccion[0]['direccion_id'];
            $agenciaOrigenId = $dataDestinatarioDireccion[0]['agencia_id'];
        } else {
            $dataDestinatarioDireccion = $dataAgenciaDireccion;
            $direccionDestinoId = $dataDestinatarioDireccion[0]['direccion_id'];
            $agenciaOrigenId = $dataDestinatarioDireccion[0]['agencia_id'];
        }

        $dataBien = self::obtenerBienTarifario($agenciaOrigenId, $agenciaDestinoId, $dataRemitente[0]['persona_id']);

        $respuesta = new stdClass();
        $respuesta->dataRemitente = $dataRemitente;
        $respuesta->dataRemitenteDireccion = $dataRemitenteDireccion;
        $respuesta->dataDestinatario = $dataDestinatario;
        $respuesta->dataDestinatarioDireccion = $dataDestinatarioDireccion;
        $respuesta->dataBien = $dataBien;
        $respuesta->porcentajeIgv = Configuraciones::IGV_PORCENTAJE;
        $respuesta->direccionPorDefectoRemitenteId = $direccionOrigenId;
        $respuesta->direccionPorDefectoDestinatarioId = $direccionDestinoId;
        $respuesta->urlTerminoCondiciones = Configuraciones::url_host() . '/terminos_condiciones.php/';

        return $respuesta;
    }

    public function obtenerBienTarifario($agenciaOrigenId, $agenciaDestinoId, $personaId) {
        $empresaId = ServicioEncomiendaNegocio::EMPRESA_ID;

        $dataTarifario = PedidoNegocio::create()->obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);
        $dataBien = BienNegocio::create()->obtenerActivosXEmpresaId($empresaId);

        $precio_k = self::obtenerValorTarifario($dataTarifario, 'precio_xk', $personaId);
        $precio_sobre = self::obtenerValorTarifario($dataTarifario, 'precio_sobre', $personaId);

        foreach ($dataBien as $index => $item) {
            $precio = 0;
            $factorAplicado = 0;

            $precioBien = self::obtenerValorTarifario($dataTarifario, 'precio_articulo', $personaId, $item['id']);
            $pesoVolumetrico = $item['peso_volumetrico'];

            // SOBRE
            if ($item['bien_tipo_id'] == 2) {
                $precio = round($precio_sobre * 1);
                $factorAplicado = ($precio_sobre * 1);
            } elseif (!ObjectUtil::isEmpty($precioBien)) {
                $precio = round($precioBien * 1);
                $factorAplicado = ($precio_k * 1);
            } else {
                $precio = round(($precio_k * 1) * ($pesoVolumetrico * 1));
                $factorAplicado = ($precio_k * 1);
            }
            $dataBien[$index]['precio'] = $precio;
            $dataBien[$index]['factor_aplicado'] = $factorAplicado;
        }

        return $dataBien;
    }

    public function obtenerValorTarifario($data, $columna, $personaId, $bienId = NULL) {
        $monedaId = ServicioEncomiendaNegocio::EMPRESA_ID;
        $valorFactor = null;
        if (ObjectUtil::isEmpty($data)) {
            return $valorFactor;
        }
        $consulta = "\$arrayFiltrado = array_merge(array_filter(\$data, function (\$item) { return (\$item['$columna']*1 > 0) && \$item['moneda_id'] == $monedaId && \$item['persona_id'] == $personaId " . (!ObjectUtil::isEmpty($bienId) ? " && \$item['bien_id'] == $bienId" : "") . "  ;}));";
        eval($consulta);

        if (!ObjectUtil::isEmpty($arrayFiltrado)) {
            return $arrayFiltrado[0][$columna];
        } else if ($columna == 'precio_minimo') {
            return 0;
        } else if ($columna == 'precio_articulo') {
            return null;
        } else {
            $consulta = "\$arrayFiltrado = array_merge(array_filter(\$data, function (\$item) { return (\$item['$columna']*1 > 0) && \$item['moneda_id'] == $monedaId;}));";
            eval($consulta);
            if (!ObjectUtil::isEmpty($arrayFiltrado)) {
                return $arrayFiltrado[0][$columna];
            }
        }
        return $valorFactor;
    }

    function obtenerTarifarioDevolucionCargo($agenciaOrigenId, $agenciaDestinoId, $personaId) {
        $dataTarifario = PedidoNegocio::create()->obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);
        return self::obtenerValorTarifario($dataTarifario, 'precio_sobre', $personaId);
    }

    public function obtenerTarifarioDireccion($agenciaOrigenId, $agenciaDestinoId, $personaId, $direccionOrigenId, $direccionDestinoId, $detalle) {
        (float) $tarifaEnvioDomicilio = 0;
        (float) $tarifaRecojoDomicilio = 0;

        $dataTarifario = PedidoNegocio::create()->obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);
        $respuesta = new stdClass();
        $respuesta->tarifaDevolucionCargo = (float) self::obtenerValorTarifario($dataTarifario, 'precio_sobre', $personaId);
        $respuesta->tarifaPrecioMinimo = (float) self::obtenerValorTarifario($dataTarifario, 'precio_minimo', $personaId);

        if (strpos($direccionOrigenId, 'A') !== false && strpos($direccionDestinoId, 'A') !== false) {

            $respuesta->tarifaEnvioDomicilio = $tarifaEnvioDomicilio;
            $respuesta->tarifaRecojoDomicilio = $tarifaRecojoDomicilio;
            return $respuesta;
        }


        $pesoTotal = 0;
        foreach ($detalle as $item) {
            switch ((int) $item['tipo']) {
                case ServicioEncomiendaNegocio::TIPO_PAQUETE:
                    $pesoTotal = $pesoTotal + (!ObjectUtil::isEmpty($item['bien_peso_total']) && (float) $item['bien_peso_total'] > 0 ? (float) $item['bien_peso_total'] : ((float) $item['bien_peso'] * (float) $item['cantidad']) );
                    break;
                case ServicioEncomiendaNegocio::TIPO_ARTICULO:
                    $pesoTotal = $pesoTotal + ((float) $item['bien_peso_volumetrico'] * (float) $item['cantidad']);
                    break;
            }
        }

        if (strpos($direccionOrigenId, 'A') === false) {
            $dataTarifario = TarifarioNegocio::create()->obtenerTarifarioXPersonaDireccionId($direccionOrigenId);
            if (!ObjectUtil::isEmpty($dataTarifario)) {
                switch (true) {
                    case $pesoTotal <= 50:
                        $tarifaRecojoDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_50k']) ? $dataTarifario[0]['precio_50k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 100:
                        $tarifaRecojoDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_100k']) ? $dataTarifario[0]['precio_100k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 250:
                        $tarifaRecojoDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_250k']) ? $dataTarifario[0]['precio_250k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 500:
                        $tarifaRecojoDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_500k']) ? $dataTarifario[0]['precio_500k'] * 1 : 0);
                        break;
                    default:
                        $tarifaRecojoDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_maxk']) ? $dataTarifario[0]['precio_maxk'] * 1 : 0);
                        break;
                }
            }
        }

        if (strpos($direccionDestinoId, 'A') === false) {
            $dataTarifario = TarifarioNegocio::create()->obtenerTarifarioXPersonaDireccionId($direccionDestinoId);
            if (!ObjectUtil::isEmpty($dataTarifario)) {
                switch (true) {
                    case $pesoTotal <= 50:
                        $tarifaEnvioDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_50k']) ? $dataTarifario[0]['precio_50k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 100:
                        $tarifaEnvioDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_100k']) ? $dataTarifario[0]['precio_100k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 250:
                        $tarifaEnvioDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_250k']) ? $dataTarifario[0]['precio_250k'] * 1 : 0);
                        break;
                    case $pesoTotal <= 500:
                        $tarifaEnvioDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_500k']) ? $dataTarifario[0]['precio_500k'] * 1 : 0);
                        break;
                    default:
                        $tarifaEnvioDomicilio = (!ObjectUtil::isEmpty($dataTarifario[0]['precio_maxk']) ? $dataTarifario[0]['precio_maxk'] * 1 : 0);
                        break;
                }
            }
        }

        $respuesta->tarifaEnvioDomicilio = (float) $tarifaEnvioDomicilio;
        $respuesta->tarifaRecojoDomicilio = (float) $tarifaRecojoDomicilio;
        return $respuesta;
    }

    function obtenerPrecioPaquete($agenciaOrigenId, $agenciaDestinoId, $personaId, $item) {
        if (ObjectUtil::isEmpty($item)) {
            return $item;
        }

        $dataTarifario = PedidoNegocio::create()->obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);

        $precio_k = self::obtenerValorTarifario($dataTarifario, 'precio_xk', $personaId);
        $precio_5k = self::obtenerValorTarifario($dataTarifario, 'precio_5k', $personaId);

        $tipoPeso = (int) $item['bandera_tipo_peso'];
        $bienAlto = (float) $item['bien_alto'];
        $bienAncho = (float) $item['bien_ancho'];
        $bienLargo = (float) $item['bien_longitud'];
        $bienPeso = (float) $item['bien_peso'];

        $cantidad = (int) $item['cantidad'];
        $precio = 0;
        $factorAplicado = 0;

        $pesoTotal = (float) $bienPeso * $cantidad;

        $pesoVolumetrico = ($bienAlto * $bienAncho * $bienLargo * 200);
        $pesoVolumetricoTotal = $pesoVolumetrico * $cantidad;

        if (!ObjectUtil::isEmpty($precio_k) && !ObjectUtil::isEmpty($precio_5k) && $cantidad > 0) {
            $precio_5k = round((float) $precio_5k);
            $pesoUsar = ($pesoVolumetricoTotal > $pesoTotal ? $pesoVolumetricoTotal : $pesoTotal);

            $factorAplicado = $precio_k;

            if ($tipoPeso == ServicioEncomiendaNegocio::TIPO_PESO_TOTAL) {
                $pesoUsar = $pesoTotal;
            }

            if ($pesoUsar <= 5) {
                $pesoUsar = 0;
            } else {
                $pesoUsar = round($pesoUsar - 5, 6);
            }
            $precio = ($precio_5k + round($precio_k * $pesoUsar));
            $precio = round($precio / $cantidad, 6);
        }
        $item['bien_peso_total'] = round($pesoTotal, 4);
        $item['bien_peso_volumetrico'] = round($pesoVolumetrico, 4);
        $item['bien_factor_volumetrico'] = round($factorAplicado, 4);
        $item['precio'] = round($precio, 6);
        $item['subTotal'] = round($precio * $cantidad, 2);
        return $item;
    }

    function obtenerActualizarPrecioDetalle($agenciaOrigenId, $agenciaDestinoId, $personaId, $detalle) {
        if (ObjectUtil::isEmpty($detalle)) {
            return $detalle;
        }

        $dataTarifario = PedidoNegocio::create()->obtenerXAgenciaIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);

        $precio_k = self::obtenerValorTarifario($dataTarifario, 'precio_xk', $personaId);
        $precio_5k = self::obtenerValorTarifario($dataTarifario, 'precio_5k', $personaId);
        $precio_sobre = self::obtenerValorTarifario($dataTarifario, 'precio_sobre', $personaId);

        foreach ($detalle as $index => $item) {
            $cantidad = (int) $item['cantidad'];
            $bienTipoId = (int) $item['bienTipoId'];
            $precio = 0;
            $pesoVolumetrico = 0;
            $factorAplicado = 0;

            switch ((int) $item['tipo']) {
                case ServicioEncomiendaNegocio::TIPO_PAQUETE:
                    $pesoTotal = (float) $item['bien_peso_total'];

                    $peso = (float) $item['bien_peso'];
                    $tipoPeso = (int) $item['bandera_tipo_peso'];
                    $bienAlto = (float) $item['bien_alto'];
                    $bienAncho = (float) $item['bien_ancho'];
                    $bienLargo = (float) $item['bien_longitud'];

                    if (ObjectUtil::isEmpty($pesoTotal) || $pesoTotal <= 0) {
                        $pesoTotal = $peso * $cantidad;
                    }

                    $pesoVolumetrico = ($bienAlto * $bienAncho * $bienLargo * 200);
                    $pesoVolumetricoTotal = $pesoVolumetrico * $cantidad;

                    if (!ObjectUtil::isEmpty($precio_k) && !ObjectUtil::isEmpty($precio_5k)) {
                        $factorAplicado = $precio_k;
                        $precio_5k = round((float) $precio_5k);
                        $pesoUsar = ($pesoVolumetricoTotal > $pesoTotal ? $pesoVolumetricoTotal : $pesoTotal);

                        if ($tipoPeso == ServicioEncomiendaNegocio::TIPO_PESO_TOTAL) {
                            $pesoUsar = $pesoTotal;
                        }

                        if ($pesoUsar <= 5) {
                            $pesoUsar = 0;
                        } else {
                            $pesoUsar = round($pesoUsar - 5, 6);
                        }
                        $precio = ($precio_5k + round($precio_k * $pesoUsar));
                        $precio = round($precio / $cantidad, 6);
                    }

                    break;
                case ServicioEncomiendaNegocio::TIPO_ARTICULO:
                    $precioBien = self::obtenerValorTarifario($dataTarifario, 'precio_articulo', $personaId, $item['bien_id']);
                    $pesoVolumetrico = (float) $item['bien_peso_volumetrico'];
                    if ($bienTipoId == 2) {
                        $precio = round($precio_sobre * 1);
                        $factorAplicado = ($precio_sobre * 1);
                    } else if (!ObjectUtil::isEmpty($precioBien)) {
                        $precio = round($precioBien * 1);
                        $factorAplicado = ($precio_k * 1);
                    } else if (!ObjectUtil::isEmpty($precio_k)) {
                        $precio = round($precio_k * $pesoVolumetrico);
                        $factorAplicado = ($precio_k * 1);
                    }
                    break;
            }

            $detalle[$index]['bien_peso_volumetrico'] = round($pesoVolumetrico, 4);
            $detalle[$index]['bien_factor_volumetrico'] = round($factorAplicado, 4);
            $detalle[$index]['precio'] = round($precio, 6);
            $detalle[$index]['subTotal'] = round($precio * $cantidad, 2);
        }
        return $detalle;
    }

    public function validarDatosEncomienda($opcionId, $usuarioId, $documento, $detalle) {

        $fechaEmision = date('Y-m-d');
        if (ObjectUtil::isEmpty($usuarioId)) {
            throw new WarningException("Actualmente no se encuentra en sesión, por favor vuelva a iniciar sesion.");
        }

        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No envió los parámetros necesarios para registrar la encomienda.");
        }

        if (ObjectUtil::isEmpty($detalle)) {
            throw new WarningException("No envió los parámetros necesarios para registrar el detalle de la encomienda.");
        }

        if (ObjectUtil::isEmpty($documento['agenciaOrigenId'])) {
            throw new WarningException("Debe ingresar la agencia origen, verifique que su dirección de origen tenga una zona relacionada.");
        }

        if (ObjectUtil::isEmpty($documento['agenciaDestinoId'])) {
            throw new WarningException("Debe ingresar la agencia destino, verifique que su dirección de destino tenga una zona relacionada.");
        }

        if (ObjectUtil::isEmpty($documento['bandera_registro_movil'])) {
            throw new WarningException("Debe ingresar tipo de registro, usted se acercará a una agencia o el recojo será a su domicilio.");
        }
        $banderaRegistroMovil = $documento['bandera_registro_movil'];

        if (ObjectUtil::isEmpty($documento['comprobanteDescripcion']) || ($documento['comprobanteDescripcion'] != ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA && $documento['comprobanteDescripcion'] != ServicioEncomiendaNegocio::TIPO_COMPROBANTE_BOLETA)) {
            throw new WarningException("El tipo de comprobante seleccionado no es correcto.");
        }

        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fechaEmision);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("No existe un periodo para la siguiente fecha " . $fechaEmision);
        }

        if ($dataPeriodo[0]['indicador'] != 2) {
            throw new WarningException("El periodo no esta abierto " . $fechaEmision);
        }

        $clienteId = $documento['clienteId'];
        if (ObjectUtil::isEmpty($clienteId)) {
            throw new WarningException("Debe ingresar el remitente.");
        }

        $direccionClienteId = $documento['clienteDireccionId'];
        if (ObjectUtil::isEmpty($direccionClienteId)) {
            throw new WarningException("Debe ingresar la dirección de origen.");
        }

        if ($banderaRegistroMovil == ServicioEncomiendaNegocio::TIPO_RECOJO_DOMICILIO && strpos($direccionClienteId, 'A') !== false) {
            throw new WarningException("La dirección del cliente invalida.");
        }

        $destinatarioId = $documento['destinatarioId'];
        if (ObjectUtil::isEmpty($destinatarioId)) {
            throw new WarningException("Debe ingresar el destinatario.");
        }

        $direccionDestinatarioId = $documento['destinatarioDireccionId'];
        if (ObjectUtil::isEmpty($direccionDestinatarioId)) {
            throw new WarningException("Debe ingresar la dirección de destino.");
        }

        $dataRemitente = UsuarioNegocio::create()->obtenerPersonaXUsuarioId($usuarioId);
        if (ObjectUtil::isEmpty($dataRemitente)) {
            throw new WarningException("No se encontró la información del usuario en sesión para registrar el pedido.");
        }
        if ($clienteId != $dataRemitente[0]['persona_id']) {
            throw new WarningException("La persona relacionada con el usuario es diferente al cliente seleccionado.");
        }

        $dataDestinatario = PersonaNegocio::create()->obtenerPersonaContactoXUsuarioId($usuarioId, $destinatarioId);
        if (ObjectUtil::isEmpty($dataDestinatario)) {
            throw new WarningException("No se encontró la información del destinatario.");
        }

        $facturadoRuc = $documento['facturado_ruc'];
        $facturadoNombre = $documento['facturado_nombre'];

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA && ObjectUtil::isEmpty($facturadoRuc)) {
            throw new WarningException("Ingrese el ruc del cliente a facturar.");
        }

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA && ObjectUtil::isEmpty($facturadoNombre)) {
            throw new WarningException("Ingrese la razón social del cliente a facturar.");
        }
        $comprobanteTipoId = ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA ? 7 : 6);

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoVirtualXAgenciaId($documento['agenciaOrigenId']);
        if (ObjectUtil::isEmpty($dataAperturaCaja)) {
            throw new WarningException("No se tiene la caja virtual aperturada.");
        }
        $serieSubFijo = $dataAperturaCaja[0]['sufijo_comprobante'];
        $seriePedido = "P" . $serieSubFijo;

        if (ObjectUtil::isEmpty($seriePedido)) {
            throw new WarningException("No se obtuvo la serie para registrar el pedido.");
        }

        if ($banderaRegistroMovil == ServicioEncomiendaNegocio::TIPO_RECOJO_DOMICILIO && $comprobanteTipoId == 6) {
            $serieDefecto = $dataAperturaCaja[0]['serie_boleta'];
            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_boleta'];
            if (ObjectUtil::isEmpty($serieDefecto)) {
                throw new WarningException("La serie para boleta que pertenece a esta caja esta vacio.");
            }

            if (ObjectUtil::isEmpty($correlativoDefecto)) {
                throw new WarningException("El correlativo para boleta que pertenece a esta caja esta vacio.");
            }
        }

        if ($banderaRegistroMovil == ServicioEncomiendaNegocio::TIPO_RECOJO_DOMICILIO && $comprobanteTipoId == 7) {
            $serieDefecto = $dataAperturaCaja[0]['serie_factura'];
            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_factura'];

            if (ObjectUtil::isEmpty($serieDefecto)) {
                throw new WarningException("La serie para factura que pertenece a esta caja esta vacio.");
            }

            if (ObjectUtil::isEmpty($correlativoDefecto)) {
                throw new WarningException("El correlativo para factura que pertenece a esta caja esta vacio.");
            }
        }
//        $contacto = $documento['contacto'];
//        if (!ObjectUtil::isEmpty($contacto)) {
//            if (count($contacto) > 2) {
//                mostrarValidacionLoaderClose("Puede seleccionar como máximo a 2 destinatarios opcionales.");
//                return;
//            }
//            $contacto = implode(",", $contacto);
//        }

        $montoDevolucion = (!ObjectUtil::isEmpty($documento['monto_devolucion_cargo']) ? $documento['monto_devolucion_cargo'] * 1 : 0);
        $montoCostoReparto = (!ObjectUtil::isEmpty($documento['monto_costo_reparto']) ? $documento['monto_costo_reparto'] * 1 : 0);
        $montoCostoRecojoDomicilio = (!ObjectUtil::isEmpty($documento['monto_recojo_domicilio']) ? $documento['monto_recojo_domicilio'] * 1 : 0);
        $montoAjustePrecio = (!ObjectUtil::isEmpty($documento['ajuste_precio']) ? $documento['ajuste_precio'] * 1 : 0);
        $montoSubTotal = (!ObjectUtil::isEmpty($documento['monto_subtotal']) ? $documento['monto_subtotal'] * 1 : 0);
        $montoIgv = (!ObjectUtil::isEmpty($documento['monto_igv']) ? $documento['monto_igv'] * 1 : 0);
        $montoTotal = (!ObjectUtil::isEmpty($documento['monto_total']) ? $documento['monto_total'] * 1 : 0);

        if (strpos($direccionClienteId, 'A') === false && (ObjectUtil::isEmpty($montoCostoRecojoDomicilio) || $montoCostoRecojoDomicilio <= 0)) {
            throw new WarningException("El pedido debe tener un monto de recojo a domicilio, verifique que la zona de recojo tenga un tarifario asignado");
        }

        if (strpos($direccionDestinatarioId, 'A') === false && (ObjectUtil::isEmpty($montoCostoReparto) || $montoCostoReparto <= 0)) {
            throw new WarningException("El pedido debe tener un monto de reparto, verifique que la zona de reparto tenga un tarifario asignado");
        }

        $montoCaculo = 0;

        foreach ($detalle as $item) {
            $subTotal = ($item['subTotal'] * 1);
            if (ObjectUtil::isEmpty($item['precio']) || $item['precio'] == 0) {
                throw new WarningException("No se especificó un valor válido para precio de " . $item['bienDesc']);
            }

            if (ObjectUtil::isEmpty($item['cantidad']) || $item['cantidad'] <= 0) {
                throw new WarningException("No se especificó un valor válido para cantidad de " . $item['bienDesc']);
            }

            // if (ObjectUtil::isEmpty($subTotal) || $subTotal <= 0) {
            $subTotal = (float) $item['precio'] * (float) $item['cantidad'];
            //}

            $montoCaculo = $montoCaculo + ($subTotal);
        }

        if ($documento['bandera_devolucion_cargo'] == 1 && (ObjectUtil::isEmpty($montoDevolucion) || $montoDevolucion <= 0)) {
            throw new WarningException("Este pedido no tiene un monto por la devolución de cargo, verifique con el área responsable para configurar el tarifario correspondiente.");
        }

        if (round($montoIgv + $montoSubTotal, 2) != round($montoTotal, 2)) {
            throw new WarningException("La suma entre el igv y el sub total no coincide con monto total del pedido");
        }

        if (round($montoCaculo + $montoAjustePrecio + $montoCostoRecojoDomicilio + $montoCostoReparto + $montoDevolucion, 2) != round($montoTotal, 2)) {
            throw new WarningException("La sumatoria del detalle más lo costos adicionales, no coincide con el total del documento");
        }


        return self::registrarEncomienda($opcionId, $usuarioId, $documento, $detalle);
    }

    public function registrarEncomienda($opcionId, $usuarioId, $documento, $detalle) {

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_BOLETA) {
            $comprobanteTipoId = 6;
        }

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA) {
            $comprobanteTipoId = 7;
        }

        $documento['comprobanteTipoId'] = $comprobanteTipoId;
        $documento['documentoTipoId'] = 190; // TIPO PEDIDO
        $documento['monedaId'] = 2; // MONEDA
        $documento['fechaEmision'] = date('d/m/Y');

        $fechaEmision = date('Y-m-d');
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fechaEmision);

        $documento['periodoId'] = $dataPeriodo[0]['id'];
        $documento['afectoAImpuesto'] = 1;

        $direccionClienteId = $documento['clienteDireccionId'];
        $direccionDestinatarioId = $documento['destinatarioDireccionId'];
        if (strpos($direccionClienteId, 'A') !== false) {
            $documento['clienteDireccionId'] = NULL;
            $direccionClienteId = NULL;
        }

        if (strpos($direccionDestinatarioId, 'A') !== false) {
            $documento['destinatarioDireccionId'] = NULL;
            $direccionDestinatarioId = NULL;
        }

        $facturadoRuc = $documento['facturado_ruc'];
        $facturadoNombre = $documento['facturado_nombre'];

        if ($comprobanteTipoId == 6) {
            $documento['personaId'] = $documento['clienteId'];
            $documento['personaDireccionId'] = $direccionClienteId;
        } elseif (ObjectUtil::isEmpty($facturadoRuc)) {
            $documento['personaId'] = $documento['clienteId'];
            $documento['personaDireccionId'] = $direccionClienteId;
        } else {
            $dataPersonaFacturado = PersonaNegocio::create()->obtenerPersonaXCodigoIdentificacion($facturadoRuc);
            if (!ObjectUtil::isEmpty($dataPersonaFacturado)) {
                $documento['personaId'] = $dataPersonaFacturado[0]['id'];
                $dataPersonaDireccionFacturada = PersonaNegocio::create()->obtenerPersonaDireccionXPersonaId($dataPersonaFacturado[0]['id']);
                foreach ($dataPersonaDireccionFacturada as $item) {
                    if ($item['direccion_tipo_id'] == -1) {
                        $documento['personaDireccionId'] = $item['id'];
                        break;
                    }
                }
            } else {
                $tipoDocumentoId = 3;
                $contactoTipo = 5;
                $respuestaPersonaFacturado = PersonaNegocio::create()->registrarPersonaContactos($tipoDocumentoId, $facturadoRuc,
                        $facturadoNombre, NULL, NULL, NULL, NULL, NULL, $usuarioId, NULL, $contactoTipo);

                $documento['personaId'] = $respuestaPersonaFacturado['persona_id'];
            }
        }

        foreach ($detalle as $index => $item) {
            if ($item['tipo'] == ServicioEncomiendaNegocio::TIPO_PAQUETE) {
                $detalle[$index]['bienId'] = -1;
            }
            $detalle[$index]['unidadMedidaId'] = -1;
            $detalle[$index]['precioTipoId'] = -1;
            $detalle[$index]['organizadorId'] = null;
            $detalle[$index]['precioCompra'] = 0;
            $detalle[$index]['precioCompra'] = 0;
        }


        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoVirtualXAgenciaId($documento['agenciaOrigenId']);
        $serieSubFijo = $dataAperturaCaja[0]['sufijo_comprobante'];
        $seriePedido = "P" . $serieSubFijo;

        $documento["serie"] = $seriePedido;
        $documentoTipoPedidoId = 190;
        $correlativoPedido = DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($documentoTipoPedidoId, $seriePedido);
        $documento["numero"] = $correlativoPedido;

        $documento['acCajaId'] = $dataAperturaCaja[0]['ac_caja_id'];
        $documento['comprobanteId'] = -1;
        $banderaRegistroMovil = $documento['bandera_registro_movil'];

        if (!ObjectUtil::isEmpty($documento['destinatarioDireccionId'])) {
            $documento['tipoPedidoId'] = 76;
        } else {
            $documento['tipoPedidoId'] = 77;
        }

        $dataPago = array();
        
        if ($banderaRegistroMovil == ServicioEncomiendaNegocio::TIPO_RECOJO_DOMICILIO) {
            $documento['documentoEstadoId'] = 8;
        } elseif ($banderaRegistroMovil == ServicioEncomiendaNegocio::TIPO_AGENCIA) {
            $documento['documentoEstadoId'] = 17;
        }

        $respuestaRegistroPedido = PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documento, $detalle, $dataPago);

        

        return array(array("vout_exito" => 1, "vout_mensaje" => "Registrado correctamente", "documentoId" => $respuestaRegistroPedido->documentoId, "documentoSerieNumero" => $respuestaRegistroPedido->documentoSerieNumero . '-' . $usuarioId));
    }

    public function registrarComprobantePago($opcionId, $usuarioId, $documento, $detalle) {

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_BOLETA) {
            $comprobanteTipoId = 6;
        }

        if ($documento['comprobanteDescripcion'] == ServicioEncomiendaNegocio::TIPO_COMPROBANTE_FACTURA) {
            $comprobanteTipoId = 7;
        }

        $documento['documentoTipoId'] = $comprobanteTipoId; // TIPO 
        $documento['monedaId'] = 2; // MONEDA
        $documento['fechaEmision'] = date('d/m/Y');

        $fechaEmision = date('Y-m-d');
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fechaEmision);

        $documento['periodoId'] = $dataPeriodo[0]['id'];
        $documento['afectoAImpuesto'] = 1;

        $direccionClienteId = $documento['clienteDireccionId'];
        $direccionDestinatarioId = $documento['destinatarioDireccionId'];
        if (strpos($direccionClienteId, 'A') !== false) {
            $documento['clienteDireccionId'] = NULL;
            $direccionClienteId = NULL;
        }

        if (strpos($direccionDestinatarioId, 'A') !== false) {
            $documento['destinatarioDireccionId'] = NULL;
            $direccionDestinatarioId = NULL;
        }

        $facturadoRuc = $documento['facturado_ruc'];
        $facturadoNombre = $documento['facturado_nombre'];

        if ($comprobanteTipoId == 6) {
            $documento['personaId'] = $documento['clienteId'];
            $documento['personaDireccionId'] = $direccionClienteId;
        } elseif (ObjectUtil::isEmpty($facturadoRuc)) {
            $documento['personaId'] = $documento['clienteId'];
            $documento['personaDireccionId'] = $direccionClienteId;
        } else {
            $dataPersonaFacturado = PersonaNegocio::create()->obtenerPersonaXCodigoIdentificacion($facturadoRuc);
            if (!ObjectUtil::isEmpty($dataPersonaFacturado)) {
                $documento['personaId'] = $dataPersonaFacturado[0]['id'];
                $dataPersonaDireccionFacturada = PersonaNegocio::create()->obtenerPersonaDireccionXPersonaId($dataPersonaFacturado[0]['id']);
                foreach ($dataPersonaDireccionFacturada as $item) {
                    if ($item['direccion_tipo_id'] == -1) {
                        $documento['personaDireccionId'] = $item['id'];
                        break;
                    }
                }
            } else {
                $tipoDocumentoId = 3;
                $contactoTipo = 5;
                $respuestaPersonaFacturado = PersonaNegocio::create()->registrarPersonaContactos($tipoDocumentoId, $facturadoRuc,
                        $facturadoNombre, NULL, NULL, NULL, NULL, NULL, $usuarioId, NULL, $contactoTipo);

                $documento['personaId'] = $respuestaPersonaFacturado['persona_id'];
            }
        }

        foreach ($detalle as $index => $item) {
            if ($item['tipo'] == ServicioEncomiendaNegocio::TIPO_PAQUETE) {
                $detalle[$index]['bienId'] = -1;
            }
            $detalle[$index]['unidadMedidaId'] = -1;
            $detalle[$index]['precioTipoId'] = -1;
            $detalle[$index]['organizadorId'] = null;
            $detalle[$index]['precioCompra'] = 0;
            $detalle[$index]['precioCompra'] = 0;
        }


        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoVirtualXAgenciaId($documento['agenciaOrigenId']);
        $documento['acCajaId'] = $dataAperturaCaja[0]['ac_caja_id'];
        if ($comprobanteTipoId == 6) {
            $serieDefecto = $dataAperturaCaja[0]['serie_boleta'];
            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_boleta'];
        }

        if ($comprobanteTipoId == 7) {
            $serieDefecto = $dataAperturaCaja[0]['serie_factura'];
            $correlativoDefecto = $dataAperturaCaja[0]['correlativo_factura'];
        }

        $correlativo = DocumentoNegocio::create()->obtenerNumeroAutoXDocumentoTipo($comprobanteTipoId, $serieDefecto);
        if ((int) $correlativoDefecto > (int) $correlativo) {
            $correlativo = $correlativoDefecto;
        }
        $documento["serie"] = $serieDefecto;
        $documento["numero"] = $correlativo;

        if (!ObjectUtil::isEmpty($documento['destinatarioDireccionId'])) {
            $documento['tipoPedidoId'] = 76;
        } else {
            $documento['tipoPedidoId'] = 77;
        }

        $montoTotal = (!ObjectUtil::isEmpty($documento['monto_total']) ? $documento['monto_total'] * 1 : 0);
        $dataPago = array();
        if (!ObjectUtil::isEmpty($documento['trama_pago']) && !ObjectUtil::isEmpty($documento['pago_fecha'])) {

            $tramaPago = json_decode($documento['trama_pago']);
            $codigoOperacion = $tramaPago->dataMap->ID_UNICO;

            $documentoTipoPagoId = 285;
            $camposDinamicosPagoTemporal = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoPagoId, $usuarioId);

            foreach ($camposDinamicosPagoTemporal as $index => $itemDtd) {
                // asignamos la data necesaria para que cargue los componentes
                switch ($itemDtd["tipo"]) {
                    case DocumentoTipoNegocio::DATO_NUMERO :
                        $camposDinamicosPagoTemporal[$index]['valor'] = $codigoOperacion;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION :
                        $camposDinamicosPagoTemporal[$index]['valor'] = $documento['pago_fecha'];
                        break;
                    case DocumentoTipoNegocio::DATO_IMPORTE_TOTAL:
                        $camposDinamicosPagoTemporal[$index]['valor'] = $montoTotal;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $camposDinamicosPagoTemporal[$index]['valor'] = $documento['personaId'];
                        break;
                }
            }



            $documentoPago = Array();

            $documentoPago['documento_tipo_id'] = $documentoTipoPagoId;
            $documentoPago['documento_tipo_descripcion'] = 'Pasarela de pago';
            $documentoPago['monto'] = $montoTotal;
            $documentoPago['data'] = $camposDinamicosPagoTemporal;

            $dataPago['camposDinamicosPago'] = array($documentoPago);
            $dataPago['documentoTipoIdPago'] = $documentoTipoPagoId;
            $dataPago['cliente'] = $documento['personaId'];
            $dataPago['tipoCambio'] = NULL;
            $dataPago['fecha'] = $documento['fechaEmision'];
            $dataPago['actividadEfectivo'] = NULL;
            $dataPago['montoAPagar'] = $montoTotal;
            $dataPago['totalPago'] = $montoTotal;
            $dataPago['totalDocumento'] = $montoTotal;
        }

        $documento['channel'] = 'movil';
        $respuestaRegistroComprobante = PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documento, $detalle, $dataPago, true);
        $comprobanteId = $respuestaRegistroComprobante->documentoId;
        $pedidoId = $documento['documentoOrigenId'];
        //Actualización de datos adicionales comprobante
        DetalleQrNegocio::create()->insertarXComprobanteId($comprobanteId, $usuarioId);
        Documento::create()->actualizarTramaPago($comprobanteId, $documento['trama_pago']);
        PedidoNegocio::create()->enviarCorreoComprobante('', $comprobanteId, $usuarioId);
        
        //Actualización de datos adicionales Pedido
        Documento::create()->actualizarComprobanteId($pedidoId, $comprobanteId); 
        $documentoEstado = 16;
        DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($pedidoId, $documentoEstado, $usuarioId);
                    
        return array(array("vout_exito" => 1, "vout_mensaje" => "Registrado correctamente", "documentoId" => $respuestaRegistroComprobante->documentoId, "documentoSerieNumero" => $respuestaRegistroComprobante->documentoSerieNumero . '-' . $usuarioId));
    }

    public function obtenerParametrosNiubiz() {

       $token = self::consultarToken(); 
       //$pinCerificate = "sha256/D6rSeGVZdgfsMVIUabjeGDzS7YvLVp7pbnRhCggz/B4=";        
       // $merchantid = "341198210";
       $merchantid = ConfigGlobal::PARAM_MERCHANTID_NIUBIZ; 
       $pinToken = self::consultarApiCertificado($token);
       $pinCerificate = "sha256/".$pinToken->pinHash;
        return array("token" => $token,
            "endPoint" => ConfigGlobal::PARAM_URL_NIUBIZ_END_POINT,
            "certificatePin" => ($pinCerificate),
            "certificateHost" => ConfigGlobal::PARAM_URL_NIUBIZ_PIN_CERTIFICATE_HOST,
            "merchantid" => $merchantid);
    }

    public function consultarToken() {

        $user = ConfigGlobal::PARAM_USER_NIUBIZ;
        $clave = ConfigGlobal::PARAM_PASS_NIUBIZ;
        $cadenaUsuario = $user . ':' . $clave;
        $token = base64_encode($cadenaUsuario);
        //$token2 = 'aW50ZWdyYWNpb25lc0BuaXViaXouY29tLnBlOl83ejNAOGZG';
        //ITINERARIO DE BUSES
        $url = ConfigGlobal::PARAM_URL_NIUBIZ_TOKEN;
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic " . $token
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result == 'Bad Request') {
            throw new WarningException("No se pudo generar el token de seguridad, revisar creedenciales de acceso");
        } else {
            return $result;
        }
    }

    public function consultarApiCertificado($token) {


        $url = ConfigGlobal::PARAM_URL_NIUBIZ_PIN_CERTIFICATE . ConfigGlobal::PARAM_MERCHANTID_NIUBIZ;

        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: " . $token
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result == 'Bad Request') {
            throw new WarningException("No se pudo generar el token de seguridad, revisar creedenciales de acceso");
        } else {
            return json_decode($result);
        }
    }

}
