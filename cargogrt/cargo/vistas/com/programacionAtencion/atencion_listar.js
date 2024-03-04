var tabActivo = 1;
//1 -> tab de documentos
//2 -> tab de detalle de programacion de atenciones

$(document).ready(function () {
    criterioBusquedaDocumentos = [];
    cargarComponetentes();
    ax.setSuccess("onResponseProgramacionAtencionListar");
    obtenerConfiguracionInicialListado();
    cambiarAnchoBusquedaDesplegable();
    $('#cboEstadoPAtencion').select2({
        minimumResultsForSearch: -1
    });
    $('#cboMoneda2').select2({
        minimumResultsForSearch: -1
    });
    $('#cboEstadoProgramacion').select2({
        minimumResultsForSearch: -1
    });
    $('#cboMoneda').select2({
        minimumResultsForSearch: -1
    });
});

function onResponseProgramacionAtencionListar(response) {
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
            case 'actualizarEstadoPAtencionDetalle':
                loaderClose();
                var exito = response.data['0'].vout_exito;
                if (exito == 1) {
                    mostrarOk(response.data['0'].vout_mensaje);
                    buscarProgramacionAtencionDetalle();
                }
                break;
            case 'obtenerDocumentosRelacionados':
                onResponseObtenerDocumentosRelacionados(response.data);
                loaderClose();
                break;
        }    
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'actualizarEstadoPAtencionDetalle':
                loaderClose();
                swal({title: "Stock insuficiente", text: response.message, type: "error", html: true});
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
    ax.setAccion("obtenerDocumentosPAtencion");
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
        "order": [6, "desc"],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": [
//Proveedor	S/N	M	Total	Fecha	F.Creación	F.Tentativa	Usuario	Estado	Acc.
            {"data": "documento_tipo_id", "class": "alignCenter"},
            {"data": "serie_numero"},
            {"data": "persona_nombre_completo"},
            {"data": "total", "class": "alignRight"},
            {"data": "estado_programacion"},
            {"data": "fecha_emision", "class": "alignCenter"},
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
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    return fecha;
                },
                "targets": 5
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
                    return  "<a href='#' onclick='obtenerDocumento(" + row.documento_id + ")'><span class='" + row.documento_tipo_leyenda_clase + "' title='Visualizar documento'>" + row.documento_tipo_leyenda_siglas + "</span></a>&nbsp;" + data;
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    return  "<a href='#' onclick='nuevoProgramacionAtencion(" + row.documento_id + ",\"" + row.patencion_id + "\")'><i class='fa fa-calendar-o' style='color:green;' title='Registrar atención'></i></a>&nbsp;&nbsp;&nbsp;" +
                            (row.documento_relacionado>0 && !isEmpty(row.documento_relacionado)?
                            "<a href='#' onclick='obtenerDocumentosRelacionados(" + row.documento_id + ")'><i class='ion-android-share' style='color:#E8BA2F' title='Ver relación'></i></a>&nbsp;"
                            :"");
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
                            html = "Atendido parcialmente";
                            break;
                        case 4:
                            html = "Atendido totalmente";
                            break;
                    }
                    return  html;
                },
                "targets": 4
            }
        ],
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            if (aData.patencion_id != '') {
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
        //desplegable de documentos
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.cargar("cboPersona", data.persona_activa, "id", ["nombre", "codigo_identificacion"]);
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);

        //desplegable de patencion detalle
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
    var monedaId = select2.obtenerValor('cboMoneda');
    var estadoProgramacion = select2.obtenerValor('cboEstadoProgramacion');
    llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, estadoProgramacion);

    buscarDocumentos();
}

