var botonEnviar = $('#btnEnviar i').attr('class');
var botonNuevo = $('#btnNuevo i').attr('class');
var tc = -1;
var fc = "";

function obtenerTipoCambioHoy(fecha) {
    //var fecha = obtenerFechaActual();
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXfecha");
        if (isEmpty(fecha)) {
            fecha = datex.getNow1();
        }
        ax.addParamTmp("fecha", fecha);
        ax.consumir();
        fc = fecha;
    } else {
        actualizaMontoPagarFormulario();
    }
    //alert(fecha);
}

function actualizaMontoPagarFormulario() {
    var checked = document.getElementById('checkPE').checked;
    if (checked) {
        $("#txtMontoAPagar").val(devolverImporteConvertido(montoAPagarEfectivo));
    }
}

function deshabilitarBoton()
{
    $("#btnEnviar").addClass('disabled');
    $("#btnEnviar i").removeClass(botonEnviar);
    $("#btnEnviar i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#btnEnviar").removeClass('disabled');
    $("#btnEnviar i").removeClass('fa-spinner fa-spin');
    $("#btnEnviar i").addClass(botonEnviar);
}


function deshabilitarBotonGeneral(idBoton)
{
    $("#" + idBoton).addClass('disabled');
    $("#" + idBoton + " i").removeClass(botonNuevo);
    $("#" + idBoton + " i").addClass('fa fa-spinner fa-spin');
}
function habilitarBotonGeneral(idBoton)
{
    $("#" + idBoton).removeClass('disabled');
    $("#" + idBoton + " i").removeClass('fa-spinner fa-spin');
    $("#" + idBoton + " i").addClass(botonNuevo);
}
function imprimirDocumento(id, documentoTipo)
{
    loaderShow();
    ax.setAccion("imprimir");
    ax.addParamTmp("id", id);
    ax.addParamTmp("documento_tipo_id", documentoTipo);
    ax.consumir();
}

function  listarForm()
{
    cargarDiv('#window', 'vistas/com/pago/pago_documentos_pagados.php');
}
var acciones = {
    cboCliente: false,
};
var tipoDocumentoConDocumento = 0;
var documentoPagoArray = new Array();
var pagoConDocumentoPagoArray = new Array();
var dataDocumentoTipoDatoPago = new Array();
var dataDocumentoTipoDatoPagoConDocumentoPago = new Array();
var objDocumentos = {
    tipoDocumento: null,
    numero: null,
    serie: null,
    pendiente: null,
    total: null,
};
var volverMostrarModalBusquedaPagoConDocumento = false;
var volverMostrarModalBusquedaDocumentoPagar = false;
var varGuardarDocumento = false;

$(document).ready(function () {
    loaderShow();
    $("#divLeyendaDocumentoPago").hide();
    $("#divLeyendaDocumentoDePago").hide();

    $('#modalBusquedaPagoConDocumentoPago').on('hidden.bs.modal', function (e) {
        if (volverMostrarModalBusquedaPagoConDocumento == true) {
            setTimeout(function () {
                $('#modalBusquedaPagoConDocumentoPago').modal('show');
            }, 375)
        }
        volverMostrarModalBusquedaPagoConDocumento = false;
    });

    $('#modalBusquedaDocumentoPagar').on('hidden.bs.modal', function (e) {
        if (volverMostrarModalBusquedaDocumentoPagar === true) {
            setTimeout(function () {
                $('#modalBusquedaDocumentoPagar').modal('show');
            }, 375);
        }
        volverMostrarModalBusquedaDocumentoPagar = false;
    });

    $('[data-toggle="popover"]').popover({html: true}).popover();
    ax.setSuccess("onResponsePago");
    ax.setAccion("obtenerPersonaActivas");
    ax.consumir();

    //para obtener las actividades del documento    
    ax.setAccion("obtenerActividades");
    ax.addParamTmp("tipoCobranzaPago", 2);// 1 cobranza, 2 pago
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();

    cargarConfiguracionesIniciales();
    //configuraciones iniciales de documento a pagar
    cargarConfiguracionesModalBuscarDocumentoPago();
    obtenerDocumentoTipoPago();
    //configuraciones iniciales pago con documento 
    cargarConfiguracionesModalBusquedaPagoConDocumento();
    obtenerDocumentoTipoPagoConDocumentoPago();
    //configuraciones del modal nuevo documento  pago con documento
//    obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago();
    //accion del modal

});
function cargarConfiguracionesIniciales()
{
    $("#cboClientePagoPago").select2({
        width: "100%"
    });
    $("#cboClientePagoPago").select2({
        width: "100%"
    });
    /*$("#monedaId").select2({
     width: "100%"
     });*/
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });

    $('#datepicker_fechaPago').datepicker('setDate', datex.getNow1());

}

function onResponsePago(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipoPago':
                onResponseObtenerDocumentoTipo(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago':
                onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago(response.data);
//                loaderClose();
                break;
            case 'obtenerDocumentoTipoDatoPago':
                onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(response.data);
                break;

            case 'obtenerPersonaActivas':
                onResponsePersonasActivas(response.data);
                break;
            case 'obtenerDocumentoAPagar':
                onResponseDocumentoAPagar(response.data);
                validarMonedasFormasPago();
                break;
            case 'obtenerDocumentoTipoPagoConDocumentoPago':
                onResponseObtenerDocumentoTipoPagoConDocumentoPago(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPagoConDocumentoPago':
                onResponseDocumentoPagoConDocumentoPago(response.data);
                validarMonedasFormasPago();
                break;
            case 'guardarDocumento':
//                agregarDocumentoPagoConDocumento(response.data)   
                banderaGuardarDoc = 1;
                agregarDocumentoPagoConDocumento(response.data, 1, ($('#monedaId').val() == 4 ? "1" : "0"));
                loaderClose("modalNuevoDocumentoPagoConDocumentoPago");
                habilitarBoton();
                $('#modalNuevoDocumentoPagoConDocumentoPago').modal('hide');
                break;
            case 'getAllProveedor':
                onResponseProveedor(response.data);
                break;
            case 'registrarPago':
//                mostrarOk("Pago registrado satisfactoriamente");
                HabilitarBotonSweet(response.data);
//                swal("Registrado!", "", "success");
                loaderClose();
                break;
            case 'obtenerTipoCambioXfecha':
                onResponseObtenerTipoCambioHoy(response.data);
                actualizaMontoPagarFormulario();
                loaderClose();
                break;
            case 'obtenerTipoCambioXFechaDocumentoPago':
                onResponseObtenerTipoCambioXFechaDocumentoPago(response.data);
                loaderClose();
                break;
            case 'imprimir':
                cargarDatosImprimir(response.data);
                break;
            case 'obtenerActividades':
                onResponseObtenerActividades(response.data);
                break;
            case 'buscarCriteriosBusquedaDocumentoPagarPago':
                onResponseBuscarCriteriosBusquedaDocumentoPagar(response.data);
                loaderClose();
                break;

            case 'buscarCriteriosBusquedaPagoConDocumentoPago':
                onResponseBuscarCriteriosBusquedaPagoConDocumentoPago(response.data);
                loaderClose();
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'registrarPago':
                HabilitarBotonSweet({error: response.status, mensaje: response.message});
                loaderClose();
                break;
            case 'obtenerDocumentoTipoPago':
                loaderClose();
                break;
            case 'guardarDocumento':
                banderaGuardarDoc = 0;
                habilitarBoton();
                break;

        }
    }
}




