var dataDocumentoTipoDato;
var banderaBuscar = 0;
var estadoTolltip = 0;
var bandera_eliminar = false;
var bandera_aprobar = false;
var tipoInterfaz = document.getElementById("tipoInterfaz").value;

$(document).ready(function () {
    dataDocumentoTipoDato = [];
    $('[data-toggle="popover"]').popover({ html: true }).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponseOperacionListar")
    obtenerDocumentoTipo();
    iniciarDataPicker();
    cambiarAnchoBusquedaDesplegable();
    //obtenerSaldoCuentas();
    //alert("TESTING");
});

function onResponseOperacionListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipo':
                //                onResponseObtenerDocumentoTipo(response.data);
                onResponseObtenerDocumentoTipoDesplegable(response.data);
               // buscarDesplegable(1);
                //descomentar
                //                colapsarBuscador();
                // loaderClose();
                break;
            case 'imprimir':
                loaderClose();
                cargarDatosImprimir(response.data);
                break;
            case 'anular':
                loaderClose();
                //                habilitarBotonSweetGeneral();
                swal("Anulado!", "Documento anulado correctamente.", "success");
                bandera_eliminar = true;
                buscar();
                break;
            case 'visualizarDocumento':
                onResponseVisualizarDocumento(response.data);
                loaderClose();
                break;
            case 'aprobar':
                loaderClose();
                swal("Aprobado!", "Documento aprobado correctamente.", "success");
                bandera_aprobar = true;
                buscar();
                break;
            case 'exportarReporteExcel':
                loaderClose();
                console.log("data exportar", response.data);
                // location.href = URL_BASE + "util/formatos/Reporte_Operaciones.xlsx";                                
                break;
            case 'descargarFormato':
                loaderClose();
                location.href = URL_BASE + "util/formatos/formato_movimientos.xlsx";
                break;
            case 'importarExcelMovimiento':
                loaderClose();
                descargarExcel(response.data);
                //location.href = URL_BASE + "util/formatos/formato_movimientos.xlsx";                
                break;
            case 'obtenerDocumentosRelacionados':
                onResponseObtenerDocumentosRelacionados(response.data);
                // console.log("data", response.data);
                loaderClose();
                break;
            case 'enviarCorreoDetalleDocumento':
                $('#modalDetalleDocumento').modal('hide');
                $('.modal-backdrop').hide();
                loaderClose();
                break;
            case 'buscarCriteriosBusqueda':
                onResponseBuscarCriteriosBusqueda(response.data);
                loaderClose();
                break;
            case 'obtenerCuentaSaldoTodos':
                // console.log("data", response.data);
                onResponseObtenerCuentaSaldoTodos(response.data);
                // loaderClose();
                break;
            case 'getUserEmailByUserId':
                onResponseGetUserEmailByUserId(response.data);
                loaderClose();
                break;
            case 'obtenerSelectPersonas':
                onResponseObtenerPersonaActivoXStringBusqueda(response.data, response.tag);
                break;
        }
    }
    else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'imprimir':
                loaderClose();
                break;
            case 'anular':
                loaderClose();
                swal({ title: "Cancelado", text: response.message, type: "error", html: true });
                break;
            case 'aprobar':
                loaderClose();
                break;
            case 'visualizarDocumento':
                loaderClose();
                break;
            case 'importarExcelMovimiento':
                loaderClose();
                descargarExcel(response.data);
                //location.href = URL_BASE + "util/formatos/formato_movimientos.xlsx";
                break;
            case 'descargarFormato':
                loaderClose();
                break;
            case 'obtenerDocumentosRelacionados':
                loaderClose();
                break;
            case 'exportarReporteExcel':
                loaderClose();
                break;
            case 'enviarCorreoDetalleDocumento':
                loaderClose();
                break;
        }
    }
}

function descargarExcel(data) {
    if (data.vout_exito == '0') {
        location.href = URL_BASE + "util/formatos/formato_movimientos.xlsx";
    }
    $("#fileInfo").text("").show();
    buscar();
}

function obtenerDocumentoTipo() {
    ax.setAccion("obtenerDocumentoTipo");
    ax.consumir();
}

