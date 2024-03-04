<html lang="es">
    <head>
        <style type="text/css" media="screen">
            #datatable td{
                vertical-align: middle;
            }
            .sweet-alert button.cancel {
                background-color: rgba(224, 70, 70, 0.8);
            }
            .sweet-alert button.cancel:hover {
                background-color:#E04646;
            }
            .sweet-alert {
                border-radius: 0px; 
            }
            .sweet-alert button {
                -webkit-border-radius: 0px; 
                border-radius: 0px; 
            }
            .popover{
                max-width: 100%; 
            }
            th { white-space: nowrap; }
            .alignRight { text-align: right; }

            input[type=number]::-webkit-inner-spin-button,  
            input[type=number]::-webkit-outer-spin-button {   
                -webkit-appearance: none;     
                margin: 0;    
            } 
            .columnAlignCenter{
                text-align: center;
            }    
        </style>
    </head>
    <body>
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div  class="portlet" >
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="portlet-heading bg-purple m-b-0" 
                                         onclick="colapsarBuscador()"
                                         id="idPopover" title="" data-toggle="popover" 
                                         data-placement="top" data-content="" 
                                         data-original-title="Criterios de búsqueda"
                                         style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">
                                        <div class="portlet-widgets">
                                            <a onclick="exportarLibroDiario('excel');" title="">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>&nbsp;                                            
                                            <a id="loaderBuscarVentas" onclick="loaderBuscarVentas()">
                                                <i class="ion-refresh"></i>
                                            </a>
                                            <span class="divider"></span>
                                            <!--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>
                                            </a>-->
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="bg-info" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label>Libro:</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboLibro" id="cboLibro" class="select2"> </select>
                                            </div>
                                        </div>                                       
                                        <div class="form-group col-md-3 ">
                                            <label>Periodo</label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPeriodoInicio" id="cboPeriodoInicio" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <!--                                        <div class="form-group col-md-3 ">                                            
                                                                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <select name="cboPeriodoFin" id="cboPeriodoFin" class="select2">
                                                                                        </select>
                                                                                    </div>
                                                                                </div>-->
                                    </div>                                 
                                    <div class="modal-footer" style="padding-right: 0px;padding-left: 0px;">                                        
                                        <div style="float:left" >
                                            <button type="button" onclick="nuevoAsiento();" class="btn btn-primary w-md" style="border-radius: 0px;">
                                                <i class="fa fa-plus-circle" style="font-size: 18px;"></i>&nbsp;Registrar asiento
                                            </button>
                                            <button type="button" onclick="abrirModalAperturaCierre();" class="btn btn-inverse w-md" style="border-radius: 0px;">
                                                <i class="fa fa-gears" style="font-size: 18px;"></i>&nbsp;Generar asiento de apertura y cierre
                                            </button>

                                        </div>
                                        <button type="button" onclick="exportarLibroDiario('excel');" value="Exportar" name="env" id="env" class="btn btn-info w-md" style="border-radius: 0px;"><i class=" fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;Exportar excel</button>  
                                        <button type="button" onclick="exportarLibroDiario('txt');" value="Exportar" name="env" id="env" class="btn btn-success w-md" style="border-radius: 0px;"><i class="fa fa-file-text" style="font-size: 18px;"></i>&nbsp;Exportar txt</button> 
                                        <button type="button" href="#bg-info" onclick="buscarLibroDiario(1)" value="enviar" class="btn btn-purple"><i class="ion-android-search" style="font-size: 18px;"></i>&nbsp;Buscar</button> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"></div>
                <div class="panel panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="dataList" class="table">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style='text-align:center;'>Número</th>
                                        <th style='text-align:center;'>Item</th>
                                        <th style='text-align:center;'>Cuenta</th>
                                        <th style='text-align:center;'>Relación</th>
                                        <th style='text-align:center;'>Registro</th>
                                        <th style='text-align:center;'>Documento</th>
                                        <th style='text-align:center;'>Monto</th>
                                        <th style='text-align:center;'>Área</th>
<!--                                        <th style='text-align:center;'>Sub-Total</th>
                                        <th style='text-align:center;'>IGV</th>
                                        <th style='text-align:center;'>Total</th>
                                        <th style='text-align:center;'>Acc.</th>-->
                                    </tr>
                                </thead>
                                <tbody id="tbodyDataTable"></tbody>
