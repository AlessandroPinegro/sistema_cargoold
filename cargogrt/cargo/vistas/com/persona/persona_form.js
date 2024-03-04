var c = $('#env i').attr('class');
var anchoComboSunat2;
var dataPersonaGlobal;
var data_ubigeo;
var data_zona;
$(document).ready(function () {
    controlarDomXTipoPersona();
    select2.iniciar();
    anchoComboSunat2 = $("#divCboCodigoSunat2").width();

    ax.setSuccess("successPersona");
    ax.setAccion("obtenerConfiguracionesPersona");
    ax.addParamTmp("personaId", commonVars.personaId);
    //console.log(response.data);
    ax.addParamTmp("personaTipoId", commonVars.personaTipoId);
    ax.consumir();
    

});

function onchangeTipoDocum() {
    $('#msjEstado').hide();
}

$("#cboClasePersona").on("change", function (e) {
    a = e.val;
    //console.log(e.val)
    $.each(e.val, function (index, item) {
        if (item == -3 && (e.val).length == 1) {
            $("#lblCodigoIdentificacion").html("Nro Documento");
        } else {
            $("#lblCodigoIdentificacion").html("Nro Documento *");
        }
    });
});

$('#txtCodigoIdentificacion').keypress(function () {
    $('#msjCodigoIdentificacion').hide();
});

$('#txtNombre').keypress(function () {
    $('#msjNombre').hide();
});

$('#txtRazonSocial').keypress(function () {
    $('#msjRazonSocial').hide();
});

$('#txtApellidoPaterno').keypress(function () {
    $('#msjApellidoPaterno').hide();
});

$('#txtApellidoMaterno').keypress(function () {
    $('#msjApellidoMaterno').hide();
});

$('#txtTelefono').keypress(function () {
    $('#msjTelefono').hide();
});

$('#txtCelular').keypress(function () {
    $('#msjCelular').hide();
});

$('#txtEmail').keypress(function () {
    $('#msjEmail').hide();
});

$('#txtRUC').keypress(function () {
    $('#msjRUC').hide();
});

$('#txtRazonSocial').keypress(function () {
    $('#msjRazonSocial').hide();
});

$('#txtDireccion').keypress(function () {
    $('#msjDireccion').hide();
});

function limpiar_mensajes() {
    $('#msjNombre').hide();
    $('#msjApellidoPaterno').hide();
    $('#msjApellidoMaterno').hide();
    $('#msjTelefono').hide();
    $('#msjRazonSocial').hide();
}
function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(c);
    $("#env i").addClass('fa fa-spinner fa-spin');
}
function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(c);
}

function successPersona(response) {
    console.log("llego");
     console.log(response);
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerPersona':
                editarPersona(response.data);
                break;
            case 'obtenerConfiguracionesPersona':
                onresponseConfiguracionesPersona(response.data);
                dataPersonaGlobal = response.data.personaNatural;

                ///::::::CAMBIOS JESUS::::::::::::::
                 console.log(response.data);
                 var arrayperfil=[]
                 arrayperfil = response.data.perfil
                 var filtrarperfil = arrayperfil.filter(elemento=> elemento.id == 120);
                 if(filtrarperfil.length >=1){
                    var cajaDeTexto = document.getElementById("divcrecojoreparto");
                    var divaprobacionreparto = document.getElementById("divaprobacionreparto");
                    cajaDeTexto.style.display = "block";
                    divaprobacionreparto.style.display = "block";
                  } else{
                    var divaprobacionreparto = document.getElementById("divaprobacionreparto");
                    divaprobacionreparto.style.display = "none";
                    var cajaDeTexto = document.getElementById("divcrecojoreparto");
                    cajaDeTexto.style.display = "none";
                  }
                  break;
                ///::::::CAMBIOS JESUS::::::::::::::
            case 'insertPersona':
                mostrarOk(response.data['0'].vout_mensaje);
                validarToken(response.data['0'].id);
                loaderClose();
                habilitarBoton();
                cargarListarPersonaCancelar();
                break;
            case 'updatePersona':
                mostrarOk(response.data['0'].vout_mensaje);
                validarToken(response.data['0'].persona_id);
                loaderClose();
                habilitarBoton();
                cargarListarPersonaCancelar();
                break;
            case 'obtenerConsultaDocumento':
                onresponseObtenerConsultaDocumento(response.data);
                loaderClose();
                console.log(response.data);
                break;
            case 'obtenerPersonasNaturales':
                onResponseObtenerPersonasNaturales(response.data);
                break;
            case 'obtenerDataConvenioSunat':
                onResponseObtenerDataConvenioSunat(response.data);
                loaderClose();
                break;
            case 'validarSimilitud':
                onResponseValidarSimilitudes(response.data);
                //loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'insertPersona':
                loaderClose();
                habilitarBoton();
                break;
            case 'updatePersona':
                loaderClose();
                habilitarBoton();
                break;
            case 'onresponseObtenerConsultaDocumento':
                loaderClose();
                break;
            case 'validarSimilitud':
                loaderClose();
                habilitarBoton();
                break;
        }
    }
}

var direccionTipoFiscal;
var convenioSunatId0 = null;
function onresponseConfiguracionesPersona(data) {
    //console.log(data.personaContacto);
    var empresaIds = '';

    $.each(data.empresa, function (i, item) {
        empresaIds = empresaIds + ";" + item["id"];
    });

    if (commonVars.personaId > 0) {
        select2.cargar("cboEmpresa", data.empresa, "id", "razon_social");
        select2.cargar("cboClasePersona", data.persona_clase, "id", "descripcion");
    } else {
        select2.cargarAsignaUnico("cboEmpresa", data.empresa, "id", "razon_social");
        select2.cargarAsignaUnico("cboClasePersona", data.persona_clase, "id", "descripcion");
    }

    //cargar combos sunat    
    $("#cboCodigoSunat2").select2({ width: anchoComboSunat2 + "px" });

    select2.cargar("cboCodigoSunat", data.dataSunatDetalle, "id", ["codigo", "descripcion"]);
    select2.cargar("cboCodigoSunat2", data.dataSunatDetalle2, "id", ["codigo", "descripcion"]);
    select2.cargar("cboCodigoSunat3", data.dataSunatDetalle3, "id", ["codigo", "descripcion"]);

    convenioSunatId0 = data.dataSunatDetalle3[0]["id"];

    select2.asignarValor('cboEmpresa', empresaIds.split(";"));

    if (personaTipoVentana == 2) {
        select2.asignarValor('cboClasePersona', ["-3"]);
    }

    $("#liPersonaContactos").show();

    if (!isEmpty(data.personaNatural)) {
        select2.cargar("cboContacto", data.personaNatural, "id", ["persona_nombre", "codigo_identificacion"]);
    }

    if (!isEmpty(data.contactoTipo)) {
        select2.cargar("cboContactoTipo", data.contactoTipo, "id", "descripcion");
    }

    $('#cboTipoDocumento').append('<option value="" selected disabled>Selecionar Tipo de Documento</option>',
        '</select>');
    if (!isEmpty(data.tipoDocumento)) {
        $.each(data.tipoDocumento, function (index, item) {
            $('#cboTipoDocumento').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
        });
    }
    $("#cboTipoDocumento").select2("val", "");
    $("#liPersonaContactos").hide();
    // debugger;
    // if (jQuery.inArray(data.perfil[0]['id'], data.perfil_asignado) === -1) {

    //     $("#chkDevolucionCargo").parent().hide();
    // }
    // else {

    //     $("#chkDevolucionCargo").parent().show();


    // }


    //    if (commonVars.personaTipoId != 2) {
    //        //validamos si tiene permiso para visualizar la clase de persona "Contacto"
    //        var tipoContacto = true;
    ////        $.each(data.personaClaseXUsuario, function (i, item) {
    ////            if (parseInt(item.id) == -3) {
    ////                tipoContacto = true;
    ////                return false;
    ////            }
    ////        });
    //
    //        if (tipoContacto) {
    //            $("#liPersonaContactos").show();
    //
    //            if (!isEmpty(data.personaNatural)) {
    //                select2.cargar("cboContacto", data.personaNatural, "id", ["persona_nombre", "codigo_identificacion"]);
    //            }
    //
    //            if (!isEmpty(data.contactoTipo)) {
    //                select2.cargar("cboContactoTipo", data.contactoTipo, "id", "descripcion");
    //            }
    //        } else {
    //            $("#liPersonaContactos").hide();
    //        }
    //    }

    if (!isEmpty(data.direccionTipo)) {
        //obtengo la descripcion de la direccion fiscal.
        $.each(data.direccionTipo, function (i, item) {
            if (parseInt(item.id) == -1) {
                direccionTipoFiscal = item.descripcion;
                return false;
            }
        });

        select2.cargar("cboDireccionTipo", data.direccionTipo, "id", "descripcion");
    }

    if (!isEmpty(data.dataUbigeo)) {
        select2.cargar("cboUbigeo", data.dataUbigeo, "id", ["ubigeo_codigo", "ubigeo_dep", "ubigeo_prov", "ubigeo_dist"]);
        data_ubigeo = data.dataUbigeo;
    }

    if (!isEmpty(data.dataZona)) {
        //select2.cargar("cboZona", data.dataZona, "id", "agencia_zona_desc");
        data_zona = data.dataZona;
    }
    $("#cboZona").prop('disabled', 'disabled');

    if (!isEmpty(data.persona)) {
        llenarFormularioEditar(data.persona);
    }

    if (!isEmpty(data.personaDireccion)) {
        llenarFormularioPersonaDireccion(data.personaDireccion);
    }

    if (!isEmpty(data.personaContacto)) {
        llenarFormularioPersonaContacto(data);
    }

    loaderClose();
}

