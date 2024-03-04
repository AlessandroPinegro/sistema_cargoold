var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;
$("#cbo_estado").change(function () {
    $('#msj_estado').hide();
});
function onchangeEmpresa()
{
    $('#msj_empresa').hide();

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
    alert(empresa.length);
    getComboAlmacenTipo(null, empresa, comboT);
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
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});
$('#txt_simbolo').keypress(function () {
    $('#msj_simbolo').hide();
});
$('#txt_simbolo').keypress(function () {
    $('#msj_simbolo').hide();
});

function cargarDivGetAlmacen(id) {
    $("#window").empty();
    cargarDiv("#window", "vistas/com/almacen/almacen_form.php?id=" + id + "&" + "tipo=" + 1);
}

//$('#cbo_tipo').keypress(function () {
//    $('#msj_tipo').hide();
//});
function onchangeTipoAlmacen()
{
    $('#msj_tipo').hide();
}

function limpiar_formulario_almacen()
{
    document.getElementById("frm_almacen").reset();
}

function validar_almacen_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo = document.getElementById('cbo_tipo').value;
    var estado = document.getElementById('cbo_estado').value;
    var empresa = document.getElementById("cbo_empresa").value;
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

    if (tipo == "" || tipo == null || espacio.test(tipo) || tipo.length == 0)
    {
        $("msj_tipo").removeProp(".hidden");
        $("#msj_tipo").text("Ingresar un tipo de almacen").show();
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

function cargarDivGetAlmacen(id) {
    $("#window").empty();
    cargarDiv("#window", "vistas/com/almacen/almacen_form.php?id=" + id + "&" + "tipo=" + 1);
}
function listarAlmacen() {
    breakFunction();
    acciones.iniciaAjaxTest(COMPONENTES.ALMACEN, "successAlmacen");
    ax.setAccion("getDataGridAlmacen");
    ax.consumir();
}
function successAlmacen(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridAlmacen':
                onResponseAjaxpGetDataGridAlmacen(response.data);
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

            case 'getComboTipoAlmacen':
                llenarComboAlmacenTipo(response.data);
                $('#cbo_tipo').combobox();
                break;
            case 'insertAlmacen':
                exitoInsert(response.data);
                break;

            case 'getAlmacen':
                llenarFormularioEditar(response.data);
                getComboEmpresa(response.data[0]['id']);
                break;
            case 'updateAlmacen':
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'deleteAlmacen':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
///////
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
//////
                } else {
                    swal("Cancelado", "Upss!!.El tipo de almacen " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'getComboEmpresa':
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
        cargarDiv("#window", "vistas/com/almacen/almacen_listar.php");
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
        cargarDiv("#window", "vistas/com/almacen/almacen_listar.php");
    }
}

function onResponseAjaxpGetDataGridAlmacen(data) {
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'>Tipo</th>" +
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
                "<td style='text-align:center;'>" + item.a_descripcion + "</td>" +
                "<td style='text-align:center;'>" + comentario + "</td>" +
                "<td style='text-align:center;'>" + item.ta_descripcion + "</td>" +
                "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='cargarDivGetAlmacen(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteAlmacen(" + item.id + ", \"" + item.a_descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}
function guardarAlmacen(tipo_accion)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_almacen = document.getElementById('cbo_tipo').value;
    var estado = document.getElementById('cbo_estado').value;
    var comentario = document.getElementById('txt_comentario').value;

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

    if (tipo_accion == 1)
    {
        updateAlmacen(id, descripcion, codigo, tipo_almacen, estado, comentario, empresa, comboT);
    } else {
        insertAlmacen(descripcion, codigo, tipo_almacen, estado, usu_creacion, comentario, empresa, comboT);
    }
}

function insertAlmacen(descripcion, codigo, tipo_almacen, estado, usu_creacion, comentario, empresa, comboT)
{
    if (validar_almacen_form()) {
        deshabilitarBoton();
        ax.setAccion("insertAlmacen");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo_almacen);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("comboT", comboT);
        ax.consumir();
    }
}
function getAlmacen(id_almacen)
{
    ax.setAccion("getAlmacen");
    ax.addParamTmp("id_almacen", id_almacen);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    document.getElementById('cbo_estado').value = data[0].estado;
    getComboAlmacenTipo(data[0].tipo_almacen_id);
}
function updateAlmacen(id, descripcion, codigo, tipo, estado, comentario, empresa, comboT)
{
    if (validar_almacen_form()) {
        deshabilitarBoton();
        ax.setAccion("updateAlmacen");
        ax.addParamTmp("id_alm", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("combo", comboT);
        ax.consumir();
    }
}
function confirmarDeleteAlmacen(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + nom + "",
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
            deleteAlmacen(id, nom);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
    //var res=confirm('Est\u00e1 seguro que desea eliminar el cupon especial '+nom+'?');
}

function deleteAlmacen(id_alm_tipo, nom)
{
    ax.setAccion("deleteAlmacen");
    ax.addParamTmp("id_alm", id_alm_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/almacen/almacen_listar.php");
}

function getComboAlmacenTipo(id, $empresa, comboT)
{
    ax.setAccion("getComboTipoAlmacen");
    ax.addParamTmp("id_tipo", id);
    ax.addParamTmp("empresa", id);
    ax.addParamTmp("combo", id);
    ax.consumir();
}

function llenarComboAlmacenTipo(data)
{
    var cuerpo_total = '';
    var cuerpo = '';
    var pie = '';
    var disabled = '';
    var texto = 'Ingresar el Tipo de Almacen';

    if (data[0].id_bandera == null)
    {

        if (data[0].llenar > 0) {
            $.each(data, function (index, item) {
                cuerpo = '<option value="' + item.id + '">' + item.descripcion + '</option>';
                cuerpo_total = cuerpo_total + cuerpo;
            });
        } else
        {
            disabled = 'disabled';
            texto = 'No hay datos registrados';
        }
    } else {
        $.each(data, function (index, item) {
            if (item.id == item.id_bandera)
            {
                cuerpo = '<option value="' + item.id + '" selected>' + item.descripcion + '</option>';
            } else
            {
                cuerpo = '<option value="' + item.id + '" >' + item.descripcion + '</option>';
            }
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var cabeza = '<select id="cbo_tipo" onChange="onchangeTipoAlmacen();" class="form-control" name="cbo_tipo" ' + disabled + '>' +
            '<option value="" style="display:none;">' + texto + '</option>';

    pie = '</select>';
    var html = cabeza + cuerpo_total + pie;
    $("#combo_tipo").append(html);
}
function getComboEmpresa(id)
{
    ax.setAccion("getComboEmpresa");
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