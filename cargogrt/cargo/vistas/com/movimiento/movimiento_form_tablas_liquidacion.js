var boton = {
    enviarClase: $('#env i').attr('class'),
    accion: ''
};
var validacion = {
    organizadorExistencia: true
};
var camposDinamicos = [];
var importes = {
    fleteId: null,
    seguroId: null,
    otrosId: null,
    exoneracionId: null,
    totalId: null,
    subTotalId: null,
    igvId: null,
    calculoId: null,
    icbpId: null
};
var cabecera = {
    chkDocumentoRelacion: 1
};
var bandera = {
    primeraCargaDocumentosRelacion: true,
    mostrarDivDocumentoRelacion: false,
    validacionAnticipos: 0
};
var request = {
    documentoRelacion: [] // Ids de documentos a relacionar
};
var variable = {
    documentoIdCopia: null,
    movimientoIdCopia: null
};
var dataTemporal = {
    anticipos: null
}

var tipoInterfaz = document.getElementById("tipoInterfaz").value;
var documentoId = document.getElementById("documentoId").value;
var distribucionObligatoria = 0;

let fechaTrasladoMayor = null;
let fechaTrasladoMenor = null;

$(document).ready(function () {
    datePiker.iniciarPorClase('fecha');
    //initSwitch();
    loaderShow();
    ax.setSuccess("onResponseMovimientoFormTablas");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoId", documentoId);
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.consumir();

    select2.iniciarElemento("cboUnidadMedida");
    select2.iniciarElemento("cboTipoPago");
});

function onResponseMovimientoFormTablas(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerUnidadMedida':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerUnidadesMedida(response.data);
                loaderClose();
                break;
            case 'obtenerPrecioUnitario':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerPrecioUnitario(response.data);
                loaderClose();
                break;
            case 'obtenerSelectPersonas':
                onResponseObtenerPersonaActivoXStringBusqueda(response.data, response.tag);
                break;
            case 'obtenerDocumentoTipoDato':
                onResponseObtenerDocumentoTipoDato(response.data);
                loaderClose();
                break;
            case 'enviar':
            case 'enviarEdicion':
                let accion = response[PARAM_TAG];
                if (accion == "confirmar") {
                    $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
                    $('.modal-backdrop').hide();
                }
                onResponseEnviar(response.data);
                break;
            case 'enviarEImprimir':
                cargarDatosImprimir(response.data, 1);
                break;
            case 'obtenerPersonas':
                onResponseObtenerPersonas(response.data);
                break;
            case 'obtenerStockPorBien':
                onResponseObtenerStockPorBien(response.data, response[PARAM_TAG]);
                break;
            case 'obtenerPrecioPorBien':
                onResponseObtenerPrecioPorBien(response.data, response[PARAM_TAG]);
                break;
            case 'obtenerStockActual':
                response.data[0]["indice"] = response[PARAM_TAG];
                onResponseObtenerStockActual(response.data);
                break;
            case 'obtenerPersonaDireccion':
                onResponseObtenerPersonaDireccion(response.data);
                break;
            case 'guardarDocumentoGenerado':
                if (boton.accion == 'enviarEImprimir') {
                    cargarDatosImprimir(response.data, 1);
                } else {
                    cargarPantallaListar();
                }
                break;
            case 'obtenerPreciosEquivalentes':
                onResponseObtenerPreciosEquivalentes(response.data);
                loaderClose();
                break;
            case 'obtenerBienPrecio':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerBienPrecio(response.data);
                loaderClose();
                break;
            case 'buscarDocumentoRelacion':
                onResponseBuscarDocumentoRelacion(response.data);
                loaderClose();
                break;
//FUNCIONES PARA COPIAR DOCUMENTO
            case 'obtenerConfiguracionBuscadorDocumentoRelacion':
                onResponseObtenerConfiguracionBuscadorDocumentoRelacion(response.data);
                buscarDocumentoRelacionPorCriterios();
                loaderClose();
                break;
            case 'obtenerDocumentoRelacion':
                onResponseObtenerDocumentoRelacion(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionNotaVenta':
                onResponseObtenerDocumentoRelacionTodos(response.data);
                loaderClose();
                break;

            case 'obtenerDocumentoRelacionCabecera':
                onResponseObtenerDocumentoRelacionCabecera(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                onResponseObtenerDocumentoRelacionVisualizar(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionDetalle':
                cargarDetalleDocumentoRelacion(response.data);
                loaderClose();
                break;
            case 'verificarTipoUnidadMedidaParaTramo':
                if (!isEmpty(response.data)) {
                    response.data["indice"] = response[PARAM_TAG];
                }
                onResponseVerificarTipoUnidadMedidaParaTramo(response.data);
                loaderClose();
                break;
            case 'registrarTramoBien':
                onResponseRegistrarTramoBien(response.data);
                loaderClose();
                break;
            case 'obtenerTramoBien':
                onResponseObtenerTramoBien(response.data, response[PARAM_TAG]);
                loaderClose();
                break;
            case 'obtenerPrecioCompra':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerPrecioCompra(response.data);
                loaderClose();
                break;
            case 'modificarDetallePrecios':
                onResponseModificarDetallePrecios(response.data);
                break;
            case 'modificarDetallePreciosXMonedaXOpcion':
                onResponseModificarDetallePreciosXMonedaXOpcion(response.data);
                loaderClose();
                break;
            case 'obtenerTipoCambioXFecha':
                onResponseObtenerTipoCambioXFecha(response.data);
                loaderClose();
                break;
            case 'enviarCorreosMovimiento':
                loaderClose();
                $('#modalCorreos').modal('hide');
                $('.modal-backdrop').hide();
                cargarPantallaListar();
                break;
            case 'eliminarPDF':
                loaderClose();
                cargarPantallaListar();
                break;
            case 'obtenerNumeroNotaCredito':
                onResponseObtenerNumeroNotaCredito(response.data);
                loaderClose();
                break;

                // pagos            
            case 'obtenerDocumentoTipoDatoPago':
                onResponseObtenerDocumentoTipoDatoPago(response.data);
                break;
            case 'guardarDocumentoPago':
                $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
                $('.modal-backdrop').hide();
                onResponseEnviar(response.data);
                break;
            case 'guardarDocumentoAtencionSolicitud':
                $("modalAsignarAtencion").modal('hide');
                $('.modal-backdrop').hide();
                onResponseEnviar(response.data);
            case 'obtenerProductos':
                onResponseObtenerProductos(response.data, response[PARAM_TAG]);
                loaderClose();
                break;
            case 'obtenerStockParaProductosDeCopia':
                onResponseObtenerStockParaProductosDeCopia(response.data);
                loaderClose();
                break;
            case 'obtenerDireccionOrganizador':
                onResponseObtenerDireccionOrganizador(response.data, response[PARAM_TAG]);
                loaderClose();
                break;

        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                break;
            case 'obtenerUnidadMedida':
                break;
            case 'enviar':
                cerrarModalAnticipo();
                loaderClose();
                habilitarBoton();
                onResponseEnviarError(response['message']);
                break;
            case 'enviarEImprimir':
                loaderClose();
                habilitarBoton();
                break;
            case 'obtenerDocumentoRelacion':
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                loaderClose();
                break;
            case 'guardarDocumentoGenerado':
                habilitarBoton();
                loaderClose();
            case 'enviarCorreosMovimiento':
                loaderClose();
                break;
            case 'eliminarPDF':
                loaderClose();
                cargarPantallaListar();
                break;
            case 'guardarDocumentoPago':
                loaderClose();
                habilitarBoton();
                onResponseEnviarError(response['message']);
                break;
        }
    }
}

function onResponseEnviarError(mensaje) {

    //ERROR CONTROLADO SUNAT
    if (mensaje.indexOf("[Cod: IMA02]") != -1) {
        swal("Error controlado", mensaje, "error");
    } else if (mensaje.indexOf("[Cod: IMAEX") != -1) {
        swal("Error no controlado", mensaje, "error");
    }

}

function onResponseObtenerPersonaDireccion(data) {
    if (personaDireccionId !== 0) {
        onResponseObtenerDataCbo("_" + personaDireccionId, "id", "direccion", data);
    }
    if (textoDireccionId !== 0) {
        onResponseObtenerPersonaDireccionTexto(data);
    }
}

function onResponseObtenerDataCbo(cboId, itemId, itemDes, data) {

    document.getElementById('cbo' + cboId).innerHTML = "";

    select2.asignarValor('cbo' + cboId, "");
    //$('#cbo' + cboId).append('<option value=0>Seleccione la dirección</option>');
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            $('#cbo' + cboId).append('<option value="' + item[itemId] + '">' + item[itemDes] + '</option>');
        });

        select2.asignarValor('cbo' + cboId, data[0]["id"]);
    } else {
        select2.asignarValor('cbo' + cboId, 0);
    }
}

function onResponseObtenerStockActual(data) {
    var stock = parseFloat(data[0]['stock']);
    var cantidadMinima = parseFloat(data[0]['cantidad_minima']);
    var indice = parseInt(data[0]['indice']);
    var saldo = 0;

    var indexTemporal = -1;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    detalle[indexTemporal]["stockBien"] = stock;

    var cantidad = 0;
    if (existeColumnaCodigo(12)) {
        cantidad = parseFloat($('#txtCantidad_' + indice).val());
    }

    if (detalle[indexTemporal]["bienTipoId"] != -1) {
        if (movimientoTipoIndicador == 1 || dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
            saldo = stock + cantidad;
        } else if (movimientoTipoIndicador == 2) {
            saldo = stock - cantidad;
        } else {
            saldo = stock;
        }
    } else {
        saldo = 0;
    }

    var bienId = null;
    if (existeColumnaCodigo(11)) {
        bienId = select2.obtenerValor("cboBien_" + indice);
    }
    if (bienId == -1) {
        saldo = 0;
    }
    if (!isEmpty(bienId)) {
        if (existeColumnaCodigo(7)) {
            $('#txtStock_' + indice).html(devolverDosDecimales(saldo));
        }
        if (existeColumnaCodigo(8)) {
            $('#txtStockSugerido_' + indice).html(devolverDosDecimales(cantidadMinima));
        }
    }
}

function hallarStockSaldo(indice) {
//    var indLista = indiceLista.indexOf(parseInt(indice));   
    var indexTemporal = -1;
    var bandera = false;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    if (indexTemporal != -1 && !isEmpty(detalle[indexTemporal]["stockBien"])) {
        var stock = detalle[indexTemporal]["stockBien"];
        var bienTipoId = detalle[indexTemporal]["bienTipoId"];
        var cantidad = 0;
        if (existeColumnaCodigo(12)) {
            cantidad = parseFloat($('#txtCantidad_' + indice).val());
        }
        var saldo = 0;

        //breakFunction();
        if (isEmpty(stock))
            stock = 0;
        if (bienTipoId != -1) {
            if (movimientoTipoIndicador == 1 || dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                saldo = stock + cantidad;
                bandera = true;
            } else if (movimientoTipoIndicador == 2) {
                saldo = stock - cantidad;
                bandera = true;
            } else {
                saldo = stock;
                bandera = false;
            }
        } else {
            saldo = 0;
        }

        var bienId = null;
        if (existeColumnaCodigo(11)) {
            bienId = select2.obtenerValor("cboBien_" + indice);
        }
        if (bienId == -1) {
            saldo = 0;
        }
        if (!isEmpty(bienId)) {
            if (existeColumnaCodigo(7) && bandera) {
                $('#txtStock_' + indice).html(devolverDosDecimales(saldo));
            }
        }
    }
}

function obtenerDocumentoTipoDato(documentoTipoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentoTipoDato");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

function obtenerUnidadMedida(bienId, indice) {

    var unidadMedidaId = 0;
    if (banderaCopiaDocumento === 1) {
        unidadMedidaId = detalle[indice].unidadMedidaId;
    }

    if (existeColumnaCodigo(13)) {
        $("#cboUnidadMedida_" + indice).empty();
        select2.readonly("cboUnidadMedida_" + indice, true);
    }
    ax.setAccion("obtenerUnidadMedida");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    if (existeColumnaCodigo(4)) {
        ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo_" + indice));
    } else {
        ax.addParamTmp("precioTipoId", null);
    }
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
//    ax.addParamTmp("incluyeIGV", incluyeIGV);
    ax.setTag(indice);
    ax.consumir();
}
function onResponseObtenerUnidadesMedida(data, index) {
    //INDEX: SI LA DATA VIENE DE LA COPIA
    if (isEmpty(data.indice)) {
        data.indice = index;
    }

    if (!isEmpty(data) && !isEmpty(data.unidad_medida) && existeColumnaCodigo(13)) {
        select2.cargar("cboUnidadMedida_" + data.indice, data.unidad_medida, "id", "simbolo");
        select2.asignarValor("cboUnidadMedida_" + data.indice, data.unidad_medida[0].id);
        select2.readonly("cboUnidadMedida_" + data.indice, false);

        if (banderaCopiaDocumento === 0) {
            setearUnidadMedidaDescripcion(data.indice);
        }
    }


    var operador = obtenerOperador();

    if (existeColumnaCodigo(1)) {
        $("#txtPrecioCompra_" + data.indice).html(devolverDosDecimales(data.precioCompra * operador));
    } else {
        varPrecioCompra = devolverDosDecimales(data.precioCompra * operador);
    }
//    obtenerStockActual(data.indice);
    if (banderaCopiaDocumento === 1) {
        obtenerUtilidades(data.indice);
        obtenerUtilidadesGenerales();
        if (existeColumnaCodigo(13)) {
            select2.asignarValor("cboUnidadMedida_" + data.indice, detalle[data.indice].unidadMedidaId);
            if (unidadMedidaTxt === 1) {
                setearUnidadMedidaDescripcion(data.indice);
            }
        }

//        obtenerStockActual(data.indice);
    } else if (banderaCopiaDocumento === 0) {
        obtenerStockActual(data.indice);
        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data.precio * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }

    if (existeColumnaCodigo(13)) {
        $("#cboUnidadMedida_" + data.indice).select2({width: anchoUnidadMedidaTD + 'px'});
    }
}

function obtenerOperador() {
    var operadorIGV = 1.18;
    if (!isEmpty(importes.subTotalId)) {
        if (!document.getElementById('chkIncluyeIGV').checked) {
            operadorIGV = 1;
        }
    } else if (opcionIGV == 0) {
        operadorIGV = 1;
    }

    return operadorIGV;
}

function onResponseObtenerPreciosEquivalentes(data) {
    if (!isEmpty(data)) {
        var operador = obtenerOperador();

        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + data.indice).html(devolverDosDecimales(data.precioCompra * operador));
        } else {
            varPrecioCompra = devolverDosDecimales(data.precioCompra * operador);
        }
        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data.precio * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

function onResponseObtenerBienPrecio(data) {
    if (!isEmpty(data)) {
        var operador = obtenerOperador();

        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data[0].precio * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

function obtenerPrecioUnitario(indice) {
    loaderShow();

    var bienId = select2.obtenerValor("cboBien_" + indice);
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida_" + indice);

    ax.setAccion("obtenerPrecioUnitario");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.setTag(indice);
    ax.consumir();
}

function devolverDosDecimales(num) {
//    return Math.round(num * 100) / 100;
//    breakFunction();
    return redondearNumero(num).toFixed(2);
//      return redondearNumero(num);
}

function onResponseObtenerPrecioUnitario(data) {
    if (!isEmpty(data)) {
        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data.precio);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

var organizadorIdDefectoTM;
var movimientoTipoIndicador;
var dataDocumentoTipo;
var dataContOperacionTipo = [];
var inicialAlturaDetalle;
var documentoTipoTipo;
var monedaSimbolo;

var opcionIGV = null;
//var valorOpcionIGV;
var monedaBase = null;
var monedaBaseId = 0;
var cboDetraccionId = null;
var montoTotalDetraido = 0;
var montoTotalRetencion = 0;
function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.documento_tipo)) {
        dataCofiguracionInicial = data;

        $("#cboDocumentoTipo").select2({
            width: "100%"
        }).on("change", function (e) {
            importes.totalId = null;
            importes.subTotalId = null;
            importes.igvId = null;
            importes.calculoId = null;
            importes.otrosId = null;
            importes.exoneracionId = null;
            cboDetraccionId = null;
            percepcionId = 0;
            montoTotalDetraido = 0;
            montoTotalRetencion = 0;
            nroFilasInicial = parseInt(dataDocumentoTipo[document.getElementById('cboDocumentoTipo').options.selectedIndex]['cantidad_detalle']);
            documentoTipoTipo = parseInt(dataDocumentoTipo[document.getElementById('cboDocumentoTipo').options.selectedIndex]['tipo']);
            opcionIGV = parseInt(dataDocumentoTipo[document.getElementById('cboDocumentoTipo').options.selectedIndex]['opcion_igv']);

            $('#txtComentario').val(dataDocumentoTipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].comentario_defecto);

            $("#contenedorChkIncluyeIGV").hide();
            $("#contenedorTotalDiv").hide();
            $("#contenedorSubTotalDiv").hide();
            $("#contenedorPercepcionDiv").hide();
            $("#contenedorIgvDiv").hide();
            $("#contenedorCambioPersonalizado").hide();

            $("#contenedorFleteDiv").hide();
            $("#contenedorSeguroDiv").hide();

            $("#contenedorExoneracionDiv").hide();
            $("#contenedorOtrosDiv").hide();
            $("#contenedorIcbpDiv").hide();

            //serie y numero            
            $("#contenedorSerieDiv").hide();
            $("#contenedorNumeroDiv").hide();

            //tipo pago
            $("#divContenedorTipoPago").hide();

            obtenerDocumentoTipoDato(e.val);

            if (nroFilasInicial > 50) {
                $('#divTodasFilas').hide();
            } else {
                $('#divTodasFilas').show();
            }

        });

        $('#divContenedorDsco').show();

        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.cargar("cboPenalidadMotivo", data.penalidad, "id", "descripcion");
        select2.cargar("cboPeriodo", data.periodo, "id", ["mes_nombre", "anio"]);
        select2.asignarValorQuitarBuscador('cboPeriodo');
        select2.asignarValor('cboPenalidadMotivo');

        $.each(data.moneda, function (i, itemMoneda) {
            if (itemMoneda.base == 1) {
                monedaBase = itemMoneda.id;
                monedaSimbolo = itemMoneda.simbolo;
                monedaBaseId = itemMoneda.id;
            }
        });


        if (isEmpty(monedaBase)) {
            monedaBase = data.moneda[0].id;
            monedaSimbolo = data.moneda[0].simbolo;
        }

        select2.asignarValorQuitarBuscador("cboMoneda", monedaBase);
        $("#cboMoneda").select2({
            width: "100%"
        }).on("change", function (e) {
            visualizarCambioPersonalizado(e.val);

            if (isEmpty(detalle)) {
                modificarSimbolosMoneda(e.val, data.moneda[document.getElementById('cboMoneda').options.selectedIndex]);
            }
            modificarPreciosMoneda(e.val, data.moneda[document.getElementById('cboMoneda').options.selectedIndex])
        });

        //moneda por defecto de documento tipo
        var monedaDefectoId = data.documento_tipo[0].moneda_id;
        if (!isEmpty(monedaDefectoId)) {
            $.each(data.moneda, function (i, itemMoneda) {
                if (itemMoneda.id == monedaDefectoId) {
                    monedaBase = monedaDefectoId;
                    monedaSimbolo = itemMoneda.simbolo;

                    select2.asignarValorQuitarBuscador("cboMoneda", monedaDefectoId);
                }
            });
        }

        dataDocumentoTipo = data.documento_tipo;
        documentoTipoTipo = dataDocumentoTipo[0]['tipo'];
        opcionIGV = dataDocumentoTipo[0]['opcion_igv'];


        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.cargar("cboAgenciaOrigenModal", data.dataAgencia, "agencia_id", "codigo");
        select2.cargar("cboAgenciaDestinoModal", data.dataAgencia, "agencia_id", "codigo");
        select2.asignarValor("cboAgenciaOrigenModal");
        select2.asignarValor("cboAgenciaDestinoModal");

        if (isEmpty(data.movimientoTipo[0].documento_tipo_defecto_id)) {
            select2.asignarValor("cboDocumentoTipo", data.documento_tipo[0].id);
        } else {
            select2.asignarValor("cboDocumentoTipo", data.movimientoTipo[0].documento_tipo_defecto_id);
        }

        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipo", true);
        }
        onResponseObtenerDocumentoTipoDato(data.documento_tipo_conf);

        nroFilasReducida = 5;
        inicialAlturaDetalle = $("#contenedorDetalle").height();
        $("#contenedorDetalle").css("height", $("#contenedorDetalle").height() + 38 * nroFilasReducida);
        organizadorIdDefectoTM = data.movimientoTipo[0].organizador_defecto;

        //simbolo moneda
//        monedaSimbolo=dataCofiguracionInicial.movimientoTipo[0].moneda_simbolo
        nroFilasInicial = parseInt(dataDocumentoTipo[0]['cantidad_detalle']);

        if (nroFilasInicial > 50) {
            $('#divTodasFilas').hide();
        }

        movimientoTipoIndicador = data.movimientoTipo[0].indicador;
//        if(documentoTipoTipo==1){
//            $("#contenedorUtilidadesTotales").show();
//        }
        if (isEmpty(data.organizador)) {
            muestraOrganizador = false;
        }

        $('#txtComentario').val(data.documento_tipo[0].comentario_defecto);

        llenarMonedaSimboloTotales();
//        llenarCabeceraDetalle();
//        llenarTablaDetalle(data);

//        if (!isEmpty(dataCofiguracionInicial.movimientoTipoColumna)) {
        $('#datatable').DataTable({
            "scrollX": true,
            "paging": false,
            "info": false,
            "filter": false,
            "ordering": false,
//                "autoWidth": true,
            "destroy": true
        });

        $('#datatablePenalidad').DataTable({
            "scrollX": true,
            "paging": false,
            "info": false,
            "filter": false,
            "ordering": false,
//                "autoWidth": true,
            "destroy": true
        });

        dibujarBotonesDeEnvio(data);

        //llenar organizador en cabecera
        llenarComboOrganizadorCabecera(data.organizador);

        visualizarCambioPersonalizado(monedaBase);

        var banderaMostrarMoneda = true;
//        $.each(dataCofiguracionInicial.movimientoTipoColumna, function (index, objeto) {
//            if (objeto.codigo == 5) {
//                banderaMostrarMoneda = true;
//            }
//        });

        if (banderaMostrarMoneda == false) {
            $("#contenedorMoneda").hide();
        }

        obtenerDireccionOrganizador(1);

        if (!isEmpty(data.dataDocumento)) {
            let documentoFechaEmision = data.dataDocumento[0]['fecha_emision'];
            let documentoMonedaId = data.dataDocumento[0]['moneda_id'];
            let documentoPeriodoId = data.dataDocumento[0]['periodo_id'];
            let documentoPersonaId = data.dataDocumento[0]['persona_id'];
            let documentoComprobanteTipoId = data.dataDocumento[0]['comprobante_tipo_id'];
            let documentoComentario = data.dataDocumento[0]['comentario'];
            clienteValorAnteriorId = documentoPersonaId;

            let documentoSerieId = data.dataDocumento[0]['serie'];
            let documentoNumeroId = data.dataDocumento[0]['numero'];

            let dtdPersonaId = obtenerDocumentoTipoDatoIdXTipo("5");
            let dtdFechaEmisionId = obtenerDocumentoTipoDatoIdXTipo("9");

            let dtdSerieId = obtenerDocumentoTipoDatoIdXTipo("7");
            let dtdNumeroId = obtenerDocumentoTipoDatoIdXTipo("8");

            select2.asignarValor("cbo_" + dtdPersonaId, documentoPersonaId);

            select2.asignarValor("cboPeriodo", documentoPeriodoId);
            select2.asignarValorTrigger("cboMoneda", documentoMonedaId);

            $('#datepicker_' + dtdFechaEmisionId).val(datex.parserFecha(documentoFechaEmision));
            $('#txt_' + dtdSerieId).val(documentoSerieId);
            $('#txt_' + dtdNumeroId).val(documentoNumeroId);
            $('#txtComentario').val(documentoComentario);


            let dtdComboComprobante = obtenerDocumentoTipoDatoXTipoXCodigo("4", "0");

            if (!isEmpty(dtdComboComprobante.data)) {
                let itemComboComprobante = dtdComboComprobante.data.filter(item => item.valor == documentoComprobanteTipoId);
                select2.asignarValor("cbo_" + dtdComboComprobante['id'], itemComboComprobante[0]['id']);
            }
        } else {
            loaderClose();
        }

        if (!isEmpty(data.dataDocumentoPenalidad)) {
            detallePenalidad = data.dataDocumentoPenalidad;
            dibujarTablaPenalidad();
        }

        if (!isEmpty(data.dataDocumentoRelacion)) {
            let documentosRelacionados = [];
            $.each(data.dataDocumentoRelacion, function (index, item) {
                documentosRelacionados.push({"documentoId": item.documento_relacionado_id, "movimientoId": item.documento_relacion_movimiento_id});
            });
//            loaderShow();
            ax.setAccion("obtenerDocumentoRelacionNotaVenta");
            ax.addParamTmp("documentoPorRelacionar", documentosRelacionados);
            ax.consumir();
        }

    }

    doc_TipoId = select2.obtenerValor("cboDocumentoTipo");
}

function habilitarComboTipoPago() {
    if (documentoTipoTipo == 1 || documentoTipoTipo == 4) {
        $("#divContenedorTipoPago").show();
    } else {
        $("#divContenedorTipoPago").hide();
    }
}

var organizadorIdAntes = null;
function llenarComboOrganizadorCabecera(data) {
    if (muestraOrganizador) {
        $("#divContenedorOrganizador").show();

        $("#cboOrganizador").select2({
            width: "100%"
        }).on("change", function (e) {
            confirmarCambioOrganizador();
            //PARA OBTENER LA DIRECCION DE ORGANIZADOR
            obtenerDireccionOrganizador(1);
        });

        select2.cargar("cboOrganizador", data, "id", "descripcion");

        if (organizadorIdDefectoTM != 0)
            select2.asignarValor("cboOrganizador", organizadorIdDefectoTM);
        else
            select2.asignarValor("cboOrganizador", data[0].id);

        organizadorIdAntes = select2.obtenerValor("cboOrganizador");
    }
}

function obtenerDireccionOrganizador(origenDestino) {
    //origenDestino => ORIGEN: 1 DESTINO:2
    var organizadorId = null;
    var dtd = null;
    if (origenDestino == 1) {
        organizadorId = select2.obtenerValor('cboOrganizador');
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 8);
    } else {
        organizadorId = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(17));
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 9);
    }

    if (!isEmpty(dtd) && !isEmpty(organizadorId)) {
        loaderShow();
        ax.setAccion("obtenerDireccionOrganizador");
        ax.addParamTmp("organizadorId", organizadorId);
        ax.setTag(origenDestino);
        ax.consumir();
    }
}

function onResponseObtenerDireccionOrganizador(data, origenDestino) {
    var dtd = null;
    if (origenDestino == 1) {
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 8);
    } else {
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 9);
    }

    if (!isEmpty(dtd)) {
        $('#txt_' + dtd.id).val(data[0].direccion);
    }
}

function confirmarCambioOrganizador() {
    if (existeColumnaCodigo(7)) {
        if (!isEmpty(detalle)) {
            swal({
                title: "Confirmación de cambio de almacén",
                text: "¿Está seguro de cambiar el almacén, se va a actualizar el stock?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#33b86c",
                confirmButtonText: "Si, actualizar!",
                cancelButtonColor: '#d33',
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    organizadorIdAntes = select2.obtenerValor("cboOrganizador");
                    actualizarStockDetalle();
                } else {
                    select2.asignarValor("cboOrganizador", organizadorIdAntes);
                }
            });
        } else {
            organizadorIdAntes = select2.obtenerValor("cboOrganizador");
        }
    }
}

function actualizarStockDetalle() {
    var varOrganizadorId = select2.obtenerValor("cboOrganizador");

    $.each(detalle, function (indice, item) {
        detalle[indice].organizadorId = varOrganizadorId;
        obtenerStockActual(item.index);
    });
}

function llenarMonedaSimboloTotales() {
//    $('#contenedorTotalDiv').append("<median class='text-uppercase' id='simTotal'>" + monedaSimbolo + "</median>");
//    $('#percepcionDescripcion').append("<median class='text-uppercase' id='simPercepcion'>" + monedaSimbolo + "</median>");
//    $('#contenedorIgvDiv').append("<median class='text-uppercase' id='simIGV'>" + monedaSimbolo + "</median>");
//    $('#contenedorSubTotalDiv').append("<median class='text-uppercase' id='simSubTotal'>" + monedaSimbolo + "</median>");
//    $('#totalUtilidadDescripcion').append(" " + "<median class='text-uppercase' id='simTotalUtildiad'>" + monedaSimbolo + "</median>");
//    $('#contenedorFleteDiv').append("<median class='text-uppercase' id='simFlete'>" + monedaSimbolo + "</median>");
//    $('#contenedorSeguroDiv').append("<median class='text-uppercase' id='simSeguro'>" + monedaSimbolo + "</median>");
//    $('#contenedorOtrosDiv').append("<median class='text-uppercase' id='simOtros'>" + monedaSimbolo + "</median>");
//    $('#contenedorExoneracionDiv').append("<median class='text-uppercase' id='simExonerado'>" + monedaSimbolo + "</median>");
//    $('#contenedorIcbpDiv').append("<median class='text-uppercase' id='simIcbp'>" + monedaSimbolo + "</median>");

    $('#contenedorTotalDivText').append("<median class='text-uppercase' id='simTotal'>" + monedaSimbolo + "</median>");
    $('#divContenedorDscoText').append("<median class='text-uppercase' id='simDescuento'>" + monedaSimbolo + "</median>");
    $('#percepcionDescripcion').append("<median class='text-uppercase' id='simPercepcion'>" + monedaSimbolo + "</median>");
    $('#contenedorIgvDivText').append("<median class='text-uppercase' id='simIGV'>" + monedaSimbolo + "</median>");
    $('#contenedorSubTotalDivText').append("<median class='text-uppercase' id='simSubTotal'>" + monedaSimbolo + "</median>");

    $('#contenedorGratuitaDivText').append("<median class='text-uppercase' id='simGratuito'>" + monedaSimbolo + "</median>");
    $('#contenedorGravadaDivText').append("<median class='text-uppercase' id='simGravado'>" + monedaSimbolo + "</median>");
    $('#contenedorInafectaDivText').append("<median class='text-uppercase' id='simInafecto'>" + monedaSimbolo + "</median>");
    $('#contenedorExoneradoDivText').append("<median class='text-uppercase' id='simExonerado'>" + monedaSimbolo + "</median>");

    $('#totalUtilidadDescripcion').append(" " + "<median class='text-uppercase' id='simTotalUtildiad'>" + monedaSimbolo + "</median>");
    $('#otrosGastosDescripcion').append("<median class='text-uppercase' id='simOtrosGastos'>" + monedaSimbolo + "</median>");

}

function llenarCabeceraDetalle() {
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    $("#headDetalleCabecera").empty();
    var fila = "<tr>";

    //columnas dinamicas de acuerdo a documento_tipo_columna
    var anchoDinamicoTabla = 0;//;500;//941-> 1041px

    fila += "<th style='text-align:center; width: 20px;'>#</th>"; // # Item
    anchoDinamicoTabla += parseInt(40);

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.codigo)) {
                case 1://Precio de compra
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + " <div id='simPC' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 2:// Utilidad moneda                    
                    if (documentoTipoTipo == 1) {
                        fila += "<th style='text-align:center;width: " + item.ancho + "px;'>" + item.descripcion + "  <div id='simUD' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                        anchoDinamicoTabla += parseInt(item.ancho);
                    }
                    break;
                case 3:// Utilidad porcentaje                    
                    if (documentoTipoTipo == 1) {
                        fila += "<th style='text-align:center;width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);
                    }
                    break;
                case 4:// 87px TIPO PRECIO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 5:// 69px PRECIO UNITARIO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "<div id='simPU' style='display: inline-table;'>" + monedaSimbolo + "</div></th> ";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 6:// 47px SUB TOTAL
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "<div id='simST' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 7:// 40px STOCK
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 8:// 86px STOCK MINIMO
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);

                        $('#divTodasFilas').hide();
                    }
                    break;
                case 9:// 59px PRIORIDAD
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);

                        $('#divTodasFilas').hide();
                    }
                    break;
                case 10:// 14px COLUMNA EN BLANCO
                    fila += "<th style='text-align:center; border:0; width: " + item.ancho + "px;' bgcolor='#FFFFFF'></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 11:// 310 PRODUCTO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" +
                            "<a href='#' title='Nuevo producto' onclick='cargarBien()'>&nbsp;&nbsp;<i class='fa fa-plus-circle' style='color:#1ca8dd'></i></a>&nbsp;&nbsp;" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 12:// 47 CANTIDAD
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 13:// 71 UNIDAD DE MEDIDA
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 14:// 40 ACCIONES
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 15:// 47 Organizador
                    if (muestraOrganizador && organizadorIdDefectoTM == 0) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    }
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 16://descripcion de producto
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 17://descripcion de unidad de medida
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 18://fecha de vencimiento
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 21://comentario
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;' class='claseMostrarColumnaComentario'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
            }
        });
    }

    fila = fila + "</tr>";


    if (documentoTipoTipo == 1 && existeColumnaCodigo(2) && existeColumnaCodigo(3)) {
        $("#contenedorUtilidadesTotales").show();
    }

    if (!isEmpty(dataColumna)) {
        if (anchoDinamicoTabla > 1195) {
            $("#datatable").width(anchoDinamicoTabla + 2 * dataColumna.length);
        }
    }
    $('#datatable thead').append(fila);
}

var muestraOrganizador = true;
var dataCofiguracionInicial;
var nroFilasInicial;
var nroFilasReducida;
var alturaBienTD;
var anchoUnidadMedidaTD;
var anchoTipoPrecioTD;
function llenarTablaDetalle(data) {
    var nroFilas = nroFilasReducida;
    //NUEVO   

    var cuerpo = "";

    //LLENAR TABLA DETALLE
    for (var i = 0; i < nroFilas; i++) {
        //var i=0;
        cuerpo += llenarFilaDetalleTabla(i);
    }

    $('#datatable tbody').append(cuerpo);

    if (existeColumnaCodigo(11)) {
        alturaBienTD = $("#tdBien_" + (nroFilas - 1)).width();
    }
    if (existeColumnaCodigo(4)) {
        anchoTipoPrecioTD = $("#tdTipoPrecio_" + (nroFilas - 1)).width();
    }
    if (existeColumnaCodigo(13)) {
        anchoUnidadMedidaTD = $("#tdUnidadMedida_" + (nroFilas - 1)).width();
    }

    //LLENAR COMBOS
    for (var i = 0; i < nroFilas; i++) {
        cargarOrganizadorDetalleCombo(data.organizador, i);
        cargarUnidadMedidadDetalleCombo(i);
        cargarBienDetalleCombo(data.bien, i);
        cargarPrecioTipoDetalleCombo(data.precioTipo, i);
        inicializarFechaVencimiento(i);
    }

}

