
<div class="wraper container-fluid">
    <h3 id="titulo" class="title"></h3>

    <div class="row">

        <input type="hidden" id="tipoInterfaz" value="<?php echo $_GET['tipoInterfaz']; ?>" />

        <div id="datosImpresion" style="background-color: #dfd" hidden="true">
        </div>

        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="input-group m-t-10" style="width: 101%;">    
                <button class="btn btn-info btn-block" onclick="nuevoForm()">
                    <i class=" fa fa-plus-square-o"></i> Nuevo                
                </button>
            </div>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-10">   
            <div class="input-group m-t-10">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="padding-left: 0px;">                                    
                    <!--<div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">-->
                    <div id="cabeceraBuscador" name="cabeceraBuscador" >
                        <div class="input-group" id="divBuscador">                                
                            <span class="input-group-btn">
                                <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">
                                    <i class="caret"></i>
                                </a>
                                <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable">
                                    <li>
                                        <div id="divTipoDocumento">
                                            <div class="form-group col-md-2">
                                                <label style="color: #141719;">Tipo doc.</label>
                                            </div>
                                            <div class="form-group col-md-10">
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboDocumentoTipo" id="cboDocumentoTipo" class="select2" multiple>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Serie</label>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txtSerie" name="txtSerie" class="form-control" value="" maxlength="45">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" id="txtNumero" name="txtNumero" class="form-control" value="" maxlength="45">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Persona</label>
                                        </div>
                                        <div class="form-group col-md-10">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboPersona" id="cboPersona" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group col-md-2">
                                            <label  style="color: #141719;">Fecha</label>
                                        </div>
                                        <div class="form-group col-md-10">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="inicioFechaEmision">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="finFechaEmision">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li id="liEstadoNegocio" hidden>
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Estado Negocio</label>
                                        </div>
                                        <div class="form-group col-md-10">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <select name="cboEstadoNegocio" id="cboEstadoNegocio" class="select2">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group col-md-2">
                                            <label style="color: #141719;">Moneda</label>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <select name="cboMoneda" id="cboMoneda" class="select2">
                                                </select>
                                            </div>
                                        </div>
                                        <div style="float: right">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger"> <i class="fa fa-close"></i>Cancelar</button>
                                                <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDesplegable()" class="btn btn-purple"> <i class="fa fa-search"></i>Buscar</button>                                        
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </span>
                            <input type="text" id="txtBuscar" name="txtBuscar" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarCriteriosBusqueda()">                                
                            <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegable2">

                            </ul>

                        </div>                           
                    </div>
                </div>
                <!--</div>-->
                <!--<div class="col-md-2" style="padding-left: 0px;padding-right: 0px;">-->
                <div class="input-group-btn" style="padding-left: 0px;padding-right: 0px;">
                    <div class="btn-toolbar" role="toolbar"  style="float: right" >
                        <div class="input-group-btn">
                            <a type="button" class="btn btn-success" onclick="actualizarBusqueda()" title="Actualizar resultados de búsqueda"><i class="ion-refresh"></i></a>
                        </div>

                        <div class="input-group-btn" style="padding-left: 10px;">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="ion-gear-a"></i>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a  onclick="actualizarBusquedaExcel()" title="">
                                        <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp; Exportar excel
                                    </a>
                                </li>
                                <li>
                                    <a onclick="descargarFormato(0)" title="">
                                        <i class="ion-archive"></i>&nbsp;&nbsp;Descargar Formato
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="">
                                        <div class="fileUpload" style="background-color: transparent; border: transparent; padding-left: 0px;    padding-right: 0px;">
                                            <span  style="color: black;"><i class="ion-upload m-r-5"></i>Importar Excel</span>

                                            <input type="file" id="file" name="file" class="upload"
                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                   onchange="$('#fileInfo').html($(this).val().slice(12));"/>
                                        </div>
                                        <b class="" id="fileInfo"><span id="lblDoc"></span></b>
                                        <input type="hidden" id="secretFile" value="" />
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--</div>-->
            </div>
        </div>
    </div>
    <div class="row">
        <div hidden="hidden" class="col-lg-3 col-md-3 col-sm-3">
            <div class="input-group m-t-10">
                <div class="panel panel-default p-0  m-t-20">
                    <div class="panel-body p-0">
                        <div class="list-group no-border" id="divDocumentoTipos">

                        </div>
                    </div>
                </div>

                <div class="panel panel-default p-0  m-t-20">
                    <div class="panel-body p-0">
                        <div class="list-group no-border mail-list" id="divPersonasMayorMovimientos">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default m-t-20 p-t-0" style="padding-left: 10px;padding-bottom: 1px;">

            <table id="datatable" class="table table-small-font table-striped table-hover"  style="width: 1205px">
                <thead id="theadListado">

                </thead>
            </table>
            <br>
            <div style="clear:left">
                <p id="divLeyenda">
                    <!--                            <b>Leyenda:</b>&nbsp;&nbsp;
                                        <i class='fa fa-print' style='color:green;'></i> Imprimir &nbsp;&nbsp;&nbsp;
                                        <i class='fa fa-ban' style='color:#cb2a2a;'></i> Anular &nbsp;&nbsp;&nbsp;
                                        <i class='fa fa-eye' style='color:#1ca8dd;'></i> Visualizar &nbsp;&nbsp;&nbsp;
                                        <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Aprobar &nbsp;&nbsp;&nbsp;
                                        <i class='ion-android-share' style="color:#E8BA2F;"></i> Ver Relación &nbsp;&nbsp;&nbsp;
                                        <i class="ion-ios7-keypad" style="color:#615ca8"></i> Asignar códigos únicos &nbsp;&nbsp;&nbsp;-->
                </p>
            </div>
        </div>
    </div>
