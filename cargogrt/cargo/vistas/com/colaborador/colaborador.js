var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;

//var VALOR_ID_USUARIO = 17777777777;

$("#cboEstado").change(function () {
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
//    ax.addParamTmp("estado", est);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
function onchangeEmpresa()
{
    $('#msj_empresa').hide();
}

$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});
$('#txt_dni').keypress(function () {
    $('#msj_dni').hide();
});

$('#txt_nombre').keypress(function () {
    $('#msj_nombre').hide();
});
$('#txt_apepaterno').keypress(function () {
    $('#msj_paterno').hide();
});

$('#txt_apematerno').keypress(function () {
    $('#msj_materno').hide();
});
$('#txt_email').keypress(function () {
    $('#msj_email').hide();
});

$('#txt_celular').keypress(function () {
    $('#msj_celular').hide();
});

$('#txt_telefono').keypress(function () {
    $('#msj_telefono').hide();
});

$('#txt_celular').keydown(function () {
    $('#msj_celular').hide();
});

$('#txt_telefono').keydown(function () {
    $('#msj_telefono').hide();
});

function limpiar_formulario_colaborador()
{
    document.getElementById("frm_colaborador").reset();
    var dir_limpiar = "http://" + location.host + "/almacen/vistas/com/colaborador/imagen/none.jpg";
    $('#myImg').src = dir_limpiar;
    
}
function validar_colaborador_form()
{
    var bandera = true;
    var dni = document.getElementById("txt_dni").value;
    var nombre = document.getElementById("txt_nombre").value;
    var ape_paterno = document.getElementById("txt_apepaterno").value;
    var ape_materno = document.getElementById("txt_apematerno").value;
    var email = document.getElementById("txt_email").value;
    var celular = document.getElementById("txt_celular").value;
    var telefono = document.getElementById("txt_telefono").value;

    var empresa = document.getElementById("cboEmpresa").value;
    var estado = document.getElementById('cboEstado').value;


    //expresiones de validacion 

    var exp_email = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
    var letras_latinas = /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\s]+$/;
    var expresion_dni = /^\d{8}[0-9]$/;
    var phoneNumber = /^[0-9-()+]{3,20}/;
    var espacio = /^\s+$/;

    if (dni == "" || espacio.test(dni) || dni.lenght == 0 || dni == null) {
        $("#msj_dni").removeProp("hidden");
        $("#msj_dni").text("Ingresar el número de DNI").show();
        bandera = false;

    } else
    {
        if (isNaN(dni))
        {
            $("#msj_dni").removeProp("hidden");
            $("#msj_dni").text("Solo se admiten numeros").show();
            bandera = false;
        }
    }
    if (nombre == "" || nombre == null || espacio.test(nombre) || nombre.length == 0)
    {
        $("msj_nombre").removeProp(".hidden");
        $("#msj_nombre").text("Ingresar un nombre").show();
        bandera = false;
    } else {
        if (!letras_latinas.test(nombre))
        {
            $("msj_nombre").removeProp(".hidden");
            $("#msj_nombre").text("Solo se admiten letras").show();
            bandera = false;
        }
    }

    if (ape_paterno == "" || ape_paterno == null || espacio.test(ape_paterno) || ape_paterno.length == 0)
    {
        $("msj_paterno").removeProp(".hidden");
        $("#msj_paterno").text("Ingresar apellido paterno").show();
        bandera = false;
    } else {
        if (!letras_latinas.test(ape_paterno))
        {
            $("msj_paterno").removeProp(".hidden");
            $("#msj_paterno").text("Solo se Admiten letras").show();
            bandera = false;
        }
    }

    if (ape_materno == "" || ape_materno == null || espacio.test(ape_materno) || ape_materno.length == 0)
    {
        $("msj_materno").removeProp(".hidden");
        $("#msj_materno").text("Ingresar apellido materno").show();
        bandera = false;
    } else {
        if (!letras_latinas.test(ape_materno))
        {
            $("msj_materno").removeProp(".hidden");
            $("#msj_materno").text("Solo se admiten letras").show();
            bandera = false;
        }
    }


    if (email == "" || email == null || espacio.test(email) || email.length == 0)
    {
        $("msj_email").removeProp(".hidden");
        $("#msj_email").text("Ingresar un correo electrónico").show();
        bandera = false;

    } else
    {
        if (!exp_email.test(email))
        {
            $("msj_email").removeProp(".hidden");
            $("#msj_email").text("No es un correo valido").show();
            bandera = false;
        }
    }
    if (celular != "" || celular == null || !espacio.test(celular) || celular.length != 0) {
        if (isNaN(celular))
        {
            $("msj_celular").removeProp(".hidden");
            $("#msj_celular").text("No es un numero de celular valido").show();
            bandera = false;
        }
    }

    if (telefono != "" || !espacio.test(telefono) || telefono.length != 0) {
        if (isNaN(telefono))
        {
            $("msj_telefono").removeProp(".hidden");
            $("#msj_telefono").text("No es un numero de teléfono valido").show();
            bandera = false;
        }
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
function listarColaboradores()
{
    ax.setSuccess("successColaborador");
    ax.setAccion("getDataGridColaborador");
    ax.consumir();
}

function editarColaborador(id) {
    VALOR_ID_USUARIO = id;
    $("#window").empty();
    cargarDiv("#window", "vistas/com/colaborador/colaborador_form.php?id=" + id + "&" + "tipo=" + 1);

    cargarComponentesFormColaborador();
}

function successColaborador(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridColaborador':
                onResponseAjaxpGetDataGridColaboradores(response.data);
                $('#datatable').dataTable({
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
                break;
            case 'insertColaborador':
                exitoInsert(response.data);
                break;
            case 'getDetalleColaborador':
                onResponseDetalleColaborador(response.data);
                break;
            case 'getColaborador':
                llenarFormularioEditar(response.data);
                break;
            case 'updateColaborador':
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'deleteColaborador':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                } else {
                    swal("Cancelado", "Upss!!. " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);

                if (!isEmpty(VALOR_ID_USUARIO))
                {
                    getColaborador(VALOR_ID_USUARIO);
                }
                loaderClose();
                break;
        }
    }
}

function onResponseGetAllEmpresas(data) {

    $.each(data, function (index, value) {
        $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
    });
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
        cargarDiv("#window", "vistas/com/colaborador/colaborador_listar.php");
    }
}

