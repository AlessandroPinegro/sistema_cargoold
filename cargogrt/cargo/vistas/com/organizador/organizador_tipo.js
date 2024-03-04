var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;

$("#cboEstado").change(function () {
    $('#msj_estado').hide();
});
function onchangeEmpresa()
{
    $('#msj_empresa').hide();
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
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarTipoEstado");
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
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});
$('#txt_comentario').keypress(function () {
    $('#msj_comentario').hide();
});

function limpiar_formulario_organizador()
{
    document.getElementById("frm_organizador_tipo").reset();
}

function validar_organizador_tipo_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var estado = document.getElementById('cboEstado').value;
    if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0)
    {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingrese una descripción").show();
        bandera = false;
    }
    if (codigo == "" || codigo == null || espacio.test(codigo) || codigo.length == 0)
    {
        $("msj_codigo").removeProp(".hidden");
        $("#msj_codigo").text("Ingrese un código").show();
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

function listarOrganizadorTipo() {
    ax.setSuccess("successOrganizador");
    cargarDatagridOrganizadorTipo();
}

function cargarDatagridOrganizadorTipo()
{
    ax.setAccion("getDataGridOrganizadorTipo");
    ax.consumir();
}
function successOrganizador(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridOrganizadorTipo':
                onResponseAjaxpGetDataGridOrganizadorTipo(response.data);
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
            case 'insertOrganizadorTipo':
                exitoInsert(response.data);
                break;
            case 'getOrganizadorTipo':
                llenarFormularioEditar(response.data);
                break;
            case 'updateOrganizadorTipo':
                exitoUpdate(response.data);
                break;
            case 'cambiarTipoEstado':
                if (response.data[0]['vout_exito'] == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                }
                break;

            case 'deleteOrganizadorTipo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                    cargarDatagridOrganizadorTipo();
                } else {
                    swal("Cancelado",response.data['0'].nombre + " " + response.data['0'].vout_mensaje + " en el mantenedor organizador", "error");
                }
                bandera_eliminar = true;
                break;
        }
    }
}

function exitoUpdate(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();;
    }
}
function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();;
    }
}

function onResponseAjaxpGetDataGridOrganizadorTipo(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;' width=100px>Estado</th>" +
            "<th style='text-align:center;' width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if(!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var comentario = item.comentario;
            if (item.comentario == null)
            {
                comentario = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + comentario + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarOrganizadorTipo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteOrganizadorTipo(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
    loaderClose();
}
function guardarOrganizadorTipo(tipo)
{
    
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('cboEstado').value;

    if (tipo == 1)
    {
        updateOrganizadorTipo(id, descripcion, codigo, comentario, estado);
    } else {
        insertOrganizadorTipo(descripcion, codigo, comentario, estado, usu_creacion);
    }
}

function insertOrganizadorTipo(descripcion, codigo, comentario, estado, usu_creacion)
{
    if (validar_organizador_tipo_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertOrganizadorTipo");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.consumir();
    }
}
function getOrganizadorTipo(id_organizador_tipo)
{
    ax.setAccion("getOrganizadorTipo");
    ax.addParamTmp("id_organizador_tipo", id_organizador_tipo);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    asignarValorSelect2('cboEstado',data[0].estado);
    loaderClose();
}
function updateOrganizadorTipo(id, descripcion, codigo, comentario, estado)
{
    if (validar_organizador_tipo_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("updateOrganizadorTipo");
        ax.addParamTmp("id_alm_tipo", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function confirmarDeleteOrganizadorTipo(id, nom) {
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
            deleteOrganizadorTipo(id, nom);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteOrganizadorTipo(id_alm_tipo, nom)
{
    ax.setAccion("deleteOrganizadorTipo");
    ax.addParamTmp("id_alm_tipo", id_alm_tipo);
    ax.addParamTmp("nom", nom);
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

function nuevoOrganizadorTipo()
{
    loaderShow(null);
    cargarDivTitulo('#window', 'vistas/com/organizador/organizador_tipo_form.php',"Nuevo "+obtenerTitulo());
    loaderClose();
}

function editarOrganizadorTipo(id) {
    cargarDivTitulo("#window", "vistas/com/organizador/organizador_tipo_form.php?id=" + id + "&" + "tipo=" + 1,"Editar "+obtenerTitulo());
    loaderShow(null);
    getOrganizadorTipo(id);
}

function obtenerTitulo()
{
    tituloGlobal = $("#titulo").text();
    var titulo =  tituloGlobal;
    $("#window").empty();
    
    if(!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar()
{
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/organizador/organizador_tipo_listar.php",tituloGlobal);
}