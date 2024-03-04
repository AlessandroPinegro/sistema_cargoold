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
                        <!--<div  class="portlet" >-->
<!--                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="portlet-heading bg-purple m-b-0" 
                                         onclick="colapsarBuscador()" title="Expadir / contraer buscador"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: hand;">
                                        data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()" title="Expadir / contraer buscador"
                                        <h3 class="portlet-title" style="color: #797979">
                                            <a id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de búsqueda" style="color: white">

                                            </a>
                                        </h3>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            </div>-->

                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Persona</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersona" id="cboPersona" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fecha</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFecha">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Comentario</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--<input type="text" id="txtComenatrio" name="txtComenatrio" class="form-control" value="" />-->
                                                <textarea type="text" id="txtComenatrio" name="txtComenatrio" class="form-control" value="" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!--</div>-->   
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
                                    <th style='text-align:center;'>Tipo bien</th> 
                                    <th style='text-align:center;'>Unidad de medida</th>
                                    <th style='text-align:center;'>Stock del sistema</th>
                                    <th style='text-align:center;'>Stock real</th>
                                    <th style='text-align:center;'>Discrepancia</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListarAuditoria()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="enviar()" value="enviar" name="env" id="env" class="btn btn-success w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-save"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                        <button type="button" onclick="finalizar()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Finalizar</button>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-detalle-kardex"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Verificación de stock</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="table">
                            <div id="dataList">
                                <table id="datatableStock" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>Unidad de medida</th>
                                            <th style='text-align:center;'>Stock</th>
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
        </div><!--
        <!--</div>-->  
        <script src="vistas/com/reporte/reporte.js"></script>
        <script src="vistas/com/auditoria/auditoria.js"></script>
    </body>
</html>


