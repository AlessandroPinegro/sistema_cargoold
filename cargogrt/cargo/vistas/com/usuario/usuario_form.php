<?php
session_start();
//$id = null;
$tipo = null;
extract($_REQUEST, EXTR_PREFIX_ALL, "f");
if (isset($f_id)) {
    $id = (int) filter_var($f_id, FILTER_SANITIZE_NUMBER_INT);
}
if (isset($f_tipo)) {
    //si el tipo es 1 se va a editar
    $tipo = (int) filter_var($f_tipo, FILTER_SANITIZE_NUMBER_INT);
}
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <!--<link href="vistas/libs/imagina/css/bootstrap-combobox.css" rel="stylesheet">-->
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/colorpicker/colorpicker.css" />
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/jquery-multi-select/multi-select.css" />
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/select2/select2.css" />
        <script type="text/javascript" src="vistas/libs/imagina/assets/jquery-multi-select/jquery.multi-select.js"></script>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js" type="text/javascript"></script>

    </head>
    <body>
<!--        <section class="content">-->
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><b id="titulo"></b></h4>
                        <div class="col-md-12 ">
                            <div class="panel-body">
                                <form  id="frm_usuario"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Usuario *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_usuario" name="txt_usuario" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <i id='msj_usuario'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 
                                        <div class="form-group col-md-6">
                                            <label>Perfil *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_perfiles">
                                                    <select id="cbo_perfil" name="cbo_perfil" onchange="onchangePerfil()"  class="select2" multiple data-placeholder="Perfil..." >
                                                    </select>
                                                </div>
                                                <i id='msj_perfil'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Colaborador *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_colaboradores">
                                                    <select id="cbo_colaborador" name="cbo_colaborador" onchange="onchangeColaborador()"  class="select2" data-placeholder="Colaborador..." >
                                                    </select>
                                                </div>
                                                <input type="hidden" id="hd_email" name="hd_email">
                                                <i id='msj_colaborador'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label id="lb_empresa">Empresas *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_empresa">
                                                    <select id="cbo_empresa" name="cbo_empresa" onchange="onchangeColaborador()"  class="select2" multiple data-placeholder="Empresa..." >
                                                    </select>
                                                </div>
                                                <span id='msj_empresa' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Estado *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                <select name="estado" id="estado"  class="select2" >
                                                    <option value="1" selected>Activo</option>
                                                    <option value="0">Inactivo</option>
                                                </select>
                                                <span id='msj_estado' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label> &nbsp;</label>
                                            <div  class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                                                <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                                <button type="button" onclick="guardarUsuario('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>
        <script type="text/javascript">
                                                $(document).ready(function () {
                                                    altura();
                                                    loaderShow(null);
                                                    cargarCombo();
                                                    cargarComponentes();
//                                                    altura();
                                                });
        </script>
        <script src="vistas/com/usuario/usuario.js"></script>
    </body>
</html>
