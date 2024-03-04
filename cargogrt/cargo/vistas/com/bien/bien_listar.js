var c = $('#env i').attr('class');
var bandera_eliminar = false;
var bandera_getCombo = false;
var acciones = {
    getTipoBien: false,
    getEmpresa: false,
    getTipoUnidad: false
};
$(document).ready(function () {
    ax.setSuccess("successBien");
    listarBien();
    altura();
    modificarAnchoTabla('datatable');

    $('#txtFechaBaja').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
});
function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
function listarBien()
{
    ax.setAccion("getDataGridBien");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}
function getFormatoImportar()
{
    ax.setAccion("getFormatoImportar");
    ax.consumir();
}
function successBien(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'getDataGridBien':
                onResponseAjaxpGetDataGridBien(response.data);
                break;
            case 'getBien':
                llenarFormularioEditar(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                listarBien();
                break;
            case 'deleteBien':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "Se ah eliminado el articulo " + response.tag + ".", "success");
                    listarBien();
                } else {
                    swal("Cancelado", "El articulo " + response.tag + " " + response.data['0'].vout_mensaje+" en el mantenedor de pedidos", "error");
                }
                bandera_eliminar = true;
                break;
            case 'darDeBajaActivoFijo':
                $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
                $('#modalBajaActivo').modal('hide');
                listarBien();
                break;
            case 'generarCodigoBarra':
                break;
            case 'importBien':
                loaderClose();
                $('#resultado').append(response.data);
                listarBien();
                break;
            case 'exportarBienExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/lista_de_bienes.xlsx";
                break;
            case 'getFormatoImportar':
                if (response.data) {
                    window.location.href = URL_BASE + "util/formatos/formato_bien.xls";
                }
                break;
            case 'getAllEmpresaImport':
                onResponseGetAllEmpresas(response.data);
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertBien':
                break;
            case 'importBien':
            case 'darDeBajaActivoFijo':
                loaderClose();
                break;
        }
    }
}

