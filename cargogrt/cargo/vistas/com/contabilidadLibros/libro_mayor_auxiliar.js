var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var total;
var total_cantidad;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseLibroMayorAuxiliar");
    obtenerConfiguracionesInicialesLibroMayorAuxiliar();
});

function onResponseLibroMayorAuxiliar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicialLibroMayorAuxiliar':
                onResponseObtenerConfiguracionesInicialesLibroMayorAuxiliar(response.data);
                break;
            case 'listarLibroMayorXCriterios':
                onResponseListarLibroMayorAuxiliar(response.data);
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                onResponseObtenerDocumentoRelacionVisualizar(response.data);
                loaderClose();
                break;
            case 'exportarLibroMayorAuxiliar':
                loaderClose();
                if (response.tag == 'excel') {
                    location.href = URL_BASE + "util/formatos/libroMayorAuxiliar.xlsx";
                } else if (response.tag == 'txt') {
//                    var urlIframe = ' <iframe src="' + URL_BASE + "util/uploads/" + response.data + '" width="100%" height="100%"></iframe>';
//                    $('#idIframe').html(urlIframe);
//                    $('#modalMostrarArchivoTxt').modal('show');

                    var link = document.createElement("a");
                    link.download = response.data;
                    link.href = URL_BASE + "util/uploads/" + response.data;
                    link.click();

                }
                break;
            case 'obtenerReporteComprasProducto':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;

            case 'obtenerDocumento':
                onResponseObtenerDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentosRelacionados':
                onResponseObtenerDocumentosRelacionados(response.data);
                loaderClose();
                break;


        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteComprasExcel':
                loaderClose();
                break;
            case 'obtenerReporteComprasProducto':
                loaderClose();
                break;

        }
    }
}

function obtenerConfiguracionesInicialesLibroMayorAuxiliar()
{
    ax.setAccion("obtenerConfiguracionInicialLibroMayorAuxiliar");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}
var dataLibro = null;
var dataPeriodo = [];
function onResponseObtenerConfiguracionesInicialesLibroMayorAuxiliar(data) {
    dataCuentasContables = data.dataCuentasContables;
    select2.cargar("cboPeriodoInicio", data.dataPeriodo, "id", ["anio", "mes"]);
    select2.cargar("cboPeriodoFin", data.dataPeriodo, "id", ["anio", "mes"]);
    onResponseObtenerDataCbo('Persona', 'id', ['codigo_identificacion', ' | ', 'nombre'], data.dataPersonaActiva);
//    onResponseObtenerDataCbo('PeriodoInicio', 'id', ['anio', ' - ', 'mes'], data.dataPeriodo, data.dataPeriodoActual[0]['id']);
//    onResponseObtenerDataCbo('PeriodoFin', 'id', ['anio', ' - ', 'mes'], data.dataPeriodo, data.dataPeriodoActual[0]['id']);

    select2.asignarValor("cboPeriodoInicio", data.dataPeriodoActual[0]['id']);
    select2.asignarValor("cboPeriodoFin", data.dataPeriodoActual[0]['id']);

    $.each(dataCuentasContables, function (index, item) {
        var html = llenarCuentasContable(item, '', 'cboCuentaContable');
        $('#cboCuentaContable').append(html);
    });

    $("#cboCuentaContable").select2({
        width: "100%"
    });

    select2.asignarValor("cboCuentaContable", "");
    dataPeriodo = data.dataPeriodo;
    onResponseListarLibroMayorAuxiliar(data.dataLibroMayor);
}
function onResponseObtenerDataCbo(cboId, itemId, itemDes, data, valor) {
    document.getElementById('cbo' + cboId).innerHTML = "";

    select2.asignarValor('cbo' + cboId, "");
    $('#cbo' + cboId).append('<option value="">Seleccione</option>');
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            $('#cbo' + cboId).append('<option value="' + item[itemId] + '">' + (isArray(itemDes) ? item[itemDes[0]] + itemDes[1] + item[itemDes[2]] : item[itemDes]) + '</option>');
        });
        if (!isEmpty(valor)) {
            select2.asignarValor('cbo' + cboId, valor);
        } else {
            select2.asignarValor('cbo' + cboId, "");
        }
    }
}

