$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    select2.iniciar();
    ax.setSuccess("onResponseEstadoFinancieroNaturaleza");
    obtenerDataInicial();
});

function onResponseEstadoFinancieroNaturaleza(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDataInicialEstadoFinancieroNaturaleza':
                onResponseObtenerDataInicial(response.data);
                break;
            case 'obtenerEstadoFinancieroNaturalezaXCriterios':
                onResponseObtenerEstadoFinancieroNaturaleza(response.data);
                break;
            case 'obtenerEstadoFinancieroNaturalezaExcel':
                loaderClose();
                location.href = URL_BASE + response.data;
                break;

        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            default :
                loaderClose();
                break;

        }
    }
}

function obtenerDataInicial() {
    loaderShow();
    ax.setAccion("obtenerDataInicialEstadoFinancieroNaturaleza");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("tipo", tipo_busquedad);
    ax.consumir();
}

function exportarExcel()
{
    loaderShow();
    ax.setAccion("obtenerEstadoFinancieroNaturalezaExcel");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("valorBusquedad", select2.obtenerValor("cboPeriodo"));
    ax.addParamTmp("tipo", tipo_busquedad);
    ax.consumir();
}

function buscarPorCriterios(colapsa)
{
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;

    if (colapsa === 1)
        colapsarBuscador();

    loaderShow();
    ax.setAccion("obtenerEstadoFinancieroNaturalezaXCriterios");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("valorBusquedad", select2.obtenerValor("cboPeriodo"));
    ax.addParamTmp("tipo", tipo_busquedad);
    ax.consumir();
}


function onResponseObtenerDataInicial(data) {
    let complemento = (tipo_busquedad == 1 ? "mensual" : "acumulado");
    $("#titulo").html("Estado de ganancias y perdidas por naturaleza " + complemento);

    if (tipo_busquedad == 1) {
        $("#lblCboPeriodo").html("Periodo :");
        select2.cargar('cboPeriodo', data.dataPeriodo, 'id', ['anio', 'mes']);
        select2.asignarValor('cboPeriodo', data.dataPeriodoActual[0]['id']);
    } else if (tipo_busquedad == 2) {
        $("#lblCboPeriodo").html("Año :");
        select2.cargar('cboPeriodo', data.dataAnio, 'anio', 'anio');
        select2.asignarValor('cboPeriodo', data.dataAnioActual);
    }

    onResponseObtenerEstadoFinancieroNaturaleza(data.dataReporte);
}

