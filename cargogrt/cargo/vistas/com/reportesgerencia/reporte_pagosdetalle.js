$(document).ready(function (){
    loaderClose();  
    ax.setSuccess("SuccessReportesPagos");                        
});


function SuccessReportesPagos(response)
{
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'ExportarDetallePagosExcel':
                   loaderClose();
                   location.href = URL_BASE + "util/formatos/pagosdetallados.xls";
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


function BuscarResultadoDetallePagos()
{
    let parametrofecha1=  $('#fechaIniciorep').val();    // alert("Parametros :"+parametrofecha1);
    let parametrofecha2=  $('#fechaFinrep').val();  
    // alert("fecha ini: "+parametrofecha1); 
    // alert("fecha fin: "+parametrofecha2); // alert("Parametros :"+parametrofecha2);
    loaderShow();
    ax.setAccion("ReportePagosDetalle");
    ax.addParamTmp("fechapagoini", parametrofecha1);
    ax.addParamTmp("fechapagofn", parametrofecha2);
     //ax.consumir(); 
     $('#datatablepagos').dataTable({
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
                        { "data": "doc", "sClass": "alignCenter" },
                        { "data": "tipo_cpe", "sClass": "alignCenter" },
                        { "data": "fecha_emision", "sClass": "alignCenter" },
                        { "data": "total", "sClass": "alignCenter" },
                        { "data": "estado", "sClass": "alignCenter" },
                        { "data": "codpago", "sClass": "alignCenter" },
                        { "data": "importe_pago", "sClass": "alignCenter" },
                        { "data": "forma_pago", "sClass": "alignCenter" },
                        { "data": "fecha_pago", "sClass": "alignCenter" },
                        { "data": "estadoPago", "sClass": "alignCenter" },
                        { "data": "usuario", "sClass": "alignCenter" },
                        { "data": "caja", "sClass": "alignCenter" },
                        { "data": "agencia", "sClass": "alignCenter" },
                        ],
            destroy: true
    });
        loaderClose();
    
}

$('.fecha').datepicker({
    isRTL: false,
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'es'
});



function ExportarDetallePagosExcel()
{
    loaderShow();
    let parametrofecha1=  $('#fechaIniciorep').val(); 
    let parametrofecha2=  $('#fechaFinrep').val();  
    ax.setAccion("ExportarDetallePagosExcel");
    ax.addParamTmp("fechapagoinixls", parametrofecha1);
    ax.addParamTmp("fechapagofnxls", parametrofecha2);   
    ax.consumir();  
}