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
            .colorOC {
                color: #0000ff;
            }  
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
                                <!--                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="portlet-heading bg-purple m-b-0" 
                                                                         onclick="">
                                                                        <div class="portlet-widgets">
                                
                                                                            <a onclick="exportarReporteServiciosAtendidosExcel()" title="">
                                                                                <i class="fa fa-file-excel-o"></i>
                                                                            </a>&nbsp;
                                                                            <a id="loaderBuscar" onclick="loaderBuscar()">
                                                                                <i class="ion-refresh"></i>
                                                                            </a>
                                                                            <span class="divider"></span>
                                                                            <a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                                                <i class="ion-minus-round"></i>
                                                                            </a>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                </div>-->

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
                                <tr>
                                    <th style='text-align:center;'>Grupo producto</th>
                                    <th style='text-align:center;'>Código</th>
                                    <th style='text-align:center;'>Producto</th> 
                                    <th style='text-align:center;'>Proveedor</th> 
                                    <th style='text-align:center;'>Opción</th>
                                </tr>
                            </thead>
                        </table>
                        <!--</div>-->
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-eye" style="color: green"></i> Ver cotizaciones
                    </p>
                </div>
            </div>
        </div>

        <div id="modalCotizaciones"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">             
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Cotizaciones</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="table">
                            <div id="dataList">
                                <table id="datatableCotizaciones" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>                                            
                                            <th style='text-align:center;'>Cotización</th>
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <th style='text-align:center;'>Proveedor</th>
                                            <th style='text-align:center;'>Cantidad</th>
                                            <th style='text-align:center;'>Moneda</th> 
                                            <th style='text-align:center;'>Precio</th> 
                                            <th style='text-align:center;'>O.C.</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div><!--
        <!--</div>-->  
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporte/reporteCotizaciones.js"></script>
    </body>
</html>


