var estadoTolltipMPG = 0;
var banderaBuscarMPG = 0;
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
    obtenerConfiguracionesInicialesMovimientoPersonaGeneral();
    modificarAnchoTabla('dataTableMovimientoPersona');
});

function onResponseMovimientoPersona(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesMovimientoPersonaGeneral':
                onResponseObtenerConfiguracionesInicialesMovimientoPersona(response.data);
                break;
            case 'obtenerCantidadesTotalesMovimientoPersonaGeneral':
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

function obtenerConfiguracionesInicialesMovimientoPersonaGeneral()
{
    ax.setAccion("obtenerConfiguracionesInicialesMovimientoPersonaGeneral");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesMovimientoPersona(data) {
    var string = '<option selected value="-1">Seleccionar una persona</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaMPG').append(string);
        select2.asignarValor('cboPersonaMPG', "-1");
    }
    
    if (!isEmpty(data.documento_tipo)) {
        var stringDocumento = '';
        $.each(data.documento_tipo, function (indexDocumento, itemDocumneto) {
            stringDocumento += '<option value="' + itemDocumneto.id + '">' + itemDocumneto.descripcion + '</option>';
        });
        $('#cboTipoDocumentoMPG').append(stringDocumento);
    }
    
    if (!isEmpty(data.empresa)) {
        var stringEmpresa = '';
        $.each(data.empresa, function (indexDocumento, itemEmpresa) {
            stringEmpresa += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
        });
        $('#cboEmpresaMPG').append(stringEmpresa);
    }
    
    if (!isEmpty(data.bien)) {
        var string = '<option selected value="-1">Seleccionar un producto</option>';
        $.each(data.bien, function (indexBien, itemBien) {
            string += '<option value="' + itemBien.id + '">' + itemBien.descripcion +' | '+itemBien.bien_tipo_descripcion + '</option>';
        });
        $('#cboBienMPG').append(string);
        select2.asignarValor('cboBienMPG', "-1");
    }
    loaderClose();
}

var valoresBusquedaMovimientoPersonaGeneral = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaMovimientoPersona()
{
    var personaId = $('#cboPersonaMPG').val();
    var bienId = $('#cboBienMPG').val();
    var documentoTipoId = $('#cboTipoDocumentoMPG').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();
    var empresaId = $('#cboEmpresaMPG').val();

    valoresBusquedaMovimientoPersonaGeneral[0].persona = personaId;
    valoresBusquedaMovimientoPersonaGeneral[0].bien = bienId;
    valoresBusquedaMovimientoPersonaGeneral[0].documentoTipo = documentoTipoId;
    valoresBusquedaMovimientoPersonaGeneral[0].empresa = empresaId;
    valoresBusquedaMovimientoPersonaGeneral[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaMovimientoPersonaGeneral[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableMovimientoPersona();

}
function buscarMovimientoPersonaGeneral(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesMovimientoPersonaGeneral();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMPG = 1;
        
    if (colapsa === 1)
        colapsarBuscador();
    
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusquedaMovimientoPersona();
    if (!isEmpty(valoresBusquedaMovimientoPersonaGeneral[0].empresa))
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresaMPG');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaMovimientoPersonaGeneral[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMPG');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaMovimientoPersonaGeneral[0].persona))
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersonaMPG');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaMovimientoPersonaGeneral[0].bien))
    {
        cadena += StringNegrita("Producto: ");

        cadena += select2.obtenerText('cboBienMPG');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaMovimientoPersonaGeneral[0].fechaVencimientoHasta))
    {
        cadena += StringNegrita("Fecha: ");
        cadena += valoresBusquedaMovimientoPersonaGeneral[0].fechaVencimientoHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableMovimientoPersona() {
    color = '';
    ax.setAccion("obtenerDataMovimientoPersonaGeneral");
    ax.addParamTmp("criterios", valoresBusquedaMovimientoPersonaGeneral);
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
            {"data": "razon_social"},
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
                "targets": 7
            }, 
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 8
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(7).footer()).html(
                    'S/. ' + (formatearNumero(cantidad_total))
                    );
            $(api.column(8).footer()).html(
                    'S/. ' + (formatearNumero(total))
                    );
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
        buscarMovimientoPersonaGeneral();
    }
}
function cerrarPopover()
{
    if (banderaBuscarMPG == 1)

    {
        if (estadoTolltipMPG == 1)
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


    estadoTolltipMPG = (estadoTolltipMPG == 0) ? 1 : 0;
}

function obtenerCantidadesTotalesMovimientoPersonaGeneral()
{
    ax.setAccion("obtenerCantidadesTotalesMovimientoPersonaGeneral");
    ax.addParamTmp("criterios", valoresBusquedaMovimientoPersonaGeneral);
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