$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteComisionVendedor");
    obtenerConfiguracionesInicialesComisionVendedor();
    modificarAnchoTabla('datatable');
});

function onResponseReporteComisionVendedor(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesComisionVendedor':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataComisionVendedor':
                onResponseGetDataGridComisionVendedor(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoComisionVendedor':
                onResponseDocumentoComisionVendedor(response.data);
                loaderClose();
                break;
            case 'obtenerReporteComisionVendedorExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteComisionVendedorExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesComisionVendedor()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesComisionVendedor");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');
    var date = new Date();
    var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
    var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    
    primerDia=primerDia.getDate();
    if(primerDia<10) {
        primerDia='0'+primerDia;
    }     
    
    $('#inicioFechaEmision').val(primerDia+"/"+(date.getMonth()+1)+"/"+date.getFullYear());
    $('#finFechaEmision').val(ultimoDia.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear());
    
    
    var string ='';
    if (!isEmpty(data.empresa)) {
        $.each(data.empresa, function (indexEmpresa, itemEmpresa) {
            string += '<option value="' + itemEmpresa.id + '">' + itemEmpresa.razon_social + '</option>';
        });
        $('#cboEmpresa').append(string);
        //select2.asignarValor('cboTienda', "-1");
    }
    
    if (!isEmpty(data.persona)) {
        select2.cargar("cboPersonaVendedor", data.persona, "id", ["persona_nombre", "codigo_identificacion"]);
    }
    loaderClose();
}

var valoresBusquedaComisionVendedor = [{vendedor: "", porcentaje: "", fechaEmision: ""}];

function cargarDatosBusqueda()
{

    var vendedor = $('#cboPersonaVendedor').val();
    var porcentaje = $('#txtPorcentaje').val();
    var fechaEmisionInicio = $('#inicioFechaEmision').val();
    var fechaEmisionFin = $('#finFechaEmision').val();
    var empresaId = $('#cboEmpresa').val();


    valoresBusquedaComisionVendedor[0].vendedor = vendedor;
    valoresBusquedaComisionVendedor[0].empresa = empresaId;
    valoresBusquedaComisionVendedor[0].porcentaje = porcentaje;
    valoresBusquedaComisionVendedor[0].fechaEmision = objetoFecha(fechaEmisionInicio, fechaEmisionFin);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaComisionVendedor[0].empresa))
    {
        cadena += StringNegrita("Empresa: ");

        cadena += select2.obtenerTextMultiple('cboEmpresa');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComisionVendedor[0].vendedor))
    {
        cadena += negrita("Vendedor: ");
        cadena += select2.obtenerTextMultiple('cboPersonaVendedor');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComisionVendedor[0].porcentaje))
    {
        cadena += negrita("Porcentaje: ");
        cadena += $('#txtPorcentaje').val();
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaComisionVendedor[0].fechaEmision.inicio) || !isEmpty(valoresBusquedaComisionVendedor[0].fechaEmision.fin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaComisionVendedor[0].fechaEmision.inicio + " - " + valoresBusquedaComisionVendedor[0].fechaEmision.fin;
        cadena += "<br>";
    }
    return cadena;
}

function buscarComisionVendedor(colapsa) {
    loaderShow();
        
    var porcentaje = $('#txtPorcentaje').val();
    if(parseFloat(porcentaje)<0 || isEmpty(porcentaje)){
        mostrarAdvertencia('Ingrese un porcentaje positivo');
        loaderClose();
        return ;
    }
    
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaComisionVendedor(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaComisionVendedor()
{
    ax.setAccion("obtenerDataComisionVendedor");
    ax.addParamTmp("criterios", valoresBusquedaComisionVendedor);
    ax.consumir();
}

function onResponseGetDataGridComisionVendedor(data) {

    if (!isEmptyData(data))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoComisionVendedor(' + item['vendedor_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/
        $('#datatable').dataTable({
     
            "order": [[0, "desc"]],
            "data": data,      
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "vendedor_nombre"},
                {"data": "total_ventas",  "sClass": "alignRight"},
                {"data": "comision", "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 1
                }, 
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 2
                }
            ],
            
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarKardex();
    }
    loaderClose();
}

function verDocumentoComisionVendedor(vendedorId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoComisionVendedor");
    ax.addParamTmp("id_vendedor", vendedorId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoComisionVendedor(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['vendedor_descripcion'] + '</strong>';

        $('#datatableDocumentoComisionVendedor').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "fecha_emision"},
                {"data": "documento_tipo_descripcion"},
                {"data": "persona_nombre"},
                {"data": "serie"},
                {"data": "numero"},
                {"data": "fecha_vencimiento"},
                {"data": "documento_estado_descripcion"},
                {"data": "cantidad", "sClass": "alignRight"}
            ],
            "destroy": true
        });
        $('.modal-title').empty();
        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-documentos-servicios').modal('show');
    }
    else
    {
        var table = $('#datatableDocumentoComisionVendedor').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este vendedor.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteComisionVendedorExcel()
{    
    var porcentaje = $('#txtPorcentaje').val();
    if(parseFloat(porcentaje)<0 || isEmpty(porcentaje)){
        mostrarAdvertencia('Ingrese un porcentaje positivo');
        loaderClose();
        return ;
    }
    
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteComisionVendedorExcel");
    ax.addParamTmp("criterios", valoresBusquedaComisionVendedor);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarComisionVendedor();
    }
    loaderClose();
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
}