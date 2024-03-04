var tabActivo = 1;
//1 -> tab de documentos
//2 -> tab de detalle de programacion de pago

$(document).ready(function () {
    criterioBusquedaDocumentos = [];
    cargarComponetentes();
    ax.setSuccess("onResponseProgramacionPagoListar");
    obtenerConfiguracionInicialListado();
    cambiarAnchoBusquedaDesplegable();
    $('#cboEstadoPPago').select2({
        minimumResultsForSearch: -1
    });
    $('#cboMoneda2').select2({
        minimumResultsForSearch: -1
    });
});

function onResponseProgramacionPagoListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicialListado':
                onResponseObtenerConfiguracionInicial(response.data);
                buscarDocumentos();
                loaderClose();
                break;
            case 'obtenerDocumento':
                onResponseObtenerDocumento(response.data);
                loaderClose();
                break;
            case 'actualizarEstadoPPagoDetalle':
                loaderClose();
                var exito = response.data['0'].vout_exito;
                if (exito == 1) {
                    mostrarOk(response.data['0'].vout_mensaje);
                    buscarProgramacionPagoDetalle();
                }
                break;
        }
    }
}

function cargarComponetentes() {
    cargarTitulo("titulo", "");
    select2.iniciar();
    datePiker.iniciarPorClase('fecha');
}

function obtenerConfiguracionInicialListado() {
    ax.setAccion("obtenerConfiguracionInicialListado");
    ax.consumir();
}

function obtenerFechaActualBD() {
    var hoy = new Date();
    var dia = hoy.getDate();
    dia = (dia < 10) ? ('0' + dia) : dia;
    var mes = hoy.getMonth() + 1;
    mes = (mes < 10) ? ('0' + mes) : mes;
    var anio = hoy.getFullYear();

    return anio + "-" + mes + "-" + dia;
}

function buscarDocumentos() {
    loaderShow();
    ax.setAccion("obtenerDocumentosPPago");
    ax.addParamTmp("criterios", criterioBusquedaDocumentos);

    $('#datatable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
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
        },
        "order": [[7, "desc"]],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": [
//Proveedor	S/N	M	Total	Fecha	F.Creación	F.Tentativa	Usuario	Estado	Acc.
            {"data": "documento_tipo_id", "class": "alignCenter"},
            {"data": "serie"},
         //   {"data": "serie_numero"},
            {"data": "persona_nombre_completo"},
            {"data": "total", "class": "alignRight"},
            {"data": "estado_programacion"},
            {"data": "fecha_emision", "class": "alignCenter"},
            {"data": "fecha_bl", "class": "alignCenter"},
            {"data": "fecha_creacion", "class": "alignCenter"},
            {"data": "usuario", "class": "alignCenter"}

        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    if (parseFloat(data).formatMoney(2, '.', ',') == '0.00') {
                        return '-';
                    } else {
                        return row.moneda_simbolo + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    var fecha = row.fecha_creacion_formato;
                    var muestraFecha = '';

                    if (obtenerFechaActualBD() == data.substring(0, 10)) {
                        muestraFecha = fecha.substring(12, fecha.length);
                    } else {
                        muestraFecha = fecha.substring(0, 10);
                    }
                    return muestraFecha;
                },
                "targets": 7
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    return fecha;
                },
                "targets": [5, 6]
            },
            {
                "render": function (data, type, row) {
                    if (!isEmpty(data)) {
                        if (data.length > 28) {
                            data = data.substring(0, 25) + '...';
                        }
                    }
                    return data;
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    return  "<a href='#' onclick='obtenerDocumento(" + row.documento_id + ")'><span class='" + row.documento_tipo_leyenda_clase + "' title='Visualizar documento'>" + row.documento_tipo_leyenda_siglas + "</span></a>&nbsp;" + ( row.documento_tipo_id == 239 ? row.codigo : row.serie_numero);
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    return  "<a href='#' onclick='nuevoProgramacionPago(" + row.documento_id + ",\"" + row.ppago_id + "\")'><i class='fa fa-calendar-o' style='color:green;' title='Registrar programación de pago'></i></a>&nbsp;";
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    var html = '';
                    switch (data * 1) {
                        case 1:
                            html = "Programado";
                            break;
                        case 2:
                            html = "Por programar";
                            break;
                        case 3:
                            html = "Pagado parcialmente";
                            break;
                    }
                    return  html;
                },
                "targets": 4
            }
        ],
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            if (aData.ppago_id != '') {
                $(nRow).addClass("colorPP");
            }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();
}

