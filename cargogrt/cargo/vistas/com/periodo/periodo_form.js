
var periodoId;
$(document).ready(function () {
    loaderShow();
    periodoId = document.getElementById("id").value;
    ax.setSuccess("exitoPeriodo");
    configuracionesIniciales();
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
    ax.consumir();
}

function exitoPeriodo(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'guardarPeriodo':
                exitoGuardar(response.data.resultado);
                loaderClose();
                break;
            case 'obtenerPeriodoXid':
                onResponseObtenerPeriodoXid(response.data);
                loaderClose();
                break;
        }
    }
}

function exitoGuardar(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/periodo/periodo_listar.php";
    cargarDiv("#window", url);
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data);        

    data.dataMes.sort((a, b) => {
        if (a.id == b.id) {
            return 0;
        }
        if (a.id < b.id) {
            return -1;
        }
        return 1;
    });

    select2.cargar("cboMes", data.dataMes, "codigo", ["codigo", "descripcion"]);

    //traemos la data del documentoTipo
    if (!isEmpty(periodoId)) {
        ax.setAccion("obtenerPeriodoXid");
        ax.addParam("id", periodoId);
        ax.consumir();
    }
}

function onResponseObtenerPeriodoXid(data) {
//    console.log(data);
    var dataPeriodo = data.dataPeriodo;

    //caja de texto
    $('#txtAnio').val(dataPeriodo[0]['anio']);

    //combos
    select2.asignarValor('cboMes', dataPeriodo[0]['mes']);
    select2.asignarValor('cboEstado', dataPeriodo[0]['estado']);

}

function enviar() {
    //caja de texto
    var anio = $('#txtAnio').val();

    //combos
    var estadoId = 1;
    var mes = select2.obtenerValor('cboMes');


    if (validarFormulario(anio, estadoId, mes)) {
        guardarPeriodo(anio, estadoId, mes);
    }
}

function guardarPeriodo(anio, estadoId, mes) {

    loaderShow();
    ax.setAccion("guardarPeriodo");
    ax.addParamTmp("anio", anio);
    ax.addParamTmp("mes", mes);
    ax.addParamTmp("estadoId", estadoId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("periodoId", periodoId);
    ax.consumir();

}

function validarFormulario(anio, estadoId, mes) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();

    if (anio === "" || anio === null || espacio.test(anio) || anio.length === 0) {
        $("#msjAnio").text("Ingrese año").show();
        bandera = false;
    }

    if (estadoId === "" || estadoId === null || espacio.test(estadoId) || estadoId.length === 0) {
        $("#msjEstado").text("Seleccione un estado").show();
        bandera = false;
    }

    if (mes === "" || mes === null || espacio.test(mes) || mes.length === 0) {
        $("#msjMes").text("Seleccione un mes").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajes() {
    $("#msjAnio").hide();
    $("#msjMes").hide();
    $("#msjEstado").hide();
}