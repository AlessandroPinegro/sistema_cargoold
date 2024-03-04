<?php
session_start();
$id = null;
$tipo = null;
extract($_REQUEST, EXTR_PREFIX_ALL, "f");
if (isset($f_id)) {
    $id = (int) filter_var($f_id, FILTER_SANITIZE_NUMBER_INT);
}
?>
<html lang="es">
    <head>
        
        <title>Mantenimietno de colaboradores</title>
        <script>
            altura();
            getDetalleColaborador(<?php echo $id; ?>);
        </script>   
    </head>
    <body >
        <!--        <div class="page-title">
                    <h3 class="title">Mantenimiento de colaboradores</h3>
                </div>-->

        <div class="wraper container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div >
                            <h3 class="panel-title">informaci&oacute;del Colaborador</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <div id="listar_detalle">

                                        </div>
                                        <div class="form-group col-md-12">
                                            <a href="#" class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarDiv('#window', 'vistas/com/colaborador/colaborador_listar.php')" ><i class="ion-arrow-left-a"></i>&ensp;Regresar</a>&nbsp;&nbsp;&nbsp;
                                        </div>                                           
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End row -->
        </div>

        <script src="vistas/libs/imagina/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="vistas/libs/imagina/assets/datatables/dataTables.bootstrap.js"></script>  
        <script src="vistas/libs/imagina/assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
        <script src="vistas/com/colaborador/colaborador.js"></script>
    </body>
    <!-- Mirrored from coderthemes.com/velonic/admin/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:15:09 GMT -->
</html>

