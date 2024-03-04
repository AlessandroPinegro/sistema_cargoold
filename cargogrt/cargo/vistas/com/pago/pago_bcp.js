var edicion = {bcpEMailId:null};
var tabActivo = 1;
//1 -> tab de documentos
//2 -> tab de detalle de programacion de pago

$(document).ready(function () {
    criterioBusquedaDocumentos = [];
    cargarComponetentes();
    ax.setSuccess("onResponsePagoBCP");
    obtenerConfiguracionInicialListado();
    cambiarAnchoBusquedaDesplegable();
    $('#cboEstado').select2({
        minimumResultsForSearch: -1
    });
//    $('#cboMoneda').select2({
//        minimumResultsForSearch: -1
//    });
});

function onResponsePagoBCP(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicialListado':
                onResponseObtenerConfiguracionInicial(response.data);
                buscarBCPEmail();
                loaderClose();
                break;
            case 'nuevoReintentoDePago':
                loaderClose();
                buscarBCPEmail();
                break;
            case 'actualizarNumeroOperacion':
                loaderClose();
                buscarBCPEmail();
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

function buscarBCPEmail() {
    loaderShow();
    ax.setAccion("obtenerBCPEmail");
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
        "order": [[1, "desc"]],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": [
//Proveedor	S/N	M	Total	Fecha	F.Creación	F.Tentativa	Usuario	Estado	Acc.
            {"data": "id", "class": "alignCenter"},
            {"data": "fecha_creacion", "class": "alignCenter"},
            {"data": "asunto"},
            {"data": "extraccion_proveedor"},
            {"data": "extraccion_fechabd", "class": "alignCenter"},
            {"data": "extraccion_importebd", "class": "alignRight"},
            {"data": "extraccion_numero_operacion"},
            {"data": "transferencia"},
            {"data": "tipo_documento_descripcion"},
            {"data": "documento_serie_numero"},
            {"data": "log"},
            {"data": "estado", "class": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return  (row.estado == 1)? "<a onclick='nuevoReintentoDePago(" + row.id + ")'><i class='fa ion-refresh' style='color:blue;' title='Reintentar pagar'></i></a>&nbsp;":"";
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    var fecha = '';
                    if (!isEmpty(data)) {
                        fecha = formatearFechaBDCadena(data);
                    }
                    return fecha;
                },
                "targets": [1,4]
            },
            {
                "render": function (data, type, row) {
                    if (parseFloat(data).formatMoney(2, '.', ',') == '0.00') {
                        return '-';
                    } else {
                        return row.extraccion_moneda + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": 5
            },
            
            {
                "render": function (data, type, row) {
//                    if  (row.estado == 1) {
                        if (isEmpty(data)) data = "";
                        return data+" <a onclick='prepEditarNumeroOperacion(" + row.id + ", \"" + data + "\")'><i class='fa fa-edit' style='color:#E8BA2F;' title='Editar el Nro. Operación.'></i></a>&nbsp;";
//                    }else{
//                        return data;
//                    }
                },
                "targets": 6
            },
            {
                "render": function (data, type, row) {
                    var html = '';
                    switch (data * 1) {
                        case 1:
                            html = "Proceso pendiente";
                            break;
                        case 2:
                            html = "Eliminado";
                            break;
                        case 3:
                            html = "Proceso completado";
                            break;
                    }
                    return  html;
                },
                "targets": 11
            }
        ],
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            if (aData.estado == 3) {
                $(nRow).addClass("colorPP");
            }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();
}

function actualizarBusqueda() {
    buscarBCPEmail();
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
    var transferencia = $('#txtTransferencia').val();
//    var fechaEmision = {inicio: $('#inicioFechaEmision').val(),
//        fin: $('#finFechaEmision').val()};
//    var fechaBL = {inicio: $('#inicioFechaBL').val(),
//        fin: $('#finFechaBL').val()};
    var monedaId = select2.obtenerValor('cboMoneda');
    var estado = select2.obtenerValor('cboEstado');
    llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, transferencia, monedaId, estado);

    buscarBCPEmail();
}

var criterioBusquedaDocumentos = {};

function llenarParametrosBusqueda(personaId, documentoTipoIds, serie, numero, transferencia, monedaId, estado) {
    criterioBusquedaDocumentos = {};

    criterioBusquedaDocumentos.personaId = personaId;
    criterioBusquedaDocumentos.documentoTipoIds = documentoTipoIds;
    criterioBusquedaDocumentos.serie = serie;
    criterioBusquedaDocumentos.numero = numero;
    criterioBusquedaDocumentos.numero = transferencia;
//    criterioBusquedaDocumentos.fechaEmision = fechaEmision;
    criterioBusquedaDocumentos.monedaId = monedaId;
//    criterioBusquedaDocumentos.fechaBL = fechaBL;
    criterioBusquedaDocumentos.estado = estado;
}

var criterioBusquedaPPagoDetalle = {};


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

    buscarBCPEmail();
}

function buscarPorPersona(personaId) {
    llenarParametrosBusqueda(personaId, null, null, null, null, null, null);

    buscarBCPEmail();
}

function nuevoReintentoDePago(bcpEmailId) {
    ax.setAccion("nuevoReintentoDePago");
    ax.addParamTmp("bcpEmailId", bcpEmailId);
    ax.consumir();
}

function actualizarTabActivo(tab) {
    tabActivo = tab;
    actualizarBusqueda();
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

function prepEditarNumeroOperacion(bcpEMailId, numeroOperacion){
    edicion.bcpEMailId = bcpEMailId;
    if (!isEmpty(numeroOperacion)){
        document.getElementById("txtNumeroOperacion").value = numeroOperacion;
    }
    mostrarModalNumeroOperacion();
}
function cerrarModalNumeroOperacion(){
    $('#modalNumeroOperacion').modal('hide');
    $('.modal-backdrop').hide();
}
function mostrarModalNumeroOperacion(){
    $('#modalNumeroOperacion').modal({backdrop: 'static', keyboard: false});
    $('#modalNumeroOperacion').modal('show');
}
function actualizarNumeroOperacion(){
    ax.setAccion("actualizarNumeroOperacion");
    ax.addParamTmp("bcpEMailId", edicion.bcpEMailId);
    ax.addParamTmp("numeroOperacion", document.getElementById("txtNumeroOperacion").value);
    ax.consumir();
}