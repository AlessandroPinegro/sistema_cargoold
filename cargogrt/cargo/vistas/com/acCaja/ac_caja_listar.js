
$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("exitoListarACCaja");
    obtenerUsuarioDatos();
    modificarAnchoTabla('dataTableACCaja');
    cargarSelect2();
//    listarACCaja();
});

function exitoListarACCaja(response) {
     console.log(response);
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerAperturaCierreUltimo':
                onResponseObtenerAperturaCierreUltimo(response.data);
                loaderClose();
                break;
            case 'obtenerUsuarioDatos':
                onResponseObtenerUsuarioDatos(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesApertura':
                onResponseObtenerDetalleApertura(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesCierre':
                onResponseObtenerDetalleCierre(response.data);
                loaderClose();
                break;
        }
    }
}

function obtenerUsuarioDatos() {
    loaderShow();
    ax.setAccion("obtenerUsuarioDatos");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function dibujarIconosLeyenda(mostrar) {
    if (mostrar) {
        $("#iconos_leyenda").html("<i class='fa fa-edit' style='color:#E8BA2F;'></i> Editar apertura &nbsp;&nbsp;&nbsp;"
                + "<i class='fa fa-edit' style='color:green;'></i> Editar cierre &nbsp;&nbsp;&nbsp;"
                + "<i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;");
    } else {
        $("#iconos_leyenda").html("<i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;");
    }
}

var dataCaja = [];
var mostrarEdicion = false;
function onResponseObtenerUsuarioDatos(data) {
    mostrarEdicion = false;
    $.each(data.dataUsuario[0].perfil.split(";"), function (index, item) {
        if (item == 1 || item == 118) {
            mostrarEdicion = true;
        }
    });

    if (!isEmpty(data.dataAgencia)) {
        select2.cargar("cboAgencia", data.dataAgencia, "id", "codigo");
        if (data.dataAgencia.length == 1) {
            $("#cboAgencia").prop('disabled', true);
        } else {
            $("#cboAgencia").prop('disabled', false);
        }
        select2.asignarValor("cboAgencia", data.dataAgencia[0].id);
        setTimeout(function () {
            onChangeCboAgencia(data.dataAgencia[0].id, data.cajaDefault);
        }, 200);
        dataCaja = data.dataCaja;
    }

    dibujarIconosLeyenda(mostrarEdicion);
//    listarACCaja(mostrarEdicion);

}

function onChangeCboAgencia(valor, cajaId = null) {
    loaderShow();
    if (!isEmpty(valor) && !isEmpty(dataCaja)) {
        let dataCajaFilter = dataCaja.filter(item => item.agencia_id == valor);
        select2.cargar("cboCaja", dataCajaFilter, "id", "codigo");
        if (!isEmpty(dataCajaFilter)) {
            select2.asignarValor("cboCaja", (!isEmpty(cajaId) ? cajaId : dataCajaFilter[0].id));
        } else {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "No tiene ninguna caja asignada para esta agencia.");
        }
        if (dataCajaFilter.length == 1) {
            $("#cboCaja").prop('disabled', true);
        } else {
            $("#cboCaja").prop('disabled', false);
        }
        obtenerAperturaCierreUltimo();
        listarACCaja(mostrarEdicion);
}
}

function onChangeCboCaja(valor) {
    loaderShow();
    obtenerAperturaCierreUltimo();
    listarACCaja(mostrarEdicion);
}

function obtenerAperturaCierreUltimo() {
    loaderShow();
    ax.setAccion("obtenerAperturaCierreUltimo");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("cajaId", select2.obtenerValor("cboCaja"));
    ax.consumir();
}

