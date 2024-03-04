var dataDocumentoTipoDato;
var banderaBuscar = 0;
var estadoTolltip = 0;
var bandera_eliminar = false;
var bandera_aprobar = false;
var tipoInterfaz = document.getElementById("tipoInterfaz").value;
var currentUserEmail;
var bandera = {
    primeraCargaDocumentosRelacion: true
};



$(document).ready(function () {
    dataDocumentoTipoDato = [];
    $('[data-toggle="popover"]').popover({ html: true }).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponseMovimientoListarGuia");
    obtenerConfiguracionesInicialesGuia();
    iniciarDataPicker();
});

/**
 *
 * @param response
 * @param response.data
 * @param response.data.columna
 */
var dataConfiguracionInicial;

function onResponseMovimientoListarGuia(response) {
    //breakFunction();
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesGuiaRT':
                dataConfiguracionInicial = response.data;
                select2.cargar("cboBus", dataConfiguracionInicial.bus.vehiculo, "id", "flota_placa");
                select2.cargar("cboAgenciaOrigen", dataConfiguracionInicial.agenciaUser, "id", "descripcion");
                select2.cargar("cboAgenciaDestino", dataConfiguracionInicial.origen, "agencia_id", "descripcion");
                select2.cargar("agenciaUser", dataConfiguracionInicial.agenciaUser, "id", "descripcion");
                select2.cargar("cboConductor", dataConfiguracionInicial.choferes, "id", "conductor");
                //select2.cargar("cboManifiesto", dataConfiguracionInicial.manifiesto, "manifiesto", "manifiesto");
                select2.asignarValor('cboManifiesto', -1);
                select2.asignarValor('cboBus', -1);
                select2.asignarValor('cboAgenciaDestino', -1);
                select2.asignarValor('cboConductor', -1);
                //$('#agenciaUser option')[0].selected = true;
                select2.asignarValor('agenciaUser', $('#agenciaUser').val());
                select2.asignarValor('cboAgenciaOrigen', $('#agenciaUser').val());
                $('#cboAgenciaOrigen').select2("enable", false);
                onResponseAjaxpGetDataGrid(response.data.guia);
                loaderClose();
                break;
            case 'buscarGuiaRT':
                onResponseAjaxpGetDataGrid(response.data);
                loaderClose();
                break;
            case 'visualizarDocumentoGuiaRT':
                onResponsevisualizarDocumentoGuiaRT(response.data, response.tag);
                loaderClose();
                break;
                case 'imprimirDocumentoGuiaRT':
                    loaderClose();
                    window.open(URL_BASE + 'vistas/com/movimiento/documentos/' + response.data.pdf_title);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'imprimir':
                loaderClose();
                break;
        }
    }
}

