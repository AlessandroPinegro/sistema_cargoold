var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;
var acciones = {
    getComboUbigeo: false,
    getAgencia: false
};

$(document).ready(function () {
    ax.setSuccess("successAgencia");
});

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

function cargarCombo()
{
    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getComboUbigeo");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo

    //retorna los datos de los usuarios para llenar el combo

    getAgencia(VALOR_ID_USUARIO);
}
function nuevo() {
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/agencia/agencia_form.php', "Nuevo " + obtenerTitulo());
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}
function cargarComponentes()
{
    cargarSelect2();
}
function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}

$("#estado").change(function () {
    $('#msj_estado').hide();
});
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

function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});

$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

$('#txt_direccion').keypress(function () {
    $('#msj_direccion').hide();
});

function onchangeUbigeo()
{
    $('#msj_ubigeo').hide();
}

function onchangePerfil()
{
    $('#msj_perfil').hide();
}

function onchangeEmpresa()
{
    $('#msj_empresa').hide();
}

function limpiar_formulario_usuario()
{
    document.getElementById("frm_usuario").reset();
}

function getAgencia(id)
{
    loaderShow();
    ax.setAccion("getAgencia");
    ax.addParamTmp("id_agencia", id);
    ax.consumir();
}
function validar_agencia_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var age_codigo = document.getElementById('txt_codigo').value;
    var age_descripcion = document.getElementById('txt_descripcion').value;
    var age_direccion = document.getElementById('txt_direccion').value;
    var id_ubigeo = document.getElementById('cbo_ubigeo').value;

    var estado = document.getElementById('estado').value;


    if (age_codigo == "" || espacio.test(age_codigo) || age_codigo.length == 0)
    {
        $("msj_codigo").removeProp(".hidden");
        $("#msj_codigo").text("Ingresar un Codigo").show();
        bandera = false;
    }


    if (age_descripcion == "" || espacio.test(age_descripcion) || age_descripcion.length == 0)
    {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar una descripción").show();
        bandera = false;
    }

    if (age_direccion == "" || espacio.test(age_direccion) || age_direccion.length == 0)
    {
        $("msj_direccion").removeProp(".hidden");
        $("#msj_direccion").text("Ingresar una dirección").show();
        bandera = false;
    }


    if (id_ubigeo == "" || id_ubigeo == null || espacio.test(id_ubigeo) || id_ubigeo.length == 0)
    {
        $("msj_ubigeo").removeProp(".hidden");
        $("#msj_ubigeo").text("Ingresar un ubigeo válido").show();
        bandera = false;
    }

    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
    {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }

    return bandera;
}

function cargarDatagrid()
{
    ax.setAccion("getDataGridUsuario");
    ax.consumir();

}

function listarAgencias() {
    ax.setSuccess("successAgencia");
    cargarDatagrid();
}

function successAgencia(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertAgencia':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Agencia guardada correctamente.");
                break;

            case 'updateAgencia':
                loaderClose();
                cargarPantallaListar();
                mostrarOk("Agencia actualizada correctamente.");
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertAgencia':
                habilitarBoton();
                loaderClose();
                cargarPantallaListar();
                break;

            case 'updateAgencia':
                habilitarBoton();
                loaderClose();

                break;
        }
    }
}

function agenciaCargarData()
{
    if (acciones.getComboUbigeo && acciones.getAgencia)
    {
        if (!isEmpty(VALOR_ID_USUARIO))
        {
//                llenarFormularioEditar(dataPorId);
            document.getElementById('txt_codigo').value = dataPorId['0']['codigo'];
            document.getElementById('txt_descripcion').value = dataPorId['0']['descripcion'];
            document.getElementById('txt_direccion').value = dataPorId['0']['direccion'];
            document.getElementById('estado').value = dataPorId['0']['estado'];
            asignarValorSelect2("cbo_ubigeo", dataPorId['0']['ubigeo_id']);
            asignarValorSelect2("estado", dataPorId['0']['estado']);

            select2.cargar("cboOrganizador", dataPorId['0']['dataOrganizadorPosicion'], "id", "descripcion_completa");
            select2.asignarValor("cboOrganizador", dataPorId['0']['organizador_defecto_id']);

            select2.cargar("cboOrganizadorRecepcion", dataPorId['0']['dataOrganizadorPosicion'], "id", "descripcion_completa");
            select2.asignarValor("cboOrganizadorRecepcion", dataPorId['0']['organizador_defecto_recepcion_id']);

            loaderClose();
        } else
        {
            loaderClose();
        }
    }
}

