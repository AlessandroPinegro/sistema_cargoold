var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var dataTotal;
var total_cantidad;
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
//    cargarTitulo("titulo", "");
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseVentasPorVendedor");
    obtenerConfiguracionesInicialesVentasPorVendedor();
    modificarAnchoTabla('dataTableVentasPorVendedor');
});

function onResponseVentasPorVendedor(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesVentasPorVendedor':
                onResponseObtenerConfiguracionesInicialesVentasPorVendedor(response.data);
                break;
            case 'obtenerCantidadesTotalesVentasPorVendedor':
                dataTotal = response.data[0];
                getDataTableVentasPorVendedor();
                break;
            case 'obtenerReporteVentasPorVendedorExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'obtenerBienTipoHijo':
                onResponseObtenerBienTipoHijo(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteVentasPorVendedorExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesVentasPorVendedor()
{
    ax.setAccion("obtenerConfiguracionesInicialesVentasPorVendedor");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesVentasPorVendedor(data) {

    var string = '<option selected value="-1">Seleccionar un vendedor</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaVendedor').append(string);
        select2.asignarValor('cboPersonaVendedor', "-1");
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
    
    var string ='';
    if (!isEmpty(data.empresa)) {
        $.each(data.empresa, function (indexEmpresa, itemEmpresa) {
            string += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
        });
        $('#cboEmpresa').append(string);
        //select2.asignarValor('cboTienda', "-1");
    }
    
    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
    {
        $('#inicioFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
        {
            $('#finFechaEmisionMP').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
        }
    }
                    
    if (!isEmpty(data.bien_tipo)) {
        select2.cargar("cboBienTipoPadre", data.bien_tipo, "id", ["codigo","descripcion"]);
        select2.asignarValor("cboBienTipoPadre",null);
    }                    
    loaderClose();
}

var valoresBusquedaVentasPorVendedor = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaVentasPorVendedor()
{
    var personaId = $('#cboPersonaVendedor').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();
    var empresaId = $('#cboEmpresa').val();
    var bienTipo = $('#cboBienTipo').val();
    var bienTipoPadre = $('#cboBienTipoPadre').val();

    valoresBusquedaVentasPorVendedor[0].persona = personaId;
    valoresBusquedaVentasPorVendedor[0].documentoTipo = documentoTipoId;
    valoresBusquedaVentasPorVendedor[0].empresa = empresaId;
    valoresBusquedaVentasPorVendedor[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaVentasPorVendedor[0].fechaEmisionHasta = fechaEmisionFin;
    valoresBusquedaVentasPorVendedor[0].bienTipo = bienTipo;
    valoresBusquedaVentasPorVendedor[0].bienTipoPadre = bienTipoPadre;
//    getDataTableVentasPorVendedor();

}
function buscarVentasPorVendedor(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesVentasPorVendedor();
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
    cargarDatosBusquedaVentasPorVendedor();
    
    if (!isEmpty(valoresBusquedaVentasPorVendedor[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorVendedor[0].empresa))
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (select2.obtenerValor('cboPersonaVendedor')!=-1)
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersonaVendedor');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorVendedor[0].bienTipo))
    {
        cadena += StringNegrita("Grupo de producto: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaVentasPorVendedor[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaVentasPorVendedor[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaVentasPorVendedor[0].fechaEmisionDesde + " - " + valoresBusquedaVentasPorVendedor[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableVentasPorVendedor() {
    color = '';
    ax.setAccion("obtenerDataVentasPorVendedor");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorVendedor);
    $('#dataTableVentasPorVendedor').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[6, "desc"]],  
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//Vendedor G.P. Principal G.P. Secundario F. Emisión Tipo documento Cliente S|N	Total S/.	Total $
            {"data": "vendedor_nombre"},
            {"data": "bien_tipo_padre_descripcion"},
            {"data": "bien_tipo_descripcion"},
            {"data": "fecha_emision"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie_numero"},
            {"data": "total_soles", "class": "alignRight"},
            {"data": "total_dolares", "class": "alignRight"},

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
                "targets": 3
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(7).footer()).html(
                    'S/. ' + (formatearNumero(dataTotal.total_soles))
                    );
            $(api.column(8).footer()).html(
                    '$ ' + (formatearNumero(dataTotal.total_dolares))
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
        buscarVentasPorVendedor();
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

function obtenerCantidadesTotalesVentasPorVendedor()
{
    ax.setAccion("obtenerCantidadesTotalesVentasPorVendedor");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorVendedor);
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

function exportarReporteVentasPorVendedor()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaVentasPorVendedor();
    ax.setAccion("obtenerReporteVentasPorVendedorExcel");
    ax.addParamTmp("criterios", valoresBusquedaVentasPorVendedor);
    ax.consumir();
}

function obtenerBienTipoHijo(){    
    var bienTipoPadreId = $('#cboBienTipoPadre').val();
    
    loaderShow();
    ax.setAccion("obtenerBienTipoHijo");
    ax.addParamTmp("bienTipoPadreId", bienTipoPadreId);
    ax.consumir();
}

function onResponseObtenerBienTipoHijo(data){
    select2.cargar("cboBienTipo", data, "id", ["codigo","descripcion"]);
    select2.asignarValor("cboBienTipo",null);
}						