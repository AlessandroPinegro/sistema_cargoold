/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var banderaVolver = 0;
var empresa = "union";
function cargarDatosImprimir(data, volver)
{
    banderaVolver = volver;
    var documentoTipo = data.documentoTipoId; 
    if(documentoTipo==45) 
        documentoTipo=18;
    
    var nombreFuncion="plantillaDocumentoTipo"+documentoTipo;

    try {
        if(data.dataDocumento[0].identificador_negocio==1) {// 1: cotizacion de venta
            plantillaDocumentoTipoCotizacion(data,documentoTipo);
        }else{
            eval(nombreFuncion+"(data, documentoTipo);");
        }
    }
    catch (err) {
        plantillaDocumentoTipoEstandar(data, documentoTipo);
    }
    
//    switch (parseInt(documentoTipo)) {
////        case 6:
////            //boleta de venta JR
////            plantillaDocumentoTipo6(data, documentoTipo);
////            break;
////        case 78:
////            //boleta de venta
////            plantillaDocumentoTipo78(data, documentoTipo);
////            break;
////        case 81:
////            //boleta de venta
////            plantillaDocumentoTipo81(data, documentoTipo);
////            break;
//        case 7:
//            //factura de venta JR
//            plantillaDocumentoTipo7(data, documentoTipo);
//            break;
//        case 79:
//            //factura de venta
//            plantillaDocumentoTipo79(data, documentoTipo);
//            break;
//        case 82:
//            //factura de venta
//            plantillaDocumentoTipo82(data, documentoTipo);
//            break;
////        case 85:
////            //factura de venta
////            plantillaDocumentoTipo85(data, documentoTipo);
////            break;
//        case 63:
//            //Proforma IndustrialMV
//            plantillaDocumentoTipo63(data, documentoTipo);
//            break;
//        case 21:
//            //Nota de pedido IndustrialMV
//            plantillaDocumentoTipo21(data, documentoTipo);
//            break;
//        case 12:
//            //V. Guia de remisión JR
//            plantillaDocumentoTipo12(data, documentoTipo);
//            break;
//        case 77:
//            //V. Guia de remisión
//            plantillaDocumentoTipo77(data, documentoTipo);
//            break;
//        case 80:
//            //V. Guia de remisión
//            plantillaDocumentoTipo80(data, documentoTipo);
//            break;
//        case 45:
//        case 18:
//            //guia de remision IndustrialMV UC0
//            plantillaDocumentoTipo18(data, 18);
//            break;
//        case 83:
//            //guia de remision IndustrialMV HC0
//            plantillaDocumentoTipo83(data, documentoTipo);
//            break;
//        case 61:
//            //nota de credito JR
//            plantillaDocumentoTipo61(data, documentoTipo);
//            break;
//        case 98:
//            //nota de credito 
//            plantillaDocumentoTipo98(data, documentoTipo);
//            break;
//        case 101:
//            //nota de credito    
//            plantillaDocumentoTipo101(data, documentoTipo);
//            break;
//        case 62:
//            //nota de debito JR
//            plantillaDocumentoTipo62(data, documentoTipo);
//            break;
//        case 99:
//            //nota de debito IndustrialMV 345
//            plantillaDocumentoTipo99(data, documentoTipo);
//            break;
//        case 102:
//            //nota de debito IndustrialMV 227
//            plantillaDocumentoTipo102(data, documentoTipo);
//            break;
//        case 23:
//            //Cotización de venta JR
//            plantillaDocumentoTipo23(data, documentoTipo);
//            break;
//        case 208:
//            //Cotización de venta 
//            plantillaDocumentoTipo208(data, documentoTipo);
//            break;
//        case 209:
//            //Cotización de venta 
//            plantillaDocumentoTipo209(data, documentoTipo);
//            break;
//            
//        case 53:
//            //nota de entrada  = internamiento
//            plantillaDocumentoTipo53(data, documentoTipo);
//            break;
//        case 54:
//            //nota de salida  = devolucion
//            plantillaDocumentoTipo54(data, documentoTipo);
//            break;
//        case 55:
//            //nota de salida  = distribucion
//            plantillaDocumentoTipo55(data, documentoTipo);
//            break;
//        case 56:
//            //inventario
//            plantillaDocumentoTipo56(data, documentoTipo);
//            break;
//        case 57:
//            //nota de salida comprometido
//            plantillaDocumentoTipo57(data, documentoTipo);
//            break;
//            // JR
//        case 188:
//            //guia de remision transportista JR
//            plantillaDocumentoTipo188(data, documentoTipo);
//            break;
//        case 212:
//            //guia de remision transportista 
//            plantillaDocumentoTipo212(data, documentoTipo);
//            break;
//        case 213:
//            //guia de remision transportista 
//            plantillaDocumentoTipo213(data, documentoTipo);
//            break;
//        default:
//            plantillaDocumentoTipoEstandar(data, documentoTipo);
////            mostrarAdvertencia("No existe una plantilla para este documento");
//            break;
//    }
    loaderClose();
}

function plantillaDocumentoTipo6(data, documentoTipo)
{
    //console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 545:
                    serieGuia= item.valor;
                    break;
                case 544:
                    $('#cabeceraNumeroPedido').append(item.valor);
                    break;
                case 546:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                case 918:
                    numeroGuia= item.valor;
                    break;
                default:
                    break;
            }
        });
        
        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraFechaVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo78(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 633:
                    serieGuia= item.valor;
                    break;
                case 632:
                    $('#cabeceraNumeroPedido').append(item.valor);
                    break;
                case 634:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                case 927:
                    numeroGuia= item.valor;
                    break;
                default:
                    break;
            }
        });
        
        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraFechaVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo81(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 648:
                    serieGuia= item.valor;
                    break;
                case 647:
                    $('#cabeceraNumeroPedido').append(item.valor);
                    break;
                case 649:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                case 919:
                    numeroGuia= item.valor;
                    break;
                default:
                    break;
            }
        });
        
        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraFechaVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

//NOTA DE CREDITO
function plantillaDocumentoTipo61(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);

        var serieM="";
        var numeroM="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 608:
//                    serieM=item.valor;
////                    $('#celdaSerieModifica').append(item.valor);
//                    break;
//                case 566:
//                    numeroM=item.valor;
////                    $('#celdaNumeroModifica').append(item.valor);
//                    break;
//                case 567:
//                    var fechaModifica = separarFecha(item.valor);                    
//                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
//                    break;
//                case 1710:                    
//                    $('#celdaTipoDocumento').append(item.valor);
//                    break;
                case 609:
                    if(item.valor=="Anulación"){
                        $('#celdaAnulacion').append("x");
                    }
                    if(item.valor=="Bonificaciones"){
                        $('#celdaBonificaciones').append("x");
                    }
                    if(item.valor=="Descuentos"){
                        $('#celdaDescuentos').append("x");
                    }
                    if(item.valor=="Devoluciones"){
                        $('#celdaDevoluciones').append("x");
                    }
                    if(item.valor=="Otros"){
                        $('#celdaOtros').append("x");
                    }
                    
                    break;
                default:
                    break;
            }
        });
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                var fechaModifica = separarFecha(item.fecha_emision);                    
                
                if (item.identificador_negocio == 3) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Boleta');                  
                }
                if (item.identificador_negocio == 4) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Factura');                  
                }
            });
        }        
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo98(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);

        var serieM="";
        var numeroM="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 2021:
//                    serieM=item.valor;
////                    $('#celdaSerieModifica').append(item.valor);
//                    break;
//                case 2022:
//                    numeroM=item.valor;
////                    $('#celdaNumeroModifica').append(item.valor);
//                    break;
//                case 2023:
//                    var fechaModifica = separarFecha(item.valor);                    
//                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
//                    break;
//                case 2020:                    
//                    $('#celdaTipoDocumento').append(item.valor);
//                    break;
                case 2024:
                    if(item.valor=="Anulación"){
                        $('#celdaAnulacion').append("x");
                    }
                    if(item.valor=="Bonificaciones"){
                        $('#celdaBonificaciones').append("x");
                    }
                    if(item.valor=="Descuentos"){
                        $('#celdaDescuentos').append("x");
                    }
                    if(item.valor=="Devoluciones"){
                        $('#celdaDevoluciones').append("x");
                    }
                    if(item.valor=="Otros"){
                        $('#celdaOtros').append("x");
                    }
                    
                    break;
                default:
                    break;
            }
        });         
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                var fechaModifica = separarFecha(item.fecha_emision);                    
                
                if (item.identificador_negocio == 3) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Boleta');                  
                }
                if (item.identificador_negocio == 4) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Factura');                  
                }
            });
        }
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo101(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);

        var serieM="";
        var numeroM="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 2036:
//                    serieM=item.valor;
////                    $('#celdaSerieModifica').append(item.valor);
//                    break;
//                case 2037:
//                    numeroM=item.valor;
////                    $('#celdaNumeroModifica').append(item.valor);
//                    break;
//                case 2038:
//                    var fechaModifica = separarFecha(item.valor);                    
//                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
//                    break;
//                case 2035:                    
//                    $('#celdaTipoDocumento').append(item.valor);
//                    break;
                case 2039:
                    if(item.valor=="Anulación"){
                        $('#celdaAnulacion').append("x");
                    }
                    if(item.valor=="Bonificaciones"){
                        $('#celdaBonificaciones').append("x");
                    }
                    if(item.valor=="Descuentos"){
                        $('#celdaDescuentos').append("x");
                    }
                    if(item.valor=="Devoluciones"){
                        $('#celdaDevoluciones').append("x");
                    }
                    if(item.valor=="Otros"){
                        $('#celdaOtros').append("x");
                    }
                    
                    break;
                default:
                    break;
            }
        });         
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                var fechaModifica = separarFecha(item.fecha_emision);                    
                
                if (item.identificador_negocio == 3) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Boleta');                  
                }
                if (item.identificador_negocio == 4) {
                    $('#celdaSerieNumeroModifica').append(item.serie_numero_original);                                
                    $('#celdaFechaModifica').append(fechaModifica.dia+"/"+fechaModifica.mes+"/"+fechaModifica.anio);
                    $('#celdaTipoDocumento').append('Factura');                  
                }
            });
        }
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

//NOTA DE DEBITO
function plantillaDocumentoTipo62(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDireccion').append(data['dataDocumento'][0].direccion.substring(0, 58));
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
        $('#celdaVendedor').append(data['dataDocumento'][0].usuario);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 1713:
                    $('#celdaCodigo').append(item.valor);
                    break;                
                case 1712:
                    $('#celdaObservacion').append(item.valor);
                    break;                   
                case 1714:
                    $('#celdaPedido').append(item.valor);
                    break;              
                case 1715:
                    $('#celdaReferencia').append(item.valor);
                    break;        
                default:
                    break;
            }
        }); 
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowItem" id="detalleRowItem'+(index+1)+'">'+(index+1)+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo99(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDireccion').append(data['dataDocumento'][0].direccion.substring(0, 58));
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
        $('#celdaVendedor').append(data['dataDocumento'][0].usuario);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2048:
                    $('#celdaCodigo').append(item.valor);
                    break;                
                case 2047:
                    $('#celdaObservacion').append(item.valor);
                    break;                   
                case 2050:
                    $('#celdaPedido').append(item.valor);
                    break;              
                case 2051:
                    $('#celdaReferencia').append(item.valor);
                    break;        
                default:
                    break;
            }
        }); 
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowItem" id="detalleRowItem'+(index+1)+'">'+(index+1)+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo102(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append(data['dataDocumento'][0].nombre);
        $('#celdaDireccion').append(data['dataDocumento'][0].direccion.substring(0, 58));
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);
        $('#celdaVendedor').append(data['dataDocumento'][0].usuario);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append(fechaEmision.dia + " de "+ obtenerMesLetra(fechaEmision.mes)+ " del " + fechaEmision.anio);


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2063:
                    $('#celdaCodigo').append(item.valor);
                    break;                
                case 2062:
                    $('#celdaObservacion').append(item.valor);
                    break;                   
                case 2065:
                    $('#celdaPedido').append(item.valor);
                    break;              
                case 2066:
                    $('#celdaReferencia').append(item.valor);
                    break;        
                default:
                    break;
            }
        }); 
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowItem" id="detalleRowItem'+(index+1)+'">'+(index+1)+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipoCotizacion(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/cotizacionVenta.php', function () {
        //asignamos los valores a los id de la plantilla       
        
        //cabecera empresa
        $('#empresaRazonSocial').append(data['dataEmpresa'][0].razon_social);
        $('#empresaRUC').append("RUC: " + data['dataEmpresa'][0].ruc);
        
        var empresaDireccion=data['dataEmpresa'][0].direccion;
        
        if(empresaDireccion.indexOf("LA LIBERTAD")>=0){
            $('#empresaDireccion1').append(empresaDireccion.substr(0,empresaDireccion.indexOf("LA LIBERTAD") ));
            $('#empresaDireccion2').append(empresaDireccion.substr(empresaDireccion.indexOf("LA LIBERTAD"),empresaDireccion.length ));
        } else {
            $('#empresaDireccion1').append(empresaDireccion);
        }
        
        $('#serieNumero').append("COTIZACIÓN "+data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append("Señores:"+data['dataDocumento'][0].nombre);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append("Fecha:"+fechaEmision.dia + "/"+ fechaEmision.mes+ "/" + fechaEmision.anio);
        
        $('#celdaDescripcion').append(data['dataDocumento'][0].descripcion);   
        
        var filaDatos="";
        var estilo="";
        var txtValor="";
        var txtDescripcion="";
        var altoCelda="condicionesCelda";
        var aprobadoPor="";
        if (!isEmpty(data.documentoDatoValor)) {
            $.each(data.documentoDatoValor, function (index, item) {
                txtDescripcion=item.descripcion;
                
                estilo="";
                if(index==0){
                    estilo="border-top: 1px solid black;";
                }
                if(index==(data.documentoDatoValor.length-1)){                    
                    estilo="border-bottom: 1px solid black;";
                }
                
                if(isEmpty(item.valor)){
                    txtValor="";
                }else{
                    txtValor=item.valor;
                }
                
                // tipos
                if (item.tipo == 1) {
                    txtValor = formatearNumero(txtValor);
                }

                if (item.tipo == 3) {
                    var fechaD = separarFecha(txtValor);
                    txtValor = fechaD.dia + "/" + fechaD.mes + "/" + fechaD.anio;
                }
                
                if(txtValor.length>61){
                    altoCelda="condicionesCelda2";
                }else{
                    altoCelda="condicionesCelda";
                }
                
                if(txtDescripcion=='Plazo de entrega (días)'){
                    txtDescripcion='Plazo de entrega';
                    txtValor=formatearCantidad(txtValor)+ ' días';
                    
                    // para la cuenta
                    if(!isEmpty(data.dataDocumento[0].cuenta_numero)){
                        filaDatos=filaDatos+'<div class="'+altoCelda+'">'
                                        +'<div class="anchoCondiciones1" style=\''+estilo+'\'>Cuenta</div>'
                                        +'<div class="anchoCondiciones2" style=\''+estilo+'\'>'+data.dataDocumento[0].cuenta_numero+'</div>'
                                    +'</div>'; 
                    }
                }
                
                if(txtDescripcion=='Aprobado por, nombre'){    
                    if(!isEmpty(txtValor)){
                        aprobadoPor = txtValor.split("|");
                        aprobadoPor=aprobadoPor[0];
                    }
                    
                    txtDescripcion='Elaborado por';
                    txtValor=data.dataDocumento[0].perfil_usuario.split(" ");
                    txtValor=data.dataDocumento[0].usuario+' | '+txtValor[0];
                }
                
                if(txtDescripcion=='Aprobado por, cargo'){                                        
                    txtDescripcion='Aprobado por';
                    txtValor=aprobadoPor + ' | '+txtValor;
                }
                
                filaDatos=filaDatos+'<div class="'+altoCelda+'">'
                                        +'<div class="anchoCondiciones1" style=\''+estilo+'\'>'+txtDescripcion+'</div>'
                                        +'<div class="anchoCondiciones2" style=\''+estilo+'\'>'+txtValor+'</div>'
                                    +'</div>';
            });
        }else{
            $('#otrosDatosDocumento').hide();
        }        
//        console.log(filaDatos);
        $('#otrosDatosDocumento').append(filaDatos);
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);
        
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));
        
        $('#celdaComentario').append(data['dataDocumento'][0].comentario);

        //llamamos ala funcion imprimir
        imprimirHoja('cotizacionVenta');
    });
}