function actualizarBusqueda() {
    if (tabActivo == 1) {
        buscarDocumentos();
    } else {
//        buscarProgramacionPagoDetalle();
        buscarPorCriterios2();
    }
}

$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

$('.fecha').datepicker({
    isRTL: false,
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'es'
}).on('changeDate', function (ev) {
//    console.log(ev);
    if (tabActivo == 1) {
        setTimeout(function () {
            $("#spanBuscador").addClass('open');
        }, 5);
    } else {
        setTimeout(function () {
            $("#spanBuscador2").addClass('open');
        }, 5);
    }
});

function onResponseObtenerConfiguracionInicial(data) {
    if (!isEmpty(data.documento_tipo)) {
        dibujarTiposDocumentos(data.documento_tipo);
        dibujarPersonasMayorDocumentos(data.personasMayorDocumentos);

        //desplegable de documentos
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.cargar("cboPersona", data.persona_activa, "id", ["nombre", "codigo_identificacion"]);
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);

        //desplegable de ppago detalle
        select2.cargar("cboDocumentoTipo2", data.documento_tipo, "id", "descripcion");
        select2.cargar("cboPersona2", data.persona_activa, "id", ["nombre", "codigo_identificacion"]);
        select2.cargar("cboMoneda2", data.moneda, "id", ["descripcion", "simbolo"]);
        
        
        $('#finFechaProgramada2').val(datex.getNow1());
    }
}

//here
function buscarPorCriterios() {
    var personaId = select2.obtenerValor('cboPersona');
    var documentoTipoIds = $('#cboDocumentoTipo').val();
    var serie = $('#txtSerie').val();
    var numero = $('#txtNumero').val();
    var fechaEmision = {inicio: $('#inicioFechaEmision').val(),
        fin: $('#finFechaEmision').val()};
    var fechaBL = {inicio: $('#inicioFechaBL').val(),
        fin: $('#finFechaBL').val()};
    var monedaId = select2.obtenerValor('cboMoneda');
    var estadoProgramacion = select2.obtenerValor('cboEstadoPromacion');
    llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaBL, estadoProgramacion);

    buscarDocumentos();
}

function buscarPorCriterios2() {
    var personaId = select2.obtenerValor('cboPersona2');
    var documentoTipoIds = $('#cboDocumentoTipo2').val();
    var serie = $('#txtSerie2').val();
    var numero = $('#txtNumero2').val();
    var fechaEmision = {inicio: $('#inicioFechaEmision2').val(),
        fin: $('#finFechaEmision2').val()};
    var fechaBL = {inicio: $('#inicioFechaBL2').val(),
        fin: $('#finFechaBL2').val()};
    var fechaProgramada = {inicio: $('#inicioFechaProgramada2').val(),
        fin: $('#finFechaProgramada2').val()};
    var monedaId = select2.obtenerValor('cboMoneda2');
    var estadoPPago = select2.obtenerValor('cboEstadoPPago');
    llenarParametrosBusqueda2(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaBL, fechaProgramada, estadoPPago);

    buscarProgramacionPagoDetalle();
}

var criterioBusquedaDocumentos = {};

function llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaBL, estadoProgramacion) {
    criterioBusquedaDocumentos = {};

    criterioBusquedaDocumentos.personaId = personaId;
    criterioBusquedaDocumentos.documentoTipoIds = documentoTipoIds;
    criterioBusquedaDocumentos.serie = serie;
    criterioBusquedaDocumentos.numero = numero;
    criterioBusquedaDocumentos.fechaEmision = fechaEmision;
    criterioBusquedaDocumentos.monedaId = monedaId;
    criterioBusquedaDocumentos.fechaBL = fechaBL;
    criterioBusquedaDocumentos.estadoProgramacion = estadoProgramacion;
}

var criterioBusquedaPPagoDetalle = {};

function llenarParametrosBusqueda2(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaBL, fechaProgramada, estadoPPago) {
    criterioBusquedaPPagoDetalle = {};

    criterioBusquedaPPagoDetalle.personaId = personaId;
    criterioBusquedaPPagoDetalle.documentoTipoIds = documentoTipoIds;
    criterioBusquedaPPagoDetalle.serie = serie;
    criterioBusquedaPPagoDetalle.numero = numero;
    criterioBusquedaPPagoDetalle.fechaEmision = fechaEmision;
    criterioBusquedaPPagoDetalle.monedaId = monedaId;
    criterioBusquedaPPagoDetalle.fechaBL = fechaBL;
    criterioBusquedaPPagoDetalle.fechaProgramada = fechaProgramada;
    criterioBusquedaPPagoDetalle.estadoPPago = estadoPPago;
}

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function dibujarTiposDocumentos(documentoTipo) {
    var html = '';
    html += '<a href="#" onclick="buscarPorDocumentoTipo(' + null + ')" class="list-group-item">';
    html += '<span class="fa fa-circle text-pink pull-right" style="color: #D8D8D8;"></span>Todos';
    html += '</a>';
    var divDocumentoTipos = $('#divDocumentoTipos');
    divDocumentoTipos.empty();
    $.each(documentoTipo, function (index, item) {
        html += '<a href="#" onclick="buscarPorDocumentoTipo(' + item.id + ')" class="list-group-item">';
        html += '<span class="' + item.leyenda_clase + '">' + item.leyenda_siglas + '</span>' + item.descripcion;
        html += '</a>';
    });

    divDocumentoTipos.append(html);
}

