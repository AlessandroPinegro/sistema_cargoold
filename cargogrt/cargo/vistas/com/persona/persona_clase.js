var c = $('#env i').attr('class');
function iniciarControlador()
{
    ax.setSuccess("successPersona");
    listarPersonaClase();
}

function listarPersonaClase()
{
    ax.setAccion("getDataGridPersonaClase");
    ax.consumir();
}

function successPersona(response) {
    
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridPersonaClase':
                onResponseGetDataGridPersonaClase(response.data);
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
            case 'getAllPersonaTipo':
                onResponsegetAllPersonaTipo(response.data);
                if (!isEmpty(VALOR_ID_USUARIO))
                {
                    llenarFormPersonaClase();
                }
                loaderClose();

                break;
            case 'insertPersonaClase':
                onResponseSavePersonaClase(response.data);
                break;
            case 'updatePersonaClase':
                onResponseSavePersonaClase(response.data);
                break;
            case 'deletePersonaClase':
                var error = response.data[0]['vout_exito'];
                if (error == 1) {
                    swal("Eliminado!", "Clase de persona eliminada correctamente", "success");
                } else {
                    swal("Cancelado", "No se pudo eliminar " + response.data[0]['vout_mensaje'], "error");
                }
                bandera_eliminar = true;
                listarPersonaClase();
                break;
            case 'cambiarEstadoPersonaClase':
                onResponseCambiarEstadoPersonaClase(response.data);
                listarPersonaClase();
                break;
        }
    }
}

function onResponseGetDataGridPersonaClase(data)
{
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Clase</th>" +
            "<th style='text-align:center;'>Tipo</th>" +
            "<th style='text-align:center;'width=100px>Estado</th>" +
            "<th style='text-align:center;'width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {

            if (isEmpty(item.persona_tipo_descripcion))
            {
                item.persona_tipo_descripcion = "";
            }
            cuerpo = '<tr>' +
                    '<td style="text-align:left;">' + item.persona_clase_descripcion + '</td>' +
                    '<td style="text-align:left;">' + item.persona_tipo_descripcion + '</td>' +
                    '<td style="text-align:center;"><a onclick = "cambiarEstadoPersonaClase(' + item.persona_clase_id + ')" ><b><i id="' + item.persona_clase_id + '" class="' + item.icono + '" style="color:' + item.color + '";></i><b></a></td>' +
                    '<td style="text-align:center;">' +
                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>';
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);
    loaderClose();
}

function getAllPersonaTipo()
{
    ax.setAccion("getAllPersonaTipo");
    ax.consumir();
}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}

//function onResponsegetAllPersonaTipo(data)
//{
//    $.each(data, function (index, value) {
//        $('#listaPersonaTipo').append('<li><a onclick="cargarForm(\''+value.ruta+'\')">'+value.descripcion+'</a></li>');
//    });
//}

function cargarForm(ruta)
{
    cargarDiv('#window', ruta);
}

function nuevaPersonaClase()
{
    VALOR_ID_USUARIO = null;

    cargarFormPersonaClase("Nueva");
    cargarComponentesFormPersonaclase();
}

function cargarFormPersonaClase(nombre)
{
    cargarDiv('#window', 'vistas/com/persona/persona_clase_form.php', nombre + " " + obtenerTitulo());
}

function cargarComponentesFormPersonaclase()
{

    getAllPersonaTipo();
}

function onResponsegetAllPersonaTipo(data)
{

    if (!isEmpty(data))
    {
        $('#cboTipo').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboTipo').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    }
}

function onChange(nombre)
{
    $('#msj' + nombre).hide();
}

function guardarPersonaClase()
{
    var descripcion = trim(document.getElementById('txtDescripcion').value);
    var estado = document.getElementById('cboEstado').value;
    var tipo = $('#cboTipo').val();
    savePersonaClase(descripcion, estado, tipo);
}

function savePersonaClase(descripcion, estado, tipo)
{
    if (validarFormPersonaClase(descripcion, tipo)) {
        loaderClose();
        deshabilitarBoton();

        if (isEmpty(VALOR_ID_USUARIO))
        {
            ax.setAccion("insertPersonaClase");
        } else {

            ax.setAccion("updatePersonaClase");
            ax.addParamTmp("id", VALOR_ID_USUARIO);
        }
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}

function validarFormPersonaClase(descripcion, tipo) {
    var bandera = true;
    if (isEmpty(descripcion) || descripcion.length > 500)
    {
        $("msjDescripcion").removeProp(".hidden");
        $("#msjDescripcion").text("Ingresar una descripción").show();
        bandera = false;
    }
    if (isEmpty(tipo))
    {
        $("msjTipo").removeProp(".hidden");
        $("#msjTipo").text("Seleccionar un tipo").show();
        bandera = false;
    }
    return bandera;
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

$('#txtDescripcion').keypress(function () {
    $('#msjDescripcion').hide();
});

function onResponseSavePersonaClase(data)
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
        cargarListarPersonaClase();
    }
}

function onResponseCambiarEstadoPersonaClase(data)
{
    if (data[0]["vout_exito"] == 1)
    {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
    }
    else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validacion', data[0]["vout_mensaje"] + ", no se puede cambiar de estado");
    }
}

function editarPersonaClase(id, descripcion, tipo, estado)
{
    VALOR_ID_USUARIO = id;
    VARLOR_DESCRIPCION = descripcion;
    VALOR_TIPO = tipo;
    VALOR_ESTADO = estado;
    cargarFormPersonaClase("Editar");
    cargarComponentesFormPersonaclase();
}

function llenarFormPersonaClase()
{
    $('#txtDescripcion').val(VARLOR_DESCRIPCION);
    asignarValorSelect2('cboEstado', VALOR_ESTADO);
    if (!isEmpty(VALOR_TIPO))
    {
        asignarValorSelect2("cboTipo", VALOR_TIPO.split(";"));
    }
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function confirmarDeletePersonaClase(id, descripcion) {
    BANDERA_ELIMINAR = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + descripcion + "",
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
            deletePersonaClase(id);
        } else {
            if (BANDERA_ELIMINAR == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deletePersonaClase(id)
{
    ax.setAccion("deletePersonaClase");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cambiarEstadoPersonaClase(id)
{
    ax.setAccion("cambiarEstadoPersonaClase");
    ax.addParamTmp("id", id);
    ax.consumir();
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

function cargarListarPersonaClase()
{
    loaderShow(null);
    cargarDivTitulo('#window', 'vistas/com/persona/persona_clase_listar.php', tituloGlobal);
}