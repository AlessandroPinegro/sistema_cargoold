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
    ax.setSuccess("onResponseReporteDeudaGeneral");
    obtenerConfiguracionesInicialesReporteDeudaGeneral();
});

function onResponseReporteDeudaGeneral(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesDeudaGeneral':
                onResponseObtenerConfiguracionesInicialesReporteDeudaGeneral(response.data);
                break;
            case 'obtenerDetalleCobro':
                onResponseDetalleCobroGeneral(response.data);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

        }
    }
}

function obtenerConfiguracionesInicialesReporteDeudaGeneral()
{
    ax.setAccion("obtenerConfiguracionesInicialesDeudaGeneral");
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesReporteDeudaGeneral(data) {

    var string = '<option selected value="-1">Seleccionar un cliente</option>';
    if (!isEmpty(data.persona)) {

        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersona').append(string);
        select2.asignarValor('cboPersona', "-1");
    }
    var stringEmpresa = '<option selected value="-1">Seleccionar una empresa</option>';
    if (!isEmpty(data.empresa)) {

        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");

//        $.each(data.empresa, function (indexEmpresa, itemEmpresa) {
//            stringEmpresa += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
//        });
//        $('#cboEmpresa').append(stringEmpresa);
    }
    loaderClose();
}

var valoresBusquedaReporteDeudaGeneral = [{persona: "", fecha: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var PersonaId = $('#cboPersona').val();
    var fecha = $('#datpickerFecha').val();
    var empresaId = $('#cboEmpresa').val();
    
    var mostrar=null;
    if ($('#chk_mostrar').is(':checked')) {
        mostrar = 1
    } else
    {
        mostrar = 0;
    }
    
    valoresBusquedaReporteDeudaGeneral[0].mostrar = mostrar;
    valoresBusquedaReporteDeudaGeneral[0].persona = PersonaId;
    valoresBusquedaReporteDeudaGeneral[0].empresa = empresaId;
    valoresBusquedaReporteDeudaGeneral[0].fecha = fecha;
//    valoresBusquedaReporteDeudaGeneral[0].fechaVencimiento = objetoFecha(fechaVencimientoInicio, fecha);
    getDataTable();
}
function buscarDeudaGeneral(colapsa)
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

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    if (valoresBusquedaReporteDeudaGeneral[0].persona != -1)
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }
    var valorEmpresa = select2.obtenerTextMultiple('cboEmpresa');
    if (valorEmpresa != null)
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteDeudaGeneral[0].fecha))
    {
        cadena += StringNegrita("Fecha: ");
        cadena += valoresBusquedaReporteDeudaGeneral[0].fecha;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTable() {
    color = '';
    ax.setAccion("obtenerDataDeudaGeneral");
    ax.addParamTmp("criterios", valoresBusquedaReporteDeudaGeneral);
    $('#dataTableDeudaGeneral').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        
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
//                        return '<a href="#" onClick = "agregarDocumentoPago(' + row.semaforo + ');" name = "btnpd_' + row.documento_id + '" id="btnpd_' + row.documento_id + '"><b><i class="fa fa-flag" style = "color:' + color + ';"></i><b></a>&nbsp;\n';
                        return '<i class="fa fa-flag" style = "color:' + color + ';"></i>';
                    }
                    return data;
                },
                "orderable": true,
                "class": "align"
            },
            {"data": "fecha_emision", "width": "30px"},
            {"data": "fecha_vencimiento", "width": "30px"},
            {"data": "documento_tipo_descripcion", "width": "150px"},
            {"data": "razon_social", "width": "120px"},
            {"data": "persona_nombre_completo", "width": "250px"},
            {"data": "serie", "width": "25px"},
            {"data": "numero", "width": "50px"},
            {"data": "descripcion", "width": "80px"},
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
                        return "<a href='#' onclick='visualizarCobroGeneral(" + row.documento_id + ")' title='Visualizar'><b><i class='fa fa-eye' style='color:#1ca8dd;'></i><b></a>&nbsp;\n";
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
                    if(parseFloat(data).formatMoney(2, '.', ',')=='0.00'){
                        return '-';
                    }else{
                        return parseFloat(data).formatMoney(2, '.', ',');
                    }
                },
                "targets": [10,11,12,13,14]
            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": [1,2]
            }
        ],
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
//            console.log(data);
            if (!isEmpty(data))
            {
                var api = this.api(), data;
                
                $(api.column(10).footer()).html(
                        'S/. ' + (parseFloat(data[0]["pagado_soles_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(11).footer()).html(
                        'S/. ' + (parseFloat(data[0]["deuda_soles_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(12).footer()).html(
                        '$ ' + (parseFloat(data[0]["pagado_dolares_reporte"])).formatMoney(2, '.', ',')
                        );
                $(api.column(13).footer()).html(
                        '$ ' + (parseFloat(data[0]["deuda_dolares_reporte"])).formatMoney(2, '.', ',')
                        );
            }

        }
    });
    loaderClose();
}

function loaderBuscarDeudaGeneral()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarDeudaGeneral();
    }

}
function cerrarPopover()
{
    if (banderaBuscar == 1)

    {
        if (estadoTolltip == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        }
        else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else
    {
        $('[data-toggle="popover"]').popover('hide');
    }


    estadoTolltip = (estadoTolltip == 0) ? 1 : 0;
}

function visualizarCobroGeneral(id)
{
    loaderShow();
    ax.setAccion("obtenerDetalleCobro");
    ax.addParamTmp("documentoId", id);
    ax.consumir();
}

function onResponseDetalleCobroGeneral(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
//        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';
        var stringTituloStock = '<strong> ' + data[0]['documento_tipo_descripcion'] + '</strong>';

        $('#datatableDetalleCobroGeneral').dataTable({
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
//            ,footerCallback: function (row, data, start, end, display) {
//                if (!isEmpty(data))
//                {
//                    var api = this.api(), data;
//                    var intVal = function (i) {
//                        return typeof i === 'string' ?
//                                i.replace(/[\$,]/g, '') * 1 :
//                                typeof i === 'number' ?
//                                i : 0;
//                    };
//
//                    if (api.column(3).data().length > 0)
//                    {
//                        totalDetalle = api
//                                .column(3)
//                                .data()
//                                .reduce(function (a, b) {
//                                    return intVal(a) + intVal(b);
//                                });
//                    } else
//                    {
//                        totalDetalle = 0;
//                    }
//
//                    $(api.column(3).footer()).html(
//                            'S/. ' + (parseFloat(totalDetalle)).formatMoney(2, '.', ',')
//                            );
//                }
//
//            }
        });
        $('.modal-title').empty();

        $('.modal-title').append(stringTituloStock);

        $("#modal_detalle_cobros_general").modal("show");

    }
    else
    {
        var table = $('#datatableDetalleCobroGeneral').DataTable();
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