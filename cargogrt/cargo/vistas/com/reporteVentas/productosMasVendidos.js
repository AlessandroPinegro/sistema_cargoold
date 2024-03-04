$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteProductosMasVendidos");
    obtenerConfiguracionesInicialesProductosMasVendidos();
    modificarAnchoTabla('datatable');
});

function onResponseReporteProductosMasVendidos(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesProductosMasVendidos':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataProductosMasVendidos':
                onResponseGetDataGridProductosMasVendidos(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoProductosMasVendidos':
                onResponseDocumentoProductosMasVendidos(response.data);
                loaderClose();
                break;
            case 'obtenerReporteProductosMasVendidosExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteProductosMasVendidosExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesProductosMasVendidos()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesProductosMasVendidos");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');
    
    if (!isEmpty(data.fecha_primer_documento)) {
        if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha'])) {
            $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
            if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual'])) {
                $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
            }
        }
    }
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaProductosMasVendidos = [{tienda: "", limite: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var limite = $('#txtLimite').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaProductosMasVendidos[0].tienda = tienda;
    valoresBusquedaProductosMasVendidos[0].limite = limite;
    valoresBusquedaProductosMasVendidos[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaProductosMasVendidos[0].tienda))
    {
        cadena += negrita("Empresa: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductosMasVendidos[0].limite))
    {
        cadena += negrita("Top: ");
        cadena += $('#txtLimite').val();
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductosMasVendidos[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaProductosMasVendidos[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaProductosMasVendidos[0].fechaEmision.inicio + " - " + valoresBusquedaProductosMasVendidos[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarProductosMasVendidos(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaProductosMasVendidos(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaProductosMasVendidos()
{
    ax.setAccion("obtenerDataProductosMasVendidos");
    ax.addParamTmp("criterios", valoresBusquedaProductosMasVendidos);
    ax.consumir();
}

function onResponseGetDataGridProductosMasVendidos(data) {

    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoProductosMasVendidos(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/
        $('#datatable').dataTable({
           
            "order": [[3, "desc"]],
            "data": data,            
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "productos_soles",  "sClass": "alignCenter"},
                {"data": "productos_dolares",  "sClass": "alignCenter"},
                {"data": "productos_vendidos",  "sClass": "alignCenter"}
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

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarKardex();
    }
    loaderClose();
}

function verDocumentoProductosMasVendidos(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoProductosMasVendidos");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoProductosMasVendidos(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoProductosMasVendidos').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
                {"data": "documento_tipo_descripcion"},
                {"data": "persona_nombre"},
                {"data": "serie"},
                {"data": "numero"},
                {"data": "fecha_vencimiento"},
                {"data": "documento_estado_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-documentos-servicios').modal('show');
    }
    else
    {
        var table = $('#datatableDocumentoProductosMasVendidos').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteProductosMasVendidosExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteProductosMasVendidosExcel");
    ax.addParamTmp("criterios", valoresBusquedaProductosMasVendidos);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarProductosMasVendidos();
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