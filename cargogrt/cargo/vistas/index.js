var ax = new Ajaxp(URL_EXECUTECONTROLLER, 'POST', 'JSON');
var commonVars = {
    movimientoTipoId: 0,
    titulo: 0,
    empresa: 0,
    bienId: 0,
    bienTipoId: 0,
    personaId: 0,
    personaTipoId: 0,
    documentoTipoDatoId: 0,
    DocumentoTipoDatoListaId: 0,
    auditoriaId: 0
};

var token;
var tokenId;
var tokenTipo;
var tokenRetorno;
var alturaMenuComprimido = 0;

$(document).ready(function () {
    token = getParameterByName('token');
    tokenId = getParameterByName('id');
    tokenTipo = getParameterByName('tipo');
    tokenRetorno = getParameterByName('retorno');

    loaderShow();
    altura();
    ax.setOpcion(4);
    ax.setSuccess("successPerfil");
    cargarListaEmpresas();

    var sideBar = new SideBar();
    sideBar.init();
});

$('#contra_actual').keypress(function () {
    $('#msj_actual').hide();
});
$('#contra_nueva').keypress(function () {
    $('#msj_nueva').hide();
});
$('#contra_confirmar').keypress(function () {
    $('#msj_confirmar').hide();
});


function altura() {
    $("#espacio").height("100px");
    var h2 = 0;
    var h4 = 0;
    h2 = $(window).height();

    h4 = $("#cuerpo").outerHeight();

    var es = "<div id='espacio'></div>";
    $("#window").after(es);
    //h+h1+h3   
    var vacio = h2 - (h4);
    $("#espacio").height(vacio);
}

function calculaEspacio(){
//    var navegacion = $(".navigation").height() + 140 + $(".logo").height();
    var contenido = $("#window").height();
    if (contenido < alturaMenuComprimido){
        $("#espacio").height(alturaMenuComprimido - contenido);
    }
}

function cargarDivIndex(div, url, id, titulo, banderaAgregar = 1)
{
    if ("left-panel collapsed" !== $('aside.left-panel')[0].className) {
        $('.navbar-toggle').click();
    }
    commonVars.titulo = titulo;
    $('div').remove('.sweet-overlay');
    $('div').remove('.sweet-alert');
    $("#window").html("");

    $(div).load(url, function (responseTxt, statusTxt, xhr) {
        if (statusTxt == "success")
            $('#titulo').empty();
        $('#titulo').append(titulo);

        if (banderaAgregar == 1 && $('#divAgregarDocumento').length > 0) {
            $('#divAgregarDocumento').show();
        } else if ($('#divFormularioBusqueda').length > 0) {
            $('#divAgregarDocumento').html('');
            $('#divFormularioBusqueda').addClass('col-md-12').removeClass('col-md-10');
        }
    });
    ax.setOpcion(id);
//    mostrar el loader 

    loaderShow();
    setTimeout(function () {
        calculaEspacio();
    }, 3000);
}

function obtenerPantallaPrincipal(id)
{
    ax.setAccion("obtenerPantallaPrincipal");
    ax.consumir();
}
function successPerfil(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'ObtenerEmpresasXUsuarioId':
                onResponseObtenerEmpresasXUsuario(response.data);
                //loaderClose();
                break;
            case 'obtenerMenuXEmpresa':
                // Esta funcion es comun, esta en commonsOnResponseAjaxp
                break;
        }
    }
}

function commonsOnResponseAjaxp(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerMenuXEmpresa':
                onResponseObtenerMenuXEmpresa(response.data);
                if (token == '' || token == null)
                {
                    obtenerPantallaPrincipal();
                } else
                {
                    obtenerPantallaXToken();
                }

                break;
            case 'obtenerPantallaPrincipal':
                if (!isEmpty(response.data)) {
                    cargarDivIndex("#window", response.data[0].url, response.data[0].id, response.data[0].nombre);
                    active(response.data[0].id, response.data[0].padre);
                }
                loaderClose();
                break;
            case 'obtenerPantallaXToken':
                cargarDivIndex("#window", response.data[0].url, response.data[0].id, response.data[0].nombre);
                active(response.data[0].id, response.data[0].padre);
                loaderClose();
                break;
        }
    }
}

function  obtenerPantallaXToken()
{
//    alert(token);
    ax.setAccion("obtenerPantallaXToken");
    ax.addParamTmp("token", token);
    ax.addParamTmp("id", tokenId);
    ax.addParamTmp("tipo", tokenTipo);
    ax.consumir();
}

function ocultar() {
    $("ul").removeAttr("style");
}

function ajustarAnchoBuscador() {
    if ($("#ulBuscadorDesplegable").length > 0) {
        setTimeout(function () {
            cambiarAnchoBusquedaDesplegable();
        }, 500);
    }
}

