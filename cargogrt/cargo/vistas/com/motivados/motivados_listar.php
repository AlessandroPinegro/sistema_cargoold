<html lang="es">

    <head>
        <style type="text/css" media="screen">
            @media screen and (max-width: 1000px) {
                #scroll {
                    width: 1000px;
                }

                #muestrascroll {
                    overflow-x: scroll;
                }
            }

            #datatable td {
                vertical-align: middle;
            }

            .sweet-alert button.cancel {
                background-color: rgba(224, 70, 70, 0.8);
            }

            .sweet-alert button.cancel:hover {
                background-color: #E04646;
            }

            .sweet-alert {
                border-radius: 0px;
            }

            .sweet-alert button {
                -webkit-border-radius: 0px;
                border-radius: 0px;
            }

            .popover {
                max-width: 100%;
            }

            th {
                white-space: nowrap;
            }

            .alignRight {
                text-align: right;
            }
        </style>
    </head>

    <body>
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="">
                            <div id="bg-info" class="panel-collapse collapse in">
                                <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                                <div class="row">                  
                                    <div class="form-group col-md-6">
                                        <label>Motivo * </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txt_motivo" name="txt_motivo" class="form-control" required="" aria-required="true" value="" />
                                        </div>
                                        <i id='msj_motivo' style='color:red;font-style: normal;' hidden></i>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Descripción * </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" />
                                        </div>
                                        <i id='msj_descripcion' style='color:red;font-style: normal;' hidden></i>
                                    </div>
                                </div>
                                <div class="row">                  
                                    <div class="form-group col-md-6">
                                        <label>Ejemplo  </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txt_ejemplo" name="txt_ejemplo" class="form-control" required="" aria-required="true" value="" />
                                        </div>
                                        <i id='msj_ejemplo' style='color:red;font-style: normal;' hidden></i>
                                    </div>
                                </div>
                                <div class="row">          
                                    <div class="form-group col-md-6" align="rigth">
                                        <label>&nbsp; </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <button type="button" onclick="guardarMotivo()" value="guardar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;"><i class="fa fa-plus" aria-hidden="true"></i>&ensp;Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="datatable2" id="scroll">

                    </div>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>

    <!--
    <!--</div>-->
    <script src="vistas/com/motivados/motivados_listar.js"></script>
</body>

</html>