function dibujarPersonasMayorDocumentos(personas) {
    var html = '';
    var divPersonasMayorMovimientos = $('#divPersonasMayorMovimientos');
    divPersonasMayorMovimientos.empty();
    if (!isEmpty(personas)) {
        $.each(personas, function (index, item) {
            html += '<a href="#" class="list-group-item" onclick="buscarPorPersona(' + item.id + ')" >';
            html += '<span class="badge bg-info">' + item.veces + '</span>' + item.nombre;
            html += '</a>';
        });
    }

    divPersonasMayorMovimientos.append(html);
}

function limpiarBuscadores() {
    $('#txtSerie').val('');
    $('#txtNumero').val('');
    $('#inicioFechaEmision').val('');
    $('#finFechaEmision').val('');
    $('#inicioFechaBL').val('');
    $('#finFechaBL').val('');

    select2.asignarValor('cboDocumentoTipo', -1);
    select2.asignarValor('cboPersona', -1);
    select2.asignarValor('cboMoneda', -1);

    criterioBusquedaDocumentos = {};
}

function limpiarBuscadores2() {
    $('#txtSerie2').val('');
    $('#txtNumero2').val('');
    $('#inicioFechaEmision2').val('');
    $('#finFechaEmision2').val('');
    $('#inicioFechaBL2').val('');
    $('#finFechaBL2').val('');
    $('#inicioFechaProgramada2').val('');
    $('#finFechaProgramada2').val('');

    select2.asignarValor('cboDocumentoTipo2', -1);
    select2.asignarValor('cboPersona2', -1);
    select2.asignarValor('cboMoneda2', -1);

    criterioBusquedaPPagoDetalle = {};
}

function buscarPorDocumentoTipo(documentoTipoId) {
    var documentoTipoIds = [];
    if (!isEmpty(documentoTipoId)) {
        documentoTipoIds.push(documentoTipoId);
    }
    llenarParametrosBusqueda(null, documentoTipoIds, null, null, null, null, null);

    buscarDocumentos();
}

function buscarPorPersona(personaId) {
    llenarParametrosBusqueda(personaId, null, null, null, null, null, null);

    buscarDocumentos();
}

function nuevoProgramacionPago(documentoId, ppagoId) {
    var titulo = "Nueva";
    if (!isEmpty(ppagoId)) {
        titulo = "Editar";
    }
    var url = URL_BASE + "vistas/com/programacionPago/programacion_pago_form.php?winTitulo=" + titulo + "&docId=" + documentoId;
    cargarDiv("#window", url);
}

function actualizarTabActivo(tab) {
    tabActivo = tab;
    actualizarBusqueda();
}

function buscarProgramacionPagoDetalle() {
    loaderShow();
    ax.setAccion("obtenerProgramacionPagoDetalle");
    ax.addParamTmp("criterios", criterioBusquedaPPagoDetalle);
    $('#datatableProgramacionPagoDetalle').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
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
        },
        "order": [2, "desc"],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": [
//Acc.	F.Programada	Proveedor	S/N	M	Importe	F.Emisión	F.BL	Indicador	Estado	F.Creación	Usuario
            {"data": "documento_tipo_id"},
