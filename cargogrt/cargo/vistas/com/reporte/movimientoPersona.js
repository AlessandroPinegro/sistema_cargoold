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
    ax.setSuccess("onResponseMovimientoPersona");
    obtenerConfiguracionesInicialesMovimientoPersona();
    modificarAnchoTabla('dataTableMovimientoPersona');
});

function onResponseMovimientoPersona(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesMovimientoPersona':
                onResponseObtenerConfiguracionesInicialesMovimientoPersona(response.data);
                break;
            case 'obtenerCantidadesTotalesMovimientoPersona':
                if (response.data.total === null)
                {
                    response.data.total = 0;
                }
                if (response.data.cantidad_total === null)
                {
                    response.data.cantidad_total = 0;
                }
                
                total = response.data.total;
                cantidad_total = response.data.cantidad_total;
                getDataTableMovimientoPersona();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

        }
    }
}

function obtenerConfiguracionesInicialesMovimientoPersona()
{
    ax.setAccion("obtenerConfiguracionesInicialesMovimientoPersona");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesMovimientoPersona(data) {

    var string = '<option selected value="-1">Seleccionar una persona</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaMP').append(string);
        select2.asignarValor('cboPersonaMP', "-1");
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
    
    if (!isEmpty(data.bien)) {
        var string = '<option selected value="-1">Seleccionar un producto</option>';
        $.each(data.bien, function (indexBien, itemBien) {
            string += '<option value="' + itemBien.id + '">' + itemBien.descripcion +' | '+itemBien.bien_tipo_descripcion + '</option>';
        });
        $('#cboBienMP').append(string);
        select2.asignarValor('cboBienMP', "-1");
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

var valoresBusquedaMovimientoPersona = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaMovimientoPersona()
{
    var personaId = $('#cboPersonaMP').val();
    var bienId = $('#cboBienMP').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaMovimientoPersona[0].persona = personaId;
    valoresBusquedaMovimientoPersona[0].bien = bienId;
    valoresBusquedaMovimientoPersona[0].documentoTipo = documentoTipoId;
    valoresBusquedaMovimientoPersona[0].empresa = commonVars.empresa;
    valoresBusquedaMovimientoPersona[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaMovimientoPersona[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableMovimientoPersona();

}
function buscarMovimientoPersona(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesMovimientoPersona();
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
    cargarDatosBusquedaMovimientoPersona();
    
    if (!isEmpty(valoresBusquedaMovimientoPersona[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (select2.obtenerValor('cboPersonaMP')!=-1)
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersonaMP');
        cadena += "<br>";
    }
    if (select2.obtenerValor('cboBienMP')!=-1)
    {
        cadena += StringNegrita("Producto: ");

        cadena += select2.obtenerText('cboBienMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaMovimientoPersona[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaMovimientoPersona[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaMovimientoPersona[0].fechaEmisionDesde + " - " + valoresBusquedaMovimientoPersona[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableMovimientoPersona() {
    color = '';
    ax.setAccion("obtenerDataMovimientoPersona");
    ax.addParamTmp("criterios", valoresBusquedaMovimientoPersona);
    $('#dataTableMovimientoPersona').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],  
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
            
            {"data": "fecha_emision"},
//            {"data": "fecha_vencimiento", "width": "50px"},
            {"data": "bien_descripcion"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "cantidad", "class": "alignRight"},
            {"data": "total", "class": "alignRight"}

        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 6
            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": 0
            },
//            {
//                "render": function (data, type, row) {
//                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
//                },
//                "targets": 1
//            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 7
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(6).footer()).html(
                    (formatearNumero(cantidad_total))
                    );
            $(api.column(7).footer()).html(
                    'S/. ' + (formatearNumero(total))
                    );
        }
    });
    loaderClose();
}

var actualizandoBusqueda = false;
function loaderBuscarDeuda()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarMovimientoPersona();
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

function obtenerCantidadesTotalesMovimientoPersona()
{
    ax.setAccion("obtenerCantidadesTotalesMovimientoPersona");
    ax.addParamTmp("criterios", valoresBusquedaMovimientoPersona);
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