</div>
<!--modal para el detalle del movimiento-->
<div id="modalDetalleDocumento"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full"> 
        <div class="modal-content" style="background-color: #eeeeee"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <!--                <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModal"></h4> -->
                <span class="label label-warning group text-uppercase" id="tituloVisualizacion"></span>
                <span class="label label-info group text-uppercase" id="fechaVisualizacion"></span>
                <span class="label label-success group text-uppercase" id="tcVisualizacion"></span>
                <span class="label label-danger group text-uppercase" id="tipoDuaVisualizacion"></span>

            </div>
            <div class="modal-body" style="padding-bottom: 0px"> 
                <div class="row"> 
                    <div class="col-lg-12"> 
                        <ul id="tabsCif" class="nav nav-tabs"> 
                            <li class="active"> 
                                <a href="#general" data-toggle="tab" aria-expanded="true"> 
                                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                    <span class="hidden-xs">General</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#cifDolares" data-toggle="tab" aria-expanded="false" onclick="actualizarAnchosTablaColumnas('dtCifDolares')"> 
                                    <span class="visible-xs"><i class="fa fa-dollar"></i></span> 
                                    <span class="hidden-xs">CIF Dólares</span> 
                                </a> 
                            </li> 
                            <li class=""> 
                                <a href="#cifSoles" data-toggle="tab" aria-expanded="false" onclick="actualizarAnchosTablaColumnas('dtCifSoles')"> 
                                    <span class="visible-xs"><i class="fa fa-money"></i></span> 
                                    <span class="hidden-xs">CIF Soles</span> 
                                </a> 
                            </li> 
                        </ul> 
                        <div class="tab-content"> 
                            <div class="tab-pane active" id="general"> 
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <table id="datatable2" class="table table-striped table-bordered" id="formularioCopiaDetalle">
                                                <thead id="theadDetalle">

                                                </thead>
                                                <tbody id="tbodyDetalle">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12">
                                                <label>COMENTARIO </label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <textarea disabled="true" type="text" id="txtComentario" name="txtComentario" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <table id="dtResumen" class="table table-bordered">
                                            <tbody>
                                                <tr style="background-color: #f9f9f9">
                                                    <td style="padding:2px; font-weight: bold;">Valor FOB</td>
                                                    <td id="celFob" style='text-align:right;padding:2px; font-weight: bold;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Flete</td>
                                                    <td id="celFlete" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Seguro</td>
                                                    <td id="celSeguro" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Comisión bancaria</td>
                                                    <td id="celComisionBanc" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Otros costos</td>
                                                    <td id="celOtrosCostos" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr style="background-color: #f9f9f9">
                                                    <td style="padding:2px; font-weight: bold;">Valor CIF</td>
                                                    <td id="celCif" style='text-align:right;padding:2px; font-weight: bold;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Ad-valorem</td>
                                                    <td id="celAdValorem" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr style="background-color: #f9f9f9">
                                                    <td style="padding:2px; font-weight: bold;">B.Imponible para IGV</td>
                                                    <td id="celImponible" style='text-align:right;padding:2px; font-weight: bold;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">IGV 18%</td>
                                                    <td id="celIgv" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">Percepción</td>
                                                    <td id="celPercepcion" style='text-align:right;padding:2px;'></td>
                                                </tr>
                                                <tr style="background-color: #f9f9f9">
                                                    <td style="padding:2px; font-weight: bold;">Total</td>
                                                    <td id="celTotal" style='text-align:right;padding:2px; font-weight: bold;'></td>
                                                </tr>
                                                <tr>
                                                    <td style='border-left-color: #FFFFFF; border-right-color: #FFFFFF; padding:2px;'>&nbsp;</td>
                                                    <td style='border-right-color: #FFFFFF; padding:2px;'>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:2px">THC</td>
                                                    <td id="celThc" style='text-align:right;padding:2px;'></td>
                                                </tr>
