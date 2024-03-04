var dataDocumentoTipoDato;
var banderaBuscar = 0;
var estadoTolltip = 0;
var bandera_eliminar = false;
var bandera_aprobar = false;
var tipoInterfaz = document.getElementById("tipoInterfaz").value;
var currentUserEmail;

$('.gang-name-1').click(function(){
    if($(this).hasClass("collapsed")){
        $(this).nextUntil('tr.gang-name-1')
            .find('td')
            .parent()
            .find('td > div')
            .slideDown("fast", function(){
                var $set = $(this);
                $set.replaceWith($set.contents());
            });
        $(this).removeClass("collapsed");
    } else {
        $(this).nextUntil('tr.gang-name-1')
            .find('td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('td > div')
            .slideUp("fast");
        $(this).addClass("collapsed");
    }
});
$('.gang-name-2').click(function(){
    if($(this).hasClass("collapsed")){
        $(this).nextUntil('tr.gang-name-2')
            .find('td')
            .parent()
            .find('td > div')
            .slideDown("fast", function(){
                var $set = $(this);
                $set.replaceWith($set.contents());
            });
        $(this).removeClass("collapsed");
    } else {
        $(this).nextUntil('tr.gang-name-2')
            .find('td')
            .wrapInner('<div style="display: block;" />')
            .parent()
            .find('td > div')
            .slideUp("fast");
        $(this).addClass("collapsed");
    }
});


$(document).ready(function () {
    dataDocumentoTipoDato = [];
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponseMovimientoListar");
//    obtenerMovimientoTipoColumnaLista();
    obtenerDocumentoTipo();
    iniciarDataPicker();
    cambiarAnchoBusquedaDesplegable();
});

/**
 *
 * @param response
 * @param response.data
 * @param response.data.columna
 */
function onResponseMovimientoListar(response) {
    //breakFunction();
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipo':
                onResponseObtenerMovimientoTipoColumnaLista(response.data.columna);
                buscar(1);
                onResponseObtenerDocumentoTipoDesplegable(response.data);
                dibujarLeyendaAcciones(response.data.acciones);
                loaderClose();
                break;
            case 'imprimir':
                loaderClose();
                if (!isEmpty(response.data.dataDocumento)) {
                    cargarDatosImprimir(response.data);
                } else {
                    abrirDocumentoPDF(response.data);
                }
                break;
            case 'anular':
                loaderClose();
//                habilitarBotonSweetGeneral();
                swal("Anulado!", "Documento anulado correctamente.", "success");
                bandera_eliminar = true;
                buscar();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                onResponseObtenerDocumentoRelacionVisualizar(response.data);
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
                location.href = URL_BASE + "util/formatos/Reporte_Movimientos.xlsx";
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
                loaderClose();
                break;
            case 'enviarCorreoDetalleDocumento':
                $('#modalDetalleDocumento').modal('hide');
                $('.modal-backdrop').hide();
                loaderClose();
                break;
            case 'editarComentarioDocumento':
                exitoCrear(response.data);
                loaderClose();
                break;
            case 'buscarCriteriosBusqueda':
                onResponseBuscarCriteriosBusqueda(response.data);
                loaderClose();
                break;
            case 'guardarEdicionDocumento':
                exitoCrear(response.data);
                $('#modalDetalleDocumento').modal('hide');
                $('.modal-backdrop').hide();
                buscar();
                loaderClose();
                break;
            case 'obtenerPersonaDireccion':
                if (personaDireccionId !== 0) {
                    onResponseObtenerDataCbo("_" + personaDireccionId, "id", "direccion", response.data);
                }
                if (textoDireccionId !== 0) {
                    onResponseObtenerPersonaDireccionTexto(response.data);
                }
                break;
            case 'enviarMovimientoEmailPDF':
                if (!isEmpty(response.data[0]['id'])) {
                    $('#modalDetalleDocumento').modal('hide');
                    $('.modal-backdrop').hide();
                }
                loaderClose();
                break;
                //COLUMNAS DEL LISTADO DINAMICO
            case 'obtenerMovimientoTipoColumnaLista':
                onResponseObtenerMovimientoTipoColumnaLista(response.data);
                loaderClose();
                break;
            case 'obtenerEmailsXAccion':
                onResponseObtenerEmailsXAccion(response.data);
                loaderClose();
                break;
            case 'enviarCorreoXAccion':
                $('#modalDetalleDocumento').modal('hide');
                $('.modal-backdrop').hide();
                loaderClose();
                break;
            case 'getUserEmailByUserId':
                onResponseGetUserEmailByUserId(response.data);
                loaderClose();
                break;
            case 'obtenerReporteDocumentosAsignaciones':
                onResponseObtenerReporteDocumentosAsignaciones(response.data);
                loaderClose();
                break;
            case 'generarBienUnicoXDocumentoId':
                onResponseGenerarBienUnicoXDocumentoId(response.data);
                loaderClose();
                break;
            case 'anularBienUnicoXDocumentoId':
                loaderClose();
                if (response.data[0].vout_exito == 1)
                {
                    swal("Anulado!", response.data[0].vout_mensaje, "success");
                    buscar();
                } else
                {
                    swal("Cancelado!", response.data[0].vout_mensaje, "error");
                }
                break;
            case 'obtenerBienUnicoConfiguracionInicial':
                onResponseObtenerBienUnicoConfiguracionInicial(response.data);
                loaderClose();
                break;
            case 'guardarBienUnicoDetalle'  :                
                onResponseGuardarBienUnicoDetalle(response.data);
                loaderClose();
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
                swal({title: "Cancelado",text: response.message,type: "error",html: true});                
                break;
            case 'aprobar':
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
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
            case 'enviarCorreoDetalleDocumento':
                loaderClose();
                break;
            case 'enviarMovimientoEmailPDF':
                loaderClose();
                break;
            case 'generarBienUnicoXDocumentoId':
                loaderClose();
                swal("Cancelado", response.message, "error");
                break;
            case 'guardarBienUnicoDetalle'  :            
                loaderClose();                    
                swalMostrarSoloConfirmacion('error','Cancelado!',response.message,'mostrarModalAsignacionCU()');
                break;
        }
    }
}

function mostrarModalAsignacionCU(){
    $('#modalAsignarCodigoUnico').modal('show')
}

function swalMostrarSoloConfirmacion(tipo,titulo,mensaje,funcion){
    swal({
            title: titulo,
            text: mensaje,
            type: tipo,
            showCancelButton: false,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Ok",
            cancelButtonColor: '#d33',
            cancelButtonText: "No, cancelar !",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                eval(funcion);
            }
        });
}

function onResponseGetUserEmailByUserId(data)
{
    appendCurrentUserEmail(data);
}

/**
 *
 * @param data
 * @param data.vout_exito
 */