function buscarPorCriterios2() {
    var personaId = select2.obtenerValor('cboPersona2');
    var documentoTipoIds = $('#cboDocumentoTipo2').val();
    var serie = $('#txtSerie2').val();
    var numero = $('#txtNumero2').val();
    var fechaEmision = {inicio: $('#inicioFechaEmision2').val(),
        fin: $('#finFechaEmision2').val()};
    var fechaProgramada = {inicio: $('#inicioFechaProgramada2').val(),
        fin: $('#finFechaProgramada2').val()};
    var monedaId = select2.obtenerValor('cboMoneda2');
    var estadoPAtencion = select2.obtenerValor('cboEstadoPAtencion');
    llenarParametrosBusqueda2(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaProgramada, estadoPAtencion);

    buscarProgramacionAtencionDetalle();
}

var criterioBusquedaDocumentos = {};

function llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, estadoProgramacion) {
    criterioBusquedaDocumentos = {};

    criterioBusquedaDocumentos.personaId = personaId;
    criterioBusquedaDocumentos.documentoTipoIds = documentoTipoIds;
    criterioBusquedaDocumentos.serie = serie;
    criterioBusquedaDocumentos.numero = numero;
    criterioBusquedaDocumentos.fechaEmision = fechaEmision;
    criterioBusquedaDocumentos.monedaId = monedaId;
    criterioBusquedaDocumentos.estadoProgramacion = estadoProgramacion;
}

var criterioBusquedaPAtencionDetalle = {};

function llenarParametrosBusqueda2(personaId, documentoTipoIds, serie, numero, fechaEmision, monedaId, fechaProgramada, estadoPAtencion) {
    criterioBusquedaPAtencionDetalle = {};

    criterioBusquedaPAtencionDetalle.personaId = personaId;
    criterioBusquedaPAtencionDetalle.documentoTipoIds = documentoTipoIds;
    criterioBusquedaPAtencionDetalle.serie = serie;
    criterioBusquedaPAtencionDetalle.numero = numero;
    criterioBusquedaPAtencionDetalle.fechaEmision = fechaEmision;
    criterioBusquedaPAtencionDetalle.monedaId = monedaId;
    criterioBusquedaPAtencionDetalle.fechaProgramada = fechaProgramada;
    criterioBusquedaPAtencionDetalle.estadoPAtencion = estadoPAtencion;
}

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function limpiarBuscadores() {
    $('#txtSerie').val('');
    $('#txtNumero').val('');
    $('#inicioFechaEmision').val('');
    $('#finFechaEmision').val('');

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
    $('#inicioFechaProgramada2').val('');
    $('#finFechaProgramada2').val('');

    select2.asignarValor('cboDocumentoTipo2', -1);
    select2.asignarValor('cboPersona2', -1);
    select2.asignarValor('cboMoneda2', -1);

    criterioBusquedaPAtencionDetalle = {};
}

function nuevoProgramacionAtencion(documentoId, patencionId) {
    var titulo = "Nueva";
    if (!isEmpty(patencionId)) {
        titulo = "Editar";
    }
    var url = URL_BASE + "vistas/com/programacionAtencion/atencion_form.php?winTitulo=" + titulo + "&docId=" + documentoId;
    cargarDiv("#window", url);
}

function actualizarTabActivo(tab) {
    tabActivo = tab;
    actualizarBusqueda();
}

