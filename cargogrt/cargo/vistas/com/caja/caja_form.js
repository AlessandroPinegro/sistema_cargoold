var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;

var acciones = {
    getComboAgencias: false,
    getCaja: false
};

$(document).ready(function () {
    ax.setSuccess("successAgencia");
    select2.asignarValor("cboTipoDocumento", -1);
    select2.asignarValor("cboTipoDocumentoRelacion", -1);
});

function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}

function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}

function cargarCombo() {

    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getComboAgencias");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo

    //retorna los datos de los usuarios para llenar el combo

    getCaja(VALOR_ID_USUARIO);
}

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function cargarComponentes() {
    cargarSelect2();
}

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

$("#estado").change(function () {
    $('#msj_estado').hide();
});

function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}

function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});

$('#txt_ip').keypress(function () {
    $('#msj_ip').hide();
});

$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

$('#txt_sufijo').keypress(function () {
    $('#msj_sufijo').hide();
});

function onchangeAgencia() {
    $('#msj_colaborador').hide();
}

function limpiar_formulario_usuario() {
    document.getElementById("frm_usuario").reset();
}

function getCaja(id) {
    loaderShow();
    ax.setAccion("getCaja");
    ax.addParamTmp("id_caja", id);
    ax.consumir();
}

function validar_caja_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var caja_nombre = document.getElementById('txt_codigo').value;
    var caja_descripcion = document.getElementById('txt_descripcion').value;
    var caja_sufijo = document.getElementById('txt_sufijo').value;
    var correlativo_inicio = document.getElementById('txt_correlativo').value;
    var id_agencia = document.getElementById('cbo_agencia').value;
    var estado = document.getElementById('estado').value;
    var bandera_virtual = document.getElementById('virtual').value;
     var caja_ip = document.getElementById('txt_ip').value;

    if (caja_nombre == "" || espacio.test(caja_nombre) || caja_nombre.length == 0) {
        $("msj_codigo").removeProp(".hidden");
        $("#msj_codigo").text("Ingresar un codigo").show();
        bandera = false;
    }
    
      if (caja_ip == "" || espacio.test(caja_ip) || caja_ip.length == 0) {
        $("msj_ip").removeProp(".hidden");
        $("#msj_ip").text("Ingresar un punto de venta (IP) a la caja.").show();
        bandera = false;
    }

    if (caja_descripcion == "" || espacio.test(caja_descripcion) || caja_descripcion.length == 0) {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar una Descripcion").show();
        bandera = false;
    }

    if (caja_sufijo == "" || espacio.test(caja_sufijo) || caja_sufijo.length == 0) {
        $("msj_sufijo").removeProp(".hidden");
        $("#msj_sufijo").text("Ingresar un Sufijo Comprobante").show();
        bandera = false;
    }

    if (correlativo_inicio == "" || espacio.test(correlativo_inicio) || correlativo_inicio.length == 0) {
        $("msj_correlativo").removeProp(".hidden");
        $("#msj_correlativo").text("Ingresar un Sufijo Comprobante").show();
        bandera = false;
    }

    if (id_agencia == "" || id_agencia == null || espacio.test(id_agencia) || id_agencia.length == 0) {
        $("msj_perfil").removeProp(".hidden");
        $("#msj_perfil").text("Ingresar el nombre de una agencia valida").show();
        bandera = false;
    }

    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null) {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }

    if (bandera_virtual == "" || espacio.test(bandera_virtual) || bandera_virtual.lenght == 0 || bandera_virtual == null) {
        $("msj_virtual").removeProp(".hidden");
        $("#msj_virtual").text("Seleccionar si la caja es virtual o no").show();
        bandera = false;
    } 

    return bandera;
}

function cargarDatagrid() {
    ax.setAccion("getDataGridUsuario");
    ax.consumir();
}

function listarUsuarios() {
    ax.setSuccess("successAgencia");
    cargarDatagrid();
}

function successAgencia(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertCaja':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Agencia guardada correctamente.");
                break;

            case 'updateCaja':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Caja actualizada correctamente.");
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertCaja':
                habilitarBoton();
                loaderClose();
                break;

            case 'updateCaja':
                habilitarBoton();
                loaderClose();
                break;
        }
    }
}

