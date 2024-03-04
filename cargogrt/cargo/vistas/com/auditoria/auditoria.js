$(document).ready(function () {
    loaderShow(null);
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseAuditoria");
    cargarDatosBusqueda();
    obtenerDataBusquedaAuditoria();
});
var auditoriaId = null;
function onResponseAuditoria(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerDataAuditoria':
                onResponseGetDataGridAuditoria(response.data);
                break;
            case 'obtenerDetalleAuditoria':
                onResponseDetalleAuditoria(response.data);
                loaderClose();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                break;
//            case 'obtenerReporteKardexExcel':
//                loaderClose();
//                location.href = URL_BASE + "util/formatos/reporte.xlsx";
//                break;
            case 'enviarAuditoria':
                auditoriaId = response.data;
                loaderClose();
                //mostrarOk("Enviado correctamente");
                break;
            case 'finalizarAuditoria':
                auditoriaData.length = 0;
                auditoriaIdsData.length = 0;
                loaderClose();
                //mostrarOk("Finalizado correctamente");
                cargarPantallaListarAuditoria();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'enviarAuditoria':
                loaderClose();
                break;
            case 'finalizarAuditoria':
                loaderClose();
                break;
        }
    }
}
function onResponseObtenerConfiguracionesIniciales(data) {
    if (!isEmpty(data.organizador)) {
        select2.cargar("cboOrganizador", data.organizador, "id", "descripcion");
        if (!isEmpty(data.bien)) {
            select2.cargar("cboBien", data.bien, "id", "codigo_descripcion");
            if (!isEmpty(data.bien_tipo)) {
                select2.cargar("cboTipoBien", data.bien_tipo, "id", "descripcion");
                if (!isEmpty(data.fecha_primer_documento)) {

                    if (!isEmpty(data.fecha_primer_documento[0]['primera_fecha']))
                    {
                        $('#inicioFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['primera_fecha']));
                        if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
                        {
                            $('#finFechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
                        }
                    }

                }
            }

        }
    }
    loaderClose();
}

var valoresBusquedaAuditoria = [{organizador: "", bien: "", bienTipo: "", fechaEmision: "", empresaId: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var organizadorId = $('#cboOrganizador').val();

    var bien = $('#cboBien').val();

    var bienTipo = $('#cboTipoBien').val();

    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();


    valoresBusquedaAuditoria[0].organizador = organizadorId;
    valoresBusquedaAuditoria[0].bien = bien;
    valoresBusquedaAuditoria[0].bienTipo = bienTipo;
    valoresBusquedaAuditoria[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
    valoresBusquedaAuditoria[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaAuditoria[0].organizador))
    {
        cadena += negrita("Organizador: ");
        cadena += select2.obtenerTextMultiple('cboOrganizador');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaAuditoria[0].bien))
    {
        cadena += negrita("Bien: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaAuditoria[0].bienTipo))
    {
        cadena += negrita("Bien tipo: ");
        cadena += select2.obtenerTextMultiple('cboTipoBien');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaAuditoria[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaAuditoria[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaAuditoria[0].fechaEmision.inicio + " - " + valoresBusquedaAuditoria[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarAuditoria() {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaAuditoria(cadena);
}

function obtenerDataBusquedaAuditoria()
{
    ax.setAccion("obtenerDataAuditoria");
    ax.addParamTmp("criterios", valoresBusquedaAuditoria);
    
    ax.addParamTmp("auditoriaId", commonVars.auditoriaId);
    ax.consumir();
}

var auditoriaIdsData = new Array();
var auditoria = null;
function onResponseGetDataGridAuditoria(data) {
    var idPersona = data.persona_sesion;
    if(!isEmpty(data.persona))
    {
        select2.cargar("cboPersona",data.persona,"id","nombre");
        select2.asignarValor("cboPersona",idPersona);
    }
    
    $("#txtFecha").val(data.fecha);
    
    
    if(commonVars.auditoriaId != 0){
        select2.asignarValor("cboPersona",data.auditoriaDetalle[0].persona_id);
        $("#txtComenatrio").val(data.auditoriaDetalle[0].comentario);
        $("#txtFecha").val(formatearFechaJS(data.auditoriaDetalle[0].fecha));
        auditoriaId=commonVars.auditoriaId;
    }
    
    if (!isEmptyData(data.lista))
    {
        auditoria = data.lista;
        var id = 0;
        var stockRealValor=null;
        var discrepanciaValor=null;
        
        $.each(auditoria, function (index, item) {
            id += 1;
            stockRealValor=null;
            discrepanciaValor=null;
            
            auditoria[index]["id"] = id;
            
            auditoria[index]["stock_real"] = '<input type="number" value = "" id="txtValorReal_' + id + '" name="txtValorReal_' + id + '" onkeyup="calcularDiscrepancia(' + 'event' + ',' + id + ');" onchange="calcularDiscrepancia(' + 'event' + ',' + id + ');" style="text-align: right;" class="form-control" value="" />';
            auditoria[index]["discrepancia"] = '<label id="lblDiscrepancia_' + id + '"></label>';
            
            if(commonVars.auditoriaId != 0){
                auditoriaEditar=data.auditoriaDetalle;
                $.each(auditoriaEditar, function (indice, detalle) {
                    if(item.organizador_id==detalle.organizador_id && item.bien_id==detalle.bien_id && item.unidad_medida_id==detalle.unidad_medida_id){
                        auditoria[index]["stock_real"] = '<input type="number" value = "'+detalle.valor_real+'" id="txtValorReal_' + id + '" name="txtValorReal_' + id + '" onkeyup="calcularDiscrepancia(' + 'event' + ',' + id + ');"  onchange="calcularDiscrepancia(' + 'event' + ',' + id + ');" style="text-align: right;" class="form-control" />';
                        auditoria[index]["discrepancia"] = '<label id="lblDiscrepancia_' + id + '">'+detalle.discrepancia+'</label>';
                        
                        stockRealValor=detalle.valor_real;
                        discrepanciaValor=detalle.discrepancia;
                    }
                });                
            }            
            
            auditoria[index]["stock_real_valor"] = stockRealValor;
            auditoria[index]["discrepancia_valor"] = discrepanciaValor;
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "order": [[0, "desc"]],
            "data": auditoria,
            "columns": [
                {"data": "organizador_descripcion", "width": "150px"},
                {"data": "bien_descripcion", "width": "250px"},
                {"data": "bien_tipo_descripcion", "width": "120px"},
                {"data": "unidad_medida_descripcion", "width": "120px"},
                {"data": "stock", "sClass": "alignRight", "width": "80px"},
                {"data": "stock_real", "sClass": "alignCenter", "width": "50px"},
                {"data": "discrepancia", "sClass": "alignCenter", "width": "50px"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    loaderClose();
}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria();
    }
    loaderClose();
}

function verDetalleAuditoria(bienId, organizadorId, fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDetalleAuditoria");
    ax.addParamTmp("id_bien", bienId);
    ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}

function onResponseDetalleAuditoria(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';

        $('#datatableStock').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "unidad_medida_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-kardex').modal('show');
    }
    else
    {
        var table = $('#datatableStock').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

function exportarReporteKardexExcel()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteKardexExcel");
    ax.addParamTmp("criterios", valoresBusquedaAuditoria);
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function loaderBuscar()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria();
    }
    loaderClose();
}

function colapsarBuscador() {
    if (actualizandoBusquedaAuditoria) {
        actualizandoBusquedaAuditoria = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}
var actualizandoBusquedaAuditoria = false;
function actualizarBusqueda()
{
    actualizandoBusquedaAuditoria = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria(0);
    }
}
function nuevoFormAuditoria()
{
    cargarDiv('#window', 'vistas/com/auditoria/auditoria.php');
}

var auditoriaData = new Array();
function calcularDiscrepancia(key, id)
{
//    var unicode
//    if (key.charCode)
//    {
//        unicode = key.charCode;
//    }
//    else
//    {
//        unicode = key.keyCode;
//    }
    //alert(unicode); // Para saber que codigo de tecla presiono , descomentar
//    if (unicode == 13) {

    var stockSistema = auditoria[id - 1]['stock'];

    var valorReal = $("#txtValorReal_" + id).val();

    var discrepancia = stockSistema - valorReal;
    discrepancia=Math.round(discrepancia*1000000)/1000000;

    $("#lblDiscrepancia_" + id).empty();

    $("#lblDiscrepancia_" + id).append(discrepancia);

    auditoria[id - 1]["stock_real_valor"] = valorReal;
    auditoria[id - 1]["discrepancia_valor"] = discrepancia;
//    }
}

function enviar()
{
    loaderShow(null);
    var fecha = $("#txtFecha").val();
    var comentario = $("#txtComenatrio").val();
    var personaId = select2.obtenerValor("cboPersona");
    //alert(personaId);
    
    ax.setAccion("enviarAuditoria");
    ax.addParamTmp("auditoriaId", auditoriaId);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("auditoriaData", auditoria);
    ax.addParamTmp("personaId", personaId);
    ax.consumir();
}
function finalizar()
{
    if (!verificarImputStockRealLLenos())
    {
        var fecha = $("#txtFecha").val();
        var comentario = $("#txtComenatrio").val();
        var personaId = select2.obtenerValor("cboPersona");

        ax.setAccion("finalizarAuditoria");
        ax.addParamTmp("auditoriaId", auditoriaId);
        ax.addParamTmp("fecha", fecha);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("auditoriaData", auditoria);
        ax.addParamTmp("personaId", personaId);
        ax.consumir();
    }
    else
    {
        mostrarAdvertencia("Completar todos los campos");
    }
}

function verificarAuditoriaData(id)
{
    var bandera = false;
    $.each(auditoria, function (index, item) {
        if (auditoria[id - 1]['stock_real_valor'] != null)
        {
            bandera = true;
            return false;
        }
    })
    return bandera;
}
function cargarPantallaListarAuditoria()
{
    loaderShow(null);
    cargarDiv("#window", "vistas/com/auditoria/auditoria_listar.php");
}

function verificarImputStockRealLLenos()
{
    console.log(auditoria);
    var bandera = false;
    $.each(auditoria, function (index, value) {
        if (value.stock_real_valor == null || value.stock_real_valor == '')
        {
            bandera = true;
            return false;
        }
    })
    return bandera;
}
