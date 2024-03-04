<?php
session_start();
$id = null;
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
    <link href="vistas/libs/imagina/css/bootstrap-combobox.css" rel="stylesheet">
    <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet" />
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
                            <form id="frm_organizador" method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>" />
                                <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                                <div class="row">
                                    <div class="form-group col-md-6 ">
                                        <label>Código *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txt_codigo" name="txt_codigo" class="form-control" value="" maxlength="40" />
                                        </div>
                                        <span id='msj_codigo' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Descripci&oacute;n *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="500" />
                                        </div>
                                        <span id='msj_descripcion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label id="lb_empresa">Empresas *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboEmpresa" id="cboEmpresa" class="select2" onchange="onchangeEmpresa();">
                                            </select>
                                            <span id='msj_empresa' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tipo de Organizador *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboOrganizadorTipo" id="cboOrganizadorTipo" class="select2" onchange="onchangeTipoOrganizador();">
                                            </select>
                                            <i id='msj_tipo' style='color:red;font-style: normal;' hidden></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label>Organizador Padre</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboOrganizadorPadre" id="cboOrganizadorPadre" class="select2" onchange="onchangeOrganizadorPadre();">
                                            </select>
                                        </div>
                                        <span id='msj_padre' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Agencia *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboAgencia" id="cboAgencia" class="select2" onchange="onchangeAgencia();">
                                            </select>
                                        </div>
                                        <span id='msj_agencia' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Estado *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                            <select name="cboEstado" id="cboEstado" class="select2">
                                                <option value="1" selected>Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                            <span id='msj_estado' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Comentario </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea type="text" id="txt_comentario" name="txt_comentario" class="form-control" value="" maxlength="500"></textarea>
                                            <!--<input type="text" id="comentario" name="comentario" class="form-control" value=""/>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()"><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                        <button type="button" onclick="guardarOrganizador('<?php echo $tipo; ?>')" value="guardar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
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
    <script src="vistas/com/organizador/organizador.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            altura();
            cargarSelect2();         
            obtenerConfiguracionInicialForm();
        }); 
      
    </script>
</body>
<!-- Mirrored from coderthemes.com/velonic/admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:15:09 GMT -->

</html>