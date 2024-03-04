$(document).ready(function () {
    loaderShow();
    loaderClose();
    ax.setSuccess("exitoPeriodo");
    listarPeriodo();

    //altura();
});

function listarPeriodo() {
    ax.setAccion("listarPeriodo");
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function exitoPeriodo(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'listarPeriodo':
                onResponseListarPeriodo(response.data);
                onResponseVacio();
                loaderClose();
                break;
            case 'cambiarEstado':
                exitoRespuesta(response.data);
                loaderClose();
                break;
            case 'eliminar':
                var error = response.data['0'].vout_exito;
                if (error > 0) {
                    swal("Eliminado!", "El periodo: " + response.data['0'].descripcion + ".", "success");
                    listarPeriodo();
                } else {
                    swal("Cancelado", "El periodo: " + response.data['0'].descripcion + ". " + response.data['0'].vout_mensaje, "error");
                }
                bandera_eliminar = true;
                break;
            case 'cerrarPeriodo':
                exitoRespuesta(response.data);
                loaderClose();
                break;
            case 'abrirPeriodo':
                exitoRespuesta(response.data);
                loaderClose();
                break;
            case 'cerrarPeriodoContable':
                exitoRespuesta(response.data);
                loaderClose();
                break;
            case 'obtenerConfiguracionesInicialesGenerarPeriodoPorAnio':
                onResponseObtenerConfiguracionesInicialesGenerarPeriodoPorAnio(response.data);
                loaderClose();
                break;
            case 'generarPeriodoAnio':
                onResponseGenerarPeriodoAnio(response.data);
                loaderClose();
                break;
            case 'cerrarPeriodoReabierto':
                exitoRespuesta(response.data);
                loaderClose();
                break;
            case 'actualizarBanderaModificacion':
                exitoRespuesta(response.data);
                loaderClose();
                break;
        }
    }
}

function cambiarIconoEstado(data)
{
    $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', 'Estado actualizado');
}

function exitoRespuesta(data) {
    if (data[0]["vout_exito"] == 0) {
        $.Notification.autoHideNotify('warning', 'top right', 'Validación', data[0]["vout_mensaje"]);
    } else {
        $.Notification.autoHideNotify('success', 'top-right', '&Eacute;xito', data[0]["vout_mensaje"]);
        listarPeriodo();
    }
}

function nuevo() {
    var titulo = "Nuevo";
    var url = URL_BASE + "vistas/com/periodo/periodo_form.php?winTitulo=" + titulo;
    cargarDiv("#window", url);
}