function cargarPersona()
{
//    var rutaAbsoluta = 'http://' + location.host + '/almacen/index.php?token=1';
    var rutaAbsoluta = URL_BASE + 'index.php?token=1';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function onResponsePersonasActivas(data)
{
    var string = '<option selected value="-1">Seleccionar un proveedor</option>';

    $.each(data, function (indexPersona, itemPersona) {
        string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
    });
    $('#cboClientePagoPago').append(string);
    select2.asignarValor('cboClientePagoPago', "-1");
//    loaderClose();
}

var actividadDefecto;
function onResponseObtenerActividades(data)
{
    var string = '<option selected value="-1">Seleccionar actividad</option>';

    $.each(data, function (indexActividad, itemActividad) {
        string += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
    });
    $('#cboActividadEfectivo').append(string);
//    console.log(data);
    actividadDefecto = data[0].actividad_defecto;
    select2.asignarValor('cboActividadEfectivo', actividadDefecto);
    loaderClose();
}

//Sección de documento a pagar -------

function modalBusquedaDocumentoAPagarPago()
{
    var clienteId = $('#cboClientePagoPago').val();

    if (clienteId == '-1')
        clienteId = 0;

    /*if (clienteId == '-1')
     {
     $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar un  cliente');
     } else*/
    {
        if (selectPersonaId > 0) {
            select2.asignarValor('cbo_' + selectPersonaId, clienteId);
        }
        $('#modalBusquedaDocumentoPagar').modal('show');

        banderaAbrirModalDocumentoAPagar = 1;

        if (clienteId != 0) {
            buscarDocumentoPago(1);
        } else {
            loaderShow("#modalBusquedaDocumentoPagar");
            if (isEmpty(dataDocumentoTipoDatoPago)) {
                dataDocumentoTipoDatoPago.push({fechaPago: $('#datepicker_fechaPago').val()});
            } else {
                dataDocumentoTipoDatoPago[0]['fechaPago'] = $('#datepicker_fechaPago').val();
            }
            setTimeout(function () {
                getDataTableDocumentoAPagarPago()
            }, 500);
        }

    }
}

function cargarConfiguracionesModalBuscarDocumentoPago()
{
    $("#cboDocumentoTipo").select2({
        width: "100%"
    });
}

function obtenerDocumentoTipoPago()
{
    ax.setAccion("obtenerDocumentoTipoPago");
    ax.addParamTmp("empresa_id", commonVars.empresa);//commonVars.empresa
    ax.consumir();
}
var selectPersonaId;
function onResponseObtenerDocumentoTipoDato(data, personaActiva) {
//    breakFunction();
    selectPersonaId = 0;
    dataDocumentoTipoDatoPago = data;
//    console.log(data);
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas

        var columna = 1;
        $.each(data, function (index, item) {
            if (item.tipo != 12 && item.tipo != 11) {
//                console.log(item.tipo,' - ',columna);

                switch (columna) {
                    case 1:
                        if (index > 0) {
                            appendForm('</div>');
                        }
                        appendForm('<div class="row">');
                        columna = 2;
                        break;
                    case 2:
                        columna = 3;
                        break;
                    case 3:
                        columna = 1;
                        break;
                }

                var html = '<div class="form-group col-md-4">' +
                        '<label>' + item.descripcion + '</label>' +
                        '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';

                switch (parseInt(item.tipo)) {
                    case 1:
                    case 14:
                    case 15:
                    case 16:
                        html += '<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="" maxlength="26"/>';
                        break;
                    case 2:
                    case 6:
                    case 7:
                    case 8:
                    case 12:
                    case 13:
                        html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="" maxlength="45"/>';
                        break;
                    case 3:
                    case 9:
                    case 10:
                    case 11:
                        html += '<div class="row">' +
                                '<div class="form-group col-md-6">' +
                                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_inicio_' + item.id + '">' +
                                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                                '</div></div>' +
                                '<div class="form-group col-md-6">' +
                                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_fin_' + item.id + '">' +
                                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                                '</div></div></div>';
                        break;
                    case 4:
                        html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                        break;
                    case 5:
                        html += '<div id="div_persona"><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                        html += '<option value="' + 0 + '">Seleccione la persona</option>';
                        if (!isEmpty(personaActiva))
                        {
                            $.each(personaActiva, function (indexPersona, itemPersona) {
                                html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                            });
                        }

                        html += '</select></div>';
                        selectPersonaId = item.id;
                        break;
                }
                html += '</div></div>';
                appendForm(html);

                switch (item.tipo) {
                    case 4, "4":
                        $("#cbo_" + item.id).select2({
                            width: '100%'
                        });
                        break;
                    case 5, "5":
                        $("#cbo_" + item.id).select2({
                            width: '100%'
                        });
                        break;
                }

            }
        });
        appendForm('</div>');
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
//        if (selectPersonaId > 0)
//            select2.asignarValor('cbo_' + selectPersonaId, select2.obtenerValor("cboClientePagoPago"));
    }
}

function loaderBuscarDocumentoPago()
{
    actualizandoBusquedaDocumentoPagar = true;
//    var estadobuscador = $('#bg-info').attr("aria-expanded");
//    if (estadobuscador == "false")
//    {
    buscarDocumentoPago();
//    }
//    loaderClose();
}

function buscarDocumentoPago(colapsa)
{
    loaderShow("#modalBusquedaDocumentoPagar");
    var cadena;
    cadena = obtenerDatosBusqueda();
//    if (!isEmpty(cadena) && cadena !== 0)
//    {
//        $('#idPopover').attr("data-content", cadena);
//    }
//    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    setTimeout(function () {
        getDataTableDocumentoAPagarPago()
    }, 500);

//    if (colapsa === 1)
//        colapsarBuscadorDocumentoPagar();
}

function obtenerDatosBusqueda()
{
//    breakFunction();
    var valorPersona;
    tipoDocumento = $('#cboDocumentoTipo').val();
    var cadena = "";
    cargarDatoDeBusqueda();
    var valorTipoDocumento = obtenerValorTipoDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDatoPago, function (index, item) {


        switch (parseInt(item.tipo)) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                if (!isEmpty(item.valor))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor + " ";
                    cadena += "<br>";
                }
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                if (!isEmpty(item.valor.inicio) || !isEmpty(item.valor.fin))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:
                if (!isEmpty(item.valor))
                {
                    if (select2.obtenerText('cbo_' + item.id) !== null)
                    {
                        cadena += negrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbo_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0)
                {
                    cadena += negrita(item.descripcion) + ": ";
                    valorPersona = select2.obtenerText('cbo_' + item.id);
                    cadena += valorPersona;
                    cadena += "<br>";
                }
                break;
        }
    });
    dataDocumentoTipoDatoPago[0]['tipoDocumento'] = tipoDocumento;
    dataDocumentoTipoDatoPago[0]['fechaPago'] = $('#datepicker_fechaPago').val();
    return cadena;
}

function cargarDatoDeBusqueda()
{
    $.each(dataDocumentoTipoDatoPago, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 1:
                item["valor"] = $('#txt_' + item.id).val();
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                item["valor"] = $('#txt_' + item.id).val();
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                var f = {inicio: $('#datepicker_inicio_' + item.id).val(),
                    fin: $('#datepicker_fin_' + item.id).val()};
                item["valor"] = f;
                break;
            case 4:
            case 5:
                item["valor"] = $('#cbo_' + item.id).val();
                break;

        }
    });
}

