$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesBienesMayorRotacion();
    modificarAnchoTabla('datatable');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesBienesMayorRotacion':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataBienesMayorRotacion':
                onResponseGetDataGridBienesMayorRotacion(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleBienesMayorRotacion':
                onResponseDetalleBienesMayorRotacion(response.data);
                loaderClose();
                break;
            case 'obtenerReporteBienesMayorRotacionExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteBienesMayorRotacionExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesBienesMayorRotacion()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesBienesMayorRotacion");
    ax.addParamTmp("id_empresa", -1);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.bien)) {
            select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
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
    loaderClose();
}

var valoresBusquedaBienesMayorRotacion = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaBienesMayorRotacion[0].organizador = organizadorId;
    valoresBusquedaBienesMayorRotacion[0].bien = bien;
    valoresBusquedaBienesMayorRotacion[0].bienTipo = bienTipo;
    valoresBusquedaBienesMayorRotacion[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
//    valoresBusquedaBienesMayorRotacion[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaBienesMayorRotacion[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienesMayorRotacion[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienesMayorRotacion[0].bienTipo))
    {
        cadena += negrita("Producto tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienesMayorRotacion[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaBienesMayorRotacion[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaBienesMayorRotacion[0].fechaEmision.inicio + " - " + valoresBusquedaBienesMayorRotacion[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarBienesMayorRotacion(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaBienesMayorRotacion(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaBienesMayorRotacion()
{
    ax.setAccion("obtenerDataBienesMayorRotacion");
    ax.addParamTmp("criterios", valoresBusquedaBienesMayorRotacion);
    ax.consumir();
}

function onResponseGetDataGridBienesMayorRotacion(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDetalleBienesMayorRotacion(' + item['bien_id'] + ',' + item['organizador_id'] +',' + item['unidad_medida_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });
        $('#datatable').dataTable({
           
            "order": [[5, "desc"]],
            "data": data,          
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "organizador_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "frecuencia", "sClass": "alignRight"},
                {"data": "opciones", "sClass": "alignCenter"}
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
        buscarBienesMayorRotacion();
    }
    loaderClose();
}

function verDetalleBienesMayorRotacion(bienId, organizadorId,unidadMedidaId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleBienesMayorRotacion");
    ax.addParamTmp("id_bien", bienId);
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("id_unidadMedida", unidadMedidaId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleBienesMayorRotacion(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> '+data[0]['organizador_descripcion']+' - ' + data[0]['bien_descripcion'] +' - ' + data[0]['unidad_medida_descripcion']+ '</strong>';

        $('#datatableBienesMayorRotacion').dataTable({
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
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 8
                }, 
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                    },
                    "targets": [0,1,6]
                }
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-documentos-servicios').modal('show');
    }
    else
    {
        var table = $('#datatableBienesMayorRotacion').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteBienesMayorRotacionExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteBienesMayorRotacionExcel");
    ax.addParamTmp("criterios", valoresBusquedaBienesMayorRotacion);
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
        buscarBienesMayorRotacion(0);
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
