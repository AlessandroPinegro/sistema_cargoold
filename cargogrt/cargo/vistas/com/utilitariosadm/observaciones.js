$(document).ready(function () {
  $('[data-toggle="popover"]').popover({ html: true }).popover();
  cargarTitulo("titulo", "");
  select2.iniciar();
  ax.setSuccess("successObservaciones");
  obtenerConfiguracionesInicialesObservaciones();
  $("#divLeyenda").show();
});

function successObservaciones(response) {
  console.log(response);
  $("#divLeyenda").show();
  if (response["status"] === "ok") {
    var dataTableConfig = {
      language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ning\xFAn dato disponible en esta tabla",
        sInfo:
          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "Siguiente",
          sPrevious: "Anterior",
        },
        oAria: {
          sSortAscending:
            ": Activar para ordenar la columna de manera ascendente",
          sSortDescending:
            ": Activar para ordenar la columna de manera descendente",
        },
      },
      scrollX: true,
      bDestroy: true,
      // createdRow: function (row, response, dataIndex) {
      //   if (response[9] !== "A. Por Sistema") {
      //     $(row).css("background-color", "#FFF3E0");
      //   }
      // },
      //   "columnDefs": [
      //     { "width": "30%", "targets": 0 }, // Ancho de la primera columna
      //     { "width": "10%", "targets": 1 }, // Ancho de la segunda columna
      //     { "width": "25%", "targets": 3 }, // Ancho de la tercera columna
      //     { "width": "20%", "targets": 4 }, // Ancho de la tercera columna
      //     { "width": "20%", "targets": 5 }, // Ancho de la tercera columna

      // ],
    };
    switch (response[PARAM_ACCION_NAME]) {
      case "obtenerConfiguracionesInicialesObservaciones":
        obtenerData(response.data);
        loaderShow();
        break;

      case "obtenerDataPorFiltros":
        onResponseData(response.data);
        // obtenerData(response.data);
        break;

      case "exportarDataExcel":
        loaderClose();
        location.href = URL_BASE + "util/formatos/reporte.xlsx";
        break;
      case "obtenerDocumentoRelacionVisualizar":
        // onResponseObtenerDocumentoRelacionVisualizar(response.data);
        break;
      case "mostrarDetalleDocumentoObservacion":
        obtenerDetalleObservacion(response.data);
        return;
    }
    $("#datatable").dataTable(dataTableConfig);
  }
}