function onResponseObtenerDocumentoTipo(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        onResponseObtenerDocumentoTipoDato(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoLista(data.documento_tipo_dato_lista);
    }
}

function onResponseCargarDocumentotipoDatoLista(dataValor) {
    if (!isEmpty(dataValor)) {
        $.each(dataValor, function (index, item) {
            select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
        });
    }
}

function onResponseObtenerDocumentoTipoDato(data, personaActiva) {
    dataDocumentoTipoDato = data;
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas

        var columna = 1;
        $.each(data, function (index, item) {
            switch (columna) {
                case 1:
                    if (index > 0) {
                        appendForm('</div>');
                    }
                    appendForm('<div class="row">');
                    columna = 2;
                    break;
                case 2:
                    columna = 3;
                    break;
                case 3:
                    columna = 1;
                    break;
            }

            var html = '<div class="form-group col-md-4">' +
                '<label>' + item.descripcion + '</label>' +
                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            switch (parseInt(item.tipo)) {
                case 1:
                case 14:
                    html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="" maxlength="8"/>';
                    break;
                case 2:
                case 6:
                case 7:
                case 8:
                case 12:
                case 13:
                    html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="" maxlength="45"/>';
                    break;
                case 3:
                case 9:
                case 10:
                case 11:
                    html += '<div class="row">' +
                        '<div class="form-group col-md-6">' +
                        '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_inicio_' + item.id + '">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                        '</div></div>' +
                        '<div class="form-group col-md-6">' +
                        '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_fin_' + item.id + '">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                        '</div></div></div>';
                    break;
                case 4:
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                    break;
                case 5:
                    html += '<div id="div_persona"><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la persona</option>';
                    if (!isEmpty(personaActiva)) {
                        $.each(personaActiva, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    }
                    html += '</select></div>';
                    break;
            }
            html += '</div></div>';
            appendForm(html);
            switch (item.tipo) {
                case 4, "4":
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5, "5":
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
            }
        });
        appendForm('</div>');
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
    }
}

function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

function cargarDatoDeBusqueda() {
    $.each(dataDocumentoTipoDato, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 1:
            case 14:
                item["valor"] = $('#txt_' + item.id).val();
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
                item["valor"] = $('#txt_' + item.id).val();
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                var f = {
                    inicio: $('#datepicker_inicio_' + item.id).val(),
                    fin: $('#datepicker_fin_' + item.id).val()
                };
                item["valor"] = f;
                break;
            case 4:
            case 5:
                item["valor"] = $('#cbo_' + item.id).val();
                break;

        }
    });
}

function obtenerDatosBusqueda() {
    var valorPersona;

    tipoDocumento = $('#cboDocumentoTipo').val();
    var cadena = "";
    //    if (!isEmpty(tipoDocumento))
    //    {

    cargarDatoDeBusqueda();
    var valorTipoDocumento = obtenerValorTipoDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDato, function (index, item) {


        switch (parseInt(item.tipo)) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                if (!isEmpty(item.valor)) {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor + " ";
                    cadena += "<br>";
                }
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                if (!isEmpty(item.valor.inicio) || !isEmpty(item.valor.fin)) {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:

                if (!isEmpty(item.valor)) {
                    if (select2.obtenerText('cbo_' + item.id) !== null) {
                        cadena += negrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbo_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0) {
                    cadena += negrita(item.descripcion) + ": ";
                    valorPersona = select2.obtenerText('cbo_' + item.id);
                    cadena += valorPersona;
                    cadena += "<br>";
                }
                break;
        }
    });
    dataDocumentoTipoDato[0]['tipoDocumento'] = tipoDocumento;
    return cadena;
    //    }
    //    return 0;
}

