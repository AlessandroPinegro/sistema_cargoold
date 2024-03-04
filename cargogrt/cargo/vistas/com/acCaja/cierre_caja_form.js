var idEditar=document.getElementById("idEditar").value;
var dataProducto=[{id:1, descripcion: "Producto 1", stock: 10},{id:2, descripcion: "Producto 2", stock: 15}]

$(document).ready(function () {
    loaderClose();
    ax.setSuccess("exitoCierreCaja");
    configuracionesIniciales();
    $("#cboProducto").select2({
        width: "100%"
    });
    
    $('#importeTraslado').prop("readonly", true);
    $('#rbCaja').prop("checked", true);
});

function exitoCierreCaja(response) {
     console.log(response);
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesCierre':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;   
            case 'obtenerBienXId':
                AgregarProducto(response.data);
                loaderClose();
                break;
            case 'guardarCierreCaja':
                loaderClose();
                exitoCrear(response.data);
                break;
        }
    }else{
        switch (response[PARAM_ACCION_NAME]) {
            case 'guardarCierreCaja':
                recargarPagina(response);
                break;
                
                    case 'obtenerConfiguracionesInicialesCierre':
         cargarPantallaListar();
                break; 
        }
        
    }
}

function configuracionesIniciales() {
     console.log("llego");
    loaderShow();    
    var idEditar = document.getElementById("idEditar").value;
    var cajaId = document.getElementById("cajaId").value;
    ax.setAccion("obtenerConfiguracionesInicialesCierre");
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
        setTimeout(function(){ cargarPantallaListar() }, 7000); // intervalo de tiempo para que ejecute la funcion , 500 = 0.5 seg
    }
}

var dataInicial = [];

var visa='0.00';
var deposito='0.00';
var transferencia='0.00';
var traslado='0.00';
var efectivo='0.00';
var total='0.00';
var egreso='0.00';
var ingreso='0.00';
var ingresoTotal='0.00';
var egresoEfectivo='0.00';
var egresoOtros='0.00';
var egresoPos='0.00';
var egresoOtros='0.00';
var efectivoTotal='0.00';

var lstImportes_modificados = [];
    
