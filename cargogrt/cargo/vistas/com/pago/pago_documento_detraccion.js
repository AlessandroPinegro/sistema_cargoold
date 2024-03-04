var banderaBuscarMP = 0;
var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var total;
var total_cantidad;
var botonEnviar = $('#btnEnviar i').attr('class');
var documentoPagoArray = new Array();
var pagoConDocumentoPagoArray = new Array();
var documentoXPagar;
$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponsePagoDetraccion");
//    ax.setAccion("listarRegistroComprasXCriteriosDetraccion");
    obtenerConfiguracionesInicialesPagoDetraccion();
    buscarReporteCompras();
    modificarAnchoTabla('datatable2');
});

function onResponsePagoDetraccion(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicial':
                onResponseObtenerConfiguracionesInicialesPagoDetraccion(response.data);
                break;
            case 'listarRegistroComprasXCriteriosDetraccion':
                onResponseListarRegistroComprasXCriteriosDetraccion(response.data);
                $('#datatable').dataTable({
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
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                onResponseObtenerDocumentoRelacionVisualizar(response.data);
                loaderClose();
                break;
            case 'obtenerDocumento':
                onResponseObtenerDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentosRelacionados':
                onResponseObtenerDocumentosRelacionados(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion':
                onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion(response.data);
                break;
            case 'guardarDocumento':
//                agregarDocumentoPagoConDocumento(response.data)   
                banderaGuardarDoc = 1;
                agregarDocumentoPagoConDocumento(response.data, 1, monedaid);
                loaderClose("modalNuevoDocumentoPagoConDocumentoPago");
                habilitarBoton();
                break;
            case 'obtenerDocumentoPagoConDocumentoPago':
                onResponseDocumentoPagoConDocumentoPago(response.data);
                validarMonedasFormasPago();
                break;
            case 'obtenerDocumentoAPagar':
                onResponseDocumentoAPagar(response.data);
//                validarMonedasFormasPago();
                break;
            case 'obtenerTipoCambioXfecha':
                onResponseObtenerTipoCambioHoy(response.data);
                break;
            case 'registrarPago':
                HabilitarBotonSweet(response.data);
                loaderClose();
                break;
            case 'registrarPagoDetraccion':
                HabilitarBotonSweet(response.data);
                buscarReporteCompras();
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'registrarPagoDetraccion':
                habilitarBoton();
                HabilitarBotonSweet({error: response.status, mensaje: response.message});
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPagoDetraccion() {
    ax.setAccion("obtenerConfiguracionInicial");
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesPagoDetraccion(data) {
    onResponseObtenerDataCbo('PersonaProveedor', 'id', ['codigo_identificacion', ' | ', 'nombre'], data.persona_activa);
//    onResponseListarRegistroCompras(data);
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

var valoresBusquedaReporteCompras = [{}];

function cargarDatosBusquedaRegistroCompras() {
    var personaId = $('#cboPersonaProveedor').val();
    var serie = $('#serie').val();
    var numero = $('#numero').val();
    var fechaInicio = $('#inicioFechaEmisionMP').val();
    var fechaFin = $('#finFechaEmisionMP').val();
    valoresBusquedaReporteCompras = [{}];
    valoresBusquedaReporteCompras[0].empresa = commonVars.empresa;
    valoresBusquedaReporteCompras[0].persona = personaId;
    valoresBusquedaReporteCompras[0].serie = serie;
    valoresBusquedaReporteCompras[0].numero = numero;
    valoresBusquedaReporteCompras[0].fechaEmisionDesde = fechaInicio;
    valoresBusquedaReporteCompras[0].fechaEmisionHasta = fechaFin;
}

function buscarReporteCompras(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerReporteDeCompras();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }

    banderaBuscarMP = 1;

    if (colapsa === 1) {
        colapsarBuscador();
        $('[data-toggle="popover"]').popover('show');
    }
}

var actualizandoBusqueda = false;
function loaderBuscarVentas() {
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarReporteCompras();
    }
}

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

function cerrarPopover() {
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

function obtenerDatosBusqueda() {
    var cadena = "";
    cargarDatosBusquedaRegistroCompras();
    if (!isEmpty(select2.obtenerValor('cboPersonaProveedor'))) {
        cadena += StringNegrita("Proveedor: ");

        cadena += select2.obtenerText('cboPersonaProveedor');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteCompras[0].serie) || !isEmpty(valoresBusquedaReporteCompras[0].serie)) {
        cadena += StringNegrita("Serie: ");

        cadena += $("#serie").val();
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteCompras[0].numero) || !isEmpty(valoresBusquedaReporteCompras[0].numero)) {
        cadena += StringNegrita("Numero: ");

        cadena += $("#numero").val();
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteCompras[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaReporteCompras[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteCompras[0].fechaEmisionDesde + " - " + valoresBusquedaReporteCompras[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
//sp_documento_operacion_buscarParaCopiar_consulta

function obtenerReporteDeCompras() {
    ax.setAccion("listarRegistroComprasXCriteriosDetraccion");
    ax.addParamTmp("criterios", valoresBusquedaReporteCompras);
    ax.consumir();
}

function onResponseListarRegistroComprasXCriteriosDetraccion(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = "<table id='datatable' class='table table-sm table-striped table-hover table-bordered'>"
//    var cabeza = "<table id='datatable' style='width:100%' class='table table-small-font table-striped table-bordered' >"
            + "<thead style='width:100%'>" +
            " <tr>" +
            "<th style='text-align:center;'width=50px>F. Emsión</th>" +
            "<th style='text-align:center;'>Tipo</th>" +
            "<th style='text-align:center;'>S | N</th>" +
            "<th style='text-align:center;'>R.U.C.</th>" +
            "<th style='text-align:center;'>Proveedor</th>" +
//            "<th style='text-align:center;'>Tipo Cambio</th>"+
            "<th style='text-align:center;'>Moneda</th>" +
            "<th style='text-align:center;'>Sub-Total</th>" +
            "<th style='text-align:center;'>IGV</th>" +
            "<th style='text-align:center;'>Total</th>" +
            "<th style='text-align:center;'width=50px>M. Pendiente</th>" +
            "<th style='text-align:center;'width=50px>Detracción</th>" +
            "<th style='text-align:center;'width=50px>M. Detra.</th>" +
            "<th style='text-align:center;'width=50px>Acc.</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cuerpo = '<tr>' +
                    '<td style="text-align:center;">' + formatearFechaBDCadena(item.fecha_emision) + '</td>' +
                    '<td style="text-align:center;">' + item.documento_tipo_descripcion + '</td>' +
                    '<td style="text-align:center;">' + item.serie_numero + '</td>' +
                    '<td style="text-align:center;">' + item.codigo_identificacion_persona + '</td>' +
                    '<td style="text-align:center;">' + item.nombre_persona + '</td>' +
//                    '<td style="text-align:left;">' + item.tipo_cambio + '</td>' +
                    '<td style="text-align:center;">' + item.desc_moneda + '</td>' +
                    '<td style="text-align:right;">' + formatearNumeroPorCantidadDecimales(item.sub_total, 2) + '</td>' +
                    '<td style="text-align:right;">' + formatearNumeroPorCantidadDecimales(item.igv, 2) + '</td>' +
                    '<td style="text-align:right;">' + formatearNumeroPorCantidadDecimales(item.total, 2) + '</td>' +
                    '<td style="text-align:right;">' + formatearNumeroPorCantidadDecimales(item.pendiente, 2) + '</td>' +
                    '<td style="text-align:left;">' + item.detra_descripcion + '</td>' +
                    '<td style="text-align:right;">' + formatearNumeroPorCantidadDecimales(item.detra_total, 2) + '</td>' +
                    '<td style="text-align:center;">' +
                    '<a onclick="visualizarDocumento(' + item.id + ')"><b><i class="fa fa-eye" style="color:#1ca8dd;"></i><b></a>&nbsp;&nbsp;&nbsp;&nbsp;\n' +
                    '<a onclick="modalNuevoDocumentoPagoConDocumentoPago(' + item.persona_id + ',' + item.detra_total + ',' + item.id + ')"><b><i class="fa fa-money" style="color:#00A41A;"></i><b></a>' +
                    '</td>' +
                    '</tr>';
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);
    loaderClose();
}

var docId;
function visualizarDocumento(documentoId, movimientoId) {
    docId = documentoId;
    loaderShow();
    ax.setAccion("obtenerDocumentoRelacionVisualizar");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("movimientoId", movimientoId);
    ax.consumir();
}

function onResponseObtenerDocumentoRelacionVisualizar(data) {
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
            $('#datatable2').show();
        }
        if (!isEmpty(data.dataDistribucionContable)) {
            $('a[href="#distribucion"]').click();
            cargarDistribucionDocumento(data.dataDistribucionContable);
        } else {
            $('#datatableDistribucion2').show();
        }
        $('#tabsDistribucionMostrar').hide();
        $("#div_contenido_tab").removeClass("tab-content");
    }
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function cargarDataDocumento(data, configuracionEditable, dataDocumentoAdjunto) {
    textoDireccionId = 0;
    personaDireccionId = 0;
    camposDinamicos = [];

    guardarEdicionDocumento = false;
    if (!isEmpty(configuracionEditable)) {
        guardarEdicionDocumento = true;
    }
    $("#formularioDetalleDocumento").empty();
    var contador = 0;
    if (!isEmpty(data)) {
        $('#tituloVisualizacionModal').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            if (item.tipo != 31) {

                if (contador % 3 == 0) {
                    appendFormDetalle('<div class="row">');
                    appendFormDetalle('</div>');
                }
                contador++;
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

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function fechaArmada(valor) {
    var fecha = separarFecha(valor);
    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
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


//inicio de sección modal nuevo documento pago con documento

var personaNuevoId;
var totalDetraccion;
var documentoAPagar;
function modalNuevoDocumentoPagoConDocumentoPago(personaId, montoDetraccion, documentoAPagarId) {
    documentoXPagar = documentoAPagarId;
    totalDetraccion =  Math.round(montoDetraccion*100)/100;

    $('[data-toggle="popover"]').popover('hide');
    var clienteId = personaId;
    if (personaNuevoId > 0 && clienteId > 0)
    {
        select2.asignarValor('cbo_' + personaNuevoId, clienteId);
    }
    personaNuevoId = clienteId;
    documentoAPagar = documentoAPagarId;
    obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion(documentoAPagarId);
}

function obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion() {
    loaderShow();
    ax.setAccion("obtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion");
    ax.addParamTmp('empresa_id', commonVars.empresa);
    ax.consumir();
}

var dataConfigInicialDocPago;
var dataDocumentoTipo;
var dataDocumentoTipoConf;
var dataDocumentoTipoId;
function onResponseObtenerConfiguracionesInicialesNuevoDocumentoPagoConDocumentoPagoDetraccion(data) {
    dataConfigInicialDocPago = data;
    dataDocumentoTipoConf = data.documento_tipo_conf;
    dataDocumentoTipoId = data.documento_tipo["id"];
    onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(dataDocumentoTipoConf);
    select2.cargar("cboPeriodo", data.periodo, "id", ["anio", "mes"]);
    select2.asignarValorQuitarBuscador('cboPeriodo', null);
}

var camposDinamicos = [];
var fechaId;
var cambioPersonalizadoId = 0;
var dataPersona = [];
var banderaPersona = false;
var fechaDocumentoPago;
function onResponseObtenerDocumentoTipoDatoNuevoPagoConDocumento(data) {
    camposDinamicos = [];
    fechaId = 0;
    cambioPersonalizadoId = 0;
    var moneda_id = 2;
    var tipo_moneda = (moneda_id == "4" ? " (Dolares)" : " (Soles)");
    $("#span_moneda").text(tipo_moneda);
    $("#formNuevoDocumentoPagoConDocumentoPago").empty();

    if (!isEmpty(data)) {
        $("#contenedorDocumentoTipoNuevo").css("height", 55 * data.length);
        // Mostraremos la data en filas de dos columnas        
        $.each(data, function (index, item) {
            appendFormNuevo('<div class="row">');
            var html = '<div class="form-group col-md-12">';
            if (item.tipo == 5) {
                html += '<label hidden>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
            } else {
                html += '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
            }
            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';

            camposDinamicos.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion
            });

            switch (parseInt(item.tipo)) {
                case 1:
                case 14:
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="' + totalDetraccion + '" maxlength="45" style="text-align:right; "/>';
                    break;
                case 2:
                case 6:
                case 7:
                case 8:
                case 12:
                case 13:

                    var readonly = (parseInt(item.editable) === 0) ? 'readonly="true"' : '';
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }
                    html += '<input type="text" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" ' + readonly + '" class="form-control" value="' + value + '" maxlength="45"/>';
                    break;
                case 3:
                case 9:
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + item.data + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2"></select>';
                    break;
                case 5:
                    $.each(item.data, function (indexPersona, itemPersona) {
                        if (parseInt(itemPersona.id) === parseInt(personaNuevoId)) {
                            dataPersona = itemPersona;
                        }
                    });
                    html += '<div id ="div_proveedor" ><select name="cbond_' + dataPersona["id"] + '" id="cbond_' + dataPersona["id"] + '" class="select2" hidden>';
//                    html += '<div id ="div_proveedor" ><select name="cboClientePagoPago" id="cboClientePagoPago" class="select2" hidden>';
                    html += '<option value="' + dataPersona["id"] + '">' + dataPersona["nombre"] + ' | ' + dataPersona["codigo_identificacion"] + '</option hidden>';
                    html += '</select>';
//                    personaNuevoId = dataPersona["id"];
                    break;
                case 20:
                    html += '<div id ="div_cuenta" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        if (itemCuenta.moneda_id * 1 == (moneda_id) * 1) {
                            html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                        }
                    });
                    html += '</select>';
                    break;
                case 21:
                    html += '<div id ="div_actividad" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="go select2 "';
                    html += '<option value="' + 0 + '">Seleccione la actividad</option>';
                    $.each(item.data, function (indexActividad, itemActividad) {
                        html += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
                case 24:
                    cambioPersonalizadoId = item.id;
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="" maxlength="45" style="text-align:right; "/>';
                    break;
            }
            html += '</div></div>';
            appendFormNuevo(html);
            appendFormNuevo('</div>');
            switch (item.tipo) {
                case 4, "4":
                    select2.cargar("cbond_" + item.id, item.data, "id", "descripcion");
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5, "5":
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 20, "20":
                case 21, "21":
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.numero_defecto)) {
                        var id = parseInt(item.numero_defecto);
                        select2.asignarValor("cbond_" + item.id, id);
                    }

                    if (item.editable == 0) {
                        $("#cbond_" + item.id).attr('disabled', 'disabled');
                    }
                    break;
                case 9, "9":
                    fechaId = item.id;
                    $('#datepickernd_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    }).on('changeDate', function (ev) {
                        if (cambioPersonalizadoId != 0 && !isEmpty(cambioPersonalizadoId)) {
                            obtenerTipoCambioXFechaDocumentoPago();
                        }
                        cambiarPeriodo();
                    });

                    //FALTA REVISAR EN QUE CASO SE DA.
                    if (cambioPersonalizadoId != 0 && !isEmpty(cambioPersonalizadoId)) {
                        obtenerTipoCambioXFechaDocumentoPago();
                    }

//                    $('#datepickernd_' + item.id).datepicker('setDate', item.data);                    
                    setTimeout(function () {
                        cambiarPeriodo();
                    }, 300);
                    fechaDocumentoPago = $('#datepickernd_' + item.id).val();
                    console.log(fechaDocumentoPago);
                    break;
            }
        });

        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });

    }
    $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');
    loaderClose("#modalNuevoDocumentoPagoConDocumentoPago");
}

function appendFormNuevo(html) {
    $("#formNuevoDocumentoPagoConDocumentoPago").append(html);
}
function cambiarPeriodo() {
    var periodoId = obtenerPeriodoIdXFechaEmision();
    select2.asignarValorQuitarBuscador('cboPeriodo', periodoId);
}

function obtenerPeriodoIdXFechaEmision() {
    var periodoId = null;
    var dtdFechaEmision = obtenerDocumentoTipoDatoIdDocPagoXTipo(9);
    if (!isEmpty(dtdFechaEmision)) {
        var fechaEmision = $('#datepickernd_' + dtdFechaEmision).val();

        var fechaArray = fechaEmision.split('/');
        var d = parseInt(fechaArray[0], 10);
        var m = parseInt(fechaArray[1], 10);
        var y = parseInt(fechaArray[2], 10);

        $.each(dataConfigInicialDocPago.periodo, function (index, item) {
            if (item.anio == y && item.mes == m) {
                periodoId = item.id;
            }
        });
    }
//    console.log(fechaArray,periodoId);
    return periodoId;
}

function obtenerDocumentoTipoDatoIdDocPagoXTipo(tipo) {
    var dataConfig = dataConfigInicialDocPago.documento_tipo_conf;

    var id = null;
    if (!isEmpty(dataConfig)) {
        $.each(dataConfig, function (index, item) {
            if (parseInt(item.tipo) === parseInt(tipo)) {
                id = item.id;
                return false;
            }
        });
    }

    return id;
}

function onResponseObtenerDocumentoTipo(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");

        if (data.documento_tipo.length === 1) {
            select2.asignarValor("cboDocumentoTipo", data.documento_tipo[0].id);
            select2.readonly("cboDocumentoTipo", true);
            $('#divTipoDocumento').hide();
        }

        onResponseObtenerDocumentoTipoDato(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoLista(data.documento_tipo_dato_lista);
    }
}

function obtenerValoresCamposDinamicos() {

    var isOk = true;
    if (isEmpty(camposDinamicos))
        return false;
    $.each(camposDinamicos, function (index, item) {

        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 24:
            case 14:
                camposDinamicos[index]["valor"] = document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
                //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                camposDinamicos[index]["valor"] = document.getElementById("datepickernd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
                camposDinamicos[index]["valor"] = personaNuevoId;
                break;
            case 20:// cuenta
            case 21:// actividad
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbond_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
        }

    });
    return isOk;

}