function descargarExcel(data)
{
    if (data.vout_exito == '0') {
        location.href = URL_BASE + "util/formatos/formato_movimientos.xlsx";
    }
    $("#fileInfo").text("").show();
    buscar();
}

function obtenerDocumentoTipo()
{
    ax.setAccion("obtenerDocumentoTipo");
    ax.consumir();
}
/**
 *
 * @param data
 * @param data.documento_tipo
 * @param data.documento_tipo_dato
 * @param data.documento_tipo_dato_lista
 * @param data.persona_activa
 */
function onResponseObtenerDocumentoTipo(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        onResponseObtenerDocumentoTipoDato(data.documento_tipo_dato, data.persona_activa);
        onResponseCargarDocumentotipoDatoLista(data.documento_tipo_dato_lista);
    }
}

function onResponseCargarDocumentotipoDatoLista(dataValor)
{
    if (!isEmpty(dataValor))
    {
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
                    if (!isEmpty(personaActiva))
                    {
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

function cargarDatoDeBusqueda()
{
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
                var f = {inicio: $('#datepicker_inicio_' + item.id).val(),
                    fin: $('#datepicker_fin_' + item.id).val()};
                item["valor"] = f;
                break;
            case 4:
            case 5:
                item["valor"] = $('#cbo_' + item.id).val();
                break;

        }
    });
}

