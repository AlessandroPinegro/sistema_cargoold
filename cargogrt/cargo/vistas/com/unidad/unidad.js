var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_actualizar_unidad_base = false;

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

function onchangeTipoUnidad()
{
    $('#msj_tipo').hide();
}

function limpiar_formulario_unidad()
{
    document.getElementById("frm_unidad").reset();
}

function validar_unidad_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var simbolo = document.getElementById('txt_simbolo').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo = document.getElementById('cboUnidadTipo').value;
    var estado = document.getElementById('cboEstado').value;
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

    if (simbolo == "" || simbolo == null || espacio.test(simbolo) || simbolo.length == 0)
    {
        $("msj_simbolo").removeProp(".hidden");
        $("#msj_simbolo").text("Ingresar un símbolo para esta unidad").show();
        bandera = false;
    }
    if (tipo == "" || tipo == null || espacio.test(tipo) || tipo.length == 0)
    {
        $("msj_tipo").removeProp(".hidden");
        $("#msj_tipo").text("Ingresar un tipo de unidad").show();
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

function listarUnidad() {
    ax.setSuccess("successUnidad");
    cargarDatagrid();
//    ax.setAccion("getDataGridUnidad");
//    ax.consumir();
}
function successUnidad(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridUnidad':
                onResponseAjaxpGetDataGridUnidad(response.data);
//                $('#datatable').dataTable();
                $('#datatable').dataTable({
                   
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

            case 'obtenerConfiguracionInicialUnidad': //            case 'getAllUnidadTipo':
//                onResponseGetAllUnidadTipo(response.data);
//                if (!isEmpty(VALOR_ID_USUARIO)){
//                    getUnidad(VALOR_ID_USUARIO);
//                }
                onResponseObtenerConfiguracionInicialUnidad(response.data);
                loaderClose();
                break;
            case 'insertUnidad':
                loaderClose();
                bandera_actualizar_unidad_base = true;
                exitoInsert(response.data);
                break;

            case 'getUnidad':
                llenarFormularioEditar(response.data);
                break;
            case 'updateUnidad':
                loaderClose();
                bandera_actualizar_unidad_base = true;
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                if (response.data[0]['vout_exito'] == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                    cargarDatagrid();
                }
                break;
            case 'deleteUnidad':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].nombre + ".", "success");
                    cargarDatagrid();
                } else {
                    swal("Cancelado", "" + response.data['0'].nombre + " " + response.data['0'].vout_mensaje + " en el mantenedor equivalencia de unidades", "error");
                }
                bandera_eliminar = true;
                break;
            case 'validarAsignarUnidadBase':
//                if (response.data['0'].tipo_accion != '1')
//                {
                //si es 1 el tipo de unidad ya tiene asignado una unidad base 
                if (response.data['0'].vout_exito == 1)
                {
                    //confirma si el usuario quiere actualizar la unidad base o quiere cancelar la operacion
                    confirmarActualizarUnidadBase(response.data['0'].tipo_accion, response.data['0'].unidad_base_descripcion);
                }
                //si es 2 la unidad base utilizado en equivalencia
                if (response.data['0'].vout_exito == 2)
                {
                    ///acambiar por una notificación
                    guardarUnidad(response.data['0'].tipo_accion);
//                    $.Notification.autoHideNotify('warning', 'top-right', 'Validación', "No se puede actualizar unidad de medida base, Ya existe una unidad de medida base para este tipo de unidad y ya esta siendo utilizado en la funcionalidad de equivalencias");   
                }
                if (response.data['0'].vout_exito == 0)
                {
                    guardarUnidad(response.data['0'].tipo_accion);
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
    } else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function cargarDatagrid()
{
    ax.setAccion("getDataGridUnidad");
    ax.consumir();
}
function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridUnidad(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'width=100px>Codigo</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;'>Simbolo</th>" +
            "<th style='text-align:center;'>Tipo</th>" +
            "<th style='text-align:center;'>Unidad base</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var complemento = " de " + item.unidad;
            if (item.unidad == null || item.unidad == 0 || item.unidad == '')
            {
                complemento = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.um_descripcion + complemento + "</td>" +
                    "<td style='text-align:left;'>" + item.simbolo + "</td>" +
                    "<td style='text-align:left;'>" + item.tm_descripcion + "</td>" +
                    "<td style='text-align:center;'><b><i class='" + item.unidad_medida_base + "' style='color:#0080FF;'></i><b></td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i  id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarUnidad(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteUnidad(" + item.id + ", \"" + item.um_descripcion + complemento + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}

function guardarUnidad(tipo_accion)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var simbolo = document.getElementById('txt_simbolo').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_unidad = document.getElementById('cboUnidadTipo').value;
    var estado = document.getElementById('cboEstado').value;
    var unidad_base = 0;
    if ($('#chk_unidad_base').is(':checked')) {
        unidad_base = 1
    } else {
        unidad_base = 0;
    }

    var codigoSunatId = select2.obtenerValor('cboCodigoSunat');

    if (tipo_accion == 1)
    {
        updateUnidad(id, descripcion, codigo, simbolo, tipo_unidad, estado, unidad_base, codigoSunatId);
    } else {
        insertUnidad(descripcion, codigo, tipo_unidad, simbolo, estado, usu_creacion, unidad_base, codigoSunatId);
    }
}

function save(tipo_accion)
{
    var bandera = 0;

    if ($('#chk_unidad_base').is(':checked')) {
        bandera = 1
    }
    if (bandera == 1)
    {
        validarAsignarUnidadBase(tipo_accion)
    } else
    {
        guardarUnidad(tipo_accion);
    }
}
function validarAsignarUnidadBase(tipo_accion)
{
    var tipo_unidad = document.getElementById('cboUnidadTipo').value;
    var unidadId = document.getElementById('id').value;
    ax.setAccion("validarAsignarUnidadBase");
    ax.addParamTmp("tipo_unidad", tipo_unidad);
    ax.addParamTmp("tipo_accion", tipo_accion);
    ax.addParamTmp("unidadId", unidadId);
    ax.consumir();
}

function insertUnidad(descripcion, codigo, tipo_unidad, simbolo, estado, usu_creacion, unidad_base, codigoSunatId)
{
    if (validar_unidad_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertUnidad");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo_unidad);
        ax.addParamTmp("simbolo", simbolo);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("unidad_base", unidad_base);
        ax.addParamTmp("codigoSunatId", codigoSunatId);
        ax.consumir();
    }
}
function getUnidad(id_unidad)
{
    ax.setAccion("getUnidad");
    ax.addParamTmp("id_unidad", id_unidad);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_simbolo').value = data[0].simbolo;
    if (data[0].unidad_medida_base == null || data[0].unidad_medida_base == '') {
        $("#chk_unidad_base").prop("checked", "");
    } else {
        $("#chk_unidad_base").prop("checked", "checked");
    }
    asignarValorSelect2('cboEstado', data[0].estado);
    asignarValorSelect2('cboUnidadTipo', data[0].unidad_medida_tipo_id);
    asignarValorSelect2('cboCodigoSunat', data[0].sunat_tabla_detalle_id);

    loaderClose();
}
function updateUnidad(id, descripcion, codigo, simbolo, tipo, estado, unidad_base, codigoSunatId)
{
    if (validar_unidad_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateUnidad");
        ax.addParamTmp("id_uni", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("simbolo", simbolo);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("unidad_base", unidad_base);
        ax.addParamTmp("codigoSunatId", codigoSunatId);
        ax.consumir();
    }
}
function confirmarDeleteUnidad(id, nom) {
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
            deleteUnidad(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function confirmarActualizarUnidadBase(tipo_accion, unidad_medida_base_descripcion) {
    bandera_actualizar_unidad_base = false;
    swal({
//        title: "El tipo de unidad ya tiene asignado una unidad de medida base, desea remplazar?",
        title: "",
        text: "El tipo de unidad ya tiene asignado una unidad de medida base, desea remplazar?\n\
              \n\
               Remplazaras " + 'la unidad de medida base ' + unidad_medida_base_descripcion + "",
//        text: "Remplazaras " + 'la unidad base ' + unidad_medida_base_descripcion + "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,remplazar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            guardarUnidad(tipo_accion);
        } else {
            if (bandera_actualizar_unidad_base == false)
            {
                swal("Cancelado", "La operación fue cancelada", "error");
            }
        }
    });
}

function deleteUnidad(id_uni_tipo, nom)
{
    ax.setAccion("deleteUnidad");
    ax.addParamTmp("id_uni", id_uni_tipo);
    ax.addParamTmp("nom", nom);
    ax.consumir();
//    cargarPantallaListar();
}

function getAllUnidadTipo()
{
    ax.setAccion("getAllUnidadTipo");
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

function nuevoFormUnidad()
{
    loaderShow();
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/unidad/unidad_form.php', "Nueva " + obtenerTitulo());
    cargarComponentesFormUnidad();
}

function editarUnidad(id) {
    VALOR_ID_USUARIO = id;
    loaderShow(null);
    cargarDivTitulo("#window", "vistas/com/unidad/unidad_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
    cargarComponentesFormUnidad();
}

function cargarComponentesFormUnidad()
{
    loaderShow();
    ax.setAccion("obtenerConfiguracionInicialUnidad");
    ax.consumir();
//    getAllUnidadTipo();
}

function onResponseGetAllUnidadTipo(data) {
//     loaderClose();
    if (!isEmpty(data)) {
        $('#cboUnidadTipo').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboUnidadTipo').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    }
    loaderClose();
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
    loaderShow();
    cargarDivTitulo("#window", "vistas/com/unidad/unidad_listar.php", tituloGlobal);
}

function obtenerConfiguracionInicialUnidadTipo() {
    ax.setAccion("obtenerConfiguracionInicialUnidadTipo");
    ax.consumir();
}

function onResponseObtenerConfiguracionInicialUnidad(data) {
//    onResponseGetAllUnidadTipo(data);
    select2.cargar("cboUnidadTipo", data.dataUnidadTipo, "id", "descripcion");
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo", "descripcion"]);

    if (!isEmpty(VALOR_ID_USUARIO)) {
        getUnidad(VALOR_ID_USUARIO);
    }
}