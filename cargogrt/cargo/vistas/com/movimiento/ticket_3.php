<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="../../images/icono_ittsa.ico">
        <title>ITTSA CARGO - INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL</title>
    </head>

    <?php
    require_once('../../../util/Configuraciones.php');
    require_once('../../../modeloNegocio/almacen/DocumentoNegocio.php');
    require_once('../../../modeloNegocio/almacen/PersonaNegocio.php');
    require_once('../../../modelo/almacen/MovimientoBien.php');
    require_once('../../../modeloNegocio/almacen/EmpresaNegocio.php');
    require_once('../../../modelo/almacen/Documento.php');

    isset($_GET["id"]) ? $idComprob = $_GET["id"] : "";

    if (ObjectUtil::isEmpty($_GET["id"]) || $idComprob == "") {
        echo("No se encontró el documento");
        exit();
    }

    $igv = 18;
    $arrayDetalle = array();

    $datoDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($idComprob);
    $datoDocumentoRelacionado = Documento::create()->obtenerDetalleDocumentoRealacionado($datoDocumento[0]["id"]);

    if (ObjectUtil::isEmpty($datoDocumento)) {
        echo("No se encontró el documento");
        exit();
    }

    $movimientoId = $datoDocumento[0]["movimiento_id"];
    $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);
    $serie_numero = $datoDocumentoRelacionado[0]["serie_numero"];
    $total = 0.00;

    //DATOS EMPRESA
    $empresaId = $datoDocumento[0]["empresa_id"];
    $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);

    //DATOS CLIENTE
    $personaId = $datoDocumento[0]['persona_id'];

    //DATOS REMITENTE
    $remitenteId = $datoDocumento[0]['persona_origen_id'];
    $dataRemitente = PersonaNegocio::create()->obtenerPersonaXId($remitenteId);

    //DATOS DESTINATARIO
    $destinatarioId = $datoDocumento[0]['persona_destinatario_id'];
    $dataDestinatario = PersonaNegocio::create()->obtenerPersonaXId($destinatarioId);

    $aliasEmpresa = $dataEmpresa[0]["alias"];
    $rucEmpresa = $dataEmpresa[0]["ruc"];
    $nombreEmpresa = $dataEmpresa[0]["razon_social"];
    $direccionEmpresa = $dataEmpresa[0]["direccion"];

    $guiaRemision = $datoDocumento[0]['guia_remision'];

    $TipoComprobanteNro = $datoDocumento[0]["documento_tipo_id"];
    $ComprobanteSerie = $datoDocumento[0]["serie"];
    $ComprobanteNro = $datoDocumento[0]["numero"];
    $nrodeComprobante = $datoDocumento[0]["serie_numero"];
    $fechaComprobante = DateUtil::formatearBDACadena($datoDocumento[0]["fecha_creacion"]);
    $fechaCreacion = new DateTime($datoDocumento[0]["fecha_creacion"]);
    $horaComprobante = $fechaCreacion->format("h:m A");

    $monedaSimbolo = $datoDocumento[0]["moneda_simbolo"];
    $totalPagar = number_format($datoDocumento[0]["total"], 2);
    $subtotalPagar = number_format($datoDocumento[0]["subtotal"], 2);
    $igv = number_format($datoDocumento[0]["igv"], 2);

    $clienteDocumento = $datoDocumento[0]["codigo_identificacion"];
    $clienteNombre = $datoDocumento[0]["persona_nombre"];
    $clienteDocumentoTipo = $datoDocumento[0]["persona_documento_tipo"];

    $remitenteDocumentoTipo = $datoDocumento[0]["persona_documento_tipo_origen"];
    $remitenteNombre = $datoDocumento[0]["persona_origen_nombre"];
    $remitenteDocumento = $datoDocumento[0]["codigo_identificacion_origen"];

    if (!ObjectUtil::isEmpty($dataRemitente[0]['telefono']) && !ObjectUtil::isEmpty($dataRemitente[0]['celular'])) {
        $remitenteTelefonos = $dataRemitente[0]['telefono'] . " / " . $dataRemitente[0]['celular'];
    } else if (!ObjectUtil::isEmpty($dataRemitente[0]['telefono'])) {
        $remitenteTelefonos = $dataRemitente[0]['telefono'];
    } else if (!ObjectUtil::isEmpty($dataRemitente[0]['celular'])) {
        $remitenteTelefonos = $dataRemitente[0]['celular'];
    }

    $destinoDocumentoTipo = $datoDocumento[0]["persona_documento_tipo_destino"];
    $destinoNombre = $datoDocumento[0]["persona_destino_nombre"];
    $destinoDocumento = $datoDocumento[0]["codigo_identificacion_destino"];

    if (!ObjectUtil::isEmpty($dataDestinatario[0]['telefono']) && !ObjectUtil::isEmpty($dataDestinatario[0]['celular'])) {
        $destinatarioTelefonos = $dataDestinatario[0]['telefono'] . " / " . $dataDestinatario[0]['celular'];
    } else if (!ObjectUtil::isEmpty($dataDestinatario[0]['telefono'])) {
        $destinatarioTelefonos = $dataDestinatario[0]['telefono'];
    } else if (!ObjectUtil::isEmpty($dataDestinatario[0]['celular'])) {
        $destinatarioTelefonos = $dataDestinatario[0]['celular'];
    }

    $origen = $datoDocumento[0]["agencia_origen"];
    $destino = $datoDocumento[0]["agencia_destino"];
    $modalidad = mb_strtoupper($datoDocumento[0]["modalidad_descripcion"], 'UTF-8');
    $direccionDestino = $datoDocumento[0]['persona_direccion_destino'];

    $trDireccion = '';
    if ($datoDocumento[0]["modalidad_id"] == 76) {
        $trDireccion = '<tr>
    <td style="width:29%;font-size:8px;background-color:white;text-align:left;">		
DIRECCIÓN ENTREGA:
        </td>
        <td style="width:71%;font-size:8px;background-color:white;text-align:left;">' .
                $direccionDestino
                . '</td>
    </tr>';
    }

    $ruta = $datoDocumento[0]["agencia_origen"] . ' - ' . $datoDocumento[0]["agencia_destino"];

    $metodoPago = mb_strtoupper($datoDocumento[0]["documento_tipo_pago"], 'UTF-8');

    if ($metodoPago == '') {
        if ($datoDocumento[0]["modalidad_id"] == 75) {
            $metodoPago = 'PAGO CONTRAENTREGA';
        } else if ($datoDocumento[0]["documento_tipo_id"] == 191) {
            $metodoPago = 'CRÉDITO';
        }
    }


    $textoComprobante = strtoupper($datoDocumento[0]["comprobante_codigo_qr"]);
    $textoPedido = strtoupper($datoDocumento[0]["traking_codigo_qr"]);
    $pedidoId = strtoupper($datoDocumento[0]["pedido_id"]);
    $comprobanteId = strtoupper($datoDocumento[0]["documento_id"]);

    $otroCostoGastoDescripcion = mb_strtoupper($datoDocumento[0]["otro_gasto_descripcion"], 'UTF-8');
    $otroGastoCosto = $datoDocumento[0]["monto_otro_gasto"];
    $costoReparto = $datoDocumento[0]["monto_costo_reparto"];
    $costoDevolucionCargo = $datoDocumento[0]["monto_devolucion_gasto"];
    $ajustePrecio = number_format($datoDocumento[0]["ajuste_precio"], 2);

    $urlDespacho = ConfigGlobal::PARAM_URL_HTTP.ConfigGlobal::PARAM_URL_ADD. "tracking.php?" . Util::encripta("tipo=2&documentoId=" . $pedidoId);

    if ($TipoComprobanteNro == 6) {
        $TipoComprobante = "BOLETA DE VENTA";
    } else if ($TipoComprobanteNro == 7) {
        $TipoComprobante = "FACTURA DE VENTA";
    } else if ($TipoComprobanteNro == 191) {
        $TipoComprobante = "NOTA DE VENTA";
    } else if ($TipoComprobanteNro == 61) {
        $TipoComprobante = "NOTA DE CRÉDITO";
    } else if ($TipoComprobanteNro == 269) {
        $TipoComprobante = "NOTA DE DÉBITO";
    }

    $monedaDescripcion = mb_strtoupper($datoDocumento[0]["moneda_descripcion"], 'UTF-8');

    $centimos = substr($datoDocumento[0]['total'], -2);

    $enLetra = new EnLetras();
    $totalLetras = mb_strtoupper($enLetra->convertir($datoDocumento[0]['total'], $datoDocumento[0]['moneda_id']), 'UTF-8');

    if ($textoPedido == "" || $textoPedido == null) {
        $textoComprobante = $rucEmpresa . "|" . $TipoComprobanteNro . "|" . $ComprobanteSerie . "|" . $ComprobanteNro . "|" . $igv . "|" . $totalPagar;
    }

    $urlComprobanteQr = DocumentoNegocio::create()->generaQRticket($textoComprobante, $comprobanteId);
    $urlComprobanteQr = Util::ImageTobase64($urlComprobanteQr);

    $urlPedidoQr = DocumentoNegocio::create()->generaQRticket($urlDespacho, $pedidoId);
    $urlPedidoQr = Util::ImageTobase64($urlPedidoQr);

    require_once('../../../controlador/commons/TCPDF-main/tcpdf.php');

    $medidas = array(75, 600); // Ajustar aqui segun los milimetros necesarios;
    $pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
    $pdf->SetMargins(5, 5, 5);
    $pdf->startPageGroup();

    $pdf->AddPage();

    $bloque1 = <<<EOF
		<table>
				<tr>
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
						$aliasEmpresa
						</div>
					</td>
				</tr>
				<tr>
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
						RUC: $rucEmpresa
						</div>
					</td>
				</tr>
				
				<tr>		
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
						$nombreEmpresa
						</div>
					</td>
				</tr>
				<tr>
					<td style="background-color:white;">
						<div style="font-size:6px; text-align:center;">
						$direccionEmpresa
						</div>
					</td>
				</tr>
                <tr>
					<td style="background-color:white;">
						<div style="font-size:4px; text-align:center;">
                        &nbsp;
						</div>
					</td>
				</tr>
				<tr>
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
                        $TipoComprobante ELECTRÓNICA
						</div>
					</td>
				</tr>
				<tr>
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
						Nro: $nrodeComprobante
						</div>
					</td>
				</tr>
                                <tr>
					<td style="background-color:white;">
						<div style="font-size:8px; text-align:center;">
                        &nbsp;
						</div>
					</td>
				</tr>
            <tr>
                <td style="width:21%;font-size:8px;background-color:white;text-align:left;">		
						FECHA: 
		</td>
                <td style="width:29%;font-size:8px;background-color:white;text-align:left;">		
