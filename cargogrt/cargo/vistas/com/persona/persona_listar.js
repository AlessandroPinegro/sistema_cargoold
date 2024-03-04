var c = $('#env i').attr('class');
var buscar = false;

var personaTipoVentana = 0;
$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    ax.setSuccess("successPersonaListar");
    select2.iniciar();
    personaTipoVentana = getParameterByName('personaTipo');
    if (!isEmpty(tokenId)) {
        editarPersona(tokenId, tokenTipo);
    } else if (!isEmpty(tokenTipo) && tokenTipo == 2) {
        cargarFormulario(tokenTipo, 'PN');
    } else if (!isEmpty(tokenTipo) && tokenTipo == 4) {
        cargarFormulario(tokenTipo, 'PJ');
    } else {
        configuracionesIniciales();
        colapsarBuscadorPersona();
        cambiarAnchoBusquedaDesplegable();
    }
    modificarAnchoTabla('datatable');
});

function exportarReporteExcel(colapsa) {
    loaderShow();
    ax.setAccion("ExportarPersonaExcel");
    ax.consumir();
}

function configuracionesIniciales()
{
    ax.setAccion("configuracionesInicialesPersonaListar");
    ax.consumir();
}

function onresponseConfiguraciones(data)
{
//    console.log(data);
    $('#listaPersonaTipo').empty();
    var perJuridica;
    var perNatural;
    var html;
    if (!isEmpty(data.persona_tipo))
    {
        $('#cboTipoPersonaBusqueda').append('<option value="-1">Seleccionar tipo de persona</option>');
        $.each(data.persona_tipo, function (index, value) {
            /*
             * Para el buscador
             */
            $('#cboTipoPersonaBusqueda').append('<option value="' + value.id + '">' + value.descripcion + '</option>');

            //Para boton nuevo           
//            $('#listaPersonaTipo').append('<li><a data-toggle="modal" data-target="#accordion-modal" onclick="cargarFormularioPersona(' + value.id + ',\'' + value.descripcion + '\',\'' + value.ruta + '\')">' + value.descripcion + '</a></li>');

            if (value.id == 2) {
                perNatural = value;
            } else {
                perJuridica = value;
            }
        });

        html = '' +
                '<button type="button" class="btn btn-info"  style="margin-right: 10px;" title="Nueva Persona Juridica" onclick="cargarFormularioPersona(' + perJuridica.id + ',\'' + perJuridica.descripcion + '\',\'' + perJuridica.ruta + '\')"><i class=" fa fa-plus-square-o"></i>&nbsp; Nueva ' + perJuridica.descripcion + '</button>  &nbsp;&nbsp;' +
                '<button type="button" class="btn btn-info"  title="Nueva Persona Natural" onclick="cargarFormularioPersona(' + perNatural.id + ',\'' + perNatural.descripcion + '\',\'' + perNatural.ruta + '\')"><i class=" fa fa-plus-square-o"></i>&nbsp; Nueva ' + perNatural.descripcion + '</button>' +
                '';

        $('#listaPersonaTipo').append(html);
    }

    /*
     * Cargar dato en el select multiple de busqueda clase de persona
     */
    if (!isEmpty(data.persona_tipo))
    {
        $.each(data.persona_clase, function (index, value) {
            $('#cboClasePersonaBusqueda').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    }
    listarPersona();
}

var personaTipoIdG;
var valorPersonaTipoG;
var rutaG;

function cargarFormularioPersona(personaTipoId, valor_persona_tipo, ruta)
{
    personaTipoIdG = personaTipoId;
    valorPersonaTipoG = valor_persona_tipo;
    rutaG = ruta;

    loaderShow(null);
    ax.setAccion("obtenerPersonaClaseAsociada");
    ax.addParamTmp("personaTipoId", personaTipoId);
    ax.consumir();
}

function onResponseObtenerPersonaClaseAsociada(data) {
    if (isEmpty(data)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', 'No tiene permisos para crear el tipo de persona.');
    } else {
        cargarFormulario(personaTipoIdG, valorPersonaTipoG, rutaG);
    }
}

function cargarFormulario(personaTipoId, valor_persona_tipo, ruta)
{
    loaderShow(null);
    var dependiente = document.getElementById('hddIsDependiente').value;
//    obtenerTitulo(dependiente);
    if (!isEmpty(valor_persona_tipo))
    {
        valor_persona_tipo = valor_persona_tipo.toLowerCase();
    }
    if (dependiente == 0)
    {
        commonVars.personaId = 0;
        commonVars.personaTipoId = personaTipoId;
        cargarDiv('#window', 'vistas/com/persona/persona_form.php', "Nueva " + valor_persona_tipo);
    } else
    {
        cargarDivModal('#respuesta', ruta, "Nueva " + valor_persona_tipo);
    }
}

var nombres;
var codigo;
var tipoPersona;
var clasePersona;

function listarPersona() {
//    var nombres = $("#txtNombresBusqueda").val();
//    var codigo = $("#txtCodigoBusqueda").val();
//    var tipoPersona = $("#cboTipoPersonaBusqueda").val();
//    var clasePersona = $("#cboClasePersonaBusqueda").val();
    ax.setAccion("getDataGridPersona");
    ax.addParamTmp("nombres", nombres);
    ax.addParamTmp("codigo", codigo);
    //ax.addParamTmp("tipoPersona", tipoPersona);
    ax.addParamTmp("tipoPersona", tipoPersona != '-1' ? tipoPersona : null);
    ax.addParamTmp("clasePersona", clasePersona);
    $('#datatable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(true),
        "scrollX": true,
        "autoWidth": true,
        "columns": [
            {"data": "codigo_identificacion"},
            {"data": "persona_nombre_completo"},
            {"data": "telefono"},
            {"data": "direccion"},
            {"data": "descripcion"},
            {"data": "persona_clase_descripcion"},
//            {data: "estado", "width": "20",
//                render: function (data, type, row) {
//                    if (type === 'display') {
//                        if (row.estado == 1)
//                        {
//                            return '<a onclick ="cambiarEstadoPersona(' + row.id + ')" ><b><i class="ion-checkmark-circled" style="color:#5cb85c"></i><b></a>';
//                        } else {
//                            return '<a onclick ="cambiarEstadoPersona(' + row.id + ')"><b><i class="ion-flash-off" style="color:#cb2a2a"></i><b></a>';
//                        }
//                    }
//                    return data;
//                },
//                "orderable": true,
//                "class": "alignCenter"
//            },
            {data: "id",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a onclick="editarPersona(' + row.id + ',' + row.persona_tipo_id + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>\n\
                                   <a onclick="confirmarDeletePersona(' + row.id + ')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></a>';
                    }
                    return data;
                },
                "orderable": true,
                "class": "alignCenter"
            },
        ],
        columnDefs: [
            {
                "render": function (data) {
                    if (data.length > 34) {
                        data = data.substring(0, 31) + '...';
                    }
                    return data;
                },
                "targets": 1
            },
            {
                "render": function (data) {
                    if (!isEmpty(data)) {
                        if (data.length > 37) {
                            data = data.substring(0, 34) + '...';
                        }
                    }
                    return data;
                },
                "targets": 3
            }
        ],
//        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        "order": [1, "asc"],
        destroy: true
    });


    nombres = null;
    codigo = null;
    tipoPersona = null;
    clasePersona = null;

   loaderClose();
}

