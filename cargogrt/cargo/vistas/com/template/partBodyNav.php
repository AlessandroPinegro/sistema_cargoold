<?php
//session_start();
//$id_perfil = $_SESSION['perfil_id'];
include_once __DIR__ . '/../../../controlador/almacen/PerfilControlador.php';
?>

<script>
    function active(j, k) {
        $("ul li").removeClass("active");
        $("ul #l" + k).addClass("active");
        $("ul li ul li").removeClass("active");
        $("ul li ul #m" + j).addClass("active");
    }
    function calr() {
        $("div").remove("#ui-datepicker-div");
        $("div").remove("#ui-datepicker-div");
    }
</script>
<nav class="navigation">
    <ul id="menuEmpresa" class="list-unstyled">
        
    </ul>
</nav>


