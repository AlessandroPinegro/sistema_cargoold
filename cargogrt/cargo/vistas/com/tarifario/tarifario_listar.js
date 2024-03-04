var c = $("#env i").attr("class");
var acciones = {
    getTarifario: false
};

var valoresBusquedaPorActividad = [
    {origen: "", destino: "", persona: "", articulo: ""
    }
];

$(document).ready(function () {

    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorCuenta");
    obtenerConfiguracionesInicialesPorActividad();
});

$("#cboOrigen").change(function () {
    $('#msj_origen').hide();
});

$("#cboDestino").change(function () {
    $('#msj_destino').hide();
});

$("#cboMoneda").change(function () {
    $('#msj_moneda').hide();
});

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: "100%"});
}

function cargarDatosBusqueda() {

    var origen = $('#cboOrigen2').val();
    var destino = $('#cboDestino2').val();
    var persona = $('#cboPersona2').val();
    var articulo = $('#cboArticulo2').val();

    valoresBusquedaPorActividad[0].origen = origen;
    valoresBusquedaPorActividad[0].destino = destino;
    valoresBusquedaPorActividad[0].persona = persona;
    valoresBusquedaPorActividad[0].articulo = articulo;
}

function obtenerDatosBusqueda() {
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaPorActividad[0].origen)) {
        cadena += negrita("Origen: ");
        cadena += select2.obtenerText('cboOrigen2');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorActividad[0].destino)) {
        cadena += negrita("Destino: ");
        cadena += select2.obtenerText('cboDestino2');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorActividad[0].persona)) {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerText('cboPersona2');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorActividad[0].articulo)) {
        cadena += negrita("Articulo: ");
        cadena += select2.obtenerText('cboArticulo2');
        cadena += "<br>";
    }

    return cadena;
}


function buscarTarifario(colapsa) {
    listarTarifario();
    colapsarBuscador();
    loaderClose();

}

function onResponseReportePorCuenta(response) {
    if (response["status"] === "ok") {
        switch (response[PARAM_ACCION_NAME]) {
            case "obtenerConfiguracionesInicialesPorActividad":
                onResponseObtenerConfiguracionesIniciales(response.data); 
                break;

            case "insertTarifario":
                loaderClose();
                habilitarBoton();
                buscarTarifario();
                mostrarOk("Tarifario guardada correctamente.");
                cleanForm();
                break;
            case "importTarifario":
                loaderClose();
                $("#resultado").append(response.data);
                // listar;
                buscarTarifario();
                break;
            case "obtenerReportePorCuentaExcel":
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;

            case "deleteTarifario":
                var error = response.data["0"].vout_exito;
                if (error > 0) {
                    swal(
                            "Eliminado!",
                            "Se elimino Tarifario: " + response.data["0"].nombre + ".",
                            "success"
                            );
                    buscarTarifario();
                } else {
                    swal(
                            "Cancelado",
                            " " +
                            response.data["0"].nombre +
                            " " +
                            response.data["0"].vout_mensaje,
                            "error"
                            );
                }
                bandera_eliminar = true;
                break;

            case "getTarifario":
                acciones.getTarifario = true;
                dataPorId = response.data;
                tarifarioCargarData();
                break;

            case "updateTarifario":
                loaderClose();
                habilitarBoton();
                buscarTarifario();
                $("#addTarifario").modal("hide");
                mostrarOk("Tarifario actualizado correctamente.");
                cleanForm();
                break;
            case 'exportarTarifario':
                loaderClose();
                location.href = URL_BASE + "util/formatos/lista_tarifario.xlsx";
                break;
            case 'obtenerSelectPersonas':
                onResponseObtenerPersonaActivoXStringBusqueda(response.data, response.tag);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case "obtenerReportePorCuentaExcel":
                loaderClose();
                break;
            case "insertTarifario":
                loaderClose();
                habilitarBoton();
                break;

            case "updateTarifario":
                loaderClose();
                habilitarBoton();
                break;
            case 'exportarTarifario':
                loaderClose();
                // location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
                
                 case "importTarifario":
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorActividad() {

    var scriptPersona = '';
    ax.setAccion("obtenerConfiguracionesInicialesPorActividad");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.addParamTmp("scriptPersona", scriptPersona);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {

    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione(
                "cboOrigen",
                data.agencia,
                "agencia_id",
                ["codigo", "descripcion"],
                "Seleccione una agencia origen"
                );
        select2.asignarValor("cboOrigen", "");
    }

    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione(
                "cboDestino",
                data.agencia,
                "agencia_id",
                ["codigo", "descripcion"],
                "Seleccione una agencia destino"
                );
        select2.asignarValor("cboDestino", "");
    }

    if (!isEmpty(data.bien)) {
        select2.cargarSeleccione(
                "cboArticulo",
                data.bien,
                "id",
                ["codigo", "b_descripcion"],
                "Seleccione un articulo"
                );
        select2.asignarValor("cboArticulo", "");
    }

    $("#cboPersona").select2({
        width: '100%'
    }).on("change", function (e) {
    });

    setTimeout(function () {
        $($("#cboPersona").data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13)
            {
                obtenerDataCombo('Persona');
            }
        });
    }, 1000);

    $("#cboPersona2").select2({
        width: '100%'
    }).on("change", function (e) {
    });

    setTimeout(function () {
        $($("#cboPersona2").data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13)
            {
                obtenerDataCombo('Persona2');
            }
        });
    }, 1000);

    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione(
                "cboOrigen2",
                data.agencia,
                "agencia_id",
                ["codigo", "descripcion"],
                "Seleccione una agencia origen"
                );
        select2.asignarValor("cboOrigen2", "");
    }

    if (!isEmpty(data.bien)) {
        select2.cargarSeleccione(
                "cboArticulo2",
                data.bien,
                "id",
                ["codigo", "b_descripcion"],
                "Seleccione un articulo"
                );
        select2.asignarValor("cboArticulo2", "");
    }
    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione(
                "cboDestino2",
                data.agencia,
                "agencia_id",
                ["codigo", "descripcion"],
                "Seleccione una agencia destino"
                );
        select2.asignarValor("cboDestino2", "");
    }

    if (!isEmpty(data.moneda)) {
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.asignarValor("cboMoneda", "2");
    }

    listarTarifario();

