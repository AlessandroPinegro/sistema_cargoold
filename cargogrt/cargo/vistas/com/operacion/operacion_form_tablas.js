var c = $('#env i').attr('class');
var validarOrganizador = true;
var camposDinamicos = [];
var importes =
{
    totalId: null,
    subTotalId: null,
    igvId: null,
    calculoId: null
};

var checkActivo = 1;

var primeraCargaDocumentosACopiar = true;
var banderaDatoDocumentoCopiar = 0;
var arrayDocumentoARelacionarIds = [];


var banderaBuscar = 0;
var estadoTooltip = 0;

var var_documentoId = null;

$(document).ready(function () {
    indexDetalleEditar = null;
    //loaderShow();
    $('[data-toggle="popover"]').popover({ html: true }).popover();
    iniciarDataPicker();
    ax.setSuccess("onResponseMovimientoForm");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.consumir();
    $("#cboUnidadMedida").select2({
        width: "100%"
    });
});

function onResponseMovimientoForm(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoTipoDato':
                dataCofiguracionInicial.documento_tipo_conf = response.data;
                onResponseObtenerDocumentoTipoDato(response.data);
                loaderClose();
                break;
            case 'enviar':
                onResponseEnviar(response.data);
                //cargarPantallaListar();
                loaderClose();
                break;
            case 'enviarEImprimir':
                cargarDatosImprimir(response.data, 1);
                break;
            case 'getAllPersona':
                onResponseGetAllPersona(response.data);
                break;
            case 'obtenerPersonaDireccion':
                if (personaDireccionId !== 0) {
                    onResponseObtenerDataCbo("_" + personaDireccionId, "id", "direccion", response.data);
                }
                if (textoDireccionId !== 0) {
                    onResponseObtenerPersonaDireccionTexto(response.data);
                }
                break;
            // pagos
            case 'obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento':
                onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoTipoDatoPago':
                onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(response.data);
                break;
            case 'guardarDocumento':
                $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
                $('.modal-backdrop').hide();
                cargarPantallaListar();
                loaderClose();
                break;
            case 'obtenerTipoCambioXfecha':
                onResponseObtenerTipoCambioHoy(response.data);
                loaderClose();
                break;
            case 'guardarRetiroDeposito':
                //                $('#modalRetiroDeposito').modal('hide');
                //                $('.modal-backdrop').hide();
                cargarPantallaListar();
                loaderClose();
                break;
            case 'obtenerCuentaSaldo':
                if (isEmpty(response.data)) {
                    mostrarValidacionLoaderClose("Seleccione caja chica.");
                } else {
                    onResponseObtenerCuentaSaldo(response.data);
                    loaderClose();
                }
                break;

            //FUNCIONES PARA COPIAR DOCUMENTO
            case 'configuracionesBuscadorCopiaDocumento':
                OnResponseConfiguracionesBuscadorCopiaDocumento(response.data);
                buscarDocumentoACopiar();
                loaderClose();
                break;

            case 'buscarCriteriosBusquedaDocumentoCopiar':
                onResponseBuscarCriteriosBusqueda(response.data);
                loaderClose();
                break;

            case 'obtenerDetalleDocumentoACopiarSinDetalle':
                OnResponseObtenerDetalleDocumentoACopiarSinDetalle(response.data);
                loaderClose();
                break;

            case 'visualizarDocumento':
                onResponseVisualizarDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerSelectPersonas':
                onResponseObtenerPersonaActivoXStringBusqueda(response.data, response.tag);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                cargarPantallaListar()
                break;
            case 'obtenerUnidadMedida':
                break;
            case 'enviar':
                loaderClose();
                break;
            case 'enviarEImprimir':
                loaderClose();
                break;
            case 'guardarDocumento':
                loaderClose();
                break;
            case 'guardarRetiroDeposito':
                loaderClose();
                break;
        }
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

        //        $("#cbo" + cboId).select2({
        //            width: '100%'
        //        });
    } else {
        select2.asignarValor('cbo' + cboId, 0);
    }
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/operacion/operacion_listar.php";
    cargarDiv("#window", url);
}

