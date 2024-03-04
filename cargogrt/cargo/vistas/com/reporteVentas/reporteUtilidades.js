$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteReporteUtilidades");
    obtenerConfiguracionesInicialesReporteUtilidades();
    modificarAnchoTabla('datatable');
});

function onResponseReporteReporteUtilidades(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteUtilidades':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataReporteUtilidades':
                onResponseGetDataGridReporteUtilidades(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoReporteUtilidades':
                onResponseDocumentoReporteUtilidades(response.data);
                loaderClose();
                break;
            case 'obtenerReporteReporteUtilidadesExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteUtilidadesExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteUtilidades()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesReporteUtilidades");
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
    
    var tipoTiempo =
            [
                {id: 1, descripcion: "Día"},
                {id: 2, descripcion: "Mes"},
                {id: 3, descripcion: "Año"}
            ];
    
    select2.cargar("cboTiempo", tipoTiempo, "id", "descripcion");
    select2.asignarValor("cboTiempo",2);
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaReporteUtilidades = [{tienda: "", tiempo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var tiempo = $('#cboTiempo').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaReporteUtilidades[0].tienda = tienda;
    valoresBusquedaReporteUtilidades[0].tiempo = tiempo;
    valoresBusquedaReporteUtilidades[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaReporteUtilidades[0].tienda))
    {
        cadena += negrita("Tienda: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteUtilidades[0].tiempo))
    {
        cadena += negrita("Tiempo: ");
        cadena += select2.obtenerText('cboTiempo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteUtilidades[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaReporteUtilidades[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteUtilidades[0].fechaEmision.inicio + " - " + valoresBusquedaReporteUtilidades[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarReporteUtilidades(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaReporteUtilidades(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaReporteUtilidades()
{
    ax.setAccion("obtenerDataReporteUtilidades");
    ax.addParamTmp("criterios", valoresBusquedaReporteUtilidades);
    ax.consumir();
}

function onResponseGetDataGridReporteUtilidades(data) {

    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoReporteUtilidades(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/
        $('#datatable').dataTable({
           
            "order": [[0, "desc"]],
            "data": data,            
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_tiempo",  "sClass": "alignCenter"},
                {"data": "utilidad_porcentaje_total",  "sClass": "alignRight"},
                {"data": "utilidad_total_soles",  "sClass": "alignRight"},
                {"data": "utilidad_dolares_soles",  "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [1,2,3]
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

function verDocumentoReporteUtilidades(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoReporteUtilidades");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoReporteUtilidades(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoReporteUtilidades').dataTable({
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
        var table = $('#datatableDocumentoReporteUtilidades').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteReporteUtilidadesExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteReporteUtilidadesExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteUtilidades);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarReporteUtilidades();
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