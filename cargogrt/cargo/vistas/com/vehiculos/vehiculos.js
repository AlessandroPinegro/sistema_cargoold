//declaracion del objeto para guardar las llamadas ajax

//var accionTipoGlobal = 1;
var acciones = {
    getAllEmpresa: false,
    getAllOrganizadorTipo: false,
    obtenerOrganizadorActivo: false,
    getVehiculo: false,
};

altura();

function successVehiculos(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridVehiculos':
                //                loaderClose();
                onResponseAjaxpGetDataGridVehiculo(response.data);
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
            case 'insertVehiculo':
                exitoInsert(response.data);
                break;
            case 'obtenerConfiguracionInicialForm':
                onResponseObtenerConfiguracionInicialForm(response.data);
                loaderClose();
                break;

            case 'insertVehiculos':
                let data = response.data;
                if (data[0]["vout_exito"] == 0) {

                    loaderClose();
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
                } else {

                    loaderClose();
                    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
                    // cargarPantallaListar();
                }
                // exitoInsert(response.data);
                break;
            case 'getEmpresas':
                select2.cargarSeleccione("cboEmpresa", response.data, "id", "persona_documento_nombre", "Seleccione una empresa");
                break;
            case 'getVehiculo':
                dataVehiculo = response.data;
                acciones.getVehiculo = true;
                finalizarCarga();
                break;
            case 'updateVehiculo':
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                break;
            case 'deleteVehiculo':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("¡Eliminado!", "Se ah eliminado el vehículo " + response.data['0'].placa + ".", "success");
                    cargarDatagridVehiculos();
                } else {
                    swal("Cancelado", "Upss!!. " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'generaQR':
                generaPDFQR(response.data);
                break;
            case 'generaPDFQR':
                loaderClose();
                showPDFQR(response.data);
                break;
        }
    }
}

function finalizarCarga() {
    if (acciones.getVehiculo) {
        if (!isEmpty(dataVehiculo)) {
            llenarFormularioEditar(dataVehiculo);
        }
        loaderClose();
    }
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

$('#txt_placa').keypress(function () {
    $('#msj_placa').hide();
});
$('#txt_flota_id').keypress(function () {
    $('#msj_flota_id').hide();
});
$('#txt_marca').keypress(function () {
    $('#msj_marca').hide();
});

$('#txt_capacidad').keypress(function () {
    $('#msj_capacidad').hide();
});
$('#txt_empresa').keypress(function () {
    $('#msj_empresa').hide();
});
$('#txt_tipo').keypress(function () {
    $('#msj_tipo').hide();
});



function limpiar_formulario_organizador() {
    document.getElementById("frm_vehiculos").reset();
}

// validar solo números flota ID
$('#txt_flota_id').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});
// validar solo números capacidad
$('#txt_capacidad').on('input', function () {
    this.value = this.value.replace(/[^0-9,.]/g, '');
});
function validar_vehiculo_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var placa = document.getElementById('txt_placa').value;
    var capacidad = document.getElementById('txt_capacidad').value;
    var flotaId = document.getElementById('txt_flota_id').value;
    // var flotaNumero = document.getElementById('txt_flota_numero').value;
    var marca = document.getElementById('txt_marca').value;

    if (placa == "" || placa == null || espacio.test(placa) || placa.length == 0) {
        $("msj_placa").removeProp(".hidden");
        $("#msj_placa").text("Ingresar una placa").show();
        bandera = false;
    }
    if (capacidad == "" || capacidad == null || espacio.test(capacidad) || capacidad.length == 0) {
        $("#msj_capacidad").removeProp(".hidden");
        $("#msj_capacidad").text("Ingresar capacidad").show();
        bandera = false;
    }
    if (flotaId == "" || flotaId == null || espacio.test(flotaId) || flotaId.length == 0) {
        $("#msj_flota_id").removeProp(".hidden");
        $("#msj_flota_id").text("Ingresar Integración Flota").show();
        bandera = false;
    }
    // if (flotaNumero == "" || flotaNumero == null || espacio.test(flotaNumero) || flotaNumero.length == 0){
    //   $("#msj_numero").removeProp(".hidden");
    //   $("#msj_numero").text("Ingresar Flota Número").show();
    //   bandera = false;
    // }
    if (marca == "" || marca == null || espacio.test(marca) || marca.length == 0) {
        $("#msj_marca").removeProp(".hidden");
        $("#msj_marca").text("Ingresar Marca").show();
        bandera = false;
    }
    return bandera;
}

