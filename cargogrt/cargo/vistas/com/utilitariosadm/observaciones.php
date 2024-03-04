<div class="wraper container-fluid">
    <!-- <div class="row"> -->
        <!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->
            <!-- <div class="col-lg-9"> -->
                <h3 id="titulo" class="title"></h3>
            <!-- </div> -->
        <!-- </div> -->
    <!-- </div> -->
    <div class="card">
        <div class="panel panel-body" id="muestrascroll">
            <div class="form-group col-md-5">
                <label for="inputEmail4">Fecha de Observacion Inicial</label>
                <input type="date" class="form-control" id="fInicio">
            </div>
            <div class="form-group col-md-5">
                <label for="inputPassword4">Fecha de Observacion Final</label>
                <input type="date" class="form-control" id="fFinal">
            </div>
            <div class="form-group col-md-1">
                <br>
                <button onclick="buscarDataObservacion()" type="button" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            </div>
            <div class="form-group col-md-1">
                <br>
                <i title="Descargar Excel" style="color:green; font-size: 24px; cursor: pointer;" class="fa fa-file-excel-o icono-sobresaliente" aria-hidden="true" onclick="descargarExcel()"></i>
            </div>
            <style>
                .icono-sobresaliente {
                    color: green;
                    font-size: 24px;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                }

                .icono-sobresaliente:hover {
                    transform: scale(1.1);
                }
            </style>
        </div>
    </div>
    <div class="card">
        <div class="panel panel-body" style="padding: 0.1px 30px;" id="muestrascroll">
        <p id="divLeyenda">
                </p>
        <button onclick="Enviar()" type="button" class="btn btn-info">Enviar al Mansiche</button>
            <div id="dataListObservacion">
                <table id="datatable" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr style='background-color:#f77816;'>
                            <th style='text-align:center;color:#ffffff'>Pedido</th>
                            <th style='text-align:center;color:#ffffff'>F. Pedido</th>
                            <th style='text-align:center;color:#ffffff'>Cliente</th>
                            <th style='text-align:center;color:#ffffff'>Origen</th>
                            <th style='text-align:center;color:#ffffff'>Destino</th>
                            <th style='text-align:center; color:#ffffff'>Total</th>
                            <th style='text-align:center;color:#ffffff'>Comp.</th>
                            <th style='text-align:center;color:#ffffff'>F. Emision</th>
                            <th style='text-align:center;color:#ffffff'>Nro. Comp.</th>
                            <th style='text-align:center;color:#ffffff'>Estado</th>
                            <th style='text-align:center;color:#ffffff'>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- <div style="clear:left">
                <p id="divLeyenda">
                </p>
            </div> -->
        </div>
    </div>
</div>
<!--modal para el detalle del movimiento-->
<div id="modalDetalleDocumento" class="modal fade" tabindex="-1" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0px">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="formularioDetalleDocumento" method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                        </form>
                    </div>
                </div>

                <div class="col-lg-12 ">
                    <div class="portlet" style="box-shadow: 0 0px 0px">
                        <div id="portlet2" class="row">
                            <div class="portlet-body">
                                <div id="tabDistribucion">
                                    <ul id="tabsDistribucionMostrar" class="nav nav-tabs nav-justified">
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
                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="envioCorreo" hidden>
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
                    <div class="col-md-6" id="espacioTab"></div>
                    <div class="col-md-6">
                        <div class="input-group m-t-10" style="float: right">
                            <!-- <a class="btn btn-purple" id="btnRetornar" hidden=""><i class="ion-arrow-left-a" ></i>&nbsp;&nbsp; Regresar</a>
                            <a class="btn btn-success" id="btnGenerarPedidoDevolucionCargo" hidden=""><i class="ion-checkmark-circled" ></i>&nbsp;&nbsp; Generar devolución de cargo</a>
                            <a class="btn btn-info" id="btnImprimirModal"><i class="fa fa-print"></i> Imprimir</a> -->
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vistas/com/utilitariosadm/observaciones.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>