<!--                                                <tr>
                                                    <td style="padding:2px">Flete SUNAT</td>
                                                    <td id="celFleteSunat" style='text-align:right;padding:2px;'></td>
                                                </tr>-->
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div> 
                            <div class="tab-pane" id="cifDolares"> 
                                <div class="row">
                                    <table id="dtCifDolares" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style='text-align:center;'>#</th>
                                                <th style='text-align:center;'>Producto</th>
                                                <th style='text-align:center;'>Valor FOB</th>
                                                <th style='text-align:center;'>Flete</th> 
                                                <th style='text-align:center;'>Seguro</th>
                                                <th style='text-align:center;'>Com. Bancaria</th>
                                                <th style='text-align:center;'>Otros costos</th>
                                                <th style='text-align:center;'>Valor CIF</th>
                                                <th style='text-align:center;'>Ad valorem</th>
                                                <th style='text-align:center;'>B. Imponible IGV</th>
                                                <th style='text-align:center;'>IGV 18%</th>
                                                <th style='text-align:center;'>Total</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" style="text-align:right"></th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div> 
                            <div class="tab-pane" id="cifSoles"> 
                                <div class="row">
                                    <table id="dtCifSoles" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style='text-align:center;'>#</th>
                                                <th style='text-align:center;'>Producto</th>
                                                <th style='text-align:center;'>Valor FOB</th>
                                                <th style='text-align:center;'>Flete</th> 
                                                <th style='text-align:center;'>Seguro</th>
                                                <th style='text-align:center;'>Com. Bancaria</th>
                                                <th style='text-align:center;'>Otros costos</th>
                                                <th style='text-align:center;'>Valor CIF</th>
                                                <th style='text-align:center;'>Ad valorem</th>
                                                <th style='text-align:center;'>B. Imponible IGV</th>
                                                <th style='text-align:center;'>IGV 18%</th>
                                                <th style='text-align:center;'>Total</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" style="text-align:right"></th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div> 
                        </div> 
                    </div> 
                </div> <!-- End row -->
            </div> 
            <div class="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">                       
                        <!--<div class="alert alert-success fade in" id="alertEmail">-->


                        <div class="input-group m-t-10" id="alertEmail">
                            <span class="input-group-btn">
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Tipo envío <span class="caret"></span></button>                            
                                    <ul class="dropdown-menu" role="menu" id="ulObtenerEmail">
