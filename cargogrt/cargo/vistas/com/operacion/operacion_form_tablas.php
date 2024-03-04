<!DOCTYPE html>
<html lang="es">
    <head>

    </head>
    <body>        
        <div class="row">
            <input type="hidden" id="hddIsDependiente" value="1">
            <div class="col-lg-12">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <div class="row">
                            <div class="col-md-10" style="margin-top: -12px; margin-left: -32px;">
                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" style="margin-top: -12px;">
                                    <h3 class="text-dark text-uppercase">
                                        <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2"></select>
                                    </h3>
                                </div>
                                
                                
                                <div class="col-lg-4 col-md-3 col-sm-6 col-xs-6">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                        <div id="contenedorSerieDiv" hidden="true">
                                            <h4 id="contenedorSerie"></h4>
                                        </div>
                                    </div> 
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">                                    
                                        <div id="contenedorNumeroDiv" hidden="true">
                                            <h4 id="contenedorNumero"></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">     
                                    <h4>
                                        <select id="cboMoneda" name="cboMoneda" class="select2">
                                            <option value="-1">&nbsp;</option>
                                        </select>
                                    </h4>
                                </div>
                                
                                <div class="col-lg-3 col-md-2 col-sm-6 col-xs-6">
                                    <h5 id="contenedorTransferencia" class="text-dark"></h5>
                                </div>
                            </div>

                            <div class="col-md-2" style="margin-left: 32px;">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="margin-top: -12px;">      
                                    <h4>                                   
                                        <select id="cboPeriodo" name="cboPeriodo" class="select2"  style="width: 100%">         
                                        </select>                                  
                                    </h4>                             
                                </div>
                                <div class="portlet-widgets col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    
                                    <span class="divider"></span>
                                    <a onclick="cargarBuscadorDocumentoACopiar()" id="cargarBuscadorDocumentoACopiar">
          <!-- <i class="fa fa-files-o" tooltip-btndata-toggle='tooltip' title="Bandeja de documentos a relacionar" style="color: #5CB85C;"></i> -->
                                    </a>
                                    
                                    <span class="divider"></span>
                                    <!-- <a onclick="abrirModalComentario()" id="abrirModalComentario">                                        
                                        <i class="fa fa-comment-o" title="Ingresar comentario"></i>
                                    </a>
                                     -->
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            

                        </div>
                    </div>
                    <div class="modal-footer"   style="margin-top: -10px;"></div>
                    <div id="portlet1" class="panel-collapse collapse in"  style="margin-top: -20px;">
                        <div class="portlet-body">                            
                            <!--DESCRIPCION-->
                            <div id="contenedorDescripcion" style="min-height: 75px;height: auto;">
                                <form  id="formularioDescripcion" class="form">
                                    <div class="row"></div>
                                    <div class="form-group col-md-12">
                                        <label>Descripción *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea type="text" id="txtDescripcion" name="txtDescripcion" value="" maxlength="500" rows="2" placeholder="" style="height: auto;width: 100%;display: block;padding: 6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;background-color: #fafafa; color: rgba(0,0,0,0.6); font-size: 14px; border: 1px solid #eee; box-shadow: none;"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>    
                            <div class="row"></div>   
                            <!--FIN DESCRIPCION-->                        
                            
                            <!--PARTE DINAMICA-->
                            <div id="contenedorDocumentoTipo" style="min-height: 75px;height: auto;">
                                <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/unidad_listar.php')" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>                  
                            <!--FIN PARTE DINAMICA-->         
                            
                            <div id="DivDocumentoACopiar" style="min-height: 0px;height: auto;" hidden="true">
                                <div id="contenedorLinkDocumentoACopiar" class="form-group">
                                    <div class="col-md-12" style="text-align: left;">
                                        <div id="checkDocumentoRelaciones">
                                            <label class="cr-styled" style="text-align: left;" >
                                                <input type="checkbox" id="chkDocumentoACopiar" checked>
                                                <i class="fa"></i> 
                                                Relacionar documento
                                                <br>
                                            </label>
                                        </div>
                                        <div id="linkDocumentoACopiar" style="min-height: 0px;height: auto;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div id="contenedorDetalle" style="min-height: 90px;height: auto;">
                                <div class="col-md-12">
                                                                          
                                    <div class="row text-center m-t-10 m-b-10">
                                        <!--TOTALES-->
                                        <div id="contenedorTotalDiv" hidden="true">
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>

                                                <h4 id="contenedorTotal"></h4>
                                                <median class="text-uppercase">Total</median>
                                            </div>
                                        </div>
                                        <div id="contenedorPercepcionDiv" hidden="true">
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>                                            
                                                <h4 id="contenedorPercepcion"></h4>
                                                <median class="text-uppercase">
                                                <label class="cr-styled" style="text-align: left;" >
                                                    <input type="checkbox" id="chkPercepcion" onclick="onChangeCheckPercepcion();">
                                                    <i class="fa"></i>
                                                    Percepción
                                                </label>
                                                </median>                                            
                                            </div>
                                        </div>
                                        <div id="contenedorIgvDiv" hidden="true">
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                                                <h4 id="contenedorIgv"></h4>
                                                <median class="text-uppercase">
                                                <label class="cr-styled" style="text-align: left;" >
                                                    <input type="checkbox" id="chkIGV" onclick="onChangeCheckIGV();" checked="true">
                                                    <i class="fa"></i>
                                                    IGV
                                                </label>
                                                </median>
                                            </div>
                                        </div>
                                        <div id="contenedorSubTotalDiv" hidden="true">
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                                                <h4 id="contenedorSubTotal"></h4>
                                                <median class="text-uppercase">Sub total</median>
                                            </div>
                                        </div>
