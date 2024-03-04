<html lang="es">
    <head>

        <style type="text/css" media="screen">

            @media screen and (max-width: 1000px) {
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
    </head>
    <body >
        <div class="page-title">
            <h3 id="titulo" class="title"></h3>
        </div>
        <div class="row">
            <!--<div class="col-md-12 col-md-12 col-xs-12">-->
            <div class="panel panel-default">
                <a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="nuevoVehiculo()"><i class=" fa fa-plus-square-o" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Nuevo</a>
                <a href="#" style="border-radius: 0px;" class="btn btn-info w-md" onclick="addVehiculo()"><i class="fa fa-refresh" aria-hidden="true" style="font-size: 18px;"></i>&nbsp;&nbsp;<i> </i><i> </i>Sincronizar</a>
                <br><br>
                <div class="panel panel-body" id="muestrascroll">
                    <div class="col-md-12 col-sm-12 col-xs-12" id="scroll">
                        <div class="table">
                            <div id="dataListVehiculos">

                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:left">
                    <p><b>Leyenda:</b>&nbsp;&nbsp;
                        <!--<i class="fa fa-file-text" style="color:#088A68;"></i> Detalle de  la informaci&oacute;n &nbsp;&nbsp;&nbsp;-->
                        <i class="fa fa-edit" style="color:#E8BA2F;"></i> Editar la informaci&oacute;n &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-trash-o" style="color:#cb2a2a;"></i> Eliminar&nbsp;&nbsp;&nbsp;
                        <i class='fa fa-qrcode' aria-hidden='true' style='color:#1ca8dd;'></i> Generar QR &nbsp;&nbsp;&nbsp;
                        <!--<i class="ion-flash-off" style="color:#cb2a2a;"></i> Estado inactivo  -->
                    </p>
                </div>
            </div>
        </div>
        <script src="vistas/com/vehiculos/vehiculos.js"></script>        
        <script type="text/javascript"> 
                    $(document).ready(function () {                        
                        select2.iniciar();
                        listarVehiculos();
                    });
        </script>
    </body>
</html>


