<div class="page-title">
    <h3 class="title">Provisiones por pagar</h3>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading"> 
                <h4 class="panel-title"> 
                    <a data-toggle="collapse" data-parent="#accordion-test-1" href="#collapseTwo-1" class="collapsed" aria-expanded="false">
                        REGISTRO
                    </a> 
                    <form class="form-horizontal col-md-4" role="form" style="float: right;">
                        <div class="form-group">
                            <a id="link_0" onclick="abrirModal('modalDetalleDocumento')" style="color:#0000FF" class="col-sm-9">[Orden de compra: 0001 - 000029]</a>
                            <div class="col-sm-3">
                                <a onclick="abrirModal('modalOrdenCompra')" id="cargarBuscadorDocumentoACopiar">   
                                    <i class="fa fa-files-o" tooltip-btndata-toggle="tooltip" title="Bandeja de órdenes de compra a relacionar" style="color: #5CB85C;float: right;"></i> 
                                </a> 
                            </div>
                        </div>
                    </form>

                    <!--<div style="float: right;">-->
                    <!--                        <div id="linkDocumentoACopiar" style="min-height: 0px;height: auto;">        
                                                <a id="link_0" onclick="visualizarDocumentoRelacion(0)" style="color:#0000FF">[Orden de compra: 0001 - 000029]</a>
                                            </div>-->
                    <!--                        <a onclick="abrirModal('modalOrdenCompra')" id="cargarBuscadorDocumentoACopiar">   
                                                <i class="fa fa-files-o" tooltip-btndata-toggle="tooltip" title="Bandeja de órdenes de compra a relacionar" style="color: #5CB85C;"></i> 
                                            </a> -->
                    <!--</div>-->
                </h4> 
            </div> 
            <div id="collapseTwo-1" class="panel-collapse collapse in"> 
                <div class="panel-body">
                    <div class="col-md-2">
                        <label>Periodo</label>
                        <select id ="" name="" class="select2">
                            <option value="1">Julio 2017</option>
                        </select>
                    </div>
                    <!--                    <div class="col-md-2">
                                            <label>Unidad negocio</label>
                                            <select id ="" name="" class="select2">
                                                <option value="1">01 General (Todos)</option>
                                            </select>
                                        </div>-->
                    <div class="col-md-4" style="padding-right: 0px;">
                        <div class="col-md-5">
                            <label>Número</label>
                            <input type="text" id="" name="" class="form-control" value="0001" maxlength="50" style="text-align: right">
                        </div>
                        <div class="col-md-7">
                            <label>Fecha</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="" value="05/07/2017">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding-left: 0px;">
                        <!--                        <div class="col-md-4">
                                                    <label>O/Compra</label>
                                                    <input type="text" id="" name="" class="form-control" value="OC 201606-00257" maxlength="50">
                                                </div>-->
                        <div class="col-md-5">
                            <label>Sucursal</label>
                            <select id ="" name="" class="select2">
                                <option value="1">01|Trujillo</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label>Tipo compra</label>
                            <select id ="" name="" class="select2">
                                <option value="1">01|Compras nacionales</option>
                                <option value="2">02|Compras exterior</option>
                                <option value="3">03|Compras a relacionadas</option>
                            </select>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>


    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading"> 
                <div class="row">
                    <h4 class="panel-title"> 
                        <!--<div class="col-md-2">-->
                        <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="collapsed col-md-2" aria-expanded="false">
                            DOCUMENTO
                        </a>
                        <!--</div>-->

                        <div class="col-md-2">
                            <!--<label>Tipo doc.</label>-->
                            <select id ="" name="" class="select2">
                                <option value="1">00|Otros</option>
                                <option value="1" selected>01|Factura</option>
                                <option value="1">02|Recibo por Honorarios</option>
                                <option value="1">03|Boleta de Venta</option>
                            </select>
                        </div> 
                        <div class="col-md-3">
                            <div class="col-md-5">
                                <!--<label>Serie</label>-->
                                <input type="text" id="" name="" class="form-control" value="002" maxlength="50" style="text-align: right">
                            </div>
                            <div class="col-md-7">
                                <!--<label>Número</label>-->
                                <input type="text" id="" name="" class="form-control" value="04231" maxlength="50" style="text-align: right">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!--<label>Fecha</label>-->
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="" value="06/07/2017">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">   
                            <div class="col-md-5">
                                <!--<label>T.Cambio</label>-->
                                <input type="number" id="" name="" class="form-control" value="3.25" maxlength="10" style="text-align: right">
                            </div>                         
                            <div class="col-md-7">
                                <!--<label>Moneda</label>-->
                                <select id ="" name="" class="select2">
                                    <option value="1">01|Soles</option>
                                    <option value="1">02|Dolares</option>
                                </select>
                            </div>
                        </div>
                    </h4> 
                </div>
            </div> 
            <div id="collapseTwo-2" class="panel-collapse collapse in"> 
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Proveedor</label>
                            <select id ="" name="" class="select2">
                                <option value="1">ABC MULTISERVICIOS GENERALES E.I.R.L. | 20600759141</option>
                            </select>
                        </div>
                        <!--<div class="col-md-4" style="padding-right: 0px;">-->

                        <!--</div>-->
                        <!--<div class="col-md-4" style="padding-right: 0px;">-->
                        <!--</div>-->
                        <!--</div>-->
                        <!--XXXXXXXXXXXXXXXXXXXx-->
                        <!--<div class="row">-->
                        <div class="col-md-2">
                            <label>Condición de pago</label>
                            <select id ="" name="" class="select2">
                                <option value="1">01|Contado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Vencimiento</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="" value="06/07/2017">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Glosa/Comentario</label>
                            <input type="text" id="" name="" class="form-control" value="04231" maxlength="50" >
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>

    <!--    <div class="col-lg-12">
            <div class="panel panel-default"> 
                <div class="panel-heading"> 
                    <h4 class="panel-title"> 
                        <a data-toggle="collapse" data-parent="#accordion-test-3" href="#collapseTwo-3" class="collapsed" aria-expanded="false">
                            Tributos
                        </a> 
                    </h4> 
                </div> 
                <div id="collapseTwo-3" class="panel-collapse collapse in"> 
                    <div class="panel-body">
    
                    </div> 
                </div> 
            </div>
        </div>-->

    <!--    <div class="col-lg-3">
            <div class="panel panel-default"> 
                <div class="panel-heading"> 
                    <h4 class="panel-title"> 
                        <a data-toggle="collapse" data-parent="#accordion-test-4" href="#collapseTwo-4" class="collapsed" aria-expanded="false">
                            Retención liq. compra
                        </a> 
                    </h4> 
                </div> 
                <div id="collapseTwo-4" class="panel-collapse collapse in"> 
                    <div class="panel-body">
    
                    </div> 
                </div> 
            </div>
        </div>-->

    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading"> 
                <div class="row">
                    <h4 class="panel-title"> 
                        <a data-toggle="collapse" data-parent="#accordion-test-5" href="#collapseTwo-5" class="collapsed col-md-2" aria-expanded="false">
                            IMPORTES
                        </a> 
                    </h4> 
                    <div class="col-md-10">
                        <div class="col-md-2">
                            <label>Base afecta</label>
                            <input type="number" id="" name="" class="form-control" value="3548.30" maxlength="10" style="text-align: right">
                        </div>
                        <div class="col-md-2">
                            <label>Inafecto</label>
                            <input type="number" id="" name="" class="form-control" value="0.00" maxlength="10" style="text-align: right">
                        </div>
                        <div class="col-md-2">
                            <label class="cr-styled">        
                                <input type="checkbox" id="chkIGV" onclick="" checked="true" style="text-align: right;">
                                <i class="fa"></i>                                      
                                <b>IGV</b>                                              
                            </label>
                            <input type="number" id="" name="" class="form-control" value="638.69" maxlength="10" style="text-align: right">
                        </div>
                        <div class="col-md-2">
                            <label class="cr-styled">        
                                <input type="checkbox" id="chkIGV" onclick="" style="text-align: right;">
                                <i class="fa"></i>                                      
                                <b>Percepción</b>                                              
                            </label>
                            <input type="number" id="" name="" class="form-control" value="0.00" maxlength="10" style="text-align: right">
                        </div>
                        <div class="col-md-2">
                            <label class="cr-styled">        
                                <input type="checkbox" id="chkIGV" onclick=""  style="text-align: right;">
                                <i class="fa"></i>                                      
                                <b>Renta</b>                                              
                            </label>
                            <input type="number" id="" name="" class="form-control" value="0.00" maxlength="10" style="text-align: right">
                        </div>
                        <div class="col-md-2">
                            <label>Total</label>
                            <input type="number" id="" name="" class="form-control" value="0.00" maxlength="10" style="text-align: right">
                        </div>
                    </div>
                </div>
            </div> 
            <div id="collapseTwo-5" class="panel-collapse collapse in"> 
                <div class="panel-body">

                    <!--                </div> 
                                </div> 
                            </div>
                        </div>-->

                    <!--    <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body"> -->
                    <div class="col-lg-12"> 
                        <ul class="nav nav-tabs"> 
                            <li class="active"> 
                                <a href="#inicio" data-toggle="tab" aria-expanded="true"> 
                                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                    <span class="hidden-xs">Detalle del documento</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#profile" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-user"></i></span> 
                                    <span class="hidden-xs">Datos adicionales</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#messages" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-envelope-o"></i></span> 
                                    <span class="hidden-xs">Importación</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#settings" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-cog"></i></span> 
                                    <span class="hidden-xs">No domicialiados</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#settings" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-cog"></i></span> 
                                    <span class="hidden-xs">Historial</span> 
                                </a> 
                            </li> 
                        </ul> 
                        <div class="tab-content"> 
                            <div class="tab-pane active" id="inicio"> 
                                <div> 
                                    <div class="row">                    
                                        <table id="tablaDetalle" class="table table-striped table-bordered" style="width: 100%">                     
                                            <thead>                        
                                                <tr>                            
                                                    <th style='text-align:center;'>Item</th>                
                                                    <th style='text-align:center;'>Referencia</th>                 
                                                    <th style='text-align:center;'>Cuenta</th>                                     
                                                    <th style='text-align:center;'>Descripcion</th>                   
                                                    <th style='text-align:center;'>OP</th>                   
                                                    <th style='text-align:center;'>C.Costo</th>                             
                                                    <th style='text-align:center;'>Descripcion C.C.</th>                         
                                                    <th style='text-align:center;'>Importe</th>                       
                                                    <th style='text-align:center;'>Actividad</th>                       
                                                    <th style='text-align:center;'>Descripción act.</th>                                
                                                    <th style='text-align:center;'>Proyecto</th>                       
                                                    <th style='text-align:center;'>Descripción pry.</th>                                
                                                </tr>                    
                                            </thead> 
                                            <tbody>
                                                <tr role="row" class="even">
                                                    <td class="sorting_1">1</td>
                                                    <td>42 201707-0029</td>
                                                    <td>42111</td>
                                                    <td>PEDIDO ORDEN DE COMPRA </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class=" alignRight">4,187.00</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>              
                                    </div>       
                                </div> 
                            </div> 
                            <div class="tab-pane" id="profile">  
                                <div> 

                                </div>                                
                            </div> 
                            <div class="tab-pane" id="messages">  
                                <div> 

                                </div>                              
                            </div> 
                            <div class="tab-pane" id="settings">  
                                <div> 

                                </div>
                            </div> 
                        </div> 
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                    
                    <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">-->      
                    <div style="float: right;" id="divAccionesEnvio">
                        <a href="#" class="btn btn-danger" onclick="cargarPantallaListar()">
                            <i class="fa fa-close"></i> Cancelar
                        </a>&nbsp;&nbsp;
                        <button type="button" class="btn btn-success" onclick="cargarPantallaListar()" name="env" id="env">
                            <i class="fa fa-send-o"></i> Enviar
                        </button>
                    </div>   
                    <!--</div>-->                                            
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalOrdenCompra"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
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
                                <th style='text-align:center;'>S/N</th>                                     
                                <th style='text-align:center;'>Persona</th>                   
                                <th style='text-align:center;'>Comentario</th>                   
                                <th style='text-align:center;'>M</th>                             
                                <th style='text-align:center;'>Total</th>                         
                                <th style='text-align:center;'>Usuario</th>                       
                                <th style='text-align:center;'></th>                               
                            </tr>                    
                        </thead> 
                        <tbody>
                            <tr role="row" class="even">
                                <td class="sorting_1">2017-06-20 15:10:04</td>
                                <td>2017-05-04</td>
                                <td>0001 - 000030</td>
                                <td>ABC MULTISERVICIOS GENERALES E.I.R.L. </td>
                                <td>Pedidos OC</td>
                                <td class=" alignCenter">$</td>
                                <td class=" alignRight">12,282.00</td>
                                <td class=" alignCenter">imagina</td>
                                <td class=" alignCenter"><a onclick="cerrarModal('modalOrdenCompra')"><b><i class="fa fa-download" style="color:#04B404;" tooltip-btndata-toggle="tooltip" title="Copiar">&nbsp;&nbsp;</i></b></a></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="sorting_1">2017-06-20 14:32:12</td>
                                <td>2017-05-04</td>
                                <td>0001 - 000029</td>
                                <td>ABC MULTISERVICIOS GENERALES E.I.R.L. </td>
                                <td>Pedidos OC 2</td>
                                <td class=" alignCenter">$</td>
                                <td class=" alignRight">4,187.00</td>
                                <td class=" alignCenter">imagina</td>
                                <td class=" alignCenter"><a onclick="cerrarModal('modalOrdenCompra')"><b><i class="fa fa-download" style="color:#04B404;" tooltip-btndata-toggle="tooltip" title="Copiar">&nbsp;&nbsp;</i></b></a></td>
                            </tr>
                        </tbody>
                    </table>              
                </div>                 
            </div>                
            <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px;clear:left">            
                <div class="form-group">                    
                    <div class="col-md-6" style="text-align: left;">        
                        <p><b>Leyenda:</b>&nbsp;&nbsp;                          
                            <i class="fa fa-download" style="color:#04B404;"></i> Agregar orden de compra&nbsp;&nbsp;       
                            <!--<i class="fa fa-arrow-down" style="color:#1ca8dd;"></i> Agregar documento a relacionar-->             
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