<!--                                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>                                            
                                            <h4>
                                                <input type="number" id="tipoCambio" class="form-control" style="text-align: right;" value="0.00" disabled="true"/>
                                            </h4>
                                            <median class="text-uppercase">
                                                <label class="cr-styled" style="text-align: left;" >
                                                    TC &nbsp;
                                                    <input type="checkbox" id="checkBP"> 
                                                    <i class="fa"></i>
                                                    &nbsp; Personalizado
                                                </label>
                                            </median>                                               
                                        </div>-->
<!--                                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>                                            
                                            <h4>
                                                <select id="cboMoneda" name="cboMoneda" class="select2">
                                                    <option value="-1">&nbsp;</option>
                                                </select>
                                            </h4>
                                            <median class="text-uppercase">Moneda</median>                                            
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /Portlet -->

                <div class=" col-lg-12">
                    <div class="modal-footer">
                        <a href="#" class="btn btn-danger"  onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>
                        <a class="btn btn-success"  onclick="enviar()" name="env" id="env" style="display: none;" ><i class="fa fa-send-o"></i> Guardar</a>
                        <a class="btn btn-info" onclick="enviarYPagar()" name="envPag" id="envPag" style="display: none;"><i class="fa fa-money"></i> Guardar y pagar</a>
                                       
                        <a class="btn btn-success"  onclick="enviarRetiroDeposito()" name="envRetDep" id="envRetDep" style="display: none;"><i class="fa fa-send-o"></i> Guardar</a>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- End row -->
              
        <!-- MODAL COMENTARIO -->
            <div id="modalComentario" class="modal fade" tabindex="-1" role="dialog" 
                 aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 id="tituloModalComentario">Comentario</h4>
                        </div>
                        <div class="modal-body">
                            <div class="panel panel-body" style="padding: 5px">
                                <div class="col-md-12">
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea type="text" id="txtComentario" name="txtComentario" class="form-control" value="" maxlength="500"  style="height: 180px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger m-b-5"  style="border-radius: 0px;"  onclick="cerrarModalComentario()">
                                <i class="fa fa-close"></i>&ensp;Cerrar
                            </button>
                        </div> 
                    </div> 
                </div>
            </div>
        <!-- FIN MODAL COMENTARIO -->       
        <!--inicio modal nuevo documento pago con documento-->
        <div id="modalNuevoDocumentoPagoConDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog "> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&ensp;<i class="ion-close-round" tooltip-btndata-toggle='tooltip' title="Cerrar"></i></button> 
                        <span class="divider"></span>
                        <button type="button" class="close" onclick="getAllProveedor()"><i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i></button> 

                        <!--<h4 class="modal-title">Pago directo</h4>--> 
                        <div style="height: auto; margin-bottom: -20px;">
                            <div class="row">
                                <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <select name="cboDocumentoTipoNuevoPagoConDocumento" id="cboDocumentoTipoNuevoPagoConDocumento" class="select2"></select>
                                </div>

                                <div id="contenedorTipoCambioDiv" hidden="true">                                            
                                    <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-5">                                            
                                        <median class="text-uppercase">
                                        <input type="number" id="tipoCambio" class="form-control" style="text-align: right;" value="0.00" disabled="true"/>
                                        <label class="cr-styled" style="text-align: left;" >
                                            <input type="checkbox" id="checkBP"> 
                                            <i class="fa"></i>
                                            T.C. Personalizado
                                        </label>
                                        </median>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-body"> 
                        <!--efectivo-->
                        <div id="contenedorEfectivo" hidden="true">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="widget-panel widget-style-1 bg-success">
                                    <i class="fa ion-cash"></i> 
                                    <div class="row">
                                        <div class="form-group col-md-12 ">
                                            <label>Monto a pagar en efectivo</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <span class="counter"><input type="number" class="form-control" style="text-align: right; background-color: #F5F6CE" id="txtMontoAPagar" name="txtMontoAPagar" value="0.00"></span>
                                            </div>
                                            <label>Paga con</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <span class="counter"><input type="number" class="form-control" onkeyup="actualizarVuelto()" onchange="actualizarVuelto()" style="text-align: right;" id="txtPagaCon" name="txtPagaCon" value="0.00"></span>
                                            </div>
                                            <label>Vuelto</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <span class="counter"><input type="number" readonly="true" class="form-control" style="text-align: right;" id="txtVuelto" name="txtVuelto" value="0.00"></span>
                                            </div>

                                            <label></label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboActividadEfectivo" id="cboActividadEfectivo" class="select2"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div>Sales</div>-->
                                </div>
                            </div>
                        </div>

                        
                        <div id="contenedorDocumentoTipoNuevo" style="min-height: 75px;height: auto;">
                            <form  id="formNuevoDocumentoPagoConDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                                        <!--<a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/unidad_listar.php')" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;-->
                                                        <!--<button type="button" onclick="save('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;-->
                                    </div>
                                </div>
                            </form>
                        </div>  

                    </div> 
                    <div class="modal-footer"> 
                        <div class="portlet-widgets">
                            <button type="button" onclick="guardarDocumento()" value="enviar" name="btnEnviar" id="btnEnviar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                            <span class="divider"></span>
                            <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                        </div>
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal --> 
        <!--fin modal nuevo documento pago con documento -->
        <!--inicio modal retiro / deposito-->        
        <div id="modalRetiroDeposito"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 id="tituloModalRetiroDeposito"></h4>
                    </div> 
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                              
                                <label>Cuenta Destino*</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select id="cboCuentaDestino" name="cboCuentaDestino" class="select2">
                                        <option value="-1">&nbsp;</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <a class="btn btn-danger" id="id" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a>                         
                        <a class="btn btn-success"  onclick="enviarRetiroDeposito()"  ><i class="fa fa-send-o"></i> Enviar</a>
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal -->
        
        <!-- MODAL SALDO CUENTA -->
            <div id="modalCuenta" class="modal fade" tabindex="-1" role="dialog" 
                 aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 id="tituloModalCuenta">Cuenta</h4>
                        </div>
                        <div class="modal-body">
                            <!--<div class="panel panel-body" style="padding: 5px">-->
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Saldo</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="counter"><input type="number" readonly="true" class="form-control" style="text-align: right;" id="txtSaldo" name="txtSaldo" value="0.00"></span>
                                    </div>
                                </div>
                            <!--</div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger m-b-5"  style="border-radius: 0px;"  onclick="cerrarModalCuenta()">
                                <i class="fa fa-close"></i>&ensp;Cerrar
                            </button>
                        </div> 
                    </div> 
                </div>
            </div>
        <!-- MODAL SALDO CUENTA -->
        
        <!--MODAL DE COPIA DE DOCUMENTO-->
        <div id="modalBusquedaDocumentoACopiar"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="col-lg-12">
                                
                        <div id="divBuscador">
                                <div class="form-group input-group">
                                    <span class="input-group-btn">                                       
                                        <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                            <i class="caret"></i>
                                        </a>
                                        <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                            <li>
                                                <div id="divTipoDocumento">
                                                    <div class="form-group col-md-2">
                                                        <label style="color: #141719;">Tipo doc.</label>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboDocumentoTipoM" id="cboDocumentoTipoM" class="select2" multiple>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group col-md-2">
                                                    <label style="color: #141719;">Serie</label>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txtSerie" name="txtSerie" class="form-control" value="" maxlength="45">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" id="txtNumero" name="txtNumero" class="form-control" value="" maxlength="45">
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group col-md-2">
                                                    <label style="color: #141719;">Persona</label>
                                                </div>
                                                <div class="form-group col-md-10">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select name="cboPersonaM" id="cboPersonaM" class="select2" multiple>
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group col-md-2">
                                                    <label  style="color: #141719;">Fecha Emisión</label>
                                                </div>
                                                <div class="form-group col-md-10">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaEmisionInicio">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaEmisionFin">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group col-md-2">
                                                    <label  style="color: #141719;">Fecha Vencimiento</label>
                                                </div>
                                                <div class="form-group col-md-10">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaVencimientoInicio">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaVencimientoFin">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div style="float: right">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDocumentoACopiar(1)" class="btn btn-purple"><i class="fa fa-search"></i> Buscar</button>                                        
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                            </li>
                                        </ul>
                                    </span>
                                
                                    <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusquedaDocumentoCopiar()">                                
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegable2">
                                    </ul>
                                    </input>
                                    <span class="input-group-btn">
                                        <a type="button" class="btn btn-success" onclick="actualizarBusquedaCopiaDocumento()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                                    </span>
                                </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <table id="datatableModalDocumentoACopiar" class="table table-striped table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style='text-align:center;'>F. creación</th>
                                        <th style='text-align:center;'>F. emisión</th>
                                        <th style='text-align:center;'>Tipo documento</th>
                                        <th style='text-align:center;'>Persona</th>
                                        <th style='text-align:center;'>Serie</th>
                                        <th style='text-align:center;'>Número</th> 
                                        <th style='text-align:center;'>F. venc.</th>
                                        <th style='text-align:center;'>M</th>
                                        <th style='text-align:center;'>Total</th>
                                        <th style='text-align:center;'></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px;clear:left">
                        <div class="form-group">
                            <div class="col-md-6" style="text-align: left;">
                                <p><b>Leyenda:</b>&nbsp;&nbsp;
                                    <i class="ion-android-add" style="color:#2E9AFE;"></i> Agregar documento &nbsp;&nbsp;
                                    <i class="fa fa-arrow-down" style="color:#04B404;"></i> Agregar documento y cerra ventana    
                                </p>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!--FIN MODAL COPIA DE DOCUMENTO-->
        
    <!--modal para el detalle del operacion-->
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
                                    <label>COMENTARIO </label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea type="text" id="txtComentarioCopia" name="txtComentarioCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 col-md-12">
                                    <br>
                                    <label>DESCRIPCION </label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea type="text" id="txtDescripcionCopia" name="txtDescripcionCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
                                    </div>
                                </div>
                                <!--</div>-->
                            </div>
                        </div> 
                    </div>
                </div> 
                <div class="modal-footer" style="padding-top: 0px;">
                    <!--<label>Correo *</label>-->
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9"> 
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="input-group m-t-10" style="float: right">
                                <!--<a class="btn btn-success" onclick="enviarCorreoDetalleDocumento()"><i class="ion-email"></i> Enviar correo</a>-->
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>  
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div><!-- /.modal -->

        <div id="datosImpresion" hidden="true">
        </div>

        <script src="vistas/com/operacion/operacion_form_tablas.js"></script>
    </body>
</html>
