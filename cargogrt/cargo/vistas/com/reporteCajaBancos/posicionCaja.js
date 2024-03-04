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

function onResponseObtenerConfiguracionesIniciales(data) {
    dataConfiguracionInicial = data;
    if (!isEmpty(data.fecha_primer_documento[0]['fecha_actual']))
    {
        $('#fechaEmision').val(formatearFechaBDCadena(data.fecha_primer_documento[0]['fecha_actual']['date']));
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
    
    select2.cargar("cboAgencia", data.dataAgenciaUsuario, "id", "codigo");
    select2.asignarValorTrigger("cboAgencia", data.dataAgenciaUsuario[0]['id']);
    
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

  $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;'>Fecha pago</th>" +
            "<th style='text-align:center;'>Tipo documento</th>" +
            "<th style='text-align:center;'width=100px>S|N</th>" +
            "<th style='text-align:center;'width=100px>Agencia</th>" +
            "<th style='text-align:center;'width=100px>Caja</th>" +
              "<th style='text-align:center;'width=100px>Tipo doc. pago</th>" +
               "<th style='text-align:center;'width=100px>Cliente/Proveedor</th>" +
                "<th style='text-align:center;'width=100px>Actividad</th>" +
                 "<th style='text-align:center;'width=100px>Cuenta</th>" +
                  "<th style='text-align:center;'width=100px>Total</th>" +
              
         
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
     

            cuerpo = '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-07 15:36:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. Boleta' + '</td>' +
                     '<td style="text-align:left;">' + 'BT01-000006' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'IMAGINA TECHNOLOGIES S.A.C.' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'Caja chica' + '</td>' +
                           '<td style="text-align:left;">' + '300.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>' + 
                    '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-07 18:36:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. FACTURA' + '</td>' +
                     '<td style="text-align:left;">' + 'FT01-000006' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'MAPFRE PERU COMPAÑIA' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'BCP SOLES 520-20113-202' + '</td>' +
                           '<td style="text-align:left;">' + '800.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-08 10:36:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. Boleta' + '</td>' +
                     '<td style="text-align:left;">' + 'BT01-000007' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'EMPRESA EDITORA EL COMERCIO S.A' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'BCP SOLES 520-20113-202' + '</td>' +
                           '<td style="text-align:left;">' + '900.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-09 12:36:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. Factura' + '</td>' +
                     '<td style="text-align:left;">' + 'FT01-000007' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'EDUARDO RUBIO CONTRERAS' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'BCP SOLES 520-20113-202' + '</td>' +
                           '<td style="text-align:left;">' + '1300.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-09 16:32:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. Boleta' + '</td>' +
                     '<td style="text-align:left;">' + 'BT01-000008' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'IMAGINA TECHNOLOGIES S.A.C.' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'Caja chica' + '</td>' +
                           '<td style="text-align:left;">' + '300.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">' + '2022-11-10 15:36:18'	 + '</td>' +
                    '<td style="text-align:left;">' + 'V. Factura' + '</td>' +
                     '<td style="text-align:left;">' + 'FT01-000008' + '</td>' +
                      '<td style="text-align:left;">' + 'TRUJILLO' + '</td>' +
                       '<td style="text-align:left;">' + 'CAJA 1' + '</td>' +
                        '<td style="text-align:left;">' + 'Efectivo' + '</td>' +
                         '<td style="text-align:left;">' + 'COMPAÑIA MINERA' + '</td>' +
                          '<td style="text-align:left;">' + 'Ingreso por ventas' + '</td>' +
                           '<td style="text-align:left;">' + 'Caja chica' + '</td>' +
                           '<td style="text-align:left;">' + '2300.00' + '</td>' +
         
//                    '<a onclick="editarPersonaClase(' + item.persona_clase_id + ', \'' + item.persona_clase_descripcion + '\', \'' + item.persona_tipo_id + '\',' + item.persona_clase_estado + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n' +
//                    '<a onclick="confirmarDeletePersonaClase(' + item.persona_clase_id + ',\'' + item.persona_clase_descripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>' +
                    '</td>' +
                    
                    '</tr>' ;
            cuerpo_total = cuerpo_total + cuerpo;
    
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable").append(html);
    loaderClose();
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