function confirmarDeletePersona(id)
{
    BANDERA_ELIMINAR = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás a la persona",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            deletePersona(id);
        } else {
            if (BANDERA_ELIMINAR == false) {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}

function successPersonaListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {

            case 'cambiarEstadoPersona':
                onResponseCambiarEstadoPersona(response.data);
                listarPersona();
                break;
            case 'deletePersona':
                var error = response.data[0]['vout_exito'];
                if (error == 1) {
                    swal("Eliminado!", "Persona eliminada correctamente", "success");
                } else {
                    swal("Cancelado", response.data[0]['vout_mensaje'] + "No se pudo eliminar", "error");
                }
                bandera_eliminar = true;
                listarPersona();
                break;
            case 'configuracionesInicialesPersonaListar':
                onresponseConfiguraciones(response.data);
                break;
            case 'getAllEmpresa':
                onResponseGetAllEmpresas(response.data);
                break;
                break;
            case 'importPersona':
                $('#fileInfo').html('');
                $('#resultado').append(response.data);
                break;
            case 'ExportarPersonaExcel':
                loaderClose();
                location.href = URL_BASE + "util/formatos/lista_de_personas.xlsx";
                break;
            case 'buscarCriteriosBusquedaPersona':
                onResponseBuscarCriteriosBusquedaPersona(response.data);
                loaderClose();
                break;
            case 'obtenerPersonaClaseAsociada':
                onResponseObtenerPersonaClaseAsociada(response.data);
                loaderClose();
                break;
        }
    } else
    {
        switch (response[PARAM_ACCION_NAME]) {
            case 'deletePersona':
                swal("Cancelado", "No se pudo eliminar, esta en uso", "error");
        }
    }
}

