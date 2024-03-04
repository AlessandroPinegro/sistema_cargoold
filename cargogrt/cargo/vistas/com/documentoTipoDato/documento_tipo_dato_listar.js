var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;
var acciones = {
    getTipoBien: false,
    getEmpresa: false,
    getTipoUnidad: false
};
$(document).ready(function () {
    ax.setSuccess("successDocumentoTipoDato");
    listardocumentoTipoDato();
    modificarAnchoTabla('datatable');
//    cargarTitulo("titulo", "");
    altura();
});

function listardocumentoTipoDato()
{
    ax.setAccion("getDataGridDocumentoTipoDato");
    ax.addParamTmp("empresaId",commonVars.empresa);
    ax.consumir();
}
function successDocumentoTipoDato(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridDocumentoTipoDato':
                onResponseAjaxpGetDataGridDocumentoTipoDato(response.data);
                break;            
        }
    } else
    {
        
    }
}
function onResponseAjaxpGetDataGridDocumentoTipoDato(data) {
   if (!isEmptyData(data))
    {
        $.each(data, function (index, item)
        {
            data[index]["opciones"] = '<a onclick="editarDocumentoTipoDato(' + item['id'] + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>';           
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "documento_tipo"},
                {"data": "nombre_lista"},
                {"data": "opciones", "sClass": "alignCenter"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    loaderClose();
}

function editarDocumentoTipoDato(id) {
    loaderShow(null);
    commonVars.documentoTipoDatoId = id;
    cargarDiv("#window", "vistas/com/documentoTipoDato/documento_tipo_dato_lista_listar.php", "Mantenedor " + obtenerTitulo());
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