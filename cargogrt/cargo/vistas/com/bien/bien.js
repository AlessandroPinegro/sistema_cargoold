var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;
var acciones = {
    getTipoBien: false,
    getEmpresa: false,
    getTipoUnidad: false
};

$(document).ready(function () {
    altura();
    cargarSelect2();
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
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
//    document.getElementById(data[0].id_estado).className = data[0].icono;
//    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function onchangeEmpresa()
{
    $('#msj_empresa').hide();
}
function onchangeTipoBien()
{
    $('#msj_tipo').hide();
}


function onchangeUnidadTipo()
{
    $('#msj_UnidadTipo').hide();
}

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});
$('#txt_precio_venta').keypress(function () {
    $('#msj_precio_venta').hide();
});

function limpiar_formulario_bien()
{
    document.getElementById("frm_bien").reset();
}

function validar_bien_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var cant_minima = document.getElementById('txt_cant_minima').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo = document.getElementById('cboBienTipo').value;
    var empresa = document.getElementById("cboEmpresa").value;
    var estado = document.getElementById('cboEstado').value;
    var unidad_tipo = document.getElementById('cboUnidadTipo').value;

    var agregado_precio_venta = document.getElementById('txt_precio_venta').value;

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
        $("#msj_tipo").text("Ingresar un tipo de bien").show();
        bandera = false;
    }

    if (isEmpty(agregado_precio_venta) || isNaN(agregado_precio_venta))
    {
        $("msj_precio_venta").removeProp(".hidden");
        $("#msj_precio_venta").text("Ingresar un agregado al precio de venta.").show();
        bandera = false;
    }

    if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        bandera = false;
    }
    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
    {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }

    if (unidad_tipo == "" || espacio.test(unidad_tipo) || unidad_tipo.lenght == 0 || unidad_tipo == null)
    {
        $("msj_UnidadTipo").removeProp(".hidden");
        $("#msj_UnidadTipo").text("Seleccionar un tipo de unidad").show();
        bandera = false;
    }
    return bandera;
}

function listarBien() {
    ax.setSuccess("successBien");
    listarBIEN();
}