function llenarCuentasContable(item, extra, cbo_id) {
    var cuerpo = '';
    if ($("#" + cbo_id + " option[value='" + item['id'] + "']").length != 0) {
        return cuerpo;
    }
    if (item.hijos * 1 == 0) {
        cuerpo = '<option value="' + item['id'] + '">' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
        return cuerpo;
    }
    cuerpo = '<option value="' + item['id'] + '" >' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
    var dataHijos = dataCuentasContables.filter(cuentaContable => cuentaContable.plan_contable_padre_id == item.id);
    $.each(dataHijos, function (indexHijo, cuentaContableHijo) {
        cuerpo += llenarCuentasContable(cuentaContableHijo, extra + '&nbsp;&nbsp;&nbsp;&nbsp;', cbo_id);
    });
    return cuerpo;
}

var valoresBusquedaLibroDiario = [{persona: null}];//bandera 0 es balance

function cargarDatosBusquedaLibroMayor()
{
    var personaId = $('#cboPersona').val();
    var periodoInicio = $('#cboPeriodoInicio').val();
    var periodoFin = $('#cboPeriodoFin').val();

    var planContable = dataCuentasContables.find(elemento => elemento.id == $('#cboCuentaContable').val());
    var planContableCodigo = (planContable !== undefined ? planContable.codigo : null);
    if (!isEmpty(periodoInicio) && !isEmpty(periodoFin)) {
        let dataPeriodoInicial = filerArrayOfElement(dataPeriodo, "id", periodoInicio);
        let dataPeriodoFinal = filerArrayOfElement(dataPeriodo, "id", periodoFin);
        if (dataPeriodoInicial[0]['anio'] != dataPeriodoFinal[0]['anio']) {
            mostrarAdvertencia("Los periodos a consultar deben pertencer al mismo ejercicio.");
            return false;
        }
        if (dataPeriodoInicial[0]['mes']*1 > dataPeriodoFinal[0]['mes']*1) {
            mostrarAdvertencia("El periodo inicial no puede ser mayor al final.");
            return false;
        }
    }

    valoresBusquedaLibroDiario[0].persona = personaId;
    valoresBusquedaLibroDiario[0].empresa = commonVars.empresa;
    valoresBusquedaLibroDiario[0].periodoInicio = periodoInicio;
    valoresBusquedaLibroDiario[0].periodoFin = periodoFin;
    valoresBusquedaLibroDiario[0].planContableCodigo = planContableCodigo;
    return valoresBusquedaLibroDiario;

}
function buscarLibroMayor(colapsa)
{
    
    var cadena;
    cadena = obtenerDatosBusqueda();
    if(cadena === false){
        return;
    }
    loaderShow();
    obtenerListarLibroMayor();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;

    if (colapsa === 1)
        colapsarBuscador();

}

