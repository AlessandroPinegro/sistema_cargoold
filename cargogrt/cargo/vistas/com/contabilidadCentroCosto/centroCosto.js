$(document).ready(function () {
    ax.setSuccess("exitoCentroCosto");
    loaderShow();
    listarCentroCostoPadres();
    cargarSelect2();
//    cargarComponenteNestable();
//    obtenerConfiguracionesIniciales();
});


function listarCentroCostoPadres(){
    ax.setAccion("listarCentroCostoPadres");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function exitoCentroCosto(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarCentroCostoPadres':
                onResponseListarCentroCostoPadres(response.data);
                loaderClose();
                break;
            case 'obtenerHijos':
                onResponseObtenerHijos(response.data);
                loaderClose();
                break;
            case 'obtenerCentroCostoEdicion':
                onResponseObtenerCentroCostoEdicion(response.data);
                loaderClose();
                break;
            case 'guardarCentroCosto':
                onResponseGuardarCentroCosto(response.data);
                loaderClose();
                break;
            case 'eliminarCentroCosto':                
                onResponseEliminarCentroCosto(response.data);
                loaderClose();        
                break;
        }
    }
}

function cargarNestable(id){    
        $('#'+id).nestable({
            group: 1
        }).on('change', this.updateOutput);
}

function expandirTodo() {
    $('.dd').nestable('expandAll');
}

function contraerTodo() {
    $('.dd').nestable('collapseAll');
}

function onResponseListarCentroCostoPadres(data){
//    console.log(data);   
    var estilo="font-weight: normal;";
    
    $("#nestableLista").empty();
    var html='<ol class="dd-list" style="display: inline-block;">';
    
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {       
            if(item.hijos>0){
                estilo="font-weight: bold;";
            }
            
            html+='<li id="li'+item.id+'" class="dd-item dd3-item" data-id="'+item.id+'" onclick="obtenerHijos('+item.id+')" >';
//            html+='<div class="dd-handle dd3-handle"></div>';
            html+='<div class="dd3-content">';
            html+='<a href="#" style="'+estilo+'" onclick="obtenerFormularioEdicion('+item.id+')" id="descCentroCosto'+item.id+'">'+item.codigo +' | '+item.descripcion;
            html+='</a><a href="#" title="Nuevo centro de costo" onclick="nuevoCentroCosto('+item.id+',\''+item.codigo +'\',\''+item.descripcion+'\')" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
            html+='<a title="Eliminar centro de costo" onclick="confirmarEliminarCentroCosto('+item.id+',\''+item.codigo +'\',\''+item.descripcion+'\')">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
            html+='</div>';
            if(item.hijos>0){
                html+='<ol class="dd-list" id="ol'+item.id+'"><input type="hidden" name="hid'+item.id+'" id="hid'+item.id+'" value="0"/></ol>';
            }
            html+='</li>';
        });
    }
    
    html += '</ol>';
    $("#nestableLista").append(html);
    
    cargarNestable('nestableLista');
    contraerTodo();
    
}

function obtenerHijos(padreId){    
//    console.log($('#hid'+padreId).val());
//    if ($("#ol" + padreId).length == 1) {
    if($('#hid'+padreId).val()==0){
        ax.setAccion("obtenerHijos");
        ax.addParamTmp("padreId", padreId);
        ax.consumir();
    }
}

