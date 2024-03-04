
<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="idEditar" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="cajaId" value="<?php echo $_GET['cajaId']; ?>" />
           <input id="egreso"  name="egreso"  type="number" style="visibility:hidden">
             <input id="ingreso"  name="ingreso"  type="number" style="visibility:hidden">
              <input id="egresoOtros"  name="egresoOtros"  type="number" style="visibility:hidden">
               <input id="egresoPos"  name="egresoPos"  type="number" style="visibility:hidden">
                    <input id="efectivo"  name="efectivo"  type="number" style="visibility:hidden">
              
        <input type="hidden" id="id" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> Cierre <span id="spFecha"></span></h3>
        </div>
        <div class="panel-body">
            <span id="msjFormGen" class="control-label" style="color:red;font-style: normal;" hidden></span>

            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-color panel-info" style="border: 1px solid #3bc0c3;">
                            <div class="panel-heading"> 
                                <h3 class="panel-title" id="fechaApertura"></h3> 
                            </div> 
                            <div class="panel-body">
                                <div class="row" id="divDatosApertura">
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
                                                <input id="importeVisa"  name="importeVisa"  type="number" class="form-control" value="0.00"
                                                       style="text-align: right">
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
                                                <input id="importeDeposito"  name="importeDeposito"  type="number" class="form-control" value="0.00"
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
                                                <input id="importeTransferencia"  name="importeTransferencia"  type="number" class="form-control" value="0.00"
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
                                                <input id="importeTotal"  name="importeTotal"  type="number" class="form-control" 
                                                       value="0.00" style="text-align: right">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            </div>
                                            <span id='msjImporteCierre' class="control-label" style='color:red;font-style: normal;' hidden></span>
                                            <div>&ensp;</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="cr-styled" for="rbCaja">
                                                <input type="radio" id="rbCaja" name="example-radios1" onclick="habilitarBoton();"> 
                                                <i class="fa"></i> 
                                                <b>Depósito Banco</b>
                                            </label>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">S/</span>
                                                <input id="importeCierre"  name="importeCierre"  type="number" class="form-control" 
                                                       value="0.00" style="text-align: right" onchange="sumaTotal();">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            </div>
                                            <span id='msjImporteCierre' class="control-label" style='color:red;font-style: normal;' hidden></span>

                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="cr-styled" for="rbTraslado">
                                                <input type="radio" id="rbTraslado" name="example-radios1" onclick="habilitarBoton();"> 
                                                <i class="fa"></i>
                                                <b>Caja </b>
                                            </label>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">S/</span>
                                                <input id="importeTraslado"  name="importeTraslado"  type="number" class="form-control" 
                                                       value="0.00" style="text-align: right" onchange="sumaTotal();">
                                                <span class="input-group-addon"><i class="ion-android-archive"></i></span>
                                                <!--<span class="input-group-addon" id="sugerido"></span>-->
                                            </div>
                                            <span id='msjImporteTraslado' class="control-label" style='color:red;font-style: normal;' hidden></span>

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
                                                <textarea type="text" id="txtComentario" name="txtComentario" 
                                                          value="" maxlength="500" rows="2" placeholder="Comentario"
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

            <br>
            <div class="row">
                <div class="form-group col-md-12">
                    <a href="#" class="btn btn-danger m-b-5" id="btnCancelar" onclick="cargarPantallaListar()" 
                       style="border-radius: 0px;">
                        <i class="fa fa-close"></i>&ensp;Cancelar
                    </a>&nbsp;&nbsp;&nbsp;                               

                    <button type="button" id="btnEnviar" name="btnEnviar" class="btn btn-info w-sm m-b-5" 
                            style="border-radius: 0px;" onclick="guardar()">
                        <i class="fa fa-send-o"></i>&ensp;Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="vistas/com/acCaja/cierre_caja_form.js"></script>