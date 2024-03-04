var c = $('#env i').attr('class');
var validarOrganizador = true;
var camposDinamicos = [];
var importes = 
    {
        totalId: null,
        subTotalId: null,
        igvId: null,
        calculoId: null
    };
    
var checkActivo = 1;

var banderaDatoDocumentoCopiar = 0;
var arrayDocumentoARelacionarIds = [];
var banderaBuscar = 0;
var estadoTooltip = 0;

var var_documentoId = null;
var var_movimientoId = null;
            
$(document).ready(function () {
    indexDetalleEditar = null;
    //loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    iniciarDataPicker();
    ax.setSuccess("onResponseMovimientoForm");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.consumir();
    $("#cboUnidadMedida").select2({
        width: "100%"
    });
});

function onResponseMovimientoForm(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerUnidadMedida':
                onResponseObtenerUnidadesMedida(response.data);
                loaderClose();
                break;
            case 'obtenerPrecioUnitario':
                onResponseObtenerPrecioUnitario(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoTipoDato':
                onResponseObtenerDocumentoTipoDato(response.data);
                loaderClose();
                break;
            case 'enviar':
                cargarPantallaListar();
//                loaderClose();
                break;
            case 'getAllPersonaTipo':
                onResponsegetAllPersonaTipo(response.data);
                break;
            case 'enviarEImprimir':
                cargarDatosImprimir(response.data, 1);
                break;
            case 'getAllPersona':
                onResponseGetAllPersona(response.data);
                break;
            case 'getAllLoaderBien':
                onResponseLoaderBien(response.data);
                break;
            case 'obtenerStockPorBien':
                onResponseStockBien(response.data);
                break;
            case 'obtenerPrecioPorBien':
                onResponsePrecioBien(response.data);
                break;
            case 'obtenerStockAControlar':
                confirmacionRedirecciona();
                valoresFormularioDetalle = null;
                break;
                
            //FUNCIONES PARA COPIAR DOCUMENTO}

            case 'ConfiguracionesBuscadorCopiaDocumento':
                OnResponseConfiguracionesBuscadorCopiaDocumento(response.data);
                buscarDocumentoACopiar();
                banderaAbrirModal = 1;
                colapsarBuscadorCopiaDocumento();
                loaderClose();
                break;
            case 'obtenerDetalleDocumentoACopiar':
                OnResponseObtenerDetalleDocumentoACopiar(response.data);
                loaderClose();
                break;
            case 'visualizarDocumento':
                onResponseVisualizarDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleDocumentoACopiarSoloDetalle':
                loaderClose();
                cargarDetalleDocumentoACopiar(response.data);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                break;
            case 'obtenerUnidadMedida':
                break;
            case 'enviar':
                loaderClose();
                break;
            case 'enviarEImprimir':
                loaderClose();
                break;
            case 'obtenerStockAControlar':
                valoresFormularioDetalle = null;
                loaderClose();
                break;
            case 'obtenerDetalleDocumentoACopiar':
                loaderClose();
                break;
            case 'visualizarDocumento':
                loaderClose();
                break;
        }
    }
}
function obtenerDocumentoTipoDato(documentoTipoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentoTipoDato");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}
function obtenerUnidadMedida(bienId) {
    //alert("hola");
    loaderShow();
    $("#cboUnidadMedida").empty();
    select2.readonly("cboUnidadMedida", true);
    ax.setAccion("obtenerUnidadMedida");
    ax.addParamTmp("bienId", bienId);
    ax.consumir();
}
function onResponseObtenerUnidadesMedida(data) {
    if (!isEmpty(data) && !isEmpty(data.unidad_medida)) {
        select2.cargar("cboUnidadMedida", data.unidad_medida, "id", "descripcion");
        select2.asignarValor("cboUnidadMedida", data.unidad_medida[0].id);
        select2.readonly("cboUnidadMedida", false);
        
        // asignamos el precio en caso no sea una edición
        if (isEmpty(indexDetalleEditar)) {
            document.getElementById("txtPrecio").value = data.precio;
        }
    }
}

function obtenerPrecioUnitario() {
    loaderShow();
    var bienId = select2.obtenerValor("cboBien");
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida");
    
    ax.setAccion("obtenerPrecioUnitario");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.consumir();
}

function onResponseObtenerPrecioUnitario(data) {
    if (!isEmpty(data)) {
        document.getElementById("txtPrecio").value = data.precio;
    }
}

function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.documento_tipo)) {
        $("#cboDocumentoTipo").select2({
            width: "100%"
        }).on("change", function (e) {
            importes.totalId = null;
            importes.subTotalId = null;
            importes.igvId = null;
            importes.calculoId = null;
            $("#contenedorChkIncluyeIGV").hide();
            $("#contenedorTotalDiv").hide();
            $("#contenedorSubTotalDiv").hide();
            $("#contenedorIgvDiv").hide();
            obtenerDocumentoTipoDato(e.val);
        });
//        $("#cboUnidadMedida").empty();
        select2.cargar("cboDocumentoTipo", data.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipo", data.documento_tipo[0].id);
        if (data.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipo", true);
        }
        onResponseObtenerDocumentoTipoDato(data.documento_tipo_conf);
        if (!isEmpty(data.bien)) {
            $("#cboBien").select2({
                width: "100%"
            }).on("change", function (e) {
                obtenerUnidadMedida(e.val);
            });
            select2.cargar("cboBien", data.bien, "id", ["codigo_fabricante","codigo","descripcion"]);
            select2.asignarValor("cboBien", 0);
            select2.readonly("cboUnidadMedida", true);
        }
        if (!isEmpty(data.organizador)) {
            select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
            select2.asignarValor("cboOrganizador", data.organizador[0].id);
        }else{
            $("#contenedorOrganizador").hide();
            validarOrganizador = false;
        }
    }
}
function onResponseObtenerDocumentoTipoDato(data) {
    camposDinamicos = [];
    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        var escribirItem;
        var contadorEspeciales = 0;
        $.each(data, function (index, item) {
            switch (parseInt(item.tipo)) {
                case 14:
                case 15:
                case 16:
                    contadorEspeciales += 1;
                    escribirItem = false;
                    break;
                default:
                    appendForm('<div class="row">');
                    var html = '<div class="form-group col-md-12">' +
                            '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
                    if (item.tipo == 5)
                    {
                        html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar proveedor"></i></a>';
                    }
                    html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    escribirItem = true;
                    break;
            }
            camposDinamicos.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion
            });
            var readonly = (parseInt(item.editable) === 0)?'readonly="true"':'';
            switch (parseInt(item.tipo)) {
                case 1:
                    html += '<input type="number" id="txt_' + item.id + '" name="txt_' + item.id +'" '+readonly+' class="form-control" value="" maxlength="45" style="text-align: right;" />';
                    break;
                case 14:
                    importes.totalId = 'txt_' + item.id;
                    $("#contenedorTotalDiv").show();
                    $("#contenedorTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id +'" '+readonly+' class="form-control" value="" maxlength="45" style="text-align: center;" />');
                    break;
                case 15:
                    importes.igvId = 'txt_' + item.id;
                    $("#contenedorIgvDiv").show();
                    $("#contenedorIgv").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id +'" '+readonly+' class="form-control" value="" maxlength="45" style="text-align: center;" />');
                    break;
                case 16:
                    importes.subTotalId = 'txt_' + item.id;
                    // si existe un subtotal, Mostramos el checkbox de cálculo de precios con / sin igv
                    $("#contenedorChkIncluyeIGV").show();
                    $("#contenedorSubTotalDiv").show();
                    $("#contenedorSubTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id +'" '+readonly+' class="form-control" value="" maxlength="45" style="text-align: center;" />');
                    break;     
                case 2:
                case 6:
                case 7:
                case 8:
                case 12:
                case 13:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)){
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)){
                        value = item.cadena_defecto;
                    } 
                    
                    html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="'+value+'" maxlength="45"/>';
                    break;
                case 3:
                case 9:
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                    break;
                case 5:
                    html += '<div id ="div_persona" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la persona</option>';
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
//                    html += '<div class="form-group col-lg-4"><a href="#" class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 17:
                    html += '<div id ="div_organizador_destino" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione organizador</option>';
                    $.each(item.data, function (indexOrganizador, itemOrganizador) {
                        html += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
            }
            if (escribirItem){
                html += '</div></div>';
                appendForm(html);
                appendForm('</div>');
            }
            switch (parseInt(item.tipo)) {
                case 3:
                case 9:
                case 10:
                case 11:
                    $('#datepicker_'+item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    });
                    $('#datepicker_'+item.id).datepicker('setDate', item.data);
                    break;
                case 4:
                    select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 17:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
            }
        });
        $("#contenedorDocumentoTipo").css("height", 75 * (data.length-contadorEspeciales));
        onChangeCheckIncluyeIGV();
    }
    $("#contenedorDocumentoTipo").css("height", $("#contenedorDocumentoTipo").height() + 30);
}