$("#cboTipoDocumento").change(function () {
    debugger;
    $('#txt_Serie').val('');
    $('#txt_Correlativo').val('');
    var TipoDocumento = select2.obtenerValor('cboTipoDocumento');
    if (TipoDocumento == "284" || TipoDocumento == "61"  || TipoDocumento == "269") {
        $("#divTipoDocumentoRelacion").removeClass('hidden');
    } else {
        $("#divTipoDocumentoRelacion").addClass('hidden');
    }
});

function cajaCargarData() {
    if (acciones.getComboAgencias && acciones.getCaja) {
        if (!isEmpty(VALOR_ID_USUARIO)) {
            //                llenarFormularioEditar(dataPorId);
            var data_caja = dataPorId.data;
            var data_correlativo = dataPorId.correlativo;

            document.getElementById('txt_codigo').value = data_caja['0']['codigo'];
            document.getElementById('txt_descripcion').value = data_caja['0']['descripcion'];
            document.getElementById('txt_sufijo').value = data_caja['0']['sufijo'];
            document.getElementById('txt_correlativo').value = data_caja['0']['correlativo'];
            document.getElementById('txt_ip').value = data_caja['0']['ip_caja'];

            document.getElementById('estado').value = data_caja['0']['estado'];

            document.getElementById('virtual').value = data_caja['0']['bandera_virtual'];

            asignarValorSelect2("cbo_agencia", data_caja['0']['agencia_id']);

            asignarValorSelect2("estado", data_caja['0']['estado']);

            asignarValorSelect2("virtual", data_caja['0']['bandera_virtual']);

            $(data_correlativo).each(function (index) {
                //ids
                var correlativo_id = data_correlativo[index].correlativo_id;

                //texto
                var documento_descripcion = data_correlativo[index].documento_descripcion;
                var serie = data_correlativo[index].serie;
                var correlativo_inicio = data_correlativo[index].correlativo_inicio;
                var documento_tipo_relacion_descripcion = data_correlativo[index].documento_tipo_relacion_descripcion;

                // ids de tablas relacionadas
                var documento_tipo_id = data_correlativo[index].documento_tipo_id;
                var documento_tipo_relacion_id = data_correlativo[index].documento_tipo_relacion_id;
     

                arrayCorrelativo_id.push(correlativo_id);
                arrayTipoDocumentoText.push(documento_descripcion.trim());//se elimina espacios
                arraySerieText.push(serie);
                arrayCorrelativoText.push(correlativo_inicio);
                arraydocumento_tipo_relacion_descripcion.push(documento_tipo_relacion_descripcion);
                //comprobar
       
                // array ids
                arrayTipoDocumento.push(documento_tipo_id);
                arraydocumento_tipo_relacion_id.push(documento_tipo_relacion_id);

                listaCorrelativoDetalle.push([correlativo_id, documento_tipo_id, documento_descripcion.trim(), serie, correlativo_inicio, documento_tipo_relacion_id, documento_tipo_relacion_descripcion]);

                onListarDireccionDetalle(listaCorrelativoDetalle);

            });

            loaderClose();
        } else {
            loaderClose();
        }
    }
}

function onListarDireccionDetalle(data) {
    $('#dataTableCorrelativo tbody tr').remove();
    var cuerpo = "";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {

            //listaCorrelativoDetalle.push([direccionTipo,direccionTipoText,ubigeo,ubigeoText,direccionText,personaDireccionId]);

            var eliminar = "<a href='#' onclick = 'eliminarDireccionDetalleXIndice(" + index + ")' >"
                    + "<i id='e" + index + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a href='#' onclick = 'editarCorrelativoDetalle("
                    + item['1'] + ", \"" + item['2'] + "\", \"" + index + "\")' >"
                    + "<i id='e" + index + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            var documento_tipo_relacion_descripcion = item['6'] == null ? '' : item['6'];
            cuerpo += "<tr>"
                    + "<td style='text-align:left;'>" + item['2'] + "</td>"
                    + "<td style='text-align:left;'>" + item['3'] + "</td>"
                    + "<td style='text-align:left;'>" + item['4'] + "</td>"
                    + "<td style='text-align:left;'>" + documento_tipo_relacion_descripcion + "</td>"
                    + "<td style='text-align:center;'>" + editar + eliminar
                    + "</td>"
                    + "</tr>";
        });

        $('#dataTableCorrelativo tbody').append(cuerpo);
    }
}
var arrayCorrelativo_id = [];
var arrayTipoDocumento = [];
var arrayTipoDocumentoText = [];
var arraySerieText = [];
var arrayCorrelativoText = [];