function onResponseObtenerConfiguracionesIniciales(data){
    dataInicial= JSON.parse(JSON.stringify(data));
    
    if(!isEmpty(data.dataAperturaInventarioCombo)){
        select2.cargar("cboProducto", data.dataAperturaInventarioCombo, "bien_id", "bien_descripcion");
        select2.asignarValor("cboProducto",null);
    }
    
//    $('#dviAgregarBien').hide();
    
    if(isEmpty(idEditar)){
        $('#spFecha').html("al " + data.dataApertura[0]["fecha"] + " - Turno: " 
                + data.dataApertura[0]["turno"]);
    }else{
        $('#spFecha').html('al '+ data.dataAperturaCierre[0]["fecha_cierre_formato"]
                + " - turno: " + data.dataAperturaCierre[0]["turno_cierre"]);
    }
    
     ingresoTotal=redondearDosDecimales(data.dataIngresos[0]['importe_total']);
     egresoOtros=redondearDosDecimales(data.dataEgresosOtros[0]['importe_total']);
     egresoPos=redondearDosDecimales(data.dataEgresosPOS[0]['importe_total']);
     efectivoTotal=redondearDosDecimales(data.dataEfectivo[0]['importe_total']);
     egresoEfectivo=redondearDosDecimales(data.dataEgresosEfectivo[0]['importe_total']);
     
    visa=redondearDosDecimales(data.dataVisa[0]['importe_total']);
    deposito=redondearDosDecimales(data.dataDeposito[0]['importe_total']);
    transferencia=redondearDosDecimales(data.dataTransferencia[0]['importe_total']);
    var visaInfo = visa;
    var depositoInfo = deposito;
    var transferenciaInfo = transferencia;
    
    if(!isEmpty(data.dataCajaChica)){
        $('#importeCierre').val(efectivo);
    }
    
    
    if(!isEmpty(data.dataAperturaCaja)){
        var dataApertura=data.dataAperturaCaja[0];
        
        importe_apertura=redondearDosDecimales(dataApertura.importe_apertura);
    
        if (!isEmpty(data.dataAperturaCierre)) {
            var dataAperturaCierre = data.dataAperturaCierre[0];
            visa=redondearDosDecimales(dataAperturaCierre.visa);
            deposito=redondearDosDecimales(dataAperturaCierre.deposito);
            transferencia=redondearDosDecimales(dataAperturaCierre.transferencia);
            traslado=redondearDosDecimales(dataAperturaCierre.traslado);
            efectivo=redondearDosDecimales(dataAperturaCierre.importe_cierre);
            importe_apertura=redondearDosDecimales(dataAperturaCierre.importe_apertura);
            $('#txtComentario').val(dataAperturaCierre.comentario_cierre);
            
            if(dataAperturaCierre.is_pintar_cierre!=0){
                $('#importeTotal').css('color', 'red');
                $('#importeTotal').css('background-color', '#f2d889');
            }
            
            if(dataAperturaCierre.is_pintar_visa!=0){
                $('#importeVisa').css('color', 'red');
                $('#importeVisa').css('background-color', '#f2d889');
            }
            
            if(dataAperturaCierre.is_pintar_deposito!=0){
                $('#importeDeposito').css('color', 'red');
                $('#importeDeposito').css('background-color', '#f2d889');
            }
            
            if(dataAperturaCierre.is_pintar_transferencia!=0){
                $('#importeTransferencia').css('color', 'red');
                $('#importeTransferencia').css('background-color', '#f2d889');
            }
        }
        
        $('#txtComentarioApertura').val(dataApertura.comentario_apertura);
        $('#fechaApertura').html('Resumen al ' + '<span class="label label-inverse">' + 
                dataApertura.fecha_apertura_formato + '</span>' +
                ' | <span class="label label-inverse">' + 
                dataApertura.usuario_apertura + '</span>');
        
        
        $('#id').val(redondearDosDecimales(dataApertura.ac_caja_id));
        
        $("#dataList").empty();
        var cuerpo_total = "";
        var cuerpo = "";
        var descripcion="0.00"
        var importe="0.00";
        var cont_filas=0;
        var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
                + "<thead>"
                + "<tr class='info'>"
                + "<th width='40%' style='text-align: center;' class='celda-centrado'>Descripción</th>"
                + "<th style='text-align: center;' class='celda-centrado'>Monto</th>"
                + "<th style='text-align: center;' class='celda-centrado'>Total</th>"
                + "</tr>"
                + "</thead>";
        if (!isEmpty(data.dataIngresoSalida)) {
            var cont=data.dataIngresoSalida.length;
            var bcp='0.00';
            
            for(var i = 0; i <= 5; i++) {
                
                if(i==0){
                    descripcion="Apertura en efectivo";
                    importe=redondearDosDecimales(dataApertura.importe_apertura);
                }else if(i==1){
                    descripcion="Ingresos en efectivo";
                    
                    if(cont==1){
                        importe = (data.dataIngresoSalida[0]['ing_sal']=="I") ? 
                                redondearDosDecimales(data.dataIngresoSalida[0]['total_conversion']) : 
                                "0.00";
                    
                    }else{
                        importe = redondearDosDecimales(data.dataIngresoSalida[0]['total_conversion']);
                          
                    }
                }else if(i==2){
                    descripcion="Salidas en efectivo";
                    
                    if(cont==1){
                        importe = (data.dataIngresoSalida[0]['ing_sal']=="S") ? 
                                redondearDosDecimales(data.dataIngresoSalida[0]['total_conversion']) : 
                                "0.00";
                        egreso=importe;
                    }else{
                        importe = redondearDosDecimales(data.dataIngresoSalida[1]['total_conversion']);
                        egreso=importe;
                    }
                }
                else if(i==3){
                    descripcion="POS";
                    importe=redondearDosDecimales(data.dataVisa[0]['importe_total']);
                }
                else if(i==4){
                    descripcion="Depósito";
                    importe=redondearDosDecimales(data.dataDeposito[0]['importe_total']);
                }
                else if(i==5){
                    descripcion="Transferencia";
                    importe=redondearDosDecimales(data.dataTransferencia[0]['importe_total']);
                }
                
                cuerpo = "<tr>"
                        + "<td class='celda-centrado'>" + descripcion + "</td>"
                        + "<td align='center'>S/ " + importe + "</td>";
                
                if (cont_filas == 0) {
                    if (i < 3) {
                        efectivo=dataApertura.importe_apertura;
                        
                        for(var x=0;x<cont;x++){
                            efectivo= redondearDosDecimales(parseFloat(efectivo) + parseFloat(redondearDosDecimales(data.dataIngresoSalida[x]['total_conversion'])));
                        }
                        
                        cuerpo += "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ " + efectivo + "</td>";
                        cont_filas=1;
                    }
                }else if (i == 3) {                      
                        bcp= redondearDosDecimales(parseFloat(visa) + parseFloat(deposito) + parseFloat(transferencia));
                        
                        cuerpo += "<td align='center' rowspan='3' \n\
                            style='vertical-align: middle; font-weight: bold;'>S/ " + bcp + "</td>";
                }
                
                cuerpo +="</tr>";
                cuerpo_total += cuerpo;
            }
        }else{
            cuerpo = "<tr>"
                    +"<td class='celda-centrado'>Apertura de efectivo</td>"
                    +"<td align='center'>S/ " + importe_apertura + "</td>"
                    +"<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ " + importe_apertura + "</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td class='celda-centrado'>Ingresos en efectivo</td>"
                    +"<td align='center'>S/ 0.00</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td class='celda-centrado'>Salidas en efectivo</td>"
                    +"<td align='center'>S/ 0.00</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td class='celda-centrado'>POS</td>"
                    +"<td align='center'>S/ " + visaInfo + "</td>"
                    +"<td align='center' rowspan='3' \n\
                            style='vertical-align: middle;font-weight: bold;'>S/ "
                    + (redondearDosDecimales(parseFloat(visaInfo) + parseFloat(depositoInfo)+parseFloat(transferenciaInfo))) + "</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td class='celda-centrado'>Depósito</td>"
                    +"<td align='center'>S/ " + depositoInfo + "</td>"
                    +"</tr>"
                    +"<tr>"
                    +"<td class='celda-centrado'>Transferencia</td>"
                    +"<td align='center'>S/ " + transferenciaInfo + "</td>"
                    +"</tr>"
            ;
            cuerpo_total += cuerpo;
            
            efectivo=importe_apertura;
        }
        
        var pie = '</table>';
        var html = cabeza + cuerpo_total + pie;
        $("#dataList").append(html);
        
    }
    
    efectivo = !isEmpty(data.dataAperturaCierre) ? data.dataAperturaCierre[0]["importe_cierre"] : efectivo;
    
    if(isEmpty(idEditar)){
        if(efectivo>140){
            traslado=redondearDosDecimales(efectivo-140);
            efectivo = redondearDosDecimales(140);
        }
    }
    
    
    $('#importeVisa').val(visa);
    $('#importeDeposito').val(deposito);
    $('#importeTransferencia').val(transferencia);
    
    total = redondearDosDecimales(parseFloat(redondearDosDecimales(efectivo)) + parseFloat(redondearDosDecimales(traslado)));
    
    if(!isEmpty(idEditar)){
        $('#importeTraslado').val(traslado);
        $('#importeCierre').val(redondearDosDecimales(efectivo));
    }else{
        $('#importeCierre').val(total);
    }
    

    $('#importeTotal').val(total);
    $('#egreso').val(egresoEfectivo);
    $('#efectivo').val(efectivoTotal);
    $('#ingreso').val(ingresoTotal);
      $('#egresoOtros').val(egresoOtros);
        $('#egresoPos').val(egresoPos);
    
}

