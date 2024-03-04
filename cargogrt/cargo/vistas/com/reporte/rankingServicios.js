$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesRankingServicios();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesRankingServicios':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataRankingServicios':
                onResponseGetDataGridRankingServicios(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleRankingServicios':
                onResponseDetalleRankingServicios(response.data);
                loaderClose();
                break;
            case 'obtenerReporteRankingServiciosExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteRankingServiciosExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesRankingServicios()
{
    //alert('hola RS');
    ax.setAccion("obtenerConfiguracionesInicialesRankingServicios");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
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
    loaderClose();
}

var valoresBusquedaRankingServicios = [{fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    valoresBusquedaRankingServicios[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaRankingServicios[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaRankingServicios[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaRankingServicios[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaRankingServicios[0].fechaEmision.inicio + " - " + valoresBusquedaRankingServicios[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarRankingServicios(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaRankingServicios(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaRankingServicios()
{
    ax.setAccion("obtenerDataRankingServicios");
    ax.addParamTmp("criterios", valoresBusquedaRankingServicios);
    ax.consumir();
}

function onResponseGetDataGridRankingServicios(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleRankingServicios(' + item['bien_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\',\'' + item['bien_descripcion'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[1, "desc"]],
            "data": data,            
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "cantidad_bienes", "sClass": "alignRight"},
                {"data": "opciones", "sClass": "alignCenter"}
            ],
            
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    
    loaderClose();
}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarRankingServicios();
    }
    loaderClose();
}

var descripcionServicio = '';
function verDetalleRankingServicios(bienId,fechaInicio, fechaFin,servicio)
{
    descripcionServicio=servicio;
    //alert(servicio);
    loaderShow();
    ax.setAccion("obtenerDetalleRankingServicios");
    ax.addParamTmp("id_bien", bienId);    
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);    
    ax.consumir();
}

function onResponseDetalleRankingServicios(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+ descripcionServicio + '</strong>';

        $('#datatableRankingServicios').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
                {"data": "documento_tipo_descripcion"},
                {"data": "persona_nombre"},
                {"data": "numero"},
                {"data": "documento_estado_descripcion"},                
                {"data": "organizador_descripcion"},                        
                {"data": "bien_descripcion"},
                {"data": "unidad_medida_descripcion"},
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
        var table = $('#datatableRankingServicios').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteRankingServiciosExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteRankingServiciosExcel");
    ax.addParamTmp("criterios", valoresBusquedaRankingServicios);
    //ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarRankingServicios(0);
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
