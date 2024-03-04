<?php
// date_default_timezone_set("America/Lima");

require_once('../../../util/Configuraciones.php');
// require_once('../../../controlador/core/ControladorBase.php');
require_once('../../../modeloNegocio/almacen/DocumentoNegocio.php');
require_once('../../../modelo/almacen/MovimientoBien.php');
require_once('../../../modeloNegocio/almacen/MovimientoAtencionNegocio.php');

isset($_GET["id"]) ? $idManifiesto = $_GET["id"] : "";

if (ObjectUtil::isEmpty($_GET["id"]) || $idManifiesto == "") {
	throw new WarningException("No se encontró el documento");
}
// $datosDocumento = new ObjectUtil();
$datosDocumento = MovimientoNegocio::create()->getManifiesto($idManifiesto);
// var_dump ($datosDocumento) ;
if (ObjectUtil::isEmpty($datosDocumento)) {
	throw new WarningException("No se encontró el documento");
}


// $nombre_impresora = "POS-80-series";
// $connector = new WindowsPrintConnector($nombre_impresora);
// $printer = new Printer($connector);
// $printer->setTextSize(1, 1);
// $printer->feed();
//TRAEMOS LA INFORMACIÓN DE LA VENTA
// $texto = "12312312|" . $docCod . "|" . $docAbr . "|" . $nroDocum . "|" . $impuesto . "|" . $total . "|" . $fecha . "|1||";


$agenciaOrigen = $datosDocumento["origen"];
$agenciaDestino = $datosDocumento["destino"];

//REQUERIMOS LA CLASE TCPDF
$existeB = false;
$existeF = false;
$existeNV = false;
$detalleBoleta = '';
$detallefactura = '';
$detalleNV = '';
$tableFactura = '';
$tableBoleta = '';
$tablenNv = '';
$cabecera = '';
$cantFactura = 0;
$TotalenFactura = 0;
$cantpaqueFactura = 0;





require_once('../../../controlador/commons/TCPDF-main/tcpdf.php');


// $medidas = array(75, 600); // Ajustar aqui segun los milimetros necesarios;
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// $pdf->SetMargins(5, 5,5);
$pdf->startPageGroup();

$pdf->AddPage();
// $printer->setJustification(Printer::JUSTIFY_CENTER);
// $printer->text("NOMBRE EMPRESA" . "\n");
// $printer->text("Dirección De Empresa: Av. Sanchez cerro 834 aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa" . "\n");
// $printer->text("RUC 123423423" . "\n");
// $printer->text("MI NOMBREEEEEE DE ESTACION" . "\n");
// $printer->text("Dirección: DE ESTACIONNNNNNNNNNNNNNNNNNNNNNNNN" . "\n");
// $printer->text("DEPARTAMENTO - CIUDAD" . "\n");

// ---------------------------------------------------------

