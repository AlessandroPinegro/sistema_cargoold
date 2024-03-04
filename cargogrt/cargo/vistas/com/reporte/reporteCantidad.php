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
                                    <div class="portlet-heading bg-info m-b-0" id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de busqueda">
                                        <h3  class="portlet-title">
                                            <a id="tituloBuscador" > Buscador</a>
                                        </h3>
                                        <div class="portlet-widgets">
                                            <span class="divider"></span>
                                            <a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>
                                            </a>
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
                                            <label>Tipo de documento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoDocumento" id="cboTipoDocumento" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Fecha emision</label>
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
                                        <div class="form-group col-md-6">
                                            <label>Fecha vencimiento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaVencimiento">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaVencimiento">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscar()" value="enviar" class="btn btn-success"><i class="fa fa-search"></i>&ensp;Buscar</button>&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body" id="muestrascroll">
                    <div class="col-md-12 col-sm-12 col-xs-12" id="scroll">
                        <div class="table">
                            <div id="dataList">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>Fecha de emisión</th>
                                            <th style='text-align:center;'>Organizador</th>
                                            <th style='text-align:center;'>Tipo de documento</th> 
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Serie</th>
                                            <th style='text-align:center;'>Número</th>
                                            <th style='text-align:center;'>Fecha de vencimiento</th>
                                            <th style='text-align:center;'>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align:right">Total:</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <!--<i class="fa fa-file-text" style="color:#088A68;"></i> Detalle de  la informaci&oacute;n &nbsp;&nbsp;&nbsp;-->
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
                        <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                        <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo 
                    </p>
                </div>
            </div>
        </div>
        <!--</div>-->
        <script src="vistas/com/reporte/reporteCantidad.js"></script>
    </body>
</html>


