$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteReporteTributario");
    obtenerConfiguracionesInicialesReporteTributario();
    modificarAnchoTabla('datatable');
});

function onResponseReporteReporteTributario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteTributario':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataReporteTributario':
                onResponseGetDataGridReporteTributario(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoReporteTributario':
                onResponseDocumentoReporteTributario(response.data);
                loaderClose();
                break;
            case 'obtenerReporteTributarioExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteTributarioExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteTributario()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesReporteTributario");
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
    
    var tipo =
            [
                {id: 1, descripcion: "Detracción"},
                {id: 2, descripcion: "Retención"},
                {id: 3, descripcion: "Percepción"},
                {id: 4, descripcion: "IGV"}
            ];
    
    select2.cargar("cboTipoTributo", tipo, "id", "descripcion");
    //select2.asignarValor("cboTipoTributo",2);
    
    loaderClose();
}

var valoresBusquedaReporteTributario = [{tipoTributo: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{
    //var tipoTributo = $('#cboTipoTributo').val();
    var tipoTributo = select2.obtenerValor('cboTipoTributo');
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaReporteTributario[0].tipoTributo = tipoTributo;
    valoresBusquedaReporteTributario[0].empresaId = commonVars.empresa;
    valoresBusquedaReporteTributario[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaReporteTributario[0].tipoTributo))
    {
        cadena += negrita("Tipo: ");
        cadena += select2.obtenerText('cboTipoTributo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteTributario[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaReporteTributario[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteTributario[0].fechaEmision.inicio + " - " + valoresBusquedaReporteTributario[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarReporteTributario(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaReporteTributario(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaReporteTributario()
{
    ax.setAccion("obtenerDataReporteTributario");
    ax.addParamTmp("criterios", valoresBusquedaReporteTributario);
    ax.consumir();
}

function onResponseGetDataGridReporteTributario(data) {
    //console.log(data);
    
    var total=data.total;
    if (total === null) {
        total = 0;
    }
                
    var datos=data.datos;

    if (!isEmptyData(datos))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoReporteTributario(' + item['tienda_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/       
        
        $('#datatable').dataTable({
            
            "order": [[0, "desc"]],
            "data": datos,      
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_emision",  "sClass": "alignCenter"},
                {"data": "tipo_comprobante_descripcion"},
                {"data": "serie_num_comprobante"},
                {"data": "tipo_documento_descripcion"},
                {"data": "serie_num_documento"},
                {"data": "importe",  "sClass": "alignRight"}
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
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 5
                }
            ],
            
            "destroy": true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(5).footer()).html(
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

function verDocumentoReporteTributario(tiendaId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoReporteTributario");
    ax.addParamTmp("id_tienda", tiendaId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoReporteTributario(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['tienda_descripcion'] + '</strong>';

        $('#datatableDocumentoReporteTributario').dataTable({
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
        var table = $('#datatableDocumentoReporteTributario').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este tienda.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteReporteTributarioExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteTributarioExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteTributario);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarReporteTributario();
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