function onResponseObtenerAperturaCierreUltimo(data) {
    loaderShow();

//$('#idNuevoCierre').show();
//$('#idNuevaApertura').show();
    if (!isEmpty(data.dataAperturaCaja) && !isEmpty(data.dataACCaja)) {
        
        if (data.dataAperturaCaja[0]['fecha_apertura'] > data.dataACCaja[0]['fecha_cierre']) {
            $('#idNuevoCierre').show();
            $('#idNuevaApertura').hide();
        } else {
            $('#idNuevaApertura').show();
            $('#idNuevoCierre').hide();
        }
        
    } else {
     
        if (isEmpty(data.dataAperturaCaja)) {
            $('#idNuevaApertura').show();
            $('#idNuevoCierre').hide();
        }

        if (!isEmpty(data.dataAperturaCaja) && isEmpty(data.dataACCaja)) {
            $('#idNuevoCierre').show();
            $('#idNuevaApertura').hide();
        }
       
    }
    loaderClose();
}

function nuevoApertura() {
    var titulo = "";
    var url = URL_BASE + "vistas/com/acCaja/apertura_caja_form.php?winTitulo=" + titulo + "&cajaId=" + select2.obtenerValor("cboCaja");
    cargarDiv("#window", url);
}

function editarApertura(id) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/acCaja/apertura_caja_form.php?winTitulo=" + titulo + "&id=" + id + "&cajaId=" + select2.obtenerValor("cboCaja");
    cargarDiv("#window", url);
}

function nuevoCierre() {
    var titulo = "";
    var url = URL_BASE + "vistas/com/acCaja/cierre_caja_form.php?winTitulo=" + titulo + "&cajaId=" + select2.obtenerValor("cboCaja");
    cargarDiv("#window", url);
}

function editarCierre(id) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/acCaja/cierre_caja_form.php?winTitulo=" + titulo + "&id=" + id + "&cajaId=" + select2.obtenerValor("cboCaja");
    cargarDiv("#window", url);
}

var criteriosBusquedaACCaja = {};
function listarACCaja(mostrarEdicion) {
    criteriosBusquedaACCaja = {empresaId: commonVars.empresa, cajaId: select2.obtenerValor("cboCaja")};

    ax.setAccion("obtenerDataACCaja");
    ax.addParamTmp("criteriosBusqueda", criteriosBusquedaACCaja);
    $('#dataTableACCaja').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[3, "desc"]],
        "columns": [
//Usuario	Fecha apertura	I. Apertura	Fecha cierre	I. Cierre	Traslado	Visa	Accion
            {"data": "agencia_codigo"},
            {"data": "caja_codigo"},
            {"data": "usuario_apertura"},
            {"data": "fecha_apertura", "class": "alignCenter"},
            {"data": "importe_apertura", "class": "alignRight"},
            {"data": "ac_caja_id", "class": "alignCenter"},
            {"data": "fecha_cierre", "class": "alignCenter"},
            {"data": "importe_cierre", "class": "alignRight"},
            {"data": "monto_traslado", "class": "alignRight"},
            {"data": "monto_visa", "class": "alignRight"},
            {"data": "monto_deposito", "class": "alignRight"},
            {"data": "monto_transferencia", "class": "alignRight"},
            {"data": "ac_caja_id", "class": "alignCenter"}

        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    if (formatearNumero(data) == '0.00') {
                        return '-';
                    } else {
                        return 'S/ ' + formatearNumero(data);
                    }
                },
                "targets": [4, 7, 8, 9, 10, 11]
            },
            {
                "render": function (data, type, row) {
                    if (type == "display") {
                        return row.fecha_apertura_formato;
                    } else {
                        return (!isEmpty(data) ? data.substring(0, 19) : '');
                    }

                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    var html = '';
                    if (mostrarEdicion) {
                        html += '<a title="Editar apertura" onclick="editarApertura(' + data + ')"><i class="fa fa-edit" style="color:#E8BA2F;"></i></a>&nbsp&nbsp&nbsp';
                    }

                    html += '<a title="Visualizar" onclick="visualizar(' + data + ',\'Apertura\')"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';

                    return html;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    return row.fecha_cierre_formato;
                },
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    var html = '';

                    if (!isEmpty(row.fecha_cierre)) {
                        if (mostrarEdicion) {
                            html += '<a title="Editar cierre" onclick="editarCierre(' + data + ')"><i class="fa fa-edit" style="color:green;"></i></a>&nbsp&nbsp&nbsp';
                        }
                        html += '<a title="Visualizar" onclick="visualizar(' + data + ',\'Cierre\')"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';
                    }

                    return html;
                },
                "targets": 12
            }
