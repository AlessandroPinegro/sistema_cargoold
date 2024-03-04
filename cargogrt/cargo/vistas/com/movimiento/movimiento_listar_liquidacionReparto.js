$(document).ready(function (){
    loaderClose();  
    ax.setSuccess("exitoOpcionesLiquidacionReparto");                        
});

function exitoOpcionesLiquidacionReparto(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'ExportarLiquidacionRepartopdf':
                loaderClose();
                //console.log(response.data);
                window.open(URL_BASE + 'pdf2.php?url_pdf=' + URL_BASE + "vistas/com/movimiento/documentos/" + response.data.nombre_pdf+'.pdf' + '&nombre_pdf=' + response.data.nombre_pdf);
                // setTimeout(function () {
                //     ax.setAccion("eliminarPDF");
                //     ax.addParamTmp("url", response.data.url);
                //     ax.consumir();
            
                // }, 500);
                break;
        }
    }
}


function BuscarResultadoReparto()
{
    let parametroserie=  $('#seriemanfreparto').val();    // alert("Parametros :"+parametrofecha1);
    let parametronumero=  $('#numeromanfreparto').val();  
    // alert("fecha ini: "+parametrofecha1); 
    // alert("fecha fin: "+parametrofecha2); // alert("Parametros :"+parametrofecha2);
    //cpe_id, Modalidadpedido,modalidad_id, total_pquetes, cpe,serie_numero_pedido,estado_pedido,totalpaquetesxcpe,cast(totalpagado as money) pago,cast(totalcomprobante as money) TotalCPE 
    ax.setAccion("BuscarLiquidacionReparto");
    ax.addParamTmp("Repartoserie", parametroserie);
    ax.addParamTmp("Repartonumero", parametronumero);
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
                        { "data": "cpe", "sClass": "alignCenter" },
                        { "data": "serie_numero_pedido", "sClass": "alignCenter" },
                        { "data": "Modalidadpedido", "sClass": "alignCenter" },
                        { "data": "estado_pedido", "sClass": "alignCenter" },
                        { "data": "totalpaquetesxcpe", "sClass": "alignCenter" },
                        { "data": "pago", "sClass": "alignCenter" },
                        { "data": "TotalCPE", "sClass": "alignCenter" },
                        ],
            
            destroy: true
    });
        loaderClose();
    
}
//

function ExportarLiquidacionRepartopdf()
{
    
    let parametroserie=  $('#seriemanfreparto').val();    // alert("Parametros :"+parametrofecha1);
    let parametronumero=  $('#numeromanfreparto').val(); 
    ax.setAccion("ExportarLiquidacionRepartopdf"); 
    ax.addParamTmp("Repartoseriepdf", parametroserie);
    ax.addParamTmp("Repartonumeropdf", parametronumero);
    ax.consumir();
}

 