
<div class="page-title">
    <h3 id="titulo" class="title">Solicitud de viáticos</h3>
</div>
<div class="row">
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="portlet">
                    <div class="row">                        
                        <div class="col-md-12">
                            <div class="input-group m-t-10">

                                <div class="input-group-btn">
                                    <div class="form-group col-md-12">
                                        <a href="#" style="border-radius: 0px;" class="btn btn-info w-sm m-b-5" onclick="nuevo()">
                                            <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Nuevo
                                        </a>&nbsp;&nbsp;
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                 
                                    <!--<div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">-->
                                    <div class="input-group" id="divBuscador">                                
                                        <span class="input-group-btn">
                                            <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                                <i class="caret"></i>
                                            </a>
                                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 1059px;" id="ulBuscadorDesplegable">
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-2">
                                                            <label>DNI</label>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" id="txtCodigoBusqueda" name="txtCodigoBusqueda" class="form-control" value="" maxlength="500">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-2">
                                                            <label>Nombre</label>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <input type="text" id="txtNombresBusqueda" name="txtNombresBusqueda" class="form-control" value="" maxlength="500">
                                                            </div>
                                                        </div>                                
                                                    </div>
                                                    <div style="float: right">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"> <i class="fa fa-close"></i> Cancelar</button>
                                                            <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarSolicitudViatico(1)" class="btn btn-purple"> <i class="fa fa-search"></i> Buscar</button>                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </ul>
                                        </span>
                                        <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusquedaSolicitudViatico()">                                
                                        <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 1059px;" id="ulBuscadorDesplegable2">

                                        </ul>

                                    </div>
                                    <!--</div>-->
                                </div>
<!--                                <div class="input-group-btn" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="btn-toolbar" role="toolbar" style="float: right">
                                        <div class="input-group-btn">
                                            <a type="button" class="btn btn-success" onclick="actualizarBusquedaSolicitudViatico()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                                        </div>

                                        <div class="input-group-btn" style="padding-left: 10px;">
                                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="ion-gear-a"></i>  <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp; Exportar excel</a>
                                                </li>
                                                <li>
                                                    <a><i class="ion-archive"></i> Descargar Formato</a>
                                                </li>
                                                <li>
                                                    <a href="#" title="">
                                                        <div class="fileUpload" style="background-color: transparent; border: transparent; padding-left: 0px;    padding-right: 0px;">
                                                            <span style="color: black;"><i class="ion-upload m-r-5"></i>Importar Excel</span>

                                                            <input type="file" id="file" name="file" class="upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="$('#fileInfo').html($(this).val().slice(12));">
                                                        </div>
                                                        <b class="" id="fileInfo"><span id="lblDoc"></span></b>
                                                        <input type="hidden" id="secretFile" value="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <table id="datatable" class="table table-striped table-bordered"  style="width: 100%">
                <thead>
                    <tr>
                        <th style='text-align:center;'>Colaborador</th>
                        <th style='text-align:center;'>Número</th>
                        <th style='text-align:center;'>Total</th> 
                        <th style='text-align:center;'>F. Solicitada</th>
                        <th style='text-align:center;'>F. Creacion</th>
                        <th style='text-align:center;'>Estado</th>
                        <th style='text-align:center;'>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td >TORRES CORDOVA, EDGAR</td>
                        <td >2016 06 000001</td>
                        <td style="text-align: right">2000.00</td> 
                        <td style="text-align: center">03/06/2016</td>
                        <td style="text-align: center">03/06/2016</td>
                        <td style="text-align: center">Registrado</td>
                        <td style="text-align: center"><a href="#" onclick="editar(1)" title="Editar"><i class="fa fa-edit" style="color:#E8BA2F"></i></a>&nbsp;
                            <a href="#" onclick="anularDocumento(1)" title="Anular"><i class="fa fa-ban" style="color:#cb2a2a"></i></a>&nbsp;
                            <a href="#" onclick="aprobarDocumento(1)" title="Aprobar"><i class="ion-checkmark-circled" style="color:#5cb85c"></i></a>&nbsp;                        
                        </td>
                    </tr>
                    <tr>
                        <td >CARDENAS CUBA, GIAN</td>
                        <td >2016 06 000002</td>
                        <td style="text-align: right">2500.00</td> 
                        <td style="text-align: center">07/06/2016</td>
                        <td style="text-align: center">05/06/2016</td>
                        <td style="text-align: center">Registrado</td>
                        <td style="text-align: center"><a href="#" onclick="editar(1)" title="Editar"><i class="fa fa-edit" style="color:#E8BA2F"></i></a>&nbsp;
                            <a href="#" onclick="anularDocumento(1)" title="Anular"><i class="fa fa-ban" style="color:#cb2a2a"></i></a>&nbsp;
                            <a href="#" onclick="aprobarDocumento(1)" title="Aprobar"><i class="ion-checkmark-circled" style="color:#5cb85c"></i></a>&nbsp;                        
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <br>
        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                <i class="fa fa-ban" style="color:#cb2a2a;"></i> Anular&nbsp;&nbsp;&nbsp;
                <i class="ion-checkmark-circled" style="color:#5cb85c;"></i> Aprobar&nbsp;&nbsp;&nbsp;
<!--                <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo -->
            </p>
        </div>
    </div>    
</div>
<script src="vistas/com/rendirCuenta/solicitud_viatico_listar.js"></script>