//========== INICIO DE GUARDAR DOCUMENTO DETRACIOCCIÓN ===============

function enviarDocumento() {
    $('#modalNuevoDocumentoPagoConDocumentoPago').modal('hide');
    //VALIDO QUE EL PERIODO ESTE SELECCIONADO
    var periodoId = select2.obtenerValor('cboPeriodo');
    if (isEmpty(periodoId)) {
        mostrarAdvertencia('Seleccione un periodo');
        return;
    }

    //VALIDO QUE LA FECHA DE EMISION ESTE EN EL PERIODO SELECCIONADO
    var periodoFechaEm = obtenerPeriodoIdXFechaEmision();
    if (periodoId != periodoFechaEm) {
        //OCULTO EL MODAL
        $('#modalNuevoDocumentoPagoConDocumentoPago').modal('hide');

        swal({
            title: "¿Desea continuar?",
            text: "La fecha de emisión no está en el periodo seleccionado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                guardarDocumento();
            }
            $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');
        });
        return;
    }
    var pagoDescripcion = '';
    swal({
        title: "Est\xe1s seguro?",
        text: pagoDescripcion
                + "Registrar y Pagar el documento de Detracción",
        type: "warning",
        html: true,
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,registrar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
//        closeOnConfirm: true,
//        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            guardarDocumento();
        } else {
            $('#modalNuevoDocumentoPagoConDocumentoPago').modal('show');
        }
    });

}

