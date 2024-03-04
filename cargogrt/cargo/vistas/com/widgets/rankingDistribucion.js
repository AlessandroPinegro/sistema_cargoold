$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("onResponseRankingDistribucion");
    obtenerCantidadTotalRankingDistribucion();
});
var cantidad = 0;
function onResponseRankingDistribucion(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerCantidadTotalRankingDistribucion':
                if (response.data === null)
                {
                    response.data = 0;
                }
                if (response.data === null)
                {
                    response.data = 0;
                }
                cantidad = response.data;
                getDataTable();
                break;
        }
    }
}


function getDataTable() {
   var posicion = 0;
    ax.setAccion("obtenerDataRankingDistribucion");
    ax.addParamTmp("empresa", commonVars.empresa);
    $('#datatable').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
//        "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
            {data: "orden",
                render: function (data, type, row) {
                    posicion = posicion + 1;
                    if (type === 'display') {
                        return posicion + "°";
                    }
                    return data;
                },"orderable": false, "width": "70px"
            },
            {"data": "persona_nombre_completo","orderable": false, "width": "70px", "width": "100px"},
            {"data": "cantidad","orderable": false, "width": "70px", "class": "alignRight", "width": "60px"}
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(2).footer()).html(
                    (formatearNumero(cantidad))
                    );
        }
    });
    posicion = 0;
    loaderClose();
}

function obtenerCantidadTotalRankingDistribucion()
{
    ax.setAccion("obtenerCantidadTotalRankingDistribucion");
    ax.addParamTmp("empresa", commonVars.empresa);
    ax.consumir();
}