function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

function deshabilitarBoton()
{
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}
var detalle = [];
var indexDetalle = 0;
function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}
var indexDetalleEditar = null;
function confirmar() {
    loaderShow();
    if (isEmpty(indexDetalleEditar)) {
        agregar();
    } else {
        editar();
    }
}
function validarFormularioDetalle() {
// validamos que hayamos seleccionado todo
    var organizadorId = null;
    var organizadorDesc = null;
    if (validarOrganizador){
        organizadorId = select2.obtenerValor("cboOrganizador");
        if (isEmpty(organizadorId)) {
            mostrarValidacionLoaderClose("Debe ingresar un organizador");
            return false;
        }
        organizadorDesc = select2.obtenerText("cboOrganizador");
    }
    var bienId = select2.obtenerValor("cboBien");
    if (isEmpty(bienId)) {
        mostrarValidacionLoaderClose("Debe ingresar un bien");
        return false;
    }
    var cantidad = document.getElementById("txtCantidad").value;
    if (isEmpty(cantidad) || !esNumero(cantidad) || cantidad <=0) {
        mostrarValidacionLoaderClose("Debe ingresar una cantidad válida");
        return false;
    }
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida");
    if (isEmpty(unidadMedidaId)) {
        mostrarValidacionLoaderClose("Debe ingresar una unidad de medida");
        return false;
    }
    var precio = document.getElementById("txtPrecio").value;
    if (isEmpty(precio) || !esNumero(precio) || precio < 0) {
        mostrarValidacionLoaderClose("Debe ingresar un precio válido");
        return false;
    }
    
    var bienDesc = select2.obtenerText("cboBien");
    var unidadMedidaDesc = select2.obtenerText("cboUnidadMedida");
    return {
        organizadorId: organizadorId,
        bienId: bienId,
        cantidad: cantidad,
        unidadMedidaId: unidadMedidaId,
        precio: precio,
        organizadorDesc: organizadorDesc,
        bienDesc: bienDesc,
        unidadMedidaDesc: unidadMedidaDesc
    };
}

