<html lang="es">
    <head>
        <style type="text/css" media="screen">
            #datatable td{
                vertical-align: middle;
            }
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
            .popover{
                max-width: 100%; 
            }
            th { white-space: nowrap; }
            .alignRight { text-align: right; }
        </style>
    </head>
    <body>
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div  class="portlet" >
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="portlet-heading bg-purple m-b-0" 
                                         onclick="colapsarBuscador()"
                                         id="idPopover" title="" data-toggle="popover" 
                                         data-placement="top" data-content="" 
                                         data-original-title="Criterios de búsqueda"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">
                                        <div class="portlet-widgets">
                                            <a onclick="exportarLibroMayorAuxiliar('excel');" title="">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>&nbsp;                                            
                                            <a id="loaderBuscarVentas" onclick="loaderBuscarVentas()">
                                                <i class="ion-refresh"></i>
                                            </a>
                                            <span class="divider"></span>
                                            <!--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>
                                            </a>-->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <div class="row">

                                        <div class="form-group col-md-6 ">
                                            <label>CuentaContable:</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboCuentaContable" id="cboCuentaContable" class="select2">
                                                    <option value="">Seleccione una cuenta</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>Persona</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersona" id="cboPersona" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 ">
                                            <label>Periodo</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPeriodoInicio" id="cboPeriodoInicio" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 ">                                            
                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPeriodoFin" id="cboPeriodoFin" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                                    <div class="row">
                                                                            <div class="form-group col-md-6 ">
                                                                                <label>CuentaContable:</label>
                                                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <select name="cboCuentaContable" id="cboCuentaContable" class="select2">
                                                                                        <option value="">Seleccione una cuenta</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                           
                                                                        </div>-->

                                    <div class="modal-footer">
                                        <button type="button" onclick="exportarLibroMayorAuxiliar('excel');" value="Exportar" name="env" id="env" class="btn btn-info w-md" style="border-radius: 0px;">&ensp;Exportar excel</button>&nbsp;&nbsp;  
                                        <!--<button type="button" onclick="exportarLibroMayorAuxiliar('txt');" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar txt</button>&nbsp;&nbsp;-->  
                                        <button type="button" href="#bg-info" onclick="buscarLibroMayor(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="dataList" class="table">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style='text-align:center;'>Número</th>
                                        <th style='text-align:center;'>Item</th>
                                        <th style='text-align:center;'>Cuenta</th>
                                        <th style='text-align:center;'>Relación</th>
                                        <th style='text-align:center;'>Registro</th>
                                        <th style='text-align:center;'>Documento</th>
                                        <th style='text-align:center;'>Monto</th>
                                        <th style='text-align:center;'>Área</th>
<!--                                        <th style='text-align:center;'>Sub-Total</th>
                                        <th style='text-align:center;'>IGV</th>
                                        <th style='text-align:center;'>Total</th>
                                        <th style='text-align:center;'>Acc.</th>-->
                                    </tr>
                                </thead>
                                <tbody id="tbodyDataTable"></tbody>
<!--                                <tfoot>
                                    <tr>
                                        <th colspan="8" style="text-align:right">Totales:</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalMostrarArchivoTxt"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase">ARCHIVO TXT</h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div id="idIframe" class="row" >
                        </div> 
                    </div>
                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
                            <div class="input-group m-t-10" style="float: right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="row" style="box-shadow: 0 0px 0px">
                                    <!--                            <div class="portlet-heading">
                                                                    <h3 id="nombreDocumentoTipo" class="portlet-title text-dark text-uppercase">
                                    
                                                                    </h3>                                
                                                                    <div class="clearfix"></div>
                                                                </div>-->
                                    <div id="portlet1" class="row">
                                        <div class="portlet-body" >
                                            <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                            </form>
                                        </div>

                                    </div>
                                </div> <!-- /Portlet -->
                            </div>
                            <div class="col-lg-12 ">
                                <div class="portlet" style="box-shadow: 0 0px 0px">
                                    <div id="portlet2" class="row">
                                        <div class="portlet-body">
                                            <div id="tabDistribucion">
                                                <ul id="tabsDistribucionMostrar"  class="nav nav-tabs nav-justified">
                                                    <li class="active">
                                                        <a href="#detalle" data-toggle="tab" aria-expanded="true" title="Detalle"> 
                                                            <span class="hidden-xs">Detalle del documento</span> 
                                                        </a> 
                                                    </li> 
                                                    <li> 
                                                        <a href="#distribucion" data-toggle="tab" aria-expanded="false" title="Distribución Contable"> 
                                                            <span class="hidden-xs">Distribución contable</span> 
                                                        </a> 
                                                    </li>
                                                </ul>
                                                <div id="div_contenido_tab" class="tab-content">
                                                    <div class="tab-pane active" id="detalle">
                                                        <table id="datatable2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalle">
                                                            </thead>
                                                            <tbody id="tbodyDetalle">
                                                            </tbody>
                                                        </table>
                                                    </div>                                            
                                                    <div class="tab-pane" id="distribucion" hidden="">
                                                        <table id="datatableDistribucion2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalleCabeceraDistribucion">

                                                            </thead>
                                                            <tbody id="tbodyDetalleDistribucion">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
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
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
                            <div class="input-group m-t-10" style="float: right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

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
    <!--</div>-->
    <script src="vistas/com/contabilidadLibros/libro_mayor_auxiliar.js?<?php echo date("YmdHms"); ?>"></script>
</body>
</html>


