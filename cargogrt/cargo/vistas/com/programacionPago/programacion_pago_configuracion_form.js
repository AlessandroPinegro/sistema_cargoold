
$(document).ready(function () {
    ax.setSuccess("exitoProgramacionPagoConfiguracion");
    var id = document.getElementById("id").value;
    obtenerConfiguracionesIniciales(id);
    cargarSelect2();
});

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/programacionPago/programacion_pago_configuracion_listar.php";
    cargarDiv("#window", url);
}

function exitoProgramacionPagoConfiguracion(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'guardarProgramacionPagoConfiguracion':
                onResponseGuardarProgramacionPagoConfiguracion(response.data);
                loaderClose();
                break;
        }
    }
}

function obtenerConfiguracionesIniciales(ppagoConfiguracionId) {
    loaderShow();
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParam("ppagoConfiguracionId", ppagoConfiguracionId);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data);
    var dataProv = [];
    var itemInicialProv = {id: -1, persona_nombre: 'Todos'};
    dataProv.push(itemInicialProv);

    $.each(data.dataProveedor, function (i, item) {
        dataProv.push(item);
    });
    select2.cargar('cboProveedor', dataProv, 'id', 'persona_nombre');
    select2.cargar('cboGrupoProducto', data.dataBienTipo, 'id', ['codigo', 'descripcion']);
    select2.cargar('cboIndicador', data.dataIndicador, 'id', 'descripcion');

    //asignar
    select2.asignarValor('cboProveedor', -1);
    select2.asignarValor('cboIndicador', null);
    
    if(!isEmpty(data.dataProgramacionPago)){
        llenarFormularioEditar(data);
    }
}

function llenarFormularioEditar(data) {
    var dataProgramacionPago = data.dataProgramacionPago[0];

    $('#txtDescripcion').val(dataProgramacionPago.descripcion);
    $('#txtComentario').val(dataProgramacionPago.comentario);
    select2.asignarValor('cboProveedor', dataProgramacionPago.persona_id);
    select2.asignarValor('cboGrupoProducto', dataProgramacionPago.bien_tipo_ids.split(";"));

    if (!isEmpty(data.dataProgramacionPagoDetalle)) {
        $.each(data.dataProgramacionPagoDetalle, function (i, itemDetalle) {
            var objDetalle = {};            
            objDetalle.indicadorId = itemDetalle.indicador_id;
            objDetalle.dias = itemDetalle.dias;
            objDetalle.porcentaje = itemDetalle.porcentaje;
            objDetalle.indicadorDesc = itemDetalle.indicador_descripcion;
            objDetalle.programacionPagoDetalleId = itemDetalle.id;
            listaProgramacionPagoDetalle.push(objDetalle);
            onListarProgramacionPagoDetalle(listaProgramacionPagoDetalle);
        });
    }
}

function abrirModalNuevoDetalle() {
    $('#modalProgramacionPagoDetalle').modal('show');
}

//AGREGAR PROGRAMACION PAGO DETALLE

var listaProgramacionPagoDetalle = [];

function agregarProgramacionPagoDetalle() {
    var indicadorId = select2.obtenerValor('cboIndicador');
    var dias = $('#txtDias').val();
    var porcentaje = $('#txtPorcentaje').val();
    var detalleIndice = $('#detalleIndice').val(); //el indice de edicion        
    var objDetalle = {};//Objeto para el detalle  

    if (validarFormularioProgramacionPagoDetalle(indicadorId, dias, porcentaje)) {
        if (validarProgramacionPagoDetalleRepetido(indicadorId, dias)) {
            var indicadorDesc = select2.obtenerText('cboIndicador');
            objDetalle.indicadorId = indicadorId;
            objDetalle.dias = dias;
            objDetalle.porcentaje = porcentaje;
            objDetalle.indicadorDesc = indicadorDesc;

            if (detalleIndice != '') {// validamos si es edicion                                
                objDetalle.programacionPagoDetalleId = listaProgramacionPagoDetalle[detalleIndice].programacionPagoDetalleId;
                listaProgramacionPagoDetalle[detalleIndice] = objDetalle;
            } else {
                objDetalle.programacionPagoDetalleId = null;
                listaProgramacionPagoDetalle.push(objDetalle);
            }

//          console.log(listaProgramacionPagoDetalle);
//          console.log(listaProgramacionPagoDetalleEliminado);
            onListarProgramacionPagoDetalle(listaProgramacionPagoDetalle);
            limpiarCamposProgramacionPagoDetalle();
            limpiarMensajesProgramacionPagoDetalle();

            $('#modalProgramacionPagoDetalle').modal('hide');
        }
    }
}

