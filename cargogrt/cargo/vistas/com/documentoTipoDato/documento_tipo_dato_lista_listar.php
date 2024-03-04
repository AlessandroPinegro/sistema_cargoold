<div class="page-title">
    <!--<h3 class="title"><span id="tituloPrincipal"><a onclick="cargarDiv('#window',URL_BASE+'vistas/com/documentoTipoDato/documento_tipo_dato_listar.php')" title="Click para regresar">Listas dinámicas </a> &gt; Mantenedor lista</span></h3>-->
    <h3 class="title"><span id="tituloPrincipal"></span></h3>
</div>
<!--<div class="col-md-12 col-md-12 col-xs-12">-->
<div class="panel panel-default">  

    <div class="row">
        <div class="form-group col-md-12">
            <a href="#" class="btn btn-danger m-b-5" style="border-radius: 0px;" onclick="retornar()"><i class="ion-reply"></i>&ensp;Atras</a>&nbsp;&nbsp;&nbsp;
            <button type="button" onclick="nuevo()" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-pencil"></i>&ensp;Nuevo</button>&nbsp;&nbsp;
        </div>
    </div>
    
    <br>
    <div class="panel panel-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style='text-align:center;'>Descripción</th>
                        <th style='text-align:center;'>Valor</th>
                        <th style='text-align:center;'>Estado</th>
                        <th style='text-align:center;'>Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div style="clear:left">
        <p><b>Leyenda:</b>&nbsp;&nbsp;
            <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
            <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
            <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
            <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo 
        </p>
    </div>
    
    <div id="modalDocumentoTipoDatoLista" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 ">
                            <label>Descripci&oacute;n</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="55"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 ">
                            <label>Valor</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divValorTxt" style="display: none">
                                <input type="text" id="txt_valor" name="txt_valor" class="form-control" required="" aria-required="true" value="" maxlength="45"/>
                            </div>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divValorCbo" style="display: none">
                                <select name="cboValor" id="cboValor" class="select2">                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 ">
                            <label>Estado</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="cboEstado" id="cboEstado" class="select2">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <!--<a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="cargarModal()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Nuevo</a>-->
                    <button type="button" class="btn btn-info" data-dismiss="modal" onclick="guardar()"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="vistas/com/documentoTipoDato/documento_tipo_dato_lista_listar.js"></script>