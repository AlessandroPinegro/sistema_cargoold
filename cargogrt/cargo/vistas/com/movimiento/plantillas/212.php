<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <div id="dataImprimir">
            <div class="cabeceraSerieNumero" id="serieNumero"></div>            
            
            <div class="cabeceraFechas">
                <div class="fechasCelda anchoFechas" id="fechaEmision"></div>
                <div class="fechasCelda espacioEntreFechas"></div>
                <div class="fechasCelda anchoFechas" id="fechaTraslado"></div>
            </div>
            
            <div class="cabeceraComprobantePago">                 
                <div class="comprobantePagoCelda anchoComprabantePago" id="comprabantePago"></div>
            </div>

            <div class="cabeceraPuntosPartida">
                <div class="puntosCelda" style="width: 5.5cm; text-align: left; padding-left: 0.5cm;" id="puntoPartida"></div>
                <div class="puntosCelda anchoPuntos" style="padding-left: 0.5cm;"   id="puntoPartidaDist"></div>
                <div class="puntosCelda espacioEntrePuntos" ></div>
                <div class="puntosCelda anchoPuntos" style="padding-left: 0.5cm;" id="puntoPartidaProv"></div>
                <div class="puntosCelda espacioEntrePuntos" ></div>
                <div class="puntosCelda anchoPuntos" style="padding-left: 0.5cm;" id="puntoPartidaDpto"></div>
            </div>

            <div class="cabeceraPuntosLlegada">
                <div class="puntosCelda" style="width: 5.5cm; text-align: left; padding-left: 0.5cm;" id="puntoLlegada"></div>
                <div class="puntosCelda anchoPuntos" style="padding-right: 0.2cm;" id="puntoLlegadaDist"></div>
                <div class="puntosCelda espacioEntrePuntos" ></div>
                <div class="puntosCelda anchoPuntos" style="padding-right: 0.2cm;"  id="puntoLlegadaProv"></div>
                <div class="puntosCelda espacioEntrePuntos" ></div>
                <div class="puntosCelda anchoPuntos" style="padding-right: 0.3cm;"  id="puntoLlegadaDpto"></div>
            </div>

            <div class="cabeceraRemitente">
                <div class="remitenteCelda anchoRemitente" style="padding-left: 3cm;" id="remitenteNombreRazon" ></div>
                <div class="remitenteCelda anchoRemitente" style="padding-left: 0.6cm;" id="remitenteDocumento" ></div>
            </div>

            <div class="cabeceraDestinatario">
                <div class="destinatarioCelda anchoDestinatario" style="padding-left: 2.1cm;" id="destinatarioNombreRazon" ></div>
                <div class="destinatarioCelda anchoDestinatario" id="destinatarioDocumento" ></div>
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
            
            <div class="cabeceraUnidadTransporte">
                <div class="unidadTransporteCelda anchoUnidadTransporte" style="padding-left: 0.4cm;" id="celdaNombreTransportista"></div>
                <div class="unidadTransporteCelda anchoUnidadTransporte" style="width: 6.2cm; text-align: left; padding-right: 1cm;"  id="celdaMarcaPlaca" ></div>
                <div class="unidadTransporteCelda anchoUnidadTransporte" id="celdaConstanciaInscripcion" ></div>
                <div class="unidadTransporteCelda anchoUnidadTransporte" id="celdaLicenciaConducir" ></div>     
                <!--<div class="unidadTransporteCelda anchoUnidadTransporte" id="celdaConfiguracionVehiculo" ></div>-->         
            </div>

            <div class="cabeceraSubContratacion">
                <div class="subContratacionCelda anchoSubContratacion" style="padding-left: 3cm;" id="subContratacionUnidades" ></div>
                <div class="subContratacionCelda anchoSubContratacion" id="subContratacionDocumento" ></div>
                <div class="subContratacionCelda anchoSubContratacion" style="padding-top: 0.3cm;" id="subContratacionNombreRazon" ></div>
            </div>

            <div class="cabeceraEmpresaPaga">
                <div class="empresaPagaCelda anchoEmpresaPaga" id="empresaPagaDocumento" ></div>
                <div class="empresaPagaCelda anchoEmpresaPaga"  ></div>
                <div class="empresaPagaCelda anchoEmpresaPaga" style="padding-top: 0.3cm;" id="empresaPagaNombreRazon" ></div>
            </div>
            
        </div>
    </body>
</html>


