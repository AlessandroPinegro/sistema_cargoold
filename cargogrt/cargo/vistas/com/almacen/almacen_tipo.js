var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;

$("#cbo_estado").change(function () {
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

function limpiar_formulario_almacen()
{
    document.getElementById("frm_almacen_tipo").reset();
}

function validar_almacen_tipo_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
     var empresa = document.getElementById("cbo_empresa").value;
    var estado = document.getElementById('cbo_estado').value;
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
    if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        bandera = false;
    }
    return bandera;
}

function cargarDivGetAlmacenTipo(id) {
    $("#window").empty();
    cargarDiv("#window", "vistas/com/almacen/almacen_tipo_form.php?id=" + id + "&" + "tipo=" + 1);
}
function listarAlmacenTipo() {
    breakFunction();
    acciones.iniciaAjaxTest(COMPONENTES.ALMACEN, "successPerfil");
    ax.setAccion("getDataGridAlmacenTipo");
    ax.consumir();
}
function successPerfil(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridAlmacenTipo':
                onResponseAjaxpGetDataGridAlmacenTipo(response.data);
//                $('#datatable').dataTable();
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
            case 'insertAlmacenTipo':
                exitoInsert(response.data);
                break;
            case 'getAlmacenTipo':
                llenarFormularioEditar(response.data);
                getComboEmpresaTipo(response.data[0]['id']);
                break;
            case 'updateAlmacenTipo':
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

            case 'deleteAlmacenTipo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                } else {
                    swal("Cancelado", "Upss!!. " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje + " en el mantenedor almacén", "error");
                }
                bandera_eliminar = true;
                break;
            case 'getComboEmpresaTipo':
                if (response.data[0]['cantidad'] == 1)
                {
                    $('#lb_empresa').hide();
                }
                if (bandera_getCombo == false)
                {
                    if (response.data[0]['cantidad'] == 1)
                    {
                        $('#combo_empresa').hide();
                    } else
                    {
                        $('#combo_empresa').show();
                    }
                    llenarComboEmpresa(response.data);
                    jQuery(".select2").select2({
                        width: '100%'
                    });
                    bandera_getCombo = true;
                }
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
        cargarDiv("#window", "vistas/com/almacen/almacen_tipo_listar.php");
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
        cargarDiv("#window", "vistas/com/almacen/almacen_tipo_listar.php");
    }
}

function onResponseAjaxpGetDataGridAlmacenTipo(data) {
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
                "<a href='#' onclick='cargarDivGetAlmacenTipo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteAlmacenTipo(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}
function guardarAlmacenTipo(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('cbo_estado').value;

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
        updateAlmacenTipo(id, descripcion, codigo, comentario, estado, empresa, comboT);
    } else {
        insertAlmacenTipo(descripcion, codigo, comentario, estado, usu_creacion, empresa, comboT);
    }
}

function insertAlmacenTipo(descripcion, codigo, comentario, estado, usu_creacion, empresa, comboT)
{
    if (validar_almacen_tipo_form()) {
        deshabilitarBoton();
        ax.setAccion("insertAlmacenTipo");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("comboT", comboT);
        ax.consumir();
    }
}
function getAlmacenTipo(id_almacen_tipo)
{
    ax.setAccion("getAlmacenTipo");
    ax.addParamTmp("id_almacen_tipo", id_almacen_tipo);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    document.getElementById('cbo_estado').value = data[0].estado;
}
function updateAlmacenTipo(id, descripcion, codigo, comentario, estado, empresa, comboT)
{
    if (validar_almacen_tipo_form()) {
        deshabilitarBoton();
        ax.setAccion("updateAlmacenTipo");
        ax.addParamTmp("id_alm_tipo", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("combo", comboT);
        ax.consumir();
    }
}
function confirmarDeleteAlmacenTipo(id, nom) {
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
            deleteAlmacenTipo(id, nom);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteAlmacenTipo(id_alm_tipo, nom)
{
    ax.setAccion("deleteAlmacenTipo");
    ax.addParamTmp("id_alm_tipo", id_alm_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/almacen/almacen_tipo_listar.php");
}
function getComboEmpresaTipo(id)
{
    ax.setAccion("getComboEmpresaTipo");
    ax.addParamTmp("id_tipo", id);
    ax.consumir();
}

function llenarComboEmpresa(data)
{
    var cuerpo_total = '';
    var cuerpo = '';
    var pie = '';
    var disabled = '';
    var texto = 'Empresa...';
    var tamData = 0;

    if (data[0].id_bandera == null)
    {
        tamData = data[0].llenar;
        if (data[0].llenar > 0) {
            $.each(data, function (index, item) {
                cuerpo = '<option value="' + item.id + '">' + item.razon_social + '</option>';
                cuerpo_total = cuerpo_total + cuerpo;
            });
        } else {
            disabled = 'disabled';
            texto = 'No hay datos registrados';
        }
    } else {
        tamData = data[0].llenar;
        if (data[0].llenar > 0) {
            $.each(data, function (index, item) {
                if (item.estado == 1)
                {
                    cuerpo = '<option value="' + item.id + '" selected>' + item.razon_social + '</option>';
                } else
                {

                    cuerpo = '<option value="' + item.id + '" >' + item.razon_social + '</option>';
                }
                cuerpo_total = cuerpo_total + cuerpo;
            });
        } else
        {
            disabled = 'disabled';
            texto = 'No hay datos registrados';
        }
    }

    var cabeza = '<select id="cbo_empresa" name="cbo_empresa" onchange="onchangeEmpresa()"  class="select2" multiple data-placeholder="' + texto + '"' + disabled + '>';
    pie = '</select>';
    var html = cabeza + cuerpo_total + pie;
    $("#combo_empresa").append(html);
    if (tamData === 1)
    {
        $('#cbo_empresa').multiSelect('select_all');
    }
}