
$(document).ready(function () {
    cargarTitulo("titulo", "");
    ax.setSuccess("exitoProgramacionPagoConfiguracion");
    listarProgramacionPagoConfiguracion();
    modificarAnchoTabla('datatable');
});

function listarProgramacionPagoConfiguracion() {
    loaderShow();
    ax.setAccion("listarProgramacionPagoConfiguracion");
    ax.consumir();
}

function exitoProgramacionPagoConfiguracion(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarProgramacionPagoConfiguracion':
                onResponseListarProgramacionPagoConfiguracion(response.data);
                loaderClose();
                break;
            case 'eliminar':
                loaderClose();
                var exito = response.data['0'].vout_exito;
                if (exito == 1) {
                    swal("Eliminado!", response.data['0'].vout_mensaje, "success");
                    listarProgramacionPagoConfiguracion();
                }
                break;
            case 'actualizarEstado':
                loaderClose();
                var exito = response.data['0'].vout_exito;
                if (exito == 1) {
                    mostrarOk(response.data['0'].vout_mensaje);
                    listarProgramacionPagoConfiguracion();
                }
                break;
        }
    }
}

function nuevo() {
    var titulo = "Nueva";
    var url = URL_BASE + "vistas/com/programacionPago/programacion_pago_configuracion_form.php?winTitulo=" + titulo;
    cargarDiv("#window", url);
}

function onResponseListarProgramacionPagoConfiguracion(data) {
    if (!isEmpty(data)) {
        $('#datatable').dataTable({
            "scrollX": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "data": data,
//Descripcion	Proveedor	Grupo de productos	Comentario	Porcentajes	Estado	Acc.
            "columns": [
                {"data": "descripcion"},
                {"data": "persona_nombre_completo"},
                {"data": "bien_tipo_descripcion"},
                {"data": "comentario"},
                {"data": "porcentajes"},
                {"data": "estado", "sClass": "alignCenter"},
                {"data": "ppc_id", "sClass": "alignCenter"}
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        var html = '';
                        if (data == 1) {
                            html = '<a onclick ="actualizarEstado(' + row.ppc_id + ',0)" ><b><i class="ion-checkmark-circled" style="color:#5cb85c"></i><b></a>';
                        } else {
                            html = '<a onclick ="actualizarEstado(' + row.ppc_id + ',1)"><b><i class="ion-flash-off" style="color:#cb2a2a"></i><b></a>';
                        }
                        return   html;
                    },
                    "targets": 5
                },
                {
                    "render": function (data, type, row) {
                        var descripcionPP = row.descripcion;
                        descripcionPP = descripcionPP.replace(/\'/g, " ");
                        descripcionPP = descripcionPP.replace(/\"/g, " ");
                        return   "<a href='#' onclick='editarProgramacionPagoConfiguracion(" + data + ")'><i class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;\n"
                                + "<a href='#' onclick='confirmarEliminar(" + data + ",\"" + descripcionPP + "\")'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n";
                    },
                    "targets": 6
                }
            ],
            "destroy": true
        });
    } else
    {
        var table = $('#datatable').DataTable();
        table.clear().draw();
    }
}

function editarProgramacionPagoConfiguracion(id){
    loaderShow();
    cargarDiv('#window', "vistas/com/programacionPago/programacion_pago_configuracion_form.php?winTitulo=Editar&id=" + id);
}

function confirmarEliminar(id, nom) {
    swal({
        title: "Estás seguro?",
        text: "Eliminarás la configuración de programación de pago con descripción: " + nom + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            eliminar(id, nom);
        } else {
            swal("Cancelado", "La eliminación fue cancelada", "error");
        }
    });
}

function eliminar(id, nom)
{
    loaderShow();
    ax.setAccion("eliminar");
    ax.addParamTmp("id", id);
    ax.setTag(nom);
    ax.consumir();
}

function actualizarEstado(id,nuevoEstado){
    loaderShow();
    ax.setAccion("actualizarEstado");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nuevoEstado", nuevoEstado);
    ax.consumir();    
}