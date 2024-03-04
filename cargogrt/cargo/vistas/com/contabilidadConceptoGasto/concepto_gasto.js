var c = $('#env i').attr('class');
var bandera_eliminar = false;

$(document).ready(function () {
    ax.setSuccess("successConceptoGasto");
    listarConceptoGasto();
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
function cambiarEstado(id){
    ax.setAccion("cambiarEstadoConceptoGasto");
    ax.addParamTmp("id", id);
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
    document.getElementById("frm_concepto_gasto").reset();
}

function validar_concepto_gasto_form() {
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

function listarConceptoGasto() {
    ax.setAccion("listarConceptoGasto");
    ax.consumir();
}
function successConceptoGasto(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarConceptoGasto':
                onResponseAjaxpGetDataGridConceptoGasto(response.data);
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
            case 'guardarConceptoGasto':
                exitoInsert(response.data);
                break;

            case 'obtenerConceptoGasto':
                llenarFormularioEditar(response.data);
                break;

            case 'cambiarEstadoConceptoGasto':
                if (response.data[0]['vout_exito'] == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                    listarConceptoGasto();
                }
                break;

            case 'eliminarConceptoGasto':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].concepto_gasto_descripcion + ".", "success");
                    listarConceptoGasto();
                } else {
                    swal("Cancelado", "", "error");
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
        loaderClose();
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else
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
    } else
    {
        loaderClose();
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function onResponseAjaxpGetDataGridConceptoGasto(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var icono = 'ion-checkmark-circled';
            var color = '#5cb85c;';
            if (item.estado == 0) {
                icono = 'ion-flash-off';
                color = '#cb2a2a;';
            }

            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + formatearCadena(item.comentario) + "</td>" +
                    "<td style='text-align:center;'><a onclick = 'cambiarEstado(" + item.id + ")' ><b><i class='" + icono + "' style='color:" + color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarConceptoGasto(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteConceptoGasto(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
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
function guardarConceptoGasto()
{
    
    var id = document.getElementById('id').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    comentario = comentario.replace(/\n/g, "<br>");
    var estado = $('#cboEstado').val();

    if (validar_concepto_gasto_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("guardarConceptoGasto");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("conceptoGastoId", id);
        ax.consumir();
    }
}

function obtenerConceptoGasto(id)
{
    ax.setAccion("obtenerConceptoGasto");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    if (!isEmpty(data[0].comentario))
    {
        document.getElementById('txt_comentario').value = data[0].comentario.replace(/<br>/g, "\n");
        ;
    }
    asignarValorSelect2('cboEstado', data[0].estado);
    loaderClose();
}

function confirmarDeleteConceptoGasto(id, nom) {
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
            eliminarConceptoGasto(id);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function eliminarConceptoGasto(id){
    ax.setAccion("eliminarConceptoGasto");
    ax.addParamTmp("id", id);   
    ax.consumir();
}

function cargarSelect2(){
    $(".select2").select2({
        width: '100%'
    });
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function nuevoConceptoGasto(){
    loaderShow(null);
    cargarDivTitulo('#window', 'vistas/com/contabilidadConceptoGasto/concepto_gasto_form.php', "Nuevo " + obtenerTitulo());
    loaderClose();
}

function editarConceptoGasto(id) {

    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/contabilidadConceptoGasto/concepto_gasto_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
    obtenerConceptoGasto(id);
}

function cargarPantallaListar()
{
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/contabilidadConceptoGasto/concepto_gasto_listar.php", tituloGlobal);
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