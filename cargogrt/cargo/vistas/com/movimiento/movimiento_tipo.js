var c = $('#env i').attr('class');
var bandera_eliminar = false;
function asignarValorSelect2(id,valor)
{
    $("#"+id).select2().select2("val",valor);
    $("#"+id).select2({width: '100%'});
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
function cargarDivMovimientoTipo(div, url)
{
    $('div').remove('.sweet-overlay');
    $('div').remove('.sweet-alert');
    $("#window").html("");
    $(div).load(url);
}
function cambiarIconoEstado(data)
{
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function cargarDivGetMovimientoTipo(id) {
    $("#window").empty();
    loaderShow(null);
    cargarDivMovimientoTipo("#window", "vistas/com/movimiento/movimiento_tipo_form.php?id=" + id + "&" + "tipo=" + 1);
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
function listarMovimientoTipo() {
//    breakFunction();
//    acciones.iniciaAjaxTest(COMPONENTES.BIEN, "successBien");
    ax.setSuccess("successMovimientoTipo");
    ax.setAccion("getDataGridMovimientoTipo");
    ax.consumir();
}
function successMovimientoTipo(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridMovimientoTipo':
                onResponseAjaxpGetDataGridMovimientoTipo(response.data);
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
                loaderClose();
                break;
            case 'insertMovimientoTipo':
                exitoInsertMovimientoTipo(response.data);
                break;
            case 'getMovimientoTipo':
                llenarFormularioMovimientoTipoEditar(response.data);
            break;
            case 'updateMovimientoTipo':
                exitoUpdateMovimientoTipo(response.data);
                break;
            case 'deleteMovimientoTipo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                }
                bandera_eliminar = true;
                break;
            case 'cambiarMovimientoTipoEstado':
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
function exitoInsertMovimientoTipo(data)
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
        cargarDivMovimientoTipo("#window", "vistas/com/movimiento/movimiento_tipo_listar.php");
    }
}
function exitoUpdateMovimientoTipo(data)
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
        cargarDivMovimientoTipo("#window", "vistas/com/movimiento/movimiento_tipo_listar.php");
    }
}
function onResponseAjaxpGetDataGridMovimientoTipo(data) {
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=80px>Codigo</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'>Indicador</th>" +
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
                    "<td style='text-align:right;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + comentario + "</td>" +
                    "<td style='text-align:left;'>" + item.indicador_descripcion + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='cargarDivGetMovimientoTipo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteMovimientoTipo(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}
function guardarMovimientoTipo(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('cbo_estado').value;
    var indicador = document.getElementById('cbo_indicador').value;

    if (tipo == 1)
    {
        updateMovimientoTipo(id, descripcion, codigo,indicador, comentario, estado);
    } else {
        insertMovimientoTipo(descripcion, codigo,indicador, comentario, estado, usu_creacion);
    }
}
function insertMovimientoTipo(descripcion, codigo,indicador, comentario, estado, usu_creacion)
{
    if (validar_form()) {
        deshabilitarBoton();
        ax.setAccion("insertMovimientoTipo");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("indicador", indicador);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.consumir();
    }
}
function getMovimientoTipo(id)
{
    ax.setAccion("getMovimientoTipo");
    ax.addParamTmp("id", id);
    ax.consumir();
}
function llenarFormularioMovimientoTipoEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    asignarValorSelect2('cbo_indicador',data[0].indicador);
    asignarValorSelect2('cbo_estado',data[0].estado);
    loaderClose();
}
function updateMovimientoTipo(id, descripcion, codigo,indicador, comentario, estado)
{
    if (validar_form()) {
        deshabilitarBoton();
        ax.setAccion("updateMovimientoTipo");
        ax.addParamTmp("id", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("indicador", indicador);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function confirmarDeleteMovimientoTipo(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminaras el tipo de movimiento" + nom + "!",
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
            deleteMovimientoTipo(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteMovimientoTipo(id, nom)
{
    ax.setAccion("deleteMovimientoTipo");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/movimiento/movimiento_tipo_listar.php");
}
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarMovimientoTipoEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}