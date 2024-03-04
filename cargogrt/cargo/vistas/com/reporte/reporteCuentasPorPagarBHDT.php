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
            <h3 id="tituloModal" class="title">Cuentas por pagar BHDT</h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div  class="portlet" >
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="portlet-heading bg-info m-b-0" 
                                         onclick="colapsarBuscador()"
                                         id="idPopover" title="" data-toggle="popover" 
                                         data-placement="top" data-content="" 
                                         data-original-title="Criterios de búsqueda"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">
                                        <div class="portlet-widgets">
                                            <a id="loaderBuscarDeuda" onclick="loaderBuscarDeuda()">
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
                                            <label>Proveedor</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersonaDeuda" id="cboPersonaDeuda" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <!--                                                    <div class="form-group col-md-6">
                                                                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
                                                    <input type="hidden" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaVencimiento">
<!--                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>-->
                                                    <!--</div>-->
                                                    <div class="form-group col-md-12">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaVencimiento">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label class="cr-styled">
                                                    <input type="checkbox" name="chk_mostrar" id="chk_mostrar">
                                                    <i class="fa"></i> 
                                                    Mostrar pagados
                                                </label>
                                            </div>                                            
                                        </div>
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label class="cr-styled">
                                                    <input type="checkbox" name="chk_mostrar_lib" id="chk_mostrar_lib" checked>
                                                    <i class="fa"></i> 
                                                    Mostrar Programados
                                                </label>
                                            </div>                                            
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <!--<button id="btnBuscar" type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscar()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <!--<button type="button" href="#bg-info" onclick="buscar(1)" value="enviar" class="btn btn-info"> Buscar</button>-->
                                        <div class="btn-group dropdown">
                                            <button type="button" onclick="buscar(1)" class="btn btn-info">Buscar</button>
                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a onclick="exportarExcelComprasBHDT()">F. Compras BHDT</a></li>
                                            </ul>
                                        </div>
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
                        <div id="dataList">
                            <table id="dataTableDeuda" class="table table-striped table-bordered" widht="300px">
                                <thead>
                                    <tr>
                                        <th style='text-align:center; vertical-align: middle;'  ></th>
                                        <th style='text-align:center; vertical-align: middle;'  >Proveedor</th>      
                                        <th style='text-align:center; vertical-align: middle;'  >RUC/DNI</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Tipo documento</th> 
                                        <th style='text-align:center; vertical-align: middle;'  >Número</th>
                                        <th style='text-align:center; vertical-align: middle;'  >F. Emisión</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Descripción</th>
                                        <th style='text-align:center; vertical-align: middle;'  >F. Recepción</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Moneda</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Sub Total</th>
                                        <th style='text-align:center; vertical-align: middle;'  >IGV</th>                                        
                                        <th style='text-align:center; vertical-align: middle'   >Importe Pagado</th>
                                        <th style='text-align:center; vertical-align: middle'   >Deuda programada</th>
                                        <th style='text-align:center; vertical-align: middle'   >Deuda por programar</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Total</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Condición de pago</th>
                                        <th style='text-align:center; vertical-align: middle;'  >F. Vencimiento</th>
                                        <th style='text-align:center; vertical-align: middle;'  >¿Pagó?</th>
                                        <th style='text-align:center; vertical-align: middle;'  >F.Pago detracción</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Fec.P.P.P</th>
                                        <!--<th style='text-align:center; vertical-align: middle;'  >Fec.D.P</th>-->
                                        <th style='text-align:center; vertical-align: middle;'  >D.T.D.V</th>
                                        <th style='text-align:center; vertical-align: middle;'  >Acciones</th>
                                    </tr>

                                </thead>

                            </table>
                            <!--</div>--> 
                            <div style="clear:left">
                                <p><br>
                                    <b>Leyenda:</b>&nbsp;&nbsp;<br>
                                    <i class="fa fa-flag" style="color:#DF0101;"></i>&nbsp;FV < FA &nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-flag" style="color:#FFC200;"></i>&nbsp;FV >= FA ( Pero menor o igual a 3 días ) &nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-flag" style="color:#01DF01;"></i>&nbsp;FV > FA ( Mayor de 3 días ) &nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-eye" style="color:#1ca8dd;"></i>&nbsp;Visualizar documentos de pago &nbsp;&nbsp;&nbsp;
                                    <br>
                                    <b>FV:</b> Fecha de vencimiento<br>
                                    <b>FA:</b> Fecha actual<br>
                                    <b>Fec.P.P.P: </b> Fecha programada de pago al proveedor<br>
                                    <!--<b>Fec.D.P: </b> Fecha depósito al proveedor<br>-->
                                    <b>D.T.D.V: </b> Dias transcurridos despues del vencimiento
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal visualizar-->
            <div id="modal_detalle_pagos"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:80%;"> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                            <h4 class="modal-title"></h4> 
                        </div> 
                        <div class="modal-body"> 
                            <div class="table">
                                <div id="dataList">
                                    <table id="datatableDetallePago" class="table table-striped table-bordered" >
                                        <thead>
                                            <tr>
                                                <th style='text-align:center;'>Fecha de pago</th>
                                                <th style='text-align:center;'>Documento tipo/Efectivo</th>
                                                <th style='text-align:center;'>Número</th>
                                                <!--<th style='text-align:center;'>Fecha Vencimiento</th>-->
                                                <th style='text-align:center;'>Moneda</th>
                                                <th style='text-align:center;'>Monto</th>
                                                <!--<th style='text-align:center;'>Discrepancia</th>-->
                                            </tr>
                                        </thead>
<!--                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>-->
                                    </table>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                        </div> 
                    </div> 
                </div>
            </div>

            <!--Modal programacion pago-->
            <div id="modalProgramacionPago"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog" style="width:80%;"> 
                    <div class="modal-content" style="padding-bottom: 10px;padding-top: 15px;"> 
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                            <h4 class="modal-title" id="tituloModalPP"></h4> 
                        </div> 
                        <div class="modal-body"> 
                            <table id="datatableDetallePP" class="table table-striped table-bordered" >
                                <thead>                                        
                                    <!--Indicador	Días	Fecha programada	Importe	Estado-->
                                    <tr>
                                        <th style='text-align:center;'>Indicador</th>
                                        <th style='text-align:center;'>Días</th>
                                        <th style='text-align:center;'>Fecha programada</th>
                                        <th style='text-align:center;'>Importe</th>
                                        <th style='text-align:center;'>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div style="clear:left">
                            <p><b>Leyenda:</b>&nbsp;&nbsp;
                                <i class="fa fa-lock" style="color:red;"></i> Por liberar &nbsp;&nbsp;&nbsp;
                                <i class="fa fa-unlock" style="color:green;"></i> Liberado &nbsp;&nbsp;&nbsp;
                            </p>
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                        </div> 
                    </div> 
                </div>
            </div>

        </div>
        <!--</div>-->
        <script src="vistas/com/reporte/reporteCuentasPorPagarBHDT.js"></script>
    </body>
</html>