//POR SI EN EL JS NO HAY EL METODO
function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function active_menu(j, k) {
    $("ul li").removeClass("active");
    $("ul #l" + k).addClass("active");
    $("ul li ul li").removeClass("active");
    $("ul li ul #m" + j).addClass("active");
}
function calr() {
    $("div").remove("#ui-datepicker-div");
    $("div").remove("#ui-datepicker-div");
}
function cargarListaEmpresas()
{
    ax.setAccion("ObtenerEmpresasXUsuarioId");
    ax.consumir();
}

function obtenerMenuXEmpresa(idEmpresa)
{
    commonVars.empresa = idEmpresa;
    ax.setAccion("obtenerMenuXEmpresa");
    ax.addParamTmp("id_empresa", idEmpresa);
    ax.consumir();
}


function onResponseObtenerEmpresasXUsuario(data) {
    if (!isEmpty(data))
    {
        asignarTituloMenuNav(data[0]['alias']);
        asignarImagenMenuNav(data[0]['icono']);
        obtenerMenuXEmpresa(data[0]['id']);
        $.each(data, function (index, value) {
            $('#listaEmpresa').append('<li><a onclick="cargarMenuEmpresa(' + value.id + ',\'' + value.alias + '\',\'' + value.icono + '\')">' + value.alias + '</a></li>');

        });
    }
}

