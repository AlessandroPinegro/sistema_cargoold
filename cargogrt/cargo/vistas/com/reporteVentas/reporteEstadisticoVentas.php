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
        
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="vistas/libs/imagina/assets/morris/morris.css">  
        <!--<link rel="stylesheet" href="assets/morris/morris.css">-->
                       
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

                                            <!--<a onclick="exportarReporteReporteEstadisticoVentasExcel()" title="">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>&nbsp;-->
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
                                        
                                        <div class="form-group col-md-4 ">
                                            <label>Empresa</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEmpresa" id="cboEmpresa" class="select2" multiple>
                                                </select>
                                            </div>                                            
                                        </div>
                                        <div class="form-group col-md-4 ">
                                            <label>Tipo de documento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 ">
                                            <label>Tipo Frecuencia</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoFrecuencia" id="cboTipoFrecuencia" class="select2">
                                                </select>
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4 ">
                                            <label>Producto</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBien" id="cboBien" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 ">
                                            <label>Grupo de producto</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoBien" id="cboTipoBien" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
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
                                    </div>

                                    <div class="modal-footer">
                                        <!--<button type="button" onclick="exportarReporteReporteEstadisticoVentasExcel();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  -->
                                        <button type="button" href="#bg-info" onclick="buscarReporteEstadisticoVentas(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Fecha Emisión</th>
                                    <th style='text-align:center;'>Bien</th>
                                    <th style='text-align:center;'>Frecuencia</th>                                    
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> 
                -->
                
                <!-- Grafico -->
                <div id="divGrafico" hidden>
                    <div class="row">
                        <!--  Line Chart -->
                        <div class="col-sm-12">
                            <div class="portlet"><!-- /primary heading -->
                                <div class="portlet-heading">
                                    <h3 class="portlet-title text-dark">

                                    </h3>
                                    <!--<div class="portlet-widgets">
                                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                                        <span class="divider"></span>
                                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet4"><i class="ion-minus-round"></i></a>
                                        <span class="divider"></span>
                                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                                    </div>-->
                                    <div class="clearfix"></div>
                                </div>
                                <div id="portlet4" class="panel-collapse collapse in">
                                    <div class="portlet-body table-responsive">
                                        <div id="graficoReporteEstadisticoVentas" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div> <!-- /Portlet -->
                        </div>
                    </div> <!-- End row-->
                    <div style="clear:left">
                        <p><b>Leyenda:</b>&nbsp;&nbsp;</p>
                        <div id="divLeyenda"></div>
                    </div>
                </div>
                <!-- Fin Grafico -->
                
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
                                <table id="datatableReporteEstadisticoVentas" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>                                            
                                            <th style='text-align:center;'>Bien</th>
                                            <th style='text-align:center;'>Organizador</th>
                                            <th style='text-align:center;'>Tipo bien</th> 
                                            <th style='text-align:center;'>Unidad de medida</th>
                                            <th style='text-align:center;'>Stock</th>
                                            <th style='text-align:center;'>Frecuencia</th>
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
        <!--Morris Chart-->
        <script src="vistas/libs/imagina/assets/morris/morris.min.js"></script>
        <script src="vistas/libs/imagina/assets/morris/raphael.min.js"></script>
        
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporteVentas/reporteEstadisticoVentas.js"></script>
        
        
        <!--<script src="../../libs/imagina/assets/morris/morris.init.js"></script>-->
    </body>
</html>