function cerrarPopover() {
    if (banderaBuscar == 1) {
        if (estadoTolltip == 1) {
            $('[data-toggle="popover"]').popover('hide');
        }
        else {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltip = (estadoTolltip == 0) ? 1 : 0;
}

function obtenerValorTipoDocumento() {
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipo');
    if (valorTipoDocumento !== null) {
        var cadena = negrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";
}


function descargarFormato(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    cadena = (!isEmpty(cadena) && cadena !== 0) ? cadena : "Todos";

    $('#idPopover').attr("data-content", cadena);
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    //getDataTable();
    ax.setAccion("descargarFormato");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);
    //ax.addParamTmp("anio", 2015);
    //ax.addParamTmp("mes", 10);
    ax.consumir();

    if (colapsa === 1)
        colapsarBuscador();
}

function buscar(colapsa) {
    //loaderShow();
    getDataTable();
}
function exportarReporteExcel(colapsa) {
    loaderShow();
    //    var cadena;
    //    cadena = obtenerDatosBusqueda();
    //    cadena = (!isEmpty(cadena) && cadena !== 0) ? cadena : "Todos";
    //
    //    $('#idPopover').attr("data-content", cadena);
    //    $('[data-toggle="popover"]').popover('show');
    //    banderaBuscar = 1;
    //getDataTable();
    ax.setAccion("exportarReporteExcel");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);
    //ax.addParamTmp("anio", 2015);
    //ax.addParamTmp("mes", 10);
    ax.consumir();

    //    if (colapsa === 1)
    //        colapsarBuscador();
}
function getDataTable() {
    // debugger;
    var hoy = new Date();
    var dia = hoy.getDate();
    dia = (dia < 10) ? ('0' + dia) : dia;
    var mes = hoy.getMonth() + 1;
    mes = (mes < 10) ? ('0' + mes) : mes;
    var anio = hoy.getFullYear();
    var fechaActual = anio + "-" + mes + "-" + dia;

    //    console.log(dataDocumentoTipoDato);
    ax.setAccion("obtenerDocumentos");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);
    // console.log("criterios", dataDocumentoTipoDato);
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
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(true),
        "scrollX": true,
        "columns": [
            { "data": "icono_documento", "sClass": "alignCenter", "width": "40px" },
            { "data": "persona_nombre", "width": "350px" },
            { "data": "serie_numero", "width": "120px" },
            { "data": "moneda_descripcion", "width": "40px" },
            { "data": "total", "sClass": "alignRight", "width": "90px" },
            { "data": "fecha_emision_ord", "sClass": "alignCenter", "width": "90px" },
            { "data": "fecha_vencimiento_ord", "sClass": "alignCenter", "width": "90px" },
            { "data": "documento_descripcion", "width": "300px" },
            { "data": "fecha_creacion_ord", "sClass": "alignCenter", "width": "90px" },
            { "data": "usuario", "width": "110px" },
            { "data": "documento_estado_descripcion", "width": "90px" },
            { "data": "acciones", "width": "90px" }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    // console.log("data", data);
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 4
            },
            {
                "render": function (data, type, row) {
                    var fecha = row.fecha_creacion;
                    var muestraFecha = '';

                    if (fechaActual == data.substring(0, 10)) {
                        muestraFecha = fecha.substring(11, fecha.length);
                    } else {
                        muestraFecha = fecha.substring(0, 10);
                    }
                    return muestraFecha;
                },
                "targets": 8
            },
            {
                "render": function (data, type, row) {
                    return row.fecha_emision;
                },
                "targets": 5
            },
            {
                "render": function (data, type, row) {
                    return row.fecha_vencimiento;
                },
                "targets": 6
            },
            {
                "render": function (data) {
                    if (!isEmpty(data)) {
                        if (data.length > 28) {
                            data = data.substring(0, 25) + '...';
                        }
                    }
                    return data;
                },
                "targets": 1
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        "order": [[8, "desc"]],
        destroy: true
    });
    // loaderClose();
}
function nuevoForm() {
    //alert(tipoInterfaz);
    loaderShow();
    VALOR_ID_USUARIO = null;

    //if (tipoInterfaz == 2) {
    cargarDiv('#window', 'vistas/com/operacion/operacion_form_tablas.php');
    /*}else{
     cargarDiv('#window', 'vistas/com/movimiento/movimiento_form.php');
     }*/
}

