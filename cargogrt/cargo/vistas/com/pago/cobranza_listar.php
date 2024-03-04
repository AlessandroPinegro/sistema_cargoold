<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<div class="row">
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <div id="datosImpresion" style="background-color: #dfd" hidden="true">
        </div>
        <div class="row">
            
                    <div class="col-md-12">                    
                        <div class="form-group input-group" id="divBuscador">
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
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarPago(1)" class="btn btn-purple"> <i class="fa fa-search"></i> Buscar</button>                                        
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                    </li>
                                </ul>
                            </span>

                            <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusquedaDocumentoPagoListar()">                                
                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegable2">
                            </ul>
                            </input>
                            <span class="input-group-btn">
                                <a type="button" class="btn btn-success" onclick="buscarPago()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                            </span>
                        </div>
                    </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!--<div class="table-responsive">-->
                <table id="datatableListaPagos" class="table table-striped table-bordered">
                    <thead>
                        <tr>                               
                            <th style='text-align:center;'>Código</th>                            
                            <th style='text-align:center;'>Fecha emisión</th>
                            <th style='text-align:center;'>Tipo documento</th>
                            <th style='text-align:center;'>Persona</th>
                            <th style='text-align:center;'>Número</th> 
                            <!--<th style='text-align:center;'>F. Venc.</th>-->
                            <th style='text-align:center;'>Disponible</th>
                            <th style='text-align:center;'>Total</th>
                            <th style='text-align:center;'>Moneda</th>
                            <th style='text-align:center;'>Estado</th>
                            <th style='text-align:center;'>Acciones</th>
                        </tr>
                    </thead>
                </table>
            <!--</div>-->
            </div>
        </div>
    </div>
    <div style="clear:left">
        <p><b>Leyenda:</b>&nbsp;&nbsp;
            <i class="fa fa-print" style="color:#088A08;"></i> Imprimir &nbsp;&nbsp;&nbsp;
            <i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;
            <i class='fa fa-ban' style='color:#DF7401;'></i> Anular &nbsp;&nbsp;&nbsp;
            <i class='fa fa-trash-o' style='color:#cb2a2a;'></i> Eliminar 
        </p>
    </div>
</div>


<!--modal detalle-->
 <div id="modalDetalleDocumentoPago"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:80%;"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title"></h4> 
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table id="datatableDocumentoPago" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Documento</th>
                                    <th style='text-align:center;'>F. Emisión</th>
                                    <th style='text-align:center;'>F. Vencimiento</th>
                                    <th style='text-align:center;'>S|N</th>
                                    <th style='text-align:center;'>F. Pago</th>
                                    <th style='text-align:center;'>Total</th>
                                    <th style='text-align:center;'>Moneda</th>
                                    <th style='text-align:center;'>Importe pago</th>
                                    <th style='text-align:center;'>Moneda pago</th>
                                </tr>
                            </thead>
                        </table>
                    </div> 
                </div>
            </div> 
            <div class="modal-footer">
                <!--<label>Correo *</label>-->
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">                       
                        <!--<div class="alert alert-success fade in" id="alertEmail">-->


                        <div class="input-group m-t-10" id="alertEmail">                        
                            <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="nleon@imaginatecperu.com" placeholder="email1@dominio.com;email2@dominio.com">
                            <span class="input-group-btn">                                
                                <button type="button" class="btn btn-success" onclick="enviarCorreoDocumentoPago()" id="idDescripcionBoton"><i class="ion-email" ></i> Enviar correo</button>
                            </span>
                        </div>


                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"> 
                        <div class="checkbox pull-left" style="margin-top: 15px;">
                            <label class="cr-styled">
                                <input onclick="getUserEmailByUserId()" type="checkbox" name="checkIncluirSelf" id="checkIncluirSelf">
                                <i class="fa"></i> Incluir mi e-mail
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
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
<script src="vistas/com/pago/cobranza_listar.js"></script>