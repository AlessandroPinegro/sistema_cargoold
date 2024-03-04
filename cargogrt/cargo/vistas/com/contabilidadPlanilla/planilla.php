<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<div class="row">
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <a href="#" style="border-radius: 0px;" class="btn btn-success w-md" onclick="prepararImportarExcel()"><i class="fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Importar excel</a>
        <!--<a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="preparaGenerar()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Generar libro</a>-->
        <!--<a href="#" style="border-radius: 0px;" class="btn btn-purple w-md" onclick="preparaGenerarResumen()"><i class=" fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Generar resumen</a>-->
        <br><br>
        <div class="panel panel-body">
            <div class="row">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style='text-align:center;'>Periodo</th>
                            <th style='text-align:center;'>Excel</th>
                            <th style='text-align:center;'>Tipo archivo</th>
                            <th style='text-align:center;'>Usuario</th>
                            <th style='text-align:center;'>Fecha</th>
                            <th style='text-align:center;'>Estado</th>
                            <th style='text-align:center;'>Acciones</th>
                            <!--<th style='text-align:center;'>Opciones</th>-->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br>
    </div>
    <div id="modalImportar" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Importar excel</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Periodo *</label>
                                <select name="cboPeriodo" id="cboPeriodo" class="select2">                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <select name="cboTipoDocumento" id="cboTipoDocumento" class="select2">
<!--                                    <option value="1">Planilla</option>
                                    <option value="2">CTS</option>
                                    <option value="3">Gratificación</option>                                  -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <a href="#" style="border-radius: 0px;" class="fileUpload btn btn-success col-md-12" ><i class=" fa fa-download" style="font-size: 18px;"></i>
                            <i><input name="file" id="file"  type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="upload" onchange='' ></i>&ensp;
                            <span id="lblImportarArchivo">Seleccione archivo excel</span>
                        </a>
                        <input type="hidden"  id="secret" value="" />
                        <input type="hidden"  id="archivoId" value="" />                        
                        <br>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnGenerar" class="btn btn-info" onclick="importarArchivo()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Generar</button>
                        <button type="button" id="btnSalirModal" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalGenerarExcel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generar excel</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Año *</label>
                                <select name="cboAnioExcel" id="cboAnioExcel" class="select2">
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019" selected>2019</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Mes *</label>
                                <select name="cboMesExcel" id="cboMesExcel" class="select2">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnGenerarExcel" class="btn btn-info" onclick="generarExcel()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Generar</button>
                        <button type="button" id="btnSalirModalExcel" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalGenerarExcelResumen" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generar excel de resumen de kardex</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Año *</label>
                                <select name="cboAnioExcelResumen" id="cboAnioExcelResumen" class="select2">
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnGenerarExcel" class="btn btn-info" onclick="generarResumen()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Generar</button>
                        <button type="button" id="btnSalirModalExcelResumen" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="vistas/com/contabilidadPlanilla/planilla.js"></script>