function obtenerConfiguracionesInicialesObservaciones() {
  loaderShow();
  ax.setAccion("obtenerConfiguracionesInicialesObservaciones");
  ax.addParamTmp("empresaId", commonVars.empresa);
  ax.consumir();
}
function generarTabla(datatable) {
  var cuerpo_total = "";
  var cabeza =
    '<table id="datatable"  class="table table-striped table-bordered"><thead>' +
    '<tr style="background-color:#f77816;">' +
    '<th style="text-align:center; color:#ffffff" >Nro. De Pedido</th>' +
    '<th style="text-align:center; color:#ffffff" >F. Pedido</th>' +
    '<th style="text-align:center; color:#ffffff" >F. Observacion</th>' +
    '<th style="text-align:center; color:#ffffff" >Cliente Remitente</th>' +
    '<th style="text-align:center; color:#ffffff" >Agencia de  Origen</th>' +
    '<th style="text-align:center; color:#ffffff" >Agencia de   Destino</th>' +
    '<th style="text-align:center; color:#ffffff">Total</th>' +
    '<th style="text-align:center; color:#ffffff">Comp.</th>' +
    '<th style="text-align:center; color:#ffffff">Nro. Comp.</th>' +
    '<th style="text-align:center; color:#ffffff">Usuario</th>' +
    '<th style="text-align:center; color:#ffffff" width="50px">Estado</th>' +
    '<th style="text-align:center; color:#ffffff" width="60px">Acciones</th>' +
    "</tr>" +
    "</thead>";

  if (!isEmpty(datatable)) {
    $.each(datatable, function (index, item) {
      var cuerpo =
        // ( item.mansiche == 1 ? "<i class='fa fa-building-o' style='color:#f44336;'></i> ":
        "<tr>" +
        "<td id=  "+ item.documento_id + "   style='text-align:left;'  >" + 
        ( item.mansiche == 1 ? "<i class='fa fa-building-o' style='color:#f44336;'></i> ":
       "<input type='checkbox' class='form-check-input' id='exampleCheck" + index + "' onchange='handleCheckboxChange(this)' data-valor='" + item.documento_id + "'></input>") + 
      "<a  href='#' onclick='detallePedido(\"" +  item.documento_id +  '", "' +  item.movimiento_id +  "\")' style='color: blue; text-decoration: underline;'>" +
      item.serie_numero + "</a>" + "</td>" +
        "<td style='text-align:left; width='50px'>" +
        item.fecha_creacion +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.fecha_observacion +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.cliente +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.agencia_origen +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.agencia_destino +
        "</td>" +
        "<td style='text-align:left;'>" +
        "S/." +
        formatearNumero(item.total) +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.comprobante_tipo +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.comprobante_serie_numero +
        "</td>" +
        "<td style='text-align:left;'>" +
        (item.usuario_creacion === "Jpinegro"
          ? "A. Por Sistema"
          : item.usuario_creacion) +
        "</td>" +
        "<td style='text-align:left;'>" +
        item.estado +
        "</td>" +
        "<td style='text-align:center;'>" +
        "<a href='#' onclick='detalleOnservacion(\"" +
        item.documento_id +
        "\")' ><b><i class='fa fa-eye' style='color:green;'></i><b></a>&nbsp;\n" +
        "<a  title='Reversar Comprobante' id=" +
        item.documento_id +
        " href='#' onclick='reversarComprobante(\"" +
        item.documento_id +
        "\")' ><b><i class='fa fa-cog' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
        " <a href='#' onclick='prueb(\"" +
        item.documento_id +
        "\")' ><b><i class='fa fa-bars' style='color:#3f51b5;'></i><b></a>&nbsp;\n" +
        "</td>" +
        "</tr>";
      cuerpo_total += cuerpo;
    });
  }

  var pie = "</table>";
  var html = cabeza + cuerpo_total + pie;
  $("#dataListObservacion").empty().append(html);
  dibujarLeyendaAcciones();
  loaderClose();
}

var valoresSeleccionados = [];

function handleCheckboxChange(checkbox) {
    var valor = checkbox.getAttribute('data-valor');
    if (checkbox.checked) {
        valoresSeleccionados.push({
            documentoId: valor
        });
        console.log('Valores seleccionados:', valoresSeleccionados);
    } else {
        var index = valoresSeleccionados.findIndex(function(item) {
            return item.documentoId === valor;
        });
        if (index !== -1) {
            valoresSeleccionados.splice(index, 1);
            console.log('Valores seleccionados:', valoresSeleccionados);
        }
    }
}

 function Enviar(){

  if(valoresSeleccionados.length == 0){
    mostrarValidacionLoaderClose("Usted no a seleccionado ningun Item para enviar al manciche...");
  }else{
    swal({
      title: "Estás seguro?",
      text: "Si finaliza el envio de pedidos a mansiche, usted tiene la opcion de retornar a su punto de destino para ser entregadas",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#33b86c",
      confirmButtonText: "Si, finalizar!",
      cancelButtonColor: '#d33',
      cancelButtonText: "No, cancelar !",
      closeOnConfirm: false,
      closeOnCancel: true
  }, function (isConfirm) {
      if (isConfirm) {
        ax.setAccion("enviarManciche");
        ax.addParamTmp("valoresSeleccionados",valoresSeleccionados);
        ax.consumir();
          swal({
            title: "Mansiche!",
            text: "Pedidos enviados a mansiche correctamente.",
            type: "success"
          }, function () {
       obtenerConfiguracionesInicialesObservaciones();
        valoresSeleccionados = [];
          });
      } 
  });
  }

  console.log('Todos los datos almacenados en el array:', valoresSeleccionados);
 }
 function mostrarValidacionLoaderClose(mensaje) {
  mostrarAdvertencia(mensaje);
  loaderClose();
}

