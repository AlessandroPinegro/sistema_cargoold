$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesRankingColaboradores();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesRankingColaboradores':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataRankingColaboradores':
                onResponseGetDataGridRankingColaboradores(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleRankingColaboradores':
                onResponseDetalleRankingColaboradores(response.data);
                loaderClose();
                break;
            case 'obtenerReporteRankingColaboradoresExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteRankingColaboradoresExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesRankingColaboradores()
{
    //alert('hola RC...');
    ax.setAccion("obtenerConfiguracionesInicialesRankingColaboradores");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.persona_tipo)) {
            select2.cargar("cboPersonaTipo", data.persona_tipo, "id", "descripcion");
            if (!isEmpty(data.bien)) {
                select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
                if (!isEmpty(data.bien_tipo)) {
                    select2.cargar("cboTipoBien", data.bien_tipo, "id", "descripcion");
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
    }
    loaderClose();
}

var valoresBusquedaRankingColaboradores = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: "", personaTipo: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();   
    
    var personaTipo = $('#cboPersonaTipo').val();

    valoresBusquedaRankingColaboradores[0].organizador = organizadorId;
    valoresBusquedaRankingColaboradores[0].bien = bien;
    valoresBusquedaRankingColaboradores[0].bienTipo = bienTipo;
    valoresBusquedaRankingColaboradores[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaRankingColaboradores[0].empresaId = commonVars.empresa;
    valoresBusquedaRankingColaboradores[0].personaTipo = personaTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaRankingColaboradores[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRankingColaboradores[0].personaTipo))
    {
        cadena += negrita("Persona tipo: ");
        cadena += select2.obtenerTextMultiple('cboPersonaTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRankingColaboradores[0].bien))
    {
        cadena += negrita("Bien: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRankingColaboradores[0].bienTipo))
    {
        cadena += negrita("Bien tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRankingColaboradores[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaRankingColaboradores[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaRankingColaboradores[0].fechaEmision.inicio + " - " + valoresBusquedaRankingColaboradores[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarRankingColaboradores(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaRankingColaboradores(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaRankingColaboradores()
{
    ax.setAccion("obtenerDataRankingColaboradores");
    ax.addParamTmp("criterios", valoresBusquedaRankingColaboradores);
    ax.consumir();
}

function onResponseGetDataGridRankingColaboradores(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {            
            data[index]["id"] = index+1;
        });
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "data": data,            
            "columns": [
                {"data": "id", "sClass": "alignCenter"},
                {"data": "persona_nombre"},
                {"data": "frecuencia_ingreso", "sClass": "alignRight"},
                {"data": "frecuencia_salida", "sClass": "alignRight"},
                {"data": "frecuencia_total", "sClass": "alignRight"},
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
        buscarRankingColaboradores();
    }
    loaderClose();
}

function verDetalleRankingColaboradores(organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleRankingColaboradores");// commonVars.empresa
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("id_empresa",  commonVars.empresa);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleRankingColaboradores(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['organizador_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableRankingColaboradores').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "organizador_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "frecuencia", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-documentos-servicios').modal('show');
    }
    else
    {
        var table = $('#datatableRankingColaboradores').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteRankingColaboradoresExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteRankingColaboradoresExcel");
    ax.addParamTmp("criterios", valoresBusquedaRankingColaboradores);
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
        buscarRankingColaboradores(0);
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