function obtenerValorTipoDocumento()
{
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipo');
    if (valorTipoDocumento !== null)
    {
        var cadena = negrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";
}
function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}
function getDataTableDocumentoAPagarPago() {
    ax.setAccion("obtenerDocumentosAPagarPago");
    ax.addParamTmp("criterios", dataDocumentoTipoDatoPago);
    ax.addParamTmp("empresa_id", commonVars.empresa);
    $('#datatableModalDocumentoAPagar').DataTable().destroy();
    $('#datatableModalDocumentoAPagar').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
            {"data": "fecha_creacion", "class": "alignCenter", "width": "10%"},
            {"data": "fecha_emision", "class": "alignCenter", "width": "7%"},
            {"data": "documento_tipo_descripcion", "width": "13%"},
            {"data": "persona_nombre_completo", "width": "29%"},
            {"data": "numero", "width": "7%"},
            {"data": "fecha_vencimiento", "class": "alignCenter", "width": "7%"},
            {"data": "dolares",
                render: function (data, type, row) {
                    if (row.dolares === "1")
                        return "Dolares";
                    return "Soles";
                }
                , "width": "7%"
            },
            {"data": "pendiente", "class": "alignRight", "width": "7%"},
            {"data": "deuda_liberada", "class": "alignRight", "width": "7%"},
            {"data": "total", "class": "alignRight", "width": "8%"},
            {"data": null,
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a href="#" onClick = "agregarDocumentoPago(' + row.documento_id + ',\'' + 0 + '\',\'' + row.dolares + '\');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="ion-android-add" style = "color:#2E9AFE;" tooltip-btndata-toggle="tooltip" title="Agregar"></i><b></a>&nbsp;\n' +
                                '<a href="#" onclick="agregarDocumentoPago(' + row.documento_id + ',\'' + 1 + '\',\'' + row.dolares + '\');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="fa fa-arrow-down" style = "color:#04B404;" tooltip-btndata-toggle="tooltip" title="Agregar y cerrar"></i><b></a>';
//                        return '<button  onClick = "agregarDocumentoPago(' + row.documento_id + ');" name = "btn_' + row.documento_id + '" id="btn_' + row.documento_id + '" class="btn btn-primary" style="border-radius: 0px;" ><i class = "ion-android-add"></i></button>'
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
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [7, 8, 9]
            },
            {
                "render": function (data, type, row) {
                    var numero = data;
                    if (isEmpty(data)) {
                        numero = row.serie;
                    }
                    return numero;
                },
                "targets": 4
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();

    cambiarAnchoBusquedaDesplegable();
}

var montoAPagarEfectivo = 0;
function agregarDocumentoPagoDataTable()
{
//    volverMostrarModalBusquedaDocumentoPagar = true;
    $("#txtMontoAPagar").val("0.00");
    $('#dgDocumentoPago').empty();
    $("#tfoo1").empty();
    var cuerpototal = '';
    var pendiente = 0;
    var moneda = "S/.";
    $.each(documentoPagoArray, function (index, item) {
        var cuerpo = "<tr>" +
//                "<td style='text-align:left;'>" + item.documentoId + "</td>" +
                "<td style='text-align:left;'>" + item.tipoDocumento + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.serie) + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.numero) + "</td>" +
                "<td style='text-align:right;'>" + redondearNumero(item.total).toFixed(2) + "</td>" +
                "<td style='text-align:right;'>" + redondearNumero(item.pendiente).toFixed(2) + "</td>" +
                "<td style='text-align:center;'><a href='#' onclick='quitarDocumentoAPagar(" + item.documentoId + ")'><b><i class='ion-close' style='color:#cb2a2a;' tooltip-btndata-toggle='tooltip' title='Quitar'></i><b></a></td>" +
                "</td>" +
                "</tr>";
        cuerpototal = cuerpototal + cuerpo;
        pendiente += item.pendiente * 1
        moneda = item.dolares * 1 === 1 ? "$ " : moneda;
    });
    var fpendiente = pendiente.toFixed(2);
    montoAPagarEfectivo = fpendiente;
    pendiente = moneda + fpendiente;
    $("#dgDocumentoPago").append(cuerpototal);
    $("#modalBusquedaDocumentoPagar").modal('hide');
    //agregar y ocultar el modal
    if (accionagregarDocumentoPago == 1)
    {
        volverMostrarModalBusquedaDocumentoPagar = false;
    } else {
        volverMostrarModalBusquedaDocumentoPagar = true;
    }
    if (documentoPagoArray.length > 0)
    {
//        $("#txtMontoAPagar").val(fpendiente);
        $("#tfoo1").append("<tr><td colspan='4'>Total</td><td>" + pendiente + "</td></tr>");
        $("#divLeyendaDocumentoPago").show();
    }
    loaderClose();
}

function onResponseDocumentoAPagar(data)
{
    var objDocumentos = {
        documentoId: null,
        tipoDocumentoId: null,
        tipoDocumento: null,
        numero: null,
        serie: null,
        pendiente: null,
        dolares: null,
        total: null,
        tipo: null
    };
    objDocumentos.documentoId = data[0].documento_id;
    objDocumentos.tipoDocumento = data[0].documento_tipo;
    objDocumentos.numero = data[0].numero;
    objDocumentos.serie = data[0].serie;
//    objDocumentos.pendiente = data[0].pendiente;
    objDocumentos.pendiente = data[0].deuda_liberada;
    objDocumentos.dolares = data[0].mdolares;
    objDocumentos.total = data[0].total;
    objDocumentos.tipo = data[0].tipo;

    documentoPagoArray.push(objDocumentos);
    agregarDocumentoPagoDataTable();

    select2.asignarValor("cboClientePagoPago", data[0].persona_id);
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');
    $("#cboClientePagoPago").attr('disabled', 'disabled');
    $('#datepicker_fechaPago').attr("disabled", "disabled");
}

function verificarDocumentoPagoFueAgregado(data, documentoId)
{

    var bandera = false;

    $.each(data, function (index, item) {
        if (item.documentoId * 1 === documentoId * 1)
        {
            bandera = true;
        }
    });
    return bandera;
}

function verificaDocumentoMoneda(data, moneda) {
    if (data.length === 0) {
        return true;
    }
    return data[0].dolares * 1 === moneda * 1;
}

var accionagregarDocumentoPago;
function agregarDocumentoPago(documentoId, tipo, dolares)
{
    accionagregarDocumentoPago = tipo;
    if (verificarDocumentoPagoFueAgregado(documentoPagoArray, documentoId) == false && validarPaga(documentoId) == false)
    {
        if (!verificaDocumentoMoneda(documentoPagoArray, dolares)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento posee un tipo moneda diferente al de los agregados");
            return false;
        }
        loaderShow();
        breakFunction();
        ax.setAccion("obtenerDocumentoAPagar");
        ax.addParamTmp("documentoId", documentoId);
        ax.addParamTmp("fechaPago", $('#datepicker_fechaPago').val());
        ax.consumir();
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento ya fue agregado");
    }
}

function quitarDocumentoAPagar(documentoAPagarId)
{
    loaderShow(null);
//    console.log(documentoPagoArray);
    $.each(documentoPagoArray, function (index, value) {
        if (value.documentoId == documentoAPagarId)
        {
            documentoPagoArray.splice(index, 1);
            return false;
        }
    });
    agregarDocumentoPagoDataTable();
    if (documentoPagoArray.length == 0)
    {
        $("#divLeyendaDocumentoPago").hide();
        //habilita el combo de la persona
        $("#cboClientePagoPago").removeAttr('disabled');
        $("#datepicker_fechaPago").removeAttr('disabled');
    }
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento eliminado de la lista');
}

function onResponseObtenerDocumentoTipo(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");

        if (data.documento_tipo.length === 1) {
            select2.asignarValor("cboDocumentoTipo", data.documento_tipo[0].id);
            select2.readonly("cboDocumentoTipo", true);
            $('#divTipoDocumento').hide();
        }

        onResponseObtenerDocumentoTipoDato(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoLista(data.documento_tipo_dato_lista);
    }
}
function onResponseCargarDocumentotipoDatoLista(dataValor)
{
    if (!isEmpty(dataValor))
    {
        $.each(dataValor, function (index, item) {
            select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
        });
    }
}


function obtenerDocumentoTipoDatoPago(documentoTipoId) {
    ax.setAccion("obtenerDocumentoTipoDatoPago");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}
function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

//Fin de sección de documento a pagar--------------------------------


//Sección de pago en efectivo y con documentos  


function modalBusquedaPagoConDocumentoPago()
{
    var clienteId = $('#cboClientePagoPago').val();

    if (clienteId == '-1')
        clienteId = 0;

    /*if (clienteId == '-1')
     {
     $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar un  cliente');
     } else*/
    {
        if (selectPersonaIdPagarConDocumentoPago > 0) {
            select2.asignarValor('cbodp_' + selectPersonaIdPagarConDocumentoPago, clienteId);
        }
        $('#modalBusquedaPagoConDocumentoPago').modal('show');

        banderaAbrirModalPagoConDocumento = 1;

        if (clienteId != 0) {
            buscarDocumentoPagoConDocumentoPago(1);
        } else {
            loaderShow("#modalBusquedaPagoConDocumentoPago");
            setTimeout(function () {
                getDataTablePagoConDocumentoPago()
            }, 500);
        }


    }
}

