<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <div id="dataImprimir">
            <div class="cabeceraSerieNumero" id="serieNumero"></div>            
            
            <div class="cabeceraFechas">
                <div class="fechasCelda anchoFechas" id="emisionDia"></div>
                <div class="fechasCelda anchoFechas" id="emisionMes"></div>
                <div class="fechasCelda anchoFechas" id="emisionAnio"></div>
                <div class="fechasCelda espacioEntreFechas"></div>
                <div class="fechasCelda anchoFechas" id="trasladoDia"></div>
                <div class="fechasCelda anchoFechas" id="trasladoMes"></div>
                <div class="fechasCelda anchoFechas" id="trasladoAnio"></div>
            </div>

            <div class="cabeceraDestinatario">
                <div class="destinatarioCelda anchoDestinatario" id="celdaCodigo" ></div>
                <div class="destinatarioCelda anchoDestinatario" id="celdaNombreRazon" style="width: 9cm;" ></div>
                <div class="destinatarioCelda anchoDestinatario" id="celdaDireccion" ></div>
            </div>
            
            <div class="cabeceraDestinatarioDocumento">                
                <div class="destinatarioCelda anchoDestinatarioDocumento" id="celdaDocumento" ></div>
            </div>

            <div class="cabeceraEmpresaTranporte">
                <div class="empresaTranporteCelda anchoEmpresaTranporte" id="celdaNombreTransportista"></div>
                <div class="empresaTranporteCelda anchoEmpresaTranporte" id="celdaCodigoIdentificacionTransportista"></div>  
                <div class="empresaTranporteCelda anchoEmpresaTranporte" id="celdaPlacaMarca" ></div>  
                <div class="empresaTranporteCelda anchoEmpresaTranporte" style="padding-left: 3.8cm;"  id="celdaConstanciaInscripcion" ></div>
                <div class="empresaTranporteCelda anchoEmpresaTranporte" style="padding-left: 2.2cm;"  id="celdaLicenciaConducir" ></div>            
            </div>            
            
            <div class="cabeceraTabla">
                <div class="cabeceraTablaCelda cabeceraOrdenCompra" id="cabeceraOrdenCompra"></div>
                <div class="cabeceraTablaCelda cabeceraNumPedido" id="cabeceraNumPedido"></div>
                <div class="cabeceraTablaCelda cabeceraNumFactura" id="cabeceraNumFactura"></div>
                <div class="cabeceraTablaCelda cabeceraVendedor" id="cabeceraVendedor"></div>
            </div>
            
            <div class="cabeceraPuntos">
                <div class="puntosCelda anchoPuntos" id="puntoPartida"></div>
                <div class="puntosCelda espacioEntrePuntos" ></div>
                <div class="puntosCelda anchoPuntos" id="puntoLlegada"></div>
            </div>
            
            <div id="detalle" class="part-detalle">
                <!--
                <div class="detalleRow" id="detalleRow1">
                    <div class="detalleRowCodigo" id="detalleRowCodigo1"></div>
                    <div class="detalleRowCantidad" id="detalleRowCantidad1"></div>
                    <div class="detalleRowUM" id="detalleRowUM1"></div>
                    <div class="detalleRowDescripcion" id="detalleRowDescripcion1"></div>
                    <div class="detalleRowPrecioUnitario" id="detalleRowPrecioUnitario1"></div>
                    <div class="detalleRowPrecioTotal" id="detalleRowPrecioTotal1"></div>
                    <div class="detalleRowPesoTotal" id="detalleRowPesoTotal1"></div>
                </div>
                -->
            </div>           
               
            <div class="cabeceraMotivoTraslado">
                <div class="motivoTrasladoCelda" id="celdaCompraVenta"></div>
                <div class="motivoTrasladoCelda" id="celdaTransformacion"></div>
                <div class="motivoTrasladoCelda" id="celdaConsignacion"></div>
                <div class="motivoTrasladoCelda" id="celdaTransferencia"></div>
                <div class="motivoTrasladoCelda" id="celdaEmisor"></div>
                <div class="motivoTrasladoCelda" id="celdaOtros"></div>      
            </div>         
            
        </div>
    </body>
</html>


