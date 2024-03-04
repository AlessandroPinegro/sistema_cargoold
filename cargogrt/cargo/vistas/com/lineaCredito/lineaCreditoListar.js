var spiner = $('#env i').attr('class');

function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}
function cambiarIconoEstado(data)
{
    document.getElementById(data[0].id_estado).className = data[0].icono;
    document.getElementById(data[0].id_estado).style.color = data[0].color;
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}
function listarLineaCredito() {
    ax.setSuccess("successLineaCredito");
    ax.setAccion("listarLineaCredito");
    ax.consumir();
}
function successLineaCredito(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarLineaCredito':
                crearTablaLineaCredito(response.data);
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
                break;
           case 'cambiarEstado':
                if(response.data[0].vout_exito==0)
                {
                     $.Notification.autoHideNotify('warning', 'top right', 'Validación',response.data[0]["vout_mensaje"]);
                }else{
                cambiarIconoEstado(response.data);
                }
                break;
        }
    }
}

function crearTablaLineaCredito(data) {
    $("#datatable2").empty();
    var editar ="";
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=200px>Clase de persona</th>" +
            "<th style='text-align:center;' width=100px>Valor</th>" +
            "<th style='text-align:center;'width=100px>Dias</th>" +
            "<th style='text-align:center;'width=100px>Periodo de Gracia</th>" +
            "<th style='text-align:center;'width=100px>Moneda</th>" +
            "<th style='text-align:center;' width=100px>Estado</th>" +
            "<th style='text-align:center;' width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var comentario = item.comentario;
            if (item.comentario == null)
            {
                comentario = '';
            }
            if(item.estado==1)
            {
                editar = "<a href='#' onclick='prepararEditarLineaCredito(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n";
            }else
            {
                editar = "<a href='#' ><b><i class='fa fa-edit' style='color:#848484;'></i><b></a>&nbsp;\n";
            }
//            var valor = item.valor;
//            var editar ="<b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n"
            cuerpo = "<tr>" +
                    "<td style='text-align:center;'>" + item.persona_clase + "</td>" +
                    "<td style='text-align:center;'>" +  (parseFloat(item.valor)).toFixed('2') + "</td>" +
                    "<td style='text-align:center;'>" + item.dias + "</td>" +
                    "<td style='text-align:center;'>" + item.periodo_gracia + "</td>" +
                    "<td style='text-align:center;'>" + item.moneda + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i  id='" + item.id + "'  class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    editar
                    +
                    "<a href='#' onclick='confirmarEliminarLineaCredito(" + item.id + ", \"" + item.persona_clase + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
                    "</td>" +
                    "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }

    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#datatableListar").append(html);
    loaderClose();
}
function nuevaLineaCredito()
{
    cargarDivTitulo('#window', 'vistas/com/lineaCredito/lineaCreditoForm.php',"Nuevo "+obtenerTitulo());

}

function prepararEditarLineaCredito(id) {
    cargarDivTitulo("#window", "vistas/com/lineaCredito/lineaCreditoForm.php?id=" + id + "&" + "tipo=" + 1,"Editar "+obtenerTitulo());
}

function confirmarEliminarLineaCredito(lineaCreditoId, personaClase) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + personaClase + "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si,eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No,cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            eliminarLineaCredito(lineaCreditoId, personaClase);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function eliminarLineaCredito(lineaCreditoId, personaClase)
{
    ax.setAccion("eliminarLineaCredito");
    ax.addParamTmp("lineaCreditoId", lineaCreditoId);
    ax.addParamTmp("personaClase", personaClase);
    ax.consumir();
    cargarPantallaListar()
}

function obtenerTitulo()
{
    tituloGlobal = $("#titulo").text();
    var titulo =  tituloGlobal;
    $("#window").empty();
    
    if(!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar()
{
    cargarDivTitulo("#window", "vistas/com/lineaCredito/lineaCreditoListar.php",tituloGlobal);
}