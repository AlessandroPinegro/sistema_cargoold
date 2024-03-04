<style type="text/css">
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .columnAlignCenter{
        text-align: center;
    }
</style> 
<!DOCTYPE html>
<html lang="es">    
    <head>   
    </head>    
    <body> 
        <div class="row">
            <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />
            <input type="hidden" id="documentoId" value="<?php echo $_GET['documentoId']; ?>" />
            <input type="hidden" id="hddIsDependiente" value="1">       
            <div class="col-lg-12 form-group">  
                <div class="panel panel-info panel-color" style="margin-bottom: 0px;">
                    <div class="panel-body"> 
                        <div class="row">      
                            <div class="col-md-2"> 
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                                    <input type="text" id="txCodigo" name="txCodigo" class="form-control"  value="" maxlength="45" readonly="" style="text-align: right">
                                    <span class="input-group-addon"><i class="fa fa-qrcode"></i></span>          
                                </div>   

                            </div>
                            <div class="col-md-2"> 
                                <!--<input disabled="" type="text" id="cboModalidad" name="cboModalidad" class="form-control" required="" aria-required="true" style="text-align: center;">-->
                                <!--<input typ name="cboModalidad" id="cboModalidad" class="select2" disabled=""></select>--> 
                                <select name="cboModalidad" id="cboModalidad" class="select2" disabled=""></select> 
                            </div>

                            <div class="col-md-2"> 
                                <!--<input disabled="" type="text" id="cboComprobanteTipo" name="cboComprobanteTipo" class="form-control" required="" aria-required="true" style="text-align: center;">-->

                                <select name="cboComprobanteTipo" id="cboComprobanteTipo" class="select2" disabled=""></select> 
                            </div>

                            <div class="col-md-2"> 
                                <!--<input disabled="" type="text" id="cboMoneda" name="cboMoneda" class="form-control" required="" aria-required="true" style="text-align: center;">-->

                                <select name="cboMoneda" id="cboMoneda" class="select2" disabled=""></select> 
                            </div>

                            <div class="col-md-2"> 
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaEmision" disabled="">    
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>          
                                </div>    
                            </div>

                            <div class="col-md-2">  
                                <select name="cboPeriodo" id="cboPeriodo" class="select2"></select> 
                            </div> 
                        </div>   
                    </div>   
                </div>   
                <!--                <br/> -->
                <!--<div  style=" padding: 15px; text-align: right; border-top: 2px solid #ccc; margin-top: -10px;"></div>-->    

                <!--PARTE DINAMICA-->                
                <div id="contenedorDocumentoTipo">       
                    <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">   
                        <div class="row">
                            <!-- Basic example -->
                            <div class="col-md-6">
                                <div class="panel panel-info panel-color">
                                    <div class="panel-heading" style="padding-top: 10px;  padding-right: 20px; padding-bottom: 10px; padding-left: 20px;">
                                        <h3 id="titleAgenciaOrigen" class="panel-title">Origen</h3>
                                        <select name="cboAgenciaOrigen" id="cboAgenciaOrigen" class="select2" disabled=""></select> 

                                    </div>
                                    <div class="panel-body"> 
                                        <div class="form-group">
                                            <label for="cboCliente"><label id="lblClienteCbo">Cliente</label></label>
                                            <!--<input disabled="" type="text" id="cboCliente" name="cboCliente" class="form-control" required="" aria-required="true" style="text-align: left;">-->
                                            <select name="cboCliente" id="cboCliente" class="select2" disabled=""></select> 
                                        </div>
                                        <div class="form-group">
                                            <label for="cboClienteDireccion">Dirección recojo</label>
                                            <!--<input disabled="" type="text" id="cboClienteDireccion" name="cboClienteDireccion" class="form-control" required="" aria-required="true" style="text-align: left;">-->


                                            <select name="cboClienteDireccion" id="cboClienteDireccion" class="select2" disabled=""></select> 
                                        </div>
                                        <div id="divClienteDireccionFacturacion" class="form-group">
                                            <label for="cboClienteDireccionFacturacion">Dirección facuración</label>
                                            <!--<input disabled="" type="text" id="cboClienteDireccionFacturacion" name="cboClienteDireccionFacturacion" class="form-control" required="" aria-required="true" style="text-align: left;">-->
                                            <!--<textarea type="text" id="cboClienteDireccionFacturacion" name="cboClienteDireccionFacturacion" class="form-control" value="" maxlength="500" rows="2" placeholder="Autorizado" style="height: auto;width: 100%;display: block;padding: 6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;" readonly=""></textarea>-->

                                            <select name="cboClienteDireccionFacturacion" id="cboClienteDireccionFacturacion" class="select2" disabled=""></select> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Horizontal form -->
                            <div class="col-md-6">
                                <div class="panel panel-info panel-color">
                                    <div class="panel-heading" style="padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px;">
                                        <h3 id="titleAgenciaDestino" class="panel-title">Destino.</h3>
                                        <select name="cboAgenciaDestino" id="cboAgenciaDestino" class="select2" disabled=""></select> 
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="form-group">
                                            <label for="cboDestinatario"><label id="lblDestinatarioCbo">Destinatario</label></label>
                                            <!--<input disabled="" type="text" id="cboDestinatario" name="cboDestinatario" class="form-control" required="" aria-required="true" style="text-align: left;">-->

                                            <select name="cboDestinatario" id="cboDestinatario" class="select2" disabled=""></select> 
                                        </div>

                                        <div class="form-group">
                                            <label for="cboDestinatarioDireccion">Dirección despacho <i class="fa fa-map-marker"></i></label>
                                            <!--<input disabled="" type="text" id="cboDestinatarioDireccion" name="cboDestinatarioDireccion" class="form-control" required="" aria-required="true" style="text-align: left;">-->

                                            <select name="cboDestinatarioDireccion" id="cboDestinatarioDireccion" class="select2" disabled=""></select> 
                                        </div>
                                        <div id="divDestinatarioDireccionFacturacion" class="form-group">
                                            <label for="cboDestinatarioDireccionFacturacion">Dirección facuración</label>
                                            <!--<input disabled="" type="text" id="cboDestinatarioDireccionFacturacion" name="cboDestinatarioDireccionFacturacion" class="form-control" required="" aria-required="true" style="text-align: left;">-->
                                            <!--<textarea type="text" id="cboDestinatarioDireccionFacturacion" name="cboDestinatarioDireccionFacturacion" class="form-control" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;" readonly=""></textarea>-->

                                            <select name="cboDestinatarioDireccionFacturacion" id="cboDestinatarioDireccionFacturacion" class="select2" disabled=""></select> 
                                        </div>
                                        <div class="form-group">
                                            <label for="cboAutorizado"><label>Destinatario opcional.</label></label> 
                                            <select name="cboAutorizado" id="cboAutorizado" class="select2" multiple="" disabled=""></select> 
                                        </div>

                                        <div class="form-group">
                                            <label for="cboEntregado"><label>Entregado a</label>
                                                <span class="divider"></span> <a onclick="cargarPersona('Entregado', 2);"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar persona natural" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="cargarPersona('Entregado', 4);"><i class="fa fa-bank" tooltip-btndata-toggle="tooltip" title="Agregar persona juridica" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="editarPersona('Entregado');"><i class="fa fa-edit" tooltip-btndata-toggle="tooltip" title="Editar cliente" style="color: #1ca8dd;"></i></a>
                                                <span class="divider"></span> <a onclick="actualizarCboPersona('Entregado')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>

                                            </label> 
                                            <select name="cboEntregado" id="cboEntregado" class="select2"></select> 
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div>                       
                    </form>                   
                </div>                      
                <!--FIN PARTE DINAMICA-->   
                <div id="contenedorDetalle">     
                    <div class="panel panel-info panel-color">
                        <div class="panel-body"> 
                            <div class="row">    
                                <div class="col-md-6"></div>  
                                <div class="col-md-6">   
                                    <label>Guías</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                                    
                                        <div id="contenedorGuiaDivCombo">
                                            <div class="input-group">
                                                <select name="cboGuiaRelacion" id="cboGuiaRelacion" class="select2 "multiple="" disabled=""></select>
                                                <span class="input-group-btn" disabled="">
                                                    <button type="button" class="btn btn-effect-ripple btn-primary" ><i class="ion-plus" ></i></button>
                                                </span>
                                            </div>
                                        </div>
                                        <!--                                <div id="contenedorGuiaDivTexto" hidden="true">
                                                                            <div class="input-group">
                                                                                <input  type="text" id="txtGuiaRelacion" name="txtGuiaRelacion" placeholder="" data-mask="T999-9999999" class="form-control"/>                                                                                                                
                                                                                <span class="input-group-btn">
                                                                                    <button type="button" class="btn btn-effect-ripple btn-primary" 
                                                                                            onclick='mostrarDivComboGuia(true);'><i class="ion-checkmark"></i></button>
                                                                                    <button type="button" class="btn btn-effect-ripple btn-danger" 
                                                                                            onclick='mostrarDivComboGuia(false);'><i class="ion-close-round"></i></button>
                                                                                </span>
                                                                            </div>
                                                                        </div>-->
                                    </div>                           
                                </div>  
                            </div>  
                            <br/>
                            <!--Incluir tab-->
                            <div class="row" style="height: auto;">        
                                <div class="form-group  col-md-12">   
                                    <table id="datatable" class="table table-striped table-bordered">      
                                        <thead id="headDetalleCabecera">  
                                            <tr class="bg-info" style="color:white">
                                                <!--<th width="15%" class="text-center" >Acciones</th>-->
                                                <th width="15%" class="text-center" >Cantidad</th>
                                                <th width="15%" class="text-center" >Cantidad a entregar</th>
                                                <th width="40%" class="text-center" >Concepto..</th>
                                                <th width="15%" class="text-center" >Valor Unitario</th>
                                                <th width="15%" class="text-center" >Sub Total</th>