function cargarConfiguracionesModalBusquedaPagoConDocumento()
{
    $("#cboDocumentoTipoPagoConDocumentoPago").select2({
        width: "100%"
    });
}

function obtenerDocumentoTipoPagoConDocumentoPago()
{
    ax.setAccion("obtenerDocumentoTipoPagoConDocumentoPago");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerDocumentoTipoPagoConDocumentoPago(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipoPagoConDocumentoPago", data.documento_tipo, "id", "descripcion");

        if (data.documento_tipo.length === 1) {
            select2.asignarValor("cboDocumentoTipoPagoConDocumentoPago", data.documento_tipo[0].id);
            select2.readonly("cboDocumentoTipoPagoConDocumentoPago", true);
            $('#divTipoDocumentoPagoConDocumento').hide();
        }
        onResponseObtenerDocumentoTipoDatoPagoConDocumentoPago(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoListaPagoConDocumentoPago(data.documento_tipo_dato_lista);
    }
}

function onResponseObtenerDocumentoTipoDatoPagoConDocumentoPago(data, personaActiva) {
    selectPersonaIdPagarConDocumentoPago = 0;
    dataDocumentoTipoDatoPagoConDocumentoPago = data;
    $("#formularioDocumentoTipoPagoConDocumento").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas
        var columna = 1;
        $.each(data, function (index, item) {
            if (item.tipo != 12) {
                switch (columna) {
                    case 1:
                        if (index > 0) {
                            appendFormPagarConDocumento('</div>');
                        }
                        appendFormPagarConDocumento('<div class="row">');
                        columna = 2;
                        break;
                    case 2:
                        columna = 3;
                        break;
                    case 3:
                        columna = 1;
                        break;
                }

                var html = '<div class="form-group col-md-4">' +
                        '<label>' + item.descripcion + '</label>' +
                        '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                switch (parseInt(item.tipo)) {
                    case 1:
                        html += '<input type="text" id="txtdp_' + item.id + '" name="txtdp_' + item.id + '" class="form-control" value="" maxlength="8"/>';
                        break;
                    case 2:
                    case 6:
                    case 7:
                    case 8:
                    case 12:
                    case 13:
                    case 14:
                        html += '<input type="text" id="txtdp_' + item.id + '" name="txtdp_' + item.id + '" class="form-control" value="" maxlength="45"/>';
                        break;
                    case 3:
                    case 9:
                    case 10:
                    case 11:
                        html += '<div class="row">' +
                                '<div class="form-group col-md-6">' +
                                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickerdp_inicio_' + item.id + '">' +
                                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                                '</div></div>' +
                                '<div class="form-group col-md-6">' +
                                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickerdp_fin_' + item.id + '">' +
                                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                                '</div></div></div>';
                        break;
                    case 4:
                        html += '<select name="cbo_' + item.id + '" id="cbodp_' + item.id + '" class="select2"></select>';
                        break;
                    case 5:
                        html += '<div id="div_persona"><select name="cbodp_' + item.id + '" id="cbodp_' + item.id + '" class="select2">';
                        html += '<option value="' + 0 + '">Seleccione la persona</option>';
                        if (!isEmpty(personaActiva))
                        {
                            $.each(personaActiva, function (indexPersona, itemPersona) {
                                html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                            });
                        }

                        html += '</select></div>';
                        selectPersonaIdPagarConDocumentoPago = item.id;
                        break;
                }
                html += '</div></div>';
                appendFormPagarConDocumento(html);

                switch (item.tipo) {
                    case 4, "4":
                        $("#cbodp_" + item.id).select2({
                            width: '100%'
                        });
                        break;
                    case 5, "5":
                        $("#cbodp_" + item.id).select2({
                            width: '100%'
                        });
                        break;
                }
            }
        });
        appendFormPagarConDocumento('</div>');
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
//        if (selectPersonaId > 0)
//            select2.asignarValor('cbo_' + selectPersonaId, select2.obtenerValor("cboClientePagoPago"));
    }
}

function onResponseCargarDocumentotipoDatoListaPagoConDocumentoPago(dataValor)
{
    if (!isEmpty(dataValor))
    {
        $.each(dataValor, function (index, item) {
            select2.cargar("cbodp_" + item.id, item.data, "id", "descripcion");
        });
    }
}

function appendFormPagarConDocumento(html) {
    $("#formularioDocumentoTipoPagoConDocumento").append(html);
}

function loaderBuscarPagoConDocumento()
{
    var estadobuscador = $('#bg-info2').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarDocumentoPagoConDocumentoPago();
    }
//    loaderClose();
}

function buscarDocumentoPagoConDocumentoPago(colapsa)
{
    loaderShow("#modalBusquedaPagoConDocumentoPago");
    var cadena;
    cadena = obtenerDatosBusquedaPagoConDocumentoPago();
//    cadena = obtenerDatosBusqueda();
//    if (!isEmpty(cadena) && cadena !== 0)
//    {
//        $('#idPopover2').attr("data-content", cadena);
//    }
//    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    setTimeout(function () {
        getDataTablePagoConDocumentoPago()
    }, 500);

//    if (colapsa === 1)
//        colapsarBuscadorPagoConDocumento();
}

function getDataTablePagoConDocumentoPago() {
    ax.setAccion("obtenerDocumentosPagoConDocumentoPago");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("criterios", dataDocumentoTipoDatoPagoConDocumentoPago);
    $('#datatableModalDocumentoPagoConDocumento').DataTable().destroy();
    $('#datatableModalDocumentoPagoConDocumento').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
            {"data": "fecha_creacion", "class": "alignCenter", "width": "10%"},
            {"data": "fecha_emision", "class": "alignCenter", "width": "7%"},
            {"data": "documento_tipo_descripcion", "width": "13%"},
            {"data": "persona_nombre_completo", "width": "29%"},
            {"data": "numero", "width": "7%"},
            {"data": "fecha_vencimiento", "class": "alignCenter", "width": "7%"},
            {"data": "dolares",
                render: function (data, type, row) {
                    if (row.moneda_id === "4")
                        return "Dolares";
                    return "Soles";
                }
                , "width": "7%"
            },
            {"data": "monto", "class": "alignRight", "width": "7%"},
            {"data": "total", "class": "alignRight", "width": "8%"},
            {data: "codigo",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a href="#" onClick = "agregarDocumentoPagoConDocumento(' + row.documento_id + ',\'' + 0 + '\',\'' + (row.moneda_id === "4" ? "1" : "0") + '\');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="ion-android-add" style = "color:#2E9AFE;" tooltip-btndata-toggle="tooltip" title="Agregar"></i><b></a>&nbsp;\n' +
                                '<a href="#" onclick="agregarDocumentoPagoConDocumento(' + row.documento_id + ',\'' + 1 + '\',\'' + (row.moneda_id === "4" ? "1" : "0") + '\');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="fa fa-arrow-down" style = "color:#04B404;" tooltip-btndata-toggle="tooltip" title="Agregar y cerrar"></i><b></a>';

//                        return '<button  onClick = "agregarDocumentoPagoConDocumento(' + row.documento_id + ');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '" class="btn btn-primary" style="border-radius: 0px;" ><i class = "ion-android-add"></i></button>';       
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
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [7, 8]
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();

    cambiarAnchoBusquedaDesplegablePagoConDocumento();
}
var accionAgregarDocumento;
function agregarDocumentoPagoConDocumento(documentoId, tipo, moneda)
{
    accionAgregarDocumento = tipo;
    if (!verificarDocumentoPagoFueAgregado(pagoConDocumentoPagoArray, documentoId) && !validarPagaConDocumento(documentoId))
    {
        var tmoneda = $("#monedaId").val() * 1 + moneda * 1;
        if (tmoneda > 2 && tmoneda < 5) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La moneda especificada para los pagos no coincide con la moneda del documento");
            return false;
        }
        if (!verificaDocumentoMoneda(pagoConDocumentoPagoArray, moneda)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento posee un tipo moneda diferente al de los agregados");
            return false;
        }
        ax.setAccion("obtenerDocumentoPagoConDocumentoPago");
        ax.addParamTmp("documentoId", documentoId);
        ax.consumir();
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento ya fue agregado");
    }
}
function onResponseDocumentoPagoConDocumentoPago(data)
{
    var objDocumentosPagoConDocumento = {
        documentoId: null,
        tipoDocumento: null,
        tipoDocumentoId: null,
        numero: null,
        serie: null,
        pendiente: null,
        total: 0.00,
        monto: 0.00,
    };
    objDocumentosPagoConDocumento.documentoId = data[0].documento_id;
    objDocumentosPagoConDocumento.tipoDocumento = data[0].documento_tipo;
    objDocumentosPagoConDocumento.tipoDocumentoId = data[0].documento_tipo_id;
    objDocumentosPagoConDocumento.numero = data[0].numero;
    objDocumentosPagoConDocumento.serie = data[0].serie;
    objDocumentosPagoConDocumento.pendiente = redondearNumero(data[0].pendiente);
    objDocumentosPagoConDocumento.total = redondearNumero(data[0].total);
    objDocumentosPagoConDocumento.dolares = data[0].dolares;
    objDocumentosPagoConDocumento.moneda = data[0].dolares * 1 === 0 ? "Soles" : "Dolares";
    objDocumentosPagoConDocumento.monto = redondearNumero(data[0].monto);
    pagoConDocumentoPagoArray.push(objDocumentosPagoConDocumento);
    agregarDocumentoPagoConDocumentoDataTable();
    $("#monedaId").prop("disabled", true);

    if (banderaGuardarDoc === 0) {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');
    }
    banderaGuardarDoc = 0;
}

