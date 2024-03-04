var estadoTolltipMP = 0;
var banderaBuscarMP = 0;
var total;
var total_cantidad;
var currentDocument = "no asignado";
$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    $('[data-toggle="tooltip"]').tooltip();
    cargarTitulo("titulo", "");
    esperar(1000).then(function(){$("#titulo").html("Reporte de atenciones del proceso de compra")});
    select2.iniciar();
//    iniciarDataPicker();
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
    ax.setSuccess("onResponseReporteListadoAtencion");
    obtenerConfiguracionesInicialesListadoAtencion();
    modificarAnchoTabla('dataTableReporteAtenciones');
});


function onResponseReporteListadoAtencion(response) {
    if (response['status'] === 'ok') {
        switch(response[PARAM_ACCION_NAME])
        {
            case 'obtenerConfiguracionesInicialesListadoAtencion':
                onResponseObtenerConfiguracionesInicialesListadoAtencion(response.data);
                break;
            case 'getDataTableReporteCompras':
                onResponseGetDataTableReporteCompras(response.data);
                break;
            case 'getMapaConstruccionData':
                onResponseGetMapaConstruccionData(response.data);
                loaderShow("#modalito");
                // setTimeout(loaderClose, 5000);
                esperar(1000).then(loaderClose);
                break;
            case 'visualizarDetalleDocumentoReporteAsignacion':
                onResponseVisualizarDetalleDocumentoReporteAsignacion(response.data);
                break;
        }
    }
}
function onResponseGetDataTableReporteCompras(data)
{
    console.log(data);
}
function obtenerConfiguracionesInicialesListadoAtencion() {
    ax.setAccion("obtenerConfiguracionesInicialesListadoAtencion");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}



