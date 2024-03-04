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
                            <form id="frm_usuario" method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>" />
                                <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                                <!--INICIO DE PESTAÑAS -->
                                <div class="row">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="active">
                                            <a href="#tabGeneral" data-toggle="tab" aria-expanded="true">
                                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                                <span class="hidden-xs">General</span>
                                            </a>
                                        </li>
                                        <li class="" id="liCorrelativo">
                                            <a href="#tabCorrelativo" data-toggle="tab" aria-expanded="false">
                                                <span class="visible-xs"><i class="ion-arrow-graph-up-right"></i></span>
                                                <span class="hidden-xs">Correlativos</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <!--PESTAÑA GENERAL-->
                                        <div class="tab-pane active" id="tabGeneral">
                                            <div class="row">
                                                <input type="text" hidden id="txtCajaId" value="<?php echo $id ?>">
                                                <div class="form-group col-md-6">
                                                    <label>Codigo *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_codigo" name="txt_codigo" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                                                    </div>
                                                    <i id='msj_codigo' style='color:red;font-style: normal;' hidden></i>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Descripcion *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                                                    </div>
                                                    <i id='msj_descripcion' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Sufijo Comprobante *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_sufijo" name="txt_sufijo" class="form-control" required="" aria-required="true" value="" maxlength="3" />
                                                    </div>
                                                    <i id='msj_sufijo' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Correlativo inicio *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_correlativo" name="txt_correlativo" class="form-control" required="" aria-required="true" value="" maxlength="6" />
                                                    </div>
                                                    <i id='msj_correlativo' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Agencia *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div id="combo_colaboradores">
                                                            <select id="cbo_agencia" name="cbo_agencia" onchange="onchangeAgencia()" class="select2" data-placeholder="Colaborador...">
                                                            </select>
                                                        </div>
                                                        <input type="hidden" id="hd_email" name="hd_email">
                                                        <i id='msj_colaborador' style='color:red;font-style: normal;' hidden></i>
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
                                                        <span id='msj_estado' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group col-md-6"></div> -->
                                                <div class="form-group col-md-6">
                                                    <label>Virtual *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                        <select name="virtual" id="virtual" class="select2">
                                                            <option value="1" >Si</option>
                                                            <option value="0" selected>No</option>
                                                        </select>
                                                        <span id='msj_virtual' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                    </div>
                                                </div>
                                                
                                                  <div class="form-group col-md-6">
                                                    <label>Punto de Venta (IP) *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_ip" name="txt_ip" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                                                    </div>
                                                    <i id='msj_ip' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!--PESTAÑA CORRELATIVO-->
                                        <div class="tab-pane" id="tabCorrelativo">
                                            <input type="hidden" id="idDireccionDetalle" value="" />
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <div id="divTipoDocumentoCorrelativo">
                                                        <label id="labelTipoDocumento">Tipo Documento *</label>
                                                        <select name="cboTipoDocumento" id="cboTipoDocumento" class="select2" data-placeholder="Colaborador...">
                                                            <option value="284">Guía de Remisión Transportista</option>
                                                            <option value="191">Nota de venta</option>
                                                            <option value="6">V. Boleta</option>
                                                            <option value="7">V. Factura</option>
                                                            <option value="61">V. Nota de crédito</option>
                                                            <option value="269">V. Nota de débito</option>
                                                            </select>
                                                        <span id="msjTipoDocumento" class="control-label" style="color:red;font-style: normal;" hidden></span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3 hidden" id="divTipoDocumentoRelacion">
                                                    <div>
                                                        <label id="labelTipoDocumentoRelacion">Tipo Documento Relación *</label>
                                                        <select name="cboTipoDocumentoRelacion" id="cboTipoDocumentoRelacion" class="select2" data-placeholder="Colaborador...">
                                                            <option value="191">Nota de venta</option>
                                                            <option value="6">V. Boleta</option>
                                                            <option value="7">V. Factura</option>
                                                            </select>
                                                        <span id="msjTipoDocumento" class="control-label" style="color:red;font-style: normal;" hidden></span>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Serie *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_Serie" name="txt_codigo" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                                                    </div>
                                                    <i id='msj_Serie' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Correlativo *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txt_Correlativo" name="txt_codigo" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                                                    </div>
                                                    <i id='msj_cCorrelativo' style='color:red;font-style: normal;' hidden></i>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group col-md-12">
                                                        <button type="button" name="btnGuardar" id="btnGuardar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;" onclick="agregarCorrelativoDetalle()"><i class="fa fa-plus-square-o"></i>&nbsp;Agregar Correlativo</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-body" id="muestrascroll">
                                                <span id="msjDireccionDetalle" class="control-label" style="color:red;font-style: normal;" hidden></span>
                                                <div class="row" id="scroll">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table">
                                                            <div id="dataList">
                                                                <table id="dataTableCorrelativo" class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align:center">Documento Tipo</th>
                                                                            <th style="text-align:center">Serie</th>
                                                                            <th style="text-align:center">Correlativo Inicio</th>
                                                                            <th style="text-align:center">Documento Tipo Relacion</th>
                                                                            <th style="text-align:center">Acciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="clear:left">
                                                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                                                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar Correlativo&nbsp;&nbsp;&nbsp;
                                                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar Correlativo&nbsp;&nbsp;&nbsp;
                                                    </p><br>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()"><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                        <button type="button" onclick="guardarCaja()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
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
        $(document).ready(function() {
            altura();
            loaderShow(null);
            cargarCombo();
            cargarComponentes();
            //                                                    altura();
        });
    </script>
    <script src="vistas/com/caja/caja_form.js"></script>
</body>

</html>