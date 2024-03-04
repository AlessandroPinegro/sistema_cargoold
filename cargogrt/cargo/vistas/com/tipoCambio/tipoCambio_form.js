/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var op = document.getElementById("op").value;

$(document).ready(function () {
//    loaderShow();
    ax.setSuccess("exitoTipoCambio");
    configuracionesIniciales();
    $('#btnGuardar i').attr('class');
    var id = document.getElementById("id").value;
    if (!isEmpty(id)) {
        //alert(id);
        ax.setAccion("obtenerTipoCambioXid");
        ax.addParam("id", id);
        ax.consumir();
    } 
    //obtenerEquivalenciaSunat() 
    cargarComponentes();
    altura();
});

function cargarComponentes() {
    cargarSelect2();
    cargarDatePicker('fecha');
}

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

function cargarDatePicker(id) {
    $('#' + id).datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        language: 'es'
    });
}

function configuracionesIniciales() {
    loaderShow();
    var fecha = obtenerFechaActual();    
    
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParam("fecha", fecha);
    ax.consumir();   
        
    //fecha actual
    $('#fecha').val(fecha);       
}

function obtenerFechaActual(){
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if(dd<10) {
        dd='0'+dd;
    } 

    if(mm<10) {
        mm='0'+mm;
    } 

    hoy = dd+'/'+mm+'/'+yyyy;
    
    return hoy;
}

function exitoTipoCambio(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;   
            case 'obtenerMoneda':
                onResponseObtenerDataCbo("Moneda", "id", "moneda_descripcion", response.data);
                break;            
            case 'obtenerMonedaBase':
                onResponseObtenerMonedaBase(response.data);
//                loaderClose();
                break;
            case 'crearTipoCambio':
                exitoCrear(response.data);
                loaderClose();
                break;
            case 'obtenerTipoCambioXid':
                onResponseObtenerTipoCambioXid(response.data);
                loaderClose();
                break;   
            case 'obtenerEquivalenciaSunat':
                onResponseObtenerEquivalenciaSunat(response.data);
                loaderClose();
                break;   
        }
    }
}

function onObtenerConfiguracionesIniciales(data) {
    onResponseObtenerDataCbo("Moneda", "id", "moneda_descripcion", data.moneda);
    onResponseObtenerMonedaBase(data.monedaBase);
    
    if (op == 'Nuevo') {
        onResponseObtenerEquivalenciaSunat(data.equivalenciaSunat);
    }
}


function onResponseObtenerDataCbo(cboId, itemId, itemDes, data) {
    //alert("hola");
    //console.log(data);
    document.getElementById('cbo' + cboId).innerHTML = "";
    asignarValorSelect2('cbo' + cboId, "");
    if (!isEmpty(data)) {
        $('#cbo' + cboId).append('<option></option>');
        $.each(data, function (index, item) {
            $('#cbo' + cboId).append('<option value="' + item[itemId] + '">' + item[itemDes] + '</option>');
        });
    }
    
    if(data.length===1)
        asignarValorSelect2('cbo' + cboId, data[0]["id"]);
}

function onResponseObtenerMonedaBase(data){ 
    $('#txtBase1').val(data[0]["moneda_descripcion"]);
    $('#txtBase2').val(data[0]["moneda_descripcion"]);
}


function enviar(tipoAccion) {
    loaderShow();
    var monedaId = document.getElementById('cboMoneda').value;
    var fecha = $('#fecha').val();
    var equivalenciaCompra = $('#txtEquivalenciaCompra').val();
    var equivalenciaVenta = $('#txtEquivalenciaVenta').val();
          

    if (validarFormulario(monedaId,fecha,equivalenciaCompra ,equivalenciaVenta)) {
        var id = document.getElementById('id').value;
        crearTipoCambio(id,monedaId,fecha,equivalenciaCompra ,equivalenciaVenta);
        
        /*if (tipoAccion === 'Nuevo') {
            crearTipoCambio(monedaId,fecha,equivalenciaCompra ,equivalenciaVenta);
        } else {
            var id = document.getElementById('id').value;
            //actualizarTipoCambio(id, monedaId,fecha,equivalenciaCompra ,equivalenciaVenta);
        }*/
        
    } else {
        //alert('no validado');
        loaderClose();
    }
}

