<!DOCTYPE html>
<html lang="es">
    <head>
        
    </head>
    <body>        
        <div class="row">
            <input type="hidden" id="hddIsDependiente" value="1">
            <div class="col-lg-4">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="portlet-title text-dark text-uppercase">
                                    <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2"></select>
                                </h3>
                            </div>
                            <div class="col-md-6">
                                <div class="portlet-widgets">

                                    <span class="divider"></span>
                                    <a onclick="cargarBuscadorDocumentoACopiar()" id="cargarBuscadorDocumentoACopiar">
                                        <i class="fa fa-files-o" tooltip-btndata-toggle='tooltip' title="Copiar documento"></i>
                                    </a>
                                    <span class="divider"></span>
                                    <a onclick="loaderComboPersona()">
                                        <i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i>
                                    </a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion1" href="#portlet1"><i class="ion-minus-round" tooltip-btndata-toggle='tooltip' title="Minimizar"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div id="portlet1" class="panel-collapse collapse in">
                        <div class="portlet-body">
                            <div id="contenedorDocumentoTipo" style="min-height: 75px;height: auto;">
                                <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/unidad/unidad_listar.php')" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                                            <button type="button" onclick="save('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </form>
                                <div id="DivDocumentoACopiar" style="min-height: 50px;height: auto;" hidden="true">
                                    <div id="contenedorLinkDocumentoACopiar" class="form-group">
                                        <div class="col-md-12" style="text-align: left;">
                                            <div>
                                                <label class="cr-styled" style="text-align: left;" >
                                                    <input type="checkbox" id="chkDocumentoACopiar" checked>
                                                    <i class="fa"></i> 
                                                    Relacionar documento
                                                    <br>
                                                </label>
                                            </div>
                                            <div id="linkDocumentoACopiar">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /Portlet -->
            </div>
            <div class="col-lg-8 ">
                <div class="portlet"><!-- /primary heading -->
                    <div class="portlet-heading">
                        <h3 class="portlet-title text-dark text-uppercase">
                            Detalle del documento
                        </h3>
                        <div class="portlet-widgets">
                            <a onclick="loaderComboBien()">
                                <i class="ion-refresh" tooltip-btndata-toggle='tooltip' title="Actualizar"></i>
                            </a>
                            <span class="divider"></span>
                            <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round" tooltip-btndata-toggle='tooltip' title="Minimizar"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body">
                            <div id="contenedorDetalle" style="min-height: 405px;height: auto;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6" id="contenedorOrganizador">
                                            <label>Organizador *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboOrganizador" id="cboOrganizador" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Bien *</label> <span class="divider"></span> 
                                            <a onclick="cargarBien();">
                                                <i class="ion-social-dropbox" tooltip-btndata-toggle='tooltip'  style="font-size:16px; color:#E8BA2F;" title="Agregar bien"></i>
                                            </a>
                                            <span class="divider">
                                            </span> 
                                            <a onclick="verificarStockBien();">
                                                <i class="fa fa-cubes"  tooltip-btndata-toggle='tooltip'  style="color:#5cb85c;" title="Verificar stock"></i>
                                            </a>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboBien" id="cboBien" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Cantidad *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="number" id="txtCantidad" name="txtCantidad" class="form-control" required="" aria-required="true" value="1.00" maxlength="25" style="text-align: right;"/>
                                            </div>
                                        </div> 
                                        <div class="form-group col-md-4">
                                            <label>Unidad de medida *</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboUnidadMedida" id="cboUnidadMedida" class="select2" onchange="obtenerPrecioUnitario()">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Precio Unitario*</label>
                                            <span class="divider"></span> 
                                            <a onclick="verificarPrecioBien();">
                                                <i class="ion-pricetag" tooltip-btndata-toggle='tooltip' title="Precio del bien"></i>
                                            </a>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="number" id="txtPrecio" name="txtPrecio" class="form-control" required="" aria-required="true" value="" maxlength="25" style="text-align: right;"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="modal-footer">
                                            <div class="form-group">
                                                <div class="col-md-6" style="text-align: left;" hidden="hidden">
                                                    <div id="contenedorChkIncluyeIGV" hidden="true">
                                                        <label class="cr-styled" style="text-align: left;" >
                                                            <input type="checkbox" id="chkIncluyeIGV" onclick="onChangeCheckIncluyeIGV();">
                                                            <i class="fa"></i> 
                                                            Los precios incluyen IGV
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-info" onclick="confirmar()"><i class="fa ion-arrow-down-c"></i> Confirmar</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row" style="height: auto;">
                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style='text-align:center;'>Org.</th>
                                                    <th style='text-align:center;'>Cant</th>
                                                    <th style='text-align:center;'>U.M.</th>
                                                    <th style='text-align:center;'>Bien</th> 
                                                    <th style='text-align:center;'>P.Unit</th> 
                                                    <th style='text-align:center;'>Precio</th> 
                                                    <th style='text-align:center;'></th>
                                                </tr>
                                            </thead>
                                            <tbody id="dgDetalle">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row text-center m-t-30 m-b-30">