function onResponsevisualizarDocumentoGuiaRT(data, documentoOrigenId) {
    $("#formularioDetalleDocumento").empty();
    //habilitarBotonGenerarDocumentoDevolucionCargo();
    var serieDocumento = '';
    /*if (!isEmpty(data['dataDocumento'][0].serie)) {
        serieDocumento = data['dataDocumento'][0].serie + " - ";
    }*/
    let titulo = "<b>Guia " + (data[0].serieguia.toUpperCase() + "-" + data[0].nroguia);

    if (!isEmpty(data[0].destino) && !isEmpty(data[0].origen)) {
        titulo = titulo + " | " + data[0].origen + " - " + data[0].destino
    }

    /*if (data['dataDocumento'][0]['bandera_es_cargo'] == 1) {
        titulo = titulo + ' | <span class="label label-info">Dev. cargo</span>';
    }*/
    titulo = titulo + "</b>";
    $('#tituloVisualizacionModal').html(titulo);
    var fechaEmision = separarFecha(data[0].guia_fecha_emision);

    var html = '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Fecha:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + (fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);
    html = html + '</div>';
    html = html + '</div>';


    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Moneda:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].moneda_descripcion;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-2">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Tipo:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].modalidad_descripcion;
    html = html + '</div>';
    html = html + '</div>';


    html = html + '</div>';


    html = html + '<div class="row">';
    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Dirección origen:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].persona_direccion_origen;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Dirección destino:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].persona_direccion_destino;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '</div>';


    html = html + '<div class="row">';
    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Remitente:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].documento_remitente + ' | ' + data[0].remitente;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Destinatario:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].documento_destinatario + ' | ' + data[0].destinatario;
    html = html + '</div>';
    html = html + '</div>';


    html = html + '</div><br>';

    html = html + '<div class="row">';
    html = html + '<div class="col-md-12">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Datos de Unidad de Transporte y Conductor:</label>';
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Marca y número de placa:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].flota_marca + ' | ' + data[0].flota_placa;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Tarjeta de Circulación:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    //html = html + data[0].flota_marca + ' | ' + data[0].flota_placa;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Código de Configuración Vehicular:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].vehiculo_id;
    html = html + '</div>';
    html = html + '</div>';


    html = html + '</div>';


    html = html + '<div class="row">';
    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Licencia de Conducir:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].licencia;
    html = html + '</div>';
    html = html + '</div>';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + '<label>Conductor:</label>';
    html = html + '</div>';
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data[0].conductor;
    html = html + '</div>';
    html = html + '</div>';


    html = html + '</div>';


    appendFormDetalle(html);
    $('#tabsDistribucionMostrar').show();
    $('a[href="#tabDistribucionDetalle"]').click();
    cargarDetalleDocumento(data);


    document.getElementById("btnImprimirModal").onclick = function (event) {
        if (data['dataDocumento'][0]['identificador_negocio'] == '4' ||
            data['dataDocumento'][0]['identificador_negocio'] == '3' ||
            data['dataDocumento'][0]['identificador_negocio'] == '43') {
            imprimirDocumentoTicket(data['dataDocumento'][0]['id']);
        } else {
            imprimirDocumento(data['dataDocumento'][0]['id'], null, 'modalDetalleDocumento');
        }
    };

    $('#modalDetalleDocumento').modal('show');
}


function obtenerConfiguracionesInicialesGuia() {
    ax.setAccion("obtenerConfiguracionesInicialesGuiaRT");
    ax.consumir();
}

function buscarGuiaRT() {
    loaderShow();
    var bus_id = $('#cboBus').val();
    var fecha_salida = $('#fechaSalidad').val().split('/').reverse().join('-');
    var conductor_id = $('#cboConductor').val();
    var origin_id = $('#agenciaUser').val();
    var destino_id = $('#cboAgenciaDestino').val();
    var nro_guia = $('#nro_guia').val();

    if (isEmpty(bus_id) || bus_id == '') {
        bus_id = null;
    }
    if (isEmpty(fecha_salida) || fecha_salida == '') {
        fecha_salida = null;
    }
    if (isEmpty(conductor_id) || conductor_id == '') {
        conductor_id = null;
    }
    if (isEmpty(nro_guia) || nro_guia == '') {
        nro_guia = null;
    }
    if (isEmpty(origin_id) || origin_id == '') {
        origin_id = null;
    }
    if (isEmpty(destino_id) || destino_id == '') {
        budestino_ids_id = null;
    }
    ax.setAccion("buscarGuiaRT");
    ax.addParamTmp("bus_id", bus_id);
    ax.addParamTmp("fecha_salida", fecha_salida);
    ax.addParamTmp("conductor_id", conductor_id);
    ax.addParamTmp("origin_id", origin_id);
    ax.addParamTmp("destino_id", destino_id);
    ax.addParamTmp("nro_guia", nro_guia);
    ax.consumir();
}

function appendFormDetalle(html) {

    $("#formularioDetalleDocumento").append(html);
}

