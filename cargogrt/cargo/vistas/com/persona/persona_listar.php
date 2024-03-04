<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<input type="hidden" id="hddIsDependiente" value="0">
<div class="row">
    <input type="hidden" id="txtTipo" name="txtTipo" class="form-control" value="" readonly="true"/>
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <div class="row">
            <div class="col-lg-12">
                <div  class="portlet" >
                    <div class="row">                        
                        <div class="col-md-12" style="padding-left:0px">
                            <div class="input-group m-t-10">
                                
                        <div class="input-group-btn" id="listaPersonaTipo">
<!--                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true"  style="width: 100%; padding-top: 10px;padding-bottom: 10px;"><i class=" fa fa-plus-square-o"></i> Nueva <span class="caret"></span></button>
                            <ul id="listaPersonaTipo" class="dropdown-menu" role="menu">
                            </ul>-->
                        </div>
                                
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                 
                            <!--<div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">-->
                                <div class="input-group" id="divBuscador">                                
                                    <span class="input-group-btn">
                                        <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                            <i class="caret"></i>
                                        </a>
                                        <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                            <div class="portlet-body">
                                                <div class="row">
                                                    <div class="form-group col-md-2">
                                                        <label>Cód. Id.</label>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" id="txtCodigoBusqueda" name="txtCodigoBusqueda" class="form-control" value="" maxlength="500"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label>Nombre</label>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" id="txtNombresBusqueda" name="txtNombresBusqueda" class="form-control" value="" maxlength="500"/>
                                                        </div>
                                                    </div>                                
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-2">
                                                        <label>Tipo de persona</label>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboTipoPersonaBusqueda" id="cboTipoPersonaBusqueda" class="select2">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label>Clase de persona</label>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboClasePersonaBusqueda" id="cboClasePersonaBusqueda" class="select2" multiple>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="float: right">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"> <i class="fa fa-close"></i> Cancelar</button>
                                                        <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarPersona(1)" class="btn btn-purple"> <i class="fa fa-search"></i> Buscar</button>                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </ul>
                                    </span>
                                    <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" >                                
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable2">

                                    </ul>

                                </div>
                            <!--</div>-->
                        </div>
                            <div class="input-group-btn" style="padding-left: 0px;padding-right: 0px;">
                                <div class="btn-toolbar" role="toolbar"  style="float: right" >
                                    <div class="input-group-btn">
                                        <a type="button" class="btn btn-success" onclick="actualizarBusquedaPersona()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                                    </div>

                                    <div class="input-group-btn" style="padding-left: 10px;">
                                    
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a  onclick="exportarReporteExcel(1)" title="">
                                                    <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp; Exportar excel
                                                </a>
                                            </li>
                                            <li>
                                                <a href="util/formatos/formato_persona.xls"><i class="ion-archive"></i> Descargar Formato</a>
                                            </li>
                                            <li>
                                                <a href="#" title="">
                                                    <div class="fileUpload" style="background-color: transparent; border: transparent; padding-left: 0px;    padding-right: 0px;">
                                                        <span  style="color: black;"><i class="ion-upload m-r-5"></i>Importar Excel</span>

                                                        <input type="file" id="file" name="file" class="upload"
                                                               accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                               onchange="$('#fileInfo').html($(this).val().slice(12));"/>
                                                    </div>
                                                    <b class="" id="fileInfo"><span id="lblDoc"></span></b>
                                                    <input type="hidden" id="secretFile" value="" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <table id="datatable" class="table table-striped table-bordered" >
                <thead>
                    <tr>
                        <th style='text-align:center;'>Cód. Id.</th>
                        <th style='text-align:center;'>Nombre</th>
                        <th style='text-align:center;'>Telefono</th>
                        <th style='text-align:center;'>Dirección</th>
                        <th style='text-align:center;'>Tipo</th>
                        <th style='text-align:center;'>Clase</th>
                        <!--<th style='text-align:center;'>Estado</th>-->
                        <th style='text-align:center;'>Acc.</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
<!--                <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo -->
            </p>
        </div>
    </div>
    <div id="modalPersona" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Importar Persona</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="lb_empresa">Empresa *</label>
                                <select name="cboEmpresa" id="cboEmpresa" class="select2">
                                </select>
                                <span id='msj_empresa' class="control-label"
                                      style='color:red;font-style: normal;' hidden></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="lb_empresa">Resultado</label>
                                <div id="resultado" style="overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!--<a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="cargarModal()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Nuevo</a>-->
                        <button type="button" id="btnImportar" class="btn btn-info" onclick="importar()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Importar</button>
                        <button type="button" id="btnSalirModal" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="vistas/com/persona/persona_listar.js"></script>