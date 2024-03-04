<?php
session_start();

include_once __DIR__ . '/util/Util.php';
include_once __DIR__ . '/modelo/itec/Usuario.php';
include_once __DIR__ . '/modeloNegocio/almacen/UsuarioNegocio.php';
include_once __DIR__ . '/modelo/almacen/PerfilUsuario.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = utf8_decode(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = Util::encripta(utf8_encode(filter_var($_POST["password"], FILTER_SANITIZE_STRING)));

    //$response = Usuario::create()->validateLogin($username, $password);
    //    $usu = new UsuarioControlador();

    $response = UsuarioNegocio::create()->validateLogin($username, $password, 'web');
    //    var_dump($response);
    //    exit();
    //    foreach ($response as $campo) {
    //        $fila['usuario'] = $campo['usuario'];
    //        $fila['clave'] = $campo['clave'];
    //        $fila['id'] = $campo['id'];
    //    }
    //    $campo_perfil = PerfilUsuario::create()->getPerfilUsuario($fila['id']);
    //    foreach ($campo_perfil as $campo) {
    //        $fila['perfil_id'] = $campo['perfil_id'];
    //    }
    //
    //    $campo_colaborador = Usuario::create()->colaboradorPorUsuario($fila['id']);
    //    foreach ($campo_colaborador as $campo) {
    //        $fila['id_colaborador'] = $campo['id'];
    //    }
    //    if (!$fila) {
    //        header("location:login.php?error=notfound");
    //        exit;
    //    }
    //
    //    $cup_clase = $fila['usuario'];
    //    $usu_clave = $fila['clave'];
    //    $usu_id = $fila['id'];
    //    $perfil_id = $fila['perfil_id'];
    //    $colaborador_id = $fila['id_colaborador'];
    ////    echo $cup_clase;
    //
    //    $_SESSION['id_usuario'] = $usu_id;
    //    $_SESSION['perfil_id'] = $perfil_id;
    //    $_SESSION['id_colaborador'] = $colaborador_id;

    if (ObjectUtil::isEmpty($response)) {
        header("location:login.php?error=notfound");
        exit;
    } elseif (ObjectUtil::isEmpty($response[0]['opciones'])) {
        header("location:login.php?error=notfoundoption");
        exit;
    }
    //    $usu_clave=$response[0]['clave'];
    //    if (strlen($usu_clave) > 0 && $usu_clave == $password) {
    $_SESSION['ldap_user'] = $username;
    //        $_SESSION['rec_cup_clase'] = $cup_clase;

    Util::crearCookie($username);
    Util::crearCookieToken($response[0][Configuraciones::PARAM_USUARIO_TOKEN]);

    $time = time();
    $fecha_inicio = date("Y-m-d H:i:s", $time);
    $usu_id = $response[0]['id'];
    //        $response2 = Usuario::create()->insertCookie($usu_id, $fecha_inicio);
    //VARIABLES DE SESION AL INICIAR
    $cadena = '';
    if (in_array("arrayGet", $_SESSION)) {
        $VarGet = $_SESSION['arrayGet'];
        $cadena = '';
        if (count($VarGet) > 0 && $VarGet['id'] != null) {
            $cadena = "?token=" . $VarGet['token'] . "&id=" . $VarGet['id'];
        } else if (count($VarGet) > 0 && $VarGet['documentoId'] != null) {
            $cadena = "?token=" . $VarGet['token'] . "&documentoId=" . $VarGet['documentoId'];
        }
    }
    header("location:index.php" . $cadena);
    exit;
    //    } else {
    //        header("location:index.php?error=si");
    //        exit;
    //    }
} else {
?>

    <html lang="en">

    <!-- Mirrored from coderthemes.com/velonic/admin/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:17:26 GMT -->

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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
    <!-- f77816 -->

    <body ng-app="almacenLoginApp" style="background: url('vistas/images/fondoIttsa.png') no-repeat center center fixed;
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;">
        <div class="wrapper-page animated fadeInDown">
            <div class="panel panel-color panel-info" style="background-color: #f77816; border-top: 1px solid #f77816;">
                <div class="panel-heading" style="background-color: #f77816; padding: 10px">

                    <h3 class="text-center m-t-10">
                        <img src="vistas/images/IttsaCargo-Blanco.png" width="160" height="60" />
                        </strong>
                    </h3>

                </div>

                <form class="form-horizontal" action="login.php" method="POST">

                    <div class="form-group ">
                    <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #ffffff;"><i class="fa fa-user" style="color: #f77816;"></i></span>
                                <input class="form-control" type="text" name="username" placeholder="Usuario" value="">
                            </div>
                        </div> 
                    </div>
                    <div class="form-group ">

                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon" style="background-color: #ffffff;"><i class="ion-locked" style="color: #f77816;"></i></span>
                                <input class="form-control" type="password" name="password" placeholder="Contrase&ntilde;a" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <div class="col-xs-12">
                            <a href="<?php echo Configuraciones::url_base(); ?>recuperar_cuenta.php" style="color:#ffffff">¿Has olvidado tu contrase&ntilde;a?&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            <button class="btn btn-info w-md" style="background-color: #ffffff;  border: 1px solid #ffffff; color:#f77816 !important; " type="submit"><i class="ion-log-in" style="color: #f77816;"></i> Ingresar</button>
                        </div>
                    </div>
                </form>

                <?php
                if (isset($_GET['error'])) {
                    echo ("<br />\n");
                    if ($_GET['error'] == "si") {

                        echo ("<font color='red'>Error: Usuario y/o contrase&ntilde;a errónea.Por favor verificar o contactarse con el administrador del sistema</font>\n");
                    } elseif ($_GET['error'] == "notfound") {
                        echo ("<font color='red'>Error: Usuario y/o contrase&ntilde;a errónea.Por favor verificar o contactarse con el administrador del sistema</font>\n");
                    } elseif ($_GET['error'] == "notfoundoption") {
                        echo ("<font color='red'>Error: El usuario no cuenta con opciones disponibles.Por favor verificar o contactarse con el administrador del sistema</font>\n");
                    }
                }
                ?>
            </div>
        </div>

        <?php
        include_once __DIR__ . '/vistas/com/template/partBodyMainContentEnds.php';
        ?>
    </body>

    </html>

<?php
}
?>