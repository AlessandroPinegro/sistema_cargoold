var dataDocumentoTipoDatoPago;
var banderaBuscarPago = 0;
var estadoTolltipPago = 0;
var bandera_anularPagoPago = false;
var bandera_anularPagoPagoPago = false;

$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
//    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponsePagoListar");
    obtenerDocumentoTipoPago();
    cambiarAnchoBusquedaDesplegable();
});

function nuevoPagoForm()
{
    VALOR_ID_USUARIO = null;
    cargarDiv('#window', 'vistas/com/pago/pago.php');
}
function obtenerDocumentoTipoPago()
{
    ax.setAccion("obtenerDocumentoTipoPago");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponsePagoListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDocumentoTipoPago':
                onResponseObtenerDocumentoTipoPago(response.data);
                buscarPago(1);
//                colapsarBuscador();
                loaderClose();
                break;
            case 'anularDocumentoPago':
                loaderClose();
//                if(response.data['0'].vout_exito ==2)
//                {
//                    swal("Cancelado", " " + response.data['0'].vout_mensaje, "error");
//                }else
//                {
                    swal("Anulado!", "Documento de pago anulado correctamente.", "success");
                    bandera_anularPago = true;
                    buscarPago();
//                }
                
                break;
            case 'eliminarDocumentoPago':
                loaderClose();
                if(response.data[0].vout_exito == 1)
                {
                    swal("Eliminado!", "Documento de pago eliminado correctamente.", "success");
                    buscarPago();
                }else
                {
                    swal("Cancelado", "No se puede eliminar el documento, ya fue utilizado" , "error");
                }
                bandera_eliminarPago = true;
                break;
                
            case 'obtenerDetallePago':
                onResponseVisualizarDetallePago(response.data);
                loaderClose();
                break;
            case 'buscarCriteriosBusquedaDocumentoPagadosListarPago':
                onResponseBuscarCriteriosBusquedaDocumentoPagadosListarPago(response.data);
                loaderClose();
                break;
            case 'eliminarRelacionDePago':
//                exitoCrear(response.data);
                onResponseEliminarRelacionDePago(response[PARAM_TAG],response.data);
//                visualizarDocumentoPago(response[PARAM_TAG]);
                loaderClose();
                break;
            case 'eliminarDocumentoDePago':
                onResponseEliminarDocumentoDePago(response[PARAM_TAG],response.data);
                loaderClose();
                break;
        }
    }
    else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'imprimir':
                loaderClose();
                break;
            case 'anularDocumentoPago':
                loaderClose();
//               swal("Cancelado", "No se puede anular el documento, ya fue utilizado" , "error");          
                swal({title: "Cancelado",text: response.message,type: "error",html: true});      
                break;
            case 'visualizarDocumento':
                loaderClose();
                break;
            case 'eliminarRelacionDePago':
                loaderClose();       
                swal({title: "Cancelado",text: response.message,type: "error",html: true});      
                break;
        }
    }
}