//            ,
//            {
//                "targets": 8,
//                "visible": mostrarEdicion
//            }
        ],
        destroy: true
    });
    loaderClose();
}

function visualizar(id, texto) {
    loaderShow();
    if (texto == "Apertura") {
        loaderShow();
        ax.setAccion("obtenerConfiguracionesInicialesApertura");
        ax.addParamTmp("empresaId", commonVars.empresa);
        ax.addParamTmp("cajaId", select2.obtenerValor("cboCaja"));
        ax.addParamTmp("idEditar", id);
        ax.consumir();
    } else if (texto == "Cierre") {
        ax.setAccion("obtenerConfiguracionesInicialesCierre");
        ax.addParamTmp("empresaId", commonVars.empresa);
        ax.addParamTmp("cajaId", select2.obtenerValor("cboCaja"));
        ax.addParamTmp("idEditar", id);
        ax.consumir();
    }

    $('#tituloVisualizacionModal').html("Visualizar " + texto);
}

var lstInventario = [];
function onResponseObtenerDetalleApertura(data) {

    if (!isEmpty(data.dataAperturaCierre)) {
        $('#spFecha').html('al ' + data.dataAperturaCierre[0]["fecha_apertura_formato"]
                + " - turno: " + data.dataAperturaCierre[0]["turno_apertura"]);
    }

    if (isEmpty(data.dataACCaja)) {
        $('#importeCierre1').html("0.00");
        $('#fechaCierre').html('Cierre anterior');
    } else {
        var dataCierre = data.dataACCaja[0];
        $('#txtComentarioCierre').val(dataCierre.comentario_cierre);
        $('#fechaCierre').html('Cierre al ' + '<span class="label label-inverse">' +
                dataCierre.fecha_cierre_formato + '</span>' +
                ' | <span class="label label-inverse">' +
                dataCierre.usuario_cierre + '</span>');
        $('#importeCierre1').html(formatearNumero(dataCierre.importe_cierre));

        if (isEmpty(data.dataAperturaCierre)) {
            $('#importeApertura').html(formatearNumero(dataCierre.importe_cierre));
            aperturaSugerido = formatearNumero(dataCierre.importe_cierre);
        }
    }

    if (!isEmpty(data.dataAperturaCierre)) {
        var dataAperturaCierre = data.dataAperturaCierre[0];
        $('#importeApertura').html("S/ " + formatearNumero(dataAperturaCierre.importe_apertura));
        $('#txtComentario').val(dataAperturaCierre.comentario_apertura);
        if (dataAperturaCierre.is_pintar_apertura != 0) {
            $('#importeApertura').css('color', '#f44336');
        }
        aperturaSugerido = dataAperturaCierre.apertura_sugerido;
    }

    $('#sugerido').html("<p title='Efectivo sugerido: " + aperturaSugerido + "' class='fa fa-info-circle' style='color:#fff;'>");

    $('#modalDetalle').modal('show');
    $('#detalleApertura').show();
    $('#detalleCierre').hide();

}