function onResponseObtenerConfiguracionesInicialesListadoAtencion(data) {

    if (!isEmpty(data.documento_tipo)) {
        var stringDocumento = '';
        $.each(data.documento_tipo, function (indexDocumento, itemDocumento) {
            stringDocumento += '<option value="' + itemDocumento.id + '">' + itemDocumento.descripcion + '</option>';
        });
        $('#cboTipoDocumentoMP').append(stringDocumento);
    }

    if(!isEmpty(data.cboProductoData)){
        var stringDocumento = '';
        $.each(data.cboProductoData, function (indexDocumento, itemDocumento) {
            stringDocumento += '<option value="' + itemDocumento.id + '">' + itemDocumento.codigo + " | " + itemDocumento.b_descripcion + '</option>';
        });
        $('#cboProducto').append(stringDocumento);
    }

    if(!isEmpty(data.cboProductoTipoData)){
        var stringDocumento = '';
        $.each(data.cboProductoTipoData, function (indexDocumento, itemDocumento) {
            stringDocumento += '<option value="' + itemDocumento.id + '">' + itemDocumento.codigo + " | " + itemDocumento.descripcion + '</option>';
        });
        $('#cboProductoTipo').append(stringDocumento);
    }

    var string = '<option selected value="-1">Seleccionar un proveedor</option>';
    if (!isEmpty(data.cboPersonaData)) {
        $.each(data.cboPersonaData, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboProveedor').append(string);
        select2.asignarValor('cboProveedor', "-1");
    }



}

var valoresBusquedaReporteAtenciones = [{documentoTipo: "", empresa: "",fechaVencimientoDesde: "", fechaVencimientoHasta: "", bandera: "0"}];//bandera 0 es balance

function cargarDatosBusquedaReporteCompras()
{
    var documentoTipoId = $('#cboTipoDocumentoMP').val();
    var fechaEmisionInicio = $('#inicioFechaEmisionMP').val();
    var fechaEmisionFin = $('#finFechaEmisionMP').val();

    valoresBusquedaReporteAtenciones[0].documentoTipo = documentoTipoId;
    valoresBusquedaReporteAtenciones[0].empresa = commonVars.empresa;
    valoresBusquedaReporteAtenciones[0].fechaEmisionDesde = fechaEmisionInicio;
    valoresBusquedaReporteAtenciones[0].fechaEmisionHasta = fechaEmisionFin;
//    getDataTableReporteCompras();

}

function buscarReporteAtenciones(colapsa)
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    getDataTableReporteAtenciones();
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

    if (!isEmpty(valoresBusquedaReporteAtenciones[0].documentoTipo))
    {
        cadena += StringNegrita("Tipo de documento: ");

        cadena += select2.obtenerTextMultiple('cboTipoDocumentoMP');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaReporteAtenciones[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaReporteAtenciones[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaReporteAtenciones[0].fechaEmisionDesde + " - " + valoresBusquedaReporteAtenciones[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}
var color;

function getDataTableReporteAtenciones() {
    breakFunction();
    color = '';
    ax.setAccion("obtenerDataReporteAtenciones");
    ax.addParamTmp("criterios", valoresBusquedaReporteAtenciones);
    //ax.consumir();
    $('#dataTableReporteAtenciones').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//F. Creación	F. Emisión	Vendedor	Tipo documento	Persona	Serie	Número	Total
            {"data": "fecha_creacion", "visible" : false},
            {"data": "fecha_emision"},
            {"data": "documento_tipo_descripcion"},
            {"data": "persona_nombre_completo"},
            {"data": "serie"},
            {"data": "numero"},
            {"data": "usuario_nombre"},
            {"data": "total", "class": "alignCenter"}

        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    // return parseFloat(data).formatMoney(2, '.', ',');
                    return data;
                },
                "targets": 7
            },
            // {
            //     "render": function (data, type, row) {
            //         return (isEmpty(data))?'':data.replace(" 00:00:00", "");
            //     },
            //     "targets": 0
            // },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                },
                "targets": 1
            }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull){

            //console.log(iDisplayIndex);
            // var $th = $td.closest('table').find('th').eq($td.index());
//            console.log(aData);
            // $('td:eq(7)', nRow).html( StringNegrita("<a href='#' onclick='abrirModalito("+aData['documento_id']+","+quote(aData['documento_tipo_descripcion'],aData['serie'], aData['numero'])+")' >"+aData['documento_tipo_descripcion']+"</a>") );
            $('td:eq(6)', nRow).html( StringNegrita("<a href='#' onclick='abrirModalito("+aData['documento_id']+","+quote(aData['documento_tipo_descripcion'],aData['serie'], aData['numero'])+")' >"+"<i class='fa fa-sitemap'></i>"+"</a>") );

        },
        destroy: true
        // footerCallback: function (row, data, start, end, display) {
        //     var api = this.api(), data;
        //     $(api.column(7).footer()).html(
        //         'S/. ' + (formatearNumero(total))
        //     );
        // }
    });
    ocultarSearchBar("dataTableReporteAtenciones");
    evitarFooter();
    // breakFunction();
    loaderClose();
}

function quote(text, serie, numero)
{
    if(!isEmpty(text)) {
        return '"' + text + ' ' + serie + ' ' +  numero +  '"';
    }else{
        return "nope";
    }
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
    setTimeout(ocultarPopOver, 3000);
}

function ocultarPopOver()
{
    $('[data-toggle="popover"]').popover('hide');
}

function visualizarMapaAtenciones(documentoId)
{
    var nuevaUrl = URL_BASE +'vistas/com/reporte/reporteAtenciones.php?documentoId='+documentoId;
    window.open(nuevaUrl);
}

