var c = $('#env i').attr('class');
var dataObtenerLineaCredito = '';
var accionTipoGlobal = 1;
var acciones = {
    obtenerComboPersonaClase: false,
    obtenerComboMoneda: false,
    obtnerLineaCredito: false,
};
$("#cboPersonaClase").change(function () {
    $('#msjPersonaClase').hide();
});
$("#cboMoneda").change(function () {
    $('#msjMoneda').hide();
});
$("#txtImporte").keypress(function () {
    $('#msjImporte').hide();
});
function deshabilitarBoton()
{
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}
function cargarComponentes()
{
    ax.setSuccess("successLineaCredito");
    
    
//    $("#cboEstado").hide();

    $('#spnImporte').spinner();
    $('#spnPeriodo').spinner();
    $('#spnPeriodoGracia').spinner();
    cargarSelect2();

}
function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}
//function mostrarEstado()
//{
//    $("#cboEstado").hide();
//}
function validarFormulario() {
    var bandera = true;
    var espacio = /^\s+$/;
    var numero = !/^([0-9])*$/;
    var personaClaseId = document.getElementById('cboPersonaClase').value;
    var moneda = document.getElementById('cboMoneda').value;
    var importe = document.getElementById('txtImporte').value;
    if (personaClaseId == "" || personaClaseId == null || espacio.test(personaClaseId) || personaClaseId.length == 0)
    {
        $("msjPersonaClase").removeProp(".hidden");
        $("#msjPersonaClase").text("Seleccionar una clase de persona").show();
        bandera = false;
    }
    if (importe == "" || importe == null || espacio.test(importe) || importe.length == 0)
    {
        $("msjImporte").removeProp(".hidden");
        $("#msjImporte").text("Ingresar un Importe").show();
        bandera = false;
    }

    if (isNaN(importe))
    {
        $("msjImporte").removeProp(".hidden");
        $("#msjImporte").text("Ingresar un numero valido").show();
        bandera = false;
        bandera = false;
    }
    if (moneda == "" || moneda == null || espacio.test(moneda) || moneda.length == 0)
    {
        $("msjMoneda").removeProp(".hidden");
        $("#msjMoneda").text("Seleccionar un tipo de moneda").show();
        bandera = false;
    }
    return bandera;
}

function obtenerComboPersonaClase()
{
    ax.setAccion("obtenerComboPersonaClase");
    ax.consumir();
}
function obtenerComboMoneda()
{
    ax.setAccion("obtenerComboMoneda");
    ax.consumir();
}
function successLineaCredito(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerComboPersonaClase':
                acciones.obtenerComboPersonaClase = true;
                cargarDataComboPersonaClase(response.data);
                verificarCargarComplemento();
                break;
            case 'obtenerComboMoneda':
                acciones.obtenerComboMoneda = true;
                cargarDataComboMoneda(response.data);
                verificarCargarComplemento();
                break;
            case 'insertarLineaCredito':
                exitoInsertar(response.data)
                break;
            case 'actualizarLineaCredito':
                exitoActualizar(response.data)
                break;
            case 'obtenerLineaCredito':
                acciones.obtnerLineaCredito = true;
                dataObtenerLineaCredito = response.data;
                verificarCargarComplemento();
                break;
        }
    }
}

function cargarDataComboPersonaClase(data)
{
    $('#cboPersonaClase').append('<option value="">' + "" + '</option>');
    $.each(data, function (index, item) {
        $('#cboPersonaClase').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
    });
}

