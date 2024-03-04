var c = $('#env i').attr('class');
var bandera_eliminar = false;
function cargarDivMotivoSalida(div, url)
{
    $('div').remove('.sweet-overlay');
    $('div').remove('.sweet-alert');
    $("#window").html("");
    $(div).load(url);
}
function cambiarIconoEstado(data)
{
    alert("cambiar icono");
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function cargarDivGetBienMotivoSalida(id) {
    $("#window").empty();
    cargarDivMotivoSalida("#window", "vistas/com/bien/bien_motivo_salida_form.php?id=" + id + "&" + "tipo=" + 1);
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
function validar_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var estado = document.getElementById('cbo_estado').value;
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
    return bandera;
}
function listarBienMotivoSalida() {
//    breakFunction();
//    acciones.iniciaAjaxTest(COMPONENTES.BIEN, "successBien");
    ax.setSuccess("successBienMotivoSalida");
    ax.setAccion("getDataGridBienMotivoSalida");
    ax.consumir();
}
function successBienMotivoSalida(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridBienMotivoSalida':
                onResponseAjaxpGetDataGridBienMotivoSalida(response.data);
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
            case 'insertBienMotivoSalida':
                exitoInsertBienMotivoSalida(response.data);
                break;
            case 'getBienMotivoSalida':
                llenarFormularioMotivoSalidaEditar(response.data);
            break;
            case 'updateBienMotivoSalida':
                exitoUpdateBienMotivoSalida(response.data);
                break;
            case 'deleteBienMotivoSalida':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                }
//                else {
//                    swal("Cancelado", "Upss!!. " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje + " en el mantenedor unidades", "error");
//                }
                bandera_eliminar = true;
                break;
            case 'cambiarBienMotivoSalidaEstado':
                if (response.data[0]['vout_exito'] == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                }
                break;
        }
    }
}
function exitoInsertBienMotivoSalida(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.notify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarDivMotivoSalida("#window", "vistas/com/bien/bien_motivo_salida_listar.php");
    }
}
function exitoUpdateBienMotivoSalida(data)
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
        cargarDivMotivoSalida("#window", "vistas/com/bien/bien_motivo_salida_listar.php");
    }
}
function onResponseAjaxpGetDataGridBienMotivoSalida(data) {
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=80px>Codigo</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'width=80px>Estado</th>" +
            "<th style='text-align:center;'width=80px>Acciones</th>" +
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
                    "<td style='text-align:center;'>" + comentario + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='cargarDivGetBienMotivoSalida(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteBienMotivoSalida(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}
function guardarBienMotivoSalida(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('cbo_estado').value;

    if (tipo == 1)
    {
        updateBienMotivoSalida(id, descripcion, codigo, comentario, estado);
    } else {
        insertBienMotivoSalida(descripcion, codigo, comentario, estado, usu_creacion);
    }
}
function insertBienMotivoSalida(descripcion, codigo, comentario, estado, usu_creacion)
{
    if (validar_form()) {
        deshabilitarBoton();
        ax.setAccion("insertBienMotivoSalida");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.consumir();
    }
}
function getBienMotivoSalida(id)
{
    ax.setAccion("getBienMotivoSalida");
    ax.addParamTmp("id", id);
    ax.consumir();
}
function llenarFormularioMotivoSalidaEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    document.getElementById('cbo_estado').value = data[0].estado;

}
function updateBienMotivoSalida(id, descripcion, codigo, comentario, estado)
{
    if (validar_bien_tipo_form()) {
        deshabilitarBoton();
        ax.setAccion("updateBienMotivoSalida");
        ax.addParamTmp("id", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function confirmarDeleteBienMotivoSalida(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminaras el motivo de salida " + nom + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            deleteBienMotivoSalida(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteBienMotivoSalida(id, nom)
{
    ax.setAccion("deleteBienMotivoSalida");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/bien/bien_motivo_salida_listar.php");
}
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarBienMotivoSalidaEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}