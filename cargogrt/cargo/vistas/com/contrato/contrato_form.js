var boton ={
    enviarClase: $('#env i').attr('class'),
    accion: ''
};
var validacion = {
    organizadorExistencia: true
};
var camposDinamicos = [];
var importes = {
    totalId: null,
    subTotalId: null,
    igvId: null,
    calculoId: null
};
var cabecera = {
    chkDocumentoRelacion: 1
};
var bandera = {
    primeraCargaDocumentosRelacion : true,
    mostrarDivDocumentoRelacion : false,
    
};
var request = {
    documentoRelacion: [] // Ids de documentos a relacionar
};
var variable = {
    documentoIdCopia: null,
    movimientoIdCopia: null
};

var tipoInterfaz = document.getElementById("tipoInterfaz").value;

$(document).ready(function () {
    datePiker.iniciarPorClase('fecha');    
    
    ax.setSuccess("onResponseMovimientoFormTablas");
    ax.addParamTmp("empresaId", commonVars.empresa);
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
                loaderClose();
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
            case 'obtenerDocumentoTipoDato':
                onResponseObtenerDocumentoTipoDato(response.data);
                loaderClose();
                break;
            case 'enviar':
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

        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                break;
            case 'obtenerUnidadMedida':
                break;
            case 'enviar':
                loaderClose();
                habilitarBoton();
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
                break;
        }
    }
}

function onResponseObtenerPersonaDireccion(data){
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
function onResponseObtenerUnidadesMedida(data) {
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

        obtenerStockActual(data.indice);
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
var inicialAlturaDetalle;
var documentoTipoTipo;
var monedaSimbolo;

var opcionIGV = null;
//var valorOpcionIGV;
var monedaBase = null;
var monedaBaseId = 0;
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
            percepcionId = 0;
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

            //serie y numero            
            $("#contenedorSerieDiv").hide();
            $("#contenedorNumeroDiv").hide();

            //tipo pago
            $("#divContenedorTipoPago").hide();

            obtenerDocumentoTipoDato(e.val);
        });

        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);

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

        select2.asignarValor("cboMoneda", monedaBase);
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

                    select2.asignarValor("cboMoneda", monedaDefectoId);
                }
            });
        }

        dataDocumentoTipo = data.documento_tipo;
        documentoTipoTipo = dataDocumentoTipo[0]['tipo'];
        opcionIGV = dataDocumentoTipo[0]['opcion_igv'];


        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");

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

        movimientoTipoIndicador = data.movimientoTipo[0].indicador;
//        if(documentoTipoTipo==1){
//            $("#contenedorUtilidadesTotales").show();
//        }
        if (isEmpty(data.organizador)) {
            muestraOrganizador = false;
        }


        $('#txtComentario').val(data.documento_tipo[0].comentario_defecto);

        llenarMonedaSimboloTotales();
        llenarCabeceraDetalle();
        llenarTablaDetalle(data);

        if (!isEmpty(dataCofiguracionInicial.movimientoTipoColumna)) {
            $('#datatable').DataTable({
                "scrollX": true,
                "paging": false,
                "info": false,
                "filter": false,
                "ordering": false,
                "autoWidth": true
            });
        }

        dibujarBotonesDeEnvio(data);

        //llenar organizador en cabecera
        llenarComboOrganizadorCabecera(data.organizador);

        visualizarCambioPersonalizado(monedaBase);

        var banderaMostrarMoneda = false;
        $.each(dataCofiguracionInicial.movimientoTipoColumna, function (index, objeto) {
            if (objeto.codigo == 5) {
                banderaMostrarMoneda = true;

            }
        });

        if (banderaMostrarMoneda == false) {
            $("#contenedorMoneda").hide();
        }
    }
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
        });

        select2.cargar("cboOrganizador", data, "id", "descripcion");

        if (organizadorIdDefectoTM != 0)
            select2.asignarValor("cboOrganizador", organizadorIdDefectoTM);
        else
            select2.asignarValor("cboOrganizador", data[0].id);

        organizadorIdAntes = select2.obtenerValor("cboOrganizador");
    }
}