function validarFormularioProgramacionPagoDetalle(indicadorId, dias, porcentaje) {
    var bandera = true;
    limpiarMensajesProgramacionPagoDetalle();

    if (isEmpty(indicadorId)) {
        $("#msjIndicador").removeProp(".hidden");
        $("#msjIndicador").text("Indicador es obligatorio").show();
        bandera = false;
    }

    if (dias < 0 || isEmpty(dias)) {
        $("#msjDias").removeProp(".hidden");
        $("#msjDias").text("Días tiene que ser igual o mayor a cero").show();
        bandera = false;
    }

    if (porcentaje <= 0 || isEmpty(porcentaje)) {
        $("#msjPorcentaje").removeProp(".hidden");
        $("#msjPorcentaje").text("Porcentaje tiene que ser positivo").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesProgramacionPagoDetalle() {
    $("#msjUnidadMedida").hide();
    $("#msjDias").hide();
    $("#msjPorcentaje").hide();
}

function limpiarCamposProgramacionPagoDetalle() {
    select2.asignarValor('cboIndicador', null);
    $('#txtDias').val('');
    $('#txtPorcentaje').val('');
    $('#detalleIndice').val('');
}

function buscarProgramacionPagoDetalleXIndicador(indicadorId) {
    var ind = -1;

    if (!isEmpty(listaProgramacionPagoDetalle)) {
        $.each(listaProgramacionPagoDetalle, function (i, item) {
            if (item.indicadorId == indicadorId) {
                ind = i;
            }
        });
    }

    return ind;
}

function buscarProgramacionPagoDetalle(indicadorId, dias) {
    var ind = -1;

    if (!isEmpty(listaProgramacionPagoDetalle)) {
        $.each(listaProgramacionPagoDetalle, function (i, item) {
            if (item.indicadorId == indicadorId && item.dias == dias) {
                ind = i;
            }
        });
    }

    return ind;
}

function validarProgramacionPagoDetalleRepetido(indicadorId, dias) {
    var valido = true;
    //PUEDE REPETIRSE: Después de B/L
    if (indicadorId != 63) {
        var detalleIndice = $('#detalleIndice').val();

        if (detalleIndice != '') {
            //alert('igual');
            var indice = buscarProgramacionPagoDetalleXIndicador(indicadorId);
            if (indice != detalleIndice && indice != -1) {
                mostrarAdvertencia("El indicador ya ha sido agregado");
                valido = false;
            } else {
                valido = true;
            }
        } else {
            //alert('diferente');
            var indice = buscarProgramacionPagoDetalleXIndicador(indicadorId);
            if (indice > -1) {
                mostrarAdvertencia("El indicador ya ha sido agregado");
                valido = false;
            }
        }
    } else {
        var detalleIndice = $('#detalleIndice').val();

        if (detalleIndice != '') {
            //alert('igual');
            var indice = buscarProgramacionPagoDetalle(indicadorId, dias);
            if (indice != detalleIndice && indice != -1) {
                mostrarAdvertencia("El detalle ya ha sido agregado");
                valido = false;
            } else {
                valido = true;
            }
        } else {
            //alert('diferente');
            var indice = buscarProgramacionPagoDetalle(indicadorId, dias);
            if (indice > -1) {
                mostrarAdvertencia("El detalle ya ha sido agregado");
                valido = false;
            }
        }
    }

    return valido;
}

function onListarProgramacionPagoDetalle(data) {
    $('#dataTableProgramacionPagoDetalle tbody tr').remove();
    var cuerpo = "";
    var ind = 0;
    if (!isEmpty(data)) {
        data.forEach(function (item) {
            var eliminar = "<a href='#' onclick = 'eliminarProgramacionPagoDetalle(\"" + ind + "\")' >"
                    + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a href='#' onclick = 'editarProgramacionPagoDetalle(\"" + ind + "\")' >"
                    + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            cuerpo += "<tr>"
                    + "<td style='text-align:left;'>" + item.indicadorDesc + "</td>"
                    + "<td style='text-align:right;'>" + formatearCantidad(item.dias) + "</td>"
                    + "<td style='text-align:right;'>" + formatearNumero(item.porcentaje) + "</td>"
                    + "<td style='text-align:center;'>" + editar + eliminar
                    + "</td>"
                    + "</tr>";

            ind++;
        });

        $('#dataTableProgramacionPagoDetalle tbody').append(cuerpo);
    }
}

function editarProgramacionPagoDetalle(indice) {
    $('#detalleIndice').val(indice);

    abrirModalNuevoDetalle();
    var objDetalle = listaProgramacionPagoDetalle[indice];
    select2.asignarValor('cboIndicador', objDetalle.indicadorId);
    onChangeIndicador();
    $('#txtDias').val(formatearCantidad(objDetalle.dias));
    $('#txtPorcentaje').val(formatearNumero(objDetalle.porcentaje));
}

var listaProgramacionPagoDetalleEliminado = [];

function eliminarProgramacionPagoDetalle(indice) {
    if (!isEmpty(listaProgramacionPagoDetalle[indice].programacionPagoDetalleId)) {
        listaProgramacionPagoDetalleEliminado.push(listaProgramacionPagoDetalle[indice].programacionPagoDetalleId);
    }

    listaProgramacionPagoDetalle.splice(indice, 1);

    var detalleCopia = listaProgramacionPagoDetalle.slice();
    listaProgramacionPagoDetalle = [];

    if (!isEmpty(detalleCopia)) {
        $.each(detalleCopia, function (i, item) {
            listaProgramacionPagoDetalle.push(item);
        });
    }

    onListarProgramacionPagoDetalle(listaProgramacionPagoDetalle);
}

//------------ FIN DETALLE -----------------

function onChangeIndicador(){
    var indicador= select2.obtenerValor('cboIndicador');
    // si es diferente a Después de B/L:
    if(indicador!=63){
        $('#txtDias').val('0');
        $('#txtDias').attr("readonly","true");
    }else{
        $('#txtDias').val('');
        $('#txtDias').removeAttr("readonly");  
    }
}

function guardarProgramacionPago() {
    var descripcion = $('#txtDescripcion').val();
    var proveedorId = select2.obtenerValor('cboProveedor');
    var grupoProducto = $('#cboGrupoProducto').val();
    var comentario = $('#txtComentario').val();

    if (validarFormulario(descripcion, proveedorId, grupoProducto)) {
        var id = document.getElementById('id').value;
        crearProgramacionPago(id, descripcion, proveedorId, grupoProducto,comentario);
    }
}

function validarFormulario(descripcion, proveedorId, grupoProducto) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajes();

    if (descripcion === "" || descripcion === null || espacio.test(descripcion) || descripcion.length === 0) {
        $("#msjDescripcion").text("Descripcion es obligatorio").show();
        bandera = false;
    }

    if (proveedorId === "" || proveedorId === null || espacio.test(proveedorId) || proveedorId.length === 0) {
        $("#msjProveedor").text("Proveedor es obligatorio").show();
        bandera = false;
    }

    if (grupoProducto === "" || grupoProducto === null || espacio.test(grupoProducto) || grupoProducto.length === 0) {
        $("#msjGrupoProducto").text("Grupo de producto es obligatorio").show();
        bandera = false;
    }
    
    if(isEmpty(listaProgramacionPagoDetalle)){
        $("#msjDetalle").text("Ingrese el detalle").show();
        bandera = false;        
    }else{    
        var porcentajeTotal=0;
        $.each(listaProgramacionPagoDetalle, function (i, item) {
            porcentajeTotal=porcentajeTotal+item.porcentaje*1;
        });
        
        if(porcentajeTotal!=100){
            mostrarAdvertencia('El porcentaje total debe ser igual a 100');
            bandera = false;        
        }
        
    }
    
    return bandera;
}

function limpiarMensajes() {
    $("#msjDescripcion").hide();
    $("#msjProveedor").hide();
    $("#msjGrupoProducto").hide();
    $("#msjDetalle").hide();
}

function crearProgramacionPago(id, descripcion, proveedorId, grupoProducto,comentario) {
    loaderShow();
    ax.setAccion("guardarProgramacionPagoConfiguracion");
    ax.addParamTmp("programacionPagoConfiguracionId", id);
    ax.addParamTmp("descripcion", descripcion);
    ax.addParamTmp("proveedorId", proveedorId);
    ax.addParamTmp("grupoProducto", grupoProducto);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("listaProgramacionPagoDetalle", listaProgramacionPagoDetalle);
    ax.addParamTmp("listaProgramacionPagoDetalleEliminado", listaProgramacionPagoDetalleEliminado);
    ax.consumir();
}

function onResponseGuardarProgramacionPagoConfiguracion(data){
    if(data[0]['vout_exito']==1){
        mostrarOk(data[0]['vout_mensaje']);
        cargarPantallaListar();
    }else{
        mostrarAdvertencia((data[0]['vout_mensaje']));
    }
}