$fechaComprobante 
                </td>

                <td style="width:50%;font-size:8px;background-color:white;text-align:left;">
                        HORA: $horaComprobante
                    </td>
            </tr>
            <tr>
                <td style="width:21%;font-size:8px;background-color:white;text-align:left;">		
$clienteDocumentoTipo:
					</td>
                    <td style="width:79%;font-size:8px;background-color:white;text-align:left;">	
$clienteDocumento
                </td>
				</tr>
                <tr>
                <td style="width:21%;font-size:8px;background-color:white;text-align:left;">	
CLIENTE:  
                </td>
                <td style="width:79%;font-size:8px;background-color:white;text-align:left;">	
$clienteNombre
                                </td>
            </tr>
			</table>
EOF;

    $pdf->writeHTML($bloque1, false, false, false, false, '');

    
    $bloque2 = <<<EOF
<table>
	<tr>
    <td style="width:100%;background-color:white;text-align:left;">
					<div style="font-size:7px;text-align:center;">
			-----------------------------------------------------------------------------
			</div>
					</td>
				</tr>
	</tr>
    <tr>
<td style="width:60%;background-color:white;text-align:left;">
<div style="font-size:7px;">
DESCRIPCIÓN
</div>
</td>
<td style="width:18%;background-color:white;text-align:left;">
<div style="font-size:7px;">
CANT.
</div>
</td>
<td style="width:20%;background-color:white;text-align:right;">
<div style="font-size:7px;">
PRECIO
</div>
</td>
</tr>
	<tr>
    <td style="width:100%;background-color:white;text-align:left;">
					<div style="font-size:7px;text-align:center;">
			-----------------------------------------------------------------------------
			</div>
					</td>
				</tr>
	</table>
