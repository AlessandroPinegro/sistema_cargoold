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
<html lang="en">

    <!-- Mirrored from coderthemes.com/velonic/admin/form-advanced.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:16:18 GMT -->
    <head>
        <title>Mantenimietno de Perfiles</title>
        <link href="vistas/libs/imagina/css/bootstrap-combobox.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/colorpicker/colorpicker.css" />

        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet"/>

    </head>
    <body>
        <div class="row" >
            <div class="col-md-12 " >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><b id="titulo"></b></h4>
                        <div class="col-md-12 ">
                            <div class="panel-body">
                                <form id="frm_perfil"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $id ?>"/>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Descripci&oacute;n *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="descripcion" name="descripcion" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <span id='msj_desc' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>Clases a visualizar * </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboClasePersona" id="cboClasePersona" class="select2" multiple>
                                                </select>
                                                <span id='msjClasePersona' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6" hidden="true">
                                            <label>Agencias</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboAgencia" id="cboAgencia" class="select2" onchange="onChangeCboAgencias()" >
                                                </select>
                                                <span id='msjAgencia' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6" hidden=""true">
                                            <label>Cajas</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboCaja" id="cboCaja" class="select2" multiple ></select>
                                                <span id='msjCaja' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Comentario </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea type="text" id="comentario" name="comentario" class="form-control" value="" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Estado *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                <select name="estado" id="estado" class="select2">
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
                                            <span id='msj_opcion' class="control-label"
                                                  style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>
                                    <!--Modal para las opciones del menu--> 
                                    <div id="accordion-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header"> 
                                                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--> 
                                                    <h4 class="modal-title"><b>Administrador de opciones</b></h4> 
                                                </div> 
                                                <div class="modal-body"> 
                                                    <div  id="accordion-test"> 
                                                        <!--<div id="opcionp" id="muestrascroll">-->
                                                        <div id="opcionp" class="table" style="width:1000px; height:500px; overflow-y: scroll;">
                                                        </div>
<!--                                                                                                                <div id="opcionMovimiento" class="table" style="width:1000px; height:500px; overflow-y: scroll;">
                                                                                                                </div>-->
                                                    </div>

                                                </div><!-- /.body -->

                                                <div class="modal-footer"> 
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancelarAsignarOpciones('<?php echo $id; ?>')" ><i class="ion-close-round"></i>&nbsp;&nbsp;Cancelar</button> 
                                                    <button type="button" name="btnOpciones" id="btnOpciones" class="btn btn-success" data-dismiss="modal"><i class="fa fa-save"></i>&nbsp;&nbsp;Guardar</button> 
                                                </div> 
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                    <div class="form-group col-md-12">
                                        <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                        <button type="button" id="btnCargarOpciones" data-toggle="modal" data-target="#accordion-modal"  name="btnCargarOpciones"  class="btn btn-success m-b-5" style="border-radius: 0px;"><i class="ion-android-add"></i>&ensp;Acceso Opciones</button>&nbsp;&nbsp;&nbsp;
                                        <button type="button" onclick="guardarPerfil('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>
        <script src="vistas/libs/imagina/js/bootstrap-combobox.js"></script>
        <script>
            $(document).ready(function () {
                loaderShow(null);
                cargarComponentes();
                obtenerConfiguracionInicialForm();
//                                                obtenerPersonaClase();
//                                                cargarMenu();
//                                                cargarTipoMovimientoMenu();
//                                                getPerfil();
//                                                $(".select2-no-results").text("Ingresar un código").show();
                altura();
            });

        </script>   
        <script src="vistas/com/perfil/perfil.js"></script>
    </body>
</html>
