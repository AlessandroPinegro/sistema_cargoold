<html lang="es">
    <head>
        <!--        -->

        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet"/>
        <link href="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        <script type="text/javascript" src="vistas/libs/imagina/assets/jquery-multi-select/jquery.multi-select.js"></script>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js" type="text/javascript"></script>
        <!--<link href="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.css" rel="stylesheet"/>-->
        <style type="text/css" media="screen">

            #datatable td{
                vertical-align: middle;
            }
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
    <body >
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 ">
                            <div class="panel-body">

                                <form  id="frm_equivalencia"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                                    <input type="hidden" name="accion" id="accion" value="0"/>
                                    <input type="hidden" name="id_equivalencia" id="id_equivalencia" value="-1"/>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Factor </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="number" id="txt_factor2" name="txt_factor2" class="form-control" required="" aria-required="true" value="1" style="text-align: right"    onkeyup="if(this.value.length>10){this.value=this.value.substring(0,10)}"/>
                                            </div>
                                            <i id='msj_factor2'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Unidad base *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_unidades">
                                                    <select id="cbo_unidad" name="cbo_unidad" class="select2" data-placeholder="unidad base..." >
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
                                                <input type="number" id="txt_factor1" name="txt_factor1" class="form-control" required="" aria-required="true" value="1" style="text-align: right"    onkeyup="if(this.value.length>10){this.value=this.value.substring(0,10)}"/>
                                            </div>
                                            <i id='msj_factor1'
                                               style='color:red;font-style: normal;' hidden></i>
                                        </div> 
                                        <div class="form-group col-md-6">
                                            <label>Unidad alternativa *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="combo_alternativa">
                                                    <select id="cbo_alternativa" onChange="onchangeAlternativa();" class="select2" name="cbo_alternativa" data-placeholder="unidad alternativa...">
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
                                                <button type="button" onclick="guardarEquivalencia();" id="env" data-toggle="modal" data-target="#accordion-modal"  name="btnCargarOpciones"  class="btn btn-success m-b-5" style="border-radius: 0px;"><i class="ion-android-add"></i>&ensp;Agregar</button>
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
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <br><br>
                <div class="panel panel-body" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="dataList">
                        </div>
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <!--<i class="fa fa-file-text" style="color:#088A68;"></i> Detalle de  la informaci&oacute;n &nbsp;&nbsp;&nbsp;-->
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
<!--                        <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                        <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo -->
                    </p>
                </div>
            </div>
        </div>
        <script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>  
        <script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>
        <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js"></script>
        <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.init.js"></script>
        <script src="vistas/com/unidad/equivalencia.js"></script>
        <script type="text/javascript">
                                                    $(document).ready(function () {
//                                                        loaderShow(null);
                                                        listarEquivalencias();
                                                        cargarCombo();
                                                        cargarComponentes();
                                                        altura();
                                                    });

        </script>
    </body>
</html>


