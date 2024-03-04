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
if ($tipo == 1)
    $titulo = "Editar tipo de movimiento";
else
    $titulo = "Nuevo tipo de movimiento";
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Tipo de movimientos</title>
        <link href="vistas/libs/imagina/assets/modal-effect/css/component.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/jquery-multi-select/multi-select.css" />
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/select2/select2.css" />
        <script type="text/javascript" src="vistas/libs/imagina/assets/jquery-multi-select/jquery.multi-select.js"></script>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js" type="text/javascript"></script>
        <script>
            altura();
        </script> 
        <?php
        if ($tipo == 1) {
            ?>
            <script language="javascript">
                getMovimientoTipo(<?php echo $id; ?>);
            </script>
        <?php }
        ?>
    </head>
    <body>
<!--        <section class="content">-->
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><b><?php echo $titulo; ?>:</b></h4>
                        <div class="col-md-12 ">
                            <div class="panel-body">
                                <form  id="frm_bien_tipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>C&oacute;digo *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_codigo" name="txt_codigo" class="form-control" value="" maxlength="8"/>
                                            </div>
                                            <span id='msj_codigo' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Descripci&oacute;n *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value=""/>
                                            </div>
                                            <span id='msj_descripcion' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Indicador *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                <select name="cbo_indicador" id="cbo_indicador"  class="select2" >
<!--                                                    <option value="0"><br></option>-->
                                                    <option value="1" selected>Ingreso</option>
                                                    <option value="2">Salida</option>
                                                    <option value="3">Transferencia</option>
                                                </select>
                                                <span id='msj_estado' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label>Estado *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                <select name="cbo_estado" id="cbo_estado" class="select2">
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
                                            <label>Comentario</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<input type="text" id="txt_comentario" name="txt_comentario" class="form-control" required="" aria-required="true" value=""/>-->
                                                <!--<input type="text" id="comentario" name="comentario" class="form-control" value=""/>-->
                                                <textarea type="text" id="txt_comentario" name="txt_comentario" class="form-control" value=""></textarea>
                                            </div>
                                            <span id='msj_comentario' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/movimiento/movimiento_tipo_listar.php')" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="guardarMovimientoTipo('<?php echo $tipo; ?>')" value="buscar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="vistas/libs/imagina/js/bootstrap-combobox.js"></script>
        <script src="vistas/com/movimiento/movimiento_tipo.js"></script>
        <script type="text/javascript">
                                                $(document).ready(function () {
                                                    
                                                    cargarComponentes();
                                                    loaderClose();
                                                });
                                                altura();
        </script>
    </body>
    <!-- Mirrored from coderthemes.com/velonic/admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:15:09 GMT -->
</html>
