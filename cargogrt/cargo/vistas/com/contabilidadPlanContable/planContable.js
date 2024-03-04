$(document).ready(function () {
    ax.setSuccess("exitoPlanContable");
    loaderShow();
    listarPlanContablePadres();
    cargarSelect2();
//    cargarComponenteNestable();
    obtenerConfiguracionesIniciales();
    updateCheckBox();
});


function listarPlanContablePadres() {
    ax.setAccion("listarPlanContablePadres");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function exitoPlanContable(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarPlanContablePadres':
                onResponseListarPlanContablePadres(response.data);
                loaderClose();
                break;
            case 'obtenerHijos':
                onResponseObtenerHijos(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerCuentaEdicion':
                onResponseObtenerCuentaEdicion(response.data);
                loaderClose();
                break;
            case 'guardarCuenta':
                $('#divFormulario').modal('hide');
                $('.modal-backdrop').hide();
                onResponseGuardarCuenta(response.data);
                loaderClose();
                break;
            case 'eliminarCuenta':
                onResponseEliminarCuenta(response.data);
                loaderClose();
                break;
            case 'exportarPlanContable':
                loaderClose();
                if (response.tag == 'excel') {
                    location.href = URL_BASE + "util/formatos/PlanContable.xlsx";
                } else if (response.tag == 'txt' && !isEmpty(response.data)) {
                    var link = document.createElement("a");
                    link.download = response.data;
                    link.href = URL_BASE + "util/uploads/" + response.data;
                    link.click();
                } else {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', "No se obtuvo la información del Plan Contable.");
                }
                break;
        }
    }
}


function exportarRegistroCompras(tipo) {
    
    if (tipo == 'txt') {
        var periodo = select2.obtenerText('cboPeriodo');
        if (periodo === 'Periodo') {
            mostrarAdvertencia('Debe seleccionar un periodo');
            return;
        }    
    }    
    loaderShow();
    ax.setAccion("exportarPlanContable");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("tipo", tipo);
    ax.addParamTmp("periodo",periodo);
    ax.setTag(tipo);
    ax.consumir();
}

function cargarNestable(id) {
    $('#' + id).nestable({
        group: 1
    }).on('change', this.updateOutput);
}

function expandirTodo() {
    $('.dd').nestable('expandAll');
}

function contraerTodo() {
    $('.dd').nestable('collapseAll');
}

function onResponseListarPlanContablePadres(data) {
//    console.log(data);
    var estilo = "";

    $("#nestableLista").empty();
//    var html='<ol class="dd-list" style="display: inline-block;">';
    var html = '<ol class="dd-list" style="width:1150px;">';

    if (!isEmpty(data)) {
        $.each(data, function (index, item) {

            if (item.titulo == 1) {
                estilo = "font-weight: bold;text-transform: uppercase;";
            } else {
                estilo = "font-weight: normal;text-transform: uppercase;";
            }

            html += '<li id="li' + item.id + '" class="dd-item dd3-item" data-id="' + item.id + '" onclick="obtenerHijos(' + item.id + ')" >';
//            html+='<div class="dd-handle dd3-handle"></div>';
            html += '<div class="dd3-content">';
            html += '<a href="#" style="' + estilo + '" onclick="obtenerFormularioEdicion(' + item.id + ')" id="descCuenta' + item.id + '">' + item.codigo + ' ' + item.descripcion + '</a>';
            if (item.criterio == 1)
            {
                html += '<a title="Eliminar cuenta" onclick="confirmarEliminarCuenta(' + item.id + ',\'' + item.codigo + '\',\'' + item.descripcion + '\')" style="float: right;">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
            }
            if (item.criteriocreacion != 0)
            {
                html += '<a href="#" title="Nueva cuenta" onclick="nuevaCuenta(' + item.id + ',\'' + item.codigo + '\',\'' + item.descripcion + '\')" style="float: right;" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
            }
            html += '</div>';
            if (item.hijos > 0) {
                html += '<ol class="dd-list" id="ol' + item.id + '"><input type="hidden" name="hid' + item.id + '" id="hid' + item.id + '" value="0"/></ol>';
            }
            html += '</li>';
        });
    }

    html += '</ol>';
    $("#nestableLista").append(html);

    cargarNestable('nestableLista');
    contraerTodo();

}