function crearTipoCambio(id,monedaId,fecha,equivalenciaCompra ,equivalenciaVenta) {
    ax.setAccion("crearTipoCambio");
    ax.addParamTmp("tipoCambioId", id);
    ax.addParamTmp("monedaId", monedaId);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("equivalenciaCompra", equivalenciaCompra);
    ax.addParamTmp("equivalenciaVenta", equivalenciaVenta);
    ax.consumir();
}

function actualizarTipoCambio(id, monedaId,fecha,equivalenciaCompra ,equivalenciaVenta) {
    ax.setAccion("actualizarTipoCambio");
    ax.addParamTmp("id", id);
    ax.addParamTmp("monedaId", monedaId);
    ax.addParamTmp("fecha", fecha);
    ax.addParamTmp("equivalenciaCompra", equivalenciaCompra);
    ax.addParamTmp("equivalenciaVenta", equivalenciaVenta);
    ax.consumir();
}

function validarFormulario(monedaId,fecha,equivalenciaCompra ,equivalenciaVenta) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();

    if (monedaId === "" || monedaId === null || espacio.test(monedaId) || monedaId.length === 0) {
        $("#msjMoneda").text("La moneda es obligatorio").show();
        bandera = false;
    }
    
    if (fecha === "" || fecha === null || espacio.test(fecha) || fecha.length === 0) {
        $("#msjFecha").text("Fecha es obligatorio").show();
        bandera = false;
    }
    if (equivalenciaCompra === "" || equivalenciaCompra === null || espacio.test(equivalenciaCompra) || equivalenciaCompra.length === 0) {
        $("#msjEquivalenciaCompra").text("Equivalencia compra es obligatorio").show();
        bandera = false;
    }    
    if (equivalenciaVenta === "" || equivalenciaVenta === null || espacio.test(equivalenciaVenta) || equivalenciaVenta.length === 0) {
        $("#msjEquivalenciaVenta").text("Equivalencia venta es obligatorio").show();
        bandera = false;
    }
    
    if (equivalenciaVenta <0) {
        $("#msjEquivalenciaVenta").text("Equivalencia venta tiene que se positivo").show();
        bandera = false;
    }
    if (equivalenciaCompra <0) {
        $("#msjEquivalenciaCompra").text("Equivalencia compra tiene que se positivo").show();
        bandera = false;
    }
    
    return bandera;
}

function limpiarMensajes() {
    $("#msjMoneda").hide();
    $("#msjFecha").hide();
    $("#msjEquivalenciaVenta").hide();
    $("#msjEquivalenciaCompra").hide();
}

function exitoCrear(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        $.Notification.autoHideNotify('success', 'top-right', 'Éxito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}


function onResponseObtenerTipoCambioXid(data) {   
    asignarValorSelect2("cboMoneda", data[0]["moneda_id"]);
    $('#fecha').val(data[0]["fecha_formateada"]);
    $('#txtEquivalenciaVenta').val(data[0]["equivalencia_venta"]);
    $('#txtEquivalenciaCompra').val(data[0]["equivalencia_compra"]);
    
    $('#fecha').attr('disabled', "true");
}

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/tipoCambio/tipoCambio.php";
    cargarDiv("#window", url);
}


var fc = "";
function obtenerEquivalenciaSunat() {  
    var fecha = $('#fecha').val();
    
    if(fc !== fecha){  
        loaderShow();
        ax.setAccion("obtenerEquivalenciaSunat");
        ax.addParam("fecha", fecha);
        ax.consumir();
        fc = fecha;
    }
}

function onResponseObtenerEquivalenciaSunat(data) {  
    $('#txtEquivalenciaVenta').val('');
    $('#txtEquivalenciaCompra').val('');
    
    if (!isEmpty(data)) {
        $('#txtEquivalenciaVenta').val(data['venta']);
        $('#txtEquivalenciaCompra').val(data['compra']);    
    }
}