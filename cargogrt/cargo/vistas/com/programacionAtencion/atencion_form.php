
<div class="page-title">
    <h3 id="titulo" class="title"><?php echo $_GET['winTitulo']; ?> atención</h3>
</div>
<!--<div class="row">-->


<div class="panel panel-default">
    <input type="hidden" id="documentoId" value="<?php echo $_GET['docId']; ?>" />
    <input type="hidden" id="patencionDetalleId" value="<?php echo $_GET['patencionDetalleId']; ?>" />

    <div class="panel-heading" style="border-bottom-width: 0px;padding-bottom: 0px;">      
        <div class="portlet" style="margin-bottom: 0px;">
            <div class="portlet-heading bg-success">
                <h3 class="portlet-title" id="cabeceraAtencion">
                </h3>
                <div class="portlet-widgets">
                    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary"><i class="ion-minus-round"></i></a>                    
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="bg-primary" class="panel-collapse collapse in">
                <div class="portlet-body" style="padding-left: 0px;padding-right: 0px;padding-bottom: 0px;font-size: 14px;">
                    <table id="dataTableMovimientoBienDetalle" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align:center">N°</th>
                                <th style="text-align:center">Producto</th>
                                <th style="text-align:center">U.Medida</th>
                                <th style="text-align:center">Cantidad</th>
                                <th style="text-align:center">Atenciones</th>
                                <th style="text-align:center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="panel panel-body" >
            <div class="row">                            
                <div class="form-group col-md-12">
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                    
                        <button type="button" class="btn btn-info w-sm m-b-5" onclick="abrirModalNuevoDetalle()" style="border-radius: 0px;">
                            <i class="fa fa-plus-square"></i>&ensp;Nuevo
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" id="detalleIndice" value="" />
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="table" id="divTablaProgramacion">
                                                
                    </div>
                        
                    <div class="table" id="divTablaDetalle">
<!--                        <table id="dataTableProgramacionAtencionDetalle" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align:center">N°</th>
                                    <th style="text-align:center">Producto</th>
                                    <th style="text-align:center">U.Medida</th>
                                    <th style="text-align:center">Cant. Prog.</th>
                                    <th style="text-align:center">Fecha Prog.</th>
                                    <th style="text-align:center">Estado</th>
                                    <th style="text-align:center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>-->
                    </div>
                </div>
            </div>
        </div>

        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar detalle &nbsp;&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar detalle &nbsp;&nbsp;&nbsp;
                <i class="fa fa-server" style="color:#1ca8dd;"></i> Programado &nbsp;&nbsp;&nbsp;
                <i class="fa fa-unlock" style="color:green;"></i> Liberado &nbsp;&nbsp;&nbsp;
                <i class="fa fa-lock" style="color:red;"></i> Comprometido &nbsp;&nbsp;&nbsp;
                <i class='fa fa-th-large' style='color:orange;'></i> Atendido parcialmente &nbsp;&nbsp;&nbsp;
                <i class='fa fa-th-large' style='color:green;' ></i> Atendido totalmente &nbsp;&nbsp;&nbsp;
            </p><br>
        </div>


        <div class="row">
            <div class="form-group col-md-12">
                <a href="#" class="btn btn-danger m-b-5" onclick="cargarPantallaListar()" style="border-radius: 0px;">
                    <i class="fa fa-close"></i>&ensp;Cancelar
                </a>&nbsp;&nbsp;&nbsp;                               

                <button type="button" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;" onclick="guardarProgramacionAtencion()">
                    <i class="fa fa-send-o"></i>&ensp;Enviar
                </button>
            </div>
        </div>
    </div>
</div>
<!--</div>-->

<!--inicio modal nuevo detalle-->     
<div id="modalProgramacionAtencionDetalle"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog">            
        <div class="modal-content">               
            <div class="modal-header">                      
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>    
                <h4 class="modal-title" id="tituloModalDetalle"><b>Detalle de atención</b></h4>         
            </div>                     
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Producto *</label>
                        <div class="form-group">
                            <select name="cboProducto" id="cboProducto" class="select2"></select>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>
                            Cantidad *
                        </label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="number" id="txtCantidad" name="txtCantidad" style="text-align: right" class="form-control" value="" maxlength="10">                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>
                            Fecha programada *
                        </label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaProgamada">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Estado *</label>
                        <div class="form-group">
                            <select name="cboEstado" id="cboEstado" class="select2" onchange="onChangeEstado()">
                                <option value="1">Programado</option>
                                <option value="3">Liberado</option>
                                <option value="4">Comprometido</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="contenedorOrganizador" hidden="true">
                    <div class="form-group col-md-12">
                        <label>Organizador</label>
                        <div class="form-group">
                            <select name="cboOrganizador" id="cboOrganizador" class="select2"></select>
                        </div>
                    </div>
                </div>
            </div>                   
            <div class="modal-footer">       
                <button type="button" class="btn btn-danger m-b-5" style="border-radius: 0px;margin-bottom: 0px;" data-dismiss="modal">
                    <i class="fa fa-close"></i>&ensp;Cerrar
                </button>        
                <button type="button" class="btn btn-info m-b-5" style="border-radius: 0px;margin-bottom: 0px;" onclick="agregarProgramacionAtencionDetalle()">
                    <i class="fa fa-plus-square-o"></i>&ensp;Agregar
                </button>
            </div>         
        </div>         
    </div>     
</div>

<!--modal para el detalle del documento-->
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
<script src="vistas/com/programacionAtencion/atencion_form.js"></script>