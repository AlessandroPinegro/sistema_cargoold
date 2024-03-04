//declaracion del objeto para guardar las llamadas ajax

//var accionTipoGlobal = 1;
var acciones = {
    getAllEmpresa: false,
    getAllOrganizadorTipo: false,
    obtenerOrganizadorActivo: false,
    getOrganizador: false,
    getAgencia: false,
};

function successOrganizador(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicialForm':
                onResponseobtenerConfiguracionInicialForm(response.data);
                break;

            case 'getDataGridOrganizador':
                onResponseAjaxpGetDataGridOrganizador(response.data);
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
            case 'insertOrganizador':
                exitoInsert(response.data);
                break;

            case 'getOrganizador':
                dataOrganizador = response.data;
                // asignarValorSelect2("cboDestino", aquiid);
                acciones.getOrganizador = true;
                finalizarCarga();
                break;
            case 'updateOrganizador':
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'deleteOrganizador':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].vout_mensaje + ".", "success");
                    cargarDatagridOrganizador();
                } else {
                    swal("Cancelado", "Upss!!. " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;

            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);
                acciones.getAllEmpresa = true;
                finalizarCarga();
                break;
            case 'getAllOrganizadorTipo':
                onResponseGetAllOrganizadorTipo(response.data);
                acciones.getAllOrganizadorTipo = true;
                finalizarCarga();
                break;
            case 'getOrganizadorXTipo':
                loaderShow();
                onResponseGetAOrganizadorXTipo(response.data);
                // acciones.getAllOrganizadorTipo = true;
                // finalizarCarga();
                loaderClose();
                break;
            case 'obtenerOrganizadorActivo':
                onResponseObtenerOrganizadorActivo(response.data);
                acciones.obtenerOrganizadorActivo = true;
                finalizarCarga();
                break;
            case 'generaQR':
                generaPDFQR(response.data);
                break;
            case 'generaPDFQR':
                loaderClose();
                showPDFQR(response.data);
                break;
            case 'getDataGridAgencia':
                onResponseGetAllAgencia(response.data);
                acciones.Agencia = true;
                break;
            case 'TraerAgenciaHijo':
                $("#cboAgencia").select2("val", response.data[0].agencia_id);
//                console.log(response.data);
                break;
        }
    }
}

function finalizarCarga() {
    if (acciones.getAllEmpresa && acciones.getAllOrganizadorTipo && acciones.obtenerOrganizadorActivo && acciones.getOrganizador && acciones.Agencia) {
        if (!isEmpty(dataOrganizador)) {
            llenarFormularioEditar(dataOrganizador);
        }
        loaderClose();
    }
}

function cargarDatagrid() {
    ax.setAccion("getDataGridAgencia");
    ax.consumir();
}

var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;
$("#cboEstado").change(function () {
    $('#msj_estado').hide();
});

function onchangeEmpresa() {
    $('#msj_empresa').hide();
}

function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}

function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}

function cambiarEstado(id_estado) {
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    //    ax.addParamTmp("estado", est);
    ax.consumir();
}