function habilitarBoton(){
    if ($('#rbCaja').is(':checked')) {
        $('#importeCierre').prop("readonly",false);
        $('#importeTraslado').prop("readonly",true);
    }else if ($('#rbTraslado').is(':checked')) {
        $('#importeCierre').prop("readonly",true);
        $('#importeTraslado').prop("readonly",false);
    }
}

function sumaTotal(){
    var efectivo=$('#importeTotal').val();
    var caja=$('#importeCierre').val();
    var traslado=$('#importeTraslado').val();
    
    if ($('#rbCaja').is(':checked')) {
        $('#importeCierre').val(redondearDosDecimales(caja));
        $('#importeTraslado').val(redondearDosDecimales(parseFloat(redondearDosDecimales(efectivo)) - parseFloat(redondearDosDecimales(caja))));
    }else if ($('#rbTraslado').is(':checked')) {
        $('#importeTraslado').val(redondearDosDecimales(traslado));
        $('#importeCierre').val(redondearDosDecimales(parseFloat(redondearDosDecimales(efectivo)) - parseFloat(redondearDosDecimales(traslado))));
    }
}

var lstInventario = [];
var lstInventarioEliminado = [];
var lstInventarioModificados = [];
var ordenEdicion = 0;
var cont = 0;

var cont_recargar=0;
function onResponseListarBien(dataProducto) {
     console.log(dataProducto);
    
    $("#dataListInventario").empty();
    var texto="";
    
    if(isEmpty(idEditar)){
        texto="Apertura";
    }else{
        texto="Cierre";
    }
    
    var comentario="";
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatableInventario' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>#</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Producto</th>"
            + "<th style='text-align:center; vertical-align: middle;'>" + texto + "</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Ingreso</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Salida</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Unidad Medida</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Total Cierre</th>"
//            + "<th style='text-align:center; vertical-align: middle;'>Stock Actual</th>"
            + "</tr>"
            + "</thead>";
    
    if (!isEmpty(dataProducto)) {
        
        $.each(dataProducto, function (index, item) {
            var total_cierre=0;
            if (isEmpty(idEditar)){
                
                if (!item.id.match(/x/g)){
                    if(cont_recargar==0){
                        lstInventario[index]["id"]="t"+index;
                        total_cierre = redondearDosDecimales(parseFloat(redondearDosDecimales(item.cant_apertura_cierre)) + 
                                       parseFloat(redondearDosDecimales(item.ingreso_almacen)) -
                                       parseFloat(redondearDosDecimales(item.salida_almacen)));

                        lstInventario[index]["stock"]= redondearDosDecimales(total_cierre);
                        lstInventario[index]["total_cierre"]= redondearNumero(total_cierre).toFixed(2);
                        lstInventario[index]["is_pintar"]= 0;
                    }
                    
                
                }else{
                    if(cont_recargar==0){
                        total_cierre = redondearDosDecimales(parseFloat(redondearDosDecimales(item.cant_cierre_agregado)) + 
                                       parseFloat(redondearDosDecimales(item.ingreso_almacen)) -
                                       parseFloat(redondearDosDecimales(item.salida_almacen)));

                        lstInventario[index]["total_cierre"]= redondearNumero(total_cierre).toFixed(2);
                    }
                }
                
                
            }else{
                if(cont_recargar==0){
                    lstInventario[index]["total_cierre"]= redondearNumero(item.cant_apertura_cierre).toFixed(2);
                    lstInventario[index]["stock"]= redondearDosDecimales(item.cant_apertura_cierre);
                }
            }
            
            lstInventario[index]["cant_apertura_cierre"]= redondearDosDecimales(lstInventario[index]["cant_apertura_cierre"]);
            lstInventario[index]["stock"]= redondearDosDecimales(lstInventario[index]["stock"]);
            
            var style="";
            
            if(item.is_pintar==1){
                style="color: red; background-color: #f2d889';";
            }
            
            cuerpo = "<tr>"
                    + "<td style='text-align:center;vertical-align: middle;'>" + (index+1) + "</td>"
                    + "<td width='40%' style='text-align:left;vertical-align: middle;'>" + item.bien_descripcion + "</td>"
                    + "<td style='text-align:right;vertical-align: middle;"
                    +(!isEmpty(idEditar)? style : "") + "'>" + redondearDosDecimales(item.cant_apertura_cierre)
                        + " <i class='fa fa-info-circle' style='color:#1ca8dd;' title='Stock " 
                        + (texto=="Cierre" ? ("cierre sugerido") : "apertura") + ": " 
                        + redondearDosDecimales(item.stock_apertura)+ "'></i></td>"
                    + "<td style='text-align:right;vertical-align: middle;'>" + redondearDosDecimales(item.ingreso_almacen) + "</td>"
                    + "<td style='text-align:right;vertical-align: middle;'>" + redondearDosDecimales(item.salida_almacen) + "</td>"
                    + "<td style='text-align:left;vertical-align: middle;'>" + item.unidad_medida_descripcion + "</td>"
                    + "<td style='text-align:center;'>\n\
                      <input type='number' id='txt" + index  + "' name='txtBien"+ item.bien_id  +"' \n\
                        class='form-control' value='" + item.total_cierre 
                    + "' style='text-align: right;" 
                    +(isEmpty(idEditar)? style : "") + "'"
                    + "' size='4' onchange='ActualizarCantidad(" + index + ",this.value)' min='0' size='4'></td>"
//                    + "<td style='text-align:right; vertical-align: middle;'>" + redondearDosDecimales(item.stock) + "</td>"
                    + "</tr>";
            cuerpo_total += cuerpo;
            if(!isEmpty(item.comentario)){
                comentario=item.comentario;
            }
        });
    }
    cont_recargar++;
    var pie = '</table>';
    
    var html = cabeza + cuerpo_total + pie;
    $("#dataListInventario").append(html);
    onResponseVacio('datatableInventario', [0, "asc"]);
    $('#txtComentarioInventario').val(comentario);
    
}

