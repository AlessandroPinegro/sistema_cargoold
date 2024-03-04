
<div class="wraper container-fluid"> 
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12" > 
            <h3 id="titulo" class="title"></h3> 
        </div>
    </div>


    <div class="row">

        <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />
        <input type="hidden" id="documentoGenerarId" value="<?php echo $_GET['documentoGenerarId']; ?>" />
        <input type="hidden" id="tipoGenerarDocumento" value="<?php echo $_GET['tipoGenerarDocumento']; ?>" />

        <div id="datosImpresion" style="background-color: #dfd" hidden="true"></div>

        <div id="divAgregarDocumento" class="col-md-2" hidden="">
            <button id="btnNuevo" class="btn btn-info btn-block" onclick="nuevoForm()" >
                <i class=" fa fa-plus-square-o"></i> Nuevo                
            </button>  
        </div>
        <div id="divFormularioBusqueda" class="col-md-10">                   
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
                                <label>Nro. Liquidación</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" placeholder="Ingrese comprobante" id="txtNumeroLiquidacion" name="txtNumeroLiquidacion"> 
                                </div>
                            </div> 
                            <div class="form-group col-md-2">
                                <label>Fecha emisión liq.</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaLiquidacionInicio">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>   
                            </div> 
                            <div class="form-group col-md-2">
                                <label></label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaLiquidacionFin">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>   
                            </div>

                            <div class="form-group col-md-4">
                                <label>Estado</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select  name="cboEstado" id="cboEstado" class="select2" placeholder="Seleccione estado" multiple=""></select>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Nro. Comprobante</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" placeholder="Ingrese comprobante" id="txtNumeroComprobante" name="txtNumeroComprobante"> 
                                </div>
                            </div> 
                            <div class="form-group col-md-2">
                                <label>Fecha emisión comp.</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaComprobanteInicio">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>   
                            </div> 
                            <div class="form-group col-md-2">
                                <label></label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaComprobanteFin">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>   
                            </div> 
                            <div class="form-group col-md-4">
                                <label>Usuario de registro</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select  name="cboUsuario" id="cboUsuario" class="select2" placeholder="Seleccione usuario"></select>
                                </div>
                            </div>  
                        </div>
                        <div class="row">

                            <div id="divBusquedaCboCliente" class="form-group col-md-4">
                                <label>Cliente</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select  name="cboCliente" id="cboCliente" class="select2" placeholder="Seleccione cliente"></select>
                                </div>
                            </div> 
                            <div id="divBusquedaCboTipoDocumento" class="form-group col-md-4">
                                <label>Agencia</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboAgencia" id="cboAgencia" class="select2 " onchange="onChangeCboAgencia(this.value)"></select>                                
                                </div>
                            </div> 

                            <div class="form-group col-md-4">
                                <label>Caja</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboCaja" id="cboCaja" class="select2 " onchange="onChangeCboCaja(this.value)"></select>        
                                </div>
                            </div>  
                        </div>

                        <div class="row">                                      
                            <div class="modal-footer"> 
                                <button type="button"  onclick="generarExcel();" class="btn btn-primary"><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp; &nbsp;&nbsp;  Exportar excel</button>&nbsp;&nbsp;  
                                <button type="button" onclick="limpiarFiltros();"   class="btn btn-success"><i class="fa fa-eraser"></i>&nbsp; Limpiar filtros</button>&nbsp; 
                                <button type="button" href="#bg-info" onclick="buscarPedidos();" value="enviar" class="btn btn-info"><i class="ion-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;Buscar</button>&nbsp; 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="panel panel-default m-t-20 p-t-0">

                <table id="datatable" class="table table-small-font table-striped table-hover"   width="100%">
                    <thead id="theadListado">
                        <tr>
                            <th style='text-align:center;' width="10%">Liquidación</th>
                            <th style='text-align:center;' width="5%">F.Liquidación</th>
                            <th style='text-align:center;' width="19%">Cliente</th>
                            <!--<th style='text-align:center;' width="8%">Destino</th>-->
                            <th style='text-align:center;' width="8%">Total</th>
                            <th style='text-align:center;' width="10%">T. Comp.</th>
                            <th style='text-align:center;' width="10%">F.Emisión</th>
                            <th style='text-align:center;' width="10%">Nro.Comp.</th>
                            <th style='text-align:center;' width="10%">Usuario</th>
                            <th style='text-align:center;' width="5%">Estado</th>
                            <th style='text-align:center;' width="5%">Acciones</th>
                        </tr>
                    </thead>
                </table>
                <br>
                <div style="clear:left">
                    <p id="divLeyenda">
                        <br><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class='ion-android-share' style="color:#E8BA2F;"></i> Ver documentos relacionados &nbsp;&nbsp;&nbsp;                        
                        <i class='fa fa-ban' style='color:#cb2a2a;'></i> Anular pedido&nbsp;&nbsp;&nbsp;
                        <i class='fa fa-print' style='color:green;'></i> Imprimir pedido &nbsp;&nbsp;&nbsp; 
                        <i class='fa fa-print' style='color:blue;'></i> Imprimir Documento ticket &nbsp;&nbsp;&nbsp; 
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!--modal para el detalle del movimiento-->
<div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" style="display: none; overflow: scroll;">
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
            </div> 

            <div class="modal-footer" id="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10" id="espacioTab" >
                    </div>

                    <!-- <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10" id="envioCorreo" hidden>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <div class="input-group m-t-10">
                                <span class="input-group-btn">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary" disabled>Envio de correos: </button> 
                                    </div>
                                </span>                        
                                <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="" placeholder="email1@dominio.com;email2@dominio.com">
                                <span class="input-group-btn">                                
                                    <button type="button" class="btn btn-success" onclick="enviarCorreoComprobante()"><i class="ion-email" ></i> Enviar</button>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="checkbox pull-left" style="margin-top: 15px;">
                                <label class="cr-styled">
                                    <input onclick="getUserEmailByUserId()" type="checkbox" name="checkIncluirSelf" id="checkIncluirSelf">
                                    <i class="fa"></i> Incluir mi e-mail
                                </label>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">                       
                        <div class="input-group m-t-10" style="float: right">
                            <a class="btn btn-purple" id="btnRetornar" hidden=""><i class="ion-arrow-left-a" ></i>&nbsp;&nbsp; Regresar</a>
                            <a class="btn btn-success" id="btnGenerarPedidoDevolucionCargo" hidden=""><i class="ion-checkmark-circled" ></i>&nbsp;&nbsp; Generar devolución de cargo</a>
                            <a class="btn btn-info" id="btnImprimirModal"><i class="fa fa-print"></i> Imprimir</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>


