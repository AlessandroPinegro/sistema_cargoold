var TOTAL;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();

    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesIniciales();
    modificarAnchoTabla('datatable');

});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);

                break;
            case 'obtenerReporteBalanceExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'obtenerTotalBalance':
                if (response.data.total === null)
                {
                    response.data.total = 0;
                }
                
                TOTAL = response.data.total;
                getDataTable();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteBalanceExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesIniciales()
{
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.documento_tipo))
    {
        select2.cargar("cboTipoDocumento", data.documento_tipo, "id", "descripcion");
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
    loaderClose();
}

var valoresBusquedaReporteBalance = [{organizador: "", tipoDocumento: "", fechaEmision: "", fechaVencimiento: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusqueda()
{
//    var organizadorId = $('#cboOrganizador').val();

    var DocumentoId = $('#cboTipoDocumento').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    var fechaVencimientoInicio = $('#inicioFechaVencimiento').val();
    var fechaVencimientoFin = $('#finFechaVencimiento').val();

//    valoresBusquedaReporteBalance[0].organizador = organizadorId;
    valoresBusquedaReporteBalance[0].tipoDocumento = DocumentoId;
    valoresBusquedaReporteBalance[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaReporteBalance[0].fechaVencimiento = objetoFecha(fechaVencimientoInicio, fechaVencimientoFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaReporteBalance[0].tipoDocumento))
    {
        cadena += negrita("Tipo de documento: ");
        cadena += select2.obtenerTextMultiple('cboTipoDocumento');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteBalance[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaReporteBalance[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteBalance[0].fechaEmision.inicio + " - " + valoresBusquedaReporteBalance[0].fechaEmision.fin;
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteBalance[0].fechaVencimiento.inicio) || !isEmpty(valoresBusquedaReporteBalance[0].fechaVencimiento.fin))
    {
        cadena += negrita("Fecha vencimiento: ");
        cadena += valoresBusquedaReporteBalance[0].fechaVencimiento.inicio + " - " + valoresBusquedaReporteBalance[0].fechaVencimiento.fin;
        cadena += "<br>";
    }

    return cadena;
}

function buscarBalance(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();

    obtenerTotalBalance();

    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerTotalBalance()
{
    ax.setAccion("obtenerTotalBalance");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalance);
    ax.consumir();
}

function getDataTable() {
    ax.setAccion("obtenerDataBalance");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalance);

    var table = $('#datatable').DataTable();
    table.clear().draw();

    $('#datatable').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "columns": [
            {"data": "fecha_emision", "width": "60px"},
            {"data": "documento_tipo_descripcion", "width": "190px"},
            {"data": "persona_nombre", "width": "250px"},
            {"data": "serie", "width": "50px"},
            {"data": "numero", "class": "center", "width": "60px"},
            {"data": "fecha_vencimiento", "class": "center", "width": "55px"},
            {"data": "importe", "sClass": "alignRight", "width": "70px"}
        ],
        destroy: true,
        columnDefs: [
            {
                "render": function (data, type, row) {

                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 5
            }
        ],
        footerCallback: function (row, data, start, end, display) {

            var api = this.api(), data;
            $(api.column(6).footer()).html(
                    'S/. ' + (formatearNumero(TOTAL))
                    );
        }
    });
    loaderClose();
}
function imprimir(muestra)
{
    var ficha = document.getElementById(muestra);
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarBalance();
    }
    loaderClose();
}

function exportarReporteBalanceExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteBalanceExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalance);
    ax.consumir();
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

