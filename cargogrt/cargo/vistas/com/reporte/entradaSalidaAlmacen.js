$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesEntradaSalidaAlmacen();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesEntradaSalidaAlmacen':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataEntradaSalidaAlmacen':
                onResponseGetDataGridEntradaSalidaAlmacen(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleEntradaSalidaAlmacen':
                onResponseDetalleEntradaSalidaAlmacen(response.data);
                loaderClose();
                break;
            case 'obtenerReporteEntradaSalidaAlmacenExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteEntradaSalidaAlmacenExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesEntradaSalidaAlmacen()
{
    //alert('hola ES');
    ax.setAccion("obtenerConfiguracionesInicialesEntradaSalidaAlmacen");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.documento_tipo)) {
            select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
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
    }
    loaderClose();
}

var valoresBusquedaEntradaSalidaAlmacen = [{organizador: "", bien: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();   
    
    var documentoTipo = $('#cboDocumentoTipo').val();

    valoresBusquedaEntradaSalidaAlmacen[0].organizador = organizadorId;
    valoresBusquedaEntradaSalidaAlmacen[0].bien = bien;
    valoresBusquedaEntradaSalidaAlmacen[0].bienTipo = bienTipo;
    valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaEntradaSalidaAlmacen[0].empresaId = commonVars.empresa;
    valoresBusquedaEntradaSalidaAlmacen[0].documentoTipo = documentoTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].documentoTipo))
    {
        cadena += negrita("Documento tipo: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.inicio + " - " + valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarEntradaSalidaAlmacen(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaEntradaSalidaAlmacen(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaEntradaSalidaAlmacen()
{
    ax.setAccion("obtenerDataEntradaSalidaAlmacen");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalidaAlmacen);
    ax.consumir();
}

function onResponseGetDataGridEntradaSalidaAlmacen(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleEntradaSalidaAlmacen(' + item['organizador_id'] + ',' + item['indicador'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
           
            "order": [[2, "desc"]],
            "data": data,          
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "organizador_descripcion"},
                {"data": "tipo_frecuencia"},
                {"data": "frecuencia", "sClass": "alignRight"},
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
        buscarEntradaSalidaAlmacen();
    }
    loaderClose();
}

function verDetalleEntradaSalidaAlmacen(organizadorId, indicador,fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleEntradaSalidaAlmacen");// commonVars.empresa
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("id_empresa",  commonVars.empresa);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.addParamTmp("indicador", indicador);
    ax.consumir();
}

function onResponseDetalleEntradaSalidaAlmacen(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['organizador_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableEntradaSalidaAlmacen').dataTable({
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
        var table = $('#datatableEntradaSalidaAlmacen').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este producto.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteEntradaSalidaAlmacenExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteEntradaSalidaAlmacenExcel");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalidaAlmacen);
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
        buscarEntradaSalidaAlmacen(0);
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