var banderaGuardarDoc = 0;
var monedaid = 2;
function guardarDocumento() {
    deshabilitarBoton();

    loaderShow();
    varGuardarDocumento = true;
//obtenemos el tipo de documento

    var documentoTipoId = dataDocumentoTipoId;
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }
//Validar y obtener valores de los campos dinamicos
    obtenerValoresCamposDinamicos();
    if (!obtenerValoresCamposDinamicos())
        return;
//    var monedaid = 2;
    var periodoId = select2.obtenerValor('cboPeriodo');

    ax.setAccion("registrarPagoDetraccion");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("monedaId", monedaid);
    ax.addParamTmp("periodoId", periodoId);
    ax.addParamTmp("documentoAPagarId", documentoXPagar);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}

function habilitarBoton() {
    $("#btnEnviar").removeClass('disabled');
    $("#btnEnviar i").removeClass('fa-spinner fa-spin');
    $("#btnEnviar i").addClass(botonEnviar);
}

function deshabilitarBoton() {
    $("#btnEnviar").addClass('disabled');
    $("#btnEnviar i").removeClass(botonEnviar);
    $("#btnEnviar i").addClass('fa fa-spinner fa-spin');
}

//========== INICIO DE PAGAR CON DOCUMENTO DETRACIOCCIÓN ===============

