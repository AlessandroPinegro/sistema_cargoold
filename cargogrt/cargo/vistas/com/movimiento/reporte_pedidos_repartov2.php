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

                        </div>
                                
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
                            <div class="row">

        <div class="form-group col-md-4">
                <label> Fecha inicio </label>
                <div class="input-group col-lg-12 col-md-16 col-sm-12 col-xs-12">
                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaInicioped">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> Fecha Fin </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaFinped">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
        </div>
        <div class="form-group col-md-4">
                <label> &nbsp; </label>
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="button" href="#bg-info" onclick="BuscarResultadoReparto();" value="enviar" class="btn btn-info"><i class="ion-search"></i>&nbsp; Buscar</button>&nbsp;
                <a type="button" class="btn btn-success" onclick="exportarReporteRepartosExcel();" title="exportar excel"><i class="ion-archive"></i>&nbsp;Descargar</a>
                </div>
        </div>
</div>
                        </div>
                            

                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
        <table id="datatable" class="table table-striped table-bordered" >
                <!-- class="table table-small-font table-striped table-hover" width="100%"> -->
                
                    <thead >
                        <tr>
                            <th style='text-align:center;' >Pedido</th>
                            <th style='text-align:center;' >Cpe</th>
                            <th style='text-align:center;' >Tipo cpe</th>
                            <th style='text-align:center;' >Fecha</th>
                            <th style='text-align:center;' >Origen</th>
                            <th style='text-align:center;' >Destino</th>
                            <th style='text-align:center;' >Doc Remitente</th>
                            <th style='text-align:center;' >Remitente </th>
                            <th style='text-align:center;' >Doc Destinatario</th>
                            <th style='text-align:center;' >Destinatario</th>
                            <th style='text-align:center;' >Dirección Destino</th>
                            <th style='text-align:center;' >telefono</th>
                            <th style='text-align:center;' >Paquetes</th>
                            <th style='text-align:center;' >Modalidad</th>
                            <th style='text-align:center;' >Manifiesto</th>
                            <th style='text-align:center;' >Placa</th>
                            <th style='text-align:center;' >Nro.Flota</th>
                            <th style='text-align:center;' >T.Flota</th>
                            <th style='text-align:center;' >C.Recojo </th>
                            <th style='text-align:center;' >C.Reparto </th>
                            <th style='text-align:center;' >Otros </th>
                            <th style='text-align:center;' >IGV </th>
                            <th style='text-align:center;' >Total </th>
                        </tr>
                    </thead>
                   
                </table>
        </div>
        <br>
      
    </div>
   
</div>

<script src="vistas/com/movimiento/reporte_pedidos_repartov2.js"></script>
        