function confirmarControlador(){
    // obtenerStockAControlar
    if (validarOrganizador){
        ax.setAccion("obtenerStockAControlar");
        ax.addParamTmp("organizadorId", valoresFormularioDetalle.organizadorId);
        ax.addParamTmp("unidadMedidaId", valoresFormularioDetalle.unidadMedidaId);
        ax.addParamTmp("bienId", valoresFormularioDetalle.bienId);
        ax.addParamTmp("cantidad", valoresFormularioDetalle.cantidad);
        ax.consumir();
    }else{
        confirmacionRedirecciona();
    }
}
function confirmacionRedirecciona(){
    $('#cargarBuscadorDocumentoACopiar').removeAttr("onclick");
    if (valoresFormularioDetalle.accion === "agregar"){
        agregarConfirmado();
    }else if (valoresFormularioDetalle.accion === "editar"){
        editarConfirmado();
    }
    // asigno el importe
    asignarImporteDocumento();
    loaderClose();
}
var valoresFormularioDetalle;
function agregar() {
    indexDetalleEditar = null;
    valoresFormularioDetalle = validarFormularioDetalle();
    if (!valoresFormularioDetalle)
        return;
    var existeDetalle = false;
    // validamos si existe un registro similar
    $.each(detalle, function (i, item) {
        if ((!validarOrganizador || parseInt(item.organizadorId) === parseInt(valoresFormularioDetalle.organizadorId)) &&
                parseInt(item.bienId) === parseInt(valoresFormularioDetalle.bienId) &&
                parseInt(item.unidadMedidaId) === parseInt(valoresFormularioDetalle.unidadMedidaId)) {
            confirmarEdicion(item.index);
            existeDetalle = true;
            return false;
        }
    });
    if (existeDetalle)
        return;
    
    valoresFormularioDetalle.accion = "agregar";
    confirmarControlador();
}
function agregarConfirmado(){
    var subTotal = formatearNumero(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);
    valoresFormularioDetalle.subTotal = subTotal;
//    breakFunction();
//    valoresFormularioDetalle.cantidad = formatearNumero(valoresFormularioDetalle.cantidad);
//    valoresFormularioDetalle.precio = formatearNumero(valoresFormularioDetalle.precio);
    indexDetalle += 1;
    valoresFormularioDetalle.index = indexDetalle;
    detalle.push(valoresFormularioDetalle);
    
    if(valoresFormularioDetalle.organizadorDesc==null){
        valoresFormularioDetalle.organizadorDesc='';
    }
    
    $("#dgDetalle").append("<tr id='fila_" + indexDetalle + "'>" +
            "<td id='colOrganizador_" + indexDetalle + "' style='text-align:right;'>" + valoresFormularioDetalle.organizadorDesc + "</th>" +
            "<td id='colCantidad_" + indexDetalle + "' style='text-align:right;'>" + formatearNumero(valoresFormularioDetalle.cantidad) + "</th>" +
            "<td id='colUnidadMedida_" + indexDetalle + "' style='text-align:left;'>" + valoresFormularioDetalle.unidadMedidaDesc + "</th>" +
            "<td id='colBien_" + indexDetalle + "' style='text-align:left;'>" + valoresFormularioDetalle.bienDesc + "</th> " +
            "<td id='colPrecio_" + indexDetalle + "' style='text-align:right;'>" + formatearNumero(valoresFormularioDetalle.precio) + "</th> " +
            "<td id='colSubTotal_" + indexDetalle + "' style='text-align:right;'>" + subTotal + "</th> " +
            '<td style="text-align:center;"><a title="Editar" onclick="prepararEdicion(' + indexDetalle + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></b></b></a><b><b>&nbsp' +
            '<a title="Eliminar" onclick="confirmarEliminar(' + indexDetalle + ')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></b></b></a></b></b></td>' +
            "</tr>");
    limpiarFormularioDetalle();
    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height()+ 40);
    loaderClose();
}
function prepararEdicion(index) {
    loaderShow();
    var existeDetalle = false;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(index)) {
            select2.asignarValor("cboOrganizador", item.organizadorId);
            select2.asignarValor("cboBien", item.bienId);
            obtenerUnidadMedida(item.bienId);
            select2.asignarValor("cboUnidadMedida", item.unidadMedidaId);
            document.getElementById("txtCantidad").value = item.cantidad;
            document.getElementById("txtPrecio").value = item.precio;
            existeDetalle = true;
            return false;
        }
    });
    if (!existeDetalle) {
        mostrarValidacionLoaderClose("No existe data para editar");
    } else {
        indexDetalleEditar = index;
        loaderClose();
    }
}
function editar() {
    valoresFormularioDetalle = validarFormularioDetalle();
    if (!valoresFormularioDetalle)
        return;
    
    if (isEmpty(detalle) || isEmpty(indexDetalleEditar)) {
        indexDetalleEditar = null;
        mostrarValidacionLoaderClose("No se ha encontrado data para editar");
        return;
    }
    valoresFormularioDetalle.accion = "editar";
    confirmarControlador();
}
function editarConfirmado(){
    var indexTemporal = null;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indexDetalleEditar)) {
            indexTemporal = i;
            return false;
        }
    });
    if (isEmpty(indexTemporal)) {
        indexDetalleEditar = null;
        mostrarValidacionLoaderClose("No se ha encontrado data para editar");
        return;
    }
    var subTotal = formatearNumero(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);