function confirmarCambioOrganizador() {
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

function actualizarStockDetalle() {
    var varOrganizadorId = select2.obtenerValor("cboOrganizador");

    $.each(detalle, function (indice, item) {
        detalle[indice].organizadorId = varOrganizadorId;
        obtenerStockActual(item.index);
    });
}

function llenarMonedaSimboloTotales() {
    $('#contenedorTotalDiv').append("<median class='text-uppercase' id='simTotal'>" + monedaSimbolo + "</median>");
    $('#percepcionDescripcion').append("<median class='text-uppercase' id='simPercepcion'>" + monedaSimbolo + "</median>");
    $('#contenedorIgvDiv').append("<median class='text-uppercase' id='simIGV'>" + monedaSimbolo + "</median>");
    $('#contenedorSubTotalDiv').append("<median class='text-uppercase' id='simSubTotal'>" + monedaSimbolo + "</median>");
    $('#totalUtilidadDescripcion').append(" " + "<median class='text-uppercase' id='simTotalUtildiad'>" + monedaSimbolo + "</median>");
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

        ax.addParamTmp("organizadorId", organizadorId);
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", select2.obtenerValor("cboBien_" + indice));
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
            //obtenerStockActual(indice);
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
            '<i class=\"ion-pricetag\"  title=\"Ver precio\"></i>&nbsp;Precio mínimo</a>'
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
function onResponseObtenerDocumentoTipoDato(data) {
    dataCofiguracionInicial.documento_tipo_conf = data;

    validarCambioFechaEmision = true;
    camposDinamicos = [];
    personaDireccionId = 0;
    var contador = 0;

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
                case 19:
                case 24:
                case 25:
                case 27:
                    contadorEspeciales += 1;
                    escribirItem = false;
                    break;
                default:
                    if (contador % 3 == 0) {
                        appendForm('<div class="row">');
                    }
                    contador++;

                    var html = '<div class="form-group col-md-4">' +
                            '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
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
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 5:
                            html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar ' + item.descripcion.toLowerCase() + '" style="color: #CB932A;"></i></a>' +
                                    '<span class="divider"></span> <a onclick="actualizarCboPersona()"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>';
                        case 4:
                        case 17:
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
                descripcion: item.descripcion
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
                    $("#contenedorSerie").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Serie"  style="text-align: right;"/>');
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
                    $("#contenedorNumero").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Número"  style="text-align: right;"/>');
                    break;

                case 14:
                    importes.totalId = 'txt_' + item.id;
                    $("#contenedorTotalDiv").show();
                    $("#contenedorTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    break;
                case 15:
                    importes.igvId = 'txt_' + item.id;
                    $("#contenedorIgvDiv").show();
                    $("#contenedorIgv").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    break;
                case 16:
                    importes.subTotalId = 'txt_' + item.id;
                    // si existe un subtotal, Mostramos el checkbox de cálculo de precios con / sin igv
                    $("#contenedorChkIncluyeIGV").show();
//                    $("#chkIncluyeIGV").prop("checked", "checked");
                    $("#chkIncluyeIGV").prop("checked", "");
                    $("#contenedorSubTotalDiv").show();
                    $("#contenedorSubTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
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
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
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
                    html += '<div id ="div_organizador_destino" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione organizador</option>';
                    $.each(item.data, function (indexOrganizador, itemOrganizador) {
                        html += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                    });
                    html += '</select>';
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
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
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
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    fechaEmisionAnterior = item.data;
                    break;
                case 4:
                    select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.lista_defecto)) {
                        var id = parseInt(item.lista_defecto);
                        select2.asignarValor("cbo_" + item.id, id);
                    }
                    break;
                case 5:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        obtenerPersonaDireccion(e.val);
                    });
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
    //verificar que el producto a relacionar se encuentre en la data de productos que carga el combo.
    var band=false;
    $.each(dataCofiguracionInicial.bien, function (index, itemBien) {
        if(itemBien.id==valoresFormularioDetalle.bienId){
            band=true;
            return false;
        }        
    });   
    //fin verificar    
    if(band){
        var subTotal = 0;
        if (existeColumnaCodigo(12) && existeColumnaCodigo(5) && existeColumnaCodigo(6)) {
            subTotal = devolverDosDecimales(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);
        }
        valoresFormularioDetalle.subTotal = subTotal;
        valoresFormularioDetalle.index = indexDetalle;

        detalle.push(valoresFormularioDetalle);


        if (valoresFormularioDetalle.organizadorDesc == null) {
            valoresFormularioDetalle.organizadorDesc = '';
        }

        asignarValoresDetalleFormulario();
        indexDetalle += 1;
    }
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
                case 13:// UNIDAD DE MEDIDA                    
                    obtenerUnidadMedida(valoresFormularioDetalle.bienId, indexDetalle);
                    break;
                case 15:// Organizador
                    select2.asignarValor("cboOrganizador_" + indexDetalle, valoresFormularioDetalle.organizadorId);
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
    cargarDiv("#window", "vistas/com/contrato/contrato_listar.php");
}

