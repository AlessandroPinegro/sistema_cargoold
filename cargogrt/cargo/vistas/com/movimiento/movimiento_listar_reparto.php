<div>
    <!-- <h3 id="titulo" class="title"></h3> -->

    <div class="row">

        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <div id="datosImpresion" style="background-color: #dfd" hidden="true"></div>

        <div class="col-md-12">

            <div id="bg-info" class="panel-collapse collapse in">
                <div class="portlet-body">
                    <div style="display: flex;justify-content: space-between;">
                        <div class="input-group col-md-4">
                            <div class="form-group col-md-6">
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select name="cboAgenciaOrigen" id="cboAgenciaOrigen" class="select2" placeholder="Seleccione origen">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6"></div>
                        </div>
                        <div class="input-group col-md-4">
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <h3 id='despachoSerieNumero' style='margin-top: 0px;margin-bottom: 0px;'></h3>
                            </div>
                        </div>
                        <div class="input-group col-md-2">

                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaEmision">
                            </div>
                        </div>
                        <div class="input-group col-md-2">
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <select name="cboPeriodo" id="cboPeriodo" class="select2"></select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="input-group-addon"><i class="fa fa-bus"></i></span>
                                <select name="cboBus" id="cboBus" class="select2" placeholder="Seleccione Bus"></select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select name="cboConductor" id="cboConductor" class="select2" placeholder="Seleccione conductor"></select>
                            </div>
                        </div>
                        <div class="form-group col-md-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="col-md-12">
    <div class="row">
        <div>
            <div id="dataListaManifiestos">
            </div>
            <div>
                <div class="row">
                    <div style="display: flex;justify-content: flex-end;align-items: center;">
                        <label>Guia de remisión transportista <a onclick="obtenerSerieCorrelativoGuia()"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a></label>&nbsp;

                        <div class="form-group col-md-1">
                            <input type="text" class="form-control" placeholder="serie" id="serieGuia" maxlength="4" disabled="">
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control" placeholder="número" id="numeroGuia" maxlength="7">
                        </div>
                        <button type="button" href="#bg-info" style="width: 155px;" onclick="GenerarDespacho();" value="enviar" id="generar" class="btn btn-info"><i class="fa fa-send-o"></i> Generar
                        </button>&nbsp;
                    </div>
                </div>

                <br>
                <div class="row" style="display: flex;justify-content: flex-end;">
                    <div>
                        <button type="button" href="#bg-info" style="width: 158px;" onclick="imprimirDocumentoGuia();" value="enviar" class="btn btn-info" id="imprimeGuia" disabled="true"><i class="fa fa-print"></i> Imprimir GRT's
                        </button>&nbsp;
                    </div>
                    <div>
                        <button type="button" style="width: 158px;" href="#bg-info" onclick="imprimirDocumentoManifiesto();" value="enviar" class="btn btn-info" id="imprimeManifiesto" disabled="true"> <i class="fa fa-print"></i> Imprimir Manifiesto(s)&nbsp;&nbsp;&nbsp;&nbsp;
                        </button>&nbsp;
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger m-b-5" style="width: 158px;" href="#bg-info" onclick="cerrar();" value="enviar"><i class="fa fa-close"></i> cerrar
                        </button>&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<script src="vistas/com/movimiento/movimiento_listar_reparto.js"></script>