function listarBIEN()
{
    ax.setAccion("getDataGridBien");
    ax.consumir();
}
function successBien(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridBien':
                onResponseAjaxpGetDataGridBien(response.data);
                break;
            case 'insertBien':
                loaderClose();
                exitoInsert(response.data);
                break;
            case 'getBien':
                llenarFormularioEditar(response.data);
                break;
            case 'updateBien':
                loaderClose();
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                listarBIEN();
                break;
            case 'deleteBien':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "El tipo de bien " + response.data['0'].nombre + ".", "success");
                    listarBIEN();
                } else {
                    swal("Cancelado", "El tipo de bien " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'generarCodigoBarra':
                break;

            case 'importBien':
                loaderClose();
                $('#resultado').append(response.data);
                listarBIEN();
                break;

            case 'ExportarBienExcel':
                loaderClose();
                location.href = "http://" + location.host + "/almacen/util/formatos/lista_de_bienes.xlsx";
                break;
            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);
                getAllUnidadMedidaTipoCombo();
                verificarCargaDeComplemento();
                break;
            case 'getAllUnidadMedidaTipoCombo':
                onResponsegetAllUnidadMedidaTipoCombo(response.data);
                getAllBienTipo();
                verificarCargaDeComplemento();
                break;
            case 'getAllBienTipo':
                onResponseGetAllBienTipo(response.data);

                if (!isEmpty(VALOR_ID_USUARIO))
                {
                    getBien(VALOR_ID_USUARIO);
                }
//               loaderClose();
                verificarCargaDeComplemento();
                break;
            case 'getAllEmpresaImport':
                onResponseGetAllEmpresas(response.data);
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertBien':
                habilitarBoton();
                loaderClose();
                break;
        }
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
        cargarPantallaListar();
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
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridBien(data) {


    if (!isEmptyData(data))
    {
        $.each(data, function (index, item)
        {
            data[index]["opciones"] = '<a onclick="editarBien(' + item['id'] + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>\n\
                                   <a onclick="confirmarDeleteBien(' + item['id'] + ',\'' + item['b_descripcion'] + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>';

            if (data[index]["estado"] == 1)
            {
                data[index]["estado"] = '<a onclick ="cambiarEstado(' + item['id'] + ')" ><b><i class="ion-checkmark-circled" style="color:#5cb85c"></i><b></a>';
            }
            else
            {
                data[index]["estado"] = '<a onclick ="cambiarEstado(' + item['id'] + ')"><b><i class="ion-flash-off" style="color:#cb2a2a"></i><b></a>';
            }
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "order": [[0, "asc"]],
            "data": data,
            "columns": [
                {"data": "codigo"},
                {"data": "b_descripcion"},
//                {"data": "comentario"},
//                {"data": "cantidad_minima"},
                {"data": "unidad_medida_tipo_descripcion"},
                {"data": "precio_compra", "sClass": "alignRight"},
                {"data": "precio_venta", "sClass": "alignRight"},
                {"data": "estado"},
                {"data": "opciones", "sClass": "alignCenter"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    loaderClose();
}

function guardarBien(tipo_accion)
{

    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var cant_minima = document.getElementById('txt_cant_minima').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_bien = document.getElementById('cboBienTipo').value;
    var estado = document.getElementById('cboEstado').value;
    var comentario = document.getElementById('txt_comentario').value;

    var agregado_precio_venta = document.getElementById('txt_precio_venta').value;
    var agregado_precio_venta_tipo = document.getElementById('cboPrecioVentaTipo').value;

    var empresa = $('#cboEmpresa').val();
    var unidad_tipo = $('#cboUnidadTipo').val();

    var file = document.getElementById('secretImg').value;
    if (file == '')
    {
        file = null;
    }

    if (tipo_accion == 1)
    {
        updateBien(id, descripcion, codigo, cant_minima, tipo_bien, estado, comentario, empresa, file, unidad_tipo, agregado_precio_venta, agregado_precio_venta_tipo);
    } else {
        insertBien(descripcion, codigo, tipo_bien, cant_minima, estado, usu_creacion, comentario, empresa, file, unidad_tipo, agregado_precio_venta, agregado_precio_venta_tipo);
    }
}

function insertBien(descripcion, codigo, tipo_bien, cant_minima, estado, usu_creacion, comentario, empresa, file, unidad_tipo, agregado_precio_venta, agregado_precio_venta_tipo)
{
    console.log(tipo_bien);
    if (validar_bien_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertBien");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo_bien);
        ax.addParamTmp("cant_minima", cant_minima);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("file", file);
        ax.addParamTmp("unidad_tipo", unidad_tipo);
        ax.addParamTmp("agregado_precio_venta", agregado_precio_venta);
        ax.addParamTmp("agregado_precio_venta_tipo", agregado_precio_venta_tipo);
        ax.consumir();
    }
    else
    {
        loaderClose();
    }
}
function getBien(id_bien)
{
    ax.setAccion("getBien");
    ax.addParamTmp("id_bien", id_bien);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    var dir = "http://" + location.host + "/almacen/vistas/com/bien/imagen/" + data[0].imagen;
    $('#myImg').empty();
    document.getElementById('myImg').src = dir;
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_cant_minima').value = data[0].cantidad_minima;
    document.getElementById('txt_comentario').value = data[0].comentario;

    document.getElementById('txt_precio_venta').value = data[0].agregado_precio_venta;

    asignarValorSelect2('cboPrecioVentaTipo', data[0].agregado_precio_venta_tipo);

    asignarValorSelect2('cboEstado', data[0].estado);

    asignarValorSelect2('cboBienTipo', data[0].bien_tipo_id);


    if (!isEmpty(data[0]['empresas_id']))
    {
        asignarValorSelect2("cboEmpresa", data[0]['empresas_id'].split(";"));
    }
    if (!isEmpty(data[0]['unidad_medida_tipo_id']))
    {
        asignarValorSelect2("cboUnidadTipo", data[0]['unidad_medida_tipo_id'].split(";"));
    }
    loaderClose();
}
function updateBien(id, descripcion, codigo, cant_minima, tipo, estado, comentario, empresa, file, unidad_tipo, agregado_precio_venta, agregado_precio_venta_tipo)
{
    if (validar_bien_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("updateBien");
        ax.addParamTmp("id_bien", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("cant_minima", cant_minima);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("file", file);
        ax.addParamTmp("unidad_tipo", unidad_tipo);
        ax.addParamTmp("agregado_precio_venta", agregado_precio_venta);
        ax.addParamTmp("agregado_precio_venta_tipo", agregado_precio_venta_tipo);
        ax.consumir();
    }
    else
    {
        loaderClose();
    }
}
function confirmarDeleteBien(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminaras el bien " + nom + "!",
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
            deleteBien(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteBien(id_bien_tipo, nom)
{
    ax.setAccion("deleteBien");
    ax.addParamTmp("id_bien", id_bien_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}

function getComboBienTipo(id)
{
    ax.setAccion("getComboTipoBien");
    ax.addParamTmp("id_tipo", id);
    ax.consumir();
}

function getComboEmpresa(id)
{
    ax.setAccion("getComboEmpresa");
    ax.addParamTmp("id_tipo", id);
    ax.consumir();
}

function generarCodigoBarra()
{
    var codigo = document.getElementById("txt_codigo").value;
    $("#bcTarget").barcode("11111111", "ean8", {barWidth: 5, barHeight: 30});
}

function importBien() {

    getAllEmpresaImport();
    $('#resultado').empty();
    $('#btnImportar').show();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboEmpresa").attr("disabled", false);
    asignarValorSelect2('cboEmpresa', "");
    $('#modalBien').modal('show');
}

function importar()
{
    var empresa = $('#cboEmpresa').val();

    if (isEmpty(empresa))
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        return;
    }

    $('#resultado').empty();
    $('#btnImportar').hide();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboEmpresa").attr("disabled", true);

    var file = document.getElementById('secret').value;
    loaderShow(".modal-content");
    ax.setAccion("importBien");
    ax.addParam("file", file);
    ax.addParam("empresa_id", empresa);
    ax.consumir();
}

function ExportarBienExcel()
{
    loaderShow(null);
    ax.setAccion("ExportarBienExcel");
    ax.consumir();
}

function getAllEmpresa()
{
    ax.setAccion("getAllEmpresa");
    ax.consumir();
}

function getAllEmpresaImport()
{
    ax.setAccion("getAllEmpresaImport");
    ax.consumir();
}

function getAllUnidadMedidaTipoCombo()
{
    ax.setAccion("getAllUnidadMedidaTipoCombo");
    ax.consumir();
}

function getAllBienTipo()
{
    ax.setAccion("getAllBienTipo");
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

function cargarComponentesFormBien()
{

    getAllUnidadMedidaTipoCombo();
    getAllEmpresa();


}

function onResponseGetAllEmpresas(data) {

    if (!isEmpty(data))
    {
        $('#cboEmpresa').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
        });
    }
    acciones.getEmpresa = true;
}

function onResponsegetAllUnidadMedidaTipoCombo(data) {

    if (!isEmpty(data))
    {
        $('#cboUnidadTipo').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboUnidadTipo').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    }
    acciones.getTipoUnidad = true;
}

function onResponseGetAllBienTipo(data) {

    if (!isEmpty(data))
    {
        $('#cboBienTipo').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboBienTipo').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    }
    acciones.getTipoBien = true;
}
function getAllEmpresa()
{
    ax.setAccion("getAllEmpresa");
    ax.consumir();
}

function getAllBienTipo()
{
    ax.setAccion("getAllBienTipo");
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

function cargarComponentesFormBien()
{
    getAllEmpresa();
}

function nuevoBien()
{
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/bien/bien_form.php', "Nuevo " + obtenerTitulo());
    cargarComponentesFormBien();
}

function editarBien(id) {
    VALOR_ID_USUARIO = id;
//    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/bien/bien_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());

    cargarComponentesFormBien();
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
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/bien/bien_listar.php", tituloGlobal);
}

function verificarCargaDeComplemento()
{
    if (isEmpty(VALOR_ID_USUARIO))
    {
        if (acciones.getEmpresa && acciones.getTipoBien && acciones.getTipoUnidad)
        {
            loaderClose();
        }
    }
}