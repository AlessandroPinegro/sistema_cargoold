$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteActivosFijos");
    obtenerConfiguracionesInicialesActivosFijos();
    modificarAnchoTabla('datatable');
});

function onResponseReporteActivosFijos(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesActivosFijos':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataActivosFijos':
                onResponseGetDataGridActivosFijos(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoActivosFijos':
                onResponseDocumentoActivosFijos(response.data);
                loaderClose();
                break;
            case 'obtenerReporteActivosFijosExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteActivosFijosExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesActivosFijos()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesActivosFijos");
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
    
    if (!isEmpty(data.motivo)) {
        select2.cargar("cboMotivo", data.motivo, "id", "descripcion");
    }
    
    if (!isEmpty(data.bien)) {
        select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
    }
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaActivosFijos = [{tienda: "", bien: "", motivo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var bien = $('#cboBien').val();
    var motivo = $('#cboMotivo').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaActivosFijos[0].tienda = tienda;
    valoresBusquedaActivosFijos[0].bien = bien;
    valoresBusquedaActivosFijos[0].motivo = motivo;
    valoresBusquedaActivosFijos[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaActivosFijos[0].tienda))
    {
        cadena += negrita("Empresa: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaActivosFijos[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaActivosFijos[0].motivo))
    {
        cadena += negrita("Motivo: ");
        cadena += select2.obtenerTextMultiple('cboMotivo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaActivosFijos[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaActivosFijos[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaActivosFijos[0].fechaEmision.inicio + " - " + valoresBusquedaActivosFijos[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarActivosFijos(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaActivosFijos(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaActivosFijos()
{
    ax.setAccion("obtenerDataActivosFijos");
    ax.addParamTmp("criterios", valoresBusquedaActivosFijos);
    ax.consumir();
}

function onResponseGetDataGridActivosFijos(data) {
//    console.log(data);
    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoActivosFijos(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/        
        
        /*Bien (activo fijo)
                                motivo
                                - proveedor
                                - tipo documento
                                - serie
                                - numero
                                - precio del activo*/
        $('#datatable').dataTable({
            "scrollX": true,
            "order": [[0, "asc"]],
            "data": data,
            "autoWidth": true,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "tipo_lista_descripcion"},
                {"data": "persona_nombre_completo"},
                {"data": "documento_tipo_descripcion"},
                {"data": "serie"},
                {"data": "numero",  "sClass": "alignRight"},
                {"data": "moneda_descripcion"},
                {"data": "valor_monetario",  "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 7
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

function verDocumentoActivosFijos(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoActivosFijos");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoActivosFijos(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoActivosFijos').dataTable({
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
        var table = $('#datatableDocumentoActivosFijos').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteActivosFijosExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteActivosFijosExcel");
    ax.addParamTmp("criterios", valoresBusquedaActivosFijos);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarActivosFijos();
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