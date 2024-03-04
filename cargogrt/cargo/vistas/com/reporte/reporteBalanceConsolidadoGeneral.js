var total_general = 0;
var importe_pagado_general = 0;
var importe_pendiente_general = 0;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();

    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesGenerales();
    modificarAnchoTabla('datatableBalanceConsolidado');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesBalanceConsolidadoGeneral':
                onResponseObtenerConfiguracionesIniciales(response.data);

                break;
            case 'obtenerReporteBalanceExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'obtenerCantidadesTotalesBalanceConsolidadoGeneral':
                if (response.data.total === null)
                {
                    response.data.total = 0;
                }
                if (response.data.importe_pagado === null)
                {
                    response.data.importe_pagado = 0;
                }
                if (response.data.importe_pendiente === null)
                {
                    response.data.importe_pendiente = 0;
                }
                total_general = response.data.total;
                cantidad_importe_pendiente_general = response.data.importe_pendiente;
                cantidad_importe_pagado_general = response.data.importe_pagado;
                getDataTableBalanceConsolidado();
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

function obtenerConfiguracionesInicialesGenerales()
{
    ax.setAccion("obtenerConfiguracionesInicialesBalanceConsolidadoGeneral");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.documento_tipo))
    {
        select2.cargar("cboTipoDocumento", data.documento_tipo, "id", "descripcion");
    }
    var string = '<option selected value="-1">Seleccionar una persona</option>';
    if (!isEmpty(data.persona)) {

        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersona').append(string);
        select2.asignarValor('cboPersona', "-1");
    }
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");
    }
    loaderClose();
}

var valoresBusquedaReporteBalanceConsolidadoGeneral = [{organizador: "", tipoDocumento: "", fechaEmision: "", fechaVencimiento: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusqueda()
{
//    var organizadorId = $('#cboOrganizador').val();

    var DocumentoId = $('#cboTipoDocumento').val();
    var EmpresaId = $('#cboEmpresa').val();
    var PersonaId = $('#cboPersona').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    var fechaVencimientoInicio = $('#inicioFechaVencimiento').val();
    var fechaVencimientoFin = $('#finFechaVencimiento').val();
    valoresBusquedaReporteBalanceConsolidadoGeneral[0].empresa = EmpresaId;
//    valoresBusquedaReporteBalanceConsolidadoGeneral[0].organizador = organizadorId;
    valoresBusquedaReporteBalanceConsolidadoGeneral[0].persona = PersonaId;
    valoresBusquedaReporteBalanceConsolidadoGeneral[0].tipoDocumento = DocumentoId;
    valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaVencimiento = objetoFecha(fechaVencimientoInicio, fechaVencimientoFin);
    
//     getDataTableBalanceConsolidado();
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    
    if (!isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].persona))
    {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].empresa))
    {
        cadena += negrita("Empresa: ");
        cadena += select2.obtenerText('cboEmpresa');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].tipoDocumento))
    {
        cadena += negrita("Tipo de documento: ");
        cadena += select2.obtenerTextMultiple('cboTipoDocumento');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaEmision.inicio + " - " + valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaEmision.fin;
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaVencimiento.inicio) || !isEmpty(valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaVencimiento.fin))
    {
        cadena += negrita("Fecha vencimiento: ");
        cadena += valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaVencimiento.inicio + " - " + valoresBusquedaReporteBalanceConsolidadoGeneral[0].fechaVencimiento.fin;
        cadena += "<br>";
    }

    return cadena;
}

function buscarBalanceConsolidadoGeneral(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesBalanceConsolidado();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
//    cargarDatosBusqueda();

    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerTotalBalance()
{
    ax.setAccion("obtenerTotalBalance");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalanceConsolidadoGeneral);
    ax.consumir();
}

function getDataTableBalanceConsolidado() {
    color = '';
    ax.setAccion("obtenerDataBalanceConsolidadoGeneral");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalanceConsolidadoGeneral);
    $('#datatableBalanceConsolidado').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],      
        "columns": [
//            {"data": "fecha_emision", "width": "50px"},
            {"data": "fecha_emision", "width": "50px"},
            {"data": "fecha_vencimiento", "width": "50px"},
            {"data": "documento_tipo_descripcion", "width": "70px"},
            {"data": "razon_social", "width": "50px"},
            {"data": "persona_nombre_completo", "width": "255px"},
            {"data": "serie", "width": "20px"},
            {"data": "numero", "width": "60px"},
            {"data": "moneda_descripcion", "width": "50px"},
            {"data": "importe_pagado", "class": "alignRight", "width": "60px"},
            {"data": "importe_pendiente", "class": "alignRight", "width": "60px"},
            {"data": "total", "class": "alignRight", "width": "60px"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": [0,1]
            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [8,9,10]
            }
        ],
        destroy: true
//        ,footerCallback: function (row, data, start, end, display) {
//            var api = this.api(), data;
//            $(api.column(7).footer()).html(
//                    'S/. ' + (formatearNumero(cantidad_importe_pagado_general))
//                    );
//            $(api.column(8).footer()).html(
//                    'S/. ' + (formatearNumero(cantidad_importe_pendiente_general))
//                    );
//            $(api.column(9).footer()).html(
//                    'S/. ' + (formatearNumero(total_general))
//                    );
//        }
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
        buscarBalanceConsolidadoGeneral();
    }
    loaderClose();
}

function exportarReporteBalanceExcel()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteBalanceExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalanceConsolidadoGeneral);
    ax.consumir();
}

function obtenerCantidadesTotalesBalanceConsolidado()
{
    ax.setAccion("obtenerCantidadesTotalesBalanceConsolidadoGeneral");
    ax.addParamTmp("criterios", valoresBusquedaReporteBalanceConsolidadoGeneral);
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