//    loaderClose();
} 

function obtenerDataBusquedaTarifario(data) {

    loaderClose();

    $("#datatable2").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza =
            '<table id="datatableT" class="table table-striped table-bordered" style="width: 100%"><thead>' +
            " <tr>" +
            "<th style='text-align:center; vertical-align: middle;'>Ag.Origen</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Ag. Destino</th>" +
            "<th style='text-align:center; vertical-align: middle;' >M.</th> " +
            "<th style='text-align:center; vertical-align: middle;' >Persona</th>" +
            "<th style='text-align:center; vertical-align: middle;' >P. Mínimo</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Articulo</th> " +
            "<th style='text-align:center; vertical-align: middle;' >P. Artículo</th> " +
            "<th style='text-align:center; vertical-align: middle;' >P. por Kilogramo</th>" +
            "<th style='text-align:center; vertical-align: middle;'>P. <= 5k</th>" +
            "<th style='text-align:center; vertical-align: middle;' >P. Sobre</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
    $.each(data, function (index, item) {
        if (item.nombre == null) {
            item.nombre = "";
        } else {
            item.nombre = item.nombre;
        }
        if (item.bien_descripcion == null) {
            item.bien_descripcion = "";
        } else {
            item.bien_descripcion = item.bien_descripcion;
        }
        cuerpo =
                "<tr>" +
                "<td style='text-align:left;'>" + item.agencia_origen + "</td>" +
                "<td style='text-align:left;'>" + item.agencia_destino + "</td>" +
                "<td style='text-align:left;'>" + item.moneda + "</td>" +
                "<td style='text-align:left;'>" + item.nombre + "</td>" +
                "<td style='text-align:left;'>" + parseFloat(item.precio_minimo, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + item.bien_descripcion + "</td>" +
                "<td style='text-align:left;'>" + parseFloat(item.precio_articulo, 2).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + parseFloat((item.precio_xk == null ? "0" : item.precio_xk), 2).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + parseFloat((item.precio_5k == null ? "0" : item.precio_5k), 2).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + parseFloat((item.precio_sobre == null ? "0" : item.precio_sobre), 2).toFixed(2) + "</td>" +
                "<td style='text-align:center;'>" + "<a href='#' onclick='getTarifario(" +
                item.tarifario_id +
                ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteTarifario(" +
                item.tarifario_id +
                ', "' +
                item.agencia_origen +
                " - " +
                item.agencia_destino +
                "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = "</table>";
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);

    $("#datatableT").dataTable();
}

$("#txt_sobre").keypress(function () {
    $("#msj_sobre").hide();
});

$("#txt_paquete").keypress(function () {
    $("#msj_paquete").hide();
});

$("#txt_kilogramo").keypress(function () {
    $("#msj_kilogramo").hide();
});

$("#cboOrigen").keypress(function () {
    $("#msj_origen").hide();
});

$("#cboDestino").keypress(function () {
    $("#msj_destino").hide();
});

$("#cboPersona").keypress(function () {
    $("#msj_persona").hide();
});

$("#cboMoneda").keypress(function () {
    $("#msj_moneda").hide();
});

function getTarifario(id) {
    VALOR_ID_USUARIO = id;
    loaderShow();
    $("#modalTitulo").empty();
    $("#modalTitulo").html("Editar Tarifario Agencia");
    $("#addTarifario").modal("show");
    select2.asignarValor("cboPersona", "");
    ax.setAccion("getTarifario");
    ax.addParamTmp("id_tarifario", id);
    ax.consumir();
}

function tarifarioCargarData() {

    if (acciones.getTarifario) {
        if (!isEmpty(VALOR_ID_USUARIO)) {

            document.getElementById("id").value = dataPorId["0"]["tarifario_id"];
            document.getElementById("txt_kilogramo").value = parseFloat((dataPorId["0"]["precio_xk"] == null ? "0" : dataPorId["0"]["precio_xk"]), 2).toFixed(2);
            document.getElementById("txt_sobre").value = parseFloat((dataPorId["0"]["precio_sobre"] == null ? "0" : dataPorId["0"]["precio_sobre"]), 2).toFixed(2);
            document.getElementById("txt_paquete").value = parseFloat((dataPorId["0"]["precio_5k"] == null ? "0" : dataPorId["0"]["precio_5k"]), 2).toFixed(2);
            document.getElementById("txt_precio_minimo").value = parseFloat((dataPorId["0"]["precio_minimo"] == null ? "0" : dataPorId["0"]["precio_minimo"]), 2).toFixed(2);
            document.getElementById("txt_precio_articulo").value = parseFloat((dataPorId["0"]["precio_articulo"] == null ? "0" : dataPorId["0"]["precio_articulo"]), 2).toFixed(2);

            asignarValorSelect2("cboOrigen", dataPorId["0"]["agencia_origen_id"]);
            asignarValorSelect2("cboDestino", dataPorId["0"]["agencia_destino_id"]);
            asignarValorSelect2("cboMoneda", dataPorId["0"]["moneda_id"]);

            obtenerPersonas('Persona', dataPorId["0"]["persona_id"]);

            asignarValorSelect2("cboArticulo", dataPorId["0"]["bien_id"]);

            var idArticulo = select2.obtenerValor("cboArticulo");

            if (idArticulo != "") {
                $('#txt_kilogramo').val('');
                $('#txt_sobre').val('');
                $('#txt_paquete').val('');
                $("#txt_kilogramo").prop("disabled", true);
                $("#txt_sobre").prop("disabled", true);
                $("#txt_paquete").prop("disabled", true);
                $("#txt_precio_minimo").prop("disabled", true);
                habilitarPrecioArticulo();
            } else {
                $("#txt_kilogramo").prop("disabled", false);
                $("#txt_sobre").prop("disabled", false);
                $("#txt_paquete").prop("disabled", false);
                $("#txt_precio_articulo").prop("disabled", true);
            }

            loaderClose();
        } else {
            loaderClose();
        }
    }
}
function confirmarDeleteTarifario(id, nom) {
    bandera_eliminar = false;
    swal(
            {
                title: "Est\xe1s seguro?",
                text: "Eliminarás Tarifario Agencias:" + nom + "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#33b86c",
                confirmButtonText: "Si,eliminar!",
                cancelButtonColor: "#d33",
                cancelButtonText: "No,cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    deleteTarifario(id, nom);
                } else {
                    if (bandera_eliminar == false) {
                        swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
                    }
                }
            }
    );
}

function deleteTarifario(id_tarifario, nom) {
    ax.setAccion("deleteTarifario");
    ax.addParamTmp("id_tarifario", id_tarifario);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}
$("#cboArticulo").change(function () {
    $('#txt_kilogramo').val('');
    $('#txt_sobre').val('');
    $('#txt_paquete').val('');
    $("#txt_kilogramo").prop("disabled", true);
    $("#txt_sobre").prop("disabled", true);
    $("#txt_paquete").prop("disabled", true);
    $("#txt_precio_minimo").prop("disabled", true);

    var articulo = document.getElementById("cboArticulo").value;
    if (articulo == "") {
        $("#txt_kilogramo").prop("disabled", false);
        $("#txt_paquete").prop("disabled", false);
        $("#txt_sobre").prop("disabled", false);
        $("#txt_precio_minimo").prop("disabled", false);
    }
});

function validar_caja_form() {
    $("#msj_origen").hide();
    $("#msj_destino").hide();
    $("#msj_moneda").hide();
    $("#msj_precio_minimo").hide();
    $("#msj_precio_articulo").hide();
    $("#msj_kilogramo").hide();
    $("#msj_paquete").hide();
    $("#msj_sobre").hide();

    var bandera = true;
    var espacio = /^\s+$/;

    var origen = document.getElementById("cboOrigen").value;
    var destino = document.getElementById("cboDestino").value;
    var moneda = document.getElementById("cboMoneda").value;
    var persona = document.getElementById("cboPersona").value;
    var articulo = document.getElementById("cboArticulo").value;

    var precio_minimo = document.getElementById("txt_precio_minimo").value;
    var precio_articulo = document.getElementById("txt_precio_articulo").value;


    var precio_kilogramo = document.getElementById("txt_kilogramo").value;
    var precio_sobre = document.getElementById("txt_sobre").value;
    var precio_paquete = document.getElementById("txt_paquete").value;

    if (origen == "" || origen == null || espacio.test(origen) || origen.length == 0) {
        $("#msj_origen").removeProp(".hidden");
        $("#msj_origen").text("Ingresar una agencia válida").show();
        bandera = false;
    }
    if (destino == "" || destino == null || espacio.test(destino) || destino.length == 0) {
        $("#msj_destino").removeProp(".hidden");
        $("#msj_destino").text("Ingresar una agencia válida").show();
        bandera = false;
    }

    if (moneda == "" || moneda == null || espacio.test(moneda) || moneda.length == 0) {
        $("#msj_moneda").removeProp(".hidden");
        $("#msj_moneda").text("Ingresar moneda").show();
        bandera = false;
    }

    if (persona != "") {
        if (articulo == "") {
            if (precio_minimo == "" || precio_minimo == null || espacio.test(precio_minimo) || precio_minimo.length == 0) {
                $("#msj_precio_minimo").removeProp(".hidden");
                $("#msj_precio_minimo").text("Ingresar precio mínimo").show();
                bandera = false;
            }
        }
    }

    if (articulo != "") {
        if (precio_articulo == "" || precio_articulo == null || espacio.test(precio_articulo) || precio_articulo.length == 0) {
            $("#msj_precio_articulo").removeProp(".hidden");
            $("#msj_precio_articulo").text("Ingresar precio artículo").show();
            bandera = false;
        }
    }

    if (origen != "" || origen != null || !espacio.test(origen) || origen.length != 0) {
        if (persona == "" && articulo == "") {
            if (precio_kilogramo == "" || precio_kilogramo == null || espacio.test(precio_kilogramo) || precio_kilogramo.length == 0) {
                $("#msj_kilogramo").removeProp(".hidden");
                $("#msj_kilogramo").text("Ingresar precio por Kilogramo").show();
                bandera = false;
            }
            if (precio_kilogramo != "" || precio_kilogramo != "0" || !espacio.test(precio_kilogramo) || precio_kilogramo.length != 0) {
                if (precio_paquete == "" || precio_paquete == "0" || precio_paquete == null || espacio.test(precio_paquete) || precio_paquete.length == 0) {
                    $("#msj_paquete").removeProp(".hidden");
                    $("#msj_paquete").text("Ingresar Precio <= 5k ").show();
                    bandera = false;
                }
            }
            if (precio_kilogramo == "" || precio_kilogramo == "0" || espacio.test(precio_kilogramo) || precio_kilogramo.length == 0) {
                if (precio_sobre == "" || precio_sobre == null || espacio.test(precio_sobre) || precio_sobre.length == 0) {
                    $("#msj_sobre").removeProp(".hidden");
                    $("#msj_sobre").text("Ingresar de Sobre").show();
                    bandera = false;
                }
            }
        }

    }

    return bandera;
}

$("#cboPersona").change(function () {
    $("#idPersonaHidden").val($(this).val());
    var persona = document.getElementById("cboPersona").value;
    var articulo = document.getElementById("cboArticulo").value;
    if (persona == "" && articulo == "") {
        $("#txt_kilogramo").prop("disabled", false);
        $("#txt_paquete").prop("disabled", false);
        $("#txt_sobre").prop("disabled", false);
    }
});

$("#cboPersona").change(function () {
    $("#idPersonaHiddenNew").val($(this).val());
});

function guardarTarifario() {

    var id = document.getElementById("id").value;
    var kilogramo = document.getElementById("txt_kilogramo").value;
    var sobre = document.getElementById("txt_sobre").value;
    var paquete = document.getElementById("txt_paquete").value;
    var precio_minimo = document.getElementById("txt_precio_minimo").value;
    var precio_articulo = document.getElementById("txt_precio_articulo").value;

    var origen = document.getElementById("cboOrigen").value;
    var destino = document.getElementById("cboDestino").value;
    var persona = document.getElementById("cboPersona").value;
    var moneda = document.getElementById("cboMoneda").value;
    var bien = document.getElementById("cboArticulo").value;

    if (id != "") {
        updateTarifario(id, origen, destino, persona, moneda, kilogramo, sobre, paquete, bien, precio_minimo, precio_articulo);
    } else {
        insertTarifario(origen, destino, persona, moneda, kilogramo, sobre, paquete, bien, precio_minimo, precio_articulo);
    }
}

function habilitarBoton() {
    $("#env").removeClass("disabled");
    $("#env i").removeClass("fa-spinner fa-spin");
    $("#env i").addClass(c);
}

function deshabilitarBoton() {
    $("#env").addClass("disabled");
    $("#env i").removeClass(c);
    $("#env i").addClass("fa fa-spinner fa-spin");
}

function insertTarifario(origen, destino, persona, moneda, kilogramo, sobre, paquete, bien, precio_minimo, precio_articulo) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertTarifario");
        ax.addParamTmp("origen", origen);
        ax.addParamTmp("destino", destino);
        ax.addParamTmp("persona", persona);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("kilogramo", kilogramo);
        ax.addParamTmp("sobre", sobre);
        ax.addParamTmp("paquete", paquete);
        ax.addParamTmp("bien", bien);
        ax.addParamTmp("precio_minimo", precio_minimo);
        ax.addParamTmp("precio_articulo", precio_articulo);
        ax.consumir();
    }
}