function ActualizarCantidad(index,cantidad,comodin){
    var texto="";
    
    if(comodin=="nuevo producto"){
        texto="stock";
    }else{
//        if(!lstInventario[index]["id"].match(/x/g)){
//            texto="cant_apertura_cierre";
//        }else{
            texto="total_cierre";
//        }
    }
    
    var cant=redondearNumero(cantidad).toFixed(2);
    var valor=false;
    
    if (!isEmpty(lstInventarioModificados)) {
        for (var i = 0; i < lstInventarioModificados.length; i++) {
            if (lstInventario[index]["bien_id"] != lstInventarioModificados[i]["bien_id"]) {
                valor=true;
            }else{
                if (cant !== lstInventarioModificados[i][texto]) {
                    lstInventarioModificados[i][texto] = cant;
                    lstInventarioModificados[i]["is_pintar"] = 1;
                }
            }
        }
        
        if(valor){
            if (cant !== lstInventario[index][texto]) {
                lstInventarioModificados.push(lstInventario[index]);
            }
        }
    } else {
        if (cant !== lstInventario[index]["stock"]) {
            lstInventarioModificados.push(lstInventario[index]);
        }
    }
    
    lstInventario[index]["total_cierre"]=cant;
    lstInventario[index]["is_pintar"]=1;
    
    for (var x = 0; x < lstInventarioModificados.length; x++) {
        if (lstInventarioModificados[x]["total_cierre"] == lstInventarioModificados[x]["stock"]) {
            lstInventarioModificados.splice(x, 1);
            lstInventario[index]["is_pintar"]=0;
        }
    }
    
}