function loaderBuscarDocumentoPagoConDocumentoPago()
{
    actualizandoBusquedaPagoConDocumento = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarDocumentoPagoConDocumentoPago();
    }
}

function agregarDocumentoPagoConDocumentoDataTable()
{
    $('#dgDocumentoPagoConDocumento').empty();
    $("#tfoo2").empty();
    var cuerpototal = '';
    var total = 0;
    var moneda = "S/.";
    $.each(pagoConDocumentoPagoArray, function (index, item) {
        var imprimir = "";
        if (item.tipoDocumentoId * 1 == 18 || item.tipoDocumentoId * 1 == 45) {
            imprimir = "<a href='#' onclick='imprimirDocumento(" + item.documentoId + ", " + item.tipoDocumentoId + ")'><b><i class='fa fa-print' style='margin-right: 5px;;' tooltip-btndata-toggle='tooltip' title='Imprimir'></i><b></a>";
        }
        var cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.tipoDocumento + "</td>" +
//                "<td style='text-align:center;'>" + quitarNULL(item.serie) + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.numero) + "</td>" +
                "<td style='text-align:right;'>" + item.moneda + "</td>" +
                "<td style='text-align:right;'>" + item.total + "</td>" +
                "<td style='text-align:right;'>" + item.monto + "</td>" +
                "<td style='text-align:center;'>" + imprimir +
                "<a href='#' onclick='quitarDocumentoPagoConDocumento(" + item.documentoId + ")'><b><i class='ion-close' style='color:#cb2a2a;' tooltip-btndata-toggle='tooltip' title='Quitar'></i><b></a></td>" +
                "</td>" +
                "</tr>";
        cuerpototal = cuerpototal + cuerpo;
        total += item.monto * 1;
        moneda = item.dolares * 1 === 1 ? "$ " : moneda;
    });
    total = moneda + total.toFixed(2);
    $("#dgDocumentoPagoConDocumento").append(cuerpototal);

    $('#modalBusquedaPagoConDocumentoPago').modal('hide');

    //Cuando es cero el modal permanece abierto ,1 se cierra
    if (accionAgregarDocumento == 0)
    {
        if (varGuardarDocumento == true)
        {
            volverMostrarModalBusquedaPagoConDocumento = false;
        } else
        {
            volverMostrarModalBusquedaPagoConDocumento = true;
        }
    }
    if (pagoConDocumentoPagoArray.length > 0)
    {
        $("#tfoo2").append("<tr><td colspan='4'>Total</td><td>" + total + "</td</tr>")
        $("#divLeyendaDocumentoDePago").show();
    }
    loaderClose();
    varGuardarDocumento = false;
}

function quitarDocumentoPagoConDocumento(documentoId)
{
    loaderShow();
    $.each(pagoConDocumentoPagoArray, function (index, item) {
        if (item.documentoId == documentoId)
        {
            pagoConDocumentoPagoArray.splice(index, 1);
            return false;
        }
    });
    agregarDocumentoPagoConDocumentoDataTable();
    if (pagoConDocumentoPagoArray.length == 0)
    {
        $("#monedaId").prop("disabled", false);
        $("#divLeyendaDocumentoDePago").hide();
    }
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento eliminado de la lista');
}
function obtenerDatosBusquedaPagoConDocumentoPago()
{
    var valorPersona;
    tipoDocumentoConDocumento = $('#cboDocumentoTipoPagoConDocumentoPago').val();
    var cadena = "";
    cargarDatoDeBusquedaPagoConDocumento();
    var valorTipoDocumento = obtenerValorTipoDocumentoPagoConDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDatoPagoConDocumentoPago, function (index, item) {


        switch (parseInt(item.tipo)) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                if (!isEmpty(item.valor))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor + " ";
                    cadena += "<br>";
                }
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                if (!isEmpty(item.valor.inicio) || !isEmpty(item.valor.fin))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:
                if (!isEmpty(item.valor))
                {
                    if (select2.obtenerText('cbodp_' + item.id) !== null)
                    {
                        cadena += negrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbodp_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0)
                {
                    cadena += negrita(item.descripcion) + ": ";
                    valorPersona = select2.obtenerText('cbodp_' + item.id);
                    cadena += valorPersona;
                    cadena += "<br>";
                }
                break;
        }
    });
    dataDocumentoTipoDatoPagoConDocumentoPago[0]['tipoDocumento'] = tipoDocumentoConDocumento;
    return cadena;
}


function cargarDatoDeBusquedaPagoConDocumento()
{
    $.each(dataDocumentoTipoDatoPagoConDocumentoPago, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 1:
                item["valor"] = $('#txtdp_' + item.id).val();
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                item["valor"] = $('#txtdp_' + item.id).val();
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                var f = {inicio: $('#datepickerdp_inicio_' + item.id).val(),
                    fin: $('#datepickerdp_fin_' + item.id).val()};
                item["valor"] = f;
                break;
            case 4:
            case 5:
                item["valor"] = $('#cbodp_' + item.id).val();
                break;

        }
    });
}

function obtenerValorTipoDocumentoPagoConDocumento()
{
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipoPagoConDocumentoPago');
    if (valorTipoDocumento !== null)
    {
        var cadena = negrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";
}

//Fin de sección de pago en efectivo y con documentos


//inicio de sección modal nuevo documento pago con documento

function modalNuevoDocumentoPagoConDocumentoPago()
{
    obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago();
    var clienteId = $('#cboClientePagoPago').val();
    if (personaNuevoId > 0 && clienteId > 0)
    {
        select2.asignarValor('cbo_' + personaNuevoId, clienteId);
    }
    deshabilitarBotonGeneral("btnNuevo");
//    $('#modal-14').modal('show');
//    $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');
}
function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago() {
    ax.setAccion("obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago");
    ax.addParamTmp('empresa_id', commonVars.empresa);
    ax.consumir();
}

var dataConfigInicialDocPago;
function onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPago(data) {

    if (!isEmpty(data.documento_tipo)) {
        data.documento_tipo = data.documento_tipo.filter(item => item.id !== "238");
    }

    dataConfigInicialDocPago = data;
    if (!isEmpty(data.documento_tipo)) {

        $("#cboDocumentoTipoNuevoPagoConDocumentoPago").select2({
            width: "100%"
        }).on("change", function (e) {
            loaderShow("#modalNuevoDocumentoPagoConDocumentoPago");
            obtenerDocumentoTipoDatoPago(e.val);
        });
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumentoPago", data.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumentoPago", data.documento_tipo[0].id);
        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumentoPago", true);
        }
        onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data.documento_tipo_conf);

        select2.cargar("cboPeriodo", data.periodo, "id", ["anio", "mes"]);
        select2.asignarValorQuitarBuscador('cboPeriodo', null);
    }
}