function updateTarifario(id, origen, destino, persona, moneda, kilogramo, sobre, paquete, bien, precio_minimo, precio_articulo) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateTarifario");
        ax.addParamTmp("id_tarifario", id);
        ax.addParamTmp("origen", origen);
        ax.addParamTmp("destino", destino);
        ax.addParamTmp("persona", persona);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("kilogramo", kilogramo);
        ax.addParamTmp("sobre", sobre);
        ax.addParamTmp("paquete", paquete);
        ax.addParamTmp("bien", bien);
        ax.addParamTmp("precio_minimo", precio_minimo);
        ax.addParamTmp("precio_articulo", precio_articulo);
        ax.consumir();
    }
}

// , "width": "50px"
function onResponseDocumentoPorCuenta(data) {
    if (!isEmptyData(data)) {
        $('[data-toggle="popover"]').popover("hide");
        var stringTituloStock =
                "<strong> " + data[0]["documentoTipo_descripcion"] + "</strong>";

        $("#datatableDocumentoPorCuenta").dataTable({
            order: [[0, "desc"]],
            ordering: false,
            data: data,
            columns: [
                {data: "fecha_creacion"},
                {data: "fecha_emision"},
                {data: "documento_tipo_descripcion"},
                {data: "persona_nombre"},
                {data: "serie"},
                {data: "numero"},
                {data: "fecha_vencimiento"},
                {data: "documento_estado_descripcion"},
                {data: "cantidad", sClass: "alignRight"},
            ],
            destroy: true,
        });
        $(".modal-title").empty();
        $(".modal-title").append(stringTituloStock);
        $("#modal-detalle-documentos-servicios").modal("show");
    } else {
        var table = $("#datatableDocumentoPorCuenta").DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documentoTipo.");
    }
}