function detallePedido(documento_id, movimiento_id) {
  alert("MODULO DEL DETALLE DE CADA PEDIDO:  " + documento_id);
  ax.setAccion("obtenerDocumentoRelacionVisualizar");
  ax.addParamTmp("documentoId", documento_id);
  ax.addParamTmp("movimientoId", movimiento_id);
  ax.consumir();
}
function onResponseObtenerDocumentoRelacionVisualizar(data) {
  $("#formularioDetalleDocumento").empty();
  console.log(data);
  var serieDocumento = "";
  if (!isEmpty(data["dataDocumento"][0].serie)) {
    serieDocumento = data["dataDocumento"][0].serie + " - ";
  }
  let titulo =
    "<b>" +
    (data["dataDocumento"][0].documento_tipo_descripcion.toUpperCase() +
      " " +
      serieDocumento +
      data["dataDocumento"][0].numero);
  if (
    !isEmpty(data["dataDocumento"][0].agencia_destino) &&
    !isEmpty(data["dataDocumento"][0].agencia_origen)
  ) {
    titulo =
      titulo +
      " | " +
      data["dataDocumento"][0].agencia_origen +
      " - " +
      data["dataDocumento"][0].agencia_destino;
  }

  if (data["dataDocumento"][0]["bandera_es_cargo"] == 1) {
    titulo = titulo + ' | <span class="label label-info">Dev. cargo</span>';
  }
  titulo = titulo + "</b>";
  $("#tituloVisualizacionModal").html(titulo);
  var fechaEmision = separarFecha(data["dataDocumento"][0].fecha_emision);
  var html = '<div class="row">';
  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Fecha:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html =
    html +
    (fechaEmision.dia + "/" + fechaEmision.mes + "/" + fechaEmision.anio);
  html = html + "</div>";
  html = html + "</div>";

  if (!isEmpty(data["dataDocumento"][0].fecha_vencimiento)) {
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Fecha Vencimiento:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

    var fechaVencimiento = separarFecha(
      data["dataDocumento"][0].fecha_vencimiento
    );
    html =
      html +
      (fechaVencimiento.dia +
        "/" +
        fechaVencimiento.mes +
        "/" +
        fechaVencimiento.anio);
    html = html + "</div>";
    html = html + "</div>";
  }

  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Moneda:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html = html + data["dataDocumento"][0].moneda_descripcion;
  html = html + "</div>";
  html = html + "</div>";

  html = html + "</div>";

  html = html + '<div class="row">';
  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Cliente:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html =
    html +
    data["dataDocumento"][0].codigo_identificacion +
    " | " +
    data["dataDocumento"][0].persona_nombre;
  html = html + "</div>";
  html = html + "</div>";

  let personaDireccion = !isEmpty(data["dataDocumento"][0].persona_direccion)
    ? data["dataDocumento"][0].persona_direccion
    : "";

  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Dirección facturación:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html = html + personaDireccion;
  html = html + "</div>";
  html = html + "</div>";

  if (!isEmpty(data["dataDocumento"][0].persona_entregado_nombre)) {
    html = html + '<div class="col-md-2">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Destinatario opcional:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data["dataDocumento"][0].persona_autorizada;
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-2">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Entregado a:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data["dataDocumento"][0].persona_entregado_nombre;
    html = html + "</div>";
    html = html + "</div>";
  } else {
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Destinatario opcional:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data["dataDocumento"][0].persona_autorizada;
    html = html + "</div>";
    html = html + "</div>";
  }

  html = html + "</div>";

  html = html + '<div class="row">';
  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Remitente:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html =
    html +
    data["dataDocumento"][0].codigo_identificacion_origen +
    " | " +
    data["dataDocumento"][0].persona_origen_nombre;
  html = html + "</div>";
  html = html + "</div>";

  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Destinatario:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html =
    html +
    data["dataDocumento"][0].codigo_identificacion_destino +
    " | " +
    data["dataDocumento"][0].persona_destino_nombre;
  html = html + "</div>";
  html = html + "</div>";

  html = html + '<div class="col-md-2">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Tipo:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html = html + data["dataDocumento"][0].modalidad_descripcion;
  html = html + "</div>";
  html = html + "</div>";

  if (!isEmpty(data["dataDocumento"][0].documento_tipo_pago)) {
    html = html + '<div class="col-md-2">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Tipo pago:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + data["dataDocumento"][0].documento_tipo_pago;
    html = html + "</div>";
    html = html + "</div>";
  }

  html = html + "</div>";

  html = html + '<div class="row">';
  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Dirección origen:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html = html + data["dataDocumento"][0].persona_direccion_origen;
  html = html + "</div>";
  html = html + "</div>";

  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Dirección destino:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html = html + data["dataDocumento"][0].persona_direccion_destino;
  html = html + "</div>";
  html = html + "</div>";

  html = html + '<div class="col-md-4">';
  html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
  html = html + "<label>Guía relación:</label>";
  html = html + "</div>";
  html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
  html =
    html +
    (!isEmpty(data["dataDocumento"][0].guia_relacion)
      ? data["dataDocumento"][0].guia_relacion
      : "");
  html = html + "</div>";
  html = html + "</div>";

  html = html + "</div>";

  if (!isEmpty(data["dataDocumento"][0].documento_cargo_id)) {
    html = html + '<div class="col-md-8"   style="padding-left: 0px;">';

    html = html + '<div class="row">';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html =
      html +
      "<label>" +
      (!isEmpty(data["dataDocumento"][0].otro_gasto_descripcion)
        ? data["dataDocumento"][0].otro_gasto_descripcion
        : "Otros costos") +
      ":</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].monto_otro_gasto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Devolución de cargo:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html =
      html + formatearNumero(data["dataDocumento"][0].monto_devolucion_gasto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + "</div>";

    html = html + '<div class="row">';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Costo reparto:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].monto_costo_reparto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-3">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Recojo Domicilio reparto:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html =
      html + formatearNumero(data["dataDocumento"][0].costo_recojo_domicilio);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Sub total:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].subtotal);
    html = html + "</div>";
    html = html + "</div>";

    html = html + "</div>";

    html = html + '<div class="row">';

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>IGV:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].igv);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-6">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Total:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].total);
    html = html + "</div>";
    html = html + "</div>";

    html = html + "</div>";

    html = html + "</div>";

    html = html + '<div class="col-md-4">';
    var fechaEmisionCargo = separarFecha(
      data["dataDocumento"][0].documento_cargo_fecha_emision
    );
    html =
      html +
      `<div class="mini-stat clearfix">
                        <span class="mini-stat-icon bg-info"><i class="ion-arrow-return-left"></i></span>
                        <div class="mini-stat-info text-center">
                            <span class="counter">` +
      (fechaEmisionCargo.dia +
        "/" +
        fechaEmisionCargo.mes +
        "/" +
        fechaEmisionCargo.anio) +
      `</span>
                            ` +
      data["dataDocumento"][0].agencia_destino +
      ` - ` +
      data["dataDocumento"][0].agencia_origen +
      `
                        </div>
                        <div class="mini-stat-info text-center">
                                <span class="counter">` +
      data["dataDocumento"][0].documento_cargo_serie +
      ` - ` +
      data["dataDocumento"][0].documento_cargo_numero +
      `</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;` +
      data["dataDocumento"][0].documento_cargo_usuario +
      `
                        </div>
                    </div>`;
    html = html + "</div>";

    html = html + "</div>";
  } else {
    html = html + '<div class="row">';

    html = html + '<div class="col-md-3">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html =
      html +
      "<label>" +
      (!isEmpty(data["dataDocumento"][0].otro_gasto_descripcion)
        ? data["dataDocumento"][0].otro_gasto_descripcion
        : "Otros costos") +
      ":</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].monto_otro_gasto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-3">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Devolución de cargo:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html =
      html + formatearNumero(data["dataDocumento"][0].monto_devolucion_gasto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-3">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Costo reparto:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].monto_costo_reparto);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-3">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Recojo Domicilio reparto:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html =
      html + formatearNumero(data["dataDocumento"][0].costo_recojo_domicilio);
    html = html + "</div>";
    html = html + "</div>";

    html = html + "</div>";

    html = html + '<div class="row">';
    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Sub total:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].subtotal);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>IGV:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].igv);
    html = html + "</div>";
    html = html + "</div>";

    html = html + '<div class="col-md-4">';
    html = html + '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">';
    html = html + "<label>Total:</label>";
    html = html + "</div>";
    html = html + '<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';
    html = html + formatearNumero(data["dataDocumento"][0].total);
    html = html + "</div>";
    html = html + "</div>";

    html = html + "</div>";
    html = html + "</div>";
  }

  $("#modalDetalleDocumento").modal("show");
}

