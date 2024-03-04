
<div class="page-title">
    <!--<h3 class="title" id="titulo"></h3>-->
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12" >
            <div class="col-lg-8">
                <h3 class="title">Apertura / Cierre de caja</h3>
            </div>
            <div class="col-lg-2">
                <select name="cboAgencia" id="cboAgencia" class="select2 " onchange="onChangeCboAgencia(this.value)"></select>
            </div>
            <div class="col-lg-2">
                <select name="cboCaja" id="cboCaja" class="select2 " onchange="onChangeCboCaja(this.value)"></select>
            </div>
           
        </div>
    </div>


</div>
<div class="row">
    <div class="panel panel-default">
        <div class="row">
            <div class="form-group col-md-12">
                <a id="idNuevaApertura" href="#" style="border-radius: 0px;display: none;" class="btn btn-info w-sm m-b-5" onclick="nuevoApertura()">
                    <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Aperturar
                </a>&nbsp;&nbsp;
                <!--            </div>
                            <div class="form-group col-md-6">-->
                <a id="idNuevoCierre" href="#" style="border-radius: 0px;display: none;" class="btn btn-info w-sm m-b-5" onclick="nuevoCierre()">
                    <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Cerrar
                </a>&nbsp;&nbsp;
            </div>
        </div>
        <div class="panel panel-body">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <table id="dataTableACCaja" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Agencia</th>
                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Caja</th>
                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Usuario</th>
                            <th style='text-align:center;' colspan="3">Apertura</th>
                            <th style='text-align:center;' colspan="7">Cierre</th>
                            <!--<th style='text-align:center; vertical-align: middle;' rowspan="2">Acción</th>-->
                        </tr>
                        <tr>
                            <th style='text-align:center;'>Fecha</th>
                            <th style='text-align:center;'>Importe</th>
                            <th style='text-align:center;'>Acción</th>
                            <th style='text-align:center;'>Fecha</th>
                            <th style='text-align:center;'>Importe</th>
                            <th style='text-align:center;'>Traslado</th>
                            <th style='text-align:center;'>POS</th>
                            <th style='text-align:center;'>Depósito</th>
                            <th style='text-align:center;'>Transferencia</th>
                            <th style='text-align:center;'>Acción</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <span id="iconos_leyenda"></span>
<!--                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar apertura &nbsp;&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:green;"></i> Editar cierre &nbsp;&nbsp;&nbsp;
                <i class="fa fa-eye" style="color:#1ca8dd;"></i> Visualizar &nbsp;&nbsp;&nbsp;-->
                <!--<i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar &nbsp;&nbsp;&nbsp;-->
            </p>
        </div>
    </div>
</div>


