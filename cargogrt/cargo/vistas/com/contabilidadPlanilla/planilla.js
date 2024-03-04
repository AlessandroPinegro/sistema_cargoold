$(document).ready(function () {
    ax.setSuccess("exitoPlanilla");
    obtenerConfiguracionInicial();
    listarImportarArchivo();
    modificarAnchoTabla('datatable');
    inicializaComponentes();
});
function inicializaComponentes() {
    select2.iniciar();
}
var dataTipoArchivo = [{id: 1, descripcion: "Planilla"}, {id: 2, descripcion: "CTS"}, {id: 3, descripcion: "Gratificación"}];
function obtenerConfiguracionInicial() {
    loaderShow();
    ax.setAccion("obtenerConfiguracionInicial");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function listarImportarArchivo() {
    ax.setAccion("obtenerPlaImportarArchivo");
    ax.consumir();
}

function exitoPlanilla(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicial':
                onResponseObtenerConfiguracionInicial(response.data);
                loaderClose();
                break;
            case 'obtenerPlaImportarArchivo':
                onResponseObtenerPlaImportarArchivo(response.data);
                loaderClose();
                break;
            case 'registrarActualizarImportarArchivo':
                $('#modalImportar').modal('hide');
                listarImportarArchivo();
                loaderClose();
                break;
        }
    }
}

function onResponseObtenerConfiguracionInicial(data) {
    select2.cargar('cboPeriodo', data.dataPeriodo, 'id', ['anio', 'mes']);
    select2.asignarValor('cboPeriodo', data.dataPeriodoActual[0]['id']);
    select2.cargar('cboTipoDocumento', dataTipoArchivo, 'id', 'descripcion');

}
function onResponseObtenerPlaImportarArchivo(data) {
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            data[index]["nombre"] = "<a style='color: #4caf50;font-weight: bold;' onclick='abrirExcel(\"" + item["url"] + "\")'>" + item["nombre"] + "</a>";
            data[index]["accion"] = "<a style='color: #02028E;font-weight: bold;' onclick='actualizarImportarExcel(" + item["id"] + "," + item["periodo_id"] + "," + item["tipo_archivo"] + ")'><i class='ion-loop'></i></a>";
            switch (data[index]["estado"] * 1) {
                case 1:
                    data[index]["estado"] = "Habilitado";
                    break;
            }
            data[index]["tipo_archivo"] = dataTipoArchivo.find(elemento => elemento.id == data[index]["tipo_archivo"]).descripcion;
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[4, "desc"]],
            "data": data,
            "columns": [
                {"data": "periodo"},
                {"data": "nombre"},
                {"data": "tipo_archivo"},
                {"data": "usuario"},
                {"data": "fecha_creacion"},
                {"data": "estado"},
                {"data": "accion"}
            ],
            "destroy": true
        });
    }
}
function abrirTxt(txt) {
    window.location.href = URL_BASE + "util/uploads/" + txt;
}
function abrirExcel(excel) {
    window.location.href = URL_BASE + "util/uploads/documentoPlanilla/" + excel;
}

/*IMPORTAR EXCEL*/
$(function () {
    $(":file").change(function () {
        //validar que la extension sea .xls
        var nombreArchivo = $(this).val().slice(12);
        var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();

        if (extension != "xls") {
            $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'La extensión del excel tiene que ser .xls');
            return;
        }

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#lblImportarArchivo").text(nombreArchivo);
                $('#secret').attr('value', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
            $fileupload = $('#file');
            $fileupload.replaceWith($fileupload.clone(true));
        }
    });
});

function importarArchivo()
{
    var file = document.getElementById('secret').value;
    var id = document.getElementById('archivoId').value;

    if (isEmpty($('#secret').attr('value'))) {
        mostrarAdvertencia("No se especificó un archivo correcto!");
        return;
    }

    loaderShow('#modalImportar');

    var periodo_id = $('#cboPeriodo').val();
    var tipo_archivo = $('#cboTipoDocumento').val();
    var archivo_nombre = $("#lblImportarArchivo").text();

    if (isEmpty(periodo_id)) {
        mostrarAdvertencia("Debe seleccionar un periodo.");
        return;
    }

    if (isEmpty(tipo_archivo)) {
        mostrarAdvertencia("Debe seleccionar un tipo de documento.");
        return;
    }

    if (isEmpty(archivo_nombre) || archivo_nombre == "Seleccione archivo excel") {
        mostrarAdvertencia("Aún no selecciona un documento.");
        return;
    }

    $('#btnGenerar').hide();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboPeriodo").attr("disabled", true);
    $("#cboTipoDocumento").attr("disabled", true);

    ax.setAccion("registrarActualizarImportarArchivo");
    ax.addParam("arhivoContenido", file);
    ax.addParam("archivoNombre", archivo_nombre);
    ax.addParam("archivoTipo", tipo_archivo);
    ax.addParam("periodoId", periodo_id);
    ax.addParam("id", id);
    ax.consumir();
}

function prepararImportarExcel() {
    $('#btnGenerar').show();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboPeriodo").attr("disabled", false);
    $("#cboTipoDocumento").attr("disabled", false);

    $('#resultado').empty();
    $('#file').val('');
    $('#secretFile').attr('value', null);
    $('#archivoId').attr('value', null);
    $("#lblImportarArchivo").text("Seleccione archivo excel");
    $('#modalImportar').modal('show');
}

function actualizarImportarExcel(id, periodoId, tipoArchivo) {
    $('#btnGenerar').show();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboPeriodo").attr("disabled", false);
    $("#cboTipoDocumento").attr("disabled", false);

    select2.asignarValor('cboPeriodo', periodoId);
    select2.asignarValor('cboTipoDocumento', tipoArchivo);

    $("#cboPeriodo").attr("disabled", true);
    $("#cboTipoDocumento").attr("disabled", true);

    $('#resultado').empty();
    $('#file').val('');
    $('#archivoId').attr('value', id);
    $('#secretFile').attr('value', null);
    $("#lblImportarArchivo").text("Seleccione archivo excel");
    $('#modalImportar').modal('show');
}