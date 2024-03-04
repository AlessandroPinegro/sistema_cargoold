var motivoID;
$(document).ready(function () {
    loaderShow();
    motivoID = document.getElementById("id").value;
    ax.setSuccess("exitoMotivos");
    configuracionesIniciales();
    //cargarLista();
    cargarComponentes();
});



function cargarComponentes() {
    cargarSelect2();
}

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

function configuracionesIniciales() {
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}


function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/contabilidadMotivoMovimiento/motivo_movimiento_listar.php";
    cargarDiv("#window", url);
}

function exitoMotivos(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerMotivoMovimientoXid':
                onResponseObtenerMotivoMovimientoXid(response.data);
                loaderClose();
                break;
            case 'guardarMotivoMovimiento':
                exitoGuardar(response.data.resultado);
                loaderClose();
                break;
        }
    }
}

function exitoGuardar(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/contabilidadMotivoMovimiento/motivo_movimiento_listar.php";
    cargarDiv("#window", url);
}


function onResponseObtenerConfiguracionesIniciales(data)
{
    //llenar combos
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo", "descripcion"]);
    select2.cargar("cboTipoCambio", data.dataTipoCambio, "codigo", ["codigo", "descripcion"]);
    select2.cargar("cboTipoMotivo", data.dataTipoMotivo, "codigo", ["codigo", "descripcion"]);
    select2.cargar("cboGrupo", data.dataGrupo, "codigo", ["codigo", "descripcion"]);
    select2.cargar("cboTipoCalculo", data.dataTipoCalculo, "codigo", ["codigo", "descripcion"]);

    mostrarCaracteristicas(data.caracteristicas);
    mostrarDocumentosTipos(data.documentostipos);
    
    if (!isEmpty(motivoID))
    {
        ax.setAccion("obtenerMotivoMovimientoXid");
        ax.addParamTmp("id", motivoID);
        ax.consumir();
    }
}

function onResponseObtenerMotivoMovimientoXid(data)
{
    //alert("cargando data:");
    var dataMotivoMovimiento = data.dataMotivoMovimiento;
    var dataMotivoMovimientoCaracteristicas = data.dataMotivoMovimientoCaracteristicas;
    var dataMotivoMovimientoDocumentos = data.dataMotivoMovimientoDocumentos;

    //caja de texto
    $('#txtMotivo').val(dataMotivoMovimiento[0]['codigo']);
    $('#txtDescripcion').val(dataMotivoMovimiento[0]['descripcion']);
    $('#txtNombreCorto').val(dataMotivoMovimiento[0]['nombre_corto']);

    select2.asignarValor('cboCodigoSunat', dataMotivoMovimiento[0]['sunat_tabla_detalle_id']);
    select2.asignarValor('cboTipoMotivo', dataMotivoMovimiento[0]['tipo_motivo_id']);
    select2.asignarValor('cboTipoCalculo', dataMotivoMovimiento[0]['tipo_calculo_id']);
    select2.asignarValor('cboGrupo', dataMotivoMovimiento[0]['grupo_id']);
    select2.asignarValor('cboEstado', dataMotivoMovimiento[0]['estado']);
    select2.asignarValor('cboTipoCambio', dataMotivoMovimiento[0]['tipo_cambio_id']);

    //console.log(dataMotivoMovimiento[0]['sunat_tabla_detalle_id']);



    //caracteristicas seleccionadas

    if (!isEmpty(dataMotivoMovimientoCaracteristicas))
    {
        $.each(dataMotivoMovimientoCaracteristicas, function (index, item) {
            document.getElementById("car" + item.caracteristica_id).checked = true;
        });
    }
    
    //documentos seleccionados
    
    if(!isEmpty(dataMotivoMovimientoDocumentos))
    {
        $.each(dataMotivoMovimientoDocumentos, function (index, item){
           document.getElementById("doc"+ item.documento_tipo_id).checked = true; 
           console.log(item.documento_tipo_id);
        });
    }
}

