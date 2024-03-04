var bandera_eliminar = false;
var COMBO_ALTERNATIVA = '';

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}
function cargarCombo(tipo)
{
    loaderShow();
    ax.setAccion("getComboUnidad");
    ax.consumir();
}
$("#cbo_unidad").change(function () {
    $('#msj_unidad').hide();
    var uni_base = document.getElementById('cbo_unidad').value;
    getComboAlternativa(uni_base);
});

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
function deshabilitarBoton()
{
    $("#env").addClass('disabled');
}

function habilitarBoton()
{
    $("#env").removeClass('disabled');
}

$('#txt_factor1').keypress(function () {
    $('#msj_factor1').hide();
});
$('#txt_factor2').keypress(function () {
    $('#msj_factor2').hide();
});
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}

function onchangeAlternativa()
{
    $('#msj_alternativa').hide();
}
function limpiar_formulario_equivalencia()
{
    document.getElementById("frm_equivalencia").reset();
}

function validarEquivalencia(unidad_base, unidad_alternativa)
{
    ax.setAccion("validarEquivalencia");
    ax.addParamTmp("unidad_base", unidad_base);
    ax.addParamTmp("unidad_alternativa", unidad_alternativa);
    ax.consumir();
}
//function validarAgregarEquivalencia()
//{
//    var unidad_alt = document.getElementById('cbo_alternativa').value;
//    var unidad_base = document.getElementById('cbo_unidad').value;
//    validarEquivalencia(unidad_base, unidad_alt);
//}

function validar_equivalencia_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var id = document.getElementById('id').value;
    var fac_alternativa = document.getElementById('txt_factor1').value;
    var uni_alternativa = document.getElementById('cbo_alternativa').value;
    var fac_base = document.getElementById('txt_factor2').value;
    var uni_base = document.getElementById('cbo_unidad').value;
//    var farctorUnidad = document.getElementById('txt_factor2').value;
//    var FactorUnidadAlternativo = document.getElementById('txt_factor2').value;

    if (fac_alternativa == "" || fac_alternativa == null || espacio.test(fac_alternativa) || fac_alternativa.length == 0)
    {
        $("msj_factor1").removeProp(".hidden");
        $("#msj_factor1").text("Ingresar un factor").show();
        bandera = false;
    }
    if (isNaN(fac_alternativa))
    {
        $("msj_factor1").removeProp(".hidden");
        $("#msj_factor1").text("Ingresar un número").show();
        bandera = false;
    }    
    if (fac_alternativa<0)
    {
        $("msj_factor1").removeProp(".hidden");
        $("#msj_factor1").text("Ingresar un número positivo").show();
        bandera = false;
    }

    if (uni_alternativa == "" || uni_alternativa == null || espacio.test(uni_alternativa) || uni_alternativa.length == 0)
    {
        $("msj_alternativa").removeProp(".hidden");
        $("#msj_alternativa").text("Ingresar unidad alternativa").show();
        bandera = false;
    }

    if (fac_base == "" || fac_base == null || espacio.test(fac_base) || fac_base.length == 0)
    {
        $("msj_factor2").removeProp(".hidden");
        $("#msj_factor2").text("Ingresar un factor").show();
        bandera = false;
    }
    if (isNaN(fac_base))
    {
        $("msj_factor2").removeProp(".hidden");
        $("#msj_factor2").text("Ingresar un número").show();
        bandera = false;
    }
    if (fac_base<0)
    {
        $("msj_factor2").removeProp(".hidden");
        $("#msj_factor2").text("Ingresar un número positivo").show();
        bandera = false;
    }
    if (uni_base == "" || uni_base == null || espacio.test(uni_base) || uni_base.length == 0)
    {
        $("msj_unidad").removeProp(".hidden");
        $("#msj_unidad").text("Ingresar una unidad base").show();
        bandera = false;
    }
    return bandera;
}

function listarEquivalencias() {
    ax.setSuccess("successEquivalencia");
    cargarDatagrid();
    
}
function successEquivalencia(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridEquivalencia':
                onResponseAjaxpGetDataGridEquivalencia(response.data);
                $('#datatable').dataTable({
                    "scrollX":true,
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
            case 'getComboAlternativa':
                llenarComboAlternativa(response.data);
                break;
            case 'getComboUnidad':
                llenarComboUnidad(response.data);
                loaderClose();
                break;
            case 'insertEquivalencia':
                loaderClose();
                exitoInsert(response.data);
                break;
            case 'getEquivalencia':
                llenarFormularioEquivalencia(response.data);
                break;
            case 'updateEquivalencia':
                loaderClose();
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
//                cambiarIconoEstado(response.data);
                 cargarDatagrid()
                break;
            case 'deleteEquivalencia':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "La equivalencia de " + response.data['0'].nombre1 + " con " + response.data['0'].nombre2 + ".", "success");
                    cargarDatagrid();
                } else {
                    swal("Cancelado", "Upss!!.El tipo de unidad " + response.data['0'].nombre1 + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'validarEquivalencia':
                if (response.data[0].vout_exito == '0')
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'Ya exite una unidad alternativa para la unidad base!');
                } else
                {
                    guardarEquivalencia();
                }
                break;
        }
    }
}

function cargarDatagrid()
{
    loaderShow();
    ax.setAccion("getDataGridEquivalencia");
    ax.consumir();
    
}

