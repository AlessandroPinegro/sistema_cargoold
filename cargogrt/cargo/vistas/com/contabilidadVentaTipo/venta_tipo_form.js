var ventaTipoID;
$(document).ready(function () {
    loaderShow();
    ventaTipoID = document.getElementById("id").value;
    ax.setSuccess("exitoTipoVenta");
    cargarComponentes();
    configuracionesIniciales();
    //actualizarCheckbox();
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

function exitoTipoVenta(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerVentaTipoXid':
                onResponseobtenerVentaTipoXid(response.data);
                actualizarCheckbox();
                loaderClose();
                break;
            case 'guardarVentaTipo':
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

function onResponseObtenerConfiguracionesIniciales(data)
{
    //console.log(data);

    mostrarCaracteristicas(data.caracteristicas);
    mostrarDocumentosTipos(data.documentostipos);
    mostrarCaracteristicasOtros(data.caracteristicasOtros);

    if (!isEmpty(ventaTipoID))
    {
        ax.setAccion("obtenerVentaTipoXid");
        ax.addParamTmp("id", ventaTipoID);
        ax.consumir();
    }

}

function onResponseobtenerVentaTipoXid(data)
{

    var dataVentaTipo = data.dataVentaTipo;
    var dataVentaTipoCaracteristicas = data.dataVentaTipoCaracteristicas;
    var dataVentaTipoCaracteristicasOtros = data.dataVentaTipoCaracteristicasOtros;
    var dataVentaTipoDocumentos = data.dataVentaTipoDocumentos;

    var valcheckNotaCredito = false;
    var valcheckValorVenta = false;


    valcheckNotaCredito = dataVentaTipo[0]['nota_credito'] == 1 ? true : false;
    valcheckValorVenta = dataVentaTipo[0]['valor_venta_inafecto'] == 1 ? true : false;

    $('#txtCodigo').val(dataVentaTipo[0]['codigo']);
    $('#txtDescripcion').val(dataVentaTipo[0]['descripcion']);
    $('#txtCodigoExportacion').val(dataVentaTipo[0]['cod_tipo_exportacion']);

    document.getElementById('checkNotaCredito').checked = valcheckNotaCredito;
    document.getElementById('checkValorVentaInafecto').checked = valcheckValorVenta;
    select2.asignarValor('cboEstado', dataVentaTipo[0]['estado']);

//    if (valcheckNotaCredito == true)
//    {
//        $('#tablaOtros').attr('data-toggle', 'tab');
//        $('#liOtros').attr('class', 'enabled');
//    }

    //caracteristicas seleccionadas

    if (!isEmpty(dataVentaTipoCaracteristicas))
    {
        $.each(dataVentaTipoCaracteristicas, function (index, item) {
            document.getElementById("car" + item.id).checked = true;
        });
    }
    //Otros seleccionados
    if (!isEmpty(dataVentaTipoCaracteristicasOtros))
    {
        $.each(dataVentaTipoCaracteristicasOtros, function (index, item) {
            document.getElementById("car" + item.id).checked = true;
        });
    }

    //Documentos Seleccionados
    if (!isEmpty(dataVentaTipoDocumentos))
    {
        $.each(dataVentaTipoDocumentos, function (index, item) {
            document.getElementById("doc" + item.documento_tipo_id).checked = true;
        });
    }


}

function actualizarCheckbox()
{
    if ($("#checkNotaCredito").is(':checked'))
    {
        //console.log("ES notacredito");
        $('#liDocumento a').first().attr('data-toggle', 'tab');

    } else {
        //console.log("NO ES notacredito");
        $('#liDocumento a').first().attr('data-toggle', '');
        $('#tabDocumentosRequeridos').attr('class', 'tab-pane');
        $('#tabCaracteristicas').attr('class', 'tab-pane active');
        $('#liCaracteristica').attr('class', 'active');
        $('#liDocumento').attr('class', '');

        //desmarcar los documentos;
        var chekDocumentos = document.getElementsByName('chekDocumentoTipo');
        $.each(chekDocumentos, function (index, item) {
            $(this).prop('checked', false);
            //item.prop('checked' ,false);
        });
    }

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

function mostrarCaracteristicasOtros(data)
{
    $("#tbodyOtros").empty();
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

        $("#tbodyOtros").append(html);
    } else {
        $("#rowOtros").hide();
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

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/contabilidadVentaTipo/venta_tipo_listar.php";
    cargarDiv("#window", url);
}

function enviar()
{
    var codigo = $('#txtCodigo').val();
    codigo.trim();
    var descripcion = $('#txtDescripcion').val();
    descripcion.trim();
    var codigoExportacion = $('#txtCodigoExportacion').val();
    codigoExportacion.trim();
    var notaCredito = 0;
    if ($('#checkNotaCredito').is(':checked'))
    {
        notaCredito = 1;
    }
    var valorVentaInafecto = 0;
    if ($('#checkValorVentaInafecto').is(':checked'))
    {
        valorVentaInafecto = 1;
    }
    var estadoId = select2.obtenerValor('cboEstado');
    var caracteristicasSeleccionadas = new Array();
    var documentosSeleccionados = new Array();


    var chekCaracteristicas = document.getElementsByName('chekCaracteristica');
    var chekDocumentos = document.getElementsByName('chekDocumentoTipo');
    //caracteristicas seleccionadas

    $.each(chekCaracteristicas, function (index, item) {
        if (item.checked == true) {
            caracteristicasSeleccionadas.push(item.value);
        }
    });

    $.each(chekDocumentos, function (index, item) {
        if (item.checked == true) {
            documentosSeleccionados.push(item.value);
        }
    });

    //console.log(caracteristicasSeleccionadas);
    //console.log("documentos: " + documentosSeleccionados);

    guardarVentaTipo(codigo, descripcion, codigoExportacion, notaCredito, valorVentaInafecto, estadoId, caracteristicasSeleccionadas, documentosSeleccionados);
}

function guardarVentaTipo(codigo, descripcion, codigoExportacion, notaCredito, valorVentaInafecto, estadoId, caracteristicasSeleccionadas, documentosSeleccionados)
{

    loaderShow();
    ax.setAccion("guardarVentaTipo");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("codigoExportacion", codigoExportacion);
    ax.addParamTmp("notaCredito", notaCredito);
    ax.addParamTmp("valorVentaInafecto", valorVentaInafecto);
    ax.addParamTmp("estadoId", estadoId);


    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("ventaTipoId", ventaTipoID);
    ax.addParamTmp("caracteristicasSeleccionadas", caracteristicasSeleccionadas);
    ax.addParamTmp("documentosSeleccionados", documentosSeleccionados);
    //ax.addParamTmp("documentosSeleccionados", documentosSeleccionados);
    ax.consumir();

}