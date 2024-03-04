$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorCuenta");
    obtenerConfiguracionesInicialesPorCuenta();
    iniciarDatatable();
});

function onResponseReportePorCuenta(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesPorCuenta':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataPorCuenta':
                onResponseGetDataGridPorCuenta(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoPorCuenta':
                onResponseDocumentoPorCuenta(response.data);
                loaderClose();
                break;
            case 'obtenerReportePorCuentaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorCuentaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesPorCuenta()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesPorCuenta");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');
//    var date = new Date();
//    var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
//    var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
//    
//    primerDia=primerDia.getDate();
//    if(primerDia<10) {
//        primerDia='0'+primerDia;
//    }     
//    
//    $('#inicioFechaEmision').val(primerDia+"/"+(date.getMonth()+1)+"/"+date.getFullYear());
//    $('#finFechaEmision').val(ultimoDia.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear());
//    
    
    var dataMes = [ {id: 1, descripcion: "Enero"},
                    {id: 2, descripcion: "Febrero"},
                    {id: 3, descripcion: "Marzo"},
                    {id: 4, descripcion: "Abril"},
                    {id: 5, descripcion: "Mayo"},
                    {id: 6, descripcion: "Junio"},
                    {id: 7, descripcion: "Julio"},
                    {id: 8, descripcion: "Agosto"},
                    {id: 9, descripcion: "Setiembre"},
                    {id: 10, descripcion: "Octubre"},
                    {id: 11, descripcion: "Noviembre"},
                    {id: 12, descripcion: "Diciembre"}
                  ];
    
    select2.cargar("cboMes", dataMes, "id", "descripcion");
    var hoy = new Date();
    var mm = hoy.getMonth()+1; //hoy es 0!       
    var anioActual = hoy.getFullYear();
    
    select2.asignarValor("cboMes",mm);
    
    
    //anio
    var fechaPrimera=data.fecha_primer_documento[0]['primera_fecha'];
    var fechaPartes = fechaPrimera.split("-");
    var anioInicial=parseInt(fechaPartes[0]);
    
    var string ='';
    
    for (var i = 0; i <= (anioActual - anioInicial); i++)
    {
        string += '<option value="' + (anioInicial + i) + '">' + (anioInicial + i) + '</option>';
    }
    $('#cboAnio').append(string);
    
    select2.asignarValor("cboAnio",anioActual);
    //fin anio
    
    if (!isEmpty(data.documento_tipo)) {
        select2.cargar("cboTipoDocumento", data.documento_tipo, "id", "descripcion");
    }
    
    if (!isEmpty(data.cuenta)) {
        select2.cargar("cboCuenta", data.cuenta, "id", "descripcion_numero");
    }    
    
    if (!isEmpty(data.empresa)) {
        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");
    }
    
    loaderClose();
}

var valoresBusquedaPorCuentaGeneral = [{documentoTipo: "",  mes: "",anio: "",cuenta:"",empresaId:""}];

function cargarDatosBusqueda()
{

    var documentoTipo = $('#cboTipoDocumento').val();
    var cuenta = $('#cboCuenta').val();
    var mes = $('#cboMes').val();
    var anio = $('#cboAnio').val();
    var empresa = select2.obtenerValor("cboEmpresa");


    valoresBusquedaPorCuentaGeneral[0].documentoTipo = documentoTipo;
    valoresBusquedaPorCuentaGeneral[0].cuenta = cuenta;
    valoresBusquedaPorCuentaGeneral[0].mes = mes;
    valoresBusquedaPorCuentaGeneral[0].anio = anio;
    valoresBusquedaPorCuentaGeneral[0].empresaId = empresa;
    
    console.log(valoresBusquedaPorCuentaGeneral);
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();
    
    if (!isEmpty(valoresBusquedaPorCuentaGeneral[0].mes))
    {
        cadena += negrita("Mes: ");
        cadena += select2.obtenerText('cboMes');
        cadena += "<br>";
    }
    
    if (!isEmpty(valoresBusquedaPorCuentaGeneral[0].anio))
    {
        cadena += negrita("Anio: ");
        cadena += select2.obtenerText('cboAnio');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorCuentaGeneral[0].documentoTipo))
    {
        cadena += negrita("Tipo documento: ");
        cadena += select2.obtenerTextMultiple('cboTipoDocumento');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaPorCuentaGeneral[0].cuenta))
    {
        cadena += negrita("Cuenta: ");
        cadena += select2.obtenerTextMultiple('cboCuenta');
        cadena += "<br>";
    }
    
    if (!isEmpty(valoresBusquedaPorCuentaGeneral[0].empresaId))
    {
        cadena += negrita("Empresa: ");
        cadena += select2.obtenerText('cboEmpresa');
        cadena += "<br>";
    }
    
    
    return cadena;
}

function buscarPorCuenta(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaPorCuenta(cadena);
    
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaPorCuenta()
{
    ax.setAccion("obtenerDataPorCuenta");
    ax.addParamTmp("criterios", valoresBusquedaPorCuentaGeneral);
    ax.consumir();
}

function onResponseGetDataGridPorCuenta(data) {

    var dataTotal=data.total[0];                
    var datos=data.datos;
    
    if (!isEmptyData(datos))
    {
        /*$.each(data, function (index, item) {
            data[index]["opciones"] = '<a onclick="verDocumentoPorCuenta(' + item['documentoTipo_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
        });*/        
        
        $('#datatable').dataTable({
            
            "order": [[0, "desc"]],            
            "data": datos,
            "scrollX": true,
            "autoWidth": true,
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

function verDocumentoPorCuenta(documentoTipoId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoPorCuenta");
    ax.addParamTmp("id_documentoTipo", documentoTipoId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoPorCuenta(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['documentoTipo_descripcion'] + '</strong>';

        $('#datatableDocumentoPorCuenta').dataTable({
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
        var table = $('#datatableDocumentoPorCuenta').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documentoTipo.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorCuentaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorCuentaExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorCuentaGeneral);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarPorCuenta();
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