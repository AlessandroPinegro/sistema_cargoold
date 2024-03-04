<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> tipo de documento</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-md-6 ">
                    <label>Descripci&oacute;n *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" required="" aria-required="true" value="" maxlength="200"/>
                    </div>
                    <span id='msjDescripcion' class="control-label" style='color:red;font-style: normal;' hidden></span>
                </div>
                <div class="form-group col-md-6">
                    <label>Tipo comprobante de pago Sunat</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboCodigoSunat" id="cboCodigoSunat" class="select2">
                        </select>
                        <i id='msjCodigoSunat' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Tipo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboTipo" id="cboTipo" class="select2">
                        </select>
                        <i id='msjTipo' style='color:red;font-style: normal;' hidden></i>
                    </div>
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
                <div class="form-group col-md-6">
                    <label>Comentario </label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <textarea type="text" id="txtComentario" name="txtComentario" class="form-control" value="" maxlength="500"></textarea>
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
                            style="border-radius: 0px;" onclick="enviar()">
                        <i class="fa fa-send-o"></i>&ensp;Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vistas/com/contabilidadDocumentoTipo/documento_tipo_form.js"></script>