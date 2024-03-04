var idEditar = document.getElementById("idEditar").value;
var cajaId = document.getElementById("cajaId").value;
$(document).ready(function () {
    loaderClose();
    ax.setSuccess("exitoAperturaCaja");
    configuracionesIniciales();
//    validarIpCaja(cajaId);
    var idOcultar = document.getElementById("idOcultar").value;
    if (!isEmpty(idOcultar) && idOcultar == 1) {
        $('#btnCancelar').hide();
    }
    $("#cboProducto").select2({
        width: "100%"
    });

});

function exitoAperturaCaja(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesApertura':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerBienXId':
                AgregarProducto(response.data);
                loaderClose();
                break;
            case 'guardarAperturaCaja':
                loaderClose();
                
                exitoCrear(response.data);
                break;
//                 case 'validarIpCaja':
//       
//                loaderClose();
//
//    location.href = URL_BASE + "vistas/com/ac_caja_lisar.php";
//                break;   
        
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
//                    case 'validarIpCaja':
//                loaderClose();
//              
//                   location.href = URL_BASE + "vistas/com/ac_caja_lisar.php";
//                break;   
                
            case 'guardarAperturaCaja':
                recargarPagina(response);
                break;
                
                case 'obtenerConfiguracionesInicialesApertura':
                cargarPantallaListar()
                break; 
        
        }

    }
}

function configuracionesIniciales() {
    loaderShow();
    var idEditar = document.getElementById("idEditar").value;
    var cajaId = document.getElementById("cajaId").value;
    ax.setAccion("obtenerConfiguracionesInicialesApertura");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("idEditar", idEditar);
    ax.addParamTmp("cajaId", cajaId);
    ax.consumir();
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/acCaja/ac_caja_listar.php";
    cargarDiv("#window", url);
}

function exitoCrear(data) {
    if (data[0]["vout_exito"] == 1) {
        cargarPantallaListar();
    }
}

function recargarPagina(data) {
    if (data.message == "Existen cambios con respecto a la carga inicial al momento que se habilitó ésta página, por favor vuelva a cargar el sistema") {
        setTimeout(function () {
            cargarPantallaListar()
        }, 7000); // intervalo de tiempo para que ejecute la funcion , 500 = 0.5 seg
    }
}

var dataInicial = [];
var aperturaSugerido = "0.00";

function onResponseObtenerConfiguracionesIniciales(data) {
    dataInicial = JSON.parse(JSON.stringify(data));

    if (!isEmpty(data.dataAperturaInventarioCombo)) {
        select2.cargar("cboProducto", data.dataAperturaInventarioCombo, "bien_id", "bien_descripcion");
        select2.asignarValor("cboProducto", null);
    }

    if (isEmpty(idEditar)) {
        $('#spFecha').html("al " + data.dataApertura[0]["fecha"]
                + " - Turno: " + data.dataApertura[0]["turno"]
                + " | Agencia: " + data.dataCaja[0]["agencia_codigo"]
                + " | Caja: " + data.dataCaja[0]["codigo"]
                );
    } else {
        $('#spFecha').html('al ' + data.dataAperturaCierre[0]["fecha_apertura_formato"]
                + " - Turno: " + data.dataAperturaCierre[0]["turno_apertura"]
                + " | Agencia: " + data.dataCaja[0]["agencia_codigo"]
                + " | Caja: " + data.dataCaja[0]["codigo"]
                );
    }

    if (isEmpty(data.dataACCaja)) {
        //$('#divDatosCierre').hide();
        $('#importeCierre').html("0.00");
        $('#fechaCierre').html('Cierre anterior');
    } else {
        //$('#divDatosCierre').show();
        var dataCierre = data.dataACCaja[0];
        $('#txtComentarioCierre').val(dataCierre.comentario_cierre);
        $('#fechaCierre').html('Cierre al '
                + '<span class="label label-inverse">' + dataCierre.fecha_cierre_formato + '</span>'
                + ' | <span class="label label-inverse">' + dataCierre.usuario_cierre + '</span>');
        $('#importeCierre').html(redondearDosDecimales(dataCierre.importe_cierre));

        if (isEmpty(data.dataAperturaCierre)) {
            $('#importeApertura').val(redondearDosDecimales(dataCierre.importe_cierre));
            aperturaSugerido = redondearDosDecimales(dataCierre.importe_cierre);
        }
    }

    if (!isEmpty(data.dataAperturaCierre)) {
        var dataAperturaCierre = data.dataAperturaCierre[0];
        $('#importeApertura').val(redondearDosDecimales(dataAperturaCierre.importe_apertura));
        $('#txtComentario').val(dataAperturaCierre.comentario_apertura);
        if (dataAperturaCierre.is_pintar_apertura != 0) {
            $('#importeApertura').css('color', 'red');
            $('#importeApertura').css('background-color', '#f2d889');
        }
        aperturaSugerido = dataAperturaCierre.apertura_sugerido;
    }

    $('#sugerido').html("<p title='Efectivo sugerido: " + aperturaSugerido + "' class='fa fa-info-circle' style='color:#1ca8dd;'>");

//    if(!isEmpty(data.dataAperturaInventarioTabla)){
//        lstInventario=data.dataAperturaInventarioTabla;
//    }
//    
//    onResponseListarBien(lstInventario);
}

