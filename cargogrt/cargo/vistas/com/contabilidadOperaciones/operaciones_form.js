
var operacionId;
$(document).ready(function () {
    loaderShow();
    operacionId = document.getElementById("id").value;
    ax.setSuccess("exitoOperaciones");
    configuracionesIniciales();
    cargarLista();
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

function cargarLista()
{
    ax.setAccion("obtenerOperacionNumeracionXid");
    ax.addParamTmp("operacionId", operacionId);
    ax.consumir();
}

function exitoOperaciones(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;   
            case 'guardarOperacion':
                exitoGuardar(response.data.resultado);
                loaderClose();
                break;
            case 'obtenerOperacionXid':
                onResponseObtenerOperacionesXid(response.data);
                loaderClose();
                break;
            case 'obtenerOperacionNumeracionXid':
                onResponseObtenerOperacionNumeracionXid(response.data);
                loaderClose();
                break;
        }
    }
}

function listarOperacionNumeracion(data)
{
        $("#tbodyOperacionNumeracion").empty();
    var html='';
    html+= '<tr>'+
                        '<th style="text-align: center;">Período </th>'+
                        '<th style="text-align: center;">Numerador </th>'+
                   '</tr>';
    if(!isEmpty(data))
    {
        $.each(data, function(index, item){
            
            html+= '<tr>'+
                   '<td>'+item.periodo+'</td>'+
                   '<td>'+item.numerador+'</td></tr>'                        
        });
        $("#tbodyOperacionNumeracion").append(html);
    }else{
        $("#rowNumeracion").hide();
    }
}

function onResponseObtenerOperacionNumeracionXid(data)
{
    listarOperacionNumeracion(data);
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
    var url = URL_BASE + "vistas/com/contabilidadOperaciones/operaciones_listar.php";
    cargarDiv("#window", url);
}

function onResponseObtenerConfiguracionesIniciales(data){
//    console.log(data);        
    
    select2.cargar("cboTipoCambio", data.dataTipoCambio, "codigo", ["codigo","descripcion"]);
    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo","descripcion"]);
    select2.cargar("cboSubdiario", data.dataSubdiario, "id", ["subdiario_codigo","subdiario_descripcion"]);
    select2.cargarAsignaUnico("cboSucursal", data.dataSucursal, "id", "nombre");
        
    //traemos la data del operacion
    if (!isEmpty(operacionId)) {
        ax.setAccion("obtenerOperacionXid");
        ax.addParam("id", operacionId);
        ax.consumir();
    } 
}

function onResponseObtenerOperacionesXid(data){
//    console.log(data);
    var dataOperacion=data.dataOperacion;
    
    //caja de texto
    $('#txtCodigo').val(dataOperacion[0]['codigo']);
    $('#txtDescripcion').val(dataOperacion[0]['descripcion']); 
    
    //combos
    select2.asignarValor('cboTipoCambio',dataOperacion[0]['tipo_cambio']);
    select2.asignarValor('cboCodigoSunat',dataOperacion[0]['sunat_tabla_detalle_id']);
    select2.asignarValor('cboEstado',dataOperacion[0]['estado']);      
    select2.asignarValor('cboSubdiario',dataOperacion[0]['subdiario_id']);
    select2.asignarValor('cboSucursal',dataOperacion[0]['sucursal_id']);
    
    if (dataOperacion[0]['egreso_cheque'] == 1){
        document.getElementById("chkEgresoBanco").checked = true;     
    }else{
        document.getElementById("chkEgresoBanco").checked = false;
    }   
}

function enviar() {
    //caja de texto
    var codigo=$('#txtCodigo').val();    codigo=codigo.trim();
    var descripcion=$('#txtDescripcion').val();    descripcion=descripcion.trim();
    
    //combos
    var tipoCambioId=select2.obtenerValor('cboTipoCambio');
    var codigoSunatId=select2.obtenerValor('cboCodigoSunat');
    var estadoId=select2.obtenerValor('cboEstado');
    var subdiarioId=select2.obtenerValor('cboSubdiario');
    var sucursalId=select2.obtenerValor('cboSucursal');
    
    //checks 
    var chkEgresoBanco = 0;
    if (document.getElementById("chkEgresoBanco").checked) {
        chkEgresoBanco = 1;
    }  
    
    if(validarFormulario(codigo,descripcion,tipoCambioId,estadoId,sucursalId)){
        guardarOperacion(codigo,descripcion,tipoCambioId,codigoSunatId,estadoId,subdiarioId,sucursalId,
            chkEgresoBanco);        
    }  
}

function guardarOperacion(codigo,descripcion,tipoCambioId,codigoSunatId,estadoId,subdiarioId,sucursalId,
    chkEgresoBanco){
        
    loaderShow();
    ax.setAccion("guardarOperacion");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("tipoCambioId", tipoCambioId);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("estadoId", estadoId);
    ax.addParamTmp("subdiarioId", subdiarioId);
    ax.addParamTmp("sucursalId", sucursalId);
    ax.addParamTmp("chkEgresoBanco",chkEgresoBanco);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("operacionId", operacionId);
    ax.consumir();
    
}

function validarFormulario(codigo,descripcion,tipoCambioId,estadoId,sucursalId) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();
    
    if (codigo === "" || codigo === null || espacio.test(codigo) || codigo.length === 0)
    {
        $("#msjCodigo").text("Ingrese un código").show();
        bandera = false;
    }

    if (descripcion === "" || descripcion === null || espacio.test(descripcion) || descripcion.length === 0) {
        $("#msjDescripcion").text("Ingrese descripción").show();
        bandera = false;
    }
    
    if (estadoId === "" || estadoId === null || espacio.test(estadoId) || estadoId.length === 0) {
        $("#msjEstado").text("Seleccione un estado").show();
        bandera = false;
    }
    
    if (tipoCambioId === "" || tipoCambioId === null || espacio.test(tipoCambioId) || tipoCambioId.length === 0) {
        $("#msjTipoCambio").text("Seleccione tipo de cambio").show();
        bandera = false;
    }
    
    if (sucursalId === "" || sucursalId === null || espacio.test(sucursalId) || sucursalId.length === 0) {
        $("#msjSucursal").text("Seleccione sucursal").show();
        bandera = false;
    }
    
    return bandera;
}

function limpiarMensajes() {
    $("#msjCodigo").hide();
    $("#msjDescripcion").hide();
    $("#msjTipoCambio").hide();
    $("#msjCodigoSunat").hide();
    $("#msjEstado").hide();
    $("#msjOperacion").hide();
    $("#msjSucursal").hide();
}