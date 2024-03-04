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
            <div class="col-lg-12"> 
                <div class="portlet">
                    <div class="portlet-heading">               
                        <div class="row">                    
                            <div class="col-md-12" style="margin-top: -12px; margin-left: -32px;">  
                                <div class="col-md-7">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: -10px;">
                                        <h3 class="text-dark text-uppercase">                           
                                            <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2"></select>
                                        </h3>                               
                                    </div>                                     

                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"> 
                                        <div id="contenedorSerieDiv" hidden="true">                
                                            <h4 id="contenedorSerie"></h4>                      
                                        </div>                           
                                    </div>                            
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">     
                                        <div id="contenedorNumeroDiv" hidden="true">      
                                            <h4 id="contenedorNumero"></h4>            
                                        </div>                    
                                    </div>    
                                </div>
                                <div class="col-md-5">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="contenedorMoneda">  
                                        <h4>                                   
                                            <select id="cboMoneda" name="cboMoneda" class="select2" style="font-weight: bold;font-style: italic;width: 100%" > 
                                                <option value="-1">&nbsp;</option>                           
                                            </select>                                 
                                        </h4>                        
                                    </div>      
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">      
                                        <h4>                                   
                                            <select id="cboPeriodo" name="cboPeriodo" class="select2"  style="width: 100%">         
                                            </select>                                  
                                        </h4>                             
                                    </div>
                                    <!--                                    <div id="divContenedorOrganizador" class="col-lg-4 col-md-4 col-sm-6 col-xs-6" hidden="true">      
                                                                            <h4>                                   
                                                                                <select id="cboOrganizador" name="cboOrganizador" class="select2">         
                                                                                </select>                                  
                                                                            </h4>                             
                                                                        </div>  
                                                                        <div id="divContenedorOrganizadorDestino" class="col-lg-5 col-md-5 col-sm-6 col-xs-6" hidden="true">     
                                                                            <h4 id="h4OrganizadorDestino">
                                                                            </h4>
                                                                        </div>-->
                                    <!--                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">       
                                                                            <div id="contenedorCambioPersonalizado" hidden="true">       
                                                                                <h4 id="cambioPersonalizado"></h4>         
                                                                            </div>                             
                                                                        </div>-->


                                </div>                    
                            </div>
                            <!--                            <div class="col-md-2" style="margin-left: 32px;" > 
                                                            <div class="col-lg-10 col-md-10 col-sm-6 col-xs-6" style="margin-top: -12px;">      
                                                                <h4>                                   
                                                                    <select id="cboPeriodo" name="cboPeriodo" class="select2"  style="width: 100%">         
                                                                    </select>                                  
                                                                </h4>                             
                                                            </div>
                                                            <div class="portlet-widgets col-lg-1 col-md-1 col-sm-2 col-xs-2" style="padding-left: 0px;">                     
                                                                <span class="divider"></span>                    
                                                                <a onclick="cargarBuscadorDocumentoACopiar()" id="cargarBuscadorDocumentoACopiar">   
                                                                    <i class="fa fa-files-o" tooltip-btndata-toggle='tooltip' title="Bandeja de documentos a relacionar" style="color: #5CB85C;"></i> 
                                                                </a>                              
                                                            </div>
                                                        </div>
                                                        <label class='' id="nombreArchivo" style="color: black" hidden="true"></label>    -->
                        </div>                 
                    </div>                
                    <div class="modal-footer"   style="margin-top: -10px;"></div>    
                    <div id="portlet1" class="panel-collapse collapse in"  style="margin-top: -20px;">   
                        <div class="portlet-body">               
                            <!--PARTE DINAMICA-->                
                            <div id="contenedorDocumentoTipo">       
                                <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8"></form>                   
                            </div>                      
                            <!--FIN PARTE DINAMICA-->   

                            <div id="divDocumentoRelacion" style="min-height: 0px;height: auto;">  
                                <div id="contenedorLinkDocumentoACopiar" class="form-group">         
                                    <div class="col-md-10" style="text-align: left;">                
                                        <div id="divChkDocumentoRelacion">                               
                                            <label style="text-align: left;" >
                                                <b>
                                                    <br>  
                                                    Relacionar documento
                                                    <br>
                                                </b>
                                            </label>                                
                                        </div>                                     
                                        <div id="linkDocumentoACopiar" style="min-height: 0px;height: auto;"></div>                              
                                    </div>   
                                    <!--<div class="col-md-8" style="text-align: left;">-->
                                    <div class="portlet-widgets col-lg-2 col-md-2 col-sm-12 col-xs-12" style=" margin-top: 15px;">
                                        <a href="#" class="btn btn-success" onclick="cargarBuscadorDocumentoACopiar()"><i class="fa fa-search"></i> Buscar documento</a>
                                    </div>
                                    <!--</div>-->
                                </div>                           
                            </div>


                            <div id="contenedorDetalle" style="min-height: 170px;height: auto;">    
                                <div class="col-md-12">                                
                                    <div class="row">                                  
                                        <div class="form-group">                                      
                                            <div class="col-md-6" style="text-align: left;">                  
                                                <div id="contenedorChkIncluyeIGV" hidden="true">              
                                                    <label class="cr-styled" style="text-align: left;" >                         
                                                        <input type="checkbox" id="chkIncluyeIGV"  checked="">    
                                                        <i class="fa"></i>                                                 
                                                        Los precios incluyen IGV                                  
                                                    </label>                                           
                                                </div>                               
                                            </div>                               
                                        </div>                              
                                    </div>  
                                    <br>
                                    <!--Incluir tab-->
                                    <!--<div id="tabDistribucion">-->
                                    <div class="row" style="height: auto;">   
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">     
                                            <table id="datatable" class="table table-striped table-bordered">      
                                                <thead id="headDetalleCabecera">  
                                                    <tr class="bg-info" style="color:white">
                                                        <!--<th width="15%" class="text-center" >Acciones</th>-->
                                                        <th width="15%" class="text-center" >Cantidad</th>
                                                        <!--<th width="15%" class="text-center" >Cantidad a entregar</th>-->
                                                        <th width="40%" class="text-center" >Concepto</th>
                                                        <th width="15%" class="text-center" >Nota de venta</th>
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
                                    <br><br>
                                    <div class="row" style="height: auto;">   
                                        <div class="col-md-8">
                                            <div class="row" style="height: auto;">   
                                                <div class="col-md-8"></div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Penalidad Ittsa</label>
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div id="contenedorDireccionTipoDivCombo">
                                                                <div class="input-group">
                                                                    <select name="cboPenalidadMotivo" id="cboPenalidadMotivo" class="select2 "></select>
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-effect-ripple btn-primary" onclick="habilitarDivPenalidad(true)"><i class="ion-plus"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div id="contenedorDireccionTipoDivTexto" hidden="true">
                                                                <div class="input-group">
                                                                    <input type="text" id="txtPenalidadMotivo" name="txtPenalidadMotivo" class="form-control" value="" maxlength="100" />
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-effect-ripple btn-primary" onclick="habilitarDivPenalidad(false)"><i class="ion-close-round"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row" style="height: auto;">   
                                                <div class="col-md-8"></div>
                                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
                                                    <div class="input-group">
                                                        <input  type="number" id="txtPenalidadMonto" name="txtPenalidadMonto" placeholder="" class="form-control" style="text-align: right;"/>                                                                                                                
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-effect-ripple btn-primary" 
                                                                    onclick='agregarPenalidad();'><i class="ion-checkmark"></i></button>
                                                            <button type="button" class="btn btn-effect-ripple btn-danger" 
                                                                    onclick='limpiarPenalidad();'><i class="ion-close-round"></i></button>
                                                        </span>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">     
                                            <table id="datatablePenalidad" class="table table-striped table-bordered">      
                                                <thead id="headDetalleCabeceraPenalidad">  
                                                    <tr class="bg-info" style="color:white">
                                                        <th width="50%" class="text-center" >Motivo</th>
                                                        <th width="50%" class="text-center" >Valor</th> 
                                                    </tr>
                                                </thead>                                         
                                                <tbody id="dgDetallePenalidad"></tbody>                                      
                                            </table>                                
                                        </div>   
                                    </div> 

                                    <!--</div>-->


                                    <div class="row text-center m-t-10 m-b-10">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">                                             
                                            <div class="widget-panel widget-style-1 bg-info" style="padding: 1px 60px 1px 1px;color: black;">           
                                                <i class="fa fa-comments-o"></i>                                  
                                                <div>
                                                    <textarea type="text" id="txtComentario" name="txtComentario" value="" maxlength="500" rows="4" placeholder="Comentario" style="height: auto;width: 100%;display: block;padding: 6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>                                            
                                                </div> 
                                            </div>              
                                        </div>  
                                        <div id="cboTipoPagoDiv" class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display: none">
                                            <h4>                           
                                                <select id="cboTipoPago" name="cboTipoPago" class="select2" onchange="onChangeTipoPago()">     
                                                    <option value="1" selected>Contado</option>                             
                                                    <option value="2">Cr&eacute;dito</option>                          
                                                </select>                                        
                                            </h4>                                    
                                            <a id="aMostrarModalProgramacion" onclick="mostrarModalProgramacionPago()" title="Ver programación de pago" hidden>
                                                <small id="tipoPagoDescripcion" class="text-muted" style="text-decoration: underline"></small>
                                            </a>                                     
                                            <small id="idFormaPagoContado" class="text-muted" style="color: #1ca8dd;text-decoration: underline ">
                                                Forma de pago: Contado
                                            </small>                                                                                 
                                        </div>   
                                        <div class="col-md-4" style='float: right;'>
                                            <table width="100%"> 
                                                <tr id="divContenedorDsco" hidden="true">
                                                    <td>
                                                        <median id="divContenedorDscoText" class="text-uppercase" style="font-weight: bold;">Penalidad&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 style="margin-top: 0px;margin-bottom: 0px;">
                                                            <input type="text" id="contenedorDsco" name="contenedorDsco" readonly="true" class="form-control" value="0.00" style="text-align: right;">                                                   
                                                        </h4>  
                                                    </td>
                                                </tr>
                                                <tr id="contenedorExoneradoDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorExoneradoDivText" class="text-uppercase" style="font-weight: bold;">Exonerado&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorExonerado" style="margin-top: 0px;margin-bottom: 0px;"></h4>      
                                                    </td>
                                                </tr>
                                                <tr id="contenedorInafectaDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorInafectaDivText" class="text-uppercase" style="font-weight: bold;">Inafecta&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorInafecta" style="margin-top: 0px;margin-bottom: 0px;"></h4>      
                                                    </td>
                                                </tr>
                                                <tr id="contenedorGratuitaDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorGratuitaDivText" class="text-uppercase" style="font-weight: bold;">Gratuita&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorGratuita" style="margin-top: 0px;margin-bottom: 0px;"></h4>      
                                                    </td>
                                                </tr>
                                                <tr id="contenedorGravadaDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorGravadaDivText" class="text-uppercase" style="font-weight: bold;">Gravada&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorGravada" style="margin-top: 0px;margin-bottom: 0px;"></h4>      
                                                    </td>
                                                </tr>

                                                <tr id="contenedorSubTotalDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorSubTotalDivText" class="text-uppercase" style="font-weight: bold;">Sub Total&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorSubTotal" style="margin-top: 0px;margin-bottom: 0px;"></h4>      
                                                    </td>
                                                </tr>
                                                <tr id="contenedorIgvDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorIgvDivText" class="text-uppercase" style="font-weight: bold;">IGV&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorIgv" style="margin-top: 0px;margin-bottom: 0px;"></h4>  
                                                    </td>
                                                </tr>
                                                <tr id="contenedorRetencionDiv" hidden="true">
                                                    <td>
                                                        <median id="retencionDescripcion" class="text-uppercase" style="font-weight: bold; text-align: right">IR-RETENCION&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorRetencion" style="margin-top: 0px;margin-bottom: 0px;"></h4>  
                                                    </td>
                                                </tr> 
                                                <tr id="contenedorOtrosGastosDiv" hidden="true">
                                                    <td>
                                                        <median id="otrosGastosDescripcion" class="text-uppercase" style="font-weight: bold; text-align: right">OTROS GASTOS&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorOtrosGastos" style="margin-top: 0px;margin-bottom: 0px;"></h4>  
                                                    </td>
                                                </tr> 

                                                <tr id="contenedorTotalDiv" hidden="true">
                                                    <td>
                                                        <median id="contenedorTotalDivText" class="text-uppercase" style="font-weight: bold;">Total&nbsp;&nbsp;</median>
                                                    </td>
                                                    <td>
                                                        <h4 id="contenedorTotal" style="margin-top: 0px;margin-bottom: 0px;"> </h4>
                                                    </td>
                                                </tr>
                                            </table>                                            
                                        </div>



                                        <div id="contenedorICBPERDiv" hidden="true" class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>              
                                            <h4>
                                                <input type="text" id="contenedorICBPER" name="contenedorICBPER" readonly="true" class="form-control" value="" style="text-align: center;">                                                   
                                            </h4> 
                                            <median class="text-uppercase">ICBPER</median>                
                                        </div>


                                        <!--LINEA CREDITO-->
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 has-success claseLineaCredito" hidden="">
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4 claseLineaCredito" style="float: right;">                                               
                                                <h4>
                                                    <input disabled type="number" id="txtSaldo" name="txtSaldo" class="form-control" value="" style="text-align: center;">
                                                </h4>                                
                                                <median class="text-uppercase" style="color: #3c763d">SALDO S/</median>
                                            </div>  
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4 claseLineaCredito" style="float: right;">                                               
                                                <h4>
                                                    <input disabled type="number" id="txtLineaExtra" name="txtLineaExtra" class="form-control" value="" style="text-align: center;">
                                                </h4>                                
                                                <median class="text-uppercase" style="color: #3c763d">LINEA EXTRA S/</median>
                                            </div>      
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4 claseLineaCredito" style="float: right;">                                               
                                                <h4>
                                                    <input disabled type="number" id="txtMontoAprobado" name="txtMontoAprobado" class="form-control" value="" style="text-align: center;">
                                                </h4>                                
                                                <median class="text-uppercase" style="color: #3c763d">APROBADO S/</median>
                                            </div>      
                                        </div>
                                        <!--FIN LINEA CREDITO-->


                                        <!--UTILIDADES-->                                  
                                        <div id="contenedorUtilidadesTotales" hidden="true" class="claseMostrarUtilidad">       
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>       
                                                <h4>                                                                
                                                    <input type="text" id="txtTotalUtilidadPorcentaje" name="txtTotalUtilidadPorcentaje" readonly="true" class="form-control" value="" style="text-align: center;">    
                                                </h4>                                            
                                                <median class="text-uppercase">Total Utilidad %</median>          
                                            </div>                                           
                                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>        
                                                <h4>                                                 
                                                    <input type="text" id="txtTotalUtilidadSoles" name="txtTotalUtilidadSoles" readonly="true" class="form-control" value="" style="text-align: center;">    
                                                </h4>                                   
                                                <median class="text-uppercase" id="totalUtilidadDescripcion">Total Utilidad</median>    
                                            </div>                                                                                                                   
                                        </div>                                                                                           
                                    </div>                            
                                </div>                      
                            </div>                        
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">                                   
                                        <div style="float: right;padding-top: 25px;" id="divAccionesEnvio"></div>   
                                    </div>   
                                </div>                                                                                         
                            </div>                     
                        </div>                 
                    </div>            
                </div> 
                <!-- /Portlet -->      
            </div> 
            <!-- end col -->    
        </div> 
        <!-- End row -->
        <!--inicio modad-->     
        <div id="modalStockBien"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"
             data-backdrop="static" data-keyboard="false">       
            <div class="modal-dialog">            
                <div class="modal-content">               
                    <div class="modal-header">                      
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>    
                        <h4 class="modal-title">Verificación de stock</h4>         
                    </div>                     
                    <div class="modal-body">                 
                        <div class="table">                      
                            <table id="datatableStock" class="table table-striped table-bordered">     
                                <thead>                              
                                    <tr>                                      
                                        <th style='text-align:center;'>Organizador</th>                      
                                        <th style='text-align:center;'>Unidad de medida</th>         
                                        <th style='text-align:center;'>Stock</th>                       
                                    </tr>                             
                                </thead>                         
                            </table>                   
                        </div>                    
                        <div id="div_resumenStock">   
                        </div>                 
                    </div>                   
                    <div class="modal-footer">       
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>        
                    </div>         
                </div>         
            </div>     
        </div>

        <div id="modalAsignarAtencion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-full">     
                <div class="modal-content">       
                    <div class="modal-header">           
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->    
                        <h4 class="modal-title" id="full-width-modalLabel">Asignación de atención</h4>    
                    </div>          
                    <div class="modal-body-scrollbar">     
                        <div class="scoll-tree">               
                            <div class="table">                  
                                <div id="dataList">                     
                                    <table id="datatableAsignarAtencion" class="table table-striped table-bordered">      
                                    <!-- <thead id="theadProductosDetalles"> -->                  
                                        <thead>                               
                                            <tr id="theadProductosDetalles">           
                                            </tr>                             
                                        </thead>                          
                                        <tbody id="tbodyProductosDetalles">    
                                        </tbody>                              
                                    </table>                         
                                </div>                 
                            </div>              
                        </div>               
                    </div>              
                    <div class="modal-footer">        
                        <button type="button" class="btn btn-info w-md m-b-5" id="id" onclick="asignar()" style="border-radius: 0px; margin-top: 8px; " >
                            <i class="fa fa-send-o"></i>&ensp;Enviar
                        </button>                     
                        <button type="button" class="btn btn-danger m-b-5" id="id" onclick="cancelarModalAsignacion()" style="border-radius: 0px;" data-dismiss="modal">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>        
                    </div>      
                </div>       
            </div>    
        </div>      
        <!--Inicio modal para el precio del bien-->    
        <div id="modalPrecioBien"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
            <div class="modal-dialog modal-lg">       
                <div class="modal-content">            
                    <div class="modal-header">            
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>       
                        <h4 class="modal-title">Precio del bien</h4>             
                    </div>                   
                    <div class="modal-body">         
                        <div class="table">              
                            <div id="dataList">              
                                <table id="datatablePrecio" class="table table-striped table-bordered">        
                                    <thead>                         
                                        <tr>                        
                                            <th style='text-align:center;'>Tipo</th>   
                                            <th style='text-align:center;'>P. Sugerido</th>            
                                            <th style='text-align:center;'>Ut. Sugerido (%)</th>    
                                            <th style='text-align:center;'>Descuento (%)</th>              
                                            <th style='text-align:center;'>Precio mínimo</th>                   
                                            <th style='text-align:center;'>Acción</th>                 
                                        </tr>                                    
                                    </thead>                          
                                </table>                 
                            </div>                    
                        </div>               
                    </div>             
                    <div class="modal-footer">   
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>                   
                    </div>             
                </div>         
            </div>      
        </div>       
        <!--Fin modal para el precio del bien-->   
        <div id="modalDocumentoRelacion"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
            <div class="modal-dialog modal-full">      
                <div class="modal-content">      
                    <div class="modal-header">            
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>       
                        <h4 class="modal-title"><b>Relacionar las Nota de venta</b></h4>             
                    </div> 
                    <div class="modal-body">          
                        <div class="row">                       
                            <div class="col-lg-12">                   
                                <div  class="portlet" >
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="portlet-heading bg-info m-b-0" 
                                                 onclick="colapsarBuscador()"  

                                                 style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">                                        
                                                <h3 class="portlet-title"><i class="fa fa-filter"></i> Filtrar por </h3>                   
                                                <div class="portlet-widgets">
                                                    <a id="loaderBuscar" onclick="loaderBuscar()">
                                                        <!--<i class="ion-refresh"></i>-->
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
                                                <div class="form-group col-md-4">
                                                    <label>Serie</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" class="form-control" placeholder="Ingrese la serie" id="txtSerieModal" name="txtNumeroLiquidacion"> 
                                                    </div>
                                                </div> 
                                                <div class="form-group col-md-4">
                                                    <label>Número</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" class="form-control" placeholder="Ingrese el número" id="txtNumeroModal" name="txtNumeroLiquidacion"> 
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Fecha emisión</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaInicioModal">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>   
                                                </div> 
                                                <div class="form-group col-md-2">
                                                    <label></label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaFinModal">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>   
                                                </div>  

                                            </div> 
                                            <div class="row">

                                                <div  class="form-group col-md-4">
                                                    <label>Agencia origen</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select  name="cboAgenciaOrigenModal" id="cboAgenciaOrigenModal" class="select2" placeholder="Seleccione agencia" multiple=""></select>
                                                    </div>
                                                </div> 
                                                <div  class="form-group col-md-4">
                                                    <label>Agencia destino</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select  name="cboAgenciaDestinoModal" id="cboAgenciaDestinoModal" class="select2" placeholder="Seleccione agencia" multiple=""></select>
                                                    </div>
                                                </div>  
                                            </div>

                                            <div class="row">                                      
                                                <div class="modal-footer"> 
                                                    <button type="button"  class="btn btn-info" onclick="buscarDocumentoRelacionPorCriterios();" ><i class="fa fa-search"></i> Buscar</button>&nbsp; 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                  
                            </div>                       
                        </div>                      
                        <div class="row">                    
                            <table id="dtDocumentoRelacion" class="table table-striped table-bordered" style="width: 100%">                     
                                <thead>                        
                                    <tr>                            
                                        <th style='text-align:center;'>Seleccionar</th>                
                                        <th style='text-align:center;'>F. Creación</th>             
                                        <th style='text-align:center;'>Agencia Origen</th>           
                                        <th style='text-align:center;'>Agencia Destino</th>                      
                                        <th style='text-align:center;'>Tipo Documento</th>                  
                                        <th style='text-align:center;'>Persona</th>                   
                                        <th style='text-align:center;'>S/N</th>                             
                                        <th style='text-align:center;'>Total</th>                                    
                                    </tr>                    
                                </thead>                    
                            </table>              
                        </div>                 
                    </div>                
                    <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px;clear:left">            
                        <div class="form-group">                    
                            <div class="col-md-6" style="text-align: left;">        
