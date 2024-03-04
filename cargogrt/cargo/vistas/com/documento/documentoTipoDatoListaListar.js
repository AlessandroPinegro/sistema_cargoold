
function listarDocumentoTipoDatoLista() {
    ax.setSuccess("successlistarDocumentoTipoDatoLista");
    cargarDatagrid();

}
function nuevoDocumentoTipoDatoLista()
{
    cargarDivTitulo('#window', 'vistas/com/documento/documentoTipoDatoListaForm.php', "Nuevo " + obtenerTitulo());
}
function successlistarDocumentoTipoDatoLista(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarDocumentoTipoDatoLista':
                crearTablaDocumentoTipoDatoLista(response.data);
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
            case 'eliminarDocumentoTipoDatoLista':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", " " + response.data['0'].descripcion + ".", "success");
                    cargarDatagrid();
                } else {
                    swal("Cancelado", "" + response.data['0'].descripcion + " " + response.data['0'].vout_mensaje + " en el mantenedor equivalencia de unidades", "error");
                }
                bandera_eliminar = true;
                break;
            case 'cambiarEstado':
                if (response.data[0].vout_exito == 0)
                {
                    $.Notification.autoHideNotify('warning', 'top right', 'Validación', response.data[0]["vout_mensaje"]);
                } else {
                    cambiarIconoEstado(response.data);
                }
                break;
        }
    }
}
function cargarDatagrid()
{
    ax.setAccion("listarDocumentoTipoDatoLista");
    ax.consumir();

}
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
function crearTablaDocumentoTipoDatoLista(data) {
    $("#datatableListar").empty();
    var editar = "";
    var cuerpo_total = '';
    var cuerpo = '';
    var cabeza = '<table id="datatable" class="table table-striped table-bordered"><thead>' +
            " <tr>" +
            "<th style='text-align:center;' width=200px>Descripción</th>" +
            "<th style='text-align:center;' width=100px>Tipo de dato</th>" +
            "<th style='text-align:center;'width=100px>Valor</th>" +
            "<th style='text-align:center;' width=100px>Estado</th>" +
            "<th style='text-align:center;' width=100px>Acciones</th>" +
            "</tr>" +
            "</thead>";
    if (!isEmpty(data))
    {
        $.each(data, function (index, item) {
            var comentario = item.comentario;
            var valor = item.valor;
            if (item.comentario == null)
            {
                comentario = '';
            }
            if(item.valor==null)
            {
                valor='';
            }
            if (item.estado == 1)
            {
                editar = "<a href='#' onclick='prepararEditarLineaCredito(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n";
            } else
            {
                editar = "<a href='#' ><b><i class='fa fa-edit' style='color:#848484;'></i><b></a>&nbsp;\n";
            }
//            var valor = item.valor;
//            var editar ="<b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n"
            cuerpo = "<tr>" +
                    "<td style='text-align:left;'>" + item.descripcion + "</td>" +
                    "<td style='text-align:left;'>" + item.documento_tipo_dato_descripcion + "</td>" +
                    "<td style='text-align:left;'>" + valor + "</td>" +
                    "<td style='text-align:center;'><a href='#' onclick = 'cambiarEstado(" + item.id + ")' ><b><i  id='" + item.id + "'  class='" + item.icono + "' style='color:" + item.color + ";'></i><b></a></td>" +
                    "<td style='text-align:center;'>" +
                    "<a href='#' onclick='prepararEditarDocumentoTipoDatoLista(" + item.id + ")'><b><i class='fa fa-edit' style='color:#E8BA2F;'></i><b></a>&nbsp;\n" +
                    "<a href='#' onclick='confirmarEliminar(" + item.id + ", \"" + item.descripcion + "\")'><b><i class='fa fa-trash-o' style='color:#cb2a2a;'></i><b></a>" +
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
function prepararEditarDocumentoTipoDatoLista(id) {
    cargarDivTitulo("#window", "vistas/com/documento/DocumentoTipoDatoListaForm.php?id=" + id + "&" + "tipo=" + 1, "Editar " + obtenerTitulo());
}
function confirmarEliminar(id, descripcion) {
    bandera_eliminar = false;
    swal({
        title: "Est\xe1s seguro?",
        text: "Eliminarás " + descripcion + "",
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
            eliminarDocumentoTipoDatoLista(id, descripcion);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminaci\xf3n fue cancelada", "error");
            }
        }
    });
}
function eliminarDocumentoTipoDatoLista(id, descripcion)
{
    ax.setAccion("eliminarDocumentoTipoDatoLista");
    ax.addParamTmp("documentoTipoDatoListaId", id);
    ax.addParamTmp("descripcion", descripcion);
    ax.consumir();
//    cargarPantallaListar();
}

function obtenerTitulo()
{
    TITULO = $("#titulo").text();
    var titulo = TITULO;
    $("#window").empty();

    if (!isEmpty(titulo))
    {
        titulo = titulo.toLowerCase();
    }
    return titulo;
}

function cargarPantallaListar()
{
    cargarDivTitulo("#window", "vistas/com/documento/documentoTipoDatoListaListar.php", TITULO);
}