var accionAgregarDocumento;
function agregarDocumentoPagoConDocumento(documentoId, tipo, moneda) {
    loaderShow();
    accionAgregarDocumento = tipo;
    var tmoneda = 0;
    if (!verificarDocumentoPagoFueAgregado(pagoConDocumentoPagoArray, documentoId) && !validarPagaConDocumento(documentoId))
    {
        if (!verificaDocumentoMoneda(pagoConDocumentoPagoArray, tmoneda)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento posee un tipo moneda diferente al de los agregados");
            return false;
        }
        ax.setAccion("obtenerDocumentoPagoConDocumentoPago");
        ax.addParamTmp("documentoId", documentoId);
        ax.consumir();
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El documento ya fue agregado");
    }
}

function validarPagaConDocumento(id) {
    var exito = false;
    $.each(documentoPagoArray, function (index, item) {
        if (item.documentoId == id)
        {
            exito = true;
            return false;
        }
    });
    return exito;
}

function verificarDocumentoPagoFueAgregado(data, documentoId) {
    debugger;
    var bandera = false;

    $.each(data, function (index, item) {
        if (item.documentoId * 1 === documentoId * 1)
        {
            bandera = true;
        }
    });
    return bandera;
    debugger;
}

function onResponseDocumentoPagoConDocumentoPago(data) {
    debugger;
    var objDocumentosPagoConDocumento = {
        documentoId: null,
        tipoDocumento: null,
        tipoDocumentoId: null,
        numero: null,
        serie: null,
        pendiente: null,
        total: 0.00,
        monto: 0.00,
    };
    objDocumentosPagoConDocumento.documentoId = data[0].documento_id;
    objDocumentosPagoConDocumento.tipoDocumento = data[0].documento_tipo;
    objDocumentosPagoConDocumento.tipoDocumentoId = data[0].documento_tipo_id;
    objDocumentosPagoConDocumento.numero = data[0].numero;
    objDocumentosPagoConDocumento.serie = data[0].serie;
    objDocumentosPagoConDocumento.pendiente = redondearNumero(data[0].pendiente);
    objDocumentosPagoConDocumento.total = redondearNumero(data[0].total);
    objDocumentosPagoConDocumento.dolares = data[0].dolares;
    objDocumentosPagoConDocumento.moneda = data[0].dolares * 1 === 0 ? "Soles" : "Dolares";
    objDocumentosPagoConDocumento.monto = redondearNumero(data[0].monto);
    pagoConDocumentoPagoArray.push(objDocumentosPagoConDocumento);
//    agregarDocumentoPagoConDocumentoDataTable();
    $("#monedaId").prop("disabled", true);

    if (banderaGuardarDoc === 0) {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');
    }
    banderaGuardarDoc = 0;
    debugger;
}

