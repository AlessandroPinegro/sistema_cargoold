var c = $('#env i').attr('class');
var url_cancelar = '';
var bandera_eliminar = false;
var bandera_getCombo = false;
var bandera_getComboEmpresa = false;
var dataPorId;
//var acciones = {
//    getComboPerfil: false,
//    getComboAgencias: false,
//    getComboEmpresa: false,
//    getCaja: false,
//    getComboUsuario: false
//};

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

function cargarCombo(tipo)
{
  
    //retorna los datos de la tabla colaborador para llenar el combo
    ax.setAccion("getComboAgencias");
    ax.consumir();
    //retorna los datos de la tabla empresa para llenar el combo
    
    getCaja(VALOR_ID_USUARIO);
}

function nuevo() {

    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/caja/caja_form.php', "Nuevo " + obtenerTitulo());
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

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

$("#estado").change(function () {
    $('#msj_estado').hide();
});
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

$('#txt_usuario').keypress(function () {
    $('#msj_codigo').hide();
});

function limpiar_formulario_usuario()
{
    document.getElementById("frm_usuario").reset();
}

function cargarDivGetCaja(id) {

    //sele envia un numero 1 para indicar que el combo se cargara para editar
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/caja/caja_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());

}
function getCaja(id)
{
    loaderShow();
    ax.setAccion("getCaja");
    ax.addParamTmp("id_caja", id);
    ax.consumir();
}

function cargarDatagrid()
{
    ax.setAccion("getDataGridCaja");
    ax.consumir();

}
function listarCajas() {
    ax.setSuccess("successUsuario")
    cargarDatagrid();
}
function successUsuario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridCaja':
                onResponseAjaxpGetDataGridCajas(response.data);
                $('#datatable').dataTable({                            
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
                case 'getComboAgencias':
                acciones.getComboAgencias = true;
                llenarComboAgencia(response.data);
                cajaCargarData();
                break;
            case 'obtenerUsuarios':
                acciones.getComboUsuario = true;
                llenarComboUsuario(response.data);
                cajaCargarData();
                break;
            case 'getComboPerfil':
                acciones.getComboPerfil = true;
                llenarComboPerfil(response.data);
                cajaCargarData();
                break;
            case 'insertUsuario':
                exitoInsert(response.data);
                break;
            case 'getCaja':
                acciones.getCaja = true;
                dataPorId = response.data;
                cajaCargarData();
                break;
            case 'updateUsuario':
                exitoUpdate(response.data);
                break;
            case 'deleteCaja':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "Se eliminó la caja: " + response.data['0'].nombre + ".", "success");
                    cargarDatagrid();

                } else {
                    swal("Cancelado", " " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'obtenerContrasenaActual':
                if (validarCambiarContrasena(response.data[0]['clave']))
                {
                    cambiarContrasena();
                }
                break;
            case 'cambiarContrasena':
                exitoCambioContrasena(response.data)
                break;
            case 'obtenerPantallaPrincipalUsuario':
                url_cancelar = response.data[0]['url'];
                break;
            case 'getComboEmpresa':
                acciones.getComboEmpresa = true;
                llenarComboEmpresa(response.data);
                cajaCargarData();
                break;
        }
    }else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'cambiarContrasena':
                habilitarBoton();
                break;
             
        }
    }
}
function cajaCargarData()
{
    if (acciones.getComboAgencias  && acciones.getCaja )
    {
        if (!isEmpty(VALOR_ID_USUARIO))
        {
//                llenarFormularioEditar(dataPorId);
           document.getElementById('txt_usuario').value = dataPorId['0']['codigo'];
                 document.getElementById('txt_descripcion').value = dataPorId['0']['descripcion'];
      
            document.getElementById('estado').value = dataPorId['0']['estado'];
           
            asignarValorSelect2("cbo_agencia", dataPorId['0']['agencia_id']);
     
            asignarValorSelect2("estado", dataPorId['0']['estado']);
            loaderClose();
        } else
        {
            loaderClose();
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
        cargarPantallaListar();
    }
}
function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        //$.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
        mostrarAdvertencia(data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        //$.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        mostrarOk(data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridCajas(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Cod. Agencia</th>" +
            "<th style='text-align:center;'>Descripcion</th>" +
                "<th style='text-align:center;'>Detalle</th>" +
                "<th style='text-align:center;'>Sufijo Comprobante</th>" +
                "<th style='text-align:center;'>Correlativo Inicio</th>" +
         "<th style='text-align:center;'>Punto Venta (IP)</th>" +
           
     
//            "<th style='text-align:center;'>Clave</th>" +
         "<th style='text-align:center;'>Estado</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
            if (!isEmpty(data))
  { 
    $.each(data, function (index, item) {
        if(item.sufijo==null){
            var sufi = "";
        }else{
            var sufi =item.sufijo;
        }
        if(item.correlativo==null){
            var correlativo = "";
        }else{
            var correlativo =item.correlativo;
        }
        cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.agencia_codigo + "</td>" +
                "<td style='text-align:left;'>" + item.codigo + "</td>" +
                "<td style='text-align:left;'>" + item.descripcion + "</td>" + 
                "<td style='text-align:left;'>" + sufi + "</td>" + 
                "<td style='text-align:left;'>" + correlativo + "</td>" + 
                "<td style='text-align:left;'>" + item.ip_caja + "</td>" + 
                "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.caja_id + ")' ><b><i id='" + item.caja_id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='cargarDivGetCaja(" + item.caja_id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteCaja(" + item.caja_id + ", \"" + item.codigo + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
}
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);   
    loaderClose(); 
}

function llenarComboAgencia(data)
{
    $.each(data, function (index, item) {
        $('#cbo_agencia').append('<option value="' + item.agencia_id + '">' + item.codigo + ' | ' + item.descripcion +  '</option>');
    });
}





function confirmarDeleteCaja(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás caja: " + nom + "",
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
            deleteCaja(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteCaja(id_caja, nom)
{
    ax.setAccion("deleteCaja");
    ax.addParamTmp("id_caja", id_caja);
    ax.addParamTmp("nom", nom);
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

function cancelarCambiarContrasena()
{
     $("#window").empty();
}