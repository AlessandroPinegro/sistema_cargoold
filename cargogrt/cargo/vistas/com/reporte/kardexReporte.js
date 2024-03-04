$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseKardexReporte");
    obtenerConfiguracionesInicialesKardexReporte();
    modificarAnchoTabla('datatable');
});

function onResponseKardexReporte(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesKardexReporte':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerDataKardexReporte':
                onResponseGetDataGridKardexReporte(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleKardexReporte':
                onResponseDetalleKardexReporte(response.data);
                loaderClose();
                break;
            case 'obtenerKardexReporteExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerKardexReporteExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesKardexReporte()
{
    ax.setAccion("obtenerConfiguracionesInicialesKardexReporte");
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

var valoresBusquedaKardexReporte = [{bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];

function cargarDatosBusqueda()
{
    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    valoresBusquedaKardexReporte[0].bien = bien;
    valoresBusquedaKardexReporte[0].bienTipo = bienTipo;
    valoresBusquedaKardexReporte[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaKardexReporte[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaKardexReporte[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaKardexReporte[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaKardexReporte[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaKardexReporte[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaKardexReporte[0].fechaEmision.inicio + " - " + valoresBusquedaKardexReporte[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarKardexReporte(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaKardexReporte(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaKardexReporte()
{
    ax.setAccion("obtenerDataKardexReporte");
    ax.addParamTmp("criterios", valoresBusquedaKardexReporte);
    ax.consumir();
}

function onResponseGetDataGridKardexReporte(data) {
//console.log(data);

    if (!isEmptyData(data))
    {
        $('#datatable').dataTable({
           
            "order": [[0, "asc"]],
            "data": data,
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "codigo_contable"},
                {"data": "bien_tipo_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "unidad_medida_descripcion"},
                {"data": "costo_inicial", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [3,5]
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
        buscarKardexReporte();
    }
    loaderClose();
}

function verDetalleKardexReporte(bienId, organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleKardexReporte");
    ax.addParamTmp("id_bien", bienId);
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleKardexReporte(data)
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

function exportarKardexReporteExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerKardexReporteExcel");
    ax.addParamTmp("criterios", valoresBusquedaKardexReporte);
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