<!--                                        <th width="15%" class="text-center" >Acciones</th>
                                            <th width="15%" class="text-center" >Cantidad</th>
                                            <th width="40%" class="text-center" >Concepto</th>
                                            <th width="15%" class="text-center" >Valor Unitario</th>
                                            <th width="15%" class="text-center" >Sub Total</th>-->
                                            </tr>
                                        </thead>                                         
                                        <tbody id="dgDetalle"></tbody>                                      
                                    </table>                               
                                </div>  
                            </div>  
                            <div class="row text-center">                           
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <input type="number" id="txtOtroCargo" name="txtOtroCargo" class="form-control" value="" style="text-align: right;"  disabled=""/>    
                                </div>              
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'> 
                                    <input type="text" id="txtDescripcionOtroCargo" style="text-align: right" name="txtDescripcionOtroCargo" class="form-control" value="Otros cargos" maxlength="45" disabled=""> 
                                </div>
                                <!--                        <div class="form-group  col-md-1" style='float: right;'>
                                                            <label class="cr-styled" style="text-align: left;" >        
                                                                <input type="checkbox" id="chkOtroCargo"  checked="true" disabled="">   
                                                                <i class="fa"></i>   
                                                            </label>  
                                                        </div>-->
                            </div>
                            <div class="row text-center">                           
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <input type="number" id="txtDevolucionCargo" name="txtDevolucionCargo" class="form-control" value="" style="text-align: right;" disabled=""/>    
                                </div>              
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                                    <label class="cr-styled" style="text-align: left;" >        
        <!--                                <input type="checkbox" id="chkDevolucionCargo"  checked="true" disabled="">   
                                        <i class="fa"></i>   -->
                                        <median class="text-uppercase">Dev. de cargo</median>                 
                                        <median class="text-uppercase" id="simDevolucionCargo">$</median> 
                                    </label>  
                                </div>
                            </div>
                            <div class="row text-center">                           
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <input type="number" id="txtCostoReparto" name="txtCostoReparto" class="form-control" value="" style="text-align: right;" disabled=""/>    
                                </div>              
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                                    <median class="text-uppercase" style="text-right">Costo reparto</median>
                                </div>
                            </div>
                            <div class="row text-center">                           
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <input type="number" id="txtAjustePrecio" name="txtAjustePrecio" class="form-control" value="" style="text-align: right;" readonly=""/>    
                                </div>              
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                                    <median class="text-uppercase" style="text-right">Ajuste precio</median>
                                </div>
                            </div>

                            <div class="row text-center">     
                                <!--TOTALES-->                       
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <div id="contenedorTotalDiv">              
                                        <h4 id="contenedorTotal"><input type="number" id="txtTotal" name="txtTotal" class="form-control" value=""  style="text-align: center;" disabled="" /></h4>                             
                                        <median class="text-uppercase">Total</median>                   
                                        <median class="text-uppercase" id="simTotal">$</median> 
                                    </div>                                  
                                </div>                            
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                                    <div id="contenedorIgvDiv">                        
                                        <h4 id="contenedorIgv"><input type="number" id="txtIgv" name="txtIgv" class="form-control" value=""  style="text-align: center;" disabled=""/></h4>                       
                                        <median class="text-uppercase">                
                                        <label class="cr-styled" style="text-align: left;">        
                                            <input type="checkbox" id="chkIGV"  checked="true" disabled="">   
                                            <i class="fa"></i>   
                                            <median class="text-uppercase">IGV</median>                 
                                            <median class="text-uppercase" id="simIgv">$</median> 
                                        </label> 
                                        </median>                                    
                                    </div>                                     
                                </div>                                      
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>   
                                    <div id="contenedorSubTotalDiv">                                 
                                        <h4 id="contenedorSubTotal"><input type="number" id="txtSubTotal" name="txtSubTotal" class="form-control" value=""  style="text-align: center;" disabled=""/></h4>                                
                                        <median class="text-uppercase">Sub total</median>
                                        <median class="text-uppercase" id="simSubTotal">$</median>
                                    </div>                                      
                                </div> 
                                <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right; border-right:thin solid #d1d1d1' >   
                                    <div id="contenedorDetraccionDiv">                                 
                                        <h4 id="contenedorDetraccion"><input type="number" id="txtDetraccion" name="txtDetraccion" class="form-control" value=""  style="text-align: center;" disabled=""/></h4>                                
                                        <median class="text-uppercase">Detracción</median>                 
                                        <median class="text-uppercase" id="simDetraccion">$</median> 
                                    </div>                                      
                                </div>                                      
                            </div>                        
                        </div>    
                    </div>
                </div>                      
                <div class="row">                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>                       
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">      
                            <div style="float: right;padding-top: 25px;" id="divAccionesEnvio">
                                <a href="#" class="btn btn-danger" onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-success" onclick="refresarDocumento()" name="refrescar" id="refrescar" hidden=""><i class="ion-loop"></i> Actualizar</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-info" onclick="enviarPagoC()" name="env" id="env"><i class="fa fa-send-o"></i> Pagar</button>
                            </div>   
                        </div>                                            
                    </div>                          
                </div>   
            </div> 
            <!-- end col -->    
        </div> 
        <!-- End row --> 


        <!--inicio modal confirmacion-->     
        <div id="modalConfirmacionRegistro"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" style="display: none;">       
            <div class="modal-dialog modal-sm" >            
                <div class="modal-content">               
                    <div class="modal-header">                      
                        <h4 class="modal-title text-dark text-uppercase"><b>CLAVE DE SEGURIDAD CLIENTE</b></h4> 
                    </div>                     
                    <div class="modal-body">                 
                        <div class="row">                            
                            <div class="form-group col-md-12">
                                <label >SOLICITE AL CLIENTE INGRESAR SU CLAVE DE SEGURIDAD DE 4 DIGITOS</label>
                                <label>Clave *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="password" id="txtClave" name="txtClave" class="form-control" required="" aria-required="true" maxlength="4" style="font-size: 16px;text-align: right;" onkeypress="return isNumeric(event);">
                                </div>                                
                            </div>
                        </div>
                    </div>                   
                    <div class="modal-footer">   
                        <div class="form-group col-md-5">
                            <a type="button" class="btn btn-danger w-sm m-b-5" style="border-radius: 0px;margin-bottom: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;No, cancelar!</a>
                        </div>
                        <div class="form-group col-md-1"></div>
                        <div class="form-group col-md-5">

                            <a id="btnConfirmacionRegistro" type="button" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Si, guardar!</a>
                        </div>
                    </div>       
                </div>         
            </div>     
        </div>


        <!--inicio modal nuevo documento pago con documento-->    
        <div id="modalNuevoDocumentoPagoConDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="display: none;">      
            <div class="modal-dialog ">      
                <div class="modal-content">      
                    <div class="modal-header">       
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&ensp;
                            <i class="ion-close-round" tooltip-btndata-toggle='tooltip' title="Cerrar"></i>
                        </button>                     
                        <span class="divider"></span> 
                        <!--                        <button type="button" class="close" onclick="getAllProveedor()">
                                                    <i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i>
                                                </button>                  -->
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
                                                <span class="counter">
                                                    <input type="number" class="form-control" style="text-align: right; background-color: #F5F6CE" id="txtMontoAPagar" name="txtMontoAPagar" value="0.00" >
                                                </span>                                    
                                            </div>                                     
                                            <label>Paga con</label>                    
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                                <span class="counter"><input type="number" class="form-control" style="text-align: right;" id="txtPagaCon" name="txtPagaCon" value="0.00"></span>   
                                            </div>                                       
                                            <label>Vuelto</label>                      
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                                                <span class="counter">
                                                    <input type="number" readonly="true" class="form-control" style="text-align: right;" id="txtVuelto" name="txtVuelto" value="0.00">
                                                </span>                               
                                            </div>                     
                                            <label></label>             
                                            <div id="divCboActividadEfectivo" class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                <select name="cboActividadEfectivo" id="cboActividadEfectivo" class="select2"></select>    
                                            </div>            
                                            <label></label>             
                                            <div id="divCboCuentaEfectivo" class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                <select name="cboCuentaEfectivo" id="cboCuentaEfectivo" class="select2"></select>    
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
                                        <!--<a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/unidad_listar.php')" >
                                        <i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;-->  
                                        <!--<button type="button" onclick="save('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;">
                                        <i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;-->                        
                                    </div>                         
                                </div>              
                            </form>            
                        </div>             
                    </div>              
                    <div class="modal-footer">  
                        <div class="portlet-widgets">    
                            <div id="divCboCuentaEfectivo" class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left"> 
                                <div id="tablaDocumentoPagoAcumulado" class="col-md-4 text-right" style="padding-left: 0px;"></div>
                                <div class="col-md-8"></div> 
                            </div>
                            <button type="button" onclick="guardarDocumentoPagoTemporal()" value="guardar" name="btnGuardarPago" id="btnGuardarPago" class="btn btn-success w-sm m-b-5" style="border-radius: 0px;margin-top: 5px;">
                                <i class="fa fa-save"></i>&ensp;Agregar otro pago
                            </button>&nbsp;&nbsp; 
                            <button type="button" onclick="guardarDocumentoPago()" value="enviar" name="btnEnviar" id="btnEnviar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;">
                                <i class="fa fa-send-o"></i>&ensp;Enviar
                            </button>&nbsp;&nbsp;               
                            <span class="divider"></span>       
                            <button type="button" class="btn btn-danger m-b-5" name="btnCerrarModalPago" id="btnCerrarModalPago" style="border-radius: 0px;" data-dismiss="modal">
                                <i class="fa fa-close"></i>&ensp;Cerrar
                            </button>                        
                        </div>               
                    </div>               
                </div>            
            </div>       
        </div>
        <!-- /.modal --> 
        <script src="vistas/com/movimiento/movimiento_form_tablas_pago.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>    
    </body>
</html>