function obtenerHijos(padreId) {
//    console.log($('#hid'+padreId).val());
//    if ($("#ol" + padreId).length == 1) {
    if ($('#hid' + padreId).val() == 0) {
        ax.setAccion("obtenerHijos");
        ax.addParamTmp("padreId", padreId);
        ax.consumir();
    }
}

function onResponseObtenerHijos(data) {
//    console.log(data[0]['plan_contable_padre_id']);   
    var estilo = "";

    if (!isEmpty(data)) {
        var padreId = data[0]['plan_contable_padre_id'];
//        $("#ol"+padreId).empty();        
        var html = '';
        if (!isEmpty(data)) {
            $.each(data, function (index, item) {
                if (item.titulo == 1) {
                    estilo = "font-weight: bold;text-transform: uppercase;";
                } else {
                    estilo = "font-weight: normal;text-transform: uppercase;";
                }

                html += '<li id="li' + item.id + '" class="dd-item dd3-item dd-collapsed" data-id="' + item.id + '" onclick="obtenerHijos(' + item.id + ')" >';
                if (item.hijos > 0) {
                    html += '<button data-action="collapse" type="button" style="display: none;">Collapse</button>';
                    html += '<button data-action="expand" type="button" style="display: block;">Expand</button>';
                }
//                html+='<div class="dd-handle dd3-handle"></div>';
                html += '<div class="dd3-content">';
                html += '<a href="#" style="' + estilo + '" onclick="obtenerFormularioEdicion(' + item.id + ')"  id="descCuenta' + item.id + '">' + item.codigo + ' ' + item.descripcion + '</a>';
                if (item.criterio == 1)
                {
                    html += '<a title="Eliminar cuenta" onclick="confirmarEliminarCuenta(' + item.id + ',\'' + item.codigo + '\',\'' + item.descripcion + '\')" style="float: right;">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
                }
                if (item.criteriocreacion != 1)
                {
                    html += '<a href="#" title="Nueva cuenta" onclick="nuevaCuenta(' + item.id + ',\'' + item.codigo + '\',\'' + item.descripcion + '\')" style="float: right;" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
                }
                html += '</div>';
                if (item.hijos > 0) {
                    html += '<ol class="dd-list" id="ol' + item.id + '"><input type="hidden" name="hid' + item.id + '" id="hid' + item.id + '" value="0"/></ol>';
                }
                html += '</li>';
            });
        }
        $("#ol" + padreId).append(html);
        $('#hid' + padreId).val('1');
    }

}

function obtenerFormularioEdicion(id) {
    loaderShow();
    ax.setAccion("obtenerCuentaEdicion");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}

function cancelar() {
    $('#divFormulario').modal('hide');
    limpiarMensajes();
    limpiarFormulario();
}

function obtenerConfiguracionesIniciales() {
    var empresa = commonVars.empresa;
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParamTmp("empresaId",empresa);
    ax.consumir();
    
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data);

    select2.cargar("cboCuentaTipo", data.cuentaTipo, "id", "descripcion");
    select2.asignarValor("cboCuentaTipo", -1);
    select2.cargar("cboDimension", data.dimension, "id", "descripcion");
    select2.cargar("cboMoneda", data.moneda, "id", ["simbolo", "descripcion"]);
    select2.asignarValor("cboMoneda", 2);
    select2.cargar("cboCuentaExige", data.cuentaExige, "id", "descripcion");
    select2.cargar("cboCuentaNaturaleza", data.cuentaNaturaleza, "id", "descripcion");
    select2.cargar("cboCuentaCargo", data.cuentas, "id", ["codigo", "descripcion"]);
    select2.cargar("cboCuentaAbono", data.cuentas, "id", ["codigo", "descripcion"]);
    select2.cargar("cboPeriodo", data.periodo, "id", ["anio","mes"]);
}