function verificaDocumentoMoneda(data, moneda) {
    if (data.length === 0) {
        return true;
    }
    return data[0].dolares * 1 === moneda * 1;
}

function validarMonedasFormasPago() {
    console.log(fechaDocumentoPago);
    obtenerTipoCambioHoy(fechaDocumentoPago);
    debugger;
    var a = documentoPagoArray.length;
    var b = pagoConDocumentoPagoArray.length;
    var tipoCambio = $('#tipoCambio').val();
    var checkTipoCambio = $("#checkBP").is(":checked");

    if (a * b > 0 && tc < 0) {
        var d1 = documentoPagoArray[0].dolares * 1;
        var d2 = pagoConDocumentoPagoArray[0].dolares * 1;
        if (d1 + d2 > 0) {
            if (isEmpty(tipoCambio) && !checkTipoCambio) {
                $(".btn-submit").prop("disabled", true);
                $.Notification.notify('error', 'top center', 'Error', 'No se ha registrado un tipo de cambio para la fecha de pago.');
                return false;
            }
        }
    }
    $(".btn-submit").prop("disabled", false);

    ax.setAccion("obtenerDocumentoAPagar");
    ax.addParamTmp("documentoId", documentoXPagar);
    ax.addParamTmp("fechaPago", fechaDocumentoPago);
    ax.consumir();
    return true;

    debugger;
}
var fc = "";
function obtenerTipoCambioHoy(fecha) {
    //var fecha = obtenerFechaActual();
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXfecha");
        if (isEmpty(fecha)) {
            fecha = datex.getNow1();
        }
        ax.addParamTmp("fecha", fecha);
        ax.consumir();
        fc = fecha;
    }
}
var tipoCambioXFecha;
function onResponseObtenerTipoCambioHoy(data) {
    debugger;
    tipoCambioXFecha = data[0].equivalencia_venta;
    debugger
}
//function confirmarRegistrarPago(){
////    if (validarCamposDeEntradaPago())
////    {
//        SweetConfirmarRegistrarPago();
////    }
//}