function cambiarIconoEstado(data) {
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

function onchangeTipoOrganizador() {
    $('#msj_tipo').hide();
}

function onchangeOrganizadorPadre() {
    $('#msj_padre').hide();
}

function onchangeAgencia() {
    $('#msj_agencia').hide();
}

function limpiar_formulario_organizador() {
    document.getElementById("frm_organizador").reset();
}

// obtener organizador padre  segun tipo organizador
$("#cboOrganizadorTipo").change(function () {
    $("#cboOrganizadorPadre").select2("val", -1);
    var tipo = $("#cboOrganizadorTipo").val();
    loaderShow();
    obtener_organizador(tipo);
    /*ax.setAccion("getOrganizadorXTipo");
     ax.addParamTmp("tipo", tipo);
     ax.consumir();*/
});

function obtener_organizador(tipo) {
    var tipoPadre = '';
    if (tipo == 12) {
        tipoPadre = 11;
    }
    if (tipo == 11) {
        tipoPadre = 10;
    }

    ax.setAccion("getOrganizadorXTipo");
    ax.addParamTmp("tipo", tipoPadre);
    ax.consumir();
}

function onResponseGetAOrganizadorXTipo(data) {

    if (!isEmpty(data) && data[0].organizador_tipo_id == 10) {
        select2.cargarSeleccione("cboOrganizadorPadre", data, "id", ["codigoAgencia", "descripcion"], "Seleccione organizador padre");
    } else {
        select2.cargarSeleccione("cboOrganizadorPadre", data, "id", ["codigoAgencia", "padreDesc", "descripcion"], "Seleccione organizador padre");
    }

    select2.asignarValor('cboOrganizadorPadre', organizadorPadreEdit);
    loaderClose();
}

// end obtener organizador padre  segun tipo organizador
function validar_organizador_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo = document.getElementById('cboOrganizadorTipo').value;
    var estado = document.getElementById('cboEstado').value;
    var empresa = document.getElementById("cboEmpresa").value;
    var padre = document.getElementById("cboOrganizadorPadre").value;
    var agencia = document.getElementById("cboAgencia").value;
    if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0) {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar una descripción").show();
        bandera = false;
    }
    if (codigo == "" || codigo == null || espacio.test(codigo) || codigo.length == 0) {
        $("msj_codigo").removeProp(".hidden");
        $("#msj_codigo").text("Ingresar un código").show();
        bandera = false;
    }

    if (tipo == "" || tipo == null || espacio.test(tipo) || tipo.length == 0) {
        $("msj_tipo").removeProp(".hidden");
        $("#msj_tipo").text("Ingresar un tipo de organizador").show();
        bandera = false;
    }

    if (tipo == 11 || tipo == 12) {
        if (padre == "" || padre == null || espacio.test(padre) || padre.length == 0) {
            $("msj_padre").removeProp(".hidden");
            $("#msj_padre").text("Ingresar un organizador padre").show();
            bandera = false;
        }
    }

    if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null) {
        $("msj_estado").removeProp(".hidden");
        $("#msj_estado").text("Seleccionar un estado").show();
        bandera = false;
    }
    if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null) {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        bandera = false;
    }
    if (agencia == "" || espacio.test(agencia) || agencia.lenght == 0 || agencia == null || agencia == "0") {
        $("msj_agencia").removeProp(".hidden");
        $("#msj_agencia").text("Seleccionar una agencia").show();
        bandera = false;
    }
    return bandera;
}

function listarOrganizador() {
    ax.setSuccess("successOrganizador");
    cargarDatagridOrganizador();
}

function cargarDatagridOrganizador() {
    ax.setAccion("getDataGridOrganizador");
    ax.consumir();
}

function organizadorEsPadre(id, nombre) {
    ax.setAccion("organizadorEsPadre");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nombre", nombre);
    ax.consumir();
}

