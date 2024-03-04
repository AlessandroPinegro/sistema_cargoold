/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
//    alert(1);
    cargarComponentes();
    altura();
//    onResponseVacio();
});

function cargarComponentes() {
    cargarSelect2();
    cargarDatePicker('fecha');
}

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

function cargarDatePicker(id) {
    $('#' + id).datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        language: 'es'
    });
}

function cargarPantallaListar(){    
    var url = URL_BASE + "vistas/com/rendirCuenta/solicitud_viatico_listar.php";
    cargarDiv("#window", url);
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
        "order": [[0, "desc"]],
//        "processing": true,
//        "serverSide": true,
        "bFilter": false,
////        "ajax": ax.getAjaxDataTable(),
//        "scrollX": true,
//        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
//        destroy: true
    });
}