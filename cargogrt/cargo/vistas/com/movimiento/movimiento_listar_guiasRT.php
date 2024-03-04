<div class="wraper container-fluid">
    <!-- <h3 id="titulo" class="title"></h3> -->

    <div class="row">

        <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />

        <div id="datosImpresion" style="background-color: #dfd" hidden="true"></div>

        <!-- <div class="col-md-2">
            <button id="btnNuevo" class="btn btn-info btn-block" onclick="nuevoForm()">
                <i class=" fa fa-plus-square-o"></i> Nuevo
            </button>
        </div>-->
        <div class="col-md-12">
            <div class="form-group col-md-4">
                <h3 id="titulo" class="title">Entrega</h3>
            </div>
            <div class="row" style='display: flex;justify-content: flex-end;'>
                <div class="form-group col-md-4">
                    <label>Agencia</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="agenciaUser" id="agenciaUser" class="select2" placeholder="Seleccione conductor"></select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet">
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="portlet-heading bg-info m-b-0" onclick="colapsarBuscador()" id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de búsqueda" style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">
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
                            <div class="form-group col-md-2">
                                <label>Bus</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboBus" id="cboBus" class="select2" placeholder="Seleccione Bus"></select>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Fecha de Salida</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaSalidad">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Conductor</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboConductor" id="cboConductor" class="select2" placeholder="Seleccione conductor"></select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nro. de Guia</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" placeholder="Nro. de Guia" id="nro_guia">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Origen</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboAgenciaOrigen" id="cboAgenciaOrigen" class="select2" placeholder="Seleccione origen"></select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Destino</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboAgenciaDestino" id="cboAgenciaDestino" class="select2" placeholder="Seleccione destino"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="modal-footer">
                                <button type="button" href="#bg-info" onclick="limpiarFiltrosManifiesto();" value="enviar" class="btn btn-info"> Limpiar
                                </button>&nbsp;
                                <button type="button" href="#bg-info" onclick="buscarGuiaRT();" value="enviar" class="btn btn-info"> Buscar
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="panel panel-default">
                <div id="dataList">
                </div>
                <br>
                <div style="clear:left">
                    <p id="divLeyenda">
                        <br><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class='fa fa-print' style='color:green;'></i> Imprimir Guia &nbsp;&nbsp;&nbsp;
                        <i class='fa fa-ban' style='color:#cb2a2a;'></i> Anular Guia &nbsp;&nbsp;&nbsp;
                    </p>
                </div>
            </div>
        </div>
    </div>

<!--modal para el detalle del movimiento-->
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 


                <!--                <br/><br/>
                                <div class="row">
                                    <div class="col-lg-12 ">
                                        <span class="hidden-xs">Detalle del documento</span> 
                                        <table id="datatable2" class="table table-striped table-bordered">
                                            <thead id="theadDetalle">
                                            </thead>
                                            <tbody id="tbodyDetalle">
                                            </tbody>
                                        </table> 
                                    </div>  
                                </div>
                                
                                <br/><br/>
                                <div id="divDetalleEntrega" class="row" hidden="">
                                    <div class="col-lg-12 ">
                                        <span class="hidden-xs">Detalle del documento</span> 
                                        <table id="datatableEntrega" class="table table-striped table-bordered">
                                            <thead id="theadDetalleEntrega">
                                            </thead>
                                            <tbody id="tbodyDetalleEntrega">
                                            </tbody>
                                        </table> 
                                    </div>  
                                </div>-->
            </div> 

            <div class="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                    <!--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>-->
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-6">                       
                        <div class="input-group m-t-10" style="float: right">
                            <a class="btn btn-info" id="btnImprimirModal"><i class="fa fa-print"></i> Imprimir</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<!-- fin modal -->



</div>
</div>

<script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
<script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>
<script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
<script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>
<script src="vistas/com/movimiento/movimiento_listar_guiasRT.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>