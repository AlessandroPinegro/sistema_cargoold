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
    ax.setSuccess("onResponseReporteCompras");
    obtenerConfiguracionesInicialesReporteCompras();
    modificarAnchoTabla('dataTableReporteCompras');
});

function onResponseReporteCompras(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesReporteCompras':
                onResponseObtenerConfiguracionesInicialesReporteCompras(response.data);
                break;
            case 'obtenerCantidadesTotalesReporteCompras':
                if (response.data.total === null)
                {
                    response.data.total = 0;
                }

                total = response.data.total;
                getDataTableReporteCompras();
                break;
            case 'obtenerReporteReporteComprasExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
            case 'obtenerReporteComprasProducto':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;

            case 'obtenerDocumento':
                onResponseObtenerDocumento(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentosRelacionados':
                onResponseObtenerDocumentosRelacionados(response.data);
                loaderClose();
                break;


        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteReporteComprasExcel':
                loaderClose();
                break;
            case 'obtenerReporteComprasProducto':
                loaderClose();
                break;

        }
    }
}

function obtenerConfiguracionesInicialesReporteCompras()
{
    ax.setAccion("obtenerConfiguracionesInicialesReporteCompras");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesReporteCompras(data) {

    var string = '<option selected value="-1">Seleccionar un proveedor</option>';
    if (!isEmpty(data.persona)) {
        $.each(data.persona, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersonaProveedor').append(string);
        select2.asignarValor('cboPersonaProveedor', "-1");
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
    if (!isEmpty(data.origen)) {
//        var string = '<option selected value="-1">Seleccionar un</option>';
        var stringOrigen = '';

        $('#cboOrigen').append(stringOrigen);
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

var valoresBusquedaReporteCompras = [{persona: "", fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaReporteCompras()
{
    var personaId = $('#cboPersonaProveedor').val();
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var origen = $('#cboOrigen').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaReporteCompras[0].persona = personaId;
    valoresBusquedaReporteCompras[0].documentoTipo = documentoTipoId;
    valoresBusquedaReporteCompras[0].origen = origen;
    valoresBusquedaReporteCompras[0].empresa = commonVars.empresa;
    valoresBusquedaReporteCompras[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaReporteCompras[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableReporteCompras();

}
function buscarReporteCompras(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerCantidadesTotalesReporteCompras();
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
    cargarDatosBusquedaReporteCompras();

    if (!isEmpty(valoresBusquedaReporteCompras[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaReporteCompras[0].origen))
    {
        cadena += StringNegrita("Origen: ");

        cadena += select2.obtenerText('cboOrigen');
        cadena += "<br>";
    }
    if (select2.obtenerValor('cboPersonaProveedor') != -1)
    {
        cadena += StringNegrita("Proveedor: ");

        cadena += select2.obtenerText('cboPersonaProveedor');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaReporteCompras[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaReporteCompras[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteCompras[0].fechaEmisionDesde + " - " + valoresBusquedaReporteCompras[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableReporteCompras() {
    color = '';
    ax.setAccion("obtenerDataReporteCompras");
    ax.addParamTmp("criterios", valoresBusquedaReporteCompras);
    $('#dataTableReporteCompras').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Origen Total acciones
            {"data": "fecha_creacion"},
            {"data": "fecha_emision"},
            {"data": "usuario_nombre"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "origen"},
            {"data": "total", "class": "alignRight"},
            {"data": "acciones", "class": "alignCenter"}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 8
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": 1
            }
        ],
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(8).footer()).html(
                    'S/. ' + (formatearNumero(total))
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
        buscarReporteCompras();
    }
}

function cerrarPopover()
{
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
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


    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
}

function obtenerCantidadesTotalesReporteCompras()
{
    ax.setAccion("obtenerCantidadesTotalesReporteCompras");
    ax.addParamTmp("criterios", valoresBusquedaReporteCompras);
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

function exportarReporteReporteCompras()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaReporteCompras();
    ax.setAccion("obtenerReporteReporteComprasExcel");
    ax.addParamTmp("criterios", valoresBusquedaReporteCompras);
    ax.consumir();
}
function exportarReporteComprasProducto()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaReporteCompras();
    ax.setAccion("obtenerReporteComprasProducto");
    ax.addParamTmp("criterios", valoresBusquedaReporteCompras);
    ax.consumir();
}
function obtenerDocumento(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumento");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}

function onResponseObtenerDocumento(data) {
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);

    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna, data.organizador);
    } else {
        $('#formularioCopiaDetalle').hide();
    }

    $('#modalDetalleDocumento').modal('show');
}
function cargarDataDocumento(data) {
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
                    case 3:
                        valor = fechaArmada(valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
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
function fechaArmada(valor) {
    var fecha = separarFecha(valor);
    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}
function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
    $('#txtDescripcion').val(data[0]['descripcion_documemto']);
}
var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna, dataOrganizador) {
    movimientoTipoColumna = dataMovimientoTipoColumna;
    if (!isEmptyData(data)) {
        $('#formularioCopiaDetalle').show();

        $.each(data, function (index, item) {
            data[index]["importe"] = formatearNumero(data[index]["cantidad"] * data[index]["valor_monetario"]);
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["valor_monetario"] = formatearNumero(data[index]["valor_monetario"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
//        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataOrganizador)) {
            html += "<th style='text-align:center;'>Organizador</th>";
        }
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Unidad de medida</th>";
        html += "<th style='text-align:center;'>Producto</th> ";
        if (existeColumnaCodigo(5)) {
            html += "<th style='text-align:center;'>Precio Unitario</th>";
            html += "<th style='text-align:center;'>Total</th>";
        }
        html += "</tr>";
        tHeadDetalle.append(html);


        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalle');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            html += "<tr>";
//            if(existeColumnaCodigo(15)){
            if (!isEmpty(dataOrganizador)) {
                html += "<td>" + item.organizador_descripcion + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidad_medida_descripcion + "</td>";
            html += "<td>" + item.bien_descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.valor_monetario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function obtenerDocumentosRelacionados(documentoId){
    loaderShow();
    ax.setAccion("obtenerDocumentosRelacionados");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}
function obtenerDocumentoRelacion(documentoId){
    $('#modalDocumentoRelacionado').modal('hide');
    obtenerDocumento(documentoId);
}
function onResponseObtenerDocumentosRelacionados(data){
    $('#linkDocumentoRelacionado').empty();
    
    if (!isEmptyData(data)){
        $('[data-toggle="popover"]').popover('hide');
        $.each(data, function (index, item) {
            $('#linkDocumentoRelacionado').append("<a onclick='obtenerDocumentoRelacion(" + item.documento_relacionado_id + ")' style='color:#0000FF'>[" + item.documento_tipo + ": " + item.serie_numero + "]</a><br>");
        });
        $('#modalDocumentoRelacionado').modal('show');
    }else{
        mostrarAdvertencia("No se encontró ningún documento relacionado con el actual.");
    }
}