function onResponseListarBienApertura(dataInventario) {
    $("#dataListInventario").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatableInventario' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>#</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Producto</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Cantidad</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Stock <i title='Stock referencial' class='fa fa-info-circle' style='color:#1ca8dd;'></i></th>"
            + "<th style='text-align:center; vertical-align: middle;'>Unidad Medida</th>"
            + "</tr>"
            + "</thead>";
    if (!isEmpty(dataInventario)) {
        $.each(dataInventario, function (index, item) {

            lstInventario[index]["cant_apertura_cierre"] = redondearNumero(item.cant_apertura_cierre).toFixed(2);
            lstInventario[index]["stock"] = formatearNumero(item.stock);
            lstInventario[index]["stock_apertura"] = formatearNumero(item.stock_apertura);

            var style = "";
            if (item.is_pintar == 1) {
                style = "color: red; background-color: #f2d889';";
            }
            cuerpo = "<tr>"
                    + "<td width='10%' style='text-align:center; vertical-align: middle;'>" + (index + 1) + "</td>"
                    + "<td style='text-align:left; vertical-align: middle;'>" + item.bien_descripcion + "</td>"
                    + "<td width='15%' style='text-align:center;" + style + "'>" + item.cant_apertura_cierre
                    + "&ensp;<span><p title='Sugerido apertura: " + item.stock_apertura + "' class='fa fa-info-circle' style='color:#1ca8dd;'></span></div></td>"
                    + "<td width='10%' style='text-align:right;vertical-align: middle;'>" + item.stock + "</td>"
                    + "<td width='15%' style='text-align:left;vertical-align: middle;'>" + item.unidad_medida_descripcion + "</td>";
            +"</tr>";

            cuerpo_total += cuerpo;
        });
        $('#txtComentarioInventario').val(dataInventario[0]["comentario"]);
    }
    var pie = '</table>';

    var html = cabeza + cuerpo_total + pie;
    $("#dataListInventario").append(html);
    onResponseVacio('datatableInventario', [0, "asc"]);

}