function obtenerDocumentoTipoDato(documentoTipoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentoTipoDato");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

function devolverDosDecimales(num) {
    //    return Math.round(num * 100) / 100;
    //    breakFunction();
    return redondearNumero(num).toFixed(2);
}

var organizadorDefectoId;
var documentoTipoTipo;
function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.documento_tipo) && !isEmpty(data.operacionTipo) && data.operacionTipo[0]['id'] == 1) {
        var documentoTipoArray = [];
        $.each(data.documento_tipo, function (index, item) {
            if (item.id != "8" && item.id != "9" && item.id != "235" && item.id != "234") {
                documentoTipoArray.push(item);
            }
        });
        data.documento_tipo = documentoTipoArray;
    }


    if (!isEmpty(data.documento_tipo)) {
        dataCofiguracionInicial = data;
        //        console.log(dataCofiguracionInicial);

        $("#cboDocumentoTipo").select2({
            width: "100%"
        }).on("change", function (e) {
            importes.totalId = null;
            importes.subTotalId = null;
            importes.igvId = null;
            importes.calculoId = null;
            percepcionId = 0;
            $("#contenedorChkIncluyeIGV").hide();
            $("#contenedorTotalDiv").hide();
            $("#contenedorSubTotalDiv").hide();
            $("#contenedorPercepcionDiv").hide();
            $("#contenedorIgvDiv").hide();
            $("#contenedorEfectivo").hide();
            $("#contenedorSerieDiv").hide();
            $("#contenedorNumeroDiv").hide();
            $("#contenedorTipoCambioDiv").hide();
            primeraCargaDocumentosACopiar = true;
            arrayDocumentoARelacionarIds = [];
            $('#DivDocumentoACopiar').hide();

            documentoTipoTipo = parseInt(dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex]['tipo']);

            //moneda por defecto de documento tipo
            var monedaDefectoId = dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].moneda_id;
            if (!isEmpty(monedaDefectoId)) {
                $.each(dataCofiguracionInicial.moneda, function (i, itemMoneda) {
                    if (itemMoneda.id == monedaDefectoId) {
                        select2.asignarValorQuitarBuscador("cboMoneda", monedaDefectoId);
                    }
                });
            }
            //fin moneda por defecto

            obtenerDocumentoTipoDato(e.val);
            ocultarMostrarBotonesEnvio();
        });
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipo", data.documento_tipo[0].id);

        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.cargar("cboPeriodo", data.periodo, "id", ["anio", "mes"]);
        select2.asignarValorQuitarBuscador('cboPeriodo', null);

        var monedaBase = null;

        $.each(data.moneda, function (i, itemMoneda) {
            if (itemMoneda.base == 1) {
                monedaBase = itemMoneda.id;
            }
        });

        if (isEmpty(monedaBase)) {
            monedaBase = data.moneda[0].id;
        }

        select2.asignarValorQuitarBuscador("cboMoneda", monedaBase);

        //moneda por defecto de documento tipo
        var monedaDefectoId = data.documento_tipo[0].moneda_id;
        if (!isEmpty(monedaDefectoId)) {
            $.each(data.moneda, function (i, itemMoneda) {
                if (itemMoneda.id == monedaDefectoId) {
                    select2.asignarValorQuitarBuscador("cboMoneda", monedaDefectoId);
                }
            });
        }

        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipo", true);
        }

        documentoTipoTipo = parseInt(dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex]['tipo']);

        ocultarMostrarBotonesEnvio();
        onResponseObtenerDocumentoTipoDato(data.documento_tipo_conf);
    }
}

var muestraOrganizador = true;
var dataCofiguracionInicial;
var alturaBienTD;