function exitoUpdate(data)
{
    if (data[0]["vout_exito"] == 0)
    {

        habilitarBoton();
        document.getElementById('id_equivalencia').value = '-1';
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]['vout_mensaje']);
    }
    else
    {
        $("#dataList").empty();
        habilitarBoton();
        document.getElementById('accion').value = '0';
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]['vout_mensaje']);
        document.getElementById('txt_factor2').value = 1;
        document.getElementById('txt_factor1').value = 1;
        asignarValorSelect2('cbo_alternativa', "");
        asignarValorSelect2('cbo_unidad', "");
        listarEquivalencias();
    }
}

function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        $("#dataList").empty();
        document.getElementById('txt_factor2').value = 1;
        document.getElementById('txt_factor1').value = 1;
        asignarValorSelect2('cbo_alternativa', "");
        asignarValorSelect2('cbo_unidad', "");
        listarEquivalencias();
    }
}
function onResponseAjaxpGetDataGridEquivalencia(data) {
     $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Factor</th>" +
            "<th style='text-align:center;'>Unidad base</th>" +
            "<th style='text-align:center;'>Factor </th>" +
            "<th style='text-align:center;'>Unidad alternativa</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            cuerpo = "<tr>" +
                    "<td style='text-align:right;'>" + item.factor + "</td>" +
                    "<td style='text-align:left;'>" + item.unidad_descripcion + "</td>" +
                    "<td style='text-align:right;'>" + item.factor_alternativo + "</td>" +
                    "<td style='text-align:left;'>" + item.alternativa_descripcion + "</td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='getEquivalencia(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteEquivalencia(" + item.id + ", \"" + item.unidad_descripcion + "\",\"" + item.alternativa_descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}

function getComboAlternativa(id)
{
    ax.setAccion("getComboAlternativa");
    ax.addParamTmp("id_unidad", id);
    ax.consumir();
}
function llenarComboAlternativa(data)
{

    $('#cbo_alternativa').empty();
    $('#cbo_alternativa').append('<option value=""></option>');
    $.each(data, function (index, item) {
        $('#cbo_alternativa').append('<option value="' + item.id + '">' + item.um_descripcion + '</option>');
    });
    asignarValorSelect2('cbo_alternativa', COMBO_ALTERNATIVA);
    loaderClose();
}

function llenarComboUnidad(data)
{
    if(!isEmpty(data))
    {
    $('#cbo_unidad').append('<option value=""></option>');
    $.each(data, function (index, item) {
        $('#cbo_unidad').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
    });
    asignarValorSelect2('cbo_unidad', "");
    }
}

function guardarEquivalencia(tipo)
{
    var tipo_accion = document.getElementById('accion').value;
    var id_equivalencia = document.getElementById('id_equivalencia').value;
    var id = document.getElementById('id').value;
    var fac_alternativa = document.getElementById('txt_factor1').value;
    var uni_alternativa = document.getElementById('cbo_alternativa').value;
    var fac_base = document.getElementById('txt_factor2').value;
    var uni_base = document.getElementById('cbo_unidad').value;
    if (tipo_accion == '1')
    {
        updateEquivalencia(id_equivalencia, fac_alternativa, uni_alternativa, fac_base, uni_base);
    } else {
        insertEquivalencia(fac_alternativa, uni_alternativa, fac_base, uni_base);
    }
}
function insertEquivalencia(fac_alternativa, uni_alternativa, fac_base, uni_base)
{
    if (validar_equivalencia_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("insertEquivalencia");
        ax.addParamTmp("fac_alternativa", fac_alternativa);
        ax.addParamTmp("uni_alternativa", uni_alternativa);
        ax.addParamTmp("fac_base", fac_base);
        ax.addParamTmp("uni_base", uni_base);
        ax.consumir();
    }
}
function getEquivalencia(id_equivalencia)
{
    loaderShow(null);
    document.getElementById('id_equivalencia').value = id_equivalencia;
    ax.setAccion("getEquivalencia");
    ax.addParamTmp("id_equivalencia", id_equivalencia);
    ax.consumir();
}
function llenarFormularioEquivalencia(data)
{
    $('#msj_alternativa').hide();
    $('#msj_unidad').hide();
    $('#msj_factor1').hide();
    $('#msj_factor2').hide();

    document.getElementById('accion').value = '1';
    COMBO_ALTERNATIVA = data[0].unidad_medida_alternativa_id;
    document.getElementById('txt_factor2').value = data[0].factor;
    document.getElementById('txt_factor1').value = data[0].factor_alternativo;
    getComboAlternativa(data[0].unidad_medida_id);
    asignarValorSelect2('cbo_unidad', data[0].unidad_medida_id);
}

function updateEquivalencia(id, fac_alternativa, uni_alternativa, fac_base, uni_base)
{
    if (validar_equivalencia_form()) {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion("updateEquivalencia");
        ax.addParamTmp("id_equivalencia", id);
        ax.addParamTmp("unidad_base", uni_base);
        ax.addParamTmp("factor_unidad", fac_base);
        ax.addParamTmp("unidad_alternativa", uni_alternativa);
        ax.addParamTmp("factor_alternativa", fac_alternativa);
        ax.consumir();
    }
}
function confirmarDeleteEquivalencia(id, nom1, nom2) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás la equivalencia " + nom1 + " con " + nom2 + "",
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
            deleteEquivalencia(id, nom1, nom2);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteEquivalencia(id_equivalencia, nom1, nom2)
{
    ax.setAccion("deleteEquivalencia");
    ax.addParamTmp("id_equivalencia", id_equivalencia);
    ax.addParamTmp("nom1", nom1);
    ax.addParamTmp("nom2", nom2);
    ax.consumir();
//    cargarDiv("#window", "vistas/com/unidad/equivalencia_listar.php");
}