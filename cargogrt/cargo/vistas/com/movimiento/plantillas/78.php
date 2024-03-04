<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <div id="dataImprimir">
            <div class="cabeceraSerieNumero" id="serieNumero"></div>
                        
            <div class="cabeceraPersona">
                <div class="cabeceraPersonaCelda" id="cabeceraNombre"></div>
                <div class="cabeceraPersonaCelda" id="cabeceraDireccion"></div>
                <div class="cabeceraPersonaCelda inlineItec" style="width: 4.0cm" id="cabeceraCodigo"></div>
            <!--</div>                 
            
            <div class="cabeceraFecha">-->
                <div class="inlineItec" style="width: 0.7cm; text-align: center" id="cabeceraDia"></div>
                <div class="inlineItec" style="width: 2.7cm; padding-left: 0.40cm; text-align: center" id="cabeceraMes"></div>
                <div class="inlineItec" style="width: 1.5cm;padding-left: 0.40cm; text-align: right" id="cabeceraAnio"></div>
            </div>
            
            <div class="cabeceraTabla">
                <div class="cabeceraTablaCelda cabeceraNumeroPedido" id="cabeceraNumeroPedido"></div>
                <div class="cabeceraTablaCelda cabeceraGuiaRemitente" id="cabeceraGuiaRemitente"></div>
                <div class="cabeceraTablaCelda cabeceraCondiciones" id="cabeceraCondiciones"></div>
                <div class="cabeceraTablaCelda cabeceraUnidadMonetaria" id="cabeceraUnidadMonetaria"></div>
                <div class="cabeceraTablaCelda cabeceraFechaEmision" id="cabeceraFechaEmision"></div>
                <div class="cabeceraTablaCelda cabeceraFechaVencimiento" id="cabeceraFechaVencimiento"></div>
            </div>
            
             <div id="detalle" class="part-detalle">
            <!--
            <div class="detalleRow" id="detalleRow1">
                <div class="detalleRowCantidad" id="detalleRowCantidad1"></div>
                <div class="detalleRowDescripcion" id="detalleRowDescripcion1"></div>
                <div class="detalleRowPU" id="detalleRowPU1"></div>
                <div class="detalleRowImporte" id="detalleRowImporte1"></div>
            </div>
            <div class="detalleRow" id="detalleRow2">
                <div class="detalleRowCantidad" id="detalleRowCantidad2"></div>
                <div class="detalleRowDescripcion" id="detalleRowDescripcion2"></div>
                <div class="detalleRowPU" id="detalleRowPU2"></div>
                <div class="detalleRowImporte" id="detalleRowImporte2"></div>
            </div>
            -->
        </div>

            <div class="pieTotalEnLetra" id="pieTotalTexto"></div>
            <div class="pieFecha">
                <div class="inlineItec" style="width: 0.8cm;" id="pieDia"></div>
                <div class="inlineItec" style="width: 0.8cm;" id="pieMes"></div>
                <div class="inlineItec" style="width: 0.8cm;" id="pieAnio"></div>
            </div>
            <div class="pieIGV" id="igv"></div>
            <div class="pieImportes">
                <div class="pieImportesImporte" id="pieImporteTotal"></div>
            </div>
        </div>
    </body>
</html>