EOF;
    $pdf->writeHTML($bloque2, false, false, false, false, '');

    
    
    //LÓGICA PARA LOS ITEMS Y COSTOS ADICIONALES (REPARTO, DEV.CARGO,ETC).
    if ($otroGastoCosto * 1 > 0) {

        $cantidad = number_format(1, 2);
        $documentoDetalle[] = array("bien_descripcion" => $otroCostoGastoDescripcion,
            "cantidad" => $cantidad,
            "valor_monetario" => $otroGastoCosto);
    }

    if ($costoReparto * 1 > 0) {

        $cantidad = number_format(1, 2);
        $documentoDetalle[] = array("bien_descripcion" => "Costo de reparto",
            "cantidad" => $cantidad,
            "valor_monetario" => $costoReparto);
    }

    if ($costoDevolucionCargo * 1 > 0) {

        $cantidad = number_format(1, 2);
        $documentoDetalle[] = array("bien_descripcion" => "Costo por devolución de cargo",
            "cantidad" => $cantidad,
            "valor_monetario" => $costoDevolucionCargo);
    }

    $textoAjustePrecio = '';
    if ($ajustePrecio * 1 > 0) {

        $textoAjustePrecio = '<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
AJUSTE DE PRECIO: 
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">' .
                $monedaSimbolo
                . '</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">' .
                $ajustePrecio
                . '</div>

	</td>

</tr>';
    }

    foreach ($documentoDetalle as $detalle) {
        $subTotal = round($detalle['cantidad'] * $detalle['valor_monetario'], 2);
        $cantid = number_format($detalle['cantidad'], 2);
        $total += round($subTotal, 2);
        $subTotal = number_format($subTotal, 2);

        $bloque4 = <<<EOF
<table>
<tr>
<td style="width:60%;background-color:white;text-align:left;">
<div style="font-size:7px;">
$detalle[bien_descripcion]
</div>
</td>
<td style="width:18%;background-color:white;text-align:right;">
<div style="font-size:7px;">
 $cantid
</div>
</td>
<td style="width:20%;background-color:white;text-align:right;">
<div style="font-size:7px;">
$subTotal
</div>
</td>
</tr>
</table>
EOF;

        $pdf->writeHTML($bloque4, false, false, false, false, '');
    }



    $bloque5 = <<<EOF

