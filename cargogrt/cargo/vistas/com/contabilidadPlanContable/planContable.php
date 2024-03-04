
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">

            <div class="panel-heading">
                <div class="col-md-7 col-sm-7">
                    <h3 class="panel-title">PLAN CONTABLE </h3>
                </div>
                <div class="col-md-5 col-sm-5">                    
                    <button type="button" onclick="exportarRegistroCompras('excel');" value="Exportar" name="env" id="env" class="btn btn-info w-md btn-sm" style="border-radius: 0px;">&ensp;Exportar excel</button>&ensp;&ensp;
                    <button type="button" onclick="exportarRegistroCompras('txt');" value="Exportar" name="env" id="env" class="btn btn-success w-md btn-sm" style="border-radius: 0px;">&ensp;Exportar txt</button>&nbsp;&nbsp;
                    <div class="col-sm-4">
                        <select name="cboPeriodo" id="cboPeriodo" class="select2">
                            <option value="-1" selected>Periodo</option>
                        </select>
                    </div>                    
                    <a onclick="contraerTodo()" title="Contraer todo"><i class="ion-arrow-shrink"></i></a>
                </div>  
                <br>
            </div> 
            <div class="panel-body"> 
                <!--<div class="dd" id="nestableLista" style="height:734px; overflow: auto; white-space: nowrap;">-->
                <div class="dd" id="nestableLista" style="width:100%;height:400px; overflow: auto;">

                </div>
            </div>
        </div>
    </div>    
</div>

<!--Modal formulario plan contable.-->
<div id="divFormulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">                     
            <div class="modal-header"> 
                <button type="button" onclick="cancelar()" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h3 class="panel-title" id="descripcionFormulario"></h3> 
            </div> 
            <div class="modal-body">                        
                <div class="row">
                    <div class="form-group col-md-6 ">
                        <label>C&oacute;digo *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                            
                            <div class="col-md-4" style="padding: 0;">
                                <input  type="text" id="txtCodigoPadre" name="txtCodigoPadre" class="form-control" value="" maxlength="45" style="text-align: right" readonly="true"/>
                            </div>
                            <div class="col-md-8" style="padding: 0;">
                                <input  type="text" id="txtCodigo" name="txtCodigo" class="form-control" value="" maxlength="45"/>
                            </div>
                        </div>
                        <span id='msjCodigo' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Estado *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                
                            <select name="cboEstado" id="cboEstado" class="select2">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <span id='msjEstado' class="control-label" style='color:red;font-style: normal;' hidden></span>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-12">
                        <label>Descripci&oacute;n *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" required="" aria-required="true" value="" maxlength="200"/>
                        </div>
                        <span id='msjDescripcion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div> 
                <div class="row">

                    <div class="form-group col-md-6">
                        <label>Tipo de Cuenta *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboCuentaTipo" id="cboCuentaTipo" class="select2">
                            </select>
                            <i id='msjCuentaTipo' class="control-label" style='color:red;font-style: normal;' hidden></i>
                        </div>

                    </div>

                    <div class="form-group col-md-6">                                                
                        <label>&nbsp;</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="checkbox">
                                <label class="cr-styled">
                                    <input type="checkbox" name="chkTitulo" id="chkTitulo"  >
                                    <i class="fa"></i> 
                                    Es cuenta título
                                </label>
                            </div>
                        </div>
                    </div>
                </div>        
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Exige dimensión</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboDimension" id="cboDimension" class="select2" multiple>
                            </select>
                            <span id='msjDimension' class="control-label" style='color:red;font-style: normal;' hidden></span>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Moneda origen *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboMoneda" id="cboMoneda" class="select2">
                            </select>
                            <i id='msjMoneda' class="control-label" style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="form-group col-md-6">
                        <label>La cuenta exige</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboCuentaExige" id="cboCuentaExige" class="select2" multiple>
                            </select>
                            <span id='msjCuentaExige' class="control-label" style='color:red;font-style: normal;' hidden></span>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Naturaleza de la cuenta *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboCuentaNaturaleza" id="cboCuentaNaturaleza" class="select2">
                            </select>
                            <i id='msjCuentaNaturaleza' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>
                </div>
                <div class="row" style="border: solid 1px #CCC;margin-top: 12px;margin-bottom: 20px;">
                    <div class="checkbox" style="">
                        <label class="cr-styled" style="font-weight: bold">
