
<style type="text/css" media="screen">
    .popover{
        max-width: 100%; 
    }
</style>

<div class="page-title">
    <!--<h3 id="titulo" class="title"></h3>-->
    <h3 class="title">Reporte de productos únicos</h3>
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
                                    <a id="imprimir" onclick="imprimirBienUnico14()" title="Imprimir (Formato de 14 stickers)">
                                        <i class="ion-android-printer">14 &nbsp;</i>
                                    </a>
                                    <a id="imprimir" onclick="imprimirBienUnico()" title="Imprimir (Formato de 22 stickers)">
                                        <i class="ion-android-printer">22 &nbsp;</i>
                                    </a>
                                    <a id="imprimir" onclick="imprimirBienUnicoNuevo()" title="Imprimir (Formato nuevo de 22 stickers)">
                                        <i class="ion-android-printer">22 &nbsp;</i>
                                    </a>
                                    <a id="loaderBuscar" onclick="loaderBuscar()" title="Buscar">
                                        <i class="ion-refresh"></i>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </div>

                    <div id="bg-info" class="panel-collapse collapse in">
                        <div class="portlet-body">
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

                                <div class="form-group col-md-4 ">                                            
                                    <label>Producto</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboBien" id="cboBien" class="select2" multiple onchange="onChangeBien()">
                                        </select>
                                    </div>
                                </div>  
                            </div>
                            
                            <div class="row">                                        
                                <div class="form-group col-md-4">
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Nro. Guía</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" id="txtNroGuia" name="txtNroGuia" class="form-control" aria-required="true" value="" maxlength="50" placeholder="001-000001">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Fecha guía</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaEmision">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-4 ">
                                    <label>Proveedor</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboProveedor" id="cboProveedor" class="select2" multiple>
                                        </select>
                                    </div>
                                </div>              
                                <div class="form-group col-md-4 ">
                                    <label>Cliente</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboCliente" id="cboCliente" class="select2" multiple>
                                        </select>
                                    </div>
                                </div>                               
                            </div>
                            
                            <div class="row" id="divContenedorDesdeHasta" hidden>
                                <div class="form-group col-md-4">
                                    <label>Desde</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" id="txtDesde" name="txtDesde" class="form-control" aria-required="true" value="" maxlength="40">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Hasta</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" id="txtHasta" name="txtHasta" class="form-control"  aria-required="true"  value="" maxlength="40">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Prod. Único</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" id="txtProdUnico" name="txtProdUnico" class="form-control" aria-required="true" value="" maxlength="40">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="form-group col-md-6">                                                
                                                <label>Posición inical</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="number" id="txtPosicionInicial" name="txtPosicionInicial" class="form-control" style="text-align: right" aria-required="true"  value="1" onkeyup="if(this.value.length>0){ if(this.value>16){this.value=16;}else if(this.value<1){this.value=1}}" onchange="if(this.value.length>0){ if(this.value>14){this.value=14;}else if(this.value<1){this.value=1}}">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Estado</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboEstadoBienUnico" id="cboEstadoBienUnico" class="select2">
                                                        <option value="0">Todos</option>
                                                        <option value="1">Disponible</option>
                                                        <option value="2">Atendido</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <!--<div class="modal-footer">-->
                                    <label>&nbsp;</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" onclick="imprimirBienUnicoNuevo();"  class="btn btn-primary" style="float: right;margin-right: 10px;" title="Imprimir nuevo formato de 22 stickers">Imprimir (22)</button>
                                        <button type="button" onclick="imprimirBienUnico();"  class="btn btn-success w-md" style="float: right;margin-right: 10px;" title="Imprimir formato de 22 stickers">Imprimir (22)</button>
                                        <button type="button" onclick="imprimirBienUnico14();"  class="btn btn-info w-md" style="float: right;margin-right: 10px;" title="Imprimir formato de 14 stickers">Imprimir (14)</button>
                                        
                                        <!--<button type="button" onclick="buscarBienUnico(1)"  class="btn btn-purple"  style="float: right;margin-right: 10px;"> Buscar</button>-->
                                    </div>
                                    <!--</div>-->
                                </div>
                                <div class="form-group col-md-4">
                                    <!--<div class="modal-footer">-->
                                    <label>&nbsp;</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        
                                        <button type="button" onclick="buscarBienUnico(1)"  class="btn btn-purple"  style="float: right;margin-right: 10px;"> Buscar</button>
                                    </div>
                                    <!--</div>-->
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="divContenido"></div>
        <div id="divDataTable">
            <div class="panel panel-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <!--<div class="table-responsive">-->
                    <table id="dataTableProductoUnico" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style='text-align:center;'>Prod. Único</th>
                                <th style='text-align:center;'>Producto</th>
                                <!--<th style='text-align:center;'>G.P. Principal</th>-->
                                <th style='text-align:center;'>Grupo</th>
                                <th style='text-align:center;'>Fecha</th>
                                <th style='text-align:center;'>G.Recepción</th>
                                <th style='text-align:center;'>Proveedor</th>
                                <th style='text-align:center;'>Cliente</th>                                
                                <th style='text-align:center;'>Estado</th>
                                <th style='text-align:center;'>Acc.</th>                                
                            </tr>
                        </thead>
                    </table>
                    <!--</div>-->
                </div>
            </div>
            <div style="clear:left">
                <p><b>Leyenda:</b>&nbsp;&nbsp;
                    <i class="ion-cube" style="color: #615ca8"></i> Ver detalle del producto &nbsp;&nbsp;
                    <i class="fa fa-print" style="color:green" ></i> Imprimir QR &nbsp;&nbsp;
                    <i class="fa fa-eye" style="color:#1ca8dd;"></i> Ver detalle del documento
                </p>
            </div>
        </div>

        <div id="divDetalleBienUnico">
            <div class="panel panel-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4 class="modal-title" id="tituloDetalle"></h4> 
                    <br>
                    <table id="dataTablaDetalleBienUnico" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style='text-align:center;'>Fecha</th>
                                <th style='text-align:center;'>Documento</th>
                                <th style='text-align:center;'>S|N</th>
                                <th style='text-align:center;'>Persona</th> 
                            </tr>
                        </thead>
                    </table> 
                </div>
            </div>
            <div class="row" style="margin-right: 3px;">                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-danger" onclick="regresarDataTable()"  style="float: right"><i class="ion-ios7-undo"></i> Regresar</button> 
                </div>
            </div>
        </div>

    </div>
</div>

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

<form target="_blank" action="script/almacen/qrBienUnico.php" method="post" id="formQR" name="formQR">                                
    <input type="hidden" name="bienUnicoIdHidden" id="bienUnicoIdHidden" value=""/>
    <input type="hidden" name="posInicialHidden" id="posInicialHidden" value=""/>
</form>

<script src="vistas/com/reporte/reporte.js"></script>
<script src="vistas/com/bienUnico/bienUnico.js"></script>