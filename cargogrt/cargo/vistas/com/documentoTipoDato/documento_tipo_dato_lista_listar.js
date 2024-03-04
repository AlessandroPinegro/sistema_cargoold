var c = $('#env i').attr('class');
var bandera_eliminar = false;

$(document).ready(function () {
    modificarAnchoTabla('datatable');    
    obtenerConfiguracionesIniciales();    
    altura();
});

function obtenerConfiguracionesIniciales(){    
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParamTmp("documentoTipoDatoId", commonVars.documentoTipoDatoId);
    ax.consumir();
}

function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    if(data[0].vout_exito==0){
        mostrarAdvertencia(data[0].vout_mensaje);
    }else{
        mostrarOk(data[0].vout_mensaje);
        listarDatoLista();
    }
}
function listarDatoLista()
{
    ax.setAccion("getDataGridDocumentoTipoDatoLista");
    ax.addParamTmp("documentoTipoDatoId", commonVars.documentoTipoDatoId);
    ax.consumir();
}
function successDocumentoTipoDato(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                listarDatoLista();
                break;
            case 'getDataGridDocumentoTipoDatoLista':
                onResponseAjaxpGetDataGridDocumentoTipoDatoLista(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
//                listarDatoLista();
                break;
            case 'eliminarDocumentoTipoDatoLista':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", 'El item '+ response.data['0'].descripcion + ": " + response.data['0'].vout_mensaje, "success");
                    listarDatoLista();
                } else {
                    swal("Cancelado", 'El item '+ response.data['0'].descripcion + ": " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;

            case 'save':
                $("#modalDocumentoTipoDatoLista").modal('hide');
                loaderClose();
                listarDatoLista();
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            
        }
    }
}


function retornar(){
    var url=URL_BASE+'vistas/com/documentoTipoDato/documento_tipo_dato_listar.php';
    cargarDiv('#window',url);
}

var dataConfiguracionesIniciales;
function onResponseObtenerConfiguracionesIniciales(data){
    dataConfiguracionesIniciales=data;
    var dtdDescripcion=data.documentoTipoDato[0].documento_tipo_descripcion+': '+data.documentoTipoDato[0].doc_tipo_dato_desc;
    
    $('#tituloPrincipal').html('<a onclick="retornar()" title="Click para regresar">Listas dinámicas </a> &gt; '+dtdDescripcion);    
    
    if(!isEmpty(data.dataValor)){
        select2.cargar('cboValor',data.dataValor,'codigo',['codigo','descripcion']);
        select2.asignarValor('cboValor',data.dataValor[0].codigo);
        
        $('#divValorCbo').show();
    }else{
        $('#divValorTxt').show();
    }
}

function onResponseAjaxpGetDataGridDocumentoTipoDatoLista(data) {
    if (!isEmptyData(data))
    {
        $.each(data, function (index, item)
        {
            data[index]["opciones"] = '\<a onclick="prepareEdit(' + item['id'] + ',\'' + item['descripcion'] + '\',\'' + item['valor'] + '\',' + item['estado'] + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>\n\
                                   <a onclick="confirmarDocumentoTipoDatoLista(' + item['id'] + ',\'' + item['descripcion'] + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>';

            if (data[index]["estado"] == 1)
            {
                data[index]["estado"] = '<a onclick ="cambiarEstado(' + item['id'] + ')" ><b><i class="ion-checkmark-circled" style="color:#5cb85c"></i><b></a>';
            }
            else
            {
                data[index]["estado"] = '<a onclick ="cambiarEstado(' + item['id'] + ')"><b><i class="ion-flash-off" style="color:#cb2a2a"></i><b></a>';
            }
            
            if(isEmpty(dataConfiguracionesIniciales.dataValor)){
                data[index]['valorDesc']=data[index]['valor'];
            }else{
                data[index]['valorDesc']=obtenerValorDescripcion(data[index]['valor']);
            }
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "data": data,
            "columns": [
                {"data": "descripcion"},
                {"data": "valorDesc"},
                {"data": "estado", "sClass": "alignCenter"},
                {"data": "opciones", "sClass": "alignCenter"}
            ],
            "destroy": true
        });
    }
    else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    loaderClose();
}
function confirmarDocumentoTipoDatoLista(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Estás seguro?",
        text: "Eliminaras el item: " + nom + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            eliminarDocumentoTipoDatoLista(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function eliminarDocumentoTipoDatoLista(id, nom)
{
    ax.setAccion("eliminarDocumentoTipoDatoLista");
    ax.addParamTmp("id_documento_tipo_dato_lista", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}


function obtenerTitulo()
{
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    $("#window").empty();

    if (!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

//---------------MODAL----------------------------
function nuevo()
{
    commonVars.DocumentoTipoDatoListaId = 0;        
    $('#txt_descripcion').val('');

    if (isEmpty(dataConfiguracionesIniciales.dataValor)) {
        $('#txt_valor').val('');
    } else {
        select2.asignarValor('cboValor', dataConfiguracionesIniciales.dataValor[0].codigo);
    }

    asignarValorSelect2("cboEstado", "1");
    tituloModal("Nuevo");
    cargarModal();
}

function cargarModal()
{
    $('#modalDocumentoTipoDatoLista').modal('show');
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function tituloModal(titulo)
{
    $('.modal-title').empty();
    $('.modal-title').append(titulo);
}

//---------------NUEVO-------------------------

function guardar()
{
    var descripcion, valor, estado;
    descripcion = $('#txt_descripcion').val();
    
    if(isEmpty(dataConfiguracionesIniciales.dataValor)){
        valor = $('#txt_valor').val();
    }else{
        valor=select2.obtenerValor('cboValor');
    }
    estado = $('#cboEstado').val();
//    breakFunction();
    if (isEmpty(descripcion) || isEmpty(estado)) {
        mostrarAdvertencia("Debe ingresar descripcion y/o seleccionar estado");
        return;
    }

    loaderShow(".modal-content");
    ax.setAccion("save");
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("valor", valor);
    ax.addParamTmp("estado", estado);
    ax.addParamTmp("documentoTipoDatoId", commonVars.documentoTipoDatoId);
    ax.addParamTmp("documentoTipoDatoListaId", commonVars.DocumentoTipoDatoListaId);
    ax.consumir();

}

//function breakFunction() {
//    return null;
//}

//----------EDITAR------------------
function prepareEdit(id, descripcion, valor, estado)
{
    if(isEmpty(dataConfiguracionesIniciales.dataValor)){
        $('#txt_valor').val(valor);
    }else{
        select2.asignarValor('cboValor',valor);
    }
    
    $('#txt_descripcion').val(descripcion);
    asignarValorSelect2("cboEstado", estado);
    commonVars.DocumentoTipoDatoListaId = id;    
    
    tituloModal("Editar");
    cargarModal();
}

function obtenerValorDescripcion(valorId){
    
    var valorDesc = '';
    if (!isEmpty(dataConfiguracionesIniciales.dataValor)) {
        $.each(dataConfiguracionesIniciales.dataValor, function (i, item) {
            if (item.codigo == valorId) {
                valorDesc=item.codigo+' | '+item.descripcion;
                return false;
            }
        });
    }
    
    return valorDesc;
}