function negrita(cadena) {
    return "<b>" + cadena + "</b>";
}
var actualizandoBusqueda = false;
function actualizarBusqueda() {
    //    actualizandoBusqueda = true;
    //    var estadobuscador = $('#bg-info').attr("aria-expanded");
    //    if (estadobuscador == "false")
    //    {
        buscarDesplegable(0);
    //    }
}
function actualizarBusquedaExcel() {
    //    actualizandoBusqueda = true;
    //    var estadobuscador = $('#bg-info').attr("aria-expanded");
    //    if (estadobuscador == "false")
    //    {
    exportarReporteExcel(0);
    //    }
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
function imprimirDocumento(id, documentoTipo) {
    loaderShow();
    ax.setAccion("imprimir");
    ax.addParamTmp("id", id);
    ax.addParamTmp("documento_tipo_id", documentoTipo);
    ax.consumir();
}

function anularDocumento(id) {
    confirmarAnularMovimiento(id);
}

function anular(id) {
    loaderShow();
    ax.setAccion("anular");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function visualizarDocumento(documentoId, movimientoId) {
    $('#txtCorreo').val('');
    loaderShow();
    ax.setAccion("visualizarDocumento");
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.consumir();

    //    
}

var dataVisualizarDocumento;
function onResponseVisualizarDocumento(data) {
    dataVisualizarDocumento = data;
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

            if (!isEmpty(valor)) {
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

function confirmarAnularMovimiento(id) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Anulara un documento, esta anulación no podra revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, anular!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            anular(id);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function aprobar(documentoId) {
    ax.setAccion("aprobar");
    ax.addParamTmp("id", documentoId);
    ax.consumir();
}

function confirmarAprobarMovimiento(id) {
    bandera_aprobar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Aprobar un documento, esta aprobación no podra revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, aprobar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            aprobar(id);
        } else {
            if (bandera_aprobar == false) {
                swal("Cancelado", "La aprobaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function aprobarDocumento(id) {
    confirmarAprobarMovimiento(id);
}

/*IMPORTAR EXCEL*/
$(function () {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
            //            $fileupload = $('#file');
            //            $fileupload.replaceWith($fileupload.clone(true));
        }
    });
});

function imageIsLoaded(e) {
    $('#secretFile').attr('value', e.target.result);
    enviarExcel();
}

function enviarExcel() {
    loaderShow();

    var documento = document.getElementById('secretFile').value;
    var docNombre = document.getElementById('file').value.slice(12);
    if (documento === '') {
        documento = null;
        docNombre = null;
    }

    //alert(documento);
    //alert(docNombre);
    importarExcelMovimiento(documento, docNombre);
}

function importarExcelMovimiento(documento, docNombre) {
    if (validarFormularioCarga(documento)) {
        //alert('Importar');
        ax.setAccion("importarExcelMovimiento");
        ax.addParamTmp("documento", documento);
        ax.addParamTmp("docNombre", docNombre);
        ax.consumir();
    } else {
        loaderClose();
    }
}

function validarFormularioCarga(documento) {
    var bandera = true;
    var espacio = /^\s+$/;

    if (documento === "" || documento === null || espacio.test(documento) || documento.length === 0) {
        $("#lblDoc").text("Documento es obligatorio").show();
        bandera = false;
    }
    return bandera;
}

/*FIN IMPORTAR EXCEL*/

function obtenerDocumentosRelacionados(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentosRelacionados");
    ax.addParamTmp("documento_id", documentoId);
    ax.consumir();
}

function onResponseObtenerDocumentosRelacionados(data) {
    $('#linkDocumentoRelacionado').empty();
    if (!isEmptyData(data)) {
        $('[data-toggle="popover"]').popover('hide');
        $.each(data, function (index, item) {
            $('#linkDocumentoRelacionado').append("<a onclick='visualizarDocumentoRelacionado(" + item.documento_relacionado_id + "," + item.movimiento_id + ")' style='color:#0000FF'>[" + item.documento_tipo + ": " + item.serie_numero + "]</a><br>");
        });
        $('#modalDocumentoRelacionado').modal('show');
    }

    else {
        mostrarAdvertencia("No se encontro ningun documento relacionado con el actual.");
    }

}

function visualizarDocumentoRelacionado(documentoId, movimientoId) {
    $('#modalDocumentoRelacionado').modal('hide');
    visualizarDocumento(documentoId, movimientoId);

}

function enviarCorreoDetalleDocumento() {
    //$('#txtCorreo').val('');
    var correo = $('#txtCorreo').val();
    correo = fixCorreo(correo);
    $('#txtCorreo').val(correo);
    var arr = correo.split(";");
    var valid = true;
    var boo = false;
    for (var i = 0; i < arr.length - 1; i++) {
        boo = validarEmail(arr[i]);
        if (boo) {
            //            console.log("VALIDO: "+arr[i]);
            valid = valid && boo;
        }
        else {
            //            console.log("INVALIDO: "+arr[i]);
            valid = valid && boo;
        }
    }


    if (!isEmpty(correo) && valid) {

        //    console.log(dataVisualizarDocumento);
        //    cargarDataDocumento(data.dataDocumento);
        //    cargarDataComentarioDocumento(data.comentarioDocumento);
        //    cargarDetalleDocumento(data.detalleDocumento);

        var nombreDocumentoTipo = '';
        var dataDocumento = '';

        // datos de documento
        if (!isEmpty(dataVisualizarDocumento.dataDocumento)) {

            nombreDocumentoTipo = dataVisualizarDocumento.dataDocumento[0]['nombre_documento'];

            // Mostraremos la data en filas de dos columnas
            $.each(dataVisualizarDocumento.dataDocumento, function (index, item) {
                var html = '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' + item.descripcion + ': </b>';

                var valor = quitarNULL(item.valor);

                if (!isEmpty(valor)) {
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
                    }
                }

                html += '' + valor + '';

                html += '</td></tr>';
                dataDocumento = dataDocumento + html;
            });
            dataDocumento = dataDocumento + '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' + 'Comentario' + ': </b>' + dataVisualizarDocumento.comentarioDocumento[0]['comentario_documemto'] + '</td></tr>';
            dataDocumento = dataDocumento + '<tr><td style=\'text-align:left;padding:0 55px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' + 'Descripción' + ': </b>' + dataVisualizarDocumento.comentarioDocumento[0]['descripcion_documemto'] + '</td></tr>';

        }

        // detalle de documento


        //    console.log(correo)
        //    console.log(nombreDocumentoTipo);
        //    console.log(dataDocumento);
        //    console.log(dataDetalle);   

        loaderShow('#modalDetalleDocumento');
        ax.setAccion("enviarCorreoDetalleDocumento");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("nombreDocumentoTipo", nombreDocumentoTipo);
        ax.addParamTmp("dataDocumento", dataDocumento);
        ax.consumir();
    }
    else {
        mostrarAdvertencia("Ingrese email válido.");
        return;
    }


}

//busqueda desplegable

$('.dropdown-menu').click(function (e) {
    //        console.log(e);
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

function iniciarDataPicker() {
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
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
}

function onResponseObtenerDocumentoTipoDesplegable(data) {
    //    console.log(data);
    // debugger;

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

    if (!isEmpty(data.documento_tipo)) {
        dibujarTiposDocumentos(data.documento_tipo);
        dibujarPersonasMayorOperacion(data.personasMayorOperacion);

        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        $("#cboPersona").select2({
            width: '100%'
        }).on("change", function (e) {
        });

        setTimeout(function () {
            $($("#cboPersona").data("select2").search).on('keyup', function (e) {
                if (e.keyCode === 13) {
                    obtenerDataCombo('Persona');
                }
            });
        }, 1000);
    }
}

function dibujarTiposDocumentos(documentoTipo) {
    //    console.log(documentoTipo);

    var html = '';
    html += '<a href="#" onclick="busquedaPorTexto(5,' + null + ',' + null + ')" class="list-group-item">';
    html += '<span class="fa fa-circle text-pink pull-right" style="color: #D8D8D8;"></span>Todos';
    html += '</a>';

    $('#divDocumentoTipos').empty();
    $.each(documentoTipo, function (index, item) {
        html += '<a href="#" onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" class="list-group-item">';
        html += '<span class="' + item.icono + '"></span>' + item.descripcion;
        html += '</a>';
    });

    $("#divDocumentoTipos").append(html);
}

function dibujarPersonasMayorOperacion(personas) {
    var html = '';

    $('#divPersonasMayorMovimientos').empty();
    if (!isEmpty(personas)) {
        $.each(personas, function (index, item) {
            html += '<a href="#" class="list-group-item" onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >';
            html += '<span class="badge bg-info">' + item.veces + '</span>' + item.nombre;
            html += '</a>';
        });
    }

    $("#divPersonasMayorMovimientos").append(html);
}

function buscarDesplegable() {
    dataDocumentoTipoDato = [];

    var personaId = select2.obtenerValor('cboPersona');
    var tipoDocumentoIds = $('#cboDocumentoTipo').val();
    ;
    var serie = $('#txtSerie').val();
    var numero = $('#txtNumero').val();
    var fechaEmision = {
        inicio: $('#inicioFechaEmision').val(),
        fin: $('#finFechaEmision').val()
    };
    //    console.log(tipoDocumentoIds);
    llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision);

}

function llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision) {
    dataDocumentoTipoDato = [];
    
    if (!(isEmpty(personaId) && isEmpty(tipoDocumentoIds) && isEmpty(serie) && isEmpty(numero) && isEmpty(fechaEmision))) { 
        dataDocumentoTipoDato.push({ descripcion: "Persona", tipo: "5", valor: personaId, tipoDocumento: tipoDocumentoIds });
        dataDocumentoTipoDato.push({ descripcion: "Serie", tipo: "7", valor: serie });
        dataDocumentoTipoDato.push({ descripcion: "Numero", tipo: "8", valor: numero });
        dataDocumentoTipoDato.push({ descripcion: "Fecha de emision", tipo: "9", valor: fechaEmision });
    }
    dataDocumentoTipoDato.push({ descripcion: "Agencia", valor: select2.obtenerValor("cboAgencia") });
    dataDocumentoTipoDato.push({ descripcion: "Caja", valor: select2.obtenerValor("cboCaja") });

    //loaderShow();
    getDataTable();
}

function busquedaPorTexto(tipo, texto, tipoDocumento) {
    
    var tipoDocumentoIds = [];
    if (!isEmpty(tipoDocumento)) {
        tipoDocumentoIds.push(tipoDocumento);
    }

    if (tipo == 5) {
        llenarParametrosBusqueda(texto, tipoDocumentoIds, null, null, null);
    }

}

function buscarCriteriosBusqueda() {
    //    loaderShow();
    ax.setAccion("buscarCriteriosBusqueda");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.consumir();
}

function onResponseBuscarCriteriosBusqueda(data) {
    var dataPersona = data.dataPersona;
    var dataDocumentoTipo = data.dataDocumentoTipo;
    var dataSerieNumero = data.dataSerieNumero;

    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >';
            html += '<span class="col-md-1"><i class="ion-person"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.codigo_identificacion + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.nombre + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }

    if (!isEmpty(dataDocumentoTipo)) {
        $.each(dataDocumentoTipo, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" >';
            html += '<span class="col-md-1"><i class="fa fa-files-o"></i></span>';
            html += '<span class="col-md-11">';
            html += '<label style="color: #141719;">' + item.descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }


    if (!isEmpty(dataSerieNumero)) {
        $.each(dataSerieNumero, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorSerieNumero(\'' + item.serie + '\',\'' + item.numero + '\')" >';
            html += '<span class="col-md-1"><i class="ion-document"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.serie_numero + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.documento_tipo_descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }

    $("#ulBuscadorDesplegable2").append(html);


    //    console.log(dataPersona);
}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusqueda(null, null, serie, numero, null);
}

function obtenerSaldoCuentas() {

    //ejemplo Pie charts
    //    $('.sparkpie').sparkline([3, 4, 1, 2], {
    //        type: 'pie',
    //        width: '100%',
    //        height: '32',
    //        sliceColors: ['#1a2942', '#f13c6e', '#3bc0c3', '#dcdcdc'],
    //        offset: 0,
    //        borderWidth: 0,
    //        borderColor: '#00007f'
    //    });    

    //loaderShow();
    ax.setAccion("obtenerCuentaSaldoTodos");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();

}

function onResponseObtenerCuentaSaldoTodos(data) {
    //    console.log(data);

    if (!isEmpty(data)) {
        var arrayTotal = [];
        var arrayColor = [];
        var html = '';
        var numero = '';

        $('#leyendaDivCuenta').empty();

        $.each(data, function (index, item) {
            arrayTotal.push(item.total);
            arrayColor.push(item.color);

            numero = '';
            if (!isEmpty(item.numero)) {
                numero = '<br>N°: ' + item.numero;
            }

            //            html += '<span class="fa fa-circle" style="color: '+item.color+';"></span> '+item.cuenta_descripcion+' &nbsp;&nbsp;&nbsp;';
            html += '<div class="col-md-4"  style="padding-left: 0px;"><div class="alert alert-success" style="background-color: ' + item.color + ';color: white;padding-top: 5px;padding-bottom: 5px;margin-bottom: 0px;">' + //margin-right: 5px
                item.cuenta_descripcion + numero + '<br> S/. ' + redondearNumero(item.total).toFixed(2) +
                '</div></div>';

        });

        $("#leyendaDivCuenta").append(html);

        //        console.log(arrayColor,arrayTotal);
        dibujarGraficoCuentaSaldo(arrayTotal, arrayColor);
    }


}

function dibujarGraficoCuentaSaldo(total, color) {
    $('.sparkpie').sparkline(total, {
        type: 'pie',
        width: '100%',
        height: '62px',
        sliceColors: color,
        offset: 0,
        borderWidth: 0,
        borderColor: '#00007f'
    });
}


function onResponseGetUserEmailByUserId(data) {

    appendCurrentUserEmail(data);
}

function getUserEmailByUserId() {
    ax.setAccion("getUserEmailByUserId");
    ax.consumir();

}

function appendCurrentUserEmail(data) {
    //console.log(data[0]['email']);
    var fetchEmail;
    var arr;
    var correo;
    var newCorreo = "";


    if (!isEmpty(data)) {
        fetchEmail = data[0]['email'];
    }

    //if(fetchEmail!='EMAIL_INVALIDO'){notificacion};
    if ($("#checkIncluirSelf").is(':checked')) {
        $('#txtCorreo').val(function (i, val) {
            return val + fetchEmail + (val ? ';' : '');
        });


        correo = $('#txtCorreo').val();
        correo = fixCorreo(correo);
        $('#txtCorreo').val(correo);

    } else {

        correo = $('#txtCorreo').val();
        arr = correo.split(";");

        for (var i = 0; i < arr.length; i++) {
            if (arr[i] === fetchEmail) {
                //alert("adsa: "+arr[i]);
                arr.splice(i, 1);
            } else {
                newCorreo += arr[i] + ";";

            }
        }
        newCorreo = fixCorreo(newCorreo);
        $('#txtCorreo').val(newCorreo);
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
    }
    else {
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

function obtenerDataCombo(id) {
    let texto = $($("#cbo" + id).data("select2").search).val();


    if (isEmpty(texto)) {
        //        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese un texto para buscar");
        return;
    }

    $('#cbo' + id).select2('close');

    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", texto);
    ax.setTag({ id: id, valor: texto });
    ax.consumir();
}

function onResponseObtenerPersonaActivoXStringBusqueda(data, dataCombo) {

    select2.cargarSeleccione("cbo" + dataCombo.id, data, "id", ["nombre", "codigo_identificacion"], "Seleccione un cliente");

    loaderClose();
    if (!isEmpty(dataCombo.valor)) {
        $('#cbo' + dataCombo.id).select2('open');
        $($("#cbo" + dataCombo.id).data("select2").search).val(dataCombo.valor);
        $($("#cbo" + dataCombo.id).data("select2").search).trigger('input');
        setTimeout(function () {
            $('.select2-results__option').trigger("mouseup");
        }, 500);
    }
    if (!isEmpty(dataCombo.personaId)) {
        select2.asignarValor("cbo" + dataCombo.id, dataCombo.personaId);
        if (personaIdRegistro == dataCombo.personaId) {
            personaIdRegistro = null;
        }
    }

    setTimeout(function () {
        $($("#cbo" + dataCombo.id).data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13) {
                obtenerDataCombo(dataCombo.id);
            }
        });
    }, 1000);
}


function onChangeCboAgencia(valor, cajaId = null) {
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
        buscarDesplegable();
    }
}

function onChangeCboCaja(valor) {
    buscarDesplegable();
}