function enviar(accion) {
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
                    guardar(accion);
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
                        guardar(accion);
                    }
                });
            }
        });

        if (bandera == true) {
            guardar(accion);
        }

    } else {
        guardar(accion);
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
                camposDinamicos[index]["valor"] = document.getElementById("datepicker_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
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
            case 17:// organizador
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
                var objArchivo={nombre: $('#nombreArchivo').html(),data:$('#dataArchivo').val()};
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

function guardar(accion) {


    loaderShow();

    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
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
    if (validarDetalleRepetido()) {
        mostrarValidacionLoaderClose("Detalle repetido, seleccione otro bien, organizador o unidad de medida.");
        return;
    }

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

        //validando el total pago = total documento
        if (tipoPago == 2) {
            var totalPago = 0;
            listaPagoProgramacion.forEach(function (item) {
                totalPago = totalPago + item[1] * 1;
            });
            if (totalPago != calculoTotal) {
                mostrarValidacionLoaderClose('Total de pago no coincide con el total del documento');
                return;
            }
        }
    }

    if (tipoPago != 2) {
        listaPagoProgramacion = [];
    }

    deshabilitarBoton();
    ax.setAccion('enviar');
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("checkIgv", checkIgv);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("accionEnvio", accion);
    //gclv: agregando el campo de tipo de pago en base al combo de la vista
    ax.addParamTmp("tipoPago", tipoPago);
    ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
    ax.consumir();
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

    });

    return valido;
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

function actualizarCboPersona() {
    obtenerPersonas();
}

function obtenerPersonas() {
    var documneto_tipo = document.getElementById('cboDocumentoTipo').value;
    ax.setAccion("obtenerPersonas");
    ax.addParamTmp("documentoTipoId", documneto_tipo);
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
function onResponseObtenerStockPorBien(data, indice)
{
    var tituloModal = '<strong>' + select2.obtenerText("cboBien_" + indice) + '</strong>';
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

    $('.modal-title').empty();
    $('.modal-title').append(tituloModal);
    $('#modalStockBien').modal('show');
}
function calcularImporteDetalle() {
    var importe = 0;
    if (movimientoTipoIndicador != 5) {
        if (!isEmpty(detalle)) {
            $.each(detalle, function (index, item) {
                importe += (parseFloat(item.cantidad) * parseFloat(item.precio));
            });
        }
    }
    return importe;
}
var igvValor = 0.18;
var calculoTotal = 0;
function asignarImporteDocumento() {
    var calculo, igv;
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {
        calculo = calcularImporteDetalle();
        document.getElementById(importes.calculoId).value = devolverDosDecimales(calculo);
        if (importes.calculoId === importes.subTotalId) {
            if (!isEmpty(importes.igvId)) {
                igv = igvValor * calculo;
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.totalId)) {
                document.getElementById(importes.totalId).value = devolverDosDecimales(calculo + igv);
            }
        } else if (importes.calculoId === importes.totalId) {
            if (!isEmpty(importes.igvId)) {
                igv = (calculo - calculo / (1 + igvValor));
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.subTotalId)) {
                document.getElementById(importes.subTotalId).value = devolverDosDecimales(calculo - igv);
            }
        }
        calculoTotal = parseFloat($('#' + importes.totalId).val());
    }

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
    if (document.getElementById('chkIGV').checked) {
        igvValor = 0.18;
    } else {
        igvValor = 0;
    }
    asignarImporteDocumento();
}

//Area de Opcion de Copiar Documento


function cargarBuscadorDocumentoACopiar()
{
    if (bandera.primeraCargaDocumentosRelacion) {
        loaderShow();
        obtenerConfiguracionesInicialesBuscadorCopiaDocumento();
        bandera.primeraCargaDocumentosRelacion = false;
    } else {
        cargarModalCopiarDocumentos();
    }
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
    $('#modalDocumentoRelacion').modal('show');
}

