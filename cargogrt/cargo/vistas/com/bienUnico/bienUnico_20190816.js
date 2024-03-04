$(document).ready(function () {
//    loaderShow();
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    controlarDivTablaDetalle(1);
    ax.setSuccess("onResponseReporteBienUnico");
    obtenerConfiguracionesInicialesBienUnico();
});

function onResponseReporteBienUnico(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesBienUnico':
                onResponseObtenerConfiguracionesIniciales(response.data);
                validarToken();
                break;
            case 'obtenerBienTipoHijo':
                onResponseObtenerBienTipoHijo(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleBienUnico':
                onResponseObtenerDetalleBienUnico(response.data);
                loaderClose();
                break;
            case 'obtenerBienUnicoXId':
                onResponseObtenerBienUnicoXId(response.data);
                loaderClose();
                break;
            case 'verDetalleDocumento':
                onResponseVerDetallePorCliente(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoDetalleXId':
                onResponseObtenerDocumentoDetalleXId(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {

        }
    }
}

function obtenerConfiguracionesInicialesBienUnico()
{
    ax.setAccion("obtenerConfiguracionesInicialesBienUnico");
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
    //alert('reporte servicio');

    if (!isEmpty(data.bien_tipo)) {
        select2.cargar("cboBienTipoPadre", data.bien_tipo, "id", ["codigo", "descripcion"]);
        select2.asignarValor("cboBienTipoPadre", null);
    }

    if (!isEmpty(data.personaProveedor)) {
        select2.cargar("cboProveedor", data.personaProveedor, "id", ["codigo_identificacion", "persona_nombre"]);
    }

    if (!isEmpty(data.personaCliente)) {
        select2.cargar("cboCliente", data.personaCliente, "id", ["codigo_identificacion", "persona_nombre"]);
    }

    if (!isEmpty(data.bien)) {
        select2.cargar("cboBien", data.bien, "id", ["codigo", "descripcion"]);
    }

    loaderClose();
}

var valoresBusquedaBienUnico = [{bienTipoPadre: "", bienTipo: "", bien: "", nroGuia: "", fechaGuia: "", proveedor: "", cliente: "", prodUnico: ""}];

function cargarDatosBusqueda() {
    var bienTipoPadre = $('#cboBienTipoPadre').val();
    var bienTipo = $('#cboBienTipo').val();
    var bien = $('#cboBien').val();
    var nroGuia = $('#txtNroGuia').val();
    var fechaGuia = $('#txtFechaEmision').val();
    var proveedor = $('#cboProveedor').val();
    var cliente = $('#cboCliente').val();
    var prodUnico = $('#txtProdUnico').val();
    var posInicial = $('#txtPosicionInicial').val();
    var estadoBienUnico=select2.obtenerValor('cboEstadoBienUnico');

    valoresBusquedaBienUnico[0].bienTipoPadre = bienTipoPadre;
    valoresBusquedaBienUnico[0].bienTipo = bienTipo;
    valoresBusquedaBienUnico[0].bien = bien;
    valoresBusquedaBienUnico[0].nroGuia = nroGuia.trim();
    valoresBusquedaBienUnico[0].fechaGuia = fechaGuia;
    valoresBusquedaBienUnico[0].proveedor = proveedor;
    valoresBusquedaBienUnico[0].cliente = cliente;
    valoresBusquedaBienUnico[0].prodUnico = prodUnico.trim();
    valoresBusquedaBienUnico[0].posInicial = posInicial;
    valoresBusquedaBienUnico[0].estadoBienUnico=estadoBienUnico;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

//    if (!isEmpty(valoresBusquedaBienUnico[0].bienTipoPadre))
//    {
//        cadena += negrita("Grupo de producto principal: ");
//        cadena += select2.obtenerTextMultiple('cboBienTipoPadre');
//        cadena += "<br>";
//    }
    if (!isEmpty(valoresBusquedaBienUnico[0].estadoBienUnico))
    {
        cadena += negrita("Estado: ");
        cadena += select2.obtenerText('cboEstadoBienUnico');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].bienTipo))
    {
        cadena += negrita("Grupo de producto: ");
        cadena += select2.obtenerTextMultiple('cboBienTipo');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].bien))
    {
        cadena += negrita("Producto: ");
        cadena += select2.obtenerTextMultiple('cboBien');
        cadena += "<br>";
    }
    var band = false;
    if (!isEmpty(valoresBusquedaBienUnico[0].nroGuia))
    {
        cadena += negrita("Nro guía: ");
        cadena += valoresBusquedaBienUnico[0].nroGuia + '&nbsp&nbsp&nbsp';
        band = true;
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].fechaGuia))
    {
        cadena += negrita("Fecha guía: ");
        cadena += valoresBusquedaBienUnico[0].fechaGuia;
        band = true;
    }
    if (band) {
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].proveedor))
    {
        cadena += negrita("Proveedor: ");
        cadena += select2.obtenerTextMultiple('cboProveedor');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].cliente))
    {
        cadena += negrita("Cliente: ");
        cadena += select2.obtenerTextMultiple('cboCliente');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaBienUnico[0].prodUnico))
    {
        cadena += negrita("Prod. Único: ");
        cadena += valoresBusquedaBienUnico[0].prodUnico;
        cadena += "<br>";
    }

    return cadena;
}