function enviar()
{
    //get textboxes
    var codigomotivo = $("#txtMotivo").val();
    codigomotivo.trim();
    var descripcion = $("#txtDescripcion").val();
    descripcion.trim();
    var nombrecorto = $("#txtNombreCorto").val();
    nombrecorto.trim();

    //get cboxes
    var tipoMotivoId = select2.obtenerValor('cboTipoMotivo');
    var tipoCalculoId = select2.obtenerValor('cboTipoCalculo');
    var tipoCambioId = select2.obtenerValor('cboTipoCambio');
    var grupoId = select2.obtenerValor('cboGrupo');
    var estadoId = select2.obtenerValor('cboEstado');
    var codigoSunatId=select2.obtenerValor('cboCodigoSunat');

    var caracteristicasSeleccionadas = new Array();
    var documentosSeleccionados = new Array();

    var chekCaracteristicas = document.getElementsByName('chekCaracteristica');
    var chekDocumentoTipo = document.getElementsByName('chekDocumentoTipo');

    $.each(chekCaracteristicas, function (index, item) {
        if (item.checked == true) {
            caracteristicasSeleccionadas.push(item.value);
        }
    });
    
    $.each(chekDocumentoTipo, function(index, item){
       if(item.checked == true)
       {
           documentosSeleccionados.push(item.value);
       }
    });
    
    guardarMotivoMovimiento(codigomotivo, descripcion, nombrecorto, tipoMotivoId, tipoCalculoId, tipoCambioId, grupoId, codigoSunatId, estadoId, caracteristicasSeleccionadas, documentosSeleccionados);
}

function guardarMotivoMovimiento(codigo, descripcion, nombreCorto, tipoMotivoId, tipoCalculoId, tipoCambioId, grupoId, codigoSunatId, estadoId, caracteristicasSeleccionadas, documentosSeleccionados)
{

    loaderShow();
    ax.setAccion("guardarMotivoMovimiento");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("nombreCorto", nombreCorto);
    ax.addParamTmp("tipoMotivoId", tipoMotivoId);
    ax.addParamTmp("tipoCalculoId", tipoCalculoId);
    ax.addParamTmp("tipoCambioId", tipoCambioId);
    ax.addParamTmp("grupoId", grupoId);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("estadoId", estadoId);

    ax.addParamTmp("caracteristicasSeleccionadas", caracteristicasSeleccionadas);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("motivoId", motivoID);
    ax.addParamTmp("documentosSeleccionados", documentosSeleccionados);
    ax.consumir();

}


function mostrarCaracteristicas(data)
{
    $("#tbodyCaracteristicas").empty();
    var html = '';

    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            if (index % 2 == 0) {
                html += '<tr>';
            }
            html += '<td>' +
                    '<div class="checkbox" style="margin: 0px;">' +
                    '<label class="cr-styled">' +
                    '<input onclick="" type="checkbox" name="chekCaracteristica" id="car' + item.id + '" value="' + item.id + '">' +
                    '<i class="fa"></i> ' +
                    item.descripcion +
                    '</label>' +
                    ' </div>' +
                    '</td>';
            if (index % 2 == 1) {
                html += '</tr>';
            }
        });

        $("#tbodyCaracteristicas").append(html);
    } else {
        $("#rowCaracteristicas").hide();
    }
}

function mostrarDocumentosTipos(data)
{
    $("#tbodyDocumentosTipos").empty();
    var html = '';

    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            if (index % 2 == 0) {
                html += '<tr>';
            }
            html += '<td>' +
                    '<div class="checkbox" style="margin: 0px;">' +
                    '<label class="cr-styled">' +
                    '<input onclick="" type="checkbox" name="chekDocumentoTipo" id="doc' + item.id + '" value="' + item.id + '">' +
                    '<i class="fa"></i> ' +
                    item.descripcion +
                    '</label>' +
                    ' </div>' +
                    '</td>';
            if (index % 2 == 1) {
                html += '</tr>';
            }
        });

        $("#tbodyDocumentosTipos").append(html);
    } else {
        $("#rowDocumentosTipos").hide();
    }
}