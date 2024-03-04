var dataDocumentoTipoDatoPago;
var banderaBuscarPago = 0;
var estadoTolltipPago = 0;
var bandera_anularPagoPago = false;
var bandera_anularPagoPagoPago = false;

$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponsePagoListar");
    obtenerDocumentoTipo();
    cambiarAnchoBusquedaDesplegable();
});

function nuevoPagoForm()
{
    VALOR_ID_USUARIO = null;
    cargarDiv('#window', 'vistas/com/pago/cobranza.php');
}
function obtenerDocumentoTipo()
{
    ax.setAccion("obtenerDocumentoTipoPagoConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponsePagoListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipoPagoConDocumento':
                onResponseObtenerDocumentoTipo(response.data);
                buscarPago(1);
//                colapsarBuscador();
                loaderClose();
                break;
            case 'anularDocumentoPago':
                loaderClose();
//                if(response.data['0'].vout_exito ==2)
//                {
//                    swal("Cancelado", " " + response.data['0'].vout_mensaje, "error");
//                }else
//                {
                swal("Anulado!", "Documento de pago anulado correctamente.", "success");
                bandera_anularPago = true;
                buscarPago();
//                }

                break;
            case 'eliminarDocumentoDePago':
                loaderClose();
                if (response.data[0].vout_exito == 1)
                {
                    swal("Eliminado!", response.data[0].vout_mensaje, "success");
                    buscarPago();
                } else
                {
                    swal("Cancelado", response.data[0].vout_mensaje, "error");
                }
                bandera_eliminarPago = true;
                break;

            case 'visualizarPago':
                onResponseVisualizarDocumentoPago(response.data);
                loaderClose();
                break;
            case 'buscarCriteriosBusquedaDocumentoPagoListar':
                onResponseBuscarCriteriosBusquedaDocumentoPagoListar(response.data);
                loaderClose();
                break;
            case 'imprimir':
                loaderClose();
                cargarDatosImprimir(response.data);
                break;
            case 'getUserEmailByUserId':
                onResponseGetUserEmailByUserId(response.data);
                loaderClose();
                break;
        }
    }
    else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'imprimir':
                loaderClose();
                break;
            case 'anularDocumentoPago':
                loaderClose();
                swal("Cancelado", "No se puede anular el documento, ya fue utilizado", "error");
                break;
            case 'visualizarDocumento':
                loaderClose();
                break;
        }
    }
}

function onResponseObtenerDocumentoTipo(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
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

function onResponseObtenerDocumentoTipoDato(data, personaActiva) {
    dataDocumentoTipoDatoPago = data;
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas

        var columna = 1;
        $.each(data, function (index, item) {
            if (item.tipo != 12) {
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
                        html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="" maxlength="8"/>';
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
    }
}
function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

function buscarPago(colapsa)
{
    loaderShow(null);
    var cadena;
    cadena = obtenerDatosBusquedaPago();
    banderaBuscar = 1;
    getPagoDataTable();
}
var actualizandoBusqueda = false;
function actualizarBusqueda()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPago(0);
    }
}
function colapsarBuscador() {
    if (actualizandoBusqueda) {
        actualizandoBusqueda = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}

function getPagoDataTable() {
    ax.setAccion("obtenerDocumentosPagoListarConDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("criterios", dataDocumentoTipoDatoPago);
    $('#datatableListaPagos').dataTable({
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
        "autoWidth": true,
        "order": [[0, "desc"]],
        "columns": [
            {"data": "codigo","width": '50px'},
            {"data": "fecha_emision","width": '60px'},
            {"data": "documento_tipo_descripcion","width": '120px'},
            {"data": "persona_nombre_completo","width": '220px'},
//            {"data": "serie"},
            {"data": "numero","width": '50px'},
//            {"data": "fecha_vencimiento"},
            {"data": "monto", "class": "alignRight","width": '60px'},
            {"data": "total", "class": "alignRight","width": '60px'},
            {"data": "moneda_descripcion", "class": "alignLeft","width": '50px'},
            {"data": "documento_estado_descripcion","width": '50px'},
            {"data": "acciones", "class": "alignCenter","width": '80px'}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [5, 6]
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 1
            }
        ],
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true
    });
    loaderClose();
}
function obtenerDatosBusquedaPago()
{
    var valorPersona;

    tipoDocumentoPago = $('#cboDocumentoTipo').val();
    var cadena = "";
//    if (!isEmpty(tipoDocumento))
//    {

    cargarDatoDeBusquedaPago();
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
                    cadena += StringNegrita(item.descripcion) + ": ";
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
                    cadena += StringNegrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:

                if (!isEmpty(item.valor))
                {
                    if (select2.obtenerText('cbo_' + item.id) !== null)
                    {
                        cadena += StringNegrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbo_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0)
                {
                    cadena += StringNegrita(item.descripcion) + ": ";
                    valorPersona = select2.obtenerText('cbo_' + item.id);
                    cadena += valorPersona;
                    cadena += "<br>";
                }
                break;
        }
    });
    dataDocumentoTipoDatoPago[0]['tipoDocumento'] = tipoDocumentoPago;
    return cadena;
//    }
//    return 0;
}