var personaDireccionId = 0;
var personaGuardarID = 0;
var fechaPago = 0;
var dataCuenta;
var dataActividad;
var cuentaOrigenId;
var textoDireccionId = 0;
function onResponseObtenerDocumentoTipoDato(data) {
    //console.log(data);
    camposDinamicos = [];
    personaDireccionId = 0;
    var contador = 0;

    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // console.log("dataaa", data);
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
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 5:
                            html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar ' + item.descripcion.toLowerCase() + '" style="color: #CB932A;"></i></a>' +
                                '<span class="divider"></span> <a onclick="loaderComboPersona()"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>';
                        case 4:
                        case 17:
                        case 18:
                            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">';
                            break;
                        case 20:
                            html += '<span class="divider"></span> <a onclick="obtenerCuentaSaldo();"><i class="ion-android-archive" tooltip-btndata-toggle="tooltip" title="Ver saldo de caja"></i></a>';
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

                    if (item.codigo != 1) {
                        $("#contenedorSerieDiv").show();
                    }
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
                    $("#contenedorNumero").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Número"  readonly style="text-align: right;"/>');
                    break;

                case 14:
                    importes.totalId = 'txt_' + item.id;
                    $("#contenedorTotalDiv").show();
                    $("#contenedorTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;"/>');
                    break;
                case 15:
                    importes.igvId = 'txt_' + item.id;
                    $("#contenedorIgvDiv").show();
                    $("#contenedorIgv").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    break;
                case 16:
                    importes.subTotalId = 'txt_' + item.id;
                    // si existe un subtotal, Mostramos el checkbox de cálculo de precios con / sin igv
                    //                    $("#contenedorChkIncluyeIGV").show();
                    //                    $("#chkIncluyeIGV").prop("checked", "checked");
                    $("#contenedorSubTotalDiv").show();
                    $("#contenedorSubTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;"  onchange=\"calcularImportes()\" onkeyup =\"calcularImportes()\"/>');
                    break;
                case 19:
                    percepcionId = item.id;
                    $("#contenedorPercepcionDiv").show();
                    $("#contenedorPercepcion").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" onchange="calculeTotalMasPercepcion(' + item.id + ')" onkeyup ="calculeTotalMasPercepcion(' + item.id + ')" disabled/>');
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
                case 3:
                case 9:
                    fechaPago = item.id;
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '" onchange="obtenerTipoCambioDatepicker();">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                    break;

                case 5:
                    personaGuardarID = item.id;
                    html += '<div id ="div_persona" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la persona</option>';
                    if (!isEmpty(item.data)) {
                        $.each(item.data, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    } else {
                        console.log("no hay personas", "personaGuardarID", personaGuardarID);
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
                    dataCuenta = item.data;
                    cuentaOrigenId = item.id;
                    html += '<div id ="div_cuenta" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                    });
                    html += '</select>';
                    break;
                case 21:
                    dataActividad = item.data;
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
                case 37:
                    html += '<div id ="div_persona_' + item.id + '" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
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
                        cambiarPeriodo();
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    cambiarPeriodo();
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
                    });
            
                    setTimeout(function () {
                        $($("#cbo_" + item.id).data("select2").search).on('keyup', function (e) {
                            if (e.keyCode === 13) {
                                obtenerDataCombo("_" + item.id);
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
                case 23:
                case 37:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
            }
        });
        //console.log(contador/3,Math.ceil(contador/3));
        //$("#contenedorDocumentoTipo").css("height", 75 * (data.length-contadorEspeciales));
        $("#contenedorDocumentoTipo").css("height", 75 * Math.ceil(contador / 3));
        //onChangeCheckIncluyeIGV();

        dibujarCuentaTransferencia();
    }
    //$("#contenedorDocumentoTipo").css("height", $("#contenedorDocumentoTipo").height() + 30);
}

function calcularImportes() {
    //alert("totales");
    importes.calculoId = importes.subTotalId;
    asignarImporteDocumento();
}

var percepcionId = 0;
function calculeTotalMasPercepcion(id) {
    //var calculo = calcularImporteDetalle();
    //console.log(calculoTotal); 
    if (calculoTotal <= 0) {
        mostrarValidacionLoaderClose("Total debe ser mayor a cero.");
        return false;
    }

    var percepcion = parseFloat($('#txt_' + id).val());

    if (isEmpty(percepcion) || !esNumero(percepcion) || percepcion < 0) {
        mostrarValidacionLoaderClose("Debe ingresar una percepción válida");
        $('#' + importes.totalId).val(redondearNumero(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var percepcionMaxima = 0.02 * calculoTotal + 1;

    if (percepcion > percepcionMaxima) {
        mostrarValidacionLoaderClose("Percepción no puede ser mayor a: " + redondearNumero(percepcionMaxima));
        $('#' + importes.totalId).val(redondearNumero(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var suma = percepcion + calculoTotal;
    $('#' + importes.totalId).val(redondearNumero(suma));

}

function onChangeCheckPercepcion() {
    if (document.getElementById('chkPercepcion').checked) {
        $('#txt_' + percepcionId).removeAttr('disabled');
    } else {
        $('#txt_' + percepcionId).val('');
        $('#' + importes.totalId).val(redondearNumero(calculoTotal));
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

function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}
var detalle = [];
var indexDetalle = 0;
function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}

function cargarPantallaListar() {
    cargarDiv("#window", "vistas/com/operacion/operacion_listar.php");
}
function enviar() {
    //VALIDO QUE EL PERIODO ESTE SELECCIONADO
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
                guardar("enviar");
            }
        });
        return;
    }

    guardar("enviar");
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
            case 37:// proveedor relacionado al financiamiento
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

        }
    });
    return isOk;
}

function enviarEImprimir() {
    guardar("enviarEImprimir");
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
    if (parseFloat($('#' + importes.totalId).val()) <= 0) {
        mostrarValidacionLoaderClose("Total debe ser positivo.");
        return;
    }

    if (isEmpty($('#txtDescripcion').val())) {
        mostrarValidacionLoaderClose("Debe ingresar descripción");
        return;
    }

    if (!obtenerValoresCamposDinamicos())
        return;

    var periodoId = select2.obtenerValor('cboPeriodo');

    obtenerCheckDocumentoACopiar();

    ax.setAccion(accion);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("descripcion", $('#txtDescripcion').val());
    ax.addParamTmp("monedaId", $('#cboMoneda').val());
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoARelacionar", arrayDocumentoARelacionarIds);
    ax.addParamTmp("valor_check", checkActivo);
    ax.addParamTmp("periodoId", periodoId);
    ax.consumir();
}

function cargarPersona() {
    var rutaAbsoluta = URL_BASE + 'index.php?token=1';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
    //    var win = window.open(rutaAbsoluta, '_blank');
    //    win.focus();
}

function loaderComboPersona() {
    getAllPersona();
}

function getAllPersona() {
    var documneto_tipo = document.getElementById('cboDocumentoTipo').value;
    ax.setAccion("getAllPersona");
    ax.addParamTmp("documentoTipoId", documneto_tipo);
    ax.consumir();
}
function onResponseGetAllPersona(data) {
    $("#div_persona").empty();
    var header = '';
    var string = '';
    var footer = '';
    var html = '';

    $.each(data, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 5:
                header = '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                string = '<option value="' + 0 + '">Seleccione la persona</option>';
                $.each(item.data, function (indexPersona, itemPersona) {
                    string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                });
                footer = '</select>';
                html = header + string + footer;
                $("#div_persona").append(html);
                break;
        }

        switch (item.tipo) {
            case 5, "5":
                $("#cbo_" + item.id).select2({
                    width: '100%'
                }).on("change", function (e) {
                    obtenerPersonaDireccion(e.val);
                    obtenerBienesConStockMenorACantidadMinima(e.val);
                });

                if (!isEmpty(personaIdRegistro)) {
                    select2.asignarValor("cbo_" + item.id, personaIdRegistro);
                }
                break;
        }
    });
}