function abrirModalito(documentoId, currentTitle)
{
    var frameSrc = "vistas/com/reporte/reporteAtenciones.php";
    var a = $('head');
    $.each(a, function(index, value){
        var temp = $(this).html();
        $(this).append($("<link/>",
            { rel: "stylesheet", href: "../../sgi/vistas/libs/imagina/css/bootstrap-reset.css", type: "text/css" }));
    })
    var $head = $("#framecito").contents().find("head");
    // $head.append($("<link/>",
    //     { rel: "stylesheet", href: "../libs/imagina/css/bootstrap-reset.css", type: "text/css" }));
    currentDocument =  currentTitle;
    ax.setAccion("getMapaConstruccionData");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
    $("#tituloModalitoMapa").text("Visualización de " + currentTitle);

    $('#framecito').attr("src",frameSrc);

}

function appendLink(val, onclickaction , onclickprm, url)
{
    var prms = "";
    if(isEmpty(url))
        url = "#";
    if(isEmpty(onclickaction))
        onclickaction="";
    if(!isEmpty(onclickprm))
    {
        $.each(onclickprm, function(index, value){
            prms+=value;
            prms+= index==onclickprm.length-1?"":",";
        });
    }
    return "<a href='"+url+"' data-toggle='tooltip' title='Ver Detalle "+val+"' onclick='"+onclickaction+"("+prms+")'>"+val+"</a>";
}

function cerrarModalito() {
    $('#modalito').modal('hide');
    $("#tablaAtencionHead").empty();
    $("#tablaAtencionBody").empty();
    permitidosListar = new Array();
}
var permitidosListar = new Array();
var globalData;



function onResponseGetMapaConstruccionData(data)
{
    globalData = data;
    $.each(data.dataTablaLeftSide, function (index, value) {
        permitidosListar.push(value.bien_id)
    })
    console.log(data.dataTablaLeftSide);
    if(!isEmpty(data.dataTablaAtencionesColumnas))
    {
        $.each(data.dataTablaAtencionesColumnas, function(index, value) {
            if(index>=2) {
                var parameters = new Array();
                parameters.push(value.documentoId);
                parameters.push(value.movimientoId);
                $("#tablaAtencionHead").append("<th>" + appendLink(value.nombreserienumero,'visualizarDetalleDocumentoReporteAsignacion',parameters) + "</th>");
            }else{
                $("#tablaAtencionHead").append("<th>" + value.nombreserienumero + "</th>");
            }


            if(!isEmpty(data.dataTablaAtencionesData))
            {

                var htmlConstruir="";
                var relleno = "";
                var cantidadRelleno = "";
                var noPrep = false;

                for(var i=0;i<data.dataTablaAtencionesData.length;i++)
                {
                    if(!isEmpty(data.dataTablaAtencionesData[i][index])) {
                        if (($.inArray(data.dataTablaAtencionesData[i][index].bien_id, permitidosListar)) !== -1) {
                            relleno = data.dataTablaLeftSide[index].bien_descripcion;
                            cantidadRelleno = (data.dataTablaLeftSide[index].cantidad / 1);
                            htmlConstruir += "<td>" + (data.dataTablaAtencionesData[i][index].cantidad / 1) + " " + data.dataTablaAtencionesData[i][index].unidad_medida_id + "</td>";
                        }else {
                            htmlConstruir += "<td></td>";
                        }
                    }else {
                        // htmlConstruir += "<td></td>";
                        noPrep = true;
                    }
                    // console.log(data.dataTablaAtencionesData[i][0]);
                }
                htmlConstruir+="</tr>";
                var nuevoHtml = "<tr><td id ='nelprro'>"+relleno+"</td><td id ='nelprro'>"+cantidadRelleno+"</td>";
                if(noPrep)nuevoHtml="";
                $("#tablaAtencionBody").append(nuevoHtml+htmlConstruir);

            }
        });
        esperar(500).then(function(){
            var a = $("#tablaAtencionBody tr")
            $.each(a, function(index ,value){
                var TDS = $(this).find("td[id!='nelprro']");
                var maxQty = $(this).find("td:nth-child(2)").text();
                var currentQty = 0;
                console.log(maxQty);
                $.each(TDS, function(indice, valor){
                    var thisVal = parseInt($(this).text());
                    if(thisVal>=maxQty)
                    {
                        $(this).attr('bgcolor','#dff0d8');
                    }else{
                        $(this).attr('bgcolor','#f2dede');
                    }
                    if($(this).text()=="")
                    {
                        $(this).attr('bgcolor','#f5f5f5');
                        $(this).text('-');
                    }

                });
            });
        });
    }




    var jump = 100;
    var size = 100;

    if(!isEmpty(data.Posteriores)) {
        $.each(data.dataPosteriores, function (index, value) {
            var nuevoElemento = '<div id="divAnt' + value.documento_relacionado_id + '" class="divsMapa" style="position: absolute; width: 300px;top: ' + size + 'px;left: 50px;">' +
                '<div class="portlet">' +
                '<div class="portlet-heading bg-mapa-centro-offset--1">' +
                '<h3 class="portlet-title">' +
                value.documento_tipo + " " + value.serie_numero +
                '</h3>' +
                '<div class="clearfix"></div></div></div></div>';
            $("#framecito").contents().find('div').first().append(nuevoElemento);

            size += jump;
        });
        size = 100;
    }
    if(!isEmpty(data.dataAnteriores)) {

        $.each(data.dataAnteriores, function (index, value) {
            var nuevoElemento = '<div id="divPost' + value.documento_relacionado_id + '" class="divsMapaDerecha" style="position: absolute; width: 300px;top: ' + size + 'px;left: 1200px;right: 50px">' +
                '<div class="portlet">' +
                '<div class="portlet-heading bg-mapa-centro-offset-1">' +
                '<h3 class="portlet-title">' +
                value.documento_tipo + " " + value.serie_numero +
                '</h3>' +
                '<div class="clearfix"></div></div></div></div>';
            $("#framecito").contents().find('div').first().append(nuevoElemento);
            size += jump;
        });
        size=100;
    }


    var elementoCentral = '<div id="div2" class="divsMapaCentro"'+
    'style="position: absolute;top: 200px; left: 600px;width: 300px">'+
        '<div class="portlet">'+
    '<div class="portlet-heading bg-mapa-centro">'+
    '<h3 class="portlet-title">'+
    currentDocument +
    '</h3>'+
    '<div class="clearfix"></div>'+
    '</div></div></div>';




    $("#framecito").contents().find('div').first().append(elementoCentral);


    $('#modalito').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
    //Por alguna extraña razón, se necesita llamar a loaderShow aquí y en el handler del response, de lo contrario no se muestra.
    loaderShow('#modalito');
    // setTimeout(loaderClose,5000);
}

