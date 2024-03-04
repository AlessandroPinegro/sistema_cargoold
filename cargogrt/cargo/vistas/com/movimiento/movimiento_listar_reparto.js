$(document).ready(function () {
    loaderShow();
    dataDocumentoTipoDato = [];
    select2.iniciar();
    ax.setSuccess("successManifiesto");
    obtenerConfiguracionesInicialesManifiestoRepartoDetalle(document.getElementById("id").value);
    iniciarDataPicker();
});
var serieGuia = '';
var numeroGuia = '';
var piloto = '';

var abrirGuia = false;

var dataDespachoDetalle = [];
function successManifiesto(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {

            case 'obtenerCorrelativoGuiaBoleta':
                onResponseObtenerCorrelativoGuia(response.data);
                loaderClose();
                break;

            case 'obtenerConfiguracionesInicialesManifiestoRepartoDetalle':
                if (!isEmpty(response.data.choferes)) {
                    select2.cargar("cboConductor", response.data.choferes, "id", "conductor");
                }

                if (!isEmpty(response.data.bus)) {
                    select2.cargar("cboBus", response.data.bus, "id", ["flota_marca", "flota_placa"]);
                }

                select2.cargar("cboPeriodo", response.data.periodo, "id", ["mes_nombre", "anio"]);
                datePiker.iniciar('txtFechaEmision');
                $('#txtFechaEmision').datepicker('update', new Date());

                onResponseObtenerCorrelativoGuia(response.data.dataCorrelativo);

                var dataTable = response.data.table;
                if (!isEmpty(dataTable)) {
                    dataDespachoDetalle = dataTable;
                    $("#cboAgenciaOrigen").empty();
                    $("#despachoSerieNumero").append(dataTable[0]['manifiesto'] + '&nbsp;&nbsp;<span class="label label-info" id="lblEstado">' + dataTable[0]['estado'] + '</span>');
                    let dataAgenciaOrigen = distinctArrayBy(dataTable, 'agencia_id');

                    select2.cargar("cboAgenciaOrigen", dataAgenciaOrigen, 'agencia_id', 'agencia_descripcion');
                    select2.asignarValor('cboAgenciaOrigen', dataAgenciaOrigen[0]['agencia_id']);

                    select2.asignarValor('cboBus', dataTable[0].vehiculo_id);
                    select2.asignarValor('cboPeriodo', (!isEmpty(dataTable[0].guia_periodo_id) ? dataTable[0].guia_periodo_id : dataTable[0].periodo_id));

                    select2.asignarValor('cboConductor', dataTable[0].chofer_id);

                    let listaGuiaBoletaVacia = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6 && isEmpty(item.guia_id));

                    let listaGuiaBoleta = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6 && !isEmpty(item.guia_id));

                    if (!isEmpty(listaGuiaBoletaVacia) || isEmpty(dataTable[0].chofer_id)) {
                        $("#numeroGuia").prop('disabled', false);
                        $("#imprimeGuia").prop('disabled', false);
                        $("#generar").prop('disabled', false);
                    } else {
                        $("#numeroGuia").prop('disabled', true);
                        $("#imprimeGuia").prop('disabled', true);
                        $("#generar").prop('disabled', true);
                    }

                    if (!isEmpty(listaGuiaBoleta) || !isEmpty(dataTable[0].chofer_id)) {
                        bloquearElemento();
                        $("#imprimeGuia").prop('disabled', false);
                        $("#imprimeManifiesto").prop('disabled', false);
                    } else {
                        $("#imprimeGuia").prop('disabled', true);
                        $("#imprimeManifiesto").prop('disabled', true);
                    }
                }


                onResponseAjaxpGetDataGrid(dataTable);
                var table = $('#datatableManifiesto').DataTable({
                    "autoWidth": true,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
                loaderClose();
                break;
            case 'imprimirManifiesto':
                abrirPDF(response.data);
                break;

            case 'imprimirDocumentoGuiaRT':
                $.each(response.data, function (index, item) {
                    abrirPDF(item);
                });
                break;

            case 'generarGuiaRTXManifiestoId':
                //                debugger;
                if (response.status == 'ok') {

                    swal("Guía generada!", "Guía generada!", "success");

                    var dataTable = response.data.table;
                    var dataTable = response.data.table;
                    if (!isEmpty(dataTable)) {
                        dataDespachoDetalle = dataTable;
                        let listaGuiaBoletaVacia = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6 && isEmpty(item.guia_id));

                        let listaGuiaBoleta = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6 && !isEmpty(item.guia_id));

                        if (!isEmpty(listaGuiaBoletaVacia)) {
                            $("#numeroGuia").prop('disabled', false);
                            $("#imprimeGuia").prop('disabled', false);
                            $("#generar").prop('disabled', false);
                        } else {
                            $("#numeroGuia").prop('disabled', true);
                            $("#imprimeGuia").prop('disabled', true);
                            $("#generar").prop('disabled', true);
                        }

                        if (!isEmpty(listaGuiaBoleta)) {
                            bloquearElemento();
                            $("#imprimeGuia").prop('disabled', false);
                            $("#imprimeManifiesto").prop('disabled', false);
                        } else {
                            $("#imprimeGuia").prop('disabled', true);
                            $("#imprimeManifiesto").prop('disabled', true);
                        }


                        onResponseAjaxpGetDataGrid(dataTable);
                        var table = $('#datatableManifiesto').DataTable({
                            "autoWidth": true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }
                        });
                    }

                    loaderClose();
                } else {
                    $.Notification.autoHideNotify('warning', 'top right', 'Error, intente denuevo', '');
                }
                break;
        }
    }
}