function calcularImporteDetalle() {
    var importe = document.getElementById(importes.subTotalId).value;

    return redondearNumero(importe);
}
var igvValor = 0.18;
var calculoTotal = 0;
function asignarImporteDocumento() {
    var calculo, igv;
    //validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {
        calculo = calcularImporteDetalle();
        //document.getElementById(importes.calculoId).value = calculo;
        if (importes.calculoId === importes.subTotalId) {
            if (!isEmpty(importes.igvId)) {
                igv = redondearNumero(igvValor * calculo);
                document.getElementById(importes.igvId).value = igv;
            }
            if (!isEmpty(importes.totalId)) {
                document.getElementById(importes.totalId).value = redondearNumero(calculo + igv);
            }
        } else if (importes.calculoId === importes.totalId) {
            if (!isEmpty(importes.igvId)) {
                igv = redondearNumero(calculo - calculo / (1 + igvValor));
                document.getElementById(importes.igvId).value = igv;
            }
            if (!isEmpty(importes.subTotalId)) {
                document.getElementById(importes.subTotalId).value = redondearNumero(calculo - igv);
            }
        }
        calculoTotal = parseFloat($('#' + importes.totalId).val());
    }
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
        if (Math.abs(importeFinalSugerido - importeFinal) > 1 && percepcionId == 0) {
            mostrarValidacionLoaderClose("El importe total tiene mucha variación con el cálculado por el sistema. No se puede continuar la operación.");
            return false;
        }
    }
    return true;
}
function onChangeCheckIncluyeIGV() {
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

function onChangeCheckIGV() {
    if (document.getElementById('chkIGV').checked) {
        igvValor = 0.18;
    } else {
        igvValor = 0;
    }
    asignarImporteDocumento();
}

function iniciarDataPicker() {
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}


// modal comentario
function abrirModalComentario() {

    /* var stringTitulo = '<strong>' + select2.obtenerText("cboDocumentoTipo") + '</strong>';   
     $('#tituloModalComentario').empty();
     $('#tituloModalComentario').append(stringTitulo);*/

    $('#modalComentario').modal('show');
}

//cerrar modal
function cerrarModalComentario() {
    $('#modalComentario').modal('hide');
}

//inicio de sección modal nuevo documento pago con documento
function enviarYPagar() {
    if (!obtenerValoresCamposDinamicos())
        return;

    //VALIDO QUE EL PERIODO ESTE SELECCIONADO
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
                guardar("enviar");
            }
        });
        return;
    }

    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if ($('#cboMoneda').val() == 4) {
        $("#contenedorTipoCambioDiv").show();
    } else {
        $("#contenedorTipoCambioDiv").hide();
    }

    loaderShow();
    ax.setAccion("obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("documentoTipo", documentoTipoId);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento(data) {
    //    console.log(data);    
    $("#contenedorEfectivo").hide();
    if (!isEmpty(data.documento_tipo)) {
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
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumento", data.documento_tipo, "id", "descripcion");
        $('#cboDocumentoTipoNuevoPagoConDocumento').append('<option value="0">Efectivo</option>');
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", data.documento_tipo[0].id);
        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumento", true);
        }
        onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data.documento_tipo_conf);
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
    $("#txtPagaCon").val($('#' + importes.totalId).val());
    actualizarVuelto();
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

function obtenerTipoCambioDatepicker() {
    var fecha = document.getElementById("datepicker_" + fechaPago).value;
    obtenerTipoCambioHoy(fecha);
}

var fc = "";
function obtenerTipoCambioHoy(fecha) {
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXfecha");
        ax.addParam("fecha", fecha);
        ax.consumir();
        fc = fecha;
    }
}

function onResponseObtenerTipoCambioHoy(data) {
    //    tc = -1;
    //    if(!isEmptyData(data)){
    //        if(data[0])
    //            tc = data[0]['equivalencia_venta'];
    //    }
    //    if(validarMonedasFormasPago()){
    $('#tipoCambio').val('');
    if (!isEmpty(data)) {
        $('#tipoCambio').val(data[0]['equivalencia_venta']);
        return;
    }
    //    }
    return;
}

//$('#txtPagaCon').keyup(function () {
//    var monto = parseFloat($('#txtMontoAPagar').val());
//    var pago = parseFloat($('#txtPagaCon').val());
//    var vuelto = pago - monto;
//    $('#txtVuelto').val(vuelto.toFixed(2));
//});