var arrayPersonaDireccionId = [];
var listaCorrelativoDetalle = [];
var listaCorrelativoEliminado = [];

var arraydocumento_tipo_relacion_id = [];
var arraydocumento_tipo_relacion_descripcion = [];

function agregarCorrelativoDetalle() {
    //ids
    var TipoDocumento = select2.obtenerValor('cboTipoDocumento');
    //texto
    var TipoDocumentoText = select2.obtenerText('cboTipoDocumento');
    var SerieText = document.getElementById('txt_Serie').value;
    var CorrelativoText = document.getElementById('txt_Correlativo').value;
    //var idDireccionDetalle = $('#idDireccionDetalle').val();
    var idDireccionDetalle = $('#idDireccionDetalle').val();
    var TipoDocumentoRelacion = "";
    var TipoDocumentoRelacionText = "";
    if (TipoDocumento == "284" ||  TipoDocumento == "61"  || TipoDocumento == "269") {
        TipoDocumentoRelacion = select2.obtenerValor('cboTipoDocumentoRelacion');
        TipoDocumentoRelacionText = select2.obtenerText('cboTipoDocumentoRelacion');
    }


    // ids tablas
    var personaDireccionId = null;
    //alert(idDireccionDetalle);

    if (validarFormularioCorrelativoDetalle(TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText)) {
        if (validarCorrelativoDetalleRepetido(TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText, TipoDocumentoRelacionText)) {
            $("#divTipoDocumentoRelacion").addClass('hidden');
            if (idDireccionDetalle != '') {

                arrayTipoDocumento[idDireccionDetalle] = TipoDocumento;
                arrayTipoDocumentoText[idDireccionDetalle] = TipoDocumentoText;
                arraySerieText[idDireccionDetalle] = SerieText;
                arrayCorrelativoText[idDireccionDetalle] = CorrelativoText;

                // ids de tablas relacionadas
                personaDireccionId = arrayCorrelativo_id[idDireccionDetalle];

                arraydocumento_tipo_relacion_id[idDireccionDetalle] = TipoDocumentoRelacion;
                arraydocumento_tipo_relacion_descripcion[idDireccionDetalle] = TipoDocumentoRelacionText;

                listaCorrelativoDetalle[idDireccionDetalle] = [personaDireccionId, TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText, TipoDocumentoRelacion, TipoDocumentoRelacionText];
            } else {
                //alert('diferente');

                arrayTipoDocumento.push(TipoDocumento);
                arrayTipoDocumentoText.push(TipoDocumentoText);
                arraySerieText.push(SerieText);
                arrayCorrelativoText.push(CorrelativoText);
                arraydocumento_tipo_relacion_id.push(TipoDocumentoRelacion)
                arraydocumento_tipo_relacion_descripcion.push(TipoDocumentoRelacionText);
                listaCorrelativoDetalle.push([personaDireccionId, TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText, TipoDocumentoRelacion, TipoDocumentoRelacionText]);
            }

//            console.log(listaCorrelativoDetalle);
//            console.log(listaPersonaDireccionEliminado);
            onListarDireccionDetalle(listaCorrelativoDetalle);
            limpiarCamposCorrelativoDetalle();
            limpiarMensajesCorrelativoDetalle();

        }
    }
}

function validarFormularioCorrelativoDetalle(TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText) {
    //    debugger;
    var bandera = true;
    limpiarMensajesCorrelativoDetalle();

    if (TipoDocumentoText === '' || TipoDocumentoText === null) {
        $("#msjTipoDocumento").removeProp(".hidden");
        $("#msjTipoDocumento").text("Tipo Documento es obligatorio").show();
        mostrarAdvertencia("Serie es obligatorio");
        bandera = false;
    }
    if (SerieText === '' || SerieText === null) {
        $("#msjSerie").removeProp(".hidden");
        $("#msjSerie").text("Serie es obligatorio").show();
        mostrarAdvertencia("Serie es obligatorio");
        bandera = false;
    }
    if (CorrelativoText === '' || CorrelativoText === null) {
        $("#msjCorrelativo").removeProp(".hidden");
        $("#msjCorrelativo").text("Correlativo es obligatorio").show();
        mostrarAdvertencia("Correlativo es obligatorio");
        bandera = false;
    }
    return bandera;
}
function limpiarMensajesCorrelativoDetalle() {
    $("#msjTipoDocumento").hide();
    $("#msjSerie").hide();
    $("#msjCorrelativo").hide();
}

