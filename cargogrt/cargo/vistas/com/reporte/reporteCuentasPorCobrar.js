var estadoTolltip = 0;
var banderaBuscar = 0;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseReporteDeuda");
    obtenerConfiguracionesInicialesReporteDeuda();
});

function onResponseReporteDeuda(response) {
    breakFunction();
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesDeuda':
                onResponseObtenerConfiguracionesInicialesReporteDeuda(response.data);

                break;
            case 'obtenerDetalleCobro':
                onResponseDetalleCobro(response.data);
                break;
            case 'exportarReporteVentas':
                onResponseExportarReporteVentas(response.data);
                loaderClose();
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

        }
    }
}

function obtenerConfiguracionesInicialesReporteDeuda()
{
    ax.setAccion("obtenerConfiguracionesInicialesDeuda");
//    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesReporteDeuda(data) {

    var string = '<option selected value="-1">Seleccionar un cliente</option>';
    if (!isEmpty(data)) {

        $.each(data, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaDeuda').append(string);
        select2.asignarValor('cboPersonaDeuda', "-1");
    }
    loaderClose();
}

var valoresBusquedaReporteDeuda = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var PersonaId = $('#cboPersonaDeuda').val();
    var fechaVencimientoInicio = $('#inicioFechaVencimiento').val();
    var fechaVencimientoFin = $('#finFechaVencimiento').val();
    var mostrar = null;
    if ($('#chk_mostrar').is(':checked')) {
        mostrar = 1
    } else
    {
        mostrar = 0;
    }

    valoresBusquedaReporteDeuda[0].mostrar = mostrar;
    valoresBusquedaReporteDeuda[0].persona = PersonaId;
    valoresBusquedaReporteDeuda[0].empresa = commonVars.empresa;
    valoresBusquedaReporteDeuda[0].fechaVencimientoDesde = fechaVencimientoInicio;
    valoresBusquedaReporteDeuda[0].fechaVencimientoHasta = fechaVencimientoFin;
//    valoresBusquedaReporteDeuda[0].fechaVencimiento = objetoFecha(fechaVencimientoInicio, fechaVencimientoFin);
    getDataTable();

}
function buscar(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
//    cargarDatosBusqueda();

    if (colapsa === 1)
        colapsarBuscador();
}
function exportarReporteVentas(){
    loaderShow();
//    cargarDatosBusqueda();
    ax.setAccion("exportarReporteVentas");
//    ax.addParamTmp("persona", valoresBusquedaReporteDeuda[0].persona);
    var idPersona = $('#cboPersonaDeuda').select2("val");
    ax.addParamTmp("persona", (idPersona == "-1" || idPersona == null) ? null : idPersona);
   // ax.addParamTmp("fecha", valoresBusquedaReporteDeuda[0].fechaVencimientoHasta);
    ax.consumir();

}
function onResponseExportarReporteVentas(data){
    window.open(URL_BASE + data);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaReporteDeuda[0].persona))
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersonaDeuda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteDeuda[0].fechaVencimientoHasta))
    {
        cadena += StringNegrita("Fecha: ");
        cadena += valoresBusquedaReporteDeuda[0].fechaVencimientoHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTable() {
    color = '';
    ax.setAccion("obtenerDataDeuda");
    ax.addParamTmp("criterios", valoresBusquedaReporteDeuda);
    $('#dataTableDeuda').dataTable({
        "processing": true,
        dom: 'Bfrtip',
        language: {
            buttons: {
                colvis: 'Columnas visibles'
            }
        },
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        buttons: [{
                extend: 'print',
                text: 'Imprimir',
//                            autoPrint: false,
                title: '<h3 style="text-align:center;">REPORTE DE CUENTAS POR COBRAR </h3>',
                message: '',
                exportOptions: {
                    columns: ':visible',
                    page: 'current'
                },
                customize: function (win) {
                    $(win.document.body)
                            .css('font-size', '7pt')
                            ;

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');

                    $(win.document.body).find('table').find('td:nth-child(1)').addClass('alignCenter');
                    $(win.document.body).find('table').find('td:nth-child(2)').addClass('alignCenter');
                    $(win.document.body).find('table').find('td:nth-child(3)').addClass('alignLeft');
                    $(win.document.body).find('table').find('td:nth-child(4)').addClass('alignLeft');
                    $(win.document.body).find('table').find('td:nth-child(5)').addClass('alignLeft');
                    $(win.document.body).find('table').find('td:nth-child(6)').addClass('alignCenter');
                    $(win.document.body).find('table').find('td:nth-child(7)').addClass('alignCenter');
                    $(win.document.body).find('table').find('td:nth-child(8)').addClass('alignLeft');                    
                    $(win.document.body).find('table').find('td:nth-child(10)').addClass('alignRight');
                    $(win.document.body).find('table').find('td:nth-child(11)').addClass('alignRight');
                    $(win.document.body).find('table').find('td:nth-child(11)').addClass('alignRight');
                    $(win.document.body).find('table').find('td:nth-child(12)').addClass('alignRight');
                    $(win.document.body).find('table').find('td:nth-child(13)').addClass('alignRight');
                    $(win.document.body).find('table').find('td:nth-child(14)').addClass('alignRight');
                    $(win.document.body).find('table').find('th').addClass('alignCenter');
                    
                    //espaciado entre filas
                    $(win.document.body).find('table').find('th').css('padding', '2px');
                    $(win.document.body).find('table').find('td').css('padding', '2px');
                }
            },
            'colvis'
                    //                'columnsToggle'
        ],
        "scrollCollapse": true,
        "columns": [
            {data: "semaforo",
                render: function (data, type, row) {

                    if (type === 'display') {
                        if (row.semaforo > 3)
                        {
                            //rojo
                            color = '#01DF01';
                        } else {
                            if (row.semaforo >= 0 && row.semaforo <= 3)
                            {
                                //ambar
                                color = '#FFC200';
                            } else
                            {
                                //verde
                                color = '#DF0101';
                            }
                        }
                        // return '<a href="#" onClick = "agregarDocumentoPago(' + row.semaforo + ');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="fa fa-flag" style = "color:' + color + ';"></i><b></a>&nbsp;\n';
                        return '<i class="fa fa-flag" style = "color:' + color + ';"></i>';
                    }
                    return data;
                },
                "orderable": true,
                "class": "align",
                "width": "10px"
            },
            {"data": "fecha_emision", "width": "50px"},
            {"data": "fecha_vencimiento", "width": "50px"},
            {"data": "documento_tipo_descripcion", "width": "100px"},
            {"data": "persona_nombre_completo", "width": "255px"},
            {"data": "serie", "width": "20px"},
            {"data": "numero", "width": "60px"},
            {"data": "descripcion", "width": "250px"},
            {"data": "moneda_descripcion", "width": "50px"},
            {"data": "pagado_soles", "class": "alignRight", "width": "50px"},
            {"data": "deuda_soles", "class": "alignRight", "width": "50px"},
            {"data": "pagado_dolares", "class": "alignRight", "width": "50px"},
            {"data": "deuda_dolares", "class": "alignRight", "width": "50px"},
            {"data": "total", "class": "alignRight", "width": "50px"},
            {data: "codigo",
                render: function (data, type, row) {

                    if (type === 'display') {

//                        return '<a href="#" onClick = "agregarDocumentoPago(' + row.semaforo + ');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="fa fa-flag" style = "color:' + color + ';"></i><b></a>&nbsp;\n';
                        return "<a href='#' onclick='visualizarCobro(" + row.documento_id + ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
                    }
                    return data;
                },
//                "orderable": true,
                "class": "alignCenter",
                "width": "50px"
            },
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    if (parseFloat(data).formatMoney(2, '.', ',') == '0.00') {
                        return '-';
                    } else {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": [9, 10, 11, 12, 13]
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": [1, 2]
            }
        ],
//        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
//            console.log(data);
            if (!isEmpty(data))
            {
                var api = this.api(), data;

                $(api.column(9).footer()).html(
                        'S/. ' + (parseFloat(data[0]["pagado_soles_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(10).footer()).html(
                        'S/. ' + (parseFloat(data[0]["deuda_soles_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(11).footer()).html(
                        '$ ' + (parseFloat(data[0]["pagado_dolares_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(12).footer()).html(
                        '$ ' + (parseFloat(data[0]["deuda_dolares_reporte"])).formatMoney(2, '.', ',')
                        );
            }

        }
    });
    loaderClose();
}

function loaderBuscarDeuda()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscar();
    }
}
function cerrarPopover()
{
    if (banderaBuscar == 1)

    {
        if (estadoTolltip == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        } else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    } else
    {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltip = (estadoTolltip == 0) ? 1 : 0;
}
function visualizarCobro(id)
{
    loaderShow();
    ax.setAccion("obtenerDetalleCobro");
    ax.addParamTmp("documentoId", id);
    ax.consumir();
}
function onResponseDetalleCobro(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
//        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';
        var stringTituloStock = '<strong> ' + data[0]['documento_tipo_descripcion'] + '</strong>';

        $('#datatableDetalleCobro').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "fecha"},
                {"data": "documento_pago_descripcion"},
                {"data": "numero"},
//                {"data": "fecha_vencimiento", "sClass": "alignRight"},
                {"data": "moneda_descripcion"},
                {"data": "importe", "sClass": "alignRight"},
//                {"data": "discrepancia", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 4
                },
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                    },
                    "targets": 0
                },
            ],
            "destroy": true
//           ,footerCallback: function (row, data, start, end, display) {
//            if (!isEmpty(data))
//            {
//                var api = this.api(), data;
//                var intVal = function (i) {
//                    return typeof i === 'string' ?
//                            i.replace(/[\$,]/g, '') * 1 :
//                            typeof i === 'number' ?
//                            i : 0;
//                };
//                    
//                if(api.column(4).data().length>0)
//                {
//                    totalDetalle = api
//                        .column(4)
//                        .data()
//                        .reduce(function (a, b) {
//                            return intVal(a) + intVal(b);
//                        });
//                }else
//                {
//                    totalDetalle = 0;
//                }
//
//                $(api.column(4).footer()).html(
//                        'S/. ' + (parseFloat(totalDetalle)).formatMoney(2, '.', ',')
//                        );
//            }

//        }
        });
        $('.modal-title').empty();

        $('.modal-title').append(stringTituloStock);

        $("#modal_detalle_cobros").modal("show");

    } else
    {
        var table = $('#datatableDetalleCobro').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documento.")
    }
    loaderClose();
}

var actualizandoBusqueda = false;
function colapsarBuscador() {
    if (actualizandoBusqueda) {
        actualizandoBusqueda = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').removeAttr('height', "0px");
        $('#bg-info').addClass('in');
    }
}