var lstInventario = [];
var lstInventarioEliminado = [];
var lstInventarioModificados = [];
var ordenEdicion = 0;
var cont = 0;

function eliminarBien(BienId) {
    ordenEdicion = 0;
    lstInventario.some(function (item) {
        if (item.bien_id == BienId) {
            lstInventario.splice(ordenEdicion, 1);
            lstInventarioEliminado.push({inventario_id: item.id})
            onResponseListarBien(lstInventario);
            $("#txtCant").val();
            return item.bien_id === BienId;
        }
        ordenEdicion++;
    });

    ordenEdicion = 0;
    if (!isEmpty(lstInventarioModificados)) {
        lstInventarioModificados.some(function (item) {
            if (item.bien_id == BienId) {
                lstInventarioModificados.splice(ordenEdicion, 1);
                onResponseListarBien(lstInventarioModificados);
                $("#txtCant").val();
                return item.bien_id === BienId;
            }
            ordenEdicion++;
        });
    }

}

function ActualizarCantidad(index, cantidad) {
    var cant = redondearDosDecimales(cantidad);
    var valor = false;

    if (!isEmpty(lstInventarioModificados)) {
        for (var i = 0; i < lstInventarioModificados.length; i++) {
            if (lstInventario[index]["bien_id"] != lstInventarioModificados[i]["bien_id"]) {
                valor = true;
            } else {
                if (cant !== lstInventarioModificados[i]["cant_apertura_cierre"]) {
                    lstInventarioModificados[i]["cant_apertura_cierre"] = cant;
                }
            }
        }

        if (valor) {
            if (cant !== lstInventario[index]["stock"]) {
                lstInventarioModificados.push(lstInventario[index]);
            }
        }
    } else {
        if (cant !== lstInventario[index]["stock"]) {
            lstInventarioModificados.push(lstInventario[index]);
        }
    }

    lstInventario[index]["cant_apertura_cierre"] = cant;
    lstInventario[index]["is_pintar"] = 1;

    for (var x = 0; x < lstInventarioModificados.length; x++) {
        if (lstInventarioModificados[x]["cant_apertura_cierre"] == lstInventarioModificados[x]["stock"]) {
            lstInventarioModificados.splice(x, 1);
            lstInventario[index]["is_pintar"] = 0;
        }
    }

}

function limpiarMensajes() {
    $("#msjImporteApertura").text("").show();
    $("#msjInventario").text("").show();
    $("#msjFormGen").text("").show();
}

function validarCampos() {
    validarFormulario($("#importeApertura").val());

    $("#msjProducto").hide();
    $("#msjCant").hide();
    var valor = true

    if (isEmpty($("#cboProducto").val())) {
        $("#msjProducto").html("Seleccione un producto").show();
        valor = false;
    }

    return valor;
}

$("#importeApertura").change(function () {
    $("#importeApertura").val(redondearDosDecimales(this.value));
});