<div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--> 
                <h4 class="modal-title">Documentos relacionados
                    <!--<a onclick="prepararModalDocumentoACopiar()" style="float: right;color:#55acee" title="Abrir bandeja de documentos a relacionar"><i class="fa fa-plus-circle">&nbsp;&nbsp;&nbsp;</i></a>-->
                </h4> 
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
                    <div id="divContenedorAdjunto" class="form-group col-md-4">
                        <!--<h4>-->
                        <div class="fileUpload btn btn-purple" style="border-radius: 0px;"
                             id="idPopover" 
                             title=""  
                             data-toggle="popover" 
                             data-placement="top" 
                             data-content="">
                            <i class="ion-upload" style="font-size: 16px;"></i>
                            Cargar documento
                            <input name="archivoAdjunto" id="archivoAdjunto"  type="file" accept="*" class="upload" >
                            <input type="hidden" id="dataArchivo" value="" />
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
                <button type="button" class="btn btn-success m-b-5" id="idGuardarBienUnico" style="border-radius: 0px; margin-bottom: 0px" onclick="guardarDocumentosAdjuntos()" ><i class="fa fa-save"></i>&ensp;Guardar</button>
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</button> 
            </div> 
        </div> 
    </div>
</div>
<!-- fin modal visualizar archivos-->