function deletePersona(id)
{
    ax.setAccion("deletePersona");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cambiarEstadoPersona(id)
{
    ax.setAccion("cambiarEstadoPersona");
    ax.addParamTmp("id", id);
    ax.consumir();
}
function onResponseCambiarEstadoPersona(data)
{
    if (data[0]["vout_exito"] == 1)
    {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
    } else
    {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"] + "No se puede cambiar de estado.");
    }
}
function obtenerTitulo(dependiente)
{
    tituloGlobal = $("#titulo").text();
    var titulo = tituloGlobal;
    if (dependiente == 0)
    {
        $("#window").empty();
    }
    if (!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarListarPersonaCancelar()
{
    loaderShow(null);
    cargarDiv('#window', 'vistas/com/persona/persona_listar.php', tituloGlobal);
}

function buscarPersona(colapsa)
{
    buscar = true;
    var cadena;
    cadena = obtenerDatosBusqueda();
    cadena = (!isEmpty(cadena) && cadena !== 0) ? cadena : "Todos";
    $('#idPopover').attr("data-content", cadena);
    $('[data-toggle="popover"]').popover('show');
    obtenerParametrosBusqueda();
    listarPersona();
    if (colapsa === 1)
        colapsarBuscadorPersona();
}

var actualizandoBusquedaPersona = false;

function colapsarBuscadorPersona() {
    if (actualizandoBusquedaPersona) {
        actualizandoBusquedaPersona = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}
function obtenerDatosBusqueda()
{
    var cadena = "";
    var nombres = $("#txtNombresBusqueda").val();
    var codigo = $("#txtCodigoBusqueda").val();
    var tipoPersona = $("#cboTipoPersonaBusqueda").val();
    var clasePersona = $("#cboClasePersonaBusqueda").val();


    if (!isEmpty(codigo))
    {
        cadena += StringNegrita("Cód. Id.: ");

        cadena += codigo;
        cadena += "<br>";
    }
    if (!isEmpty(nombres))
    {
        cadena += StringNegrita("Nombre: ");

        cadena += nombres;
        cadena += "<br>";
    }
    if (tipoPersona != -1)
    {
        cadena += StringNegrita("Tipo de persona: ");

        cadena += select2.obtenerText('cboTipoPersonaBusqueda');
        cadena += "<br>";
    }
    if (!isEmpty(clasePersona))
    {
        cadena += StringNegrita("Clase de persona: ");
        cadena += select2.obtenerTextMultiple('cboClasePersonaBusqueda');
        cadena += "<br>";
    }
    return cadena;
}
function editarPersona(id, tipo) {
    loaderShow(null);
    commonVars.personaId = id;
    commonVars.personaTipoId = tipo;
    cargarDiv("#window", "vistas/com/persona/persona_form.php", "Editar " + obtenerTitulo());
}
function actualizarBusquedaPersona()
{
    actualizandoBusquedaPersona = true;
//    var estadobuscador = $('#bg-info').attr("aria-expanded");
//    if (estadobuscador == "false")
//    {
    buscarPersona(0);
//    }
}
/*IMPORTAR EXCEL*/
$(function () {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
//            $fileupload = $('#file');
//            $fileupload.replaceWith($fileupload.clone(true));
        }
    });
});

function imageIsLoaded(e) {
    $('#secretFile').attr('value', e.target.result);
    importPersona();
}

function validarFormularioCarga(documento) {
    var bandera = true;
    var espacio = /^\s+$/;

    if (documento === "" || documento === null || espacio.test(documento) || documento.length === 0) {
        $("#lblDoc").text("Documento es obligatorio").show();
        bandera = false;
    }
    return bandera;
}
function getAllEmpresaImport()
{
    ax.setAccion("getAllEmpresa");
    ax.consumir();
}
/*FIN IMPORTAR EXCEL*/

function importPersona() {
    getAllEmpresaImport();
    $('#resultado').empty();
    $('#btnImportar').show();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Cancelar");
    $("#cboEmpresa").attr("disabled", false);
    asignarValorSelect2('cboEmpresa', "");
    $('#modalPersona').modal('show');
}

function importar()
{
    var file = document.getElementById('secretFile').value;
    var empresa = $('#cboEmpresa').val();

    if (isEmpty(empresa))
    {
        $("msj_empresa").removeProp(".hidden");
        $("#msj_empresa").text("Seleccionar una empresa").show();
        return;
    }

    $('#resultado').empty();
    $('#btnImportar').hide();
    $('#btnSalirModal').empty();
    $('#btnSalirModal').append("<i class='fa ion-android-close'>&nbsp;&nbsp;</i>Salir");
    $("#cboEmpresa").attr("disabled", true);

    loaderShow(".modal-content");
    ax.setAccion("importPersona");
    ax.addParam("file", file);
    ax.addParam("empresa_id", empresa);
    ax.consumir();
}
function onResponseGetAllEmpresas(data) {

    if (!isEmpty(data))
    {
        $('#cboEmpresa').empty();
        $.each(data, function (index, value) {
            $('#cboEmpresa').append('<option value="' + value.id + '">' + value.razon_social + '</option>');
        });
    }
    acciones.getEmpresa = true;
}

function asignarValorSelect2(id, valor)
{
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({width: '100%'});
}

$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

$('#txtBuscar').keyup(function (e) {
    var bAbierto = $('#txtBuscar').attr("aria-expanded");

    if (!eval(bAbierto)) {
        $('#txtBuscar').dropdown('toggle');
    }

});

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 90) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 90) + "px");
}