$("#btnAgregar").click(function () {
    var valor = validarCampos();

    if (valor) {
        loaderShow();
        ax.setAccion("obtenerBienXId");
        ax.addParamTmp("empresaId", commonVars.empresa);
        ax.addParamTmp("bien_id", $("#cboProducto").val());
        ax.addParamTmp("comodin", "apertura");
        ax.consumir();
    }
});

function AgregarItem(item) {
    var bien = {};
    bien.id = "t" + cont++;
    bien.bien_id = item.bien_id;
    bien.bien_descripcion = item.bien_descripcion;
    bien.cant_apertura_cierre = document.getElementById('txtCant').value;
    bien.stock = item.stock;
    bien.unidad_medida_descripcion = item.unidad_medida_descripcion;
    bien.bien_tipo_id = item.bien_tipo_id;
    bien.comentario = item.comentario;
    bien.ingreso_almacen = item.ingreso_almacen;
    bien.organizador_id = item.organizador_id;
    bien.salida_almacen = item.salida_almacen;
    bien.stock_apertura = "0.00";
    bien.total_cierre = item.total_cierre;
    bien.unidad_medida_id = item.unidad_medida_id;
    lstInventario.push(bien);
    onResponseListarBien(lstInventario);
    $("#txtCant").val("");
    select2.asignarValor("cboProducto", null);
}

function VerificarListaModificados(item) {
    var indice = 0;
    var cantidad;

    for (var i = 0; i < lstInventario.length; i++) {
        if (item.bien_id == lstInventario[i]["bien_id"]) {
            indice = i;
            cantidad = lstInventario[i]["cant_apertura_cierre"];
        }
    }

    ActualizarCantidad(indice, cantidad);
}

function AgregarProducto(dataProducto) {
    var vout_exito = "0";
    var valor = false;

    if (!isEmpty(dataProducto)) {
        $.each(dataProducto, function (index, item) {
            if (!isEmpty(lstInventario)) {
                if (lstInventario.length > 0) {
                    for (var i = 0; i < lstInventario.length; i++) {
                        if (item.bien_id == lstInventario[i]["bien_id"]) {
                            valor = true;
                        }
                    }

                    if (!valor) {
                        AgregarItem(item);
                        VerificarListaModificados(item);
                        vout_exito = "1";
                    }
                }
            } else {
                AgregarItem(item);
                VerificarListaModificados(item);
                vout_exito = "1";
            }

        });

        if (vout_exito == "1") {
            mostrarOk("Producto agregado a la lista");
            limpiarMensajes();
        } else {
            mostrarInformacion("El producto ya existe");
        }
    }
//    else{
//        mostrarInformacion("El producto no tiene stock");
//    }

}

