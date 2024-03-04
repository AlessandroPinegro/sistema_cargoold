<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-9">
                <h3 id="titulo" class="title"></h3>
            </div>
            <div id="divCboAgencia" class="col-lg-3">
                <select name="cboAgencia" id="cboAgencia" class="select2 " onchange="onChangeCboAgencia(this.value)"></select>
            </div>
        </div>
    </div>
    <div class="card">   
        <div class="panel panel-body" id="muestrascroll">
            <div class="col-md-12 col-sm-12 col-xs-12" id="scroll">
                <div class="table">
                    <div id="dataListDesbloqueos">
                        <table id="datatable" class="table table-striped table-bordered nowrap">
                            <thead>
                             <tr style='background-color:#f77816;'>
                                    <th style='text-align:center;color:#ffffff' >Pedido</th>
                                    <th style='text-align:center;color:#ffffff'>F. Pedido</th>
                                    <th style='text-align:center;color:#ffffff'>Cliente</th>
                                    <th style='text-align:center;color:#ffffff'>Origen</th>
                                    <th style='text-align:center; color:#ffffff'>Total</th>
                                    <th style='text-align:center;color:#ffffff'>Comp.</th>
                                    <th style='text-align:center;color:#ffffff'>F. Emision</th>
                                    <th style='text-align:center;color:#ffffff'>Nro. Comp.</th>
                                    <th style='text-align:center;color:#ffffff' >Estado</th>
                                    <th style='text-align:center;color:#ffffff' >Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="clear:left">
                        <p id="divLeyenda">
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="vistas/com/utilitariosadm/desbloqueo_pedido.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>