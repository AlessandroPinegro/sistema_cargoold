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
                                            <a onclick="exportarReporteReporteCompras()" title="">
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
                                        <div class="form-group col-md-6 ">
                                            <label>Proveedor</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersonaProveedor" id="cboPersonaProveedor" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Tipo de documento</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboTipoDocumentoMP" id="cboTipoDocumentoMP" class="select2" multiple>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Origen</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboOrigen" id="cboOrigen" class="select2" >
                                                    <option value="">Seleccione Origen</option>
                                                    <option value="Compras NC">Compras NC</option>
                                                    <option value="Compras EXT">Compras EXT</option>
                                                    <option value="Documento EAR">Documento EAR</option>
                                                    <option value="Operaciones">Operaciones</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha emisión</label>
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
                                        <button type="button" onclick="exportarReporteComprasProducto();" value="Exportar" name="env" id="env" class="btn btn-info w-md" style="border-radius: 0px;">&ensp;Exportar productos</button>&nbsp;&nbsp;  
                                        <button type="button" onclick="exportarReporteReporteCompras();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;  
                                        <button type="button" href="#bg-info" onclick="buscarReporteCompras(1)" value="enviar" class="btn btn-purple"> Buscar</button>
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
                            <table id="dataTableReporteCompras" class="table table-striped table-bordered">
                                <thead>

                                    <!--F. Creacion	F. Emisión	Tipo documento	Persona	Serie	Número-->                                        
                                    <tr>
                                        <th style='text-align:center;'>F. Creación</th>
                                        <th style='text-align:center;'>F. Emisión</th>
                                        <th style='text-align:center;'>Usuario</th>
                                        <th style='text-align:center;'>Tipo documento</th>
                                        <th style='text-align:center;'>Proveedor</th>
                                        <th style='text-align:center;'>Serie</th>
                                        <th style='text-align:center;'>Número</th>
                                        <th style='text-align:center;'>Origen</th>
                                        <th style='text-align:center;'>Total</th>
                                        <th style='text-align:center;'>Acciones</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th colspan="8" style="text-align:right">Totales:</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 id="nombreDocumentoTipo" class="modal-title text-dark text-uppercase">Visualización del documento</h4>                   
                    </div>
                    <div class="modal-body" style="padding-bottom: 5px;padding-top: 10px;"> 
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="row" id="formularioDetalleDocumento" >
                                </div>
                            </div>
                            <div class="col-lg-7 ">
                                <div class="row" >                                   
                                    <div class="form-group col-lg-12 col-md-12" hidden="true" id="formularioCopiaDetalle">                                            
                                        <table id="datatable2" class="table table-striped table-bordered">
                                            <thead id="theadDetalle">

                                            </thead>
                                            <tbody id="tbodyDetalle">

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12">
                                        <label>DESCRIPCION </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea type="text" id="txtDescripcion" name="txtDescripcionCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12">
                                        <br>
                                        <label>COMENTARIO </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea type="text" id="txtComentario" name="txtComentarioCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
                                        </div>
                                    </div>
                                    <!--</div>-->
                                </div>
                            </div> 
                        </div>
                    </div> 
                    <div class="modal-footer">
                        <!--<label>Correo *</label>-->
                        <div class="row">
                            <div class="input-group m-t-10" style="float: right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>  
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div><!-- /.modal -->

        <!--MODAL DE DOCUMENTOS RELACIONADOS-->
        <div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Documentos relacionados</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div id="linkDocumentoRelacionado">

                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div>
        <!--</div>-->
        <script src="vistas/com/reporte/reporteCompras.js"></script>
    </body>
</html>


