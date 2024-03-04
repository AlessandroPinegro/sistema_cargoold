<html lang="es">
    <head>
        <link href="vistas/libs/imagina/assets/select2/select2.css" rel="stylesheet"/>
        <link href="vistas/libs/imagina/assets/timepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
        <style type="text/css" media="screen">
/*            @media screen and (max-width: 1000px) {
                #scroll{
                    width: 1000px;               
                }
                #muestrascroll{
                    overflow-x:scroll;
                }
            }*/
            #datatable td{
                vertical-align: middle;
            }
            .sweet-alert button.cancel {
                background-color: rgba(224, 70, 70, 0.8);
            }
            .sweet-alert button.cancel:hover {
                background-color:#E04646;
            }
            .sweet-alert {
                border-radius: 0px; 
            }
            .sweet-alert button {
                -webkit-border-radius: 0px; 
                border-radius: 0px; 
            }
            .popover{
                max-width: 100%; 
            }
            th { white-space: nowrap; }
            .alignRight { text-align: right; }
        </style>
    </head>
    <body >
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="row"></div>
                <div class="panel panel-body" id="muestrascroll">
                    <div class="col-md-12 col-sm-12 col-xs-12" id="scroll">
                        <div class="table">
                            <div id="dataList">
                                <table id="datatable" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style='text-align:center;'>Psición</th>
                                            <th style='text-align:center;'>Persona</th>
                                            <th style='text-align:center;'>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align:right">Total:</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--</div>-->
        <script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>  
<!--        <script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>-->
        <script src="vistas/com/widgets/rankingDistribucion.js"></script>
    </body>
</html>