function onResponseObtenerDocumentoTipoPago(data) {
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        onResponseObtenerDocumentoTipoPagoDato(data.documento_tipo_dato, data.persona_activa);
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

function onResponseObtenerDocumentoTipoPagoDato(data, personaActiva) {
    dataDocumentoTipoDatoPago = data;
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        // Mostraremos la data en filas de dos columnas

        var columna = 1;
        $.each(data, function (index, item) {
            if(item.tipo!=12 && item.tipo!=11){
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

function buscarPago(colapsa)
{
    loaderShow(null);
    var cadena;
    cadena = obtenerDatosBusquedaPago();
    banderaBuscar = 1;
    getPagoDataTable();
}
var actualizandoBusqueda = false;
function actualizarBusqueda()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPago(0);
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
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}

function getPagoDataTable() {
    ax.setAccion("obtenerDocumentosPagados");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.addParamTmp("criterios", dataDocumentoTipoDatoPago);
    $('#datatableListaPagos').dataTable({
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
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],
        "columns": [
            {"data": "fecha_creacion", "class": "alignCenter", "width": "50px"},
            {"data": "fecha_emision", "class": "alignCenter", "width": "50px"},
            {"data": "documento_tipo_descripcion", "width": "110px"},
            {"data": "persona_nombre_completo", "width": "250px"},
            {"data": "serie", "width": "40px"},
            {"data": "numero", "width": "50px"},
            {"data": "fecha_vencimiento", "class": "alignCenter", "width": "70px"},
            {"data": "moneda_descripcion", "width": "70px"},
            {"data": "pendiente", "class": "alignRight", "width": "50px"},
            {"data": "total", "class": "alignRight", "width": "60px"},
            {"data": "documento_estado_descripcion", "width": "60px"},
            {"data": "acciones","class": "alignCenter", "width": "40px"}
        ],
        columnDefs: [
            
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [8,9]
            }
        ],
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true
    });
    loaderClose();
}
function obtenerDatosBusquedaPago()
{
    var valorPersona;

    tipoDocumentoPago = $('#cboDocumentoTipo').val();
    var cadena = "";
//    if (!isEmpty(tipoDocumento))
//    {

    cargarDatoDeBusquedaPago();
    var valorTipoDocumento = obtenerValorTipoDocumento();
    cadena += (!isEmpty(valorTipoDocumento)) ? valorTipoDocumento + "<br>" : "";
    $.each(dataDocumentoTipoDatoPago, function (index, item) {


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
                    cadena += StringNegrita(item.descripcion) + ": ";
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
                    cadena += StringNegrita(item.descripcion) + ": ";
                    cadena += item.valor.inicio + " - " + item.valor.fin + " ";
                    cadena += "<br>";
                }
                break;
            case 4:

                if (!isEmpty(item.valor))
                {
                    if (select2.obtenerText('cbo_' + item.id) !== null)
                    {
                        cadena += StringNegrita(item.descripcion) + ": ";
                        cadena += select2.obtenerText('cbo_' + item.id) + " ";
                        cadena += "<br>";
                    }
                }
                break;
            case 5:
                if (item.valor != 0)
                {
                    cadena += StringNegrita(item.descripcion) + ": ";
                    valorPersona = select2.obtenerText('cbo_' + item.id);
                    cadena += valorPersona;
                    cadena += "<br>";
                }
                break;
        }
    });
    dataDocumentoTipoDatoPago[0]['tipoDocumento'] = tipoDocumentoPago;
    return cadena;
//    }
//    return 0;
}

function obtenerValorTipoDocumento()
{
    var valorTipoDocumento = select2.obtenerTextMultiple('cboDocumentoTipo');
    if (valorTipoDocumento !== null)
    {
        var cadena = StringNegrita("Tipo de documento: ") + valorTipoDocumento;
        return cadena;
    }
    return "";


}

