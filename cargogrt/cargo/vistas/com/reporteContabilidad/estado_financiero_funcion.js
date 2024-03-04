$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    select2.iniciar();
    ax.setSuccess("onResponseEstadoFinancieroFuncion");
    obtenerDataInicial();
});

function onResponseEstadoFinancieroFuncion(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDataInicialEstadoFinancieroFuncion':
                onResponseObtenerDataInicial(response.data);
                break;
            case 'obtenerEstadoFinancieroFuncionXCriterios':
                onResponseObtenerEstadoFinancieroFuncion(response.data);
                break;
            case 'obtenerEstadoFinancieroFuncionExcel':
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
    ax.setAccion("obtenerDataInicialEstadoFinancieroFuncion");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("tipo", tipo_busquedad);
    ax.consumir();
}

function exportarExcel()
{
    loaderShow();
    ax.setAccion("obtenerEstadoFinancieroFuncionExcel");
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
    ax.setAccion("obtenerEstadoFinancieroFuncionXCriterios");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("valorBusquedad", select2.obtenerValor("cboPeriodo"));
    ax.addParamTmp("tipo", tipo_busquedad);
    ax.consumir();
}


function onResponseObtenerDataInicial(data) {
    let complemento = (tipo_busquedad == 1 ? "mensual" : "acumulado");
    $("#titulo").html("Estado de ganancias y perdidas por función " + complemento);

    if (tipo_busquedad == 1) {
        $("#lblCboPeriodo").html("Periodo :");
        select2.cargar('cboPeriodo', data.dataPeriodo, 'id', ['anio', 'mes']);
        select2.asignarValor('cboPeriodo', data.dataPeriodoActual[0]['id']);
    } else if (tipo_busquedad == 2) {
        $("#lblCboPeriodo").html("Año :");
        select2.cargar('cboPeriodo', data.dataAnio, 'anio', 'anio');
        select2.asignarValor('cboPeriodo', data.dataAnioActual);
    }

    onResponseObtenerEstadoFinancieroFuncion(data.dataReporte);
}

function onResponseObtenerEstadoFinancieroFuncion(data) {
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
        let montoVentaNeta = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeVenta = 100;
        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoVentaNeta, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(100, 2) + '</td>';
        cuerpo_total += '</tr>';
        cuerpo_total += '<tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 74)[0];
        let montoDescuentoRebaja = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeDescuentoRebaja = redondearNumerDecimales(montoDescuentoRebaja / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoDescuentoRebaja, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeDescuentoRebaja, 2) + '</td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 69)[0];
        let montoCostoVenta = redondearNumero((item.haber * 0) - (item.debe * 1));
        let porcentajeCostoVenta = redondearNumerDecimales(montoCostoVenta / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoCostoVenta, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeCostoVenta, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoUtilidadBruta = redondearNumero(montoVentaNeta + montoDescuentoRebaja + montoCostoVenta);
        let porcentajeUtilidadBruta = redondearNumero(porcentajeVenta + porcentajeDescuentoRebaja + porcentajeCostoVenta);
        cuerpo_total += '<td align="right"><b>UTILIDAD BRUTA</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoUtilidadBruta, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeUtilidadBruta, 2) + '</b></td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 95)[0];
        let montoGastoVenta = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeGastoVenta = redondearNumerDecimales(montoGastoVenta / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoVenta, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoVenta, 2) + '</td>';
        cuerpo_total += '</tr>';


        item = data.filter(elemento => parseInt(elemento['codigo']) === 94)[0];
        let montoGastoAdministrativo = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeGastoAdministrativo = redondearNumerDecimales(montoGastoAdministrativo / montoVentaNeta, 6) * porcentajeVenta;

        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoAdministrativo, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoAdministrativo, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoUtilidadOperativa = redondearNumero(montoUtilidadBruta + montoGastoVenta + montoGastoAdministrativo);
        let porcentajeUtilidadOperativa = redondearNumero(porcentajeUtilidadBruta + porcentajeGastoVenta + porcentajeGastoAdministrativo);
        cuerpo_total += '<td align="right"><b>UTILIDAD OPERATIVA</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoUtilidadOperativa, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeUtilidadOperativa, 2) + '</b></td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<td align="left">OTROS INGRESOS Y EGRESOS</td>';
        cuerpo_total += '<td></td>';
        cuerpo_total += '<td></td>';
        cuerpo_total += '</tr>';

        let montoDescuentoRebajaObtenida = 0;
        let porcentajeDescuentoRebajaObtenida = 0;
        cuerpo_total += '<td align="left">Descuentos, Bonificaciones y Rebajas Obtenidas</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoDescuentoRebajaObtenida, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeDescuentoRebajaObtenida, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 75)[0];
        let montoIngresoDiversos = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeIngresoDiversos = redondearNumerDecimales(montoIngresoDiversos / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoIngresoDiversos, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeIngresoDiversos, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoIngresoExcepcional = 0;
        let porcentajeIngresoExcepcional = 0;
        cuerpo_total += '<td align="left">OTROS INGRESOS</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoIngresoExcepcional, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeIngresoExcepcional, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 77)[0];
        let montoIngresoFinanciero = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeIngresoFinanciero = redondearNumerDecimales(montoIngresoFinanciero / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoIngresoDiversos, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeIngresoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoCargaExcepcional = 0;
        let porcentajeCargaExcepcional = 0;
        cuerpo_total += '<td align="left">OTROS GASTOS</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoCargaExcepcional, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeCargaExcepcional, 2) + '</td>';
        cuerpo_total += '</tr>';

        item = data.filter(elemento => parseInt(elemento['codigo']) === 97)[0];
        let montoGastoFinanciero = redondearNumero((item.haber * 1) - (item.debe * 1));
        let porcentajeGastoFinanciero = redondearNumerDecimales(montoGastoFinanciero / montoVentaNeta, 6) * porcentajeVenta;
        cuerpo_total += '<td align="left">' + item.descripcion + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(montoGastoFinanciero, 2) + '</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(porcentajeGastoFinanciero, 2) + '</td>';
        cuerpo_total += '</tr>';

        let montoUtilidadPeriodo = redondearNumero(montoUtilidadOperativa + montoDescuentoRebajaObtenida + montoIngresoDiversos + montoCargaExcepcional + montoIngresoFinanciero + montoIngresoExcepcional + montoGastoFinanciero);
        let porcentajeUtilidadPeriodo = redondearNumero(porcentajeUtilidadOperativa + porcentajeDescuentoRebajaObtenida + porcentajeIngresoDiversos + porcentajeCargaExcepcional + porcentajeIngresoFinanciero + porcentajeIngresoExcepcional + porcentajeGastoFinanciero);
        cuerpo_total += '<td align="right"><b>UTILIDAD ANTES DE PARTICIPACIONES E IMPUESTOS</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(montoUtilidadPeriodo, 2) + '</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(porcentajeUtilidadPeriodo, 2) + '</b></td>';
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