function visualizarDetalleDocumentoReporteAsignacion(documentoId, movimientoId)
{
    // alert(documentoId + " " + movimientoId);
    ax.setAccion("visualizarDetalleDocumentoReporteAsignacion");
    ax.addParamTmp("documentoId", documentoId);
    ax.addParamTmp("movimientoId", movimientoId);
    ax.consumir();
}
var resultadoObtenerEmails;
var dataVisualizarDocumento;
function onResponseVisualizarDetalleDocumentoReporteAsignacion(data)
{
    // cerrarModalito(); //NO CERRAR, OCULTAR APRA PODER REGRESAR _
    $('#modalito').modal('hide');
    resultadoObtenerEmails = null;
    dataVisualizarDocumento = data;
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento, data.configuracionEditable);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna);
    dibujarTipoEnvioEmail(data.dataAccionEnvio);
    $('#btnRegresar').append(" "+currentDocument);
    $('#modalDetalleDocumento').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

function reOpenModal()
{

    $('#modalito').modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
    esperar(1000).then(function(){
        $('#btnRegresar').html('<i class="ion-arrow-return-left"></i> Regresar a ')
    });
}

function existeColumnaCodigo(codigo) {
    var dataColumna = movimientoTipoColumna;

    var existe = false;
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            if (parseInt(item.codigo) === parseInt(codigo)) {
                existe = true;
                return false;
            }
        });
    }

    return existe;
}
function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}
function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}
function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}
function cargarDataDocumento(data, configuracionEditable)
{
    textoDireccionId = 0;
    personaDireccionId = 0;
    camposDinamicos = [];

    guardarEdicionDocumento = false;
    if (!isEmpty(configuracionEditable)) {
        guardarEdicionDocumento = true;
    }

//    if(!isEmpty(configuracionEditable)){
//        $("#botonEdicion").show();
//    }

    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);
    var contador = 0;

    if (!isEmpty(data)) {
//        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        $('#tituloVisualizacionModal').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            if (contador % 3 == 0) {
                appendFormDetalle('<div class="row">');
                appendFormDetalle('</div>');
            }
            contador++;