function cargarDatoDeBusquedaPago()
{
    $.each(dataDocumentoTipoDatoPago, function (index, item) {
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
function anularPago(id)
{
    confirmarAnularPago(id);
}

function confirmarAnularPago(id) {
    bandera_anularPago = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Anular un documento de pago, esta anulación no podra revertirse.",
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
            anularDocumento(id);
        } else {
            if (bandera_anularPago == false)
            {
                swal("Cancelado", "La anulaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function anularDocumento(id)
{
    loaderShow();
    ax.setAccion("anularDocumentoPago");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function eliminarPago(id)
{
    confirmarEliminarPago(id);
}
function confirmarEliminarPago(id) {
    bandera_eliminarPago = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás un documento de pago",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarDocumentoPago(id);
        } else {
            if (bandera_eliminarPago == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function eliminarDocumentoPago(id)
{
    loaderShow();
    ax.setAccion("eliminarDocumentoPago");
    ax.addParamTmp("id", id);
    ax.consumir();
}


function cerrarPopover()
{
    if (banderaBuscar == 1)
    {
        if (estadoTolltipPago == 1)
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
    estadoTolltipPago = (estadoTolltipPago == 0) ? 1 : 0;
}

var documentoPagoId;
function visualizarDocumentoPago(documentoId,movimientoId)
{
    documentoPagoId=documentoId;
    
    $('#txtCorreo').val('');
    loaderShow();
    ax.setAccion("obtenerDetallePago");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();

//    
}
function onResponseVisualizarDetallePago(data)
{
    $('[data-toggle="popover"]').popover('hide');
    cargarDetalleDocumentoPago(data);
//    $('#modalDetalleDocumentoPago').modal('show');
}
function cargarDetalleDocumentoPago(data) {

    if (!isEmptyData(data))
    {
        
        var stringTitulo = '<strong> ' + data[0]['documento_tipo_descripcion'] +' '+ data[0]['serie_numero'] + '</strong>';
                
        $('#datatableDocumentoPago').dataTable({
//            "scrollX":datatable2 true,
            "order": [[0, "desc"]],
            "data": data,
//            "scrollX": true,
            "columns": [
//                {"data": "serie_documento_pago", "sClass": "alignCenter"},
                {"data": "codigo_documento_pago", "sClass": "alignCenter"},
                {"data": "fecha", "sClass": "alignCenter"},
                {"data": "documento_pago_descripcion"},
                {"data": "numero"},
                {"data": "moneda_descripcion"},
                {"data": "importe", "sClass": "alignRight"},
                {"data": "documento_pago_id", "sClass": "alignCenter"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 5
                }, 
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {  
                        var html='';                        
                        if(row.cantidad_pagos==1 && !isEmpty(row.documento_pago) ){
                            html='&nbsp;&nbsp;&nbsp;<a href="#" onclick="confirmarEliminarDocumentoDePago('+row.documento_pago+','+row.documento_id+')" title="Eliminar documento de pago"><b><i class="fa fa-minus-square" style="color:#ebc142;"></i><b></b></b></a>';
                        }
                        
                        return '<a href="#" onclick="confirmarEliminarRelacionDePago('+data+','+row.documento_id+')" title="Eliminar pago"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></b></b></a>'+html;
                    },
                    "targets": 6
                }
            ],
            "destroy": true
        });
        
        $('.modal-title').empty();               
        $('.modal-title').append(stringTitulo);
        $('#modalDetalleDocumentoPago').modal('show');  
    }
    else
    {
        var table = $('#datatableDocumentoPago').DataTable();
        table.clear().draw();
    }
}

//function anularDocumento(id)
//{
//    confirmarAnularMovimiento(id);
//}

function enviarCorreoDocumentoPago(){        
    var correo=$('#txtCorreo').val();
    if(isEmpty(correo)){
        mostrarAdvertencia("Ingrese email");
        return;
    }    
    
    loaderShow('#modalDetalleDocumentoPago');
    ax.setAccion("enviarCorreoDocumentoPago");
    ax.addParamTmp("correo", correo);
    ax.addParamTmp("documento_id", documentoPagoId);
    ax.addParamTmp("tipoCobroPago", 1);//1->cobro, 2 -> pago
    ax.consumir();
}


$('.dropdown-menu').click(function(e) {
//        console.log(e);
    if(e.target.id != "btnBusqueda"
            && e.delegateTarget.id != "ulBuscadorDesplegable2"
            && e.delegateTarget.id != "listaEmpresa" ) {
        e.stopPropagation();
    }
});

$('#txtBuscar').keyup(function(e) {   
    var bAbierto=$(this).attr("aria-expanded");
    
    if(!eval(bAbierto)){        
        $(this).dropdown('toggle');
    }
});

function setCriterio(array, criterio){
    var found = false;
    for(var i = 0; i < array.length; i++){
        var item = array[i];
        if(parseInt(item.tipo) === parseInt(criterio.tipo)){
            found = true;
            array[i] = criterio;
            break;
        }
    }
    if(!found){
        array.push(criterio);
    }
}

function buscarCriteriosBusquedaDocumentoPagadosListarPago(){    
//    loaderShow();
    //buscarDocumentoPago();
    ax.setAccion("buscarCriteriosBusquedaDocumentoPagadosListarPago");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaDocumentoPagadosListarPago(response){
    var dataPersona = response.dataPersona;
    var dataDocumentoTipo = response.dataDocumentoTipo;
    var dataSerieNumero = response.dataSerieNumero;
    
    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {        
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorTexto(5,' + item.id + ','+null+')" >'
                    + '<span class="col-md-1"><i class="ion-person"></i></span>'
                    + '<span class="col-md-2">'
                    + '<label style="color: #141719">' + item.codigo_identificacion + '</label>'
                    + '</span>'
                    + '<span class="col-md-9">'
                    + '<label style="color: #141719">' + item.nombre + '</label>'
                    + '</span></a>'
                    + '</div>';
        });
    }    
    if (!isEmpty(dataDocumentoTipo)) {        
        $.each(dataDocumentoTipo, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorTexto(5,' + null + ','+item.id+')" >'
                    + '<span class="col-md-1"><i class="fa fa-files-o"></i></span>'
                    + '<span class="col-md-11">'
                    + '<label style="color: #141719">' + item.descripcion + '</label>'
                    + '</span></a>'
                    + '</div>';
        });
    }
    if (!isEmpty(dataSerieNumero)) {        
        $.each(dataSerieNumero, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                    + '<a onclick="busquedaPorSerieNumero(\'' + item.serie + '\',\''+item.numero+'\')" >'
                    + '<span class="col-md-1"><i class="ion-document"></i></span>'
                    + '<span class="col-md-2">'
                    + '<label style="color: #141719">' + item.serie_numero + '</label>'
                    + '</span>'
                    + '<span class="col-md-9">'
                    + '<label style="color: #141719">' + item.documento_tipo_descripcion + '</label>'
                    + '</span></a>'            
                    + '</div>';
        });
    }
    $("#ulBuscadorDesplegable2").append(html);
}

function busquedaPorTexto (tipo,texto,tipoDocumento){
    var tipoDocumentoIds = [];
    if(!isEmpty(tipoDocumento)){
        tipoDocumentoIds.push(tipoDocumento);
    }
    if(tipo == 5){
        llenarParametrosBusquedaPagoConDocumento(texto,tipoDocumentoIds,null,null);
    }
}

function busquedaPorSerieNumero(serie,numero){
    llenarParametrosBusquedaPagoConDocumento(null,null,serie,numero);
}

function llenarParametrosBusquedaPagoConDocumento(personaId,tipoDocumentoIds,serie,numero){
    
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 7,
        descripcion: "Serie",
        id: 552,
        opcional: 0,
        orden: 1,
        valor: serie !== null? serie : "",
        tipoDocumento: tipoDocumentoIds !== null
            ? tipoDocumentoIds
            : []
    });
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo : 5,
        descripcion: "Persona",
        id: 114,
        opcional: 0,
        orden: 1,
        valor: personaId
    });
    setCriterio(dataDocumentoTipoDatoPago, {
        tipo: 8,
        descripcion: "Número",
        id: 115,
        opcional: 0,
        orden: 2,
        valor: numero
    });
    getPagoDataTable();
}

function cambiarAnchoBusquedaDesplegable(){
    var ancho = $("#divBuscador").width();    
    $("#ulBuscadorDesplegable").width((ancho-5)+"px");    
    $("#ulBuscadorDesplegable2").width((ancho-5)+"px");    
}

function confirmarEliminarRelacionDePago(documentoPagoId,documentoId){
    $('#modalDetalleDocumentoPago').modal('hide');  
    
    swal({
        title: "¿Está seguro?",
        text: "Eliminará un pago, esta acción no podrá revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarRelacionDePago(documentoPagoId,documentoId);
        } else {
//            swal("Cancelado", "La eliminación fue cancelada", "error");
            $('#modalDetalleDocumentoPago').modal('show');  
        }
    });
}

function eliminarRelacionDePago(documentoPagoId,documentoId){    
    loaderShow();
    ax.setAccion("eliminarRelacionDePago");
    ax.addParamTmp("documentoPagoId", documentoPagoId);
    ax.setTag(documentoId);
    ax.consumir();
}

function exitoCrear(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        $.Notification.autoHideNotify('success', 'top-right', 'Éxito', data[0]["vout_mensaje"]);
    }
}

