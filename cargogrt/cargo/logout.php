<?php
session_start();
include_once __DIR__.'/modelo/itec/Usuario.php';
include_once __DIR__.'/util/Util.php';
?>
<html>
<head>
	<meta http-equiv="Refresh" content="1;url=index.php">
	<style type="text/css">
		body{font-size: 10pt; font-family: arial,helvetica}
	</style>
</head>
<?php
date_default_timezone_set("America/Lima");
ob_start();

?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<div align="center">
<!--	<img src="images/gracias_por_tu_visita.jpg" align = middle><br>-->
	<p>Cerrando sesi&oacute;n <img src="vistas/images/waitbar18-0.gif"></p>
</div>
<?php
//session_start();
if(isset($_SESSION['id_usuario'])){
    $id_usuario = $_SESSION['id_usuario'];
     $time = time();
     $fecha_fin =  date("y-m-d H:i:s", $time);
}

Usuario::create()->updateFechaCookie($id_usuario=null,$fecha_fin=null);
session_destroy();
Util::borrarCookie();
//include 'datos_cerrar_bd.php';
?>

</html>