var camposDinamicos = [];
var fechaId;
var personaNuevoId;
var cambioPersonalizadoId = 0;
function onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data) {
    dataConfigInicialDocPago.documento_tipo_conf = data;

    camposDinamicos = [];
    fechaId = 0;
    personaNuevoId = 0;
    cambioPersonalizadoId = 0;
    var moneda_id = $("#monedaId").val();
    var tipo_moneda = (moneda_id == "4" ? " (Dolares)" : " (Soles)");
    $("#span_moneda").text(tipo_moneda);
    $("#formNuevoDocumentoPagoConDocumentoPago").empty();
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
            camposDinamicos.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion
            });
            switch (parseInt(item.tipo)) {
                case 1:
                case 14:
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="" maxlength="45" style="text-align:right; "/>';
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
                        if (itemCuenta.moneda_id * 1 == (moneda_id) * 1) {
                            html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                        }
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
                case 24:
                    cambioPersonalizadoId = item.id;
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="" maxlength="45" style="text-align:right; "/>';
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
                case 9, "9":
                    fechaId = item.id;
                    $('#datepickernd_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    }).on('changeDate', function (ev) {
                        if (cambioPersonalizadoId != 0 && !isEmpty(cambioPersonalizadoId)) {
                            obtenerTipoCambioXFechaDocumentoPago();
                        }
                        cambiarPeriodo();
                    });

                    //FALTA REVISAR EN QUE CASO SE DA.
                    if (cambioPersonalizadoId != 0 && !isEmpty(cambioPersonalizadoId)) {
                        obtenerTipoCambioXFechaDocumentoPago();
                    }

//                    $('#datepickernd_' + item.id).datepicker('setDate', item.data);                    
                    setTimeout(function () {
                        cambiarPeriodo();
                    }, 300);

                    break;
            }
        });
        var clienteId = $('#cboClientePagoPago').val();
        if (personaNuevoId > 0 && clienteId > 0)
        {
            select2.asignarValor('cbond_' + personaNuevoId, clienteId);
            if (documentoPagoArray.length > 0) {
                $('#cbond_' + personaNuevoId).attr('disabled', 'disabled');
            }
        }
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });

    }
    habilitarBotonGeneral("btnNuevo");
    $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');

    loaderClose("#modalNuevoDocumentoPagoConDocumentoPago");
}

function obtenerTipoCambioXFechaDocumentoPago() {
    ax.setAccion("obtenerTipoCambioXFechaDocumentoPago");
    ax.addParam("fecha", $('#datepickernd_' + fechaId).val());
    ax.consumir();
}

function appendFormNuevo(html) {
    $("#formNuevoDocumentoPagoConDocumentoPago").append(html);
}

function obtenerValoresCamposDinamicos() {
    var isOk = true;
    if (isEmpty(camposDinamicos))
        return false;
    $.each(camposDinamicos, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 24:
            case 14:
                camposDinamicos[index]["valor"] = document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
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
                camposDinamicos[index]["valor"] = document.getElementById("datepickernd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
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
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbond_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
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

//fin  de sección modal nuevo documento pago con documento


///session de transacción del pago

function registrarPago()
{
    deshabilitarBotonSweet();
    loaderShow();
    var montoAPagar = isChecked("checkPE") ? $('#txtMontoAPagar').val() : 0;
    var tipoCambio = $('#tipoCambio').val();
    var monedaPago = $("#monedaId").val();
    var cliente = $('#cboClientePagoPago').val();
    var fecha = $('#datepicker_fechaPago').val();
    var actividadEfectivo = $('#cboActividadEfectivo').val();

    var documentoAPagar = documentoPagoArray;
    var documentoPagoConDocumento = pagoConDocumentoPagoArray;

    ax.setAccion("registrarPago");
    ax.addParamTmp("cliente", cliente);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("montoAPagar", montoAPagar);
    ax.addParamTmp("monedaPago", monedaPago);
    ax.addParamTmp("tipoCambio", tipoCambio);
    ax.addParamTmp("documentoAPagar", documentoAPagar);
    ax.addParamTmp("documentoPagoConDocumento", documentoPagoConDocumento);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("actividadEfectivo", actividadEfectivo);
    ax.consumir();

}

function confirmarRegistrarPago()
{
    if (validarCamposDeEntradaPago())
    {
        SweetConfirmarRegistrarPago();
    }
}

function validarCamposDeEntradaPago()
{
    var bandera = true;
    var cliente = $('#cboClientePagoPago').val();
    var fecha = $('#datepicker_fechaPago').val();
    var documentoAPagar = documentoPagoArray;
    if (documentoAPagar.length == 0)
    {
        bandera = false;
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Ingresar documento a pagar');
    }
    if (fecha.length == 0 || fecha == null || fecha == '')
    {
        bandera = false;
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar una fecha');
    }
    if (cliente == -1)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar un cliente');
        bandera = false;
    }
    return bandera;
}

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

function enviarDocumento() {
    //VALIDO QUE EL PERIODO ESTE SELECCIONADO
    var periodoId = select2.obtenerValor('cboPeriodo');
    if (isEmpty(periodoId)) {
        mostrarAdvertencia('Seleccione un periodo');
        return;
    }

    //VALIDO QUE LA FECHA DE EMISION ESTE EN EL PERIODO SELECCIONADO
    var periodoFechaEm = obtenerPeriodoIdXFechaEmision();
    if (periodoId != periodoFechaEm) {
        //OCULTO EL MODAL
        $('#modalNuevoDocumentoPagoConDocumentoPago').modal('hide');

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
                guardarDocumento();
            }
            $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');
        });
        return;
    }

    guardarDocumento();
}

var banderaGuardarDoc = 0;
function guardarDocumento() {
    deshabilitarBoton();
    banderaGuardarDoc = 0;
    loaderShow("#modalNuevoDocumentoPagoConDocumentoPago");
    varGuardarDocumento = true;
//obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumentoPago");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }
//Validar y obtener valores de los campos dinamicos
    if (!obtenerValoresCamposDinamicos())
        return;
    var monedaid = $("#monedaId").val();
    var periodoId = select2.obtenerValor('cboPeriodo');

    ax.setAccion("guardarDocumento");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("moendaId", monedaid);
    ax.addParamTmp("periodoId", periodoId);
    ax.consumir();
}

function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}

function getAllProveedor()
{
    loaderShow("#modalNuevoDocumentoPagoConDocumentoPago");
    var documneto_tipo = document.getElementById('cboDocumentoTipoNuevoPagoConDocumentoPago').value;
    ax.setAccion("getAllProveedor");
    ax.addParamTmp("documentoTipoId", documneto_tipo);
    ax.consumir();
}

function onResponseProveedor(data)
{
    $("#div_proveedor").empty();
    var header = '';
    var string = '';
    var footer = '';
    var html = '';
    $.each(data, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 5:
                header = '<select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                string = '<option value="' + 0 + '">Seleccione la persona</option>';
                $.each(item.data, function (indexPersona, itemPersona) {
                    string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                });
                footer = '</select>';
                html = header + string + footer;
                $("#div_proveedor").append(html);
                break;
        }
        switch (item.tipo) {
            case 5, "5":
                $("#cbond_" + item.id).select2({
                    width: '100%'
                });
                if (!isEmpty(personaIdRegistro)) {
                    select2.asignarValor("cbond_" + item.id, personaIdRegistro);
                }
                break;
        }
    });
    loaderClose();
}