<!--                            <input type="checkbox" name="chkAsiento" id="chkAsiento"  onchange="onChangeCheckAsiento()">-->
                            <input type="checkbox" name="chkAsiento" id="chkAsiento">
                            <i class="fa"></i> 
                            Habilitar asientos automáticos
                        </label>
                    </div>
                    <!--<h5 style="font-weight: bold"> &nbsp;&nbsp;&nbsp;&nbsp; DE LOS ASIENTOS AUTOMATICOS </h5>-->

                    <div class="form-group col-md-6">
                        <label>Cuenta de cargo</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboCuentaCargo" id="cboCuentaCargo" class="select2" disabled>
                            </select>
                            <i id='msjCuentaCargo' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Cuenta de abono</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboCuentaAbono" id="cboCuentaAbono" class="select2" disabled>
                            </select>
                            <i id='msjCuentaAbono' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>
                </div>

                <div class="row" style="border: solid 1px #CCC;margin-top: 12px;margin-bottom: 12px;">
                    <!--<h5 style="margin-top: 0px;"> &nbsp;&nbsp;&nbsp;&nbsp;--> 
                    <div class="checkbox" style="">
                        <label class="cr-styled" style="font-weight: bold">

<!--<input type="checkbox" name="chkAjustar" id="chkAjustar"  onchange="onChangeCheckAjustar()">-->

                            <input type="checkbox" name="chkAjustar" id="chkAjustar" ">
                            <i class="fa"></i> 
                            Ajustar tipo cambio
                        </label>
                    </div>
                    <!--</h5>-->

                    <!--                    <div class="form-group col-md-6"> 
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="checkbox">
                                                    <label class="cr-styled">
                                                        <input type="checkbox" name="chkAjustar" id="chkAjustar"  >
                                                        <i class="fa"></i> 
                                                        Ajustar tipo cambio
                                                    </label>
                                                </div>
                                            </div>
                                        </div>-->
                    <!--                </div>
                                    <div class="row">-->
                    <div class="form-group col-md-6">
                        <label>Como ajustar</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboComoAjustar" id="cboComoAjustar" class="select2" disabled>
                                <!--<option value="-1" selected>Ninguno</option>-->
                                <option value="1">Por documento</option>
                                <option value="2">Por saldo cuenta</option>
                            </select>
                            <i id='msjComoAjustar' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Tipo de cambio</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="cboTipoCambio" id="cboTipoCambio" class="select2" disabled>
                                <!--<option value="-1" selected>Ninguno</option>-->
                                <option value="1">Compra</option>
                                <option value="2">Venta</option>
                            </select>
                            <i id='msjTipoCambio' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="form-group col-md-6 ">
                        <label>C&oacute;digo cuenta equivalente</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input  type="text" id="txtCodigoEquivalente" name="txtCodigoEquivalente" class="form-control" value="" maxlength="45"/>
                        </div>
                        <span id='msjCodigoEquivalente' class="control-label"
                              style='color:red;font-style: normal;' hidden></span>
                    </div>

                    <div class="form-group col-md-6 ">
                        <label>Descripción cuenta equivalente</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input  type="text" id="txtDescripcionEquivalente" name="txtDescripcionEquivalente" class="form-control" value="" maxlength="200"/>
                        </div>
                        <span id='msjDescripcionEquivalente' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer"> 
                <div class="form-group col-md-12">
                    <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;margin-top: 8px;" onclick="cancelar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                    <button type="button" onclick="guardar()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;                                   
                </div>
            </div>
        </div>
    </div> 
</div>


<div class="row">    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <!--<div class="panel-body">--> 
            <div style="clear:left">
                <p style="margin-bottom: 0px"><b>Leyenda:</b>&nbsp;&nbsp;
                    <!--<i class="ion-arrow-expand"></i> Expandir todo &nbsp;&nbsp;&nbsp;-->
                    <i class="ion-arrow-shrink"></i> Contraer todo &nbsp;&nbsp;&nbsp;
                    <i class="ion-plus"></i> Expandir &nbsp;&nbsp;&nbsp;
                    <i class="ion-minus"></i> Contraer &nbsp;&nbsp;&nbsp;                        
                    <i class="fa fa-plus-square" style="color:#1ca8dd"></i> Nueva cuenta &nbsp;&nbsp;&nbsp;
                    <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar cuenta &nbsp;&nbsp;&nbsp;
                </p>
            </div>
            <!--</div>-->
        </div>
    </div>
</div>

<script src="vistas/com/contabilidadPlanContable/planContable.js"></script>