<!--                                <tfoot>
                                    <tr>
                                        <th colspan="8" style="text-align:right">Totales:</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--MODAL DETALLE DOCUMENTO-->
        <div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> 
                    </div>
                    <div class="modal-body" style="padding-bottom: 0px"> 
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="row" style="box-shadow: 0 0px 0px"> 
                                    <div id="portlet1" class="row">
                                        <div class="portlet-body" >
                                            <form  id="formularioDetalleDocumento"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8" style="min-height: 75px;height: auto;">
                                            </form>
                                        </div>

                                    </div>
                                </div> <!-- /Portlet -->
                            </div>
                            <div class="col-lg-12 ">
                                <div class="portlet" style="box-shadow: 0 0px 0px">
                                    <div id="portlet2" class="row">
                                        <div class="portlet-body">
                                            <div id="tabDistribucion">
                                                <ul id="tabsDistribucionMostrar"  class="nav nav-tabs nav-justified">
                                                    <li class="active">
                                                        <a href="#detalle" data-toggle="tab" aria-expanded="true" title="Detalle"> 
                                                            <span class="hidden-xs">Detalle del documento</span> 
                                                        </a> 
                                                    </li> 
                                                    <li> 
                                                        <a href="#distribucion" data-toggle="tab" aria-expanded="false" title="Distribución Contable"> 
                                                            <span class="hidden-xs">Distribución contable</span> 
                                                        </a> 
                                                    </li>
                                                </ul>
                                                <div id="div_contenido_tab" class="tab-content">
                                                    <div class="tab-pane active" id="detalle">
                                                        <table id="datatable2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalle">
                                                            </thead>
                                                            <tbody id="tbodyDetalle">
                                                            </tbody>
                                                        </table>
                                                    </div>                                            
                                                    <div class="tab-pane" id="distribucion" hidden="">
                                                        <table id="datatableDistribucion2" class="table table-striped table-bordered">
                                                            <thead id="theadDetalleCabeceraDistribucion">

                                                            </thead>
                                                            <tbody id="tbodyDetalleDistribucion">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12">
                                            <label>COMENTARIO </label>
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea type="text" id="txtComentario" name="txtComentario" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 

                        </div>
                    </div> 
                    <div class="modal-footer">                                  
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
                            <div class="input-group m-t-10" style="float: right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <!--FIN MODAL DETALLE  DOCUMENTO-->

        <!--MODAL DE DOCUMENTOS RELACIONADOS-->
        <div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Documentos relacionados</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div id="linkDocumentoRelacionado">

                        </div>
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button> 
                    </div> 
                </div> 
            </div>
        </div>
        <!--FIN MODAL DOCUMENTOS RELACIONADOS-->
        <!--MODAL EDITAR GLOSA-->
        <div id="modalEditarGlosa"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Editar glosa</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="row">
                            <label>Glosa *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea type="text" id="txtGlosaFormEdit" name="txtGlosaFormEdit" rows="5" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                            </div>
                            <input type="hidden" id="txtVoucherIdFormEdit"/>
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;&nbsp;Cerrar</button> 
                        <button type="button" class="btn btn-info"  onclick="guardarGlosa()"><i class="fa fa-save"></i>&nbsp;&nbsp;Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--MODAL ASIENTO CIERRE Y APERTURA-->
        <div id="modalGenerarAsientoAperturaCierre"  class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <h4 class="modal-title">Generar asiento de apertura y cierre</h4> 
                    </div> 
                    <div class="modal-body"> 
                        <div class="row">
                            <label>Ejercicio *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name='cboEjercicio' id='cboEjercicio' class='select2'></select>
                            </div>
                        </div>
                        <div class="row">
                            <label>Tipo asiento *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name='cboTipoAsiento' id='cboTipoAsiento' class='select2'></select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer"> 
                        <div class="col-md-3">
                            <button type="button" onclick="generarAsientoCierreApertura(0);" class="btn btn-success w-md" style="border-radius: 0px;"><i class=" fa fa-file-excel-o" style="font-size: 18px;"></i>&nbsp;Descarga pre visualización</button>  
                        </div>
                        <div class="col-md-9">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;&nbsp;Cerrar</button> 
                            <button type="button" class="btn btn-info"  onclick="generarAsientoCierreApertura(1)"><i class="fa fa-gears"></i>&nbsp;&nbsp;Generar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--MODAL DE REGISTRAR ASIENTO-->
        <div id="modalRegistrarAsiento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full"> 
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                        <div class="form-group col-md-5" style="font-size: 18px"><b>Registro de asiento contable</b></div>
                        <div class="form-group col-md-3">
                            <select name="cboLibroForm" id="cboLibroForm" class="select2"></select>
                        </div>
                        <div class="form-group col-md-3">
                            <select name="cboPeriodoForm" id="cboPeriodoForm" class="select2"></select>
                        </div>
                        <h4 class="modal-title">&nbsp;&nbsp;</h4>




                        <!--                        <div class="form-group col-md-3">
                                                    <label>Libro *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select name="cboLibroForm" id="cboLibroForm" class="select2"></select>
                                                        <input type="hidden" id="txtVoucherId"/>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Periodo *</label>
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <select name="cboPeriodoForm" id="cboPeriodoForm" class="select2"></select>
                                                    </div>
                                                </div>-->
                    </div> 
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Moneda *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name="cboMonedaForm" id="cboMonedaForm" class="select2"></select>
                                    <input type="hidden" id="txtVoucherId"/>
                                </div>
                            </div> 
                            <div class="form-group col-md-3">
                                <label>Fecha *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaDocumentoForm">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Tipo cambio *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" id="txtTipoCambioForm" class="form-control claseNumeroDecimal" style="text-align: right;" value="1" onkeyup="calcularTotalesAsiento();"/>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Monto debe *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" id="txtMontoDebe" class="form-control claseNumeroDecimal" style="text-align: right;" value="0" onkeyup='if (this.value.length > 13) {
                                                this.value = this.value.substring(0, 13)
                                            }
                                            ;
                                            onChangeDebeHaber(0);'/>
                                </div>
                            </div>                           

                            <div class="form-group col-md-2">
                                <label>Monto haber *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" id="txtMontoHaber" class="form-control claseNumeroDecimal" style="text-align: right;" value="0" onkeyup='if (this.value.length > 13) {
                                                this.value = this.value.substring(0, 13)
                                            }
                                            ;
                                            onChangeDebeHaber(1);'/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id="txtVoucherId"/>
                            <div class="form-group col-md-3">
                                <label>Cuenta contable *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                    <select name='cboCuentaContableLibroDiario' id='cboCuentaContableLibroDiario' class='select2' style='max-width: 300px;' onchange='onChangeCentroCosto(this.value);'></select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Centro de costo *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name='cboCentroCostoLibroDiario' id='cboCentroCostoLibroDiario' class='select2'></select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Persona</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name='cboPersonaLibroDiario' id='cboPersonaLibroDiario' class='select2' style='max-width: 300px;' onchange="onChangeCboPersona(this.value);" ></select>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Documento</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select name='cboDocumentoLibroDiario' id='cboDocumentoLibroDiario' class='select2'></select>
                                </div>
                            </div>
                        </div>          
                        <!--                            <div class="form-group col-md-4 pull-right">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                                                           
                                                            <div style="height: auto; float: left; margin-top: 0px;">   
                                                                <a onclick="agregarFilaAsiento({})" >                                       
                                                                    <b style="color: #797979">[&nbsp; Agregar una fila]&nbsp;&nbsp;</b> 
                                                                    <i class="fa fa-plus-square" style="color:#E8BA2F;" title="Agregar item"></i>
                                                                </a>                                     
                                                            </div>                           
                                                        </div>
                                    <button type="button" class="btn btn-info w-md" style="margin-top: 25px;" onclick="agregarFilaAsiento();"><i class="fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;Agregar</button>
                                                    </div>-->

                        <div class="row">                                   
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                <div style="height: auto; float: right; margin-top: 0px;">
                                    <input id="txtIndiceLineaAsientoEdita" type="hidden"/>
                                    <button id="btnCancelarEditarFila" type="button" class="btn btn-danger btn-rounded m-b-5" onclick="cancelarEdicionFilaAsiento();">Cancelar</button>
                                    <button type="button" class="btn btn-success btn-rounded m-b-5" onclick="agregarFilaAsiento();">Agregar</button>
                                </div>
                                <!--                                <div style="height: auto; float: right; margin-top: 0px;">   
                                                                    <a onclick="agregarFilaAsiento();" >                                       
                                                                        <b style="color: #797979">[&nbsp; Agregar una fila]&nbsp;&nbsp;</b> 
                                                                        <i class="fa fa-plus-square" style="color:#E8BA2F;" title="Agregar item"></i>
                                                                    </a>                                     
                                                                </div>                           -->
                            </div>                          
                        </div> 
                        <div class="row"> 
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="dataList" class="table">
                                    <table id="datatableForm" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th width ="5%"  style='text-align:center;'>#</th>
                                                <th width ="24%" style='text-align:center;'>Cuenta Contable</th>
                                                <th width ="10%" style='text-align:center;'>Centro Costo</th>
                                                <th width ="10%" style='text-align:center;'>Persona</th>
                                                <th width ="10%" style='text-align:center;'>Documento</th>
                                                <th width ="5%" style='text-align:center;'>F. Contabilización</th>
                                                <th width ="5%"  style='text-align:center;'>Monto dólares</th>                                                
                                                <th width ="12%" style='text-align:center;'>Debe</th>
                                                <th width ="12%" style='text-align:center;'>Haber</th>
                                                <th width ="5%"  style='text-align:center;'>T.C.</th>
                                                <th width ="5%"  style='text-align:center;'>Acción</th>
                                               <!--<th width ="5%" style='text-align:center;'>Conversión S/</th>-->
                                            </tr>
                                        </thead>
                                        <tbody id="tBodyDatatableForm"></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" style="text-align:right">Totales :</th>
                                                <th><p id="pTotalDolares" style="text-align: right"></p></th>
                                                <th><p id="pTotalDebe" style="text-align: right"></p></th>
                                                <th><p id="pTotalHaber"style="text-align: right"></p></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                           <!--<th><p id="pTotalConversion" style="text-align: right"></p></th>-->


                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 pull-right">
                                <label>Glosa *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea type="text" id="txtGlosaForm" name="txtGlosaForm" rows="3" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;&nbsp;Cerrar</button> 
                        <button id="btnEnviar" type="button" class="btn btn-info"  onclick="guardarAsiento()"><i class="fa fa-save"></i>&nbsp;&nbsp;Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--FIN MODAL REGISTRAR ASIENTO-->

        <script src="vistas/libs/imagina/js/inputmask2.js"></script>
        <script src="vistas/com/contabilidadLibros/libro_diario.js"></script>

    </body>
</html>