var cuentaId = null;
function onResponseObtenerCuentaEdicion(data) {
//    console.log(data);
    padreCuentaId = null;
    cuentaId = data[0]['cuenta_id'];

    $('#descripcionFormulario').html('ACTUALIZAR CUENTA');
    limpiarMensajes();
    limpiarFormulario();

//    if(isEmpty(data[0]['plan_contable_padre_id'])){
//        $('#txtCodigo').attr("readonly","true");        
//    }else{
//        $('#txtCodigo').removeAttr("readonly");        
//    }

    $('#txtCodigoPadre').val(data[0]['cuenta_codigo_padre']);

    var codigoCuenta = data[0]['cuenta_codigo'].replace(data[0]['cuenta_codigo_padre'], "");

    $('#txtCodigo').val(codigoCuenta);
    $('#txtDescripcion').val(data[0]['cuenta_descripcion']);
    select2.asignarValor("cboEstado", data[0]['cuenta_estado']);
    select2.asignarValor('cboCuentaTipo', data[0]['cuenta_tipo_id']);

    if (data[0]['titulo'] == 1) {
        document.getElementById("chkTitulo").checked = true;
    } else {
        document.getElementById("chkTitulo").checked = false;
    }

    select2.asignarValor("cboMoneda", data[0]['moneda_id']);
    select2.asignarValor("cboCuentaNaturaleza", data[0]['cuenta_naturaleza_id']);
    
    if(!isEmpty(data[0]['cuenta_cargo_id']) && !isEmpty(data[0]['cuenta_abono_id']))
    {
        document.getElementById("chkAsiento").checked = true;
        $("#cboCuentaCargo").prop("disabled", false);
        $("#cboCuentaAbono").prop("disabled", false);
    }
    else{
        document.getElementById("chkAjustar").checked = false;
    }
    select2.asignarValor("cboCuentaCargo", data[0]['cuenta_cargo_id']);
    select2.asignarValor("cboCuentaAbono", data[0]['cuenta_abono_id']);

    if (data[0]['ajustar'] == 1) {
        document.getElementById("chkAjustar").checked = true;
        $("#cboComoAjustar").prop("disabled", false);
        $("#cboTipoCambio").prop("disabled", false);
    } else {
        document.getElementById("chkAjustar").checked = false;
    }
    select2.asignarValor("cboComoAjustar", data[0]['como_ajustar']);
    select2.asignarValor("cboTipoCambio", data[0]['tipo_cambio']);

    $('#txtCodigoEquivalente').val(data[0]['cuenta_equivalente_codigo']);
    $('#txtDescripcionEquivalente').val(data[0]['cuenta_equivalente_descripcion']);

    if (!isEmpty(data[0]['plan_contable_dimension_ids'])) {
        select2.asignarValor("cboDimension", data[0]['plan_contable_dimension_ids'].split(";"));
    }
    if (!isEmpty(data[0]['plan_contable_cuenta_exige_ids'])) {
        select2.asignarValor("cboCuentaExige", data[0]['plan_contable_cuenta_exige_ids'].split(";"));
    }

    $('#divFormulario').modal('show');
}