function validarPaga(id)
{
    var exito = false;
    $.each(pagoConDocumentoPagoArray, function (index, item) {
        if (item.documentoId == id)
        {
            exito = true;
            return false;
        }
    });

    return exito;
}
function validarPagaConDocumento(id)
{
    var exito = false;
    $.each(documentoPagoArray, function (index, item) {
        if (item.documentoId == id)
        {
            exito = true;
            return false;
        }
    });
    return exito;
}

var totalPago;

function obtenerTotalListaDeDocumentos(dataArray)
{
    totalPago = 0;
    $.each(dataArray, function (index, item) {
        totalPago = totalPago + parseFloat(item.pendiente);
    });
    return totalPago;
}

function SweetConfirmarRegistrarPago() {
    var actividadEfectivo = $('#cboActividadEfectivo').val();

    var checked = isChecked("checkPE");
    if (checked && actividadEfectivo == -1) {
        mostrarValidacionLoaderClose("Debe seleccionar actividad.");
        return;
    }

    //para mostrar mensaje que el pago > al monto a pagar
    var pagoDescripcion = '';
    var aPagar = obtenerTotalListaDeDocumentos(documentoPagoArray);
    var aPagarConv = aPagar;
    if (getFormatoPagoActual() != getFormatoDocumentoAPagar()) {
        aPagarConv = devolverImporteConvertido(aPagar) * 1;
    }
    var pago = $('#txtMontoAPagar').val() * 1 + obtenerTotalListaDeDocumentos(pagoConDocumentoPagoArray) * 1;

    if (pago > aPagarConv) {
        pagoDescripcion = '<p style="color: #d20e0e;display: block;">El total de pago es mayor al total del documento a pagar. '
                + '<br>En: ' + getFormatoPagoActual() + formatearNumero(pago - aPagarConv)
                + '</p> <br>';
    }

    var pagoEnEfectivo = isChecked("checkPE") ?
            "Pago en efectivo: " + getFormatoPagoActual() + formatearNumero($('#txtMontoAPagar').val()) + "<br>" : "";
    swal({
        title: "Est\xe1s seguro?",
        text: pagoDescripcion
                + "Total de documento a pagar: " + getFormatoDocumentoAPagar() + formatearNumero(obtenerTotalListaDeDocumentos(documentoPagoArray)) + "<br>"
                + pagoEnEfectivo +
                "Pago con documento: " + getFormatoPagoActual() + formatearNumero(obtenerTotalListaDeDocumentos(pagoConDocumentoPagoArray)),
        type: "warning",
        html: true,
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,registrar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            registrarPago();
        } else {
            swal("Cancelado", "La operaci\xf3n fue cancelada", "error");
        }
    });
}
function HabilitarBotonSweet(data)
{
    $(".confirm").removeProp("disabled");

    $(".cancel").removeProp("disabled");

    if (isEmpty(data.error)) {
        $('#dgDocumentoPago').empty();
        $('#dgDocumentoPagoConDocumento').empty();
        documentoPagoArray.length = 0;
        pagoConDocumentoPagoArray.length = 0;
        $('#txtMontoAPagar').val('0.00');
        $('#txtPagaCon').val('0.00');
        $('#txtVuelto').val('0.00');
        $('#divLeyendaDocumentoDePago').empty();
        $('#divLeyendaDocumentoPago').empty();
        $('#tfoo1').empty();
        $("#tfoo2").empty();
        $("#cboClientePagoPago").removeAttr('disabled');
        $("#datepicker_fechaPago").removeAttr('disabled');
        select2.asignarValor('cboClientePagoPago', -1);
        $("#checkPE").prop("checked", "");
        swal("Correcto!", "Operacion exitosa", "success");
    } else {
        swal("Validación!", data.mensaje, "warning");
    }
}

function deshabilitarBotonSweet()
{
    $(".confirm").prop("disabled", "true");

    $(".cancel").prop("disabled", "true");

    $(".confirm").html('<i id="spin"  class="fa fa-spin fa-spinner" ></i><i style="font-style: normal;font-size:14px;">Procesando...</i>');

    $(".cancel").prop("disabled", "true");
}

var actualizandoBusquedaDocumentoPagar = false;
function colapsarBuscadorDocumentoPagar() {
    if (banderaAbrirModalDocumentoAPagar === 0)
    {
        if (actualizandoBusquedaDocumentoPagar) {
            actualizandoBusquedaDocumentoPagar = false;
            return;
        }
        if ($('#bg-info').hasClass('in')) {
            $('#bg-info').attr('aria-expanded', "false");
            $('#bg-info').attr('height', "0px");
            $('#bg-info').removeClass('in');
        } else {
            $('#bg-info').attr('aria-expanded', "false");
            $('#bg-info').removeAttr('height', "0px");
            $('#bg-info').addClass('in');
        }
    } else
    {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
        banderaAbrirModalDocumentoAPagar = 0;
    }
}

var actualizandoBusquedaPagoConDocumento = false;
function colapsarBuscadorPagoConDocumento() {
    if (banderaAbrirModalPagoConDocumento === 0)
    {
        if (actualizandoBusquedaPagoConDocumento) {
            actualizandoBusquedaPagoConDocumento = false;
            return;
        }
        if ($('#bg-info2').hasClass('in')) {
            $('#bg-info2').attr('aria-expanded', "false");
            $('#bg-info2').attr('height', "0px");
            $('#bg-info2').removeClass('in');
        } else {
            $('#bg-info2').attr('aria-expanded', "false");
            $('#bg-info2').removeAttr('height', "0px");
            $('#bg-info2').addClass('in');
        }
    } else
    {
        $('#bg-info2').attr('aria-expanded', "false");
        $('#bg-info2').attr('height', "0px");
        $('#bg-info2').removeClass('in');
        banderaAbrirModalPagoConDocumento = 0;
    }
}
function obtenerTipoCambioDatepicker() {
    var fecha = $("#datepicker_fechaPago").val();
    obtenerTipoCambioHoy(fecha);
}

obtenerTipoCambioDatepicker();

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

$("#checkPE").click(function () {
    var checked = $(this).is(":checked");
    if (!checked) {
        $("#txtMontoAPagar").prop("disabled", true);
        $("#loaderPagoEfectivo").show();
        $("#txtMontoAPagar").val('0.00');

        select2.asignarValor('cboActividadEfectivo', actividadDefecto);
        $("#cboActividadEfectivo").attr('disabled', 'disabled');
        return true;
    }
    $("#loaderPagoEfectivo").hide();
    $("#txtMontoAPagar").prop("disabled", false);
    $("#txtMontoAPagar").val(devolverImporteConvertido(montoAPagarEfectivo));
    $("#cboActividadEfectivo").removeAttr('disabled');
    return true;
});
function isChecked(id) {
    return $("#" + id).is(":checked");
}


function onResponseObtenerTipoCambioHoy(data) {
    tc = -1;
    if (!isEmptyData(data)) {
        if (data[0])
            tc = data[0]['equivalencia_venta'];
    }
    if (validarMonedasFormasPago()) {
        $('#tipoCambio').val('');
        if (!isEmpty(data)) {
            $('#tipoCambio').val(data[0]['equivalencia_venta']);
            return;
        }
    }
    return;
}
function validarMonedasFormasPago() {
    var a = documentoPagoArray.length;
    var b = pagoConDocumentoPagoArray.length;
    var tipoCambio = $('#tipoCambio').val();
    var checkTipoCambio = $("#checkBP").is(":checked");

    if (a * b > 0 && tc < 0) {
        var d1 = documentoPagoArray[0].dolares * 1;
        var d2 = pagoConDocumentoPagoArray[0].dolares * 1;
        if (d1 + d2 > 0) {
            if (isEmpty(tipoCambio) && !checkTipoCambio) {
                $(".btn-submit").prop("disabled", true);
                $.Notification.notify('error', 'top center', 'Error', 'No se ha registrado un tipo de cambio para la fecha de pago.');
                return false;
            }
        }
    }
    $(".btn-submit").prop("disabled", false);
    return true;
}
function getFormatoPagoActual() {
    return $("#monedaId").val() * 1 === 2 ? "S/." : "$ ";
}

