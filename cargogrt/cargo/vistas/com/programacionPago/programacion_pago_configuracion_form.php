
<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> configuración de programación pago</h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Descripcion *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" value="" maxlength="200">
                        </div>
                        <span id="msjDescripcion" class="control-label" style="color:red;font-style: normal;" hidden></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Proveedor *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select id="cboProveedor" name="cboProveedor" class="select2">
                            </select>
                        </div>
                        <span id="msjProveedor" class="control-label" style="color:red;font-style: normal;" hidden></span>
                    </div>
                </div>
                <div class="row">                            
                    <div class="form-group col-md-12">
                        <label>Grupos de producto *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select id="cboGrupoProducto" name="cboGrupoProducto" class="select2" multiple>
                            </select>
                        </div>
                        <span id="msjGrupoProducto" class="control-label" style="color:red;font-style: normal;" hidden></span>
                    </div>
                </div>
                <div class="row">                            
                    <div class="form-group col-md-12">
                        <label>Comentario</label>
                        <textarea type="text" id="txtComentario" name="txtComentario" class="form-control" value="" maxlength="500"></textarea>                        
                    </div>
                </div>

                <div class="row">                            
                    <div class="form-group col-md-12">
                        <label>Detalle</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                    
                            <button type="button" class="btn btn-info w-sm m-b-5" onclick="abrirModalNuevoDetalle()" style="border-radius: 0px;">
                                <i class="fa fa-plus-square"></i>&ensp;Nuevo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-body" >
                    <span id="msjDetalle" class="control-label" style="color:red;font-style: normal;" hidden></span>

                    <input type="hidden" id="detalleIndice" value="" />
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12" >
                            <div class="table">
                                <table id="dataTableProgramacionPagoDetalle" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">Indicador</th>
                                            <th style="text-align:center">Días</th>
                                            <th style="text-align:center">Porcentaje</th>
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

                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar detalle &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar detalle &nbsp;&nbsp;&nbsp;
                    </p><br>
                </div>


                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="#" class="btn btn-danger m-b-5" onclick="cargarPantallaListar()" style="border-radius: 0px;">
                            <i class="fa fa-close"></i>&ensp;Cancelar
                        </a>&nbsp;&nbsp;&nbsp;                               

                        <button type="button" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;" onclick="guardarProgramacionPago()">
                            <i class="fa fa-send-o"></i>&ensp;Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--inicio modal nuevo detalle-->     
<div id="modalProgramacionPagoDetalle"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog">            
        <div class="modal-content">               
            <div class="modal-header">                      
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>    
                <h4 class="modal-title" id="tituloModalDetalle"></h4>         
            </div>                     
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Indicador *</label>
                        <div class="form-group">
                            <select name="cboIndicador" id="cboIndicador" class="select2" onchange="onChangeIndicador()"></select>
                            <span id="msjIndicador" class="control-label" style="color:red;font-style: normal;" hidden></span>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Días *</label>
                        <div class="input-group col-md-12">                                                
                            <input type="number" name="txtDias" id="txtDias"  class="form-control"  style="text-align: right" onkeyup="if (this.value.length > 6) {
                                        this.value = this.value.substring(0, 6)
                                    }" value="">
                        </div>
                        <span id="msjDias" class="control-label" style="color:red;font-style: normal;" hidden></span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Porcentaje *</label>
                        <div class="input-group col-md-12">                                                
                            <input type="number" name="txtPorcentaje" id="txtPorcentaje"  class="form-control"  style="text-align: right" onkeyup="if (this.value.length > 6) {
                                        this.value = this.value.substring(0, 6)
                                    }" value="">
                        </div>
                        <span id="msjPorcentaje" class="control-label" style="color:red;font-style: normal;" hidden></span>
                    </div>
                </div>
            </div>                   
            <div class="modal-footer">       
                <button type="button" class="btn btn-danger m-b-5" style="border-radius: 0px;margin-bottom: 0px;" data-dismiss="modal">
                    <i class="fa fa-close"></i>&ensp;Cerrar
                </button>        
                <button type="button" class="btn btn-info m-b-5" style="border-radius: 0px;margin-bottom: 0px;" onclick="agregarProgramacionPagoDetalle()">
                    <i class="fa fa-plus-square-o"></i>&ensp;Agregar
                </button>
            </div>         
        </div>         
    </div>     
</div>

<script src="vistas/com/programacionPago/programacion_pago_configuracion_form.js"></script>