function obtenerDatosBusqueda()
{
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
                if (!isEmpty(item.valor))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor + " ";
                    cadena += "<br>";
                }
                break;
            case 3:
            case 9:
            case 10:
            case 11:
                if (!isEmpty(item.valor.inicio) || !isEmpty(item.valor.fin))
                {
                    cadena += negrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:

                if (!isEmpty(item.valor))
                {
                    if (select2.obtenerText('cbo_' + item.id) !== null)
                    {
                        cadena += negrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbo_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0)
                {
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

function cerrarPopover()
{
    if (banderaBuscar == 1)

    {
        if (estadoTolltip == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        }
        else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else
    {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltip = (estadoTolltip == 0) ? 1 : 0;
}

function obtenerValorTipoDocumento()
{
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipo');
    if (valorTipoDocumento !== null)
    {
        var cadena = negrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";
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

function descargarFormato(colapsa) {
    loaderShow();
//    var cadena;
//    cadena = obtenerDatosBusqueda();
//    cadena = (!isEmpty(cadena) && cadena !== 0) ? cadena : "Todos";
//
//    $('#idPopover').attr("data-content", cadena);
//    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    //getDataTable();
    ax.setAccion("descargarFormato");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);
    //ax.addParamTmp("anio", 2015);
    //ax.addParamTmp("mes", 10);
    ax.consumir();

//    if (colapsa === 1)
//        colapsarBuscador();
}

function buscar(colapsa)
{
    loaderShow();
    getDataTable();
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

function getDataTable() {


    ax.setAccion("obtenerDocumentos");
    ax.addParamTmp("criterios", dataDocumentoTipoDato);

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
        "order": [[indexFechaCreacion, "desc"]],
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "columns": detalleColumna,
        "columnDefs": detalleColumnaDefs,
        /*"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull){

            console.log(iDisplayIndex);
            // var $th = $td.closest('table').find('th').eq($td.index());
            $('td:eq(7)', nRow).html( "<a href='#' onclick='abrirModalReporteAtenciones()' >"+aData['documento_estado_negocio_descripcion']+"</a>" );
        },*/
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        //"aoColumnDefs":[
        //    {
        //        "aTargets":[2],
        //        "mRender": function(data, type, full){
        //            return data;
        //            //return '<a href="google.com/'+data+">'";
        //        }
        //    }
        //],
        destroy: true
    });
    loaderClose();
}

function nuevoForm()
{
    //alert(tipoInterfaz);
    loaderShow();
    VALOR_ID_USUARIO = null;
    cargarDiv('#window', 'vistas/com/contrato/contrato_form.php');    
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}
var actualizandoBusqueda = false;
function actualizarBusqueda()
{
    buscar(0);
}
function actualizarBusquedaExcel()
{
    exportarReporteExcel(0);
}
function colapsarBuscador() {
    if (actualizandoBusqueda) {
        actualizandoBusqueda = false;
        return;
    }
    var bgInfo = $('#bg-info');
    if (bgInfo.hasClass('in')) {
        bgInfo.attr('aria-expanded', "false");
        bgInfo.attr('height', "0px");
        bgInfo.removeClass('in');
    } else {
        bgInfo.attr('aria-expanded', "false");
        bgInfo.removeAttr('height', "0px");
        bgInfo.addClass('in');
    }
}
function imprimirDocumento(id, documentoTipo)
{               
    loaderShow();
    ax.setAccion("imprimir");
    ax.addParamTmp("id", id);
    ax.addParamTmp("documento_tipo_id", documentoTipo);
    ax.consumir();
}

function anularDocumento(id)
{
    confirmarAnularMovimiento(id);
}

function anular(id)
{
    loaderShow();
    ax.setAccion("anular");
    ax.addParamTmp("id", id);
    ax.consumir();
}

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

//    
}

var dataVisualizarDocumento;
/**
 * @param data          Trae la data Para visualizacion del documento.
 * @param data.dataDocumento   Info.
 * @param data.configuracionEditable Info.
 * @param data.comentarioDocumento Info.
 * @param data.detalleDocumento Info.
 * @param data.dataMovimientoTipoColumna Info.
 * @param data.dataAccionEnvio Info.
 */
function onResponseObtenerDocumentoRelacionVisualizar(data)
{

    resultadoObtenerEmails = null;
    dataVisualizarDocumento = data;
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento, data.configuracionEditable,data.dataDocumentoAdjunto);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna);
    dibujarTipoEnvioEmail(data.dataAccionEnvio);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}


var camposDinamicos = [];
var textoDireccionId = 0;
var personaDireccionId = 0;
var guardarEdicionDocumento = false;
/**
 * @param data          Trae la data del documento.
 * @param data.edicion_habilitar   Information about the object's members.
 * @param data.documento_tipo_dato_id Info.
 * @param data.item.documento_tipo_id Info.
 * @param data.numero_defecto Info.
 * @param data.codigo_identificacion Info.
 * @param data.descripcion_numero Info.
 * @param data.valor_id Info.
 * @param data.codigo Info.
 * @param data. Info.
 */
function cargarDataDocumento(data, configuracionEditable,dataDocumentoAdjunto)
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
                            valor = formatearNumero(valor);
                            break;
                        case 27:
                            if(!isEmpty(dataDocumentoAdjunto)){
                                valor='<a style="color: blue;" href="util/uploads/documentoAdjunto/'+dataDocumentoAdjunto[0]['nombre']+'" download="'+dataDocumentoAdjunto[0]['archivo']+'">'+dataDocumentoAdjunto[0]['archivo']+'</a>';
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

function obtenerPersonaDireccion(personaId) {
//    alert(personaId);    
    if (personaDireccionId !== 0 || textoDireccionId !== 0) {
        ax.setAccion("obtenerPersonaDireccion");
        ax.addParamTmp("personaId", personaId);
        ax.consumir();
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

/**
 *
 * @param data
 * @param dataMovimientoTipoColumna
 * @param data.unidadMedida
 */

var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna) {
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
            html += "<td>" + item.descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.precioUnitario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    }
    else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function fechaArmada(valor)
{
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
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            anular(id);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function aprobar(documentoId)
{
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
            if (bandera_aprobar == false)
            {
                swal("Cancelado", "La aprobaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function aprobarDocumento(id)
{
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

function obtenerDocumentosRelacionados(documentoId)
{
    loaderShow();
    ax.setAccion("obtenerDocumentosRelacionados");
    ax.addParamTmp("documento_id", documentoId);
    ax.consumir();
}

/**
 *
 * @param data
 * @param data.documento_relacionado_id
 * @param data.movimiento_id
 */
function onResponseObtenerDocumentosRelacionados(data)
{
    $('#linkDocumentoRelacionado').empty();
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        $.each(data, function (index, item) {
            $('#linkDocumentoRelacionado').append("<a onclick='visualizarDocumentoRelacion(" + item.documento_relacionado_id + "," + item.movimiento_id + ")' style='color:#0000FF'>[" + item.documento_tipo + ": " + item.serie_numero + "]</a><br>");
        });
        $('#modalDocumentoRelacionado').modal('show');
    }

    else
    {
        mostrarAdvertencia("No se encontro ningun documento relacionado con el actual.");
    }

}



function visualizarDocumentoRelacion(documentoId, movimientoId)
{
    $('#modalDocumentoRelacionado').modal('hide');
    visualizarDocumento(documentoId, movimientoId);

}

function enviarCorreoDetalleDocumento() {

    var correo = $('#txtCorreo').val();
    correo = fixCorreo(correo);
    $('#txtCorreo').val(correo);
    var arr = correo.split(";");
    var valid = true;
    var boo = false;
    for (var i = 0; i < arr.length - 1; i++)
    {
        boo = validarEmail(arr[i]);
        if (boo)
        {
            valid = valid && boo;
        }
        else {
            valid = valid && boo;
        }
    }


    if (!isEmpty(correo) && valid) {
        loaderShow('#modalDetalleDocumento');
        ax.setAccion("enviarCorreoDetalleDocumento");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("documentoId", docId);
        ax.addParamTmp("comentarioDocumento", $('#txtComentario').val());
        ax.consumir();

    } else
    {
        mostrarAdvertencia("Ingrese email válido.");
        return;
    }



}

function editarComentarioDocumento() {
    loaderShow('#modalDetalleDocumento');
    //Validar y obtener valores de los campos dinamicos

    if (guardarEdicionDocumento) {
        if (!obtenerValoresCamposDinamicos())
            return;
    }


    ax.setAccion("guardarEdicionDocumento");
//    ax.setAccion("editarComentarioDocumento");
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("documentoId", docId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.consumir();
}

function exitoCrear(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        $.Notification.autoHideNotify('success', 'top-right', 'Éxito', data[0]["vout_mensaje"]);
//        cargarPantallaListar();
    }
}

function validarCorreo() {
    //$('#txtCorreo').val('');
    var correo = $('#txtCorreo').val();
    var pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;


    if (!isEmpty(correo) && pattern.test(correo)) {
        loaderShow('#modalDetalleDocumento');
        ax.setAccion("enviarMovimientoEmailPDF");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("documentoId", docId);
        ax.addParamTmp("comentario", $('#txtComentario').val());
        ax.consumir();
    } else {
        mostrarAdvertencia("Ingrese email");
        return;
    }

}

function validarCorreoMasPDF() {
    var correo = $('#txtCorreo').val();
    correo = fixCorreo(correo);
    $('#txtCorreo').val(correo);
    var arr = correo.split(";");
    var valid = true;
    var boo = false;
    for (var i = 0; i < arr.length - 1; i++)
    {
        boo = validarEmail(arr[i]);
        if (boo)
        {
            valid = valid && boo;
        }
        else {
            valid = valid && boo;
        }
    }

    if (!isEmpty(correo) && valid) {
        loaderShow('#modalDetalleDocumento');
        ax.setAccion("enviarMovimientoEmailCorreoMasPDF");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("documentoId", docId);
        ax.addParamTmp("comentario", $('#txtComentario').val());
        ax.consumir();
    } else {
        mostrarAdvertencia("Ingrese email");
        return;

    }

}


$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa" && e.delegateTarget.id != "ulObtenerEmail") {
        e.stopPropagation();
    }
});

var contadorClick = 1;
$('#txtBuscar').keyup(function (e) {
    var bAbierto = $('#txtBuscar').attr("aria-expanded");

    if (!eval(bAbierto)) {
        $('#txtBuscar').dropdown('toggle');
    }

});


function iniciarDataPicker()
{
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}
/**
 *
 * @param data
 * @param data.documento_tipo
 * @param data.personasMayorMovimientos
 * @param data.persona_activa
 * @param data.moneda
 */
function onResponseObtenerDocumentoTipoDesplegable(data) {    
    if (!isEmpty(data.documento_tipo)) {
        dibujarTiposDocumentos(data.documento_tipo);
        dibujarPersonasMayorMovimiento(data.personasMayorMovimientos);

        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.cargar("cboPersona", data.persona_activa, "id", ["nombre", "codigo_identificacion"]);
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.cargar("cboEstadoNegocio", data.estadoNegocioPago, "codigo", ["codigo", "descripcion"]);
        
    }
}

//here
function buscarDesplegable()
{
    dataDocumentoTipoDato = [];

    var personaId = select2.obtenerValor('cboPersona');
    var tipoDocumentoIds = $('#cboDocumentoTipo').val();
    ;
    var serie = $('#txtSerie').val();
    var numero = $('#txtNumero').val();
    var fechaEmision = {inicio: $('#inicioFechaEmision').val(),
        fin: $('#finFechaEmision').val()};
    var monedaId = select2.obtenerValor('cboMoneda');
    var estadoNegocio = select2.obtenerValor('cboEstadoNegocio');
    llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision, monedaId,estadoNegocio);

}

function llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision, monedaId,estadoNegocio) {
    dataDocumentoTipoDato = [];

    if (isEmpty(personaId) && isEmpty(tipoDocumentoIds) && isEmpty(serie) && isEmpty(numero) && isEmpty(fechaEmision)) {
        dataDocumentoTipoDato = [];
    } else {
        dataDocumentoTipoDato.push({descripcion: "Persona", tipo: "5", valor: personaId, tipoDocumento: tipoDocumentoIds, monedaId: monedaId,estadoNegocio:estadoNegocio});
        dataDocumentoTipoDato.push({descripcion: "Serie", tipo: "7", valor: serie});
        dataDocumentoTipoDato.push({descripcion: "Numero", tipo: "8", valor: numero});
        dataDocumentoTipoDato.push({descripcion: "Fecha de emision", tipo: "9", valor: fechaEmision});
    }

    loaderShow();
    getDataTable();
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

//here
function buscarCriteriosBusqueda() {
//    loaderShow();
    ax.setAccion("buscarCriteriosBusqueda");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.consumir();
}
/**
 *
 * @param data
 * @param data.dataPersona
 * @param data.dataDocumentoTipo
 * @param data.dataSerieNumero
 * @param data.documento_tipo_descripcion
 */
function onResponseBuscarCriteriosBusqueda(data) {
    var dataPersona = data.dataPersona;
    var dataDocumentoTipo = data.dataDocumentoTipo;
    var dataSerieNumero = data.dataSerieNumero;

    var html = '';
    var ulBuscadorDesplegable2 = $('#ulBuscadorDesplegable2');
    ulBuscadorDesplegable2.empty();
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

    ulBuscadorDesplegable2.append(html);


}

function busquedaPorTexto(tipo, texto, tipoDocumento) {

    var tipoDocumentoIds = [];
    if (!isEmpty(tipoDocumento)) {
        tipoDocumentoIds.push(tipoDocumento);
    }

    if (tipo == 5) {
        llenarParametrosBusqueda(texto, tipoDocumentoIds, null, null, null, null,null);
    }

}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusqueda(null, null, serie, numero, null, null,null);
}

function dibujarTiposDocumentos(documentoTipo) {

    var html = '';
    html += '<a href="#" onclick="busquedaPorTexto(5,' + null + ',' + null + ')" class="list-group-item">';
    html += '<span class="fa fa-circle text-pink pull-right" style="color: #D8D8D8;"></span>Todos';
    html += '</a>';
    var divDocumentoTipos = $('#divDocumentoTipos');
    divDocumentoTipos.empty();
    $.each(documentoTipo, function (index, item) {
        html += '<a href="#" onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" class="list-group-item">';
        html += '<span class="' + item.icono + '"></span>' + item.descripcion;
        html += '</a>';
    });

    divDocumentoTipos.append(html);
}

/**
 *
 * @param personas
 * @param personas.veces
 */
function dibujarPersonasMayorMovimiento(personas) {
    var html = '';
    var divPersonasMayorMovimientos = $('#divPersonasMayorMovimientos');
    divPersonasMayorMovimientos.empty();
    if (!isEmpty(personas)) {
        $.each(personas, function (index, item) {
            html += '<a href="#" class="list-group-item" onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >';
            html += '<span class="badge bg-info">' + item.veces + '</span>' + item.nombre;
            html += '</a>';
        });
    }

    divPersonasMayorMovimientos.append(html);
}

function guardarEdicion() {
    loaderShow('#modalDetalleDocumento');

    //Validar y obtener valores de los campos dinamicos
    if (!obtenerValoresCamposDinamicos())
        return;


    ax.setAccion("guardarEdicionDocumento");
    ax.addParamTmp("documentoId", docId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.consumir();
}

function obtenerValoresCamposDinamicos() {
    var isOk = true;
    if (isEmpty(camposDinamicos))
        return false;
    $.each(camposDinamicos, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 14:
            case 15:
            case 16:
            case 19:
                var numero = document.getElementById("txt_" + item.id).value;
                if (isEmpty(numero)) {
                    if (item.opcional == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                } else {
                    if (!esNumero(numero)) {
                        mostrarValidacionLoaderClose("Debe ingresar un número válido para " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                camposDinamicos[index]["valor"] = numero;
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
                camposDinamicos[index]["valor"] = document.getElementById("txt_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
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
                camposDinamicos[index]["valor"] = document.getElementById("datepicker_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
            case 18:// direccion persona
            case 20:// cuenta
            case 21:// actividad
            case 22:// retencion detraccion
            case 23:// otra persona
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 17:// organizador
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                            camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;

        }
    });
    return isOk;
}

function onResponseObtenerDataCbo(cboId, itemId, itemDes, data) {

    document.getElementById('cbo' + cboId).innerHTML = "";

    select2.asignarValor('cbo' + cboId, "");
    //$('#cbo' + cboId).append('<option value=0>Seleccione la dirección</option>');
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            $('#cbo' + cboId).append('<option value="' + item[itemId] + '">' + item[itemDes] + '</option>');
        });

        select2.asignarValor('cbo' + cboId, data[0]["id"]);

//        $("#cbo" + cboId).select2({
//            width: '100%'
//        });
    } else {
        select2.asignarValor('cbo' + cboId, 0);
    }
}

function onResponseObtenerPersonaDireccionTexto(data) {
    if (isEmpty(data)) {
        $('#txt_' + textoDireccionId).val('');
    } else {
        $('#txt_' + textoDireccionId).val(data[0]['direccion']);
    }

}

/**
 *
 * @param data
 * @param data.pdf
 * @param data.nombre
 * @param data.url
 */
function abrirDocumentoPDF(data) {
    var link = document.createElement("a");
    link.download = data.nombre + '.pdf';
    link.href = 'vistas/com/movimiento/documentos/' + data.pdf;
    link.click();


    ax.setAccion("eliminarPDF");
    ax.addParamTmp("url", data.url);
    ax.consumir();
}

function obtenerMovimientoTipoColumnaLista() {
    ax.setAccion("obtenerMovimientoTipoColumnaLista");
    ax.consumir();
}

var detalleColumna = [];
var detalleColumnaDefs = [];
var indexFechaCreacion = 0;

/**
 *
 * @param data
 * @param data.ancho
 * @param data.alias
 * @param data.alineacion
 * @param data.documento_columna_consulta_id
 * @param data.fecha_creacion
 * @param data.documento_tipo_dato_tipo
 * @param data.documento_tipo_icono
 * @param data.documento_id
 */
function onResponseObtenerMovimientoTipoColumnaLista(data) {
    var dataColumna = data;

    $("#theadListado").empty();
    var fila = "<tr>";

    //columnas dinamicas de acuerdo a documento_tipo_columna
    var anchoDinamicoTabla = 0;
    var objDetalle = {};
    var objColumnaDefs = {};
    var alinear = '';

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            //PARA LA CABECERA
            fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.nombre + "</th>";
            anchoDinamicoTabla += parseInt(item.ancho);

            //PARA LAS COLUMNAS DEL DETALLE
            //limpiar
            objDetalle = {};
            alinear = '';

            //construir
            objDetalle.data = item.alias;
            if(item.alias === 'documento_estado_negocio_descripcion'){
                //objDetalle.mRender = '<a href="google.com/'+data+'">';
            }
            objDetalle.width = item.ancho + "px";

            if (!isEmpty(item.alineacion)) {
                switch (item.alineacion) {
                    case 'D':
                        alinear = "alignRight";
                        break;
                    case 'I':
                        alinear = "alignLeft";
                        break;
                    case 'C':
                        alinear = "alignCenter";
                        break;
                }

                objDetalle.sClass = alinear;
            }

            //guardo en array
            detalleColumna.push(objDetalle);
            //PARA LAS COLUMNAS DEFS DEL DETALLE
            objColumnaDefs = {};
            if (!isEmpty(item.documento_columna_consulta_id)) {
                switch (parseInt(item.documento_columna_consulta_id)) {
                    //FORMATO NUMERO DECIMAL
                    case 5: //total
                        objColumnaDefs.render = function (data, type, row) {
                            return parseFloat(data).formatMoney(2, '.', ',');
                        };
                        objColumnaDefs.targets = index;
                        break;

                    case 8: //fecha creacion
                        objColumnaDefs.render = function (data, type, row) {
                            var fecha = row.fecha_creacion;
                            var muestraFecha = '';

                            if (obtenerFechaActualBD() == data.substring(0, 10)) {
                                muestraFecha = fecha.substring(12, fecha.length);
                            } else {
                                muestraFecha = fecha.substring(0, 10);
                            }
                            return muestraFecha;
                        };
                        objColumnaDefs.targets = index;
                        indexFechaCreacion = index;
                        break;
                    case 18: //ESTADO NEGOCIO
                        objColumnaDefs.render=function(data, type, row){
                            var rowTitle = row.documento_tipo_descripcion + " " + row.serie_numero;
                            return '<a href="#" onclick="obtenerReporteDocumentosAsignaciones('+row.documento_id+',\''+rowTitle+'\')">'+data+'</a>';
                        };
                        objColumnaDefs.targets = index;
                        break;
                        //FORMATO FECHAS
                    case 6://fecha emision
                    case 7://fecha vencimiento
                        objColumnaDefs.render = function (data, type, row) {
                            var fecha = '';
                            if (!isEmpty(data)) {
                                fecha = formatearFechaBDCadena(data);
                            }
                            return fecha;
                        };
                        objColumnaDefs.targets = index;
                        break;

                    case 1: //nombre persona
                        objColumnaDefs.render = function (data) {
                            if (!isEmpty(data)) {
                                if (data.length > 28) {
                                    data = data.substring(0, 25) + '...';
                                }
                            }
                            return data;
                        };
                        objColumnaDefs.targets = index;
                        break;
                    case 2: //documento descripcion, para mostrar el icono en ves de la descripcion.
                        objColumnaDefs.render = function (data, type, row) {
                            return '<i class="' + row.documento_tipo_icono + '"></i>';
                        };
                        objColumnaDefs.targets = index;
                        break;
                    case 19: //BIEN UNICO
                        objColumnaDefs.render=function(data, type, row){
//                            console.log(data,row);
//                            data=row.bien_unico_contad;
                            var titulo = row.documento_tipo_descripcion + " " + row.serie_numero;
                            var html='';
                            if(isEmpty(data)){
                                html='<button class="btn btn-success btn-xs m-b-5" onclick="confirmarGenerarBienUnico('+row.documento_id+',\''+titulo+'\')" style="margin-bottom: 1px;"><i class="ion-plus"></i>&nbsp Generar</button>';                                
                            }else if(data==1) {
                                html='<button class="btn btn-danger btn-xs m-b-5" onclick="confirmarAnularBienUnico('+row.documento_id+',\''+titulo+'\')" style="margin-bottom: 1px;"><i class="ion-close-round"></i>&nbsp Anular</button>'
                            }
                            
                            if(row.estado_documento!=1){
                                html='';
                            }
                            return html;
                        };
                        objColumnaDefs.targets = index;
                        break;
                    case 20:
                        $('#liEstadoNegocio').show();
                        break;
                }
            } else {
                switch (parseInt(item.documento_tipo_dato_tipo)) {
                    //FORMATO NUMERO DECIMAL
                    case 1:
                        objColumnaDefs.render = function (data, type, row) {
                            return parseFloat(data).formatMoney(2, '.', ',');
                        };
                        objColumnaDefs.targets = index;
                        break;

                        //FORMATO FECHAS
                    case 3:
                        objColumnaDefs.render = function (data, type, row) {
                            var fecha = '';
                            if (!isEmpty(data)) {
                                fecha = formatearFechaBDCadena(data);
                            }
                            return fecha;
                        };
                        objColumnaDefs.targets = index;
                        break;
                }

            }
            //guardar en array columna defs
            if (!isEmpty(objColumnaDefs)) {
                detalleColumnaDefs.push(objColumnaDefs);
            }
        });
    }

    //acciones
    fila += "<th style='text-align:center; width:95px;'>Acciones</th>";
//    fila += "<th style='text-align:center; width:95px;'>Acciones</th>";
//    fila += "<th style='text-align:center; width:95px;'>Acciones</th>";
    anchoDinamicoTabla += 95;
    detalleColumna.push({data: "acciones", width: "95px"});

    fila += "</tr>";

    $("#datatable").width(anchoDinamicoTabla);
    $('#datatable thead').append(fila);


}

function obtenerEmail(accion, descripcion, icono) {
    $('#idDescripcionBoton').show();
    $('#idDescripcionBoton').html('<i class="' + icono + '"></i>&nbsp;&nbsp; ' + descripcion);

    ax.setAccion("obtenerEmailsXAccion");
    ax.addParamTmp("accion", accion);
    ax.addParamTmp("documentoId", docId);
    ax.consumir();
}

var resultadoObtenerEmails;
/**
 *
 * @param data
 * @param data.correo
 */
function onResponseObtenerEmailsXAccion(data) {
    resultadoObtenerEmails = data;

    $('#txtCorreo').val(data.correo);
}



function enviarCorreoXAccion() {
    var correo = $('#txtCorreo').val();
    //var pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;

    correo = fixCorreo(correo);
    $('#txtCorreo').val(correo);
    var arr = correo.split(";");
    var valid = true;
    var boo = false;
    for (var i = 0; i < arr.length - 1; i++)
    {
        boo = validarEmail(arr[i]);
        if (boo)
        {
            valid =  valid && boo;
        }
        else{
            valid = valid && boo;
        }
    }

    if (isEmpty(resultadoObtenerEmails)) {
        mostrarAdvertencia("Seleccion tipo de envío de correo.");
        return;
    }

    if (!isEmpty(correo) && valid) {
        loaderShow('#modalDetalleDocumento');
        ax.setAccion("enviarCorreoXAccion");
        ax.addParamTmp("correo", correo);
        ax.addParamTmp("documentoId", docId);
        ax.addParamTmp("dataRespuestaEmail", resultadoObtenerEmails);
        ax.addParamTmp("comentario", $('#txtComentario').val());
        ax.consumir();

    } else {
        mostrarAdvertencia("Ingrese email válido.");
        return;
    }
}

/**
 *
 * @param data
 * @param data.funcion
 * @param data.icono
 */
function dibujarTipoEnvioEmail(data) {

    $("#idDescripcionBoton").hide();
    var ulObtenerEmail = $("#ulObtenerEmail");
    ulObtenerEmail.empty();
    var html = '';
    var estilo = '';
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            estilo = '';
            if (!isEmpty(item.color)) {
                estilo = 'style="color: ' + item.color + '"';
            }

            html += '<li><a href="#" onclick="obtenerEmail(\'' + item.funcion + '\',\'' + item.descripcion + '\',\'' + item.icono + '\')"><i class="' + item.icono + '" ' + estilo + '></i>&nbsp;&nbsp; ' + item.descripcion + '</a></li>';
        });
    } else {
        $('#alertEmail').hide();
    }

    ulObtenerEmail.append(html);
}