var visa = '0.00';
var deposito = '0.00';
var traslado = '0.00';
var transferencia = '0.00';
var efectivo = '0.00';
var total = '0.00';
function onResponseObtenerDetalleCierre(data) {

    if (!isEmpty(data.dataApertura)) {
        $('#spFecha').html('al ' + data.dataAperturaCierre[0]["fecha_cierre_formato"]
                + " - turno: " + data.dataAperturaCierre[0]["turno_cierre"]);
    }

    visa = redondearDosDecimales(data.dataVisa[0]['importe_total']);
    deposito = redondearDosDecimales(data.dataDeposito[0]['importe_total']);
    transferencia = redondearDosDecimales(data.dataTransferencia[0]['importe_total']);
    var visaInfo = visa;
    var depositoInfo = deposito;
    var transferenciaInfo = transferencia;

    if (!isEmpty(data.dataCajaChica)) {
        $('#importeCierre').val(efectivo);
    }

    if (!isEmpty(data.dataAperturaCaja)) {
        var dataApertura = data.dataAperturaCaja[0];

        importe_apertura = redondearDosDecimales(dataApertura.importe_apertura);

        if (!isEmpty(data.dataAperturaCierre)) {
            var dataAperturaCierre = data.dataAperturaCierre[0];
            if (!isEmpty(dataAperturaCierre.fecha_cierre)) {
                visa = redondearDosDecimales(dataAperturaCierre.visa);
                deposito = redondearDosDecimales(dataAperturaCierre.deposito);
                transferencia = redondearDosDecimales(dataAperturaCierre.transferencia);
            }
            traslado = redondearDosDecimales(dataAperturaCierre.traslado);
            efectivo = redondearDosDecimales(dataAperturaCierre.importe_cierre);
            importe_apertura = redondearDosDecimales(dataAperturaCierre.importe_apertura);
            $('#txtComentario2').val(dataAperturaCierre.comentario_cierre);

            if (dataAperturaCierre.is_pintar_cierre != 0) {
                $('#importeTotal').css('color', 'red');
                $('#importeTotal').css('background-color', '#f2d889');
            }

            if (dataAperturaCierre.is_pintar_visa != 0) {
                $('#importeVisa').css('color', 'red');
                $('#importeVisa').css('background-color', '#f2d889');
            }

            if (dataAperturaCierre.is_pintar_deposito != 0) {
                $('#importeDeposito').css('color', 'red');
                $('#importeDeposito').css('background-color', '#f2d889');
            }

            if (dataAperturaCierre.is_pintar_transferencia != 0) {
                $('#importeTransferencia').css('color', 'red');
                $('#importeTransferencia').css('background-color', '#f2d889');
            }
        }

        $('#txtComentarioApertura').val(dataApertura.comentario_apertura);
        $('#fechaApertura').html('Resumen al ' + '<span class="label label-inverse">' +
                dataApertura.fecha_apertura_formato + '</span>' +
                ' | <span class="label label-inverse">' +
                dataApertura.usuario_apertura + '</span>');


        $('#id').val(formatearNumero(dataApertura.ac_caja_id));

        $("#dataList").empty();
        var cuerpo_total = "";
        var cuerpo = "";
        var descripcion = "0.00";
        var importe = "0.00";
        var cont_filas = 0;
        var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
                + "<thead>"
                + "<tr class='info'>"
                + "<th width='40%' style='text-align: center;' class='celda-centrado'>Descripción</th>"
                + "<th style='text-align: center;' class='celda-centrado'>Monto</th>"
                + "<th style='text-align: center;' class='celda-centrado'>Total</th>"
                + "</tr>"
                + "</thead>";
        if (!isEmpty(data.dataIngresoSalida)) {
            var cont = data.dataIngresoSalida.length;
            var bcp = '0.00';

            for (var i = 0; i <= 5; i++) {

                if (i == 0) {
                    descripcion = "Apertura en efectivo";
                    importe = formatearNumero(dataApertura.importe_apertura);
                } else if (i == 1) {
                    descripcion = "Ingresos en efectivo";

                    if (cont == 1) {
                        importe = (data.dataIngresoSalida[0]['ing_sal'] == "I") ?
                                formatearNumero(data.dataIngresoSalida[0]['total_conversion']) :
                                "0.00";
                    } else {
                        importe = formatearNumero(data.dataIngresoSalida[0]['total_conversion']);
                    }
                } else if (i == 2) {
                    descripcion = "Salidas en efectivo";

                    if (cont == 1) {
                        importe = (data.dataIngresoSalida[0]['ing_sal'] == "S") ?
                                formatearNumero(data.dataIngresoSalida[0]['total_conversion']) :
                                "0.00";
                    } else {
                        importe = formatearNumero(data.dataIngresoSalida[1]['total_conversion']);
                    }
                } else if (i == 3) {
                    descripcion = "POS";
                    importe = formatearNumero(data.dataVisa[0]['importe_total']);
                } else if (i == 4) {
                    descripcion = "Depósito";
                    importe = formatearNumero(data.dataDeposito[0]['importe_total']);
                } else if (i == 5) {
                    descripcion = "Transferencia";
                    importe = formatearNumero(data.dataTransferencia[0]['importe_total']);
                }

                cuerpo = "<tr>"
                        + "<td class='celda-centrado'>" + descripcion + "</td>"
                        + "<td align='right'>S/ " + importe + "</td>";
//                debugger;
                if (cont_filas == 0) {
                    if (i < 3) {
                        efectivo = dataApertura.importe_apertura;

                        for (var x = 0; x < cont; x++) {
                            efectivo = redondearDosDecimales(parseFloat(efectivo) + parseFloat(data.dataIngresoSalida[x]['total_conversion']));
                        }

                        cuerpo += "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ " + efectivo + "</td>";
                        cont_filas = 1;
                    }
                } else if (i == 3) {
                    bcp = formatearNumero(parseFloat(visa) + parseFloat(deposito) + parseFloat(transferencia));

                    cuerpo += "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle; font-weight: bold;'>S/ " + bcp + "</td>";

                }

                cuerpo += "</tr>";
                cuerpo_total += cuerpo;
            }
        } else {
            cuerpo = "<tr>"
                    + "<td class='celda-centrado'>Apertura en efectivo</td>"
                    + "<td align='center'>S/ " + importe_apertura + "</td>"
                    + "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ " + importe_apertura + "</td>"
                    + "</tr>"
                    + "<tr>"
                    + "<td class='celda-centrado'>Ingresos en efectivo</td>"
                    + "<td align='center'>S/ 0.00</td>"
                    + "</tr>"
                    + "<tr>"
                    + "<td class='celda-centrado'>Salidas en efectivo</td>"
                    + "<td align='center'>S/ 0.00</td>"
                    + "</tr>"
                    + "<tr>"
                    + "<td class='celda-centrado'>POS</td>"
                    + "<td align='center'>S/ " + visaInfo + "</td>"
                    + "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ "
                    + (formatearNumero(parseFloat(visaInfo) + parseFloat(depositoInfo))) + "</td>"
                    + "</tr>"
                    + "<tr>"
                    + "<td class='celda-centrado'>Depósito</td>"
                    + "<td align='center'>S/ " + depositoInfo + "</td>"
                    + "</tr>"
                    + "<td class='celda-centrado'>Transferencia</td>"
                    + "<td align='center'>S/ " + transferenciaInfo + "</td>"
                    + "</tr>";
            cuerpo_total += cuerpo;

            efectivo = importe_apertura;
        }

        var pie = '</table>';
        var html = cabeza + cuerpo_total + pie;
        $("#dataList").append(html);

    }

    efectivo = !isEmpty(data.dataAperturaCierre) ? data.dataAperturaCierre[0]["importe_cierre"] : efectivo;


    $('#importeVisa').val(visa);
    $('#importeDeposito').val(deposito);
    $('#importeTransferencia').val(transferencia);

    total = redondearDosDecimales(parseFloat(efectivo) + parseFloat(traslado));

    $('#importeTraslado').val(traslado);
    $('#importeCierre').val(redondearDosDecimales(efectivo));

    $('#importeTotal').val(total);


    $('#modalDetalle').modal('show');
    $('#detalleApertura').hide();
    $('#detalleCierre').show();
}