var banderaVerTodasFilas = 0;
function verTodasFilas() {

    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height() + 38 * (nroFilasInicial - nroFilasReducida));

    //NUEVO

    var cuerpo = "";

    //LLENAR TABLA DETALLE
    for (var i = nroFilasReducida; i < nroFilasInicial; i++) {
        //var i=0;
        cuerpo += llenarFilaDetalleTabla(i);
    }

    $('#datatable tbody').append(cuerpo);

    //LLENAR COMBOS
    for (var i = nroFilasReducida; i < nroFilasInicial; i++) {
        cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, i);
        cargarUnidadMedidadDetalleCombo(i);
        cargarBienDetalleCombo(dataCofiguracionInicial.bien, i);
        cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, i);
        inicializarFechaVencimiento(i);
    }

    nroFilasReducida = nroFilasInicial;
    $('#divTodasFilas').hide();
    if (dataCofiguracionInicial.documento_tipo[0].cantidad_detalle != 0 && !isEmpty(dataCofiguracionInicial.documento_tipo[0].cantidad_detalle)) {
        $('#divAgregarFila').hide();
    }

    banderaVerTodasFilas = 1;
//    loaderClose();
}

function inicializarFechaVencimiento(indice) {
    $('#txtFechaVencimiento_' + indice).datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    }).on('changeDate', function (ev) {
        actualizarTotalesGenerales(parseInt(indice));
    });
}

function obtenerStockActual(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerStockActual");
        var organizadorId = null;
        if (organizadorIdDefectoTM == 0) {
            if (!existeColumnaCodigo(15)) {
                if (muestraOrganizador) {
                    organizadorId = select2.obtenerValor('cboOrganizador');
                }
            } else {
                organizadorId = select2.obtenerValor("cboOrganizador_" + indice);
            }
        } else {
            organizadorId = organizadorIdDefectoTM;
        }

        //ALMACEN DE LLEGADA PARA TRANSFERENCIA INTERNA
        var organizadorDestinoId = null;
        if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
            var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
            if (!isEmpty(dtdOrganizadorId)) {
                organizadorDestinoId = select2.obtenerValor('cbo_' + dtdOrganizadorId);
            }
        }

        ax.addParamTmp("organizadorId", organizadorId);
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", select2.obtenerValor("cboBien_" + indice));
        ax.addParamTmp("organizadorDestinoId", organizadorDestinoId);
        ax.setTag(indice);
        ax.consumir();
    }
}

function obtenerPreciosEquivalentes(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerPreciosEquivalentes");
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", select2.obtenerValor("cboBien_" + indice));
        if (existeColumnaCodigo(4)) {
            ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo_" + indice));
        } else {
            ax.addParamTmp("precioTipoId", null);
        }
//        ax.addParamTmp("monedaId", dataCofiguracionInicial.movimientoTipo[0].moneda_id);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
        ax.addParamTmp("indice", indice);
        ax.consumir();
    }
}

function obtenerBienPrecio(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerBienPrecio");
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", bienId);
        if (existeColumnaCodigo(4)) {
            ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo_" + indice));
        } else {
            ax.addParamTmp("precioTipoId", null);
        }
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.setTag(indice);
        ax.consumir();
    }
}

function cargarOrganizadorDetalleCombo(data, i) {
    if (!isEmpty(data) && existeColumnaCodigo(15)) {
        $("#cboOrganizador_" + i).select2({
            width: "100%"
        }).on("change", function (e) {
            indiceBien = i;

            obtenerStockActual(indiceBien);
            hallarSubTotalDetalle(indiceBien);
        });
        select2.cargar("cboOrganizador_" + i, data, "id", "descripcion");
        if (organizadorIdDefectoTM != 0)
            select2.asignarValor("cboOrganizador_" + i, organizadorIdDefectoTM);
        else
            select2.asignarValor("cboOrganizador_" + i, data[0].id);

    } else {
        $("#contenedorOrganizador_" + i).hide();
        validacion.organizadorExistencia = false;
    }
}

var indiceBien;
var primeraFechaEmision;
var banderaPrimeraFE = true;
function cargarBienDetalleCombo(data, indice) {
    //loaderShow();

    if (!isEmpty(data)) {
        $("#cboBien_" + indice).select2({
            width: '100%'
        }).on("change", function (e) {
            indiceBien = indice;
            banderaCopiaDocumento = 0;
            bienTramoId = null;

            loaderShow();

            if (documentoTipoTipo == 1 && banderaPrimeraFE) {
                primeraFechaEmision = $('#datepicker_' + fechaEmisionId).val();
                banderaPrimeraFE = false;
            }
//            alert(primeraFechaEmision);

            if (existeColumnaCodigo(4)) {
                select2.asignarValor("cboPrecioTipo_" + indice, precioTipoPrimero);
                $("#cboPrecioTipo_" + indice).select2({width: anchoTipoPrecioTD + 'px'});
            }
            obtenerUnidadMedida(e.val, indice);
            setearDescripcionProducto(indice);

            if (existeColumnaCodigo(21)) {
                $('#txtComentarioDetalle_' + indice).removeAttr("readonly");
            }
        });
        select2.cargar("cboBien_" + indice, data, "id", ["codigo_barra", "codigo", "descripcion"]);
        select2.asignarValor("cboBien_" + indice, 0);
        if (existeColumnaCodigo(13)) {
            select2.readonly("cboUnidadMedida_" + indice, true);
        }
        $("#cboBien_" + indice).select2({width: alturaBienTD + "px"});
    }

}

function cargarUnidadMedidadDetalleCombo(indice) {
    $("#cboUnidadMedida_" + indice).select2({
        width: "100%"
    }).on("change", function (e) {
        indiceBien = indice;

        obtenerPreciosEquivalentes(indice);
        obtenerStockActual(indice);
        setearUnidadMedidaDescripcion(indice);
    });

    $("#cboUnidadMedida_" + indice).select2({width: anchoUnidadMedidaTD + "px"});
}

var precioTipoPrimero;
function cargarPrecioTipoDetalleCombo(data, indice) {
    if (existeColumnaCodigo(4)) {
        $("#cboPrecioTipo_" + indice).select2({
            width: "100%"
        }).on("change", function (e) {
            obtenerBienPrecio(indice);
        });

        if (!isEmpty(data)) {
            select2.cargar("cboPrecioTipo_" + indice, data, "precio_tipo_id", "precio_tipo_descripcion");
            precioTipoPrimero = data[0]["precio_tipo_id"];

//        select2.asignarValor("cboPrecioTipo_" + indice, data[0]["precio_tipo_id"]);
        }

        $("#cboPrecioTipo_" + indice).select2({width: anchoTipoPrecioTD + "px"});
    }
}

var KPADINGTD = 1;
function llenarFilaDetalleTabla(indice) {
    numeroItemFinal++;
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    var fila = "<tr id=\"trDetalle_" + indice + "\">";

    fila = fila + "<td style='border:0; width: 20px; vertical-align: middle; padding-right: 10px;'id=\"txtNumItem_" + indice + "\" name=\"txtNumItem_" + indice + "\" align='right'>" + numeroItemFinal + "</td>";

    //columnas dinamicas de acuerdo a documento_tipo_columna
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.codigo)) {
                case 1://Precio de compra
                    fila = fila + "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'id=\"txtPrecioCompra_" + indice + "\" name=\"txtPrecioCompra_" + indice + "\" align='right'></td>";
                    break;
                case 2:// Utilidad moneda                    
                    if (documentoTipoTipo == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtUtilidadSoles_" + indice + "\" name=\"txtUtilidadSoles_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 3:// Utilidad porcentaje                    
                    if (documentoTipoTipo == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtUtilidadPorcentaje_" + indice + "\" name=\"txtUtilidadPorcentaje_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 4:// Tipo de precio
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdTipoPrecio_" + indice + "\">" + agregarPrecioTipoTabla(indice) + "</td>";
                    break;
                case 5://Precio unitario
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarPrecioUnitarioDetalleTabla(indice) + "</td>";
                    break;
                case 6://Sub total detalle
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtSubTotalDetalle_" + indice + "\" name=\"txtSubTotalDetalle_" + indice + "\" align='right'></td>";
                    break;
                case 7://Stock
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtStock_" + indice + "\" name=\"txtStock_" + indice + "\" align='right'></td>";
                    break;
                case 8://Stock minimo
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtStockSugerido_" + indice + "\" name=\"txtStockSugerido_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 9://Prioridad
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtPrioridad_" + indice + "\" name=\"txtPrioridad_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 10://Columna en blanco
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' bgcolor='#FFFFFF'></td>";
                    break;
                case 11://Producto
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdBien_" + indice + "\">" + agregarBienDetalleTabla(indice) + "</td>";
                    break;
                case 12://Cantidad
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarCantidadDetalleTabla(indice) + "</td>";
                    break;
                case 13://Unidad de medida
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdUnidadMedida_" + indice + "\">" + agregarUnidadMedidaDetalleTabla(indice) + "</td>";
                    break;
                case 14://Acciones
                    fila += "<td style='border:0; width: " + item.ancho + "px; text-align:center; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarAccionesDetalleTabla(indice) + "</td>"
                    break;
                case 15://Organizador
                    if (muestraOrganizador && organizadorIdDefectoTM == 0) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarOrganizadorDetalleTabla(indice) + "</td>";
                    }
                    break;
                case 16://Producto descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarProductoDescripcionDetalleTabla(indice, item.longitud) + "</td>";
                    break;
                case 17://Unidad de medida descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarUnidadMedidaDescripcionDetalleTabla(indice, item.longitud) + "</td>";
                    break;
                case 18://Unidad de medida descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarFechaVencimientoDetalleTabla(indice) + "</td>";
                    break;
                case 21://Comentario
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' class='claseMostrarColumnaComentario'>" + agregarComentarioDetalleTabla(indice, item.longitud) + "</td>";
                    break;
            }
        });
    }

    fila = fila + "</tr>";

    return fila;
}

function agregarAccionesDetalleTabla(i) {
    var $html = '<div class="btn-toolbar" role="toolbar">' +
            '&nbsp;<a onclick=\"confirmarEliminar(' + i + ');\">' +
            '<i class=\"fa fa-trash-o\" style=\"color:#cb2a2a;\" title=\"Eliminar\"></i></a>' +
            '<div class="btn-group" style="float: right">' +
            '<a  class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" onclick=\"eliminarOverflowDataTable();\">' +
            '<i class="ion-gear-a"></i>  <span class="caret"></span>' +
            '</a>' +
            '<ul class="dropdown-menu dropdown-menu-right">' +
            '<li>' +
            '<a onclick=\"verificarTipoUnidadMedida(' + i + ');\">' +
            '<i class=\"ion-ios7-toggle\"   style=\"color:#1ca8dd;\"   title=\"Registrar tramo\"></i>&nbsp;Registrar tramo</a>' +
            '</li>' +
            '<li>' +
            '<a onclick=\"listarTramosBien(' + i + ');\">' +
            '<i class=\"fa  fa-tasks\"    style=\"color:#615ca8;\"  title=\"Listar tramo\"></i>&nbsp;Listar tramo</a>' +
            '</li>' +
            '<li>' +
            '<a onclick=\"verificarStockBien(' + i + ');\">' +
            '<i class=\"fa fa-cubes\"    style=\"color:#5cb85c;\" title=\"Verificar stock\"></i>&nbsp;Verificar stock</a>' +
            '</li>' +
            '<li>' +
            '<a onclick=\"verificarPrecioBien(' + i + ');\">' +
            '<i class=\"ion-pricetag\"  title=\"Ver precio\"></i>&nbsp;Precio mínimo</a>' +
            '</li>' +
            '<li>' +
            '<a onclick=\"relacionarActivoFijo(' + i + ');\">' +
            '<i class=\"ion-android-share\"  style=\"color:#E8BA2F;\" title=\"Relacionar activo fijo\"></i>&nbsp;Relacionar activo fijo</a>' +
            '</li>' +
            '</ul>' +
            '</div>' +
            '</div>';
    return $html;
}

function eliminarOverflowDataTable() {
}

function agregarOverflowDataTable() {
}

$('#window').click(function (e) {
    var valor = e.target.className;

    if (valor != 'dropdown-toggle' && valor != 'ion-gear-a' && valor != 'caret') {
        agregarOverflowDataTable();
    }

});

function agregarOrganizadorDetalleTabla(i) {
    var $html = "<div id=\"contenedorOrganizador_" + i + "\">" +
            "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboOrganizador_" + i + "\" id=\"cboOrganizador_" + i + "\" class=\"select2\">" +
            "</select></div></div>";

    return $html;
}

function agregarCantidadDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"number\" id=\"txtCantidad_" + i + "\" name=\"txtCantidad_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"1\" style=\"text-align: right;\" onchange=\"hallarSubTotalDetalle(" + i + ");hallarStockSaldo(" + i + ");\" onkeyup =\"hallarSubTotalDetalle(" + i + ");hallarStockSaldo(" + i + ");\"/></div>";

    return $html;
}

function agregarProductoDescripcionDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"text\" id=\"txtProductoDescripcion_" + i + "\" name=\"txtProductoDescripcion_" + i + "\" maxlength='" + longitud + "' readonly='true' class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarComentarioDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12 claseMostrarColumnaComentario\">" +
            "<input  type=\"text\" id=\"txtComentarioDetalle_" + i + "\" name=\"txtComentarioDetalle_" + i + "\" maxlength='" + longitud + "' readonly='true'  class=\"form-control claseMostrarColumnaComentario\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarUnidadMedidaDescripcionDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"text\" id=\"txtUnidadMedidaDescripcion_" + i + "\" name=\"txtUnidadMedidaDescripcion_" + i + "\" maxlength='" + longitud + "' readonly='true' class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarFechaVencimientoDetalleTabla(i) {
    var fecha = obtenerFechaActual();

    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaVencimiento_' + i + '" value="' + fecha + '">' +
            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>'
            + "</div>";

    return $html;
}

function agregarPrecioUnitarioDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"number\" id=\"txtPrecio_" + i + "\" name=\"txtPrecio_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: right;\" onchange=\"hallarSubTotalDetalle(" + i + ")\" onkeyup =\"hallarSubTotalDetalle(" + i + ")\"/></div>";

    return $html;
}


function hallarSubTotalDetalle(indice) {
    indice = parseInt(indice);
    //obtenerStockActual(indice)

    //hallar totales genereales y subtotal detalle:
    actualizarTotalesGenerales(indice);

    //hallar utilidades detalles
    obtenerUtilidades(indice);

    //hallar utilidades generales
    obtenerUtilidadesGenerales();
}

function obtenerUtilidadesGenerales() {
    if (documentoTipoTipo == 1 && existeColumnaCodigo(2) && existeColumnaCodigo(3)) {

        var totalUtilidadesSoles = 0;
        var utilidadSoles = 0;

        var totalDocumento = parseFloat($('#' + importes.totalId).val());
        if (isEmpty(totalDocumento)) {
            totalDocumento = calcularImporteDetalle();
        }
        var totalUtilidadesPorcentaje = 0;

        if (totalDocumento != 0) {

            for (var i = 0; i < nroFilasInicial; i++) {
                utilidadSoles = parseFloat($('#txtUtilidadSoles_' + i).html());
                if (isEmpty(utilidadSoles) || !esNumero(utilidadSoles)) {
                    utilidadSoles = 0;
                }

                totalUtilidadesSoles = totalUtilidadesSoles + utilidadSoles;
            }

            totalUtilidadesPorcentaje = (totalUtilidadesSoles / totalDocumento) * 100;
        }


        $('#txtTotalUtilidadSoles').val(devolverDosDecimales(totalUtilidadesSoles));
        $('#txtTotalUtilidadPorcentaje').val(devolverDosDecimales(totalUtilidadesPorcentaje) + " %");

        document.getElementById('txtTotalUtilidadSoles').style.color = '#FF0000';
        document.getElementById('txtTotalUtilidadPorcentaje').style.color = '#FF0000';


        //guardar en array detalle utilidades general en primera fila     
        if (!isEmpty(detalle[0])) {
            detalle[0].utilidadTotal = totalUtilidadesSoles;
            detalle[0].utilidadPorcentajeTotal = totalUtilidadesPorcentaje;
        }
        //fin guardar utilidad 
    }
}

function obtenerUtilidades(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (documentoTipoTipo == 1 && bienId != -1 && !isEmpty(bienId) &&
            existeColumnaCodigo(1) && existeColumnaCodigo(5) && existeColumnaCodigo(6) && existeColumnaCodigo(12)) {
        if (!valoresFormularioDetalle)
            return;

        var precioVenta = $('#txtPrecio_' + indice).val();
        var precioCompra = $('#txtPrecioCompra_' + indice).html();
        var cantidad = $('#txtCantidad_' + indice).val();

        var utilidadSoles = (precioVenta - precioCompra) * cantidad;

        var subTotal = $('#txtSubTotalDetalle_' + indice).html();
        var utilidadPorcentaje = 0;
        if (subTotal != 0) {
            utilidadPorcentaje = (utilidadSoles / subTotal) * 100;
        }

        if (existeColumnaCodigo(2)) {
            $('#txtUtilidadSoles_' + indice).html(devolverDosDecimales(utilidadSoles));
            document.getElementById('txtUtilidadSoles_' + indice).style.color = '#FF0000';
        }
        if (existeColumnaCodigo(3)) {
            $('#txtUtilidadPorcentaje_' + indice).html(devolverDosDecimales(utilidadPorcentaje) + " %");
            document.getElementById('txtUtilidadPorcentaje_' + indice).style.color = '#FF0000';
        }

        //guardar en array detalle

        var indexTemporal = -1;
        if (existeColumnaCodigo(2) || existeColumnaCodigo(3)) {
            $.each(detalle, function (i, item) {
                if (parseInt(item.index) === parseInt(indice)) {
                    indexTemporal = i;
                    return false;
                }
            });
        }

        if (indexTemporal > -1) {
            if (existeColumnaCodigo(2)) {
                detalle[indexTemporal].utilidad = utilidadSoles;
            }
            if (existeColumnaCodigo(3)) {
                detalle[indexTemporal].utilidadPorcentaje = utilidadPorcentaje;
            }
        }
        //fin guardar utilidad detalle
    }
}


var indiceLista = [];

function actualizarTotalesGenerales(indice) {
    valoresFormularioDetalle = validarFormularioDetalleTablas(indice);
    bienTramoId = null;
    if (!valoresFormularioDetalle)
        return;

    var subTotal = valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio;

    if (existeColumnaCodigo(6)) {
        $('#txtSubTotalDetalle_' + indice).html(devolverDosDecimales(subTotal));
    }

    valoresFormularioDetalle.subTotal = subTotal;
    valoresFormularioDetalle.index = indice;

    var indexTemporal = -1;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    //ASIGNO EL VALOR DE COMPRA EN CASO NO ESTE CONFIGURADA LA COLUMNA COMPRA  
    if (!existeColumnaCodigo(1)) {
        var precioCompraTemp = 0;
        if (indexTemporal > -1) {
            if (!isEmpty(detalle[indexTemporal].precioCompra)) {
                precioCompraTemp = detalle[indexTemporal].precioCompra;
            }
        }

//        if(varPrecioCompra != 0){
        if (indexTemporal > -1) {
            if (detalle[indexTemporal].bienId != valoresFormularioDetalle.bienId || varPrecioCompra != 0) {
                valoresFormularioDetalle.precioCompra = varPrecioCompra;
            } else {
                valoresFormularioDetalle.precioCompra = precioCompraTemp;
            }
        } else {
            valoresFormularioDetalle.precioCompra = varPrecioCompra;
        }

        varPrecioCompra = 0;
    }

    if (indexTemporal > -1) {
        detalle[indexTemporal] = valoresFormularioDetalle;
    } else {
        detalle[detalle.length] = valoresFormularioDetalle;
    }
    asignarImporteDocumento();
}

function eliminarDetalleFormularioListas(indiceDet) {
    /*var indice = indiceLista.indexOf(indiceDet);
     if (indice > -1) {    
     indiceLista.splice(indice, 1);
     detalle.splice(indice, 1);
     }*/
    if (indiceDet > -1) {
        //indiceLista.splice(indiceDet, 1);
        detalle.splice(indiceDet, 1);
    }
}

var varPrecioCompra = 0;

function validarFormularioDetalleTablas(indice) {

    //obtener los datos del detalle dinamico
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;
    var validar = true;
    var objDetalle = {};//Objeto para el detalle    
    var correcto = true;
    var valor;
    var detDetalle = [];

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {

            valor = null;
            if (item.opcional == 1) {
                validar = false;
            } else {
                validar = true;
            }
            correcto = true;

            //obtener los datos del detalle
            switch (parseInt(item.codigo)) {
                case 1:
//                    valor = document.getElementById("txtPrecioCompra_" + indice).value;
                    valor = $('#txtPrecioCompra_' + indice).html();
                    objDetalle.precioCompra = valor;
                    break;
                    break;

                    //numeros
                case 5:// PRECIO UNITARIO
                    valor = document.getElementById("txtPrecio_" + indice).value;
                    objDetalle.precio = valor;
                    break;
                case 12:// CANTIDAD
                    valor = document.getElementById("txtCantidad_" + indice).value;
                    objDetalle.cantidad = valor;
                    break;

                    //combos, seleccion
                case 4:// TIPO PRECIO
                    valor = select2.obtenerValor("cboPrecioTipo_" + indice);
                    objDetalle.precioTipoId = valor;
                    break;
                case 11:// PRODUCTO
                    valor = select2.obtenerValor("cboBien_" + indice);
                    objDetalle.bienId = valor;
                    objDetalle.bienDesc = select2.obtenerText("cboBien_" + indice);
                    break;
                case 13:// UNIDAD DE MEDIDA
                    valor = select2.obtenerValor("cboUnidadMedida_" + indice);
                    objDetalle.unidadMedidaId = valor;
                    objDetalle.unidadMedidaDesc = select2.obtenerText("cboUnidadMedida_" + indice);
                    break;
                case 15:// Organizador
                    if (validacion.organizadorExistencia && organizadorIdDefectoTM == 0) {
                        valor = select2.obtenerValor("cboOrganizador_" + indice);
                        objDetalle.organizadorDesc = select2.obtenerText("cboOrganizador_" + indice);
                    } else {
                        valor = organizadorIdDefectoTM;
                    }
                    objDetalle.organizadorId = valor;
                    break;

                case 21://comentario
                    valor = $('#txtComentarioDetalle_' + indice).val();
                    objDetalle.comentarioDetalle = valor;
                    break;

                    //texto
                case 16://descripcion de producto          
                    valor = $('#txtProductoDescripcion_' + indice).val();
                    detDetalle.push({columnaCodigo: 16, valorDet: valor});
                    break;
                case 17://descripcion de unidad de medida
                    valor = $('#txtUnidadMedidaDescripcion_' + indice).val();
                    detDetalle.push({columnaCodigo: 17, valorDet: valor});
                    break;

                    //fechas
                case 18://fecha vencimiento
                    valor = $('#txtFechaVencimiento_' + indice).val();
                    detDetalle.push({columnaCodigo: 18, valorDet: valor});
                    break;
            }

            //validar los valores del detalle
            switch (parseInt(item.codigo)) {
                //numeros
                case 5:// PRECIO UNITARIO                
                    if ((isEmpty(valor) || !esNumero(valor) || valor < 0) && validar) {
                        $('#txtPrecio_' + indice).val(0.00);
                        mostrarValidacionLoaderClose("Debe ingresar: " + item.descripcion + " válida, en fila " + (indice + 1));
                        correcto = false;
                    }
                    break;
                case 12:// CANTIDAD                    
                    if ((isEmpty(valor) || !esNumero(valor) || valor <= 0) && validar) {
                        $('#txtCantidad_' + indice).val(1);
                        mostrarValidacionLoaderClose("Debe ingresar: " + item.descripcion + " válida, en fila " + (indice + 1));
                        correcto = false;
                    }
                    break;

                    //combos, seleccion
                case 4:// TIPO PRECIO
                case 11:// PRODUCTO                  
                case 13:// UNIDAD DE MEDIDA       
                case 15:// Organizador        
                    if (isEmpty(valor) && validar) {
                        mostrarValidacionLoaderClose("Seleccione: " + item.descripcion + ", en fila " + (indice + 1));
                        correcto = false;
                    }
                    break;

                    //texto 
                case 16://descripcion de producto   
                case 17://descripcion de unidad de medida                                                      
                    if (isEmpty(valor) && validar) {
                        mostrarValidacionLoaderClose("Ingrese: " + item.descripcion + ", en fila " + (indice + 1));
                        correcto = false;
                    }
                    break;

                    //fecha 
                case 18: // fecha vencimiento                                                     
                    if (isEmpty(valor) && validar) {
                        mostrarValidacionLoaderClose("Ingrese: " + item.descripcion + ", en fila " + (indice + 1));
                        correcto = false;
                    }
                    break;
            }

            if (!correcto) {
                return correcto;
            }
        });
    } else {
        mostrarValidacionLoaderClose("Falta configurar las columnas del detalle");
        return false;
    }

    if (!existeColumnaCodigo(15)) {
        if (muestraOrganizador) {
            objDetalle.organizadorId = select2.obtenerValor('cboOrganizador');
        }
    }

    if (!correcto) {
        return correcto;
    }

    //fin columna dinamica        
    var stockBien = 0;
    var bienTipoId = 0;

    var indexTemporal = -1;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    var bTramoId = null;
    if (!isEmpty(detalle) && !isEmpty(detalle[indexTemporal])) {
        stockBien = detalle[indexTemporal].stockBien;
        bienTipoId = detalle[indexTemporal].bienTipoId;
        //logica tramo
        var detalleBienTramoId = detalle[indexTemporal].bienTramoId;
        if (isEmpty(detalleBienTramoId) || (detalleBienTramoId != bienTramoId && bienTramoId != null)) {
            bTramoId = bienTramoId;
        } else {
            bTramoId = detalleBienTramoId;
        }

    } else {
//        bienTipoId = obtenerBienTipoIdPorBienId(bienId);
        bienTipoId = obtenerBienTipoIdPorBienId(select2.obtenerValor("cboBien_" + indice));
    }

    //otros datos:
    objDetalle.stockBien = stockBien;
    objDetalle.bienTipoId = bienTipoId;
    objDetalle.bienTramoId = bTramoId;
    objDetalle.detalle = detDetalle;

    return objDetalle;
}

function limpiarFilaDetalleFormulario(indice) {
    //columnas dinamicas de acuerdo a documento_tipo_columna
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.tipo)) {
                case 1://Precio de compra
                    $("#txtPrecioCompra_" + indice).html("");
                    break;
                case 2:// Utilidad moneda                    
                    $("#txtUtilidadSoles_" + indice).html("");
                    break;
                case 3:// Utilidad porcentaje                    
                    $("#txtUtilidadPorcentaje_" + indice).html("");
                    break;
                case 4:// Tipo de precio
                    $("#cboPrecioTipo_" + indice).select2({width: anchoTipoPrecioTD + 'px'});
                    break;
                case 5://Precio unitario
                    $("#txtPrecio" + indice).html("");
                    break;
                case 6://Sub total detalle
                    $("#txtSubTotalDetalle_" + indice).html("");
                    break;
                case 7://Stock
                    $("#txtStock_" + indice).html("");
                    break;
                case 11:
                    select2.asignarValor('cboBien_' + indice, '');
                    $("#cboBien_" + indice).select2({width: alturaBienTD + 'px'});
                    break;
                case 12:
                    document.getElementById("txtCantidad_" + indice).value = '1';
                    break;
                case 13:
                    select2.asignarValor('cboUnidadMedida_' + indice, '');
                    $("#cboUnidadMedida_" + indice).select2({width: anchoUnidadMedidaTD + 'px'});
                    break;
            }
        });
    }
}


function agregarSubTotalDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"text\" id=\"txtSubTotalDetalle_" + i + "\" name=\"txtSubTotalDetalle_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: right;\" disabled/></div>";

    return $html;
}

function agregarUnidadMedidaDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboUnidadMedida_" + i + "\" id=\"cboUnidadMedida_" + i + "\" class=\"select2\" onchange=\"\">" +
            "</select></div>";

    return $html;
}

function agregarPrecioTipoTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboPrecioTipo_" + i + "\" id=\"cboPrecioTipo_" + i + "\" class=\"select2\" onchange=\"\">" +
            "</select></div>";

    return $html;
}

function agregarBienDetalleTabla(i) {

    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboBien_" + i + "\" id=\"cboBien_" + i + "\" class=\"select2\">" +
            "</select>" +
            "<span class='input-group-btn'>" +
            "<button type='button' class='btn btn-effect-ripple btn-default' title='Actualizar producto'" +
            "style='padding-bottom: 7px;' onclick='actualizarComboProducto(" + i + ")'><i class='ion-refresh' style='color: #5CB85C;'></i></button>" +
            "</span>" +
            "</div>";

    return $html;
}