$existeB = false;
$existeF = false;
$existeNV = false;
$detalleBoleta = '';
$detallefactura = '';
$detalleNV = '';
$tableFactura = '';
$tableBoleta = '';
$tablenNv = '';
$cabecera = '';
$cantFactura = 0;
$TotalenFactura = 0;
$cantpaqueFactura = 0;
$cantBoleta = 0;
$TotalenBoleta = 0;
$cantpaqueBoleta = 0;
$cantNV = 0;
$TotalenNV = 0;
$cantpaqueNV = 0;
$cntarra = count($datosDocumento);
foreach ($datosDocumento as $key => $item) {
	$fechaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), 0, -8);
	$horaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), -8);
	$horaSalida = date("g:i a", strtotime($horaSalida));
	$busNumero = $item["flota_numero"];
	$busPlaca = $item["flota_placa"];
	$conductor = $item["conductor"];
	$copiloto = $item["copiloto"];
	$ind =   $key + 1;
	if ($ind >= $cntarra) {
		$ind = $key;
	}

	if ($item["agencia_destino_id"] == $datosDocumento[$ind]["agencia_destino_id"]) {
		if ($ind > $key) {
			if ($item["TipoComprobante"] == 7) { //factura
				$serieF =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantFactura = $cantFactura + 1;
				$TotalenFactura = $TotalenFactura + strval($item["total"]);
				$cantpaqueFactura = $cantpaqueFactura + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}
				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detallefactura .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieF . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeF = true;
			} else if ($item["TipoComprobante"] == 6) { //BOLETA
				$serieB =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantBoleta = $cantBoleta + 1;
				$TotalenBoleta = $TotalenBoleta + strval($item["total"]);
				$cantpaqueBoleta = $cantpaqueBoleta + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}

				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detalleBoleta .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieB . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeB = true;
			} else if ($item["TipoComprobante"] == 191) { //NOTA DE VENTA
				$serieNV =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantNV = $cantNV + 1;
				$TotalenNV = $TotalenNV + strval($item["total"]);
				$cantpaqueNV = $cantpaqueNV + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}

				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detalleNV .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieNV . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeNV = true;
			}
			if ($existeF) {
				$tableFactura = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>FACTURA</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">FACTURA</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detallefactura . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Facturas: ' . $cantFactura . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueFactura . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenFactura, 2) . '
	</td>
	</tr>
	</table><br>';
			}
			if ($existeB) {
				$tableBoleta = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>BOLETAS</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">BOLETA</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detalleBoleta . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Boletas x encimiendas: ' . $cantBoleta . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueBoleta . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenBoleta, 2) . '
	</td>
	</tr>
	</table><br>';
			}
			if ($existeNV) {
				$tablenNv = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>NOTAS DE VENTA</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">NV</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detalleNV . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Notas de Venta: ' . $cantNV . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueNV . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenNV, 2) . '
	</td>
	</tr>
	</table><br>';
			}
		} else {
			if ($item["TipoComprobante"] == 7) { //factura
				$serieF =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantFactura = $cantFactura + 1;
				$TotalenFactura = $TotalenFactura + strval($item["total"]);
				$cantpaqueFactura = $cantpaqueFactura + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}

				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detallefactura .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieF . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeF = true;
			} else if ($item["TipoComprobante"] == 6) { //BOLETA
				$serieB =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantBoleta = $cantBoleta + 1;
				$TotalenBoleta = $TotalenBoleta + strval($item["total"]);
				$cantpaqueBoleta = $cantpaqueBoleta + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}

				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detalleBoleta .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieB . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeB = true;
			} else if ($item["TipoComprobante"] == 191) { //NOTA DE VENTA
				$serieNV =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
				$cantNV = $cantNV + 1;
				$TotalenNV = $TotalenNV + strval($item["total"]);
				$cantpaqueNV = $cantpaqueNV + strval($item["cantidad"]);
				if ($item["modalidad_id"] == 75) {
					$modalidadDescipcion = 'PC/ENTREGA';
				} else {
					$modalidadDescipcion = '';
				}
				if ($item["modalidad_id"] == 77) {
					$direccionDestino = 'OFICINA';
				} else {
					$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

					if ($text_width < 86) {
						$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
					} else {
						$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
					}
				}

				$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
				if ($text_width_destinat < 80) {
					$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
				} else {
					$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
				}

				$detalleNV .= '<tr>
		<td style=" background-color:white; width:10%; text-align:left">' . $serieNV . '-' . $item["nro_numero"] . '</td>
		<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
		<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
		<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
		<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
		<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
	</tr>';
				$existeNV = true;
			}
			if ($existeF) {
				$tableFactura = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>FACTURA</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">FACTURA</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detallefactura . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Facturas: ' . $cantFactura . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueFactura . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenFactura, 2) . '
	</td>
	</tr>
	</table><br>';
			}
			if ($existeB) {
				$tableBoleta = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>BOLETAS</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">BOLETA</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detalleBoleta . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Boletas x encimiendas: ' . $cantBoleta . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueBoleta . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenBoleta, 2) . '
	</td>
	</tr>
	</table><br>';
			}
			if ($existeNV) {
				$tablenNv = '<table style="font-size:7.5px;padding:2px 0px;">
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		<b>NOTAS DE VENTA</b>
		</td>
		</tr>
		<br>
		<tr>
		<td style="color:#333; background-color:white; text-align:left">
		ENCOMIENDAS
		</td>
		</tr>	<table style="font-size:8px; padding:5px 0px;">
	
		<tr>
		
		<td style=" background-color:white; width:10%; text-align:left">NV</td>
		<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
		<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
		<td style=" background-color:white; width:11%; text-align:center"></td>
		<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
		<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
		</tr>
	
	</table>' . $detalleNV . '
	<br>
	<tr>
	<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
	Total de Notas de Venta: ' . $cantNV . '
	</td>
	<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
	Total de Encomiendas: ' . $cantpaqueNV . '
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
	Total: ' . number_format($TotalenNV, 2) . '
	</td>
	</tr>
	</table><br>';
			}

			$bloque11 = <<<EOF

	<table style="font-size:9px; padding:5px 0px;">

		<tr>
			<td style="font-size:12px;color:#333; background-color:white; text-align:center">
              INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS S.R.L
			</td>
		</tr>
		<tr>
			<td style="font-size:12px;color:#333; background-color:white; text-align:center">
              MANIFIESTO DE ENCOMIENDAS
			</td>
		</tr>
		<tr>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		  Oficina Origen: $item[origen]
		</td>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		Turno: $horaSalida
	  </td>
	</tr>
	<tr>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		  Oficina Destino:$item[destino]
		</td>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		Fecha de Envio: $fechaSalida
	  </td>
	</tr>
	<tr>
	<td style="color:#333; background-color:white; width:10%;text-align:left">
	  Bus: $busNumero
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left">
	Placa: $busPlaca
  </td>
  <td style="color:#333; background-color:white; width:38%;text-align:left">
	  Piloto: $conductor
	</td>
	<td style="color:#333; background-color:white; width:38%;text-align:left">
	Copiloto: $copiloto
  </td>
