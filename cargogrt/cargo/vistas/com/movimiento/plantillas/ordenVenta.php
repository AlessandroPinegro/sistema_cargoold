<?php
include_once __DIR__ . '/../../../../util/Configuraciones.php';
?>

<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="ordenVenta.css" href="url"/> 
    </head>
    <body>
        <div id="dataImprimir">
            <img alt="" src="<?php echo Configuraciones::url_base(); ?>vistas/com/movimiento/imagen/logo.PNG">

            <div class="cabeceraCotizacionVenta">
                <div class="cotizacionVentaCelda">
                    <div class="anchoCotizacionVenta1"></div>
                    <div class="anchoCotizacionVenta2" id="empresaRazonSocial"></div>
                </div>
                <div class="cotizacionVentaCelda">
                    <div class="anchoCotizacionVenta1">EQUIPAMIENTO DE PERFORACIÓN PARA LA INDUSTRIA MINERA, </div>
                    <div class="anchoCotizacionVenta2" id="empresaRUC"></div>
                </div>
                <div class="cotizacionVentaCelda">
                    <div class="anchoCotizacionVenta1">CONSTRUCCIÓN, PETRÓLEO Y AFINES.</div>
                    <div class="anchoCotizacionVenta2" id="empresaDireccion1"></div>
                </div>
                <div class="cotizacionVentaCelda">
                    <div class="anchoCotizacionVenta1"></div>
                    <div class="anchoCotizacionVenta2" id="empresaDireccion2"></div>
                </div>
            </div>

            <div class="cabeceraSerieNumero" id="serieNumero"></div>            

            <div class="cabeceraPersona">
                <div class="personaCelda" style="padding-right: 0.1cm;
                     width: 9cm;
                     text-align: left;
                     display: table-cell;
                     border: none;" id="celdaFechaEmision"></div>
                <div class="personaCelda" style="padding-right: 0.1cm;
                     width: 9cm;
                     text-align: left;
                     display: table-cell;
                     border: none;" id="celdaFechaVencimiento"></div>
                <div class="personaCelda anchoPersona" id="celdaNombre"></div>
                <div class="personaDireccionCelda anchoPersona" id="celdaPersonaDireccion"></div>
                <!--<div class="personaCelda anchoPersona" id="celdaPersonaRUC"></div>-->
                <div class="personaCelda anchoPersona" id="celdaMoneda"></div>
                <!--<div class="personaCelda anchoPersona" >Estimados señores:</div>-->
                <div class="personaCelda anchoPersona" id="celdaDescripcion"></div>
                <div id="espacioId" ></div>
                <!--<div class="personaCelda anchoPersona" >Nos es grato saludarles por medio de la presente y a la vez hacerles llegar la sgte. cotización.</div>-->                
                <div class="personaCelda anchoPersona" style="font-size: 9pt;font-weight: bold; " id="celdaDescripcionDetalle">DETALLE DE ENTREGA</div>
                <div class="personaCelda" style="padding-right: 0.1cm;
                     width: 9cm;
                     text-align: left;
                     display: table-cell;
                     border: none;" id="celdaAgenciaOrigen"></div>
                <div class="personaCelda" style="padding-right: 0.1cm;
                     width: 9cm;
                     text-align: left;
                     display: table-cell;
                     border: none;" id="celdaAgenciaDestino"></div>
                <div class="personaCelda anchoPersona" id="celdaModalidad"></div>      
                <div class="personaDireccionCelda anchoPersona" id="celdaPersonaAutorizada"></div>
                <div class="personaCelda anchoPersona" id="celGuiaRelacionada"></div>
                <div class="personaDireccionCelda anchoPersona" id="celdaPersonaDireccionOrigen"></div>
                <div class="personaDireccionCelda anchoPersona" id="celdaPersonaDireccionDestino"></div>
                
                <br/>
                <div class="personaCelda anchoPersona" style="font-size: 9pt;font-weight: bold; " id="celdaDescripcionDetalle">DETALLE DEL DOCUMENTO</div>
            </div>

            <div id="detalle" class="part-detalle">
                <div class="detalleRow" id="detalleRow0">
                    <div class="detalleRowCantidad" style="text-align: center; font-weight: bold;">Cant.</div>
                    <div class="detalleRowCodigo" style="text-align: center; font-weight: bold;">Código</div>
                    <div class="detalleRowDescripcion" style="text-align: center; font-weight: bold;" >Descripción</div>
                    <div class="detalleRowUnidad" style="text-align: center; font-weight: bold;" >Unid.</div>
                    <div class="detalleRowPU" style="text-align: center; font-weight: bold;" >P.Unit.</div>
                    <div class="detalleRowImporte" style="text-align: center; font-weight: bold;" >P.Total</div>
                </div>
            </div>
            <div class="pieImportes">

                <div class="detalleRow">
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div  style="padding-right: 0.1cm;
                          width: 3.8cm;
                          text-align: right;
                          display: table-cell;
                          border: 1px solid black" id="otroCargoDescripcion">Otros cargos </div>
                    <div class="detalleRowImporte" id="pieImporteOtroCargo">0.00</div>
                </div>

                <div class="detalleRow"> 
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div  style="padding-right: 0.1cm;
                          width: 3.8cm;
                          text-align: right;
                          display: table-cell;
                          border: 1px solid black">Dev. de cargo </div>
                    <div class="detalleRowImporte" id="pieImporteDevolucionCargo">0.00</div>
                </div>
                <div class="detalleRow"> 
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div  style="padding-right: 0.1cm;
                          width: 3.8cm;
                          text-align: right;
                          display: table-cell;
                          border: 1px solid black">Costo reparto </div>
                    <div class="detalleRowImporte" id="pieImporteCostoReparto">0.00</div>
                </div>

                <div class="detalleRow">   
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div style="padding-right: 0.1cm;
                         width: 3.8cm;
                         text-align: right;
                         display: table-cell;
                         border: 1px solid black">Sub total </div>
                    <div class="detalleRowImporte" id="pieImporteSubTotal" >0.00</div>
                </div>

                <div class="detalleRow"> 
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div style="padding-right: 0.1cm;
                         width: 3.8cm;
                         text-align: right;
                         display: table-cell;
                         border: 1px solid black">IGV </div>
                    <div class="detalleRowImporte" id="pieImporteIgv">0.00</div>
                </div>

                <div class="detalleRow">    
                    <div  style="padding-right: 0.1cm; width: 11.6cm; text-align: right; display: table-cell;border: none">&nbsp;&nbsp;</div>
                    <div style="padding-right: 0.1cm;
                         width: 3.8cm;
                         text-align: right;
                         display: table-cell;
                         border: 1px solid black" id="totalDescripcion">Total </div>
                    <div class="detalleRowImporte" id="pieImporteTotal" ></div>
                </div> 
            </div>             


            <div class="cabeceraCondiciones" id="otrosDatosDocumento">
                <div class="condicionesCelda">
                    <!--<div class="anchoCondiciones1" style="border-top: 1px solid black; text-align: center;"></div>-->
                    <!--<div class="anchoCondiciones2" style="border-top: 1px solid black; text-align: center;">LOS PRECIOS SON EN NUEVOS SOLES E INCLUYEN EL IGV</div>-->
                    <div class="anchoCondiciones3" style="border-top: 1px solid black; text-align: center;font-weight: bold; ">
                        OTROS DATOS DEL DOCUMENTO</div>
                </div>
                <!--                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1" style="border-top: 1px solid black;">Plazo de entrega:</div>
                                    <div class="anchoCondiciones2" style="border-top: 1px solid black;" id="plazoEntrega"></div>
                                </div>
                                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1">Forma de pago:</div>
                                    <div class="anchoCondiciones2" id="formaPago"></div>
                                </div>
                                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1">Forma de pago:</div>
                                    <div class="anchoCondiciones2" id="formaPago"></div>
                                </div>
                                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1">Garantía: </div>
                                    <div class="anchoCondiciones2" id="garantia"></div>
                                </div>
                                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1">Vig. de cot.: </div>
                                    <div class="anchoCondiciones2" id="vigCot"></div>
                                </div>
                                <div class="condicionesCelda">
                                    <div class="anchoCondiciones1" style="border-bottom: 1px solid black;">Cuenta bancaria: </div>
                                    <div class="anchoCondiciones2" style="border-bottom: 1px solid black;" id="cuentaBancaria"></div>
                                </div>-->               

            </div>
            <!--            <div class="cabeceraOtro">
                            <div class="personaCelda anchoOtro">Sin otro particular, agradeciendo su gentil atención, quedamos a la espera de vuestra pronta respuesta.</div>
                            <div class="personaCelda anchoOtro">Atentamente.</div>
                        </div>-->


            <!--            <div class="cabeceraPie">
                            <div class="pieVentaCelda">
                                <div class="anchoPie1"></div>
                                <div class="anchoPie2">TELEFAX</div>
                                <div class="anchoPie3">044 262811</div>
                            </div>
                            <div class="pieVentaCelda">
                                <div class="anchoPie1"></div>
                                <div class="anchoPie2"></div>
                                <div class="anchoPie3">044 209454</div>
                            </div>
                            <div class="pieVentaCelda">
                                <div class="anchoPie1"></div>
                                <div class="anchoPie2">RPC</div>
                                <div class="anchoPie3">977192256</div>
                            </div>
                            <div class="pieVentaCelda">
                                <div class="anchoPie1" style="font-weight: bold;"></div>
                                <div class="anchoPie2">RPM</div>
                                <div class="anchoPie3">*445213</div>
                            </div>
                            <div class="pieVentaCelda">
                                <div class="anchoPie1"></div>
                                <div class="anchoPie2">NEXTEL</div>
                                <div class="anchoPie3">836*3196</div>
                            </div>
                            <div class="pieVentaCelda">
                                <div class="anchoPie1"></div>
                                <div class="anchoPie2">CELULAR</div>
                                <div class="anchoPie3">965076817</div>
                            </div>
                        </div>-->
        </div>
    </body>
</html>