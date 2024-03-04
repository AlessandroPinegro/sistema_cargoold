
<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="idEditar" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="cajaId" value="<?php echo $_GET['cajaId']; ?>" />
        <input type="hidden" id="idOcultar" value="<?php echo $_GET['ocultar']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?>  Apertura <span id="spFecha"></span></h3>
        </div>
        <div class="panel-body">
            <span id="msjFormGen" class="control-label" style="color:red;font-style: normal;" hidden></span>
            <!--            <ul class="nav nav-tabs nav-justified" > 
                            <li class="active">
                                <a href="#caja" data-toggle="tab" aria-expanded="true">
                                    <span class="visible-xs"><i class="ion-android-archive"></i></span>
                                    <span class="hidden-xs">Caja</span>
                                </a>
                            </li>
            
                            <li> 
                                <a href="#inventario" data-toggle="tab" aria-expanded="true"> 
                                    <span class="visible-xs"><i class="fa fa-tasks"></i></span> 
                                    <span class="hidden-xs">Inventario</span> 
                                </a> 
                            </li>
                        </ul>-->

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-color panel-info" style="border: 1px solid #3bc0c3;">
                        <div class="panel-heading"> 
                            <h3 class="panel-title" id="fechaCierre"></h3> 
                        </div> 
                        <div class="panel-body">
                            <div class="row" id="divDatosCierre">
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Saldo</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div style="text-align: center" class="widget-panel widget-style-1 bg-info">
                                            <i class="fa fa-money" style="padding-right: 10px "></i> 
                                            <h2 class="m-0 counter">S/ <span id="importeCierre"></span></h2>
                                            <br><br>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Comentario </label>
                                    <div class="widget-panel widget-style-1 bg-info" style="padding: 1px 60px 1px 1px;color: black;">
                                        <i class="fa fa-comments-o"></i>
                                        <textarea type="text" id="txtComentarioCierre" name="txtComentarioCierre" 
                                                  value="" maxlength="500" rows="2" placeholder="" disabled=""
                                                  style="height: auto;width: 100%;display: block;padding: 
                                                  6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="panel panel-color panel-success" style="border: 1px solid #33b86c;">
                        <div class="panel-heading"> 
                            <h3 class="panel-title">Apertura <span id="title"></span></h3> 
                        </div> 
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div>
                                        <label>Efectivo</label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div style="text-align: left; background-color: #73b891" class="widget-panel widget-style-1">
                                            <i class="fa fa-money" style="padding-right: 10px "></i>
                                            <div class="input-group">
                                                <span class="input-group-addon">S/</span>
                                                <input onchange="validarFormulario(this.value)" type="number" id="importeApertura" name="importeApertura" class="form-control"
                                                       value="0.00" style="text-align: right">
                                                <span class="input-group-addon" id="sugerido"></span>
                                            </div>
                                            <br><br>
                                            <span id='msjImporteApertura' class="control-label" style='color:white;font-style: normal;' hidden></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Comentario </label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="widget-panel widget-style-1" style="padding: 1px 60px 1px 1px;color: black; background-color: #73b891">
                                            <i class="fa fa-comments-o"></i> 
                                            <textarea type="text" id="txtComentario" name="txtComentario" 
                                                      value="" maxlength="500" rows="2" placeholder="Comentario"
                                                      style="height: auto;width: 100%;display: block;padding: 
                                                      6px 12px;transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="form-group col-md-12">
                    <a href="#" class="btn btn-danger m-b-5" id="btnCancelar" onclick="cargarPantallaListar()" 
                       style="border-radius: 0px;">
                        <i class="fa fa-close"></i>&ensp;Cancelar
                    </a>&nbsp;&nbsp;&nbsp;                               

                    <button type="button" id="btnEnviar" name="btnEnviar" class="btn btn-info w-sm m-b-5" 
                            style="border-radius: 0px;" onclick="guardar()">
                        <i class="fa fa-send-o"></i>&ensp;Enviar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="vistas/com/acCaja/apertura_caja_form.js"></script>