function buscarDocumentoRelacionPorCriterios() {
    loaderShow('#dtDocumentoRelacion');
    var cadena;
    //alert('hola');
    cadena = obtenerDatosBusquedaDocumentoACopiar();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');

    setTimeout(function () {
        getDataTableDocumentoACopiar()
    }, 500);
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

function getDataTableDocumentoACopiar()
{
    ax.setAccion("buscarDocumentoRelacionPorCriterio");
    ax.addParamTmp("criterios", parametrosBusquedaDocumentoACopiar);
    ax.addParamTmp("empresa_id", commonVars.empresa);
    
    $('#dtDocumentoRelacion').dataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
            {"data": "fecha_creacion", "width": "9%"},
            {"data": "fecha_emision", "width": "7%"},
            {"data": "documento_tipo", "width": "13%"},
            {"data": "persona", "width": "27%"},
            {"data": "serie", "width": "7%"},
            {"data": "numero", "width": "7%"},
            {"data": "fecha_vencimiento", "width": "7%"},
            {"data": "moneda_simbolo", "width": "4%", "sClass": "alignCenter"},
            {"data": "total", "width": "8%", "sClass": "alignRight"},
            {"data": "usuario", "width": "6%", "sClass": "alignCenter"},
            {data: "flechas",
                render: function (data, type, row) {
                    if (type === 'display') {
                        var soloRelacionar = '';

                        if (row.relacionar == '1') {
                            soloRelacionar = '<a onclick="agregarCabeceraDocumentoRelacion(' + row.documento_tipo_id + ',' + row.documento_id + ',' + row.movimiento_id + ')"><b><i class="fa fa-arrow-down" style = "color:#1ca8dd;" tooltip-btndata-toggle="tooltip" title="Solo relacionar"></i></b></a>';
                        }

                        return '<a onclick="agregarDocumentoRelacion(' + row.documento_tipo_id + ',' + row.documento_id + ',' + row.movimiento_id + ',' + row.moneda_id + ',' + row.relacionar + ')"><b><i class="fa fa-download" style = "color:#04B404;" tooltip-btndata-toggle="tooltip" title="Copiar">&nbsp&nbsp</i></b></a>' +
                                soloRelacionar
                                ;
                    }
                    return data;
                },
                "orderable": false,
                "class": "alignCenter",
                "width": "5%"
            }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": [1, 6]
            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 8
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData['documento_relacionado'] != '0')
            {
                $('td', nRow).css('background-color', '#FFD0D0');
            }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    cargarModalCopiarDocumentos();
    loaderClose();

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
    fecha_vencimiento_fin: null
};
function obtenerParametrosBusquedaDocumentoACopiar()
{
    parametrosBusquedaDocumentoACopiar = {
        empresa_id: null,
        documento_tipo_ids: null,
        persona_id: null,
        serie: null,
        numero: null,
        fecha_emision_inicio: null,
        fecha_emision_fin: null,
        fecha_vencimiento_inicio: null,
        fecha_vencimiento_fin: null,
        movimiento_tipo_id: dataCofiguracionInicial.movimientoTipo[0].id
    };

    parametrosBusquedaDocumentoACopiar.empresa_id = commonVars.empresa;
    parametrosBusquedaDocumentoACopiar.documento_tipo_ids = $('#cboDocumentoTipoM').val();

    var personaId = $('#cboPersonaM').val();
    if (!isEmpty(personaId))
    {
        parametrosBusquedaDocumentoACopiar.persona_id = personaId[0];
    }

    parametrosBusquedaDocumentoACopiar.serie = $('#txtSerie').val();
    parametrosBusquedaDocumentoACopiar.numero = $('#txtNumero').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = $('#dpFechaEmisionInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_fin = $('#dpFechaEmisionFin').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio = $('#dpFechaVencimientoInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin = $('#dpFechaVencimientoFin').val();
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

    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDocumentoRelacion");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", request.documentoRelacion);
    ax.consumir();
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
            if (item.documentoId === documentoACopiarId)
            {
                resultado = true;
            }
        }

    });

    return resultado;
}

function onResponseObtenerDocumentoRelacion(data) {

    if (data.documentoACopiar[0].incluye_igv == 1) {
        $("#chkIncluyeIGV").prop("checked", "checked");
    } else {
        $("#chkIncluyeIGV").prop("checked", "");
    }

    select2.asignarValor("cboMoneda", data.documentoACopiar[0].moneda_id);
    modificarSimbolosMoneda(data.documentoACopiar[0].moneda_id, dataCofiguracionInicial.moneda[document.getElementById('cboMoneda').options.selectedIndex]);

    detalle = [];
    indiceLista = [];
    banderaCopiaDocumento = 0;
    indexDetalle = 0;

    if (data.detalleDocumento.length > 15) {
        nroFilasReducida = data.detalleDocumento.length;
        limpiarDetalle();
    } else if (data.detalleDocumento.length > 5) {
        if (nroFilasInicial == 0 || isEmpty(nroFilasInicial)) {
            nroFilasInicial = data.detalleDocumento.length;
        }
        verTodasFilas();
    }

    cargarDataDocumentoACopiar(data.documentoACopiar, data.dataDocumentoRelacionada);
    cargarDetalleDocumentoRelacion(data.detalleDocumento);

    $('#modalDocumentoRelacion').modal('hide');

    cargarDocumentoRelacionadoDeCopia(data.documentosRelacionados);

    //cargar los datos copiados de programacion
    cargarPagoProgramacion(data.dataPagoProgramacion);
}

