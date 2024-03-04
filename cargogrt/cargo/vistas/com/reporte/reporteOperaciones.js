var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var totalSoles;
var totalDolares;
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
    ax.setSuccess("onResponseReporteOperaciones");
    obtenerConfiguracionesInicialesReporteOperaciones();
});

function onResponseReporteOperaciones(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteOperaciones':
                onResponseObtenerConfiguracionesInicialesReporteOperaciones(response.data);
                break;
            case 'obtenerCantidadesTotalesReporteOperaciones':                
                totalSoles = response.data.totalSoles;
                totalDolares = response.data.totalDolares;
                getDataTableReporteOperaciones();
                break;
            case 'obtenerReporteReporteOperacionesExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'verDetallePorOperacion':
                onResponseVerDetallePorOperacion(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteOperacionesExcel':
                loaderClose();
                break;
            case 'verDetallePorOperacion':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesReporteOperaciones()
{
    ax.setAccion("obtenerConfiguracionesInicialesReporteOperaciones");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesReporteOperaciones(data) {

    var string = '<option selected value="-1">Seleccionar un cliente</option>';
    if (!isEmpty(data.persona)) {
        select2.cargar('cboPersona',data.persona,'id',['nombre','codigo_identificacion']);
        select2.asignarValor('cboPersona', "-1");
    }
    
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar('cboTipoDocumentoMP',data.documento_tipo,'id','descripcion');
    }    
    
    var string ='';
    if (!isEmpty(data.empresa)) {
        select2.cargar('cboEmpresa',data.empresa,'id','razon_social');
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

var valoresBusquedaReporteOperaciones = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaReporteOperaciones()
{
    var personaId = select2.obtenerValor('cboPersona');
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();
    var empresaId = $('#cboEmpresa').val();

    valoresBusquedaReporteOperaciones[0].persona = personaId;
    valoresBusquedaReporteOperaciones[0].documentoTipo = documentoTipoId;
    valoresBusquedaReporteOperaciones[0].empresa = empresaId;
    valoresBusquedaReporteOperaciones[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaReporteOperaciones[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableReporteOperaciones();

}
function buscarReporteOperaciones(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesReporteOperaciones();
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
    cargarDatosBusquedaReporteOperaciones();
    
    if (!isEmpty(valoresBusquedaReporteOperaciones[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteOperaciones[0].empresa))
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteOperaciones[0].persona))
    {
        cadena += StringNegrita("Persona: ");

        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteOperaciones[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaReporteOperaciones[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteOperaciones[0].fechaEmisionDesde + " - " + valoresBusquedaReporteOperaciones[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableReporteOperaciones() {
    color = '';
    
    ax.setAccion("obtenerDataReporteOperaciones");
    ax.addParamTmp("criterios", valoresBusquedaReporteOperaciones);
    $('#dataTableReporteOperaciones').dataTable({
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
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "descripcion"},
            {"data": "total_soles", "class": "alignRight"},
            {"data": "total_dolares", "class": "alignRight"},
            {"data": "acciones", "class": "alignCenter"}

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
                    'S/. ' + (formatearNumero(totalSoles))
                    );
            $(api.column(8).footer()).html(
                    '$ ' + (formatearNumero(totalDolares))
                    );
        }
    });
    loaderClose();
    
//    setTimeout(function () {
//        $('#dataTableReporteOperaciones').attr("width", $('#dataTableReporteOperaciones_wrapper').css("width"));
//    }, 500);
    var ancho = $('#dataTableReporteOperaciones_wrapper').css("width");
    $('#dataTableReporteOperaciones').attr("width", ancho);    
}

var actualizandoBusqueda = false;
function loaderBuscarVentas()
{
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarReporteOperaciones();
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

function obtenerCantidadesTotalesReporteOperaciones()
{
    ax.setAccion("obtenerCantidadesTotalesReporteOperaciones");
    ax.addParamTmp("criterios", valoresBusquedaReporteOperaciones);
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

function exportarReporteReporteOperaciones()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaReporteOperaciones();
    ax.setAccion("obtenerReporteReporteOperacionesExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteOperaciones);
    ax.consumir();
}

// ver detalle en modal
function verDetallePorOperacion(documentoId)
{
    loaderShow();
    ax.setAccion("verDetallePorOperacion");
    ax.addParamTmp("documento_id", documentoId);
    ax.consumir();

//    
}

function onResponseVerDetallePorOperacion(data)
{    
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
    $('#txtDescripcion').val(data[0]['descripcion_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);


    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');
                        
            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor))
            {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
//                    case 2:
                    case 3:
                        valor = fechaArmada(valor);
                        break;
//                    case 4:
//                    case 5:
//                    case 6:
//                    case 7:
//                    case 8:
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
//                    case 12:
//                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 19:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
//            "scrollX": true,
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
                {"data": "precioUnitario", "sClass": "alignRight"},
                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}