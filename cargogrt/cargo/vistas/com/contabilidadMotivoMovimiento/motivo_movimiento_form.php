<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> Motivo Movimiento</h3>
        </div>
        <div class="panel-body">

            <div class="row">                    
                <div class="form-group col-md-6">
                    <label>Motivo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtMotivo" name="txtMotivo" class="form-control" required="" aria-required="true" value="" maxlength="45">
                        <i id='msjMotivo' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>                          

                <div class="form-group col-md-6">
                    <label>Código SUNAT *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboCodigoSunat" id="cboCodigoSunat" class="select2">
                        </select>
                        <i id='msjCodigoSunat' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>          




            <div class="row">                    
                <div class="form-group col-md-6">
                    <label>Descripción *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" required="" aria-required="true" value="" maxlength="500">
                        <i id='msjDescripcion' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>   

                <div class="form-group col-md-6">
                    <label>Nombre Corto *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtNombreCorto" name="txtNombreCorto" class="form-control" required="" aria-required="true" value="" maxlength="45">
                        <i id='msjNombreCorto' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label>Motivo a Generar *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboMotivoGenerar" id="cboMotivoGenerar" class="select2">
                        </select>
                        <i id='msjMotivoGenerar' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Tipo Motivo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboTipoMotivo" id="cboTipoMotivo" class="select2">
                        </select>
                        <i id='msjTipoMotivo' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Tipo C&aacute;lculo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboTipoCalculo" id="cboTipoCalculo" class="select2">
                        </select>
                        <i id='msjTipoCalculo' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Tipo Cambio *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboTipoCambio" id="cboTipoCambio" class="select2">
                        </select>
                        <i id='msjTipoCambio' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Estado Asociado *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboEstadoAsociado" id="cboEstadoAsociado" class="select2">
                        </select>
                        <i id='msjEstadoAsociado' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>



            <div class="row"> 

                <div class="form-group col-md-6">
                    <label>Grupo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboGrupo" id="cboGrupo" class="select2">
                        </select>
                        <i id='msjGrupo' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Estado *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                                
                        <select name="cboEstado" id="cboEstado" class="select2">
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        <span id='msjEstado' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div>
            </div>    

            <div class="row">
                <div class="form-group col-md-8">
                    <label>Reg. Libro Electr&oacute;nico *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select name="cboRegLibroElectronico" id="cboRegLibroElectronico" class="select2">
                        </select>
                        <i id='msjRegLibroElectronico' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label>Código SUNAT (Libro)*</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtCodigoSunatLibro" name="txtCodigoSunatLibro" class="form-control" required="" aria-required="true" value="" maxlength="45">

                        <i id='msjCodigoSunatLibro' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div>
            </div>




            <br>

            <form  id="frm_motivo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                <div class="row">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#tabCaracteristicas" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">Características del Motivo</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#tabDocumentosRequeridos" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="ion-person-stalker"></i></span>
                                <span class="hidden-xs">Documento Requerido por el Motivo</span>
                            </a>
                        </li>
                        <li class="disabled">
                            <!--<a href="#tabOtros" data-toggle="tab" aria-expanded="false"> enable tab-->
                            <a href="#tabOtros" aria-expanded="false">
                                <span class="visible-xs"><i class="ion-pricetags"></i></span>
                                <span class="hidden-xs">Otros</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!--PESTAÑA CARACTERISTICAS-->
                        <div class="tab-pane active" id="tabCaracteristicas">
                            <div class="row" id="rowCaracteristicas"> 
                                <div class="table col-md-12">                        
                                    <table class="table table-striped table-bordered">
                                        <tbody id="tbodyCaracteristicas">

                                        </tbody>
                                    </table>                    
                                </div>        
                            </div>

                        </div>
                        <!--FIN PESTAÑA CARACTERISTICAS-->

                        <!--PESTAÑA DOCUMENTOS REQUERIDOS-->
                        <div class="tab-pane" id="tabDocumentosRequeridos">
                            <div class="row" id="rowDocumentosTipos"> 
                                <div class="table col-md-12">                        
                                    <table class="table table-striped table-bordered">
                                        <tbody id="tbodyDocumentosTipos">

                                        </tbody>
                                    </table>                    
                                </div>        
                            </div>
                        </div>
                        <!--FIN PESTAÑA DOCUMENTOS REQUERIDOS-->

                        <!--PESTAÑA OTROS-->
                        <div class="tab-pane" id="tabOtros">                                       

                        </div>
                        <!--FIN PESTAÑA OTROS-->

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
                                style="border-radius: 0px;" onclick="enviar()">
                            <i class="fa fa-send-o"></i>&ensp;Enviar
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="vistas/com/contabilidadMotivoMovimiento/motivo_movimiento_form.js"></script>