$("#cboUbigeo").change(function () {

    var ubigeo = '';
    var array_new = '';
    asignarValorSelect2('cboZona', null);

    ubigeo = buscarElemento(data_ubigeo, 'id', null, select2.obtenerValor('cboUbigeo'), null, 'id');
    array_new = filterItems(data_zona, $.trim(ubigeo));

    select2.cargar("cboZona", array_new, "id", "agencia_zona_desc");
    $("#cboZona").prop('disabled', false);
});

function cargarZona() {
    var Ubigeo = '';
    var array_new = '';
    Ubigeo = buscarElemento(data_ubigeo, 'id', null, select2.obtenerValor('cboUbigeo'), null, 'id');
    array_new = filterItems(data_zona, $.trim(Ubigeo));
    select2.cargar("cboZona", array_new, "id", "agencia_zona_desc");
    $("#cboZona").prop('disabled', false);
}

function filterItems(arr, query) {
    return arr.filter(el => el.ubigeo == query);
}

// Función para buscar por uno o dos criterios, un elemento o un solo valor
function buscarElemento(array, id_busquedad, id_busquedad2, valor, valor2, id_respuesta) {

    var respuesta = "";
    if (!isEmpty(array)) {
        $.each(array, function (index, item) {
            if (item[id_busquedad] == valor) {
                if (!isEmpty(id_busquedad2)) {
                    if (item[id_busquedad2] == valor2) {
                        respuesta = isEmpty(id_respuesta) ? item : item[id_respuesta];
                        return false;
                    }
                } else {
                    respuesta = isEmpty(id_respuesta) ? item : item[id_respuesta];
                    return false;
                }
            }
        });
    }
    return respuesta;
}
function llenarFormularioPersonaDireccion(dataPersonaDireccion) {
    // llenado de los array de Persona contacto

    //    console.log(dataPersonaDireccion);
    $(dataPersonaDireccion).each(function (index) {
        //ids
        var ubigeo = dataPersonaDireccion[index].ubigeo_id;
        var direccionTipo = dataPersonaDireccion[index].direccion_tipo_id;

        //texto
        var ubigeoText = dataPersonaDireccion[index].ubigeo_descripcion;
        var direccionTipoText = dataPersonaDireccion[index].direccion_tipo_descripcion;
        direccionTipoText = direccionTipoText.trim();
        var direccionText = dataPersonaDireccion[index].direccion;
        var referencia = dataPersonaDireccion[index].referencia;
        var zonaId = dataPersonaDireccion[index].zona_id;
        var zonaText = dataPersonaDireccion[index].zona_descripcion;
        var referenciaText = dataPersonaDireccion[index].referencia;

        var latitudText = parseFloat(dataPersonaDireccion[index].latitud);
        var longitudText = parseFloat(dataPersonaDireccion[index].longitud);
        // ids de tablas relacionadas
        var personaDireccionId = dataPersonaDireccion[index].id;

        arrayDireccionTipo.push(direccionTipo);
        arrayDireccionTipoText.push(direccionTipoText);
        arrayUbigeo.push(ubigeo);
        arrayUbigeoText.push(ubigeoText);
        arrayDireccionText.push(direccionText);
        arrayZona.push(zonaId);
        arrayZonaText.push(zonaText);
        arrayReferencia.push(referenciaText);

        arrayZonaText.push(zonaText);
        arrayReferencia.push(referenciaText);

        arrayLatitud.push(latitudText);
        arrayLongitud.push(longitudText);
        // array ids
        arrayPersonaDireccionId.push(personaDireccionId);

        listaDireccionDetalle.push([direccionTipo, direccionTipoText, ubigeo, ubigeoText, direccionText, personaDireccionId, zonaId, zonaText, referencia, latitudText, longitudText]);
        onListarDireccionDetalle(listaDireccionDetalle);

    });
    // fin
}

function llenarFormularioPersonaContacto(data) {
    // llenado de los array de Persona contacto

    var dataPersonaContacto = data.personaContacto;
    var dataPersonaNatural = data.personaNatural;
    //console.log(dataPersonaContacto);
    $(dataPersonaContacto).each(function (index) {

        var contacto = dataPersonaContacto[index].persona_id;
        var contactoTipo = dataPersonaContacto[index].contacto_tipo_id;

        //texto
        var contactoTipoText = dataPersonaContacto[index].contacto_tipo_descripcion;
        var contactoText = dataPersonaContacto[index].persona_nombre_codigo;
        var contactoTelefonoText = dataPersonaContacto[index].celular !== null ? dataPersonaContacto[index].celular : "Sin celular.";
        contactoTelefonoText += dataPersonaContacto[index].telefono !== null ? dataPersonaContacto[index].telefono : " / Sin teléfono.";
        var contactoEmailText = dataPersonaContacto[index].email !== null ? dataPersonaContacto[index].email : "Sin email.";

        // ids de tablas relacionadas
        var personaContactoId = dataPersonaContacto[index].id;

        arrayContactoTipo.push(contactoTipo);
        arrayContactoTipoText.push(contactoTipoText);
        arrayContacto.push(contacto);
        arrayContactoText.push(contactoText);
        arrayContactoTelefonoText.push(contactoTelefonoText);
        arrayContactoEmailText.push(contactoEmailText);

        // array ids
        arrayPersonaContactoId.push(personaContactoId);

        listaContactoDetalle.push([contactoTipo, contactoTipoText, contacto, contactoText, personaContactoId, contactoTelefonoText, contactoEmailText]);
        onListarContactoDetalle(listaContactoDetalle);

    });
    // fin
}

function cancelarRegistro() {
    if (token == 1) {
        self.close();
    } else {
        cargarListarPersonaCancelar();
    }
}

function cargarListarPersonaCancelar() {
    loaderShow(null);
    cargarDiv('#window', 'vistas/com/persona/persona_listar.php', 'Listar persona');
}
function controlarDomXTipoPersona() {
   

    $("#lblCodigoIdentificacion").empty();
    $("#lblNombre").empty();
    if (commonVars.personaTipoId == 2) {
        $("#empresa").empty();
        $("#contenedorNombres").show();
        $("#contenedorApellidoPaterno").show();
        $("#contenedorApellidoMaterno").show();
        $("#lblCodigoIdentificacion").append("Nro Documento *");
        $("#lblNombre").append("Nombres *");
        $("#contenedorBuscarDocumento").show();
    } else {
        $("#persona").empty();
        $("#contenedorBuscarRUC").show();
        $("#contenedorRazonSocial").show();
        $("#lblCodigoIdentificacion").append("RUC *")
        $("#lblNombre").append("Razón social *");
    }

    $("#liPersonaContactos").hide();
}

function validarSimilitud() {

    if (commonVars.personaTipoId == 2) {
        var nombre = trim(document.getElementById('txtNombre').value);
        var apellido_paterno = trim(document.getElementById('txtApellidoPaterno').value);
        loaderShow();
        ax.setAccion("validarSimilitud");
        ax.addParamTmp("personaId", commonVars.personaId);
        ax.addParamTmp("nombre", nombre);
        ax.addParamTmp("apellidoPaterno", apellido_paterno);
        ax.consumir();
    } else {
        guardarPersona();
    }
}
function onResponseValidarSimilitudes(data) {
    if (!isEmpty(data)) {
        loaderClose();
        /*var htmlTablaSimilitudes = "<table>";
         $.each(data, function (indice, valor) {
         htmlTablaSimilitudes += "<tr>";
         htmlTablaSimilitudes += "<td>" + valor['nombre'] + "</td>";
         htmlTablaSimilitudes += "<td>" + valor['apellido_paterno'] + "</td>";
         htmlTablaSimilitudes += "<td>" + valor['apellido_materno']==null?"":valor['apellido_materno']==null + "</td>";
         htmlTablaSimilitudes += "</tr>";
         });
         
         htmlTablaSimilitudes += "</table>";*/

        var htmlTablaSimilitudes = "<ul class='list-group'>";
        $.each(data, function (indice, valor) {
            var aMaterno = (isEmpty(valor['apellido_materno'])) ? " " : valor['apellido_materno'];
            var aPaterno = (isEmpty(valor['apellido_paterno'])) ? " " : valor['apellido_paterno'];
            var Nombre = (isEmpty(valor['nombre'])) ? " " : valor['nombre'];
            htmlTablaSimilitudes += "<li class='list-group-item'>";
            htmlTablaSimilitudes += " " + Nombre;
            htmlTablaSimilitudes += " " + aPaterno;
            htmlTablaSimilitudes += " " + aMaterno;
            htmlTablaSimilitudes += "</li>";
        });

        var textoSwal = "<h4>¿ Desea completar el registro de todos modos ?</h4>";
        htmlTablaSimilitudes += "</ul>";
        htmlTablaSimilitudes += textoSwal;

        swal({
            title: "¡Se encontraron nombres similares!",
            text: htmlTablaSimilitudes,
            html: true,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si, registrar!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No, cancelar registro !",
            closeOnConfirm: true,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                guardarPersona();
            } else {
                swal("Cancelado", "El registro fue cancelado", "error");
            }
        });

    } else {
        guardarPersona();
    }

}

