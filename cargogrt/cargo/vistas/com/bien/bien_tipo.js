var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;

$("#cboEstado").change(function () {
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
$('#txt_comentario').keypress(function () {
    $('#msj_comentario').hide();
});

function limpiar_formulario_bien()
{
    document.getElementById("frm_bien_tipo").reset();
}

function validar_bien_tipo_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
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
    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
    {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }
    return bandera;
}

function listarBienTipo() {
    ax.setSuccess("successBien");
    cargarDatagridBienTipo();
}

function cargarDatagridBienTipo()
{
    ax.setAccion("getDataGridBienTipo");
    ax.consumir();
}
function successBien(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridBienTipo':
                onResponseAjaxpGetDataGridBienTipo(response.data);
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
            case 'insertBienTipo':
                exitoInsert(response.data);
                break;
            case 'getBienTipo':
                var data=response.data;
                breakFunction();
                onResponseObtenerBienTipoPadres(data.dataBienTipoPadres);
                llenarFormularioEditar(data.dataBienTipo);
                loaderClose();
                break;
            case 'updateBienTipo':
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
            case 'deleteBienTipo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "Se eliminó el grupo de articulos: " + response.data['0'].nombre + ".", "success");
                    cargarDatagridBienTipo();
                } else {
                    swal("Cancelado", response.data['0'].vout_mensaje + " en el mantenedor de articulos", "error");
                }
                bandera_eliminar = true;
                break;
             case 'obtenerBienTipoPadres':
                onResponseObtenerBienTipoPadres(response.data);  
                break;
             case 'obtenerBienTipoPadresDisponibles':
                onResponseObtenerBienTipoPadres(response.data);  
                break;
             case 'obtenerConfiguracionesInicialesBienTipo':
                onResponseObtenerConfiguracionesInicialesBienTipo(response.data);  
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
        $.Notification.notify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
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

function onResponseAjaxpGetDataGridBienTipo(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Código</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'>Tipo Padre</th>" +
            "<th style='text-align:center;'>Estado</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
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

            var bienTipoPadre = item.bien_tipo_padre;
             if (item.bien_tipo_padre == null)
            {
                bienTipoPadre = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>&nbsp;" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + comentario + "</td>" +
                    "<td style='text-align:left;'>" + bienTipoPadre + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarBienTipo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarDeleteBienTipo(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
    loaderClose();
}
function guardarBienTipo(tipo)
{
    var id = document.getElementById('id').value;
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var comentario = document.getElementById('txt_comentario').value;
    var estado = document.getElementById('cboEstado').value;
    var bienTipoPadreId = document.getElementById('cboBienTipoPadre').value;
    
    var codigoSunatId=select2.obtenerValor('cboCodigoSunat');	
    var codigoSunatId2=select2.obtenerValor('cboCodigoSunat2');	    
    
    if (tipo == 1)
    {
        updateBienTipo(id, descripcion, codigo, comentario, estado,bienTipoPadreId,codigoSunatId,codigoSunatId2);
    } else {
        insertBienTipo(descripcion, codigo, comentario, estado, usu_creacion,bienTipoPadreId,codigoSunatId,codigoSunatId2);
    }
}

function insertBienTipo(descripcion, codigo, comentario, estado, usu_creacion,bienTipoPadreId,codigoSunatId,codigoSunatId2)
{
    if (validar_bien_tipo_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("insertBienTipo");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("bienTipoPadreId", bienTipoPadreId);
        ax.addParamTmp("codigoSunatId", codigoSunatId);
        ax.addParamTmp("codigoSunatId2", codigoSunatId2);
        ax.consumir();
    }
}
function getBienTipo(id_bien_tipo)
{
    ax.setAccion("getBienTipo");
    ax.addParamTmp("id_bien_tipo", id_bien_tipo);
    ax.consumir();
}

function llenarFormularioEditar(data)
{
    if (!isEmpty(data))
    {
        document.getElementById('txt_descripcion').value = data[0].descripcion;
        document.getElementById('txt_codigo').value = data[0].codigo;
        document.getElementById('txt_comentario').value = data[0].comentario;
        if(!isEmpty(data[0].bien_tipo_padre_id)){
            asignarValorSelect2('cboBienTipoPadre', data[0].bien_tipo_padre_id);
        }  
        asignarValorSelect2('cboEstado', data[0].estado);
        asignarValorSelect2('cboCodigoSunat', data[0].sunat_tabla_detalle_id);
        asignarValorSelect2('cboCodigoSunat2', data[0].sunat_tabla_detalle_id2);
    }
}
function updateBienTipo(id, descripcion, codigo, comentario, estado,bienTipoPadreId,codigoSunatId,codigoSunatId2)
{
    if (validar_bien_tipo_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("updateBienTipo");
        ax.addParamTmp("id_bien_tipo", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("bienTipoPadreId", bienTipoPadreId);
        ax.addParamTmp("codigoSunatId", codigoSunatId);
        ax.addParamTmp("codigoSunatId2", codigoSunatId2);
        ax.consumir();
    }
}
function confirmarDeleteBienTipo(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "¡Eliminarás el grupo de articulos: " + nom + "!",
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
            deleteBienTipo(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteBienTipo(id_uni_tipo, nom)
{
    ax.setAccion("deleteBienTipo");
    ax.addParamTmp("id_bien_tipo", id_uni_tipo);
    ax.addParamTmp("nom", nom);
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

function nuevoBienTipo()
{    
    cargarDivTitulo('#window', 'vistas/com/bien/bien_tipo_form.php', "Nuevo " + obtenerTitulo());
}

function editarBienTipo(id) {
    cargarDivTitulo("#window", "vistas/com/bien/bien_tipo_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
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
    cargarDivTitulo("#window", "vistas/com/bien/bien_tipo_listar.php", tituloGlobal);
}

function obtenerConfiguracionesInicialesBienTipo(bienTipoId){   
    loaderShow();
    ax.setAccion("obtenerConfiguracionesInicialesBienTipo");
    ax.addParamTmp("bienTipoId", bienTipoId);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesBienTipo(data) {
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo", "descripcion"]);
    select2.cargar("cboCodigoSunat2", data.dataSunatDetalle2, "id", ["codigo", "descripcion"]);
    select2.cargar("cboBienTipoPadre", data.dataBienTipoPadres, "id", ["codigo", "descripcion"]);
    
    llenarFormularioEditar(data.dataBienTipo);
}