//            {"data": "serie_numero"},
            {"data": "serie"},
            {"data": "fecha_programada_alt", "class": "alignCenter"},
            {"data": "persona_nombre_completo"},
            {"data": "importe_programado", "class": "alignRight"},
            {"data": "ppago_detalle_estado_logico"},
            {"data": "fecha_emision", "class": "alignCenter"},
            {"data": "fecha_bl", "class": "alignCenter"},
            {"data": "indicador_descripcion"},
            {"data": "fecha_creacion", "class": "alignCenter"},
            {"data": "usuario", "class": "alignCenter"}

        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    if (parseFloat(data).formatMoney(2, '.', ',') == '0.00') {
                        return '-';
                    } else {
                        return row.moneda_simbolo + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": 4
            },
            {
                "render": function (data, type, row) {
//                    console.log(row);
                    var fecha = row.fecha_creacion_formato;
                    var muestraFecha = '';

                    if (obtenerFechaActualBD() == data.substring(0, 10)) {
                        muestraFecha = fecha.substring(12, fecha.length);
                    } else {
                        muestraFecha = fecha.substring(0, 10);
                    }
                    return muestraFecha;
                },
                "targets": 9
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    return fecha;
                },
                "targets": [6, 7]
            },
            {
                "render": function (data, type, row) {
                    if (!isEmpty(data)) {
                        if (data.length > 28) {
                            data = data.substring(0, 25) + '...';
                        }
                    }
                    return data;
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    var html = '';

                    // El estado
                    switch (row.ppago_detalle_estado_logico * 1) {
                        case 1:
                            html = html + "<a href='#' onclick='actualizarEstadoPPagoDetalle(" + row.ppago_detalle_id + ",3)'><i class='fa fa-lock' style='color:red;' title='Actualizar a liberado'></i></a>&nbsp;&nbsp;&nbsp;";
                            break;
                        case 3:
                            html = html + "<a href='#' onclick='actualizarEstadoPPagoDetalle(" + row.ppago_detalle_id + ",1)'><i class='fa fa-unlock' style='color:green;' title='Actualizar a por liberar'></i></a>&nbsp;";
                            break;
                        case 4:
                            html = html + "<i class='fa fa-money' style='color:orange;' title='Pagado parcialmente'></i>&nbsp;";
                            break;
                        case 5:
                            html = html + "<i class='fa fa-money' style='color:green;' title='Pagado'></i>&nbsp;";
                            break;
                        default:
                            html = html + "<i class='ion-close-circled' style='color:red;' title='Eliminado'></i>";
                    }


                    // La programación de pago
                    if (row.ppago_detalle_estado != 2) {
                        html = html + "<a href='#' onclick='editarProgramacionPago(" + row.documento_id + "," + row.ppago_detalle_id + ")'><i class='fa fa-calendar-o' style='color:green;' title='Editar programación de pago'></i></a>";
                    }
                    return  html;
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    if (!isEmpty(row.fecha_programada)) {
                        fecha = '<b>' + fecha + '</b>';
                    }
                    return fecha;
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    return "<a href='#' onclick='obtenerDocumento(" + row.documento_id + ")'><span class='" + row.documento_tipo_leyenda_clase + "' title='Visualizar documento'>" + row.documento_tipo_leyenda_siglas + "</span></a>&nbsp;" +  ( row.documento_tipo_id == 239 ? row.codigo : row.serie_numero);
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    var html = '';

                    // El estado Por liberar      Liberado      Pagado      Pagado parcialmente
                    switch (data * 1) {
                        case 1:
                            html = "Por liberar";
                            break;
                        case 3:
                            html = "Liberado";
                            break;
                        case 4:
                            html = "Pagado parcialmente";
                            break;
                        case 5:
                            html = "Pagado totalmente";
                            break;
                        default:
                            html = "<i class='ion-close-circled' style='color:red;' title='Eliminado'></i>";
                    }
                    return  html;
                },
                "targets": 5
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();
}

//----------------------- VISUALIZAR DOCUMENTO -------------------------------
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

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
    $('#txtDescripcion').val(data[0]['descripcion_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
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

function existeColumnaCodigo(codigo) {
    var dataColumna = movimientoTipoColumna;

    var existe = false;
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            if (parseInt(item.codigo) === parseInt(codigo)) {
                existe = true;
                return false;
            }
        });
    }

    return existe;
}
//-------------------- FIN VISUALIZAR DOCUMENTO ----------------------------

function actualizarEstadoPPagoDetalle(ppagoDetalleId, nuevoEstado) {
    swal({
        title: "¿Está seguro que desea cambiar el estado?",
        text: "Verificar si está seguro cambiar el estado al detalle de programación de pagos seleccionado",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, modificar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            loaderShow();
            ax.setAccion("actualizarEstadoPPagoDetalle");
            ax.addParamTmp("ppagoDetalleId", ppagoDetalleId);
            ax.addParamTmp("nuevoEstado", nuevoEstado);
            ax.consumir();
        }
    });
}

function editarProgramacionPago(documentoId, ppagoDetalleId) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/programacionPago/programacion_pago_form.php?winTitulo=" + titulo + "&docId=" + documentoId + "&ppagoDetalleId=" + ppagoDetalleId;
    cargarDiv("#window", url);
}