<!--modal para el detalle apertura/cierre-->
<div id="modalDetalle"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> 
            </div>
            <div class="modal-body" style="padding-bottom: 0px;"> 
                <div id="detalleApertura">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-color panel-info" style="border: 1px solid #3bc0c3;">
                                <div class="panel-heading">
                                    <h3 class="panel-title" id="fechaCierre"></h3> 
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Saldo</label>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div style="text-align: center" class="widget-panel widget-style-1 bg-info">
                                                    <i class="fa fa-money" style="padding-right: 10px "></i> 
                                                    <h2 class="m-0 counter">S/ <span id="importeCierre1"></span></h2>
                                                    <br><br>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label>Comentario </label>
                                            <div class="widget-panel widget-style-1 bg-info" style="padding: 1px 60px 1px 1px;color: black;">
                                                <i class="fa fa-comments-o"></i>
                                                <textarea type="text" id="txtComentarioCierre" name="txtComentarioCierre" 
                                                          value="" maxlength="500" rows="2" placeholder="" disabled=""
                                                          style="height: auto;width: 100%;display: block;padding:
                                                          6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="panel panel-color panel-success" style="border: 1px solid #33b86c;">
                                <div class="panel-heading"> 
                                    <h3 class="panel-title">Apertura <span id="title"></span></h3> 
                                </div> 
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <div>
                                                <label>Efectivo</label>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div style="text-align: center" class="widget-panel widget-style-1 bg-info">
                                                    <i class="fa fa-money" style="padding-right: 10px "></i> 
                                                    <h2 class="m-0 counter"><span id="importeApertura"></span>&ensp;
                                                        <span id="sugerido"></span></h2>
                                                    <br><br>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label>Comentario </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="widget-panel widget-style-1" style="padding: 1px 60px 1px 1px;color: black; background-color: #73b891">
                                                    <i class="fa fa-comments-o"></i> 
                                                    <textarea type="text" id="txtComentario" name="txtComentario" 
                                                              value="" maxlength="500" rows="2" placeholder="Comentario" disabled=""
                                                              style="height: auto;width: 100%;display: block;padding:
                                                              6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <div id="detalleCierre">

                    <div class="tab-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="panel panel-color panel-info" style="border: 1px solid #3bc0c3;">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title" id="fechaApertura"></h3> 
                                    </div> 
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                                <div class="table" style="padding-top: 17px;">
                                                    <div id="dataList">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label>Comentario</label>
                                                <div class="widget-panel widget-style-1 bg-info" style="padding: 1px 60px 1px 1px;color: black;">
                                                    <i class="fa fa-comments-o"></i>
                                                    <textarea type="text" id="txtComentarioApertura" name="txtComentarioApertura" 
                                                              value="" maxlength="500" rows="2" placeholder="" disabled=""
                                                              style="height: auto;width: 100%;display: block;padding:
                                                              6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-color panel-success" style="border: 1px solid #33b86c;">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">Cierre <span id="title"></span></h3> 
                                    </div> 
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>POS</label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeVisa"  name="importeVisa" type="number" class="form-control" value="0.00"
                                                               style="text-align: right" readonly="">
                                                        <span class="input-group-addon"><i class="fa fa-cc-visa"></i></span>
                                                    </div>
                                                    <span id='msjImporteVisa' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12" style="">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Depósito</label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeDeposito"  name="importeDeposito" readonly="" type="number" class="form-control" value="0.00"
                                                               style="text-align: right">
                                                        <span class="input-group-addon"><i class="fa fa-cc-visa"></i></span>
                                                    </div>
                                                    <span id='msjImporteDeposito' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12" style="">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Transferencia</label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeTransferencia"  name="importeTransferencia" readonly="" type="number" class="form-control" value="0.00"
                                                               style="text-align: right">
                                                        <span class="input-group-addon"><i class="fa fa-cc-visa"></i></span>
                                                    </div>
                                                    <span id='msjImporteTransferencia' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-12" style="">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Efectivo</label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeTotal"  name="importeTotal" readonly="" type="number" class="form-control" 
                                                               value="0.00" style="text-align: right">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    </div>
                                                    <span id='msjImporteCierre' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                                    <div>&ensp;</div>
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Depósito Banco </label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeTraslado"  name="importeTraslado" readonly="" type="number" class="form-control" 
                                                               value="0.00" style="text-align: right">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                    </div>
                                                    <span id='msjImporteTraslado' class="control-label" style='color:red;font-style: normal;' hidden></span>

                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Caja</label>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">S/</span>
                                                        <input id="importeCierre"  name="importeCierre" readonly="" type="number" class="form-control" 
                                                               value="0.00" style="text-align: right">
                                                        <span class="input-group-addon"><i class="ion-android-archive"></i></span>
                                                        <!--<span class="input-group-addon" id="sugerido"></span>-->
                                                    </div>
                                                    <span id='msjImporteCierre' class="control-label" style='color:red;font-style: normal;' hidden></span>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">                    
                                            <div class="form-group col-lg-12" style="padding-top: 7px;">
                                                <label>Comentario</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="widget-panel widget-style-1" style="padding: 1px 60px 1px 1px;color: black;
                                                         background-color: #73b891">
                                                        <i class="fa fa-comments-o"></i> 
                                                        <textarea type="text" id="txtComentario2" name="txtComentario" 
                                                                  value="" maxlength="500" rows="2" placeholder="Comentario" disabled=""
                                                                  style="height: auto;width: 100%;display: block;padding:
                                                                  6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> 
            <div class="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">     
                        <div class="input-group m-t-10" style="float: right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<!-- fin detalle -->

<script src="vistas/com/acCaja/ac_caja_listar.js"></script>