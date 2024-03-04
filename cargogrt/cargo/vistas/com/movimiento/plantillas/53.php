<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Impresion internamiento</title>
    </head>
    <body>
        <div id="dataImprimir">
            <div>
                <div id="logo" style="float: left;">
                    <img src="http://localhost/almacen/vistas/images/iconoImagina.png" width="30" height="30">
                </div>
                <div id="titulo">
                    <p style="padding-top: 10px;"><b>INTERNAMIENTO</b></p>
                    <p id="numeroInternamiento"><b>Número: </b></p> 
                </div>
            </div> 
            <br>
            <div>
                <table>
                    <tr>
                        <td colspan="2">
                            <p id="nombrePersonaInternamiento">
                                <b>Señor: </b>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p id="direccionPersonaInternamiento">
                                <b>Dirección: </b>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p id="codigoIdentificacionPersonaInternamiento"><b>DNI/RUC: </b></p>
                        </td>
                        <td style="text-align: center;">
                            <p id="fechaInternamiento"><b>Fecha: </b></p>
                        </td>

                    </tr>
                </table>
            </div>
            <br>

            <div>
                <table rules="all" rules="columns" border="1" id="tablaContenido">
                    <tr style="text-align: center;">
                        <td style="width: 15%;"><b>Cantidad</b></td>
                        <td style="width: 45%;"><b>Descripción</b></td>
                        <td style="width: 20%;"><b>P.Unitario</b></td>
                        <td style="width: 20%;"><b>Importe</b></td>
                    </tr>
                </table>
            </div>
            <br>
            <div>
                <p id="totalEnTexto"><b>Son: </b></p>
            </div>
            <div id="pieTotal">
                <div id="total">
                    <p><b>Total</b></p>
                </div>
                <div id="totalResultado">
                    <p id="totalValor"></p>
                </div>
            </div>
            
        </div>

    </body>
</html>