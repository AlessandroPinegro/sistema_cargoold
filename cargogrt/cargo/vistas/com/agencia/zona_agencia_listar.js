var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;
var acciones = {


    getComboAgencia: false,

    getZona: false
};

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

function cargarComboAgencia(tipo) {

    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getDataGridAgencia");
    ax.consumir();
 
    //retorna los datos de la tabla empresa para llenar el combo
    //retorna los datos de los usuarios para llenar el combo
    getZona(VALOR_ID_USUARIO);
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
    ax.setAccion("cambiarEstadoZona");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data) {
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
$('#txt_usuario').keypress(function () {
    $('#msj_usuario').hide();
});


function limpiar_formulario_usuario() {
    document.getElementById("frm_usuario").reset();
}

function cargarDivGetZona(id) {

    //sele envia un numero 1 para indicar que el combo se cargara para editar
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/agencia/zona_agencia_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());

}
function getZona(id) {
    loaderShow();
    ax.setAccion("getZona");
    ax.addParamTmp("id_zona", id);
    ax.consumir();
}


function cargarDatagrid() {
    ax.setAccion("getDataGridAgenciaZona");
    ax.consumir();

}
function listarAgencias() {
    ax.setSuccess("successUsuario");
    cargarDatagrid();
}

function successUsuario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridAgenciaZona':
                onResponseAjaxpGetDataGridAgenciasZona(response.data);
                $('#datatable').dataTable({                    
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
            case 'getDataGridAgencia':
                acciones.getComboAgencia = true;
                llenarComboAgencia(response.data);
                break;
            // case 'getComboUbigeo':
            //     acciones.getComboUbigeo = true;
            //     llenarComboUbigeo(response.data);
            //     agenciaCargarData();
            //     break;

            case 'insertUsuario':
                exitoInsert(response.data);
                break;
            case 'getZona':
                acciones.getZona = true;
                dataPorId = response.data;
                zonaCargarData();
                break;
            case 'updateUsuario':
                exitoUpdate(response.data);
                break;
            case 'deleteZona':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                    cargarDatagrid();

                } else {
                    swal("Cancelado", " " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'cambiarEstadoZona':
                cambiarIconoEstado(response.data);
                break;
            case 'obtenerContrasenaActual':
                if (validarCambiarContrasena(response.data[0]['clave'])) {
                    cambiarContrasena();
                }
                break;
            case 'cambiarContrasena':
                exitoCambioContrasena(response.data)
                break;
            case 'obtenerPantallaPrincipalUsuario':
                url_cancelar = response.data[0]['url'];
                break;

        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'cambiarContrasena':
                habilitarBoton();
                break;

        }
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

function onResponseAjaxpGetDataGridAgenciasZona(data) {
    console.log(data);
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered" ><thead>' +
        " <tr>" +
        "<th style='text-align:center;'>Agencia</th>" +
        "<th style='text-align:center;'>Zona</th>" +
        "<th style='text-align:center;'>Estado</th>" +
        "<th style='text-align:center;'>Acciones</th>" +
        "</tr>" +
        "</thead>";
    $.each(data, function (index, item) {
        cuerpo = "<tr>" +
            "<td style='text-align:left;'>" + item.descripcion + "</td>" +
            "<td style='text-align:left;'>" + item.zona + "</td>" +
            "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
            "<td style='text-align:center;'>" +
            "<a href='#' onclick='cargarDivGetZona(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
            "<a href='#' onclick='confirmarDeleteZona(" + item.id + ", \"" + item.zona + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
            "</td>" +
            "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);
}

function confirmarDeleteZona(id,zona) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + zona + "",
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
            deleteZona(id,zona);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function deleteZona(id_zona,zona) {
    ax.setAccion("deleteZona");
    ax.addParamTmp("id_zona", id_zona);
    ax.addParamTmp("zona", zona);
    ax.consumir();
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

function cancelarCambiarContrasena()
{
     $("#window").empty();
}