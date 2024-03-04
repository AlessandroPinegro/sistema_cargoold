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
            <h3 class="title">Reporte de ventas por cliente</h3>
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
                                            <a onclick="exportarReporteVentasPorCliente()" title="">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>&nbsp;                                            
                                            <a id="loaderBuscarVentas" onclick="loaderBuscarVentas()">
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
                                        <div class="form-group col-md-4 ">
                                            <label>Cliente</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersona" id="cboPersona" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                         <div class="form-group col-md-4">
                                            <label>Tipo de documento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoDocumentoMP" id="cboTipoDocumentoMP" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>                                       
                                        <div class="form-group col-md-4 ">
                                            <label>Empresa</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEmpresa" id="cboEmpresa" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-md-4 ">
                                            <label>Grupo de producto principal</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBienTipoPadre" id="cboBienTipoPadre" class="select2" onchange="obtenerBienTipoHijo()" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-4 ">
                                            <label>Grupo de producto secundario</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBienTipo" id="cboBienTipo" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Fecha emision</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmisionMP">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmisionMP">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" onclick="exportarReporteVentasPorCliente();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  
                                        <button type="button" href="#bg-info" onclick="buscarVentasPorCliente(1)" value="enviar" class="btn btn-purple"> Buscar</button>
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
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div id="tortaDolares">
                                                                    <div id="tortaDolaresContenedor" class="flot-chart" style="height: 320px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div id="tortaDolaresProductos" style="margin-top: 10px;">
                                                                    <div id="tortaDolaresProductosContenedor" class="flot-chart" style="height: 320px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- /Portlet -->
                                        </div> <!-- end col --> 
                                        <div class="col-lg-12" hidden="hidden">
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
                                                            <div id="tortaSolesContenedor" class="flot-chart" style="height: 160px;">
                                                            </div>
                                                        </div>
                                                        <div id="tortaSolesProductos" style="margin-top: 10px;">
                                                            <div id="tortaSolesProductosContenedor" class="flot-chart" style="height: 160px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- /Portlet -->
                                        </div> <!-- end col -->
                                    </div> 
                                </div>
                                <div class="tab-pane" id="tabla"> 
                                    <div class="table">
                                    <!--<div id="dataList" class="table-responsive">-->
                                        <table id="dataTableVentasPorCliente" class="table table-striped table-bordered">
                                            <thead>

        <!--F. Creacion	F. Emisión	Tipo documento	Persona	Serie	Número-->                                        
                                                <tr>
                                                    <th style='text-align:center;'>Cliente</th>
                                                    <th style='text-align:center;'>G.P. Principal</th>
                                                    <th style='text-align:center;'>G.P. Secundario</th>
                                                    <!--<th style='text-align:center;'>F. Creación</th>-->
                                                    <th style='text-align:center;'>F. Emisión</th>
                                                    <th style='text-align:center;'>Tipo documento</th>
                                                    <th style='text-align:center;'>S|N</th>
                                                    <th style='text-align:center;'>Total S/.</th>
                                                    <th style='text-align:center;'>Total $</th>
                                                    <th style='text-align:center;'>Opciones</th>
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" style="text-align:right">Totales:</th>
                                                    <th> </th>
                                                    <th> </th>
                                                    <th> </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div>
                </div>
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
                                                    <!--<th style='text-align:center;'>Organizador</th>-->
                                                    <th style='text-align:center;'>Cantidad</th>
                                                    <th style='text-align:center;'>U. Medida</th>
                                                    <th style='text-align:center;'>Descripcion</th> 
                                                    <th style='text-align:center;'>P. Unit.</th>
                                                    <th style='text-align:center;'>Total</th>
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
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.time.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.resize.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.pie.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.selection.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.stack.js"></script>
        <script src="vistas/libs/imagina/assets/flot-chart/jquery.flot.crosshair.js"></script>
        <script src="vistas/com/reporteVentas/porCliente.js"></script>
    </body>
</html>


