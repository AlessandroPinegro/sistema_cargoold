<?php
// date_default_timezone_set("America/Lima");

require_once('../../../util/Configuraciones.php');
// require_once('../../../controlador/core/ControladorBase.php');
require_once('../../../modeloNegocio/almacen/DocumentoNegocio.php');
require_once('../../../modelo/almacen/MovimientoBien.php');
require_once('../../../controlador/almacen/MovimientoAtencionControlador.php');
require_once('../../../modeloNegocio/almacen/MovimientoAtencionNegocio.php');
require_once('../../../modeloNegocio/almacen/EmpresaNegocio.php');

isset($_GET["id"]) ? $idDespacho = $_GET["id"] : "";
isset($_GET["e"]) ? $existe = $_GET["e"] : 0;
isset($_GET["serie"]) ? $serie = $_GET["serie"] :  "";
isset($_GET["numero"]) ? $numero = $_GET["numero"] :  "";

if (ObjectUtil::isEmpty($_GET["id"]) || $idDespacho == "") {
	throw new WarningException("No se encontró el documento");
}
// $datosDocumento = new ObjectUtil();
$datosDocumento = MovimientoNegocio::create()->getManifiestoReparto($idDespacho);
// var_dump ($datosDocumento) ;
if (ObjectUtil::isEmpty($datosDocumento)) {
	throw new WarningException("No se encontró el documento");
}
if (ObjectUtil::isEmpty($serie)) {
	throw new WarningException("error al ingresar la serie");
}
if (ObjectUtil::isEmpty($numero)) {
	throw new WarningException("error al ingresar el numero");
}
$empresaId = $datosDocumento[0]["empresa_id"];
$dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);

$numero=$numero-1;
$aliasEmpresa =  $dataEmpresa[0]["alias"];
$rucEmpresa =  $dataEmpresa[0]["ruc"];
$nombreEmpresa =  $dataEmpresa[0]["razon_social"];
$direccionEmpresa =  $dataEmpresa[0]["direccion"];

$agenciaOrigen = $datosDocumento["origen"];
$agenciaDestino = $datosDocumento["destino"];

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

$medidas = array(234, 280); // Ajustar aqui segun los milimetros necesarios;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $medidas, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(5, 5, 5);
$pdf->startPageGroup();