<!--                                        <div class="col-sm-3 col-xs-6">
                                            
                                        </div>-->
                                        <div class="col-sm-4 col-xs-6">
                                            <div id="contenedorSubTotalDiv" hidden="true">
                                                <h4 id="contenedorSubTotal"></h4>
                                                <median class="text-uppercase">Sub total</median>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-6">
                                            <div id="contenedorIgvDiv" hidden="true">
                                                <h4 id="contenedorIgv"></h4>
                                                <median class="text-uppercase">
                                                    <label class="cr-styled" style="text-align: left;" >
                                                        <input type="checkbox" id="chkIGV" onclick="onChangeCheckIGV();">
                                                        <i class="fa"></i>
                                                        IGV
                                                    </label>
                                                </median>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-6">
                                            <div id="contenedorTotalDiv" hidden="true">
                                                <h4 id="contenedorTotal"></h4>
                                                <median class="text-uppercase">Total</median>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /Portlet -->
                
                <div class=" col-lg-12">
                    <div class="modal-footer">
                        <a href="#" class="btn btn-danger"  onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>
                        <a class="btn btn-success"  onclick="enviar()" name="env" id="env" ><i class="fa fa-send-o"></i> Enviar</a>
                        <a class="btn btn-info"  onclick="enviarEImprimir()" name="imp" id="imp" ><i class="fa fa-print"></i> Enviar e imprimir</a>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- End row -->
       
        <!--inicio modad-->
         <!--<div id="con-close-modal"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">-->
        <div id="modalStockBien"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Verificación de stock</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="table">
                            <!--<div id="dataLista">-->
                                <table id="datatableStock" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>Organizador</th>
                                            <th style='text-align:center;'>Unidad de medida</th>
                                            <th style='text-align:center;'>Stock</th>
                                        </tr>
                                    </thead>
                                </table>
                            <!--</div>-->
                        </div>
                        <!--<i><strong>Resumen:</strong></i><br>-->
                        <div id="div_resumenStock">
                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal -->    
        
        
        <!--Inicio modal para el precio del bien--> 
        
         <!--inicio modad-->
        <div id="modalPrecioBien"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
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
                                            <th style='text-align:center;'>Precio</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div><!-- /.modal -->   
        
        <!--Fin modal para el precio del bien-->
        
        <div id="modalBusquedaDocumentoACopiar"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="col-lg-12">
                                <div  class="portlet" >
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="portlet-heading bg-purple m-b-0" 
                                                 onclick="colapsarBuscadorCopiaDocumento()" title="Expadir / contraer buscador"
                                                 style="padding-top: 13px;padding-bottom: 13px;cursor: hand;">
                                                <!--                                         data-toggle="popover" data-placement="top">-->
                                                <h3 class="portlet-title" style="color: #797979">
                                                    <a id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de búsqueda" style="color: white">
                                                        Buscador de documentos a copiar
                                                    </a>
                                                </h3>

                                                <div class="portlet-widgets">
                                                    <a onclick="actualizarBusquedaCopiaDocumento()" title="Actualizar resultados de búsqueda">
                                                        <i class="ion-refresh"></i>
                                                    </a>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="bg-infoCopiaDocumento" class="panel-collapse collapse in">
                                        <div class="portlet-body">
                                            <div id="divTipoDocumento" class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Tipo de documento</label>
                                                </div>
                                                <div class="form-group col-md-10">
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select name="cboDocumentoTipoM" id="cboDocumentoTipoM" class="select2" multiple>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <form  id="formularioDocumentoTipo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label>Persona</label>
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <select name="cboPersonaM" id="cboPersonaM" class="select2" multiple>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Serie</label>
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" id="txtSerie" name="txtSerie" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Número</label>
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" id="txtNumero" name="txtNumero" class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label>Fecha Emisión</label>
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

                                                        <div class="form-group col-md-4">
                                                            <label>Fecha Vencimiento</label>
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

                                                    </div>
                                                </form>
                                            </div> 

                                            <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px">
                                                <button type="button" onclick="buscarDocumentoACopiar(1)" value="enviar" class="btn btn-purple"></i>&ensp;Buscar</button>&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table id="datatableModalDocumentoACopiar" class="table table-striped table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style='text-align:center;'></th>
                                        <th style='text-align:center;'>F. creación</th>
                                        <th style='text-align:center;'>F. emisión</th>
                                        <th style='text-align:center;'>Tipo documento</th>
                                        <th style='text-align:center;'>Persona</th>
                                        <th style='text-align:center;'>Serie</th>
                                        <th style='text-align:center;'>Número</th> 
                                        <th style='text-align:center;'>F. venc.</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px;clear:left">
                        <div class="form-group">
                            <div class="col-md-6" style="text-align: left;">
                                <p><b>Leyenda:</b>&nbsp;&nbsp;
                                    <i class="fa fa-arrow-down" style="color:#04B404;"></i> Agregar documento a copiar
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
                                <div class="portlet"><!-- /primary heading -->
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
                                </div> <!-- /Portlet -->
                            </div>
                            <div class="col-lg-8 ">
                                <div class="portlet"><!-- /primary heading -->
                                    <div class="portlet-heading">
                                        <h3 class="portlet-title text-dark text-uppercase">
                                            Detalle del documento
                                        </h3>
                                        <div class="portlet-widgets">
<!--                                            <span class="divider"></span>
                                            <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>-->
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
        </div><!-- /.modal --> 

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
                        <button type="button" class="btn btn-info m-b-4" onclick="asignarStockXOrganizador();"><i class="fa fa-send-o"></i>&ensp;Aceptar</button>
                        <button type="button" class="btn btn-danger m-b-5" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 

                    </div> 
                </div> 
            </div>
        </div><!-- /.modal --> 
        
        
        <div id="datosImpresion" hidden="true">
        </div>

        <script src="vistas/com/movimiento/imprimir.js"></script>
        <script src="vistas/com/movimiento/movimiento_form.js"></script>
    </body>
</html>