function limpiarMensajes(){
    $("#msjImporteCierre").text("").show();
    $("#msjImporteVisa").text("").show();
    $("#msjImporteDeposito").text("").show();
    $("#msjImporteTraslado").text("").show();
    $("#msjInventario").text("").show();
    $("#msjFormGen").text("").show();
}

function validarCampos() {
    validarFormulario($("#importeCierre").val(),$("#importeVisa").val(),$("#importeTraslado").val(),$("#msjImporteDeposito").val());
    
    $("#msjProducto").hide();
    $("#msjCant").hide();
    var valor = true
    
    if (isEmpty($("#cboProducto").val())) {
        $("#msjProducto").html("Seleccione un producto").show();
        valor = false;
    }
    
    return valor;
}

$("#importeVisa").change(function () {
    $("#importeVisa").val(redondearDosDecimales(this.value));
});

$("#importeTotal").change(function () {
    $("#importeTotal").val(redondearDosDecimales(this.value));
    $("#importeCierre").val(redondearDosDecimales(this.value));
});

$("#btnAgregar").click(function () {
    var valor =validarCampos();
    
    if(valor){
        loaderShow();
        ax.setAccion("obtenerBienXId");
        ax.addParamTmp("empresaId", commonVars.empresa);
        ax.addParamTmp("bien_id", $("#cboProducto").val());
        ax.addParamTmp("comodin", "cierre");
        ax.addParamTmp("idEditar", idEditar);
        ax.consumir();
    }
});

