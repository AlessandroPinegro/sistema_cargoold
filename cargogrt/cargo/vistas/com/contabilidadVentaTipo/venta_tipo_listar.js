$(document).ready(function () {
    loaderClose();
    ax.setSuccess("exitoVentaTipo")
    listarVentaTipo();
});

function listarVentaTipo()
{
    ax.setAccion("listarVentaTipo");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function exitoVentaTipo(response)
{
    if (response.status === 'ok')
    {
        switch (response[PARAM_ACCION_NAME])
        {
            case 'listarVentaTipo':
                onResponseListarVentaTipo(response.data);
                onResponseVacio();
                loaderClose();
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                listarVentaTipo();
                break;
            case 'eliminar':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "El Tipo de Venta: " + response.data['0'].descripcion + ".", "success");
                    listarVentaTipo();
                } else {
                    swal("Cancelado", "El Tipo de Venta:  " + response.data['0'].descripcion + ". " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
        }
    }
}

function cambiarEstado(id)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cambiarIconoEstado(data)
{
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function onResponseListarVentaTipo(data)
{
    //console.log(data);

    $("#dataList").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cuerpo_acc = "";

    var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>Código</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Descripción</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Código Exportación</th>"
            //+ "<th style='text-align:center; vertical-align: middle;'>Código SUNAT</th>"
            //+ "<th style='text-align:center; vertical-align: middle;'>Tipo cálculo</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Estado</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Acciones</th>"
            + "</tr>"
            + "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            cuerpo_acc = "";
            //Acciones
            cuerpo_acc = cuerpo_acc
                    + "<a href='#' onclick='editar(" + item.id + ")'><i class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;\n"
                    + "<a href='#' onclick='confirmarEliminar(" + item.id + ",\"" + item.descripcion + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n";

            cuerpo = "<tr>"
                    + "<td style='text-align:left;'>" + item.codigo + "</td>"
                    + "<td style='text-align:left;'>" + item.descripcion + "</td>"
                    + "<td style='text-align:left;'>" + item.cod_tipo_exportacion + "</td>"
                    //+ "<td style='text-align:left;'>" + item.sunat_detalle_codigo_descripcion + "</td>"
                    //+ "<td style='text-align:left;'>" + item.tipo_calculo_descripcion + "</td>"
                    ;

            //Estado
            if (item.estado === "1") {
                cuerpo = cuerpo + "<td style='text-align:center;'><a onclick ='cambiarEstado(" + item['id'] + ")' ><b><i class='ion-checkmark-circled' style='color:#5cb85c'></i><b></a></td>";
            } else {
                cuerpo = cuerpo + "<td style='text-align:center;'><a onclick ='cambiarEstado(" + item['id'] + ")' ><b><i class='ion-flash-off' style='color:#cb2a2a'></i><b></a></td>";
            }

            cuerpo = cuerpo + "<td style='text-align:center;'>"
                    + cuerpo_acc
                    + "</td>"
                    + "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);
}

function onResponseVacio() {
    $('#datatable').dataTable({
        "order": [[0, 'asc']],
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
}

function nuevo() {
    var titulo = "Nuevo";
    var url = URL_BASE + "vistas/com/contabilidadVentaTipo/venta_tipo_form.php?winTitulo=" + titulo;
    cargarDiv("#window", url);
}

function editar(id) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/contabilidadVentaTipo/venta_tipo_form.php?winTitulo=" + titulo + "&id=" + id;
    cargarDiv("#window", url);
}

var bandera_eliminar = false;

function confirmarEliminar(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Estás seguro?",
        text: "Eliminarás el Tipo de Venta: " + nom + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {

        if (isConfirm) {
            eliminar(id, nom);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function eliminar(id, nom)
{
    ax.setAccion("eliminar");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}