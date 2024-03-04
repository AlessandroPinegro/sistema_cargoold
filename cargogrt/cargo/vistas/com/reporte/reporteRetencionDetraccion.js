$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteRetencionDetraccion");
    obtenerConfiguracionesInicialesRetencionDetraccion();
    modificarAnchoTabla('datatable');
});

function onResponseReporteRetencionDetraccion(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesRetencionDetraccion':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataRetencionDetraccion':
                onResponseGetDataGridRetencionDetraccion(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRetencionDetraccion':
                onResponseDocumentoRetencionDetraccion(response.data);
                loaderClose();
                break;
            case 'obtenerReporteRetencionDetraccionExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteRetencionDetraccionExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesRetencionDetraccion()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesRetencionDetraccion");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');
    var tipoRD = [  {id: 1, descripcion: "Retención"},
                    {id: 2, descripcion: "Detracción"}
                ];
    
    select2.cargar("cboTipoRD", tipoRD, "id", "descripcion");
    
    if (!isEmpty(data.fecha_primer_documento)) {
        if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha'])) {
            $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
            if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual'])) {
                $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
            }
        }
    }
    
    if (!isEmpty(data.persona)) {
        select2.cargar("cboCliente", data.persona, "id", ["persona_nombre","codigo_identificacion"]);
    }    
    
    loaderClose();
}

var valoresBusquedaRetencionDetraccion = [{cliente: "", tipoRD: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var cliente = $('#cboCliente').val();
    var tipoRD = select2.obtenerValor('cboTipoRD');
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaRetencionDetraccion[0].cliente = cliente;
    valoresBusquedaRetencionDetraccion[0].tipoRD = tipoRD;
    valoresBusquedaRetencionDetraccion[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaRetencionDetraccion[0].empresa = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaRetencionDetraccion[0].cliente))
    {
        cadena += negrita("Cliente: ");
        cadena += select2.obtenerTextMultiple('cboCliente');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRetencionDetraccion[0].tipoRD))
    {
        cadena += negrita("Tipo: ");
        cadena += select2.obtenerText('cboTipoRD');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaRetencionDetraccion[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaRetencionDetraccion[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaRetencionDetraccion[0].fechaEmision.inicio + " - " + valoresBusquedaRetencionDetraccion[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarRetencionDetraccion(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaRetencionDetraccion(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaRetencionDetraccion()
{
    ax.setAccion("obtenerDataRetencionDetraccion");
    ax.addParamTmp("criterios", valoresBusquedaRetencionDetraccion);
    ax.consumir();
}

function onResponseGetDataGridRetencionDetraccion(data) {

    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoRetencionDetraccion(' + item['cliente_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/
        $('#datatable').dataTable({
         
            "order": [[0, "desc"]],
            "data": data,            
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
//                {"data": "usuario_nombre"},
                {"data": "documento_tipo_descripcion"},
                {"data": "persona_nombre_completo"},
                {"data": "serie"},
                {"data": "numero"},
                {"data": "tipo_rd_descripcion"},
                {"data": "total", "class": "alignRight"},
                {"data": "pendiente", "class": "alignRight"}
            ],            
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [7,8]
                }, 
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                    },
                    "targets": 1
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

function verDocumentoRetencionDetraccion(clienteId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoRetencionDetraccion");
    ax.addParamTmp("id_cliente", clienteId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoRetencionDetraccion(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['cliente_descripcion'] + '</strong>';

        $('#datatableDocumentoRetencionDetraccion').dataTable({
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
        var table = $('#datatableDocumentoRetencionDetraccion').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este cliente.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteRetencionDetraccionExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteRetencionDetraccionExcel");
    ax.addParamTmp("criterios", valoresBusquedaRetencionDetraccion);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarRetencionDetraccion();
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