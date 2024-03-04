$(document).ready(function (){
    loaderClose();  
    ax.setSuccess("SuccessReportesAdmin");                        
});

function SuccessReportesAdmin(response)
{
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'exportarReporteventasSolesTotalExcel':
                   loaderClose();
                   location.href = URL_BASE + "util/formatos/reportetotalventassoles.xls";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            // case 'obtenerReporteCierreCajaExcel':
            //     loaderClose();
            //     break;
        }
    }
}


function BuscarResultadoReportenuevo1()
{
    let parametrofecha1=  $('#fechaIniciorep').val();    // alert("Parametros :"+parametrofecha1);
    let parametrofecha2=  $('#fechaFinrep').val();    // alert("Parametros :"+parametrofecha2);
    ax.setAccion("ReporteVentasSolesxFecha");
    ax.addParamTmp("bfechaini", parametrofecha1);
    ax.addParamTmp("bfechafin", parametrofecha2);
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
//     "serverSide": true,
    "bFilter": true,
    "ajax": ax.getAjaxDataTable(true),
    "scrollX": true,
    "autoWidth": true,
        "columns": [
                    { "data": "serie", "sClass": "alignCenter" },
                    { "data": "numero", "sClass": "alignCenter" },
                    { "data": "serie_correlativo", "sClass": "alignCenter" },
                    { "data": "tipo_documento", "sClass": "alignCenter" },
                    { "data": "Remitente", "sClass": "alignCenter" },
                    { "data": "Destinatario", "sClass": "alignCenter" },
                    { "data": "modalidad", "sClass": "alignCenter" },
                    { "data": "estado_pedido", "sClass": "alignCenter" },
                    { "data": "estado_comprobante", "sClass": "alignCenter" },
                    { "data": "agencia_origen", "sClass": "alignCenter" } ,//1
                    { "data": "agencia_destino", "sClass": "alignCenter" },
                    { "data": "fecha_venta", "sClass": "alignCenter" },
                    { "data": "otros_gastos", "sClass": "alignCenter" },
                    { "data": "devolucion_gasto", "sClass": "alignCenter" },
                    { "data": "costo_reparto", "sClass": "alignCenter" },
                    { "data": "costo_recojodomicilio", "sClass": "alignCenter" },
                    { "data": "ajuste_precio", "sClass": "alignCenter" },
                    { "data": "subtotal", "sClass": "alignCenter" },
                    { "data": "igv", "sClass": "alignCenter" },                    
                    { "data": "venta_total", "sClass": "alignCenter" },
                    { "data": "serie_nc", "sClass": "alignCenter" },
                    { "data": "numero_nc", "sClass": "alignCenter" },
                    { "data": "pago_efectivo", "sClass": "alignCenter" },
                    { "data": "pago_ticketdeposito", "sClass": "alignCenter" },
                    { "data": "pago_tickettransferencia", "sClass": "alignCenter" },
                    { "data": "pago_ticketpos", "sClass": "alignCenter" },
                     { "data": "pago_pasarelapago", "sClass": "alignCenter" }                    
                    ],
        
        destroy: true
});
    loaderClose();
    // ax.consumir();  
}

function ExportarReporteVentasSolesTotalExcel()
{
    loaderShow();
    let parametrofecha1=  $('#fechaIniciorep').val();    
     let parametrofecha2=  $('#fechaFinrep').val(); 
    ax.setAccion("exportarReporteventasSolesTotalExcel");
    ax.addParamTmp("bfechainirep", parametrofecha1);
    ax.addParamTmp("bfechafinrep", parametrofecha2);   
    ax.consumir();  
}

$('.fecha').datepicker({
    isRTL: false,
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'es'
});


// $('#datatable').dataTable({
//     "language": {
//         "sProcessing": "Procesando...",
//         "sLengthMenu": "Mostrar _MENU_ registros",
//         "sZeroRecords": "No se encontraron resultados",
//         "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
//         "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
//         "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
//         "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
//         "sInfoPostFix": "",
//         "sSearch": "Buscar:",
//         "sUrl": "",
//         "sInfoThousands": ",",
//         "sLoadingRecords": "Cargando...",
//         "oPaginate": {
//             "sFirst": "Primero",
//             "sLast": "Último",
//             "sNext": "Siguiente",
//             "sPrevious": "Anterior"
//         },
//         "oAria": {
//             "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
//             "sSortDescending": ": Activar para ordenar la columna de manera descendente"
//         }
//     },
//         "processing": true,
// //     "serverSide": true,
//     "bFilter": true,
//     "ajax": ax.getAjaxDataTable(true),
//     "scrollX": true,
//     "autoWidth": true,
//         "columns": [
//                     { "data": "serie", "sClass": "alignCenter" },
//                     { "data": "numero", "sClass": "alignCenter" },
//                     { "data": "serie_correlativo", "sClass": "alignCenter" },
//                     { "data": "tipo_documento", "sClass": "alignCenter" },
//                     { "data": "Remitente", "sClass": "alignCenter" },
//                     { "data": "Destinatario", "sClass": "alignCenter" },
//                     { "data": "modalidad", "sClass": "alignCenter" },
//                     { "data": "estado_pedido", "sClass": "alignCenter" },
//                     { "data": "estado_comprobante", "sClass": "alignCenter" },
//                     { "data": "agencia_origen", "sClass": "alignCenter" } ,//1
//                     { "data": "agencia_destino", "sClass": "alignCenter" },
//                     { "data": "fecha_venta", "sClass": "alignCenter" },
//                     { "data": "otros_gastos", "sClass": "alignCenter" },
//                     { "data": "devolucion_gasto", "sClass": "alignCenter" },
//                     { "data": "costo_reparto", "sClass": "alignCenter" },
//                     { "data": "costo_recojodomicilio", "sClass": "alignCenter" },
//                     { "data": "ajuste_precio", "sClass": "alignCenter" },
//                     { "data": "subtotal", "sClass": "alignCenter" },
//                     { "data": "igv", "sClass": "alignCenter" },                    
//                     { "data": "venta_total", "sClass": "alignCenter" },
//                     { "data": "serie_nc", "sClass": "alignCenter" },
//                     { "data": "numero_nc", "sClass": "alignCenter" },
//                     { "data": "pago_efectivo", "sClass": "alignCenter" },
//                     { "data": "pago_ticketdeposito", "sClass": "alignCenter" },
//                     { "data": "pago_tickettransferencia", "sClass": "alignCenter" },
//                     { "data": "pago_ticketpos", "sClass": "alignCenter" },
//                      { "data": "pago_pasarelapago", "sClass": "alignCenter" }                    
//                     ],
        
//         destroy: true
// });
//     loaderClose();