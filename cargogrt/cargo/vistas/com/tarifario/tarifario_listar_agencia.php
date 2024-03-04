<html lang="es">

<head>
    <style type="text/css" media="screen">
        @media screen and (max-width: 1000px) {
            #scroll {
                width: 1000px;
            }

            #muestrascroll {
                overflow-x: scroll;
            }
        }

        #datatable td {
            vertical-align: middle;
        }

        .sweet-alert button.cancel {
            background-color: rgba(224, 70, 70, 0.8);
        }

        .sweet-alert button.cancel:hover {
            background-color: #E04646;
        }

        .sweet-alert {
            border-radius: 0px;
        }

        .sweet-alert button {
            -webkit-border-radius: 0px;
            border-radius: 0px;
        }

        .popover {
            max-width: 100%;
        }

        th {
            white-space: nowrap;
        }

        .alignRight {
            text-align: right;
        }

    </style>
</head>

<body>
    <div class="page-title">
        <h3 id="titulo" class="title"></h3>
    </div>
    <div class="row">
        <div class="panel panel-default" style="background: none !important;border: none !important;">
            <div class="row">
                <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    <button type="button" value="enviar" name="env" id="env" class="btn btn-info w-md" onclick="openModal();"><i class=" fa fa-plus-square-o" aria-hidden="true"></i>&ensp;Nuevo</button>&nbsp;&nbsp;
                </div>
                <div class="col-lg-10">
                    <div class="portlet">
                    <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portlet-heading bg-info m-b-0" onclick="colapsarBuscador()" id="idPopover" title="" data-toggle="popover" data-placement="top" data-content="" data-original-title="Criterios de búsqueda" style="padding-top: 13px;padding-bottom: 13px;cursor: pointer; cursor: hand;">
                                    <h3 class="portlet-title"><i class="fa fa-filter"></i>Filtrar por</h3>
                                    <div class="portlet-widgets">
                                        <span class="divider"></span>
                                        <!--<a data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="cerrarPopover()">
                                                <i class="ion-minus-round"></i>                                                
                                        </a>-->                                        
                                    </div>
                                    <div class="clearfix">
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div id="bg-info" class="panel-collapse collapse in">
                            <div class="portlet-body">
                                <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                                <div class="row">
                           
                                    <input type="text" hidden id="txtCajaId" value="<?php echo $id ?>">
                                    <div class="form-group col-md-6">
                                        <label>Agencia</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboOrigen2" id="cboOrigen2" class="select2" onchange="onchangeOrigen()">
                                            </select>
                                        </div>                                        
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Zona de reparto</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboDestino2" id="cboDestino2" class="select2"  onchange="onchangeDestino()">
                                            </select>
                                        </div>                                        
                                    </div>

                                    <!-- <div class="form-group col-md-4 ">
                                        <label>Moneda *</label>
                                        <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select name="cboMoneda2" id="cboMoneda2" class="select2"  onchange="onchangeMoneda()">
                                            </select>
                                        </div>                                        
                                    </div>                                 -->
                                </div>                                
                            </div>


                            <div class="modal-footer">
                            <a href="util/formatos/formato_tarifario_zona.xlsx" style="border-radius: 0px;" class="fileUpload btn btn-danger"><i class="ion-archive" style="font-size: 18px;"></i> &ensp;Descargar Formato</a>
                                 <a href="#" style="border-radius: 0px;" class="fileUpload btn btn-success w-md"><i class="fa fa-upload" style="font-size: 18px;"></i>
                                    <i><input name="file" id="fileImport" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="upload" onchange=''></i>&ensp;Importar
                                </a>
                                <a href="#" style="border-radius: 0px;" class="btn btn-success w-md" onclick="exportarTarifario()"><i class="fa fa-download" aria-hidden="true" style="font-size: 18px;"></i>&nbsp;Exportar</a>&nbsp;&nbsp;
                                <input type="hidden" id="secret" value="" />
                                <button type="button" onclick="buscarTarifario()" value="enviar" name="env" id="env" class="btn btn-info w-md" style="border-radius: 0px;"><i class="ion-search"></i>&nbsp;&nbsp;Buscar</button>&nbsp;&nbsp;
                                <!--<button type="button" data-toggle="collapse" data-parent="#accordion1" href="#bg-info" onclick="buscarPorCuenta()" value="enviar" class="btn btn-purple"><i></i>&ensp;Buscar</button>&nbsp;&nbsp;-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row"></div>

        <div class="panel panel-body">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!--<div class="table" id="muestrascroll">-->
                <div id="datatable2" id="scroll">

                </div>
                <!--</div>-->
            </div>
        </div>

        <!--                <div style="clear:left">
                                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                                        <i class="fa fa-eye" ></i> Ver detalle
                                    </p>
                                </div>-->
    </div>
    </div>

    <div id="modal-detalle-documentos-servicios" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Verificación de stock</h4>
                </div>
                <div class="modal-body">
                    <div class="table">
                        <div id="dataList">
                            <table id="datatableDocumentoPorCuenta" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style='text-align:center;'>F. Creacion</th>
                                        <th style='text-align:center;'>F. Emisión</th>
                                        <th style='text-align:center;'>Tipo documento</th>
                                        <th style='text-align:center;'>Persona</th>
                                        <th style='text-align:center;'>Serie</th>
                                        <th style='text-align:center;'>Número</th>
                                        <th style='text-align:center;'>F. Venc.</th>
                                        <th style='text-align:center;'>Estado</th>
                                        <th style='text-align:center;'>Cantidad</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" data-dismiss="modal"><i class="fa fa-close"></i>&ensp;Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal new tarifario -->

    <div id="addTarifario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="cerrarModalTarifario()" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="modalTitulo">Nuevo Tarifario Agencia</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                    <div class="row">
                
                        <input type="text" hidden id="txtCajaId" value="<?php echo $id ?>">
                        <div class="form-group col-md-4 ">
                            <label>Agencia *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="cboOrigen" id="cboOrigen" class="select2" onchange="onchangeOrigen()">
                                </select>
                            </div>
                            <i id='msj_origen' style='color:red;font-style: normal;' hidden></i>
                        </div>

                        <div class="form-group col-md-4 ">
                            <label>Zona de reparto *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="cboDestino" id="cboDestino" class="select2"  onchange="onchangeDestino()">
                                </select>
                            </div>
                            <i id='msj_destino' style='color:red;font-style: normal;' hidden></i>
                        </div>

                        <div class="form-group col-md-4 ">
                            <label>Moneda *</label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="cboMoneda" id="cboMoneda" class="select2"  onchange="onchangeMoneda()">
                                </select>
                            </div>
                            <i id='msj_moneda' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    
                    </div>
                    <div class="row">
                        <br>
                        <div class="form-group col-md-4">
                            <label>TARIFA SOBRE * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txtsobre" name="txtsobre" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj0' style='color:red;font-style: normal;' hidden></i>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TARIFA (0 - 50 Kg.) * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt1k" name="txt1k" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj1' style='color:red;font-style: normal;' hidden></i>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TARIFA (51- 100  Kg.) * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt2k" name="txt2k" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj2' style='color:red;font-style: normal;' hidden></i>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TARIFA (101- 250  Kg.) * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt3k" name="txt3k" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj3' style='color:red;font-style: normal;' hidden></i>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TARIFA (251- 500  Kg.) * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt4k" name="txt4k" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj4' style='color:red;font-style: normal;' hidden></i>
                        </div>
                        <div class="form-group col-md-4">
                            <label>TARIFA (+ 500  Kg.) * </label>
                            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="txt5k" name="txt5k" class="form-control" required="" aria-required="true" value="" maxlength="45" />
                            </div>
                            <i id='msj5' style='color:red;font-style: normal;' hidden></i>
                        </div>
                    </div>                                                            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger m-b-5" style="border-radius: 0px; margin-bottom: 0px" onclick="cerrarModalTarifario()">
                            <i class="fa fa-close"></i>&ensp;Cerrar
                        </button>
                        <button type="button" name="btnGuardar" id="btnGuardar" class="btn btn-success m-b-5" style="border-radius: 0px; margin-bottom: 0px" onclick="guardarTarifario()">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="modalImport" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Importar tarifario zona</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <!-- <div class="form-group col-md-12">
                    <label id="lb_empresa">Empresa *</label>
                    <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <select name="cboEmpresa" id="cboEmpresa" class="select2">
                    </select>
                    <span id='msj_empresa' class="control-label" style='color:red;font-style: normal;' hidden></span>
                    </div>
                </div> -->
                <div class="form-group col-md-12">
                    <label id="lb_empresa">Resultado</label>
                    <div id="resultado" style="overflow-y: auto;">

                    </div>
                </div>
                </div>

                <div class="modal-footer">
                <!--<a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="cargarModal()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Nuevo</a>-->
                <button type="button" id="btnImportar" class="btn btn-info" onclick="importar()"><i class="fa fa-save" value="">&nbsp;&nbsp;</i>Importar</button>
                <button type="button" id="btnSalirModal" class="btn btn-danger" data-dismiss="modal"><i class="fa ion-android-close">&nbsp;&nbsp;</i>Cancelar</button>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <script>
        $(function() {
          $("#fileImport").change(function() {
            //validar que la extension sea .xls
            var nombreArchivo = $(this).val().slice(12);
            var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();
            //                        console.log(nombreArchivo,extension);
            if (extension != "xlsx") {
              $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'La extensión del excel tiene que ser .xlsx');
              return;
            }

            if (this.files && this.files[0]) {
              var reader = new FileReader();
              reader.onload = imageIsLoaded;
              reader.readAsDataURL(this.files[0]);
              $fileupload = $('#fileImport');
              $fileupload.replaceWith($fileupload.clone(true));
            }
          });
        });

        function imageIsLoaded(e) {
          $('#secret').attr('value', e.target.result);
          importTarifario();
        };
      </script>

    <script src="vistas/com/reporte/reporte.js"></script>
    <script src="vistas/com/tarifario/tarifario_listar_agencia.js"></script>
</body>

</html>