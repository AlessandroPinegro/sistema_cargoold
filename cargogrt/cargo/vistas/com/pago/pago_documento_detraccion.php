<html lang="es">
    <head>
        <style type="text/css" media="screen">

            #datatable td{
                vertical-align: middle;
                max-width: 90%;
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
                                            <a id="loaderBuscarVentas" onclick="loaderBuscarVentas()">
                                                <i class="ion-refresh"></i>
                                            </a>
                                            <span class="divider"></span>
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
                                        <div class="form-group col-md-3 ">
                                            <label>Serie</label>
                                            <input type="text" id="serie" name="txtSerie" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="obtenerDatosBusqueda()" aria-expanded="true">
                                        </div>
                                        <div class="form-group col-md-3 ">
                                            <label>Número</label>
                                            <input type="text" id="numero" name="txtNumero" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="obtenerDatosBusqueda()" aria-expanded="true">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Fecha emisión:</label>
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
                                        <div class="form-group col-md-6 "></div>
                                    </div>
                                    <div class="modal-footer">                                                                                
                                        <button type="button" href="#bg-info" onclick="buscarReporteCompras(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="datatable2" class="table-responsive"></div>
                    </div>
                </div>
            </div>   

            <div style="clear:left">
                <p><b>Leyenda:</b>&nbsp;&nbsp;   
                    <i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;
                    <i class='fa fa-money' style='color:#00A41A;'></i> Pagar
                </p>
            </div>
            <!--</div>-->

            <!--                <div class="panel panel-body" >
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div id="datatable2" style="width:100%"></div>
                                </div>
            
                                <div style="clear:left">
                                    <p><b>Leyenda:</b>&nbsp;&nbsp;   
                                        <i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;
                                        <i class='fa fa-usd' style='color:#E8BA2F;'></i> Pagar
                                    </p>
                                </div>
                            </div>-->
        </div>
        <!--</div>-->
        <!--INICIO MODAL DETALLE DOCUMENTO-->
        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="row" style="box-shadow: 0 0px 0px">
                                    <div id="portlet1" class="row">
                                        <div class="portlet-body" >
                                            <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                            </form>
                                        </div>

                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-12 ">
                                <div class="portlet" style="box-shadow: 0 0px 0px">
                                    <div id="portlet2" class="row">
                                        <div class="portlet-body">
                                            <div id="tabDistribucion">
                                                <ul id="tabsDistribucionMostrar"  class="nav nav-tabs nav-justified">
                                                    <li class="active">
                                                        <a href="#detalle" data-toggle="tab" aria-expanded="true" title="Detalle"> 
                                                            <span class="hidden-xs">Detalle del documento</span> 
                                                        </a> 
                                                    </li> 
                                                    <li> 
                                                        <a href="#distribucion" data-toggle="tab" aria-expanded="false" title="Distribución Contable"> 
                                                            <span class="hidden-xs">Distribución contable</span> 
                                                        </a> 
                                                    </li>
                                                </ul>
                                                <div id="div_contenido_tab" class="tab-content">
                                                    <div class="tab-pane active" id="detalle">
                                                        <table id="datatable2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalle">
                                                            </thead>
                                                            <tbody id="tbodyDetalle">
                                                            </tbody>
                                                        </table>
                                                    </div>                                            
                                                    <div class="tab-pane" id="distribucion" hidden="">
                                                        <table id="datatableDistribucion2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalleCabeceraDistribucion">

                                                            </thead>
                                                            <tbody id="tbodyDetalleDistribucion">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12">
                                            <label>COMENTARIO </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea type="text" id="txtComentario" name="txtComentario" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 

                        </div>
                    </div> 
                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
                            <div class="input-group m-t-10" style="float: right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <!--FIN MODAL DETALLE DOCUMENTO-->


        <!--inicio modal nuevo documento pago con documento-->
        <div id="modalNuevoDocumentoPagoConDocumentoPago"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog "> 
                <div class="modal-content"> 
                    <div class="modal-header" style="padding-bottom: 0px;"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
                                    <h4 class="modal-title text-dark text-uppercase">Nuevo documento Pago Detracción</h4> 
                                </div>      
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style="margin-top: -15px;">
                                    <h4>                                   
                                        <select id="cboPeriodo" name="cboPeriodo" class="select2"  style="width: 100%">         
                                        </select>                                  
                                    </h4>
                                </div>                                      
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&ensp;<i class="ion-close-round" tooltip-btndata-toggle='tooltip' title="Cerrar"></i></button> 
                                    <!--<span class="divider"></span>-->
                                    <!--<button type="button" class="close" onclick="getAllProveedor()"><i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i></button>--> 
                                </div>
                            </div> 
                        </div>
                    </div> 
                    <div class="modal-body" style="padding-top: 10px;"> 
                        <!--<select name="cboClientePagoPago" id="cboClientePagoPago" class="select2" hidden=""></select>-->
                        <!--                        <div style="min-height: 75px;height: auto;">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <h3 class="text-dark text-uppercase">
                                                                    <select name="cboDocumentoTipoNuevoPagoConDocumentoPago" id="cboDocumentoTipoNuevoPagoConDocumentoPago" class="select2"></select>
                                                                </h3>   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                        <div id="contenedorDocumentoTipoNuevo" style="min-height: 75px;height: auto;">
                            <form  id="formNuevoDocumentoPagoConDocumentoPago"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <div class="row">
                                    <div class="form-group col-md-12">

                                    </div>
                                </div>
                            </form>
                        </div>  

                    </div> 
                    <div class="modal-footer"> 
                        <div class="portlet-widgets">
                            <button type="button" onclick="enviarDocumento()" value="enviar" name="btnEnviar" id="btnEnviar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Guardar y Pagar</button>&nbsp;&nbsp;
                            <span class="divider"></span>
                            <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                        </div>
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal --> 
        <!--fin modal nuevo documento pago con documento -->
    </div>     
    <script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
    <script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>  
    <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
    <script src="vistas/libs/imagina/js/jquery.tool.js"></script>
    <script src="vistas/com/pago/pago_documento_detraccion.js"></script>
</body>
</html>


