<html lang="es">
    <head>
        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet" />
        <link href="vistas/libs/imagina/assets/timepicker/bootstrap-datepicker.min.css" rel="stylesheet" />
    </head>

    <body>
        <div class="row">
            <div class="panel panel-default">
                <input type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
                <input type="hidden" id="op" value="<?php echo $_GET['winTitulo']; ?>" />
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $_GET['winTitulo']; ?> Tipo de Cambio</h3>
                </div>
                <div class="panel-body">
                    <form  id="frmSctr" method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Fecha *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input type="text" id="fecha" name="fecha" placeholder="dd/mm/yyyy" class="form-control" onchange="obtenerEquivalenciaSunat()">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <span id="msjFecha" class="control-label" style="color:red;font-style: normal;" hidden></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Moneda *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select id="cboMoneda" name="cboMoneda" class="select2">
                                        <option value="-1">&nbsp;</option>
                                    </select>
                                </div>
                                <span id="msjMoneda" class="control-label" style="color:red;font-style: normal;" hidden></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Equivalencia Compra *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-8">
                                        <input type="number" id="txtEquivalenciaCompra" name="txtEquivalenciaCompra" class="form-control" value="" style="text-align:right" onkeyup="if(this.value.length>13){this.value=this.value.substring(0,13)}">                                    
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="txtBase1" name="txtBase1" class="form-control" value="" disabled>                                                                            
                                    </div>
                                </div>                                
                                <span id="msjEquivalenciaCompra" class="control-label" style="color:red;font-style: normal;" hidden></span>
                            </div>                            
                            <div class="form-group col-md-6">
                                <label>Equivalencia Venta *</label>
                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-8">
                                        <input type="number" id="txtEquivalenciaVenta" name="txtEquivalenciaVenta" class="form-control" value="" style="text-align:right" onkeyup="if(this.value.length>13){this.value=this.value.substring(0,13)}">
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="txtBase2" name="txtBase2" class="form-control" value="" disabled>                                                                            
                                    </div>
                                </div>
                                <span id="msjEquivalenciaVenta" class="control-label" style="color:red;font-style: normal;" hidden></span>
                            </div>
                        </div>
                        <br>  
                        
                        <div class="row" id="divOpeEdita">
                            <div class="form-group col-md-12">
                                <a href="#" class="btn btn-danger m-b-5" id="btnCancelar" onclick="cargarPantallaListar()" 
                                   style="border-radius: 0px;">
                                    <i class="fa fa-close"></i>&ensp;Cancelar
                                </a>&nbsp;&nbsp;&nbsp;                               
                                <button type="button" id="btnEnviar" name="btnEnviar" class="btn btn-info w-sm m-b-5" 
                                        style="border-radius: 0px;" onclick="enviar('<?php echo $_GET['winTitulo']; ?>')">
                                    <i class="fa fa-send-o"></i>&ensp;Enviar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

          

        <script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>
        <script src="vistas/libs/imagina/assets/timepicker/bootstrap-datepicker.js"></script>

        <script src="vistas/libs/imagina/js/jquery.tool.js"></script>
        <script src="vistas/com/tipoCambio/tipoCambio_form.js"></script>
    </body>
</html>

