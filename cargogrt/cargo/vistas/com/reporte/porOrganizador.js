$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteXOrganizador");
    obtenerConfiguracionesInicialesReporteXOrganizador();
    modificarAnchoTabla('datatable');
});

function onResponseReporteXOrganizador(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteXOrganizador':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerReporteXOrganizador':
                onResponseGetDataGridReporteXOrganizador(response.data);
                loaderClose();
                break;
//            case 'obtenerDetalleKardex':
//                onResponseDetalleKardex(response.data);
//                loaderClose();
//                break;
//            case 'obtenerReporteKardexExcel':
//                loaderClose();
//                location.href = URL_BASE + "util/formatos/reporte.xlsx";
//                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
//            case 'obtenerReporteKardexExcel':
//                loaderClose();
//                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteXOrganizador()
{
    ax.setAccion("obtenerConfiguracionesInicialesReporteXOrganizador");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
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
    loaderClose();
}

var valoresBusquedaXOrganizador = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaXOrganizador[0].organizador = organizadorId;
    valoresBusquedaXOrganizador[0].bien = bien;
    valoresBusquedaXOrganizador[0].bienTipo = bienTipo;
    valoresBusquedaXOrganizador[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaXOrganizador[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaXOrganizador[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaXOrganizador[0].bien))
    {
        cadena += negrita("Bien: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaXOrganizador[0].bienTipo))
    {
        cadena += negrita("Bien tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaXOrganizador[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaXOrganizador[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaXOrganizador[0].fechaEmision.inicio + " - " + valoresBusquedaXOrganizador[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarXOrganizador(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaXOrganizador(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaXOrganizador()
{
    ax.setAccion("obtenerReporteXOrganizador");
    ax.addParamTmp("criterios", valoresBusquedaXOrganizador);
    ax.consumir();
}

function onResponseGetDataGridReporteXOrganizador(data) {

    if (!isEmptyData(data))
    {
//        $.each(data, function (index, item) {
//            data[index]["opciones"] = '<a onclick="verDetalleKardex(' + item['bien_id'] + ',' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
//        });
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "bien_descripcion", "width": "250px"},
                {"data": "organizador_descripcion", "width": "250px"},
                {"data": "bien_tipo_descripcion", "width": "200px"},
                {"data": "total_monetario", "sClass": "alignRight", "width": "150px"}

            ],
            "destroy": true,
            columnDefs: [
                {
                    "render": function (data, type, row) {

                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 3
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                if (!isEmpty(data))
                {
                    var api = this.api(), data;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    total = api
                            .column(3)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            });
                    $(api.column(3).footer()).html(
                            'S/. ' + (parseFloat(total)).formatMoney(2, '.', ',')
                            );
                }
                else
                {
                    var api = this.api(), data;
                    $(api.column(3).footer()).html(
                            'S/. ' + (0)
                            );
                }
                loaderClose();
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
        buscarXOrganizador();
    }
    loaderClose();
}

function verDetalleKardex(bienId, organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleKardex");
    ax.addParamTmp("id_bien", bienId);
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleKardex(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';

        $('#datatableStock').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "unidad_medida_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-kardex').modal('show');
    }
    else
    {
        var table = $('#datatableStock').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

function exportarReporteKardexExcel()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteKardexExcel");
    ax.addParamTmp("criterios", valoresBusquedaXOrganizador);
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
        buscarXOrganizador();
    }
    loaderClose();
}

var actualizandoBusqueda = false;

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