function onResponseListarBienCierre(dataInventario) {

    $("#dataListInventario2").empty();

    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatableInventario2' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>#</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Producto</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Cierre</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Ingreso</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Salida</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Unidad Medida</th>"
            + "</tr>"
            + "</thead>";

    if (!isEmpty(dataInventario)) {

        $.each(dataInventario, function (index, item) {

            lstInventario[index]["cant_apertura_cierre"] = formatearNumero(lstInventario[index]["cant_apertura_cierre"]);
            lstInventario[index]["stock"] = formatearNumero(lstInventario[index]["stock"]);

            var style = "";

            if (item.is_pintar == 1) {
                style = "color: red; background-color: #f2d889';";
            }

            cuerpo = "<tr>"
                    + "<td style='text-align:center;vertical-align: middle;'>" + (index + 1) + "</td>"
                    + "<td width='40%' style='text-align:left;vertical-align: middle;'>" + item.bien_descripcion + "</td>"
                    + "<td style='text-align:right;vertical-align: middle;"
                    + style + "'>" + formatearNumero(item.cant_apertura_cierre)
                    + " <i class='fa fa-info-circle' style='color:#1ca8dd;' title='Stock cierre sugerido: "
                    + formatearNumero(item.stock_apertura) + "'></i></td>"
                    + "<td style='text-align:right;vertical-align: middle;'>" + formatearNumero(item.ingreso_almacen) + "</td>"
                    + "<td style='text-align:right;vertical-align: middle;'>" + formatearNumero(item.salida_almacen) + "</td>"
                    + "<td style='text-align:left;vertical-align: middle;'>" + item.unidad_medida_descripcion + "</td>"
                    + "</tr>";
            cuerpo_total += cuerpo;
        });

        $('#txtComentarioInventario2').val(dataInventario[0]["comentario"]);
    }

    var pie = '</table>';

    var html = cabeza + cuerpo_total + pie;
    $("#dataListInventario2").append(html);
    onResponseVacio('datatableInventario2', [0, "asc"]);

}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}