function cargarDataComboMoneda(data)
{
    $('#cboMoneda').append('<option value="">' + "" + '</option>');
    $.each(data, function (index, item) {
        $('#cboMoneda').append('<option value="' + item.id + '">' + item.descripcion + " (" + item.simbolo + ")" + '</option>');
    });
}
function enviarLineaCredito()
{
    var id = document.getElementById('id').value;
    var tipoAccion = document.getElementById('tipoAccion').value;
    var personaClaseId = document.getElementById('cboPersonaClase').value;
    var moneda = document.getElementById('cboMoneda').value;
    var importe = document.getElementById('txtImporte').value;
    var periodo = document.getElementById('txtPeriodo').value;
    var periodoGracia = document.getElementById('txtPeriodoGracia').value;
    var estado = document.getElementById('cboEstado').value;
    if (tipoAccion == 1)
    {
        actualizarLineaCredito(id, personaClaseId, moneda, importe, periodo, periodoGracia,estado);
    } else {
        insertarLineaCredito(personaClaseId, moneda, importe, periodo, periodoGracia,estado);
    }
}

function insertarLineaCredito(personaClaseId, moneda, importe, periodo, periodoGracia)
{
    if (validarFormulario()) {
        deshabilitarBoton();
        ax.setAccion("insertarLineaCredito");
        ax.addParamTmp("personaClaseId", personaClaseId);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("importe", importe);
        ax.addParamTmp("periodo", periodo);
        ax.addParamTmp("periodoGracia", periodoGracia);
        ax.consumir();
    }
}
function actualizarLineaCredito(id,personaClaseId, moneda, importe, periodo, periodoGracia,estado)
{
    if (validarFormulario()) {
        deshabilitarBoton();
        ax.setAccion("actualizarLineaCredito");
        ax.addParamTmp("lineaCreditoId", id);
        ax.addParamTmp("personaClaseId", personaClaseId);
        ax.addParamTmp("moneda", moneda);
        ax.addParamTmp("importe", importe);
        ax.addParamTmp("periodo", periodo);
        ax.addParamTmp("periodoGracia", periodoGracia);
        ax.addParamTmp("estado",estado );
        ax.consumir();
    }
}

function exitoInsertar(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function exitoActualizar(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function obtenerLineaCredito()
{
    var id = document.getElementById('id').value;
    ax.setAccion("obtenerLineaCredito");
    ax.addParamTmp("lineaCreditoId", id);
    ax.consumir();
}
function verificarCargarComplemento()
{
    if (acciones.obtenerComboPersonaClase && acciones.obtenerComboMoneda && acciones.obtnerLineaCredito)
    {
        if (dataObtenerLineaCredito == '' || dataObtenerLineaCredito == null)
        {
            loaderClose();
        } else
        {
            $("#txtImporte").val(parseFloat(dataObtenerLineaCredito['0']['valor']).toFixed(2));
            asignarValorSelect2("cboPersonaClase", dataObtenerLineaCredito['0']['persona_clase_id']);
            asignarValorSelect2("cboMoneda", dataObtenerLineaCredito['0']['moneda_id']);
            asignarValorSelect2("cboEstado", dataObtenerLineaCredito['0']['estado']);
             var spinnerPeriodo = $( "#spnPeriodo" ).spinner();
             spinnerPeriodo.spinner( "value", dataObtenerLineaCredito['0']['dias'] );
             
             var spinnerPeriodo = $( "#spnPeriodoGracia" ).spinner();
             spinnerPeriodo.spinner( "value", dataObtenerLineaCredito['0']['periodo_gracia'] );
//            $("#txtPeriodo").val(dataObtenerLineaCredito['0']['dias']);
//            $("#txtPeriodoGracia").val(dataObtenerLineaCredito['0']['periodo_gracia']);
            loaderClose();
        }
    }
}
function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function cargarPantallaListar()
{
    cargarDiv("#window", "vistas/com/lineaCredito/lineaCreditoListar.php",obtenerTitulo());
}

function obtenerTitulo()
{
    TITULO = $("#titulo").text();
    var titulo =  TITULO;
    $("#window").empty();
    
    if(!isEmpty(titulo))
    {
        var partes = titulo.split(" ");
        var cadena ="";
        $.each(partes, function (index, item) {
             if(index!=0)
             {
                 cadena += item + " ";
             }
        });
        
        if(!isEmpty(cadena))
        {
            titulo = capitaliseFirstLetter(cadena);
        }
        
    }
    return titulo;
}

function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
