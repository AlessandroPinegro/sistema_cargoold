var c = $('#env i').attr('class');
var unidad_controlID = 0;
var bandera_eliminar = false;
var bandera_getCombo = false;
var acciones = {
    getTipoBien: false,
    getEmpresa: false,
    getTipoUnidad: false
};

$(document).ready(function () {
    datePiker.iniciarPorClase('fecha');

    controlarDomXTipoBien();
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParamTmp("bienId", commonVars.bienId);
    ax.addParamTmp("bienTipoId", commonVars.bienTipoId);
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();

    listarComboProveedores();
    cargarSelect2();
});

function controlarDomXTipoBien() {
    if (commonVars.bienTipoId != -1) {
        $("#contenedorCantidadMinima").show();
        $("#contenedorBienTipo").show();
        $("#contenedorUnidadTipo").show();
        $("#contenedorUnidadControl").show();
        $("#contenedorMarca").show();
        $("#contenedorMaquinaria").show();
        $("#contenedorCodigoSunat").show();
        $("#contenedorCuentaContable").show();
        $("#contenedorCostoInicial").show();
    } else {
        $('#btnProveedor').hide();
    }
}

$("#cboEstado").change(function () {
    $('#msj_estado').hide();
});

function deshabilitarBoton()
{
    $("#env").addClass('disabled');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
}
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

function onchangeEmpresa()
{
    $('#msj_empresa').hide();
}
function onchangeTipoBien(valor)
{
    $('#msj_tipo').hide();
    if (valor == -2) {
        $('a[href="#tabActivoFijo"]').show();
    } else {
        $('a[href="#tabGeneral"]').click();
        $('a[href="#tabActivoFijo"]').hide();
    }

}

function onchangeUnidadTipo()
{
    $('#msj_UnidadTipo').hide();
//    loaderShow();
    ax.setAccion("obtenerUnidadControl");
    //ax.addParamTmp("id_unidad_medida_tipo", document.getElementById('cboUnidadTipo').value);
    ax.addParamTmp("id_unidad_medida_tipo", $('#cboUnidadTipo').val());
    ax.consumir();

    //var unidad_tipo = $('#cboUnidadTipo').val();
    //alert(document.getElementById('cboUnidadTipo').value);
}
function posicion_input(input) {
    var posicion = $("#" + input + "").offset().top;
    $("html, body").animate({
        scrollTop: posicion
    }, 600);
}

var input = document.getElementById('txt_codigo');
input.addEventListener('input', function () {
    $('#msj_codigo').hide();
});

$('#txt_codigo').keypress(function () {
    $('#msj_codigo').hide();
});
$('#txt_descripcion').keypress(function () {
    $('#msj_descripcion').hide();
});
$('#txt_precio_venta').keypress(function () {
    $('#msj_precio_venta').hide();
});

function limpiarFormulario()
{
    document.getElementById("frm_bien").reset();
}

