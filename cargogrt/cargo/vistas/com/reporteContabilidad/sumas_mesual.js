var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var total;
var total_cantidad;
$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    select2.iniciar();

    ax.setSuccess("onResponseReporteSumasMensual");
    obtenerDataInicial();
});

function onResponseReporteSumasMensual(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDataInicialReporteSumasMensual':
                onResponseObtenerDataInicial(response.data);
                break;
            case 'obtenerReporteSumasMensualXCriterios':
                onResponseReporteSumaMensual(response.data);
                break;
            case 'obtenerReporteSumasExcel':
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

function obtenerDataInicial()
{
    loaderShow();
    ax.setAccion("obtenerDataInicialReporteSumasMensual");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function exportarExcel()
{
    loaderShow();
    ax.setAccion("obtenerReporteSumasExcel");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("valorBusquedad", select2.obtenerValor("cboPeriodo"));
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function buscarPorCriterios(colapsa)
{
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;

    if (colapsa === 1)
        colapsarBuscador();

    loaderShow();
    ax.setAccion("obtenerReporteSumasMensualXCriterios");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("periodoId", select2.obtenerValor("cboPeriodo"));
    ax.consumir();
}


var dataCuentasContablesTitulo = [];
function onResponseObtenerDataInicial(data) {
    dataCuentasContablesTitulo = data.dataCuentaContableTitulo;
    select2.cargar('cboPeriodo', data.dataPeriodo, 'id', ['anio', 'mes']);
    select2.asignarValor('cboPeriodo', data.dataPeriodoActual[0]['id']);
    onResponseReporteSumaMensual(data.dataReporte);
}

function onResponseReporteSumaMensual(data) {
    $("#dataList").empty();

    var trVacio = '';
    trVacio += '<tr>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '</tr>';

    var cuerpo_total = '';

    cuerpo_total += '<table id="datatable" class="table table-striped table-bordered">';
    cuerpo_total += '<thead>';
    cuerpo_total += '<tr>';
    cuerpo_total += '<th style="text-align:center;">Código</th>';
    cuerpo_total += '<th style="text-align:center;">Cuenta</th>';
    cuerpo_total += '<th style="text-align:center;">Debe inicial</th>';
    cuerpo_total += '<th style="text-align:center;">Haber inicial</th>';
    cuerpo_total += '<th style="text-align:center;">Mov. debe</th>';
    cuerpo_total += '<th style="text-align:center;">Mov. haber</th>';
    cuerpo_total += '<th style="text-align:center;">Saldo deudor</th>';
    cuerpo_total += '<th style="text-align:center;">Saldo acreedor</th>';
    cuerpo_total += '</tr>';
    cuerpo_total += '</thead>';
    cuerpo_total += '<tbody>';

    if (!isEmpty(dataCuentasContablesTitulo) && !isEmpty(data)) {
        $.each(dataCuentasContablesTitulo, function (index, item) {
            let dataCuenta = data.filter(elemento => elemento['plan_contable_codigo'].substring(0, 2) === item.codigo);
            if (isEmpty(dataCuenta)) {
                return;
            }

            cuerpo_total += '<tr>';
            cuerpo_total += '<td align="left"><b>' + item.codigo + '</b></td>';
            cuerpo_total += '<td align="left"><b>' + item.descripcion + '</b></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '<td></td>';
            cuerpo_total += '</tr>';

            let total_debe_inicial = 0;
            let total_haber_inicial = 0;
            let total_debe_movimiento = 0;
            let total_haber_movimiento = 0;
            let total_debe_saldo = 0;
            let total_haber_saldo = 0;
            $.each(dataCuenta, function (indexCuenta, cuenta) {

                let diferencia = redondearNumero(redondearNumero(cuenta.debe_inicial) + redondearNumero(cuenta.debe) - redondearNumero(cuenta.haber_inicial) - redondearNumero(cuenta.haber));
                let debe_saldo = (diferencia > 0 ? redondearNumero(diferencia) : 0);
                let haber_saldo = (diferencia < 0 ? redondearNumero(Math.abs(diferencia)) : 0);

                total_debe_inicial += redondearNumero(cuenta.debe_inicial);
                total_haber_inicial += redondearNumero(cuenta.haber_inicial);
                total_debe_movimiento += redondearNumero(cuenta.debe);
                total_haber_movimiento += redondearNumero(cuenta.haber);
                total_debe_saldo += redondearNumero(debe_saldo);
                total_haber_saldo += redondearNumero(haber_saldo);

                cuerpo_total += '<tr>';
                cuerpo_total += '<td align="left">' + cuenta.plan_contable_codigo + '</td>';
                cuerpo_total += '<td align="left">' + cuenta.plan_contable_descripcion + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(cuenta.debe_inicial, 2) + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(cuenta.haber_inicial, 2) + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(cuenta.debe, 2) + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(cuenta.haber, 2) + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(debe_saldo, 2) + '</td>';
                cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(haber_saldo, 2) + '</td>';
                cuerpo_total += '</tr>';
            });

            cuerpo_total += '<tr>';
            cuerpo_total += '<td align="left"></td>';
            cuerpo_total += '<td align="left"></td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_debe_inicial, 2) + '</td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_haber_inicial, 2) + '</td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_debe_movimiento, 2) + '</td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_haber_movimiento, 2) + '</td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_debe_saldo, 2) + '</td>';
            cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(total_haber_saldo, 2) + '</td>';
            cuerpo_total += '</tr>';

        });
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