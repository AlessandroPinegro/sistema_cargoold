$(document).ready(function (){
    loaderClose();  
    ax.setSuccess("SuccessReportesInventario");                        
});

function SuccessReportesInventario(response)
{
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'exportarInventarioTotalExcel':
                   loaderClose();
                   location.href = URL_BASE + "util/formatos/inventariocargoall.xls";
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


function BuscarResultadoInventario()
{
    let parametrofecha1=  $('#fechaIniciorep').val();    // alert("Parametros :"+parametrofecha1);
    let parametrofecha2=  $('#fechaFinrep').val();  
    // alert("fecha ini: "+parametrofecha1); 
    // alert("fecha fin: "+parametrofecha2); // alert("Parametros :"+parametrofecha2);
    ax.setAccion("ReporteInventarioTotal");
    ax.addParamTmp("bfechaini", parametrofecha1);
    ax.addParamTmp("bfechafin", parametrofecha2);
    // ax.consumir(); 
    $('#datatableinventario').dataTable({
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
                        { "data": "docserie", "sClass": "alignCenter" },
                        { "data": "docnumero", "sClass": "alignCenter" },
                        { "data": "Docnumeroserie", "sClass": "alignCenter" },
                        { "data": "docfecha", "sClass": "alignCenter" },
                        { "data": "DocRemitente", "sClass": "alignCenter" },
                        { "data": "Remitente", "sClass": "alignCenter" },
                        { "data": "TelRemitente", "sClass": "alignCenter" },
                        { "data": "direccionorigen", "sClass": "alignCenter" },
                        { "data": "Origen", "sClass": "alignCenter" },
                        { "data": "DocDestinatario", "sClass": "alignCenter" } ,//1
                        { "data": "Destinatario", "sClass": "alignCenter" },
                        { "data": "direcciondestino", "sClass": "alignCenter" },
                        { "data": "TelDestinatario", "sClass": "alignCenter" },
                        { "data": "Destino", "sClass": "alignCenter" },         
                        { "data": "estadoventa", "sClass": "alignCenter" },    
                        { "data": "doctotal-Soles", "sClass": "alignCenter" },   
                        { "data": "pedidoserie", "sClass": "alignCenter" },  
                        { "data": "pedidonumero", "sClass": "alignCenter" },  
                        { "data": "pedidofecha", "sClass": "alignCenter" },  
                        { "data": "estadopedido", "sClass": "alignCenter" }, 
                        { "data": "modalidad", "sClass": "alignCenter" }, 
                        { "data": "pesototal", "sClass": "alignCenter" }, 
                        { "data": "manifiesto", "sClass": "alignCenter" }, 
                        { "data": "vehiculo_placa", "sClass": "alignCenter" },
                        { "data": "vehiculo_flota", "sClass": "alignCenter" },
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


function ExportarInventarioTotalExcel()
{
    loaderShow();
    let parametrofecha1=  $('#fechaIniciorep').val();    
     let parametrofecha2=  $('#fechaFinrep').val(); 
    ax.setAccion("exportarInventarioTotalExcel");
    ax.addParamTmp("bfechainirepxls", parametrofecha1);
    ax.addParamTmp("bfechafinrepxls", parametrofecha2);   
    ax.consumir();  
}