function guardar() {
    //caja de texto
    var codigo = $('#txtCodigo').val();
    codigo = codigo.trim();
    codigo = $('#txtCodigoPadre').val() + codigo;
    var descripcion = $('#txtDescripcion').val();
    descripcion = descripcion.trim();
    var codigoEqui = $('#txtCodigoEquivalente').val();
    codigoEqui = codigoEqui.trim();
    var descripcionEqui = $('#txtDescripcionEquivalente').val();
    descripcionEqui = descripcionEqui.trim();

    //combos
    var estado = select2.obtenerValor('cboEstado');
    var cuentaTipo = select2.obtenerValor('cboCuentaTipo');
    var moneda = select2.obtenerValor('cboMoneda');
    var naturalezaCuenta = select2.obtenerValor('cboCuentaNaturaleza');
    var cuentaCargo = select2.obtenerValor('cboCuentaCargo');
    var cuentaAbono = select2.obtenerValor('cboCuentaAbono');
    var comoAjustar = select2.obtenerValor('cboComoAjustar');
    var tipoCambio = select2.obtenerValor('cboTipoCambio');


    //combo multiple
    var dimension = $('#cboDimension').val();
    var cuentaExige = $('#cboCuentaExige').val();

    //checks 
    var checkTitulo = 0;
    if (document.getElementById("chkTitulo").checked) {
        checkTitulo = 1;
    }
    var checkAjustar = 0;
    if ($("#chkAjustar").is(':checked')) {
        checkAjustar = 1;
    } else {
        checkAjustar = 0;
    }

    var checkAsiento = 0;
    if ($("#chkAsiento").is(':checked')) {
        checkAsiento = 1;
    } else {
        checkAsiento = 0;
    }

    if (validarFormulario(codigo, descripcion, estado, cuentaTipo, moneda, naturalezaCuenta, cuentaCargo, cuentaAbono, comoAjustar, tipoCambio, checkAsiento, checkAjustar)) {
        guardarCuenta(codigo, descripcion, codigoEqui, descripcionEqui, estado, cuentaTipo, moneda, naturalezaCuenta,
                cuentaCargo, cuentaAbono, comoAjustar, tipoCambio, dimension, cuentaExige, checkTitulo, checkAjustar);
    }

}



function validarFormulario(codigo, descripcion, estado, cuentaTipo, moneda, naturalezaCuenta, cuentaCargo, cuentaAbono, comoAjustar, tipoCambio, checkAsiento, checkAjustar) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();

    if (codigo === "" || codigo === null || espacio.test(codigo) || codigo.length == 0)
    {
        $("#msjCodigo").text("Ingrese un código").show();
        bandera = false;
    }

    if (descripcion === "" || descripcion === null || espacio.test(descripcion) || descripcion.length === 0) {
        $("#msjDescripcion").text("Ingrese descripción").show();
        bandera = false;
    }

    if (estado === "" || estado === null || espacio.test(estado) || estado.length === 0) {
        $("#msjEstado").text("Seleccione un estado").show();
        bandera = false;
    }
    if (cuentaTipo === "" || cuentaTipo === null || espacio.test(cuentaTipo) || cuentaTipo.length === 0) {
        $("#msjCuentaTipo").text("Seleccion una cuenta tipo").show();
        bandera = false;
    }
    if (moneda === "" || moneda === null || espacio.test(moneda) || moneda.length === 0) {
        $("#msjMoneda").text("Seleccion una moneda").show();
        bandera = false;
    }
    if (naturalezaCuenta === "" || naturalezaCuenta === null || espacio.test(naturalezaCuenta) || naturalezaCuenta.length === 0) {
        $("#msjCuentaNaturaleza").text("Seleccion la naturaleza de la cuenta").show();
        bandera = false;
    }

    if (checkAsiento === 1) {
        if (cuentaCargo === "" || cuentaCargo === null || espacio.test(cuentaCargo) || cuentaCargo.length === 0)
        {
            $("#msjCuentaCargo").text("Seleccione una cuenta de cargo. ").show();
            bandera = false;
        }
        if (cuentaAbono === "" || cuentaAbono === null || espacio.test(cuentaAbono) || cuentaAbono.length === 0)
        {
            $("#msjCuentaAbono").text("Seleccione una cuenta de abono. ").show();
            bandera = false;
        }
    }
    if (checkAjustar === 1) {
        if (comoAjustar === "" || comoAjustar === null || espacio.test(comoAjustar) || comoAjustar.length === 0)
        {
            $("#msjComoAjustar").text("Seleccione como ajustar. ").show();
            bandera = false;
        }
        if (tipoCambio === "" || tipoCambio === null || espacio.test(tipoCambio) || tipoCambio.length === 0)
        {
            $("#msjTipoCambio").text("Seleccione tipo de cambio. ").show();
            bandera = false;
        }
    }


    return bandera;
}

