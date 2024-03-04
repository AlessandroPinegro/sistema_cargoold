<div class="page-title">
    <h3 class="title">Periodos</h3>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="row">
            <div class="form-group col-md-12">
                <a href="#" style="border-radius: 0px;" class="btn btn-info w-sm m-b-5" onclick="nuevo()">
                    <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Nuevo
                </a>&nbsp;&nbsp;
                <a href="#" style="border-radius: 0px;" class="btn btn-success w-sm m-b-5" onclick="preparaGeneraPeriodoAnio()">
                    <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Generar por año
                </a>&nbsp;&nbsp;
            </div>
        </div>
        <div class="panel panel-body" id="muestrascroll">
            <div class="row" id="scroll">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="table">
                        <div id="dataList">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="modalGenerarPeriodoAnio" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Generar periodos por año</h4>
                    </div>
                    <div  class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Año *</label>
                                    <select name="cboAnio" id="cboAnio" class="select2">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="btnGenerar" class="btn btn-info" onclick="generarPeriodoAnio()"><i class="fa fa-save" value="" >&nbsp;&nbsp;</i>Generar</button>
                            <button type="button" id="btnSalirModal" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <!--<i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la información &nbsp;&nbsp;&nbsp;-->
                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
                <!--<i class="ion-checkmark-circled" style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;-->
                <!--<i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo  &nbsp;&nbsp;&nbsp;-->
                <i class='ion-locked' style='color:red;'></i> Cerrar periodo para administración &nbsp;&nbsp;&nbsp;
                <!--<i class='ion-locked' style='color:#1ca8dd;'></i> Cerrar periodo para contabilidad &nbsp;&nbsp;&nbsp;-->
                <i class='ion-unlocked' style='color:green;'></i> Abrir periodo
                <i class='ion-unlocked' style='color:purple;'></i> Reabrir periodo
            </p>
        </div>
    </div>
</div>

<script src="vistas/com/periodo/periodo_listar.js"></script>