function onResponseObtenerEstadoFinancieroNaturaleza(data) {
    $("#dataList").empty();

    var trVacio = '';
    trVacio += '<tr>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '</tr>';

    var cuerpo_total = '';

    cuerpo_total += '<table id="datatable" class="table table-striped table-bordered">';
    cuerpo_total += '<thead>';
    cuerpo_total += '<tr>';
    cuerpo_total += '<th style="text-align:center;">Cuenta</th>';
    cuerpo_total += '<th style="text-align:center;">Monto</th>';
    cuerpo_total += '<th style="text-align:center;">%</th>';
    cuerpo_total += '</tr>';
    cuerpo_total += '</thead>';
    cuerpo_total += '<tbody>';

    if (!isEmpty(data)) {
        let item = data.filter(elemento => parseInt(elemento['codigo']) === 70)[0];
        let montoVentaNeta = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeVenta = 100;
        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoVentaNeta, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(100, 2) + '</td>';
        cuerpo_total += '</tr>';
        cuerpo_total += '<tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 74)[0];
        let montoDescuentoRebaja = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeDescuentoRebaja = redondearNumerDecimales(montoDescuentoRebaja / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoDescuentoRebaja, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeDescuentoRebaja, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoUtilidadVentaNeta = redondearNumero(montoVentaNeta + montoDescuentoRebaja);
        let porcentajeUtilidadVentaNeta = redondearNumerDecimales(porcentajeVenta + porcentajeDescuentoRebaja, 6);
        cuerpo_total += '<td align="right"><b>VENTA NETA</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoUtilidadVentaNeta, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeUtilidadVentaNeta, 2) + '</b></td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 60)[0];
        let montoCompras = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeCompras = redondearNumerDecimales(montoCompras / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoCompras, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeCompras, 2) + '</td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 61)[0];
        let montoVariacionInvetario = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeVariacionInvetario = redondearNumerDecimales(montoVariacionInvetario / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoVariacionInvetario, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeVariacionInvetario, 2) + '</td>';
        cuerpo_total += '</tr>';


        let montoMargenComercial = redondearNumero(montoUtilidadVentaNeta + montoCompras + montoVariacionInvetario);
        let porcentajeMargenComercial = redondearNumerDecimales(porcentajeUtilidadVentaNeta + porcentajeCompras + porcentajeVariacionInvetario, 6);
        cuerpo_total += '<td align="right"><b>MARGEN COMERCIAL</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoMargenComercial, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeMargenComercial, 2) + '</b></td>';
        cuerpo_total += '</tr>';

//        item = data.filter(elemento => parseInt(elemento['codigo']) === 602)[0];
//        let montoMateriaPrima = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
//        let porcentajeMateriaPrima = redondearNumerDecimales(montoMateriaPrima / montoVentaNeta, 6) * porcentajeVenta;
//
//        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoMateriaPrima, 2) + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeMateriaPrima, 2) + '</td>';
//        cuerpo_total += '</tr>';
//
//
//        item = data.filter(elemento => parseInt(elemento['codigo']) === 603)[0];
//        let montoMaterialAuxiliar = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
//        let porcentajeMaterialAuxiliar = redondearNumerDecimales(montoMaterialAuxiliar / montoVentaNeta, 6) * porcentajeVenta;
//
//        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoMaterialAuxiliar, 2) + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeMaterialAuxiliar, 2) + '</td>';
//        cuerpo_total += '</tr>';
//
//        item = data.filter(elemento => parseInt(elemento['codigo']) === 604)[0];
//        let montoEnvases = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
//        let porcentajeEnvases = redondearNumerDecimales(montoEnvases / montoVentaNeta, 6) * porcentajeVenta;
//
//        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoEnvases, 2) + '</td>';
//        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeEnvases, 2) + '</td>';
//        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 63)[0];
        let montoGastoServiciosPrestados = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeGastoServiciosPrestados = redondearNumerDecimales(montoGastoServiciosPrestados / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoServiciosPrestados, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoServiciosPrestados, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoValorAgregado = redondearNumero(montoMargenComercial + montoGastoServiciosPrestados);
        let porcentajeValorAgregado = redondearNumerDecimales(porcentajeMargenComercial + porcentajeGastoServiciosPrestados, 6);
        cuerpo_total += '<td align="right"><b>VALOR AGREGADO</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoValorAgregado, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeValorAgregado, 2) + '</b></td>';
        cuerpo_total += '</tr>';



        item = data.filter(elemento => parseInt(elemento['codigo']) === 62)[0];
        let montoGastoPersonal = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeGastoPersonal = redondearNumerDecimales(montoGastoPersonal / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoPersonal, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoPersonal, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 64)[0];
        let montoGastoTributos = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeGastoTributos = redondearNumerDecimales(montoGastoTributos / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoTributos, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoTributos, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoBrutoExportacion = redondearNumero(montoValorAgregado + montoGastoPersonal + montoGastoTributos);
        let porcentajeBrutoExportacion = redondearNumerDecimales(porcentajeValorAgregado + porcentajeGastoPersonal + porcentajeGastoTributos, 6);

        let nombre = (montoBrutoExportacion > 0 ? 'EXCEDENTE BRUTO' : 'INSUFICIENCIA BRUTA');
        cuerpo_total += '<td align="right"><b>' + nombre + ' DE EXPLOTACION</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoBrutoExportacion, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeBrutoExportacion, 2) + '</b></td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 73)[0];
        let montoDescuentoRebajaObtenida = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeDescuentoRebajaObtenida = redondearNumerDecimales(montoDescuentoRebajaObtenida / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoDescuentoRebajaObtenida, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeDescuentoRebajaObtenida, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 75)[0];
        let montoIngresoDiversos = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeIngresoDiversos = redondearNumerDecimales(montoIngresoDiversos / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoIngresoDiversos, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeIngresoDiversos, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 76)[0];
        let montoGananciaNoFinanciero = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeGananciaNoFinanciero = redondearNumerDecimales(montoGananciaNoFinanciero / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGananciaNoFinanciero, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGananciaNoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 65)[0];
        let montoOtrosGastos = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeOtrosGastos = redondearNumerDecimales(montoOtrosGastos / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoOtrosGastos, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeOtrosGastos, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 68)[0];
        let montoValuacionDeterioro = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeValuacionDeterioro = redondearNumerDecimales(montoValuacionDeterioro / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoValuacionDeterioro, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeValuacionDeterioro, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 66)[0];
        let montoPerdidaNoFinanciero = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajePerdidaNoFinanciero = redondearNumerDecimales(montoPerdidaNoFinanciero / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoPerdidaNoFinanciero, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajePerdidaNoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoResultadoExplotacion = redondearNumero(montoBrutoExportacion + montoDescuentoRebajaObtenida + montoIngresoDiversos + montoGananciaNoFinanciero + montoOtrosGastos + montoValuacionDeterioro + montoPerdidaNoFinanciero);
        let porcentajeResultadoExplotacion = redondearNumerDecimales(porcentajeBrutoExportacion + porcentajeDescuentoRebajaObtenida + porcentajeIngresoDiversos + porcentajeGananciaNoFinanciero + porcentajeOtrosGastos + porcentajeValuacionDeterioro + porcentajePerdidaNoFinanciero, 6);
        cuerpo_total += '<td align="right"><b>RESULTADO DE EXPLOTACION</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoResultadoExplotacion, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeResultadoExplotacion, 2) + '</b></td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 77)[0];
        let montoIngresoFinanciero = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeIngresoFinanciero = redondearNumerDecimales(montoIngresoFinanciero / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoIngresoFinanciero, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeIngresoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 67)[0];
        let montoGastoFinanciero = redondearNumero((!isEmpty(item) ? (item.haber * 1) - (item.debe * 1) : 0));
        let porcentajeGastoFinanciero = redondearNumerDecimales(montoGastoFinanciero / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + (!isEmpty(item) ? item.descripcion : '') + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoFinanciero, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoResultado = redondearNumero(montoResultadoExplotacion + montoIngresoFinanciero + montoGastoFinanciero);
        let porcentajeResultado = redondearNumerDecimales(porcentajeResultadoExplotacion + porcentajeIngresoFinanciero + porcentajeGastoFinanciero, 6);

        cuerpo_total += '<td align="right"><b>RESULTADO ANTES DE PARTICIPACION E IMPUESTOS</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoResultado, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeResultado, 2) + '</b></td>';
        cuerpo_total += '</tr>';


    }

    cuerpo_total += '</tbody></table>';
    $("#dataList").append(cuerpo_total);

    $('#datatable').dataTable({
        "lengthMenu": [[-1], ["Todo"]],
        "order": [],
        "destroy": true,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });

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



function arrayObjectUnique(array, campo) {
    var unique = {};
    $.each(array, function (x, item) {
        unique[item[campo]] = item[campo];
    });
    return $.map(unique, function (value) {
        return value;
    });
}


function redondearNumerDecimales(monto, decimales) {
    if (isEmpty(decimales)) {
        decimales = 2;
    }
    return Math.round(monto * Math.pow(10, decimales)) / Math.pow(10, decimales);
}