function validarCamposDeEntradaPago() {
    var bandera = true;
    var cliente = personaNuevoId;
    var fecha = fechaId;
    var documentoAPagar = documentoPagoArray;
    if (documentoAPagar.length == 0)
    {
        bandera = false;
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Ingresar documento a pagar');
    }
    if (fecha.length == 0 || fecha == null || fecha == '')
    {
        bandera = false;
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar una fecha');
    }
    if (cliente == -1)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Seleccionar un cliente');
        bandera = false;
    }
    return bandera;
}

//function SweetConfirmarRegistrarPago() {
//    debugger;
//    var actividadEfectivo = $('#cboActividadEfectivo').val();
//
//    var checked = isChecked("checkPE");
//    if (checked && actividadEfectivo == -1) {
//        mostrarValidacionLoaderClose("Debe seleccionar actividad.");
//        return;
//    }
//
//    //para mostrar mensaje que el pago > al monto a pagar
//    var pagoDescripcion = '';debugger;
//    var aPagar = obtenerTotalListaDeDocumentos(documentoPagoArray);
//    var aPagarConv = aPagar;debugger;
//    if (getFormatoPagoActual() != getFormatoDocumentoAPagar()) {
//        aPagarConv = devolverImporteConvertido(aPagar) * 1;
//    }
//    var pago = $('#txtMontoAPagar').val() * 1 + obtenerTotalListaDeDocumentos(pagoConDocumentoPagoArray) * 1;
//
//    if (pago > aPagarConv) {
//        pagoDescripcion = '<p style="color: #d20e0e;display: block;">El total de pago es mayor al total del documento a pagar. '
//                + '<br>En: ' + getFormatoPagoActual() + formatearNumero(pago - aPagarConv)
//                + '</p> <br>';
//    }
//
//    var pagoEnEfectivo = isChecked("checkPE") ?
//            "Pago en efectivo: " + getFormatoPagoActual() + formatearNumero($('#txtMontoAPagar').val()) + "<br>" : "";
//    swal({
//        title: "Est\xe1s seguro?",
//        text: pagoDescripcion
//                + "Total de documento a pagar: " + getFormatoDocumentoAPagar() + formatearNumero(obtenerTotalListaDeDocumentos(documentoPagoArray)) + "<br>"
//                + pagoEnEfectivo +
//                "Pago con documento: " + getFormatoPagoActual() + formatearNumero(obtenerTotalListaDeDocumentos(pagoConDocumentoPagoArray)),
//        type: "warning",
//        html: true,
//        showCancelButton: true,
//        confirmButtonColor: "#33b86c",
//        confirmButtonText: "Si,registrar!",
//        cancelButtonColor: '#d33',
//        cancelButtonText: "No,cancelar!",
//        closeOnConfirm: true,
//        closeOnCancel: false
//    }, function (isConfirm) {
//        if (isConfirm) {
//            registrarPago();
//        } else {
//            swal("Cancelado", "La operaci\xf3n fue cancelada", "error");
//        }
//    });
//    debugger;
//}

