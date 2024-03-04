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
                <button type="button" href="#bg-info" onclick="BuscarResultadoDetallePagos();" value="enviar" class="btn btn-info"><i class="ion-search"></i>&nbsp; Buscar</button>&nbsp;
                <a type="button" class="btn btn-success" onclick="ExportarDetallePagosExcel();" title="exportar excel"><i class="ion-archive"></i>&nbsp;Descargar</a>
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
        <table id="datatablepagos" class="table table-striped table-bordered" >
                <!-- class="table table-small-font table-striped table-hover" width="100%"> -->
                
                    <thead >
                        <tr>
                            <th style='text-align:center;' >Serie</th>
                            <th style='text-align:center;' >Número</th>
                            <th style='text-align:center;' >Docnumeroserie</th> 
                            <th style='text-align:center;' >Tipo</th>
                            <th style='text-align:center;' >Fecha CPE</th>
                            <th style='text-align:center;' >Total CPE</th>                           
                            <th style='text-align:center;' >Estado CPE</th>
                            <th style='text-align:center;' >Cod. Pago</th>
                            <th style='text-align:center;' >Importe Pago</th>
                            <th style='text-align:center;' >Forma Pago</th>
                            <th style='text-align:center;' >F.Pago</th>
                            <th style='text-align:center;' >EStado Pago</th>
                            <th style='text-align:center;' >Usuario</th>
                            <th style='text-align:center;' >Caja</th>
                            <th style='text-align:center;' >Agencia</th>                           

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
        </div>
    
    
    /*
"serie": "BD04",
            "numero": "0006244",
            "doc": "BD04-0006244",
            "tipo_cpe": "V. Boleta ",
            "fecha_emision": "2023-10-31 00:00:00.000",
            "total": "14.00",
            "estado": "Entregado",
            "codpago": 629146,
            "importe_pago": "14.00",
            "forma_pago": "Efectivo",
            "fecha_pago": "2023-10-31 22:01:20.320",
            "usuario": "EOTEROA",
            "caja": "CAJA 01 - SULLANA-OFIC",
            "agencia": "SULLANA - CARGO"
*/
    
    -->
    </div>
   
</div>

<script src="vistas/com/reportesgerencia/reporte_pagosdetalle.js"></script>
        