function obtenerConfiguracionesInicialesManifiestoRepartoDetalle(manifiestoId) {
    ax.setAccion("obtenerConfiguracionesInicialesManifiestoRepartoDetalle");
    ax.addParamTmp("manifiesto_id", manifiestoId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function onResponseAjaxpGetDataGrid(data) {
    //    debugger;
    $("#dataListaManifiestos").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatableManifiesto" class="table table-striped table-bordered"><thead>' +
        ' <tr>' +
        '<th style="text-align:center;" width="10%">Pedido</th>' +
        '<th style="text-align:center;" width="10%">Destino</th>' +
        '<th style="text-align:center;" width="10%">Cantidad</th>' +
        '<th style="text-align:center;" width="30%">Concepto</th>' +
        '<th style="text-align:center;" width="10%">N. Comprobante</th>' +
        '<th style="text-align:center;" width="15%">GR Cliente</th>' +
        '<th style="text-align:center;" width="15%">GR Transportista</th>' +
        "</tr>" +
        "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.pedido + "</td>" +
                "<td style='text-align:left;'>" + item.agencia_descripcion + "</td>" +
                "<td style='text-align:right;'>" + parseFloat(item.cantidad) + "</td>" +
                "<td style='text-align:left;'>" + item.articulo + "</td>" +
                "<td style='text-align:left;'>" + item.documento_tipo_desc + ' | ' + item.factura + "</td>" +
                "<td style='text-align:left;'>" + item.guia_relacion + "</td>" +
                "<td style='text-align:left;'>" + (!isEmpty(item.guia) ? item.guia : '') + "</td>" +
                "</td></tr>";
            cuerpo_total = cuerpo_total + cuerpo;

        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataListaManifiestos").append(html);
}

function imprimirDocumentoManifiesto() {
    loaderShow();
    var manifiestoId = document.getElementById("id").value;
    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;

    ax.setAccion("imprimirManifiesto");
    ax.addParamTmp("manifiestoId", manifiestoId);
    ax.addParamTmp("vehiculo", vehiculo);
    ax.addParamTmp("piloto", piloto);
    ax.consumir();


}

function imprimirDocumentoGuia() {
    loaderShow();

    //Cambios Cristopher
    //let litaGuiaBoleta = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6 && !isEmpty(item.guia_id));
    let litaGuiaBoleta = dataDespachoDetalle;

    if (isEmpty(litaGuiaBoleta)) {
        mostrarValidacionLoaderClose("No existen guías relacionadas con una boleta");
        return;
    }
    let listaGuia = distinctArrayByStatic(litaGuiaBoleta, 'guia_id');
    let listaUnicaGuia = listaGuia.filter((v, i, a) => a.indexOf(v) === i);
    ax.setAccion("imprimirDocumentoGuiaRT");
    ax.addParamTmp("id", listaUnicaGuia);

    //Cambios Cristopher
    ax.addParamTmp("despacho", dataDespachoDetalle[0].manifiesto);
    ax.consumir();
}

function abrirPDF(data) {
    window.open(URL_BASE + 'pdf2.php?url_pdf=' + URL_BASE + 'vistas/com/movimiento/documentos/' + data + '&nombre_pdf=' + data);
    var url = '/../../vistas/com/movimiento/documentos/' + data;
    setTimeout(function () {
        eliminarPDF(url);
    }, 1000);
    loaderClose();
}


function eliminarPDF(url) {
    ax.setAccion("eliminarPDFM");
    ax.addParamTmp("url", url);
    ax.consumir();
}


function generarGuiaRT() {

    var manifiestoId = document.getElementById("id").value;
    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;
    var periodo = document.getElementById("cboPeriodo").value;
    var fecha = document.getElementById("txtFechaEmision").value;
    var serieGuia = document.getElementById("serieGuia").value;
    var numeroGuia = document.getElementById("numeroGuia").value;

    loaderShow();
    ax.setAccion("generarGuiaRTXManifiestoId");
    ax.addParamTmp("manifiestoId", manifiestoId);
    ax.addParamTmp("serie", serieGuia);
    ax.addParamTmp("numero", numeroGuia);
    ax.addParamTmp("vehiculo", vehiculo);
    ax.addParamTmp("piloto", piloto);
    ax.addParamTmp("periodo", periodo);
    ax.addParamTmp("fecha", fecha);
    ax.consumir();
}

function bloquearElemento() {
    $("#cboAgenciaOrigen").prop('disabled', true);
    $("#cboPeriodo").prop('disabled', true);
    $("#txtFechaEmision").prop('disabled', true);
    $("#cboBus").prop('disabled', true);
    $("#cboConductor").prop('disabled', true);

    //    $("#lblEstado").html("Generado");
}

function cerrar() {
    cargarDivIndex('#window', URL_BASE + 'vistas/com/movimiento/movimiento_listar_manifiesto_reparto.php', '295', 'Reparto', 0);
}

function GenerarDespacho() {
    var serieGuia = document.getElementById("serieGuia").value;
    var numeroGuia = document.getElementById("numeroGuia").value;
    var piloto = document.getElementById("cboConductor").value;

    if (isEmpty(serieGuia) || isEmpty(numeroGuia)) {
        $.Notification.autoHideNotify('warning', 'top right', 'ingrese una serie y numero', '');
        return;
    }

    if (isEmpty(piloto) || piloto == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'seleccione un conductor', '');
        return;
    }

    swal({
        title: "Generar guía",
        text: "Al dar clic en si, cambiara el estado a generado, y ya no se podra editar ",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,generar !",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            generarGuiaRT();
        }
    });
}