function onResponseDocumentoAPagar(data) {
    debugger;
    var objDocumentos = {
        documentoId: null,
        tipoDocumentoId: null,
        tipoDocumento: null,
        numero: null,
        serie: null,
        pendiente: null,
        dolares: null,
        total: null,
        tipo: null,
        detra_id: null,
        detra_descripcion: null,
        detra_total: null
    };
    objDocumentos.documentoId = data[0].documento_id;
    objDocumentos.tipoDocumento = data[0].documento_tipo;
    objDocumentos.numero = data[0].numero;
    objDocumentos.serie = data[0].serie;
    objDocumentos.pendiente = data[0].deuda_liberada;
    objDocumentos.dolares = data[0].mdolares;
    objDocumentos.total = data[0].total;
    objDocumentos.tipo = data[0].tipo;
    objDocumentos.tipo = data[0].detra_id;
    objDocumentos.tipo = data[0].detra_descripcion;
    objDocumentos.tipo = data[0].detra_total;
    documentoPagoArray.push(objDocumentos);
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Documento agregado');

    registrarPago();
    debugger;
}

function getFormatoPagoActual() {
    return monedaid * 1 === 2 ? "S/." : "$ ";
}

function getFormatoDocumentoAPagar() {
    return (documentoPagoArray[0].dolares) * 1 === 1 ? "$ " : "S/.";
}

