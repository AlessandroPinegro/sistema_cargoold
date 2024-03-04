
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">-->
<div class="row">
    <div class="panel panel-default">
        <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> Tipo de venta</h3>
        </div>
        <div class="panel-body">

            <div class="row">                    
                <div class="form-group col-md-6">
                    <label>C&oacute;digo *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtCodigo" name="txtCodigo" class="form-control" required="" aria-required="true" value="" maxlength="45">
                        <i id='msjCodigo' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div> 
                <div class="row"> 
                    <div class="col-md-2">
                        <label> </label>
                        <div class="checkbox" style="margin: 0px;">
                            <label class="cr-styled">
                                <input onclick="actualizarCheckbox()" type="checkbox" name="checkNotaCredito" id="checkNotaCredito">
                                <i class="fa"></i> Es nota cr&eacute;dito
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label> </label>
                        <div class="checkbox" style="margin: 0px;">
                            <label class="cr-styled">
                                <input onclick="" type="checkbox" name="checkValorVentaInafecto" id="checkValorVentaInafecto">
                                <i class="fa"></i> Valor de venta es INAFECTO en el Registro de Ventas
                            </label>
                        </div>
                    </div>
                </div>


            </div>       

            <div class="row">
                <div class="form-group col-md-12">
                    <label>Descripción *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtDescripcion" name="txtDescripcion" class="form-control" required="" aria-required="true" value="" maxlength="500">
                        <i id='msjDescripcion' style='color:red;font-style: normal;' hidden></i>
                    </div>
                </div> 
            </div>


            <div class="row">

                <div class="form-group col-md-6">
                    <label>Código Tipo de Exportación *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="text" id="txtCodigoExportacion" name="txtCodigoExportacion" class="form-control" required="" aria-required="true" value="" maxlength="500">
                        <i id='msjCodigoExportacino' style='color:red;font-style: normal;' hidden></i>
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

            <br>

            <form  id="frm_motivo"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                <div class="row" id="divTabla">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active" id="liCaracteristica">
                            <a href="#tabCaracteristicas" data-toggle="tab" aria-extended="true">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">Características</span>
                            </a>
                        </li>
                        <li class="" id="liDocumento">
                            <a href="#tabDocumentosRequeridos" data-toggle="" aria-extended="">
                                <span class="visible-xs"><i class="ion-person-stalker"></i></span>
                                <span class="hidden-xs">Documentos Requeridos</span>
                            </a>
                        </li>
                        <li class="" id="liOtros">
                            <a href="#tabOtros" data-toggle="tab" aria-extended=""> 
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
                            <div class="row" id="rowOtros"> 
                                <div class="table col-md-12">                        
                                    <table class="table table-striped table-bordered">
                                        <tbody id="tbodyOtros">

                                        </tbody>
                                    </table>                    
                                </div>        
                            </div>
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
<script src="vistas/com/contabilidadVentaTipo/venta_tipo_form.js" type="text/javascript"></script>