function getUserEmailByUserId()
{
    ax.setAccion("getUserEmailByUserId");
    ax.consumir();

}

function appendCurrentUserEmail(data)
{
    var fetchEmail;
    var arr;
    var correo;
    var newCorreo = "";


    if (!isEmpty(data))
    {
        fetchEmail = data[0]['email'];
    }

    if ($("#checkIncluirSelf").is(':checked'))
    {
        $('#txtCorreo').val(function (i, val) {
            return val + fetchEmail + (val ? ';' : '');
        });


        correo = $('#txtCorreo').val();
        correo = fixCorreo(correo);
        $('#txtCorreo').val(correo);

    } else {

        correo = $('#txtCorreo').val();
        arr = correo.split(";");

        for (var i = 0; i < arr.length; i++)
        {
            if (arr[i] === fetchEmail)
            {
                //alert("adsa: "+arr[i]);
                arr.splice(i, 1);
            } else {
                newCorreo += arr[i]+";";

            }
        }
        newCorreo = fixCorreo(newCorreo);
        $('#txtCorreo').val(newCorreo);
    }
}

function cerrarModalReporteAtenciones()
{
    $('#dataReporteAtenciones').empty();
    $('#modalReporteAtenciones').modal('hide');
}

function obtenerReporteDocumentosAsignaciones(documentoId, rowTitle)
{
    loaderShow();
    var tituloTablaReportes = $('#modalReporteAtencionesTitulo');
    tituloTablaReportes.text("Atencion de " + rowTitle);
    ax.setAccion("obtenerReporteDocumentosAsignaciones");
    ax.addParamTmp("documento_id", documentoId);
    ax.consumir();
}

