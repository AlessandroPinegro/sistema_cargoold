$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteEstadisticoVentas");
    obtenerConfiguracionesInicialesReporteEstadisticoVentas();
    //verGrafico();
});

function onResponseReporteEstadisticoVentas(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteEstadisticoVentas':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataReporteEstadisticoVentas':
                //onResponseGetDataGridReporteEstadisticoVentas(response.data);
                verGrafico(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleReporteEstadisticoVentas':
                onResponseDetalleReporteEstadisticoVentas(response.data);
                loaderClose();
                break;
            case 'obtenerReporteReporteEstadisticoVentasExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteEstadisticoVentasExcel':
                loaderClose();
                break;
            
            case 'obtenerDataReporteEstadisticoVentas':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteEstadisticoVentas()
{
    //alert('hola DB');
    ax.setAccion("obtenerConfiguracionesInicialesReporteEstadisticoVentas");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    var tipoFrecuencia = [  {id: 1, descripcion: "Diario"},
                            {id: 2, descripcion: "Mensual"},
                            {id: 3, descripcion: "Anual"}
                          ];
    
    select2.cargar("cboTipoFrecuencia", tipoFrecuencia, "id", "descripcion");
    select2.asignarValor("cboTipoFrecuencia",1);
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");
        if (!isEmpty(data.documento_tipo)) {
            select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
            if (!isEmpty(data.bien)) {
                select2.cargar("cboBien", data.bien, "id", ["codigo","descripcion"]);
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

var valoresBusquedaReporteEstadisticoVentas = [{empresa: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: "", documentoTipo: "", tipoFrecuencia: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var empresaId = $('#cboEmpresa').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();   
    
    var documentoTipo = $('#cboDocumentoTipo').val();    
    var tipoFrecuencia = $('#cboTipoFrecuencia').val();

    valoresBusquedaReporteEstadisticoVentas[0].empresa = empresaId;
    valoresBusquedaReporteEstadisticoVentas[0].bien = bien;
    valoresBusquedaReporteEstadisticoVentas[0].bienTipo = bienTipo;
    valoresBusquedaReporteEstadisticoVentas[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaReporteEstadisticoVentas[0].documentoTipo = documentoTipo;
    valoresBusquedaReporteEstadisticoVentas[0].tipoFrecuencia = tipoFrecuencia;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].tipoFrecuencia))
    {
        cadena += negrita("Tipo frecuencia: ");
        cadena += select2.obtenerText('cboTipoFrecuencia');
        cadena += "<br>";
    }    
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].empresa))
    {
        cadena += negrita("Empresa: ");
        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].documentoTipo))
    {
        cadena += negrita("Documento tipo: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteEstadisticoVentas[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaReporteEstadisticoVentas[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteEstadisticoVentas[0].fechaEmision.inicio + " - " + valoresBusquedaReporteEstadisticoVentas[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarReporteEstadisticoVentas(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaReporteEstadisticoVentas(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaReporteEstadisticoVentas()
{
    ax.setAccion("obtenerDataReporteEstadisticoVentas");
    ax.addParamTmp("criterios", valoresBusquedaReporteEstadisticoVentas);
    ax.consumir();
}

function onResponseGetDataGridReporteEstadisticoVentas(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleEntradaSalidaAlmacen(' + item['empresa_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
           
            "order": [[0, "asc"]],
            "data": data,            
            "columns": [
                {"data": "fecha_emision"},
                {"data": "bien_descripcion"},
                {"data": "frecuencia", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                    },
                    "targets": 0
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
        buscarReporteEstadisticoVentas();
    }
    loaderClose();
}

function verDetalleReporteEstadisticoVentas(empresaId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleReporteEstadisticoVentas");// commonVars.empresa
    ax.addParamTmp("id_empresa", empresaId);
    ax.addParamTmp("id_empresa",  commonVars.empresa);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleReporteEstadisticoVentas(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['empresa_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableReporteEstadisticoVentas').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "empresa_descripcion"},
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
        var table = $('#datatableReporteEstadisticoVentas').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteReporteEstadisticoVentasExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteReporteEstadisticoVentasExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteEstadisticoVentas);
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
        buscarReporteEstadisticoVentas(0);
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


// Grafico
function createLineChart(element, data, xkey, ykeys, labels, lineColors) {
    //alert('crar Linea');
    //alert(element);    
    
    var tipoFrecuencia=$('#cboTipoFrecuencia').val();
    var tipoxLabels="day";
    if(tipoFrecuencia==2){
        tipoxLabels="month";
    }
    if(tipoFrecuencia==3){
        tipoxLabels="year";
    }
    
    Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          resize: true, //defaulted to true
          lineColors: lineColors,
          xLabels:tipoxLabels
        });
}

function verGraficoEjemplo(data) {   
    
    alert('grafico');
    
    //create line chart
    var $data  = [
            { y: '2009', a: 100, b: 90 , c: 75 },
            { y: '2010', a: 75,  b: 65 , c: 50 },
            { y: '2011', a: 50,  b: 40 , c: 30 },
            { y: '2012', a: 75,  b: 65 , c: 50 },
            { y: '2013', a: 50,  b: 40 , c: 22 },
            { y: '2014', a: 75,  b: 65 , c: 50 },
            { y: '2015', a: 100, b: 90 , c: 65 }
          ];
    createLineChart('graficoReporteEstadisticoVentas', $data, 'y', ['a', 'b','c'], ['Series A', 'Series B', 'Series C'], ['#1a2942', '#3bc0c3', '#dcdcdc']);

}

function verGrafico(data) {
    $('#divGrafico').show();
    document.getElementById("graficoReporteEstadisticoVentas").innerHTML="";
    document.getElementById("divLeyenda").innerHTML="";
    
    //var dataGrafico = [{ y: null, a: null, b: null , c: null , d:null, e:null }];
    
    var serieA='Número de ventas';
    
    if (!isEmptyData(data))
    {
        var dataGrafico = [{y: null, a: null}];
        $.each(data, function (index, item) {
            dataGrafico[index] = {y: item['fecha_tiempo'], a: item['numero_ventas']};
        });
        createLineChart('graficoReporteEstadisticoVentas', dataGrafico, 'y', ['a'], [serieA], ['#088A08']);

        var html = '<i class="ion-arrow-right-a" style="color:#088A08;"></i>&nbsp;' + serieA + '&nbsp;&nbsp;&nbsp;';

    }
    
    $("#divLeyenda").append(html);
}