<!--                                            <li><a href="#" onclick="obtenerEmail('enviarCorreoPDF');"><i class="ion-email" style="color: #33b86c"></i>&nbsp;&nbsp; Correo y PDF</a></li>
                                        <li><a href="#" onclick="obtenerEmail('enviarPDF');"><i class="fa fa-file-pdf-o" style="color: #cb2a2a"></i>&nbsp;&nbsp; Con PDF</a></li>                                
                                        <li><a href="#" onclick="obtenerEmail('enviarCorreo')"><i class="ion-email" style="color: #1ca8dd"></i>&nbsp;&nbsp; Correo</a></li>                                -->
                                    </ul>
                                </div>
                            </span>                        
                            <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" value="nleon@imaginatecperu.com" placeholder="email1@dominio.com;email2@dominio.com">
                            <span class="input-group-btn">                                
                                <button type="button" class="btn btn-success" onclick="enviarCorreoXAccion()" id="idDescripcionBoton"><i class="ion-email" ></i></button>
                            </span>
                        </div>


                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <div class="checkbox pull-left" style="margin-top: 15px;">
                            <!--                            <label class="cr-styled">
                                                            <input onclick="getUserEmailByUserId()" type="checkbox" name="checkIncluirSelf" id="checkIncluirSelf">
                                                            <i class="fa"></i> Incluir mi e-mail
                                                        </label>-->
                        </div>
                    </div>


                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <!--                        <form target="_blank" action="vistas/com/movimiento/movimiento_pdf.php" method="post" id="formPDF" name="formPDF">                                
                                                    <input type="hidden" name="documentoIdHidden" id="documentoIdHidden" value=""/>
                                                    <input type="hidden" name="correoHidden" id="correoHidden" value=""/>
                                                </form>-->
                        <!--<div class="alert alert-info fade in" style="float: right">-->
                        <div class="input-group m-t-10" style="float: right">
                            <!--<a class="btn btn-purple" onclick="editarComentarioDocumento()"><i class="fa fa-save"></i> Guardar</a>-->
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                        <!--</div>-->  
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<div id="modalDetalleDocumentoDUA"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title text-dark text-uppercase" id="tituloVisualizacionModalDUA"></h4> 
            </div>
            <div class="modal-body" style="padding-bottom: 0px"> 
                <div class="row">

                    <div class="col-lg-12">
                        <div class="row" style="box-shadow: 0 0px 0px">
                            <!--                            <div class="portlet-heading">
                                                            <h3 id="nombreDocumentoTipo" class="portlet-title text-dark text-uppercase">
                            
                                                            </h3>                                
                                                            <div class="clearfix"></div>
                                                        </div>-->
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
                                    <table id="datatableDUA" class="table table-striped table-bordered">
                                        <thead id="theadDetalleDUA">

                                        </thead>
                                        <tbody id="tbodyDetalleDUA">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group col-lg-12 col-md-12">
                                    <label>COMENTARIO </label>
                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea type="text" id="txtComentarioDUA" name="txtComentarioDUA" class="" value="" maxlength="500" style="height: auto;width: 100%;display: block;padding: 6px 12px; border-color:#ddd" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div> 

                </div>
            </div> 
            <div class="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">                    
                        <div class="input-group m-t-10">

                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

                    </div>


                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <div class="input-group m-t-10" style="float: right">
                            <!--<a class="btn btn-purple" onclick="editarComentarioDocumento()"><i class="fa fa-save"></i> Guardar</a>-->
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<div id="modalDocumentoRelacionado"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--> 
                <h4 class="modal-title">Documentos relacionados
                    <a onclick="prepararModalDocumentoACopiar()" style="float: right;color:#55acee" title="Abrir bandeja de documentos a relacionar"><i class="fa fa-plus-circle">&nbsp;&nbsp;&nbsp;</i></a>
                </h4> 
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

<div id="modalReporteAtenciones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalReporteAtenciones" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modalReporteAtencionesTitulo">Atenciones: </h4>
            </div>
            <div class="modal-body">

                <table id="tableReporteAtenciones" style="width: 100%;" >
                    <tr class="gang-name-1">
                        <td colspan="2">Solicitud 001 - 0000001</td>
                    </tr>
                    <tr class="members blips">
                        <td class="atencion-td">Documento 1 </td>
                        <td class="atencion-td">
                    <tr class="gang-name-2">
                        <td colspan="1">Sub Cotizacion 001 - 0000001</td>
                    </tr>
                    <tr class="members blips">
                        <td class="atencion-td">Sub Documento 1 </td>
                    </tr>
                    </td>

                    </tr>
