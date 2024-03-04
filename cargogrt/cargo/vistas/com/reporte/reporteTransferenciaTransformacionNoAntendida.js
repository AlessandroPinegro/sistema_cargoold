var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var dataTotal;
var total_cantidad;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
//    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseTransferenciaTransformacionNoAtendida");
    obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida();
    modificarAnchoTabla('dataTableTransferenciaTransformacionNoAtendida');
});

function onResponseTransferenciaTransformacionNoAtendida(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida':
                onResponseObtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida(response.data);
                break;
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

function obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida()
{
    ax.setAccion("obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida");
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida(data) {
    if (!isEmpty(data.dataMotivoTraslado)) {
        select2.cargar('cboMotivoTraslado', data.dataMotivoTraslado, 'id', 'descripcion');        
    }


    if (!isEmpty(data.dataFecha[0]['primera_fecha'])) {
        $('#fechaEmisionInicio').val(formatearFechaBDCadena(data.dataFecha[0]['primera_fecha']));
        if (!isEmpty(data.dataFecha[0]['fecha_actual'])) {
            $('#fechaEmisionFin').val(formatearFechaBDCadena(data.dataFecha[0]['fecha_actual']));
        }
    }

    loaderClose();
}

var valoresBusquedaTransferenciaTransformacionNoAtendida = [{motivoTraslado: ""}];

function cargarDatosBusquedaTransferenciaTransformacionNoAtendida()
{
    var motivoTrasladoId = $('#cboMotivoTraslado').val();
    var fechaEmisionInicio = $('#fechaEmisionInicio').val();
    var fechaEmisionFin = $('#fechaEmisionFin').val();

    valoresBusquedaTransferenciaTransformacionNoAtendida[0].motivoTraslado = motivoTrasladoId;
    valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionHasta = fechaEmisionFin;
}
function buscarTransferenciaTransformacionNoAtendida(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    getDataTableTransferenciaTransformacionNoAtendida();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;
    
    if (colapsa === 1)
        colapsarBuscador();
    
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusquedaTransferenciaTransformacionNoAtendida();
    
    if (!isEmpty(valoresBusquedaTransferenciaTransformacionNoAtendida[0].motivoTraslado))
    {
        cadena += StringNegrita("Motivo de traslado: ");

        cadena += select2.obtenerTextMultiple('cboMotivoTraslado');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionDesde + " - " + valoresBusquedaTransferenciaTransformacionNoAtendida[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}

function getDataTableTransferenciaTransformacionNoAtendida() {    
    ax.setAccion("obtenerDataTransferenciaTransformacionNoAtendida");
    ax.addParamTmp("criterios", valoresBusquedaTransferenciaTransformacionNoAtendida);
    $('#dataTableTransferenciaTransformacionNoAtendida').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],  
        "columns": [
            {"data": "fecha_creacion", "class": "alignCenter"},
            {"data": "fecha_emision", "class": "alignCenter"},
            {"data": "documento_tipo_descripcion"},
            {"data": "serie_numero", "class": "alignCenter"},
            {"data": "persona_nombre_completo"},
            {"data": "doc_tipo_dato_lista_desc"},
            {"data": "usuario"},
            {"data": "documento_id", "class": "alignCenter"}

        ],
        columnDefs: [ 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':datex.parserFecha(data);
                },
                "targets": [0,1]
            },
            {
                "render": function (data, type, row) {
                    var html = '<a onclick="verDetalleDocumento(' + row.documento_id + ',' + row.movimiento_id + ')"  title="Ver detalle del documento"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';
                    return html;
                },
                "targets": 7
            }
        ],
        destroy: true
    });
    loaderClose();
}

var actualizandoBusqueda = false;
function loaderBuscarVentas()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarTransferenciaTransformacionNoAtendida();
    }
}
function cerrarPopover()
{
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
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


    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
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
        $('#bg-info').removeAttr('height', "0px");
        $('#bg-info').addClass('in');
    }
}

function exportarReporteTransferenciaTransformacionNoAtendida()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaTransferenciaTransformacionNoAtendida();
    ax.setAccion("obtenerReporteTransferenciaTransformacionNoAtendidaExcel");
    ax.addParamTmp("criterios", valoresBusquedaTransferenciaTransformacionNoAtendida);
    ax.consumir();
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


function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}