function actualizarVuelto() {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
}

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
function onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data) {
    camposDinamicosPago = [];
    personaNuevoId = 0;
    $("#formNuevoDocumentoPagoConDocumento").empty();
    if (!isEmpty(data)) {
        $("#contenedorDocumentoTipoNuevo").css("height", 75 * data.length);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            appendFormNuevo('<div class="row">');
            var html = '<div class="form-group col-md-12">' +
                '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
            if (item.tipo == 5) {
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
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + fechaEmision + '" disabled>' +
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
        var clienteId = select2.obtenerValor('cbo_' + personaGuardarID);
        if (personaNuevoId > 0 && clienteId > 0) {
            select2.asignarValor('cbond_' + personaNuevoId, clienteId);
        }
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
        var fecha_ = $('#datepicker_fechaPago').val();
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

function guardarDocumento() {
    //deshabilitarBoton();
    //parte documento operacion
    //loaderShow();     
    loaderShow("#modalNuevoDocumentoPagoConDocumento");
    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }

    //validamos que el total no sea negativo o cero
    if (parseFloat($('#' + importes.totalId).val()) <= 0) {
        mostrarValidacionLoaderClose("Total debe ser positivo.");
        return;
    }

    if (!obtenerValoresCamposDinamicos())
        return;
    //fin documento operacion    
    //parte documento pago    
    //obtenemos el tipo de documento
    var documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
    if (isEmpty(documentoTipoIdPago)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }

    if (isEmpty($('#txtDescripcion').val())) {
        mostrarValidacionLoaderClose("Debe ingresar descripción");
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
    var cliente = select2.obtenerValor('cbo_' + personaGuardarID);
    var tipoCambio = $('#tipoCambio').val();
    var fecha = document.getElementById("datepicker_" + fechaPago).value;
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

    if (documentoTipoIdPago == 0 && (parseFloat(vuelto) < 0 || isEmpty(vuelto))) {
        mostrarValidacionLoaderClose("El vuelto no puede ser negativo.");
        return;
    }

    var actividadEfectivo = $('#cboActividadEfectivo').val();
    obtenerCheckDocumentoACopiar();

    var periodoId = select2.obtenerValor('cboPeriodo');

    ax.setAccion("guardarDocumento");
    //documento operacion
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("descripcion", $('#txtDescripcion').val());
    ax.addParamTmp("monedaId", $('#cboMoneda').val());

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

    //relaciones
    ax.addParamTmp("documentoARelacionar", arrayDocumentoARelacionarIds);
    ax.addParamTmp("valor_check", checkActivo);

    ax.addParamTmp("periodoId", periodoId);
    ax.consumir();
}

var documentoTipoGeneraId;
function onResponseEnviar(data) {
    //    console.log(data);
    //    if (!isEmpty(data.documentoTipoGenerar)) {
    //        loaderClose();
    //        $('#modalRetiroDeposito').modal('show');
    //
    //
    //    } else {
    cargarPantallaListar();
    //    }

}

function enviarRetiroDeposito() {
    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }

    if (!obtenerValoresCamposDinamicos())
        return;

    var cuentaId = select2.obtenerValor('cbo_' + cuentaOrigenId);

    // validar que este seleccionado cuenta destino    
    var cuentaDestinoId = 0;
    if (document.getElementById("chkTransferencia").checked) {
        cuentaDestinoId = select2.obtenerValor("cboCuentaDestino");
        if (isEmpty(cuentaDestinoId)) {
            mostrarValidacionLoaderClose("Seleccione la cuenta destino");
            return;
        }

        if (cuentaId == cuentaDestinoId) {
            var cuentaDescripcion = 'cuenta de egreso';
            if (documentoTipoTipo == 8) {
                cuentaDescripcion = 'cuenta de ingreso';
            }

            mostrarValidacionLoaderClose("Seleccione una " + cuentaDescripcion + " diferente");
            return;
        }
    }

    obtenerCheckDocumentoACopiar();
    var periodoId = select2.obtenerValor('cboPeriodo');
    loaderShow();
    ax.setAccion("guardarRetiroDeposito");
    // nuevo documento operacion
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("descripcion", $('#txtDescripcion').val());
    ax.addParamTmp("monedaId", $('#cboMoneda').val());

    //para nuevo documento de retiro/operacion    
    //    ax.addParamTmp("documentoTipoGeneraId", documentoTipoGeneraId);
    ax.addParamTmp("cuentaDestinoId", cuentaDestinoId);
    ax.addParamTmp("documentoARelacionar", arrayDocumentoARelacionarIds);
    ax.addParamTmp("valor_check", checkActivo);
    ax.addParamTmp("periodoId", periodoId);

    ax.consumir();
}

// modal saldo de caja
function onResponseObtenerCuentaSaldo(data) {

    //    var stringTitulo = '<strong>' + select2.obtenerText('cbo_' + cuentaOrigenId);   + '</strong>';
    var stringTitulo = select2.obtenerText('cbo_' + cuentaOrigenId);
    $('#tituloModalCuenta').empty();
    $('#tituloModalCuenta').append(stringTitulo);

    $('#modalCuenta').modal('show');

    $('#txtSaldo').val(devolverDosDecimales(data[0]['total']));
}

//cerrar modal
function cerrarModalCuenta() {
    $('#modalCuenta').modal('hide');
}

function obtenerCuentaSaldo() {

    var cuentaId = select2.obtenerValor('cbo_' + cuentaOrigenId);

    loaderShow();
    ax.setAccion("obtenerCuentaSaldo");
    ax.addParamTmp("cuentaId", cuentaId);
    ax.consumir();
}

function onResponseObtenerPersonaDireccionTexto(data) {
    //    console.log(data);
    if (isEmpty(data)) {
        $('#txt_' + textoDireccionId).val('');
    } else {
        $('#txt_' + textoDireccionId).val(data[0]['direccion']);
    }

}

var personaIdRegistro = null;
function setearPersonaRegistro(personaId) {
    //    console.log(personaId);
    personaIdRegistro = personaId;
    getAllPersona();
    obtenerPersonaDireccion(personaIdRegistro);
}

function dibujarCuentaTransferencia() {
    $("#contenedorTransferencia").empty();
    if (documentoTipoTipo == 7 || documentoTipoTipo == 8) {
        var html = '<div class="form-group col-md-12" style="display: none;">';
        html += '<div class="checkbox">';
        html += '<label class="cr-styled">';
        html += '<input type="checkbox" name="chkTransferencia" id="chkTransferencia" onclick="mostrarComboCuentaTransferencia()">';
        html += '<i class="fa"></i> ';
        html += 'Transferencia';
        html += '</label>';
        html += '</div>';
        html += '</div>';

        $("#contenedorTransferencia").append(html);
        appendForm('<div id="divCuentaDestino"><div>');
    }
}

function mostrarComboCuentaTransferencia() {
    $("#divCuentaDestino").empty();
    var dtdActividadId = obtenerDocumentoTipoDatoIdXTipo(21);
    var actividadId = obtenerActividadIdXDescripcion("Transferencia monetaria");

    if (document.getElementById("chkTransferencia").checked) {
        select2.asignarValor("cbo_" + dtdActividadId, actividadId);

        var cuentaDescripcion = 'Cuenta de egreso';
        if (documentoTipoTipo == 8) {
            cuentaDescripcion = 'Cuenta de ingreso';
        }

        var html = '<div class="form-group col-md-4">';
        html += '<label>' + cuentaDescripcion + '</label>';
        html += '<select name="cboCuentaDestino" id="cboCuentaDestino" class="select2"></select>';
        html += '</div>';

        $("#divCuentaDestino").append(html);

        select2.cargar("cboCuentaDestino", dataCuenta, "id", "descripcion_numero");

        $("#cboCuentaDestino").select2({
            width: '100%'
        });
    } else {
        select2.asignarValor("cbo_" + dtdActividadId, 0);
    }
}

function ocultarMostrarBotonesEnvio() {
    if (documentoTipoTipo == 7 || documentoTipoTipo == 8) {
        $("#envRetDep").show();
        $("#env").hide();
        $("#envPag").hide();
    } else if (documentoTipoTipo == 0) {
        $("#envRetDep").hide();
        $("#env").show();
        $("#envPag").hide();
    } else {
        $("#envRetDep").hide();
        $("#env").show();
        $("#envPag").show();
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

function obtenerActividadIdXDescripcion(actividadDescripcion) {
    var id = null;
    if (!isEmpty(dataActividad)) {
        $.each(dataActividad, function (index, item) {
            if (item.descripcion == actividadDescripcion) {
                id = item.id;
                return false;
            }
        });
    }

    return id;
}

//Area de Opcion de Copiar Documento

function cargarBuscadorDocumentoACopiar() {
    if (primeraCargaDocumentosACopiar) {
        loaderShow();
        obtenerConfiguracionesInicialesBuscadorCopiaDocumento();
        primeraCargaDocumentosACopiar = false;
    } else {
        cargarModalCopiarDocumentos();
    }
}

function obtenerConfiguracionesInicialesBuscadorCopiaDocumento() {
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");

    ax.setAccion("configuracionesBuscadorCopiaDocumento");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

function OnResponseConfiguracionesBuscadorCopiaDocumento(data) {
    //    console.log(data);
    $("#cboDocumentoTipoM").select2({
        width: "100%"
    });
    select2.cargar("cboDocumentoTipoM", data.documentoTipo, "id", "descripcion");
    $("#cboPersonaM").select2({
        width: "100%"
    });
    select2.cargar("cboPersonaM", data.persona, "id", "nombre");

    cargarModalCopiarDocumentos();
}

function cargarModalCopiarDocumentos() {
    $('#modalBusquedaDocumentoACopiar').modal('show');
}

function buscarDocumentoACopiar() {
    loaderShow('#datatableModalDocumentoACopiar');

    obtenerParametrosBusquedaDocumentoACopiar();
    setTimeout(function () {
        getDataTableDocumentoACopiar()
    }, 500);
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
function obtenerParametrosBusquedaDocumentoACopiar() {
    parametrosBusquedaDocumentoACopiar = {
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

    parametrosBusquedaDocumentoACopiar.empresa_id = commonVars.empresa;
    parametrosBusquedaDocumentoACopiar.documento_tipo_ids = $('#cboDocumentoTipoM').val();

    var personaId = $('#cboPersonaM').val();
    if (!isEmpty(personaId)) {
        parametrosBusquedaDocumentoACopiar.persona_id = personaId[0];
    }

    parametrosBusquedaDocumentoACopiar.serie = $('#txtSerie').val();
    parametrosBusquedaDocumentoACopiar.numero = $('#txtNumero').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = $('#dpFechaEmisionInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_fin = $('#dpFechaEmisionFin').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio = $('#dpFechaVencimientoInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin = $('#dpFechaVencimientoFin').val();
}

function getDataTableDocumentoACopiar() {
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");

    ax.setAccion("buscarDocumentoACopiar");
    ax.addParamTmp("criterios", parametrosBusquedaDocumentoACopiar);
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    //breakFunction();

    $('#datatableModalDocumentoACopiar').dataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
            { "data": "fecha_creacion", "width": "10%" },
            { "data": "fecha_emision", "width": "7%" },
            { "data": "documento_tipo", "width": "14%" },
            { "data": "persona", "width": "29%" },
            { "data": "serie", "width": "8%" },
            { "data": "numero", "width": "8%" },
            { "data": "fecha_vencimiento", "width": "7%" },
            { "data": "moneda_simbolo", "width": "4%", "sClass": "alignCenter" },
            { "data": "total", "width": "8%", "sClass": "alignRight" },
            {
                data: "flechas",
                render: function (data, type, row) {
                    if (type === 'display') {
                        var soloRelacionar = '';

                        if (row.relacionar == '1') {
                            soloRelacionar = '<a onclick="agregarAbrirDocumentoACopiar(' + row.documento_tipo_id + ',' + row.documento_id + ')"><b><i class="ion-android-add" style="color:#2E9AFE;" tooltip-btndata-toggle="tooltip" title="Agregar"></i></b></a>  '
                                + '<a onclick="agregarDocumentoACopiarSinDetalle(' + row.documento_tipo_id + ',' + row.documento_id + ')"><b><i class="fa fa-arrow-down" style="color:#04B404;" tooltip-btndata-toggle="tooltip" title="Agregar y cerrar"></i></b></a>';
                        }

                        return soloRelacionar
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
            if (aData['documento_relacionado'] != '0') {
                $('td', nRow).css('background-color', '#FFD0D0');
            }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    cargarModalCopiarDocumentos();
    loaderClose();

}

//here
$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

function buscarCriteriosBusquedaDocumentoCopiar() {
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");

    ax.setAccion("buscarCriteriosBusquedaDocumentoCopiar");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

function onResponseBuscarCriteriosBusqueda(data) {
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
    obtenerParametrosBusquedaDocumentoACopiar();

    parametrosBusquedaDocumentoACopiar.serie = serie;
    parametrosBusquedaDocumentoACopiar.numero = numero;
    parametrosBusquedaDocumentoACopiar.persona_id = personaId;
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

function actualizarBusquedaCopiaDocumento() {
    buscarDocumentoACopiar(0);
}

var banderaAbrirCerrar = 0;
function agregarAbrirDocumentoACopiar(documentoTipoOrigenId, documentoId) {
    banderaAbrirCerrar = 1;
    agregarDocumentoACopiarSinDetalle(documentoTipoOrigenId, documentoId);

    //    loaderClose();
    //    setTimeout(function(){ cargarBuscadorDocumentoACopiar(); }, 1200);
}

function agregarDocumentoACopiarSinDetalle(documentoTipoOrigenId, documentoId) {
    loaderShow("#modalBusquedaDocumentoACopiar");
    //loaderShow();
    if (validarDocumentoACopiarRepetido(documentoId)) {
        mostrarAdvertencia("Documento a copiar ya a sido agregado");
        loaderClose();
        return;
    }

    var_documentoId = documentoId;

    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDetalleDocumentoACopiarSinDetalle");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("documentos_relacinados", arrayDocumentoARelacionarIds);
    ax.consumir();
}

function validarDocumentoACopiarRepetido(documentoACopiarId) {
    var resultado = false;
    $.each(arrayDocumentoARelacionarIds, function (index, item) {
        if (!isEmpty(item.documentoId)) {
            if (item.documentoId === documentoACopiarId) {
                resultado = true;
            }
        }

    });

    return resultado;
}

var banderachkDocumentoACopiar = 0;
var varDocumentoPadreId;
var detalleLink;
function OnResponseObtenerDetalleDocumentoACopiarSinDetalle(data) {
    cargarDataDocumentoACopiar(data.dataDocumento, data.dataDocumentoRelacionada);
    if (!isEmpty(var_documentoId)) {
        arrayDocumentoARelacionarIds.push({
            documentoId: var_documentoId,
            tipo: 1,
            documentoPadreId: null
        });
        varDocumentoPadreId = var_documentoId;

        var_documentoId = null;
    }

    if (!isEmpty(detalleLink)) {
        if (banderaDatoDocumentoCopiar === 1) {
            $('#DivDocumentoACopiar').show();

            if (banderachkDocumentoACopiar === 0) {
                $("#DivDocumentoACopiar").css("height", $("#DivDocumentoACopiar").height() + 20);
                banderachkDocumentoACopiar = 1;
            }
        }

        var htmlComision = '';
        if (dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].identificador_negocio == 19) {
            htmlComision = '[Comisión: ' + devolverDosDecimales(arrayComision[contadorDocumentoCopiadoAVisualizar].comision) + ']';
        }

        $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "] " + htmlComision + "</a>");
        $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiarSinDetalle(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a><br>");
        $("#DivDocumentoACopiar").css("height", $("#DivDocumentoACopiar").height() + 20);

        arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleLink;
        arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
        contadorDocumentoCopiadoAVisualizar++;
        detalleLink = null;
    }

    $('#modalBusquedaDocumentoACopiar').modal('hide');
    if (banderaAbrirCerrar == 1) {
        setTimeout(function () {
            $('#modalBusquedaDocumentoACopiar').modal('show');
        }, 500);
    }
    banderaAbrirCerrar = 0;

    cargarDocumentoRelacionadoDeCopia(data.documentoCopiaRelaciones);
}


var contadorDocumentoCopiadoAVisualizar = 0;
var arrayComision = [];
function cargarDataDocumentoACopiar(data, documentoTipoDatacopia) {
    if (dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].identificador_negocio == 19) {
        arrayComision.push({
            comision: documentoTipoDatacopia[0].valor * 1
        });
    }

    //    console.log(data,documentoTipoDatacopia);
    var documentoTipo = "", serie = "", numero = "";
    if (banderaDatoDocumentoCopiar === 0) {
        if (!isEmpty(data)) {

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
            banderaDatoDocumentoCopiar = 1;

        }

        if (!isEmpty(documentoTipoDatacopia)) {
            $.each(documentoTipoDatacopia, function (index, item) {
                if (isEmpty(item.documento_tipo_dato_origen)) {
                    select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                } else {
                    $('#txt_' + item.documento_tipo_dato_destino).val(item.valor);
                    if (isEmpty($('#txt_' + item.documento_tipo_dato_destino).val())) {
                        $('#datepicker_' + item.documento_tipo_dato_destino).val(formatearFechaJS(item.valor));
                    }
                }
            });
        }
    }

    //PARA COMPROBANTE DE COMISION ACTUALIZAR EL TOTAL
    if (dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].identificador_negocio == 19) {
        var comisionTotal = 0;
        if (!isEmpty(arrayComision)) {
            $.each(arrayComision, function (i, item) {
                comisionTotal += item.comision * 1;

            });
        }
        $('#' + importes.totalId).val(devolverDosDecimales(comisionTotal * 1));
    }

    if (!isEmpty(data)) {
        $.each(data, function (index, item) {

            documentoTipo = item.documento_tipo_descripcion;

            switch (parseInt(item.tipo)) {
                case 7:
                    if (!isEmpty(item.valor)) {
                        serie = item.valor;
                    }

                    break;
                case 8:
                    if (!isEmpty(item.valor)) {
                        numero = item.valor;
                    }
                    break;
            }
        });

        detalleLink = documentoTipo + ": " + serie + " - " + numero;
    }
}



function cargarDocumentoRelacionadoDeCopia(data) {

    if (!isEmpty(data)) {
        if (data == 1) {
            $("#chkDocumentoACopiar").prop("checked", "");
            //            $("#checkDocumentoRelaciones").hide();

        } else {

            var detalleEnlace = '';
            $.each(data, function (index, item) {
                if (!validarDocumentoACopiarRepetido(parseInt(item.documento_id))) {
                    arrayDocumentoARelacionarIds.push({
                        documentoId: parseInt(item.documento_id),
                        tipo: 2,
                        documentoPadreId: varDocumentoPadreId
                    });

                    detalleEnlace = item.documento_tipo_descripcion + ": " + item.serie_numero;

                    $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleEnlace + "]</a><br>");
                    //                    $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a>");
                    $("#DivDocumentoACopiar").css("height", $("#DivDocumentoACopiar").height() + 20);

                    arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleEnlace;
                    arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
                    contadorDocumentoCopiadoAVisualizar++;
                }
            });

            varDocumentoPadreId = null;
        }

    }
}

function visualizarDocumentoACopiar(indice) {
    if (!isEmpty(arrayDocumentoARelacionarIds[indice].documentoId)) {
        ax.setAccion("visualizarDocumento");
        ax.addParamTmp("documento_id", arrayDocumentoARelacionarIds[indice].documentoId);
        ax.consumir();
    }
}

function onResponseVisualizarDocumento(data) {
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);

    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna, data.organizador);
    } else {
        $('#formularioCopiaDetalle').hide();
    }
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentarioCopia').val(data[0]['comentario_documemto']);
    $('#txtDescripcionCopia').val(data[0]['descripcion_documemto']);
}

function cargarDataDocumento(data) {
    $("#formularioDetalleDocumento").empty();

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

            if (!isEmpty(valor)) {
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
                    case 19:
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

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function fechaArmada(valor) {
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

function eliminarDocumentoACopiarSinDetalle(indice) {
    var contRelacion = 1;
    //eliminar las relaciones hijas    
    $.each(arrayDocumentoARelacionarIds, function (index, item) {
        if (item.documentoPadreId == arrayDocumentoARelacionarIds[indice].documentoId) {
            arrayDocumentoARelacionarIds[index].documentoId = null;
            contRelacion++;
        }
    });


    $("#DivDocumentoACopiar").css("height", $("#DivDocumentoACopiar").height() - 20 * contRelacion);
    banderaEliminarDocumentoRelacion = 1;

    var banderaExisteDocumentoRelacionado = 0;

    arrayDocumentoARelacionarIds[indice].documentoId = null;
    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    $.each(arrayDocumentoARelacionarIds, function (index, item) {

        if (!isEmpty(item.documentoId)) {
            var htmlComision = '';
            if (dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].identificador_negocio == 19) {
                htmlComision = '[Comisión: ' + devolverDosDecimales(arrayComision[index].comision) + ']';
            }

            $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoACopiar(" + item.posicion + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "] " + htmlComision + "</a>");

            if (item.tipo == 1) {
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiarSinDetalle(" + item.posicion + ")'style='color:red;'>&ensp;X</a>");
            }

            $('#linkDocumentoACopiar').append("<br>");

            banderaExisteDocumentoRelacionado = 1;
        }
    });

    //ACTUALIZAR LA COMISION TOTAL
    if (dataCofiguracionInicial.documento_tipo[document.getElementById('cboDocumentoTipo').options.selectedIndex].identificador_negocio == 19) {
        arrayComision[indice].comision = 0;
        var comisionTotal = 0;
        if (!isEmpty(arrayComision)) {
            $.each(arrayComision, function (i, item) {
                comisionTotal += item.comision * 1;

            });
        }
        $('#' + importes.totalId).val(devolverDosDecimales(comisionTotal * 1));
    }

    if (banderaExisteDocumentoRelacionado === 0) {
        banderaDatoDocumentoCopiar = 0;
        $('#DivDocumentoACopiar').hide();
        $("#checkDocumentoRelaciones").show();
        $("#chkDocumentoACopiar").prop("checked", "checked");
    }
}

function obtenerCheckDocumentoACopiar() {
    if ($('#checkDocumentoRelaciones').attr("style") == "display: none;") {
        checkActivo = 1;
        return;
    }

    if (document.getElementById('chkDocumentoACopiar').checked) {
        checkActivo = 1;
    } else {
        checkActivo = 0;
    }
}

var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna, dataOrganizador) {
    movimientoTipoColumna = dataMovimientoTipoColumna;
    console.log(data);
    if (!isEmptyData(data)) {
        $('#formularioCopiaDetalle').show();

        $.each(data, function (index, item) {
            data[index]["importe"] = formatearNumero(data[index]["cantidad"] * data[index]["valor_monetario"]);
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["valor_monetario"] = formatearNumero(data[index]["valor_monetario"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
        //        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataOrganizador)) {
            html += "<th style='text-align:center;'>Organizador</th>";
        }
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Unidad de medida</th>";
        html += "<th style='text-align:center;'>Producto</th> ";
        if (existeColumnaCodigo(5)) {
            html += "<th style='text-align:center;'>Precio Unitario</th>";
            html += "<th style='text-align:center;'>Total</th>";
        }
        html += "</tr>";
        tHeadDetalle.append(html);


        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalle');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            html += "<tr>";
            //            if(existeColumnaCodigo(15)){
            if (!isEmpty(dataOrganizador)) {
                html += "<td>" + item.organizador_descripcion + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidad_medida_descripcion + "</td>";
            html += "<td>" + item.bien_descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.valor_monetario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function existeColumnaCodigo(codigo) {
    var dataColumna = movimientoTipoColumna;

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

function obtenerDataCombo(id, personaId) {
    let texto = $($("#cbo" + id).data("select2").search).val();


    if (isEmpty(texto)) {
        //        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese un texto para buscar");
        return;
    }

    $('#cbo' + id).select2('close');

    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", texto);
    ax.setTag({ id: id, valor: texto, personaId: personaId });
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
            if (e.keyCode === 13) {
                obtenerDataCombo(dataCombo.id);
            }
        });
    }, 1000);
}