function onResponseObtenerMenuXEmpresa(data) {
//    console.log(data);
    $('#menuEmpresa').empty();
    $('#window').empty();
    alturaMenuComprimido = 0;
    if (!isEmpty(data))
    {
        var dataMenu;
        var id_li_padre;
        var $id_li_hijo;
        var idOpcion = 0;
        alturaMenuComprimido = data.length * 65 + 132.5;
        $.each(data, function (index, value) {
            dataMenu = "";
            id_li_padre = "l" + value.id;
            dataMenu = '<li id="' + id_li_padre + '" class="has-submenu" ><a href="#"><i class="' + value.icono_padre + '"></i><span class="nav-label">' + value.nombre + '</span></a>';
            dataMenu += '<ul class="list-unstyled">';

            if (value.indicador_negocio == 1) {
                var urlNuevoMov = '';

                $.each(value.hijo, function (index, valueHijo) {
                    if (valueHijo.url.indexOf('tipoInterfaz=6') != -1) {
                        urlNuevoMov = 'movimiento_form_tablas_liquidacion.php?tipoInterfaz=6';
                    }else if (valueHijo.url.indexOf('tipoInterfaz=5') != -1) {
                        urlNuevoMov = 'movimiento_form_tablas_static.php?tipoInterfaz=2';
                    } else if (valueHijo.url.indexOf('tipoInterfaz=4') != -1) {
                        urlNuevoMov = 'movimiento_form_dua.php?tipoInterfaz=2';
                    } else if (valueHijo.url.indexOf('tipoInterfaz=3') != -1) {
                        urlNuevoMov = 'movimiento_form_tablas_atencion.php?tipoInterfaz=3';
                    } else {
                        urlNuevoMov = 'movimiento_form_tablas.php?tipoInterfaz=2';
                    }

                    idOpcion = (isEmpty(valueHijo["opcion_id"])) ? valueHijo.id : valueHijo["opcion_id"];
                    $id_li_hijo = "m" + valueHijo.id;
                    dataMenu += '<li id="' + $id_li_hijo + '">';
                    let banderaAgregar = 0;
                    let styleLi = '';
                    if (valueHijo.bandera_accion_agregar == 1) {
                        banderaAgregar = 1;
                        dataMenu += '<a style="display: table-cell; padding-right: 5px;"';
                        dataMenu += ' onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + 'vistas/com/movimiento/' + urlNuevoMov + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');active(' + valueHijo.id + ',' + value.id + ');">';
                        if ((valueHijo.nombre !== "Recepción" && valueHijo.nombre!="Despacho" && valueHijo.url !== "vistas/com/movimientoPedido/movimiento_descargo_pedido_listar.php") || valueHijo.nombre !== "Entrega") {
                            dataMenu += '<span class="badge bg-primary">+</span></a>';
                        }
                    } else {
                        styleLi = 'style="display: table-cell; padding-right: 5px;"';
                    }
                    dataMenu += '<a  ' + styleLi + ' data-toggle="reload" onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + valueHijo.url + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\', ' + banderaAgregar + ');';
                    dataMenu += ' active(' + valueHijo.id + ',' + value.id + ');"style="display: table-cell;padding-left: 0px;">';
                    dataMenu += '<i class="' + valueHijo.icono + ' "style="width: 15px;"></i>' + valueHijo.nombre + '</a></li>';
                });
            } else if (value.indicador_negocio == 2) {
                $.each(value.hijo, function (index, valueHijo) {
                    idOpcion = (isEmpty(valueHijo["opcion_id"])) ? valueHijo.id : valueHijo["opcion_id"];
                    $id_li_hijo = "m" + valueHijo.id;
                    dataMenu += '<li id="' + $id_li_hijo + '">';
                    dataMenu += '<a style="display: table-cell; padding-right: 5px;"';
                    dataMenu += ' onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + 'vistas/com/operacion/operacion_form_tablas.php' + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');active(' + valueHijo.id + ',' + value.id + ');">';
                    dataMenu += '<span class="badge bg-primary">+</span></a>';
                    dataMenu += '<a data-toggle="reload" onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + valueHijo.url + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');';
                    dataMenu += ' active(' + valueHijo.id + ',' + value.id + ');"style="display: table-cell;padding-left: 0px;">';
                    dataMenu += '<i class="' + valueHijo.icono + ' "style="width: 15px;"></i>' + valueHijo.nombre + '</a></li>';
                });
            } else {
                $.each(value.hijo, function (index, valueHijo) {

                    //para agregar un boton nuevo.
                    if (valueHijo.url == 'vistas/com/pago/cobranza_documentos_cobrados.php') {
                        idOpcion = (isEmpty(valueHijo["opcion_id"])) ? valueHijo.id : valueHijo["opcion_id"];
                        $id_li_hijo = "m" + valueHijo.id;
                        dataMenu += '<li id="' + $id_li_hijo + '">';
                        dataMenu += '<a style="display: table-cell; padding-right: 5px;"';
                        dataMenu += ' onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + 'vistas/com/pago/cobranza.php' + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');active(' + valueHijo.id + ',' + value.id + ');">';
                        dataMenu += '<span class="badge bg-primary">+</span></a>';
                        dataMenu += '<a data-toggle="reload" onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + valueHijo.url + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');';
                        dataMenu += ' active(' + valueHijo.id + ',' + value.id + ');"style="display: table-cell;padding-left: 0px;">';
                        dataMenu += '<i class="' + valueHijo.icono + ' "style="width: 15px;"></i>' + valueHijo.nombre + '</a></li>';

                    } else if (valueHijo.url == 'vistas/com/pago/pago_documentos_pagados.php') {
                        idOpcion = (isEmpty(valueHijo["opcion_id"])) ? valueHijo.id : valueHijo["opcion_id"];
                        $id_li_hijo = "m" + valueHijo.id;
                        dataMenu += '<li id="' + $id_li_hijo + '">';
                        dataMenu += '<a style="display: table-cell; padding-right: 5px;"';
                        dataMenu += ' onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + 'vistas/com/pago/pago.php' + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');active(' + valueHijo.id + ',' + value.id + ');">';
                        dataMenu += '<span class="badge bg-primary">+</span></a>';
                        dataMenu += '<a data-toggle="reload" onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + valueHijo.url + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');';
                        dataMenu += ' active(' + valueHijo.id + ',' + value.id + ');"style="display: table-cell;padding-left: 0px;">';
                        dataMenu += '<i class="' + valueHijo.icono + ' "style="width: 15px;"></i>' + valueHijo.nombre + '</a></li>';
                    } else {
                        idOpcion = (isEmpty(valueHijo["opcion_id"])) ? valueHijo.id : valueHijo["opcion_id"];
                        $id_li_hijo = "m" + valueHijo.id;
                        dataMenu += '<li id="' + $id_li_hijo + '"><a data-toggle="reload" onclick="cargarDivIndex(\'#window\',\'' + URL_BASE + valueHijo.url + '\',\'' + idOpcion + '\',\'' + valueHijo.nombre + '\');';
                        dataMenu += ' active(' + valueHijo.id + ',' + value.id + ');"><i class="' + valueHijo.icono + '"></i>' + valueHijo.nombre + '</a></li>';
                    }
                });
            }

            dataMenu += '</ul></li>';
            $('#menuEmpresa').append(dataMenu);
        });
        var sideBar = new SideBar();
        sideBar.init();
    }
}

function cargarMenuEmpresa(id, alias, imagen)
{
    loaderShow(null);
    asignarTituloMenuNav(alias);
    asignarImagenMenuNav(imagen);
    obtenerMenuXEmpresa(id);
}

function asignarTituloMenuNav(titulo)
{
    $('#nombreEmpresa').empty();
    $('#nombreEmpresa').append(titulo);
}

function asignarImagenMenuNav(imagen)
{
    if (imagen != "null")
    {
        document.getElementById("imagenEmpresa").src = URL_BASE + "vistas/images/" + imagen;
    } else
    {
        document.getElementById("imagenEmpresa").src = "";
    }
}