var actualizandoBusqueda = false;
function exportarReportePorCuentaExcel() {
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorCuentaExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function loaderBuscar() {
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $("#bg-info").attr("aria-expanded");
    if (estadobuscador == "false") {
        buscarTarifario();
    }
    loaderClose();
}

var actualizandoBusquedaAuditoria = false;
function actualizarBusqueda() {
    actualizandoBusquedaAuditoria = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false") {
        buscarTarifario(0);
    }
}
function colapsarBuscador() {
    if (actualizandoBusqueda) {
        actualizandoBusqueda = false;
        return;
    }
    if ($("#bg-info").hasClass("in")) {
        $("#bg-info").attr("aria-expanded", "false");
        $("#bg-info").attr("height", "0px");
        $("#bg-info").removeClass("in");
    } else {
        $("#bg-info").attr("aria-expanded", "false");
        $("#bg-info").removeAttr("height", "0px");
        $("#bg-info").addClass("in");
    }
}

function iniciarDatatable() {
    $("#datatable").dataTable({
        scrollX: true,
        autoWidth: true,
        dom: '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true,
    });
}

function cleanForm() {
    $("#addTarifario").modal("hide");
    document.getElementById("id").value = "";
    select2.asignarValor("cboOrigen", "");
    select2.asignarValor("cboDestino", "");
    select2.asignarValor("cboPersona", "");
    select2.asignarValor("cboMoneda", "");
    select2.asignarValor("cboArticulo", "");
    document.getElementById("txt_kilogramo").value = "";
    document.getElementById("txt_sobre").value = "";
    document.getElementById("txt_paquete").value = "";
    document.getElementById("txt_precio_minimo").value = "";
    document.getElementById("txt_precio_articulo").value = "";

    $("#msj_moneda").hide();
    $("#msj_origen").hide();
    $("#msj_destino").hide();
    $("#msj_persona").hide();
    $("#msj_articulo").hide();
    $("#msj_kilogramo").hide();
    $("#msj_sobre").hide();
    $("#msj_paquete").hide();
    $("#msj_precio_minimo").hide();
    $("#msj_precio_articulo").hide();
}