function AgregarItem(item){
    var cantidad = redondearDosDecimales(document.getElementById('txtCant').value); 
    var bien = {};
    bien.id = "x" + cont++;
    bien.bien_id = item.bien_id;
    bien.bien_descripcion = item.bien_descripcion;
    bien.cant_apertura_cierre = "0.00";
    bien.bien_tipo_id=item.bien_tipo_id;
    bien.comentario = item.comentario;
    bien.ingreso_almacen = item.ingreso_almacen;
    bien.organizador_id = item.organizador_id;
    bien.salida_almacen = item.salida_almacen;
    bien.stock_apertura = "0.00";
    bien.total_cierre = cantidad;
    bien.cant_cierre_agregado = cantidad;
    bien.is_pintar= (item.stock*1!=cantidad*1)? 1 : 0;
    bien.stock = item.stock;
    bien.unidad_medida_id = item.unidad_medida_id;
    bien.unidad_medida_descripcion = item.unidad_medida_descripcion;
    lstInventario.push(bien);
    onResponseListarBien(lstInventario);
    $("#txtCant").val("");
    select2.asignarValor("cboProducto",null);
}

function AgregarProducto(dataProducto){
    var vout_exito="0";
    var valor=false;
    
    if (!isEmpty(dataProducto)) {
        $.each(dataProducto, function (index, item) {
            if(!isEmpty(lstInventario)){
                if(lstInventario.length>0){
                    for(var i=0;i < lstInventario.length; i++){
                        if (item.bien_id==lstInventario[i]["bien_id"]){
                            valor=true;
                        }
                    }

                    if(!valor){
                        AgregarItem(item);
                        var indice = lstInventario.length-1;
                        ActualizarCantidad(indice,lstInventario[indice]["cant_cierre_agregado"],"nuevo producto");
                        vout_exito="1";
                    }
                }
            }else{
                AgregarItem(item);
                var indice = lstInventario.length-1;
                ActualizarCantidad(indice,lstInventario[indice]["cant_cierre_agregado"],"nuevo producto");
                vout_exito="1";
            }
                
        });
        
        if(vout_exito=="1"){
            mostrarOk("Producto agregado a la lista");
            limpiarMensajes();
        }else{
            mostrarInformacion("El producto ya existe");
        }
    }
//    else{
//        mostrarInformacion("El producto no tiene stock");
//    }
    
}

function guardar(){    

    var importeCierre = $('#importeCierre').val();
    var importeVisa = $('#importeVisa').val();
    var importeDeposito = $('#importeDeposito').val();
    var importeTraslado = $('#importeTraslado').val();
    var importeTransferencia = $('#importeTransferencia').val();
    var comentario = $('#txtComentario').val(); 
    var egreso = $('#egreso').val();
     var ingreso = $('#ingreso').val();
          var egresoOtros = $('#egresoOtros').val();
               var egresoPos = $('#egresoPos').val();
                    var efectivo = $('#efectivo').val();

    if (validarFormulario(importeCierre,importeVisa,importeTraslado,importeDeposito,importeTransferencia)) {
        var id = document.getElementById('id').value;//para guardar en ese id el cierre de caja
        var idEditar = document.getElementById('idEditar').value;//para edicion
        guardarCierreCaja(idEditar,id,redondearDosDecimales(importeTraslado),
                        redondearDosDecimales(importeVisa),redondearDosDecimales(importeCierre),
                        comentario,redondearDosDecimales(importeDeposito),redondearDosDecimales(importeTransferencia),
                        redondearDosDecimales(egreso),redondearDosDecimales(ingreso),redondearDosDecimales(egresoOtros),
                        redondearDosDecimales(egresoPos),redondearDosDecimales(efectivo));        
    }
}

function validarFormulario(importeCierre,importeVisa,importeTraslado, importeDeposito,importeTransferencia) {
    limpiarMensajes();
    var bandera = true;
    var espacio = /^\s+$/;

    if (importeCierre === "" || importeCierre === null || espacio.test(importeCierre) || importeCierre.length === 0) {
        $("#msjImporteCierre").text("Importe de cierre es obligatorio").show();
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
        bandera = false;
    }else{
        $("#msjImporteCierre").text("").hide();
    }
    
    if (importeVisa === "" || importeVisa === null || espacio.test(importeVisa) || importeVisa.length === 0) {
        $("#msjImporteVisa").text("Importe de visa es obligatorio").show();
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
        bandera = false;
    }else{
        $("#msjImporteVisa").text("").hide();
    }
    
    if (importeDeposito === "" || importeDeposito === null || espacio.test(importeDeposito) || importeDeposito.length === 0) {
        $("#msjImporteDeposito").text("Importe de depósito es obligatorio").show();
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
        bandera = false;
    }else{
        $("#msjImporteDeposito").text("").hide();
    }
    
    if (importeTraslado === "" || importeTraslado === null || espacio.test(importeTraslado) || importeTraslado.length === 0) {
        $("#msjImporteTraslado").text("Importe de traslado es obligatorio").show();
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
        bandera = false;
    }else{
        $("#msjImporteTraslado").text("").hide();
    }
    
    if (importeTransferencia === "" || importeTransferencia === null || espacio.test(importeTransferencia) || importeTransferencia.length === 0) {
        $("#msjImporteTransferencia").text("Importe de transferencia es obligatorio").show();
//        $("#msjFormGen").text("Por favor revise la pestaña: Caja").show();
        bandera = false;
    }else{
        $("#msjImporteTransferencia").text("").hide();
    }
    
    return bandera;
}

