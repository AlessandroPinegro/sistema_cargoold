
$(document).ready(function () {
    //loaderShow();
    loaderClose();
    ax.setSuccess("exitoSolicitudViatico");
    onResponseVacio();
//    listarSolicitudViatico();
});

function listarSolicitudViatico(){
    ax.setAccion("listarSolicitudViatico");
    ax.consumir();
}

function exitoSolicitudViatico(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarSolicitudViatico':
                onResponseListarSolicitudViatico(response.data);
                onResponseVacio();
                loaderClose();
                break;
        }
    }
}

function onResponseVacio() {
    $('#datatable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
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
//        "order": [[7, "desc"]],
//        "processing": true,
//        "serverSide": true,
        "bFilter": false,
////        "ajax": ax.getAjaxDataTable(),
//        "scrollX": true,
//        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
//        destroy: true
    });
}

function nuevo() {
    var titulo = "Nueva";
    var url = URL_BASE + "vistas/com/rendirCuenta/solicitud_viatico_form.php?winTitulo=" + titulo;
    cargarDiv("#window", url);
}

function editar(id) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/rendirCuenta/solicitud_viatico_form.php?winTitulo=" + titulo + "&id=" + id;
    cargarDiv("#window", url);
}