function openModal() {
    $('[data-toggle="popover"]').popover('hide');
    $("#addTarifario").modal("show");
    $("#modalTitulo").empty();
    $("#modalTitulo").html("Nuevo Tarifario Agencia");
    $("#txt_kilogramo").prop("disabled", false);
    $("#txt_sobre").prop("disabled", false);
    $("#txt_paquete").prop("disabled", false);
    obtenerConfiguracionesInicialesPorActividad();
}

function cerrarModalTarifario() {
    $("#addTarifario").modal("hide");
    cleanForm();
}


// importar tarifario
function importTarifario() {
    $('[data-toggle="popover"]').popover('hide');
    $("#resultado").empty();
    $("#btnImportar").show();
    $("#btnSalirModal").empty();
    $("#btnSalirModal").append(
            "<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar"
            );
    $("#modalImport").modal("show");
    $("#fileImport").val("");
    loaderClose();
}
function importar() {
    $("#resultado").empty();
    $("#btnImportar").hide();
    $("#btnSalirModal").empty();
    $("#btnSalirModal").append(
            "<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir"
            );

    var file = document.getElementById("secret").value;
    //    console.log(file);
    loaderShow(".modal-content");
    ax.setAccion("importTarifario");
    ax.addParam("file", file);
    ax.consumir();
    loaderClose();
}

