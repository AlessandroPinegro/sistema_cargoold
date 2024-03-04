var c = $('#env i').attr('class');
var zonaID = '';

var acciones = {
    getTarifarioZona: false
};

var valoresBusquedaPorActividad = [{agencia: "", reparto: ""}];

$(document).ready(function () {
    //    loaderShow();
    // $('[data-toggle="popover"]').popover({ html: true }).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseReportePorCuenta");
    obtenerConfiguracionesInicialesPorActividad();
    // obtenerDataPorTarifarioZona();
    iniciarDatatable();
    getTarifarioAll();
//    colapsarBuscador();
});

function cargarDatosBusqueda() {
    var agenciaO = $('#cboOrigen2').val();
    var zReparto = $('#cboDestino2').val();

    valoresBusquedaPorActividad[0].agencia = agenciaO;
    valoresBusquedaPorActividad[0].reparto = zReparto;
}

function obtenerDatosBusqueda() {
    var cadena = "";
    cargarDatosBusqueda();

    if (!isEmpty(valoresBusquedaPorActividad[0].agencia)) {
        cadena += negrita("Agencia: ");
        cadena += select2.obtenerText('cboOrigen2');
        cadena += "<br>";
    }
    if (!isEmpty(valoresBusquedaPorActividad[0].reparto)) {
        cadena += negrita("Zona Reparto: ");
        cadena += select2.obtenerText('cboDestino2');
        cadena += "<br>";
    }

    return cadena;
}

function buscarTarifario(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    $('[data-toggle="popover"]').popover('hide');
    if (!isEmpty(cadena) && cadena !== 0) {
        $('#idPopover').attr("data-content", cadena);
        // $('[data-toggle="popover"]').popover('show');
    }
    banderaBuscar = 1;
    getTarifarioAll();
    if (colapsa === 1)
        colapsarBuscador();
    //    obtenerDataBusquedaTarifario(cadena);
}

function onResponseReportePorCuenta(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesTarifarioZona':
                onResponseObtenerConfiguracionesIniciales(response.data);
                //                loaderClose();
//                getTarifarioAll();
                break;
            case 'obtenerDataPorTarifarioZona':
                onResponseGetDataGridPorCuenta(response.data);
                $("#datatable").dataTable({
                    scrollX: true,
                    autoWidth: true,
                    searching: false,
                    order: [[0, "asc"]],
                    language: {
                        sProcessing: "Procesando...",
                        sLengthMenu: "Mostrar _MENU_ registros",
                        sZeroRecords: "No se encontraron resultados",
                        sEmptyTable: "Ning\xfAn dato disponible en esta tabla",
                        sInfo:
                                "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty:
                                "Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                        sInfoPostFix: "",
                        sSearch: "Buscar:",
                        sUrl: "",
                        sInfoThousands: ",",
                        sLoadingRecords: "Cargando...",
                        oPaginate: {
                            sFirst: "Primero",
                            sLast: "Último",
                            sNext: "Siguiente",
                            sPrevious: "Anterior",
                        },
                        oAria: {
                            sSortAscending:
                                    ": Activar para ordenar la columna de manera ascendente",
                            sSortDescending:
                                    ": Activar para ordenar la columna de manera descendente",
                        },
                    },
                });
                selectAgenciaZona();
                loaderClose();
                break;
            case 'insertTarifarioZona':
                limpiar();
                loaderClose();
                habilitarBoton();
                obtenerDataPorTarifarioZona();
                mostrarOk("Tarifario guardada correctamente.");
                cleanForm();
                break;
            case 'obtenerReportePorCuentaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/reporte.xlsx";
                break;

            case 'deleteTarifarioZona':

                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "Se elimino Tarifario: " + response.data['0'].nombre + ".", "success");
                    obtenerDataPorTarifarioZona();

                } else {
                    swal("Cancelado", " " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;

            case 'getTarifarioZona':
                acciones.getTarifarioZona = true;
                dataPorId = response.data;
                tarifarioCargarData();
                loaderShow();
                break;

            case 'updateTarifarioZona':
                limpiar();
                loaderClose();
                habilitarBoton();
                obtenerDataPorTarifarioZona();
                mostrarOk("Tarifario actualizado correctamente.");
                cleanForm();
                break;
            case 'obtenerZonaxAgencia':
                $("#cboDestino").empty();
                if (!isEmpty(response.data)) {
                    select2.cargarSeleccione("cboDestino", response.data, "id", ["descripcion"], "Seleccione una zona reparto");
                    select2.asignarValor('cboDestino', '');
                    select2.cargarSeleccione("cboDestino2", response.data, "id", ["descripcion"], "Seleccione una zona reparto");
                    select2.asignarValor('cboDestino2', '');
                } else {
                    select2.cargarSeleccione("cboDestino", '', "id", ["descripcion"], "Seleccione una zona reparto");
                    select2.asignarValor('cboDestino', '');
                    select2.cargarSeleccione("cboDestino2", '', "id", ["descripcion"], "Seleccione una zona reparto");
                    select2.asignarValor('cboDestino2', '');
                }
                asignarValorSelect2("cboDestino", zonaID);
                asignarValorSelect2("cboDestino2", zonaID);
                loaderClose();
                break;
            case "importTarifarioZona":
                $("#resultado").append(response.data);
                loaderClose();
                // listar;
                getTarifarioAll();
                break;
            case 'exportarTarifarioZona':
                loaderClose();
                location.href = URL_BASE + "util/formatos/lista_tarifario_zona.xlsx";
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerReportePorCuentaExcel':
                loaderClose();
                break;
            case 'insertTarifarioZona':
                limpiar();
                loaderClose();
                habilitarBoton();
                break;
            case 'updateTarifarioZona':
                limpiar();
                loaderClose();
                habilitarBoton();
                break;

        }
    }
}

