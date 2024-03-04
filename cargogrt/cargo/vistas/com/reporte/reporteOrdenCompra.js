var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var total;
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
    ax.setSuccess("onResponseReporteOrdenCompra");
    obtenerConfiguracionesInicialesReporteOrdenCompra();
    modificarAnchoTabla('dataTableReporteOrdenCompra');
});

function onResponseReporteOrdenCompra(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteOrdenCompra':
                onResponseObtenerConfiguracionesInicialesReporteOrdenCompra(response.data);
                break;
            case 'obtenerCantidadesTotalesReporteOrdenCompra':
                if (response.data.total === null)
                {
                    response.data.total = 0;
                }
                
                total = response.data.total;
                getDataTableReporteOrdenCompra();
                break;
            case 'obtenerReporteReporteOrdenCompraExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteOrdenCompraExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteOrdenCompra()
{
    ax.setAccion("obtenerConfiguracionesInicialesReporteOrdenCompra");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesReporteOrdenCompra(data) {

    var string = '<option selected value="-1">Seleccionar un proveedor</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaProveedor').append(string);
        select2.asignarValor('cboPersonaProveedor', "-1");
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

var valoresBusquedaReporteOrdenCompra = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaReporteOrdenCompra()
{
    var personaId = select2.obtenerValor('cboPersonaProveedor');
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaReporteOrdenCompra[0].persona = personaId;
    valoresBusquedaReporteOrdenCompra[0].empresa = commonVars.empresa;
    valoresBusquedaReporteOrdenCompra[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaReporteOrdenCompra[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableReporteOrdenCompra();

}
function buscarReporteOrdenCompra(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesReporteOrdenCompra();
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
    cargarDatosBusquedaReporteOrdenCompra();
    
    if (select2.obtenerValor('cboPersonaProveedor')!=-1)
    {
        cadena += StringNegrita("Proveedor: ");

        cadena += select2.obtenerText('cboPersonaProveedor');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteOrdenCompra[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaReporteOrdenCompra[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteOrdenCompra[0].fechaEmisionDesde + " - " + valoresBusquedaReporteOrdenCompra[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableReporteOrdenCompra() {
    color = '';
    ax.setAccion("obtenerDataReporteOrdenCompra");
    ax.addParamTmp("criterios", valoresBusquedaReporteOrdenCompra);
    $('#dataTableReporteOrdenCompra').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "order": [[0, "desc"]],    
        "scrollX": true,
        "autoWidth": true,
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Total
            {"data": "fecha_tentativa"},
            {"data": "fecha_emision"},
//            {"data": "usuario_nombre"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "bien_descripcion"},
            {"data": "cantidad"},
            {"data": "unidad_medida_descripcion"},
//            {"data": "total", "class": "alignRight"}

        ],
        columnDefs: [
//            {
//                "render": function (data, type, row) {
//                    return parseFloat(data).formatMoney(2, '.', ',');
//                },
//                "targets": 7
//            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": [0,1]
            }
        ],
        destroy: true
//        ,footerCallback: function (row, data, start, end, display) {
//            var api = this.api(), data;
//            $(api.column(9).footer()).html(
//                    'S/. ' + (formatearNumero(total))
//                    );
//        }
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
        buscarReporteOrdenCompra();
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

function obtenerCantidadesTotalesReporteOrdenCompra()
{
    ax.setAccion("obtenerCantidadesTotalesReporteOrdenCompra");
    ax.addParamTmp("criterios", valoresBusquedaReporteOrdenCompra);
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

function exportarReporteReporteOrdenCompra()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaReporteOrdenCompra();
    ax.setAccion("obtenerReporteReporteOrdenCompraExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteOrdenCompra);
    ax.consumir();
}