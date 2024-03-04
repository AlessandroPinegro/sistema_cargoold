$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReporteCierreCaja");
    obtenerConfiguracionesInicialesCierreCaja();

    
});

var dataConfiguracionInicial;
function onResponseReporteCierreCaja(response) {
   console.log(response);
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesCierreCaja':
                onResponseObtenerConfiguracionesIniciales(response.data);
//                loaderClose();
                break;
            case 'obtenerDataCierreCaja':
                onResponseGetDataGridCierreCaja(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoCierreCaja':
                onResponseDocumentoCierreCaja(response.data);
                loaderClose();
                break;
            case 'obtenerReporteCierreCajaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReporteCierreCajaExcel':
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesCierreCaja()
{
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesCierreCaja");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function validardate (){
    var fechaInicioInput = document.getElementById('fechaEmision'); 
    console.log(fechaInicioInput.value); 
}

function onResponseObtenerConfiguracionesIniciales(data) {
   
    dataConfiguracionInicial = data;
//:::CAMBIOS JESUS
    if(!isEmpty(data.usuario.dataUsuario)){
        select2.cargar("cboUsuario", data.usuario.dataUsuario, "usuario_id", "usuario"  );
    }
//:::CAMBIOS JESUS

    if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
    {
        $('#fechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']));
    }

    if (!isEmpty(data.cuenta)) {
        select2.cargar("cboCuenta", data.cuenta, "id", "descripcion_numero");
    }

    if (!isEmpty(data.actividad_tipo)) {
        select2.cargar("cboActividadTipo", data.actividad_tipo, "id", "descripcion");
    }

    if (!isEmpty(data.actividad)) {
        select2.cargar("cboActividad", data.actividad, "id", ["codigo", "descripcion"]);
    }

    if (!isEmpty(data.dataAgenciaUsuario)) {
        select2.cargar("cboAgencia", data.dataAgenciaUsuario, "id", "codigo");
        select2.asignarValorTrigger("cboAgencia", data.dataAgenciaUsuario[0]['id']);
    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Actualmente no tiene ninguna agencias asignada");
    }
    loaderClose();
}

var valoresBusquedaCierreCaja = [{actividad: "", actividadTipo: "", fechaEmision: "", cuenta: "", cajaId: ""}];

function cargarDatosBusqueda()
{
    var actividad = $('#cboActividad').val();
    var actividadTipo = $('#cboActividadTipo').val();
    var cuenta = $('#cboCuenta').val();
    var fechaEmision = $('#fechaEmision').val();
    var cajaId = select2.obtenerValor("cboCaja");

    valoresBusquedaCierreCaja[0].actividad = actividad;
    valoresBusquedaCierreCaja[0].actividadTipo = actividadTipo;
    valoresBusquedaCierreCaja[0].cuenta = cuenta;
    valoresBusquedaCierreCaja[0].fechaEmision = fechaEmision;
    valoresBusquedaCierreCaja[0].empresaId = commonVars.empresa;
    valoresBusquedaCierreCaja[0].cajaId = cajaId;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();


    if (!isEmpty(valoresBusquedaCierreCaja[0].actividad))
    {
        cadena += negrita("Actividad: ");
        cadena += select2.obtenerTextMultiple('cboActividad');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaCierreCaja[0].actividadTipo))
    {
        cadena += negrita("Actividad Tipo: ");
        cadena += select2.obtenerTextMultiple('cboActividadTipo');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaCierreCaja[0].cuenta))
    {
        cadena += negrita("Cuenta: ");
        cadena += select2.obtenerTextMultiple('cboCuenta');
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaCierreCaja[0].fechaEmision))
    {
        cadena += StringNegrita("Fecha pago: ");
        cadena += valoresBusquedaCierreCaja[0].fechaEmision;
        cadena += "<br>";
    }

    if (!isEmpty(valoresBusquedaCierreCaja[0].cajaId))
    {
        cadena += StringNegrita("Caja: ");
        cadena += select2.obtenerText('cboCaja');
        cadena += "<br>";
    }

    return cadena;
}