function onResponseObtenerHijos(data){
//    console.log(data[0]['plan_contable_padre_id']);   
    var estilo="font-weight: normal;";
    
    if (!isEmpty(data) ) {   
        var padreId = data[0]['centro_costo_padre_id'];
//        $("#ol"+padreId).empty();        
        var html='';
        if (!isEmpty(data)) {
            $.each(data, function (index, item) {     
                if(item.hijos>0){
                    estilo="font-weight: bold;";
                }            
                
                html+='<li id="li'+item.id+'" class="dd-item dd3-item dd-collapsed" data-id="'+item.id+'" onclick="obtenerHijos('+item.id+')" >';
                if (item.hijos > 0) {
                    html += '<button data-action="collapse" type="button" style="display: none;">Collapse</button>';
                    html += '<button data-action="expand" type="button" style="display: block;">Expand</button>';
                }
//                html+='<div class="dd-handle dd3-handle"></div>';
                html+='<div class="dd3-content">';
                html+='<a href="#" style="'+estilo+'" onclick="obtenerFormularioEdicion('+item.id+')"  id="descCentroCosto'+item.id+'" >'+item.codigo +' | '+item.descripcion;
                html+='</a><a href="#" title="Nuevo centro de costo" onclick="nuevoCentroCosto('+item.id+',\''+item.codigo +'\',\''+item.descripcion+'\')" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
                html+='<a title="Eliminar centro de costo" onclick="confirmarEliminarCentroCosto('+item.id+',\''+item.codigo +'\',\''+item.descripcion+'\')">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
                html+='</div>';
                if (item.hijos > 0) {
                    html += '<ol class="dd-list" id="ol' + item.id + '"><input type="hidden" name="hid'+item.id+'" id="hid'+item.id+'" value="0"/></ol>';
                }
                html += '</li>';
            });
        }
        $("#ol"+padreId).append(html);         
        $('#hid'+padreId).val('1'); 
    }
    
}

function obtenerFormularioEdicion(id){
    loaderShow();
    ax.setAccion("obtenerCentroCostoEdicion");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}

function cancelar(){
    $("#divFormulario").hide();    
    limpiarMensajes();    
    limpiarFormulario();
}

var centroCostoId=null;
function onResponseObtenerCentroCostoEdicion(data){ 
//    console.log(data);
    padreCentroCostoId=null;
    centroCostoId=data[0]['id'];
    
    $('#descripcionFormulario').html('ACTUALIZAR CENTRO COSTO');
    limpiarMensajes();
    limpiarFormulario();       
    
    var codigoCentroCosto=data[0]['codigo'];
    
    $('#txtCodigo').val(codigoCentroCosto); 
    $('#txtDescripcion').val(data[0]['descripcion']); 
    select2.asignarValor("cboEstado",data[0]['estado']);    
    
    $("#divFormulario").show();
}

function guardar(){
    //caja de texto
    var codigo=$('#txtCodigo').val();    codigo=codigo.trim();
    var descripcion=$('#txtDescripcion').val();    descripcion=descripcion.trim();
     
    //combos
    var estado=select2.obtenerValor('cboEstado');     
    
    if(validarFormulario(codigo,descripcion,estado)){
        guardarCentroCosto(codigo,descripcion,estado);        
    }      
}

function validarFormulario(codigo,descripcion,estado) {
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
    
    return bandera;
}

function limpiarMensajes() {
    $("#msjCodigo").hide();
    $("#msjEstado").hide();
    $("#msjDescripcion").hide();
}