function exitoUpdate(data) {
    if (data[0]["vout_exito"] == 0) {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function exitoInsert(data) {
    if (data[0]["vout_exito"] == 0) {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function onResponseAjaxpGetDataGridOrganizador(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=100px>Código</th>" +
            "<th style='text-align:center;'>Descripción</th>" +
            "<th style='text-align:center;'>Comentario</th>" +
            "<th style='text-align:center;'>Agencia</th>" +
            "<th style='text-align:center;'>Tipo Organizador</th>" +
            "<th style='text-align:center;'>Organizador Padre</th>" +
            // "<th style='text-align:center;'>Tipo</th>" +
            "<th style='text-align:center;' width=100px>Estado</th>" +
            "<th style='text-align:center;' width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {

            var comentario = item.comentario;
            if (item.comentario == null) {
                comentario = '';
            }
            var organizador_padre = item.organizador_padre;
            if (item.organizador_padre == null) {
                organizador_padre = '';
            } else if (item.organizador_tipo_id == 11) {
                organizador_padre = item.agencia_codigo + ' | ' + item.organizador_padre;
            } else if (item.organizador_tipo_id == 12) {
                organizador_padre = item.agencia_codigo + ' | ' + item.organizador_padre_padre + ' | ' + item.organizador_padre;
            }

            var agencia = item.agencia;
            if (item.agencia == null) {
                agencia = '';
            }
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.codigo + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + comentario + "</td>" +
                    "<td style='text-align:left;'>" + agencia + "</td>" +
                    "<td style='text-align:left;'>" + item.descripcion_tipo + "</td>" +
                    "<td style='text-align:left;'>" + organizador_padre + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='editarOrganizador(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='javascript:void(0);' onclick='confirmarDeleteOrganizador(" + item.id + ", \"" + item.a_descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>&nbsp;\n";

            if (item.organizador_tipo_id == 12) {
                cuerpo += "<a href='javascript:void(0);' onclick='showQR(" + item.id + ",\"" + item.descripcion + "\",\"" + item.descripcion_tipo + "\",\"" + item.codigo + "\")' title='Ver código QR' style='margin-left: 5px;'><b><i class='fa fa-qrcode' aria-hidden='true' style='color:#1ca8dd;'></i><b></a>";
            } else {
                cuerpo += "<i href='' style='margin-left: 19px;'></i>";
            }

            cuerpo += "</td></tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
    loaderClose();
}
function guardarOrganizador(tipo_accion) {
    var id = document.getElementById('id').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_organizador = document.getElementById('cboOrganizadorTipo').value;
    var estado = document.getElementById('cboEstado').value;
    var comentario = document.getElementById('txt_comentario').value;
    var padre = document.getElementById('cboOrganizadorPadre').value;

    var empresa = document.getElementById('cboEmpresa').value;
    var agencia = document.getElementById('cboAgencia').value;
    if (padre == 0 || padre == '') {
        padre = null;
    }

    if (agencia == 0 || agencia == '') {
        agencia = null;
    }
    if (tipo_accion == 1) {
        updateOrganizador(id, descripcion, codigo, padre, tipo_organizador, estado, comentario, empresa, agencia);
    } else {
        insertOrganizador(descripcion, codigo, padre, tipo_organizador, estado, comentario, empresa, agencia);
    }
}

function insertOrganizador(descripcion, codigo, padre, tipo_organizador, estado, comentario, empresa, agencia) {
    if (validar_organizador_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("insertOrganizador");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("padre", padre);
        ax.addParamTmp("tipo", tipo_organizador);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("agencia", agencia);
        ax.consumir();
    }
}
function getOrganizador(id_organizador) {
    ax.setAccion("getOrganizador");
    ax.addParamTmp("id_organizador", id_organizador);
    ax.consumir();
}

var organizadorPadreEdit = '';
function llenarFormularioEditar(data) {
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;
    asignarValorSelect2('cboEstado', data[0].estado);
    asignarValorSelect2('cboOrganizadorTipo', data[0].organizador_tipo_id);

    if (data[0].organizador_padre_id == "null") {
        organizadorPadreEdit = '';
    } else {
        organizadorPadreEdit = data[0].organizador_padre_id;
    }
    obtener_organizador(data[0].organizador_tipo_id);

    if (data[0].agencia_id == "null") {
        $("#cboAgencia").select2("val", 0);
    } else {
        $("#cboAgencia").prop('disabled', 'disabled');
        $("#cboAgencia").select2("val", data[0].agencia_id);
    }
    if (!isEmpty(data[0]['empresas_id'])) {
        asignarValorSelect2("cboEmpresa", data[0].empresas_id);
    }
}
function updateOrganizador(id, descripcion, codigo, padre, tipo, estado, comentario, empresa, agencia) {
    if (validar_organizador_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("updateOrganizador");
        ax.addParamTmp("id_alm", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("padre", padre);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("agencia", agencia);
        ax.consumir();
    }
}

function confirmarDeleteOrganizador(id, nombre) {

    var dataEliminar = "Eliminarás el organizador " + nombre;

    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: dataEliminar,
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
            deleteOrganizador(id);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function deleteOrganizador(id) {
    ax.setAccion("deleteOrganizador");
    ax.addParamTmp("organizadorId", id);
    ax.consumir();
}

function getAllEmpresa() {
    ax.setAccion("getAllEmpresa");
    ax.consumir();
}

function getAllOrganizadorTipo() {
    ax.setAccion("getAllOrganizadorTipo");
    ax.consumir();
}

function obtenerOrganizadorActivo(id) {
    ax.setAccion("obtenerOrganizadorActivo");
    ax.addParamTmp("id", id);
    ax.consumir();
}



function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}



function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function  obtenerConfiguracionInicialForm() {
    let organizadorId = $("#id").val();
    loaderShow();
    ax.setAccion("obtenerConfiguracionInicialForm");
    ax.addParamTmp("id", organizadorId);
    ax.consumir();
}

function onResponseobtenerConfiguracionInicialForm(data) {
    onResponseGetAllEmpresas(data.dataEmpresa);
    onResponseGetAllOrganizadorTipo(data.dataOrganizadorTipo);
    onResponseGetAllAgencia(data.dataAgencia);
    onResponseObtenerOrganizadorActivo(data.dataOrganizadorActivo);

    dataOrganizador = data.dataOrganizador;
    if (!isEmpty(dataOrganizador)) {
        llenarFormularioEditar(dataOrganizador);
    }
    loaderClose();
}


function cargarComponentesFormOrganizador() {
    getAllEmpresa();
    getAllOrganizadorTipo();
    cargarDatagrid();
    obtenerOrganizadorActivo(VALOR_ID_USUARIO);
    getOrganizador(VALOR_ID_USUARIO);
}

function nuevoOrganizador() {
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/organizador/organizador_form.php', "Nuevo " + obtenerTitulo());
//    cargarComponentesFormOrganizador();
}

function editarOrganizador(id) {
    VALOR_ID_USUARIO = id;
    cargarDivTitulo("#window", "vistas/com/organizador/organizador_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
//    cargarComponentesFormOrganizador();
}

function onResponseGetAllEmpresas(data) {
    if (!isEmpty(data)) {
        // $('#cboEmpresa').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
            asignarValorSelect2("cboEmpresa", value.id);
        });
    }
}
function onResponseGetAllAgencia(data) {

    if (!isEmpty(data)) {
        $('#cboAgencia').append('<option value="">Seleccione una agencia</option>');
        $.each(data, function (index, value) {
            $('#cboAgencia').append('<option value="' + value.agencia_id + '">' + value.descripcion + '</option>');
        });
    }
    $("#cboAgencia").select2("val", '');
}

function onResponseGetAllOrganizadorTipo(data) {
    if (!isEmpty(data)) {
        select2.cargarSeleccione('cboOrganizadorTipo', data, 'id', 'descripcion', "Seleccione tipo de organizador");
        select2.asignarValor('cboOrganizadorTipo', '');
    }
}



function onResponseObtenerOrganizadorActivo(data) {

    $('#cboOrganizadorPadre').append('<option value="">Seleccione organizador padre</option>');
//    if (!isEmpty(data)) {
//        $.each(data, function (index, value) {
//            if (value.idTipo != 12) {
//                $('#cboOrganizadorPadre').append('<option value="' + value.id + '">' + value.descripcion + ' | ' + value.desTipo + '</option>');
//            }
//        });
//    }
    select2.asignarValor('cboOrganizadorPadre', '');
    TraerAgenciaHijo();
}

function TraerAgenciaHijo() {
    $("#cboOrganizadorPadre").change(function () {
        var organizadorID = $(this).children("option:selected").val();
        if (organizadorID == '') {
            $('#cboAgencia').prop('disabled', false);
            $("#cboAgencia").select2("val", '');
        } else {
            ax.setAccion("TraerAgenciaHijo");
            ax.addParamTmp("id", organizadorID);
            ax.consumir();
            $("#cboAgencia").prop('disabled', 'disabled');
        }
    })
}

function obtenerTitulo() {
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    $("#window").empty();

    if (!isEmpty(titulo)) {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar() {
    loaderShow(null);
    cargarDivIndex('#window', URL_BASE + 'vistas/com/organizador/organizador_listar.php', '22', 'Organizador');
}

// generar QR
function showQR(id, descripcion, tipo, codigo) {
//    console.log("click");
    loaderShow();
    ax.setAccion("generaQR");
    ax.addParamTmp("id", id);
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("tipo", tipo);
    ax.consumir();
}

function generaPDFQR(data) {
    loaderShow();
    ax.setAccion("generaPDFQR");
    ax.addParamTmp("id", data.id);
    ax.addParamTmp("ruta", data.ruta);
    ax.consumir();
}


function showPDFQR(data) {
    window.open(URL_BASE + 'vistas/com/organizador/codigoQR/' + data);

    if (!isEmpty(data)) {
        var urlDelete = '/../../vistas/com/organizador/codigoQR/' + data;
        setTimeout(function () {
            eliminarPDF(urlDelete);
        }, 1000);
    }
}

function eliminarPDF(url) {
    ax.setAccion("eliminarPDFM");
    ax.addParamTmp("url", url);
    ax.consumir();
}





