/**
 *
 * @param data
 * @param data.serie_numero
 */
function onResponseObtenerReporteDocumentosAsignaciones(data)
{
    console.log(data);
    var htmlTablaReporteAtenciones="";
    var tablaReportes = $('#tableReporteAtenciones');

    tablaReportes.empty();
    if(!isEmpty(data)){
        $.each(data, function(index, item){
            htmlTablaReporteAtenciones += '<tr class="gang-name-1">'+
                                            '<td colspan="2">'+
                                                item.documento_tipo+" - "+item.serie_numero+
                                            '</td>'+
                                          '</tr>';
        });
        tablaReportes.append(htmlTablaReporteAtenciones);
    }else{
        tablaReportes.append("<h2>Aún no se registran atenciones para este documento.</h2>")
    }
    $('#modalReporteAtenciones').modal('show');

}

function confirmarGenerarBienUnico(documentoId,titulo){
    swal({
        title: "Est\xe1s seguro?",
        text: "Generará los códigos únicos de los productos correspondientes del documento: <br>"+titulo,
        type: "warning",
        html:true,
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, generar !",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            generarBienUnico(documentoId);
        } else {
            swal("Cancelado", "La generación fue cancelada", "error");
        }
    });
}

function generarBienUnico(documentoId){
    loaderShow();
    ax.setAccion("generarBienUnicoXDocumentoId");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}

