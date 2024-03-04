var c = $('#env i').attr('class');
var dataObtenerDocumentoTipoDatoLista = '';
var acciones = {
    obtenerComboDocumentoTipoDato: false,
    obtenerDocumentoTipoDatoLista: false,
};
$("#txtDescripcion").keypress(function () {
    $('#msjDescripcion').hide();
});

$("#cboDocumentoTipoDato").change(function () {
    $('#msjDocumentoTipoDato').hide();
});
function validarFormulario() {
    var bandera = true;
    var espacio = /^\s+$/;
    var numero = !/^([0-9])*$/;
    var descripcion = document.getElementById('txtDescripcion').value;
    var documentoTipoDatoId = document.getElementById('cboDocumentoTipoDato').value;
    if (documentoTipoDatoId == "" || documentoTipoDatoId == null || espacio.test(documentoTipoDatoId) || documentoTipoDatoId.length == 0)
    {
        $("msjDocumentoTipoDato").removeProp(".hidden");
        $("#msjDocumentoTipoDato").text("Seleccionar un tipo de documento").show();
        bandera = false;
    }
    if (descripcion == "" || descripcion == null || espacio.test(descripcion) || descripcion.length == 0)
    {
        $("msjDescripcion").removeProp(".hidden");
        $("#msjDescripcion").text("Ingresar una descripción").show();
        bandera = false;
    }
    return bandera;
}

function cargarComponentes()
{
    ax.setSuccess("successlistarDocumentoTipoDatoLista");


//    $("#cboEstado").hide();

    $('#spnImporte').spinner();
    $('#spnPeriodo').spinner();
    $('#spnPeriodoGracia').spinner();
    cargarSelect2();

}
function cargarSelect2()
{
    $(".select2").select2({
        width: '100%'
    });
}
function deshabilitarBoton()
{
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton()
{
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}
function obtenerComboDocumentoTipoDato()
{
    ax.setAccion("obtenerComboDocumentoTipoDato");
    ax.consumir();
}

function successlistarDocumentoTipoDatoLista(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerComboDocumentoTipoDato':
                acciones.obtenerComboDocumentoTipoDato = true;
                cargarDataComboDocumentoTipoDato(response.data);
                verificarCargarComplemento();
                break;
            case 'insertarDocumentoTipoDatoLista':
                exitoInsertar(response.data)
                break;
            case 'obtenerDocumentoTipoDatoLista':
                acciones.obtenerDocumentoTipoDatoLista= true;
                dataObtenerDocumentoTipoDatoLista = response.data;
                verificarCargarComplemento();
                break;
            case 'actualizarDocumentoTipoDatoLista':
                exitoActualizar(response.data)
                break;
        }
    }
}

function verificarCargarComplemento()
{
    if (acciones.obtenerComboDocumentoTipoDato && acciones.obtenerDocumentoTipoDatoLista)
    {
        if (dataObtenerDocumentoTipoDatoLista == '' || dataObtenerDocumentoTipoDatoLista == null)
        {
            loaderClose();
        } else
        {
            $("#txtDescripcion").val(dataObtenerDocumentoTipoDatoLista['0']['descripcion']);
            $("#txtValor").val(dataObtenerDocumentoTipoDatoLista['0']['valor']);
            asignarValorSelect2("cboDocumentoTipoDato", dataObtenerDocumentoTipoDatoLista['0']['documento_tipo_dato_id']);
            asignarValorSelect2("cboEstado", dataObtenerDocumentoTipoDatoLista['0']['estado']);
            loaderClose();
        }
    }
}
function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}
function exitoInsertar(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        cargarPantallaListar();
    }
}
function exitoActualizar(data)
{
    if (data[0]["vout_exito"] == 0)
    {
        habilitarBoton();
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    }
    else
    {
        habilitarBoton();
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
         cargarPantallaListar();
    }
}
function cargarDataComboDocumentoTipoDato(data)
{
    $('#cboDocumentoTipoDato').append('<option value="">' + "" + '</option>');
    $.each(data, function (index, item) {
        $('#cboDocumentoTipoDato').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
    });
}
function enviarDocumentoTipoDatoLista()
{
    var id = document.getElementById('id').value;
    var tipoAccion = document.getElementById('tipoAccion').value;
    var descripcion = document.getElementById('txtDescripcion').value;
    var documentoTipoDatoId = document.getElementById('cboDocumentoTipoDato').value;
    var valor = document.getElementById('txtValor').value;
    var estado = document.getElementById('cboEstado').value;
    if (tipoAccion == 1)
    {
        actualizarDocumentoTipoDatoLista(id, documentoTipoDatoId, descripcion, valor, estado);
    } else {
        insertarDocumentoTipoDatoLista(documentoTipoDatoId, descripcion, valor, estado);
    }
}
function insertarDocumentoTipoDatoLista(documentoTipoDatoId, descripcion, valor, estado)
{
    if (validarFormulario()) {
        deshabilitarBoton();
        ax.setAccion("insertarDocumentoTipoDatoLista");
        ax.addParamTmp("documentoTipoDatoId", documentoTipoDatoId);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("valor", valor);
        ax.addParamTmp("estado", estado);
        ax.consumir();
    }
}
function actualizarDocumentoTipoDatoLista(id, documentoTipoDatoId, descripcion, valor, estado)
{
    if (validarFormulario()) {
        deshabilitarBoton();
        ax.setAccion("actualizarDocumentoTipoDatoLista");
        ax.addParamTmp("id", id);
        ax.addParamTmp("documentoTipoDatoId", documentoTipoDatoId);
        ax.addParamTmp("descripcion", descripcion);
        ax.addParamTmp("valor", valor);
        ax.addParamTmp("estado",estado );
        ax.consumir();
    }
}
function obtenerDocumentoTipoDatoLista()
{
    var id = document.getElementById('id').value;
    ax.setAccion("obtenerDocumentoTipoDatoLista");
    ax.addParamTmp("documentoTipoDatoListaId", id);
    ax.consumir();
}

function cargarPantallaListar()
{
    cargarDiv("#window", "vistas/com/documento/documentoTipoDatoListaListar.php",obtenerTitulo());
}

function obtenerTitulo()
{
    TITULO = $("#titulo").text();
    var titulo =  TITULO;
    $("#window").empty();
    
    if(!isEmpty(titulo))
    {
        var partes = titulo.split(" ");
        var cadena ="";
        $.each(partes, function (index, item) {
             if(index!=0)
             {
                 cadena += item + " ";
             }
        });
        
        if(!isEmpty(cadena))
        {
            titulo = capitaliseFirstLetter(cadena);
        }
        
    }
    return titulo;
}

function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