function buscarBienUnico(colapsa) {
    loaderShow();
    var cadena = "";
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    } else {
        $('#idPopover').attr("data-content", '');
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;

    obtenerDataBienUnico();

    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDataBienUnico() {
    ax.setAccion("obtenerDataBienUnico");
    ax.addParamTmp("criterios", valoresBusquedaBienUnico);
    $('#dataTableProductoUnico').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "autoWidth": true,
        "order": [[0, "desc"]],
//         "lengthMenu": [[1, 2, 3], [1, 2, 3]],
        "columns": [
//Prod. Único	Producto	G.P. Principal	G.P. Secundario	Fecha	S|N Guía	Proveedor	Cliente	Acc.            
            {"data": "codigo_unico", "width": '250px'},
            {"data": "bien_codigo_descripcion", "width": '250px'},
//            {"data": "bien_tipo_padre_descripcion"},
            {"data": "bien_tipo_descripcion", "width": '100px'},
            {"data": "fecha_emision", "width": '70px'},
            {"data": "serie_numero", "class": "alignCenter", "width": '60px'},
            {"data": "persona_nombre_completo", "width": '150px'},
            {"data": "cliente_nombre_completo", "width": '150px'},
            {"data": "documento_guia_remision_id", "width": '100px'},
            {"data": "bien_unico_id", "class": "alignCenter", "width": '40px'}
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : datex.parserFecha(data.replace(" 00:00:00", ""));
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    var html = '<a onclick="verDetalleBienUnico(' + data + ')" title="Ver detalle del producto" href="#divContenido"><i class="ion-cube" style="color: #615ca8"></i></a>&nbsp;&nbsp;&nbsp;' +
                            '<a onclick="imprimirCodigoUnico(' + data + ')"  title="Imprimir QR"><i class="fa fa-print" style="color:green;"></i></a>&nbsp;&nbsp;&nbsp;' +
                            '<a onclick="verDetalleDocumento(' + row.documento_id + ',' + row.movimiento_id + ')"  title="Ver detalle del documento"><i class="fa fa-eye" style="color:#1ca8dd;"></i></a>';
                    return html;
                },
                "targets": 8
            },
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? 'Disponible' : 'Atendido';
                },
                "targets": 7
            }
        ],
        "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
        destroy: true
    });

    controlarDivTablaDetalle(1);
    setTimeout(function () {
        loaderClose();
    }, 500);
}


function loaderBuscar()
{
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarBienUnico();
    }
    loaderClose();
}

var actualizandoBusqueda = false;
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

function obtenerBienTipoHijo() {
    var bienTipoPadreId = $('#cboBienTipoPadre').val();

    loaderShow();
    ax.setAccion("obtenerBienTipoHijo");
    ax.addParamTmp("bienTipoPadreId", bienTipoPadreId);
    ax.consumir();
}

function onResponseObtenerBienTipoHijo(data) {
    select2.cargar("cboBienTipo", data, "id", ["codigo", "descripcion"]);
    select2.asignarValor("cboBienTipo", null);
}

function verDetalleBienUnico(bienUnicoId) {
    loaderShow();
    ax.setAccion("obtenerDetalleBienUnico");
    ax.addParamTmp("bienUnicoId", bienUnicoId);
    ax.consumir();
}

function onResponseObtenerDetalleBienUnico(data) {
    if (!isEmptyData(data)) {
        controlarDivTablaDetalle(2);

        $('[data-toggle="popover"]').popover('hide');
        var titulo = data[0]['bien_codigo'] + ' | ' + data[0]['bien_descripcion'] + ': <br><b>' + data[0]['bien_unico_codigo'] + ' </b>';
        $('#tituloDetalle').html(titulo);

        $('#dataTablaDetalleBienUnico').dataTable({
            "order": [[0, "asc"]],
            "data": data,
            "scrollX": true,
            "autoWidth": true,
            "columns": [
                {"data": "fecha_emision", "sClass": "alignCenter", "width": '150px'},
                {"data": "documento_tipo_descripcion", "width": '240px'},
                {"data": "serie_numero", "sClass": "alignCenter", "width": '140px'},
                {"data": "persona_nombre_completo", "width": '470px'}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return (isEmpty(data)) ? '' : datex.parserFecha(data.replace(" 00:00:00", ""));
                    },
                    "targets": 0
                }
            ],
            "dom": '<"top"<"col-md-6"l><"col-md-6"f>>rt<"bottom"<"col-md-6"i><"col-md-6"p>><"clear">',
            "destroy": true
        });

    } else
    {
        var table = $('#dataTablaDetalleBienUnico').DataTable();
        table.clear().draw();
    }

}

function regresarDataTable() {
    $('[data-toggle="popover"]').popover('show');
    controlarDivTablaDetalle(1);
}