function onResponseGenerarBienUnicoXDocumentoId(data){
    exitoCrear(data);
    buscar();
}

function confirmarAnularBienUnico(documentoId,titulo){
    swal({
        title: "Est\xe1s seguro?",
        text: "Anulará los códigos únicos de los productos correspondientes del documento: <br>"+titulo,
        type: "warning",
        html:true,
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, anular!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            anularBienUnico(documentoId);
        } else {
            swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
        }
    });
}

function anularBienUnico(documentoId){
    loaderShow();
    ax.setAccion("anularBienUnicoXDocumentoId");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();    
}

function asignarCodigoUnico(documentoId, movimientoId){
    loaderShow();
    ax.setAccion("obtenerBienUnicoConfiguracionInicial");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();    
}

var dataAsignarBU = null;
function onResponseObtenerBienUnicoConfiguracionInicial(data) {
    dataAsignarBU = data;
    var dataBienUnicoDisponible = data.dataBienUnicoDisponible;
    var dataDocumento = data.dataDocumento;
    var dataMovimientoBienUnico = data.dataMovimientoBienUnico;
    var banderaBU = dataDocumento[0]['estado_qr'];
    var titulo = "";

//    if (!isEmpty(dataMovimientoBienUnico)) {
//        listaBienUnicoDetalle = dataMovimientoBienUnico;
//        banderaBU = validarCantidadesExactasBUD();
//    }

    if (banderaBU==2) {
        titulo = '<b>Códigos únicos asignados: </b>' + dataDocumento[0]['documento_tipo_descripcion'] + ' ' + dataDocumento[0]['serie_numero'];
        $('#tituloModalAsignarCodigoUnico').html(titulo);

        $('#divAgregarBU').hide();
        $('#divLeyendaBU').hide();
        $('#idGuardarBienUnico').hide();
        $('#idEnviarBienUnico').hide();
        
        listaBienUnicoDetalle = dataMovimientoBienUnico;
        onListarBienUnicoDetalle(listaBienUnicoDetalle, 0);
//        return;
    } else {
        if (isEmpty(dataBienUnicoDisponible)) {
            mostrarAdvertencia('No hay productos únicos disponibles para asignar.');
            return;
        }

        titulo = '<b>Asignar códigos únicos: </b>' + dataDocumento[0]['documento_tipo_descripcion'] + ' ' + dataDocumento[0]['serie_numero'];
        $('#tituloModalAsignarCodigoUnico').html(titulo);

        $('#divAgregarBU').show();
        $('#divLeyendaBU').show();
        $('#idGuardarBienUnico').show();
        $('#idEnviarBienUnico').show();

        select2.cargar('cboBienUnico', dataBienUnicoDisponible, 'bien_unico_id', ['codigo_unico', 'bien_descripcion']);
        select2.asignarValor('cboBienUnico', dataBienUnicoDisponible[0].bien_unico_id);

        document.getElementById("chkHasta").checked = false;
        onClickCheckHasta();
        limpiarBienUnicoDetalle();

        if (!isEmpty(dataMovimientoBienUnico)) {
            listaBienUnicoDetalle = dataMovimientoBienUnico;
            onListarBienUnicoDetalle(listaBienUnicoDetalle, 1);
        }
    }

    $('#modalAsignarCodigoUnico').modal('show');    
}