var personaDireccionId = 0;
var fechaEmisionId = 0;
var textoDireccionId = 0;
var cambioPersonalizadoId = 0;
var validarCambioFechaEmision = false;
var clienteValorAnteriorId = null;
function onResponseObtenerDocumentoTipoDato(data) {
    dataCofiguracionInicial.documento_tipo_conf = data;

    validarCambioFechaEmision = true;
    camposDinamicos = [];
    personaDireccionId = 0;
    var contador = 0;
    var mostrarCampo = true;

    var documentoTipo = dataCofiguracionInicial.documento_tipo.filter(item => item.id == $("#cboDocumentoTipo").val());
    if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 6) {
        mostrarCampo = false;
    }

    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        var escribirItem;
        var contadorEspeciales = 0;
        $.each(data, function (index, item) {
            switch (parseInt(item.tipo)) {
                case 7:
                case 8:
                case 14:
                case 15:
                case 16:
                case 17:
                case 19:
                case 24:
                case 25:
                case 27:
                case 32:
                case 33:
                case 34:
                case 35:
                case 38:
                    contadorEspeciales += 1;
                    escribirItem = false;
                    break;
                default:
                    //Tipo venta, solo debe mostrarse para los movimientotipo de ventas gratuitas.
                    if (parseInt(item.tipo) == 4 && item.codigo == 12 && dataCofiguracionInicial.movimientoTipo[0]["codigo"] != 18) {
                        contadorEspeciales += 1;
                        escribirItem = false;
                        break;
                    }
                    if (contador % 3 == 0) {
                        appendForm('<div class="row">');
                    }
                    contador++;

                    var html = '<div class="form-group col-md-4">';
                    if (item.tipo != 31) {
                        if (item.codigo != 11) {
                            html += '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
                        }
                    }

//                    if (item.tipo == 5)
//                    {
//                        html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar proveedor"></i></a>';
//                    }
                    switch (parseInt(item.tipo)) {
                        case 1:
                        case 7:
                        case 8:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                        case 2:
                        case 6:
                        case 12:
                        case 13:
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                        case 24:
                        case 26:
                        case 33:
                        case 32:
                        case 34:
                        case 35:
                        case 36:
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 38:
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 5:
                            html += `<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar ` + item.descripcion.toLowerCase() + `" style="color: #CB932A;"></i></a>
                                    <span class="divider"></span> <a onclick="actualizarCboPersona('_` + item.id + `')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>`;
                        case 4:

//                        case 17:
                        case 18:
                            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">';
                            break;
                    }

                    escribirItem = true;
                    break;
            }
            camposDinamicos.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion,
                codigo: item.codigo
            });
            var readonly = (parseInt(item.editable) === 0) ? 'readonly="true"' : '';
            var longitudMaxima = item.longitud;
            if (isEmpty(longitudMaxima)) {
                longitudMaxima = 45;
            }

            var maxNumero = 'onkeyup="if(this.value.length>' + longitudMaxima + '){this.value=this.value.substring(0,' + longitudMaxima + ')}"';

            switch (parseInt(item.tipo)) {
                case 1:
                    html += '<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '" ' + maxNumero + ' style="text-align: right;" />';
                    break;

                case 7:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    $("#contenedorSerieDiv").show();
                    $("#contenedorSerie").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Serie"  style="text-align: right;" disabled=""/>');
                    break;

                case 8:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    $("#contenedorNumeroDiv").show();
                    $("#contenedorNumero").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Número"  style="text-align: right;" disabled=""/>');
                    break;

                case 33:
                    importes.seguroId = 'txt_' + item.id;
                    $("#contenedorSeguroDiv").show();
                    $("#contenedorSeguro").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + '  onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 32:
                    importes.fleteId = 'txt_' + item.id;
                    $("#contenedorFleteDiv").show();
                    $("#contenedorFlete").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;

                case 34:
                    importes.otrosId = 'txt_' + item.id;
                    $("#contenedorOtrosDiv").show();
                    $("#contenedorOtros").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + '  onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 35:
                    importes.exoneracionId = 'txt_' + item.id;
                    $("#contenedorExoneracionDiv").show();
                    $("#contenedorExoneracion").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 36:
                    cboDetraccionId = item.id;
                    html += '<div id ="div_detraccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(dataCofiguracionInicial.dataDetraccion)) {
                        $.each(dataCofiguracionInicial.dataDetraccion, function (indexDetraccion, itemDetraccion) {
                            html += '<option value="' + itemDetraccion.id + '">' + itemDetraccion.descripcion + '</option>';
                        });
                    }
                    html += '</select>';
                    html += '<span name="txt_' + item.id + '" id="txt_' + item.id + '" class="control-label" style="font-style: normal;" hidden=""></span>';

                    break;
                case 38:
                    importes.icbpId = 'txt_' + item.id;
                    $("#contenedorIcbpDiv").show();
                    $("#contenedorIcbp").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 14:
                    importes.totalId = 'txt_' + item.id;
                    $("#contenedorTotalDiv").show();
                    $("#contenedorTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: right;" />');
                    break;
                case 15:
                    importes.igvId = 'txt_' + item.id;
                    $("#contenedorIgvDiv").show();
                    $("#txtDescripcionIGV").html(item.descripcion);
                    $("#contenedorIgv").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: right;" />');
                    $("#txtDescripcionIGV").css("font-weigh", "");
                    break;
                case 16:
                    importes.subTotalId = 'txt_' + item.id;

                    if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 30) {
                        $("#chkIGV").prop("checked", false);
                        igvValor = 0;
                    } else {
                        // si existe un subtotal, Mostramos el checkbox de cálculo de precios con / sin igv
//                        $("#contenedorChkIncluyeIGV").show();
                        $("#chkIncluyeIGV").prop("checked", "checked");
//                        $("#chkIncluyeIGV").prop("checked", "");
                        $("#chkIGV").prop("checked", true);
                        igvValor = 0.18;
                    }

                    $("#contenedorSubTotalDiv").show();
                    $("#contenedorSubTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: right;" />');
                    break;
                case 19:
                    percepcionId = item.id;
                    $("#contenedorPercepcionDiv").show();
                    $("#contenedorPercepcion").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" onchange="calculeTotalMasPercepcion(' + item.id + ')"  disabled/>');
                    break;
                case 2:
                case 6:
                case 12:
                case 13:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    if (parseInt(item.numero_defecto) === 1) {
                        textoDireccionId = item.id;
                    }

                    if (parseInt(item.numero_defecto) === 2) {
                        value = dataCofiguracionInicial.dataEmpresa[0]['direccion'];
                    }

                    html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '"/>';
                    break;
                case 9:
                    fechaEmisionId = item.id;
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '"  onchange="obtenerTipoCambioDatepicker();">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 3:
                case 10:
                case 11:
                    if (item.codigo != '11') {
                        html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '">' +
                                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    }
                    break;
                case 4:
                    /* let functionOnChangeTipo_4 = '';
                     switch (item.codigo * 1) {
                     case 12:
                     functionOnChangeTipo_4 = ' onchange = "onChangeCboTipoVenta();" ';
                     break;
                     case 14:
                     functionOnChangeTipo_4 = ' onchange = "onChangeCboMotivo();" ';
                     break;
                     }
                     html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2" ' + functionOnChangeTipo_4 + ' ></select>';
                     id_cboMotivoMov = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                     break;*/
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                    if (item.codigo == "10") {
                        html += '<span name="txt_' + item.id + '" id="txt_' + item.id + '" class="control-label" style="font-style: normal;" hidden=""></span>';
                    }
                    id_cboMotivoMov = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                    break;
                case 5:
                    html += '<div id ="div_persona" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(item.data)) {
                        $.each(item.data, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    }
                    html += '</select>';
//                    html += '<div class="form-group col-lg-4"><a href="#" class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 17:
                    var htmlOrg = '';
                    htmlOrg += '<div id ="div_organizador_destino" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2" placeholder="Seleccione almacén de llegada" onchange="onChangeOrganizadorDestino()">';

                    id_cboDestino = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                    htmlOrg += '<option></option>';
                    $.each(item.data, function (indexOrganizador, itemOrganizador) {
                        htmlOrg += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                    });
                    htmlOrg += '</select>';

                    $("#h4OrganizadorDestino").append(htmlOrg);
                    $("#divContenedorOrganizadorDestino").show();
                    break;
                case 18:
                    personaDireccionId = item.id;
                    html += '<div id ="div_direccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    //html += '<option value="' + 0 + '">Seleccione la dirección</option>';
                    html += '</select>';
//                    html += '<div class="form-group col-lg-4"><a href="#" class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 20:
                    html += '<div id ="div_cuenta" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                    });
                    html += '</select>';
                    break;
                case 21:
                    html += '<div id ="div_actividad" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la actividad</option>';
                    $.each(item.data, function (indexActividad, itemActividad) {
                        html += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
                case 22:
                    html += '<div id ="div_retencion_detraccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione retención o detracción</option>';
                    $.each(item.data, function (indexRetencionDetraccion, itemRetencionDetraccion) {
                        html += '<option value="' + itemRetencionDetraccion.id + '">' + itemRetencionDetraccion.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
                case 23:
                    html += '<div id ="div_persona_' + item.id + '" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    if (dataCofiguracionInicial.documento_tipo[0]["identificador_negocio"] == 1) {
                        html += '<option value="' + 0 + '">Seleccione a quién va dirigido</option>';
                    } else {
                        html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    }
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
//                    html += '<div class="form-group col-lg-4"><a href="#" class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 24:
                    cambioPersonalizadoId = item.id;
//                    $("#contenedorCambioPersonalizado").show();
                    $("#cambioPersonalizado").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: right;" placeholder="' + item.descripcion + '"/>');
                    break;
                case 25:
                    $("#divContenedorTipoPago").show();
                    break;
                case 26:
                    html += '<div id ="div_vendedor" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(item.data)) {
                        $.each(item.data, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    }
                    html += '</select>';
                    break;
                case 27:
                    $("#divContenedorAdjunto").show();
                    iniciarArchivoAdjunto();
                    break;
                case 31:
                    if (mostrarCampo) { //DT Guia de remision BH
                        html += '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
                        html += '<div id ="divContenedorAdjuntoMultiple">';
                        html += '<a class="btn btn-primary btn-sm m-b-5" onclick="adjuntar();"><i class="fa fa-cloud-upload"></i> Adjuntar archivos</a>';
                        iniciarArchivoAdjuntoMultiple();
                    }

                    break;
            }
            if (escribirItem) {
                html += '</div></div>';
                appendForm(html);
                if (contador % 3 == 0) {
                    appendForm('</div>');
                }
            }
            switch (parseInt(item.tipo)) {
                case 3:
//                case 9:
                case 10:
                case 11:
                    $('#datepicker_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    break;
                case 9:
                    $('#datepicker_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    }).on('changeDate', function (ev) {
                        onChangeFechaEmision();
                        cambiarPeriodo();
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    cambiarPeriodo();
                    fechaEmisionAnterior = item.data;
                    break;
                case 4:
                    select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        if (item.codigo == "10") {
                            obtenerMontoRetencion();
                        }
                    });

                    if (!isEmpty(item.lista_defecto)) {
                        var id = parseInt(item.lista_defecto);
                        select2.asignarValor("cbo_" + item.id, id);
                    }

                    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) {
                        select2.asignarValor("cbo_" + item.id, null);
                    }

                    break;
                case 5:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
//                        debugger;
                        if (!isEmpty(detalle) && !isEmpty(clienteValorAnteriorId) && clienteValorAnteriorId != e.val) {
                            swal({
                                title: "Confirmación de cambio de cliente",
                                text: "¿Está seguro de cambiar el cliente, se eliminarán los documentos relacionados y el detalle?",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#33b86c",
                                confirmButtonText: "Si, actualizar!",
                                cancelButtonColor: '#d33',
                                cancelButtonText: "No, cancelar!",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            }, function (isConfirm) {
                                if (isConfirm) {
                                    clienteValorAnteriorId = e.val;
                                    for (var indiceRelacion = (request.documentoRelacion.length - 1); indiceRelacion >= 0; indiceRelacion--) {
                                        eliminarDocumentoACopiar(request.documentoRelacion[indiceRelacion].posicion);
                                    }
                                } else {
                                    select2.asignarValor("cbo_" + item.id, clienteValorAnteriorId);
                                }
                            });

                            return;
                        }
                        clienteValorAnteriorId = e.val;

//                        obtenerPersonaDireccion(e.val);
                    });

                    setTimeout(function () {

                        $($("#cbo_" + item.id).data("select2").search).on('keyup', function (e) {
                            if (e.keyCode === 13)
                            {
                                obtenerDataCombo('_' + item.id);
                            }
                        });
                    }, 1000);
                    break;
                case 17:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 18:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 20:
                case 21:
                case 26:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.numero_defecto)) {
                        var id = parseInt(item.numero_defecto);
                        select2.asignarValor("cbo_" + item.id, id);
                    }

                    if (item.editable == 0) {
                        $("#cbo_" + item.id).attr('disabled', 'disabled');
                    }
                    break;
                case 22:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 23:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {

                    });
                    break;
                case 36:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        obtenerMontoDetraccion(e.val, item.id);
                    });
                    break;
            }
        });

        modificarDetallePrecios();


        validarImporteLlenar();
        asignarImporteDocumento();
    }
}

var percepcionId = 0;
function calculeTotalMasPercepcion(id) {

    if (calculoTotal <= 0) {
        mostrarValidacionLoaderClose("Total debe ser mayor a cero.");
        return false;
    }

    var percepcion = parseFloat($('#txt_' + id).val());

    if (isEmpty(percepcion) || percepcion < 0) {
        mostrarValidacionLoaderClose("Debe ingresar una percepción válida");
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var percepcionMaxima = 0.02 * calculoTotal + 1;

    if (percepcion > percepcionMaxima) {
        mostrarValidacionLoaderClose("Percepción no puede ser mayor a: " + devolverDosDecimales(percepcionMaxima));
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var suma = percepcion + calculoTotal;
    $('#' + importes.totalId).val(devolverDosDecimales(suma));

}

function onChangeCheckPercepcion() {
    if (document.getElementById('chkPercepcion').checked) {
        $('#txt_' + percepcionId).removeAttr('disabled');
    } else {
        $('#txt_' + percepcionId).val('');
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + percepcionId).attr('disabled', 'disabled');
    }
}

function obtenerPersonaDireccion(personaId) {
    //alert(personaId);    
    if (personaDireccionId !== 0 || textoDireccionId !== 0) {
        ax.setAccion("obtenerPersonaDireccion");
        ax.addParamTmp("personaId", personaId);
        ax.consumir();
    }

}

function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

function deshabilitarBoton()
{
    $("#env").addClass('disabled');
    $("#envOpciones").addClass('disabled');
    $("#env i").removeClass(boton.enviarClase);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
    $("#envOpciones").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(boton.enviarClase);
}
var detalle = [];
var detallePenalidad = [];
var detalleDos = [];
var indexDetalle = 0;
function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}

function confirmacionRedirecciona() {
    $('#cargarBuscadorDocumentoACopiar').removeAttr("onclick");
    if (valoresFormularioDetalle.accion === "agregar") {
        agregarConfirmado();
    } else if (valoresFormularioDetalle.accion === "editar") {
        editarConfirmado();
    }
    // asigno el importe
    asignarImporteDocumento();
    loaderClose();
}
var valoresFormularioDetalle;

function agregarConfirmado() {
    var subTotal = devolverDosDecimales(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);

    valoresFormularioDetalle.subTotal = subTotal;

    valoresFormularioDetalle.index = indexDetalle;
    detalle.push(valoresFormularioDetalle);

    indexDetalle += 1;

    if (valoresFormularioDetalle.organizadorDesc == null) {
        valoresFormularioDetalle.organizadorDesc = '';
    }

    dibujarTabla();

    loaderClose();
}

var numeroItemFinal = 0;
function dibujarTabla() {
    $('#datatable tbody').empty();
    if (!isEmpty(detalle) && detalle.length > 0) {
        $.each(detalle, function (indexFila, item) {
            $('#datatable tbody').append('<tr>' +
                    "<td id='colCantidad_" + item.index + "' style='text-align:right;'>" + formatearNumero(item.cantidad) + "</td>" +
                    "<td id='colBien_" + item.index + "' style='text-align:left;'>" + item.bienDesc +
                    (!isEmpty(item.bien_alto) && item.bien_alto > 0 ? ' ' + formatearNumero(item.bien_alto) + ' x ' + formatearNumero(item.bien_ancho) + ' x ' + formatearNumero(item.bien_longitud) + ' ( ' + formatearNumero(item.bien_peso) + ' Kg)' : '')
                    + "</td> "
                    + "<td id='colDocumentoSerieNumero_" + item.index + "' style='text-align:left;'>" + item.serieNumeroPadre + "</td> " +
                    "<td id='colPrecio_" + item.index + "' style='text-align:right;'>" + formatearNumero(item.precio) + "</td> " +
                    "<td id='colSubTotal_" + item.index + "' style='text-align:right;'>" + item.subTotal + "</td> " +
                    "</tr>");
        });
    } else {
        reinicializarDataTableDetalle();
    }
}


function dibujarTablaPenalidad() {
    let montoTotal = 0;
    $('#datatablePenalidad tbody').empty();
    if (!isEmpty(detallePenalidad) && detallePenalidad.length > 0) {
        $.each(detallePenalidad, function (indexFila, item) {
            $('#datatablePenalidad tbody').append('<tr>' +
                    "<td id='colPenalidadMotivo_" + item.index + "' style='text-align:left;'>" +
                    "<a onclick='eliminarPenalidadSeleccionad(" + indexFila + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp&nbsp&nbsp" +
                    item.descripcion +
                    "</td> " +
                    "<td id='colPenalidadMonto_" + item.index + "' style='text-align:right;'>-" + formatearNumero(item.monto) + "</td>" +
                    "</tr>");
            montoTotal = montoTotal + (item.monto * 1);
        });
    } else {
        reinicializarDataTableDetallePenalidad();
    }

    document.getElementById("contenedorDsco").value = devolverDosDecimales(-montoTotal);
    asignarImporteDocumento();
}

var banderaCopiaDocumento = 0;
var unidadMedidaTxt = 0;
function asignarValoresDetalleFormulario() {

    banderaCopiaDocumento = 1;
    //COLUMNAS DINAMICAS

    //obtener los datos del detalle dinamico
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;
    var valor;

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            unidadMedidaTxt = 0;
            valor = null;

            //obtener los datos del detalle
            switch (parseInt(item.codigo)) {
                //numeros
                case 5:// PRECIO UNITARIO             
                    $('#txtPrecio_' + indexDetalle).val(devolverDosDecimales(valoresFormularioDetalle.precio));
                    break;
                case 6:// SUB TOTAL
                    $('#txtSubTotalDetalle_' + indexDetalle).html(devolverDosDecimales(valoresFormularioDetalle.subTotal));
                    break;
                case 9:// PRIORIDAD
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        $('#txtPrioridad_' + indexDetalle).html(valoresFormularioDetalle.prioridad);
                    }
                case 12:// CANTIDAD
                    $('#txtCantidad_' + indexDetalle).val(devolverDosDecimales(valoresFormularioDetalle.cantidad));
                    break;

                    //combos, seleccion
                case 4:// TIPO PRECIO
                    select2.asignarValor("cboPrecioTipo_" + indexDetalle, valoresFormularioDetalle.precioTipoId);
                    $("#cboPrecioTipo_" + indexDetalle).select2({width: anchoTipoPrecioTD + 'px'});
                    break;
                case 11:// PRODUCTO                    
                    select2.asignarValor("cboBien_" + indexDetalle, valoresFormularioDetalle.bienId);
                    $("#cboBien_" + indexDetalle).select2({width: alturaBienTD + 'px'});
                    setearDescripcionProducto(indexDetalle);
                    break;
//                case 13:// UNIDAD DE MEDIDA                    
//                    obtenerUnidadMedida(valoresFormularioDetalle.bienId, indexDetalle);
//                    break;
                case 15:// Organizador
                    select2.asignarValor("cboOrganizador_" + indexDetalle, valoresFormularioDetalle.organizadorId);
                    break;

                case 21:// COMENTARIO
                    $('#txtComentarioDetalle_' + indexDetalle).removeAttr("readonly");
                    $('#txtComentarioDetalle_' + indexDetalle).val(valoresFormularioDetalle.comentarioDetalle);
                    break;


                default:
                    //DATOS DE MOVIMIENTO_BIEN_DETALLE
                    if (!isEmpty(valoresFormularioDetalle.detalle)) {
                        $.each(valoresFormularioDetalle.detalle, function (indexBD, itemBD) {
                            if (parseInt(itemBD.columnaCodigo) === parseInt(item.codigo)) {
                                valor = itemBD.valorDet;
                                switch (itemBD.columnaCodigo) {
                                    //texto
                                    case 16://descripcion de producto      
                                        $('#txtProductoDescripcion_' + indexDetalle).removeAttr("readonly");
                                        $('#txtProductoDescripcion_' + indexDetalle).val(valor);
                                        break;
                                    case 17://descripcion de unidad de medida
                                        $('#txtUnidadMedidaDescripcion_' + indexDetalle).removeAttr("readonly");
                                        $('#txtUnidadMedidaDescripcion_' + indexDetalle).val(valor);
                                        if (isEmpty(valor)) {
                                            unidadMedidaTxt = 1;
                                        }
                                        break;

                                        //fechas
                                    case 18://fecha vencimiento
                                        if (!isEmpty(valor)) {
                                            valor = formatearFechaBDCadena(valor);
                                            $('#txtFechaVencimiento_' + indexDetalle).val(valor);
                                        }
                                        break;
                                }
                            }
                        });
                    } else {
                        unidadMedidaTxt = 1;
                    }
                    break;
            }
        });

        //CONSIDERANDO QUE SIEMPRE HAY UNIDAD DE MEDIDA
        //DESPUES QUE SE DIBUJO TODO LLAMAMOS A LA FUNCION onResponseObtenerUnidadesMedida , ANTES ERA CON METODO LLAMADO AL AJAX
        onResponseObtenerUnidadesMedida(valoresFormularioDetalle.dataUnidadMedida, indexDetalle);
    } else {
        mostrarValidacionLoaderClose("Falta configurar las columnas del detalle");
        return false;
    }
    //FIN COLUMNAS DINAMICAS    
}

var nroFilasEliminados = 0;
function eliminarDetalleFormularioTabla(indice) {
    //$('#cboUnidadMedida_'+indice).attr('disabled', "true");
    var numItemActual = $('#txtNumItem_' + indice).html();

    $('#trDetalle_' + indice).remove();

    //LLENAR TABLA DETALLE
    var fila = llenarFilaDetalleTabla(nroFilasReducida);

    $('#datatable tbody').append(fila);

    //LLENAR COMBOS
    cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, nroFilasReducida);
    cargarUnidadMedidadDetalleCombo(nroFilasReducida);
    cargarBienDetalleCombo(dataCofiguracionInicial.bien, nroFilasReducida);
    cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, nroFilasReducida);
    inicializarFechaVencimiento(nroFilasReducida);

    nroFilasInicial++;
    nroFilasReducida++;
    nroFilasEliminados++;

    $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * (nroFilasReducida - nroFilasEliminados));

    reenumerarFilasDetalle(indice, numItemActual);

}

function eliminar(index) {
    loaderShow();
    if (isEmpty(detalle) || isEmpty(index)) {
        mostrarValidacionLoaderClose("No se ha encontrado data para eliminar");
        return;
    }
    var indexTemporal = null;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(index)) {
            indexTemporal = i;
            return false;
        }
    });
    if (isEmpty(indexTemporal)) {
        mostrarValidacionLoaderClose("No se ha encontrado data para eliminar");
        return;
    }

    if (indexTemporal > -1) {
        detalle.splice(indexTemporal, 1);
        eliminarDetalleFormularioTabla(index);
        asignarImporteDocumento();
        obtenerUtilidadesGenerales();
    }

    loaderClose();
}
function confirmarEliminar(index) {
    swal({
        title: "¿Está seguro que desea eliminar?",
        text: "Una vez eliminado tendrá que seleccionar nuevamente todo el registro si desea volver agreagarlo",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminar(index);
        }
    });
}

function cargarPantallaListar() {
    let url = 'vistas/com/movimiento/movimiento_listar_liquidacion.php?tipoInterfaz=' + tipoInterfaz;
    cargarDivIndex('#window', URL_BASE + url, '362', 'Liquidación', 1);
}

function enviar(accion, banderaPago = false) {
    debugger;
    //VALIDO QUE EL PERIODO ESTE SELECCIONADO
    debugger;
    var periodoId = select2.obtenerValor('cboPeriodo');
    if (isEmpty(periodoId)) {
        mostrarAdvertencia('Seleccione un periodo');
        return;
    }

    //VALIDO QUE LA FECHA DE EMISION ESTE EN EL PERIODO SELECCIONADO
    var periodoFechaEm = obtenerPeriodoIdXFechaEmision();
    if (periodoId != periodoFechaEm) {
        swal({
            title: "¿Desea continuar?",
            text: "La fecha de emisión no está en el periodo seleccionado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                guardar(accion, banderaPago);
            }
        });
        return;
    }

    if (bandera.validacionAnticipos == 1) {
        mostrarModalAnticipo();
        return;
    }

    // asignarAtencion();
    boton.accion = accion;

    var dtdTotal = obtenerDocumentoTipoDatoIdXTipo(14);
    if (!isEmpty(dtdTotal)) {
        var existeCero = false;

        $.each(detalle, function (i, item) {
            if (item.precio == 0 || isEmpty(item.precio)) {
                existeCero = true;
                return false;
            }
        });

        if (existeCero) {
            swal({
                title: "¿Desea continuar?",
                text: "Existe precios con valor cero en el detalle del documento.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#33b86c",
                confirmButtonText: "Si!",
                cancelButtonColor: '#d33',
                cancelButtonText: "No!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    guardar(accion, banderaPago);
                }
            });

            return;
        }
    }

    if (documentoTipoTipo == 1) {
        //validacion cambio de fecha emision en caso de dolares.
        if (select2.obtenerValor("cboMoneda") == 4) {
            var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
            if (primeraFechaEmision != fechaEmision && !isEmpty(fechaEmision) && !isEmpty(primeraFechaEmision)) {

//                swal("Recálculo!", "La fecha de emisión inicial: " + primeraFechaEmision + ", se cambió a: "+
//                            fechaEmision + '. Se va a proceder a recalcular el(los) precio(s) de compra y utilidad(es).', "success");

                swal({
                    title: "Recálculo!",
                    text: "La fecha de emisión inicial: " + primeraFechaEmision + ", se cambió a: " +
                            fechaEmision + ". Se va a proceder a recalcular el(los) precio(s) de compra y utilidad(es).",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#AEDEF4",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        recalculoPrecioCompraUtilidades();
                        return;
                    }
                });

                return;
            }
        }

        // validacion de tramos longitud
        var unidadMedidaTipo;
        var banderaUM = true;
        $.each(detalle, function (i, item) {
            unidadMedidaTipo = obtenerUnidadMedidaTipoBien(item.bienId);

            if (unidadMedidaTipo.indexOf("Longitud") > -1 && banderaUM == true) {
                banderaUM = false;
                swal({
                    title: "¿Desea continuar sin registrar tramos?",
                    text: "Existe 1 o más detalles que tienen como tipo de unidad de medida Longitud y no se han registrado tramos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#33b86c",
                    confirmButtonText: "Si!",
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        guardar(accion, banderaPago);
                    }
                });
            }
        });

        if (banderaUM == true) {
            guardar(accion, banderaPago);
        }

    } else {
        debugger;
        guardar(accion, banderaPago);
}

}

function obtenerUnidadMedidaTipoBien(bienId) {
    var unidadMedidaTipo = 0;

    $.each(dataCofiguracionInicial.bien, function (index, item) {
        if (item.id == bienId) {
            unidadMedidaTipo = item.unida_medida_tipo_descripcion;
            return false;
        }
    });
    return unidadMedidaTipo;
}

function obtenerValoresCamposDinamicos() {
    var isOk = true;
    if (isEmpty(camposDinamicos))
        return false;
    $.each(camposDinamicos, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 14:
            case 15:
            case 16:
            case 19:
            case 24:
            case 32:
            case 33:
            case 34:
            case 35:
            case 38:
                var numero = document.getElementById("txt_" + item.id).value;
                if (isEmpty(numero)) {
                    if (item.opcional == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                } else {
                    if (!esNumero(numero)) {
                        mostrarValidacionLoaderClose("Debe ingresar un número válido para " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                camposDinamicos[index]["valor"] = numero;
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
                camposDinamicos[index]["valor"] = document.getElementById("txt_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
                //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                if (item.codigo != '11') {
                    camposDinamicos[index]["valor"] = document.getElementById("datepicker_" + item.id).value;
                    if (item.opcional == 0) {
                        //validamos
                        if (isEmpty(camposDinamicos[index]["valor"])) {
                            mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                            isOk = false;
                            return false;
                        }
                    }
                } else {
                    camposDinamicos[index]["valor"] = null;
                }
                break;
            case 4:
            case 5:// persona
            case 18:// direccion persona
            case 20:// cuenta
            case 21:// actividad
            case 22:// retencion detraccion
            case 23:// otra persona
            case 26:// vendedor
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                let campoOpcional = (deshabilitarProveedor() && item.tipo == 5 ? 1 : item.opcional);
                if (campoOpcional == 0) {
                    deshabilitarProveedor()
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 17:// organizador
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 27:// ADJUNTO
                var objArchivo = {nombre: $('#nombreArchivo').html(), data: $('#dataArchivo').val()};
                camposDinamicos[index]["valor"] = objArchivo;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty($('#dataArchivo').val())) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 31:// ADJUNTO MULTIPLE
                var objArchivo = lstDocumentoArchivos;
                camposDinamicos[index]["valor"] = objArchivo;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty($('#dataArchivoMulti').val())) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;

        }
    });
    return isOk;
}

function enviarEImprimir()
{
    if (documentoTipoTipo == 1) {

        var unidadMedidaTipo;
        var bandera = true;
        $.each(detalle, function (i, item) {
            unidadMedidaTipo = obtenerUnidadMedidaTipoBien(item.bienId);

            if (unidadMedidaTipo.indexOf("Longitud") > -1 && bandera == true) {
                bandera = false;
                swal({
                    title: "¿Desea continuar sin registrar tramos?",
                    text: "Existe 1 o más detalles que tienen como tipo de unidad de medida Longitud y no se han registrado tramos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#33b86c",
                    confirmButtonText: "Si!",
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        guardar("enviarEImprimir");
                    }
                });
            }

        });

        if (bandera == true) {
            guardar("enviarEImprimir");
        }

    } else {
        guardar("enviarEImprimir");
    }
}

function guardar(accion, banderaPago = false) {
debugger;
    if (banderaPago) {
        loaderShow("#modalNuevoDocumentoPagoConDocumento");
    } else {
        loaderShow();
    }
    let datosExtras = {};
    datosExtras.afecto_detraccion_retencion = null;
    datosExtras.detallePenalidad = detallePenalidad;

    let comentario = $('#txtComentario').val();
    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }

    var contOperacionTipoId = select2.obtenerValor("cboOperacionTipo");
    if (isEmpty(contOperacionTipoId) && !isEmpty(dataContOperacionTipo)) {
        mostrarValidacionLoaderClose("Debe seleccionar el tipo de operación");
        return;
    }

    //validamos que el total no sea negativo o cero    
    // if(parseFloat($('#'+importes.totalId).val())<=0){
    // mostrarValidacionLoaderClose("Total debe ser positivo.");
    // return;
    // }

    // validamos los importes que esten llenos
    // if (!validarImportesLlenos()) {
    // return;
    // }
    //Validar y obtener valores de los campos dinamicos
    if (!obtenerValoresCamposDinamicos())
        return;

    if (!validarDetalleFormularioLlenos()) {
        return;
    }

//    if (isEmpty(detalle))
//        mostrarAdvertencia("Falta ingresar datos.");
//        loaderClose();
//        return;
    obtenerCheckDocumentoACopiar();
//    if (validarDetalleRepetido()) {
//        mostrarValidacionLoaderClose("Detalle repetido, seleccione otro bien, organizador o unidad de medida.");
//        return;
//    }

    var checkIgv = 0;
    var afectoAImpuesto = 0;

    if (!isEmpty(importes.subTotalId)) {
        if (document.getElementById('chkIncluyeIGV').checked) {
            checkIgv = 1;
        }
    } else {
        checkIgv = opcionIGV;
    }

    if ($("#chkIGV").length > 0 && document.getElementById('chkIGV').checked) {
        afectoAImpuesto = 1;
    }

    //Calculamos la detracción
    var dtdTipoDetraccion = obtenerDocumentoTipoDatoIdXTipo(36);
    if (!isEmpty(dtdTipoDetraccion) && igvValor > 0) {
        if (select2.obtenerValor("cbo_" + dtdTipoDetraccion) * 1 > 0) {
            let dataDetraccion = dataCofiguracionInicial.dataDetraccion.filter(item => item.id == select2.obtenerValor("cbo_" + dtdTipoDetraccion));
            datosExtras.afecto_detraccion_retencion = 1;
            datosExtras.porcentaje_afecto = dataDetraccion[0]['porcentaje'];
            datosExtras.monto_detraccion_retencion = montoTotalDetraido;
        }
    }

    //Calculamos la retención
    var dtdTipoRetencion = obtenerDocumentoTipoDatoXTipoXCodigo(4, "10");
    if (!isEmpty(dtdTipoRetencion) && igvValor > 0) {
        var valorRetencion = dtdTipoRetencion.data.filter(item => item.id == select2.obtenerValor("cbo_" + dtdTipoRetencion.id));
        if (!isEmpty(valorRetencion) && valorRetencion[0]['valor'] == 1) {
            datosExtras.afecto_detraccion_retencion = 2;
            datosExtras.porcentaje_afecto = dataCofiguracionInicial.dataRetencion.porcentaje;
            datosExtras.monto_detraccion_retencion = montoTotalRetencion;
        }
    }

    if (montoTotalDetraido > 0 && montoTotalRetencion > 0) {
        mostrarValidacionLoaderClose('El documento no puede estar afecto a detracción retención al mismo tiempo.');
        return;
    }


    if ((montoTotalDetraido > 0 || montoTotalRetencion > 0) && igvValor == 0) {
        mostrarValidacionLoaderClose('El documento no esta afecto a IGV, por lo tanto no puede estar afecto a retención o detracción.');
        return;
    }

    var tipoPago = null;

    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
    if (!isEmpty(dtdTipoPago)) {
        tipoPago = select2.obtenerValor("cboTipoPago");

        //validando el total pago = total documento
        if (tipoPago == 2) {
            var totalPago = 0;
            listaPagoProgramacion.forEach(function (item) {
                totalPago = totalPago + item[1] * 1;
            });
            if (totalPago != calculoTotal) {
                mostrarValidacionLoaderClose('Total de pago no coincide con el total del documento' + formatearNumero(calculoTotal));
                return;
            }
        }
    }

    if (tipoPago != 2) {
        listaPagoProgramacion = [];
    }

    var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
    if (!isEmpty(dtdOrganizadorId)) {
        var organizadorOrigen = select2.obtenerValor('cboOrganizador');
        var organizadorDestino = select2.obtenerValor('cbo_' + dtdOrganizadorId);

        if (organizadorOrigen == organizadorDestino) {
            mostrarValidacionLoaderClose('Seleccione un almacén de destino diferente al almacén de origen');
            return;
        }
    }

    var periodoId = select2.obtenerValor('cboPeriodo');

    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { // validar transferencia interna
        validarAlmacenOrigenDestino();

        var bandValidacionTrans = true;
        var dtdMovimientoTipo = obtenerDocumentoTipoDatoXTipoXCodigo(4, 4);
        var codMotivo = dtdMovimientoTipo.data[document.getElementById('cbo_' + id_cboMotivoMov).options.selectedIndex]['valor'];

//        var indMotivo = (select2.obtenerText('cbo_' + id_cboMotivoMov)).indexOf('|');
//        var codMotivo = (select2.obtenerText('cbo_' + id_cboMotivoMov)).substr(0, indMotivo);

        switch (codMotivo * 1) {
            case 1:
                if (origen_destino != 'O') {
                    mostrarValidacionLoaderClose('Debe seleccionar el almacén virtual como almacén de origen o cambie el motivo de movimiento');
                    bandValidacionTrans = false;
                }
                break;
            case 2:
                if (origen_destino != 'D') {
                    mostrarValidacionLoaderClose('Debe seleccionar el almacén virtual como almacén de llegada o cambie el motivo de movimiento');
                    bandValidacionTrans = false;
                }
                break;
            case 3:
                if (origen_destino != null) {
                    mostrarValidacionLoaderClose('El almacén virtual no debe estar seleccionado como origen o llegada');
                    bandValidacionTrans = false;
                }
                break;
        }

        if (!bandValidacionTrans) {
            return;
        }
    }

    if (isEmpty(comentario)) {
        mostrarValidacionLoaderClose("Ingrese un comentario");
        return;
    }
    let dataPago = {};
    if (accion == "confirmar" && banderaPago) {

        let montoTotalDocumento = parseFloat($("#" + importes.totalId).val());
        loaderShow("#modalNuevoDocumentoPagoConDocumento");

        //parte documento pago    
        //obtenemos el tipo de documento
        let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
        let actividadEfectivo = $('#cboActividadEfectivo').val();
        if (isEmpty(documentoTipoIdPago)) {
            mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
            return;
        }


        let montoAPagar = $('#txtMontoAPagar').val();
        if (isEmpty(montoAPagar)) {
            montoAPagar = 0;
        }

        if (montoAPagar == 0 && documentoTipoIdPago == 282) {
            mostrarValidacionLoaderClose("Debe ingresar monto a pagar en efectivo");
            return;
        }

////        //validar total de documento de pago = total de documento a pagar.
////        var banderaTotalPago = true;
////        if (documentoTipoIdPago == 282) {
////            if (parseFloat(montoAPagar) != montoTotalDocumento) {
////                banderaTotalPago = false;
////            }
////        } else if (parseFloat($('#' + totalPago).val()) != montoTotalDocumento) {
////            banderaTotalPago = false;
////        }
////
////        if (!banderaTotalPago) {
////            mostrarValidacionLoaderClose("El monto de pago debe ser igual al monto total del documento.");
////            return;
////        }
//
//        let datoDinamicosPago = [];
//        //Validar y obtener valores de los campos dinamicos
//        if (documentoTipoIdPago != 282) {
//            if (!obtenerValoresCamposDinamicosPago()) {
//                return;
//            }
//            datoDinamicosPago = camposDinamicosPago;
//        } else {
//            if (!obtenerValoresCamposDinamicosPagoEfectivo()) {
//                return;
//            }
//            datoDinamicosPago = camposDinamicosPagoEfectivo;
//        }

        let totalCamposDinamicos = 0;
        $.each(camposDinamicosPagoTemporal, function (index, item) {
            totalCamposDinamicos = totalCamposDinamicos + ((item.data.filter(itemFilter => itemFilter.tipo == 14)[0]['valor']) * 1);
        });

//        let montoTotalDocumento = $('#' + importes.totalId).val();

        if (!isEmpty(camposDinamicosPagoTemporal) && formatearNumero(totalCamposDinamicos) == formatearNumero(montoTotalDocumento)) {

        } else {

            let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
            let documentoTipoIdPagoDescripcion = select2.obtenerText("cboDocumentoTipoNuevoPagoConDocumento");
            //Validar y obtener valores de los campos dinamicos
            if (documentoTipoIdPago != 282) {
                if (!obtenerValoresCamposDinamicosPago()) {
                    return;
                }

                let montoTotal = camposDinamicosPago.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
                if (formatearNumero((totalCamposDinamicos * 1) + (montoTotal * 1)) != formatearNumero(montoTotalDocumento)) {
                    mostrarValidacionLoaderClose("El monto total a pagar es  " + formatearNumero(montoTotalDocumento));
                    return;
                }
                camposDinamicosPagoTemporal.push({documento_tipo_id: documentoTipoIdPago,
                    documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
                    monto: montoTotal,
                    data: obtenerArraySinReferencia(camposDinamicosPago)});
            } else {
                if (!obtenerValoresCamposDinamicosPagoEfectivo()) {
                    return;
                }
                let montoTotal = camposDinamicosPagoEfectivo.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
                if (formatearNumero((totalCamposDinamicos * 1) + (montoTotal * 1)) != formatearNumero(montoTotalDocumento)) {
                    mostrarValidacionLoaderClose("El monto total a pagar es  " + formatearNumero(montoTotalDocumento));
                    return;
                }
                camposDinamicosPagoTemporal.push({documento_tipo_id: documentoTipoIdPago,
                    documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
                    monto: montoTotal,
                    data: obtenerArraySinReferencia(camposDinamicosPagoEfectivo)});
            }
            cargarDetallePago();
        }

        let fechaId = obtenerDocumentoTipoDatoIdXTipo(9);

        let cboPersonaId = obtenerDocumentoTipoDatoIdXTipo(5);

        dataPago.camposDinamicosPago = camposDinamicosPagoTemporal;
        dataPago.documentoTipoIdPago = documentoTipoIdPago;
        dataPago.cliente = select2.obtenerValor("cbo_" + cboPersonaId);
        dataPago.tipoCambio = $('#tipoCambio').val();
        dataPago.fecha = document.getElementById("datepicker_" + fechaId).value;
        dataPago.actividadEfectivo = actividadEfectivo;
        dataPago.montoAPagar = montoAPagar;
        dataPago.totalPago = $('#' + totalPago).val();
        dataPago.totalDocumento = montoTotalDocumento;

        datosExtras.dataPago = dataPago;
        datosExtras.accion = accion;
        //fin documento pago    
    } 
    datosExtras.accion = accion;
    // else if (accion == "confirmar") {
    //      abrirModalPagos(dataCofiguracionInicial);
    //      return;
    //  }


    let accionEnvio = (!isEmpty(documentoId) ? 'enviarEdicion' : 'enviar')

    obtenerDistribucion();
    deshabilitarBoton();
    ax.setAccion(accionEnvio);
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("contOperacionTipoId", contOperacionTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("checkIgv", checkIgv);
    ax.addParamTmp("afectoAImpuesto", afectoAImpuesto);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("accionEnvio", accion);
    bandera.validacionAnticipos = (bandera.validacionAnticipos > 0) ? 1 : 0;
    ax.addParamTmp("anticiposAAplicar",
            {
                validacion: bandera.validacionAnticipos,
                empresaId: commonVars.empresa,
                data: obtenerAnticiposAAplicar()
            });
//    actividadId: ((bandera.validacionAnticipos == 1)?select2.obtenerValor("cboAnticipoActividad"):null)
    //gclv: agregando el campo de tipo de pago en base al combo de la vista
    ax.addParamTmp("tipoPago", tipoPago);
    ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
    ax.addParamTmp("periodoId", periodoId);
    ax.addParamTmp("origen_destino", origen_destino);
    ax.addParamTmp("importeTotalInafectas", importeTotalInafectas);
    ax.addParamTmp("detalleDistribucion", dataDistribucion);
    ax.addParamTmp("distribucionObligatoria", distribucionObligatoria);
    ax.addParamTmp("datosExtras", datosExtras);
    ax.setTag(accion);
    ax.consumir();
}