function validarCorrelativoDetalleRepetido(TipoDocumento, TipoDocumentoText, SerieText, CorrelativoText, TipoDocumentoRelacionText) {
    //por revisar
    debugger;
    var valido = true;

    var idDireccionDetalle = $('#idDireccionDetalle').val();

    //alert(idDireccionDetalle + ' : '+ indiceDireccionTipo);

    if (idDireccionDetalle != '') {
        //alert('igual');
        if (TipoDocumento == "284" || TipoDocumento == "61" || TipoDocumento == "269" ) {
            if(TipoDocumentoRelacionText==null){
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El Documento Relacionado no puede estar vacio");
            }
            else {
            var indice = buscarCorrelativoDetalleG(TipoDocumentoRelacionText, SerieText, CorrelativoText);
             } }
        else {
        var indice = buscarCorrelativoDetalle(TipoDocumentoText, SerieText, CorrelativoText); }
//        console.log(indice,idDireccionDetalle);
        if (indice != idDireccionDetalle && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El Tipo de Documento ya ha sido agregado");
            valido = false;
        } else {
            valido = true;
        }
    } else {
        //alert('diferente');
        
        if (TipoDocumento == "284" || TipoDocumento == "61" || TipoDocumento == "269" ) {
            if(TipoDocumentoRelacionText==null){
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El Documento Relacionado no puede estar vacio");
            }
            else {
            var indice = buscarCorrelativoDetalleG(TipoDocumentoRelacionText, SerieText, CorrelativoText);
            if (indice > -1) {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El Tipo de Documento o correlativo ya ha sido agregado");
                valido = false;
            } }
        } else {
            var indice = buscarCorrelativoDetalle(TipoDocumentoText, SerieText, CorrelativoText);
            if (indice > -1) {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El Tipo de Documento o correlativo ya ha sido agregado");
                valido = false;
            }
        }

    }

    return valido;
}

function buscarCorrelativoDetalle(TipoDocumentoText, SerieText, CorrelativoText) {
    debugger;
    var tam = arrayTipoDocumentoText.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayTipoDocumentoText[i] === TipoDocumentoText) {
            ind = i;
            break;
        }
    }
    return ind;
}
function buscarCorrelativoDetalleG(TipoDocumentoText, SerieText, CorrelativoText) {
    debugger;
    var tam = arraydocumento_tipo_relacion_descripcion.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arraydocumento_tipo_relacion_descripcion[i] === TipoDocumentoText) {
            ind = i;
            break;
        }
    }
    return ind;
}

function limpiarCamposCorrelativoDetalle() {
    asignarValorSelect2('cboTipoDocumento', null);
    $('#txt_Serie').val('');
    $('#txt_Correlativo').val('');
    $('#idDireccionDetalle').val('');
}

function editarCorrelativoDetalle(TipoDocumento, TipoDocumentoText, ind) {
    var indice = ind;
    asignarValorSelect2('cboTipoDocumento', arrayTipoDocumento[indice]);

    $('#txt_Serie').val(arraySerieText[indice]);
    $('#txt_Correlativo').val(arrayCorrelativoText[indice]);
    $('#idDireccionDetalle').val(ind);
    if (TipoDocumento == "284" || TipoDocumento == "61"  || TipoDocumento == "269") {
        asignarValorSelect2('cboTipoDocumentoRelacion', arraydocumento_tipo_relacion_id[indice]);
        $("#divTipoDocumentoRelacion").removeClass('hidden');
    } else {
        $("#divTipoDocumentoRelacion").addClass('hidden');
    }
}

function eliminarDireccionDetalleXIndice(indice) {
    debugger;
    arrayTipoDocumento.splice(indice, 1);
    arrayTipoDocumentoText.splice(indice, 1);
    arraySerieText.splice(indice, 1);
    arrayCorrelativoText.splice(indice, 1);

    arraydocumento_tipo_relacion_id.splice(indice,1)
    arraydocumento_tipo_relacion_descripcion.splice(indice,1);


    if (!isEmpty(arrayCorrelativo_id[indice])) {
        var personaDireccionId = arrayCorrelativo_id[indice];
        arrayCorrelativo_id.splice(indice, 1);
        listaCorrelativoEliminado.push([personaDireccionId]);
    }

    listaCorrelativoDetalle = [];
    var tam = arrayTipoDocumento.length;
    for (var i = 0; i < tam; i++) {
        listaCorrelativoDetalle.push([arrayCorrelativo_id[i], arrayTipoDocumento[i], arrayTipoDocumentoText[i], arraySerieText[i], arrayCorrelativoText[i],arraydocumento_tipo_relacion_id[i],arraydocumento_tipo_relacion_descripcion[i]]);
    }

    onListarDireccionDetalle(listaCorrelativoDetalle);
}

