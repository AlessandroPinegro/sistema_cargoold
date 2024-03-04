var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var dataTotal;
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
    ax.setSuccess("onResponseVentasIgvVentas");
    obtenerConfiguracionesInicialesVentasIgvVentas();
    modificarAnchoTabla('dataTableVentasIgvVentas');
});

function onResponseVentasIgvVentas(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesVentasIgvVentas':
                onResponseObtenerConfiguracionesInicialesVentasIgvVentas(response.data);
                break;
            case 'obtenerCantidadesTotalesVentasIgvVentas':
                dataTotal = response.data[0];
                getDataTableVentasIgvVentas();
                break;
            case 'obtenerReporteVentasIgvVentasExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteVentasIgvVentasExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesVentasIgvVentas()
{
    ax.setAccion("obtenerConfiguracionesInicialesVentasIgvVentas");
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesVentasIgvVentas(data) {
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

var valoresBusquedaVentasIgvVentas = [{empresa: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaVentasIgvVentas()
{
    var tiendaId = $('#cboTienda').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaVentasIgvVentas[0].empresa = tiendaId;
    valoresBusquedaVentasIgvVentas[0].documentoTipo = documentoTipoId;
    valoresBusquedaVentasIgvVentas[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaVentasIgvVentas[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableVentasIgvVentas();

}
function buscarVentasIgvVentas(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesVentasIgvVentas();
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
    cargarDatosBusquedaVentasIgvVentas();
    
    if (!isEmpty(valoresBusquedaVentasIgvVentas[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasIgvVentas[0].empresa))
    {
        cadena += StringNegrita("Tienda: ");

        cadena += select2.obtenerTextMultiple('cboTienda');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasIgvVentas[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaVentasIgvVentas[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaVentasIgvVentas[0].fechaEmisionDesde + " - " + valoresBusquedaVentasIgvVentas[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableVentasIgvVentas() {
    color = '';
    ax.setAccion("obtenerDataVentasIgvVentas");
    ax.addParamTmp("criterios", valoresBusquedaVentasIgvVentas);
    $('#dataTableVentasIgvVentas').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],  
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
            {"data": "igv_soles", "class": "alignRight"},
            {"data": "igv_dolares", "class": "alignRight"}

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
                "targets": [7,8]
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
            $(api.column(7).footer()).html(
                    'S/. ' + (formatearNumero(dataTotal.total_igv_soles))
                    );
            $(api.column(8).footer()).html(
                    '$ ' + (formatearNumero(dataTotal.total_igv_dolares))
                    );
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
        buscarVentasIgvVentas();
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

function obtenerCantidadesTotalesVentasIgvVentas()
{
    ax.setAccion("obtenerCantidadesTotalesVentasIgvVentas");
    ax.addParamTmp("criterios", valoresBusquedaVentasIgvVentas);
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

function exportarReporteVentasIgvVentas()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaVentasIgvVentas();
    ax.setAccion("obtenerReporteVentasIgvVentasExcel");
    ax.addParamTmp("criterios", valoresBusquedaVentasIgvVentas);
    ax.consumir();
}