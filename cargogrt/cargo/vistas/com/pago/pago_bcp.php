<style type="text/css">    
    .colorPP {
        color: #0000ff;
    }                                                                                                                                                                                               
</style> 
<!--<div class="wraper container-fluid">-->
<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>

<div class="panel panel-default"> 
    <div class="row">
        <div class="input-group m-t-10">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="padding-left: 0px;">                                    
                <div id="cabeceraBuscador" name="cabeceraBuscador" >
                    <div class="input-group" id="divBuscador">                                
                        <span class="input-group-btn" id="spanBuscador">
                            <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary col-lg-12 col-md-12 col-sm-12 col-xs-12" href="#">
                                Buscar<div  style="float: right"><i class="caret"></i></div>
                            </a>
                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                <li>
                                    <div id="divTipoDocumento">
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Tipo doc. pagado</label>
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
                                        <label style="color: #141719;">S/N pagado</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txtSerie" name="txtSerie" class="form-control" value="" maxlength="45">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txtNumero" name="txtNumero" class="form-control" value="" maxlength="45">
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-group col-md-2">
                                        <label style="color: #141719;">Cod. Transferencia</label>
                                    </div>
                                    <div class="form-group col-md-10">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" id="txtTransferencia" name="txtTransferencia" class="form-control" value="" maxlength="45">
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
<!--                                <li>
                                    <div class="form-group col-md-2">
                                        <label  style="color: #141719;">Fecha emisión</label>
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
                                </li>-->
                                <li>
                                    <div class="form-group col-md-2">
                                        <label style="color: #141719;">Estado</label>
                                    </div>
                                    <div class="form-group col-md-10">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <select name="cboEstado" id="cboEstado" class="select2" style="width: 200px">
                                                        <option value="0">Todos</option>
                                                        <option value="1">Proceso pendiente</option>
                                                        <option value="3">Proceso completado</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <div class="form-group col-md-2">
                                        <label style="color: #141719;">Moneda</label>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboMoneda" id="cboMoneda" class="select2">
                                            </select>
                                        </div>
                                    </div>
                                    <div style="float: right">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"> <i class="fa fa-close"></i>Cancelar</button>
                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarPorCriterios()" class="btn btn-purple"> <i class="fa fa-search"></i>Buscar</button>                                        
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </span>  
                    </div>                           
                </div>
            </div>

            <div class="input-group-btn" style="padding-left: 0px;padding-right: 0px;">
                <div class="btn-toolbar" role="toolbar"  style="float: right" >
                    <div class="input-group-btn">
                        <a type="button" class="btn btn-success" onclick="actualizarBusqueda()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3" hidden="hidden">
            <div class="input-group m-t-10">
                <div class="panel panel-default p-0  m-t-20">
                    <div class="panel-body p-0">
                        <div class="list-group no-border" id="divDocumentoTipos">

                        </div>
                    </div>
                </div>

                <div class="panel panel-default p-0  m-t-20">
                    <div class="panel-body p-0">
                        <div class="list-group no-border mail-list" id="divPersonasMayorMovimientos">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 20px">
            <table id="datatable" class="table table-small-font table-striped table-hover"  style="width: 1305px;" >
                <thead>                                      
                    <tr>
                        <th style='text-align:center;'>Acc.</th>
                        <th style='text-align:center;'>F.Creación</th>
                        <th style='text-align:center;'>Asunto</th>
                        <th style='text-align:center;'>Persona</th>
                        <th style='text-align:center;'>F. Pago</th>
                        <th style='text-align:center;'>Importe</th>
                        <th style='text-align:center;'>Nro.Op.</th>
                        <th style='text-align:center;'>Transferencia</th>
                        <th style='text-align:center;'>TD. Pagado</th>
                        <th style='text-align:center;'>S/N Doc.Pag.</th>
                        <th style='text-align:center;'>Ult.Observaciones</th>
                        <th style='text-align:center;'>Estado</th>
                    </tr>
                </thead>
            </table>
            <br>
            <div style="clear:left">
                <p id="divLeyenda">
                    <br>
                    <b>Leyenda:</b>&nbsp;&nbsp;
                    <i class='fa  ion-refresh' style='color:blue;'></i>Volver a intentar pagar  &nbsp;&nbsp;&nbsp;
                </p>
            </div>
        </div>
    </div>
</div> 
<!--FIN PESTAÑA DOCUMENTOS-->
<!--</div>-->
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


<div id="modalNumeroOperacion"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalNumeroOperacion" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog">            
        <div class="modal-content">               
            <div class="modal-header">                      
                <h4 class="modal-title">Edición de número de operación</h4>         
            </div>                     
            <div class="modal-body">     
                <div class="row"> 
                    <div class="col-md-12"> 
                        <div class="form-group"> 
                            <label for="field-3" class="control-label">Número de operación</label> 
                            <input type="text" class="form-control" id="txtNumeroOperacion" name="txtNumeroOperacion" placeholder="Número de operación"> 
                        </div> 
                    </div> 
                </div>
            </div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> 
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="actualizarNumeroOperacion()">Guardar</button> 
            </div>
        </div>         
    </div>     
</div>

<script src="vistas/com/pago/pago_bcp.js"></script>