<!--                                <p><b>Leyenda:</b>&nbsp;&nbsp;                          
                                    <i class="fa fa-download" style="color:#04B404;"></i> Agregar documento a copiar&nbsp;&nbsp;       
                                    <i class="fa fa-arrow-down" style="color:#1ca8dd;"></i> Agregar documento a relacionar             
                                </p>                    -->
                            </div>                  
                            <div class="col-md-6">          
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>      

                                <button type="button" class="btn btn-info" onclick="agregarDocumentoRelacionTotal();"><i class="fa fa-download"></i> Relacionar</button>
                            </div>             
                        </div>             
                    </div>             
                </div>           
            </div>      
        </div>        
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
                                <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                </form>
                            </div>
                        </div>   

                        <div class="col-lg-12 ">
                            <div class="portlet" style="box-shadow: 0 0px 0px">
                                <div id="portlet2" class="row">
                                    <div class="portlet-body">
                                        <div id="tabDistribucion">
                                            <ul id="tabsDistribucionMostrar"  class="nav nav-tabs nav-justified">
                                                <li class="active">
                                                    <a href="#tabDistribucionDetalle" data-toggle="tab" aria-expanded="true" title="Detalle"> 
                                                        <span class="hidden-xs"> <b>Resumen</b></span> 
                                                    </a> 
                                                </li> 
                                                <li id="liDistribucion"> 
                                                    <a href="#tabDistribucionEntrega" data-toggle="tab" aria-expanded="false" title="Detalle entrega"> 
                                                        <span class="hidden-xs"><b>Detalle Entrega</b></span> 
                                                    </a> 
                                                </li>
                                            </ul>
                                            <div id="div_contenido_tab" class="tab-content">
                                                <div class="tab-pane active" id="tabDistribucionDetalle">
                                                    <table id="datatable2" class="table table-striped table-bordered">
                                                        <thead id="theadDetalle">
                                                        </thead>
                                                        <tbody id="tbodyDetalle">
                                                        </tbody>
                                                    </table> 
                                                </div>                                            
                                                <div class="tab-pane" id="tabDistribucionEntrega" hidden="">
                                                    <table id="datatableEntrega" class="table table-striped table-bordered">
                                                        <thead id="theadDetalleEntrega">
                                                        </thead>
                                                        <tbody id="tbodyDetalleEntrega">
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div> 


                        <!--                <br/><br/>
                                        <div class="row">
                                            <div class="col-lg-12 ">
                                                <span class="hidden-xs">Detalle del documento</span> 
                                                <table id="datatable2" class="table table-striped table-bordered">
                                                    <thead id="theadDetalle">
                                                    </thead>
                                                    <tbody id="tbodyDetalle">
                                                    </tbody>
                                                </table> 
                                            </div>  
                                        </div>
                                        
                                        <br/><br/>
                                        <div id="divDetalleEntrega" class="row" hidden="">
                                            <div class="col-lg-12 ">
                                                <span class="hidden-xs">Detalle del documento</span> 
                                                <table id="datatableEntrega" class="table table-striped table-bordered">
                                                    <thead id="theadDetalleEntrega">
                                                    </thead>
                                                    <tbody id="tbodyDetalleEntrega">
                                                    </tbody>
                                                </table> 
                                            </div>  
                                        </div>-->
                    </div> 

                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                            <!--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>-->
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-6">                       
                                <div class="input-group m-t-10" style="float: right">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!-- /.modal -->    

        <!-- modal visualizar archivos-->
        <div id="modalVisualizarArcvhivos"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizarModalArchivos"></h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="row">
                            <div id="divContenedorAdjuntoMulti" class="form-group col-md-4">
                                <!--<h4>-->
                                <div class="fileUpload btn btn-purple" style="border-radius: 0px;"
                                     id="idPopoverMulti" 
                                     title=""  
                                     data-toggle="popover" 
                                     data-placement="top" 
                                     data-content="">
                                    <i class="ion-upload" style="font-size: 16px;"></i>
                                    Cargar documento
                                    <input name="archivoAdjuntoMulti" id="archivoAdjuntoMulti"  type="file" accept="*" class="upload" >
                                    <input type="hidden" id="dataArchivoMulti" value="" />
                                </div>
                                <!--</h4>-->                         
                            </div>

                            <div class="form-group col-md-4">
                                <button id="btnAgregarDoc" name="btnAgregarDoc" type="button" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;">
                                    <i class="fa fa-plus-circle"></i>&nbsp;&nbsp;Agregar a la Lista
                                </button>
                            </div>

                        </div>
                        <span id="msjDocumento" style="color:#cb2a2a;font-style: normal;"></span>
                        <br>
                        <div class="row" id="scroll">
                            <div class="form-group col-md-12" >
                                <div class="table">
                                    <div id="dataList2">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="divLeyenda">
                            <b>Leyenda:</b>&nbsp;&nbsp;
                            <i class="fa fa-cloud-download" style="color:#1ca8dd;"></i>&nbsp;Descargar &nbsp;&nbsp;&nbsp;
                            <i class="fa fa-trash-o" style="color:#cb2a2a;"></i>&nbsp;Eliminar &nbsp;&nbsp;&nbsp;
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div>
        <!-- fin modal visualizar archivos-->

        <div id="modalAsignarOrganizador"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;overflow-y: scroll;" data-backdrop="static" data-keyboard="false">  
            <div class="modal-dialog">            
                <div class="modal-content">              
                    <div class="modal-header">                
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>             
                        <h4 class="modal-title">Asignar stock</h4>           
                    </div>               
                    <div class="modal-body" id="contenedorAsignarStockXOrganizador">      
                    </div>                 
                    <div class="modal-footer">           
                        <button type="button" class="btn btn-info m-b-4" onclick="asignarStockXOrganizador();">
                            <i class="fa fa-send-o"></i>&ensp;Aceptar
                        </button>                  
                        <button type="button" class="btn btn-danger m-b-5" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button>   
                    </div>         
                </div>             
            </div>      
        </div>
        <!-- /.modal -->             
        <!--inicio modal bienes faltantes-->          
        <div id="modalDocumentoGenerado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
            <div class="modal-dialog modal-lg">        
                <div class="modal-content">             
                    <div class="modal-header">              
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>        
                        <h3>Generar documento</h3>         
                    </div>                  
                    <div class="modal-body">    
                        <div class="table">               
                            <div class="row" style="height: auto;">    
                                <table id="dtDocumentoGenerado" class="table table-striped table-bordered">       
                                    <thead>                             
                                        <tr>                                
                                            <th style='text-align:center;'>Producto</th>      
                                            <th style='text-align:center;'>Cantidad</th>             
                                            <th style='text-align:center;'>Tipo Documento</th>                 
                                            <th style='text-align:center;'>Org. / Proveedor</th>                       
                                        </tr>                              
                                    </thead>                         
                                    <tbody id="dtBodyDocumentoGenerado">     
                                    </tbody>                           
                                </table>                      
                            </div>                     
                        </div>                      
                    </div>                
                    <div class="modal-footer">  
                        <a class="btn btn-danger" id="id" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a>          
                        <a class="btn btn-success"  onclick="guardarDocumentoGenerado()"  ><i class="fa fa-send-o"></i> Enviar</a>      
                    </div>        
                </div>        
            </div>       
        </div>
        <!-- /.modal -->      
        <!--inicio modal registrar tramo del bien-->    
        <div id="modalTramoBienRegistro"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> 
            <div class="modal-dialog">       
                <div class="modal-content">      
                    <input type="hidden" id="indiceTramo" value="0">     
                    <div class="modal-header">                   
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>         
                        <h4 class="modal-title">Registrar tramo</h4>          
                    </div>                    
                    <div class="modal-body">     
                        <div class="row">                   
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">             
                                <label id="bienTramoRegistro"></label>                  
                            </div>               
                        </div>                
                        <div class="row">           
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                                <label> </label>                
                            </div>                  
                        </div>   
                        <div class="row">     
                            <div class="form-group col-md-6">              
                                <label>Unidad de medida *</label>           
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                    <select name="cboUnidadMedidaTramo" id="cboUnidadMedidaTramo" class="select2"></select>        
                                    <i id='msjTipoUnidadMedidaTramo' style='color:red;font-style: normal;' hidden></i>        
                                </div>                 
                            </div>                   
                            <div class="form-group col-md-6">       
                                <label>Cantidad *</label>               
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">         
                                    <input type="number" id="txtCantidadTramo" name="txtCantidadTramo" class="form-control" value="0"/>        
                                </div>                        
                                <span id='msjCantidadTramo' class="control-label" style='color:red;font-style: normal;' hidden></span>            
                            </div>                     
                        </div>            
                    </div>           
                    <div class="modal-footer">  
                        <a class="btn btn-danger" id="id" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a>           
                        <a class="btn btn-success"  onclick="registrarTramoBien()"  ><i class="fa fa-send-o"></i> Enviar</a>        
                    </div>           
                </div>         
            </div>    
        </div>
        <!-- fin modal registro de tramo de bien -->  
        <!--Inicio modal para la busqueda y seleccion de tramo de bien-->     
        <div id="modalTramoBienBusqueda"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> 
            <div class="modal-dialog">              
                <div class="modal-content">
                    <div class="modal-header">      
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>      
                        <h4 class="modal-title">Seleccionar tramo</h4>         
                    </div>                   
                    <div class="modal-body">              
                        <div class="row">                 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">       
                                <label id="bienTramoBusqueda"></label>            
                            </div>                      
                        </div>                 
                        <div class="row">            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
                                <label> </label>            
                            </div>                       
                        </div>                    
                        <div class="table">          
                            <div id="dataList">           
                                <table id="datatableTramoBien" class="table table-striped table-bordered">     
                                    <thead>                           
                                        <tr>                            
                                            <th style='text-align:center;'>Unidad medida</th>    
                                            <th style='text-align:center;'>Cantidad</th>     
                                            <th style='text-align:center;'>Acción</th>                
                                        </tr>                              
                                    </thead>                  
                                </table>                   
                            </div>                   
                        </div>                   
                    </div>                  
                    <div class="modal-footer">    
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>       
                    </div>          
                </div>           
            </div>        
        </div>      
        <!--Fin modal para la busqueda y seleccion de tramo de bien-->     
        <!--Modal para seleccionar los correos.-->    
        <div id="modalCorreos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
            <div class="modal-dialog">         
                <div class="modal-content">         
                    <div class="modal-header">            
                        <button type="button" onclick="cancelarEnvioCorreos()" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4>Confirmación de correos</h4>        
                    </div>          
                    <div class="modal-body">   
                        <div class="row" id="rowDataTableCorreo">
                            <div class="table col-md-12">                       
                                <table class="table table-striped table-bordered">            
                                    <thead>                                 
                                        <tr>                               
                                            <th style='text-align:center;'>Correos</th>        
                                        </tr>                           
                                    </thead>                      
                                    <tbody id="tbodyDetalleCorreos">     
                                    </tbody>                         
                                </table>                   
                            </div>                           
                        </div>                                  
                        <div class="row">                     
                            <div class="form-group col-md-12">        
                                <label>Ingrese correo(s)</label>            
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                                    <textarea type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="" placeholder="email1@dominio.com;email2@dominio.com;" maxlength="500"></textarea> 
                                </div>                  
                            </div>              
                        </div>                
                    </div>               
                    <div class="modal-footer">  
                        <a class="btn btn-danger" onclick="cancelarEnvioCorreos()" id="id" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a> 
                        <a class="btn btn-success"  onclick="enviarCorreosMovimiento()"  ><i class="fa fa-send-o"></i> Enviar</a>    
                    </div>     
                </div>        
            </div>       
        </div>     
        <!--Fin modal correos-->     
        <!--Inicio modal para programacion de pagos-->        
        <div id="modalProgramacionPagos"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> 
            <div class="modal-dialog modal-lg">  
                <div class="modal-content">        
                    <div class="modal-header">          
                        <button type="button" class="close" aria-hidden="true" onclick="cancelarProgramacion()">×</button>   
                        <h4 class="modal-title"><b>Programación de pagos</b><label id="labelTotalDocumento" style="float: right; padding-right: 20px;"></label></h4>      
                    </div>            
                    <div class="modal-body">        
                        <input type="hidden" id="idPagoProgramacion" value="" />         
                        <div class="col-md-8">                         
                            <div class="row">                          
                                <div class="col-md-12">                  
                                    <div class="form-group col-md-6 form-inline" style="padding-left: 0px;border-right: 2px solid #e5e5e5;margin-bottom: 0px;padding-bottom: 7px;" >
                                        <div class="radio-inline" style="padding-left: 0px;">       
                                            <label class="cr-styled">                                 
                                                <input type="radio" id="rdFechaPago" name="rdTiempoPago" value="rdFechaPago" checked onchange="onChangeRdFechaPago()">        
                                                <i class="fa"></i>                                
                                                Fecha pago                             
                                            </label>                             
                                        </div>                                 
                                        <!--<div class="input-group" style="float: right">-->         
                                        <input type="text" style="float: right;width: 124.156px;" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaPago" disabled>
                                        <!--    
                                        <span class="input-group-addon">                       
                                        <i class="glyphicon glyphicon-calendar"></i>           
                                        </span>-->                                
                                        <!--</div>-->                         
                                    </div>                            
                                    <div class="form-group col-md-6  form-inline" style="padding-left: 0px;border-right: 2px solid #e5e5e5;margin-bottom: 0px;padding-bottom: 7px;">       
                                        <div class="radio-inline" style="padding-left: 10px;">                               
                                            <label class="cr-styled">                                         
                                                <input type="radio" id="rdImportePago" name="rdMontoPago" value="rdImportePago" checked onchange="onChangeRdImportePago()">      
                                                <i class="fa"></i>                                  
                                                Importe                                       
                                            </label>                                  
                                        </div>                                
                                        <input  style="float: right;width: inherit;text-align: right;" type="number" id="txtImportePago" name="txtImportePago" class="form-control" required="" aria-required="true" value="0"   onkeyup="actualizarPorcentajePago()" onchange="actualizarPorcentajePago()" disabled/>       
                                    </div>                           
                                </div>                
                            </div>                  
                            <div class="row">        
                                <div class="col-md-12">         
                                    <div class="form-group col-md-6 form-inline" style="padding-left: 0px;border-right: 2px solid #e5e5e5;margin-bottom: 0px;padding-bottom: 7px;">  
                                        <div class="radio-inline" style="padding-left: 0px;">                  
                                            <label class="cr-styled">                 
                                                <input type="radio" id="rdDias" name="rdTiempoPago" value="rdDias" onchange="onChangeRdDias()">    
                                                <i class="fa"></i>                                   
                                                Días                                         
                                            </label>                                  
                                        </div>                                   
                                        <input  style="float: right;width: inherit;" type="number" id="txtDias" name="txtDias" class="form-control" value="0" onkeyup="actualizarFechaPago()" onchange="actualizarFechaPago()" disabled/> 
                                    </div>                  
                                    <div class="form-group col-md-6 form-inline" style="padding-left: 0px;border-right: 2px solid #e5e5e5;margin-bottom: 0px;padding-bottom: 7px;">      
                                        <div class="radio-inline" style="padding-left: 10px;">         
                                            <label class="cr-styled">                                   
                                                <input type="radio" id="rdPorcentaje" name="rdMontoPago" value="rdPorcentaje" onchange="onChangeRdPorcentaje()"> 
                                                <i class="fa"></i>                                 
                                                Porcentaje (%)                                
                                            </label>                                        
                                        </div>                                   
                                        <input  style="float: right;width: inherit;text-align: right;" type="number" id="txtPorcentaje" name="txtPorcentaje" class="form-control" value="0" onkeyup="actualizarImportePago()" onchange="actualizarImportePago()" disabled/>       
                                    </div>                          
                                </div>                         
                            </div>                        
                        </div>                       
                        <div class="col-md-4" style="padding: 0px;">           
                            <div class="form-group col-md-12" style="padding: 0px;">
                                <label  class="cr-styled">Glosa </label>                     
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <!--<textarea type="text" id="txtGlosa" name="txtGlosa" class="form-control" value="" maxlength="500"></textarea>-->      
                                    <textarea type="text" id="txtGlosa" name="txtGlosa" value="" maxlength="500" rows="2" placeholder="" style="height: auto;width: 100%;display: block;padding: 4px 12px;border: 1px solid #eee;background-color: #fafafa;"></textarea>  
                                </div>                   
                            </div>                  
                        </div>                  
                        <div class="row">              
                            <div class="col-md-12">              
                                <a  style="float: right" class="btn btn-success"  onclick="agregarPagoProgramacion()"  ><i class="fa fa-plus-square-o"></i> Confirmar</a>    
                            </div>             
                        </div>                          
                        <br>                     
                        <div class="row">         
                            <div class="table col-md-12">         
                                <table id="dataTablePagoProgramacion" class="table table-striped table-bordered">        
                                    <thead>                                
                                        <tr>                                   
                                            <th style='text-align:center;'>Fecha</th> 
                                            <th style='text-align:center;'>Días</th>      
                                            <th style='text-align:center;'>Importe</th>             
                                            <th style='text-align:center;'>(%)</th>                       
                                            <th style='text-align:center;'>Glosa</th>                   
                                            <th style='text-align:center;'>Acciones</th>                 
                                        </tr>                              
                                    </thead>                          
                                    <tbody>                             
                                    </tbody>                          
                                    <tfoot>                            
                                        <tr>                               
                                            <th colspan="2" style="text-align: right">TOTAL</th>       
                                            <th class="alignRight" style="text-align:right;"></th>              
                                            <th class="alignRight" style="text-align:right;"></th>     
                                            <th colspan="2" class="alignRight" style="text-align:right;"></th>  
                                        </tr>                     
                                    </tfoot>                     
                                </table>                     
                            </div>                     
                        </div>                     
                        <div style="clear:left">   
                            <p><b>Leyenda:</b>&nbsp;&nbsp;     
                                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar &nbsp;&nbsp;&nbsp;  
                                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar &nbsp;&nbsp;&nbsp;     
                            </p><br>                  
                        </div>                  
                    </div>                 
                    <div class="modal-footer">    
                        <button onclick="cancelarProgramacion()" type="button" class="btn btn-danger m-b-5" id="btnCancelar" style="border-radius: 0px; margin-bottom:0px">
                            <i class="fa fa-close"></i>&ensp;Cancelar
                        </button>     
                        <button onclick="aceptarProgramacion(true)" type="button" class="btn btn-info m-b-5" id="btnCerrar" style="border-radius: 0px; margin-bottom:0px">
                            <i class="fa fa-send-o"></i>&ensp;Aceptar
                        </button>              
                    </div>            
                </div>            
            </div>        
        </div>        
        <!--Fin modal para programacion de pagos--> 
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
                                                    <input type="number" class="form-control" style="text-align: right; background-color: #F5F6CE" id="txtMontoAPagar" name="txtMontoAPagar" value="0.00">
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
        <!--fin modal nuevo documento pago con documento -->     

        <div id="modalAnticipos"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalAnticipos" aria-hidden="true" style="display: none;">       
            <div class="modal-dialog">            
                <div class="modal-content">               
                    <div class="modal-header">                      
                        <h4 class="modal-title">Seleccione los anticipos que desea aplicar</h4>         
                    </div>                     
                    <div class="modal-body">     
                        <div >
                            <h5>El proveedor cuenta con anticipos, los cuales están pendientes de aplicar. ¿Desea pagar directamente aplicando algún anticipo?</h5>
                        </div>                 
                        <div class="table">                      
                            <table id="dtAnticipos" class="table table-striped table-bordered">     
                                <thead>                              
                                    <tr>                                      
                                        <th style='text-align:center;'>Seleccione</th>                      
                                        <th style='text-align:center;'>Código</th> 
                                        <th style='text-align:center;'>F. Emisión</th> 
                                        <th style='text-align:center;'>Descripción</th>         
                                        <th style='text-align:center;'>Disponible</th>                       
                                    </tr>                             
                                </thead>                         
                            </table>                   
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <button id="btnLimpiaAnticipos" type="button" class="btn btn-danger" onclick="limpiarAnticipos()">No, gracias</button> 
                        <button id="btnAplicaAnticipos" type="button" class="btn btn-success" onclick="aplicarAnticipos()">Aplicar</button> 
                    </div>
                </div>         
            </div>     
        </div>


        <div id="modalActivosFijos"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalActivosFijos" aria-hidden="true" style="display: none;">       
            <div class="modal-dialog">            
                <div class="modal-content">               
                    <div class="modal-header">                      
                        <h4 class="modal-title">Seleccione el activo fijo relacionado su compra</h4>         
                    </div>                     
                    <div class="modal-body">     
                        <div class="row">     
                            <div class="form-group col-md-12">              
                                <label>Activos fijos *</label>           
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                    <select name="cboActivosFijos" id="cboActivosFijos" class="select2"></select>  
                                    <input  id="indexTablaDetalle" type="hidden" />
                                    <i id='msjCboActivoFijo' style='color:red;font-style: normal;' hidden></i>        
                                </div>                 
                            </div>            
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>  
                        <button type="button" class="btn btn-success" onclick="guardarRelacionActivoFijo()">
                            <i class="fa fa-send-o"></i>&ensp;Guardar
                        </button>   
                    </div>
                </div>         
            </div>     
        </div>

        <div id="datosImpresion" hidden="true">  
        </div> 


        <script src="vistas/com/movimiento/movimiento_form_tablas_liquidacion.js"></script>    
    </body>
</html>