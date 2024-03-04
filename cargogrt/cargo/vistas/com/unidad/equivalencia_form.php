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
if (isset($f_factor)) {
    //si el tipo es 1 se va a editar
    $factor = (int) filter_var($f_factor, FILTER_SANITIZE_NUMBER_INT);
}
if ($tipo == 1)
    $titulo = "Editar equivalencia";
else
    $titulo = "Nuevo equivalencia";
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <title>Mantenimietno de equivalencias</title>
        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet"/>
        <link href="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        <?php
        if ($tipo == 1) {
            ?>
            <script language="javascript">
                getEquivalencia(<?php echo $id; ?>,<?php echo $factor; ?>);
            </script>
            <?php
        } else {
            ?>
            <script language="javascript">
    //                getComboUnidad();
            </script>
            <?php
        }
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

                                <form  id="frm_equivalencia"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Factor </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_factor2" name="txt_factor2" class="form-control" required="" aria-required="true" value="1"/>
                                            </div>
                                            <i id='msj_factor2'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Unidad Base *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_unidades">
                                                    <select id="cbo_unidad" name="cbo_unidad" class="select2" data-placeholder="Colaborador..." >
                                                    </select>
                                                </div>
                                                <i id='msj_unidad'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Factor </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_factor1" name="txt_factor1" class="form-control" required="" aria-required="true" value="1" />
                                            </div>
                                            <i id='msj_factor1'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 
                                        <div class="form-group col-md-6">
                                            <label>Unidad Alternativa *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_alternativa">
                                                    <select id="cbo_alternativa" onChange="onchangeAlternativa();" class="select2" name="cbo_alternativa">
                                                        <!--<option value="" id="s1"  style="display:none;">Unidad alternativa</option>-->
                                                    </select>
                                                </div>
                                                <i id='msj_alternativa'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>&ensp;</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button type="button" onclick="validarAgregarEquivalencia();" id="btnCargarOpciones" data-toggle="modal" data-target="#accordion-modal"  name="btnCargarOpciones"  class="btn btn-success m-b-5" style="border-radius: 0px;"><i class="ion-android-add"></i>&ensp;Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-9">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <i id='msj_agregar'
                                                   style='color:red;font-style: normal;' hidden></i>
                                                <div id="lista_alternativa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/equivalencia_listar.php')" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="guardarEquivalencia('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                cargarCombo();
                cargarComponentes();
            });
//            loaderShow(null);
            altura();
        </script>
        <script src="vistas/com/unidad/equivalencia.js"></script>
    </body>
</html>