function appendFormDetalle(html) {
  $("#formularioDetalleDocumento").append(html);
}

function obtenerData(data) {
  loaderClose();
  var datatable = data.dataObservacion.pedidosObservados;
  generarTabla(datatable);
}

function onResponseData(data) {
  var datatable = data.pedidosObservados;
  generarTabla(datatable);
}
function dibujarLeyendaAcciones() {
  var html = "<br><b>Leyenda:</b>&nbsp;&nbsp;";
  html +=
    "<i class='fa fa-eye' style='color:green;'></i>&nbsp; Detalle" +
    " &nbsp;&nbsp;&nbsp;" +
    "<i class='fa fa-cog' style='color:#E8BA2F;'></i>&nbsp; Reversar" +
    " &nbsp;&nbsp;&nbsp;" +
    "<i class='fa fa-building-o' style='color:#f44336;'></i>&nbsp; P. En Mansiche" +
    " &nbsp;&nbsp;&nbsp;";
  $("#divLeyenda").html(html);
}
function reversarComprobante(documento_id) {
  swal(
    {
      title: "Estás seguro?",
      text:
        "Si finaliza el reverso del comprobante " +
        documento_id +
        " ya no podrá modificar posteriormente.",

      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#33b86c",
      confirmButtonText: "Si, finalizar!",
      cancelButtonColor: "#d33",
      cancelButtonText: "No, cancelar !",
      closeOnConfirm: false,
      closeOnCancel: true,
    },
    function (isConfirm) {
      if (isConfirm) {
        ax.setAccion("reversarPedido");
        ax.addParamTmp("documento_id", documento_id);
        ax.consumir();
        swal(
          {
            title: "Reversado!",
            text: "Documento fue reversado correctamente.",
            type: "success",
          },
          function () {
            // $("#" + documento_id)
            //   .parent()
            //   .parent()
            //   .parent()
            //   .parent()
            //   .hide();
            obtenerConfiguracionesInicialesObservaciones();
          }
        );
      } else {
        //$('#modalAsignarCodigoUnico').modal('show');
      }
    }
  );
}
function buscarDataObservacion() {
  loaderShow();
  var fInicio = document.getElementById("fInicio").value;
  var fFinal = document.getElementById("fFinal").value;
  ax.setAccion("obtenerDataPorFiltros");
  ax.addParamTmp("fInicio", fInicio);
  ax.addParamTmp("fFinal", fFinal);
  ax.consumir();
}
function descargarExcel() {
  loaderShow();
  var fInicio = document.getElementById("fInicio").value;
  var fFinal = document.getElementById("fFinal").value;
  ax.setAccion("exportarDataExcel");
  ax.addParamTmp("fInicio", fInicio);
  ax.addParamTmp("fFinal", fFinal);
  ax.consumir();
}
function detalleOnservacion(documento_id) {
  ax.setAccion("mostrarDetalleDocumentoObservacion");
  ax.addParamTmp("documento_id", documento_id);
  ax.consumir();
}
function obtenerDetalleObservacion(data) {
  console.log(data.detalledepedido);
}

