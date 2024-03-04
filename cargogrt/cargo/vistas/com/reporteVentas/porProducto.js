$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorProducto");
    obtenerConfiguracionesInicialesPorProducto();
    modificarAnchoTabla('datatable');
});

function onResponseReportePorProducto(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorProducto':
                onResponseObtenerConfiguracionesIniciales(response.data);
                break;
            case 'obtenerDataPorProducto':
                onResponseGetDataGridPorProducto(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorProducto':
                onResponseDocumentoPorProducto(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorProductoExcel':
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
            case 'obtenerReportePorProductoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorProducto()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorProducto");
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

var valoresBusquedaPorProducto = [{tienda: "", bien: "", bienTipo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var tienda = $('#cboTienda').val();
    var bien = $('#cboBien').val();
    var bienTipo = $('#cboBienTipo').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();
    var bienTipoPadre = $('#cboBienTipoPadre').val();


    valoresBusquedaPorProducto[0].tienda = tienda;
    valoresBusquedaPorProducto[0].bien = bien;
    valoresBusquedaPorProducto[0].bienTipo = bienTipo;
    valoresBusquedaPorProducto[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaPorProducto[0].bienTipoPadre = bienTipoPadre;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaPorProducto[0].tienda))
    {
        cadena += negrita("Tienda: ");
        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorProducto[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorProducto[0].bienTipo))
    {
        cadena += negrita("Grupo de producto: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorProducto[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaPorProducto[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaPorProducto[0].fechaEmision.inicio + " - " + valoresBusquedaPorProducto[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarPorProducto(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorProducto(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorProducto()
{
    ax.setAccion("obtenerDataPorProducto");
    ax.addParamTmp("criterios", valoresBusquedaPorProducto);
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function onResponseGetDataGridPorProducto(data) {
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
                    "targets": [4,6,7]
                }
            ],            
            "destroy": true,
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

function verDocumentoPorProducto(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorProducto");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorProducto(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoPorProducto').dataTable({
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
        var table = $('#datatableDocumentoPorProducto').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorProductoExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorProductoExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorProducto);
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
        buscarPorProducto();
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