function exportarTarifario() {
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("exportarTarifario");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function obtenerDataCombo(id) {
    let texto = $($("#cbo" + id).data("select2").search).val();

    if (isEmpty(texto)) {
//        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese un texto para buscar");
        return;
    }

    $('#cbo' + id).select2('close');

    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", texto);
    ax.setTag({id: id, valor: texto});
    ax.consumir();
}

function onResponseObtenerPersonaActivoXStringBusqueda(data, dataCombo) {

    select2.cargarSeleccione("cbo" + dataCombo.id, data, "id", ["nombre", "codigo_identificacion"], "Seleccione una persona");

    loaderClose();
    if (!isEmpty(dataCombo.valor)) {
        $('#cbo' + dataCombo.id).select2('open');
        $($("#cbo" + dataCombo.id).data("select2").search).val(dataCombo.valor);
        $($("#cbo" + dataCombo.id).data("select2").search).trigger('input');
        setTimeout(function () {
            $('.select2-results__option').trigger("mouseup");
        }, 500);
    }
    if (!isEmpty(dataCombo.personaId)) {
        select2.asignarValor("cbo" + dataCombo.id, dataCombo.personaId);
        habilitarPrecioMinimo();
    } else {
        select2.asignarValor("cbo" + dataCombo.id, '');
    }

    setTimeout(function () {
        $($("#cbo" + dataCombo.id).data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13)
            {
                obtenerDataCombo(dataCombo.id);
            }
        });
    }, 1000);
}