var fechaInicioInput = document.getElementById("fInicio");
var fechaFinInput = document.getElementById("fFinal");

fechaInicioInput.addEventListener("input", function () {
  var fechaInicio = new Date(fechaInicioInput.value);
  fechaInicio.setDate(fechaInicio.getDate() + 1); // Añade un día para no incluir la fecha inicial
  var fechaFinMin = new Date(fechaInicio); // Clona la fecha inicial para usarla como fecha mínima
  fechaFinMin.setFullYear(fechaInicio.getFullYear() + 1); // Añade un año a la fecha mínima

  fechaFinInput.disabled = false;
  fechaFinInput.min = formatDate(fechaInicio);
  fechaFinInput.max = formatDate(fechaFinMin); // Establece la fecha máxima un año después de la fecha inicial

  if (new Date(fechaFinInput.value) < fechaInicio) {
    fechaFinInput.value = formatDate(fechaInicio);
  }
});

fechaFinInput.addEventListener("input", function () {
  var fechaInicio = new Date(fechaInicioInput.value);
  var fechaFin = new Date(fechaFinInput.value);
  if (fechaFin < fechaInicio) {
    fechaFinInput.value = formatDate(fechaInicio);
  }
});

function formatDate(date) {
  var year = date.getFullYear();
  var month = String(date.getMonth() + 1).padStart(2, "0");
  var day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}
