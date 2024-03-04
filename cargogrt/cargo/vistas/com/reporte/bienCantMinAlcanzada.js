$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesKardex();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesKardex':
                onResponseObtenerConfiguracionesIniciales(response.data);
                break;
            case 'obtenerBienesCantMinimaAlcanzada':
                onResponseGetDataGridKardex(response.data);
                loaderClose();
                break;
//            case 'obtenerDetalleKardex':
//                onResponseDetalleKardex(response.data);
//                loaderClose();
//                break;
            case 'obtenerReporteBienesCantMinimaAlcanzadaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/ReporteBienCantMinAlcanzada.xlsx";
                break;
        }
    }
//    else {
//        switch (response[PARAM_ACCION_NAME]) {
//            case 'obtenerReporteKardexExcel':
//                loaderClose();
//                break;
//        }
//    }
}

function obtenerConfiguracionesInicialesKardex()
{
    ax.setAccion("obtenerConfiguracionesInicialesKardex");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    if (!isEmpty(data.organizador)) {
//        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.bien)) {
            select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
//            if (!isEmpty(data.bien_tipo)) {
//                select2.cargar("cboTipoBien", data.bien_tipo, "id", "descripcion");
//                if (!isEmpty(data.fecha_primer_documento)) {
//                    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
//                    {
//                        $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
//                        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
//                        {
//                            $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
//                        }
//                    }
//                }
//            }
        }
//    }
    loaderClose();
}

var valoresBusquedaKardex = [{bien: "", empresaId: ""}];//bandera 0 es balance
//var valoresBusquedaKardex = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
//    var organizadorId = $('#cboOrganizador').val();
    var bien = $('#cboBien').val();
//    var bienTipo = $('#cboTipoBien').val();
//    var fechaEmisionInicio = $('#inicioFechaEmision').val();
//    var fechaEmisionFin = $('#finFechaEmision').val();

//    valoresBusquedaKardex[0].organizador = organizadorId;
    valoresBusquedaKardex[0].bien = bien;
//    valoresBusquedaKardex[0].bienTipo = bienTipo;
//    valoresBusquedaKardex[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaKardex[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

//    if (!isEmpty(valoresBusquedaKardex[0].organizador))
//    {
//        cadena += negrita("Organizador: ");
//        cadena += select2.obtenerTextMultiple('cboOrganizador');
//        cadena += "<br>";
//    }
    if (!isEmpty(valoresBusquedaKardex[0].bien))
    {
        cadena += negrita("Bien: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
//    if (!isEmpty(valoresBusquedaKardex[0].bienTipo))
//    {
//        cadena += negrita("Bien tipo: ");
//        cadena += select2.obtenerTextMultiple('cboTipoBien');
//        cadena += "<br>";
//    }
//    if (!isEmpty(valoresBusquedaKardex[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaKardex[0].fechaEmision.fin))
//    {
//        cadena += negrita("Fecha emisión: ");
//        cadena += valoresBusquedaKardex[0].fechaEmision.inicio + " - " + valoresBusquedaKardex[0].fechaEmision.fin;
//        cadena += "<br>";
//    }
    return cadena;
}

function buscarKardex(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaKardex(cadena);

    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaKardex()
{
    ax.setAccion("obtenerBienesCantMinimaAlcanzada");
    ax.addParamTmp("criterios", valoresBusquedaKardex);
    ax.consumir();
}

function onResponseGetDataGridKardex(data) 
{
    if (!isEmptyData(data))
    {
//        $.each(data, function (index, item) {
//            data[index]["opciones"] = '<a onclick="verDetalleKardex(' + item['bien_id'] + ',' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
//        });
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "bien_descripcion"},
//                {"data": "organizador_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "cantidad_minima", "sClass": "alignRight"},
                {"data": "proveedor"}
//                {"data": "opciones", "sClass": "alignCenter"}
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

//function verDetalleKardex(bienId, organizadorId, fechaInicio, fechaFin)
//{
//    loaderShow();
//    ax.setAccion("obtenerDetalleKardex");
//    ax.addParamTmp("id_bien", bienId);
//    ax.addParamTmp("id_organizador", organizadorId);
//    ax.addParamTmp("fecha_inicio", fechaInicio);
//    ax.addParamTmp("fecha_fin", fechaFin);
//    ax.consumir();
//}

//function onResponseDetalleKardex(data)
//{
//    if (!isEmptyData(data))
//    {
//        $('[data-toggle="popover"]').popover('hide');
//        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';
//
//        $('#datatableStock').dataTable({
//            order: [[0, "desc"]],
//            "ordering": false,
//            "data": data,
//            "columns": [
//                {"data": "unidad_medida_descripcion"},
//                {"data": "cantidad", "sClass": "alignRight"}
//            ],
//            "destroy": true
//        });
//        $('.modal-title').empty();
//        $('.modal-title').append(stringTituloStock);
//        $('#modal-detalle-kardex').modal('show');
//    }
//    else
//    {
//        var table = $('#datatableStock').DataTable();
//        table.clear().draw();
//        mostrarAdvertencia("No se encontro detalles de este bien.")
//    }
//}

var actualizandoBusqueda = false;
function exportarReporteKardexExcel()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteBienesCantMinimaAlcanzadaExcel");
    ax.addParamTmp("criterios", valoresBusquedaKardex);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador === "false")
    {
        buscarKardex();
    }
    loaderClose();
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