function onResponseListarBien(dataProducto) {
    $("#dataListInventario").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatableInventario' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>#</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Producto</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Cantidad</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Stock <i title='Stock referencial' class='fa fa-info-circle' style='color:#1ca8dd;'></i></th>"
            + "<th style='text-align:center; vertical-align: middle;'>Unidad Medida</th>"
//            + "<th style='text-align:center; vertical-align: middle;'>Acciones</th>"
            + "</tr>"
            + "</thead>";
    if (!isEmpty(dataProducto)) {
        $.each(dataProducto, function (index, item) {
            if (isEmpty(idEditar)) {
                lstInventario[index]["id"] = "t" + index;
            }
            lstInventario[index]["cant_apertura_cierre"] = redondearNumero(item.cant_apertura_cierre).toFixed(2);
            lstInventario[index]["stock"] = redondearDosDecimales(item.stock);
            lstInventario[index]["stock_apertura"] = redondearDosDecimales(item.stock_apertura);

            var style = "";
            if (item.is_pintar == 1) {
                style = "color: red; background-color: #f2d889';";
            }
            cuerpo = "<tr>"
                    + "<td width='10%' style='text-align:center; vertical-align: middle;'>" + (index + 1) + "</td>"
                    + "<td style='text-align:left; vertical-align: middle;'>" + item.bien_descripcion + "</td>"
                    + "<td width='15%' style='text-align:center;'>\n\
                      <div class='input-group'><input type='number' id='txt" + index + "' name='txt" + index
                    + "' class='form-control' value='" + item.cant_apertura_cierre
                    + "' style='text-align: right; " + style + "' size='4'"
                    + " onchange='ActualizarCantidad(" + index + ",this.value)' min='0'>"
                    + "<span class='input-group-addon'><p title='Sugerido apertura: " + item.stock_apertura + "' class='fa fa-info-circle' style='color:#1ca8dd;'></span></div></td>"
                    + "<td width='10%' style='text-align:right;vertical-align: middle;'>" + item.stock + "</td>"
                    + "<td width='15%' style='text-align:left;vertical-align: middle;'>" + item.unidad_medida_descripcion + "</td>";

//            cuerpo += "<td width='10%' style='text-align:center;vertical-align: middle;'>"
//                    + "<a href='#' onclick='eliminarBien(\"" + item.bien_id + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n"
//                    + "</td>"
            +"</tr>";

            cuerpo_total += cuerpo;
        });
        $('#txtComentarioInventario').val(dataProducto[0]["comentario"]);
    }
    var pie = '</table>';

    var html = cabeza + cuerpo_total + pie;
    $("#dataListInventario").append(html);
    onResponseVacio('datatableInventario', [0, "asc"]);


}

function guardar() {
    debugger;
    var importeApertura = $('#importeApertura').val();
    var cajaId=$('#cajaId').val();
    var comentario = $('#txtComentario').val();
//    var comentarioInventario = $('#txtComentarioInventario').val();
    var valor = validarFormulario(importeApertura);
  
    
    if (valor ) {
        var idEditar = document.getElementById('idEditar').value;//para edicion
        guardarAperturaCaja(idEditar, importeApertura, comentario);
    }
}

function validarFormulario(importeApertura) {
    var bandera = true;
    var espacio = /^\s+$/;

    if (importeApertura === "" || importeApertura === null || espacio.test(importeApertura) || importeApertura.length === 0) {
        $("#msjImporteApertura").text("Importe de apertura es obligatorio").show();
        bandera = false;
    } else {
        $("#msjImporteApertura").text("").hide();
    }

//    if(lstInventario.length==0){
//        $("#msjInventario").text("Ingrese por lo menos un producto").show();
//        bandera = false;
//    }else{
//        $("#msjInventario").text("").hide();
//    }

//    if(lstInventario.length==0 && (importeApertura === "" || importeApertura === null || espacio.test(importeApertura) || importeApertura.length === 0)){
//        $("#msjFormGen").text("Por favor revise la(s) pestaña(s): Caja e Inventario").show();
//    }else if(lstInventario.length>0 && (importeApertura === "" || importeApertura === null || espacio.test(importeApertura) || importeApertura.length === 0)){
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
//    }else if(lstInventario.length==0 && ( isEmpty(importeApertura) || importeApertura != null || !espacio.test(importeApertura) || importeApertura.length != 0)){
//        $("#msjFormGen").text("Por favor revise la pestaña: Inventario").show();
//    }else{
//        limpiarMensajes();
//    }

    if ((importeApertura === "" || importeApertura === null || espacio.test(importeApertura) || importeApertura.length === 0)) {
        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
    } else {
        limpiarMensajes();
    }
    return bandera;
}

function guardarAperturaCaja(idEditar, importeApertura, comentario) {
    let cajaId = document.getElementById("cajaId").value;
    loaderShow();
    ax.setAccion("guardarAperturaCaja");
    ax.addParamTmp("idEditar", idEditar);
    ax.addParamTmp("importeApertura", importeApertura);
    ax.addParamTmp("aperturaSugerido", aperturaSugerido);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("dataInicial", dataInicial);
    ax.addParamTmp("cajaId", cajaId); 
    ax.consumir();
}



//function validarIpCaja(cajaId) {
//    loaderShow();
//    ax.setAccion("validarCajaIp");
//    ax.addParamTmp("cajaId", cajaId); 
//    ax.consumir();
//}
