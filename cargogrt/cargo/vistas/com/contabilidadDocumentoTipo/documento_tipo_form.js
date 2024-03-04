
var documentoTipoId;
$(document).ready(function () {
    loaderShow();
    documentoTipoId = document.getElementById("id").value;
    ax.setSuccess("exitoDocumentoTipo");
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
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();   
}

function exitoDocumentoTipo(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;   
            case 'guardarDocumentoTipo':
                exitoGuardar(response.data.resultado);
                loaderClose();
                break;
            case 'obtenerDocumentoTipoXid':
                onResponseObtenerDocumentoTipoXid(response.data);
                loaderClose();
                break;   
        }
    }
}

function exitoGuardar(data){
    if (data[0]["vout_exito"] == 0){
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else{
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/contabilidadDocumentoTipo/documento_tipo_listar.php";
    cargarDiv("#window", url);
}

function onResponseObtenerConfiguracionesIniciales(data){
//    console.log(data);        
    
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo","descripcion"]);
    select2.cargar("cboTipo", data.dataTipo, "codigo", ["codigo","descripcion"]);
        
    //traemos la data del documentoTipo
    if (!isEmpty(documentoTipoId)) {
        ax.setAccion("obtenerDocumentoTipoXid");
        ax.addParam("id", documentoTipoId);
        ax.consumir();
    } 
}

function onResponseObtenerDocumentoTipoXid(data){
//    console.log(data);
    var dataDocumentoTipo=data;
    
    //caja de texto
    $('#txtDescripcion').val(dataDocumentoTipo[0]['descripcion']); 
    $('#txtComentario').val(dataDocumentoTipo[0]['comentario_defecto']); 
    
    //combos
    select2.asignarValor('cboCodigoSunat',dataDocumentoTipo[0]['sunat_tabla_detalle_id']);
    select2.asignarValor('cboTipo',dataDocumentoTipo[0]['tipo']);
    select2.asignarValor('cboEstado',dataDocumentoTipo[0]['estado']);      
    
}

function enviar() {
    //caja de texto
    var descripcion=$('#txtDescripcion').val();    descripcion=descripcion.trim();
    var comentario=$('#txtComentario').val();    comentario=comentario.trim();
    
    //combos
    var codigoSunatId=select2.obtenerValor('cboCodigoSunat');
    var estadoId=select2.obtenerValor('cboEstado');
    var tipo=select2.obtenerValor('cboTipo');
    
    
    if(validarFormulario(descripcion,estadoId,tipo)){
        guardarDocumentoTipo(descripcion,comentario,codigoSunatId,estadoId,tipo);        
    }  
}

function guardarDocumentoTipo(descripcion,comentario,codigoSunatId,estadoId,tipo){
        
    loaderShow();
    ax.setAccion("guardarDocumentoTipo");
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("estadoId", estadoId);
    ax.addParamTmp("tipo", tipo);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
    
}

function validarFormulario(descripcion,estadoId,tipo) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();
    
    if (descripcion === "" || descripcion === null || espacio.test(descripcion) || descripcion.length === 0) {
        $("#msjDescripcion").text("Ingrese descripción").show();
        bandera = false;
    }
    
    if (estadoId === "" || estadoId === null || espacio.test(estadoId) || estadoId.length === 0) {
        $("#msjEstado").text("Seleccione un estado").show();
        bandera = false;
    }
    
    if (tipo === "" || tipo === null || espacio.test(tipo) || tipo.length === 0) {
        $("#msjTipo").text("Seleccione un tipo").show();
        bandera = false;
    }
    
    return bandera;
}

function limpiarMensajes() {
    $("#msjDescripcion").hide();
    $("#msjCodigoSunat").hide();
    $("#msjEstado").hide();
}