function validarFormulario() {

    var bandera = true;
    var bandera_bloque1 = true;
    var bandera_bloque2 = true;
    // valido modal de proveedores repetido
    bandera = validarModalProveedoresRepetidos();
    if (bandera == false) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione proveedores en orden de prioridad");
    }

    var espacio = /^\s+$/;

    if (commonVars.bienTipoId != -1) {
        var descripcion = document.getElementById('txt_descripcion').value;
        var codigo = document.getElementById('txt_codigo').value;
        var largo = document.getElementById('txt_largo').value;
        var ancho = document.getElementById('txt_ancho').value;
        var alto = document.getElementById('txt_alto').value;
        var peso_volumetrico = document.getElementById('txt_peso_volumetrico').value;
        var tipo = document.getElementById('cboBienTipo').value;
        var empresa = document.getElementById("cboEmpresa").value;
        var estado = document.getElementById('cboEstado').value;
        var unidad_tipo = document.getElementById('cboUnidadTipo').value;
        var unidad_control_id = document.getElementById('cboUnidadControl').value;

        if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
            bandera_bloque1 = false;
        }

        if (codigo == "" || codigo == null || espacio.test(codigo) || codigo.length == 0)
        {
            $("msj_codigo").removeProp(".hidden");
            $("#msj_codigo").text("Ingresar un codigo").show();
            bandera = false;
            bandera_bloque1 = false;
        }

        if (tipo == "" || tipo == null || espacio.test(tipo) || tipo.length == 0)
        {
            $("msj_tipo").removeProp(".hidden");
            $("#msj_tipo").text("Ingresar un tipo de bien").show();
            bandera = false;
            bandera_bloque1 = false;
        }

        if (largo == "" || largo == null || espacio.test(largo) || largo.length == 0)
        {
            $("msj_Largo").removeProp(".hidden");
            $("#msj_Largo").text("Ingresar Valor").show();
            bandera = false;
            bandera_bloque2 = false;
        }

        if (ancho == "" || ancho == null || espacio.test(ancho) || ancho.length == 0)
        {
            $("msj_ancho").removeProp(".hidden");
            $("#msj_ancho").text("Ingresar Valor").show();
            bandera = false;
            bandera_bloque2 = false;
        }

        if (alto == "" || alto == null || espacio.test(alto) || alto.length == 0)
        {
            $("msj_alto").removeProp(".hidden");
            $("#msj_alto").text("Ingresar Valor").show();
            bandera = false;
            bandera_bloque2 = false;
        }

        if (peso_volumetrico == "" || peso_volumetrico == null || espacio.test(peso_volumetrico) || peso_volumetrico.length == 0)
        {
            $("msj_peso_volumetrico").removeProp(".hidden");
            $("#msj_peso_volumetrico").text("Ingresar una descripción").show();
            bandera = false;
            bandera_bloque2 = false;
        }

        if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
        {
            $("msj_empresa").removeProp(".hidden");
            $("#msj_empresa").text("Seleccionar una empresa").show();
            bandera = false;
        }

        if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
        {
            $("msj_estado").removeProp(".hidden");
            $("#msj_estado").text("Seleccionar un estado").show();
            bandera = false;
        }

        if (unidad_tipo == "" || espacio.test(unidad_tipo) || unidad_tipo.lenght == 0 || unidad_tipo == null)
        {
            $("msj_UnidadTipo").removeProp(".hidden");
            $("#msj_UnidadTipo").text("Seleccionar un tipo de unidad").show();
            bandera = false;
        }

        if (unidad_control_id == "" || unidad_control_id == null || espacio.test(unidad_control_id) || unidad_control_id.length == 0)
        {
            $("msj_tipo").removeProp(".hidden");
            $("#msj_unidad_control").text("Seleccionar una unidad de control").show();
            bandera = false;
        }

    } else {
        var descripcion = document.getElementById('txt_descripcion').value;
        var codigo = document.getElementById('txt_codigo').value;
        var empresa = document.getElementById("cboEmpresa").value;
        var estado = document.getElementById('cboEstado').value;
        var largo = document.getElementById('txt_largo').value;
        var ancho = document.getElementById('txt_ancho').value;
        var alto = document.getElementById('txt_alto').value;
        var peso_volumetrico = document.getElementById('txt_peso_volumetrico').value;

        if (largo == "" || largo == null || espacio.test(largo) || largo.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
        }

        if (ancho == "" || ancho == null || espacio.test(ancho) || ancho.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
        }

        if (alto == "" || alto == null || espacio.test(alto) || alto.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
        }

        if (peso_volumetrico == "" || peso_volumetrico == null || espacio.test(peso_volumetrico) || peso_volumetrico.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
        }
        if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0)
        {
            $("msj_descripcion").removeProp(".hidden");
            $("#msj_descripcion").text("Ingresar una descripción").show();
            bandera = false;
        }
        if (codigo == "" || codigo == null || espacio.test(codigo) || codigo.length == 0)
        {
            $("msj_codigo").removeProp(".hidden");
            $("#msj_codigo").text("Ingresar un código").show();
            bandera = false;
        }

        if (empresa == "" || espacio.test(empresa) || empresa.lenght == 0 || empresa == null)
        {
            $("msj_empresa").removeProp(".hidden");
            $("#msj_empresa").text("Seleccionar una empresa").show();
            bandera = false;
        }
        if (estado == "" || espacio.test(estado) || estado.lenght == 0 || estado == null)
        {
            $("msj_estado").removeProp(".hidden");
            $("#msj_estado").text("Seleccionar un estado").show();
            bandera = false;
        }
    }
    if (!bandera_bloque1) {
        posicion_input('frm_bien');
    }
    if (!bandera_bloque2) {
        posicion_input('bloque2');
    }
    return bandera;
}

function successBien(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'obtenerUnidadControl':
                obtenerUnidadControlCombo(response.data);
                loaderClose();
                break;
            case 'insertBien':
                loaderClose();
                exitoInsert(response.data);
                break;
            case 'getBien':
                llenarFormularioEditar(response.data);
                break;
            case 'updateBien':
                loaderClose();
                exitoUpdate(response.data);
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                listarBIEN();
                break;
            case 'deleteBien':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "El tipo de bien " + response.data['0'].nombre + ".", "success");
                    listarBIEN();
                } else {
                    swal("Cancelado", "El tipo de bien " + response.data['0'].nombre + " " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'generarCodigoBarra':
                break;

            case 'importBien':
                loaderClose();
                $('#resultado').append(response.data);
                listarBIEN();
                break;

            case 'ExportarBienExcel':
                loaderClose();
                location.href = "http://" + location.host + "/almacen/util/formatos/lista_de_bienes.xlsx";
                break;
            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);
                break;
            case 'getAllUnidadMedidaTipoCombo':
                onResponsegetAllUnidadMedidaTipoCombo(response.data);
                break;
            case 'getAllBienTipo':
                onResponseGetAllBienTipo(response.data);

//                if (!isEmpty(VALOR_ID_USUARIO))
//                {
//                    getBien(VALOR_ID_USUARIO);
//                }
//               loaderClose();
                verificarCargaDeComplemento();
                break;
            case 'getAllEmpresaImport':
                onResponseGetAllEmpresas(response.data);
                break;
            case 'obtenerComboProveedores':
                onResponseObtenerComboProveedores(response.data);
                loaderClose();
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertBien':
                habilitarBoton();
                loaderClose();
                break;
        }
    }
}

