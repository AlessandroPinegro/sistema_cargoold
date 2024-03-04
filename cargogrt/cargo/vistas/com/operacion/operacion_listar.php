<div class="page-title" style="padding-bottom: 0px;">
    <!--<h3 class="title" id="titulo"></h3>-->
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12" >
            <div class="col-lg-8">
                <h3 class="title">Ingreso / Egresos</h3>
            </div>
            <div class="col-lg-2">
                <select name="cboAgencia" id="cboAgencia" class="select2 " onchange="onChangeCboAgencia(this.value)"></select>
            </div>
            <div class="col-lg-2">
                <select name="cboCaja" id="cboCaja" class="select2 " onchange="onChangeCboCaja(this.value)"></select>
            </div>
           
        </div>
    </div>


</div>
<div class="wraper container-fluid">
    <div class="row">
        <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />
        <!--<div class="panel panel-default" style="padding-bottom:  5px;">-->
        <div id="datosImpresion" style="background-color: #dfd" hidden="true">
        </div>
        <div class="col-md-2">
            <!--<div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">-->
            <button class="btn btn-info btn-block" onclick="nuevoForm()" >
                <i class=" fa fa-plus-square-o"></i> Nuevo                
            </button>
            <!--</div>-->

            <div class="panel panel-default p-0  m-t-20">
                <div class="panel-body p-0">
                    <div class="list-group no-border" id="divDocumentoTipos">

                    </div>
                </div>
            </div>
     
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">                

                    <div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">
                        <div id="cabeceraBuscador" name="cabeceraBuscador" >

                            <!--<div class="form-group col-lg-10 col-md-10 col-sm-12 col-xs-12">-->
                            <div class="input-group" id="divBuscador">                                
                                <span class="input-group-btn">
                                    <!--<button type="button" class="btn btn-effect-ripple btn-primary"><i class="caret"></i></button>-->
                                    <!--<li class="btn btn-effect-ripple btn-primary">-->
                                    <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                        <i class="caret"></i>
                                    </a>
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                        <li>
                                            <div id="divTipoDocumento">
                                                <div class="form-group col-md-2">
                                                    <label style="color: #141719;">Tipo de documento</label>
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
                                                    <select name="cboPersona" id="cboPersona" class="select2">
                                                    </select>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group col-md-2">
                                                <label  style="color: #141719;">Fecha</label>
                                            </div>
                                            <div class="form-group col-md-10">
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmision">
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmision">
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group col-md-3" style="float: right">
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"> Cancelar</button>
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDesplegable()" class="btn btn-purple"> Buscar</button>                                        
                                            </div>

                                        </li>
                                    </ul>
                                    <!--</li>-->
                                </span>
                                <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusqueda()">                                
                                <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable2">

                                </ul>

                            </div>
                            <!--</div>-->                           
                        </div>
                    </div>
                    <div class="col-md-2" style="padding-left: 0px;padding-right: 0px;">
                        <div class="btn-toolbar" role="toolbar"  style="float: right" >
                            <div class="btn-group">
                                <a type="button" class="btn btn-success" onclick="actualizarBusqueda()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                            </div>
                            <div class="btn-group">
                                <a type="button" class="btn btn-success" onclick="actualizarBusquedaExcel()" title="Exportar excel"><i class="fa fa-file-excel-o"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default m-t-20">
                <div class="panel-body" style="padding-top: 0px;">
                    <div class="row">
                        <table id="datatable" class="table table-striped table-hover"  style="width: 1500px">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'></th>
                                    <th style='text-align:center;'>Persona</th>
                                    <th style='text-align:center;'>S/N</th>
                                    <th style='text-align:center;'>M</th>
                                    <th style='text-align:center;'>Total</th> 
                                    <th style='text-align:center;'>F. Emisión</th>
                                    <th style='text-align:center;'>F. Venc.</th>
                                    <th style='text-align:center;'>Descripción</th>
                                    <th style='text-align:center;'>F. Creacion</th>
                                    <th style='text-align:center;'>Usuario</th>
                                    <th style='text-align:center;'>Estado</th>
                                    <th style='text-align:center;'>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <br>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class='fa fa-print' style='color:green;'></i> Imprimir &nbsp;&nbsp;&nbsp;
                        <i class='fa fa-ban' style='color:#cb2a2a;'></i> Anular &nbsp;&nbsp;&nbsp;
                        <i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;
                        <i class='ion-android-share' style="color:#E8BA2F;"></i> Ver Relación &nbsp;&nbsp;&nbsp;
                        <i class='fa fa-file-excel-o' ></i> Exportar Excel
                    </p>
                </div>
            </div>
 
        </div>    
        <!--</div>-->
    </div>
</div>

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
                                    <textarea type="text" id="txtComentario" name="txtComentarioCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12">
                                <br>
                                <label>DESCRIPCION </label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea type="text" id="txtDescripcion" name="txtDescripcionCopia" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly="true"></textarea>
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
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">                       
                        <!--<div class="alert alert-success fade in" id="alertEmail">-->


                        <div class="input-group m-t-10" id="alertEmail">                        
                            <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="nleon@imaginatecperu.com" placeholder="email1@dominio.com;email2@dominio.com">
                            <span class="input-group-btn">                                
                                <button type="button" class="btn btn-success" onclick="enviarCorreoDetalleDocumento()" id="idDescripcionBoton"><i class="ion-email" ></i> Enviar correo</button>
                            </span>
                        </div>


                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"> 
                        <div class="checkbox pull-left" style="margin-top: 15px;">
                            <label class="cr-styled">
                                <input onclick="getUserEmailByUserId()" type="checkbox" name="checkIncluirSelf" id="checkIncluirSelf">
                                <i class="fa"></i> Incluir mi e-mail
                            </label>
                        </div>
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

<div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title">Documentos relacionados</h4> 
            </div> 
            <div class="modal-body"> 
                <div id="linkDocumentoRelacionado">

                </div>
            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
            </div> 
        </div> 
    </div>
</div>

<!--<script src="vistas/com/operacion/imprimir.js"></script>-->
<script src="vistas/com/movimiento/imprimir.js"></script>
<script src="vistas/com/operacion/operacion_listar.js"></script>