//    valoresFormularioDetalle.cantidad = formatearNumero(valoresFormularioDetalle.cantidad);
//    valoresFormularioDetalle.precio = formatearNumero(valoresFormularioDetalle.precio);
    valoresFormularioDetalle.index = indexDetalleEditar;
    detalle[indexTemporal] = valoresFormularioDetalle;
    $("#colCantidad_" + indexDetalleEditar).html(formatearNumero(valoresFormularioDetalle.cantidad));
    $("#colUnidadMedida_" + indexDetalleEditar).html(valoresFormularioDetalle.unidadMedidaDesc);
    $("#colBien_" + indexDetalleEditar).html(valoresFormularioDetalle.bienDesc);
    $("#colPrecio_" + indexDetalleEditar).html(formatearNumero(valoresFormularioDetalle.precio));
    $("#colSubTotal_" + indexDetalleEditar).html(subTotal);
    indexDetalleEditar = null;
    limpiarFormularioDetalle();
    loaderClose();
}
function eliminar(index) {
    loaderShow();
    if (isEmpty(detalle) || isEmpty(index)) {
        mostrarValidacionLoaderClose("No se ha encontrado data para eliminar");
        return;
    }
    var indexTemporal = null;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(index)) {
            indexTemporal = i;
            return false;
        }
    });
    if (isEmpty(indexTemporal)) {
        mostrarValidacionLoaderClose("No se ha encontrado data para eliminar");
        return;
    }

    if (indexTemporal > -1) {
        detalle.splice(indexTemporal, 1);
    }
    $("#fila_" + index).remove();
    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height()- 40);
    loaderClose();
}
function confirmarEliminar(index) {
    swal({
        title: "¿Está seguro que desea eliminar?",
        text: "Una vez eliminado tendrá que seleccionar nuevamente todo el registro si desea volver agreagarlo",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminar(index);
        }
    });
}
function confirmarEdicion(index) {
    loaderClose();
    swal({
        title: "¿Está seguro que desea editar?",
        text: "Ya existe un detalle similar al que desea agregar",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, editar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            indexDetalleEditar = index;
            editar();
        }
    });
}
function limpiarFormularioDetalle() {
    document.getElementById("txtCantidad").value = '1.00';
    document.getElementById("txtPrecio").value = '';
    select2.asignarValor('cboBien', '');
}
function cargarPantallaListar()
{
    cargarDiv("#window", "vistas/com/movimiento/movimiento_listar.php");
}
function enviar() {
    guardar("enviar");
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
                var numero = document.getElementById("txt_" + item.id).value;
                if (isEmpty(numero)){
                    if (item.opcional == 0){
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }else{
                    if (!esNumero(numero)){
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
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
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
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
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


function onResponsegetAllPersonaTipo(data)
{
//    $('#listaPersonaTipo').empty();
    if (!isEmpty(data))
    {
        $.each(data, function (index, value) {
            $('#listaPersonaTipo').append('<li><a data-toggle="modal" data-target="#accordion-modal" onclick="cargarForm(' + value.id + ',\'' + value.descripcion + '\',\'' + value.ruta + '\')">' + value.descripcion + '</a></li>');
        });
    }
}
function cargarForm(id, valor_persona_tipo, ruta)
{
//    var iframe;
    $("#respuesta").load('http://' + location.host + '/almacen/' + ruta);
//    iframe = '<iframe src="http://' + location.host + '/almacen/' + ruta + '" style="width:100%;height:100%;border:0"</iframe>';
//    document.getElementById('respuesta').innerHTML = iframe;
}

function enviarEImprimir()
{
    guardar("enviarEImprimir");
}
/*function guardar(accion){
    loaderShow();
    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }
    // validamos los importes que esten llenos
    if (!validarImportesLlenos()) {
        return;
    }
    //Validar y obtener valores de los campos dinamicos
    if (!obtenerValoresCamposDinamicos())
        return;
    ax.setAccion(accion);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalle", detalle);
    ax.consumir();
}*/

function guardar(accion) {
    loaderShow();
    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
        return;
    }
    // validamos los importes que esten llenos
    if (!validarImportesLlenos()) {
        return;
    }
    //Validar y obtener valores de los campos dinamicos
    if (!obtenerValoresCamposDinamicos())
        return;

//    if (isEmpty(detalle))
//        mostrarAdvertencia("Falta ingresar datos.");
//        loaderClose();
//        return;
    obtenerCheckDocumentoACopiar();

    ax.setAccion(accion);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("documentoARelacionar", arrayDocumentoARelacionarIds);
    ax.addParamTmp("valor_check", checkActivo);
    ax.consumir();
}

function cargarPersona()
{
    var rutaAbsoluta = URL_BASE + 'index.php?token=1';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
//    var win = window.open(rutaAbsoluta, '_blank');
//    win.focus();
}

function loaderComboPersona()
{
    getAllPersona();
}

function getAllPersona()
{
    var documneto_tipo = document.getElementById('cboDocumentoTipo').value;
    ax.setAccion("getAllPersona");
    ax.addParamTmp("documentoTipoId", documneto_tipo);
    ax.consumir();
}
function onResponseGetAllPersona(data)
{
    $("#div_persona").empty();
    var header = '';
    var string = '';
    var footer = '';
    var html = '';

    $.each(data, function (index, item) {
        switch (parseInt(item.tipo)) {
            case 5:
                header = '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                string = '<option value="' + 0 + '">Seleccione la persona</option>';
                $.each(item.data, function (indexPersona, itemPersona) {
                    string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                });
                footer = '</select>';
                html = header + string + footer;
                $("#div_persona").append(html);
                break;
        }

        switch (item.tipo) {
            case 5, "5":
                $("#cbo_" + item.id).select2({
                    width: '100%'
                });
                break;
        }
    });
}
function loaderComboBien()
{
    getAllLoaderBien();
}
function getAllLoaderBien()
{
    ax.setAccion("getAllLoaderBien");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function onResponseLoaderBien(data)
{
    if (!isEmpty(data)) {
        select2.recargar("cboBien", data, "id", ["codigo","descripcion"]);
        select2.asignarValor("cboBien", 0);
    }
}
function cargarBien()
{
    var rutaAbsoluta = URL_BASE + 'index.php?token=2';
//    var win = window.open(rutaAbsoluta, '_blank');
//    win.focus();
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function verificarStockBien()
{
    var bienId = select2.obtenerValor("cboBien");
    if (bienId == null)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un bien");
    } else
    {
        //alert(bienId);
        obtenerStockPorBien(bienId);
    }
}
function obtenerStockPorBien(bienId)
{
    ax.setAccion("obtenerStockPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}
var stringResumenStockBien;
function onResponseStockBien(data)
{
    stringResumenStockBien = '';
//    $('#div_resumenStock').empty();
    $('#datatableStock').empty();
    var stringTituloStock = '<strong>' + select2.obtenerText("cboBien") + '</strong>';
        $('#datatableStock').dataTable({
        order: [[0, "desc"]],
        "ordering": false,
        "data": data.stockBien,
        "columns": [
            {"data": "organizador_descripcion"},
            {"data": "unidad_medida_descripcion"},
            {"data": "stock", "sClass": "alignRight"}
        ],
        "destroy": true
    });
//    var title = '<i><strong>Resumen:</strong></i><br>';
    
//    if(!isEmpty(data.resumenStockBien))
//    {
//        $.each(data.resumenStockBien, function (index, item) {
//        stringResumenStockBien += item.cantidad + ' ' + item.unidad_descripcion + ', ';
//        });
//    }
//    
//    stringResumenStockBien = stringResumenStockBien.substring(0, stringResumenStockBien.length - 2);
//     $('#div_resumenStock').append(stringResumenStockBien);

    $('.modal-title').empty();
    $('.modal-title').append(stringTituloStock);
    $('#modalStockBien').modal('show');
}
function calcularImporteDetalle(){
    var importe = 0;
    if(!isEmpty(detalle)){
        $.each(detalle, function(index, item){
            importe+=(item.cantidad * item.precio);
        });
    }
    return redondearNumero(importe);
}
var igvValor = 0.18;
function asignarImporteDocumento(){
    var calculo, igv;
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)){
        calculo  = calcularImporteDetalle();
        document.getElementById(importes.calculoId).value = calculo;
        if (importes.calculoId === importes.subTotalId){
            if (!isEmpty(importes.igvId)){
                igv = redondearNumero(igvValor * calculo);
                document.getElementById(importes.igvId).value = igv;
            }
            if (!isEmpty(importes.totalId)){
                document.getElementById(importes.totalId).value = redondearNumero(calculo + igv);
            }
        } else if (importes.calculoId === importes.totalId){
            if (!isEmpty(importes.igvId)){
                igv = redondearNumero(calculo - calculo / (1+igvValor));
                document.getElementById(importes.igvId).value = igv;
            }
            if (!isEmpty(importes.subTotalId)){
                document.getElementById(importes.subTotalId).value = redondearNumero(calculo - igv);
            }
        }
    }
}
function validarImportesLlenos(){
//    breakFunction();
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)){
        var importeFinal = document.getElementById(importes.calculoId).value;
        if (isEmpty(importeFinal)){
            asignarImporteDocumento();
            importeFinal = document.getElementById(importes.calculoId).value;
        }
        var importeFinalSugerido = calcularImporteDetalle();
        if (Math.abs(importeFinalSugerido-importeFinal)>1){
            mostrarValidacionLoaderClose("El importe total tiene mucha variación con el cálculado por el sistema. No se puede continuar la operación.");
            return false;
        }
    }
    return true;
}
function onChangeCheckIncluyeIGV(){
    validarImporteLlenar();
    asignarImporteDocumento();
}
function validarImporteLlenar(){
    if (!isEmpty(importes.subTotalId)){
        if (document.getElementById('chkIncluyeIGV').checked){
            importes.calculoId = importes.totalId;
        } else {
            importes.calculoId = importes.subTotalId;
        }
    }else{
        importes.calculoId = importes.totalId;
    }
}

function verificarPrecioBien()
{
    var bienId = select2.obtenerValor("cboBien");
    if (bienId == null)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un bien");
    } else
    {
        obtenerPrecioPorBien(bienId);
    }
}

function obtenerPrecioPorBien(bienId)
{
    ax.setAccion("obtenerPrecioPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.consumir();
}
function onResponsePrecioBien(data)
{
    stringResumenStockBien = '';
//    $('#div_resumenStock').empty();
//    $('#datatableStock').empty();
    var stringTituloStock = '<strong>' + select2.obtenerText("cboBien") + '</strong>';
        $('#datatablePrecio').dataTable({
        order: [[0, "desc"]],
        "ordering": false,
        "data": data,
        "columns": [
            {"data": "precio_tipo_descripcion"},
            {"data": "precio", "sClass": "alignRight"}
        ],
        "destroy": true
    });

    $('.modal-title').empty();
    $('.modal-title').append(stringTituloStock);
    $('#modalPrecioBien').modal('show');
}
function onChangeCheckIGV (){
    if (document.getElementById('chkIGV').checked){
        igvValor = 0.18;
    } else {
        igvValor = 0;
    }
    asignarImporteDocumento();
}

//Area de Opcion de Copiar Documento


function cargarBuscadorDocumentoACopiar()
{
    loaderShow();
    obtenerConfiguracionesInicialesBuscadorCopiaDocumento();

}

function obtenerConfiguracionesInicialesBuscadorCopiaDocumento()
{
    ax.setAccion("ConfiguracionesBuscadorCopiaDocumento");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function OnResponseConfiguracionesBuscadorCopiaDocumento(data)
{
    $("#cboDocumentoTipoM").select2({
        width: "100%"
    });
    select2.cargar("cboDocumentoTipoM", data.documento_tipo, "id", "descripcion");
    $("#cboPersonaM").select2({
        width: "100%"
    });
    select2.cargar("cboPersonaM", data.persona, "id", "nombre");

    var table = $('#datatableModalDocumentoACopiar').DataTable();
    table.clear().draw();

    $('#modalBusquedaDocumentoACopiar').modal('show');
}

function buscarDocumentoACopiar(colapsa) {
    loaderShow();
    var cadena;
    //alert('hola');
    cadena = obtenerDatosBusquedaDocumentoACopiar();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    getDataTableDocumentoACopiar();
    if (colapsa === 1)
        colapsarBuscadorCopiaDocumento();
}

function obtenerDatosBusquedaDocumentoACopiar()
{
    var cadena = "";
    obtenerParametrosBusquedaDocumentoACopiar();

    if (!isEmpty(parametrosBusquedaDocumentoACopiar.documento_tipo_ids))
    {
        cadena += negrita("Documento: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipoM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.persona_id))
    {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerTextMultiple('cboPersonaM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.serie))
    {
        cadena += negrita("Serie: ");
        cadena += $('#txtSerie').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.numero))
    {
        cadena += negrita("Numero: ");
        cadena += $('#txtNumero').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_emision_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_emision_fin;
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin))
    {
        cadena += negrita("Fecha vencimiento: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin;
        cadena += "<br>";
    }
    return cadena;
}

function getDataTableDocumentoACopiar()
{
    ax.setAccion("buscarDocumentoACopiar");
    ax.addParamTmp("criterios", parametrosBusquedaDocumentoACopiar);    
    ax.addParamTmp("empresa_id", commonVars.empresa);
    //alert('hola');
    $('#datatableModalDocumentoACopiar').dataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[1, "desc"]],
        "columns": [
            {data: "flechas",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a onclick="agregarDocumentoACopiar(' + row.documento_tipo_id + ',' + row.documento_id + ',' + row.movimiento_id + ')"><b><i class="fa fa-arrow-down" style = "color:#04B404;" tooltip-btndata-toggle="tooltip" title="Agregar y cerrar"><b></a>';
                    }
                    return data;
                },
                "orderable": false,
                "class": "alignCenter",
                "width": "10px"
            },
            {"data": "fecha_creacion", "width": "150px"},
            {"data": "fecha_emision", "width": "40px"},
            {"data": "documento_tipo", "width": "150px"},
            {"data": "persona", "width": "250px"},
            {"data": "serie", "width": "40px"},
            {"data": "numero", "width": "50px"},
            {"data": "fecha_vencimiento", "width": "40px"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 7
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    loaderClose();

}

var parametrosBusquedaDocumentoACopiar = {
    empresa_id: null,
    documento_tipo_ids: null,
    persona_id: null,
    serie: null,
    numero: null,
    fecha_emision_inicio: null,
    fecha_emision_fin: null,
    fecha_vencimiento_inicio: null,
    fecha_vencimiento_fin: null
};
function obtenerParametrosBusquedaDocumentoACopiar()
{
    parametrosBusquedaDocumentoACopiar.empresa_id = commonVars.empresa;
    parametrosBusquedaDocumentoACopiar.documento_tipo_ids = $('#cboDocumentoTipoM').val();

    var personaId = $('#cboPersonaM').val();
    if (!isEmpty(personaId))
    {
        parametrosBusquedaDocumentoACopiar.persona_id = personaId[0];
    }

    parametrosBusquedaDocumentoACopiar.serie = $('#txtSerie').val();
    parametrosBusquedaDocumentoACopiar.numero = $('#txtNumero').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = $('#dpFechaEmisionInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_fin = $('#dpFechaEmisionFin').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio = $('#dpFechaVencimientoInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin = $('#dpFechaVencimientoFin').val();
}

function agregarDocumentoACopiar(documentoTipoOrigenId, documentoId, movimientoId)
{
    loaderShow("#modalBusquedaDocumentoACopiar");
    if (validarDocumentoACopiarRepetido(documentoId))
    {
        mostrarAdvertencia("Documento a copiar ya a sido agregado");
        loaderClose();
        return;
    }

    var_documentoId = documentoId;
    var_movimientoId = movimientoId;

    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDetalleDocumentoACopiar");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", arrayDocumentoARelacionarIds);
    ax.consumir();
}

function validarDocumentoACopiarRepetido(documentoACopiarId)
{
    var resultado = false;
    $.each(arrayDocumentoARelacionarIds, function (index, item) {
        if (!isEmpty(item.documentoId))
        {
            if (item.documentoId === documentoACopiarId)
            {
                resultado = true;
            }
        }

    });

    return resultado;
}

function OnResponseObtenerDetalleDocumentoACopiar(data) {

    detalle = [];
    breakFunction();
    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height() - (40 * indexDetalle));
    indexDetalle = 0;
    cargarDataDocumentoACopiar(data.dataDocumento, data.dataDocumentoRelacionada);
    cargarDetalleDocumentoACopiar(data.detalleDocumento);

    $('#modalBusquedaDocumentoACopiar').modal('hide');

}

var contadorDocumentoCopiadoAVisualizar = 0;
function cargarDataDocumentoACopiar(data, documentoTipoDatacopia)
{
    var documentoTipo = "", serie = "", numero = "";
    if (banderaDatoDocumentoCopiar === 0)
    {
        if (!isEmpty(data))
        {

            $.each(data, function (index, item) {

                switch (parseInt(item.tipo)) {
                    case 5:
                        select2.asignarValor('cbo_' + item.otro_documento_id, item.valor);
                        break;
                    case 6:
//                    case 7:
//                    case 8:
                        $('#txt_' + item.otro_documento_id).val(item.valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        $('#datepicker_' + item.otro_documento_id).val(formatearFechaJS(item.valor));
                        break;
                }
            });
            banderaDatoDocumentoCopiar = 1;

        }

        if (!isEmpty(documentoTipoDatacopia))
        {
            $.each(documentoTipoDatacopia, function (index, item) {
                if (isEmpty(item.documento_tipo_dato_origen))
                {
                    select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                }
                else
                {
                    $('#txt_' + item.documento_tipo_dato_destino).val(item.valor);
                }
            });
        }
    }

    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {

            documentoTipo = item.documento_tipo_descripcion;

            switch (parseInt(item.tipo)) {
                case 7:
                    if (!isEmpty(item.valor))
                    {
                        serie = item.valor;
                    }

                    break;
                case 8:
                    if (!isEmpty(item.valor))
                    {
                        numero = item.valor;
                    }
                    break;
            }
        });

        detalleLink = documentoTipo + ": " + serie + " - " + numero;
    }
}

function cargarDetalleDocumentoACopiar(data)
{

    var banderaMostrarModal = 0;
    $('#contenedorAsignarStockXOrganizador').empty();
    $('#dgDetalle').empty();
    dataFaltaAsignarCantidadXOrganizador = null;
    dataFaltaAsignarCantidadXOrganizador = [];

    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            
            cargarDataTableDocumentoACopiar(
                        cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad, item.unidad_medida_id,
                                item.valor_monetario, item.organizador_descripcion, item.bien_descripcion, item.unidad_medida_descripcion)
                        );



            /*if (!isEmpty(item.organizador_id) && isEmpty(item.stock_organizadores))
            {
                cargarDataTableDocumentoACopiar(
                        cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad, item.unidad_medida_id,
                                item.valor_monetario, item.organizador_descripcion, item.bien_descripcion, item.unidad_medida_descripcion)
                        );
            }
            else
            {
                banderaMostrarModal = 1;
                cargarModalParaAgregarOrganizador(item);
            }*/
        });
    }

    if (banderaMostrarModal === 1)
    {
        $('#modalAsignarOrganizador').modal('show');
    }

}