function listarVehiculos() {
    ax.setSuccess("successVehiculos");
    cargarDatagridVehiculos();
}

function cargarDatagridVehiculos() {
    loaderShow(null);
    ax.setAccion("getDataGridVehiculos");
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

function onResponseAjaxpGetDataGridVehiculo(data) {

    //select2.cargar("cboEmpresa", data.empresa, "id", "persona_documento_nombre");  
    var data = data.vehiculo;
    $("#dataListVehiculos").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
        " <tr>" +
        // "<th style='text-align:center;' width=100px>Codigo</th>" +
        "<th style='text-align:center;'>Placa</th>" +
        "<th style='text-align:center;'>Marca</th>" +
        "<th style='text-align:center;'>Capacidad</th>" +
        "<th style='text-align:center;'>Empresa</th>" +
        "<th style='text-align:center;'>Tipo</th>" +
        // "<th style='text-align:center;' width=100px>Estado</th>" +
        "<th style='text-align:center;' width=100px>Acciones</th>" +
        "</tr>" +
        "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            if (item.persona == null) {
                item.persona = "";
            } else {
                item.persona = item.persona;
            }

            if (item.tipo == 1) {
                item.tipo = "BUS";
            } else if (item.tipo == 2) {
                item.tipo = "FURGON";
            }
            else if (item.tipo == 3) {
                item.tipo = "CARGUERO";
            }

            if (item.flota_carga_maxima <= 0) {
                item.flota_carga_maxima = 0;
            } else {
                item.flota_carga_maxima = item.flota_carga_maxima;
            }
            cuerpo = "<tr>" +
                // "<td style='text-align:left;'>" + item.id + "</td>" +
                "<td style='text-align:left;'>" + item.flota_placa + "</td>" +
                "<td style='text-align:left;'>" + item.flota_marca + "</td>" +
                "<td style='text-align:right;'>" + item.flota_carga_maxima + "</td>" +
                "<td style='text-align:left;'>" + item.persona + "</td>" +
                "<td style='text-align:left;'>" + item.tipo + "</td>" +
                // "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i id='" + item.id + "' class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='editarVehiculo(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteVehiculo(" + item.id + ", \"" + item.flota_placa + "\")' title='Eliminar vehículo'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "<a href='#' onclick='showQR(" + item.id + ", \"" + item.flota_placa + "\",\"" + item.tipo + "\",\"" + item.flota_marca + "\",\"" + (isEmpty(item.persona_id) ?  "":item.persona_id) + "\")' title='Ver código QR' style='margin-left: 5px;'><b><i class='fa fa-qrcode' aria-hidden='true' style='color:#1ca8dd;'></i><b></a>" +
                "</td>" +
                "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataListVehiculos").append(html);
    loaderClose();
}

function guardarVehiculo(tipo_accion) {
    var id = document.getElementById('id').value;
    var flotaId = document.getElementById('txt_flota_id').value;
    var empresa = document.getElementById('cboEmpresa').value;
    var flota = document.getElementById('txt_flota_numero').value;
    var placa = document.getElementById('txt_placa').value;
    var marca = document.getElementById('txt_marca').value;
    var capacidad = document.getElementById('txt_capacidad').value;
    var tipo = document.getElementById('cboTipo').value;
    var tarjetaCirculacion = document.getElementById('txt_tarjeta_circulacion').value;
    var codigoConfiguracion = document.getElementById('txt_codigo_configuracion').value;

    if (tipo_accion == 1) {
        updateVehiculo(id, flotaId, empresa, flota, placa, marca, capacidad, tipo, tarjetaCirculacion, codigoConfiguracion);
    } else {
        insertVehiculo(flotaId, empresa, flota, placa, marca, capacidad, tipo, tarjetaCirculacion, codigoConfiguracion);
    }
}

function insertVehiculo(flotaId, empresa, flota, placa, marca, capacidad, tipo, tarjetaCirculacion, codigoConfiguracion) {
    if (validar_vehiculo_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("insertVehiculo");
        ax.addParamTmp("flotaId", flotaId);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("flota", flota);
        ax.addParamTmp("placa", placa);
        ax.addParamTmp("marca", marca);
        ax.addParamTmp("capacidad", capacidad);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("txt_tarjeta_circulacion", tarjetaCirculacion);
        ax.addParamTmp("txt_codigo_configuracion", codigoConfiguracion);
        ax.consumir();
    }
}
function getVehiculo(id_vehiculo) {
    ax.setAccion("getVehiculo");
    ax.addParamTmp("id_vehiculo", id_vehiculo);
    ax.consumir();
}