function obtenerDatosBusqueda()
{
    var cadena = "";
    let valores =  cargarDatosBusquedaLibroMayor();
    
    if(valores === false){
        return valores;
    }
    
    if (!isEmpty(select2.obtenerValor('cboPersona'))) {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }

    if (!isEmpty(select2.obtenerValor('cboPeriodoInicio'))) {
        cadena += StringNegrita("Periodo Inicio: ");

        cadena += select2.obtenerText('cboPeriodoInicio');
        cadena += "<br>";
    }

    if (!isEmpty(select2.obtenerValor('cboPeriodoFin'))) {
        cadena += StringNegrita("Periodo Fin: ");

        cadena += select2.obtenerText('cboPeriodoFin');
        cadena += "<br>";
    }

    if (!isEmpty(select2.obtenerValor('cboCuentaContable'))) {
        cadena += StringNegrita("Cuenta Contable: ");

        cadena += select2.obtenerText('cboCuentaContable');
        cadena += "<br>";
    }


    if (!isEmpty(valoresBusquedaLibroDiario[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaLibroDiario[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaLibroDiario[0].fechaEmisionDesde + " - " + valoresBusquedaLibroDiario[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}

function onResponseListarLibroMayorAuxiliar(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cabeza = '';

    var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"

            + "<thead>"
            + "<tr>"
            + "<th style='display: none;'>Número</th>"
            + "<th style='display: none;'>Registro</th>"
            + "<th style='display: none;'>Documento</th>"
            + "<th style='display: none;'>Fecha</th>"
            + "<th style='display: none;'>Libro</th>"
            + "<th style='display: none;'>Dólares</th>"
            + "<th style='display: none;'>Debe</th>"
            + "<th style='display: none;'>Haber</th>"
            + "<th style='display: none;'>Concepto</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody>";

    if (!isEmpty(dataCuentasContables) && !isEmpty(data)) {
//        debugger;
        $.each(dataCuentasContables, function (indexCuenta, cuentaContable) {
            var html = '';
            var totalDolaresCuenta = 0;
            var totalDebeCuenta = 0;
            var totalHaberCuenta = 0;
            var arrayFiltrado = data.filter(elemento => elemento['plan_contable_id'] == cuentaContable.id);
            if (!isEmpty(arrayFiltrado) && arrayFiltrado.length > 0) {
                html += '<tr>';
                html += '<td colspan="9" align="left"><b>' + cuentaContable.codigo + ' | ' + cuentaContable.descripcion + '</b></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '</tr>';


                html += '<tr>';
                html += "<td style='text-align:center;'><b>Número</b></td>";
                html += "<td style='text-align:center;'><b>Registro</b></td>";
                html += "<td style='text-align:center;'><b>Documento</b></td>";
                html += "<td style='text-align:center;'><b>Fecha</b></td>";
                html += "<td style='text-align:center;'><b>Libro</b></td>";
                html += "<td style='text-align:center;'><b>Dólares</b></td>";
                html += "<td style='text-align:center;'><b>Debe</b></td>";
                html += "<td style='text-align:center;'><b>Haber</b></td>";
                html += "<td style='text-align:center;'><b>Concepto</b></td>";
                html += '</tr>';



                var arrayPersona = [];
                $.each(arrayFiltrado, function (index, item) {
                    if (item.plan_contable_id == cuentaContable.id) {
                        arrayPersona.push(item.persona_id);
                    }
                });
                arrayPersona = arrayPersona.filter(function (valor, indice, elemento) {
                    return elemento.indexOf(valor) === indice;
                });

                if (!isEmpty(arrayPersona) && arrayPersona.length > 0) {
                    $.each(arrayPersona, function (indexPersona, itemPersona) {
                        var datosPersona = '';
                        if (!isEmpty(itemPersona)) {
                            var persona = arrayFiltrado.filter(elemento => elemento.persona_id === itemPersona);
                            datosPersona = (!isEmpty(persona) ? persona[persona.length - 1].persona_codigo_identificacion + ' | ' + persona[persona.length - 1].persona_nombre_completo : '');

                            html += '<tr>';
                            html += '<td colspan="9" align="left"><b>' + datosPersona + '</b></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '<td style="display: none;"></td>';
                            html += '</tr>';
                        }
                        var arrayDetalle = arrayFiltrado.filter(elemento => elemento['persona_id'] == itemPersona);
                        var totalDolaresPersona = 0;
                        var totalDebePersona = 0;
                        var totalHaberPersona = 0;
                        $.each(arrayDetalle, function (indexDetalle, itemDetalle) {

                            let correlativoCuo = "";
                            if (!isEmpty(itemDetalle.cuo)) {
                                correlativoCuo = itemDetalle.cuo.split(itemDetalle.libro_codigo + '-')[1];
                            }
                            html += '<tr>';
                            html += '<td align="center">' + correlativoCuo + '</td>';
                            html += '<td align="left">' + itemDetalle.documento_cuo + '</td>';
                            html += '<td align="left">' + itemDetalle.documento_referencia + '</td>';
                            html += '<td align="center">' + formatearFechaJS(itemDetalle.fecha_contabilizacion) + '</td>';
                            html += '<td align="center">' + itemDetalle.libro_codigo + '</td>';
                            html += '<td align="right">' + formatearNumeroPorCantidadDecimales(itemDetalle.monto_dolares) + '</td>';
                            html += '<td align="right">' + formatearNumeroPorCantidadDecimales(itemDetalle.debe_soles) + '</td>';
                            html += '<td align="right">' + formatearNumeroPorCantidadDecimales(itemDetalle.haber_soles) + '</td>';
                            html += '<td align="left">' + itemDetalle.glosa + '</td>';
                            html += '</tr>';

                            totalDolaresPersona += itemDetalle.monto_dolares;
                            totalDebePersona += itemDetalle.debe_soles * 1;
                            totalHaberPersona += itemDetalle.haber_soles * 1;
                        });

                        html += '<tr>';
                        html += '<td colspan="3" align="left"><b>Saldo :</b></td>';
                        html += '<td style="display: none;"></td>';
                        html += '<td style="display: none;"></td>';
                        html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDebePersona - totalHaberPersona) + '</b></td>';
                        html += '<td></td>';
                        html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDolaresPersona) + '</b></td>';
                        html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDebePersona) + '</b></td>';
                        html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalHaberPersona) + '</b></td>';
                        html += '<td></td>';
                        html += '</tr>';

                        totalDolaresCuenta += totalDolaresPersona;
                        totalDebeCuenta += totalDebePersona;
                        totalHaberCuenta += totalHaberPersona;
                    });
                }
                html += '<tr>';
                html += '<td colspan="3" align="left"><b>Saldo cuenta :</b></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDebeCuenta - totalHaberCuenta) + '</b></td>';
                html += '<td></td>';
                html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDolaresCuenta) + '</b></td>';
                html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalDebeCuenta) + '</b></td>';
                html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(totalHaberCuenta) + '</b></td>';
                html += '<td></td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td colspan="9" align="left"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '<td style="display: none;"></td>';
                html += '</tr>';

            }
            cuerpo_total += html;
        });
    }

    var pie = "</tbody></table>";
    var tabla = cabeza + cuerpo_total + pie;
    $("#dataList").append(tabla);

    $('#datatable').dataTable({
        "lengthMenu": [[-1], ["Todo"]],
        "order": [],
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



var color;

function getDataTableReporteCompras() {
    color = '';
    ax.setAccion("obtenerDataReporteCompras");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    $('#dataTableReporteCompras').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Origen Total acciones
            {"data": "fecha_creacion"},
            {"data": "fecha_emision"},
            {"data": "usuario_nombre"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "origen"},
            {"data": "total", "class": "alignRight"},
            {"data": "acciones", "class": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 8
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
                "targets": 1
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(8).footer()).html(
                    'S/. ' + (formatearNumero(total))
                    );
        }
    });
    loaderClose();
}

