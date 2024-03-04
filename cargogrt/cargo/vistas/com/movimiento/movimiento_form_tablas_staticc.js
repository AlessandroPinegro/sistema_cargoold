var boton = {
    enviarClase: $('#env i').attr('class'),
    accion: ''
};
var validacion = {
    organizadorExistencia: true
};
var camposDinamicos = [];
var importes = {
    totalId: "txtTotal",
    subTotalId: "txtSubTotal",
    igvId: "txtIgv",
    calculoId: null,
    otrosCargoId: "txtOtroCargo",
    devolucionCargoId: "txtDevolucionCargo",
    costoRepartoId: "txtCostoReparto",
    detraccionId: "txtDetraccion",
    ajustePrecioId: "txtAjustePrecio",
    costoRecojoDomicilioId: "txtRecojoDomicilio"
};
var cabecera = {
    chkDocumentoRelacion: 1
};
var bandera = {
    primeraCargaDocumentosRelacion: true,
    mostrarDivDocumentoRelacion: false,
    validacionAnticipos: 0
};
var request = {
    documentoRelacion: [] // Ids de documentos a relacionar
};
var variable = {
    documentoIdCopia: null,
    movimientoIdCopia: null
};
var dataTemporal = {
    anticipos: null
}

var tipoInterfaz = document.getElementById("tipoInterfaz").value;
var distribucionObligatoria = 0;
//::: CAMBIOS JESUS
var  numero_ruc=0;
var cbocliente  = '';
var arraycbocliente =[];
//::: CAMBIOS JESUS
					 
var CreditoRemitente=0;
var CreditoDestinatario=0;
var ArrayListaClientesSelect=[];		   
						  								

$(document).ready(function () {
    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    datePiker.iniciarPorClase('fecha');
    var documentoId = document.getElementById("documentoId").value;
    //initSwitch();
    $("#chkOtroCargo").prop("checked", false);
    $("#chkDevolucionCargo").prop("checked", false);
    $("#chkAprobacionCredito").prop("checked", false);
    //    loaderShow();
    ax.setSuccess("onResponseMovimientoFormTablas");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoId", documentoId);
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.consumir();
    select2.iniciar();
    datePiker.iniciar('txtFechaEmision');
    $('#txtFechaEmision').datepicker('update', new Date());
    ///::::::::::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::::::::::::
    var serie = document.getElementById("serie");
    var correlativo = document.getElementById("correlativo");
    var numDoc = document.getElementById("numDoc");
    var tipoDoc = document.getElementById("tipoDoc");
    serie.addEventListener("input", function () {
      if(tipoDoc.value == "09"){
        var valor = serie.value.toUpperCase();
        var valorLimpio = valor.replace(/[^T0-9]/g, "");
        if (valorLimpio.length > 0) {
          if (isNaN(valorLimpio.charAt(0)) && valorLimpio.charAt(0) !== 'T') {
            valorLimpio = 'T' + valorLimpio.slice(1);
          }
        }
        if (valorLimpio.length > 1 && valorLimpio.charAt(1) === 'T') {
          valorLimpio = valorLimpio.slice(0, 1) + valorLimpio.slice(2);
        }
        serie.value = valorLimpio.slice(0, 4);
  
      }if(tipoDoc.value == "31"){
        var valor = serie.value.toUpperCase();
        var valorLimpio = valor.replace(/[^V0-9]/g, "");
        if (valorLimpio.length > 0) {
          if (isNaN(valorLimpio.charAt(0)) && valorLimpio.charAt(0) !== 'V') {
            valorLimpio = 'V' + valorLimpio.slice(1);
          }
        }
        if (valorLimpio.length > 1 && valorLimpio.charAt(1) === 'V') {
          valorLimpio = valorLimpio.slice(0, 1) + valorLimpio.slice(2);
        }
        serie.value = valorLimpio.slice(0, 4);
      }if(tipoDoc.value == "03"){
        var valor = serie.value.toUpperCase();
        var valorLimpio = valor.replace(/[^B0-9]/g, "");
        if (valorLimpio.length > 0) {
          if (isNaN(valorLimpio.charAt(0)) && valorLimpio.charAt(0) !== 'B') {
            valorLimpio = 'B' + valorLimpio.slice(1);
          }
        }
        if (valorLimpio.length > 1 && valorLimpio.charAt(1) === 'B') {
          valorLimpio = valorLimpio.slice(0, 1) + valorLimpio.slice(2);
        }
        serie.value = valorLimpio.slice(0, 4);
      }if(tipoDoc.value == "01"){
        var valor = serie.value.toUpperCase();
        var valorLimpio = valor.replace(/[^F0-9]/g, "");
        if (valorLimpio.length > 0) {
          if (isNaN(valorLimpio.charAt(0)) && valorLimpio.charAt(0) !== 'F') {
            valorLimpio = 'F' + valorLimpio.slice(1);
          }
        }
        if (valorLimpio.length > 1 && valorLimpio.charAt(1) === 'F') {
          valorLimpio = valorLimpio.slice(0, 1) + valorLimpio.slice(2);
        }
        serie.value = valorLimpio.slice(0, 4);
      }
      else{ 
        var valor = serie.value.toUpperCase(); 
        var valorLimpio = valor.replace(/[^A-Z0-9]/g, "");
        serie.value = valorLimpio.slice(0, 4); 
      }
    });
    correlativo.addEventListener("input", function () {
      var valor = correlativo.value;
      var valorLimpio = valor.replace(/[^\d]/g, "");
      correlativo.value = valorLimpio.slice(0, 8);
    });
  
    numDoc.addEventListener("input", function () {
      var accionbtn = document.getElementById("agregarDatos");
    // Verificar si la entrada contiene solo letras
    if (/^[a-zA-Z]+$/.test(serie.value)) {
   //   console.log( 'Entrada contiene solo letras');
    }
    // Verificar si la entrada contiene solo números
    else if (/^[0-9]+$/.test(serie.value)) {
     // console.log( 'Entrada contiene solo números');
    }
    // Si la entrada contiene una combinación de letras y números
    else {
     // console.log( 'Entrada contiene una combinación de letras y números');
    }
      var valor = numDoc.value;
      var valorNumerico = valor.replace(/[^\d]/g, "");
      if (tipoDoc.value === "01" || tipoDoc.value === "03" || tipoDoc.value === "31" || tipoDoc.value === "09") {
          if (numDoc.value.length >= 11) {
          numDoc.value = valorNumerico.slice(0, 11);
          accionbtn.disabled = false;
        } else {
          // Deshabilita el botón si la longitud no es suficiente
          accionbtn.disabled = true;
      }
      }else {
        numDoc.value = valorNumerico.slice(0, 11);
        accionbtn.disabled = false;
      }
    });
  });
  
  var datosArray = [];
  var agregarDatoBtn = document.getElementById("agregarDatos");
  agregarDatoBtn.disabled = true;
  var listaDatos = document.getElementById("listaDatos");
  var tablaDatosBody = document.getElementById("tablaDatosBody");
  function miFuncion() {
    numDoc.value = "";
    serie.value = "";
    correlativo.value = "";
    agregarDatoBtn.disabled = true;
  }
  agregarDatoBtn.addEventListener("click", function () {
    var nuevoDato1 = serie.value;
    var nuevoDato4 = correlativo.value;
    var nuevoDato2 = numDoc.value;
    var nuevoDato3 = tipoDoc.value;
    var textoSeleccionado = tipoDoc.options[tipoDoc.selectedIndex].text;
   // console.log("datos array");
   // console.log(select2.obtenerValor("datos array"));
   // console.log(arraycbocliente);
     var filtaridcliente = arraycbocliente.filter(elemento => elemento.id == select2.obtenerValor("cboCliente"))
     console.log(  datosArray);
    if (nuevoDato3 === "01" || nuevoDato3 === "03" || nuevoDato3 === "82"){
      if( nuevoDato1 && nuevoDato2 &&  nuevoDato3 && nuevoDato4 && textoSeleccionado ){
        // if(numDoc.value  ===  filtaridcliente[0].codigo_identificacion){
          var tipoDocExistente = datosArray.some(function (dato) {
            return dato.numTipoDoc === nuevoDato3;
          }); 
        if (tipoDocExistente) {
          mostrarValidacionLoaderClose('Ya existe un documento existente. No se permite ingresar datos duplicados.');
      } else {
          datosArray.push({
            serieinput: nuevoDato1,
            correlativoinput: nuevoDato4,
            tipoDocinput: textoSeleccionado,
            numTipoDoc: nuevoDato3,
            numDocinput: nuevoDato2,
          });
        }
        //  }else{
        //   mostrarValidacionLoaderClose(" El numero de Documento " + " ( "  + numDoc.value  + " ) No coincide  con el Numero de documento del emisor ");
        //   return; 
        // }
      }else{
         mostrarValidacionLoaderClose("registrar todo los campos");
      }       
     }else{
        console.log("llego");
      if ( nuevoDato1 && nuevoDato2 && nuevoDato3 && nuevoDato4 && textoSeleccionado) {
        var tipoDocExistente = datosArray.some(function (dato) {
          return dato.numTipoDoc === nuevoDato3;
        });
        if (tipoDocExistente && nuevoDato3 === "31") {
          // Verifica si ya hay 2 objetos con tipo de documento 31
          var cantidadObjetosTipo31 = datosArray.filter(function (dato) {
            return dato.numTipoDoc === "31";
          }).length;
          if (cantidadObjetosTipo31 >= 2) {
            mostrarValidacionLoaderClose("Ya existen 2 documentos de  GUIA REMISION TRANSPORTISTA. No se permite agregar más.");
          } else {
            // Agrega el nuevo objeto si no se ha alcanzado el límite
            datosArray.push({
              serieinput: nuevoDato1,
              correlativoinput: nuevoDato4,
              tipoDocinput: textoSeleccionado,
              numTipoDoc: nuevoDato3,
              numDocinput: nuevoDato2,
            });
            console.log("Nuevo objeto agregado correctamente.");
          }
        } else {
          datosArray.push({
            serieinput: nuevoDato1,
            correlativoinput: nuevoDato4,
            tipoDocinput: textoSeleccionado,
            numTipoDoc: nuevoDato3,
            numDocinput: nuevoDato2,
          });
        }
      } else {
        mostrarValidacionLoaderClose("Registrar todo los campos");
      }  
     }
          serie.value = "";
          correlativo.value = "";
          numDoc.value = "";
          actualizarLista();
  });
  function actualizarLista() {
    while (tablaDatosBody.firstChild) {
      tablaDatosBody.removeChild(tablaDatosBody.firstChild);
    }
    datosArray.forEach(function (dato) {
      // var dato = datosArray[i];
      var fila = tablaDatosBody.insertRow();
      var celda1 = fila.insertCell(0);
      var celda2 = fila.insertCell(1);
      var celda3 = fila.insertCell(2);
      var celdaAccion = fila.insertCell(3);
      celda1.textContent = dato.serieinput + "-" + dato.correlativoinput;
      celda2.textContent = dato.tipoDocinput;
      celda3.textContent = dato.numDocinput;
      celdaAccion.innerHTML =
        '<a type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="fa fa-trash"></i></a>';
    });
  }
  // Función para eliminar una fila
  function eliminarFila(icono) {
    var fila = icono.parentNode.parentNode;
    var index = fila.rowIndex - 1; // Restar 1 para obtener el índice correcto en el array
    datosArray.splice(index, 1); // Eliminar el elemento del array
    actualizarLista(); // Actualizar la tabla
  }
  function agregarDetalle() {
    $("#miModal").modal("show");
  }
  
  // ::::::::::::::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::::::::::::::::::::::

function onResponseMovimientoFormTablas(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;

            case 'obtenerXAgenciaIdXPersonaId':
                onResponseObtenerXAgenciaIdXPersonaId(response.data, response[PARAM_TAG]);
                loaderClose();
                break;

            case 'obtenerCorrelativoGuia':
                onResponseObtenerCorrelativoGuia(response.data);
                loaderClose();
                break;

            case 'obtenerUnidadMedida':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerUnidadesMedida(response.data);
                loaderClose();
                break;
            case 'obtenerPrecioUnitario':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerPrecioUnitario(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoTipoDato':
                onResponseObtenerDocumentoTipoDato(response.data);
                loaderClose();
                break;
            case 'enviar':
                let accion = response[PARAM_TAG];
                if (accion == "guardarPago" || accion == "confirmacionPago") {
                    $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
                    $('.modal-backdrop').hide();
                }
                onResponseEnviar(response.data);
                break;
            case 'enviarEImprimir':
                cargarDatosImprimir(response.data, 1);
                break;
            case 'obtenerPersonas':
                onResponseObtenerPersonas(response.data, response.tag);
                break;
            case 'obtenerSelectPersonas':
                onResponseObtenerPersonaActivoXStringBusqueda(response.data, response.tag);
                // ::::::::::::::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::::::::::::::::::::::
            if(cbocliente === 'Cliente'){
                arraycbocliente =  response.data
                console.log("entro por primera vez");
              }
          break;
          // ::::::::::::::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::::::::::::::::::::::
            case 'obtenerStockPorBien':
                onResponseObtenerStockPorBien(response.data, response[PARAM_TAG]);
                break;
            case 'obtenerPrecioPorBien':
                onResponseObtenerPrecioPorBien(response.data, response[PARAM_TAG]);
                break;
            case 'obtenerStockActual':
                response.data[0]["indice"] = response[PARAM_TAG];
                onResponseObtenerStockActual(response.data);
                break;
            case 'obtenerPersonaDireccion':
                onResponseObtenerPersonaDireccion(response.data, response.tag);
                break;
            case 'guardarDocumentoGenerado':
                if (boton.accion == 'enviarEImprimir') {
                    cargarDatosImprimir(response.data, 1);
                } else {
                    cargarPantallaListar();
                }
                break;
            case 'obtenerPreciosEquivalentes':
                onResponseObtenerPreciosEquivalentes(response.data);
                loaderClose();
                break;
            case 'obtenerBienPrecio':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerBienPrecio(response.data);
                loaderClose();
                break;
            case 'buscarDocumentoRelacion':
                onResponseBuscarDocumentoRelacion(response.data);
                loaderClose();
                break;
            //FUNCIONES PARA COPIAR DOCUMENTO
            case 'obtenerConfiguracionBuscadorDocumentoRelacion':
                onResponseObtenerConfiguracionBuscadorDocumentoRelacion(response.data);
                buscarDocumentoRelacionPorCriterios();
                loaderClose();
                break;
            case 'obtenerDocumentoRelacion':
                onResponseObtenerDocumentoRelacion(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionCabecera':
                onResponseObtenerDocumentoRelacionCabecera(response.data);
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':

                onResponseObtenerDocumentoRelacionVisualizar(response.data);
                loaderClose();
                break;

            case 'obtenerConfiguracionCostoReparto':
                document.getElementById("tipotipo").value = response.data;
                loaderClose();
                break;

            case 'obtenerDocumentoRelacionDetalle':
                cargarDetalleDocumentoRelacion(response.data);
                loaderClose();
                break;
            case 'verificarTipoUnidadMedidaParaTramo':
                if (!isEmpty(response.data)) {
                    response.data["indice"] = response[PARAM_TAG];
                }
                onResponseVerificarTipoUnidadMedidaParaTramo(response.data);
                loaderClose();
                break;
            case 'registrarTramoBien':
                onResponseRegistrarTramoBien(response.data);
                loaderClose();
                break;
            case 'obtenerTramoBien':
                onResponseObtenerTramoBien(response.data, response[PARAM_TAG]);
                loaderClose();
                break;
            case 'obtenerPrecioCompra':
                response.data["indice"] = response[PARAM_TAG];
                onResponseObtenerPrecioCompra(response.data);
                loaderClose();
                break;
            case 'modificarDetallePrecios':
                onResponseModificarDetallePrecios(response.data);
                break;
            case 'modificarDetallePreciosXMonedaXOpcion':
                onResponseModificarDetallePreciosXMonedaXOpcion(response.data);
                loaderClose();
                break;
            case 'obtenerTipoCambioXFecha':
                onResponseObtenerTipoCambioXFecha(response.data);
                loaderClose();
                break;
            case 'enviarCorreosMovimiento':
                loaderClose();
                $('#modalCorreos').modal('hide');
                $('.modal-backdrop').hide();
                cargarPantallaListar();
                break;
            case 'eliminarPDF':
                loaderClose();
                cargarPantallaListar();
                break;
            case 'obtenerNumeroNotaCredito':
                onResponseObtenerNumeroNotaCredito(response.data);
                loaderClose();
                break;

            // pagos            
            case 'obtenerDocumentoTipoDatoPago':
                onResponseObtenerDocumentoTipoDatoPago(response.data);
                break;
            case 'guardarDocumentoPago':
                $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
                $('.modal-backdrop').hide();
                onResponseEnviar(response.data);
                break;
            case 'guardarDocumentoAtencionSolicitud':
                $("modalAsignarAtencion").modal('hide');
                $('.modal-backdrop').hide();
                onResponseEnviar(response.data);
            case 'obtenerProductos':
                onResponseObtenerProductos(response.data, response[PARAM_TAG]);
                loaderClose();
                break;
            case 'obtenerStockParaProductosDeCopia':
                onResponseObtenerStockParaProductosDeCopia(response.data);
                loaderClose();
                break;
            case 'obtenerDireccionOrganizador':
                onResponseObtenerDireccionOrganizador(response.data, response[PARAM_TAG]);
                loaderClose();
                break;

        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                setTimeout(function () {
                    cargarPantallaListar();
                }
                    , 2000);
                break;
            case 'obtenerUnidadMedida':
                break;
            case 'enviar':
                cerrarModalAnticipo();
                loaderClose();
                habilitarBoton();
                onResponseEnviarError(response['message']);
                break;
            case 'enviarEImprimir':
                loaderClose();
                habilitarBoton();
                break;
            case 'obtenerDocumentoRelacion':
                loaderClose();
                break;
            case 'obtenerDocumentoRelacionVisualizar':
                loaderClose();
                break;
            case 'guardarDocumentoGenerado':
                habilitarBoton();
                loaderClose();
            case 'enviarCorreosMovimiento':
                loaderClose();
                break;
            case 'eliminarPDF':
                loaderClose();
                cargarPantallaListar();
                break;
            case 'guardarDocumentoPago':
                loaderClose();
                habilitarBoton();
                onResponseEnviarError(response['message']);
                break;
        }
    }
}
var indexDetalleEditar;
var tipoDetalleEditar;
function agregarItem(index = null, tipo = null) {

    if (isEmpty(select2.obtenerValor("cboAgenciaOrigen"))) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione la agencia de origen");
        return;
    }

    if (isEmpty(select2.obtenerValor("cboAgenciaDestino"))) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar la agencia de destino");
        return;
    }

    if (isEmpty(select2.obtenerValor("cboAgenciaDestino"))) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar la agencia de destino");
        return;
    }

    if (isEmpty(select2.obtenerValor("cboMoneda"))) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar la moneda");
        return;
    }

    if (isEmpty(select2.obtenerValor("cboModalidad"))) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar la tipo de pedido.");
        return;
    }

    if (isEmpty(obtenerPersonaId())) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione el cliente y destinatario");
        return;
    }

    tipoDetalleEditar = tipo;
    if (tipoDetalleEditar == 1) {
        $("#divMedidaProducto").hide();
        $("#divBienDetalle").show();
    } else {
        $("#divMedidaProducto").show();
        $("#divBienDetalle").hide();

    }
    indexDetalleEditar = index;
    $("#modalAgregarDetalle").modal('show');
    if (!isEmpty(index)) {
        $("#btnContinuar").hide();
    } else {
        limpiarFormularioDetalle();
        $("#btnContinuar").show();
    }

    if (tipoDetalleEditar == 0) {
        loaderShow();
        select2.asignarValor("cboBien", -1);
        select2.asignarValor("cboPrecioTipo", precioTipoPrimero);
        obtenerUnidadMedida(1);
    }
}


function prepararEdicion(index = null) {
    loaderShow();
    var existeDetalle = false;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(index)) {
            indexDetalleEditar = index;
            tipoDetalleEditar = item.tipo;
            select2.asignarValor("cboBien", item.bienId);
            document.getElementById("txtPrecio").value = item.precio;
            obtenerUnidadMedida(item.bienId, i);
            select2.asignarValor("cboUnidadMedida", item.unidadMedidaId);
            select2.asignarValor("cboPrecioTipo", item.precioTipoId);
            document.getElementById("txtCantidad").value = item.cantidad;
            document.getElementById("txtPrecio").value = item.precio;

            document.getElementById("txtPesoVolumetrico").value = item.bien_peso_volumetrico;
            document.getElementById("txtFactorVolumetrico").value = item.bien_factor_volumetrico;

            document.getElementById("txtAltura").value = item.bien_alto;
            document.getElementById("txtAncho").value = item.bien_ancho;
            document.getElementById("txtLongitud").value = item.bien_longitud;
            document.getElementById("txtPeso").value = item.bien_peso;
            document.getElementById("txtdescripcion").value = item.descripcionpaquete;
            document.getElementById("txtPesoTotal").value = item.bien_peso_total;
            document.getElementById("txtSubTotalDetalle").value = item.subTotal;

            let pesoVolumetricoTotal = (item.cantidad * 1) * (item.bien_peso_volumetrico * 1);

            $("#lblPesoVolumetricoTotal").html('P. V. total: ' + redondearNumerDecimales(pesoVolumetricoTotal));

            if (item.bandera_tipo_peso == 1) {
                $('#rtnButtonPesoTotal').prop('checked', true);
            } else {
                $('#rtnButtonPesoPaquete').prop('checked', true);
            }

            existeDetalle = true;
            return false;
        }
    });
    if (!existeDetalle) {
        mostrarValidacionLoaderClose("No existe data para editar");
    } else {

        agregarItem(indexDetalleEditar, tipoDetalleEditar);
        $("#btnContinuar").hide();
        loaderClose();
    }
}


function editar(bandera_cierre) {
    valoresFormularioDetalle = validarFormularioDetalleTablas();
    if (!valoresFormularioDetalle)
        return;

    if (isEmpty(detalle) || isEmpty(indexDetalleEditar)) {
        indexDetalleEditar = null;
        mostrarValidacionLoaderClose("No se ha encontrado data para editar");
        return;
    }

    valoresFormularioDetalle.accion = "editar";
    confirmarControlador();

    if (bandera_cierre == 1) {
        $('#modalAgregarDetalle').modal('hide').data('bs.modal', null);
    }

}

function limpiarFormularioDetalle() {
    document.getElementById("txtCantidad").value = '1.00';
    document.getElementById("txtPrecio").value = '';
    document.getElementById("txtSubTotalDetalle").value = '';
    document.getElementById("txtAltura").value = '';
    document.getElementById("txtAncho").value = '';
    document.getElementById("txtLongitud").value = '';
    document.getElementById("txtPeso").value = '';
    document.getElementById("txtdescripcion").value = "";
    document.getElementById("txtPesoTotal").value = '';
    document.getElementById("txtPesoVolumetrico").value = '';
    $("#lblPesoVolumetricoTotal").html('');
    $('#rtnButtonPesoPaquete').prop('checked', true);


    $("#cboBien").prop("disabled", false);
    $("#txtPrecio").prop("disabled", true);
    //    $("#cboUnidadMedida").prop("disabled", false);
    //    $("#cboPrecioTipo").prop("disabled", false);
    select2.asignarValor('cboBien', '');
    //    select2.asignarValor('cboPrecioTipo', '');
}

function onResponseEnviarError(mensaje) {

    //ERROR CONTROLADO SUNAT
    if (mensaje.indexOf("[Cod: IMA02]") != -1) {
        swal("Error controlado", mensaje, "error");
    } else if (mensaje.indexOf("[Cod: IMAEX") != -1) {
        swal("Error no controlado", mensaje, "error");
    }

}

function onResponseObtenerPersonaDireccionXTipo(data, item) {
    switch (item.tipo) {
        case "despacho":
            select2.cargar("cbo" + item.id + "Direccion", data, "id", "direccion");
            break;

        case "facturacion":
            select2.cargar("cbo" + item.id + "DireccionFacturacion", data, "id", "direccion");
            break;
    }
}

var dataPersonaDespachoDireccion = [];
var dataPersonaRecojoDireccion = [];
function onResponseObtenerPersonaDireccion(data, parametros) {
    let dataFacturacion = data.filter(item => item.direccion_tipo_id == -1);
    let dataDespacho = data;//.filter(item => item.direccion_tipo_id == 1);
    let agenciaText = "";
    let cboId = parametros.tipo;
    let direccionFacturacionId = "";
    switch (cboId) {
        case "Cliente":
            agenciaText = select2.obtenerText("cboAgenciaOrigen");
            dataPersonaRecojoDireccion = data;
            if (!isEmpty(agenciaText)) {
                agenciaText = agenciaText + " (Agencia)";
                dataDespacho = [{ id: "-1", "direccion_completa": agenciaText, "agencia_id": -1 }].concat(!isEmpty(data) ? data : []);
            }
            break;

        case "Destinatario":
            agenciaText = select2.obtenerText("cboAgenciaDestino");
            dataPersonaDespachoDireccion = data;
            if (obtenerCodigoModalidad() != '1' && !isEmpty(agenciaText)) {
                agenciaText = agenciaText + " (Agencia)";
                dataDespacho = [{ id: "-1", "direccion_completa": agenciaText, "agencia_id": -1 }].concat(!isEmpty(data) ? data : []);
            }

            if (obtenerCodigoModalidad() == '2') {
                parametros.direccionId = -1;
            }
            break;
    }

    if (!isEmpty(dataFacturacion)) {
        direccionFacturacionId = dataFacturacion[0]['id'];
    }
    dataFacturacion = data;

    if (cboId == "Facturado") {
        select2.cargarDataSet("cboClienteDireccionFacturacion", dataFacturacion, "id", "direccion_completa", "agencia_id");
        select2.asignarValor("cboClienteDireccionFacturacion", (!isEmpty(parametros.direccionFacturacionId) ? parametros.direccionFacturacionId : direccionFacturacionId));

    } else {
        select2.cargarDataSet("cbo" + cboId + "Direccion", dataDespacho, "id", "direccion_completa", "agencia_id");
        select2.asignarValor("cbo" + cboId + "Direccion", (!isEmpty(parametros.direccionId) ? parametros.direccionId : "-1"));

        select2.cargarDataSet("cbo" + cboId + "DireccionFacturacion", dataFacturacion, "id", "direccion_completa", "agencia_id");
        select2.asignarValor("cbo" + cboId + "DireccionFacturacion", (!isEmpty(parametros.direccionFacturacionId) ? parametros.direccionFacturacionId : direccionFacturacionId));

    }


    asignarImporteDocumento();
}

function onResponseObtenerDataCbo(cboId, itemId, itemDes, data) {

    document.getElementById('cbo' + cboId).innerHTML = "";

    select2.asignarValor('cbo' + cboId, "");
    //$('#cbo' + cboId).append('<option value=0>Seleccione la dirección</option>');
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            $('#cbo' + cboId).append('<option value="' + item[itemId] + '">' + item[itemDes] + '</option>');
        });

        select2.asignarValor('cbo' + cboId, data[0]["id"]);
    } else {
        select2.asignarValor('cbo' + cboId, 0);
    }
}

function onResponseObtenerStockActual(data) {
    var stock = parseFloat(data[0]['stock']);
    var cantidadMinima = parseFloat(data[0]['cantidad_minima']);
    var indice = parseInt(data[0]['indice']);
    var saldo = 0;

    var indexTemporal = -1;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    detalle[indexTemporal]["stockBien"] = stock;

    var cantidad = 0;
    if (existeColumnaCodigo(12)) {
        cantidad = parseFloat($('#txtCantidad_' + indice).val());
    }

    if (detalle[indexTemporal]["bienTipoId"] != -1) {
        if (movimientoTipoIndicador == 1 || dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
            saldo = stock + cantidad;
        } else if (movimientoTipoIndicador == 2) {
            saldo = stock - cantidad;
        } else {
            saldo = stock;
        }
    } else {
        saldo = 0;
    }

    var bienId = null;
    if (existeColumnaCodigo(11)) {
        bienId = select2.obtenerValor("cboBien_" + indice);
    }
    if (bienId == -1) {
        saldo = 0;
    }
    if (!isEmpty(bienId)) {
        if (existeColumnaCodigo(7)) {
            $('#txtStock_' + indice).html(devolverDosDecimales(saldo));
        }
        if (existeColumnaCodigo(8)) {
            $('#txtStockSugerido_' + indice).html(devolverDosDecimales(cantidadMinima));
        }
    }
}

function hallarStockSaldo(indice) {
    //    var indLista = indiceLista.indexOf(parseInt(indice));   
    var indexTemporal = -1;
    var bandera = false;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    if (indexTemporal != -1 && !isEmpty(detalle[indexTemporal]["stockBien"])) {
        var stock = detalle[indexTemporal]["stockBien"];
        var bienTipoId = detalle[indexTemporal]["bienTipoId"];
        var cantidad = 0;
        if (existeColumnaCodigo(12)) {
            cantidad = parseFloat($('#txtCantidad_' + indice).val());
        }
        var saldo = 0;

        //breakFunction();
        if (isEmpty(stock))
            stock = 0;
        if (bienTipoId != -1) {
            if (movimientoTipoIndicador == 1 || dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                saldo = stock + cantidad;
                bandera = true;
            } else if (movimientoTipoIndicador == 2) {
                saldo = stock - cantidad;
                bandera = true;
            } else {
                saldo = stock;
                bandera = false;
            }
        } else {
            saldo = 0;
        }

        var bienId = null;
        if (existeColumnaCodigo(11)) {
            bienId = select2.obtenerValor("cboBien_" + indice);
        }
        if (bienId == -1) {
            saldo = 0;
        }
        if (!isEmpty(bienId)) {
            if (existeColumnaCodigo(7) && bandera) {
                $('#txtStock_' + indice).html(devolverDosDecimales(saldo));
            }
        }
    }
}

function obtenerDocumentoTipoDato(documentoTipoId) {
    loaderShow();
    ax.setAccion("obtenerDocumentoTipoDato");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

function obtenerUnidadMedida(bienId, indice) {

    var unidadMedidaId = 0;
    if (banderaCopiaDocumento === 1) {
        unidadMedidaId = detalle[indice].unidadMedidaId;
    }
    ax.setAccion("obtenerUnidadMedida");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo"));
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("fechaEmision", $('#txtFechaEmision').val());
    ax.setTag(indice);
    ax.consumir();
}

function onResponseObtenerUnidadesMedida(data, index) {
    //INDEX: SI LA DATA VIENE DE LA COPIA
    if (isEmpty(data.indice)) {
        data.indice = index;
    }

    if (!isEmpty(data) && !isEmpty(data.unidad_medida)) {
        select2.cargar("cboUnidadMedida", data.unidad_medida, "id", "descripcion");
        select2.asignarValor("cboUnidadMedida", data.unidad_medida[0].id);
        select2.readonly("cboUnidadMedida", false);
    }

    var operador = obtenerOperador();

    if (banderaCopiaDocumento === 1) {
        select2.asignarValor("cboUnidadMedida", detalle[data.indice].unidadMedidaId);
        if (unidadMedidaTxt === 1) {
            setearUnidadMedidaDescripcion();
        }
    } else if (banderaCopiaDocumento === 0) {
        if (isEmpty(indexDetalleEditar)) {
            document.getElementById("txtPrecio").value = devolverDosDecimales(data.precio * operador);
            //            hallarSubTotalDetalle();
        }
    }
}

function obtenerOperador() {
    var operadorIGV = 1.18;
    //    if (!isEmpty(importes.subTotalId)) {
    //        if (!document.getElementById('chkIncluyeIGV').checked) {
    //            operadorIGV = 1;
    //        }
    //    } else if (opcionIGV == 0) {
    //        operadorIGV = 1;
    //    }

    return operadorIGV;
}

function onResponseObtenerPreciosEquivalentes(data) {
    if (!isEmpty(data)) {
        var operador = obtenerOperador();

        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + data.indice).html(devolverDosDecimales(data.precioCompra * operador));
        } else {
            varPrecioCompra = devolverDosDecimales(data.precioCompra * operador);
        }
        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data.precio * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

function onResponseObtenerBienPrecio(data) {

    if (!isEmpty(data)) {
        var operador = obtenerOperador();
        document.getElementById("txtPrecio").value = devolverDosDecimales(data[0].precio * operador);
        //        hallarSubTotalDetalle();

    }
}

function obtenerPrecioUnitario(indice) {
    loaderShow();

    var bienId = select2.obtenerValor("cboBien_" + indice);
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida_" + indice);

    ax.setAccion("obtenerPrecioUnitario");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.setTag(indice);
    ax.consumir();
}

function devolverDosDecimales(num) {
    //    return Math.round(num * 100) / 100;
    //    breakFunction();
    return redondearNumero(num).toFixed(2);
    //      return redondearNumero(num);
}

function onResponseObtenerPrecioUnitario(data) {
    if (!isEmpty(data)) {
        if (existeColumnaCodigo(5)) {
            document.getElementById("txtPrecio_" + data.indice).value = devolverDosDecimales(data.precio);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

var organizadorIdDefectoTM;
var movimientoTipoIndicador;
var dataDocumentoTipo;
var dataContOperacionTipo = [];
var inicialAlturaDetalle;
var documentoTipoTipo;
var monedaSimbolo;

var opcionIGV = null;
//var valorOpcionIGV;
var monedaBase = null;
var monedaBaseId = 0;
var cboDetraccionId = null;
var montoTotalDetraido = 0;
var montoTotalRetencion = 0;

//function cambiarTituloDescripcion(tipo) {
//    let textoAgencia = select2.obtenerText("cboAgencia" + tipo);
//    $("#titleAgencia" + tipo).html(textoAgencia + " (" + tipo + ")");
//}

function actualizarPrecioDetalle() {
    if (!isEmpty(detalle)) {
        let precio_k = obtenerValorTarifario('precio_xk');
        let precio_5k = obtenerValorTarifario('precio_5k');
        let precio_sobre = obtenerValorTarifario('precio_sobre');

        $.each(detalle, function (index, item) {
            let precio = 0.00;
            let pesoVolumetrico = 0.00;
            let factor_aplicado = 0.00;
            let precioBien = obtenerTarifarioBien(item.bienId);
            switch (item.tipo * 1) {
                case 0:
                    let cantidad = item.cantidad;
                    let altura = item.bien_alto;
                    let ancho = item.bien_ancho;
                    let longitud = item.bien_longitud;
                    let pesoTotal = item.bien_peso_total;
                    let banderaTipoPeso = item.bandera_tipo_peso; //1 : El calculo en base al peso total / 0 : En base al peso unitario
                    pesoVolumetrico = (altura * 1) * (ancho * 1) * (longitud * 1) * (200 * 1);
                    let pesoVolumetricoTotal = pesoVolumetrico * (cantidad * 1);
                    if (!isEmpty(precio_k) && !isEmpty(precio_5k)) {
                        let precio5kg = Math.round(precio_5k * 1);
                        let pesoUsar = (pesoVolumetricoTotal > pesoTotal ? (pesoVolumetricoTotal * 1) : (pesoTotal * 1));

                        if (banderaTipoPeso == 1) {
                            pesoUsar = (pesoTotal * 1);
                        }

                        if (pesoUsar <= 5) {
                            pesoUsar = 0;
                        } else {
                            pesoUsar = redondearNumerDecimales(pesoUsar - 5);
                        }
                        precio = precio5kg + Math.round(precio_k * (pesoUsar * 1));
                        precio = redondearNumerDecimales(precio / (cantidad * 1), 6);
                    }
                    break;
                case 1:
                    let bien = dataCofiguracionInicial.bien.filter(itemBien => itemBien.id == item.bienId);
                    pesoVolumetrico = (bien[0]['peso_volumetrico'] * 1);
                    if (bien[0]['bien_tipo_id'] == 2) {
                        precio = Math.round(precio_sobre * 1);
                        factor_aplicado = (precio_sobre * 1);
                    } else if (!isEmpty(precioBien)) {
                        precio = Math.round(precioBien * 1);
                        factor_aplicado = (precio_k * 1);
                    } else if (!isEmpty(precio_k)) {
                        precio = Math.round(precio_k * pesoVolumetrico);
                        factor_aplicado = (precio_k * 1);
                    }
                    break;
            }
            detalle[index].bien_peso_volumetrico = redondearNumerDecimales(pesoVolumetrico, 4);
            detalle[index].bien_factor_volumetrico = redondearNumerDecimales(factor_aplicado, 4);
            detalle[index].precio = redondearDosDecimales(precio, 6);
            detalle[index].subTotal = redondearDosDecimales(precio * (item.cantidad));
        });
    }

    dibujarTabla();
    onChangeCheckDevolucionCargo();
    asignarImporteDocumento();
}

function onChangeAgencia() {


    obtenerXAgenciaIdXPersonaId();

    let personaId = select2.obtenerValor("cboDestinatario");
    let direccionId = select2.obtenerValor("cboDestinatarioDireccion");
    let direccionFacturacionId = select2.obtenerValor("cboDestinatarioDireccionFacturacion");

    obtenerPersonaDireccion('Destinatario', personaId, direccionId, direccionFacturacionId);
}
var banderaCargaInicialDireccion = false;
function onChangeCboModalidad() {
    let banderaEliminarDetalle = false;
    switch (obtenerCodigoModalidad()) {
        case '0':
		   
        case '3':
            $("#divClienteDireccionFacturacion").hide();
            $("#divDestinatarioDireccionFacturacion").show();

            $("#lblDestinatarioCbo").html("Cliente / Destinatario");
            $("#lblClienteCbo").html("Remitente");
			 ValidarCreditoDestinatarioOnchangeModalidad();										  
            banderaEliminarDetalle = true;
            break;

        case '1':
        case '2':
            $("#divClienteDireccionFacturacion").show();
            $("#divDestinatarioDireccionFacturacion").hide();
            $("#lblDestinatarioCbo").html("Destinatario");
            $("#lblClienteCbo").html("Cliente");
				 ValidarCreditoRemitenteOnchangeModalidad();								   
            break;
    }
function ValidarCreditoDestinatarioOnchangeModalidad(){  
          let personaIdDestinatario  = select2.obtenerValor("cboDestinatario");
          let cbocpetipo= document.getElementById("cboComprobanteTipo").value;

          if(personaIdDestinatario!=-1)
          {
            if(CreditoDestinatario==0 && cbocpetipo==191)
            {          
                mostrarValidacionLoaderClose('Cliente Destinatario no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta');
                document.getElementById("env").disabled = true;
            }else{
                document.getElementById("env").disabled = false;
            }
          }else{
            document.getElementById("env").disabled = false;
          }
        
    }

function ValidarCreditoRemitenteOnchangeModalidad(){   
        let personaIdcliente = select2.obtenerValor("cboCliente");
        let cbocpetipo= document.getElementById("cboComprobanteTipo").value;
        //let valor = data[0].aprobacion_credito;
           if(personaIdcliente!=-1)
           {
                if(CreditoRemitente==0 && cbocpetipo==191)
                {   
                    mostrarValidacionLoaderClose('Cliente Remitente no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta');
                    document.getElementById("env").disabled = true;
                }else{
                    document.getElementById("env").disabled = false;
                }
           }else{
            document.getElementById("env").disabled = false;
           }
        
    } 

	obtenerPersonaDireccion('Destinatario', select2.obtenerValor("cboDestinatario"));
    obtenerCostoReparto();
    if (banderaEliminarDetalle) {
        if (!isEmpty(detalle)) {
            detalle = [];
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Debido al cambio de precios debe volver a ingresar el detalle del pedido.");
        }

        dibujarTabla();
        onChangeCheckDevolucionCargo();
        asignarImporteDocumento();
    }
}

function onChangeComprobanteTipo(valor) {
     //  ::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::
  var botonMostrarModal = document.getElementById("mostrarModal");
  if (valor == "191" || valor == "7" || valor == "6") {
    botonMostrarModal.style.display = "block";
  } else {
    botonMostrarModal.style.display = "none";
  }
  //  ::::::::::::::DESARROLLADO POR JESUS:::::::::::::::::


    $("#txtGuiaSerie").val('');
    $("#txtGuiaCorrelativo").val('');
	ValidarCreditoPersonaOnchangeTipoDoc(valor);								
    if (!isEmpty(valor) && valor != 6) {
        let valorIdentificador = select2.obtenerValorDataSet("cboComprobanteTipo", "identificador_negocio");
        $("#divGuiaSerieTransportista").hide();
        $("#divGuiaCorrelativoTransportista").hide();

        loaderShow();
        ax.setAccion("obtenerCorrelativoGuia");
        ax.addParamTmp("agencia_id", select2.obtenerValor("cboAgenciaOrigen"));
        ax.addParamTmp("identificador_negocio", valorIdentificador);
        ax.consumir();
    } else {
        $("#divGuiaSerieTransportista").hide();
        $("#divGuiaCorrelativoTransportista").hide();
    }
}

	function ValidarCreditoPersonaOnchangeTipoDoc(valcpe)
{
        if(!isEmpty(valcpe))
        {
            //mostrarValidacionLoaderClose('validacion 1  '+valcpe);
            let personaIdcliente = select2.obtenerValor("cboCliente");
            let personaIdDestinatario  = select2.obtenerValor("cboDestinatario");
            var valtipoPedidoId = select2.obtenerValor("cboModalidad");
            //console.log(valtipoPedidoId); CE=75,PA=77,RD=76, RDCE=82
            //CreditoRemitente, CreditoDestinatario
            if(valcpe==191)
            {   
                //mostrarValidacionLoaderClose('validacion 1  '+valtipoPedidoId);
                //mostrarValidacionLoaderClose('No tiene credito');
                if(valtipoPedidoId==76 ||  valtipoPedidoId==77) {
                    //console.log("validar remitente");
                    if(CreditoRemitente==0)
                    {
                        mostrarValidacionLoaderClose("Cliente no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta");
                        document.getElementById("env").disabled = true;
                    }
                   // console.log("validar remitente");
                   // document.getElementById("env").disabled = true;
                }else if(valtipoPedidoId==75 ||  valtipoPedidoId==82){
                    console.log("validar destinatario");
                    if(CreditoDestinatario==0)
                    {
                        mostrarValidacionLoaderClose("Cliente Destinatario no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta");
                        document.getElementById("env").disabled = true;
                    }
                    
                }
                 
            }else{
                document.getElementById("env").disabled = false;
            }

            
        }
} 											 
 
function onResponseObtenerCorrelativoGuia(data) {

    if (!isEmpty(data)) {
        let documentoTipoDatoSerie = data.filter(item => item.tipo == 7);
        if (!isEmpty(documentoTipoDatoSerie)) {
            let serie = !isEmpty(documentoTipoDatoSerie[0].data) ? (documentoTipoDatoSerie[0].data) : '';
            $("#txtGuiaSerie").val(serie);
        }

        let documentoTipoDatoNumero = data.filter(item => item.tipo == 8);
        if (!isEmpty(documentoTipoDatoNumero)) {
            let numero = (!isEmpty(documentoTipoDatoNumero[0].data) ? documentoTipoDatoNumero[0].data : '');
            $("#txtGuiaCorrelativo").val(numero);
        }
    }
}

function onResponseObtenerConfiguracionesIniciales(data) {

    dataCofiguracionInicial = data;

    let clienteId = -1;
    let destinatarioId = -1;
    let personaId = -1;
    let contactoIds = null;
    let comprobanteTipoId = null;
    let monedaId = null;
    let numeroSerie = '';

    let agenciaOrigenId = data.dataAgenciaUsuario[0]['id'];
    let agenciaDestinoId = null;
    let periodoId = obtenerPeriodoIdXFechaEmision();


    let modalidadId = '77';

    let clienteDireccionId = '-1';
    let clienteDireccionFacturacionId = null;
    let destinatarioDireccionId = '-1';
    let destinatarioDireccionFacturacionId = null;

    let banderaRegistroMovil = null;

    if (!isEmpty(data.dataDocumento)) {
        personaId = data.dataDocumento[0]['persona_id'];
        clienteId = data.dataDocumento[0]['persona_origen_id'];
        destinatarioId = data.dataDocumento[0]['persona_destinatario_id'];
        comprobanteTipoId = data.dataDocumento[0]['comprobante_tipo_id'];
        monedaId = data.dataDocumento[0]['moneda_id'];
        periodoId = data.dataDocumento[0]['periodo_id'];
        agenciaOrigenId = data.dataDocumento[0]['agencia_id'];
        agenciaDestinoId = data.dataDocumento[0]['agencia_destino_id'];
        modalidadId = data.dataDocumento[0]['modalidad_id'];
        banderaRegistroMovil = data.dataDocumento[0]['bandera_registro_movil'];

        contactoIds = data.dataDocumento[0]['contacto'];
        if (!isEmpty(contactoIds)) {
            contactoIds = contactoIds.split(',');
        }

        let guiaRelacionId = data.dataDocumento[0]['guia_relacion'];
        if (!isEmpty(guiaRelacionId)) {
            guiaRelacionId = guiaRelacionId.split(',');
            $.each(guiaRelacionId, function (index, item) {
                dataComboGuia.push({ 'guia': item });
            });

            select2.cargar("cboGuiaRelacion", dataComboGuia, "guia", "guia");
            select2.asignarValor("cboGuiaRelacion", guiaRelacionId);
        }


        numeroSerie = data.dataDocumento[0]['serie'];
        numeroSerie = numeroSerie + '-' + data.dataDocumento[0]['numero'];

        clienteDireccionId = (!isEmpty(data.dataDocumento[0]['persona_direccion_origen_id']) ? data.dataDocumento[0]['persona_direccion_origen_id'] : clienteDireccionId);
        destinatarioDireccionId = (!isEmpty(data.dataDocumento[0]['persona_direccion_destino_id']) ? data.dataDocumento[0]['persona_direccion_destino_id'] : destinatarioDireccionId);

        if (obtenerCodigoModalidad(modalidadId) == '0') {
            destinatarioDireccionFacturacionId = data.dataDocumento[0]['persona_direccion_id'];
        } else {
            clienteDireccionFacturacionId = data.dataDocumento[0]['persona_direccion_id'];
        }

        let montoOtrosCargo = data.dataDocumento[0]['monto_otro_gasto'];
        let montoDevolucionCargo = data.dataDocumento[0]['monto_devolucion_gasto'];
        let montocostoReparto = data.dataDocumento[0]['monto_costo_reparto'];
        let montoDetraccion = data.dataDocumento[0]['monto_detraccion_retencion'];
        let otroGastoDescripcion = data.dataDocumento[0]['otro_gasto_descripcion'];
        let montoPrecioMinimo = data.dataDocumento[0]['ajuste_precio'];
        let montoCostoRecojoDomicilio = data.dataDocumento[0]['costo_recojo_domicilio'];

        if (!isEmpty(montoOtrosCargo) && montoOtrosCargo * 1 > 0) {
            $("#chkOtroCargo").prop("checked", true);
        }

        if (!isEmpty(montoDevolucionCargo) && montoDevolucionCargo * 1 > 0) {
            $("#chkDevolucionCargo").prop("checked", true);
        }

        $('#' + importes.costoRecojoDomicilioId).val(parseFloat(!isEmpty(montoCostoRecojoDomicilio) ? montoCostoRecojoDomicilio : 0).toFixed(2));
        $('#' + importes.ajustePrecioId).val(parseFloat(!isEmpty(montoPrecioMinimo) ? montoPrecioMinimo : 0).toFixed(2));
        $('#' + importes.otrosCargoId).val(parseFloat(!isEmpty(montoOtrosCargo) ? montoOtrosCargo : 0).toFixed(2));
        $('#' + importes.devolucionCargoId).val(parseFloat(!isEmpty(montoDevolucionCargo) ? montoDevolucionCargo : 0).toFixed(2));
        $('#' + importes.costoRepartoId).val(parseFloat(!isEmpty(montocostoReparto) ? montocostoReparto : 0).toFixed(2));
        $('#' + importes.detraccionId).val(parseFloat(!isEmpty(montoDetraccion) ? montoDetraccion : 0).toFixed(2));

        $('#txtDescripcionOtroCargo').val(otroGastoDescripcion);

        $('#txtFechaEmision').val(datex.parserFecha(data.dataDocumento[0]['fecha_emision']));
    }

    select2.cargar("cboAgenciaOrigen", data.dataAgenciaUsuario, "id", "descripcion_corta");
    select2.asignarValor('cboAgenciaOrigen', agenciaOrigenId);
    if (!isEmpty(data.dataAgenciaUsuario)) {
        if (data.dataAgenciaUsuario.length == 1) {
            $("#cboAgenciaOrigen").prop("disabled", true);
        }
    }

    select2.cargar("cboAgenciaDestino", data.dataAgencia, "agencia_id", "descripcion_corta");
    select2.asignarValor('cboAgenciaDestino', agenciaDestinoId);
    //    cambiarTituloDescripcion('Destino');

    select2.cargar("cboPeriodo", data.periodo, "id", ["mes_nombre", "anio"]);
    select2.asignarValor('cboPeriodo', periodoId);

    select2.cargar("cboModalidad", data.dataModalidadPedido, "id", "descripcion");
    select2.asignarValor('cboModalidad', modalidadId);
    onChangeCboModalidad(modalidadId);

    let dataDocumentoTipoDato = dataCofiguracionInicial.documentoTipoDato;
    dataPersona = [];
    if (!isEmpty(dataDocumentoTipoDato)) {
        let documentoTipoDato = dataDocumentoTipoDato.filter(item => item.tipo == 5);
        if (!isEmpty(documentoTipoDato)) {
            if (!isEmpty(dataCofiguracionInicial.dataPersona)) {
                dataPersona = dataCofiguracionInicial.dataPersona;
            } else {
                dataPersona = documentoTipoDato[0].data;
            }
            //            select2.cargarDataSetSeleccione("cboCliente", dataPersona, "id", "text", "persona_tipo_id", "Busque un cliente");
            //            select2.cargarDataSetSeleccione("cboDestinatario", dataPersona, "id", "text", "persona_tipo_id", "Busque un destinatario");

            select2.cargarDataSetSeleccione("cboCliente", dataPersona, "id", ["nombre", "codigo_identificacion"], ["persona_tipo_id", "telefono", "celular"], "Busque un cliente");
            select2.cargarDataSetSeleccione("cboDestinatario", dataPersona, "id", ["nombre", "codigo_identificacion"], ["persona_tipo_id", "telefono", "celular"], "Busque un destinatario");
            select2.cargarDataSetSeleccione("cboFacturado", dataPersona, "id", ["nombre", "codigo_identificacion"], ["persona_tipo_id", "telefono", "celular"], "Busque un destinatario");


            $("#cboCliente").select2({
                width: '100%'
            }).on("change", function (e) {
				
                obtenerXAgenciaIdXPersonaId();
                obtenerCostoReparto();
            ObtenerCreditoClienteRemitente(e.val);
													  
														   
                //                actualizarPrecioDetalle();
                obtenerPersonaDireccion('Cliente', e.val);
            });

            $("#cboDestinatario").select2({
                width: '100%'
            }).on("change", function (e) {
                obtenerXAgenciaIdXPersonaId();
                obtenerCostoReparto();
				ObtenerCreditoClienteDestinatario(e.val);										 
                //                actualizarPrecioDetalle();
                obtenerPersonaDireccion('Destinatario', e.val);
            });


            $("#cboFacturado").select2({
                width: '100%'
            }).on("change", function (e) {
                obtenerPersonaDireccion('Facturado', e.val);
            });


            setTimeout(function () {
                $($("#cboCliente").data("select2").search).on('keyup', function (e) {
                    if (e.keyCode === 13) {
                        obtenerDataCombo('Cliente');
                    }
                });

                $($("#cboDestinatario").data("select2").search).on('keyup', function (e) {
                    if (e.keyCode === 13) {
                        obtenerDataCombo('Destinatario');
                    }
                });
            }, 1000);

            select2.cargar("cboAutorizado", dataPersona, "id", ["nombre", "codigo_identificacion"]);

            //            setTimeout(function () {
            //                select2.asignarValor('cboCliente', "-1");
            //                select2.asignarValor('cboDestinatario', "-1");
            //            }, 500);

            if (!isEmpty(clienteId)) {
                select2.asignarValor('cboCliente', clienteId);
                obtenerPersonaDireccion('Cliente', clienteId, clienteDireccionId, clienteDireccionFacturacionId);
            }
            if (!isEmpty(destinatarioId)) {
                select2.asignarValor('cboDestinatario', destinatarioId);
                obtenerPersonaDireccion('Destinatario', destinatarioId, destinatarioDireccionId, destinatarioDireccionFacturacionId);
            }
            if (!isEmpty(contactoIds)) {
                select2.asignarValor('cboAutorizado', contactoIds);
            }

            if (!isEmpty(personaId) && personaId != clienteId) {
                select2.asignarValor('cboFacturado', personaId);
                obtenerPersonaDireccion('Facturado', personaId, null, clienteDireccionFacturacionId);
            } else {
                $("#divCboFacturado").hide();
            }
        }
        if (isEmpty(numeroSerie)) {
            let documentoTipoDatoSerie = dataDocumentoTipoDato.filter(item => item.tipo == 7);
            if (!isEmpty(documentoTipoDatoSerie)) {
                numeroSerie = !isEmpty(documentoTipoDatoSerie[0].data) ? (documentoTipoDatoSerie[0].data + '-') : '';
            }
            let documentoTipoDatoNumero = dataDocumentoTipoDato.filter(item => item.tipo == 8);
            if (!isEmpty(documentoTipoDatoNumero)) {
                numeroSerie = numeroSerie + '' + (!isEmpty(documentoTipoDatoNumero[0].data) ? documentoTipoDatoNumero[0].data : '');
            }
        }
    }
    if (!isEmpty(banderaRegistroMovil)) {
        $('#cboCliente').prop('disabled', true);
        if (banderaRegistroMovil == 2) {
            $('#cboFacturado').prop('disabled', true);
        }
    } else {
        $("#divCboFacturado").hide();
    }

	

    $('#txCodigo').val(numeroSerie);

    select2.cargarDataSet("cboComprobanteTipo", data.documentoTipo, "id", "descripcion", "identificador_negocio");
    select2.asignarValor('cboComprobanteTipo', comprobanteTipoId);
    if (!isEmpty(comprobanteTipoId)) {
        onChangeComprobanteTipo(comprobanteTipoId);
    }

    select2.cargar("cboMoneda", data.moneda, "id", ["descripcion", "simbolo"]);
    $.each(data.moneda, function (i, itemMoneda) {
        if (itemMoneda.base == 1) {
            monedaBase = itemMoneda.id;
            monedaSimbolo = itemMoneda.simbolo;
            monedaBaseId = itemMoneda.id;
        }
    });

    if (isEmpty(monedaBase)) {
        monedaBase = data.moneda[0].id;
        monedaSimbolo = data.moneda[0].simbolo;
    }

    if (isEmpty(monedaId)) {
        monedaId = monedaBase;
    }

    $("#cboMoneda").select2({
        width: "100%"
    }).on("change", function (e) {
        modificarSimbolosMoneda(e.val, data.moneda[document.getElementById('cboMoneda').options.selectedIndex]);
    });

    select2.asignarValor("cboMoneda", monedaId);

    cargarPrecioTipoDetalleCombo(data.precioTipo);

    modificarSimbolosMoneda(document.getElementById('cboMoneda').options.selectedIndex, data.moneda[document.getElementById('cboMoneda').options.selectedIndex]);


    $("#cboBien").select2({
        width: '100%'
    }).on("change", function (e) {
        // debugger;
        $("#cboBien").prop("disabled", false);
        $('#txtCantidad').prop("disabled", false);
        $('#txtPrecio').prop("disabled", true);
        $('#txtPesoVolumetrico').prop("disabled", true);

        let precio_k = obtenerValorTarifario('precio_xk');
        let precio_sobre = obtenerValorTarifario('precio_sobre');

        let precio = 0.00;
        let factor_aplicado = 0.00;
        let precioBien = obtenerTarifarioBien(e.val);
        // let banderaExiteProductoParaOtroCliente = validarRelacionPrecio(e.val);
        let bienTipoId = select2.obtenerValorDataSet("cboBien", "bien_tipo_id");
        let bienPesoVolumetrico = select2.obtenerValorDataSet("cboBien", "peso_volumetrico");

        if (tipoDetalleEditar == 1) {
            // if (isEmpty(precioBien) && banderaExiteProductoParaOtroCliente) {
            //     $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El articulo no está disponible para este cliente, por favor seleccione otro.");
            //     precio = 0;
            // } else 

            if (bienTipoId == 2 && !isEmpty(precio_sobre)) {
                precio = Math.round((precio_sobre * 1));
                factor_aplicado = (precio_sobre * 1);
            } else if (!isEmpty(precioBien)) {
                precio = Math.round(precioBien * 1);
                if (!isEmpty(precio_k)) {
                    factor_aplicado = (precio_k * 1);
                }
            } else {
                $.Notification.autoHideNotify('warning', 'top right', 'Validación', "El articulo seleccionado no cuenta con un precio en el tarifario, por favor coordinar con el área correspondiente para el ingreso del precio.");
                // precio = Math.round(precio_k * (bienPesoVolumetrico * 1));
                // factor_aplicado = (precio_k * 1);
            }
        }

        document.getElementById("txtPrecio").value = redondearDosDecimales(precio);
        document.getElementById("txtPesoVolumetrico").value = redondearNumerDecimales(bienPesoVolumetrico, 4);
        document.getElementById("txtFactorVolumetrico").value = redondearNumerDecimales(factor_aplicado, 4);

        hallarSubTotalDetalle();
    });

    cargarBienDetalleCombo(data.bien);

    reinicializarDataTableDetalle();

    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumentoACopiar(data.detalleDocumento);
    }

    $('#rtnButtonPesoPaquete').prop('checked', true);
}

function habilitarComboTipoPago() {
    if (documentoTipoTipo == 1 || documentoTipoTipo == 4) {
        $("#divContenedorTipoPago").show();
    } else {
        $("#divContenedorTipoPago").hide();
    }				 
}

function ObtenerCreditoClienteRemitente(valpersonaId)
{
    let modalidavalidacion_= select2.obtenerValor("cboModalidad");
    let comprobanteid_ = select2.obtenerValor("cboComprobanteTipo");
    CreditoRemitente=0;
    $.each(ArrayListaClientesSelect, function(id,val){
        //console.log(val);

        if(val.id == valpersonaId)
        {
            CreditoRemitente=val.aprobacion_credito;
        }
    });
    if(modalidavalidacion_== 77 || modalidavalidacion_==76)
    {
        if(comprobanteid_==191)
        {
            if(CreditoRemitente==0)
            {
                mostrarValidacionLoaderClose("El cliente Remitente no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta");
                document.getElementById("env").disabled = true;
            }else{
                document.getElementById("env").disabled = false;
            }
            
        }else{
            document.getElementById("env").disabled = false;
        }
    }else{
        document.getElementById("env").disabled = false;
    
    }
    console.log(modalidavalidacion_);
    console.log(comprobanteid_);

    //validar modalidad 
    //validar cpe
}


function ObtenerCreditoClienteDestinatario(valpersonaId)
{
    let modalidavalidacion_= select2.obtenerValor("cboModalidad");
    let comprobanteid_ = select2.obtenerValor("cboComprobanteTipo");
    CreditoDestinatario=0;
    $.each(ArrayListaClientesSelect, function(id,val){
        //console.log(val);
        if(val.id == valpersonaId)
        {
            CreditoDestinatario=val.aprobacion_credito;
        }
    });
    if(modalidavalidacion_== 75 || modalidavalidacion_==82)
    {
        if(comprobanteid_==191)
        {
            if(CreditoDestinatario==0)
            {
                mostrarValidacionLoaderClose("El cliente Destinatario  no cuenta con aprobaci�n de cr�dito para emitir Nota de Venta");
                document.getElementById("env").disabled = true;
            }else{
                document.getElementById("env").disabled = false;
            }
            
        }else{
            document.getElementById("env").disabled = false;
        }
    }else{
        document.getElementById("env").disabled = false;
    }
    //validar modalidad 
    //validar cpe
}














var organizadorIdAntes = null;
function llenarComboOrganizadorCabecera(data) {
    if (muestraOrganizador) {
        $("#divContenedorOrganizador").show();

        $("#cboOrganizador").select2({
            width: "100%"
        }).on("change", function (e) {
            confirmarCambioOrganizador();
            //PARA OBTENER LA DIRECCION DE ORGANIZADOR
            obtenerDireccionOrganizador(1);
        });

        select2.cargar("cboOrganizador", data, "id", "descripcion");

        if (organizadorIdDefectoTM != 0)
            select2.asignarValor("cboOrganizador", organizadorIdDefectoTM);
        else
            select2.asignarValor("cboOrganizador", data[0].id);

        organizadorIdAntes = select2.obtenerValor("cboOrganizador");
    }
}

function obtenerDireccionOrganizador(origenDestino) {
    //origenDestino => ORIGEN: 1 DESTINO:2
    var organizadorId = null;
    var dtd = null;
    if (origenDestino == 1) {
        organizadorId = select2.obtenerValor('cboOrganizador');
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 8);
    } else {
        organizadorId = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(17));
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 9);
    }

    if (!isEmpty(dtd) && !isEmpty(organizadorId)) {
        loaderShow();
        ax.setAccion("obtenerDireccionOrganizador");
        ax.addParamTmp("organizadorId", organizadorId);
        ax.setTag(origenDestino);
        ax.consumir();
    }
}

function onResponseObtenerDireccionOrganizador(data, origenDestino) {
    var dtd = null;
    if (origenDestino == 1) {
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 8);
    } else {
        dtd = obtenerDocumentoTipoDatoXTipoXCodigo(2, 9);
    }

    if (!isEmpty(dtd)) {
        $('#txt_' + dtd.id).val(data[0].direccion);
    }
}

function confirmarCambioOrganizador() {
    if (existeColumnaCodigo(7)) {
        if (!isEmpty(detalle)) {
            swal({
                title: "Confirmación de cambio de almacén",
                text: "¿Está seguro de cambiar el almacén, se va a actualizar el stock?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#33b86c",
                confirmButtonText: "Si, actualizar!",
                cancelButtonColor: '#d33',
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    organizadorIdAntes = select2.obtenerValor("cboOrganizador");
                    actualizarStockDetalle();
                } else {
                    select2.asignarValor("cboOrganizador", organizadorIdAntes);
                }
            });
        } else {
            organizadorIdAntes = select2.obtenerValor("cboOrganizador");
        }
    }
}

function actualizarStockDetalle() {
    var varOrganizadorId = select2.obtenerValor("cboOrganizador");

    $.each(detalle, function (indice, item) {
        detalle[indice].organizadorId = varOrganizadorId;
        obtenerStockActual(item.index);
    });
}

function llenarMonedaSimboloTotales() {
    $('#simDevolucionCargo').html(monedaSimbolo);
    $('#simTotal').html(monedaSimbolo);
    $('#simIgv').html(monedaSimbolo);
    $('#simSubTotal').html(monedaSimbolo);
    $('#simDetraccion').html(monedaSimbolo);
}

function llenarCabeceraDetalle() {
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    $("#headDetalleCabecera").empty();
    var fila = "<tr>";

    //columnas dinamicas de acuerdo a documento_tipo_columna
    var anchoDinamicoTabla = 0;//;500;//941-> 1041px

    fila += "<th style='text-align:center; width: 20px;'>#</th>"; // # Item
    anchoDinamicoTabla += parseInt(40);

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.codigo)) {
                case 1://Precio de compra
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + " <div id='simPC' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 2:// Utilidad moneda                    
                    if (documentoTipoTipo == 1) {
                        fila += "<th style='text-align:center;width: " + item.ancho + "px;'>" + item.descripcion + "  <div id='simUD' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                        anchoDinamicoTabla += parseInt(item.ancho);
                    }
                    break;
                case 3:// Utilidad porcentaje                    
                    if (documentoTipoTipo == 1) {
                        fila += "<th style='text-align:center;width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);
                    }
                    break;
                case 4:// 87px TIPO PRECIO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 5:// 69px PRECIO UNITARIO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "<div id='simPU' style='display: inline-table;'>" + monedaSimbolo + "</div></th> ";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 6:// 47px SUB TOTAL
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "<div id='simST' style='display: inline-table;'>" + monedaSimbolo + "</div></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 7:// 40px STOCK
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 8:// 86px STOCK MINIMO
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);

                        $('#divTodasFilas').hide();
                    }
                    break;
                case 9:// 59px PRIORIDAD
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                        anchoDinamicoTabla += parseInt(item.ancho);

                        $('#divTodasFilas').hide();
                    }
                    break;
                case 10:// 14px COLUMNA EN BLANCO
                    fila += "<th style='text-align:center; border:0; width: " + item.ancho + "px;' bgcolor='#FFFFFF'></th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 11:// 310 PRODUCTO
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" +
                        "<a  title='Nuevo producto' onclick='cargarBien()'>&nbsp;&nbsp;<i class='fa fa-plus-circle' style='color:#1ca8dd'></i></a>&nbsp;&nbsp;" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 12:// 47 CANTIDAD
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 13:// 71 UNIDAD DE MEDIDA
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 14:// 40 ACCIONES
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 15:// 47 Organizador
                    if (muestraOrganizador && organizadorIdDefectoTM == 0) {
                        fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    }
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 16://descripcion de producto
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 17://descripcion de unidad de medida
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 18://fecha de vencimiento
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
                case 21://comentario
                    fila += "<th style='text-align:center; width: " + item.ancho + "px;' class='claseMostrarColumnaComentario'>" + item.descripcion + "</th>";
                    anchoDinamicoTabla += parseInt(item.ancho);
                    break;
            }
        });
    }

    fila = fila + "</tr>";


    if (documentoTipoTipo == 1 && existeColumnaCodigo(2) && existeColumnaCodigo(3)) {
        $("#contenedorUtilidadesTotales").show();
    }

    if (!isEmpty(dataColumna)) {
        if (anchoDinamicoTabla > 1195) {
            $("#datatable").width(anchoDinamicoTabla + 2 * dataColumna.length);
        }
    }
    $('#datatable thead').append(fila);
}

var muestraOrganizador = true;
var dataCofiguracionInicial;
var dataPersona;
var nroFilasInicial;
var nroFilasReducida;
var alturaBienTD;
var anchoUnidadMedidaTD;
var anchoTipoPrecioTD;
function llenarTablaDetalle(data) {
    var nroFilas = nroFilasReducida;
    //NUEVO   

    var cuerpo = "";

    //LLENAR TABLA DETALLE
    for (var i = 0; i < nroFilas; i++) {
        //var i=0;
        cuerpo += llenarFilaDetalleTabla(i);
    }

    $('#datatable tbody').append(cuerpo);

    if (existeColumnaCodigo(11)) {
        alturaBienTD = $("#tdBien_" + (nroFilas - 1)).width();
    }
    if (existeColumnaCodigo(4)) {
        anchoTipoPrecioTD = $("#tdTipoPrecio_" + (nroFilas - 1)).width();
    }
    if (existeColumnaCodigo(13)) {
        anchoUnidadMedidaTD = $("#tdUnidadMedida_" + (nroFilas - 1)).width();
    }

    //LLENAR COMBOS
    for (var i = 0; i < nroFilas; i++) {
        cargarOrganizadorDetalleCombo(data.organizador, i);
        cargarUnidadMedidadDetalleCombo(i);
        cargarBienDetalleCombo(data.bien, i);
        cargarPrecioTipoDetalleCombo(data.precioTipo, i);
        inicializarFechaVencimiento(i);
    }

}

var banderaVerTodasFilas = 0;
function verTodasFilas() {

    $("#contenedorDetalle").css("height", $("#contenedorDetalle").height() + 38 * (nroFilasInicial - nroFilasReducida));

    //NUEVO

    var cuerpo = "";

    //LLENAR TABLA DETALLE
    for (var i = nroFilasReducida; i < nroFilasInicial; i++) {
        //var i=0;
        cuerpo += llenarFilaDetalleTabla(i);
    }

    $('#datatable tbody').append(cuerpo);

    //LLENAR COMBOS
    for (var i = nroFilasReducida; i < nroFilasInicial; i++) {
        cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, i);
        cargarUnidadMedidadDetalleCombo(i);
        cargarBienDetalleCombo(dataCofiguracionInicial.bien, i);
        cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, i);
        inicializarFechaVencimiento(i);
    }

    nroFilasReducida = nroFilasInicial;
    $('#divTodasFilas').hide();
    if (dataCofiguracionInicial.documento_tipo[0].cantidad_detalle != 0 && !isEmpty(dataCofiguracionInicial.documento_tipo[0].cantidad_detalle)) {
        $('#divAgregarFila').hide();
    }

    banderaVerTodasFilas = 1;
    //    loaderClose();
}

function inicializarFechaVencimiento(indice) {
    $('#txtFechaVencimiento_' + indice).datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    }).on('changeDate', function (ev) {
        actualizarTotalesGenerales(parseInt(indice));
    });
}

function obtenerStockActual(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerStockActual");
        var organizadorId = null;
        if (organizadorIdDefectoTM == 0) {
            if (!existeColumnaCodigo(15)) {
                if (muestraOrganizador) {
                    organizadorId = select2.obtenerValor('cboOrganizador');
                }
            } else {
                organizadorId = select2.obtenerValor("cboOrganizador_" + indice);
            }
        } else {
            organizadorId = organizadorIdDefectoTM;
        }

        //ALMACEN DE LLEGADA PARA TRANSFERENCIA INTERNA
        var organizadorDestinoId = null;
        if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
            var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
            if (!isEmpty(dtdOrganizadorId)) {
                organizadorDestinoId = select2.obtenerValor('cbo_' + dtdOrganizadorId);
            }
        }

        ax.addParamTmp("organizadorId", organizadorId);
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", select2.obtenerValor("cboBien_" + indice));
        ax.addParamTmp("organizadorDestinoId", organizadorDestinoId);
        ax.setTag(indice);
        ax.consumir();
    }
}

function obtenerPreciosEquivalentes(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerPreciosEquivalentes");
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indice));
        ax.addParamTmp("bienId", select2.obtenerValor("cboBien_" + indice));
        if (existeColumnaCodigo(4)) {
            ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo_" + indice));
        } else {
            ax.addParamTmp("precioTipoId", null);
        }
        //        ax.addParamTmp("monedaId", dataCofiguracionInicial.movimientoTipo[0].moneda_id);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
        ax.addParamTmp("indice", indice);
        ax.consumir();
    }
}

function obtenerBienPrecio() {
    var bienId = select2.obtenerValor("cboBien");
    if (!isEmpty(bienId)) {
        loaderShow();
        ax.setAccion("obtenerBienPrecio");
        ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida"));
        ax.addParamTmp("bienId", bienId);
        if (existeColumnaCodigo(4)) {
            ax.addParamTmp("precioTipoId", select2.obtenerValor("cboPrecioTipo"));
        } else {
            ax.addParamTmp("precioTipoId", null);
        }
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.consumir();
    }
}

function cargarOrganizadorDetalleCombo(data, i) {
    if (!isEmpty(data) && existeColumnaCodigo(15)) {
        $("#cboOrganizador_" + i).select2({
            width: "100%"
        }).on("change", function (e) {
            indiceBien = i;

            obtenerStockActual(indiceBien);
            hallarSubTotalDetalle(indiceBien);
        });
        select2.cargar("cboOrganizador_" + i, data, "id", "descripcion");
        if (organizadorIdDefectoTM != 0)
            select2.asignarValor("cboOrganizador_" + i, organizadorIdDefectoTM);
        else
            select2.asignarValor("cboOrganizador_" + i, data[0].id);

    } else {
        $("#contenedorOrganizador_" + i).hide();
        validacion.organizadorExistencia = false;
    }
}

var indiceBien;
var primeraFechaEmision;
var banderaPrimeraFE = true;
function cargarBienDetalleCombo(data) {
    select2.cargarDataSet("cboBien", data, "id", ["codigo", "descripcion"], ["bien_tipo_id", "peso_volumetrico"]);
    select2.asignarValor("cboBien", null);
}

function obtenerPrecioCalculado() {
    // debugger;
    if (tipoDetalleEditar == 0) {
        let precio = 0.00;

        let precio_k = obtenerValorTarifario('precio_xk');
        let precio_5k = obtenerValorTarifario('precio_5k');
        let cantidad = document.getElementById("txtCantidad").value;
        let altura = document.getElementById("txtAltura").value;
        let ancho = document.getElementById("txtAncho").value;
        let longitud = document.getElementById("txtLongitud").value;
        let peso = document.getElementById("txtPeso").value;
        let pesoTotal = document.getElementById("txtPesoTotal").value;
        let banderaTipoPeso = $('input[name="rtnButtonPeso"]:checked').val();
        if (banderaTipoPeso == 0) {
            pesoTotal = (peso * 1) * (cantidad * 1);
        } else if (banderaTipoPeso == 1) {
            peso = (pesoTotal * 1) / (cantidad * 1);
        }

        let factor_aplicado = 0.00;
        let pesoVolumetrico = (altura * 1) * (ancho * 1) * (longitud * 1) * (200 * 1);
        let pesoVolumetricoTotal = pesoVolumetrico * (cantidad * 1);

        if (isEmpty(altura) && (!isEmpty(peso) || !isEmpty(pesoTotal))) {
            document.getElementById("txtAltura").value = 0;
        }

        if (isEmpty(ancho) && (!isEmpty(peso) || !isEmpty(pesoTotal))) {
            document.getElementById("txtAncho").value = 0;
        }

        if (isEmpty(longitud) && (!isEmpty(peso) || !isEmpty(pesoTotal))) {
            document.getElementById("txtLongitud").value = 0;
        }

        $("#lblPesoVolumetricoTotal").html('P. V. total: ' + redondearNumerDecimales(pesoVolumetricoTotal));

        if (!isEmpty(precio_k) && !isEmpty(precio_5k)) {

            let pesoUsar = ((pesoVolumetricoTotal > pesoTotal) ? (pesoVolumetricoTotal * 1) : (pesoTotal * 1));

            if (banderaTipoPeso == 1) {// 1: Usar el peso total - 0: Usar el peso por cada paquete.
                pesoUsar = (pesoTotal * 1);
            }

            if (pesoUsar <= 5) {
                pesoUsar = 0;
            } else {
                pesoUsar = redondearNumerDecimales(pesoUsar - 5);
            }

            precio = Math.round((precio_5k * 1) + (precio_k * (pesoUsar * 1)));
            precio = redondearNumerDecimales(precio / (cantidad * 1), 6);

            factor_aplicado = (precio_k * 1);
        }

        if (banderaTipoPeso == 0) {
            document.getElementById("txtPesoTotal").value = redondearDosDecimales(pesoTotal);
        } else if (banderaTipoPeso == 1) {
            document.getElementById("txtPeso").value = redondearDosDecimales(peso);
        }

        document.getElementById("txtPrecio").value = redondearNumerDecimales(precio, 6);
        document.getElementById("txtPesoVolumetrico").value = redondearNumeroXDecimales(pesoVolumetrico, 4);
        document.getElementById("txtFactorVolumetrico").value = redondearNumeroXDecimales(factor_aplicado, 4);

        var subTotal = (cantidad * precio);
        $('#txtSubTotalDetalle').val(devolverDosDecimales(subTotal));
    } else {
        hallarSubTotalDetalle();
    }

}

function cargarUnidadMedidadDetalleCombo(indice) {
    $("#cboUnidadMedida_" + indice).select2({
        width: "100%"
    }).on("change", function (e) {
        indiceBien = indice;

        obtenerPreciosEquivalentes(indice);
        obtenerStockActual(indice);
        setearUnidadMedidaDescripcion(indice);
    });

    $("#cboUnidadMedida_" + indice).select2({ width: anchoUnidadMedidaTD + "px" });
}

var precioTipoPrimero;
function cargarPrecioTipoDetalleCombo(data) {

    $("#cboPrecioTipo").select2({
        width: "100%"
    }).on("change", function (e) {
        obtenerBienPrecio();
    });

    if (!isEmpty(data)) {
        select2.cargar("cboPrecioTipo", data, "precio_tipo_id", "precio_tipo_descripcion");
        precioTipoPrimero = data[0]["precio_tipo_id"];
    }

}

var KPADINGTD = 1;
function llenarFilaDetalleTabla(indice) {
    numeroItemFinal++;
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    var fila = "<tr id=\"trDetalle_" + indice + "\">";

    fila = fila + "<td style='border:0; width: 20px; vertical-align: middle; padding-right: 10px;'id=\"txtNumItem_" + indice + "\" name=\"txtNumItem_" + indice + "\" align='right'>" + numeroItemFinal + "</td>";

    //columnas dinamicas de acuerdo a documento_tipo_columna
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.codigo)) {
                case 1://Precio de compra
                    fila = fila + "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'id=\"txtPrecioCompra_" + indice + "\" name=\"txtPrecioCompra_" + indice + "\" align='right'></td>";
                    break;
                case 2:// Utilidad moneda                    
                    if (documentoTipoTipo == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtUtilidadSoles_" + indice + "\" name=\"txtUtilidadSoles_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 3:// Utilidad porcentaje                    
                    if (documentoTipoTipo == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtUtilidadPorcentaje_" + indice + "\" name=\"txtUtilidadPorcentaje_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 4:// Tipo de precio
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdTipoPrecio_" + indice + "\">" + agregarPrecioTipoTabla(indice) + "</td>";
                    break;
                case 5://Precio unitario
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarPrecioUnitarioDetalleTabla(indice) + "</td>";
                    break;
                case 6://Sub total detalle
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtSubTotalDetalle_" + indice + "\" name=\"txtSubTotalDetalle_" + indice + "\" align='right'></td>";
                    break;
                case 7://Stock
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtStock_" + indice + "\" name=\"txtStock_" + indice + "\" align='right'></td>";
                    break;
                case 8://Stock minimo
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtStockSugerido_" + indice + "\" name=\"txtStockSugerido_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 9://Prioridad
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"txtPrioridad_" + indice + "\" name=\"txtPrioridad_" + indice + "\" align='right'></td>";
                    }
                    break;
                case 10://Columna en blanco
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' bgcolor='#FFFFFF'></td>";
                    break;
                case 11://Producto
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdBien_" + indice + "\">" + agregarBienDetalleTabla(indice) + "</td>";
                    break;
                case 12://Cantidad
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarCantidadDetalleTabla(indice) + "</td>";
                    break;
                case 13://Unidad de medida
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' id=\"tdUnidadMedida_" + indice + "\">" + agregarUnidadMedidaDetalleTabla(indice) + "</td>";
                    break;
                case 14://Acciones
                    fila += "<td style='border:0; width: " + item.ancho + "px; text-align:center; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarAccionesDetalleTabla(indice) + "</td>"
                    break;
                case 15://Organizador
                    if (muestraOrganizador && organizadorIdDefectoTM == 0) {
                        fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarOrganizadorDetalleTabla(indice) + "</td>";
                    }
                    break;
                case 16://Producto descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarProductoDescripcionDetalleTabla(indice, item.longitud) + "</td>";
                    break;
                case 17://Unidad de medida descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarUnidadMedidaDescripcionDetalleTabla(indice, item.longitud) + "</td>";
                    break;
                case 18://Unidad de medida descripcion
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;'>" + agregarFechaVencimientoDetalleTabla(indice) + "</td>";
                    break;
                case 21://Comentario
                    fila += "<td style='border:0; width: " + item.ancho + "px; vertical-align: middle; padding: " + KPADINGTD + "px;' class='claseMostrarColumnaComentario'>" + agregarComentarioDetalleTabla(indice, item.longitud) + "</td>";
                    break;
            }
        });
    }

    fila = fila + "</tr>";

    return fila;
}

function agregarAccionesDetalleTabla(i) {
    var $html = '<div class="btn-toolbar" role="toolbar">' +
        '&nbsp;<a onclick=\"confirmarEliminar(' + i + ');\">' +
        '<i class=\"fa fa-trash-o\" style=\"color:#cb2a2a;\" title=\"Eliminar\"></i></a>' +
        '<div class="btn-group" style="float: right">' +
        '<a  class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" onclick=\"eliminarOverflowDataTable();\">' +
        '<i class="ion-gear-a"></i>  <span class="caret"></span>' +
        '</a>' +
        '<ul class="dropdown-menu dropdown-menu-right">' +
        '<li>' +
        '<a onclick=\"verificarTipoUnidadMedida(' + i + ');\">' +
        '<i class=\"ion-ios7-toggle\"   style=\"color:#1ca8dd;\"   title=\"Registrar tramo\"></i>&nbsp;Registrar tramo</a>' +
        '</li>' +
        '<li>' +
        '<a onclick=\"listarTramosBien(' + i + ');\">' +
        '<i class=\"fa  fa-tasks\"    style=\"color:#615ca8;\"  title=\"Listar tramo\"></i>&nbsp;Listar tramo</a>' +
        '</li>' +
        '<li>' +
        '<a onclick=\"verificarStockBien(' + i + ');\">' +
        '<i class=\"fa fa-cubes\"    style=\"color:#5cb85c;\" title=\"Verificar stock\"></i>&nbsp;Verificar stock</a>' +
        '</li>' +
        '<li>' +
        '<a onclick=\"verificarPrecioBien(' + i + ');\">' +
        '<i class=\"ion-pricetag\"  title=\"Ver precio\"></i>&nbsp;Precio mínimo</a>' +
        '</li>' +
        '<li>' +
        '<a onclick=\"relacionarActivoFijo(' + i + ');\">' +
        '<i class=\"ion-android-share\"  style=\"color:#E8BA2F;\" title=\"Relacionar activo fijo\"></i>&nbsp;Relacionar activo fijo</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>';
    return $html;
}

function eliminarOverflowDataTable() {
}

function agregarOverflowDataTable() {
}

$('#window').click(function (e) {
    var valor = e.target.className;

    if (valor != 'dropdown-toggle' && valor != 'ion-gear-a' && valor != 'caret') {
        agregarOverflowDataTable();
    }

});

function agregarOrganizadorDetalleTabla(i) {
    var $html = "<div id=\"contenedorOrganizador_" + i + "\">" +
        "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboOrganizador_" + i + "\" id=\"cboOrganizador_" + i + "\" class=\"select2\">" +
        "</select></div></div>";

    return $html;
}

function agregarCantidadDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"number\" id=\"txtCantidad_" + i + "\" name=\"txtCantidad_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"1\" style=\"text-align: right;\" onchange=\"hallarSubTotalDetalle(" + i + ");hallarStockSaldo(" + i + ");\" onkeyup =\"hallarSubTotalDetalle(" + i + ");hallarStockSaldo(" + i + ");\"/></div>";

    return $html;
}

function agregarProductoDescripcionDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"text\" id=\"txtProductoDescripcion_" + i + "\" name=\"txtProductoDescripcion_" + i + "\" maxlength='" + longitud + "' readonly='true' class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarComentarioDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12 claseMostrarColumnaComentario\">" +
        "<input  type=\"text\" id=\"txtComentarioDetalle_" + i + "\" name=\"txtComentarioDetalle_" + i + "\" maxlength='" + longitud + "' readonly='true'  class=\"form-control claseMostrarColumnaComentario\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarUnidadMedidaDescripcionDetalleTabla(i, longitud) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"text\" id=\"txtUnidadMedidaDescripcion_" + i + "\" name=\"txtUnidadMedidaDescripcion_" + i + "\" maxlength='" + longitud + "' readonly='true' class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: left;\" onchange=\"hallarSubTotalDetalle(" + i + ");\" /></div>";

    return $html;
}

function agregarFechaVencimientoDetalleTabla(i) {
    var fecha = obtenerFechaActual();

    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaVencimiento_' + i + '" value="' + fecha + '">' +
        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>'
        + "</div>";

    return $html;
}

function agregarPrecioUnitarioDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"number\" id=\"txtPrecio_" + i + "\" name=\"txtPrecio_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: right;\" onchange=\"hallarSubTotalDetalle(" + i + ")\" onkeyup =\"hallarSubTotalDetalle(" + i + ")\"/></div>";

    return $html;
}


function hallarSubTotalDetalle() {
    var subTotal = $('#txtCantidad').val() * $('#txtPrecio').val();
    $('#txtSubTotalDetalle').val(devolverDosDecimales(subTotal));

    //    calcularDescuentoPromocion();
}

function obtenerUtilidadesGenerales() {
    if (documentoTipoTipo == 1 && existeColumnaCodigo(2) && existeColumnaCodigo(3)) {

        var totalUtilidadesSoles = 0;
        var utilidadSoles = 0;

        var totalDocumento = parseFloat($('#' + importes.totalId).val());
        if (isEmpty(totalDocumento)) {
            totalDocumento = calcularImporteDetalle();
        }
        var totalUtilidadesPorcentaje = 0;

        if (totalDocumento != 0) {

            for (var i = 0; i < nroFilasInicial; i++) {
                utilidadSoles = parseFloat($('#txtUtilidadSoles_' + i).html());
                if (isEmpty(utilidadSoles) || !esNumero(utilidadSoles)) {
                    utilidadSoles = 0;
                }

                totalUtilidadesSoles = totalUtilidadesSoles + utilidadSoles;
            }

            totalUtilidadesPorcentaje = (totalUtilidadesSoles / totalDocumento) * 100;
        }


        $('#txtTotalUtilidadSoles').val(devolverDosDecimales(totalUtilidadesSoles));
        $('#txtTotalUtilidadPorcentaje').val(devolverDosDecimales(totalUtilidadesPorcentaje) + " %");

        document.getElementById('txtTotalUtilidadSoles').style.color = '#FF0000';
        document.getElementById('txtTotalUtilidadPorcentaje').style.color = '#FF0000';


        //guardar en array detalle utilidades general en primera fila     
        if (!isEmpty(detalle[0])) {
            detalle[0].utilidadTotal = totalUtilidadesSoles;
            detalle[0].utilidadPorcentajeTotal = totalUtilidadesPorcentaje;
        }
        //fin guardar utilidad 
    }
}

function obtenerUtilidades(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (documentoTipoTipo == 1 && bienId != -1 && !isEmpty(bienId) &&
        existeColumnaCodigo(1) && existeColumnaCodigo(5) && existeColumnaCodigo(6) && existeColumnaCodigo(12)) {
        if (!valoresFormularioDetalle)
            return;

        var precioVenta = $('#txtPrecio_' + indice).val();
        var precioCompra = $('#txtPrecioCompra_' + indice).html();
        var cantidad = $('#txtCantidad_' + indice).val();

        var utilidadSoles = (precioVenta - precioCompra) * cantidad;

        var subTotal = $('#txtSubTotalDetalle_' + indice).html();
        var utilidadPorcentaje = 0;
        if (subTotal != 0) {
            utilidadPorcentaje = (utilidadSoles / subTotal) * 100;
        }

        if (existeColumnaCodigo(2)) {
            $('#txtUtilidadSoles_' + indice).html(devolverDosDecimales(utilidadSoles));
            document.getElementById('txtUtilidadSoles_' + indice).style.color = '#FF0000';
        }
        if (existeColumnaCodigo(3)) {
            $('#txtUtilidadPorcentaje_' + indice).html(devolverDosDecimales(utilidadPorcentaje) + " %");
            document.getElementById('txtUtilidadPorcentaje_' + indice).style.color = '#FF0000';
        }

        //guardar en array detalle

        var indexTemporal = -1;
        if (existeColumnaCodigo(2) || existeColumnaCodigo(3)) {
            $.each(detalle, function (i, item) {
                if (parseInt(item.index) === parseInt(indice)) {
                    indexTemporal = i;
                    return false;
                }
            });
        }

        if (indexTemporal > -1) {
            if (existeColumnaCodigo(2)) {
                detalle[indexTemporal].utilidad = utilidadSoles;
            }
            if (existeColumnaCodigo(3)) {
                detalle[indexTemporal].utilidadPorcentaje = utilidadPorcentaje;
            }
        }
        //fin guardar utilidad detalle
    }
}


var indiceLista = [];

function actualizarTotalesGenerales(indice) {
    valoresFormularioDetalle = validarFormularioDetalleTablas(indice);
    if (!valoresFormularioDetalle)
        return;

    var subTotal = valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio;

    if (existeColumnaCodigo(6)) {
        $('#txtSubTotalDetalle_' + indice).html(devolverDosDecimales(subTotal));
    }

    valoresFormularioDetalle.subTotal = subTotal;
    valoresFormularioDetalle.index = indice;

    var indexTemporal = -1;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indice)) {
            indexTemporal = i;
            return false;
        }
    });

    //ASIGNO EL VALOR DE COMPRA EN CASO NO ESTE CONFIGURADA LA COLUMNA COMPRA  
    if (!existeColumnaCodigo(1)) {
        var precioCompraTemp = 0;
        if (indexTemporal > -1) {
            if (!isEmpty(detalle[indexTemporal].precioCompra)) {
                precioCompraTemp = detalle[indexTemporal].precioCompra;
            }
        }

        //        if(varPrecioCompra != 0){
        if (indexTemporal > -1) {
            if (detalle[indexTemporal].bienId != valoresFormularioDetalle.bienId || varPrecioCompra != 0) {
                valoresFormularioDetalle.precioCompra = varPrecioCompra;
            } else {
                valoresFormularioDetalle.precioCompra = precioCompraTemp;
            }
        } else {
            valoresFormularioDetalle.precioCompra = varPrecioCompra;
        }

        varPrecioCompra = 0;
    }

    if (indexTemporal > -1) {
        detalle[indexTemporal] = valoresFormularioDetalle;
    } else {
        detalle[detalle.length] = valoresFormularioDetalle;
    }
    asignarImporteDocumento();
}

function eliminarDetalleFormularioListas(indiceDet) {
    /*var indice = indiceLista.indexOf(indiceDet);
     if (indice > -1) {    
     indiceLista.splice(indice, 1);
     detalle.splice(indice, 1);
     }*/
    if (indiceDet > -1) {
        //indiceLista.splice(indiceDet, 1);
        detalle.splice(indiceDet, 1);
    }
}

function confirmar(bandera_cierre) {
    loaderShow();
    if (isEmpty(indexDetalleEditar)) {
        agregar(bandera_cierre);

    } else {
        editar(bandera_cierre);

    }
}

function obtenerPersonaId() {


    var personaId = select2.obtenerValor("cboCliente");
 ///::: JESUS
 console.log("limpiar");
 console.log(personaId);
console.log("array");
console.log(datosArray);
console.log("Actualizar lista");
//datosArray=[];
//actualizarLista();
///::: JESUS

    if (obtenerCodigoModalidad() == '0' || obtenerCodigoModalidad() == '3') {
        personaId = select2.obtenerValor("cboDestinatario");
    }

    return (personaId != -1 ? personaId : null);
}

function obtenerTarifarioBien(bienId) {
    let valorFactor = null;
    if (!isEmpty(dataCofiguracionInicial.dataTarifario)) {
        var agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
        var agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
        var personaId = obtenerPersonaId();
        var monedaId = select2.obtenerValor("cboMoneda");
        var factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
            && item.agencia_destino_id == agenciaDestinoId
            && item.persona_id == personaId
            && item.bien_id == bienId
            && item.moneda_id == monedaId
            && (item.precio_articulo * 1) > 0
        );
        if (!isEmpty(factor)) {
            return (factor[0]['precio_articulo'] * 1);
        } else {
            factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
                && item.agencia_destino_id == agenciaDestinoId
                && item.bien_id == bienId
                && item.moneda_id == monedaId
                && isEmpty(item.persona_id)
                && (item.precio_articulo * 1) > 0
            );

            if (!isEmpty(factor)) {
                return (factor[0]['precio_articulo'] * 1);
            }
        }
    }
    return valorFactor;
}

function validarRelacionPrecio(bienId) {
    let personaId = obtenerPersonaId();
    let agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
    let agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
    let monedaId = select2.obtenerValor("cboMoneda");
    factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
        && item.agencia_destino_id == agenciaDestinoId
        && item.moneda_id == monedaId
        && item.bien_id == bienId
        && item.persona_id != personaId
        && !isEmpty(item.persona_id)
        && (item['precio_articulo'] * 1) > 0
    );

    if (!isEmpty(factor)) {
        return true;
    }
    return false;
}


function obtenerValorTarifario(columna) {

    let valorFactor = null;
    if (!isEmpty(dataCofiguracionInicial.dataTarifario)) {
        var agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
        var agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
        var personaId = obtenerPersonaId();
        var monedaId = select2.obtenerValor("cboMoneda");
        var factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
            && item.agencia_destino_id == agenciaDestinoId
            && item.persona_id == personaId
            && item.moneda_id == monedaId
            && (item[columna] * 1) > 0
        );
        if (!isEmpty(factor)) {
            return (factor[0][columna] * 1);
        } else if (columna == 'precio_minimo') {
            return 0;
        } else {
            agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
            agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
            monedaId = select2.obtenerValor("cboMoneda");
            factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
                && item.agencia_destino_id == agenciaDestinoId
                && item.moneda_id == monedaId
                && isEmpty(item.persona_id)
                && (item[columna] * 1) > 0
            );

            if (!isEmpty(factor)) {
                return (factor[0][columna] * 1);
            }
        }
    }
    return valorFactor;
}

function factorVolumetrico() {
    let valorFactor = {};
    if (!isEmpty(dataCofiguracionInicial.dataTarifario)) {
        var agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
        var agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
        var monedaId = select2.obtenerValor("cboMoneda");
        var factor = dataCofiguracionInicial.dataTarifario.filter(item => item.agencia_origen_id == agenciaOrigenId
            && item.agencia_destino_id == agenciaDestinoId
            && item.moneda_id == monedaId
            && ((item.precio_sobre * 1) > 0 || (item.precio_xk * 1) > 0 || (item.precio_5k * 1) > 0)
        );
        if (!isEmpty(factor)) {
            return factor[0];
        }
    }
    return valorFactor;
}
var varPrecioCompra = 0;
function validarFormularioDetalleTablas() {
    var objDetalle = {};//Objeto para el detalle    
    var correcto = true;
    var detDetalle = [];
    objDetalle.precioCompra = 0;
    objDetalle.precio = document.getElementById("txtPrecio").value;
    objDetalle.descripcionpaquete = document.getElementById("txtdescripcion").value;
    console.log(objDetalle.descripcionpaquete);
    let agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
    let agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");

    if (isEmpty(objDetalle.precio) || !esNumero(objDetalle.precio) || (objDetalle.precio <= 0 && agenciaOrigenId != agenciaDestinoId)) {
        mostrarValidacionLoaderClose("Debe ingresar: precio válida");
        correcto = false;
    }

    objDetalle.cantidad = document.getElementById("txtCantidad").value;
    if (isEmpty(objDetalle.cantidad) || !esNumero(objDetalle.cantidad) || objDetalle.cantidad <= 0) {
        mostrarValidacionLoaderClose("Debe ingresar: cantidad válida");
        correcto = false;
    }
    objDetalle.precioTipoId = 1;
    objDetalle.precioTipoDesc = "Venta";
    objDetalle.stockBien = 0;
    objDetalle.bienId = select2.obtenerValor("cboBien");
    if (isEmpty(objDetalle.bienId)) {
        mostrarValidacionLoaderClose("Debe seleccionar: bien válida");
        correcto = false;
    }
    var bien = obtenerBienPorBienId(objDetalle.bienId);
    objDetalle.bienDesc = select2.obtenerText("cboBien");
    objDetalle.unidadMedidaId = -1;
    objDetalle.unidadMedidaDesc = "unidad(es)";


    objDetalle.organizadorId = null;
    objDetalle.organizadorDesc = null;

    let bien_alto = document.getElementById("txtAltura").value;
    if ((isEmpty(bien_alto) || !esNumero(bien_alto)) && tipoDetalleEditar == 0) {
        mostrarValidacionLoaderClose("Debe ingresar: alto del paquete valido");
        correcto = false;
    }
    objDetalle.bien_alto = (!isEmpty(bien_alto) ? bien_alto : null);

    let bien_ancho = document.getElementById("txtAncho").value;
    if ((isEmpty(bien_ancho) || !esNumero(bien_ancho)) && tipoDetalleEditar == 0) {
        mostrarValidacionLoaderClose("Debe ingresar: ancho del paquete valido");
        correcto = false;
    }
    objDetalle.bien_ancho = (!isEmpty(bien_ancho) ? bien_ancho : null);

    let bien_longitud = document.getElementById("txtLongitud").value;
    if ((isEmpty(bien_longitud) || !esNumero(bien_longitud)) && tipoDetalleEditar == 0) {
        mostrarValidacionLoaderClose("Debe ingresar: longitud del paquete valido");
        correcto = false;
    }
    objDetalle.bien_longitud = (!isEmpty(bien_longitud) ? bien_longitud : null);

    let bandera_tipo_peso = $('input[name="rtnButtonPeso"]:checked').val();
    objDetalle.bandera_tipo_peso = bandera_tipo_peso;

    let bien_peso = document.getElementById("txtPeso").value;
    if ((isEmpty(bien_peso) || !esNumero(bien_peso) || bien_peso <= 0) && bandera_tipo_peso == 0 && tipoDetalleEditar == 0) {
        mostrarValidacionLoaderClose("Debe ingresar: peso del paquete valido");
        correcto = false;
    }

    let bien_peso_total = document.getElementById("txtPesoTotal").value;
    if ((isEmpty(bien_peso_total) || !esNumero(bien_peso_total) || bien_peso_total <= 0) && bandera_tipo_peso == 1 && tipoDetalleEditar == 0) {
        mostrarValidacionLoaderClose("Debe ingresar: el peso total");
        correcto = false;
    }

    objDetalle.bien_peso = (!isEmpty(bien_peso) ? bien_peso : null);
    objDetalle.bien_peso_total = (!isEmpty(bien_peso_total) ? bien_peso_total : null);

    let bien_peso_volumetrico = document.getElementById("txtPesoVolumetrico").value;
    objDetalle.bien_peso_volumetrico = (!isEmpty(bien_peso_volumetrico) ? bien_peso_volumetrico : null);

    let bien_factor_volumetrico = document.getElementById("txtFactorVolumetrico").value;
    objDetalle.bien_factor_volumetrico = (!isEmpty(bien_factor_volumetrico) ? bien_factor_volumetrico : null);

    if (!correcto) {
        return correcto;
    }

    //fin columna dinamica        
    var stockBien = 0;

    objDetalle.stockBien = stockBien;
    objDetalle.bienTipoId = (!isEmpty(bien) ? bien[0]['bien_tipo_id'] : 0);
    objDetalle.bienTramoId = null;
    objDetalle.detalle = detDetalle;
    objDetalle.tipo = tipoDetalleEditar;
    return objDetalle;
}

function limpiarFilaDetalleFormulario(indice) {
    //columnas dinamicas de acuerdo a documento_tipo_columna
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            switch (parseInt(item.tipo)) {
                case 1://Precio de compra
                    $("#txtPrecioCompra_" + indice).html("");
                    break;
                case 2:// Utilidad moneda                    
                    $("#txtUtilidadSoles_" + indice).html("");
                    break;
                case 3:// Utilidad porcentaje                    
                    $("#txtUtilidadPorcentaje_" + indice).html("");
                    break;
                case 4:// Tipo de precio
                    $("#cboPrecioTipo_" + indice).select2({ width: anchoTipoPrecioTD + 'px' });
                    break;
                case 5://Precio unitario
                    $("#txtPrecio" + indice).html("");
                    break;
                case 6://Sub total detalle
                    $("#txtSubTotalDetalle_" + indice).html("");
                    break;
                case 7://Stock
                    $("#txtStock_" + indice).html("");
                    break;
                case 11:
                    select2.asignarValor('cboBien_' + indice, '');
                    $("#cboBien_" + indice).select2({ width: alturaBienTD + 'px' });
                    break;
                case 12:
                    document.getElementById("txtCantidad_" + indice).value = '1';
                    break;
                case 13:
                    select2.asignarValor('cboUnidadMedida_' + indice, '');
                    $("#cboUnidadMedida_" + indice).select2({ width: anchoUnidadMedidaTD + 'px' });
                    break;
            }
        });
    }
}


function agregarSubTotalDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"text\" id=\"txtSubTotalDetalle_" + i + "\" name=\"txtSubTotalDetalle_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"\" style=\"text-align: right;\" disabled/></div>";

    return $html;
}

function agregarUnidadMedidaDetalleTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboUnidadMedida_" + i + "\" id=\"cboUnidadMedida_" + i + "\" class=\"select2\" onchange=\"\">" +
        "</select></div>";

    return $html;
}

function agregarPrecioTipoTabla(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboPrecioTipo_" + i + "\" id=\"cboPrecioTipo_" + i + "\" class=\"select2\" onchange=\"\">" +
        "</select></div>";

    return $html;
}

function agregarBienDetalleTabla(i) {

    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboBien_" + i + "\" id=\"cboBien_" + i + "\" class=\"select2\">" +
        "</select>" +
        "<span class='input-group-btn'>" +
        "<button type='button' class='btn btn-effect-ripple btn-default' title='Actualizar producto'" +
        "style='padding-bottom: 7px;' onclick='actualizarComboProducto(" + i + ")'><i class='ion-refresh' style='color: #5CB85C;'></i></button>" +
        "</span>" +
        "</div>";

    return $html;
}

var personaDireccionId = 0;
var fechaEmisionId = 0;
var textoDireccionId = 0;
var cambioPersonalizadoId = 0;
var validarCambioFechaEmision = false;
function onResponseObtenerDocumentoTipoDato(data) {
    dataCofiguracionInicial.documento_tipo_conf = data;

    validarCambioFechaEmision = true;
    camposDinamicos = [];
    personaDireccionId = 0;
    var contador = 0;
    var mostrarCampo = true;

    var documentoTipo = dataCofiguracionInicial.documento_tipo.filter(item => item.id == $("#cboDocumentoTipo").val());
    if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 6) {
        mostrarCampo = false;
    }

    $("#formularioDocumentoTipo").empty();
    if (!isEmpty(data)) {
        var escribirItem;
        var contadorEspeciales = 0;
        $.each(data, function (index, item) {
            switch (parseInt(item.tipo)) {
                case 7:
                case 8:
                case 14:
                case 15:
                case 16:
                case 17:
                case 19:
                case 24:
                case 25:
                case 27:
                case 32:
                case 33:
                case 34:
                case 35:
                case 38:
                    contadorEspeciales += 1;
                    escribirItem = false;
                    break;
                default:
                    //Tipo venta, solo debe mostrarse para los movimientotipo de ventas gratuitas.
                    if (parseInt(item.tipo) == 4 && item.codigo == 12 && dataCofiguracionInicial.movimientoTipo[0]["codigo"] != 18) {
                        contadorEspeciales += 1;
                        escribirItem = false;
                        break;
                    }
                    if (contador % 3 == 0) {
                        appendForm('<div class="row">');
                    }
                    contador++;

                    var html = '<div class="form-group col-md-4">';
                    if (item.tipo != 31) {
                        if (item.codigo != 11) {
                            html += '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
                        }
                    }

                    //                    if (item.tipo == 5)
                    //                    {
                    //                        html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar proveedor"></i></a>';
                    //                    }
                    switch (parseInt(item.tipo)) {
                        case 1:
                        case 7:
                        case 8:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                        case 2:
                        case 6:
                        case 12:
                        case 13:
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                        case 24:
                        case 26:
                        case 33:
                        case 32:
                        case 34:
                        case 35:
                        case 36:
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 38:
                            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                            break;
                        case 5:
                            html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar ' + item.descripcion.toLowerCase() + '" style="color: #CB932A;"></i></a>' +
                                '<span class="divider"></span> <a onclick="actualizarCboPersona()"><i class="ion-refresh" tooltip-btndata-toggle="tooltip" title="Actualizar" style="color: #5CB85C;"></i></a>';
                        case 4:

                        //                        case 17:
                        case 18:
                            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">';
                            break;
                    }

                    escribirItem = true;
                    break;
            }
            camposDinamicos.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion,
                codigo: item.codigo
            });
            var readonly = (parseInt(item.editable) === 0) ? 'readonly="true"' : '';
            var longitudMaxima = item.longitud;
            if (isEmpty(longitudMaxima)) {
                longitudMaxima = 45;
            }

            var maxNumero = 'onkeyup="if(this.value.length>' + longitudMaxima + '){this.value=this.value.substring(0,' + longitudMaxima + ')}"';

            switch (parseInt(item.tipo)) {
                case 1:
                    html += '<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '" ' + maxNumero + ' style="text-align: right;" />';
                    break;

                case 7:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    $("#contenedorSerieDiv").show();
                    $("#contenedorSerie").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Serie"  style="text-align: right;"/>');
                    break;

                case 8:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    $("#contenedorNumeroDiv").show();
                    $("#contenedorNumero").html('<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '" placeholder="Número"  style="text-align: right;"/>');
                    break;

                case 33:
                    importes.seguroId = 'txt_' + item.id;
                    $("#contenedorSeguroDiv").show();
                    $("#contenedorSeguro").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + '  onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 32:
                    importes.fleteId = 'txt_' + item.id;
                    $("#contenedorFleteDiv").show();
                    $("#contenedorFlete").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;

                case 34:
                    importes.otrosId = 'txt_' + item.id;
                    $("#contenedorOtrosDiv").show();
                    $("#contenedorOtros").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + '  onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 35:
                    importes.exoneracionId = 'txt_' + item.id;
                    $("#contenedorExoneracionDiv").show();
                    $("#contenedorExoneracion").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 36:
                    cboDetraccionId = item.id;
                    html += '<div id ="div_detraccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(dataCofiguracionInicial.dataDetraccion)) {
                        $.each(dataCofiguracionInicial.dataDetraccion, function (indexDetraccion, itemDetraccion) {
                            html += '<option value="' + itemDetraccion.id + '">' + itemDetraccion.descripcion + '</option>';
                        });
                    }
                    html += '</select>';
                    html += '<span name="txt_' + item.id + '" id="txt_' + item.id + '" class="control-label" style="font-style: normal;" hidden=""></span>';

                    break;
                case 38:
                    importes.icbpId = 'txt_' + item.id;
                    $("#contenedorIcbpDiv").show();
                    $("#contenedorIcbp").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="' + devolverDosDecimales(item.numero_defecto) + '" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' onchange="asignarImporteDocumento()"  style="text-align: center;" />');
                    break;
                case 14:
                    importes.totalId = 'txt_' + item.id;
                    $("#contenedorTotalDiv").show();
                    $("#contenedorTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    break;
                case 15:
                    importes.igvId = 'txt_' + item.id;
                    $("#contenedorIgvDiv").show();
                    $("#txtDescripcionIGV").html(item.descripcion);
                    $("#contenedorIgv").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    $("#txtDescripcionIGV").css("font-weigh", "");
                    break;
                case 16:
                    importes.subTotalId = 'txt_' + item.id;

                    if (!isEmpty(documentoTipo) && documentoTipo[0]['identificador_negocio'] == 30) {
                        $("#chkIGV").prop("checked", false);
                        igvValor = 0;
                    } else {
                        // si existe un subtotal, Mostramos el checkbox de cálculo de precios con / sin igv
                        $("#contenedorChkIncluyeIGV").show();
                        //                    $("#chkIncluyeIGV").prop("checked", "checked");
                        $("#chkIncluyeIGV").prop("checked", "");
                        $("#chkIGV").prop("checked", true);
                        igvValor = 0.18;
                    }

                    $("#contenedorSubTotalDiv").show();
                    $("#contenedorSubTotal").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" />');
                    break;
                case 19:
                    percepcionId = item.id;
                    $("#contenedorPercepcionDiv").show();
                    $("#contenedorPercepcion").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: center;" onchange="calculeTotalMasPercepcion(' + item.id + ')"  disabled/>');
                    break;
                case 2:
                case 6:
                case 12:
                case 13:
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }

                    if (parseInt(item.numero_defecto) === 1) {
                        textoDireccionId = item.id;
                    }

                    if (parseInt(item.numero_defecto) === 2) {
                        value = dataCofiguracionInicial.dataEmpresa[0]['direccion'];
                    }

                    html += '<input type="text" id="txt_' + item.id + '" name="txt_' + item.id + '" class="form-control" value="' + value + '" maxlength="' + longitudMaxima + '"/>';
                    break;
                case 9:
                    fechaEmisionId = item.id;
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '"  onchange="obtenerTipoCambioDatepicker();">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 3:
                case 10:
                case 11:
                    if (item.codigo != '11') {
                        html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepicker_' + item.id + '">' +
                            '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    }
                    break;
                case 4:
                    /* let functionOnChangeTipo_4 = '';
                     switch (item.codigo * 1) {
                     case 12:
                     functionOnChangeTipo_4 = ' onchange = "onChangeCboTipoVenta();" ';
                     break;
                     case 14:
                     functionOnChangeTipo_4 = ' onchange = "onChangeCboMotivo();" ';
                     break;
                     }
                     html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2" ' + functionOnChangeTipo_4 + ' ></select>';
                     id_cboMotivoMov = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                     break;*/
                    html += '<select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2"></select>';
                    if (item.codigo == "10") {
                        html += '<span name="txt_' + item.id + '" id="txt_' + item.id + '" class="control-label" style="font-style: normal;" hidden=""></span>';
                    }
                    id_cboMotivoMov = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                    break;
                case 5:
                    html += '<div id ="div_persona" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(item.data)) {
                        $.each(item.data, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    }
                    html += '</select>';
                    //                    html += '<div class="form-group col-lg-4"><a   class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 17:
                    var htmlOrg = '';
                    htmlOrg += '<div id ="div_organizador_destino" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2" placeholder="Seleccione almacén de llegada" onchange="onChangeOrganizadorDestino()">';

                    id_cboDestino = (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) ? item.id : null;
                    htmlOrg += '<option></option>';
                    $.each(item.data, function (indexOrganizador, itemOrganizador) {
                        htmlOrg += '<option value="' + itemOrganizador.id + '">' + itemOrganizador.descripcion + '</option>';
                    });
                    htmlOrg += '</select>';

                    $("#h4OrganizadorDestino").append(htmlOrg);
                    $("#divContenedorOrganizadorDestino").show();
                    break;
                case 18:
                    personaDireccionId = item.id;
                    html += '<div id ="div_direccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    //html += '<option value="' + 0 + '">Seleccione la dirección</option>';
                    html += '</select>';
                    //                    html += '<div class="form-group col-lg-4"><a   class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 20:
                    html += '<div id ="div_cuenta" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                    });
                    html += '</select>';
                    break;
                case 21:
                    html += '<div id ="div_actividad" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la actividad</option>';
                    $.each(item.data, function (indexActividad, itemActividad) {
                        html += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
                case 22:
                    html += '<div id ="div_retencion_detraccion" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione retención o detracción</option>';
                    $.each(item.data, function (indexRetencionDetraccion, itemRetencionDetraccion) {
                        html += '<option value="' + itemRetencionDetraccion.id + '">' + itemRetencionDetraccion.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
                case 23:
                    html += '<div id ="div_persona_' + item.id + '" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    if (dataCofiguracionInicial.documento_tipo[0]["identificador_negocio"] == 1) {
                        html += '<option value="' + 0 + '">Seleccione a quién va dirigido</option>';
                    } else {
                        html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    }
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
                    //                    html += '<div class="form-group col-lg-4"><a   class="btn btn-info w-sm m-b-5" id="id" style="border-radius: 0px;" onclick="cargarPersona();" ><i class="ion-plus"></i></div></div>';
                    break;
                case 24:
                    cambioPersonalizadoId = item.id;
                    //                    $("#contenedorCambioPersonalizado").show();
                    $("#cambioPersonalizado").html('<input type="number" id="txt_' + item.id + '" name="txt_' + item.id + '" ' + readonly + ' class="form-control" value="" maxlength="' + longitudMaxima + '"  ' + maxNumero + ' style="text-align: right;" placeholder="' + item.descripcion + '"/>');
                    break;
                case 25:
                    $("#divContenedorTipoPago").show();
                    break;
                case 26:
                    html += '<div id ="div_vendedor" ><select name="cbo_' + item.id + '" id="cbo_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione ' + item.descripcion.toLowerCase() + '</option>';
                    if (!isEmpty(item.data)) {
                        $.each(item.data, function (indexPersona, itemPersona) {
                            html += '<option value="' + itemPersona.id + '">' + itemPersona.persona_nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                        });
                    }
                    html += '</select>';
                    break;
                case 27:
                    $("#divContenedorAdjunto").show();
                    iniciarArchivoAdjunto();
                    break;
                case 31:
                    if (mostrarCampo) { //DT Guia de remision BH
                        html += '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
                        html += '<div id ="divContenedorAdjuntoMultiple">';
                        html += '<a class="btn btn-primary btn-sm m-b-5" onclick="adjuntar();"><i class="fa fa-cloud-upload"></i> Adjuntar archivos</a>';
                        iniciarArchivoAdjuntoMultiple();
                    }

                    break;
            }
            if (escribirItem) {
                html += '</div></div>';
                appendForm(html);
                if (contador % 3 == 0) {
                    appendForm('</div>');
                }
            }
            switch (parseInt(item.tipo)) {
                case 3:
                //                case 9:
                case 10:
                case 11:
                    $('#datepicker_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    break;
                case 9:
                    $('#datepicker_' + item.id).datepicker({
                        isRTL: false,
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        language: 'es'
                    }).on('changeDate', function (ev) {
                        onChangeFechaEmision();
                        cambiarPeriodo();
                    });
                    $('#datepicker_' + item.id).datepicker('setDate', item.data);
                    cambiarPeriodo();
                    fechaEmisionAnterior = item.data;
                    break;
                case 4:
                    select2.cargar("cbo_" + item.id, item.data, "id", "descripcion");
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        if (item.codigo == "10") {
                            obtenerMontoRetencion();
                        }
                    });

                    if (!isEmpty(item.lista_defecto)) {
                        var id = parseInt(item.lista_defecto);
                        select2.asignarValor("cbo_" + item.id, id);
                    }

                    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) {
                        select2.asignarValor("cbo_" + item.id, null);
                    }

                    break;
                case 5:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        obtenerPersonaDireccion(e.val);
                    });
                    break;
                case 17:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 18:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 20:
                case 21:
                case 26:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.numero_defecto)) {
                        var id = parseInt(item.numero_defecto);
                        select2.asignarValor("cbo_" + item.id, id);
                    }

                    if (item.editable == 0) {
                        $("#cbo_" + item.id).attr('disabled', 'disabled');
                    }
                    break;
                case 22:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 23:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {

                    });
                    break;
                case 36:
                    $("#cbo_" + item.id).select2({
                        width: '100%'
                    }).on("change", function (e) {
                        obtenerMontoDetraccion(e.val, item.id);
                    });
                    break;
            }
        });

        modificarDetallePrecios();


        validarImporteLlenar();
        asignarImporteDocumento();
    }
}

var percepcionId = 0;
function calculeTotalMasPercepcion(id) {

    if (calculoTotal <= 0) {
        mostrarValidacionLoaderClose("Total debe ser mayor a cero.");
        return false;
    }

    var percepcion = parseFloat($('#txt_' + id).val());

    if (isEmpty(percepcion) || percepcion < 0) {
        mostrarValidacionLoaderClose("Debe ingresar una percepción válida");
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var percepcionMaxima = 0.02 * calculoTotal + 1;

    if (percepcion > percepcionMaxima) {
        mostrarValidacionLoaderClose("Percepción no puede ser mayor a: " + devolverDosDecimales(percepcionMaxima));
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + id).val('');
        return false;
    }

    var suma = percepcion + calculoTotal;
    $('#' + importes.totalId).val(devolverDosDecimales(suma));

}

function onChangeCheckPercepcion() {
    if (document.getElementById('chkPercepcion').checked) {
        $('#txt_' + percepcionId).removeAttr('disabled');
    } else {
        $('#txt_' + percepcionId).val('');
        $('#' + importes.totalId).val(devolverDosDecimales(calculoTotal));
        $('#txt_' + percepcionId).attr('disabled', 'disabled');
    }
}


function cargarTelefonoXTipo(tipo) {
    let telefono = select2.obtenerValorDataSet("cbo" + tipo, "telefono");
    let celular = select2.obtenerValorDataSet("cbo" + tipo, "celular");
    let html = '';
    if (!isEmpty(telefono)) {
        html = html + `<span class="divider"></span> <i class="fa fa-phone" tooltip-btndata-toggle="tooltip" title="Telefono obligatorio" ></i>&nbsp;&nbsp;` + telefono;
    }

    if (!isEmpty(celular)) {
        html = html + `&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="divider"></span> <i  class="ion-iphone" tooltip-btndata-toggle="tooltip" title="Telefono opcional"></i>&nbsp;&nbsp;` + celular;
    }

    $("#lblTelefono" + tipo).html(html);
}

function obtenerPersonaDireccion(tipo, personaId, direccionId = null, direccionFacturacionId = null) {

    cargarTelefonoXTipo(tipo);

    select2.asignarValor("cbo" + tipo + "Direccion", "");
    select2.asignarValor("cbo" + tipo + "DireccionFacturacion", "");

    ax.setAccion("obtenerPersonaDireccion");
    ax.addParamTmp("personaId", personaId);
    ax.setTag({ tipo: tipo, direccionId: direccionId, direccionFacturacionId: direccionFacturacionId });
    ax.consumir();
    //    } 
}

function appendForm(html) {
    $("#formularioDocumentoTipo").append(html);
}

function deshabilitarBoton() {
    $("#env").addClass('disabled');
    $("#env i").removeClass(boton.enviarClase);
    $("#env i").addClass('fa fa-spinner fa-spin');

    $("#btnEnviar").addClass('disabled');
    $("#btnEnviar i").removeClass($('#btnEnviar i').attr('class'));
    $("#btnEnviar i").addClass('fa fa-spinner fa-spin');

    $("#btnCerrarModalPago").addClass('disabled');
    $("#btnCerrarModalPago i").removeClass($('#btnCerrarModalPago i').attr('class'));
    $("#btnCerrarModalPago i").addClass('fa fa-spinner fa-spin');

}

function habilitarBoton() {
    $("#env").removeClass('disabled');
    $("#env i").removeClass('fa-spinner fa-spin');
    $("#env i").addClass(boton.enviarClase);



    $("#btnEnviar").removeClass('disabled');
    $("#btnEnviar i").removeClass('fa-spinner fa-spin');
    $("#btnEnviar i").addClass($('#btnEnviar i').attr('class'));


    $("#btnCerrarModalPago").removeClass('disabled');
    $("#btnCerrarModalPago i").removeClass('fa-spinner fa-spin');
    $("#btnCerrarModalPago i").addClass($('#btnCerrarModalPago i').attr('class'));
}

var detalle = [];
var detalleDos = [];
var indexDetalle = 0;
function mostrarValidacionLoaderClose(mensaje) {
    mostrarAdvertencia(mensaje);
    loaderClose();
}

function confirmacionRedirecciona() {
    $('#cargarBuscadorDocumentoACopiar').removeAttr("onclick");
    if (valoresFormularioDetalle.accion === "agregar") {
        agregarConfirmado();
    } else if (valoresFormularioDetalle.accion === "editar") {
        editarConfirmado();
    }
    // asigno el importe
    asignarImporteDocumento();
    loaderClose();
}
var valoresFormularioDetalle;
var validarOrganizador = false;
function agregar(bandera_cierre) {
    indexDetalleEditar = null;
    valoresFormularioDetalle = validarFormularioDetalleTablas();
    if (!valoresFormularioDetalle)
        return;


    var existeDetalle = false;
    // validamos si existe un registro similar
    $.each(detalle, function (i, item) {
        if (parseInt(item.tipo) == 1 &&
            parseInt(item.bienId) === parseInt(valoresFormularioDetalle.bienId) &&
            parseInt(item.unidadMedidaId) === parseInt(valoresFormularioDetalle.unidadMedidaId)) {
            confirmarEdicion(item.index);
            existeDetalle = true;
            return false;
        }
    });
    if (existeDetalle)
        return;

    valoresFormularioDetalle.accion = "agregar";

    confirmarControlador();

    if (bandera_cierre == 1) {
        $('#modalAgregarDetalle').modal('hide').data('bs.modal', null);
    }
    loaderClose();
}

function confirmarControlador() {
    // obtenerStockAControlar
    if (validarOrganizador && bienTipoFabricacion != 1 && !banderaEsPromocion) {
        ax.setAccion("obtenerStockAControlar");
        ax.addParamTmp("organizadorId", valoresFormularioDetalle.organizadorId);
        ax.addParamTmp("unidadMedidaId", valoresFormularioDetalle.unidadMedidaId);
        ax.addParamTmp("bienId", valoresFormularioDetalle.bienId);
        ax.addParamTmp("cantidad", valoresFormularioDetalle.cantidad);
        ax.consumir();
    } else {
        confirmacionRedirecciona();
    }
}
function confirmacionRedirecciona() {
    $('#cargarBuscadorDocumentoACopiar').removeAttr("onclick");
    if (valoresFormularioDetalle.accion === "agregar") {
        agregarConfirmado();
    } else if (valoresFormularioDetalle.accion === "editar") {
        editarConfirmado();
    }
    // asigno el importe
    asignarImporteDocumento();
    loaderClose();
}
function agregarConfirmado() {
    var subTotal = devolverDosDecimales(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);

    valoresFormularioDetalle.subTotal = subTotal;

    valoresFormularioDetalle.index = indexDetalle;
    detalle.push(valoresFormularioDetalle);

    indexDetalle += 1;

    if (valoresFormularioDetalle.organizadorDesc == null) {
        valoresFormularioDetalle.organizadorDesc = '';
    }
    limpiarFormularioDetalle();

    dibujarTabla();

    loaderClose();
}

function editarConfirmado() {
    var indexTemporal = null;
    $.each(detalle, function (i, item) {
        if (parseInt(item.index) === parseInt(indexDetalleEditar)) {
            indexTemporal = i;
            return false;
        }
    });
    if (isEmpty(indexTemporal)) {
        indexDetalleEditar = null;
        mostrarValidacionLoaderClose("No se ha encontrado data para editar");
        return;
    }
    var subTotal = devolverDosDecimales(valoresFormularioDetalle.cantidad * valoresFormularioDetalle.precio);
    let movimientoBienId = detalle[indexTemporal]['movimientoBienId'];

    valoresFormularioDetalle.subTotal = subTotal;
    valoresFormularioDetalle.index = indexDetalleEditar;
    valoresFormularioDetalle.movimientoBienId = movimientoBienId;

    detalle[indexTemporal] = valoresFormularioDetalle;

    dibujarTabla();
    indexDetalleEditar = null;
    limpiarFormularioDetalle();
    loaderClose();
}

function confirmarEdicion(index) {
    loaderClose();
    swal({
        title: "¿Está seguro que desea editar?",
        text: "Ya existe un articulo similar al que desea agregar",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, editar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            indexDetalleEditar = index;
            editar();
        }
    });
}

var numeroItemFinal = 0;
function dibujarTabla() {
    $('#datatable tbody').empty();
    if (!isEmpty(detalle) && detalle.length > 0) {
        numeroItemFinal = 0;
        $.each(detalle, function (indexFila, item) {
            $('#datatable tbody').append('<tr><td style="text-align:center;"><a title="Editar" onclick="prepararEdicion(' + item.index + ')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></b></b></a><b><b>&nbsp' +
                '<a title="Eliminar" onclick="confirmarEliminar(' + item.index + ')"><b><i class="fa fa-trash-o" style="color:#cb2a2a;"></i><b></b></b></a></b></b></td>' +
                "<td id='colCantidad_" + item.index + "' style='text-align:right;'>" + formatearNumero(item.cantidad) + "</td>" +
                "<td id='colBien_" + item.index + "' style='text-align:left;'>" + item.bienDesc
                + (!isEmpty(item.bien_alto) && item.bien_alto > 0 ? ' ' + formatearNumero(item.bien_alto) + ' x ' + formatearNumero(item.bien_ancho) + ' x ' + formatearNumero(item.bien_longitud) : '')
                + (!isEmpty(item.bien_peso) && item.bien_peso > 0 ? ' ( ' + formatearNumero(item.bien_peso) + ' Kg)' : '')
                + "</td> " +
                "<td id='colPrecio_" + item.index + "' style='text-align:right;'>" + formatearNumero(item.precio) + "</td> " +
                "<td id='colSubTotal_" + item.index + "' style='text-align:right;'>" + item.subTotal + "</td> " +
                "</tr>");
        });
    } else {
        reinicializarDataTableDetalle();
    }
}

var banderaCopiaDocumento = 0;
var unidadMedidaTxt = 0;
function asignarValoresDetalleFormulario() {

    banderaCopiaDocumento = 1;
    //COLUMNAS DINAMICAS

    //obtener los datos del detalle dinamico
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;
    var valor;

    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            unidadMedidaTxt = 0;
            valor = null;

            //obtener los datos del detalle
            switch (parseInt(item.codigo)) {
                //numeros
                case 5:// PRECIO UNITARIO             
                    $('#txtPrecio_' + indexDetalle).val(devolverDosDecimales(valoresFormularioDetalle.precio));
                    break;
                case 6:// SUB TOTAL
                    $('#txtSubTotalDetalle_' + indexDetalle).html(devolverDosDecimales(valoresFormularioDetalle.subTotal));
                    break;
                case 9:// PRIORIDAD
                    if (dataCofiguracionInicial.movimientoTipo[0].interfaz == 1) {
                        $('#txtPrioridad_' + indexDetalle).html(valoresFormularioDetalle.prioridad);
                    }
                case 12:// CANTIDAD
                    $('#txtCantidad_' + indexDetalle).val(devolverDosDecimales(valoresFormularioDetalle.cantidad));
                    break;

                //combos, seleccion
                case 4:// TIPO PRECIO
                    select2.asignarValor("cboPrecioTipo_" + indexDetalle, valoresFormularioDetalle.precioTipoId);
                    $("#cboPrecioTipo_" + indexDetalle).select2({ width: anchoTipoPrecioTD + 'px' });
                    break;
                case 11:// PRODUCTO                    
                    select2.asignarValor("cboBien_" + indexDetalle, valoresFormularioDetalle.bienId);
                    $("#cboBien_" + indexDetalle).select2({ width: alturaBienTD + 'px' });
                    setearDescripcionProducto(indexDetalle);
                    break;
                //                case 13:// UNIDAD DE MEDIDA                    
                //                    obtenerUnidadMedida(valoresFormularioDetalle.bienId, indexDetalle);
                //                    break;
                case 15:// Organizador
                    select2.asignarValor("cboOrganizador_" + indexDetalle, valoresFormularioDetalle.organizadorId);
                    break;

                case 21:// COMENTARIO
                    $('#txtComentarioDetalle_' + indexDetalle).removeAttr("readonly");
                    $('#txtComentarioDetalle_' + indexDetalle).val(valoresFormularioDetalle.comentarioDetalle);
                    break;


                default:
                    //DATOS DE MOVIMIENTO_BIEN_DETALLE
                    if (!isEmpty(valoresFormularioDetalle.detalle)) {
                        $.each(valoresFormularioDetalle.detalle, function (indexBD, itemBD) {
                            if (parseInt(itemBD.columnaCodigo) === parseInt(item.codigo)) {
                                valor = itemBD.valorDet;
                                switch (itemBD.columnaCodigo) {
                                    //texto
                                    case 16://descripcion de producto      
                                        $('#txtProductoDescripcion_' + indexDetalle).removeAttr("readonly");
                                        $('#txtProductoDescripcion_' + indexDetalle).val(valor);
                                        break;
                                    case 17://descripcion de unidad de medida
                                        $('#txtUnidadMedidaDescripcion_' + indexDetalle).removeAttr("readonly");
                                        $('#txtUnidadMedidaDescripcion_' + indexDetalle).val(valor);
                                        if (isEmpty(valor)) {
                                            unidadMedidaTxt = 1;
                                        }
                                        break;

                                    //fechas
                                    case 18://fecha vencimiento
                                        if (!isEmpty(valor)) {
                                            valor = formatearFechaBDCadena(valor);
                                            $('#txtFechaVencimiento_' + indexDetalle).val(valor);
                                        }
                                        break;
                                }
                            }
                        });
                    } else {
                        unidadMedidaTxt = 1;
                    }
                    break;
            }
        });

        //CONSIDERANDO QUE SIEMPRE HAY UNIDAD DE MEDIDA
        //DESPUES QUE SE DIBUJO TODO LLAMAMOS A LA FUNCION onResponseObtenerUnidadesMedida , ANTES ERA CON METODO LLAMADO AL AJAX
        onResponseObtenerUnidadesMedida(valoresFormularioDetalle.dataUnidadMedida, indexDetalle);
    } else {
        mostrarValidacionLoaderClose("Falta configurar las columnas del detalle");
        return false;
    }
    //FIN COLUMNAS DINAMICAS    
}

var nroFilasEliminados = 0;
function eliminarDetalleFormularioTabla(indice) {
    //$('#cboUnidadMedida_'+indice).attr('disabled', "true");
    var numItemActual = $('#txtNumItem_' + indice).html();

    $('#trDetalle_' + indice).remove();

    //LLENAR TABLA DETALLE
    var fila = llenarFilaDetalleTabla(nroFilasReducida);

    $('#datatable tbody').append(fila);

    //LLENAR COMBOS
    cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, nroFilasReducida);
    cargarUnidadMedidadDetalleCombo(nroFilasReducida);
    cargarBienDetalleCombo(dataCofiguracionInicial.bien, nroFilasReducida);
    cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, nroFilasReducida);
    inicializarFechaVencimiento(nroFilasReducida);

    nroFilasInicial++;
    nroFilasReducida++;
    nroFilasEliminados++;

    $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * (nroFilasReducida - nroFilasEliminados));

    reenumerarFilasDetalle(indice, numItemActual);

}

function eliminar(index) {

    loaderShow();

    var indexTemporal = -1;

    if (!isEmpty(detalle)) {
        indexTemporal = detalle.findIndex(item => item.index == index);
        if (indexTemporal > -1) {
            if (!isEmpty(detalle[indexTemporal].movimientoBienId)) {
                listaDetalleEliminado.push(detalle[indexTemporal].movimientoBienId);
            }
            detalle.splice(indexTemporal, 1);
            dibujarTabla();

            asignarImporteDocumento();
        }
    }
    loaderClose();
}

var listaDetalleEliminado = [];
function confirmarEliminar(index) {
    swal({
        title: "¿Está seguro que desea eliminar?",
        text: "Una vez eliminado tendrá que seleccionar nuevamente todo el registro si desea volver agreagarlo",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminar(index);
        }
    });
}

function cargarPantallaListar(documentoId, tipoRetorno, documentoGenerarGuiaId) {
    //    cargarDivIndex("#window", "vistas/com/movimiento/movimiento_listar_static.php?tipoInterfaz=" + tipoInterfaz);
    let url = 'vistas/com/movimiento/movimiento_listar_static.php?tipoInterfaz=' + tipoInterfaz;
    if (!isEmpty(documentoId)) {
        url += '&documentoGenerarId=' + documentoId;
    }
    if (!isEmpty(tipoRetorno)) {
        url += '&tipoGenerarDocumento=' + tipoRetorno;
    }

    //if (!isEmpty(documentoGenerarGuiaId)) {
    //    url += '&documentoGenerarGuiaId=' + documentoGenerarGuiaId;
    //}

    cargarDivIndex('#window', URL_BASE + url, '227', 'Pedidos', 1);
}
function enviar(accion) {
    //VALIDO QUE EL PERIODO ESTE SELECCIONADO

    var periodoId = select2.obtenerValor('cboPeriodo');
    if (isEmpty(periodoId)) {
        mostrarAdvertencia('Seleccione un periodo');
        return;
    }

    //VALIDO QUE LA FECHA DE EMISION ESTE EN EL PERIODO SELECCIONADO
    var periodoFechaEm = obtenerPeriodoIdXFechaEmision();
    if (periodoId != periodoFechaEm) {
        swal({
            title: "¿Desea continuar?",
            text: "La fecha de emisión no está en el periodo seleccionado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                guardar(accion);
            }
        });
        return;
    }

    boton.accion = accion;

    let itemPrecioCero = detalle.filter(itemFilter => itemFilter.precio == 0 || isEmpty(itemFilter.precio));

    if (!isEmpty(itemPrecioCero)) {
        swal({
            title: "¿Desea continuar?",
            text: "Existe precios con valor cero en el detalle del documento.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                guardar(accion);
            }
        });

        return;
    }
    guardar(accion);
}

function obtenerUnidadMedidaTipoBien(bienId) {
    var unidadMedidaTipo = 0;

    $.each(dataCofiguracionInicial.bien, function (index, item) {
        if (item.id == bienId) {
            unidadMedidaTipo = item.unida_medida_tipo_descripcion;
            return false;
        }
    });
    return unidadMedidaTipo;
}

function obtenerValoresCamposDinamicos() {
    var isOk = true;
    if (isEmpty(camposDinamicos))
        return false;
    $.each(camposDinamicos, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 14:
            case 15:
            case 16:
            case 19:
            case 24:
            case 32:
            case 33:
            case 34:
            case 35:
            case 38:
                var numero = document.getElementById("txt_" + item.id).value;
                if (isEmpty(numero)) {
                    if (item.opcional == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                } else {
                    if (!esNumero(numero)) {
                        mostrarValidacionLoaderClose("Debe ingresar un número válido para " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                camposDinamicos[index]["valor"] = numero;
                break;
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
                camposDinamicos[index]["valor"] = document.getElementById("txt_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                if (item.codigo != '11') {
                    camposDinamicos[index]["valor"] = document.getElementById("datepicker_" + item.id).value;
                    if (item.opcional == 0) {
                        //validamos
                        if (isEmpty(camposDinamicos[index]["valor"])) {
                            mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                            isOk = false;
                            return false;
                        }
                    }
                } else {
                    camposDinamicos[index]["valor"] = null;
                }
                break;
            case 4:
            case 5:// persona
            case 18:// direccion persona
            case 20:// cuenta
            case 21:// actividad
            case 22:// retencion detraccion
            case 23:// otra persona
            case 26:// vendedor
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                let campoOpcional = (deshabilitarProveedor() && item.tipo == 5 ? 1 : item.opcional);
                if (campoOpcional == 0) {
                    deshabilitarProveedor()
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                        camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 17:// organizador
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                        camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 27:// ADJUNTO
                var objArchivo = { nombre: $('#nombreArchivo').html(), data: $('#dataArchivo').val() };
                camposDinamicos[index]["valor"] = objArchivo;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty($('#dataArchivo').val())) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 31:// ADJUNTO MULTIPLE
                var objArchivo = lstDocumentoArchivos;
                camposDinamicos[index]["valor"] = objArchivo;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty($('#dataArchivoMulti').val())) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;

        }
    });
    return isOk;
}

function enviarEImprimir() {
    if (documentoTipoTipo == 1) {

        var unidadMedidaTipo;
        var bandera = true;
        $.each(detalle, function (i, item) {
            unidadMedidaTipo = obtenerUnidadMedidaTipoBien(item.bienId);

            if (unidadMedidaTipo.indexOf("Longitud") > -1 && bandera == true) {
                bandera = false;
                swal({
                    title: "¿Desea continuar sin registrar tramos?",
                    text: "Existe 1 o más detalles que tienen como tipo de unidad de medida Longitud y no se han registrado tramos.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#33b86c",
                    confirmButtonText: "Si!",
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        guardar("enviarEImprimir");
                    }
                });
            }

        });

        if (bandera == true) {
            guardar("enviarEImprimir");
        }

    } else {
        guardar("enviarEImprimir");
    }
}

function obtenerMontoNotaDebito() {
    let monto = 0;


    if (!isEmpty(dataCofiguracionInicial.dataDocumento)) {
        if (dataCofiguracionInicial.dataDocumento[0].bandera_registro_movil == 2) {
            let montoTotal = dataCofiguracionInicial.dataDocumento[0].total * 1;
            if (montoTotal > 0) {
                let montoTotalDocumento = $("#" + importes.totalId).val() * 1;
                if (montoTotalDocumento > montoTotal) {
                    monto = redondearNumeroXDecimales(montoTotalDocumento - montoTotal, 6);
                }
            }
        }
    }
    return monto;
}


function obtenerMontoAPagar() {
    let montoTotalDocumento = $("#" + importes.totalId).val() * 1;
    if (!isEmpty(dataCofiguracionInicial.dataDocumento)) {
        if (dataCofiguracionInicial.dataDocumento[0].bandera_registro_movil == 2) {
            let montoTotal = dataCofiguracionInicial.dataDocumento[0].total * 1;
            if (montoTotal > 0) {
                let montoTotalDocumento = $("#" + importes.totalId).val() * 1;
                if (montoTotalDocumento > montoTotal) {
                    return redondearNumeroXDecimales(montoTotalDocumento - montoTotal, 6);
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return montoTotalDocumento;
        }
    } else {
        return montoTotalDocumento;
    }
}

function guardar(accion) {
//  ::::::::::::DESARROLLADO POR JESUS::::::::::::::::
console.log("llegamos....");
console.log(datosArray.length);
var nrodocumento = document.getElementById("cboComprobanteTipo");
console.log(" n doc");
console.log(nrodocumento.value);
if (nrodocumento.value == "191" || nrodocumento.value == "7") {
  if (datosArray.length == 0) {
    swal("Error", "Ingresar numero de guias. ", "error");
    return;
  }
}
//  ::::::::::::DESARROLLADO POR JESUS::::::::::::::::


    //obtenemos el tipo de documento
    var documentoTipoId = dataCofiguracionInicial.movimientoTipo[0]['documento_tipo_defecto_id'];
    if (isEmpty(documentoTipoId)) {
        mostrarValidacionLoaderClose("Debe seleccionar el tipo de documento");
        return;
    }

    //obtenemos el agencia origen
    var agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
    if (isEmpty(agenciaOrigenId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la agencia origen");
        return;
    }

    //obtenemos el agencia destino
    var agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
    if (isEmpty(agenciaDestinoId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la agencia destino");
        return;
    }

    //    if (agenciaDestinoId == agenciaOrigenId) {
    //        mostrarValidacionLoaderClose("Las agencias seleccionadas deben ser diferentes.");
    //        return;
    //    }

    //obtenemos el tipo de pedido

    var tipoPedidoId = select2.obtenerValor("cboModalidad");
    if (isEmpty(tipoPedidoId)) {
        mostrarValidacionLoaderClose("Debe seleccionar el tipo de pedido");
        return;
    }

    //obtenemos el comprobante de tipo
    var comprobanteTipoId = select2.obtenerValor("cboComprobanteTipo");
    if (isEmpty(comprobanteTipoId)) {
        mostrarValidacionLoaderClose("Debe seleccionar el comprobante.");
        return;
    }

    //obtenemos el periodo
    var periodoId = select2.obtenerValor("cboPeriodo");
    if (isEmpty(periodoId)) {
        mostrarValidacionLoaderClose("Debe seleccionar el periodo.");
        return;
    }

    //obtenemos la moneda
    var monedaId = select2.obtenerValor("cboMoneda");
    if (isEmpty(monedaId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la moneda.");
        return;
    }

    //obtenemos la fecha de pedido
    var fechaEmision = $("#txtFechaEmision").val();
    if (isEmpty(fechaEmision)) {
        mostrarValidacionLoaderClose("Debe seleccionar la fecha de pedido.");
        return;
    }


    //obtenemos el cliente
    var clienteId = select2.obtenerValor("cboCliente");
    if (isEmpty(clienteId) || clienteId == -1) {
        mostrarValidacionLoaderClose("Debe seleccionar la cliente.");
        return;
    }
    var clienteDireccionId = select2.obtenerValor("cboClienteDireccion");
    var clienteDireccionFacturacionId = select2.obtenerValor("cboClienteDireccionFacturacion");
    if (isEmpty(clienteDireccionId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la dirección de recojo.");
        return;
    }

    //obtenemos el destinatario
    var destinatarioId = select2.obtenerValor("cboDestinatario");
    if (isEmpty(destinatarioId) || destinatarioId == -1) {
        mostrarValidacionLoaderClose("Debe seleccionar el destinatario.");
        return;
    }
    var destinatarioDireccionId = select2.obtenerValor("cboDestinatarioDireccion");
    var destinatarioDireccionFacturacionId = select2.obtenerValor("cboDestinatarioDireccionFacturacion");
    if (isEmpty(destinatarioDireccionId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la dirección de despacho.");
        return;
    }

    var telefonoDestinatario = select2.obtenerValorDataSet("cboDestinatario", "telefono");
    if (isEmpty(telefonoDestinatario)) {
        mostrarValidacionLoaderClose("El destinatario debe tener un telefono registrado.");
        return;
    }

    if (!validarDetalleFormularioLlenos()) {
        return;
    }

    //    if (validarDetalleRepetido()) {
    //        mostrarValidacionLoaderClose("Detalle repetido, seleccione otro bien, organizador o unidad de medida.");
    //        return;
    //    }

    var afectoAImpuesto = 0;
    if ($("#chkIGV").length > 0 && document.getElementById('chkIGV').checked) {
        afectoAImpuesto = 1;
    }

    var contacto = select2.obtenerIdMultiple("cboAutorizado");
    if (!isEmpty(contacto)) {
        if (contacto.length > 2) {
            mostrarValidacionLoaderClose("Puede seleccionar como máximo a 2 destinatarios opcionales.");
            return;
        }
        contacto = contacto.join();
    }

    //Cambios Cristopher
    var guiasRelacion = datosArray.map(el => el.numTipoDoc + "|" + el.serieinput + "-" + el.correlativoinput).join(",");
    
    /*var guiasRelacion = select2.obtenerIdMultiple("cboGuiaRelacion");
    if (!isEmpty(guiasRelacion)) {
        guiasRelacion = guiasRelacion.join();
    }*/
    var personaId = clienteId;
    var personaDireccionId = clienteDireccionFacturacionId;
    if (obtenerCodigoModalidad() == '0' || obtenerCodigoModalidad() == '3') {
        personaId = destinatarioId;
        personaDireccionId = destinatarioDireccionFacturacionId;
    }

    if (comprobanteTipoId == '7' && isEmpty(personaDireccionId)) {
        mostrarValidacionLoaderClose("Debe seleccionar la dirección de facturación.");
        return;
    }

    if (destinatarioDireccionId != '-1') {
        let agenciaDireccionDestinatarioId = select2.obtenerValorDataSet("cboDestinatarioDireccion", "agencia_id");
        if (agenciaDireccionDestinatarioId != agenciaDestinoId) {
            mostrarValidacionLoaderClose("La zona de la dirección de despacho seleccionada no pertenece a la agencia de destino.");
            return;
        }
    }

    if (clienteDireccionId != '-1') {
        let agenciaDireccionClienteId = select2.obtenerValorDataSet("cboClienteDireccion", "agencia_id");
        if (agenciaDireccionClienteId != agenciaOrigenId) {
            mostrarValidacionLoaderClose("La zona de la dirección de recojo seleccionada no pertenece a la agencia de origen.");
            return;
        }
    }


    let costoReparto = $("#" + importes.costoRepartoId).val();
    let clausula = $("#tipotipo").val();
    if (clausula != 1) {
        if ((isEmpty(costoReparto) || (costoReparto * 1) <= 0) && (obtenerCodigoModalidad() == '1' || obtenerCodigoModalidad() == '3')) {
            mostrarValidacionLoaderClose("El costo de reparto no puede ser cero, por favor verifique que la zona de la dirección de despacho tenga un tarifario asignado.");
            return;
        }
    }

    if ((obtenerCodigoModalidad() == '0' || obtenerCodigoModalidad() == '3') && comprobanteTipoId == 191) {
        mostrarValidacionLoaderClose("Seleccione otro comprobante de pago para la modalidad contra entrega.");
        return;
    }

	  
																										   
																											  
			   
	 

    let identificadorNegocioRelacion = select2.obtenerValorDataSet("cboComprobanteTipo", "identificador_negocio");}

    //Cambios Cristopher
    if (comprobanteTipoId == 7 || comprobanteTipoId == 191 || comprobanteTipoId == 6) {
        var serieGuiaTransportista = $("#txtGuiaSerie").val();
        if (isEmpty(serieGuiaTransportista)) {
            mostrarValidacionLoaderClose("Debe ingresar la serie de la guía.");
            return;
        }

        var correlativoGuiaTransportista = $("#txtGuiaCorrelativo").val();
        if (isEmpty(correlativoGuiaTransportista)) {
            mostrarValidacionLoaderClose("Debe ingresar la correlativo de la guía.");
            return;
        }
    }

    let documentoMontoTotal = $("#" + importes.totalId).val();
    if (isEmpty(documentoMontoTotal) || !esNumero(documentoMontoTotal) || documentoMontoTotal <= 0) {
        mostrarValidacionLoaderClose("El monto total del documento debe ser mayor a cero.");
        return;
    }

    let itemPrecioCero = detalle.filter(itemFilter => itemFilter.precio == 0 || isEmpty(itemFilter.precio));
    if (!isEmpty(itemPrecioCero) && agenciaOrigenId != agenciaDestinoId) {
        mostrarValidacionLoaderClose("Existen algunos items que el precio es cero, por favor verifique el tarifario.");
        return;
    }

    let personaFacturadaId = select2.obtenerValor("cboFacturado");
    let cboValidacionFacturacion = "cboCliente";
    if ($("#cboFacturado").is(":visible")) {
        if (isEmpty(personaFacturadaId)) {
            mostrarValidacionLoaderClose("Debe seleccionar la persona a facturar.");
            return;
        }
        personaId = personaFacturadaId;
        cboValidacionFacturacion = "cboFacturado";
    } else if (obtenerCodigoModalidad() == '0' || obtenerCodigoModalidad() == '3') {
        cboValidacionFacturacion = "cboDestinatario";
    }

    if (comprobanteTipoId == 7 && select2.obtenerValorDataSet(cboValidacionFacturacion, "persona_tipo_id") != 4) {
        mostrarValidacionLoaderClose("No se puede emitir facturas a una persona natural.");
        return;
    } 
    if ($.isEmptyObject(detalle) ) {
        mostrarValidacionLoaderClose("Ingrese al menos un articulo a su detalle de pedido.");
        return;
    }
    else if (comprobanteTipoId == 6 && select2.obtenerValorDataSet(cboValidacionFacturacion, "persona_tipo_id") != 2) {
        mostrarValidacionLoaderClose("No se puede emitir boletas a una persona juridica.");
        return;
    }

    let banderaMovilRegistro = 0;
    if (!isEmpty(dataCofiguracionInicial.dataDocumento)) {
        banderaMovilRegistro = dataCofiguracionInicial.dataDocumento[0]['bandera_registro_movil'];
    }


    let documento = {};
    documento.documentoId = document.getElementById("documentoId").value;
    documento.empresaId = commonVars.empresa;
    documento.documentoTipoId = documentoTipoId;
    documento.agenciaOrigenId = agenciaOrigenId;
    documento.agenciaDestinoId = agenciaDestinoId;
    documento.tipoPedidoId = tipoPedidoId;
    documento.comprobanteTipoId = comprobanteTipoId;
    documento.monedaId = monedaId;
    documento.fechaEmision = fechaEmision;
    documento.periodoId = periodoId;
    documento.afectoAImpuesto = afectoAImpuesto;

    documento.personaId = personaId; //Persona que se emite el comprobante
    documento.personaDireccionId = personaDireccionId;

    documento.clienteId = clienteId; //Persona origen
    documento.clienteDireccionId = clienteDireccionId;
    documento.clienteDireccionDescripcion = select2.obtenerText("cboClienteDireccion");

    documento.destinatarioId = destinatarioId;
    documento.destinatarioDireccionId = destinatarioDireccionId;
    documento.destinatarioDireccionDescripcion = select2.obtenerText("cboDestinatarioDireccion");

    documento.monto_otros_cargos_descripcion = $("#txtDescripcionOtroCargo").val();
    documento.monto_detraccion = $("#" + importes.detraccionId).val();
    documento.monto_otros_cargos = $("#" + importes.otrosCargoId).val();
    documento.monto_devolucion_cargo = $("#" + importes.devolucionCargoId).val();
    documento.monto_costo_reparto = $("#" + importes.costoRepartoId).val();
    documento.monto_subtotal = $("#" + importes.subTotalId).val();
    documento.monto_igv = $("#" + importes.igvId).val();
    documento.monto_total = $("#" + importes.totalId).val();
    documento.ajuste_precio = $("#" + importes.ajustePrecioId).val();
    documento.monto_recojo_domicilio = $("#" + importes.costoRecojoDomicilioId).val();
    documento.contacto = contacto;
    documento.guia_relacion = guiasRelacion;
    documento.documento_pago = documento_pago;

    documento.serie_guia = serieGuiaTransportista;
    documento.numero_guia = correlativoGuiaTransportista;
    documento.identificador_negocio_relacion = identificadorNegocioRelacion;
    documento.listaDetalleEliminado = listaDetalleEliminado;
    documento.montoNotaDebito = obtenerMontoNotaDebito();

    if (!isEmpty(dataCofiguracionInicial.dataDocumento) && !isEmpty(dataCofiguracionInicial.dataDocumento[0]['comprobante_id'])) {
        documento.comprobanteId = dataCofiguracionInicial.dataDocumento[0]['comprobante_id'];
    }


    if (accion == "confirmacion" || accion == "confirmacionPago") {
        let clave = $("#txtClave").val();
        let claveConfirmacion = $("#txtClaveConfirmacion").val();

        if (isEmpty(clave) || clave.length != 4) {
            mostrarValidacionLoaderClose("Debe ingresar la clave de 4 dígitos.");
            return;
        }

        if (isEmpty(claveConfirmacion) || claveConfirmacion.length != 4) {
            mostrarValidacionLoaderClose("Debe ingresar la confirmación de clave de 4 dígito.");
            return;
        }

        if (clave != claveConfirmacion) {
            mostrarValidacionLoaderClose("Las claves ingresadas no coinciden.");
            return;
        }
        documento.clave = clave;
    }


    let dataPago = {};
    if (accion == "guardarPago" || accion == "confirmacionPago") {

        //parte documento pago    
        //obtenemos el tipo de documento
        let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
        let actividadEfectivo = $('#cboActividadEfectivo').val();
        if (isEmpty(documentoTipoIdPago)) {
            mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
            return;
        }
        let montoAPagar = 0;
        let totalCamposDinamicos = obtenerTotalPagoTemporal();

        let montoTotalDocumento = obtenerMontoAPagar();

        if (!isEmpty(camposDinamicosPagoTemporal) && formatearNumero(totalCamposDinamicos) == formatearNumero(montoTotalDocumento)) {

        } else {

            let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
            let documentoTipoIdPagoDescripcion = select2.obtenerText("cboDocumentoTipoNuevoPagoConDocumento");
            //Validar y obtener valores de los campos dinamicos
            if (documentoTipoIdPago != 282) {
                if (!obtenerValoresCamposDinamicosPago()) {
                    return;
                }

                let montoTotal = camposDinamicosPago.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
                if (formatearNumero((totalCamposDinamicos * 1) + (montoTotal * 1)) != formatearNumero(montoTotalDocumento)) {
                    mostrarValidacionLoaderClose("El monto total a pagar es  " + formatearNumero(montoTotalDocumento));
                    return;
                }
                camposDinamicosPagoTemporal.push({
                    documento_tipo_id: documentoTipoIdPago,
                    documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
                    monto: montoTotal,
                    data: obtenerArraySinReferencia(camposDinamicosPago)
                });
            } else {
                if (!obtenerValoresCamposDinamicosPagoEfectivo()) {
                    return;
                }
                let montoTotal = camposDinamicosPagoEfectivo.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
                if (formatearNumero((totalCamposDinamicos * 1) + (montoTotal * 1)) != formatearNumero(montoTotalDocumento)) {
                    mostrarValidacionLoaderClose("El monto total a pagar es  " + formatearNumero(montoTotalDocumento));
                    return;
                }
                camposDinamicosPagoTemporal.push({
                    documento_tipo_id: documentoTipoIdPago,
                    documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
                    monto: montoTotal,
                    data: obtenerArraySinReferencia(camposDinamicosPagoEfectivo)
                });
            }
            cargarDetallePago();
        }

        dataPago.camposDinamicosPago = camposDinamicosPagoTemporal;
        dataPago.documentoTipoIdPago = documentoTipoIdPago;
        dataPago.cliente = personaId;
        dataPago.tipoCambio = $('#tipoCambio').val();
        dataPago.fecha = document.getElementById("txtFechaEmision").value;
        dataPago.actividadEfectivo = actividadEfectivo;
        dataPago.montoAPagar = montoAPagar;
        dataPago.totalPago = obtenerMontoAPagar();
        dataPago.totalDocumento = documento.monto_total;

        if (accion == "guardarPago" && banderaMovilRegistro == 0 && obtenerCodigoModalidad() != '1') {
            $('#modalNuevoDocumentoPagoConDocumento').modal('hide');
            document.getElementById("btnConfirmacionRegistro").onclick = function (event) {
                guardar('confirmacionPago');
            };
            $('#modalConfirmacionRegistro').modal('show');
            return;
        }

        //fin documento pago    
    } else if ((obtenerCodigoModalidad() == '1' || obtenerCodigoModalidad() == '2') && comprobanteTipoId != 191 && accion != 'confirmacion' && obtenerMontoAPagar() > 0) {
        abrirModalPagos(dataCofiguracionInicial);
        return;
    } else if (accion != 'confirmacion' && banderaMovilRegistro == 0 && (obtenerCodigoModalidad() == '0' || obtenerCodigoModalidad() == '2')) {

        document.getElementById("btnConfirmacionRegistro").onclick = function (event) {
            guardar('confirmacion');
        };

        $('#modalConfirmacionRegistro').modal('show');
        return;
    }

    $('#modalConfirmacionRegistro').modal('hide');
    if (accion == "confirmacionPago") {
        $('#modalNuevoDocumentoPagoConDocumento').modal('show');
        loaderShow("#modalNuevoDocumentoPagoConDocumento");
    } else if (accion == "guardarPago") {
        loaderShow("#modalNuevoDocumentoPagoConDocumento");
    }

    if (obtenerCodigoModalidad() == '1' || obtenerCodigoModalidad() == '3' || banderaMovilRegistro != 0) {

        let mensaje = 'Esta a punto de generar el pedido';
        if (banderaMovilRegistro != 2) {
            mensaje += '  y su comprobante respectivo';
        }
        if (!isEmpty(serieGuiaTransportista)) {
            mensaje += ', además de la guía de transportista con la serie y correlativo: <b>' + serieGuiaTransportista + '-' + correlativoGuiaTransportista + '</b>';
        }

        swal({
            title: "Confirmación",
            text: mensaje,
            type: "warning",
            html: true,
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si,generar !",
            cancelButtonColor: '#d33',
            cancelButtonText: "No,cancelar !",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (accion == "guardarPago") {
                    loaderShow("#modalNuevoDocumentoPagoConDocumento");
                } else {
                    loaderShow();
                }

                deshabilitarBoton();
                ax.setAccion('enviar');
                ax.addParamTmp("documento", documento);
                ax.addParamTmp("detalle", detalle);
                ax.addParamTmp("dataPago", dataPago);
                ax.addParamTmp("dataguia", datosArray);
                ax.setTag(accion);
                ax.consumir();
            } else {
                loaderClose();
            }
        });
    } else {
        loaderShow();
        deshabilitarBoton();
        ax.setAccion('enviar');
        ax.addParamTmp("documento", documento);
        ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("dataPago", dataPago);
        ax.addParamTmp("dataguia", datosArray);
        ax.setTag(accion);
        ax.consumir();
    }
}

function validarDetalleFormularioDocumentoRelacionIdentico() {
    //SOLO VALIDA CUANDO SE COPIA CON DETALLE    
    var bandera = false;
    var df;
    var dr;

    if (!isEmpty(detalleDocumentoRelacion)) {

        if (detalle.length <= detalleDocumentoRelacion.length) {
            df = detalle;
            dr = detalleDocumentoRelacion;
        } else {
            df = detalleDocumentoRelacion;
            dr = detalle;
        }

        for (var i = 0; i < dr.length; i++) {
            var bandera2 = false;
            for (var j = 0; j < df.length; j++) {
                //FALTA CORREGIR LAS VARIABLES CUANDO INTERCAMBIA DF X DR
                if (dr[i].bien_id == df[j].bienId && formatearCantidad(dr[i].cantidad) == formatearCantidad(df[j].cantidad) && dr[i].unidad_medida_id == df[j].unidadMedidaId) {
                    bandera2 = true;
                    break;
                }
            }
            if (!bandera2) {
                bandera = true;
                break;
            }
        }
    }

    return bandera;
}

function validarDetalleFormularioContenidoEnDocumentoRelacion() {
    //SOLO VALIDA CUANDO SE COPIA CON DETALLE    
    var bandera = false;//NO HAY ERRORES
    var df;
    var dr;

    if (!isEmpty(detalleDocumentoRelacion)) {
        df = detalle;
        dr = detalleDocumentoRelacion;

        for (var i = 0; i < df.length; i++) {
            var bandera2 = false;
            for (var j = 0; j < dr.length; j++) {
                if (df[i].bienId == dr[j].bien_id && df[i].cantidad * 1 <= dr[j].cantidad * 1 && df[i].unidadMedidaId == dr[j].unidad_medida_id) {
                    bandera2 = true;
                    break;
                }
            }
            if (!bandera2) {
                bandera = true;
                break;
            }
        }
    }

    return bandera;
}

function validarDetalleFormularioLlenos() {
    var valido = true;

    $.each(detalle, function (i, item) {
        //validamos que este seleccionado el tipo de precio 
        if (isEmpty(item.precioTipoId) || item.precioTipoId == 0) {
            mostrarValidacionLoaderClose("Seleccione el tipo de precio");
            valido = false;
            return false;
        }

        if (isEmpty(item.cantidad) || item.cantidad <= 0) {
            mostrarValidacionLoaderClose("No se especificó un valor válido para Cantidad");
            valido = false;
            return false;
        }

        if (((item.subTotal * 1) % 1) != 0) {
            mostrarValidacionLoaderClose("En el detalle del pedido la columna Sub Total no contener decimales, por favor edite el articulo, o anulelo y vuelva a registrarlo.");
            valido = false;
            return false;
        }

    });

    return valido;
}

function existeMotivoActivoFijo() {
    //BOLETA
    if ($("#cbo_623").length > 0 && (select2.obtenerValor("cbo_623") == "166" || select2.obtenerValor("cbo_623") == "59")) {
        return true;
        //FACTURA
    } else if ($("#cbo_624").length > 0 && (select2.obtenerValor("cbo_624") == "165" || select2.obtenerValor("cbo_624") == "56")) {
        return true;
    }
    return false;
}

function validarDetalleRepetido() {
    var detalleRepetido = false;

    for (var i = 0; i < detalle.length; i++) {
        for (var j = i + 1; j < detalle.length; j++) {
            if (detalle[i]["bienId"] === detalle[j]["bienId"] && detalle[i]["organizadorId"] === detalle[j]["organizadorId"] && detalle[i]["unidadMedidaId"] === detalle[j]["unidadMedidaId"]) {
                detalleRepetido = true;
                break;
            }
        }
    }

    return detalleRepetido;
}

function cargarPersona(tipo, personaTipoId) {
    var rutaAbsoluta = URL_BASE + 'index.php?token=1&tipo=' + personaTipoId + '&retorno=' + tipo;
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
    //    var win = window.open(rutaAbsoluta, '_blank');
    //    win.focus();
}

function editarPersona(tipo) {
    let personaId = select2.obtenerValor("cbo" + tipo);
    if (isEmpty(personaId) || personaId == -1) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione un " + tipo);
    } else {
        let persona_tipo_id = select2.obtenerValorDataSet("cbo" + tipo, "persona_tipo_id");
        var rutaAbsoluta = URL_BASE + 'index.php?token=1&id=' + personaId + '&tipo=' + persona_tipo_id + '&retorno=' + tipo;
        window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");

    }
}

function actualizarCboPersona(tipo) {

    obtenerPersonas(tipo);
}

function obtenerPersonas(tipo) {
    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", "");
    ax.addParamTmp("personaId", personaIdRegistro);
    ax.setTag({ id: tipo, personaId: personaIdRegistro });
    ax.consumir();
}
function onResponseObtenerPersonas(data, tipo) {
    let documentoTipoDato = data.filter(item => item.tipo == 5);
    if (!isEmpty(documentoTipoDato)) {
        dataPersona = documentoTipoDato[0].data;
        select2.cargarDataSet("cbo" + tipo, dataPersona, "id", ["codigo_identificacion", "nombre"], "persona_tipo_id");
        if (!isEmpty(personaIdRegistro)) {
            select2.asignarValor("cbo" + tipo, personaIdRegistro);
        } else {
            select2.asignarValor("cbo" + tipo, "");
        }
    }
}
function onResponseLoaderBien(data) {
    if (!isEmpty(data)) {
        select2.recargar("cboBien", data, "id", ["codigo", "descripcion"]);
        select2.asignarValor("cboBien", 0);
    }
}
function cargarBien() {
    var rutaAbsoluta = URL_BASE + 'index.php?token=2';
    //    var win = window.open(rutaAbsoluta, '_blank');
    //    win.focus();
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function verificarStockBien(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null) {
        mostrarAdvertencia("Seleccionar un producto");
    } else {
        obtenerStockPorBien(bienId, indice);
    }
}

function obtenerStockPorBien(bienId, indice) {

    ax.setAccion("obtenerStockPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.setTag(indice);
    ax.consumir();
}

function relacionarActivoFijo(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (isEmpty(bienId)) {
        mostrarAdvertencia("Seleccionar un producto");
        return;
    }
    $("#indexTablaDetalle").val(indice);
    $.each(detalle, function (i, item) {
        if (item.index == indice) {
            select2.asignarValor("cboActivosFijos", item.bienActivoFijoId);
        }
    });

    $('#modalActivosFijos').modal('show');
}

function guardarRelacionActivoFijo() {
    var indice = $("#indexTablaDetalle").val();
    var bien_activo_fijo_id = select2.obtenerValor("cboActivosFijos");
    if (isEmpty(bien_activo_fijo_id)) {
        mostrarAdvertencia("Seleccionar un activo fijo");
        return;

    }
    $.each(detalle, function (i, item) {
        if (item.index == indice) {
            item.bienActivoFijoId = bien_activo_fijo_id;
        }
    });

    $('#modalActivosFijos').modal('hide');
}


function onResponseObtenerStockPorBien(dataStock, indice) {
    var tituloModal = '<strong>' + select2.obtenerText("cboBien_" + indice) + '</strong>';
    $('.modal-title').empty();
    $('.modal-title').append(tituloModal);

    var data = [];

    if (!isEmpty(dataStock)) {
        $.each(dataStock, function (i, item) {
            if (item.stock != 0) {
                data.push(item);
            }
        });
    }

    if (!isEmptyData(data)) {
        $('#datatableStock').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                { "data": "organizador_descripcion" },
                { "data": "unidad_medida_descripcion" },
                { "data": "stock", "sClass": "alignRight" }
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return parseFloat(data).formatMoney(2, '.', ',');
                    },
                    "targets": 2
                }
            ],
            "destroy": true
        });
    } else {
        var table = $('#datatableStock').DataTable();
        table.clear().draw();
    }

    $('#modalStockBien').modal('show');
}
var importeTotalInafectas = 0;
function calcularImporteDetalle() {
    var importe = 0;
    var importeInafectas = 0;

    if (!isEmpty(detalle)) {
        $.each(detalle, function (index, item) {
            importe += (parseFloat(item.cantidad) * parseFloat(item.precio));
        });
    }
    importeTotalInafectas = importeInafectas;
    return importe;
}
var igvValor = 0.18;
var calculoTotal = 0;
function asignarImporteDocumento() {

    //    return;
    asignarValorCostoReparto();
    asignarValorRecojoDomicilio();

    var factorImpuesto = 1;
    var calculo, igv;
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {

        calculo = calcularImporteDetalle();

        var importe_devolucion_cargo = 0;
        var importe_otro_cargo = 0;
        var costo_reparto = 0;
        var detraccion_monto = 0;
        var ajuste_precio = 0;
        var costo_recojo_domicilio = 0;
        var monto_minimo = obtenerValorTarifario('precio_minimo');
        if (!isEmpty(importes.otrosCargoId) && !isEmpty($('#' + importes.otrosCargoId).val()) && document.getElementById('chkOtroCargo').checked) {
            importe_otro_cargo = parseFloat($('#' + importes.otrosCargoId).val());

            if (importe_otro_cargo < 0) {
                importe_otro_cargo = 0;
                $('#txtOtroCargo').val(importe_otro_cargo);
                mostrarValidacionLoaderClose("No puedes colocar un valor negativo en otros cargos.");
            }
        } else {
            document.getElementById(importes.otrosCargoId).value = devolverDosDecimales(importe_otro_cargo);
        }

        if (!isEmpty(importes.devolucionCargoId) && !isEmpty($('#' + importes.costoRepartoId).val()) && document.getElementById('chkDevolucionCargo').checked) {
            importe_devolucion_cargo = parseFloat($('#' + importes.devolucionCargoId).val());
        } else {
            document.getElementById(importes.devolucionCargoId).value = devolverDosDecimales(importe_devolucion_cargo);
        }


        if (!isEmpty(importes.costoRepartoId) && !isEmpty($('#' + importes.costoRepartoId).val())) {
            costo_reparto = parseFloat($('#' + importes.costoRepartoId).val());
        } else {
            document.getElementById(importes.costoRepartoId).value = devolverDosDecimales(costo_reparto);
        }

        if (!isEmpty(importes.costoRecojoDomicilioId) && !isEmpty($('#' + importes.costoRecojoDomicilioId).val())) {
            costo_recojo_domicilio = parseFloat($('#' + importes.costoRecojoDomicilioId).val());
        } else {
            document.getElementById(importes.costoRecojoDomicilioId).value = devolverDosDecimales(costo_recojo_domicilio);
        }

        if (!isEmpty(importes.detraccionId) && !isEmpty($('#' + importes.detraccionId).val())) {
            detraccion_monto = parseFloat($('#' + importes.detraccionId).val());
        } else {
            document.getElementById(importes.detraccionId).value = devolverDosDecimales(detraccion_monto);
        }

        calculo = calculo + costo_reparto + importe_devolucion_cargo + importe_otro_cargo + costo_recojo_domicilio;

        if (calculo < monto_minimo) {
            ajuste_precio = redondearNumerDecimales(monto_minimo - calculo);
        }

        calculo = calculo + ajuste_precio;

        document.getElementById(importes.ajustePrecioId).value = devolverDosDecimales(ajuste_precio);

        document.getElementById(importes.calculoId).value = devolverDosDecimales(calculo);
        if (importes.calculoId === importes.subTotalId) {
            if (!isEmpty(importes.igvId)) {
                igv = igvValor * calculo;
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.totalId)) {
                document.getElementById(importes.totalId).value = devolverDosDecimales(calculo + (factorImpuesto * igv)); // + importe_devolucion_cargo + importe_otro_cargo + costo_reparto);
            }
        } else if (importes.calculoId === importes.totalId) {
            if (!isEmpty(importes.igvId)) {
                igv = (calculo - calculo / (1 + igvValor));
                document.getElementById(importes.igvId).value = devolverDosDecimales(igv);
            }
            if (!isEmpty(importes.subTotalId)) {
                document.getElementById(importes.subTotalId).value = devolverDosDecimales(calculo - (factorImpuesto * igv));
            }
        }
        calculoTotal = parseFloat($('#' + importes.totalId).val());
    }
}
function obtenerTotalSobres() {

    let contador = 0;
    if (!isEmpty(detalle)) {
        $.each(detalle, function (index, item) {
            if (item.bienTipoId == 2) {
                contador = contador + (item.cantidad * 1);
            }
        });
    }
    return contador;
}

function obtenerPesoTotal() {

    let pesoTotal = 0;
    if (!isEmpty(detalle)) {
        $.each(detalle, function (index, item) {
            switch (item.tipo * 1) {
                case 0:
                    pesoTotal = pesoTotal + (item.bien_peso_total * 1);
                    break;
                case 1:
                    pesoTotal = pesoTotal + ((!isEmpty(item.bien_peso_volumetrico) ? (item.bien_peso_volumetrico * 1) : 0) * (item.cantidad * 1));
                    break;
            }

        });
    }
    return pesoTotal;
}

function asignarValorRecojoDomicilio() {
    // debugger;
    let costo_recojo = 0;
    let costo_total = 0;
    let costo_sobre = 0;
    let direccion_persona_id = select2.obtenerValor("cboClienteDireccion");
    let agencia_origen_id = select2.obtenerValor("cboAgenciaOrigen");

    let costo_recojo_reparto = $('#tipotipo').val();
    if ((obtenerCodigoModalidad() == '1' || obtenerCodigoModalidad() == '3') && !isEmpty(direccion_persona_id) && direccion_persona_id != -1) {
        let dataDireccion = dataPersonaRecojoDireccion.filter(itemFilter => itemFilter.id == direccion_persona_id);
        if (!isEmpty(dataDireccion)) {
            let tarifario = dataCofiguracionInicial.dataTarifarioZona.filter(itemFilter => itemFilter.agencia_id == agencia_origen_id && itemFilter.zona_id == dataDireccion[0]['zona_id']);
            let peso_total = obtenerPesoTotal();
            let cantidad_sobres = obtenerTotalSobres();
            if (cantidad_sobres >= 1) {
                costo_sobre = tarifario[0]['precio_sobre'];
            }
            else {
                costo_sobre = 0;
            }
            if (!isEmpty(tarifario) && peso_total > 0) {
                switch (true) {
                    case peso_total <= 50:
                        costo_recojo = (!isEmpty(tarifario[0]['precio_50k']) ? tarifario[0]['precio_50k'] * 1 : 0);
                        break;
                    case peso_total <= 100:
                        costo_recojo = (!isEmpty(tarifario[0]['precio_100k']) ? tarifario[0]['precio_100k'] * 1 : 0);
                        break;
                    case peso_total <= 250:
                        costo_recojo = (!isEmpty(tarifario[0]['precio_250k']) ? tarifario[0]['precio_250k'] * 1 : 0);
                        break;
                    case peso_total <= 500:
                        costo_recojo = (!isEmpty(tarifario[0]['precio_500k']) ? tarifario[0]['precio_500k'] * 1 : 0);
                        break;
                    default:
                        costo_recojo = (!isEmpty(tarifario[0]['precio_maxk']) ? tarifario[0]['precio_maxk'] * 1 : 0);
                        break;
                }
            }
        }
    }

    if (costo_recojo_reparto == 1) {
        costo_total = 0;
    }
    else {
        costo_total = costo_sobre + costo_recojo;
    }

    document.getElementById(importes.costoRecojoDomicilioId).value = devolverDosDecimales(costo_total);
}

function obtenerCostoReparto() {
    let personaId = obtenerPersonaId();
    ax.setAccion("obtenerConfiguracionCostoReparto");
    ax.addParamTmp("cboCliente", personaId);
    ax.consumir();

}
function asignarValorCostoReparto() {
    // debugger;
    let costo_reparto = 0;
    let costo_total = 0;
    let costo_sobre = 0;
    let direccion_persona_id = select2.obtenerValor("cboDestinatarioDireccion");
    let agencia_destino_id = select2.obtenerValor("cboAgenciaDestino");

    let costo_recojo_reparto = $('#tipotipo').val();
    if ((obtenerCodigoModalidad() == '1' || obtenerCodigoModalidad() == '3') && !isEmpty(direccion_persona_id) && direccion_persona_id != -1) {
        let dataDireccion = dataPersonaDespachoDireccion.filter(itemFilter => itemFilter.id == direccion_persona_id);
        if (!isEmpty(dataDireccion)) {
            let tarifario = dataCofiguracionInicial.dataTarifarioZona.filter(itemFilter => itemFilter.agencia_id == agencia_destino_id && itemFilter.zona_id == dataDireccion[0]['zona_id']);
            let peso_total = obtenerPesoTotal();
            let cantidad_sobres = obtenerTotalSobres();
            if (!isEmpty(tarifario) && cantidad_sobres >= 1) {
                costo_sobre = (tarifario[0]['precio_sobre'] * 1);
            }
            else {
                costo_sobre = 0;
            }
            if (!isEmpty(tarifario) && peso_total > 0) {
                switch (true) {
                    case peso_total <= 50:
                        costo_reparto = (!isEmpty(tarifario[0]['precio_50k']) ? tarifario[0]['precio_50k'] * 1 : 0);
                        break;
                    case peso_total <= 100:
                        costo_reparto = (!isEmpty(tarifario[0]['precio_100k']) ? tarifario[0]['precio_100k'] * 1 : 0);
                        break;
                    case peso_total <= 250:
                        costo_reparto = (!isEmpty(tarifario[0]['precio_250k']) ? tarifario[0]['precio_250k'] * 1 : 0);
                        break;
                    case peso_total <= 500:
                        costo_reparto = (!isEmpty(tarifario[0]['precio_500k']) ? tarifario[0]['precio_500k'] * 1 : 0);
                        break;
                    default:
                        costo_reparto = (!isEmpty(tarifario[0]['precio_maxk']) ? tarifario[0]['precio_maxk'] * 1 : 0);
                        break;
                }
            }
        }
    }

    if (costo_recojo_reparto == 1) {
        costo_total = 0;
    }
    else {
        costo_total = costo_sobre + costo_reparto;
    }
    document.getElementById(importes.costoRepartoId).value = devolverDosDecimales(costo_total);
}
function validarImportesLlenos() {
    //    breakFunction();
    validarImporteLlenar();
    if (!isEmpty(importes.calculoId)) {
        var importeFinal = document.getElementById(importes.calculoId).value;
        if (isEmpty(importeFinal)) {
            asignarImporteDocumento();
            importeFinal = document.getElementById(importes.calculoId).value;
        }
        var importeFinalSugerido = calcularImporteDetalle();
        var valorPercepcion = 0;
        if (percepcionId != 0) {
            valorPercepcion = $('#txt_' + percepcionId).val();
            if (isEmpty(valorPercepcion)) {
                valorPercepcion = 0;
            }
        }
        if (Math.abs(parseFloat(importeFinalSugerido) + parseFloat(valorPercepcion) - parseFloat(importeFinal)) > 1) {
            mostrarValidacionLoaderClose("El importe total tiene mucha variación con el cálculado por el sistema. No se puede continuar la operación.");
            return false;
        }
    }
    return true;
}
function onChangeCheckIncluyeIGV() {
    //    modificarDetallePrecios();
    validarImporteLlenar();
    asignarImporteDocumento();
}
function validarImporteLlenar() {
    if (!isEmpty(importes.subTotalId)) {
        if (document.getElementById('chkIGV').checked) {
            importes.calculoId = importes.totalId;
        } else {
            importes.calculoId = importes.subTotalId;
        }
    } else {
        importes.calculoId = importes.totalId;
    }
}

function verificarPrecioBien(indice) {
    indiceVerificarPrecioBien = indice;
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else {
        obtenerPrecioPorBien(bienId, indice);
    }
}

function obtenerPrecioPorBien(bienId, indice) {
    var incluyeIGV = 1;
    if (!document.getElementById('chkIncluyeIGV').checked) {
        incluyeIGV = 0;
    }

    ax.setAccion("obtenerPrecioPorBien");
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("unidadMedidaId", select2.obtenerValor("cboUnidadMedida_" + indiceVerificarPrecioBien));
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("incluyeIGV", incluyeIGV);
    ax.setTag(indice);

    ax.consumir();
}

function cambiarAPrecioMinimo(indice, precioTipoId, precioMinimo) {
    if (existeColumnaCodigo(5)) {
        document.getElementById("txtPrecio_" + indice).value = devolverDosDecimales(precioMinimo);
    }
    if (existeColumnaCodigo(4)) {
        select2.asignarValor("cboPrecioTipo_" + indice, precioTipoId);
        $("#cboPrecioTipo_" + indice).select2({ width: anchoTipoPrecioTD + 'px' });
    }
    $('#modalPrecioBien').modal('hide');

    hallarSubTotalDetalle(indice);
}

function onResponseObtenerPrecioPorBien(data, indice) {
    var tituloModal = '<strong>' + select2.obtenerText("cboBien_" + indice) + '</strong>';
    var dataPrecioBien = [];
    if (!isEmpty(data) && existeColumnaCodigo(12)) {
        var descuentoPorcentaje = 0;
        var precioMinimo = 0;
        var accion = '';

        var operador = obtenerOperador();

        $.each(data, function (index, item) {
            //calculo de utilidad porcentaje
            var precioVenta = item.precio * operador;
            //            var precioCompra = $('#txtPrecioCompra_' + indiceVerificarPrecioBien).html();
            var precioCompra = $('#txtPrecioCompra_' + indice).html();

            //------------------------
            //ASIGNO EL VALOR DE COMPRA EN CASO NO ESTE CONFIGURADA LA COLUMNA COMPRA  
            if (!existeColumnaCodigo(1)) {
                var indexTemporal = -1;
                $.each(detalle, function (i, item) {
                    if (parseInt(item.index) === parseInt(indice)) {
                        indexTemporal = i;
                        return false;
                    }
                });

                if (indexTemporal > -1) {
                    if (!isEmpty(detalle[indexTemporal].precioCompra)) {
                        precioCompra = detalle[indexTemporal].precioCompra;
                    }
                }
            }
            //-----------------------


            var cantidad = $('#txtCantidad_' + indice).val();

            var utilidadSoles = (precioVenta - precioCompra) * cantidad;

            var subTotal = precioVenta * cantidad;
            var utilidadPorcentaje = 0;
            if (subTotal != 0) {
                utilidadPorcentaje = (utilidadSoles / subTotal) * 100;
            }

            //$('#txtUtilidadPorcentaje_' + indice).html(devolverDosDecimales(utilidadPorcentaje) + " %");
            // fin calculo

            descuentoPorcentaje = (parseFloat(item.descuento) / 100) * parseFloat(utilidadPorcentaje);
            precioMinimo = parseFloat(precioVenta) - (descuentoPorcentaje / 100) * parseFloat(precioVenta);

            if (precioMinimo < precioCompra) {
                precioMinimo = precioCompra + 0.1;
            }

            accion = "<a onclick=\"cambiarAPrecioMinimo(" + indice + "," + item.precio_tipo_id + "," + precioMinimo + ");\">" +
                "<i class=\"fa fa-arrow-down\"  tooltip-btndata-toggle='tooltip'  style=\"color:#04B404;\" title=\"Seleccionar precio mínimo\"></i></a>";

            dataPrecioBien.push([item.precio_tipo_descripcion,
                precioVenta,
                utilidadPorcentaje,
                descuentoPorcentaje,
                precioMinimo,
                accion]);

        });
    }

    $('#datatablePrecio').dataTable({
        order: [[0, "desc"]],
        "ordering": false,
        "data": dataPrecioBien,
        "columns": [
            { "data": "0" },
            { "data": "1", "sClass": "alignRight" },
            { "data": "2", "sClass": "alignRight" },
            { "data": "3", "sClass": "alignRight" },
            { "data": "4", "sClass": "alignRight" },
            { "data": "5", "sClass": "alignCenter" }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [1, 2, 3, 4]
            }
        ],
        "destroy": true
    });

    $('.modal-title').empty();
    $('.modal-title').append(tituloModal);

    //    if (!isEmpty(data) && existeColumnaCodigo(1)) {
    if (!isEmpty(data)) {
        $('#modalPrecioBien').modal('show');
    } else {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese los precios del producto");
    }
}
function onChangeCheckIGV() {
    if (document.getElementById('chkIGV').checked) {
        igvValor = 0.18;
    } else {
        igvValor = 0;
    }
    asignarImporteDocumento();
}

function obtenerCodigoModalidad(valor = null) {

    let codigo = '';
    if (!isEmpty(dataCofiguracionInicial.dataModalidadPedido)) {
        if (isEmpty(valor)) {
            valor = select2.obtenerValor("cboModalidad");
        }
        let itemModalidad = dataCofiguracionInicial.dataModalidadPedido.filter(item => item.id == valor);
        if (!isEmpty(itemModalidad)) {
            codigo = itemModalidad[0]['codigo'];
        }
    }
    return codigo;
}

function onChangeCheckOtroCargo() {
    if (document.getElementById('chkOtroCargo').checked) {
        $("#txtOtroCargo").prop("disabled", false);
        $("#txtDescripcionOtroCargo").prop("disabled", false);
    } else {
        $("#txtOtroCargo").prop("disabled", true);
        $("#txtDescripcionOtroCargo").prop("disabled", true);
        document.getElementById(importes.otrosCargoId).value = devolverDosDecimales(0);
    }
    asignarImporteDocumento();
}

function onChangeCheckDevolucionCargo() {
    let devolucionCargo = 0;
    if (document.getElementById('chkDevolucionCargo').checked) {
        let precio_sobre = obtenerValorTarifario('precio_sobre');
        if (!isEmpty(precio_sobre)) {
            devolucionCargo = precio_sobre;
        }
    }
    document.getElementById(importes.devolucionCargoId).value = devolverDosDecimales(devolucionCargo);
    asignarImporteDocumento();
}
//Area de Opcion de Copiar Documento

function validarSoloUnDocumentoDeCopia() {
    var bandera = true;//no hay error

    if (!isEmpty(request.documentoRelacion)) {
        $.each(request.documentoRelacion, function (i, item) {
            if (!isEmpty(item.documentoId)) {
                bandera = false;//hay error
            }
        });
    }

    return bandera;
}

function cargarBuscadorDocumentoACopiar() {

    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna
        if (!isEmpty(obtenerDocumentoTipoDatoIdXTipo(17))) {
            var orgIdDest = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(17));
            if (isEmpty(orgIdDest)) {
                mostrarAdvertencia('Seleccione almacén de llegada');
                return;
            }
        } else {
            mostrarAdvertencia('Seleccione almacén de llegada');
            return;
        }

        //VALIDAR QUE SOLO SE COPIE UN DOCUMENTO
        //SOLO CUANDO EL MOTIVO DE MOVIMIENTO ES REPOSICION (CODIGO = 1)  ó  CUANDO ALMACEN VIRTUAL ESTA EN ORIGEN
        var dtdMovimientoTipo = obtenerDocumentoTipoDatoXTipoXCodigo(4, 4);

        var codMotivo = null;
        if (!isEmpty(select2.obtenerValor('cbo_' + id_cboMotivoMov))) {
            codMotivo = dtdMovimientoTipo.data[document.getElementById('cbo_' + id_cboMotivoMov).options.selectedIndex]['valor'];
        }

        if ((codMotivo == 1 || origen_destino == 'O') && !validarSoloUnDocumentoDeCopia()) {
            mostrarAdvertencia('Solo debe seleccionar un documento a reponer');
            return;
        }
    }

    var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
    if (!isEmpty(dtdOrganizadorId)) {
        var organizadorOrigen = select2.obtenerValor('cboOrganizador');
        var organizadorDestino = select2.obtenerValor('cbo_' + dtdOrganizadorId);

        if (organizadorOrigen == organizadorDestino) {
            mostrarValidacionLoaderClose('Seleccione un almacén de destino diferente al almacén de origen');
            return;
        }
    }

    if (bandera.primeraCargaDocumentosRelacion) {
        loaderShow();
        obtenerConfiguracionesInicialesBuscadorCopiaDocumento();
        bandera.primeraCargaDocumentosRelacion = false;
    } else {
        cargarModalCopiarDocumentos();
        actualizarBusquedaDocumentoRelacion();
    }
}

function obtenerConfiguracionesInicialesBuscadorCopiaDocumento() {
    ax.setAccion("obtenerConfiguracionBuscadorDocumentoRelacion");
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionBuscadorDocumentoRelacion(data) {
    $("#cboDocumentoTipoM").select2({
        width: "100%"
    });
    select2.cargar("cboDocumentoTipoM", data.documento_tipo, "id", "descripcion");
    $("#cboPersonaM").select2({
        width: "100%"
    });
    select2.cargar("cboPersonaM", data.persona, "id", "nombre");

    //    var table = $('#dtDocumentoRelacion').DataTable();
    //table.clear().draw();

    cargarModalCopiarDocumentos();
}

function cargarModalCopiarDocumentos() {
    var nombreCeldaPersona = 'Persona';
    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
        validarAlmacenOrigenDestino();
        if (origen_destino == "O") {
            nombreCeldaPersona = 'Guía relacionada';
        }
    }

    $('#nombreCeldaTHDocRelacion').html(nombreCeldaPersona);

    $('#modalDocumentoRelacion').modal('show');
    setTimeout(function () {
        cambiarAnchoBusquedaDesplegable();
    }, 100);
}

function buscarDocumentoRelacionPorCriterios() {
    loaderShow('#dtDocumentoRelacion');
    var cadena;
    //alert('hola');
    cadena = obtenerDatosBusquedaDocumentoACopiar();
    if (!isEmpty(cadena) && cadena !== 0) {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');

    setTimeout(function () {
        getDataTableDocumentoACopiar()
    }, 500);
}

function obtenerDatosBusquedaDocumentoACopiar() {
    var cadena = "";
    obtenerParametrosBusquedaDocumentoACopiar();

    if (!isEmpty(parametrosBusquedaDocumentoACopiar.documento_tipo_ids)) {
        cadena += negrita("Documento: ");
        cadena += select2.obtenerTextMultiple('cboDocumentoTipoM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.persona_id)) {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerTextMultiple('cboPersonaM');
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.serie)) {
        cadena += negrita("Serie: ");
        cadena += $('#txtSerie').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.numero)) {
        cadena += negrita("Numero: ");
        cadena += $('#txtNumero').val();
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_emision_fin)) {
        cadena += negrita("Fecha emisión: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_emision_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_emision_fin;
        cadena += "<br>";
    }
    if (!isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio) || !isEmpty(parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin)) {
        cadena += negrita("Fecha vencimiento: ");
        cadena += parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio + " - " + parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin;
        cadena += "<br>";
    }
    return cadena;
}

function getDataTableDocumentoACopiar() {
    ax.setAccion("buscarDocumentoRelacionPorCriterio");
    ax.addParamTmp("criterios", parametrosBusquedaDocumentoACopiar);
    ax.addParamTmp("empresa_id", commonVars.empresa);

    $('#dtDocumentoRelacion').dataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
            { "data": "fecha_creacion", "width": "9%" },
            { "data": "fecha_emision", "width": "7%" },
            { "data": "documento_tipo", "width": "10%" },
            { "data": "persona", "width": "24%" },
            { "data": "serie_numero", "width": "10%" },
            { "data": "serie_numero_original", "width": "10%" },
            { "data": "fecha_vencimiento", "width": "7%" },
            { "data": "moneda_simbolo", "width": "4%", "sClass": "alignCenter" },
            { "data": "total", "width": "8%", "sClass": "alignRight" },
            { "data": "usuario", "width": "6%", "sClass": "alignCenter" },
            {
                data: "flechas",
                render: function (data, type, row) {
                    if (type === 'display') {
                        var soloRelacionar = '';

                        if (row.relacionar == '1') {
                            soloRelacionar = '<a onclick="agregarCabeceraDocumentoRelacion(' + row.documento_tipo_id + ',' + row.documento_id + ',' + row.movimiento_id + ')"><b><i class="fa fa-arrow-down" style = "color:#1ca8dd;" tooltip-btndata-toggle="tooltip" title="Solo relacionar"></i></b></a>';
                        }

                        return '<a onclick="agregarDocumentoRelacion(' + row.documento_tipo_id + ',' + row.documento_id + ',' + row.movimiento_id + ',' + row.moneda_id + ',' + row.relacionar + ')"><b><i class="fa fa-download" style = "color:#04B404;" tooltip-btndata-toggle="tooltip" title="Copiar">&nbsp&nbsp</i></b></a>' +
                            soloRelacionar
                            ;
                    }
                    return data;
                },
                "orderable": false,
                "class": "alignCenter",
                "width": "5%"
            }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return (isEmpty(data)) ? '' : data.replace(" 00:00:00", "");
                },
                "targets": [1, 6]
            },
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 8
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData['documento_relacionado'] != '0') {
                $('td', nRow).css('background-color', '#FFD0D0');
            }
        },
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true
    });
    cargarModalCopiarDocumentos();
    loaderClose();

}

var parametrosBusquedaDocumentoACopiar = {
    empresa_id: null,
    documento_tipo_ids: null,
    persona_id: null,
    serie: null,
    numero: null,
    fecha_emision_inicio: null,
    fecha_emision_fin: null,
    fecha_vencimiento_inicio: null,
    fecha_vencimiento_fin: null
};

var id_cboDestino = null;
var id_cboMotivoMov = null;
var origen_destino = null;

function validarAlmacenOrigenDestino() {
    if (!isEmpty(obtenerDocumentoTipoDatoIdXTipo(17))) {
        var orgIdDest = select2.obtenerValor("cbo_" + obtenerDocumentoTipoDatoIdXTipo(17));
        if (isEmpty(orgIdDest)) {
            mostrarAdvertencia('Seleccione almacén de llegada');
            return;
        }
    } else {
        mostrarAdvertencia('Seleccione almacén de llegada');
        return;
    }

    var text1 = "";
    var text2 = "";

    text1 = (select2.obtenerText("cbo_" + id_cboDestino)).toLowerCase();
    text2 = (select2.obtenerText("cboOrganizador")).toLowerCase();

    if (text1.indexOf("virtual") == 0 && text2.indexOf("virtual") != 0) {
        parametrosBusquedaDocumentoACopiar.documento_tipo_ids = ["12", "189"];
        parametrosBusquedaDocumentoACopiar.serie = "D"; //destino almacen virtual
        origen_destino = "D";
    } else if (text1.indexOf("virtual") != 0 && text2.indexOf("virtual") == 0) {
        parametrosBusquedaDocumentoACopiar.documento_tipo_ids = ["264"];
        parametrosBusquedaDocumentoACopiar.serie = "O"; //orgien almacen virtual
        origen_destino = "O";
    } else {
        origen_destino = null;
    }
}

function obtenerParametrosBusquedaDocumentoACopiar() {
    parametrosBusquedaDocumentoACopiar = {
        empresa_id: null,
        documento_tipo_ids: null,
        persona_id: null,
        serie: null,
        numero: null,
        fecha_emision_inicio: null,
        fecha_emision_fin: null,
        fecha_vencimiento_inicio: null,
        fecha_vencimiento_fin: null,
        movimiento_tipo_id: dataCofiguracionInicial.movimientoTipo[0].id
    };

    if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna
        validarAlmacenOrigenDestino();
    } else {
        parametrosBusquedaDocumentoACopiar.documento_tipo_ids = $('#cboDocumentoTipoM').val();
        parametrosBusquedaDocumentoACopiar.serie = $('#txtSerie').val();
    }

    parametrosBusquedaDocumentoACopiar.empresa_id = commonVars.empresa;


    var personaId = $('#cboPersonaM').val();
    if (!isEmpty(personaId)) {
        parametrosBusquedaDocumentoACopiar.persona_id = personaId[0];
    }

    //    parametrosBusquedaDocumentoACopiar.serie = $('#txtSerie').val();
    parametrosBusquedaDocumentoACopiar.numero = $('#txtNumero').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = $('#dpFechaEmisionInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_emision_fin = $('#dpFechaEmisionFin').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_inicio = $('#dpFechaVencimientoInicio').val();
    parametrosBusquedaDocumentoACopiar.fecha_vencimiento_fin = $('#dpFechaVencimientoFin').val();
}

function agregarDocumentoRelacion(documentoTipoOrigenId, documentoId, movimientoId, monedaId, relacionar) {
    if (relacionar == 0) {
        $("#chkDocumentoRelacion").prop("checked", "");
        $("#divChkDocumentoRelacion").hide();
    }

    loaderShow("#modalDocumentoRelacion");
    //loaderShow();
    if (validarDocumentoRelacionRepetido(documentoId)) {
        mostrarAdvertencia("Documento a copiar ya a sido agregado");
        loaderClose();
        return;
    }

    if (select2.obtenerValor("cboMoneda") != monedaId && !isEmpty(detalle)) {
        mostrarAdvertencia("Las moneda deben ser iguales, seleccione otro documento, o cambie la moneda.");
        loaderClose();
        return;
    }

    variable.documentoIdCopia = documentoId;
    variable.movimientoIdCopia = movimientoId;

    if (dataCofiguracionInicial.documento_tipo[0].identificador_negocio == 5) {
        var documentoTipo = dataDocumentoTipo[0]['id'];
        ax.setAccion("obtenerNumeroNotaCredito");
        ax.addParamTmp("documentoTipoId", documentoTipo);
        ax.addParamTmp("documentoRelacionadoTipo", documentoTipoOrigenId);
        ax.consumir();
    }
    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDocumentoRelacion");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", request.documentoRelacion);
    ax.consumir();
}

function onResponseObtenerNumeroNotaCredito(data) {
    //colocamos la serie y numero en las cajas de texto
    var idTipoDatoSerie, idTipoDatoNumero;
    idTipoDatoSerie = buscarDocumentoTipoDatoPorTipo(7).id;
    $("#txt_" + idTipoDatoSerie).val(data[0].serie);

    idTipoDatoNumero = buscarDocumentoTipoDatoPorTipo(8).id;
    $("#txt_" + idTipoDatoNumero).val(data[0].numero);

}

function buscarDocumentoTipoDatoPorTipo(tipo) {

    var objDocumentoTipoDato = null;
    if (!isEmpty(dataCofiguracionInicial) && !isEmpty(dataCofiguracionInicial.documento_tipo_conf)) {
        $.each(dataCofiguracionInicial.documento_tipo_conf, function (indexConf, itemConf) {
            if (itemConf.tipo * 1 == tipo) {
                objDocumentoTipoDato = itemConf;
                return false;
            }
        });
    }
    return objDocumentoTipoDato;
}

function agregarCabeceraDocumentoRelacion(documentoTipoOrigenId, documentoId, movimientoId) {
    loaderShow("#modalDocumentoRelacion");
    //loaderShow();
    if (validarDocumentoRelacionRepetido(documentoId)) {
        mostrarAdvertencia("Documento a relacionar ya a sido agregado");
        loaderClose();
        return;
    }

    variable.documentoIdCopia = documentoId;
    variable.movimientoIdCopia = movimientoId;

    var documentoTipoDestinoId = $('#cboDocumentoTipo').val();

    ax.setAccion("obtenerDocumentoRelacionCabecera");
    ax.addParamTmp("documento_id_origen", documentoTipoOrigenId);
    ax.addParamTmp("documento_id_destino", documentoTipoDestinoId);
    ax.addParamTmp("documento_id", documentoId);
    ax.addParamTmp("movimiento_id", movimientoId);
    ax.addParamTmp("documentos_relacinados", request.documentoRelacion);
    ax.consumir();
}

function validarDocumentoRelacionRepetido(documentoACopiarId) {
    var resultado = false;
    $.each(request.documentoRelacion, function (index, item) {
        if (!isEmpty(item.documentoId)) {
            if (item.documentoId === documentoACopiarId) {
                resultado = true;
            }
        }

    });

    return resultado;
}

function reinicializarDataTableDetalle() {
    $('#datatable').dataTable({
        "order": [[0, "desc"]],
        "scrollX": true,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": false,
        //        "autoWidth": true,
        "destroy": true
    });
    asignarImporteDocumento();
}

var detalleDocumentoRelacion = [];
function onResponseObtenerDocumentoRelacion(data) {
    //    $('#modalDocumentoRelacion').modal('hide');

    if (data.documentoACopiar[0].incluye_igv == 1) {
        $("#chkIncluyeIGV").prop("checked", "checked");
    } else {
        $("#chkIncluyeIGV").prop("checked", "");
    }

    select2.asignarValorQuitarBuscador("cboMoneda", data.documentoACopiar[0].moneda_id);
    modificarSimbolosMoneda(data.documentoACopiar[0].moneda_id, dataCofiguracionInicial.moneda[document.getElementById('cboMoneda').options.selectedIndex]);

    detalle = [];
    indiceLista = [];
    banderaCopiaDocumento = 0;
    indexDetalle = 0;

    if (data.detalleDocumento.length > 5) {
        nroFilasReducida = data.detalleDocumento.length;
    } else {
        nroFilasReducida = 5;
    }

    $('#datatable').DataTable().clear().destroy();
    limpiarDetalle();
    reinicializarDataTableDetalle();

    detalleDocumentoRelacion = data.detalleDocumento;
    cargarDataDocumentoACopiar(data.documentoACopiar, data.dataDocumentoRelacionada);
    cargarDetalleDocumentoRelacion(data.detalleDocumento);

    cargarDocumentoRelacionadoDeCopia(data.documentosRelacionados);

    //cargar los datos copiados de programacion
    cargarPagoProgramacion(data.dataPagoProgramacion);
}

function cargarDocumentoRelacionadoDeCopia(data) {

    if (!isEmpty(data)) {
        if (data == 1) {
            $("#chkDocumentoRelacion").prop("checked", "");
            $("#divChkDocumentoRelacion").hide();

        } else {

            var detalleEnlace = '';
            $.each(data, function (index, item) {
                if (!validarDocumentoRelacionRepetido(parseInt(item.documento_id))) {
                    request.documentoRelacion.push({
                        documentoId: parseInt(item.documento_id),
                        movimientoId: parseInt(item.movimiento_id),
                        tipo: 2,
                        documentoPadreId: varDocumentoPadreId
                    });

                    detalleEnlace = item.documento_tipo_descripcion + ": " + item.serie_numero;

                    $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleEnlace + "]</a><br>");
                    //                    $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiar(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a>");
                    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

                    request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleEnlace;
                    request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
                    contadorDocumentoCopiadoAVisualizar++;
                }
            });

            varDocumentoPadreId = null;
        }

    }
}


function onResponseObtenerDocumentoRelacionCabecera(data) {
    cargarDataDocumentoACopiar(data.dataDocumento, data.dataDocumentoRelacionada);
    if (!isEmpty(variable.documentoIdCopia) && !isEmpty(variable.movimientoIdCopia)) {
        request.documentoRelacion.push({
            documentoId: variable.documentoIdCopia,
            movimientoId: variable.movimientoIdCopia,
            tipo: 1,
            documentoPadreId: null
        });
        varDocumentoPadreId = variable.documentoIdCopia;

        variable.documentoIdCopia = null;
        variable.movimientoIdCopia = null;
    }

    if (!isEmpty(detalleLink)) {
        if (bandera.mostrarDivDocumentoRelacion) {
            $('#divDocumentoRelacion').show();

            if (banderachkDocumentoRelacion === 0) {
                $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);
                banderachkDocumentoRelacion = 1;
            }
        }

        $('#linkDocumentoACopiar').append("<a id='link_" + contadorDocumentoCopiadoAVisualizar + "' onclick='visualizarDocumentoRelacion(" + contadorDocumentoCopiadoAVisualizar + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + detalleLink + "]</a>");
        $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + contadorDocumentoCopiadoAVisualizar + "' onclick='eliminarDocumentoACopiarSinDetalle(" + contadorDocumentoCopiadoAVisualizar + ")'style='color:red;'>&ensp;X</a><br>");
        $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() + 20);

        request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].detalleLink = detalleLink;
        request.documentoRelacion[contadorDocumentoCopiadoAVisualizar].posicion = contadorDocumentoCopiadoAVisualizar;
        contadorDocumentoCopiadoAVisualizar++;
        detalleLink = null;
    }

    $('#modalDocumentoRelacion').modal('hide');

    cargarDocumentoRelacionadoDeCopia(data.documentoCopiaRelaciones);
}

var contadorDocumentoCopiadoAVisualizar = 0;
function cargarDataDocumentoACopiar(data, dataDocumentoRelacionada) {
    var documentoTipo = "", serie = "", numero = "";
    if (!bandera.mostrarDivDocumentoRelacion) {
        if (!isEmpty(data)) {

            $.each(data, function (index, item) {

                switch (parseInt(item.tipo)) {
                    case 5:
                        select2.asignarValor('cbo_' + item.otro_documento_id, item.valor);
                        var indice = select2.obtenerValor('cbo_' + item.otro_documento_id);
                        if (indice == item.valor) {
                            obtenerPersonaDireccion(item.valor);
                        }
                        break;
                    case 6:
                        //                    case 7:
                        //                    case 8:
                        $('#txt_' + item.otro_documento_id).val(item.valor);
                        break;
                    //case 9: //fecha emision
                    case 10:
                    case 11:
                        $('#datepicker_' + item.otro_documento_id).val(formatearFechaJS(item.valor));
                        break;
                }
            });
            bandera.mostrarDivDocumentoRelacion = true;

        }

        if (!isEmpty(dataDocumentoRelacionada)) {
            $.each(dataDocumentoRelacionada, function (index, item) {
                if (isEmpty(item.documento_tipo_dato_origen)) {
                    select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                } else {

                    switch (item.tipo * 1) {
                        case 26: // vendedor
                            select2.asignarValor('cbo_' + item.documento_tipo_dato_destino, item.valor);
                            break;
                        default:
                            $('#txt_' + item.documento_tipo_dato_destino).val(item.valor);
                            if (isEmpty($('#txt_' + item.documento_tipo_dato_destino).val())) {
                                $('#datepicker_' + item.documento_tipo_dato_destino).val(formatearFechaJS(item.valor));
                            }
                    }
                }
            });
        }
    }

    if (!isEmpty(data)) {
        $.each(data, function (index, item) {

            documentoTipo = item.documento_tipo_descripcion;

            switch (parseInt(item.tipo)) {
                case 7:
                    if (!isEmpty(item.valor)) {
                        serie = item.valor;
                    }

                    break;
                case 8:
                    if (!isEmpty(item.valor)) {
                        numero = item.valor;
                    }
                    break;
            }
        });

        detalleLink = documentoTipo + ": " + serie + " - " + numero;
    }
}

function cargarDetalleDocumentoACopiar(data) {
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cargarDataTableDocumentoACopiar(
                cargarFormularioDetalleACopiar(item.organizador_id, item.bien_id, item.cantidad,
                    item.unidad_medida_id, item.valor_monetario, item.organizador_descripcion,
                    (item.bien_codigo + ' | ' + item.bien_descripcion), item.unidad_medida_descripcion, item.precio_tipo_id,
                    item.movimiento_bien_detalle, item.dataUnidadMedida, item.precio_tipo_descripcion,
                    item.movimiento_bien_id, item.bien_tipo_id, item.bien_alto
                    , item.bien_ancho
                    , item.bien_longitud
                    , item.bien_peso
                    , item.tipo
                    , item.bien_peso_volumetrico
                    , item.bien_factor_volumetrico
                    , item.bien_peso_total
                )
            );

        });
    }
}

function obtnerStockParaProductosDeCopia() {
    if (!isEmpty(detalle)) {
        //ALMACEN DE LLEGADA PARA TRANSFERENCIA INTERNA
        var organizadorDestinoId = null;
        if (dataCofiguracionInicial.movimientoTipo[0]["codigo"] == 20) { //Transferencia interna        
            var dtdOrganizadorId = obtenerDocumentoTipoDatoIdXTipo(17);
            if (!isEmpty(dtdOrganizadorId)) {
                organizadorDestinoId = select2.obtenerValor('cbo_' + dtdOrganizadorId);
            }
        }

        loaderShow();
        ax.setAccion("obtenerStockParaProductosDeCopia");
        ax.addParamTmp("organizadorDefectoId", organizadorIdDefectoTM);
        ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("organizadorDestinoId", organizadorDestinoId);
        ax.consumir();
    }
}

function onResponseObtenerStockParaProductosDeCopia(data) {
    $.each(data, function (index, item) {
        onResponseObtenerStockActual(item);
    });

    $('#modalDocumentoRelacion').modal('hide');
}

var dataFaltaAsignarCantidadXOrganizador = [];
function cargarModalParaAgregarOrganizador(data) {
    var stockOrganizadores;
    dataFaltaAsignarCantidadXOrganizador.push(data);
    var html = '<div>';
    html += '<p id="titulo_' + data.bien_id + '">' + data.bien_descripcion + ' : ' + data.cantidad + ' ' + data.unidad_medida_descripcion + '</p>';
    html += '</div>';

    if (isEmpty(data.stock_organizadores)) {
        html += '<p style="color:red;">No hay stock para este bien.</p>';
    } else {
        html += '<div class="table">';
        html += '<table id="datatableStock_' + data.bien_id + '" class="table table-striped table-bordered">';
        html += '<thead>';
        html += '<tr>';
        html += '<th style="text-align:center;">Organizador</th>';
        html += '<th style="text-align:center;">Disponible</th>';
        html += '<th style="text-align:center;">A usar</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';

        stockOrganizadores = obtenerstockPredefinido(data.stock_organizadores, data.cantidad);
        $.each(data.stock_organizadores, function (index, item) {

            html += '<tr>';
            html += '<td >' + item.organizadorDescripcion + '</td>';
            html += '<td >' + formatearCantidad(item.stock) + '</td>';
            html += '<td >';
            html += '<input type="number" min="0" id="txt_' + item.organizadorId + '_' + data.bien_id + '" style="text-align: right;" ';
            html += ' value="' + obtenerStockPredefinidoXOrganizador(item.organizadorId, stockOrganizadores) + '">';
            html += '</td>';
            html += '</tr>';
        });


        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    }


    $('#contenedorAsignarStockXOrganizador').append(html);

    $('#datatableStock_' + data.bien_id).dataTable({
        "columns": [
            { "width": "10px" },
            { "width": "10px", "sClass": "alignRight" },
            { "width": "10px", "sClass": "alignCenter" }
        ],
        "dom": '<"top">rt<"bottom"><"clear">',
        "order": [[1, "desc"]]
    });
}

function obtenerStockPredefinidoXOrganizador(organizadorId, stockOrganizadores) {
    var stock = "";
    $.each(stockOrganizadores, function (index, item) {
        if (item.organizadorId == organizadorId) {
            stock = formatearCantidad(item.asignado);
        }
    });

    return stock;
}
function obtenerstockPredefinido(data, stockDeseado) {
    var array = [];
    //    var organizadores = [];
    $.each(data, function (index, item) {
        array.push({
            organizadorId: item.organizadorId,
            stock: item.stock,
            asignado: 0
        });
    });

    array = ordenacionBurbuja(array);

    $.each(array, function (index, item) {
        if (parseFloat(stockDeseado) > parseFloat(item.stock)) {
            array[index]['asignado'] = item.stock;
            stockDeseado = stockDeseado - item.stock;

        } else {
            array[index]['asignado'] = stockDeseado;
            stockDeseado = 0;
        }
    });

    return array;

}

function ordenacionBurbuja(array) {

    var tamanio = array.length;
    var i, j;
    var aux;
    for (i = 0; i < tamanio; i++) {
        for (j = 0; j < (tamanio - 1); j++) {
            if (array[j].stock < array[j + 1].stock) {
                aux = array[j];
                array[j] = array[j + 1];
                array[j + 1] = aux;
            }
        }

    }

    return array;
}

function asignarStockXOrganizador() {
    var suma = 0;
    var valorStockUnitario;
    var organizadorUsado = [];

    var listaDetalleDocumentoACopiar = [];
    var banderaSalirEach = 0;

    $.each(dataFaltaAsignarCantidadXOrganizador, function (index, itemData) {
        if (banderaSalirEach === 0) {
            if (!isEmpty(itemData.stock_organizadores)) {
                $.each(itemData.stock_organizadores, function (index1, item) {

                    valorStockUnitario = $('#txt_' + item.organizadorId + '_' + itemData.bien_id).val();
                    if (!isEmpty(valorStockUnitario)) {
                        if (valorStockUnitario < 0) {
                            mostrarAdvertencia("El valor a usar es menor que cero para el bien " + itemData.bien_descripcion + " en el organizador " + item.organizadorDescripcion);
                            banderaSalirEach = 1;
                        } else {
                            if (parseFloat(valorStockUnitario) > parseFloat(itemData.cantidad)) {
                                mostrarAdvertencia("El valor a usar es mayor al requerido para el bien " + itemData.bien_descripcion);
                                banderaSalirEach = 1;
                            } else {
                                if (parseFloat(valorStockUnitario) > parseFloat(item.stock)) {
                                    mostrarAdvertencia("El valor a usar es mayor que el stock para el bien " + itemData.bien_descripcion);
                                    banderaSalirEach = 1;
                                } else {
                                    if (valorStockUnitario > 0) {
                                        suma = parseFloat(suma) + parseFloat(valorStockUnitario);
                                        organizadorUsado.push({
                                            organizadorDescripcion: item.organizadorDescripcion,
                                            organizadorId: item.organizadorId,
                                            usado: valorStockUnitario
                                        });
                                    }
                                }
                            }
                        }
                    }

                });
            }
            if (banderaSalirEach === 0) {
                if (parseFloat(suma) > 0 && parseFloat(suma) <= itemData.cantidad) {

                    $.each(organizadorUsado, function (index2, itemOrganizadorUsado) {
                        listaDetalleDocumentoACopiar.push(
                            cargarFormularioDetalleACopiar(itemOrganizadorUsado.organizadorId, itemData.bien_id, itemOrganizadorUsado.usado,
                                itemData.unidad_medida_id, itemData.valor_monetario, itemOrganizadorUsado.organizadorDescripcion,
                                itemData.bien_descripcion, itemData.unidad_medida_descripcion)
                        );
                    });
                } else {
                    if (!isEmpty(itemData.stock_organizadores)) {
                        mostrarAdvertencia("Los valores ingresados no son correctos para el bien " + itemData.bien_descripcion);
                        banderaSalirEach = 1;
                    }

                }
            }

            organizadorUsado = [];
            suma = 0;

            //            }
        }
    });

    if (banderaSalirEach === 0) {
        $.each(listaDetalleDocumentoACopiar, function (index, item) {
            cargarDataTableDocumentoACopiar(item);
        });

        listaDetalleDocumentoACopiar = [];

        if (banderaSalirEach === 0) {
            $('#modalAsignarOrganizador').modal('hide');

        }
    }


}

function cargarFormularioDetalleACopiar(organizadorId, bienId, cantidad, unidadMedidaId, precio,
    organizadorDesc, bienDesc, unidadMedidaDesc, precioTipoId, movimientoBienDetalle, dataUnidadMedida, precioTipoDesc,
    movimientoBienId, bienTipoId, bienAlto, bienAncho, bienLongitud, bienPeso, tipo, bienPesoVolumetrico, bienFactorVolumetrico,
    bienPesoTotal,descripcionpaquete) {

    var objDetalle = {};//Objeto para el detalle    
    var detDetalle = [];
    objDetalle.precioCompra = 0;
    objDetalle.precio = precio;
    objDetalle.cantidad = cantidad;
    objDetalle.precioTipoId = precioTipoId;
    objDetalle.precioTipoDesc = precioTipoDesc;
    objDetalle.stockBien = 0;
    objDetalle.bienId = bienId;
    objDetalle.bienDesc = bienDesc;
    objDetalle.unidadMedidaId = unidadMedidaId;
    objDetalle.unidadMedidaDesc = unidadMedidaDesc;
    objDetalle.organizadorId = organizadorId;
    objDetalle.organizadorDesc = organizadorDesc;
    objDetalle.bien_alto = bienAlto;
    objDetalle.bien_ancho = bienAncho;
    objDetalle.bien_longitud = bienLongitud;
    objDetalle.bien_peso = bienPeso;
    objDetalle.bien_peso_volumetrico = bienPesoVolumetrico;
    objDetalle.bien_factor_volumetrico = bienFactorVolumetrico;
    objDetalle.bien_peso_total = bienPesoTotal;
    objDetalle.descripcion = descripcionpaquete;
    //fin columna dinamica        
    var stockBien = 0;

    objDetalle.stockBien = stockBien;
    objDetalle.bienTipoId = bienTipoId;
    objDetalle.bienTramoId = null;
    objDetalle.detalle = detDetalle;
    objDetalle.tipo = tipo;
    objDetalle.movimientoBienId = movimientoBienId;
    return objDetalle;
}

var banderachkDocumentoRelacion = 0;
var varDocumentoPadreId;
function cargarDataTableDocumentoACopiar(data) {
    if (!isEmpty(data)) {
        valoresFormularioDetalle = data;
        agregarConfirmado();
        asignarImporteDocumento();
    }
}

var banderaEliminarDocumentoRelacion = 0;
var eliminadosArray = new Array();
function eliminarDocumentoACopiar(indice) {
    detalleDocumentoRelacion = [];

    numeroItemFinal = 0;
    var tipoRelacion = request.documentoRelacion[indice].tipo;
    var contRelacion = 1;

    if (tipoRelacion == 1) {
        loaderShow();
        detalle = [];
        indiceLista = [];
        banderaCopiaDocumento = 0;
        indexDetalle = 0;
        asignarImporteDocumento();
        obtenerUtilidadesGenerales();
        nroFilasInicial = parseInt(dataDocumentoTipo[0]['cantidad_detalle']);

        if (banderaVerTodasFilas === 1) {
            nroFilasReducida = nroFilasInicial;
        } else {
            nroFilasReducida = 5;
        }


        mapaEstadoHeaders.set((request.documentoRelacion[indice].documentoId).toString(), false);
        htmlUniqueHeaders.delete(request.documentoRelacion[indice].documentoId);
        mapaHeaders.delete(request.documentoRelacion[indice].detalleLink);


        $.each(request.documentoRelacion, function (index, item) {
            if (item.documentoPadreId == request.documentoRelacion[indice].documentoId) {
                //mapaEstadoHeaders.set((request.documentoRelacion[indice].documentoId).toString(), false);
                request.documentoRelacion[index].documentoId = null;
                request.documentoRelacion[index].movimientoId = null;


                contRelacion++;
            }
        });
    }

    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20 * contRelacion);

    banderaEliminarDocumentoRelacion = 1;

    if (tipoRelacion == 1) {
        //        $('#dgDetalle').empty();
        //LIMPIAR DATATABLE
        $('#datatable').DataTable().clear().destroy();
        llenarTablaDetalle(dataCofiguracionInicial);
        //REINICIALIZAAR DATATABLE
        $('#datatable').DataTable({
            "scrollX": true,
            "paging": false,
            "info": false,
            "filter": false,
            "ordering": false,
            "autoWidth": true,
            "destroy": true
        });
    }

    var banderaExisteDocumentoRelacionado = 0;
    //$("#contenedorDetalle").css("height", $("#contenedorDetalle").height() - (40 * indexDetalle));

    request.documentoRelacion[indice].documentoId = null;
    request.documentoRelacion[indice].movimientoId = null;
    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    $.each(request.documentoRelacion, function (index, item) {

        if (!isEmpty(item.documentoId)) {
            $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoRelacion(" + item.posicion + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "]</a>");

            if (item.tipo == 1) {
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiar(" + item.posicion + ")'style='color:red;'>&ensp;X</a>");
            }

            $('#linkDocumentoACopiar').append("<br>");
            banderaExisteDocumentoRelacionado = 1;
        }
    });

    if (banderaExisteDocumentoRelacionado === 0) {
        bandera.mostrarDivDocumentoRelacion = false;
        $('#divDocumentoRelacion').hide();
        $("#divChkDocumentoRelacion").show();
        $("#chkDocumentoRelacion").prop("checked", "checked");
    }

    if (tipoRelacion == 1) {
        loaderShow();
        ax.setAccion("obtenerDocumentoRelacionDetalle");
        ax.addParamTmp("documentos_relacionados", request.documentoRelacion);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
        ax.consumir();
    }

}

function eliminarDocumentoACopiarSinDetalle(indice) {
    var contRelacion = 1;
    //eliminar las relaciones hijas    
    $.each(request.documentoRelacion, function (index, item) {
        if (item.documentoPadreId == request.documentoRelacion[indice].documentoId) {
            request.documentoRelacion[index].documentoId = null;
            request.documentoRelacion[index].movimientoId = null;

            contRelacion++;
        }
    });


    $("#divDocumentoRelacion").css("height", $("#divDocumentoRelacion").height() - 20 * contRelacion);
    banderaEliminarDocumentoRelacion = 1;

    var banderaExisteDocumentoRelacionado = 0;
    //$("#contenedorDetalle").css("height", $("#contenedorDetalle").height() - (40 * indexDetalle));

    request.documentoRelacion[indice].documentoId = null;
    request.documentoRelacion[indice].movimientoId = null;
    $('#link_' + indice).remove();
    $('#cerrarLink_' + indice).remove();

    $('#linkDocumentoACopiar').empty();
    $.each(request.documentoRelacion, function (index, item) {

        if (!isEmpty(item.documentoId)) {
            $('#linkDocumentoACopiar').append("<a id='link_" + item.posicion + "' onclick='visualizarDocumentoRelacion(" + item.posicion + ")' id='linkDocumentoACopiar' style='color:#0000FF'>[" + item.detalleLink + "]</a>");

            if (item.tipo == 1) {
                $('#linkDocumentoACopiar').append("<a id='cerrarLink_" + item.posicion + "' onclick='eliminarDocumentoACopiarSinDetalle(" + item.posicion + ")'style='color:red;'>&ensp;X</a>");
            }

            $('#linkDocumentoACopiar').append("<br>");

            banderaExisteDocumentoRelacionado = 1;
        }
    });

    if (banderaExisteDocumentoRelacionado === 0) {
        bandera.mostrarDivDocumentoRelacion = false;
        $('#divDocumentoRelacion').hide();
        $("#divChkDocumentoRelacion").show();
        $("#chkDocumentoRelacion").prop("checked", "checked");
    }
}

function visualizarDocumentoRelacion(indice) {
    if (!isEmpty(request.documentoRelacion[indice].documentoId) && !isEmpty(request.documentoRelacion[indice].movimientoId)) {
        ax.setAccion("obtenerDocumentoRelacionVisualizar");
        ax.addParamTmp("documentoId", request.documentoRelacion[indice].documentoId);
        ax.addParamTmp("movimientoId", request.documentoRelacion[indice].movimientoId);
        ax.consumir();
    }
}

function onResponseObtenerDocumentoRelacionVisualizar(data) {
    cargarDataDocumento(data.dataDocumento);
    cargarDetalleDocumento(data.detalleDocumento);
    $('#modalDetalleDocumento').modal('show');
}

function cargarDataDocumento(data) {
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);


    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                '<label>' + item.descripcion + '</label>' +
                '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor)) {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
                    case 3:
                        valor = fechaArmada(valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
                    case 14:
                    case 15:
                    case 16:
                    case 38:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function cargarDetalleDocumento(data) {
    if (!isEmptyData(data)) {
        $.each(data, function (index, item) {
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["precioUnitario"] = formatearNumero(data[index]["precioUnitario"]);
            data[index]["importe"] = formatearNumero(data[index]["importe"]);
        });
        $('#datatable2').dataTable({
            "order": [[0, "desc"]],
            "data": data,
            "columns": [
                { "data": "organizador" },
                { "data": "cantidad", "sClass": "alignRight" },
                { "data": "unidadMedida" },
                { "data": "descripcion" },
                { "data": "precioUnitario", "sClass": "alignRight" },
                { "data": "importe", "sClass": "alignRight" }
            ],
            "destroy": true
        });
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function obtenerCheckDocumentoACopiar() {
    if ($('#divChkDocumentoRelacion').attr("style") == "display: none;") {
        cabecera.chkDocumentoRelacion = 1;
        return;
    }

    if (document.getElementById('chkDocumentoRelacion').checked) {
        cabecera.chkDocumentoRelacion = 1;
    } else {
        cabecera.chkDocumentoRelacion = 0;
    }
}

function negrita(cadena) {
    return "<b>" + cadena + "</b>";
}


function actualizarBusquedaDocumentoRelacion() {
    buscarDocumentoRelacionPorCriterios();
}

var listaDetalleFaltante;
var listaDetalleOriginal;
function onResponseEnviar(data) {
    cerrarModalAnticipo();
    if (!isEmpty(data.generarDocumentoAdicional)) {
        habilitarBoton();
        loaderClose();
        $('#modalDocumentoGenerado').modal('show');

        $("#dtBodyDocumentoGenerado").empty();

        var dataOrganizador = data.dataOrganizador;
        var dataProveedor = data.dataProveedor;
        var dataDocumentoTipo = data.dataDocumentoTipo;

        listaDetalleOriginal = data.dataDetalle;

        //        var cuerpo = "";
        //LLENAR TABLA DETALLE
        for (var i = 0; i < listaDetalleOriginal.length; i++) {
            //cuerpo += llenarFilaDetalleFaltantesTabla(i, listaDetalleOriginal[i]);
            $('#dtBodyDocumentoGenerado').append(llenarFilaDetalleFaltantesTabla(i, listaDetalleOriginal[i]));
        }
        //        $('#dtBodyDocumentoGenerado').append(cuerpo);

        //LLENAR COMBOS
        for (var i = 0; i < listaDetalleOriginal.length; i++) {
            //cargarOrganizadorDetalleCombo(data.organizador, i);
            //cargarUnidadMedidadDetalleCombo(i);
            cargarTipoDocumentoCombo(dataDocumentoTipo, dataOrganizador, dataProveedor, i);
            cargarOrganizadorProveedorCombo(dataOrganizador, dataProveedor, i);
        }
    } else if (!isEmpty(data.anticipos)) {
        mostrarAnticipos(data);
    } else {
        if (!isEmpty(data.dataPlantilla)) {
            dibujarModalCorreos(data);
        } else if (!isEmpty(data.dataDocumentoPago)) {
            abrirModalPagos(data);

        } else if (!isEmpty(data.dataAtencionSolicitud)) {
            //asignarAtencion();
            abrirModalAtencionSolicitud(data);
        } else if (!isEmpty(data.resEfact) && data.resEfact.esDocElectronico == 1) {
            onResponseRespuestaEfact(data);
        } else if (boton.accion == 'enviarEImprimir') {
            var dataImp = data.dataImprimir;

            if (!isEmpty(dataImp.dataDocumento)) {
                cargarDatosImprimir(dataImp, 1);
            } else if (!isEmpty(dataImp.iReport)) {
                abrirDocumentoPDF(dataImp, URL_BASE + '/reporteJasper/documentos/');
            } else {
                abrirDocumentoPDF(dataImp, 'vistas/com/movimiento/documentos/');
            }
        } else {
            cargarPantallaListar(data.documentoGenerarId, data.tipoGenerarPdf, data.documentoGenerarGuiaId);
        }
    }

}

function onResponseRespuestaEfact(data) {
    var dataDocElec = data.resEfact.respDocElectronico;
    var titulo = '';
    //CORRECTO
    if (dataDocElec.tipoMensaje == 1 || dataDocElec.tipoMensaje == 0) {
        mostrarOk(dataDocElec.mensaje);
        if (dataDocElec.titulo === 'undefined') {
            titulo = '';
        } else {
            titulo = dataDocElec.titulo;
        }
        swal({
            title: "Registro correcto" + titulo,
            text: dataDocElec.mensaje,
            type: "success",
            html: true,
            showCancelButton: false,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (!isEmpty(dataDocElec.urlPDF)) {
                    window.open(URL_BASE + 'pdf2.php?url_pdf=' + dataDocElec.urlPDF + '&nombre_pdf=' + dataDocElec.nombrePDF);
                }
                cargarPantallaListar();
            }
        });
    }

    //ERROR CONTROLADO SUNAT NO VA A REGISTRAR - WARNING EXCEPTION EN NEGOCIO - PARA NEGAR COMMIT

    //ERROR DESCONOCIDO
    if (dataDocElec.tipoMensaje == 2 || dataDocElec.tipoMensaje == 3 || dataDocElec.tipoMensaje == 4) {
        var mensaje = dataDocElec.mensaje;
        if (dataDocElec.tipoMensaje == 4) {
            mensaje += "<br><br> Se registró en el sistema, pero fue rechazada por SUNAT.";
        } else {
            mensaje += "<br><br> Se registró en el sistema, posteriormente se intentará registrar en SUNAT."
        }
        swal({
            title: "Error desconocido",
            text: mensaje,
            type: "warning",
            html: true,
            showCancelButton: false,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (!isEmpty(dataDocElec.urlPDF)) {
                    window.open(URL_BASE + 'pdf2.php?url_pdf=' + dataDocElec.urlPDF + '&nombre_pdf=' + dataDocElec.nombrePDF);
                }
                cargarPantallaListar();
            }
        });
    }
}


function abrirDocumentoPDF(data, contenedor) {
    var link = document.createElement("a");
    link.download = data.nombre + '.pdf';
    link.href = contenedor + data.pdf;
    link.click();


    ax.setAccion("eliminarPDF");
    ax.addParamTmp("url", data.url);
    ax.consumir();
}

var dataRespuestaCorreo;
function dibujarModalCorreos(data) {

    dataRespuestaCorreo = data;

    $("#tbodyDetalleCorreos").empty();
    var html = '';

    if (!isEmpty(data.dataCorreos)) {
        $.each(data.dataCorreos, function (index, itemh) {
            html += '<tr>' +
                '<td>' +
                '<div class="checkbox" style="margin: 0px;">' +
                '<label class="cr-styled">' +
                '<input onclick="" type="checkbox" name="chekCorreo" id="correo' + index + '" value="' + itemh + '" checked>' +
                '<i class="fa"></i> ' +
                itemh +
                '</label>' +
                ' </div>' +
                '</td>' +
                '</tr>'
                ;
        });

        $("#tbodyDetalleCorreos").append(html);
    } else {
        $("#rowDataTableCorreo").hide();
    }

    $('#modalCorreos').modal('show');
}

function enviarCorreosMovimiento() {
    var txtCorreo = $('#txtCorreo').val();
    var correosSeleccionados = new Array();

    if (!isEmpty(dataRespuestaCorreo.dataCorreos)) {
        var chekCorreo = document.getElementsByName('chekCorreo');

        $.each(chekCorreo, function (index, item) {
            if (item.checked == true) {
                correosSeleccionados.push(item.value);
            }
        });
    }

    if (isEmpty(txtCorreo) && isEmpty(correosSeleccionados)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccione o ingrese un correo, para enviar email.");
        return;
    }

    loaderShow('#modalCorreos');
    ax.setAccion("enviarCorreosMovimiento");
    ax.addParamTmp("txtCorreo", txtCorreo);
    ax.addParamTmp("correosSeleccionados", correosSeleccionados);
    ax.addParamTmp("respuestaCorreo", dataRespuestaCorreo);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.consumir();
}

function cancelarEnvioCorreos() {
    $('#modalCorreos').modal('hide');
    $('.modal-backdrop').hide();
    cargarPantallaListar();
}

function llenarFilaDetalleFaltantesTabla(indice, dataDetalle) {
    var fila = "<tr id=\"trDetalleFaltante_" + indice + "\">"
        + "<td style='border:0; width: 40%; vertical-align: middle;'>" + agregarBienDetalleFaltanteTabla(indice, dataDetalle['bienDesc']) + "</td>"
        + "<td style='border:0; width: 10%; vertical-align: middle; '>" + agregarCantidadDetalleFaltanteTabla(indice, dataDetalle['cantidad']) + "</td>"
        + "<td style='border:0; width: 20%; vertical-align: middle; '>" + agregarTipoDocumento(indice) + "</td>"
        + "<td style='border:0; width: 30%; vertical-align: middle; '>" + agregarComboOrganizadorProveedor(indice) + "</td>"
        + "</tr>";

    return fila;

}

function agregarBienDetalleFaltanteTabla(i, valor) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"text\" id=\"txtBien_" + i + "\" name=\"txtBien_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"" + valor + "\" style=\"text-align: left;\" readonly=true /></div>";

    return $html;
}

function agregarCantidadDetalleFaltanteTabla(i, valor) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<input type=\"text\" id=\"txtCantidadFaltante_" + i + "\" name=\"txtCantidadFaltante_" + i + "\" class=\"form-control\" required=\"\" aria-required=\"true\" value=\"" + valor + "\" style=\"text-align: right;\" readonly=true /></div>";

    return $html;
}

function agregarTipoDocumento(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboTipoDocumento_" + i + "\" id=\"cboTipoDocumento_" + i + "\" class=\"select2\">" +
        "</select></div>";

    return $html;
}

function agregarComboOrganizadorProveedor(i) {
    var $html = "<div class=\"input-group col-lg-12 col-md-12 col-sm-12 col-xs-12\">" +
        "<select name=\"cboOrganizadorProveedor_" + i + "\" id=\"cboOrganizadorProveedor_" + i + "\" class=\"select2\">" +
        "</select></div>";

    return $html;
}

function cargarTipoDocumentoCombo(dataDocumentoTipo, dataOrganizador, dataProveedor, indice) {
    //loaderShow();

    // tipo entrada salida
    //    $('#cboTipoDocumento_' + indice).append('<option value="1">Guía</option>');
    //    $('#cboTipoDocumento_' + indice).append('<option value="2">Solicitud de pedido</option>');

    if (!isEmpty(dataOrganizador[indice])) {
        select2.cargar("cboTipoDocumento_" + indice, dataDocumentoTipo, "documento_tipo_id", "descripcion");
        select2.asignarValor("cboTipoDocumento_" + indice, dataDocumentoTipo[0]['documento_tipo_id']);
    } else {
        $('#cboTipoDocumento_' + indice).append('<option value="-1">Solicitud de compra</option>');
        select2.asignarValor("cboTipoDocumento_" + indice, -1);
    }

    $("#cboTipoDocumento_" + indice).select2({
        width: '100%'
    }).on("change", function (e) {

        //loaderShow();
        //        asignarOrganizadorProveedor(e.val, indice, dataOrganizador, dataProveedor);
        //obtenerStockActual(indice);
    });

}

function asignarOrganizadorProveedor(tipoDocumentoId, indice, dataOrganizador, dataProveedor) {
    if (tipoDocumentoId != -1) {
        if (!isEmpty(dataOrganizador[indice])) {
            select2.cargar("cboOrganizadorProveedor_" + indice, dataOrganizador[indice], "organizadorId", "descripcion");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataOrganizador[indice][0]["organizadorId"]);
        }
    } else {
        if (!isEmpty(dataProveedor[indice])) {
            select2.cargar("cboOrganizadorProveedor_" + indice, dataProveedor[indice], "persona_id", "persona_nombre");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataProveedor[indice][0]["persona_id"]);
        }
    }

    //alert(tipoDocumentoId)    ;
}

function cargarOrganizadorProveedorCombo(dataOrganizador, dataProveedor, indice) {

    $("#cboOrganizadorProveedor_" + indice).select2({
        width: '100%'
    });

    if (!isEmpty(dataOrganizador[indice])) {
        select2.cargar("cboOrganizadorProveedor_" + indice, dataOrganizador[indice], "organizadorId", "descripcion");
        select2.asignarValor("cboOrganizadorProveedor_" + indice, dataOrganizador[indice][0]["organizadorId"]);
    } else {
        if (!isEmpty(dataProveedor[indice])) {
            // solo proveedor
            //            select2.asignarValor("cboTipoDocumento_" + indice, 2);
            $("#cboTipoDocumento_" + indice).attr('disabled', 'disabled');

            select2.cargar("cboOrganizadorProveedor_" + indice, dataProveedor[indice], "persona_id", "persona_nombre");
            select2.asignarValor("cboOrganizadorProveedor_" + indice, dataProveedor[indice][0]["persona_id"]);
        }
    }
}

var listaDetallePedidos;
var listaDetalleGuia;
var listaDetalleVenta;

function guardarDocumentoGenerado() {

    listaDetallePedidos = [];
    listaDetalleGuia = [];
    listaDetalleVenta = [];
    var proveedor = null;

    //obtenemos el tipo de documento
    var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");

    var indGuia = 0;
    var indPed = 0;


    var totalGuia = 0;
    var totalProv = 0;
    for (var i = 0; i < listaDetalleOriginal.length; i++) {
        var tipoDocumentoId = select2.obtenerValor("cboTipoDocumento_" + i);
        var organizadorProveedorId = select2.obtenerValor("cboOrganizadorProveedor_" + i);
        var organizadorProveedorText = select2.obtenerText("cboOrganizadorProveedor_" + i);

        if (tipoDocumentoId != -1) {
            listaDetalleGuia.push(listaDetalleOriginal[i]);
            listaDetalleGuia[indGuia]["tipoDocumentoId"] = tipoDocumentoId;
            listaDetalleGuia[indGuia]["organizadorId"] = organizadorProveedorId;
            listaDetalleGuia[indGuia]["organizadorDesc"] = organizadorProveedorText;

            totalGuia = totalGuia + listaDetalleGuia[indGuia]["cantidad"] * listaDetalleGuia[indGuia]["precio"];

            indGuia++;
        } else {
            proveedor = select2.obtenerValor("cboOrganizadorProveedor_" + i);

            listaDetallePedidos.push(listaDetalleOriginal[i]);
            //            listaDetallePedidos[indPed]["organizadorId"] = organizadorIdDefectoTM;
            listaDetallePedidos[indPed]["proveedorId"] = proveedor;

            indPed++;
        }
    }

    var checkIgv = 0;

    if (!isEmpty(importes.subTotalId)) {
        if (document.getElementById('chkIncluyeIGV').checked) {
            checkIgv = 1;
        }
    } else {
        checkIgv = opcionIGV;
    }

    $('#modalDocumentoGenerado').modal('hide');
    loaderShow();
    deshabilitarBoton();
    ax.setAccion("guardarDocumentoGenerado");
    //guardar guia y nota
    ax.addParamTmp("detalleGuia", listaDetalleGuia);
    ax.addParamTmp("detallePedido", listaDetallePedidos);
    //ax.addParamTmp("proveedorId", proveedor);
    ax.addParamTmp("totalGuia", totalGuia);
    //ax.addParamTmp("totalProv", totalProv);

    //guardar venta
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.addParamTmp("camposDinamicos", camposDinamicos);
    ax.addParamTmp("detalleVenta", listaDetalleOriginal);
    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
    ax.addParamTmp("comentario", $('#txtComentario').val());
    ax.addParamTmp("checkIgv", checkIgv);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("accionEnvio", boton.accion);
    ax.consumir();
}

function obtenerBienTipoIdPorBienId(bienId) {
    var bienTipoId = 0;
    if (!isEmpty(dataCofiguracionInicial.bien)) {
        let bien = dataCofiguracionInicial.bien.filter(item => item.id == bienId);
        if (!isEmpty(bien)) {
            bienTipoId = bien[0]['bien_tipo_id'];
        }
    }
    return bienTipoId;
}

function obtenerBienPorBienId(bienId) {
    var bien = null;
    if (!isEmpty(dataCofiguracionInicial.bien)) {
        bien = dataCofiguracionInicial.bien.filter(item => item.id == bienId);
    }
    return bien;
}

function limpiarDetalle() {
    detalle = [];
    indiceLista = [];
    banderaCopiaDocumento = 0;
    indexDetalle = 0;
    nroFilasEliminados = 0;
    numeroItemFinal = 0;

    $('#dgDetalle').empty();
    $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * nroFilasReducida);
    llenarTablaDetalle(dataCofiguracionInicial);
}

var numeroItemFinal = 0;
function agregarFila() {
    if (nroFilasInicial > nroFilasReducida || parseInt(dataDocumentoTipo[0]['cantidad_detalle']) == 0) {
        //LLENAR TABLA DETALLE        
        var fila = llenarFilaDetalleTabla(nroFilasReducida);

        $('#datatable tbody').append(fila);

        //LLENAR COMBOS
        cargarOrganizadorDetalleCombo(dataCofiguracionInicial.organizador, nroFilasReducida);
        cargarUnidadMedidadDetalleCombo(nroFilasReducida);
        cargarBienDetalleCombo(dataCofiguracionInicial.bien, nroFilasReducida);
        cargarPrecioTipoDetalleCombo(dataCofiguracionInicial.precioTipo, nroFilasReducida);

        // nroFilasInicial++;
        nroFilasReducida++;

        $("#contenedorDetalle").css("height", inicialAlturaDetalle + 38 * (nroFilasReducida - nroFilasEliminados));
    } else {
        $('#divTodasFilas').hide();
        $('#divAgregarFila').hide();
    }

}


// funcionalidad de tramos registro

function verificarTipoUnidadMedida(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedida_" + indice);
    if (isEmpty(bienId)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else if (isEmpty(unidadMedidaId)) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar unidad de medida");
    } else {
        //verificar el tipo de unidad de medida (longitud)
        loaderShow();
        ax.setAccion("verificarTipoUnidadMedidaParaTramo");
        ax.addParamTmp("unidadMedidaId", unidadMedidaId);
        ax.setTag(indice);
        ax.consumir();

    }
}

function onResponseVerificarTipoUnidadMedidaParaTramo(data) {
    if (isEmpty(data)) {
        mostrarAdvertencia("Seleccione una unidad de medida de tipo longitud");
    } else {

        var tituloModal = '<strong>PRODUCTO: ' + select2.obtenerText("cboBien_" + data.indice) + '</strong>';

        limpiarMensajesTramo();
        $('#txtCantidadTramo').val('');
        $('#indiceTramo').val(data.indice);

        // unidad medida (metros)
        $('#cboUnidadMedidaTramo').empty();
        $('#cboUnidadMedidaTramo').append('<option value="157">Metro(s)</option>');

        $("#cboUnidadMedidaTramo").select2({
            width: '100%'
        });

        $('#bienTramoRegistro').empty();
        $('#bienTramoRegistro').append(tituloModal);
        $('.modal-title').empty();
        $('.modal-title').append("<strong>REGISTRAR TRAMO</strong>");
        $('#modalTramoBienRegistro').modal('show');

    }
}

function registrarTramoBien() {
    var unidadMedidaId = select2.obtenerValor("cboUnidadMedidaTramo");
    var cantidadTramo = $('#txtCantidadTramo').val();
    var indiceTramo = $('#indiceTramo').val();

    if (validarFormularioModalTramo(unidadMedidaId, cantidadTramo)) {
        var bienId = select2.obtenerValor("cboBien_" + indiceTramo);

        loaderShow();
        ax.setAccion("registrarTramoBien");
        ax.addParamTmp("unidadMedidaId", unidadMedidaId);
        ax.addParamTmp("cantidadTramo", cantidadTramo);
        ax.addParamTmp("bienId", bienId);
        ax.consumir();
    }
}

function validarFormularioModalTramo(unidadMedidaId, cantidadTramo) {
    var bandera = true;
    var espacio = /^\s+$/;
    limpiarMensajesTramo();

    if (unidadMedidaId === "" || unidadMedidaId === null || espacio.test(unidadMedidaId) || unidadMedidaId.length === 0) {
        $("#msjTipoUnidadMedidaTramo").text("La unidad de medida es obligatorio").show();
        bandera = false;
    }

    if (cantidadTramo === "" || cantidadTramo === null || espacio.test(cantidadTramo) || cantidadTramo.length === 0) {
        $("#msjCantidadTramo").text("Cantidad es obligatorio").show();
        bandera = false;
    } else if (cantidadTramo <= 0) {
        $("#msjCantidadTramo").text("Cantidad tiene que se positivo").show();
        bandera = false;
    }

    return bandera;
}

function limpiarMensajesTramo() {
    $("#msjTipoUnidadMedidaTramo").hide();
    $("#msjCantidadTramo").hide();
}

function onResponseRegistrarTramoBien(data) {
    if (data[0]["vout_exito"] == 0) {
        mostrarAdvertencia(data[0]["vout_mensaje"]);
    } else {
        mostrarOk(data[0]["vout_mensaje"]);
        $('#modalTramoBienRegistro').modal('hide');
    }
}

// funcionalidad de tramos busqueda

function listarTramosBien(indice) {
    var bienId = select2.obtenerValor("cboBien_" + indice);
    if (bienId == null) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Seleccionar un producto");
    } else {
        loaderShow();
        ax.setAccion("obtenerTramoBien");
        ax.addParamTmp("bienId", bienId);
        ax.setTag(indice);
        ax.consumir();
    }
}

function onResponseObtenerTramoBien(data, indice) {


    var tituloModal = '<strong>PRODUCTO: ' + select2.obtenerText("cboBien_" + indice) + '</strong>';
    //    Unidad medida	Cantidad  Accion

    var dataTramoBien = [];
    if (!isEmpty(data)) {
        var accion = '';

        $.each(data, function (index, item) {
            accion = "<a onclick=\"cambiarACantidadTramo(" + indice + "," + item.unidad_medida_id + "," + item.cantidad + "," + item.bien_tramo_id + ");\">" +
                "<i class=\"fa fa-arrow-down\"  tooltip-btndata-toggle='tooltip'  style=\"color:#04B404;\" title=\"Seleccionar tramo\"></i></a>";

            dataTramoBien.push([item.unidad_medida_descripcion,
            item.cantidad,
                accion]);
        });
    }

    $('#datatableTramoBien').dataTable({
        order: [[1, "asc"]],
        "ordering": false,
        "data": dataTramoBien,
        "columns": [
            { "data": "0" },
            { "data": "1", "sClass": "alignRight" },
            { "data": "2", "sClass": "alignCenter" }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 1
            }
        ],
        "destroy": true
    });


    $('#bienTramoBusqueda').empty();
    $('#bienTramoBusqueda').append(tituloModal);
    $('.modal-title').empty();
    $('.modal-title').append("<strong>SELECCIONAR TRAMO</strong>");
    $('#modalTramoBienBusqueda').modal('show');
}

var bienTramoId = null;
function cambiarACantidadTramo(indice, unidadMedidaId, cantidad, tramoId) {
    bienTramoId = tramoId;

    if (existeColumnaCodigo(12)) {
        document.getElementById("txtCantidad_" + indice).value = devolverDosDecimales(cantidad);
    }
    select2.asignarValor("cboUnidadMedida_" + indice, unidadMedidaId);
    $("#cboUnidadMedida_" + indice).select2({ width: anchoUnidadMedidaTD + 'px' });

    $('#modalTramoBienBusqueda').modal('hide');
    obtenerStockActual(indice);
    hallarSubTotalDetalle(indice);
}

// recalculo de precio de compra y utilidades
function recalculoPrecioCompraUtilidades() {
    //    alert('recalculoPrecioCompraUtilidades');

    primeraFechaEmision = $('#datepicker_' + fechaEmisionId).val();
    //    banderaPrimeraFE=true;

    $.each(detalle, function (indice, item) {
        obtenerPrecioCompra(item.index, item.unidadMedidaId, item.bienId);
    });

}

function obtenerPrecioCompra(indice, unidadMedidaId, bienId) {
    loaderShow();
    ax.setAccion("obtenerPrecioCompra");
    ax.addParamTmp("unidadMedidaId", unidadMedidaId);
    ax.addParamTmp("bienId", bienId);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
    ax.setTag(indice);
    ax.consumir();
}

function onResponseObtenerPrecioCompra(data) {
    if (!isEmpty(data)) {
        var operador = obtenerOperador();

        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + data.indice).html(devolverDosDecimales(data.precioCompra * operador));
        } else {
            varPrecioCompra = devolverDosDecimales(data.precioCompra * operador);
        }
        hallarSubTotalDetalle(data.indice);
    }
}

// recalculo de precio de compra y utilidades
function modificarPrecioCompra() {

    $.each(detalle, function (indice, item) {
        obtenerPrecioCompra(item.index, item.unidadMedidaId, item.bienId);
    });

}

function onResponseObtenerPersonaDireccionTexto(data) {
    if (isEmpty(data)) {
        $('#txt_' + textoDireccionId).val('');
    } else {
        $('#txt_' + textoDireccionId).val(data[0]['direccion']);
    }

}

function modificarDetallePrecios() {
    if (!isEmpty(detalle)) {
        loaderShow();
        var operador = obtenerOperador();

        ax.setAccion("modificarDetallePrecios");
        ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("operador", operador);
        ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
        ax.consumir();
    }
}

function onResponseModificarDetallePrecios(data) {
    $.each(data, function (indice, item) {
        if (existeColumnaCodigo(5)) {
            $("#txtPrecio_" + item.index).val(devolverDosDecimales(item.precio));
        }
        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + item.index).html(devolverDosDecimales(item.precioCompra));
        } else {
            varPrecioCompra = devolverDosDecimales(item.precioCompra);
        }
        hallarSubTotalDetalle(item.index);
    });
}

function modificarSimbolosMoneda(monedaId, moneda) {
    monedaSimbolo = moneda.simbolo;
    monedaBase = monedaId;

    $('#simDevolucionCargo').html(monedaSimbolo);
    $('#simTotal').html(monedaSimbolo);
    $('#simIgv').html(monedaSimbolo);
    $('#simSubTotal').html(monedaSimbolo);
    $('#simDetraccion').html(monedaSimbolo);
}

function modificarPreciosMoneda(monedaId, moneda) {
    if (!isEmpty(detalle) && existeColumnaCodigo(5)) {
        swal({
            title: " ¿Desea continuar?",
            text: "Se va a modificar los precios a " + moneda.descripcion,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                modificarSimbolosMoneda(monedaId, moneda);
                alertaModificarPrecioMoneda(monedaId, moneda);

            } else {
                select2.asignarValorQuitarBuscador("cboMoneda", monedaBase);
            }
        });
    }
}

function alertaModificarPrecioMoneda(monedaId, moneda) {
    swal({
        title: "Escoja una opción",
        text: "1: Convertir el precio con el tipo de cambio de la fecha de emisión.\n\
                   2: Modificar con el precio registrado previamente en el sistema.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "2",
        cancelButtonColor: '#d33',
        cancelButtonText: "1",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            modificarDetallePreciosXMonedaXOpcion(2);
        } else {
            modificarDetallePreciosXMonedaXOpcion(1);
        }
    });
}

function modificarDetallePreciosXMonedaXOpcion(opcion) {
    loaderShow();
    var operador = obtenerOperador();

    ax.setAccion("modificarDetallePreciosXMonedaXOpcion");
    ax.addParamTmp("detalle", detalle);
    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
    ax.addParamTmp("operador", operador);
    ax.addParamTmp("fechaEmision", $('#datepicker_' + fechaEmisionId).val());
    ax.addParamTmp("opcion", opcion);
    ax.consumir();
}

function onResponseModificarDetallePreciosXMonedaXOpcion(data) {
    $.each(data, function (indice, item) {
        if (existeColumnaCodigo(5)) {
            $("#txtPrecio_" + item.index).val(devolverDosDecimales(item.precio));
        }
        if (existeColumnaCodigo(1)) {
            $("#txtPrecioCompra_" + item.index).html(devolverDosDecimales(item.precioCompra));
        } else {
            varPrecioCompra = devolverDosDecimales(item.precioCompra);
        }
        hallarSubTotalDetalle(item.index);
    });
}

function dibujarBotonesDeEnvio(data) {

    var html = '<a   class="btn btn-danger" onclick="cargarPantallaListar()"><i class="fa fa-close"></i> Cancelar</a>';
    var accion = '';
    var estilo = '';
    $('#divAccionesEnvio').empty();

    var htmlPredet = '';
    if (!isEmpty(data.accionEnvioPredeterminado)) {
        accion = data.accionEnvioPredeterminado[0];
        if (!isEmpty(accion.color)) {
            //            estilo='style="color: '+accion.color+'"';
            estilo = '';
        }
        htmlPredet = '&nbsp;&nbsp;<button type="button" class="btn btn-success" onclick="enviar(\'' + accion.funcion + '\')" name="env" id="env"><i class="' + accion.icono + '" ' + estilo + '></i> ' + accion.descripcion + '</button>';
    }

    if (!isEmpty(data.accionesEnvio)) {
        accion = data.accionesEnvio;

        html += '&nbsp;&nbsp;<div class="btn-group dropup">' +
            htmlPredet +
            '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" name="envOpciones" id="envOpciones"><span class="caret"></span></button>' +
            '<ul class="dropdown-menu dropdown-menu-right" role="menu">';

        $.each(accion, function (index, item) {
            estilo = '';
            if (!isEmpty(item.color)) {
                estilo = 'style="color: ' + item.color + '"';
            }

            html += '<li><a   onclick="enviar(\'' + item.funcion + '\')"><i class="' + item.icono + '" ' + estilo + '></i>&nbsp;&nbsp; ' + item.descripcion + '</a></li>';
        });

        html += '</ul></div>';

    }

    $("#divAccionesEnvio").append(html);

}


//here
$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "ulBuscadorDesplegable2" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

function buscarDocumentoRelacion() {
    ax.setAccion("buscarDocumentoRelacion");
    ax.addParamTmp("busqueda", $('#txtBuscar').val());
    ax.addParamTmp("empresa_id", commonVars.empresa);
    ax.consumir();
}

function onResponseBuscarDocumentoRelacion(data) {
    var dataPersona = data.dataPersona;
    var dataDocumentoTipo = data.dataDocumentoTipo;
    var dataSerieNumero = data.dataSerieNumero;

    var html = '';
    $('#ulBuscadorDesplegable2').empty();
    if (!isEmpty(dataPersona)) {
        $.each(dataPersona, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + item.id + ',' + null + ')" >';
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

    if (!isEmpty(dataDocumentoTipo)) {
        $.each(dataDocumentoTipo, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorTexto(5,' + null + ',' + item.id + ')" >';
            html += '<span class="col-md-1"><i class="fa fa-files-o"></i></span>';
            html += '<span class="col-md-11">';
            html += '<label style="color: #141719;">' + item.descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }


    if (!isEmpty(dataSerieNumero)) {
        $.each(dataSerieNumero, function (index, item) {
            html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            html += '<a onclick="busquedaPorSerieNumero(\'' + item.serie + '\',\'' + item.numero + '\')" >';
            html += '<span class="col-md-1"><i class="ion-document"></i></span>';
            html += '<span class="col-md-2">';
            html += '<label style="color: #141719;">' + item.serie_numero + '</label>';
            html += '</span>';
            html += '<span class="col-md-9">';
            html += '<label style="color: #141719;">' + item.documento_tipo_descripcion + '</label>';
            html += '</span></a>';
            html += '</div>';

        });
    }
    $("#ulBuscadorDesplegable2").append(html);
}


function busquedaPorTexto(tipo, texto, tipoDocumento) {

    var tipoDocumentoIds = [];
    if (!isEmpty(tipoDocumento)) {
        tipoDocumentoIds.push(tipoDocumento);
    }

    if (tipo == 5) {
        llenarParametrosBusqueda(texto, tipoDocumentoIds, null, null, null, null);
    }

}

function busquedaPorSerieNumero(serie, numero) {
    llenarParametrosBusqueda(null, null, serie, numero, null, null);
}

function llenarParametrosBusqueda(personaId, tipoDocumentoIds, serie, numero, fechaEmision) {
    obtenerParametrosBusquedaDocumentoACopiar()

    parametrosBusquedaDocumentoACopiar.serie = serie;
    parametrosBusquedaDocumentoACopiar.numero = numero;
    parametrosBusquedaDocumentoACopiar.persona_id = personaId
    parametrosBusquedaDocumentoACopiar.fecha_emision_inicio = fechaEmision;
    parametrosBusquedaDocumentoACopiar.documento_tipo_ids = tipoDocumentoIds;
    loaderShow();

    getDataTableDocumentoACopiar();
}

$('#txtBuscar').keyup(function (e) {
    var bAbierto = $(this).attr("aria-expanded");

    if (!eval(bAbierto)) {
        $(this).dropdown('toggle');
    }

});

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function fechaArmada(valor) {
    var fecha = separarFecha(valor);

    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

var personaIdRegistro = null;
function setearPersonaRegistro(personaId, tipo) {

    personaIdRegistro = personaId;
    obtenerPersonas(tipo);
    obtenerPersonaDireccion(tipo, personaIdRegistro);
}

function visualizarCambioPersonalizado(monedaId) {
    if (cambioPersonalizadoId != 0) {
        if (monedaId != monedaBaseId) {
            fc = "";
            $('#txt_' + cambioPersonalizadoId).val('');
            $("#contenedorCambioPersonalizado").show();

            obtenerTipoCambioDatepicker();
        } else {
            $('#txt_' + cambioPersonalizadoId).val('');
            $("#contenedorCambioPersonalizado").hide();
        }
    }
}

function obtenerTipoCambioDatepicker() {
    if (fechaEmisionId != 0 /*&& cambioPersonalizadoId != 0*/) {
        var fecha = $("#datepicker_" + fechaEmisionId).val();
        obtenerTipoCambioXFecha(fecha);
    }
}

var fc = "";
function obtenerTipoCambioXFecha(fecha) {
    //var fecha = obtenerFechaActual();
    if (fc !== fecha) {
        ax.setAccion("obtenerTipoCambioXFecha");
        ax.addParam("fecha", fecha);
        ax.addParamTmp("documentoId", null);
        ax.consumir();
        fc = fecha;
    }
}

function onResponseObtenerTipoCambioXFecha(data) {
    if (!isEmptyData(data)) {
        $('#txt_' + cambioPersonalizadoId).val(data[0]['equivalencia_venta']);
        $('#tipoCambio').val('');
        $('#tipoCambio').val(data[0]['equivalencia_venta']);
    } else {
        $('#txt_' + cambioPersonalizadoId).val('');
    }
}

function setearDescripcionProducto(indice) {
    if (existeColumnaCodigo(16)) {
        var descripcion = select2.obtenerText("cboBien_" + indice);

        descripcion = descripcion.split("|");
        descripcion = descripcion[1].trim();

        $('#txtProductoDescripcion_' + indice).val(descripcion);
        $('#txtProductoDescripcion_' + indice).removeAttr("readonly");
    }
}

function setearUnidadMedidaDescripcion(indice) {
    if (existeColumnaCodigo(17)) {
        var descripcion = select2.obtenerText("cboUnidadMedida_" + indice);

        $('#txtUnidadMedidaDescripcion_' + indice).val(descripcion);
        $('#txtUnidadMedidaDescripcion_' + indice).removeAttr("readonly");
    }
}

function existeColumnaCodigo(codigo) {
    var dataColumna = dataCofiguracionInicial.movimientoTipoColumna;

    var existe = false;
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            if (parseInt(item.codigo) === parseInt(codigo)) {
                existe = true;
                return false;
            }
        });
    }

    return existe;
}

function obtenerFechaActual() {
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    hoy = dd + '/' + mm + '/' + yyyy;

    return hoy;
}

var fechaEmisionAnterior;
function onChangeFechaEmision() {
    if (documentoTipoTipo == 1) {
        if (!validarCambioFechaEmision) {
            if (!isEmpty(detalle)) {
                swal({
                    title: "Confirmación de actualización de precio promedio",
                    text: "¿Está seguro de actualizar los precios promedios a la fecha de emisión " + $('#datepicker_' + fechaEmisionId).val() + '?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#33b86c",
                    confirmButtonText: "Si!",
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        modificarDetallePrecios();
                    } else {
                        validarCambioFechaEmision = true;
                        $('#datepicker_' + fechaEmisionId).datepicker('setDate', fechaEmisionAnterior);
                    }
                    fechaEmisionAnterior = $('#datepicker_' + fechaEmisionId).val();
                });
            }
        }
        validarCambioFechaEmision = false;
    }
}

function onChangeTipoPago() {
    var tipoPagoId = select2.obtenerValor('cboTipoPago');


    if (tipoPagoId == 2) {
        //        if (calculoTotal > 0) {
        mostrarModalProgramacionPago();

        //        } else {
        //            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Total debe ser positivo.");
        //            select2.asignarValor('cboTipoPago', 1);
        //        }
    } else {
        $('#aMostrarModalProgramacion').hide();
        $('#idFormaPagoContado').show();
    }
}

function cancelarProgramacion() {
    $('#modalProgramacionPagos').modal('hide');

    if (!isEmpty(listaPagoProgramacion)) {
        swal({
            title: "Confirmación de cancelación de programación de pago",
            text: "¿Está seguro de cancelar la programación de pago? Al confirmar se limpiará la programación de pago registrada.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                modalPagoAbierto = false;

                listaPagoProgramacion = [];
                arrayFechaPago = [];
                arrayImportePago = [];
                arrayDias = [];
                arrayPorcentaje = [];
                arrayGlosa = [];
                arrayPagoProgramacionId = [];
                pagoProgramacionTotalImporte = 0;

                //                onListarPagoProgramacion(listaPagoProgramacion);

                $('#aMostrarModalProgramacion').show();
                $('#tipoPagoDescripcion').html('Forma de pago: Crédito');
                $('#tipoPagoDescripcion').css({ 'color': '#cb2a2a' });
                $('#idFormaPagoContado').hide();
            } else {
                aceptarProgramacion(false);
            }
        });
    } else {
        $('#aMostrarModalProgramacion').show();
        $('#tipoPagoDescripcion').html('Forma de pago: Crédito');
        $('#tipoPagoDescripcion').css({ 'color': '#cb2a2a' });
        $('#idFormaPagoContado').hide();
    }

}

function aceptarProgramacion(muestraMensaje) {
    if (!isEmpty(listaPagoProgramacion)) {

        var programacionTexto = '';
        var totalPago = 0;
        listaPagoProgramacion.forEach(function (item) {
            //listaPagoProgramacion.push([ fechaPago, importePago, dias, porcentaje,glosa,pagoProgramacionId]);
            totalPago = totalPago + item[1] * 1;

            var sep = ' | ';
            if (programacionTexto == '') {
                sep = '';
            }

            programacionTexto += sep + item[0] + ': ' + item[1];
        });

        if (programacionTexto.length > 55) {
            programacionTexto = programacionTexto.substring(0, 52) + '...';
        }

        $('#modalProgramacionPagos').modal('hide');
        $('#aMostrarModalProgramacion').show();
        $('#tipoPagoDescripcion').html('(' + programacionTexto + ')');

        if (totalPago != calculoTotal) {
            if (muestraMensaje) {
                mensajeValidacion('Total de pago no coincide con el total del documento');
            }
            $('#tipoPagoDescripcion').css({ 'color': '#cb2a2a' });
        } else {
            $('#tipoPagoDescripcion').css({ 'color': '#1ca8dd' });
        }

        $('#idFormaPagoContado').hide();

    } else {
        mensajeValidacion('Registre programación de pago.');
    }
}

var modalPagoAbierto = false;
function mostrarModalProgramacionPago() {
    var dtdFechaVencimientoId = obtenerDocumentoTipoDatoIdXTipo(10);

    //    if (!isEmpty(dtdFechaVencimientoId)) {
    if (!modalPagoAbierto) {
        $('#fechaPago').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        }).on('changeDate', function (ev) {
            //                actualizarNumeroDias();
        });

        //            var fechaVencimiento=$('#datepicker_' + dtdFechaVencimientoId).val();
        var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
        $('#fechaPago').datepicker('setDate', fechaEmision);

        $('#txtImportePago').val(devolverDosDecimales(calculoTotal));

        actualizarPorcentajePago();
        onChangeRdFechaPago();
        onChangeRdImportePago();

        modalPagoAbierto = true;
    }

    $('#modalProgramacionPagos').modal('show');

    setTimeout(function () {
        onListarPagoProgramacion(listaPagoProgramacion);
    }, 500);

    $('#labelTotalDocumento').html('(Total: ' + monedaSimbolo + ' ' + parseFloat(calculoTotal).formatMoney(2, '.', ',') + ')');

    ////    } else {
    //        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Falta configurar la fecha de vencimiento.");
    //        select2.asignarValor('cboTipoPago', 1);
    //    }
}

function obtenerDocumentoTipoDatoIdXTipo(tipo) {
    var dataConfig = dataCofiguracionInicial.documento_tipo_conf;

    var id = null;
    if (!isEmpty(dataConfig)) {
        $.each(dataConfig, function (index, item) {
            if (parseInt(item.tipo) === parseInt(tipo)) {
                id = item.id;
                return false;
            }
        });
    }

    return id;
}

function actualizarPorcentajePago() {
    var importePago = $('#txtImportePago').val();
    if (importePago > calculoTotal) {
        $('#txtImportePago').val(calculoTotal);
        mensajeValidacion('Importe de pago no puede ser mayor al total');
        calculoPorcentajePago();
        return;
    }

    if (importePago <= 0) {
        $('#txtImportePago').val(0);
        mensajeValidacion('El importe de pago debe ser positivo.');
        calculoPorcentajePago();
        return;
    }

    calculoPorcentajePago();
}

function calculoPorcentajePago() {
    //    if (document.getElementById("rdImportePago").checked) {
    var importePago = $('#txtImportePago').val();
    var porcentaje = (importePago / calculoTotal) * 100;
    $('#txtPorcentaje').val(devolverDosDecimales(porcentaje));
    //    }
}

function mensajeValidacion(mensaje) {
    $.Notification.autoHideNotify('warning', 'top right', 'Validación', mensaje);
}

function actualizarImportePago() {
    var porcentaje = $('#txtPorcentaje').val();
    if (porcentaje > 100) {
        $('#txtPorcentaje').val(100);
        mensajeValidacion('Porcentaje máximo 100.');
        calculoImportePago();
        return;
    }

    if (porcentaje <= 0) {
        $('#txtPorcentaje').val(0);
        mensajeValidacion('Porcentaje de pago debe ser positivo.');
        calculoImportePago();
        return;
    }

    calculoImportePago();

}

function calculoImportePago() {
    var porcentaje = $('#txtPorcentaje').val();
    var importePago = (calculoTotal * porcentaje) / 100;
    $('#txtImportePago').val(devolverDosDecimales(importePago));
}

function restarFechas(f1, f2) {
    var aFecha1 = f1.split('/');
    var aFecha2 = f2.split('/');
    var fFecha1 = Date.UTC(aFecha1[2], aFecha1[1] - 1, aFecha1[0]);
    var fFecha2 = Date.UTC(aFecha2[2], aFecha2[1] - 1, aFecha2[0]);
    var dif = fFecha2 - fFecha1;
    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
    return dias;
}

function actualizarNumeroDias() {
    var dtdFechaVencimientoId = obtenerDocumentoTipoDatoIdXTipo(10);
    var fechaVencimiento = $('#datepicker_' + dtdFechaVencimientoId).val();
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var fechaPago = $('#fechaPago').val();

    if (restarFechas(fechaEmision, fechaPago) < 0) {
        mensajeValidacion('La fecha de pago no puede ser menor a la fecha de emisión.');
        $('#fechaPago').datepicker('setDate', fechaEmision);
        return;
    }

    if (restarFechas(fechaPago, fechaVencimiento) < 0) {
        //        mensajeValidacion('La fecha de pago no puede ser mayor a la fecha de vencimiento.');
        //        $('#fechaPago').datepicker('setDate', fechaVencimiento);

        $('#modalProgramacionPagos').modal('hide');

        swal({
            title: "La fecha de pago es mayor a la fecha de vencimiento",
            text: "¿Desea actualizar la fecha de vencimiento a la fecha de pago: " + fechaPago + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#33b86c",
            confirmButtonText: "Si, actualizar!",
            cancelButtonColor: '#d33',
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#datepicker_' + dtdFechaVencimientoId).datepicker('setDate', fechaPago);
                calculoNumeroDias();
                $('#modalProgramacionPagos').modal('show');
            } else {
                $('#fechaPago').datepicker('setDate', fechaVencimiento);
                $('#modalProgramacionPagos').modal('show');
            }
        });

        return;
    }

    calculoNumeroDias();
}

function calculoNumeroDias() {
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var fechaPago = $('#fechaPago').val();

    $('#txtDias').val(restarFechas(fechaEmision, fechaPago));
}

function actualizarFechaPago() {
    var fechaEmision = $('#datepicker_' + fechaEmisionId).val();
    var dias = $('#txtDias').val();

    if (!isEmpty(dias)) {
        $('#fechaPago').datepicker('setDate', sumaFecha(dias, fechaEmision));
    }

}

function sumaFecha(d, fecha) {
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() + 1) + "/" + Fecha.getFullYear());
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
    var aFecha = sFecha.split(sep);
    var fecha = aFecha[2] + '/' + aFecha[1] + '/' + aFecha[0];
    fecha = new Date(fecha);
    fecha.setDate(fecha.getDate() + parseInt(d));
    var anno = fecha.getFullYear();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    mes = (mes < 10) ? ("0" + mes) : mes;
    dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = dia + sep + mes + sep + anno;
    return (fechaFinal);
}

//GUARDAR EN VARIABLE ARRAY PROGRAMACION PAGO
var listaPagoProgramacion = [];

var arrayFechaPago = [];
var arrayImportePago = [];
var arrayDias = [];
var arrayPorcentaje = [];
var arrayGlosa = [];
var arrayPagoProgramacionId = [];

function agregarPagoProgramacion() {
    //alert("Hola");

    //    var fechaPago = $('#cboFechaPago').val();

    var fechaPago = $('#fechaPago').val();
    var importePago = $('#txtImportePago').val();
    var dias = $('#txtDias').val();
    var porcentaje = $('#txtPorcentaje').val();
    var glosa = $('#txtGlosa').val();
    var idPagoProgramacion = $('#idPagoProgramacion').val();

    // ids tablas
    var pagoProgramacionId = null;
    //alert(idPagoProgramacion);

    if (validarFormularioPagoProgramacion(fechaPago, importePago, dias, porcentaje)) {
        if (validarPagoProgramacionRepetido(fechaPago, importePago, dias, porcentaje)) {

            if (idPagoProgramacion != '') {
                //alert('igual');

                arrayFechaPago[idPagoProgramacion] = fechaPago;
                arrayImportePago[idPagoProgramacion] = importePago;
                arrayDias[idPagoProgramacion] = dias;
                arrayPorcentaje[idPagoProgramacion] = porcentaje;
                arrayGlosa[idPagoProgramacion] = glosa;

                // ids de tablas relacionadas
                pagoProgramacionId = arrayPagoProgramacionId[idPagoProgramacion];

                listaPagoProgramacion[idPagoProgramacion] = [fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId];
            } else {
                //alert('diferente');

                arrayFechaPago.push(fechaPago);
                arrayImportePago.push(importePago);
                arrayDias.push(dias);
                arrayPorcentaje.push(porcentaje);
                arrayGlosa.push(glosa);

                listaPagoProgramacion.push([fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId]);
            }

            onListarPagoProgramacion(listaPagoProgramacion);
            limpiarCamposPagoProgramacion();

        }
    }
}

function validarFormularioPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var bandera = true;

    if (fechaPago === '' || fechaPago === null) {
        mensajeValidacion('Fecha de pago es obligatorio');
        bandera = false;
    }

    if (importePago === '' || importePago === null) {
        mensajeValidacion('Importe de pago es obligatorio');
        bandera = false;
    }

    if (importePago <= 0) {
        mensajeValidacion('Importe de pago debe ser positivo');
        bandera = false;
    }

    if (dias === '' || dias === null) {
        mensajeValidacion('Número de días es obligatorio');
        bandera = false;
    }

    if (porcentaje === '' || porcentaje === null) {
        mensajeValidacion('Porcentaje es obligatorio');
        bandera = false;
    }

    if (porcentaje <= 0) {
        mensajeValidacion('Porcentaje de pago debe ser positivo');
        bandera = false;
    }

    if (pagoProgramacionTotalImporte != 0) {
        var pagoRestante = calculoTotal - pagoProgramacionTotalImporte;

        if (pagoRestante - importePago < 0) {
            mensajeValidacion('Total de pago excedido. Importe de pago restante: ' + devolverDosDecimales(pagoRestante));
            bandera = false;
        }
    }

    return bandera;
}

function validarPagoProgramacionRepetido(fechaPago, importePago, dias, porcentaje) {
    var valido = true;

    var idPagoProgramacion = $('#idPagoProgramacion').val();

    if (idPagoProgramacion != '') {
        var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
        if (indice != idPagoProgramacion && indice != -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La fecha de pago ya ha sido agregado");
            valido = false;
        }
    } else {
        var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
        if (indice > -1) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La fecha de pago ya ha sido agregado");
            valido = false;
        }
    }

    return valido;
}

var pagoProgramacionTotalImporte = 0;
function onListarPagoProgramacion(data) {
    breakFunction();
    var ind = 0;
    var totalImporte = 0;
    var totalPorcentaje = 0;
    var dataTb = [];

    data.forEach(function (item) {
        dataTb[ind] = [item[0], item[1], item[2], item[3], item[4], item[5]];

        totalImporte += item['1'] * 1;
        totalPorcentaje += item['3'] * 1;

        var eliminar = "<a  onclick = 'eliminarPagoProgramacion(\""
            + item['0'] + "\", \"" + item['1'] + "\", \"" + item['2'] + "\", \"" + item['3'] + "\")' >"
            + "<i id='e" + ind + "' class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

        var editar = "<a  onclick = 'editarPagoProgramacion(\"" + ind + "\")' >"
            + "<i id='e" + ind + "' class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

        dataTb[ind][6] = editar + eliminar;

        ind++;
    });

    $('#dataTablePagoProgramacion').DataTable({
        "scrollX": false,
        "paging": false,
        "info": false,
        "filter": false,
        "ordering": true,
        "order": [[1, 'asc']],
        "data": dataTb,
        "columns": [
            { "data": 0, "sClass": "alignCenter" },
            { "data": 2, "sClass": "alignRight" },
            { "data": 1, "sClass": "alignRight" },
            { "data": 3, "sClass": "alignRight" },
            { "data": 4 },
            { "data": 6, "sClass": "alignCenter" }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": [2, 3]
            }
        ],
        "dom": '<"top">rt<"bottom"<"col-md-3"l><"col-md-9"p><"col-md-12"i>><"clear">',
        destroy: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api(), data;
            $(api.column(2).footer()).html(parseFloat(totalImporte).formatMoney(2, '.', ','));
            $(api.column(3).footer()).html(parseFloat(totalPorcentaje).formatMoney(2, '.', ','));
        }
    });

    pagoProgramacionTotalImporte = totalImporte;
}

function limpiarCamposPagoProgramacion() {
    $('#txtGlosa').val('');
    $('#idPagoProgramacion').val('');
}

function buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var tam = arrayFechaPago.length;
    var ind = -1;
    for (var i = 0; i < tam; i++) {
        if (arrayFechaPago[i] === fechaPago /*&& arrayImportePago[i] === importePago && arrayDias[i] === dias && arrayPorcentaje[i] === porcentaje*/) {
            ind = i;
            break;
        }
    }
    return ind;
}

function editarPagoProgramacion(indice) {
    $('#fechaPago').datepicker('setDate', arrayFechaPago[indice]);
    $('#txtImportePago').val(arrayImportePago[indice]);
    $('#txtPorcentaje').val(arrayPorcentaje[indice]);
    $('#txtGlosa').val(arrayGlosa[indice]);

    pagoProgramacionTotalImporte = pagoProgramacionTotalImporte - arrayImportePago[indice] * 1;

    $('#idPagoProgramacion').val(indice);
}

var listaPagoProgramacionEliminado = [];

function eliminarPagoProgramacion(fechaPago, importePago, dias, porcentaje) {
    var indice = buscarPagoProgramacion(fechaPago, importePago, dias, porcentaje);
    if (indice > -1) {
        arrayFechaPago.splice(indice, 1);
        arrayImportePago.splice(indice, 1);
        arrayDias.splice(indice, 1);
        arrayPorcentaje.splice(indice, 1);
        arrayGlosa.splice(indice, 1);
    }


    listaPagoProgramacion = [];
    var tam = arrayFechaPago.length;
    for (var i = 0; i < tam; i++) {
        listaPagoProgramacion.push([arrayFechaPago[i], arrayImportePago[i], arrayDias[i], arrayPorcentaje[i], arrayGlosa[i], arrayPagoProgramacionId[i]]);
    }

    onListarPagoProgramacion(listaPagoProgramacion);
}

function onChangeRdFechaPago() {
    if (document.getElementById("rdFechaPago").checked) {
        $("#fechaPago").removeAttr("disabled");
        $("#txtDias").attr('disabled', 'disabled');
    }
}

function onChangeRdDias() {
    if (document.getElementById("rdDias").checked) {
        $("#txtDias").removeAttr("disabled");
        $("#fechaPago").attr('disabled', 'disabled');
    }
}

function onChangeRdImportePago() {
    if (document.getElementById("rdImportePago").checked) {
        $("#txtImportePago").removeAttr("disabled");
        $("#txtPorcentaje").attr('disabled', 'disabled');
    }
}

function onChangeRdPorcentaje() {
    if (document.getElementById("rdPorcentaje").checked) {
        $("#txtPorcentaje").removeAttr("disabled");
        $("#txtImportePago").attr('disabled', 'disabled');
    }
}

function cargarPagoProgramacion(data) {
    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);

    if (!isEmpty(data) && isEmpty(listaPagoProgramacion) && !isEmpty(dtdTipoPago)) {
        var fechaPago;
        var importePago;
        var dias;
        var porcentaje;
        var glosa;
        var pagoProgramacionId = null;
        $.each(data, function (index, item) {
            fechaPago = formatearFechaBDCadena(item.fecha_pago);
            importePago = devolverDosDecimales(item.importe);
            porcentaje = devolverDosDecimales(item.porcentaje);
            dias = item.dias;
            glosa = item.glosa;

            arrayFechaPago.push(fechaPago);
            arrayImportePago.push(importePago);
            arrayDias.push(dias);
            arrayPorcentaje.push(porcentaje);
            arrayGlosa.push(glosa);

            listaPagoProgramacion.push([fechaPago, importePago, dias, porcentaje, glosa, pagoProgramacionId]);
        });

        select2.asignarValor('cboTipoPago', 2);
        aceptarProgramacion('false');
    }
}

function asignarImportePago() {

    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
    if (!isEmpty(dtdTipoPago) && !isEmpty(listaPagoProgramacion)) {

        var porcentaje;
        var importePago;
        $.each(listaPagoProgramacion, function (index, item) {
            porcentaje = item[3];
            importePago = (calculoTotal * porcentaje) / 100;
            listaPagoProgramacion[index][1] = importePago;
        });

        aceptarProgramacion(false);
    }

}

function abrirModalConfirmacion() {
    $('#modalConfirmacionRegistro').modal('show');
}

//seccion PAGOS
var camposDinamicosPagoEfectivo = [];
function abrirModalPagos(data) {
    habilitarBoton();
    var dataDP = data.dataDocumentoPago;

    if (select2.obtenerValor("cboMoneda") == 4) {
        $("#contenedorTipoCambioDiv").show();
    } else {
        $("#contenedorTipoCambioDiv").hide();
    }

    $("#contenedorEfectivo").show();

    $("#divCboActividadEfectivo").hide();
    $("#divCboCuentaEfectivo").hide();

    if (!isEmpty(dataDP.documento_tipo)) {
        $("#cboDocumentoTipoNuevoPagoConDocumento").select2({
            width: "100%"
        }).on("change", function (e) {
            $("#contenedorEfectivo").hide();
            loaderShow("#modalNuevoDocumentoPagoConDocumento");
            if (e.val == 282) {
                obtenerFormularioEfectivo();
            } else {
                obtenerDocumentoTipoDatoPago(e.val);
            }
        });
        select2.cargar("cboDocumentoTipoNuevoPagoConDocumento", dataDP.documento_tipo, "id", "descripcion");
        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", 282);
        //        $('#cboDocumentoTipoNuevoPagoConDocumento').append('<option value="0">Efectivo</option>');
        //        select2.asignarValor("cboDocumentoTipoNuevoPagoConDocumento", 0);
        if (dataDP.documento_tipo.length === 1) {
            select2.readonly("cboDocumentoTipoNuevoPagoConDocumento", true);
        }
        camposDinamicosPagoEfectivo = dataDP.documento_tipo_conf;
        onResponseObtenerDocumentoTipoDatoPago(dataDP.documento_tipo_conf);
        obtenerFormularioEfectivo();
    }

    //llenado de la actividad    
    select2.cargar('cboActividadEfectivo', data.actividad, 'id', ['codigo', 'descripcion']);
    select2.asignarValor('cboActividadEfectivo', data.actividad[0].actividad_defecto);
    $("#cboActividadEfectivo").prop("disabled", true);

    select2.cargar('cboCuentaEfectivo', data.cuenta, 'cuenta_id', 'descripcion_numero');
    select2.asignarValor('cboCuentaEfectivo', data.cuenta[0].cuenta_defecto);
    $("#cboActividadEfectivo").prop("disabled", true);
}


function obtenerFormularioEfectivo() {
    $("#formNuevoDocumentoPagoConDocumento").empty();
    $("#contenedorDocumentoTipoNuevo").css("height", 0);

    $("#contenedorEfectivo").show();
    loaderClose();

    let montoPendientePago = redondearDosDecimales(obtenerMontoAPagar() - obtenerTotalPagoTemporal());

    $("#txtMontoAPagar").val(montoPendientePago);
}

$("#tipoCambio").prop("disabled", true);
$("#checkBP").click(function () {
    var checked = $(this).is(":checked");
    if (!checked) {
        $("#tipoCambio").prop("disabled", true);
        return true;
    }
    obtenerTipoCambioDatepicker();
    $("#tipoCambio").prop("disabled", false);
    return true;
});

$('#txtPagaCon').keyup(function () {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
});
$('#txtMontoAPagar').keyup(function () {
    var monto = parseFloat($('#txtMontoAPagar').val());
    var pago = parseFloat($('#txtPagaCon').val());
    var vuelto = pago - monto;
    $('#txtVuelto').val(vuelto.toFixed(2));
    //    $('#txtVuelto').val(formatearNumero(vuelto));
});

function obtenerDocumentoTipoDatoPago(documentoTipoId) {
    ax.setAccion("obtenerDocumentoTipoDatoPago");
    ax.addParamTmp("documentoTipoId", documentoTipoId);
    ax.consumir();
}

var camposDinamicosPago = [];
var personaNuevoId;
var totalPago;
function onResponseObtenerDocumentoTipoDatoPago(data) {
    camposDinamicosPago = [];
    personaNuevoId = 0;
    $("#formNuevoDocumentoPagoConDocumento").empty();
    if (!isEmpty(data)) {

        let contadorOcultos = 0;
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {
            appendFormNuevo('<div class="row">');
            let hiddenImput = '';
            if (item.tipo == 5 || item.tipo == 21) {
                hiddenImput = 'hidden=""';
                contadorOcultos = contadorOcultos + 1;
            }

            var html = '<div class="form-group col-md-12" ' + hiddenImput + ' >' +
                '<label>' + item.descripcion + ' ' + ((item.opcional == 0) ? '*' : '') + '</label>';
            //            if (item.tipo == 5)
            //            {
            //                html += '<span class="divider"></span> <a onclick="cargarPersona();"><i class="ion-person-add" tooltip-btndata-toggle="tooltip" title="Agregar proveedor"></i></a>';
            //            }
            html += '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            camposDinamicosPago.push({
                id: item.id,
                tipo: parseInt(item.tipo),
                opcional: item.opcional,
                descripcion: item.descripcion
            });
            switch (parseInt(item.tipo)) {
                case 1:
                case 14:

                    let montoPendientePago = redondearDosDecimales(obtenerMontoAPagar() - obtenerTotalPagoTemporal());
                    totalPago = 'txtnd_' + item.id;
                    html += '<input type="number" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" class="form-control" value="' + montoPendientePago + '" maxlength="45" style="text-align:right; "/>';
                    break;
                case 2:
                case 6:
                case 7:
                case 8:
                case 12:
                case 13:

                    var readonly = (parseInt(item.editable) === 0) ? 'readonly="true"' : '';
                    var value = '';
                    // Número autoincrementable
                    if (parseInt(item.tipo) === 8 && !isEmpty(item.data)) {
                        value = item.data;
                    } else if (!isEmpty(item.cadena_defecto)) {
                        value = item.cadena_defecto;
                    }
                    html += '<input type="text" id="txtnd_' + item.id + '" name="txtnd_' + item.id + '" ' + readonly + '" class="form-control" value="' + value + '" maxlength="45"/>';
                    break;
                case 3:
                case 10:
                case 11:
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + item.data + '">' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                //FECHA DE EMISION DESHABILITADO Y FECHA DE EMISION DEL DOCUMENTO
                case 9:
                    var fechaEmision = item.data;
                    var dtdFechaEmision = obtenerDocumentoTipoDatoIdXTipo(9);
                    if (!isEmpty(dtdFechaEmision)) {
                        fechaEmision = $('#datepicker_' + dtdFechaEmision).val();
                    }
                    html += '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="datepickernd_' + item.id + '" value = "' + fechaEmision + '" disabled>' +
                        '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
                    break;
                case 4:
                    html += '<select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2"></select>';
                    break;
                case 5:
                    html += '<div id ="div_proveedor"><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la persona</option>';
                    $.each(item.data, function (indexPersona, itemPersona) {
                        html += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
                    });
                    html += '</select>';
                    personaNuevoId = item.id;
                    break;
                case 20:
                    html += '<div id ="div_cuenta" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la cuenta</option>';
                    $.each(item.data, function (indexCuenta, itemCuenta) {
                        html += '<option value="' + itemCuenta.id + '">' + itemCuenta.descripcion_numero + '</option>';
                    });
                    html += '</select>';
                    break;
                case 21:
                    html += '<div id ="div_actividad" ><select name="cbond_' + item.id + '" id="cbond_' + item.id + '" class="select2">';
                    html += '<option value="' + 0 + '">Seleccione la actividad</option>';
                    $.each(item.data, function (indexActividad, itemActividad) {
                        html += '<option value="' + itemActividad.id + '">' + itemActividad.codigo + ' | ' + itemActividad.descripcion + '</option>';
                    });
                    html += '</select>';
                    break;
            }
            html += '</div></div>';
            appendFormNuevo(html);
            appendFormNuevo('</div>');
            switch (item.tipo * 1) {
                case 4:
                    select2.cargar("cbond_" + item.id, item.data, "id", "descripcion");
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });
                    break;
                case 5:
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });


                    //                    select2.asignarValor("cbond_" + item.id, select2.obtenerValor("cboCliente"));

                    break;
                case 20:
                case 21:
                    $("#cbond_" + item.id).select2({
                        width: '100%'
                    });

                    if (!isEmpty(item.numero_defecto)) {
                        var id = parseInt(item.numero_defecto);
                        select2.asignarValor("cbond_" + item.id, id);
                    }

                    if (item.editable == 0) {
                        $("#cbond_" + item.id).attr('disabled', 'disabled');
                    }
                    break;
            }
        });
        var clienteId = select2.obtenerValor('cbo_' + obtenerDocumentoTipoDatoIdXTipo(5));
        if (personaNuevoId > 0 && clienteId > 0) {
            select2.asignarValor('cbond_' + personaNuevoId, clienteId);
        }
        $('.fecha').datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es'
        });
        var fecha_ = $('#datepicker_fechaPago').val();
        $("#contenedorDocumentoTipoNuevo").css("height", 75 * (data.length - contadorOcultos));
    }
    //habilitarBotonGeneral("btnNuevoC")
    $('#modalNuevoDocumentoPagoConDocumento').modal('show');
    loaderClose("#modalNuevoDocumentoPagoConDocumento");

}

function appendFormNuevo(html) {
    $("#formNuevoDocumentoPagoConDocumento").append(html);
}

function obtenerValoresCamposDinamicosPago() {
    var isOk = true;
    if (isEmpty(camposDinamicosPago))
        return false;
    $.each(camposDinamicosPago, function (index, item) {
        //string
        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:
                if (item.tipo == 14) {
                    totalPago = "txtnd_" + item.id;
                }
                camposDinamicosPago[index]["valor"] = document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                camposDinamicosPago[index]["valor"] = document.getElementById("datepickernd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
            case 20:// cuenta
            case 21:// actividad
                let valorObtenido;
                if (item.tipo == 5) { // Persona a pagar.
                    valorObtenido = obtenerPersonaId();
                } else {
                    valorObtenido = select2.obtenerValor('cbond_' + item.id);
                }
                camposDinamicosPago[index]["valor"] = valorObtenido;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPago[index]["valor"]) ||
                        camposDinamicosPago[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                        camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
        }
    });
    return isOk;
}

function obtenerValoresCamposDinamicosPagoEfectivo() {
    var isOk = true;
    if (isEmpty(camposDinamicosPagoEfectivo))
        return false;
    $.each(camposDinamicosPagoEfectivo, function (index, item) {
        let valorObtenido = null;
        //string
        switch (item.tipo) {
            case 1:
            case 2:
            case 6:
            case 7:
            case 8:
            case 12:
            case 13:
            case 14:


                if (item.tipo == 14) {
                    valorObtenido = document.getElementById("txtMontoAPagar").value;
                    totalPago = "txtMontoAPagar";
                } else if (item.tipo == 2 && item.codigo == 1) {
                    valorObtenido = document.getElementById("txtPagaCon").value;
                } else if (item.tipo == 2 && item.codigo == 2) {
                    valorObtenido = document.getElementById("txtVuelto").value;
                }

                camposDinamicosPagoEfectivo[index]["valor"] = valorObtenido; //document.getElementById("txtnd_" + item.id).value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            //fechas
            case 3:
            case 9:
            case 10:
            case 11:
                camposDinamicosPagoEfectivo[index]["valor"] = document.getElementById("txtFechaEmision").value;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"])) {
                        mostrarValidacionLoaderClose("Debe ingresar" + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 4:
            case 5:// persona
            case 20:// cuenta
            case 21:// actividad  
                if (item.tipo == 5) { // Persona a pagar.                    
                    valorObtenido = obtenerPersonaId();
                } else if (item.tipo == 21) { //Actividad
                    valorObtenido = select2.obtenerValor("cboActividadEfectivo");
                } else if (item.tipo == 20) { //Cuenta destino
                    valorObtenido = select2.obtenerValor("cboCuentaEfectivo");
                }

                camposDinamicosPagoEfectivo[index]["valor"] = valorObtenido;
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicosPagoEfectivo[index]["valor"]) ||
                        camposDinamicosPagoEfectivo[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        loaderClose();
                        habilitarBoton();
                        isOk = false;
                        return false;
                    }
                }
                break;
            case 36:// detraccion
                camposDinamicos[index]["valor"] = select2.obtenerValor('cbo_' + item.id);
                if (item.opcional == 0) {
                    //validamos
                    if (isEmpty(camposDinamicos[index]["valor"]) ||
                        camposDinamicos[index]["valor"] == 0) {
                        mostrarValidacionLoaderClose("Debe ingresar " + item.descripcion);
                        isOk = false;
                        return false;
                    }
                }
                break;
        }
    });
    return isOk;
}



function validarSumaCantidades() {
    var state = true;
    var a = $("#tbodyProductosDetalles tr");
    $.each(a, function (index, value) {
        var TDS = $(this).find('td');
        var maxQty = $(this).find("td:nth-child(2)").text();
        var currentQty = 0;
        $.each(TDS, function (indice, valor) {
            //var cajitas =  $("input[type='number']");
            var cajitas = $(this).find("input[type='number']");
            $.each(cajitas, function (i, o) {
                currentQty += parseInt($(this).val());
            });

        });

        if (currentQty > parseInt(maxQty)) {
            state = false;
        }
    });
    return state;
}
//guardar el documento y ATENCION DE SOLICITUDES
function guardarDocumentoAtencionSolicitud() {

    if (validarSumaCantidades()) {
        //parte documento operacion
        loaderShow("#modalAsignarAtencion");

        var documentoTipoId = select2.obtenerValor("cboDocumentoTipo");
        if (isEmpty(documentoTipoId)) {
            mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
            return;
        }

        obtenerCheckDocumentoACopiar();

        var checkIgv = 0;

        if (!isEmpty(importes.subTotalId)) {
            if (document.getElementById('chkIncluyeIGV').checked) {
                checkIgv = 1;
            }
        } else {
            checkIgv = opcionIGV;
        }

        var tipoPago = null;

        var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
        if (!isEmpty(dtdTipoPago)) {
            tipoPago = select2.obtenerValor("cboTipoPago");
        }

        if (tipoPago != 2) {
            listaPagoProgramacion = [];
        }
        //fin documento operacion

        ax.setAccion("guardarDocumentoAtencionSolicitud");
        //documento operacion
        ax.addParamTmp("documentoTipoId", documentoTipoId);
        ax.addParamTmp("camposDinamicos", camposDinamicos);
        // ax.addParamTmp("detalle", detalle);
        ax.addParamTmp("detalle", detalleDos);
        ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
        ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
        ax.addParamTmp("comentario", $('#txtComentario').val());
        ax.addParamTmp("checkIgv", checkIgv);
        ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
        ax.addParamTmp("accionEnvio", boton.accion);
        ax.addParamTmp("tipoPago", tipoPago);
        ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
        //------------------------
        //documento atencion solicitud
        ax.addParamTmp("atencionSolicitudes", nArray);
        ax.consumir();
    } else {
        mostrarAdvertencia("Las cantidades ingresadas superan a las cantidades disponibles en la orden.");
    }

}

function guardarDocumentoPago() {
    guardar("guardarPago");
}

function obtenerArraySinReferencia(data) {
    let array = [];
    $.each(data, function (index, item) {
        array.push(Object.assign({}, item));
    });
    return array;
}

function obtenerTotalPagoTemporal() {
    let totalCamposDinamicos = 0;
    if (!isEmpty(camposDinamicosPagoTemporal)) {
        $.each(camposDinamicosPagoTemporal, function (index, item) {
            totalCamposDinamicos = totalCamposDinamicos + ((item.data.filter(itemFilter => itemFilter.tipo == 14)[0]['valor']) * 1);
        });
    }
    return totalCamposDinamicos;
}

var camposDinamicosPagoTemporal = [];
function guardarDocumentoPagoTemporal() {

    let totalCamposDinamicos = obtenerTotalPagoTemporal();

    let montoTotalDocumento = obtenerMontoAPagar();


    let documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
    let documentoTipoIdPagoDescripcion = select2.obtenerText("cboDocumentoTipoNuevoPagoConDocumento");
    //Validar y obtener valores de los campos dinamicos
    if (documentoTipoIdPago != 282) {
        if (!obtenerValoresCamposDinamicosPago()) {
            return;
        }

        let montoTotal = camposDinamicosPago.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        if ((totalCamposDinamicos * 1) + (montoTotal * 1) > montoTotalDocumento) {
            mostrarValidacionLoaderClose("El monto máximo a pagar es " + formatearNumero(montoTotalDocumento));
            return;
        }
        camposDinamicosPagoTemporal.push({
            documento_tipo_id: documentoTipoIdPago,
            documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
            monto: montoTotal,
            data: obtenerArraySinReferencia(camposDinamicosPago)
        });
    } else {
        if (!obtenerValoresCamposDinamicosPagoEfectivo()) {
            return;
        }
        let montoTotal = camposDinamicosPagoEfectivo.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        if ((totalCamposDinamicos * 1) + (montoTotal * 1) > montoTotalDocumento) {
            mostrarValidacionLoaderClose("El monto máximo a pagar es " + formatearNumero(montoTotalDocumento));
            return;
        }
        camposDinamicosPagoTemporal.push({
            documento_tipo_id: documentoTipoIdPago,
            documento_tipo_descripcion: documentoTipoIdPagoDescripcion,
            monto: montoTotal,
            data: obtenerArraySinReferencia(camposDinamicosPagoEfectivo)
        });
    }

    cargarDetallePago();

    $("#contenedorEfectivo").hide();
    loaderShow("#modalNuevoDocumentoPagoConDocumento");
    if (documentoTipoIdPago == 282) {
        setTimeout(function () {
            obtenerFormularioEfectivo();
        }, 1000);
    } else {
        obtenerDocumentoTipoDatoPago(documentoTipoIdPago);
    }
}

function cargarDetallePago() {
    $("#tablaDocumentoPagoAcumulado").empty();
    let montoTotal = 0;
    $.each(camposDinamicosPagoTemporal, function (index, item) {
        let itemTotal = item.data.filter(itemFilter => itemFilter.tipo == 14)[0]['valor'];
        montoTotal = (montoTotal * 1) + (itemTotal * 1);
        $('#tablaDocumentoPagoAcumulado').append("<a id='cerrarLink_" + index + "' onclick='eliminarDocumentoPagoTemporal(" + index + ")'style='color:red;'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp");
        $('#tablaDocumentoPagoAcumulado').append("<a id='link_" + index + "' onclick='' style='color:#0000FF;text-align: right;'>[" + item.documento_tipo_descripcion + "&nbsp&nbsp&nbsp" + formatearNumero(itemTotal) + "]</a><br>");
        $("#tablaDocumentoPagoAcumulado").css("height", $("#linkDocumentoACopiar").height() + 20);
    });
}

function eliminarDocumentoPagoTemporal(indice) {
    camposDinamicosPagoTemporal.splice(indice, 1);
    cargarDetallePago();
}
//guardar el documento y pago
//function guardarDocumentoPago() {
//    //deshabilitarBoton();
//    //parte documento operacion

//    loaderShow("#modalNuevoDocumentoPagoConDocumento");
//
//    var documentoTipoId = select2.obtenerValor("cboComprobanteTipo");
//    if (isEmpty(documentoTipoId)) {
//        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
//        return;
//    }
//
////    obtenerCheckDocumentoACopiar();
//
//    var checkIgv = 0;
//
//    if (!isEmpty(importes.subTotalId)) {
//        if (document.getElementById('chkIGV').checked) {
//            checkIgv = 1;
//        }
//    } else {
//        checkIgv = opcionIGV;
//    }
//
//    var tipoPago = null;
//
//    var dtdTipoPago = obtenerDocumentoTipoDatoIdXTipo(25);
//    if (!isEmpty(dtdTipoPago)) {
//        tipoPago = 1;
//    }
//
//    if (tipoPago != 2) {
//        listaPagoProgramacion = [];
//    }
//
//    //fin documento operacion    
//
//    //parte documento pago    
//    //obtenemos el tipo de documento
//    var documentoTipoIdPago = select2.obtenerValor("cboDocumentoTipoNuevoPagoConDocumento");
//    if (isEmpty(documentoTipoIdPago)) {
//        mostrarValidacionLoaderClose("Debe ingresar tipo de documento");
//        return;
//    }
//
//    //Validar y obtener valores de los campos dinamicos
//    if (documentoTipoIdPago != 0) {
//        if (!obtenerValoresCamposDinamicosPago())
//            return;
//    } else {
//        camposDinamicosPago = null;
//    }
//    //fin documento pago    
//
//    //datos de registro de pago
//    var cliente = select2.obtenerValor('cbo_' + obtenerDocumentoTipoDatoIdXTipo(5));
//    var tipoCambio = $('#tipoCambio').val();
//    var fecha = $('#txtFechaEmision').val();
//    var retencion = null;
//
//    // efectivo a pagar
//    var montoAPagar = $('#txtMontoAPagar').val();
//    if (isEmpty(montoAPagar)) {
//        montoAPagar = 0;
//    }
//
//    if (montoAPagar == 0 && documentoTipoIdPago == 0) {
//        mostrarValidacionLoaderClose("Debe ingresar monto a pagar en efectivo");
//        return;
//    }
//
//    var pagarCon = $('#txtPagaCon').val();
//    var vuelto = $('#txtVuelto').val();
//
//    var actividadEfectivo = $('#cboActividadEfectivo').val();
//
//    //validar total de documento de pago = total de documento a pagar.
//    var banderaTotalPago = true;
//    if (documentoTipoIdPago == 0) {
//        if (parseFloat(montoAPagar) != parseFloat(calculoTotal)) {
//            banderaTotalPago = false;
//        }
//    } else {
//        if (parseFloat($('#' + totalPago).val()) != parseFloat(calculoTotal)) {
//            banderaTotalPago = false;
//        }
//    }
//
//    if (!banderaTotalPago) {
//        mostrarValidacionLoaderClose("El monto de pago debe ser igual al monto total del documento.");
//        return;
//    }
//
//    var periodoId = select2.obtenerValor('cboPeriodo');
//
////        deshabilitarBoton();
//    ax.setAccion("guardarDocumentoPago");
//    //documento operacion    
//    ax.addParamTmp("documentoTipoId", documentoTipoId);
//    ax.addParamTmp("camposDinamicos", camposDinamicos);
//    ax.addParamTmp("detalle", detalle);
//    ax.addParamTmp("documentoARelacionar", request.documentoRelacion);
//    ax.addParamTmp("valor_check", cabecera.chkDocumentoRelacion);
//    ax.addParamTmp("comentario", $('#txtComentario').val());
//    ax.addParamTmp("checkIgv", checkIgv);
//    ax.addParamTmp("monedaId", select2.obtenerValor("cboMoneda"));
//    ax.addParamTmp("accionEnvio", boton.accion);
//    ax.addParamTmp("tipoPago", tipoPago);
//    ax.addParamTmp("listaPagoProgramacion", listaPagoProgramacion);
//    //------------------------
//
//    //documento pago
//    ax.addParamTmp("documentoTipoIdPago", documentoTipoIdPago);
//    ax.addParamTmp("camposDinamicosPago", camposDinamicosPago);
//
//    //registro de pago        
//    ax.addParamTmp("cliente", cliente);
//    ax.addParamTmp("montoAPagar", montoAPagar);
//    ax.addParamTmp("tipoCambio", tipoCambio);
//    ax.addParamTmp("fecha", fecha);
//    ax.addParamTmp("retencion", retencion);
//    ax.addParamTmp("actividadEfectivo", actividadEfectivo);
//    ax.addParamTmp("empresaId", commonVars.empresa);
//
//    //totales
//    ax.addParamTmp("totalDocumento", $('#' + importes.totalId).val());
//    ax.addParamTmp("totalPago", $('#' + totalPago).val());
//
//    ax.addParamTmp("periodoId", periodoId);
//    ax.consumir();
//}

function reenumerarFilasDetalle(indice, numItemActual) {
    var numItem = numItemActual;
    for (var i = (indice + 1); i < nroFilasReducida; i++) {
        if ($('#txtNumItem_' + i).length > 0) {
            $('#txtNumItem_' + i).html(numItem);
            numItem++;
        }
    }

    numeroItemFinal--;
}

function dibujarAtencionLeftSide() {
    $("#theadProductosDetalles").append("<th>Producto</th><th>Cantidad</th>");
    var html = '';
    for (var i = 0; i < detalle.length; i++) {
        html += '<tr>';
        html += '<td>' + detalle[i].bienDesc + '</td>';
        html += '<td style="text-align: right;">' + (detalle[i].cantidad / 1) + '</td>';
        html += '<td style="display:none;">' + detalle[i].bienId + '</td>';
        html += '</tr>';
    }
    $('#tbodyProductosDetalles').append(html);
}


// God helps your poor soul understand the code you are about to see :D
var count = 0;
var headersNoRepetir = new Set();
var htmlUniqueHeaders = new Set();
var onClickHeaderAtencion;
var dataMapaHeaders = new Map();
var mapaHeaders = new Map();
var dataMapaEstadoHeaders = new Map();
var mapaEstadoHeaders = new Map();
var dataMapaCantidadesValidacion, mapaCantidadesValidacion = new Map();
var dataMapaCantidadesAsignacion, mapaCantidadesAsignacion = new Map();
var showModal = true;
var arrayBienIdCorrectos = [];
var globalAS;
var sub = 0;
function cancelarModalAsignacion() {
    detalle = detalleDos;
}



var arrayCantidades = new Array();
var mapaBienId = new Map();
var nArray;
function asignar() {

    mapaBienId.clear();
    arrayCantidades = [];
    var allInputIds = $('#tbodyProductosDetalles input').map(function (index, dom) {
        return dom.id
    });
    for (var i = 0; i < allInputIds.length; i++) {
        var splat = (allInputIds[i].replace('txt', "")).split("_");
        var idSolic = splat[0];
        var result = globalAS.filter(function (obj) {
            return obj.documentoId == idSolic;
        });
        if (!isEmpty(result)) {
            var search = "#" + idSolic + "_" + splat[1] + " input";
            var inputGroup = $(search);
            for (var o = 0; o < result[0].detalleBien.length; o++) {

                if (parseInt(inputGroup.last().attr('value')) == parseInt(result[0].detalleBien[o].bien_id)) {

                    if (mapaBienId.has(parseInt(result[0].detalleBien[o].bien_id))) {
                        var localArray = mapaBienId.get(parseInt(result[0].detalleBien[o].bien_id));
                        localArray.push({
                            'cantidad': inputGroup.first().val(),
                            'mov_bien_ant_id': result[0].detalleBien[o].movimiento_bien_id
                        });
                        mapaBienId.set(parseInt(result[0].detalleBien[o].bien_id), localArray);
                    } else {
                        arrayCantidades.push({
                            'cantidad': inputGroup.first().val(),
                            'mov_bien_ant_id': result[0].detalleBien[o].movimiento_bien_id
                        });
                        mapaBienId.set(parseInt(result[0].detalleBien[o].bien_id), arrayCantidades);
                        arrayCantidades = [];
                    }

                }
            }
        } else {
            //do nothing xdxd
        }
    }
    nArray = Array.from(mapaBienId);
    guardarDocumentoAtencionSolicitud();
}

//OPCION PARA REFRESCAR PRODUCTO
function cargarBien() {
    var rutaAbsoluta = URL_BASE + 'index.php?token=2';
    window.open(rutaAbsoluta, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1500, height=900");
}

function actualizarComboProducto(indice) {
    loaderShow();
    ax.setAccion("obtenerProductos");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.setTag(indice);
    ax.consumir();
}


function obtenerXAgenciaIdXPersonaId() {

    let agenciaOrigenId = select2.obtenerValor("cboAgenciaOrigen");
    let agenciaDestinoId = select2.obtenerValor("cboAgenciaDestino");
    let personaId = obtenerPersonaId();

    dataCofiguracionInicial.bien = [];
    dataCofiguracionInicial.dataTarifario = [];

    loaderShow();
    ax.setAccion("obtenerXAgenciaIdXPersonaId");
    ax.addParamTmp("agenciaOrigenId", agenciaOrigenId);
    ax.addParamTmp("agenciaDestinoId", agenciaDestinoId);
    ax.addParamTmp("personaId", personaId);
    //    ax.setTag(indice);
    ax.consumir();
}

function onResponseObtenerXAgenciaIdXPersonaId(data) {
    let dataBien = (!isEmpty(data.bien) ? data.bien : []);
    dataCofiguracionInicial.dataTarifario = data.tarifario;
    dataCofiguracionInicial.bien = data.bien;
    if (!isEmpty(detalle)) {
        detalle = [];
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Debido al cambio de precios debe volver a ingresar el detalle del pedido.");
    }

    cargarBienDetalleCombo(dataBien);

    dibujarTabla();
    onChangeCheckDevolucionCargo();
    asignarImporteDocumento();

}

function onResponseObtenerProductos(data, indice) {
    cargarBienDetalleCombo(data, indice);
}

function iniciarArchivoAdjunto() {
    $("#archivoAdjunto").change(function () {
        $("#nombreArchivo").html($('#archivoAdjunto').val().slice(12));
        //llenado del popover
        $('#idPopover').attr("data-content", $('#archivoAdjunto').val().slice(12));
        $('[data-toggle="popover"]').popover('show');
        $('.popover-content').css('color', 'black');
        $('[class="popover fade top in"]').css('z-index', '0');
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
}

var lstDocumentoArchivos = [];
var lstDocEliminado = [];
var cont = 0;
var ordenEdicion = 0;
function eliminarDocumento(docId) {
    ordenEdicion = 0;
    lstDocumentoArchivos.some(function (item) {
        if (item.id == docId) {
            lstDocumentoArchivos.splice(ordenEdicion, 1);
            lstDocEliminado.push([{ id: docId, archivo: item.archivo }])
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
            return item.id === docId;
        }
        ordenEdicion++;
    });
}

$("#btnAgregarDoc").click(function fileIsLoaded(e) {
    var documento = {};
    if (!isEmpty($("#archivoAdjuntoMulti").val())) {
        if ($("#archivoAdjuntoMulti").val().slice(12).length > 0) {
            documento.data = $("#dataArchivoMulti").val();
            documento.archivo = $("#archivoAdjuntoMulti").val().slice(12);
            documento.id = "t" + cont++;
            lstDocumentoArchivos.push(documento);
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
            $("#archivoAdjuntoMulti").val("");
            $("#dataArchivoMulti").val("");
            $('[data-toggle="popover"]').popover('hide');
            $('#idPopoverMulti').attr("data-content", "");
            $("#msjDocumento").html("");
            $("#msjDocumento").hide();
        } else {
            onResponseListarArchivosDocumento(lstDocumentoArchivos);
        }

    } else {
        $("#msjDocumento").html("Debe adjuntar un archivo primero para agregarlo a la lista");
        $("#msjDocumento").show();
    }

});
function onResponseListarArchivosDocumento(data) {
    $("#dataList2").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cabeza = "<table id='datatable3' class='table table-striped table-bordered'>"
        + "<thead>"
        + "<tr>"
        + "<th style='text-align:center; vertical-align: middle; width:20%'>#</th>"
        + "<th style='text-align:center; vertical-align: middle;'>Nombre</th>"
        + "<th style='text-align:center; vertical-align: middle; width:15%'>Acciones</th>"
        + "</tr>"
        + "</thead>";
    if (!isEmpty(data)) {

        $.each(data, function (index, item) {
            if (!item.id.match(/t/g)) {
                lstDocumentoArchivos[index]["data"] = "util/uploads/documentoAdjunto/" + item.nombre;
            }

            cuerpo = "<tr>"
                + "<td style='text-align:center;'>" + (index + 1) + "</td>"
                + "<td style='text-align:center;'>" + item.archivo + "</td>";
            cuerpo += "<td style='text-align:center;'>"
                + "<a href='" + item.data + "' download='" + item.archivo + "' target='_blank'><i class='fa fa-cloud-download' style='color:#1ca8dd;'></i></a>&nbsp;\n"
                + "<a  onclick='eliminarDocumento(\"" + item.id + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n"
                + "</td>"
                + "</tr>";
            cuerpo_total += cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList2").append(html);
    $("#datatable3").DataTable();
}

function imageIsLoaded(e) {
    $('#dataArchivo').attr('value', e.target.result);
}

function adjuntar() {
    onResponseListarArchivosDocumento(lstDocumentoArchivos);
    $('#tituloVisualizarModalArchivos').html("Adjuntar archivos");
    $('#modalVisualizarArcvhivos').modal('show');
}

function iniciarArchivoAdjuntoMultiple() {
    $("#archivoAdjuntoMulti").change(function () {
        $("#nombreArchivoMulti").html($('#archivoAdjuntoMulti').val().slice(12));
        //llenado del popover
        $('#idPopoverMulti').attr("data-content", $('#archivoAdjuntoMulti').val().slice(12));
        $('[data-toggle="popover"]').popover('show');
        $('.popover-content').css('color', 'black');
        $('[class="popover fade top in"]').css('z-index', '0');
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoadedMulti;
            reader.readAsDataURL(this.files[0]);
        }
    });
}

function imageIsLoadedMulti(e) {
    $('#dataArchivoMulti').attr('value', e.target.result);
}

// Anticipos
function mostrarAnticipos(data) {
    var anticipos = data.anticipos;
    var actividades = data.actividades;
    bandera.validacionAnticipos = 1;
    mostrarModalAnticipo();
    $("#dtAnticipos").dataTable({
        order: [[1, "desc"]],
        "ordering": false,
        "data": anticipos,
        "columns": [
            { "data": "documento_id", sClass: "columnAlignCenter" },
            { "data": "serie" },
            { "data": "fecha_emision" },
            { "data": "descripcion" },
            { "data": "pendiente", "sClass": "alignRight" }
        ],
        columnDefs: [
            {
                "render": function (data, type, row) {
                    return '<label class="cr-styled"><input type="checkbox" id="chkAnticipo_' + data + '"><i class="fa"></i></label>';
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    return formatearFechaBDCadena(data);
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    return row.simbolo + ' ' + parseFloat(data).formatMoney(2, '.', ',');
                },
                "targets": 4
            }
        ],
        "dom": '<"top">rt<"bottom"><"clear">',
        "destroy": true
    });
    dataTemporal.anticipos = anticipos;
    //    select2.iniciarElemento("cboAnticipoActividad");
    //    select2.cargar("cboAnticipoActividad", actividades, "id", "descripcion");
}

function limpiarAnticipos() {
    dataTemporal.anticipos = null;
    bandera.validacionAnticipos = 2;
    enviar(boton.accion);
}
function aplicarAnticipos() {
    // Validamos 
    // Si se ha seleccionado algun anticipo, debe de haberse seleccionado una cuenta
    var validaActividad = false;
    $.each(dataTemporal.anticipos, function (indexAnticipo, itemAnticipo) {
        if (document.getElementById('chkAnticipo_' + itemAnticipo.documento_id).checked) {
            validaActividad = true;
            return false;
        }
    });
    if (!validaActividad) {
        mostrarAdvertencia("No ha seleccionado ningún anticipo");
        return;
    }
    bandera.validacionAnticipos = 3;
    // Enviamos a guardar
    enviar(boton.accion);
}
function deshabilitaBotonesAnticipos() {
    $('#btnLimpiaAnticipos').prop('disabled', true);
    $('#btnAplicaAnticipos').prop('disabled', true);
}
function obtenerAnticiposAAplicar() {
    if (!isEmpty(dataTemporal.anticipos)) {
        var anticiposAplicar = [];
        $.each(dataTemporal.anticipos, function (indexAnticipo, itemAnticipo) {
            if (document.getElementById('chkAnticipo_' + itemAnticipo.documento_id).checked) {
                anticiposAplicar.push({ documentoId: itemAnticipo.documento_id, pendiente: itemAnticipo.pendiente });
            }
        });
        return anticiposAplicar;
    } else {
        return null;
    }
}
function cerrarModalAnticipo() {
    if (bandera.validacionAnticipos == 1) {
        $('#modalAnticipos').modal('hide');
        $('.modal-backdrop').hide();
    }
}
function mostrarModalAnticipo() {
    $('#modalAnticipos').modal({ backdrop: 'static', keyboard: false });
    $('#modalAnticipos').modal('show');
}

function cambiarPeriodo() {
    var periodoId = obtenerPeriodoIdXFechaEmision();
    select2.asignarValorQuitarBuscador('cboPeriodo', periodoId);
}

function obtenerPeriodoIdXFechaEmision() {
    var periodoId = null;
    var fechaEmision = $('#txtFechaEmision').val();
    var fechaArray = fechaEmision.split('/');
    var d = parseInt(fechaArray[0], 10);
    var m = parseInt(fechaArray[1], 10);
    var y = parseInt(fechaArray[2], 10);
    $.each(dataCofiguracionInicial.periodo, function (index, item) {
        if (item.anio == y && item.mes == m) {
            periodoId = item.id;
        }
    });
    return periodoId;
}

function obtenerDocumentoTipoDatoXTipoXCodigo(tipo, codigo) {
    var dataConfig = dataCofiguracionInicial.documento_tipo_conf;
    var dtd = null;
    if (!isEmpty(dataConfig)) {
        $.each(dataConfig, function (index, item) {
            if (parseInt(item.tipo) === parseInt(tipo) && item.codigo == codigo) {
                dtd = item;
                return false;
            }
        });
    }

    return dtd;
}

function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
    $("#ulBuscadorDesplegable2").width((ancho - 5) + "px");
}

function onChangeOrganizadorDestino() {
    if (!isEmpty(obtenerDocumentoTipoDatoIdXTipo(17))) {
        obtenerDireccionOrganizador(2);
    }
}
/************************************ DISTRIBUCION CONTABLE *************************************/

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    if ((target == '#distribucion')) {
        var monto = obtenerSubTotal_Total_Distribucion();
        if (!isEmpty(importes.subTotalId) && monto * 1 < 0) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero debe ingresar el sub total para iniciar con la distribución contable.');
        } else if (monto * 1 < 0) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero debe ingresar el monto total para iniciar con la distribución contable.');
        } else if (isEmpty(select2.obtenerValor('cboOperacionTipo'))) {
            $('#tabDetalle').click();
            mostrarAdvertencia('Primero seleccione el tipo de operación.');
        }
    }
});
var nroFilasDistribucion = 0;
function llenarCabeceraDistribucion() {
    $('#headDetalleCabeceraDistribucion').empty();
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];
    //    var operacionTipo = dataContOperacionTipo[document.getElementById('cboOperacionTipo').options.selectedIndex];
    var fila = ' <tr role="row">';
    fila += '<th style="text-align: center; width: 5%;" class="sorting_disabled" rowspan="1" colspan="1">#</th>';
    fila += '<th style="text-align: center; width: 30%;" class="sorting_disabled" rowspan="1" colspan="1">Cuenta Contable</th>';
    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        fila += '<th style="text-align: center; width: 30%;" class="sorting_disabled" rowspan="1" colspan="1">Centro Costo</th>';
    }
    fila += '<th style="text-align:center; width: 15%;" class="sorting_disabled" rowspan="1" colspan="1">Procentaje(%)</th>';
    fila += '<th style="text-align:center; width: 15%;" class="sorting_disabled" rowspan="1" colspan="1">Monto</th>';
    fila += '<th style="text-align: center; width: 5%;" class="sorting_disabled" rowspan="1" colspan="1">Acciones</th>';
    $('#headDetalleCabeceraDistribucion').append(fila);
}

function agregarFilaDistribucion(opcion) {

    if (obtenerAcumuladosPorcentajes_Montos(2) >= obtenerSubTotal_Total_Distribucion() && opcion != 1) {
        if (!isEmpty(importes.subTotalId))
            mostrarAdvertencia('El monto no puede exceder al sub total.');
        else
            mostrarAdvertencia('El monto no puede exceder al total.');
        return;
    }
    var operacionTipoId = select2.obtenerValor('cboOperacionTipo');
    if (isEmpty(operacionTipoId)) {
        mostrarAdvertencia('Primero seleccione el tipo de operación.');
        return;
    }

    var operacionTipo = dataContOperacionTipo.filter(item => item.id == select2.obtenerValor('cboOperacionTipo'))[0];
    var indice = nroFilasDistribucion;
    let addOnchageCboCuenta = (operacionTipo.requiere_centro_costo == 2 ? " onchange = 'onChangeCuentaContable(" + indice + ",this.value);' " : "");
    var fila = "<tr id=\"trDetalleDistribucion_" + indice + "\">";
    fila += "<td style='border:0; vertical-align: middle; padding-right: 10px;' id='txtNumItemDistribucion_" + indice + "' name='txtNumItemDistribucion_" + indice + "' align='center'></td>";
    fila += "<td style='border:0; vertical-align: middle;'>" +
        "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
        "<select name='cboCuentaContable_" + indice + "' id='cboCuentaContable_" + indice + "' class='select2' " + addOnchageCboCuenta + " >" +
        "</select></div></td>";
    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        fila += "<td style='border:0; vertical-align: middle;'>" +
            "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
            "<select name='cboCentroCosto_" + indice + "' id='cboCentroCosto_" + indice + "' class='select2'>" +
            "</select></div></td>";
    }

    fila += "<td style='border:0; vertical-align: middle;'><div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12' >" +
        "<input type='number'   id='txtPorcentajeDistribucion_" + indice + "' name='txtPorcentajeDistribucion_" + indice + "' class='form-control' required='' aria-required='true' style='text-align: right;' " +
        "onkeyup='if (this.value.length > 13) {this.value = this.value.substring(0, 13)}; actualizarMontoDistribucion(" + indice + ");'  /><span class='input-group-addon'>%</span></div></td>";
    fila += "<td style='border:0; vertical-align: middle;'><div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
        "<div class='input-group col-lg-12 col-md-12 col-sm-12 col-xs-12'>" +
        "<input type='number' id='txtMontoDistribucion_" + indice + "' name='txtMontoDistribucion_" + indice + "' class='form-control' required='' aria-required='true' value='' style='text-align: right;' " +
        "onkeyup='if (this.value.length > 13) {this.value = this.value.substring(0, 13)}; calculoPorcentajePagoDistribucion(" + indice + ");'  /></div></td>";
    fila += "<td style='border:0; align='center' vertical-align: middle;'>&nbsp;<a onclick='confirmarEliminarDistribucion(" + indice + ");'>" +
        "<i class='fa fa-trash-o' style='color:#cb2a2a;' title='Eliminar'></i></a></td>";
    $('#datatableDistribucion tbody').append(fila);
    nroFilasDistribucion++;
    reenumerarFilasDetalleDistribucion();
    if (operacionTipo.requiere_centro_costo == 1 || operacionTipo.requiere_centro_costo == 2) {
        if (!isEmpty(dataCofiguracionInicial.centroCosto)) {
            $.each(dataCofiguracionInicial.centroCosto, function (indexPadre, centroCostoPadre) {
                if (isEmpty(centroCostoPadre.centro_costo_padre_id)) {
                    var html = '<optgroup id="' + centroCostoPadre.id + '" label="' + centroCostoPadre['codigo'] + ' | ' + centroCostoPadre['descripcion'] + '">';
                    // centroCostoPadre['codigo'] + " | " + centroCostoPadre['descripcion']
                    var dataHijos = dataCofiguracionInicial.centroCosto.filter(centroCosto => centroCosto.centro_costo_padre_id == centroCostoPadre.id);
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
            select2.asignarValor("cboCentroCosto_" + indice, "");
        }
    }
    var array_cuentas_relaciondas = [];
    if (!isEmpty(dataCofiguracionInicial.cuentaContable)) {
        if (isEmpty(operacionTipo.cuentas_relacionadas)) {
            array_cuentas_relaciondas = dataCofiguracionInicial.cuentaContable;
        } else {
            var cuentas_relacionadas = operacionTipo.cuentas_relacionadas;
            cuentas_relacionadas = cuentas_relacionadas.split(',');
            if (!isEmpty(cuentas_relacionadas)) {
                $.each(dataCofiguracionInicial.cuentaContable, function (indexPadre, cuenta) {
                    $.each(cuentas_relacionadas, function (indexPadre, item) {
                        var busquedad = new RegExp('^' + item + '.*$');
                        if (!isEmpty(cuenta.codigo) && cuenta.codigo.match(busquedad)) {
                            array_cuentas_relaciondas.push(cuenta);
                        }
                    });
                });
            }
        }
    }

    $.each(array_cuentas_relaciondas, function (indexPadre, cuentaContablePadre) {
        var html = llenarCuentasContable(cuentaContablePadre, '', 'cboCuentaContable_' + indice);
        $('#cboCuentaContable_' + indice).append(html);
    });
    select2.asignarValor("cboCuentaContable_" + indice, "");
}


function onChangeCuentaContable(indice, valor) {
    let cuenta = dataCofiguracionInicial.cuentaContable.filter(item => item.id == valor);
    if (!isEmpty(valor) && cuenta[0]['codigo'].substr(0, 1) == '6' && cuenta[0]['codigo'].substr(0, 2) != '60' && cuenta[0]['codigo'].substr(0, 2) != '61' && cuenta[0]['codigo'].substr(0, 2) != '69') {
        $("#cboCentroCosto_" + indice).prop('disabled', false);
    } else {
        select2.asignarValor("cboCentroCosto_" + indice, "");
        $("#cboCentroCosto_" + indice).prop('disabled', true);
    }
}

function llenarCuentasContable(item, extra, cbo_id) {
    var cuerpo = '';
    if ($("#" + cbo_id + " option[value='" + item['id'] + "']").length != 0) {
        return cuerpo;
    }
    if (item.hijos * 1 == 0) {
        cuerpo = '<option value="' + item['id'] + '">' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
        return cuerpo;
    }
    cuerpo = '<option value="' + item['id'] + '" disabled>' + extra + item['codigo'] + " | " + item['descripcion'] + '</option>';
    var dataHijos = dataCofiguracionInicial.cuentaContable.filter(cuentaContable => cuentaContable.plan_contable_padre_id == item.id);
    //    cuerpo = '<optgroup label="' + extra + item['codigo'] + ' | ' + item['descripcion'] + '">';
    $.each(dataHijos, function (indexHijo, cuentaContableHijo) {
        //            cuerpo += '<option value="' + cuentaContableHijo['id'] + '">' + cuentaContableHijo['codigo'] + " | " + cuentaContableHijo['descripcion'] + '</option>';
        cuerpo += llenarCuentasContable(cuentaContableHijo, extra + '&nbsp;&nbsp;&nbsp;&nbsp;', cbo_id);
    });
    //    cuerpo += ' </optgroup>';
    return cuerpo;
}

function confirmarEliminarDistribucion(index) {
    swal({
        title: "¿Está seguro que desea eliminar?",
        text: "Una vez eliminado tendrá que seleccionar nuevamente todo el registro si desea volver agregarlo",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarDistribucion(index);
        }
    });
}

function eliminarDistribucion(indice) {
    $('#trDetalleDistribucion_' + indice).remove();
    reenumerarFilasDetalleDistribucion();
}

function calculoPorcentajePagoDistribucion(indice) {
    var importeAcumulado = obtenerAcumuladosPorcentajes_Montos(2);
    var importePago = $('#txtMontoDistribucion_' + indice).val() * 1;
    if (importeAcumulado > obtenerSubTotal_Total_Distribucion()) {
        var nuevo_importe = redondearNumerDecimales(obtenerSubTotal_Total_Distribucion() - importeAcumulado + importePago, 6);
        var porcentaje = redondearNumerDecimales((nuevo_importe / obtenerSubTotal_Total_Distribucion()) * 100, 6);
        $('#txtMontoDistribucion_' + indice).val(redondearNumerDecimales(nuevo_importe, 2));
        $('#txtPorcentajeDistribucion_' + indice).val(redondearNumerDecimales(porcentaje, 4));
        if (!isEmpty(importes.subTotalId))
            mensajeValidacion('El monto no puede exceder al sub total.');
        else
            mensajeValidacion('El monto no puede exceder al total.');
        return;
    }

    if (importePago <= 0) {
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(0));
        $('#txtPorcentajeDistribucion_' + indice).val(devolverDosDecimales(0));
        mensajeValidacion('El monto debe ser positivo.');
        return;
    }

    var porcentaje = (importePago / obtenerSubTotal_Total_Distribucion()) * 100;
    $('#txtPorcentajeDistribucion_' + indice).val(devolverDosDecimales(porcentaje));
}

function redondearNumerDecimales(monto, decimales) {
    if (isEmpty(decimales)) {
        decimales = 2;
    }
    return Math.round(monto * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function actualizarMontoDistribucion(indice) {

    var porcentajeAcumulado = obtenerAcumuladosPorcentajes_Montos(1);
    var porcentaje = $('#txtPorcentajeDistribucion_' + indice).val() * 1;
    if (porcentajeAcumulado > 100) {
        var nuevo_porcentaje = redondearNumerDecimales(100 - porcentajeAcumulado, 6);
        $('#txtPorcentajeDistribucion_' + indice).val(redondearNumerDecimales(porcentaje, 4));
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(obtenerSubTotal_Total_Distribucion() * nuevo_porcentaje / 100));
        mensajeValidacion('Porcentaje máximo 100.');
        return;
    }

    if (porcentaje < 0) {
        $('#txtPorcentajeDistribucion_' + indice).val(0);
        $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(0));
        mensajeValidacion('Porcentaje de pago debe ser positivo.');
        return;
    }

    var monto = (obtenerSubTotal_Total_Distribucion() * porcentaje) / 100;
    $('#txtMontoDistribucion_' + indice).val(devolverDosDecimales(monto));
}

function obtenerAcumuladosPorcentajes_Montos(tipo) {
    var montoAcumulado = 0;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if (tipo == 1 && $('#txtPorcentajeDistribucion_' + i).length != 0) {
            montoAcumulado = redondearNumerDecimales($('#txtPorcentajeDistribucion_' + i).val() * 1 + montoAcumulado, 6);
        } else if (tipo == 2 && $('#txtMontoDistribucion_' + i).length != 0) {
            montoAcumulado = redondearNumerDecimales($('#txtMontoDistribucion_' + i).val() * 1 + montoAcumulado, 6);
            //            montoAcumulado += $('#txtMontoDistribucion_' + i).val() * 1;
        }
    }
    return isEmpty(montoAcumulado) ? 0 : montoAcumulado;
}

function obtenerSubTotal_Total_Distribucion() {
    var monto = 0;
    var checkIGV = false;
    if ($("#chkIncluyeIGV").length > 0 && $('#chkIncluyeIGV').is(':checked')) {
        checkIGV = true;
    }
    if (importes.subTotalId == importes.calculoId || (checkIGV && !isEmpty(importes.subTotalId))) {

        if ($('#' + importes.subTotalId).length != 0 && !isEmpty($('#' + importes.subTotalId).val())) {
            monto += $('#' + importes.subTotalId).val() * 1;
        }

        if ($('#' + importes.otrosId).length != 0 && !isEmpty($('#' + importes.otrosId).val())) {
            monto += $('#' + importes.otrosId).val() * 1;
        }

        if ($('#' + importes.exoneracionId).length != 0 && !isEmpty($('#' + importes.exoneracionId).val())) {
            monto += $('#' + importes.exoneracionId).val() * 1;
        }

        if ($('#' + importes.igvId).length != 0 && !isEmpty($('#' + importes.igvId).val()) && select2.obtenerValor("cboOperacionTipo") == "30") {
            monto += $('#' + importes.igvId).val() * 1;
        }

    } else if (importes.totalId == importes.calculoId && $('#' + importes.totalId).length != 0 && !isEmpty($('#' + importes.totalId).val())) {
        monto = $('#' + importes.totalId).val() * 1;
    }
    return monto;
}

function reenumerarFilasDetalleDistribucion() {
    var numItem = 1;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            $('#txtNumItemDistribucion_' + i).html(numItem);
            numItem++;
        }
    }
}


var dataDistribucion = [];
function obtenerDistribucion() {
    dataDistribucion = [];
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            var item = {};
            item.linea = $('#txtNumItemDistribucion_' + i).html();
            if (!isEmpty(select2.obtenerValor("cboCuentaContable_" + i))) {
                item.plan_contable_id = select2.obtenerValor("cboCuentaContable_" + i);
            }

            if (!isEmpty(select2.obtenerValor("cboCentroCosto_" + i)) && (operacionTipo.requiere_centro_costo == '1' || operacionTipo.requiere_centro_costo == '2')) {
                item.centro_costo_id = select2.obtenerValor("cboCentroCosto_" + i);
            }

            if ($('#txtMontoDistribucion_' + i).val() * 1 >= 0) {
                item.monto = $('#txtMontoDistribucion_' + i).val() * 1;
            }

            if ($('#txtPorcentajeDistribucion_' + i).val() * 1 > 0) {
                item.porcentaje = $('#txtPorcentajeDistribucion_' + i).val() * 1;
            }

            if (!isEmpty(item.plan_contable_id) || !isEmpty(item.porcentaje)) {
                dataDistribucion.push(item);
            }
        }
    }
}

function validarDistribucion() {
    dataDistribucion = [];
    var operacionTipo = dataContOperacionTipo.filter(function (obj) {
        return obj.id == select2.obtenerValor('cboOperacionTipo');
    })[0];
    var porcentaje = 0;
    var monto_total = 0;
    for (var i = 0; i < nroFilasDistribucion; i++) {
        if ($('#txtNumItemDistribucion_' + i).length > 0) {
            var item = {};
            item.linea = $('#txtNumItemDistribucion_' + i).html();
            if (isEmpty(select2.obtenerValor("cboCuentaContable_" + i))) {
                mostrarValidacionLoaderClose('Debe seleccionar la cuenta contable en la fila ' + item.linea + '.');
                return false;
            } else {
                item.plan_contable_id = select2.obtenerValor("cboCuentaContable_" + i);
            }

            if (isEmpty(select2.obtenerValor("cboCentroCosto_" + i)) && operacionTipo.requiere_centro_costo == '1') {
                mostrarValidacionLoaderClose('Debe seleccionar el centro de costo en la fila ' + item.linea + '.');
                return false;
            } else if (operacionTipo.requiere_centro_costo == '1') {
                item.centro_costo_id = select2.obtenerValor("cboCentroCosto_" + i);
            }

            if ($('#txtMontoDistribucion_' + i).val() * 1 <= 0) {
                mostrarValidacionLoaderClose('El monto en la fila ' + item.linea + ' debe ser mayor que cero.');
                return false;
            } else if ($('#txtMontoDistribucion_' + i).val() * 1 > obtenerSubTotal_Total_Distribucion()) {
                mostrarValidacionLoaderClose('El monto en la fila ' + item.linea + ' sobre pasa el monto máximo ' + obtenerSubTotal_Total_Distribucion() + '.');
                return false;
            } else {
                item.monto = $('#txtMontoDistribucion_' + i).val() * 1;
                monto_total += $('#txtMontoDistribucion_' + i).val() * 1;
            }

            if ($('#txtPorcentajeDistribucion_' + i).val() * 1 <= 0) {
                mostrarValidacionLoaderClose('El porcentaje en la fila ' + item.linea + ' debe ser mayor que cero.');
                return false;
            } else if ($('#txtPorcentajeDistribucion_' + i).val() * 1 > 100) {
                mostrarValidacionLoaderClose('El porcentaje en la fila ' + item.linea + ' no debe ser mayor de lo permitido 100%.');
                return false;
            } else {
                item.porcentaje = $('#txtPorcentajeDistribucion_' + i).val() * 1;
                porcentaje += $('#txtPorcentajeDistribucion_' + i).val() * 1;
            }

            dataDistribucion.push(item);
        }
    }

    if (devolverDosDecimales(monto_total) != obtenerSubTotal_Total_Distribucion()) {
        mostrarValidacionLoaderClose('La suma de los montos debe ser ' + obtenerSubTotal_Total_Distribucion() + '.');
        return false;
    }

    if (devolverDosDecimales(porcentaje) != 100) {
        mostrarValidacionLoaderClose('La suma de porcentajes debe ser  100.00%.');
        return false;
    }

    return true;
}

function obtenerMontoRetencion() {

    let dataRetencion = obtenerDocumentoTipoDatoXTipoXCodigo("4", "10");
    if (!isEmpty(dataRetencion) && !isEmpty(dataCofiguracionInicial.dataRetencion)) {
        var retencion = dataCofiguracionInicial.dataRetencion;
        var montoTotal = $("#" + importes.totalId).val();
        if (!isEmpty(retencion) && !isEmpty(montoTotal) && (montoTotal * 1) > 0 && igvValor > 0) {
            var valorRetencion = dataRetencion.data.filter(item => item.id == select2.obtenerValor("cbo_" + dataRetencion.id));
            if (!isEmpty(valorRetencion) && valorRetencion[0]['valor'] == 1) {
                var monto_minimo = retencion.monto_minimo * 1;
                montoTotal = montoTotal * 1;
                var monedaBase = dataCofiguracionInicial.moneda.filter(item => item.base == 1)[0];
                var monedaSeleccionada = select2.obtenerValor('cboMoneda');
                var tipoCambio = ($("#tipoCambio").length > 0 && !isEmpty($('#tipoCambio').val()) ? ($('#tipoCambio').val()) * 1 : 0);
                if (monedaBase.id != monedaSeleccionada) {
                    monto_minimo = (monto_minimo / tipoCambio);
                }

                if (montoTotal > monto_minimo) {
                    var montoRetenido = (montoTotal) * ((retencion.porcentaje) / 100);
                    $('#txt_' + dataRetencion.id).removeAttr('style').attr('style', 'color:black;font-style: normal;');
                    $('#txt_' + dataRetencion.id).html('<b>Aplica la retención de ' + monedaSimbolo + ' ' + formatearNumero(montoRetenido) + '</b>');
                    $('#txt_' + dataRetencion.id).show();
                    montoTotalRetencion = montoRetenido;
                } else if (montoTotal <= monto_minimo) {
                    $('#txt_' + dataRetencion.id).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                    $('#txt_' + dataRetencion.id).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                    $('#txt_' + dataRetencion.id).show();
                    montoTotalRetencion = 0;
                }
            } else {
                $('#txt_' + dataRetencion.id).hide();
                montoTotalRetencion = 0;
            }
        } else {
            $('#txt_' + dataRetencion.id).hide();
            montoTotalRetencion = 0;
        }
    } else {
        montoTotalRetencion = 0;
    }

    if (!isEmpty(importes.calculoId)) {
        calculoTotal = parseFloat(devolverDosDecimales($('#' + importes.totalId).val() - montoTotalDetraido - montoTotalRetencion));
    }
}

function obtenerMontoDetraccion() {
    if ($('#cbo_' + cboDetraccionId).length != 0 && ($('#cbo_' + cboDetraccionId).val() * 1) > 0) {
        var detraccion = dataCofiguracionInicial.dataDetraccion.filter(item => item.id == select2.obtenerValor('cbo_' + cboDetraccionId));
        var montoTotal = $("#" + importes.totalId).val();
        if (!isEmpty(detraccion) && !isEmpty(montoTotal) && (montoTotal * 1) > 0) {
            var monto_minimo = detraccion[0].monto_minimo * 1;
            montoTotal = montoTotal * 1;
            var monedaBase = dataCofiguracionInicial.moneda.filter(item => item.base == 1)[0];
            var monedaSeleccionada = select2.obtenerValor('cboMoneda');
            var tipoCambio = ($("#tipoCambio").length > 0 && !isEmpty($('#tipoCambio').val()) ? ($('#tipoCambio').val()) * 1 : 0);
            if (monedaBase.id != monedaSeleccionada) {
                monto_minimo = (monto_minimo / tipoCambio);
            }
            if (igvValor == 0) {
                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = 0;
            } else if (montoTotal > monto_minimo) {
                var textoConversion = '';
                var montoDetraido = (montoTotal) * ((detraccion[0].porcentaje) / 100);
                if (monedaBase.id == monedaSeleccionada) {
                    montoDetraido = Math.round(montoDetraido);
                } else {
                    textoConversion = '(' + monedaBase.simbolo + ' ' + formatearNumero(Math.round(montoDetraido * tipoCambio)) + ')';
                }

                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:black;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>Aplica la detracción de ' + monedaSimbolo + ' ' + formatearNumero(montoDetraido) + ' ' + textoConversion + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = montoDetraido;
            } else if (montoTotal <= monto_minimo) {
                $('#txt_' + cboDetraccionId).removeAttr('style').attr('style', 'color:red;font-style: normal;');
                $('#txt_' + cboDetraccionId).html('<b>El monto mínimo es ' + monedaSimbolo + ' ' + formatearNumero(monto_minimo) + '</b>');
                $('#txt_' + cboDetraccionId).show();
                montoTotalDetraido = 0;
            }
        } else {
            $('#txt_' + cboDetraccionId).hide();
            montoTotalDetraido = 0;
        }
    } else {
        $('#txt_' + cboDetraccionId).hide();
        montoTotalDetraido = 0;
    }

    if (!isEmpty(importes.calculoId)) {
        calculoTotal = parseFloat(devolverDosDecimales($('#' + importes.totalId).val() - montoTotalDetraido - montoTotalRetencion));
    }
}

function onChangeCboTipoVenta() {
    asignarImporteDocumento();
}

$('input[type=radio][name=rtnButtonPeso]').on('change', function () {
    let banderaTipoPeso = $(this).val();
    if (banderaTipoPeso == 1) {
        $("#txtPeso").prop('disabled', true);
        $("#txtPesoTotal").prop('disabled', false);
    } else {

        $("#txtPesoTotal").prop('disabled', true);
        $("#txtPeso").prop('disabled', false);
    }

    obtenerPrecioCalculado();
});

function deshabilitarProveedor() {

    if ($("#cboDocumentoTipo").val() == 189) {

        let campoProveedor = camposDinamicos.filter(item => item.tipo == 5);
        if (!isEmpty(campoProveedor)) {
            var motivoTraslado = $("#cbo_1730").val();
            if (motivoTraslado == 224) {
                select2.asignarValor("cbo_" + campoProveedor[0]['id'], "-1");
                $("#cbo_1723").prop('disabled', true); // desabilita cbo proveedor
                return true;
            } else {
                $("#cbo_1723").prop('disabled', false);
                return false;
            }
        }
    }
    return false;
}

function onChangeCboMotivo() {
    deshabilitarProveedor();
}
var dataComboGuia = [];
function mostrarDivComboGuia(accionRegistrar) {

    if (accionRegistrar) {
        let guia_texto = $("#txtGuiaRelacion").val();
        if (isEmpty(guia_texto)) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese una guía valida.");
            return;
        }
        if (!isEmpty(dataComboGuia.filter(item => item.guia == guia_texto))) {
            $.Notification.autoHideNotify('warning', 'top right', 'Validación', "La guía " + guia_texto + ", ya fue ingresada.");
            return;
        }

        let guiaSelecionada = select2.obtenerIdMultiple("cboGuiaRelacion");
        if (!isEmpty(guia_texto)) {
            dataComboGuia.push({ 'guia': guia_texto });
            if (!isEmpty(guiaSelecionada)) {
                guiaSelecionada.push(guia_texto);
            } else {
                guiaSelecionada = [guia_texto];
            }
        }
        select2.cargar("cboGuiaRelacion", dataComboGuia, "guia", "guia");
        select2.asignarValor("cboGuiaRelacion", guiaSelecionada);

        $.Notification.autoHideNotify('success', 'top right', 'Exito', "Guía agregada.");

    }
    $("#contenedorGuiaDivTexto").hide();
    $("#contenedorGuiaDivCombo").show();
}

function mostrarDivTextoGuia() {

    $("#contenedorGuiaDivCombo").hide();
    $("#contenedorGuiaDivTexto").show();
    $("#txtGuiaRelacion").val('');
    $("#txtGuiaRelacion").focus().val();
    //    focusCampo("txtGuiaRelacion");
}


var documento_pago = [];
function abrirModalpago() { }
function onResponseAbrirModalpago(data) {
    $("#modaldepago").modal('show');
}
function obtenerDataPago() {
    documento_pago = '';
}

$($("#cboAutorizado").data("select2").search).on('keyup', function (e) {
    if (e.keyCode === 13) {
        obtenerDataCombo('Autorizado');
    }
});


function onResponseObtenerPersonaActivoXStringBusqueda(data, dataCombo) {

    select2.cargarDataSetSeleccione("cbo" + dataCombo.id, data, "id", ["nombre", "codigo_identificacion"], ["persona_tipo_id", "telefono", "celular"], ("Seleccione " + dataCombo.id));
    loaderClose();

  ArrayListaClientesSelect=[];
    ArrayListaClientesSelect=data;
//validacion Ingrid 201123
	
    if (!isEmpty(dataCombo.valor)) {
        $('#cbo' + dataCombo.id).select2('open');
        $($("#cbo" + dataCombo.id).data("select2").search).val(dataCombo.valor);
        $($("#cbo" + dataCombo.id).data("select2").search).trigger('input');
        setTimeout(function () {
            $('.select2-results__option').trigger("mouseup");
        }, 500);
    }

    if (!isEmpty(dataCombo.personaId)) {
        select2.asignarValor("cbo" + dataCombo.id, dataCombo.personaId);
        if (dataCombo.id != "Autorizado") {
            setTimeout(function () {
                cargarTelefonoXTipo(dataCombo.id);
            }, 500);
        }
        if (personaIdRegistro == dataCombo.personaId) {
            personaIdRegistro = null;
        }
    }

    setTimeout(function () {
        $($("#cbo" + dataCombo.id).data("select2").search).on('keyup', function (e) {
            if (e.keyCode === 13) {
                obtenerDataCombo(dataCombo.id);
            }
        });
    }, 1000);
}

function obtenerDataCombo(id) {
    let texto = $($("#cbo" + id).data("select2").search).val();
    //    return;

    if (isEmpty(texto)) {
        //        $.Notification.autoHideNotify('warning', 'top right', 'Validación', "Ingrese un texto para buscar");
        return;
    }
    //:::TRABAJO JESUS
  cbocliente = id;
  if(id === 'Cliente'){
   cbocliente = id;
  }
//:::TRABAJO JESUS

    $('#cbo' + id).select2('close');
    loaderShow();
    ax.setAccion("obtenerSelectPersonas");
    ax.addParamTmp("querySelect", texto);
    ax.addParamTmp("pesonaId", personaIdRegistro);
    ax.setTag({ id: id, valor: texto });
    ax.consumir();
}

function obtenerSerieCorrelativoGuia() {
    onChangeComprobanteTipo(select2.obtenerValor("cboComprobanteTipo"));
}

$("#txtAltura").on('keyup', function (e) {
    if (e.keyCode === 13) {
        $("#txtAncho").focus();
    }
});

$("#txtAncho").on('keyup', function (e) {
    if (e.keyCode === 13) {
        $("#txtLongitud").focus();
    }
});

$("#txtLongitud").on('keyup', function (e) {
    if (e.keyCode === 13) {
        if ($('input[name="rtnButtonPeso"]:checked').val() == 0) {
            $("#txtPeso").focus();
        } else {
            $("#txtPesoTotal").focus();
        }

    }
});

$("#txtPeso").on('keyup', function (e) {
    if (e.keyCode === 13) {
        $("#txtCantidad").focus();
    }
});

$("#txtPesoTotal").on('keyup', function (e) {
    if (e.keyCode === 13) {
        $('input[name="name_of_your_radiobutton"]:checked').val();

        $("#txtCantidad").focus();
    }
});

$("#txtCantidad").on('keyup', function (e) {
    if (e.keyCode === 13) {
        confirmar(1);
    }
});

$("#txtClave").on('keyup', function (e) {
    if (e.keyCode === 13) {
        $("#txtClaveConfirmacion").focus();
    }
});




						 
							