function buscarProgramacionAtencionDetalle() {
    loaderShow();
    ax.setAccion("obtenerProgramacionAtencionDetalle");
    ax.addParamTmp("criterios", criterioBusquedaPAtencionDetalle);

    $('#datatableProgramacionAtencionDetalle').dataTable({
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
        "order": [3, "desc"],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": [
//Acc.	Documento	Cliente	Producto	Cantidad	F.Programada	F.Creación	Estado	Usuario
            {"data": "documento_tipo_id"},
            {"data": "serie_numero"},
            {"data": "persona_nombre_completo"},
//            {"data": "bien_descripcion"},
//            {"data": "cantidad_programada", "class": "alignRight"},
//            {"data": "organizador_desc"},
            {"data": "fecha_programada", "class": "alignCenter"},
            {"data": "fecha_creacion", "class": "alignCenter"},
            {"data": "patencion_detalle_estado_logico"},
            {"data": "usuario", "class": "alignCenter"}

        ],
        columnDefs: [
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
                "targets": 4
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    return fecha;
                },
                "targets": 3
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
                    var html = '';

                    // El estado
                    switch (row.patencion_detalle_estado_logico * 1) {
                        case 1:
                            html = html + "<i class='fa fa-server' style='color:#1ca8dd;' title='Programado'></i>&nbsp;&nbsp;&nbsp;";
                            break;
                        case 3:
                            html = html + "<i class='fa fa-unlock' style='color:green;' title='Liberado'></i></a>&nbsp;";
                            break;
                        case 4:
                            html = html + "<i class='fa fa-lock' style='color:red;' title='Comprometido'></i></a>&nbsp;&nbsp;&nbsp;";
                            break;
                        case 5:
                            html = html + "<i class='fa fa-th-large' style='color:orange;' title='Atendido parcialmente'></i></a>&nbsp;&nbsp;&nbsp;";
                            break;
                        case 6:
                            html = html + "<i class='fa fa-th-large' style='color:green;' title='Atendido totalmente'></i></a>&nbsp;&nbsp;&nbsp;";
                            break;
                        default:
                            html = html + "<i class='ion-close-circled' style='color:red;' title='Eliminado'></i>";
                    }


                    // La programación de pago
                    if (row.patencion_detalle_estado_logico != 2) {
                        html = html + "<a href='#' onclick='editarProgramacionAtencion(" + row.documento_id + "," + row.patencion_detalle_id + ")'><i class='fa fa-calendar-o' style='color:green;' title='Editar atención'></i></a>";
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
                    return "<a href='#' onclick='obtenerDocumento(" + row.documento_id + ")'><span class='" + row.documento_tipo_leyenda_clase + "' title='Visualizar documento'>" + row.documento_tipo_leyenda_siglas + "</span></a>&nbsp;" + data;
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    var html = '';
                    switch (data * 1) {
                        case 1:
                            html = "Programado";
                            break;
                        case 3:
                            html = "Liberado";
                            break;
                        case 4:
                            html = "Comprometido";
                            break;
                        case 5:
                            html = "Atendido parcialmente";
                            break;
                        case 6:
                            html = "Atendido totalmente";
                            break;
                        default:
                            html = "Eliminado";
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
    cargarDataDocumento(data.dataDocumento, data.dataDocumentoAdjunto);
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

function cargarDataDocumento(data, dataDocumentoAdjunto) {
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
                    case 27:
                        if (!isEmpty(dataDocumentoAdjunto)) {
                            valor = '<a style="color: blue;" href="util/uploads/documentoAdjunto/' + dataDocumentoAdjunto[0]['nombre'] + '" download="' + dataDocumentoAdjunto[0]['archivo'] + '">' + dataDocumentoAdjunto[0]['archivo'] + '</a>';
                        }
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

function actualizarEstadoPAtencionDetalle(patencionDetalleId, nuevoEstado) {
    swal({
        title: "¿Está seguro que desea cambiar el estado?",
        text: "Verificar si está seguro cambiar el estado de la atención seleccionada",
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
            ax.setAccion("actualizarEstadoPAtencionDetalle");
            ax.addParamTmp("patencionDetalleId", patencionDetalleId);
            ax.addParamTmp("nuevoEstado", nuevoEstado);
            ax.consumir();
        }
    });
}

function editarProgramacionAtencion(documentoId, patencionDetalleId) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/programacionAtencion/atencion_form.php?winTitulo=" + titulo + "&docId=" + documentoId + "&patencionDetalleId=" + patencionDetalleId;
    cargarDiv("#window", url);
}

function obtenerDocumentosRelacionados(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentosRelacionados");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
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

function obtenerDocumentoRelacion(documentoId) {
    $('#modalDocumentoRelacionado').modal('hide');
    obtenerDocumento(documentoId);
}