function distinctArrayBy(arr, propName) {
    var result = arr.reduce(function (arr1, e1) {
        var matches = arr1.filter(function (e2) {
            return e1[propName] == e2[propName];
        })
        if (matches.length == 0)
            arr1.push(e1)
        return arr1;
    }, []);

    return result;
}

function distinctArrayByStatic(arr, propName) {
    var result = arr.reduce(function (arr1, e1) {
        var matches = arr1.filter(function (e2) {
            return e1[propName] == e2[propName];
        })
        if (matches.length == 0)
            arr1.push(e1[propName])
        return arr1;
    }, []);

    return result;
}



function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}


function obtenerSerieCorrelativoGuia() {
    loaderShow();
    ax.setAccion("obtenerCorrelativoGuiaBoleta");
    ax.addParamTmp("agencia_id", select2.obtenerValor("cboAgenciaOrigen"));
    ax.addParamTmp("identificador_negocio", 3);
    ax.consumir();

}

function onResponseObtenerCorrelativoGuia(data) {
    //    debugger;
    if (!isEmpty(data)) {
        let documentoTipoDatoSerie = data.filter(item => item.tipo == 7);
        if (!isEmpty(documentoTipoDatoSerie)) {
            let serie = !isEmpty(documentoTipoDatoSerie[0].data) ? (documentoTipoDatoSerie[0].data) : '';
            $("#serieGuia").val(serie);
        }

        let documentoTipoDatoNumero = data.filter(item => item.tipo == 8);
        if (!isEmpty(documentoTipoDatoNumero)) {
            let numero = (!isEmpty(documentoTipoDatoNumero[0].data) ? documentoTipoDatoNumero[0].data : '');
            $("#numeroGuia").val(numero);
        }
    }
}