function obtenerConfiguracionInicialForm() {
    loaderShow();
    ax.setAccion("obtenerConfiguracionInicialForm");
    ax.addParamTmp("id", $("#id").val());
    ax.consumir();
}

function onResponseObtenerConfiguracionInicialForm(data) {
    select2.cargarSeleccione("cboEmpresa", data.dataEmpresa, "id", "persona_documento_nombre", "Seleccione una empresa");
    select2.asignarValor("cboEmpresa", "");

    if (!isEmpty(data.dataVehiculo)) {
        llenarFormularioEditar(data.dataVehiculo);
    }
}

function llenarFormularioEditar(data) {
    select2.asignarValor('cboEmpresa', data[0].persona_id);
    document.getElementById('txt_flota_id').value = data[0].integracion_flota_id;
    document.getElementById('txt_flota_numero').value = data[0].flota_numero;
    document.getElementById('txt_placa').value = data[0].flota_placa;
    document.getElementById('txt_capacidad').value = data[0].flota_carga_maxima;
    document.getElementById('txt_marca').value = data[0].flota_marca;
    document.getElementById('txt_tarjeta_circulacion').value = data[0].tarjeta_circulacion;
    document.getElementById('txt_codigo_configuracion').value = data[0].codigo_configuracion;
    select2.asignarValor('cboTipo', data[0].tipo);

}
function updateVehiculo(id, flotaId, empresa, flota, placa, marca, capacidad, tipo, tarjetaCirculacion, codigoConfiguracion) {
    if (validar_vehiculo_form()) {
        loaderShow(null);
        deshabilitarBoton();
        ax.setAccion("updateVehiculo");
        ax.addParamTmp("id", id);
        ax.addParamTmp("flotaId", flotaId);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("flota", flota);
        ax.addParamTmp("placa", placa);
        ax.addParamTmp("marca", marca);
        ax.addParamTmp("capacidad", capacidad);
        ax.addParamTmp("tipo", tipo);
        ax.addParamTmp("tarjetaCirculacion", tarjetaCirculacion);
        ax.addParamTmp("codigoConfiguracion", codigoConfiguracion);
        ax.consumir();
    }
}


function confirmarDeleteVehiculo(id, placa) {
    bandera_eliminar = false;
    swal({
        title: "Estás seguro?",
        text: "¡Eliminarás el vehículo: " + placa + "!",
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
            deleteVehiculo(id, placa);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function deleteVehiculo(id, placa) {
    ax.setAccion("deleteVehiculo");
    ax.addParamTmp("id", id);
    ax.addParamTmp("placa", placa);
    ax.consumir();
}
function getEmpresa() {
    ax.setAccion("getEmpresas");
    ax.consumir();
}
function cargarComponentesFormVehiculo() {
    getEmpresa();
    getVehiculo(VALOR_ID_USUARIO);

}

function nuevoVehiculo() {
    VALOR_ID_USUARIO = null;
    cargarDivTitulo('#window', 'vistas/com/vehiculos/vehiculos_form.php', "Nuevo " + obtenerTitulo());
    //    cargarComponentesFormVehiculo();
}

function editarVehiculo(id) {
    cargarDivTitulo("#window", "vistas/com/vehiculos/vehiculos_form.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
    //    cargarComponentesFormVehiculo();
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
    cargarDivTitulo("#window", "vistas/com/vehiculos/vehiculos_listar.php", "Vehículo");
}

// generar QR
function showQR(id, placa, tipo, capacidad, empresa) {
    loaderShow();
    ax.setAccion("generaQR");
    ax.addParamTmp("id", id);
    ax.addParamTmp("placa", placa);
    ax.addParamTmp("tipo", tipo);
    ax.addParamTmp("capacidad", capacidad);
    ax.addParamTmp("empresa", empresa);
    ax.consumir();
}

function generaPDFQR(data) {
    loaderShow();
    ax.setAccion("generaPDFQR");
    ax.addParamTmp("idVehiculo", data.id);
    ax.consumir();
}

function showPDFQR(data) {
    window.open(URL_BASE + 'vistas/com/vehiculos/codigoQR/' + data);

    if (!isEmpty(data)) {
        var urlDelete = '/../../vistas/com/vehiculos/codigoQR/' + data;
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

function addVehiculo() {
    loaderShow();
    ax.setAccion("insertVehiculos");
    ax.consumir();
}