function plantillaDocumentoTipo208(data, documentoTipo)
{
    //console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla
        
        //cabecera empresa
        $('#empresaRazonSocial').append(data['dataEmpresa'][0].razon_social);
        $('#empresaRUC').append("RUC: " + data['dataEmpresa'][0].ruc);
        
        var empresaDireccion=data['dataEmpresa'][0].direccion;
        
        if(empresaDireccion.indexOf("LA LIBERTAD")>=0){
            $('#empresaDireccion1').append(empresaDireccion.substr(0,empresaDireccion.indexOf("LA LIBERTAD") ));
            $('#empresaDireccion2').append(empresaDireccion.substr(empresaDireccion.indexOf("LA LIBERTAD"),empresaDireccion.length ));
        } else {
            $('#empresaDireccion1').append(empresaDireccion);
        }
        
        $('#serieNumero').append("COTIZACIÓN "+data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append("Señores:"+data['dataDocumento'][0].nombre);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append("Fecha:"+fechaEmision.dia + "/"+ fechaEmision.mes+ "/" + fechaEmision.anio);
        
        $('#celdaDescripcion').append(data['dataDocumento'][0].descripcion);

        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2083:
                    $('#plazoEntrega').append(item.valor);
                    break;
                case 2084:
                    $('#formaPago').append(item.valor);
                    break;
                case 2085:                    
                    $('#garantia').append(item.valor);
                    break;
                case 2086:                    
                    $('#vigCot').append(item.valor);
                    break;
                case 2087:                    
                    $('#cuentaBancaria').append(item.valor);
                    break;
                default:
                    break;
            }
        });
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);
        
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));
        
        $('#celdaComentario').append(data['dataDocumento'][0].comentario);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo209(data, documentoTipo)
{
    //console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla
        
        //cabecera empresa
        $('#empresaRazonSocial').append(data['dataEmpresa'][0].razon_social);
        $('#empresaRUC').append("RUC: " + data['dataEmpresa'][0].ruc);
        
        var empresaDireccion=data['dataEmpresa'][0].direccion;
        
        if(empresaDireccion.indexOf("LA LIBERTAD")>=0){
            $('#empresaDireccion1').append(empresaDireccion.substr(0,empresaDireccion.indexOf("LA LIBERTAD") ));
            $('#empresaDireccion2').append(empresaDireccion.substr(empresaDireccion.indexOf("LA LIBERTAD"),empresaDireccion.length ));
        } else {
            $('#empresaDireccion1').append(empresaDireccion);
        }
        
        $('#serieNumero').append("COTIZACIÓN "+data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append("Señores:"+data['dataDocumento'][0].nombre);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append("Fecha:"+fechaEmision.dia + "/"+ fechaEmision.mes+ "/" + fechaEmision.anio);
        
        $('#celdaDescripcion').append(data['dataDocumento'][0].descripcion);

        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2114:
                    $('#plazoEntrega').append(item.valor);
                    break;
                case 2115:
                    $('#formaPago').append(item.valor);
                    break;
                case 2116:                    
                    $('#garantia').append(item.valor);
                    break;
                case 2117:                    
                    $('#vigCot').append(item.valor);
                    break;
                case 2118:                    
                    $('#cuentaBancaria').append(item.valor);
                    break;
                default:
                    break;
            }
        });
                  
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        $('#detalle').append(fila);
        
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));
        
        $('#celdaComentario').append(data['dataDocumento'][0].comentario);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

//FACTURA DE VENTA
function plantillaDocumentoTipo7(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
       
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraRUC').append(data['dataDocumento'][0].codigo_identificacion);
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);
        
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(2, 2));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 50:
//                    serieGuia= item.valor;
//                    break;
//                case 917:
//                    numeroGuia= item.valor;
//                    break;
                case 1655:
                    $('#cabeceraTotalPiezas').append(formatearNumero(item.valor));
                    break;
                case 1656:
                    $('#cabeceraTotalVolumen').append(formatearNumero(item.valor));
                    break;
                case 1657:
                    $('#cabeceraTotalPeso').append(formatearNumero(item.valor));
                    break;
                case 1652:
                    $('#cabeceraPedido').append(item.valor);
                    break;
//                case 541:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;
                case 542:
                    $('#cabeceraCondicion').append(item.valor);
                    break;
                case 1649:
                    $('#cabeceraObservacion').append(item.valor);
                    break;
                case 1650:
                    $('#cabeceraPartida').append(item.valor);
                    break;
                case 1651:
                    $('#cabeceraLlegada').append(item.valor);
                    break;
                case 1654:
                    $('#cabeceraObservacionMercaderia').append(item.valor);
                    break;
                case 1658:
                    $('#cabeceraVB').append(item.valor);
                    break;
                case 1653://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x"); 
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia/Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 6) {
                    $('#cabeceraGuiaRemitente').append(item.serie_numero_original);
                }
            });
        }
//        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'                
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

//        $('#pieDia').append(fechaEmision.dia);
//        $('#pieMes').append(fechaEmision.mes);
//
//        if (fechaEmision.anio.length === 4)
//        {
//            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
//        }


//        $('#igv').append(data.valorIgv);
        //console.log(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraBruto').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraValorVenta').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#cabeceraTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo82(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
       
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraRUC').append(data['dataDocumento'][0].codigo_identificacion);
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);
        
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(2, 2));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 1807:
//                    serieGuia= item.valor;
//                    break;
//                case 1808:
//                    numeroGuia= item.valor;
//                    break;
                case 1818:
                    $('#cabeceraTotalPiezas').append(formatearNumero(item.valor));
                    break;
                case 1819:
                    $('#cabeceraTotalVolumen').append(formatearNumero(item.valor));
                    break;
                case 1820:
                    $('#cabeceraTotalPeso').append(formatearNumero(item.valor));
                    break;
                case 1804:
                    $('#cabeceraPedido').append(item.valor);
                    break;
//                case 1805:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;
                case 1812:
                    $('#cabeceraCondicion').append(item.valor);
                    break;
                case 1813:
                    $('#cabeceraObservacion').append(item.valor);
                    break;
                case 1814:
                    $('#cabeceraPartida').append(item.valor);
                    break;
                case 1815:
                    $('#cabeceraLlegada').append(item.valor);
                    break;
                case 1817:
                    $('#cabeceraObservacionMercaderia').append(item.valor);
                    break;
                case 1821:
                    $('#cabeceraVB').append(item.valor);
                    break;
                case 1816://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x"); 
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia/Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
                
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 6) {
                    $('#cabeceraGuiaRemitente').append(item.serie_numero_original);
                }
            });
        }
        
//        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'                
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

//        $('#pieDia').append(fechaEmision.dia);
//        $('#pieMes').append(fechaEmision.mes);
//
//        if (fechaEmision.anio.length === 4)
//        {
//            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
//        }


//        $('#igv').append(data.valorIgv);
        //console.log(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraBruto').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraValorVenta').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#cabeceraTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo79(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
       
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraRUC').append(data['dataDocumento'][0].codigo_identificacion);
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);
        
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(2, 2));

        var serieGuia="";
        var numeroGuia="";
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
//                case 2318:
//                    serieGuia= item.valor;
//                    break;
//                case 2319:
//                    numeroGuia= item.valor;
//                    break;
                case 2329:
                    $('#cabeceraTotalPiezas').append(formatearNumero(item.valor));
                    break;
                case 2330:
                    $('#cabeceraTotalVolumen').append(formatearNumero(item.valor));
                    break;
                case 2331:
                    $('#cabeceraTotalPeso').append(formatearNumero(item.valor));
                    break;
                case 2315:
                    $('#cabeceraPedido').append(item.valor);
                    break;
//                case 2316:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;
                case 2323:
                    $('#cabeceraCondicion').append(item.valor);
                    break;
                case 2324:
                    $('#cabeceraObservacion').append(item.valor);
                    break;
                case 2325:
                    $('#cabeceraPartida').append(item.valor);
                    break;
                case 2326:
                    $('#cabeceraLlegada').append(item.valor);
                    break;
                case 2328:
                    $('#cabeceraObservacionMercaderia').append(item.valor);
                    break;
                case 2332:
                    $('#cabeceraVB').append(item.valor);
                    break;
                case 2327://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x"); 
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia/Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 6) {
                    $('#cabeceraGuiaRemitente').append(item.serie_numero_original);
                }
            });
        }
        
