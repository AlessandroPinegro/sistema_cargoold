
<div class="row">
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"> 
                <h3 class="panel-title">CENTRO COSTO
                    <a  onclick="contraerTodo()" style="float: right" title="Contraer todo"><i class="ion-arrow-shrink"></i></a>
                    <!--<a  onclick="expandirTodo()" style="float: right" title="Expandir todo"><i class="ion-arrow-expand">&nbsp;&nbsp;&nbsp;</i></a>-->                        
                    <a  onclick="nuevoCentroCostoPadre()" style="float: right;color:#55acee" title="Nuevo centro de costo"><i class="fa fa-plus-circle">&nbsp;&nbsp;&nbsp;</i></a>                        
                </h3> 
            </div> 
            <div class="panel-body"> 
                <div class="dd" id="nestableLista" style="height:400px; overflow: auto; white-space: nowrap;">
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
        <div id="divFormulario" class="panel panel-default" hidden="true"> 
            <div class="panel-heading"> 
                <h3 class="panel-title" id="descripcionFormulario"></h3> 
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-6 ">
                        <label>C&oacute;digo *</label>
                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                            
<!--                            <div class="col-md-4" style="padding: 0;">
                                <input  type="text" id="txtCodigoPadre" name="txtCodigoPadre" class="form-control" value="" maxlength="45" style="text-align: right" readonly="true"/>
                            </div>-->
                            <div class="col-md-12" style="padding: 0;">
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
                
                <br>
                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cancelar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="guardar()" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;                                   
                    </div>
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
                        <i class="fa fa-plus-square" style="color:#1ca8dd"></i> Nuevo &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar &nbsp;&nbsp;&nbsp;
                    </p>
                </div>
            <!--</div>-->
        </div>
    </div>
</div>

<script src="vistas/com/contabilidadCentroCosto/centroCosto.js"></script>