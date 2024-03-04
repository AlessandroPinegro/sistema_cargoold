<?php
    include_once __DIR__.'/../../../../util/Configuraciones.php';
?>

<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>        
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
                <div class="personaCelda anchoPersona" id="celdaFechaEmision"></div>
                <div class="personaCelda anchoPersona" id="celdaNombre"></div>
                <div class="personaCelda anchoPersona" id="celdaMoneda"></div>
                <div class="personaCelda anchoPersona" >Estimados señores:</div>
                <div class="personaCelda anchoPersona" ></div>
                <div class="personaCelda anchoPersona" >Nos es grato saludarles por medio de la presente y a la vez hacerles llegar la sgte. cotización.</div>
                <div class="personaCelda anchoPersona" style="font-weight: bold; font-size: 10pt;" id="celdaDescripcion"></div>
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
                <div class="detalleRow" id="detalleRow0">
                    <div class="detalleRowCantidad" style="border: none;"></div>
                    <div class="detalleRowCodigo" style="border: none;"></div>
                    <div class="detalleRowDescripcion"  style="border: none;" ></div>
                    <div class="detalleRowUnidad"  style="border: none;" ></div>
                    <!--<div class="detalleRowPU" style="text-align: center" >P.INC.IGV</div>-->                    
                    <div class="detalleRowPU" style="text-align: center" id="totalDescripcion">Total </div>
                    <div class="detalleRowImporte" id="pieImporteTotal" ></div>
                </div>
            </div>             
                        
<!--            <div class="cabeceraFuncionamiento">
                <div class="funcionamientoCelda anchoFuncionamiento" style="font-weight: bold; font-size: 9pt;">Funcionamiento</div>
                <div class="funcionamientoCelda anchoFuncionamiento" id="celdaComentario"></div>
            </div>-->
                                    
            <div class="cabeceraCondiciones" id="otrosDatosDocumento">
                <div class="condicionesCelda">
                    <!--<div class="anchoCondiciones1" style="border-top: 1px solid black; text-align: center;"></div>-->
                    <!--<div class="anchoCondiciones2" style="border-top: 1px solid black; text-align: center;">LOS PRECIOS SON EN NUEVOS SOLES E INCLUYEN EL IGV</div>-->
                    <div class="anchoCondiciones3" style="border-top: 1px solid black; text-align: center;">TERMINOS Y CONDICIONES</div>
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
            <div class="cabeceraOtro">
                <div class="personaCelda anchoOtro">Sin otro particular, agradeciendo su gentil atención, quedamos a la espera de vuestra pronta respuesta.</div>
                <div class="personaCelda anchoOtro">Atentamente.</div>
            </div>
            
                   
            <div class="cabeceraPie">
<!--                <div class="pieVentaCelda">
                    <div class="anchoPie1"></div>
                    <div class="anchoPie2">TELEFAX</div>
                    <div class="anchoPie3">044 262811</div>
                </div>-->
                <div class="pieVentaCelda">
                    <div class="anchoPie1"></div>
                    <div class="anchoPie2">FIJO</div>
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
            </div>
        </div>
    </body>
</html>