function exitoInsert(data)
{
    habilitarBoton();
    if (data[0]["vout_exito"] == 0)
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarDiv("#window", "vistas/com/colaborador/colaborador_listar.php");
    }
}


function onResponseAjaxpGetDataGridColaboradores(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Nombre</th>" +
            "<th style='text-align:center;'>Apellidos</th>" +
            "<th style='text-align:center;'>Celular</th>" +
            "<th style='text-align:center;'>Email</th>" +
            "<th style='text-align:center;'>Direccion</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {


        $.each(data, function (index, item) {
            var celular = item.celular;
            if (item.celular == null)
            {
                celular = '';
            }
            var direccion = item.direccion;
            if (item.direccion == null)
            {
                direccion = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:center;'>" + item.nombre + "</td>" +
                    "<td style='text-align:center;'>" + item.apellidos + "</td>" +
                    "<td style='text-align:center;'>" + celular + "</td>" +
                    "<td style='text-align:center;'>" + item.email + "</td>" +
                    "<td style='text-align:center;'>" + direccion + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarColaborador(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteColaborador(" + item.id + ", \"" + item.nombre + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);
    loaderClose();
}
function guardarColaborador(tipo)
{
    var id = $('#id').val();
    var dni = $('#txt_dni').val();
    var nombre = $('#txt_nombre').val();
    var paterno = $('#txt_apepaterno').val();
    var materno = $('#txt_apematerno').val();
    var telefono = $('#txt_telefono').val();
    var celular = $('#txt_celular').val();
    var email = $('#txt_email').val();
    var direccion = $('#txt_direccion').val();
    var ref_direccion = $('#txt_refdireccion').val();
    var usuario = $('#usuario').val();
    var estado = $('#cboEstado').val();
    var empresa = $('#cboEmpresa').val();

    var file = $('#secretImg').val();
    if (file == '')
    {
        file = null;
    }
    if (tipo == 1)
    {
        updateColaborador(id, dni, nombre, paterno, materno, telefono, celular, email, direccion, ref_direccion, estado, file, empresa);
    } else {
        insertColaborador(dni, nombre, paterno, materno, telefono, celular, email, direccion, ref_direccion, usuario, estado, file, empresa);
    }
}

function insertColaborador(dni, nombre, paterno, materno, telefono, celular, email, direccion, ref_direccion, usuario, estado, file, empresa)
{
    if (validar_colaborador_form()) {
        deshabilitarBoton();
        ax.setAccion("insertColaborador");
        ax.addParamTmp("dni", dni);
        ax.addParamTmp("nombre", nombre);
        ax.addParamTmp("paterno", paterno);
        ax.addParamTmp("materno", materno);
        ax.addParamTmp("telefono", telefono);
        ax.addParamTmp("celular", celular);
        ax.addParamTmp("email", email);
        ax.addParamTmp("direccion", direccion);
        ax.addParamTmp("ref_direccion", ref_direccion);
        ax.addParamTmp("usuario", usuario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("file", file);
        ax.addParamTmp("empresa", empresa);
//        ax.addParamTmp("comboT", comboT);
        ax.consumir();
    }
}

function getDetalleColaborador(id_colaborador)
{
    ax.setAccion("getDetalleColaborador");
    ax.addParamTmp("id_colaborador", id_colaborador);
    ax.consumir();
}
function onResponseDetalleColaborador(data)
{
    var cuerpo = '';
    var cabeza = ' <table class="table"><tbody>';
    cuerpo = '<tr>' +
            '<th width="20%">DNI:</th>' +
            '<td >' + data[0].dni + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Nombres:</th>' +
            '<td>' + data[0].nombres + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Apellidos:</th>' +
            '<td>' + data[0].ape_paterno + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Apellidos:</th>' +
            '<td>' + data[0].ape_materno + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Celular:</th>' +
            '<td>' + data[0].celular + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Tel&eacute;fono:</th>' +
            '<td>' + data[0].telefono + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Email:</th>' +
            '<td>' + data[0].email + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Direcci&oacute;n:</th>' +
            '<td>' + data[0].direccion + '</td>' +
            '</tr>' +
            '<tr>' +
            '<th width="20%">Referencia:</th>' +
            '<td>' + data[0].dir_referencia + '</td>' +
            '</tr>';
    var pie = '</table></tbody>';
    var html = cabeza + cuerpo + pie;
    $("#listar_detalle").append(html);
}

function getColaborador(id_colaborador)
{
    ax.setAccion("getColaborador");
    ax.addParamTmp("id_colaborador", id_colaborador);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    if (!isEmpty(data))
    {
        if (data[0].imagen == null || data[0].imagen == "")
        {
            data[0].imagen = "none.jpg"
        }
        var dir = "http://" + location.host + "/almacen/vistas/com/colaborador/imagen/" + data[0].imagen;

        $('#txt_dni').val(data[0].codigo_identificacion);
        $('#txt_nombre').val(data[0].nombre);
        $('#txt_apepaterno').val(data[0].apellido_paterno);
        $('#txt_apematerno').val(data[0].apellido_materno);
        $('#txt_telefono').val(data[0].telefono);
        $('#txt_celular').val(data[0].celular);
        $('#txt_email').val(data[0].email);
        $('#txt_direccion').val(data[0].direccion);
        $('#txt_refdireccion').val(data[0].direccion_referencia);
        asignarValorSelect2('cboEstado', data[0].estado);
        $('#myImg').src = dir;
        if (data[0]['id'] == data[0]['id_col_sesion'])
        {
            $('#cboEstado').disabled = true;
        }
        if (!isEmpty(data[0]['empresas_id']))
        {
            asignarValorSelect2("cboEmpresa", data[0]['empresas_id'].split(";"));
        }
    }
}
function updateColaborador(id, dni, nombre, paterno, materno, telefono, celular, email, direccion, ref_direccion, estado, file, empresa)
{
    if (validar_colaborador_form()) {
        deshabilitarBoton();
        ax.setAccion("updateColaborador");
        ax.addParamTmp("id_colaborador", id);
        ax.addParamTmp("dni", dni);
        ax.addParamTmp("nombre", nombre);
        ax.addParamTmp("paterno", paterno);
        ax.addParamTmp("materno", materno);
        ax.addParamTmp("telefono", telefono);
        ax.addParamTmp("celular", celular);
        ax.addParamTmp("email", email);
        ax.addParamTmp("direccion", direccion);
        ax.addParamTmp("ref_direccion", ref_direccion);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("file", file);
        ax.addParamTmp("empresa", empresa);
        ax.consumir();
    }
}

function confirmarDeleteColaborador(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás a " + nom + "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            deleteColaborador(id, nom);
//            swal("Eliminado!", "" + nom + ".", "success");
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
    //var res=confirm('Est\u00e1 seguro que desea eliminar el cupon especial '+nom+'?');

}

function deleteColaborador(id_colaborador, nom)
{
    ax.setAccion("deleteColaborador");
    ax.addParamTmp("id_colaborador", id_colaborador);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/colaborador/colaborador_listar.php");
}

function getAllEmpresa()
{
    ax.setAccion("getAllEmpresa");
    ax.consumir();
}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function cargarComponentesFormColaborador()
{
//    cargarSelect2();
    getAllEmpresa();
}

function nuevoColaborador()
{
    VALOR_ID_USUARIO = null;

    cargarDiv('#window', 'vistas/com/colaborador/colaborador_form.php');
    cargarComponentesFormColaborador();

}