var dataPrecioTipo;
var anchoCuentaContable;
function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data.dataSunatDetalle);
//    console.log(data.cuentaContable);
    //SETEAR ANCHO PARA COMBO CUENTA CONTABLE    
    anchoCuentaContable = $("#divCuentaContable").width();

    dataPrecioTipo = data.precioTipo;
    dataCentroCosto = data.dataCentroCosto;
    $('a[href="#tabActivoFijo"]').hide();
    $('#txtDescuento').val('0.00');
    // cargamos los combos
    if (commonVars.bienId > 0) {

        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");
        if (commonVars.bienTipoId == -2) {
            $('a[href="#tabActivoFijo"]').show();
        } else if (commonVars.bienTipoId != -1) {
            select2.cargar("cboUnidadTipo", data.unidadMedidaTipo, "id", "descripcion");
            select2.cargar("cboBienTipo", data.bienTipo, "id", ["codigo", "descripcion"]);
        } else {
            select2.cargarAsignaUnico("cboUnidadMedida", data.unidadMedidaUnidades, "id", "descripcion");
        }

    } else {
        select2.cargarAsignaUnico("cboEmpresa", data.empresa, "id", "razon_social");
        select2.cargarAsignaUnico("cboUnidadTipo", data.unidadMedidaTipo, "id", "descripcion");

        if (data.unidadMedidaTipo.length == 1) {
            onchangeUnidadTipo();
        }

        select2.cargarAsignaUnico("cboBienTipo", data.bienTipo, "id", ["codigo", "descripcion"]);
        select2.cargarAsignaUnico("cboUnidadMedida", data.unidadMedidaUnidades, "id", "descripcion");

        //preseleccion de todas las empresas    
        var empresaIds = '';

        $.each(data.empresa, function (i, item) {
            empresaIds = empresaIds + ";" + item["id"];
        });

        select2.asignarValor('cboEmpresa', empresaIds.split(";"));

        // fin preseleccion

    }
    // cargamos el formulario
    if (!isEmpty(data.bien)) {
        unidad_controlID = data.bien[0].unidad_control_id;
        llenarFormularioEditar(data.bien);
        cargarCentroCostoBien(data.dataDistribucion);
        if (commonVars.bienTipoId != -1) {
            onchangeUnidadTipo();
        }
    }

    // cargamos el modal proveedor
    if (!isEmpty(data.bienPersona)) {
        dataBienPersona = data.bienPersona;
    }

    loaderClose();
}

var dataBienPersona = [];

function obtenerUnidadControlCombo(data) {
    if (commonVars.bienId > 0) {
        select2.limpiar("cboUnidadControl");
        select2.cargarAsignaUnico("cboUnidadControl", data.unidadMedida, "id", "descripcion");
        select2.cargarAsignaUnico("cboUnidadMedida", data.unidadMedida, "id", "descripcion");
        select2.asignarValor('cboUnidadControl', unidad_controlID);
    } else {
        select2.cargarAsignaUnico("cboUnidadControl", data.unidadMedida, "id", "descripcion");
        select2.cargarAsignaUnico("cboUnidadMedida", data.unidadMedida, "id", "descripcion");
    }
    loaderClose();
}

function exitoInsert(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        validarToken();
        cargarPantallaListar();
    }
}
function exitoUpdate(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else
    {
        habilitarBoton();
        loaderClose();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        validarToken();
        cargarPantallaListar();
    }
}
function calculate() {


    var largo = document.getElementById('txt_largo').value;
    var ancho = document.getElementById('txt_ancho').value;
    var alto = document.getElementById('txt_alto').value;
    var volumen = largo * ancho * alto;
    var volumen2 = volumen.toFixed(2);
    var pesoVolumetrico = volumen * 200;
    var pesoVolumetrico2 = pesoVolumetrico.toFixed(2);
    document.getElementById('result2').innerHTML = volumen2;
    document.getElementById('result3').innerHTML = pesoVolumetrico2;
    $('#txt_peso_volumetrico').val(pesoVolumetrico2);
}

function guardarBien()
{
    var usu_creacion = document.getElementById('usuario').value;
    var descripcion = document.getElementById('txt_descripcion').value;
    var codigo = document.getElementById('txt_codigo').value;
    var tipo_bien = document.getElementById('cboBienTipo').value;
    var estado = document.getElementById('cboEstado').value;
    var comentario = document.getElementById('txt_comentario').value;
    var largo = document.getElementById('txt_largo').value;
    var ancho = document.getElementById('txt_ancho').value;
    var alto = document.getElementById('txt_alto').value;
    var peso_volumetrico = document.getElementById('txt_peso_volumetrico').value;
    var unidad_control_id = document.getElementById('cboUnidadControl').value;
    var empresa = $('#cboEmpresa').val();
    var unidad_tipo = $('#cboUnidadTipo').val();

    var file = document.getElementById('secretImg').value;
    if (file == '')
    {
        file = null;
    }

    var arrayCentroCostoBien = [];
    if (tipo_bien == -2) {
        arrayCentroCostoBien = obtenerCentroCostoBien();
        if (isEmpty(arrayCentroCostoBien)) {
            return;
        }
    }

    if (commonVars.bienId > 0  || commonVars.bienId == -1)
    {
        updateBien(commonVars.bienId, descripcion, codigo, tipo_bien, estado, comentario, empresa, file, unidad_tipo,
                unidad_control_id, largo, ancho, alto, peso_volumetrico);
    } else {
        insertBien(descripcion, codigo, tipo_bien, estado, usu_creacion, comentario, empresa, file, unidad_tipo,
                unidad_control_id, largo, ancho, alto, peso_volumetrico);
    }
}

