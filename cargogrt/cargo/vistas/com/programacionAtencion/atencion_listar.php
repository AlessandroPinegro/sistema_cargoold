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
        <div class="panel panel-default m-t-20 p-t-0" style="padding-left: 0px;padding-bottom: 1px;padding-right: 0px;">

            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#tabDocumentos" data-toggle="tab" aria-expanded="true" onclick="actualizarTabActivo(1)">
                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                        <span class="hidden-xs">Orden de venta</span>
                    </a>
                </li>
                <li class="">
                    <a href="#tabProgramacionAtencionDetalle" data-toggle="tab" aria-expanded="false" onclick="actualizarTabActivo(2)">
                        <span class="visible-xs"><i class="ion-person-stalker"></i></span>
                        <span class="hidden-xs">Detalle de programación de atenciones</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content" style="margin: 0px;padding: 15px"> 
                <!--PESTAÑA DOCUMENTOS-->
                <div class="tab-pane active" id="tabDocumentos">

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
                                                                    <select name="cboEstadoProgramacion" id="cboEstadoProgramacion" class="select2" style="width: 200px">
                                                                        <option value="0">Todos</option>
                                                                        <option value="1">Programados</option>
                                                                        <option value="2">Por programar</option>
                                                                        <option value="3">Atendido parcialmente</option>
                                                                        <option value="4">Atendido totalmente</option>
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
                                                            <select name="cboMoneda" id="cboMoneda" class="select2" style="width: 200px">
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
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 20px">
                            <table id="datatable" class="table table-small-font table-striped table-hover" >
                                <thead>                                      
                                    <tr>
                                        <th style='text-align:center;'>Acc.</th>
                                        <th style='text-align:center;'>Documento</th>
                                        <th style='text-align:center;'>Cliente</th>
                                        <th style='text-align:center;'>Total</th>
                                        <th style='text-align:center;'>Estado</th>
                                        <th style='text-align:center;'>F.Emisión</th>
                                        <th style='text-align:center;'>F.Creación</th>
                                        <th style='text-align:center;'>Usuario</th>
                                        <!--<th style='text-align:center;'>Estado</th>-->
                                    </tr>
                                </thead>
                            </table>
                            <br>
                            <div style="clear:left">
                                <p id="divLeyenda">
                                    <br>
                                    <b>Leyenda:</b>&nbsp;&nbsp;
                                    <i class='fa fa-calendar-o' style='color:green;'></i> Registrar atención &nbsp;&nbsp;&nbsp;
                                    <i class="ion-android-share" style="color:#E8BA2F;"></i> Ver relación 
                                </p>
                            </div>
                        </div>
                    </div>
                </div> 
                <!--FIN PESTAÑA DOCUMENTOS-->
                <!--PESTAÑA DETALLE PROGRAMACION ATENCION-->
                <div class="tab-pane" id="tabProgramacionAtencionDetalle">
                    <div class="row">
                        <div class="input-group m-t-10">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="padding-left: 0px;">                                    
                                <div id="cabeceraBuscador2" name="cabeceraBuscador2" >
                                    <div class="input-group" id="divBuscador">                                
                                        <span class="input-group-btn" id="spanBuscador2">
                                            <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary col-lg-12 col-md-12 col-sm-12 col-xs-12" href="#">
                                                Buscar<div  style="float: right"><i class="caret"></i></div>
                                            </a>
                                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable2">
                                                <li style="display: none">
                                                    <div id="divTipoDocumento2">
                                                        <div class="form-group col-md-2">
                                                            <label style="color: #141719;">Tipo doc.</label>
                                                        </div>
                                                        <div class="form-group col-md-10">
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <select name="cboDocumentoTipo2" id="cboDocumentoTipo2" class="select2" multiple>
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
                                                            <input type="text" id="txtSerie2" name="txtSerie2" class="form-control" value="" maxlength="45">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" id="txtNumero2" name="txtNumero2" class="form-control" value="" maxlength="45">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group col-md-2">
                                                        <label style="color: #141719;">Persona</label>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboPersona2" id="cboPersona2" class="select2">
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
                                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmision2">
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmision2">
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group col-md-2">
                                                        <label  style="color: #141719;">Fecha programada</label>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaProgramada2">
                                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaProgramada2">
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
                                                                    <select name="cboEstadoPAtencion" id="cboEstadoPAtencion" class="select2" style="width: 200px">
                                                                        <option value="0">Todos</option>
                                                                        <option value="1">Programado</option>
                                                                        <option value="3">Liberado</option>
                                                                        <option value="4">Comprometido</option>
                                                                        <option value="5">Atendido parcialmente</option>
                                                                        <option value="6">Atendido totalmente</option>
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
                                                            <select name="cboMoneda2" id="cboMoneda2" class="select2" style="width: 200px">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="float: right">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores2()" class="btn btn-danger"> <i class="fa fa-close"></i>Cancelar</button>
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarPorCriterios2()" class="btn btn-purple"> <i class="fa fa-search"></i>Buscar</button>                                        
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

                    <div style="padding-top: 20px">
                        <table id="datatableProgramacionAtencionDetalle" class="table table-small-font table-striped table-hover"  style="width: 100%">
                            <thead>                                      
                                <tr>
                                    <th style='text-align:center;'>Acc.</th>
                                    <th style='text-align:center;'>Documento</th>
                                    <th style='text-align:center;'>Cliente</th>
                                    <!--<th style='text-align:center;'>Producto</th>-->
                                    <!--<th style='text-align:center;'>Cantidad</th>-->
                                    <!--<th style='text-align:center;'>Organizador</th>-->
                                    <th style='text-align:center;'>F.Programada</th>
                                    <th style='text-align:center;'>F.Creación</th>
                                    <th style='text-align:center;'>Estado</th>
                                    <th style='text-align:center;'>Usuario</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <div style="clear:left">
                            <p id="divLeyendaPP">
                                <br>
                                <b>Leyenda:</b>&nbsp;&nbsp;
                                <i class='fa fa-calendar-o' style='color:green;'></i> Editar atención &nbsp;&nbsp;&nbsp;
                                <i class='fa fa-server' style='color:#1ca8dd;'></i> Programado &nbsp;&nbsp;&nbsp;
                                <i class='fa fa-unlock' style='color:green;' ></i> Liberado &nbsp;&nbsp;&nbsp;
                                <i class='fa fa-lock' style='color:red;'></i> Comprometido &nbsp;&nbsp;&nbsp;
                                <i class='fa fa-th-large' style='color:orange;'></i> Atendido parcialmente &nbsp;&nbsp;&nbsp;
                                <i class='fa fa-th-large' style='color:green;' ></i> Atendido totalmente &nbsp;&nbsp;&nbsp;
                                <!--<i class='ion-close-circled' style='color:red;'></i> Eliminado &nbsp;&nbsp;&nbsp;-->
                            </p>
                        </div>
                    </div>
                </div>
                <!--FIN PESTAÑA DETALLE PROGRAMACION ATENCION-->
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

                </div>
            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
            </div> 
        </div> 
    </div>
</div>

<script src="vistas/com/programacionAtencion/atencion_listar.js"></script>