function limpiarPersona(id) {
    select2.limpiar('cboPersona' + id);
    $("#cboPersona").append("<option value = ''>Seleccione persona</option>");
    //select2.asignarValor('cboPersona' + id, '');
    habilitarPrecioMinimo();
}

function habilitarPrecioMinimo() {
    $('#msj_precio_minimo').hide();
    var idPersona = select2.obtenerValor("cboPersona");
    var idArticulo = select2.obtenerValor("cboArticulo");

    if (idArticulo == "") {
        if (idPersona == null || idPersona == '') {
            $("#txt_precio_minimo").val('');
            $("#txt_precio_minimo").prop("disabled", true);
        } else {
            $("#txt_precio_minimo").prop("disabled", false);
        }
    }
}

function habilitarPrecioArticulo() {
    $('#msj_precio_articulo').hide();
    var idArticulo = select2.obtenerValor("cboArticulo");

    if (idArticulo == null || idArticulo == '') {
        $("#txt_precio_articulo").val('');
        $("#txt_precio_articulo").prop("disabled", true);
    } else {
        $("#txt_precio_articulo").prop("disabled", false);
    }
}

function obtenerPersonas(id, personaId = null) {
    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", null);
    ax.addParamTmp("personaId", personaId);
    ax.setTag({id: id, personaId: personaId});
    ax.consumir();
}



function listarTarifario() {
    cargarDatosBusqueda();
    ax.setAccion("obtenerDataPorTarifario");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
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
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(true),
        "scrollX": true,
        "autoWidth": true,
        "columns": [
            {"data": "agencia_origen"},
            {"data": "agencia_destino"},
            {"data": "moneda"},
            {"data": "nombre"},
            {"data": "precio_minimo"}, //4
            {"data": "bien_descripcion"},
            {"data": "precio_articulo"}, //6
            {"data": "precio_xk"}, //7
            {"data": "precio_5k"}, //8
            {"data": "precio_sobre"}, //9
            {data: "tarifario_id",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a onclick="getTarifario(' + data + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>\n\
                                   <a onclick="confirmarDeleteTarifario(' + data + ',\''+row.agencia_origen+'\',\''+row.agencia_destino+'\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>';
                    }
                    return data;
                },
                "orderable": true,
                "class": "alignCenter"
            },
        ],
        columnDefs: [
            {
                "render": function (data) {
                    return parseFloat((!isEmpty(data) ? data : 0)).toFixed(2);
                },
                "targets": [4, 6, 7, 8, 9]
            }
        ],
        "order": [1, "asc"],
        destroy: true
    });

}