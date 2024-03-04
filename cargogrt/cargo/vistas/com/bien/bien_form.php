<div class="row">
    <div class="col-md-12 ">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><b id="titulo" ></b></h4>
                <div class="col-md-12 ">
                    <div class="panel-body">
                        <form  id="frm_bien"  method="post" class="form" enctype="multipart/form-data;charset=UTF-8">
                            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['id_usuario']; ?>"/>
                            <div class="row">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#tabGeneral" data-toggle="tab" aria-expanded="true">
                                            <span class="visible-xs"><i class="fa fa-home"></i></span>
                                            <span class="hidden-xs"></span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <!--PESTAÑA GENERAL-->
                                    <div class="tab-pane active" id="tabGeneral">
                                        <div class="row">
                                            <div class="form-group col-md-6 ">
                                                <label>Código de artículo *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input  type="text" id="txt_codigo" name="txt_codigo" class="form-control" value="" maxlength="45"/>
                                                </div>
                                                <span id='msj_codigo' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Descripción *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" id="txt_descripcion" name="txt_descripcion" class="form-control" required="" aria-required="true" value="" maxlength="500" required />
                                                </div>
                                                <span id='msj_descripcion' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="form-group col-md-6" id="contenedorBienTipo" hidden="true">
                                                <label>Grupo de artículo *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboBienTipo" id="cboBienTipo" class="select2" onchange="onchangeTipoBien(this.value);">
                                                    </select>
                                                    <i id='msj_tipo'
                                                       style='color:red;font-style: normal;' hidden></i>
                                                </div>

                                            </div>

                                            <div class="form-group col-md-6" id="contenedorUnidadTipo" hidden="true">
                                                <label id="lb_empresa">Tipo de unidades *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboUnidadTipo" id="cboUnidadTipo" class="select2" multiple onchange="onchangeUnidadTipo();">
                                                    </select>
                                                    <span id='msj_UnidadTipo' class="control-label"
                                                          style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="form-group col-md-6" id="contenedorUnidadControl" hidden="true">
                                                <label>Unidad Control *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboUnidadControl" id="cboUnidadControl" class="select2" onchange="">
                                                    </select>
                                                    <i id='msj_unidad_control'
                                                       style='color:red;font-style: normal;' hidden></i>
                                                </div>

                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Estado *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <!--<span class="input-group-addon white-bg " data-toggle="tooltip" data-placement="bottom"  title="" data-html='true' data-original-title="<?php echo $alerta; ?>"><i  class="ion-alert"></i></span>-->
                                                    <select name="cboEstado" id="cboEstado" class="select2">
                                                        <option value="1" selected>Activo</option>
                                                        <option value="0">Inactivo</option>
                                                    </select>
                                                    <span id='msj_estado' class="control-label"
                                                          style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div> 

                                        </div>

                                        <div class="row">


                                            <div class="form-group col-md-6">        
                                                <label id="lb_empresa">Empresas *</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <select name="cboEmpresa" id="cboEmpresa" class="select2" onchange="onchangeEmpresa();" multiple>
                                                    </select>
                                                    <span id='msj_empresa' class="control-label"
                                                          style='color:red;font-style: normal;' hidden></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">    
                                            <div class="form-group col-md-4">


                                            </div>
                                            <div class="form-group col-md-4">
                                                <img id="myImg2" src="vistas/com/bien/imagen/cajamedidas.png" onerror="this.src='vistas/com/bien/imagen/cajamedidas.png'" style="width: 80%; height:80%;" alt="" class="img-thumbnail profile-img thumb-lg" />
                                                <input type="hidden" id="secretImg" value="" />                                       

                                            </div>
                                        </div> 
                                        <br>
                                        <div class="row" id="bloque2">
                                            <div class="form-group col-md-1 ">

                                            </div>             

                                            <div class="form-group col-md-2 ">
                                                <label>Largo (metros)</label>  
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input  type="text" id="txt_largo" name="txt_largo" oninput="calculate()" class="form-control" value="" maxlength="45"/>
                                                </div>
                                                <span id='msj_Largo' class="control-label"
                                                      style='color:red;font-style: normal;' hidden>aaaa</span>
                                            </div>
                                            <div class="form-group col-md-1 ">
                                                <br> <label style="font-size: 26px;"> &nbsp;&nbsp; &nbsp; X </label>  
                                            </div>             
                                            <div class="form-group col-md-2">
                                                <label>Ancho (metros)</label> 
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" id="txt_ancho" name="txt_ancho" oninput="calculate()" class="form-control" required="" aria-required="true" value="" maxlength="500"/>
                                                </div>
                                                <span id='msj_ancho' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>
                                            <div class="form-group col-md-1 ">
                                                <br> <label style="font-size: 26px;"> &nbsp;&nbsp; &nbsp; X </label>  
                                            </div>  
                                            <div class="form-group col-md-2">
                                                <label>Alto (metros)</label>  
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" id="txt_alto" name="txt_alto" oninput="calculate()" class="form-control" required="" aria-required="true" value="" maxlength="500"/> 
                                                </div>
                                                <span id='msj_alto' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>   
                                            <div class="form-group col-md-1 ">
                                                <br> <label style="font-size: 26px;"> &nbsp;&nbsp; &nbsp; = </label>  
                                            </div>             <div class="form-group col-md-2">
                                                <br>
                                                <label id="result2"></label> M3
                                                <br>
                                                <label id="result3"></label> Peso Volumetrico
                                                <span id='msj_descripcion' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>

                                            <div class="form-group col-md-1 ">

                                            </div>                

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group col-md-1 ">
                                            </div>             

                                            <div class="form-group col-md-2 ">
                                            </div>

                                            <div class="form-group col-md-1 ">
                                            </div>    

                                            <div class="form-group col-md-2">
                                            </div>

                                            <div class="form-group col-md-1 ">
                                            </div>  

                                            <div class="form-group col-md-2">
                                            </div> 

                                            <div class="form-group col-md-1 ">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>Peso Comercial</label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="text" id="txt_peso_volumetrico" name="txt_peso_volumetrico" class="form-control" required="" aria-required="true" value="" maxlength="500"/>
                                                </div>
                                                <span id='msj_peso_volumetrico' class="control-label"
                                                      style='color:red;font-style: normal;' hidden></span>
                                            </div>

                                            <div class="form-group col-md-1 ">
                                            </div>                

                                        </div><br><br>
                                        <div class="row"> 
                                            <div class="form-group col-md-3">
                                                <!--<label>Imagen</label>-->
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="fileUpload btn w-lg m-b-5" id="multi" style="border-radius: 0px;background-color: #337Ab7;color: #fff;cursor:default;">
                                                        <div id="edi" ><i class="ion-upload m-r-15" style="font-size: 16px;"></i>Subir imagen</div>
                                                        <input name="file" id="file"  type="file" accept="image/*" class="upload" onchange='$("#upload-file-info").html($(this).val().slice(12));' >
                                                    </div>
                                                    &nbsp; &nbsp; <b class='' id="upload-file-info">Ninguna imagen seleccionada</b>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <img id="myImg" src="vistas/com/bien/imagen/bienNone.jpg" onerror="this.src='vistas/com/bien/imagen/bienNone.jpg'" alt="" class="img-thumbnail profile-img thumb-lg" />
                                                <input type="hidden" id="secretImg" value="" />                                       
                                                <script>
                                                    $(function () {
                                                        $(":file").change(function () {
                                                            if (this.files && this.files[0]) {
                                                                var reader = new FileReader();
                                                                reader.onload = imageIsLoaded;
                                                                reader.readAsDataURL(this.files[0]);
                                                            }
                                                        });
                                                    });
                                                    function imageIsLoaded(e) {
                                                        $('#secretImg').attr('value', e.target.result);
                                                        $('#myImg').attr('src', e.target.result);
                                                        $('#myImg').attr('width', '128px');
                                                        $('#myImg').attr('height', '128px');
                                                    }
                                                    ;
                                                </script>
                                            </div>
                                            <div id="bcTarget"></div>


                                            <div class="form-group col-md-6">
                                                <label>Comentario </label>
                                                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <textarea type="text" id="txt_comentario" name="txt_comentario" class="form-control" value="" maxlength="500"></textarea>
                                                </div>
                                            </div>
                                        </div>                                      
                                    </div>
                                    <!--FIN PESTAÑA GENERAL-->
                                </div>
                            </div>       
                    </div> 


                    <br>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <a href="#" class="btn btn-danger m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPantallaListar()" ><i class="fa fa-close"></i>&ensp;Cancelar</a>&nbsp;&nbsp;&nbsp;
                            <button type="button" onclick="guardarBien('<?php echo $tipo; ?>')" value="enviar" name="env" id="env" class="btn btn-info w-sm m-b-5" style="border-radius: 0px;"><i class="fa fa-send-o"></i>&ensp;Enviar</button>&nbsp;&nbsp;
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<script src="vistas/com/bien/bien_form.js"></script>