<!--                    <tr class="members blips">-->
<!--                        <td class="atencion-td">Documento 3</td>-->
<!--                        <td class="atencion-td">Documento 4</td>-->
                    <!--                    </tr>                    -->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="cerrarModalReporteAtenciones()"><i class="fa fa-remove"></i><span> Cerrar</span></button>

                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="modalAsignarCodigoUnico"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:80%;"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title" id="tituloModalAsignarCodigoUnico">Asignar códigos únicos</h4> 
            </div> 
            <div class="modal-body" style="padding-bottom: 5px;"> 
                <div id="divAgregarBU">
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Productos únicos disponibles</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <select name="cboBienUnico" id="cboBienUnico" class="select2" onchange="onChangeComboBienUnico()"></select>
                            </div>
                        </div>                    
                        <!--                </div>
                        
                                        <div class="row">-->
                        <div class="form-group col-md-4">                        
                            <label class="cr-styled" style="text-align: left;">
                                <input type="checkbox" id="chkHasta" name="chkHasta" onclick="onClickCheckHasta()">
                                <i class="fa"></i> 
                                <b>Hasta </b>
                            </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">                            
                                <div class="col-md-9" style="padding-left: 0px;padding-right: 0px;padding-top: 6px;">
                                    <input type="text" id="txtBienUnicoDescripcion" name="txtBienUnicoDescripcion" class="form-control" value="" maxlength="300" readonly="true">
                                </div>
                                <div class="col-md-3" style="padding-left: 0px;padding-right: 0px;padding-top: 6px;">
                                    <input type="number" id="txtBienUnicoNumero" name="txtBienUnicoNumero" class="form-control" value="" maxlength="7">
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12" style="padding-top: 12px; padding-bottom: 12px;">
                            <div class="col-md-5">&nbsp;</div>
                            <div class="col-md-2">
                                <button type="button" name="btnGuardar" id="btnGuardar" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"  onclick="agregarBienUnico()"><i class="fa fa-plus-square-o"></i>&nbsp;Agregar</button>
                            </div>
                            <div class="col-md-5">&nbsp;</div>
                        </div>
                    </div>
                </div>

                <!--<div class="panel panel-body">-->
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="table">
                            <!--<table id="dataTableBienUnicoDetalle" class="table table-striped table-bordered">-->                                    
                            <div id="dataList">
<!--                                    <thead>
                                <tr>
                                    <th style="text-align:center">N°</th>
                                    <th style="text-align:center">Prod. Único</th>
                                    <th style="text-align:center">Producto</th>
                                    <th style="text-align:center">Estado</th>
                                    <th style="text-align:center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>-->
                            </div>
                            <!--</table>-->
                        </div>
                    </div>
                </div>
                <!--</div>-->

                <div style="clear:left" id="divLeyendaBU">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar detalle&nbsp;&nbsp;&nbsp;
                        <!--<i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar detalle&nbsp;&nbsp;&nbsp;-->
                    </p>
                </div>

            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-info m-b-5" id="idGuardarBienUnico" style="border-radius: 0px; margin-bottom: 0px" onclick="guardarBienUnicoDetalle(1)" ><i class="fa fa-save"></i>&ensp;Guardar</button> 
                <button type="button" class="btn btn-success m-b-5" id="idEnviarBienUnico" style="border-radius: 0px;"  onclick="enviarBienUnicoDetalle()"  ><i class="fa fa-send-o"></i>&ensp;Finalizar</button> 
                <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</button> 
            </div> 
        </div> 
    </div>
</div>

