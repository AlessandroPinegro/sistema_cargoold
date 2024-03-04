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
                                        <input type="text" hidden id="txtAgenciaId" value="<?php echo $id ?>">
                                        <div class="form-group col-md-6">
                                            <label>Codigo *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_codigo" name="txt_codigo" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <i id='msj_codigo'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 

                                        <div class="form-group col-md-6">
                                            <label>Descripcion *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <i id='msj_descripcion'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Direccion *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_direccion" name="txt_direccion" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <i id='msj_direccion'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Ubigeo *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select id="cbo_ubigeo" name="cbo_ubigeo" onchange="onchangeUbigeo()"  class="select2" data-placeholder="Colaborador..." >
                                                </select>
                                                <i id='msj_ubigeo'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Estado *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="estado" id="estado"  class="select2" >
                                                    <option value="1" selected>Activo</option>
                                                    <option value="0">Inactivo</option>
                                                </select>
                                                <span id='msj_estado' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Organizador por defecto para pedido</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select id="cboOrganizador" name="cboOrganizador"  class="select2" data-placeholder="Organizador..." ></select>
                                            </div> 
                                        </div> 
                                        <div class="form-group col-md-6">
                                            <label>Organizador por defecto para recepción</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select id="cboOrganizadorRecepcion" name="cboOrganizadorRecepcion"  class="select2" data-placeholder="Organizador..." ></select>
                                            </div> 
                                        </div> 
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="guardarAgencia()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
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
        <script src="vistas/com/agencia/agencia_form.js"></script>
    </body>
</html>
