<?php
include_once __DIR__ . '/vistas/com/util/Seguridad.php';
include_once __DIR__ . '/../../util/Configuraciones.php';
include_once __DIR__ . '/../../util/ObjectUtil.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/BienUnicoNegocio.php';
include_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
include_once __DIR__ . '/../../util/DateUtil.php';

//variables
$criterios = $_POST['criteriosBusqueda'];

$elemntosFiltrados = 10000;
$limite = 1000;
$order = null;
$columns = null;
$start = 0;
$data = BienUnicoNegocio::create()->obtenerDataBienUnicoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start);

//SI ESTA SELECCIONADO Y SI DESDE Y HASTA ESTAN LLENOS LIMITAR SEGUN EL RANGO DESDE - HASTA.
$codDesde = $criterios[0]['codDesde'];
$codHasta = $criterios[0]['codHasta'];
if (count($criterios[0]['bien']) == 1 && ($codDesde!='' || $codHasta!='')) {
    $numDesde=substr($codDesde, -5)*1;
    $numHasta=substr($codHasta, -5)*1;
    $dataTemp = array();
    foreach ($data as $index => $item) {
        $correlativoCod=substr($item['codigo_unico'], -5)*1;
        
        if($codDesde!='' && $codHasta!=''){
            if($correlativoCod>=$numDesde && $correlativoCod<=$numHasta){
                array_push($dataTemp, $item);
            }
        }else if($codDesde!='' && $codHasta==''){
            if($correlativoCod>=$numDesde){
                array_push($dataTemp, $item);
            }            
        }else{
            if($correlativoCod<=$numHasta){
                array_push($dataTemp, $item);
            }            
        }
    }
    
    if(count($dataTemp)==0){
        echo "No se encuentran productos únicos en el intérvalo de códigos ingresados.";
        return;        
    }
    
    $data=$dataTemp;
}
//FIN DESDE - HASTA

$posInicial = $criterios[0]['posInicial'];

if ($limite < count($data)) {
    echo "El número de códigos de productos a generar supera el límite = " . $limite . "";
    return;
}

//GENERACION DE QR
$rutaComun = "/util/phpqrcode/temp/";
$rutaFisica = __DIR__ . "/../../$rutaComun";
$rutaWeb = Configuraciones::url_base() . $rutaComun;
$rutaSGI = Configuraciones::url_base();

array_map('unlink', glob("$rutaFisica*.png"));

//IMPRIMIR EN BLANCO
$ind = 0;
if ($posInicial > 1 && $posInicial<=14) {
    $ind = $posInicial - 1;
    for ($i = 0; $i < $posInicial-1; $i++) {
        $estiloDiv = 'float: left;width: 49%;';
        if (($i + 1) % 2 == 0) {
            $estiloDiv = 'float: right;width: 49%;';
        }
        ?>
        <div style="<?php echo $estiloDiv ?>; height: 98px;"></div>
        <?php
    }
}


foreach ($data as $index => $item) {
//    if($index>10){
//        return;
//    }
    $bienUnicoId = $item['bien_unico_id'];
    $bienUnicoCodigo = $item['codigo_unico'];
    $bienDescripcion = $item['bien_descripcion'];
    $bienCodigo = $item['bien_codigo'];

    $archivo = $rutaFisica . $bienUnicoId . ".png";
//    $rutaBienUnico = Configuraciones::url_base() . '?token=3&id=' . $bienUnicoId . '&codigo=' . $bienUnicoCodigo;    
    $rutaBienUnico = Configuraciones::url_base() . '?token=3&id=' . $bienUnicoId;
    QRcode::png($rutaBienUnico, $archivo, 'L', 2, 1);

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


    <?php
    if (($index + $ind + 1) % 22 == 0) {
    ?>
        <div style="float: left;width: 49%;; height: 40px;"></div>
        <div style="float: right;width: 49%;; height: 40px;"></div>
    <?php
    }
}