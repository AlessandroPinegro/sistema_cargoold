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
            <h3 class="title">Reporte de ventas por vendedor</h3>
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
                                            <a onclick="exportarReporteVentasPorVendedor()" title="">
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
                                            <label>Vendedor</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersonaVendedor" id="cboPersonaVendedor" class="select2">
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
                                        <button type="button" onclick="exportarReporteVentasPorVendedor();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  
                                        <button type="button" href="#bg-info" onclick="buscarVentasPorVendedor(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table">
                            <!--<div id="dataList" class="table-responsive">-->
                                <table id="dataTableVentasPorVendedor" class="table table-striped table-bordered">
                                    <thead>                                      
                                        <tr>
                                            <th style='text-align:center;'>Vendedor</th>
                                            <th style='text-align:center;'>G.P. Principal</th>
                                            <th style='text-align:center;'>G.P. Secundario</th>
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <th style='text-align:center;'>Tipo documento</th>
                                            <th style='text-align:center;'>Cliente</th>
                                            <th style='text-align:center;'>S|N</th>
                                            <th style='text-align:center;'>Total S/.</th>
                                            <th style='text-align:center;'>Total $</th>                                            
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right">Totales:</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--</div>-->
        <script src="vistas/com/reporteVentas/porVendedor.js"></script>
    </body>
</html>


