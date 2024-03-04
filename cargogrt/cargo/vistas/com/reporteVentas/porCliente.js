var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var totalSoles;
var totalDolares;
var total_cantidad;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
//    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseVentasPorCliente");
    obtenerConfiguracionesInicialesVentasPorCliente();
    modificarAnchoTabla('dataTableVentasPorCliente');
    
    $(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        if (e.target.hash == '#tabla'){
            $('#dataTableVentasPorCliente').resize();
        }else{
            $("#tortaDolares #tortaDolaresContenedor").resize();
        }
     });
});

function onResponseVentasPorCliente(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesVentasPorCliente':
                onResponseObtenerConfiguracionesInicialesVentasPorCliente(response.data);
                break;
            case 'obtenerCantidadesTotalesVentasPorCliente':  
                totalSoles = response.data.totalSoles;
                totalDolares = response.data.totalDolares;
                
                obtenerGraficoClientesDolares(totalDolares);
                obtenerGraficoProductosDolares(totalDolares);
//                obtenerGraficoClientesSoles(totalSoles);
//                obtenerGraficoProductosSoles(totalSoles);
                
                getDataTableVentasPorCliente();
                break;
            case 'obtenerReporteVentasPorClienteExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'verDetallePorCliente':
                onResponseVerDetallePorCliente(response.data);
                loaderClose();
                break;
            case 'obtenerBienTipoHijo':
                onResponseObtenerBienTipoHijo(response.data);
                loaderClose();
                break;
            case 'reportePorClienteObtenerGraficoClientesDolares':
                var dataTorta = [];
                if (!isEmptyData(response.data))
                {
                    // Formateamos la data para mostrar la torta
                    $.each(response.data, function(index, item){
                        dataTorta.push({data: item.total, label: item.persona_nombre_completo});
                    });
                }
                createPieGraph("#tortaDolares #tortaDolaresContenedor", dataTorta);
                break;
            case 'reportePorClienteObtenerGraficoClientesSoles':
                var dataTorta = [];
                if (!isEmptyData(response.data))
                {
                    // Formateamos la data para mostrar la torta
                    $.each(response.data, function(index, item){
                        dataTorta.push({data: item.total, label: item.persona_nombre_completo});
                    });
                }
                createPieGraph("#tortaSoles #tortaSolesContenedor", dataTorta);
                break;
            case 'reportePorClienteObtenerGraficoProductosDolares':
                var dataTorta = [];
                if (!isEmptyData(response.data))
                {
                    // Formateamos la data para mostrar la torta
                    $.each(response.data, function(index, item){
                        dataTorta.push({data: item.total, label: item.bien_tipo_padre_descripcion + '-' + item.bien_tipo_descripcion});
                    });
                }
                createPieGraph("#tortaDolaresProductos #tortaDolaresProductosContenedor", dataTorta);
                break;
            case 'reportePorClienteObtenerGraficoProductosSoles':
                var dataTorta = [];
                if (!isEmptyData(response.data))
                {
                    // Formateamos la data para mostrar la torta
                    $.each(response.data, function(index, item){
                        dataTorta.push({data: item.total, label: item.bien_tipo_padre_descripcion + '-' + item.bien_tipo_descripcion});
                    });
                }
                createPieGraph("#tortaSolesProductos #tortaSolesProductosContenedor", dataTorta);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteVentasPorClienteExcel':
                loaderClose();
                break;
            case 'verDetallePorCliente':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesVentasPorCliente()
{
    ax.setAccion("obtenerConfiguracionesInicialesVentasPorCliente");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesVentasPorCliente(data) {

    var string = '<option selected value="-1">Seleccionar un cliente</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersona').append(string);
        select2.asignarValor('cboPersona', "-1");
    }
    
    if (!isEmpty(data.documento_tipo)) {
//        var string = '<option selected value="-1">Seleccionar un</option>';
        var stringDocumento = '';
        $.each(data.documento_tipo, function (indexDocumento, itemDocumneto) {
            stringDocumento += '<option value="' + itemDocumneto.id + '">' + itemDocumneto.descripcion + '</option>';
        });
        $('#cboTipoDocumentoMP').append(stringDocumento);
//        select2.asignarValor('cboTipoDocumentoMP', "-1");
    }    
    
    var string ='';
    if (!isEmpty(data.empresa)) {
        $.each(data.empresa, function (indexEmpresa, itemEmpresa) {
            string += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
        });
        $('#cboEmpresa').append(string);
        //select2.asignarValor('cboTienda', "-1");
    }
    
    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
    {
        $('#inicioFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
        {
            $('#finFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
        }
    }
    
    if (!isEmpty(data.bien_tipo)) {
        select2.cargar("cboBienTipoPadre", data.bien_tipo, "id", ["codigo","descripcion"]);
        select2.asignarValor("cboBienTipoPadre",null);
    }
                    
    loaderClose();
}

var valoresBusquedaVentasPorCliente = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaVentasPorCliente()
{
    var personaId = $('#cboPersona').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();
    var empresaId = $('#cboEmpresa').val();
    var bienTipo = $('#cboBienTipo').val();
    var bienTipoPadre = $('#cboBienTipoPadre').val();

    valoresBusquedaVentasPorCliente[0].persona = personaId;
    valoresBusquedaVentasPorCliente[0].documentoTipo = documentoTipoId;
    valoresBusquedaVentasPorCliente[0].empresa = empresaId;
    valoresBusquedaVentasPorCliente[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaVentasPorCliente[0].fechaEmisionHasta = fechaEmisionFin;
    valoresBusquedaVentasPorCliente[0].bienTipo = bienTipo;
    valoresBusquedaVentasPorCliente[0].bienTipoPadre = bienTipoPadre;

}
function buscarVentasPorCliente(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesVentasPorCliente();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;
    
    if (colapsa === 1)
        colapsarBuscador();
    
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusquedaVentasPorCliente();
    
    if (!isEmpty(valoresBusquedaVentasPorCliente[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorCliente[0].empresa))
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (select2.obtenerValor('cboPersona')!=-1)
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorCliente[0].bienTipo))
    {
        cadena += StringNegrita("Grupo de producto: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorCliente[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaVentasPorCliente[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaVentasPorCliente[0].fechaEmisionDesde + " - " + valoresBusquedaVentasPorCliente[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableVentasPorCliente() {
    color = '';
    ax.setAccion("obtenerDataVentasPorCliente");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    $('#dataTableVentasPorCliente').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[7, "desc"]],  
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Total
//            {"data": "fecha_creacion"},
            {"data": "persona_nombre_completo"},
            {"data": "bien_tipo_padre_descripcion"},
            {"data": "bien_tipo_descripcion"},
            {"data": "fecha_emision"},
            {"data": "documento_tipo_descripcion"},
            {"data": "serie_numero", "class": "alignCenter"},
            {"data": "total_soles", "class": "alignRight"},
            {"data": "total_dolares", "class": "alignRight"},
            {"data": "acciones", "class": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    if(parseFloat(data).formatMoney(2, '.', ',')=='0.00'){
                        return '-';
                    }else{
                        return parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": [6,7]
            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": 3
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(6).footer()).html(
                    'S/. ' + (formatearNumero(totalSoles))
                    );
            $(api.column(7).footer()).html(
                    '$ ' + (formatearNumero(totalDolares))
                    );
        }
    });
    loaderClose();
}

var actualizandoBusqueda = false;
function loaderBuscarVentas()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarVentasPorCliente();
    }
}
function cerrarPopover()
{
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        }
        else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else
    {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
}

