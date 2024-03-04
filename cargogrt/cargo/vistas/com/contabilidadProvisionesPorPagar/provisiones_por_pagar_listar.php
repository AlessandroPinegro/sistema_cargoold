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
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 ">
                <button id="btnNuevo" class="btn btn-info btn-block" onclick="verProvision()">
                    <i class=" fa fa-plus-square-o"></i> Nuevo                
                </button>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 ">                                    
                <div id="cabeceraBuscador" name="cabeceraBuscador" >
                    <div class="input-group" id="divBuscador">                                
                        <span class="input-group-btn" id="spanBuscador">
                            <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary col-lg-12 col-md-12 col-sm-12 col-xs-12" href="#">
                                Buscar<div  style="float: right"><i class="caret"></i></div>
                            </a>
                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                <li style="display: none">
                                    <div id="divTipoDocumento">
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Tipo doc.</label>
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
                                        <label style="color: #141719;">Serie/Número</label>
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
                                </li>
                                <li>
                                    <div class="form-group col-md-2">
                                        <label style="color: #141719;">Estado</label>
                                    </div>
                                    <div class="form-group col-md-10">
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <select name="cboEstadoPromacion" id="cboEstadoPromacion" class="select2" style="width: 200px">
                                                        <option value="0">Todos</option>
                                                        <option value="1">Registrado</option>
                                                        <option value="2">Anulado</option>
                                                        <!--<option value="3">Pagado parcialmente</option>-->
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
                                                <option value="2">Soles</option>
                                                <option value="2">Dolares</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="float: right">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="" class="btn btn-danger"> <i class="fa fa-close"></i>Cancelar</button>
                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="" class="btn btn-purple"> <i class="fa fa-search"></i>Buscar</button>                                        
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </span>  
                    </div>                           
                </div>
            </div>

            <div class="input-group-btn" style="padding-left: 0px;padding-right: 15px;">
                <div class="btn-toolbar" role="toolbar"  style="float: right" >
                    <div class="input-group-btn">
                        <a type="button" class="btn btn-success" onclick="actualizarBusqueda()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="padding-top: 20px">
            <table id="datatable" class="table table-small-font table-striped table-hover" >
                <thead>                                      
                    <tr>
                        <th style='text-align:center;'>Periodo</th>
                        <th style='text-align:center;'>Número</th>
                        <th style='text-align:center;'>Doc. Tipo</th>
                        <th style='text-align:center;'>S/N</th>
                        <th style='text-align:center;'>F.Emisión</th>
                        <th style='text-align:center;'>Proveedor</th>
                        <th style='text-align:center;'>M</th>
                        <th style='text-align:center;'>Total</th>
                        <th style='text-align:center;'>F.Creación</th>
                        <th style='text-align:center;'>Estado</th>
                        <th style='text-align:center;'>Usuario</th>
                        <th style='text-align:center;'>Acc.</th>
                    </tr>                             
                    <tr>
                        <td style='text-align:left;'>2017|01</td>
                        <td style='text-align:left;'>0001</td>
                        <td style='text-align:left;'>Factura</td>
                        <td style='text-align:left;'>002|04231</td>
                        <td style='text-align:left;'>06/07/2017</td>
                        <td style='text-align:left;'>ABC MULTISERVICIOS GENERALES E.I.R.L. | 20600759141</td>
                        <td style='text-align:left;'>S/.</td>
                        <td style='text-align:right;'>4,187.00</td>
                        <td style='text-align:left;'>06/07/2017</td>
                        <td style='text-align:left;'>Registrado</td>
                        <td style='text-align:left;'>imagina</td>
                        <td style='text-align:left;'>
                            <a onclick="verProvision()" title="Visualizar">
                                <b>
                                    <i class="fa fa-eye" style="color:#1ca8dd"></i>
                                </b>
                            </a>&nbsp;
                            <a title="Anular">
                                <b>
                                    <i class="fa fa-ban" style="color:#cb2a2a"></i>
                                </b>
                            </a>&nbsp;
                            <a onclick="abrirModal('modalDocumentoRelacionado')" title="Relación">
                                <b>
                                    <i class="ion-android-share" style="color:#E8BA2F"></i>
                                </b>
                            </a>
                        </td>
                    </tr>
                </thead>
            </table>
            <br>
            <div style="clear:left">
                <div style="clear:left">
                    <p id="divLeyenda"><br>
                        <b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-eye" style="color:#1ca8dd;"></i>&nbsp;Visualizar &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-ban" style="color:#cb2a2a;"></i>&nbsp;Anular &nbsp;&nbsp;&nbsp;
                        <i class="ion-android-share" style="color:#E8BA2F;"></i>&nbsp;Relación &nbsp;&nbsp;&nbsp;
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!--MODAL DE DOCUMENTOS RELACIONADOS-->
<div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title">Documentos relacionados</h4> 
            </div> 
            <div class="modal-body"> 
                <div id="linkDocumentoRelacionado">
                    <a onclick="" style="color:#0000FF">[Orden de compra: 0001 - 000029]</a>
                </div>
            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
            </div> 
        </div> 
    </div>
</div>

<script src="vistas/com/contabilidadProvisionesPorPagar/provisiones_por_pagar_listar.js"></script>