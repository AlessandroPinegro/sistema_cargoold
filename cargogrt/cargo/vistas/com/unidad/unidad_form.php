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
        <title>Mantenimietno de tipo de unidades</title>
        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet"/>
        
        <style type="text/css" media="screen">

            .sweet-alert button.cancel {
                background-color: rgba(224, 70, 70, 0.8);
            }
            .sweet-alert button.cancel:hover {
                background-color:#E04646;
            }
            .sweet-alert {
                border-radius: 0px; 

            }
            .sweet-alert button {
                -webkit-border-radius: 0px; 
                border-radius: 0px; 

            }
        </style>
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
                                <form  id="frm_unidad"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <!--<input type="hidden" name="unidadId" id="unidadId" value="-1"/>-->
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>C&oacute;digo *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_codigo" name="txt_codigo" class="form-control" value="" maxlength="20"/>
                                            </div>
                                            <span id='msj_codigo' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Descripci&oacute;n *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="250"/>
                                            </div>
                                            <span id='msj_descripcion' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Simbolo *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_simbolo" name="txt_simbolo" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <span id='msj_simbolo' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div> 
                                        <div class="form-group col-md-6">
                                            <label>Estado *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEstado" id="cboEstado" class="select2">
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
                                            <label>Tipo de Unidad *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboUnidadTipo" id="cboUnidadTipo" onchange="onchangeTipoUnidad()" class="select2">
                                                </select>
                                                <i id='msj_tipo'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">

                                            <label>&nbsp;&nbsp;</label>
                                            <div class="col-xs-12">
                                                <div class="checkbox">
                                                    <label class="cr-styled">
                                                        <input type="checkbox" name="chk_unidad_base" id="chk_unidad_base">
                                                        <i class="fa"></i> 
                                                        Unidad Base
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                                            
                                        <div class="form-group col-md-6">
                                            <label>Código de la unidad de medida Sunat</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboCodigoSunat" id="cboCodigoSunat" class="select2">
                                                </select>
                                                <i id='msjCodigoSunat' style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="save('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
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
        <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
        <script src="vistas/com/unidad/unidad.js"></script>
        <script type="text/javascript">
                                                $(document).ready(function () {
                                                    altura();
//                                                    loaderShow(null);
                                                    cargarSelect2();
                                                });
        </script>
    </body>
</html>
