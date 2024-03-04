$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesComprometidosDia();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesComprometidosDia':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataComprometidosDia':
                onResponseGetDataGridComprometidosDia(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleComprometidosDia':
                onResponseDetalleComprometidosDia(response.data);
                loaderClose();
                break;
            case 'obtenerReporteComprometidosDiaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteComprometidosDiaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesComprometidosDia()
{
    //alert('hola CD');
    ax.setAccion("obtenerConfiguracionesInicialesComprometidosDia");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.bien)) {
            select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
            if (!isEmpty(data.bien_tipo)) {
                select2.cargar("cboTipoBien", data.bien_tipo, "id",["codigo","descripcion"]);
                if (!isEmpty(data.fecha_primer_documento)) {

                    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
                    {
                        $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
                        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
                        {
                            $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
                        }
                    }

                }
            }

        }
    }
    loaderClose();
}

var valoresBusquedaComprometidosDia = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaComprometidosDia[0].organizador = organizadorId;
    valoresBusquedaComprometidosDia[0].bien = bien;
    valoresBusquedaComprometidosDia[0].bienTipo = bienTipo;
    valoresBusquedaComprometidosDia[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaComprometidosDia[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaComprometidosDia[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComprometidosDia[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComprometidosDia[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComprometidosDia[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaComprometidosDia[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaComprometidosDia[0].fechaEmision.inicio + " - " + valoresBusquedaComprometidosDia[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarComprometidosDia(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaComprometidosDia(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaComprometidosDia()
{
    ax.setAccion("obtenerDataComprometidosDia");
    ax.addParamTmp("criterios", valoresBusquedaComprometidosDia);
    ax.consumir();
}

function onResponseGetDataGridComprometidosDia(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleComprometidosDia(' + item['bien_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[4, "desc"]],
            "data": data,            
            "columns": [
                {"data": "fecha_emision"},
                {"data": "bien_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_control_descripcion"},
                {"data": "cantidad_control", "sClass": "alignRight"},
                {"data": "opciones", "sClass": "alignCenter", "width": "50px"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 4
                }, 
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
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
        buscarComprometidosDia();
    }
    loaderClose();
}

function verDetalleComprometidosDia(bienId,fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleComprometidosDia");
    ax.addParamTmp("id_bien", bienId);    
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function onResponseDetalleComprometidosDia(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+ data[0]['bien_descripcion'] + '</strong>';

        $('#datatableComprometidosDia').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
                {"data": "documento_tipo_descripcion"},
                {"data": "persona_nombre"},
                {"data": "numero"},
                {"data": "documento_estado_descripcion"},                
                {"data": "organizador_descripcion"},        
                {"data": "unidad_medida_descripcion"},
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
        var table = $('#datatableComprometidosDia').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteComprometidosDiaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteComprometidosDiaExcel");
    ax.addParamTmp("criterios", valoresBusquedaComprometidosDia);
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
        buscarComprometidosDia(0);
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