function cargarDocumentoRelacionadoDeCopia(data) {

    if (!isEmpty(data))
    {
        if (data == 1) {
            $("#chkDocumentoRelacion").prop("checked", "");
            $("#divChkDocumentoRelacion").hide();

        } else {

            var detalleEnlace = '';
            $.each(data, function (index, item) {
                if (!validarDocumentoRelacionRepetido(parseInt(item.documento_id))) {
                    request.documentoRelacion.push({
                        documentoId: parseInt(item.documento_id),
                        movimientoId: parseInt(item.movimiento_id),
                        tipo: 2,
                        documentoPadreId: varDocumentoPadreId
                    });

                    detalleEnlace = item.documento_tipo_descripcion + ": " + item.serie_numero;

                    $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleEnlace + "]</a><br>");
//                    $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a>");
                    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

                    request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleEnlace;
                    request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
                    contadorDocumentoCopiadoAVisualizar++;
                }
            });

            varDocumentoPadreId = null;
        }

    }
}


function onResponseObtenerDocumentoRelacionCabecera(data) {
    cargarDataDocumentoACopiar(data.dataDocumento, data.dataDocumentoRelacionada);
    if (!isEmpty(variable.documentoIdCopia) && !isEmpty(variable.movimientoIdCopia))
    {
        request.documentoRelacion.push({
            documentoId: variable.documentoIdCopia,
            movimientoId: variable.movimientoIdCopia,
            tipo: 1,
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
                $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);
                banderachkDocumentoRelacion = 1;
            }
        }

        $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
        $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiarSinDetalle(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a><br>");
        $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

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
                    
                    switch (item.tipo*1 ){
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

    var banderaMostrarModal = 0;
    $('#contenedorAsignarStockXOrganizador').empty();
    //$('#dgDetalle').empty();
    dataFaltaAsignarCantidadXOrganizador = null;
    dataFaltaAsignarCantidadXOrganizador = [];

    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {

            cargarDataTableDocumentoACopiar(
                    cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad,
                            item.unidad_medida_id, item.valor_monetario, item.organizador_descripcion,
                            item.bien_descripcion, item.unidad_medida_descripcion, item.precio_tipo_id,
                            item.movimiento_bien_detalle
                            )
                    );



            /*if (!isEmpty(item.organizador_id) && isEmpty(item.stock_organizadores))
             {
             cargarDataTableDocumentoACopiar(
             cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad, item.unidad_medida_id,
             item.valor_monetario, item.organizador_descripcion, item.bien_descripcion, item.unidad_medida_descripcion)
             );
             }
             else
             {
             banderaMostrarModal = 1;
             cargarModalParaAgregarOrganizador(item);
             }*/
        });
    }

    if (banderaMostrarModal === 1)
    {
        $('#modalAsignarOrganizador').modal('show');
    }

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
        organizadorDesc, bienDesc, unidadMedidaDesc, precioTipoId, movimientoBienDetalle) {

    //agregar logica de columnas dinamicas...  
    //obtener los datos del detalle dinamico
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;
    var objDetalle = {};//Objeto para el detalle    
    var detDetalle = [];

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            //obtener los datos del detalle
            switch (parseInt(item.codigo)) {
                //numeros
                case 5:// PRECIO UNITARIO
                    objDetalle.precio = precio;
                    break;
                case 12:// CANTIDAD
                    objDetalle.cantidad = cantidad;
                    break;

                    //combos, seleccion
                case 4:// TIPO PRECIO
                    if (isEmpty(precioTipoId)) {
                        precioTipoId = dataCofiguracionInicial.precioTipo[0]['precio_tipo_id'];
                    }
                    objDetalle.precioTipoId = precioTipoId;
                    break;
                case 11:// PRODUCTO
                    objDetalle.bienId = bienId;
                    objDetalle.bienDesc = bienDesc;
                    break;
                case 13:// UNIDAD DE MEDIDA
                    objDetalle.unidadMedidaId = unidadMedidaId;
                    objDetalle.unidadMedidaDesc = unidadMedidaDesc;
                    break;
                case 15:// Organizador
                    if (organizadorIdDefectoTM == 0) {
                        objDetalle.organizadorId = organizadorId;
                        objDetalle.organizadorDesc = organizadorDesc;
                    } else {
                        objDetalle.organizadorId = organizadorIdDefectoTM;
                    }
                    break;

                default:
                    //DATOS DE MOVIMIENTO_BIEN_DETALLE
                    if (!isEmpty(movimientoBienDetalle)) {
                        $.each(movimientoBienDetalle, function (indexBD, itemBD) {
                            if (parseInt(itemBD.columna_codigo) === parseInt(item.codigo)) {
                                detDetalle.push({columnaCodigo: parseInt(itemBD.columna_codigo), valorDet: itemBD.valor_detalle});
                            }
                        });
                    }
                    break;
            }
        });
    } else {
        mostrarValidacionLoaderClose("Falta configurar las columnas del detalle");
        return false;
    }

    //fin columnas dinamicas      

    //en caso el organizador no este en el detalle pero si en la cabecera
    if (!existeColumnaCodigo(15)) {
        if (muestraOrganizador) {
            objDetalle.organizadorId = select2.obtenerValor('cboOrganizador');
        }
    }

    objDetalle.detalle = detDetalle;
    return objDetalle;
}

