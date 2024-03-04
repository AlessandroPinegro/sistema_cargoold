<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Mantenimietno de usuarios</title>
        <!--<link href="vistas/libs/imagina/assets/modal-effect/css/component.css" rel="stylesheet">-->
        <!--<link href="vistas/libs/imagina/css/bootstrap-combobox.css" rel="stylesheet">-->
        <!--<link href="vistas/libs/imagina/css/bootstrap-combobox.css" rel="stylesheet" type="text/css"/>-->
    </head>
    <body>
<!--        <section class="content">-->
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><b>Cambiar contrase&ntilde;a</b></h4>
                        <div class="col-md-12 ">
                            <div class="panel-body">
                                <form  id="frm_usuario"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Contrase&ntilde;a actual *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" id="contra_actual" name="contra_actual" class="form-control" required="" aria-required="true" value=""/>
                                            </div>
                                            <i id='msj_actual'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 

                                        <div class="form-group col-md-6">
                                            <label>Contrase&ntilde;a nueva *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" id="contra_nueva" name="contra_nueva" class="form-control" required="" aria-required="true" value=""/>
                                            </div>
                                            <i id='msj_nueva'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 
                                    </div>
                                    <div class="row">
                                         <div class="form-group col-md-6">
                                            <label>Confirmar contrase&ntilde;a nueva *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="password" id="contra_confirmar" name="contra_confirmar" class="form-control" required="" aria-required="true" value=""/>
                                            </div>
                                            <i id='msj_confirmar'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="a_cancelar" name='a_cancelar' style="border-radius: 0px;" onclick="cancelarCambiarContrasena();" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="cambiarContrasena();" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                            <!--<button type="button" onclick="obtenerContrasenaActual();" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <script src="vistas/libs/imagina/js/bootstrap-combobox.js"></script>-->
              <script src="vistas/com/usuario/usuario.js"></script>  
        <script type="text/javascript">
     $(document).ready(function(){
//          obtenerPantallaPrincipalUsuario();
          });
//          altura();
</script>

<!--         <script src="<?php echo Configuraciones::url_base(); ?>vistas/index.js"></script>-->
    </body>
    <!-- Mirrored from coderthemes.com/velonic/admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:15:09 GMT -->
</html>