function insertBien(descripcion, codigo, tipo_bien, estado, usu_creacion, comentario, empresa, file, unidad_tipo,
        unidad_control_id, largo, ancho, alto, peso_volumetrico)
{
    if (validarFormulario()) {
        loaderShow();
        ax.setAccion("insertBien");
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);
        ax.addParamTmp("tipo", tipo_bien);
        ax.addParamTmp("estado", estado);
        ax.addParamTmp("usu_creacion", usu_creacion);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("file", file);
        ax.addParamTmp("unidad_tipo", unidad_tipo);
        ax.addParamTmp("unidad_control_id", unidad_control_id);
        ax.addParamTmp("largo", largo);
        ax.addParamTmp("ancho", ancho);
        ax.addParamTmp("alto", alto);
        ax.addParamTmp("peso_volumetrico", peso_volumetrico);
        ax.consumir();
    } else {
        loaderClose();
    }
}

function llenarFormularioEditar(data) {
    
    var dir = URL_BASE + "vistas/com/bien/imagen/" + data[0].imagen;
    $('#myImg').empty();

    var volumen = data[0].largo * data[0].ancho * data[0].alto;
    var volumen2 = volumen.toFixed(2);
    var pesoVolumetrico = volumen * 200;
    var pesoVolumetrico2 = pesoVolumetrico.toFixed(2);

    var largo = data[0].largo * 1;
    var largo2 = largo.toFixed(4);

    var ancho = data[0].ancho * 1;
    var ancho2 = ancho.toFixed(4);

    var alto = data[0].alto * 1;
    var alto2 = alto.toFixed(4);

    document.getElementById('myImg').src = dir;
    document.getElementById('txt_descripcion').value = data[0].descripcion;
    document.getElementById('txt_codigo').value = data[0].codigo;
    document.getElementById('txt_comentario').value = data[0].comentario;

    document.getElementById('txt_largo').value = largo2;
    document.getElementById('txt_ancho').value = ancho2;
    document.getElementById('txt_alto').value = alto2;


    document.getElementById('result2').innerHTML = volumen2;
    document.getElementById('result3').innerHTML = pesoVolumetrico2;

    document.getElementById('txt_peso_volumetrico').value = data[0].peso_volumetrico;

    document.getElementById('txt_peso_volumetrico').value = data[0].peso_volumetrico;

    select2.asignarValor('cboEstado', data[0].estado);

    select2.asignarValor('cboBienTipo', data[0].bien_tipo_id);
    select2.asignarValor('cboEmpresa', data[0].codigo_empresa);
    select2.asignarValor('cboUnidadTipo', data[0].codigo_unidad_control);

    if (!isEmpty(data[0]['unidad_medida_tipo_id']))
    {
        select2.asignarValor("cboUnidadTipo", data[0]['unidad_medida_tipo_id'].split(";"));
    }
    loaderClose();
}

function llenarcboUnidadControlEditar(data)
{
    select2.asignarValor('cboUnidadControl', data[0].unidad_control_id);
    loaderClose();
}

function updateBien(id, descripcion, codigo, tipo_bien, estado, comentario, empresa, file, unidad_tipo,
        unidad_control_id, largo, ancho, alto, peso_volumetrico)
{
    if (validarFormulario()) {
        loaderShow();

        ax.setAccion("updateBien");
        ax.addParamTmp("id_bien", id);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("codigo", codigo);

        if (commonVars.bienTipoId != -1) {
            ax.addParamTmp("tipo", tipo_bien);
        } else {
            ax.addParamTmp("tipo", -1);
        }

        ax.addParamTmp("estado", estado);
        ax.addParamTmp("comentario", comentario);
        ax.addParamTmp("empresa", empresa);
        ax.addParamTmp("file", file);
        ax.addParamTmp("unidad_tipo", unidad_tipo);
        ax.addParamTmp("unidad_control_id", unidad_control_id);
        ax.addParamTmp("largo", largo);
        ax.addParamTmp("ancho", ancho);
        ax.addParamTmp("alto", alto);
        ax.addParamTmp("peso_volumetrico", peso_volumetrico);
        ax.consumir();
    } else
    {
        loaderClose();
    }
}

function generarCodigoBarra()
{
    var codigo = document.getElementById("txt_codigo").value;
    $("#bcTarget").barcode("11111111", "ean8", {barWidth: 5, barHeight: 30});
}

function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
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

function cargarPantallaListar()
{
    loaderShow(null);
    cargarDiv("#window", "vistas/com/bien/bien_listar.php", tituloGlobal);
}

//MODAL PROVEEDORES
function abrirModalProveedor() {
    loaderShow();
    listarComboProveedores();
    $('#modalProveedores').modal('show');
}

function listarComboProveedores() {
    ax.setAccion("obtenerComboProveedores");
    ax.consumir();
}


var dataComboProveedor;
var banderaComboProveedor = 0;

