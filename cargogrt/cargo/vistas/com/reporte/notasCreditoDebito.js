var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var totalReporte;
var totalUtilizado;
var total_cantidad;
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
    ax.setSuccess("onResponseNotasCreditoDebito");
    obtenerConfiguracionesInicialesNotasCreditoDebito();
    modificarAnchoTabla('dataTableNotasCreditoDebito');
});

function onResponseNotasCreditoDebito(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesNotasCreditoDebito':
                onResponseObtenerConfiguracionesInicialesNotasCreditoDebito(response.data);
                break;
            case 'obtenerCantidadesTotalesNotasCreditoDebito':
                totalReporte = response.data;
                getDataTableNotasCreditoDebito();
                break;
            case 'obtenerReporteNotasCreditoDebitoExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteNotasCreditoDebitoExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesNotasCreditoDebito()
{
    ax.setAccion("obtenerConfiguracionesInicialesNotasCreditoDebito");
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesNotasCreditoDebito(data) {
    //console.log(data);
    //var string = '<option selected value="-1">Seleccionar una tienda</option>';
    var string ='';
    if (!isEmpty(data.empresa)) {
        $.each(data.empresa, function (indexEmpresa, itemEmpresa) {
            string += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
        });
        $('#cboTienda').append(string);
        //select2.asignarValor('cboTienda', "-1");
    }
    
    if (!isEmpty(data.documento_tipo)) {
//        var string = '<option selected value="-1">Seleccionar un</option>';
        var stringDocumento = '';
        $.each(data.documento_tipo, function (indexDocumento, itemDocumneto) {
            stringDocumento += '<option value="' + itemDocumneto.id + '">' + itemDocumneto.descripcion + '</option>';
        });
        $('#cboTipoDocumentoMP').append(stringDocumento);
//        select2.asignarValor('cboTipoDocumentoMP', "-1");
    }    
    
    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
    {
        $('#inicioFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
        {
            $('#finFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
        }
    }
                    
    loaderClose();
}

var valoresBusquedaNotasCreditoDebito = [{empresa: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaNotasCreditoDebito()
{
    var tiendaId = $('#cboTienda').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaNotasCreditoDebito[0].empresa = tiendaId;
    valoresBusquedaNotasCreditoDebito[0].documentoTipo = documentoTipoId;
    valoresBusquedaNotasCreditoDebito[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaNotasCreditoDebito[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableNotasCreditoDebito();

}
function buscarNotasCreditoDebito(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesNotasCreditoDebito();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;
    
    if (colapsa === 1)
        colapsarBuscador();
    
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusquedaNotasCreditoDebito();
    
    if (!isEmpty(valoresBusquedaNotasCreditoDebito[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaNotasCreditoDebito[0].empresa))
    {
        cadena += StringNegrita("Tienda: ");

        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaNotasCreditoDebito[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaNotasCreditoDebito[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaNotasCreditoDebito[0].fechaEmisionDesde + " - " + valoresBusquedaNotasCreditoDebito[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableNotasCreditoDebito() {
    color = '';
    ax.setAccion("obtenerDataNotasCreditoDebito");
    ax.addParamTmp("criterios", valoresBusquedaNotasCreditoDebito);
    $('#dataTableNotasCreditoDebito').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Total
            {"data": "fecha_creacion"},
            {"data": "fecha_emision"},
            {"data": "razon_social"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "moneda_descripcion"},
            {"data": "total_soles", "class": "alignRight" },
//            {"data": "pagado_soles", "class": "alignRight" },
            {"data": "total_dolares", "class": "alignRight" },
//            {"data": "pagado_dolares", "class": "alignRight" },
//            {"data": "estado_nota"}

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
                "targets": [8,9]
            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": [0,1]
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(8).footer()).html(
                    'S/. ' + (formatearNumero(totalReporte.total_soles_reporte))
                    );
//            $(api.column(9).footer()).html(
//                    'S/. ' + (formatearNumero(totalReporte.pagado_soles_reporte))
//                    );
            $(api.column(9).footer()).html(
                    '$ ' + (formatearNumero(totalReporte.total_dolares_reporte))
                    );
//            $(api.column(11).footer()).html(
//                    '$ ' + (formatearNumero(totalReporte.pagado_dolares_reporte))
//                    );
        }
    });
    loaderClose();
}

var actualizandoBusqueda = false;
function loaderBuscarVentas()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarNotasCreditoDebito();
    }
}
function cerrarPopover()
{
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
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


    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
}

function obtenerCantidadesTotalesNotasCreditoDebito()
{
    ax.setAccion("obtenerCantidadesTotalesNotasCreditoDebito");
    ax.addParamTmp("criterios", valoresBusquedaNotasCreditoDebito);
    ax.consumir();
}

//function imprimir(muestra)
//{
//    var ficha = document.getElementById(muestra);
//    var ventimp = window.open(' ', 'popimpr');
//    ventimp.document.write(ficha.innerHTML);
//    ventimp.document.close();
//    ventimp.print();
//    ventimp.close();
//}

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

function exportarReporteNotasCreditoDebito()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaNotasCreditoDebito();
    ax.setAccion("obtenerReporteNotasCreditoDebitoExcel");
    ax.addParamTmp("criterios", valoresBusquedaNotasCreditoDebito);
    ax.consumir();
}