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
        <link href="vistas/libs/imagina/assets/modal-effect/css/component.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="vistas/libs/imagina/assets/select2/select2.css" />
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
                                <form  id="frm_servicio"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <!--<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>-->
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <input type="hidden" name="tipoAccion" id="tipoAccion" value="<?php echo $tipo ?>"/>
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label>Calse de Persona *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<div id="combo_colaboradores">-->
                                                <select id="cboPersonaClase" name="cboPersonaClase"  class="select2" data-placeholder="Clase de persona..." >
                                                </select>
                                                <!--</div>-->
                                                <i id='msjPersonaClase'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
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
                                            <label>Importe *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="txtImporte" id="txtImporte" name="txtImporte" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                                            </div>
                                            <i id='msjImporte'
                                               style='color:red;font-style: normal;' hidden></i>
                                            <!--                                            <div id="spnImporte">
                                                                                                    <div class="input-group input-small">
                                                                                                        <input type="text" id="txtImporte" name="txtImporte" class="spinner-input form-control" maxlength="3" readonly>
                                                                                                        <div class="spinner-buttons input-group-btn btn-group-vertical">
                                                                                                            <button type="button" class="btn spinner-up btn-xs btn-default">
                                                                                                                <i class="fa fa-angle-up"></i>
                                                                                                            </button>
                                                                                                            <button type="button" class="btn spinner-down btn-xs btn-default">
                                                                                                                <i class="fa fa-angle-down"></i>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>-->
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Moneda *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<div id="combo_colaboradores">-->
                                                <select id="cboMoneda" name="cboMoneda"  class="select2" data-placeholder="Moneda..." >
                                                </select>
                                                <!--</div>-->
                                                <i id='msjMoneda'
                                                   style='color:red;font-style: normal;' hidden></i>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Periodo *</label>
                                            <div id="spnPeriodo">
                                                <div class="input-group input-small">
                                                    <input type="text" id="txtPeriodo" class="spinner-input form-control" maxlength="3" readonly>
                                                    <div class="spinner-buttons input-group-btn btn-group-vertical">
                                                        <button type="button" class="btn spinner-up btn-xs btn-default">
                                                            <i class="fa fa-angle-up"></i>
                                                        </button>
                                                        <button type="button" class="btn spinner-down btn-xs btn-default">
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Periodo de Gracia *</label>
                                            <div id="spnPeriodoGracia">
                                                <div class="input-group input-small">
                                                    <input type="text" id="txtPeriodoGracia" class="spinner-input form-control" maxlength="3" readonly>
                                                    <div class="spinner-buttons input-group-btn btn-group-vertical">
                                                        <button type="button" class="btn spinner-up btn-xs btn-default">
                                                            <i class="fa fa-angle-up"></i>
                                                        </button>
                                                        <button type="button" class="btn spinner-down btn-xs btn-default">
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="enviarLineaCredito()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="vistas/libs/imagina/assets/jquery-multi-select/jquery.multi-select.js"></script>
        <script type="text/javascript" src="vistas/libs/imagina/assets/spinner/spinner.min.js"></script>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js" type="text/javascript"></script>        
        <script src="vistas/com/lineaCredito/lineaCreditoForm.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                loaderShow(null);
                cargarComponentes();
                obtenerComboPersonaClase();
                obtenerComboMoneda();
                obtenerLineaCredito();
                altura();
            });
        </script>
    </body>
</html>