function guardarPersona() {
    var codigoIdentificacion = trim(document.getElementById('txtCodigoIdentificacion').value);

    if (commonVars.personaTipoId == 2) {
        var nombre = trim(document.getElementById('txtNombre').value);
        var tipoDocumn = $('#cboTipoDocumento').val();
        var direccionofc = trim(document.getElementById('txtDireccionpers').value); ///$('#txtDireccionpers').val();LMCC 080923
        var licencia = trim(document.getElementById('txtLicencia').value);// LMCC 080923
    } else {
        var nombre = trim(document.getElementById('txtRazonSocial').value);
        //id de ruc segun BD 
        var tipoDocumn = 3;
        var direccionofc =trim(document.getElementById('txtDireccionfiscal').value); // LMCC 080923
        var licencia =''; // LMCC 080923
    }

    var apellido_paterno = trim(document.getElementById('txtApellidoPaterno').value);
    var apellido_materno = trim(document.getElementById('txtApellidoMaterno').value);
    var telefono = trim(document.getElementById('txtTelefono').value);
    var celular = trim(document.getElementById('txtCelular').value);
    var email = trim(document.getElementById('txtEmail').value);

    var estado = document.getElementById('cboEstado').value;
    var bandera_recojo_reparto = ($('#chkDevolucionCargo').is(':checked') ? 1 : 0);
    var bandera_credito_persona = ($('#chkAprobacionCredito').is(':checked') ? 1 : 0);
    var clase = $('#cboClasePersona').val();
    var empresa = $('#cboEmpresa').val();

    var file = $('#secretImg').val();

    var codigoSunatId = select2.obtenerValor('cboCodigoSunat');
    var codigoSunatId2 = select2.obtenerValor('cboCodigoSunat2');
    var codigoSunatId3 = select2.obtenerValor('cboCodigoSunat3');

    var nombreBCP = trim(document.getElementById('txtNombreBCP').value);

    if (validarPersona(codigoIdentificacion, nombre, apellido_paterno, apellido_materno, telefono, celular, email, empresa, clase, tipoDocumn/*, direccion*/)) {
        if (commonVars.personaId > 0) {
            actualizarPersona(commonVars.personaId, commonVars.personaTipoId, codigoIdentificacion, nombre,
                apellido_paterno, apellido_materno, telefono, celular, email, file, estado, empresa, clase,
                codigoSunatId, codigoSunatId2, codigoSunatId3, nombreBCP, tipoDocumn, bandera_recojo_reparto, bandera_credito_persona,licencia,direccionofc);
        } else {
            insertarPersona(commonVars.personaTipoId, codigoIdentificacion, nombre, apellido_paterno,
                apellido_materno, telefono, celular, email, file, estado, empresa, clase,
                codigoSunatId, codigoSunatId2, codigoSunatId3, nombreBCP, tipoDocumn, bandera_recojo_reparto, bandera_credito_persona,licencia, direccionofc);
        }
    } else {
        loaderClose();
    }
}

function insertarPersona(personaTipoId, codigoIdentificacion, nombre, apellido_paterno, apellido_materno,
    telefono, celular, email, file, estado, empresa, clase, codigoSunatId, codigoSunatId2, codigoSunatId3, nombreBCP, tipoDocumn, bandera_recojo_reparto,bandera_credito_persona,licencia, direccionofc) {
    loaderShow(null);
    deshabilitarBoton();
    ax.setAccion("insertPersona");
    ax.addParamTmp("PersonaTipoId", personaTipoId);
    ax.addParamTmp("codigoIdentificacion", codigoIdentificacion);
    ax.addParamTmp("nombre", nombre);
    ax.addParamTmp("apellido_paterno", apellido_paterno);
    ax.addParamTmp("apellido_materno", apellido_materno);
    ax.addParamTmp("telefono", telefono);
    ax.addParamTmp("celular", celular);
    ax.addParamTmp("email", email);
    ax.addParamTmp("file", file);
    ax.addParamTmp("estado", estado);
    ax.addParamTmp("empresa", empresa);
    ax.addParamTmp("clase", clase);
    ax.addParamTmp("listaContactoDetalle", listaContactoDetalle);
    ax.addParamTmp("listaDireccionDetalle", listaDireccionDetalle);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("codigoSunatId2", codigoSunatId2);
    ax.addParamTmp("codigoSunatId3", codigoSunatId3);
    ax.addParamTmp("nombreBCP", nombreBCP);
    ax.addParamTmp("tipoDocumento", tipoDocumn);
    ax.addParamTmp("bandera_recojo_reparto", bandera_recojo_reparto);
    ax.addParamTmp("bandera_credito_persona", bandera_credito_persona);
    ax.addParamTmp("licencia", licencia);
    ax.addParamTmp("direccionofc", direccionofc);
    ax.consumir();
}

function actualizarPersona(personaId, personaTipoId, codigoIdentificacion, nombre, apellido_paterno,
    apellido_materno, telefono, celular, email, file, estado, empresa, clase, codigoSunatId, codigoSunatId2, codigoSunatId3, nombreBCP, tipoDocumn, bandera_recojo_reparto,bandera_credito_persona,licencia, direccionofc) {
    loaderShow(null);
    deshabilitarBoton();
    ax.setAccion("updatePersona");
    ax.addParamTmp("id", personaId)
    ax.addParamTmp("PersonaTipoId", personaTipoId);
    ax.addParamTmp("codigoIdentificacion", codigoIdentificacion);
    ax.addParamTmp("nombre", nombre);
    ax.addParamTmp("apellido_paterno", apellido_paterno);
    ax.addParamTmp("apellido_materno", apellido_materno);
    ax.addParamTmp("telefono", telefono);
    ax.addParamTmp("celular", celular);
    ax.addParamTmp("email", email);
    ax.addParamTmp("file", file);
    ax.addParamTmp("estado", estado);
    ax.addParamTmp("empresa", empresa);
    ax.addParamTmp("clase", clase);
    ax.addParamTmp("listaContactoDetalle", listaContactoDetalle);
    ax.addParamTmp("listaPersonaContactoEliminado", listaPersonaContactoEliminado);
    ax.addParamTmp("listaDireccionDetalle", listaDireccionDetalle);
    ax.addParamTmp("listaPersonaDireccionEliminado", listaPersonaDireccionEliminado);
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.addParamTmp("codigoSunatId2", codigoSunatId2);
    ax.addParamTmp("codigoSunatId3", codigoSunatId3);
    ax.addParamTmp("nombreBCP", nombreBCP);
    ax.addParamTmp("tipoDocumento", tipoDocumn);
    ax.addParamTmp("bandera_recojo_reparto", bandera_recojo_reparto);
    ax.addParamTmp("bandera_credito_persona", bandera_credito_persona);
    ax.addParamTmp("licencia", licencia);
    ax.addParamTmp("direccionofc", direccionofc);
    ax.consumir();
}