<div id="modalReporteAtenciones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalReporteAtenciones" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalReporteAtencionesTitulo">Atenciones: </h4>
            </div>
            <div class="modal-body">

                <table id="tableReporteAtenciones" style="width: 100%;" >
                    <tr class="gang-name-1">
                        <td colspan="2">Solicitud 001 - 0000001</td>
                    </tr>
                    <tr class="members blips">
                        <td class="atencion-td">Documento 1 </td>
                        <td class="atencion-td">
                    <tr class="gang-name-2">
                        <td colspan="1">Sub Cotizacion 001 - 0000001</td>
                    </tr>
                    <tr class="members blips">
                        <td class="atencion-td">Sub Documento 1 </td>
                    </tr>
                    </td>

                    </tr>
<!--                    <tr class="members blips">-->
<!--                        <td class="atencion-td">Documento 3</td>-->
<!--                        <td class="atencion-td">Documento 4</td>-->
                    <!--                    </tr>                    -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="cerrarModalReporteAtenciones()"><i class="fa fa-remove"></i><span> Cerrar</span></button>

                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="modalAsignarCodigoUnico"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:80%;"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title" id="tituloModalAsignarCodigoUnico">Asignar códigos únicos</h4> 
            </div> 
            <div class="modal-body" style="padding-bottom: 5px;"> 
                <div id="divAgregarBU">
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Productos únicos disponibles</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <select name="cboBienUnico" id="cboBienUnico" class="select2" onchange="onChangeComboBienUnico()"></select>
                            </div>
                        </div>                    
                        <!--                </div>
                        
                                        <div class="row">-->
                        <div class="form-group col-md-4">                        
                            <label class="cr-styled" style="text-align: left;">
                                <input type="checkbox" id="chkHasta" name="chkHasta" onclick="onClickCheckHasta()">
                                <i class="fa"></i> 
                                <b>Hasta </b>
                            </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                            
                                <div class="col-md-9" style="padding-left: 0px;padding-right: 0px;padding-top: 6px;">
                                    <input type="text" id="txtBienUnicoDescripcion" name="txtBienUnicoDescripcion" class="form-control" value="" maxlength="300" readonly="true">
                                </div>
                                <div class="col-md-3" style="padding-left: 0px;padding-right: 0px;padding-top: 6px;">
                                    <input type="number" id="txtBienUnicoNumero" name="txtBienUnicoNumero" class="form-control" value="" maxlength="7">
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12" style="padding-top: 12px; padding-bottom: 12px;">
                            <div class="col-md-5">&nbsp;</div>
                            <div class="col-md-2">
                                <button type="button" name="btnGuardar" id="btnGuardar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"  onclick="agregarBienUnico()"><i class="fa fa-plus-square-o"></i>&nbsp;Agregar</button>
                            </div>
                            <div class="col-md-5">&nbsp;</div>
                        </div>
                    </div>
                </div>

                <!--<div class="panel panel-body">-->
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="table">
                            <!--<table id="dataTableBienUnicoDetalle" class="table table-striped table-bordered">-->                                    
                            <div id="dataList">
<!--                                    <thead>
                                <tr>
                                    <th style="text-align:center">N°</th>
                                    <th style="text-align:center">Prod. Único</th>
                                    <th style="text-align:center">Producto</th>
                                    <th style="text-align:center">Estado</th>
                                    <th style="text-align:center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>-->
                            </div>
                            <!--</table>-->
                        </div>
                    </div>
                </div>
                <!--</div>-->

                <div style="clear:left" id="divLeyendaBU">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar detalle&nbsp;&nbsp;&nbsp;
                        <!--<i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar detalle&nbsp;&nbsp;&nbsp;-->
                    </p>
                </div>

            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-info m-b-5" id="idGuardarBienUnico" style="border-radius: 0px; margin-bottom: 0px" onclick="guardarBienUnicoDetalle(1)" ><i class="fa fa-save"></i>&ensp;Guardar</button> 
                <button type="button" class="btn btn-success m-b-5" id="idEnviarBienUnico" style="border-radius: 0px;"  onclick="enviarBienUnicoDetalle()"  ><i class="fa fa-send-o"></i>&ensp;Finalizar</button> 
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</button> 
            </div> 
        </div> 
    </div>
