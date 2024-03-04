$(document).ready(function () {
    loaderShow();
    dataDocumentoTipoDato = [];
    select2.iniciar();
    ax.setSuccess("successManifiesto");
    obtenerConfiguracionesInicialesManifiesto(document.getElementById("id").value);
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

            case 'obtenerConfiguracionesInicialesManifiesto':
                if (!isEmpty(response.data.choferes)) {
                    select2.cargar("cboConductor", response.data.choferes, "id", "conductor");
                    select2.cargar("cboCopiloto", response.data.choferes, "id", "conductor");
                }

                if (!isEmpty(response.data.bus)) {
                    select2.cargar("cboBus", response.data.bus, "id", "flota_placa");
                }

                select2.cargar("cboPeriodo", response.data.periodo, "id", ["mes_nombre", "anio"]);
                datePiker.iniciar('txtFechaEmision');
                $('#txtFechaEmision').datepicker('update', new Date());

                onResponseObtenerCorrelativoGuia(response.data.dataCorrelativo);

                var dataTable = response.data.table;
                if (!isEmpty(dataTable)) {
                    dataDespachoDetalle = dataTable;
                    $("#cboAgenciaOrigen").empty();
                    $("#despachoSerieNumero").append(dataTable[0]['despacho_serie_numero'] + '&nbsp;&nbsp;<span class="label label-info" id="lblEstado">' + dataTable[0]['despacho_estado'] + '</span>');
                    let dataAgenciaOrigen = distinctArrayBy(dataTable, 'agencia_id');
                    let dataAgenciaDestino = distinctArrayBy(dataTable, 'agencia_destino_id');
                    let dataManifiesto = distinctArrayBy(dataTable, 'manifiesto_id');

                    select2.cargar("cboAgenciaOrigen", dataAgenciaOrigen, 'agencia_id', 'agencia_origen');
                    select2.cargar("cboAgenciaDestino", dataAgenciaDestino, 'agencia_destino_id', 'agencia_destino');
                    select2.cargar("cboManifiesto", dataManifiesto, 'manifiesto_id', 'manifiesto_serie_numero');
                    select2.asignarValor("cboManifiesto", distinctArrayByStatic(dataManifiesto, 'manifiesto_id'));


                    select2.asignarValor('cboAgenciaOrigen', dataAgenciaOrigen[0]['agencia_id']);
                    select2.asignarValor('cboAgenciaDestino', dataAgenciaDestino[0]['agencia_destino_id']);

                    select2.asignarValor('cboBus', dataTable[0].vehiculo_id);
                    select2.asignarValor('cboPeriodo', dataTable[0].periodo_id);

                    select2.asignarValor('cboConductor', dataTable[0].chofer_id);
                    select2.asignarValor('cboCopiloto', dataTable[0].copiloto_id);

                    if (dataTable[0].documento_estado_id == 14) {
                        $("#lblEstado").removeClass("label-info");
                        $("#lblEstado").addClass("label-success");
                        bloquearElemento();
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
            case 'imprimirManifiestoXDespachoId':
                abrirPDF(response.data);
                break;

            case 'imprimirDocumentoGuiaRT':
                $.each(response.data, function (index, item) {
                    abrirPDF(item);
                });
                break;
               
            case 'AbrirDespachoT':
                 if (response.status == 'ok') {
                swal("Despacho Abierto!", "Despacho abierto!", "success");
                }
                break;


            case 'generarGuiaRT':
                if (response.status == 'ok') {

                    swal("Guía generada!", "Guía generada!", "success");

                    var dataTable = response.data.table;
                    if (!isEmpty(dataTable)) {
                        dataDespachoDetalle = dataTable;

                        if (dataTable[0].documento_estado_id == 14) {
                            $("#lblEstado").removeClass("label-info");
                            $("#lblEstado").addClass("label-success");
                            bloquearElemento();
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
function obtenerConfiguracionesInicialesManifiesto(despacho_id) {
    ax.setAccion("obtenerConfiguracionesInicialesManifiesto");
    ax.addParamTmp("despacho_id", despacho_id);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("manifiestos", null);
    ax.consumir();
}

function onResponseAjaxpGetDataGrid(data) {
//    debugger;
    $("#dataListaManifiestos").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatableManifiesto" class="table table-striped table-bordered"><thead>' +
            ' <tr>' +
            '<th style="text-align:center;" width="10%">Manifiesto</th>' +
            '<th style="text-align:center;" width="10%">Destino</th>' +
            '<th style="text-align:center;" width="10%">Cantidad</th>' +
            '<th style="text-align:center;" width="30%">Concepto</th>' +
            '<th style="text-align:center;" width="20%">N. Comprobante</th>' +
            '<th style="text-align:center;" width="10%">GR Cliente</th>' +
            '<th style="text-align:center;" width="10%">GR Transportista</th>' +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.manifiesto_serie_numero + "</td>" +
                    "<td style='text-align:left;'>" + item.agencia_destino + "</td>" +
                    "<td style='text-align:right;'>" + parseFloat(item.manifiesto_cantidad) + "</td>" +
                    "<td style='text-align:left;'>" + item.articulo + "</td>" +
                    "<td style='text-align:left;'>" + item.documento_tipo_desc + ' | ' + item.factura_serie_numero + "</td>" +
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
    var nroDespacho = document.getElementById("id").value;
    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;
    var copiloto = document.getElementById("cboCopiloto").value;
    var periodo = document.getElementById("cboPeriodo").value;
    var fecha = document.getElementById("txtFechaEmision").value;


    ax.setAccion("imprimirManifiestoXDespachoId");
    ax.addParamTmp("nroDespacho", nroDespacho);
    ax.addParamTmp("vehiculo", vehiculo);
    ax.addParamTmp("piloto", piloto);
    ax.addParamTmp("copiloto", copiloto);
    ax.addParamTmp("periodo", periodo);
    ax.addParamTmp("fecha", fecha);
    ax.consumir();
}

function imprimirDocumentoGuia() {
    loaderShow();

    //Cambios Cristopher
    //let listaGuiaBoleta  = dataDespachoDetalle.filter(item => item.factura_documento_tipo_id == 6);
    let listaGuiaBoleta = dataDespachoDetalle; 

    if(isEmpty(listaGuiaBoleta)){
        mostrarValidacionLoaderClose("No existen guías relacionadas con una boleta");
        return;
    }
    let listaGuia = distinctArrayByStatic(listaGuiaBoleta, 'guia_id');
    let listaUnicaGuia = listaGuia.filter((v, i, a) => a.indexOf(v) === i);
    ax.setAccion("imprimirDocumentoGuiaRT");
    ax.addParamTmp("id", listaUnicaGuia);

    //Cambios Cristopher
    ax.addParamTmp("despacho", dataDespachoDetalle[0].despacho_serie_numero);
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

    var nroDespacho = document.getElementById("id").value;
    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;
    var copiloto = document.getElementById("cboCopiloto").value;
    var periodo = document.getElementById("cboPeriodo").value;
    var fecha = document.getElementById("txtFechaEmision").value;
    var serieGuia = document.getElementById("serieGuia").value;
    var numeroGuia = document.getElementById("numeroGuia").value;
    loaderShow();
    ax.setAccion("generarGuiaRT");
    ax.addParamTmp("despachoId", nroDespacho);
    ax.addParamTmp("serie", serieGuia);
    ax.addParamTmp("numero", numeroGuia);
    ax.addParamTmp("vehiculoId", vehiculo);
    ax.addParamTmp("pilotoId", piloto);
    ax.addParamTmp("copilotoId", copiloto);
    ax.addParamTmp("periodoId", periodo);
    ax.addParamTmp("fecha", fecha);
    ax.consumir();
}

function AbrirDespachoT() {

    var nroDespacho = document.getElementById("id").value;

    loaderShow();
    ax.setAccion("AbrirDespachoT");
    ax.addParamTmp("despachoId", nroDespacho);

    ax.consumir();
}

$('#cboManifiesto').on("change", function (e) {
    filtrarManifiestos();

});

$('#cboAgenciaDestino').on("change", function (e) {
    filtrarManifiestos();

});

function filtrarManifiestos() {
//    debugger;
    loaderShow();
    let listManifiestos = select2.obtenerIdMultiple("cboManifiesto");
//    let agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");


    let dataFiltrado = [];
    if (!isEmpty(listManifiestos)) {
        $.each(listManifiestos, function (index, itemFilter) {
            dataFiltrado = dataFiltrado.concat(dataDespachoDetalle.filter(item => item.manifiesto_id == itemFilter
//                    && item.agencia_destino_id == agenciaDestinoId
            ));
        });
    }

    onResponseAjaxpGetDataGrid(dataFiltrado);
    setTimeout(function () {
        var table = $('#datatableManifiesto').DataTable({
//        destroy: true,
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
    }, 1000);

}

function bloquearElemento() {
    $("#cboAgenciaOrigen").prop('disabled', true);
    // $("#cboAgenciaDestino").prop('disabled', true);
    $("#cboPeriodo").prop('disabled', true);
    $("#txtFechaEmision").prop('disabled', true);
    $("#cboBus").prop('disabled', true);
    $("#cboConductor").prop('disabled', true);
    $("#cboCopiloto").prop('disabled', true);
//    $("#serieGuia").prop('disabled', true);
    $("#numeroGuia").prop('disabled', true);
    $("#imprimeManifiesto").prop('disabled', false);
    $("#imprimeGuia").prop('disabled', false);
    $("#generar").prop('disabled', true);
    $("#lblEstado").html("Generado");
}

function cerrar() {
    cargarDivIndex('#window', URL_BASE + 'vistas/com/movimiento/movimiento_listar_despacho.php', '123', 'Despacho', 0);
}


function AbrirDespacho() {
    var serieGuia = document.getElementById("serieGuia").value;
    var numeroGuia = document.getElementById("numeroGuia").value;
    var piloto = document.getElementById("cboConductor").value;

    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;
    var copiloto = document.getElementById("cboCopiloto").value;
    var periodo = document.getElementById("cboPeriodo").value;
    var fecha = document.getElementById("txtFechaEmision").value;


    swal({
        title: "Aperturar Despacho: ",
        text: "Al dar clic en si, se volvera aperturar este despacho , y podrá agregar paquetes.  ",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,aperturar !",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {

            AbrirDespachoT();
        }
    });

}

function GenerarDespacho() {
    var serieGuia = document.getElementById("serieGuia").value;
    var numeroGuia = document.getElementById("numeroGuia").value;
    var piloto = document.getElementById("cboConductor").value;

    var vehiculo = document.getElementById("cboBus").value;
    var piloto = document.getElementById("cboConductor").value;
    var copiloto = document.getElementById("cboCopiloto").value;
    var periodo = document.getElementById("cboPeriodo").value;
    var fecha = document.getElementById("txtFechaEmision").value;



    if (isEmpty(serieGuia)) {
        mostrarValidacionLoaderClose("Seleccione un bus");
        return;
    }

    if (isEmpty(numeroGuia)) {
        mostrarValidacionLoaderClose("Seleccione un bus");
        return;
    }

    if (isEmpty(vehiculo)) {
        mostrarValidacionLoaderClose("Seleccione un bus");
        return;
    }

    if (isEmpty(copiloto) || copiloto == 0) {
        mostrarValidacionLoaderClose("Seleccione un copiloto");
        return;
    }

    if (isEmpty(piloto) || piloto == 0) {
        mostrarValidacionLoaderClose("Seleccione un conductor");
        return;
    }

    if (isEmpty(copiloto) || copiloto == 0) {
        mostrarValidacionLoaderClose("Seleccione un copiloto");
        return;
    }

    if (copiloto == piloto) {
        mostrarValidacionLoaderClose("El piloto y copiloto deben ser diferentes");
        return;
    }

    swal({
        title: "Generar Guías: ",
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
            let  serie = !isEmpty(documentoTipoDatoSerie[0].data) ? (documentoTipoDatoSerie[0].data) : '';
            $("#serieGuia").val(serie);
        }

        let documentoTipoDatoNumero = data.filter(item => item.tipo == 8);
        if (!isEmpty(documentoTipoDatoNumero)) {
            let numero = (!isEmpty(documentoTipoDatoNumero[0].data) ? documentoTipoDatoNumero[0].data : '');
            $("#numeroGuia").val(numero);
        }
    }
}