<!--modal para el detalle del movimiento-->
<div id="modalPlanillaImportar"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; overflow: scroll;">
    <div class="modal-dialog modal-full"> 
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title text-dark text-uppercase" id="tituloModal">Planilla de importación y Precio de costo</h4> 
            </div>
            <div class="modal-body" style="padding-bottom: 0px"> 
                <ul  class="nav nav-tabs"> 
                    <li class="active" id="liPlanillaImp"> 
                        <a href="#tabPlanillaImp" data-toggle="tab" aria-expanded="true" onclick="actualizarAnchosTablaColumnas('dataTablePlanillaImp')"> 
                            <span class="visible-xs"></span> 
                            <span class="hidden-xs"> Planilla de importación</span> 
                        </a> 
                    </li> 
                    <li class="" id="liPrecioCostoDolar"> 
                        <a href="#tabPrecioCostoDolar" data-toggle="tab" aria-expanded="false" onclick="actualizarAnchosTablaColumnas('dataTablePrecioCostoDolar')"> 
                            <span class="visible-xs"></span> 
                            <span class="hidden-xs"> Precio de costo en dólares</span> 
                        </a> 
                    </li> 
                    <li class="" id="liPrecioCostoSoles"> 
                        <a href="#tabPrecioCostoSoles" data-toggle="tab" aria-expanded="false" onclick="actualizarAnchosTablaColumnas('dataTablePrecioCostoSoles')"> 
                            <span class="visible-xs"></span> 
                            <span class="hidden-xs"> Precio de costo en soles</span> 
                        </a> 
                    </li> 
                </ul>

                <div class="tab-content" style="padding-left: 0px;padding-right: 0px;"> 
                    <div class="tab-pane active" id="tabPlanillaImp"> 

                        <div class="row">                    
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table id="dataTablePlanillaImp" class="table table-striped table-bordered" style="width: 2000px">
                                    <!--Proveedor	Fecha	Concepto	US$ Valor venta	IGV	Total Compra	T/C	S/. Valor venta	IGV	Total Compra-->	

                                    <thead>                                
                                        <tr>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2"></th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Proveedor</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Fecha</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">Concepto</th>
                                            <th style='text-align:center; vertical-align: middle;' colspan="3">Dólares</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">T/C</th>
                                            <th style='text-align:center; vertical-align: middle;' colspan="3">Soles</th>
                                            <th style='text-align:center; vertical-align: middle;' rowspan="2">S/N</th>
                                        </tr>
                                        <tr>
                                            <th style='text-align:center;'>US$ Valor venta</th>
                                            <th style='text-align:center;'>IGV</th>
                                            <th style='text-align:center;'>Total Compra</th>
                                            <th style='text-align:center;'>S/. Valor venta</th>
                                            <th style='text-align:center;'>IGV</th>
                                            <th style='text-align:center;'>Total Compra</th>
                                        </tr>

                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Totales:</th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                            <th> </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabPrecioCostoDolar"> 
                        <div class="row">                    
                            <div class="col-md-12 col-sm-12 col-xs-12" id="divCostoDolar">
                                <table id="dataTablePrecioCostoDolar" class="table table-striped table-bordered">
                                </table>
                            </div>
                        </div>                     
                    </div>
                    <div class="tab-pane" id="tabPrecioCostoSoles">                          
                        <div class="row">                    
                            <div class="col-md-12 col-sm-12 col-xs-12" id="divCostoSoles">
                                <table id="dataTablePrecioCostoSoles" class="table table-striped table-bordered">
                                </table>
                            </div>
                        </div>                         
                    </div>
                </div>
            </div> 
            <div class="modal-footer">                                  
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                        
                        <div class="input-group m-t-10" style="float: right">                            
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>                 
                        </div> 
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