</div>

<form target="_blank" action="script/almacen/qrDocumento.php" method="post" id="formDocumentoQR" name="formDocumentoQR">                                
    <input type="hidden" name="documentoIdHidden" id="documentoIdHidden" value=""/>
</form>

<div id="modalDocumentoRelacion" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">      
        <div class="modal-content">          
            <div class="modal-body">          
                <div class="row">                       
                    <div class="col-lg-12">                   
                        <div id="divBuscadorCopia">                        
                            <div class="form-group input-group">                        
                                <span class="input-group-btn">                             
                                    <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">      
                                        <i class="caret"></i>                        
                                    </a>                                      
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegableCopia">      
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
                                <input type="text" id="txtBuscarCopia" name="txtBuscarCopia" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarDocumentoRelacion()">
                                <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegableCopia2">      
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
                    <table id="dtDocumentoRelacion" class="" style="width: 100%">                     
                        <thead>                        
                            <tr>                            
                                <th style='text-align:center;'>F. creación</th>                
                                <th style='text-align:center;'>F. emisión</th>             
                                <th style='text-align:center;'>Tipo documento</th>           
                                <th style='text-align:center;'>Persona</th>                    
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
                            <!--<i class="fa fa-download" style="color:#04B404;"></i> Agregar documento a copiar&nbsp;&nbsp;-->       
                            <i class="fa fa-arrow-down" style="color:#1ca8dd;"></i> Agregar documento a relacionar             
                        </p>                    
                    </div>                  
                    <div class="col-md-6">          
                        <button type="button" class="btn btn-danger" onclick="cerrarModalCopia()"><i class="fa fa-close"></i> Cerrar</button>       
                    </div>             
                </div>             
            </div>             
        </div>           
    </div>      
</div>      

<!--inicio modal anulacion-->     
<div id="modalAnulacion"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">       
    <div class="modal-dialog">            
        <div class="modal-content">               
            <div class="modal-header">                      
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>         
                <h4 class="modal-title text-dark text-uppercase" id="tituloModalAnulacion"></h4> 
            </div>                     
            <div class="modal-body">                 
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Motivo de anulación *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea type="text" id="txtMotivoAnulacion" name="txtMotivoAnulacion" class="form-control" value="" maxlength="500"></textarea>
                        </div>
                    </div>
                </div>
            </div>                   
            <div class="modal-footer">   
                <div class="form-group col-md-12">
                    <a href="#" class="btn btn-danger w-sm m-b-5" id="id" style="border-radius: 0px;margin-bottom: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                    <a type="button" onclick="anularDocumentoMensaje()"  class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Anular</a>&nbsp;&nbsp;
                </div>
            </div>         
        </div>         
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
                <div class="table">
                    <table id="datatableDocumentoPago" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style='text-align:center;'>Código</th>
                                <th style='text-align:center;'>Fecha de pago</th>
                                <th style='text-align:center;'>Documento tipo/Efectivo</th>
                                <th style='text-align:center;'>Número</th>
                                <th style='text-align:center;'>Moneda</th>
                                <th style='text-align:center;'>Monto</th>
                                <!--<th style='text-align:center;'>Acc.</th>-->
                            </tr>
                        </thead>
                    </table>
                </div> 
            </div>            

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button> 
            </div>
        </div> 
    </div>
</div><!-- /.modal --> 

<script src="vistas/com/movimiento/movimiento_listar_liquidacion.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>