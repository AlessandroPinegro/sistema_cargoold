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

                                            <a onclick="exportarReportePorCuentaFechaExcel()" title="">
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

                                        <div class="form-group col-md-6">
                                            <label>Fecha</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaEmision">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 ">
                                            <label>Cuenta</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboCuenta" id="cboCuenta" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <!--                                        <div class="form-group col-md-6 ">
                                                                                    <label>Tipo documento</label>
                                                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <select name="cboTipoDocumento" id="cboTipoDocumento" class="select2" multiple>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>-->
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" onclick="exportarReportePorCuentaFechaExcel();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  
                                        <!--<button type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscarPorCuentaFecha()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <button type="button" href="#bg-info" onclick="buscarPorCuentaFecha(1)" value="enviar" class="btn btn-purple"> Buscar</button>
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
                            <table id="datatable" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <!--<th style='text-align:center; vertical-align: middle;' rowspan="2">Fecha creación</th>-->
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Fecha pago</th>
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Tipo documento</th>
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">S | N</th>
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">COD</th>                                    
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Encargado</th>                         
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Tercero</th>
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Detalle</th>
                                        <th style='text-align:center; vertical-align: middle;' rowspan="2">Cuenta</th>
                                        <th style='text-align:center; vertical-align: middle;' colspan="3">Caja chica</th>
                                        <th style='text-align:center; vertical-align: middle;' colspan="3">BCP</th>
                                        <th style='text-align:center; vertical-align: middle;' colspan="3">BBVA</th>
                                        <th style='text-align:center; vertical-align: middle;' colspan="3">ScotiaBank</th>
                                    </tr>
                                    <tr>
    <!--                                    <th style='text-align:center;'>Fecha creación</th>
                                        <th style='text-align:center;'>Fecha emisión</th>
                                        <th style='text-align:center;'>S | N</th>
                                        <th style='text-align:center;'>Tipo documento</th>
                                        <th style='text-align:center;'>COD</th>                                    
                                        <th style='text-align:center;'>Encargado</th>
                                        <th style='text-align:center;'>Detalle</th>
                                        <th style='text-align:center;'>Cuenta</th>-->
                                        <th style='text-align:center;'>Ing.</th>
                                        <th style='text-align:center;'>Sal.</th>
                                        <th style='text-align:center;'>SALDO</th>
                                        <th style='text-align:center;'>Ing.</th>
                                        <th style='text-align:center;'>Sal.</th>
                                        <th style='text-align:center;'>SALDO</th>
                                        <th style='text-align:center;'>Ing.</th>
                                        <th style='text-align:center;'>Sal.</th>
                                        <th style='text-align:center;'>SALDO</th>
                                        <th style='text-align:center;'>Ing.</th>
                                        <th style='text-align:center;'>Sal.</th>
                                        <th style='text-align:center;'>SALDO</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th colspan="8" style="text-align:right">Totales S/.</th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                </tfoot>
                            </table>
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
                                <table id="datatableDocumentoPorCuentaFecha" class="table table-striped table-bordered">
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
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporteCajaBancos/porCuentaPorFecha.js"></script>
    </body>
</html>


