$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("onResponseReporteCotizaciones");
//    obtenerConfiguracionesInicialesCotizacionessAtendidos();
//    modificarAnchoTabla('datatable');
    buscarCotizaciones();
});

function onResponseReporteCotizaciones(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesCotizacionessAtendidos':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataCotizaciones':
                onResponseObtenerDataCotizaciones(response.data);
                loaderClose();
                break;
            case 'obtenerCotizacionesDetalle':
                onResponseObtenerCotizacionesDetalle(response.data);
                loaderClose();
                break;
            case 'obtenerReporteCotizacionessAtendidosExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteCotizacionessAtendidosExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesCotizacionessAtendidos()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesCotizacionessAtendidos");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');
    
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.bien)) {
            select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
            if (!isEmpty(data.documento_tipo)) {
                select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
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
    loaderClose();
}

var valoresBusquedaCotizacioness = [{organizador: "", bien: "", documentoTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var documentoTipo = $('#cboDocumentoTipo').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaCotizacioness[0].organizador = organizadorId;
    valoresBusquedaCotizacioness[0].bien = bien;
    valoresBusquedaCotizacioness[0].documentoTipo = documentoTipo;
    valoresBusquedaCotizacioness[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaCotizacioness[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaCotizacioness[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaCotizacioness[0].bien))
    {
        cadena += negrita("Bien: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaCotizacioness[0].documentoTipo))
    {
        cadena += negrita("Documento tipo: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaCotizacioness[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaCotizacioness[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaCotizacioness[0].fechaEmision.inicio + " - " + valoresBusquedaCotizacioness[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarCotizaciones() {
    loaderShow();
//    var cadena;
//    cadena = obtenerDatosBusqueda();
//    if (!isEmpty(cadena) && cadena !== 0)
//    {
//        $('#idPopover').attr("data-content", cadena);
//    }
//    $('[data-toggle="popover"]').popover('show');
//    banderaBuscar = 1;

    obtenerDataBusquedaCotizaciones();
    
//    if (colapsa === 1)
//        colapsarBuscador();
}

function obtenerDataBusquedaCotizaciones()
{
    ax.setAccion("obtenerDataCotizaciones");
//    ax.addParamTmp("criterios", valoresBusquedaCotizacioness);
    ax.consumir();
}

function onResponseObtenerDataCotizaciones(data) {

    if (!isEmptyData(data)){
        $('#datatable').dataTable({          
            "order": [[0, "desc"]],
            "data": data,           
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_tipo_descripcion", "sClass": "alignLeft", width: "15%"},
                {"data": "bien_codigo", width: "10%"},
                {"data": "bien_descripcion",  "sClass": "alignLeft", width: "40%"},
                {"data": "proveedor_descripcion",  "sClass": "alignLeft", width: "28%"},
                {"data": "bien_id", "sClass": "alignCenter", width: "7%"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return '<a onclick="verCotizaciones(' + data + ')" title="Ver cotizaciones"><b><i class="fa fa-eye"" style="color:green"></i><b></a>';;
                    },
                    "targets": 4
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

function verCotizaciones(bienId)
{
    loaderShow();
    ax.setAccion("obtenerCotizacionesDetalle");
    ax.addParamTmp("bienId", bienId);
    ax.consumir();
}
// , "width": "50px"
function onResponseObtenerCotizacionesDetalle(data)
{
    if (!isEmptyData(data))
    {
//        $('[data-toggle="popover"]').popover('hide');
        var stringTituloModal = '<strong> ' + data[0]['bien_descripcion'] + '</strong>';

        $('#datatableCotizaciones').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "cotizacion_serie_num", "sClass": "alignCenter"},
                {"data": "fecha_emision", "sClass": "alignCenter"},
                {"data": "persona_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "moneda_simbolo", "sClass": "alignCenter"},
                {"data": "precio_unitario", "sClass": "alignRight"},
                {"data": "oc_serie_numero", "sClass": "alignCenter"}
            ],
             columnDefs: [
                {
                    "render": function (data, type, row) {
                        return datex.parserFecha(data);
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        return formatearCantidad(data);
                    },
                    "targets": 3
                },
                {
                    "render": function (data, type, row) {
                        return formatearNumero(data);
                    },
                    "targets": 5
                }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                if (aData.oc_serie_numero != '') {
                    $(nRow).addClass("colorOC");
                }
            },
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloModal);
        $('#modalCotizaciones').modal('show');
    }
    else
    {
        var table = $('#datatableCotizaciones').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontró cotizaciones.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteCotizacionessAtendidosExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteCotizacionessAtendidosExcel");
    ax.addParamTmp("criterios", valoresBusquedaCotizacioness);
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarCotizacioness();
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