</tr>
	</table>


EOF;

			$pdf->writeHTML($bloque11, false, false, false, false, '');

			$bloque2 = <<<EOF
			<div style="font-size:4px; text-align:center;">
			&nbsp;
			</div>
$tableBoleta
$tableFactura
$tablenNv


EOF;

			$pdf->writeHTML($bloque2, false, false, false, false, '');
			$cantFactura = 0;
			$TotalenFactura = 0;
			$cantpaqueFactura = 0;
			$cantNV = 0;
			$TotalenNV = 0;
			$cantpaqueNV = 0;
			$detallefactura = '';
			$detalleBoleta = '';
			$detalleNV = '';
		}
	} else {

		if ($item["TipoComprobante"] == 7) { //factura
			$serieF =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
			$cantFactura = $cantFactura + 1;
			$TotalenFactura = $TotalenFactura + strval($item["total"]);
			$cantpaqueFactura = $cantpaqueFactura + strval($item["cantidad"]);
			if ($item["modalidad_id"] == 75) {
				$modalidadDescipcion = 'PC/ENTREGA';
			} else {
				$modalidadDescipcion = '';
			}
			if ($item["modalidad_id"] == 77) {
				$direccionDestino = 'OFICINA';
			} else {
				$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

				if ($text_width < 86) {
					$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
				} else {
					$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
				}
			}

			$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
			if ($text_width_destinat < 80) {
				$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
			} else {
				$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
			}

			$detallefactura .= '<tr>
	<td style=" background-color:white; width:10%; text-align:left">' . $serieF . '-' . $item["nro_numero"] . '</td>
	<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
	<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
	<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
	<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
	<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
</tr>';
			$existeF = true;
		} else if ($item["TipoComprobante"] == 6) { //BOLETA
			$serieB =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
			$cantBoleta = $cantBoleta + 1;
			$TotalenBoleta = $TotalenBoleta + strval($item["total"]);
			$cantpaqueBoleta = $cantpaqueBoleta + strval($item["cantidad"]);
			if ($item["modalidad_id"] == 75) {
				$modalidadDescipcion = 'PC/ENTREGA';
			} else {
				$modalidadDescipcion = '';
			}
			if ($item["modalidad_id"] == 77) {
				$direccionDestino = 'OFICINA';
			} else {
				$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

				if ($text_width < 86) {
					$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
				} else {
					$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
				}
			}

			$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
			if ($text_width_destinat < 80) {
				$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
			} else {
				$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
			}

			$detalleBoleta .= '<tr>
	<td style=" background-color:white; width:10%; text-align:left">' . $serieB . '-' . $item["nro_numero"] . '</td>
	<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
	<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
	<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
	<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
	<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
</tr>';
			$existeB = true;
		} else if ($item["TipoComprobante"] == 191) { //NOTA DE VENTA
			$serieNV =  filter_var($item["nro_serie"], FILTER_SANITIZE_NUMBER_INT);
			$cantNV = $cantNV + 1;
			$TotalenNV = $TotalenNV + strval($item["total"]);
			$cantpaqueNV = $cantpaqueNV + strval($item["cantidad"]);
			if ($item["modalidad_id"] == 75) {
				$modalidadDescipcion = 'PC/ENTREGA';
			} else {
				$modalidadDescipcion = '';
			}
			if ($item["modalidad_id"] == 77) {
				$direccionDestino = 'OFICINA';
			} else {
				$text_width = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));

				if ($text_width < 86) {
					$direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
				} else {
					$direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
				}
			}

			$text_width_destinat = $pdf->GetStringWidth(mb_strtoupper($item["destinatario"], 'UTF-8'));
			if ($text_width_destinat < 80) {
				$destinatario = mb_strtoupper($item["destinatario"], 'UTF-8');
			} else {
				$destinatario = substr(mb_strtoupper($item["destinatario"], 'UTF-8'), 0, 30);
			}

			$detalleNV .= '<tr>
	<td style=" background-color:white; width:10%; text-align:left">' . $serieNV . '-' . $item["nro_numero"] . '</td>
	<td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
	<td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
	<td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
	<td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . substr(mb_strtoupper($item["bien_descripcion"], 'UTF-8'), 0, 13) . ' </td>
	<td style=" background-color:white; width:8%; text-align:center">' . number_format($item["total"], 2) . '</td>
