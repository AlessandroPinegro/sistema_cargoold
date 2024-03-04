
$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("exitoListarACCaja"); 
    obtenerUsuarioDatos();

    cargarSelect2();
    iniciarDataPicker();
 //   obtenerWidgets()
//    listarACCaja();
});

function exitoListarACCaja(response) {
     console.log(response.data);
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
             case 'exportarPosicionCaja':
                loaderClose();
                location.href = URL_BASE + "util/formatos/posicion_caja.xlsx";
                break;
            case 'exportarLiquidacionAgenciaPdf':
                loaderClose();
                window.open(URL_BASE + 'pdf2.php?url_pdf=' + URL_BASE + "vistas/com/movimiento/documentos/" + response.data.nombre_pdf+'.pdf' + '&nombre_pdf=' + response.data.nombre_pdf);
                // setTimeout(function () {
                //     ax.setAccion("eliminarPDF");
                //     ax.addParamTmp("url", response.data.url);
                //     ax.consumir();
            
                // }, 500);
                break;
            case 'exportarDetalleLiquidacionAgenciaPdf':
                loaderClose();
                window.open(URL_BASE + 'pdf2.php?url_pdf=' + URL_BASE + "vistas/com/movimiento/documentos/" + response.data.nombre_pdf+'.pdf' + '&nombre_pdf=' + response.data.nombre_pdf);
                break;
            case 'obtenerAperturaCierreUltimo':
                onResponseObtenerAperturaCierreUltimo(response.data);
                loaderClose();
                break;
            case 'obtenerUsuarioDatosCaja':
                onResponseObtenerUsuarioDatos(response.data);
                loaderClose();
                break;
              
               case 'obtenerWidgets':
                onResponseObtenerWidgets(response.data);
                loaderClose();
                break;
                
            case 'obtenerConfiguracionesInicialesApertura':
                onResponseObtenerDetalleApertura(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesCierre':
                onResponseObtenerDetalleCierre(response.data);
                loaderClose();
                break;
        }
    }
}

function iniciarDataPicker() {
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}

function obtenerUsuarioDatos() {
    loaderShow();
    ax.setAccion("obtenerUsuarioDatosCaja");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}




function dibujarIconosLeyenda(mostrar) {
    if (mostrar) {
        $("#iconos_leyenda").html("<i class='fa fa-edit' style='color:#E8BA2F;'></i> Editar apertura &nbsp;&nbsp;&nbsp;"
                + "<i class='fa fa-edit' style='color:green;'></i> Editar cierre &nbsp;&nbsp;&nbsp;"
                + "<i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;");
    } else {
        $("#iconos_leyenda").html("<i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;");
    }
}

var dataCaja = [];
var mostrarEdicion = false;
function onResponseObtenerUsuarioDatos(data) {
    mostrarEdicion = false;
    $.each(data.dataUsuario[0].perfil.split(";"), function (index, item) {
        if (item == 1 || item == 118) {
            mostrarEdicion = true;
        }
    });

if(data.perfil[0].liqagencia_vertodas==1){
    if (!isEmpty(data.dataAgencia)) {
        select2.cargar("cboAgencia", data.dataAgencia, "agencia_id", "codigo");
         select2.cargar("cboPeriodo", data.periodo, "id", ["mes_nombre","anio"]);
         select2.cargar("cboUsuario", data.dataUsuario, "usuario_id", "usuario");
        if (data.dataAgencia.length == 1) {
            $("#cboAgencia").prop('disabled', true);
        } else {
            $("#cboAgencia").prop('disabled', false);
        }
        select2.asignarValor("cboAgencia", data.dataAgencia[0].agencia_id);

    }
    }    
    else if (data.perfil[0].liqagencia_vertodas==2){
            if (!isEmpty(data.dataAgencia)) {
        select2.cargar("cboAgencia", data.dataAgencia2, "id", "codigo");
         select2.cargar("cboPeriodo", data.periodo, "id", ["mes_nombre","anio"]);
         select2.cargar("cboUsuario", data.dataUsuario, "usuario_id", "usuario");
        if (data.dataAgencia.length == 1) {
            $("#cboAgencia").prop('disabled', true);
        } else {
            $("#cboAgencia").prop('disabled', false);
        }
        select2.asignarValor("cboAgencia", data.dataAgencia2[0].id);

    }
    }     
    dibujarIconosLeyenda(mostrarEdicion);
//    listarACCaja(mostrarEdicion);
}
var criteriosBusquedaACCaja = {};
var lstInventario = [];
  function exportarLiquidacionAgenciaPdf(){
    actualizandoBusqueda = true;
    loaderShow();
    var agencia=document.getElementById('cboAgencia').value;
    var fecha=$('#fechaInicio').val();
    ax.setAccion("exportarLiquidacionAgenciaPdf");
    ax.addParamTmp("agencia", agencia);
    ax.addParamTmp("fecha", fecha);
    ax.consumir();
  }

  function exportarDetalleLiquidacionAgenciaPdf(){
    actualizandoBusqueda = true;
     var agencia = document.getElementById('cboAgencia').value;
     var fecha = $('#fechaInicio').val();
     ax.setAccion("exportarDetalleLiquidacionAgenciaPdf");
     ax.addParamTmp("agencia", agencia);
     ax.addParamTmp("fecha", fecha);
     ax.consumir();
    // ax.setAccion()

  }
  
  function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}