function validarDetalleFormularioDocumentoRelacionIdentico() {
    //SOLO VALIDA CUANDO SE COPIA CON DETALLE    
    var bandera = false;
    var df;
    var dr;

    if (!isEmpty(detalleDocumentoRelacion)) {

        if (detalle.length <= detalleDocumentoRelacion.length) {
            df = detalle;
            dr = detalleDocumentoRelacion;
        } else {
            df = detalleDocumentoRelacion;
            dr = detalle;
        }

        for (var i = 0; i < dr.length; i++) {
            var bandera2 = false;
            for (var j = 0; j < df.length; j++) {
                //FALTA CORREGIR LAS VARIABLES CUANDO INTERCAMBIA DF X DR
                if (dr[i].bien_id == df[j].bienId && formatearCantidad(dr[i].cantidad) == formatearCantidad(df[j].cantidad) && dr[i].unidad_medida_id == df[j].unidadMedidaId) {
                    bandera2 = true;
                    break;
                }
            }
            if (!bandera2) {
                bandera = true;
                break;
            }
        }
    }

    return bandera;
}

function validarDetalleFormularioContenidoEnDocumentoRelacion() {
    //SOLO VALIDA CUANDO SE COPIA CON DETALLE    
    var bandera = false;//NO HAY ERRORES
    var df;
    var dr;

    if (!isEmpty(detalleDocumentoRelacion)) {
        df = detalle;
        dr = detalleDocumentoRelacion;

        for (var i = 0; i < df.length; i++) {
            var bandera2 = false;
            for (var j = 0; j < dr.length; j++) {
                if (df[i].bienId == dr[j].bien_id && df[i].cantidad * 1 <= dr[j].cantidad * 1 && df[i].unidadMedidaId == dr[j].unidad_medida_id) {
                    bandera2 = true;
                    break;
                }
            }
            if (!bandera2) {
                bandera = true;
                break;
            }
        }
    }

    return bandera;
}

function validarDetalleFormularioLlenos() {
    var valido = true;

    $.each(detalle, function (i, item) {
        //validamos que este seleccionado el tipo de precio
        if (existeColumnaCodigo(4)) {
            if (isEmpty(item.precioTipoId) || item.precioTipoId == 0) {
                mostrarValidacionLoaderClose("Seleccione el tipo de precio");
                valido = false;
                return false;
            }
        }
        if (existeColumnaCodigo(12)) {
            if (isEmpty(item.cantidad) || item.cantidad <= 0) {
                mostrarValidacionLoaderClose("No se especificó un valor válido para Cantidad");
                valido = false;
                return false;
            }
        }
        if (existeMotivoActivoFijo() && isEmpty(item.bienActivoFijoId)) {
            mostrarValidacionLoaderClose("No se relacionó un activo fijo.");
            valido = false;
            return false;
        }

    });

    return valido;
}

function existeMotivoActivoFijo() {
    //BOLETA
    if ($("#cbo_623").length > 0 && (select2.obtenerValor("cbo_623") == "166" || select2.obtenerValor("cbo_623") == "59")) {
        return true;
        //FACTURA
    } else if ($("#cbo_624").length > 0 && (select2.obtenerValor("cbo_624") == "165" || select2.obtenerValor("cbo_624") == "56")) {
        return true;
    }
    return false;
}

function validarDetalleRepetido() {
    var detalleRepetido = false;

    for (var i = 0; i < detalle.length; i++) {
        for (var j = i + 1; j < detalle.length; j++) {
            if (detalle[i]["bienId"] === detalle[j]["bienId"] && detalle[i]["organizadorId"] === detalle[j]["organizadorId"] && detalle[i]["unidadMedidaId"] === detalle[j]["unidadMedidaId"]) {
                detalleRepetido = true;
                break;
            }
        }
    }

    return detalleRepetido;
}

function cargarPersona()
{
    var rutaAbsoluta = URL_BASE + 'index.php?token=1';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
//    var win = window.open(rutaAbsoluta, '_blank');
//    win.focus();
}

function actualizarCboPersona(id) {
    obtenerPersonas(id);
}

function obtenerPersonas(id, personaId = null) {
    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", null);
    ax.addParamTmp("personaId", personaId);
    ax.setTag({id: id, personaId: personaId});
    ax.consumir();
}
function onResponseObtenerPersonas(data)
{
    $("#div_persona").empty();
    var header = '';
    var string = '';
    var footer = '';
    var html = '';

    $.each(data, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 5:
                header = '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                string = '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                $.each(item.data, function (indexPersona, itemPersona) {
                    string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                });
                footer = '</select>';
                html = header + string + footer;
                $("#div_persona").append(html);
                break;
            case 23:
                $("#div_persona_" + item.id).empty();
                header = '<div id ="div_persona_' + item.id + '" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                string = '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                $.each(item.data, function (indexPersona, itemPersona) {
                    string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                });
                footer = '</select>';
                html = header + string + footer;
                $("#div_persona_" + item.id).append(html);
                break;
        }

        switch (parseInt(item.tipo)) {
            case 5:
                $("#cbo_" + item.id).select2({
                    width: '100%'
                }).on("change", function (e) {
                    obtenerPersonaDireccion(e.val);
                });

                if (!isEmpty(personaIdRegistro)) {
                    select2.asignarValor("cbo_" + item.id, personaIdRegistro);
                }
                break;
            case 23:
                $("#cbo_" + item.id).select2({
                    width: '100%'
                }).on("change", function (e) {
                });
                break;
        }
    });

}
function onResponseLoaderBien(data)
{
    if (!isEmpty(data)) {
        select2.recargar("cboBien", data, "id", ["codigo", "descripcion"]);
        select2.asignarValor("cboBien", 0);
    }
}
function cargarBien()
{
    var rutaAbsoluta = URL_BASE + 'index.php?token=2';
//    var win = window.open(rutaAbsoluta, '_blank');
//    win.focus();
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function verificarStockBien(indice)
{
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null)
    {
        mostrarAdvertencia("Seleccionar un producto");
    } else
    {
        obtenerStockPorBien(bienId, indice);
    }
}

function obtenerStockPorBien(bienId, indice)
{

    ax.setAccion("obtenerStockPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.setTag(indice);
    ax.consumir();
}

function relacionarActivoFijo(indice)
{
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (isEmpty(bienId)) {
        mostrarAdvertencia("Seleccionar un producto");
        return;
    }
    $("#indexTablaDetalle").val(indice);
    $.each(detalle, function (i, item) {
        if (item.index == indice) {
            select2.asignarValor("cboActivosFijos", item.bienActivoFijoId);
        }
    });

    $('#modalActivosFijos').modal('show');
}

function guardarRelacionActivoFijo() {
    var indice = $("#indexTablaDetalle").val();
    var bien_activo_fijo_id = select2.obtenerValor("cboActivosFijos");
    if (isEmpty(bien_activo_fijo_id)) {
        mostrarAdvertencia("Seleccionar un activo fijo");
        return;

    }
    $.each(detalle, function (i, item) {
        if (item.index == indice) {
            item.bienActivoFijoId = bien_activo_fijo_id;
        }
    });

    $('#modalActivosFijos').modal('hide');
}


function onResponseObtenerStockPorBien(dataStock, indice)
{
    var tituloModal = '<strong>' + select2.obtenerText("cboBien_" + indice) + '</strong>';
    $('.modal-title').empty();
    $('.modal-title').append(tituloModal);

    var data = [];

    if (!isEmpty(dataStock)) {
        $.each(dataStock, function (i, item) {
            if (item.stock != 0) {
                data.push(item);
            }
        });
    }

    if (!isEmptyData(data)) {
        $('#datatableStock').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "organizador_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 2
                }
            ],
            "destroy": true
        });
    } else {
        var table = $('#datatableStock').DataTable();
        table.clear().draw();
    }

    $('#modalStockBien').modal('show');
}
var importeTotalInafectas = 0;
function calcularImporteDetalle() {
    var importe = 0;
    var importeInafectas = 0;
    let isVentaGratuita = false;
    let isVentaGratuitaObsequio = false;
    //Identificamos si estamos tratando con venta gratuita y además, tipo de venta = obsequio
    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == "18") {
        isVentaGratuita = true;
        let dataComboTipoVenta = dataCofiguracionInicial.documento_tipo_conf.filter(item => item.tipo == 4 && item.codigo == 12);
        if (!isEmpty(dataComboTipoVenta)) {
            let valorSeleccionado = dataComboTipoVenta[0].data.filter(item => item.id == $("#cbo_" + dataComboTipoVenta[0].id).val());
            if (!isEmpty(valorSeleccionado) && valorSeleccionado[0]['valor'] == 0) {
                isVentaGratuitaObsequio = true;
            }
        }
    }

    if (!isEmpty(detalle)) {
        $.each(detalle, function (index, item) {
            importe += (parseFloat(item.cantidad) * parseFloat(item.precio));
        });
        if (isVentaGratuita || movimientoTipoIndicador == 5) {
            importeInafectas = importe;

            if (isVentaGratuitaObsequio) {
                importe = 0;
            } else {
                if (document.getElementById('chkIncluyeIGV').checked) {
                    importeInafectas = devolverDosDecimales(importeInafectas / (1 + igvValor)) * 1;
                }
            }
        }
    }
    importeTotalInafectas = importeInafectas;

//    if (movimientoTipoIndicador != 5 && !isVentaGratuitaObsequio) {
//        if (!isEmpty(detalle)) {
//            $.each(detalle, function (index, item) {
//                importe += (parseFloat(item.cantidad) * parseFloat(item.precio));
//            });
//        }
//    } else {
//        if (!isEmpty(detalle)) {
//            $.each(detalle, function (index, item) {
//                importeInafectas += (parseFloat(item.cantidad) * parseFloat(item.precio));
//            });
//        }
//        importeTotalInafectas = importeInafectas;
//    }
    return importe;
}
var igvValor = 0.18;
var calculoTotal = 0;
function asignarImporteDocumento() {
    var factorImpuesto = 1;
    var documentoTipo = dataCofiguracionInicial.documento_tipo.filter(item => item.id == $("#cboDocumentoTipo").val());
    if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 30) {
        factorImpuesto = -1;
    }

    var calculo, igv;
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {
        calculo = calcularImporteDetalle();
        if (!isEmpty(importes.fleteId) && !isEmpty(importes.seguroId)) {
            var importe_seguro = parseFloat($('#' + importes.seguroId).val());
            var importe_flete = parseFloat($('#' + importes.fleteId).val());
            calculo = calculo + importe_seguro + importe_flete;
        }
        var importe_penalidad = parseFloat($('#contenedorDsco').val());

        calculo = redondearNumerDecimales(calculo + importe_penalidad, 2);

        var importe_exoneracion = 0;
        var importe_otro = 0;
        var icbp = 0;
        if (!isEmpty(importes.otrosId)) {
            importe_otro = parseFloat($('#' + importes.otrosId).val());
        }

        if (!isEmpty(importes.exoneracionId)) {
            importe_exoneracion = parseFloat($('#' + importes.exoneracionId).val());
        }

        if (!isEmpty(importes.icbpId)) {
            icbp = parseFloat($('#' + importes.icbpId).val());
        }

        document.getElementById(importes.calculoId).value = devolverDosDecimales(calculo);
        if (importes.calculoId === importes.subTotalId) {
            if (!isEmpty(importes.igvId)) {
                igv = igvValor * calculo;
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.totalId)) {
                document.getElementById(importes.totalId).value = devolverDosDecimales(calculo + (factorImpuesto * igv) + importe_exoneracion + importe_otro + icbp);
            }
        } else if (importes.calculoId === importes.totalId) {
            if (!isEmpty(importes.igvId)) {
                igv = (calculo - calculo / (1 + igvValor));
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.subTotalId)) {
                document.getElementById(importes.subTotalId).value = devolverDosDecimales(calculo - (factorImpuesto * igv));
            }
        }
        calculoTotal = parseFloat($('#' + importes.totalId).val());

        //Calculamos la detracción
        var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(36);
        if (!isEmpty(dtdTipoPago)) {
            obtenerMontoDetraccion();
        }

        //Calculamos la retención
        var dtdTipoPagoRetencion = obtenerDocumentoTipoDatoXTipoXCodigo(4, "10");
        if (!isEmpty(dtdTipoPagoRetencion)) {
            obtenerMontoRetencion();
        }
    }
//    obtenerMontoDetraccion();
    //recalcular los importes de pago.
    asignarImportePago();
}
function validarImportesLlenos() {
//    breakFunction();
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {
        var importeFinal = document.getElementById(importes.calculoId).value;
        if (isEmpty(importeFinal)) {
            asignarImporteDocumento();
            importeFinal = document.getElementById(importes.calculoId).value;
        }
        var importeFinalSugerido = calcularImporteDetalle();
        var valorPercepcion = 0;
        if (percepcionId != 0) {
            valorPercepcion = $('#txt_' + percepcionId).val();
            if (isEmpty(valorPercepcion)) {
                valorPercepcion = 0;
            }
        }
        if (Math.abs(parseFloat(importeFinalSugerido) + parseFloat(valorPercepcion) - parseFloat(importeFinal)) > 1) {
            mostrarValidacionLoaderClose("El importe total tiene mucha variación con el cálculado por el sistema. No se puede continuar la operación.");
            return false;
        }
    }
    return true;
}
function onChangeCheckIncluyeIGV() {
    modificarDetallePrecios();
    validarImporteLlenar();
    asignarImporteDocumento();
}
function validarImporteLlenar() {
    if (!isEmpty(importes.subTotalId)) {
        if (document.getElementById('chkIncluyeIGV').checked) {
            importes.calculoId = importes.totalId;
        } else {
            importes.calculoId = importes.subTotalId;
        }
    } else {
        importes.calculoId = importes.totalId;
    }
}

function verificarPrecioBien(indice)
{
    indiceVerificarPrecioBien = indice;
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else
    {
        obtenerPrecioPorBien(bienId, indice);
    }
}

function obtenerPrecioPorBien(bienId, indice)
{
    var incluyeIGV = 1;
    if (!document.getElementById('chkIncluyeIGV').checked) {
        incluyeIGV = 0;
    }

    ax.setAccion("obtenerPrecioPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indiceVerificarPrecioBien));
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("incluyeIGV", incluyeIGV);
    ax.setTag(indice);

    ax.consumir();
}

function cambiarAPrecioMinimo(indice, precioTipoId, precioMinimo) {
    if (existeColumnaCodigo(5)) {
        document.getElementById("txtPrecio_" + indice).value = devolverDosDecimales(precioMinimo);
    }
    if (existeColumnaCodigo(4)) {
        select2.asignarValor("cboPrecioTipo_" + indice, precioTipoId);
        $("#cboPrecioTipo_" + indice).select2({width: anchoTipoPrecioTD + 'px'});
    }
    $('#modalPrecioBien').modal('hide');

    hallarSubTotalDetalle(indice);
}

function onResponseObtenerPrecioPorBien(data, indice)
{
    var tituloModal = '<strong>' + select2.obtenerText("cboBien_" + indice) + '</strong>';
    var dataPrecioBien = [];
    if (!isEmpty(data) && existeColumnaCodigo(12)) {
        var descuentoPorcentaje = 0;
        var precioMinimo = 0;
        var accion = '';

        var operador = obtenerOperador();

        $.each(data, function (index, item) {
            //calculo de utilidad porcentaje
            var precioVenta = item.precio * operador;
//            var precioCompra = $('#txtPrecioCompra_' + indiceVerificarPrecioBien).html();
            var precioCompra = $('#txtPrecioCompra_' + indice).html();

            //------------------------
            //ASIGNO EL VALOR DE COMPRA EN CASO NO ESTE CONFIGURADA LA COLUMNA COMPRA  
            if (!existeColumnaCodigo(1)) {
                var indexTemporal = -1;
                $.each(detalle, function (i, item) {
                    if (parseInt(item.index) === parseInt(indice)) {
                        indexTemporal = i;
                        return false;
                    }
                });

                if (indexTemporal > -1) {
                    if (!isEmpty(detalle[indexTemporal].precioCompra)) {
                        precioCompra = detalle[indexTemporal].precioCompra;
                    }
                }
            }
            //-----------------------


            var cantidad = $('#txtCantidad_' + indice).val();

            var utilidadSoles = (precioVenta - precioCompra) * cantidad;

            var subTotal = precioVenta * cantidad;
            var utilidadPorcentaje = 0;
            if (subTotal != 0) {
                utilidadPorcentaje = (utilidadSoles / subTotal) * 100;
            }

            //$('#txtUtilidadPorcentaje_' + indice).html(devolverDosDecimales(utilidadPorcentaje) + " %");
            // fin calculo

            descuentoPorcentaje = (parseFloat(item.descuento) / 100) * parseFloat(utilidadPorcentaje);
            precioMinimo = parseFloat(precioVenta) - (descuentoPorcentaje / 100) * parseFloat(precioVenta);

            if (precioMinimo < precioCompra) {
                precioMinimo = precioCompra + 0.1;
            }

            accion = "<a onclick=\"cambiarAPrecioMinimo(" + indice + "," + item.precio_tipo_id + "," + precioMinimo + ");\">" +
                    "<i class=\"fa fa-arrow-down\"  tooltip-btndata-toggle='tooltip'  style=\"color:#04B404;\" title=\"Seleccionar precio mínimo\"></i></a>";

            dataPrecioBien.push([item.precio_tipo_descripcion,
                precioVenta,
                utilidadPorcentaje,
                descuentoPorcentaje,
                precioMinimo,
                accion]);

        });
    }

    $('#datatablePrecio').dataTable({
        order: [[0, "desc"]],
        "ordering": false,
        "data": dataPrecioBien,
        "columns": [
            {"data": "0"},
            {"data": "1", "sClass": "alignRight"},
            {"data": "2", "sClass": "alignRight"},
            {"data": "3", "sClass": "alignRight"},
            {"data": "4", "sClass": "alignRight"},
            {"data": "5", "sClass": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [1, 2, 3, 4]
            }
        ],
        "destroy": true
    });

    $('.modal-title').empty();
    $('.modal-title').append(tituloModal);

//    if (!isEmpty(data) && existeColumnaCodigo(1)) {
    if (!isEmpty(data)) {
        $('#modalPrecioBien').modal('show');
    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese los precios del producto");
    }
}
function onChangeCheckIGV() {
    var documentoTipo = dataCofiguracionInicial.documento_tipo.filter(item => item.id == $("#cboDocumentoTipo").val());
    if (document.getElementById('chkIGV').checked) {
        if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 30) {
            igvValor = 0.08;
        } else {
            igvValor = 0.18;
        }
    } else {
        igvValor = 0;
    }
    asignarImporteDocumento();
}

//Area de Opcion de Copiar Documento

function validarSoloUnDocumentoDeCopia() {
    var bandera = true;//no hay error

    if (!isEmpty(request.documentoRelacion)) {
        $.each(request.documentoRelacion, function (i, item) {
            if (!isEmpty(item.documentoId)) {
                bandera = false;//hay error
            }
        });
    }

    return bandera;
}

function cargarBuscadorDocumentoACopiar()
{
    let documentoTipoDatoId = obtenerDocumentoTipoDatoIdXTipo(5);
    let personaId = select2.obtenerValor("cbo_" + documentoTipoDatoId);

    if (isEmpty(personaId) || personaId == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Primero debe seleccionar el cliente.");
        return;
    }
//    loaderShow();

    $('#modalDocumentoRelacion').modal('show');

    buscarDocumentoRelacionPorCriterios();
}

function obtenerConfiguracionesInicialesBuscadorCopiaDocumento()
{
    ax.setAccion("obtenerConfiguracionBuscadorDocumentoRelacion");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionBuscadorDocumentoRelacion(data)
{
    $("#cboDocumentoTipoM").select2({
        width: "100%"
    });
    select2.cargar("cboDocumentoTipoM", data.documento_tipo, "id", "descripcion");
    $("#cboPersonaM").select2({
        width: "100%"
    });
    select2.cargar("cboPersonaM", data.persona, "id", "nombre");

//    var table = $('#dtDocumentoRelacion').DataTable();
    //table.clear().draw();

    cargarModalCopiarDocumentos();
}

function cargarModalCopiarDocumentos() {
    var nombreCeldaPersona = 'Persona';
    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
        validarAlmacenOrigenDestino();
        if (origen_destino == "O") {
            nombreCeldaPersona = 'Guía relacionada';
        }
    }

    $('#nombreCeldaTHDocRelacion').html(nombreCeldaPersona);

    $('#modalDocumentoRelacion').modal('show');
    setTimeout(function () {
        cambiarAnchoBusquedaDesplegable();
    }, 100);
}

function buscarDocumentoRelacionPorCriterios() {

    colapsarBuscador();
    obtenerParametrosBusquedaDocumentoACopiar();
    getDataTableDocumentoACopiar();

}

function obtenerDatosBusquedaDocumentoACopiar()
{
    var cadena = "";
    obtenerParametrosBusquedaDocumentoACopiar();

    if (!isEmpty(parametrosBusquedaDocumentoACopiar.documento_tipo_ids))
    {
        cadena += negrita("Documento: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipoM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.persona_id))
    {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerTextMultiple('cboPersonaM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.serie))
    {
        cadena += negrita("Serie: ");
        cadena += $('#txtSerie').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.numero))
    {
        cadena += negrita("Numero: ");
        cadena += $('#txtNumero').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_emision_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_emision_fin;
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin))
    {
        cadena += negrita("Fecha vencimiento: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin;
        cadena += "<br>";
    }
    return cadena;
}

function getDataTableDocumentoACopiar() {
    ax.setAccion("obtenerNotaVentaDocumentos");
    ax.addParamTmp("criterios", parametrosBusquedaDocumentoACopiar);
    ax.addParamTmp("empresaId", commonVars.empresa);

    $('#dtDocumentoRelacion').dataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(true, "#modalDocumentoRelacion"),
        "scrollX": true,
        "order": [[1, "desc"]],
        "columns": [
            {"data": "documento_id", "width": "10%"}, //0
            {"data": "fecha_creacion", "width": "10%"}, //1  
            {"data": "agencia_origen", "width": "10%"}, //2
            {"data": "agencia_destino", "width": "10%"}, //3
            {"data": "documento_tipo_descripcion", "width": "10%"}, //4
            {"data": "persona_codigo_identificacion", "width": "30%"}, //5 
            {"data": "serie_numero", "width": "10%"}, //6 
            {"data": "total", "width": "10%", "sClass": "alignRight"}, //7
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    // return (isEmpty(data)) ? '' : datex.parserFecha(data.date.replace(" 00:00:00", ""));
                    return`
                        <div class="checkbox">
                            <label class="cr-styled">
                                <input type="checkbox" style="text-align: left;" name="checkDocumentoRelacion" id="checkDocumentoRelacion` + data + `" value="` + data + `" data-documento_id=` + row.documento_id + ` data-movimiento_id=` + row.movimiento_id + ` data-moneda_id=` + row.moneda_id + ` data-serie_numero=` + row.serie_numero + ` checked>
                                <i class="fa"></i>
                            </label>
                        </div>
                    `;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : datex.parserFechaHora(data);
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    return data + ' | ' + row.persona_nombre;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    return row.moneda_simbolo + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 7
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            // if (aData['documento_relacionado'] != '0')
            // {
            //     $('td', nRow).css('background-color', '#FFD0D0');
            // }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
    });
    // loaderClose();
}

var parametrosBusquedaDocumentoACopiar = {
    empresa_id: null,
    documento_tipo_ids: null,
    persona_id: null,
    serie: null,
    numero: null,
    fecha_emision_inicio: null,
    fecha_emision_fin: null,
    fecha_vencimiento_inicio: null,
    fecha_vencimiento_fin: null,
    documento_id: documentoId
};

var id_cboDestino = null;
var id_cboMotivoMov = null;
var origen_destino = null;

function validarAlmacenOrigenDestino() {
    if (!isEmpty(obtenerDocumentoTipoDatoIdXTipo(17))) {
        var orgIdDest = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(17));
        if (isEmpty(orgIdDest)) {
            mostrarAdvertencia('Seleccione almacén de llegada');
            return;
        }
    } else {
        mostrarAdvertencia('Seleccione almacén de llegada');
        return;
    }

    var text1 = "";
    var text2 = "";

    text1 = (select2.obtenerText("cbo_" + id_cboDestino)).toLowerCase();
    text2 = (select2.obtenerText("cboOrganizador")).toLowerCase();

    if (text1.indexOf("virtual") == 0 && text2.indexOf("virtual") != 0) {
        parametrosBusquedaDocumentoACopiar.documento_tipo_ids = ["12", "189"];
        parametrosBusquedaDocumentoACopiar.serie = "D"; //destino almacen virtual
        origen_destino = "D";
    } else if (text1.indexOf("virtual") != 0 && text2.indexOf("virtual") == 0) {
        parametrosBusquedaDocumentoACopiar.documento_tipo_ids = ["264"];
        parametrosBusquedaDocumentoACopiar.serie = "O"; //orgien almacen virtual
        origen_destino = "O";
    } else {
        origen_destino = null;
    }
}

function obtenerParametrosBusquedaDocumentoACopiar()
{
    let documentoTipoDatoId = obtenerDocumentoTipoDatoIdXTipo(5);
    parametrosBusquedaDocumentoACopiar = {};
    parametrosBusquedaDocumentoACopiar.persona_id = select2.obtenerValor("cbo_" + documentoTipoDatoId);
    parametrosBusquedaDocumentoACopiar.serie = $('#txtSerieModal').val();
    parametrosBusquedaDocumentoACopiar.numero = $('#txtNumeroModal').val();
    parametrosBusquedaDocumentoACopiar.fecha_inicio = $('#fechaInicioModal').val();
    parametrosBusquedaDocumentoACopiar.fecha_fin = $('#fechaFinModal').val();
    parametrosBusquedaDocumentoACopiar.agencia_origen_id = select2.obtenerIdMultiple("cboAgenciaOrigenModal");
    parametrosBusquedaDocumentoACopiar.agencia_destino_id = select2.obtenerIdMultiple("cboAgenciaDestinoModal");
    parametrosBusquedaDocumentoACopiar.documento_id = documentoId;
}

function agregarDocumentoRelacionTotal() {
//    let tributo = [];
    loaderShow("#modalDocumentoRelacion");

    let banderaErrorCheck = false;
    let dataCheck = $("input[name='checkDocumentoRelacion']");

    let documentoRelacion = [];
    $.each(dataCheck, function (index, item) {
        if (item.checked) {
            let documentoId = item['dataset']['documento_id'];
            let monedaId = item['dataset']['moneda_id'];
            let movimientoId = item['dataset']['movimiento_id'];
            let serie_numero = item['dataset']['serie_numero'];
            if (validarDocumentoRelacionRepetido(documentoId))
            {
                banderaErrorCheck = true;
                mostrarAdvertencia("Documento " + serie_numero + " a copiar ya a sido agregado");
                loaderClose();
                return;
            }

            if (select2.obtenerValor("cboMoneda") != monedaId) {
                banderaErrorCheck = true;
                mostrarAdvertencia("Documento " + serie_numero + " las moneda deben ser iguales, seleccione otro documento, o cambie la moneda.");
                loaderClose();
                return;
            }
            documentoRelacion.push({"documentoId": documentoId, "movimientoId": movimientoId});
        }
    });

    if (banderaErrorCheck) {
        return;
    }

    if (isEmpty(documentoRelacion)) {
        mostrarAdvertencia("Debe seleccionar al menos un documento.");
        loaderClose();
        return;
    }

    ax.setAccion("obtenerDocumentoRelacionNotaVenta");
    ax.addParamTmp("documentoPorRelacionar", documentoRelacion);
//    ax.addParamTmp("documentoRelacionados", request.documentoRelacion);
    ax.consumir();
}

function agregarDocumentoRelacion(documentoTipoOrigenId, documentoId, movimientoId, monedaId, relacionar)
{
    if (relacionar == 0) {
        $("#chkDocumentoRelacion").prop("checked", "");
        $("#divChkDocumentoRelacion").hide();
    }

    loaderShow("#modalDocumentoRelacion");
    //loaderShow();
    if (validarDocumentoRelacionRepetido(documentoId))
    {
        mostrarAdvertencia("Documento a copiar ya a sido agregado");
        loaderClose();
        return;
    }

    if (select2.obtenerValor("cboMoneda") != monedaId && !isEmpty(detalle)) {
        mostrarAdvertencia("Las moneda deben ser iguales, seleccione otro documento, o cambie la moneda.");
        loaderClose();
        return;
    }

    variable.documentoIdCopia = documentoId;
    variable.movimientoIdCopia = movimientoId;

    if (dataCofiguracionInicial.documento_tipo[0].identificador_negocio == 5) {
        var documentoTipo = dataDocumentoTipo[0]['id'];
        ax.setAccion("obtenerNumeroNotaCredito");
        ax.addParamTmp("documentoTipoId", documentoTipo);
        ax.addParamTmp("documentoRelacionadoTipo", documentoTipoOrigenId);
        ax.consumir();
    }
    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDocumentoRelacion");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", request.documentoRelacion);
    ax.consumir();
}

function onResponseObtenerNumeroNotaCredito(data) {
    //colocamos la serie y numero en las cajas de texto
    var idTipoDatoSerie, idTipoDatoNumero;
    idTipoDatoSerie = buscarDocumentoTipoDatoPorTipo(7).id;
    $("#txt_" + idTipoDatoSerie).val(data[0].serie);

    idTipoDatoNumero = buscarDocumentoTipoDatoPorTipo(8).id;
    $("#txt_" + idTipoDatoNumero).val(data[0].numero);

}

function buscarDocumentoTipoDatoPorTipo(tipo) {

    var objDocumentoTipoDato = null;
    if (!isEmpty(dataCofiguracionInicial) && !isEmpty(dataCofiguracionInicial.documento_tipo_conf)) {
        $.each(dataCofiguracionInicial.documento_tipo_conf, function (indexConf, itemConf) {
            if (itemConf.tipo * 1 == tipo) {
                objDocumentoTipoDato = itemConf;
                return false;
            }
        });
    }
    return objDocumentoTipoDato;
}

function agregarCabeceraDocumentoRelacion(documentoTipoOrigenId, documentoId, movimientoId)
{
    loaderShow("#modalDocumentoRelacion");
    //loaderShow();
    if (validarDocumentoRelacionRepetido(documentoId))
    {
        mostrarAdvertencia("Documento a relacionar ya a sido agregado");
        loaderClose();
        return;
    }

    variable.documentoIdCopia = documentoId;
    variable.movimientoIdCopia = movimientoId;

    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDocumentoRelacionCabecera");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", request.documentoRelacion);
    ax.consumir();
}

function validarDocumentoRelacionRepetido(documentoACopiarId)
{
    var resultado = false;
    $.each(request.documentoRelacion, function (index, item) {
        if (!isEmpty(item.documentoId))
        {
            if (item.documentoId == documentoACopiarId)
            {
                resultado = true;
            }
        }

    });

    return resultado;
}

function reinicializarDataTableDetalle() {
    $('#datatable').DataTable({
        "scrollX": true,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": false,
        "autoWidth": true,
        "destroy": true
    });
}

function reinicializarDataTableDetallePenalidad() {
    $('#datatablePenalidad').DataTable({
        "scrollX": true,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": false,
        "autoWidth": true,
        "destroy": true
    });
}


