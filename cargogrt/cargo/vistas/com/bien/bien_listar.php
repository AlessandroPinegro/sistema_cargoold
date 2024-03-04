<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<div class="panel panel-default">
    <div class="row">
        <div class="col-lg-12">           
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-info"  onclick="nuevoBien()"><i class=" fa fa-plus-square-o"></i>&nbsp; Nuevo articulo</button>
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" onclick="nuevoServicio()">Nuevo servicio</a></li>
                </ul>
            </div>

            <button type="button" onclick="exportarBienExcel();" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&ensp;Exportar</button>

            <a href="util/formatos/formato_bien.xlsx" style="border-radius: 0px;" class="btn btn-danger w-md"><i class=" fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;Descargar formato</a>

            <a href="#" style="border-radius: 0px;" class="fileUpload btn btn-success w-md" ><i class=" fa fa-download" style="font-size: 18px;"></i>
                <i><input name="file" id="file"  type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="upload" onchange='' ></i>&ensp;Importar
            </a>

            <input type="hidden"  id="secret" value="" />

            <script>
                $(function () {
                    $(":file").change(function () {
                        //validar que la extension sea .xls
                        var nombreArchivo = $(this).val().slice(12);
                        var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();
//                        console.log(nombreArchivo,extension);
                        if (extension != "xlsx") {
                            $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'La extensión del excel tiene que ser .xlsx');
                            return;
                        }

                        if (this.files && this.files[0]) {
                            var reader = new FileReader();
                            reader.onload = imageIsLoaded;
                            reader.readAsDataURL(this.files[0]);
                            $fileupload = $('#file');
                            $fileupload.replaceWith($fileupload.clone(true));
                        }
                    });
                });
                function imageIsLoaded(e) {
                    console.log(e);
                    $('#secret').attr('value', e.target.result);
                    importBien();
                }
                ;
            </script>            
        </div>       

    </div>
    <br>
    <div class="panel panel-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style='text-align:center;'>Código</th>
                        <th style='text-align:center;'>Grupo Articulo</th>
                        <th style='text-align:center;'>Descripción</th>
                        <th style='text-align:center;'>Tipo Unidad</th>
                        <th style='text-align:right;'>Largo</th>
                        <th style='text-align:right;'>Ancho</th>
                        <th style='text-align:right;'>Alto</th>
                        <th style='text-align:right;'>Peso Comercial</th> 
                        <th style='text-align:center;'>Estado</th>
                        <th style='text-align:center;'>Acc.</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div style="clear:left">
        <p><b>Leyenda:</b>&nbsp;&nbsp;
            <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
            <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
<!--            <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
            <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo -->
        </p>
    </div>

    <div id="modalBien" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Importar Bien</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label id="lb_empresa">Empresa *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="cboEmpresa" id="cboEmpresa" class="select2">
                                </select>
                                <span id='msj_empresa' class="control-label"
                                      style='color:red;font-style: normal;' hidden></span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label id="lb_empresa">Resultado</label>
                            <div id="resultado" style="overflow-y: auto;">

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

    <div id="modalBajaActivo"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalBajaActivo" aria-hidden="true" style="display: none;">       
        <div class="modal-dialog">            
            <div class="modal-content">               
                <div class="modal-header">                      
                    <h4 class="modal-title">Seleccione el periodo para dar de baja al bien</h4>         
                </div>                     
                <div class="modal-body">     
                    <div class="row">  
                        <div class="form-group col-md-12">              
                            <label>Cuenta contable *</label>           
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                <select name="cboPlanContable" id="cboPlanContable" class="select2"></select>
                            </div>                 
                        </div>
                        <div class="form-group col-md-6">              
                            <label>Periodo *</label>           
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                <select name="cboPeriodoActivo" id="cboPeriodoActivo" class="select2"></select>  
                                <input  id="txtBienIdHidden" type="hidden" />
                            </div>                            
                        </div>    
                        <div class="form-group col-md-6">
                            <label>Fecha de baja *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaBaja">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-danger m-b-5" style="border-radius: 0px;" data-dismiss="modal">
                        <i class="fa fa-close"></i>&ensp;Cerrar
                    </button>  
                    <button type="button" class="btn btn-success m-b-5" style="border-radius: 0px;"  onclick="generarBajaActivoFijo()">
                        <i class="fa fa-send-o"></i>&ensp;Dar de baja
                    </button>   
                </div>
            </div>         
        </div>     
    </div>
</div>
<script src="vistas/com/bien/bien_listar.js"></script>