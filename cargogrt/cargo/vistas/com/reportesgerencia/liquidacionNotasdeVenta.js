$(document).ready(function () {
    $('[data-toggle="popover"]').popover({ html: true }).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    ax.setSuccess("successLiquidacionNotasdeVenta");
    reporteLiquidacionDeNotasDeVenta();
    $("#divLeyenda").show();
  });
  function successLiquidacionNotasdeVenta(response) {
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
        "scrollX": true,
        "bFilter": true,
        bDestroy: true,
        createdRow: function (row, response, dataIndex) {
          //console.log(response);
          if (response[7] === "") {
            $(row).css("background-color", "#FFE0B2"); 
          }else{
            $(row).css("background-color", "#C8E6C9"); 
          }
        },
      };
      switch (response[PARAM_ACCION_NAME]) {
        case "reporteLiquidacionDeNotasDeVenta":
          obtenerData(response.data);
          loaderShow();
          break;
  
        case "reporteLiquidacionDeNotasDeVenta":
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

  function descargarExcel() {
    loaderShow();
    var fInicio = document.getElementById("fInicio").value;
    var fFinal = document.getElementById("fFinal").value;
    ax.setAccion("exportarDataExcel");
    ax.addParamTmp("fechaInicio", fInicio);
    ax.addParamTmp("fechaFin", fFinal);
    ax.consumir();
  }
  function buscarDataObservacion() {
     loaderShow();
        var fInicio = document.getElementById("fInicio").value;
        var fFinal = document.getElementById("fFinal").value;
        ax.setAccion("reporteLiquidacionDeNotasDeVenta");
        ax.addParamTmp("fechaInicio", fInicio);
        ax.addParamTmp("fechaFin", fFinal);
        ax.consumir();
  }

  function reporteLiquidacionDeNotasDeVenta() {
    loaderShow();
    ax.setAccion("reporteLiquidacionDeNotasDeVenta");
    // ax.addParamTmp("fechaInicio", fInicio);
    // ax.addParamTmp("fechaFin", fFinal);
    ax.consumir();
  }
  function obtenerData(data) {
    loaderClose();
    var datatable = data
    generarTabla(datatable);
  }
  function onResponseData(data) {
    var datatable = data
    generarTabla(datatable);
  }

  function generarTabla(datatable) {
    var cuerpo_total = "";
    var cabeza =
        '<table id="datatable"  class="table table-striped table-bordered"><thead>' +
        '<tr style="background-color:#f77816;">' +
        '<th style="text-align:center; color:#ffffff; "width=6%>Comprobante</th>' +
        '<th style="text-align:center; color:#ffffff; " width=45% >Cliente</th>' +
        '<th style="text-align:center; color:#ffffff;  width=6%">Agencia Origen</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%> Agencia Destino</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>F.NotaVenta</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>Total</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>Descripcion</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>Comp.Liquidacion</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>F. Liqu</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>Total Liqu</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>facturacredito</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>fechafc</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%>totalfc</th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%> G R R Transportista </th>' +
        '<th style="text-align:center; color:#ffffff; "width=6%> G R T Electronica </th>' +
        '</tr>' +
        '</thead>';

    if (!isEmpty(datatable)) {
        $.each(datatable, function (index, item) {
            var cuerpo =
                "<tr>" +
                "<td style='text-align:left;'>" + item.serie + '-' + item.numero + "</td>" +
                "<td style='text-align:left;'>" + item.cliente + "</td>" +
                "<td style='text-align:left;'>"  + item.origen + "</td>" +
                "<td style='text-align:left;'>" + item.destino + "</td>" +
                "<td style='text-align:left;'>" + item.fechanota + "</td>" +
                "<td style='text-align:left;'>" + "S/." + formatearNumero(item.total) + "</td>" +
                "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                "<td style='text-align:left;'>" + (item.liquidacion == null ? '' : item.liquidacion) + "</td>" +
                "<td style='text-align:left;'>" + (item.fechaliq == null ? '' : item.fechaliq) + "</td>" +
                "<td style='text-align:left;'>" + "S/." + formatearNumero(item.totalliq) + "</td>" +
                "<td style='text-align:left;'>" + (item.facturacredito == null ? '' : item.facturacredito) + "</td>" +
                "<td style='text-align:left;'>" + (item.fechafc == null ? '' : item.fechafc) + "</td>" +
                "<td style='text-align:left;'>" + "S/." + formatearNumero(item.totalfc) + "</td>" +
                "<td style='text-align:left;'>"  + item.GRRT + "</td>" +
                "<td style='text-align:left;'>" + item.GRTE + "</td>" +
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
function dibujarLeyendaAcciones() {
    var pendiente = "Pendiente por facturar";
    var facturado = "Facturado";
    var html = "<br><b>Leyenda:</b>&nbsp;&nbsp;";
    html +=
      "<i  class='fa fa-circle' aria-hidden='true' style='color:#FFE0B2;  font-size: 20px'> </i>" + pendiente + " &nbsp;&nbsp;&nbsp;" +
      "<i class='fa fa-circle' aria-hidden='true' style='color:#C8E6C9;  font-size: 20px'></i>" +      facturado  
    $("#divLeyenda").html(html);
  }
//   "background-color", "#FFCDD2"


  var fechaInicioInput = document.getElementById("fInicio");
  var fechaFinInput = document.getElementById("fFinal");
  
  fechaInicioInput.addEventListener("input", function () {
    var fechaInicio = new Date(fechaInicioInput.value);
    fechaInicio.setDate(fechaInicio.getDate() + 1); // Añade un día para no incluir la fecha inicial
    var fechaFinMin = new Date(fechaInicio); // Clona la fecha inicial para usarla como fecha mínima
    fechaFinMin.setMonth(fechaFinMin.getMonth() + 1); // Añade un mes a la fecha mínima
  
    fechaFinInput.disabled = false;
    fechaFinInput.min = formatDate(fechaInicio);
    fechaFinInput.max = formatDate(fechaFinMin); // Establece la fecha máxima un mes después de la fecha inicial
    fechaFinInput.value = fechaFinInput.min;
  
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
  