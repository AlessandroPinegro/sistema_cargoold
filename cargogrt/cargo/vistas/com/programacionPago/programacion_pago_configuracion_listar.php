<div class="page-title">
            <h3 id="titulo"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="#" style="border-radius: 0px;" class="btn btn-info w-sm m-b-5" onclick="nuevo()">
                            <i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Nuevo
                        </a>&nbsp;&nbsp;
                    </div>
                </div>
                
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style='text-align:center;'>Descripcion</th>
                                    <th style='text-align:center;'>Proveedor</th>
                                    <th style='text-align:center;'>Grupo de productos</th>
                                    <th style='text-align:center;'>Comentario</th>
                                    <th style='text-align:center;'>Porcentajes</th>
                                    <th style='text-align:center;'>Estado</th>
                                    <th style='text-align:center;'>Acc.</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la información &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
                        <i class="ion-checkmark-circled" style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                        <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo 
                    </p>
                </div>
            </div>
        </div>

        <script src="vistas/com/programacionPago/programacion_pago_configuracion_listar.js"></script>