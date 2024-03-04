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
<body >
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
<!--                                    <a onclick="exportarReporteReporteCompras()" title="">-->
<!--                                        <i class="fa fa-file-excel-o"></i>-->
<!--                                    </a>&nbsp;-->
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

                                <div class="form-group col-md-6">
                                    <label>Tipo de documento</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboTipoDocumentoMP" id="cboTipoDocumentoMP" class="select2" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label>Producto</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboProducto" id="cboProducto" class="select2">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 ">
                                    <label>Grupo de producto</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboProductoTipo" id="cboProductoTipo" class="select2">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label>Proveedor</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select name="cboProveedor" id="cboProveedor" class="select2">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Fecha emisión</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmisionMP">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmisionMP">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
<!--                                <button type="button" onclick="exportarReporteReporteCompras();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;">&ensp;Exportar reporte</button>&nbsp;&nbsp;-->
                                <button type="button" href="#bg-info" onclick="buscarReporteAtenciones(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row"></div>
        <div class="panel panel-body">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!--<div class="table">-->
                        <table id="dataTableReporteAtenciones" class="table table-striped table-bordered">
                            <thead>

                            <!--F. Creacion	F. Emisión	Tipo documento	Persona	Serie	Número-->
                            <tr>
                                <th style='text-align:center;'>F. Creación</th>
                                <th style='text-align:center;'>F. Emisión</th>
                                <th style='text-align:center;'>Tipo documento</th>
                                <th style='text-align:center;'>Proveedor</th>
                                <th style='text-align:center;'>Serie</th>
                                <th style='text-align:center;'>Número</th>
                                <th style='text-align:center;'>Usuario</th>
                                <th style='text-align:center;'>Acciones</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th colspan="7" style="text-align:right">Totales:</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                <!--</div>-->
            </div>

        </div>



    </div>
</div>

<!--<a href="#" class="btn" id="openBtn" onclick="abrirModalito()">Open modal</a>-->

<div id="modalito" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="cerrarModalito()" aria-hidden="true">×</button>
                <h4 class="modal-title" id="tituloModalitoMapa">Modal Heading</h4>
            </div>
            <div class="modal-body-scrollbar" id="bodyToAppend">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">Detalle</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#profile" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Gráfico de Relaciones</span>
                            </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered" id = "tablaAtencion">
                                    <thead >
                                        <tr id="tablaAtencionHead">

                                        </tr>
                                    </thead>
                                    <tbody id="tablaAtencionBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile">

                            <div class="wrapper-imagina">
                                <iframe src="" class="frame-imagina" id = "framecito"  frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button class="btn btn-icon btn-danger m-b-5" onclick="cerrarModalito()"> <i class="fa fa-remove"></i> Cerrar </button>
<!--                <button type="button" class="btn btn-default" onclick="cerrarModalito()">Close</button>-->
<!--                <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"  onclick="reOpenModal()" aria-hidden="true">×</button>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                            </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="input-group m-t-10" style="float: right">
                                <button id ="btnRegresar" type="button" class="btn btn-info m-b-5" data-dismiss="modal" onclick="reOpenModal()"><i class="ion-arrow-return-left"></i> Regresar a </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--</div>-->
<script src="vistas/com/reporte/reporteListadoAtencion.js"></script>
</body>
</html>


