var botonEnviar = $('#btnEnviar i').attr('class');
var botonNuevo = $('#btnNuevoC i').attr('class');
var efectivoActual = 0;
function desHabilitarBotonGeneral(idBoton)
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
function  listarForm()
{
    cargarDiv('#window', 'vistas/com/pago/cobranza_documentos_cobrados.php');
}
function imprimirDocumento(id, documentoTipo)
{
    loaderShow();
    ax.setAccion("imprimir");
    ax.addParamTmp("id", id);
    ax.addParamTmp("documento_tipo_id", documentoTipo);
    ax.consumir();
}
var acciones = {
    cboCliente: false,
};
var tipoDocumentoConDocumento = 0;
var documentoArray = new Array();
var pagoConDocumentoArray = new Array();
var dataDocumentoTipoDato = new Array();
var dataDocumentoTipoDatoPagoConDocumento = new Array();
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

    $('#modalBusquedaPagoConDocumento').on('hidden.bs.modal', function (e) {
        if (volverMostrarModalBusquedaPagoConDocumento == true) {
            setTimeout(function () {
                $('#modalBusquedaPagoConDocumento').modal('show');
            }, 375)
        }
        volverMostrarModalBusquedaPagoConDocumento = false;
    });

    $('#modalBusquedaDocumentoPagar').on('hidden.bs.modal', function (e) {
        if (volverMostrarModalBusquedaDocumentoPagar == true) {
            setTimeout(function () {
                $('#modalBusquedaDocumentoPagar').modal('show');
            }, 375)
        }
        volverMostrarModalBusquedaDocumentoPagar = false;
    });

    $('[data-toggle="popover"]').popover({html: true}).popover();
    ax.setSuccess("onResponsePago")
    ax.setAccion("obtenerPersonaActivas");
    ax.consumir();

    //para obtener las actividades del documento    
    ax.setAccion("obtenerActividades");
    ax.addParamTmp("tipoCobranzaPago", 1);// 1 cobranza, 2 pago
    ax.addParamTmp("empresaId", commonVars.empresa);// 1 cobranza, 2 pago
    ax.consumir();

    cargarConfiguracionesIniciales();
    //configuraciones iniciales de documento a pagar
    cargarConfiguracionesModalBuscarDocumentoPago();
    obtenerDocumentoTipo();
    //configuraciones iniciales pago con documento 
    cargarConfiguracionesModalBusquedaPagoConDocumento();
    obtenerDocumentoTipoPagoConDocumento();

});
function cargarConfiguracionesIniciales()
{
    $("#cboClientePago").select2({
        width: "100%"
    });
    $("#cboClientePago").select2({
        width: "100%"
    });
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });

    $('#datepicker_fechaPago').datepicker('setDate', datex.getNow1());

    $("#cboActividadEfectivo").select2({
        width: "100%"
    });
}

