$(document).ready(function (){
  
    loaderClose();      
    ax.setSuccess("SuccessReporteReparto");                
});

function SuccessReporteReparto(response)
{
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'exportarReporteRepartosExcel':
                   loaderClose();
                   location.href = URL_BASE + "util/formatos/ReporteRepartos.xls";
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




function BuscarResultadoReparto()
{
    let parametro1=  $('#fechaInicioped').val();   
    let parametro2=  $('#fechaFinped').val();  
    ax.setAccion("ReportePedidosRepartoPCE");
    ax.addParamTmp("bfechainipedido", parametro1);
    ax.addParamTmp("bfechafinpedido", parametro2);

   // ax.consumir(); 
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
            { "data": "numero_pedido", "sClass": "alignCenter" },
                        { "data": "numero_cpe", "sClass": "alignCenter" },
                        { "data": "TipoComprobante", "sClass": "alignCenter" },
                        { "data": "fecha_creacion", "sClass": "alignCenter" },
                        { "data": "origen", "sClass": "alignCenter" },
                        { "data": "destino", "sClass": "alignCenter" },
                        { "data": "doc_remitente", "sClass": "alignCenter" },
                        { "data": "nombre_remitente", "sClass": "alignCenter" },
                        { "data": "doc_destinatario", "sClass": "alignCenter" },
                        { "data": "nombre_destinatario", "sClass": "alignCenter" },
                        { "data": "persona_direccion_destino", "sClass": "alignCenter" },
                        { "data": "telefono_destinatario", "sClass": "alignCenter" },         
                        { "data": "total_paquetes", "sClass": "alignCenter" },    
                        { "data": "Modalidad", "sClass": "alignCenter" },  
                        { "data": "manifiesto", "sClass": "alignCenter" },  
                        { "data": "flota_placa", "sClass": "alignCenter" },  
                        { "data": "flota_numero", "sClass": "alignCenter" }, 
                        { "data": "TipoFlota", "sClass": "alignCenter" }, 
                        { "data": "costo_recojo_domicilio", "sClass": "alignCenter" }, 
                        { "data": "monto_costo_reparto", "sClass": "alignCenter" }, 
                        { "data": "otrosgastos", "sClass": "alignCenter" }, 
                        { "data": "igvcpe", "sClass": "alignCenter" }, 
                        { "data": "totalcpe", "sClass": "alignCenter" }, 
                    ],
        
        destroy: true
});
   
}









$('.fecha').datepicker({
    isRTL: false,
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'es'
});



function exportarReporteRepartosExcel()
{
    loaderShow();
    let parametro1=  $('#fechaInicioped').val();   
    let parametro2=  $('#fechaFinped').val();  
    ax.setAccion("exportarReporteRepartosExcel");
    ax.addParamTmp("bfechainipedido", parametro1);
    ax.addParamTmp("bfechafinpedido", parametro2);
    ax.consumir(); 

}