function guardarCierreCaja(idEditar,id,importeCierre,importeVisa,importeTraslado,comentario,importeDeposito,importeTransferencia,egreso,ingreso,
                            egresoOtros,egresoPos,efectivo) {  
                       
    lstImportes_modificados = [];
    
    var is_pintar_visa= (!isEmpty(dataInicial.dataAperturaCierre)) 
                ? dataInicial.dataAperturaCierre[0]["is_pintar_visa"] : 0;
    var is_pintar_deposito= (!isEmpty(dataInicial.dataAperturaCierre)) 
                ? dataInicial.dataAperturaCierre[0]["is_pintar_deposito"] : 0;
    var is_pintar_transferencia= (!isEmpty(dataInicial.dataAperturaCierre)) 
                ? dataInicial.dataAperturaCierre[0]["is_pintar_transferencia"] : 0;
    var is_pintar_cierre= (!isEmpty(dataInicial.dataAperturaCierre)) 
                ? dataInicial.dataAperturaCierre[0]["is_pintar_cierre"] : 0;

    var importeTotal = redondearDosDecimales($("#importeTotal").val());
    if(total!=importeTotal){
        lstImportes_modificados.push({descripcion: "Caja",monto_original: total,monto_modificado: importeTotal});
        is_pintar_cierre=1;
    }
    
    if(visa!=importeVisa){
        lstImportes_modificados.push({descripcion: "VISA",monto_original: visa,monto_modificado: importeVisa});
        is_pintar_visa=1;
    }
    
    if(deposito!=importeDeposito){
        lstImportes_modificados.push({descripcion: "Depósito",monto_original: deposito,monto_modificado: importeDeposito});
        is_pintar_deposito=1;
    }
    
    if(transferencia!=importeTransferencia){
        lstImportes_modificados.push({descripcion: "Transferencia",monto_original: transferencia,monto_modificado: importeTransferencia});
        is_pintar_transferencia=1;
    }
    
    var cajaId = document.getElementById("cajaId").value; 
    
    loaderShow();
    ax.setAccion("guardarCierreCaja");
    ax.addParamTmp("idEditar", idEditar);
    ax.addParamTmp("id", id);
    ax.addParamTmp("importeCierre", importeCierre);
    ax.addParamTmp("importeVisa", importeVisa);
    ax.addParamTmp("importeDeposito", importeDeposito);
    ax.addParamTmp("importeTransferencia", importeTransferencia);
    ax.addParamTmp("importeTraslado", importeTraslado);
    ax.addParamTmp("comentario", comentario);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("lstImportes_modificados", lstImportes_modificados);   
    ax.addParamTmp("is_pintar_cierre", is_pintar_cierre);
    ax.addParamTmp("is_pintar_visa", is_pintar_visa);
    ax.addParamTmp("is_pintar_deposito", is_pintar_deposito);
    ax.addParamTmp("is_pintar_transferencia", is_pintar_transferencia);
    ax.addParamTmp("total_cierre", importeTotal);
    ax.addParamTmp("cierre_sugerido", total);
    ax.addParamTmp("visa_sugerido", visa);
    ax.addParamTmp("deposito_sugerido", deposito);
    ax.addParamTmp("transferencia_sugerido", transferencia);
    ax.addParamTmp("dataInicial", dataInicial);
    ax.addParamTmp("cajaId", cajaId);
    ax.addParamTmp("egreso", egreso);  
    ax.addParamTmp("ingreso", ingreso);  
    ax.addParamTmp("egresoOtros", egresoOtros); 
    ax.addParamTmp("egresoPos", egresoPos); 
    ax.addParamTmp("efectivo", efectivo); 
    ax.consumir();
}