function DNIObligatorio(idClasePersona) {
    var required = true;
    var a = $('#cboClasePersona').val();
    //Si esta vacio el combo, retorna validación. || Como el resto exigen DNI, si el tamaño del array es > 1 , contacto no es el unico y retorna validacion
    if (isEmpty(a) || a.length > 1) {
        return true;
    }


    $.each(a, function (i, e) {
        if (a[i] == idClasePersona)
            required = false
    });
    return required;
}
function validarPersona(codigoIdentificacion, nombre, apellido_paterno, apellido_materno, telefono, celular, email, empresa, clase, tipoDocumn/*, direccion*/) {

    //expresiones de validacion 
    var expresion_email = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;


    //var requiereDNI = DNIObligatorio()==-3?false:true; //Si contacto esta seleccionado
    var bandera = true;
    var cboDireccionTipo = select2.obtenerValor('cboDireccionTipo');
    var cboUbigeo = select2.obtenerValor('cboUbigeo');
    var cboZona = select2.obtenerValor('cboZona');

    if (!isEmpty(cboDireccionTipo) || !isEmpty(cboUbigeo) || !isEmpty(cboZona)) {
        mostrarAdvertencia("Tiene cambios pendientes de agregar en la pestaña dirección.");
        bandera = false;
    }

    if (commonVars.personaTipoId == 2) {
        //debugger;
        if ((isEmpty(tipoDocumn) || (isNaN(tipoDocumn) || tipoDocumn.length == 0))) {
            $("#msjEstado").removeProp(".hidden");
            $("#msjEstado").text("Seleccione un Tipo de Documento").show();
            mostrarAdvertencia("Seleccione un Tipo de Documento");
            bandera = false;
        }

        if (tipoDocumn == '1') {
            if ((isEmpty(codigoIdentificacion) || (isNaN(codigoIdentificacion) || codigoIdentificacion.length !== 8)) && DNIObligatorio(-3)) {
                $("#msjCodigoIdentificacion").removeProp(".hidden");
                $("#msjCodigoIdentificacion").text("La cantidad de caracteres para el tipo de documento seleccionado no coincide").show();
                mostrarAdvertencia("La cantidad de caracteres para el tipo de documento seleccionado no coincide");
                bandera = false;
            }
        }

        else if (tipoDocumn == '2') {
            if ((isEmpty(codigoIdentificacion) || (isNaN(codigoIdentificacion) || codigoIdentificacion.length !== 12)) && DNIObligatorio(-3)) {
                $("#msjCodigoIdentificacion").removeProp(".hidden");
                $("#msjCodigoIdentificacion").text("La cantidad de caracteres para el tipo de documento seleccionado no coincide").show();
                mostrarAdvertencia("La cantidad de caracteres para el tipo de documento seleccionado no coincide");
                bandera = false;
            }
        }

        else if (tipoDocumn == '4') {
            if ((isEmpty(codigoIdentificacion) || (isNaN(codigoIdentificacion) || codigoIdentificacion.length !== 12)) && DNIObligatorio(-3)) {
                $("#msjCodigoIdentificacion").removeProp(".hidden");
                $("#msjCodigoIdentificacion").text("La cantidad de caracteres para el tipo de documento seleccionado no coincide").show();
                mostrarAdvertencia("La cantidad de caracteres para el tipo de documento seleccionado no coincide");
                bandera = false;
            }
        }

    } else {
        //validar proveedor internacional
        var dataClase = $('#cboClasePersona').val();
        var proveedorInter = false;
        if (!isEmpty(dataClase)) {
            var index = dataClase.indexOf(claseProveedorInternacionalId);
            if (index != -1) {
                proveedorInter = true;
            }
        }

        if (proveedorInter) {
            if (isEmpty(codigoIdentificacion)) {
                $("#msjCodigoIdentificacion").removeProp(".hidden");
                $("#msjCodigoIdentificacion").text("Ingresar un código de identificación").show();
                mostrarAdvertencia("Ingresar un código de identificación");
                bandera = false;
            }
        }
        //        else if (isEmpty(codigoIdentificacion) || (isNaN(codigoIdentificacion) || codigoIdentificacion.length !== 11))
        //        {
        //            $("#msjCodigoIdentificacion").removeProp(".hidden");
        //            $("#msjCodigoIdentificacion").text("Ingresar un DNI/RUC").show();
        //            bandera = false;
        //        }
    }


    if (isEmpty(nombre) || nombre.length > 250) {
        console.log(nombre);
        $("#msjNombre").removeProp(".hidden");
        $("#msjNombre").text("Ingresar un nombre").show();
        $("#msjRazonSocial").removeProp(".hidden");
        $("#msjRazonSocial").text("Ingresar razón social").show();
        mostrarAdvertencia("Ingresar razón social");
        bandera = false;
    }

    if (commonVars.personaTipoId == 2) {
        if (isEmpty(apellido_paterno) || apellido_paterno.length > 45) {
            $("#msjApellidoPaterno").removeProp(".hidden");
            $("#msjApellidoPaterno").text("Ingresar un apellido paterno").show();
            mostrarAdvertencia("Ingresar un apellido paterno");
            bandera = false;
        }

        //        if (isEmpty(apellido_materno) || apellido_materno.length > 45)
        //        {
        //            $("#msjApellidoMaterno").removeProp(".hidden");
        //            $("#msjApellidoMaterno").text("Ingresar un apellido materno").show();
        //            bandera = false;
        //        }
    }


    //    if (commonVars.personaTipoId != 2 && isEmpty(direccion))
    //    {
    //        $("#msjDireccion").removeProp(".hidden");
    //        $("#msjDireccion").text("Ingrese dirección").show();
    //        bandera = false;
    //    }

    if (isEmpty(empresa)) {
        $("#msjEmpresa").removeProp(".hidden");
        $("#msjEmpresa").text("Seleccionar una empresa").show();
        mostrarAdvertencia("Seleccionar una empresa");
        bandera = false;
    }

    if (isEmpty(clase)) {
        $("#msjClasePersona").removeProp(".hidden");
        $("#msjClasePersona").text("Seleccionar una clase de persona").show();
        mostrarAdvertencia("Seleccionar una clase de persona");
        bandera = false;
    }
    if (isEmpty(telefono)) {
        $("#msjTelefono").removeProp(".hidden");
        $("#msjTelefono").text("Ingresar Telefono").show();
        mostrarAdvertencia("Ingresar Telefono");
        bandera = false;
    }
    //arrayDireccionTipoText
    //    if (commonVars.personaTipoId != 2) {
    //        var indiceFiscal = buscarDireccionTipoTexto(direccionTipoFiscal);
    //        if (indiceFiscal == -1) {
    //            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese dirección fiscal.");
    //            bandera = false;
    //        }
    //    }

    return bandera;
}
function mostrarMensajeError(nombre) {
    $('#msj' + nombre).hide();
}
function llenarFormularioEditar(data) {

    //console.log(data);
    $("#txtCodigoIdentificacion").val(data[0].codigo_identificacion);

    $("#txtNombre").val(data[0].nombre);
    $("#txtApellidoPaterno").val(data[0].apellido_paterno);
    $("#txtApellidoMaterno").val(data[0].apellido_materno);
    if (data[0].bandera_recojo_reparto == 1) {
        $('#chkDevolucionCargo').prop('checked', true);
    }
    if (data[0].bandera_credito_persona == 1){
        $('#chkAprobacionCredito').prop('checked', true);
    }

    $("#txtTelefono").val(data[0].telefono);
    $("#txtCelular").val(data[0].celular);
    $("#txtEmail").val(data[0].email);
     $("#txtLicencia").val(data[0].licencia);

     if( data[0].persona_tipo_id===2)
     {
        $("#txtDireccionpers").val(data[0].direccion);
     }else{
        $("#txtDireccionfiscal").val(data[0].direccion);
     }
    //    $("#txtDireccion").val(data[0].direccion_1);
    //    $("#txtReferenciaDireccion").val(data[0].direccion_2);
    //    $("#txtDireccion3").val(data[0].direccion_3);
    //    $("#txtDireccion4").val(data[0].direccion_4);
    $("#txtRazonSocial").val(data[0].nombre);

    if (!isEmpty(data[0].persona_clase_id)) {
        select2.asignarValor('cboClasePersona', data[0].persona_clase_id.split(";"));
    }

    if (!isEmpty(data[0].sunat_tipo_documento)) {
        select2.asignarValor('cboTipoDocumento', data[0].sunat_tipo_documento);
    }
    if (!isEmpty(data[0].empresa_id)) {
        select2.asignarValor('cboEmpresa', data[0].empresa_id.split(";"));
    }
    if (!isEmpty(data[0].estado)) {
        select2.asignarValor('cboEstado', data[0].estado);
    }
    if (data[0].imagen == null || data[0].imagen == "" || data[0].imagen == "null") {
        data[0].imagen = "none.jpg";
    }

    //tablas sunat    
    select2.asignarValor('cboCodigoSunat', data[0]['sunat_tabla_detalle_id']);
    select2.asignarValor('cboCodigoSunat2', data[0]['sunat_tabla_detalle_id2']);
    select2.asignarValor('cboCodigoSunat3', data[0]['sunat_tabla_detalle_id3']);

    $("#txtNombreBCP").val(data[0].nombre_bcp);

    $("#cboCodigoSunat2").select2({ width: anchoComboSunat2 + "px" });

    var dir = URL_BASE + "/vistas/com/persona/imagen/" + data[0].imagen;
    document.getElementById("myImg").src = dir;

    modificarFormularioProveedorInternacional();
    if (data[0].sunat_tipo_documento == "6") {
        select2.asignarValor('cboTipoDocumento', "-1");
    }
}
function buscarConsultaRUC() {
    var codigoIdentificacion = trim(document.getElementById('txtCodigoIdentificacion').value);

    if (isEmpty(codigoIdentificacion)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese RUC.");
        return;
    }
    if (isNaN(codigoIdentificacion) || codigoIdentificacion.length !== 11) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese RUC, 11 dígitos numéricos .");
        return;
    }

    loaderShow();
    ax.setAccion("obtenerConsultaDocumento");
    ax.addParamTmp("codigoIdentificacion", codigoIdentificacion);
    ax.consumir();

}
function buscarConsultaDocumento() {
    var tipoDocumn = $('#cboTipoDocumento').val();
    var codigoIdentificacion = trim(document.getElementById('txtCodigoIdentificacion').value);

    if (tipoDocumn == "1") {
        tipoDocumn = "1";//dni
        bandera = true;
    } else if (tipoDocumn == "2") {
        tipoDocumn = "3";//carnet extranjeria
        bandera = true;
    } else if (tipoDocumn == "4") {
        tipoDocumn = "2";//pasaporte
    }

    if (isEmpty(codigoIdentificacion)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese Documento.");
        $('#txtNombre').val('');
        $('#txtApellidoPaterno').val('');
        $('#txtApellidoMaterno').val('');
        $('#txtCelular').val('');
        $('#txtEmail').val('');
        $('#txtDireccion').val('');
        return;
    }
    if (isEmpty(tipoDocumn)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione Tipo Documento .");
        $('#txtNombre').val('');
        $('#txtApellidoPaterno').val('');
        $('#txtApellidoMaterno').val('');
        $('#txtCelular').val('');
        $('#txtEmail').val('');
        $('#txtDireccion').val('');
        return;
    }
    loaderShow();
    ax.setAccion("obtenerConsultaDocumento");
    ax.addParamTmp("codigoIdentificacion", codigoIdentificacion);
    ax.addParamTmp("tipoDocumn", tipoDocumn);
    ax.consumir();

}

