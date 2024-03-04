$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesDispersionBienes();
    //verGrafico();
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesDispersionBienes':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataDispersionBienes':
                //onResponseGetDataGridDispersionBienes(response.data);
                verGrafico(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleDispersionBienes':
                onResponseDetalleDispersionBienes(response.data);
                loaderClose();
                break;
            case 'obtenerReporteDispersionBienesExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteDispersionBienesExcel':
                loaderClose();
                break;
            
            case 'obtenerDataDispersionBienes':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesDispersionBienes()
{
    //alert('hola DB');
    ax.setAccion("obtenerConfiguracionesInicialesDispersionBienes");
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
    
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.documento_tipo)) {
            select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
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

var valoresBusquedaDispersionBienes = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: "", documentoTipo: "", tipoFrecuencia: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();   
    
    var documentoTipo = $('#cboDocumentoTipo').val();    
    var tipoFrecuencia = $('#cboTipoFrecuencia').val();

    valoresBusquedaDispersionBienes[0].organizador = organizadorId;
    valoresBusquedaDispersionBienes[0].bien = bien;
    valoresBusquedaDispersionBienes[0].bienTipo = bienTipo;
    valoresBusquedaDispersionBienes[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaDispersionBienes[0].empresaId = commonVars.empresa;
    valoresBusquedaDispersionBienes[0].documentoTipo = documentoTipo;
    valoresBusquedaDispersionBienes[0].tipoFrecuencia = tipoFrecuencia;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaDispersionBienes[0].tipoFrecuencia))
    {
        cadena += negrita("Tipo frecuencia: ");
        cadena += select2.obtenerText('cboTipoFrecuencia');
        cadena += "<br>";
    }    
    if (!isEmpty(valoresBusquedaDispersionBienes[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaDispersionBienes[0].documentoTipo))
    {
        cadena += negrita("Documento tipo: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaDispersionBienes[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaDispersionBienes[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaDispersionBienes[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaDispersionBienes[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaDispersionBienes[0].fechaEmision.inicio + " - " + valoresBusquedaDispersionBienes[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarDispersionBienes(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaDispersionBienes(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaDispersionBienes()
{
    ax.setAccion("obtenerDataDispersionBienes");
    ax.addParamTmp("criterios", valoresBusquedaDispersionBienes);
    ax.consumir();
}

function onResponseGetDataGridDispersionBienes(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleEntradaSalidaAlmacen(' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
            "scrollX": true,
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
        buscarDispersionBienes();
    }
    loaderClose();
}

function verDetalleDispersionBienes(organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleDispersionBienes");// commonVars.empresa
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("id_empresa",  commonVars.empresa);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleDispersionBienes(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['organizador_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableDispersionBienes').dataTable({
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
        var table = $('#datatableDispersionBienes').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteDispersionBienesExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteDispersionBienesExcel");
    ax.addParamTmp("criterios", valoresBusquedaDispersionBienes);
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
        buscarDispersionBienes(0);
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
    createLineChart('graficoDispersionBienes', $data, 'y', ['a', 'b','c'], ['Series A', 'Series B', 'Series C'], ['#1a2942', '#3bc0c3', '#dcdcdc']);

}

function verGrafico(data) {   
    $('#divGrafico').show();
    document.getElementById("graficoDispersionBienes").innerHTML="";
    document.getElementById("divLeyenda").innerHTML="";
    
    //var $dataGrafico = [{ y: null, a: null, b: null , c: null , d:null, e:null }];
    
    var $a='';
    var $b='';
    var $c='';
    var $d='';
    var $e='';
    
    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            if(item['bien_descripcion_1']!=''){
                $a=item['bien_descripcion_1'];
            }
            if(item['bien_descripcion_2']!=''){
                $b=item['bien_descripcion_2'];
            }
            if(item['bien_descripcion_3']!=''){
                $c=item['bien_descripcion_3'];
            }
            if(item['bien_descripcion_4']!=''){
                $d=item['bien_descripcion_4'];
            }
            if(item['bien_descripcion_5']!=''){
                $e=item['bien_descripcion_5'];
            }
        });
        
        if($a!='' && $b!='' && $c!='' && $d!='' && $e!=''){
            var $dataGrafico = [{ y: null, a: null, b: null , c: null , d:null, e:null }];
            $.each(data, function (index, item) {
                $dataGrafico[index] = { y: item['fecha_emision'], a: item['frecuencia_1'], b: item['frecuencia_2'] , c: item['frecuencia_3'] , d:item['frecuencia_4'] , e: item['frecuencia_5'] };            
            });
            createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a', 'b', 'c', 'd', 'e'], [$a, $b, $c, $d, $e], ['#1a2942', '#1ca8dd', '#E8BA2F', '#cb2a2a', '#088A08']);
            
            var html='<i class="ion-arrow-right-a" style="color:#1a2942;"></i>&nbsp;'+$a+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#1ca8dd;"></i>&nbsp;'+$b+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#E8BA2F;"></i>&nbsp;'+$c+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#cb2a2a;"></i>&nbsp;'+$d+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#088A08;"></i>&nbsp;'+$e+'&nbsp;&nbsp;&nbsp;';
        }       
        if($a!='' && $b!='' && $c!='' && $d!='' && $e==''){
            var $dataGrafico = [{ y: null, a: null, b: null , c: null , d:null}];
            $.each(data, function (index, item) {
                $dataGrafico[index] = { y: item['fecha_emision'], a: item['frecuencia_1'], b: item['frecuencia_2'] , c: item['frecuencia_3'] , d:item['frecuencia_4'] };            
            });
            createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a', 'b', 'c', 'd'], [$a, $b, $c, $d], ['#1a2942', '#1ca8dd', '#E8BA2F', '#cb2a2a']);
            
            var html='<i class="ion-arrow-right-a" style="color:#1a2942;"></i>&nbsp;'+$a+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#1ca8dd;"></i>&nbsp;'+$b+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#E8BA2F;"></i>&nbsp;'+$c+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#cb2a2a;"></i>&nbsp;'+$d+'&nbsp;&nbsp;&nbsp;';
        }
        if($a!='' && $b!='' && $c!='' && $d=='' && $e==''){
            var $dataGrafico = [{ y: null, a: null, b: null , c: null}];
            $.each(data, function (index, item) {
                $dataGrafico[index] = { y: item['fecha_emision'], a: item['frecuencia_1'], b: item['frecuencia_2'] , c: item['frecuencia_3']  };            
            });
            createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a', 'b', 'c'], [$a, $b, $c], ['#1a2942', '#1ca8dd', '#E8BA2F']);
            
            var html='<i class="ion-arrow-right-a" style="color:#1a2942;"></i>&nbsp;'+$a+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#1ca8dd;"></i>&nbsp;'+$b+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#E8BA2F;"></i>&nbsp;'+$c+'&nbsp;&nbsp;&nbsp;';
        }
        if($a!='' && $b!='' && $c=='' && $d=='' && $e==''){
            var $dataGrafico = [{ y: null, a: null, b: null }];
            $.each(data, function (index, item) {
                $dataGrafico[index] = { y: item['fecha_emision'], a: item['frecuencia_1'], b: item['frecuencia_2'] };            
            });
            createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a', 'b'], [$a, $b], ['#1a2942', '#1ca8dd']);
            
            var html='<i class="ion-arrow-right-a" style="color:#1a2942;"></i>&nbsp;'+$a+'&nbsp;&nbsp;&nbsp;';
            html=html+'<i class="ion-arrow-right-a" style="color:#1ca8dd;"></i>&nbsp;'+$b+'&nbsp;&nbsp;&nbsp;';
        }
        if($a!='' && $b=='' && $c=='' && $d=='' && $e==''){
            var $dataGrafico = [{ y: null, a: null}];
            $.each(data, function (index, item) {
                $dataGrafico[index] = { y: item['fecha_emision'], a: item['frecuencia_1'] };            
            });
            createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a'], [$a], ['#1a2942']);
            
            var html='<i class="ion-arrow-right-a" style="color:#1a2942;"></i>&nbsp;'+$a+'&nbsp;&nbsp;&nbsp;';
        }        
    } 
    
    
//    createLineChart('graficoDispersionBienes', $dataGrafico, 'y', ['a', 'b','c','d','e'], [$a,$b,$c,$d,$e], ['#1a2942', '#1ca8dd', '#E8BA2F', '#cb2a2a', '#088A08']);
    
    
    $("#divLeyenda").append(html);
}