//        $('#cabeceraGuiaRemitente').append(serieGuia+' - '+numeroGuia);
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'                
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowDescuento" id="detalleRowDescuento'+(index+1)+'"></div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

//        $('#pieDia').append(fechaEmision.dia);
//        $('#pieMes').append(fechaEmision.mes);
//
//        if (fechaEmision.anio.length === 4)
//        {
//            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
//        }


//        $('#igv').append(data.valorIgv);
        //console.log(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraBruto').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraValorVenta').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#cabeceraIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#cabeceraTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo85(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 706:
                    $('#cabeceraGuiaRemitente').append(item.valor);
                    break;
                case 713:
                    $('#cabeceraOrdenCompra').append(item.valor);
                    break;
                case 714:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                default:
                    break;
            }
        });
        
        
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraFechaVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
          
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }


        $('#igv').append(data.valorIgv);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo21(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

//        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 607:
                    $('#cabeceraTiempoEntrega').append(item.valor);
                    break;
                case 606:
                    $('#cabeceraValidezOferta').append(item.valor);
                    break;
                case 550:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                default:
                    break;
            }
        });
        
        
        $('#cabeceraFechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        $('#cabeceraFechaVencimiento').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);
//            
//        $('#cabeceraGuiaRemitente').append("");
//        $('#cabeceraGuiaTransportista').append("");
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
           /* 
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowPU' + (index + 1)).append(formatearNumero(item['precioUnitario']));
            $('#detalleRowImporte' + (index + 1)).append(formatearNumero(item['importe']));*/
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }


        $('#igv').append(data.valorIgv);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo63(data, documentoTipo)
{
    //console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

//        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);
                
        $('#cabeceraUnidadMonetaria').append(data['dataDocumento'][0].moneda_descripcion);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        //var fechaVencimiento = separarFecha(data['dataDocumento'][0].fecha_vencimiento);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio.substr(3, 1));


        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 612:
                    $('#cabeceraTiempoEntrega').append(item.valor);
                    break;
                case 611:
                    $('#cabeceraValidezOferta').append(item.valor);
                    break;
                case 590:
                    $('#cabeceraCondiciones').append(item.valor);
                    break;
                case 599:
                    $('#cabeceraTelefono').append(item.valor);
                    break;
                case 598:
                    $('#cabeceraContacto').append(item.valor);
                    break;
                default:
                    break;
            }
        });
        
        
        //$('#cabeceraTelefono').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio);
        //$('#cabeceraContacto').append(fechaVencimiento.dia+"/"+fechaVencimiento.mes+"/"+fechaVencimiento.anio);

        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '</div>'
            ;
           /* 
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowPU' + (index + 1)).append(formatearNumero(item['precioUnitario']));
            $('#detalleRowImporte' + (index + 1)).append(formatearNumero(item['importe']));*/
        });
        //console.log(fila);
        $('#detalle').append(fila);

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

//GUIA DE REMISION VENTA
function plantillaDocumentoTipo12(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#emisionDia').append(fechaEmision.dia);
        $('#emisionMes').append(fechaEmision.mes);
        $('#emisionAnio').append(fechaEmision.anio.substr(2, 2));   
        
        //destinatario
        $('#celdaNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        $('#celdaDireccion').append(data['dataDocumento'][0].persona_direccion);       
        
        $('#puntoLlegada').append(data['dataDocumento'][0].direccion);
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);       
             
        var marca="";
        var placa="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 1663:
                    $('#celdaCodigo').append(item.valor);
                    break;
                case 70:
//                    var ind=item.valor.indexOf('|');
//                    $('#celdaNombreTransportista').append(item.valor.substr(0, ind));
//                    $('#celdaCodigoIdentificacionTransportista').append(item.valor.substr(ind+1,item.valor.length));
                      
                    $('#celdaNombreTransportista').append(item.valor);
                    break;
                case 131:
                    $('#celdaCodigoIdentificacionTransportista').append(item.valor);
                    break;
                case 68:
                    placa=item.valor;
//                    $('#celdaPlaca').append(item.valor);
                    break;
                case 66:
                    marca=item.valor;
//                    $('#celdaMarca').append(item.valor);
                    break;
                case 67:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 69:
                    $('#celdaLicenciaConducir').append(item.valor);
                    break;
                case 52:
                    $('#puntoPartida').append(item.valor);
                    break;   
//                case 53:
//                    $('#puntoLlegada').append(item.valor);
//                    break;
//                case 1659:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;                    
                case 1660:
                    $('#cabeceraNumPedido').append(item.valor);
                    break;
//                case 1661:
//                    $('#cabeceraNumFactura').append(item.valor);
//                    break;
                case 65:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#trasladoDia').append(fechaTraslado.dia);
                    $('#trasladoMes').append(fechaTraslado.mes);
                    $('#trasladoAnio').append(fechaTraslado.anio.substr(2, 2)); 
                    //$('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                    break;
                case 855://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x");
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia / Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
        
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 4) {
                    $('#cabeceraNumFactura').append(item.serie_numero_original);
                }
            });
        }
            
//        $('#comprabantePago').append(tipoComprobante+" "+serieComprobante+" - "+numeroComprobante);
        $('#celdaPlacaMarca').append(placa+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+marca);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+formatearCantidad(item['bien_codigo'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'               
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo77(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#emisionDia').append(fechaEmision.dia);
        $('#emisionMes').append(fechaEmision.mes);
        $('#emisionAnio').append(fechaEmision.anio.substr(2, 2));   
        
        //destinatario
        $('#celdaNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        $('#celdaDireccion').append(data['dataDocumento'][0].persona_direccion);       
        
        $('#puntoLlegada').append(data['dataDocumento'][0].direccion);        
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);       
             
        var marca="";
        var placa="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2190:
                    $('#celdaCodigo').append(item.valor);
                    break;
                case 2192:
//                    var ind=item.valor.indexOf('|');
                    $('#celdaNombreTransportista').append(item.valor);
//                    $('#celdaCodigoIdentificacionTransportista').append(item.valor.substr(ind+1,item.valor.length));
                    break;
                case 2193:
                    $('#celdaCodigoIdentificacionTransportista').append(item.valor);
                    break;
                case 2194:
                    placa=item.valor;
//                    $('#celdaPlaca').append(item.valor);
                    break;
                case 2195:
                    marca=item.valor;
//                    $('#celdaMarca').append(item.valor);
                    break;
                case 2196:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 2197:
                    $('#celdaLicenciaConducir').append(item.valor);
                    break;
                case 2199:
                    $('#puntoPartida').append(item.valor);
                    break;   
//                case 2200:
//                    $('#puntoLlegada').append(item.valor);
//                    break;
//                case 2201:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;                    
                case 2202:
                    $('#cabeceraNumPedido').append(item.valor);
                    break;
//                case 2203:
//                    $('#cabeceraNumFactura').append(item.valor);
//                    break;
                case 2189:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#trasladoDia').append(fechaTraslado.dia);
                    $('#trasladoMes').append(fechaTraslado.mes);
                    $('#trasladoAnio').append(fechaTraslado.anio.substr(2, 2)); 
                    //$('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                    break;
                case 2198://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x");
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia / Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 4) {
                    $('#cabeceraNumFactura').append(item.serie_numero_original);
                }
            });
        }
                
