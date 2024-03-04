<?php
include_once __DIR__ . '/vistas/com/util/Seguridad.php';
include_once __DIR__ . '/../../util/Configuraciones.php';
include_once __DIR__ . '/../../util/ObjectUtil.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/BienUnicoNegocio.php';
include_once __DIR__ . '/../../util/phpqrcode/qrlib.php';

$bienUnicoId = $_POST['bienUnicoIdHidden'];
$posInicial = $_POST['posInicialHidden'];
$dataBienUnico = BienUnicoNegocio::create()->obtenerBienUnicoXId($bienUnicoId);

$bienUnicoCodigo = $dataBienUnico[0]['bien_unico_codigo'];
$bienDescripcion = $dataBienUnico[0]['bien_descripcion'];
$bienCodigo = $dataBienUnico[0]['bien_codigo'];

//echo $bienUnicoCodigo.'<br>'.$bienDescripcion.'<br>'.$bienCodigo;
//GENERACION DE QR
$rutaComun = "/util/phpqrcode/temp/";
$rutaFisica = __DIR__ . "/../../$rutaComun";
$rutaWeb = Configuraciones::url_base() . $rutaComun;

array_map('unlink', glob("$rutaFisica*.png"));

$archivo = $rutaFisica . $bienUnicoId . ".png";
// outputs image directly into browser, as PNG stream 
$rutaBienUnico = Configuraciones::url_base() . '?token=3&id=' . $bienUnicoId;
QRcode::png($rutaBienUnico, $archivo, 'L', 2, 1);

//IMPRIMIR EN BLANCO
$ind = 0;
if ($posInicial > 1 && $posInicial <= 14) {
    $ind = $posInicial - 1;
    for ($i = 0; $i < $posInicial - 1; $i++) {
        $estiloDiv = 'float: left;width: 49%;';
        if (($i + 1) % 2 == 0) {
            $estiloDiv = 'float: right;width: 49%;';
        }
        ?>
        <div style="<?php echo $estiloDiv ?>; height: 98px;"></div>
        <?php
    }
}

    $estiloDiv = 'float: left;width: 49%;';
    $anchoTabla = '399px';
    $espacioIzq = '<td  style="height: 98px; padding: 0px; width: 112px"></td>';
    if (($index + $ind + 1) % 2 == 0) {
        $estiloDiv = 'float: right;width: 49%;';
        $anchoTabla = '285px';
        $espacioIzq = '';
    }
    
    ?>
    <div style="<?php echo $estiloDiv ?>; height: 98px;">
    <!--SI ES ISQUIERDA DARLE MAS ANCHO 285px + 114px-->
    <!--<table style="font-size: 8px; height: 98px;width: 285px">-->
    <table style="font-size: 8px; height: 98px;width: <?php echo $anchoTabla ?>">
        <tbody style="height: 98px;">
            <tr>
                <td  style="height: 11px; padding: 0px;"></td>              
            </tr>
            <tr style="height: 86px;">
                <td style="padding: 0px;width: <?php echo $anchoTabla ?>">
                    <table style="font-size: 8px; height: 98px;">
                        <tbody style="height: 98px;">
                            <?php echo $espacioIzq ?>
                            <td  style="height: 98px; padding: 0px; width: 98px">
                                <!--<img src="vistas/images/logoBHQR.png" />-->
                            </td>
                            <td  style="height: 98px; padding: 0px; width: 102px">
                                <table style="font-size: 8px; font-family: 'Tahoma'">
                                    <tr>
                                        <td style="padding: 0px;font-size: 9px;">
                                            <b>NP: <?php echo $bienCodigo ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0px;">
                                            <?php echo $bienDescripcion ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0px;">
                                            CI:<?php echo $bienUnicoCodigo ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align: center; width: 68px; height: 98px; padding: 0px;">
                                <img src="<?php echo $rutaWeb . $bienUnicoId . ".png"; ?>" />
                            </td>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