function obtenerFechaActual()
{
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

function getFormatoDocumentoAPagar() {
    return (documentoPagoArray[0].dolares) * 1 === 1 ? "$ " : "S/.";
}

//modal busqueda desplegable

$('.dropdown-menu').click(function (e) {
//        console.log(e);
    if (e.target.id != "btnBusqueda"
            && e.delegateTarget.id != "ulBuscadorDesplegable2"
            && e.delegateTarget.id != "listaEmpresa"
            && e.delegateTarget.id != "ulBuscadorDesplegablePagoConDocumento2") {
        e.stopPropagation();
    }
});

function buscarCriteriosBusquedaDocumentoPagarPago() {
//    loaderShow();
    //buscarDocumentoPago();
    ax.setAccion("buscarCriteriosBusquedaDocumentoPagarPago");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaDocumentoPagar(data) {
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
        llenarParametrosBusqueda(texto, tipoDocumentoIds, null, null);
    }

}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusqueda(null, null, serie, numero);
}

function setCriterio(array, criterio) {
    var found = false;
    for (var i = 0; i < array.length; i++) {
        var item = array[i];
        if (parseInt(item.tipo) === parseInt(criterio.tipo)) {
            found = true;
            array[i] = criterio;
            break;
        }
    }
    if (!found) {
        array.push(criterio);
    }
}

function llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero) {
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 5,
        descripcion: "Persona",
        id: 93,
        tipoDocumento: tipoDocumentoIds !== null
                ? tipoDocumentoIds
                : [],
        opcional: 0,
        orden: -1,
        valor: personaId !== null ? personaId : 0
    });
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 7,
        descripcion: "Serie",
        id: 13,
        opcional: 0,
        orden: 1,
        valor: serie
    });
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 8,
        descripcion: "Número",
        id: 14,
        opcion: 0,
        orden: 2,
        valor: numero
    });
    getDataTableDocumentoAPagarPago();
}
$('#txtBuscar').keyup(function (e) {
    var bAbierto = $(this).attr("aria-expanded");

    if (!eval(bAbierto)) {
        $(this).dropdown('toggle');
    }
});

function buscarCriteriosBusquedaPagoConDocumento() {
//    loaderShow();
    //buscarDocumentoPago();
    ax.setAccion("buscarCriteriosBusquedaPagoConDocumentoPago");
    ax.addParamTmp("busqueda", $('#txtBuscarPagoConDocumento').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaPagoConDocumentoPago(data) {
    var dataPersona = data.dataPersona;
    var dataDocumentoTipo = data.dataDocumentoTipo;
    var dataSerieNumero = data.dataSerieNumero;

    var html = '';
    $('#ulBuscadorDesplegablePagoConDocumento2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTextoPagoConDocumento(5,' + item.id + ',' + null + ')" >';
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
            html += '<a onclick="busquedaPorTextoPagoConDocumento(5,' + null + ',' + item.id + ')" >';
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
            html += '<a onclick="busquedaPorSerieNumeroPagoConDocumento(\'' + item.serie + '\',\'' + item.numero + '\')" >';
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
    $("#ulBuscadorDesplegablePagoConDocumento2").append(html);
}

function busquedaPorTextoPagoConDocumento(tipo, texto, tipoDocumento) {

    var tipoDocumentoIds = [];
    if (!isEmpty(tipoDocumento)) {
        tipoDocumentoIds.push(tipoDocumento);
    }

    if (tipo == 5) {
        llenarParametrosBusquedaPagoConDocumento(texto, tipoDocumentoIds, null, null);
    }

}

function busquedaPorSerieNumeroPagoConDocumento(serie, numero) {
    llenarParametrosBusquedaPagoConDocumento(null, null, serie, numero);
}

function llenarParametrosBusquedaPagoConDocumento(personaId, tipoDocumentoIds, serie, numero) {

    setCriterio(dataDocumentoTipoDatoPagoConDocumentoPago, {
        tipo: 7,
        descripcion: "Serie",
        id: 552,
        opcional: 0,
        orden: 1,
        valor: serie !== null ? serie : "",
        tipoDocumento: tipoDocumentoIds !== null
                ? tipoDocumentoIds
                : []
    });
    setCriterio(dataDocumentoTipoDatoPagoConDocumentoPago, {
        tipo: 5,
        descripcion: "Persona",
        id: 114,
        opcional: 0,
        orden: 1,
        valor: personaId
    });
    setCriterio(dataDocumentoTipoDatoPagoConDocumentoPago, {
        tipo: 8,
        descripcion: "Número",
        id: 115,
        opcional: 0,
        orden: 2,
        valor: numero
    });
    getDataTablePagoConDocumentoPago();
}
$('#txtBuscarPagoConDocumento').keyup(function (e) {
    var bAbierto = $(this).attr("aria-expanded");

    if (!eval(bAbierto)) {
        $(this).dropdown('toggle');
    }
});

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function cambiarAnchoBusquedaDesplegablePagoConDocumento() {
    var ancho = $("#divBuscadorPagoConDocumento").width();
    $("#ulBuscadorDesplegablePagoConDocumento").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegablePagoConDocumento2").width((ancho - 5) + "px");
}

var personaIdRegistro = null;
function setearPersonaRegistro(personaId) {
//    console.log(personaId);
    personaIdRegistro = personaId;
    getAllProveedor();
}

$('#tipoCambio').keyup(function () {
    actualizaMontoPagarFormulario();
});
$('#monedaId').change(function () {
    actualizaMontoPagarFormulario();
});

function devolverImporteConvertido(importe) {
    var tipoCambio = $('#tipoCambio').val();
    if (isEmpty(documentoPagoArray) || isEmpty(tipoCambio) || tipoCambio == '' || isEmpty(importe)) {
        return 0;
    }
    var monedaId = $("#monedaId").val();

//    console.log(importe,tipoCambio);

    var factor = 1;
    if (documentoPagoArray[0].dolares * 1 == 0 && monedaId == 4) {
        factor = tipoCambio;
    } else if (documentoPagoArray[0].dolares * 1 == 1 && monedaId == 2) {
        factor = 1 / tipoCambio;
    }

    var importeConvertido = importe / factor;
//    importeConvertido = importeConvertido.toFixed(2);//falta redondeo superior.

    var importe_10 = importeConvertido * 10;
    var importe_10_ceil = Math.ceil(importe_10);
    importeConvertido = importe_10_ceil / 10;

    return importeConvertido.toFixed(2);
}

function onResponseObtenerTipoCambioXFechaDocumentoPago(data) {
    if (!isEmptyData(data)) {
        $('#txtnd_' + cambioPersonalizadoId).val(data[0]['equivalencia_venta']);
    } else {
        $('#txtnd_' + cambioPersonalizadoId).val('');
    }
}

function obtenerDocumentoTipoDatoIdDocPagoXTipo(tipo) {
    var dataConfig = dataConfigInicialDocPago.documento_tipo_conf;

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

function cambiarPeriodo() {
    var periodoId = obtenerPeriodoIdXFechaEmision();
    select2.asignarValorQuitarBuscador('cboPeriodo', periodoId);
}

function obtenerPeriodoIdXFechaEmision() {
    var periodoId = null;
    var dtdFechaEmision = obtenerDocumentoTipoDatoIdDocPagoXTipo(9);
    if (!isEmpty(dtdFechaEmision)) {
        var fechaEmision = $('#datepickernd_' + dtdFechaEmision).val();

        var fechaArray = fechaEmision.split('/');
        var d = parseInt(fechaArray[0], 10);
        var m = parseInt(fechaArray[1], 10);
        var y = parseInt(fechaArray[2], 10);

        $.each(dataConfigInicialDocPago.periodo, function (index, item) {
            if (item.anio == y && item.mes == m) {
                periodoId = item.id;
            }
        });
    }
//    console.log(fechaArray,periodoId);
    return periodoId;
}