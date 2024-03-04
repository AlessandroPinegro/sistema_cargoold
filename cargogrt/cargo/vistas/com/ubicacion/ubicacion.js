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
    getComboUbicacionTipo(null, empresa, comboT);
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
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

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

function cargarDivGetUbicacion(id) {
    $("#window").empty();
    cargarDiv("#window", "vistas/com/ubicacion/ubicacion_form.php?id=" + id + "&" + "tipo=" + 1);
}
function onchangeTipoUbicacion()
{
    $('#msj_tipo').hide();
}

function limpiar_formulario_ubicacion()
{
    document.getElementById("frm_ubicacion").reset();
}

function validar_ubicacion_form() {
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
        $("#msj_tipo").text("Ingresar un tipo de ubicación").show();
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

function cargarDivGetUbicacion(id) {
    $("#window").empty();
    cargarDiv("#window", "vistas/com/ubicacion/ubicacion_form.php?id=" + id + "&" + "tipo=" + 1);
}
function listarUbicacion() {
    breakFunction();
    acciones.iniciaAjaxTest(COMPONENTES.UBICACION, "successUbicacion");
    ax.setAccion("getDataGridUbicacion");
    ax.consumir();
}
function successUbicacion(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridUbicacion':
                onResponseAjaxpGetDataGridUbicacion(response.data);
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

            case 'getComboTipoUbicacion':
                llenarComboUbicacionTipo(response.data);
                $('#cbo_tipo').combobox();
                break;
            case 'insertUbicacion':
                exitoInsert(response.data);
                break;

            case 'getUbicacion':
                llenarFormularioEditar(response.data);
                break;
            case 'updateUbicacion':
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'deleteUbicacion':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                } else {
                    swal("Cancelado", "Upss!!. " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
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
        cargarDiv("#window", "vistas/com/ubicacion/ubicacion_listar.php");
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
        cargarDiv("#window", "vistas/com/ubicacion/ubicacion_listar.php");
    }
}
function onResponseAjaxpGetDataGridUbicacion(data) {
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'>Tipo</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    $.each(data, function (index, item) {
        var factor = item.ubicacion;
        var complemento = " de " + item.ubicacion;
        var comentario = item.comentario;
        if (item.comentario == null)
        {
            comentario = '';
        }

        if (item.ubicacion == null)
        {
            factor = '';
            complemento = '';
        }
        cuerpo = "<tr>" +
                "<td style='text-align:center;'>" + item.codigo + "</td>" +
                "<td style='text-align:center;'>" + item.u_descripcion + "</td>" +
                "<td style='text-align:center;'>" + comentario + "</td>" +
                "<td style='text-align:center;'>" + item.tu_descripcion + "</td>" +
                "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='cargarDivGetUbicacion(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteUbicacion(" + item.id + ", \"" + item.u_descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}
function guardarUbicacion(tipo_accion)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var comentario = document.getElementById('txt_comentario').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_ubicacion = document.getElementById('cbo_tipo').value;
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
    
    if (tipo_accion == 1)
    {
        updateUbicacion(id, descripcion, codigo, comentario, tipo_ubicacion, estado,empresa,comboT);
    } else {
        insertUbicacion(descripcion, codigo, tipo_ubicacion, comentario, estado, usu_creacion,empresa,comboT);
    }
}

function insertUbicacion(descripcion, codigo, tipo_ubicacion, comentario, estado, usu_creacion,empresa,comboT)
{
    if (validar_ubicacion_form()) {
        deshabilitarBoton();
        ax.setAccion("insertUbicacion");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo_ubicacion);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("comboT", comboT);
        ax.consumir();
    }
}
function getUbicacion(id_ubi)
{
    ax.setAccion("getUbicacion");
    ax.addParamTmp("id_ubi", id_ubi);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    document.getElementById('cbo_estado').value = data[0].estado;
    getComboUbicacionTipo(data[0].tipo_ubicacion);
}
function updateUbicacion(id, descripcion, codigo, comentario, tipo, estado, factor)
{
    if (validar_ubicacion_form()) {
        deshabilitarBoton();
        ax.setAccion("updateUbicacion");
        ax.addParamTmp("id_ubi", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function confirmarDeleteUbicacion(id, nom) {
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
            deleteUbicacion(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
    //var res=confirm('Est\u00e1 seguro que desea eliminar el cupon especial '+nom+'?');
}

function deleteUbicacion(id_ubi_tipo, nom)
{
    ax.setAccion("deleteUbicacion");
    ax.addParamTmp("id_ubi", id_ubi_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
    cargarDiv("#window", "vistas/com/ubicacion/ubicacion_listar.php");
}

function getComboUbicacionTipo(id, empresa, combo)
{
    ax.setAccion("getComboTipoUbicacion");
    ax.addParamTmp("id_tipo", id);
    ax.addParamTmp("empresa", empresa);
    ax.addParamTmp("combo", combo);
    ax.consumir();
}

function llenarComboUbicacionTipo(data)
{
    $("#combo_tipo").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var pie = '';
    var disabled = '';
    var texto = 'Ingresar el Tipo de Ubicacion';
    if (data[0].id_bandera == null)
    {
        if (data[0].llenar > 0) {
            $.each(data, function (index, item) {
                cuerpo = '<option value="' + item.tipo_id + '">' + item.descripcion + '</option>';
                cuerpo_total = cuerpo_total + cuerpo;
            });
        } else {
            disabled = 'disabled';
            texto = 'No hay datos registrados';
        }
    } else {
        $.each(data, function (index, item) {
            if (item.tipo_id == item.id_bandera)
            {
                cuerpo = '<option value="' + item.tipo_id + '" selected>' + item.descripcion + '</option>';
            } else
            {
                cuerpo = '<option value="' + item.tipo_id + '" >' + item.descripcion + '</option>';
            }
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var cabeza = '<select id="cbo_tipo" onChange="onchangeTipoUbicacion();" class="form-control" name="cbo_tipo"' + disabled + '>' +
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