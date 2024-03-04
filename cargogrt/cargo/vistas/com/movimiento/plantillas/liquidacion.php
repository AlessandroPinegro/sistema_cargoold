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
                    <div class="anchoCotizacionVenta1"></div>
                    <div class="anchoCotizacionVenta2" id="empresaRUC"></div>
                </div>
                <div class="cotizacionVentaCelda">
                    <div class="anchoCotizacionVenta1"></div>
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
                <div class="personaCelda anchoPersona" id="celdaMoneda"></div> 
                <div class="personaCelda anchoPersona" id="celdaNotaVentaRelacion"></div> 
                <div class="personaDireccionCelda anchoPersona" id="celdaPersonaDireccion"></div> 
                
                <div id="espacioId" ></div>
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
            <div id="divPenalidad" class="pieImportes"></div>
            <div class="pieImportes">
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

        </div>
    </body>
</html>