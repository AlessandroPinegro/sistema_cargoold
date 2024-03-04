<?php
include_once __DIR__ . '/vistas/com/util/Seguridad.php';
include_once __DIR__ . '/../../util/Configuraciones.php';
include_once __DIR__ . '/../../util/ObjectUtil.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
include_once __DIR__ . '/../../util/phpqrcode/qrlib.php';

$documentoId = $_POST['documentoIdHidden'];
$dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

$serie = $dataDocumento[0]['serie'];
$numero = $dataDocumento[0]['numero'];
$documentoTipo=$dataDocumento[0]['documento_tipo_descripcion'];
$serieNumero=$dataDocumento[0]['serie_numero'];

//echo $bienUnicoCodigo.'<br>'.$bienDescripcion.'<br>'.$bienCodigo;
//GENERACION DE QR
$rutaComun = "/util/phpqrcode/temp/";
$rutaFisica = __DIR__ . "/../../$rutaComun";
$rutaWeb = Configuraciones::url_base() . $rutaComun;

array_map('unlink', glob("$rutaFisica*.png"));

$archivo = $rutaFisica . $documentoId . ".png";
// outputs image directly into browser, as PNG stream 
$rutaQR = Configuraciones::url_base() . '?token=3&documentoId=' . $documentoId;
QRcode::png($rutaQR, $archivo, 'L', 4);

?>
    <div style="float: left;width: 49%; height: 157px;">
        <table style="width: 100%; font-size: 10px; height: 157px;">
            <tr>
                <td style="text-align: center; width: 150px; height: 157px; padding: 0px;">
                    <img src="<?php echo $rutaWeb . $documentoId . ".png"; ?>" />
                </td>
                <td style="padding: 0px;">
                    <table style="font-size: 10px; height: 157px;">
                        <tr>
                            <td  style="height: 15px; padding: 0px;"></td>
                        </tr>
                        <tr>
                            <td  style="height: 57px; padding: 0px;">
                                <!--<img src="../../vistas/images/logoBHQR.png" />-->
                            </td>
                        </tr>
                        <tr>
                            <td  style="height: 1px; padding: 0px;"></td>
                        </tr>
                        <tr>
                            <td style="padding: 0px;">
                                <table style="font-size: 10px; font-family: 'Tahoma'">
                                    <tr>
                                        <td style="padding: 0px;">
                                            DOCUMENTO:
                                        </td>
                                        <td style="padding: 0px;">
                                            <?php echo strtoupper($documentoTipo) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0px;">
                                            S|N:
                                        </td>
                                        <td style="padding: 0px;">
                                            <?php echo $serieNumero ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