function onresponseObtenerConsultaDocumento(data) {
    //debugger;
    $('#txtRazonSocial').val('');
    $('#txtDireccion').val('');
    limpiar_mensajes();
    var tipoDocumn = $('#cboTipoDocumento').val();

    if (isEmpty(data)) {
        mostrarAdvertencia('Documento no encontrado.');
        $('#txtNombre').val('');
        $('#txtApellidoPaterno').val('');
        $('#txtApellidoMaterno').val('');
        $('#txtCelular').val('');
        $('#txtEmail').val('');
        $('#txtDireccion').val('');
        $('#txtDireccion').val('');
    } else if (isEmpty(tipoDocumn)) {
        if (!isEmpty(data.empresaInfo) && data.state == "OK") {
            $('#txtRazonSocial').val(data.empresaInfo.empresaRazonSocial);

            if (data.empresaInfo.empresaDireccion != "-" && !isEmpty(data.empresaInfo.empresaDireccion)) {
                $('#txtDireccion').val(data.empresaInfo.empresaDireccion);
                habilitarDivDireccionTipoCombo();
                select2.asignarValor("cboDireccionTipo", -1);
            }


            mostrarOk(data.message);
        } else {
            mostrarAdvertencia(data.message);
            $('#txtRazonSocial').val('');
            $('#txtDireccion').val('');
        }
    } else {
        if (!isEmpty(data.clienteInfo) && data.state == "OK") {
            $('#txtNombre').val(data.clienteInfo.clienteNombre);
            $('#txtApellidoPaterno').val(data.clienteInfo.clienteApellidoPaterno);
            $('#txtApellidoMaterno').val(data.clienteInfo.clienteApellidoMaterno);
            $('#txtTelefono').val(data.clienteInfo.clienteTelefono);
            $('#txtEmail').val(data.clienteInfo.clienteEmail);
            if (data.clienteInfo.clienteDireccion == "-") {
                $('#txtDireccion').val('');
            }
            habilitarDivDireccionTipoCombo();
            // select2.asignarValor("cboDireccionTipo", -1);
            mostrarOk(data.message);
        } else {
            mostrarAdvertencia(data.message);
            $('#txtNombre').val('');
            $('#txtApellidoPaterno').val('');
            $('#txtApellidoMaterno').val('');
            $('#txtCelular').val('');
            $('#txtEmail').val('');
            $('#txtDireccion').val('');
        }
    }
}

function validarToken(personaId) {
    if (token == 1) {
        window.opener.setearPersonaRegistro(personaId, tokenRetorno);
        setTimeout("self.close();", 700);
    }
}

function habilitarDivContactoTipoTexto() {
    $("#contenedorContactoTipoDivCombo").hide();
    $("#contenedorContactoTipoDivTexto").show();
}

function habilitarDivContactoTipoCombo() {
    $("#contenedorContactoTipoDivTexto").hide();
    $("#contenedorContactoTipoDivCombo").show();
}

function nuevoContactoPersona() {
    var rutaAbsoluta = URL_BASE + 'index.php?token=1&personaTipo=2';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

var personaIdRegistro = null;
function setearPersonaRegistro(personaId) {
    //    console.log(personaId);
    personaIdRegistro = personaId;
    obtenerPersonasNaturales();
}

function obtenerPersonasNaturales() {
    ax.setAccion("obtenerPersonasNaturales");
    ax.consumir();
}

function onResponseObtenerPersonasNaturales(data) {
    //    console.log(data);
    if (!isEmpty(data)) {
        select2.cargar("cboContacto", data, "id", ["persona_nombre", "codigo_identificacion"]);
    }

    if (!isEmpty(personaIdRegistro)) {
        select2.asignarValor("cboContacto", personaIdRegistro);
    }
}

// PESTAÑA CONTACTO
var listaContactoDetalle = [];

var arrayContacto = [];
var arrayContactoText = [];
var arrayContactoTipo = [];
var arrayContactoTipoText = [];
var arrayContactoTelefonoText = [];
var arrayContactoEmailText = [];
var arrayPersonaContactoId = [];

function findContactoById(index) {
    if (!isEmpty(dataPersonaGlobal)) {
        return dataPersonaGlobal.find(item => item.id == index);
    }
    //    for(var i=0;i<dataPersonaGlobal.length;i++)
    //    {
    //        if(dataPersonaGlobal[i].id===index)
    //        {
    //            return dataPersonaGlobal[i];
    //        }
    //        //console.log("id"+dataPersonaGlobal[i].id + "Nombre: " + dataPersonaGlobal[i].persona_nombre + "Celular: " + dataPersonaGlobal[i].celular);
    //    }
    //console.log("SADASDASD");
    //alert("asdasdasd");
}

function agregarContactoDetalle() {
    //ids
    //debugger;
    var contacto = select2.obtenerValor('cboContacto');
    var contactoTipo = select2.obtenerValor('cboContactoTipo');

    //texto
    var contactoTipoText = document.getElementById('txtContactoTipo').value;
    contactoTipoText = contactoTipoText.trim();
    if (isEmpty(contactoTipoText)) {
        contactoTipoText = select2.obtenerText('cboContactoTipo');
    }
    var contactoText = select2.obtenerText('cboContacto');

    var contactoTelefonoText = !isEmpty(findContactoById(contacto).celular) ? findContactoById(contacto).celular : "Sin celular";
    var contactoEmailText = !isEmpty(findContactoById(contacto).email) ? findContactoById(contacto).email : "Sin email.";

    var idContactoDetalle = $('#idContactoDetalle').val();

    // ids tablas
    var personaContactoId = null;
    //alert(idContactoDetalle);

    if (validarFormularioContactoDetalle(contactoTipoText, contacto)) {
        if (validarContactoDetalleRepetido(contactoTipoText, contacto)) {

            if (idContactoDetalle != '') {

                arrayContactoTipo[idContactoDetalle] = contactoTipo;
                arrayContactoTipoText[idContactoDetalle] = contactoTipoText;
                arrayContacto[idContactoDetalle] = contacto;
                arrayContactoText[idContactoDetalle] = contactoText;
                arrayContactoTelefonoText[idContactoDetalle] = contactoTelefonoText;
                arrayContactoEmailText[idContactoDetalle] = contactoEmailText;

                // ids de tablas relacionadas
                personaContactoId = arrayPersonaContactoId[idContactoDetalle];

                listaContactoDetalle[idContactoDetalle] = [contactoTipo, contactoTipoText, contacto, contactoText, personaContactoId, contactoTelefonoText, contactoEmailText];
            } else {
                //alert('diferente');

                arrayContactoTipo.push(contactoTipo);
                arrayContactoTipoText.push(contactoTipoText);
                arrayContacto.push(contacto);
                arrayContactoText.push(contactoText);
                arrayContactoTelefonoText.push(contactoTelefonoText);
                arrayContactoEmailText.push(contactoEmailText);

                listaContactoDetalle.push([contactoTipo, contactoTipoText, contacto, contactoText, personaContactoId, contactoTelefonoText, contactoEmailText]);
            }

            //            console.log(listaContactoDetalle);
            //            console.log(listaPersonaContactoEliminado);
            onListarContactoDetalle(listaContactoDetalle);
            limpiarCamposContactoDetalle();
            limpiarMensajesContactoDetalle();

        }
    }
}

function validarFormularioContactoDetalle(contactoTipo, contacto) {
    var bandera = true;
    limpiarMensajesContactoDetalle();

    if (contactoTipo === '' || contactoTipo === null) {
        $("#msjContactoTipo").removeProp(".hidden");
        $("#msjContactoTipo").text("Tipo de contacto es obligatorio").show();
        bandera = false;
    }

    if (contacto === '' || contacto === null) {
        $("#msjContacto").removeProp(".hidden");
        $("#msjContacto").text("Contacto es obligatorio").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesContactoDetalle() {
    $("#msjContactoTipo").hide();
    $("#msjContacto").hide();
    $("#msjContactoDetalle").hide();

}

function validarContactoDetalleRepetido(contactoTipo, contacto) {
    var valido = true;

    var idContactoDetalle = $('#idContactoDetalle').val();

    //alert(idContactoDetalle + ' : '+ indiceContactoTipo);

    if (idContactoDetalle != '') {
        //alert('igual');
        var indice = buscarContactoDetalle(contactoTipo, contacto);
        //        console.log(indice,idContactoDetalle);
        if (indice != idContactoDetalle && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El contacto ya ha sido agregado");
            valido = false;
        } else {
            valido = true;
        }
    } else {
        //alert('diferente');
        var indice = buscarContactoDetalle(contactoTipo, contacto);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El contacto ya ha sido agregado");
            valido = false;
        }
    }

    return valido;
}

function onListarContactoDetalle(data) {
    $('#dataTableContacto tbody tr').remove();
    var cuerpo = "";
    var ind = 0;
    //console.log(data);
    if (!isEmpty(data)) {
        data.forEach(function (item) {

            //listaContactoDetalle.push([ contactoTipo, contactoTipoText, contacto, contactoText,personaContactoId]);


            var eliminar = "<a href='#' onclick = 'eliminarContactoDetalle(\""
                + item['1'] + "\", \"" + item['2'] + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a href='#' onclick = 'editarContactoDetalle("
                + item['0'] + ", \"" + item['1'] + "\", \"" + item['2'] + "\", \"" + ind + "\")' >"
                + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            cuerpo += "<tr>"
                + "<td style='text-align:left;'>" + item['3'] + "</td>"
                + "<td style='text-align:left;'>" + item['1'] + "</td>"
                + "<td style='text-align:left;'>" + item['5'] + "</td>"
                + "<td style='text-align:left;'>" + item['6'] + "</td>"
                + "<td style='text-align:center;'>" + editar + eliminar
                + "</td>"
                + "</tr>";

            ind++;
        });

        $('#dataTableContacto tbody').append(cuerpo);
    }
}

function limpiarCamposContactoDetalle() {
    asignarValorSelect2('cboContactoTipo', null);
    asignarValorSelect2('cboContacto', null);
    $('#txtContactoTipo').val('');
    $('#idContactoDetalle').val('');
}

function buscarContactoDetalle(contactoTipoText, contacto) {
    var tam = arrayContactoTipoText.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayContactoTipoText[i] === contactoTipoText && arrayContacto[i] === contacto) {
            ind = i;
            break;
        }
    }
    return ind;
}