var dataFaltaAsignarCantidadXOrganizador = [];
function cargarModalParaAgregarOrganizador(data)
{
    var stockOrganizadores;
    dataFaltaAsignarCantidadXOrganizador.push(data);
    var html = '<div>';
    html += '<p id="titulo_' + data.bien_id + '">' + data.bien_descripcion + ' : ' + data.cantidad + ' ' + data.unidad_medida_descripcion + '</p>';
    html += '</div>';

    if (isEmpty(data.stock_organizadores))
    {
        html += '<p style="color:red;">No hay stock para este bien.</p>';
    }
    else
    {
        html += '<div class="table">';
        html += '<table id="datatableStock_' + data.bien_id + '" class="table table-striped table-bordered">';
        html += '<thead>';
        html += '<tr>';
        html += '<th style="text-align:center;">Organizador</th>';
        html += '<th style="text-align:center;">Disponible</th>';
        html += '<th style="text-align:center;">A usar</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';

        stockOrganizadores = obtenerstockPredefinido(data.stock_organizadores, data.cantidad);
        $.each(data.stock_organizadores, function (index, item) {

            html += '<tr>';
            html += '<td >' + item.organizadorDescripcion + '</td>';
            html += '<td >' + formatearCantidad(item.stock) + '</td>';
            html += '<td >';
            html += '<input type="number" min="0" id="txt_' + item.organizadorId + '_' + data.bien_id + '" style="text-align: right;" ';
            html += ' value="' + obtenerStockPredefinidoXOrganizador(item.organizadorId, stockOrganizadores) + '">';
            html += '</td>';
            html += '</tr>';
        });


        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    }


    $('#contenedorAsignarStockXOrganizador').append(html);

    $('#datatableStock_' + data.bien_id).dataTable({
        "columns": [
            {"width": "10px"},
            {"width": "10px", "sClass": "alignRight"},
            {"width": "10px", "sClass": "alignCenter"}
        ],
        "dom": '<"top">rt<"bottom"><"clear">',
        "order": [[1, "desc"]]
    });
}

