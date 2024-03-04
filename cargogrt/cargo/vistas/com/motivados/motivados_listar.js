var c = $("#env i").attr("class");
var acciones = {
    getMotivado: false,
};
$(document).ready(function () {
    //    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    ax.setSuccess("onResponseMotivados");
    // obtenerConfiguracionesInicialesPorActividad();
    obtenerDataMotivado();
});

function onResponseMotivados(response) {
    if (response["status"] === "ok") {
        switch (response[PARAM_ACCION_NAME]) {
            // case "obtenerConfiguracionesInicialesPorActividad":
            //   onResponseObtenerConfiguracionesIniciales(response.data);
            //   //                loaderClose();
            //   break;
            case "obtenerDataMotivado":
                onResponseGetDataGridPorCuenta(response.data);
                // loaderClose();
                break;
            case "insertMotivado":
                loaderClose();
                habilitarBoton();
                obtenerDataMotivado();
                mostrarOk("Motivo guardado correctamente.");
                cleanForm();
                break;
            case "obtenerReportePorCuentaExcel":
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;

            case "deleteMotivado":
                var error = response.data["0"].vout_exito;
                if (error > 0) {
                    swal(
                            "Eliminado!",
                            "Se ah eliminado motivado: " + response.data["0"].nombre + ".",
                            "success"
                            );
                    obtenerDataMotivado();
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

            case "getMotivado":
                acciones.getMotivado = true;
                dataPorId = response.data;
                motivadoCargarData();
                break;

            case "updateMotivado":
                loaderClose();
                habilitarBoton();
                obtenerDataMotivado();
                mostrarOk("Motivado actualizado correctamente.");
                cleanForm();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case "insertMotivado":
                loaderClose();
                habilitarBoton();
                break;
            case "insertMotivado":
                loaderClose();
                habilitarBoton();
                break;

            case "updateMotivado":
                loaderClose();
                habilitarBoton();
                break;
        }
    }
}

function obtenerDataMotivado() {
    //alert('hola');
    ax.setAccion("obtenerDataMotivado");
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {

    if (!isEmpty(data.actividad_tipo)) {
        select2.cargar(
                "cboActividadTipo",
                data.actividad_tipo,
                "id",
                "descripcion"
                );
    }

    if (!isEmpty(data.actividad)) {
        select2.cargar("cboActividad", data.agencia, "agencia_id", [
            "codigo",
            "codigo",
        ]);
    }

    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }

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
    if (!isEmpty(data.persona)) {
        select2.cargarSeleccione(
                "cboPersona",
                data.persona,
                "id",
                ["codigo_identificacion", "nombre_completo"],
                "Seleccione una persona"
                );
        select2.asignarValor("cboPersona", "");
    }
    if (!isEmpty(data.moneda)) {
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.asignarValor("cboMoneda", "2");
    }
    loaderClose();
}

var valoresBusquedaPorActividad = [
    {
        mes: "",
        anio: "",
        tienda: "",
        actividad: "",
        actividadTipo: "",
        agenciaO: "",
    },
];


function onResponseGetDataGridPorCuenta(data) {

    $("#datatable2").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza =
            '<table id="datatable" class="table table-striped table-bordered" style="width: 100%"><thead>' +
            " <tr>" +
            "<th style='text-align:center; vertical-align: middle;'>Motivo</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Descripción</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Ejemplo</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
    $.each(data, function (index, item) {
        if (item.ejemplos == null) {
            item.ejemplos = "";
        } else {
            item.ejemplos = item.ejemplos;
        }

        cuerpo =
                "<tr>" +
                "<td style='text-align:left;'>" +
                item.motivo +
                "</td>" +
                "<td style='text-align:left;'>" +
                item.descripcion +
                "</td>" +
                "<td style='text-align:left;'>" +
                item.ejemplos +
                "</td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='getMotivado(" +
                item.id +
                ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteMotivado(" + item.id + ", \"" + item.motivo + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = "</table>";
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);

    $("#datatable").dataTable();
}

$("#txt_motivo").keypress(function () {
    $("#msj_motivo").hide();
});
$("#txt_descripcion").keypress(function () {
    $("#msj_descripcion").hide();
});
$("#txt_ejemplo").keypress(function () {
    $("#msj_ejemplo").hide();
});


function getMotivado(id) {
    VALOR_ID_USUARIO = id;
    loaderShow();
    ax.setAccion("getMotivado");
    ax.addParamTmp("id_motivado", id);
    ax.consumir();
}
function asignarValorSelect2(id, valor) {
    $("#" + id)
            .select2()
            .select2("val", valor);
    $("#" + id).select2({width: "100%"});
}
function motivadoCargarData() {
    if (acciones.getMotivado) {
        if (!isEmpty(VALOR_ID_USUARIO)) {
            //                llenarFormularioEditar(dataPorId);
            document.getElementById("id").value = dataPorId["0"]["id"];
            document.getElementById("txt_motivo").value = dataPorId["0"]["motivo"];
            document.getElementById("txt_descripcion").value = dataPorId["0"]["descripcion"];
            document.getElementById("txt_ejemplo").value = dataPorId["0"]["ejemplos"];
            loaderClose();
        } else {
            loaderClose();
        }
    }
}
function confirmarDeleteMotivado(id, nom) {
    bandera_eliminar = false;
    swal(
            {
                title: "Est\xe1s seguro?",
                text: "Eliminarás " + nom,
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
                    deleteMotivado(id, nom);
                } else {
                    if (bandera_eliminar == false) {
                        swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
                    }
                }
            }
    );
}

function deleteMotivado(id, nom) {
    ax.setAccion("deleteMotivado");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}

function validar_caja_form() {
    var bandera = true;
    var espacio = /^\s+$/;

    var motivo = document.getElementById("txt_motivo").value;
    var descripcion = document.getElementById("txt_descripcion").value;
    var ejemplo = document.getElementById("txt_ejemplo").value;

    if (motivo == "" || espacio.test(motivo) || motivo.length == 0) {
        $("msj_motivo").removeProp(".hidden");
        $("#msj_motivo").text("Ingresar motivo").show();
        bandera = false;
    }

    if (descripcion == "" || espacio.test(descripcion) || descripcion.length == 0) {
        $("msj_descripcion").removeProp(".hidden");
        $("#msj_descripcion").text("Ingresar descripción").show();
        bandera = false;
    }

    return bandera;
}

function guardarMotivo() {
    var id = document.getElementById("id").value;
    var motivo = document.getElementById("txt_motivo").value;
    var descripcion = document.getElementById("txt_descripcion").value;
    var ejemplo = document.getElementById("txt_ejemplo").value;

    if (id != "") {
        updateMotivado(id, motivo, descripcion, ejemplo);
    } else {
        insertMotivado(motivo, descripcion, ejemplo);
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
function insertMotivado(motivo, descripcion, ejemplo) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertMotivado");
        ax.addParamTmp("motivo", motivo);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("ejemplo", ejemplo);
        ax.consumir();
    }
}
function updateMotivado(id, motivo, descripcion, ejemplo) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateMotivado");
        ax.addParamTmp("id", id);
        ax.addParamTmp("motivo", motivo);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("ejemplo", ejemplo);
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
    ax.addParamTmp("criterios", valoresBusquedaPorCuenta);
    ax.consumir();
}

function loaderBuscar() {
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $("#bg-info").attr("aria-expanded");
    if (estadobuscador == "false") {
        buscarPorCuenta();
    }
    loaderClose();
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
    $("#datatable2").dataTable({
        scrollX: true,
        autoWidth: true,
        destroy: true,
    });
}


function cleanForm() {
    document.getElementById("id").value = ""
    document.getElementById("txt_motivo").value = "";
    document.getElementById("txt_descripcion").value = "";
    document.getElementById("txt_ejemplo").value = "";
}