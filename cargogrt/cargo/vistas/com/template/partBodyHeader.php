            <!-- Header -->
            <?php
            include_once __DIR__.'/../../../controlador/almacen/PerfilControlador.php';
            ?>
            <?php             
            $ruta_imagen=null;
            if(isset($_SESSION['ldap_user'])){
                $usuario=$_SESSION['ldap_user'];            
    //            $id_perfil = $_SESSION['perfil_id']; 
    //            $id_usuario = $_SESSION['id_usuario'];
                $per = new PerfilControlador();
    //            $response_imagen = $per->obtenerImagenPerfil($id_perfil,$id_usuario);
                $response_imagen = $per->obtenerImagenXUsuario($usuario);            
                
                foreach ($response_imagen as $campo) {
                  $imagen = $campo['imagen'];
                 }
                 if($imagen==null || $imagen=='')
                 {
                     $imagen='none.jpg';
                 }
                 $ruta_imagen = Configuraciones::url_base()."vistas/com/persona/imagen/".$imagen;
    //              $rutaImagenBien = __DIR__ . '/../../vistas/com/bien/imagen/';
            }
            
            if (!file_exists($ruta_imagen)) {
                $ruta_imagen=Configuraciones::url_base()."vistas/com/persona/imagen/none.jpg";
            } 
             
            ?>
            <header class="top-head container-fluid">
                <button type="button" onclick="ocultar();ajustarAnchoBuscador();" class="navbar-toggle pull-left" title="Opciones">
                    <span class="sr-only">Navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
<!--                 Search 
                <form role="search" class="navbar-left app-search pull-left hidden-xs">
                  <input type="text" placeholder="Buscar..." class="form-control">
                </form>-->
                
                <!-- Left navbar -->
                
                
                <nav class=" navbar-default hidden-xs" role="navigation">
                    <ul class="nav navbar-nav">
                    </ul>
                </nav>                
                
                <!-- Right navbar -->
                <ul class="list-inline navbar-right top-menu top-right-menu">  
                    <!-- Notification -->
<!--                    <li class="dropdown">
                        <a href="<?php // echo Configuraciones::url_base(); ?>Manual_de_usuario_2016_Sistema_Imagina_JR.pdf" target="_blank" title="Manual de usuario">
                            <i class="ion-android-note"></i>       
                            <span class="badge badge-sm up bg-pink count"><i class="ion-help"></i></span>
                        </a>
                    </li>-->
                    <!-- /Notification -->
                    
                    <!-- user login dropdown start-->
                    <li class="dropdown text-center">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <!--<img alt="" src="vistas/images/none.jpg" class="img-circle profile-img thumb-sm">-->
                            <img alt="" src="<?php echo $ruta_imagen; ?>" onerror="this.src='vistas/com/persona/imagen/none.jpg'" class="img-circle profile-img thumb-sm">
                            <span class="username"><?php if(isset($_SESSION['ldap_user'])){ echo $_SESSION['ldap_user']; }?></span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu extended pro-menu fadeInUp animated" tabindex="5003" style="overflow: hidden; outline: none;">
                            <li><a href="#" onclick='cargarDiv("#window", "<?php echo Configuraciones::url_base(); ?>vistas/com/usuario/usuario_cuenta_form.php");' ><i class="ion-person"></i>Cambiar contrase&ntilde;a</a></li>
                            <li><a href="<?php echo Configuraciones::url_base(); ?>logout.php"><i class="fa fa-sign-out"></i> Cerrar sesión</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->       
                </ul>
                <!-- End right navbar -->

            </header>
            <!-- Header Ends -->