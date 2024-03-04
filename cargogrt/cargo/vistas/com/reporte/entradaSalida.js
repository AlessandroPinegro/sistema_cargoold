$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesEntradaSalida();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesEntradaSalida':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataEntradaSalida':
                onResponseGetDataGridEntradaSalida(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleEntradaSalida':
                onResponseDetalleEntradaSalida(response.data);
                loaderClose();
                break;
            case 'obtenerReporteEntradaSalidaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteEntradaSalidaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesEntradaSalida()
{
    //alert('hola ES...');
    ax.setAccion("obtenerConfiguracionesInicialesEntradaSalida");
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
                    select2.cargar("cboTipoBien", data.bien_tipo, "id",["codigo","descripcion"]);
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

var valoresBusquedaEntradaSalida = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: "", documentoTipo: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();   
    
    var documentoTipo = $('#cboDocumentoTipo').val();

    valoresBusquedaEntradaSalida[0].organizador = organizadorId;
    valoresBusquedaEntradaSalida[0].bien = bien;
    valoresBusquedaEntradaSalida[0].bienTipo = bienTipo;
    valoresBusquedaEntradaSalida[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaEntradaSalida[0].empresaId = commonVars.empresa;
    valoresBusquedaEntradaSalida[0].documentoTipo = documentoTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaEntradaSalida[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalida[0].documentoTipo))
    {
        cadena += negrita("Documento tipo: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalida[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalida[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalida[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaEntradaSalida[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaEntradaSalida[0].fechaEmision.inicio + " - " + valoresBusquedaEntradaSalida[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarEntradaSalida(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaEntradaSalida(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaEntradaSalida()
{
    ax.setAccion("obtenerDataEntradaSalida");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalida);
    ax.consumir();
}

function onResponseGetDataGridEntradaSalida(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleEntradaSalida(' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
            
            "order": [[0, "desc"]],
            "data": data,          
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
                {"data": "documento_tipo_descripcion"},
                {"data": "documento_numero"},
                {"data": "organizador_descripcion"},
                {"data": "bien_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_control_descripcion"},
                {"data": "cantidad_control", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 8
                },
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                    },
                    "targets": 1
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
    
    loaderClose();
}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarEntradaSalida();
    }
    loaderClose();
}

function verDetalleEntradaSalida(organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleEntradaSalida");// commonVars.empresa
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("id_empresa",  commonVars.empresa);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleEntradaSalida(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['organizador_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableEntradaSalida').dataTable({
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
        var table = $('#datatableEntradaSalida').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteEntradaSalidaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteEntradaSalidaExcel");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalida);
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
        buscarEntradaSalida(0);
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