function limpiarMensajes() {
    $("#msjCodigo").hide();
    $("#msjEstado").hide();
    $("#msjDescripcion").hide();
    $("#msjCuentaTipo").hide();
    $("#msjMoneda").hide();
    $("#msjCuentaNaturaleza").hide();
    $("#msjCuentaCargo").hide();
    $("#msjCuentaAbono").hide();
    $("#msjComoAjustar").hide();
    $("#msjTipoCambio").hide();
}

function guardarCuenta(codigo, descripcion, codigoEqui, descripcionEqui, estado, cuentaTipo, moneda, naturalezaCuenta,
        cuentaCargo, cuentaAbono, comoAjustar, tipoCambio, dimension, cuentaExige, checkTitulo, checkAjustar) {

    loaderShow();
    ax.setAccion("guardarCuenta");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("codigoEqui", codigoEqui);
    ax.addParamTmp("descripcionEqui", descripcionEqui);
    ax.addParamTmp("estado", estado);
    ax.addParamTmp("cuentaTipo", cuentaTipo);
    ax.addParamTmp("moneda", moneda);
    ax.addParamTmp("naturalezaCuenta", naturalezaCuenta);
    ax.addParamTmp("cuentaCargo", cuentaCargo);
    ax.addParamTmp("cuentaAbono", cuentaAbono);
    ax.addParamTmp("comoAjustar", comoAjustar);
    ax.addParamTmp("tipoCambio", tipoCambio);
    ax.addParamTmp("dimension", dimension);
    ax.addParamTmp("cuentaExige", cuentaExige);
    ax.addParamTmp("checkTitulo", checkTitulo);
    ax.addParamTmp("checkAjustar", checkAjustar);
    ax.addParamTmp("cuentaId", cuentaId);
    ax.addParamTmp("padreCuentaId", padreCuentaId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();

}

function onResponseGuardarCuenta(data) {
//    console.log(data);    

    exitoGuardar(data.resultado);

    if (data.resultado[0]["vout_exito"] == 1) {
        if (isEmpty(data.dataPadre)) {//edicion
            $('#descCuenta' + data.resultado[0]["id"]).html(data.codigo + ' ' + data.descripcion);
        } else {//nuevo
            dibujarFilaCuenta(data.dataPadre);
        }
    }
}

function dibujarFilaCuenta(data) {
//    console.log(data);
    var item = data[0];
    var html = '';
    var estilo = "";

    if (item.titulo == 1) {
        estilo = "font-weight: bold;";
    } else {
        estilo = "font-weight: normal;";
    }

    $("#li" + item.cuenta_id).empty();

//    html += '<li id="li' + item.cuenta_id + '" class="dd-item dd3-item dd-collapsed" data-id="' + item.cuenta_id + '" onclick="obtenerHijos(' + item.cuenta_id + ')" >';
    if (item.hijos > 0) {
        html += '<button data-action="collapse" type="button" style="display: block;">Collapse</button>';
        html += '<button data-action="expand" type="button" style="display: none;">Expand</button>';
    }
//    html += '<div class="dd-handle dd3-handle"></div>';
    html += '<div class="dd3-content">';
    html += '<a href="#"  style="' + estilo + '" onclick="obtenerFormularioEdicion(' + item.cuenta_id + ')"  id="descCuenta' + item.cuenta_id + '" >' + item.cuenta_codigo + ' ' + item.cuenta_descripcion + '</a>';
    if (item.criterio == 1)
    {
        html += '<a title="Eliminar cuenta" onclick="confirmarEliminarCuenta(' + item.cuenta_id + ',\'' + item.cuenta_codigo + '\',\'' + item.cuenta_descripcion + '\')" style="float: right;">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
    }
    if (item.criteriocreacion != 1)
    {
        html += '<a href="#" title="Nueva cuenta" onclick="nuevaCuenta(' + item.cuenta_id + ',\'' + item.cuenta_codigo + '\',\'' + item.cuenta_descripcion + '\')" style="float: right;" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
    }
    html += '</div>';
    if (item.hijos > 0) {
        html += '<ol class="dd-list" id="ol' + item.cuenta_id + '"><input type="hidden" name="hid' + item.cuenta_id + '" id="hid' + item.cuenta_id + '" value="0"/></ol>';
    }
//    html += '</li>';

    $("#li" + item.cuenta_id).append(html);
    $("#li" + item.cuenta_id).removeClass('dd-collapsed');
//    console.log(1);
    obtenerHijos(item.cuenta_id);
}


function exitoGuardar(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
//        cargarPantallaListar();
    }
}

