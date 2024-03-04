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
                <label> Fecha inicio </label>
                <div class="input-group col-lg-12 col-md-16 col-sm-12 col-xs-12">
                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaIniciorep">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> Fecha Fin </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaFinrep">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> &nbsp; </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" href="#bg-info" onclick="BuscarResultadoInventario();" value="enviar" class="btn btn-info"><i class="ion-search"></i>&nbsp; Buscar</button>&nbsp;
                <a type="button" class="btn btn-success" onclick="ExportarInventarioTotalExcel();" title="exportar excel"><i class="ion-archive"></i>&nbsp;Descargar</a>
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
        <table id="datatableinventario" class="table table-striped table-bordered" >
                <!-- class="table table-small-font table-striped table-hover" width="100%"> -->
                
                    <thead >
                        <tr>
                            <th style='text-align:center;' >Serie</th>
                            <th style='text-align:center;' >Número</th>
                            <th style='text-align:center;' >Docnumeroserie</th>
                            <th style='text-align:center;' >Fecha</th>
                            <th style='text-align:center;' >Doc Remitente</th>
                            <th style='text-align:center;' >Remitente</th>
                            <th style='text-align:center;' >R. telefono</th>
                            <th style='text-align:center;' >Dirección Origen</th>
                            <th style='text-align:center;' >Origen</th> 
                            <th style='text-align:center;' >Doc Destinatario</th>
                            <th style='text-align:center;' >Destinatario</th>
                            <th style='text-align:center;' >D. telefono</th>
                            <th style='text-align:center;' >Dirección Destinado</th>
                            <th style='text-align:center;' >Destino</th>
                            <th style='text-align:center;' >Estado</th>
                            <th style='text-align:center;' >Total</th>
                            <th style='text-align:center;' >P.Serie</th>
                            <th style='text-align:center;' >P.Número</th>
                            <th style='text-align:center;' >P.Fecha</th>
                            <th style='text-align:center;' >P.Estado</th>
                            <th style='text-align:center;' >Modalidad</th>
                            <th style='text-align:center;' >Peso Total</th>
                            <th style='text-align:center;' >Manifiesto</th>
                            <th style='text-align:center;' >Placa</th>
                            <th style='text-align:center;' >Num. Flota</th>
                            <!-- <th style='text-align:center;' >P.transferencia</th>
                            <th style='text-align:center;' >P.POS</th>
                            <th style='text-align:center;' >P.Pasarela</th> -->


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

<script src="vistas/com/reportesgerencia/reporteinventario.js"></script>
        