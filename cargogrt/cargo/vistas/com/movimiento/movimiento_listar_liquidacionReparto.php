<div class="page-title">
    <h3 id="titulo" class="title"></h3>
</div>
<input type="hidden" id="hddIsDependiente" value="0">
<div class="row">
    <input type="hidden" id="txtTipo" name="txtTipo" class="form-control" value="" readonly="true"/>
    <div class="panel panel-default" style="padding-bottom:  5px;">
        <div class="row">
            <div class="col-lg-12">
                <div  class="portlet" >
                    <div class="row">                        
                        <div class="col-md-12" style="padding-left:0px">
                            <div class="input-group m-t-10">
                                
                        <div class="input-group-btn" id="listaPersonaTipo">
<!--                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true"  style="width: 100%; padding-top: 10px;padding-bottom: 10px;"><i class=" fa fa-plus-square-o"></i> Nueva <span class="caret"></span></button>
                            <ul id="listaPersonaTipo" class="dropdown-menu" role="menu">
                            </ul>-->
                        </div>
                                
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                 
                            <!--<div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">-->
                            <div class="row">

        <div class="form-group col-md-4">
                <label> Serie </label>
                <div class="input-group col-lg-12 col-md-16 col-sm-12 col-xs-12">
                <input type="text" class="form-control " placeholder="M001" id="seriemanfreparto" name="seriemanfreparto" value="M001" readonly >
                
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> Número </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="text" class="form-control " placeholder="0000000" id="numeromanfreparto" name="numeromanfreparto"  maxlength="7">
                
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> &nbsp; </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" href="#bg-info" onclick="BuscarResultadoReparto();" value="enviar" class="btn btn-info"><i class="ion-search"></i>&nbsp; Buscar</button>&nbsp;
                <button type="button" href="#bg-info" onclick="ExportarLiquidacionRepartopdf();" value="enviar" class="btn btn-success"><i class="ion-archive"></i>&nbsp; Descargar</button>&nbsp;
                
                </div>
        </div>


<!--                            <div id="divBusquedaCboCliente" class="form-group col-md-4">
        <label>Cliente</label>
        <select  name="cboClienteListado" id="cboClienteListado" class="select2" placeholder="Seleccione cliente"></select>
</div> -->


</div>
                            <!--</div>-->


                            
                        </div>
                            

                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
        <table id="datatableinventario" class="table table-striped table-bordered"  width="100%">
                <!-- class="table table-small-font table-striped table-hover" width="100%"> -->
                <!--Item,CPE,Pedido,Modalidad,PaquetesManifiesto,EstadoPedido,PaquetesxPedido, Pago,TotalCpe -->
                    <thead >
                        <tr>
                            <!--<th style='text-align:center;' >Item</th>-->
                            <th style='text-align:center;' >Cpe</th>
                            <th style='text-align:center;' >Pedido</th>
                            <th style='text-align:center;' >Modalidad</th>
                            <th style='text-align:center;' >Estado</th>
                            <th style='text-align:center;' >Paquetes x Pedido</th>
                            <th style='text-align:center;' >pago</th>
                            <th style='text-align:center;' >Total</th>   
                        </tr>
                    </thead>
                   
                </table>
        </div>
        <br>
        <!--<div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
            </p>
        </div>-->
    </div>
   
</div>

<script src="vistas/com/movimiento/movimiento_listar_liquidacionReparto.js"></script>
        