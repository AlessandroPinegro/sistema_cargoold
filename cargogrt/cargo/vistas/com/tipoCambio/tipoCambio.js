/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    //loaderShow();
    ax.setSuccess("exitoTipoCambio");
    listarTipoCambio();

    //altura();
});

function listarTipoCambio(){
    ax.setAccion("listarTipoCambio");
    ax.consumir();
}

function exitoTipoCambio(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarTipoCambio':
                onResponseListarTipoCambio(response.data);
                onResponseVacio();
                loaderClose();
                break;
            case 'cambiarEstado':
                cambiarIconoEstado(response.data);
                listarTipoCambio();
                break;
            case 'eliminar':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "El tipo de cambio con fecha: " + response.data['0'].fecha + ".", "success");
                    listarTipoCambio();
                } else {
                    swal("Cancelado", "El tipo de cambio con fecha: " + response.data['0'].fecha + ". " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
        }
    }
}

function cambiarIconoEstado(data)
{
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function nuevo() {
    var titulo = "Nuevo";
    var url = URL_BASE + "vistas/com/tipoCambio/tipoCambio_form.php?winTitulo=" + titulo;
    cargarDiv("#window", url);
}

function verProximosVencimientos() {
    var url = URL_BASE + "vistas/com/vigocu/emoProximosVencimientos.php";
    cargarDiv("#window", url);
}

function onResponseListarTipoCambio(data) {
    $("#dataList").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cuerpo_acc = "";    
    
    var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
            + "<th style='text-align:center; vertical-align: middle;'>Fecha</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Moneda</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Equivalencia compra</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Equivalencia venta</th>"
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
                    + "<a href='#' onclick='confirmarEliminar(" + item.id + ",\"" + item.fecha + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n";

            cuerpo = "<tr>"
                    + "<td style='text-align:center;'>" + (item.fecha).substr(0, 10) + "</td>"
                    + "<td style='text-align:center;'>" + item.descripcion+" - "+item.simbolo + "</td>"
                    + "<td style='text-align:center;'>" + item.simbolo_base+"  "+item.equivalencia_compra + "</td>"
                    + "<td style='text-align:center;'>" + item.simbolo_base+"  "+ item.equivalencia_venta + "</td>";
            
            //Estado
            if (item.estado === 1) {
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
        "order": [[ 0, 'desc' ]],
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

function editar(id) {
    var titulo = "Editar";
    var url = URL_BASE + "vistas/com/tipoCambio/tipoCambio_form.php?winTitulo=" + titulo + "&id=" + id;
    cargarDiv("#window", url);
}

function cambiarEstado(id_estado)
{
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id_estado", id_estado);
    ax.consumir();
}

var bandera_eliminar = false;

function confirmarEliminar(id, nom) {
    bandera_eliminar = false;
    swal({
        title: "Estás seguro?",
        text: "Eliminaras el tipo de cambio de fecha: " + nom + "!",
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