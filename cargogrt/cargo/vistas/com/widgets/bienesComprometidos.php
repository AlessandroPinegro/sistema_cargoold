<html lang="es">
    <head>
        <style type="text/css" media="screen">
            @media screen and (max-width: 1000px) {
                #scroll{
                    width: 1000px;               
                }
                #muestrascroll{
                    overflow-x:scroll;
                }
            }
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
                                         onclick="colapsarBuscador()" title="Expadir / contraer buscador"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: hand;">
                                        <!--data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()" title="Expadir / contraer buscador"-->
                                        <h3 class="portlet-title" style="color: #797979">
                                            <a id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de búsqueda" style="color: white">
                                                Buscador
                                            </a>
                                        </h3>

                                        <div class="portlet-widgets">
                                            <a onclick="actualizarBusqueda()" title="Actualizar resultados de búsqueda">
                                                <i class="ion-refresh"></i>
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Estado</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboEstado" id="cboEstado" class="select2">
                                                    <option value="" selected>Todos</option>
                                                    <option value="1">Registrado</option>
                                                    <!--<option value="2">Anulado</option>-->
                                                    <option value="3">Aprobado</option>
                                                    <option value="4">Pendiente</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>&ensp;</label>
                                            <br>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Fecha:&ensp;   </label> <label id="lblFecha"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px">
                                        <button type="button" href="#bg-info" onclick="buscarBienesComprometidos(1)" value="enviar" class="btn btn-purple"> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Organizador</th>
                                    <th style='text-align:center;'>Bien</th>
                                    <th style='text-align:center;'>Unidad medida</th>
                                    <th style='text-align:center;'>Cantidad</th> 
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right">Totales:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/widgets/bienesComprometidos.js"></script>
    </body>
</html>


