<?php
include_once __DIR__ . '/util/Util.php';
include_once __DIR__ . '/controlador/core/ControladorParametros.php';
include_once __DIR__ . '/controlador/almacen/AlmacenIndexControlador.php';
include_once __DIR__ . '/controlador/almacen/UsuarioControlador.php';
if (is_null($_POST['usu_email']) == false) {
    $usu_email = $_POST['usu_email'];
    $usu = new UsuarioControlador();
    $response = $usu->recuperarContrasena($usu_email);
    if (ObjectUtil::isEmpty($response[0]['email'])) {
        header("location:recuperar_cuenta.php?error=si");
    } else {
        header("location:recuperar_cuenta.php?error=no");
    }
}
?>
<html lang="en">

<!-- Mirrored from coderthemes.com/velonic/admin/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:17:26 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="vistas/images/icono_ittsa.ico">

    <title>ITTSA CARGO - INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL</title>

    <!-- Google-Fonts -->
    <!-- Bootstrap core CSS -->
    <link href="vistas/libs/imagina/css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="admin/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="vistas/libs/imagina/css/bootstrap-reset.css" rel="stylesheet">

    <!--Animation css-->
    <link href="vistas/libs/imagina/css/animate.css" rel="stylesheet">

    <!--Icon-fonts css-->
    <link href="vistas/libs/imagina/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="vistas/libs/imagina/assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

    <!--Morris Chart CSS -->
    <!--<link rel="stylesheet" href="admin/assets/morris/morris.css">-->


    <!-- Custom styles for this template -->
    <link href="vistas/libs/imagina/css/style.css" rel="stylesheet">
    <link href="vistas/libs/imagina/css/helper.css" rel="stylesheet">
    <link href="vistas/libs/imagina/css/style-responsive.css" rel="stylesheet" />

</head>


<body ng-app="almacenLoginApp" style="background: url('vistas/images/fondoIttsa.png') no-repeat center center fixed;
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;">

    <div class="wrapper-page animated fadeInDown">
        <div class="panel panel-color panel-info" style="background-color: #f77816; border-top: 1px solid #f77816;">
            <div class="panel-heading" style="background-color: #f77816; padding: 10px">
                <h3 class="text-center m-t-10"> Recuperar contrase&ntilde;a</h3>
            </div>

            <form class="form-horizontal m-t-20" action="recuperar_cuenta.php" method="POST">
                 

                <div class="form-group ">
                    <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #ffffff;"><i class="fa fa-user" style="color: #f77816;"></i></span>
                                <input class="form-control" type="text" id="usu_email" name="usu_email" placeholder="Ingrese usuario o email" value="">
                            </div>
                        </div> 
                    </div>
                <div class="form-group ">
                    <div class="col-xs-12">

                    </div>
                </div>
                <div class="form-group text-right">
                    <div class="col-xs-12">
                        <a href="<?php echo Configuraciones::url_base(); ?>login.php" style="color:#ffffff !important;">Regresar al inicio de sesión&nbsp;&nbsp;&nbsp;</a>
                        <button class="btn btn-info w-md" id="btn_recuperar" name="btn_recuperar" style="background-color: #ffffff; border: 1px solid #ffffff;  color:#f77816 !important;" type="submit"><i class="fa fa-send-o"></i> Enviar</button>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET['error'])) {
                echo ("<br />\n");
                if ($_GET['error'] == "si") {
                    echo ("<font color='red'>Error: Usuario o email no registrado</font>\n");
                } elseif ($_GET['error'] == "no") {
                    echo ("<font color='green'>Datos enviados al email</font>\n");
                }
            }
            ?>
        </div>
    </div>

    <?php
    include_once __DIR__ . '/vistas/com/template/partBodyMainContentEnds.php';
    ?>
    <!--<script src="<?php echo Configuraciones::url_base(); ?>vistas/recuperarCuenta.js"></script>-->
</body>

</html>