function onClickCheckHasta(){    
//        document.getElementById("chkHasta").checked = true;
    if (document.getElementById("chkHasta").checked) {      
        var buPartes= obtenerBienUnicoPartes();
        
        $('#txtBienUnicoDescripcion').val(buPartes.parte1);          
        $('#txtBienUnicoNumero').val(buPartes.parte2);               
    }else{        
        $('#txtBienUnicoDescripcion').val('');        
        $('#txtBienUnicoNumero').val('');  
    }
}

function onChangeComboBienUnico(){
    onClickCheckHasta();
}

function obtenerBienUnicoPartes(){    
    var dataBienUnicoDisponible=dataAsignarBU.dataBienUnicoDisponible;
    
    var indiceBU=document.getElementById('cboBienUnico').options.selectedIndex;
    var codigoBU = dataBienUnicoDisponible[indiceBU].codigo_unico;
    
    var parte1=codigoBU.substring(0,codigoBU.length-5);
    var parte2=codigoBU.substring(codigoBU.length-5,codigoBU.length)*1;
    
    var parteUltima=parte2;
    $.each(dataBienUnicoDisponible, function (i, item) {
        var codigoBUItem=item.codigo_unico;
        var parteItem1=codigoBUItem.substring(0,codigoBUItem.length-5);
        var parteItem2=codigoBUItem.substring(codigoBUItem.length-5,codigoBUItem.length)*1;
        
        if (parte1===parteItem1) {
            parteUltima = parteItem2;
        }
    });
    
    return {parte1:parte1,parte2:parteUltima};            
}

//AGREGAR BIEN UNICO DETALLE

var listaBienUnicoDetalle = [];