var banderachkDocumentoRelacion = 0;
var varDocumentoPadreId;
function cargarDataTableDocumentoACopiar(data)
{
    if (!isEmpty(data))
    {

        if (!isEmpty(variable.documentoIdCopia) && !isEmpty(variable.movimientoIdCopia))
        {
            request.documentoRelacion.push({
                documentoId: variable.documentoIdCopia,
                movimientoId: variable.movimientoIdCopia,
                tipo: 1,
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
                    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);
                    banderachkDocumentoRelacion = 1;
                }
            }

            $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
            $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a><br>");
            $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

            request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleLink;
            request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
            contadorDocumentoCopiadoAVisualizar++;
            detalleLink = null;
        }

        valoresFormularioDetalle = data;
        agregarConfirmado();
        asignarImporteDocumento();
    }
}

var banderaEliminarDocumentoRelacion = 0;
var eliminadosArray = new Array();
function eliminarDocumentoACopiar(indice)
{
    numeroItemFinal = 0;
    var tipoRelacion = request.documentoRelacion[indice].tipo;
    var contRelacion = 1;

    if (tipoRelacion == 1) {
        loaderShow();
        detalle = [];
        indiceLista = [];
        banderaCopiaDocumento = 0;
        indexDetalle = 0;
        asignarImporteDocumento();
        obtenerUtilidadesGenerales();
        nroFilasInicial = parseInt(dataDocumentoTipo[0]['cantidad_detalle']);

        if (banderaVerTodasFilas === 1) {
            nroFilasReducida = nroFilasInicial;
        } else {
            nroFilasReducida = 5;
        }


        mapaEstadoHeaders.set((request.documentoRelacion[indice].documentoId).toString(), false);
        htmlUniqueHeaders.delete(request.documentoRelacion[indice].documentoId);
        mapaHeaders.delete(request.documentoRelacion[indice].detalleLink);


        $.each(request.documentoRelacion, function (index, item) {
            if (item.documentoPadreId == request.documentoRelacion[indice].documentoId) {
                //mapaEstadoHeaders.set((request.documentoRelacion[indice].documentoId).toString(), false);
                request.documentoRelacion[index].documentoId = null;
                request.documentoRelacion[index].movimientoId = null;


                contRelacion++;
            }
        });
    }

    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20 * contRelacion);

    banderaEliminarDocumentoRelacion = 1;

    if (tipoRelacion == 1) {
        $('#dgDetalle').empty();
        llenarTablaDetalle(dataCofiguracionInicial);
    }

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
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiar(" + item.posicion + ")'style='color:red;'>&ensp;X</a>");
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

    if (tipoRelacion == 1) {
        ax.setAccion("obtenerDocumentoRelacionDetalle");
        ax.addParamTmp("documentos_relacionados", request.documentoRelacion);
        ax.consumir();
    }

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


    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20 * contRelacion);
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
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiarSinDetalle(" + item.posicion + ")'style='color:red;'>&ensp;X</a>");
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

function visualizarDocumentoRelacion(indice)
{
    if (!isEmpty(request.documentoRelacion[indice].documentoId) && !isEmpty(request.documentoRelacion[indice].movimientoId))
    {
        ax.setAccion("obtenerDocumentoRelacionVisualizar");
        ax.addParamTmp("documentoId", request.documentoRelacion[indice].documentoId);
        ax.addParamTmp("movimientoId", request.documentoRelacion[indice].movimientoId);
        ax.consumir();
    }
}

function onResponseObtenerDocumentoRelacionVisualizar(data)
{
    cargarDataDocumento(data.dataDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);


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

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
                {"data": "precioUnitario", "sClass": "alignRight"},
                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    } else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function obtenerCheckDocumentoACopiar()
{
    if ($('#divChkDocumentoRelacion').attr("style") == "display: none;") {
        cabecera.chkDocumentoRelacion = 1;
        return;
    }

    if (document.getElementById('chkDocumentoRelacion').checked) {
        cabecera.chkDocumentoRelacion = 1;
    } else
    {
        cabecera.chkDocumentoRelacion = 0;
    }
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}