function onResponseObtenerComboProveedores(data) {
    if (banderaComboProveedor === 0) {
        dataComboProveedor = data;
        llenarCombo("cboProveedor1", "id", "persona_nombre", "codigo_identificacion", data);
        llenarCombo("cboProveedor2", "id", "persona_nombre", "codigo_identificacion", data);
        llenarCombo("cboProveedor3", "id", "persona_nombre", "codigo_identificacion", data);
        llenarCombo("cboProveedor4", "id", "persona_nombre", "codigo_identificacion", data);
        banderaComboProveedor = 1;

    }

    if (!isEmpty(dataBienPersona)) {
        llenarModalProveedores(dataBienPersona);
    }
}

function llenarCombo(cboId, itemId, itemDes, itemDoc, data) {
    //document.getElementById(cboId).innerHTML = "";
    //asignarValorSelect2(cboId, "");
    if (!isEmpty(data)) {
        $('#' + cboId).append('<option value="-1">Ninguno</option>');
        $.each(data, function (index, item) {
            $('#' + cboId).append('<option value="' + item[itemId] + '">' + item[itemDes] + ' | ' + item[itemDoc] + '</option>');
        });
    }
}

var listaProveedorId = [];
var listaComboProveedor = [];
var listaPrioridad = [];
function agregarProveedor(cboProveedor, prioridad) {
    var proveedorId = document.getElementById(cboProveedor).value;

    eliminarProveedorDeLista(cboProveedor);

    if (validarProveedorRepetido(listaProveedorId, proveedorId, cboProveedor)) {
        if (validarPrioridad(listaPrioridad, prioridad, cboProveedor, proveedorId)) {

            if (proveedorId != -1) {
                listaProveedorId.push(proveedorId);
                listaComboProveedor.push(cboProveedor);
                listaPrioridad.push(prioridad);
            }
        }
    }
}

function validarPrioridad(listaPrioridad, prioridad, cboProveedor, proveedorId) {
    var valido = true;
    if (proveedorId != -1) {
        var indice = listaPrioridad.indexOf(prioridad - 1);
        if ((indice + 2) != prioridad) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione proveedor en orden de prioridad");

            //recarga el combo
            limpiaCombo(cboProveedor);
            valido = false;
        }
    }
    return valido;
}

function validarProveedorRepetido(listaProveedorId, proveedorId, cboProveedor) {

    var valido = true;

    if (proveedorId != -1) {
        var indice = listaProveedorId.indexOf(proveedorId);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El proveedor ya ha sido seleccionado");

            //recarga el combo
            limpiaCombo(cboProveedor);
            llenarCombo(cboProveedor, "id", "persona_nombre", dataComboProveedor);

            valido = false;
        }
    }
    return valido;
}

function limpiaCombo(cboProveedor) {
    asignarValorSelect2(cboProveedor, "");
}

