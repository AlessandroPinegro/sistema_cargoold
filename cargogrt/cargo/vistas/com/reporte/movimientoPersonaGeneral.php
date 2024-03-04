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
                                        <div class="form-group col-md-6">
                                            <label>Empresa</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEmpresaMPG" id="cboEmpresaMPG" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Tipo de documento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoDocumentoMPG" id="cboTipoDocumentoMPG" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Producto</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBienMPG" id="cboBienMPG" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>Persona</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersonaMPG" id="cboPersonaMPG" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
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
                                        <!--<button id="btnBuscar" type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscarMovimientoPersonaGeneral()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <!--<button id="btnBuscar" type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="obtenerCantidadesTotalesMovimientoPersona();" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->
                                        <button type="button" href="#bg-info" onclick="buscarMovimientoPersonaGeneral(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <!--<div class="table">-->
                            <div id="dataList">
                                <table id="dataTableMovimientoPersona" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <!--<th style='text-align:center;'>F. Emisión</th>-->
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <!--<th style='text-align:center;'>F. Venc.</th>-->
                                            <th style='text-align:center;'>Producto</th>
                                            <th style='text-align:center;'>Tipo de documento</th>
                                            <th style='text-align:center;'>Empresa</th>
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Serie</th>
                                            <th style='text-align:center;'>Número</th>
                                            <th style='text-align:center;'>Cantidad</th>
                                            <th style='text-align:center;'>Total</th>
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
                            </div>
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
        <!--</div>-->
        <script src="vistas/com/reporte/movimientoPersonaGeneral.js"></script>
    </body>
</html>