function cargarDivCancelarCambioContrasena()
{
    cargarDivTitulo("#window", url_cancelar, tituloGlobal);
}
function exitoCambioContrasena(data)
{
    if (data[0]['vout_exito'] == 1)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        $("#window").empty();
    } else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
//        cargarDiv("#window", data[0]['pant_principal'], tituloGlobal);
    }
}

function exitoUpdate(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        //$.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
        mostrarAdvertencia(data[0]["vout_mensaje"]);
    } else
    {
        habilitarBoton();
        //$.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        mostrarOk(data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}


function llenarComboUbigeo(data)
{
    $('#cbo_ubigeo').append('<option value="">Seleccione un ubigeo</option>');
    $.each(data, function (index, item) {
        $('#cbo_ubigeo').append('<option value="' + item.id + '">' + item.ubigeo_codigo + ', ' + item.ubigeo_dep + ' | ' + item.ubigeo_prov + ' | ' + item.ubigeo_dist + '</option>');
    });
    
    select2.asignarValor('cbo_ubigeo','');
}



function guardarAgencia()
{

    var agenciaId = document.getElementById('txtAgenciaId').value;
    var id = document.getElementById('id').value;

    var age_codigo = document.getElementById('txt_codigo').value;
    var age_descripcion = document.getElementById('txt_descripcion').value;
    var age_direccion = document.getElementById('txt_direccion').value;
    var id_ubigeo = document.getElementById('cbo_ubigeo').value;
    var organizadorDefectoId = document.getElementById('cboOrganizador').value;
    var organizadorDefectoRecepcionId = document.getElementById('cboOrganizadorRecepcion').value;

    var estado = document.getElementById('estado').value;

    if (agenciaId != '')
    {
        updateAgencia(id, age_codigo, age_descripcion, age_direccion, id_ubigeo, estado, organizadorDefectoId, organizadorDefectoRecepcionId);
    } else {
        insertAgencia(age_codigo, age_descripcion, age_direccion, id_ubigeo, estado);
    }
}

function insertAgencia(age_codigo, age_descripcion, age_direccion, id_ubigeo, estado)
{
    if (validar_agencia_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertAgencia");
        ax.addParamTmp("age_codigo", age_codigo);
        ax.addParamTmp("age_descripcion", age_descripcion);
        ax.addParamTmp("age_direccion", age_direccion);
        ax.addParamTmp("id_ubigeo", id_ubigeo);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}

function updateAgencia(id, age_codigo, age_descripcion, age_direccion, id_ubigeo, estado, organizadorDefectoId, organizadorDefectoRecepcionId)
{
    if (validar_agencia_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateAgencia");
        ax.addParamTmp("id_agencia", id);
        ax.addParamTmp("age_codigo", age_codigo);
        ax.addParamTmp("id_ubigeo", id_ubigeo);
        ax.addParamTmp("age_descripcion", age_descripcion);
        ax.addParamTmp("age_direccion", age_direccion);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("organizadorDefectoId", organizadorDefectoId);
        ax.addParamTmp("organizadorDefectoRecepcionId", organizadorDefectoRecepcionId);
        ax.consumir();
    }
}

function obtenerTitulo()
{
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    $("#window").empty();

    if (!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar()
{
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/agencia/agencia_listar.php", tituloGlobal);
}

