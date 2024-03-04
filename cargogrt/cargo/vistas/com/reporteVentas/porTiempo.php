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

                                            <a onclick="exportarReportePorTiempoExcel()" title="">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>&nbsp;
                                            <a id="loaderBuscar" onclick="loaderBuscar()">
                                                <i class="ion-refresh"></i>
                                            </a>
                                            <span class="divider"></span>
                                            <!--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>-->
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            </div>

                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <div class="row">                                        
                                        <div class="form-group col-md-8">
                                            <label>Fecha</label>
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
                                        
                                        <div class="form-group col-md-4 ">                                            
                                            <label>Tiempo</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTiempo" id="cboTiempo" class="select2">
                                                </select>
                                            </div>
                                        </div>                                        
                                    </div>
                                    
                                    <div class="row">      
                                        <div class="form-group col-md-4 ">
                                            <label>Empresa</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTienda" id="cboTienda" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" onclick="exportarReportePorTiempoExcel();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  
                                        <!--<button type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscarPorTiempo()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <button type="button" href="#bg-info" onclick="buscarPorTiempo(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <ul class="nav nav-tabs"> 
                                <li class="active"> 
                                    <a href="#grafico" data-toggle="tab" aria-expanded="true"> 
                                        <span class="visible-xs"><i class="ion-pie-graph"></i></span> 
                                        <span class="hidden-xs"><i class="ion-pie-graph"></i> Gráfico</span> 
                                    </a> 
                                </li> 
                                <li class=""> 
                                    <a href="#tabla" data-toggle="tab" aria-expanded="false"> 
                                        <span class="visible-xs"><i class="fa  fa-table"></i></span> 
                                        <span class="hidden-xs"><i class="fa  fa-table"></i> Tabla informativa</span> 
                                    </a> 
                                </li>  
                            </ul> 
                            
                            <div class="tab-content"> 
                                <div class="tab-pane active" id="grafico"> 
                                    <div>
                                        <div class="col-lg-12">
                                            <div class="portlet"><!-- /primary heading -->
                                                <div class="portlet-heading">
                                                    <h3 class="portlet-title text-dark text-uppercase">
                                                        Ventas en dólares
                                                    </h3>
                                                    <div class="portlet-widgets">
                                                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet4"><i class="ion-minus-round"></i></a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div id="portlet4" class="panel-collapse collapse in">
                                                    <div class="portlet-body">
                                                        <div id="tortaDolares">
                                                            <div id="tortaDolaresContenedor" class="flot-chart" style="height: 450px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- /Portlet -->
                                        </div> <!-- end col -->
                                        <div class="col-lg-6" hidden="hidden">
                                            <div class="portlet"><!-- /primary heading -->
                                                <div class="portlet-heading">
                                                    <h3 class="portlet-title text-dark text-uppercase">
                                                       Ventas en soles
                                                    </h3>
                                                    <div class="portlet-widgets">
                                                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet3"><i class="ion-minus-round"></i></a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div id="portlet3" class="panel-collapse collapse in">
                                                    <div class="portlet-body">
                                                        <div id="tortaSoles">
                                                            <div id="tortaSolesContenedor" class="flot-chart" style="height: 320px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- /Portlet -->
                                        </div> <!-- end col -->
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabla"> 
                                    <!--<div class="table-responsive">-->
                                    <table id="datatable" class="table table-striped table-bordered" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style='text-align:center; vertical-align: middle;' rowspan="2">Tiempo</th>
                                                <th style='text-align:center; vertical-align: middle;' colspan="2">Soles</th>
                                                <th style='text-align:center; vertical-align: middle;' colspan="2">Dólares</th>
                                            </tr>
                                            <tr>
                                                <th style='text-align:center;'>Núm. ventas</th>
                                                <th style='text-align:center;'>Total ventas</th> 
                                                <th style='text-align:center;'>Núm. ventas</th>
                                                <th style='text-align:center;'>Total ventas</th> 
                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th colspan="1" style="text-align:right">Totales:</th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    <!--</div>-->
                    </div>
                </div>
<!--                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-eye" ></i> Ver detalle
                    </p>
                </div>-->
            </div>
        </div>

        <div id="modal-detalle-documentos-servicios"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">             
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Verificación de stock</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="table">
                            <div id="dataList">
                                <table id="datatableDocumentoPorTiempo" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>                                            
                                            <th style='text-align:center;'>F. Creacion</th>
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <th style='text-align:center;'>Tipo documento</th>
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Serie</th>
                                            <th style='text-align:center;'>Número</th> 
                                            <th style='text-align:center;'>F. Venc.</th>
                                            <th style='text-align:center;'>Estado</th>                                            
                                            <th style='text-align:center;'>Cantidad</th>
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
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.time.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.resize.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.pie.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.selection.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.stack.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.crosshair.js"></script>
        <!--<script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.init.js"></script>-->
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporteVentas/porTiempo.js"></script>
    </body>
</html>


