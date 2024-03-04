$(document).ready(function () {
    ax.setSuccess("exitoInvPermvalorizado");
    listar();
    modificarAnchoTabla('datatable');
    inicializaComponentes();
});
function inicializaComponentes() {
    select2.iniciar();
}

function listar() {
    ax.setAccion("listar");
    ax.consumir();
}

function exitoInvPermvalorizado(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listar':
                if (!isEmpty(response.data)) {
                    dataLibroTemp = (!isEmpty(response.data) ? response.data : []);
                    $.each(response.data, function (index, item) {
                        response.data[index]["txt_nombre"] = "<a href='" + URL_BASE + "util/uploads/" + item["txt_nombre"] + "' download style='color: #337ab7;font-weight: bold;'>" + item["txt_nombre"] + "</a>";
                        response.data[index]["excel_nombre"] = "<a href='#' style='color: #4caf50;font-weight: bold;' onclick='abrirExcel(\"" + item["excel_nombre"] + "\")'>" + item["excel_nombre"] + "</a>";
                        response.data[index]["acciones"] = ``;
                        if (!isEmpty(item.parametros_saldos)) {
                            response.data[index]["acciones"] = `<a   onclick="mostrarSaldoGenerados( ` + item.id + `)"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>`;
                        }
                    });
                    $('#datatable').dataTable({
                        "scrollX": true,
                        "autoWidth": true,
                        "order": [[4, "desc"]],
                        "data": response.data,
                        "columns": [
                            {"data": "periodo"},
                            {"data": "excel_nombre"},
                            {"data": "txt_nombre"},
                            {"data": "usuario"},
                            {"data": "fecha_creacion"},
                            {"data": "estado"},
                            {"data": "acciones"}
                        ],
                        "destroy": true
                    });
                }
                //onResponseListarPlanContablePadres(response.data);
                loaderClose();
                break;
            case 'generarLibro':
//                window.location.href = URL_BASE + "util/uploads/"+response.data;
                $('#modalImportar').modal('hide');
                listar();
                loaderClose();
                break;
            case 'generarExcel':
//                listar();
                $('#modalGenerarExcel').modal('hide');
//                location.href = URL_BASE + "util/formatos/kardex.xls";
                window.location.href = URL_BASE + "util/uploads/" + response.data.nombre;
                loaderClose();
                break;
            case 'generarResumen':
                $('#modalGenerarExcelResumen').modal('hide');
                window.location.href = URL_BASE + "util/uploads/" + response.data.nombre;
                loaderClose();
                break;
        }
    }
}

function abrirTxt(txt) {
    window.location.href = URL_BASE + "util/uploads/" + txt;
}
function abrirExcel(excel) {
    window.location.href = URL_BASE + "util/uploads/" + excel;
}

function preparaGenerar() {
    $('#btnGenerar').show();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboAnio").attr("disabled", false);
    $("#cboMes").attr("disabled", false);

    $('#resultado').empty();
    $('#file').val('');
    $('#secretFile').attr('value', null);
    $("#lblImportarArchivo").text("Seleccione archivo excel");
    $('#modalImportar').modal('show');
}
/*IMPORTAR EXCEL*/
$(function () {
    $(":file").change(function () {
        //validar que la extension sea .xls
        var nombreArchivo = $(this).val().slice(12);
        var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();

        if (extension != "xls") {
            $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'La extensión del excel tiene que ser .xls');
            return;
        }

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#lblImportarArchivo").text(nombreArchivo);
                $('#secret').attr('value', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
            $fileupload = $('#file');
            $fileupload.replaceWith($fileupload.clone(true));
        }
    });
});

function generar()
{
    var file = document.getElementById('secret').value;
    if (isEmpty($('#secret').attr('value'))) {
        mostrarAdvertencia("No se especificó un archivo correcto!");
        return;
    }
    loaderShow('#modalImportar');
    var anio = $('#cboAnio').val();
    var mes = $('#cboMes').val();

    $('#btnGenerar').hide();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboAnio").attr("disabled", true);
    $("#cboMes").attr("disabled", true);

    ax.setAccion("generarLibro");
    ax.addParam("file", file);
    ax.addParam("anio", anio);
    ax.addParam("mes", mes);
    ax.consumir();
}

function preparaGenerarExcel() {
    $('#modalGenerarExcel').modal('show');
}