//            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-4"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                '<label>' + item.descripcion + '</label>' +
                '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = '';
            if (item.edicion_habilitar == 0) {
                valor = quitarNULL(item.valor);

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
//                    case 22:
//                        if(valor==1)
//                            valor='Retención';
//                        else if(valor==2)
//                            valor='Detracción';
//                        else valor='';
//                        break;
                    }
                }
            } else {
                $.each(configuracionEditable, function (index, itemEditable) {
                    if (itemEditable.documento_tipo_dato_id == item.documento_tipo_id) {

                        camposDinamicos.push({
                            id: item.documento_tipo_id,
                            tipo: parseInt(itemEditable.tipo),
                            opcional: itemEditable.opcional,
                            descripcion: itemEditable.descripcion
                        });

                        switch (parseInt(item.tipo)) {
                            case 1:
                            case 14:
                            case 15:
                            case 16:
                            case 19:
                                valor += '<input type="number" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45" style="text-align: right;" />';
                                break;

                            case 7:
                            case 8:
                                valor += '<input type="text" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45" style="text-align: right;"/>';
                                break;

                            case 2:
                            case 6:
                            case 12:
                            case 13:

                                if (parseInt(itemEditable.numero_defecto) === 1) {
                                    textoDireccionId = itemEditable.documento_tipo_dato_id;
                                }
                                valor += '<input type="text" id="txt_' + item.documento_tipo_id + '" name="txt_' + item.documento_tipo_id + '" class="form-control" value="" maxlength="45"/>';
                                break;
                            case 9:
                            case 3:
                            case 10:
                            case 11:
                                valor += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                                    '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.documento_tipo_id + '">' +
                                    '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span></div>';
                                break;
                            case 4:
                                valor += '<select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2"></select>';
                                break;
                            case 5:
                                valor += '<div id ="div_persona" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                                $.each(itemEditable.data, function (indexPersona, itemPersona) {
                                    valor += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                                });
                                valor += '</select>';
                                break;
                            case 17:
                                valor += '<div id ="div_organizador_destino" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione organizador</option>';
                                $.each(itemEditable.data, function (indexOrganizador, itemOrganizador) {
                                    valor += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                                });
                                valor += '</select>';
                                break;
                            case 18:
                                personaDireccionId = item.documento_tipo_id;
                                valor += '<div id ="div_direccion" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '</select>';
                                break;
                            case 20:
                                valor += '<div id ="div_cuenta" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                                $.each(itemEditable.data, function (indexCuenta, itemCuenta) {
                                    valor += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                                });
                                valor += '</select>';
                                break;
                            case 21:
                                valor += '<div id ="div_actividad" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione la actividad</option>';
                                $.each(itemEditable.data, function (indexActividad, itemActividad) {
                                    valor += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                                });
                                valor += '</select>';
                                break;
                            case 22:
                                valor += '<div id ="div_retencion_detraccion" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione retención o detracción</option>';
                                $.each(itemEditable.data, function (indexRetencionDetraccion, itemRetencionDetraccion) {
                                    valor += '<option value="' + itemRetencionDetraccion.id + '">' + itemRetencionDetraccion.descripcion + '</option>';
                                });
                                valor += '</select>';
                                break;
                            case 23:
                                valor += '<div id ="div_persona_' + item.documento_tipo_id + '" ><select name="cbo_' + item.documento_tipo_id + '" id="cbo_' + item.documento_tipo_id + '" class="select2">';
                                valor += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                                $.each(itemEditable.data, function (indexPersona, itemPersona) {
                                    valor += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                                });
                                valor += '</select>';
                                break;
                        }
                    }
                });
            }

            html += '' + valor + '';
            html += '</div></div>';
            appendFormDetalle(html);

            if (item.edicion_habilitar == 1) {
                $.each(configuracionEditable, function (index, itemEditable) {
                    if (itemEditable.documento_tipo_dato_id == item.documento_tipo_id) {
                        switch (parseInt(item.tipo)) {
                            case 3:
                            case 9:
                            case 10:
                            case 11:
                                $('#datepicker_' + item.documento_tipo_id).datepicker({
                                    isRTL: false,
                                    format: 'dd/mm/yyyy',
                                    autoclose: true,
                                    language: 'es'
                                });

                                if (isEmpty(itemEditable.valor_id)) {
                                    $('#datepicker_' + item.documento_tipo_id).datepicker('setDate', itemEditable.data);
                                } else {
                                    $('#datepicker_' + item.documento_tipo_id).datepicker('setDate', formatearFechaJS(itemEditable.valor_id));
                                }


                                break;
                            case 4:
                                select2.cargar("cbo_" + item.documento_tipo_id, itemEditable.data, "id", "descripcion");
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });
                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 5:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                }).on("change", function (e) {
                                    obtenerPersonaDireccion(e.val);
//                                    obtenerBienesConStockMenorACantidadMinima(e.val);
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 17:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 18:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 20:
                            case 21:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 22:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            case 23:
                                $("#cbo_" + item.documento_tipo_id).select2({
                                    width: '100%'
                                });

                                select2.asignarValor("cbo_" + item.documento_tipo_id, itemEditable.valor_id);
                                break;
                            //input numero
                            case 1:
                            case 14:
                            case 15:
                            case 16:
                            case 19:
                                $('#txt_' + item.documento_tipo_id).val(formatearNumero(itemEditable.valor_id));
                                break;

                            //input texto
                            case 7:
                            case 8:
                            case 2:
                            case 6:
                            case 12:
                            case 13:
                                $('#txt_' + item.documento_tipo_id).val(itemEditable.valor_id);
                                break;
                        }
                    }
                });

            }
        });
        appendFormDetalle('</div>');
    }
}
function cargarDetalleDocumento(data, dataMovimientoTipoColumna) {
    movimientoTipoColumna = dataMovimientoTipoColumna;

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
//        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataVisualizarDocumento.organizador)) {
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
            if (!isEmpty(dataVisualizarDocumento.organizador)) {
                html += "<td>" + item.organizador + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidadMedida + "</td>";
            html += "<td>" + item.descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.precioUnitario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    }
    else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}
function dibujarTipoEnvioEmail(data) {

    $("#idDescripcionBoton").hide();
    var ulObtenerEmail = $("#ulObtenerEmail");
    ulObtenerEmail.empty();
    var html = '';
    var estilo = '';
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            estilo = '';
            if (!isEmpty(item.color)) {
                estilo = 'style="color: ' + item.color + '"';
            }

            html += '<li><a href="#" onclick="obtenerEmail(\'' + item.funcion + '\',\'' + item.descripcion + '\',\'' + item.icono + '\')"><i class="' + item.icono + '" ' + estilo + '></i>&nbsp;&nbsp; ' + item.descripcion + '</a></li>';
        });
    } else {
        $('#alertEmail').hide();
    }

    ulObtenerEmail.append(html);
}