//        $('#comprabantePago').append(tipoComprobante+" "+serieComprobante+" - "+numeroComprobante);
        $('#celdaPlacaMarca').append(placa+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+marca);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+formatearCantidad(item['bien_codigo'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'               
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo80(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#emisionDia').append(fechaEmision.dia);
        $('#emisionMes').append(fechaEmision.mes);
        $('#emisionAnio').append(fechaEmision.anio.substr(2, 2));   
        
        //destinatario
        $('#celdaNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        $('#celdaDireccion').append(data['dataDocumento'][0].persona_direccion);       
        
        $('#puntoLlegada').append(data['dataDocumento'][0].direccion);        
        $('#cabeceraVendedor').append(data['dataDocumento'][0].usuario);       
             
        var marca="";
        var placa="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 2159:
                    $('#celdaCodigo').append(item.valor);
                    break;
                case 2161:
//                    var ind=item.valor.indexOf('|');
                    $('#celdaNombreTransportista').append(item.valor);
//                    $('#celdaCodigoIdentificacionTransportista').append(item.valor.substr(ind+1,item.valor.length));
                    break;
                case 2162:
                    $('#celdaCodigoIdentificacionTransportista').append(item.valor);
                    break;
                case 2163:
                    placa=item.valor;
//                    $('#celdaPlaca').append(item.valor);
                    break;
                case 2164:
                    marca=item.valor;
//                    $('#celdaMarca').append(item.valor);
                    break;
                case 2165:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 2166:
                    $('#celdaLicenciaConducir').append(item.valor);
                    break;
                case 2168:
                    $('#puntoPartida').append(item.valor);
                    break;   
//                case 2169:
//                    $('#puntoLlegada').append(item.valor);
//                    break;
//                case 2170:
//                    $('#cabeceraOrdenCompra').append(item.valor);
//                    break;                    
                case 2171:
                    $('#cabeceraNumPedido').append(item.valor);
                    break;
//                case 2172:
//                    $('#cabeceraNumFactura').append(item.valor);
//                    break;
                case 2158:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#trasladoDia').append(fechaTraslado.dia);
                    $('#trasladoMes').append(fechaTraslado.mes);
                    $('#trasladoAnio').append(fechaTraslado.anio.substr(2, 2)); 
                    //$('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                    break;
                case 2167://motivo de traslado  
                    switch (item.valor) {
                        case "Compra-Venta":   $('#celdaCompraVenta').append("x");
                            break;
                        case "Transformación":   $('#celdaTransformacion').append("x");
                            break;
                        case "Consignación":   $('#celdaConsignacion').append("x");
                            break;
                        case "Transferencia / Filial":   $('#celdaTransferencia').append("x");
                            break;
                        case "Emisor Itinerante":   $('#celdaEmisor').append("x");
                            break;
                        case "Otros":   $('#celdaOtros').append("x");
                            break;
                        default:
                            break;                            
                    }                    
                    break;
                default:
                    break;
            }
        });
        
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 2) {
                    $('#cabeceraOrdenCompra').append(item.serie_numero_original);
                }
                if (item.identificador_negocio == 4) {
                    $('#cabeceraNumFactura').append(item.serie_numero_original);
                }
            });
        }
                