function obtenerParametrosBusqueda() {
    nombres = $("#txtNombresBusqueda").val();
    codigo = $("#txtCodigoBusqueda").val();
    tipoPersona = $("#cboTipoPersonaBusqueda").val();
    clasePersona = $("#cboClasePersonaBusqueda").val();
}

function llenarParametrosBusqueda(nombresTxt, codigoTxt, tipoPersonaTxt, clasePersonaTxt) {

    var clasePersonaIds = [];
    if (!isEmpty(clasePersonaTxt)) {
        clasePersonaIds.push(clasePersonaTxt);
    }

    if (!isEmpty(codigoTxt)) {
        nombresTxt = null;
    }

    nombres = nombresTxt;
    codigo = codigoTxt;
    tipoPersona = tipoPersonaTxt;
    clasePersona = clasePersonaIds;

    //loaderShow();
    listarPersona();
}

$("#txtBuscar").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        buscarCriteriosBusquedaPersona();
    }
});

function buscarCriteriosBusquedaPersona() {


    loaderShow();
    ax.setAccion("buscarCriteriosBusquedaPersona");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.consumir();
}

function onResponseBuscarCriteriosBusquedaPersona(data) {
    var dataPersona = data.dataPersona;
    var dataPersonaClase = data.dataPersonaClase;

    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="llenarParametrosBusqueda(\'' + item.nombre + '\',\'' + item.codigo_identificacion + '\',' + null + ',' + null + ')" >';
            html += '<span class="col-md-1"><i class="ion-person"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.codigo_identificacion + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.nombre + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }

    if (!isEmpty(dataPersonaClase)) {
        $.each(dataPersonaClase, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="llenarParametrosBusqueda(' + null + ',' + null + ',' + null + ',' + item.id + ')" >';
            html += '<span class="col-md-1"><i class="ion-person-stalker"></i></span>';
            html += '<span class="col-md-11">';
            html += '<label style="color: #141719;">' + item.persona_clase_descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }

    $("#ulBuscadorDesplegable2").append(html);
 loaderClose();
//    console.log(dataPersona);
}

function limpiarBuscadores() {
    $('#txtCodigoBusqueda').val('');
    $('#txtNombresBusqueda').val('');

    select2.asignarValor('cboClasePersonaBusqueda', -1);
    select2.asignarValor('cboTipoPersonaBusqueda', -1);
}