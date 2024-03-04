<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> Operación</h3>
        </div>
        <div class="panel-body">
            <div class="row">  
                <div class="form-group col-md-6">
                    <label>Subdiario</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                
                        <select name="cboSubdiario" id="cboSubdiario" class="select2">
                        </select>
                        <span id='msjSubdiario' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>Sucursal *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                
                        <select name="cboSucursal" id="cboSucursal" class="select2">
                        </select>
                        <span id='msjSucursal' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="form-group col-md-6 ">
                    <label>C&oacute;digo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                        <div class="col-md-12" style="padding: 0;">
                            <input  type="text" id="txtCodigo" name="txtCodigo" class="form-control" value="" maxlength="45"/>
                        </div>
                    </div>
                    <span id='msjCodigo' class="control-label" style='color:red;font-style: normal;' hidden></span>
                </div>
                <div class="form-group col-md-6 ">
                    <label>Descripci&oacute;n *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" required="" aria-required="true" value="" maxlength="200"/>
                    </div>
                    <span id='msjDescripcion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                </div>
            </div>
            <div class="row">                    
                <div class="form-group col-md-6">
                    <label>Tipo de cambio *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboTipoCambio" id="cboTipoCambio" class="select2">
                        </select>
                        <i id='msjTipoCambio' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>                          

                <div class="form-group col-md-6">
                    <label>Tipo operación Sunat LE</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboCodigoSunat" id="cboCodigoSunat" class="select2">
                        </select>
                        <i id='msjCodigoSunat' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>

            <div class="row">              
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
                <div class="form-group col-md-6">                                                
                    <label>&nbsp;</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="checkbox">
                            <label class="cr-styled">
                                <input type="checkbox" name="chkEgresoBanco" id="chkEgresoBanco"  >
                                <i class="fa"></i> 
                                Esta operación es por egreso de banco con cheque
                            </label>
                        </div>
                    </div>
                </div>
            </div>               
            <br>
            
            <div class="row" id="rowNumeracion"> 
                <div class="col-md-4">
                    
                </div>
                <div class="col-md-4">                        
                    <table class="table table-striped table-bordered" style="text-align: center;">
                        <tbody id="tbodyOperacionNumeracion" >

                        </tbody>
                    </table>                    
                </div>    
                <div class="col-md-4">
                    
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
                            style="border-radius: 0px;" onclick="enviar()">
                        <i class="fa fa-send-o"></i>&ensp;Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vistas/com/contabilidadOperaciones/operaciones_form.js"></script>