function guardarCentroCosto(codigo, descripcion, estado) {
    
    loaderShow();
    ax.setAccion("guardarCentroCosto");
    ax.addParamTmp("codigo", codigo);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("estado", estado);
    ax.addParamTmp("centroCostoId",centroCostoId);
    ax.addParamTmp("padreCentroCostoId",padreCentroCostoId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();

}

function onResponseGuardarCentroCosto(data){
//    console.log(data);    
    
    exitoGuardar(data.resultado);
    
    if(data.resultado[0]["vout_exito"] == 1){        
        if(isEmpty(centroCostoId) && isEmpty(padreCentroCostoId)){//nuevo padre
            loaderShow();            
            cargarDiv("#window", "vistas/com/contabilidadCentroCosto/centroCosto.php", '');
            
        } else if(isEmpty(data.dataPadre)){//edicion
            $('#descCentroCosto'+data.resultado[0]["id"]).html(data.codigo + ' | ' +data.descripcion);            
        }else{//nuevo hijo
            dibujarFilaCentroCosto(data.dataPadre);
        }        
    }        
}

function dibujarFilaCentroCosto(data) {
//    console.log(data);
    var item=data[0];
    var html = '';
    var estilo="font-weight: normal;";
        
    $("#li"+item.id).empty();       

//    html += '<li id="li' + item.cuenta_id + '" class="dd-item dd3-item dd-collapsed" data-id="' + item.cuenta_id + '" onclick="obtenerHijos(' + item.cuenta_id + ')" >';
    if (item.hijos > 0) {
        html += '<button data-action="collapse" type="button" style="display: block;">Collapse</button>';
        html += '<button data-action="expand" type="button" style="display: none;">Expand</button>';
    }
         
    if (item.hijos > 0) {
        estilo = "font-weight: bold;";
    }
            
//    html += '<div class="dd-handle dd3-handle"></div>';
    html += '<div class="dd3-content">';
    html += '<a href="#"  style="'+estilo+'" onclick="obtenerFormularioEdicion(' + item.id + ')"  id="descCentroCosto' + item.id + '" >' + item.codigo + ' | ' + item.descripcion;
    html += '</a><a href="#" title="Nuevo centro de costo" onclick="nuevoCentroCosto(' + item.id + ',\'' + item.codigo +'\',\''+ item.descripcion + '\')" >&nbsp;&nbsp;<i class="fa fa-plus-square" style="color:#1ca8dd"></i></a>';
    html += '<a title="Eliminar centro de costo" onclick="confirmarEliminarCentroCosto(' + item.id + ',\'' + item.codigo + '\',\'' + item.descripcion + '\')">&nbsp;&nbsp;<i class="fa fa-trash-o" style="color:#cb2a2a;"></i></a>';
    html += '</div>';
    if (item.hijos > 0) {
        html += '<ol class="dd-list" id="ol' + item.id + '"><input type="hidden" name="hid' + item.id + '" id="hid' + item.id + '" value="0"/></ol>';
    }
//    html += '</li>';
        
    $("#li" + item.id).append(html);
    $("#li" + item.id).removeClass('dd-collapsed');
//    console.log(1);
    obtenerHijos(item.id);
}


function exitoGuardar(data){
    if (data[0]["vout_exito"] == 0){
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else{
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
//        cargarPantallaListar();
    }
}

function limpiarFormulario(){
    $('#txtCodigoPadre').val(''); 
    $('#txtCodigo').val(''); 
    $('#txtDescripcion').val(''); 
    select2.asignarValor("cboEstado",1);
}

var padreCentroCostoId=null;
function nuevoCentroCosto(padreId,codigo,descripcion){
    padreCentroCostoId=padreId;
    centroCostoId=null;
    
    $('#descripcionFormulario').html('NUEVO SUB CENTRO DE COSTO DE: '+codigo+' | '+descripcion);
    
    limpiarMensajes();
    limpiarFormulario();
    
    $("#divFormulario").show();
}

function confirmarEliminarCentroCosto(id,codigo,descripcion) {
    
    swal({
        title: "Estás seguro?",
        text: "Eliminarás el centro de costo: " + codigo + ' | ' + descripcion + ". Si tuviera hijos serán eliminados.",
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
            eliminarCentroCosto(id);
        } else {
                swal("Cancelado", "La eliminación fue cancelada", "error");
        }
    });
}

function eliminarCentroCosto(id){
//    alert(id);
    loaderShow();
    ax.setAccion("eliminarCentroCosto");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function onResponseEliminarCentroCosto(data) {
    if (data['0'].vout_exito == 1) {
        swal("Eliminado!", "Se eliminó el centro de costo: " + data['0'].centro_costo_descripcion + ".", "success");
        
        $("#li"+data['0'].centro_costo_id).remove();          
        
    } else {
//        swal("Cancelado", "No se puedo eliminar " + data['0'].nombre + " " + data['0'].vout_mensaje, "error");
    }
}

function nuevoCentroCostoPadre(){
    padreCentroCostoId=null;
    centroCostoId=null;
    
    $('#descripcionFormulario').html('NUEVO CENTRO DE COSTO');
    
    limpiarMensajes();
    limpiarFormulario();
    
    $("#divFormulario").show();
}