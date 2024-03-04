
var subdiarioId;
$(document).ready(function () {
    loaderShow();
    subdiarioId = document.getElementById("id").value;
    ax.setSuccess("exitoSubdiarios");
    configuracionesIniciales();
    cargarLista();
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

function cargarLista()
{
    ax.setAccion("obtenerSubdiarioNumeracionXid");
    ax.addParamTmp("subdiarioId", subdiarioId);
    ax.consumir();
}

function exitoSubdiarios(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'guardarSubdiario':
                exitoGuardar(response.data.resultado);
                loaderClose();
                break;
            case 'obtenerSubdiarioXid':
                onResponseObtenerSubdiariosXid(response.data);
                loaderClose();
                break;
            case 'obtenerSubdiarioNumeracionXid':
                onResponseObtenerSubdiarioNumeracionXid(response.data);
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
    var url = URL_BASE + "vistas/com/contabilidadSubdiarios/subdiarios_listar.php";
    cargarDiv("#window", url);
}

function onResponseObtenerSubdiarioNumeracionXid(data)
{
    listarSubdiarioNumeracion(data);
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data);        

    select2.cargar("cboTipoCambio", data.dataTipoCambio, "codigo", ["codigo", "descripcion"]);
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo", "descripcion"]);
    select2.cargar("cboTipoAsientos", data.dataTipoAsientos, "codigo", ["codigo", "descripcion"]);
    select2.cargarAsignaUnico("cboSucursal", data.dataSucursal, "id", "nombre");

    dibujarCaracteristicas(data.caracteristicas);


    //traemos la data del subdiario
    if (!isEmpty(subdiarioId)) {
        ax.setAccion("obtenerSubdiarioXid");
        ax.addParam("id", subdiarioId);
        ax.consumir();
    }
}

function onResponseObtenerSubdiariosXid(data) {
//    console.log(data);
    var dataSubdiario = data.dataSubdiario;
    var dataSubdiarioCaracteristica = data.dataSubdiarioCaracteristica;

    //caja de texto
    $('#txtCodigo').val(dataSubdiario[0]['codigo']);
    $('#txtDescripcion').val(dataSubdiario[0]['descripcion']);

    //combos
    select2.asignarValor('cboTipoCambio', dataSubdiario[0]['tipo_cambio']);
    select2.asignarValor('cboCodigoSunat', dataSubdiario[0]['sunat_tabla_detalle_id']);
    select2.asignarValor('cboEstado', dataSubdiario[0]['estado']);
    select2.asignarValor('cboTipoAsientos', dataSubdiario[0]['tipo_asiento_id']);
    select2.asignarValor('cboSucursal', dataSubdiario[0]['sucursal_id']);

    //caracteristicas seleccionadas
    $.each(dataSubdiarioCaracteristica, function (index, item) {
        document.getElementById("car" + item.caracteristica_id).checked = true;
    });
}

function dibujarCaracteristicas(data) {
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

function listarSubdiarioNumeracion(data)
{
    $("#tbodySubdiarioNumeracion").empty();
    var html = '';
    html += '<tr>' +
            '<th style="text-align: center;">Período </th>' +
            '<th style="text-align: center;">Numerador </th>' +
            '</tr>';
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {

            html += '<tr>' +
                    '<td>' + item.periodo + '</td>' +
                    '<td>' + item.numerador + '</td></tr>'
        });
        $("#tbodySubdiarioNumeracion").append(html);
    } else {
        $("#rowNumeracion").hide();
    }
}

function enviar() {
    //caja de texto
    var codigo = $('#txtCodigo').val();
    codigo = codigo.trim();
    var descripcion = $('#txtDescripcion').val();
    descripcion = descripcion.trim();

    //combos
    var tipoCambioId = select2.obtenerValor('cboTipoCambio');
    var codigoSunatId = select2.obtenerValor('cboCodigoSunat');
    var estadoId = select2.obtenerValor('cboEstado');
    var tipoAsientoId = select2.obtenerValor('cboTipoAsientos');
    var sucursalId = select2.obtenerValor('cboSucursal');

    //obtener las caracteristicas seleccionadas
    var caracteristicasSeleccionadas = new Array();

    var chekCaracteristicas = document.getElementsByName('chekCaracteristica');

    $.each(chekCaracteristicas, function (index, item) {
        if (item.checked == true) {
            caracteristicasSeleccionadas.push(item.value);
        }
    });
//    console.log(caracteristicasSeleccionadas);    

    if (validarFormulario(codigo, descripcion, tipoCambioId, estadoId, sucursalId)) {
        guardarSubdiario(codigo, descripcion, tipoCambioId, codigoSunatId, estadoId, tipoAsientoId, sucursalId,
                caracteristicasSeleccionadas);
    }
}

function guardarSubdiario(codigo, descripcion, tipoCambioId, codigoSunatId, estadoId, tipoAsientoId, sucursalId, caracteristicasSeleccionadas)
{

    loaderShow();
    ax.setAccion("guardarSubdiario");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("tipoCambioId", tipoCambioId);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("estadoId", estadoId);
    ax.addParamTmp("tipoAsientoId", tipoAsientoId);
    ax.addParamTmp("sucursalId", sucursalId);
    ax.addParamTmp("caracteristicasSeleccionadas", caracteristicasSeleccionadas);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("subdiarioId", subdiarioId);
    ax.consumir();

}

function validarFormulario(codigo, descripcion, tipoCambioId, estadoId, sucursalId) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();

    if (codigo === "" || codigo === null || espacio.test(codigo) || codigo.length === 0)
    {
        $("#msjCodigo").text("Ingrese un código").show();
        bandera = false;
    }

    if (descripcion === "" || descripcion === null || espacio.test(descripcion) || descripcion.length === 0) {
        $("#msjDescripcion").text("Ingrese descripción").show();
        bandera = false;
    }

    if (estadoId === "" || estadoId === null || espacio.test(estadoId) || estadoId.length === 0) {
        $("#msjEstado").text("Seleccione un estado").show();
        bandera = false;
    }

    if (tipoCambioId === "" || tipoCambioId === null || espacio.test(tipoCambioId) || tipoCambioId.length === 0) {
        $("#msjTipoCambio").text("Seleccione tipo de cambio").show();
        bandera = false;
    }

    if (sucursalId === "" || sucursalId === null || espacio.test(sucursalId) || sucursalId.length === 0) {
        $("#msjSucursal").text("Seleccione sucursal").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajes() {
    $("#msjCodigo").hide();
    $("#msjDescripcion").hide();
    $("#msjTipoCambio").hide();
    $("#msjCodigoSunat").hide();
    $("#msjEstado").hide();
    $("#msjTipoAsientos").hide();
    $("#msjSucursal").hide();
}