function obtenerTotalListaDeDocumentos(dataArray) {
    totalPago = 0;
    $.each(dataArray, function (index, item) {
        totalPago = totalPago + parseFloat(item.pendiente);
    });
    return totalPago;
}

function devolverImporteConvertido(importe) {
    var tipoCambio = $('#tipoCambio').val();
    if (isEmpty(documentoPagoArray) || isEmpty(tipoCambio) || tipoCambio == '' || isEmpty(importe)) {
        return 0;
    }
    var monedaId = $("#monedaId").val();

//    console.log(importe,tipoCambio);

    var factor = 1;
    if (documentoPagoArray[0].dolares * 1 == 0 && monedaId == 4) {
        factor = tipoCambio;
    } else if (documentoPagoArray[0].dolares * 1 == 1 && monedaId == 2) {
        factor = 1 / tipoCambio;
    }

    var importeConvertido = importe / factor;
//    importeConvertido = importeConvertido.toFixed(2);//falta redondeo superior.

    var importe_10 = importeConvertido * 10;
    var importe_10_ceil = Math.ceil(importe_10);
    importeConvertido = importe_10_ceil / 10;

    return importeConvertido.toFixed(2);
}

function HabilitarBotonSweet(data) {
    habilitarBoton();

    $(".confirm").removeProp("disabled");

    $(".cancel").removeProp("disabled");

    if (isEmpty(data.error)) {
        swal("Correcto!", "Operacion exitosa", "success");
    } else {
        swal("Validación!", data.mensaje, "warning");
    }
}


function registrarPago() {
    debugger;

    var montoAPagar = 0;
    var tipoCambio = tipoCambioXFecha;
    var monedaPago = monedaid;
    var cliente = personaNuevoId;
    var fecha = fechaDocumentoPago;
    var actividadEfectivo = $("#cbond_2680").val();
//            select2.obtenerValor('cbond_2681');
//    var ac = $("#cbond_").attr("actividad").val();
    debugger;
    var documentoAPagar = documentoPagoArray;
    var documentoPagoConDocumento = pagoConDocumentoPagoArray;
    debugger;
    ax.setAccion("registrarPago");
    ax.addParamTmp("cliente", cliente);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("montoAPagar", montoAPagar);
    ax.addParamTmp("monedaPago", monedaPago);
    ax.addParamTmp("tipoCambio", tipoCambio);
    ax.addParamTmp("documentoAPagar", documentoAPagar);
    ax.addParamTmp("documentoPagoConDocumento", documentoPagoConDocumento);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("actividadEfectivo", actividadEfectivo);
    ax.consumir();
    debugger;

}


//========== FIN DE PAGAR CON DOCUMENTO DETRACIOCCIÓN ===============





//========== FIN DE GUARDAR DETRACIOCCIÓN ===============

