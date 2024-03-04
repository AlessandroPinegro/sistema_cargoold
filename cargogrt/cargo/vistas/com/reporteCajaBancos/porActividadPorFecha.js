$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorActividadPorFecha");
    obtenerConfiguracionesInicialesPorActividadPorFecha();
});

function onResponseReportePorActividadPorFecha(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorActividadPorFecha':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataPorActividadPorFecha':
                onResponseGetDataGridPorActividadPorFecha(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorActividadPorFecha':
                onResponseDocumentoPorActividadPorFecha(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorActividadPorFechaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorActividadPorFechaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorActividadPorFecha()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorActividadPorFecha");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    
    if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
    {
        $('#fechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
    }
        
    if (!isEmpty(data.actividad_tipo)) {
        select2.cargar("cboActividadTipo", data.actividad_tipo, "id", "descripcion");
    }
    
    if (!isEmpty(data.actividad)) {
        select2.cargar("cboActividad", data.actividad, "id", ["codigo","descripcion"]);
    }
    
//    if (!isEmpty(data.empresa)) {
//        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
//    }
    loaderClose();
}

var valoresBusquedaPorActividadPorFecha = [{fechaEmision: "",tienda: "", actividad: "", actividadTipo: ""}];

function cargarDatosBusqueda()
{

//    var tienda = $('#cboTienda').val();
    var actividad = $('#cboActividad').val();
    var actividadTipo = $('#cboActividadTipo').val();
    var fechaEmision=$('#fechaEmision').val();

    valoresBusquedaPorActividadPorFecha[0].fechaEmision = fechaEmision;
    valoresBusquedaPorActividadPorFecha[0].tienda = commonVars.empresa;
    valoresBusquedaPorActividadPorFecha[0].actividad = actividad;
    valoresBusquedaPorActividadPorFecha[0].actividadTipo = actividadTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaPorActividadPorFecha[0].fechaEmision))
    {
        cadena += StringNegrita("Fecha pago: ");
        cadena += valoresBusquedaPorActividadPorFecha[0].fechaEmision;
        cadena += "<br>";
    }
//    if (!isEmpty(valoresBusquedaPorActividadPorFecha[0].tienda))
//    {
//        cadena += negrita("Tienda: ");
//        cadena += select2.obtenerTextMultiple('cboTienda');
//        cadena += "<br>";
//    }
    if (!isEmpty(valoresBusquedaPorActividadPorFecha[0].actividad))
    {
        cadena += negrita("Actividad: ");
        cadena += select2.obtenerTextMultiple('cboActividad');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorActividadPorFecha[0].actividadTipo))
    {
        cadena += negrita("Actividad Tipo: ");
        cadena += select2.obtenerTextMultiple('cboActividadTipo');
        cadena += "<br>";
    }
    
    return cadena;
}

function buscarPorActividadPorFecha(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorActividadPorFecha(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorActividadPorFecha()
{
    ax.setAccion("obtenerDataPorActividadPorFecha");
    ax.addParamTmp("criterios", valoresBusquedaPorActividadPorFecha);
    ax.consumir();
}

function onResponseGetDataGridPorActividadPorFecha(data) {
    
    var total=data.total;
    if (total === null) {
        total = 0;
    }
                
    var datos=data.datos;
    
    if (!isEmptyData(datos))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoPorActividadPorFecha(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/          
        $('#datatable').dataTable({
          
            "bPaginate": false,
            "order": [[0, "asc"]],
            "scrollX": true,
            "autoWidth": true,
            "data": datos,            
            "columns": [
                {"data": "codigo_actividad", "width": '120px'},
                {"data": "actividad_tipo_descripcion", "width": '300px'},
                {"data": "actividad_descripcion", "width": '400px'},
                {"data": "total",  "sClass": "alignRight", "width": '180px'}
            ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 3
            }
        ],
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(3).footer()).html(
                        'S/. ' + (formatearNumero(total))
                        );
            }
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

function verDocumentoPorActividadPorFecha(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorActividadPorFecha");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorActividadPorFecha(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoPorActividadPorFecha').dataTable({
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
        var table = $('#datatableDocumentoPorActividadPorFecha').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorActividadPorFechaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorActividadPorFechaExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorActividadPorFecha);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPorActividadPorFecha();
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