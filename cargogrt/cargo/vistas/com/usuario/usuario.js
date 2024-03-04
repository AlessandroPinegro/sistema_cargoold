var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;
var acciones = {
    getComboPerfil: false,
    getComboColaborador: false,
    getComboEmpresa: false,
    getUsuario: false,
    getComboUsuario: false
};

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

function cargarCombo(tipo)
{
    ax.setAccion("getComboPerfil");
    ax.consumir();
    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getComboColaborador");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo
    ax.setAccion("getComboEmpresa");
    ax.consumir();
    //retorna los datos de los usuarios para llenar el combo
    ax.setAccion("obtenerUsuarios");
    ax.consumir();
    getUsuario(VALOR_ID_USUARIO);
}

function nuevo() {

    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/usuario/usuario_form.php', "Nuevo " + obtenerTitulo());
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
$('#txt_usuario').keypress(function () {
    $('#msj_usuario').hide();
});

function onchangeColaborador()
{
    $('#msj_colaborador').hide();
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

function cargarDivGetUsuario(id) {

    //sele envia un numero 1 para indicar que el combo se cargara para editar
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/usuario/usuario_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());

}
function getUsuario(id)
{
    loaderShow();
    ax.setAccion("getUsuario");
    ax.addParamTmp("id_usuario", id);
    ax.consumir();
}
function validar_usuario_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var usu_nombre = document.getElementById('txt_usuario').value;
    var id_colaborador = document.getElementById('cbo_colaborador').value;
    var id_perfil = document.getElementById('cbo_perfil').value;
    var empresa = document.getElementById("cbo_empresa").value;
    var estado = document.getElementById('estado').value;
    
    if (usu_nombre == "" || espacio.test(usu_nombre) || usu_nombre.length == 0)
    {
        $("msj_usuario").removeProp(".hidden");
        $("#msj_usuario").text("Ingresar un usuario").show();
        bandera = false;
    }
    if (id_colaborador == "" || id_colaborador == null || espacio.test(id_colaborador) || id_colaborador.length == 0)
    {
        $("msj_colaborador").removeProp(".hidden");
        $("#msj_colaborador").text("Seleccione un colaborador válido").show();
        bandera = false;
    }

    if (id_perfil == "" || id_perfil == null || espacio.test(id_perfil) || id_perfil.length == 0)
    {
        $("msj_perfil").removeProp(".hidden");
        $("#msj_perfil").text("Ingresar el nombre de un perfil válido").show();
        bandera = false;
    }
    if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
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
function listarUsuarios() {
    ax.setSuccess("successUsuario")
    cargarDatagrid();
}
function successUsuario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridUsuario':
                onResponseAjaxpGetDataGridUsuarios(response.data);
                $('#datatable').dataTable({
                    "scrollX": true,
                    "autoWidth": true,
                    "order": [[0, "asc"]],
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
                loaderClose();
                break;
            case 'getComboColaborador':
                acciones.getComboColaborador = true;
                llenarComboColaborador(response.data);
                usuarioCargarData();
                break;
            case 'obtenerUsuarios':
                acciones.getComboUsuario = true;
                llenarComboUsuario(response.data);
                usuarioCargarData();
                break;
            case 'getComboPerfil':
                acciones.getComboPerfil = true;
                llenarComboPerfil(response.data);
                usuarioCargarData();
                break;
            case 'insertUsuario':
                loaderClose();
                exitoInsert(response.data);
                break;
            case 'getUsuario':
                acciones.getUsuario = true;
                dataPorId = response.data;
                usuarioCargarData();
                break;
            case 'updateUsuario':
                loaderClose();
                exitoUpdate(response.data);
                break;
            case 'deleteUsuario':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                    cargarDatagrid();

                } else {
                    swal("Cancelado", " " + "Usuario " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'obtenerContrasenaActual':
                if (validarCambiarContrasena(response.data[0]['clave']))
                {
                    cambiarContrasena();
                }
                break;
            case 'cambiarContrasena':
                exitoCambioContrasena(response.data)
                break;
            case 'obtenerPantallaPrincipalUsuario':
                url_cancelar = response.data[0]['url'];
                break;
            case 'getComboEmpresa':
                acciones.getComboEmpresa = true;
                llenarComboEmpresa(response.data);
                usuarioCargarData();
                break;
        }
    }else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'cambiarContrasena':
                habilitarBoton();
                break;
             
        }
    }
}
function usuarioCargarData()
{
    if (acciones.getComboColaborador && acciones.getComboEmpresa && acciones.getComboPerfil 
            && acciones.getUsuario && acciones.getComboUsuario)
    {
        if (!isEmpty(VALOR_ID_USUARIO))
        {
//                llenarFormularioEditar(dataPorId);
            document.getElementById('txt_usuario').value = dataPorId['0']['usuario'];
            document.getElementById('estado').value = dataPorId['0']['est_usuario'];
            asignarValorSelect2("cbo_perfil", dataPorId['0']['perfil'].split(";"));
            asignarValorSelect2("cbo_colaborador", dataPorId['0']['persona_id']);
            asignarValorSelect2("cbo_empresa", dataPorId['0']['empresa'].split(';'));
            asignarValorSelect2("estado", dataPorId['0']['est_usuario']);
            asignarValorSelect2("cboJefe", dataPorId['0']['usuario_padre_id']);
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
    }
    else
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
    }
    else
    {
        habilitarBoton();
        //$.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        mostrarOk(data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridUsuarios(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Usuario</th>" +
            "<th style='text-align:center;'>Perfil</th>" +
            "<th style='text-align:center;'>Nombres y Apellidos</th>" +
            "<th style='text-align:center;'>Email</th>" +
            "<th style='text-align:center;'>Empresas</th>" +
//            "<th style='text-align:center;'>Jefe</th>" +
//            "<th style='text-align:center;'>Clave</th>" +
            "<th style='text-align:center;'>Estado</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
    $.each(data, function (index, item) {
        cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.usuario + "</td>" +
                "<td style='text-align:left;'>" + item.perfil + "</td>" +
                "<td style='text-align:left;'>" + item.nombre + ' ' + item.apellidos + "</td>" +
                "<td style='text-align:left;'>" + item.email + "</td>" +
                "<td style='text-align:left;'>" + item.empresa + "</td>" +
//                "<td style='text-align:left;'>" + item.usuario_padre_nombre + "</td>" +
                "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.usuario_id + ")' ><b><i id='" + item.usuario_id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='cargarDivGetUsuario(" + item.usuario_id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteUsuario(" + item.usuario_id + ", \"" + item.usuario + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);    
}

function llenarComboColaborador(data)
{
    $('#cbo_colaborador').append('<option value="">Seleccione un colaborador</option>');
   
    $.each(data, function (index, item) {
        $('#cbo_colaborador').append('<option value="' + item.id + '">' + item.apellidos + ', ' + item.nombres + ' | '+ item.dni + '</option>');
    });
    
    select2.asignarValor('cbo_colaborador','');
}

function llenarComboUsuario(data)
{
    $('#cboJefe').append('<option value="-1">Seleccione jefe</option>');
    $.each(data, function (index, item) {
        $('#cboJefe').append('<option value="' + item.usuario_id + '">' + item.apellidos + ', ' + item.nombre + ' | '+ item.codigo_identificacion + '</option>');
    });
}

function llenarComboPerfil(data)
{
    $.each(data, function (index, item) {
        $('#cbo_perfil').append('<option value="' + item.id + '">' + item.nombre + '</option>');
    });
}

function guardarUsuario(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var usu_nombre = document.getElementById('txt_usuario').value;
    var id_colaborador = document.getElementById('cbo_colaborador').value;
    var id_perfil = $("#cbo_perfil").val();
//    var empresa = $("#cbo_empresa").val();
    var estado = document.getElementById('estado').value;
    var jefeId=select2.obtenerValor('cboJefe');

    var combo = document.getElementById('cbo_empresa');
    var tam = combo.length;
    var empresa = new Array();
    var comboT = new Array();
    var j = 0;
    for (var i = 0; i < tam; i++)
    {
        comboT[i] = combo.options[i].value;
        if (combo.options[i].selected == true)
        {
            var id_empresa = combo.options[i].value;
            empresa[j] = id_empresa;
            j++;
        }
    }
    if (tipo == 1)
    {
        updateUsuario(id, usu_nombre, id_colaborador, id_perfil, estado, empresa, comboT,jefeId);
    } else {
        insertUsuario(usu_nombre, id_colaborador, id_perfil, usu_creacion, estado, empresa, comboT,jefeId);
    }
}
function insertUsuario(usu_nombre, id_colaborador, id_perfil, usu_creacion, estado, empresa, comboT,jefeId)
{
    if (validar_usuario_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertUsuario");
        ax.addParamTmp("usu_nombre", usu_nombre);
        ax.addParamTmp("id_colaborador", id_colaborador);
        ax.addParamTmp("id_perfil", id_perfil);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("combo", comboT);
        ax.addParamTmp("jefeId", jefeId);
        ax.consumir();
    }
}
function getUsuario(id_usuario)
{
    ax.setAccion("getUsuario");
    ax.addParamTmp("id_usuario", id_usuario);
    ax.consumir();
}

function updateUsuario(id, usu_nombre, id_colaborador, id_perfil, estado, empresa, comboT,jefeId)
{
    if (validar_usuario_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateUsuario");
        ax.addParamTmp("id_usuario", id);
        ax.addParamTmp("usu_nombre", usu_nombre);
        ax.addParamTmp("id_colaborador", id_colaborador);
        ax.addParamTmp("id_perfil", id_perfil);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("combo", comboT);
        ax.addParamTmp("jefeId", jefeId);
        ax.consumir();
    }
}
function confirmarDeleteUsuario(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + nom + "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            deleteUsuario(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function deleteUsuario(id_usuario, nom)
{
    ax.setAccion("deleteUsuario");
    ax.addParamTmp("id_usuario", id_usuario);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}

$('#contra_actual').keypress(function () {
    $('#msj_actual').hide();
});
$('#contra_actual').click(function () {
    $('#msj_actual').hide();
});
$('#contra_nueva').keypress(function () {
    $('#msj_nueva').hide();
});
$('#contra_confirmar').keypress(function () {
    $('#msj_confirmar').hide();
});

function obtenerContrasenaActual()
{
    var usuario = document.getElementById('usuario').value;
    console.log(usuario);
     ax.setOpcion(3);
    ax.setAccion("obtenerContrasenaActual");
    ax.addParamTmp("usuario", usuario);
    ax.consumir();
}

function validarCambiarContrasena(contra)
{
    var contra_actual = document.getElementById('contra_actual').value;
    var contra_nueva = document.getElementById('contra_nueva').value;
    var contra_confirmar = document.getElementById('contra_confirmar').value;
    var bandera = true;
    var espacio = /^\s+$/;

//    if (contra != contra_actual)
//    {
//        $("msj_actual").removeProp(".hidden");
//        $("#msj_actual").text("Contraseñas incorrecta").show();
//        bandera = false;
//    }

    if (contra_nueva != contra_confirmar)
    {
        $("msj_nueva").removeProp(".hidden");
        $("#msj_nueva").text("Contraseñas no coinciden").show();
        $("msj_confirmar").removeProp(".hidden");
        $("#msj_confirmar").text("Contraseñas no coinciden").show();
        bandera = false;
    }

    if (contra_actual == "" || contra_actual == null || espacio.test(contra_actual) || contra_actual.length == 0)
    {
        $("msj_actual").removeProp(".hidden");
        $("#msj_actual").text("Ingresar una contraseña").show();
        bandera = false;
    }
    if (contra_nueva == "" || contra_nueva == null || espacio.test(contra_nueva) || contra_nueva.length == 0)
    {
        $("msj_nueva").removeProp(".hidden");
        $("#msj_nueva").text("Ingresar una contraseña").show();
        bandera = false;
    }
    if (contra_confirmar == "" || contra_confirmar == null || espacio.test(contra_confirmar) || contra_confirmar.length == 0)
    {
        $("msj_confirmar").removeProp(".hidden");
        $("#msj_confirmar").text("Ingresar una contraseña").show();
        bandera = false;
    }
    return bandera;
}

function cambiarContrasena()
{
    if(validarCambiarContrasena())
    {
    var usuario = document.getElementById('usuario').value;
    var contra_actual = document.getElementById('contra_actual').value;
    var contra_nueva = document.getElementById('contra_nueva').value;
    deshabilitarBoton();
    ax.setOpcion(3);
    ax.setSuccess("successUsuario");
    ax.setAccion("cambiarContrasena");
    ax.addParamTmp("usuario", usuario);
    ax.addParamTmp("contra_actual", contra_actual);
    ax.addParamTmp("contra_nueva", contra_nueva);
    ax.consumir();  
    }
}
//function obtenerPantallaPrincipalUsuario()
//{
//    acciones.iniciaAjaxTest(COMPONENTES.USUARIO, "successUsuario");
//    ax.setAccion("obtenerPantallaPrincipalUsuario");
//    ax.consumir();
//}

function llenarComboEmpresa(data)
{
    if(!isEmpty(data))
    {
    $.each(data, function (index, item) {
        $('#cbo_empresa').append('<option value="' + item.id + '">' + item.razon_social + '</option>');
    });
    asignarValorSelect2("cbo_empresa", data['0']['id']);
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
    cargarDivTitulo("#window", "vistas/com/usuario/usuario_listar.php", tituloGlobal);
}

function cancelarCambiarContrasena()
{
     $("#window").empty();
}