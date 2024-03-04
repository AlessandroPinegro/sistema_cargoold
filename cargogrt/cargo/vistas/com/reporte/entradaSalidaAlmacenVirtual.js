$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteBalance");
    obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual();
    modificarAnchoTabla('datatable');
    modificarAnchoTabla('datatableDetalle');
});

function onResponseReporteBalance(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataEntradaSalidaAlmacenVirtual':
                onResponseGetDataGridEntradaSalidaAlmacen(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                onResponseObtenerDocumentoRelacionVisualizar(response.data);
                loaderClose();
                break;
            case 'obtenerDataEntradaSalidaAlmacenVirtualDetalle':
                onResponseObtenerDataEntradaSalidaAlmacenVirtualDetalle(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
//            case 'obtenerReporteEntradaSalidaAlmacenExcel':
//                loaderClose();
//                break;
            case 'obtenerDocumentoRelacionVisualizar':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual()
{
    //alert('hola ES');
    ax.setAccion("obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {

    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrigen", data.organizador, "id", "descripcion");
    }

    if (!isEmpty(data.bien)) {
        select2.cargar("cboProducto", data.bien, "id", ["codigo", "descripcion"]);
    }

    if (!isEmpty(data.fecha_primer_documento)) {
        if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
        {
            $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
            if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
            {
                $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
            }
        }

    }
    loaderClose();
}

var valoresBusquedaEntradaSalidaAlmacen = [{origen: "", producto: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{
    var origen = $('#cboOrigen').val();
    var producto = $('#cboProducto').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();

    valoresBusquedaEntradaSalidaAlmacen[0].origen = origen;
    valoresBusquedaEntradaSalidaAlmacen[0].producto = producto;
    valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].origen))
    {
        cadena += negrita("Origen: ");
        cadena += select2.obtenerTextMultiple('cboOrigen');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].producto))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboProducto');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha traslado: ");
        cadena += valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.inicio + " - " + valoresBusquedaEntradaSalidaAlmacen[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarEntradaSalidaAlmacen(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaEntradaSalidaAlmacen(cadena);

    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaEntradaSalidaAlmacen()
{
    ax.setAccion("obtenerDataEntradaSalidaAlmacenVirtual");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalidaAlmacen);
    ax.consumir();
}

function onResponseGetDataGridEntradaSalidaAlmacen(data) {

    if (!isEmptyData(data))
    {
        $('#datatable').dataTable({
            "order": [0, "desc"],
            "data": data,
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_emision", "sClass": "alignCenter"},
                {"data": "serie_numero", "sClass": "alignCenter"},
                {"data": "org_origen"},
                {"data": "bien_codigo"},
                {"data": "bien_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "cant_rep", "sClass": "alignRight"},
                {"data": "unidad_medidad"},
                {"data": "documento_id", "sClass": "alignCenter"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 5
                },
                {
                    "render": function (data, type, row) {
                        return parseFloat(row.cantidad * 1 - row.cant_rep * 1).formatMoney(2, '.', ',');
                    },
                    "targets": 6
                },
                {
                    "render": function (data, type, row) {
                        return '<a onclick="verDetalleEntradaSalidaAlmacen(' + row['documento_id'] + ',' + row['movimiento_id'] + ')"><b style="color: blue;" >' + data + '<b></a>';
                        ;
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        var accion = '<a onclick="verDetalleReposicion(' + row['documento_id'] + ',' + row['bien_id'] + ')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
                        if (row['cant_rep'] * 1 == 0) {
                            accion = '';
                        }
                        return accion;
                        ;
                    },
                    "targets": 8
                }
            ],
            destroy: true
        });
    } else {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }

}

function verDetalleReposicion(documentoId, bienId) {
    loaderShow();
    ax.setAccion("obtenerDataEntradaSalidaAlmacenVirtualDetalle");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("bienId", bienId);
    ax.consumir();
}

function verDetalleEntradaSalidaAlmacen(documentoId, movimientoId)
{
    $('#modalDetalleReporte').modal('hide');
    visualizarDocumento(documentoId, movimientoId);
}

var dataVisualizarDocumento;
var docId;
function visualizarDocumento(documentoId, movimientoId)
{
    docId = documentoId;

    $('#txtCorreo').val('');
    loaderShow();
    ax.setAccion("obtenerDocumentoRelacionVisualizar");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("movimientoId", movimientoId);
    ax.consumir();
}

function onResponseObtenerDocumentoRelacionVisualizar(data)
{

    resultadoObtenerEmails = null;
    dataVisualizarDocumento = data;
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento, data.configuracionEditable, data.dataDocumentoAdjunto);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna);
    } else {
        $('#datatable2').hide();
    }

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

    $("#formularioDetalleDocumento").empty();
    var contador = 0;

    if (!isEmpty(data)) {
        $('#tituloVisualizacionModal').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            if (contador % 3 == 0) {
                appendFormDetalle('<div class="row">');
                appendFormDetalle('</div>');
            }
            contador++;

            var html = '<div class="form-group col-md-4"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = '';
            if (item.edicion_habilitar == 0 || item.edicion_habilitar == 1) {
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
                                valor += '<input type="text" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45"/>';
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

function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna) {
    $('#datatable2').show();
    movimientoTipoColumna = dataMovimientoTipoColumna;

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
//        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataVisualizarDocumento.organizador)) {
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
            if (!isEmpty(dataVisualizarDocumento.organizador)) {
                html += "<td>" + item.organizador + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidadMedida + "</td>";
            html += "<td>" + item.bien_codigo + " | " + item.descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.precioUnitario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarEntradaSalidaAlmacen();
    }
    loaderClose();
}

function onResponseDetalleEntradaSalidaAlmacen(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + ' - ' + data[0]['unidad_medida_descripcion'] + '</strong>';

        $('#datatableEntradaSalidaAlmacen').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "organizador_descripcion"},
                {"data": "bien_tipo_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "stock", "sClass": "alignRight"},
                {"data": "frecuencia", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-documentos-servicios').modal('show');
    } else
    {
        var table = $('#datatableEntradaSalidaAlmacen').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este producto.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteEntradaSalidaAlmacenExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteEntradaSalidaAlmacenExcel");
    ax.addParamTmp("criterios", valoresBusquedaEntradaSalidaAlmacen);
    //ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarEntradaSalidaAlmacen(0);
    }
    loaderClose();
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

function onResponseObtenerDataEntradaSalidaAlmacenVirtualDetalle(data) {
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');

//        var tituloModal = '<strong> Detalle de reposiciones del documento ' + data[0]['serie_numero_pend'] + ' y producto '+data[0]['bien_codigo'] +' | '+data[0]['bien_descripcion'] +'</strong>';
        var tituloModal = '<strong> Detalle de reposiciones</strong>';

        $('#tituloDetalleReporte').html(tituloModal);

        $('#modalDetalleReporte').modal('show');
        setTimeout(function () {
            cargarDataTableDetalleReporte(data);
        }, 500);

    } else {
        var table = $('#datatableDetalle').DataTable();
        table.clear().draw();

        mostrarAdvertencia('No existen reposiciones.');
    }
}

function cargarDataTableDetalleReporte(data) {
    $('#datatableDetalle').dataTable({
        "order": [0, "desc"],
        "data": data,
        "scrollX": true,
        "autoWidth": true,
        "columns": [
            {"data": "fecha_emision", "sClass": "alignCenter"},
            {"data": "serie_numero", "sClass": "alignCenter"},
            {"data": "org_destino"},
            {"data": "bien_codigo"},
            {"data": "bien_descripcion"},
            {"data": "cantidad", "sClass": "alignRight"},
            {"data": "unidad_medidad"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    return '<a onclick="verDetalleEntradaSalidaAlmacen(' + row['documento_id'] + ',' + row['movimiento_id'] + ')"><b style="color: blue;" >' + data + '<b></a>';
                    ;
                },
                "targets": 1
            }
        ],
        destroy: true
    });
}