function onResponseObtenerDocumentoRelacionTodos(data) {
    var arrayfecha=[];
    var tamaño = data.dataDocumento.length-1;
    for (let i = 0; i <= tamaño; i++) {
        arrayfecha.push(data['dataDocumento'][i]['fecha_emision']);  }
        let arrayFechas = arrayfecha.map((fechaActual) => new Date(fechaActual) );

        var fechaMayor = new Date(Math.max.apply(null,arrayFechas));
        var fechaMenor = new Date(Math.min.apply(null,arrayFechas));

        if (fechaMayor > fechaTrasladoMayor || fechaTrasladoMayor == null) fechaTrasladoMayor = fechaMayor;
        if (fechaMenor < fechaTrasladoMenor || fechaTrasladoMenor == null) fechaTrasladoMenor = fechaMenor;

        debugger;
        const options = { year: '2-digit',month: '2-digit', day: '2-digit' };
        const formatoFechaMayor = fechaTrasladoMayor.toLocaleDateString('es-ES', options);
        const formatoFechaMenor = fechaTrasladoMenor.toLocaleDateString('es-ES', options);

        if(formatoFechaMayor!=''){
            debugger;
            document.getElementById('txtComentario').value = 'Traslado de cargo y paquetería del '+formatoFechaMenor+' al '+formatoFechaMayor;
        }

        $('#modalDocumentoRelacion').modal('hide');

        //    detalle = [];
        //    indiceLista = [];
        //    banderaCopiaDocumento = 0;
        //    indexDetalle = 0;
        //
        //    if (data.detalleDocumento.length > 5) {
        //        nroFilasReducida = data.detalleDocumento.length;
        //    } else {
        //        nroFilasReducida = 5;
        //    }

        $('#datatable').DataTable().clear().destroy();
        //    limpiarDetalle();
        reinicializarDataTableDetalle();

        //    detalleDocumentoRelacion = data.detalleDocumento;
        //    cargarDataDocumentoACopiar(data.documentoACopiar, data.dataDocumentoRelacionada);
        let detalleCopia = data.detalleDocumento;
        let documentoCopia = data.dataDocumento;
        if (!isEmpty(detalleCopia) && !isEmpty(documentoCopia)) {
            $.each(documentoCopia, function (index, item) {

            if ((item.monto_costo_reparto * 1) > 0) {
                let bienCostoReparto = {};
                bienCostoReparto.organizador_id = null;
                bienCostoReparto.bien_id = -2;
                bienCostoReparto.cantidad = 1;
                bienCostoReparto.unidad_medida_id = -1;
                bienCostoReparto.bien_codigo = 'SV001';
                bienCostoReparto.precio_tipo_id = 2;
                bienCostoReparto.bien_tipo_id = -1;
                bienCostoReparto.tipo = 1;
                bienCostoReparto.movimiento_id = item.movimiento_id;
                bienCostoReparto.documento_id = item.documento_id;
                bienCostoReparto.documento_serie_numero = item.serie_numero;
                bienCostoReparto.bien_descripcion = 'Costo reparto';
                bienCostoReparto.valor_monetario = item.monto_costo_reparto * 1;
                bienCostoReparto.comentario = bienCostoReparto.bien_descripcion;
                detalleCopia.push(bienCostoReparto);
            }

            if ((item.monto_devolucion_gasto * 1) > 0) {
                let bienDevolucionReparto = {};
                bienDevolucionReparto.organizador_id = null;
                bienDevolucionReparto.bien_id = -2;
                bienDevolucionReparto.cantidad = 1;
                bienDevolucionReparto.unidad_medida_id = -1;
                bienDevolucionReparto.bien_codigo = 'SV001';
                bienDevolucionReparto.precio_tipo_id = 2;
                bienDevolucionReparto.bien_tipo_id = -1;
                bienDevolucionReparto.tipo = 1;
                bienDevolucionReparto.movimiento_id = item.movimiento_id;
                bienDevolucionReparto.documento_id = item.documento_id;
                bienDevolucionReparto.documento_serie_numero = item.serie_numero;
                bienDevolucionReparto.bien_descripcion = 'Devolución de cargo';
                bienDevolucionReparto.valor_monetario = item.monto_devolucion_gasto * 1;
                bienDevolucionReparto.comentario = bienDevolucionReparto.bien_descripcion;
                detalleCopia.push(bienDevolucionReparto);
            }

            if ((item.monto_otro_gasto * 1) > 0) {
                let bienOtrosGastos = {};
                bienOtrosGastos.organizador_id = null;
                bienOtrosGastos.bien_id = -2;
                bienOtrosGastos.cantidad = 1;
                bienOtrosGastos.unidad_medida_id = -1;
                bienOtrosGastos.bien_codigo = 'SV001';
                bienOtrosGastos.precio_tipo_id = 2;
                bienOtrosGastos.bien_tipo_id = -1;
                bienOtrosGastos.tipo = 1;
                bienOtrosGastos.movimiento_id = item.movimiento_id;
                bienOtrosGastos.documento_id = item.documento_id;
                bienOtrosGastos.documento_serie_numero = item.serie_numero;
                bienOtrosGastos.bien_descripcion = (isEmpty(item.otro_gasto_descripcion) ? 'Otros cargos' : item.otro_gasto_descripcion);
                bienOtrosGastos.valor_monetario = item.monto_otro_gasto * 1;
                bienOtrosGastos.comentario = bienOtrosGastos.bien_descripcion;
                detalleCopia.push(bienOtrosGastos);
            }

            if ((item.ajuste_precio * 1) > 0) {
                let bienOtrosGastos = {};
                bienOtrosGastos.organizador_id = null;
                bienOtrosGastos.bien_id = -2;
                bienOtrosGastos.cantidad = 1;
                bienOtrosGastos.unidad_medida_id = -1;
                bienOtrosGastos.bien_codigo = 'SV001';
                bienOtrosGastos.precio_tipo_id = 2;
                bienOtrosGastos.bien_tipo_id = -1;
                bienOtrosGastos.tipo = 1;
                bienOtrosGastos.movimiento_id = item.movimiento_id;
                bienOtrosGastos.documento_id = item.documento_id;
                bienOtrosGastos.documento_serie_numero = item.serie_numero;
                bienOtrosGastos.bien_descripcion = 'Ajuste de precio';
                bienOtrosGastos.valor_monetario = item.ajuste_precio * 1;
                bienOtrosGastos.comentario = bienOtrosGastos.bien_descripcion;
                detalleCopia.push(bienOtrosGastos);
            }

            if ((item.costo_recojo_domicilio * 1) > 0) {
                let bienOtrosGastos = {};
                bienOtrosGastos.organizador_id = null;
                bienOtrosGastos.bien_id = -2;
                bienOtrosGastos.cantidad = 1;
                bienOtrosGastos.unidad_medida_id = -1;
                bienOtrosGastos.bien_codigo = 'SV001';
                bienOtrosGastos.precio_tipo_id = 2;
                bienOtrosGastos.bien_tipo_id = -1;
                bienOtrosGastos.tipo = 1;
                bienOtrosGastos.movimiento_id = item.movimiento_id;
                bienOtrosGastos.documento_id = item.documento_id;
                bienOtrosGastos.documento_serie_numero = item.serie_numero;
                bienOtrosGastos.bien_descripcion = 'Recojo a domicilio';
                bienOtrosGastos.valor_monetario = item.costo_recojo_domicilio * 1;
                bienOtrosGastos.comentario = bienOtrosGastos.bien_descripcion;
                detalleCopia.push(bienOtrosGastos);
            }
        });

    }
    //    debuggeCr
    cargarDetalleDocumentoRelacion(detalleCopia);
    cargarDocumentoRelacionadoDeCopia(data.dataDocumento);
}

var detalleDocumentoRelacion = [];
function onResponseObtenerDocumentoRelacion(data) {
//    $('#modalDocumentoRelacion').modal('hide');

    if (data.documentoACopiar[0].incluye_igv == 1) {
        $("#chkIncluyeIGV").prop("checked", "checked");
    } else {
        $("#chkIncluyeIGV").prop("checked", "");
    }

    select2.asignarValorQuitarBuscador("cboMoneda", data.documentoACopiar[0].moneda_id);
    modificarSimbolosMoneda(data.documentoACopiar[0].moneda_id, dataCofiguracionInicial.moneda[document.getElementById('cboMoneda').options.selectedIndex]);

    detalle = [];
    indiceLista = [];
    banderaCopiaDocumento = 0;
    indexDetalle = 0;

    if (data.detalleDocumento.length > 5) {
        nroFilasReducida = data.detalleDocumento.length;
    } else {
        nroFilasReducida = 5;
    }

    $('#datatable').DataTable().clear().destroy();
    limpiarDetalle();
    reinicializarDataTableDetalle();

    detalleDocumentoRelacion = data.detalleDocumento;
    cargarDataDocumentoACopiar(data.documentoACopiar, data.dataDocumentoRelacionada);
    cargarDetalleDocumentoRelacion(data.detalleDocumento);

    cargarDocumentoRelacionadoDeCopia(data.documentosRelacionados);

    //cargar los datos copiados de programacion
    cargarPagoProgramacion(data.dataPagoProgramacion);
}

function cargarDocumentoRelacionadoDeCopia(data) {

    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            if (!validarDocumentoRelacionRepetido(parseInt(item.documento_id))) {
                let detalleLink = (item.documento_tipo_descripcion + ": " + item.serie_numero);

                let documentoRelacion = {
                    documentoId: parseInt(item.documento_id),
                    movimientoId: parseInt(item.movimiento_id),
                    tipo: 9,
                    documentoPadreId: varDocumentoPadreId,
                    posicion: contadorDocumentoCopiadoAVisualizar,
                    detalleLink: detalleLink
                };

                $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + item.documento_id + ", " + item.movimiento_id + ")' name='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp&nbsp&nbsp");
                // $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

                request.documentoRelacion.push(documentoRelacion);
                contadorDocumentoCopiadoAVisualizar++;
            }
        });

        varDocumentoPadreId = null;

    }
}


function onResponseObtenerDocumentoRelacionCabecera(data) {
    cargarDataDocumentoACopiar(data.dataDocumento, data.dataDocumentoRelacionada);
    if (!isEmpty(variable.documentoIdCopia) && !isEmpty(variable.movimientoIdCopia))
    {
        request.documentoRelacion.push({
            documentoId: variable.documentoIdCopia,
            movimientoId: variable.movimientoIdCopia,
            tipo: 9,
            documentoPadreId: null
        });
        varDocumentoPadreId = variable.documentoIdCopia;

        variable.documentoIdCopia = null;
        variable.movimientoIdCopia = null;
    }

    if (!isEmpty(detalleLink))
    {
        if (bandera.mostrarDivDocumentoRelacion)
        {
            $('#divDocumentoRelacion').show();

            if (banderachkDocumentoRelacion === 0) {
                // $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);
                banderachkDocumentoRelacion = 1;
            }
        }

        $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
        $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiarSinDetalle(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a><br>");
        // $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

        request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleLink;
        request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
        contadorDocumentoCopiadoAVisualizar++;
        detalleLink = null;
    }

    $('#modalDocumentoRelacion').modal('hide');

    cargarDocumentoRelacionadoDeCopia(data.documentoCopiaRelaciones);
}

var contadorDocumentoCopiadoAVisualizar = 0;
function cargarDataDocumentoACopiar(data, dataDocumentoRelacionada)
{
    var documentoTipo = "", serie = "", numero = "";
    if (!bandera.mostrarDivDocumentoRelacion)
    {
        if (!isEmpty(data))
        {

            $.each(data, function (index, item) {

                switch (parseInt(item.tipo)) {
                    case 5:
                        select2.asignarValor('cbo_' + item.otro_documento_id, item.valor);
                        var indice = select2.obtenerValor('cbo_' + item.otro_documento_id);
                        if (indice == item.valor) {
                            obtenerPersonaDireccion(item.valor);
                        }
                        break;
                    case 6:
//                    case 7:
//                    case 8:
                        $('#txt_' + item.otro_documento_id).val(item.valor);
                        break;
                        //case 9: //fecha emision
                    case 10:
                    case 11:
                        $('#datepicker_' + item.otro_documento_id).val(formatearFechaJS(item.valor));
                        break;
                }
            });
            bandera.mostrarDivDocumentoRelacion = true;

        }

        if (!isEmpty(dataDocumentoRelacionada))
        {
            $.each(dataDocumentoRelacionada, function (index, item) {
                if (isEmpty(item.documento_tipo_dato_origen))
                {
                    select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                } else
                {

                    switch (item.tipo * 1) {
                        case 26: // vendedor
                            select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                            break;
                        default:
                            $('#txt_' + item.documento_tipo_dato_destino).val(item.valor);
                            if (isEmpty($('#txt_' + item.documento_tipo_dato_destino).val())) {
                                $('#datepicker_' + item.documento_tipo_dato_destino).val(formatearFechaJS(item.valor));
                            }
                    }
                }
            });
        }
    }

    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {

            documentoTipo = item.documento_tipo_descripcion;

            switch (parseInt(item.tipo)) {
                case 7:
                    if (!isEmpty(item.valor))
                    {
                        serie = item.valor;
                    }

                    break;
                case 8:
                    if (!isEmpty(item.valor))
                    {
                        numero = item.valor;
                    }
                    break;
            }
        });

        detalleLink = documentoTipo + ": " + serie + " - " + numero;
    }
}

function cargarDetalleDocumentoRelacion(data)
{
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cargarDataTableDocumentoACopiar(
                    cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad,
                            item.unidad_medida_id, item.valor_monetario, item.organizador_descripcion,
                            (item.bien_codigo + ' | ' + item.bien_descripcion), item.unidad_medida_descripcion, item.precio_tipo_id,
                            item.movimiento_bien_detalle, item.dataUnidadMedida, item.precio_tipo_descripcion,
                            null
                            , item.bien_tipo_id, item.bien_alto
                            , item.bien_ancho
                            , item.bien_longitud
                            , item.bien_peso
                            , item.tipo
                            , item.bien_peso_volumetrico
                            , item.bien_factor_volumetrico
                            , item.cantidad_entregada
                            , item.movimiento_bien_id
                            , item.movimiento_id
                            , item.documento_id
                            , item.documento_serie_numero
                            , item.comentario
                            )
                    );
        });
    }
}

function obtnerStockParaProductosDeCopia() {
    if (!isEmpty(detalle)) {
        //ALMACEN DE LLEGADA PARA TRANSFERENCIA INTERNA
        var organizadorDestinoId = null;
        if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
            var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
            if (!isEmpty(dtdOrganizadorId)) {
                organizadorDestinoId = select2.obtenerValor('cbo_' + dtdOrganizadorId);
            }
        }

        loaderShow();
        ax.setAccion("obtenerStockParaProductosDeCopia");
        ax.addParamTmp("organizadorDefectoId", organizadorIdDefectoTM);
        ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("organizadorDestinoId", organizadorDestinoId);
        ax.consumir();
    }
}

function onResponseObtenerStockParaProductosDeCopia(data) {
    $.each(data, function (index, item) {
        onResponseObtenerStockActual(item);
    });

    $('#modalDocumentoRelacion').modal('hide');
}

var dataFaltaAsignarCantidadXOrganizador = [];
function cargarModalParaAgregarOrganizador(data)
{
    var stockOrganizadores;
    dataFaltaAsignarCantidadXOrganizador.push(data);
    var html = '<div>';
    html += '<p id="titulo_' + data.bien_id + '">' + data.bien_descripcion + ' : ' + data.cantidad + ' ' + data.unidad_medida_descripcion + '</p>';
    html += '</div>';

    if (isEmpty(data.stock_organizadores))
    {
        html += '<p style="color:red;">No hay stock para este bien.</p>';
    } else
    {
        html += '<div class="table">';
        html += '<table id="datatableStock_' + data.bien_id + '" class="table table-striped table-bordered">';
        html += '<thead>';
        html += '<tr>';
        html += '<th style="text-align:center;">Organizador</th>';
        html += '<th style="text-align:center;">Disponible</th>';
        html += '<th style="text-align:center;">A usar</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';

        stockOrganizadores = obtenerstockPredefinido(data.stock_organizadores, data.cantidad);
        $.each(data.stock_organizadores, function (index, item) {

            html += '<tr>';
            html += '<td >' + item.organizadorDescripcion + '</td>';
            html += '<td >' + formatearCantidad(item.stock) + '</td>';
            html += '<td >';
            html += '<input type="number" min="0" id="txt_' + item.organizadorId + '_' + data.bien_id + '" style="text-align: right;" ';
            html += ' value="' + obtenerStockPredefinidoXOrganizador(item.organizadorId, stockOrganizadores) + '">';
            html += '</td>';
            html += '</tr>';
        });


        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    }


    $('#contenedorAsignarStockXOrganizador').append(html);

    $('#datatableStock_' + data.bien_id).dataTable({
        "columns": [
            {"width": "10px"},
            {"width": "10px", "sClass": "alignRight"},
            {"width": "10px", "sClass": "alignCenter"}
        ],
        "dom": '<"top">rt<"bottom"><"clear">',
        "order": [[1, "desc"]]
    });
}

function obtenerStockPredefinidoXOrganizador(organizadorId, stockOrganizadores)
{
    var stock = "";
    $.each(stockOrganizadores, function (index, item) {
        if (item.organizadorId == organizadorId)
        {
            stock = formatearCantidad(item.asignado);
        }
    });

    return stock;
}
function obtenerstockPredefinido(data, stockDeseado)
{
    var array = [];
//    var organizadores = [];
    $.each(data, function (index, item) {
        array.push({organizadorId: item.organizadorId,
            stock: item.stock,
            asignado: 0});
    });

    array = ordenacionBurbuja(array);

    $.each(array, function (index, item) {
        if (parseFloat(stockDeseado) > parseFloat(item.stock))
        {
            array[index]['asignado'] = item.stock;
            stockDeseado = stockDeseado - item.stock;

        } else
        {
            array[index]['asignado'] = stockDeseado;
            stockDeseado = 0;
        }
    });

    return array;

}

function ordenacionBurbuja(array) {

    var tamanio = array.length;
    var i, j;
    var aux;
    for (i = 0; i < tamanio; i++)
    {
        for (j = 0; j < (tamanio - 1); j++)
        {
            if (array[j].stock < array[j + 1].stock)
            {
                aux = array[j];
                array[j] = array[j + 1];
                array[j + 1] = aux;
            }
        }

    }

    return array;
}

function asignarStockXOrganizador()
{
    var suma = 0;
    var valorStockUnitario;
    var organizadorUsado = [];

    var listaDetalleDocumentoACopiar = [];
    var banderaSalirEach = 0;

    $.each(dataFaltaAsignarCantidadXOrganizador, function (index, itemData) {
        if (banderaSalirEach === 0)
        {
            if (!isEmpty(itemData.stock_organizadores))
            {
                $.each(itemData.stock_organizadores, function (index1, item) {

                    valorStockUnitario = $('#txt_' + item.organizadorId + '_' + itemData.bien_id).val();
                    if (!isEmpty(valorStockUnitario))
                    {
                        if (valorStockUnitario < 0)
                        {
                            mostrarAdvertencia("El valor a usar es menor que cero para el bien " + itemData.bien_descripcion + " en el organizador " + item.organizadorDescripcion);
                            banderaSalirEach = 1;
                        } else
                        {
                            if (parseFloat(valorStockUnitario) > parseFloat(itemData.cantidad))
                            {
                                mostrarAdvertencia("El valor a usar es mayor al requerido para el bien " + itemData.bien_descripcion);
                                banderaSalirEach = 1;
                            } else
                            {
                                if (parseFloat(valorStockUnitario) > parseFloat(item.stock))
                                {
                                    mostrarAdvertencia("El valor a usar es mayor que el stock para el bien " + itemData.bien_descripcion);
                                    banderaSalirEach = 1;
                                } else
                                {
                                    if (valorStockUnitario > 0)
                                    {
                                        suma = parseFloat(suma) + parseFloat(valorStockUnitario);
                                        organizadorUsado.push({
                                            organizadorDescripcion: item.organizadorDescripcion,
                                            organizadorId: item.organizadorId,
                                            usado: valorStockUnitario
                                        });
                                    }
                                }
                            }
                        }
                    }

                });
            }
            if (banderaSalirEach === 0)
            {
                if (parseFloat(suma) > 0 && parseFloat(suma) <= itemData.cantidad)
                {

                    $.each(organizadorUsado, function (index2, itemOrganizadorUsado) {
                        listaDetalleDocumentoACopiar.push(
                                cargarFormularioDetalleACopiar(itemOrganizadorUsado.organizadorId, itemData.bien_id, itemOrganizadorUsado.usado,
                                        itemData.unidad_medida_id, itemData.valor_monetario, itemOrganizadorUsado.organizadorDescripcion,
                                        itemData.bien_descripcion, itemData.unidad_medida_descripcion)
                                );
                    });
                } else
                {
                    if (!isEmpty(itemData.stock_organizadores))
                    {
                        mostrarAdvertencia("Los valores ingresados no son correctos para el bien " + itemData.bien_descripcion);
                        banderaSalirEach = 1;
                    }

                }
            }

            organizadorUsado = [];
            suma = 0;

//            }
        }
    });

    if (banderaSalirEach === 0)
    {
        $.each(listaDetalleDocumentoACopiar, function (index, item) {
            cargarDataTableDocumentoACopiar(item);
        });

        listaDetalleDocumentoACopiar = [];

        if (banderaSalirEach === 0)
        {
            $('#modalAsignarOrganizador').modal('hide');

        }
    }


}

function cargarFormularioDetalleACopiar(organizadorId, bienId, cantidad, unidadMedidaId, precio,
        organizadorDesc, bienDesc, unidadMedidaDesc, precioTipoId, movimientoBienDetalle, dataUnidadMedida, precioTipoDesc,
        movimientoBienId, bienTipoId, bienAlto, bienAncho, bienLongitud, bienPeso, tipo, bienPesoVolumetrico, bienFactorVolumetrico, cantidadEntregada,
        movimientoBienPadreId, movimientoPadreId, documentoPadreId, serieNumeroPadre, comentarioDetalle) {

    var objDetalle = {};//Objeto para el detalle    
    var detDetalle = [];
    objDetalle.precioCompra = 0;
    objDetalle.precio = precio;
    objDetalle.cantidad = cantidad;
    objDetalle.precioTipoId = precioTipoId;
    objDetalle.precioTipoDesc = precioTipoDesc;
    objDetalle.stockBien = 0;
    objDetalle.bienId = bienId;
    objDetalle.bienDesc = bienDesc;
    objDetalle.unidadMedidaId = unidadMedidaId;
    objDetalle.unidadMedidaDesc = unidadMedidaDesc;
    objDetalle.organizadorId = organizadorId;
    objDetalle.organizadorDesc = organizadorDesc;
    objDetalle.bien_alto = bienAlto;
    objDetalle.bien_ancho = bienAncho;
    objDetalle.bien_longitud = bienLongitud;
    objDetalle.bien_peso = bienPeso;
    objDetalle.bien_peso_volumetrico = bienPesoVolumetrico;
    objDetalle.bien_factor_volumetrico = bienFactorVolumetrico;
    objDetalle.cantidadEntregada = cantidadEntregada;
    objDetalle.movimientoBienPadreId = movimientoBienPadreId;
    objDetalle.movimientoPadreId = movimientoPadreId;
    objDetalle.documentoPadreId = documentoPadreId;
    objDetalle.serieNumeroPadre = serieNumeroPadre;
    objDetalle.comentarioDetalle = comentarioDetalle;
    //fin columna dinamica        
    var stockBien = 0;

    objDetalle.stockBien = stockBien;
    objDetalle.bienTipoId = bienTipoId;
    objDetalle.bienTramoId = null;
    objDetalle.detalle = detDetalle;
    objDetalle.tipo = tipo;
    objDetalle.movimientoBienId = movimientoBienId;
    return objDetalle;
}

var banderachkDocumentoRelacion = 0;
var varDocumentoPadreId;
function cargarDataTableDocumentoACopiar(data)
{
    if (!isEmpty(data))
    {
        valoresFormularioDetalle = data;
        agregarConfirmado();
        asignarImporteDocumento();
    }
}

var banderaEliminarDocumentoRelacion = 0;
var eliminadosArray = new Array();
function eliminarDocumentoACopiar(indice)
{
    loaderShow();
    let documentoId;
    let movimientoId;
    $.each(request.documentoRelacion, function (index, item) {
        if (item.posicion == indice) {
            documentoId = item.documentoId;
            movimientoId = item.movimientoId;

            request.documentoRelacion.splice(index, 1);
            return false;
        }
    });

    for (var i = detalle.length - 1; i >= 0; i--) {
        if (detalle[i]['documentoPadreId'] == documentoId || detalle[i]['movimientoPadreId'] == movimientoId) {
            detalle.splice(i, 1);
        }
    }

    // $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20);

    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    if (!isEmpty(request.documentoRelacion)) {
        $.each(request.documentoRelacion, function (index, item) {

            if (!isEmpty(item.documentoId))
            {
                $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoRelacion(" + item.documentoId + "," + item.documentoId + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "]</a>");
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiar(" + item.posicion + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        });
    }


    dibujarTabla();

    asignarImporteDocumento();

    setTimeout(function () {
        loaderClose();
    }, 600);
}

function eliminarDocumentoACopiarSinDetalle(indice)
{
    var contRelacion = 1;
    //eliminar las relaciones hijas    
    $.each(request.documentoRelacion, function (index, item) {
        if (item.documentoPadreId == request.documentoRelacion[indice].documentoId) {
            request.documentoRelacion[index].documentoId = null;
            request.documentoRelacion[index].movimientoId = null;

            contRelacion++;
        }
    });


    // $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20 * contRelacion);
    banderaEliminarDocumentoRelacion = 1;

    var banderaExisteDocumentoRelacionado = 0;
    //$("#contenedorDetalle").css("height", $("#contenedorDetalle").height() - (40 * indexDetalle));

    request.documentoRelacion[indice].documentoId = null;
    request.documentoRelacion[indice].movimientoId = null;
    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    $.each(request.documentoRelacion, function (index, item) {

        if (!isEmpty(item.documentoId))
        {
            $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoRelacion(" + item.posicion + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "]</a>");

            if (item.tipo == 1) {
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiarSinDetalle(" + item.posicion + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>");
            }

            $('#linkDocumentoACopiar').append("<br>");

            banderaExisteDocumentoRelacionado = 1;
        }
    });

    if (banderaExisteDocumentoRelacionado === 0)
    {
        bandera.mostrarDivDocumentoRelacion = false;
        $('#divDocumentoRelacion').hide();
        $("#divChkDocumentoRelacion").show();
        $("#chkDocumentoRelacion").prop("checked", "checked");
    }
}

function visualizarDocumentoRelacion(documentoId, movimientoId)
{

    loaderShow();
    ax.setAccion("obtenerDocumentoRelacionVisualizar");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("movimientoId", movimientoId);
    ax.consumir();
}

function onResponseObtenerDocumentoRelacionVisualizar(data, documentoOrigenId)
{
    $("#formularioDetalleDocumento").empty();
//    habilitarBotonGenerarDocumentoDevolucionCargo();
    var serieDocumento = '';
    if (!isEmpty(data['dataDocumento'][0].serie)) {
        serieDocumento = data['dataDocumento'][0].serie + " - ";
    }
    let titulo = "<b>" + (data['dataDocumento'][0].documento_tipo_descripcion.toUpperCase() + " " + serieDocumento + data['dataDocumento'][0].numero);

    if (!isEmpty(data['dataDocumento'][0].agencia_destino) && !isEmpty(data['dataDocumento'][0].agencia_origen)) {
        titulo = titulo + " | " + data['dataDocumento'][0].agencia_origen + " - " + data['dataDocumento'][0].agencia_destino
    }

    if (data['dataDocumento'][0]['bandera_es_cargo'] == 1) {
        titulo = titulo + ' | <span class="label label-info">Dev. cargo</span>';
    }
    titulo = titulo + "</b>";
    $('#tituloVisualizacionModal').html(titulo);
    var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);

    var html = '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Fecha:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + (fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);
    html = html + '</div>';
    html = html + '</div>';


    if (!isEmpty(data['dataDocumento'][0].fecha_vencimiento)) {
        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Fecha Vencimiento:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        html = html + (fechaVencimiento.dia + "/" + fechaVencimiento.mes + "/" + fechaVencimiento.anio);
        html = html + '</div>';
        html = html + '</div>';
    }

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Moneda:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].moneda_descripcion;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '</div>';


    html = html + '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Cliente:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].codigo_identificacion + ' | ' + data['dataDocumento'][0].persona_nombre;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Dirección facturación:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].persona_direccion;
    html = html + '</div>';
    html = html + '</div>';


    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Autorizado:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].persona_autorizada;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '</div>';



    html = html + '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Remitente:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].codigo_identificacion_origen + ' | ' + data['dataDocumento'][0].persona_origen_nombre;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Destinatario:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].codigo_identificacion_destino + ' | ' + data['dataDocumento'][0].persona_destino_nombre;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-2">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Tipo:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].modalidad_descripcion;
    html = html + '</div>';
    html = html + '</div>';

    if (!isEmpty(data['dataDocumento'][0].documento_tipo_pago)) {
        html = html + '<div class="col-md-2">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Tipo pago:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + data['dataDocumento'][0].documento_tipo_pago;
        html = html + '</div>';
        html = html + '</div>';
    }

    html = html + '</div>';


    html = html + '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Dirección origen:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].persona_direccion_origen;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Dirección destino:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data['dataDocumento'][0].persona_direccion_destino;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Guía relación:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + (!isEmpty(data['dataDocumento'][0].guia_relacion) ? data['dataDocumento'][0].guia_relacion : "");
    html = html + '</div>';
    html = html + '</div>';

    html = html + '</div>';


    if (!isEmpty(data['dataDocumento'][0].documento_cargo_id)) {

        html = html + '<div class="col-md-8"   style="padding-left: 0px;">';

        html = html + '<div class="row">';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>' + (!isEmpty(data['dataDocumento'][0].otro_gasto_descripcion) ? data['dataDocumento'][0].otro_gasto_descripcion : "Otros costos") + ':</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_otro_gasto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Devolución de cargo:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_devolucion_gasto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '</div>';

        html = html + '<div class="row">';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Costo reparto:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_costo_reparto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Sub total:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].subtotal);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '</div>';

        html = html + '<div class="row">';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>IGV:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].igv);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-6">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Total:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].total);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '</div>';

        html = html + '</div>';

        html = html + '<div class="col-md-4">';
        var fechaEmisionCargo = separarFecha(data['dataDocumento'][0].documento_cargo_fecha_emision);
        html = html + `<div class="mini-stat clearfix">
                            <span class="mini-stat-icon bg-info"><i class="ion-arrow-return-left"></i></span>
                            <div class="mini-stat-info text-center">
                                <span class="counter">` + (fechaEmisionCargo.dia + "/" + fechaEmisionCargo.mes + "/" + fechaEmisionCargo.anio) + `</span>
                                ` + data['dataDocumento'][0].agencia_destino + ` - ` + data['dataDocumento'][0].agencia_origen + `
                            </div>
                            <div class="mini-stat-info text-center">
                                    <span class="counter">` + data['dataDocumento'][0].documento_cargo_serie + ` - ` + data['dataDocumento'][0].documento_cargo_numero + `</span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;` + data['dataDocumento'][0].documento_cargo_usuario + `
                            </div>
                        </div>`;
        html = html + '</div>';


        html = html + '</div>';
    } else {
        html = html + '<div class="row">';

        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>' + (!isEmpty(data['dataDocumento'][0].otro_gasto_descripcion) ? data['dataDocumento'][0].otro_gasto_descripcion : "Otros costos") + ':</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_otro_gasto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Devolución de cargo:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_devolucion_gasto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Costo reparto:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].monto_costo_reparto);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '</div>';

        html = html + '<div class="row">';
        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Sub total:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].subtotal);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>IGV:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].igv);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '<div class="col-md-4">';
        html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
        html = html + '<label>Total:</label>';
        html = html + '</div>';
        html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
        html = html + formatearNumero(data['dataDocumento'][0].total);
        html = html + '</div>';
        html = html + '</div>';

        html = html + '</div>';
        html = html + '</div>';
    }

    appendFormDetalle(html);
    $('#tabsDistribucionMostrar').show();
    $('a[href="#tabDistribucionDetalle"]').click();
    cargarDetalleDocumento(data.detalleDocumento);
    cargarDetalleEntregas(data.detalleEntregaRelacionadas, data['dataDocumento'][0]['id']);

    $('#modalDetalleDocumento').modal('show');
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);

    debugger;
    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor))
            {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
                    case 3:
                        valor = fechaArmada(valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
                    case 14:
                    case 15:
                    case 16:
                    case 38:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {
    if (!isEmptyData(data)) {
        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
        html += "<th style='text-align:center;'>Artículo</th>";
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Cantidad Entregada</th> ";
        html += "<th style='text-align:center;'>Cantidad Pendiente</th> ";
        html += "<th style='text-align:center;'>Precio Unitario</th>";
        html += "<th style='text-align:center;'>P. Total</th>";
        html += "</tr>";
        tHeadDetalle.append(html);

        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalle');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            let descripcion = item['bien_descripcion'] + (!isEmpty(item.bien_alto) && item.bien_alto > 0 ? ' ' + formatearNumero(item.bien_alto) + ' x ' + formatearNumero(item.bien_ancho) + ' x ' + formatearNumero(item.bien_longitud) + ' ( ' + formatearNumero(item.bien_peso) + ' Kg)' : '');
            html += "<tr>";
            html += "<td>" + item.bien_codigo + " | " + descripcion + "</td> ";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['cantidad'], 2) + "</td>";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['cantidad_entregada'], 2) + "</td>";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales((item['cantidad'] * 1 - item['cantidad_entregada'] * 1), 2) + "</td>";
//            html += "<td>" + item.bien_codigo + "</td>";
//            html += "<td>" + descripcion + "</td> ";
//            html += "<td>" + item.simbolo + "</td> ";
            html += "<td style='text-align:right;'>" + formatearNumero(item['valor_monetario']) + "</td>";
            html += "<td style='text-align:right;'>" + formatearNumero(item['sub_total']) + "</td>";
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


function cargarDetalleEntregas(data, documentoId) {
    if (!isEmptyData(data)) {
        $("#liDistribucion").show();
        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalleEntrega');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
        html += "<th style='text-align:center;'>Artículo</th>";
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Entregado</th>";
        html += "<th style='text-align:center;'>Fecha</th>";
        html += "<th style='text-align:center;'>N° Entrega</th>";
        html += "<th style='text-align:center;'>Usuario</th> ";
        html += "<th style='text-align:center;'>Acciones</th> ";
        html += "</tr>";
        tHeadDetalle.append(html);

        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalleEntrega');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {

            let descripcion = item['bien_descripcion'] + (!isEmpty(item.bien_alto) && item.bien_alto > 0 ? ' ' + formatearNumero(item.bien_alto) + ' x ' + formatearNumero(item.bien_ancho) + ' x ' + formatearNumero(item.bien_longitud) + ' ( ' + formatearNumero(item.bien_peso) + ' Kg)' : '');
            html += "<tr>";
            html += "<td>" + item.bien_codigo + " | " + descripcion + "</td> ";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['cantidad'], 2) + "</td>";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['cantidad_entregada'], 2) + "</td>";
            html += "<td style='text-align:center;'>" + ((isEmpty(item.fecha_emision)) ? '' : datex.parserFecha(item.fecha_emision.replace(" 00:00:00", ""))) + "</td>";
            html += "<td style='text-align:center;'>" +
                    '<a href="#" onclick="visualizarDocumento(' + item.documento_id + ',' + item.movimiento_id + ',\'modalDetalleDocumento\', ' + documentoId + ')" title="Visualizar" style="cursor:pointer;color:-webkit-link;    text-decoration: underline;">' + item.serie + '-' + item.numero + '</a>'
                    + "</td>";
            html += "<td style='text-align:center;'>" + item.usuario_creacion + "</td> ";
            html += "<td style='text-align:center;'>" +
                    '<a onclick="imprimirDocumento(' + item.documento_id + ',null,\'modalDetalleDocumento\')" title="Imprimir pedido"><b><i class="fa fa-print" style="color:#088A08"></i></b></a>'
                    + "</td> ";
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        $("#liDistribucion").hide();
//        var table = $('#datatableEntrega').DataTable();
//        table.clear().draw();
    }
}