function imprimirCodigoUnico(bienUnicoId) {
    $('#bienUnicoIdHidden').val(bienUnicoId);
    $('#posInicialHidden').val($('#txtPosicionInicial').val());
    document.formQR.submit();
}

function validarToken() {
//    var bienUnicoCodigo = getParameterByName('codigo');
//    if (token == 3 && !isEmpty(bienUnicoCodigo)) {
//        $('#txtProdUnico').val(bienUnicoCodigo);
//        setTimeout(function () {
//            buscarBienUnico(1);
//        }, 100);
//    }

    var bienUnicoId = getParameterByName('id');
    if (token == 3 && !isEmpty(bienUnicoId)) {
        loaderShow();
        ax.setAccion("obtenerBienUnicoXId");
        ax.addParamTmp("bienUnicoId", bienUnicoId);
        ax.consumir();
    } else {

        var documentoId = getParameterByName('documentoId');
        if (token == 3 && !isEmpty(documentoId)) {
            loaderShow();
            ax.setAccion("obtenerDocumentoDetalleXId");
            ax.addParamTmp("documentoId", documentoId);
            ax.consumir();
        }
    }
}

function imprimirBienUnico() {
    cargarDatosBusqueda();
    
    var bienIds=select2.obtenerIdMultiple('cboBien');
    if(!isEmpty(bienIds) && bienIds.length==1){
        valoresBusquedaBienUnico[0].codDesde = $('#txtDesde').val().trim();
        valoresBusquedaBienUnico[0].codHasta = $('#txtHasta').val().trim();
    }

    //resolucion de la ventana
    var WinId = window.open('', '_blank');//window.open('', 'newwin', 'width=400,height=600');

    $.post(URL_BASE + 'script/almacen/qrBienUnicoImprimirXCriterios.php', {criteriosBusqueda: valoresBusquedaBienUnico}, function (result) {
        WinId.document.open();
        WinId.document.write(result);
        WinId.document.close();
        WinId.focus();
    });
}

function imprimirBienUnico14() {
    cargarDatosBusqueda();
    
    var bienIds=select2.obtenerIdMultiple('cboBien');
    if(!isEmpty(bienIds) && bienIds.length==1){
        valoresBusquedaBienUnico[0].codDesde = $('#txtDesde').val().trim();
        valoresBusquedaBienUnico[0].codHasta = $('#txtHasta').val().trim();
    }

    //resolucion de la ventana
    var WinId = window.open('', '_blank');//window.open('', 'newwin', 'width=400,height=600');

    $.post(URL_BASE + 'script/almacen/qrBienUnicoImprimirXCriterios14.php', {criteriosBusqueda: valoresBusquedaBienUnico}, function (result) {
        WinId.document.open();
        WinId.document.write(result);
        WinId.document.close();
        WinId.focus();
    });
}

function onResponseObtenerBienUnicoXId(data) {
    if (!isEmpty(data)) {

        var bienUnicoCodigo = data[0].bien_unico_codigo;

        $('#txtProdUnico').val(bienUnicoCodigo);
        setTimeout(function () {
            buscarBienUnico(1);
        }, 100);

        setTimeout(function () {
            verDetalleBienUnico(data[0].bien_unico_id);
        }, 300);

    }
}

function controlarDivTablaDetalle(tipo) {
    //tipo: 1, habilito el div de tabla

    if (tipo === 1) {
        $('#divDetalleBienUnico').hide();
        $('#divDataTable').show();
    } else {
        $('#divDataTable').hide();
        $('#divDetalleBienUnico').show();
    }
}

// ver detalle en modal
function verDetalleDocumento(documentoId, movimientoId)
{
    loaderShow();
    ax.setAccion("verDetalleDocumento");
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.consumir();

//    
}

function onResponseVerDetallePorCliente(data)
{
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataDocumento(data)
{
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);


    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

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
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {

    if (!isEmptyData(data))
    {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
//            "scrollX": true,
            "order": [[2, "asc"]],
            "data": data,
            "columns": [
//                {"data": "organizador"},
                {"data": "cantidad", "sClass": "alignRight"},
                {"data": "unidadMedida"},
                {"data": "descripcion"},
                {"data": "precioUnitario", "sClass": "alignRight"},
                {"data": "importe", "sClass": "alignRight"}
            ],
            "destroy": true
        });
    } else
    {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}


function fechaArmada(valor)
{
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

function onResponseObtenerDocumentoDetalleXId(data) {
    if (!isEmpty(data)) {

        var serieNumero = data[0].serie_numero;

        $('#txtNroGuia').val(serieNumero);
        setTimeout(function () {
            buscarBienUnico(1);
        }, 100);

    }
}

function onChangeBien(){
    var bienIds=select2.obtenerIdMultiple('cboBien');
//    console.log(bienIds);
    if(!isEmpty(bienIds) && bienIds.length==1){
        $('#divContenedorDesdeHasta').show();
    }else{        
        $('#divContenedorDesdeHasta').hide();
    }
}