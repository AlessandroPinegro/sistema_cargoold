
<div class="page-title">
    <h3 id="titulo" class="title"><?php echo $_GET['winTitulo']; ?> programación de pago</h3>
</div>
<!--<div class="row">-->
<div class="panel panel-default">
    <input type="hidden" id="documentoId" value="<?php echo $_GET['docId']; ?>" />
    <input type="hidden" id="ppagoDetalleId" value="<?php echo $_GET['ppagoDetalleId']; ?>" />
    <div class="panel-heading">
        <!--<h3 class="panel-title" id="cabeceraPanel"></h3>-->
        <div class="row">
            <div class="col-md-5">
                <select name="cboProveedor" id="cboProveedor" class="select2" disabled></select>
            </div>
            <div class="col-md-5">
                <p id="pDocDescripcion" style="text-align: center;"></p>
            </div>
            <div class="col-md-2">
                <p id="pTotalDesc" style="text-align: right;"></p>
            </div>
        </div>  
    </div>
    <div class="panel-body">
        <form>
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="col-md-3" style="padding-left: 0px;">
                        <label>Fecha emisión</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                
                            <p id="pFechaEmision"></p>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="col-md-3">
                        <label>Fecha indicador</label>
                    </div>
                    <div class="col-md-9" style="padding-right: 0px;">
                        <a title="Modificar fecha tentativa" style="color: #0000ff"><p id="pFechaTentativa" onclick="habilitarContenedorFechaTentativa()"></p></a>

                        <div id="contenedorFechaTentativa"  class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaTentativa">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <span class="input-group-addon btn-success" title="Actualizar fecha tentativa" onclick="actualizarFechaProgramada()"><a><i class="ion-android-checkmark" style="color: white"></i></a></span>
                                <span class="input-group-addon btn-danger" title="Cancelar" onclick="habilitarFechaTentativa()"><a><i class="ion-android-close" style="color: white"></i></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                        <th style="text-align:center">Fecha programada</th>
                                        <th style="text-align:center">Importe</th>
                                        <th style="text-align:center">Porcentaje(%)</th>
                                        <th style="text-align:center">Estado</th>
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
                    <i class='fa fa-lock' style='color:red;'></i> Por liberar &nbsp;&nbsp;&nbsp;
                    <i class='fa fa-unlock' style='color:green;'></i> Liberado &nbsp;&nbsp;&nbsp;
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
<!--</div>-->

<!--inicio modal nuevo detalle-->     
<div id="modalProgramacionPagoDetalle"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog">            
        <div class="modal-content">               
            <div class="modal-header">                      
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>    
                <h4 class="modal-title" id="tituloModalDetalle"><b>Detalle de programación de pago</b></h4>         
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
                        <label>
                            <div class="form-group col-md-12 form-inline" style="padding-left: 0px;">  
                                <div class="radio-inline" style="padding-left: 0px;">                  
                                    <label class="cr-styled">                 
                                        <input type="radio" id="rdDias" name="rdTiempoProgramado" value="rdDias" onchange="habilitarContenedorTiempoProgramado()">    
                                        <i class="fa"></i>                                   
                                        Días                                         
                                    </label>                                  
                                </div>
                                <div class="radio-inline" style="padding-left: 0px;">       
                                    <label class="cr-styled">                                 
                                        <input type="radio" id="rdFechaProg" name="rdTiempoProgramado" value="rdFechaProg" checked onchange="habilitarContenedorTiempoProgramado()">        
                                        <i class="fa"></i>                                
                                        Fecha programada                             
                                    </label>                             
                                </div>
                            </div>
                        </label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contenedorDias"  style="display: none;">                                                
                            <input type="number" name="txtDias" id="txtDias"  class="form-control"  style="text-align: right" 
                                   onkeyup="if (this.value.length > 6) {
                                               this.value = this.value.substring(0, 6)
                                           }
                                           ;
                                           limpiarFechaProgramada()"
                                   onchange="limpiarFechaProgramada()"  value="">
                        </div>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contenedorFechaProgramada"  style="display: none;">
                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaProgamada">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <!--<label>-->
                            <div class="form-group col-md-12 form-inline" style="padding-left: 0px;">  
                                <div class="radio-inline" style="padding-left: 0px;">                  
                                    <label class="cr-styled">                 
                                        <input type="radio" id="rdPorcentaje" name="rdImporteProgramado" value="rdPorcentaje" onchange="habilitarContenedorImporteProgramado()">    
                                        <i class="fa"></i>                                   
                                        Porcentaje                                         
                                    </label>                                  
                                </div>
                                <div class="radio-inline" style="padding-left: 0px;">       
                                    <label class="cr-styled">                                 
                                        <input type="radio" id="rdImporte" name="rdImporteProgramado" value="rdImporte" checked onchange="habilitarContenedorImporteProgramado()">        
                                        <i class="fa"></i>                                
                                        Importe                          
                                    </label>                             
                                </div>
                            </div>
                        <!--</label>-->
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="input-group col-md-12 ">                                                
                            <input type="number" name="txtPorcentaje" id="txtPorcentaje"  class="form-control"  style="text-align: right" 
                                   onkeyup="if (this.value.length > 19) {
                                               this.value = this.value.substring(0, 19)
                                           }
                                           ;
                                           actualizarImporte()"
                                   onchange="actualizarImporte()"
                                   value="">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="input-group col-md-12">                                                
                            <input type="number" name="txtImporte" id="txtImporte"  class="form-control"  style="text-align: right"
                                   onkeyup="if (this.value.length > 19) {
                                               this.value = this.value.substring(0, 19)
                                           }
                                           ;
                                           actualizarPorcentaje()"
                                   onchange="actualizarPorcentaje()"
                                   value="">
                        </div>
                    </div>
                    <span id="msjImporte" class="control-label" style="color:red;font-style: normal;" hidden></span>
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
<script src="vistas/com/programacionPago/programacion_pago_form.js"></script>