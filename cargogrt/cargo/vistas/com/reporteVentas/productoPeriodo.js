$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
//    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteProductoPorPeriodo");
    obtenerConfiguracionesInicialesProductoPorPeriodo();
    modificarAnchoTabla('datatable');
});

function onResponseReporteProductoPorPeriodo(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesProductoPorPeriodo':
                onResponseObtenerConfiguracionesIniciales(response.data);
                break;
            case 'obtenerDataProductoPorPeriodo':
                onResponseGetDataGridProductoPorPeriodo(response.data);
                loaderClose();
                break;
            case 'obtenerReporteProductoPorPeriodoExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'obtenerBienTipoHijo':
                onResponseObtenerBienTipoHijo(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteProductoPorPeriodoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesProductoPorPeriodo()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesProductoPorPeriodo");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    
    var periodo = [  {id: 1, descripcion: "Mensual"},
                     {id: 2, descripcion: "Anual"}
                 ];
    
    select2.cargar("cboPeriodo", periodo, "id", "descripcion");
    select2.asignarValor("cboPeriodo",1);
    
    //alert('reporte servicio');
    if (!isEmpty(data.fecha_primer_documento)) {
        if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha'])) {
            $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
            if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual'])) {
                $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
            }
        }
    }
    
    if (!isEmpty(data.bien_tipo)) {
        select2.cargar("cboBienTipoPadre", data.bien_tipo, "id", ["codigo","descripcion"]);
        select2.asignarValor("cboBienTipoPadre",null);
    }
    
    if (!isEmpty(data.bien)) {
        select2.cargar("cboBien", data.bien, "id",["codigo","descripcion"]);
    }
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaProductoPorPeriodo = [{tienda: "", bien: "", bienTipo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var bien = $('#cboBien').val();
    var bienTipo = $('#cboBienTipo').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();
    var bienTipoPadre = $('#cboBienTipoPadre').val();
    var periodo=select2.obtenerValor('cboPeriodo');

    valoresBusquedaProductoPorPeriodo[0].tienda = tienda;
    valoresBusquedaProductoPorPeriodo[0].bien = bien;
    valoresBusquedaProductoPorPeriodo[0].bienTipo = bienTipo;
    valoresBusquedaProductoPorPeriodo[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaProductoPorPeriodo[0].bienTipoPadre = bienTipoPadre;
    valoresBusquedaProductoPorPeriodo[0].periodo = periodo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaProductoPorPeriodo[0].periodo))
    {
        cadena += negrita("Periodo: ");
        cadena += select2.obtenerText('cboPeriodo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductoPorPeriodo[0].tienda))
    {
        cadena += negrita("Tienda: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductoPorPeriodo[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductoPorPeriodo[0].bienTipo))
    {
        cadena += negrita("Grupo de producto: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaProductoPorPeriodo[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaProductoPorPeriodo[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaProductoPorPeriodo[0].fechaEmision.inicio + " - " + valoresBusquedaProductoPorPeriodo[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarProductoPorPeriodo(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaProductoPorPeriodo(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaProductoPorPeriodo()
{
    ax.setAccion("obtenerDataProductoPorPeriodo");
    ax.addParamTmp("criterios", valoresBusquedaProductoPorPeriodo);
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function onResponseGetDataGridProductoPorPeriodo(data) {
//    console.log(data);
    if (!isEmptyData(data))
    {
        var totalSoles = 0;
        var totalDolares = 0;
        $.each(data, function (index, item) {
            totalSoles = totalSoles + parseFloat(item.importe_total_soles);
            totalDolares = totalDolares + parseFloat(item.importe_total_dolares);
        });
        
        $('#datatable').dataTable({
          
            "order": [[1, "asc"]],
            "data": data,     
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_codigo"},
                {"data": "bien_descripcion"},
                {"data": "fecha_tiempo",  "sClass": "alignCenter"},
                {"data": "bien_tipo_padre_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "cantidad_conv",  "sClass": "alignRight"},
                {"data": "unidad_control"},
                {"data": "importe_total_soles",  "sClass": "alignRight"},
                {"data": "importe_total_dolares",  "sClass": "alignRight"}
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
                    "targets": [5,7,8]
                }
            ],            
            "destroy": true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(7).footer()).html(
                        'S/. ' + (formatearNumero(totalSoles))
                        );
                $(api.column(8).footer()).html(
                        '$ ' + (formatearNumero(totalDolares))
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

var actualizandoBusqueda = false;
function exportarReporteProductoPorPeriodoExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteProductoPorPeriodoExcel");
    ax.addParamTmp("criterios", valoresBusquedaProductoPorPeriodo);
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
        buscarProductoPorPeriodo();
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