function asignarValorSelect2(id, valor) {

    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

function eliminarProveedorDeLista(cboProveedor) {
    var indice = listaComboProveedor.indexOf(cboProveedor);
    if (indice > -1) {
        listaComboProveedor.splice(indice, 1);
        listaProveedorId.splice(indice, 1);
        listaPrioridad.splice(indice, 1);
    }
}

function reiniciarComboProveedores() {
    listaProveedorId = [];
    listaPrioridad = [];
    listaComboProveedor = [];
    banderaComboProveedor = 0;

    limpiaCombo("cboProveedor1");
    limpiaCombo("cboProveedor2");
    limpiaCombo("cboProveedor3");
    limpiaCombo("cboProveedor4");
}

var banderaLlenarProveedores = 0;
function llenarModalProveedores(data) {
    if (banderaLlenarProveedores === 0) {
        for (var i = 0; i < data.length; i++) {
//            console.log(data[i]);
            asignarValorSelect2('cboProveedor' + data[i]['prioridad'], data[i]['persona_id']);

            listaProveedorId.push(data[i]['persona_id']);
            listaComboProveedor.push('cboProveedor' + data[i]['prioridad']);
            listaPrioridad.push(parseInt(data[i]['prioridad']));
        }
        banderaLlenarProveedores = 1;
    }
}

//cerrar modal
function validarCerrarModal() {
    if (validarModalProveedoresRepetidos()) {
        $('#modalProveedores').modal('hide');
    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione proveedores en orden de prioridad");
    }

}

function validarModalProveedoresRepetidos() {

    var suma = 0;
    var n = listaPrioridad.length;
    for (var i = 0; i < listaPrioridad.length; i++) {
        suma = suma + listaPrioridad[i];

    }

    var total = n * (n + 1) / 2;

    if (total == suma) {
        return true;
    } else {
        return false;
    }

}

// PESTAÑA PRECIO
var listaPrecioDetalle = [];

var arrayPrecioTipo = [];
var arrayPrecioTipoText = [];
var arrayUnidadMedida = [];
var arrayUnidadMedidaText = [];
var arrayMoneda = [];
var arrayMonedaText = [];
var arrayPrecio = [];
var arrayDescuento = [];
var arrayIncluyeIGV = [];
var arrayBienPrecioId = [];
var arrayCheckIGV = [];

function agregarPrecioDetalle() {
    //alert("Hola");

//    var precioTipo = $('#cboPrecioTipo').val();

    var precioTipo = select2.obtenerValor('cboPrecioTipo');
    var unidadMedida = $('#cboUnidadMedida').val();
    var moneda = $('#cboMoneda').val();
    var precio = $('#txtprecio').val();
    var descuento = $('#txtDescuento').val();
    var idPrecioDetalle = $('#idPrecioDetalle').val();

    var incluyeIGV;
    var checkIGV = 0;
    if (document.getElementById("chkIncluyeIGV").checked) {
        precio = precio / 1.18;
        checkIGV = 1;
    }
    incluyeIGV = precio * 1.18;

    // ids tablas
    var bienPrecioId = null;
    //alert(idPrecioDetalle);

    if (validarFormularioPrecioDetalle(precioTipo, unidadMedida, moneda, precio, descuento)) {
        if (validarPrecioDetalleRepetido(precioTipo, unidadMedida, moneda, precio, incluyeIGV)) {
            var precioTipoText = $('#cboPrecioTipo').find(':selected').text();
            var unidadMedidaText = $('#cboUnidadMedida').find(':selected').text();
            var monedaText = $('#cboMoneda').find(':selected').text();

            //alert("....");

            if (idPrecioDetalle != '') {
                //alert('igual');

                arrayPrecioTipo[idPrecioDetalle] = precioTipo;
                arrayPrecioTipoText[idPrecioDetalle] = precioTipoText;
                arrayUnidadMedida[idPrecioDetalle] = unidadMedida;
                arrayUnidadMedidaText[idPrecioDetalle] = unidadMedidaText;
                arrayMoneda[idPrecioDetalle] = moneda;
                arrayMonedaText[idPrecioDetalle] = monedaText;
                arrayPrecio[idPrecioDetalle] = precio;
                arrayDescuento[idPrecioDetalle] = descuento;
                arrayIncluyeIGV[idPrecioDetalle] = incluyeIGV;
                arrayCheckIGV[idPrecioDetalle] = checkIGV;

                // ids de tablas relacionadas
                bienPrecioId = arrayBienPrecioId[idPrecioDetalle];

                listaPrecioDetalle[idPrecioDetalle] = [precioTipo, precioTipoText, unidadMedida, unidadMedidaText, moneda, monedaText, precio, descuento, bienPrecioId, incluyeIGV, checkIGV];
            } else {
                //alert('diferente');

                arrayPrecioTipo.push(precioTipo);
                arrayPrecioTipoText.push(precioTipoText);
                arrayUnidadMedida.push(unidadMedida);
                arrayUnidadMedidaText.push(unidadMedidaText);
                arrayMoneda.push(moneda);
                arrayMonedaText.push(monedaText);
                arrayPrecio.push(precio);
                arrayDescuento.push(descuento);
                arrayIncluyeIGV.push(incluyeIGV);
                arrayCheckIGV.push(checkIGV);

                listaPrecioDetalle.push([precioTipo, precioTipoText, unidadMedida, unidadMedidaText, moneda, monedaText, precio, descuento, bienPrecioId, incluyeIGV, checkIGV]);
            }

            onListarPrecioDetalle(listaPrecioDetalle);
            limpiarCamposPrecioDetalle();
            limpiarMensajesPrecioDetalle();

        }
    }
}

function validarFormularioPrecioDetalle(precioTipo, unidadMedida, moneda, precio, descuento) {
    var bandera = true;
    limpiarMensajesPrecioDetalle();

    if (precioTipo === '' || precioTipo === null) {
        $("#msjPrecioTipo").removeProp(".hidden");
        $("#msjPrecioTipo").text("Tipo de precio es obligatorio").show();
        bandera = false;
    }

    if (unidadMedida === '' || unidadMedida === null) {
        $("#msjUnidadMedida").removeProp(".hidden");
        $("#msjUnidadMedida").text("Unidad de medida es obligatorio").show();
        bandera = false;
    }

    if (moneda === '' || moneda === null) {
        $("#msjMoneda").removeProp(".hidden");
        $("#msjMoneda").text("Moneda es obligatorio").show();
        bandera = false;
    }

    if (precio === '' || precio === null) {
        $("#msjPrecio").removeProp(".hidden");
        $("#msjPrecio").text("Precio es obligatorio").show();
        bandera = false;
    }

    if (precio <= 0) {
        $("#msjPrecio").removeProp(".hidden");
        $("#msjPrecio").text("Precio tiene que ser positivo.").show();
        bandera = false;
    }

    if (descuento === '' || descuento === null) {
        $("#msjDescuento").removeProp(".hidden");
        $("#msjDescuento").text("Descuento es obligatorio").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesPrecioDetalle() {
    $("#msjPrecioTipo").hide();
    $("#msjUnidadMedida").hide();
    $("#msjMoneda").hide();
    $("#msjPrecio").hide();
    $("#msjPrecioDetalle").hide();
    $("#msjDescuento").hide();

}

function validarPrecioDetalleRepetido(precioTipo, unidadMedida, moneda, precio, incluyeIGV) {
    var valido = true;

    var idPrecioDetalle = $('#idPrecioDetalle').val();

    //alert(idPrecioDetalle + ' : '+ indicePrecioTipo);

    if (idPrecioDetalle != '') {
        //alert('igual');
        var indice = buscarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, incluyeIGV);
//        console.log(indice,idPrecioDetalle);
        if (indice != idPrecioDetalle && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El precio ya ha sido agregado");
            valido = false;
        } else {
            valido = true;
        }
    } else {
        //alert('diferente');
        var indice = buscarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, incluyeIGV);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El precio ya ha sido agregado");
            valido = false;
        }
    }

    return valido;
}