function obtenerCheckDocumentoACopiar()
{
    cabecera.chkDocumentoRelacion = 1;
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}


function actualizarBusquedaDocumentoRelacion() {
    buscarDocumentoRelacionPorCriterios();
}

var listaDetalleFaltante;
var listaDetalleOriginal;
function onResponseEnviar(data) {
    cerrarModalAnticipo();
    if (!isEmpty(data.generarDocumentoAdicional)) {
        habilitarBoton();
        loaderClose();
        $('#modalDocumentoGenerado').modal('show');

        $("#dtBodyDocumentoGenerado").empty();

        var dataOrganizador = data.dataOrganizador;
        var dataProveedor = data.dataProveedor;
        var dataDocumentoTipo = data.dataDocumentoTipo;

        listaDetalleOriginal = data.dataDetalle;

//        var cuerpo = "";
        //LLENAR TABLA DETALLE
        for (var i = 0; i < listaDetalleOriginal.length; i++) {
            //cuerpo += llenarFilaDetalleFaltantesTabla(i, listaDetalleOriginal[i]);
            $('#dtBodyDocumentoGenerado').append(llenarFilaDetalleFaltantesTabla(i, listaDetalleOriginal[i]));
        }
//        $('#dtBodyDocumentoGenerado').append(cuerpo);

        //LLENAR COMBOS
        for (var i = 0; i < listaDetalleOriginal.length; i++) {
            //cargarOrganizadorDetalleCombo(data.organizador, i);
            //cargarUnidadMedidadDetalleCombo(i);
            cargarTipoDocumentoCombo(dataDocumentoTipo, dataOrganizador, dataProveedor, i);
            cargarOrganizadorProveedorCombo(dataOrganizador, dataProveedor, i);
        }
    } else if (!isEmpty(data.anticipos)) {
        mostrarAnticipos(data);
    } else {
        if (!isEmpty(data.dataPlantilla)) {
            dibujarModalCorreos(data);
        } else if (!isEmpty(data.dataDocumentoPago)) {
            abrirModalPagos(data);

        } else if (!isEmpty(data.dataAtencionSolicitud)) {
            //asignarAtencion();
            abrirModalAtencionSolicitud(data);
        } else if (!isEmpty(data.resEfact) && data.resEfact.esDocElectronico == 1) {
            onResponseRespuestaEfact(data);
        } else if (boton.accion == 'enviarEImprimir') {
            var dataImp = data.dataImprimir;

            if (!isEmpty(dataImp.dataDocumento)) {
                cargarDatosImprimir(dataImp, 1);
            } else if (!isEmpty(dataImp.iReport)) {
                abrirDocumentoPDF(dataImp, URL_BASE + '/reporteJasper/documentos/');
            } else {
                abrirDocumentoPDF(dataImp, 'vistas/com/movimiento/documentos/');
            }
        } else {
            cargarPantallaListar();
        }
    }

}

function onResponseRespuestaEfact(data) {
    var dataDocElec = data.resEfact.respDocElectronico;
    var titulo = '';
    //CORRECTO
    if (dataDocElec.tipoMensaje == 1 || dataDocElec.tipoMensaje == 0) {
        mostrarOk(dataDocElec.mensaje);
        if (dataDocElec.titulo === 'undefined') {
            titulo = '';
        } else {
            titulo = dataDocElec.titulo;
        }
        swal({
            title: "Registro correcto" + titulo,
            text: dataDocElec.mensaje,
            type: "success",
            html: true,
            showCancelButton: false,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (!isEmpty(dataDocElec.urlPDF)) {
                    window.open(URL_BASE + 'pdf2.php?url_pdf=' + dataDocElec.urlPDF + '&nombre_pdf=' + dataDocElec.nombrePDF);
                }
                cargarPantallaListar();
            }
        });
    }

    //ERROR CONTROLADO SUNAT NO VA A REGISTRAR - WARNING EXCEPTION EN NEGOCIO - PARA NEGAR COMMIT

    //ERROR DESCONOCIDO
    if (dataDocElec.tipoMensaje == 2 || dataDocElec.tipoMensaje == 3 || dataDocElec.tipoMensaje == 4) {
        var mensaje = dataDocElec.mensaje;
        if (dataDocElec.tipoMensaje == 4) {
            mensaje += "<br><br> Se registró en el sistema, pero fue rechazada por SUNAT.";
        } else {
            mensaje += "<br><br> Se registró en el sistema, posteriormente se intentará registrar en SUNAT."
        }
        swal({
            title: "Error desconocido",
            text: mensaje,
            type: "warning",
            html: true,
            showCancelButton: false,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (!isEmpty(dataDocElec.urlPDF)) {
                    window.open(URL_BASE + 'pdf2.php?url_pdf=' + dataDocElec.urlPDF + '&nombre_pdf=' + dataDocElec.nombrePDF);
                }
                cargarPantallaListar();
            }
        });
    }
}


function abrirDocumentoPDF(data, contenedor) {
    var link = document.createElement("a");
    link.download = data.nombre + '.pdf';
    link.href = contenedor + data.pdf;
    link.click();


    ax.setAccion("eliminarPDF");
    ax.addParamTmp("url", data.url);
    ax.consumir();
}

var dataRespuestaCorreo;
function dibujarModalCorreos(data) {

    dataRespuestaCorreo = data;

    $("#tbodyDetalleCorreos").empty();
    var html = '';

    if (!isEmpty(data.dataCorreos)) {
        $.each(data.dataCorreos, function (index, itemh) {
            html += '<tr>' +
                    '<td>' +
                    '<div class="checkbox" style="margin: 0px;">' +
                    '<label class="cr-styled">' +
                    '<input onclick="" type="checkbox" name="chekCorreo" id="correo' + index + '" value="' + itemh + '" checked>' +
                    '<i class="fa"></i> ' +
                    itemh +
                    '</label>' +
                    ' </div>' +
                    '</td>' +
                    '</tr>'
                    ;
        });

        $("#tbodyDetalleCorreos").append(html);
    } else {
        $("#rowDataTableCorreo").hide();
    }

    $('#modalCorreos').modal('show');
}

function enviarCorreosMovimiento() {
    var txtCorreo = $('#txtCorreo').val();
    var correosSeleccionados = new Array();

    if (!isEmpty(dataRespuestaCorreo.dataCorreos)) {
        var chekCorreo = document.getElementsByName('chekCorreo');

        $.each(chekCorreo, function (index, item) {
            if (item.checked == true) {
                correosSeleccionados.push(item.value);
            }
        });
    }

    if (isEmpty(txtCorreo) && isEmpty(correosSeleccionados)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione o ingrese un correo, para enviar email.");
        return;
    }

    loaderShow('#modalCorreos');
    ax.setAccion("enviarCorreosMovimiento");
    ax.addParamTmp("txtCorreo", txtCorreo);
    ax.addParamTmp("correosSeleccionados", correosSeleccionados);
    ax.addParamTmp("respuestaCorreo", dataRespuestaCorreo);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.consumir();
}

function cancelarEnvioCorreos() {
    $('#modalCorreos').modal('hide');
    $('.modal-backdrop').hide();
    cargarPantallaListar();
}

function llenarFilaDetalleFaltantesTabla(indice, dataDetalle) {
    var fila = "<tr id=\"trDetalleFaltante_" + indice + "\">"
            + "<td style='border:0; width: 40%; vertical-align: middle;'>" + agregarBienDetalleFaltanteTabla(indice, dataDetalle['bienDesc']) + "</td>"
            + "<td style='border:0; width: 10%; vertical-align: middle; '>" + agregarCantidadDetalleFaltanteTabla(indice, dataDetalle['cantidad']) + "</td>"
            + "<td style='border:0; width: 20%; vertical-align: middle; '>" + agregarTipoDocumento(indice) + "</td>"
            + "<td style='border:0; width: 30%; vertical-align: middle; '>" + agregarComboOrganizadorProveedor(indice) + "</td>"
            + "</tr>";

    return fila;

}

function agregarBienDetalleFaltanteTabla(i, valor) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"text\" id=\"txtBien_" + i + "\" name=\"txtBien_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"" + valor + "\" style=\"text-align: left;\" readonly=true /></div>";

    return $html;
}

function agregarCantidadDetalleFaltanteTabla(i, valor) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<input type=\"text\" id=\"txtCantidadFaltante_" + i + "\" name=\"txtCantidadFaltante_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"" + valor + "\" style=\"text-align: right;\" readonly=true /></div>";

    return $html;
}

function agregarTipoDocumento(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboTipoDocumento_" + i + "\" id=\"cboTipoDocumento_" + i + "\" class=\"select2\">" +
            "</select></div>";

    return $html;
}

function agregarComboOrganizadorProveedor(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
            "<select name=\"cboOrganizadorProveedor_" + i + "\" id=\"cboOrganizadorProveedor_" + i + "\" class=\"select2\">" +
            "</select></div>";

    return $html;
}

function cargarTipoDocumentoCombo(dataDocumentoTipo, dataOrganizador, dataProveedor, indice) {
    //loaderShow();

    // tipo entrada salida
//    $('#cboTipoDocumento_' + indice).append('<option value="1">Guía</option>');
//    $('#cboTipoDocumento_' + indice).append('<option value="2">Solicitud de pedido</option>');

    if (!isEmpty(dataOrganizador[indice])) {
        select2.cargar("cboTipoDocumento_" + indice, dataDocumentoTipo, "documento_tipo_id", "descripcion");
        select2.asignarValor("cboTipoDocumento_" + indice, dataDocumentoTipo[0]['documento_tipo_id']);
    } else {
        $('#cboTipoDocumento_' + indice).append('<option value="-1">Solicitud de compra</option>');
        select2.asignarValor("cboTipoDocumento_" + indice, -1);
    }

    $("#cboTipoDocumento_" + indice).select2({
        width: '100%'
    }).on("change", function (e) {

        //loaderShow();
//        asignarOrganizadorProveedor(e.val, indice, dataOrganizador, dataProveedor);
        //obtenerStockActual(indice);
    });

}

function asignarOrganizadorProveedor(tipoDocumentoId, indice, dataOrganizador, dataProveedor) {
    if (tipoDocumentoId != -1) {
        if (!isEmpty(dataOrganizador[indice])) {
            select2.cargar("cboOrganizadorProveedor_" + indice, dataOrganizador[indice], "organizadorId", "descripcion");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataOrganizador[indice][0]["organizadorId"]);
        }
    } else {
        if (!isEmpty(dataProveedor[indice])) {
            select2.cargar("cboOrganizadorProveedor_" + indice, dataProveedor[indice], "persona_id", "persona_nombre");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataProveedor[indice][0]["persona_id"]);
        }
    }

    //alert(tipoDocumentoId)    ;
}

function cargarOrganizadorProveedorCombo(dataOrganizador, dataProveedor, indice) {

    $("#cboOrganizadorProveedor_" + indice).select2({
        width: '100%'
    });

    if (!isEmpty(dataOrganizador[indice])) {
        select2.cargar("cboOrganizadorProveedor_" + indice, dataOrganizador[indice], "organizadorId", "descripcion");
        select2.asignarValor("cboOrganizadorProveedor_" + indice, dataOrganizador[indice][0]["organizadorId"]);
    } else {
        if (!isEmpty(dataProveedor[indice])) {
            // solo proveedor
//            select2.asignarValor("cboTipoDocumento_" + indice, 2);
            $("#cboTipoDocumento_" + indice).attr('disabled', 'disabled');

            select2.cargar("cboOrganizadorProveedor_" + indice, dataProveedor[indice], "persona_id", "persona_nombre");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataProveedor[indice][0]["persona_id"]);
        }
    }
}

var listaDetallePedidos;
var listaDetalleGuia;
var listaDetalleVenta;

function guardarDocumentoGenerado() {

    listaDetallePedidos = [];
    listaDetalleGuia = [];
    listaDetalleVenta = [];
    var proveedor = null;

    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");

    var indGuia = 0;
    var indPed = 0;


    var totalGuia = 0;
    var totalProv = 0;
    for (var i = 0; i < listaDetalleOriginal.length; i++) {
        var tipoDocumentoId = select2.obtenerValor("cboTipoDocumento_" + i);
        var organizadorProveedorId = select2.obtenerValor("cboOrganizadorProveedor_" + i);
        var organizadorProveedorText = select2.obtenerText("cboOrganizadorProveedor_" + i);

        if (tipoDocumentoId != -1) {
            listaDetalleGuia.push(listaDetalleOriginal[i]);
            listaDetalleGuia[indGuia]["tipoDocumentoId"] = tipoDocumentoId;
            listaDetalleGuia[indGuia]["organizadorId"] = organizadorProveedorId;
            listaDetalleGuia[indGuia]["organizadorDesc"] = organizadorProveedorText;

            totalGuia = totalGuia + listaDetalleGuia[indGuia]["cantidad"] * listaDetalleGuia[indGuia]["precio"];

            indGuia++;
        } else {
            proveedor = select2.obtenerValor("cboOrganizadorProveedor_" + i);

            listaDetallePedidos.push(listaDetalleOriginal[i]);
//            listaDetallePedidos[indPed]["organizadorId"] = organizadorIdDefectoTM;
            listaDetallePedidos[indPed]["proveedorId"] = proveedor;

            indPed++;
        }
    }

    var checkIgv = 0;

    if (!isEmpty(importes.subTotalId)) {
        if (document.getElementById('chkIncluyeIGV').checked) {
            checkIgv = 1;
        }
    } else {
        checkIgv = opcionIGV;
    }

    $('#modalDocumentoGenerado').modal('hide');
    loaderShow();
    deshabilitarBoton();
    ax.setAccion("guardarDocumentoGenerado");
    //guardar guia y nota
    ax.addParamTmp("detalleGuia", listaDetalleGuia);
    ax.addParamTmp("detallePedido", listaDetallePedidos);
    //ax.addParamTmp("proveedorId", proveedor);
    ax.addParamTmp("totalGuia", totalGuia);
    //ax.addParamTmp("totalProv", totalProv);

    //guardar venta
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalleVenta", listaDetalleOriginal);
    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("checkIgv", checkIgv);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("accionEnvio", boton.accion);
    ax.consumir();
}

function obtenerBienTipoIdPorBienId(bienId) {
    var bienTipoId = 0;

    $.each(dataCofiguracionInicial.bien, function (index, item) {
        if (item.id == bienId) {
            bienTipoId = item.bien_tipo_id;
            return false;
        }
    });
    return bienTipoId;
}

function limpiarDetalle() {
    detalle = [];
    indiceLista = [];
    banderaCopiaDocumento = 0;
    indexDetalle = 0;
    nroFilasEliminados = 0;
    numeroItemFinal = 0;

    $('#dgDetalle').empty();
    $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * nroFilasReducida);
    llenarTablaDetalle(dataCofiguracionInicial);
}

var numeroItemFinal = 0;
function agregarFila() {
    if (nroFilasInicial > nroFilasReducida || parseInt(dataDocumentoTipo[0]['cantidad_detalle']) == 0) {
        //LLENAR TABLA DETALLE        
        var fila = llenarFilaDetalleTabla(nroFilasReducida);

        $('#datatable tbody').append(fila);

        //LLENAR COMBOS
        cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, nroFilasReducida);
        cargarUnidadMedidadDetalleCombo(nroFilasReducida);
        cargarBienDetalleCombo(dataCofiguracionInicial.bien, nroFilasReducida);
        cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, nroFilasReducida);

        // nroFilasInicial++;
        nroFilasReducida++;

        $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * (nroFilasReducida - nroFilasEliminados));
    } else {
        $('#divTodasFilas').hide();
        $('#divAgregarFila').hide();
    }

}


// funcionalidad de tramos registro

function verificarTipoUnidadMedida(indice)
{
    var bienId = select2.obtenerValor("cboBien_" + indice);
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida_" + indice);
    if (isEmpty(bienId)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else if (isEmpty(unidadMedidaId)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar unidad de medida");
    } else {
        //verificar el tipo de unidad de medida (longitud)
        loaderShow();
        ax.setAccion("verificarTipoUnidadMedidaParaTramo");
        ax.addParamTmp("unidadMedidaId", unidadMedidaId);
        ax.setTag(indice);
        ax.consumir();

    }
}

function onResponseVerificarTipoUnidadMedidaParaTramo(data) {
    if (isEmpty(data)) {
        mostrarAdvertencia("Seleccione una unidad de medida de tipo longitud");
    } else {

        var tituloModal = '<strong>PRODUCTO: ' + select2.obtenerText("cboBien_" + data.indice) + '</strong>';

        limpiarMensajesTramo();
        $('#txtCantidadTramo').val('');
        $('#indiceTramo').val(data.indice);

        // unidad medida (metros)
        $('#cboUnidadMedidaTramo').empty();
        $('#cboUnidadMedidaTramo').append('<option value="157">Metro(s)</option>');

        $("#cboUnidadMedidaTramo").select2({
            width: '100%'
        });

        $('#bienTramoRegistro').empty();
        $('#bienTramoRegistro').append(tituloModal);
        $('.modal-title').empty();
        $('.modal-title').append("<strong>REGISTRAR TRAMO</strong>");
        $('#modalTramoBienRegistro').modal('show');

    }
}

function registrarTramoBien() {
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedidaTramo");
    var cantidadTramo = $('#txtCantidadTramo').val();
    var indiceTramo = $('#indiceTramo').val();

    if (validarFormularioModalTramo(unidadMedidaId, cantidadTramo)) {
        var bienId = select2.obtenerValor("cboBien_" + indiceTramo);

        loaderShow();
        ax.setAccion("registrarTramoBien");
        ax.addParamTmp("unidadMedidaId", unidadMedidaId);
        ax.addParamTmp("cantidadTramo", cantidadTramo);
        ax.addParamTmp("bienId", bienId);
        ax.consumir();
    }
}

function validarFormularioModalTramo(unidadMedidaId, cantidadTramo) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajesTramo();

    if (unidadMedidaId === "" || unidadMedidaId === null || espacio.test(unidadMedidaId) || unidadMedidaId.length === 0) {
        $("#msjTipoUnidadMedidaTramo").text("La unidad de medida es obligatorio").show();
        bandera = false;
    }

    if (cantidadTramo === "" || cantidadTramo === null || espacio.test(cantidadTramo) || cantidadTramo.length === 0) {
        $("#msjCantidadTramo").text("Cantidad es obligatorio").show();
        bandera = false;
    } else if (cantidadTramo <= 0) {
        $("#msjCantidadTramo").text("Cantidad tiene que se positivo").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesTramo() {
    $("#msjTipoUnidadMedidaTramo").hide();
    $("#msjCantidadTramo").hide();
}

function onResponseRegistrarTramoBien(data) {
    if (data[0]["vout_exito"] == 0) {
        mostrarAdvertencia(data[0]["vout_mensaje"]);
    } else {
        mostrarOk(data[0]["vout_mensaje"]);
        $('#modalTramoBienRegistro').modal('hide');
    }
}

// funcionalidad de tramos busqueda

function listarTramosBien(indice)
{
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else
    {
        loaderShow();
        ax.setAccion("obtenerTramoBien");
        ax.addParamTmp("bienId", bienId);
        ax.setTag(indice);
        ax.consumir();
    }
}

function onResponseObtenerTramoBien(data, indice)
{


    var tituloModal = '<strong>PRODUCTO: ' + select2.obtenerText("cboBien_" + indice) + '</strong>';
//    Unidad medida	Cantidad  Accion

    var dataTramoBien = [];
    if (!isEmpty(data)) {
        var accion = '';

        $.each(data, function (index, item) {
            accion = "<a onclick=\"cambiarACantidadTramo(" + indice + "," + item.unidad_medida_id + "," + item.cantidad + "," + item.bien_tramo_id + ");\">" +
                    "<i class=\"fa fa-arrow-down\"  tooltip-btndata-toggle='tooltip'  style=\"color:#04B404;\" title=\"Seleccionar tramo\"></i></a>";

            dataTramoBien.push([item.unidad_medida_descripcion,
                item.cantidad,
                accion]);
        });
    }

    $('#datatableTramoBien').dataTable({
        order: [[1, "asc"]],
        "ordering": false,
        "data": dataTramoBien,
        "columns": [
            {"data": "0"},
            {"data": "1", "sClass": "alignRight"},
            {"data": "2", "sClass": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 1
            }
        ],
        "destroy": true
    });


    $('#bienTramoBusqueda').empty();
    $('#bienTramoBusqueda').append(tituloModal);
    $('.modal-title').empty();
    $('.modal-title').append("<strong>SELECCIONAR TRAMO</strong>");
    $('#modalTramoBienBusqueda').modal('show');
}

var bienTramoId = null;
function cambiarACantidadTramo(indice, unidadMedidaId, cantidad, tramoId) {
    bienTramoId = tramoId;

    if (existeColumnaCodigo(12)) {
        document.getElementById("txtCantidad_" + indice).value = devolverDosDecimales(cantidad);
    }
    select2.asignarValor("cboUnidadMedida_" + indice, unidadMedidaId);
    $("#cboUnidadMedida_" + indice).select2({width: anchoUnidadMedidaTD + 'px'});

    $('#modalTramoBienBusqueda').modal('hide');
    obtenerStockActual(indice);
    hallarSubTotalDetalle(indice);
}

// recalculo de precio de compra y utilidades
function recalculoPrecioCompraUtilidades() {
//    alert('recalculoPrecioCompraUtilidades');

    primeraFechaEmision = $('#datepicker_' + fechaEmisionId).val();
//    banderaPrimeraFE=true;

    $.each(detalle, function (indice, item) {
        obtenerPrecioCompra(item.index, item.unidadMedidaId, item.bienId);
    });

}

function obtenerPrecioCompra(indice, unidadMedidaId, bienId) {
    loaderShow();
    ax.setAccion("obtenerPrecioCompra");
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
    ax.setTag(indice);
    ax.consumir();
}

function onResponseObtenerPrecioCompra(data) {
    if (!isEmpty(data)) {
        var operador = obtenerOperador();

        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + data.indice).html(devolverDosDecimales(data.precioCompra * operador));
        } else {
            varPrecioCompra = devolverDosDecimales(data.precioCompra * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

// recalculo de precio de compra y utilidades
function modificarPrecioCompra() {

    $.each(detalle, function (indice, item) {
        obtenerPrecioCompra(item.index, item.unidadMedidaId, item.bienId);
    });

}

function onResponseObtenerPersonaDireccionTexto(data) {
    if (isEmpty(data)) {
        $('#txt_' + textoDireccionId).val('');
    } else {
        $('#txt_' + textoDireccionId).val(data[0]['direccion']);
    }

}

function modificarDetallePrecios() {
    if (!isEmpty(detalle)) {
        loaderShow();
        var operador = obtenerOperador();

        ax.setAccion("modificarDetallePrecios");
        ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("operador", operador);
        ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
        ax.consumir();
    }
}

function onResponseModificarDetallePrecios(data) {
    $.each(data, function (indice, item) {
        if (existeColumnaCodigo(5)) {
            $("#txtPrecio_" + item.index).val(devolverDosDecimales(item.precio));
        }
        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + item.index).html(devolverDosDecimales(item.precioCompra));
        } else {
            varPrecioCompra = devolverDosDecimales(item.precioCompra);
        }
        hallarSubTotalDetalle(item.index);
    });
}

function modificarSimbolosMoneda(monedaId, moneda) {
//    alert(simbolo);
    monedaSimbolo = moneda.simbolo;
    monedaBase = monedaId;

    $('#simTotal').html(monedaSimbolo);
    $('#simPercepcion').html(monedaSimbolo);
    $('#simIGV').html(monedaSimbolo);
    $('#simSubTotal').html(monedaSimbolo);
    $('#simTotalUtildiad').html(monedaSimbolo);
    $('#simPU').html(monedaSimbolo);
    $('#simST').html(monedaSimbolo);
    $('#simPC').html(monedaSimbolo);
    $('#simUD').html(monedaSimbolo);

    $('#simFlete').html(monedaSimbolo);
    $('#simSeguro').html(monedaSimbolo);
    $('#simOtros').html(monedaSimbolo);
    $('#simExonerado').html(monedaSimbolo);
}

function modificarPreciosMoneda(monedaId, moneda) {
    if (!isEmpty(detalle) && existeColumnaCodigo(5)) {
        swal({
            title: " ¿Desea continuar?",
            text: "Se va a modificar los precios a " + moneda.descripcion,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                modificarSimbolosMoneda(monedaId, moneda);
                alertaModificarPrecioMoneda(monedaId, moneda);

            } else {
                select2.asignarValorQuitarBuscador("cboMoneda", monedaBase);
            }
        });
    }
}

function alertaModificarPrecioMoneda(monedaId, moneda) {
    swal({
        title: "Escoja una opción",
        text: "1: Convertir el precio con el tipo de cambio de la fecha de emisión.\n\
                   2: Modificar con el precio registrado previamente en el sistema.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "2",
        cancelButtonColor: '#d33',
        cancelButtonText: "1",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            modificarDetallePreciosXMonedaXOpcion(2);
        } else {
            modificarDetallePreciosXMonedaXOpcion(1);
        }
    });
}

function modificarDetallePreciosXMonedaXOpcion(opcion) {
    loaderShow();
    var operador = obtenerOperador();

    ax.setAccion("modificarDetallePreciosXMonedaXOpcion");
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("operador", operador);
    ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
    ax.addParamTmp("opcion", opcion);
    ax.consumir();
}

function onResponseModificarDetallePreciosXMonedaXOpcion(data) {
    $.each(data, function (indice, item) {
        if (existeColumnaCodigo(5)) {
            $("#txtPrecio_" + item.index).val(devolverDosDecimales(item.precio));
        }
        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + item.index).html(devolverDosDecimales(item.precioCompra));
        } else {
            varPrecioCompra = devolverDosDecimales(item.precioCompra);
        }
        hallarSubTotalDetalle(item.index);
    });
}

function dibujarBotonesDeEnvio(data) {

    var html = '<a href="#" class="btn btn-danger" onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>';
    var accion = '';
    var estilo = '';
    $('#divAccionesEnvio').empty();

//    var htmlPredet = '';
//    if (!isEmpty(data.accionEnvioPredeterminado)) {
//        accion = data.accionEnvioPredeterminado[0];
//        if (!isEmpty(accion.color)) {
////            estilo='style="color: '+accion.color+'"';
//            estilo = '';
//        }
//        htmlPredet = '&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="enviar(\'' + accion.funcion + '\')" name="env" id="env"><i class="' + accion.icono + '" ' + estilo + '></i> ' + accion.descripcion + '</button>';
//    }
//
//    if (!isEmpty(data.accionesEnvio)) {
//        accion = data.accionesEnvio;
//
//        html += '&nbsp;&nbsp;<div class="btn-group dropup">' +
//                htmlPredet +
//                '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" name="envOpciones" id="envOpciones"><span class="caret"></span></button>' +
//                '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
//
//        $.each(accion, function (index, item) {
//            estilo = '';
//            if (!isEmpty(item.color)) {
//                estilo = 'style="color: ' + item.color + '"';
//            }
//
//            html += '<li><a href="#" onclick="enviar(\'' + item.funcion + '\')"><i class="' + item.icono + '" ' + estilo + '></i>&nbsp;&nbsp; ' + item.descripcion + '</a></li>';
//        });
//
//        html += '</ul></div>';
//
//    }

    html += '&nbsp;&nbsp;<button type="button" class="btn btn-info" onclick="enviar(\'' + 'guardar' + '\')" name="env" id="enviar"><i class="fa fa-save"></i> Guardar</button>';

    html += '&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="enviar(\'' + 'confirmar' + '\')" name="env" id="enviarConfirmacion"><i class="ion-android-send" ></i> Confirmar</button>';


    $("#divAccionesEnvio").append(html);

}


//here
$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

function buscarDocumentoRelacion() {
    ax.setAccion("buscarDocumentoRelacion");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarDocumentoRelacion(data) {
    var dataPersona = data.dataPersona;
    var dataDocumentoTipo = data.dataDocumentoTipo;
    var dataSerieNumero = data.dataSerieNumero;

    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >';
            html += '<span class="col-md-1"><i class="ion-person"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.codigo_identificacion + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.nombre + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }

    if (!isEmpty(dataDocumentoTipo)) {
        $.each(dataDocumentoTipo, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" >';
            html += '<span class="col-md-1"><i class="fa fa-files-o"></i></span>';
            html += '<span class="col-md-11">';
            html += '<label style="color: #141719;">' + item.descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }


    if (!isEmpty(dataSerieNumero)) {
        $.each(dataSerieNumero, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorSerieNumero(\'' + item.serie + '\',\'' + item.numero + '\')" >';
            html += '<span class="col-md-1"><i class="ion-document"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.serie_numero + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.documento_tipo_descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }
    $("#ulBuscadorDesplegable2").append(html);
}


function busquedaPorTexto(tipo, texto, tipoDocumento) {

    var tipoDocumentoIds = [];
    if (!isEmpty(tipoDocumento)) {
        tipoDocumentoIds.push(tipoDocumento);
    }

    if (tipo == 5) {
        llenarParametrosBusqueda(texto, tipoDocumentoIds, null, null, null, null);
    }

}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusqueda(null, null, serie, numero, null, null);
}

function llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision) {
    obtenerParametrosBusquedaDocumentoACopiar()

    parametrosBusquedaDocumentoACopiar.serie = serie;
    parametrosBusquedaDocumentoACopiar.numero = numero;
    parametrosBusquedaDocumentoACopiar.persona_id = personaId
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = fechaEmision;
    parametrosBusquedaDocumentoACopiar.documento_tipo_ids = tipoDocumentoIds;
//    loaderShow();

    getDataTableDocumentoACopiar();
}

$('#txtBuscar').keyup(function (e) {
    var bAbierto = $(this).attr("aria-expanded");

    if (!eval(bAbierto)) {
        $(this).dropdown('toggle');
    }

});

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

var personaIdRegistro = null;
function setearPersonaRegistro(personaId) {
    personaIdRegistro = personaId;
    let dtdPersonaId = obtenerDocumentoTipoDatoIdXTipo(5);
    obtenerPersonas('_' + dtdPersonaId, personaId);
    obtenerPersonaDireccion(personaIdRegistro);
}

function visualizarCambioPersonalizado(monedaId) {
    if (cambioPersonalizadoId != 0) {
        if (monedaId != monedaBaseId) {
            fc = ""
            $('#txt_' + cambioPersonalizadoId).val('');
            $("#contenedorCambioPersonalizado").show();

            obtenerTipoCambioDatepicker();
        } else {
            $('#txt_' + cambioPersonalizadoId).val('');
            $("#contenedorCambioPersonalizado").hide();
        }
    }
}

function obtenerTipoCambioDatepicker() {
    if (fechaEmisionId != 0 /*&& cambioPersonalizadoId != 0*/) {
        var fecha = $("#datepicker_" + fechaEmisionId).val();
        obtenerTipoCambioXFecha(fecha);
    }
}

var fc = "";
function obtenerTipoCambioXFecha(fecha) {
    //var fecha = obtenerFechaActual();
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXFecha");
        ax.addParam("fecha", fecha);
        ax.addParamTmp("documentoId", null);
        ax.consumir();
        fc = fecha;
    }
}

function onResponseObtenerTipoCambioXFecha(data) {
    if (!isEmptyData(data)) {
        $('#txt_' + cambioPersonalizadoId).val(data[0]['equivalencia_venta']);
        $('#tipoCambio').val('');
        $('#tipoCambio').val(data[0]['equivalencia_venta']);
    } else {
        $('#txt_' + cambioPersonalizadoId).val('');
    }
}

function setearDescripcionProducto(indice) {
    if (existeColumnaCodigo(16)) {
        var descripcion = select2.obtenerText("cboBien_" + indice);

        descripcion = descripcion.split("|");
        descripcion = descripcion[1].trim();

        $('#txtProductoDescripcion_' + indice).val(descripcion);
        $('#txtProductoDescripcion_' + indice).removeAttr("readonly");
    }
}

function setearUnidadMedidaDescripcion(indice) {
    if (existeColumnaCodigo(17)) {
        var descripcion = select2.obtenerText("cboUnidadMedida_" + indice);

        $('#txtUnidadMedidaDescripcion_' + indice).val(descripcion);
        $('#txtUnidadMedidaDescripcion_' + indice).removeAttr("readonly");
    }
}

function existeColumnaCodigo(codigo) {
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    var existe = false;
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            if (parseInt(item.codigo) === parseInt(codigo)) {
                existe = true;
                return false;
            }
        });
    }

    return existe;
}

function obtenerFechaActual() {
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    hoy = dd + '/' + mm + '/' + yyyy;

    return hoy;
}

var fechaEmisionAnterior;
function onChangeFechaEmision() {
    if (documentoTipoTipo == 1) {
        if (!validarCambioFechaEmision) {
            if (!isEmpty(detalle)) {
                swal({
                    title: "Confirmación de actualización de precio promedio",
                    text: "¿Está seguro de actualizar los precios promedios a la fecha de emisión " + $('#datepicker_' + fechaEmisionId).val() + '?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#33b86c",
                    confirmButtonText: "Si!",
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        modificarDetallePrecios();
                    } else {
                        validarCambioFechaEmision = true;
                        $('#datepicker_' + fechaEmisionId).datepicker('setDate', fechaEmisionAnterior);
                    }
                    fechaEmisionAnterior = $('#datepicker_' + fechaEmisionId).val();
                });
            }
        }
        validarCambioFechaEmision = false;
    }
}

function onChangeTipoPago() {
    var tipoPagoId = select2.obtenerValor('cboTipoPago');


    if (tipoPagoId == 2) {
        if (calculoTotal > 0) {
            mostrarModalProgramacionPago();

        } else {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Total debe ser positivo.");
            select2.asignarValor('cboTipoPago', 1);
        }
    } else {
        $('#aMostrarModalProgramacion').hide();
        $('#idFormaPagoContado').show();
    }
}

function cancelarProgramacion() {
    $('#modalProgramacionPagos').modal('hide');

    if (!isEmpty(listaPagoProgramacion)) {
        swal({
            title: "Confirmación de cancelación de programación de pago",
            text: "¿Está seguro de cancelar la programación de pago? Al confirmar se limpiará la programación de pago registrada.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                modalPagoAbierto = false;

                listaPagoProgramacion = [];
                arrayFechaPago = [];
                arrayImportePago = [];
                arrayDias = [];
                arrayPorcentaje = [];
                arrayGlosa = [];
                arrayPagoProgramacionId = [];
                pagoProgramacionTotalImporte = 0;

//                onListarPagoProgramacion(listaPagoProgramacion);

                $('#aMostrarModalProgramacion').show();
                $('#tipoPagoDescripcion').html('Forma de pago: Crédito');
                $('#tipoPagoDescripcion').css({'color': '#cb2a2a'});
                $('#idFormaPagoContado').hide();
            } else {
                aceptarProgramacion(false);
            }
        });
    } else {
        $('#aMostrarModalProgramacion').show();
        $('#tipoPagoDescripcion').html('Forma de pago: Crédito');
        $('#tipoPagoDescripcion').css({'color': '#cb2a2a'});
        $('#idFormaPagoContado').hide();
    }

}

