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
        <input type="hidden" id="tipotipo" />
            <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />
            <input type="hidden" id="documentoId" value="<?php echo $_GET['documentoId']; ?>" />
            <input type="hidden" id="hddIsDependiente" value="1">       
            <div class="col-lg-12 form-group">  
                <div class="row"> 
                <div id="miModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <!-- class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar Documento Remitente</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                <div class="form-group col-md-6">
                                        <b for="inputEmail4" class="text-dark">T.Documento Cliente</b>
                                        <br>
                                        <select id="tipoDoc" onclick="miFuncion()" class="select2 " aria-label=".form-select-lg example">
                                            <option selected></option>
                                            <option value="01">01 | FACTURA</option>
                                            <option value="03"> 03 | BOLETA DE VENTA</option>
                                            <option value="82"> 82 | DECLARACION JURADA</option>
                                            <option value="31"> 31 | GUIA REMISION TRANSPORTISTA</option>
                                            <option value="09"> 09 |GUIA DE REMISION REMITENTE</option>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b for="inputEmail4" class="text-dark">Serie</b>
                                        <input style="color: blue;" id="serie" type="text" class="form-control" name="name" placeholder="Ingrese serie" autofocus>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <b for="inputEmail4" class="text-dark">Correlativo</b>
                                        <input style="color: blue;" id="correlativo" type="text" class="form-control" name="name" placeholder="Ingrese correlativo" autofocus>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <b for="inputEmail4" class="text-dark">Ruc/Dni</b>
                                        <input style="color: blue;" id="numDoc" type="text" class="form-control" name="name" placeholder="Ingrese Ruc/Dni" autofocus>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <br>
                                        <button type="button" id="agregarDatos" class="btn btn-success"><strong> <i class="ion-plus"></i> </strong>  Agregar </button>
                                    </div>
                                </div>
                                <ul id="listaDatos"></ul>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">N° Comprobante</th>
                                            <th scope="col">T. Documento Cliente</th>
                                            <th scope="col">Ruc/Dni</th>
                                            <th scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDatosBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-danger m-b-5" data-dismiss="modal" style="margin-bottom: 0px;"><i class="fa ion-android-close"></i> Cerrar</button>
                                <!-- <button id="cerrarModal" type="button" class="btn btn-danger" data-bs-dismiss="modal">Salir</button> -->
                                <!-- <button id="modalguardar" type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button> -->
                            </div>
                        </div>
                    </div>
                </div>    

                    <!--                    <div class="col-md-1"> 
                                            <select name="cboAgenciaOrigen" id="cboAgenciaOrigen" class="select2" onchange="cambiarTituloDescripcion('Origen');onChangeAgencia();"></select> 
                                        </div>
                                        <div class="col-md-1"> 
                                            <select name="cboAgenciaDestino" id="cboAgenciaDestino" class="select2" onchange="cambiarTituloDescripcion('Destino');onChangeAgencia();"></select> 
                                        </div>-->
                    <div class="col-md-2"> 
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                            <input type="text" id="txCodigo" name="txCodigo" class="form-control"  value="" maxlength="45" readonly="" style="text-align: right">
                            <span class="input-group-addon"><i class="fa fa-qrcode"></i></span>          
                        </div>   

                    </div>
                    <div class="col-md-2"> 
                        <select name="cboModalidad" id="cboModalidad" class="select2" onchange="onChangeCboModalidad(this.value)"></select> 
                    </div>

                    <div class="col-md-2"> 
                        <select name="cboComprobanteTipo" id="cboComprobanteTipo" class="select2" onchange="onChangeComprobanteTipo(this.value);"></select> 
                    </div>

                    <div class="col-md-2"> 
                        <select name="cboMoneda" id="cboMoneda" class="select2"></select> 
                    </div>

                    <div class="col-md-2"> 
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaEmision">    
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>          
                        </div>    
                    </div>

                    <div class="col-md-2"> 
                        <select name="cboPeriodo" id="cboPeriodo" class="select2"></select> 
                    </div> 
                </div>   
                <br/> 
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
                                        <select name="cboAgenciaOrigen" id="cboAgenciaOrigen" class="select2" onchange="onChangeAgencia();"></select> 

                                    </div>
                                    <div class="panel-body"> 
                                        <div class="form-group">
                                            <label for="cboCliente"><label id="lblClienteCbo">Cliente</label> 
                                                <span class="divider"></span> <a onclick="cargarPersona('Cliente', 2);"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar persona natural" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="cargarPersona('Cliente', 4);"><i class="fa fa-bank" tooltip-btndata-toggle="tooltip" title="Agregar persona juridica" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="editarPersona('Cliente');"><i class="fa fa-edit" tooltip-btndata-toggle="tooltip" title="Editar cliente" style="color: #1ca8dd;"></i></a>
                                                <span class="divider"></span> <a onclick="actualizarCboPersona('Cliente')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>
                                                <label  id="lblTelefonoCliente"></label>
                                            </label> 

                                            <select name="cboCliente" id="cboCliente" class="select2"></select>  
                                        </div> 
                                        <div class="form-group">
                                            <label for="cboClienteDireccion">Dirección recojo</label>
                                            <select name="cboClienteDireccion" id="cboClienteDireccion" class="select2"></select> 
                                        </div>
                                        
                                        <div id="divCboFacturado" class="form-group">
                                            <label for="cboFacturado"><label id="lblFacturadoCbo">Facturar a</label> 
                                                <span class="divider"></span> <a onclick="cargarPersona('Facturado', 2);"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar persona natural" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="cargarPersona('Facturado', 4);"><i class="fa fa-bank" tooltip-btndata-toggle="tooltip" title="Agregar persona juridica" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="editarPersona('Facturado');"><i class="fa fa-edit" tooltip-btndata-toggle="tooltip" title="Editar cliente" style="color: #1ca8dd;"></i></a>
                                                <span class="divider"></span> <a onclick="actualizarCboPersona('Facturado')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>
                                                <label  id="lblTelefonoFacturado"></label>
                                            </label> 

                                            <select name="cboFacturado" id="cboFacturado" class="select2"></select>  
                                        </div> 
                                        
                                        <div id="divClienteDireccionFacturacion" class="form-group">
                                            <label for="cboClienteDireccionFacturacion">Dirección facturación</label>
                                            <select name="cboClienteDireccionFacturacion" id="cboClienteDireccionFacturacion" class="select2"></select> 
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Horizontal form -->
                            <div class="col-md-6">
                                <div class="panel panel-info panel-color">
                                    <div class="panel-heading" style="padding-top: 10px; padding-right: 20px; padding-bottom: 10px; padding-left: 20px;">
                                        <h3 id="titleAgenciaDestino" class="panel-title">Destino</h3>
                                        <select name="cboAgenciaDestino" id="cboAgenciaDestino" class="select2" onchange="onChangeAgencia();"></select> 
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="form-group">
                                            <label for="cboDestinatario"><label id="lblDestinatarioCbo">Destinatario</label>
                                                <span class="divider"></span> <a onclick="cargarPersona('Destinatario', 2);"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar cliente" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="cargarPersona('Destinatario', 4);"><i class="fa fa-bank" tooltip-btndata-toggle="tooltip" title="Agregar persona juridica" style="color: #CB932A;"></i></a>

                                                <span class="divider"></span> <a onclick="editarPersona('Destinatario');"><i class="fa fa-edit" tooltip-btndata-toggle="tooltip" title="Editar cliente" style="color: #1ca8dd;"></i></a>
                                                <span class="divider"></span> <a onclick="actualizarCboPersona('Destinatario')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>
                                                <label  id="lblTelefonoDestinatario"></label>
                                            </label>
                                            <select name="cboDestinatario" id="cboDestinatario" class="select2"></select> 
                                        </div> 
                                        <div class="form-group">
                                            <label for="cboDestinatarioDireccion">Dirección despacho <i class="fa fa-map-marker"></i></label>
                                            <select name="cboDestinatarioDireccion" id="cboDestinatarioDireccion" class="select2" onchange="asignarImporteDocumento();"></select> 
                                        </div>
                                        <div id="divDestinatarioDireccionFacturacion" class="form-group">
                                            <label for="cboDestinatarioDireccionFacturacion">Dirección facturación</label>
                                            <select name="cboDestinatarioDireccionFacturacion" id="cboDestinatarioDireccionFacturacion" class="select2"></select> 
                                        </div>
                                        <div class="form-group">
                                            <label for="cboAutorizado"><label>Destinatario opcional</label>
                                                <span class="divider"></span> <a onclick="cargarPersona('Autorizado', 2);"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar cliente" style="color: #CB932A;"></i></a>
                                                <span class="divider"></span> <a onclick="cargarPersona('Autorizado', 4);"><i class="fa fa-bank" tooltip-btndata-toggle="tooltip" title="Agregar persona juridica" style="color: #CB932A;"></i></a>

                                                <span class="divider"></span> <a onclick="actualizarCboPersona('Autorizado')"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>
                                            </label>
                                            <select name="cboAutorizado" id="cboAutorizado" class="select2" multiple=""></select> 
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div>                       
                    </form>                   
                </div>                      
                <!--FIN PARTE DINAMICA-->   
                <div id="contenedorDetalle">                                 
                    <div class="row">    
                        <div   class="col-md-6">   
                            <div id="divGuiaSerieTransportista" class="col-md-6" hidden=""> 
                                <label>Serie guía transportista <a onclick="obtenerSerieCorrelativoGuia()"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a></label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                    <input  type="text" id="txtGuiaSerie" name="txtGuiaSerie" placeholder="" class="form-control" disabled=""/>                                                                                                                
                                </div>
                            </div>
                            <div  id="divGuiaCorrelativoTransportista" class="col-md-6" hidden=""> 
                                <label>Correlativo guía transportista</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input  type="text" id="txtGuiaCorrelativo" name="txtGuiaCorrelativo" placeholder="" class="form-control"/>                                                                                                                
                                </div>
                            </div>
                        </div>  
                        <!--<div class="col-md-3"></div>-->
                        <div class="col-md-6">   
                            <label>Guías</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <!--Cambios Cristopher-->                                                   
                                <div id="contenedorGuiaDivCombo" hidden="true">
                                    <div class="input-group">
                                        <select name="cboGuiaRelacion" id="cboGuiaRelacion" class="select2 "multiple=""></select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-effect-ripple btn-primary" 
                                                    onclick='mostrarDivTextoGuia();'><i class="ion-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div id="contenedorGuiaDivTexto" hidden="true">
                                    <div class="input-group">
                                        <input  type="text" id="txtGuiaRelacion" name="txtGuiaRelacion" placeholder="" class="form-control"/>                                                                                                                
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-effect-ripple btn-primary" 
                                                    onclick='mostrarDivComboGuia(true);'><i class="ion-checkmark"></i></button>
                                            <button type="button" class="btn btn-effect-ripple btn-danger" 
                                                    onclick='mostrarDivComboGuia(false);'><i class="ion-close-round"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>       
                            <button id="mostrarModal"  onclick="agregarDetalle()" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Agregar</button>
                    
                        </div>  
                    </div>  
                    <div class="row">    
                        <div class="col-md-6">   
                            <div style="float: left;padding-top: 25px;" id="divAccionesEnvio">
                                <button type="button" class="btn btn-success" onclick="agregarItem(null, 1)"><i class="fa fa-plus"></i> Por artículo</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-info" onclick="agregarItem(null, 0)"><i class="fa fa-plus"></i> Por medida</button>
                            </div>                            
                        </div> 
                    </div> 
                    <br/>
                    <!--Incluir tab-->
                    <div class="row" >        
                        <div class="form-group  col-md-12">   
                            <table id="datatable" class="table table-striped table-bordered" width="100%">      
                                <thead id="headDetalleCabecera">  
                                    <tr class="bg-info" style="color:white">
                                        <th width="15%" class="text-center" >Acciones</th>
                                        <th width="15%" class="text-center" >Cantidad</th>
                                        <th width="40%" class="text-center" >Concepto</th>
                                        <th width="15%" class="text-center" >Valor Unitario</th>
                                        <th width="15%" class="text-center" >Sub Total</th>
                                    </tr>
                                </thead>                                         
                                <tbody id="dgDetalle"></tbody>                                      
                            </table>                               
                        </div>  
                    </div>  
                    <div class="row text-center">                           
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                            <input type="number" id="txtOtroCargo" name="txtOtroCargo" class="form-control" value="" style="text-align: right;" onchange="asignarImporteDocumento()" onkeyup="asignarImporteDocumento()"/>    
                        </div>              
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'> 
                            <input type="text" id="txtDescripcionOtroCargo" style="text-align: right" name="txtDescripcionOtroCargo" class="form-control" value="Otros cargos" maxlength="45"> 
                        </div>
                        <div class="form-group  col-md-1" style='float: right;'>
                            <label class="cr-styled" style="text-align: left;" >        
                                <input type="checkbox" id="chkOtroCargo" onclick="onChangeCheckOtroCargo();" checked="true">   
                                <i class="fa"></i>   
                            </label>  
                        </div>
                    </div>
                    <div class="row text-center">                           
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                            <input type="number" id="txtDevolucionCargo" name="txtDevolucionCargo" class="form-control" value="" style="text-align: right;" onchange="asignarImporteDocumento()" onkeyup="asignarImporteDocumento()" disabled=""/>    
                        </div>              
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                            <label class="cr-styled" style="text-align: left;" >        
                                <input type="checkbox" id="chkDevolucionCargo" onclick="onChangeCheckDevolucionCargo();" checked="true">   
                                <i class="fa"></i>   
                                <median class="text-uppercase">Dev. de cargo</median>                 
                                <median class="text-uppercase" id="simDevolucionCargo">$</median> 
                            </label>  
                        </div>
                    </div>
                    <div class="row text-center">                           
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                            <input type="number" id="txtCostoReparto" name="txtCostoReparto" class="form-control" value="" style="text-align: right;" readonly=""/>    
                        </div>              
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                            <median class="text-uppercase" style="text-right">Costo reparto</median>
                        </div>
                    </div> 
                    <div class="row text-center">                           
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>    
                            <input type="number" id="txtRecojoDomicilio" name="txtRecojoDomicilio" class="form-control" value="" style="text-align: right;" readonly=""/>    
                        </div>              
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right;'>
                            <median class="text-uppercase" style="text-right">Costo recojo domicilio</median>
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
                                <h4 id="contenedorTotal"><input type="number" id="txtTotal" name="txtTotal" class="form-control" value=""  style="text-align: center;" readonly=""/></h4>                             
                                <median class="text-uppercase">Total</median>                   
                                <median class="text-uppercase" id="simTotal">$</median> 
                            </div>                                  
                        </div>                            
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right; border-right:thin solid #d1d1d1'>    
                            <div id="contenedorIgvDiv">                        
                                <h4 id="contenedorIgv"><input type="number" id="txtIgv" name="txtIgv" class="form-control" value=""  style="text-align: center;" readonly=""/></h4>                       
                                <median class="text-uppercase">                
                                <label class="cr-styled" style="text-align: left;" >        
                                    <input type="checkbox" id="chkIGV" onclick="onChangeCheckIGV();" checked="true">   
                                    <i class="fa"></i>   
                                    <median class="text-uppercase">IGV</median>                 
                                    <median class="text-uppercase" id="simIgv">$</median> 
                                </label> 
                                </median>                                    
                            </div>                                     
                        </div>                                      
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right; border-right:thin solid #d1d1d1'>   
                            <div id="contenedorSubTotalDiv">                                 
                                <h4 id="contenedorSubTotal"><input type="number" id="txtSubTotal" name="txtSubTotal" class="form-control" value=""  style="text-align: center;" disabled=""/></h4>                                
                                <median class="text-uppercase">Sub total</median>
                                <median class="text-uppercase" id="simSubTotal">$</median>
                            </div>                                      
                        </div> 
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style='float: right; border-right:thin solid #d1d1d1' >   
                            <div id="contenedorDetraccionDiv" hidden="">                                 
                                <h4 id="contenedorDetraccion"><input type="number" id="txtDetraccion" name="txtDetraccion" class="form-control" value=""  style="text-align: center;" /></h4>                                
                                <median class="text-uppercase">Detracción</median>                 
                                <median class="text-uppercase" id="simDetraccion">$</median> 
                            </div>                                      
                        </div>                                      
                    </div>                        
                </div>                        
                <div class="row">                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>                       
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   





                            <div class="row text-center m-t-10 m-b-10">           
                                <!--TIPO DE PAGO-->                              

                                <div style="float: right;padding-top: 25px;" id="divAccionesEnvio">
                                    <a  class="btn btn-danger" onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>
                                    &nbsp;&nbsp;<button type="button" class="btn btn-info" onclick="enviar()" id="env" name="env" ><i class="fa fa-send-o"></i> Generar</button>
                                </div>   
                            </div>                                            
                        </div>                          
                    </div>   
                </div> 
                <!-- end col -->    
            </div> 
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
                    <div class="modal-body">          
                        <div class="row">                       
                            <div class="col-lg-12">                   
                                <div id="divBuscador">                        
                                    <div class="form-group input-group">                        
                                        <span class="input-group-btn">                             
                                            <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" >      
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
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger">
                                                                <i class="fa fa-close"></i> Cancelar
                                                            </button>                             
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDocumentoRelacionPorCriterios()" class="btn btn-purple">
                                                                <i class="fa fa-search"></i> Buscar
                                                            </button>                              
                                                        </div>                                    
                                                    </div>                                      
                                                </li>                                       
                                                <li>                                        
                                                </li>                                        
                                            </ul>                                 
                                        </span>                                  
                                        <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarDocumentoRelacion()">
                                        <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegable2">      
                                        </ul>                         
                                        </input>                            
                                        <span class="input-group-btn">           
                                            <a type="button" class="btn btn-success" onclick="actualizarBusquedaDocumentoRelacion()" title="Actualizar resultados de búsqueda">
                                                <i class="ion-refresh"></i></a>                             
                                        </span>                            
                                    </div>                      
                                </div>                      
                            </div>                       
                        </div>                      
                        <div class="row">                    
                            <table id="dtDocumentoRelacion" class="table table-striped table-bordered" style="width: 100%">                     
                                <thead>                        
                                    <tr>                            
                                        <th style='text-align:center;'>F. creación</th>                
                                        <th style='text-align:center;'>F. emisión</th>             
                                        <th style='text-align:center;'>Tipo documento</th>           
                                        <th style='text-align:center;' id="nombreCeldaTHDocRelacion">Persona</th>                    
                                        <th style='text-align:center;'>S/N</th>                      
                                        <th style='text-align:center;'>S/N Doc.</th>                  
                                        <th style='text-align:center;'>F. venc.</th>                   
                                        <th style='text-align:center;'>M</th>                             
                                        <th style='text-align:center;'>Total</th>                         
                                        <th style='text-align:center;'>Usuario</th>                       
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
                                    <i class="fa fa-download" style="color:#04B404;"></i> Agregar documento a copiar&nbsp;&nbsp;       
                                    <i class="fa fa-arrow-down" style="color:#1ca8dd;"></i> Agregar documento a relacionar             
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

        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> 
            <div class="modal-dialog modal-full">   
                <div class="modal-content">            
                    <div class="modal-header">             
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>   
                        <h4 class="modal-title">Visualización del documento</h4>    
                    </div>               
                    <div class="modal-body">   
                        <div class="row">                 
                            <div class="col-lg-4">                        
                                <div class="portlet">
                                    <!-- /primary heading -->                 
                                    <div class="portlet-heading"> 
                                        <h3 id="nombreDocumentoTipo" class="portlet-title text-dark text-uppercase">   
                                        </h3>                                    
                                        <div class="clearfix"></div>             
                                    </div>                             
                                    <div id="portlet1" class="panel-collapse collapse in">
                                        <div class="portlet-body" >                      
                                            <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">    
                                            </form>                               
                                        </div>                             
                                    </div>                             
                                </div> 
                                <!-- /Portlet -->           
                            </div>         
                            <div class="col-lg-8 ">      
                                <div class="portlet">
                                    <!-- /primary heading -->        
                                    <div class="portlet-heading">              
                                        <h3 class="portlet-title text-dark text-uppercase">  
                                            Detalle del documento                               
                                        </h3>                                  
                                        <div class="portlet-widgets">              
                                        </div>                                
                                        <div class="clearfix"></div>              
                                    </div>                                   
                                    <div id="portlet2" class="panel-collapse collapse in">      
                                        <div class="portlet-body">                               
                                            <div class="panel panel-body">                            
                                                <table id="datatable2" class="table table-striped table-bordered">    
                                                    <thead>                                              
                                                        <tr>                                        
                                                            <th style='text-align:center;'>Organizador</th>              
                                                            <th style='text-align:center;'>Cantidad</th>        
                                                            <th style='text-align:center;'>Unidad de medida</th>     
                                                            <th style='text-align:center;'>Descripcion</th>           
                                                            <th style='text-align:center;'>Precio Unitario</th>          
                                                            <th style='text-align:center;'>Total</th>                       
                                                        </tr>                                       
                                                    </thead>                                
                                                </table>                                    
                                            </div>                                    
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
        <div id="modalCorreos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
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
                                        <!--<a  class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/unidad_listar.php')" >
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
                                <label>Confirmación de clave *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="password" id="txtClaveConfirmacion" name="txtClaveConfirmacion" class="form-control" required="" aria-required="true" maxlength="4" style="font-size: 16px;text-align: right;" onkeypress="return isNumeric(event);">
                                </div>
                            </div>
                        </div>
                    </div>                   
                    <div class="modal-footer">   
                        <div class="form-group col-md-5">
                            <a type="button" class="btn btn-danger w-sm m-b-5" style="border-radius: 0px;margin-bottom: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar!</a>
                        </div>
                        <div class="form-group col-md-1"></div>
                        <div class="form-group col-md-5">
                            <a id="btnConfirmacionRegistro" type="button" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Guardar!</a>
                        </div>
                    </div>       
                </div>         
            </div>     
        </div>

        <!--inicio modal-->        
        <div id="modalAgregarDetalle"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Detalle del documento</h4> 
                    </div> 
                    <div class="modal-body">  
                        <div id="divBienDetalle" class="row">  
                            <div class="form-group col-md-12">
                                <label>Articulo *</label>   
                                <select name="cboBien" id="cboBien" class="select2"></select>
                            </div>
                        </div>

                        <div id="divMedidaProducto" >
                            <div  class="row">                                        
                                <div class="col-md-12 text-center">
                                    <img id="myImg" src="vistas/images/cajamedidas.png" width="50%" height="50%" onerror="this.src='vistas/images/cajamedidas.png'"  />
                                </div> 
                            </div> 
                            <br/>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <b for="inputEmail4" class="text-dark">Descripcion</b>
                                    <input style="color: blue; border: 3px solid orange;" id="txtdescripcion" type="text" class="form-control" name="txtdescripcion" placeholder="Dice contener" autofocus>
                                </div>
                             </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Alto *</label>
                                    <input type="number" id="txtAltura" name="txtAltura" class="form-control" required="" aria-required="true" value="1" maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado()"/>
                                </div> 
                                <div class="form-group col-md-1">
                                    <label>&nbsp;</label> 
                                    <label>mts X</label> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Ancho *</label>
                                    <input type="number" id="txtAncho" name="txtAncho" class="form-control" required="" aria-required="true" value="1" maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado()"/>
                                </div> 
                                <div class="form-group col-md-1">
                                    <label>&nbsp;</label> 
                                    <label>mts X</label> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Largo *</label>
                                    <input type="number" id="txtLongitud" name="txtLongitud" class="form-control" required="" aria-required="true" value="1" maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado()"/>
                                </div>
                            </div>
                            <div class="row">                                 
                                <div class="form-group col-md-4">
                                    <label>Peso Volumentrico</label>
                                    <input type="hidden" id="txtFactorVolumetrico" name="txtFactorVolumetrico"/>
                                    <input type="number" id="txtPesoVolumetrico" name="txtPesoVolumetrico" class="form-control" required="" aria-required="true" value="1" maxlength="25" style="text-align: right;" disabled=""/>
                                    <label id="lblPesoVolumetricoTotal" style="color: #1ca8dd;"></label>
                                </div>                               
                                <div class="form-group col-md-4">
                                    <div class="radio-inline" style="padding-left: 0px;">
                                        <label class="cr-styled" for="rtnButtonPesoPaquete">
                                            <input type="radio" id="rtnButtonPesoPaquete" name="rtnButtonPeso" value="0"> 
                                            <i class="fa"></i> 
                                            <b>Peso Paquete</b> 
                                        </label>
                                    </div>
                                    <!--<label>Peso Paquete</label>-->
                                    <input type="number" id="txtPeso" name="txtPeso" class="form-control" required="" aria-required="true"  maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado()"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="radio-inline" style="padding-left: 0px;">
                                        <label class="cr-styled" for="rtnButtonPesoTotal">
                                            <input type="radio" id="rtnButtonPesoTotal" name="rtnButtonPeso" value="1"> 
                                            <i class="fa"></i>
                                            <b>Peso Total</b> 
                                        </label>
                                    </div>
                                    <input type="number" id="txtPesoTotal" name="txtPesoTotal" class="form-control" required="" aria-required="true"  maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado()"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Cantidad *</label>
                                <input type="number" id="txtCantidad" name="txtCantidad" class="form-control" required="" aria-required="true" value="1" maxlength="25" style="text-align: right;" onchange="obtenerPrecioCalculado()" onkeyup="obtenerPrecioCalculado();" onkeypress="return isNumeric(event);"/>
                            </div> 
                            <div class="form-group col-md-4">
                                <label>Precio Unitario*</label> 
                                <input type="number" id="txtPrecio" name="txtPrecio" class="form-control" required="" aria-required="true" value="" maxlength="25" style="text-align: right;" onchange="hallarSubTotalDetalle()" onkeyup="hallarSubTotalDetalle()"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sub Total*</label>  
                                <input type="number" id="txtSubTotalDetalle" name="txtSubTotalDetalle" class="form-control" required="" aria-required="true" value="" maxlength="25" style="text-align: right;" disabled=""/>
                            </div> 
                        </div> 

                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger m-b-5" data-dismiss="modal" style="margin-bottom: 0px;"><i class="fa ion-android-close"></i> Cerrar</button> 
                            <button type="button" class="btn btn-success m-b-5" onclick="confirmar(1)"><i class="fa fa-save"></i>&ensp;Agregar</button>
                            <button id="btnContinuar" type="button" class="btn btn-info m-b-5" onclick="confirmar()"><i class="fa ion-arrow-down-c"></i>&ensp;Continuar</button>
                        </div> 
                    </div> 
                </div>
            </div><!-- /.modal -->     
            <script src="vistas/com/movimiento/movimiento_form_tablas_static.js"></script>


    </body>
</html>