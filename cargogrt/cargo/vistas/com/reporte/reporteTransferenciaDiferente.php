<html lang="es">
    <head>
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
            .popover{
                max-width: 100%; 
            }
            th { white-space: nowrap; }
            .alignRight { text-align: right; }
        </style>
    </head>
    <body >
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table">
                            <!--<div id="dataList" class="table-responsive">-->
                                <table id="dataTableTransferenciaDiferente" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Guia Remisión</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Recepción</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Código producto</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Producto</th>
                                            <th style='text-align:center; vertical-align: middle;' colspan="2">Cantidad</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Unidad medida</th>
                                        </tr>
                                        <tr>
                                            <th style='text-align:center;'>Guía</th>
                                            <th style='text-align:center;'>Recepción</th>
                                        </tr>
                                    </thead>
                                </table>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
                
<!--                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-eye" style="color:#1ca8dd;"></i> Ver detalle del documento
                    </p>
                </div>-->
            </div>
        </div>
        <!--</div>-->

<!--modal para el detalle del movimiento-->
<div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title">Visualización del documento</h4> 
            </div>
            <div class="modal-body"> 
                <div class="row">

                    <div class="col-lg-5">
                        <div class="portlet" style="box-shadow: 0 0px 0px"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 id="nombreDocumentoTipo" class="portlet-title text-dark text-uppercase">

                                </h3>
                                <!--                                        <div class="portlet-widgets">
                                                                            <a data-toggle="collapse" data-parent="#accordion1" href="#portlet1"><i class="ion-minus-round"></i></a>
                                                                        </div>-->
                                <div class="clearfix"></div>
                            </div>
                            <div id="portlet1" class="panel-collapse collapse in">
                                <div class="portlet-body" >
                                    <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                    </form>
                                </div>
                            </div>
                        </div> <!-- /Portlet -->
                    </div>
                    <div class="col-lg-7 ">
                        <div class="portlet" style="box-shadow: 0 0px 0px"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 class="portlet-title text-dark text-uppercase">
                                    Detalle del documento
                                </h3>
                                <div class="portlet-widgets">
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="portlet2" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <!--<div class="panel panel-body">-->
                                        <table id="datatable2" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style='text-align:center;'>Organizador</th>
                                                    <th style='text-align:center;'>Cantidad</th>
                                                    <th style='text-align:center;'>U. Medida</th>
                                                    <th style='text-align:center;'>Descripcion</th> 
<!--                                                    <th style='text-align:center;'>P. Unit.</th>
                                                    <th style='text-align:center;'>Total</th>-->
                                                </tr>
                                            </thead>
                                        </table>
                                        
                                        <br>
                                        <div class="form-group col-lg-12 col-md-12">
                                            <label>COMENTARIO </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea type="text" id="txtComentario" name="txtComentario" class="form-control" value="" maxlength="500" readonly="true" ></textarea>
                                            </div>
                                        </div>
                                    <!--</div>-->
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button> 
            </div>
        </div> 
    </div>
</div><!-- /.modal -->
        
        <script src="vistas/com/reporte/reporteTransferenciaDiferente.js"></script>
    </body>
</html>