function buscarCierreCaja(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBusquedaCierreCaja(cadena);

    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBusquedaCierreCaja()
{
    ax.setAccion("obtenerDataCierreCaja");
    ax.addParamTmp("criterios", valoresBusquedaCierreCaja);
    ax.consumir();
}

function onResponseGetDataGridCierreCaja(data) {
//    console.log(data);

    var dataTotal = data.total[0];
    var datos = data.datos;
    // console.log(datos);
    if (!isEmptyData(datos))
    {
         
        /*$.each(data, function (index, item) {
         data[index]["opciones"] = '<a onclick="verDocumentoCierreCaja(' + item['documentoTipo_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
         });*/
        //Fecha creación	Tipo documento	S|N	Tipo doc. pago	S|N pago	Cliente/Proveedor	COD	Actividad	Cuenta	Total

        $('#datatable').dataTable({

            "order": [[0, "asc"]],
            "data": datos,
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_creacion"},
                {"data": "documento_tipo_descripcion", "width": '50px'},
//                {"data": "fecha_emision", "width": '20px'},
                {"data": "serie_numero", "width": '40px'},
                {"data": "documento_tipo_desc_pago", "width": '50px'},
                {"data": "serie_numero_pago", "width": '40px'},
            //    {"data": "usuario_nombre", "width": '150px'},
                {"data": "persona_nombre", "width": '150px'},
//                {"data": "descripcion", "width": '200px'},
                {"data": "actividad_codigo", "width": '20px'},
                {"data": "actividad_descripcion", "width": '20px'},
                {"data": "cuenta_descripcion", "width": '80px'},
                {"data": "total_conversion", "sClass": "alignRight"},
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return (!isEmpty(data) ? data.substring(0, 19) : '');
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 9
                },
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                    },
                    "targets": 1
                }
            ],
            "initComplete": function(settings, json) {
                var api = this.api();
        
                // Agrega el encabezado general
                var generalHeader = $('<th colspan="10" class="general-header"> <strong> Usuario: '  + datos[1]['usuario_nombre'] + ' </strong> </th>');
                $(api.table().container()).prepend(generalHeader);
        
                // Estilo adicional para el encabezado general
                $('.general-header').css({
                    'text-align': 'center',
                    'font-size': '18px',
                    'padding': '10px',
                    'border-bottom': '2px solid #ddd'
                });
            },
            "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
            destroy: true,
            footerCallback: function (row, data, start, end, display) {
                var api = this.api(), data;
                $(api.column(9).footer()).html((formatearNumero(dataTotal['total'])));
            }
//            ,fixedColumns: {
//                    leftColumns: 2
//            }
        });
    } else
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

function verDocumentoCierreCaja(documentoTipoId, /*organizadorId,*/ fechaInicio, fechaFin)
{
    loaderShow();
    ax.setAccion("obtenerDocumentoCierreCaja");
    ax.addParamTmp("id_documentoTipo", documentoTipoId);
    //ax.addParamTmp("id_organizador", organizadorId);
    ax.addParamTmp("fecha_inicio", fechaInicio);
    ax.addParamTmp("fecha_fin", fechaFin);
    ax.consumir();
}
// , "width": "50px"
function onResponseDocumentoCierreCaja(data)
{
    if (!isEmptyData(data))
    {
        $('[data-toggle="popover"]').popover('hide');
        var stringTituloStock = '<strong> ' + data[0]['documentoTipo_descripcion'] + '</strong>';

        $('#datatableDocumentoCierreCaja').dataTable({
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
    } else
    {
        var table = $('#datatableDocumentoCierreCaja').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documentoTipo.")
    }
}

var actualizandoBusqueda = false;
function exportarReporteCierreCajaExcel()
{
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteCierreCajaExcel");
    ax.addParamTmp("criterios", valoresBusquedaCierreCaja);
    ax.consumir();
}

function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarCierreCaja();
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


function onChangeCboAgencia(valor) {
    if (!isEmpty(valor) && !isEmpty(dataConfiguracionInicial.dataCajaUsuario)) {
        let dataCajaFilter = dataConfiguracionInicial.dataCajaUsuario.filter(item => item.agencia_id == valor);
        select2.cargar("cboCaja", dataCajaFilter, "id", "codigo");
        select2.asignarValor("cboCaja", dataCajaFilter[0].id);
        if (dataCajaFilter.length == 1) {
            $("#cboCaja").prop('disabled', true);
        } else {
            $("#cboCaja").prop('disabled', false);
        }
        buscarCierreCaja(1);
    }
}

function onChangeCboCaja() {
    buscarCierreCaja(1);
}