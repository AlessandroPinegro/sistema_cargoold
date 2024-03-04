var botonEnviar = $('#btnEnviar i').attr('class');


$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    $('#fechaDocumentoForm').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    }).on('changeDate', function () {
        obtenerTipoDeCambio(this.value);
    });

    ax.setSuccess("onResponseLibroDiario");
    obtenerConfiguracionesInicialesLibroDiario();
});
var dataLibro = [];
var dataCuentasContables = [];
var dataCentroCostos = [];
var dataPeriodo = [];
var dataPersona = [];
var dataDocumento = [];
var dataMoneda = [];
var valoresBusquedaLibroDiario = [{persona: null}];
var distribucionContable = [];
var contadorLineas = 0;

function onResponseLibroDiario(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionInicial':
                onResponseObtenerConfiguracionesInicialesLibroDiario(response.data);
                break;
            case 'obtenerContVoucher':
                if (response.tag == 1) {
                    obtenerActulizarGlosa(response.data);
                    loaderClose();
                } else {
                    onResponseObtenerAsiento(response.data);
                }
                break;
            case 'listarLibroDiarioXCriterios':
                onResponseListarLibroDiario(response.data);
                break;

            case 'generarAsientosCierreApertura':

                if (response.tag == 0) {
                    location.href = URL_BASE + "util/formatos/" + response.data;
                } else {
                    onResponseRegistrarAsientoContable(response.data);
                }
                break;

            case 'exportarLibroDiario':
                loaderClose();
                if (response.tag == 'excel') {
                    location.href = URL_BASE + "util/formatos/libroDiario.xlsx";
                } else if (response.tag == 'txt') {
                    var link = document.createElement("a");
                    link.download = response.data;
                    link.href = URL_BASE + "util/uploads/" + response.data;
                    link.click();
                }
                break;
            case 'registrarAsientoContable':
                loaderClose();
                onResponseRegistrarAsientoContable(response.data);
                break;
            case 'actualizarGlosaVoucher':
                loaderClose();
                $('#modalEditarGlosa').modal('hide');
                mostrarOk('Se actualizó la glosa del asiento.');
                buscarLibroDiario();
                break;
            case 'anularContVoucher':
                loaderClose();
                mostrarOk('Se registro anulo el asiento.');
                buscarLibroDiario();
                break;

            case 'obtenerTipoDeCambio':
                loaderClose();
                onResponseObtenerTipoDeCambio(response.data);
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'registrarAsientoContable':
                habilitarBoton();
                loaderClose();
                break;
            default :
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesInicialesLibroDiario() {
    ax.setAccion("obtenerConfiguracionInicial");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesInicialesLibroDiario(data) {

    dataLibro = data.dataLibro;
    dataCuentasContables = data.dataCuentasContables;
    dataPeriodo = data.dataPeriodo;
    dataPersona = data.dataPersonaActiva;
    dataCentroCostos = data.dataCentroCostos;
    dataMoneda = data.dataMoneda;
    dataDocumento = data.dataDocumento;

    if (!isEmpty(data.dataEjercicio)) {
        let dataFilter = filerArrayOfElement(data.dataEjercicio, 'valor', 'A');
        if (!isEmpty(dataFilter)) {
            select2.cargar('cboEjercicio', data.dataEjercicio, 'anio', 'anio');
            select2.asignarValor('cboEjercicio', data.ejercicioActual);
        }
    }

    select2.cargar('cboTipoAsiento', [{id: "AP", text: "Asiento de apertura"}, {id: "PC", text: "Asiento de pre cierre"}, {id: "CC", text: "Asiento de cierre"}], 'id', 'text');

    select2.asignarValor('cboPeriodoInicio', data.dataPeriodoActual[0]['id']);

    select2.cargarSeleccione('cboLibro', data.dataLibro, 'id', ['codigo', 'descripcion'], "Seleccione");
    select2.cargar('cboPeriodoInicio', data.dataPeriodo, 'id', ['anio', 'mes']);
    select2.asignarValor('cboPeriodoInicio', data.dataPeriodoActual[0]['id']);
    select2.asignarValor('cboLibro', "");

    $.each(dataCuentasContables, function (index, item) {
        var html = llenarCuentasContable(item, '', 'cboCuentaContableLibroDiario');
        $('#cboCuentaContableLibroDiario').append(html);
    });

    if (!isEmpty(dataCentroCostos)) {
        $.each(dataCentroCostos, function (indexPadre, centroCostoPadre) {
            if (isEmpty(centroCostoPadre.centro_costo_padre_id)) {
                var html = '<optgroup id="' + centroCostoPadre.id + '" label="' + centroCostoPadre['codigo'] + ' | ' + centroCostoPadre['descripcion'] + '">';
                var dataHijos = dataCentroCostos.filter(centroCosto => centroCosto.centro_costo_padre_id == centroCostoPadre.id);
                $.each(dataHijos, function (indexHijo, centroCostoHijo) {
                    html += '<option value="' + centroCostoHijo['id'] + '">' + centroCostoHijo['codigo'] + " | " + centroCostoHijo['descripcion'] + '</option>';
                });
                html += ' </optgroup>';
                $('#cboCentroCostoLibroDiario').append(html);
            }
        });
        $("#cboCentroCostoLibroDiario").select2({
            width: "100%"
        });
    }

    onChangeCentroCosto("");

    select2.cargarSeleccione('cboPersonaLibroDiario', data.dataPersonaActiva, 'id', ['codigo_identificacion', 'nombre'], "Seleccione");
    select2.cargar('cboLibroForm', data.dataLibro, 'id', ['codigo', 'descripcion']);
    select2.cargar('cboPeriodoForm', data.dataPeriodo, 'id', ['anio', 'mes']);
    select2.cargar('cboMonedaForm', data.dataMoneda, 'id', ['simbolo', 'descripcion']);
    select2.asignarValor('cboPeriodoForm', data.dataPeriodoActual[0]['id']);
    select2.asignarValor('cboMonedaForm', 2);
    select2.asignarValor('cboPersonaLibroDiario', "");

    onResponseListarLibroDiario(data.dataLibroDiario);
}

function llenarCuentasContable(item, extra, cbo_id) {
    var cuerpo = '';
    if ($("#" + cbo_id + " option[value='" + item['id'] + "']").length != 0) {
        return cuerpo;
    }
    if (item.hijos * 1 == 0) {
        cuerpo = '<option value="' + item['id'] + '">' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
        return cuerpo;
    }
    cuerpo = '<option value="' + item['id'] + '" disabled>' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
    var dataHijos = dataCuentasContables.filter(cuentaContable => cuentaContable.plan_contable_padre_id == item.id);
    $.each(dataHijos, function (indexHijo, cuentaContableHijo) {
        cuerpo += llenarCuentasContable(cuentaContableHijo, extra + '&nbsp;&nbsp;&nbsp;&nbsp;', cbo_id);
    });
    return cuerpo;
}

function cargarDatosBusquedaLibroDiario() {
//    var personaId = $('#cboPersona').val();
    var libro = $('#cboLibro').val();
    var periodoInicio = $('#cboPeriodoInicio').val();
    var periodoFin = null;
//    var periodoFin = $('#cboPeriodoFin').val();
//    valoresBusquedaLibroDiario[0].persona = personaId;
    valoresBusquedaLibroDiario[0].libro = libro;
    valoresBusquedaLibroDiario[0].empresa = commonVars.empresa;
    valoresBusquedaLibroDiario[0].periodoInicio = periodoInicio;
    valoresBusquedaLibroDiario[0].periodoFin = periodoFin;
}

function buscarLibroDiario(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    obtenerListarLibroDiario();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscarMP = 1;
    if (colapsa === 1)
        colapsarBuscador();
}

function obtenerDatosBusqueda() {
    var cadena = "";
    cargarDatosBusquedaLibroDiario();
//    if (!isEmpty(select2.obtenerValor('cboPersona'))) {
//        cadena += StringNegrita("Persona: ");
//        cadena += select2.obtenerText('cboPersona');
//        cadena += "<br>";
//    }

    if (!isEmpty(select2.obtenerValor('cboPeriodoInicio'))) {
        cadena += StringNegrita("Periodo Inicio: ");
        cadena += select2.obtenerText('cboPeriodoInicio');
        cadena += "<br>";
    }

//    if (!isEmpty(select2.obtenerValor('cboPeriodoFin'))) {
//        cadena += StringNegrita("Periodo Fin: ");
//        cadena += select2.obtenerText('cboPeriodoFin');
//        cadena += "<br>";
//    }

    if (!isEmpty(select2.obtenerValor('cboLibro'))) {
        cadena += StringNegrita("Tipo de Compra: ");
        cadena += select2.obtenerText('cboLibro');
        cadena += "<br>";
    }


    if (!isEmpty(valoresBusquedaLibroDiario[0].fechaEmisionDesde) || !isEmpty(valoresBusquedaLibroDiario[0].fechaEmisionHasta))
    {
        cadena += StringNegrita("Fecha emisión: ");
        cadena += valoresBusquedaLibroDiario[0].fechaEmisionDesde + " - " + valoresBusquedaLibroDiario[0].fechaEmisionHasta;
        cadena += "<br>";
    }
    return cadena;
}

function actualizarGlosa(id) {
    loaderShow();
    ax.setAccion("obtenerContVoucher");
    ax.addParamTmp("voucher_id", id);
    ax.setTag(1);
    ax.consumir();
}

function obtenerActulizarGlosa(data) {
    $('#txtGlosaFormEdit').val(data.dataVoucher[0]['glosa']);
    $('#txtVoucherIdFormEdit').val(data.dataVoucher[0]['id']);
    $('#modalEditarGlosa').modal('show');
}

function onResponseListarLibroDiario(data) {
    $("#dataList").empty();
    var cuerpo_total = '';
    var cabeza = '';
    var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center;'>Cuenta</th>"
            + "<th style='text-align:center;'>Descripción</th>"
            + "<th style='text-align:center;'>Glosa</th>"
            + "<th style='text-align:center;'>Número</th>"
            + "<th style='text-align:center;'>Documento</th>"
            + "<th style='text-align:center;'>Fecha</th>"
            + "<th style='text-align:center;'>Libro</th>"
            + "<th style='text-align:center;'>Dólares</th>"
            + "<th style='text-align:center;'>Debe</th>"
            + "<th style='text-align:center;'>Haber</th>"
            + "<th style='text-align:center;'>Accion</th>"
            + "</tr>"
            + "</thead>"
            + "<tbody>";
    if (!isEmpty(dataLibro) && !isEmpty(data)) {
        $.each(dataLibro, function (indexLibro, libro) {
            var html = '';
            var arrayVoucher = [];
            $.each(data, function (index, item) {
                if (item.cont_libro_id == libro.id) {
                    arrayVoucher.push(item.cont_voucher_id);
                }
            });
            arrayVoucher = arrayVoucher.filter(function (valor, indice, elemento) {
                return elemento.indexOf(valor) === indice;
            });
            if (!isEmpty(arrayVoucher) && arrayVoucher.length > 0) {
                $.each(arrayVoucher, function (index, itemVoucher) {

                    var dataItem = data.filter(function (obj) {
                        return obj.cont_voucher_id == itemVoucher;
                    });
                    var total_debe = 0;
                    var total_haber = 0;
                    var bandera_acciones = true;
                    $.each(dataItem, function (index, item) {
                        var correlativo = correlativo = item.cuo.split(item.libro_codigo + '-')[1];
                        html += '<tr>';
                        html += '<td align="center">' + item.plan_contable_codigo + '</td>';
                        html += '<td align="left">' + item.plan_contable_descripcion + '</td>';
                        html += '<td align="left">' + (!isEmpty(item.glosa) ? item.glosa : '') + '</td>';
                        html += '<td align="center">' + correlativo + '</td>';
                        html += '<td align="center">' + item.documento_referencia + '</td>';
                        html += '<td align="center">' + formatearFechaJS(item.fecha_contabilizacion) + '</td>';
                        html += '<td align="center">' + item.libro_codigo + '</td>';
                        html += '<td align="right">' + formatearNumeroPorCantidadDecimales(item.monto_dolares) + '</td>';
                        html += '<td align="right">' + formatearNumeroPorCantidadDecimales(item.debe_soles) + '</td>';
                        html += '<td align="right">' + formatearNumeroPorCantidadDecimales(item.haber_soles) + '</td>';

                        var acciones = '';
                        //Permite modificar el contenido del asiento, la glosa y eliminar el asiento.
                        if (bandera_acciones && item.identificador_negocio == '5' && item.bandera_periodo_abierto == '1') {
                            acciones = `<a onclick="actualizarGlosa(` + item.cont_voucher_id + `)"><i class="fa fa-comments" style="color:#1ca8dd;"></i></a>&nbsp;&nbsp;&nbsp;`
                                    + '<a  onclick="obtenerAsiento(' + item.cont_voucher_id + ')"><i class="fa fa-edit" style="color:#E8BA2F;"></i></a>&nbsp;&nbsp;&nbsp;'
                                    + '<a   onclick="confirmarEliminar(' + item.cont_voucher_id + ')"><i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
                            bandera_acciones = false;
                            //Permite solo modificar la glosa.
                        } else if (bandera_acciones && item.bandera_periodo_abierto == '1') {
                            acciones = `<a onclick="actualizarGlosa(` + item.cont_voucher_id + `)"><i class="fa fa-comments" style="color:#1ca8dd;"></i></a>&nbsp;&nbsp;&nbsp;`;
                            bandera_acciones = false;
                        }

                        html += '<td align="center">' + acciones + '</td>';
                        html += '</tr>';
                        total_debe += item.debe_soles * 1;
                        total_haber += item.haber_soles * 1;
                    });
                    html += '<tr>';
                    html += '<td colspan="8"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td style="display: none;"></td>';
                    html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(total_debe) + '</b></td>';
                    html += '<td align="right"><b>' + formatearNumeroPorCantidadDecimales(total_haber) + '</b></td>';
                    html += '<td></td>';
                    html += '</tr>';
                });
            }
            cuerpo_total += html;
        });
    }
    var pie = "</tbody></table>";
    var tabla = cabeza + cuerpo_total + pie;
    $("#dataList").append(tabla);
    $('#datatable').dataTable({
        "lengthMenu": [[-1, 10, 25, 50], ["Todo", 10, 25, 50]],
        "order": [],
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
}

function confirmarEliminar(id) {
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás el asiento contable.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            anularAsiento(id);
        }
    });
}

function obtenerListarLibroDiario() {
    ax.setAccion("listarLibroDiarioXCriterios");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    ax.consumir();
}

function exportarLibroDiario(tipo) {
    actualizandoBusqueda = true;
    loaderShow();
    cargarDatosBusquedaLibroDiario();
    ax.setAccion("exportarLibroDiario");
    ax.addParamTmp("criterios", valoresBusquedaLibroDiario);
    ax.addParamTmp("tipo", tipo);
    ax.setTag(tipo);
    ax.consumir();
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

var actualizandoBusqueda = false;
function loaderBuscarVentas() {
    actualizandoBusqueda = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarLibroDiario();
    }
}

function cerrarPopover() {
    if (banderaBuscarMP == 1)

    {
        if (estadoTolltipMP == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        } else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    } else
    {
        $('[data-toggle="popover"]').popover('hide');
    }

    estadoTolltipMP = (estadoTolltipMP == 0) ? 1 : 0;
}

function nuevoAsiento() {
//    contadorLineas = 0;
    $('#txtGlosaForm').val('');
    $('#txtVoucherId').val('');
    $('#txtTipoCambioForm').val('');
    $('#fechaDocumentoForm').val('');
    formatearInputDecimal('.claseNumeroDecimal');
    $('#tBodyDatatableForm').empty();
    recargarTablaAsiento();
    $("#btnCancelarEditarFila").hide();
    $('#modalRegistrarAsiento').modal('show');
    habilitarBoton();

}

function obtenerAsiento(id) {
    loaderShow();
    ax.setAccion("obtenerContVoucher");
    ax.addParamTmp("voucher_id", id);
    ax.consumir();
}

function guardarGlosa() {
    var glosa = $('#txtGlosaFormEdit').val();
    var id = $('#txtVoucherIdFormEdit').val();

    if (isEmpty(glosa)) {
        mostrarAdvertencia('Debe ingresar una glosa para el asiento.');
        return;
    }

    loaderShow('#modalEditarGlosa');
    ax.setAccion("actualizarGlosaVoucher");
    ax.addParamTmp("voucherId", id);
    ax.addParamTmp("glosa", glosa);
    ax.consumir();
}

function anularAsiento(id) {
    loaderShow();
    ax.setAccion("anularContVoucher");
    ax.addParamTmp("voucher_id", id);
    ax.consumir();
}

function onResponseObtenerAsiento(data) {
    
    dataAsientoContable = [];
    if (isEmpty(data) || isEmpty(data.dataVoucher) || isEmpty(data.dataVoucherDetalle)) {
        mostrarError('Error al intentar obtener la información del asiento');
        return;
    }

    select2.asignarValor('cboLibroForm', data.dataVoucher[0].cont_libro_id);
    select2.asignarValor('cboPeriodoForm', data.dataVoucher[0].periodo_id);
    $('#txtGlosaForm').val(data.dataVoucher[0].glosa);
    $('#txtVoucherId').val(data.dataVoucher[0].id);
    $('#tBodyDatatableForm').empty();
    $.each(data.dataVoucherDetalle, function (index, item) {
        if (item.autogenerado == 0) {
            item.fecha = item.fecha_contabilizacion;
            item.monto_debe = 0;
            item.monto_haber = 0;
            if (item.moneda_id == 4) {
                item.monto_debe = (item.debe_dolares * 1);
                item.monto_haber = (item.haber_dolares * 1);
                item.monto_dolares = (item.debe_dolares * 1) + (item.haber_dolares * 1);
            } else if (item.moneda_id == 2) {
                item.monto_debe = (item.debe_soles);
                item.monto_haber = (item.haber_soles);
            }
            dataAsientoContable.push(item);
        }
    });
    recargarTablaAsiento();
    loaderClose();
    $("#btnCancelarEditarFila").hide();
    $('#modalRegistrarAsiento').modal('show');
}

function eliminarFilaAsiento(indice) {
    dataAsientoContable.splice(indice, 1);
    recargarTablaAsiento();
}

var dataAsientoContable = [];
function agregarFilaAsiento() {

    var obj_linea = {};

    var fecha = $('#fechaDocumentoForm').val();
    var moneda_id = $('#cboMonedaForm').val();
    var tipo_cambio = $('#txtTipoCambioForm').val();

    if (isEmpty(fecha)) {
        mostrarAdvertencia('Debe ingresar una fecha para el asiento.');
        return;
    }

    if (isEmpty(moneda_id)) {
        mostrarAdvertencia('Debe seleccionar una moneda para el asiento.');
        return;
    }

    if (isEmpty(tipo_cambio)) {
        mostrarAdvertencia('Debe ingresar el tipo de cambio.');
        return;
    }

    var plan_contable_id = select2.obtenerValor("cboCuentaContableLibroDiario");
    if (isEmpty(plan_contable_id)) {
        mostrarAdvertencia('Debe seleccionar una cuenta contable.');
        return;
    }

    let plan_contable = dataCuentasContables.find(item => item.id == plan_contable_id);
    let  plan_contable_codigo = plan_contable.codigo;
    obj_linea.plan_contable_id = plan_contable_id;
    obj_linea.plan_contable_codigo = plan_contable.codigo;
    obj_linea.plan_contable_descripcion = plan_contable.descripcion;

    if (plan_contable_codigo.substring(0, 1) == '6' && plan_contable_codigo.substring(0, 2) != '60' && plan_contable_codigo.substring(0, 2) != '61' && plan_contable_codigo.substring(0, 2) != '69') {
        var centro_costo_id = select2.obtenerValor("cboCentroCostoLibroDiario");
        if (isEmpty(centro_costo_id)) {
            mostrarAdvertencia('Debe seleccionar un centro de costo.');
            return;
        }
        let centro_costo = dataCentroCostos.find(item => item.id == centro_costo_id);
        obj_linea.centro_costo_id = centro_costo_id;
        obj_linea.centro_costo_codigo = centro_costo.codigo;
        obj_linea.centro_costo_descripcion = centro_costo.descripcion;
    }

    var persona_id = select2.obtenerValor("cboPersonaLibroDiario");
    var documento_id = select2.obtenerValor("cboDocumentoLibroDiario");

    if (!isEmpty(persona_id) && persona_id != '-1') {
        obj_linea.persona_id = persona_id;
    }

    if (!isEmpty(documento_id) && documento_id != '-1') {
        obj_linea.documento_id = documento_id;
    }

    var montoDebe = $('#txtMontoDebe').val();
    var montoHaber = $('#txtMontoHaber').val();

    montoDebe = (!isEmpty(montoDebe) ? montoDebe.replace(/,/g, '') * 1 : 0);
    montoHaber = (!isEmpty(montoHaber) ? montoHaber.replace(/,/g, '') * 1 : 0);

    if (montoDebe > 0 && montoHaber > 0) {
        mostrarAdvertencia('El haber o el debe tiene que ser cero.');
        return;
    } else if (montoDebe == 0 && montoHaber == 0) {
        mostrarAdvertencia('El haber o el debe tiene que ser mayor que cero.');
        return;
    }

    obj_linea.fecha = datex.parserControlador(fecha);
    obj_linea.moneda_id = moneda_id;
    obj_linea.tipo_cambio = tipo_cambio;
    if (montoDebe > 0) {
        obj_linea.monto_debe = montoDebe;
    } else {
        obj_linea.monto_haber = montoHaber;
    }

    if (moneda_id == 4) {
        obj_linea.monto_dolares = (montoDebe + montoHaber);
    }
    let indice = $("#txtIndiceLineaAsientoEdita").val();
    if (!isEmpty(indice)) {
        cancelarEdicionFilaAsiento();
        dataAsientoContable[indice] = obj_linea;
    } else {
        dataAsientoContable.push(obj_linea);
    }
    recargarTablaAsiento();
    loaderShow("#modalRegistrarAsiento");
    setTimeout(function () {
        loaderClose();
    }, 500);
}

function recargarTablaAsiento() {
    var montoDebeTotal = 0;
    var montoHaberTotal = 0;
    var montoDolaresTotal = 0;

    $('#tBodyDatatableForm').empty();
    $.each(dataAsientoContable, function (index, item) {
        let monto_debe = (!isEmpty(item.monto_debe) ? (item.monto_debe * 1) : 0);
        let monto_haber = (!isEmpty(item.monto_haber) ? (item.monto_haber * 1) : 0);
        let tipo_cambio = (!isEmpty(item.tipo_cambio) ? item.tipo_cambio * 1 : 1);
        let fila = "<tr>";
        fila += "<td style='border:0; vertical-align: middle; padding: 1px;' align='center'>" + (index + 1) + "</td>";
        fila += "<td style='border:0; vertical-align: middle; padding: 1px;'>" + (item.plan_contable_codigo + " | " + item.plan_contable_descripcion) + "</td>";
        fila += "<td style='border:0; vertical-align: middle; padding: 1px;'>";
        if (!isEmpty(item.centro_costo_id)) {
            fila += (item.centro_costo_codigo + " | " + item.centro_costo_descripcion);
        }
        fila += "</td>";
        fila += "<td style='border:0; vertical-align: middle; padding: 1px;'>";
        if (!isEmpty(item.persona_id)) {
            let persona = dataPersona.filter(data => data.id == item.persona_id);
            if (!isEmpty(persona)) {
                fila += persona[0]['codigo_identificacion'];
                fila += " | ";
                fila += persona[0]['nombre'];
            }
        }
        fila += "</td>";
        fila += "<td style='border:0; vertical-align: middle; padding: 1px;'>";
        if (!isEmpty(item.documento_id)) {
            let documento = dataDocumento.filter(data => data.id == item.documento_id);
            if (!isEmpty(documento)) {
                fila += documento[0]['codigo'];
                fila += " | ";
                fila += documento[0]['serie_numero'];
            }
        }
        fila += "</td>";

        fila += "<td style='border:0; vertical-align: middle; padding: 1px;text-align: right;'>";
        fila += item.fecha.substring(0, 10);
        fila += "</td>";


        fila += "<td style='border:0; vertical-align: middle; padding: 1px;text-align: right;'>";
        fila += "" + formatearNumeroPorCantidadDecimales(item.monto_dolares, 2);
        fila += "</td>";

        fila += "<td style='border:0; vertical-align: middle; padding: 1px;text-align: right;'>";
        fila += "" + formatearNumeroPorCantidadDecimales((item.moneda_id == 4 ? (monto_debe * tipo_cambio) : monto_debe), 2);
        fila += "</td>";

        fila += "<td style='border:0; vertical-align: middle; padding: 1px;text-align: right;'>";
        fila += "" + formatearNumeroPorCantidadDecimales((item.moneda_id == 4 ? (monto_haber * tipo_cambio) : monto_haber), 2);
        fila += "</td>";

        fila += "<td style='border:0; vertical-align: middle; padding: 5px;text-align: right;'>";
        if (item.moneda_id == 4) {
            fila += "" + formatearNumeroPorCantidadDecimales(item.tipo_cambio, 4);
        } else {
            fila += "-";
        }
        fila += "</td>";

        fila += "<td style='border:0; vertical-align: middle; padding: 10px;'>";
        fila += '<a  onclick="editarLineaAsiento(' + index + ')"><i class="fa fa-edit" style="color:#E8BA2F;"></i></a>';
        fila += "&nbsp;&nbsp;<a onclick='eliminarFilaAsiento(" + index + ");'><i class='fa fa-trash-o' style='color:#cb2a2a;' title='Eliminar'></i></a>";
        fila += "</td>";
        fila += '</tr>';

        montoDolaresTotal += (item.monto_dolares * 1);
        montoDebeTotal += (item.moneda_id == 4 ? (monto_debe * tipo_cambio) : monto_debe);
        montoHaberTotal += (item.moneda_id == 4 ? (monto_haber * tipo_cambio) : monto_haber);

        $('#tBodyDatatableForm').append(fila);
    });

    $('#pTotalDolares').html(formatearNumeroPorCantidadDecimales(montoDolaresTotal, 2));
    $('#pTotalDebe').html(formatearNumeroPorCantidadDecimales(montoDebeTotal, 2));
    $('#pTotalHaber').html(formatearNumeroPorCantidadDecimales(montoHaberTotal, 2));
}

function editarLineaAsiento(indice) {
    select2.asignarValor("cboMonedaForm", dataAsientoContable[indice]['moneda_id']);
    select2.asignarValor("cboCuentaContableLibroDiario", dataAsientoContable[indice]['plan_contable_id']);
    onChangeCentroCosto(dataAsientoContable[indice]['plan_contable_id']);
    if (!isEmpty(dataAsientoContable[indice]['centro_costo_id'])) {
        select2.asignarValor("cboCentroCostoLibroDiario", dataAsientoContable[indice]['centro_costo_id']);
    }

    select2.asignarValor("cboPersonaLibroDiario", dataAsientoContable[indice]['persona_id']);
    onChangeCboPersona(dataAsientoContable[indice]['persona_id']);
    if (!isEmpty(dataAsientoContable[indice]['documento_id'])) {
        select2.asignarValor("cboDocumentoLibroDiario", dataAsientoContable[indice]['documento_id']);
    }
    $("#fechaDocumentoForm").datepicker("setDate", datex.parserFecha(dataAsientoContable[indice]['fecha']));
    $('#txtTipoCambioForm').val(dataAsientoContable[indice]['tipo_cambio']);
    if (!isEmpty(dataAsientoContable[indice]['monto_debe']) && (dataAsientoContable[indice]['monto_debe'] * 1) > 0) {
        $('#txtMontoDebe').val(formatearNumeroPorCantidadDecimales(dataAsientoContable[indice]['monto_debe'], 2));
        $('#txtMontoHaber').val(0);
    } else if (!isEmpty(dataAsientoContable[indice]['monto_haber']) && (dataAsientoContable[indice]['monto_haber'] * 1) > 0) {
        $('#txtMontoHaber').val(formatearNumeroPorCantidadDecimales(dataAsientoContable[indice]['monto_haber'], 2));
        $('#txtMontoDebe').val(0);
    }
    $("#btnCancelarEditarFila").show();
    $("#txtTipoCambioForm").focus();
    $("#txtIndiceLineaAsientoEdita").val(indice);
}

function cancelarEdicionFilaAsiento() {
    $("#btnCancelarEditarFila").hide();
    $("#txtIndiceLineaAsientoEdita").val('');
}

function onChangeCboPersona(valor) {
    let data = [];
    if (!isEmpty(dataDocumento) && !isEmpty(valor) && valor != -1) {
        data = dataDocumento.filter(item => item.persona_id == valor && !isEmpty(item.serie_numero));
    }
    select2.cargarSeleccione('cboDocumentoLibroDiario', data, 'id', ['codigo', 'serie_numero'], "Seleccione");
}

function onChangeDebeHaber(bandera_debe_haber) {
    // Si modifico la celda del debe -> el haber pasa a 0 y vice versa;
    if (bandera_debe_haber == 0) {
        $("#txtMontoHaber").val(0);
    } else if (bandera_debe_haber == 1) {
        $("#txtMontoDebe").val(0);
    }
}

function onChangeCentroCosto(valor) {
    let cuenta = dataCuentasContables.find(item => item.id == valor);
    if (cuenta !== undefined && cuenta.codigo.substring(0, 1) == '6' && cuenta.codigo.substring(0, 2) != '60' && cuenta.codigo.substring(0, 2) != '61' && cuenta.codigo.substring(0, 2) != '69') {
        $("#cboCentroCostoLibroDiario").prop("disabled", false);
    } else {
        select2.asignarValor("cboCentroCostoLibroDiario", "");
        $("#cboCentroCostoLibroDiario").prop("disabled", true);
    }
}

function guardarAsiento() {
    var libro_id = $('#cboLibroForm').val();
    var periodo_id = $('#cboPeriodoForm').val();
    var glosa = $('#txtGlosaForm').val();
    var id = $('#txtVoucherId').val();

    if (isEmpty(libro_id)) {
        mostrarAdvertencia('Debe seleccionar un libro antes de guardar.');
        return;
    }

    if (isEmpty(periodo_id)) {
        mostrarAdvertencia('Debe seleccionar el periodo.');
        return;
    }

    if (isEmpty(glosa)) {
        mostrarAdvertencia('Debe ingresar una glosa para el asiento.');
        return;
    }

    if (isEmpty(dataAsientoContable)) {
        mostrarAdvertencia('Aún no ingresa información para el asiento contable.');
        return;
    }
    let nuevoArray = [];
    $.each(dataAsientoContable, function (index, item) {
        let nuevoObjeto = {};
        nuevoObjeto = Object.assign({}, item);

        var montoDebe = item.monto_debe;
        var montoHaber = item.monto_haber;
        if (montoDebe > 0) {
            nuevoObjeto.montoDebe = montoDebe;
        } else {
            nuevoObjeto.montoHaber = montoHaber;
        }

        nuevoArray.push(nuevoObjeto);
    });
    deshabilitarBoton();
    loaderShow("#modalRegistrarAsiento");
    ax.setAccion("registrarAsientoContable");
    ax.addParamTmp("voucherId", id);
    ax.addParamTmp("libroId", libro_id);
    ax.addParamTmp("periodoId", periodo_id);
    ax.addParamTmp("monedaId", nuevoArray[0]['moneda_id']);
    ax.addParamTmp("glosa", glosa);
    ax.addParamTmp("distribucionContable", nuevoArray);
    ax.consumir();
}

function onResponseRegistrarAsientoContable(data) {
    habilitarBoton();
    if (isEmpty(data)) {
        mostrarAdvertencia('Error al intentar guardar el asiento.');
        return;
    }
    if (data[0].vout_exito != 1) {
        mostrarAdvertencia(data[0].vout_mensaje);
        return;
    }
    $('#modalRegistrarAsiento').modal('hide');
    swal("Correcto!", data[0]['vout_mensaje'], "success");
    buscarLibroDiario();
}

function obtenerTipoDeCambio(fecha) {
    if (!isEmpty(fecha)) {
        loaderShow("#modalRegistrarAsiento");
        ax.setAccion("obtenerTipoDeCambio");
        ax.addParamTmp("fecha", fecha);
        ax.consumir();
    }
}

function onResponseObtenerTipoDeCambio(data) {
    if (!isEmpty(data)) {
        $('#txtTipoCambioForm').val(data[0].equivalencia_venta);
    } else {
        $('#txtTipoCambioForm').val(1);
    }
}

function formatearInputDecimal(claseNumeroDecimal) {
    $(claseNumeroDecimal).inputmask({
        'alias': 'decimal',
        rightAlign: true,
        'groupSeparator': '.',
        'autoGroup': true
    });
}


function abrirModalAperturaCierre() {
    $('#modalGenerarAsientoAperturaCierre').modal('show');
}

function generarAsientoCierreApertura(banderaGenerar) {
    var anio = $('#cboEjercicio').val();
    var tipoAsiento = $('#cboTipoAsiento').val();

    if (isEmpty(anio)) {
        mostrarAdvertencia('Debe seleccionar el ejercicio.');
        return;
    }

    if (isEmpty(tipoAsiento)) {
        mostrarAdvertencia('Debe seleccionar el tipo de asiento.');
        return;
    }

    loaderShow("#modalGenerarAsientoAperturaCierre");
    ax.setAccion("generarAsientosCierreApertura");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("anio", anio);
    ax.addParamTmp("tipo", tipoAsiento);
    ax.addParamTmp("banderaGenerar", banderaGenerar);
    ax.setTag(banderaGenerar);
    ax.consumir();
}


function deshabilitarBoton()
{
    $("#btnEnviar").addClass('disabled');
    $("#btnEnviar i").removeClass(botonEnviar);
    $("#btnEnviar i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#btnEnviar").removeClass('disabled');
    $("#btnEnviar i").removeClass('fa-spinner fa-spin');
    $("#btnEnviar i").addClass(botonEnviar);
}