<div id="modalDetalleDocumento"   class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
    <div class="modal-dialog modal-full"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal">Orden de compra</h4> 
            </div>
            <div class="modal-body" style="padding-bottom: 0px"> 
                <div class="row">

                    <div class="col-lg-12">
                        <div class="row" style="box-shadow: 0 0px 0px">
                            <div id="portlet1" class="row">
                                <div class="portlet-body">
                                    <form id="formularioDetalleDocumento" method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">

                                        <div class="row"></div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Proveedor</label></div>

                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">ABC MULTISERVICIOS GENERALES E.I.R.L.  | 20600759141</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Serie</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">0001</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Número</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">000029</div>
                                        </div>
                                        <div class="row"></div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Fecha del documento</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">04/05/2017</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Forma de pago</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">Credit</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Número de orden</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6"><label></label></div>
                                        </div>
                                        <div class="row"></div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Tiempo de entrega</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">30 días</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Condiciones comerciales</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">50% before shipment, 50% within 30 days after bill of lading date</div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Fecha BL</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                                                <label></label>
                                            </div>
                                        </div>
                                        <div class="row"></div>
                                        <div class="form-group col-md-4">
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label>Importe total</label></div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">4,187.00</div>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div> <!-- /Portlet -->
                    </div>
                    <div class="col-lg-12 ">
                        <div class="portlet" style="box-shadow: 0 0px 0px">
                            <div id="portlet2" class="row">
                                <div class="portlet-body">
                                    <table id="datatable2" class="table table-striped table-bordered">
                                        <thead id="theadDetalle"><tr><th style="text-align:center;">Cantidad</th><th style="text-align:center;">Unidad de medida</th><th style="text-align:center;">Producto</th> <th style="text-align:center;">Precio Unitario</th><th style="text-align:center;">Total</th></tr></thead>
                                        <tbody id="tbodyDetalle"><tr><td style="text-align:right;">3</td><td>Unidad(es)</td><td>201-YT60RT127-P | RETRAC BIT YT60 127MM, 5" (P)</td> <td style="text-align:right;">230.00</td><td style="text-align:right;">690.00</td></tr><tr><td style="text-align:right;">4</td><td>Unidad(es)</td><td>203-YT60L76E111R65-V | Shank DP1500 / YT60  / L = 760 mm (P)</td> <td style="text-align:right;">345.00</td><td style="text-align:right;">1,380.00</td></tr><tr><td style="text-align:right;">5</td><td>Unidad(es)</td><td>201-T51RT127-P | RETRAC BIT T51 127MM (P)</td> <td style="text-align:right;">122.00</td><td style="text-align:right;">610.00</td></tr><tr><td style="text-align:right;">2</td><td>Unidad(es)</td><td>203-T51L60E95R52-V | Shank DX800 / T51/ L=600mm (P)</td> <td style="text-align:right;">185.00</td><td style="text-align:right;">370.00</td></tr><tr><td style="text-align:right;">1</td><td>Unidad(es)</td><td>IMPSEG | SEGURO</td> <td style="text-align:right;">5.00</td><td style="text-align:right;">5.00</td></tr><tr><td style="text-align:right;">1</td><td>Unidad(es)</td><td>IMPFLE | FLETE</td> <td style="text-align:right;">1,132.00</td><td style="text-align:right;">1,132.00</td></tr></tbody>
                                    </table>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="input-group m-t-10" style="float: right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<script src="vistas/com/contabilidadProvisionesPorPagar/provisiones_por_pagar_form.js"></script>