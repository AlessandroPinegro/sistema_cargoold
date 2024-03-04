$(document).ready(function () {
    ax.setSuccess("exitoPlanilla");
    obtenerConfiguracionInicial();
    listarActivosFijos();
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

function listarActivosFijos() {
    ax.setAccion("obtenerActivosFijos");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function exitoPlanilla(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicial':
                onResponseObtenerConfiguracionInicial(response.data);
                loaderClose();
                break;
            case 'obtenerActivosFijos':
                onResponseObtenerActivosFijos(response.data);
                loaderClose();
                break;
            case 'obtenerExcelActivosFijoSunat':
                abrirExcel(response.data);
                loaderClose();
                break;

            case 'generarDepreciacion':
                $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', response.data[0]['vout_mensaje']);
                $('#modalGenerarDepreciacion').modal('hide');
                listarActivosFijos();
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
function onResponseObtenerActivosFijos(data) {
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
//            data[index]["nombre"] = "<a style='color: #4caf50;font-weight: bold;' onclick='abrirExcel(\"" + item["url"] + "\")'>" + item["nombre"] + "</a>";
            data[index]["accion"] = "<a style='color: #02028E;font-weight: bold;' onclick='obtenerExcelActivosFijo(" + item["id"] + ")'><i class='fa fa-cloud-download'></i></a>";
            switch (data[index]["estado"] * 1) {
                case 1:
                    data[index]["estado"] = "Habilitado";
                    break;
            }
//            data[index]["tipo_archivo"] = dataTipoArchivo.find(elemento => elemento.id == data[index]["tipo_archivo"]).descripcion;
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[4, "desc"]],
            "data": data,
            "columns": [
                {"data": "anio"},
                {"data": "nombre"},
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
    window.location.href = URL_BASE + "util/formatos/" + txt;
}
function abrirExcel(excel) {
    window.location.href = URL_BASE + "util/formatos/documentoActivoFijo/" + excel;
}

function obtenerExcelActivosFijo(id) {
    loaderShow();
    ax.setAccion("obtenerExcelActivosFijoSunat");
    ax.addParam("activoFijoId", id);
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function generarDepreciacion()
{
    loaderShow('#modalGenerarDepreciacion');
    var periodo_id = $('#cboPeriodo').val();
    if (isEmpty(periodo_id)) {
        mostrarAdvertencia("Debe seleccionar un periodo.");
        return;
    }
    $('#btnGenerar').hide();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboPeriodo").attr("disabled", true);

    ax.setAccion("generarDepreciacion");
    ax.addParam("periodoId", periodo_id);
    ax.consumir();
}

function prepararModalGenerarDepreciacion() {
    $('#btnGenerar').show();
    $('#btnSalirModal').html("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboPeriodo").attr("disabled", false);
    $('#modalGenerarDepreciacion').modal('show');
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
    $('#modalGenerarDepreciacion').modal('show');
}