//        $('#comprabantePago').append(tipoComprobante+" "+serieComprobante+" - "+numeroComprobante);
        $('#celdaPlacaMarca').append(placa+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+marca);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+formatearCantidad(item['bien_codigo'])+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'               
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo83(data, documentoTipo)
{
    //console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#emisionDia').append(fechaEmision.dia);
        $('#emisionMes').append(fechaEmision.mes);
        $('#emisionAnio').append(fechaEmision.anio);   
        
        //destinatario
        $('#celdaNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#celdaDocumento').append(data['dataDocumento'][0].persona_documento_tipo + ": "+data['dataDocumento'][0].codigo_identificacion);       
        
        var tipoComprobante="";
        var serieComprobante="";
        var numeroComprobante="";
        var marca="";
        var placa="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 777:
                    $('#puntoPartida').append(item.valor);
                    break;
                case 778:
                    $('#puntoLlegada').append(item.valor);
                    break;
                case 780://tipo comprobante
                    tipoComprobante=item.valor;                    
                    break;
                case 783:
                    serieComprobante=item.valor;
                    break;
                case 784:
                    numeroComprobante=item.valor;
                    break;
                case 849:
                    $('#costoMinimo').append(item.valor);
                    break;
                case 786:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#trasladoDia').append(fechaTraslado.dia);
                    $('#trasladoMes').append(fechaTraslado.mes);
                    $('#trasladoAnio').append(fechaTraslado.anio); 
                    //$('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                    break;
                case 787:
                    marca=item.valor;
                    break;
                case 788:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 789:
                    placa=item.valor;
                    break;
                case 790:
                    $('#celdaLicenciaConducir').append(item.valor);
                    break;
                case 791:
                    $('#celdaNombreTransportista').append(item.valor);
                    break;
                case 792:
                    $('#celdaCodigoIdentificacionTransportista').append(item.valor);
                    break;
                default:
                    break;
            }
        });
                
        $('#comprabantePago').append(tipoComprobante+" "+serieComprobante+" - "+numeroComprobante);
        $('#celdaMarcaPlaca').append(marca+" - "+placa);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+formatearCantidad(item['bien_codigo'])+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowPrecioUnitario" id="detalleRowPrecioUnitario'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                + '<div class="detalleRowPrecioTotal" id="detalleRowPrecioTotal'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo13(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);

        if (data['dataDocumento'][0].persona_tipo_id == 2)
        {
            $('#dni').append(data['dataDocumento'][0].codigo_identificacion);
        }
        else
        {
            $('#ruc').append(data['dataDocumento'][0].codigo_identificacion);
        }

        $('#fechaEmision').append(formatearFechaJS(data['dataDocumento'][0].fecha_emision));

        // datos extra de la guia de remisión    
        $.each(data.documentoDatoValor, function (index, item) {

            switch (parseInt(item.documento_tipo_id)) {
                case 74:
                    $('#numeroBoleta').append(item.valor);
                    break;
                case 77:
                    $('#puntoPartida').append(item.valor);
                    break;
                case 78:
                    $('#puntoLlegada').append(item.valor);
                    break;
                case 75:
                    $('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                    break;
                case 80:
                    $('#marcaUnidadTransporte').append(item.valor);
                    break;
                case 81:
                    $('#placa').append(item.valor);
                    break;
                case 82:
                    $('#licenciaConducir').append(item.valor);
                    break;
                case 79:
                    $('#nombreTrasnportista').append(item.valor);
                    break;
                case 132:
                    $('#codigoIdentificacionTransportista').append(item.valor);
                    break;
                default:
                    break;
            }
        });

        $.each(data.detalle, function (index, item) {
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowUM' + (index + 1)).append(item['unidadMedida']);
        });

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo14(data, documentoTipo)
{
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(fechaEmision.mes);
        $('#cabeceraAnio').append(fechaEmision.anio);

        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);


        $.each(data.detalle, function (index, item) {
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowPU' + (index + 1)).append(formatearNumero(item['precioUnitario']));
            $('#detalleRowImporte' + (index + 1)).append(formatearNumero(item['importe']));
        });

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo15(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(obtenerMesLetra(fechaEmision.mes));
        $('#cabeceraAnio').append(fechaEmision.anio);
//        
        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);

        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {
                case 181:
                    $('#cabeceraGuiaRemitente').append(item.valor);
                    break;
                case 182:
                    $('#cabeceraGuiaTransportista').append(item.valor);
                    break;
                default:
                    break;
            }
        });

        $.each(data.detalle, function (index, item) {
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowPU' + (index + 1)).append(formatearNumero(item['precioUnitario']));
            $('#detalleRowImporte' + (index + 1)).append(formatearNumero(item['importe']));
        });

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieDia').append(fechaEmision.dia);
        $('#pieMes').append(fechaEmision.mes);

        if (fechaEmision.anio.length === 4)
        {
            $('#pieAnio').append(fechaEmision.anio.substr(2, 2));
        }


        $('#igv').append(data.valorIgv);

        $('#pieImporteSubtotal').append(formatearNumero(data['dataDocumento'][0].subtotal));
        $('#pieImporteIGV').append(formatearNumero(data['dataDocumento'][0].igv));
        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));
        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo16(data, documentoTipo)
{
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        $('#fechaEmision').append(formatearFechaJS(data['dataDocumento'][0].fecha_emision));


        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);

        if (data['dataDocumento'][0].persona_tipo_id == 2)
        {
            $('#dni').append(data['dataDocumento'][0].codigo_identificacion);
        }
        else
        {
            $('#ruc').append(data['dataDocumento'][0].codigo_identificacion);
        }

        // datos extra de la guia de remisión
        if (!isEmpty(data.documentoDatoValor))
        {
            $.each(data.documentoDatoValor, function (index, item) {

                switch (parseInt(item.documento_tipo_id)) {
                    case 101:
                        $('#numeroBoleta').append(item.valor);
                        break;
                    case 104:
                        $('#puntoPartida').append(item.valor);
                        break;
                    case 105:
                        $('#puntoLlegada').append(item.valor);
                        break;
                    case 102:
                        $('#fechaInicioTranslado').append(formatearFechaJS(item.valor));
                        break;
                    case 107:
                        $('#marcaUnidadTransporte').append(item.valor);
                        break;
                    case 108:
                        $('#placa').append(item.valor);
                        break;
                    case 109:
                        $('#licenciaConducir').append(item.valor);
                        break;
                    case 106:
                        $('#nombreTrasnportista').append(item.valor);
                        break;
                    case 133:
                        $('#codigoIdentificacionTransportista').append(item.valor);
                        break;
                    default:
                        break;
                }
            });
        }

        if (!isEmpty(data.detalle))
        {
            $.each(data.detalle, function (index, item) {
                $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
                $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
                $('#detalleRowUM' + (index + 1)).append(item['unidadMedida']);
            });
        }
        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo17(data, documentoTipo)
{
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#cabeceraDia').append(fechaEmision.dia);
        $('#cabeceraMes').append(fechaEmision.mes);
        $('#cabeceraAnio').append(fechaEmision.anio);

        $('#cabeceraNombre').append(data['dataDocumento'][0].nombre);
        $('#cabeceraDireccion').append(data['dataDocumento'][0].direccion);
        $('#cabeceraCodigo').append(data['dataDocumento'][0].codigo_identificacion);


        $.each(data.detalle, function (index, item) {
            $('#detalleRowCantidad' + (index + 1)).append(formatearCantidad(item['cantidad']));
            $('#detalleRowDescripcion' + (index + 1)).append(item['descripcion']);
            $('#detalleRowPU' + (index + 1)).append(formatearNumero(item['precioUnitario']));
            $('#detalleRowImporte' + (index + 1)).append(formatearNumero(item['importe']));
        });

        $('#pieTotalTexto').append(data.totalEnTexto);

        $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));

        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo53(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#numeroInternamiento').append(data['dataDocumento'][0].numero);

        $('#nombrePersonaInternamiento').append(data['dataDocumento'][0].nombre);
        $('#direccionPersonaInternamiento').append(data['dataDocumento'][0].direccion);
        $('#codigoIdentificacionPersonaInternamiento').append(data['dataDocumento'][0].codigo_identificacion);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaInternamiento').append(fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);

        cargarDetalleDocumentoIgual(data);


        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo18(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla
        $("#numero-letra").text(data.numero);
        $("#referencia-letra").text(data.dinamicos[1].valor);
        $("#fecha-letra").text(data.fecha_emision);
        $("#lugar-letra").text(data.dinamicos[2].valor);
        $("#fechaven-letra").text(data.fecha_vencimiento);
        $("#importe-letra").text(data.importe_formateado);
//        $("#orden-letra").text("industrial milciades vargas S.R.L");
        $("#importe-letras-letra").text(data.enLetras);
        $("#aceptante-letra").text(data.persona_nombre_completo);
        $("#domicilio-letra").text(data.dinamicos[0].valor);
        $("#ruc-letra").text(data.persona_ruc);
        $("#telefono-letra").text(data.persona_telefono);
        $("#doi-letra").text("17839992");

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo54(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#numeroDevolucion').append(data['dataDocumento'][0].numero);

        $('#nombrePersonaDevolucion').append(data['dataDocumento'][0].nombre);
        $('#direccionPersonaDevolucion').append(data['dataDocumento'][0].direccion);
        $('#codigoIdentificacionPersonaDevolucion').append(data['dataDocumento'][0].codigo_identificacion);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaDevolucion').append(fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);

        cargarDetalleDocumentoIgual(data);


        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo55(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#numeroDistribucion').append(data['dataDocumento'][0].numero);

        $('#nombrePersonaDistribucion').append(data['dataDocumento'][0].nombre);
        $('#direccionPersonaDistribucion').append(data['dataDocumento'][0].direccion);
        $('#codigoIdentificacionPersonaDistribucion').append(data['dataDocumento'][0].codigo_identificacion);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaDistribucion').append(fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);

        cargarDetalleDocumentoIgual(data);

        if (!isEmpty(data.documentoDatoValor))
        {
            $.each(data.documentoDatoValor, function (index, item) {

                switch (parseInt(item.documento_tipo_id)) {
                    case 497:
                        $('#motivoDistribucion').append(item.valor);
                        break;
                    default:
                        break;
                }
            });
        }
        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo56(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#numeroInventario').append(data['dataDocumento'][0].numero);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaInventario').append(fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);

        cargarDetalleDocumentoIgual(data);

        if (!isEmpty(data.documentoDatoValor))
        {
            $.each(data.documentoDatoValor, function (index, item) {

                switch (parseInt(item.documento_tipo_id)) {
                    case 505:
                        $('#tipoInventario').append(item.valor);
                        break;
                    default:
                        break;
                }
            });
        }
        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function plantillaDocumentoTipo57(data, documentoTipo)
{

    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#numeroCompromiso').append(data['dataDocumento'][0].numero);

        $('#nombrePersonaCompromiso').append(data['dataDocumento'][0].nombre);
        $('#direccionPersonaCompromiso').append(data['dataDocumento'][0].direccion);
        $('#codigoIdentificacionPersonaCompromiso').append(data['dataDocumento'][0].codigo_identificacion);

        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaEmisionCompromiso').append(fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);
        
        var fechaTentativa = separarFecha(data['dataDocumento'][0].fecha_tentativa);
        $('#fechaTentativaCompromiso').append(fechaTentativa.dia + "/" + fechaTentativa.mes + "/" + fechaTentativa.anio);
        
        cargarDetalleDocumentoIgual(data);
        //llamamos ala funcion imprimir
        imprimirHoja(data.documentoTipoId);
    });
}

function cargarDetalleDocumentoIgual(data)
{
    var html = "";

    $.each(data.detalle, function (index, item) {

        html += '<tr>';
        html += '<td id="cantidad" >' + formatearCantidad(item['cantidad']) + '</td>';
        html += '<td id="descripcion">' + item['descripcion'] + '</td>';
        html += '<td id="precioUnitario">' + formatearNumero(item['precioUnitario']) + '</td>';
        html += '<td id="subTotal">' + formatearNumero(item['importe']) + '</td>';
        html += '</tr>';
    });

    $('#tablaContenido').append(html);

    $('#totalEnTexto').append(data.totalEnTexto);
    $('#totalValor').append(formatearNumero(data['dataDocumento'][0].total));
}

function imprimirHoja(documentoTipo)
{   
//    console.log(documentoTipo);
    $('#dataImprimir').print({
        globalStyles: true,
        mediaPrint: true,
        stylesheet: URL_BASE + '/vistas/com/movimiento/plantillas/' + documentoTipo + '.css'
    });

    if (banderaVolver == 1)
    {
        cargarPantallaListar();
    }
}

function separarFecha(fecha) {
    var fechaNueva = {dia: 0, mes: 0, anio: 0};

    var fechaPartes = fecha.split("-");

    if (!isEmpty(fechaPartes[0]) && !isEmpty(fechaPartes[1]) && !isEmpty(fechaPartes[2])) {

//        if (fechaPartes[0].length === 4) {
//            fechaNueva.anio = fechaPartes[0].substr(-2, 2);
//        }
        fechaNueva.anio = fechaPartes[0];

        fechaNueva.mes = fechaPartes[1];

        if (fechaPartes[2].length >= 2) {
            fechaNueva.dia = fechaPartes[2].substr(0, 2);
        }
    }
    return fechaNueva;
}

function obtenerMesLetra(mes) {

    var mesLetra = "";
    switch (parseInt(mes)) {
        case 1:
            mesLetra = "Enero";
            break;
        case 2:
            mesLetra = "Febrero";
            break;
        case 3:
            mesLetra = "Marzo";
            break;
        case 4:
            mesLetra = "Abril";
            break;
        case 5:
            mesLetra = "Mayo";
            break;
        case 6:
            mesLetra = "Junio";
            break;
        case 7:
            mesLetra = "Julio";
            break;
        case 8:
            mesLetra = "Agosto";
            break;
        case 9:
            mesLetra = "Setiembre";
            break;
        case 10:
            mesLetra = "Octubre";
            break;
        case 11:
            mesLetra = "Noviembre";
            break;
        case 12:
            mesLetra = "Diciembre";
            break;
        default:
            break;
    }

    return mesLetra;
}

// GUIA DE REMISION TRANSPORTISTA
function plantillaDocumentoTipo188(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio.substr(2, 2));
                
        //destinatario
        $('#destinatarioNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#destinatarioDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        
        var serieComprobante="";
        var numeroComprobante="";
        var marca="";
        var placa="";
        var licenciaConducir="";
        var configVehiculo="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {                
                case 1705:
                    $('#subContratacionUnidades').append(formatearCantidad(item.valor));
                    break;
//                case 1677:
//                    serieComprobante=item.valor;
//                    break;
//                case 1678:
//                    numeroComprobante=item.valor;
//                    break;                    
                case 1671:
                    $('#puntoPartida').append(item.valor);
                    break;           
                case 1688:
                    $('#puntoPartidaDist').append(item.valor);
                    break;
                case 1689:
                    $('#puntoPartidaProv').append(item.valor);
                    break;
                case 1690:
                    $('#puntoPartidaDpto').append(item.valor);
                    break;
                case 1672:
                    $('#puntoLlegada').append(item.valor);
                    break;     
                case 1691:
                    $('#puntoLlegadaDist').append(item.valor);
                    break;
                case 1692:
                    $('#puntoLlegadaProv').append(item.valor);
                    break;
                case 1693:
                    $('#puntoLlegadaDpto').append(item.valor);
                    break; 
                case 1702:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#remitenteNombreRazon').append(item.valor.substr(0, ind));
                        $('#remitenteDocumento').append(item.valor.substr(ind+1,item.valor.length));                    
                    }
                    break; 
//                case 1703:
//                    $('#remitenteDocumento').append(item.valor);
//                    break; 
                case 1685:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#celdaNombreTransportista').append(item.valor.substr(0, ind));
                    }
                    break; 
                case 1681:
                    marca=item.valor;
                    break;
                case 1683:
                    placa=item.valor;
                    break;
                case 1682:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 1684:
                    licenciaConducir=item.valor;
                    break;
                case 1704:
//                    $('#celdaConfiguracionVehiculo').append(item.valor);
                    configVehiculo=item.valor;
                    break;
//                case 1706:
//                    $('#subContratacionDocumento').append(item.valor);
//                    break;
                case 1707:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#subContratacionNombreRazon').append(item.valor.substr(0, ind));
                        $('#subContratacionDocumento').append(item.valor.substr(ind+1,item.valor.length));   
                    }
                    break;
//                case 1708:
//                    $('#empresaPagaDocumento').append(item.valor);
//                    break;
                case 1709:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#empresaPagaNombreRazon').append(item.valor.substr(0, ind));
                        $('#empresaPagaDocumento').append(item.valor.substr(ind+1,item.valor.length));   
                    }
                    break;                    
                case 1680:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#fechaTraslado').append(fechaTraslado.dia+"/"+fechaTraslado.mes+"/"+fechaTraslado.anio.substr(2, 2));
                    break;
                default:
                    break;
            }
        });
                