function cargarDetalleDocumento(data) {
    if (!isEmptyData(data)) {
        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Descripción</th>";
        html += "<th style='text-align:center;'>Peso Total</th> ";
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
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['cantidad'], 2) + "</td>";
            html += "<td>" + item.nro_serie + "-" + item.nro_numero + " | "+item.descripcion+"</td> ";
            html += "<td style='text-align:center;'>" + formatearNumeroPorCantidadDecimales(item['bien_peso'], 2) + "</td>";
            html += "<td style='text-align:right;'>" + formatearNumero(item['valor_monetario']) + "</td>";
            html += "</tr>";
        });


        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


$("#agenciaUser").change(function () {
    var opctionselect = $("#agenciaUser").val();
    select2.asignarValor('cboAgenciaOrigen', opctionselect);
})

function onResponseAjaxpGetDataGrid(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cantidad = 0;
    var manifiesto = '';
    var cabeza = '<table id="datatableGuia" class="table table-striped table-bordered"><thead>' +
        ' <tr>' +
        '<th style="text-align:center;" width="10%">Guia</th>' +
        '<th style="text-align:center;" width="10%">Fecha de Emisión</th>' +
        '<th style="text-align:center;" width="8%">Bus</th>' +
        '<th style="text-align:center;" width="19%">Conductor</th>' +
        '<th style="text-align:center;" width="8%">Origen</th>' +
        '<th style="text-align:center;" width="8%">Destino</th>' +
        '<th style="text-align:center;" width="8%">Bultos</th>' +
        '<th style="text-align:center;" width="10%">Nro Manifiesto(s)</th>' +
        '<th style="text-align:center;" width="10%">Usuario</th>' +
        //'<th style="text-align:center;" width="10%">Estado</th>' +
        '<th style="text-align:center;" width="10%">Acciones</th>' +
        "</tr>" +
        "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            var count = Object.keys(data).length
            var ind = index + 1;

            if (ind >= count) {
                ind = index;
            }
            if (item.guia_id == data[ind].guia_id) {
                if (ind > index) {
                    //manifiesto = manifiesto + item.guia+'<br>';
                    cantidad = cantidad + parseFloat(item.bultos);
                } else {
                    cantidad = cantidad + parseFloat(item.bultos);
                    cuerpo = "<tr>" +
                        "<td style='text-align:center;'><a href='#' onclick='visualizarDocumento(" + item.guia_id + ")' title='Visualizar' " +
                        "style='cursor:pointer;color:-webkit-link;text-decoration: underline;'>" + item.guia + "</a></td>" +
                        "<td style='text-align:left;'>" + item.fecha_emision + "</td>" +
                        "<td style='text-align:left;'>" + item.flota_placa + "</td>" +
                        "<td style='text-align:left;'>" + item.conductor_docum + ' | ' + item.conductor + "</td>" +
                        "<td style='text-align:left;'>" + item.agencia + "</td>" +
                        "<td style='text-align:left;'>" + item.agencia_destino + "</td>" +
                        "<td style='text-align:center;'>" + cantidad + "</td>" +
                        "<td style='text-align:left;'>" +item.manifiesto + "</td>" +
                        "<td style='text-align:left;'>" + item.usuario_creacion + "</td>" +
                        "<td style='text-align:center;'>" +
                        "<a href='#'  onclick='anularGuiaRT(" + item.guia_id + ");' title='Anular Guia Transportista'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>&nbsp;\n" +
                        "<a href='#'  onclick='obtenerDocumentosRelacionados(" + item.guia_id + ");' title='Ver documentos relacionados'><b><i class='ion-android-share' style='color:#E8BA2F'></i><b></a>&nbsp;\n" +
                        "<a href='#'  onclick='imprimirGuiaRT(" + item.guia_id + ");' title='imprimir Guia Transportista'><b><i class='fa fa-print' style='color:#088A68;'></i><b></a>&nbsp;\n" +
                        "</td></tr>";
                    manifiesto = '';
                    cantidad = 0;
                    cuerpo_total = cuerpo_total + cuerpo;
                }

            } else {
                cantidad = cantidad + parseFloat(item.bultos);
                cuerpo = "<tr>" +
                    "<td style='text-align:center;'><a href='#' onclick='visualizarDocumento(" + item.guia_id + ")' title='Visualizar' " +
                    "style='cursor:pointer;color:-webkit-link;text-decoration: underline;'>" + item.guia + "</a></td>" +
                    "<td style='text-align:left;'>" + item.fecha_emision + "</td>" +
                    "<td style='text-align:left;'>" + item.flota_placa + "</td>" +
                    "<td style='text-align:left;'>" + item.conductor_docum + ' | ' + item.conductor + "</td>" +
                    "<td style='text-align:left;'>" + item.agencia + "</td>" +
                    "<td style='text-align:left;'>" + item.agencia_destino + "</td>" +
                    "<td style='text-align:center;'>" + cantidad + "</td>" +
                    "<td style='text-align:left;'>" +item.manifiesto + "</td>" +
                    "<td style='text-align:left;'>" + item.usuario_creacion + "</td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#'  onclick='anularGuiaRT(" + item.guia_id + ");' title='Anular Guia Transportista'><b><i class='fa fa-ban' style='color:#cb2a2a;'></i><b></a>&nbsp;\n" +
                    "<a href='#'  onclick='obtenerDocumentosRelacionados(" + item.guia_id + ");' title='Ver documentos relacionados'><b><i class='ion-android-share' style='color:#E8BA2F'></i><b></a>&nbsp;\n" +
                    "<a href='#'  onclick='imprimirGuiaRT(" + item.guia_id + ");' title='imprimir Guia Transportista'><b><i class='fa fa-print' style='color:#088A68;'></i><b></a>&nbsp;\n" +
                    "</td></tr>";
                manifiesto = '';
                cantidad = 0;
                cuerpo_total = cuerpo_total + cuerpo;
            }
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
    dataTable = $('#datatableGuia').DataTable({
        "autoWidth": true,
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
        }
    });
}
function visualizarDocumento(documentoId, movimientoId, modal, documentoOrigenId) {
    docId = documentoId;

    $('#txtCorreo').val('');
    if (!isEmpty(modal)) {
        loaderShow("#" + modal);
    } else {
        loaderShow();
    }

    //ax.setAccion("obtenerDocumentoRelacionVisualizar");
    ax.setAccion("visualizarDocumentoGuiaRT");
    ax.addParamTmp("documentoId", documentoId);
    ax.setTag(documentoOrigenId);
    ax.consumir();

    //    
}
function anularGuiaRT(id) {
    swal({
        title: "Est\xe1s seguro?",
        text: "Anulara una Guia de Transportista, esta anulación no podra revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, anular!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            loaderShow();
            ax.setAccion("anularGuiaRT");
            ax.addParamTmp("id", id);
            ax.consumir();
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function imprimirGuiaRT(id) {
    loaderShow();
    ax.setAccion("imprimirDocumentoGuiaRT");
    ax.addParamTmp("id", id);
    ax.consumir();
}
function formularioManifiesto(id) {

    cargarDivTitulo('#window', 'vistas/com/movimiento/movimiento_listar_manifiesto.php?id=' + id);
}

function limpiarFiltrosManifiesto() {
    $('#fechaSalidad').val('');
    select2.asignarValor('cboConductor', -1);
    select2.asignarValor('cboBus', -1);
    select2.asignarValor('cboEstadoDespacho', -1);
    select2.asignarValor('cboManifiesto', -1);
    select2.asignarValor('cboAgenciaDestino', -1);

    $('#agenciaUser option')[0].selected = true;
    select2.asignarValor('agenciaUser', $('#agenciaUser').val());
    select2.asignarValor('cboAgenciaOrigen', $('#agenciaUser').val());
    buscarGuiaRT();
}

function iniciarDataPicker() {
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}