function obtenerCantidadesTotalesVentasPorCliente()
{
    ax.setAccion("obtenerCantidadesTotalesVentasPorCliente");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.consumir();
}

function obtenerGraficoClientesDolares(sumatoria)
{
    ax.setAccion("reportePorClienteObtenerGraficoClientesDolares");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.addParamTmp("sumatoria", sumatoria);
    ax.consumir();
}
function obtenerGraficoClientesSoles(sumatoria)
{
    ax.setAccion("reportePorClienteObtenerGraficoClientesSoles");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.addParamTmp("sumatoria", sumatoria);
    ax.consumir();
}
function obtenerGraficoProductosDolares(sumatoria)
{
    ax.setAccion("reportePorClienteObtenerGraficoProductosDolares");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.addParamTmp("sumatoria", sumatoria);
    ax.consumir();
}
function obtenerGraficoProductosSoles(sumatoria)
{
    ax.setAccion("reportePorClienteObtenerGraficoProductosSoles");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.addParamTmp("sumatoria", sumatoria);
    ax.consumir();
}


//function imprimir(muestra)
//{
//    var ficha = document.getElementById(muestra);
//    var ventimp = window.open(' ', 'popimpr');
//    ventimp.document.write(ficha.innerHTML);
//    ventimp.document.close();
//    ventimp.print();
//    ventimp.close();
//}

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

function exportarReporteVentasPorCliente()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaVentasPorCliente();
    ax.setAccion("obtenerReporteVentasPorClienteExcel");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorCliente);
    ax.consumir();
}

// ver detalle en modal
function verDetallePorCliente(documentoId, movimientoId)
{
    loaderShow();
    ax.setAccion("verDetallePorCliente");
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.consumir();

//    
}

function onResponseVerDetallePorCliente(data)
{    
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data){
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);


    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');
                        
            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor))
            {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
//                    case 2:
                    case 3:
                        valor = fechaArmada(valor);
                        break;
//                    case 4:
//                    case 5:
//                    case 6:
//                    case 7:
//                    case 8:
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
//                    case 12:
//                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 19:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
//            "scrollX": true,
            "order": [[2, "asc"]],
            "data": data,
            "columns": [
//                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
                {"data": "precioUnitario", "sClass": "alignRight"},
                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

// bien padre e hijos
function obtenerBienTipoHijo(){    
    var bienTipoPadreId = $('#cboBienTipoPadre').val();
    
    loaderShow();
    ax.setAccion("obtenerBienTipoHijo");
    ax.addParamTmp("bienTipoPadreId", bienTipoPadreId);
    ax.consumir();
}

function onResponseObtenerBienTipoHijo(data){
    select2.cargar("cboBienTipo", data, "id", ["codigo","descripcion"]);
    select2.asignarValor("cboBienTipo",null);
}

function createPieGraph(selector, data) {
    var options;
    if (isEmpty(data)){
        options = {
        };
        
        $.plot($(selector), options).shutdown();
        $(".flot-base").remove();
        $(".flot-overlay").remove();
        $("#flotTip").remove();
        return;
    }else{
        options = {
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
}
function labelFormatter(label, series) {
    return "<table><tr><td width=50 style='font-size:8pt; text-align:right;'>"+parseFloat(series.percent).formatMoney(2, '.', ',')+"%: </td><td width=150 style='padding:2px; font-size:8pt; text-align:left;'>"+label+"</td><td width=70 style='font-size:8pt; text-align:right;'>"+parseFloat(series.data[0][1]).formatMoney(2, '.', ',')+"</td></tr></table>"
}