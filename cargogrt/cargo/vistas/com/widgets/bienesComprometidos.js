$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("onResponseBienesComprometidos");
    obtenerConfiguracionesInicialesBienesComprometidos();
});

function obtenerConfiguracionesInicialesBienesComprometidos()
{
    ax.setAccion("obtenerConfiguracionesBienesComprometidos");
    ax.consumir();
}
var cantidada = 0;
function onResponseBienesComprometidos(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesBienesComprometidos':
                $("#lblFecha").append(response.data);
                buscarBienesComprometidos(0);
                loaderClose();
                break;
            case 'obtenerCantidadBienesComprometidos':
                if (response.data === null)
                {
                    response.data = 0;
                }
                if (response.data === null)
                {
                    response.data = 0;
                }

                cantidad = response.data;
                obtenerDataBusquedaBienesComprometidos();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

        }
    }
}

var valoresBusquedaBienesComprometidos = [{estado: "", empresaId: ""}];
function cargarDatosBusqueda()
{
    var estado = $('#cboEstado').val();
    valoresBusquedaBienesComprometidos[0].estado = estado;
    valoresBusquedaBienesComprometidos[0].empresaId = commonVars.empresa;
}

function obtenerDataBusquedaBienesComprometidos()
{
    ax.setAccion("DataBusquedaBienesComprometidos");
    ax.addParamTmp("criterios", valoresBusquedaBienesComprometidos);

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
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[1, "desc"]],
        "columns": [
            {"data": "organizador_descripcion", "width": "150px"},
            {"data": "bien_descripcion", "width": "150px"},
            {"data": "unidad_medida_descripcion", "width": "250px"},
            {"data": "cantidad_total", "class": "alignRight", "width": "60px"}
//                {data: "auditoria_estado",
//                "orderable": false,
//                "class": "alignCenter"
//            },
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(3).footer()).html(
                    (formatearNumero(cantidad))
                    );
        }
    });
    loaderClose();
}
function colapsarBuscador() {
    if (actualizandoBusquedaBienesComprometidos) {
        actualizandoBusquedaBienesComprometidos = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}
var actualizandoBusquedaBienesComprometidos = false;
function actualizarBusqueda()
{
    actualizandoBusquedaBienesComprometidos = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarBienesComprometidos(0);
    }
}
function buscarBienesComprometidos(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadBienesComprometidos();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    cadena += negrita("Estado: ");
    cadena += select2.obtenerText('cboEstado');
    cadena += "<br>";

    if (isEmpty(cadena))
    {
        cadena = "Todos";
    }
    return cadena;
}
function obtenerCantidadBienesComprometidos()
{
    ax.setAccion("obtenerCantidadBienesComprometidos");
    ax.addParamTmp("criterios", valoresBusquedaBienesComprometidos);
    ax.consumir();
}

