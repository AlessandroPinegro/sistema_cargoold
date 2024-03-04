<div class="page-title">
    <!--<h3 class="title" id="titulo"></h3>-->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-8">
                <h3 class="title">Liquidación de Agencia</h3>
            </div>
            <!--            <div class="col-lg-2">
                <select name="cboAgencia" id="cboAgencia" class="select2 " onchange="onChangeCboAgencia(this.value)"></select>
            </div>-->
            <!--            <div class="col-lg-2">
                <select name="cboCaja" id="cboCaja" class="select2 " onchange="onChangeCboCaja(this.value)"></select>
            </div>-->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-3">
            <select name="cboAgencia" id="cboAgencia" class="select2 "></select>
        </div>
        <div class="col-lg-3">
            <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="fechaInicio">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <br><br>
        <!-- <div class="col-lg-3 col-sm-3">
        </div> -->
        <div style="cursor:pointer;" onclick="exportarLiquidacionAgenciaPdf();" class="col-lg-12 col-sm-12">
            <div class="widget-panel widget-style-2 bg-pink">
                <i class="fa fa-print"></i>
                <h2 class="m-0 counter">EXPORTAR PDF LIQUIDACIÓN AGENCIA</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <br><br>
        <div class="col-lg-3 col-sm-3">
        </div>
        <div style="cursor:pointer;" onclick="exportarDetalleLiquidacionAgenciaPdf();" class="col-lg-12 col-sm-12">
            <div class="widget-panel widget-style-2 bg-success">
                <i class="fa fa-print"></i>
                <h2 class="m-0 counter">EXPORTAR PDF DETALLE LIQUIDACIÓN AGENCIA</h2>

            </div>
        </div>
    </div> 


    <!--        <div style="clear:left">
            <p><b>Leyenda:</b>&nbsp;&nbsp;
                <span id="iconos_leyenda"></span>
                <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar apertura &nbsp;&nbsp;&nbsp;
                <i class="fa fa-edit" style="color:green;"></i> Editar cierre &nbsp;&nbsp;&nbsp;
                <i class="fa fa-eye" style="color:#1ca8dd;"></i> Visualizar &nbsp;&nbsp;&nbsp;
                <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar &nbsp;&nbsp;&nbsp;
            </p>
        </div>-->

</div>



<!-- fin detalle -->

<script src="vistas/com/movimiento/movimiento_listar_liquidacionAgencia.js"></script>