function obtenerStockPredefinidoXOrganizador(organizadorId, stockOrganizadores)
{
    var stock = "";
    $.each(stockOrganizadores, function (index, item) {
        if (item.organizadorId == organizadorId)
        {
            stock = formatearCantidad(item.asignado);
        }
    });

    return stock;
}
function obtenerstockPredefinido(data, stockDeseado)
{
    var array = [];
//    var organizadores = [];
    $.each(data, function (index, item) {
        array.push({organizadorId: item.organizadorId,
            stock: item.stock,
            asignado: 0});
    });

    array = ordenacionBurbuja(array);

    $.each(array, function (index, item) {
        if (parseFloat(stockDeseado) > parseFloat(item.stock))
        {
            array[index]['asignado'] = item.stock;
            stockDeseado = stockDeseado - item.stock;

        }
        else
        {
            array[index]['asignado'] = stockDeseado;
            stockDeseado = 0;
        }
    });

    return array;

}

function ordenacionBurbuja(array) {

    var tamanio = array.length;
    var i, j;
    var aux;
    for (i = 0; i < tamanio; i++)
    {
        for (j = 0; j < (tamanio - 1); j++)
        {
            if (array[j].stock < array[j + 1].stock)
            {
                aux = array[j];
                array[j] = array[j + 1];
                array[j + 1] = aux;
            }
        }

    }

    return array;
}

