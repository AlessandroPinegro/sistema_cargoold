<html lang="es">
    <head>
        

        <style type="text/css" media="screen">

            @media screen and (max-width: 1150px) {
                #scroll{
                    width: 1000px;               
                }
                #muestrascroll{
                    overflow-x:scroll;
                }
            }
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
        </style>

        <script>
            altura();
        </script> 

    </head>

    <body >
        <div class="page-title">
            <h3 id="titulo" class="title"</h3>
        </div>
        <div class="row">

            <!--<div class="col-md-12 col-md-12 col-xs-12">-->
            <div class="panel panel-default">
                <a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="nuevo()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Nuevo</a>
                <br><br>
                <div class="panel panel-body" >
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <!--<div class="table">-->
                            <div id="datatable2">

                            </div>
                        <!--</div>-->
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                        <!--<i class="fa fa-group" style="color:#1ca8dd;"></i> Listar colaboradores asignados &nbsp;&nbsp;&nbsp;-->
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
<!--                        <i class='fa fa-unlock' style="color:#5cb85c;"></i> Visible&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-lock" style="color:#cb2a2a;"></i>  No visible&nbsp;&nbsp;&nbsp;-->
                        <i class='ion-checkmark-circled' style="color:#5cb85c;"></i> Estado activo &nbsp;&nbsp;&nbsp;
                        <i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo 
                    </p>
                </div>

            </div>
        </div>
        <script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>  
        <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/js/jquery.tool.js"></script>
        <script src="vistas/com/perfil/perfil.js"></script>

        <script type="text/javascript">
                    $(document).ready(function () {
//                        loaderShow(null);
                        listar_perfil();
                    });
                    altura();
        </script>
    </body>
</html>