function limpiarFormulario() {
    $('#txtCodigoPadre').val('');
    $('#txtCodigo').val('');
    $('#txtDescripcion').val('');
    select2.asignarValor("cboEstado", 1);
    select2.asignarValor('cboCuentaTipo', null);

    document.getElementById("chkTitulo").checked = false;

    select2.asignarValor("cboMoneda", 2);
    select2.asignarValor("cboCuentaNaturaleza", null);
    select2.asignarValor("cboCuentaCargo", null);
    select2.asignarValor("cboCuentaAbono", null);

    document.getElementById("chkAjustar").checked = false;

    select2.asignarValor("cboComoAjustar", -1);
    select2.asignarValor("cboTipoCambio", -1);

    $('#txtCodigoEquivalente').val('');
    $('#txtDescripcionEquivalente').val('');

    select2.asignarValor("cboDimension", null);
    select2.asignarValor("cboCuentaExige", null);
}

var padreCuentaId = null;
function nuevaCuenta(padreId, codigo, descripcion) {
    padreCuentaId = padreId;
    cuentaId = null;

    $('#descripcionFormulario').html('NUEVA SUB CUENTA DE: ' + codigo + ' ' + descripcion);

    limpiarMensajes();
    limpiarFormulario();

    $('#txtCodigoPadre').val(codigo);
    $('#txtCodigo').removeAttr("readonly");
    $('#divFormulario').modal('show');
}

function confirmarEliminarCuenta(id, codigo, descripcion) {

    swal({
        title: "Estás seguro?",
        text: "Eliminarás la cuenta: " + codigo + ' ' + descripcion + ". Si tuviera sub cuentas también serán eliminadas.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarCuenta(id);
        } else {
            swal("Cancelado", "La eliminación fue cancelada", "error");
        }
    });
}

function eliminarCuenta(id) {
//    alert(id);
    loaderShow();
    ax.setAccion("eliminarCuenta");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function onResponseEliminarCuenta(data) {
    if (data['0'].vout_exito == 1) {
        swal("Eliminado!", "Se eliminó la cuenta: " + data['0'].cuenta_descripcion + ".", "success");

        $("#li" + data['0'].cuenta_id).remove();

    } else {
        swal("Cancelado", "" + data['0'].vout_mensaje, "error");
    }
}
function updateCheckBox()
{

    $("#chkAsiento").click(function () {
        if ($(this).is(':checked'))
        {
            $("#cboCuentaCargo").prop("disabled", false);
            $("#cboCuentaAbono").prop("disabled", false);
        }
        else {
            select2.asignarValor("cboCuentaCargo", null);
            select2.asignarValor("cboCuentaAbono", null);
            $("#cboCuentaCargo").prop("disabled", true);
            $("#cboCuentaAbono").prop("disabled", true);
        }
    });

    $("#chkAjustar").click(function () {
        if ($(this).is(':checked'))
        {
            $("#cboComoAjustar").prop("disabled", false);
            $("#cboTipoCambio").prop("disabled", false);
        }
        else {
            select2.asignarValor("cboComoAjustar", null);
            select2.asignarValor("cboTipoCambio", null);
            $("#cboComoAjustar").prop("disabled", true);
            $("#cboTipoCambio").prop("disabled", true);

        }
    });
}