function asignarStockXOrganizador()
{
    var suma = 0;
    var valorStockUnitario;
    var organizadorUsado = [];

    var listaDetalleDocumentoACopiar = [];
    var banderaSalirEach = 0;

    $.each(dataFaltaAsignarCantidadXOrganizador, function (index, itemData) {
        if (banderaSalirEach === 0)
        {
            if (!isEmpty(itemData.stock_organizadores))
            {
                $.each(itemData.stock_organizadores, function (index1, item) {

                    valorStockUnitario = $('#txt_' + item.organizadorId + '_' + itemData.bien_id).val();
                    if (!isEmpty(valorStockUnitario))
                    {
                        if (valorStockUnitario < 0)
                        {
                            mostrarAdvertencia("El valor a usar es menor que cero para el bien " + itemData.bien_descripcion + " en el organizador " + item.organizadorDescripcion);
                            banderaSalirEach = 1;
                        }
                        else
                        {
                            if (parseFloat(valorStockUnitario) > parseFloat(itemData.cantidad))
                            {
                                mostrarAdvertencia("El valor a usar es mayor al requerido para el bien " + itemData.bien_descripcion);
                                banderaSalirEach = 1;
                            }
                            else
                            {
                                if (parseFloat(valorStockUnitario) > parseFloat(item.stock))
                                {
                                    mostrarAdvertencia("El valor a usar es mayor que el stock para el bien " + itemData.bien_descripcion);
                                    banderaSalirEach = 1;
                                }
                                else
                                {
                                    if (valorStockUnitario > 0)
                                    {
                                        suma = parseFloat(suma) + parseFloat(valorStockUnitario);
                                        organizadorUsado.push({
                                            organizadorDescripcion: item.organizadorDescripcion,
                                            organizadorId: item.organizadorId,
                                            usado: valorStockUnitario
                                        });
                                    }
                                }
                            }
                        }
                    }

                });
            }
            if (banderaSalirEach === 0)
            {
                if (parseFloat(suma) > 0 && parseFloat(suma) <= itemData.cantidad)
                {

                    $.each(organizadorUsado, function (index2, itemOrganizadorUsado) {
                        listaDetalleDocumentoACopiar.push(
                                cargarFormularioDetalleACopiar(itemOrganizadorUsado.organizadorId, itemData.bien_id, itemOrganizadorUsado.usado,
                                        itemData.unidad_medida_id, itemData.valor_monetario, itemOrganizadorUsado.organizadorDescripcion,
                                        itemData.bien_descripcion, itemData.unidad_medida_descripcion)
                                );
                    });
                }
                else
                {
                    if (!isEmpty(itemData.stock_organizadores))
                    {
                        mostrarAdvertencia("Los valores ingresados no son correctos para el bien " + itemData.bien_descripcion);
                        banderaSalirEach = 1;
                    }

                }
            }

            organizadorUsado = [];
            suma = 0;

//            }
        }
    });

    if (banderaSalirEach === 0)
    {
        $.each(listaDetalleDocumentoACopiar, function (index, item) {
            cargarDataTableDocumentoACopiar(item);
        });

        listaDetalleDocumentoACopiar = [];

        if (banderaSalirEach === 0)
        {
            $('#modalAsignarOrganizador').modal('hide');

        }
    }


}

