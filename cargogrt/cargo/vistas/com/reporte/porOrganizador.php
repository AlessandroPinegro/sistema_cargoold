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

                                            <!--                                            <a onclick="exportarReporteXOrganizadorExcel()" title="">
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
                                        <div class="form-group col-md-6 ">
                                            <label>Organizador</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboOrganizador" id="cboOrganizador" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>Bien</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBien" id="cboBien" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Tipo de bien</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoBien" id="cboTipoBien" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
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
                                        <!--<button type="button" onclick="exportarReporteXOrganizadorExcel();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;"><i aria-hidden="true"></i>&ensp;Exportar reporte</button>&nbsp;&nbsp;-->  
                                        <!--<button type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscarXOrganizador()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <button type="button" href="#bg-info" onclick="buscarXOrganizador(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Bien</th>
                                    <th style='text-align:center;'>Organizador</th>
                                    <th style='text-align:center;'>Tipo bien</th> 
                                    <th style='text-align:center;'>Total S/.</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/reporte/porOrganizador.js"></script>
    </body>
</html>