function onResponseListarPeriodo(data) {
//    console.log(data);
    $("#dataList").empty();
    var cuerpo_total = "";
    var cuerpo = "";
    var cuerpo_acc = "";
    var accion_cierre = "";

    var cabeza = "<table id='datatable' class='table table-striped table-bordered'>"
            + "<thead>"
            + "<tr>"
//            + "<th style='text-align:center; vertical-align: middle;'>Código</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Año</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Mes</th>"
//            + "<th style='text-align:center; vertical-align: middle;'>Estado</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Indicador</th>"
            + "<th style='text-align:center; vertical-align: middle;'>Acciones</th>"
            + "</tr>"
            + "</thead>";
    if (!isEmpty(data)) {
        $.each(data, function (index, item) {
            //Acciones
            cuerpo_acc =
//                    "<a href='#' onclick='editar(" + item.id + ","+item.indicador+")'><i class='fa fa-edit' style='color:#E8BA2F;' title='Editar periodo'></i></a>&nbsp;\n"
                    "<a href='#' onclick='confirmarEliminar(" + item.id + ",\"" + item.anio + "\",\"" + item.mes_descripcion + "\")'  title='Eliminar periodo'><i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>&nbsp;\n"
                    ;
            //SOLO SE PUEDE ELIMINAR LO QUE ESTA REGISTRADO
            if (item.indicador * 1 != 1) {
                cuerpo_acc = '';
            }

            cuerpo = "<tr>"
//                    + "<td style='text-align:left;'>" + item.documento_tipo_codigo + "</td>"
                    + "<td style='text-align:left;'>" + item.anio + "</td>"
                    + "<td style='text-align:left;'>" + item.mes_descripcion + "</td>";

            //Estado
//            if (item.estado === "1") {
//                cuerpo = cuerpo + "<td style='text-align:center;'><a onclick ='cambiarEstado(" + item['id'] + ")' ><b><i class='ion-checkmark-circled' style='color:#5cb85c'></i><b></a></td>";
//            } else {
//                cuerpo = cuerpo + "<td style='text-align:center;'><a onclick ='cambiarEstado(" + item['id'] + ")' ><b><i class='ion-flash-off' style='color:#cb2a2a'></i><b></a></td>";
//            }
            switch (item.indicador * 1) {

                case 0:
                    accion_cierre = '';
                    if (item.anio * 1 != 2016) {
                        accion_cierre = "<a href='#' onclick='confirmarReabrirPeriodo(" + item.id + ",\"" + item.anio + "\",\"" + item.mes_descripcion + "\")'><i class='ion-unlocked' style='color:purple;' title='Reabrir periodo'></i></a>&nbsp;\n";
                    }
                    break;

                case 1:
                    accion_cierre = "<a href='#' onclick='abrirPeriodo(" + item.id + ")'><i class='ion-unlocked' style='color:green;' title='Abrir periodo'></i></a>&nbsp;\n";
                    break;
                case 2:
                    accion_cierre = "<a href='#' onclick='confirmarCerrarPeriodo(" + item.id + ",\"" + item.anio + "\",\"" + item.mes_descripcion + "\",\"" + item.contador_cierre_bien + "\")'><i class='ion-locked' style='color:red;' title='Cerrar periodo'></i></a>&nbsp;\n";
                    break;
                default:
                    accion_cierre = '';

            }

            switch (true) {
                case item.indicador_contabilidad * 1 == 2 && item.indicador * 1 == 0:
                    accion_cierre += "<a href='#' onclick='confirmarCerrarPeriodoContable(" + item.id + ",\"" + item.anio + "\",\"" + item.mes_descripcion + "\",\"" + item.contador_cierre_bien + "\")'><i class='ion-locked' style='color:#1ca8dd;' title='Cerrar periodo contable'></i></a>&nbsp;\n";
                    break;
            }
            cuerpo = cuerpo + "<td style='text-align:left;'>" + item.indicador_descripcion + "</td>";
            let chkPeriodo = '';
//            let chkPeriodo = `<label class="cr-styled"><input id="chkPeriodo` + (item.id) + `" name="chkPeriodo" type="checkbox" onchange='onChangeChkPeriodo(` + item.id + `)' `+(item.bandera_modificacion_contable == 1 ? `checked`: ``)+`><i class="fa"></i></label>`;

            cuerpo_acc += accion_cierre;
            cuerpo = cuerpo + "<td style='text-align:center;'>"
                    + chkPeriodo + cuerpo_acc
                    + "</td>"
                    + "</tr>";
            cuerpo_total = cuerpo_total + cuerpo;
        });
    }
    var pie = '</table>';
    var html = cabeza + cuerpo_total + pie;
    $("#dataList").append(html);

}

function onChangeChkPeriodo(id) {
    let valor = 0
    if (document.getElementById('chkPeriodo' + id).checked) {
        valor = 1;
    }
    loaderShow();
    ax.setAccion("actualizarBanderaModificacion");
    ax.addParamTmp("id", id);
    ax.addParamTmp("bandera_contabilidad", valor);
    ax.consumir();
}


