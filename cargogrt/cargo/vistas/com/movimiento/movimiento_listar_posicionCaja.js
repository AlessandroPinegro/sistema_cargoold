
$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("exitoListarACCaja"); 
    obtenerUsuarioDatos();
    modificarAnchoTabla('dataTableACCaja');
    cargarSelect2();
    iniciarDataPicker();
 //   obtenerWidgets()
//    listarACCaja();
});

function exitoListarACCaja(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
             case 'exportarPosicionCaja':
                loaderClose();
                location.href = URL_BASE + "util/formatos/posicion_caja.xlsx";
                break;
            case 'exportarPosicionCajaPdf':
                loaderClose();
                window.open(URL_BASE + 'pdf2.php?url_pdf=' + URL_BASE + "vistas/com/movimiento/documentos/" + response.data.nombre_pdf+'.pdf' + '&nombre_pdf=' + response.data.nombre_pdf);
                // setTimeout(function () {
                //     ax.setAccion("eliminarPDF");
                //     ax.addParamTmp("url", response.data.url);
                //     ax.consumir();
            
                // }, 500);
                break;
            case 'obtenerAperturaCierreUltimo':
                onResponseObtenerAperturaCierreUltimo(response.data);
                loaderClose();
                break;
            case 'obtenerUsuarioDatosCaja':
                onResponseObtenerUsuarioDatos(response.data);
                loaderClose();
                break;
              
               case 'obtenerWidgets':
                onResponseObtenerWidgets(response.data);
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

function iniciarDataPicker() {
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}

function obtenerUsuarioDatos() {
    loaderShow();
    ax.setAccion("obtenerUsuarioDatosCaja");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function obtenerWidgets() {
    loaderShow();
       criteriosBusquedaACCaja = {
        empresaId: commonVars.empresa,
        cajaId: select2.obtenerValor("cboCaja"),
        periodoId: select2.obtenerValor("cboPeriodo"),
        usuarioId: select2.obtenerValor("cboUsuario"),
        agenciaId: select2.obtenerValor("cboAgencia"),
        fechaInicio: $('#fechaInicio').val(),
        fechaFin: $('#fechaFin').val()
    };
    ax.setAccion("obtenerWidgets");
    ax.addParamTmp("criteriosBusqueda", criteriosBusquedaACCaja);
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
        select2.cargar("cboAgencia", data.dataAgencia, "agencia_id", "codigo");
         select2.cargar("cboPeriodo", data.periodo, "id", ["mes_nombre","anio"]);
         select2.cargar("cboUsuario", data.dataUsuario, "usuario_id", "usuario");
        if (data.dataAgencia.length == 1) {
            $("#cboAgencia").prop('disabled', true);
        } else {
            $("#cboAgencia").prop('disabled', false);
        }
        select2.asignarValor("cboAgencia", data.dataAgencia[0].agencia_id);
        setTimeout(function () {
            onChangeCboAgencia(data.dataAgencia[0].agencia_id, data.cajaDefault);
        }, 200);
        dataCaja = data.dataCaja;
    }

    dibujarIconosLeyenda(mostrarEdicion);
//    listarACCaja(mostrarEdicion);

}
function onResponseObtenerWidgets(data) {

     let monedaSimbolo = "S/";
$("#totalS").html(monedaSimbolo + ' ' +parseFloat(data.efectivoTotal).formatMoney(2,'.',','));
$("#totalEfectivo").html(monedaSimbolo + ' ' +parseFloat(data.efectivoTotal).formatMoney(2,'.',','));
$("#totalT").html(monedaSimbolo + ' ' +parseFloat(data.egreso).formatMoney(2,'.',','));
$("#totalT2").html(monedaSimbolo + ' ' +parseFloat(data.egreso).formatMoney(2,'.',','));
$("#totalPOS").html(monedaSimbolo + ' ' +parseFloat(data.posTotal).formatMoney(2,'.',','));
$("#totalDeposito").html(monedaSimbolo + ' ' +parseFloat(data.depositoTotal).formatMoney(2,'.',','));
$("#totalTransferencia").html(monedaSimbolo + ' ' +parseFloat(data.transferenciaTotal).formatMoney(2,'.',','));
$("#ingresos").html(monedaSimbolo + ' ' +parseFloat(data.ingresos).formatMoney(2,'.',','));
$("#egresoPos").html(monedaSimbolo + ' ' +parseFloat(data.egresoPos).formatMoney(2,'.',','));
$("#traslado").html(monedaSimbolo + ' ' +parseFloat(data.traslado).formatMoney(2,'.',','));
$("#egresoOtros").html(monedaSimbolo + ' ' +parseFloat(data.egresoOtros).formatMoney(2,'.',','));
$("#totalT3").html(monedaSimbolo + ' ' +parseFloat(data.egreso+data.egresoPos+data.egresoOtros).formatMoney(2,'.',','));


$("#totalD").html(monedaSimbolo + ' ' +parseFloat(data.apertura).formatMoney(2,'.',','));
$("#totalP").html(monedaSimbolo + ' ' +parseFloat(data.efectivoTotal+data.transferenciaTotal+data.depositoTotal+data.posTotal+data.egreso+data.apertura).formatMoney(2,'.',','));

$("#totalIngresos").html(monedaSimbolo + ' ' +parseFloat(data.efectivoTotal+data.transferenciaTotal+data.depositoTotal+data.posTotal+data.ingresos).formatMoney(2,'.',','));

/*
$("#totalT").html(data.transferenciaTotal);
$("#totalD").html(data.depositoTotal);
$("#totalP").html(data.posTotal);
*/
//    listarACCaja(mostrarEdicion);
drawChart(data);
}
function onChangeCboAgencia(valor,cajaId = null) {
   select2.asignarValor("cboCaja", "-1");
           if (!isEmpty(valor) && !isEmpty(dataCaja)) {
        let dataCajaFilter = dataCaja.filter(item => item.agencia_id == valor);
//        select2.cargar("cboCaja", dataCajaFilter, "id", "codigo");
        select2.cargar("cboCaja", dataCajaFilter, "caja_id", "descripcion");
       

        obtenerAperturaCierreUltimo();
        listarACCaja(mostrarEdicion);
    }
}

function onChangeCboCaja(valor) {
    obtenerAperturaCierreUltimo();
    listarACCaja(mostrarEdicion);
}

function obtenerAperturaCierreUltimo() {
    ax.setAccion("obtenerAperturaCierreUltimo");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("cajaId", select2.obtenerValor("cboCaja"));
    ax.consumir();
}

function onResponseObtenerAperturaCierreUltimo(data) {
 
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
}

var criteriosBusquedaACCaja = {};
function listarACCaja(mostrarEdicion) {
    obtenerWidgets();
    criteriosBusquedaACCaja = {
        empresaId: commonVars.empresa,
        cajaId: select2.obtenerValor("cboCaja"),
        periodoId: select2.obtenerValor("cboPeriodo"),
        usuarioId: select2.obtenerValor("cboUsuario"),
        agenciaId: select2.obtenerValor("cboAgencia"),
        fechaInicio: $('#fechaInicio').val(),
        fechaFin: $('#fechaFin').val()
    };


    ax.setAccion("obtenerDataACCajaReporte");
    ax.addParamTmp("criteriosBusqueda", criteriosBusquedaACCaja);
    let pago= select2.obtenerValor("cboPago");


           var banderaMostrarColumnaE = true;
            var banderaMostrarColumnaT = true;
             var banderaMostrarColumnaD = true;
              var banderaMostrarColumnaP = true;
          
                
    if(pago == 1 ){
        var banderaMostrarColumnaT= false;
        var banderaMostrarColumnaD= false;
        var banderaMostrarColumnaP= false;
    }
      if(pago == 2 ){
        var banderaMostrarColumnaT= false;
        var banderaMostrarColumnaD= false;
        var banderaMostrarColumnaE= false;
    }
    
          if(pago == 3 ){
        var banderaMostrarColumnaT= false;
        var banderaMostrarColumnaP= false;
        var banderaMostrarColumnaE= false;
    }
    
          if(pago == 4 ){
        var banderaMostrarColumnaP= false;
        var banderaMostrarColumnaD= false;
        var banderaMostrarColumnaE= false;
    }
    $('#dataTableACCaja').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(true),
        "scrollX": true,
        "autoWidth": true,
        "order": [[3, "desc"]],
        "columns": [
//Usuario	Fecha apertura	I. Apertura	Fecha cierre	I. Cierre	Traslado	Visa	Accion
            {"data": "agencia_codigo"},
            {"data": "caja_codigo"},
            {"data": "usuario_apertura"},
            {"data": "fecha_apertura", "class": "alignCenter" },
            {"data": "fecha_cierre", "class": "alignCenter"},
            {"data": "importe_apertura", "class": "alignRight"},
//            {"data": "ac_caja_id", "class": "alignCenter"},
            
            {"data": "monto_efectivo", "class": "alignRight"},
//            {"data": "monto_traslado", "class": "alignRight"},
            {"data": "monto_visa", "class": "alignRight"},
            {"data": "monto_deposito", "class": "alignRight"},
            {"data": "monto_transferencia", "class": "alignRight"},
             {"data": "ingreso_caja", "class": "alignRight"},
              {"data": "egreso", "class": "alignRight"},
         {"data": "egreso_ajustado", "class": "alignRight"},
         {"data": "traslado", "class": "alignRight"},
         {"data": "egreso_otros", "class": "alignRight"},
         {"data": "saldo_ajustado", "class": "alignRight"}
//            {"data": "ac_caja_id", "class": "alignCenter"}

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
                "targets": [ 6,  10,11,12,13,14]
            },
            
            {
                "render": function (data, type, row) {
                     if (formatearNumero(data) == '0.00') {
                        return '-';
                    }
                    if (row.importe_apertura == row.apertura_sugerido) {
                        return 'S/ ' + formatearNumero(data);
                    } else {
                        return '<label style="color:red">S/ ' + formatearNumero(data)+ '</style>';
                    }
                },
                "targets":  5
            },
            
            
            
                  {
                "render": function (data, type, row) {
                     if (formatearNumero(data) == '0.00') {
                        return '-';
                    }
                    if (row.monto_visa == row.visa_sugerido) {
                        return 'S/ ' + formatearNumero(data);
                    } else {
                        return '<label style="color:red">S/ ' + formatearNumero(data)+ '</style>';
                    }
                },
                "targets":  7
            },
            
              {
                "render": function (data, type, row) {
                     if (formatearNumero(data) == '0.00') {
                        return '-';
                    }
                    if (row.monto_deposito == row.deposito_sugerido) {
                        return 'S/ ' + formatearNumero(data);
                    } else {
                        return '<label style="color:red">S/ ' + formatearNumero(data)+ '</style>';
                    }
                },
                "targets":  8
            },
            
              {
                "render": function (data, type, row) {
                     if (formatearNumero(data) == '0.00') {
                        return '-';
                    }
                    if (row.monto_transferencia == row.transferencia_sugerido) {
                        return 'S/ ' + formatearNumero(data);
                    } else {
                        return '<label style="color:red">S/ ' + formatearNumero(data)+ '</style>';
                    }
                },
                "targets":  9
            },
            
             {
                "render": function (data, type, row) {
                     if (formatearNumero(data) == '0.00') {
                        return '-';
                    }
                    if (row.saldo_final == row.saldo_final_sugerido) {
                        return 'S/ ' + formatearNumero(data);
                    } else {
                        return '<label style="color:red">S/ ' + formatearNumero(data)+ '</style>';
                    }
                },
                "targets":  15
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
//            {
//                "render": function (data, type, row) {
//                    var html = '';
//                    if (mostrarEdicion) {
//                        html += '<a title="Editar apertura" onclick="editarApertura(' + data + ')"><i class="fa fa-edit" style="color:#E8BA2F;"></i></a>&nbsp&nbsp&nbsp';
//                    }
//
//                    html += '<a title="Visualizar" onclick="visualizar(' + data + ',\'Apertura\')"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';
//
//                    return html;
//                },
//                "targets": 5
//            },
            {
                "render": function (data, type, row) {
                    return row.fecha_cierre_formato;
                },
                "targets": 4
          }
          
//            ,
//            {
//                "render": function (data, type, row) {
//                    var html = '';
//
//                    if (!isEmpty(row.fecha_cierre)) {
//                        if (mostrarEdicion) {
//                            html += '<a title="Editar cierre" onclick="editarCierre(' + data + ')"><i class="fa fa-edit" style="color:green;"></i></a>&nbsp&nbsp&nbsp';
//                        }
//                        html += '<a title="Visualizar" onclick="visualizar(' + data + ',\'Cierre\')"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';
//                    }
//
//                    return html;
//                },
//                "targets": 11
//            }
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
            if(!isEmpty(dataAperturaCierre.fecha_cierre)){
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

function limpiarFiltros() {

    loaderShow();

       select2.asignarValor("cboCaja", "-1");

    select2.asignarValor("cboUsuario", "-1");
    
    select2.asignarValor("cboPeriodo", "-1");
    


    $("#fechaInicio").val('');
    $("#fechaFin").val('');
 

//    setTimeout(function () {
////        $($("#cboClienteListado").data("select2").search).on('keyup', function (e) {
////            if (e.keyCode === 13) {
////                obtenerDataCombo('ClienteListado');
////            }
////        });
//
//        $($("#cboRemitenteListado").data("select2").search).on('keyup', function (e) {
//            if (e.keyCode === 13) {
//                obtenerDataCombo('RemitenteListado');
//            }
//        });
//
//
//        $($("#cboDestinatarioListado").data("select2").search).on('keyup', function (e) {
//            if (e.keyCode === 13) {
//                obtenerDataCombo('DestinatarioListado');
//            }
//        });
//
//        
//    }, 1000);
    loaderClose();
}

function buscarPedidos() {
//   colapsarBuscador();

   listarACCaja(mostrarEdicion);
}

function colapsarBuscador() {
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

  function exportarPosicionCaja(){
  
    actualizandoBusqueda = true;
    loaderShow();
    criteriosBusquedaACCaja = {
        empresaId: commonVars.empresa,
        cajaId: select2.obtenerValor("cboCaja"),
        periodoId: select2.obtenerValor("cboPeriodo"),
        usuarioId: select2.obtenerValor("cboUsuario"),
        agenciaId: select2.obtenerValor("cboAgencia"),
        fechaInicio: $('#fechaInicio').val(),
        fechaFin: $('#fechaFin').val()
    };
    ax.setAccion("exportarPosicionCaja");
    ax.addParamTmp("criterios", criteriosBusquedaACCaja);    
    ax.consumir();
  }

  function exportarPosicionCajaPdf(){
  
    actualizandoBusqueda = true;
    loaderShow();
    criteriosBusquedaACCaja = {
        empresaId: commonVars.empresa,
        cajaId: select2.obtenerValor("cboCaja"),
        periodoId: select2.obtenerValor("cboPeriodo"),
        usuarioId: select2.obtenerValor("cboUsuario"),
        agenciaId: select2.obtenerValor("cboAgencia"),
        fechaInicio: $('#fechaInicio').val(),
        fechaFin: $('#fechaFin').val()
    };


    ax.setAccion("exportarPosicionCajaPdf");
    ax.addParamTmp("criteriosBusqueda", criteriosBusquedaACCaja);
    ax.consumir();
  }


      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart(data) {
         
     var visa= data.posTotal;
     var efectivo=data.efectivoTotal;
     var transferencia=data.transferenciaTotal;
     var deposito=data.depositoTotal;
     var ingresos=data.ingresos;
        var data = google.visualization.arrayToDataTable([
          ['Efectivo', 'Hours per Day'],
          ['Ingresos-S/ '+formatearNumero(ingresos),     ingresos],
          ['POS-S/ '+formatearNumero(visa),     visa],
          ['Transferencia-S/ '+formatearNumero(transferencia),      transferencia],
           ['Deposito-S/ '+formatearNumero(deposito),  deposito],
              ['Efectivo-S/ '+formatearNumero(efectivo), efectivo] 
        ]);

        var options = {
          title: ''
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      } 
   