//        $('#comprabantePago').append(serieComprobante+" - "+numeroComprobante);
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 6) {
                    $('#serieComprobante').append(item.serie_numero_original);
                }
            });
        }

        $('#celdaMarcaPlaca').append(marca+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+placa);
        $('#celdaLicenciaConducir').append(licenciaConducir+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+configVehiculo);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo212(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio.substr(2, 2));
                
        //destinatario
        $('#destinatarioNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#destinatarioDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        
        var serieComprobante="";
        var numeroComprobante="";
        var marca="";
        var placa="";
        var licenciaConducir="";
        var configVehiculo="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {                
                case 2240:
                    $('#subContratacionUnidades').append(formatearCantidad(item.valor));
                    break;
//                case 2221:
//                    serieComprobante=item.valor;
//                    break;
//                case 2222:
//                    numeroComprobante=item.valor;
//                    break;                    
                case 2223:
                    $('#puntoPartida').append(item.valor);
                    break;           
                case 2224:
                    $('#puntoPartidaDist').append(item.valor);
                    break;
                case 2225:
                    $('#puntoPartidaProv').append(item.valor);
                    break;
                case 2226:
                    $('#puntoPartidaDpto').append(item.valor);
                    break;
                case 2227:
                    $('#puntoLlegada').append(item.valor);
                    break;     
                case 2228:
                    $('#puntoLlegadaDist').append(item.valor);
                    break;
                case 2229:
                    $('#puntoLlegadaProv').append(item.valor);
                    break;
                case 2230:
                    $('#puntoLlegadaDpto').append(item.valor);
                    break; 
                case 2231:     
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#remitenteNombreRazon').append(item.valor.substr(0, ind));
                        $('#remitenteDocumento').append(item.valor.substr(ind + 1, item.valor.length));
                    }
                    break; 
//                case 2232:
//                    $('#remitenteDocumento').append(item.valor);
//                    break; 
                case 2234:
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#celdaNombreTransportista').append(item.valor.substr(0, ind));
                    }
                    break; 
                case 2235:
                    marca=item.valor;
                    break;
                case 2236:
                    placa=item.valor;
                    break;
                case 2237:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 2238:
                    licenciaConducir=item.valor;
                    break;
                case 2239:
//                    $('#celdaConfiguracionVehiculo').append(item.valor);
                    configVehiculo=item.valor;
                    break;
//                case 2241:
//                    $('#subContratacionDocumento').append(item.valor);
//                    break;
                case 2242:
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#subContratacionNombreRazon').append(item.valor.substr(0, ind));
                        $('#subContratacionDocumento').append(item.valor.substr(ind + 1, item.valor.length));
                    }
                    break;
//                case 2243:
//                    $('#empresaPagaDocumento').append(item.valor);
//                    break;
                case 2244:
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#empresaPagaNombreRazon').append(item.valor.substr(0, ind));
                        $('#empresaPagaDocumento').append(item.valor.substr(ind + 1, item.valor.length));
                    }
                    break;                    
                case 2220:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#fechaTraslado').append(fechaTraslado.dia+"/"+fechaTraslado.mes+"/"+fechaTraslado.anio.substr(2, 2));
                    break;
                default:
                    break;
            }
        });
                
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 6) {
                    $('#comprabantePago').append(item.serie_numero_original);
                }
            });
        }

                
//        $('#comprabantePago').append(serieComprobante+" - "+numeroComprobante);
        $('#celdaMarcaPlaca').append(marca+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+placa);
        $('#celdaLicenciaConducir').append(licenciaConducir+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+configVehiculo);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

function plantillaDocumentoTipo213(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/' + documentoTipo + '.php', function () {
        //asignamos los valores a los id de la plantilla

        $('#serieNumero').append(data['dataDocumento'][0].serie + " - " + data['dataDocumento'][0].numero);
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#fechaEmision').append(fechaEmision.dia+"/"+fechaEmision.mes+"/"+fechaEmision.anio.substr(2, 2));
                
        //destinatario
        $('#destinatarioNombreRazon').append(data['dataDocumento'][0].nombre);
        $('#destinatarioDocumento').append(data['dataDocumento'][0].codigo_identificacion);       
        
        var serieComprobante="";
        var numeroComprobante="";
        var marca="";
        var placa="";
        var licenciaConducir="";
        var configVehiculo="";
        // datos extra de la guia de remisión        
        $.each(data.documentoDatoValor, function (index, item) {
            switch (parseInt(item.documento_tipo_id)) {                
                case 2271:
                    $('#subContratacionUnidades').append(formatearCantidad(item.valor));
                    break;
//                case 2252:
//                    serieComprobante=item.valor;
//                    break;
//                case 2253:
//                    numeroComprobante=item.valor;
//                    break;                    
                case 2254:
                    $('#puntoPartida').append(item.valor);
                    break;           
                case 2255:
                    $('#puntoPartidaDist').append(item.valor);
                    break;
                case 2256:
                    $('#puntoPartidaProv').append(item.valor);
                    break;
                case 2257:
                    $('#puntoPartidaDpto').append(item.valor);
                    break;
                case 2258:
                    $('#puntoLlegada').append(item.valor);
                    break;     
                case 2259:
                    $('#puntoLlegadaDist').append(item.valor);
                    break;
                case 2260:
                    $('#puntoLlegadaProv').append(item.valor);
                    break;
                case 2261:
                    $('#puntoLlegadaDpto').append(item.valor);
                    break; 
                case 2262:
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#remitenteNombreRazon').append(item.valor.substr(0, ind));
                        $('#remitenteDocumento').append(item.valor.substr(ind + 1, item.valor.length));
                    }
                    break; 
//                case 2263:
//                    $('#remitenteDocumento').append(item.valor);
//                    break; 
                case 2265:
                    if (!isEmpty(item.valor)) {
                        var ind = item.valor.indexOf('|');
                        $('#celdaNombreTransportista').append(item.valor.substr(0, ind));
                    }
                    break; 
                case 2266:
                    marca=item.valor;
                    break;
                case 2267:
                    placa=item.valor;
                    break;
                case 2268:
                    $('#celdaConstanciaInscripcion').append(item.valor);
                    break;
                case 2269:
                    licenciaConducir=item.valor;
                    break;
                case 2270:
//                    $('#celdaConfiguracionVehiculo').append(item.valor);
                    configVehiculo=item.valor;
                    break;
//                case 2272:
//                    $('#subContratacionDocumento').append(item.valor);
//                    break;
                case 2273:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#subContratacionNombreRazon').append(item.valor.substr(0, ind));
                        $('#subContratacionDocumento').append(item.valor.substr(ind+1,item.valor.length));
                    }
                    break;
                case 2274:
                    $('#empresaPagaDocumento').append(item.valor);
                    break;
                case 2275:
                    if (!isEmpty(item.valor)) {
                        var ind=item.valor.indexOf('|');
                        $('#empresaPagaNombreRazon').append(item.valor.substr(0, ind));
                        $('#empresaPagaDocumento').append(item.valor.substr(ind+1,item.valor.length));
                    }
                    break;                    
                case 2251:
                    var fechaTraslado = separarFecha(item.valor);
                    $('#fechaTraslado').append(fechaTraslado.dia+"/"+fechaTraslado.mes+"/"+fechaTraslado.anio.substr(2, 2));
                    break;
                default:
                    break;
            }
        });
                
        if (!isEmpty(data.documentoRelacionado)) {
            var documentoRelacionado = data.documentoRelacionado;
            $.each(documentoRelacionado, function (index, item) {
                if (item.identificador_negocio == 6) {
                    $('#comprabantePago').append(item.serie_numero_original);
                }
            });
        }

                