</tr>';
			$existeNV = true;
		}
		if ($existeF) {
			$tableFactura = '<table style="font-size:7.5px;padding:2px 0px;">
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	<b>FACTURA</b>
	</td>
	</tr>
	<br>
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	ENCOMIENDAS
	</td>
	</tr>	<table style="font-size:8px; padding:5px 0px;">

	<tr>
	
	<td style=" background-color:white; width:10%; text-align:left">FACTURA</td>
	<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
	<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
	<td style=" background-color:white; width:11%; text-align:center"></td>
	<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
	<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
	</tr>

</table>' . $detallefactura . '
<br>
<tr>
<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
Total de Facturas: ' . $cantFactura . '
</td>
<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
Total de Encomiendas: ' . $cantpaqueFactura . '
</td>
<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
Total: ' . number_format($TotalenFactura, 2) . '
</td>
</tr>
</table><br>';
		}
		if ($existeB) {
			$tableBoleta = '<table style="font-size:7.5px;padding:2px 0px;">
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	<b>BOLETAS</b>
	</td>
	</tr>
	<br>
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	ENCOMIENDAS
	</td>
	</tr>	<table style="font-size:8px; padding:5px 0px;">

	<tr>
	
	<td style=" background-color:white; width:10%; text-align:left">BOLETA</td>
	<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
	<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
	<td style=" background-color:white; width:11%; text-align:center"></td>
	<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
	<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
	</tr>

</table>' . $detalleBoleta . '
<br>
<tr>
<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
Total de Boletas x encimiendas: ' . $cantBoleta . '
</td>
<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
Total de Encomiendas: ' . $cantpaqueBoleta . '
</td>
<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
Total: ' . number_format($TotalenBoleta, 2) . '
</td>
</tr>
</table><br>';
		}
		if ($existeNV) {
			$tablenNv = '<table style="font-size:7.5px;padding:2px 0px;">
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	<b>NOTAS DE VENTA</b>
	</td>
	</tr>
	<br>
	<tr>
	<td style="color:#333; background-color:white; text-align:left">
	ENCOMIENDAS
	</td>
	</tr>	<table style="font-size:8px; padding:5px 0px;">

	<tr>
	
	<td style=" background-color:white; width:10%; text-align:left">NV</td>
	<td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
	<td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
	<td style=" background-color:white; width:11%; text-align:center"></td>
	<td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
	<td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
	</tr>

</table>' . $detalleNV . '
<br>
<tr>
<td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
Total de Notas de Venta: ' . $cantNV . '
</td>
<td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
Total de Encomiendas: ' . $cantpaqueNV . '
</td>
<td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
Total: ' . number_format($TotalenNV, 2) . '
</td>
</tr>
</table><br>';
		}

		$bloque1 = <<<EOF

	<table style="font-size:9px; padding:5px 0px;">

		<tr>
			<td style="font-size:12px;color:#333; background-color:white; text-align:center">
              INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS S.R.L
			</td>
		</tr>
		<tr>
			<td style="font-size:12px;color:#333; background-color:white; text-align:center">
              MANIFIESTO DE ENCOMIENDAS
			</td>
		</tr>
		<tr>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		  Oficina Origen: $item[origen]
		</td>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		Turno: $horaSalida
	  </td>
	</tr>
	<tr>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		  Oficina Destino:$item[destino]
		</td>
		<td style="color:#333; background-color:white; width:50%;text-align:left">
		Fecha de Envio: $fechaSalida
	  </td>
	</tr>
	<tr>
	<td style="color:#333; background-color:white; width:10%;text-align:left">
	  Bus: $busNumero
	</td>
	<td style="color:#333; background-color:white; width:14%;text-align:left">
	Placa: $busPlaca
  </td>
  <td style="color:#333; background-color:white; width:38%;text-align:left">
	  Piloto: $conductor
	</td>
	<td style="color:#333; background-color:white; width:38%;text-align:left">
	Copiloto: $copiloto
  </td>
</tr>
	</table>


EOF;

		$pdf->writeHTML($bloque1, false, false, false, false, '');

		$bloque2 = <<<EOF
		<div style="font-size:4px; text-align:center;">
                        &nbsp;
						</div>
$tableBoleta
$tableFactura
$tablenNv


EOF;

		$pdf->writeHTML($bloque2, false, false, false, false, '');

		$pdf->AddPage();
		$cantFactura = 0;
		$TotalenFactura = 0;
		$cantpaqueFactura = 0;
		$cantNV = 0;
		$TotalenNV = 0;
		$cantpaqueNV = 0;
		$detallefactura = '';
		$detalleBoleta = '';
		$detalleNV = '';
	}
}



ob_clean();
$pdf->Output('manifiesto.pdf', 'I');