function onListarPrecioDetalle(data) {
    $('#dataTablePrecio tbody tr').remove();
    var cuerpo = "";
    var ind = 0;
    if (!isEmpty(data)) {
        data.forEach(function (item) {

            //listaPrecioDetalle.push([ precioTipo, precioTipoText, unidadMedida, unidadMedidaText, moneda, monedaText, precio,descuento,bienPrecioId,incluyeIGV,checkIGV]);


            var eliminar = "<a href='#' onclick = 'eliminarPrecioDetalle(\""
                    + item['0'] + "\", \"" + item['2'] + "\", \"" + item['4'] + "\", \"" + item['6'] + "\", \"" + item['9'] + "\")' >"
                    + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a href='#' onclick = 'editarPrecioDetalle(\""
                    + item['0'] + "\", \"" + item['2'] + "\", \"" + item['4'] + "\", \"" + item['6'] + "\", \"" + ind + "\")' >"
                    + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            cuerpo += "<tr>"
                    + "<td style='text-align:left;'>" + item['1'] + "</td>"
                    + "<td style='text-align:left;'>" + item['3'] + "</td>"
                    + "<td style='text-align:left;'>" + item['5'] + "</td>"
                    + "<td style='text-align:right;'>" + (item['6'] * 1).toFixed(2) + "</td>"
                    + "<td style='text-align:right;'>" + (item['9'] * 1).toFixed(2) + "</td>"
                    + "<td style='text-align:right;'>" + (item['7'] * 1).toFixed(2) + "</td>"
                    + "<td style='text-align:center;'>" + editar + eliminar
                    + "</td>"
                    + "</tr>";

            ind++;
        });

        $('#dataTablePrecio tbody').append(cuerpo);
    }
}

function limpiarCamposPrecioDetalle() {
    $('#txtprecio').val('');
    $('#txtDescuento').val('0.00');
    $('#idPrecioDetalle').val('');
}

function buscarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, incluyeIGV) {
    var tam = arrayPrecioTipo.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayPrecioTipo[i] === precioTipo && arrayUnidadMedida[i] === unidadMedida && arrayMoneda[i] === moneda /*&& arrayPrecio[i] === precio && arrayIncluyeIGV[i] === incluyeIGV*/) {
            ind = i;
            break;
        }
    }
    return ind;
}

function editarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, ind) {
    var indice = ind;
    //var indice = buscarObjEspecifico(variable, formula, medicion, puesto, meta, metaValor);

    asignarValorSelect2('cboPrecioTipo', arrayPrecioTipo[indice]);
    asignarValorSelect2('cboUnidadMedida', arrayUnidadMedida[indice]);
    asignarValorSelect2('cboMoneda', arrayMoneda[indice]);

//    $('#txtprecio').val(arrayPrecio[indice]);
    $('#txtDescuento').val(redondearNumero(arrayDescuento[indice]).toFixed(2));
    $('#idPrecioDetalle').val(ind);

    if (arrayCheckIGV[indice] == 1) {
        document.getElementById("chkIncluyeIGV").checked = true;
        $('#txtprecio').val(redondearNumero(arrayIncluyeIGV[indice]).toFixed(2));
    } else {
        document.getElementById("chkIncluyeIGV").checked = false;
        $('#txtprecio').val(redondearNumero(arrayPrecio[indice]).toFixed(2));
    }
}

var listaBienPrecioEliminado = [];

function eliminarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, incluyeIGV) {
    var indice = buscarPrecioDetalle(precioTipo, unidadMedida, moneda, precio, incluyeIGV);
    if (indice > -1) {
        arrayPrecioTipo.splice(indice, 1);
        arrayPrecioTipoText.splice(indice, 1);
        arrayUnidadMedida.splice(indice, 1);
        arrayUnidadMedidaText.splice(indice, 1);
        arrayMoneda.splice(indice, 1);
        arrayMonedaText.splice(indice, 1);
        arrayPrecio.splice(indice, 1);
        arrayDescuento.splice(indice, 1);
        arrayIncluyeIGV.splice(indice, 1);
        arrayCheckIGV.splice(indice, 1);
    }

    if (!isEmpty(arrayBienPrecioId[indice])) {
        var bienPrecioId = arrayBienPrecioId[indice];
        arrayBienPrecioId.splice(indice, 1);
        listaBienPrecioEliminado.push([bienPrecioId]);
    }

    listaPrecioDetalle = [];
    var tam = arrayPrecioTipo.length;
    for (var i = 0; i < tam; i++) {
        listaPrecioDetalle.push([arrayPrecioTipo[i], arrayPrecioTipoText[i], arrayUnidadMedida[i], arrayUnidadMedidaText[i], arrayMoneda[i], arrayMonedaText[i], arrayPrecio[i], arrayDescuento[i], arrayBienPrecioId[i], arrayIncluyeIGV[i], arrayCheckIGV[i]]);
    }

    onListarPrecioDetalle(listaPrecioDetalle);
}