function editarContactoDetalle(contactoTipo, contactoTipoText, contacto, ind) {
    var indice = ind;

    asignarValorSelect2('cboContacto', arrayContacto[indice]);

    if (isEmpty(contactoTipo)) {
        habilitarDivContactoTipoTexto();
        $('#txtContactoTipo').val(contactoTipoText);
    } else {
        habilitarDivContactoTipoCombo();
        asignarValorSelect2('cboContactoTipo', arrayContactoTipo[indice]);
    }

    $('#idContactoDetalle').val(ind);

}

var listaPersonaContactoEliminado = [];

function eliminarContactoDetalle(contactoTipoText, contacto) {
    var indice = buscarContactoDetalle(contactoTipoText, contacto);
    if (indice > -1) {
        arrayContactoTipo.splice(indice, 1);
        arrayContactoTipoText.splice(indice, 1);
        arrayContacto.splice(indice, 1);
        arrayContactoText.splice(indice, 1);
    }

    if (!isEmpty(arrayPersonaContactoId[indice])) {
        var personaContactoId = arrayPersonaContactoId[indice];
        arrayPersonaContactoId.splice(indice, 1);
        listaPersonaContactoEliminado.push([personaContactoId]);
    }

    listaContactoDetalle = [];
    var tam = arrayContactoTipo.length;
    for (var i = 0; i < tam; i++) {
        listaContactoDetalle.push([arrayContactoTipo[i], arrayContactoTipoText[i], arrayContacto[i], arrayContactoText[i], arrayPersonaContactoId[i]]);
    }

    //    console.log(listaContactoDetalle);
    onListarContactoDetalle(listaContactoDetalle);
}

function habilitarDivDireccionTipoTexto() {
    asignarValorSelect2('cboDireccionTipo', null);
    $("#contenedorDireccionTipoDivCombo").hide();
    $("#contenedorDireccionTipoDivTexto").show();
}

function habilitarDivDireccionTipoCombo() {
    $('#txtDireccionTipo').val('');
    $("#contenedorDireccionTipoDivTexto").hide();
    $("#contenedorDireccionTipoDivCombo").show();
}

// PESTAÑA DIRECCION
var listaDireccionDetalle = [];

var arrayUbigeo = [];
var arrayUbigeoText = [];
var arrayDireccionTipo = [];
var arrayDireccionTipoText = [];
var arrayDireccionText = [];
var arrayPersonaDireccionId = [];
var arrayZona = [];
var arrayZonaText = [];
var arrayReferencia = [];
var arrayLatitud = [];
var arrayLongitud = [];

function agregarDireccionDetalle() {
    //ids
    var ubigeo = select2.obtenerValor('cboUbigeo');
    var direccionTipo = select2.obtenerValor('cboDireccionTipo');
    var zona = select2.obtenerValor('cboZona');
    //texto
    var ubigeoText = select2.obtenerText('cboUbigeo');
    var zonaText = select2.obtenerText('cboZona');
    var direccionTipoText = document.getElementById('txtDireccionTipo').value;
    direccionTipoText = direccionTipoText.trim();
    if (isEmpty(direccionTipoText)) {
        direccionTipoText = select2.obtenerText('cboDireccionTipo');
    }
    var direccionText = document.getElementById('txtDireccion').value;
    var referenciaText = document.getElementById('txtReferencia').value;
    var idDireccionDetalle = $('#idDireccionDetalle').val();

    var latitud = $('#txtLatitud').val();
    var logitud = $('#txtLongitud').val();

    // ids tablas
    var personaDireccionId = null;
    //alert(idDireccionDetalle);

    if (validarFormularioDireccionDetalle(direccionTipoText, ubigeo, direccionText, zona, latitud, logitud)) {
        if (validarDireccionDetalleRepetido(direccionTipoText, ubigeo, direccionText, zona)) {

            if (idDireccionDetalle != '') {

                arrayDireccionTipo[idDireccionDetalle] = direccionTipo;
                arrayDireccionTipoText[idDireccionDetalle] = direccionTipoText;
                arrayUbigeo[idDireccionDetalle] = ubigeo;
                arrayUbigeoText[idDireccionDetalle] = ubigeoText;
                arrayDireccionText[idDireccionDetalle] = direccionText;
                arrayZona[idDireccionDetalle] = zona;
                arrayZonaText[idDireccionDetalle] = zonaText;
                arrayReferencia[idDireccionDetalle] = referenciaText;

                arrayLatitud[idDireccionDetalle] = latitud;
                arrayLongitud[idDireccionDetalle] = logitud;

                // ids de tablas relacionadas
                personaDireccionId = arrayPersonaDireccionId[idDireccionDetalle];

                listaDireccionDetalle[idDireccionDetalle] = [direccionTipo, direccionTipoText, ubigeo, ubigeoText, direccionText, personaDireccionId, zona, zonaText, referenciaText, latitud, logitud];
            } else {
                //alert('diferente');

                arrayDireccionTipo.push(direccionTipo);
                arrayDireccionTipoText.push(direccionTipoText);
                arrayUbigeo.push(ubigeo);
                arrayUbigeoText.push(ubigeoText);
                arrayDireccionText.push(direccionText);
                arrayZona.push(zona);
                arrayZonaText.push(zonaText);
                arrayReferencia.push(referenciaText);
                arrayLatitud.push(latitud);
                arrayLongitud.push(logitud);

                listaDireccionDetalle.push([direccionTipo, direccionTipoText, ubigeo, ubigeoText, direccionText, personaDireccionId, zona, zonaText, referenciaText, latitud, logitud]);
            }

            //            console.log(listaDireccionDetalle);
            //            console.log(listaPersonaDireccionEliminado);
            onListarDireccionDetalle(listaDireccionDetalle);
            limpiarCamposDireccionDetalle();
            limpiarMensajesDireccionDetalle();

        }
    }
}