function aceptarProgramacion(muestraMensaje) {
    if (!isEmpty(listaPagoProgramacion)) {

        var programacionTexto = '';
        var totalPago = 0;
        listaPagoProgramacion.forEach(function (item) {
            //listaPagoProgramacion.push([ fechaPago, importePago, dias, porcentaje,glosa,pagoProgramacionId]);
            totalPago = totalPago + item[1] * 1;

            var sep = ' | ';
            if (programacionTexto == '') {
                sep = '';
            }

            programacionTexto += sep + item[0] + ': ' + item[1];
        });

        if (programacionTexto.length > 55) {
            programacionTexto = programacionTexto.substring(0, 52) + '...';
        }

        $('#modalProgramacionPagos').modal('hide');
        $('#aMostrarModalProgramacion').show();
        $('#tipoPagoDescripcion').html('(' + programacionTexto + ')');

        if (totalPago != calculoTotal) {
            if (muestraMensaje) {
                mensajeValidacion('Total de pago no coincide con el total del documento');
            }
            $('#tipoPagoDescripcion').css({'color': '#cb2a2a'});
        } else {
            $('#tipoPagoDescripcion').css({'color': '#1ca8dd'});
        }

        $('#idFormaPagoContado').hide();

    } else {
        mensajeValidacion('Registre programación de pago.');
    }
}

var modalPagoAbierto = false;
function mostrarModalProgramacionPago() {
    var dtdFechaVencimientoId = obtenerDocumentoTipoDatoIdXTipo(10);

    if (!isEmpty(dtdFechaVencimientoId)) {
        if (!modalPagoAbierto) {
            $('#fechaPago').datepicker({
                isRTL: false,
                format: 'dd/mm/yyyy',
                autoclose: true,
                language: 'es'
            }).on('changeDate', function (ev) {
                actualizarNumeroDias();
            });

//            var fechaVencimiento=$('#datepicker_' + dtdFechaVencimientoId).val();
            var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
            $('#fechaPago').datepicker('setDate', fechaEmision);

            $('#txtImportePago').val(devolverDosDecimales(calculoTotal));

            actualizarPorcentajePago();
            onChangeRdFechaPago();
            onChangeRdImportePago();

            modalPagoAbierto = true;
        }

        $('#modalProgramacionPagos').modal('show');

        setTimeout(function () {
            onListarPagoProgramacion(listaPagoProgramacion);
        }, 500);

        $('#labelTotalDocumento').html('(Total: ' + monedaSimbolo + ' ' + parseFloat(calculoTotal).formatMoney(2, '.', ',') + ')');

    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Falta configurar la fecha de vencimiento.");
        select2.asignarValor('cboTipoPago', 1);
    }
}

function obtenerDocumentoTipoDatoIdXTipo(tipo) {
    var dataConfig = dataCofiguracionInicial.documento_tipo_conf;

    var id = null;
    if (!isEmpty(dataConfig)) {
        $.each(dataConfig, function (index, item) {
            if (parseInt(item.tipo) === parseInt(tipo)) {
                id = item.id;
                return false;
            }
        });
    }

    return id;
}

function actualizarPorcentajePago() {
    var importePago = $('#txtImportePago').val();
    if (importePago > calculoTotal) {
        $('#txtImportePago').val(calculoTotal);
        mensajeValidacion('Importe de pago no puede ser mayor al total');
        calculoPorcentajePago();
        return;
    }

    if (importePago <= 0) {
        $('#txtImportePago').val(0);
        mensajeValidacion('El importe de pago debe ser positivo.');
        calculoPorcentajePago();
        return;
    }

    calculoPorcentajePago();
}

function calculoPorcentajePago() {
//    if (document.getElementById("rdImportePago").checked) {
    var importePago = $('#txtImportePago').val();
    var porcentaje = (importePago / calculoTotal) * 100;
    $('#txtPorcentaje').val(devolverDosDecimales(porcentaje));
//    }
}

function mensajeValidacion(mensaje) {
    $.Notification.autoHideNotify('warning', 'top right', 'Validación', mensaje);
}

function actualizarImportePago() {
    var porcentaje = $('#txtPorcentaje').val();
    if (porcentaje > 100) {
        $('#txtPorcentaje').val(100);
        mensajeValidacion('Porcentaje máximo 100.');
        calculoImportePago();
        return;
    }

    if (porcentaje <= 0) {
        $('#txtPorcentaje').val(0);
        mensajeValidacion('Porcentaje de pago debe ser positivo.');
        calculoImportePago();
        return;
    }

    calculoImportePago();

}

function calculoImportePago() {
    var porcentaje = $('#txtPorcentaje').val();
    var importePago = (calculoTotal * porcentaje) / 100;
    $('#txtImportePago').val(devolverDosDecimales(importePago));
}

function restarFechas(f1, f2) {
    var aFecha1 = f1.split('/');
    var aFecha2 = f2.split('/');
    var fFecha1 = Date.UTC(aFecha1[2], aFecha1[1] - 1, aFecha1[0]);
    var fFecha2 = Date.UTC(aFecha2[2], aFecha2[1] - 1, aFecha2[0]);
    var dif = fFecha2 - fFecha1;
    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
    return dias;
}

function actualizarNumeroDias() {
    var dtdFechaVencimientoId = obtenerDocumentoTipoDatoIdXTipo(10);
    var fechaVencimiento = $('#datepicker_' + dtdFechaVencimientoId).val();
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var fechaPago = $('#fechaPago').val();

    if (restarFechas(fechaEmision, fechaPago) < 0) {
        mensajeValidacion('La fecha de pago no puede ser menor a la fecha de emisión.');
        $('#fechaPago').datepicker('setDate', fechaEmision);
        return;
    }

    if (restarFechas(fechaPago, fechaVencimiento) < 0) {
//        mensajeValidacion('La fecha de pago no puede ser mayor a la fecha de vencimiento.');
//        $('#fechaPago').datepicker('setDate', fechaVencimiento);

        $('#modalProgramacionPagos').modal('hide');

        swal({
            title: "La fecha de pago es mayor a la fecha de vencimiento",
            text: "¿Desea actualizar la fecha de vencimiento a la fecha de pago: " + fechaPago + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si, actualizar!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#datepicker_' + dtdFechaVencimientoId).datepicker('setDate', fechaPago);
                calculoNumeroDias();
                $('#modalProgramacionPagos').modal('show');
            } else {
                $('#fechaPago').datepicker('setDate', fechaVencimiento);
                $('#modalProgramacionPagos').modal('show');
            }
        });

        return;
    }

    calculoNumeroDias();
}

function calculoNumeroDias() {
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var fechaPago = $('#fechaPago').val();

    $('#txtDias').val(restarFechas(fechaEmision, fechaPago));
}

function actualizarFechaPago() {
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var dias = $('#txtDias').val();

    if (!isEmpty(dias)) {
        $('#fechaPago').datepicker('setDate', sumaFecha(dias, fechaEmision));
    }

}

function sumaFecha(d, fecha) {
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() + 1) + "/" + Fecha.getFullYear());
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
    var aFecha = sFecha.split(sep);
    var fecha = aFecha[2] + '/' + aFecha[1] + '/' + aFecha[0];
    fecha = new Date(fecha);
    fecha.setDate(fecha.getDate() + parseInt(d));
    var anno = fecha.getFullYear();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    mes = (mes < 10) ? ("0" + mes) : mes;
    dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = dia + sep + mes + sep + anno;
    return (fechaFinal);
}

//GUARDAR EN VARIABLE ARRAY PROGRAMACION PAGO
var listaPagoProgramacion = [];

var arrayFechaPago = [];
var arrayImportePago = [];
var arrayDias = [];
var arrayPorcentaje = [];
var arrayGlosa = [];
var arrayPagoProgramacionId = [];

function agregarPagoProgramacion() {
    //alert("Hola");

//    var fechaPago = $('#cboFechaPago').val();

    var fechaPago = $('#fechaPago').val();
    var importePago = $('#txtImportePago').val();
    var dias = $('#txtDias').val();
    var porcentaje = $('#txtPorcentaje').val();
    var glosa = $('#txtGlosa').val();
    var idPagoProgramacion = $('#idPagoProgramacion').val();

    // ids tablas
    var pagoProgramacionId = null;
    //alert(idPagoProgramacion);

    if (validarFormularioPagoProgramacion(fechaPago, importePago, dias, porcentaje)) {
        if (validarPagoProgramacionRepetido(fechaPago, importePago, dias, porcentaje)) {

            if (idPagoProgramacion != '') {
                //alert('igual');

                arrayFechaPago[idPagoProgramacion] = fechaPago;
                arrayImportePago[idPagoProgramacion] = importePago;
                arrayDias[idPagoProgramacion] = dias;
                arrayPorcentaje[idPagoProgramacion] = porcentaje;
                arrayGlosa[idPagoProgramacion] = glosa;

                // ids de tablas relacionadas
                pagoProgramacionId = arrayPagoProgramacionId[idPagoProgramacion];

                listaPagoProgramacion[idPagoProgramacion] = [fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId];
            } else {
                //alert('diferente');

                arrayFechaPago.push(fechaPago);
                arrayImportePago.push(importePago);
                arrayDias.push(dias);
                arrayPorcentaje.push(porcentaje);
                arrayGlosa.push(glosa);

                listaPagoProgramacion.push([fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId]);
            }

            onListarPagoProgramacion(listaPagoProgramacion);
            limpiarCamposPagoProgramacion();

        }
    }
}

function validarFormularioPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var bandera = true;

    if (fechaPago === '' || fechaPago === null) {
        mensajeValidacion('Fecha de pago es obligatorio');
        bandera = false;
    }

    if (importePago === '' || importePago === null) {
        mensajeValidacion('Importe de pago es obligatorio');
        bandera = false;
    }

    if (importePago <= 0) {
        mensajeValidacion('Importe de pago debe ser positivo');
        bandera = false;
    }

    if (dias === '' || dias === null) {
        mensajeValidacion('Número de días es obligatorio');
        bandera = false;
    }

    if (porcentaje === '' || porcentaje === null) {
        mensajeValidacion('Porcentaje es obligatorio');
        bandera = false;
    }

    if (porcentaje <= 0) {
        mensajeValidacion('Porcentaje de pago debe ser positivo');
        bandera = false;
    }

    if (pagoProgramacionTotalImporte != 0) {
        var pagoRestante = calculoTotal - pagoProgramacionTotalImporte;

        if (pagoRestante - importePago < 0) {
            mensajeValidacion('Total de pago excedido. Importe de pago restante: ' + devolverDosDecimales(pagoRestante));
            bandera = false;
        }
    }

    return bandera;
}

function validarPagoProgramacionRepetido(fechaPago, importePago, dias, porcentaje) {
    var valido = true;

    var idPagoProgramacion = $('#idPagoProgramacion').val();

    if (idPagoProgramacion != '') {
        var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
        if (indice != idPagoProgramacion && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La fecha de pago ya ha sido agregado");
            valido = false;
        }
    } else {
        var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La fecha de pago ya ha sido agregado");
            valido = false;
        }
    }

    return valido;
}

var pagoProgramacionTotalImporte = 0;
function onListarPagoProgramacion(data) {
    breakFunction();
    var ind = 0;
    var totalImporte = 0;
    var totalPorcentaje = 0;
    var dataTb = [];

    data.forEach(function (item) {
        dataTb[ind] = [item[0], item[1], item[2], item[3], item[4], item[5]];

        totalImporte += item['1'] * 1;
        totalPorcentaje += item['3'] * 1;

        var eliminar = "<a href='#' onclick = 'eliminarPagoProgramacion(\""
                + item['0'] + "\", \"" + item['1'] + "\", \"" + item['2'] + "\", \"" + item['3'] + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

        var editar = "<a href='#' onclick = 'editarPagoProgramacion(\"" + ind + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

        dataTb[ind][6] = editar + eliminar;

        ind++;
    });

    $('#dataTablePagoProgramacion').DataTable({
        "scrollX": false,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": true,
        "order": [[1, 'asc']],
        "data": dataTb,
        "columns": [
            {"data": 0, "sClass": "alignCenter"},
            {"data": 2, "sClass": "alignRight"},
            {"data": 1, "sClass": "alignRight"},
            {"data": 3, "sClass": "alignRight"},
            {"data": 4},
            {"data": 6, "sClass": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [2, 3]
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(2).footer()).html(parseFloat(totalImporte).formatMoney(2, '.', ','));
            $(api.column(3).footer()).html(parseFloat(totalPorcentaje).formatMoney(2, '.', ','));
        }
    });

    pagoProgramacionTotalImporte = totalImporte;
}

function limpiarCamposPagoProgramacion() {
    $('#txtGlosa').val('');
    $('#idPagoProgramacion').val('');
}

function buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var tam = arrayFechaPago.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayFechaPago[i] === fechaPago /*&& arrayImportePago[i] === importePago && arrayDias[i] === dias && arrayPorcentaje[i] === porcentaje*/) {
            ind = i;
            break;
        }
    }
    return ind;
}

function editarPagoProgramacion(indice) {
    $('#fechaPago').datepicker('setDate', arrayFechaPago[indice]);
    $('#txtImportePago').val(arrayImportePago[indice]);
    $('#txtPorcentaje').val(arrayPorcentaje[indice]);
    $('#txtGlosa').val(arrayGlosa[indice]);

    pagoProgramacionTotalImporte = pagoProgramacionTotalImporte - arrayImportePago[indice] * 1;

    $('#idPagoProgramacion').val(indice);
}

var listaPagoProgramacionEliminado = [];

function eliminarPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
    if (indice > -1) {
        arrayFechaPago.splice(indice, 1);
        arrayImportePago.splice(indice, 1);
        arrayDias.splice(indice, 1);
        arrayPorcentaje.splice(indice, 1);
        arrayGlosa.splice(indice, 1);
    }


    listaPagoProgramacion = [];
    var tam = arrayFechaPago.length;
    for (var i = 0; i < tam; i++) {
        listaPagoProgramacion.push([arrayFechaPago[i], arrayImportePago[i], arrayDias[i], arrayPorcentaje[i], arrayGlosa[i], arrayPagoProgramacionId[i]]);
    }

    onListarPagoProgramacion(listaPagoProgramacion);
}

function onChangeRdFechaPago() {
    if (document.getElementById("rdFechaPago").checked) {
        $("#fechaPago").removeAttr("disabled");
        $("#txtDias").attr('disabled', 'disabled');
    }
}

function onChangeRdDias() {
    if (document.getElementById("rdDias").checked) {
        $("#txtDias").removeAttr("disabled");
        $("#fechaPago").attr('disabled', 'disabled');
    }
}

function onChangeRdImportePago() {
    if (document.getElementById("rdImportePago").checked) {
        $("#txtImportePago").removeAttr("disabled");
        $("#txtPorcentaje").attr('disabled', 'disabled');
    }
}

function onChangeRdPorcentaje() {
    if (document.getElementById("rdPorcentaje").checked) {
        $("#txtPorcentaje").removeAttr("disabled");
        $("#txtImportePago").attr('disabled', 'disabled');
    }
}

function cargarPagoProgramacion(data) {
    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);

    if (!isEmpty(data) && isEmpty(listaPagoProgramacion) && !isEmpty(dtdTipoPago)) {
        var fechaPago;
        var importePago;
        var dias;
        var porcentaje;
        var glosa;
        var pagoProgramacionId = null;
        $.each(data, function (index, item) {
            fechaPago = formatearFechaBDCadena(item.fecha_pago);
            importePago = devolverDosDecimales(item.importe);
            porcentaje = devolverDosDecimales(item.porcentaje);
            dias = item.dias;
            glosa = item.glosa;

            arrayFechaPago.push(fechaPago);
            arrayImportePago.push(importePago);
            arrayDias.push(dias);
            arrayPorcentaje.push(porcentaje);
            arrayGlosa.push(glosa);

            listaPagoProgramacion.push([fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId]);
        });

        select2.asignarValor('cboTipoPago', 2);
        aceptarProgramacion('false');
    }
}

function asignarImportePago() {

    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
    if (!isEmpty(dtdTipoPago) && !isEmpty(listaPagoProgramacion)) {

        var porcentaje;
        var importePago;
        $.each(listaPagoProgramacion, function (index, item) {
            porcentaje = item[3];
            importePago = (calculoTotal * porcentaje) / 100;
            listaPagoProgramacion[index][1] = importePago;
        });

        aceptarProgramacion(false);
    }

}

//seccion PAGOS
function abrirModalPagos(data) {
    debugger;
    habilitarBoton();
    var dataDP = data.dataDocumentoPago;

    if (select2.obtenerValor("cboMoneda") == 4) {
        $("#contenedorTipoCambioDiv").show();
    } else {
        $("#contenedorTipoCambioDiv").hide();
    }

    $("#contenedorEfectivo").show();

    $("#divCboActividadEfectivo").hide();
    $("#divCboCuentaEfectivo").hide();

    if (!isEmpty(dataDP.documento_tipo)) {
        $("#cboDocumentoTipoNuevoPagoConDocumento").select2({
            width: "100%"
        }).on("change", function (e) {
            $("#contenedorEfectivo").hide();
            loaderShow("#modalNuevoDocumentoPagoConDocumento");
            if (e.val == 282) {
                obtenerFormularioEfectivo();
            } else {
                obtenerDocumentoTipoDatoPago(e.val);
            }
        });
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumento", dataDP.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", 282);
//        $('#cboDocumentoTipoNuevoPagoConDocumento').append('<option value="0">Efectivo</option>');
//        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", 0);
        if (dataDP.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumento", true);
        }
        camposDinamicosPagoEfectivo = dataDP.documento_tipo_conf;
        onResponseObtenerDocumentoTipoDatoPago(dataDP.documento_tipo_conf);
        obtenerFormularioEfectivo();
    }

    //llenado de la actividad    
    select2.cargar('cboActividadEfectivo', data.actividad, 'id', ['codigo', 'descripcion']);
    select2.asignarValor('cboActividadEfectivo', data.actividad[0].actividad_defecto);
    $("#cboActividadEfectivo").prop("disabled", true);

    select2.cargar('cboCuentaEfectivo', data.cuenta, 'cuenta_id', 'descripcion_numero');
    select2.asignarValor('cboCuentaEfectivo', data.cuenta[0].cuenta_defecto);
    $("#cboActividadEfectivo").prop("disabled", true);
}

function obtenerFormularioEfectivo() {
    $("#formNuevoDocumentoPagoConDocumento").empty();
    $("#contenedorDocumentoTipoNuevo").css("height", 0);

    $("#contenedorEfectivo").show();
    loaderClose();
    $("#txtMontoAPagar").val($('#' + importes.totalId).val());
}

$("#tipoCambio").prop("disabled", true);
$("#checkBP").click(function () {
    var checked = $(this).is(":checked");
    if (!checked) {
        $("#tipoCambio").prop("disabled", true);
        return true;
    }
    obtenerTipoCambioDatepicker();
    $("#tipoCambio").prop("disabled", false);
    return true;
});

$('#txtPagaCon').keyup(function () {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
});
$('#txtMontoAPagar').keyup(function () {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
//    $('#txtVuelto').val(formatearNumero(vuelto));
});

function obtenerDocumentoTipoDatoPago(documentoTipoId) {
    ax.setAccion("obtenerDocumentoTipoDatoPago");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

var camposDinamicosPago = [];
var personaNuevoId;
var totalPago;
function onResponseObtenerDocumentoTipoDatoPago(data) {
 debugger;
    camposDinamicosPago = [];
    personaNuevoId = 0;
    $("#formNuevoDocumentoPagoConDocumento").empty();
    if (!isEmpty(data)) {

        let contadorOcultos = 0;
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            appendFormNuevo('<div class="row">');
            let hiddenImput = '';
            if (item.tipo == 5 || item.tipo == 21)
            {
                hiddenImput = 'hidden=""';
                contadorOcultos = contadorOcultos + 1;
            }
            var html = '<div class="form-group col-md-12" ' + hiddenImput + '>' +
                    '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
            if (item.tipo == 5)
            {
                html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar proveedor"></i></a>';
            }
            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            camposDinamicosPago.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion
            });
            switch (parseInt(item.tipo)) {
                case 1:
                case 14:
                    totalPago = 'txtnd_' + item.id;
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="' + $('#' + importes.totalId).val() + '" maxlength="45" style="text-align:right; "/>';
                    break;
                case 2:
                case 6:
                case 7:
                case 8:
                case 12:
                case 13:

                    var readonly = (parseInt(item.editable) === 0) ? 'readonly="true"' : '';
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }
                    html += '<input type="text" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" ' + readonly + '" class="form-control" value="' + value + '" maxlength="45"/>';
                    break;
                case 3:
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + item.data + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                    //FECHA DE EMISION DESHABILITADO Y FECHA DE EMISION DEL DOCUMENTO
                case 9:
                    var fechaEmision = item.data;
                    var dtdFechaEmision = obtenerDocumentoTipoDatoIdXTipo(9);
                    if (!isEmpty(dtdFechaEmision)) {
                        fechaEmision = $('#datepicker_' + dtdFechaEmision).val();
                    }
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + fechaEmision + '" ' + (item.edicion_habilitar == 1 ? '' : 'disabled') + '>' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2"></select>';
                    break;
                case 5:
                    html += '<div id ="div_proveedor" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la persona</option>';
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
                    personaNuevoId = item.id;
                    break;
                case 20:
                    html += '<div id ="div_cuenta" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                    });
                    html += '</select>';
                    break;
                case 21:
                    html += '<div id ="div_actividad" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la actividad</option>';
                    $.each(item.data, function (indexActividad, itemActividad) {
                        html += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
            }
            html += '</div></div>';
            appendFormNuevo(html);
            appendFormNuevo('</div>');
            switch (parseInt(item.tipo)) {
                case 4:
                    select2.cargar("cbond_" + item.id, item.data, "id", "descripcion");
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5:
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 20:
                case 21:
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.numero_defecto)) {
                        var id = parseInt(item.numero_defecto);
                        select2.asignarValor("cbond_" + item.id, id);
                    }

                    if (item.editable == 0) {
                        $("#cbond_" + item.id).attr('disabled', 'disabled');
                    }
                    break;
            }
        });
        var clienteId = select2.obtenerValor('cbo_' + obtenerDocumentoTipoDatoIdXTipo(5));
        if (personaNuevoId > 0 && clienteId > 0)
        {
            select2.asignarValor('cbond_' + personaNuevoId, clienteId);
        }
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
        var fecha_ = $('#datepicker_fechaPago').val();
        $("#contenedorDocumentoTipoNuevo").css("height", 75 * (data.length - contadorOcultos));
    }
    //habilitarBotonGeneral("btnNuevoC")
    $('#modalNuevoDocumentoPagoConDocumento').modal('show');
    loaderClose("#modalNuevoDocumentoPagoConDocumento");

}

function appendFormNuevo(html) {
    $("#formNuevoDocumentoPagoConDocumento").append(html);
}

function obtenerValoresCamposDinamicosPago() {
    var isOk = true;
    if (isEmpty(camposDinamicosPago))
        return false;
    $.each(camposDinamicosPago, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                camposDinamicosPago[index]["valor"] = document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
                //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                camposDinamicosPago[index]["valor"] = document.getElementById("datepickernd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
            case 20:// cuenta
            case 21:// actividad
                let valorObtenido;
                if (item.tipo == 5) { // Persona a pagar.
                    valorObtenido = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(5));
                } else {
                    valorObtenido = select2.obtenerValor('cbond_' + item.id);
                }
                camposDinamicosPago[index]["valor"] = valorObtenido;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"]) ||
                            camposDinamicosPago[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
        }
    });
    return isOk;
}

var camposDinamicosPagoEfectivo = [];
function obtenerValoresCamposDinamicosPagoEfectivo() {
    var isOk = true;
    if (isEmpty(camposDinamicosPagoEfectivo))
        return false;
    $.each(camposDinamicosPagoEfectivo, function (index, item) {
        let valorObtenido = null;
        //string
        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:


                if (item.tipo == 14) {
                    valorObtenido = document.getElementById("txtMontoAPagar").value;
                    totalPago = "txtMontoAPagar";
                } else if (item.tipo == 2 && item.codigo == 1) {
                    valorObtenido = document.getElementById("txtPagaCon").value;
                } else if (item.tipo == 2 && item.codigo == 2) {
                    valorObtenido = document.getElementById("txtVuelto").value;
                }

                camposDinamicosPagoEfectivo[index]["valor"] = valorObtenido; //document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
                //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                let fechaId = obtenerDocumentoTipoDatoIdXTipo(9);
                camposDinamicosPagoEfectivo[index]["valor"] = document.getElementById("datepicker_" + fechaId).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
            case 20:// cuenta
            case 21:// actividad  
                if (item.tipo == 5) { // Persona a pagar.
                    let cboPersonaId = obtenerDocumentoTipoDatoIdXTipo(5);
                    valorObtenido = select2.obtenerValor("cbo_" + cboPersonaId);
                } else if (item.tipo == 21) { //Actividad
                    valorObtenido = select2.obtenerValor("cboActividadEfectivo");
                } else if (item.tipo == 20) { //Cuenta destino
                    valorObtenido = select2.obtenerValor("cboCuentaEfectivo");
                }

                camposDinamicosPagoEfectivo[index]["valor"] = valorObtenido;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"]) ||
                            camposDinamicosPagoEfectivo[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
        }
    });
    return isOk;
}


function validarSumaCantidades()
{
    var state = true;
    var a = $("#tbodyProductosDetalles tr");
    $.each(a, function (index, value) {
        var TDS = $(this).find('td');
        var maxQty = $(this).find("td:nth-child(2)").text();
        var currentQty = 0;
        $.each(TDS, function (indice, valor) {
            //var cajitas =  $("input[type='number']");
            var cajitas = $(this).find("input[type='number']");
            $.each(cajitas, function (i, o) {
                currentQty += parseInt($(this).val());
            });

        });

        if (currentQty > parseInt(maxQty)) {
            state = false;
        }
    });
    return state;
}
//guardar el documento y ATENCION DE SOLICITUDES
function guardarDocumentoAtencionSolicitud()
{
    if (validarSumaCantidades()) {
        //parte documento operacion
        loaderShow("#modalAsignarAtencion");

        var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
        if (isEmpty(documentoTipoId)) {
            mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
            return;
        }

        obtenerCheckDocumentoACopiar();

        var checkIgv = 0;

        if (!isEmpty(importes.subTotalId)) {
            if (document.getElementById('chkIncluyeIGV').checked) {
                checkIgv = 1;
            }
        } else {
            checkIgv = opcionIGV;
        }

        var tipoPago = null;

        var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
        if (!isEmpty(dtdTipoPago)) {
            tipoPago = select2.obtenerValor("cboTipoPago");
        }

        if (tipoPago != 2) {
            listaPagoProgramacion = [];
        }
        //fin documento operacion

        ax.setAccion("guardarDocumentoAtencionSolicitud");
        //documento operacion
        ax.addParamTmp("documentoTipoId", documentoTipoId);
        ax.addParamTmp("camposDinamicos", camposDinamicos);
        // ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("detalle", detalleDos);
        ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
        ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
        ax.addParamTmp("comentario", $('#txtComentario').val());
        ax.addParamTmp("checkIgv", checkIgv);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("accionEnvio", boton.accion);
        ax.addParamTmp("tipoPago", tipoPago);
        ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
        //------------------------
        //documento atencion solicitud
        ax.addParamTmp("atencionSolicitudes", nArray);
        ax.consumir();
    } else {
        mostrarAdvertencia("Las cantidades ingresadas superan a las cantidades disponibles en la orden.");
    }

}

function guardarDocumentoPago() {
    
    enviar('confirmar', true);
}


function obtenerArraySinReferencia(data) {
    let array = [];
    $.each(data, function (index, item) {
        array.push(Object.assign({}, item));
    });
    return array;
}

var camposDinamicosPagoTemporal = [];
function guardarDocumentoPagoTemporal() {
//    debugger;
    let totalCamposDinamicos = 0;
    $.each(camposDinamicosPagoTemporal, function (index, item) {
        totalCamposDinamicos = totalCamposDinamicos + ((item.data.filter(itemFilter => itemFilter.tipo == 14)[0]['valor']) * 1);
    });

    let montoTotalDocumento = $('#' + importes.totalId).val();


    let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
    let documentoTipoIdPagoDescripcion = select2.obtenerText("cboDocumentoTipoNuevoPagoConDocumento");
    //Validar y obtener valores de los campos dinamicos
    if (documentoTipoIdPago != 282) {
        if (!obtenerValoresCamposDinamicosPago()) {
            return;
        }

        let montoTotal = camposDinamicosPago.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        if ((totalCamposDinamicos * 1) + (montoTotal * 1) > montoTotalDocumento) {
            mostrarValidacionLoaderClose("El monto máximo a pagar es " + formatearNumero(montoTotalDocumento));
            return;
        }
        camposDinamicosPagoTemporal.push({documento_tipo_id: documentoTipoIdPago,
            documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
            monto: montoTotal,
            data: obtenerArraySinReferencia(camposDinamicosPago)});
    } else {
        if (!obtenerValoresCamposDinamicosPagoEfectivo()) {
            return;
        }
        let montoTotal = camposDinamicosPagoEfectivo.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        if ((totalCamposDinamicos * 1) + (montoTotal * 1) > montoTotalDocumento) {
            mostrarValidacionLoaderClose("El monto máximo a pagar es " + formatearNumero(montoTotalDocumento));
            return;
        }
        camposDinamicosPagoTemporal.push({documento_tipo_id: documentoTipoIdPago,
            documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
            monto: montoTotal,
            data: obtenerArraySinReferencia(camposDinamicosPagoEfectivo)});
    }

    cargarDetallePago();

    $("#contenedorEfectivo").hide();
    loaderShow("#modalNuevoDocumentoPagoConDocumento");
    if (documentoTipoIdPago == 282) {
        setTimeout(function () {
            obtenerFormularioEfectivo();
        }, 1000);
    } else {
        obtenerDocumentoTipoDatoPago(documentoTipoIdPago);
    }
}