function obtenerDecuento() {
//    var descuento=dataPrecioTipo[document.getElementById('cboPrecioTipo').options.selectedIndex]['descuento'];
//    descuento=redondearNumero(descuento).toFixed(2);
//    $('#txtDescuento').val(descuento);
}

function habilitarDivMarcaTexto() {
    $("#contenedorMarcaDivCombo").hide();
    $("#contenedorMarcaDivTexto").show();
}

function habilitarDivMarcaCombo() {
    $("#contenedorMarcaDivTexto").hide();
    $("#contenedorMarcaDivCombo").show();
}

function habilitarDivMaquinariaTexto() {
    $("#contenedorMaquinariaDivCombo").hide();
    $("#contenedorMaquinariaDivTexto").show();
}

function habilitarDivMaquinariaCombo() {
    $("#contenedorMaquinariaDivTexto").hide();
    $("#contenedorMaquinariaDivCombo").show();
}

function validarToken() {
    if (token == 2) {
        setTimeout("self.close();", 700);
    }
}

function validaNumero(e) {
    var tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros
    var patron = /[0-9]/;
    var tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}




var contadorCentroCosto = 1;
var dataCentroCosto = [];
var dataCentroCostoPersona = [];

function obtenerCentroCostoBien() {
    let arrayCentroCostoBien = [];
    let banderaValidacion = false;
    let totalPorcentaje = 0;
    for (var i = 1; i <= contadorCentroCosto; i++) {
        if ($('#cboCentroCosto_' + i).length > 0) {
            let centro_costo_id = select2.obtenerValor('cboCentroCosto_' + i);
            let porcentaje = $('#txtPorcentaje_' + i).val();

            if (isEmpty(centro_costo_id) || isEmpty(porcentaje)) {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Debe seleccionar los centro de costo o el porcentaje.");
                banderaValidacion = true;
                break;
            } else {
                totalPorcentaje += porcentaje * 1;
                arrayCentroCostoBien.push({centro_costo_id: centro_costo_id, porcentaje: porcentaje});
            }
        }
    }

    if (banderaValidacion) {
        return [];
    }

    if (totalPorcentaje != 100) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El total de porcentaje debe ser 100%.");
        return [];
    }

    return arrayCentroCostoBien;
}

function cargarCentroCostoBien(data) {
    $('#dataTableCentroCostoBien tbody tr').remove();
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            agregarCentroCostoBien(item.centro_costo_id, item.porcentaje);
        });
    } else {
        agregarCentroCostoBien();
    }
}

function agregarCentroCostoBien(centroCosto, porcentaje) {
    let indice = contadorCentroCosto;

    let eliminar = "<a  onclick='eliminarCentroCostoBienDetalle(" + indice + ");'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";
    let cuerpo = "<tr id='trCentroCostoPersona_" + indice + "'>";
    cuerpo += "<td style='border:0; vertical-align: middle;'>" +
            "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<select name='cboCentroCosto_" + indice + "' id='cboCentroCosto_" + indice + "' class='select2'>" +
            "</select></div></td>";

    cuerpo += "<td style='border:0; vertical-align: middle;'><div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<input type='number' id='txtPorcentaje_" + indice + "' name='txtPorcentaje_" + indice + "' class='form-control' required='' aria-required='true' value='' min='1' max='100' style='text-align: right;'/></div></td>" +
            "<td style='text-align:center;'>" + eliminar + "</td>" +
            "</tr>";
    $('#dataTableCentroCostoBien tbody').append(cuerpo);
    if (!isEmpty(dataCentroCosto)) {
        $.each(dataCentroCosto, function (indexPadre, centroCostoPadre) {
            if (isEmpty(centroCostoPadre.centro_costo_padre_id)) {
                let html = '<optgroup id="' + centroCostoPadre.id + '" label="' + centroCostoPadre['codigo'] + ' | ' + centroCostoPadre['descripcion'] + '">';
                let dataHijos = dataCentroCosto.filter(centroCosto => centroCosto.centro_costo_padre_id == centroCostoPadre.id);
                $.each(dataHijos, function (indexHijo, centroCostoHijo) {
                    html += '<option value="' + centroCostoHijo['id'] + '">' + centroCostoHijo['codigo'] + " | " + centroCostoHijo['descripcion'] + '</option>';
                });
                html += ' </optgroup>';
                $('#cboCentroCosto_' + indice).append(html);
            }
        });

        $("#cboCentroCosto_" + indice).select2({
            width: "100%"
        });

        select2.asignarValor("cboCentroCosto_" + indice, "-1");
        if (!isEmpty(centroCosto)) {
            select2.asignarValor("cboCentroCosto_" + indice, centroCosto);
        }
    }

    if (!isEmpty(porcentaje)) {
        $("#txtPorcentaje_" + indice).val(redondearNumero(porcentaje));
    }

    contadorCentroCosto++;
}

function eliminarCentroCostoBienDetalle(indice) {
    $('#trCentroCostoPersona_' + indice).remove();
}