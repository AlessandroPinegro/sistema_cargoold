<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<div class="row">
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <a href="#" style="border-radius: 0px;" class="btn btn-success w-md" onclick="prepararModalGenerarDepreciacion()"><i class="fa fa-recycle" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Depreciación mensual</a>
        <!--<a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="preparaGenerar()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Generar libro</a>-->
        <!--<a href="#" style="border-radius: 0px;" class="btn btn-purple w-md" onclick="preparaGenerarResumen()"><i class=" fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Generar resumen</a>-->
        <br><br>
        <div class="panel panel-body">
            <div class="row">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style='text-align:center;'>Año</th>
                            <th style='text-align:center;'>Excel</th>
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
    <div id="modalGenerarDepreciacion" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generar depreciacion</h4>
                </div>
                <div  class="modal-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2"></div>
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <div class="form-group">
                                <label>Periodo *</label>
                                <select name="cboPeriodo" id="cboPeriodo" class="select2">                                   
                                </select>
                            </div>
                        </div> 
                        <div class="col-lg-2 col-md-2 col-sm-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnGenerar" class="btn btn-info" onclick="generarDepreciacion()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Generar</button>
                        <button type="button" id="btnSalirModal" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script src="vistas/com/contabilidadActivoFijo/activo_fijo.js"></script>