function actualizarBusquedaDocumentoRelacion(){
    buscarDocumentoRelacionPorCriterios();
}

var listaDetalleFaltante;
var listaDetalleOriginal;
function onResponseEnviar(data) {
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
    } else {
        if (!isEmpty(data.dataPlantilla)) {
            dibujarModalCorreos(data);
        } else if (!isEmpty(data.dataDocumentoPago)) {
            abrirModalPagos(data);

        } else if (!isEmpty(data.dataAtencionSolicitud)) {
            //asignarAtencion();
            abrirModalAtencionSolicitud(data);
        } else if (boton.accion == 'enviarEImprimir') {
            var dataImp = data.dataImprimir;
            if (!isEmpty(dataImp.dataDocumento)) {
                cargarDatosImprimir(dataImp, 1);
            } else {
                abrirDocumentoPDF(dataImp);
            }
        } else {
            cargarPantallaListar();
        }
    }

}

function abrirDocumentoPDF(data) {
    var link = document.createElement("a");
    link.download = data.nombre + '.pdf';
    link.href = 'vistas/com/movimiento/documentos/' + data.pdf;
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
                select2.asignarValor("cboMoneda", monedaBase);
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

    var htmlPredet = '';
    if (!isEmpty(data.accionEnvioPredeterminado)) {
        accion = data.accionEnvioPredeterminado[0];
        if (!isEmpty(accion.color)) {
//            estilo='style="color: '+accion.color+'"';
            estilo = '';
        }
        htmlPredet = '&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="enviar(\'' + accion.funcion + '\')" name="env" id="env"><i class="' + accion.icono + '" ' + estilo + '></i> ' + accion.descripcion + '</button>';
    }

    if (!isEmpty(data.accionesEnvio)) {
        accion = data.accionesEnvio;

        html += '&nbsp;&nbsp;<div class="btn-group dropup">' +
                htmlPredet +
                '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" name="envOpciones" id="envOpciones"><span class="caret"></span></button>' +
                '<ul class="dropdown-menu dropdown-menu-right" role="menu">';

        $.each(accion, function (index, item) {
            estilo = '';
            if (!isEmpty(item.color)) {
                estilo = 'style="color: ' + item.color + '"';
            }

            html += '<li><a href="#" onclick="enviar(\'' + item.funcion + '\')"><i class="' + item.icono + '" ' + estilo + '></i>&nbsp;&nbsp; ' + item.descripcion + '</a></li>';
        });

        html += '</ul></div>';

    }

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
    loaderShow();

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
    obtenerPersonas();
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

    var ind = 0;
    var totalImporte = 0;
    var totalPorcentaje = 0;

    data.forEach(function (item) {

        totalImporte += item['1'] * 1;
        totalPorcentaje += item['3'] * 1;

        var eliminar = "<a href='#' onclick = 'eliminarPagoProgramacion(\""
                + item['0'] + "\", \"" + item['1'] + "\", \"" + item['2'] + "\", \"" + item['3'] + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

        var editar = "<a href='#' onclick = 'editarPagoProgramacion(\"" + ind + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

        data[ind][6] = editar + eliminar;

        ind++;
    });

    $('#dataTablePagoProgramacion').DataTable({
        "scrollX": false,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": true,
        "order": [[1, 'asc']],
        "data": data,
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
    habilitarBoton();
    var dataDP = data.dataDocumentoPago;

    if ($('#cboMoneda').val() == 4) {
        $("#contenedorTipoCambioDiv").show();
    } else {
        $("#contenedorTipoCambioDiv").hide();
    }

    $("#contenedorEfectivo").hide();
    if (!isEmpty(dataDP.documento_tipo)) {
        $("#cboDocumentoTipoNuevoPagoConDocumento").select2({
            width: "100%"
        }).on("change", function (e) {
            $("#contenedorEfectivo").hide();
            loaderShow("#modalNuevoDocumentoPagoConDocumento");
            if (e.val == 0) {
                obtenerFormularioEfectivo();
            } else {
                obtenerDocumentoTipoDatoPago(e.val);
            }
        });
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumento", dataDP.documento_tipo, "id", "descripcion");
        $('#cboDocumentoTipoNuevoPagoConDocumento').append('<option value="0">Efectivo</option>');
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", dataDP.documento_tipo[0].id);
        if (dataDP.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumento", true);
        }
        onResponseObtenerDocumentoTipoDatoPago(dataDP.documento_tipo_conf);
    }

    //llenado de la actividad    
    select2.cargar('cboActividadEfectivo', data.actividad, 'id', ['codigo', 'descripcion']);
    select2.asignarValor('cboActividadEfectivo', data.actividad[0].actividad_defecto);
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
var fechaId;
var personaNuevoId;
var totalPago;
function onResponseObtenerDocumentoTipoDatoPago(data) {
    camposDinamicosPago = [];
    fechaId = 0;
    personaNuevoId = 0;
    $("#formNuevoDocumentoPagoConDocumento").empty();
    if (!isEmpty(data)) {
        $("#contenedorDocumentoTipoNuevo").css("height", 75 * data.length);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            appendFormNuevo('<div class="row">');
            var html = '<div class="form-group col-md-12">' +
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
                case 9:
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + item.data + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    fechaId = item.id;
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
            switch (item.tipo) {
                case 4, "4":
                    select2.cargar("cbond_" + item.id, item.data, "id", "descripcion");
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5, "5":
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 20, "20":
                case 21, "21":
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
//        if (fechaId > 0 && fecha_ != '')
//        {
//            $('#datepicker_' + fechaId).val(fecha_);
//        }
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
                camposDinamicosPago[index]["valor"] = select2.obtenerValor('cbond_' + item.id);
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

//guardar el documento y pago
function guardarDocumentoPago() {
    //deshabilitarBoton();
    //parte documento operacion
    loaderShow("#modalNuevoDocumentoPagoConDocumento");

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

    //parte documento pago    
    //obtenemos el tipo de documento
    var documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
    if (isEmpty(documentoTipoIdPago)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }

    //Validar y obtener valores de los campos dinamicos
    if (documentoTipoIdPago != 0) {
        if (!obtenerValoresCamposDinamicosPago())
            return;
    } else {
        camposDinamicosPago = null;
    }
    //fin documento pago    

    //datos de registro de pago
    var cliente = select2.obtenerValor('cbo_' + obtenerDocumentoTipoDatoIdXTipo(5));
    var tipoCambio = $('#tipoCambio').val();
    var fecha = document.getElementById("datepicker_" + fechaEmisionId).value;
    var retencion = null;

    // efectivo a pagar
    var montoAPagar = $('#txtMontoAPagar').val();
    if (isEmpty(montoAPagar)) {
        montoAPagar = 0;
    }

    if (montoAPagar == 0 && documentoTipoIdPago == 0) {
        mostrarValidacionLoaderClose("Debe ingresar monto a pagar en efectivo");
        return;
    }

    var pagarCon = $('#txtPagaCon').val();
    var vuelto = $('#txtVuelto').val();

    var actividadEfectivo = $('#cboActividadEfectivo').val();

    //validar total de documento de pago = total de documento a pagar.
    var banderaTotalPago = true;
    if (documentoTipoIdPago == 0) {
        if (parseFloat(montoAPagar) != parseFloat($('#' + importes.totalId).val())) {
            banderaTotalPago = false;
        }
    } else {
        if (parseFloat($('#' + totalPago).val()) != parseFloat($('#' + importes.totalId).val())) {
            banderaTotalPago = false;
        }
    }

    if (!banderaTotalPago) {
        mostrarValidacionLoaderClose("El monto de pago debe ser igual al monto total del documento.");
        return;
    }

//        deshabilitarBoton();
    ax.setAccion("guardarDocumentoPago");
    //documento operacion    
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("checkIgv", checkIgv);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("accionEnvio", boton.accion);
    ax.addParamTmp("tipoPago", tipoPago);
    ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
    //------------------------

    //documento pago
    ax.addParamTmp("documentoTipoIdPago", documentoTipoIdPago);
    ax.addParamTmp("camposDinamicosPago", camposDinamicosPago);

    //registro de pago        
    ax.addParamTmp("cliente", cliente);
    ax.addParamTmp("montoAPagar", montoAPagar);
    ax.addParamTmp("tipoCambio", tipoCambio);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("retencion", retencion);
    ax.addParamTmp("actividadEfectivo", actividadEfectivo);
    ax.addParamTmp("empresaId", commonVars.empresa);

    //totales
    ax.addParamTmp("totalDocumento", $('#' + importes.totalId).val());
    ax.addParamTmp("totalPago", $('#' + totalPago).val());

    ax.consumir();
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
        $('.popover-content').css('color','black');
        $('[class="popover fade top in"]').css('z-index','0');
        
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
}
function imageIsLoaded(e) {
    $('#dataArchivo').attr('value', e.target.result);
}