function onResponseVacio() {
    $('#datatable').dataTable({
        "order": [[0, 'desc']],
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

function editar(id, indicador) {
    if (indicador == 1) {
        var titulo = "Editar";
        var url = URL_BASE + "vistas/com/periodo/periodo_form.php?winTitulo=" + titulo + "&id=" + id;
        cargarDiv("#window", url);
    } else {
        $.Notification.autoHideNotify('warning', 'top-right', 'Validación', 'No se puede editar periodo cerrado.');
    }
}

function cambiarEstado(id)
{
    loaderShow();
    ax.setAccion("cambiarEstado");
    ax.addParamTmp("id", id);
    ax.consumir();
}

var bandera_eliminar = false;

function confirmarEliminar(id, anio, mes) {
    bandera_eliminar = false;
    swal({
        title: "Estás seguro?",
        text: "Eliminarás el periodo: " + anio + " | " + mes + "!",
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
            eliminar(id, anio + " | " + mes);
        } else {
            if (bandera_eliminar == false)
            {
                swal("Cancelado", "La eliminación fue cancelada", "error");
            }
        }
    });
}

function confirmarReabrirPeriodo(id, anio, mes) {
    swal({
        title: "Estás seguro?",
        text: "Se reabrirá el periodo cerrado: " + anio + " | " + mes + "!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, reabrir!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            abrirPeriodo(id);
        }
    });
}

function confirmarCerrarPeriodo(id, anio, mes, contadorCierreBien) {
    var texto = '. Además se va actualizar las cantidades y costos para los periodos cerrados posteriores.';
    if (isEmpty(contadorCierreBien) || contadorCierreBien == 0) {
        texto = '';
    }

    swal({
        title: "Estás seguro?",
        text: "Se cerrará el periodo para administración: " + anio + " | " + mes + "!" + texto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, cerrar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            cerrarPeriodo(id);
        }
    });
}

function confirmarCerrarPeriodoContable(id, anio, mes, contadorCierreBien) {
    var texto = '. Además se va actualizar las cantidades y costos para los periodos cerrados posteriores.';
    swal({
        title: "Estás seguro?",
        text: "Se cerrará el periodo para contabilidad: " + anio + " | " + mes + "!" + texto,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, cerrar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar !",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            cerrarPeriodoContable(id);
        }
    });
}

function eliminar(id, nom)
{
    loaderShow();
    ax.setAccion("eliminar");
    ax.addParamTmp("id", id);
    ax.addParamTmp("nom", nom);
    ax.consumir();
}

function cerrarPeriodo(id) {
    loaderShow();
    ax.setAccion("cerrarPeriodo");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cerrarPeriodoContable(id) {
    loaderShow();
    ax.setAccion("cerrarPeriodoContable");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function cerrarPeriodoReabierto(id) {
    loaderShow();
    ax.setAccion("cerrarPeriodoReabierto");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function abrirPeriodo(id) {
    loaderShow();
    ax.setAccion("abrirPeriodo");
    ax.addParamTmp("id", id);
    ax.consumir();
}

function preparaGeneraPeriodoAnio() {
    loaderShow();
    ax.setAccion("obtenerConfiguracionesInicialesGenerarPeriodoPorAnio");
    ax.consumir();
}


function onResponseObtenerConfiguracionesInicialesGenerarPeriodoPorAnio(data) {
    select2.iniciarElemento('cboAnio');
    var date = new Date();
    var anioActual = date.getFullYear();

    var dataAnio = [];
//    if(!isEmpty(data)){        
//        var anioMasUno=data[0]['anio']*1+1;
//        dataAnio.push({anio:anioMasUno});
//        
//        //RECORRO EL ARRAY DE AÑOS PARA INSERTARLOS EN ANIO    
//        $.each(data, function (i, itemAnio) {
//            dataAnio.push({anio:itemAnio['anio']});
//        });
//    }else{
//        dataAnio.push({anio:anioActual});
//    }

    dataAnio = data;

    select2.cargarAsignaUnico('cboAnio', dataAnio, 'anio', 'anio');

    $('#modalGenerarPeriodoAnio').modal('show');
}

function generarPeriodoAnio() {
    loaderShow('#modalGenerarPeriodoAnio');

    ax.setAccion("generarPeriodoAnio");
    ax.addParamTmp("anio", select2.obtenerValor('cboAnio'));
    ax.addParamTmp("empresaId", commonVars.empresa);
    ax.consumir();
}

function onResponseGenerarPeriodoAnio(data) {
    $('#modalGenerarPeriodoAnio').modal('hide');
    if (isEmpty(data) || data == 0) {
        var comentario = 'No se registraron periodos.';
        mostrarAdvertencia(comentario);
    } else {
        var comentario = 'Se registraron ' + data + ' periodos.';
        mostrarOk(comentario);
    }
    listarPeriodo();
}