function cargarDetallePago() {
    $("#tablaDocumentoPagoAcumulado").empty();
    let montoTotal = 0;
    $.each(camposDinamicosPagoTemporal, function (index, item) {
        let itemTotal = item.data.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        montoTotal = (montoTotal * 1) + (itemTotal * 1);
        $('#tablaDocumentoPagoAcumulado').append("<a id='cerrarLink_" + index + "' onclick='eliminarDocumentoPagoTemporal(" + index + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp");
        $('#tablaDocumentoPagoAcumulado').append("<a id='link_" + index + "' onclick='' style='color:#0000FF;text-align: right;'>[" + item.documento_tipo_descripcion + "&nbsp&nbsp&nbsp" + formatearNumero(itemTotal) + "]</a><br>");
        $("#tablaDocumentoPagoAcumulado").css("height", $("#linkDocumentoACopiar").height() + 20);
    });
}

function reenumerarFilasDetalle(indice, numItemActual) {
    var numItem = numItemActual;
    for (var i = (indice + 1); i < nroFilasReducida; i++) {
        if ($('#txtNumItem_' + i).length > 0) {
            $('#txtNumItem_' + i).html(numItem);
            numItem++;
        }
    }

    numeroItemFinal--;
}

function dibujarAtencionLeftSide()
{
    $("#theadProductosDetalles").append("<th>Producto</th><th>Cantidad</th>");
    var html = '';
    for (var i = 0; i < detalle.length; i++)
    {
        html += '<tr>';
        html += '<td>' + detalle[i].bienDesc + '</td>';
        html += '<td style="text-align: right;">' + (detalle[i].cantidad / 1) + '</td>';
        html += '<td style="display:none;">' + detalle[i].bienId + '</td>';
        html += '</tr>';
    }
    $('#tbodyProductosDetalles').append(html);
}


// God helps your poor soul understand the code you are about to see :D
var count = 0;
var headersNoRepetir = new Set();
var htmlUniqueHeaders = new Set();
var onClickHeaderAtencion;

var dataMapaHeaders = new Map();
var mapaHeaders = new Map();

var dataMapaEstadoHeaders = new Map();
var mapaEstadoHeaders = new Map();

var dataMapaCantidadesValidacion, mapaCantidadesValidacion = new Map();
var dataMapaCantidadesAsignacion, mapaCantidadesAsignacion = new Map();
var showModal = true;
var arrayBienIdCorrectos = [];
var globalAS;
var sub = 0;

function cancelarModalAsignacion()
{
    detalle = detalleDos;
}



var arrayCantidades = new Array();
var mapaBienId = new Map();
var nArray;


function asignar()
{

    mapaBienId.clear();
    arrayCantidades = [];

    var allInputIds = $('#tbodyProductosDetalles input').map(function (index, dom) {
        return dom.id
    });
    for (var i = 0; i < allInputIds.length; i++) {
        var splat = (allInputIds[i].replace('txt', "")).split("_");
        var idSolic = splat[0];
        var result = globalAS.filter(function (obj) {
            return obj.documentoId == idSolic;
        });
        if (!isEmpty(result))
        {
            var search = "#" + idSolic + "_" + splat[1] + " input";
            var inputGroup = $(search);
            for (var o = 0; o < result[0].detalleBien.length; o++)
            {

                if (parseInt(inputGroup.last().attr('value')) == parseInt(result[0].detalleBien[o].bien_id))
                {

                    if (mapaBienId.has(parseInt(result[0].detalleBien[o].bien_id)))
                    {
                        var localArray = mapaBienId.get(parseInt(result[0].detalleBien[o].bien_id));
                        localArray.push({
                            'cantidad': inputGroup.first().val(),
                            'mov_bien_ant_id': result[0].detalleBien[o].movimiento_bien_id
                        });
                        mapaBienId.set(parseInt(result[0].detalleBien[o].bien_id), localArray);
                    } else
                    {
                        arrayCantidades.push({
                            'cantidad': inputGroup.first().val(),
                            'mov_bien_ant_id': result[0].detalleBien[o].movimiento_bien_id
                        });
                        mapaBienId.set(parseInt(result[0].detalleBien[o].bien_id), arrayCantidades);
                        arrayCantidades = [];
                    }

                }
            }
        } else
        {
            //do nothing xdxd
        }
    }
    nArray = Array.from(mapaBienId);

    guardarDocumentoAtencionSolicitud();
}

//OPCION PARA REFRESCAR PRODUCTO
function cargarBien() {
    var rutaAbsoluta = URL_BASE + 'index.php?token=2';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function actualizarComboProducto(indice) {
    loaderShow();
    ax.setAccion("obtenerProductos");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.setTag(indice);
    ax.consumir();
}

function onResponseObtenerProductos(data, indice) {
    cargarBienDetalleCombo(data, indice);
}

function iniciarArchivoAdjunto() {
    $("#archivoAdjunto").change(function () {
        $("#nombreArchivo").html($('#archivoAdjunto').val().slice(12));

        //llenado del popover
        $('#idPopover').attr("data-content", $('#archivoAdjunto').val().slice(12));
        $('[data-toggle="popover"]').popover('show');
        $('.popover-content').css('color', 'black');
        $('[class="popover fade top in"]').css('z-index', '0');

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
}

var lstDocumentoArchivos = [];
var lstDocEliminado = [];
var cont = 0;
var ordenEdicion = 0;
function eliminarDocumento(docId) {
    ordenEdicion = 0;
    lstDocumentoArchivos.some(function (item) {
        if (item.id == docId) {
            lstDocumentoArchivos.splice(ordenEdicion, 1);
            lstDocEliminado.push([{id: docId, archivo: item.archivo}])
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
            return item.id === docId;
        }
        ordenEdicion++;
    });
}

$("#btnAgregarDoc").click(function fileIsLoaded(e) {
    var documento = {};
    if (!isEmpty($("#archivoAdjuntoMulti").val())) {
        if ($("#archivoAdjuntoMulti").val().slice(12).length > 0) {
            documento.data = $("#dataArchivoMulti").val();
            documento.archivo = $("#archivoAdjuntoMulti").val().slice(12);
            documento.id = "t" + cont++;
            lstDocumentoArchivos.push(documento);
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
            $("#archivoAdjuntoMulti").val("");
            $("#dataArchivoMulti").val("");
            $('[data-toggle="popover"]').popover('hide');
            $('#idPopoverMulti').attr("data-content", "");
            $("#msjDocumento").html("");
            $("#msjDocumento").hide();
        } else {
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
        }

    } else {
        $("#msjDocumento").html("Debe adjuntar un archivo primero para agregarlo a la lista");
        $("#msjDocumento").show();
    }

});

function onResponseListarArchivosDocumento(data) {

    $("#dataList2").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatable3' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle; width:20%'>#</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Nombre</th>"
            + "<th style='text-align:center; vertical-align: middle; width:15%'>Acciones</th>"
            + "</tr>"
            + "</thead>";
    if (!isEmpty(data)) {

        $.each(data, function (index, item) {
            if (!item.id.match(/t/g)) {
                lstDocumentoArchivos[index]["data"] = "util/uploads/documentoAdjunto/" + item.nombre;
            }

            cuerpo = "<tr>"
                    + "<td style='text-align:center;'>" + (index + 1) + "</td>"
                    + "<td style='text-align:center;'>" + item.archivo + "</td>";

            cuerpo += "<td style='text-align:center;'>"
                    + "<a href='" + item.data + "' download='" + item.archivo + "' target='_blank'><i class='fa fa-cloud-download' style='color:#1ca8dd;'></i></a>&nbsp;\n"
                    + "<a href='#' onclick='eliminarDocumento(\"" + item.id + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n"
                    + "</td>"
                    + "</tr>";
            cuerpo_total += cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList2").append(html);
    $("#datatable3").DataTable();
}

function imageIsLoaded(e) {
    $('#dataArchivo').attr('value', e.target.result);
}

function adjuntar() {
    onResponseListarArchivosDocumento(lstDocumentoArchivos);
    $('#tituloVisualizarModalArchivos').html("Adjuntar archivos");
    $('#modalVisualizarArcvhivos').modal('show');
}

function iniciarArchivoAdjuntoMultiple() {
    $("#archivoAdjuntoMulti").change(function () {
        $("#nombreArchivoMulti").html($('#archivoAdjuntoMulti').val().slice(12));

        //llenado del popover
        $('#idPopoverMulti').attr("data-content", $('#archivoAdjuntoMulti').val().slice(12));
        $('[data-toggle="popover"]').popover('show');
        $('.popover-content').css('color', 'black');
        $('[class="popover fade top in"]').css('z-index', '0');

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoadedMulti;
            reader.readAsDataURL(this.files[0]);
        }
    });
}

function imageIsLoadedMulti(e) {
    $('#dataArchivoMulti').attr('value', e.target.result);
}

// Anticipos
function mostrarAnticipos(data) {
    var anticipos = data.anticipos;
    var actividades = data.actividades;

    bandera.validacionAnticipos = 1;
    mostrarModalAnticipo();
    $("#dtAnticipos").dataTable({
        order: [[1, "desc"]],
        "ordering": false,
        "data": anticipos,
        "columns": [
            {"data": "documento_id", sClass: "columnAlignCenter"},
            {"data": "serie"},
            {"data": "fecha_emision"},
            {"data": "descripcion"},
            {"data": "pendiente", "sClass": "alignRight"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return '<label class="cr-styled"><input type="checkbox" id="chkAnticipo_' + data + '"><i class="fa"></i></label>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return formatearFechaBDCadena(data);
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    return row.simbolo + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 4
            }
        ],
        "dom": '<"top">rt<"bottom"><"clear">',
        "destroy": true
    });
    dataTemporal.anticipos = anticipos;
//    select2.iniciarElemento("cboAnticipoActividad");
//    select2.cargar("cboAnticipoActividad", actividades, "id", "descripcion");
}

function limpiarAnticipos() {
    dataTemporal.anticipos = null;
    bandera.validacionAnticipos = 2;
    enviar(boton.accion);
}
function aplicarAnticipos() {
    // Validamos 
    // Si se ha seleccionado algun anticipo, debe de haberse seleccionado una cuenta
    var validaActividad = false;
    $.each(dataTemporal.anticipos, function (indexAnticipo, itemAnticipo) {
        if (document.getElementById('chkAnticipo_' + itemAnticipo.documento_id).checked) {
            validaActividad = true;
            return false;
        }
    });
    if (!validaActividad) {
        mostrarAdvertencia("No ha seleccionado ningún anticipo");
        return;
    }
    bandera.validacionAnticipos = 3;
    // Enviamos a guardar
    enviar(boton.accion);
}
function deshabilitaBotonesAnticipos() {
    $('#btnLimpiaAnticipos').prop('disabled', true);
    $('#btnAplicaAnticipos').prop('disabled', true);
}
function obtenerAnticiposAAplicar() {
    if (!isEmpty(dataTemporal.anticipos)) {
        var anticiposAplicar = [];
        $.each(dataTemporal.anticipos, function (indexAnticipo, itemAnticipo) {
            if (document.getElementById('chkAnticipo_' + itemAnticipo.documento_id).checked) {
                anticiposAplicar.push({documentoId: itemAnticipo.documento_id, pendiente: itemAnticipo.pendiente});
            }
        });
        return anticiposAplicar;
    } else {
        return null;
    }
}
function cerrarModalAnticipo() {
    if (bandera.validacionAnticipos == 1) {
        $('#modalAnticipos').modal('hide');
        $('.modal-backdrop').hide();
    }
}
function mostrarModalAnticipo() {
    $('#modalAnticipos').modal({backdrop: 'static', keyboard: false});
    $('#modalAnticipos').modal('show');
}

function cambiarPeriodo() {
    var periodoId = obtenerPeriodoIdXFechaEmision();
    select2.asignarValorQuitarBuscador('cboPeriodo', periodoId);
}

function obtenerPeriodoIdXFechaEmision() {
    var periodoId = null;
    var dtdFechaEmision = obtenerDocumentoTipoDatoIdXTipo(9);
    if (!isEmpty(dtdFechaEmision)) {
        var fechaEmision = $('#datepicker_' + dtdFechaEmision).val();

        var fechaArray = fechaEmision.split('/');
        var d = parseInt(fechaArray[0], 10);
        var m = parseInt(fechaArray[1], 10);
        var y = parseInt(fechaArray[2], 10);

        $.each(dataCofiguracionInicial.periodo, function (index, item) {
            if (item.anio == y && item.mes == m) {
                periodoId = item.id;
            }
        });
    }
//    console.log(fechaArray,periodoId);
    return periodoId;
}

function obtenerDocumentoTipoDatoXTipoXCodigo(tipo, codigo) {
    var dataConfig = dataCofiguracionInicial.documento_tipo_conf;

    var dtd = null;
    if (!isEmpty(dataConfig)) {
        $.each(dataConfig, function (index, item) {
            if (parseInt(item.tipo) === parseInt(tipo) && item.codigo == codigo) {
                dtd = item;
                return false;
            }
        });
    }

    return dtd;
}

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function onChangeOrganizadorDestino() {
    if (!isEmpty(obtenerDocumentoTipoDatoIdXTipo(17))) {
        obtenerDireccionOrganizador(2);
    }
}
/************************************ DISTRIBUCION CONTABLE *************************************/

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    if ((target == '#distribucion')) {
        var monto = obtenerSubTotal_Total_Distribucion();
        if (!isEmpty(importes.subTotalId) && monto * 1 < 0) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero debe ingresar el sub total para iniciar con la distribución contable.');
        } else if (monto * 1 < 0) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero debe ingresar el monto total para iniciar con la distribución contable.');
        } else if (isEmpty(select2.obtenerValor('cboOperacionTipo'))) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero seleccione el tipo de operación.');
        }
    }
});

var nroFilasDistribucion = 0;

function llenarCabeceraDistribucion() {
    $('#headDetalleCabeceraDistribucion').empty();
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];

//    var operacionTipo = dataContOperacionTipo[document.getElementById('cboOperacionTipo').options.selectedIndex];
    var fila = ' <tr role="row">';
    fila += '<th style="text-align: center; width: 5%;" class="sorting_disabled" rowspan="1" colspan="1">#</th>';
    fila += '<th style="text-align: center; width: 30%;" class="sorting_disabled" rowspan="1" colspan="1">Cuenta Contable</th>';
    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        fila += '<th style="text-align: center; width: 30%;" class="sorting_disabled" rowspan="1" colspan="1">Centro Costo</th>';
    }
    fila += '<th style="text-align:center; width: 15%;" class="sorting_disabled" rowspan="1" colspan="1">Procentaje(%)</th>';
    fila += '<th style="text-align:center; width: 15%;" class="sorting_disabled" rowspan="1" colspan="1">Monto</th>';
    fila += '<th style="text-align: center; width: 5%;" class="sorting_disabled" rowspan="1" colspan="1">Acciones</th>';
    $('#headDetalleCabeceraDistribucion').append(fila);
}

function agregarFilaDistribucion(opcion) {

    if (obtenerAcumuladosPorcentajes_Montos(2) >= obtenerSubTotal_Total_Distribucion() && opcion != 1) {
        if (!isEmpty(importes.subTotalId))
            mostrarAdvertencia('El monto no puede exceder al sub total.');
        else
            mostrarAdvertencia('El monto no puede exceder al total.');
        return;
    }
    var operacionTipoId = select2.obtenerValor('cboOperacionTipo');
    if (isEmpty(operacionTipoId)) {
        mostrarAdvertencia('Primero seleccione el tipo de operación.');
        return;
    }

    var operacionTipo = dataContOperacionTipo.filter(item => item.id == select2.obtenerValor('cboOperacionTipo'))[0];
    var indice = nroFilasDistribucion;

    let addOnchageCboCuenta = (operacionTipo.requiere_centro_costo == 2 ? " onchange = 'onChangeCuentaContable(" + indice + ",this.value);' " : "");

    var fila = "<tr id=\"trDetalleDistribucion_" + indice + "\">";
    fila += "<td style='border:0; vertical-align: middle; padding-right: 10px;' id='txtNumItemDistribucion_" + indice + "' name='txtNumItemDistribucion_" + indice + "' align='center'></td>";
    fila += "<td style='border:0; vertical-align: middle;'>" +
            "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<select name='cboCuentaContable_" + indice + "' id='cboCuentaContable_" + indice + "' class='select2' " + addOnchageCboCuenta + " >" +
            "</select></div></td>";

    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        fila += "<td style='border:0; vertical-align: middle;'>" +
                "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
                "<select name='cboCentroCosto_" + indice + "' id='cboCentroCosto_" + indice + "' class='select2'>" +
                "</select></div></td>";
    }

    fila += "<td style='border:0; vertical-align: middle;'><div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12' >" +
            "<input type='number'   id='txtPorcentajeDistribucion_" + indice + "' name='txtPorcentajeDistribucion_" + indice + "' class='form-control' required='' aria-required='true' style='text-align: right;' " +
            "onkeyup='if (this.value.length > 13) {this.value = this.value.substring(0, 13)}; actualizarMontoDistribucion(" + indice + ");'  /><span class='input-group-addon'>%</span></div></td>";

    fila += "<td style='border:0; vertical-align: middle;'><div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<input type='number' id='txtMontoDistribucion_" + indice + "' name='txtMontoDistribucion_" + indice + "' class='form-control' required='' aria-required='true' value='' style='text-align: right;' " +
            "onkeyup='if (this.value.length > 13) {this.value = this.value.substring(0, 13)}; calculoPorcentajePagoDistribucion(" + indice + ");'  /></div></td>";

    fila += "<td style='border:0; align='center' vertical-align: middle;'>&nbsp;<a onclick='confirmarEliminarDistribucion(" + indice + ");'>" +
            "<i class='fa fa-trash-o' style='color:#cb2a2a;' title='Eliminar'></i></a></td>";


    $('#datatableDistribucion tbody').append(fila);
    nroFilasDistribucion++;
    reenumerarFilasDetalleDistribucion();
    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        if (!isEmpty(dataCofiguracionInicial.centroCosto)) {
            $.each(dataCofiguracionInicial.centroCosto, function (indexPadre, centroCostoPadre) {
                if (isEmpty(centroCostoPadre.centro_costo_padre_id)) {
                    var html = '<optgroup id="' + centroCostoPadre.id + '" label="' + centroCostoPadre['codigo'] + ' | ' + centroCostoPadre['descripcion'] + '">';
                    // centroCostoPadre['codigo'] + " | " + centroCostoPadre['descripcion']
                    var dataHijos = dataCofiguracionInicial.centroCosto.filter(centroCosto => centroCosto.centro_costo_padre_id == centroCostoPadre.id);
                    $.each(dataHijos, function (indexHijo, centroCostoHijo) {
                        html += '<option value="' + centroCostoHijo['id'] + '">' + centroCostoHijo['codigo'] + " | " + centroCostoHijo['descripcion'] + '</option>';
                    });
                    html += ' </optgroup>';
                    $('#cboCentroCosto_' + indice).append(html);
                }
            });

            $("#cboCentroCosto_" + indice).select2({
                width: "100%"
            });
            select2.asignarValor("cboCentroCosto_" + indice, "");
        }
    }
    var array_cuentas_relaciondas = [];

    if (!isEmpty(dataCofiguracionInicial.cuentaContable)) {
        if (isEmpty(operacionTipo.cuentas_relacionadas)) {
            array_cuentas_relaciondas = dataCofiguracionInicial.cuentaContable;
        } else {
            var cuentas_relacionadas = operacionTipo.cuentas_relacionadas;
            cuentas_relacionadas = cuentas_relacionadas.split(',');
            if (!isEmpty(cuentas_relacionadas)) {
                $.each(dataCofiguracionInicial.cuentaContable, function (indexPadre, cuenta) {
                    $.each(cuentas_relacionadas, function (indexPadre, item) {
                        var busquedad = new RegExp('^' + item + '.*$');
                        if (!isEmpty(cuenta.codigo) && cuenta.codigo.match(busquedad)) {
                            array_cuentas_relaciondas.push(cuenta);
                        }
                    });
                });
            }
        }
    }

    $.each(array_cuentas_relaciondas, function (indexPadre, cuentaContablePadre) {
        var html = llenarCuentasContable(cuentaContablePadre, '', 'cboCuentaContable_' + indice);
        $('#cboCuentaContable_' + indice).append(html);
    });

    select2.asignarValor("cboCuentaContable_" + indice, "");
}


function onChangeCuentaContable(indice, valor) {
    let cuenta = dataCofiguracionInicial.cuentaContable.filter(item => item.id == valor);
    if (!isEmpty(valor) && cuenta[0]['codigo'].substr(0, 1) == '6' && cuenta[0]['codigo'].substr(0, 2) != '60' && cuenta[0]['codigo'].substr(0, 2) != '61' && cuenta[0]['codigo'].substr(0, 2) != '69') {
        $("#cboCentroCosto_" + indice).prop('disabled', false);
    } else {
        select2.asignarValor("cboCentroCosto_" + indice, "");
        $("#cboCentroCosto_" + indice).prop('disabled', true);
    }
}

function llenarCuentasContable(item, extra, cbo_id) {
    var cuerpo = '';
    if ($("#" + cbo_id + " option[value='" + item['id'] + "']").length != 0) {
        return cuerpo;
    }
    if (item.hijos * 1 == 0) {
        cuerpo = '<option value="' + item['id'] + '">' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
        return cuerpo;
    }
    cuerpo = '<option value="' + item['id'] + '" disabled>' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
    var dataHijos = dataCofiguracionInicial.cuentaContable.filter(cuentaContable => cuentaContable.plan_contable_padre_id == item.id);
//    cuerpo = '<optgroup label="' + extra + item['codigo'] + ' | ' + item['descripcion'] + '">';
    $.each(dataHijos, function (indexHijo, cuentaContableHijo) {
//            cuerpo += '<option value="' + cuentaContableHijo['id'] + '">' + cuentaContableHijo['codigo'] + " | " + cuentaContableHijo['descripcion'] + '</option>';
        cuerpo += llenarCuentasContable(cuentaContableHijo, extra + '&nbsp;&nbsp;&nbsp;&nbsp;', cbo_id);
    });
//    cuerpo += ' </optgroup>';
    return cuerpo;
}

function confirmarEliminarDistribucion(index) {
    swal({
        title: "¿Está seguro que desea eliminar?",
        text: "Una vez eliminado tendrá que seleccionar nuevamente todo el registro si desea volver agregarlo",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarDistribucion(index);
        }
    });
}

function eliminarDistribucion(indice) {
    $('#trDetalleDistribucion_' + indice).remove();
    reenumerarFilasDetalleDistribucion();
}

function calculoPorcentajePagoDistribucion(indice) {
    var importeAcumulado = obtenerAcumuladosPorcentajes_Montos(2);
    var importePago = $('#txtMontoDistribucion_' + indice).val() * 1;
    if (importeAcumulado > obtenerSubTotal_Total_Distribucion()) {
        var nuevo_importe = redondearNumerDecimales(obtenerSubTotal_Total_Distribucion() - importeAcumulado + importePago, 6);
        var porcentaje = redondearNumerDecimales((nuevo_importe / obtenerSubTotal_Total_Distribucion()) * 100, 6);
        $('#txtMontoDistribucion_' + indice).val(redondearNumerDecimales(nuevo_importe, 2));
        $('#txtPorcentajeDistribucion_' + indice).val(redondearNumerDecimales(porcentaje, 4));

        if (!isEmpty(importes.subTotalId))
            mensajeValidacion('El monto no puede exceder al sub total.');
        else
            mensajeValidacion('El monto no puede exceder al total.');
        return;
    }

    if (importePago <= 0) {
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(0));
        $('#txtPorcentajeDistribucion_' + indice).val(devolverDosDecimales(0));
        mensajeValidacion('El monto debe ser positivo.');
        return;
    }

    var porcentaje = (importePago / obtenerSubTotal_Total_Distribucion()) * 100;
    $('#txtPorcentajeDistribucion_' + indice).val(devolverDosDecimales(porcentaje));
}

function redondearNumerDecimales(monto, decimales) {
    if (isEmpty(decimales)) {
        decimales = 2;
    }
    return Math.round(monto * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function actualizarMontoDistribucion(indice) {

    var porcentajeAcumulado = obtenerAcumuladosPorcentajes_Montos(1);
    var porcentaje = $('#txtPorcentajeDistribucion_' + indice).val() * 1;

    if (porcentajeAcumulado > 100) {
        var nuevo_porcentaje = redondearNumerDecimales(100 - porcentajeAcumulado, 6);
        $('#txtPorcentajeDistribucion_' + indice).val(redondearNumerDecimales(porcentaje, 4));
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(obtenerSubTotal_Total_Distribucion() * nuevo_porcentaje / 100));
        mensajeValidacion('Porcentaje máximo 100.');
        return;
    }

    if (porcentaje < 0) {
        $('#txtPorcentajeDistribucion_' + indice).val(0);
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(0));
        mensajeValidacion('Porcentaje de pago debe ser positivo.');
        return;
    }

    var monto = (obtenerSubTotal_Total_Distribucion() * porcentaje) / 100;
    $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(monto));
}

function obtenerAcumuladosPorcentajes_Montos(tipo) {
    var montoAcumulado = 0;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if (tipo == 1 && $('#txtPorcentajeDistribucion_' + i).length != 0) {
            montoAcumulado = redondearNumerDecimales($('#txtPorcentajeDistribucion_' + i).val() * 1 + montoAcumulado, 6);
        } else if (tipo == 2 && $('#txtMontoDistribucion_' + i).length != 0) {
            montoAcumulado = redondearNumerDecimales($('#txtMontoDistribucion_' + i).val() * 1 + montoAcumulado, 6);
//            montoAcumulado += $('#txtMontoDistribucion_' + i).val() * 1;
        }
    }
    return isEmpty(montoAcumulado) ? 0 : montoAcumulado;
}

function obtenerSubTotal_Total_Distribucion() {
    var monto = 0;
    var checkIGV = false;

    if ($("#chkIncluyeIGV").length > 0 && $('#chkIncluyeIGV').is(':checked')) {
        checkIGV = true;
    }
    if (importes.subTotalId == importes.calculoId || (checkIGV && !isEmpty(importes.subTotalId))) {

        if ($('#' + importes.subTotalId).length != 0 && !isEmpty($('#' + importes.subTotalId).val())) {
            monto += $('#' + importes.subTotalId).val() * 1;
        }

        if ($('#' + importes.otrosId).length != 0 && !isEmpty($('#' + importes.otrosId).val())) {
            monto += $('#' + importes.otrosId).val() * 1;
        }

        if ($('#' + importes.exoneracionId).length != 0 && !isEmpty($('#' + importes.exoneracionId).val())) {
            monto += $('#' + importes.exoneracionId).val() * 1;
        }

        if ($('#' + importes.igvId).length != 0 && !isEmpty($('#' + importes.igvId).val()) && select2.obtenerValor("cboOperacionTipo") == "30") {
            monto += $('#' + importes.igvId).val() * 1;
        }

    } else if (importes.totalId == importes.calculoId && $('#' + importes.totalId).length != 0 && !isEmpty($('#' + importes.totalId).val())) {
        monto = $('#' + importes.totalId).val() * 1;
    }
    return monto;
}

function reenumerarFilasDetalleDistribucion() {
    var numItem = 1;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            $('#txtNumItemDistribucion_' + i).html(numItem);
            numItem++;
        }
    }
}


var dataDistribucion = [];
function obtenerDistribucion() {
    dataDistribucion = [];
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            var item = {};
            item.linea = $('#txtNumItemDistribucion_' + i).html();

            if (!isEmpty(select2.obtenerValor("cboCuentaContable_" + i))) {
                item.plan_contable_id = select2.obtenerValor("cboCuentaContable_" + i);
            }

            if (!isEmpty(select2.obtenerValor("cboCentroCosto_" + i)) && (operacionTipo.requiere_centro_costo == '1' || operacionTipo.requiere_centro_costo == '2')) {
                item.centro_costo_id = select2.obtenerValor("cboCentroCosto_" + i);
            }

            if ($('#txtMontoDistribucion_' + i).val() * 1 >= 0) {
                item.monto = $('#txtMontoDistribucion_' + i).val() * 1;
            }

            if ($('#txtPorcentajeDistribucion_' + i).val() * 1 > 0) {
                item.porcentaje = $('#txtPorcentajeDistribucion_' + i).val() * 1;
            }

            if (!isEmpty(item.plan_contable_id) || !isEmpty(item.porcentaje)) {
                dataDistribucion.push(item);
            }
        }
    }
}

function validarDistribucion() {
    dataDistribucion = [];
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];
    var porcentaje = 0;
    var monto_total = 0;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            var item = {};
            item.linea = $('#txtNumItemDistribucion_' + i).html();

            if (isEmpty(select2.obtenerValor("cboCuentaContable_" + i))) {
                mostrarValidacionLoaderClose('Debe seleccionar la cuenta contable en la fila ' + item.linea + '.');
                return false;
            } else {
                item.plan_contable_id = select2.obtenerValor("cboCuentaContable_" + i);
            }

            if (isEmpty(select2.obtenerValor("cboCentroCosto_" + i)) && operacionTipo.requiere_centro_costo == '1') {
                mostrarValidacionLoaderClose('Debe seleccionar el centro de costo en la fila ' + item.linea + '.');
                return false;
            } else if (operacionTipo.requiere_centro_costo == '1') {
                item.centro_costo_id = select2.obtenerValor("cboCentroCosto_" + i);
            }

            if ($('#txtMontoDistribucion_' + i).val() * 1 <= 0) {
                mostrarValidacionLoaderClose('El monto en la fila ' + item.linea + ' debe ser mayor que cero.');
                return false;
            } else if ($('#txtMontoDistribucion_' + i).val() * 1 > obtenerSubTotal_Total_Distribucion()) {
                mostrarValidacionLoaderClose('El monto en la fila ' + item.linea + ' sobre pasa el monto máximo ' + obtenerSubTotal_Total_Distribucion() + '.');
                return false;
            } else {
                item.monto = $('#txtMontoDistribucion_' + i).val() * 1;
                monto_total += $('#txtMontoDistribucion_' + i).val() * 1;
            }

            if ($('#txtPorcentajeDistribucion_' + i).val() * 1 <= 0) {
                mostrarValidacionLoaderClose('El porcentaje en la fila ' + item.linea + ' debe ser mayor que cero.');
                return false;
            } else if ($('#txtPorcentajeDistribucion_' + i).val() * 1 > 100) {
                mostrarValidacionLoaderClose('El porcentaje en la fila ' + item.linea + ' no debe ser mayor de lo permitido 100%.');
                return false;
            } else {
                item.porcentaje = $('#txtPorcentajeDistribucion_' + i).val() * 1;
                porcentaje += $('#txtPorcentajeDistribucion_' + i).val() * 1;
            }

            dataDistribucion.push(item);
        }
    }

    if (devolverDosDecimales(monto_total) != obtenerSubTotal_Total_Distribucion()) {
        mostrarValidacionLoaderClose('La suma de los montos debe ser ' + obtenerSubTotal_Total_Distribucion() + '.');
        return false;
    }

    if (devolverDosDecimales(porcentaje) != 100) {
        mostrarValidacionLoaderClose('La suma de porcentajes debe ser  100.00%.');
        return false;
    }

    return true;
}

function obtenerMontoRetencion() {
    // debugger;
    let dataRetencion = obtenerDocumentoTipoDatoXTipoXCodigo("4", "10");
    if (!isEmpty(dataRetencion) && !isEmpty(dataCofiguracionInicial.dataRetencion)) {
        var retencion = dataCofiguracionInicial.dataRetencion;
        var montoTotal = $("#" + importes.totalId).val();
        if (!isEmpty(retencion) && !isEmpty(montoTotal) && (montoTotal * 1) > 0 && igvValor > 0) {
            var valorRetencion = dataRetencion.data.filter(item => item.id == select2.obtenerValor("cbo_" + dataRetencion.id));
            if (!isEmpty(valorRetencion) && valorRetencion[0]['valor'] == 1) {
                var monto_minimo = retencion.monto_minimo * 1;
                montoTotal = montoTotal * 1;
                var monedaBase = dataCofiguracionInicial.moneda.filter(item => item.base == 1)[0];

                var monedaSeleccionada = select2.obtenerValor('cboMoneda');

                var tipoCambio = ($("#tipoCambio").length > 0 && !isEmpty($('#tipoCambio').val()) ? ($('#tipoCambio').val()) * 1 : 0);
                if (monedaBase.id != monedaSeleccionada) {
                    monto_minimo = (monto_minimo / tipoCambio);
                }

                if (montoTotal > monto_minimo) {
                    var montoRetenido = (montoTotal) * ((retencion.porcentaje) / 100);
                    $('#txt_' + dataRetencion.id).removeAttr('style').attr('style', 'color:black;font-style: normal;');
                    $('#txt_' + dataRetencion.id).html('<b>Aplica la retención de ' + monedaSimbolo + ' ' + formatearNumero(montoRetenido) + '</b>');
                    $('#txt_' + dataRetencion.id).show();
                    montoTotalRetencion = montoRetenido;
                } else if (montoTotal <= monto_minimo) {
                    $('#txt_' + dataRetencion.id).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                    $('#txt_' + dataRetencion.id).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                    $('#txt_' + dataRetencion.id).show();
                    montoTotalRetencion = 0;
                }
            } else {
                $('#txt_' + dataRetencion.id).hide();
                montoTotalRetencion = 0;
            }
        } else {
            $('#txt_' + dataRetencion.id).hide();
            montoTotalRetencion = 0;
        }
    } else {
        montoTotalRetencion = 0;
    }

    if (!isEmpty(importes.calculoId)) {
        calculoTotal = parseFloat(devolverDosDecimales($('#' + importes.totalId).val() - montoTotalDetraido - montoTotalRetencion));
    }
}

function obtenerMontoDetraccion() {
    if ($('#cbo_' + cboDetraccionId).length != 0 && ($('#cbo_' + cboDetraccionId).val() * 1) > 0) {
        var detraccion = dataCofiguracionInicial.dataDetraccion.filter(item => item.id == select2.obtenerValor('cbo_' + cboDetraccionId));
        var montoTotal = $("#" + importes.totalId).val();
        if (!isEmpty(detraccion) && !isEmpty(montoTotal) && (montoTotal * 1) > 0) {
            var monto_minimo = detraccion[0].monto_minimo * 1;
            montoTotal = montoTotal * 1;
            var monedaBase = dataCofiguracionInicial.moneda.filter(item => item.base == 1)[0];

            var monedaSeleccionada = select2.obtenerValor('cboMoneda');

            var tipoCambio = ($("#tipoCambio").length > 0 && !isEmpty($('#tipoCambio').val()) ? ($('#tipoCambio').val()) * 1 : 0);
            if (monedaBase.id != monedaSeleccionada) {
                monto_minimo = (monto_minimo / tipoCambio);
            }
            if (igvValor == 0) {
                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = 0;
            } else if (montoTotal > monto_minimo) {
                var textoConversion = '';
                var montoDetraido = (montoTotal) * ((detraccion[0].porcentaje) / 100);
                if (monedaBase.id == monedaSeleccionada) {
                    montoDetraido = Math.round(montoDetraido);
                } else {
                    textoConversion = '(' + monedaBase.simbolo + ' ' + formatearNumero(Math.round(montoDetraido * tipoCambio)) + ')';
                }

                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:black;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>Aplica la detracción de ' + monedaSimbolo + ' ' + formatearNumero(montoDetraido) + ' ' + textoConversion + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = montoDetraido;
            } else if (montoTotal <= monto_minimo) {
                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = 0;
            }
        } else {
            $('#txt_' + cboDetraccionId).hide();
            montoTotalDetraido = 0;
        }
    } else {
        $('#txt_' + cboDetraccionId).hide();
        montoTotalDetraido = 0;
    }

    if (!isEmpty(importes.calculoId)) {
        calculoTotal = parseFloat(devolverDosDecimales($('#' + importes.totalId).val() - montoTotalDetraido - montoTotalRetencion));
    }
}

function onChangeCboTipoVenta() {
    asignarImporteDocumento();
}

function deshabilitarProveedor() {
//    debugger;
    if ($("#cboDocumentoTipo").val() == 189) {

        let campoProveedor = camposDinamicos.filter(item => item.tipo == 5);
        if (!isEmpty(campoProveedor)) {
            var motivoTraslado = $("#cbo_1730").val();
            if (motivoTraslado == 224) {
                select2.asignarValor("cbo_" + campoProveedor[0]['id'], "-1");
                $("#cbo_1723").prop('disabled', true); // desabilita cbo proveedor
                return true;
            } else {
                $("#cbo_1723").prop('disabled', false);
                return false;
            }
        }
    }
    return false;
}

function onChangeCboMotivo() {
    deshabilitarProveedor();

}

function colapsarBuscador() {
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').removeAttr('height', "0px");
        $('#bg-info').addClass('in');
    }
}


function habilitarDivPenalidad(bandera) {
    if (bandera) {
        select2.asignarValor('cboPenalidadMotivo');
        $("#contenedorDireccionTipoDivCombo").hide();
        $("#contenedorDireccionTipoDivTexto").show();
    } else {
        $('#txtPenalidadMotivo').val('');
        $("#contenedorDireccionTipoDivTexto").hide();
        $("#contenedorDireccionTipoDivCombo").show();
    }
}

function limpiarPenalidad() {
    habilitarDivPenalidad(false);
    $('#txtPenalidadMonto').val(0.00);
}

function agregarPenalidad() {

    let penalidadId = select2.obtenerValor('cboPenalidadMotivo');
    let penalidadText = $('#txtPenalidadMotivo').val();
    let monto = $('#txtPenalidadMonto').val();
    if (isEmpty(penalidadId) && isEmpty(penalidadText)) {
        mostrarAdvertencia("Ingrese o seleccione la penalidad");
        return;
    }

    if (isEmpty(monto) || monto == 0) {
        mostrarAdvertencia("Ingrese el monto por la penalidad");
        return;
    }

    if (!isEmpty(detallePenalidad) && !isEmpty(penalidadId)) {
        if (!isEmpty(detallePenalidad.filter(item => item.id == penalidadId))) {
            mostrarAdvertencia("Esta penalidad ya fue agregada.");
            return;
        }
    }

    penalidadText = (!isEmpty(penalidadText) ? penalidadText : select2.obtenerText('cboPenalidadMotivo'));

    detallePenalidad.push({id: penalidadId, descripcion: penalidadText, monto: monto});
    limpiarPenalidad();
    dibujarTablaPenalidad();
}


function eliminarPenalidadSeleccionad(indice)
{
    detallePenalidad.splice(indice, 1);

    dibujarTablaPenalidad();
}


function obtenerDataCombo(id) {
    let texto = $($("#cbo" + id).data("select2").search).val();


    if (isEmpty(texto)) {
//        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese un texto para buscar");
        return;
    }

    $('#cbo' + id).select2('close');

    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", texto);
    ax.setTag({id: id, valor: texto});
    ax.consumir();
}

function onResponseObtenerPersonaActivoXStringBusqueda(data, dataCombo) {
    select2.cargarSeleccione("cbo" + dataCombo.id, data, "id", ["nombre", "codigo_identificacion"], "Seleccione un cliente");
    loaderClose();
    if (!isEmpty(dataCombo.valor)) {
        $('#cbo' + dataCombo.id).select2('open');
        $($("#cbo" + dataCombo.id).data("select2").search).val(dataCombo.valor);
        $($("#cbo" + dataCombo.id).data("select2").search).trigger('input');
        setTimeout(function () {
            $('.select2-results__option').trigger("mouseup");
        }, 500);
    }
    if (!isEmpty(dataCombo.personaId)) {
        select2.asignarValor("cbo" + dataCombo.id, dataCombo.personaId);
        if (personaIdRegistro == dataCombo.personaId) {
            personaIdRegistro = null;
        }
    }

    setTimeout(function () {
        $($("#cbo" + dataCombo.id).data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13)
            {
                obtenerDataCombo(dataCombo.id);
            }
        });
    }, 1000);
}