function validarFormularioDireccionDetalle(direccionTipoText, ubigeo, direccionText, zona, latitud, logitud) {
    //    debugger;
    var bandera = true;
    limpiarMensajesDireccionDetalle();

    if (direccionTipoText === '' || direccionTipoText === null) {
        $("#msjDireccionTipo").removeProp(".hidden");
        $("#msjDireccionTipo").text("Tipo de direccion es obligatorio").show();
        mostrarAdvertencia("Tipo de direccion es obligatorio");
        bandera = false;
    }

    //validar proveedor internacional
    var dataClase = $('#cboClasePersona').val();
    var proveedorInter = false;
    if (!isEmpty(dataClase)) {
        var index = dataClase.indexOf(claseProveedorInternacionalId);
        if (index != -1) {
            proveedorInter = true;
        }
    }

    if (!proveedorInter && direccionTipoText.toLowerCase().indexOf('fiscal') > -1) {
        if (ubigeo === '' || ubigeo === null) {
            $("#msjUbigeo").removeProp(".hidden");
            $("#msjUbigeo").text("Ubigeo es obligatorio").show();
            mostrarAdvertencia("Ubigeo es obligatorio");
            bandera = false;
        }
    }

    if (direccionTipoText.toLowerCase().indexOf('fiscal') == -1) {
        if (isEmpty(zona)) {
            $("#msjZona").removeProp(".hidden");
            $("#msjZona").text("Zona es obligatorio").show();
            mostrarAdvertencia("Zona es obligatorio");
            bandera = false;
        }
    }


    if (!proveedorInter && direccionTipoText == 'Despacho') {
        if (latitud === '' || latitud === null) {
            $("#msjBusquedadMapa").removeProp(".hidden");
            $("#msjBusquedadMapa").text("Por favor busque la dirección en el mapa").show();
            mostrarAdvertencia("Por favor busque la dirección en el mapa");
            bandera = false;
        }

        if (logitud === '' || logitud === null) {
            $("#msjBusquedadMapa").removeProp(".hidden");
            $("#msjBusquedadMapa").text("Por favor busque la dirección en el mapa").show();
            mostrarAdvertencia("Por favor busque la dirección en el mapa");
            bandera = false;
        }
    }
    if (direccionText === '' || direccionText === null) {
        $("#msjDireccion").removeProp(".hidden");
        $("#msjDireccion").text("Direccion es obligatorio").show();
        mostrarAdvertencia("Direccion es obligatorio");
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesDireccionDetalle() {
    $("#msjDireccionTipo").hide();
    $("#msjUbigeo").hide();
    $("#msjZona").hide();
    $("#msjBusquedadMapa").hide();

    $("#msjDireccion").hide();
    $("#msjDireccionDetalle").hide();

}

function validarDireccionDetalleRepetido(direccionTipoText, ubigeo, direccionText, zona) {
    var valido = true;

    var idDireccionDetalle = $('#idDireccionDetalle').val();

    //alert(idDireccionDetalle + ' : '+ indiceDireccionTipo);

    if (idDireccionDetalle != '') {
        //alert('igual');
        var indice = buscarDireccionDetalle(direccionTipoText, ubigeo, direccionText, zona);
        //        console.log(indice,idDireccionDetalle);
        if (indice != idDireccionDetalle && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La dirección ya ha sido agregado");
            valido = false;
        } else {
            valido = true;
        }

        //validando que solo haya una direccion fiscal.
        if (direccionTipoFiscal == direccionTipoText) {
            var indiceFiscal = buscarDireccionTipoTexto(direccionTipoText);
            if (indiceFiscal != idDireccionDetalle && indiceFiscal != -1) {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Solo se puede registrar una dirección fiscal.");
                valido = false;
            }
        }
    } else {
        //alert('diferente');
        var indice = buscarDireccionDetalle(direccionTipoText, ubigeo, direccionText, zona);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La dirección ya ha sido agregado");
            valido = false;
        }

        //validando que solo haya una direccion fiscal.
        if (direccionTipoFiscal == direccionTipoText) {
            var indiceFiscal = buscarDireccionTipoTexto(direccionTipoText);
            if (indiceFiscal > -1) {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Solo se puede registrar una dirección fiscal.");
                valido = false;
            }
        }
    }

    return valido;
}

function buscarDireccionTipoTexto(direccionTipoText) {
    var tam = arrayDireccionTipoText.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayDireccionTipoText[i] === direccionTipoText) {
            ind = i;
            break;
        }
    }
    return ind;
}

function onListarDireccionDetalle(data) {
    $('#dataTableDireccion tbody tr').remove();
    var cuerpo = "";
    var ubigeoDescripcion;
    var zonaDescripcion;
    var referencia;
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            ubigeoDescripcion = item['3'];

            if (isEmpty(ubigeoDescripcion)) {
                ubigeoDescripcion = '';
            }
            zonaDescripcion = item['7'];

            if (isEmpty(zonaDescripcion)) {
                zonaDescripcion = '';
            }

            referencia = item['8'];
            if (isEmpty(referencia)) {
                referencia = '';
            }

            //listaDireccionDetalle.push([direccionTipo,direccionTipoText,ubigeo,ubigeoText,direccionText,personaDireccionId]);

            var eliminar = "<a href='#' onclick = 'eliminarDireccionDetalleXIndice(" + index + ")' >"
                + "<i id='e" + index + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a href='#' onclick = 'editarDireccionDetalle("
                + item['0'] + ", \"" + item['1'] + "\", \"" + index + "\")' >"
                + "<i id='e" + index + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            cuerpo += "<tr>"
                + "<td style='text-align:left;'>" + item['1'] + "</td>"
                + "<td style='text-align:left;'>" + ubigeoDescripcion + "</td>"
                + "<td style='text-align:left;'>" + zonaDescripcion + "</td>"
                + "<td style='text-align:left;'>" + item['4'] + "</td>"
                + "<td style='text-align:left;'>" + referencia + "</td>"
                + "<td style='text-align:center;'>" + editar + eliminar
                + "</td>"
                + "</tr>";
        });

        $('#dataTableDireccion tbody').append(cuerpo);
    }
}

function limpiarCamposDireccionDetalle() {
    asignarValorSelect2('cboDireccionTipo', null);
    asignarValorSelect2('cboUbigeo', null);
    asignarValorSelect2('cboZona', null);
    $('#txtDireccionTipo').val('');
    $('#txtDireccion').val('');
    $('#txtReferencia').val('');
    $('#idDireccionDetalle').val('');
}

function buscarDireccionDetalle(direccionTipoText, ubigeo, direccionText, zona) {
    var tam = arrayDireccionTipoText.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayDireccionTipoText[i] === direccionTipoText && arrayUbigeo[i] === ubigeo && arrayDireccionText[i] === direccionText && arrayZona[i] === zona) {
            ind = i;
            break;
        }
    }
    return ind;
}

function editarDireccionDetalle(direccionTipo, direccionTipoText, ind) {
    var indice = ind;
    asignarValorSelect2('cboZona', null);
    asignarValorSelect2('cboUbigeo', arrayUbigeo[indice]);
    cargarZona();
    asignarValorSelect2('cboZona', arrayZona[indice]);
    if (isEmpty(direccionTipo)) {
        habilitarDivDireccionTipoTexto();
        $('#txtDireccionTipo').val(direccionTipoText);
    } else {
        habilitarDivDireccionTipoCombo();
        asignarValorSelect2('cboDireccionTipo', arrayDireccionTipo[indice]);
    }

    $('#txtDireccion').val(arrayDireccionText[indice]);
    $('#txtReferencia').val(arrayReferencia[indice]);
    $('#txtLatitud').val(arrayLatitud[indice]);
    $('#txtLongitud').val(arrayLongitud[indice]);
    $('#idDireccionDetalle').val(ind);
    initialize();
}

var listaPersonaDireccionEliminado = [];

function eliminarDireccionDetalleXIndice(indice) {

    arrayDireccionTipo.splice(indice, 1);
    arrayDireccionTipoText.splice(indice, 1);
    arrayUbigeo.splice(indice, 1);
    arrayUbigeoText.splice(indice, 1);
    arrayDireccionText.splice(indice, 1);
    arrayZona.splice(indice, 1);
    arrayZonaText.splice(indice, 1);
    arrayReferencia.splice(indice, 1);

    arrayLatitud.splice(indice, 1);
    arrayLongitud.splice(indice, 1);

    if (!isEmpty(arrayPersonaDireccionId[indice])) {
        var personaDireccionId = arrayPersonaDireccionId[indice];
        arrayPersonaDireccionId.splice(indice, 1);
        listaPersonaDireccionEliminado.push([personaDireccionId]);
    }

    listaDireccionDetalle = [];
    var tam = arrayDireccionTipo.length;
    for (var i = 0; i < tam; i++) {
        listaDireccionDetalle.push([arrayDireccionTipo[i], arrayDireccionTipoText[i], arrayUbigeo[i], arrayUbigeoText[i], arrayDireccionText[i], arrayPersonaDireccionId[i]
            , arrayZona[i], arrayZonaText[i], arrayReferencia[i], arrayLatitud[i], arrayLongitud[i]
        ]);
    }

    //    console.log(listaDireccionDetalle);
    onListarDireccionDetalle(listaDireccionDetalle);
}

