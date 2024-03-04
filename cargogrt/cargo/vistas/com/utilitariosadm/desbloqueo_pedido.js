$(document).ready(function () {
  $('[data-toggle="popover"]').popover({ html: true }).popover();
  cargarTitulo("titulo", "");
  select2.iniciar();
  ax.setSuccess("successDesbloqueos");
  obtenerConfiguracionesInicialesDesbloqueo();
  $('#divLeyenda').show();
});

function successDesbloqueos(response) {
  $("#divLeyenda").show();
  if (response["status"] === "ok") {
    switch (response[PARAM_ACCION_NAME]) {
      case "obtenerConfiguracionesInicialesDesbloqueo":
        onResponseobtenerConfiguracionesInicialesDesbloqueo(response.data);
        // console.log(response.data);
        $("#divLeyenda").show();
        break;
         case "obtenerDataDesbloqueos":
          onResponseAjaxpGetDataGridDesbloqueos(response.data);
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
              }
          });
          loaderClose();
          break;
    }
  }
}

function obtenerConfiguracionesInicialesDesbloqueo() {
  //        loaderShow();
  ax.setAccion("obtenerConfiguracionesInicialesDesbloqueo");
  ax.addParamTmp("empresaId", commonVars.empresa);
  ax.consumir();
}

function onResponseobtenerConfiguracionesInicialesDesbloqueo(data) {
  dataConfiguracionInicial = data;
  select2.cargar(
    "cboAgencia",
    dataConfiguracionInicial.dataAgenciaUsuario,
    "id",
    "descripcion"
  );
  if (dataConfiguracionInicial.dataAgenciaUsuario.length == 1) {
    $("#cboAgencia").prop("disabled", true);
  } else {
    $("#cboAgencia").prop("disabled", false);
  }
  select2.asignarValor(
    "cboAgencia",
    dataConfiguracionInicial.dataAgenciaUsuario[0]["id"]
  );
  dibujarLeyendaAcciones();

  var integracion_agencia_id= (dataConfiguracionInicial.dataAgenciaUsuario[0]["id"]);
  ax.setAccion("obtenerDataDesbloqueos");
  ax.addParamTmp("agenciaId", integracion_agencia_id);
  ax.consumir();
}

function onChangeCboAgencia(valor) {
   ax.setAccion("obtenerDataDesbloqueos");
   ax.addParamTmp("agenciaId", valor);
   ax.consumir();
}
function onResponseAjaxpGetDataGridDesbloqueos(data){
  var data  = data.desbloqueo
$("#dataListDesbloqueos").empty();
 var  cuerpo_total ='';
  var cuerpo ='';
  var cabeza = '<table id="datatable"  class="table table-striped table-bordered"><thead>' + // #ffffff
  " <tr style='background-color:#f77816;'>" +
  // "<th style='text-align:center;' width=100px>Codigo</th>" +
  "<th style='text-align:center; color:#ffffff' width=80px>Pedido</th>" +
  "<th style='text-align:center; color:#ffffff'>F. Pedido</th>" +
  "<th style='text-align:center; color:#ffffff'>Cliente</th>" +
  "<th style='text-align:center; color:#ffffff'>Origen</th>" +
  "<th style='text-align:center; color:#ffffff'>Total</th>" +
  "<th style='text-align:center; color:#ffffff'>Comp.</th>" +
  "<th style='text-align:center; color:#ffffff'>F. Emision</th>" +
  "<th style='text-align:center; color:#ffffff'>Nro. Comp.</th>" +
 "<th style='text-align:center; color:#ffffff' width=80px>Estado</th>" +
  "<th style='text-align:center; color:#ffffff' width=60px>Acciones</th>" +
  "</tr>" +
  "</thead>";
  if (!isEmpty(data)) {
    $.each(data, function (index, item) {
        cuerpo = "<tr>" +
        // "<td style='text-align:left;'>" + item.id + "</td>" +
        "<td style='text-align:left;'>" + item.serie_numero + "</td>" +
        "<td style='text-align:left;'>" + item.fecha_emision + "</td>" +
        "<td style='text-align:left;'>" + item.persona_nombre + "</td>" +
        "<td style='text-align:left;'>" + item.agencia_origen + "</td>" +
        "<td style='text-align:left;'>" + item.total + "</td>" +
        "<td style='text-align:left;'>" + item.comprobante_tipo + "</td>" +
        "<td style='text-align:left;'>" + item.fecha_emision + "</td>" +
        "<td style='text-align:left;'>" + item.comprobante_serie_numero + "</td>" +
        "<td style='text-align:left;'>" + item.documento_estado_negocio_descripcion + "</td>" +
        "<td style='text-align:center;'>" +
            "<a href='#' onclick='updateconfirmarDesbloqueo(\"" + item.comprobante_serie_numero + "\")' ><b><i class='fa fa-lock' style='color:#5cb85c;'></i><b></a>&nbsp;\n" +
            "</td>" +
        "</tr>";
        cuerpo_total = cuerpo_total + cuerpo;
    });
  }
  var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
  $("#dataListDesbloqueos").append(html);
 
  loaderClose();
}
 function  updateconfirmarDesbloqueo(comprobante_serie_numero){
   var valor = comprobante_serie_numero
    
    var partes= valor.split('-');
    var serie = partes[0];
    var correlativo = partes[1];
    swal({
      title: "Estás seguro?",
      text: "Si finaliza el desbloqueo del comprobante " + comprobante_serie_numero + " ya no podrá modificar posteriormente.",
      
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
          console.log("llego");
          ax.setAccion("confirmarDesbloqueo");
          ax.addParamTmp("serie", serie);
          ax.addParamTmp("correlativo", correlativo);
          ax.consumir();
          swal({
            title: "Desbloqueado!",
            text: "Documento Desbloqueado correctamente.",
            type: "success"
          }, function () {
            obtenerConfiguracionesInicialesDesbloqueo();
          });
  
         
      } else {
          $('#modalAsignarCodigoUnico').modal('show');
      }
  });

 }
 function dibujarLeyendaAcciones(data) {
  var  Descripcion= "Desbloquear";
  var html = '<br><b>Leyenda:</b>&nbsp;&nbsp;';
              html += "<i class='fa fa-lock' style='color:#5cb85c;'></i>&nbsp;" + Descripcion + " &nbsp;&nbsp;&nbsp;";
  $('#divLeyenda').html(html);
}