<!--Modal para seleccionar los EAR.-->    
<div id="modalEar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">  
    <div class="modal-dialog modal-lg">
        <div class="modal-content">         
            <div class="modal-header">            
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                <h4 class="modal-title text-dark" id="tituloModalEar"></h4>      
            </div>          
            <div class="modal-body" style="padding-bottom: 0px">  
                <div class="row">
                    <div class="table col-md-12">        
                        <div class="table">                      
                            <table id="datatableEar" class="table table-striped table-bordered">     
                                <thead>                              
                                    <tr>                                      
                                        <th style='text-align:center;'></th>
                                        <th style='text-align:center;'>Agente aduanero</th>
                                        <th style='text-align:center;'>EAR Número</th>
                                        <th style='text-align:center;'>Motivo</th>
                                        <!--<th style='text-align:center;'>Moneda</th>-->
                                        <th style='text-align:center;'>Monto</th>
                                        <!--<th style='text-align:center;'>Estado</th>-->
                                        <th style='text-align:center;'>Fecha solic.</th>                      
                                    </tr>                             
                                </thead>                         
                            </table>                   
                        </div>            
                    </div>                           
                </div>                   
            </div>
            <div style="clear:left">
                <p>
                    <label>* Seleccione el EAR para relacionar con la DUA y generar los costos unitarios.</label>
                </p>
            </div>              
            <div class="modal-footer">  
                <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cancelar</a> 
                <a class="btn btn-success"  onclick="relacionarDuaEar()"  ><i class="fa fa-save"></i> Relacionar</a>    
            </div>     
        </div>        
    </div>       
</div>     
<!--Fin modal EAR-->

