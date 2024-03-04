$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorCuentaFecha");
    obtenerConfiguracionesInicialesPorCuentaFecha();
    iniciarDatatable();
});

function onResponseReportePorCuentaFecha(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorCuentaFecha':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataPorCuentaFecha':
                onResponseGetDataGridPorCuentaFecha(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorCuentaFecha':
                onResponseDocumentoPorCuentaFecha(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorCuentaFechaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorCuentaFechaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorCuentaFecha()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorCuentaFecha");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
  
    if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
    {
        $('#fechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
    }  
    
    if (!isEmpty(data.cuenta)) {
        select2.cargar("cboCuenta", data.cuenta, "id", "descripcion_numero");
    }
    loaderClose();
}

var valoresBusquedaPorCuentaFecha = [{fechaEmision: "", cuenta:""}];

function cargarDatosBusqueda()
{

//    var documentoTipo = $('#cboTipoDocumento').val();
    var cuenta = $('#cboCuenta').val();
    var fechaEmision=$('#fechaEmision').val();


//    valoresBusquedaPorCuentaFecha[0].documentoTipo = documentoTipo;
    valoresBusquedaPorCuentaFecha[0].cuenta = cuenta;
    valoresBusquedaPorCuentaFecha[0].fechaEmision = fechaEmision;
    valoresBusquedaPorCuentaFecha[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaPorCuentaFecha[0].fechaEmision))
    {
        cadena += StringNegrita("Fecha pago: ");
        cadena += valoresBusquedaPorCuentaFecha[0].fechaEmision;
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorCuentaFecha[0].documentoTipo))
    {
        cadena += negrita("Tipo documento: ");
        cadena += select2.obtenerTextMultiple('cboTipoDocumento');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorCuentaFecha[0].cuenta))
    {
        cadena += negrita("Cuenta: ");
        cadena += select2.obtenerTextMultiple('cboCuenta');
        cadena += "<br>";
    }
    
    
    return cadena;
}

function buscarPorCuentaFecha(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorCuentaFecha(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorCuentaFecha()
{
    ax.setAccion("obtenerDataPorCuentaFecha");
    ax.addParamTmp("criterios", valoresBusquedaPorCuentaFecha);
    ax.consumir();
}

function onResponseGetDataGridPorCuentaFecha(data) {

    var dataTotal=data.total[0];                
    var datos=data.datos;
    
    if (!isEmptyData(datos))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoPorCuentaFecha(' + item['documentoTipo_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/        
        
        $('#datatable').dataTable({
            
            "order": [[0, "desc"]],            
            "data": datos,
            "scrollX": true,
//            "autoWidth": true,
            "columns": [
//                {"data": "fecha_creacion"},
                {"data": "fecha_emision", "width": '20px'},
                {"data": "documento_tipo_descripcion", "width": '50px'},
                {"data": "serie_numero", "width": '40px'},
                {"data": "actividad_codigo", "width": '20px'},
                {"data": "usuario_nombre", "width": '150px'},
                {"data": "persona_nombre", "width": '150px'},
                {"data": "descripcion", "width": '200px'},
                {"data": "cuenta_descripcion", "width": '80px'},
                {"data": "total_caja_ingreso",  "sClass": "alignRight"},
                {"data": "total_caja_salida",  "sClass": "alignRight"},
                {"data": "total_caja_saldo",  "sClass": "alignRight"},
                {"data": "total_bcp_ingreso",  "sClass": "alignRight"},
                {"data": "total_bcp_salida",  "sClass": "alignRight"},
                {"data": "total_bcp_saldo",  "sClass": "alignRight"},
                {"data": "total_bn_ingreso",  "sClass": "alignRight"},
                {"data": "total_bn_salida",  "sClass": "alignRight"},
                {"data": "total_bn_saldo",  "sClass": "alignRight"},
                {"data": "total_ret_ingreso",  "sClass": "alignRight"},
                {"data": "total_ret_salida",  "sClass": "alignRight"},
                {"data": "total_ret_saldo",  "sClass": "alignRight"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": [8,9,10,11,12,13,14,15,16,17,18,19]
                }, 
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data))?'':data.replace(" 00:00:00", "");
                    },
                    "targets": 0
                }
            ],
            "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
            destroy: true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(8).footer()).html((formatearNumero(dataTotal['suma_caja_ingreso'])));
                $(api.column(9).footer()).html((formatearNumero(dataTotal['suma_caja_salida'])));
                $(api.column(10).footer()).html((formatearNumero(dataTotal['suma_caja_saldo'])));
                $(api.column(11).footer()).html((formatearNumero(dataTotal['suma_bcp_ingreso'])));
                $(api.column(12).footer()).html((formatearNumero(dataTotal['suma_bcp_salida'])));
                $(api.column(13).footer()).html((formatearNumero(dataTotal['suma_bcp_saldo'])));
                $(api.column(14).footer()).html((formatearNumero(dataTotal['suma_bn_ingreso'])));
                $(api.column(15).footer()).html((formatearNumero(dataTotal['suma_bn_salida'])));
                $(api.column(16).footer()).html((formatearNumero(dataTotal['suma_bn_saldo'])));
                $(api.column(17).footer()).html((formatearNumero(dataTotal['suma_ret_ingreso'])));
                $(api.column(18).footer()).html((formatearNumero(dataTotal['suma_ret_salida'])));
                $(api.column(19).footer()).html((formatearNumero(dataTotal['suma_ret_saldo'])));
            }
//            ,fixedColumns: {
//                    leftColumns: 2
//            }
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

function verDocumentoPorCuentaFecha(documentoTipoId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorCuentaFecha");
    ax.addParamTmp("id_documentoTipo", documentoTipoId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorCuentaFecha(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['documentoTipo_descripcion'] + '</strong>';

        $('#datatableDocumentoPorCuentaFecha').dataTable({
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
        var table = $('#datatableDocumentoPorCuentaFecha').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documentoTipo.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorCuentaFechaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorCuentaFechaExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorCuentaFecha);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPorCuentaFecha();
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

function iniciarDatatable() {
    $('#datatable').dataTable({
        "scrollX": true,
        "autoWidth": true,
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true
    });
}