function eliminarDireccionDetalle(direccionTipoText, ubigeo, direccionText) {
    var indice = buscarDireccionDetalle(direccionTipoText, ubigeo, direccionText);
    if (indice > -1) {
        arrayDireccionTipo.splice(indice, 1);
        arrayDireccionTipoText.splice(indice, 1);
        arrayUbigeo.splice(indice, 1);
        arrayUbigeoText.splice(indice, 1);
        arrayDireccionText.splice(indice, 1);
    }

    if (!isEmpty(arrayPersonaDireccionId[indice])) {
        var personaDireccionId = arrayPersonaDireccionId[indice];
        arrayPersonaDireccionId.splice(indice, 1);
        listaPersonaDireccionEliminado.push([personaDireccionId]);
    }

    listaDireccionDetalle = [];
    var tam = arrayDireccionTipo.length;
    for (var i = 0; i < tam; i++) {
        listaDireccionDetalle.push([arrayDireccionTipo[i], arrayDireccionTipoText[i], arrayUbigeo[i], arrayUbigeoText[i], arrayDireccionText[i], arrayPersonaDireccionId[i]]);
    }

    //    console.log(listaDireccionDetalle);
    onListarDireccionDetalle(listaDireccionDetalle);
}

function asignarValorSelect2(id, valor) {
    $("#" + id).select2().select2("val", valor);
    $("#" + id).select2({ width: '100%' });
}

function setearComboConvenioSunat() {
    var codigoSunatId = select2.obtenerValor('cboCodigoSunat');

    loaderShow();
    ax.setAccion("obtenerDataConvenioSunat");
    ax.addParamTmp("codigoSunatId", codigoSunatId);
    ax.consumir();
}

function onResponseObtenerDataConvenioSunat(data) {
    if (!isEmpty(data)) {
        select2.asignarValor('cboCodigoSunat3', data[0]['sunat_tabla_detalle_id2']);
    } else {
        select2.asignarValor('cboCodigoSunat3', convenioSunatId0);
    }
}

var claseProveedorInternacionalId = "17";
function modificarFormularioProveedorInternacional() {

    $("#msjCodigoIdentificacion").hide();
    $("#msjRazonSocial").hide();
    $("#msjClasePersona").hide();

    if (commonVars.personaTipoId == 4) {
        $("#labelUbigeo").html("Ubigeo");
        var dataClase = $('#cboClasePersona').val();

        if (!isEmpty(dataClase)) {
            var index = dataClase.indexOf(claseProveedorInternacionalId);
            if (index != -1) {
                $("#lblCodigoIdentificacion").html("Código identificación *");
                $("#labelUbigeo").html("Ubigeo");
                $("#contenedorBuscarRUC").hide();
            } else {
                $("#lblCodigoIdentificacion").html("RUC *");
                $("#contenedorBuscarRUC").show();
            }
        } else {
            $("#lblCodigoIdentificacion").html("RUC *");
            $("#contenedorBuscarRUC").show();
        }
    }
}
function cargarMaps(tipo) {
    let latitud = $("#txtLatitud").val();
    let logintud = $("#txtLongitud").val();
    var rutaAbsoluta = URL_BASE + 'vistas/com/persona/googleMap.php?latitud=' + latitud + '&logintud=' + logintud;
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
    //    var win = window.open(rutaAbsoluta, '_blank');
    //    win.focus();
}

function setRespuestaMaps(latitud, logintud, direccion) {
    $("#txtDireccion").val(direccion);
    $("#txtLatitud").val(latitud);
    $("#txtLongitud").val(logintud);
}

class Localizacion {
    constructor(callback) {
        if (!isEmpty(navigator.geolocation) && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                this.latitude = position.coords.latitude;
                this.longitude = position.coords.longitude;
                callback();
            });
        } else {
            // console.log("Tu navegador no soporta geolocalizacion.");
            this.latitude = -8.11167;
            this.longitude = -79.0286;
            callback();
        }
    }
}


function initialize() {
    //    debugger;
    var ubicacion = new Localizacion(() => {

        var mapOptions, map, marker, searchBox, city,
            infoWindow = '',
            addressEl = document.getElementById('txtDireccion'),
            latEl = document.getElementById('txtLatitud'),
            longEl = document.getElementById('txtLongitud'),
            element = document.getElementById('gmaps-markers');
        city = document.getElementById('txtCiudad');
        var latitud = latEl.value;
        var logintud = longEl.value;
        //        var banderaEjecutarBusqueda = false;
        //        if (!isEmpty(latitud)) {
        //            banderaEjecutarBusqueda = true;
        //        }

        latitud = (!isEmpty(latitud) ? latitud : (!isEmpty(ubicacion) ? ubicacion.latitude : -8.106042799999999));
        logintud = (!isEmpty(logintud) ? logintud : (!isEmpty(ubicacion) ? ubicacion.longitude : -79.0329727)); // -79.0286);
        mapOptions = {
            // How far the maps zooms in.
            zoom: 14,
            // Current Lat and Long position of the pin/
            center: new google.maps.LatLng(latitud, logintud),
            // center : {
            // 	lat: -34.397,
            // 	lng: 150.644
            // },
            disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
            scrollWheel: true, // If set to false disables the scrolling on the map.
            draggable: true, // If set to false , you cannot move the map around.
            // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
            // maxZoom: 11, // Wont allow you to zoom more than this
            // minZoom: 9  // Wont allow you to go more up.

        };

        /**
         * Creates the map using google function google.maps.Map() by passing the id of canvas and
         * mapOptions object that we just created above as its parameters.
         *
         */
        // Create an object map with the constructor function Map()
        map = new google.maps.Map(element, mapOptions); // Till this like of code it loads up the map.

        /**
         * Creates the marker on the map
         *
         */
        marker = new google.maps.Marker({
            position: mapOptions.center,
            map: map,
            // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
            draggable: true
        });

        /**
         * Creates a search box
         */
        searchBox = new google.maps.places.SearchBox(addressEl);

        /**
         * When the place is changed on search box, it takes the marker to the searched location.
         */
        google.maps.event.addListener(searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces(),
                bounds = new google.maps.LatLngBounds(),
                i, place, lat, long, resultArray,
                addresss = places[0].formatted_address;

            for (i = 0; place = places[i]; i++) {
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);  // Set marker position new.
            }

            map.fitBounds(bounds);  // Fit to the bound
            map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
            // console.log( map.getZoom() );

            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();
            latEl.value = lat;
            longEl.value = long;

            resultArray = places[0].address_components;

            // Get the city and set the city input value to the one selected
            for (var i = 0; i < resultArray.length; i++) {
                if (resultArray[i].types[0] && 'administrative_area_level_2' === resultArray[i].types[0]) {
                    citi = resultArray[i].long_name;
                    city.value = citi;
                }
            }

            // Closes the previous info window if it already exists
            if (infoWindow) {
                infoWindow.close();
            }
            /**
             * Creates the info Window at the top of the marker
             */
            infoWindow = new google.maps.InfoWindow({
                content: addresss
            });

            infoWindow.open(map, marker);
        });


        /**
         * Finds the new position of the marker when the marker is dragged.
         */
        google.maps.event.addListener(marker, "dragend", function (event) {
            var lat, long, address, resultArray, citi;

            //            console.log('i am dragged');
            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ latLng: marker.getPosition() }, function (result, status) {
                if ('OK' === status) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                    address = result[0].formatted_address;
                    resultArray = result[0].address_components;

                    // Get the city and set the city input value to the one selected
                    for (var i = 0; i < resultArray.length; i++) {
                        if (resultArray[i].types[0] && 'administrative_area_level_2' === resultArray[i].types[0]) {
                            citi = resultArray[i].long_name;
                            //                            console.log(citi);
                            city.value = citi;
                        }
                    }
                    addressEl.value = address;
                    latEl.value = lat;
                    longEl.value = long;

                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }

                // Closes the previous info window if it already exists
                if (infoWindow) {
                    infoWindow.close();
                }

                /**
                 * Creates the info Window at the top of the marker
                 */
                infoWindow = new google.maps.InfoWindow({
                    content: address
                });

                infoWindow.open(map, marker);
            });
        });
        //        if (banderaEjecutarBusqueda) {
        google.maps.event.trigger(marker, 'dragend', function (event) {
            var lat, long, address, resultArray, citi;

            //                console.log('i am dragged');
            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ latLng: marker.getPosition() }, function (result, status) {
                if ('OK' === status) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                    address = result[0].formatted_address;
                    resultArray = result[0].address_components;

                    // Get the city and set the city input value to the one selected
                    for (var i = 0; i < resultArray.length; i++) {
                        if (resultArray[i].types[0] && 'administrative_area_level_2' === resultArray[i].types[0]) {
                            citi = resultArray[i].long_name;
                            //                            console.log(citi);
                            city.value = citi;
                        }
                    }
                    addressEl.value = address;
                    latEl.value = lat;
                    longEl.value = long;

                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }

                // Closes the previous info window if it already exists
                if (infoWindow) {
                    infoWindow.close();
                }

                /**
                 * Creates the info Window at the top of the marker
                 */
                infoWindow = new google.maps.InfoWindow({
                    content: address
                });

                infoWindow.open(map, marker);
            });

        });

        //        }
    });
}