<table>

<tr>
    <td style="width:100%;background-color:white;text-align:left;">
					<div style="font-size:7px;text-align:center;">
			-----------------------------------------------------------------------------
			</div>
					</td>
</tr>
$textoAjustePrecio
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
OP. GRATUITA:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    0.00
  </div>

	</td>

</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
OP. EXONERADA:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    0.00
  </div>

	</td>

</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
OP. INAFECTA:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    0.00
  </div>

	</td>

</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
OP. GRAVADA:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    $subtotalPagar
  </div>

	</td>

</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
DESCUENTO:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    0.00
  </div>

	</td>

</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
IGV:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    $igv
  </div>

	</td>

</tr>
     <tr>
					<td style="background-color:white;">
						<div style="font-size:4px; text-align:center;">
                        &nbsp;
						</div>
					</td>
				</tr>
<tr>
<td style="width:68%;background-color:white;text-align:right;">
<div style="font-size:7px;">
TOTAL A PAGAR:
</div>
</td>
<td style="width:10%;background-color:white;text-align:left;">
<div style="font-size:7px;">
 $monedaSimbolo
</div>
</td>
	<td style="width:20%;background-color:white;text-align:right;">
	<div style="font-size:7px;">
    $totalPagar
  </div>
	</td>

</tr>
 <tr>
					<td style="background-color:white;">
						<div style="font-size:4px; text-align:center;">
                        &nbsp;
						</div>
					</td>
				</tr>
</table>

EOF;

    $pdf->writeHTML($bloque5, false, false, false, false, '');

    $bloque6 = <<<EOF
    <table>
                    <tr>
                        <td style="width:100%;font-size:8px;background-color:white;text-align:left;">	
    SON: $totalLetras CON $centimos/100 $monedaDescripcion
                    </td>
                    </tr>
                    </table>
    EOF;
    $pdf->writeHTML($bloque6, false, false, false, false, '');

    $bloque7 = <<<EOF
    <table>
                    <tr>
                        <td style="width:100%;font-size:8px;background-color:white;text-align:left;">	
    DOCUMENTO RELACIONADO: $serie_numero
                    </td>
                    </tr>
                    </table>
    EOF;
    $pdf->writeHTML($bloque7, false, false, false, false, '');

    $bloque1 = <<<EOF
			<table>
				<tr>
					<td style="width:100%;background-color:white;text-align:left;">
						<div style="font-size:7px;text-align:center;">
						-----------------------------------------------------------------------------
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:100%;background-color:white;text-align:left;">
						<div style="font-size:7px;text-align:center;">
						ACEPTO LOS TÉRMINOS Y CONDICIONES DEL CONTRATO DE TRANSPORTES PUBLICADO EN WWW.ITTSABUS.COM
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:100%;background-color:white;text-align:left;">
						<div style="font-size:7px;text-align:center;">
						-----------------------------------------------------------------------------
						</div>
					</td>
				</tr>
				<tr>
					<td align="center" style="width:100%; background-color:white;text-align:center;">
						<img width="800%" src="$urlComprobanteQr">
					</td>
				</tr>
				<tr>
					<td style="width:100%; background-color:white;text-align:center;">
						<div style="font-size:8px;">
						QR COMPROBANTE
						</div>
					</td>
				</tr>                
				<tr>
					<td style="background-color:white;">
						<div style="font-size:4px; text-align:center;">
							&nbsp;
						</div>
					</td>
				</tr>
				<tr>
					<td style="width:100%;background-color:white;text-align:center;">
						<div style="font-size:8px;">
						REPRESENTACIÓN IMPRESA DE LA $TipoComprobante ELECTRÓNICA
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div style="font-size:7px; text-align:center;">
							Puede consultar este documento en www.ittsabus.com
						</div>
					</td>
				</tr>
			</table>
		EOF;
    $pdf->writeHTML($bloque1, false, false, false, false, '');
    ob_clean();
    $pdf->Output('ticket.pdf', 'I');

    unlink($urlComprobanteQr);
    unlink($urlPedidoQr);
    ?>

</html>