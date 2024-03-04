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
                                <form id="frm_vehiculos" method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>" />
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Empresa</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEmpresa" id="cboEmpresa" class="select2"></select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Integración flota *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_flota_id" name="txt_flota_id" class="form-control" value="" />
                                            </div>
                                            <span id='msj_flota_id' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Flota número </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_flota_numero" name="txt_flota_numero" class="form-control" value="" />
                                            </div>
                                            <span id='msj_numero' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Placa *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_placa" name="txt_placa" class="form-control" value="" />
                                            </div>
                                            <span id='msj_placa' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Tarjeta de circulación</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_tarjeta_circulacion" name="txt_tarjeta_circulacion" class="form-control" value="" />
                                            </div>
                                            <span id='msj_tarjeta_circulacion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Codigo de configuración vehicular </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_codigo_configuracion" name="txt_codigo_configuracion" class="form-control" value="" />
                                            </div>
                                            <span id='msj_codigo_configuracion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>
                                    </div>                
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Marca *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_marca" name="txt_marca" class="form-control" required="" aria-required="true" value="" maxlength="500" />
                                            </div>
                                            <span id='msj_marca' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div> 
                                        <div class="form-group col-md-4">
                                            <label>Capacidad *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txt_capacidad" name="txt_capacidad" class="form-control" required="" aria-required="true" value="" maxlength="500" />
                                            </div>
                                            <span id='msj_capacidad' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>                
                                        <div class="form-group col-md-4">
                                            <label>Tipo</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipo" id="cboTipo" class="select2">                          
                                                    <option value="1">Bus</option>
                                                    <option value="2">Furgon</option>
                                                    <option value="3">Carguero</option>
                                                </select>
                                            </div>
                                            <span id='msj_tipo' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                        </div>                  
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()"><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="guardarVehiculo('<?php echo $tipo; ?>')" value="guardar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <script src="vistas/com/vehiculos/vehiculos.js"></script>
        <script type="text/javascript">
         $(document).ready(function () {
            select2.iniciar(); 
            obtenerConfiguracionInicialForm();

        });
        </script>
    </body>
    <!-- Mirrored from coderthemes.com/velonic/admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:15:09 GMT -->

</html>