function obtenerValorTipoDocumento()
{
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipo');
    if (valorTipoDocumento !== null)
    {
        var cadena = StringNegrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";


}

function cargarDatoDeBusquedaPago()
{
    $.each(dataDocumentoTipoDatoPago, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 1:
            case 14:
                item["valor"] = $('#txt_' + item.id).val();
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
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
function anularPago(id)
{
    confirmarAnularPago(id);
}

function confirmarAnularPago(id) {
    bandera_anularPago = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Anular un documento de pago, esta anulación no podra revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, anular!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            anularDocumento(id);
        } else {
            if (bandera_anularPago == false)
            {
                swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function anularDocumento(id)
{
    loaderShow();
    ax.setAccion("anularDocumentoPago");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function eliminarPago(id)
{
    confirmarEliminarPago(id);
}
function confirmarEliminarPago(id) {
    bandera_eliminarPago = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás un documento de pago",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarDocumentoPago(id);
        } else {
            if (bandera_eliminarPago == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function eliminarDocumentoPago(id)
{
    loaderShow();
    ax.setAccion("eliminarDocumentoDePago");
    ax.addParamTmp("documentoPago", id);
    ax.consumir();
}


function cerrarPopover()
{
    if (banderaBuscar == 1)
    {
        if (estadoTolltipPago == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        }
        else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else
    {
        $('[data-toggle="popover"]').popover('hide');
    }
    estadoTolltipPago = (estadoTolltipPago == 0) ? 1 : 0;
}

var documentoPagoId;
function visualizarDocumentoPago(documentoId, movimientoId)
{
    documentoPagoId = documentoId;

    $('#txtCorreo').val('');
    loaderShow();
    ax.setAccion("visualizarPago");
    ax.addParamTmp("documento_id", documentoId);
    ax.consumir();

//    
}
function onResponseVisualizarDocumentoPago(data)
{
    if (!isEmpty(data)) {
        $('[data-toggle="popover"]').popover('hide');
        $('#modalDetalleDocumentoPago').modal('show'); 
        setTimeout(function(){  cargarDetalleDocumentoPago(data) }, 300);
    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "No hay detalle");
    }
}

function cargarDetalleDocumentoPago(data) {

    if (!isEmptyData(data))
    {
        var stringTitulo = '<strong> ' + data[0]['serie_pago'] + ' | ' + data[0]['documento_tipo_descripcion_pago'] + ' - ' + data[0]['numero_pago'] + '</strong>';
        
        $('#datatableDocumentoPago').dataTable({
//            "scrollX":datatable2 true,
            "order": [[0, "desc"]],
            "data": data,
            "scrollX": true,
//            "autoWidth": false,
            "columns": [
                {"data": "descripcion"},
                {"data": "fecha_emision", "sClass": "alignCenter"},
                {"data": "fecha_vencimiento", "sClass": "alignCenter"},
                {"data": "serie_numero"},
                {"data": "pago_fecha", "sClass": "alignCenter"},
                {"data": "total", "sClass": "alignRight"},
                {"data": "moneda_descripcion", "sClass": "alignLeft"},
                {"data": "importe", "sClass": "alignRight"},
                {"data": "moneda_pago_descripcion", "sClass": "alignLeft"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [5, 7]
                },
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : datex.parserFecha(data.replace(" 00:00:00", ""));
                    },
                    "targets": [1, 2, 4]
                }
            ],
            "destroy": true
        });        
        
        $('.modal-title').empty();               
        $('.modal-title').append(stringTitulo);
    }
    else
    {
        var table = $('#datatableDocumentoPago').DataTable();
        table.clear().draw();
    }
}

//function anularDocumento(id)
//{
//    confirmarAnularMovimiento(id);
//}

function enviarCorreoDocumentoPago() {
    var correo = $('#txtCorreo').val();
    correo = fixCorreo(correo);
    $('#txtCorreo').val(correo);
    var arr = correo.split(";");
    var valid = true;
    var boo = false;
    for (var i = 0; i < arr.length - 1; i++)
    {
        boo = validarEmail(arr[i]);
        if (boo)
        {
            console.log("VALIDO: " + arr[i]);
            valid = valid && boo;
        }
        else {
            console.log("INVALIDO: " + arr[i]);
            valid = valid && boo;
        }
    }


    if (!isEmpty(correo) && valid) {
        loaderShow('#modalDetalleDocumentoPago');
        ax.setAccion("enviarCorreoDocumentoPago");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("documento_id", documentoPagoId);
        ax.addParamTmp("tipoCobroPago", 1);//1->cobro, 2 -> pago
        ax.consumir();
    }
    else
    {
        mostrarAdvertencia("Ingrese email válido.");
        return;
    }


}

//here
$('.dropdown-menu').click(function (e) {
//        console.log(e);
    if (e.target.id != "btnBusqueda"
            && e.delegateTarget.id != "ulBuscadorDesplegable2"
            && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

$('#txtBuscar').keyup(function (e) {
    var bAbierto = $(this).attr("aria-expanded");

    if (!eval(bAbierto)) {
        $(this).dropdown('toggle');
    }
});

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

function buscarCriteriosBusquedaDocumentoPagoListar() {
//    loaderShow();
    //buscarDocumentoPago();
    ax.setAccion("buscarCriteriosBusquedaDocumentoPagoListar");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaDocumentoPagoListar(response) {
    var dataPersona = response.dataPersona;
    var dataDocumentoTipo = response.dataDocumentoTipo;
    var dataSerieNumero = response.dataSerieNumero;

    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >'
                    + '<span class="col-md-1"><i class="ion-person"></i></span>'
                    + '<span class="col-md-2">'
                    + '<label style="color: #141719">' + item.codigo_identificacion + '</label>'
                    + '</span>'
                    + '<span class="col-md-9">'
                    + '<label style="color: #141719">' + item.nombre + '</label>'
                    + '</span></a>'
                    + '</div>';
        });
    }
    if (!isEmpty(dataDocumentoTipo)) {
        $.each(dataDocumentoTipo, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" >'
                    + '<span class="col-md-1"><i class="fa fa-files-o"></i></span>'
                    + '<span class="col-md-11">'
                    + '<label style="color: #141719">' + item.descripcion + '</label>'
                    + '</span></a>'
                    + '</div>';
        });
    }
    if (!isEmpty(dataSerieNumero)) {
        $.each(dataSerieNumero, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorSerieNumero(\'' + item.serie + '\',\'' + item.numero + '\')" >'
                    + '<span class="col-md-1"><i class="ion-document"></i></span>'
                    + '<span class="col-md-2">'
                    + '<label style="color: #141719">' + item.serie_numero + '</label>'
                    + '</span>'
                    + '<span class="col-md-9">'
                    + '<label style="color: #141719">' + item.documento_tipo_descripcion + '</label>'
                    + '</span></a>'
                    + '</div>';
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
        llenarParametrosBusquedaPagoConDocumento(texto, tipoDocumentoIds, null, null);
    }
}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusquedaPagoConDocumento(null, null, serie, numero);
}

function llenarParametrosBusquedaPagoConDocumento(personaId, tipoDocumentoIds, serie, numero) {

    setCriterio(dataDocumentoTipoDatoPago, {
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
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 5,
        descripcion: "Persona",
        id: 114,
        opcional: 0,
        orden: 1,
        valor: personaId
    });
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 8,
        descripcion: "Número",
        id: 115,
        opcional: 0,
        orden: 2,
        valor: numero
    });
    getPagoDataTable();
}

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function imprimirDocumentoPago(id, documentoTipo)
{
    loaderShow();
    ax.setAccion("imprimir");
    ax.addParamTmp("id", id);
    ax.addParamTmp("documento_tipo_id", documentoTipo);
    ax.consumir();
}

function onResponseGetUserEmailByUserId(data)
{

    appendCurrentUserEmail(data);
}

function getUserEmailByUserId()
{
    ax.setAccion("getUserEmailByUserId");
    ax.consumir();

}

function appendCurrentUserEmail(data)
{
    //console.log(data[0]['email']);
    var fetchEmail;
    var arr;
    var correo;
    var newCorreo = "";


    if (!isEmpty(data))
    {
        fetchEmail = data[0]['email'];
    }

    //if(fetchEmail!='EMAIL_INVALIDO'){notificacion};
    if ($("#checkIncluirSelf").is(':checked'))
    {
        $('#txtCorreo').val(function (i, val) {
            return val + fetchEmail + (val ? ';' : '');
        });


        correo = $('#txtCorreo').val();
        correo = fixCorreo(correo);
        $('#txtCorreo').val(correo);

    } else {

        correo = $('#txtCorreo').val();
        arr = correo.split(";");

        for (var i = 0; i < arr.length; i++)
        {
            if (arr[i] === fetchEmail)
            {
                //alert("adsa: "+arr[i]);
                arr.splice(i, 1);
            } else {
                newCorreo += arr[i] + ";";

            }
        }
        newCorreo = fixCorreo(newCorreo);
        $('#txtCorreo').val(newCorreo);
    }
}