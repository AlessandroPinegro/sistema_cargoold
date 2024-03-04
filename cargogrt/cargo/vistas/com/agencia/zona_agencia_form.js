var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;
var acciones = {

    getComboAgencia: false,
    getAgencia: false

};

$(document).ready(function () {
    //debugger;
    ax.setSuccess("successAgenciaZonaForm");
    loaderShow();
    cargarSelect2();
    cargarCombo();

    ax.setAccion("getDataInicialZona");
    ax.addParamTmp("id_zona", VALOR_ID_USUARIO);
    ax.consumir();

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
function onchangeUbigeo()
{
    $('#msj_ubigeo').hide();
}
function cargarComboAgencia(tipo) {
    // console.log("aaaaaaaaaaaaaa "+VALOR_ID_USUARIO);
    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getDataGridAgencia");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo

    //retorna los datos de los usuarios para llenar el combo
    //  getAgencia(VALOR_ID_USUARIO);
}
function nuevo() {
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/agencia/zona_agencia_form.php', "Nuevo " + obtenerTitulo());
}

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({ width: '100%' });
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
function cambiarEstado(id_estado) {
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data) {
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

function onchangeAgencia() {
    $('#msj_agencia').hide();
}

function limpiar_formulario_usuario() {
    document.getElementById("frm_zona").reset();
}

function getAgencia(id) {
    loaderShow();
    ax.setAccion("getAgencia");
    ax.addParamTmp("id_agencia", id);
    ax.consumir();
}
function cargarCombo()
{
    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getComboUbigeo");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo

    //retorna los datos de los usuarios para llenar el combo

}
function validar_zona_form() {
    var bandera = true;
    var espacio = /^\s+$/;

    var agenciaId = document.getElementById('cboAgencia').value;
    var zona_descripcion = document.getElementById('txt_descripcion').value;
    var estado = document.getElementById('estado').value;
     var id_ubigeo = document.getElementById('cbo_ubigeo').value;

    if (agenciaId == "" || agenciaId == null || espacio.test(agenciaId) || agenciaId.length == 0) {
        $("msj_agencia").removeProp(".hidden");
        $("#msj_agencia").text("Ingresar una agencia valida").show();
        bandera = false;
    }
    console.log(agenciaId.length)
    if (zona_descripcion == "" || espacio.test(zona_descripcion) || zona_descripcion.length == 0) {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar una descripción").show();
        bandera = false;
    }


    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null) {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }


    return bandera;
}

function cargarDatagrid() {
    ax.setAccion("getDataGridUsuario");
    ax.consumir();
}

function listarAgencias() {
    ax.setSuccess("successAgencia");
    cargarDatagrid();
}

function successAgenciaZonaForm(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataInicialZona':
                let data = response.data;
                llenarComboAgencia(data.dataAgencia);
                if (!isEmpty(data.dataZona)) {
                    zonaCargarData(data.dataZona);
                }
                loaderClose();
                break;
                
                    case 'getComboUbigeo':
                let data2 = response.data;
                llenarComboUbigeo(data2);
                loaderClose();
                break;
                
            case 'insertZona':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Zona guardada correctamente. ");
                break;

            case 'updateZona':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Zona actualizada correctamente. ");
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertZona':
                habilitarBoton();
                loaderClose();
                cargarPantallaListar();
                break;

            case 'updateZona':
                habilitarBoton();
                loaderClose();

                break;
        }
    }
}

function zonaCargarData(dataPorId) {

    //                llenarFormularioEditar(dataPorId);
    document.getElementById('txt_descripcion').value = dataPorId['0']['descripcion'];
    document.getElementById('estado').value = dataPorId['0']['estado'];

    asignarValorSelect2("cboAgencia", dataPorId['0']['agencia_id']);

    asignarValorSelect2("estado", dataPorId['0']['estado']);
asignarValorSelect2("cbo_ubigeo", dataPorId['0']['ubigeo_id']);
}

function cargarDivCancelarCambioContrasena() {
    cargarDivTitulo("#window", url_cancelar, tituloGlobal);
}

function exitoCambioContrasena(data) {
    if (data[0]['vout_exito'] == 1) {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        $("#window").empty();
    } else {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
        //        cargarDiv("#window", data[0]['pant_principal'], tituloGlobal);
    }
}

function exitoUpdate(data) {
    if (data[0]["vout_exito"] == 0) {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else {
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
    }
    else {
        habilitarBoton();
        //$.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        mostrarOk(data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}


function llenarComboAgencia(data) {
    $('#cboAgencia').append('<option value="" selected disabled>Selecionar una Agencia</option>',
        '</select>');
    $.each(data, function (index, item) {
        $('#cboAgencia').append('<option value="' + item.agencia_id + '">' + item.descripcion + '</option>');
    });
    $("#cboAgencia").select2("val", "");
    onchangeAgencia();
}

function llenarComboUbigeo(data2)
{
    $('#cbo_ubigeo').append('<option value="">Seleccione un ubigeo</option>');
    $.each(data2, function (index, item) {
        $('#cbo_ubigeo').append('<option value="' + item.id + '">' + item.ubigeo_codigo + ', ' + item.ubigeo_dep + ' | ' + item.ubigeo_prov + ' | ' + item.ubigeo_dist + '</option>');
    });
    
    select2.asignarValor('cbo_ubigeo','');
}

function guardarZona() {

    var agenciaId = document.getElementById('cboAgencia').value;
    var id = document.getElementById('id').value;
    var zona_descripcion = document.getElementById('txt_descripcion').value;
    var estado = document.getElementById('estado').value;
var id_ubigeo = document.getElementById('cbo_ubigeo').value;

    if (id != '') {
        updateZona(id, agenciaId, zona_descripcion, estado,id_ubigeo);
    } else {
        insertZona(agenciaId, zona_descripcion, estado,id_ubigeo);
    }
}

function insertZona(agenciaId, zona_descripcion, estado,id_ubigeo) {
   
    if (validar_zona_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertZona");
        ax.addParamTmp("agenciaId", agenciaId);
        ax.addParamTmp("zona_descripcion", zona_descripcion);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("id_ubigeo", id_ubigeo);
        ax.consumir();
    }
}

function updateZona(id, agenciaId, zona_descripcion, estado,id_ubigeo) {
    if (validar_zona_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateZona");
        ax.addParamTmp("id_zona", id);
        ax.addParamTmp("agenciaId", agenciaId);
        ax.addParamTmp("zona_descripcion", zona_descripcion);
        ax.addParamTmp("estado", estado);
           ax.addParamTmp("id_ubigeo", id_ubigeo);
        ax.consumir();
    }
}

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
    cargarDivTitulo("#window", "vistas/com/agencia/zona_agencia_listar.php", tituloGlobal);
}

