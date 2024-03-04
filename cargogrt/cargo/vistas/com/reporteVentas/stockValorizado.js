$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteStockValorizado");
    obtenerConfiguracionesInicialesStockValorizado();
    modificarAnchoTabla('datatable');
});

function onResponseReporteStockValorizado(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesStockValorizado':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataStockValorizado':
                onResponseGetDataGridStockValorizado(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoStockValorizado':
                onResponseDocumentoStockValorizado(response.data);
                loaderClose();
                break;
            case 'obtenerReporteStockValorizadoExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteStockValorizadoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesStockValorizado()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesStockValorizado");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {   
    
    if (!isEmpty(data.bien_tipo)) {
        select2.cargar("cboBienTipo", data.bien_tipo, "id", "descripcion");
    }
    
    if (!isEmpty(data.bien)) {
        select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
    }
    
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
    }
    loaderClose();
}

var valoresBusquedaStockValorizado = [{organizador: "", bien: "", bienTipo: ""}];

function cargarDatosBusqueda()
{

    var organizador = $('#cboOrganizador').val();
    var bien = $('#cboBien').val();
    var bienTipo = $('#cboBienTipo').val();


    valoresBusquedaStockValorizado[0].organizador = organizador;
    valoresBusquedaStockValorizado[0].bien = bien;
    valoresBusquedaStockValorizado[0].bienTipo = bienTipo;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaStockValorizado[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaStockValorizado[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaStockValorizado[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    return cadena;
}

function buscarStockValorizado(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaStockValorizado(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaStockValorizado()
{
    ax.setAccion("obtenerDataStockValorizado");
    ax.addParamTmp("criterios", valoresBusquedaStockValorizado);
    ax.consumir();
}

function onResponseGetDataGridStockValorizado(data) {
//    console.log(data);
    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoStockValorizado(' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/        
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[1, "desc"]],
            "data": data,            
            "columns": [
                {"data": "bien_tipo_descripcion"},
                {"data": "bien_descripcion"},
                {"data": "stock",  "sClass": "alignRight"},
                {"data": "unidad_control"},
                {"data": "stock_valorizado",  "sClass": "alignRight"}
            ],
            columnDefs: [                
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
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

function verDocumentoStockValorizado(organizadorId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoStockValorizado");
    ax.addParamTmp("id_organizador", organizadorId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoStockValorizado(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + '</strong>';

        $('#datatableDocumentoStockValorizado').dataTable({
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
        var table = $('#datatableDocumentoStockValorizado').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este organizador.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteStockValorizadoExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteStockValorizadoExcel");
    ax.addParamTmp("criterios", valoresBusquedaStockValorizado);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarStockValorizado();
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