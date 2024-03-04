var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var dataTotal;
var total_cantidad;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");    
    modificarAnchoTabla('dataTableTransferenciaDiferente');
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseTransferenciaDiferente");
    obtenerDataTransferenciaDiferente();
});

function onResponseTransferenciaDiferente(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'verDetallePorCliente':
                onResponseVerDetallePorCliente(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'verDetallePorCliente':
                loaderClose();
                break;
        }
    }
}

function obtenerDataTransferenciaDiferente() {    
    ax.setAccion("obtenerDataTransferenciaDiferente");
    $('#dataTableTransferenciaDiferente').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "filter": false,
        "order": [[0, "desc"]],  
        "columns": [
            {"data": "tra_serie_numero", "class": "alignCenter"},
            {"data": "rec_serie_numero", "class": "alignCenter"},
            {"data": "bien_codigo"},
            {"data": "bien_desc"},
            {"data": "tra_cantidad", "class": "alignRight"},
            {"data": "rec_cantidad", "class": "alignRight"},
            {"data": "unidad_medida_desc"}

        ],
        columnDefs: [ 
            {
                "render": function (data, type, row) {
                    var html = '<a onclick="verDetalleDocumento(' + row.transferencia_id + ',' + row.trans_mov_id + ')"  title="Ver detalle de la guía" style="color:blue">'+data+'</a>';
                    return html;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    var html = '<a onclick="verDetalleDocumento(' + row.recepcion_id + ',' + row.rec_mov_id + ')"  title="Ver detalle de la recepción" style="color:green">'+data+'</a>';
                    return html;
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    return formatearNumero(data);
                },
                "targets": [4,5]
            }
        ],
        destroy: true
    });
    loaderClose();
}

// ver detalle en modal
function verDetalleDocumento(documentoId, movimientoId)
{
    loaderShow();
    ax.setAccion("verDetallePorCliente");
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.consumir();
//    
}

function onResponseVerDetallePorCliente(data)
{
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
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
//                    case 2:
                    case 3:
                        valor = fechaArmada(valor);
                        break;
//                    case 4:
//                    case 5:
//                    case 6:
//                    case 7:
//                    case 8:
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
//                    case 12:
//                    case 13:
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

function cargarDetalleDocumento(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
//            "scrollX": true,
            "order": [[2, "asc"]],
            "data": data,
            "columns": [
                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
//                {"data": "precioUnitario", "sClass": "alignRight"},
//                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    } else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}