function exitoUpdate(data) {
    if (data[0]["vout_exito"] == 0) {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function exitoInsert(data) {
    if (data[0]["vout_exito"] == 0) {
        habilitarBoton();
        //$.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
        mostrarAdvertencia(data[0]["vout_mensaje"]);
    } else {
        habilitarBoton();
        //$.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        mostrarOk(data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}


function llenarComboAgencia(data) {
    $.each(data, function (index, item) {
        $('#cbo_agencia').append('<option value="' + item.agencia_id + '">' + item.codigo + ' | ' + item.descripcion + '</option>');
    });
}




function guardarCaja() {

    var cajaId = document.getElementById('txtCajaId').value;
    var id = document.getElementById('id').value;

    var caja_nombre = document.getElementById('txt_codigo').value;
    var caja_descripcion = document.getElementById('txt_descripcion').value;
    var caja_sufijo = document.getElementById('txt_sufijo').value;
    var correlativo_inicio = document.getElementById('txt_correlativo').value;
    var id_agencia = document.getElementById('cbo_agencia').value;
    var estado = document.getElementById('estado').value;
    var bandera_virtual = document.getElementById('virtual').value;
     var caja_ip = document.getElementById('txt_ip').value;
//    var cboTipoDocumento = select2.obtenerText('cboTipoDocumento');
//    var SerieText = document.getElementById('txt_Serie').value;
//    var CorrelativoText = document.getElementById('txt_Correlativo').value;

    if (listaCorrelativoDetalle.length == 0) {
        mostrarAdvertencia("Tiene cambios pendientes de agregar en la pestaña Correlativos.");
    } else {
        if (cajaId != '') {
            updateCaja(id, caja_nombre, caja_descripcion, id_agencia, estado, caja_sufijo, correlativo_inicio,bandera_virtual,caja_ip);
        } else {
            insertCaja(caja_nombre, caja_descripcion, id_agencia, estado, caja_sufijo, correlativo_inicio,bandera_virtual,caja_ip);
        }
    }

}
function insertCaja(caja_nombre, caja_descripcion, id_agencia, estado, caja_sufijo, correlativo_inicio,bandera_virtual,caja_ip) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertCaja");
        ax.addParamTmp("caja_nombre", caja_nombre);
        ax.addParamTmp("caja_descripcion", caja_descripcion);
        ax.addParamTmp("id_agencia", id_agencia);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("caja_sufijo", caja_sufijo);
        ax.addParamTmp("correlativo_inicio", correlativo_inicio);
        ax.addParamTmp("listaCorrelativoDetalle", listaCorrelativoDetalle);
        ax.addParamTmp("bandera_virtual", bandera_virtual); 
        ax.addParamTmp("caja_ip", caja_ip); 
        ax.consumir();
    }
}

function updateCaja(id, caja_nombre, caja_descripcion, id_agencia, estado, caja_sufijo, correlativo_inicio,bandera_virtual,caja_ip) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateCaja");
        ax.addParamTmp("id_caja", id);
        ax.addParamTmp("caja_nombre", caja_nombre);
        ax.addParamTmp("id_agencia", id_agencia);
        ax.addParamTmp("caja_descripcion", caja_descripcion);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("caja_sufijo", caja_sufijo);
        ax.addParamTmp("correlativo_inicio", correlativo_inicio);
        ax.addParamTmp("listaCorrelativoDetalle", listaCorrelativoDetalle);
        ax.addParamTmp("listaCorrelativoEliminado", listaCorrelativoEliminado);
        ax.addParamTmp("bandera_virtual", bandera_virtual);
        ax.addParamTmp("caja_ip", caja_ip);
        ax.consumir();
    }
}






//function obtenerPantallaPrincipalUsuario()
//{
//    acciones.iniciaAjaxTest(COMPONENTES.USUARIO, "successUsuario");
//    ax.setAccion("obtenerPantallaPrincipalUsuario");
//    ax.consumir();
//}



function obtenerTitulo() {
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    $("#window").empty();

    if (!isEmpty(titulo)) {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar() {
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/caja/caja_listar.php", tituloGlobal);
}