function generarExcel()
{
    var anio = $('#cboAnioExcel').val();
    var mes = $('#cboMesExcel').val();

    loaderShow('#modalGenerarExcel');
    ax.setAccion("generarExcel");
    ax.addParam("anio", anio);
    ax.addParam("mes", mes);
    ax.consumir();
}

function preparaGenerarResumen() {
    $('#modalGenerarExcelResumen').modal('show');
}

function generarResumen() {
    var anio = select2.obtenerValor('cboAnioExcelResumen');

    loaderShow('#modalGenerarExcelResumen');
    ax.setAccion("generarResumen");
    ax.addParam("anio", anio);
    ax.consumir();
}
var dataLibroTemp = [];
function mostrarSaldoGenerados(id) {

    if (isEmpty(dataLibroTemp)) {
        $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'No se obtuvo la información de los saldos.');
        return;
    }

    let dataBusquedadLibro = dataLibroTemp.filter(item => item.id == id);
    if (isEmpty(dataBusquedadLibro)) {
        $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'No se obtuvo la información de los saldos.');
        return;
    }

//    let dataSaldos = JSON.parse('{"saldo_mes_anterior":1967799.98,"compras_periodo":413256.75,"consumos_periodo":75067.08,"saldo_final":2305989.65,"compras_nacionales":17142.8,"compras_importacion":373483.79,"costo_venta":52436.92}');
    let dataSaldos = JSON.parse(dataBusquedadLibro[0]['parametros_saldos']);


    $("#tlSaldoCompras").html(`<b>Saldo de compras - ` + dataBusquedadLibro[0]['periodo'] + `</b>`);
    $("#dataListSaldos").empty();

    var trVacio = '';
    trVacio += '<tr>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '<td></td>';
    trVacio += '</tr>';

    var cuerpo_total = '';

    cuerpo_total += '<table id="datatableSaldos" class="table table-striped table-bordered">';
    cuerpo_total += '<thead>';
    cuerpo_total += '<tr>';
    cuerpo_total += '<th style="text-align:center;">Concepto</th>';
    cuerpo_total += '<th style="text-align:center;">Monto</th>';
    cuerpo_total += '<th style="text-align:center;">Signo</th>';
    cuerpo_total += '</tr>';
    cuerpo_total += '</thead>';
    cuerpo_total += '<tbody>';

    if (!isEmpty(dataSaldos) && dataSaldos !== undefined) {

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Saldo mes anterior</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.saldo_mes_anterior, 2) + '</td>';
        cuerpo_total += '<td align="right">(+)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Compras periodo</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.compras_periodo, 2) + '</td>';
        cuerpo_total += '<td align="right">(+)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Comsumos periodo</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.consumos_periodo, 2) + '</td>';
        cuerpo_total += '<td align="right">(-)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="right"><b>SALDO FINAL</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(dataSaldos.saldo_final, 2) + '</b></td>';
        cuerpo_total += '<td align="right"></td>';
        cuerpo_total += '</tr>';

//        cuerpo_total += '<tr><td colspan ="3"></td></tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Saldo mes anterior</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.saldo_mes_anterior, 2) + '</td>';
        cuerpo_total += '<td align="right">(+)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Compras nacionales</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.compras_nacionales, 2) + '</td>';
        cuerpo_total += '<td align="right">(+)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Compras extranjeras</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.compras_importacion, 2) + '</td>';
        cuerpo_total += '<td align="right">(+)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="left">Saldo final</td>';
        cuerpo_total += '<td align="right">' + formatearNumeroPorCantidadDecimales(dataSaldos.saldo_final, 2) + '</td>';
        cuerpo_total += '<td align="right">(-)</td>';
        cuerpo_total += '</tr>';

        cuerpo_total += '<tr>';
        cuerpo_total += '<td align="right"><b>COSTO DE VENTA</b></td>';
        cuerpo_total += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(dataSaldos.costo_venta, 2) + '</b></td>';
        cuerpo_total += '<td align="right"></td>';
        cuerpo_total += '</tr>';


    }

    cuerpo_total += '</tbody></table>';

    console.log(cuerpo_total);

    $("#dataListSaldos").append(cuerpo_total);

    $('#datatableSaldos').dataTable({
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


    $('#modalCalculoSaldo').modal('show');

}