function onResponseEliminarRelacionDePago(documentoId, data) {
    if (data[0]["vout_exito"] == 0) {
        swalMostrarSoloConfirmacion('error','Cancelado!',data[0]["vout_mensaje"],"visualizarDocumentoPago("+documentoId+")");
    } else {
        swalMostrarSoloConfirmacion('success','Eliminado!',data[0]["vout_mensaje"],"visualizarDocumentoPago("+documentoId+");buscarPago();");
    }
}

function confirmarEliminarDocumentoDePago(documentoPago,documentoId){
    $('#modalDetalleDocumentoPago').modal('hide');  
    
    swal({
        title: "¿Está seguro?",
        text: "Eliminará el documento de pago, esta acción no podrá revertirse.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarDocumentoDePago(documentoPago,documentoId);
        } else {
//            swal("Cancelado", "La eliminación fue cancelada", "error");
            $('#modalDetalleDocumentoPago').modal('show');  
        }
    });
}

function eliminarDocumentoDePago(documentoPago,documentoId){    
    loaderShow();
    ax.setAccion("eliminarDocumentoDePago");
    ax.addParamTmp("documentoPago", documentoPago);
    ax.setTag(documentoId);
    ax.consumir();
}

function onResponseEliminarDocumentoDePago(documentoId, data) {
    if (data[0]["vout_exito"] == 0) {
        swalMostrarSoloConfirmacion('error','Cancelado!',data[0]["vout_mensaje"],"visualizarDocumentoPago("+documentoId+")");
    } else {
        swalMostrarSoloConfirmacion('success','Documento de pago eliminado!',data[0]["vout_mensaje"],"visualizarDocumentoPago("+documentoId+");buscarPago();");
    }
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