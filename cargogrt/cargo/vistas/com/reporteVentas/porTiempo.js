$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorTiempo");
    obtenerConfiguracionesInicialesPorTiempo();
//    modificarAnchoTabla('datatable');
    
    $(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        if (e.target.hash == '#tabla'){
//            $('#datatable').resize();
        }else{
            $("#tortaDolares #tortaDolaresContenedor").resize();
        }
     });
});

function onResponseReportePorTiempo(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorTiempo':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataPorTiempo':
                onResponseGetDataGridPorTiempo(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorTiempo':
                onResponseDocumentoPorTiempo(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorTiempoExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorTiempoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorTiempo()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorTiempo");
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

var valoresBusquedaPorTiempo = [{tienda: "", tiempo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var tiempo = $('#cboTiempo').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaPorTiempo[0].tienda = tienda;
    valoresBusquedaPorTiempo[0].tiempo = tiempo;
    valoresBusquedaPorTiempo[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaPorTiempo[0].tienda))
    {
        cadena += negrita("Tienda: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorTiempo[0].tiempo))
    {
        cadena += negrita("Tiempo: ");
        cadena += select2.obtenerText('cboTiempo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorTiempo[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaPorTiempo[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaPorTiempo[0].fechaEmision.inicio + " - " + valoresBusquedaPorTiempo[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}
function createPieGraph(selector, data) {
    var options = {
        series: {
            pie: {
                show: true
            }
        },
        legend: {
            show: true,
            labelFormatter: labelFormatter,
            backgroundOpacity: 0.2
        },
        grid: {
            hoverable: true,
            clickable: true
        },
        tooltip: true,
        tooltipOpts: {
            defaultTheme: false,
            content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
            shifts: {
                x: 20,
                y: 0
            }
        }
    };

    $.plot($(selector), data, options);
}

function buscarPorTiempo(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorTiempo(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorTiempo()
{
    ax.setAccion("obtenerDataPorTiempo");
    ax.addParamTmp("criterios", valoresBusquedaPorTiempo);
    ax.consumir();
}

function onResponseGetDataGridPorTiempo(data) {
    var total=data.total[0];                
    var datos=data.datos;    

    if (!isEmptyData(datos))
    {
        // Formateamos la data para mostrar la torta
        var dataTortaDolares = [];
        var dataTortaSoles = [];
        $.each(datos, function(index, item){
            dataTortaDolares.push({data: item.total_dolares, label: item.fecha_tiempo +": $."+ parseFloat(item.total_dolares).formatMoney(2, '.', ',')});
            dataTortaSoles.push({data: item.total_soles, label: item.fecha_tiempo+": S/."+ parseFloat(item.total_soles).formatMoney(2, '.', ',')});
        });
        createPieGraph("#tortaDolares #tortaDolaresContenedor", dataTortaDolares);
        createPieGraph("#tortaSoles #tortaSolesContenedor", dataTortaSoles);

        
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoPorTiempo(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/
        $('#datatable').dataTable({
            "order": [],
            "data": datos,            
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_tiempo",  "sClass": "alignLeft"},
                {"data": "numero_soles",  "sClass": "alignRight"},
                {"data": "total_soles",  "sClass": "alignRight"},
                {"data": "numero_dolares",  "sClass": "alignRight"},
                {"data": "total_dolares",  "sClass": "alignRight"}
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
                        if(parseFloat(data).formatMoney(2, '.', ',')=='0.00'){
                            return '-';
                        }else{
                            return parseFloat(data).formatMoney(2, '.', ',');
                        }                     
                    },
                    "targets": [2,4]
                }
            ],
            
            "destroy": true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(1).footer()).html(
                        total.reporte_numero_soles
                        );
                $(api.column(2).footer()).html(
                        'S/. ' + (formatearNumero(total.reporte_total_soles))
                        );
                $(api.column(3).footer()).html(
                        total.reporte_numero_dolares
                        );
                $(api.column(4).footer()).html(
                        '$ ' + (formatearNumero(total.reporte_total_dolares))
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

function verDocumentoPorTiempo(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorTiempo");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorTiempo(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoPorTiempo').dataTable({
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
        var table = $('#datatableDocumentoPorTiempo').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorTiempoExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorTiempoExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorTiempo);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPorTiempo();
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
function labelFormatter(label, series) {
    return "<table><tr><td width=50 style='font-size:8pt; text-align:right;'>"+parseFloat(series.percent).formatMoney(2, '.', ',')+"%: </td><td width=150 style='padding:2px; font-size:8pt; text-align:left;'>"+label+"</td><td width=70 style='font-size:8pt; text-align:right;'>"+parseFloat(series.data[0][1]).formatMoney(2, '.', ',')+"</td></tr></table>"
}