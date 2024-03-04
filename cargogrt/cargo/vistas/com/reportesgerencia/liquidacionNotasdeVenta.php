<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-9">
                <h3 id="titulo" class="title"></h3>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="panel panel-body" id="muestrascroll">
            <div class="form-group col-md-5">
                <label for="inputEmail4">Fecha Inicial del Comprobante</label>
                <input type="date" class="form-control" id="fInicio">
            </div>
            <div class="form-group col-md-5">
                <label for="inputPassword4">Fecha  Final del Comprobante</label>
                <input type="date" class="form-control" id="fFinal">
            </div>
            <div class="form-group col-md-1">
                <br>
                <button onclick="buscarDataObservacion()" type="button" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            </div>
            <div class="form-group col-md-1">
                <br>
                <i title="Descargar Excel" style="color:green; font-size: 24px; cursor: pointer;" class="fa fa-file-excel-o icono-sobresaliente" aria-hidden="true" onclick="descargarExcel()"></i>
            </div>
            <style>
                .icono-sobresaliente {
                    color: green;
                    font-size: 24px;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                }

                .icono-sobresaliente:hover {
                    transform: scale(1.1);
                }
            </style>
        </div>
    </div>
    <div class="card">
        <div class="panel panel-body" style="padding: 0.1px 30px;" id="muestrascroll">
        <p id="divLeyenda">
                </p>
            <div id="dataListObservacion">
                <table id="datatable" class="table table-striped table-bordered nowrap">
                    <!-- <thead>
                        <tr style='background-color:#f77816;'>
                            <th style='text-align:center;color:#ffffff'>Pedido</th>
                            <th style='text-align:center;color:#ffffff'>F. Pedido</th>
                            <th style='text-align:center;color:#ffffff;' width="19%">Cliente</th>
                            <th style='text-align:center;color:#ffffff'>Origen</th>
                            <th style='text-align:center;color:#ffffff'>Destino</th>
                            <th style='text-align:center; color:#ffffff'>Total</th>
                            <th style='text-align:center;color:#ffffff'>Comp.</th>
                            <th style='text-align:center;color:#ffffff'>F. Emision</th>
                            <th style='text-align:center;color:#ffffff'>Nro. Comp.</th>
                            <th style='text-align:center;color:#ffffff'>Estado</th>
                            <th style='text-align:center;color:#ffffff'>Acciones</th>
                        </tr>
                    </thead> -->
                </table>
            </div>
            <!-- <div style="clear:left">
                <p id="divLeyenda">
                </p>
            </div> -->
        </div>
    </div>
</div>
<script src="vistas/com/reportesgerencia/liquidacionNotasdeVenta.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>