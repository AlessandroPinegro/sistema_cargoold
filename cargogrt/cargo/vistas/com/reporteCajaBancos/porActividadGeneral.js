$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorActividad");
    obtenerConfiguracionesInicialesPorActividad();
});

function onResponseReportePorActividad(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorActividad':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataPorActividad':
                onResponseGetDataGridPorActividad(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorActividad':
                onResponseDocumentoPorActividad(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorActividadExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorActividadExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorActividad()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorActividad");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    var dataMes = [ {id: 1, descripcion: "Enero"},
                    {id: 2, descripcion: "Febrero"},
                    {id: 3, descripcion: "Marzo"},
                    {id: 4, descripcion: "Abril"},
                    {id: 5, descripcion: "Mayo"},
                    {id: 6, descripcion: "Junio"},
                    {id: 7, descripcion: "Julio"},
                    {id: 8, descripcion: "Agosto"},
                    {id: 9, descripcion: "Setiembre"},
                    {id: 10, descripcion: "Octubre"},
                    {id: 11, descripcion: "Noviembre"},
                    {id: 12, descripcion: "Diciembre"}
                  ];
    
    select2.cargar("cboMes", dataMes, "id", "descripcion");
    var hoy = new Date();
    var mm = hoy.getMonth()+1; //hoy es 0!       
    var anioActual = hoy.getFullYear();
    
    select2.asignarValor("cboMes",mm);
    
    
    //anio
    var fechaPrimera=data.fecha_primer_documento[0]['primera_fecha'];
    var fechaPartes = fechaPrimera.split("-");
    var anioInicial=parseInt(fechaPartes[0]);
    
    var string ='';
    
    for (var i = 0; i <= (anioActual - anioInicial); i++)
    {
        string += '<option value="' + (anioInicial + i) + '">' + (anioInicial + i) + '</option>';
    }
    $('#cboAnio').append(string);
    
    select2.asignarValor("cboAnio",anioActual);
    //fin anio
        
    if (!isEmpty(data.actividad_tipo)) {
        select2.cargar("cboActividadTipo", data.actividad_tipo, "id", "descripcion");
    }
    
    if (!isEmpty(data.actividad)) {
        select2.cargar("cboActividad", data.actividad, "id", ["codigo","descripcion"]);
    }
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaPorActividad = [{mes: "",anio: "",tienda: "", actividad: "", actividadTipo: ""}];

function cargarDatosBusqueda()
{

    var tienda = select2.obtenerValor("cboTienda");
    var actividad = $('#cboActividad').val();
    var actividadTipo = $('#cboActividadTipo').val();
    var mes = $('#cboMes').val();
    var anio = $('#cboAnio').val();

    valoresBusquedaPorActividad[0].mes = mes;
    valoresBusquedaPorActividad[0].anio = anio;
    valoresBusquedaPorActividad[0].tienda = tienda;
    valoresBusquedaPorActividad[0].actividad = actividad;
    valoresBusquedaPorActividad[0].actividadTipo = actividadTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaPorActividad[0].mes))
    {
        cadena += negrita("Mes: ");
        cadena += select2.obtenerText('cboMes');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorActividad[0].anio))
    {
        cadena += negrita("Anio: ");
        cadena += select2.obtenerText('cboAnio');
        cadena += "<br>";
    }
//    if (!isEmpty(valoresBusquedaPorActividad[0].tienda))
//    {
//        cadena += negrita("Tienda: ");
//        cadena += select2.obtenerTextMultiple('cboTienda');
//        cadena += "<br>";
//    }
    if (!isEmpty(valoresBusquedaPorActividad[0].actividad))
    {
        cadena += negrita("Actividad: ");
        cadena += select2.obtenerTextMultiple('cboActividad');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorActividad[0].actividadTipo))
    {
        cadena += negrita("Actividad Tipo: ");
        cadena += select2.obtenerTextMultiple('cboActividadTipo');
        cadena += "<br>";
    }
    
    return cadena;
}

function buscarPorActividad(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorActividad(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorActividad()
{
    ax.setAccion("obtenerDataPorActividad");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function onResponseGetDataGridPorActividad(data) {
    
    var total=data.total;
    if (total === null) {
        total = 0;
    }
                
    var datos=data.datos;
    
    if (!isEmptyData(datos))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoPorActividad(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
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

function verDocumentoPorActividad(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorActividad");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorActividad(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoPorActividad').dataTable({
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
        var table = $('#datatableDocumentoPorActividad').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorActividadExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorActividadExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPorActividad();
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