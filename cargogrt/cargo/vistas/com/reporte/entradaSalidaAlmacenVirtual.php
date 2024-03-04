<html lang="es">
    <head>
        <style type="text/css" media="screen">
            @media screen and (max-width: 1000px) {
                #scroll{
                    width: 1000px;               
                }
                #muestrascroll{
                    overflow-x:scroll;
                }
            }
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
                        <div  class="portlet" >
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="portlet-heading bg-purple m-b-0" 
                                         onclick="colapsarBuscador()"
                                         id="idPopover" title="" data-toggle="popover" 
                                         data-placement="top" data-content="" 
                                         data-original-title="Criterios de búsqueda"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">                                        

                                        <div class="portlet-widgets">

                                            <a id="loaderBuscar" onclick="loaderBuscar()">
                                                <i class="ion-refresh"></i>
                                            </a>
                                            <span class="divider"></span>
                                            <!--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>
                                            </a>-->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            </div>

                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">


                                    <div class="row">

                                        <div class="form-group col-md-6 ">
                                            <label>Origen</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboOrigen" id="cboOrigen" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>Producto</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboProducto" id="cboProducto" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha traslado</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmision">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmision">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" href="#bg-info" onclick="buscarEntradaSalidaAlmacen(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <!--<div class="table-responsive">-->
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <!--F.Traslado	S/N	Origen	Producto	Cantidad	Pendiente	U. Medida-->
                                <tr>
                                    <th style='text-align:center;'>F. Traslado</th>
                                    <th style='text-align:center;'>S/N</th>
                                    <th style='text-align:center;'>Origen</th>
                                    <th style='text-align:center;'>Código</th>
                                    <th style='text-align:center;'>Producto</th>
                                    <th style='text-align:center;'>Cantidad</th>
                                    <th style='text-align:center;'>Pendiente</th>
                                    <th style='text-align:center;'>U. Medida</th>
                                    <th style='text-align:center;'>Opc.</th>
                                </tr>
                            </thead>
                        </table>
                        <!--</div>-->
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-eye" ></i> Ver detalle de reposiciones
                    </p>
                </div>
            </div>
        </div>

        <!--modal para el detalle del movimiento-->
        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="row" style="box-shadow: 0 0px 0px">
                                    <!--                            <div class="portlet-heading">
                                                                    <h3 id="nombreDocumentoTipo" class="portlet-title text-dark text-uppercase">
                                    
                                                                    </h3>                                
                                                                    <div class="clearfix"></div>
                                                                </div>-->
                                    <div id="portlet1" class="row">
                                        <div class="portlet-body" >
                                            <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                            </form>
                                        </div>

                                    </div>
                                </div> <!-- /Portlet -->
                            </div>
                            <div class="col-lg-12 ">
                                <div class="portlet" style="box-shadow: 0 0px 0px">
                                    <div id="portlet2" class="row">
                                        <div class="portlet-body">
                                            <table id="datatable2" class="table table-striped table-bordered">
                                                <thead id="theadDetalle">

                                                </thead>
                                                <tbody id="tbodyDetalle">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12">
                                            <label>COMENTARIO </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea type="text" id="txtComentario" name="txtComentario" readonly=""
                                                          class="" value="" maxlength="500" style="height: auto;width: 
                                                          100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 

                        </div>
                    </div> 
                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            </div>


                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="input-group m-t-10" style="float: right">
                                    <!--<a class="btn btn-success" onclick="editarComentarioDocumento()"><i class="fa fa-save"></i> Guardar</a>-->
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                                </div>
                                <!--</div>-->  
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!--Fin detalle-->

        <!--modal para el detalle del reporte-->
        <div id="modalDetalleReporte"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloDetalleReporte"></h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <!--<div class="table-responsive">-->
                                <table id="datatableDetalle" class="table table-striped table-bordered">
                                    <thead>
                                        <!--F.Traslado	S/N	Origen	Producto	Cantidad	Pendiente	U. Medida-->
                                        <tr>
                                            <th style='text-align:center;'>F. Traslado</th>
                                            <th style='text-align:center;'>S/N</th>
                                            <th style='text-align:center;'>Destino</th>
                                            <th style='text-align:center;'>Código</th>
                                            <th style='text-align:center;'>Producto</th>
                                            <th style='text-align:center;'>Cantidad</th>
                                            <th style='text-align:center;'>U. Medida</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!--</div>-->
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            </div>


                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="input-group m-t-10" style="float: right">
                                    <!--<a class="btn btn-success" onclick="editarComentarioDocumento()"><i class="fa fa-save"></i> Guardar</a>-->
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                                </div>
                                <!--</div>-->  
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!--Fin detalle-->
        
        <!--</div>-->  
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporte/entradaSalidaAlmacenVirtual.js"></script>
    </body>
</html>