<!--Relacionar dua con documento de compra-->
<div id="modalDocumentoRelacion" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">      
        <div class="modal-content">          
            <div class="modal-body">          
                <div class="row">                       
                    <div class="col-lg-12">                   
                        <div id="divBuscadorCopia">                        
                            <div class="form-group input-group">                        
                                <span class="input-group-btn">                             
                                    <a type="button" data-toggle="dropdown" class="dropdown-toggle btn btn-effect-ripple btn-primary" href="#">      
                                        <i class="caret"></i>                        
                                    </a>                                      
                                    <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none;width: 1052px;" id="ulBuscadorDesplegableCopia">      
                                        <li>                                            
                                            <div id="divTipoDocumento">          
                                                <div class="form-group col-md-2">                    
                                                    <label style="color: #141719;">Tipo doc.</label>           
                                                </div>                                         
                                                <div class="form-group col-md-10">                
                                                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                                                        <select name="cboDocumentoTipoM" id="cboDocumentoTipoM" class="select2" multiple>     
                                                        </select>                                                 
                                                    </div>                                            
                                                </div>                                            
                                            </div>                                  
                                        </li>                                    
                                        <li>                                     
                                            <div class="form-group col-md-2">    
                                                <label style="color: #141719;">Serie</label>    
                                            </div>                                             
                                            <div class="form-group col-md-5">                   
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">      
                                                    <input type="text" id="txtSerie" name="txtSerie" class="form-control" value="" maxlength="45">      
                                                </div>                                       
                                            </div>                                     
                                            <div class="form-group col-md-5">          
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">           
                                                    <input type="text" id="txtNumero" name="txtNumero" class="form-control" value="" maxlength="45">    
                                                </div>                                   
                                            </div>                                
                                        </li>                             
                                        <li>                                    
                                            <div class="form-group col-md-2">                   
                                                <label style="color: #141719;">Persona</label>     
                                            </div>                                        
                                            <div class="form-group col-md-10">           
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">      
                                                    <select name="cboPersonaM" id="cboPersonaM" class="select2" multiple>  
                                                    </select>                                                
                                                </div>                                     
                                            </div>                                
                                        </li>                                      
                                        <li>                                           
                                            <div class="form-group col-md-2">                
                                                <label  style="color: #141719;">Fecha Emisión</label>   
                                            </div>                                           
                                            <div class="form-group col-md-10">           
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">       
                                                    <div class="row">                                              
                                                        <div class="form-group col-md-6">                                    
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaEmisionInicio">    
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>          
                                                            </div>                                                         
                                                        </div>                                          
                                                        <div class="form-group col-md-6">                      
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaEmisionFin">        
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>       
                                                            </div>                                                        
                                                        </div>                                       
                                                    </div>                                      
                                                </div>                                      
                                            </div>                                      
                                        </li>                                       
                                        <li>                                         
                                            <div class="form-group col-md-2">            
                                                <label  style="color: #141719;">Fecha Vencimiento</label>       
                                            </div>                                          
                                            <div class="form-group col-md-10">                 
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">          
                                                    <div class="row">                                          
                                                        <div class="form-group col-md-6">                      
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaVencimientoInicio">         
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>                  
                                                            </div>                                                    
                                                        </div>                                                      
                                                        <div class="form-group col-md-6">                                  
                                                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">         
                                                                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="dpFechaVencimientoFin"> 
                                                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>              
                                                            </div>                                                           
                                                        </div>                            
                                                    </div>                                           
                                                </div>                                               
                                            </div>                                       
                                        </li>                                     
                                        <li>                                           
                                            <div style="float: right">                        
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >   
                                                    <button id="btnBusqueda" type="button" href="#bg-info" onclick="limpiarBuscadores()" class="btn btn-danger">
                                                        <i class="fa fa-close"></i> Cancelar
                                                    </button>                             
                                                    <button id="btnBusqueda" type="button" href="#bg-info" onclick="buscarDocumentoRelacionPorCriterios()" class="btn btn-purple">
                                                        <i class="fa fa-search"></i> Buscar
                                                    </button>                              
                                                </div>                                    
                                            </div>                                      
                                        </li>                                       
                                        <li>                                        
                                        </li>                                        
                                    </ul>                                 
                                </span>                                  
                                <input type="text" id="txtBuscarCopia" name="txtBuscarCopia" data-toggle="dropdown" class="dropdown-toggle form-control" placeholder="Buscar" onkeyup="buscarDocumentoRelacion()">
                                <ul class="dropdown-menu extended fadeInUp animated nicescroll" tabindex="5001" style="overflow: hidden; outline: none; width: 100%;" id="ulBuscadorDesplegableCopia2">      
                                </ul>                         
                                </input>                            
                                <span class="input-group-btn">           
                                    <a type="button" class="btn btn-success" onclick="actualizarBusquedaDocumentoRelacion()" title="Actualizar resultados de búsqueda">
                                        <i class="ion-refresh"></i></a>                             
                                </span>                            
                            </div>                      
                        </div>                      
                    </div>                       
                </div>                      
                <div class="row">                    
                    <table id="dtDocumentoRelacion" class="table table-striped table-bordered" style="width: 100%">                     
                        <thead>                        
                            <tr>                            
                                <th style='text-align:center;'>F. creación</th>                
                                <th style='text-align:center;'>F. emisión</th>             
                                <th style='text-align:center;'>Tipo documento</th>           
                                <th style='text-align:center;'>Persona</th>                    
                                <th style='text-align:center;'>S/N</th>                      
                                <th style='text-align:center;'>S/N Doc.</th>                  
                                <th style='text-align:center;'>F. venc.</th>                   
                                <th style='text-align:center;'>M</th>                             
                                <th style='text-align:center;'>Total</th>                         
                                <th style='text-align:center;'>Usuario</th>                       
                                <th style='text-align:center;'></th>                               
                            </tr>                    
                        </thead>                    
                    </table>              
                </div>                 
            </div>                
            <div class="modal-footer" style="padding-bottom:  0px;padding-top: 10px;clear:left">            
                <div class="form-group">                    
                    <div class="col-md-6" style="text-align: left;">        
                        <p><b>Leyenda:</b>&nbsp;&nbsp;                          
                            <!--<i class="fa fa-download" style="color:#04B404;"></i> Agregar documento a copiar&nbsp;&nbsp;-->       
                            <i class="fa fa-arrow-down" style="color:#1ca8dd;"></i> Agregar documento a relacionar             
                        </p>                    
                    </div>                  
                    <div class="col-md-6">          
                        <button type="button" class="btn btn-danger" onclick="cerrarModalCopia()"><i class="fa fa-close"></i> Cerrar</button>       
                    </div>             
                </div>             
            </div>             
        </div>           
    </div>      
</div>    
<!--FIN Relacionar dua con documento de compra-->

<form target="_blank" action="script/almacen/qrDocumento.php" method="post" id="formDocumentoQR" name="formDocumentoQR">                                
    <input type="hidden" name="documentoIdHidden" id="documentoIdHidden" value=""/>
</form>

<script src="vistas/com/movimiento/movimiento_listar_dua.js"></script>