function getTarifarioAll() {
    // debugger;
    ax.setAccion("obtenerDataPorTarifarioZona");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
    // console.log("entraaa");
}

function exportarTarifario()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("exportarTarifarioZona");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function selectAgenciaZona() {
    $("#cboOrigen").change(function () {
        var AgenciaID = $(this).children("option:selected").val();
        zonaID = '';
        ax.setAccion("obtenerZonaxAgencia");
        ax.addParamTmp("id_agencia", AgenciaID);
        ax.consumir();
    })
    $("#cboOrigen2").change(function () {
        var AgenciaID = $(this).children("option:selected").val();
        zonaID = '';
        ax.setAccion("obtenerZonaxAgencia");
        ax.addParamTmp("id_agencia", AgenciaID);
        ax.consumir();
    })
}

function obtenerConfiguracionesInicialesPorActividad() {
    //alert('hola');
    ax.setAccion("obtenerConfiguracionesInicialesTarifarioZona");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function obtenerDataPorTarifarioZona() {
    //alert('hola');
    ax.setAccion("obtenerDataPorTarifarioZona");
    ax.consumir();
}
function onResponseObtenerConfiguracionesIniciales(data) {
    //    var dataMes = [ {id: 1, descripcion: "Enero"},
    //                    {id: 2, descripcion: "Febrero"},
    //                    {id: 3, descripcion: "Marzo"},
    //                    {id: 4, descripcion: "Abril"},
    //                    {id: 5, descripcion: "Mayo"},
    //                    {id: 6, descripcion: "Junio"},
    //                    {id: 7, descripcion: "Julio"},
    //                    {id: 8, descripcion: "Agosto"},
    //                    {id: 9, descripcion: "Setiembre"},
    //                    {id: 10, descripcion: "Octubre"},
    //                    {id: 11, descripcion: "Noviembre"},
    //                    {id: 12, descripcion: "Diciembre"}
    //                  ];
    //    
    //    select2.cargar("cboMes", dataMes, "id", "descripcion");
    //    var hoy = new Date();
    //    var mm = hoy.getMonth()+1; //hoy es 0!       
    //    var anioActual = hoy.getFullYear();
    //    
    //    select2.asignarValor("cboMes",mm);


    //anio
    //    var fechaPrimera=data.fecha_primer_documento[0]['primera_fecha'];
    //    var fechaPartes = fechaPrimera.split("-");
    //    var anioInicial=parseInt(fechaPartes[0]);
    //    
    //    var string ='';
    //    
    //    for (var i = 0; i <= (anioActual - anioInicial); i++)
    //    {
    //        string += '<option value="' + (anioInicial + i) + '">' + (anioInicial + i) + '</option>';
    //    }
    //    $('#cboAnio').append(string);
    //    
    //    select2.asignarValor("cboAnio",anioActual);
    //fin anio

    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione("cboOrigen2", data.agencia, "agencia_id", ["codigo", "descripcion"], "Seleccione una agencia");
        select2.asignarValor('cboOrigen2', '');
    }

    // if (!isEmpty(data.zona)) {
    select2.cargarSeleccione("cboDestino2", '', "zona_id", ["codigo", "descripcion"], "Seleccione una zona reparto");
    select2.asignarValor('cboDestino2', '');
    // }

    if (!isEmpty(data.actividad_tipo)) {
        select2.cargar("cboActividadTipo", data.actividad_tipo, "id", "descripcion");
    }

    if (!isEmpty(data.actividad)) {
        select2.cargar("cboActividad", data.agencia, "agencia_id", ["codigo", "codigo"]);
    }

    if (!isEmpty(data.empresa)) {
        select2.cargar("cboTienda", data.empresa, "id", "razon_social");
    }

    if (!isEmpty(data.agencia)) {
        select2.cargarSeleccione("cboOrigen", data.agencia, "agencia_id", ["codigo", "descripcion"], "Seleccione una agencia");
        select2.asignarValor('cboOrigen', '');
    }

    // if (!isEmpty(data.zona)) {
    select2.cargarSeleccione("cboDestino", '', "zona_id", ["codigo", "descripcion"], "Seleccione una zona reparto");
    select2.asignarValor('cboDestino', '');
    // }

    if (!isEmpty(data.moneda)) {
        select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
        select2.asignarValor('cboMoneda', '2');
    }
    loaderClose();
}