function reemplazarComillas(cadena) {
    cadena = cadena.replace('"', '');
    cadena = cadena.replace("'", "");

    return  cadena;
}
var dataPeriodo = [];
var dataCuentaContable = [];
function onResponseAjaxpGetDataGridBien(dataRespuesta) {
    var data = dataRespuesta;
    dataPeriodo = dataRespuesta.dataPeriodo;
    dataCuentaContable = dataRespuesta.dataCuentaContable;
    if (!isEmpty(dataRespuesta.dataPrecioTipo)) {
        $('#thPrecioTipo').html('Precio ' + dataRespuesta.dataPrecioTipo[0]['descripcion']);
    }

    if (!isEmptyData(data))
    {
        var bienDescripcion = '';
        $.each(data, function (index, item)
        {

            bienDescripcion = item['b_descripcion'];
            bienDescripcion = bienDescripcion.replace(/\'/g, " ");
            bienDescripcion = bienDescripcion.replace(/\"/g, " ");
            item.opciones = "";
            switch (parseInt(item.estado)) {
                case 1:
                    item.estado_descripcion = "Activo";
                    item.opciones = '<a onclick="editarBien(' + item['id'] + ', ' + item['bien_tipo_id'] + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;&nbsp;&nbsp;';
                    if (item['bien_tipo_id'] == -2) {
                        item.opciones += '<a onclick="darBajaActivo(' + item['id'] + ',\'' + bienDescripcion + '\')"><b><i class="ion-flash-off" style="color:#cb2a2a;"></i><b></a>';
                    } else {
                        item.opciones += '<a onclick="confirmarDeleteBien(' + item['id'] + ',\'' + bienDescripcion + '\')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>';
                    }
                    break;
                case 0:
                    item.estado_descripcion = "Inactivo";
                    break;
                case 3:
                    item.estado_descripcion = "Dado de baja";
                    break;
                case 2:
                    item.estado_descripcion = "Eliminado";
                    break;
            }
        });

        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "data": data,
            "columns": [
                {"data": "codigo"},
                {"data": "tb_descripcion"},
                {"data": "b_descripcion"},
                {"data": "unidad_medida_tipo_descripcion"},
                {"data": "largo", "sClass": "alignRight",render: $.fn.dataTable.render.number( ',', '.', 2)},
                {"data": "ancho", "sClass": "alignRight",render: $.fn.dataTable.render.number( ',', '.', 2)},
                {"data": "alto", "sClass": "alignRight",render: $.fn.dataTable.render.number( ',', '.', 2)},
                {"data": "peso_volumetrico", "sClass": "alignRight",render: $.fn.dataTable.render.number( ',', '.', 2)},
                {"data": "estado_descripcion"},
                {"data": "opciones", "sClass": "alignCenter"}
            ],
            
            columnDefs: [
                {
                    "render": function (data) {

                        if (data == null) {
                            return '';
                        } else {
                            return parseFloat(data, 2);
                        }

                    },
                    "targets": [4, 5, 6, 7]
                }],
            "destroy": true
        });
    } else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
    loaderClose();
}
function confirmarDeleteBien(id, nom, tipo) {
    bandera_eliminar = false;
    var texto = (tipo == -2 ? "Se dará de baja al activo fijo " : "Eliminarás el articulo ") + nom + "!";
    swal({
        title: "Estás seguro?",
        text: texto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            deleteBien(id, nom, tipo);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function deleteBien(id_bien_tipo, nom, tipo)
{
    ax.setAccion("deleteBien");
    ax.addParamTmp("id_bien", id_bien_tipo);
    ax.addParamTmp("tipo_bien_id", tipo);
    ax.setTag(nom);
    ax.consumir();
}

function getComboBienTipo(id)
{
    ax.setAccion("getComboTipoBien");
    ax.addParamTmp("id_tipo", id);
    ax.consumir();
}

function getComboEmpresa(id)
{
    ax.setAccion("getComboEmpresa");
    ax.addParamTmp("id_tipo", id);
    ax.consumir();
}

function generarCodigoBarra()
{
    var codigo = document.getElementById("txt_codigo").value;
    $("#bcTarget").barcode("11111111", "ean8", {barWidth: 5, barHeight: 30});
}

function importBien() {
    //alert("hola");
    getAllEmpresaImport();
    $('#resultado').empty();
    $('#btnImportar').show();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboEmpresa").attr("disabled", false);
    //asignarValorSelect2('cboEmpresa', "");
    $("#cboEmpresa").select2({
        width: '100%'
    });
    $('#modalBien').modal('show');
}

function importar()
{
    var empresa = $('#cboEmpresa').val();

    if (isEmpty(empresa))
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        return;
    } else {
        $("#msj_empresa").hide();
    }

    $('#resultado').empty();
    $('#btnImportar').hide();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboEmpresa").attr("disabled", true);

    var file = document.getElementById('secret').value;
//    console.log(file);
    loaderShow(".modal-content");
    ax.setAccion("importBien");
    ax.addParam("file", file);
    ax.addParam("empresa_id", empresa);
    ax.consumir();
}

function exportarBienExcel()
{
    loaderShow();
    ax.setAccion("exportarBienExcel");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function getAllEmpresaImport()
{
    ax.setAccion("getAllEmpresaImport");
    ax.consumir();
}

function nuevoBien()
{
    loaderShow(null);
    commonVars.bienId = 0;
    commonVars.bienTipoId = 0;
    cargarDiv('#window', 'vistas/com/bien/bien_form.php', "Nuevo " + obtenerTitulo());
}
function nuevoServicio()
{
    loaderShow(null);
    commonVars.bienId = 0;
    commonVars.bienTipoId = -1;
    cargarDiv('#window', 'vistas/com/bien/bien_form.php', "Nuevo " + obtenerTitulo());
}

function editarBien(id, tipo) {
    loaderShow(null);
    commonVars.bienId = id;
    commonVars.bienTipoId = tipo;
    cargarDiv("#window", "vistas/com/bien/bien_form.php", "Editar " + obtenerTitulo());
}

function obtenerTitulo()
{
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    $("#window").empty();

    if (!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function onResponseGetAllEmpresas(data) {

    if (!isEmpty(data))
    {
        $('#cboEmpresa').empty();
        //$('#cboEmpresa').select2("val","");

        select2.asignarValor("cboEmpresa", -1);

        $('#cboEmpresa').append('<option></option>');
        $.each(data, function (index, value) {
            $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
        });
    }
    acciones.getEmpresa = true;
}


function darBajaActivo(bienId)
{
    $("#cboPeriodoActivo").select2({
        width: "100%"
    });
    select2.cargar("cboPeriodoActivo", dataPeriodo, "id", ["anio", "mes"]);

    let cuentaRelacionada = "655";
    let array_cuentas_relaciondas = [];
    if (!isEmpty(dataCuentaContable)) {
        $.each(dataCuentaContable, function (indexPadre, cuenta) {
            var busquedad = new RegExp('^' + cuentaRelacionada + '.*$');
            if (!isEmpty(cuenta.codigo) && cuenta.codigo.match(busquedad)) {
                array_cuentas_relaciondas.push(cuenta);
            }
        });
    }

    $('#cboPlanContable').empty();
    $.each(array_cuentas_relaciondas, function (indexPadre, cuentaContablePadre) {
        var html = llenarCuentasContable(cuentaContablePadre, '', 'cboPlanContable');
        $('#cboPlanContable').append(html);
    });

    select2.asignarValor("cboPlanContable", "");

    $("#txtBienIdHidden").val(bienId);
    $('#modalBajaActivo').modal('show');
}

function llenarCuentasContable(item, extra, cbo_id) {
    var cuerpo = '';
    if ($("#" + cbo_id + " option[value='" + item['codigo'] + "']").length != 0) {
        return cuerpo;
    }
    if (item.hijos * 1 == 0) {
        cuerpo = '<option value="' + item['codigo'] + '">' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
        return cuerpo;
    }
    cuerpo = '<option value="' + item['codigo'] + '" disabled>' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
    var dataHijos = dataCuentaContable.filter(cuentaContable => cuentaContable.plan_contable_padre_id == item.id);
    $.each(dataHijos, function (indexHijo, cuentaContableHijo) {
        cuerpo += llenarCuentasContable(cuentaContableHijo, extra + '&nbsp;&nbsp;&nbsp;&nbsp;', cbo_id);
    });
    return cuerpo;
}

function generarBajaActivoFijo() {
    var bienId = $("#txtBienIdHidden").val();
    var fechaContable = $("#txtFechaBaja").val();

    var periodoId = select2.obtenerValor("cboPeriodoActivo");
    var cuentaContable = select2.obtenerValor("cboPlanContable");
    if (isEmpty(periodoId)) {
        mostrarAdvertencia("Seleccionar un periodo.");
        return;
    }

    if (isEmpty(cuentaContable)) {
        mostrarAdvertencia("Seleccionar una cuenta contable.");
        return;
    }

    if (isEmpty(fechaContable)) {
        mostrarAdvertencia("Seleccionar la fecha de baja.");
        return;
    }

    loaderShow('#modalBajaActivo');
    ax.setAccion("darDeBajaActivoFijo");
    ax.addParamTmp("bien_id", bienId);
    ax.addParamTmp("periodo_id", periodoId);
    ax.addParamTmp("cuenta_contable", cuentaContable);
    ax.addParamTmp("fecha_contable", fechaContable);
    ax.consumir();

}