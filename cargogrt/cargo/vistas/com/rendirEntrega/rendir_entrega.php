<?php
include_once __DIR__ . '/../../../util/Configuraciones.php';
$urlHost = Configuraciones::url_base();
$carpetaEar = Configuraciones::CARPETA_SGI_ADMIN;

$urlEar = $urlHost . $carpetaEar;
?>

<iframe src="<?php echo $urlEar ?>/index.php" style="width: 100%;  border: 0;" onload="resizeIframe(this)">

</iframe>
<script>
    $(document).ready(function () {
        loaderClose();

        var urlEar = '<?php echo $urlEar ?>';

        window.open(urlEar + '/index.php', '_blank');
    });

    function resizeIframe(obj) {
        obj.style.height = (obj.contentWindow.document.body.scrollHeight + 50) + 'px';
    }
</script>