<!DOCTYPE html>
<html lang="es">
    <head>
        <style type="text/css" media="screen">
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
            .tfoo tr td {
                text-align: right;
            }
            #sunat_loader{
                content: url('data:image/gif;base64,R0lGODlhEAAQAPQAAP///wBtzPj6/DiM14S45QZwzSaC09bn9qjM7RZ50Haw42an4OTv+JjD6cbe8kiW2lae3AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAAFUCAgjmRpnqUwFGwhKoRgqq2YFMaRGjWA8AbZiIBbjQQ8AmmFUJEQhQGJhaKOrCksgEla+KIkYvC6SJKQOISoNSYdeIk1ayA8ExTyeR3F749CACH5BAkKAAAALAAAAAAQABAAAAVoICCKR9KMaCoaxeCoqEAkRX3AwMHWxQIIjJSAZWgUEgzBwCBAEQpMwIDwY1FHgwJCtOW2UDWYIDyqNVVkUbYr6CK+o2eUMKgWrqKhj0FrEM8jQQALPFA3MAc8CQSAMA5ZBjgqDQmHIyEAIfkECQoAAAAsAAAAABAAEAAABWAgII4j85Ao2hRIKgrEUBQJLaSHMe8zgQo6Q8sxS7RIhILhBkgumCTZsXkACBC+0cwF2GoLLoFXREDcDlkAojBICRaFLDCOQtQKjmsQSubtDFU/NXcDBHwkaw1cKQ8MiyEAIfkECQoAAAAsAAAAABAAEAAABVIgII5kaZ6AIJQCMRTFQKiDQx4GrBfGa4uCnAEhQuRgPwCBtwK+kCNFgjh6QlFYgGO7baJ2CxIioSDpwqNggWCGDVVGphly3BkOpXDrKfNm/4AhACH5BAkKAAAALAAAAAAQABAAAAVgICCOZGmeqEAMRTEQwskYbV0Yx7kYSIzQhtgoBxCKBDQCIOcoLBimRiFhSABYU5gIgW01pLUBYkRItAYAqrlhYiwKjiWAcDMWY8QjsCf4DewiBzQ2N1AmKlgvgCiMjSQhACH5BAkKAAAALAAAAAAQABAAAAVfICCOZGmeqEgUxUAIpkA0AMKyxkEiSZEIsJqhYAg+boUFSTAkiBiNHks3sg1ILAfBiS10gyqCg0UaFBCkwy3RYKiIYMAC+RAxiQgYsJdAjw5DN2gILzEEZgVcKYuMJiEAOwAAAAAAAAAAAA==')
            }
            

        </style>
    </head>
    <body>
        <!--row para los datos generales-->
        <div class="row">
            <div class="col-lg-4">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <h3 class="portlet-title text-dark text-uppercase">
                            Datos generales del pago
                        </h3>
                        <div class="portlet-widgets">
                            <a data-toggle="collapse" data-parent="#accordion1" href="#portlet"><i class="ion-minus-round"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="portlet" class="panel-collapse collapse in">
                        <div class="portlet-body">
                            <form  id="formularioClienteFecha"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Proveedor *   </label>
                                            <select name="cboClientePagoPago" id="cboClientePagoPago" class="select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Fecha de pago *   </label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_fechaPago" onchange="obtenerTipoCambioDatepicker();">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="col-lg-8">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <h3 class="portlet-title text-dark text-uppercase">
                            Documentos a pagar *
                        </h3>
                        <div class="portlet-widgets">
                            <a onclick="modalBusquedaDocumentoAPagarPago();" class="btn btn-purple m-b-5" style="border-radius: 0px;" title="Buscar documento a pagar"><i class="fa ion-ios7-search-strong"></i> Buscar</a>
                            <span class="divider"></span>
                            <a data-toggle="collapse" data-parent="#accordion1" href="#portletDocumentoPago"><i class="ion-minus-round"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="portletDocumentoPago" class="panel-collapse collapse in">
                        <div class="portlet-body" style="min-height: 165px;">
                            <form  id="formDocumentoPendientePago"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                <table id="datatableAPagar" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>Tipo de Doc</th>
                                            <th style='text-align:center;'>Serie</th> 
                                            <th style='text-align:center;'>Número</th> 
                                            <th style='text-align:center;'>Total</th>
                                            <th style='text-align:center;'>Deuda liberada</th>
                                            <th style='text-align:center;'>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dgDocumentoPago">

                                    </tbody>
                                    <tfoot id="tfoo1" class="tfoo">
                                    </tfoot>
                                </table>
                            </form>
                            <div id="divLeyendaDocumentoPago" style="clear:left">
                            <p><b>Leyenda:</b>&nbsp;&nbsp;
                                <i class="ion-close" style="color:#cb2a2a;"></i> Quitar&nbsp;&nbsp;&nbsp;
                            </p>
                            </div>
                        </div>
                        
                    </div>
                </div> <!-- /Portlet -->
            </div>
        </div>
        <!-- fin row para los datos generales-->

        <!--row para pago en efectivo y con documentos-->
        <div class="row">
            <div class="col-lg-12">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <div class="row">
                            <div class="col-lg-2">
                                <h3 class="portlet-title text-dark text-uppercase">
                                    Pago en efectivo y con documentos
                                </h3>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label style="color: #797979;">Moneda:</label>
                                        <select id ="monedaId" class="form-control select2">
                                            <option value="2">Soles</option>
                                            <option value="4" selected>Dolares</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label style="color: #797979;">TC:</label>
                                        <input type="number" id="tipoCambio" class="form-control" style="text-align: right;" value="0.00" disabled="true"/>                                        
                                        <div class="checkbox">
                                            <label style="color: #797979;" class="cr-styled">
                                                <input type="checkbox" id="checkBP"><i class="fa"></i> Personalizado
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="portlet-widgets">
                                    <a onclick="modalBusquedaPagoConDocumentoPago();" class="btn btn-purple m-b-5" style="border-radius: 0px;"><i class="fa ion-ios7-search-strong"></i> Buscar</a>
                                    <span class="divider"></span>
                                    <button onclick="modalNuevoDocumentoPagoConDocumentoPago();" id="btnNuevo" name="btnNuevo" class="btn btn-info m-b-5" style="border-radius: 0px;"><i class="fa fa-money"></i> Nuevo</button>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portletPagoEfectivoConDocumentos"><i class="ion-minus-round"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="portletPagoEfectivoConDocumentos" class="panel-collapse collapse in">
                        <div class="portlet-body" style="min-height: 320px;height: auto;">
                            <div class="col-lg-4">
                                <div class="checkbox">
                                    <label style="color: #797979; text-align: left;" class="cr-styled">
                                        <input type="checkbox" id="checkPE"> 
                                        <i class="fa"></i>Habilitar Pago con efectivo
                                    </label>
                                </div>
                                <div class="widget-panel widget-style-1 bg-success">
                                    <div id="loaderPagoEfectivo" class="panel-disabled-imagina"></div>
                                    
                                    <i class="fa ion-cash"></i> 
                                    <div class="row">
                                        <div class="form-group col-md-12 ">
                                            <div class="form-group col-md-12 ">
                                                <div class="form-group col-md-5 ">
                                                    <label>Monto a pagar</label>
                                                </div>
                                                <div class="form-group col-md-7 ">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <span class="counter"><input type="number" class="form-control" style="text-align: right; background-color: #F5F6CE" id="txtMontoAPagar" name="txtMontoAPagar" value="0.00" disabled></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 ">
                                                <div class="form-group col-md-12 ">
                                                    <select name="cboActividadEfectivo" id="cboActividadEfectivo" class="select2" disabled></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div id="contenedorDocumentoTipo" style="min-height: 75px;height: auto;">
                                    <form  id="formularioDocumentoPendientePago"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">

                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style='text-align:center;'>Tipo de Doc</th>
                                                    <th style='text-align:center;'>Número</th> 
                                                    <th style='text-align:center;'>Moneda</th> 
                                                    <th style='text-align:center;'>Total</th> 
                                                    <th style='text-align:center;'>Disponible</th> 
                                                    <th style='text-align:center;'>Acciones</th> 
                                                </tr>
                                            </thead>

                                            <tbody id="dgDocumentoPagoConDocumento">

                                            </tbody>
                                            <tfoot id="tfoo2" class="tfoo">
                                                
                                            </tfoot>

                                        </table>
                                    </form>
                                    <div id="divLeyendaDocumentoDePago" style="clear:left">
                                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                                        <i class="ion-close" style="color:#cb2a2a;"></i> Quitar&nbsp;&nbsp;&nbsp;
                                    </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /Portlet -->
                </div> <!-- end col -->
            </div>
        </div>
        <!-- fin row para pago en efectivo y con documentos-->


        <div class="row">
            <div class="form-group">
                <div class="col-lg-offset-6 col-lg-10">
                    <button type="button" href="#" class="btn btn-danger m-b-5"  onclick="listarForm()" ><i class="fa fa-close"></i> Cancelar</button>
                    <button type="button" class="btn btn-success m-b-5 btn-submit"  onclick="confirmarRegistrarPago()" name="env" id="env" ><i class="fa fa-send-o"></i> Enviar</button>
                </div>
            </div>
        </div>


        <!--inicio modal nuevo documento pago con documento-->
        <div id="modalNuevoDocumentoPagoConDocumentoPago"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog "> 
                <div class="modal-content"> 
                    <div class="modal-header" style="padding-bottom: 0px;"> 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
                                    <h4 class="modal-title text-dark text-uppercase">Nuevo documento<b><span id="span_moneda"></span></b></h4> 
                                </div>      
                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style="margin-top: -15px;">
                                    <h4>                                   
                                        <select id="cboPeriodo" name="cboPeriodo" class="select2"  style="width: 100%">         
                                        </select>                                  
                                    </h4>
                                </div>                                      
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >&ensp;<i class="ion-close-round" tooltip-btndata-toggle='tooltip' title="Cerrar"></i></button> 
                                    <span class="divider"></span>
                                    <button type="button" class="close" onclick="getAllProveedor()"><i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i></button> 
                                </div>
                            </div> 
                        </div>
                    </div> 
                    <div class="modal-body" style="padding-top: 10px;"> 

                        <div style="min-height: 75px;height: auto;">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h3 class="text-dark text-uppercase">
                                            <select name="cboDocumentoTipoNuevoPagoConDocumentoPago" id="cboDocumentoTipoNuevoPagoConDocumentoPago" class="select2"></select>
                                        </h3>   
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <button type="button" onclick="enviarDocumento()" value="enviar" name="btnEnviar" id="btnEnviar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                            <span class="divider"></span>
                            <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                        </div>
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal --> 
        <!--fin modal nuevo documento pago con documento -->

        <!--inicio modal documento a pagar busqueda-->
        <div id="modalBusquedaDocumentoPagar"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-body">
                        
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
                                                        <label>Tipo doc.</label>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2" multiple>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                                        
                                                    </form>
                                                </div>
                                            </li>
                                            <li>
                                                <div style="float: right; margin-top: 19px">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDocumentoPago(1)" class="btn btn-purple"> <i class="fa fa-search"></i> Buscar</button>                                        
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </span>
                                
                                    <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusquedaDocumentoPagarPago()">                                
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegable2">
                                    </ul>
                                    <span class="input-group-btn">
                                        <a type="button" class="btn btn-success" onclick="loaderBuscarDocumentoPago()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                                    </span>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <table id="datatableModalDocumentoAPagar" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>F. Creación</th>
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <th style='text-align:center;'>Tipo documento</th>
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Número</th> 
                                            <th style='text-align:center;'>F. Venc.</th>
                                            <th style='text-align:center;'>Moneda</th>
                                            <th style='text-align:center;'>Pendiente</th>
                                            <th style='text-align:center;'>Deuda liberada</th>
                                            <th style='text-align:center;'>Total</th>
                                            <th style='text-align:center;'> </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px">
                                <div class="form-group">
                                    <div class="col-md-6" style="text-align: left;">
                                        <p><b>Leyenda:</b>&nbsp;&nbsp;
                                            <i class="ion-android-add" style="color:#2E9AFE;"></i> Agregar documento &nbsp;&nbsp;&nbsp;
                                            <i class="fa fa-arrow-down" style="color:#04B404;"></i> Agregar documento y cerra ventana &nbsp;&nbsp;&nbsp;
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
            </div>
        </div>
        <!--fin modal-->

        <!--inicio modal Pagar con documento-->
        <div id="modalBusquedaPagoConDocumentoPago"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-body"> 
                        <div id="divBuscadorPagoConDocumento">
                                <div class="form-group input-group">
                                    <span class="input-group-btn">                                        
                                        <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                            <i class="caret"></i>
                                        </a>
                                        <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5002" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegablePagoConDocumento">
                                            <li>
                                                <div id="divTipoDocumentoPagoConDocumento">
                                                    <div class="form-group col-md-2">
                                                        <label>Tipo doc.</label>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboDocumentoTipoPagoConDocumentoPago" id="cboDocumentoTipoPagoConDocumentoPago" class="select2" multiple>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <form  id="formularioDocumentoTipoPagoConDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </li>
                                            <li>
                                                <div style="float: right; margin-top: 19px">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDocumentoPagoConDocumentoPago()" class="btn btn-purple"> <i class="fa fa-search"></i> Buscar</button>                                        
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                            </li>
                                        </ul>
                                    </span>
                                
                                    <input type="text" id="txtBuscarPagoConDocumento" name="txtBuscarPagoConDocumento" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusquedaPagoConDocumento()">                                
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5004" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegablePagoConDocumento2">
                                    </ul>
                                    </input>
                                    <span class="input-group-btn">
                                        <a type="button" class="btn btn-success" onclick="buscarDocumentoPagoConDocumentoPago()()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                                    </span>
                                </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <table id="datatableModalDocumentoPagoConDocumento" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>F. Creación</th>
                                            <th style='text-align:center;'>F. Emisión</th>
                                            <th style='text-align:center;'>Tipo documento</th>
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Número</th> 
                                            <th style='text-align:center;'>F. Venc.</th>
                                            <th style='text-align:center;'>Moneda</th>
                                            <th style='text-align:center;'>Disponible</th>
                                            <th style='text-align:center;'>Total</th>
                                            <th style='text-align:center;'> </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px">
                                <div class="form-group">
                                    <div class="col-md-6" style="text-align: left;">
                                        <p><b>Leyenda:</b>&nbsp;&nbsp;
                                            <i class="ion-android-add" style="color:#2E9AFE;"></i> Agregar documento &nbsp;&nbsp;&nbsp;
                                            <i class="fa fa-arrow-down" style="color:#04B404;"></i> Agregar documento y cerra ventana &nbsp;&nbsp;&nbsp;
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
            </div>
        </div><!-- /.modal --> 
        <div id="datosImpresion" style="background-color: #dfd" hidden="true">
        </div>

        <script src="vistas/com/pago/pago.js"></script>
    </body>
</html>