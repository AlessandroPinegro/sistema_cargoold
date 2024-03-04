var bandera_eliminar = false;
var bandera_getCombo = false;

$("#estado").change(function () {
    $('#msj_estado').hide();
});
function onchangeEmpresa()
{
    $('#msj_empresa').hide();
}
function deshabilitarBoton()
{
    $("#env").addClass('disabled');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
}
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});

function limpiar_formulario_servicio()
{
    document.getElementById("frm_servicio").reset();
}

function validar_servicio_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var estado = document.getElementById('estado').value;
    var empresa = document.getElementById("cboEmpresa").value;
    if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0)
    {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar una descripción").show();
        bandera = false;
    }
    if (codigo == "" || codigo == null || espacio.test(codigo) || codigo.length == 0)
    {
        $("msj_codigo").removeProp(".hidden");
        $("#msj_codigo").text("Ingresar un código").show();
        bandera = false;
    }
    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
    {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }
    if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        bandera = false;
    }
    return bandera;
}
function listarServicios() {
    ax.setSuccess("successServicio");
    cargarDatagridServicio();
}

function cargarDatagridServicio()
{
    ax.setAccion("getDataGridServicio");
    ax.consumir();
}
function successServicio(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridServicio':
                onResponseAjaxpGetDataGridServicio(response.data);
                $('#datatable').dataTable({
                    "scrollX":true,
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
                });
                loaderClose();
                break;
            case 'insertServicio':
                loaderClose();
                exitoInsert(response.data);
                break;

            case 'getServicio':
                llenarFormularioEditar(response.data);
                break;
            case 'updateServicio':
                loaderClose();
                exitoUpdate(response.data);
                break;

            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                cargarDatagridServicio();
                break;

            case 'deleteServicio':
                var error = response.data['0'].vout_exito;
                if (error == 1) {
                    swal("Eliminado!", "" + response.data['0'].nombre + ".", "success");
                    cargarDatagridServicio();
                } else {
                    swal("Cancelado", "Upss!!." + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);

                if (!isEmpty(VALOR_ID_USUARIO))
                {
                    getServicio(VALOR_ID_USUARIO);
                }
//                loaderClose();
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
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridServicio(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=100px>Código</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;' >Comentario</th>" +
            "<th style='text-align:center;' width=100px>Estado</th>" +
            "<th style='text-align:center;' width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var comentario = item.comentario;
            if (item.comentario == null)
            {
                comentario = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:center;'>" + item.codigo + "</td>" +
                    "<td style='text-align:center;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:center;'>" + formatearCadena(item.comentario) + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i  id='" + item.id + "'  class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarServicio(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteServicio(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
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
function guardarServicio(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('estado').value;
    comentario = comentario.replace(/\n/g, "<br>");
    var empresa = $('#cboEmpresa').val();

    if (tipo == 1)
    {
        updateServicio(id, descripcion, comentario, estado, codigo, empresa);
    } else {
        insertServicio(descripcion, comentario, estado, usu_creacion, codigo, empresa);
    }
}
function insertServicio(descripcion, comentario, estado, usu_creacion, codigo, empresa)
{
    if (validar_servicio_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertServicio");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("empresa", empresa);
        ax.consumir();
    }
}
function getServicio(id_servicio)
{
    ax.setAccion("getServicio");
    ax.addParamTmp("id_servicio", id_servicio);
    ax.consumir();
}
function llenarFormularioEditar(data)
{
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    if(!isEmpty(data[0].comentario))
    {
        document.getElementById('txt_comentario').value = data[0].comentario.replace(/<br>/g, "\n");
    }
    asignarValorSelect2('estado', data[0].estado);
    if (!isEmpty(data[0]['empresas_id']))
    {
        asignarValorSelect2("cboEmpresa", data[0]['empresas_id'].split(";"));
    }

}

function updateServicio(id, descripcion, comentario, estado, codigo, empresa)
{
    if (validar_servicio_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("updateServicio");
        ax.addParamTmp("id_servicio", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("empresa", empresa);
        ax.consumir();
    }
}

function confirmarDeleteServicio(id, nom) {
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
            deleteServicio(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteServicio(id_servicio, nom)
{
    ax.setAccion("deleteServicio");
    ax.addParamTmp("id_servicio", id_servicio);
    ax.addParamTmp("nom", nom);
    ax.consumir();
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

function cargarComponentesFormServicio()
{
    getAllEmpresa();
}

function nuevoServicio()
{
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/servicio/servicio_form.php', "Nuevo " + obtenerTitulo());
    cargarComponentesFormServicio();
}

function editarServicio(id) {
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/servicio/servicio_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
    cargarComponentesFormServicio();
}

function onResponseGetAllEmpresas(data) {

    if (!isEmpty(data))
    {
        $('#cboEmpresa').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
        });
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
    cargarDivTitulo("#window", "vistas/com/servicio/servicio_listar.php", tituloGlobal);
}