function onResponseGetDataGridPorCuenta(data) {
    $("#datatable2").empty();
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable"  class="table table-striped table-bordered" style="width: 100%"><thead>' +
            " <tr>" +
            "<th style='text-align:center; vertical-align: middle;'>Agencia</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Zona de Reparto</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Moneda</th> " +
            "<th style='text-align:center; vertical-align: middle;' >Precio sobre Kg</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Precio por 0-50 Kg</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Precio por 51-100 Kg</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Precio por 101-250 Kg</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Precio por 251-500 Kg</th>" +
            "<th style='text-align:center; vertical-align: middle;' >Precio por +500 Kg</th>" +
            "<th style='text-align:center;'>Acciones</th>" +
            "</tr>" +
            "</thead>";
    
    $.each(data, function (index, item) {
        var moned = '';
        if (item.moneda_id == 2) {
            moned = 'S/';
        } else if (item.moneda_id == 4) {
            moned = '$/';
        }
        if (item.nombre == null) {
            item.nombre = '';
        } else {
            item.nombre = item.nombre;
        }
        cuerpo = "<tr>" +
                "<td style='text-align:left;'>" + item.agencia + "</td>" +
                "<td style='text-align:left;'>" + item.zona + "</td>" +
                "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_sobre, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_50K, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_100K, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_250K, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_500K, 4).toFixed(2) + "</td>" +
                "<td style='text-align:left;'>" + moned + parseFloat(item.precio_max, 4).toFixed(2) + "</td>" +
                "<td style='text-align:center;'>" +
                "<a href='#' onclick='getTarifarioZona(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                "<a href='#' onclick='confirmarDeleteTarifarioZona(" + item.id + ", \"" + item.agencia + " - " + item.zona + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                "</td>" +
                "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatable2").append(html);
}

function onchangeOrigen() {
    $('#msj_origen').hide();
}
function onchangeDestino() {
    $('#msj_destino').hide();
}
function onchangeMoneda() {
    $('#msj_moneda').hide();
}


$('#cboMoneda').keypress(function () {
    $('#msj_moneda').hide();
});

$('#txtsobre').keypress(function () {
    $('#msj0').hide();
});

$('#txt1k').keypress(function () {
    $('#msj1').hide();
});

$('#txt2k').keypress(function () {
    $('#msj2').hide();
});

$('#txt3k').keypress(function () {
    $('#msj3').hide();
});

$('#txt4k').keypress(function () {
    $('#msj4').hide();
});

$('#txt5k').keypress(function () {
    $('#msj5').hide();
});

function getTarifarioAll() {
    // debugger;
    ax.setAccion("obtenerDataPorTarifarioZona");
    ax.addParamTmp("criterios", valoresBusquedaPorActividad);
    ax.consumir();
}

function getTarifarioZona(id) {
    VALOR_ID_USUARIO = id;
    $("#addTarifario").modal("show");
    $("#modalTitulo").empty();
    $("#modalTitulo").html("Editar Tarifario Zona");
    loaderShow();
    ax.setAccion("getTarifarioZona");
    ax.addParamTmp("id_tarifarioZona", id);
    ax.consumir();
}

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function tarifarioCargarData() {

    // debugger;
    if (acciones.getTarifarioZona) {
        if (!isEmpty(VALOR_ID_USUARIO)) {
            zonaID = dataPorId['0']['zona_id'];
            //                llenarFormularioEditar(dataPorId);
            document.getElementById('id').value = dataPorId['0']['id'];
            document.getElementById('txtsobre').value = parseFloat(dataPorId['0']['precio_sobre']).toFixed(2);
            document.getElementById('txt1k').value = parseFloat(dataPorId['0']['precio_50K']).toFixed(2);
            document.getElementById('txt2k').value = parseFloat(dataPorId['0']['precio_100K']).toFixed(2);
            document.getElementById('txt3k').value = parseFloat(dataPorId['0']['precio_250K']).toFixed(2);
            document.getElementById('txt4k').value = parseFloat(dataPorId['0']['precio_500K']).toFixed(2);
            document.getElementById('txt5k').value = parseFloat(dataPorId['0']['precio_max']).toFixed(2);

            asignarValorSelect2("cboOrigen", dataPorId['0']['agencia_id']);
            ax.setAccion("obtenerZonaxAgencia");
            ax.addParamTmp("id_agencia", dataPorId['0']['agencia_id']);
            ax.consumir();


            asignarValorSelect2("cboMoneda", dataPorId['0']['moneda_id']);

        } else {
            loaderClose();
        }
    }
}
function confirmarDeleteTarifarioZona(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás Tarifario Agencias:" + nom + "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            deleteTarifarioZona(id, nom);
        } else {
            if (bandera_eliminar == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function deleteTarifarioZona(id, nom) {
    ax.setAccion("deleteTarifarioZona");
    ax.addParamTmp("id_tarifarioZona", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}


function validar_caja_form() {
    var bandera = true;
    var espacio = /^\s+$/;
    var txtsobre = document.getElementById('txtsobre').value;
    var txt1k = document.getElementById('txt1k').value;
    var txt2k = document.getElementById('txt2k').value;
    var txt3k = document.getElementById('txt3k').value;
    var txt4k = document.getElementById('txt4k').value;
    var txt5k = document.getElementById('txt5k').value;



    var origen = document.getElementById('cboOrigen').value;
    var destino = document.getElementById('cboDestino').value;

    var moneda = document.getElementById('cboMoneda').value;

    if (txtsobre == "" || espacio.test(txtsobre) || txtsobre.length == 0) {
        $("msj0").removeProp(".hidden");
        $("#msj0").text("Ingresar Precio").show();
        bandera = false;
    }

    if (txt1k == "" || espacio.test(txt1k) || txt1k.length == 0) {
        $("msj1").removeProp(".hidden");
        $("#msj1").text("Ingresar Precio").show();
        bandera = false;
    }

    if (txt2k == "" || espacio.test(txt2k) || txt2k.length == 0) {
        $("msj2").removeProp(".hidden");
        $("#msj2").text("Ingresar Precio").show();
        bandera = false;
    }
    if (txt3k == "" || espacio.test(txt3k) || txt3k.length == 0) {
        $("msj3").removeProp(".hidden");
        $("#msj3").text("Ingresar Precio").show();
        bandera = false;
    }
    if (txt4k == "" || espacio.test(txt4k) || txt4k.length == 0) {
        $("msj4").removeProp(".hidden");
        $("#msj4").text("Ingresar Precio").show();
        bandera = false;
    }
    if (txt5k == "" || espacio.test(txt5k) || txt5k.length == 0) {
        $("msj5").removeProp(".hidden");
        $("#msj5").text("Ingresar Precio").show();
        bandera = false;
    }
    if (origen == "" || origen == null || espacio.test(origen) || origen.length == 0) {
        $("msj_origen").removeProp(".hidden");
        $("#msj_origen").text("Ingresar una agencia  valida").show();
        bandera = false;
    }

    if (destino == "" || destino == null || espacio.test(destino) || destino.length == 0) {
        $("msj_destino").removeProp(".hidden");
        $("#msj_destino").text("Ingresar una agencia  valida").show();
        bandera = false;
    }



    if (moneda == "" || moneda == null || espacio.test(moneda) || moneda.length == 0) {
        $("msj_moneda").removeProp(".hidden");
        $("#msj_moneda").text("Ingresar una moneda").show();
        bandera = false;
    }

    return bandera;
}


function guardarTarifario() {

    var id = document.getElementById('id').value;
    var txtsobre = document.getElementById('txtsobre').value;
    var txt1k = document.getElementById('txt1k').value;
    var txt2k = document.getElementById('txt2k').value;
    var txt3k = document.getElementById('txt3k').value;
    var txt4k = document.getElementById('txt4k').value;
    var txt5k = document.getElementById('txt5k').value;

    var origen = document.getElementById('cboOrigen').value;
    var destino = document.getElementById('cboDestino').value;
    var moneda = document.getElementById('cboMoneda').value;

    //    var empresa = $("#cbo_empresa").val();

    if (id != '') {
        updateTarifarioZona(id, origen, destino, moneda, txtsobre, txt1k, txt2k, txt3k, txt4k, txt5k);
    } else {
        insertTarifarioZona(origen, destino, moneda, txtsobre, txt1k, txt2k, txt3k, txt4k, txt5k);
    }
}
function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}
function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function insertTarifarioZona(origen, destino, moneda, txtsobre, txt1k, txt2k, txt3k, txt4k, txt5k) {
    if (validar_caja_form()) {

        deshabilitarBoton();
        loaderShow();
        ax.setAccion("insertTarifarioZona");
        ax.addParamTmp("origen", origen);
        ax.addParamTmp("destino", destino);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("txtsobre", txtsobre);
        ax.addParamTmp("txt1k", txt1k);
        ax.addParamTmp("txt2k", txt2k);
        ax.addParamTmp("txt3k", txt3k);
        ax.addParamTmp("txt4k", txt4k);
        ax.addParamTmp("txt5k", txt5k);

        ax.consumir();
    }
}
function updateTarifarioZona(id, origen, destino, moneda, txtsobre, txt1k, txt2k, txt3k, txt4k, txt5k) {
    if (validar_caja_form()) {
        deshabilitarBoton();
        loaderShow();
        ax.setAccion("updateTarifarioZona");
        ax.addParamTmp("id_tarifario", id);
        ax.addParamTmp("origen", origen);
        ax.addParamTmp("destino", destino);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("txtsobre", txtsobre);
        ax.addParamTmp("txt1k", txt1k);
        ax.addParamTmp("txt2k", txt2k);
        ax.addParamTmp("txt3k", txt3k);
        ax.addParamTmp("txt4k", txt4k);
        ax.addParamTmp("txt5k", txt5k);
        ax.consumir();
    }
}


// , "width": "50px"
function onResponseDocumentoPorCuenta(data) {
    if (!isEmptyData(data)) {
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
    } else {
        var table = $('#datatableDocumentoPorCuenta').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este documentoTipo.")
    }
}

var actualizandoBusqueda = false;
function exportarReportePorCuentaExcel() {
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReportePorCuentaExcel");
    ax.addParamTmp("criterios", valoresBusquedaPorCuenta);
    ax.consumir();
}

function loaderBuscar() {
    actualizandoBusqueda = true;
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false") {
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
function limpiar() {

    select2.asignarValor('cboOrigen', '');
    select2.asignarValor('cboDestino', '');
    asignarValorSelect2("cboMoneda", 2);
    $("#txtsobre").val("");
    $("#txt1k").val("");
    $("#txt2k").val("");
    $("#txt3k").val("");
    $("#txt4k").val("");
    $("#txt5k").val("");
    $("#id").val("");

}

function openModal() {
    $('[data-toggle="popover"]').popover('hide');
    $("#addTarifario").modal("show");
    $("#modalTitulo").empty();
    $("#modalTitulo").html("Nuevo Tarifario Zona");
    obtenerConfiguracionesInicialesPorActividad();
}

function cerrarModalTarifario() {
    $("#addTarifario").modal("hide");
    cleanForm();
}

function cleanForm() {
    $("#addTarifario").modal("hide");
    document.getElementById("id").value = "";
    select2.asignarValor("cboOrigen", "");
    select2.asignarValor("cboDestino", "");
    select2.asignarValor("cboMoneda", "");
    document.getElementById("txtsobre").value = "";
    document.getElementById("txt1k").value = "";
    document.getElementById("txt2k").value = "";
    document.getElementById("txt3k").value = "";
    document.getElementById("txt4k").value = "";
    document.getElementById("txt5k").value = "";
    $("#msj_moneda").hide();
    $("#msj_origen").hide();
    $("#msj_destino").hide();
    $("#msj0").hide();
    $("#msj1").hide();
    $("#msj2").hide();
    $("#msj3").hide();
    $("#msj4").hide();
    $("#msj5").hide();
}


// importar tarifario
function importTarifario() {
    // colapsarBuscador();
    $('[data-toggle="popover"]').popover('hide');
    $("#resultado").empty();
    $("#btnImportar").show();
    $("#btnSalirModal").empty();
    $("#btnSalirModal").append(
            "<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar"
            );
    $("#modalImport").modal("show");
    $("#fileImport").val("");
}

function importar() {
    $("#resultado").empty();
    $("#btnImportar").hide();
    $("#btnSalirModal").empty();
    $("#btnSalirModal").append(
            "<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir"
            );

    var file = document.getElementById("secret").value;
    //    console.log(file);
    loaderShow(".modal-content");
    ax.setAccion("importTarifarioZona");
    ax.addParam("file", file);
    ax.consumir();
}