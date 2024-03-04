var c = $('#env i').attr('class');
var bandera_eliminar = false;

$(document).ready(function () {
//    loaderShow(null);
    listarUnidadTipo();
});

$("#cboEstado").change(function () {
    $('#msj_estado').hide();
});
function deshabilitarBoton()
{
    $("#env").addClass('disabled');
//    $("#env i").removeClass(c);
//    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
//    $("#env i").removeClass('fa-spinner fa-spin');
//    $("#env i").addClass(c);
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
//    document.getElementById(data[0].id_estado).className = data[0].icono;
//    document.getElementById(data[0].id_estado).style.color = data[0].color;
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

function limpiar_formulario_unidad()
{
    document.getElementById("frm_unidad_tipo").reset();
}

function validar_unidad_tipo_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var estado = $('#cboEstado').val();
//    var comentario = document.getElementById('txt_comentario').value;

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

function listarUnidadTipo() {
    ax.setSuccess("successUnidadTipo");
    cargarDatagrid();
//    ax.setAccion("getDataGridUnidadTipo");
//    ax.consumir();
}
function successUnidadTipo(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridUnidadTipo':
                onResponseAjaxpGetDataGridUnidadTipo(response.data);
//                $('#datatable').dataTable();
                $('#datatable').dataTable({
                    "scrollX": true,
                    "autoWidth": true,
                    "order": [[0, "desc"]],
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
            case 'insertUnidadTipo':
                
                exitoInsert(response.data);
                break;

            case 'getUnidadTipo':
                llenarFormularioEditar(response.data);
                break;
            case 'updateUnidadTipo':
                
                exitoUpdate(response.data);
                break;
            case 'cambiarTipoEstado':
                if (response.data[0]['vout_exito'] == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                    cargarDatagrid();
                }
                break;
            case 'deleteUnidadTipo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                    cargarDatagrid();
                } else {
                    swal("Cancelado", "" + response.data['0'].nombre + " " + response.data['0'].vout_mensaje + " en el mantenedor "+response.data['0'].mantenedor, "error");
                }
                bandera_eliminar = true;
                break;
        }
    }
}
function cargarDatagrid()
{
    ax.setAccion("getDataGridUnidadTipo");
    ax.consumir();
    
}
function exitoUpdate(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        loaderClose();
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        loaderClose();
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        loaderClose();
        habilitarBoton();
        $.Notification.notify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        loaderClose();
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function onResponseAjaxpGetDataGridUnidadTipo(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
            "<th style='text-align:center;'>Unidad base</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + item.unidad_medida_base + "</td>" +
                    "<td style='text-align:left;'>" + formatearCadena(item.comentario) + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
//                "<i class='fa  fa-file-text' onclick='cargarDivGetColaboradorDetalle(" + item.id + ")' style='color:#088A68;'></i>&nbsp;\n" +
                    "<a href='#' onclick='editarUnidadTipo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteUnidadTipo(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
    
    modificarAnchoTabla('datatable');
}
function guardarUnidadTipo(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    comentario = comentario.replace(/\n/g, "<br>");
    var estado = $('#cboEstado').val();
    if (tipo == 1)
    {
        updateUnidadTipo(id, descripcion, codigo, comentario, estado);
    } else {
        insertUnidadTipo(descripcion, codigo, comentario, estado, usu_creacion);
    }
}

function insertUnidadTipo(descripcion, codigo, comentario, estado, usu_creacion)
{
    if (validar_unidad_tipo_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertUnidadTipo");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.consumir();
    }
}
function getUnidadTipo(id_unidad_tipo)
{
    ax.setAccion("getUnidadTipo");
    ax.addParamTmp("id_unidad_tipo", id_unidad_tipo);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    if(!isEmpty(data[0].comentario))
    {
        document.getElementById('txt_comentario').value = data[0].comentario.replace(/<br>/g, "\n");;
    }
    asignarValorSelect2('cboEstado', data[0].estado);
    loaderClose();
}
function updateUnidadTipo(id, descripcion, codigo, comentario, estado)
{
    if (validar_unidad_tipo_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("updateUnidadTipo");
        ax.addParamTmp("id_uni_tipo", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function confirmarDeleteUnidadTipo(id, nom) {
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
            deleteUnidadTipo(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteUnidadTipo(id_uni_tipo, nom)
{
    ax.setAccion("deleteUnidadTipo");
    ax.addParamTmp("id_uni_tipo", id_uni_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
//    cargarPantallaListar();
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

function nuevaUnidadTipo()
{
    loaderShow(null);
    cargarDivTitulo('#window', 'vistas/com/unidad/unidad_tipo_form.php',"Nuevo "+obtenerTitulo());
    loaderClose();
}

function editarUnidadTipo(id) {

    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/unidad/unidad_tipo_form.php?id=" + id + "&" + "tipo=" + 1,"Editar "+obtenerTitulo());
    getUnidadTipo(id);
}

function cargarPantallaListar()
{
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/unidad/unidad_tipo_listar.php",tituloGlobal);
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