function onResponsePago(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipo':
                onResponseObtenerDocumentoTipo(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento':
                onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento(response.data);
//                loaderClose();
                break;
            case 'obtenerDocumentoTipoDato':
                onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(response.data);
                break;

            case 'obtenerPersonaActivas':
                onResponsePersonasActivas(response.data);
                break;
            case 'obtenerDocumentoAPagar':
                onResponseDocumentoAPagar(response.data);
                validarMonedasFormasPago();
                break;
            case 'obtenerDocumentoTipoPagoConDocumento':
                onResponseObtenerDocumentoTipoPagoConDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPagoConDocumento':
                onResponseDocumentoPagoConDocumento(response.data);
                validarMonedasFormasPago();
                break;
            case 'guardarDocumento':
                banderaGuardarDoc = 1;
//                console.log($('#monedaId').val());
                agregarDocumentoPagoConDocumento(response.data, 1, ($('#monedaId').val() == 4 ? "1" : "0"));
                loaderClose("modalNuevoDocumentoPagoConDocumento");
                habilitarBoton();
                $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
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
            case 'imprimir':
                cargarDatosImprimir(response.data);
                break;
            case 'validarSiTieneDocumentoRetencionDetraccion':
                onResponseValidarSiTieneDocumentoRetencionDetraccion(response.data);
                loaderClose();
                break;
            case 'obtenerActividades':
                onResponseObtenerActividades(response.data);
                break;
            case 'obtenerTipoCambioXfecha':
                onResponseObtenerTipoCambioHoy(response.data);
                actualizaMontoPagarFormulario();
                actualizaVueltoFormulario();
                loaderClose();
                break;
            case 'buscarCriteriosBusquedaDocumentoPagar':
                onResponseBuscarCriteriosBusquedaDocumentoPagar(response.data);
                loaderClose();
                break;
            case 'buscarCriteriosBusquedaPagoConDocumento':
                onResponseBuscarCriteriosBusquedaPagoConDocumento(response.data);
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
            case 'obtenerDocumentoTipo':
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
    var rutaAbsoluta = URL_BASE + 'index.php?token=1';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function onResponsePersonasActivas(data)
{
    var string = '<option selected value="-1">Seleccionar un cliente</option>';

    $.each(data, function (indexPersona, itemPersona) {
        string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
    });
    $('#cboClientePago').append(string);
    select2.asignarValor('cboClientePago', "-1");
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
    actividadDefecto = data[0].actividad_defecto;
    select2.asignarValor('cboActividadEfectivo', actividadDefecto);
    loaderClose();
}

//Sección de documento a pagar -------

function modalBusquedaDocumentoAPagar()
{
    var clienteId = $('#cboClientePago').val();

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
            setTimeout(function () {
                getDataTableDocumentoAPagar()
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

function obtenerDocumentoTipo()
{
    ax.setAccion("obtenerDocumentoTipo");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}
var selectPersonaId;
function onResponseObtenerDocumentoTipoDato(data, personaActiva) {
    selectPersonaId = 0;
    dataDocumentoTipoDato = data;
//    console.log(data);
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas

        var columna = 1;
        $.each(data, function (index, item) {
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
        });
        appendForm('</div>');
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
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
//    $('#idPopover').attr("data-content", "");
    var cadena;
    cadena = obtenerDatosBusqueda();
//    if (!isEmpty(cadena) && cadena !== 0)
//    {
//        $('#idPopover').attr("data-content", cadena);
//    }
//    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    setTimeout(function () {
        getDataTableDocumentoAPagar()
    }, 500);

//    if (colapsa === 1)
//        colapsarBuscadorDocumentoPagar();
}

function obtenerDatosBusqueda()
{
    var valorPersona;
    tipoDocumento = $('#cboDocumentoTipo').val();
    var cadena = "";
    cargarDatoDeBusqueda();
    var valorTipoDocumento = obtenerValorTipoDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDato, function (index, item) {


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
    dataDocumentoTipoDato[0]['tipoDocumento'] = tipoDocumento;
    return cadena;
}

function cargarDatoDeBusqueda()
{
    $.each(dataDocumentoTipoDato, function (index, item) {
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
function getDataTableDocumentoAPagar() {
    ax.setAccion("obtenerDocumentosAPagar");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);
    ax.addParamTmp("empresa_id", commonVars.empresa);
    $('#datatableModalDocumentoAPagar').DataTable().destroy();
    $('#datatableModalDocumentoAPagar').dataTable({
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
            {"data": "documento_tipo_descripcion", "width": "11%"},
            {"data": "persona_nombre_completo", "width": "29%"},
            {"data": "serie", "width": "5%"},
            {"data": "numero", "width": "6%"},
            {"data": "fecha_vencimiento", "class": "alignCenter", "width": "7%"},
            {"data": "dolares",
                render: function (data, type, row) {
                    if (row.dolares === "1")
                        return "Dolares";
                    return "Soles";
                },
                "width": "6%"
            },
            {"data": "pendiente", "class": "alignRight", "width": "6%"},
            {"data": "total", "class": "alignRight", "width": "8%"},
            {data: "codigo",
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
                "targets": [8, 9]
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();

    cambiarAnchoBusquedaDesplegable();
}

function isChecked(id) {
    return $("#" + id).is(":checked");
}

function agregarDocumentoPagoDataTable()
{

//    volverMostrarModalBusquedaDocumentoPagar = true;
    var rtotal = "0.00";
    $("#tfoo1").empty();
    $('#dgDocumentoPago').empty();
    var total = 0;
    var cuerpototal = '';
    var moneda = "S/.";
    $.each(documentoArray, function (index, item) {
        var cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.tipoDocumento + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.serie) + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.numero) + "</td>" +
                "<td style='text-align:left;'>" + item.descripcionRD + "</td>" +
                "<td style='text-align:right;'>" + item.pendiente.toFixed(2) + "</td>" +
                "<td style='text-align:right;'><input  id=\"txtPediente_" + item.documentoId + "\" name=\"txtPediente_" + item.documentoId + "\" onchange=\"actualizarPendienteDocumentoAPagar(" + item.documentoId + ");\" type='number' class='form-control rendered' value='" + item.pendiente.toFixed(2) + "'/></td>" +
                "<td style='text-align:center;'><a href='#' onclick='quitarDocumentoAPagar(" + item.documentoId + ")'><b><i class='ion-close' style='color:#cb2a2a;' tooltip-btndata-toggle='tooltip' title='Quitar'></i><b></a></td>" +
                "</td>" +
                "</tr>";
        cuerpototal = cuerpototal + cuerpo;
        total += item.pendiente * 1;
        moneda = item.dolares * 1 === 1 ? "$ " : moneda;
    });
    rtotal = total.toFixed(2);
    total = moneda + rtotal;
    $("#dgDocumentoPago").append(cuerpototal);
    $("#modalBusquedaDocumentoPagar").modal('hide');
    //agregar y ocultar el modal
    getTotalRendereds();
    if (accionagregarDocumentoPago == 1)
    {
        volverMostrarModalBusquedaDocumentoPagar = false;
    } else {
        volverMostrarModalBusquedaDocumentoPagar = true;
    }
    if (documentoArray.length > 0)
    {
        $("#tfoo1").append("<tr><td colspan='5'>Total</td><td id='fTotal'>" + total + "</td</tr>")
        $("#divLeyendaDocumentoPago").show();
    }
    loaderClose();
}

function actualizarPendienteDocumentoAPagar(documentoId) {

    var pendiente = $('#txtPediente_' + documentoId).val();
    var index = -1;
    $.each(documentoArray, function (i, item) {
        if (parseInt(item.documentoId) === parseInt(documentoId)) {
            index = i;
            return false;
        }
    });

    if (index > -1) {
        documentoArray[index].pendiente = pendiente;
    }

//    console.log(pendiente,documentoArray);
}

$(document).on("change", ".rendered", function () {
    var sum = getTotalRendereds();
    sum = getFormatoDocumentoAPagar() + sum;
    $("#fTotal").text(sum);
//    console.log(documentoArray);
});
var montoAPagarEfectivo = 0.00;
function getTotalRendereds() {
    var sum = 0;
    $(".rendered").each(function () {
        sum += $(this).val() * 1;
    });
//    var fsum = calcularDescuento(sum).toFixed(2);
    var fsum = sum.toFixed(2);
    efectivoActual = sum;
    montoAPagarEfectivo = fsum;

    actualizaMontoPagarFormulario();
    actualizaVueltoFormulario();

//    console.log(fsum);
//    $("#txtMontoAPagar").val(fsum);
    return sum.toFixed(2);
}

var montoPagoRetencion = 0;
function onResponseDocumentoAPagar(data)
{
    var objDocumentos = {
        documentoId: null,
        tipoDocumento: null,
        numero: null,
        serie: null,
        pendiente: null,
        dolares: null,
        total: null,
        tipoRD: null,
        descripcionRD: null,
        importeRetenido: null,
        tipo: null
    };
    objDocumentos.documentoId = data[0].documento_id;
    objDocumentos.tipoDocumento = data[0].documento_tipo;
    objDocumentos.numero = data[0].numero;
    objDocumentos.serie = data[0].serie;
    objDocumentos.pendiente = redondearNumero(data[0].pendiente);
    objDocumentos.total = data[0].total;
    objDocumentos.tipoRD = data[0].tipo_retencion_detraccion;
    objDocumentos.descripcionRD = data[0].tipo_rd_descripcion;
    objDocumentos.importeRetenido = data[0].porcentaje_retenido * data[0].total;
    objDocumentos.tipo = data[0].tipo;
    objDocumentos.dolares = data[0].mdolares;

    montoPagoRetencion = montoPagoRetencion + (data[0].total - data[0].porcentaje_retenido * data[0].total);

    documentoArray.push(objDocumentos);
    agregarDocumentoPagoDataTable();

    select2.asignarValor("cboClientePago", data[0].persona_id);
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');
    $("#cboClientePago").attr('disabled', 'disabled');

}

function verificarDocumentoPagoFueAgregado(data, documentoId)
{

    var bandera = false;

    $.each(data, function (index, item) {
        if (item.documentoId == documentoId)
        {
            bandera = true
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
    if (verificarDocumentoPagoFueAgregado(documentoArray, documentoId) == false && validarPaga(documentoId) == false)
    {
        if (!verificaDocumentoMoneda(documentoArray, dolares)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento posee un tipo moneda diferente al de los agregados");
            return false;
        }
        loaderShow("#modalBusquedaDocumentoPagar");
        ax.setAccion("obtenerDocumentoAPagar");
        ax.addParamTmp("documentoId", documentoId);
        ax.consumir();
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento ya fue agregado");
        loaderClose();
    }
}

function quitarDocumentoAPagar(documentoAPagarId)
{
    loaderShow(null);
//    console.log(documentoArray);
    $.each(documentoArray, function (index, value) {
//        console.log("quitar:" + value.documentoId);
        if (value.documentoId == documentoAPagarId)
        {
            documentoArray.splice(index, 1);
            return false;
        }
    });
    agregarDocumentoPagoDataTable();
    if (documentoArray.length == 0)
    {
        $("#divLeyendaDocumentoPago").hide();
        //habilita el combo de la persona
        $("#cboClientePago").removeAttr('disabled');
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


function obtenerDocumentoTipoDato(documentoTipoId) {
    ax.setAccion("obtenerDocumentoTipoDato");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}
function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

//Fin de sección de documento a pagar--------------------------------


//Sección de pago en efectivo y con documentos  


function modalBusquedaPagoConDocumento()
{
    var clienteId = $('#cboClientePago').val();

    if (clienteId == '-1')
        clienteId = 0;

    /*if (clienteId == '-1')
     {
     $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar un  cliente');
     } else*/
    {
        if (selectPersonaIdPagarConDocumento > 0) {
            select2.asignarValor('cbodp_' + selectPersonaIdPagarConDocumento, clienteId);
        }
        $('#modalBusquedaPagoConDocumento').modal('show');

        banderaAbrirModalPagoConDocumento = 1;
//        buscarDocumentoPagoConDocumento(1);

        if (clienteId != 0) {
            buscarDocumentoPagoConDocumento(1);
        } else {
            loaderShow("#modalBusquedaPagoConDocumento");
            setTimeout(function () {
                getDataTablePagoConDocumento()
            }, 500);
        }

    }
}

function cargarConfiguracionesModalBusquedaPagoConDocumento()
{
    $("#cboDocumentoTipoPagoConDocumento").select2({
        width: "100%"
    });
}

function obtenerDocumentoTipoPagoConDocumento()
{
    ax.setAccion("obtenerDocumentoTipoPagoConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerDocumentoTipoPagoConDocumento(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipoPagoConDocumento", data.documento_tipo, "id", "descripcion");

        if (data.documento_tipo.length === 1) {
            select2.asignarValor("cboDocumentoTipoPagoConDocumento", data.documento_tipo[0].id);
            select2.readonly("cboDocumentoTipoPagoConDocumento", true);
            $('#divTipoDocumentoPagoConDocumento').hide();
        }

        onResponseObtenerDocumentoTipoDatoPagoConDocumento(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoListaPagoConDocumento(data.documento_tipo_dato_lista);
    }
}

function onResponseObtenerDocumentoTipoDatoPagoConDocumento(data, personaActiva) {
    selectPersonaIdPagarConDocumento = 0;
    dataDocumentoTipoDatoPagoConDocumento = data;
    $("#formularioDocumentoTipoPagoConDocumento").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas
        var columna = 1;
        $.each(data, function (index, item) {
//            console.log(columna);
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
                        selectPersonaIdPagarConDocumento = item.id;
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
    }
}

function onResponseCargarDocumentotipoDatoListaPagoConDocumento(dataValor)
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
        buscarDocumentoPagoConDocumento();
    }
//    loaderClose();
}

function buscarDocumentoPagoConDocumento(colapsa)
{
    loaderShow("#modalBusquedaPagoConDocumento");
//        $('#idPopover2').attr("data-content", "");
    var cadena;
    cadena = obtenerDatosBusquedaPagoConDocumento();
//    cadena = obtenerDatosBusqueda();
//    if (!isEmpty(cadena) && cadena !== 0)
//    {
//        $('#idPopover2').attr("data-content", cadena);
//    }
//    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    setTimeout(function () {
        getDataTablePagoConDocumento()
    }, 500);

//    if (colapsa === 1)
//        colapsarBuscadorPagoConDocumento();
}

function getDataTablePagoConDocumento() {
    ax.setAccion("obtenerDocumentosPagoConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("criterios", dataDocumentoTipoDatoPagoConDocumento);
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
//            {"data": "serie"},
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
    if (verificarDocumentoPagoFueAgregado(pagoConDocumentoArray, documentoId) == false && validarPagaConDocumento(documentoId) == false)
    {
        var tmoneda = $("#monedaId").val() * 1 + moneda * 1;
        if (tmoneda > 2 && tmoneda < 5) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La moneda especificada para los pagos no coincide con la moneda del documento");
            return false;
        }
        if (!verificaDocumentoMoneda(pagoConDocumentoArray, moneda)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento posee un tipo moneda diferente al de los agregados");
            return false;
        }
        ax.setAccion("obtenerDocumentoPagoConDocumento");
        ax.addParamTmp("documentoId", documentoId);
        ax.consumir();
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento ya fue agregado");
    }
}
function onResponseDocumentoPagoConDocumento(data)
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
    pagoConDocumentoArray.push(objDocumentosPagoConDocumento);
    agregarDocumentoPagoConDocumentoDataTable();
    $("#monedaId").prop("disabled", true);

    if (banderaGuardarDoc === 0) {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');
    }
    banderaGuardarDoc = 0;
}

function loaderBuscarDocumentoPagoConDocumento()
{
    actualizandoBusquedaPagoConDocumento = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarDocumentoPagoConDocumento();
    }
}

function agregarDocumentoPagoConDocumentoDataTable()
{
    $("#tfoo2").empty();
    $('#dgDocumentoPagoConDocumento').empty();
    var cuerpototal = '';
    var total = 0;
    var moneda = "S/.";
    $.each(pagoConDocumentoArray, function (index, item) {
        var imprimir = "";
        if (item.tipoDocumentoId * 1 == 18 || item.tipoDocumentoId * 1 == 45) {
            imprimir = "<a href='#' onclick='imprimirDocumento(" + item.documentoId + ", " + item.tipoDocumentoId + ")'><b><i class='fa fa-print' style='margin-right: 5px;;' tooltip-btndata-toggle='tooltip' title='Imprimir'></i><b></a>";
        }
        var cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.tipoDocumento + "</td>" +
//                "<td style='text-align:center;'>" + quitarNULL(item.serie) + "</td>" +
                "<td style='text-align:center;'>" + quitarNULL(item.numero) + "</td>" +
                "<td style='text-align:right;'>" + item.moneda + "</td>" +
                "<td style='text-align:right;'>" + item.total.toFixed(2) + "</td>" +
                "<td style='text-align:right;'>" + item.monto.toFixed(2) + "</td>" +
                "<td style='text-align:center;'>" + imprimir +
                "<a href='#' onclick='quitarDocumentoPagoConDocumento(" + item.documentoId + ")'><b><i class='ion-close' style='color:#cb2a2a;' tooltip-btndata-toggle='tooltip' title='Quitar'></i><b></a></td>" +
                "</td>" +
                "</tr>";
        cuerpototal = cuerpototal + cuerpo;
        total += item.monto * 1;
        moneda = item.dolares * 1 === 1 ? "$ " : moneda;
    });
    var rtotal = total.toFixed(2);
    total = moneda + rtotal;
    $("#dgDocumentoPagoConDocumento").append(cuerpototal);

    $('#modalBusquedaPagoConDocumento').modal('hide');

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
    if (pagoConDocumentoArray.length > 0)
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
    $.each(pagoConDocumentoArray, function (index, item) {
        if (item.documentoId == documentoId)
        {
            pagoConDocumentoArray.splice(index, 1);
            return false;
        }
    });
    agregarDocumentoPagoConDocumentoDataTable();
    if (pagoConDocumentoArray.length == 0)
    {
        $("#monedaId").prop("disabled", false);
        $("#divLeyendaDocumentoDePago").hide();
    }
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento eliminado de la lista');
}

function obtenerDatosBusquedaPagoConDocumento()
{
    var valorPersona;
    tipoDocumentoConDocumento = $('#cboDocumentoTipoPagoConDocumento').val();
    var cadena = "";
    cargarDatoDeBusquedaPagoConDocumento();
    var valorTipoDocumento = obtenerValorTipoDocumentoPagoConDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDatoPagoConDocumento, function (index, item) {


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
    dataDocumentoTipoDatoPagoConDocumento[0]['tipoDocumento'] = tipoDocumentoConDocumento;
    return cadena;
}


function cargarDatoDeBusquedaPagoConDocumento()
{
    $.each(dataDocumentoTipoDatoPagoConDocumento, function (index, item) {
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
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipoPagoConDocumento');
    if (valorTipoDocumento !== null)
    {
        var cadena = negrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";
}

//Fin de sección de pago en efectivo y con documentos


//inicio de sección modal nuevo documento pago con documento

function modalNuevoDocumentoPagoConDocumentoCobranza()
{

    obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento();
    var clienteId = $('#cboClientePago').val();
    if (personaNuevoId > 0 && clienteId > 0)
    {
        select2.asignarValor('cbo_' + personaNuevoId, clienteId);
    }
    desHabilitarBotonGeneral("btnNuevoC");

//    $('#modal-14').modal('show');
//    $('#modalNuevoDocumentoPagoConDocumento').modal('show');
}
function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento() {
    ax.setAccion("obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

var dataConfigInicialDocPago;
function onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumento(data) {
    dataConfigInicialDocPago = data;
    if (!isEmpty(data.documento_tipo)) {
        $("#cboDocumentoTipoNuevoPagoConDocumento").select2({
            width: "100%"
        }).on("change", function (e) {
            loaderShow("#modalNuevoDocumentoPagoConDocumento");
            obtenerDocumentoTipoDato(e.val);
        });
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumento", data.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", data.documento_tipo[0].id);
        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumento", true);
        }
        onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data.documento_tipo_conf);

        select2.cargar("cboPeriodo", data.periodo, "id", ["anio", "mes"]);
        select2.asignarValorQuitarBuscador('cboPeriodo', null);
    }
}

var camposDinamicos = [];
var personaNuevoId;
function onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data) {

    dataConfigInicialDocPago.documento_tipo_conf = data;

    camposDinamicos = [];
    personaNuevoId = 0;
    var tipo_moneda = $("#monedaId").val() === "4" ? " (Dolares)" : "";
    $("#span_moneda").text(tipo_moneda);
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

                case 9, "9":
                    $('#datepickernd_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    }).on('changeDate', function (ev) {
                        cambiarPeriodo();
                    });
//                    $('#datepickernd_' + item.id).datepicker('setDate', item.data);                    
                    setTimeout(function () {
                        cambiarPeriodo();
                    }, 300);
                    break;
            }
        });
        var clienteId = $('#cboClientePago').val();
        if (personaNuevoId > 0 && clienteId > 0)
        {
            select2.asignarValor('cbond_' + personaNuevoId, clienteId);
            if (documentoArray.length > 0) {
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
    habilitarBotonGeneral("btnNuevoC");
    $('#modalNuevoDocumentoPagoConDocumento').modal('show');
    loaderClose("#modalNuevoDocumentoPagoConDocumento");

}

function appendFormNuevo(html) {
    $("#formNuevoDocumentoPagoConDocumento").append(html);
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
    loaderShow();
    var montoAPagar = $('#txtMontoAPagar').val();
    var tipoCambio = $('#tipoCambio').val();
    var monedaPago = $("#monedaId").val();
    var pagarCon = $('#txtPagaCon').val();
    var vuelto = $('#txtVuelto').val();
    var cliente = $('#cboClientePago').val();
    var fecha = $('#datepicker_fechaPago').val();
    var documentoAPagar = documentoArray;
    var documentoPagoConDocumento = pagoConDocumentoArray;

    var actividadEfectivo = $('#cboActividadEfectivo').val();

    if (validarDocumentoDiferenciaImportes()) {
        loaderClose();
        return;
    }

    deshabilitarBotonSweet();

    ax.setAccion("registrarPago");
    ax.addParamTmp("cliente", cliente);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("montoAPagar", montoAPagar);
    ax.addParamTmp("monedaPago", monedaPago);
    ax.addParamTmp("tipoCambio", tipoCambio);
//    ax.addParamTmp("retencion", GetRetencion());
    ax.addParamTmp("vuelto", vuelto);
    ax.addParamTmp("documentoAPagar", documentoAPagar);
    ax.addParamTmp("documentoPagoConDocumento", documentoPagoConDocumento);
//    ax.addParamTmp("tipoRD", tipoRD);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("actividadEfectivo", actividadEfectivo);
    ax.consumir();

}

function validarDocumentoDiferenciaImportes() {

    var banderaTipoDoc = false;
    $.each(pagoConDocumentoArray, function (i, itemPago) {
        if (itemPago.tipoDocumentoId == 236 || itemPago.tipoDocumentoId == 237) {
            banderaTipoDoc = true;
        }
    });

    var bandera = false;
    if (banderaTipoDoc) {
        $.each(documentoArray, function (i, item) {
            if (parseFloat(item.pendiente) > 10) {
                bandera = true;
                var mensaje = 'Se seleccionó un documento de pago: "Diferencia de importes". Y el importe del documento "' + item.serie + ' - ' + item.numero + '" supera ' + getFormatoPagoActual() + ' 10. Para registrar el pago seleccione/registre otro tipo de documento de pago.';
                swal("Validación!", mensaje, "warning");
            }
        });
    }

    return bandera;
}

function confirmarRegistrarPago()
{
    if (validarCamposDeEntradaPago() && validarDocumentosPago())
    {
        loaderShow();
        ax.setAccion("validarSiTieneDocumentoRetencionDetraccion");
        ax.addParamTmp("documentoAPagar", documentoArray);
        ax.consumir();
        //SweetConfirmarRegistrarPago();
    }
}

function onResponseValidarSiTieneDocumentoRetencionDetraccion(data) {
//    console.log(data);
    mensajeRetencionDetraccion = "";
    if (data == 1) {// no tiene documento de retencion/detraccion

        var contadorRetencionAPagar = 0;
        var contadorDetraccionAPagar = 0;

        $.each(documentoArray, function (i, item) {

            if (item.descripcionRD.indexOf("Retención") > -1) {
                contadorRetencionAPagar++;
            }
            if (item.descripcionRD.indexOf("Detracción") > -1) {
                contadorDetraccionAPagar++;
            }

        });


        var contadorRetencionPago = 0;
        var contadorDetraccionPago = 0;

        $.each(pagoConDocumentoArray, function (i, item) {

            if (item.tipoDocumento.indexOf("Retención") > -1) {
                contadorRetencionPago++;
            }
            if (item.tipoDocumento.indexOf("Detracción") > -1) {
                contadorDetraccionPago++;
            }

        });

//        console.log(contadorRetencionAPagar,contadorRetencionPago,contadorDetraccionAPagar,contadorDetraccionPago);
        if ((contadorRetencionAPagar != 0 && contadorRetencionAPagar != contadorRetencionPago) || (contadorDetraccionAPagar != 0 && contadorDetraccionAPagar != contadorDetraccionPago)) {
            mensajeRetencionDetraccion = " Algun(os) documento(s) a pagar no tienen comprobantes de pago.\n\n ";
        }
//        else{
//            SweetConfirmarRegistrarPago();
//        }


    }

    SweetConfirmarRegistrarPago();
}

function validarDocumentosPago()
{
    var valid = true;
    var tipo = "";
    var obj = $("#retSelect");
    $.each(pagoConDocumentoArray, function (index, item) {
        if (!obj.is(":disabled")) {
            if (obj.val() * 1 == 1 && item.tipoDocumentoId * 1 == 97) {
                tipo = "detracción";
                valid = false;
            } else if (obj.val() * 1 == 2 && item.tipoDocumentoId * 1 == 96) {
                tipo = "retención";
                valid = false;
            }
        }
    })
    if (!valid) {
        var opcion = obj.val() * 1 == 1 ? "RETENCION" : "DETRACCION";
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'No puede pagar con documentos de ' + tipo + ' cuando esta habilitada la opción de ' + opcion);
    }
    return valid;
}

function validarCamposDeEntradaPago()
{
    var bandera = true;
    var cliente = $('#cboClientePago').val();
    var fecha = $('#datepicker_fechaPago').val();
    var documentoAPagar = documentoArray;
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
    actualizaVueltoFormulario();
});
$('#txtMontoAPagar').keyup(function () {
    actualizaVueltoFormulario();
});
$('#tipoCambio').keyup(function () {
    actualizaMontoPagarFormulario();
    actualizaVueltoFormulario();
});
$('#monedaId').change(function () {
    actualizaMontoPagarFormulario();
    actualizaVueltoFormulario();
});

function actualizaVueltoFormulario() {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
}

function actualizaMontoPagarFormulario() {
    var checked = document.getElementById('checkPE').checked;
    if (checked) {
        $("#txtMontoAPagar").val(devolverImporteConvertido(montoAPagarEfectivo));
    }
}

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
        $('#modalNuevoDocumentoPagoConDocumento').modal('hide');

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
            $('#modalNuevoDocumentoPagoConDocumento').modal('show');
        });
        return;
    }

    guardarDocumento();
}

var banderaGuardarDoc = 0;
function guardarDocumento() {
    deshabilitarBoton();
    banderaGuardarDoc = 0;
    loaderShow("#modalNuevoDocumentoPagoConDocumento");
    varGuardarDocumento = true;
//obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
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
    loaderShow("#modalNuevoDocumentoPagoConDocumento");
    var documneto_tipo = document.getElementById('cboDocumentoTipoNuevoPagoConDocumento').value;
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
    $.each(pagoConDocumentoArray, function (index, item) {
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
    $.each(documentoArray, function (index, item) {
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
        totalPago = totalPago + parseFloat(item.pendiente);//item.pendiente
    });
    return totalPago;
}

var mensajeRetencionDetraccion = "";
function SweetConfirmarRegistrarPago() {
    var actividadEfectivo = $('#cboActividadEfectivo').val();

    var checked = isChecked("checkPE");
    if (checked && actividadEfectivo == -1) {
        mostrarValidacionLoaderClose("Debe seleccionar actividad.");
        return;
    }

    var vuelto = $('#txtVuelto').val();
    if (checked && (parseFloat(vuelto) < 0 || isEmpty(vuelto))) {
        mostrarValidacionLoaderClose("El vuelto no puede ser negativo.");
        return;
    }

    var pagoEfectivo = formatearNumero($('#txtMontoAPagar').val());
    var pagoDocumento = formatearNumero(obtenerTotalListaDeDocumentos(pagoConDocumentoArray));

    //para mostrar mensaje que el pago > al monto a pagar
    var pagoDescripcion = '';
    var aPagar = obtenerTotalListaDeDocumentos(documentoArray);
    var aPagarConv = aPagar;
    if (getFormatoPagoActual() != getFormatoDocumentoAPagar()) {
        aPagarConv = devolverImporteConvertido(aPagar) * 1;
    }
    var pago = $('#txtMontoAPagar').val() * 1 + obtenerTotalListaDeDocumentos(pagoConDocumentoArray) * 1;

    if (pago > aPagarConv) {
        pagoDescripcion = '<p style="color: #d20e0e;display: block;">El total de pago es mayor al total del documento a pagar. '
                + '<br>En: ' + getFormatoPagoActual() + formatearNumero(pago - aPagarConv)
                + '</p> <br>';
    }

    swal({
        title: "Est\xe1s seguro?",
        text: mensajeRetencionDetraccion +
                pagoDescripcion +
                "Total de documento a pagar: " + getFormatoDocumentoAPagar() + formatearNumero(obtenerTotalListaDeDocumentos(documentoArray)) + "<br>\
               Pago en efectivo: " + getFormatoPagoActual() + pagoEfectivo + "<br>\
               Pago con documento: " + getFormatoPagoActual() + pagoDocumento + "<br>",
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
function AplicaRetencion() {
    var obj = $("#retSelect");
    if (obj.is(":disabled")) {
        return "";
    }
    if (obj.val() == 1) {
        return "RETENCIÓN: 3% \n";
    }
    return "DETRACCIÓN: 10% \n";

}

function GetRetencion() {
    var tipo = $("#retSelect").val();
    if (tipo == 1) {
        return 0.97;
    }
    if (tipo == 2) {
        return 0.90;
    }
    return 1;
}

function HabilitarBotonSweet(data)
{
    $(".confirm").removeProp("disabled");

    $(".cancel").removeProp("disabled");

    if (isEmpty(data.error)) {
        $('#dgDocumentoPago').empty();
        $('#dgDocumentoPagoConDocumento').empty();
        documentoArray.length = 0;
        pagoConDocumentoArray.length = 0;
        $('#txtMontoAPagar').val('0.00');
        $('#txtPagaCon').val('0.00');
        $('#txtVuelto').val('0.00');
        $('#divLeyendaDocumentoDePago').empty();
        $('#divLeyendaDocumentoPago').empty();
        $(".foo").empty();
        $("#fTotal").text('');
        $("#tfoo1").empty();
        $("#tfoo2").empty();
        $("#monedaId").prop("disabled", false);
        $("#cboClientePago").removeAttr('disabled');
        select2.asignarValor('cboClientePago', -1);
        $("#checkPE").prop("checked", "");
        if (!isEmpty(data.indicador) && data.indicador==1 && isEmpty(data.mensaje)) {
            swal("Correcto!", "Operacion exitosa" , "success");
        }else if ((!isEmpty(data.indicador) && data.indicador==1) && !isEmpty(data.mensaje)) {
            swal("Correcto!", "Operacion exitosa, pago y NC generados exitosamente" +
                    data.mensaje, "success");
        } else if ((!isEmpty(data.indicador) && data.indicador==2) && !isEmpty(data.mensaje)){
            swal("Correcto!", "Operacion exitosa. Pago generado exitosamente" +
                    data.mensaje, "success");
        }

    } else {
        swal("Validación!", data.mensaje, "warning");
    }
}

function deshabilitarBotonSweet()
{
    $(".confirm").prop("disabled", "true");

    $(".cancel").prop("disabled", "true");

    $(".confirm").html('<i id="spin"  class="fa fa-spin fa-spinner" ></i><istyle="font-style: normal;font-size:14px;">Procesando...</i>');

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
//$("#checkBP").click(function(){
//    var checked = $(this).is(":checked");
//    var tipo  = $("#retSelect").val();
//    var monto = calcularDescuento(efectivoActual);
//    if(!checked){
//        
//        $("#txtMontoAPagar").val(monto.toFixed(2));
//        $("#retSelect").prop("disabled",true);
//        return;
//    }
//    $("#txtMontoAPagar").val(monto.toFixed(2));
//    $("#retSelect").prop("disabled",false);
//    return;
//});
function calcularDescuento(monto) {
    var checked = isChecked("checkBP");
    var tipo = $("#retSelect").val();
    if (checked) {
        if (tipo == 1)
            monto *= 0.97;
        else
            monto *= 0.90;
        return monto;
    }
    return monto;

}

$("#checkPE").click(function () {
    var checked = $(this).is(":checked");
    if (!checked) {
        $("#txtMontoAPagar").prop("disabled", true);
        $("#txtPagaCon").prop("disabled", true);
        $("#loaderPagoEfectivo").show();
        $("#txtMontoAPagar").val('0.00');
        $("#txtPagaCon").val('0.00');
        $("#txtVuelto").val('0.00');

        select2.asignarValor('cboActividadEfectivo', actividadDefecto);
        $("#cboActividadEfectivo").attr('disabled', 'disabled');
        return true;
    }
    $("#loaderPagoEfectivo").hide();
    $("#txtMontoAPagar").prop("disabled", false);
//    $("#txtMontoAPagar").val(montoAPagarEfectivo);
    $("#txtMontoAPagar").val(devolverImporteConvertido(montoAPagarEfectivo));
    $("#txtPagaCon").prop("disabled", false);
    $("#cboActividadEfectivo").removeAttr('disabled');
    $("#txtPagaCon").val(devolverImporteConvertido(montoAPagarEfectivo));
    actualizaVueltoFormulario();
    return true;
});


function calcularMontoDescuento(  ) {
    var monto = calcularDescuento(efectivoActual);
    $("#txtMontoAPagar").val(monto.toFixed(2));
}

// parte para cobranza en dolares

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

var tc = -1;
var fc = "";
function obtenerTipoCambioHoy(fecha) {
    //var fecha = obtenerFechaActual();
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXfecha");
        if (isEmpty(fecha)) {
            fecha = datex.getNow1();
        }
        ax.addParam("fecha", fecha);
        ax.consumir();
        fc = fecha;
    } else {
        actualizaMontoPagarFormulario();
        actualizaVueltoFormulario();
    }


    //alert(fecha);
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
    var a = documentoArray.length;
    var b = pagoConDocumentoArray.length;
    var tipoCambio = $('#tipoCambio').val();
    var checkTipoCambio = $("#checkBP").is(":checked");
//    console.log(tipoCambio,checkTipoCambio);

    if (a * b > 0 && tc < 0) {
        var d1 = documentoArray[0].dolares * 1;
        var d2 = pagoConDocumentoArray[0].dolares * 1;
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

function getFormatoDocumentoAPagar() {
    return (documentoArray[0].dolares) * 1 === 1 ? "$ " : "S/.";
}

$('.dropdown-menu').click(function (e) {
//        console.log(e);
    if (e.target.id != "btnBusqueda"
            && e.delegateTarget.id != "ulBuscadorDesplegable2"
            && e.delegateTarget.id != "listaEmpresa"
            && e.delegateTarget.id != "ulBuscadorDesplegablePagoConDocumento2") {
        e.stopPropagation();
    }
});

function buscarCriteriosBusquedaDocumentoPagar() {
//    loaderShow();
    //buscarDocumentoPago();
    ax.setAccion("buscarCriteriosBusquedaDocumentoPagar");
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
    setCriterio(dataDocumentoTipoDato, {
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
    setCriterio(dataDocumentoTipoDato, {
        tipo: 7,
        descripcion: "Serie",
        id: 13,
        opcional: 0,
        orden: 1,
        valor: serie
    });
    setCriterio(dataDocumentoTipoDato, {
        tipo: 8,
        descripcion: "Número",
        id: 14,
        opcion: 0,
        orden: 2,
        valor: numero
    });
    getDataTableDocumentoAPagar();
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
    ax.setAccion("buscarCriteriosBusquedaPagoConDocumento");
    ax.addParamTmp("busqueda", $('#txtBuscarPagoConDocumento').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaPagoConDocumento(data) {
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

    setCriterio(dataDocumentoTipoDatoPagoConDocumento, {
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
    setCriterio(dataDocumentoTipoDatoPagoConDocumento, {
        tipo: 5,
        descripcion: "Persona",
        id: 114,
        opcional: 0,
        orden: 1,
        valor: personaId
    });
    setCriterio(dataDocumentoTipoDatoPagoConDocumento, {
        tipo: 8,
        descripcion: "Número",
        id: 115,
        opcional: 0,
        orden: 2,
        valor: numero
    });
    getDataTablePagoConDocumento();
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

function devolverImporteConvertido(importe) {
    var tipoCambio = $('#tipoCambio').val();
    if (isEmpty(documentoArray) || isEmpty(tipoCambio) || tipoCambio == '' || isEmpty(importe)) {
        return 0;
    }
    var monedaId = $("#monedaId").val();

//    console.log(importe,tipoCambio);

    var factor = 1;
    if (documentoArray[0].dolares * 1 == 0 && monedaId == 4) {
        factor = tipoCambio;
    } else if (documentoArray[0].dolares * 1 == 1 && monedaId == 2) {
        factor = 1 / tipoCambio;
    }

    var importeConvertido = importe / factor;
//    importeConvertido = importeConvertido.toFixed(2);//falta redondeo superior.

    var importe_10 = importeConvertido * 10;
    var importe_10_ceil = Math.ceil(importe_10);
    importeConvertido = importe_10_ceil / 10;

    return importeConvertido.toFixed(2);
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