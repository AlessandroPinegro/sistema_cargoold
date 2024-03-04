var banderaBuscar = 0;
var estadoTolltip = 0;

$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    loaderShow();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesIniciales();
});

function iniciarDataPicker()
{
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

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
    if (!isEmpty(data.organizador) && !isEmpty(data.documento_tipo)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        select2.cargar("cboTipoDocumento", data.documento_tipo, "id", "descripcion");
    }
}

var valoresBusquedaReporteBalance = [{organizador: "", tipoDocumento: "", fechaEmision: "", fechaVencimiento: "", bandera:"1"}];//bandera 1 es cantidad

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var DocumentoId = $('#cboTipoDocumento').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    var fechaVencimientoInicio = $('#inicioFechaVencimiento').val();
    var fechaVencimientoFin = $('#finFechaVencimiento').val();

    valoresBusquedaReporteBalance[0].organizador = organizadorId;
    valoresBusquedaReporteBalance[0].tipoDocumento = DocumentoId;
    valoresBusquedaReporteBalance[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaReporteBalance[0].fechaVencimiento = objetoFecha(fechaVencimientoInicio, fechaVencimientoFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaReporteBalance[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
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

function objetoFecha(inicio, fin)
{
    var fecha = {inicio: inicio,
        fin: fin};

    return fecha;
//    if (!isEmpty(fecha.inicio) && !isEmpty(fecha.fin))
//    {
//        if (validateFechaMayorQue(fecha.inicio, fecha.fin))
//        {
//            return fecha;
//        }
//        else
//        {
//            fecha.inicio = "";
//            fecha.fin = "";
//            return fecha;
//        }
//    }
//    else
//    {
//        fecha.inicio = inicio;
//        fecha.fin = fin;
//        return fecha;
//    }
}


function buscar()
{
    var cadena;

    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        getDataTable();

        $('#idPopover').attr("data-content", cadena);
        $('[data-toggle="popover"]').popover('show');
        banderaBuscar = 1;
    }
}


function cerrarPopover()
{
    if (banderaBuscar == 1)
    {
        if (estadoTolltip == 1)
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
    estadoTolltip = (estadoTolltip == 0) ? 1 : 0;
}

function getDataTable() {
    ax.setAccion("obtenerData");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalance);
    $('#datatable').dataTable({
        "processing": true,
        "serverSide": true,
//        "ajax": ax.getAjaxDataTable()
        "ajax": {
            "url": URL_EXECUTECONTROLLER,
            "data": function (d) {
                d.param_sid = "SVlrM3hiV2VvdGl1NUJPTEVZRTRHUT09";
                d.param_flag_datatable = 1;
                d.param_opcion_id = 51;
                d.action_name = "obtenerData";
                d.criterios = valoresBusquedaReporteBalance;
            }
        },
        "lengthMenu": [[2,5, 10, 20, 50], [2,5, 10, 20, 50]],
        "columns": [
            {"data": "fecha_emision"},
            {"data": "organizador_descripcion"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre"},
            {"data": "serie"},
            {"data": "numero", "class": "center"},
            {"data": "fecha_vencimiento", "class": "center"},
            {"data": "cantidad", "sClass": "alignRight"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {

                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 7
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            if (!isEmpty(data))
            {


                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                total = api
                        .column(7)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                // Update footer    
                $(api.column(7).footer()).html(
                        'S/. ' + (parseFloat(total)).formatMoney(2, '.', ',')
                        );
            }
        }
    });
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};