//        $('#comprabantePago').append(serieComprobante+" - "+numeroComprobante);
        $('#celdaMarcaPlaca').append(marca+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+placa);
        $('#celdaLicenciaConducir').append(licenciaConducir+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+configVehiculo);
        
        var fila="";
        $.each(data.detalle, function (index, item) {
            fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                + '<div class="detalleRowCodigo" id="detalleRowCodigo'+(index+1)+'">'+item['bien_codigo']+'</div>'
                + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                + '<div class="detalleRowUM" id="detalleRowUM'+(index+1)+'">'+item['simbolo']+'</div>'
                + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                + '<div class="detalleRowPesoTotal" id="detalleRowPesoTotal'+(index+1)+'"></div>'
                + '</div>'
            ;
        });
        //console.log(fila);
        $('#detalle').append(fila);

        //llamamos ala funcion imprimir
        imprimirHoja(documentoTipo);
    });
}

// impresion estandar
function plantillaDocumentoTipoEstandar(data, documentoTipo)
{
//    console.log(data);
    $('#datosImpresion').load('vistas/com/movimiento/plantillas/documentoTipoEstandar.php', function () {
        //asignamos los valores a los id de la plantilla
        
        //cabecera empresa
        $('#empresaRazonSocial').append(data['dataEmpresa'][0].razon_social);
        $('#empresaRUC').append("RUC: " + data['dataEmpresa'][0].ruc);
        
        var empresaDireccion=data['dataEmpresa'][0].direccion;
        
        if(empresaDireccion.indexOf("LA LIBERTAD")>=0){
            $('#empresaDireccion1').append(empresaDireccion.substr(0,empresaDireccion.indexOf("LA LIBERTAD") ));
            $('#empresaDireccion2').append(empresaDireccion.substr(empresaDireccion.indexOf("LA LIBERTAD"),empresaDireccion.length ));
        } else {
            $('#empresaDireccion1').append(empresaDireccion);
        }
        
        var serieDocumento='';
        if(!isEmpty(data['dataDocumento'][0].serie)){
            serieDocumento=data['dataDocumento'][0].serie + " - ";
        }
            
        $('#serieNumero').append(data['dataDocumento'][0].documento_tipo_descripcion.toUpperCase()+" "+ serieDocumento + data['dataDocumento'][0].numero);

        
        $('#celdaNombre').append("Señores: "+data['dataDocumento'][0].nombre);
        $('#celdaPersonaDireccion').append("Dirección: "+data['dataDocumento'][0].direccion);
        $('#celdaPersonaRUC').append(data['dataDocumento'][0].persona_documento_tipo+": "+data['dataDocumento'][0].codigo_identificacion);
                
        var fechaEmision = separarFecha(data['dataDocumento'][0].fecha_emision);
        $('#celdaFechaEmision').append("Fecha: "+fechaEmision.dia + "/"+ fechaEmision.mes+ "/" + fechaEmision.anio);
        
        if(!isEmpty(data['dataDocumento'][0].descripcion)){
            $('#celdaDescripcion').append("Descripción: "+ data['dataDocumento'][0].descripcion);
            $('#espacioId').append('<div class="personaCelda anchoPersona" ></div>');
        }
        
        var filaDatos="";
        var estilo="";
        var txtValor="";
        var altoCelda="condicionesCelda";
        if (!isEmpty(data.documentoDatoValor)) {
            $.each(data.documentoDatoValor, function (index, item) {
                estilo="";
                if(index==0){
                    estilo="border-top: 1px solid black;";
                }
                if(index==(data.documentoDatoValor.length-1)){                    
                    estilo="border-bottom: 1px solid black;";
                }
                
                if(isEmpty(item.valor)){
                    txtValor="";
                }else{
                    txtValor=item.valor;
                }
                
                //tipos
                if (item.tipo == 1) {
                    txtValor = formatearNumero(txtValor);
                }

                if (item.tipo == 3) {
                    var fechaD = separarFecha(txtValor);
                    txtValor = fechaD.dia + "/" + fechaD.mes + "/" + fechaD.anio;
                }
                
                if(txtValor.length>61){
                    altoCelda="condicionesCelda2";
                }else{
                    altoCelda="condicionesCelda";
                }
                
                filaDatos=filaDatos+'<div class="'+altoCelda+'">'
                                        +'<div class="anchoCondiciones1" style=\''+estilo+'\'>'+item.descripcion+'</div>'
                                        +'<div class="anchoCondiciones2" style=\''+estilo+'\'>'+txtValor+'</div>'
                                    +'</div>';
            });
        }else{
            $('#otrosDatosDocumento').hide();
        }        
//        console.log(filaDatos);
        $('#otrosDatosDocumento').append(filaDatos);

        if(!isEmpty(data.detalle)){
            var fila="";
            $.each(data.detalle, function (index, item) {
                fila=fila+'<div class="detalleRow" id="detalleRow'+(index+1)+'">'
                    + '<div class="detalleRowCantidad" id="detalleRowCantidad'+(index+1)+'">'+formatearCantidad(item['cantidad'])+'</div>'
                    + '<div class="detalleRowDescripcion" id="detalleRowDescripcion'+(index+1)+'">'+item['descripcion']+'</div>'
                    + '<div class="detalleRowUnidad" id="detalleRowUnidad'+(index+1)+'">'+item['simbolo']+'</div>'
                    + '<div class="detalleRowPU" id="detalleRowPU'+(index+1)+'">'+formatearNumero(item['precioUnitario'])+'</div>'
                    + '<div class="detalleRowImporte" id="detalleRowImporte'+(index+1)+'">'+formatearNumero(item['importe'])+'</div>'
                    + '</div>'
                ;
            });
            $('#detalle').append(fila);
        }else{
            $('#detalle').empty();
            $('.pieImportes').empty();
            $('#celdaDescripcionDetalle').empty();
            $('#celdaDescripcion').append('<div class="personaCelda anchoPersona" >Total: '+formatearNumero(data['dataDocumento'][0].total)+ " " + data['dataDocumento'][0].moneda_descripcion +'</div>');
        }
        
        if(isEmpty(data['dataDocumento'][0].total)){
            $('#totalDescripcion').hide();
            $('#pieImporteTotal').hide();
        }else{
            $('#pieImporteTotal').append(formatearNumero(data['dataDocumento'][0].total));
        }
        
        $('#celdaComentario').append(data['dataDocumento'][0].comentario);

        //llamamos ala funcion imprimir
        imprimirHoja("documentoTipoEstandar");
    });
}