$pdf->AddPage();

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
$cantidadB=0;
$TotalBoleta=0;
$pesoB=0;
$cantidad=0;
$Hayboletas=false;
foreach ($datosDocumento as $key => $item) {
	$agenciaOrigen = $item["origen"];
	$agenciaDestino = $item["destino"];
	$fechaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), 0, -8);
	$anio = substr($fechaSalida,2,2);
	$mes = substr($fechaSalida,5,2);
	$dia = substr($fechaSalida,8,2);
	// $horaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), -8);

	$DepartamentoOrigen = MovimientoNegocio::create()->getDepartamento($item["ubigeo_origen"]);
	$DepartamentoDestino = MovimientoNegocio::create()->getDepartamento($item["ubigeo_destino"]);

	$DepartOrigen = $DepartamentoOrigen[0]["departamento"];
	$DepartDestino = $DepartamentoOrigen[0]["departamento"];
	$DistritoOrigen = $DepartamentoDestino[0]["distrito"];
	$DistritoDestino = $DepartamentoDestino[0]["distrito"];
    $remitente=$item["remitente"];
	$destinatario=$item["destinatario"];
	$destinatarioDocumento=$item["documento_destinatario"];
	$remitenteDocumento=$item["documento_remitente"];
	$vehiculo=$item["flota_marca"].' '.$item["flota_placa"];
	$total=number_format($item["total"],2);
	$cantidad=number_format($item["cantidad"],2);
	$peso=number_format($item["bien_peso"],4);
	$bultos='BULTO';
	$moneda_id=$item["moneda_id"];
	$usuario_creacion=1;
	$agencia_id=$item["origen_id"];
	$agencia_destino_id=$item["agencia_destino_id"];
	$vehiculo_id=$item["vehiculo_id"];

	if($cantidad>1){
		$bultos='BULTOS';
	}
	$tipoDocumentoDestinatario='';
	if($item["tipo_documento_destinatario"]==4){
		$tipoDocumentoDestinatario='RUC';
	}else if($item["tipo_documento_destinatario"]==2){
		$tipoDocumentoDestinatario='DNI';
	}
	// $fechaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), 0, -8);
	// $horaSalida = substr($item["fecha_salida"]->format('Y-m-d H:i:s'), -8);
	// $horaSalida = date("g:i a", strtotime($horaSalida));
	// $busNumero = $item["flota_numero"];
	// $busPlaca = $item["flota_placa"];
	// $conductor = $item["conductor"];
	// $copiloto = $item["copiloto"];
	if ($item["TipoComprobante"] == 6) {
		$remitenteB=substr($nombreEmpresa, 0, 30);
		$cantidadB=number_format($cantidadB + $cantidad,2);
		$pesoB =$pesoB+ $peso;
		$TotalBoleta = number_format($TotalBoleta + strval($item["total"]),2);
		$moneda_idB=$item["moneda_id"];
		$usuario_creacionB=1;
		$agencia_idB=$item["origen_id"];
		$agencia_destino_idB=$item["agencia_destino_id"];
		$vehiculo_idB=$item["vehiculo_id"];
		$fechaSalidaB=$fechaSalida;
		$Hayboletas=true;
	} else if ($item["TipoComprobante"] == 7) {
		if($existe==0){
			$numero=$numero+1;
			   echo $idDespacho.' '.$serie.' '.$numero.' '.$moneda_id.' '.$usuario_creacion.' '.$agencia_id.' '.$agencia_id.' '.$vehiculo_id;
          MovimientoNegocio::create()->insertGuiaRTM($idDespacho,$serie,$numero,$moneda_id,$usuario_creacion,$agencia_id,$agencia_id,$vehiculo_id);

		}
	$bloque2 = <<<EOF
		<table style="font-size:9px;">

		<tr>
			<td  style="width:100%;height:76px">

			</td>

		</tr>
		<tr>
		<td  style="width:72%;height:60px">

		</td>
		<td style="font-size:16px;color:#333; background-color:white; text-align:left;width:28%;height:60px">
		 $numero
		</td>
	</tr>
	<tr>
	<td  style="width:26%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:15%;height:20px">
	 $agenciaOrigen
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$dia
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$mes
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	&nbsp;&nbsp;&nbsp;&nbsp;$anio
	</td>
	<td  style="width:20%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:24%;height:20px">
	 0072119
	</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoOrigen
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartOrigen
</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoDestino
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartDestino
</td>
</tr>
<tr>
<td  style="width:100%;height:24px">

</td>
</tr>

<tr>
<td  style="width:10%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:38%;">
 $remitente
</td>
<td  style="width:4%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:48%;">
 $destinatario
</td>
</tr>
<tr>
<td  style="width:21%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:27%;">
 $remitenteDocumento
</td>
<td  style="width:19%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:33%;">
 $tipoDocumentoDestinatario $destinatarioDocumento
</td>
</tr>
<tr>
<td  style="width:100%;height:37px">

</td>
</tr>
<tr>
<td style="width:25%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:17%;height:7px">
 $vehiculo
</td>
<td style="width:17%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;height:7px">
	9845634
</td>
</tr>
<tr>
<td style="width:30%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;height:7px">
	3568522
</td>
<td style="width:20%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:14%;height:7px">
	LIC-B255411
</td>
<td  style="width:4%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:23%;height:7px">
 $total
</td>
</tr>

<tr>
<td  style="width:100%;height:37px">

</td>
</tr>

<tr>
<td  style="width:8%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:center;width:11%;">
 $cantidad
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;">
 $bultos
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $peso
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $total
</td>
</tr>

	</table>

EOF;

		$pdf->writeHTML($bloque2, false, false, false, false, '');

		$pdf->AddPage();
	} else if ($item["TipoComprobante"] == 191) {
		if($existe==0){
			MovimientoNegocio::create()->insertGuiaRTM($idDespacho,$serie,$numero,$moneda_id,$usuario_creacion,$agencia_id,$agencia_id,$vehiculo_id);
		}
		$bloque3 = <<<EOF
	<table style="font-size:9px;">

		<tr>
			<td  style="width:100%;height:76px">

			</td>

		</tr>
		<tr>
		<td  style="width:72%;height:60px">

		</td>
		<td style="font-size:16px;color:#333; background-color:white; text-align:left;width:28%;height:60px">
		 $numero
		</td>
	</tr>
	<tr>
	<td  style="width:26%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:15%;height:20px">
	 $agenciaOrigen
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$dia
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$mes
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	&nbsp;&nbsp;&nbsp;&nbsp;$anio
	</td>
	<td  style="width:20%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:24%;height:20px">
	 $numero
	</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoOrigen
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartOrigen
</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoDestino
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartDestino
</td>
</tr>
<tr>
<td  style="width:100%;height:24px">

</td>
</tr>

<tr>
<td  style="width:10%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:38%;">
 $remitente
</td>
<td  style="width:4%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:48%;">
 $destinatario
</td>
</tr>
<tr>
<td  style="width:21%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:27%;">
 $remitenteDocumento
</td>
<td  style="width:19%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:33%;">
 $tipoDocumentoDestinatario $destinatarioDocumento
</td>
</tr>
<tr>
<td  style="width:100%;height:37px">

</td>
</tr>
<tr>
<td style="width:25%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:17%;height:7px">
 $vehiculo
</td>
<td style="width:17%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;height:7px">
	9845634
</td>
</tr>
<tr>
<td style="width:30%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;height:7px">
	3568522
</td>
<td style="width:20%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:14%;height:7px">
	LIC-B255411
</td>
<td  style="width:4%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:23%;height:7px">
 $total
</td>
</tr>

<tr>
<td  style="width:100%;height:37px">

</td>
</tr>

<tr>
<td  style="width:8%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:center;width:11%;">
 $cantidad
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;">
 $bultos
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $peso
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $total
</td>
</tr>

	</table>

EOF;

		$pdf->writeHTML($bloque3, false, false, false, false, '');

		$pdf->AddPage();
	}
}
if($Hayboletas){

if($cantidadB>1){
	$bultos='BULTOS';
}



if($existe==0){
	$numero=$numero+1;
	MovimientoNegocio::create()->insertGuiaRTM($idDespacho,$serie,$numero,$moneda_idB,$usuario_creacionB,$agencia_idB,$agencia_idB,$vehiculo_idB);
}

$bloque4 = <<<EOF

<table style="font-size:9px;">

		<tr>
			<td  style="width:100%;height:76px">

			</td>

		</tr>
		<tr>
		<td  style="width:72%;height:60px">

		</td>
		<td style="font-size:16px;color:#333; background-color:white; text-align:left;width:28%;height:60px">
		 $numero
		</td>
	</tr>
	<tr>
	<td  style="width:26%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:15%;height:20px">
	 $agenciaOrigen
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$dia
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	$mes
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:5%;height:20px">
	&nbsp;&nbsp;&nbsp;&nbsp;$anio
	</td>
	<td  style="width:20%;height:20px">
         
	</td>
	<td style="font-size:9px;color:#333; background-color:white; text-align:left;width:24%;height:20px">
	 $numero
	</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoOrigen
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartOrigen
</td>
</tr>
<tr>
<td  style="width:29%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
 $DistritoDestino
</td>
<td  style="width:12%;height:7px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:41%;height:7px">
 $DepartDestino
</td>
</tr>
<tr>
<td  style="width:100%;height:24px">

</td>
</tr>

<tr>
<td  style="width:10%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:38%;">
 $aliasEmpresa
</td>
<td  style="width:4%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:48%;">
 $aliasEmpresa
</td>
</tr>
<tr>
<td  style="width:21%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:27%;">
 $rucEmpresa
</td>
<td  style="width:19%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:33%;">
 RUC $rucEmpresa
</td>
</tr>
<tr>
<td  style="width:100%;height:37px">

</td>
</tr>
<tr>
<td style="width:25%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:17%;height:7px">
 $vehiculo
</td>
<td style="width:17%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;height:7px">
	9845634
</td>
</tr>
<tr>
<td style="width:30%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;height:7px">
	3568522
</td>
<td style="width:20%;height:7px">

</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:14%;height:7px">
	LIC-B255411
</td>
<td  style="width:4%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:23%;height:7px">
 $TotalBoleta
</td>
</tr>

<tr>
<td  style="width:100%;height:37px">

</td>
</tr>

<tr>
<td  style="width:8%;">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:center;width:11%;">
 $cantidadB
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:40%;">
 $bultos
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $pesoB
</td>
<td  style="width:3%;height:8px">
	 
</td>
<td style="font-size:8px;color:#333; background-color:white; text-align:left;width:12%;">
 $TotalBoleta
</td>
</tr>

	</table>
EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

}
ob_clean();
$pdf->Output('manifiesto.pdf', 'I');
