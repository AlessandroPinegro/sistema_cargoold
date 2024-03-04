$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseKardexValorizado");
    obtenerConfiguracionesInicialesKardexValorizado();
    modificarAnchoTabla('datatable');
});

function onResponseKardexValorizado(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesKardexValorizado':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerDataKardexValorizado':
                onResponseGetDataGridKardexValorizado(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleKardexValorizado':
                onResponseDetalleKardexValorizado(response.data);
                loaderClose();
                break;
            case 'obtenerKardexValorizadoExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerKardexValorizadoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesKardexValorizado()
{
    ax.setAccion("obtenerConfiguracionesInicialesKardexValorizado");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.bien)) {
        select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
        if (!isEmpty(data.bien_tipo)) {
            select2.cargar("cboTipoBien", data.bien_tipo, "id", ["codigo","descripcion"]);
            if (!isEmpty(data.fecha_primer_documento)) {

                if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
                {
                    $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
                    if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
                    {
                        $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
                    }
                }

            }
        }

    }
}

var valoresBusquedaKardexValorizado = [{bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];

function cargarDatosBusqueda()
{
    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    valoresBusquedaKardexValorizado[0].bien = bien;
    valoresBusquedaKardexValorizado[0].bienTipo = bienTipo;
    valoresBusquedaKardexValorizado[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaKardexValorizado[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaKardexValorizado[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaKardexValorizado[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaKardexValorizado[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaKardexValorizado[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaKardexValorizado[0].fechaEmision.inicio + " - " + valoresBusquedaKardexValorizado[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarKardexValorizado(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaKardexValorizado(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaKardexValorizado()
{
    ax.setAccion("obtenerDataKardexValorizado");
    ax.addParamTmp("criterios", valoresBusquedaKardexValorizado);
    ax.consumir();
}

function onResponseGetDataGridKardexValorizado(data) {


    if (!isEmptyData(data))
    {
        $('#datatable').dataTable({
           
            "order": [[0, "asc"]],
            "data": data,
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock_valorizado", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [2,4]
                }
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarKardexValorizado();
    }
    loaderClose();
}

function verDetalleKardexValorizado(bienId, organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleKardexValorizado");
    ax.addParamTmp("id_bien", bienId);
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleKardexValorizado(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';

        $('#datatableStock').dataTable({
            order: [[0, "asc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "unidad_medida_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-kardex').modal('show');
    }
    else
    {
        var table = $('#datatableStock').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

function exportarKardexValorizadoExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerKardexValorizadoExcel");
    ax.addParamTmp("criterios", valoresBusquedaKardexValorizado);
//    ax.addParamTmp("tipo", 2);
    ax.consumir();
}

var actualizandoBusqueda = false;
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