function cargarFormularioDetalleACopiar(organizadorId, bienId, cantidad, unidadMedidaId, precio, organizadorDesc, bienDesc, unidadMedidaDesc) {
    return {
        organizadorId: organizadorId,
        bienId: bienId,
        cantidad: cantidad,
        unidadMedidaId: unidadMedidaId,
        precio: precio,
        organizadorDesc: organizadorDesc,
        bienDesc: bienDesc,
        unidadMedidaDesc: unidadMedidaDesc
    };
}

function cargarDataTableDocumentoACopiar(data)
{
    if (!isEmpty(data))
    {

        if (!isEmpty(var_documentoId) && !isEmpty(var_movimientoId))
        {
            arrayDocumentoARelacionarIds.push({
                documentoId: var_documentoId,
                movimientoId: var_movimientoId
            });

            var_documentoId = null;
            var_movimientoId = null;
        }

        if (!isEmpty(detalleLink))
        {
            if (banderaDatoDocumentoCopiar === 1)
            {
                $('#DivDocumentoACopiar').show();
            }

            $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
            $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a><br>");
            $("#contenedorDocumentoTipo").css("height", $("#contenedorDocumentoTipo").height() + 15);

            arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleLink;
            arrayDocumentoARelacionarIds[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
            contadorDocumentoCopiadoAVisualizar++;
            detalleLink = null;
        }

        valoresFormularioDetalle = data;
        agregarConfirmado();
        asignarImporteDocumento();
    }
}

function eliminarDocumentoACopiar(indice)
{
    loaderShow();
    var banderaExisteDocumentoRelacionado = 0;
    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height() - (40 * indexDetalle));
    indexDetalle = 0;
    arrayDocumentoARelacionarIds[indice].documentoId = null;
    arrayDocumentoARelacionarIds[indice].movimientoId = null;
    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    $.each(arrayDocumentoARelacionarIds, function (index, item) {

        if (!isEmpty(item.documentoId))
        {
            $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoACopiar(" + item.posicion + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "]</a>");
            $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiar(" + item.posicion + ")'style='color:red;'>&ensp;X</a><br>");
            banderaExisteDocumentoRelacionado = 1;
        }
    });

    if (banderaExisteDocumentoRelacionado === 0)
    {
        banderaDatoDocumentoCopiar = 0;
        $('#DivDocumentoACopiar').hide();
    }

    ax.setAccion("obtenerDetalleDocumentoACopiarSoloDetalle");
    ax.addParamTmp("documentos_relacionados", arrayDocumentoARelacionarIds);
    ax.consumir();

}


function visualizarDocumentoACopiar(indice)
{
    if (!isEmpty(arrayDocumentoARelacionarIds[indice].documentoId) && !isEmpty(arrayDocumentoARelacionarIds[indice].movimientoId))
    {
        ax.setAccion("visualizarDocumento");
        ax.addParamTmp("documento_id", arrayDocumentoARelacionarIds[indice].documentoId);
        ax.addParamTmp("movimiento_id", arrayDocumentoARelacionarIds[indice].movimientoId);
        ax.consumir();
    }
}

function onResponseVisualizarDocumento(data)
{
    cargarDataDocumento(data.dataDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    $("#formularioDetalleDocumento").css("height", 75 * data.length);


    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12">' +
                    '<label>' + item.descripcion + '</label>' +
                    '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';

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
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '<input type="text" class="form-control" readonly="true" value="' + valor + '"/>';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
                {"data": "precioUnitario", "sClass": "alignRight"},
                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function obtenerCheckDocumentoACopiar()
{
    if ($('#chkDocumentoACopiar').attr('checked')) {
        checkActivo = 1;
    }
    else
    {
        checkActivo = 0;
    }
}

function iniciarDataPicker()
{
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}

function cerrarPopover()
{
    if (banderaBuscar == 1)
    {
        if (estadoTooltip == 1)
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
    estadoTooltip = (estadoTooltip == 0) ? 1 : 0;
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}


function colapsarBuscadorCopiaDocumento() {

    if (banderaAbrirModal === 0)
    {
        if (actualizandoBusqueda) {
            actualizandoBusqueda = false;
            return;
        }
        if ($('#bg-infoCopiaDocumento').hasClass('in')) {
            $('#bg-infoCopiaDocumento').attr('aria-expanded', "false");
            $('#bg-infoCopiaDocumento').attr('height', "0px");
            $('#bg-infoCopiaDocumento').removeClass('in');
        }
        else {
            $('#bg-infoCopiaDocumento').attr('aria-expanded', "false");
            $('#bg-infoCopiaDocumento').attr('height', "0px");
            $('#bg-infoCopiaDocumento').addClass('in');
        }
    }
    else
    {
        $('#bg-infoCopiaDocumento').attr('aria-expanded', "false");
        $('#bg-infoCopiaDocumento').attr('height', "0px");
        $('#bg-infoCopiaDocumento').removeClass('in');
        banderaAbrirModal = 0;
    }

}

var actualizandoBusqueda = false;
function actualizarBusquedaCopiaDocumento()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-infoCopiaDocumento').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarDocumentoACopiar(0);
    }
}