function agregarBienUnico() {
    if (!document.getElementById("chkHasta").checked) {
        //solo un producto
        var dataBienUnicoDisponible = dataAsignarBU.dataBienUnicoDisponible;
        var indiceBU = document.getElementById('cboBienUnico').options.selectedIndex;
        var bienUnico = dataBienUnicoDisponible[indiceBU];
        
        agregarBienUnicoDetalle(bienUnico);
        
    } else {
        //array
        var parteUltima=$('#txtBienUnicoNumero').val();
        var dataBienUnicoDisponible = dataAsignarBU.dataBienUnicoDisponible;

        var indiceBU = document.getElementById('cboBienUnico').options.selectedIndex;
        var codigoBU = dataBienUnicoDisponible[indiceBU].codigo_unico;

        var parte1 = codigoBU.substring(0, codigoBU.length - 5);
        var parte2 = codigoBU.substring(codigoBU.length - 5, codigoBU.length) * 1;
                
        for(var i = parte2; i <= parteUltima; i++){
            var codigoGenerado=parte1+pad(i, 5);                
            
            var itemBienUnico=null;
            $.each(dataBienUnicoDisponible, function (i, item) {
                if (item.codigo_unico == codigoGenerado) {
                    itemBienUnico=item;
                    return;
                }
            });
            
            if(!isEmpty(itemBienUnico)){
                agregarBienUnicoDetalle(itemBienUnico);
            }            
        }
    }
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

function agregarBienUnicoDetalle(objDetalle) {
    if (validarBienUnicoDetalle(objDetalle)) {
        objDetalle.movimiento_bien_unico_id = null;

        var bienId = objDetalle.bien_id;
        var dataMovimientoBien = dataAsignarBU.dataMovimientoBien;

        var movimientoBienId = 0;
        $.each(dataMovimientoBien, function (i, item) {
            if (item.bien_id == bienId) {
                movimientoBienId = item.movimiento_bien_id;
                return;
            }
        });

        objDetalle.movimiento_bien_id = movimientoBienId;

        listaBienUnicoDetalle.push(objDetalle);

        onListarBienUnicoDetalle(listaBienUnicoDetalle,1);
    }
}

function validarBienUnicoDetalle(objDetalle) {
    var check=document.getElementById("chkHasta").checked;
    
    var valido = true;

    //REPETIDO
    var indice = buscarBienUnicoDetalle(objDetalle.bien_unico_id);
    if (indice > -1) {
//        if(!check){
            mostrarAdvertencia("Producto único ya ha sido agregado: "+ objDetalle.codigo_unico);
//        }
        valido = false;
    }
    
    if(!valido){
        return valido;
    }
    
    //CANTIDAD <    
    var bienId=objDetalle.bien_id;
    var dataMovimientoBien =dataAsignarBU.dataMovimientoBien;
    
    var cantidadTotal = 0;
    $.each(dataMovimientoBien, function (i, item) {
        if (item.bien_id == bienId) {
            cantidadTotal = item.cantidad * 1;
            return;
        }
    });
        
    var cont=0;
    $.each(listaBienUnicoDetalle, function (i, item) {
        if(item.bien_id==bienId){
            cont++;
        }       
    });
    
    if(cont>=cantidadTotal){        
        if(!check){
            mostrarAdvertencia(" Ya se completó la cantidad requerida ("+ cantidadTotal +") del producto "+objDetalle.bien_descripcion)
        }
        valido=false;
    }

    return valido;
}

function buscarBienUnicoDetalle(bienUnicoId) {    
    var ind = -1;
    
    if (!isEmpty(listaBienUnicoDetalle)) {
        $.each(listaBienUnicoDetalle, function (i, item) {
            if (item.bien_unico_id == bienUnicoId) {
                ind = i;
            }
        });
    }    
    
    return ind;
}

function onListarBienUnicoDetalle(data,opcion) {    
//    $('#dataTableBienUnicoDetalle tbody tr').remove();
//    $('#dataTableBienUnicoDetalle thead tr').remove();
    $("#dataList").empty();
        
    //dibujando la cabecera
    var cabeza = "<table id='dataTableBienUnicoDetalle' class='table table-striped table-bordered'>"+
                 "<thead>"+
                    '<tr>' +
                        '<th style="text-align:center">N°</th>' +
                        '<th style="text-align:center">Prod. Único</th>' +
                        '<th style="text-align:center">Producto</th>' +
                        '<th style="text-align:center">Estado</th>';
    if (opcion == 1) {
                cabeza += '<th style="text-align:center">Opciones</th>';
    }
          cabeza += '</tr>'+
                "</thead>";
    
    
    var cuerpo = "";
    var ind=0;
    if (!isEmpty(data)) {
        data.forEach(function (item) {
            if(opcion==1){
                var eliminar = "<a href='#' onclick = 'eliminarBienUnicoDetalle(\""+ ind + "\")' >"
                        + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";
            }
            var estadoBU=calcularEstadoBU(ind);
            
            cuerpo += "<tr>"
                        + "<td style='text-align:right;'>" + (ind + 1) + "</td>"
                        + "<td style='text-align:left;'>" + item.codigo_unico + "</td>"
                        + "<td style='text-align:left;'>" + item.bien_descripcion + "</td>"
                        + "<td style='text-align:center;'>" + estadoBU + "</td>";
            if (opcion == 1) {
                cuerpo += "<td style='text-align:center;'>" + eliminar +"</td>";
            }
                cuerpo += "</tr>";
            
            ind++;
        });

//        $('#dataTableBienUnicoDetalle tbody').append(cuerpo);
    }        
    
    var pie = '</table>';
    var html = cabeza + cuerpo + pie;
    $("#dataList").append(html);
    onResponseVacio();
}

function onResponseVacio() {
    $('#dataTableBienUnicoDetalle').dataTable({
        "order": [[ 0, 'asc' ]],
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
}

function calcularEstadoBU(indice){
    var bienId=listaBienUnicoDetalle[indice].bien_id;
    var dataMovimientoBien =dataAsignarBU.dataMovimientoBien;
    
    var cantidadTotal = 0;
    $.each(dataMovimientoBien, function (i, item) {
        if (item.bien_id == bienId) {
            cantidadTotal = item.cantidad * 1;
            return;
        }
    });
        
    var cont=0;
    for (var i = 0; i <= indice; i++) {
        if(listaBienUnicoDetalle[i].bien_id==bienId){
            cont++;
        }        
    }
    
    return cont+'/'+cantidadTotal;    
}


var listaBienUnicoDetalleEliminado = [];

function eliminarBienUnicoDetalle(indice) {
    if (!isEmpty(listaBienUnicoDetalle[indice].movimiento_bien_unico_id)) {
        listaBienUnicoDetalleEliminado.push(listaBienUnicoDetalle[indice].movimiento_bien_unico_id);
    }
    
    mostrarOk('Producto único eliminado: '+listaBienUnicoDetalle[indice].codigo_unico);
    listaBienUnicoDetalle.splice(indice, 1);
    
    var detalleCopia=listaBienUnicoDetalle.slice();
    listaBienUnicoDetalle = [];
    
    if(!isEmpty(detalleCopia)){
        $.each(detalleCopia, function (i, item) {
            listaBienUnicoDetalle.push(item);
        });
    }
        
    onListarBienUnicoDetalle(listaBienUnicoDetalle,1);
}

function limpiarBienUnicoDetalle(){    
//    $('#dataTableBienUnicoDetalle tbody tr').remove();
    $("#dataList").empty();
    listaBienUnicoDetalle=[];
    listaBienUnicoDetalleEliminado = [];
}

function guardarBienUnicoDetalle(estadoQR){
    if(isEmpty(listaBienUnicoDetalle)){
        mostrarAdvertencia('Seleccione productos únicos.');
        return;
    }
    $('#modalAsignarCodigoUnico').modal('hide');
    loaderShow();
    ax.setAccion("guardarBienUnicoDetalle");
    ax.addParamTmp("listaBienUnicoDetalle", listaBienUnicoDetalle);
    ax.addParamTmp("listaBienUnicoDetalleEliminado", listaBienUnicoDetalleEliminado);
    ax.addParamTmp("estadoQR", estadoQR);
    ax.consumir();
}

function onResponseGuardarBienUnicoDetalle(data) {
    if (data.respuesta[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Error.");
    } else {
        $.Notification.autoHideNotify('success', 'top-right', 'Éxito', 'Se guardó correctamente la asignación de productos únicos.');

        $('#modalAsignarCodigoUnico').modal('hide');
        $('.modal-backdrop').hide();
        
        if(data.indicador==1){
            buscar();
        }
    }
}

function enviarBienUnicoDetalle(){
    //valido cantidades exactas
    var bandera=validarCantidadesExactasBUD();
    if(bandera){
        $('#modalAsignarCodigoUnico').modal('hide');
        swal({
            title: "Estás seguro?",
            text: "Si finaliza la asignación de códigos únicos ya no podrá modificar la asignación posteriormente.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si, finalizar!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No, cancelar !",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
//                $('#modalAsignarCodigoUnico').modal('show');  
                guardarBienUnicoDetalle(2); 
            }else{
                $('#modalAsignarCodigoUnico').modal('show');                
            } 
        });
        
               
    }else{
        mostrarAdvertencia('Falta ingresar productos únicos. Cantidad por detalle del documento incompleto.')
    }
}

function validarCantidadesExactasBUD(){    
    var listaBUDetalleCantidades=[];
    
    var dataMovimientoBien =dataAsignarBU.dataMovimientoBien;
    
    $.each(dataMovimientoBien, function (i, itemMB) {
        var cont=0;
        $.each(listaBienUnicoDetalle, function (j, itemBUD) {
            if(itemMB.bien_id==itemBUD.bien_id){
                cont++;
            }       
        });
        
        listaBUDetalleCantidades.push({bienId:itemMB.bien_id,contador: cont});        
    });        

    var bandera = true;
    $.each(dataMovimientoBien, function (i, itemMB) {
        $.each(listaBUDetalleCantidades, function (j, itemBUDC) {
            if (itemMB.bien_id == itemBUDC.bienId) {
                if (itemMB.cantidad * 1 != itemBUDC.contador * 1) {
                    bandera = false;
                    return;
                }
            }
        });
    });
    
    if(dataMovimientoBien.length!=listaBUDetalleCantidades.length){
        bandera=false;
    }
    
    return bandera;
}

function dibujarLeyendaAcciones(data) {
    var html='<br><b>Leyenda:</b>&nbsp;&nbsp;';
    if (!isEmpty(data)) {
        $.each(data, function (i, item) {
            html+="<i class='"+item.icono+"' style='color:"+item.color+";'></i>&nbsp;"+item.descripcion+" &nbsp;&nbsp;&nbsp;";
        });
    }
    
    $('#divLeyenda').html(html);
}

function imprimirDocumentoQR(documentoId,movimientoId){
    $('#documentoIdHidden').val(documentoId);
    document.formDocumentoQR.submit();    
}