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
$('#txt_usuario').keypress(function () {
    $('#msj_usuario').hide();
});


function limpiar_formulario_usuario()
{
    document.getElementById("frm_usuario").reset();
}

function cargarDivGetAgencia(id) {

    //sele envia un numero 1 para indicar que el combo se cargara para editar
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/agencia/agencia_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());

}
function getAgencia(id)
{
    loaderShow();
    ax.setAccion("getAgencia");
    ax.addParamTmp("id_agencia", id);
    ax.consumir();
}


function cargarDatagrid()
{
    ax.setAccion("getDataGridAgencia");
    ax.consumir();

}
function listarAgencias() {
    ax.setSuccess("successUsuario");
    cargarDatagrid();
}

function successUsuario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridAgencia':
                onResponseAjaxpGetDataGridAgencias(response.data);
                $('#datatable').dataTable({                    
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
            case 'getComboUbigeo':
                acciones.getComboUbigeo = true;
                llenarComboUbigeo(response.data);
                agenciaCargarData();
                break;
            case 'insertUsuario':
                exitoInsert(response.data);
                break;
            case 'getAgencia':
                acciones.getAgencia = true;
                dataPorId = response.data;
                agenciaCargarData();
                break;
            case 'updateUsuario':
                exitoUpdate(response.data);
                break;
            case 'deleteAgencia':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("¡Eliminado!", "Se eliminó agencia: " + response.data['0'].nombre + " | " + response.data['0'].descripcion , "success");
                    cargarDatagrid();

                } else {
                    swal("Cancelado", " " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
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

function onResponseAjaxpGetDataGridAgencias(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered" style="width: 100%"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Cod. Agencia</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
                "<th style='text-align:center;'>Direccion</th>" +
            "<th style='text-align:center;'>Ubigeo</th>" +
//            "<th style='text-align:center;'>Clave</th>" +
         "<th style='text-align:center;'>Estado</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
    $.each(data, function (index, item) {
        cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.codigo + "</td>" +
                "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                            "<td style='text-align:left;'>" + item.direccion + "</td>" +
                "<td style='text-align:left;'>" + item.ubigeo_codigo + " - " + item.ubigeo_dep + " | " + item.ubigeo_prov + " | " + item.ubigeo_dist + "</td>" + 

                "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.agencia_id + ")' ><b><i id='" + item.agencia_id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='cargarDivGetAgencia(" + item.agencia_id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteAgencia(" + item.agencia_id + ", \"" + item.codigo  +  "\", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);    
}



function confirmarDeleteAgencia(id, nom, des) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás agencia: " + nom + "|"+ des,
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
            deleteAgencia(id, nom, des);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function deleteAgencia(id_agencia, nom, des)
{
    ax.setAccion("deleteAgencia");
    ax.addParamTmp("id_agencia", id_agencia);
    ax.addParamTmp("nom", nom);
    ax.addParamTmp("des", des);
    ax.consumir();
}



//function obtenerPantallaPrincipalUsuario()
//{
//    acciones.iniciaAjaxTest(COMPONENTES.USUARIO, "successUsuario");
//    ax.setAccion("obtenerPantallaPrincipalUsuario");
//    ax.consumir();
//}


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