var actualizandoBusqueda = false;
function loaderBuscarVentas()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarLibroDiario();
    }
}

function cerrarPopover()
{
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        } else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    } else
    {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
}

function obtenerListarLibroMayor() {
    ax.setAccion("listarLibroMayorXCriterios");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    ax.consumir();
}

function exportarRegistroCompras(tipo) {
    loaderShow();
    cargarDatosBusquedaLibroMayor();
    ax.setAccion("exportarRegistroCompras");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    ax.addParamTmp("tipo", tipo);
    ax.setTag(tipo);
    ax.consumir();
}

//function imprimir(muestra)
//{
//    var ficha = document.getElementById(muestra);
//    var ventimp = window.open(' ', 'popimpr');
//    ventimp.document.write(ficha.innerHTML);
//    ventimp.document.close();
//    ventimp.print();
//    ventimp.close();
//}

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

function exportarLibroMayorAuxiliar(tipo)
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaLibroMayor();
    ax.setAccion("exportarLibroMayorAuxiliar");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    ax.addParamTmp("tipo", tipo);
    ax.setTag(tipo);
    ax.consumir();
}

function obtenerDocumento(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumento");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}

function onResponseObtenerDocumento(data) {
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);

    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna, data.organizador);
    } else {
        $('#formularioCopiaDetalle').hide();
    }

    $('#modalDetalleDocumento').modal('show');
}
function cargarDataDocumento(data) {
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);

    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor))
            {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
                    case 3:
                        valor = fechaArmada(valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
                    case 14:
                    case 15:
                    case 16:
                    case 19:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}
function fechaArmada(valor) {
    var fecha = separarFecha(valor);
    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}
function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
    $('#txtDescripcion').val(data[0]['descripcion_documemto']);
}
var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna, dataOrganizador) {
    movimientoTipoColumna = dataMovimientoTipoColumna;
    if (!isEmptyData(data)) {
        $('#formularioCopiaDetalle').show();

        $.each(data, function (index, item) {
            data[index]["importe"] = formatearNumero(data[index]["cantidad"] * data[index]["valor_monetario"]);
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["valor_monetario"] = formatearNumero(data[index]["valor_monetario"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
//        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataOrganizador)) {
            html += "<th style='text-align:center;'>Organizador</th>";
        }
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Unidad de medida</th>";
        html += "<th style='text-align:center;'>Producto</th> ";
        if (existeColumnaCodigo(5)) {
            html += "<th style='text-align:center;'>Precio Unitario</th>";
            html += "<th style='text-align:center;'>Total</th>";
        }
        html += "</tr>";
        tHeadDetalle.append(html);


        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalle');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            html += "<tr>";
//            if(existeColumnaCodigo(15)){
            if (!isEmpty(dataOrganizador)) {
                html += "<td>" + item.organizador_descripcion + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidad_medida_descripcion + "</td>";
            html += "<td>" + item.bien_descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.valor_monetario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function obtenerDocumentosRelacionados(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentosRelacionados");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}
function obtenerDocumentoRelacion(documentoId) {
    $('#modalDocumentoRelacionado').modal('hide');
    obtenerDocumento(documentoId);
}
function onResponseObtenerDocumentosRelacionados(data) {
    $('#linkDocumentoRelacionado').empty();

    if (!isEmptyData(data)) {
        $('[data-toggle="popover"]').popover('hide');
        $.each(data, function (index, item) {
            $('#linkDocumentoRelacionado').append("<a onclick='obtenerDocumentoRelacion(" + item.documento_relacionado_id + ")' style='color:#0000FF'>[" + item.documento_tipo + ": " + item.serie_numero + "]</a><br>");
        });
        $('#modalDocumentoRelacionado').modal('show');
    } else {
        mostrarAdvertencia("No se encontró ningún documento relacionado con el actual.");
    }
}

var docId;
function visualizarDocumento(documentoId, movimientoId)
{
    docId = documentoId;
    loaderShow();
    ax.setAccion("obtenerDocumentoRelacionVisualizar");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("movimientoId", movimientoId);
    ax.consumir();

//    
}

function onResponseObtenerDocumentoRelacionVisualizar(data)
{

    dataVisualizarDocumento = data;
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento, data.configuracionEditable, data.dataDocumentoAdjunto);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    $('#tabDistribucion').show();
    $('#tabsDistribucionMostrar').show();

    if (!$("#div_contenido_tab").hasClass("tab-content")) {
        $("#div_contenido_tab").addClass("tab-content");
    }


    if (!isEmpty(data.detalleDocumento) && !isEmpty(data.dataDistribucionContable)) {
        $('#tabsDistribucionMostrar').show();
        $('a[href="#detalle"]').click();
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna);
        cargarDistribucionDocumento(data.dataDistribucionContable);
    } else if (isEmpty(data.detalleDocumento) && isEmpty(data.dataDistribucionContable)) {
        $('#tabDistribucion').hide();
    } else {
        if (!isEmpty(data.detalleDocumento)) {
            $('a[href="#detalle"]').click();
            cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna);
        } else {
            $('#datatable2').hide();
        }
        if (!isEmpty(data.dataDistribucionContable)) {
            $('a[href="#distribucion"]').click();
            cargarDistribucionDocumento(data.dataDistribucionContable);
        } else {
            $('#datatableDistribucion2').hide();
        }
        $('#tabsDistribucionMostrar').hide();
        $("#div_contenido_tab").removeClass("tab-content");

    }

//    dibujarTipoEnvioEmail(data.dataAccionEnvio);
    $('#modalDetalleDocumento').modal('show');
}


function cargarDataDocumento(data, configuracionEditable, dataDocumentoAdjunto)
{
    textoDireccionId = 0;
    personaDireccionId = 0;
    camposDinamicos = [];

    guardarEdicionDocumento = false;
    if (!isEmpty(configuracionEditable)) {
        guardarEdicionDocumento = true;
    }

//    if(!isEmpty(configuracionEditable)){
//        $("#botonEdicion").show();
//    }

    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);
    var contador = 0;

    if (!isEmpty(data)) {
//        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        $('#tituloVisualizacionModal').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            if (item.tipo != 31) {

                if (contador % 3 == 0) {
                    appendFormDetalle('<div class="row">');
                    appendFormDetalle('</div>');
                }
                contador++;


//            appendFormDetalle('<div class="row">');

                var html = '<div class="form-group col-md-4"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                        '<label>' + item.descripcion + '</label>' +
                        '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

                var valor = '';
                if (item.edicion_habilitar == 0) {
                    valor = quitarNULL(item.valor);

                    if (!isEmpty(valor))
                    {
                        switch (parseInt(item.tipo)) {
                            case 1:
                                valor = formatearCantidad(valor);
                                break;
//                    case 2:
                            case 3:
                                valor = fechaArmada(valor);
                                break;
//                    case 4:
//                    case 5:
//                    case 6:
//                    case 7:
//                    case 8:
                            case 9:
                            case 10:
                            case 11:
                                valor = fechaArmada(valor);
                                break;
//                    case 12:
//                    case 13:
                            case 14:
                            case 15:
                            case 16:
                            case 19:
                            case 32:
                            case 33:
                            case 34:
                            case 35:
                                valor = formatearNumero(valor);
                                break;
                            case 27:
                                if (!isEmpty(dataDocumentoAdjunto)) {
                                    valor = '<a style="color: blue;" href="util/uploads/documentoAdjunto/' + dataDocumentoAdjunto[0]['nombre'] + '" download="' + dataDocumentoAdjunto[0]['archivo'] + '">' + dataDocumentoAdjunto[0]['archivo'] + '</a>';
                                }
                                break;
                        }
                    }
                } else {
                    $.each(configuracionEditable, function (index, itemEditable) {
                        if (itemEditable.documento_tipo_dato_id == item.documento_tipo_id) {

                            camposDinamicos.push({
                                id: item.documento_tipo_id,
                                tipo: parseInt(itemEditable.tipo),
                                opcional: itemEditable.opcional,
                                descripcion: itemEditable.descripcion
                            });

                            var longitudMaxima = itemEditable.longitud;
                            if (isEmpty(longitudMaxima)) {
                                longitudMaxima = 45;
                            }

                            switch (parseInt(item.tipo)) {
                                case 1:
                                case 14:
                                case 15:
                                case 16:
                                case 19:
                                    valor += '<input type="number" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45" style="text-align: right;" />';
                                    break;

                                case 7:
                                case 8:
                                    valor += '<input type="text" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45" style="text-align: right;"/>';
                                    break;

                                case 2:
                                case 6:
                                case 12:
                                case 13:

                                    if (parseInt(itemEditable.numero_defecto) === 1) {
                                        textoDireccionId = itemEditable.documento_tipo_dato_id;
                                    }
                                    valor += '<input type="text" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="' + longitudMaxima + '"/>';
                                    break;
                                case 9:
                                case 3:
                                case 10:
                                case 11:
                                    valor += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                            '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.documento_tipo_id + '">' +
                                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>';
                                    break;
                                case 4:
                                    valor += '<select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2"></select>';
                                    break;
                                case 5:
                                    valor += '<div id ="div_persona" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                                    $.each(itemEditable.data, function (indexPersona, itemPersona) {
                                        valor += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 17:
                                    valor += '<div id ="div_organizador_destino" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione organizador</option>';
                                    $.each(itemEditable.data, function (indexOrganizador, itemOrganizador) {
                                        valor += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 18:
                                    personaDireccionId = item.documento_tipo_id;
                                    valor += '<div id ="div_direccion" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '</select>';
                                    break;
                                case 20:
                                    valor += '<div id ="div_cuenta" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                                    $.each(itemEditable.data, function (indexCuenta, itemCuenta) {
                                        valor += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 21:
                                    valor += '<div id ="div_actividad" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione la actividad</option>';
                                    $.each(itemEditable.data, function (indexActividad, itemActividad) {
                                        valor += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 22:
                                    valor += '<div id ="div_retencion_detraccion" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione retención o detracción</option>';
                                    $.each(itemEditable.data, function (indexRetencionDetraccion, itemRetencionDetraccion) {
                                        valor += '<option value="' + itemRetencionDetraccion.id + '">' + itemRetencionDetraccion.descripcion + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 23:
                                    valor += '<div id ="div_persona_' + item.documento_tipo_id + '" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                    valor += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                                    $.each(itemEditable.data, function (indexPersona, itemPersona) {
                                        valor += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                                    });
                                    valor += '</select>';
                                    break;
                                case 32:
                                case 33:
                                case 34:
                                case 35:
                                    valor = formatearNumero(item.valor);
                                    break;
                                case 36:
                                    valor = (item.valor);
                                    break;
                            }
                        }
                    });
                }

                html += '' + valor + '';
                html += '</div></div>';
                appendFormDetalle(html);

                if (item.edicion_habilitar == 1) {
                    $.each(configuracionEditable, function (index, itemEditable) {
                        if (itemEditable.documento_tipo_dato_id == item.documento_tipo_id) {
                            switch (parseInt(item.tipo)) {
                                case 3:
                                case 9:
                                case 10:
                                case 11:
                                    $('#datepicker_' + item.documento_tipo_id).datepicker({
                                        isRTL: false,
                                        format: 'dd/mm/yyyy',
                                        autoclose: true,
                                        language: 'es'
                                    });

                                    if (isEmpty(itemEditable.valor_id)) {
                                        $('#datepicker_' + item.documento_tipo_id).datepicker('setDate', itemEditable.data);
                                    } else {
                                        $('#datepicker_' + item.documento_tipo_id).datepicker('setDate', formatearFechaJS(itemEditable.valor_id));
                                    }


                                    break;
                                case 4:
                                    select2.cargar("cbo_" + item.documento_tipo_id, itemEditable.data, "id", "descripcion");
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });
                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 5:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    }).on("change", function (e) {
                                        obtenerPersonaDireccion(e.val);
//                                    obtenerBienesConStockMenorACantidadMinima(e.val);
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 17:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 18:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 20:
                                case 21:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 22:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                case 23:
                                    $("#cbo_" + item.documento_tipo_id).select2({
                                        width: '100%'
                                    });

                                    select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                    break;
                                    //input numero    
                                case 1:
                                case 14:
                                case 15:
                                case 16:
                                case 19:
                                    $('#txt_' + item.documento_tipo_id).val(formatearNumero(itemEditable.valor_id));
                                    break;

                                    //input texto
                                case 7:
                                case 8:
                                case 2:
                                case 6:
                                case 12:
                                case 13:
                                    $('#txt_' + item.documento_tipo_id).val(itemEditable.valor_id);
                                    break;
                            }
                        }
                    });

                }
            }
        });
        appendFormDetalle('</div>');
    }
}


function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDistribucionDocumento(data) {
    $('#datatableDistribucion2').show();

    if (!isEmptyData(data))
    {
        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalleCabeceraDistribucion');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
        html += "<th style='text-align:center;'>#</th>";
        html += "<th style='text-align:center;'>Cuenta Contable</th>";
        if (!isEmpty(data[0]['centro_costo_id'])) {
            html += "<th style='text-align:center;'>Centro Costo</th>";
        }
        html += "<th style='text-align:center;'>Porcentaje(%)</th>";
        html += "<th style='text-align:center;'>Monto</th> ";

        html += "</tr>";
        tHeadDetalle.append(html);


        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalleDistribucion');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            html += "<tr>";
            html += "<td style='text-align:center;'>" + item.linea + "</td>";
            html += "<td style='text-align:center;'>" + item.descripcion_cuenta + "</td>";
            if (!isEmpty(item.centro_costo_descripcion)) {
                html += "<td style='text-align:center;'>" + item.centro_costo_descripcion + "</td>";
            }
            html += "<td style='text-align:right;'>" + formatearNumero(item.porcentaje) + "</td> ";
            html += "<td style='text-align:right;'>" + formatearNumero(item.monto) + "</td> ";
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else
    {
        var table = $('#datatableDistribucion2').DataTable();
        table.clear().draw();
    }
}
