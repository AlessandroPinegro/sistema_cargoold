<div id="window" class="wraper container-fluid">
    <style type="text/css">
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet">
                <div class="portlet-heading">
                    <div class="row">
                        <div class="col-md-10" style="margin-top: -12px; margin-left: -32px;">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-top: -12px;">
                                <h3 class="text-dark text-uppercase">
                                    <select id="cbo0" name="cbo0" class="select2" disabled="true">
                                        <option value="-1">&nbsp; Solicitud de viatico</option>
                                    </select>
                                </h3>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">                                    
                                <div id="contenedorNumeroDiv" style="display: block;">
                                    <h4 id="contenedorNumero"><input type="text" id="txt_1844" name="txt_1844" class="form-control" value="" maxlength="6" placeholder="Número" style="text-align: right;"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: -10px;"></div>
                <div id="portlet1" class="panel-collapse collapse in" style="margin-top: -20px;">
                    <div class="portlet-body">                            

                        <!--PARTE DINAMICA-->
                        <div id="contenedorDocumentoTipo">
                            <form id="formularioDocumentoTipo" method="post" class="form" 
                                  enctype="multipart/form-data;charset=UTF-8">
                                <div class="row"></div>
                                <div class="form-group col-md-4">
                                    <label>Colaborador *</label>
                                    <span class="divider"></span> <a onclick="cargarPersona();">
                                        <i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar colaborador" style="color: #CB932A;"></i></a><span class="divider"></span>
                                    <a onclick="loaderComboPersona()">
                                        <i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i>
                                    </a>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select id="cbo1" name="cbo1" class="select2">
                                            <option value="-1">&nbsp; Seleccione colaborador</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha solicitada*</label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" id="fecha" name="fecha" placeholder="dd/mm/yyyy" class="form-control">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"></div>
                                <div class="form-group col-md-8">
                                    <label>Comentario</label>
                                    <textarea type="text" id="txt_comentario" name="txt_comentario" class="form-control" value="" maxlength="500"></textarea>
                                </div>

                        </div>
                        <!--FIN PARTE DINAMICA-->

                        <div id="contenedorDetalle" style="min-height: 170px; height: 360px;">
                            <div class="col-md-12">
                                <div class="row" style="height: auto;">
                                    <!--detalle-->
                                    <table id="datatable" class="table table-striped table-bordered"  style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th style='text-align:center;'>Concepto</th>
                                                <th style='text-align:center;'>Comentario</th>
                                                <th style='text-align:center;'>Importe</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Alimentación</td>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="number" id="txt2" name="txt2" class="form-control" value="" style="text-align:right"></td>
                                            </tr>
                                            <tr>
                                                <td>Hospedaje</td>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="number" id="txt2" name="txt2" class="form-control" value="" style="text-align:right"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="number" id="txt2" name="txt2" class="form-control" value="" style="text-align:right"></td>
                                            </tr>
                                            <tr>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="text" id="txt2" name="txt2" class="form-control" value=""></td>
                                                <td><input type="number" id="txt2" name="txt2" class="form-control" value="" style="text-align:right"></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row text-center m-t-10 m-b-10">
                                    <!--TOTALES-->
                                    <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-6" style="float: right;">
                                        <div id="contenedorTotalDiv">
                                            <h4 id="contenedorTotal">
                                                <input type="number" id="txt1" name="txt1" class="form-control" value="" style="text-align:right" onkeyup="if (this.value.length > 13) {
                                                            this.value = this.value.substring(0, 13)
                                                        }">                        
                                            </h4>
                                            <median class="text-uppercase">Total</median>
                                            <median class="text-uppercase" id="simTotal">S/.</median></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >      
                                <button style="float: right;" type="button" id="btnEnviar" name="btnEnviar" class="btn btn-purple w-sm m-b-5" 
                                        style="border-radius: 0px;" onclick="cargarPantallaListar()" >
                                    <i class="fa fa-send-o"></i>&ensp;Aprobar
                                </button>
                                <button style="float: right;" type="button" id="btnEnviar" name="btnEnviar" class="btn btn-success w-sm m-b-5" 
                                        style="border-radius: 0px;" onclick="cargarPantallaListar()" >
                                    <i class="fa fa-send-o"></i>&ensp;Finalizar
                                </button>
                                <button style="float: right;" type="button" id="btnEnviar" name="btnEnviar" class="btn btn-info w-sm m-b-5" 
                                        style="border-radius: 0px;" onclick="cargarPantallaListar()" >
                                    <i class="fa fa-send-o"></i>&ensp;Guardar
                                </button>                            
                                <a style="float: right;" href="#" class="btn btn-danger m-b-5" id="btnCancelar" onclick="cargarPantallaListar()" 
                                   style="border-radius: 0px;">
                                    <i class="fa fa-close"></i>&ensp;Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div> <!-- end col -->
    </div> <!-- End row -->

    <script src="vistas/com/rendirCuenta/solicitud_viatico_form.js"></script>


</div>